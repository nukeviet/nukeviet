<h1 class="h3 mb-3">{$LANG->getModule('group_manage')}</h1>
<div class="card mb-3">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-nowrap" style="width:46%">{$LANG->getModule('title')}</th>
                    <th class="text-nowrap" style="width:18%">{$LANG->getModule('add_time')}</th>
                    <th class="text-nowrap" style="width:18%">{$LANG->getModule('exp_time')}</th>
                    <th class="text-nowrap text-center" style="width:18%">{$LANG->getModule('users')}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$GROUPS item=group}
                <tr>
                    <td>
                        <a href="{$group.link_userlist}" title="{$LANG->getModule('users')}" class="text-break">{$group.title}</a>
                    </td>
                    <td class="text-nowrap">{$group.add_time|ddatetime}</td>
                    <td class="text-nowrap">
                        {if $group.exp_time}{$group.exp_time|ddatetime}{else}{$LANG->getGlobal('indefinitely')}{/if}
                    </td>
                    <td class="text-nowrap text-center">{$group.numbers|dnumber}</td>
                </tr>
                {foreachelse}
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('error_users_not_found')}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{if not empty($NAVS)}
<ul class="list-inline">
    {foreach from=$NAVS item=nav}
    <li class="list-inline-item text-nowrap me-3"><a href="{$nav.href}"><i class="fa-solid fa-caret-right"></i> {$nav.title}</a></li>
    {/foreach}
</ul>
{/if}
