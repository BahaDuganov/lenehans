define([
    "jquery",
    'MageBig_NewsPopup/js/js.cookie',
    'Magento_Customer/js/customer-data',
    'MageBig_NewsPopup/js/ajax-newsletter',
    'mage/validation',
    "domReady!",
    "magnificpopup"
], function($,Cookies, customerData, ajaxNewsletter){
    "use strict";

    $.widget('custom.mbNewsPopup', {
        options: {
            idPopup: '#magebig_newsletter',
            cookieName: 'magebig_newsletter',
            cookieLifetime: 3600,
            cookieFlag: 'magebig_newsletter_Flag',
            isSuccess: ".messages .message-success",
            notShow: '.not-show-popup input',
            submitButton: ".btn-subscribe",
            form: '#mb-newsletter-form'
        },

        _create: function() {
            var subscribeFlag = Cookies.get(this.options.cookieFlag);
            var cookieName = this.options.cookieName;
            var cookieFlag = this.options.cookieFlag;
            var cookieLifetime = this.options.cookieLifetime;
            var showhome = this.options.showHome;
            var self = this;

            self.hidePopup();

            ajaxNewsletter(self.options, self.options.form, self._subsSetcookie);

            if (!(subscribeFlag && this.options.isSuccess.length) && !Cookies.get(cookieName)) {
                var idPopup = this.options.idPopup;
                setTimeout(function () {
                    if ($.magnificPopup.instance.isOpen) {
                        $.magnificPopup.close();
                    }
                    setTimeout(function () {
                        $.magnificPopup.open({
                            items: {
                                src: idPopup,
                                type: 'inline'
                            },
                            overflowY: 'auto',
                            fixedContentPos: false,
                            removalDelay: 300,
                            mainClass: 'mfp-zoom-in',
                            callbacks: {
                                open: function () {
                                    if (this.fixedContentPos) {
                                        if (this._hasScrollBar(this.wH)) {
                                            var s = this._getScrollbarSize();
                                            if (s) {
                                                $('.sticky-menu.active').css('padding-right', s);
                                                $('#go-top').css('margin-right', s);
                                            }
                                        }
                                    }
                                },
                                close: function () {
                                    $('.sticky-menu.active').css('padding-right', '');
                                    $('#go-top').css('margin-right', '');
                                }
                            }
                        });
                    }, 500);
                }, 4500);
            } else {
                self._subsRemove(cookieFlag, showhome);
                self._subsSetcookie(cookieName, cookieLifetime, showhome);
            }
        },

        hidePopup: function () {
            $(this.options.notShow).on('click', function () {
                var $elm = $(this);
                if ($elm.is(':checked')) {
                    self._subsSetcookie(cookieName, cookieLifetime, showhome);
                } else {
                    self._subsRemove(cookieName, showhome);
                }
            });
        },

        _subsSetcookie: function (cookieName, cookieLifetime, showhome) {
            if (cookieLifetime == 0 && showhome == 1) {
                Cookies.set(cookieName, 'true');
            } else if ((cookieLifetime == 365 || cookieLifetime == 1) && showhome == 0) {
                Cookies.set(cookieName, 'true', {expires: cookieLifetime, path: '/'});
            } else if ((cookieLifetime == 365 || cookieLifetime == 1) && showhome == 1) {
                Cookies.set(cookieName, 'true', {expires: cookieLifetime});
            } else if (cookieLifetime == 0 && showhome == 0) {
                Cookies.set(cookieName, 'true', {path: '/'});
            }
        },

        _subsRemove: function (cookieName, showhome) {
            if (showhome == 1) {
                Cookies.remove(cookieName);
            } else {
                Cookies.remove(cookieName, { path: '/' });
            }
        },

        validate: function () {
            var form = this.options.form;

            return $(form).validation() && $(form).validation('isValid');
        }
    });

    return $.custom.mbNewsPopup;
});
