{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{elseif empty($smarty.const.NV_IS_USER)}
<div class="alert alert-info">
    <a href="#" data-toggle="loginForm">{$LANG->getModule('login_to_check')}.</a>
</div>
{else}
<div class="alert alert-warning" role="alert">{$LANG->getModule('no_permission')}.</div>
{/if}

{if not empty($CONTENT) }
<div class="mb-3">{$LANG->getModule('main_page_info')}</div>
{foreach from=$CONTENT item=row}
<div class="card border-primary mb-3">
    <div class="card-header text-bg-primary">
        {$LANG->getModule('plan_title')}: <strong>{$row.title}</strong>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">{$LANG->getModule('blang')}: {not empty($row.blang) ? $LANGUAGE_ARRAY[$row.blang].name : $LANG->getModule('blang_all')}</li>
        <li class="list-group-item">{$LANG->getModule('size')}: {$row.width} x {$row.height} px</li>
        {assign var="form_key" value="form_"|cat:$row.form}
        {if $LANG->existsModule($form_key)}
            {assign var="labelVar" value=$LANG->getModule($form_key)}
        {else}
            {assign var="labelVar" value=$row.form}
        {/if}
        <li class="list-group-item">{$LANG->getModule('form')}: {$labelVar}</li>
        <li class="list-group-item">{$LANG->getModule('plan_allowed')}: {not empty($row.allowed) ? $LANG->getModule('plan_allowed_yes') : $LANG->getModule('plan_allowed_no')}</li>
        {if not empty($row.description)}
        <li class="list-group-item">{$row.description}</li>
        {/if}
    </ul>
</div>
{/foreach}
{/if}
