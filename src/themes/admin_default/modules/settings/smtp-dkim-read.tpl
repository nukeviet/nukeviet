<div class="item" data-domain="{$DOMAIN}" data-confirm="{$LANG->getModule('dkim_del_confirm')}">
    {if not $IS_VERIFIED}
    <div class="mb-2 text-center"><strong>{$LANG->getModule('DKIM_authentication')}</strong></div>
    <div class="mb-2">{$LANG->getModule('DKIM_verify_note', $DOMAIN)}</div>
    {else}
    <div class="mb-2 text-center"><strong>{$LANG->getModule('DKIM_verified')}</strong></div>
    {/if}
    <div class="mb-3">
        <label class="form-label fw-medium" for="dkim-txt-host">{$LANG->getModule('DKIM_TXT_host')}:</label>
        <div class="input-group">
            <input type="text" class="form-control" id="dkim-txt-host" readonly="readonly" value="nv._domainkey">
            <button class="btn btn-secondary" type="button" data-clipboard-target="#dkim-txt-host" data-toggle="clipboard" data-title="{$LANG->getModule('value_copied')}"><i class="fa-regular fa-copy"></i></button>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label fw-medium" for="pubkeyvalue">{$LANG->getModule('DKIM_TXT_value')}:</label>
        <div class="input-group">
            <textarea class="form-control" readonly="readonly" id="pubkeyvalue" rows="6">{$DNSVALUE}</textarea>
            <button class="btn btn-secondary" type="button" data-clipboard-target="#pubkeyvalue" data-toggle="clipboard" data-title="{$LANG->getModule('value_copied')}"><i class="fa-regular fa-copy"></i></button>
        </div>
    </div>
    <div class="text-center">
        <div class="hstack d-inline-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-primary" data-toggle="dkimverify">
                <i class="fa-solid fa-star" data-icon="fa-star"></i> {if not $IS_VERIFIED}{$LANG->getModule('dkim_verify')}{else}{$LANG->getModule('dkim_reverify')}{/if}
            </button>
            <button type="button" class="btn btn-danger" data-toggle="dkimdel"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getModule('dkim_del')}</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> {$LANG->getGlobal('close')}</button>
        </div>
    </div>
</div>
