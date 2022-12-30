<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SWK_Manage_Settings {
	public function __construct(){
		$status  = get_option( 'swiss_knife_license_status' );
		if( $status !== false && $status == 'valid' ) { 
			add_action( 'wp_ajax_swkExportSettings', array($this, 'swkExportSettings') );
			add_action( 'wp_ajax_swkImportSettings', array($this, 'swkImportSettings') );
		}
	}

	public function swkExportSettings(){
		// Check for nonce security      
		if ( ! wp_verify_nonce( $_POST['swk_nonce'], 'ajax-nonce' ) ) {
			echo 'not_authorized';
		} else {
			global $wpdb;
			$options_table = $wpdb->prefix . 'options';
			$swk_options = $wpdb->get_results("SELECT option_name, option_value FROM $options_table WHERE option_name LIKE 'swk%' OR option_name LIKE 'swiss_%' OR option_name = 'shortcuts_settings_preloaded_once'", ARRAY_A);
			if(!empty($swk_options)){
				$swk_options_array = array();
				foreach($swk_options as $key => $val){
					$swk_options_array[$key] = $val;
				}
				$total_index = count($swk_options_array);
				$new_index = $total_index + 1;
				$swk_options_array[$new_index]['option_name'] = 'shortcuts_settings';
				$swk_options_array[$new_index]['option_value'] = get_option('shortcuts_settings');
				$swiss_knife_file = fopen( 'php://output', 'w' );
				fwrite($swiss_knife_file, json_encode($swk_options_array));   // here it will print the array pretty
				fclose($swiss_knife_file);

			}
		}
		wp_die();
	}

	public function swkImportSettings(){
		$return_data = array();
		// Check for nonce security      
		if ( ! wp_verify_nonce( $_POST['swk_nonce'], 'ajax-nonce' ) ) {
			$return_data['status'] = "error";
           	$return_data['message'] = "Not authorized!";
		} else {
			if(!empty($_FILES["swk_json"]["name"])){  
		     	$allowed_ext = array("json");  
		      	$extension = end(explode(".", $_FILES["swk_json"]["name"]));  
		      	if(in_array($extension, $allowed_ext)){
		           	$swk_json = json_decode(trim(file_get_contents($_FILES["swk_json"]["tmp_name"]), "\xEF\xBB\xBF"), TRUE);
		           	if(is_array($swk_json)){
		           		foreach($swk_json as $swk_option){
		           			if(strpos($swk_option['option_name'], 'swiss') !== false 
		           				|| strpos($swk_option['option_name'], 'swk') !== false
		           				|| strpos($swk_option['option_name'], 'shortcuts') !== false){
		           				update_option($swk_option['option_name'], $swk_option['option_value']);
		           			}
		           		}
		           	}
		           	$return_data['status'] = "success";
		           	$return_data['message'] = "Settings Imported Successfully.";
		      	} else {  
		           	$return_data['status'] = "error";
		           	$return_data['message'] = "Only JSON Files are allowed.";
		      	}  
		 	} else {  
		      	$return_data['status'] = "error";
		   		$return_data['message'] = "No file attached.";
			}
		}

		echo json_encode($return_data);
		wp_die();
	}
}
new SWK_Manage_Settings();