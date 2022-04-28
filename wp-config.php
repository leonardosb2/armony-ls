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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'armonia' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '5q(UcT8n_3t-)q*@!cMAO&pvH#`??t-V+i5BEg_tJWH_1-kj>,/yN2n~e*S_1nq~' );
define( 'SECURE_AUTH_KEY',  'EuGqqVQ4X>!h1Yhi8i(r;]kwj}xOhl<Kr|Z{tCrps_|Q}$Rv0St[tSNpve}Z(dJf' );
define( 'LOGGED_IN_KEY',    'U 0g($3v[7=L,5nFR;Ui}H_=>uoZ$ b}!<gCI60Sf0((x2X^c.v?B-}^!8/poEHS' );
define( 'NONCE_KEY',        '~S{Zz=Vi6$ rG8Vd{mHLNtIs|-$CnS|jM065[^NP,HYfxd&WrD}g6^&AUT}.Zi.@' );
define( 'AUTH_SALT',        'G@3!f/xqC|IZ|t*TOuzc{+3`(SgEP6{z9eTY2Sr]ELt~vgts`dsP?((0[aj{x325' );
define( 'SECURE_AUTH_SALT', '=&VsycR:Gmz&^c]2H<;`1tzIV.%y559B?82SeJO^U<(Vzo+`3)ZZ6H|rtx3%U}q:' );
define( 'LOGGED_IN_SALT',   '`WPpe<ntixO@bN3c<>=6$<ka$`mv_TL4(JDcjo+Kczwk3qP=xy&_?[gV<Sku^fsu' );
define( 'NONCE_SALT',       'xYw,BsIqjkRK?vEOL sK@4iN0QBD`$xPrNU#^hP)Lv?AC(q[j:T){qt?O_WE1BPW' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'lsb_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
