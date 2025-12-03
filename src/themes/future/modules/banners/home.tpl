{if $smarty.const.NV_IS_BANNER_CLIENT}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
        <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
        <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
    </ul>
{elseif !$smarty.const.NV_IS_USER}
    <div class="alert alert-info">
        <a href="#" data-bs-toggle="modal" data-bs-target="#loginFormModal">{$LANG->getModule('login_to_check')}.</a>
    </div>
{else}
    <div class="alert alert-warning" role="alert">{$LANG->getModule('no_permission')}.</div>
{/if}

{if !empty($CONTENT) }
    <div class="mb-3">{$CONTENT.info}</div>
    {foreach from=$CONTENT.rows item=row}
        <div class="card border-primary mb-3">
            <div class="card-header bg-primary text-white">
                {$LANG->getModule('plan_title')}: <strong>{$row.title[0]}</strong>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">{$row.blang[0]}: {$row.blang[1]}</li>
                <li class="list-group-item">{$row.size[0]}: {$row.blang[1]}</li>
                <li class="list-group-item">{$row.size[0]}: {$row.size[1]}</li>
                <li class="list-group-item">{$LANG->getModule('plan_allowed')}:
                    {if $row.allowed}
                        {$LANG->getModule('plan_allowed_yes')}
                    {else}
                        {$LANG->getModule('plan_allowed_no')}
                    {/if}
                </li>
                {if !empty($row.description[1])}
                    <li class="list-group-item">{$row.description[1]}</li>
                {/if}
            </ul>
        </div>
    {/foreach}
{/if}
