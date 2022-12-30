<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*====================================================
=            Create Options page and Menu            =
====================================================*/

class SWK_template_page_list {
	public function __construct(){
		$status  = get_option( 'swiss_knife_license_status' );

		if( $status !== false && $status == 'valid' ) { 

			//Return early if the webpage is being edited by Oxygen editor.
			if ( isset ( $_GET['ct_builder'] ) && 'true' === $_GET['ct_builder'] ) {
				add_action( 'ct_before_builder', array($this, 'template_list_render') );
				add_action( 'oxygen_enqueue_ui_scripts', array($this, 'enqueue_scripts') );
			}
		}
	}

	public function enqueue_scripts(){
		$swiss_t_list = get_option('swiss_t_list');
		$swiss_p_list = get_option('swiss_p_list');
		if($swiss_p_list == "yes" || $swiss_t_list == "yes"){
			wp_enqueue_style( 'swk-template-page', SWK_URL . 'features/css/pages-templates-list.css', array(), time() );
			wp_enqueue_script( 'swk-template-page', SWK_URL . 'features/js/pages-templates-list.min.js', array(), time(), true );
		}
	}

	public function template_list_render(){
		$swiss_t_list = get_option('swiss_t_list');
		$swiss_p_list = get_option('swiss_p_list');
		if($swiss_p_list == "yes" || $swiss_t_list == "yes"){
			echo '<div class="swk-oxy-templates-copy" style="display:none;">
			<div class="oxygen-toolbar-menus swk-oxy-templates-main">';
			if($swiss_p_list == "yes"){
				$this->pages_list();
			}

			if($swiss_t_list == "yes"){
				$this->oxy_template_lists();
			}
			echo '</div></div>';
		}
	}

	public function pages_list(){
		$pages = get_posts(array(
            'post_type' => 'page',
            'posts_per_page' => -1,
        ));
        if($pages){
        	$html = '
			<div class="oxygen-manage-menu oxygen-toolbar-button oxygen-select swk-oxy-templates pages">
					<div class="oxygen-toolbar-button-dropdown">';
						$edit_link_href = '';
						foreach($pages as $page){
							$post_ID = $page->ID;
    						$post_type = $page->post_type;
							$edit_link_href = '';
							if ($post_type == "ct_template") {
								$template_type = get_post_meta($post_ID, 'ct_template_type', true);
								
								$edit_link_href = ct_get_post_builder_link($post_ID);
								
								if ($template_type !== 'reusable_part') {
									$parent_template_inner = false;
									$parent_template = get_post_meta($post_ID, 'ct_parent_template', true);
									
									if ($parent_template) {
										$shortcodes = get_post_meta($parent_template, 'ct_builder_shortcodes', true);
										
										if ($shortcodes && strpos($shortcodes, '[ct_inner_content') !== false) {
											$parent_template_inner = true;
										}
									}
								
									if ($parent_template_inner) {
										$edit_link_href = add_query_arg('ct_inner', 'true', $edit_link_href);
									}
								}
							} else if ($post_type == "oxy_user_library") {
								$edit_link_href = ct_get_post_builder_link($post_ID);
							} else {
								// Get post template
								$post_template = intval(get_post_meta($post_ID, 'ct_other_template', true));
								
								// Check if we should edit the post or it's template
								$edit_template = false;
								$post_editable = false;
								$template_inner = false;
								
								if ($post_template == 0) { // default template
									// Get default template
									$default_template = null;
									
									if (get_option('page_for_posts') == $post_ID || get_option('page_on_front') == $post_ID ) {
										$default_template = ct_get_archives_template( $post_ID );
									}
									
									if (empty($default_template)) {
										$default_template = ct_get_posts_template($post_ID);
									}
									
									if ($default_template) {
										$shortcodes = get_post_meta($default_template->ID, 'ct_builder_shortcodes', true);
										
										if ($shortcodes && strpos($shortcodes, '[ct_inner_content') !== false) {
											$post_editable = true;
											$template_inner = true;
										} else {
											$edit_template = $default_template->ID;
										}
									} else {
										$post_editable = true;
									}
								} else if ($post_template == -1) { // None
									$post_editable = true;
								} else { // Custom template
									$shortcodes = get_post_meta($post_template, 'ct_builder_shortcodes', true);
									
									if ($shortcodes && strpos($shortcodes, '[ct_inner_content') !== false) {
										$post_editable = true;
										$template_inner = true;
									} else {
										$edit_template = $post_template;
									}
								}
								
								// Generate edit link
								if ($post_editable) {
									$edit_link_href = ct_get_post_builder_link($post_ID);
									
									if ($template_inner) {
										$edit_link_href = add_query_arg('ct_inner', 'true', $edit_link_href);
									}
								} else if ($edit_template) {
									$edit_link_href = ct_get_post_builder_link($edit_template);
								}
							}
							$html .= '<div class="oxygen-toolbar-button-dropdown-option">
								<div class="name">'.$page->post_title.'</div>
								<a href="'.get_permalink($page->ID).'" target="_blank">Preview</a>
								<a href="'.esc_url(site_url().'/wp-admin/post.php?post='.$page->ID.'&action=edit').'" target="_blank">Editor</a>
								<a href="'.$edit_link_href.'" target="_blank">Builder</a>
							</div>';					
						}
            		$html .= '</div>
						<img src=" '.SWK_URL . 'features/img/topbar/page-icon.svg">
					</div>';
            echo $html;
        }
	}

	public function oxy_template_lists(){
		$ct_templates = get_posts(array(
            'post_type' => 'ct_template',
            'posts_per_page' => -1,
        ));
        if($ct_templates){
        	$html = '
			<div class="oxygen-manage-menu oxygen-toolbar-button oxygen-select swk-oxy-templates pages">
        		
					<div class="oxygen-toolbar-button-dropdown">';
						foreach($ct_templates as $ct_template){
							$html .= '<div class="oxygen-toolbar-button-dropdown-option">
							<div class="name">'.$ct_template->post_title.'</div>
                            <a href="'.esc_url(site_url().'/wp-admin/post.php?post='.$ct_template->ID.'&action=edit').'" target="_blank">Preview</a>
                            <a href="'.esc_url(site_url().'/?ct_template='.$ct_template->post_name.'&action=edit'.'&ct_builder=true').'" target="_blank">Builder</a>
					</div>';					
            }
            $html .= '</div>
						<img src=" '.SWK_URL . 'features/img/topbar/template-icon.svg">
					</div>';
            echo $html;
        }
	}
}

new SWK_template_page_list();