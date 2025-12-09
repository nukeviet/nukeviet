/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function isValidURL(url) {
    // Đã loại bỏ dấu ? sau khối giao thức (https?:\/\/)
    var pattern = new RegExp('^(https?:\\/\\/)' +
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)*[a-z]{2,}|' +
        '((\\d{1,3}\\.){3}\\d{1,3}))' +
        '(\\:\\d+)?(\\/[-a-z\\d%@_.~+&:]*)*' +
        '(\\?[;&a-z\\d%@_.,~+=-]*)?' +
        '(\\#[-a-z\\d_]*)?$', 'i');
    return !!pattern.test(url);
}

function validateInput($inputElement, $feedbackDiv, type) {
    const $this = $inputElement;
    const value = $this.val();
    const valueLength = value.length;
    const minLength = parseInt($this.attr('minlength'));
    const maxLength = parseInt($this.attr('maxlength'));
    let errorMessage = '';

    if (valueLength === 0) {
        errorMessage = $this.data('mess');
    } else if (valueLength > 0) {
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

    if (errorMessage) {
        $this.addClass('is-invalid');
        $feedbackDiv.text(errorMessage);
    } else {
        if (valueLength > 0) {
            $this.addClass('is-valid');
        }
        $feedbackDiv.text($this.data('mess'));
    }
}

$(function() {
    const $titleInput = $('#title');
    const $urlInput = $('#url');
    const $titleFeedbackDiv = $titleInput.next('.invalid-feedback');
    const $urlFeedbackDiv = $urlInput.next('.invalid-feedback');

    $titleInput.on('input blur', function() {
        validateInput($(this), $titleFeedbackDiv, 'title');
    });

    $urlInput.on('input blur', function() {
        validateInput($(this), $urlFeedbackDiv, 'url');
    });

});
