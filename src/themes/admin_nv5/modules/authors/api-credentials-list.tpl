{if empty($REMOTE_API_ACCESS)}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('api_remote_off', $URL_CONFIG)}</div>
</div>
{/if}
<div class="card card-table">
    <div class="card-header">
        <div class="text-right">
            <a href="{$LINK_ADD}" class="btn btn-success">{$LANG->get('api_cr_add')}</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width:26%;">{$LANG->get('api_cr_credential_ident')}</th>
                        <th style="width:15%;">{$LANG->get('api_cr_title')}</th>
                        <th style="width:20%;">{$LANG->get('users')}</th>
                        <th style="width:15%;">{$LANG->get('api_cr_roles1')}</th>
                        <th style="width:12%;">{$LANG->get('api_cr_last_access')}</th>
                        <th style="width:12%;" class="text-right text-nowrap">{$LANG->get('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>{$row.credential_ident}</td>
                        <td>{$row.credential_title}</td>
                        <td>{for $lev=1 to 3}<i class="fas fa-star{if $lev <= (4 - $row.lev)} text-warning{else} text-muted{/if}"></i>{/for} {$row.username}</td>
                        <td>{", "|implode:$row.api_roles_show}</td>
                        <td>{if empty($row.last_access)}{$LANG->get('api_cr_last_access_none')}{else}{"H:i d/m/Y"|date:$row.last_access}{/if}</td>
                        <td class="text-right text-nowrap">
                            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}&amp;credential_ident={$row.credential_ident}" class="btn btn-sm btn-hspace btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" data-id="{$row.credential_ident}" data-toggle="apicerdel" class="btn btn-sm btn-danger"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
