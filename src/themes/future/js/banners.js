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
    const $ctn = $('#frm');
    if (!$ctn.length) return;

    $ctn.on('change', '#banner_plan', function () {
        const isImage = !!$(this).find('option:selected').data('image');

        const $imgBox   = $ctn.find('[data-area="banner-upload-box"]');
        const $fileAst  = $ctn.find('[data-area="required-file"]');
        const $urlAst   = $ctn.find('[data-area="required-url"]');
        const $imgInput = $ctn.find('[data-area="image-input"]');
        const $urlInput = $ctn.find('[data-area="url-input"]');

        /* Hiển thị */
        $imgBox.toggleClass('d-none', !isImage);
        $fileAst.toggleClass('d-none', !isImage);
        $urlAst.toggleClass('d-none', isImage);

        /* Validate */
        $imgInput.prop('required', isImage).prop('disabled', !isImage).removeClass('is-valid is-invalid');
        $urlInput.prop('required', !isImage).prop('disabled', isImage).removeClass('is-valid is-invalid');
    });
    $ctn.find('#banner_plan').trigger('change');
});
