<div class="card bg-body-tertiary">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('tables_info', $DBNAME)}
    </div>
    <div class="card-body">
        <div class="table-responsive-xl table-card">
            <table class="table table-striped align-middle table-sticky table-db-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 1%;">
                            <input type="checkbox" data-toggle="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width: 19%;">{$LANG->getModule('table_name')}</th>
                        <th class="text-nowrap" style="width: 8.57%;">{$LANG->getModule('table_size')}</th>
                        <th class="text-nowrap" style="width: 8.57%;">{$LANG->getModule('table_max_size')}</th>
                        <th class="text-nowrap" style="width: 8.57%;">{$LANG->getModule('table_datafree')}</th>
                        <th class="text-nowrap" style="width: 8.57%;">{$LANG->getModule('table_numrow')}</th>
                        <th class="text-nowrap" style="width: 8.57%;">{$LANG->getModule('table_charset')}</th>
                        <th class="text-nowrap" style="width: 8.57%;">{$LANG->getModule('table_type')}</th>
                        <th class="text-nowrap" style="width: 8.57%;">{$LANG->getModule('table_auto_increment')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('table_create_time')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('table_update_time')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$TABLES key=key item=table}
                    <tr>
                        <td>
                            <input type="checkbox" data-toggle="checkSingle" value="{$key}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                        </td>
                        <td class="text-break">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;tab={$key}">{$key}</a>
                        </td>
                        <td>{$table.table_size}</td>
                        <td>{$table.table_max_size ?: 'n/a'}</td>
                        <td>{$table.table_datafree}</td>
                        <td>{$table.table_numrow}</td>
                        <td>{$table.table_charset}</td>
                        <td>{$table.table_type}</td>
                        <td>{$table.table_auto_increment}</td>
                        <td>{$table.table_create_time}</td>
                        <td>{$table.table_update_time}</td>
                    </tr>
                    {/foreach}
                    <tr>
                        <td>
                            <input type="checkbox" data-toggle="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </td>
                        <td colspan="10">
                            <span class="text-primary fw-medium">{$LANG->getModule('third', $DB_TABLES_COUNT, $DB_SIZE, $DB_TOTALFREE)}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" data-toggle="formDbTbls">
            <div class="row g-2">
                <div class="col-6 col-lg-3 col-xl-2">
                    <select class="form-select" name="{$smarty.const.NV_OP_VARIABLE}" data-toggle="acOp">
                        <option value="download">{$LANG->getModule('download')}</option>
                        <option value="savefile">{$LANG->getModule('savefile')}</option>
                        <option value="optimize">{$LANG->getModule('optimize')}</option>
                    </select>
                </div>
                <div class="col-6 col-lg-3 col-xl-2">
                    <select class="form-select" name="type" data-toggle="acType">
                        <option value="all">{$LANG->getModule('download_all')}</option>
                        <option value="str">{$LANG->getModule('download_str')}</option>
                    </select>
                </div>
                <div class="col-6 col-lg-3 col-xl-2">
                    <select class="form-select" name="ext" data-toggle="acExt">
                        <option value="sql">{$LANG->getModule('ext_sql')}</option>
                        <option value="gz">{$LANG->getModule('ext_gz')}</option>
                    </select>
                </div>
                <div class="col-6 col-lg-3 col-xl-6">
                    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                    <button data-toggle="actionDbTbls" type="submit" class="btn btn-primary"><i class="fa-solid fa-play" data-icon="fa-play"></i> {$LANG->getModule('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
