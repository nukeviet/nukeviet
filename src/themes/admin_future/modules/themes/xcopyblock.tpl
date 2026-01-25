<div class="alert alert-info" role="alert">{$LANG->getModule('xcopyblock_notice')}</div>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" id="formXcopyBlock" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate data-error="{$LANG->getModule('xcopyblock_no_position')}">
            <div class="row mb-3">
                <label for="element_theme1" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('xcopyblock')} {$LANG->getModule('xcopyblock_from')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select w-auto mw-100" id="element_theme1" name="theme1" data-toggle="xCpBlSel">
                        <option value="0">{$LANG->getModule('autoinstall_method_theme_none')}</option>
                        {foreach from=$ARRAY_THEMES item=theme}
                        <option value="{$theme}">{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_theme2" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('xcopyblock_to')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select w-auto mw-100" id="element_theme2" name="theme2" data-toggle="xCpBlSel">
                        <option value="0">{$LANG->getModule('autoinstall_method_theme_none')}</option>
                        {foreach from=$ARRAY_THEMES item=theme}
                        <option value="{$theme}"{if $theme eq $SELECTTHEMES and $SELECTTHEMES neq 'default'} selected{/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div id="loadposition" class="row mb-3 d-none">
                <div class="col-sm-3 col-form-label text-sm-end pt-0">{$LANG->getModule('xcopyblock_position')}</div>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <div data-toggle="loader" class="d-none">
                        <i class="fa-solid fa-spinner fa-spin-pulse"></i> {$LANG->getGlobal('wait_page_load')}
                    </div>
                    <div data-toggle="res" class="d-none"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-shuffle" data-icon="fa-shuffle"></i> {$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
