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

            });
        },

        initDeleteButtons: function () {
            $('.asm-delete-btn').click(function (e) {

            });
        }
    }
})(jQuery);

asm.utility.documentReady(asm.list.init());
