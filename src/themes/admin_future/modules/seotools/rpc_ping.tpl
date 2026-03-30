<div id="rpc"
     data-load-url="{$LOAD_DATA}"
     data-msg-finish="{$LANG->getModule('rpc_finish')|escape:'html'}">
    <div class="card">
        <div class="card-header">
            {$LANG->getModule('rpc_ftitle')}
        </div>
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap">{$LANG->getModule('rpc_linkname')}</th>
                            <th class="text-center text-nowrap" style="width:10%">{$LANG->getModule('rpc_reruslt')}</th>
                            <th class="text-nowrap">{$LANG->getModule('rpc_message')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$SERVICES item=service}
                        <tr>
                            <td>
                                {if $service.icon}
                                <img src="{$IMGPATH}/{$service.icon}" alt="{$service.title}" class="me-1">
                                {else}
                                <img src="{$IMGPATH}/link.png" alt="{$service.title}" class="me-1">
                                {/if}
                                <span>{$service.title}</span>
                            </td>
                            <td class="text-center" id="res{$service.id}">
                                <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                            </td>
                            <td id="mes{$service.id}"></td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
