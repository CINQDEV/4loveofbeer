(function($) {
	$('document').ready(function() {
		let container = $('#oxygen-sidebar-control-panel-basic-styles', parent.document);

		let field = `<div 
						class="oxygen-control-row" 
						id="swk-edit-text" 
						ng-show='["ct_headline", "ct_li", "ct_link_button", "ct_link_text", "oxy_rich_text", "ct_span", "ct_text_block"].indexOf(iframeScope.component.active.name) > -1'>

						<div class="oxygen-control-wrapper">
							<label class="oxygen-control-label">Text Content</label>
							<div class="oxygen-control">
								<div class="oxygen-input" style="height: auto;">
									<textarea 
										rows="5"
										style="width: 100% !important; font-family: inherit; background: var(--oxy__bg_3); color: var(--oxy__text); font-size: 14px; border: 0; padding: 5px;"
										spellcheck="false" 
										ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['ct_content']" 
										ng-model-options="{ debounce: 10 }" 
										ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'ct_content', false, true);">
									</textarea>
								</div>
							</div>
						</div>
					
					</div>	` ;

		$(document, parent.document).injector().invoke(function($compile) {
            container.prepend($compile(field)(iframeScope.parentScope));
        });

        $('#swk-edit-text', parent.document).on('keypress', function(e) {
        	if(e.which === 13) {
        		e.preventDefault();
        		parent.document.execCommand('insertText', false, '<br>');
        	}
        })

        $(parent.document).on('blur', '#swk-edit-text textarea', function(e) { 
		    iframeScope.parentScope.actionTabs["contentEditing"] = true;
		    iframeScope.parentScope.disableContentEdit()
		})
	})
})(angular.element);