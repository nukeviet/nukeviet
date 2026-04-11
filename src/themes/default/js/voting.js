/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Voting functions
// Gioi han phuong an bau chon
function votingAcceptNumber(obj) {
    var form = $(obj).parents('form');
    if ($('[name*=option]:checked', form).length >= parseInt(form.data('accept'))) {
        $('[name*=option]', form).not(':checked').prop('disabled', true)
    } else {
        $('[name*=option]', form).prop('disabled', false)
    }
}

// Kiểm tra chọn đáp án trước khi submit form
function votingPrecheck(form) {
    let vals = "0";
    let num = parseInt($(form).data('accept'));
    $('[name*=option]:checked', form).each(function() {
        vals = (num == 1) ? $(this).val() : vals + ("," + $(this).val())
    });
    if ("0" === vals) {
        nukeviet.toast($(form).data('errmsg'), 'error');
        return 0;
    }
    return 1;
}

// Voting functions
function votingSend(form) {
    $.ajax({
        type: "POST",
        cache: !1,
        url: $(form).attr("action"),
        data: $(form).serialize(),
        dataType: "json",
        success: function(res) {
            if (res.status !== 'ok') {
                nukeviet.toast(res.mess, 'error');
                return 0;
            }
            if ($(form).data('related-btn')) {
                $(`#${$(form).data('related-btn')}`).prop('disabled', false);
            }
            modalShow($(form).data('result-title'), res.html);
        }
    });
}

$(function() {
    // Voting form submit
    $('body').on('submit', '[data-toggle=votingSend]', function(e) {
        e.preventDefault();
        votingSend(this);
    });

    // Xem kết quả bình chọn
    $('body').on('click', '[data-toggle=votingResult]', function(e) {
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
        cForm.removeAttribute('data-precheck');
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

    // Giới hạn số phương án bình chọn
    $('body').on('click', '[data-toggle=votingAcceptNumber]', function() {
        votingAcceptNumber(this)
    });
});
