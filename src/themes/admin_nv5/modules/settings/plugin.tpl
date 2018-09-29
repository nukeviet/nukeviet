<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('plugin_note')}</div>
</div>
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('plugin_integrated')}
    </div>
    <div class="card-body">
        <div class="row card-body-search-form search-form-right pt-0">
            <div class="col-12">
                <form action="{$NV_BASE_ADMINURL}index.php" method="get" class="form-inline" id="formSearchPlugin">
                    <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}"/>
                    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}"/>
                    <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}"/>
                    <label>{$LANG->get('plugin_viewarea')}: </label>
                    <select name="a" class="form-control form-control-sm">
                        <option value="">--</option>
                        {foreach from=$PLUGIN_AREA item=row}
                        <option value="{$row}"{if $row eq $SEARCH['plugin_area']} selected="selected"{/if}>{$row}</option>
                        {/foreach}
                    </select>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        {if not empty($SEARCH['plugin_area'])}
                        <th style="width:5%;">{$LANG->get('plugin_number')}</th>
                        {/if}
                        <th style="width:30%;">{$LANG->get('plugin_area')}</th>
                        <th style="width:35%;">{$LANG->get('plugin_file')}</th>
                        <th style="width:15%;">{$LANG->get('plugin_type')}</th>
                        <th class="text-right text-nowrap" style="width:10%;">{$LANG->get('plugin_func')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$PLUGIN_DB item=row}
                    <tr>
                        {if not empty($SEARCH['plugin_area'])}
                        <td>
                            <select id="weight_{$row.pid}" class="form-control form-control-xs w70" onchange="nv_change_plugin_weight('{$row.pid}');">
                                {for $weight=1 to $PLUGIN_DB_NUM}
                                <option value="{$weight}"{if $weight eq $row.weight} selected="selected"{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        {/if}
                        <td>{$row['hook_module']}{$row['plugin_area']}</td>
                        <td>{$row['plugin_file']}</td>
                        <td>{if empty($row['plugin_module_name'])}{$LANG->get('plugin_type_sys')}{else}{$LANG->get('plugin_type_module')}:{$row.plugin_module_name}{/if}</td>
                        <td class="text-right text-nowrap">
                            {if empty($row.plugin_module_file)}
                            <a onclick="nv_del_plugin({$row.pid});" href="javascript:void(0);" class="btn btn-sm btn-danger"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('isdel')}</a>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

{if not empty($AVAILABLE_PLUGINS)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('plugin_available')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width:25%;">{$LANG->get('plugin_area')}</th>
                        <th style="width:25%;">{$LANG->get('plugin_file')}</th>
                        <th style="width:25%;">{$LANG->get('plugin_type')}</th>
                        <th style="width:15%;">{$LANG->get('act')}</th>
                        <th class="text-right text-nowrap" style="width:10%;">{$LANG->get('plugin_func')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$AVAILABLE_PLUGINS item=row}
                    <tr>
                        <td>{if not empty($row.hook_module)}{$row.hook_module}:{/if}{", "|implode:$row.area}</td>
                        <td>{$row['file']}</td>
                        <td>
                            {if empty($row.receive_module)}
                            {$LANG->get('plugin_type_sys')}
                            {else}
                            {$LANG->get('plugin_type_module')}:{$row.receive_module}
                            {/if}
                        </td>
                        <td>
                            {if sizeof($row['area']) eq 1}<span class="text-success">{$LANG->get('plugin_status_ok')}</span>{else}<span class="text-danger">{$LANG->get('plugin_status_error')}</span>{/if}
                        </td>
                        <td class="text-right text-nowrap">
                            {if sizeof($row['area']) eq 1}
                            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}&amp;plugin_file={$row['file']}&amp;rand={$RAND}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-cog"></i> {$LANG->get('plugin_integrate')}</a>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}

<script type="text/javascript">
$(function() {
    $('[name="a"]').change(function() {
        $('#formSearchPlugin').submit();
    });
});
</script>
