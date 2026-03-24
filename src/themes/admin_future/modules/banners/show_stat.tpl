<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">{$CAPTION}</h5>
        <span class="badge text-bg-secondary">{$SUM_FORMAT}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:50%">{$LANG->getModule('stats_views_ads')}</th>
                        <th class="text-nowrap" style="width:35%">{$LANG->getModule('stats_views')}</th>
                        <th class="text-end text-nowrap" style="width:15%">{$SUM_FORMAT}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$CTS item=row}
                    <tr>
                        <td>
                            {if !empty($row.drill_down)}
                            <a href="#" class="link-primary"
                               data-toggle="show-list-stat"
                               data-bid="{$row.drill_down.bid}"
                               data-month="{$row.drill_down.month}"
                               data-ext="{$row.drill_down.ext}"
                               data-val="{$row.drill_down.val|escape:'html'}"
                               data-container="statistic">
                                {$row.label}
                            </a>
                            {else}
                            {$row.label}
                            {/if}
                        </td>
                        <td>
                            {if $row.percent > 0}
                            <progress class="w-100" max="100" value="{$row.percent}"></progress>
                            <div class="small text-muted mt-1">{$row.percent}%</div>
                            {else}
                            <span class="text-muted">0%</span>
                            {/if}
                        </td>
                        <td class="text-end text-nowrap">{$row.count_format}</td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">{$LANG->getGlobal('no_data')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
