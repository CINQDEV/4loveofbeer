<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('SWK_Random_Unsplash_Images')){
	class SWK_Random_Unsplash_Images {
		public function __construct(){
			$swiss_unsplash = get_option('swiss_unsplash');
			if($swiss_unsplash == "yes"){
				add_action( 'oxygen_enqueue_ui_scripts', array($this, 'random_unsplash_images') );
			}
		}

		public function random_unsplash_images(){
			wp_enqueue_script( 'swk-random-unsplash-images', SWK_URL . 'features/random-unsplash-images/js/random-unsplash-images-min.js', array(), time(), true );
		}
	}
}
new SWK_Random_Unsplash_Images();