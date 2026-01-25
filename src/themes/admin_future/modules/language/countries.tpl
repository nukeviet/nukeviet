<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle table-sticky mb-1">
                    <thead class="text-muted">
                        <tr>
                            <th class="text-center text-nowrap" style="width: 10%;">{$LANG->getModule('nv_lang_nb')}</th>
                            <th class="text-nowrap" style="width: 45%;">{$LANG->getModule('countries_name')}</th>
                            <th class="text-nowrap" style="width: 45%;">{$LANG->getModule('nv_lang_data')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="stt" value=0 nocache}
                        {foreach from=$COUNTRIES key=key item=value}
                        {assign var="stt" value=($stt + 1) nocache}
                        <tr>
                            <td class="text-center">{$stt}</td>
                            <td>
                                {if $LANG->existsGlobal("country_`$key`")}
                                {$LANG->getGlobal("country_`$key`")}
                                {else}{$value.1}{/if}
                            </td>
                            <td>
                                <select name="countries[{$key}]" class="form-select d-inline-block w-auto">
                                    {foreach from=$LANG_SETUP item=lang}
                                    <option value="{$lang.0}"{if isset($CONFIG_GEO[$key]) and $CONFIG_GEO[$key] eq $lang.0} selected{/if}>{$lang.1}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-center" colspan="3">
                                <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                                <button type="submit" class="btn btn-primary">{$LANG->getModule('nv_admin_submit')}</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>
