{if not empty($NO_EXTRACT)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('get_update_cantunzip')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$NO_EXTRACT item=file}
                    <tr>
                        <td class="text-danger">{$file}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{elseif not empty($ERROR_CREATE_FOLDER)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('get_update_warning_permission_folder')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$ERROR_CREATE_FOLDER item=file}
                    <tr>
                        <td class="text-danger">{$file}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{elseif not empty($ERROR_MOVE_FOLDER)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('get_update_error_movefile')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$ERROR_MOVE_FOLDER item=file}
                    <tr>
                        <td class="text-danger">{$file}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{else}
<div class="card">
    <div class="card-body text-center pt-4">
        <h4 class="mt-0">{$LANG->get('get_update_okunzip')}</h4>
        <a href="{$URL_GO}" title="{$LANG->get('get_update_okunzip_link')}">{$LANG->get('get_update_okunzip_link')}</a>
    </div>
</div>
<script type="text/javascript">
setTimeout("redirect_page()", 5000);
function redirect_page() {
    window.location = "{$URL_GO}";
}
</script>
{/if}
