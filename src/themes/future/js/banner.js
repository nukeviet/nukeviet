/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Add banner
    if ($('#banner_plan').length) {
        $('#banner_plan').change(function() {
            var typeimage = $('option:selected', $(this)).data('image'),
                uploadtype = $('option:selected', $(this)).data('uploadtype'),
                form = $(this).parents('form');
            if (!!typeimage) {
                $('#banner_uploadtype').text(' (' + uploadtype + ')').show();
                $('#banner_uploadimage').show();
                $('.file', form).addClass('required');
                $('.url', form).removeClass('required');
            } else {
                $('#banner_uploadimage').hide();
                $('.file', form).removeClass('required');
                $('.url', form).addClass('required');
            }
        });
        $('#banner_plan').trigger('change')
    }

    $('body').on('submit', '[data-toggle=afSubmit]', function(e) {
        e.preventDefault();
        afSubmit(this)
    });

    $('body').on('keypress', '[data-toggle=errorHidden][data-event=keypress]', function() {
        $(this).parent().removeClass("has-error")
    });

    $('body').on('change', '[data-toggle=errorHidden][data-event=change]', function(e) {
        e.preventDefault();
        $(this).parent().removeClass("has-error")
    });

    $('body').on('change', '[data-toggle=loadStat]', function(e) {
        e.preventDefault();
        loadStat()
    });
});
