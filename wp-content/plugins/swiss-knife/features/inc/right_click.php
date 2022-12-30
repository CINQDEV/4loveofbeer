<div id="right-click" style="display:none;">
   <?php
        $swiss_knife_options = $this->get_swiss_knife_options();
        $right_click_features = $this->right_click_options();
        $right_click_html = '';
		foreach($right_click_features as $rc_feature){
            if(get_option($rc_feature) == "yes"){
                if(isset($swiss_knife_options['swk_right_click'][1][$rc_feature])){
                    $right_click_html .= '<div class="right-click--element '.$rc_feature.'" data-key="'.$rc_feature.'">'.$swiss_knife_options['swk_right_click'][1][$rc_feature][0].'</div>';
                }
            }
        }
        echo $right_click_html;
    ?>
</div>

<script>
    if (typeof swkUseSystemClipboard === 'undefined') {
        var swkUseSystemClipboard = "<?php echo get_option('swiss_system_clipboard');?>" === "yes";
    }
</script>

<style type="text/css">
    #right-click {
        z-index: 9999999999;
        position: absolute;
        background-color: var(--oxy__bg-2);
        color: var(--oxy__text);
        flex-direction: column;
        overflow: hidden;
        border-radius: 5px;
        width: 200px;
        box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.4);
    }

    #right-click div {
        border-bottom: 1px solid var(--oxy__border);
        padding: 5px 10px;
        cursor: pointer;
        font-family: Geomanist, system-ui, sans-serif;
    }

    #right-click div:hover {
        background-color: var(--oxy__action);
        color: var(--oxy__action_text);
    }

    #right-click div:last-child {
        border-bottom: 0;
    }


    #right-click .swiss_delete {
        color: #ff7d7d;
    }

    #right-click .swiss_delete:hover {
        background-color: red;
        color: var(--oxy__action_text);
    }

    @keyframes swk--copy-animation {
      from  {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    .swk--copy-animation {
      -webkit-animation-name: swk--copy-animation !important;
      animation-name: swk--copy-animation !important;
      -webkit-animation-timing-function: ease-out !important;
      animation-timing-function: ease-out !important;
      -webkit-animation-duration: .2s !important;
      animation-duration: .2s !important;
    }
</style>
