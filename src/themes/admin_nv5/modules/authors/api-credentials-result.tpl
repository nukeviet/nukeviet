<script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/clipboard/clipboard.min.js"></script>
<div class="credential-result">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header">
            {$LANG->get('api_cr_result')}
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="credential_ident"><strong>{$LANG->get('api_cr_credential_ident')}:</strong></label>
                <div class="input-group">
                    <input type="text" name="credential_ident" id="credential_ident" value="{$CREDENTIAL_IDENT}" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" data-clipboard-target="#credential_ident" id="credential_ident_btn" data-title="{$LANG->get('value_copied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="far fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="credential_secret"><strong>{$LANG->get('api_cr_credential_secret')}:</strong></label>
                <div class="input-group">
                    <input type="text" name="credential_secret" id="credential_secret" value="{$CREDENTIAL_SECRET}" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" data-clipboard-target="#credential_secret" id="credential_secret_btn" data-title="{$LANG->get('value_copied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="far fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="clearfix">
                <p><span class="text-danger">{$LANG->get('api_cr_notice')}.</span></p>
                <a href="{$URL_BACK}" class="btn btn-primary float-right">{$LANG->get('api_cr_back')}</a>
            </div>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
$(document).ready(function() {
    var clipboard1 = new ClipboardJS('#credential_ident_btn');
    var clipboard2 = new ClipboardJS('#credential_secret_btn');
    clipboard1.on('success', function(e) {
        $(e.trigger).tooltip('show');
    });
    clipboard2.on('success', function(e) {
        $(e.trigger).tooltip('show');
    });
    $('#credential_ident_btn,#credential_secret_btn').mouseleave(function() {
        $(this).tooltip('dispose');
    });
});
</script>
{/literal}
