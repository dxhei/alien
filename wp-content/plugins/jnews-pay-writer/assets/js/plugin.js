;(function ($) {
  'use strict'

  window.jnews = window.jnews || {}
  window.jnews.payWriter = window.jnews.payWriter || {}

  var general, chart
  if (jnews.library.objKeys(jnews.payWriter).length > 0) {
    general = jnews.payWriter.general
    chart = jnews.payWriter.chart
  }

  /**
   * Bookmark Button Handler
   */
  window.jnews.payWriter = {
    chart: chart,
    general: general,
    init: function ($container) {
      var base = this
      base.delay_timer = null
      base.xhr = []
      base.$form = null
      base.$valid_icon = null

      if ($container === undefined) {
        base.container = $('body')
      } else {
        base.container = $container
      }

      //base.payapal_account_validate()
    },
    change_valid_icon: function (icon) {
      var base = this,
        current_class = base.$valid_icon.attr('class')
      if ('fa-spinner' === icon) {
        icon += ' fa-spin'
      }
      if (current_class) {
        base.$valid_icon.removeClass(current_class).addClass('fa ' + icon)
      } else {
        base.$valid_icon.removeClass(current_class).addClass('fa ' + icon)
      }
    },
    payapal_account_validate: function () {
      var base = this,
        $paypal_account = base.container.find('#paypal_account')
      $paypal_account.keyup(function () {
        var val = $paypal_account.val().trim()
        val = val.replace(/\s+/g, '')

        if (val.length > 0) {
          base.$form = $('.jeg_account_right form')
          base.$valid_icon = base.container.find('.paypal_account_text i')

          base.$form.attr('onsubmit', false)
          base.change_valid_icon('fa-spinner')
          clearTimeout(base.delay_timer)
          base.delay_timer = setTimeout(function () {
            if (typeof base.xhr === 'object') {
              base.xhr.forEach(function (request) {
                if ('function' === typeof request.abort) {
                  request.abort()
                }
              })
            }
            base.ajax_request(val)
          }, 500)
        }
      })
    },
    ajax_request: function (value) {
      var base = this
      base.xhr.push(
        $.ajax({
          url: jnews_ajax_url,
          type: 'post',
          dataType: 'json',
          data: {
            data: value,
            action: 'validate_paypal_account',
          },
          success: function (result) {
            if (result) {
              base.change_valid_icon('fa-check-circle')
              base.$form.removeAttr('onsubmit')
            } else {
              base.change_valid_icon('fa-times-circle')
              base.$form.attr('onsubmit', false)
            }
          },
        })
      )
    },
  }

  $(document).on('ready jnews-ajax-load', function (e, data) {
    jnews.payWriter.init()
  })
})(jQuery)
