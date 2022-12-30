<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SWK_Shortcuts {
	public function __construct(){
		$status  = get_option( 'swiss_knife_license_status' );
		if( $status !== false && $status == 'valid' ) {
			$swiss_shortcuts = get_option('swiss_shortcuts');
			if($swiss_shortcuts == "yes"){
				$this->populate_shortcut_defaults();
				add_action( 'wp_ajax_saveShortcuts', array($this, 'saveShortcuts') );
				add_action( 'init', array($this, 'shortcuts_scripts') );
			}
		}
	}

	public function populate_shortcut_defaults(){
		$shortcuts_settings = get_option('shortcuts_settings');
		$shortcuts_settings_preloaded_once = get_option('shortcuts_settings_preloaded_once');
		if(empty($shortcuts_settings) && empty($shortcuts_settings_preloaded_once)){
			$ctrl_or_commant = "";
			$alt_or_option = "";
			$user_agent = getenv("HTTP_USER_AGENT");
			if(strpos($user_agent, "Win") !== FALSE){
				$ctrl_or_commant = "ctrl";
				$alt_or_option = "alt";
			}
			if(strpos($user_agent, "Mac") !== FALSE){
				$ctrl_or_commant = "command";
				$alt_or_option = "option";
			}

			$shortcuts = array(
				array(
					'action' => 'save',
					'combination' => $ctrl_or_commant.'+'.'s',
					'key' => 's'
				),
				array(
					'action' => 'duplicate',
					'combination' => $ctrl_or_commant.'+'.'d',
					'key' => 'd'
				),
				array(
					'action' => 'add_element',
					'combination' => $ctrl_or_commant.'+'.'a',
					'key' => 'a'
				),
				array(
					'action' => 'add_div',
					'combination' => 'd',
					'key' => 'd'
				),
				array(
					'action' => 'add_heading',
					'combination' => 'h',
					'key' => 'h'
				),
				array(
					'action' => 'add_text',
					'combination' => 't',
					'key' => 't'
				),
				array(
					'action' => 'add_link',
					'combination' => 'l',
					'key' => 'a'
				),
				array(
					'action' => 'add_link_wrapper',
					'combination' => 'w',
					'key' => 'w'
				),
				array(
					'action' => 'add_button',
					'combination' => 'b',
					'key' => 'b'
				),
				array(
					'action' => 'add_image',
					'combination' => 'i',
					'key' => 'i'
				),
				array(
					'action' => 'add_video',
					'combination' => 'v',
					'key' => 'v'
				),
				array(
					'action' => 'add_code_block',
					'combination' => 'c',
					'key' => 'c'
				),
				array(
					'action' => 'add_easy_post',
					'combination' => 'e',
					'key' => 'e'
				),
				array(
					'action' => 'add_easy_repeater',
					'combination' => 'r',
					'key' => 'r'
				),
				array(
					'action' => 'add_shortcode',
					'combination' => 's',
					'key' => 's'
				),
				array(
					'action' => 'open_library',
					'combination' => $alt_or_option.'+'.'l',
					'key' => 'l'
				),
				array(
					'action' => 'open_reusable',
					'combination' => $alt_or_option.'+'.'r',
					'key' => 'r'
				),
				array(
					'action' => 'breakpoint1',
					'combination' => $alt_or_option.'+'.'1',
					'key' => '1'
				),
				array(
					'action' => 'breakpoint2',
					'combination' => $ctrl_or_commant.'+'.'2',
					'key' => '2'
				),
				array(
					'action' => 'breakpoint3',
					'combination' => $alt_or_option.'+'.'3',
					'key' => '3'
				),
				array(
					'action' => 'breakpoint4',
					'combination' => $alt_or_option.'+'.'4',
					'key' => '4'
				),
				array(
					'action' => 'breakpoint5',
					'combination' => $alt_or_option.'+'.'5',
					'key' => '5'
				),
				array(
					'action' => 'undo',
					'combination' => $ctrl_or_commant.'+'.'z',
					'key' => 'z'
				),
				array(
					'action' => 'rename',
					'combination' => 'shift+r',
					'key' => 'r'
				),
				array(
					'action' => 'add_conditions',
					'combination' => $alt_or_option.'+'.'f',
					'key' => 'f'
				),
				array(
					'action' => 'class_lock',
					'combination' => 'shift+l',
					'key' => 'l'
				),
			);
			//echo "<pre>"; print_r($shortcuts); "</pre>";
			update_option('shortcuts_settings', $shortcuts);
			update_option('shortcuts_settings_preloaded_once', 1);
		}
	}

	public function shortcuts_scripts(){
		//Return early if the webpage is being edited by Oxygen editor.
		if ( isset ( $_GET['ct_builder'] ) && 'true' === $_GET['ct_builder'] ) {
			add_action( 'oxygen_enqueue_iframe_scripts', array($this, 'shortcuts_enqueue_iframe_scripts'), 100 );
			add_action( 'oxygen_enqueue_ui_scripts', array($this, 'shortcuts_enqueue_builder_scripts'), 100 );
		}
	}

	public function shortcuts_enqueue_builder_scripts(){
		wp_enqueue_script( 'mousetrap_scripts', SWK_URL . 'features/js/mousetrap.min.js', array(), time() , true );
		wp_enqueue_script( 'shortcuts_scripts', SWK_URL . 'features/js/swk__shortcuts.min.js', array(), time() , true );
		wp_localize_script("shortcuts_scripts", 'swk_shortcuts', [
	        'shortcuts_settings' => json_encode($this->shortcuts_settings(), JSON_UNESCAPED_SLASHES),
			'swk_nonce' => wp_create_nonce('ajax-nonce'),
	    ]);
	}

	public function shortcuts_enqueue_iframe_scripts(){
		wp_enqueue_script( 'mousetrap_scripts', SWK_URL . 'features/js/mousetrap.min.js', array(), time() , true );
		wp_enqueue_script( 'shortcuts_scripts', SWK_URL . 'features/js/swk__shortcuts-iframe.min.js', array(), time() , true );
		wp_localize_script("shortcuts_scripts", 'swk_shortcuts', [
	        'shortcuts_settings' => json_encode($this->shortcuts_settings(), JSON_UNESCAPED_SLASHES),
			'swk_nonce' => wp_create_nonce('ajax-nonce'),
	    ]);
	}

	public function saveShortcuts(){
		check_ajax_referer('ajax-nonce', 'verify_nonce');
		$shortcuts_settings = $_POST['shortcuts'];
		update_option('shortcuts_settings', $shortcuts_settings);
		wp_die();
	}

	public function shortcuts_settings(){
		$shortcuts = array();
		$shortcuts_settings = get_option('shortcuts_settings');
		if(!empty($shortcuts_settings)){
			$user_agent = getenv("HTTP_USER_AGENT");
			foreach($shortcuts_settings as $shortcut){
				if(strpos($user_agent, "Mac") !== FALSE){
					$shortcut['combination'] = str_replace("ctrl", "command", $shortcut['combination']);
					$shortcut['combination'] = str_replace("alt", "option", $shortcut['combination']);
				}
				$shortcuts[$shortcut['action']]['combination'] = $shortcut['combination'];
				$shortcuts[$shortcut['action']]['key'] = $shortcut['key'];
			}
		}

		return $shortcuts;
	}

	public function key_to_name($key = ''){
		$names = array(
			'save' => 'Save',
			'delete' => 'Delete Element',
			'duplicate' => 'Duplicate Element',
			'add_element' => 'Add Element',
			'add_section' => 'Add Section',
			'add_columns' => 'Add Columns',
			'add_div' => 'Add Div',
			'add_heading' => 'Add Heading',
			'add_text' => 'Add Text',
			'add_rich_text' => 'Add Rich Text',
			'add_link' => 'Add Link',
			'add_link_wrapper' => 'Add Link Wrapper',
			'add_button' => 'Add Button',
			'add_image' => 'Add Image',
			'add_video' => 'Add Video',
			'add_icon' => 'Add Icon',
			'add_code_block' => 'Add Code Block',
			'add_easy_post' => 'Add Easy Post',
			'add_easy_repeater' => 'Add Easy Repeater',
			'add_shortcode' => 'Add Shortcode',
			'open_library' => 'Open Library',
			'open_reusable' => 'Open Reusable',
			'breakpoint1' => 'Breakpoint: All devices',
			'breakpoint2' => 'Breakpoint: Desktop',
			'breakpoint3' => 'Breakpoint: Tablet',
			'breakpoint4' => 'Breakpoint: Mobile Landscape',
			'breakpoint5' => 'Breakpoint: Mobile Portrait',
			'toggle_left_sidebar' => 'Toggle Left Sidebar',
			'toggle_structure' => 'Toggle Structure',
			'toggle_settings' => 'Toggle Settings',
			'toggle_selectors' => 'Toggle Selectors',
			'toggle_history' => 'Toggle History',
			'undo' => 'Undo',
			'redo' => 'Redo',
			'go_to_admin' => 'Go to WP Admin',
			'preview' => 'Open Preview',
			'edit_selected_text' => 'Edit selected Text',
			'rename' => 'Rename',
			'add_conditions' => 'Add conditions',
			'class_lock' => 'Class Lock/Unlock',
			'lock_all_classes' => 'Lock all classes',
			'unlock_all_classes' => 'Unlock all classes',
			'display_shortcuts' => 'Display Shortcuts',
			'insert_multiple_classes' => 'Insert Multiple Classes',
			'apply_code' => 'Apply Code',
			'cut' => 'Cut to Clipboard',
			'copy' => 'Copy to Clipboard',
			'paste' => 'Paste to Clipboard',
			'copy-conditions' => 'Copy conditions',
			'adv_bg' => 'Advanced - Background',
			'adv_size_spacing' => 'Advanced - Size & Spacing',
			'adv_layout' => 'Advanced - Layout',
			'adv_typography' => 'Advanced - Typography',
			'adv_borders' => 'Advanced - Borders',
			'adv_effects' => 'Advanced - Effects',
			'adv_custom_css' => 'Advanced - Custom CSS',
			'adv_javascript' => 'Advanced - Javascript',
			'adv_attributes' => 'Advanced - Attributes',
			'adv_bg_gradient' => 'Advanced - Background - Gradient',
			'adv_effects_animate' => 'Advanced - Effects - Animate On Scroll',
			'adv_effects_opacity' => 'Advanced - Effects - Opacity',
			'adv_effects_transition' => 'Advanced - Effects - Transition',
			'adv_effects_box_shadow' => 'Advanced - Effects - Box Shadow',
			'adv_effects_text_shadow' => 'Advanced - Effects - Text Shadow',
			'adv_effects_filter' => 'Advanced - Effects - Filter',
			'adv_effects_transform' => 'Advanced - Effects - Transform',
		);
		if(!empty($key)){
			return $names[$key];
		} else {
			return $names;
		}
	}
}
new SWK_Shortcuts();
