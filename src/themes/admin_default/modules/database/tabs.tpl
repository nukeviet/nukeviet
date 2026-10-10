<div class="row g-4">
    <div class="col-lg-6">
        <div class="card bg-body-tertiary">
            <div class="card-header fs-5 fw-medium">{$LANG->getModule('table_caption', $TABLENAME)}</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_name')}</div>
                        <div class="col-7 text-break">{$TABLENAME}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_type')}</div>
                        <div class="col-7 text-break">{$DATA.engine ?? $DATA.type}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('row_format')}</div>
                        <div class="col-7 text-break">{$DATA.row_format}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_size')}</div>
                        <div class="col-7 text-break">{($DATA.data_length + $DATA.index_length)|displaySize}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_max_size')}</div>
                        <div class="col-7 text-break">{if not empty($DATA.max_data_length)}{((float)$DATA.max_data_length)|displaySize}{else}n/a{/if}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_datafree')}</div>
                        <div class="col-7 text-break">{if not empty($DATA.data_free)}{((int)$DATA.data_free)|displaySize}{else}0{/if}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_numrow')}</div>
                        <div class="col-7 text-break">{$DATA.rows}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_auto_increment')}</div>
                        <div class="col-7 text-break">{$DATA.auto_increment ?? 'n/a'}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_create_time')}</div>
                        <div class="col-7 text-break">{if not empty($DATA.create_time)}{strtotime($DATA.create_time)|displayDate}{else}n/a{/if}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_update_time')}</div>
                        <div class="col-7 text-break">{if not empty($DATA.update_time)}{strtotime($DATA.update_time)|displayDate}{else}n/a{/if}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_check_time')}</div>
                        <div class="col-7 text-break">{if not empty($DATA.check_time)}{strtotime($DATA.check_time)|displayDate}{else}n/a{/if}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row g-2">
                        <div class="col-5 fw-medium">{$LANG->getModule('table_charset')}</div>
                        <div class="col-7 text-break">{$DATA.collation}</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-tabs">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a class="nav-link text-truncate active" data-bs-toggle="tab" id="link-php" data-tab="php" data-bs-target="#tab-php" aria-current="true" role="tab" aria-controls="tab-php" aria-selected="true" href="#">{$LANG->getModule('php_code')}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-truncate" data-bs-toggle="tab" id="link-sql" data-tab="sql" data-bs-target="#tab-sql" aria-current="false" role="tab" aria-controls="tab-sql" aria-selected="false" href="#">{$LANG->getModule('sql_code')}</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-php" role="tabpanel" aria-labelledby="link-php" tabindex="0">
                        <div class="p-3 bg-body-tertiary">
                            {$CODE_PHP}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-sql" role="tabpanel" aria-labelledby="link-sql" tabindex="0">
                        <div class="p-3 bg-body-tertiary">
                            {$CODE_SQL}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card bg-body-tertiary mt-4">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('table_row_caption', $TABLENAME)}
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('field_name')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('field_type')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('field_null')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('field_key')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('field_default')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('field_extra')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$COLUMNS key=key item=column}
                    <tr>
                        <td class="text-break">
                            {$column.field}
                        </td>
                        <td>{$column.type}</td>
                        <td>{$column.null}</td>
                        <td>{$column.key}</td>
                        <td>{$column.default}</td>
                        <td>{$column.extra}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
