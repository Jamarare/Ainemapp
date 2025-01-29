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
define( 'DB_NAME', 'd132377sd582023' );

/** Database username */
define( 'DB_USER', 'd132377sa529612' );

/** Database password */
define( 'DB_PASSWORD', '76Ew5wXk3D8g5N5SE' );

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
define('AUTH_KEY',         '6OgVTorl5GCwF3D2cxm4kSzDEtedyCMgLtHNa3Se3tbsgg2XekgcW0BMsIj6PiK8');
define('SECURE_AUTH_KEY',  'rjIzJ8UVrSGwv434UoAAbLW4gRF4EuL3TpFeQ3Cu6kKEizqmIL6Ml9f3vgii995a');
define('LOGGED_IN_KEY',    'u1DddDA4RULFCGkdWzzr7DRzFkFuCqgXj2MJBOBbqAU7w9awcAbABPZDFiZLa3dF');
define('NONCE_KEY',        '4ujqA0hz10cvQNwUh9vkprjcchM3rTyGF5vNNTELxBjPAOKn8rquodDre00lnmiI');
define('AUTH_SALT',        '475QzHGiVBjxD12UxUWgyG3fQJ8qYMiXbqaXiiop9iW46RHd7M1eCkee54n8rzN2');
define('SECURE_AUTH_SALT', 'buZrJDc5sJGMXY1G4lgIwbk7ZBZodYnZHw0UUbJLIeKap2HbyYX4WruiW8gVkFGb');
define('LOGGED_IN_SALT',   'xMcmFoxwGnPtKrgxDWTWiNRKSH83hIOyshr1cDh4qyHRIUZkPVt1n8G989XTwwyu');
define('NONCE_SALT',       'aueLHboOUGISRPMqe5y5FQLqRM1dsvwe0ErKaT6HAvJR9F9cSBRIX0RTsraO07SF');

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
$table_prefix = 'fim2_';

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
