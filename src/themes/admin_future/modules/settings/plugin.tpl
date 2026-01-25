<!-- BEGIN: main-->
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
    <div class="card-header fw-medium fs-5">
        <i class="fa-solid fa-plug"></i> {$LANG->getModule('plugin_integrated')}
    </div>
    <div class="card-body">
        <div class="mb-1">{$LANG->getModule('plugin_note')}</div>
        <div class="mb-2">
            <form action="{$smarty.const.NV_BASE_ADMINURL}index.php" method="get" id="formSearchPlugin">
                <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
                <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
                <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
                <div class="hstack gap-2">
                    <label for="element_search_area">{$LANG->getModule('plugin_viewarea')}:</label>
                    <select name="a" class="form-select w-auto mw-100" id="element_search_area">
                        <option value="">--</option>
                        {foreach from=$ARRAY_AREAS item=area}
                        <option value="{$area}"{if $area eq $SEARCH.area} selected{/if}>{$area}</option>
                        {/foreach}
                    </select>
                </div>
            </form>
        </div>
        {if not empty($SEARCH.area)}
        <div class="text-info">{$LANG->getModule('plugin_note_order')}.</div>
        {/if}
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-1">
                <thead>
                    <tr>
                        {if not empty($SEARCH.area)}
                        <th class="text-center text-nowrap" style="width:1%;">{$LANG->getModule('plugin_number')}</th>
                        {/if}
                        <th class="text-nowrap" style="width:33%;">{$LANG->getModule('plugin_area')}</th>
                        <th class="text-nowrap" style="width:32%;">{$LANG->getModule('plugin_file')}</th>
                        <th class="text-nowrap" style="width:33%;">{$LANG->getModule('plugin_type')}</th>
                        <th class="text-center text-nowrap" style="width:1%;">{$LANG->getModule('plugin_func')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        {if not empty($SEARCH.area)}
                        <td class="text-center">
                            <select id="weight_{$row.pid}" class="form-select fw-100" data-toggle="change_plugin_weight" data-pid="{$row.pid}" data-weight="{$row.weight}" data-checkss="{$smarty.const.NV_CHECK_SESSION}">
                                {for $weight=1 to $MAX_WEIGHT}
                                <option value="{$weight}"{if $weight eq $row.weight} selected{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        {/if}
                        <td class="text-break">
                            <strong>{$row.hook_module}{$row.plugin_area}</strong>
                        </td>
                        <td class="text-break">{$row.plugin_file}</td>
                        <td class="text-break">
                            {if empty($row.plugin_module_name)}{$LANG->getModule('plugin_type_sys')}{else}
                            {$LANG->getModule('plugin_type_module')}: {$row.plugin_module_name}
                            {/if}
                        </td>
                        <td class="text-center text-nowrap">
                            {*
                            Plugin trong thư mục modules/ thì chỉ xóa khi xóa module để đảm bảo module hoạt động bình thường
                            Plugin trong thư mục includes/plugin là phần cấu hình có thể xóa/thêm tự do
                            *}
                            {if empty($row.plugin_module_file)}
                            <button class="btn btn-sm btn-danger" data-toggle="nv_del_plugin" data-pid="{$row.pid}" data-checkss="{$smarty.const.NV_CHECK_SESSION}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getModule('isdel')}</button>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{if not empty($AVAILABLE_PLUGINS)}
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
    <div class="card-header fw-medium fs-5">
        <i class="fa-solid fa-list-ul"></i> <strong>{$LANG->getModule('plugin_available')}</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-1">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('plugin_area')}</th>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('plugin_file')}</th>
                        <th class="text-center text-nowrap" style="width: 25%;">{$LANG->getModule('plugin_type')}</th>
                        <th class="text-center text-nowrap" style="width: 24%;">{$LANG->getModule('act')}</th>
                        <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('plugin_func')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$AVAILABLE_PLUGINS key=hook_key item=rows}
                    {foreach from=$rows key=file_key item=row}
                    <tr>
                        <td class="text-break">
                            <strong>{join($row.area, ', ')}</strong>
                        </td>
                        <td class="text-break">{$row.file}</td>
                        <td class="text-center text-nowrap">
                            {if empty($row.receive_module)}{$LANG->getModule('plugin_type_sys')}{else}
                            {$LANG->getModule('plugin_type_module')}: {$row.receive_module}
                            {/if}
                        </td>
                        <td class="text-center text-nowrap">
                            {if empty($row.area)}{$LANG->getModule('plugin_status_error')}{else}{$LANG->getModule('plugin_status_ok')}{/if}
                        </td>
                        <td class="text-center text-nowrap">
                            {if not empty($row.area)}
                            <button data-click="plintegrate" data-hkey="{$hook_key}" data-fkey="{$file_key}" data-hm="{$row.hook_module}" data-rm="{$row.receive_module}" class="btn btn-sm btn-primary"><i class="fa-solid fa-gear" data-icon="fa-gear"></i> {$LANG->getModule('plugin_integrate')}</button>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- START FORFOOTER -->
<div class="modal fade" tabindex="-1" role="dialog" id="mdPluginConfig" data-bs-backdrop="static" aria-labelledby="mdPluginConfigLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title text-break fw-medium fs-5" id="mdPluginConfigLabel" data-area="title"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="vstack gap-3">
                    <div data-area="hook_module">
                        <label for="mdPluginConfigH" class="form-label">{$LANG->getModule('plugin_choose_hook_module')}:</label>
                        <select class="form-select" id="mdPluginConfigH" name="hook_module">
                        </select>
                    </div>
                    <div data-area="receive_module">
                        <label for="mdPluginConfigR" class="form-label">{$LANG->getModule('plugin_choose_receive_module')}:</label>
                        <select class="form-select" id="mdPluginConfigR" name="receive_module">
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" data-toggle="submitIntegratePlugin"><i class="fa-solid fa-gear" data-icon="fa-gear"></i> {$LANG->getModule('plugin_integrate')}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('close')}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
{/if}
