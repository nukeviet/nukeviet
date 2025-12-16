/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function afSubmit(form) {
    const $form = $(form);
    var data = new FormData(form);
    $form.find("input,button,select").prop("disabled", true);

    $.ajax({
        type: 'POST',
        url: $form.prop("action"),
        data: data,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(d) {
            if (d.status == "error") {
                alert(d.mess);
                $form.find("input,button,select").prop("disabled", false);
                if (d.input) {
                    const $errInput = $form.find("[name='" + d.input + "']");
                    $errInput.addClass('is-invalid');
                    $errInput.next('.invalid-feedback').text(d.mess);
                    $errInput.focus();
                }
            } else {
                window.location.href = d.redirect;
            }
        },
        error: function() {
            $form.find("input,button,select").prop("disabled", false);
            alert("Có lỗi xảy ra trong quá trình gửi dữ liệu.");
        }
    });
}

$(function() {
    if ($('#banner_plan').length) {
        $('#banner_plan').on('change', function () {
            const typeimage = $('option:selected', this).data('image');
            const $uploadBox = $('#banner_uploadimage');
            const $imageInput = $('#image');
            const $feedback = $imageInput.next('.invalid-feedback');
            const $asterisk = $('.required-file-asterisk');

            if (typeimage) {
                $uploadBox.removeClass('d-none');

                $imageInput
                    .prop('required', true)
                    .attr('data-valid', 'file')
                    .removeAttr('data-allowed-empty');

                $asterisk.removeClass('d-none');
            } else {
                $uploadBox.addClass('d-none');

                $imageInput
                    .prop('required', false)
                    .attr('data-allowed-empty', true)
                    .removeAttr('data-valid')
                    .removeClass('is-invalid is-valid');

                $feedback.text('');
                $asterisk.addClass('d-none');
            }
        });
        $('#banner_plan').trigger('change');
    }

    $('body').on('submit', '[data-toggle="ajax-form"]', function(e) {
        e.preventDefault();
        afSubmit(this);
    });
});
