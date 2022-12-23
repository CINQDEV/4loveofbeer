<?php
/**
 * Production environment config settings
 *
 * Enter any WordPress config settings that are specific to this environment 
 * in this file.
 * 
 * @package    Studio 24 WordPress Multi-Environment Config
 */
  

// ** MySQL settings - You can get this info from your web host ** //
/** MySQL hostname */
define('DB_HOST', 'wpdb-26.hosting.stackcp.net');

/** The name of the database for WordPress */
define('DB_NAME', 'SCWORDPRESS-35303038e520');

/** MySQL database username */
define('DB_USER', 'SCWORDPRESS-35303038e520');

/** MySQL database password - set in wp-config.local.php */
/** MySQL database password */
define('DB_PASSWORD', 'd23b3864a33b4e8e');

/**
 * For developers: WordPress debugging mode.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);


// Recommended WP config settings, uncomment to use these

/**
 * Disable theme/plugin upload.
 */
//define( 'DISALLOW_FILE_MODS', true );