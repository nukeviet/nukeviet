<form action="{$FORM_ACTION}" method="post" class="ip-action">
    <div class="row mb-3">
        <label for="element_version" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('ip_version')}</label>
        <div class="col-sm-8 col-lg-6 col-xxl-5">
            <select type="text" class="form-select ip-version w-auto mw-100" id="element_version" name="version">
                {foreach from=$IPTYPES key=key item=value}
                <option value="{$key}"{if $key eq $VERSION} selected{/if}>{$value}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label for="element_ip" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('ip_address')} <span class="text-danger">(*)</span></label>
        <div class="col-sm-8 col-lg-6 col-xxl-5">
            <input class="form-control" type="text" id="element_ip" name="ip" value="{$DATA.ip}">
        </div>
    </div>
    <div class="row mb-3">
        <label for="element_mask" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('ip_mask')}</label>
        <div class="col-sm-8 col-lg-6 col-xxl-5">
            <select type="text" class="form-select ip-mask w-auto mw-100" id="element_mask" name="mask">
                {foreach from=$MASK_LIST key=key item=value}
                <option value="{$key}" data-version="4"{if $key eq $DATA.mask and $VERSION eq 4} selected{/if}{if $VERSION neq 4} disabled style="display: none;"{/if}>{$value}</option>
                {/foreach}
                {for $i=0 to 127}
                {assign var="key" value=(128 - $i) nocache}
                <option value="{$key}" data-version="6"{if $key eq $DATA.mask and $VERSION eq 6} selected{/if}{if $VERSION neq 6} disabled style="display: none;"{/if}>/{$key}</option>
                {/for}
            </select>
        </div>
    </div>
    {if $FORM_TYPE eq 0}
    <div class="row mb-3">
        <label for="element_area" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('banip_area')}</label>
        <div class="col-sm-8 col-lg-6 col-xxl-5">
            <select type="text" class="form-select w-auto mw-100" id="element_area" name="area">
                {foreach from=$AREA_LIST key=key item=value}
                <option value="{$key}" {if $key eq $DATA.area} selected{/if}>{$value}</option>
                {/foreach}
            </select>
        </div>
    </div>
    {/if}
    <div class="row mb-3">
        <label for="element_begintime" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('start_time')}</label>
        <div class="col-sm-8 col-lg-6 col-xxl-5">
            <div class="hstack gap-2">
                <input type="text" id="element_begintime" name="begintime" class="fw-100 datepicker form-control" value="{$DATA.begintime}">
                <select name="beginhour" class="form-select fw-75">
                    {for $i=0 to 23}
                    <option value="{$i}"{if $i eq $BEGINHOUR} selected{/if}>{str_pad($i, 2, '0', STR_PAD_LEFT)}</option>
                    {/for}
                </select>
                <select name="beginmin" class="form-select fw-75">
                    {for $i=0 to 59}
                    <option value="{$i}"{if $i eq $BEGINMIN} selected{/if}>{str_pad($i, 2, '0', STR_PAD_LEFT)}</option>
                    {/for}
                </select>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="element_endtime" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('end_time')}</label>
        <div class="col-sm-8 col-lg-6 col-xxl-5">
            <div class="hstack gap-2">
                <input type="text" id="element_endtime" name="endtime" class="fw-100 datepicker form-control" value="{$DATA.endtime}">
                <select name="endhour" class="form-select fw-75">
                    {for $i=0 to 23}
                    <option value="{$i}"{if $i eq $ENDHOUR} selected{/if}>{str_pad($i, 2, '0', STR_PAD_LEFT)}</option>
                    {/for}
                </select>
                <select name="endmin" class="form-select fw-75">
                    {for $i=0 to 59}
                    <option value="{$i}"{if $i eq $ENDMIN} selected{/if}>{str_pad($i, 2, '0', STR_PAD_LEFT)}</option>
                    {/for}
                </select>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="element_notice" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('notice')}</label>
        <div class="col-sm-8 col-lg-6 col-xxl-5">
            <textarea class="form-control" id="element_notice" name="notice" maxlength="255" rows="3">{$DATA.notice}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <input type="hidden" name="save" value="1">
            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
        </div>
    </div>
</form>
