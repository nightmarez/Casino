(function ($) {
    /*
     * func - function (textFieldValue, function (keyValuesResult))
     * callback - function (keyResult)
     *
     */
    $.fn.searchPlugin = function (func, callback) {
        var input = this;

        this.focus(function () {
            $(this).val('');
            $('#searchPluginList').remove();
        });

        this.focusout(function () {
            $('#searchPluginList').remove();
        });

        this.keypress(function (e) {
            if (e.keyCode == 13 /* enter */) {
                e.preventDefault();
                return false;
            }
        });

        this.keyup(function (e) {
            switch (e.which) {
                case 38 /* up key */:
                    var ul = $('#searchPluginList');

                    if (!ul.length) {
                        return;
                    }

                    var list = ul.find('li');
                    var found = false;

                    for (var j = list.length - 1; j >= 0; --j) {
                        if ($(list[j]).hasClass('searchPluginListSelected')) {
                            if (j > 0) {
                                $(list[j]).removeClass('searchPluginListSelected');
                                $(list[j - 1]).addClass('searchPluginListSelected');
                            }

                            found = true;
                            break;
                        }
                    }

                    if (!found && list.length > 0) {
                        $(list[list.length - 1]).addClass('searchPluginListSelected');
                    }

                    break;

                case 40 /* down key */:
                    var ul = $('#searchPluginList');

                    if (!ul.length) {
                        return;
                    }

                    var list = ul.find('li');
                    var found = false;

                    for (var j = 0; j < list.length; ++j) {
                        if ($(list[j]).hasClass('searchPluginListSelected')) {
                            if (j < list.length - 1) {
                                $(list[j]).removeClass('searchPluginListSelected');
                                $(list[j + 1]).addClass('searchPluginListSelected');
                            }

                            found = true;
                            break;
                        }
                    }

                    if (!found && list.length > 0) {
                        $(list[0]).addClass('searchPluginListSelected');
                    }

                    break;

                case 13 /* enter */:
                    var input = $(this);
                    var ul = $('#searchPluginList');

                    if (!ul.length) {
                        e.preventDefault();
                        return false;
                    }

                    var list = ul.find('li');

                    for (var j = 0; j < list.length; ++j) {
                        if ($(list[j]).hasClass('searchPluginListSelected')) {
                            input.val($(list[j]).text());
                            var key = $(list[j]).data('item-id');
                            input.data('item-id', key);

                            if (callback) {
                                callback(key);
                            }

                            ul.remove();
                            break;
                        }
                    }
                    
                    break;

                default:
                    var input = $(this);
                    var ul = $('#searchPluginList');

                    if (!ul.length) {
                        ul = $(document.createElement('ul'))
                            .css('position', 'absolute')
                            .css('list-style', 'none')
                            .css('background-color', 'white')
                            .css('margin', '0')
                            .css('padding', '0')
                            .attr('id', 'searchPluginList');
                        $('body').append(ul);
                    }

                    ul.css('top', input.offset().top + input.outerHeight() + 'px')
                        .css('left', input.offset().left - 1 + 'px')
                        .css('width', input.outerWidth() + 'px');

                    ul.empty();

                    func(input.val(), function (result) {
                        if (_.size(result)) {
                            _.each(result, function (value, key) {
                                var li = $(document.createElement('li'))
                                    .css('margin', '0')
                                    .css('padding', '8px')
                                    .css('padding-left', '12px')
                                    .css('font-family', 'sans-serif')
                                    .css('font-size', '14px')
                                    .text(value);
                                li.data('item-id', key);
                                ul.append(li);

                                (function (ul, key, input) {
                                    li.mousedown(function () {
                                        input.val($(this).text().trim());
                                        var key = $(this).data('item-id');
                                        input.data('item-id', key);
                                        ul.remove();

                                        if (callback) {
                                            callback(key);
                                        }
                                    });
                                })(ul, key, input);
                            });
                        } else {
                            ul.remove();
                        }
                    });

                    break;
            }

            e.preventDefault();
            return false;
        });

        return this;
    };
})(jQuery);