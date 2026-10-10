<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-body pt-4">
            <div class="alert alert-info">{$LANG->getModule('trusted_proxy_note')}</div>
            <div class="alert alert-warning">{$LANG->getModule('trusted_proxy_note_strip')}</div>
            {if $WARNING}<div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> {$WARNING}</div>{/if}
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="trusted_proxy_enable">{$LANG->getModule('trusted_proxy_enable')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5 d-flex align-items-center">
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" class="form-check-input" role="switch" id="trusted_proxy_enable" name="trusted_proxy_enable" value="1"{if $DATA.trusted_proxy_enable} checked="checked"{/if}>
                        <label class="form-check-label" for="trusted_proxy_enable">{$LANG->getModule('trusted_proxy_enable_des')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="trusted_proxies" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('trusted_proxy_list')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control font-monospace" id="trusted_proxies" name="trusted_proxies" rows="12" spellcheck="false" placeholder="127.0.0.1&#10;10.0.0.0/8&#10;173.245.48.0/20">{$DATA.trusted_proxies}</textarea>
                    <div class="invalid-feedback"></div>
                    <div class="form-text">{$LANG->getModule('trusted_proxy_list_des')}</div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="fetch_cf" data-loaded-mess="{$LANG->getModule('trusted_proxy_cf_loaded')}">
                            <i class="fa-solid fa-cloud-arrow-down" data-icon="fa-cloud-arrow-down"></i> {$LANG->getModule('trusted_proxy_fetch_cf')}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <button type="submit" name="save" value="1" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
        </div>
    </div>
</form>
