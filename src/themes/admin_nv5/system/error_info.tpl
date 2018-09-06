<div class="card card-table">
    <div class="card-header">
        <div class="mb-1">{$LANG->get('error_info_caption')}</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                    {foreach from=$DATA item=row}
                    <tr>
                        <td>{$ERROR_TYPE[$row['errno']][1]}</td>
                        <td><strong>{$ERROR_TYPE[$row['errno']][0]}:</strong> {$row.info} </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
