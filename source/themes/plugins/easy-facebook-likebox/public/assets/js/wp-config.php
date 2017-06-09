<?php

/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home2/sjaved/public_html/wpmu/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
 //Added by WP-Cache Manager
define('DB_NAME', 'sjaved_wrdp16');

/** MySQL database username */
define('DB_USER', 'sjaved_wrdp16');

/** MySQL database password */
define('DB_PASSWORD', 'Cpo1si9NfSwtVGN');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '?-4y?)(7kGFO)<;=5Se$3ANta5/9!|HxOU>UixwPvF?=;q00mClN4*lM\`?opIFq$r*u;m*$');
define('SECURE_AUTH_KEY',  't)B*8IwdOBRZ;|^>m;IXti;!RE6Qr\`1pyJm>vLe=s@jUs1rDXw(4@Oh1@C<A/ixEQi)Cjz|E9T;w');
define('LOGGED_IN_KEY',    'jk:aM6KOP93c||<ws\`0^\`N~l)_KI~k(LV#V_oIMNX~)MB=r>d5|dA>ko$@@iBuHkNNTru<hekkR');
define('NONCE_KEY',        'PYQF47|@<wXGo*mreF)MhU75nAd8iE!Wc7jyPgmeua)_kDiMO_\`x6GZfku@K4F7G$@/$Yp2nEu1V#xF>');
define('AUTH_SALT',        'NWexy6#i9hodSweNPG^XlH~!Zy>mTPTwAo!:>feOGp@U|dicOzbBuNJykzK');
define('SECURE_AUTH_SALT', '0SOLQpC)=;<$18v>3;l--3V$u;amc?9~g@$8C)q/Rn9?z!4bbA=!v*qMbST5i<UJ:)I1nc1LMsYTT\`hRI!#_@@v=!qeo');
define('LOGGED_IN_SALT',   'B\`H$uOeO=Sn\`h!s$dW=|JTHSaiKtp_vE|2GnNXawB5Nd7>vS6ksZ@uinE:Q1^S~MxA=iU8nxuzN');
define('NONCE_SALT',       'TZVC:oh8iLXkFK<CF0zKpWS)uHu(b@pw#6~(DGL4c_Lar:Ttr<zHT:>U-aUxqbXsw#InSBfEP?');

/**#@-*/
define('AUTOSAVE_INTERVAL', 600 );
define('WP_POST_REVISIONS', 1);
define( 'WP_CRON_LOCK_TIMEOUT', 120 );
define( 'WP_AUTO_UPDATE_CORE', true );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
 
//define('PO_GLOBAL', true);

define('WP_MEMORY_LIMIT', '512M');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

define('WP_ALLOW_MULTISITE', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'jwebsol.com');
define('PATH_CURRENT_SITE', '/wpmu/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

define('SUNRISE', 'on');

//define ( 'BP_ENABLE_MULTIBLOG', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );
