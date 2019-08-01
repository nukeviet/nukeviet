<form action="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}" method="post">
    <div class="card card-table">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">{$LANG->get('nv_lang_nb')}</th>
                            <th style="width: 40%;" class="text-right">{$LANG->get('countries_name')}</th>
                            <th style="width: 55%;">{$LANG->get('nv_lang_data')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="stt" value=1}
                        {foreach from=$COUNTRIES key=c_id item=c_value}
                        <tr>
                            <td class="text-center">{$stt++}</td>
                            <td class="text-right">{$c_value.1}</td>
                            <td class="text-left">
                                <select name="countries[{$c_id}]" class="form-control form-control-sm">
                                    {foreach from=$ARRAY_LANG_SETUP item=lang_data}
                                    <option value="{$lang_data.0}"{if isset($CONFIG_GEO[$c_id]) and $CONFIG_GEO[$c_id] eq $lang_data.0} selected="selected"{/if}>{$lang_data.1}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <input type="submit" name="submit" value="{$LANG->get('nv_admin_submit')}" class="btn btn-primary">
        </div>
    </div>
</form>
