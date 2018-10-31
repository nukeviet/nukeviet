<div class="card card-table card-footer-nav">
    <div class="card-header">
        <a href="{$BACKUPNOW}" class="btn btn-primary">{$LANG->get('download_now')}</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="db-tables">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:1%">{$LANG->get('file_nb')}</th>
                        <th class="text-nowrap">{$LANG->get('file_name')}</th>
                        <th class="text-nowrap">{$LANG->get('file_size')}</th>
                        <th class="text-nowrap">{$LANG->get('file_time')}</th>
                        <th class="text-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    {for $key=0 to $NUM_FILES}
                    {assign var=index value=$NUM_FILES-$key}
                    {assign var=filetime value=$ARRAY_TIME[$index]}
                    <tr>
                        <td>{$key+1}</td>
                        <td>{$DATA[$filetime]['mc'][2]}{$DATA[$filetime]['mc'][3]}</td>
                        <td>{$DATA[$filetime]['filesize']|byte2text}</td>
                        <td>{"l d/m/Y h:i:s A"|date:$filetime}</td>
                        <td class="text-right text-nowrap">
                            <a href="{$BASE_URL}=getfile&amp;filename={$DATA[$filetime]['file']}&amp;checkss={"`$DATA[$filetime]['file']``$NV_CHECK_SESSION`"|md5}" class="btn btn-sm btn-hspace btn-secondary"><i class="icon icon-left fas fa-download"></i> {$LANG->get('download')}</a>
                            <a href="{$BASE_URL}=delfile&amp;filename={$DATA[$filetime]['file']}&amp;checkss={"`$DATA[$filetime]['file']``$NV_CHECK_SESSION`"|md5}" class="btn btn-sm btn-danger" onclick="return confirm(nv_is_del_confirm[0]);"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/for}
                </tbody>
            </table>
        </div>
    </div>
</div>
