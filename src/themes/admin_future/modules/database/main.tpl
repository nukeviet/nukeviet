<div class="card bg-body-tertiary mb-4">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('database_info', $DB.db_dbname)}
    </div>
    <div class="card-body p-0 pb-1">
        <ul class="list-group list-group-flush">
            {foreach from=$DB key=key item=value}
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule($key)}</div>
                    <div class="col-7">{$value}</div>
                </div>
            </li>
            {/foreach}
        </ul>
    </div>
</div>
<div id="show_db_tables">
    <div class="text-center">
        <i class="fa-solid fa-2x fa-spinner fa-spin-pulse"></i>
        <div>{$LANG->getGlobal('wait_page_load')}</div>
    </div>
</div>
