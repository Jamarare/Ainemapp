<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'd132377sd587032' );

/** Database username */
define( 'DB_USER', 'd132377sa534359' );

/** Database password */
define( 'DB_PASSWORD', 'NfR24Ht5sd43U9L6J' );

/** Database hostname */
define( 'DB_HOST', 'd132377.mysql.zonevs.eu' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8Kq4EMTZM5ONSTHdFqGP5DIh4klhTfPQ0KDVHMKmR9BlNZ3kB8KxNeOXdPi1sWn1');
define('SECURE_AUTH_KEY',  'NAXqbwUVD164cwqpZlKJEwj6Dj7eQjOGAZnsLAaiB2GzicuuKSTuEpARcvFLXZOV');
define('LOGGED_IN_KEY',    'w2gnlEMqQv855l8AGo9UPoqnNalcILL0hYAgX6Xm61YEzVrcdv9ElWfCd5yGl3XL');
define('NONCE_KEY',        'qwdNkJYS0U8THWSKHPN3KqCcOKGEMVMqjOHzVGGWn04Zxc1NLfoSU1ED2lFY0Nfg');
define('AUTH_SALT',        '3G8fYROLQdFQsvwskAiCjKmDAH6QE0mMUBoXdG8DqpjGxZwhyjNQodYlpyhGe44R');
define('SECURE_AUTH_SALT', 'MrwbRIR7KavRDPmnq6sEO0Z2dJMrMeyhO2Jk2aE6xOxLKL1kTqgo49Ks41GeFJak');
define('LOGGED_IN_SALT',   '2bk4lWx8wdu0GqjA4l5eGgyoQgPsUENVS7aeppNKfR8zDIedbcW8MJ9P7Mmq027S');
define('NONCE_SALT',       'rrml2LtTgpZhwC1XgSwsMTQlG2pZ2ZS6VtLJCsUpAQ3Bd0JyZX9sQ4M4Ses4kOe6');

/**
 * Other customizations.
 */
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'vk7n_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
