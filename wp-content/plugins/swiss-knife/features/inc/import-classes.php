<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SWK_import_classes {
	public function __construct(){
		add_action( 'oxygen_vsb_settings_content', array($this, 'swk_oxygen_right_panel_tabs') );
		add_action( 'wp_ajax_loadClassesFolders', array($this, 'loadClassesFolders_func') );
		add_action( 'wp_ajax_saveCSSClasses', array($this, 'saveCSSClasses_func') );
	}

	public function swk_oxygen_right_panel_tabs(){
		$swiss_import_classes = get_option('swiss_import_classes');
		if($swiss_import_classes != "yes"){
			return;
		}
		$status  = get_option( 'swiss_knife_license_status' );

		if( $status !== false && $status == 'valid' ) { 
				global $oxygen_toolbar; ?>

			<div id="swk-ic-tab" class="oxygen-sidebar-advanced-subtab oxygen-settings-main-tab on-main-tab swk-ic-tab" ng-click="switchTab('settings','swiss_knife_ic');" ng-hide="hasOpenTabs('settings')">
			    <img style="width:17px;margin-left:10px;" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjYiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyNiAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMyAyMEMxOC41MjI4IDIwIDIzIDE1LjUyMjggMjMgMTBDMjMgNC40NzcxNSAxOC41MjI4IDAgMTMgMEM3LjQ3NzE1IDAgMyA0LjQ3NzE1IDMgMTBDMyAxNS41MjI4IDcuNDc3MTUgMjAgMTMgMjBaTTEyIDguNVYzSDE0VjguNUgxOS41VjEwLjVIMTRWMTZIMTJWMTAuNUg2LjVWOC41SDEyWiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==" />
			    <?php _e("Import Classes", "oxygen"); ?>
			    <img src='<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg' />
			</div>

			<div class="oxygen-sidebar-flex-panel" ng-if="isShowTab('settings','swiss_knife_ic')&&!hasOpenChildTabs('settings','swiss_knife_ic')">

			    <?php $oxygen_toolbar->settings_home_breadcrumbs(__("Import Classes", "oxygen")); ?>
			    <div class='oxygen-control-wrapper' style='margin-bottom:14px;'>
				    <label class="oxygen-control-label">Paste comma separated classes</label>
				    <div class='oxygen-control'>
				        <textarea rows="5" id="swk-classes"></textarea>
				        <?php
				        	$ct_style_folders = get_option('ct_style_folders');
				        ?>
				        <select id="swk-class-folders">
				        	<?php
				        		if(!empty($ct_style_folders)){
				        			foreach($ct_style_folders as $ct_style_folder){
				        				echo '<option value="'.$ct_style_folder['key'].'">'.$ct_style_folder['key'].'</option>';
				        			}
				        		}
				        	?>
				    	</select>
				    	<div class="swk-lock-imported-classes-wrap">
				    		<label class="of-input"><input type="checkbox" id="swk-lock-imported-classes" /> Lock classes</label>
						</div>						
				        <button id="swk-import-classes">Import</button>
				        <div class="swk-ic-alert swk-ic-success" style="display: none;">Saved!</div>
				        <div class="swk-ic-alert swk-ic-error" style="display: none;">Please add some classes</div>
				        <div class="swk-ic-alert swk-ic-error-folder" style="display: none;">You don't have any classes folder! Please create one.</div>
				    </div>
				</div>
			</div>
	<?php
		}
	}

	public function loadClassesFolders_func(){
		check_ajax_referer('ajax-nonce', 'verify_nonce');
		$css_folders = "";
		$ct_style_folders = get_option('ct_style_folders');
		if(!empty($ct_style_folders)){
			foreach($ct_style_folders as $ct_style_folder){
				$css_folders .= '<option value="'.$ct_style_folder['key'].'">'.$ct_style_folder['key'].'</option>';
			}
		}

		echo $css_folders;

	  	wp_die();
	}

	public function saveCSSClasses_func(){
		check_ajax_referer('ajax-nonce', 'verify_nonce');
		$locked = $_POST['locked'];
		$swk_classes = trim(preg_replace('~[\r\n]+~', '', $_POST['swk_classes']));
		$swk_class_folder = $_POST['swk_class_folder'];
		$ct_components_classes = get_option('ct_components_classes');
		if(empty($ct_components_classes)){
			$ct_components_classes = array();
		}

		if(!empty($swk_classes)){
			$swk_classes_array = explode(",", $swk_classes);
			foreach($swk_classes_array as $css_class){
				$css_class = str_replace(" ", "", $css_class);
				$ct_components_classes[$css_class] = array(
					'key' => $css_class,
					'parent' => $swk_class_folder,
					'original' => array()
				);
				if($locked == "yes"){
					$ct_components_classes[$css_class]['locked'] = '';
				}
			}

			update_option('ct_components_classes', $ct_components_classes);
		}
		$return_array = array();
		$return_array['classes'] = $ct_components_classes;

		echo json_encode($return_array);

	  	wp_die();
	}
}

new SWK_import_classes();