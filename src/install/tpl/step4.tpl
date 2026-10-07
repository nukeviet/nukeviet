<p>{$LANG->getModule('if_server')} <span class="text-danger">{$LANG->getModule('not_compatible')}</span>. {$LANG->getModule('please_checkserver')}.</p>
<div class="table-responsive mb-4">
    <table id="checkserver" class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 45%">{$LANG->getModule('server_request')}</th>
                <th style="width: 35%">{$LANG->getModule('note')}</th>
                <th class="text-end">{$LANG->getModule('result')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$REQUESTS item=row}
            <tr>
                <td class="fw-medium">{$row.name}</td>
                <td>{$row.note}</td>
                <td class="text-end"><span class="badge {if $row.ok}text-bg-success{else}text-bg-danger{/if}">{$row.result}</span></td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<div class="table-responsive">
    <table id="recommend" class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 45%">{$LANG->getModule('request_more')}</th>
                <th style="width: 35%">{$LANG->getModule('note')}</th>
                <th class="text-end">{$LANG->getModule('result')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$SUPPORTS item=row}
            <tr>
                <td class="fw-medium">{$row.name}</td>
                <td>{$row.note}</td>
                <td class="text-end"><span class="badge {if $row.ok}text-bg-success{else}text-bg-warning{/if}">{$row.result}</span></td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$STEP_URL}3"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    {if $NEXTSTEP}
    <span class="next_step"><a class="btn btn-primary" href="{$STEP_URL}5">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
    {/if}
</div>
