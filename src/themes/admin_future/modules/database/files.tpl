<div class="card mb-4">
    <div class="card-body">
        <a class="btn btn-primary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=download&amp;checkss={$smarty.const.NV_CHECK_SESSION}"><i class="fa-solid fa-download"></i> {$LANG->getModule('download_now')}</a>
    </div>
</div>
{if empty($ARRAY)}
<div class="alert alert-info" role="alert">{$LANG->getModule('file_backup_empty')}</div>
{else}
<div class="card border-primary border-4 border-bottom-0 border-start-0 border-end-0 pb-1">
    <div class="card-body">
        <div class="table-responsive-sm table-card">
            <table class="table table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-bg-primary text-nowrap" style="width: 5%;">{$LANG->getModule('file_nb')}</th>
                        <th class="text-bg-primary text-nowrap" style="width: 30%;">{$LANG->getModule('file_name')}</th>
                        <th class="text-bg-primary text-nowrap" style="width: 15%;">{$LANG->getModule('file_size')}</th>
                        <th class="text-bg-primary text-nowrap" style="width: 30%;">{$LANG->getModule('file_time')}</th>
                        <th class="text-bg-primary text-nowrap text-end" style="width: 20%;">{$LANG->getGlobal('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {assign var="stt" value=0 nocache}
                    {foreach from=$ARRAY key=filetime item=files}
                    {foreach from=$files key=file_index item=file}
                    {assign var="stt" value=($stt+1) nocache}
                    <tr>
                        <td>{$stt}</td>
                        <td>{$file.name}</td>
                        <td>{$file.filesize|displaySize}</td>
                        <td>{$filetime|displayTime:0:0}</td>
                        <td class="text-end text-nowrap">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;getbackup={$filetime}&amp;index={$file_index}&amp;checkss={$file.checkss}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-download"></i> {$LANG->getModule('download')}</a>
                            <a href="#" data-toggle="delBackup" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;delbackup={$filetime}&amp;index={$file_index}&amp;checkss={$file.checkss}" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
