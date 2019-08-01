<div class="card card-table">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%;">{$LANG->get('funcs_subweight')}</th>
                        <th style="width: 5%;" class="text-nowrap text-center">{$LANG->get('funcs_in_submenu')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('funcs_title')}</th>
                        {if $MODULE_VERSION.virtual}
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('funcs_alias')}</th>
                        {/if}
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('custom_title')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('site_title')}</th>
                        <th style="width: 25%;" class="text-right text-nowrap">{$LANG->get('funcs_layout')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ACT_FUNCS key=funcs item=values}
                    {if $values.show_func}
                    <tr>
                        <td>
                            <select class="form-control form-control-xs w70" name="change_weight_{$values.func_id}" id="change_weight_{$values.func_id}" onchange="nv_chang_func_weight('{$values.func_id}');">
                                {foreach from=$WEIGHT_LIST item=weight}
                                <option value="{$weight}"{if $weight eq $values.subweight} selected="selected"{/if}>{$weight}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="text-center">
                            <label class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" value="1" name="chang_func_in_submenu_{$values.func_id}" id="chang_func_in_submenu_{$values.func_id}" onclick="nv_chang_func_in_submenu({$values.func_id});"{if $values.in_submenu} checked="checked"{/if}{if not in_array($funcs, $ARR_IN_SUBMENU)} disabled="disabled"{/if}><span class="custom-control-label"></span>
                            </label>
                        </td>
                        <td class="text-nowrap">{$funcs}</td>
                        {if $MODULE_VERSION.virtual}
                        {if $funcs neq 'main' and in_array($funcs, $FUN_CHANGE_ALIAS)}
                        <td><a href="#" onclick="return nv_change_alias({$values.func_id}, 'show_funcs_action');">{$values.alias}</a></td>
                        {else}
                        <td>{$values.alias}</td>
                        {/if}
                        {/if}
                        <td><a href="#" onclick="return nv_change_custom_name({$values.func_id}, 'show_funcs_action');">{$values.func_custom_name}</a></td>
                        <td><a href="#" onclick="return nv_change_site_title({$values.func_id}, 'show_funcs_action');">{$values.func_site_title}</a></td>
                        <td>{$values.layout}</td>
                    </tr>
                    {/if}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
