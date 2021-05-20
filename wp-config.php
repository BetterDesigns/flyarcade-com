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
define('DB_NAME', 'wordpress');
/** MySQL database username */
define('DB_USER', 'wordpress');
/** MySQL database password */
define('DB_PASSWORD', 'Treefarm22');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');
/** The Database Collate type. Don't change this if in doubt. */
//define('DB_COLLATE', '');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'xfpghbndd8sunw0jzwm06rt1gdgmklc3sv7p721o8znvqhz1yaua2tl2jqjn2bet');
define('SECURE_AUTH_KEY',  'g2boachu53abgvtkbnfkwkiif7wvgvckv4trm63o9llvsoikw2suuxco5namjkuf');
define('LOGGED_IN_KEY',    'x8xnumvolmbqrn7whhrrfatirpf9ed1aqulbsxptd5y8h1lpcmr8zhvmdpyw1laq');
define('NONCE_KEY',        'hlyfcka3i6oy0cpb4hdaucldtqbhlhyrlgffpckmkdzspuzff8dgi1shxqpsqeuo');
define('AUTH_SALT',        'hdy8kyproho01m1hv42xgoo1hkujlff1dn1iomenbq3nihupsp9dooemkokji2ir');
define('SECURE_AUTH_SALT', '23dcmze6yhb4jk1ga9b9aytv5exspslgphs1onw6tvx3rhfwladqg0sqacjijps5');
define('LOGGED_IN_SALT',   'otkbkvd3kvpc2jdfmhvjtl9txv1mm3quxq9bxchfiptsxpetpq0lkfb7dztm7efe');
define('NONCE_SALT',       '9tbjlzjwnflywib0thxuqhyuchbdxcchbvnywvsggpeebczgwt22sk6imztpmijv');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpzc_';
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
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
