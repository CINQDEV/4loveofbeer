<form id="of-form">
	
	<div class="swk_admin_card">
	
		<div class="swk_admin_body">
		
			<fieldset class="choose-theme-wrap radio-boxes dp-tabular">
				<legend class="screen-reader-text"><span>input type="radio"</span></legend>

				<h3>Select Theme</h3>

				<ul>
					

					<li>
						<?php
							$swiss_knife_theme_type = get_option('swiss_knife_theme_type');
							$checked = "";
							if(empty($swiss_knife_theme_type) || $swiss_knife_theme_type == "dark"){
								$checked = "checked";
							}
						?>
						

						<label for="theme">
							<input type="radio" <?php echo $checked; ?> id="dark" name="theme" value="dark">
							<span>Dark</span>
						</label>
								
					</li>


					<li>
						<?php
							$checked = "";
							if($swiss_knife_theme_type == "light"){
								$checked = "checked";
							}
						?>

						<label for="theme">
							<input type="radio" <?php echo $checked; ?> id="light" name="theme" value="light">
							<span>Light</span>
						</label>						
					</li>


					<li>
						<?php
							$checked = "";
							if($swiss_knife_theme_type == "default"){
								$checked = "checked";
							}
						?>

						<label for="theme">
							<input type="radio" <?php echo $checked; ?> id="default" name="theme" value="default">
							<span>Default</span>
						</label>						
					</li>
					
					
					<li>
						<?php
							$checked = "";
							if($swiss_knife_theme_type == "no_theme"){
								$checked = "checked";
							}
						?>

						<label for="theme">
							<input type="radio" <?php echo $checked; ?> id="no_theme" name="theme" value="no_theme">
							<span>Without Theme</span>
						</label>						
					</li>

				</ul>


			</fieldset><!-- End of choose-theme-wrap -->	



		</div><!-- End of swk_admin_body -->

		<div class="swk_admin_body dp--checkbox">

			<h3>Activate Features</h3>

			<ul class="features--list">
				<?php
					$SWK_features_settings = new SWK_features_settings();
					$swiss_knife_options = $SWK_features_settings->get_swiss_knife_options();
					$checked = "";
					$selected = "";
					$swiss_knife_cm_themes = $SWK_features_settings->swiss_knife_cm_themes_array();
					$html = '';
					foreach($swiss_knife_options as $main_key => $main_val){
						$option_value = get_option($main_key);
						if($option_value == "yes"){
							$checked = "checked";
						} else {
							$checked = "";
						}

						$html .= '<div class="main-label" data-label="'.strtolower($main_key).'"><label class="of-input"><input '.$checked.' type="checkbox" class="of-checkboxes main" name="'.$main_key.'" value="yes" /> '.$main_val[0].'</label><div class="child-checkboxes child-'.strtolower($main_key).'">';
						//echo "<pre>"; print_r($main_val); "</pre>";
						foreach($main_val[1] as $key => $val){
							$option_value = get_option($key);
							if($option_value == "yes"){
								$checked = "checked";
							} else {
								$checked = "";
							}
							$html .= '<label class="of-input"><input '.$checked.' type="checkbox" class="of-checkboxes" name="'.$key.'" value="yes" /> '.$val[0];
								if($key == "swiss_knife_structure_custom_width"){
									$html .= ' <input type="number" class="of-checkboxes" placeholder="300" name="swiss_knife_structure_width" value="'.get_option('swiss_knife_structure_width').'" /> px';
								}

								if($key == "swiss_knife_codemirror"){
									$html .= '<select name="swiss_knife_cm_theme" class="of-checkboxes">';
										$swiss_knife_cm_theme = get_option('swiss_knife_cm_theme');
										foreach($swiss_knife_cm_themes as $key1 => $val1){
											if($swiss_knife_cm_theme == $key1){
												$selected = 'selected';
											} else {
												$selected = '';
											}
											$html .= '<option '.$selected.' value="'.$key1.'">'.$val1.'</option>';
										}
									$html .= '</select>';
								}

								if($key == "swiss_fontsize"){
									$html .= ' <input type="number" class="of-checkboxes" placeholder="14" name="swiss_font_value" value="'.get_option('swiss_font_value').'" /> px';
								}
							$html .= '</label>';
						}
						$html .= '</div></div>';
					}
					echo $html;
				?>
			</ul>

					

		</div><!-- End of swk_admin_body -->


	</div><!-- End of swk_admin_card -->


	<a href="#" class="of-save button-primary">

		Save Changes
		<span class="spinner" ></span>
	</a>

</form>
