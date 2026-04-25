/**
 * Dashboard Select2: add class `js-select2` to any <select> that should be searchable.
 * Optional: window.SkoolystSelect2.init(container) after injecting HTML.
 */
(function ($) {
    'use strict';

    function init(root) {
        var $scope = root ? $(root) : $(document);
        $scope.find('select.js-select2').each(function () {
            var $el = $(this);
            if ($el.data('select2')) {
                return;
            }
            var $modal = $el.closest('.modal');
            $el.select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $modal.length ? $modal : $(document.body),
            });
        });
    }

    $(function () {
        init(document);
    });

    window.SkoolystSelect2 = { init: init };
})(jQuery);
