<div id="notification" class="table-responsive notification" data-delete-confirm="{$LANG->getModule('delete_confirm')}">
    <table class="table table-bordered">
        <thead class="bg-primary">
            <tr>
                <th class="text-center" style="width: 1%;"><i class="fa fa-question-circle-o" title="{$LANG->getModule('status')}"></i></th>
                <th class="text-center" style="width: 25%;">{$LANG->getModule('receiver')}</th>
                <th class="text-center">{$LANG->getModule('content')}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('add_time')}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('exp_time')}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('views')}</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
{foreach $ITEMS as $item}
            <tr class="notification-item status-{$item.status}" data-id="{$item.id}">
                <td class="text-center" style="width: 1%;vertical-align:middle">
                    {strip}{if $item.status == 'waiting'}<i class="fa fa-hourglass-half" title="{$LANG->getModule('waiting')}"></i>
                    {else if $item.status == 'expired'}<i class="fa fa-ban" title="{$LANG->getModule('expired')}"></i>
                    {else}<i class="fa fa-cog fa-spin" title="{$LANG->getModule('active')}"></i>
                    {/if}{/strip}
                </td>
                <td style="width: 25%;vertical-align:middle">
                    {strip}{if empty($item.receiver_ids)}{$LANG->getModule('to_group_all')}
                    {else}
                    {foreach $item.receiver_ids as $mid}
                        <button type="button" class="btn btn-xs btn-default member-info" tabindex="0" data-toggle="viewUser" data-id="{$LANG->getModule('id')}: {$MEMBER.$mid.0}" data-username="{$LANG->getModule('username')}: {$MEMBER.$mid.1}" data-fullname="{$LANG->getModule('fullname')}: {$MEMBER.$mid.2}">{$MEMBER.$mid.2}</button>
                    {/foreach}
                    {/if}{/strip}
                </td>
                <td style="vertical-align:middle">
                    {$item.message.0}
                    {if !empty($item.message.1)}<span class="more">... <u data-toggle="more">{$LANG->getModule('view_more')}</u></span><span class="morecontent" style="display: none">{$item.message.1}</span>{/if}
                    {if !empty($item.link)}
                    <div class="inform-link"><a href="{$item.link}" target="_blank">{$LANG->getModule('inform_link')}</a></div>
                    {/if}
                </td>
                <td class="text-center" style="width: 1%;vertical-align:middle">
                    {$item.add_time_format}
                </td>
                <td class="text-center" style="width: 1%;vertical-align:middle">
                    {$item.exp_time_format}
                </td>
                <td class="text-center" style="width: 1%;vertical-align:middle">
                    {$item.views}
                </td>
                <td class="text-center text-nowrap" style="width: 1%;vertical-align:middle">
                    <button class="btn btn-default btn-sm" data-toggle="inform_action" data-type="edit" data-title="{$LANG->getModule('inform_edit')}" title="{$LANG->getModule('inform_edit')}"><i class="fa fa-pencil-square-o fa-fw"></i></button>
                    <button class="btn btn-default btn-sm" data-toggle="inform_del" title="{$LANG->getGlobal('delete')}"><i class="fa fa-trash-o fa-fw"></i></button>
                </td>
            </tr>
{/foreach}
        </tbody>
    </table>
{if !empty($GENERATE_PAGE)}
    <div class="text-center">
        {$GENERATE_PAGE}
    </div>
{/if}
</div>
<script>
    {literal}$(function() {
        $('#notification [data-toggle=viewUser]').each(function(e) {
            var content = $(this).data('id') + '<br/>' + $(this).data('username') + '<br/>' + $(this).data('fullname');
            $(this).popover({
                'trigger': 'focus',
                'placement': 'top',
                'html': true,
                'content': content
            })
        })
    }){/literal}
</script>
