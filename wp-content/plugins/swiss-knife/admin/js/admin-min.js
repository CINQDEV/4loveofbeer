function SWK_hide_save_button(){jQuery(".scripts-rows .script-row").length<1&&jQuery(".scripts-manager #Scripts").hide()}function copyToClipboardSWK(e){var t,n,r=jQuery(e)[0];window.getSelection&&document.createRange?""==(t=window.getSelection()).toString()&&window.setTimeout((function(){(n=document.createRange()).selectNodeContents(r),t.removeAllRanges(),t.addRange(n)}),1):document.selection&&""==(t=document.selection.createRange()).text&&((n=document.body.createTextRange()).moveToElementText(r),n.select());var a=jQuery("<input>");jQuery("body").append(a),a.val(jQuery(e).text()).select(),document.execCommand("copy"),a.remove()}var mediaUploader,add_file_url,add_script_file;jQuery(document).ready((function(e){e(document).on("click",".script-info__edit",(function(){e(this).parent().parent().toggleClass("active")})),SWK_hide_save_button()})),jQuery(document).on("click",".font-file-upload",(function(e){e.preventDefault(),jQuery(this);var t=jQuery(this).parents(".file-upload");(add_file_url=wp.media.frames.file_frame=wp.media({title:"Choose Images",button:{text:"Choose Images"},multiple:!1})).on("open",(function(){var e=add_file_url.state().get("selection");ids=jQuery(t).find(".font-file").val().split(","),ids.forEach((function(t){attachment=wp.media.attachment(t),attachment.fetch(),e.add(attachment?[attachment]:[])}))})),add_file_url.on("select",(function(){var e=add_file_url.state().get("selection").toJSON();jQuery(t).find(".font-file").val(""),e.forEach((function(e){jQuery(t).find(".font-file").val(e.url)}))})),add_file_url.open()})),jQuery(document).on("click",".main-label .of-checkboxes.main",(function(){var e=jQuery(this).parents(".main-label").data("label");jQuery(this).is(":checked")?jQuery(".child-"+e).find(".of-checkboxes").prop("checked",!0):jQuery(".child-"+e).find(".of-checkboxes").prop("checked",!1)})),jQuery(document).on("click",".radio-boxes label span",(function(){var e=jQuery(this).parents("label");jQuery(e).find("input").trigger("click")})),jQuery(document).on("click",".of-save",(function(e){e.preventDefault();var t=jQuery(this),n=jQuery("input[name='theme']:checked").val();jQuery(t).find(".spinner").addClass("show"),jQuery(t).addClass("disabled"),jQuery.ajax({url:swk_ajax.ajaxurl,type:"post",data:{action:"saveOFOptions",verify_nonce:swk_ajax.swk_nonce,form_data:jQuery("#of-form .of-checkboxes").serialize(),theme_option:n},success:function(e){jQuery(t).find(".spinner").removeClass("show"),jQuery(t).removeClass("disabled"),location.reload()}})})),jQuery(document).on("click",".add-font-face, .add-variable-font",(function(e){e.preventDefault();var t=jQuery(this),n=jQuery(t).data("id"),r=jQuery(this).parents(".font");jQuery(t).hasClass("add-variable-font")&&jQuery(r).find(".new-font-box").remove(),jQuery(r).find(".font-face-boxes").append("<div class='new-font-box'>"+jQuery(".new-font-box-copy").html()+"<input type='hidden' name='parent_font[]' value='"+n+"'/></div>");var a=jQuery(r).find(".new-font-box:last-of-type");jQuery(t).hasClass("add-font-face")?(jQuery(a).find(".font-weight").show(),jQuery(a).find(".font-weight__from-to").hide(),jQuery(a).find("input[name='is_variable[]']").val("0")):(jQuery(a).find(".font-weight").hide(),jQuery(a).find(".font-weight__from-to").show(),jQuery(r).find(".font-face-bottom").remove(),jQuery(a).find("input[name='is_variable[]']").val("1"))})),jQuery(document).on("click",".font-style",(function(){var e=jQuery(this).parents(".font-field");jQuery(this).is(":checked")?jQuery(e).find("input[name='font_style[]']").val("italic"):jQuery(e).find("input[name='font_style[]']").val("normal")})),jQuery(document).on("click",".font-preload",(function(){var e=jQuery(this).parents(".font-field");jQuery(this).is(":checked")?jQuery(e).find("input[name='font_preload[]']").val("yes"):jQuery(e).find("input[name='font_preload[]']").val("no")})),jQuery(document).on("click",".edit-font-face",(function(e){e.preventDefault();var t=jQuery(this).parents(".new-font-box");jQuery(t).find(".font-field").toggle()})),jQuery(document).on("click",".delete-font-face",(function(e){e.preventDefault();var t=jQuery(this),n=jQuery(this).parents(".new-font-box"),r=jQuery(t).data("id");jQuery.ajax({url:swk_ajax.ajaxurl,type:"post",data:{action:"deleteFontFace",font_face_id:r,verify_nonce:swk_ajax.swk_nonce},success:function(e){jQuery(n).remove()}})})),jQuery(document).on("click",".delete-font-family",(function(e){e.preventDefault();var t=jQuery(this),n=jQuery(this).parents(".font"),r=jQuery(t).data("id");jQuery.ajax({url:swk_ajax.ajaxurl,type:"post",data:{action:"deleteFont",font_id:r,verify_nonce:swk_ajax.swk_nonce},success:function(e){jQuery(n).remove(),location.reload()}})})),jQuery(document).on("click",".add-new-font",(function(e){e.preventDefault(),jQuery(".add-font").show(),jQuery(".font-faces").hide()})),jQuery(document).on("click",".script-file-upload",(function(e){e.preventDefault(),jQuery(this);var t=jQuery(this).parents(".file-upload");(add_script_file=wp.media.frames.file_frame=wp.media({title:"Choose Images",button:{text:"Choose Images"},multiple:!1})).on("open",(function(){var e=add_script_file.state().get("selection");ids=jQuery(t).find(".script-file").val().split(","),ids.forEach((function(t){attachment=wp.media.attachment(t),attachment.fetch(),e.add(attachment?[attachment]:[])}))})),add_script_file.on("select",(function(){var e=add_script_file.state().get("selection").toJSON();jQuery(t).find(".script-file").val(""),e.forEach((function(e){jQuery(t).find(".script-file").val(e.url)}))})),add_script_file.open()})),jQuery(document).on("click",".swk-new-script",(function(e){e.preventDefault(),jQuery(".scripts-rows").append(jQuery(".script-copy").html()),jQuery(".scripts-manager #Scripts").show()})),jQuery(document).on("click",".swk-save-scripts",(function(e){e.preventDefault(),jQuery("#save-scripts").trigger("click")})),jQuery(document).on("click",".script-frontend-only",(function(){var e=jQuery(this).parents(".of-input");jQuery(this).is(":checked")?jQuery(e).find("input[name='script_frontend_only[]']").val("1"):jQuery(e).find("input[name='script_frontend_only[]']").val("0")})),jQuery(document).on("change","select[name='script_type[]']",(function(){var e=jQuery(this).parents(".script-row"),t=jQuery(e).find("input[name='script_name[]']").val().toLowerCase().replace(/ /g,"-");"js"==jQuery(this).val()?(jQuery(e).find(".script-type-wrap > svg").removeClass("css-icon--svg"),jQuery(e).find(".script-type-wrap > svg").removeClass("js-icon--svg"),jQuery(e).find(".script-type-wrap > svg").addClass("js-icon--svg"),jQuery(e).find(".script-type-wrap > svg > use").attr("href","#js-icon"),jQuery(e).find(".reg-enq").html("wp_enqueue_script('"+t+"');")):(jQuery(e).find(".script-type-wrap > svg").removeClass("css-icon--svg"),jQuery(e).find(".script-type-wrap > svg").removeClass("js-icon--svg"),jQuery(e).find(".script-type-wrap > svg").addClass("css-icon--svg"),jQuery(e).find(".script-type-wrap > svg > use").attr("href","#css-icon"),jQuery(e).find(".reg-enq").html("wp_enqueue_style('"+t+"');"))})),jQuery(document).on("keyup","input[name='script_name[]']",(function(){var e=jQuery(this).parents(".script-row"),t=jQuery(this).val().toLowerCase().replace(/ /g,"-");"js"==jQuery(this).find("input[name='script_type[]']").val()?jQuery(e).find(".reg-enq").html("wp_enqueue_script('"+t+"');"):jQuery(e).find(".reg-enq").html("wp_enqueue_style('"+t+"');")})),jQuery(document).on("click",".swk-copy-code",(function(e){var t=jQuery(this).parents(".script-row.edit-style"),n=jQuery(this).parents(".script-row__info");jQuery(t).find(".reg-shortcode").trigger("click"),jQuery(n).find("span").html("Copied"),setTimeout((function(){jQuery(n).find("span").html("Click to copy")}),3e3)})),jQuery(document).on("click",".reg-shortcode",(function(){var e=jQuery(this);copyToClipboardSWK(jQuery(e).find(".reg-enq")),jQuery(e).find("span").html("Copied"),setTimeout((function(){jQuery(e).find("span").html("Click to copy")}),3e3)})),jQuery(document).on("click",".swk-delete-script",(function(e){e.preventDefault();var t=jQuery(this).parents(".script-row"),n=jQuery(this).data("id");confirm("Are you sure you want to delete this script?")&&(""!=n?jQuery.ajax({url:swk_ajax.ajaxurl,type:"post",data:{action:"deleteSWKScript",script_id:n,verify_nonce:swk_ajax.swk_nonce},success:function(e){jQuery(t).remove(),SWK_hide_save_button()}}):(jQuery(t).remove(),SWK_hide_save_button()))})),jQuery(document).on("click",".swk-export",(function(e){e.preventDefault();var t=jQuery(this);jQuery(".swk-alert").hide(),jQuery(t).addClass("disabled"),jQuery(t).find(".spinner").addClass("show"),jQuery.ajax({url:swk_ajax.ajaxurl,type:"post",data:{action:"swkExportSettings",swk_nonce:swk_ajax.swk_nonce,verify_nonce:swk_ajax.swk_nonce},success:function(e){if("not_authorized"==e)jQuery(".swk-alert.alert-warning").html("Not authorized!").show();else{var n=document.createElement("a"),r=new Blob(["\ufeff"+e],{type:"application/json;charset=utf-8;"}),a=URL.createObjectURL(r);n.href=a,n.download="swiss-knife-settings-"+Date.now()+".json",document.body.appendChild(n),n.click(),document.body.removeChild(n)}jQuery(t).removeClass("disabled"),jQuery(t).find(".spinner").removeClass("show")}})})),jQuery(document).on("click",".swk-import",(function(e){e.preventDefault(),jQuery("#upload_json").submit()})),jQuery(document).on("submit","#upload_json",(function(e){if(e.preventDefault(),jQuery(".swk-alert").hide(),jQuery("#swk-json").get(0).files.length<1)jQuery(".swk-alert.alert-warning").html("Please attach settings json file!").show();else{jQuery(".swk-import").addClass("disabled"),jQuery(".swk-import").find(".spinner").addClass("show");var t=new FormData(this);t.append("action","swkImportSettings"),t.append("swk_nonce",swk_ajax.swk_nonce),t.append("verify_nonce",swk_ajax.swk_nonce),jQuery.ajax({url:swk_ajax.ajaxurl,type:"post",contentType:!1,cache:!1,processData:!1,data:t,dataType:"json",success:function(e){(e.status="success")?jQuery(".swk-alert.alert-success").html(e.message).show():jQuery(".swk-alert.alert-warning").html(e.message).show(),jQuery(".swk-import .spinner").removeClass("show"),jQuery(".swk-import").removeClass("disabled"),setTimeout((function(){jQuery(".swk-alert").hide()}),5e3)}})}}));