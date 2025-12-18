/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if ($('#banner_plan').length) {
        $('#banner_plan').on('change', function () {
            const typeimage = $('option:selected', this).data('image');
            const $uploadBox = $('#banner_uploadimage');
            const $imageInput = $('#image');
            const $urlInput = $('#url');
            const $asterisk = $('.required-file-asterisk');
            const $asteriskUrl = $('.required-url-asterisk');

            if (typeimage) {
                $uploadBox.removeClass('d-none');
                $imageInput
                    .prop('required', true)
                    .attr('data-valid', 'file');
                $asterisk.removeClass('d-none');

                $urlInput
                    .prop('required', false)
                    .removeAttr('data-valid')
                    .removeClass('is-invalid is-valid');
                $asteriskUrl.addClass('d-none');
            } else {
                $uploadBox.addClass('d-none');
                $imageInput
                    .prop('required', false)
                    .removeAttr('data-valid')
                    .removeClass('is-invalid is-valid');
                $asterisk.addClass('d-none');

                $urlInput
                    .prop('required', true)
                    .attr('data-valid', 'text')
                    .removeClass('is-invalid is-valid');
                $asteriskUrl.removeClass('d-none');
            }
        });
        $('#banner_plan').trigger('change');
    }
});
