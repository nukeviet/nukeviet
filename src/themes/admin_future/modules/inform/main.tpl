<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<div class="card" id="inform-list">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        {if $IS_SPADMIN and $FILTERS}
        <select id="inform-filter" class="form-select w-auto" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
            <option value="">{$LANG->getModule('filter_all')}</option>
            {foreach from=$FILTERS key=key item=title}
            <option value="{$key}"{if $key == $FILTER} selected{/if}>{$title}</option>
            {/foreach}
        </select>
        {else}
        <h5 class="card-title mb-0">{$LANG->getModule('main_title')}</h5>
        {/if}
        <button type="button" class="btn btn-sm btn-primary"
                data-toggle="inform-action"
                data-type="add"
                data-title="{$LANG->getModule('inform_add')}">
            <i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('add_inform')}
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:3%">{$LANG->getModule('status')}</th>
                        <th class="text-nowrap" style="width:15%">{$LANG->getModule('sender')}</th>
                        <th class="text-nowrap" style="width:18%">{$LANG->getModule('receiver')}</th>
                        <th class="text-nowrap" style="width:26%">{$LANG->getModule('content')}</th>
                        <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('add_time')}</th>
                        <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('exp_time')}</th>
                        <th class="text-center text-nowrap" style="width:6%">{$LANG->getModule('views')}</th>
                        <th class="text-center text-nowrap" style="width:10%"></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ITEMS item=item}
                    <tr data-id="{$item.id}">
                        <td class="text-center">
                            {if $item.status == 'waiting'}
                            <i class="fa-solid fa-hourglass-half text-warning" title="{$LANG->getModule('waiting')}"></i>
                            {elseif $item.status == 'expired'}
                            <i class="fa-solid fa-ban text-danger" title="{$LANG->getModule('expired')}"></i>
                            {else}
                            <i class="fa-solid fa-circle-check text-success" title="{$LANG->getModule('active')}"></i>
                            {/if}
                        </td>
                        <td>
                            {if $item.sender_role == 'group'}
                            {$LANG->getModule('admin_from_group')}<br>
                            {$LANG->getModule('id')} #{$item.sender_group}: <a href="{$item.sender_group_link}">{$item.sender_group_name}</a>
                            {elseif $item.sender_role == 'admin'}
                            {$LANG->getModule('admin_from_admin')}<br>
                            {$LANG->getModule('id')} #{$item.sender_admin}: <a href="{$item.sender_admin_link}">{$item.sender_admin_name}</a>
                            {else}
                            {$LANG->getModule('admin_from_system')}
                            {/if}
                        </td>
                        <td>
                            {$item.receiver_title}
                            {if $item.receiver_groups}:
                                {foreach from=$item.receiver_groups item=gr key=gidx}
                                    {if $gidx > 0}, {/if}<a href="{$gr.link}">{$gr.name}</a>
                                {/foreach}
                            {elseif $item.receiver_users}:
                                {foreach from=$item.receiver_users item=user key=uidx}
                                    {if $uidx > 0}, {/if}<a href="#" tabindex="0"
                                       data-bs-toggle="popover"
                                       data-bs-trigger="focus"
                                       data-bs-html="true"
                                       data-bs-placement="top"
                                       data-bs-content="{$LANG->getModule('id')}: {$user.uid}&lt;br&gt;{$LANG->getModule('username')}: {$user.username|escape:'html'}">{$user.fullname|escape:'html'}</a>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if $item.message}{$item.message.0}{/if}
                            {if isset($item.message.1)}
                            <span class="more">... <u role="button" data-toggle="inform-more">{$LANG->getModule('view_more')}</u></span>
                            <span class="morecontent d-none">{$item.message.1}</span>
                            {/if}
                            {if $item.link}
                            <div><a href="{$item.link}" target="_blank" rel="noopener">{$LANG->getModule('inform_link')}</a></div>
                            {/if}
                        </td>
                        <td class="text-center">{$item.add_time_format}</td>
                        <td class="text-center">{$item.exp_time_format}</td>
                        <td class="text-center">{$item.views}</td>
                        <td class="text-center text-nowrap">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-toggle="inform-action"
                                    data-type="edit"
                                    data-title="{$LANG->getModule('inform_edit')}">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </button>
                            <button type="button" class="btn btn-sm btn-danger"
                                    aria-label="{$LANG->getGlobal('delete')}"
                                    data-bs-toggle="tooltip" title="{$LANG->getGlobal('delete')}"
                                    data-toggle="confirm-delete"
                                    data-id="{$item.id}"
                                    data-tokend="{$CHECKSS}"
                                    data-msgconfirm="{$LANG->getModule('delete_confirm')}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fa-solid fa-bell-slash fa-2x d-block mb-2"></i>
                            {$LANG->getModule('no_notifications')}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $PAGINATION}
    <div class="card-footer border-top">
        <div class="d-flex justify-content-end">
            <div class="pagination-wrap">{$PAGINATION}</div>
        </div>
    </div>
    {/if}
</div>

{* Modal thêm/sửa thông báo *}
<div class="modal fade" id="inform-action-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body" id="inform-action-body">
                <div class="text-center py-4">
                    <i class="fa-solid fa-spinner fa-spin-pulse fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
