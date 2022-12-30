<?php
global $wpdb;
$message = "";
$swiss_knife_fonts = $wpdb->prefix . "swiss_knife_fonts"; 
$swiss_knife_font_faces = $wpdb->prefix . "swiss_knife_font_faces";
if(isset($_POST['swiss_knife_font_name'])){
	$font_name = trim($_POST['swiss_knife_font_name']);
	$font_id = $wpdb->get_var( "SELECT id FROM $swiss_knife_fonts WHERE font_name = '$font_name'" );
	if($font_id > 0){
		$message = '<div class="alert alert-danger" role="alert">You cannot add same font again!</div>';
	} else {
		$wpdb->insert($swiss_knife_fonts, array(
	        'font_name' => $font_name,
	    ));
	}
}

if(isset($_POST['parent_font'])){
	//echo "<pre>"; print_r($_POST); "</pre>"; exit;
	$insert_query = array();
	$i = 0;
	$style = "";
	foreach($_POST['parent_font'] as $parent_font){
		$insert_array[] = "('".$_POST['parent_font'][$i]."', '".$_POST['font_weight'][$i]."', '".$_POST['font_file'][$i]."', '".$_POST['font_file_2'][$i]."', '".$_POST['font_display'][$i]."', '".$_POST['font_style'][$i]."', '".$_POST['font_preload'][$i]."', '".$_POST['is_variable'][$i]."', '".$_POST['variable_from'][$i]."', '".$_POST['variable_to'][$i]."')";
		$i++;
	}
	//echo "<pre>"; print_r($insert_query); "</pre>"; exit;
	$insert_query = "INSERT INTO ".$swiss_knife_font_faces." (font_id, font_weight, font_file, font_file_2, font_display, font_style, font_preload, is_variable, variable_from, variable_to) VALUES ";
    $insert_query .= implode(', ', $insert_array);
    $wpdb->query("TRUNCATE TABLE $swiss_knife_font_faces");
    $wpdb->query($insert_query);

    /* upload swiss_knife_fonts.css */
    $SWK_admin = new SWK_admin();
    $SWK_admin->prepare_font_css();
    /* End of - upload swiss_knife_fonts.css */
}
?>

<!-- Page start -->

<div class="wrap">

	<h1 class="wp-heading-inline">Fonts Manager </h1><a href="#" class="add-new-font page-title-action">+ Add Font Family</a>
	
	<!-- Register Font Family Form -->

	<?php if(!empty($message)){ echo $message; } ?>
	<div class="add-font" style="display: none;">
		<form method="post" action="">
			<label>Font family name</label>
			<input type="text" name="swiss_knife_font_name" required />
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Font Family">
		</form>
	</div>
	<!-- End of - Register Font Family Form -->

	<!-- Registered Fonts - Cards -->

	<div class="font-faces">
		<div class="new-font-box-copy" style="display: none;">
			<div class="font-field swk-field dp--select font-weight">
				<label>Font Weight</label>
				<select name="font_weight[]">
					<option value="">Choose</option>
					<option value="100">100</option>
					<option value="200">200</option>
					<option value="300">300</option>
					<option value="400">400</option>
					<option value="500">500</option>
					<option value="600">600</option>
					<option value="700">700</option>
					<option value="800">800</option>
					<option value="900">900</option>
				</select>
			</div>
			<div class="font-field font-weight__from-to">
				<input type="number" name="variable_from[]" placeholder="Font Weight From">
				<span> </span>
				<input type="number" name="variable_to[]" placeholder="Font Weight To">
			</div>
			<div class="font-field swk-field file-upload">
				<label>Upload Woff file</label>
				<input type="text" name="font_file[]" class="font-file" />
				<a href="#" class="font-file-upload action-svg-icon swk-file-upload">
					<svg>
				    	<use xlink:href="#upload-icon"/>
				  	</svg>
				</a>
			</div>
			<div class="font-field swk-field file-upload">
				<label>Upload Woff2 file</label>
				<input type="text" name="font_file_2[]" class="font-file" />
				<a href="#" class="font-file-upload action-svg-icon swk-file-upload">
					<svg>
				    	<use xlink:href="#upload-icon"/>
				  	</svg>
				</a>
			</div>
			<div class="font-field swk-field dp--select">
				<label>Font Display</label>
				<select name="font_display[]">
					<option value="">Choose</option>
					<option value="auto">Auto</option>
					<option value="block">Block</option>
					<option value="swap">Swap</option>
					<option value="fallback">Fallback</option>
					<option value="optional">Optional</option>
				</select>
			</div>
			<div class="font-field swk-field dp--checkbox">
				<input type="checkbox" class="font-style" /> Font is <i>italic</i> (Regular by default)
				<input type="hidden" name="font_style[]" value="normal">
			</div>
			<div class="font-field swk-field dp--checkbox">
				<input type="checkbox" class="font-preload" /> Preload
				<input type="hidden" name="font_preload[]" value="no">
				<input type="hidden" name="is_variable[]" value="0">
			</div>
		</div>



		<?php
			$html = '';
			$all_fonts = $wpdb->get_results( "SELECT * FROM $swiss_knife_fonts" );
			$font_weights = array(
				'' => 'Choose',
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			);

			$font_displays = array(
				'' => 'Choose',
				'auto' => 'Auto',
				'block' => 'Block',
				'swap' => 'Swap',
				'fallback' => 'Fallback',
				'optional' => 'Optional',
			);

			if(!empty($all_fonts)){
				$html .= '<form method="post" id="font-face-form" action="">';
				$selected = "";
				$checked = "";
				foreach($all_fonts as $font){
					$has_variable = false;
					$font_id = $font->id;
					$html .= '<div class="font swk_admin_card">';
						$html .= '<div class="font-head">
							<h3>'.$font->font_name.'</h3>
							<a href="#" data-id="'.$font_id.'" class="delete-font-family font-button btn__delete">Delete<span class="spinner"></span></a>
						</div>';
						$all_font_faces = $wpdb->get_results( "SELECT * FROM $swiss_knife_font_faces WHERE font_id = '$font_id' ORDER BY font_weight ASC" );
						if(!empty($all_font_faces)){
							$html .= '<div class="font-face-boxes">';
							foreach($all_font_faces as $font_face){
								$html .= '<div class="new-font-box edit-style">';
									$html .= '<div class="font-box-head">';
									if($font_face->is_variable < 1){
										if(!empty($font_face->font_weight)){
											$html .= '<span class="font-box-head-field">'.ucfirst($font_face->font_weight).'</span>';
										}
									} else {
										if(!empty($font_face->variable_from) || !empty($font_face->variable_to)){
											$html .= '<span class="font-box-head-field">'.((!empty($font_face->variable_from)) ? $font_face->variable_from : '').' '.((!empty($font_face->variable_to)) ? '- '.$font_face->variable_to : '').'</span>';
										}
									}
									if(!empty($font_face->font_style)){
										$html .= '<span class="font-box-head-field">'.ucfirst($font_face->font_style).'</span>';
									}
									if(!empty($font_face->font_display)){
										$html .= '<span class="font-box-head-field">'.ucfirst($font_face->font_display).'</span>';
									}
									$html .= '<a href="#" class="btn__edit edit-font-face">Edit style</a>
										<a href="#" class="delete-font-face action-svg-icon" data-id="'.$font_face->id.'">
											<svg>
				    							<use xlink:href="#delete-icon"/>
				  							</svg>
										</a>
									</div>';
									$html .= '<div class="'.(($font_face->is_variable < 1) ? 'font-field' : '').' swk-field dp--select" style="display:none;">
										<label>Font Weight</label>
										<select name="font_weight[]">';
										foreach($font_weights as $key => $val){
											if($key == $font_face->font_weight){
												$selected = "selected";
											} else {
												$selected = "";
											}
											$html .= '<option '.$selected.' value="'.$key.'">'.$val.'</option>';
										}
									$html .= '</select>
									</div>';
									$has_variable = ($font_face->is_variable > 0) ? true : false;
									$html .= '<div class="'.(($font_face->is_variable > 0) ? 'font-field' : '').' font-weight__from-to" style="display:none;">
										<input type="number" value="'.$font_face->variable_from.'" name="variable_from[]" placeholder="Font Weight From">
										<span> </span>
										<input type="number" value="'.$font_face->variable_to.'" name="variable_to[]" placeholder="Font Weight To">
									</div>';
									$html .= '<div class="font-field swk-field file-upload" style="display:none;">
										<label>Upload Woff file</label>
										<input type="text" name="font_file[]" class="font-file" value="'.$font_face->font_file.'" />
										<a href="#" class="font-file-upload action-svg-icon swk-file-upload">
											<svg>
				    							<use xlink:href="#upload-icon"/>
				  							</svg>
										</a>
									</div>';
									$html .= '<div class="font-field swk-field file-upload" style="display:none;">
										<label>Upload Woff2 file</label>
										<input type="text" name="font_file_2[]" class="font-file" value="'.$font_face->font_file_2.'" />
										<a href="#" class="font-file-upload action-svg-icon swk-file-upload">
											<svg>
				    							<use xlink:href="#upload-icon"/>
				  							</svg>
										</a>
									</div>';
									$html .= '<div class="font-field swk-field dp--select" style="display:none;">
										<label>Font Display</label>
										<select name="font_display[]">';
										foreach($font_displays as $key => $val){
											if($key == $font_face->font_display){
												$selected = "selected";
											} else {
												$selected = "";
											}
											$html .= '<option '.$selected.' value="'.$key.'">'.$val.'</option>';
										}
									$html .= '</select>
									</div>';

									if($font_face->font_style == "italic"){
										$checked = "checked";
									} else {
										$checked = "";
									}
									$html .= '<div class="font-field swk-field dp--checkbox" style="display:none;">
										<input '.$checked.' type="checkbox" class="font-style" /> Font is <i>italic</i> (Regular by default)
										<input type="hidden" name="font_style[]" value="'.$font_face->font_style.'">
									</div>';
									if($font_face->font_preload == "yes"){
										$checked = "checked";
									} else {
										$checked = "";
									}
									$html .= '<div class="font-field swk-field dp--checkbox" style="display:none;">
										<input '.$checked.' type="checkbox" class="font-preload" /> Preload
										<input type="hidden" name="font_preload[]" value="'.$font_face->font_preload.'">
										<input type="hidden" name="is_variable[]" value="'.(($font_face->is_variable < 1) ? '0' : '1').'">
									</div>';
									$html .= '<input type="hidden" name="parent_font[]"" value="'.$font_id.'" />';
								$html .= '</div>';
							}
							$html .= '</div>';
						} else {
							$html .= '<div class="font-face-boxes"></div>';
						}
						$html .= '<div class="font-face-bottom">';
						if(!$has_variable){
							$html .= '<a href="#" data-id="'.$font_id.'" class="font-button add-font-face btn__add">+ Add Font Face</a>';
						}
						if(!$has_variable && empty($all_font_faces)){
							$html .= '<a href="#" data-id="'.$font_id.'" class="font-button add-variable-font btn__add">+ Add Variable Font</a>';
						}
					$html .= '</div></div>';
				}
				$html .= '<div class="clear"></div>
					<input type="submit" name="submit_fonts" class="button button-primary save-fonts" value="Save">
				</form>';
			}
			echo $html;
		?>
	</div>
</div>

<br>
<br>


<!-- Register SVG images -->
<svg display="none">
  <symbol width="24" height="24" viewBox="0 0 24 24" id="upload-icon">
    <path d="M0 0h24v24H0z" fill="none"/><path d="M5 4v2h14V4H5zm0 10h4v6h6v-6h4l-7-7-7 7z"/>
  </symbol>
  <symbol width="24" height="24" viewBox="0 0 24 24" id="delete-icon">
  	<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
  </symbol>
</svg>
<!-- End of - Register SVG images -->


<script>
jQuery(document).ready(function($){

	$('.edit-font-face').click( function() {
		$(this).parent().toggleClass('active');
	});		
});
</script>	