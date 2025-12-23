/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function() {
    const $ctn = $('#frm');
    if (!$ctn.length) return;

    $ctn.on('change', '#banner_plan', function() {
        const isImage = !!$(this).find('option:selected').data('image');

        // Cache nhanh các phần tử dựa trên data-area
        const $imgArea = $ctn.find('[data-area="banner-upload-box"], [data-area="required-file"]');
        const $urlAst  = $ctn.find('[data-area="required-url"]');
        const $imgInput = $ctn.find('[data-area="image-input"]');
        const $urlInput = $ctn.find('[data-area="url-input"]');

        // Xử lý hiển thị (Toggle class)
        $imgArea.toggleClass('d-none', !isImage);
        $urlAst.toggleClass('d-none', isImage);

        // Xử lý Validation (Gộp logic dùng ternary operator)
        $imgInput.prop('required', isImage).attr('data-valid', isImage ? 'file' : null);
        $urlInput.prop('required', !isImage).attr('data-valid', !isImage ? 'text' : null);

        // Xóa dấu vết validate cũ
        $imgInput.add($urlInput).removeClass('is-invalid is-valid').filter(function() {
            return !$(this).attr('data-valid');
        }).removeAttr('data-valid');

    }).find('#banner_plan').trigger('change');
});
