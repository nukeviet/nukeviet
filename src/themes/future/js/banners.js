/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */


/**
 * Kiểm tra xem một chuỗi có phải là URL hợp lệ.
 *
 * @param {string} url - Chuỗi URL cần kiểm tra (ví dụ: https://example.com)
 * @returns {boolean} - Trả về true nếu URL hợp lệ, ngược lại là false.
 */
function isValidURL(url) {
    var pattern = new RegExp('^(https?:\\/\\/)' +
        '(' +
            // Khớp với Tên miền (Bắt buộc phải có ít nhất một dấu chấm trước TLD, ví dụ: google.com)
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,63})' +
            '|' +
            // Hoặc Địa chỉ IP
            '((\\d{1,3}\\.){3}\\d{1,3})' +
        ')' +
        '(\\:\\d+)?(\\/[-a-z\\d%@_.~+&:]*)*' +
        '(\\?[;&a-z\\d%@_.,~+=-]*)?' +
        '(\\#[-a-z\\d_]*)?$', 'i');
    return !!pattern.test(url);
}

/**
 * Hàm kiểm tra validation tùy chỉnh
 * @param {jQuery} $inputElement - Đối tượng input jQuery (ví dụ: #image, #url)
 * @param {jQuery} $feedbackDiv - Đối tượng invalid-feedback jQuery
 * @param {string} type - Loại trường ('url', 'file', hoặc 'text')
 */
function validateInput($inputElement, $feedbackDiv, type) {
    const $this = $inputElement;
    let value, valueLength;

    if (type === 'file') {
        valueLength = $this[0].files.length;
    } else {
        value = $this.val();
        valueLength = value.length;
    }

    const isRequired = $this.prop('required');
    const isEmpty = (valueLength === 0);

    const minLength = parseInt($this.data('minlength'));
    const maxLength = parseInt($this.data('maxlength'));
    let errorMessage = '';

    if (isEmpty) {
        if (isRequired) {
            errorMessage = $this.data('mess');
        }
    } else {
        if (type === 'url' && !isValidURL(value)) {
            errorMessage = $this.data('mess-url');
        } else if (valueLength > maxLength) {
            errorMessage = $this.data('mess-max');
        } else if (valueLength < minLength) {
            errorMessage = $this.data('mess-min');
        }
    }

    $this.removeClass('is-invalid is-valid');
    $this.parent().removeClass("has-error");
    $feedbackDiv.text('');

    if (errorMessage) {
        $this.addClass('is-invalid');
        $feedbackDiv.text(errorMessage);
        return false;
    } else {
        if (!isEmpty) {
            $this.addClass('is-valid');
        }
        return true;
    }
}

$(function() {
    const $form = $('#frm');
    const $titleInput = $('#title');
    const $titleFeedbackDiv = $titleInput.next('.invalid-feedback');

    const $urlInput = $('#url');
    const $urlFeedbackDiv = $urlInput.next('.invalid-feedback');

    const $imageInput = $form.find('#image');
    const $imageFeedbackDiv = $imageInput.next('.invalid-feedback');

    $titleInput.on('input blur', function() {
        validateInput($(this), $titleFeedbackDiv, 'text');
    });

    $urlInput.on('input blur', function() {
        validateInput($(this), $urlFeedbackDiv, 'url');
    });
    $imageInput.on('change blur', function() {
        validateInput($(this), $imageFeedbackDiv, 'file');
    });

    if ($('#banner_plan').length) {
        $('#banner_plan').change(function() {
            var typeimage = $('option:selected', $(this)).data('image'),
                uploadtype = $('option:selected', $(this)).data('uploadtype');

            const $fileInputs = $imageInput;

            var $fileAsterisk = $form.find('.required-file-asterisk');

            if (!!typeimage) {
                var typeimage = $('option:selected', $(this)).data('image');

                $('#banner_uploadtype').text(' (' + uploadtype + ')').show();
                $('#banner_uploadimage').show();

                $fileInputs.prop('required', true);

                $fileAsterisk.show();
            } else {
                $('#banner_uploadimage').hide();

                $fileInputs.prop('required', false);

                $fileAsterisk.hide();
            }
        });
        $('#banner_plan').trigger('change');
    }
});
