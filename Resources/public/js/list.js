;
(function ($) {

    window.asm.list = window.asm.list || {};

    asm.list = {

        baseUrl: asm.utility.getBaseUrl(),

        init: function () {
            asm.info('asm.list init');
            if ($('#asm-filter').length > 0) {
                asm.list.initFilterForm();
            }

            if ($('.asm-edit-btn').length > 0) {
                asm.list.initEditButtons();
            }

            if ($('.asm-order').length > 0) {
                asm.list.initOrderButtons();
            }

            if ($('.asm-delete-btn').length > 0) {
                asm.list.initDeleteButtons();
            }

            if ($('.asm-add-btn').length > 0) {
                asm.list.initAddButton();
            }
        },

        initFilterForm: function () {
            asm.debug('fired filter form');

            $('#asm-filter button').click(function (e) {
                e.preventDefault();
                asm.list.reloadList({
                    filter: $('.asm-filter-type').val(),
                    value: $('.asm-filter-value').val()
                });
            });
        },

        initOrderButtons: function () {
            $('.asm-order').click(function (e) {
                asm.list.reloadList({
                    order: $(this).data('key'),
                    type: $(this).data('type')
                });
            });
        },

        initEditButtons: function () {
            $('.asm-edit-btn').click(function (e) {
                var key = $(this).data('key'),
                    locale = $(this).data('locale'),
                    domain = $(this).data('domain'),
                    formUrl = $(this).data('link');

                formUrl = encodeURI(formUrl + '/' + key + '/' + locale  + '/' + domain);

                asm.debug('formUrl', formUrl);

                asm.modal.init({
                    url: formUrl,
                    onClose: function () {
                        asm.list.reloadList();
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
                asm.modal.init({
                    url: formUrl,
                    width: 500,
                    resizable: true,
                    onClose: function () {
                        asm.list.reloadList();
                    },
                    success: function () {
                        $('#asm-translation-form').ajaxForm();
                    }
                });
            });
        },

        initDeleteButtons: function () {
            $('.asm-delete-btn').click(function (e) {
                var confirmed = confirm(asm.translations.confirm_delete);
                if (confirmed == true) {

                    var postData = {
                        key: $(this).data('key'),
                        locale: $(this).data('locale'),
                        domain: $(this).data('domain')
                    };

                    $.ajax({
                        type: 'POST',
                        url: $(this).data('link'),
                        data: postData,
                        success: function (data, textStatus, jqXHR) {
                            asm.log('delete::response', textStatus);
                            asm.list.reloadList();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            asm.log('delete::response', errorThrown);
                        }
                    });
                }
            });
        },

        reloadList: function (options) {
            var settings = $.extend({
                    filter: '',
                    value: '',
                    order: '',
                    type: ''
                },
                options
            );

            asm.debug('fired reload with settings', settings);
            $('#asm-translations-tbl').ajaxLoadElm({
                source: $('#asm-translation-list').data('link'),
                type: 'POST',
                data: settings, //filter data!
                onSuccess: function () {
                    asm.list.initEditButtons();
                    asm.list.initDeleteButtons();
                }
            });
        }
    }
})(jQuery);

asm.utility.documentReady(asm.list.init());
