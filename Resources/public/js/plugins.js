// Place any jQuery/helper plugins in here.

(function ($) {
    $.fn.ajaxForm = function (options) {
        var settings = $.extend({
            action: "",
            method: "",
            replaceWithData: true,
            animateLoad: true,
            onFinish: null
        }, options);

        try {
            this.submit(function (e) {
                e.preventDefault();

                //get the url for the form
                var that = $(this);

                if ("" == settings.action) {
                    settings.action = that.attr('action');
                }

                if ("" == settings.method) {
                    settings.method = that.attr('method');
                }

                if (true == settings.animateLoad) {
                    that.ajaxAnimateLoad();
                }

                $.ajax({
                    type: settings.method,
                    url: settings.action,
                    data: that.serialize(),
                    success: function (data, textStatus, jqXHR) {
                        asm.log('form::response: ' + textStatus);
                        if (true == settings.replaceWithData) {
                            that.replaceWith(data);
                        }

                        if (typeof settings.onFinish === 'function') {
                            settings.onFinish(self);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        asm.log('form::response: ' + errorThrown);
                        that.replaceWith(jqXHR);
                        that.ajaxForm(settings);
                    }
                });
            });

            return false;
        } catch (e) {
            asm.log(e);
        }
    };
}(jQuery));

/**
 * jQuery ajax loader plugin
 */
(function ($) {
    $.fn.ajaxAnimateLoad = function (options) {
        // Create some defaults, extending them with any options that were provided
        var settings = $.extend({
            loaderImage: '/img/ajax-loader.gif',
            loaderWidth: '32px',
            loaderHeight: '32px',
            fadeDuration: 200,
            action: 'start',
            backgroundDisabled: false
        }, options);

        return this.each(function () {
            var that = $(this),
                ajaxLoader = '<span id="ajaxloader" style="display: block; width: '
                    + settings.loaderWidth + '; height: '
                    + settings.loaderHeight + '; background: transparent url('
                    + settings.loaderImage
                    + ') no-repeat center center; position: absolute; top: 50%; left: 50%;">&nbsp;</span>';

            var backgroundOverlay = '<div class="modalBackgroundOverlay" style="position: fixed; width:100%; ' +
                'height: 100%; top: 0px; left: 0px; zoom: 1; opacity: 0.0; background-color: #FFF; ' +
                'z-index: 201;">&nbsp;</div>';

            if (settings.action == 'start') {
                if (true == settings.backgroundDisabled) {
                    if (that.children('.modalBackgroundOverlay').length == 0) {
                        that.append(backgroundOverlay);
                        jQuery('.modalBackgroundOverlay').animate({opacity: 0.4}, settings.fadeDuration);
                    }
                    if (that.children('#ajaxloader').length == 0) {
                        that.append(ajaxLoader);
                    }
                } else {
                    that.attr('style', 'position: relative;').append(ajaxLoader).animate({opacity: 0.4}, settings.fadeDuration);
                }
            } else if (settings.action == 'stop') {
                if (that.children('.modalBackgroundOverlay').length > 0) {
                    that.children('#ajaxloader').remove();
                    that.children('.modalBackgroundOverlay').animate({opacity: 0.0}, settings.fadeDuration).remove();
                } else {
                    that.attr('style', 'position: static;').remove("#ajaxloader").animate({opacity: 1.0}, settings.fadeDuration);
                }
            }
        });
    };
})(jQuery);

/**
 * ajax load a url into target, using loader animation
 */
(function ($) {
    $.fn.ajaxLoadElm = function (options, callback) {

        // Create some defaults, extending them with any options that were provided
        var settings = $.extend({
                source: '',
                animateLoad: true,
                backgroundDisabled: false
            }, options),
            that = $(this);

        try {
            if (true == settings.animateLoad) {
                that.ajaxAnimateLoad({'backgroundDisabled': settings.backgroundDisabled});
            }

            $.get(settings.source, function (data) {
                that.replaceWith(data);
            });

            if (typeof callback == 'function') { // make sure the callback is a function
                callback.call(this); // brings the scope to the callback
            }

            return false;
        }
        catch (e) {
            asm.log(e);
        }
    };
})(jQuery);

/**
 * mustache auto-renderer
 */
(function ($) {
    $.fn.renderMustache = function (options) {

        var settings = $.extend({
                source: "",
                template: ""
            }, options),
            that = $(this);

        if ("" == settings.source) {
            settings.url = that.data('source');
        }

        if (""  == settings.template) {
            settings.template = that.data('template');
        }

        $.getJSON(settings.source, function(data) {
            if (data.length > 0) {
                that.html(
                    Mustache.render(
                        $(settings.template).html(),
                        data
                    )
                );
                asm.log('mustache::refreshed ' + settings.template);
            } else {
                asm.log('mustache::no elements found for ' + settings.source);
            }
        });
    };
})(jQuery);
