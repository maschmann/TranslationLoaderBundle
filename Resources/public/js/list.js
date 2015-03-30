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

            if ($('.asm-add-btn').length > 0) {
                asm.list.initAddButton();
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
                    url: formUrl,
                    onClose: function (formUrl) {
                        $('#asm-translation-list').reloadList(formUrl);
                    },
                    success: function () {
                        $('#asm-translation-form').ajaxForm();
                    }
                });
            });
        },

        initAddButton: function () {
            $('.asm-add-btn').click(function (e) {
                var formUrl = $(this).data('link');
                asm.debug('formUrl: ' + formUrl);
                asm.modal.init({
                    url: formUrl,
                    width: 500,
                    resizable: true,
                    onClose: function (formUrl) {
                        asm.list.reloadList(formUrl);
                    }
                });
            });
        },

        initDeleteButtons: function () {
            $('.asm-delete-btn').click(function (e) {
                var confirmed = confirm(asm.translations.confirm_delete);
                if (confirmed == true) {
                    var key = $(this).data('key'),
                        locale = $(this).data('locale'),
                        domain = $(this).data('domain'),
                        link = $(this).data('link');

                    $.ajax({
                        url: link + '/' + ,
                        type: that.options.method
                    }).done(function (data) {
                        contentReady(data);
                    });
                }
            });
        },

        reloadList: function (baseUrl) {
            asm.debug('fired reload');
            $('#asm-translations-tbl').renderMustache({
                source: baseUrl + '/list', //add filters
                template: '#asm-translations-tbl-tpl'
            });
        }
    }
})(jQuery);

asm.utility.documentReady(asm.list.init());
