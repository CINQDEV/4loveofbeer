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
define('DB_NAME', 'SCWORDPRESS-353030378b44');

/** MySQL database username */
define('DB_USER', 'SCWORDPRESS-353030378b44');

/** MySQL database password */
define('DB_PASSWORD', 'e2bdea94f344d9c1');

/** MySQL hostname */
define('DB_HOST', 'wordpressdb-a.hosting.stackcp.net');

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
define('AUTH_KEY',         '0A5ys1oHxaDSvsOsRl0y/l3pWMEqDAhf');
define('SECURE_AUTH_KEY',  'bfJUepy4F34OBmQqszD6gWAUDOYIQyI1');
define('LOGGED_IN_KEY',    'ZGzeUrrF4A297bMi+m9zHwPAK1eyuYu1');
define('NONCE_KEY',        'Pe0+A/uolzLToyUS6iOj/SxSCfr9Bptp');
define('AUTH_SALT',        'R43hWyY4vO+hIar+dfToNoizilml7lQK');
define('SECURE_AUTH_SALT', 'GUG+/JSMkIamTINkiU7GVsoQpHs6m2Q8');
define('LOGGED_IN_SALT',   'cMG3THYqaiSMS2n8oc87X1EBH3ZTrqCS');
define('NONCE_SALT',       'TMfbhO6rELoCz89r51ihi0ihbSPFK9vp');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'fb_';

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
