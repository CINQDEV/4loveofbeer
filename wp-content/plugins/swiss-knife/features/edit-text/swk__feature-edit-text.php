<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('SWK_Edit_Text')){
	class SWK_Edit_Text {
		public function __construct(){
			$swiss_edit_text = get_option('swiss_edit_text');
			if($swiss_edit_text == "yes"){
				add_action( 'wp_enqueue_scripts', array($this, 'edit_text_scripts') );
			}
		}

		public function edit_text_scripts(){
			if ( ! defined( 'SHOW_CT_BUILDER' ) ) {
				return;
			}

			if ( defined( 'OXYGEN_IFRAME' ) ) {
				wp_enqueue_script( 'swk-edit-text', SWK_URL . 'features/edit-text/js/edit-text.js', array(), time(), true );
			}
		}
	}
}
new SWK_Edit_Text();