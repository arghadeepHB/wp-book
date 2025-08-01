<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'U)ZR1{@=?[<CX>{nC0fc}`U(Vto]fyQ/Kqu]IDQ@x/)fWR9s!LvFwgWKOJr:nP_9' );
define( 'SECURE_AUTH_KEY',   'el|NH&O2jmIs}/3p(sX[v&uDU@%;,p20Y)m;F<;XLn#oM?)MsOkmv?p_4rusKH}C' );
define( 'LOGGED_IN_KEY',     'oA=,G7b`Ohwr8{jl%c)Aw KZM4mh}+WGs9>onm4oj>{X$^qsiQ{QhSWL}e9@A]-v' );
define( 'NONCE_KEY',         '_w2yu`e_O}*=(} f]VA{cG{.C.pM?)^Yt~sPC^z`{O6V;HSuWL-oGmhS?*ZYT?Zk' );
define( 'AUTH_SALT',         'y@UztXlOchJmW4(88@Qi+51DF#VMETrzAW5Ht,C&_Y+U>B&*q_0.ktf|zhX}m$2f' );
define( 'SECURE_AUTH_SALT',  '-Znpq22~:2H/o9aN<XjiAaPX4=su&]dO_ez{FLXb%i^ zuy>_&vr/bW&k+7SF.%&' );
define( 'LOGGED_IN_SALT',    'RAz@r{`jY)6vUO%t|[zq!IVo[3o5w%%!(~oG:q[,qSw5O+<k0F9Ew%(%Sr}<pq6p' );
define( 'NONCE_SALT',        'NMdAxUr=H:NWlHJ`*b< 0/:b&cI-l/_<n7zFJcgBt*ChgLx,/44N:cn|CmLYv-0p' );
define( 'WP_CACHE_KEY_SALT', 'YA534uf`IGu 6;_:Pj9Ar6z*i{Si?Mk$1Yqf/|ne<.}0GWLbo#rAB5E>7stY)<&>' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
