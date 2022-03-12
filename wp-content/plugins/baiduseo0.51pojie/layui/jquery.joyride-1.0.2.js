(function($) {
	$.fn.joyride = function(options) {
		var settings = {
			'tipLocation': 'bottom',
			'scrollSpeed': 300,
			'timer': 0,
			'startTimerOnClick': false,
			'nextButton': true,
			'tipAnimation': 'pop',
			'tipAnimationFadeSpeed': 300,
			'cookieMonster': false,
			'cookieName': 'JoyRide',
			'cookieDomain': false,
			'tipContainer': 'body',
			'inline': false,
			'tipContent': '#joyRideTipContent',
			'postRideCallback': null
		};
		var options = $.extend(settings, options);
		return this.each(function() {
			if ($(options.tipContent).length === 0) return;
			$(options.tipContent).hide();
			var bodyOffset = $(options.tipContainer).children('*').first().position(),
				tipContent = $(options.tipContent + ' li'),
				count = skipCount = 0,
				prevCount = -1,
				timerIndicatorInstance, timerIndicatorTemplate =
				'<div class="joyride-timer-indicator-wrap"><span class="joyride-timer-indicator"></span></div>',
				tipTemplate = function(tipClass, index, buttonText, self) {
					return '<div class="joyride-tip-guide ' + tipClass + '" id="joyRidePopup' + index +
						'"><span class="joyride-nub"></span>' + $(self).html() + buttonText +
						'<a href="javascript:void(0)" class="joyride-close-tip">X</a>' + timerIndicatorInstance + '</div>';
				},
				tipLayout = function(tipClass, index, buttonText, self) {
					if (index == 0 && settings.startTimerOnClick && settings.timer > 0 || settings.timer == 0) {
						timerIndicatorInstance = '';
					} else {
						timerIndicatorInstance = timerIndicatorTemplate;
					}
					if (!tipClass) tipClass = '';
					(buttonText != '') ? buttonText = '<a href="#" class="joyride-next-tip small nice radius yellow button">' +
						buttonText + '</a>': buttonText = '';
					if (settings.inline) {
						$(tipTemplate(tipClass, index, buttonText, self)).insertAfter('#' + $(self).data('id'));
					} else {
						$(options.tipContainer).append(tipTemplate(tipClass, index, buttonText, self));
					}
					
				};
			if (!settings.cookieMonster || !$.cookie(settings.cookieName)) {
				tipContent.each(function(index) {
					var buttonText = $(this).data('text'),
						tipClass = $(this).attr('class'),
						self = this;
				    console.log(buttonText)	
					if (settings.nextButton && buttonText == undefined) {
						buttonText = 'Next';
					}
					if (settings.nextButton || !settings.nextButton && settings.startTimerOnClick) {
						if ($(this).attr('class')) {
							tipLayout(tipClass, index, buttonText, self);
				// 			window.location.reload();
						} else {
							tipLayout(false, index, buttonText, self);
				// 			window.location.reload();
						}
					} else if (!settings.nextButton) {
						if ($(this).attr('class')) {
							tipLayout(tipClass, index, '', self);
				// 			window.location.reload();
						} else {
							tipLayout(false, index, '', self);
				// 			window.location.reload();
						}
					}
					$('#joyRidePopup' + index).hide();
				// 	$('#joyRidePopup' + index).click(function(){
				// 	      window.location.reload();
				// 	})
					
					
					
				});
			}
			showNextTip = function() {
				var parentElementID = $(tipContent[count]).data('id'),
					parentElement = $('#' + parentElementID);
				while (parentElement.offset() === null) {
					count++;
					skipCount++;
					prevCount++;
					parentElementID = $(tipContent[count]).data('id'), parentElement = $('#' + parentElementID);
					if ($(tipContent).length < count)
						break;
				}
				var windowHalf = Math.ceil($(window).height() / 2),
					currentTip = $('#joyRidePopup' + count),
					currentTipPosition = parentElement.offset(),
					currentParentHeight = parentElement.outerHeight(),
					currentTipHeight = currentTip.outerHeight(),
					nubHeight = Math.ceil($('.joyride-nub').outerHeight() / 2),
					tipOffset = 0;
				if (currentTip.length === 0) return;
				if (count < tipContent.length) {
					if (settings.tipAnimation == "pop") {
						$('.joyride-timer-indicator').width(0);
						if (settings.timer > 0) {
							currentTip.show().children('.joyride-timer-indicator-wrap').children('.joyride-timer-indicator').animate({
								width: $('.joyride-timer-indicator-wrap').width()
							}, settings.timer);
						} else {
							currentTip.show();
						}
					} else if (settings.tipAnimation == "fade") {
						$('.joyride-timer-indicator').width(0);
						if (settings.timer > 0) {
							currentTip.fadeIn(settings.tipAnimationFadeSpeed).children('.joyride-timer-indicator-wrap').children(
								'.joyride-timer-indicator').animate({
								width: $('.joyride-timer-indicator-wrap').width()
							}, settings.timer);
						} else {
							currentTip.fadeIn(settings.tipAnimationFadeSpeed);
						}
					}
					if (settings.tipLocation == "bottom") {
						currentTip.offset({
							top: (currentTipPosition.top + currentParentHeight + nubHeight),
							left: (currentTipPosition.left - bodyOffset.left)
						});
						currentTip.children('.joyride-nub').addClass('top').removeClass('bottom');
					} else if (settings.tipLocation == "top") {
						if (currentTipHeight >= currentTipPosition.top) {
							currentTip.offset({
								top: ((currentTipPosition.top + currentParentHeight + nubHeight) - bodyOffset.top),
								left: (currentTipPosition.left - bodyOffset.left)
							});
							currentTip.children('.joyride-nub').addClass('top').removeClass('bottom');
						} else {
							currentTip.offset({
								top: ((currentTipPosition.top) - (currentTipHeight + bodyOffset.top + nubHeight)),
								left: (currentTipPosition.left - bodyOffset.left)
							});
							currentTip.children('.joyride-nub').addClass('bottom').removeClass('top');
						}
					}
					tipOffset = Math.ceil(currentTip.offset().top - windowHalf);
					$("html, body").animate({
						scrollTop: tipOffset
					}, settings.scrollSpeed);
					if (count > 0) {
						if (skipCount > 0) {
							var hideCount = prevCount - skipCount;
							skipCount = 0;
						} else {
							var hideCount = prevCount;
						}
						if (settings.tipAnimation == "pop") {
							$('#joyRidePopup' + hideCount).hide();
						} else if (settings.tipAnimation == "fade") {
							$('#joyRidePopup' + hideCount).fadeOut(settings.tipAnimationFadeSpeed);
							
						}
					}
				} else if ((tipContent.length - 1) < count) {
					if (skipCount > 0) {
						var hideCount = prevCount - skipCount;
						skipCount = 0;
					} else {
						var hideCount = prevCount;
					}
					if (settings.cookieMonster == true) {
						$.cookie(settings.cookieName, 'ridden', {
							expires: 365,
							domain: settings.cookieDomain
						});
					} else {}
					if (settings.tipAnimation == "pop") {
						$('#joyRidePopup' + hideCount).fadeTo(0, 0);
					} else if (settings.tipAnimation == "fade") {
						$('#joyRidePopup' + hideCount).fadeTo(settings.tipAnimationFadeSpeed, 0);
					}
				}
				count++;
				if (prevCount < 0) {
					prevCount = 0;
				} else {
					prevCount++;
				}
			}
			if (!settings.inline || !settings.cookieMonster || !$.cookie(settings.cookieName)) {
				$(window).resize(function() {
					var parentElementID = $(tipContent[prevCount]).data('id'),
						currentTipPosition = $('#' + parentElementID).offset(),
						currentParentHeight = $('#' + parentElementID).outerHeight(),
						currentTipHeight = $('#joyRidePopup' + prevCount).outerHeight(),
						nubHeight = Math.ceil($('.joyride-nub').outerHeight() / 2);
					if (settings.tipLocation == "bottom") {
						$('#joyRidePopup' + prevCount).offset({
							top: (currentTipPosition.top + currentParentHeight + nubHeight),
							left: currentTipPosition.left
						});
					} else if (settings.tipLocation == "top") {
						if (currentTipPosition.top <= currentTipHeight) {
							$('#joyRidePopup' + prevCount).offset({
								top: (currentTipPosition.top + nubHeight + currentParentHeight),
								left: currentTipPosition.left
							});
						} else {
							$('#joyRidePopup' + prevCount).offset({
								top: ((currentTipPosition.top) - (currentTipHeight + nubHeight)),
								left: currentTipPosition.left
							});
						}
					}
				});
			}
			var interval_id = null,
				showTimerState = false;
			if (!settings.startTimerOnClick && settings.timer > 0) {
				showNextTip();
				interval_id = setInterval(function() {
					showNextTip()
				}, settings.timer);
			} else {
				showNextTip();
			}
			var endTip = function(e, interval_id, cookie, self) {
				e.preventDefault();
				clearInterval(interval_id);
				if (cookie) {
					$.cookie(settings.cookieName, 'ridden', {
						expires: 365,
						domain: settings.cookieDomain
					});
				}
				$(self).parent().hide();
				if (settings.postRideCallback != null) {
					settings.postRideCallback();
				}
			}
			$('.joyride-close-tip').click(function(e) {
				endTip(e, interval_id, settings.cookieMonster, this);
					window.location.reload();
			});
			$('.joyride-next-tip').click(function(e) {
				e.preventDefault();
				if (count >= tipContent.length) {
					endTip(e, interval_id, settings.cookieMonster, this);
					window.location.reload();
				}
				if (settings.timer > 0 && settings.startTimerOnClick) {
					showNextTip();
					clearInterval(interval_id);
					interval_id = setInterval(function() {
						showNextTip()
					}, settings.timer);
				} else if (settings.timer > 0 && !settings.startTimerOnClick) {
					clearInterval(interval_id);
					interval_id = setInterval(function() {
						showNextTip()
					}, settings.timer);
				} else {
					showNextTip();
				}
			});
		});
	};
	jQuery.cookie = function(key, value, options) {
		if (arguments.length > 1 && String(value) !== "[object Object]") {
			options = jQuery.extend({}, options);
			if (value === null || value === undefined) {
				options.expires = -1;
			}
			if (typeof options.expires === 'number') {
				var days = options.expires,
					t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}
			value = String(value);
			return (document.cookie = [encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value), options.expires ?
				'; expires=' + options.expires.toUTCString() : '', options.path ? '; path=' + options.path : '', options.domain ?
				'; domain=' + options.domain : '', options.secure ? '; secure' : ''
			].join(''));
		}
		options = value || {};
		var result, decode = options.raw ? function(s) {
			return s;
		} : decodeURIComponent;
		return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(
			result[1]) : null;
	};
})(jQuery);
