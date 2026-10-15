<form id="cat-admins-form" method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    {if $HAS_SUBCAT and not empty($USERS_LIST)}
    <div class="alert alert-info" role="alert">{$LANG->getModule('cat_admins_subcat_note')}</div>
    {/if}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('cat_admins')}: {$CAT_TITLE}</h5>
        </div>
        <div class="card-body">
            {if empty($USERS_LIST)}
            <div class="alert alert-info mb-0" role="alert">{$LANG->getModule('cat_admins_no_user')}</div>
            {else}
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width:34%">{$LANG->getModule('admin_username')}</th>
                            <th class="text-center text-nowrap" style="width:11%">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" title="{$LANG->getModule('permissions_toggle_column')}"
                                        data-toggle="toggle-admin-column" data-target="add_content">{$LANG->getModule('permissions_add_content')}</button>
                            </th>
                            <th class="text-center text-nowrap" style="width:11%">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" title="{$LANG->getModule('permissions_toggle_column')}"
                                        data-toggle="toggle-admin-column" data-target="app_content">{$LANG->getModule('permissions_app_content')}</button>
                            </th>
                            <th class="text-center text-nowrap" style="width:11%">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" title="{$LANG->getModule('permissions_toggle_column')}"
                                        data-toggle="toggle-admin-column" data-target="pub_content">{$LANG->getModule('permissions_pub_content')}</button>
                            </th>
                            <th class="text-center text-nowrap" style="width:11%">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" title="{$LANG->getModule('permissions_toggle_column')}"
                                        data-toggle="toggle-admin-column" data-target="edit_content">{$LANG->getModule('permissions_edit_content')}</button>
                            </th>
                            <th class="text-center text-nowrap" style="width:11%">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" title="{$LANG->getModule('permissions_toggle_column')}"
                                        data-toggle="toggle-admin-column" data-target="del_content">{$LANG->getModule('permissions_del_content')}</button>
                            </th>
                            <th class="text-center text-nowrap" style="width:11%">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold" title="{$LANG->getModule('permissions_toggle_column')}"
                                        data-toggle="toggle-admin-column" data-target="admin">{$LANG->getModule('permissions_admin')}</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$USERS_LIST item=row}
                        <tr>
                            <td>
                                <strong>{$row.username}</strong>
                                <div class="small text-muted">{$row.full_name}</div>
                            </td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="add_content[]" value="{$row.userid}"{if $row.add_content} checked{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="app_content[]" value="{$row.userid}"{if $row.app_content} checked{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="pub_content[]" value="{$row.userid}"{if $row.pub_content} checked{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="edit_content[]" value="{$row.userid}"{if $row.edit_content} checked{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="del_content[]" value="{$row.userid}"{if $row.del_content} checked{/if}></td>
                            <td class="text-center"><input class="form-check-input" type="checkbox" name="admin[]" value="{$row.userid}"{if $row.admin} checked{/if}></td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
            {/if}
        </div>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="catid" value="{$CATID}">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            {if not empty($USERS_LIST)}
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
            </button>
            {/if}
            <a href="{$CAT_LIST_URL}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('cat_admins_back')}
            </a>
        </div>
    </div>
</form>
