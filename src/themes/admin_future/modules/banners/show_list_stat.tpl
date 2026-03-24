<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">{$CAPTION}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        {foreach from=$THEAD item=title}
                        <th class="text-nowrap">{$title}</th>
                        {/foreach}
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ROWS item=row}
                    <tr>
                        <td>{$row.click_time}</td>
                        <td>{$row.click_ip}</td>
                        <td>{$row.click_country}</td>
                        <td>{$row.click_browse_name}</td>
                        <td>{$row.click_os_name}</td>
                        <td>
                            {if !empty($row.click_ref)}
                            <a href="{$row.click_ref|escape:'html'}" target="_blank" rel="noopener noreferrer">{$LANG->getModule('select')}</a>
                            {/if}
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">{$LANG->getGlobal('no_data')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if !empty($GENERATE_PAGE)}
    <div class="card-footer border-top">
        <div class="pagination-wrap d-flex justify-content-center">{$GENERATE_PAGE}</div>
    </div>
    {/if}
</div>
