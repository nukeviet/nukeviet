{if $VIEW_MODE == 'full'}
    {if $SHOW_NO_USER}
    <div class="alert alert-warning" role="alert">
        <h5 class="alert-heading mb-1">{$LANG->getModule('admin_no_user_title')}</h5>
        <div>{$LANG->getModule('admin_no_user_content')}</div>
    </div>
    {else}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            {foreach from=$TABLE_HEADERS item=head}
                            <th class="text-nowrap"{if $head.key == 'userid'} style="width:10%"{elseif $head.key == 'username'} style="width:20%"{elseif $head.key == 'full_name'} style="width:25%"{else} style="width:25%"{/if}>
                                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;sortby={$head.key}&amp;sorttype={if $ORDERBY == $head.key and $ORDERTYPE == 'ASC'}DESC{else}ASC{/if}">
                                    {$head.title}
                                    {if $ORDERBY == $head.key}
                                        {if $ORDERTYPE == 'ASC'}
                                        <i class="fa-solid fa-arrow-up-short-wide ms-1"></i>
                                        {else}
                                        <i class="fa-solid fa-arrow-down-wide-short ms-1"></i>
                                        {/if}
                                    {/if}
                                </a>
                            </th>
                            {/foreach}
                            <th class="text-nowrap" style="width:10%">{$LANG->getModule('admin_permissions')}</th>
                            <th class="text-center text-nowrap" style="width:10%">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$USERS_LIST item=row}
                        <tr>
                            <td>{$row.userid}</td>
                            <td><strong>{$row.username}</strong></td>
                            <td>{$row.full_name}</td>
                            <td><a href="mailto:{$row.email}">{$row.email}</a></td>
                            <td>{$row.admin_module_cat}</td>
                            <td class="text-center text-nowrap">
                                {if $row.is_edit}
                                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;userid={$row.userid}"
                                   class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                                </a>
                                {/if}
                            </td>
                        </tr>
                        {foreachelse}
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {if $CAN_EDIT_USER}
    <form id="admin-permission-form" method="post" class="ajax-submit" novalidate data-is-edit="1"
          action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{$LANG->getModule('admin_edit_user')}: {$EDIT_USER.username}</h5>
            </div>
            <div class="card-body pt-4">
                <div class="row mb-4">
                    <div class="col-sm-3 text-sm-end">
                        <div class="form-label mb-0">{$LANG->getModule('admin_permissions')}</div>
                    </div>
                    <div class="col-sm-9">
                        {foreach from=$PERMISSION_OPTIONS item=item}
                        <div class="form-check form-check-inline me-4 mb-2">
                            <input class="form-check-input" type="radio" name="admin_module"
                                   id="admin_module_{$item.value}"
                                   value="{$item.value}"
                                   {if $EDIT_USER.permission_level == $item.value}checked{/if}>
                            <label class="form-check-label" for="admin_module_{$item.value}">{$item.text}</label>
                        </div>
                        {/foreach}
                    </div>
                </div>

                <div id="admin-permission-matrix"{if $EDIT_USER.permission_level > 0} class="d-none"{/if}>
                    <div class="table-responsive-lg table-card pb-1">
                        <table class="table table-striped align-middle table-sticky mb-0">
                            <thead>
                                <tr>
                                    <th class="text-nowrap" style="width:34%">{$LANG->getModule('content_cat')}</th>
                                    <th class="text-center text-nowrap" style="width:11%">
                                        <button type="button" class="btn btn-link p-0 text-reset text-decoration-none fw-semibold"
                                                data-toggle="toggle-admin-column" data-target="add_content">{$LANG->getModule('permissions_add_content')}</button>
                                    </th>
                                    <th class="text-center text-nowrap" style="width:11%">
                                        <button type="button" class="btn btn-link p-0 text-reset text-decoration-none fw-semibold"
                                                data-toggle="toggle-admin-column" data-target="app_content">{$LANG->getModule('permissions_app_content')}</button>
                                    </th>
                                    <th class="text-center text-nowrap" style="width:11%">
                                        <button type="button" class="btn btn-link p-0 text-reset text-decoration-none fw-semibold"
                                                data-toggle="toggle-admin-column" data-target="pub_content">{$LANG->getModule('permissions_pub_content')}</button>
                                    </th>
                                    <th class="text-center text-nowrap" style="width:11%">
                                        <button type="button" class="btn btn-link p-0 text-reset text-decoration-none fw-semibold"
                                                data-toggle="toggle-admin-column" data-target="edit_content">{$LANG->getModule('permissions_edit_content')}</button>
                                    </th>
                                    <th class="text-center text-nowrap" style="width:11%">
                                        <button type="button" class="btn btn-link p-0 text-reset text-decoration-none fw-semibold"
                                                data-toggle="toggle-admin-column" data-target="del_content">{$LANG->getModule('permissions_del_content')}</button>
                                    </th>
                                    <th class="text-center text-nowrap" style="width:11%">
                                        <button type="button" class="btn btn-link p-0 text-reset text-decoration-none fw-semibold"
                                                data-toggle="toggle-admin-column" data-target="admin_content">{$LANG->getModule('permissions_admin')}</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$CATEGORY_PERMISSIONS item=row}
                                <tr>
                                    <td><div style="padding-left:{$row.padding}px">{$row.title}</div></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" name="add_content[]" value="{$row.catid}"{if $row.add_content} checked{/if}></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" name="app_content[]" value="{$row.catid}"{if $row.app_content} checked{/if}></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" name="pub_content[]" value="{$row.catid}"{if $row.pub_content} checked{/if}></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" name="edit_content[]" value="{$row.catid}"{if $row.edit_content} checked{/if}></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" name="del_content[]" value="{$row.catid}"{if $row.del_content} checked{/if}></td>
                                    <td class="text-center"><input class="form-check-input" type="checkbox" name="admin_content[]" value="{$row.catid}"{if $row.is_admin} checked{/if}></td>
                                </tr>
                                {foreachelse}
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                                </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top text-center">
                <input type="hidden" name="save" value="1">
                <input type="hidden" name="userid" value="{$EDIT_USER.userid}">
                <input type="hidden" name="checkss" value="{$CHECKSS}">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                </button>
            </div>
        </div>
    </form>
    {/if}
    {/if}
{elseif $VIEW_MODE == 'module'}
    <div class="alert alert-info" role="alert">{$LANG->getModule('admin_module_for_user')}</div>
{else}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('admin_cat_for_user')}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width:34%">{$LANG->getModule('content_cat')}</th>
                            <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('permissions_add_content')}</th>
                            <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('permissions_app_content')}</th>
                            <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('permissions_pub_content')}</th>
                            <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('permissions_edit_content')}</th>
                            <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('permissions_del_content')}</th>
                            <th class="text-center text-nowrap" style="width:11%">{$LANG->getModule('permissions_admin')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$READONLY_ROWS item=row}
                        <tr>
                            <td><div style="padding-left:{$row.padding}px">{$row.title}</div></td>
                            <td class="text-center">{if $row.add_content}<i class="fa-solid fa-check text-success"></i>{else}<i class="fa-solid fa-xmark text-muted"></i>{/if}</td>
                            <td class="text-center">{if $row.app_content}<i class="fa-solid fa-check text-success"></i>{else}<i class="fa-solid fa-xmark text-muted"></i>{/if}</td>
                            <td class="text-center">{if $row.pub_content}<i class="fa-solid fa-check text-success"></i>{else}<i class="fa-solid fa-xmark text-muted"></i>{/if}</td>
                            <td class="text-center">{if $row.edit_content}<i class="fa-solid fa-check text-success"></i>{else}<i class="fa-solid fa-xmark text-muted"></i>{/if}</td>
                            <td class="text-center">{if $row.del_content}<i class="fa-solid fa-check text-success"></i>{else}<i class="fa-solid fa-xmark text-muted"></i>{/if}</td>
                            <td class="text-center">{if $row.is_admin}<i class="fa-solid fa-check text-success"></i>{else}<i class="fa-solid fa-xmark text-muted"></i>{/if}</td>
                        </tr>
                        {foreachelse}
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{/if}
