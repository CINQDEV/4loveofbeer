<div id="wpwrap">
	<div class="wrap shortcuts-manager">
		<h1 class="wp-heading-inline">Import/Export Swiss Knife Settings</h1>
		<div style="margin-top:20px">
			<div class="swk-alert alert-warning notice notice-error inline" style="display: none; padding:10px"></div>
			<div class="swk-alert alert-success notice notice-success inline" style="display: none; padding:10px"></div>
			
			<div class="export swk_admin_card">
				<div class="swk_admin_header">
					<h2>Export</h2>
					<p>Settings from Features and Shortcuts will be included in this export.</p>
				</div>
				<div class="swk_admin_body">
					
					<div class="sc-save">
						<a href="#" class="swk-export button-primary">Export<span class="spinner" style="display: none;"></span></a>
					</div>
				</div>
			</div>

			<div class="import swk_admin_card">
				<div class="swk_admin_header">
				<h2>Import</h2>
				<p>To start the import process please import JSON file.</p>
				</div>
				<div class="swk_admin_body">
					<form id="upload_json" method="post" enctype="multipart/form-data">
						<div class="import-button">
							<input style="display:block; margin-bottom:20px" type="file" class="swk-json" required id="swk-json" name="swk_json" value="" />
							<a href="#" class="swk-import button-primary">Import <span class="spinner" style="display: none;"></span></a>
						</div>
					</form>
				</div>
			</div>
		</div><!-- End of Sticky wrap -->
	</div> <!-- End of wrap -->
</div> <!-- End of wpwrap -->



