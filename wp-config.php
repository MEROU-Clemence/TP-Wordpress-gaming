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
define( 'DB_NAME', 'tp-wp-clemence' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'admin' );

/** Database hostname */
define( 'DB_HOST', 'database' );

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
define( 'AUTH_KEY',          '!E)&L0k@Rgpk[sDb6b{^ y4.1@~E_E|UBLbU#f1`vHn@Tex+`]Cw;B?^/}2IJlrX' );
define( 'SECURE_AUTH_KEY',   '9@ELP0oh3 E-dlZNVqZsmC.;ic>[#8ZI<O$W_x`j4}Y,LzXQ&6Wz-Pfwtg~rN7MX' );
define( 'LOGGED_IN_KEY',     'X76qC~RJotl?AO2@7J}6??3ZPxan_b0<kTZ;_~H;r}6+XP{W4|EUmw[;j>CUK#WY' );
define( 'NONCE_KEY',         '#D[XW)A4QfxCm7>a17~M#RVMrWtGm-xC|x`hx$(@A4;@jPuZXed4:FO9 qg^=K0C' );
define( 'AUTH_SALT',         '`8G(?J]{!e 5*r-7p}=&<;5-hMMF5n`MqgDGqb]9!?Ov7OCQ>L?3Oj^6@7y2G<e}' );
define( 'SECURE_AUTH_SALT',  'w06)3X<MR8>E=kL1u`qU9R.eyn)W3McfiU3vei;*.wz>1Lfz4V^/?!?VgLkmMdfp' );
define( 'LOGGED_IN_SALT',    'UMz[iK<V_m<&@4&z$l@%c4Cl NBJwzC<w2l=JAeox?{x+r=#Oeu*FC97{+tPV!HY' );
define( 'NONCE_SALT',        '_^SJc>f[m53F{#x@uT||7#nS^b-G5y.7s]nMC+yO8dCoLfVMp4m-3FL+{,D[t3U+' );
define( 'WP_CACHE_KEY_SALT', '0.:ye=:SFY$<4Q6iuzYMXLSC#nKKXHQZ C.!2)-%M`83o8,>bkgr|QQgP0he*)%r' );


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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
