(function ($) {

	'use strict';

	window.jnews = window.jnews || {};

	/**
 * Bookmark Button Handler
 */
	window.jnews.bookmark = {
		init: function ($container) {
			var base = this;

			if ($container === undefined) {
				base.container = $('body');
			} else {
				base.container = $container;
			}

			base.filter_post();
			base.add_button();
		},
		add_button: function () {
			var base = this;

			base.container.find('.jeg_meta_bookmark > a').off('click').on('click', function (e) {
				e.preventDefault();

				if ($(this).hasClass('clicked')) return;

				base.element = base.container.find('.jeg_meta_bookmark > a[data-id=' + $(this).attr('data-id') + ']');
				base.parent = base.element.parent();
				base.button = base.parent.find('.fa');
				base.article = base.container.find('.jeg_account_right');
				base.account_page = base.container.find('.jeg_account_page').length;

				base.ajax_request();
			});
		},
		ajax_request: function () {
			var base = this;
			base.button.removeClass('fa-bookmark');
			base.button.removeClass('fa-bookmark-o');
			base.parent.addClass('clicked').find('.fa').addClass('fa-pulse');

			$.post(jnews_ajax_url, { action: 'jnews_refresh_nonce', refresh_action_nonce: 'jnews_nonce' }).always(function (data) {
				$.ajax({
					url: jnews_ajax_url,
					type: 'POST',
					dataType: 'json',
					data: {
						'jnews_nonce': data.jnews_nonce,
						'action': 'bookmark_handler',
						'type': base.element.data('added') ? 'remove_bookmark' : 'add_bookmark',
						'post_id': base.element.data('id'),
					},
				}).done(function (result) {
					if (result.success === false) {
						var obj = window.jnews.loginregister

						$('#jeg_loginform form').find('h3').html(result.message);
						$.magnificPopup.open({
							removalDelay: 500,
							midClick: true,
							mainClass: 'mfp-zoom-out',
							type: 'inline',
							items: {
								src: '#jeg_loginform'
							},
							callbacks: {
								beforeOpen: function () {
									this.st.mainClass = 'mfp-zoom-out'
									$('body').removeClass('jeg_show_menu')
								},
								change: function () {
									var element = this.content.find('.g-recaptcha')
									var type = this.content.find('form').data('type')
									var key = element.data('sitekey')
									this.content.find('.form-message p').remove()
									obj.validateCaptcha = false

									if (jnewsoption.recaptcha == 1 && element.length) {
										if (!element.hasClass('loaded')) {
											obj.captcha[type] = grecaptcha.render(element.get(0), {
												'sitekey': key,
												'callback': obj.validateResponse.bind(obj),
											})
											$(element).addClass('loaded')
										} else {
											grecaptcha.reset(obj.captcha[type])
										}
									}
								}
							}
						});
					} else {
						var data = result.data;

						if (data.bookmark) {
							base.element.data('added', true);

							if (base.account_page) {
								base.article.find('.' + data.class + ' .jeg_meta_bookmark .fa').addClass('fa-bookmark');
								base.article.find('.' + data.class + ' .jeg_meta_bookmark .fa').removeClass('fa-bookmark-o');
								base.article.find('.' + data.class).addClass('added');
								base.article.find('.' + data.class).removeClass('deleted');
							} else {
								base.button.addClass('fa-bookmark');
								base.button.removeClass('fa-bookmark-o');
							}
						} else {
							base.element.data('added', false);

							if (base.account_page) {
								base.article.find('.' + data.class + ' .jeg_meta_bookmark .fa').addClass('fa-bookmark-o');
								base.article.find('.' + data.class + ' .jeg_meta_bookmark .fa').removeClass('fa-bookmark');
								base.article.find('.' + data.class).addClass('deleted');
								base.article.find('.' + data.class).removeClass('added');
							} else {
								base.button.addClass('fa-bookmark-o');
								base.button.removeClass('fa-bookmark');
							}
						}
					}
				}).fail(function (result, textStatus, errorThrown) {
					if (textStatus === 'error') {
						alert(errorThrown.toString());
					} else if (textStatus === 'timeout') {
						alert('Execution Timeout');
					}
				}).always(function (result, textStatus, errorThrown) {
					base.element.attr('data-message', result.data.message);
					if (base.account_page) {
						base.article.find('.' + result.data.class + ' .jeg_meta_bookmark').removeClass('clicked').find('.fa').removeClass('fa-pulse');
					} else {
						base.parent.removeClass('clicked').find('.fa').removeClass('fa-pulse');
					}
				});
			})
		},
		filter_post: function () {
			$('.jeg_post_list_filter select[name="post-list-filter"]').on('change', function () {
				var order = $(this).val(),
					url = $('input[name="current-page-url"]').val();

				if (url.indexOf('?') > -1) {
					url += '&order=' + order;
				} else {
					url += '?order=' + order;
				}

				window.location.href = url;
			});
		},
	}

	$(document).on('ready jnews-ajax-load', function (e, data) {
		jnews.bookmark.init()
	});

})(jQuery);