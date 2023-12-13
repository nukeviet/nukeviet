{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs m-bottom">
    <li class="active"><a href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li><a href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li><a href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{/if}
{if !empty($PLANS)}
<div class="m-bottom">{$PLANS.info}:</div>
{foreach $PLANS.rows as $row}
<div class="panel panel-primary">
    <div class="panel-heading">{$LANG->getModule('plan_title')}: <strong>{$row.title[0]}</strong></div>
    <ul class="list-group">
        <li class="list-group-item">{$row.blang[0]}: {$row.blang[1]}</li>
        <li class="list-group-item">{$row.size[0]}: {$row.size[1]}</li>
        <li class="list-group-item">{$row.form[0]}: {$row.form[1]}</li>
        <li class="list-group-item">{$LANG->getModule('plan_allowed')}: {if $row.allowed}{$LANG->getModule('plan_allowed_yes')}{else}{$LANG->getModule('plan_allowed_no')}{/if}</li>
{if !empty($row.description[1])}
        <li class="list-group-item">{$row.description[1]}</li>
{/if}
    </ul>
</div>
{/foreach}
{/if}
{if !$smarty.const.NV_IS_BANNER_CLIENT}
{if !$smarty.const.NV_IS_USER}
<div class="alert alert-info">
    <a href="#" data-toggle="loginForm">{$LANG->getModule('login_to_check')}.</a>
</div>
{else}
<div class="alert alert-info">{$LANG->getModule('no_permission')}.</div>
{/if}
{/if}
