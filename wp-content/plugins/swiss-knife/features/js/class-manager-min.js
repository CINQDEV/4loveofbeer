function toggle_shortcuts_classes_wrap(s){s(".shortcuts-classes-wrap").toggle(),s(".shortcuts-classes-wrap").hasClass("cm-active")?s(".shortcuts-classes-wrap").removeClass("cm-active"):(s(".shortcuts-classes-wrap").addClass("cm-active"),s("#insert_class_wrap li").show()),s("#class-not-found").hide()}function add_class_lock_divs(s,e){var a=$scope.iframeScope.classes,c="",o="style='display:none;'";"none"!=e&&void 0!==a[e].locked?(c="active",o=""):(c="",o="style='display:none;'"),0!=s("#oxygen-sidebar .class-manager").length?(""!=c?(s("#oxygen-sidebar .swk-lock-class").addClass(c),s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").addClass("locked")):(s("#oxygen-sidebar .swk-lock-class").removeClass("active"),s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").removeClass("locked")),""!=o?s("#oxygen-sidebar .swk-locked-div").hide():s("#oxygen-sidebar .swk-locked-div").show()):s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").prepend("<div class='class-manager'><div class='swk-lock-class "+c+"'><div class='lock'>Lock</div><div class='unlock'>Unlock</div></div><div class='insert-class'>Insert Classes</div></div><div class='swk-locked-div' "+o+"><?xml version='1.0' encoding='UTF-8'?><svg width='100pt' height='100pt' version='1.1' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'><path d='m80 50h-10v-10c0-11.047-8.9531-20-20-20s-20 8.9531-20 20v10h-10v40h60zm-20 0h-20v-10c0-5.5234 4.4766-10 10-10s10 4.4766 10 10z'/></svg></div>"),0!=s(".swk-lock-class").length&&s(".swk-lock-class").show()}jQuery(document).ready((function(s){Mousetrap.bind("esc",(function(e){0!=s(".shortcuts-classes-wrap").length&&s(".shortcuts-classes-wrap").hasClass("cm-active")&&s("#swk-multiple-class-close").trigger("click")})),Mousetrap.bind("shift+enter",(function(e){0!=s(".shortcuts-classes-wrap").length&&s(".shortcuts-classes-wrap").hasClass("cm-active")&&s("#swk-multiple-class-save").trigger("click")})),s("body").on("DOMSubtreeModified",".oxygen-sidebar-currently-editing, .oxygen-media-query-box-wrapper",(function(){let e=!!$scope.iframeScope.currentClass&&$scope.iframeScope.currentClass;e?add_class_lock_divs(s,e):(add_class_lock_divs(s,"none"),s("#oxygen-sidebar .swk-locked-div, #oxygen-sidebar .swk-lock-class").hide())})),(s=jQuery)(".oxygen-classes-dropdown").click((function(){let e=!!$scope.iframeScope.currentClass&&$scope.iframeScope.currentClass;if(!1!==e){var a="",c="";void 0!==$scope.iframeScope.classes[e].locked?(a="active",c=""):(a="",c="style='display:none;'"),0!=s("#oxygen-sidebar .swk-locked-div").length?(""!=a?(s("#oxygen-sidebar .swk-lock-class").addClass(a),s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").addClass("locked")):(s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").removeClass("locked"),s("#oxygen-sidebar .swk-lock-class").removeClass("active")),""!=c?s("#oxygen-sidebar .swk-locked-div").hide():s("#oxygen-sidebar .swk-locked-div").show()):s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").prepend("<div class='class-manager'><div class='swk-lock-class "+a+"'><div class='lock'>Lock</div><div class='unlock'>Unlock</div></div><div class='insert-class'>Insert Classes</div></div><div class='swk-locked-div' "+c+"><?xml version='1.0' encoding='UTF-8'?><svg width='100pt' height='100pt' version='1.1' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'><path d='m80 50h-10v-10c0-11.047-8.9531-20-20-20s-20 8.9531-20 20v10h-10v40h60zm-20 0h-20v-10c0-5.5234 4.4766-10 10-10s10 4.4766 10 10z'/></svg></div>"),0!=s(".swk-lock-class").length&&s(".swk-lock-class").show()}else s("#oxygen-sidebar .swk-locked-div, #oxygen-sidebar .swk-lock-class").hide()})),s(document).on("click","#oxygen-sidebar .swk-lock-class > div",(function(){s(this);let e=!!$scope.iframeScope.currentClass&&$scope.iframeScope.currentClass;var a=s(this).parents(".swk-lock-class");if(!1!==e){s("#oxygen-sidebar .swk-locked-div").toggle();var c=$scope.iframeScope.classes;s(a).hasClass("active")?(s(a).removeClass("active"),s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").removeClass("locked"),void 0!==c[e].locked&&delete c[e].locked):(s("#oxygen-sidebar .oxygen-media-query-and-selector-wrapper").addClass("locked"),s(a).addClass("active"),c[e].locked="")}})),s(document).on("click",".class-manager .insert-class",(function(){var e=$scope.iframeScope.component.active.id;if(0!==e&&"0"!==e&&(toggle_shortcuts_classes_wrap(s),s(".shortcuts-classes-wrap").hasClass("cm-active"))){var a,c,o=$scope.iframeScope.getActiveComponent(e)[0].classList;s(".shortcuts-classes-wrap .swk-multiple-class").each((function(){c=s(this),a=s(this).data("class");for(var e=0;e<o.length;e++){if(o[e]==a){s(c).addClass("active");break}s(c).removeClass("active")}}))}})),s(document).on("click","#swk-multiple-class-close",(function(){toggle_shortcuts_classes_wrap(s)})),s(".swk-multiple-class").click((function(){s(this).toggleClass("active")})),s("#swk-multiple-class-save").click((function(){if(s(".shortcuts-classes-wrap .swk-multiple-class").length>0){var e=$scope.iframeScope.component.active.id;if(""!=e){var a,c,o=$scope.iframeScope.getComponentsClasses(e,$scope.iframeScope.component.active.name);s(".shortcuts-classes-wrap .swk-multiple-class",parent.document).each((function(){a=s(this),c=s(a).data("class"),s(a).hasClass("active")?o.indexOf(c)<=0&&$scope.iframeScope.addClassToComponent(e,c):o.indexOf(c)>=0&&$scope.iframeScope.removeComponentClass(c,e)})),toggle_shortcuts_classes_wrap(s)}}})),s(".swk-class-search").keyup((function(){var e=s(this).val().toLowerCase();if(""!=e){var a,c=!1;s("#insert_class_wrap li").each((function(){a=s(this),s(a).find(".swk-multiple-class").text().indexOf(e)>=0?(s(a).show(),c=!0):s(a).hide()})).promise().done((function(){c?s("#class-not-found").hide():s("#class-not-found").show()}))}else s("#insert_class_wrap li").show(),s("#class-not-found").hide()})),s("#create-class").click((function(e){e.preventDefault();var a=s.trim(s(".swk-class-search").val().replace(/[^a-z0-9,_-]/gi,""));if(""!=a){var c=a.split(",");s.each(c,(function(e,a){$scope.iframeScope.addClass(a),s("#insert_class_wrap ul").first().append('<li style=""><span data-class="'+a+'" class="swk-multiple-class active">.'+a+"</span></li>")}))}}))}));