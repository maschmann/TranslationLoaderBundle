/**
 * console.log wrapper
 */
window.log = function () {
    log.history = log.history || [];   // store logs to an array for reference
    log.history.push(arguments);
    if (console && console.log && asm.options.env == 'dev') {
        console.log(Array.prototype.slice.call(arguments));
    }
}

/** main js object **/
;
(function ($, undefined) {

    window.asm = window.asm || {};

    asm.translations = asm.translations || {
        key: 'value'
    },

    asm.options = asm.options ||
    {
        locale: 'de_DE',
        env: 'prod'
    },

    $.extend(asm.options, {test: false});

    asm.buildOnLoad = {
        init: function () {
            //asm.buildOnLoad.testFunction();
        },

        testFunction: function () {
            // some function stuff
        }
    },

    asm.fire = {
        message: function(msg, obj, color) {
            if(asm.utility.consoleEnabled()) {
                obj = (obj) ? obj : '';
                if ('' != color) {
                    color = 'color: ' + color + ';';
                }

                if(typeof msg !== 'string') {
                    console.log('%c ' + 'data: ', color + 'font-family:Arial, Mono; font-size:13px;', msg);
                } else {
                    console.log('%c ' + msg, color + 'font-family: Arial, Mono; font-size:13px;', obj);
                }
            }
        }
    },

    asm.log = function(msg, obj) {
        msg = (arguments.length === 2) ? msg + ': ' : msg;
        asm.fire.message(msg, obj, '');
    },

    asm.debug = function(msg, obj) {
        asm.fire.message(msg, obj, 'green');
    },

    asm.info = function(msg, obj) {
        asm.fire.message(msg, obj, '#00F');
    },

    asm.warn = function(msg, obj) {
        asm.fire.message(msg, obj, '#FFA500');
    },

    asm.error = function(msg, obj) {
        asm.fire.message(msg, obj, 'Orangered');
    },

    asm.start = function(msg, obj) {
        asm.fire.message(msg, obj, '#0F0');
    },

    asm.end = function(msg, obj) {
        asm.fire.message(msg, obj, '#F00');
    },

    asm.group = function(title) {
        var obj = (obj) ? obj : '';
        if(asm.utility.consoleEnabled()) console.group(title);
    },

    asm.groupEnd = function() {
        var obj = (obj) ? obj : '';
        if(asm.utility.consoleEnabled()) console.groupEnd();
    },

    asm.profile = function(title) {
        if(asm.utility.consoleEnabled()) {
            (title) ? console.profile(title) : console.profile();
        }
    },

    asm.profileEnd = function(title) {
        if(asm.utility.consoleEnabled()) {
            (title) ? console.profileEnd('End Profile: ' + title) : console.profileEnd('End Profile');
        }
    },

    /*
     *  General Helper namespace for asm
     *
     * */
    asm.utility = {

        /*
         * Create a document ready call
         * */
        documentReady: function (call) {
            $(document).ready(function () {
                call;
            })
        },

        isDev: function() {
            return ('dev' == asm.options.env);
        },

        consoleEnabled: function() {
            return (asm.utility.isDev() && window.console);
        },

        /*
         * get The Base Url of the current page
         * options :
         * forceHttps : boolean (returns base url as https)
         *
         * return string
         */
        getBaseUrl: function (options) {
            var settings = $.extend({
                    forceHttps: false
                },
                options
            );

            try {
                var locationProtocol = location.protocol;

                if (true == settings.forceHttps) {
                    locationProtocol = 'https:'
                }
                return locationProtocol + '//' + location.hostname;
            } catch (e) {
                asm.errorHandler.logError(e);
            }
        }
    },

    /*
     * General error handlers
     */
    asm.errorHandler = {
        catchError: function (fn) {
            return function () {
                try {
                    return fn.apply(this, arguments);
                } catch (e) {
                    asm.errorHandler.logError(e);
                }
            }
        },

        logError: function (e) {
            console.log('error: ' + e);
        }
    },

    asm.renderMustache = function(url, container, template) {
        $.getJSON(url, function(elements) {
            if (Object.keys(elements).length > 0) {
                $(container).html(
                    Mustache.render(
                        $(template).html(),
                        elements
                    )
                );
                asm.log('mustache::refreshed ' + template);
            } else {
                asm.log('mustache::no elements found for ' + url);
            }
        });
    },

    /*
     * Pop up or div update function
     *
     * Can do an ajax call and with the response it will create an modal window
     * or updates an locations content
     *
     */
    asm.modal = {

        defaultOptions: {
            url: "",
            method: 'GET',
            success: function () {},
            onLoad: function () {},
            onClose: function () {},
            modal: true,
            selfClose: false,
            width: 680,
            height: 'auto',
            resizable: false,
            modalClass: null,
            closeText: '',
            closeOnEscape: true,
            data: null,
            showClose: true
        },

        leaveOutTimerId: 0,

        init: function (options) {
            if (options && typeof(options) == 'object') {
                asm.modal.options = $.extend({}, asm.modal.defaultOptions, options);
            }
            this._openModal();
        },

        close: function() {
            $(this).dialog('destroy');
        },

        _openModal: function () {
            var that = this,
                content = $('#asm-dialog').children('.content');

            function contentReady(data) {

                var layer = $('#asm-dialog').dialog({
                    modal: that.options.modal,
                    autoOpen: that.options.autoOpen,
                    width: that.options.width,
                    height: that.options.height,
                    resizable: that.options.resizable,
                    closeText: that.options.closeText,
                    closeOnEscape: that.options.closeOnEscape,
                    show: {effect: "fadeIn", duration: 800},
                    //zIndex: 11000,
                    close: function (ev, ui) {
                        $(this).dialog('destroy');
                        $('#asm-dialog').children('.content').empty();
                        if (that.options && typeof that.options.onClose === 'function') {
                            that.options.onClose(self);
                        }
                    }
                });

                content.empty();
                content.append(data);
                layer.dialog('open');

                asm.log('created dialog');

                $('.ui-widget-overlay.ui-front').on('click', function () {
                    layer.dialog('destroy');
                    $('#asm-dialog').children('.content').empty();
                    if (that.options && typeof that.options.onClose === 'function') {
                        that.options.onClose(self);
                    }
                });

                if (that.options.success !== undefined) {
                    that.options.success(data);
                }

                if (that.options && typeof that.options.onLoad === 'function') {
                    that.options.onLoad(self);
                }
            }

            if (typeof that.options.url !== "undefined" && that.options.url != '') {
                $.ajax({
                    url: that.options.url,
                    type: that.options.method
                }).done(function (data) {
                    contentReady(data);
                });
            } else {
                contentReady(that.options.data);
            }
        }
    }
})(jQuery);

asm.buildOnLoad.init();
