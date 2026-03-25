<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="input-group" style="max-width:320px">
        <span class="input-group-text">{$LANG->getModule('select_menu_block')}</span>
        <select class="form-select" data-toggle="change-mid" name="filtermid">
            {foreach from=$MENUBLOCKS item=block}
            <option value="{$block.id}"{if $block.id == $PAGE.mid} selected{/if}>{$block.title|escape}</option>
            {/foreach}
        </select>
    </div>
    <button type="button" class="btn btn-sm btn-primary"
            data-toggle="add-menu"
            data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;action=add&amp;mid={$PAGE.mid}&amp;parentid={$PAGE.parentid}">
        <i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('add_item')}
    </button>
</div>

{if $ARRAY}
<form id="menulist" method="post"
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"
      data-mid="{$PAGE.mid}"
      data-parentid="{$PAGE.parentid}"
      data-reload-confirm="{$LANG->getModule('action_menu_reload_confirm')}">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap" style="width:2%">
                                <input type="checkbox" class="form-check-input" name="check_all[]" value="yes"
                                       onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]', this.checked);">
                            </th>
                            <th class="text-center text-nowrap" style="width:6%">{$LANG->getModule('number')}</th>
                            <th class="text-center text-nowrap" style="width:3%" colspan="2">{$LANG->getModule('sub_menu')}</th>
                            <th class="text-nowrap" style="width:35%">{$LANG->getModule('title')}</th>
                            <th class="text-nowrap" style="width:22%">{$LANG->getModule('groups_view')}</th>
                            <th class="text-center text-nowrap" style="width:10%">{$LANG->getModule('display')}</th>
                            <th class="text-center text-nowrap" style="width:22%">{$LANG->getModule('action')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$ARRAY item=row}
                        <tr class="item" data-id="{$row.id}" data-num="{$row.nu}">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input" name="idcheck[]" value="{$row.id}"
                                       onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);">
                            </td>
                            <td class="text-center">
                                <select class="form-select form-select-sm fw-75" name="weight" data-toggle="change-weight">
                                    {foreach from=$WEIGHT_OPTIONS item=w}
                                    <option value="{$w}"{if $w == $row.weight} selected{/if}>{$w}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td class="text-center">{$row.sub}</td>
                            <td>
                                {if $row.nu > 0}
                                <a href="{$row.url_title}" class="btn btn-sm btn-outline-secondary"
                                   data-bs-toggle="tooltip" title="{$LANG->getModule('sub_menu')}"
                                   aria-label="{$LANG->getModule('sub_menu')}">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </a>
                                {/if}
                            </td>
                            <td>
                                {if $row.icon}
                                <img src="{$row.icon}" height="20" alt="">
                                {/if}
                                {if $row.link}
                                <a href="javascript:void(0)" data-toggle="popover-link" data-contents="{$row.link}">
                                    <strong>{$row.title|escape}</strong>
                                </a>
                                {else}
                                <strong>{$row.title|escape}</strong>
                                {/if}
                            </td>
                            <td>
                                {if $row.groups_view}
                                <ul class="list-unstyled mb-0">
                                    {foreach from=$row.groups_view item=gr}
                                    <li class="text-nowrap">- {$gr|escape}</li>
                                    {/foreach}
                                </ul>
                                {/if}
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input type="checkbox" class="form-check-input" role="switch"
                                           id="change_active_{$row.id}"
                                           {if $row.status} checked{/if}
                                           data-toggle="change-active">
                                </div>
                            </td>
                            <td class="text-center text-nowrap">
                                {if $row.can_reload}
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-toggle="menu-reload"
                                        data-bs-toggle="tooltip" title="{$LANG->getModule('action_menu_reload_note')}"
                                        aria-label="{$LANG->getModule('action_menu_reload')}">
                                    <i class="fa-solid fa-rotate" data-icon="fa-rotate"></i>
                                </button>
                                {/if}
                                <button type="button" class="btn btn-sm btn-secondary"
                                        data-toggle="edit-menu"
                                        data-url="{$row.edit_url}"
                                        data-bs-toggle="tooltip" title="{$LANG->getGlobal('edit')}"
                                        aria-label="{$LANG->getGlobal('edit')}">
                                    <i class="fa-solid fa-pencil" data-icon="fa-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger"
                                        data-toggle="item-delete"
                                        data-bs-toggle="tooltip" title="{$LANG->getGlobal('delete')}"
                                        aria-label="{$LANG->getGlobal('delete')}">
                                    <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top">
            <button type="button" class="btn btn-sm btn-danger"
                    data-toggle="multi-delete"
                    data-error="{$LANG->getModule('msgnocheck')}">
                <i class="fa-solid fa-trash"></i> {$LANG->getModule('multi_delete')}
            </button>
        </div>
    </div>
</form>
{else}
<div class="alert alert-info text-center">{$LANG->getModule('no_submenu')}</div>
{/if}

<div id="edit"></div>
