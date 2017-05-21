<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/customers/7/0/5/translatedbyus.com/httpd.www/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'translatedbyus_');

/** MySQL database username */
define('DB_USER', 'translatedbyus_');

/** MySQL database password */
define('DB_PASSWORD', 'KRE3YSFy');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'w59fX{-;!t|v9[qgr!:&`06 cY6-Kk3[3/,sJUjUwPb|QLdsRu-7y&fJ##fn]s@,');
define('SECURE_AUTH_KEY',  'hq*>{cK%-zM1BNn|Np]D^q1R05uu53X<:2[24LnW4IW+ei:-Vb[@cw`,e`-uX7j1');
define('LOGGED_IN_KEY',    '!pTE&E-Uq+aES#BWA|+xuzf7CRy>~+aAj@m{}S5twdl-n; univ:w3G+N0-B`!8=');
define('NONCE_KEY',        'YmVOYhMSYDQD8kKD95 *mOQtQQ8b48=dlfjBe(5yJlqEGhh>H-[w&bULs8sbYH>W');
define('AUTH_SALT',        '>(W_H*/tPE.c1eG^g=gG;~|{Av0Et4))!&,4LM(Ztksxb_-!px<g+@y4q{]aa;`8');
define('SECURE_AUTH_SALT', 'F<Oeq;A0*Lyj+gMnflbvb<k3s8hn4j4lKa+a]hHQ^d)d=q`/$&Snf[x^V3?USi3l');
define('LOGGED_IN_SALT',   't9E8f0.n]&#ajT8SmAzy+nm[WV/rnGD9$1B#}{U)z90:$%Y@ZaG8b%3 >K4.<~wv');
define('NONCE_SALT',       'd||b{-KRV$hQHxD,THFHd2H|!/$oSZcy^h2jAlt>%=h~)$ct_9pKN-O_}p3v,[[W');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_tbu2_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define( 'WP_DEBUG_LOG', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
