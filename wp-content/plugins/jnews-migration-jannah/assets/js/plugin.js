!function(n){"use strict";function t(){n(".jnews-migration-btn > .button").on("click",(function(t){n(this).hasClass("nodirect")&&t.preventDefault();var i=n(".jeg-migration-wrapper"),s=i.find(".jnews-migration-btn").attr("data-post-count"),e=i.find(".jnews-migration-btn .nonce").val(),o=100/s;s>0&&(i.addClass("active"),i.find(".jnews-migration-btn .button").html('<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>'+i.find(".jnews-migration-btn .button").data("progress")),i.find(".progress-line span").html("0/"+s),a(i,e,s,o,0),i.find(".jnews-migration-btn").attr("data-post-count",0))}))}function a(t,i,s,e,o){n.ajax({url:ajaxurl,type:"post",dataType:"json",data:{action:"jnews_content_migration_jannah",nonce:i},success:function(r){++o<=s?(t.find(".progress-line span").html(o+"/"+s),t.find(".progress-line").width(e*o+"%"),n(".migration-log-list").append('<li class="migration-notice '+r.status+'">'+o+") "+r.message+"</li>"),a(t,i,s,e,o)):(t.removeClass("active"),t.find(".migration-status").html(t.data("success")),t.find(".jnews-migration-btn .button").html(t.find(".jnews-migration-btn .button").data("success")).removeClass("nodirect"))}})}n(document).on("ready",(function(){t()}))}(jQuery);