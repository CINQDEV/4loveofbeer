<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*====================================================
=            Create Options page and Menu            =
====================================================*/

class SWK_admin {
	public function __construct(){
		add_filter( 'plugin_action_links_swiss-knife/swiss-knife.php', array($this, 'settings_link') );
		add_action( 'admin_menu', array($this, 'swk_license_menu_page',) );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_script') );
		add_action( 'admin_bar_menu', array($this, 'oxygen_templates_admin_bar'), 999 );
		add_action( 'init', array($this, 'SWK_ct_init'), 3 );
		add_action( 'init', array($this, 'font_manager_tables') );
		add_action( 'wp_ajax_deleteFontFace', array($this, 'deleteFontFace_func') );
		add_action( 'wp_ajax_deleteFont', array($this, 'deleteFont_func') );
		add_action('init', array($this, 'scripts_manager_table') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_register_scripts_manager_scripts') );
		add_action( 'wp_ajax_deleteSWKScript', array($this, 'deleteSWKScript_func') );
		add_action( 'admin_menu', array($this, 'swk_admin_menu') );
		add_action( 'init', array($this, 'SWK_delete_old_files') );
		add_filter( 'upload_mimes', array($this, 'SWK_myme_types'), 100 );
		add_filter( 'wp_check_filetype_and_ext', array($this, 'swk_add_allow_upload_extension_exception'), 11, 4 );
		add_filter( 'admin_body_class', array($this, 'admin_body_classes') );
    }

	public function settings_link($links){
		// Build and escape the URL.
		$status  = get_option( 'swiss_knife_license_status' );
		$page_slug = 'swk_license';
		if( $status !== false && $status == 'valid' ) {
			$page_slug = 'swiss_knife';
		}
		$url = esc_url( add_query_arg(
			'page',
			$page_slug,
			get_admin_url() . 'admin.php'
		) );
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		// Adds the link to the end of the array.
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}

    public function admin_body_classes($classes){
    	$screen = get_current_screen();
    	$swiss_knife_admin_pages = array(
			'swissknife_page_font_manager',
			'swissknife_page_scripts_manager',
			'toplevel_page_swiss_knife',
			'swissknife_page_swk_license',
			'swissknife_page_manage_settings',
			'swissknife_page_shortcuts_manager',
		);
		if(in_array($screen->id, $swiss_knife_admin_pages)){
			$classes .= " dp-framework ";
		}
    	return $classes;
    }

    public function swk_license_menu_page() {
		$swiss_hide_menu = get_option('swiss_hide_menu');
		if($swiss_hide_menu == "yes" && !current_user_can('administrator')){
			return;
		}
		add_menu_page(
			'SwissKnife',
			'SwissKnife',
			'manage_options',
			SWISS_KNIFE_LICENSE_PAGE,
			array($this, 'swk_features_page'),
			'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTUiIGhlaWdodD0iNTYiIHZpZXdCb3g9IjAgMCA1NSA1NiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTI3LjQ2MjYgMC4yNzQ4NDFDMTIuMjk5NCAwLjI3NDg0MSAwIDEyLjU3NDIgMCAyNy43Mzc0QzAgNDIuOTAwNiAxMi4yOTk0IDU1LjIgMjcuNDYyNiA1NS4yQzQyLjYyNTggNTUuMiA1NC45MjUyIDQyLjkwMDYgNTQuOTI1MiAyNy43Mzc0QzU0LjkyNTIgMTIuNTc0MiA0Mi42MjU4IDAuMjc0ODQxIDI3LjQ2MjYgMC4yNzQ4NDFaTTQ0LjkxNjggMzIuOTIyNUgzMi41NTcyVjQ1LjI4MjFIMjIuMDA2MlYzMi45MjI1SDkuNjQ2NTdWMjIuMzQxNEgyMi4wMDYyVjkuOTgxN0gzMi41ODczVjIyLjM0MTRINDQuOTQ3VjMyLjkyMjVINDQuOTE2OFoiIGZpbGw9IiNCOTEzMTkiLz4KPC9zdmc+Cg=='

		);

		$status  = get_option( 'swiss_knife_license_status' );
		if( $status !== false && $status == 'valid' ) {
			//add_submenu_page( 'swiss_knife', 'Shortcodes', 'Shortcodes', 'manage_options', 'swk_shortcodes', 'swk_shortcodes_page', 9999 );
			add_submenu_page( SWISS_KNIFE_LICENSE_PAGE, 'Features', 'Features', 'manage_options', SWISS_KNIFE_LICENSE_PAGE, array($this, 'swk_features_page'), 9999 );

			$swiss_font_manager = get_option('swiss_font_manager');
			if($swiss_font_manager == "yes"){
				add_submenu_page( SWISS_KNIFE_LICENSE_PAGE, 'Fonts Manager', 'Font Manager', 'manage_options', 'font_manager', array($this, 'font_manager_page') );
			}

			$swiss_scripts_manager = get_option('swiss_scripts_manager');
			if($swiss_scripts_manager == "yes"){
				add_submenu_page( SWISS_KNIFE_LICENSE_PAGE, 'Scripts Manager', 'Scripts Manager', 'manage_options', 'scripts_manager', array($this, 'scripts_manager_page') );
			}

			$swiss_shortcuts = get_option('swiss_shortcuts');
			if($swiss_shortcuts == "yes"){
				add_submenu_page( SWISS_KNIFE_LICENSE_PAGE, 'Shortcuts Manager', 'Shortcuts Manager', 'manage_options', 'shortcuts_manager', array($this, 'shortcuts_manager_page') );
			}

			add_submenu_page( SWISS_KNIFE_LICENSE_PAGE, 'Manage Settings', 'Manage Settings', 'manage_options', 'manage_settings', array($this, 'manage_settings_page') );
		}

		add_submenu_page( SWISS_KNIFE_LICENSE_PAGE, 'License', 'License & Support', 'manage_options', 'swk_license', array($this, 'swk_license_page'), 9999 );
	}

	/*======================================================
	=            Plugin Admin Scripts and Pages            =
	======================================================*/

	public function enqueue_admin_script($hook) {
		$screen = get_current_screen();
		//echo $screen->id; exit;
		$swiss_knife_scripts = array(
			'swissknife_page_font_manager',
			'swissknife_page_scripts_manager',
			'toplevel_page_swiss_knife',
			'swissknife_page_swk_license',
			'swissknife_page_manage_settings',
		);
		if(in_array($screen->id, $swiss_knife_scripts)){
			wp_enqueue_style( 'swk_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin.css');

			wp_register_script( "swk_ajax_scripts", plugin_dir_url( __FILE__ ).'js/admin-min.js', array('jquery') );
		   	wp_localize_script( 'swk_ajax_scripts', 'swk_ajax', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'swk_nonce' => wp_create_nonce('ajax-nonce')
			));
		   	wp_enqueue_script( 'jquery' );
		   	wp_enqueue_script( 'swk_ajax_scripts' );
		}
		if($screen->id == "swissknife_page_font_manager" || $screen->id == "swissknife_page_scripts_manager"){
			wp_enqueue_media();
		}

		if($screen->id == "swissknife_page_shortcuts_manager"){
			$SWK_Shortcuts = new SWK_Shortcuts();
			$shortcuts = $SWK_Shortcuts->shortcuts_settings();
			wp_enqueue_style( 'swk_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin.css');
			wp_register_script( "swk_shortcuts_scripts", plugin_dir_url( __FILE__ ).'js/shortcuts-min.js', array('jquery') );
		   	wp_localize_script( 'swk_shortcuts_scripts', 'swk_shortcuts', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'shortcuts' => $shortcuts,
				'swk_nonce' => wp_create_nonce('ajax-nonce'),
			));
		   	wp_enqueue_script( 'jquery' );
		   	wp_enqueue_script( 'swk_shortcuts_scripts' );
		}

		global $post;
		if ( ($hook == 'post.php' || $hook == 'edit.php') && isset($post) ) {
			if ( 'ct_template' === $post->post_type && isset($_GET['action']) && $_GET['action'] == "edit" ) {
				wp_register_script( "swk_templates_js", plugin_dir_url( __FILE__ ).'js/admin_edit_template-min.js', array('jquery') );
				wp_localize_script(
					'swk_templates_js',
					'ct_template_edit_link',
					array ( "value" => add_query_arg( array('post_type'=>'ct_template'), admin_url('edit.php')))
				);
				wp_enqueue_script(  'swk_templates_js' );
			}
		}
	}

	/*====================================
	=            Licence Form            =
	====================================*/
	public function swk_license_page() {
		$license = get_option( 'swiss_knife_license_key' );
		$status  = get_option( 'swiss_knife_license_status' );
		?>
		<div class="wrap">
			<h2><?php _e('Swiss Knife Settings'); ?></h2>
			<div id="License" class="of-tabs">
				<form method="post" action="options.php">

					<?php settings_fields('swk_license'); ?>


					<div id="licence-form" class="swk_admin_card">

						<div class="swk_admin_header">
							<h3>Welcome to Swiss Knife</h3>
							<h4>To get you started, please <b>activate your license first</b></h4>
						</div><!-- End of swk_admin_header -->
						<div class="swk_admin_body">
							<label class="description" for="swiss_knife_license_key"><?php _e('Enter your license key'); ?></label>
							<input id="swiss_knife_license_key" name="swiss_knife_license_key" type="password" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />

							<?php if( $status !== false && $status == 'valid' ) { ?>
								<div class="licence-status">
									Status: <span class="licence-status-active"><?php _e('active'); ?></span>
								</div>

								<?php wp_nonce_field( 'swk_nonce', 'swk_nonce' ); ?>

								<input type="submit" class="button-secondary " class="edd_license_deactivate" name="edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
							<?php } else { ?>
								<?php wp_nonce_field( 'swk_nonce', 'swk_nonce' ); ?>
								<?php submit_button( 'Activate License', 'primary', 'edd_license_activate', true, array() ); ?>
								<?php } ?>
						</div><!-- End of swk_admin_body -->

					</div><!-- End of swk_admin_card -->
				</form>

				<div class="swk_admin_card">

						<div class="swk_admin_header">
							<h3>Admin links</h3>
						</div><!-- End of swk_admin_header -->
						<div class="swk_admin_body">
							<h4>Admin area</h4>
							<p>Access your account, download files, licenses or generate the invoice</p>
							<a target="_blank" href="https://dplugins.com/login/" class="swk-links">https://dplugins.com/login/</a>
							<br>
							<br>

							<h4>Support</h4>
							<a target="_blank" href="https://dplugins.com/support/" class="swk-links">https://dplugins.com/support/</a>
							<br>
							<br>

							<h4>Changelog</h4>
							<a target="_blank" href="https://dplugins.com/swiss-knife-changelog/" class="swk-links">https://dplugins.com/swiss-knife-changelog/</a>
							<br>
							<br>
						</div><!-- End of swk_admin_body -->

				</div><!-- End of swk_admin_card -->


				<div class="swk_admin_card">

						<div class="swk_admin_header">
							<h3>Useful links to keep you with the updates</h3>
						</div><!-- End of swk_admin_header -->
						<div class="swk_admin_body">
							<h4>Facebook group</h4>
							<a target="_blank" href="https://www.facebook.com/groups/dplugins" class="swk-links">https://www.facebook.com/groups/dplugins</a>
							<br>
							<br>

							<h4>Newsletter</h4>
							<a target="_blank" href="https://dplugins.com/newsletter/" class="swk-links">https://dplugins.com/newsletter/</a>
							<br>
							<br>

							<h4>Youtube</h4>
							<a target="_blank" href="https://www.youtube.com/channel/UCTfzTqbcMrdzAR9qRbY8a5w" class="swk-links">https://www.youtube.com/channel/UCTfzTqbcMrdzAR9qRbY8a5w</a>
							<br>
							<br>
						</div><!-- End of swk_admin_body -->

				</div><!-- End of swk_admin_card -->



			</div> <!-- End of Licence -->
		<?php
	}

	/*====================================
	=            Features Form            =
	====================================*/
	public function swk_features_page() {
		$status  = get_option( 'swiss_knife_license_status' );
		?>
		<div class="wrap">
			<h2><?php _e('Swiss Knife Features'); ?></h2>
			<div id="Features" class="of-tabs">
				<?php
					if( $status !== false && $status == 'valid' ) {
						require_once('admin__feature-form.php');
					} else {
						echo '<h2 class="warning-licence-not-active"><a href="'.admin_url( 'admin.php?page=swk_license' ).'">Activate license</a> to see the features.</h2>';
					}
				?>
			</div> <!-- End of Settings -->
		<?php
	}

	/*====================================
	=            Scripts Manager page       =
	====================================*/
	public function scripts_manager_page() {
		$status  = get_option( 'swiss_knife_license_status' );
		if( $status !== false && $status == 'valid' ) {
			require_once SWK_DIR . 'admin/feature__scripts-manager.php';
		} else {
			echo '<h2 class="warning-licence-not-active">Activate license to see the features.</h2>';
		}
	}

	/*====================================
	=            Manage settings         =
	====================================*/
	public function manage_settings_page() {
		$status  = get_option( 'swiss_knife_license_status' );
		if( $status !== false && $status == 'valid' ) {
			require_once SWK_DIR . 'admin/feature__manage-settings.php';
		} else {
			echo '<h2 class="warning-licence-not-active">Activate license to see the features.</h2>';
		}
	}

	/*====================================
	=            Shortcuts Manager page       =
	====================================*/
	public function shortcuts_manager_page() {
		$status  = get_option( 'swiss_knife_license_status' );
		if( $status !== false && $status == 'valid' ) {
			require_once SWK_DIR . 'admin/feature__shortcuts-manager.php';
		} else {
			echo '<h2 class="warning-licence-not-active">Activate license to see the features.</h2>';
		}
	}

	/*====================================
	=            Font Manager page       =
	====================================*/
	public function font_manager_page() {
		$status  = get_option( 'swiss_knife_license_status' );
		if( $status !== false && $status == 'valid' ) {
			require_once SWK_DIR . 'admin/feature__font-manager.php';
		} else {
			echo '<h2 class="warning-licence-not-active">Activate license to see the features.</h2>';
		}
	}

	public function oxygen_templates_admin_bar($wp_admin_bar){
		$swiss_admin_tpls = get_option('swiss_admin_tpls');
		if($swiss_admin_tpls == "yes"){
			$parent_menu_args = array(
		        'id' => 'dplugins-ct-templates',
		        'title' => 'Oxygen Templates',
		        'href' => site_url().'/wp-admin/edit.php?post_type=ct_template',
		        'meta' => array(
		            'class' => 'dplugins-ct-templates',
		            'title' => __('Oxygen Templates', 'swk')
		        )
		    );
		    $wp_admin_bar->add_node($parent_menu_args);

		    // Add Oxygen templates
		    $ct_templates = get_posts(array(
		        'post_type' => 'ct_template',
		        'posts_per_page' => -1,
		    ));
		    if($ct_templates){
		        foreach($ct_templates as $ct_template){
		            $ct_template_args = array(
		                'id' => 'dplugins-'.$ct_template->post_name,
		                'title' => $ct_template->post_title,
		                'href' => esc_url(site_url().'/wp-admin/post.php?post='.$ct_template->ID.'&action=edit'),
		                'parent' => 'dplugins-ct-templates',
		                'meta' => array(
		                    'class' => 'dplugins-oxy-templates',
		                    'title' => $ct_template->post_title,
		                )
		            );
		            $wp_admin_bar->add_node($ct_template_args);
		        }
		    }
		}

		$swiss_admin_pages = get_option('swiss_admin_pages');
		if($swiss_admin_pages == "yes"){
			$parent_menu_args = array(
		        'id' => 'dplugins-ct-pages',
		        'title' => 'Pages',
		        'href' => site_url().'/wp-admin/edit.php?post_type=page',
		        'meta' => array(
		            'class' => 'dplugins-ct-pages',
		            'title' => __('Pages', 'swk')
		        )
		    );
		    $wp_admin_bar->add_node($parent_menu_args);

		    // Add Oxygen templates
		    $pages = get_posts(array(
		        'post_type' => 'page',
		        'posts_per_page' => -1,
		    ));
		    if($pages){
		        foreach($pages as $page){
		            $ct_page_args = array(
		                'id' => 'dplugins-'.$page->post_name,
		                'title' => $page->post_title,
		                'href' => esc_url(site_url().'/wp-admin/post.php?post='.$page->ID.'&action=edit'),
		                'parent' => 'dplugins-ct-pages',
		                'meta' => array(
		                    'class' => 'dplugins-oxy-pages',
		                    'title' => $page->post_title,
		                )
		            );
		            $wp_admin_bar->add_node($ct_page_args);
		        }
		    }
		}
	}

	public function SWK_ct_init(){
		/* Oxygen template tags */
		/**
		* register tags taxonomy
		*/
		$swiss_tags = get_option('swiss_tags');
		if($swiss_tags == "yes"){
			$labels = array(
				'name'              => _x( 'Template Tags', 'taxonomy general name', 'swk' ),
				'singular_name'     => _x( 'Template Tag', 'taxonomy singular name', 'swk' ),
				'search_items'      => __( 'Search Template Tags', 'swk' ),
				'all_items'         => __( 'All Template Tags', 'swk' ),
				'parent_item'       => __( 'Parent Template Tag', 'swk' ),
				'parent_item_colon' => __( 'Parent Template Tag:', 'swk' ),
				'edit_item'         => __( 'Edit Template Tag', 'swk' ),
				'update_item'       => __( 'Update Template Tag', 'swk' ),
				'add_new_item'      => __( 'Add New Template Tag', 'swk' ),
				'new_item_name'     => __( 'New Template Tag Name', 'swk' ),
				'menu_name'         => __( 'Template Tags', 'swk' ),
			);

			$args = array(
				'hierarchical'      => false,
				'labels'            => $labels,
				'show_ui'           => true,
				'public'           	=> false,
				'publicly_queryable'=> false,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'swk_tags' ),
			);

			register_taxonomy( 'swk_tags', array( 'ct_template' ), $args );
		}
		/* Oxygen template tags */

		// check if builder activated
	    if ( defined("SHOW_CT_BUILDER") ) {
	    	$swiss_font_manager = get_option('swiss_font_manager');
			if($swiss_font_manager == "yes"){
				remove_action( "ct_builder_ng_init", array($this, "ct_init_elegant_custom_fonts") );
				add_action( "ct_builder_ng_init", array($this, "SWK_ct_init_elegant_custom_fonts") );
			}
		}
	}

	public function SWK_ct_init_elegant_custom_fonts() {
		$swiss_font_manager = get_option('swiss_font_manager');
		if($swiss_font_manager != "yes"){
			return;
		}
		global $wpdb;
		$font_family_list = array('system-ui');
		$swiss_knife_fonts = $wpdb->prefix . 'swiss_knife_fonts';
		$all_fonts = $wpdb->get_results( "SELECT font_name FROM $swiss_knife_fonts" );
		if(!empty($all_fonts)){
			foreach($all_fonts as $font){
				$font_family_list[] = $font->font_name;
			}
		}

		if(!empty($font_family_list)){
			$output = json_encode( $font_family_list );
			$output = htmlspecialchars( $output, ENT_QUOTES );

			echo "elegantCustomFonts=$output;";
		}
	}

	/*====================================
	=            Font Manager    		 =
	====================================*/
	public function font_manager_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$swiss_knife_fonts = $wpdb->prefix . "swiss_knife_fonts";
		$sql = "CREATE TABLE ".$swiss_knife_fonts." (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
		  font_name varchar(255) DEFAULT '' NOT NULL,
		  PRIMARY KEY (id)
		) ".$charset_collate.";";
		dbDelta( $sql );

		$swiss_knife_font_faces = $wpdb->prefix . "swiss_knife_font_faces";
		$sql = "CREATE TABLE ".$swiss_knife_font_faces." (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
		  font_id bigint(20) DEFAULT 0 NOT NULL,
		  font_weight varchar(255) DEFAULT '' NULL,
		  is_variable bigint(20) DEFAULT 0 NULL,
		  variable_from bigint(20) DEFAULT 0 NULL,
		  variable_to bigint(20) DEFAULT 0 NULL,
		  font_file varchar(255) DEFAULT '' NULL,
		  font_file_2 varchar(255) DEFAULT '' NULL,
		  font_display varchar(255) DEFAULT '' NULL,
		  font_style varchar(255) DEFAULT '' NULL,
		  font_preload varchar(255) DEFAULT '' NULL,
		  PRIMARY KEY (id)
		) ".$charset_collate.";";

		dbDelta( $sql );
	}

	public function deleteFontFace_func(){
		check_ajax_referer('ajax-nonce', 'verify_nonce');
		global $wpdb;
		$swiss_knife_font_faces = $wpdb->prefix . "swiss_knife_font_faces";
	  	$font_face_id = $_POST['font_face_id'];
	  	$wpdb->query("DELETE FROM $swiss_knife_font_faces WHERE id = '$font_face_id'");
	  	/* upload swiss_knife_fonts.css */
	    $this->prepare_font_css();
	    /* upload swiss_knife_fonts.css */

	  	echo 'Saved!';
	  	wp_die();
	}

	public function deleteFont_func(){
		check_ajax_referer('ajax-nonce', 'verify_nonce');
		global $wpdb;
		$swiss_knife_fonts = $wpdb->prefix . "swiss_knife_fonts";
		$swiss_knife_font_faces = $wpdb->prefix . "swiss_knife_font_faces";
	  	$font_id = $_POST['font_id'];
	  	$wpdb->query("DELETE FROM $swiss_knife_fonts WHERE id = '$font_id'");
	  	$wpdb->query("DELETE FROM $swiss_knife_font_faces WHERE font_id = '$font_id'");
	  	/* upload swiss_knife_fonts.css */
	    $this->prepare_font_css();
	    /* upload swiss_knife_fonts.css */

	  	echo 'Saved!';
	  	wp_die();
	}

	public function prepare_font_css(){
		$css = "";
		$css .= '@font-face {
			font-family: system-ui;
			font-style: normal;
			font-weight: 300;
			src: local(".SFNS-Light"), local(".SFNSText-Light"), local(".HelveticaNeueDeskInterface-Light"), local(".LucidaGrandeUI"), local("Segoe UI Light"), local("Ubuntu Light"), local("Roboto-Light"), local("DroidSans"), local("Tahoma");
		}
		@font-face {
			font-family: system-ui;
			font-style: italic;
			font-weight: 300;
			src: local(".SFNS-LightItalic"), local(".SFNSText-LightItalic"), local(".HelveticaNeueDeskInterface-Italic"), local(".LucidaGrandeUI"), local("Segoe UI Light Italic"), local("Ubuntu Light Italic"), local("Roboto-LightItalic"), local("DroidSans"), local("Tahoma");
		}
		@font-face {
			font-family: system-ui;
			font-style: normal;
			font-weight: 400;
			src: local(".SFNS-Regular"), local(".SFNSText-Regular"), local(".HelveticaNeueDeskInterface-Regular"), local(".LucidaGrandeUI"), local("Segoe UI"), local("Ubuntu"), local("Roboto-Regular"), local("DroidSans"), local("Tahoma");
		}
		@font-face {
			font-family: system-ui;
			font-style: italic;
			font-weight: 400;
			src: local(".SFNS-Italic"), local(".SFNSText-Italic"), local(".HelveticaNeueDeskInterface-Italic"), local(".LucidaGrandeUI"), local("Segoe UI Italic"), local("Ubuntu Italic"), local("Roboto-Italic"), local("DroidSans"), local("Tahoma");
		}
		@font-face {
			font-family: system-ui;
			font-style: normal;
			font-weight: 500;
			src: local(".SFNS-Medium"), local(".SFNSText-Medium"), local(".HelveticaNeueDeskInterface-MediumP4"), local(".LucidaGrandeUI"), local("Segoe UI Semibold"), local("Ubuntu Medium"), local("Roboto-Medium"), local("DroidSans-Bold"), local("Tahoma Bold");
		}
		@font-face {
			font-family: system-ui;
			font-style: italic;
			font-weight: 500;
			src: local(".SFNS-MediumItalic"), local(".SFNSText-MediumItalic"), local(".HelveticaNeueDeskInterface-MediumItalicP4"), local(".LucidaGrandeUI"), local("Segoe UI Semibold Italic"), local("Ubuntu Medium Italic"), local("Roboto-MediumItalic"), local("DroidSans-Bold"), local("Tahoma Bold");
		}
		@font-face {
			font-family: system-ui;
			font-style: normal;
			font-weight: 700;
			src: local(".SFNS-Bold"), local(".SFNSText-Bold"), local(".HelveticaNeueDeskInterface-Bold"), local(".LucidaGrandeUI"), local("Segoe UI Bold"), local("Ubuntu Bold"), local("Roboto-Bold"), local("DroidSans-Bold"), local("Tahoma Bold");
		}
		@font-face {
			font-family: system-ui;
			font-style: italic;
			font-weight: 700;
			src: local(".SFNS-BoldItalic"), local(".SFNSText-BoldItalic"), local(".HelveticaNeueDeskInterface-BoldItalic"), local(".LucidaGrandeUI"), local("Segoe UI Bold Italic"), local("Ubuntu Bold Italic"), local("Roboto-BoldItalic"), local("DroidSans-Bold"), local("Tahoma Bold");
		}'."\n";
		global $wpdb;
		$swiss_knife_fonts = $wpdb->prefix . "swiss_knife_fonts";
		$swiss_knife_font_faces = $wpdb->prefix . "swiss_knife_font_faces";
		$all_fonts = $wpdb->get_results( "SELECT * FROM $swiss_knife_fonts" );
		$replace_https = "";
		$upload_dir = wp_upload_dir();
		$folder_path = $upload_dir['basedir'].'/swiss-knife';
		$filepath = $upload_dir['basedir'].'/swiss-knife'."/skf.css";
		if(!empty($all_fonts)){
			/*if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
	    		$replace_https = "yes";
			}*/
			$font_file = "";
			foreach($all_fonts as $font){
				$font_id = $font->id;
	            $all_font_faces = $wpdb->get_results( "SELECT * FROM $swiss_knife_font_faces WHERE font_id = '$font_id' ORDER BY font_weight ASC" );
				if(!empty($all_font_faces)){
					foreach($all_font_faces as $font_face){
						/*if($replace_https == "yes"){
							$font_file = str_replace("http", "https", $font_face->font_file);
						} else {*/
							$font_file = $font_face->font_file;
						//}
						$css .= "@font-face {\n";
	            			$css .= "\t"."font-family: '".$font->font_name."'";
	            			/* font fallback */
	            			/*if(!empty($font_face->font_fallback)){
	            				$css .= ", ".$font_face->font_fallback;
	            			}*/
	            			$css .= ";\n";
							if($font_face->is_variable < 1){
								if(!empty($font_face->font_weight)){
									$css .= "\t"."font-weight: ".$font_face->font_weight.";\n";
								}
							} else {
								if(!empty($font_face->variable_from) && !empty($font_face->variable_to)){
									$css .= "\t"."font-weight: ".$font_face->variable_from." ".$font_face->variable_to.";\n";
								}
							}
							$css .= "\t"."font-style: ".$font_face->font_style.";\n";
							if(!empty($font_face->font_file_2) || !empty($font_file)){
								$css .= "\t"."src: ";
							}
							if(!empty($font_face->font_file_2)){
								if($font_face->is_variable > 0){
									$css .= "\t"."url(".$font_face->font_file_2.") format('woff2  supports variations'),\n";
									$css .= "\t\t\t"."url(".$font_face->font_file_2.") format('woff2-variations')";
								} else {
									$css .= "\t"."url(".$font_face->font_file_2.") format('woff2')";
								}
								if(!empty($font_file)){
									$css .= ",\n";
								} else {
									$css .= ";\n";
								}
							}
							if(!empty($font_file)){
								if($font_face->is_variable > 0){
									$css .= "\t\t\t"."url(".$font_file.") format('woff supports variations'),\n";
									$css .= "\t\t\t"."url(".$font_file.") format('woff-variations');\n";
								} else {
									$css .= "\t\t\t"."url(".$font_file.") format('woff');\n";
								}
							}
							if(!empty($font_face->font_display)){
								$css .= "\t"."font-display: ".$font_face->font_display.";\n";
							}
						$css .= "}\n\n";
					}
				}
			}
		}
		if (!is_dir($folder_path)) {
			mkdir($folder_path);
		}

		$upload_css = file_put_contents($filepath, $css);
	}


	/*====================================
	=            Script Manager          =
	====================================*/
	public function scripts_manager_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$swiss_knife_scripts = $wpdb->prefix . "swiss_knife_scripts";
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $swiss_knife_scripts ) );
	    $sql = "";
	    $table_exists = false;
	    if ( ! $wpdb->get_var( $query ) == $swiss_knife_scripts ) {
	        $table_exists = false;
	    } else {
	        $table_exists = true;
	    }

	    if(!$table_exists){
			$sql = "CREATE TABLE ".$swiss_knife_scripts." (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
			  script_name varchar(255) DEFAULT '' NULL,
			  script_type varchar(255) DEFAULT '' NULL,
			  script_location varchar(255) DEFAULT '' NULL,
			  script_include_type varchar(255) DEFAULT '' NULL,
			  script_file varchar(255) DEFAULT '' NULL,
			  script_frontend_only bigint(10) DEFAULT 0 NULL,
			  PRIMARY KEY (id)
			) ".$charset_collate.";";

			dbDelta( $sql );
		}
	}

	/**
	 * Load assets on front end only.
	 */
	public function enqueue_register_scripts_manager_scripts() {
		$swiss_scripts_manager = get_option('swiss_scripts_manager');
		if($swiss_scripts_manager != "yes"){
			return;
		}
		$all_scripts = $this->get_all_scripts();
		if(!empty($all_scripts)){
			foreach($all_scripts as $script){
				$this->enqueue_or_register($script);
			}
		}
	}


	public function replace_name_with_slug($name){
		$slug = strtolower(str_replace(" ", "-", $name));
		return $slug;
	}

	public function enqueue_or_register($script){
		if($script->script_frontend_only == "1"){
			if ( defined( 'SHOW_CT_BUILDER' ) ) {
				return;
			}
			$this->SWK_load_script($script);
		} else {
			$this->SWK_load_script($script);
		}
	}

	public function SWK_load_script($script){
		if($script->script_type == "css"){
			if($script->script_include_type == "register"){
				wp_register_style( $this->replace_name_with_slug($script->script_name), $script->script_file, array(), time() );
			} else {
				wp_enqueue_style( $this->replace_name_with_slug($script->script_name), $script->script_file, array(), time() );
			}
		} else {
			if($script->script_include_type == "register"){
				if($script->script_location == "header"){
					wp_register_script( $this->replace_name_with_slug($script->script_name), $script->script_file, array(), time(), false );
				} else {
					wp_register_script( $this->replace_name_with_slug($script->script_name), $script->script_file, array(), time(), true );
				}
			} else {
				if($script->script_location == "header"){
					wp_enqueue_script( $this->replace_name_with_slug($script->script_name), $script->script_file, array(), time(), false );
				} else {
					wp_enqueue_script( $this->replace_name_with_slug($script->script_name), $script->script_file, array(), time(), true );
				}
			}
		}
	}

	public function get_all_scripts(){
		global $wpdb;
		$swiss_knife_scripts = $wpdb->prefix . 'swiss_knife_scripts';
		$sql = "SELECT * FROM $swiss_knife_scripts ";
		/*if(!empty($front_or_backend)){
			$sql .= " WHERE script_frontend_only = ".$front_or_backend;
		}*/
		$sql .= " ORDER BY script_type ASC";
		$all_scripts = $wpdb->get_results( $sql );
		if(!empty($all_scripts)){
			return $all_scripts;
		} else {
			return '';
		}
	}

	public function deleteSWKScript_func(){
		check_ajax_referer('ajax-nonce', 'verify_nonce');
		global $wpdb;
		$swiss_knife_scripts = $wpdb->prefix . "swiss_knife_scripts";
	  	$script_id = $_POST['script_id'];
	  	$wpdb->query("DELETE FROM $swiss_knife_scripts WHERE id = '$script_id'");

	  	echo 'Saved!';
	  	wp_die();
	}

	/*====================================
	=            Shortcodes page            =
	====================================*/
	/**
	* add external link to Tools area
	*/
	public function swk_admin_menu() {
		$status  = get_option( 'swiss_knife_license_status' );
		if( $status === false && $status !== 'valid' ) {
			return;
		}
		$swiss_hide_menu = get_option('swiss_hide_menu');
		if($swiss_hide_menu == "yes" && !current_user_can('administrator')){
			return;
		}
		if(!current_user_can('manage_options')){
			return;
		}
		global $submenu;
	    $url = site_url() . '/wp-admin/admin.php?page=oxygen_vsb_sign_shortcodes';
	    $swk_menu = $submenu['swiss_knife'];
	    $total_size = count($submenu['swiss_knife']);
		$split = 1;
		$split_1 = array_slice($swk_menu, 0, $split);
		$split_2 = array_slice($swk_menu, $split, $total_size);
		$split_1[] = array('Sign Shortcodes', 'manage_options', $url);
		$submenu['swiss_knife'] = array_merge($split_1, $split_2);
	}

	/* delete old plugin files */
	public function SWK_delete_old_files(){
		$swk_deleted_files = get_option('swk_deleted_files');
		if(empty($swk_deleted_files)){
			$files_to_delete = array(
				'feature-form.php',
				'features_settings.php',
				'swissknife_licence.php',
				'font-manager.php',
				'scripts-manager.php',
			);
			$files_path = SWK_DIR.'admin/';
			foreach($files_to_delete as $file_to_delete){
				if(file_exists($files_path.$file_to_delete)){
					unlink($files_path.$file_to_delete);
				}
			}
			update_option('swk_deleted_files', 'yes');
		}
	}

	/* allow CSS JS uploads */
	public function SWK_myme_types($mime_types){
		// if not admin
		if(!is_admin() && !current_user_can('administrator')){
			return $mime_types;
		}

		// if swiss knife css/js option is enabled
		$swiss_allow_css_js = get_option('swiss_allow_css_js');
		if($swiss_allow_css_js == "yes"){
			if(!isset($mime_types['css'])){
				$mime_types['css'] = 'text/css'; //Adding svg extension
			}
			if(!isset($mime_types['js'])){
				$mime_types['js'] = 'application/javascript'; //Adding svg extension
			}
		}

		// if swiss knife font option is enabled
		$swiss_allow_woff = get_option('swiss_allow_woff');
		if($swiss_allow_woff == "yes"){
			if(!isset($mime_types['woff'])){
				$mime_types['woff'] = 'application/x-font-woff';
			}
			if(!isset($mime_types['woff2'])){
				$mime_types['woff2'] = 'application/x-font-woff2';
			}
		}
		//echo "<pre>"; print_r($mime_types); "</pre>"; exit;
	    return $mime_types;
	}

	public function swk_add_allow_upload_extension_exception( $check, $file, $filename, $mimes ) {
		// if not admin
		if(!is_admin() && !current_user_can('administrator')){
			return $check;
		}

		$swiss_allow_css_js = get_option('swiss_allow_css_js');
		$swiss_allow_woff = get_option('swiss_allow_woff');

		if($swiss_allow_css_js == "yes"){
			if ( false !== strpos( $filename, '.css' ) ) {
		        $check['ext']  = 'css';
		        $check['type'] = 'text/css';
		    }
		    if ( false !== strpos( $filename, '.js' ) ) {
		        $check['ext']  = 'js';
		        $check['type'] = 'text/ecmascript';
		    }
		}

		if($swiss_allow_woff == "yes"){
			if ( false !== strpos( $filename, '.woff' ) ) {
		        $check['ext']  = 'woff';
		        $check['type'] = 'application/x-font-woff';
		    }
		    if ( false !== strpos( $filename, '.woff2' ) ) {
		        $check['ext']  = 'woff2';
		        $check['type'] = 'application/x-font-woff2';
		    }
		}

	    return $check;
	}
}

new SWK_admin();
