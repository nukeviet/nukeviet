<div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
        <thead>
            <tr>
                <th>{$LANG->getModule('username')}/{$LANG->getModule('email')}</th>
                <th>{$LANG->getModule('regdate')}</th>
                <th style="width:1%"></th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$USERS item=user}
            <tr>
                <td class="text-break">
                    <strong>{$user.username}</strong> <span class="text-muted">#{$user.userid}</span><br>
                    <small class="text-muted">{$user.email}</small>
                </td>
                <td><small>{$user.regdate}</small></td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="groupActiveUser" data-userid="{$user.userid}"
                        title="{$LANG->getModule('active')}" aria-label="{$LANG->getModule('active')}">
                        <i class="fa-solid fa-check" data-icon="fa-check"></i>
                    </button>
                </td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="3" class="text-center text-muted py-4">
                    <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('noresult')}
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
{if $GENERATE_PAGE}
<div class="pagination-wrap text-center mt-3">
    {$GENERATE_PAGE}
</div>
{/if}
