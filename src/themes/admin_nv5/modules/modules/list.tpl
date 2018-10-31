{if not empty($ACT_MODULES)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('caption_actmod')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%;">{$LANG->get('weight')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('module_name')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('custom_title')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('version')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('activate')}</th>
                        <th style="width: 20%;" class="text-right text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ACT_MODULES key=mod item=row}
                    <tr>
                        <td>
                            <select class="form-control form-control-xs w70" id="change_weight_{$mod}" onchange="nv_chang_weight('{$row.title}');">
                                {foreach from=$WEIGHT_LIST item=weight}
                                <option value="{$weight}"{if $weight eq $row.weight} selected="selected"{/if}>{$weight}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="text-nowrap"><i class="fas fa-search mr-1"></i><a href="{$row.link_show}">{$row.title}</a></td>
                        <td class="text-nowrap">{$row.custom_title}</td>
                        <td class="text-nowrap">{$row.version}</td>
                        <td class="text-nowrap">
                            <div class="switch-button switch-button-sm">
                                <input type="checkbox" name="change_act_{$mod}" id="change_act_{$mod}" onclick="nv_chang_act('{$mod}');" checked="checked"{if $row.is_sys} disabled="disabled"{/if}><span><label for="change_act_{$mod}"></label></span>
                            </div>
                        </td>
                        <td class="text-right text-nowrap">
                            <a href="{$row.link_edit}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" data-title="{$mod}" class="btn btn-sm btn-secondary nv-reinstall-module"><i class="icon icon-left far fa-sun"></i> {$LANG->get('recreate')}</a>
                            {if $row.is_sys}
                            <a href="#" onclick="return nv_mod_del('{$mod}');" class="btn btn-sm btn-danger"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
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
