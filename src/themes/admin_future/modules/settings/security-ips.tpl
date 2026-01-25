{if not empty($IPS)}
{foreach from=$IPS item=ip}
<li class="list-group-item{if $ip.status eq 0} text-muted{/if}">
    <div title="{$ip.status_text}"><i class="fa-solid fa-{$ip.status eq 2 ? 'pause' : ($ip.status eq 1 ? 'play' : 'stop')}"></i> <strong>{$ip.ip}</strong></div>
    <p class="small mb-1">({$ip.note})</p>
    <div class="hstack gap-2">
        <button class="btn btn-secondary btn-sm" title="{$LANG->getModule('ip_edit')}" data-toggle="edit_ip" data-id="{$ip.id}"><i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}</button>
        <button class="btn btn-secondary btn-sm" title="{$LANG->getModule('ip_delete')}" data-toggle="del_ip" data-id="{$ip.id}"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getGlobal('delete')}</button>
    </div>
</li>
{/foreach}
{/if}
<li class="list-group-item text-center">
    <button type="button" class="btn btn-primary" data-toggle="add_ip" title="{$LANG->getModule('ip_add')}">{$LANG->getModule('ip_add')}</button>
</li>
