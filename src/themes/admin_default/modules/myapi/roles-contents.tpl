<div class="row mb-2">
    <div class="col-sm-3 text-sm-end">
        {$LANG->getModule('api_roles_allowed')}: <span class="total-api-enabled badge bg-secondary rounded-pill fs-6">{$TOTAL_API_ENABLED}</span>
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-3 mb-3 mb-sm-0">
        <div class="list-group nav nav-tabs root-api-actions" role="tablist">
            {foreach $API_TREES as $API_TREE}
            <button type="button" class="list-group-item list-group-item-action list-group-item-primary nav-item" role="tab" data-bs-toggle="tab" data-bs-target="#{$API_TREE.href}" aria-controls="{$API_TREE.href}" aria-selected="{$API_TREE.expanded}">
                <i class="fa-solid fa-folder-open"></i>&nbsp;{$API_TREE.name}
                {if !empty($API_TREE.total)}
                <span class="api-count{$API_TREE.api_checked} badge bg-secondary rounded-pill fs-6"><span class="total_api">{$API_TREE.total_api}</span>/{$API_TREE.total}</span>
                {/if}
            </button>
            {foreach $API_TREE.subs as $SUB}
                <button type="button" class="list-group-item list-group-item-action{if !empty($SUB['active'])} active{/if} nav-item d-flex justify-content-between" role="tab" data-bs-toggle="tab" data-bs-target="#{$SUB.href}" aria-controls="api-child-{$SUB.key}" aria-selected="{$SUB.expanded}">
                    {$SUB.name}
                    <span class="api-count{$SUB.api_checked} badge bg-secondary rounded-pill fs-6"><span class="total_api">{$SUB.total_api}</span>/{$SUB.total}</span>
                </button>
            {/foreach}
            {/foreach}
        </div>
    </div>
    <div class="col-sm-8 tab-content child-apis">
        {foreach $API_CONTENTS as $API_CONTENT}
        <div role="tabpanel" class="tab-pane child-apis-item{if !empty($API_CONTENT.active)} active{/if}" id="{$API_CONTENT.id}">
            <table class="table table-bordered">
                <tbody>
                    <tr class="apilist">
                        <th style="width: 1%;"><input type="checkbox" id="checkall_{$API_CONTENT.id}" class="form-check-input checkall" title="{$LANG->getModule('api_roles_checkall')}" {$API_CONTENT.checkall} aria-label="{$LANG->getGlobal('toggle_checkall')}"></th>
                        <th>{$LANG->getModule('cat_api_list')}</th>
                    </tr>
                    {foreach $API_CONTENT.apis as $API}
                    <tr class="item">
                        <td style="width: 1%;"><input type="checkbox" class="form-check-input checkitem" name="api_{$API_CONTENT.input_key}[]" id="api_{$API.cmd}" value="{$API.cmd}" aria-label="{$LANG->getGlobal('toggle_checksingle')}" {if !empty($API.checked)}checked="checked"{/if}></td>
                        <td><label for="api_{$API.cmd}" class="mb-0" role="button">{$API.cmd} - {$API.name}</label></td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {/foreach}
        <div role="tabpanel" class="tab-pane" id="empty-content"></div>
    </div>
</div>
