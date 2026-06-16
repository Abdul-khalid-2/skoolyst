/**
 * Website Select2: add class `js-select2` to searchable dropdowns.
 * Add `js-select2-tags` to allow typing a custom value (e.g. city on registration).
 * Call window.SkoolystWebsiteSelect2.init(container) after injecting HTML dynamically.
 */
(function ($) {
    'use strict';

    function placeholderFromSelect($el) {
        var emptyOption = $el.find('option[value=""]').first();
        return emptyOption.length ? emptyOption.text() : 'Select';
    }

    function dropdownParentFor($el) {
        var $wrap = $el.closest('.filter-select-wrap, .form-group');
        return $wrap.length ? $wrap : $(document.body);
    }

    function buildOptions($el) {
        var options = {
            width: '100%',
            placeholder: placeholderFromSelect($el),
            allowClear: $el.find('option[value=""]').length > 0,
            minimumResultsForSearch: 0,
            dropdownParent: dropdownParentFor($el),
        };

        if ($el.hasClass('js-select2-tags')) {
            options.tags = true;
            options.createTag = function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return { id: term, text: term, newTag: true };
            };
        }

        return options;
    }

    function init(root) {
        var $scope = root ? $(root) : $(document);
        $scope.find('select.js-select2').each(function () {
            var $el = $(this);
            if ($el.data('select2')) {
                return;
            }
            $el.select2(buildOptions($el));
        });
    }

    function reset(selectEl, triggerChange) {
        if (!selectEl) {
            return;
        }
        var $el = $(selectEl);
        if ($el.data('select2')) {
            $el.val(null);
            if (triggerChange) {
                $el.trigger('change');
            } else {
                $el.trigger('change.select2');
            }
            return;
        }
        selectEl.value = '';
    }

    function resetAll(container) {
        var root = container || document;
        $(root).find('select.js-select2').each(function () {
            reset(this);
        });
    }

    $(function () {
        init(document);
    });

    window.SkoolystWebsiteSelect2 = {
        init: init,
        reset: reset,
        resetAll: resetAll,
    };
})(jQuery);
