;
(function ($) {

    window.asm.default = window.asm.default || {};

    asm.default = {

        baseUrl: asm.utility.getBaseUrl(),

        init: function () {
            asm.log('asm.default init');
            if ($('.asm-edit-btn').length > 0) {
                asm.default.initEditButtons();
            }

            if ($('.asm-delete-btn').length > 0) {
                asm.default.initDeleteButtons();
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

asm.utility.documentReady(asm.default.init());
