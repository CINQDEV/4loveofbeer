!function(e){e("document").ready((function(){e(document).on("DOMSubtreeModified",".not-available-for-media .oxygen-button-list-button, .oxygen-active-element",(function(){"ct_image"==$scope.iframeScope.component.active.name&&$scope.isActiveName("ct_image")&&("1"==$scope.iframeScope.getOption("image_type")?e("#unsplash-image").length<1?e(".oxygen-ct_image-src").prepend('<div id="unsplash-image" style="position: absolute; right:0; border: 2px solid var(--oxy__input-border); font-size: 13px; top: -5px; padding:2px 5px; cursor: pointer; color: var(--oxy__text); border-radius: 4px;">Unsplash image</div>'):e("#unsplash-image").show():e("#unsplash-image").hide())}));const n=async()=>{const n=await async function(){try{const e="https://source.unsplash.com/random";return await(await fetch(e)).url}catch(e){return e}}();e(".not-available-for-media .oxygen-file-input input").val(n).trigger("change")};e(document).on("click","#unsplash-image",(function(){n()}))}))}(angular.element);