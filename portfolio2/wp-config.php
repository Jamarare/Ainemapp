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
define( 'DB_NAME', 'd132377sd582096' );

/** Database username */
define( 'DB_USER', 'd132377sa529685' );

/** Database password */
define( 'DB_PASSWORD', '3pwcv6Q98BJ9B6G2D' );

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
define('AUTH_KEY',         'SYBKFcwJfWx57ioIpT3Dw7LRfuXlcZs6mMBxzDjcTsYz4dZCg8AfQ1AjmweCZ7ev');
define('SECURE_AUTH_KEY',  'YoKBVdeG0ybzCQfBNYQjJz07N9ahFu4JvVQLzHf5Ee4djh1pnRHNBV90lgvMsGH8');
define('LOGGED_IN_KEY',    '7Ii3RaLcDvKaA76yHNYURAKlquzB4Dj2GqeJGIKSPywraVK4HslU91nMqg273lio');
define('NONCE_KEY',        'JfNIm2Wg57AjZh7QSU0UvHDaFbbLBEuwHfLICZyjuPrUsogV26xnEFhIGH7k0uco');
define('AUTH_SALT',        'S5HW5QTf2Gr0EGm3L6WacMXFCJZWqCr6Mx1ss6KyNFznFYQwZAWmtOyyV093IbAn');
define('SECURE_AUTH_SALT', 'H9Fk97ACP8GS5XzM0RVrM8hyhYwfA0MUSlZ7mlfkL8LxodqNalQ33bZbAe2Tae8Z');
define('LOGGED_IN_SALT',   'znspz6CFKbPYNWQPpr9pTKM4QXeOsRhb220GLFIJOpNhJYVCYPPkNjpGiQKGnLJ8');
define('NONCE_SALT',       's13M6EIGhTLeugh64hGkdGfGXcenFUF4Lzit43kAO0E53xpymeqn3HGNTjMWOrQi');

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
$table_prefix = 'eqx5_';

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
