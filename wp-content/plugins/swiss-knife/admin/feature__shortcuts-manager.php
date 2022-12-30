<?php
	$SWK_Shortcuts = new SWK_Shortcuts();
	$shortcuts = $SWK_Shortcuts->shortcuts_settings();
	$all_shortcuts = $SWK_Shortcuts->key_to_name();
?>

<div id="wpwrap">
	<div class="wrap shortcuts-manager">
		
	<h1 class="wp-heading-inline">Shortcuts Manager</h1>
	<!-- Add and Save buttons -->

	<div class="sticky-wrap">
		
		<div id="shortcuts-form" class="swk_admin_card">
			<div class="swk_admin_body">
				<div id="shortcuts_table">
					<div class="sc-head">
						<div class="sc-box sc-name">Shortcut Name</div>
						<div class="sc-box">Ctrl / <span class="mac_key">⌘</span></div>
						<div class="sc-box">Alt / <span class="mac_key">⌥</span> </div>
						<div class="sc-box">Shift</div>
						<div class="sc-box sc-input">Key</div>
					</div>
					<?php 
						$html = '';
						$checked = '';
						foreach($all_shortcuts as $key => $val){
							$html .= '<div class="sc-row" data-shortcut="'.$key.'">
								<div class="sc-box sc-name">'.$val.'</div>
								<div class="sc-box">
									<label class="of-input">
										<input type="checkbox"';
										if(!empty($shortcuts)){ 
											if(isset($shortcuts[$key]) && (strpos($shortcuts[$key]['combination'], 'ctrl') !== false || strpos($shortcuts[$key]['combination'], 'command') !== false)){
												$html .= ' checked ';
											}
										}
									$html .= 'class="ctrl" name="ctrl">
									</label>
								</div>
								<div class="sc-box">
									<label class="of-input">
										<input type="checkbox" class="alt" name="alt"';
										if(!empty($shortcuts)){ 
											if(isset($shortcuts[$key]) && (strpos($shortcuts[$key]['combination'], 'alt') !== false || strpos($shortcuts[$key]['combination'], 'option') !== false)){
												$html .= ' checked ';
											}
										}
										$html .= '>
									</label>
								</div>
								<div class="sc-box">
									<label class="of-input">
										<input type="checkbox" class="shift" name="shift"';
										if(!empty($shortcuts)){ 
											if(isset($shortcuts[$key]) && (strpos($shortcuts[$key]['combination'], 'shift') !== false || strpos($shortcuts[$key]['combination'], 'shift') !== false)){
												$html .= ' checked ';
											}
										}
										$html .= '>
									</label>
								</div>
								<div class="sc-box sc-input">
									<input type="text" class="sc-key" name="key" ';
									if(!empty($shortcuts)){ 
										if(isset($shortcuts[$key])){
											$html .= 'value="'.$shortcuts[$key]['key'] . '" disabled ';
										}
									}
									$html .=' autocomplete="off" /> ';
									if(!empty($shortcuts)){ 
										if(isset($shortcuts[$key])){
											$html .= '<span class="sc-clear-key"><svg><use xlink:href="#delete"/></svg></span>';
										} else {
											$html .= '<span class="sc-clear-key" style="display: none;"><svg><use xlink:href="#delete"/></svg></span>';
										}
									} else {
										$html .= '<span class="sc-clear-key" style="display: none;"><svg><use xlink:href="#delete"/></svg></span>';
									}
								$html .= '</div>
							</div>';
						}
						echo $html;
					?>
				</div>

			</div>
		</div>


		<div class="sc-save">
			<a href="#" class="of-save sc-save-shortcuts button-primary">Save Shortcuts<span class="spinner"></span></a>
		</div>

	</div><!-- End of Sticky wrap -->




</div> <!-- End of wrap -->
</div> <!-- End of wpwrap -->


<!-- Register SVG images -->
<svg display="none">
	<symbol width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" id="delete">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M4.46613 3.40625L7.99989 6.94001L11.5337 3.40625C11.6312 3.30876 11.7893 3.30876 11.8868 3.40625L12.5937 4.11313C12.6912 4.21064 12.6912 4.36877 12.5937 4.46625L9.05989 8.00001L12.5937 11.5338C12.6912 11.6313 12.6912 11.7894 12.5937 11.8869L11.8868 12.5938C11.7893 12.6913 11.6311 12.6913 11.5337 12.5938L7.99989 9.06001L4.46613 12.5938C4.36863 12.6913 4.2105 12.6913 4.11301 12.5938L3.40613 11.8869C3.30863 11.7894 3.30863 11.6313 3.40613 11.5338L6.93989 8.00001L3.40613 4.46625C3.30863 4.36876 3.30863 4.21062 3.40613 4.11313L4.11301 3.40625C4.21051 3.30876 4.36864 3.30876 4.46613 3.40625Z" fill="black"/>
	</symbol>
</svg>
<!-- End of - Register SVG images -->



