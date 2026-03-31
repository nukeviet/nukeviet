/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

/**
 * @param {Object} data
 * @param {JQuery} data
 * @returns {number}
 */
function votingProcessResult(data, form) {
    $('input, textarea, select, button', form).prop('disabled', false);
    modalShow(form.data('result-title'), data.html);
    if (form.data('related-btn')) {
        $(`#${form.data('related-btn')}`).prop('disabled', false);
    }
    return 0;
}

$(function() {
    // Xem kết quả bình chọn
    $('body').on('click', '[data-toggle="votingResult"]', function(e) {
        e.preventDefault();
        const btn = $(this);
        btn.prop('disabled', true);
        setTimeout(() => {
            btn.prop('disabled', false);
        }, 5000);
        const oForm = btn.closest('form');
        const cFormID = `${oForm.attr('id')}-tmp`;
        const existingClone = document.getElementById(cFormID);
        if (existingClone) {
            existingClone.remove();
        }

        // Tạo 1 form mới từ form cũ. Bỏ đi phần bắt buộc chọn đáp án
        const cForm = oForm[0].cloneNode(true);
        cForm.id = cFormID;
        cForm.style.display = 'none';
        cForm.querySelectorAll('[data-valid]').forEach(el => el.removeAttribute('data-valid'));
        cForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        cForm.querySelectorAll('[id]').forEach(el => el.removeAttribute('id'));
        cForm.dataset.relatedBtn = btn.attr('id');

        // Đánh dấu chỉ xem đáp án
        let hIpt = document.createElement('input');
        hIpt.type = 'hidden';
        hIpt.name = 'viewresult';
        hIpt.value = '1';
        cForm.appendChild(hIpt);

        // Submit form mới
        document.body.appendChild(cForm);
        $('[type=submit]', $(`#${cFormID}`)).click();
    });
});
