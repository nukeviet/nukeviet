{* Template: user_waiting_remail.tpl
 * Giao diện admin_future cho khu vực Gửi lại email kích hoạt
 * Module: users - NukeViet 5.0
 *}
<div class="card">
    <div class="card-body">
        <form action="#" method="post" id="resend-email-form" novalidate>
            <div class="row g-3 align-items-end">
                <div class="col-auto">
                    <label class="form-label" for="per_email_select">{$LANG->getModule('userwait_resend_per_email')}</label>
                    <select class="form-select" id="per_email_select" name="per_email">
                        <option value="1">1</option>
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label" for="pause_time_select">{$LANG->getModule('userwait_resend_pause_time')}</label>
                    <select class="form-select" id="pause_time_select" name="pause_time">
                        <option value="1">1 {$LANG->getGlobal('sec')}</option>
                        <option value="5">5 {$LANG->getGlobal('sec')}</option>
                        <option value="10">10 {$LANG->getGlobal('sec')}</option>
                        <option value="20">20 {$LANG->getGlobal('sec')}</option>
                        <option value="30" selected>30 {$LANG->getGlobal('sec')}</option>
                        <option value="60">60 {$LANG->getGlobal('sec')}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <span class="load d-none"><i class="fa-solid fa-spinner fa-spin-pulse"></i> </span>
                        {$LANG->getGlobal('submit')}
                    </button>
                </div>
            </div>
            {* Trường ẩn lưu token xác thực *}
            <input type="hidden" name="checkss" id="resend_checkss" value="{$CHECKSS}">
        </form>
    </div>
</div>

{* Khu vực hiển thị tiến trình *}
<p class="d-none mt-3" id="resend-perload"
    data-lang-run="{$LANG->getModule('userwait_resend_run')}"
    data-lang-note="{$LANG->getModule('userwait_resend_note')}"
    data-lang-counter="{$LANG->getModule('userwait_resend_counter')}"
    data-lang-complete="{$LANG->getModule('userwait_resend_complete')}"></p>

{* Khu vực hiển thị kết quả chi tiết *}
<pre class="d-none mt-2 border p-3 rounded bg-body-tertiary" id="resend-result"
    data-lang-start="{$LANG->getModule('userwait_resend_start')}"
    data-lang-end="{$LANG->getModule('userwait_resend_end')}"><code></code></pre>
