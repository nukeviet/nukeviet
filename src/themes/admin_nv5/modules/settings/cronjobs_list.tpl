{foreach from=$CRONJOBS item=$row}
<div class="card card-table card-border-color card-border-color-{if empty($row['act'])}danger{else}success{/if}">
    <div class="card-header pt-3">
        {if isset($row["`$NV_LANG_INTERFACE`_cron_name"])}
        {$row["`$NV_LANG_INTERFACE`_cron_name"]}
        {elseif isset($row["`$NV_LANG_DATA`_cron_name"])}
        {$row["`$NV_LANG_DATA`_cron_name"]}
        {else}
        {$row.run_func}
        {/if}
        <div class="tools">
            {if empty($row['is_sys']) or empty($row['act'])}
            <a href="{$BASE_URL}cronjobs_act&amp;id={$row['id']}" title="{if empty($row['act'])}{$LANG->get('activate')}{else}{$LANG->get('disable')}{/if}"><i class="fas fa-{if empty($row['act'])}check-circle{else}power-off{/if}"></i></a>
            {/if}
            {if empty($row['is_sys'])}
            <a href="{$BASE_URL}cronjobs_edit&amp;id={$row['id']}" title="{$LANG->get('edit')}"><i class="fas fa-pencil-alt"></i></a>
            <a href="javascript:void(0);" title="{$LANG->get('delete')}" onclick="nv_is_del_cron('{$row['id']}');"><i class="fas fa-trash-alt"></i></a>
            {/if}
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <tbody>
                    <tr>
                        <td>{$LANG->get('run_file')}</td>
                        <td>{$row['run_file']}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('run_func')}</td>
                        <td>{$row['run_func']}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('params')}</td>
                        <td>{$row['params']}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('start_time')}</td>
                        <td>{"l, d/m/Y H:i:s"|date:$row['start_time']}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('interval')}</td>
                        <td>{$row['inter_val']|sec2text}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('is_del')}</td>
                        <td>{if empty($row['del'])}{$LANG->get('notdel')}{else}{$LANG->get('isdel')}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('is_sys')}</td>
                        <td>{if empty($row['is_sys'])}{$LANG->get('client')}{else}{$LANG->get('system')}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('act')}</td>
                        <td>{if empty($row['act'])}{$LANG->get('act0')}{else}{$LANG->get('act1')}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('last_time')}</td>
                        <td>{if empty($row['last_time'])}{$LANG->get('last_time0')}{else}{"l, d/m/Y H:i:s"|date:$row['last_time']}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('last_result')}</td>
                        <td>{if empty($row['last_time'])}{$LANG->get('last_result_empty')}{else}{$LANG->get("last_result`$row['last_result']`")}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('next_time')}</td>
                        <td>
                            {if empty($row['act'])}
                            n/a
                            {elseif empty($row['inter_val']) or empty($row['last_time'])}
                            {assign var="maxtime" value=$row['start_time']|max:$CRONJOBS_NEXT_TIME:$NV_CURRENTTIME nocache}
                            {"l, d/m/Y H:i:s"|date:$maxtime}
                            {else}
                            {"l, d/m/Y H:i:s"|date:($row['last_time'] + ($row['inter_val'] * 60))}
                            {/if}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
{/foreach}
