(function ($) {
    'use strict';
    $(function () {
        $('a[aria-label="Deactivate Password Protect WordPress Pro"]').click(function () {
            if (!confirm('All your content protected by Password Protect WordPress Pro will become public once you deactivate the plugin. Are you sure you want to deactivate it?')) {
                return false;
            }
        });
    });
})(jQuery);
