!function(i){"use strict";i(document).on("ready",(function(){var t;i("h3.toggle-head-open").on("click",(function(){i(this).parent().find("div.toggle-content").slideToggle("slow"),i(this).hide(),i(this).parent().find("h3.toggle-head-close").show()})),i("h3.toggle-head-close").on("click",(function(){i(this).parent().find("div.toggle-content").slideToggle("slow"),i(this).hide(),i(this).parent().find("h3.toggle-head-open").show()})),i(".tooltip").tipsy({fade:!0,gravity:"s"}),i(".tooltip-nw").tipsy({fade:!0,gravity:"nw"}),i(".tooltip-ne").tipsy({fade:!0,gravity:"ne"}),i(".tooltip-w").tipsy({fade:!0,gravity:"w"}),i(".tooltip-e").tipsy({fade:!0,gravity:"e"}),i(".tooltip-sw").tipsy({fade:!0,gravity:"w"}),i(".tooltip-se").tipsy({fade:!0,gravity:"e"}),i(".ttip, .tooltip-n").tipsy({fade:!0,gravity:"s"}),i(".tooldown, .tooltip-s").tipsy({fade:!0,gravity:"n"}),t=i(".entry-content"),i("a.lightbox-enabled, a[rel='lightbox-enabled']").iLightBox({skin:jnewsmigration.lightbox_skin}),jnewsmigration.lightbox_all&&t.find("div.entry a").not("div.entry .gallery a").each((function(t,o){var n=o.href;/\.(jpg|jpeg|png|gif)$/.test(n)&&i(this).iLightBox({skin:jnewsmigration.lightbox_skin})})),jnewsmigration.lightbox_gallery&&(t.find("div.entry .gallery a").each((function(t,o){var n=o.href;/\.(jpg|jpeg|png|gif)$/.test(n)&&i(this).addClass("ilightbox-gallery")})),t.find(".ilightbox-gallery").iLightBox({skin:jnewsmigration.lightbox_skin,path:jnewsmigration.lightbox_thumb,controls:{arrows:jnewsmigration.lightbox_arrows}})),i("section.videos-lightbox a.single-videolighbox").iLightBox({skin:jnewsmigration.lightbox_skin,path:jnewsmigration.lightbox_thumb,controls:{arrows:jnewsmigration.lightbox_arrows}}),"yes"==jnewsmigration.woocommerce_lightbox&&i("a[rel='lightbox-enabled[product-gallery]']").iLightBox({skin:jnewsmigration.lightbox_skin,path:jnewsmigration.lightbox_thumb,controls:{arrows:jnewsmigration.lightbox_arrows}}),i(".content-inner a").magnificPopup({disableOn:function(){return!!i(this).hasClass("lightbox-enabled")}})}))}(jQuery);