<?php

if (!defined('ABSPATH')) die('No direct access.');

global $updraftcentral_host_plugin;
if (!$updraftcentral_host_plugin->is_host_dir_set()) die('No access.');

// This file is included during plugins_loaded

// Load the listener class that we rely on to pick up messages
if (!class_exists('UpdraftCentral_Listener')) require_once('listener.php');

// We exit if class already exists. More common if two or more plugins integrated
// the same `UpdraftCentral` client folder.
if (!class_exists('UpdraftCentral_Main')) :

class UpdraftCentral_Main {

	/**
	 * Class constructor
	 */
	public function __construct() {

		add_action('udrpc_log', array($this, 'udrpc_log'), 10, 3);
		
		add_action('wp_ajax_updraftcentral_receivepublickey', array($this, 'wp_ajax_updraftcentral_receivepublickey'));
		add_action('wp_ajax_nopriv_updraftcentral_receivepublickey', array($this, 'wp_ajax_updraftcentral_receivepublickey'));
	
		// The host plugin's command class is registered in its "plugins_loaded" method (e.g. UpdraftPlus::plugins_loaded()).
		//
		// N.B. The new filter "updraftcentral_remotecontrol_command_classes" was introduced on Jan. 2021 and will soon replace the
		// old filter "updraftplus_remotecontrol_command_classes" (below). This was done in order to synchronize all available filters
		// and actions related to UpdraftCentral so that we can easily port the UpdraftCentral client code into our other plugins.
		//
		// If you happened to use the old filter from any of your projects then you might as well update it with the new filter as the
		// old filter has already been marked as deprecated, though currently supported as can be seen below but will soon be remove
		// from this code block.
		$command_classes = apply_filters('updraftcentral_remotecontrol_command_classes', array(
			'core' => 'UpdraftCentral_Core_Commands',
			'updates' => 'UpdraftCentral_Updates_Commands',
			'users' => 'UpdraftCentral_Users_Commands',
			'comments' => 'UpdraftCentral_Comments_Commands',
			'analytics' => 'UpdraftCentral_Analytics_Commands',
			'plugin' => 'UpdraftCentral_Plugin_Commands',
			'theme' => 'UpdraftCentral_Theme_Commands',
			'posts' => 'UpdraftCentral_Posts_Commands',
			'media' => 'UpdraftCentral_Media_Commands',
			'pages' => 'UpdraftCentral_Pages_Commands'
		));
	
		// N.B. This "updraftplus_remotecontrol_command_classes" filter has been marked as deprecated and will be remove after May 2021.
		// Please see above code comment for further explanation and its alternative.
		$command_classes = apply_filters('updraftplus_remotecontrol_command_classes', $command_classes);
	
		// If nothing was sent, then there is no incoming message, so no need to set up a listener (or CORS request, etc.). This avoids a DB SELECT query on the option below in the case where it didn't get autoloaded, which is the case when there are no keys.
		if (!empty($_SERVER['REQUEST_METHOD']) && ('GET' == $_SERVER['REQUEST_METHOD'] || 'POST' == $_SERVER['REQUEST_METHOD']) && (empty($_REQUEST['action']) || 'updraft_central' !== $_REQUEST['action']) && empty($_REQUEST['udcentral_action']) && empty($_REQUEST['udrpc_message'])) return;
		
		// Remote control keys
		// These are different from the remote send keys, which are set up in the Migrator add-on
		$our_keys = $this->get_central_localkeys();
		
		if (is_array($our_keys) && !empty($our_keys)) {
			new UpdraftCentral_Listener($our_keys, $command_classes);
		}

	}

	/**
	 * Enqueues the needed styles and scripts for UpdraftCentral
	 *
	 * @return void
	 */
	public function enqueue_central_scripts() {
		
		// This is an additional check; the caller is assumed to have already run checks before painting its page in general
		if (!current_user_can('manage_options')) return;

		global $updraftcentral_host_plugin;
		$version = $updraftcentral_host_plugin->get_version();

		$enqueue_version = (defined('WP_DEBUG') && WP_DEBUG) ? $version.'.'.time() : $version;
		$min_or_not = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

		// Fallback to unminified version if the minified version is not found.
		if (!empty($min_or_not) && !file_exists(UPDRAFTCENTRAL_CLIENT_DIR.'/js/central'.$min_or_not.'.js')) {
			$min_or_not = '';
		}

		wp_enqueue_script('updraft-central', UPDRAFTCENTRAL_CLIENT_URL.'/js/central'.$min_or_not.'.js', array(), $enqueue_version);
		wp_enqueue_style('updraft-central', UPDRAFTCENTRAL_CLIENT_URL.'/css/central'.$min_or_not.'.css', array(), $enqueue_version);

		$localize = array_merge(
			array(
				'central_url' => UPDRAFTCENTRAL_CLIENT_URL,
				'plugin_name' => $updraftcentral_host_plugin->get_plugin_name(),
				'updraftcentral_request_nonce' => wp_create_nonce('updraftcentral-request-nonce'),
			),
			$updraftcentral_host_plugin->translations
		);

		wp_localize_script('updraft-central', 'uclion', apply_filters('updraftcentral_uclion', $localize));
	}
	
	/**
	 * Retrieves current clean url for anchor link where href attribute value is not url (for ex. #div) or empty. Output is not escaped (caller should escape).
	 *
	 * @return String - current clean url
	 */
	public function get_current_clean_url() {
	
		// Within an UpdraftCentral context, there should be no prefix on the anchor link
		if (defined('UPDRAFTCENTRAL_COMMAND') && UPDRAFTCENTRAL_COMMAND || defined('WP_CLI') && WP_CLI) return '';
		
		if (defined('DOING_AJAX') && DOING_AJAX && !empty($_SERVER['HTTP_REFERER'])) {
			$current_url = $_SERVER['HTTP_REFERER'];
		} else {
			$url_prefix = is_ssl() ? 'https' : 'http';
			$host = empty($_SERVER['HTTP_HOST']) ? parse_url(network_site_url(),  PHP_URL_HOST) : $_SERVER['HTTP_HOST'];
			$current_url = $url_prefix."://".$host.wp_unslash($_SERVER['REQUEST_URI']);
		}
		$remove_query_args = array('state', 'action', 'oauth_verifier', 'nonce', 'updraftplus_instance', 'access_token', 'user_id', 'updraftplus_googledriveauth');

		$query_string = remove_query_arg($remove_query_args, $current_url);
		return function_exists('wp_unslash') ? wp_unslash($query_string) : stripslashes_deep($query_string);
	}
	
	/**
	 * Get the WordPress version
	 *
	 * @return String - the version
	 */
	public function get_wordpress_version() {
		static $got_wp_version = false;
		if (!$got_wp_version) {
			@include(ABSPATH.WPINC.'/version.php');// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise because of the function.
			$got_wp_version = $wp_version;// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- The variable is defined inside the ABSPATH.WPINC.'/version.php'.
		}
		return $got_wp_version;
	}

	/**
	 * Retrieves the UpdraftCentral generated keys
	 *
	 * @param Mixed $default default value to return when option is not found
	 *
	 * @return Mixed
	 */
	private function get_central_localkeys($default = null) {

		$option = 'updraft_central_localkeys';
		$ret = get_option($option, $default);
		return apply_filters('updraftcentral_get_option', $ret, $option, $default);
	}

	/**
	 * Updates the UpdraftCentral's keys
	 *
	 * @param string $value	    Specify option value
	 * @param bool   $use_cache Whether or not to use the WP options cache
	 * @param string $autoload	Whether to autoload (only takes effect on a change of value)
	 *
	 * @return bool
	 */
	private function update_central_localkeys($value, $use_cache = true, $autoload = 'yes') {
		$option = 'updraft_central_localkeys';

		return update_option($option, apply_filters('updraftcentral_update_option', $value, $option, $use_cache), $autoload);
	}
	
	/**
	 * Receive a new public key in $_GET, and echo a response. Will die() if called.
	 */
	public function wp_ajax_updraftcentral_receivepublickey() {
		global $updraftcentral_host_plugin;
	
		// The actual nonce check is done in the method below
		if (empty($_GET['_wpnonce']) || empty($_GET['public_key']) || !isset($_GET['updraft_key_index'])) die;
		
		$result = $this->receive_public_key();
		if (!is_array($result) || empty($result['responsetype'])) die;

		?>
		<html>
			<head>
				<title>UpdraftCentral</title>
				<style>
					body {text-align: center;font-family: Helvetica,Arial,Lucida,sans-serif;background-color: #A64C1A;color: #FFF;height: 100%;width: 100%;margin: 0;padding: 0;}#main {height: 100%;width: 100%;display: table;}#wrapper {display: table-cell;height: 100%;vertical-align: middle;}h1 {margin-bottom: 5px;}h2 {margin-top: 0;font-size: 22px;color: #FFF;}#btn-close {color: #FFF;font-size: 20px;font-weight: 500;padding: .3em 1em;line-height: 1.7em !important;background-color: transparent;background-size: cover;background-position: 50%;background-repeat: no-repeat;border: 2px solid;border-radius: 3px;-webkit-transition-duration: .2s;transition-duration: .2s;-webkit-transition-property: all !important;transition-property: all !important;text-decoration: none;}#btn-close:hover {background-color: #DE6726;}
				</style>
			</head>
			<body>
				<div id="main">
					<div id="wrapper"><img src="<?php echo esc_url(UPDRAFTCENTRAL_CLIENT_URL).'/images/ud-logo.png'; ?>" width="60" /> <h1><?php $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_connection', true); ?></h1><h2><?php echo esc_html(network_site_url()); ?></h2><p>
		<?php
		if ('ok' == $result['responsetype']) {
			$updraftcentral_host_plugin->retrieve_show_message('updraftcentral_connection_successful', true);
		} else {
			?>
			<strong><span id="udc-connect-failed">
				<?php $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_connection_failed', true); ?>
			</span></strong><br>
			<?php
			switch ($result['code']) {
				case 'unknown_key':
					$updraftcentral_host_plugin->retrieve_show_message('unknown_key', true);
					break;
				case 'not_logged_in':
					echo esc_html($updraftcentral_host_plugin->retrieve_show_message('not_logged_in')).' ';
					$updraftcentral_host_plugin->retrieve_show_message('must_visit_url', true);
					break;
				case 'nonce_failure':
					$updraftcentral_host_plugin->retrieve_show_message('security_check', true);
					$updraftcentral_host_plugin->retrieve_show_message('must_visit_link', true);
					break;
				case 'already_have':
					$updraftcentral_host_plugin->retrieve_show_message('connection_already_made', true);
					break;
				case 'insufficient_privilege':
					$updraftcentral_host_plugin->retrieve_show_message('insufficient_privilege', true);
					break;
				default:
					echo esc_html(print_r($result, true));
					break;
			}
		}
		?>
		</p>
		<p><a id="btn-close" href="<?php echo esc_url($this->get_current_clean_url()); ?>" onclick="window.close();"><?php $updraftcentral_host_plugin->retrieve_show_message('close', true); ?></a>
		</p></div></div>
		<?php
		die;
	}
	
	/**
	 * Checks _wpnonce, and if successful, saves the public key found in $_GET
	 *
	 * @return Array - with keys responsetype (can be 'error' or 'ok') and code, indicating whether the parse was successful
	 */
	private function receive_public_key() {
		
		if (!is_user_logged_in()) {
			return array('responsetype' => 'error', 'code' => 'not_logged_in');
		}

		if (!wp_verify_nonce($_GET['_wpnonce'], 'updraftcentral_receivepublickey')) return array('responsetype' => 'error', 'code' => 'nonce_failure');
		
		$updraft_key_index = $_GET['updraft_key_index'];
		$our_keys = $this->get_central_localkeys();

		if (!is_array($our_keys)) $our_keys = array();
		
		if (!isset($our_keys[$updraft_key_index])) {
			return array('responsetype' => 'error', 'code' => 'unknown_key');
		}

		if (!empty($our_keys[$updraft_key_index]['publickey_remote'])) {
			return array('responsetype' => 'error', 'code' => 'already_have');
		}
		
		$our_keys[$updraft_key_index]['publickey_remote'] = base64_decode(stripslashes($_GET['public_key']));
		$this->update_central_localkeys($our_keys, true, 'no');
		
		return array('responsetype' => 'ok', 'code' => 'ok');
	}
	
	/**
	 * Action parameters, from udrpc: $message, $level, $this->key_name_indicator, $this->debug, $this
	 *
	 * @param  string $message			  The log message
	 * @param  string $level			  Log level
	 * @param  string $key_name_indicator This indicates the key name
	 */
	public function udrpc_log($message, $level, $key_name_indicator) {

		$udrpc_log = get_site_option('updraftcentral_client_log');
		if (!is_array($udrpc_log)) $udrpc_log = array();
		
		$new_item = array(
			'time' => time(),
			'level' => $level,
			'message' => $message,
			'key_name_indicator' => $key_name_indicator
		);
		
		if (!empty($_SERVER['REMOTE_ADDR'])) {
			$new_item['remote_ip'] = $_SERVER['REMOTE_ADDR'];
		}
		if (!empty($_SERVER['HTTP_USER_AGENT'])) {
			$new_item['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		}
		if (!empty($_SERVER['HTTP_X_SECONDARY_USER_AGENT'])) {
			$new_item['http_secondary_user_agent'] = $_SERVER['HTTP_X_SECONDARY_USER_AGENT'];
		}
		
		$udrpc_log[] = $new_item;
		
		if (count($udrpc_log) > 50) array_shift($udrpc_log);
		
		update_site_option('updraftcentral_client_log', $udrpc_log);
	}
	
	/**
	 * Delete UpdraftCentral Key
	 *
	 * @param array $key_id key_id of UpdraftCentral
	 *
	 * @return array which contains deleted flag and key table. If error, Returns array which contains fatal_error flag and fatal_error_message
	 */
	public function delete_key($key_id) {
		
		$our_keys = $this->get_central_localkeys();
		if (is_array($key_id) && isset($key_id['key_id'])) {
			$key_id = $key_id['key_id'];
		}

		if (!is_array($our_keys)) $our_keys = array();
		if (isset($our_keys[$key_id])) {
			unset($our_keys[$key_id]);
			$this->update_central_localkeys($our_keys);
		}
		return array('deleted' => 1, 'keys_table' => $this->get_keys_table());
	}
	
	/**
	 * Get UpdraftCentral Log
	 *
	 * @return array which contains log_contents. If error, Returns array which contains fatal_error flag and fatal_error_message
	 */
	public function get_log() {

		global $updraftcentral_host_plugin;
	
		$udrpc_log = get_site_option('updraftcentral_client_log');
		if (!is_array($udrpc_log)) $udrpc_log = array();
		
		$log_contents = '';
		
		// Events are appended to the array in the order they happen. So, reversing the order gets them into most-recent-first order.
		rsort($udrpc_log);
		
		if (empty($udrpc_log)) {
			$log_contents = '<em>'.$updraftcentral_host_plugin->retrieve_show_message('nothing_yet_logged').'</em>';
		}
		
		foreach ($udrpc_log as $m) {
		
			// Skip invalid data
			if (!isset($m['time'])) continue;

			$time = gmdate('Y-m-d H:i:s O', $m['time']);
			// $level is not used yet. We could put the message in different colours for different levels, if/when it becomes used.
			
			$key_name_indicator = empty($m['key_name_indicator']) ? '' : $m['key_name_indicator'];
			
			$log_contents .= '<span title="'.esc_attr(print_r($m, true)).'">'."$time ";
			
			if (!empty($m['remote_ip'])) $log_contents .= '['.htmlspecialchars($m['remote_ip']).'] ';
			
			$log_contents .= "[".htmlspecialchars($key_name_indicator)."] ".htmlspecialchars($m['message'])."</span>\n";
		}
		
		return array('log_contents' => $log_contents);
	
	}
	
	public function create_key($params) {

		global $updraftcentral_host_plugin;

		// Use the site URL - this means that if the site URL changes, communication ends; which is the case anyway
		$user = wp_get_current_user();
		
		if (!is_object($user) || empty($user->ID)) return array('error' => $updraftcentral_host_plugin->retrieve_show_message('insufficient_privilege'));
		
		if (!current_user_can('manage_options')) return array('error' => $updraftcentral_host_plugin->retrieve_show_message('insufficient_privilege'));
		
		$where_send = empty($params['where_send']) ? '' : (string) $params['where_send'];
		
		if ('__updraftpluscom' != $where_send) {
			$purl = parse_url($where_send);
			if (empty($purl) || !array($purl) || empty($purl['scheme']) || empty($purl['host'])) return array('error' => $updraftcentral_host_plugin->retrieve_show_message('invalid_url'));
		}

		// ENT_HTML5 exists only on PHP 5.4+
		// @codingStandardsIgnoreLine
		$flags = defined('ENT_HTML5') ? ENT_QUOTES | ENT_HTML5 : ENT_QUOTES;
		
		$extra_info = array(
			'user_id' => $user->ID,
			'user_login' => $user->user_login,
			'ms_id' => get_current_blog_id(),
			'site_title' => html_entity_decode(get_bloginfo('name'), $flags),
		);

		if ($where_send) {
			$extra_info['mothership'] = $where_send;
			if (!empty($params['mothership_firewalled'])) {
				$extra_info['mothership_firewalled'] = true;
			}
		}

		if (!empty($params['key_description'])) {
			$extra_info['name'] = (string) $params['key_description'];
		}

		$key_size = (empty($params['key_size']) || !is_numeric($params['key_size']) || $params['key_size'] < 512) ? 2048 : (int) $params['key_size'];
		
		$extra_info['key_size'] = $key_size;
		
		$created = $this->create_remote_control_key(false, $extra_info, $where_send);

		if (is_array($created)) {
			$created['keys_table'] = $this->get_keys_table();

			$created['keys_guide'] = '<h2 class="updraftcentral_wizard_success">'. $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_key_created') .'</h2>';

			if ('__updraftpluscom' != $where_send) {
				$created['keys_guide'] .= '<div class="updraftcentral_wizard_success"><p>'.sprintf($updraftcentral_host_plugin->retrieve_show_message('need_to_copy_key'), '<a href="'.$where_send.'" target="_blank">UpdraftCentral dashboard</a>').'</p><p>'.$updraftcentral_host_plugin->retrieve_show_message('press_add_site_button').'</p><p>'.sprintf($updraftcentral_host_plugin->retrieve_show_message('detailed_instructions'), '<a target="_blank" href="https://updraftplus.com/updraftcentral-how-to-add-a-site/">UpdraftPlus.com</a>').'</p></div>';
			} else {
				$created['keys_guide'] .= '<div class="updraftcentral_wizard_success"><p>'. sprintf($updraftcentral_host_plugin->retrieve_show_message('control_this_site'), '<a target="_blank" href="https://updraftplus.com/my-account/updraftcentral-remote-control/">UpdraftPlus.com</a>').'</p></div>';
			}
		}
		
		return $created;
	}

	/**
	 * Given an index, return the indicator name
	 *
	 * @param String $index
	 *
	 * @return String
	 */
	private function indicator_name_from_index($index) {
		return $index.'.central.updraftplus.com';
	}
	
	/**
	 * Gets an RPC object, and sets some defaults on it that we always want
	 *
	 * @param  string $indicator_name indicator name
	 *
	 * @return array
	 */
	public function get_udrpc($indicator_name = 'migrator.updraftplus.com') {

		global $updraftcentral_host_plugin, $updraftplus;

		$updraftplus->ensure_phpseclib();
		
		if (!class_exists('UpdraftPlus_Remote_Communications_V2')) include_once($updraftcentral_host_plugin->get_host_dir().'/vendor/team-updraft/common-libs/src/updraft-rpc/class-udrpc2.php');
		$ud_rpc = new UpdraftPlus_Remote_Communications_V2($indicator_name);
		$ud_rpc->set_can_generate(true);
		
		return $ud_rpc;
	}
	
	private function create_remote_control_key($index = false, $extra_info = array(), $post_it = false) {
		global $updraftcentral_host_plugin;

		$our_keys = $this->get_central_localkeys();
		if (!is_array($our_keys)) $our_keys = array();
		
		if (false === $index) {
			if (empty($our_keys)) {
				$index = 0;
			} else {
				$index = max(array_keys($our_keys))+1;
			}
		}
		
		$name_hash = $index;
		
		if (isset($our_keys[$name_hash])) {
			unset($our_keys[$name_hash]);
		}

		$indicator_name = $this->indicator_name_from_index($name_hash);
		$ud_rpc = $this->get_udrpc($indicator_name);

		if ('__updraftpluscom' == $post_it) {
			$post_it = defined('UPDRAFTPLUS_OVERRIDE_UDCOM_DESTINATION') ? UPDRAFTPLUS_OVERRIDE_UDCOM_DESTINATION : 'https://updraftplus.com/?updraftcentral_action=receive_key';
			$post_it_description = 'UpdraftPlus.Com';
		} else {
			$post_it_description = $post_it;
		}
		
		// Normally, key generation takes seconds, even on a slow machine. However, some Windows machines appear to have a setup in which it takes a minute or more. And then, if you're on a double-localhost setup on slow hardware - even worse. It doesn't hurt to just raise the maximum execution time.
		
		if (function_exists('set_time_limit')) @set_time_limit(UPDRAFTCENTRAL_SET_TIME_LIMIT);// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Silenced to suppress errors that may arise because of the function.
		
		$key_size = (empty($extra_info['key_size']) || !is_numeric($extra_info['key_size']) || $extra_info['key_size'] < 512) ? 2048 : (int) $extra_info['key_size'];

		if (is_object($ud_rpc) && $ud_rpc->generate_new_keypair($key_size)) {
		
			if ($post_it && empty($extra_info['mothership_firewalled'])) {
			
				$p_url = parse_url($post_it);
				if (is_array($p_url) && !empty($p_url['user'])) {
					$http_username = $p_url['user'];
					$http_password = empty($p_url['pass']) ? '' : $p_url['pass'];
					$post_it = $p_url['scheme'].'://'.$p_url['host'];
					if (!empty($p_url['port'])) $post_it .= ':'.$p_url['port'];
					$post_it .= $p_url['path'];
					if (!empty($p_url['query'])) $post_it .= '?'.$p_url['query'];
				}
				
				$post_options = array(
					'timeout' => 90,
					'body' => array(
						'updraftcentral_action' => 'receive_key',
						'key' => $ud_rpc->get_key_remote()
					)
				);
				
				if (!empty($http_username)) {
					$post_options['headers'] = array(
						'Authorization' => 'Basic '.base64_encode($http_username.':'.$http_password)
					);
				}
			
				// This option allows the key to be sent to the other side via a known-secure channel (e.g. http over SSL), rather than potentially allowing it to travel over an unencrypted channel (e.g. http back to the user's browser). As such, if specified, it is compulsory for it to work.
				
				$updraftcentral_host_plugin->register_wp_http_option_hooks();

				$sent_key = wp_remote_post(
					$post_it,
					$post_options
				);
				
				$updraftcentral_host_plugin->register_wp_http_option_hooks(false);
				
				$connection_troubleshooting_url = 'https://updraftplus.com/troubleshooting-updraftcentral-connection-issues/';
				if (is_wp_error($sent_key) || empty($sent_key)) {

					$err_msg = sprintf($updraftcentral_host_plugin->retrieve_show_message('attempt_to_register_failed'), (string) $post_it_description, $connection_troubleshooting_url);
					if (is_wp_error($sent_key)) $err_msg .= ' '.$sent_key->get_error_message().' ('.$sent_key->get_error_code().')';
					return array(
						'r' => $err_msg
					);
				}
				
				$response = json_decode(wp_remote_retrieve_body($sent_key), true);

				if (!is_array($response) || !isset($response['key_id']) || !isset($response['key_public'])) {
					return array(
						'r' => sprintf($updraftcentral_host_plugin->retrieve_show_message('attempt_to_register_failed'), (string) $post_it_description, $connection_troubleshooting_url),
						'raw' => wp_remote_retrieve_body($sent_key)
					);
				}
				
				$key_hash = hash('sha256', $ud_rpc->get_key_remote());

				$local_bundle = $ud_rpc->get_portable_bundle('base64_with_count', $extra_info, array('key' => array('key_hash' => $key_hash, 'key_id' => $response['key_id'])));

			} elseif ($post_it) {
				// Don't send; instead, include in the bundle info that the mothership is firewalled; this will then tell the mothership to try the reverse connection instead

				if (is_array($extra_info)) {
					$extra_info['mothership_firewalled_callback_url'] = wp_nonce_url(admin_url('admin-ajax.php'), 'updraftcentral_receivepublickey');
					$extra_info['updraft_key_index'] = $index;
				}

				
				$local_bundle = $ud_rpc->get_portable_bundle('base64_with_count', $extra_info, array('key' => $ud_rpc->get_key_remote()));
			}
		

			if (isset($extra_info['name'])) {
				$name = (string) $extra_info['name'];
				unset($extra_info['name']);
			} else {
				$name = 'UpdraftCentral Remote Control';
			}
		
			$our_keys[$name_hash] = array(
				'name' => $name,
				'key' => $ud_rpc->get_key_local(),
				'extra_info' => $extra_info,
				'created' => time(),
			);
			// Store the other side's public key
			if (!empty($response) && is_array($response) && !empty($response['key_public'])) {
				$our_keys[$name_hash]['publickey_remote'] = $response['key_public'];
			}
			$this->update_central_localkeys($our_keys, true, 'no');

			return array(
				'bundle' => $local_bundle,
				'r' => $updraftcentral_host_plugin->retrieve_show_message('key_created_successfully').' '.$updraftcentral_host_plugin->retrieve_show_message('copy_paste_key'),
			);
		}

		return false;

	}
	
	/**
	 * Get the HTML for the keys table
	 *
	 * @param Boolean $echo_instead_of_return Whether the result should be echoed or returned
	 * @return String
	 */
	public function get_keys_table($echo_instead_of_return = false) {
		
		// This is an additional check - it implies requirement for a dashboard context
		if (!current_user_can('manage_options')) return;

		global $updraftcentral_host_plugin;

		if (!$echo_instead_of_return) ob_start();
			
		$our_keys = $this->get_central_localkeys();
		if (!is_array($our_keys)) $our_keys = array();

		if (empty($our_keys)) {
			?>
			<tr><td colspan="2"><em><?php $updraftcentral_host_plugin->retrieve_show_message('no_updraftcentral_dashboards', true); ?></em></td></tr>
			<?php
		}
		
		?>
		<div id="updraftcentral_keys_content" style="margin: 10px 0;">
			<?php if (!empty($our_keys)) { ?>
				<a href="<?php echo esc_url($this->get_current_clean_url()); ?>" class="updraftcentral_keys_show hidden-in-updraftcentral"><?php echo wp_kses_post(sprintf($updraftcentral_host_plugin->retrieve_show_message('manage_keys'), count($our_keys))); ?></a>
			<?php } ?>
			<table id="updraftcentral_keys_table">
				<thead>
					<tr>
						<th style="text-align:left;"><?php $updraftcentral_host_plugin->retrieve_show_message('key_description', true); ?></th>
						<th style="text-align:left;"><?php $updraftcentral_host_plugin->retrieve_show_message('details', true); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					
					foreach ($our_keys as $i => $key) {
		
						if (empty($key['extra_info'])) continue;
						
						$user_id = $key['extra_info']['user_id'];
						
						if (!empty($key['extra_info']['mothership'])) {
						
							$mothership_url = $key['extra_info']['mothership'];
							
							if ('__updraftpluscom' == $mothership_url) {
								$reconstructed_url = 'https://updraftplus.com';
							} else {
								$purl = parse_url($mothership_url);
								$path = empty($purl['path']) ? '' : $purl['path'];
								
								$reconstructed_url = $purl['scheme'].'://'.$purl['host'].(!empty($purl['port']) ? ':'.$purl['port'] : '').$path;
							}
							
						} else {
							$reconstructed_url = $updraftcentral_host_plugin->retrieve_show_message('unknown');
						}
					
						$name = $key['name'];
						
						$user = get_user_by('id', $user_id);
						
						$user_display = is_a($user, 'WP_User') ? $user->user_login.' ('.$user->user_email.')' : $updraftcentral_host_plugin->retrieve_show_message('unknown');
						?>
						<tr class="updraft_debugrow"><td style="vertical-align:top;"><?php echo esc_html($name.' ('.$i.')'); ?></td><td><?php $updraftcentral_host_plugin->retrieve_show_message('access_as_user', true); ?> <?php echo esc_html($user_display); ?> <br> <?php $updraftcentral_host_plugin->retrieve_show_message('public_key_sent', true); ?> <?php echo esc_html($reconstructed_url); ?><br>
						<?php
						if (!empty($key['created'])) {
							echo esc_html($updraftcentral_host_plugin->retrieve_show_message('created').' '.date_i18n(get_option('date_format').' '.get_option('time_format'), $key['created'])).'.';
							if (!empty($key['extra_info']['key_size'])) {
								echo ' '.esc_html(sprintf($updraftcentral_host_plugin->retrieve_show_message('key_size'), $key['extra_info']['key_size'])).'.';
							}
							?>
							<br>
							<?php
						}
						?>
						<a href="<?php echo esc_url($this->get_current_clean_url()); ?>" data-key_id="<?php echo esc_attr($i); ?>" class="updraftcentral_key_delete"><?php $updraftcentral_host_plugin->retrieve_show_message('delete', true); ?></a></td></tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
		if (!$echo_instead_of_return) return ob_get_clean();
	}

	/**
	 * Return HTML markup for the 'create key' section
	 *
	 * @param Boolean $echo_instead_of_return Whether the result should be echoed or returned
	 * @return String|Void - the HTML
	 */
	private function create_key_markup($echo_instead_of_return = false) {
		global $updraftcentral_host_plugin;

		if (!$echo_instead_of_return) ob_start();
		?> 
		<div class="create_key_container"> 
			<h4 class="updraftcentral_wizard_stage1"> <?php $updraftcentral_host_plugin->retrieve_show_message('connect_to_updraftcentral_dashboard', true); ?></h4>
			<table style="width: 100%; table-layout:fixed;"> 
				<thead></thead> 
				<tbody>
					<tr class="updraftcentral_wizard_stage1">
						<td>
							<div class="updraftcentral_wizard_mothership updraftcentral_wizard_option">
								<label class="button-primary" tabindex="0">
									<input checked="checked" type="radio" name="updraftcentral_mothership" id="updraftcentral_mothership_updraftpluscom" style="display: none;">
									UpdraftPlus.Com
								</label><br>
								<div><?php echo wp_kses_post(sprintf(esc_html($updraftcentral_host_plugin->retrieve_show_message('in_example')), '<a target="_blank" href="https://updraftplus.com/my-account/">'.esc_html($updraftcentral_host_plugin->retrieve_show_message('an_account')).'</a>')); ?></div>

							</div>
							<div class="updraftcentral_wizard_self_hosted_stage1 updraftcentral_wizard_option">
								<label class="button-primary" tabindex="0">
									<input type="radio" name="updraftcentral_mothership" id="updraftcentral_mothership_other" style="display: none;">
									<?php $updraftcentral_host_plugin->retrieve_show_message('self_hosted_dashboard', true);?>
								</label><br>
								<div><?php echo wp_kses_post(sprintf(esc_html($updraftcentral_host_plugin->retrieve_show_message('website_installed')), '<a target="_blank" href="https://wordpress.org/plugins/updraftcentral/">UpdraftCentral</a>')); ?></div>
							</div>
							<div class="updraftcentral_wizard_self_hosted_stage2" style="float:left; clear:left;display:none;">
								<p style="font-size: 13px;"><?php $updraftcentral_host_plugin->retrieve_show_message('enter_url', true); ?></p>
								<p style="font-size: 13px;" id="updraftcentral_wizard_stage1_error"></p>
								<input disabled="disabled" id="updraftcentral_keycreate_mothership" type="text" size="40" placeholder="<?php $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_dashboard_url', true); ?>" value="">
								<button type="button" class="button button-primary" id="updraftcentral_stage2_go"><?php $updraftcentral_host_plugin->retrieve_show_message('next', true); ?></button>
							</div>
						</td>
					</tr>

					<tr class="updraft_debugrow updraftcentral_wizard_stage2" style="display: none;">
						<h4 class="updraftcentral_wizard_stage2" style="display: none;"><?php $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_connection_details', true); ?></h4>
						<td class="updraftcentral_keycreate_description">
							<?php $updraftcentral_host_plugin->retrieve_show_message('description', true); ?>:
							<input id="updraftcentral_keycreate_description" type="text" size="20" placeholder="<?php $updraftcentral_host_plugin->retrieve_show_message('enter_description', true); ?>" value="" >
						</td>
					</tr>

					<tr class="updraft_debugrow updraftcentral_wizard_stage2" style="display: none;">
						<td>
							<?php $updraftcentral_host_plugin->retrieve_show_message('encryption_key_size', true); ?>
							<select style="" id="updraftcentral_keycreate_keysize">
								<option value="512"><?php echo wp_kses_post(sprintf($updraftcentral_host_plugin->retrieve_show_message('bits').' - '.$updraftcentral_host_plugin->retrieve_show_message('easy_to_break'), '512')); ?></option>
								<option value="1024"><?php echo wp_kses_post(sprintf($updraftcentral_host_plugin->retrieve_show_message('bits').' - '.$updraftcentral_host_plugin->retrieve_show_message('faster'), '1024')); ?></option>
								<option value="2048" selected="selected"><?php echo wp_kses_post(sprintf($updraftcentral_host_plugin->retrieve_show_message('bytes').' - '.$updraftcentral_host_plugin->retrieve_show_message('recommended'), '2048')); ?></option>
								<option value="4096"><?php echo wp_kses_post(sprintf($updraftcentral_host_plugin->retrieve_show_message('bits').' - '.$updraftcentral_host_plugin->retrieve_show_message('slower'), '4096')); ?></option>
							</select>
							<br>
							<div id="updraftcentral_keycreate_mothership_firewalled_container">
								<label>
									<input id="updraftcentral_keycreate_mothership_firewalled" type="checkbox">
									<?php $updraftcentral_host_plugin->retrieve_show_message('use_alternative_method', true); ?>
									<a href="<?php echo esc_url($this->get_current_clean_url()); ?>" id="updraftcentral_keycreate_altmethod_moreinfo_get"><?php $updraftcentral_host_plugin->retrieve_show_message('more_information', true); ?></a>
									<p id="updraftcentral_keycreate_altmethod_moreinfo" style="display:none; border: 1px dotted; padding: 3px; margin: 2px 10px 2px 24px;">
										<em><?php $updraftcentral_host_plugin->retrieve_show_message('this_is_useful', true);?></em>
									</p>
								</label>
							</div>
						</td>
					</tr>

					<tr class="updraft_debugrow updraftcentral_wizard_stage2" style="display: none;">
						<td>
							<button style="margin-top: 5px;" type="button" class="button button-primary" id="updraftcentral_keycreate_go"><?php $updraftcentral_host_plugin->retrieve_show_message('create', true); ?></button>
						</td>
					</tr>
					<tr class="updraft_debugrow updraftcentral_wizard_stage2" style="display: none;">
						<td>
							<a id="updraftcentral_stage1_go"><?php $updraftcentral_host_plugin->retrieve_show_message('back', true); ?></a>
						</td>
					</tr>
				</tbody>
			</table>
			<div id="updraft-copy-modal" title="<?php esc_html_e('Copy to clipboard', 'updraftplus');?>">
				<p>
					<?php echo esc_html__('Your web browser prevented the copy operation.', 'updraftplus').' '.'<a href="https://updraftplus.com/faqs/how-do-i-set-clipboard-permissions-for-different-browsers/" target="__blank">'.' '.esc_html__('Follow this link to read about how to set browser permission', 'updraftplus').'</a>'; ?>
				</p>
			</div>
		</div>
		<?php
		if (!$echo_instead_of_return) return ob_get_clean();
	}

	/**
	 * Get log event viewer mark-up
	 *
	 * @param Boolean $echo_instead_of_return Whether the result should be echoed or returned
	 * @return String - the HTML
	 */
	private function get_log_markup($echo_instead_of_return = false) {
		global $updraftcentral_host_plugin;

		if (!$echo_instead_of_return) ob_start();
		?>
			<div id="updraftcentral_view_log_container" style="margin: 10px 0;">
				<a href="<?php echo esc_url($this->get_current_clean_url()); ?>" id="updraftcentral_view_log"><?php $updraftcentral_host_plugin->retrieve_show_message('view_log_events', true); ?>...</a><br>
				<pre id="updraftcentral_view_log_contents" style="min-height: 110px; padding: 0 4px;">
				</pre>
			</div>
		<?php
		if (!$echo_instead_of_return) return ob_get_clean();
	}
	
	/**
	 * Echo the debug-tools dashboard HTML. Called by the WP action updraftplus_debugtools_dashboard.
	 */
	public function debugtools_dashboard() {
		
		$this->enqueue_central_scripts();

		global $updraftcentral_host_plugin;

		$including_desc = '';
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			$hosts = apply_filters('updraftcentral_host_plugins', array());
			$includes = $updraftcentral_host_plugin->retrieve_show_message('including_description');

			foreach ($hosts as $plugin) {
				if (false !== stripos($screen->id, $plugin)) {
					$key = str_replace('-', '_', strtolower($plugin)).'_desc';
					if (isset($includes[$key])) {
						$including_desc = $includes[$key];
						break;
					}
				}
			}
		}

		$updraftcentral_description = preg_replace('/\s+/', ' ', sprintf($updraftcentral_host_plugin->retrieve_show_message('updraftcentral_description'), $including_desc));
	?>
		<div class="advanced_tools updraft_central">
			<h3><?php $updraftcentral_host_plugin->retrieve_show_message('updraftcentral_remote_control', true); ?></h3>
			<p>
				<?php echo esc_html($updraftcentral_description); ?> <a target="_blank" href="https://updraftcentral.com"><?php $updraftcentral_host_plugin->retrieve_show_message('read_more', true); ?></a>
			</p>
			<div style="min-height: 310px;" id="updraftcentral_keys">
				<?php $this->create_key_markup(true); ?>
				<?php $this->get_keys_table(true); ?>
				<button style="display: none;" type="button" class="button button-primary" id="updraftcentral_wizard_go"><?php $updraftcentral_host_plugin->retrieve_show_message('create_another_key', true); ?></button>
				<?php $this->get_log_markup(true); ?>
			</div>
		</div>
	<?php
	}
}

endif;

global $updraftcentral_main;
$updraftcentral_main = new UpdraftCentral_Main();
