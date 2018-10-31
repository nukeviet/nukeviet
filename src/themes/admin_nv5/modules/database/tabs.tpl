<div class="row">
    <div class="col-12 col-md-6">
        <div class="card card-table">
            <div class="card-header">
                {$CAPTION}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <colgroup>
                            <col style="width: 50%">
                            <col style="width: 50%">
                        </colgroup>
                        <tbody>
                            {foreach from=$INFO item=row}
                            <tr>
                                <td>{$row.0}</td>
                                <td>{$row.1}</td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-2">
                    <a class="btn btn-secondary btn-space" href="javascript:void(0);" onclick="nv_show_highlight('php');">{$LANG->get('php_code')}</a>
                    <a class="btn btn-secondary btn-space" href="javascript:void(0);" onclick="nv_show_highlight('sql');">{$LANG->get('sql_code')}</a>
                </div>
                <pre id="my_highlight" class="py-1">{$CODEINFO}</pre>
            </div>
        </div>
    </div>
</div>
<div class="card card-table">
    <div class="card-header">
        {$RCAPTION}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-nowrap">{$LANG->get('field_name')}</th>
                        <th class="text-nowrap">{$LANG->get('field_type')}</th>
                        <th class="text-nowrap">{$LANG->get('field_null')}</th>
                        <th class="text-nowrap">{$LANG->get('field_key')}</th>
                        <th class="text-nowrap">{$LANG->get('field_default')}</th>
                        <th class="text-nowrap">{$LANG->get('field_extra')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ROWS item=row}
                    <tr>
                        <td>{$row.field}</td>
                        <td>{$row.type}</td>
                        <td>{$row.null}</td>
                        <td>{$row.key}</td>
                        <td>{$row.default}</td>
                        <td>{$row.extra}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
