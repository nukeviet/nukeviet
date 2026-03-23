{if empty($OAUTH_LIST)}
<div class="alert alert-info">{$LANG->getModule('user_oauthmanager_empty')}</div>
{else}
<div class="card"
     data-userid="{$USERID}"
     data-checkss="{$CHECKSS}">
    <div class="card-header">
        <h5 class="card-title mb-0">{$LANG->getModule('user_oauthmanager_list')}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:30%">{$LANG->getModule('user_oauthmanager_gate')}</th>
                        <th class="text-nowrap">{$LANG->getModule('user_oauthmanager_email')}</th>
                        <th class="text-nowrap text-center" style="width:10%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$OAUTH_LIST item=oauth}
                    <tr>
                        <td>{$oauth.openid}</td>
                        <td>{$oauth.email_or_id}</td>
                        <td class="text-center text-nowrap">
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-one-oauth"
                                    data-opid="{$oauth.opid}"
                                    data-msgconfirm="{$LANG->getModule('memberlist_deleteconfirm')}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                                {$LANG->getGlobal('delete')}
                            </button>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top text-end">
        <button type="button" class="btn btn-sm btn-danger"
                data-toggle="delete-all-oauth"
                data-msgconfirm="{$LANG->getModule('memberlist_deleteconfirm')}">
            <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getModule('user_oauthmanager_deleteall')}
        </button>
    </div>
</div>
{/if}
