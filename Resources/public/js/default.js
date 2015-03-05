;
(function ($) {

    window.asm.default = window.asm.default || {};

    asm.default = {

        baseUrl: asm.utility.getBaseUrl(),

        init: function () {
            asm.info('asm.default init');
        }
    }
})(jQuery);

asm.utility.documentReady(asm.default.init());
