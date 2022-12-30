<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('SWK_Lorem_Ipsum_Generator')){
	class SWK_Lorem_Ipsum_Generator {
		public function __construct(){
			$swiss_lorem_ipsum = get_option('swiss_lorem_ipsum');
			if($swiss_lorem_ipsum == "yes"){
				add_action( 'wp_enqueue_scripts', array($this, 'lorem_ipsum_scripts') );
			}
		}

		public function lorem_ipsum_scripts(){
			if ( ! defined( 'SHOW_CT_BUILDER' ) ) {
				return;
			}

			if ( defined( 'OXYGEN_IFRAME' ) ) {
				wp_enqueue_script( 'swk-lorem-ipsum', SWK_URL . 'features/lorem-ipsum-generator/js/lorem-ipsum-generator.js', array(), time(), true );
			}
		}
	}
}
new SWK_Lorem_Ipsum_Generator();