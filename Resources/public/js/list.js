;
(function ($) {

    window.asm.list = window.asm.list || {};

    asm.list = {

        baseUrl: asm.utility.getBaseUrl(),

        init: function () {
            asm.info('asm.list init');
            if ($('.asm-edit-btn').length > 0) {
                asm.list.initEditButtons();
            }

            if ($('.asm-delete-btn').length > 0) {
                asm.list.initDeleteButtons();
            }
        },

        initEditButtons: function () {
            $('.asm-edit-btn').click(function (e) {
                var key = $(this).data('key'),
                    locale = $(this).data('locale'),
                    domain = $(this).data('domain'),
                    formUrl = $(this).data('link');

                formUrl = encodeURI(formUrl + '/' + key + '/' + locale  + '/' + domain);

                asm.debug('formUrl: ' + formUrl);

                asm.modal.init({
                    url: formUrl
                });
            });
        },

        initDeleteButtons: function () {
            $('.asm-delete-btn').click(function (e) {
                var confirmed = confirm(asm.translations.confirm_delete);
                if (confirmed == true) {
                    var key = $(this).data('key'),
                        locale = $(this).data('locale'),
                        domain = $(this).data('domain');

                    asm.debug('key: ' + key + ' locale: ' + locale + ' domain: ' + domain);
                    asm.debug('delete confirmed');
                } else {
                    asm.debug('delete cancelled');
                }
            });
        }
    }
})(jQuery);

asm.utility.documentReady(asm.list.init());
