/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function formXSSsanitize(form) {
    $(form).find("input, textarea").not(":submit, :reset, :image, :file, :disabled").not('[data-sanitize-ignore]').each(function() {
        $(this).val(DOMPurify.sanitize($(this).val(), {}))
    })
}

function btnClickSubmit(event, form) {
    event.preventDefault();
    if (XSSsanitize) {
        formXSSsanitize(form)
    }

    $(form).submit()
}

$(function() {
    $('body').on('click', '[type=submit]:not([name])', function(e) {
        var form = $(this).parents('form');
        if (!$('[name=submit]', form).length) {
            btnClickSubmit(e,form)
        }
    });
})
