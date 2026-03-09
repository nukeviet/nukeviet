{assign var="themeIcons" value=[
    'admin' => "<i class=\"fa-solid fa-cube\" title=\"{$LANG->getModule('extType_admin')}\"></i>",
    'sys' => "<i class=\"fa-solid fa-cubes\" title=\"{$LANG->getModule('extType_sys')}\"></i>"
]}
<div class="card">
    <div class="card-header">
        <div class="d-flex gap-2 justify-content-between align-items-center">
            {if $GCONFIG.extension_setup eq 1 or $GCONFIG.extension_setup eq 3}
            <div>
                <div class="dropdown">
                    <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">{$LANG->getModule('install_package')}</button>
                    <div class="dropdown-menu p-3" style="--bs-dropdown-min-width: 280px;">
                        {if empty($SYS_INFO.zlib_support)}
                        <span class="text-danger fw-medium">{$LANG->getGlobal('error_zlib_support')}</span>
                        {elseif not in_array($smarty.const.NV_CLIENT_IP, ($GCONFIG.extension_setup_ips ?? []), true)}
                        <span class="text-danger fw-medium">{$LANG->getModule('install_ips', $smarty.const.NV_CLIENT_IP)}.</span>
                        {else}
                        <form id="formSubmitExt" method="post" enctype="multipart/form-data" data-error-choose="{$LANG->getModule('install_error_nofile')}" data-error-type="{$LANG->getModule('install_error_filetype')}" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=upload">
                            <input type="hidden" name="checksess" value="{$SUBMIT_CHECKSESS}">
                            <div class="input-group">
                                <input type="file" class="form-control" name="extfile">
                                <button type="submit" class="btn btn-primary">{$LANG->getModule('install_submit')}</button>
                            </div>
                        </form>
                        <div class="mt-3">
                            {assign var="mode" value=($GCONFIG.extension_upload_mode ?? 0)}
                            {if $mode eq 2}
                            <div class="alert alert-danger mb-0" role="alert">{$LANG->getModule('uncontrolled_mode_warning')}</div>
                            {elseif $mode eq 1}
                            <div class="alert alert-danger mb-0" role="alert">{$LANG->getModule('loose_mode_warning')}</div>
                            {else}
                            <i class="fa-solid fa-triangle-exclamation text-warning"></i> {$LANG->getModule('install_package_alert')}
                            {/if}
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
            {/if}
            <div>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <i class="fa-solid fa-cubes"></i> {$LANG->getModule('extType_sys')}
                    </li>
                    <li class="list-inline-item">
                        <i class="fa-solid fa-cube"></i> {$LANG->getModule('extType_admin')}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('ext_type')}</th>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('extname')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('file_version')}</th>
                        <th class="text-nowrap" style="width: 40%;">{$LANG->getModule('author')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>{$row.type}</td>
                        <td>
                            <div class="d-flex justify-content-between gap-1">
                                <div>{$row.basename}</div>
                                <div class="d-flex gap-1">
                                    {foreach from=$row.icon item=icon}
                                    {$themeIcons[$icon]}
                                    {/foreach}
                                </div>
                            </div>
                        </td>
                        <td>{$row.version}</td>
                        <td>
                            <div class="d-flex justify-content-between gap-1">
                                <div>{$row.author|encodehtml}</div>
                                <div class="d-flex gap-2">
                                    <a href="{$row.url_package}" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('package')}" data-bs-trigger="hover" aria-label="{$LANG->getModule('package')}"><i class="fa-solid fa-lg fa-file-zipper"></i></a>
                                    {if $row.delete_allowed}
                                    <a href="{$row.url_delete}" data-bs-toggle="tooltip" data-bs-title="{$LANG->getGlobal('delete')}" data-bs-trigger="hover" aria-label="{$LANG->getGlobal('delete')}" data-toggle="deleteExtension" data-confirm="{$LANG->getModule('delele_ext_confirm')}"><i class="fa-solid fa-lg fa-trash text-danger" data-icon="fa-trash"></i></a>
                                    {/if}
                                </div>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
