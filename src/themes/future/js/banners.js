/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function () {
    const ctn = $('#frm');

    const planSelect = ctn.find('#banner_plan');
    const imgBox     = ctn.find('[data-area="banner-upload-box"]');
    const fileAst    = ctn.find('[data-area="required-file"]');
    const urlAst     = ctn.find('[data-area="required-url"]');
    const imgInput   = ctn.find('[data-area="image-input"]');
    const urlInput   = ctn.find('[data-area="url-input"]');

    // --- Khởi tạo giao diện theo plan hiện tại ---
    let isImage = !!planSelect.find(':selected').data('image');

    if (isImage) {
        imgInput.attr({ required: true, 'data-valid': '' });
        urlInput.removeAttr('required data-valid');
    } else {
        urlInput.attr({ required: true, 'data-valid': '' });
        imgInput.removeAttr('required data-valid').val('');
    }

    // --- Cập nhật giao diện theo plan ---
    planSelect.on('change', function () {
        isImage = !!$(this).find(':selected').data('image');

        imgBox.toggleClass('d-none', !isImage);
        fileAst.toggleClass('d-none', !isImage);
        urlAst.toggleClass('d-none', isImage);

        if (isImage) {
            imgInput.attr({ required: true, 'data-valid': '' });
            urlInput.removeAttr('required data-valid').removeClass('is-valid is-invalid');
        } else {
            urlInput.attr({ required: true, 'data-valid': '' });
            imgInput.removeAttr('required data-valid').val('').removeClass('is-valid is-invalid');
        }
    });
});
