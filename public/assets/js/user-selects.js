'use strict';

jQuery(function ($) {
    $('[data-user-select2]').each(function () {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({ width: '100%' });
        }
    });
});
