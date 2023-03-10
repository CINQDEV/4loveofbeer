<?php

if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('acfe_pro_hooks')):

class acfe_pro_hooks{
    
    /**
     * construct
     */
    function __construct(){
    
        add_filter('acf/load_field',    array($this, 'load_field'), 15);
        add_filter('acf/prepare_field', array($this, 'prepare_field'), 15);
        add_action('acf/render_field',  array($this, 'pre_render_field'), 8);
        add_action('acf/render_field',  array($this, 'replace_render_field'), 9);
        add_action('acf/render_field',  array($this, 'render_field'), 15);
    
        add_filter('acf/load_value',    array($this, 'load_value'), 15, 3);
        add_filter('acf/update_value',  array($this, 'update_value'), 15, 3);
        add_filter('acf/format_value',  array($this, 'format_value'), 15, 3);
        add_filter('acf/validate_value',array($this, 'validate_value'), 15, 4);
        add_action('acf/delete_value',  array($this, 'delete_value'), 15, 3);
        
    }
    
    
    /**
     * load_field
     *
     * @param $field
     *
     * @return mixed
     */
    function load_field($field){
        
        if(!$this->validate_hook($field, 'load_field')){
            return $field;
        }
        
        return call_user_func_array($field['callback']['load_field'], array($field));
        
    }
    
    
    /**
     * prepare_field
     *
     * @param $field
     *
     * @return mixed
     */
    function prepare_field($field){
    
        if(!$this->validate_hook($field, 'prepare_field')){
            return $field;
        }
        
        return call_user_func_array($field['callback']['prepare_field'], array($field));
        
    }
    
    
    /**
     * pre_render_field
     *
     * @param $field
     */
    function pre_render_field($field){
    
        if(!$this->validate_hook($field, 'pre_render_field')){
            return;
        }
    
        call_user_func_array($field['callback']['pre_render_field'], array($field));
        
    }
    
    
    /**
     * replace_render_field
     *
     * @param $field
     */
    function replace_render_field($field){
    
        if(!$this->validate_hook($field, 'replace_render_field')){
            return;
        }
    
        call_user_func_array($field['callback']['replace_render_field'], array($field));
    
        $field_class = acf_get_field_type($field['type']);
        $field_key = $field['key'];
        
        if(method_exists($field_class, 'render_field')){
            
            add_action("acf/render_field/type={$field['type']}", function($field) use($field_class, $field_key){
                
                if(!has_action("acf/render_field/type={$field['type']}", array($field_class, 'render_field'))){
                    add_action("acf/render_field/type={$field['type']}", array($field_class, 'render_field'), 9);
                }
                
                if($field['key'] !== $field_key){
                    return;
                }
                
                remove_action("acf/render_field/type={$field['type']}", array($field_class, 'render_field'), 9);
            
            }, 8);
    
        }
        
    }
    
    
    /**
     * render_field
     *
     * @param $field
     */
    function render_field($field){
        
        if(!$this->validate_hook($field, 'render_field')){
            return;
        }
        
        call_user_func_array($field['callback']['render_field'], array($field));
        
    }
    
    
    /**
     * load_value
     *
     * @param $value
     * @param $post_id
     * @param $field
     *
     * @return mixed
     */
    function load_value($value, $post_id, $field){
        
        if(!$this->validate_hook($field, 'load_value')){
            return $value;
        }
        
        return call_user_func_array($field['callback']['load_value'], array($value, $post_id, $field));
        
    }
    
    
    /**
     * update_value
     *
     * @param $value
     * @param $post_id
     * @param $field
     *
     * @return mixed
     */
    function update_value($value, $post_id, $field){
        
        if(!$this->validate_hook($field, 'update_value')){
            return $value;
        }
        
        return call_user_func_array($field['callback']['update_value'], array($value, $post_id, $field));
        
    }
    
    
    /**
     * format_value
     *
     * @param $value
     * @param $post_id
     * @param $field
     *
     * @return mixed
     */
    function format_value($value, $post_id, $field){
        
        if(!$this->validate_hook($field, 'format_value')){
            return $value;
        }
        
        return call_user_func_array($field['callback']['format_value'], array($value, $post_id, $field));
        
    }
    
    
    /**
     * validate_value
     *
     * @param $valid
     * @param $value
     * @param $field
     * @param $input
     *
     * @return mixed
     */
    function validate_value($valid, $value, $field, $input){
        
        if(!$this->validate_hook($field, 'validate_value')){
            return $valid;
        }
        
        return call_user_func_array($field['callback']['validate_value'], array($valid, $value, $field, $input));
        
    }
    
    
    /**
     * delete_value
     *
     * @param $post_id
     * @param $field_name
     * @param $field
     */
    function delete_value($post_id, $field_name, $field){
        
        if(!$this->validate_hook($field, 'delete_value')){
            return;
        }
    
        call_user_func_array($field['callback']['delete_value'], array($post_id, $field_name, $field));
        
    }
    
    
    /**
     * validate_hook
     *
     * @param $field
     * @param $hook_name
     *
     * @return bool
     */
    function validate_hook($field, $hook_name){
        
        // isset
        if(!isset($field['callback'][$hook_name]) || !is_callable($field['callback'][$hook_name])){
            return false;
        }
        
        return true;
    
    }
    
}

new acfe_pro_hooks();

endif;