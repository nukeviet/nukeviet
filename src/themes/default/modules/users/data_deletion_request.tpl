<!-- BEGIN: main -->
<div class="centered" id="user-request-deletion-page">
    <div class="sm-container-box">
        <div class="usr-flex usr-justify-between usr-gap-2 margin-bottom-lg">
            <div>
                <h1>{LANG.delaccount_title}</h1>
                <p>{LANG.delaccount_note}.</p>
            </div>
            <div>
                <a href="{DATA.link_back}" class="btn btn-default"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> {LANG.delaccount_back}</a>
            </div>
        </div>
        <div class="box-security box-shadow-lg">
            <div class="alert alert-danger">
                <strong>{LANG.delaccount_warn1}</strong><br>
                {LANG.delaccount_warn2}
            </div>
            <h2 class="margin-bottom">{LANG.delaccount_explain1}</h2>
            <p>{LANG.delaccount_explain2}.</p>
            <p><strong>{LANG.delaccount_explain3}:</strong></p>
            <p>{LANG.delaccount_explain4}.</p>
            <p><strong>{LANG.delaccount_explain5}:</strong></p>
            <p>{LANG.delaccount_explain6}:</p>
            <ul class="list-default">
                <li>{LANG.delaccount_explain7}</li>
                <li>{LANG.delaccount_explain8}</li>
                <li>{LANG.delaccount_explain9}</li>
            </ul>
            <hr>
            <div class="usr-flex usr-gap-1 margin-bottom-lg">
                <input type="checkbox" id="confirm_deletion" name="confirm_deletion" value="1">
                <div>
                    <label for="confirm_deletion" class="margin-bottom-sm">{LANG.delaccount_confirm1}</label>
                    <div><small>{LANG.delaccount_confirm2}</small></div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-danger">{LANG.delaccount_confirm3}</button>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
