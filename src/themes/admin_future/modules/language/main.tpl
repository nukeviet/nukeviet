<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('lang_installed')}
    </div>
    <div class="card-body">
        <div class="table-responsive table-card">
            <table class="table table-striped align-middle mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-center text-nowrap" style="width: 5%;">{$LANG->getModule('order')}</th>
                        <th class="text-center text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_key')}</th>
                        <th class="text-center text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_name')}</th>
                        <th class="text-center text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_slsite')}</th>
                        <th class="text-center text-nowrap" style="width: 40%;">{$LANG->getGlobal('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$LIST_LANGS key=keylang item=linfo}
                    {if in_array($keylang, $EXISTS_LANGS, true)}
                    <tr>
                        <td>
                            <select class="form-select fw-75" data-toggle="change_weight" data-keylang="{$keylang}" data-current="{$linfo.weight}">
                                {for $weight=1 to $NUM_LANGS}
                                <option value="{$weight}"{if $weight eq $linfo.weight} selected{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td class="text-center">{$keylang}</td>
                        <td class="text-center">{$LANGUAGE_ARRAY[$keylang].name}</td>
                        <td class="text-center">
                            {if $keylang eq $GCONFIG.site_lang}
                            {$LANG->getModule('site_lang')}
                            {elseif $smarty.const.NV_IS_GODADMIN or ($GCONFIG.idsite gt 0 and $smarty.const.NV_IS_SPADMIN and $linfo.setup eq 1)}
                            <div class="form-check form-switch mb-0 d-inline-block">
                                <input data-toggle="activelang" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;keylang={$keylang}&amp;checksess={md5("activelang_`$keylang``$smarty.const.NV_CHECK_SESSION`")}&amp;activelang=" class="form-check-input" type="checkbox" role="switch" aria-label="{$LANG->getModule('nv_lang_slsite')}"{if in_array($keylang, $GCONFIG.allow_sitelangs, true)} checked data-current="1"{else} data-current="0"{/if}>
                            </div>
                            {/if}
                        </td>
                        <td class="text-center">
                            {if $smarty.const.NV_IS_GODADMIN or ($GCONFIG.idsite gt 0 and $smarty.const.NV_IS_SPADMIN and $linfo.setup eq 1)}
                            {if in_array($keylang, $GCONFIG.allow_sitelangs, true)}
                            <i class="fa-solid fa-check text-success" title="{$LANG->getModule('nv_setup')}" aria-label="{$LANG->getModule('nv_setup')}"></i>
                            {else}
                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="setup_delete" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;deletekeylang={$keylang}&amp;checksess={md5("`$keylang``$smarty.const.NV_CHECK_SESSION`deletekeylang")}" data-bs-toggle="tooltip" data-bs-trigger="hover" title="{$LANG->getModule('nv_setup_delete')}" aria-label="{$LANG->getModule('nv_setup_delete')}"><i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i></button>
                            {/if}
                            {/if}
                        </td>
                    </tr>
                    {/if}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{if not empty($OTHER_LANGS)}
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mt-4">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('lang_can_install')}
    </div>
    <div class="card-body">
        <div class="table-responsive table-card">
            <table class="table table-striped align-middle mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-center text-nowrap" style="width: 30%;">{$LANG->getModule('nv_lang_key')}</th>
                        <th class="text-center text-nowrap" style="width: 30%;">{$LANG->getModule('nv_lang_name')}</th>
                        <th class="text-center text-nowrap" style="width: 40%;">{$LANG->getGlobal('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$EXISTS_LANGS item=keylang}
                    {if in_array($keylang, $OTHER_LANGS, true)}
                    <tr>
                        <td class="text-center">{$keylang}</td>
                        <td class="text-center">{$LANGUAGE_ARRAY[$keylang].name}</td>
                        <td class="text-center">
                            {if $smarty.const.NV_IS_GODADMIN or ($GCONFIG.idsite gt 0 and $smarty.const.NV_IS_SPADMIN)}
                            <button type="button" data-toggle="setup_new" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;keylang={$keylang}&amp;checksess={md5("`$keylang``$smarty.const.NV_CHECK_SESSION`")}" class="btn btn-primary btn-sm"><i class="fa-solid fa-sun" data-icon="fa-sun"></i> {$LANG->getModule('nv_setup_new')}</button>
                            {/if}
                        </td>
                    </tr>
                    {/if}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
