(function($) {
	$.guidance = function(json, fn) {
		json.url = json.url || "javascript:;";
		json.style = json.style || {};
		json.title = json.title || 'aa,bb';
		json.style.bgImg = json.style.bgImg || "url(../wp-content/plugins/baiduseo/image/questionmark.png)";
		json.style.point1 = json.style.point1 || 'url(img/jt1.png)';
		json.style.point2 = json.style.point2 || 'url(img/jt2.png)';
		var index = 0;
		if (json.obj.length > 0) {
			window.closeAll = function() {
				$('#maskBg').remove();
				window.closeAll = null;
			}
			var $viewG = $(
				'<div id="maskBg">\
				<div id="mask"></div>\
				<div id="maskTitle">\
					<div id="pointer"></div>\
					<div id="mianWarn">\
						<div id="warnData"><p></p></div>\
						<a href="javascript:;" id="nextStep">下一步</a>\
						<a href="javascript:;" id="closeMaskWarn" onclick = "closeAll ()">X</a>\
					</div>\
				</div>\
			</div>'
			);
			var viewW = $(document.body).width();
			var viewH = window.screen.availWidth;
			console.log(viewH)
			$viewG.css({
				'height': viewH + 'px',
				'width': viewW + 'px'
			});
			if ($('#maskBg').length <= 0) {
				$(document.body).append($viewG);
				$('#warnData').css('background', "#fff");
			}

			function mainWarn(posObj) {
				var iW = posObj.innerWidth();
				var iH = posObj.innerHeight();
				var iL = posObj.offset().left;
				var iT = posObj.offset().top;
				$('#mask').css({
					'width': iW + 'px',
					'height': iH + 'px',
					'border-width': iT + 'px' + ' ' + (viewW - iL - iW) + 'px' + ' ' + (viewH - iH - iT) + 'px' + ' ' + iL + 'px'
				});
				if (iL < 400) {
					$('#maskTitle').css({
						'left': iL + iW - 30 + 'px',
						'top': iT + iH + 10 + 'px'
					});
					$('#pointer').css('background-image', json.style.point1);
				} else if ((viewW - iL - iW) < 400) {
					$('#maskTitle').css({
						'left': iL - 400 + 'px',
						'top': iT + iH + 10 + 'px'
					});
					$('#pointer').css('background-image', json.style.point2);
				} else {
					$('#maskTitle').css({
						'left': iL + iW - 30 + 'px',
						'top': iT + iH + 10 + 'px'
					});
					$('#pointer').css('background-image', json.style.point1);
				}
			}
			if ($.isArray(json.obj)) {
				mainWarn(json.obj[index]);
				$('#warnData p').text(json.title[index]);
				$('#nextStep').bind('click', function() {
					index++;
					if (index == json.obj.length) {
						closeAll();
						return;
					}
					mainWarn(json.obj[index]);
					$('#warnData').text(json.title[index]);
				});
			} else {
				mainWarn(json.obj);
				$('#warnData').text(json.title);
				if (fn) {
					$('#nextStep').bind('click', fn);
				} else {
					$('#nextStep').bind('click', function() {
						closeAll();
					});
				}
			}
		}
	}
})(jQuery);
