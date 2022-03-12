!function(t){"use strict";var e=function(t,e){this.element=t,this.module=e};e.prototype.init=function(){return this.canUseWebp(),this.injectEvent(),this},e.prototype.canUseWebp=function(){this.webp=new Promise((function(t){var e=new Image;e.src="data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA",e.onload=e.onerror=function(){t(2===e.height)}}))},e.prototype.injectEvent=function(){this.metaOption=t(this.module.container).find(".jeg_meta_option"),this.videoPreview=t(this.module.container).find(".jeg_post_video_preview"),this.moreoption=t(this.module.container).find(".jeg_meta_option .jeg_moreoption"),this.postWrapper=t(this.module.container).find(".jeg_post"),this.metaOptionEvent(),this.videoPreviewEvent()},e.prototype.videoPreviewEvent=function(){this.webp.then(function(e){e&&this.videoPreview.each(function(e,a){var i=t(a).parents(".jeg_thumb"),n=null,o=null,s=new Image;i.on("mouseenter",function(){var e=t(i).find(".jeg_post_video_preview"),a=t(e).data("preview");s.onload=function(){t(i).addClass("video_previewer"),e.append(s),n=setTimeout((function(){t(i).removeClass("video_previewer")}),2800),clearTimeout(o)};var l=a.indexOf("?")>-1?"&jdate=":"?";s.src=a+l+(new Date).getMonth()+(new Date).getFullYear()}.bind(this)).on("mouseleave",function(){s.onload=function(){t(i).removeClass("video_previewer"),o=setTimeout((function(){t(i).find(".jeg_post_video_preview img").remove(),s.src=""}),300),clearTimeout(n)},t(i).removeClass("video_previewer"),o=setTimeout((function(){t(i).find(".jeg_post_video_preview img").remove(),s.src=""}),300),clearTimeout(n)}.bind(this))}.bind(this))}.bind(this))},e.prototype.metaOptionEvent=function(){this.moreoption.each((function(e,a){t(a).superfish({popUpSelector:"ul,.jeg_add_to_playlist",delay:250,speed:"fast",animation:{opacity:"show"}}).supposition()})),t(window).on("click",function(){t(this.metaOption).removeClass("loading").removeClass("active"),t(this.metaOption).parents(".jeg_postblock").removeClass("menu-active")}.bind(this)),this.metaOption.on("click",function(e){e.preventDefault(),e.stopPropagation();var a=e.currentTarget,i=t(a).data("id"),n=t(a).find(".jeg_moreoption");return!t(a).hasClass("loading")&&(t(a).hasClass("active")?(t(a).removeClass("active"),!1):(t(a).addClass("loading"),void t.ajax({url:jnews_ajax_url,type:"post",dataType:"json",data:{post_id:i,action:"get_video_menu"},success:function(e){0===e.data.response?s.login(e.data):(t(this.metaOption).removeClass("loading").removeClass("active"),t(this.metaOption).parents(".jeg_postblock").removeClass("menu-active"),t(n).html("").append(e.data),t(a).removeClass("loading").addClass("active").show(),t(a).parents(".jeg_postblock").addClass("menu-active"),t(a).find('[data-action="jeg_add_post"]').on("click",s.ajaxAddPost.bind(this)),t(a).find(".jeg_moreoption > li:last-child > a").on("click",(function(t){t.preventDefault(),t.stopPropagation()})),t(a).find(".jeg_add_to_playlist").on("click",'[data-action="jeg_add_post"]',s.ajaxAddPost.bind(this)),t(a).find(".jeg_popuplink").on("click",s.openPlaylistPopup.bind(this)))}.bind(this)})))}.bind(this))};var a=function(){t(".jnews-playlist-items").on("click",'[data-action="jeg_remove_post"]',function(e){e.preventDefault(),this.ajaxRemovePost(t(e.currentTarget))}.bind(this)),t(".jeg_playlist_load_more").on("click","a",function(e){e.preventDefault(),this.ajaxLoadMore(t(e.currentTarget))}.bind(this)),t("[id*='jeg_playlist'] > .jeg_popupform_playlist > form").off("submit").on("submit",function(e){e.preventDefault(),this.ajaxPlaylistDashboard(t(e.currentTarget))}.bind(this))};a.prototype.ajaxLoadMore=function(t){var e=t.parents().find('[class*="playlist-items"]'),a={last:e.find(".jnews-playlist-item").last().data("id"),playlist_id:e.data("playlist-id"),type:"load_more",action:"playlist_handler"};this.do_ajax(a,t)},a.prototype.ajaxRemovePost=function(t){var e=t.parents().find('[class*="playlist-items"]'),a={post_id:t.data("post-id"),playlist_id:e.data("playlist-id"),type:"remove_post",action:"playlist_handler"};this.do_ajax(a,t)},a.prototype.ajaxPlaylistDashboard=function(t){var e=t.find('input[name="jnews-playlist-nonce"]'),a=t.find('input[name="type"]'),i={nonce:e.val(),type:a.val(),action:"playlist_handler"};if("create_playlist"===a.val()){var n=t.find('input[name="post_id"]');i.post_id=n.val()}if("create_playlist"!==a.val()){var o=t.find('input[name="playlist_id"]');i.playlist_id=o.val()}if("edit_playlist"===a.val()){var s=t.find('textarea[name="content"]'),l=t.find('input[name="image[]"]');i.content=s.val(),i.image=l.val()}if("delete_playlist"!==a.val()){var r=t.find('input[name="title"]'),d=t.find('select[name="visibility"]');i.title=r.val(),i.visibility=d.find(":selected").val()}this.do_ajax(i,t)},a.prototype.do_ajax=function(e,a){switch(e.type){case"remove_post":var i=a.closest('[class*="playlist-item"]').addClass("playlist-item-removing");break;case"create_playlist":case"edit_playlist":case"delete_playlist":var o=t(a).find(".form-message");o.html(""),a.find('input[type="submit"]').val(a.find('input[type="submit"]').data("process")),a.find('input[type="submit"]').prop("disabled",!0);break;case"load_more":var s=t(a).addClass("active").data("loading");t(a).text(s)}t.ajax({url:jnews_ajax_url,type:"post",dataType:"json",data:e,success:function(s){switch(e.type){case"remove_post":1===s.response?t(".playlist-item-removing").remove():t(".playlist-item-removing").html(i).removeClass("playlist-item-removing"),n(s.message);break;case"create_playlist":a.find('input[type="submit"]').val(a.find('input[type="submit"]').data("string")),a.find('input[type="submit"]').prop("disabled",!1),1===s.response?(n(s.message),t(a).trigger("reset"),t.magnificPopup.close()):o.html(s.message);break;case"edit_playlist":a.find('input[type="submit"]').val(a.find('input[type="submit"]').data("string")),a.find('input[type="submit"]').prop("disabled",!1),1===s.response?(n(s.message),t.magnificPopup.close(),setTimeout(location.reload(),500)):o.html(s.message);break;case"delete_playlist":a.find('input[type="submit"]').val(a.find('input[type="submit"]').data("string")),a.find('input[type="submit"]').prop("disabled",!1),1===s.response?(t.magnificPopup.close(),n(s.message),setTimeout(location.href=s.redirect,500)):o.html(s.message);break;case"load_more":if(s.next){var l=t(a).removeClass("active").data("load");t(a).text(l)}else a.remove();t(".jnews-playlist-items").append(s.html)}}.bind(this)})};var i=function(t){var e=!!("object"==typeof jnews&&"object"==typeof jnews.library)&&jnews.library;if("function"==typeof jnews.tns&&t.length&&e.forEach(t,(function(a,i){var n=a.getElementsByClassName("jeg_category_list_wrapper");n.length&&e.forEach(n,(function(a,i){var n=a,o=n.getElementsByClassName("jeg_category_list");t.length&&e.forEach(o,(function(t,a){var i=function(t,a){var n={resize:function(){e.requestAnimationFrame.call(e.win,(function(o){568>e.getWidth(e.globalBody)&&((a=e.dataStorage.get(t,"tiny-slider")).destroy(),a=a.rebuild(),i(t,a)),e.removeEvents(e.win,n)}))}};e.addEvents(e.win,n),a.events.on("dragStart",(function(t){t.event.preventDefault(),t.event.stopPropagation()})),e.addClass(t,"jeg_tns_active"),e.dataStorage.put(t,"tiny-slider",a)},o=!1,s={controls:void 0!==t.dataset.nav&&"true"===t.dataset.nav&&t.dataset.nav,autoplay:void 0!==t.dataset.autoplay&&"true"===t.dataset.autoplay&&t.dataset.autoplay,items:void 0===t.dataset.items?3:t.dataset.items,autoplayTimeout:void 0===t.dataset.delay?3e3:t.dataset.delay,textDirection:"ltr",gutter:void 0===t.dataset.margin?20:t.dataset.margin};if("undefined"!=typeof jnewsoption&&(o=1==jnewsoption.rtl),"undefined"!=typeof jnewsgutenbergoption&&(o=1==jnewsgutenbergoption.rtl),s.autoWidth=!o,s.textDirection=o?"rtl":"ltr",e.hasClass(n,"jeg_col_12")&&(s.items=void 0===t.dataset.items?5:t.dataset.items),!e.hasClass(t,"jeg_tns_active")){s.items>t.children.length&&(s.items=t.children.length-1>1?t.children.length-1:1);var l=jnews.tns({container:t,textDirection:s.textDirection,controls:s.controls,gutter:s.gutter,controlsText:["",""],nav:!1,center:!0,loop:!0,mouseDrag:!0,items:s.items,autoplay:s.autoplay,autoplayTimeout:s.autoplayTimeout,autoWidth:s.autoWidth,responsive:{0:{items:1,autoWidth:!1},321:{items:1,gutter:s.gutter>15?15:s.gutter,autoWidth:!1},568:{items:s.items,gutter:s.gutter>15?15:s.gutter,autoWidth:s.autoWidth},1024:{items:s.items,autoWidth:s.autoWidth}},onInit:function(t){void 0!==t.nextButton&&e.addClass(t.nextButton,"tns-next"),void 0!==t.prevButton&&e.addClass(t.prevButton,"tns-prev")}});void 0!==l&&i(t,l)}}))}));var o=a.getElementsByClassName("jeg_playlist_wrapper");o.length&&e.forEach(o,(function(t,a){var i=t,n=i.getElementsByClassName("jeg_playlist");n.length&&e.forEach(n,(function(t,a){var n=!1,o={controls:void 0!==t.dataset.nav&&"true"===t.dataset.nav&&"true"===t.dataset.nav,autoplay:void 0!==t.dataset.autoplay&&"true"===t.dataset.autoplay&&"true"===t.dataset.autoplay,items:void 0===t.dataset.items?3:parseInt(t.dataset.items),autoplayTimeout:void 0===t.dataset.delay?3e3:parseInt(t.dataset.delay),textDirection:"ltr",gutter:void 0===t.dataset.margin?20:parseInt(t.dataset.margin)};if("undefined"!=typeof jnewsoption&&(n=1==jnewsoption.rtl),"undefined"!=typeof jnewsgutenbergoption&&(n=1==jnewsgutenbergoption.rtl),o.textDirection=n?"rtl":"ltr",e.hasClass(i,"jeg_col_12")&&(o.items=void 0===t.dataset.items?5:parseInt(t.dataset.items)),!e.hasClass(t,"jeg_tns_active")){o.items>t.children.length&&(o.items=t.children.length-1>1?t.children.length-1:1);var s=jnews.tns({container:t,textDirection:o.textDirection,controls:o.controls,gutter:o.gutter,controlsText:["",""],nav:!1,loop:!0,mouseDrag:!0,items:o.items,autoplay:o.autoplay,autoplayTimeout:o.autoplayTimeout,responsive:{0:{items:1},321:{items:1,gutter:o.gutter>15?15:o.gutter},568:{items:o.items,gutter:o.gutter>15?15:o.gutter},1024:{items:o.items}},onInit:function(t){void 0!==t.nextButton&&e.addClass(t.nextButton,"tns-next"),void 0!==t.prevButton&&e.addClass(t.prevButton,"tns-prev")}});void 0!==s&&(s.events.on("dragStart",(function(t){t.event.preventDefault(),t.event.stopPropagation()})),e.addClass(t,"jeg_tns_active"),e.dataStorage.put(t,"tiny-slider",s))}}))}))})),"object"==typeof jnews.video&&"function"==typeof jnews.video.carousel){var a=e.globalBody.getElementsByClassName("jeg_postblock_video_carousel");a.length&&e.forEach(a,(function(t,a){jnews.video.carousel({container:t,textDirection:1==jnewsoption.rtl?"rtl":"ltr",onInit:function(t){void 0!==t.nextButton&&e.addClass(t.nextButton,"tns-next"),void 0!==t.prevButton&&e.addClass(t.prevButton,"tns-prev")}})}))}},n=function(e){var a=t("#notification_action_renderer"),i=a.find("#paper_toast").outerHeight()+30;a.stop(!0,!0),a.finish(),a.removeAttr("style"),a.find("#paper_toast").removeAttr("style"),a.find("#paper_toast").css({opacity:1}),a.find("#paper_toast span#label").html(e),a.animate({bottom:i},1e3,function(){setTimeout(function(){a.animate({bottom:0},1e3,function(){a.find("#paper_toast span#label").html(" ")}.bind(this))}.bind(this),3e3)}.bind(this))},o=function(e,a){var i=t(window),n=t(e);i.on("click",(function(){n.removeClass("loading").removeClass("active")})),a&&e.find(".jeg_moreoption").each((function(e,a){t(a).superfish({popUpSelector:"ul,.jeg_add_to_playlist",delay:250,speed:"fast",animation:{opacity:"show"}}).supposition()})),e.on("click",(function(e){e.preventDefault(),e.stopPropagation();var n=e.currentTarget,o=t(n);if(o.hasClass("loading"))return!1;if(o.hasClass("active")){if(o.removeClass("active"),!a){var l=o.find(".jeg_sharelist");l.length>0&&l.removeClass("supposition-active")}return!1}if(o.addClass("loading"),a){var r=o.data("id"),d=o.find(".jeg_moreoption");t.ajax({url:jnews_ajax_url,type:"post",dataType:"json",data:{post_id:r,action:"get_video_menu"},success:function(e){0===e.data.response?s.login(e.data):(t(d).html("").append(e.data),o.removeClass("loading").addClass("active"),o.parents(".jeg_postblock").addClass("menu-active"),o.find('[data-action="jeg_add_post"]').on("click",s.ajaxAddPost),o.find(".jeg_moreoption > li:last-child > a").on("click",(function(t){t.preventDefault(),t.stopPropagation()})),o.find(".jeg_add_to_playlist").on("click",'[data-action="jeg_add_post"]',s.ajaxAddPost),o.find(".jeg_popuplink").on("click",s.openPlaylistPopup))}})}else o.removeClass("loading").addClass("active"),o.each((function(e,a){var n,o=t(a).find(".jeg_sharelist");if(o.length>0){var s=o.width(),l=(o.parents(".jeg_meta_share").width(),i.width()+(n="x",window["y"==n?"pageYOffset":"pageXOffset"]||document.documentElement&&document.documentElement["y"==n?"scrollTop":"scrollLeft"]||document.body["y"==n?"scrollTop":"scrollLeft"]));o.offset().left+s>l&&o.addClass("supposition-active")}})),o.find(".jeg_popuplink").on("click",s.openPlaylistPopup)}))},s={ajaxAddPost:function(e){e.preventDefault(),e.stopPropagation();var a=t(e.currentTarget),i=a.hasClass("active")?"exclude_post":"add_post",n={post_id:a.parents(".jeg_meta_option").data("id"),playlist_id:a.data("playlist-id"),type:i,action:"playlist_handler"};s.do_ajax(n,a)},do_ajax:function(e,a){switch(e.type){case"exclude_post":case"add_post":a.addClass("loading")}t.ajax({url:jnews_ajax_url,type:"post",dataType:"json",data:e,success:function(t){switch(e.type){case"exclude_post":a.removeClass("active").removeClass("loading"),n(t.message);break;case"add_post":a.addClass("active").removeClass("loading"),n(t.message)}}})},openPlaylistPopup:function(e){e.preventDefault(),e.stopPropagation();var a=t(t(e.currentTarget).attr("href")).find('input[name="post_id"]');t(a).length&&a.val(t(e.currentTarget).data("post-id")),t.magnificPopup.open({type:"inline",removalDelay:500,midClick:!0,mainClass:"mfp-zoom-out",items:{src:t(e.currentTarget).attr("href")}})},login:function(e){e&&t("#jeg_loginform form").find("h3").html(e.message);var a=window.jnews.loginregister;t.magnificPopup.open({type:"inline",removalDelay:500,midClick:!0,mainClass:"mfp-zoom-out",items:{src:"#jeg_loginform"},callbacks:{beforeOpen:function(){this.st.mainClass="mfp-zoom-out",t("body").removeClass("jeg_show_menu")},change:function(){var e=this.content.find(".g-recaptcha"),i=this.content.find("form").data("type"),n=e.data("sitekey");this.content.find(".form-message p").remove(),a.validateCaptcha=!1,1==jnewsoption.recaptcha&&e.length&&(e.hasClass("loaded")?grecaptcha.reset(a.captcha[i]):(a.captcha[i]=grecaptcha.render(e.get(0),{sitekey:n,callback:a.validateResponse.bind(a)}),t(e).addClass("loaded")))}}})}},l=function(e){this.container=e,this.drop=".jeg_action",this.dropExpanded=".jeg_action_expanded",this.primaryNav='.item-list-tabs[aria-label*="primary navigation"]',this.classesDropExpanded="jeg_action_expanded",this.container.find(this.primaryNav).okayNav({swipe_enabled:!1,threshold:80,toggle_icon_content:"<span></span><span></span><span></span>"}),this.container.on("click touchstart",function(e){var a=t(e.target).parents(this.dropExpanded);t(this.dropExpanded).not(a).removeClass(this.classesDropExpanded)}.bind(this)),this.container.find(this.drop).on("click",function(e){var a=t(e.currentTarget);a.is(this.dropExpanded)?t(e.target).hasClass("jeg_action_toggle")&&(a.removeClass(this.classesDropExpanded),e.preventDefault()):(a.addClass(this.classesDropExpanded),e.preventDefault())}.bind(this))};l.prototype.dropMenuMoveItem=function(){t(this.container).find(this.drop).each((function(){var e=t(this);e.find(".menu-item").each((function(a,i){var n;a<2&&(n=t(i).detach()).length&&(n.removeClass("menu-item").find("a"),n.insertBefore(e))})),e.find(".menu-item").length||e.remove()}))},t(".jeg_module_hook").on("jnews_module_init",(function(a,i){var n=new e(this,i).init();t(this).data("video",n)})).on("jnews_module_ajax",(function(){t(this).data("video").injectEvent()})),t(document).on("ready",(function(){new a,new i(t("body")),new l(t("#buddypress")).dropMenuMoveItem();var e=t(".single-playlist"),n=t(".single-format-video"),r=e.find(".jeg_meta_option"),d=e.find(".jeg_meta_share"),p=n.find(".jeg_meta_option"),c=n.find(".jeg_meta_share");o(r),o(d),o(p,!0),o(c),t(".jeg_login_required").on("click",(function(t){t.preventDefault(),s.login()})),t("body").on("click",".jeg_bp_action.follow, .jeg_bp_action.unfollow",(function(e){var a,i,n,o,s,l,r,d,p;return e.preventDefault(),a=t(this),o=(n=a).parents(".follow-wrapper"),s=n.attr("id"),l=n.attr("href"),r=o.find(".jnews-spinner"),d=n.parents(".jeg_meta_subscribe:not(.no-follow)").find(".jeg_subscribe_count"),p="",s=s.split("-"),i=s[0],s=s[1],l=(l=(l=l.split("?_wpnonce="))[1].split("&"))[0],t.ajax({url:ajaxurl,type:"post",data:{action:"bp_"+i,uid:s,_wpnonce:l},beforeSend:function(){r.length&&(n.css("display","none"),r.css("display","block"))},success:function(e){if(e.length){var a=n.attr("class");p=t(e),a=a.replace(i+" ",""),p.addClass(a)}}}).always((function(){d.length?t.ajax({url:ajaxurl,type:"post",data:{action:"jnews_get_subscribe_count",uid:s,_wpnonce:l},success:function(e){if(e.status){var a=t(e.content);d.replaceWith(a)}}}).always((function(){r.length&&(n.replaceWith(p),r.css("display","none"))})):r.length&&(n.replaceWith(p),n.css("display",""),r.css("display","none"))})),!1}))})),t(document).on("jnews_vc_trigger jnews_elementor_trigger",(function(e,a){new i(t(a))}))}(jQuery);