(function($) {
    function put_lorem_ipsum_text(text_to_put){
        $("#swk-edit-text textarea", parent.document).val(text_to_put).trigger("keypress").trigger("change");
    }
	$('document').ready(function() {
		let container = $('#oxygen-sidebar-control-panel-basic-styles', parent.document);
        if($("#swk-edit-text", parent.document).length < 1){
            let field = `<div 
                    class="oxygen-control-row" 
                    id="swk-edit-text" 
                    style="display:none !important";
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
        }
		let field = `<div 
						class="oxygen-control-row" 
						id="swk-lorem-ipsum" 
						ng-show='["ct_li", "ct_link_text", "oxy_rich_text", "ct_span", "ct_text_block"].indexOf(iframeScope.component.active.name) > -1'>

						<div class="oxygen-control-wrapper">
							<label class="oxygen-control-label">Lorem Ipsum Generator</label>
							<div class="oxygen-control">
								<div class="oxygen-input" style="height: auto;">
                                <select 
                                    id="lorem-ipsum"
                                    style="width: 100% !important; font-family: inherit; background: var(--oxy__bg_3); color: var(--oxy__text); font-size: 14px; border: 0; padding: 5px;"
                                    <option value="">Insert Paragraphs</option>
                                    <option value="">Insert Paragraphs</option>
                                    <option value="p1">Insert 1 Paragraph</option>
                                    <option value="p2">Insert 2 Paragraphs</option>
                                    <option value="p3">Insert 3 Paragraphs</option>
                                    <option value="p4">Insert 4 Paragraphs</option>
                                    <option value="p5">Insert 5 Paragraphs</option>
                                </select>
								</div>
							</div>
						</div>
					
					</div>	` ;

		$(document, parent.document).injector().invoke(function($compile) {
            container.prepend($compile(field)(iframeScope.parentScope));
        });

        $("#lorem-ipsum", parent.document).on("change", function(){
            var current_option = $(this).val();
            var text_to_put = '';
            switch(current_option) {
                case 'p1':
                  text_to_put = 'Turpis non pharetra curabitur, proin sed integer maecenas id nullam aliquet suscipit arcu morbi, natoque venenatis proin egestas aptent interdum. In Faucibus laoreet. Vehicula Viverra dictumst sapien, egestas ridiculus. Senectus mauris etiam. Ligula nec vel cubilia neque cubilia dui amet Augue vitae cubilia. Dis senectus suscipit malesuada consequat iaculis nisi donec dictum donec, primis suscipit vel sagittis luctus eget ipsum pede accumsan ut vivamus dapibus venenatis. Velit odio vestibulum eros malesuada taciti cursus hymenaeos sociis lobortis condimentum condimentum eleifend, volutpat egestas aenean Tristique taciti porttitor eget, elit scelerisque inceptos nonummy fusce, ultricies Magnis mi luctus consequat risus nec vel nulla, mollis phasellus. Odio senectus venenatis curae; fringilla curabitur quis lacus. Fusce eu potenti dapibus tellus venenatis cum hendrerit convallis ut vestibulum habitasse ante facilisis dolor parturient donec arcu.';
                  put_lorem_ipsum_text(text_to_put);
                  break;
                case 'p2':
                    text_to_put = 'Turpis non pharetra curabitur, proin sed integer maecenas id nullam aliquet suscipit arcu morbi, natoque venenatis proin egestas aptent interdum. In Faucibus laoreet. Vehicula Viverra dictumst sapien, egestas ridiculus. Senectus mauris etiam. Ligula nec vel cubilia neque cubilia dui amet Augue vitae cubilia. Dis senectus suscipit malesuada consequat iaculis nisi donec dictum donec, primis suscipit vel sagittis luctus eget ipsum pede accumsan ut vivamus dapibus venenatis. Velit odio vestibulum eros malesuada taciti cursus hymenaeos sociis lobortis condimentum condimentum eleifend, volutpat egestas aenean Tristique taciti porttitor eget, elit scelerisque inceptos nonummy fusce, ultricies Magnis mi luctus consequat risus nec vel nulla, mollis phasellus. Odio senectus venenatis curae; fringilla curabitur quis lacus. Fusce eu potenti dapibus tellus venenatis cum hendrerit convallis ut vestibulum habitasse ante facilisis dolor parturient donec arcu.\
                    <br'+'>'+'<br'+'>'+'\
                    Placerat lacinia maecenas, laoreet montes nonummy aptent urna tempor hendrerit vel Accumsan feugiat blandit vitae parturient per euismod id donec nibh urna posuere suspendisse montes gravida porttitor netus habitasse elit mauris eu pulvinar lacinia vel magna praesent suscipit. Taciti orci dictumst hendrerit. Justo semper quis tristique felis. Laoreet vivamus porta litora est proin nunc Urna netus lacus gravida nisl Nunc Venenatis torquent. Urna platea netus purus viverra. Diam erat hymenaeos nec rutrum morbi aliquam torquent. Mauris scelerisque massa Sapien magna pede sociis consequat ac semper imperdiet fames pede interdum bibendum. Pretium porta rutrum fusce ad potenti et mi fames ornare est eu interdum turpis tristique volutpat orci. Auctor ad id porttitor curabitur sit magna torquent pulvinar. Bibendum elementum quam facilisis.';
                    put_lorem_ipsum_text(text_to_put);
                    break;
                case 'p3':
                    text_to_put = 'Turpis non pharetra curabitur, proin sed integer maecenas id nullam aliquet suscipit arcu morbi, natoque venenatis proin egestas aptent interdum. In Faucibus laoreet. Vehicula Viverra dictumst sapien, egestas ridiculus. Senectus mauris etiam. Ligula nec vel cubilia neque cubilia dui amet Augue vitae cubilia. Dis senectus suscipit malesuada consequat iaculis nisi donec dictum donec, primis suscipit vel sagittis luctus eget ipsum pede accumsan ut vivamus dapibus venenatis. Velit odio vestibulum eros malesuada taciti cursus hymenaeos sociis lobortis condimentum condimentum eleifend, volutpat egestas aenean Tristique taciti porttitor eget, elit scelerisque inceptos nonummy fusce, ultricies Magnis mi luctus consequat risus nec vel nulla, mollis phasellus. Odio senectus venenatis curae; fringilla curabitur quis lacus. Fusce eu potenti dapibus tellus venenatis cum hendrerit convallis ut vestibulum habitasse ante facilisis dolor parturient donec arcu.\
                    <br'+'>'+'<br'+'>'+'\
                    Placerat lacinia maecenas, laoreet montes nonummy aptent urna tempor hendrerit vel Accumsan feugiat blandit vitae parturient per euismod id donec nibh urna posuere suspendisse montes gravida porttitor netus habitasse elit mauris eu pulvinar lacinia vel magna praesent suscipit. Taciti orci dictumst hendrerit. Justo semper quis tristique felis. Laoreet vivamus porta litora est proin nunc Urna netus lacus gravida nisl Nunc Venenatis torquent. Urna platea netus purus viverra. Diam erat hymenaeos nec rutrum morbi aliquam torquent. Mauris scelerisque massa Sapien magna pede sociis consequat ac semper imperdiet fames pede interdum bibendum. Pretium porta rutrum fusce ad potenti et mi fames ornare est eu interdum turpis tristique volutpat orci. Auctor ad id porttitor curabitur sit magna torquent pulvinar. Bibendum elementum quam facilisis. \
                    <br'+'>'+'<br'+'>'+'\
                    Diam placerat egestas nullam. Velit. Varius elementum convallis, ipsum, sollicitudin tortor phasellus facilisi proin scelerisque non. Vestibulum, natoque platea tincidunt nonummy montes dolor habitasse augue nibh metus nec cras ipsum sociosqu curae;. Diam sociis ornare cras integer mattis vulputate laoreet at malesuada, laoreet posuere magnis aliquam fusce magna vivamus aenean inceptos ad. Sagittis. Conubia aptent magnis nam. Sociosqu nec amet magnis ornare pulvinar. Faucibus aenean vehicula habitasse.';
                    put_lorem_ipsum_text(text_to_put);
                    break;
                case 'p4':
                    text_to_put = 'Turpis non pharetra curabitur, proin sed integer maecenas id nullam aliquet suscipit arcu morbi, natoque venenatis proin egestas aptent interdum. In Faucibus laoreet. Vehicula Viverra dictumst sapien, egestas ridiculus. Senectus mauris etiam. Ligula nec vel cubilia neque cubilia dui amet Augue vitae cubilia. Dis senectus suscipit malesuada consequat iaculis nisi donec dictum donec, primis suscipit vel sagittis luctus eget ipsum pede accumsan ut vivamus dapibus venenatis. Velit odio vestibulum eros malesuada taciti cursus hymenaeos sociis lobortis condimentum condimentum eleifend, volutpat egestas aenean Tristique taciti porttitor eget, elit scelerisque inceptos nonummy fusce, ultricies Magnis mi luctus consequat risus nec vel nulla, mollis phasellus. Odio senectus venenatis curae; fringilla curabitur quis lacus. Fusce eu potenti dapibus tellus venenatis cum hendrerit convallis ut vestibulum habitasse ante facilisis dolor parturient donec arcu.\
                    <br'+'>'+'<br'+'>'+'\
                    Placerat lacinia maecenas, laoreet montes nonummy aptent urna tempor hendrerit vel Accumsan feugiat blandit vitae parturient per euismod id donec nibh urna posuere suspendisse montes gravida porttitor netus habitasse elit mauris eu pulvinar lacinia vel magna praesent suscipit. Taciti orci dictumst hendrerit. Justo semper quis tristique felis. Laoreet vivamus porta litora est proin nunc Urna netus lacus gravida nisl Nunc Venenatis torquent. Urna platea netus purus viverra. Diam erat hymenaeos nec rutrum morbi aliquam torquent. Mauris scelerisque massa Sapien magna pede sociis consequat ac semper imperdiet fames pede interdum bibendum. Pretium porta rutrum fusce ad potenti et mi fames ornare est eu interdum turpis tristique volutpat orci. Auctor ad id porttitor curabitur sit magna torquent pulvinar. Bibendum elementum quam facilisis.\
                    <br'+'>'+'<br'+'>'+'\
                    Diam placerat egestas nullam. Velit. Varius elementum convallis, ipsum, sollicitudin tortor phasellus facilisi proin scelerisque non. Vestibulum, natoque platea tincidunt nonummy montes dolor habitasse augue nibh metus nec cras ipsum sociosqu curae;. Diam sociis ornare cras integer mattis vulputate laoreet at malesuada, laoreet posuere magnis aliquam fusce magna vivamus aenean inceptos ad. Sagittis. Conubia aptent magnis nam. Sociosqu nec amet magnis ornare pulvinar. Faucibus aenean vehicula habitasse.\
                    <br'+'>'+'<br'+'>'+'\
                    Augue ultricies tempus sodales, est ante suscipit venenatis lacinia taciti magna nam fames montes ornare rutrum accumsan lectus Ridiculus hendrerit pede. Primis torquent sociis fusce litora Posuere. Elementum quisque. Netus eros dictumst morbi vitae litora porta porttitor fusce elementum non dictumst semper proin habitant faucibus sociis aptent conubia nunc. Tristique tortor montes euismod natoque Purus nonummy quisque luctus purus porttitor pulvinar senectus praesent quis imperdiet proin arcu dui natoque bibendum elementum pede integer donec.';
                    put_lorem_ipsum_text(text_to_put);
                    break;
                case 'p5':
                    text_to_put = 'Turpis non pharetra curabitur, proin sed integer maecenas id nullam aliquet suscipit arcu morbi, natoque venenatis proin egestas aptent interdum. In Faucibus laoreet. Vehicula Viverra dictumst sapien, egestas ridiculus. Senectus mauris etiam. Ligula nec vel cubilia neque cubilia dui amet Augue vitae cubilia. Dis senectus suscipit malesuada consequat iaculis nisi donec dictum donec, primis suscipit vel sagittis luctus eget ipsum pede accumsan ut vivamus dapibus venenatis. Velit odio vestibulum eros malesuada taciti cursus hymenaeos sociis lobortis condimentum condimentum eleifend, volutpat egestas aenean Tristique taciti porttitor eget, elit scelerisque inceptos nonummy fusce, ultricies Magnis mi luctus consequat risus nec vel nulla, mollis phasellus. Odio senectus venenatis curae; fringilla curabitur quis lacus. Fusce eu potenti dapibus tellus venenatis cum hendrerit convallis ut vestibulum habitasse ante facilisis dolor parturient donec arcu.\
                    <br'+'>'+'<br'+'>'+'\
                    Placerat lacinia maecenas, laoreet montes nonummy aptent urna tempor hendrerit vel Accumsan feugiat blandit vitae parturient per euismod id donec nibh urna posuere suspendisse montes gravida porttitor netus habitasse elit mauris eu pulvinar lacinia vel magna praesent suscipit. Taciti orci dictumst hendrerit. Justo semper quis tristique felis. Laoreet vivamus porta litora est proin nunc Urna netus lacus gravida nisl Nunc Venenatis torquent. Urna platea netus purus viverra. Diam erat hymenaeos nec rutrum morbi aliquam torquent. Mauris scelerisque massa Sapien magna pede sociis consequat ac semper imperdiet fames pede interdum bibendum. Pretium porta rutrum fusce ad potenti et mi fames ornare est eu interdum turpis tristique volutpat orci. Auctor ad id porttitor curabitur sit magna torquent pulvinar. Bibendum elementum quam facilisis.\
                    <br'+'>'+'<br'+'>'+'\
                    Diam placerat egestas nullam. Velit. Varius elementum convallis, ipsum, sollicitudin tortor phasellus facilisi proin scelerisque non. Vestibulum, natoque platea tincidunt nonummy montes dolor habitasse augue nibh metus nec cras ipsum sociosqu curae;. Diam sociis ornare cras integer mattis vulputate laoreet at malesuada, laoreet posuere magnis aliquam fusce magna vivamus aenean inceptos ad. Sagittis. Conubia aptent magnis nam. Sociosqu nec amet magnis ornare pulvinar. Faucibus aenean vehicula habitasse.\
                    <br'+'>'+'<br'+'>'+'\
                    Augue ultricies tempus sodales, est ante suscipit venenatis lacinia taciti magna nam fames montes ornare rutrum accumsan lectus Ridiculus hendrerit pede. Primis torquent sociis fusce litora Posuere. Elementum quisque. Netus eros dictumst morbi vitae litora porta porttitor fusce elementum non dictumst semper proin habitant faucibus sociis aptent conubia nunc. Tristique tortor montes euismod natoque Purus nonummy quisque luctus purus porttitor pulvinar senectus praesent quis imperdiet proin arcu dui natoque bibendum elementum pede integer donec.\
                    <br'+'>'+'<br'+'>'+'\
                    Cubilia diam ultricies malesuada lectus parturient faucibus, quisque aenean, pretium semper vel. Magna pharetra ornare netus euismod pellentesque morbi suscipit mus quisque libero curabitur neque enim. Sem senectus egestas elementum interdum pulvinar Eros et suspendisse neque mauris aliquet aliquet amet consequat habitant nascetur justo parturient nulla hendrerit nulla dolor netus eu sed sem viverra gravida gravida semper sapien in molestie cum ut dis eu vulputate tellus Vivamus. Dignissim bibendum amet cubilia ipsum. Mollis taciti torquent. Conubia velit dictumst turpis Purus condimentum taciti sagittis penatibus velit. Elementum nisi est. Laoreet congue placerat dis risus. Mollis. Elementum fringilla aptent molestie maecenas adipiscing a quam vestibulum metus, pretium. Dolor elementum ut nisi a phasellus.';
                    put_lorem_ipsum_text(text_to_put);
                    break;
                default:
                  // do nothing
            }
        });

        $(parent.document).on('blur', '#swk-edit-text textarea', function(e) { 
		    iframeScope.parentScope.actionTabs["contentEditing"] = true;
		    iframeScope.parentScope.disableContentEdit()
		});
	})
})(angular.element);