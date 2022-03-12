;(function ($) {
  'use strict'
  window.jnews = window.jnews || {}
  window.jnews.payWriter = window.jnews.payWriter || {}
  var isJNewsLibrary = 'object' === typeof jnews && 'object' === typeof jnews.library
  window.jnews.payWriter = function () {
    var base = this,
      delayTimer = null,
      xhr = {},
      container,
      arrayChunk = function (array, size) {
        var res = []
        for (let i = 0; i < array.length; i += size) {
          var chunk = array.slice(i, i + size)
          res.push(chunk)
        }
        return res
      },
      formSerialize = function (form, array) {
        if ('undefined' === typeof array) {
          array = false
        }
        if (!form || form.nodeName !== 'FORM') {
          return
        }
        var i,
          j,
          q = []
        if (array) {
          q = {}
        }
        for (i = form.elements.length - 1; i >= 0; i = i - 1) {
          if (form.elements[i].name === '') {
            continue
          }
          switch (form.elements[i].nodeName) {
            case 'INPUT':
              switch (form.elements[i].type) {
                case 'text':
                case 'hidden':
                case 'password':
                case 'button':
                case 'reset':
                case 'submit':
                  if (array) {
                    if (form.elements[i].name.indexOf('[]') > 0) {
                      if ('undefined' === typeof q[form.elements[i].name.replace('[]', '')]) {
                        q[form.elements[i].name.replace('[]', '')] = [JSON.parse(form.elements[i].value)]
                      } else {
                        q[form.elements[i].name.replace('[]', '')].push(JSON.parse(form.elements[i].value))
                      }
                    } else {
                      q[form.elements[i].name] = form.elements[i].value
                    }
                  } else {
                    q.push(form.elements[i].name + '=' + form.elements[i].value)
                  }
                  break
                case 'checkbox':
                case 'radio':
                  if (form.elements[i].checked) {
                    if (array) {
                      if (form.elements[i].name.indexOf('[]') > 0) {
                        if ('undefined' === typeof q[form.elements[i].name.replace('[]', '')]) {
                          q[form.elements[i].name.replace('[]', '')] = [JSON.parse(form.elements[i].value)]
                        } else {
                          q[form.elements[i].name.replace('[]', '')].push(JSON.parse(form.elements[i].value))
                        }
                      } else {
                        q[form.elements[i].name] = form.elements[i].value
                      }
                    } else {
                      q.push(form.elements[i].name + '=' + form.elements[i].value)
                    }
                  }
                  break
                case 'file':
                  break
              }
              break
            case 'TEXTAREA':
              if (array) {
                if (form.elements[i].name.indexOf('[]') > 0) {
                  if ('undefined' === typeof q[form.elements[i].name.replace('[]', '')]) {
                    q[form.elements[i].name.replace('[]', '')] = [JSON.parse(form.elements[i].value)]
                  } else {
                    q[form.elements[i].name.replace('[]', '')].push(JSON.parse(form.elements[i].value))
                  }
                } else {
                  q[form.elements[i].name] = form.elements[i].value
                }
              } else {
                q.push(form.elements[i].name + '=' + form.elements[i].value)
              }
              break
            case 'SELECT':
              switch (form.elements[i].type) {
                case 'select-one':
                  if (array) {
                    if (form.elements[i].name.indexOf('[]') > 0) {
                      if ('undefined' === typeof q[form.elements[i].name.replace('[]', '')]) {
                        q[form.elements[i].name.replace('[]', '')] = [JSON.parse(form.elements[i].value)]
                      } else {
                        q[form.elements[i].name.replace('[]', '')].push(JSON.parse(form.elements[i].value))
                      }
                    } else {
                      q[form.elements[i].name] = form.elements[i].value
                    }
                  } else {
                    q.push(form.elements[i].name + '=' + form.elements[i].value)
                  }
                  break
                case 'select-multiple':
                  for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                    if (form.elements[i].options[j].selected) {
                      if (array) {
                        if (form.elements[i].name.indexOf('[]') > 0) {
                          if ('undefined' === typeof q[form.elements[i].name.replace('[]', '')]) {
                            q[form.elements[i].name.replace('[]', '')] = [JSON.parse(form.elements[i].options[j].value)]
                          } else {
                            q[form.elements[i].name.replace('[]', '')].push(JSON.parse(form.elements[i].options[j].value))
                          }
                        } else {
                          q[form.elements[i].name] = form.elements[i].options[j].value
                        }
                      } else {
                        q.push(form.elements[i].name + '=' + form.elements[i].options[j].value)
                      }
                    }
                  }
                  break
              }
              break
            case 'BUTTON':
              switch (form.elements[i].type) {
                case 'reset':
                case 'submit':
                case 'button':
                  if (array) {
                    if (form.elements[i].name.indexOf('[]') > 0) {
                      if ('undefined' === typeof q[form.elements[i].name.replace('[]', '')]) {
                        q[form.elements[i].name.replace('[]', '')] = [JSON.parse(form.elements[i].value)]
                      } else {
                        q[form.elements[i].name.replace('[]', '')].push(JSON.parse(form.elements[i].value))
                      }
                    } else {
                      q[form.elements[i].name] = form.elements[i].value
                    }
                  } else {
                    q.push(form.elements[i].name + '=' + form.elements[i].value)
                  }
                  break
              }
              break
          }
        }
        return array ? q : encodeURIComponent(q.join('&'))
      },
      ajaxRequest = function (action, param) {
        if ('undefined' !== typeof xhr[action]) {
          return false
        } else {
          if ('create_bulk_payout' === action) {
            if ('undefined' === typeof param.payout_type) {
              param.payout_type = 'paypal'
            }
            var data = arrayChunk(param.data, 100),
              bulkResult = []
            jnews.library.forEach(data, function (value, index) {
              setTimeout(function () {
                var chunkParam = JSON.parse(JSON.stringify(param))
                chunkParam.data = value
                xhr[action + '_' + index] = jnews.library.ajax(
                  'POST',
                  ajaxurl,
                  {
                    data: chunkParam,
                    action: action,
                  },
                  function (result) {
                    var tempResult = JSON.parse(result)
                    // Create Bulk Payout
                    if (typeof tempResult.payout_list !== 'undefined' && tempResult.payout_list) {
                      jnews.library.forEach(tempResult.payout_list, function (listValue, listKey) {
                        bulkResult.push(listValue)
                      })
                    }
                    delete xhr[action + '_' + index]
                    if (jnews.library.objKeys(xhr).length === 0) {
                      if ('create_bulk_payout' === action) {
                        if (paypalBulkPayoutBtn.length) {
                          jnews.library.forEach(paypalBulkPayoutBtn, function ($paypalBulkPayoutBtn) {
                            jnews.library.removeClass($paypalBulkPayoutBtn, 'disabled')
                            $paypalBulkPayoutBtn.removeAttribute('disabled')
                            jnews.library.removeEvents($paypalBulkPayoutBtn, paypalBulkPayoutBtnEvent)
                          })
                        }
                        tempResult.payout_list = bulkResult
                        paypalPayoutPopupEvent(JSON.stringify(tempResult))
                        paypalResetData()
                        bulkPayoutAccordion()
                      }
                    }
                  }
                )
              }, (index + 1) * 2 * 1000)
            })
          } else {
            xhr[action] = jnews.library.ajax(
              'POST',
              ajaxurl,
              {
                data: param,
                action: action,
              },
              function (result) {
                // Validate Paypal Account
                if ('validate_paypal_account' === action) {
                  if ('true' === result) {
                    changePaypalValidIcon('fa-check-circle')
                    formYourProfile.removeAttribute('onsubmit')
                  } else {
                    changePaypalValidIcon('fa-times-circle', false)
                    formYourProfile.setAttribute('onsubmit', false)
                  }
                }
                delete xhr[action]
              }
            )
          }
          return true
        }
      },
      // DateRangePicker
      dateRange = null,
      dateRangeCustomWrapper = null,
      dateRangeCustom = null,
      toggleCustomRangeWrapper = function (value) {
        if ('custom' === value) {
          dateRangeCustomWrapper.style.display = 'inline-block'
        } else {
          dateRangeCustomWrapper.style.display = 'none'
        }
        //Tweaks the datepicker fields dates when a choice is made from select menu
        if (value == 'this_month') {
          dateRangeCustom.setDates(jpwt_stats_vars.time_start_this_month, jpwt_stats_vars.time_end_this_month)
        }
        if (value == 'last_month') {
          dateRangeCustom.setDates(jpwt_stats_vars.time_start_last_month, jpwt_stats_vars.time_end_last_month)
        }
        if (value == 'this_week') {
          dateRangeCustom.setDates(jpwt_stats_vars.time_start_this_week, jpwt_stats_vars.time_end_this_week)
        }
        if (value == 'this_year') {
          dateRangeCustom.setDates(jpwt_stats_vars.time_start_this_year, jpwt_stats_vars.time_end_this_year)
        }
        if (value == 'all_time') {
          dateRangeCustom.setDates(jpwt_stats_vars.datepicker_mindate, jpwt_stats_vars.datepicker_maxdate)
        }
      },
      initDatePicker = function () {
        if ('function' === typeof DateRangePicker && window.DateRangePicker) {
          var customRangeWrapper = container.querySelector('#jpwt_stats_header_datepicker')
          if (null !== customRangeWrapper) {
            dateRange = customRangeWrapper.querySelector('#jpwt-time-range')
            dateRangeCustomWrapper = customRangeWrapper.querySelector('#jpwt-time-range-custom')
            if (null !== dateRangeCustomWrapper) {
              dateRangeCustom = new DateRangePicker(dateRangeCustomWrapper, {
                format: 'yyyy-mm-dd',
                minDate: jpwt_stats_vars.datepicker_mindate,
                maxDate: jpwt_stats_vars.datepicker_maxdate,
              })
            }
          }
        }
      },
      // Paypal Account Validate
      paypalAccountEvent = {},
      formYourProfile = null,
      paypalValidIcon = null,
      paypalAccountText = null,
      changePaypalValidIcon = function (icon) {
        var currentClass = paypalValidIcon.className,
          loading = ''
        if ('fa-spinner' === icon) {
          loading = 'fa-spin'
        }
        if (currentClass) {
          currentClass = currentClass.split(' ')
          jnews.library.forEach(currentClass, function (item) {
            jnews.library.removeClass(paypalValidIcon, item)
          })
          if ('' === loading) {
            jnews.library.addClass(paypalValidIcon, 'fa')
            jnews.library.addClass(paypalValidIcon, icon)
          } else {
            jnews.library.addClass(paypalValidIcon, 'fa')
            jnews.library.addClass(paypalValidIcon, icon)
            jnews.library.addClass(paypalValidIcon, loading)
          }
        } else {
          if ('' === loading) {
            jnews.library.addClass(paypalValidIcon, 'fa')
            jnews.library.addClass(paypalValidIcon, icon)
          } else {
            jnews.library.addClass(paypalValidIcon, 'fa')
            jnews.library.addClass(paypalValidIcon, icon)
            jnews.library.addClass(paypalValidIcon, loading)
          }
        }
      },
      bulkPayoutAccordion = function () {
        //addEventListener on mouse click
        payoutAccordionBtn = container.getElementsByClassName('jpwt-accordion-btn')
        // Payout Accordion Button
        if (payoutAccordionBtn.length) {
          jnews.library.forEach(payoutAccordionBtn, function ($payoutAccordionBtn) {
            jnews.library.addEvents($payoutAccordionBtn, {
              click: function (e) {
                payoutAccordionEvent(e)
              },
            })
          })
        }
      },
      initPaypalAccountValidate = function () {
        formYourProfile = container.querySelector('#your-profile')
        if (null !== formYourProfile) {
          paypalAccountText = formYourProfile.querySelector('input[name="paypal_account"]')
          if (null !== paypalAccountText) {
            paypalValidIcon = jnews.library.doc.createElement('i')
            paypalValidIcon.id = 'paypal-valid'
            paypalAccountText.parentNode.insertBefore(paypalValidIcon, paypalAccountText.nextSibling)
            paypalAccountEvent = {
              input: function () {
                var val = paypalAccountText.value.trim()
                val = val.replace(/\s+/g, '')

                if (val.length > 0) {
                  formYourProfile.setAttribute('onsubmit', false)
                  changePaypalValidIcon('fa-spinner')
                  clearTimeout(delayTimer)
                  delayTimer = setTimeout(function () {
                    if (typeof xhr === 'object') {
                      jnews.library.objKeys(xhr).forEach(function (key) {
                        xhr[key].xhr.abort()
                        delete xhr[key]
                      })
                    }
                    ajaxRequest('validate_paypal_account', val)
                  }, 500)
                }
              },
            }
          }
          jnews.library.addEvents(formYourProfile, {
            submit: function (event) {
              if( formYourProfile.getAttribute('onsubmit') === 'false' ) {
                event.preventDefault();
                return false;
              }
            },
          })
        }
      },
      // Payout data
      paypalData = null,
      paypalDataStart = null,
      paypalDataEnd = null,
      paypalResetData = function () {
        paypalData = null
        paypalDataStart = null
        paypalDataEnd = null
      },
      // Paypal Payout
      paypalBulkPayoutForm = null,
      paypalBulkPayoutBtn = {},
      paypalBulkPayoutBtnEvent = {
        click: function (e) {
          e.preventDefault()
        },
      },
      payoutType = 'paypal',
      paypalPayoutBtn = {},
      manualPayoutBtn = {},
      paypalPayoutPopupBtn = {},
      paypalPayoutOverlayWrapper = {},
      paypalPayoutOverlay = {},
      paypalPayoutMainOverlay = {},
      paypalPayoutLoadingOverlay = {},
      paypalPayoutCloseBtn = [],
      payoutAccordionBtn = {},
      paypalPayoutEvent = function (btn) {
        payoutType = btn.getAttribute('class') === 'manual-payout' ? 'manual' : 'paypal'

        if (paypalPayoutOverlayWrapper.length) {
          paypalData = btn.getAttribute('data-paypal')

          if (null !== dateRangeCustomWrapper) {
            var dateStart = dateRangeCustomWrapper.querySelector('#jnews_pay_writer_time_start')
            var dateEnd = dateRangeCustomWrapper.querySelector('#jnews_pay_writer_time_end')
            if (null !== dateStart) paypalDataStart = dateStart.value
            if (null !== dateEnd) paypalDataEnd = dateEnd.value
          }

          jnews.library.forEach(paypalPayoutOverlayWrapper, function ($paypalPayoutOverlayWrapper, i) {
            if (paypalPayoutMainOverlay.length) {
              jnews.library.forEach(paypalPayoutMainOverlay, function ($paypalPayoutMainOverlay) {
                jnews.library.addClass(jnews.library.globalBody, 'jpwt-payout-open')
                jnews.library.addClass($paypalPayoutOverlayWrapper, 'active')
                jnews.library.addClass($paypalPayoutMainOverlay, 'active')

                var recipient = $paypalPayoutMainOverlay.querySelector('#recipient'),
                  paypalAccount = $paypalPayoutMainOverlay.querySelector('#paypal-account'),
                  totalPayout = $paypalPayoutMainOverlay.querySelector('#total-payout'),
                  paypalDataParse = JSON.parse(paypalData)
                if (null !== recipient) recipient.innerHTML = paypalDataParse.name
                if (null !== paypalAccount) paypalAccount.innerHTML = paypalDataParse.address
                if (null !== totalPayout) totalPayout.innerHTML = paypalDataParse.total_parse
              })
            }
          })
        }
      },
      paypalPayoutPopup = function () {
        paypalPayoutBtn = container.getElementsByClassName('paypal-payout')
        manualPayoutBtn = container.getElementsByClassName('manual-payout')
        paypalPayoutPopupBtn = container.getElementsByClassName('jpwt-payout-btn')
        paypalPayoutCloseBtn = [container.querySelectorAll('.jpwt-payout-overlay .close'), container.querySelectorAll('.jpwt-payout-overlay .jpwt-payout-cancel')]
        paypalPayoutOverlayWrapper = container.getElementsByClassName('jpwt-payout-overlay-wrapper')
        if (paypalPayoutOverlayWrapper.length) {
          jnews.library.forEach(paypalPayoutOverlayWrapper, function ($paypalPayoutOverlayWrapper, i) {
            paypalPayoutOverlay = $paypalPayoutOverlayWrapper.querySelectorAll('.jpwt-payout-overlay')
            paypalPayoutLoadingOverlay = $paypalPayoutOverlayWrapper.querySelectorAll('.jpwt-payout-overlay.loading')
            paypalPayoutMainOverlay = $paypalPayoutOverlayWrapper.querySelectorAll('.jpwt-payout-overlay.main')
          })
        }
      },
      paypalPayoutPopupEvent = function (result) {
        result = JSON.parse(result)
        if (paypalPayoutOverlay.length) {
          jnews.library.forEach(paypalPayoutOverlayWrapper, function ($paypalPayoutOverlayWrapper) {
            jnews.library.addClass($paypalPayoutOverlayWrapper, 'result')
          })
          jnews.library.forEach(paypalPayoutOverlay, function ($paypalPayoutOverlay) {
            if (jnews.library.hasClass($paypalPayoutOverlay, 'loading')) {
              jnews.library.removeClass($paypalPayoutOverlay, 'active')
            }
            if (jnews.library.hasClass($paypalPayoutOverlay, 'result')) {
              jnews.library.addClass($paypalPayoutOverlay, 'active')
              var resultError = $paypalPayoutOverlay.querySelector('.jpwt-result-error')
              var resultSuccess = $paypalPayoutOverlay.querySelector('.jpwt-result-success')
              if (typeof result.error !== 'undefined' && result.error) {
                if (null !== resultError) {
                  var overlayHeader = $paypalPayoutOverlay.querySelector('.jpwt-payout-overlay-header')
                  jnews.library.addClass(overlayHeader, 'header-error')
                  jnews.library.addClass(resultError, 'active')
                  jnews.library.removeClass(resultSuccess, 'active')
                  var payoutErrorMessage = resultError.querySelector('#payout-message')
                  if (null !== payoutErrorMessage) {
                    payoutErrorMessage.innerHTML = typeof result.response.message === 'object' ? result.response.message.message : result.response.message
                  }
                }
              } else {
                if (null !== resultSuccess) {
                  var singlePayoutResult = resultSuccess.querySelector('#jpwt-single-payout-result')
                  var bulkPayoutResult = resultSuccess.querySelector('#jpwt-bulk-payout-result')
                  if (null !== singlePayoutResult) {
                    var payoutResultContent = singlePayoutResult.querySelector('#payout-result-content')
                    if (null !== payoutResultContent) {
                      jnews.library.addClass(resultSuccess, 'active')
                      jnews.library.removeClass(resultError, 'active')
                      jnews.library.forEach(result.payout_list, function (item, index) {
                        if (result.payout_list.length > 1) {
                          payoutResultContent = singlePayoutResult.querySelector('#payout-result-content').cloneNode(true)
                          payoutResultContent.id = ''
                          jnews.library.addClass(payoutResultContent, 'payout-result-content')
                          item.transaction_status = item.transaction_status.toLowerCase()
                          jnews.library.addClass(payoutResultContent, item.transaction_status)
                          var listPayoutDetail = jnews.library.doc.createElement('div')
                          listPayoutDetail.id = 'jpwt-payout-detail-' + index
                          jnews.library.addClass(listPayoutDetail, 'jpwt-payout-detail')
                          listPayoutDetail.appendChild(payoutResultContent)
                          var listPayoutDetailContent = listPayoutDetail.querySelector('.payout-result-content')
                          if (null !== listPayoutDetailContent) {
                            for (var prop in item) {
                              var payoutContent = payoutResultContent.querySelector('.payout-' + prop)
                              if (payoutContent !== null) {
                                payoutContent.innerHTML = item[prop]
                                if ('undefined' !== typeof item['item_error'] && '' !== item['item_error']) {
                                  jnews.library.addClass(payoutContent.closest('li'), 'active')
                                }
                              }
                            }
                            bulkPayoutResult.appendChild(listPayoutDetail)
                          }
                        } else {
                          for (var prop in item) {
                            item.transaction_status = item.transaction_status.toLowerCase()
                            jnews.library.addClass(payoutResultContent, item.transaction_status)
                            var payoutContent = singlePayoutResult.querySelector('.payout-' + prop)
                            if (payoutContent !== null) {
                              payoutContent.innerHTML = item[prop]
                              if ('undefined' !== typeof item['item_error'] && '' !== item['item_error']) {
                                jnews.library.addClass(payoutContent.closest('li'), 'active')
                              }
                            }
                          }
                        }
                      })
                      if (result.payout_list.length > 1) {
                        jnews.library.addClass(bulkPayoutResult, 'active')
                        jnews.library.removeClass(singlePayoutResult, 'active')
                      } else {
                        jnews.library.addClass(singlePayoutResult, 'active')
                        jnews.library.removeClass(bulkPayoutResult, 'active')
                      }
                    }
                  }
                }
              }
            }
          })
        }
      },
      payoutAccordionEvent = function (e) {
        //check if element contains active class
        if (!e.currentTarget.parentElement.classList.contains('active')) {
          //add active class on cliked accordion
          e.currentTarget.parentElement.classList.add('active')
        } else {
          //remove active class on cliked accordion
          e.currentTarget.parentElement.classList.remove('active')
        }
      },
      paypalBulkPayout = function () {
        paypalBulkPayoutForm = container.querySelector('#jpwt-list-author')
        if (null !== paypalBulkPayoutForm) {
          paypalBulkPayoutBtn = paypalBulkPayoutForm.querySelectorAll('input[type="submit"],input[type="text"],.prev-page.button,.next-page.button')
        }
      },
      bindEvent = function () {
        // DateRangePicker
        if (null !== dateRange) {
          jnews.library.addEvents(dateRange, {
            change: function (e) {
              toggleCustomRangeWrapper(e.target.value)
            },
          })
          jnews.library.triggerEvents(dateRange, 'change')
        }

        // Paypal Payout Button
        if (paypalPayoutBtn.length) {
          jnews.library.forEach(paypalPayoutBtn, function ($paypalPayoutBtn) {
            jnews.library.addEvents($paypalPayoutBtn, {
              click: function () {
                paypalPayoutEvent($paypalPayoutBtn)
              },
            })
          })
        }

        // Manual Payout Button
        if (manualPayoutBtn.length) {
          jnews.library.forEach(manualPayoutBtn, function ($paypalPayoutBtn) {
            jnews.library.addEvents($paypalPayoutBtn, {
              click: function () {
                paypalPayoutEvent($paypalPayoutBtn)
              },
            })
          })
        }

        // Paypal Popup Payout Button
        if (paypalPayoutPopupBtn.length) {
          jnews.library.forEach(paypalPayoutPopupBtn, function ($paypalPayoutPopupBtn) {
            if (jnews.library.hasClass($paypalPayoutPopupBtn, 'jpwt-payout-pay')) {
              jnews.library.addEvents($paypalPayoutPopupBtn, {
                click: function (e) {
                  e.preventDefault()
                  if (null !== paypalData) {
                    if (paypalPayoutLoadingOverlay.length) {
                      jnews.library.forEach(paypalPayoutLoadingOverlay, function ($paypalPayoutLoadingOverlay) {
                        jnews.library.addClass($paypalPayoutLoadingOverlay, 'active')
                      })
                    }
                    var params = {
                      data: [JSON.parse(paypalData)],
                      tstart: paypalDataStart,
                      tend: paypalDataEnd,
                      payout_type: payoutType,
                    }
                    if (null !== paypalDataStart) params.tstart = paypalDataStart
                    if (null !== paypalDataEnd) params.tend = paypalDataEnd
                    ajaxRequest('create_bulk_payout', params)
                  }
                },
              })
            }
          })
        }

        // Paypal Popup Close Button
        if (paypalPayoutCloseBtn.length) {
          jnews.library.forEach(paypalPayoutCloseBtn, function (paypalPopupCloseButton) {
            jnews.library.forEach(paypalPopupCloseButton, function ($paypalPopupCloseButton) {
              jnews.library.addEvents($paypalPopupCloseButton, {
                click: function (e) {
                  var doRefresh = false
                  if (paypalPayoutOverlayWrapper.length) {
                    jnews.library.forEach(paypalPayoutOverlayWrapper, function ($paypalPayoutOverlayWrapper) {
                      doRefresh = jnews.library.hasClass($paypalPayoutOverlayWrapper, 'result') ? true : false
                      var paypalPayoutOverlay = $paypalPayoutOverlayWrapper.getElementsByClassName('jpwt-payout-overlay')
                      if (paypalPayoutOverlay.length) {
                        jnews.library.forEach(paypalPayoutOverlay, function ($paypalPayoutOverlay) {
                          jnews.library.removeClass($paypalPayoutOverlay, 'active')
                          jnews.library.removeClass($paypalPayoutOverlayWrapper, 'active')
                          jnews.library.removeClass(jnews.library.globalBody, 'jpwt-payout-open')
                        })
                      }
                    })
                  }
                  if (doRefresh) {
                    window.location.reload()
                  }
                  paypalResetData()
                },
              })
            })
          })
        }

        // Bulk Payout
        if (null !== paypalBulkPayoutForm) {
          jnews.library.addEvents(paypalBulkPayoutForm, {
            submit: function (e) {
              if (e.submitter.getAttribute('name') !== 'jnews_pay_writer_submit') {
                e.preventDefault()
                if (paypalPayoutOverlayWrapper.length) {
                  jnews.library.forEach(paypalPayoutOverlayWrapper, function ($paypalPayoutOverlayWrapper, i) {
                    jnews.library.addClass(jnews.library.globalBody, 'jpwt-payout-open')
                    jnews.library.addClass($paypalPayoutOverlayWrapper, 'active')
                    jnews.library.forEach(paypalPayoutOverlay, function ($paypalPayoutOverlay) {
                      if (jnews.library.hasClass($paypalPayoutOverlay, 'loading')) {
                        jnews.library.addClass($paypalPayoutOverlay, 'active')
                      }
                      if (jnews.library.hasClass($paypalPayoutOverlay, 'result')) {
                        jnews.library.addClass($paypalPayoutOverlay, 'active')
                      }
                    })
                  })
                }
                if (paypalBulkPayoutBtn.length) {
                  jnews.library.forEach(paypalBulkPayoutBtn, function ($paypalBulkPayoutBtn) {
                    jnews.library.addClass($paypalBulkPayoutBtn, 'disabled')
                    $paypalBulkPayoutBtn.setAttribute('disabled', true)
                    jnews.library.addEvents($paypalBulkPayoutBtn, paypalBulkPayoutBtnEvent)
                  })
                }
                ajaxRequest('create_bulk_payout', formSerialize(paypalBulkPayoutForm, true))
              }
            },
          })
        }

        //deactivate paypal account valildation because paypal not allow account validation anymore
        // window load & resize
        // jnews.library.addEvents(jnews.library.win, {
        //   load: function () {
        //     initPaypalAccountValidate()
        //     // Toggle submit form your profile
        //     if (null !== paypalAccountText) {
        //       jnews.library.addEvents(paypalAccountText, paypalAccountEvent)
        //     }
        //   },
        // })
      }
    base.init = function () {
      if (isJNewsLibrary) {
        container = jnews.library.globalBody
        initDatePicker()
        paypalPayoutPopup()
        paypalBulkPayout()
        bindEvent()
      }
    }
  }
  window.jnews.payWriter = new window.jnews.payWriter()
  if ('object' === typeof jnews && 'object' === typeof jnews.library) {
    jnews.library.requestAnimationFrame.call(jnews.library.win, function () {
      jnews.library.docReady(function () {
        jnews.payWriter.init()
      })
    })
  }
})(jQuery)
