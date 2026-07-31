<div class="card mb-3">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="fa-solid fa-users text-primary"></i>
        <h1 class="h6 mb-0">{$LANG->getModule('listusers')}</h1>
    </div>
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-nowrap" style="width:60%">
                        <a href="{$ORDER_LINKS.username}" class="text-decoration-none text-reset">
                            {$LANG->getModule('account')}
                            {if $ORDERBY == 'username'}
                            <i class="fa-solid {if $SORTBY == 'DESC'}fa-arrow-down-z-a{else}fa-arrow-down-a-z{/if} text-primary"></i>
                            {else}
                            <i class="fa-solid fa-sort text-body-tertiary"></i>
                            {/if}
                        </a>
                    </th>
                    <th class="text-nowrap" style="width:20%">
                        <a href="{$ORDER_LINKS.gender}" class="text-decoration-none text-reset">
                            {$LANG->getModule('gender')}
                            {if $ORDERBY == 'gender'}
                            <i class="fa-solid {if $SORTBY == 'DESC'}fa-arrow-down-z-a{else}fa-arrow-down-a-z{/if} text-primary"></i>
                            {else}
                            <i class="fa-solid fa-sort text-body-tertiary"></i>
                            {/if}
                        </a>
                    </th>
                    <th class="text-nowrap" style="width:20%">
                        <a href="{$ORDER_LINKS.regdate}" class="text-decoration-none text-reset">
                            {$LANG->getModule('regdate')}
                            {if $ORDERBY == 'regdate'}
                            <i class="fa-solid {if $SORTBY == 'DESC'}fa-arrow-down-9-1{else}fa-arrow-down-1-9{/if} text-primary"></i>
                            {else}
                            <i class="fa-solid fa-sort text-body-tertiary"></i>
                            {/if}
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$USERS item=user}
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            {if $user.avata}
                            <img src="{$user.avata}" alt="{$user.username}" width="40" height="40" class="fw-40 fh-40 flex-shrink-0 rounded-circle object-fit-cover">
                            {else}
                            <span class="avatar-letters fw-40 fh-40" style="background-color:{$user.avatar_color}" aria-hidden="true">{$user.avatar_letters}</span>
                            {/if}
                            <a href="{$user.link}" class="text-break">
                                {$user.username}
                                {if $user.full_name and $user.full_name != $user.username}
                                <span class="text-muted">({$user.full_name})</span>
                                {/if}
                            </a>
                        </div>
                    </td>
                    <td>{$user.gender}</td>
                    <td class="text-nowrap">{$user.regdate}</td>
                </tr>
                {foreachelse}
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">
                        <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('notuser')}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    {if not empty($GENERATE_PAGE)}
    <div class="card-footer border-top pagination-wrap text-center">
        {$GENERATE_PAGE}
    </div>
    {/if}
</div>
{if not empty($NAVBARS)}
<ul class="list-inline">
    {foreach from=$NAVBARS item=nav}
    <li class="list-inline-item text-nowrap me-3"><a href="{$nav.href}"><i class="fa-solid fa-caret-right"></i> {$nav.title}</a></li>
    {/foreach}
</ul>
{/if}
