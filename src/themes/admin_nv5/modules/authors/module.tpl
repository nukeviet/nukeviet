<div class="card card-table">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%;" class="text-center">{$LANG->get('number')}</th>
                        <th style="width: 25%;">{$LANG->get('module')}</th>
                        <th style="width: 25%;">{$LANG->get('custom_title')}</th>
                        <th style="width: 14%;" class="text-center">{$LANG->get('level1')}</th>
                        <th style="width: 14%;" class="text-center">{$LANG->get('level2')}</th>
                        <th style="width: 14%;" class="text-center">{$LANG->get('level3')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>
                            <select name="id_weight_{$row.mid}" id="id_weight_{$row.mid}" onchange="nv_chang_weight('{$row.mid}');" class="form-control form-control-xs">
                                {for $weight=1 to $NUM_MODULES}
                                <option value="{$weight}"{if $weight eq $row.weight} selected="selected"{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td>{$row.module}</td>
                        <td>{$row.custom_title}</td>
                        <td class="text-center">
                            {if $row.module eq 'authors'}
                            {if $row.act_1}{$LANG->get('on')}{else}{$LANG->get('off')}{/if}
                            {else}
                            <div class="switch-button switch-button-sm text-left">
                                <input type="checkbox" id="change_act_1_{$row.mid}" value="1" onclick="nv_chang_act('{$row.mid}', 1);"{if $row.act_1} checked="checked"{/if}><span><label for="change_act_1_{$row.mid}"></label></span>
                            </div>
                            {/if}
                        </td>
                        <td class="text-center">
                            <div class="switch-button switch-button-sm text-left">
                                <input type="checkbox" id="change_act_2_{$row.mid}" value="1" onclick="nv_chang_act('{$row.mid}', 2);"{if $row.act_2} checked="checked"{/if}><span><label for="change_act_2_{$row.mid}"></label></span>
                            </div>
                        </td>
                        <td class="text-center">
                            {if $row.module eq 'database' or $row.module eq 'settings' or $row.module eq 'site'}
                            {if $row.act_3}{$LANG->get('on')}{else}{$LANG->get('off')}{/if}
                            {else}
                            <div class="switch-button switch-button-sm text-left">
                                <input type="checkbox" id="change_act_3_{$row.mid}" value="1" onclick="nv_chang_act('{$row.mid}', 3);"{if $row.act_3} checked="checked"{/if}><span><label for="change_act_3_{$row.mid}"></label></span>
                            </div>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
