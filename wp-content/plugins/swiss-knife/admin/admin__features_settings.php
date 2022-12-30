<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class SWK_features_settings
{
    public function __construct()
    {
        add_action('wp_ajax_saveOFOptions', array($this, 'saveOFOptions_func'));
        add_action('init', array($this, 'load_if_active'));
        add_action('wp_footer', array($this, 'swk_render_template'));
        add_filter('style_loader_tag', array($this, 'swiss_knife_preload_font_file'), 10, 2);
        add_filter('body_class', array($this, 'body_classes'));
        add_action('wp', array($this, 'oxy_component_configurations'), 100);
    }

    public function get_swiss_knife_options()
    {
        $swiss_knife_options = array(

            /*================================
            =            Features            =
            ================================*/

            'swk_topbar' => array(
                'Topbar',
                array(
                    'swiss_knife_debug' => array(
                        'Debug',
                        'styles' => array(),
                        'scripts' => array('swk_feature_debug_js')
                    ),

                    'swiss_p_list' => array(
                        'Pages List - Drop Down Menu',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_t_list' => array(
                        'Oxygen Templates List - Drop Down Menu',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    // 'swiss_compact_topbar' => array(
                    // 	'Compact',
                    // 	'styles' => array('swiss_compact_topbar_css'),
                    // 	'scripts' => array()
                    // ),
                )
            ),
            'swk_left_sidebar' => array(
                'Left Sidebar',
                array(
                    'swiss_edit_text' => array(
                        'Edit Text elements from left sidebar',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_lorem_ipsum' => array(
                        'Lorem Ipsum generator',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_unsplash' => array(
                        'Random Unsplash Images',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_hide_content_edit' => array(
                        'Hide Oxygen&apos;s content editor',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_class_lock' => array(
                        'Class Manager (Insert and Lock)',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_advanceSettings' => array(
                        'Advanced Settings tabs on the left side',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_swk_bigger-measurebox' => array(
                        'Bigger Padding and Margin boxes',
                        'styles' => array('swk_bigger-measurebox'),
                        'scripts' => array()
                    ),
                    
                    // 'swk_filtered_tab_data' => array(
                    //     '3rd Advanced Tab (Display only edited sections)',
                    //     'styles' => array(),
                    //     'scripts' => array()
                    // ),

                    // 'swk_compact_add_elements' => array(
                    //     'Compact List for Add Elements',
                    //     'styles' => array('swk_compact_add_elements'),
                    //     'scripts' => array()
                    // ),

                    // 'swiss_breakpoints_size' => array(
                    // 	'Display breakpoints size on hover',
                    // 	'styles' => array('swk_breakpoints_size_css'),
                    // 	'scripts' => array('swk_breakpoints_size')
                    // ),

                )
            ),
            'swk_structure_panel' => array(
                'Structure Panel',
                array(
                    'swiss_knife_structure_icons' => array(
                        'Structure Icons',
                        'styles' => array('swk_feature_structure_icons'),
                        'scripts' => array()
                    ),

                    'swiss_knife_open' => array(
                        'Open Structure on load',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_knife_structure_custom_width' => array(
                        'Structure Width',
                        'styles' => array(),
                        'scripts' => array()
                    ),
                )
            ),
            'swk_code_editor' => array(
                'Code Editor',
                array(
                    'swiss_knife_codehint' => array(
                        'Code Hint',
                        'styles' => array('swk_quick_show_hint', 'swk_quick_emmet_css', 'swk_quick_code_hint_css'),
                        'scripts' => array('swk_quick_emmet_browser', 'swk_quick_show_hint', 'swk_quick_show_hint_html_hint', 'swk_quick_show_hint_javascript_hint', 'swk_quick_show_hint_xml_hint', 'swk_quick_code_hint', 'swk_quick_closebrackets', 'swk_quick_matchbrackets')
                    ),

                    'swiss_knife_codemirror' => array(
                        'Code Mirror Theme ',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_fontsize' => array(
                        'Code Editor font size',
                        'styles' => array(),
                        'scripts' => array()
                    ),
                )
            ),
            'swk_custom_fonts' => array(
                'Custom Fonts',
                array(
                    'swiss_font_manager' => array(
                        'Enable Font Manager',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_allow_woff' => array(
                        'Allow upload woff and woff2',
                        'styles' => array(),
                        'scripts' => array()
                    ),
                )
            ),
            'swk_scripts_styles' => array(
                'Scripts & Styles',
                array(
                    'swiss_scripts_manager' => array(
                        'Enable Scripts Manager',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_allow_css_js' => array(
                        'Allow CSS/JS Uploads',
                        'styles' => array(),
                        'scripts' => array()
                    ),
                )
            ),
            'swk_utilities' => array(
                'Utilities',
                array(
                    'swiss_shortcuts' => array(
                        'Keyboard Shortcuts',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_import_classes' => array(
                        'Import Classes',
                        'styles' => array('swk_import-classes'),
                        'scripts' => array('swk_feature_import_classes')
                    ),

                    'swiss_hide_menu' => array(
                        'Swiss Knife Menu - Hide for all users except admin',
                        'styles' => array(),
                        'scripts' => array()
                    ),

                    'swiss_tags' => array(
                        'Template Tags',
                        'styles' => array(),
                        'scripts' => array()
                    ),

					'swiss_admin_tpls' => array(
						'Oxygen Templates List - Drop Down Menu in WP Admin topbar',
						'styles' => array(),
						'scripts' => array()
					),

                    'swiss_admin_pages' => array(
						'Pages List - Drop Down Menu in WP Admin topbar',
						'styles' => array(),
						'scripts' => array()
					),
                    
                    'swiss_system_clipboard' => array(
                        'Clipboard - Cut, Copy and Paste elements between pages or websites',
                        'styles' => array(),
                        'scripts' => array()
                    ),
                    
                    'swiss_show_clipboard_panel' => array(
                        'Show Clipboard icons in the topbar (Cut, Copy and Paste)',
                        'styles' => array(),
                        'scripts' => array('swk_clipboard')
                    ),
      



					// 'page_overlay' => array(
					// 	'Custom Page Overlay',
					// 	'styles' => array('page_overlay'),
					// 	'scripts' => array()
					// ),
				)
			),
			'swk_right_click' => array(
				'Right Click',
				array(
					'swiss_cut' => array(
						'Cut',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_copy' => array(
						'Copy',
						'styles' => array(),
						'scripts' => array()
					),
					/* 'swiss_copy_style' => array(
						'Copy Style',
						'styles' => array(),
						'scripts' => array()
					), */
					'swiss_copy_condition' => array(
						'Copy Conditions',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_paste' => array(
						'Paste',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_duplicate' => array(
						'Duplicate',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_make_reusable' => array(
						'Make Reusable',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_rename' => array(
						'Rename',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_set_conditions' => array(
						'Set Conditions',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_change_id' => array(
						'Change ID',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_wrap' => array(
						'Wrap with div',
						'styles' => array(),
						'scripts' => array()
					),
					'swiss_delete' => array(
						'Delete',
						'styles' => array(),
						'scripts' => array()
					),
				)
			),
			'swk_cleanup' => array(
				'Cleanup',
				array(
					'swk_clean_data' => array(
						'Delete Data when plugins is uninstalled',
						'styles' => array(),
						'scripts' => array()
					),
				)
			)
		);
		return $swiss_knife_options;
	}

    public function swiss_knife_cm_themes_array()
    {
        $cm_themes = array(
            'codemirror_theme__default_darker' => 'Default - Darker Bg',
            'codemirror_theme__swiss_knife_dark' => 'Swiss Knife',

            'codemirror_theme__oceanic' => 'Oceanic',
            'codemirror_theme__monokai' => 'Monokai',
            'codemirror_theme__cobalt' => 'Cobalt',
            'codemirror_theme__made_of_code' => 'Made of code',
            'codemirror_theme__plastic_code_wrap' => 'Plastic Code Wrap',
            'codemirror_theme__dracula' => 'Dracula',

            'codemirror_theme__swiss_knife_light' => 'Swiss Knife - Light',
            'codemirror_theme__eiffel' => 'Eiffel - Light',
            'codemirror_theme__idle' => 'Idle - Light',
        );
        return $cm_themes;
    }

    public function saveOFOptions_func()
    {
        check_ajax_referer('ajax-nonce', 'verify_nonce');
        parse_str($_POST['form_data'], $form_data);
        $theme_option = $_POST['theme_option'];
        update_option('swiss_knife_theme_type', $theme_option);
        $selected_options = array_keys($form_data);
        //print_r($selected_options); exit;
        $swiss_knife_options = $this->get_swiss_knife_options();
        foreach ($swiss_knife_options as $main_key => $main_val) {
            if (in_array($main_key, $selected_options)) {
                update_option($main_key, 'yes');
            } else {
                update_option($main_key, 'no');
            }
            foreach ($main_val[1] as $key => $val) {
                if (in_array($key, $selected_options)) {
                    update_option($key, 'yes');
                } else {
                    update_option($key, 'no');
                }
            }
        }
        if (isset($form_data['swiss_knife_structure_width'])) {
            update_option('swiss_knife_structure_width', $form_data['swiss_knife_structure_width']);
        }
        if (isset($form_data['swiss_knife_cm_theme'])) {
            update_option('swiss_knife_cm_theme', $form_data['swiss_knife_cm_theme']);
        }

        if (isset($form_data['swiss_font_value'])) {
            update_option('swiss_font_value', $form_data['swiss_font_value']);
        }

        echo 'Saved!';

        wp_die();
    }


    /*================================================
    =            Add File Version Numbers            =
    ================================================*/

    public function asset_version($path)
    {
        $file = SWK_DIR . $path;
        if (is_file($file)) {
            return filemtime($file);
        }

        return null;
    }

    public function swk_scripts_and_styles()
    {

        /*====================================
        =           Theme 2.0                =
        ====================================*/

        wp_register_style('swk_oxygen_theme', SWK_URL . 'features/css/theme.css',
            [],
            $this->asset_version('features/css/theme.css')
        );

        wp_register_style('swk_no_theme', SWK_URL . 'features/css/no_theme.css',
            [],
            $this->asset_version('features/css/no_theme.css')
        );


        /*================================
        =            Features            =
        ================================*/

        // Topbar

        // Debug
        wp_register_script('swk_feature_debug_js', SWK_URL . 'features/js/debug-min.js', array(), EDD_PLUGINVERSION, true);


        // Structure Panel

        //  icons * Fix Light theme
        wp_register_style('swk_feature_structure_icons', SWK_URL . 'features/css/structure_icons.css', array(), time());
       
        //  swk_comapact_add_elements
        // wp_register_style('swk_compact_add_elements', SWK_URL . 'features/css/compact_add_elements.css', array(), time());

        // Open Structure on load *
        wp_register_script('swk_feature_open_structure_on_load_js', SWK_URL . 'features/js/open_structure_on_load-min.js', array(), EDD_PLUGINVERSION, true);


        //  Measure Box Webflow Style
        wp_register_style('swk_bigger-measurebox', SWK_URL . 'features/css/bigger-measurebox.css', array(), time());


        // Left Sidebar

        // Breakpoints Size
        // wp_register_script( 'swk_breakpoints_size', SWK_URL . 				'features/js/expanded_breakpoints-min.js', array(), '1.0.0', true );
        // wp_register_style( 'swk_breakpoints_size_css', SWK_URL . 			'features/css/expanded_breakpoints.css',  array(), time() );


        // Codemirror Base
        wp_register_style('swk_quick_code_hint_css', SWK_URL . 'features/css/code_hint.css', array(), time());
        wp_register_script('swk_quick_code_hint', SWK_URL . 'features/js/code_hint-min.js', array(), EDD_PLUGINVERSION, true);

        wp_register_style('swk_quick_emmet_css', SWK_URL . 'features/vendors/codemirror/emmet-min.css', array(), time());
        wp_register_script('swk_quick_emmet_browser', SWK_URL . 'features/vendors/codemirror/browser-min.js', array(), EDD_PLUGINVERSION, true);


        // Codemirror Hint
        wp_register_style('swk_quick_show_hint', SWK_URL . 'features/vendors/codemirror/hint/show-hint-min.css', array(), time());
        wp_register_script('swk_quick_show_hint', SWK_URL . 'features/vendors/codemirror/hint/show-hint-min.js', array(), EDD_PLUGINVERSION, true);
        wp_register_script('swk_quick_show_hint_html_hint', SWK_URL . 'features/vendors/codemirror/hint/html-hint-min.js', array(), EDD_PLUGINVERSION, true);
        wp_register_script('swk_quick_show_hint_javascript_hint', SWK_URL . 'features/vendors/codemirror/hint/javascript-hint-min.js', array(), EDD_PLUGINVERSION, true);
        wp_register_script('swk_quick_show_hint_xml_hint', SWK_URL . 'features/vendors/codemirror/hint/xml-hint-min.js', array(), EDD_PLUGINVERSION, true);


        // Codemirror AddOn
        wp_register_script('swk_quick_closebrackets', SWK_URL . 'features/vendors/codemirror/addon/closebrackets-min.js', array(), EDD_PLUGINVERSION, true);
        wp_register_script('swk_quick_matchbrackets', SWK_URL . 'features/vendors/codemirror/addon/matchbrackets-min.js', array(), EDD_PLUGINVERSION, true);


        // Codemirror Themes Default
        wp_register_style('codemirror_theme__default_darker', SWK_URL . 'features/css/code_themes/codemirror_theme__default_darker.css', array(), time());
        wp_register_style('codemirror_theme__swiss_knife_dark', SWK_URL . 'features/css/code_themes/codemirror_theme__swiss_knife_dark.css', array(), time());
        wp_register_style('codemirror_theme__swiss_knife_light', SWK_URL . 'features/css/code_themes/codemirror_theme__swiss_knife_light.css', array(), time());

        // Codemirror Themes Dark
        wp_register_style('codemirror_theme__oceanic', SWK_URL . 'features/css/code_themes/codemirror_theme__oceanic.css', array(), time());
        wp_register_style('codemirror_theme__monokai', SWK_URL . 'features/css/code_themes/codemirror_theme__monokai.css', array(), time());
        wp_register_style('codemirror_theme__cobalt', SWK_URL . 'features/css/code_themes/codemirror_theme__cobalt.css', array(), time());
        wp_register_style('codemirror_theme__made_of_code', SWK_URL . 'features/css/code_themes/codemirror_theme__made_of_code.css', array(), time());
        wp_register_style('codemirror_theme__plastic_code_wrap', SWK_URL . 'features/css/code_themes/codemirror_theme__plastic_code_wrap.css', array(), time());
        wp_register_style('codemirror_theme__dracula', SWK_URL . 'features/css/code_themes/codemirror_theme__dracula.css', array(), time());

        // Codemirror Themes Light
        wp_register_style('codemirror_theme__eiffel', SWK_URL . 'features/css/code_themes/codemirror_theme__eiffel.css', array(), time());
        wp_register_style('codemirror_theme__idle', SWK_URL . 'features/css/code_themes/codemirror_theme__idle.css', array(), time());


        // Clipboard
        wp_register_script('swk_clipboard', SWK_URL . 'features/js/clipboard-min.js', array(), EDD_PLUGINVERSION, true);
        wp_register_script('swk_clipboard_panel', SWK_URL . 'features/js/clipboard-panel-min.js', array(), EDD_PLUGINVERSION, true);


        // Page Overlay
        // wp_register_style( 'page_overlay', SWK_URL . 						'features/css/page_overlay.css', array(), time() );


        /*================================
        =      Codemirror CSS            =
        ================================*/
        $swiss_knife_cm_themes = $this->swiss_knife_cm_themes_array();
        foreach ($swiss_knife_cm_themes as $key => $val) {
            wp_register_style('swk_feature_cm_' . $key, SWK_URL . 'features/css/code_themes/' . $key . '.css', array(), time());
        }

        $swiss_knife_options = $this->get_swiss_knife_options();
        foreach ($swiss_knife_options as $main_key => $main_val) {
            foreach ($main_val[1] as $key => $val) {
                if ($key != "swiss_knife_structure_custom_width" && $key != "swiss_knife_open" && $key != "swiss_class_lock") {
                    $option_value = get_option($key);
                    if ($option_value == "yes") {
                        if (!empty($val['styles'])) {
                            foreach ($val['styles'] as $style) {
                                if (!wp_style_is($style, 'enqueued')) {
                                    wp_enqueue_style($style);
                                }
                            }
                        }
                        if (!empty($val['scripts'])) {
                            foreach ($val['scripts'] as $script) {
                                if (!wp_script_is($script, 'enqueued')) {
                                    wp_enqueue_script($script);
                                    if ($script == "swk_feature_import_classes") {
                                        wp_localize_script($script, 'swk_importclasses_ajax', array(
                                            'ajaxurl' => admin_url('admin-ajax.php'),
                                            'swk_nonce' => wp_create_nonce('ajax-nonce'),
                                        ));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        /*================================================
        =            This is for Select Theme            =
        ================================================*/
        $swiss_knife_theme_type = get_option('swiss_knife_theme_type');
        if ($swiss_knife_theme_type == "no_theme") {
            wp_enqueue_style('swk_no_theme');
        } else {
            wp_enqueue_style('swk_oxygen_theme');
        }

        /*================================================
        =       This is for Code mirror Theme            =
        ================================================*/
        $swiss_knife_codemirror = get_option('swiss_knife_codemirror');
        if ($swiss_knife_codemirror == "yes") {
            $swiss_knife_cm_theme = get_option('swiss_knife_cm_theme');
            wp_enqueue_style('swk_feature_cm_' . $swiss_knife_cm_theme);
        }

		/*================================================
		=             This is for Right Click            =
		================================================*/
        if (get_option('swiss_show_clipboard_panel')) {
            wp_enqueue_script('swk_clipboard_panel');
        }

        wp_enqueue_script('swk_clipboard');

		$swiss_knife_codemirror = get_option('swiss_knife_codemirror');
		if($swiss_knife_codemirror == "yes"){
			$swiss_knife_cm_theme = get_option('swiss_knife_cm_theme');
			wp_enqueue_style('swk_feature_cm_'.$swiss_knife_cm_theme);
		}
	}

    public function right_click_options()
    {
        $right_click_features = array('swiss_cut', 'swiss_copy', 'swiss_copy_style', 'swiss_copy_condition', 'swiss_paste', 'swiss_duplicate', 'swiss_make_reusable', 'swiss_rename', 'swiss_set_conditions', 'swiss_change_id', 'swiss_wrap', 'swiss_delete');
        return $right_click_features;

    }

    /*=============================================
    =            Debug Style in iframe            =
    =============================================*/
    public function swk_styles_inside_iframe()
    {
        // Debug
        wp_enqueue_style('swk_feature_debug_iframe', SWK_URL . 'features/css/debug_iframe.css');
        // Class lock *
        $swiss_class_lock = get_option('swiss_class_lock');
        if ($swiss_class_lock == "yes") {
            wp_enqueue_script('swk_feature_class_lock_iframe', SWK_URL . 'features/js/class-manager-iframe-min.js', array(), time(), true);
        }

        // right click
        $right_click_features = $this->right_click_options();
        foreach ($right_click_features as $rc_feature) {
            if (get_option($rc_feature) == "yes") {
                wp_enqueue_script('swk_right_click_iframe', SWK_URL . 'features/js/rightclick-min.js', array(), time(), true);
                break;
            }
        }
    }

    // Load only if licence is active and Oxygen Builder Editor is enabled
    public function load_if_active()
    {
        $status = get_option('swiss_knife_license_status');

        if ($status !== false && $status == 'valid') {

            //Return early if the webpage is being edited by Oxygen editor.
            if (isset ($_GET['ct_builder']) && 'true' === $_GET['ct_builder']) {

                add_action('wp_head', array($this, 'swk_head_hook'), -1);
                add_action('wp_footer', array($this, 'swk_scripts_and_styles'));
                add_action('oxygen_enqueue_iframe_scripts', array($this, 'swk_styles_inside_iframe'));
                add_action('ct_before_builder', array($this, 'ct_before_builder_buttons'));
                add_action('oxygen_enqueue_ui_scripts', array($this, 'swk_enqueue_builder_scripts'));

            }
            add_action('wp_enqueue_scripts', array($this, 'swiss_knife_font_enqueue_stylesheet'), -2000);
        }
    }

    public function swk_head_hook()
    {
        $swiss_fontsize = get_option('swiss_fontsize');
        $swiss_font_value = get_option('swiss_font_value');
        if ($swiss_fontsize == "yes" && !empty($swiss_font_value)) {
            echo '<style>
				.oxygen-sidebar-code-editor-wrap .CodeMirror {
					font-size: ' . $swiss_font_value . 'px !important;
				}
			</style>';
        }
    }

	/*================================
	=            Features            =
	================================*/
	function ct_before_builder_buttons(){
		$swiss_knife_debug = get_option('swiss_knife_debug');
		if($swiss_knife_debug == "yes"){
			require_once (SWK_DIR.'features/inc/debug_bar.php');
		}

		$swiss_show_clipboard_panel = get_option('swiss_show_clipboard_panel');
		if($swiss_show_clipboard_panel == "yes"){
			require_once (SWK_DIR.'features/inc/clipboard.php');
		}

        $swiss_advanceSettings = get_option('swiss_advanceSettings');
        if ($swiss_advanceSettings == "yes") {
            require_once(SWK_DIR . 'features/inc/advance_settings.php');
        }
    }

    public function swiss_knife_font_enqueue_stylesheet()
    {
        $swiss_font_manager = get_option('swiss_font_manager');
        if ($swiss_font_manager != "yes") {
            return;
        }
        $upload_dir = wp_upload_dir();
        $filepath = $upload_dir['basedir'] . '/swiss-knife' . "/skf.css";
        if (file_exists($filepath)) {
            $file_url = $upload_dir['baseurl'] . '/swiss-knife' . '/skf.css';
            wp_enqueue_style('swiss-knife-custom-fonts', $file_url, array(), time(), true, 'all');
        }
    }

    public function swiss_knife_preload_font_file($html, $handle)
    {
        if ($handle === 'swiss-knife-custom-fonts') {
            $new_html = "";
            global $wpdb;
            $swiss_knife_fonts = $wpdb->prefix . "swiss_knife_fonts";
            $swiss_knife_font_faces = $wpdb->prefix . "swiss_knife_font_faces";
            $all_fonts = $wpdb->get_results("SELECT * FROM $swiss_knife_fonts");
            if (!empty($all_fonts)) {
                $font_file = "";
                foreach ($all_fonts as $font) {
                    $font_id = $font->id;
                    $all_font_faces = $wpdb->get_results("SELECT * FROM $swiss_knife_font_faces WHERE font_id = '$font_id' AND font_preload = 'yes' ORDER BY font_weight ASC");
                    if (!empty($all_font_faces)) {
                        foreach ($all_font_faces as $font_face) {
                            $font_file = $font_face->font_file;
                            if (!empty($font_file) && !empty($font_face->font_file_2)) {
                                $new_html .= '<link rel="preload" as="font" href="' . $font_face->font_file_2 . '" type="font/woff2" crossorigin>' . PHP_EOL;
                            } else {
                                if (!empty($font_file)) {
                                    $new_html .= '<link rel="preload" as="font" href="' . $font_file . '" type="font/woff" crossorigin>' . PHP_EOL;
                                }
                                if (!empty($font_face->font_file_2)) {
                                    $new_html .= '<link rel="preload" as="font" href="' . $font_face->font_file_2 . '" type="font/woff2" crossorigin>' . PHP_EOL;
                                }
                            }
                        }
                    }
                }
            }
            $html = str_replace('media="1"', 'media="all"', $html);
            $html = str_replace("media='1'", "media='all'", $html);
            $new_html .= $html;
            ///$new_html .= str_replace("rel='stylesheet'", 'rel="preload" as="font" type="font/woff2" crossorigin', $html);
            return $new_html;
        }
        return $html;
    }

    public function swk_enqueue_builder_scripts()
    {
        $status = get_option('swiss_knife_license_status');

        if ($status !== false && $status == 'valid') {
            // Custom structure width *
            wp_register_script('swk_feature_structure_width_js', SWK_URL . 'features/js/structure_width-min.js', array(), EDD_PLUGINVERSION, true);

            /* for structure width js */
            $swk_width = get_option('swiss_knife_structure_custom_width');
            $swiss_knife_open = get_option('swiss_knife_open');
            $swk_width_value = get_option('swiss_knife_structure_width');
            if (empty($swk_width_value)) {
                $swk_width_value = 300;
            }
            if ($swk_width == "yes") {
                $swk_width = true;
            } else {
                $swk_width = false;
            }
            if ($swiss_knife_open == "yes") {
                $swiss_knife_open = true;
            } else {
                $swiss_knife_open = false;
            }
            $settings = array(
                'swk_width' => $swk_width,
                'swiss_knife_open' => $swiss_knife_open,
                'swk_width_value' => $swk_width_value,
            );
            $swiss_knife_structure_custom_width = get_option('swiss_knife_structure_custom_width');
            $swiss_knife_open = get_option('swiss_knife_open');
            if ($swiss_knife_structure_custom_width == "yes" || $swiss_knife_open == "yes") {
                wp_enqueue_script('swk_feature_structure_width_js');
                wp_localize_script("swk_feature_structure_width_js", 'swk', [
                    'settings' => $settings,
                ]);
            }

            // import classes
            wp_register_style('swk_import-classes', SWK_URL . 'features/css/import-classes.css');
            wp_register_script('swk_feature_import_classes', SWK_URL . 'features/js/import-classes.min.js', array(), EDD_PLUGINVERSION, true);
            add_action('body_class', array($this, 'swk_editor_body_class'));

            // Class lock *
            $swiss_class_lock = get_option('swiss_class_lock');
            if ($swiss_class_lock == "yes") {
                wp_enqueue_style('swk_feature_class_lock', SWK_URL . 'features/css/class-manager.css', array(), time());
                wp_enqueue_script('swk_feature_class_lock', SWK_URL . 'features/js/class-manager-min.js', array(), time(), true);
            }

            $use_filtered_tab = get_option('swk_filtered_tab_data');
            if ($use_filtered_tab == "yes") {
                wp_enqueue_style('swk_feature_filtered_tab', SWK_URL . 'features/css/filtered-tab.css', array(), time());
                wp_enqueue_script('swk_feature_filtered_tab', SWK_URL . 'features/js/filtered-tab-min.js', array(), time(), true);
            }

            // Advanced settings *
            $swiss_advanceSettings = get_option('swiss_advanceSettings');
            if ($swiss_advanceSettings == "yes") {
                wp_enqueue_script('advanced_settings_min', SWK_URL . 'features/js/advanced-settings-min.js', array(), time(), true);
            }

            // breakpoints
            $swiss_knife_expanded_breakpoints = get_option('swiss_knife_expanded_breakpoints');
            if ($swiss_knife_expanded_breakpoints == "yes") {
                wp_enqueue_script('swk-expanded-breakpoints', SWK_URL . 'features/js/expanded_breakpoints-min.js', array(), time(), true);
            }
            wp_enqueue_script('swk_theme_js', SWK_URL . 'features/js/theme-min.js', array(), time(), true);
        }
    }

    public function swk_render_template()
    {
        if (!isset ($_GET['ct_builder'])) {
            return;
        }
        if ('true' !== $_GET['ct_builder']) {
            return;
        }
        $swiss_knife_structure_custom_width = get_option('swiss_knife_structure_custom_width');
        $swiss_knife_open = get_option('swiss_knife_open');
        $swiss_shortcuts = get_option('swiss_shortcuts');
        if (($swiss_knife_open == "yes" || $swiss_knife_structure_custom_width == "yes" || $swiss_shortcuts == "yes") && defined("SHOW_CT_BUILDER")) {
            ?>
            <div swiss-knife></div>
            <?php
        }

        /* right click */
        $right_click_features = $this->right_click_options();
        foreach ($right_click_features as $rc_feature) {
            if (get_option($rc_feature) == "yes") {
                require_once(SWK_DIR . 'features/inc/right_click.php');
                break;
            }
        }
        /* right click */

        if ($swiss_shortcuts == "yes") {
            /* shortcuts list */
            $SWK_Shortcuts = new SWK_Shortcuts();
            $shortcuts = $SWK_Shortcuts->shortcuts_settings();
            $all_shortcuts = $SWK_Shortcuts->key_to_name();
            ?>
            <div class="shortcuts-wrap active-list" style="display: none;">
                <div class="shortcuts__list">
                    <h1 class="shortcuts__title">Shortcuts</h1>
                    <ul>
                        <?php
                        $html = '';
                        foreach ($all_shortcuts as $key => $val) {
                            if (!empty($shortcuts)) {
                                if (isset($shortcuts[$key]['key'])) {
                                    $command_array = array();
                                    $command_array[] = '<div class="shortcut__name">' . $val . '</div>';
                                    if (isset($shortcuts[$key]) && (strpos($shortcuts[$key]['combination'], 'ctrl') !== false || strpos($shortcuts[$key]['combination'], 'command') !== false)) {
                                        $command_array[] .= '<div class="shortcut__key">Ctrl / ⌘</div>';
                                    }
                                    if (isset($shortcuts[$key]) && (strpos($shortcuts[$key]['combination'], 'alt') !== false || strpos($shortcuts[$key]['combination'], 'option') !== false)) {
                                        $command_array[] = '<div class="shortcut__key">Alt / ⌥</div>';
                                    }
                                    if (isset($shortcuts[$key]) && (strpos($shortcuts[$key]['combination'], 'shift') !== false || strpos($shortcuts[$key]['combination'], 'shift') !== false)) {
                                        $command_array[] = '<div class="shortcut__key">Shift</div>';
                                    }
                                    if ($shortcuts[$key]['key']) {
                                        $command_array[] = '<div class="shortcut__key">' . $shortcuts[$key]['key'] . '</div>';
                                    }
                                    $html .= '<li>' . implode("", $command_array) . '</li>';
                                }
                            }
                        }
                        echo $html;
                        ?>
                    </ul>
                </div>
            </div>
            <?php
            /* shortcuts list */
        }

        if (get_option('swiss_class_lock') == "yes") {
            /* shortcut to add multiple classes at once on current element */
            $ct_style_folders = get_option('ct_style_folders');
            $ct_components_classes = get_option('ct_components_classes');
            $foldered_classes = array();
            $uncategorized_classes = array();
            $parent = '';
            if (!empty($ct_components_classes)) {
                foreach ($ct_components_classes as $class => $ct_class) {
                    if (isset($ct_class['parent'])) {
                        $parent = $ct_class['parent'];
                        $foldered_classes[$parent][] = $class;
                    } else {
                        $uncategorized_classes[] = $class;
                    }
                }
            }
            $multiple_classes_html = '<div class="shortcuts-classes-wrap active-list" style="display:none;">';
            $multiple_classes_html .= '<div class="shortcuts__list"><input class="swk-class-search" placeholder="Search for names..">
				<div id="class-not-found" style="display:none;">
					<h1>That class is not found</h1>
					<p>add comma separated classes to assign multiple</p>
					<a href="#" id="create-class">Create class</a>
				</div>
			<div id="insert_class_wrap"> ';
            if (!empty($uncategorized_classes)) {
                $multiple_classes_html .= '<h1 class="shortcuts__title">Uncategorized</h1>
					<ul>';
                foreach ($uncategorized_classes as $uncategorized_class) {
                    $multiple_classes_html .= '<li><span data-class="' . $uncategorized_class . '" class="swk-multiple-class">.' . $uncategorized_class . '</span></li>';
                }
                $multiple_classes_html .= '</ul>';
            }
            if (!empty($foldered_classes)) {
                foreach ($foldered_classes as $key => $classes) {
                    $multiple_classes_html .= '<h1 class="shortcuts__title">' . $key . '</h1>
					<ul>';
                    foreach ($classes as $class) {
                        $multiple_classes_html .= '<li><span data-class="' . $class . '" class="swk-multiple-class">.' . $class . '</span></li>';
                    }
                    $multiple_classes_html .= '</ul>';
                }
            }
            $multiple_classes_html .= '</div><div class="class-manager-actions"><button type="button" id="swk-multiple-class-save">Update Selection</button><button type="button" id="swk-multiple-class-close">Close</button></div></div></div><style>.shortcuts-classes-wrap.active-list {display:block;}</style>';
            echo $multiple_classes_html;
            /* echo "<pre>"; print_r($ct_style_folders); "</pre>";
            echo "<pre>"; print_r($ct_components_classes); "</pre>"; */
            /* shortcut to add multiple classes at once on current element */
        }
    }

    public function swk_editor_body_class($classes)
    {
        $swiss_knife_structure_custom_width = get_option('swiss_knife_structure_custom_width');
        if (isset($swiss_knife_structure_custom_width) && $swiss_knife_structure_custom_width == "yes") {
            $classes[] = "swk-structure-custom-width";
        }

        return $classes;
    }

    public function body_classes($classes)
    {
        $swiss_class_lock = get_option('swiss_class_lock');
        if ($swiss_class_lock == "yes" && isset ($_GET['ct_builder']) && 'true' === $_GET['ct_builder']) {
            $classes[] = 'swiss-class-lock';
        }
        return $classes;
    }

    public function oxy_component_configurations()
    {
        $swiss_hide_content_edit = get_option('swiss_hide_content_edit');
        if ($swiss_hide_content_edit == "yes" && isset ($_GET['ct_builder']) && 'true' === $_GET['ct_builder']) {
            global $oxygen_vsb_components;
            $unset_elements = array('headline', 'text_block', 'link_button', 'link_text');
            foreach ($unset_elements as $unset_element) {
                $i = 0;
                foreach ($oxygen_vsb_components[$unset_element]->options['params'] as $element) {
                    if (isset($element['param_name']) && $element['param_name'] == "ct_content") {
                        unset($oxygen_vsb_components[$unset_element]->options['params'][$i]);
                    }
                    $i++;
                }
                $unset_element_array = array_values($oxygen_vsb_components[$unset_element]->options['params']);
                $oxygen_vsb_components[$unset_element]->options['params'] = $unset_element_array;
            }
            //echo "<pre>"; print_r($oxygen_vsb_components); "</pre>"; exit;
        }
    }
}

new SWK_features_settings();
