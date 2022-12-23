<?php
/**
 * Default config settings
 *
 * Enter any WordPress config settings that are default to all environments
 * in this file.
 * 
 * Please note if you add constants in this file (i.e. define statements) 
 * these cannot be overridden in environment config files so make sure these are only set once.
 *
 * @package    Studio 24 WordPress Multi-Environment Config
 */
  

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
define('AUTH_KEY',         'v>iIu^]o+Yl5vr?E#E2ZT~gd_aA=0P3A:zR5<t~6L@f0#GbC1K>P9Ld:-)*h%*UO');
define('SECURE_AUTH_KEY',  'Ax-+$,=?2U}DJqjjd]@#s{SSu!/V+X-LZPSRz$T_.K.|rb=d>Eu3gSN(|?]t4<_.');
define('LOGGED_IN_KEY',    '^d)5<J+Hy9 6f-0%>%BU.b3VW=W`+#H4M6!W-^bDx${Xn%J!?DG%+O0bU/X`z5LW');
define('NONCE_KEY',        '/9Iwk_>OH#<~ft9n!IQ|G^5/aA@I?QMa}y1^^YEpyCl Yv-ZW3IQ%xoYC[~-em;H');
define('AUTH_SALT',        'Q1G?h} V+J*)w?nO+6Ew|=+m>?X8U)y%XHK94:BrkEw}Vr-=6m,FM0t3RV~!JC-T');
define('SECURE_AUTH_SALT', 'iAcc >{tg)L8_*2=JpsX|tIHK/9WP=dMiV;ifyRiD:^ga3?_Am40~cRK{U2&8GC+');
define('LOGGED_IN_SALT',   '6uw40LUp>K|Aq8W>}n|$X`@_uRDD`aFM|-MfbIR|9GJzezk}Zf_OI=-?H))2UI4M');
define('NONCE_SALT',       '7sYj$+xO@Vg%gsn[jSjVSz+x{%a7A6Bi+uuN:|Bkjiu|5`EM>$4Ef|(c?/cktD+`');

/**#@-*/


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '05_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');


// Recommended WP config settings, uncomment to use these

/**
 * Increase memory limit. 
 */
//define('WP_MEMORY_LIMIT', '64M');

/**
 * Limit post revisions.
 */
//define('WP_POST_REVISIONS', 5);

/**
 * Disable automatic updates.
 */
//define( 'AUTOMATIC_UPDATER_DISABLED', true );

/**
 * Disable file editor.
 */
//define( 'DISALLOW_FILE_EDIT', true );
