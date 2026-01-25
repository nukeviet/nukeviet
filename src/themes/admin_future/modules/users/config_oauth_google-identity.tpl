<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$FORM_ACTION}" novalidate>
            <div class="row mb-3">
                <label for="oauth_client_id" class="col-sm-3 col-form-label text-sm-end">Google Client ID</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="oauth_client_id" name="oauth_client_id" value="{$DATA.oauth_client_id}">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$DATA.checkss}">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
