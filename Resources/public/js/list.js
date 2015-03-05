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
                asm.debug('edit button clicked');
            });
        },

        initDeleteButtons: function () {
            $('.asm-delete-btn').click(function (e) {
                var confirmed = confirm(asm.translations.confirm_delete);
                if (confirmed == true) {
                    asm.debug('delete confirmed');
                } else {
                    asm.debug('delete cancelled');
                }
            });
        }
    }
})(jQuery);

asm.utility.documentReady(asm.list.init());
