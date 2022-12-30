<?php

/*
Plugin Name: Swiss Knife Pro
Description: The first plugin you should install when the Oxygen's default is not enough.
Author: DPlugins
Author URI: https://dplugins.com/
Text Domain: swk
Version: 4.0.0
License: GNU General Public License v2.0 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'Move Along, Nothing to See Here' );

// EDD Plugin const
define( 'SWISS_KNIFE_STORE_URL', 'https://dplugins.com/' );
define( 'SWISS_KNIFE_ITEM_ID', 17 );
define( 'SWISS_KNIFE_ITEM_NAME',  'Swiss Knife' );
define( 'SWISS_KNIFE_LICENSE_PAGE', 'swiss_knife' );

define( 'EDD_AUTHOR', 'devusrmk' );
define( 'EDD_PLUGINVERSION',  '4.0.0' );


// Plugin const
define('SWK_UPDATER', __FILE__);
define('SWK_BASE',	plugin_basename(__FILE__));
define('SWK_URL',	plugin_dir_url(__FILE__));
define('SWK_DIR',	plugin_dir_path(__FILE__));

// @CustomMod->add
update_option('swiss_knife_license_key', 'developer');
update_option('swiss_knife_license_status', 'valid');

// Load Admin
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/admin/edd/EDD_SL_Plugin_Updater.php' );
}

require_once SWK_DIR . 'admin/admin.php';
require_once SWK_DIR . 'admin/admin__swissknife_licence.php';
require_once SWK_DIR . 'features/inc/import-classes.php';
require_once SWK_DIR . 'admin/feature__shortcuts-manager-functions.php';
require_once SWK_DIR . 'admin/feature__manage-settings-functions.php';
require_once SWK_DIR . 'features/edit-text/swk__feature-edit-text.php';
require_once SWK_DIR . 'features/lorem-ipsum-generator/swk__feature-lorem-ipsum-generator.php';
require_once SWK_DIR . 'features/random-unsplash-images/swk__feature-random-unsplash-images.php';
require_once SWK_DIR . 'admin/feature__page-template-lists.php';

function edd_sl_sample_plugin_updater() {

	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'swiss_knife_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( SWISS_KNIFE_STORE_URL, SWK_UPDATER,
		array(
			'version' => EDD_PLUGINVERSION,                    // current version number
			'license' => $license_key,             // license key (used get_option above to retrieve from DB)
			'item_id' => SWISS_KNIFE_ITEM_ID,       // ID of the product
			'author'  => EDD_AUTHOR, // author of this plugin
			'beta'    => false,
		)
	);

}

// CustomMod->remove
// add_action( 'init', 'edd_sl_sample_plugin_updater' );




/*=========================================
=            Features Settings            =
=========================================*/
require_once SWK_DIR . 'admin/admin__features_settings.php';


/*================================================
=            Clean up After Uninstall            =
================================================*/

function delete_plugin_database_tables(){
	$swk_clean_data = get_option('swk_clean_data');
	if($swk_clean_data == "yes"){
		global $wpdb;
		$options_table = $wpdb->prefix . 'options';
		$swiss_knife_fonts = $wpdb->prefix . "swiss_knife_fonts";
		$swiss_knife_font_faces = $wpdb->prefix . "swiss_knife_font_faces";
		$swiss_knife_scripts = $wpdb->prefix . "swiss_knife_scripts";
		$db_fields = array(
			"swiss_knife_license_key",
			"swiss_knife_license_status",
			"swiss_knife_debug",
			"swiss_knife_open",
			"swiss_knife_structure_custom_width",
			"swiss_knife_structure_width",
			"swiss_knife_structure_icons",
			"swiss_knife_theme_type",
			"swiss_knife_topbar",
			"swiss_knife_test",
			"swiss_knife_codehint",
			"swiss_hide_menu",
			"swiss_open_preview_new_tab",
			"swiss_knife_codemirror",
			"swiss_knife_cm_theme",
			"swk_feature_import_classes",
			"swiss_shortcuts",
			"shortcuts_settings",
			"shortcuts_settings_preloaded_once",
			"swiss_fontsize",
			"swiss_class_manager",
			"swiss_edit_text",
			"swiss_hide_content_edit",
			"swiss_allow_woff",
			"swiss_allow_css_js",
			"swiss_scripts_manager",
			"swiss_font_manager",
			"swiss_import_classes",
			"swk_clipboard",
			"swiss_tags",
			"swiss_admin_tpls",
			"swiss_t_list",
			"swiss_p_list",
			"swk_utilities",
			"swk_scripts_styles",
			"swk_custom_fonts",
			"swk_code_editor",
			"swk_structure_panel",
			"swk_left_sidebar",
			"swk_topbar",
		);
		$right_click_features = array('swiss_cut', 'swiss_copy', 'swiss_copy_style', 'swiss_copy_condition', 'swiss_paste', 'swiss_duplicate', 'swiss_make_reusable', 'swiss_rename', 'swiss_set_conditions', 'swiss_change_id', 'swiss_wrap', 'swiss_delete');
		foreach($right_click_features as $rc_feature){
			array_push($db_fields, $rc_feature);
		}
		$db_fields = join("','", $db_fields);
		$wpdb->query("DELETE FROM $options_table WHERE option_name IN('".$db_fields."')");
		$wpdb->query("DROP TABLE $swiss_knife_fonts");
		$wpdb->query("DROP TABLE $swiss_knife_font_faces");
		$wpdb->query("DROP TABLE $swiss_knife_scripts");
	}
}

function SWK_body_classes($classes){
	if ( defined("SHOW_CT_BUILDER") ) {
		$classes[] = 'dp-framework';
		$swiss_knife_theme_type = get_option('swiss_knife_theme_type');
		if($swiss_knife_theme_type == "dark"){
			$classes[] = 'swk-dark-theme';
		} else if($swiss_knife_theme_type == "light"){
			$classes[] = 'swk-light-theme';
		} else if($swiss_knife_theme_type == "default"){
			$classes[] = 'swk-default-theme';
		} else if($swiss_knife_theme_type == "no_theme"){
			$classes[] = 'swk-no-theme';
		} else {
			// do nothing
		}
	}
	return $classes;
}
add_filter( 'body_class','SWK_body_classes' );

//register_deactivation_hook(__FILE__, 'delete_plugin_database_tables');

register_uninstall_hook(__FILE__, 'delete_plugin_database_tables');




// Add stuff
