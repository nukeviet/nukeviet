{foreach from=$LIST_USERS key=type item=list_data}
{if $type eq 'pending'}
<div id="id_pending">
    <h3 class="myh3">{$list_data.title}</h3>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getModule('userid')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getGlobal('username')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('nametitle')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('email')}</th>
                            <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$list_data.loop item=row}
                        <tr>
                            <td class="text-center">{$row.userid}</td>
                            <td><a title="{$LANG->getModule('detail')}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;userid={$row.userid}">{$row.username}</a></td>
                            <td>{$row.full_name}</td>
                            <td><a href="mailto:{$row.email}">{$row.email}</a></td>
                            <td class="text-center">
                            {if $row.show_tools}
                            <i class="fa-solid fa-check"></i> <a class="approved" href="javascript:void(0);" data-id="{$row.userid}">{$LANG->getModule('approved')}</a>
                            <i class="fa-solid fa-times"></i> <a class="denied" href="javascript:void(0);" data-id="{$row.userid}">{$LANG->getModule('denied')}</a>
                            {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if !empty($list_data.page)}
        <div class="card-footer border-top">
            <div class="text-center pagination-wrap">{$list_data.page}</div>
        </div>
        {/if}
    </div>
</div>
{elseif $type eq 'leaders'}
<div id="id_leaders">
    <h3 class="myh3">{$list_data.title}</h3>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getModule('userid')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getGlobal('username')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('nametitle')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('email')}</th>
                            <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$list_data.loop item=row}
                        <tr>
                            <td class="text-center">{$row.userid}</td>
                            <td><a title="{$LANG->getModule('detail')}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;userid={$row.userid}">{$row.username}</a></td>
                            <td>{$row.full_name}</td>
                            <td><a href="mailto:{$row.email}">{$row.email}</a></td>
                            <td class="text-center">
                            {if $row.show_tools}
                            <i class="fa-solid fa-star-half"></i> <a class="demote" href="javascript:void(0);" data-id="{$row.userid}">{$LANG->getModule('demote')}</a>
                            <i class="fa-solid fa-trash"></i> <a class="deleteleader" href="javascript:void(0);" data-userid="{$row.userid}">{$LANG->getModule('exclude_user2')}</a>
                            {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if !empty($list_data.page)}
        <div class="card-footer border-top">
            <div class="text-center pagination-wrap">{$list_data.page}</div>
        </div>
        {/if}
    </div>
</div>
{elseif $type eq 'members'}
<div id="id_members">
    <h3 class="myh3">{$list_data.title}</h3>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getModule('userid')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getGlobal('username')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('nametitle')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('email')}</th>
                            <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$list_data.loop item=row}
                        <tr>
                            <td class="text-center">{$row.userid}</td>
                            <td><a title="{$LANG->getModule('detail')}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;userid={$row.userid}">{$row.username}</a></td>
                            <td>{$row.full_name}</td>
                            <td><a href="mailto:{$row.email}">{$row.email}</a></td>
                            <td class="text-center text-nowrap">
                                {if $row.show_tools}
                                <button class="text-nowrap btn btn-sm btn-secondary promote" data-id="{$row.userid}">
                                    <i class="fa-solid fa-star text-warning" data-icon="fa-star"></i> {$LANG->getModule('promote')}
                                </button>
                                <button class="text-nowrap btn btn-sm btn-secondary deletemember" data-id="{$row.userid}">
                                    <i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i> {$LANG->getModule('exclude_user2')}
                                </button>
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if !empty($list_data.page)}
        <div class="card-footer border-top">
            <div class="text-center pagination-wrap">{$list_data.page}</div>
        </div>
        {/if}
    </div>
</div>
{/if}
{/foreach}
