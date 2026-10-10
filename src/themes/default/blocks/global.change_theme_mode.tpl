{assign var="icons" value=[
    'r' => '<i class="fa-solid fa-shuffle fa-fw text-center"></i>',
    'd' => '<i class="fa-solid fa-desktop fa-fw text-center"></i>',
    'm' => '<i class="fa-solid fa-mobile-screen fa-fw text-center"></i>'
]}
<div class="dropup-center dropup site-theme-mode">
    <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown" aria-expanded="false">
        {$icons[$CURRENT_TYPE]} {$LANG->getGlobal("theme_type_`$CURRENT_TYPE`")}
    </button>
    <ul class="dropdown-menu">
        {foreach from=$TYPES item=ttype}
        {if $ttype eq $CURRENT_TYPE}
        <li><div class="dropdown-item active">{$icons[$ttype]} {$LANG->getGlobal("theme_type_`$ttype`")}</div></li>
        {else}
        <li><a class="dropdown-item" href="#" rel="nofollow" data-toggle="siteThemeModeChange" data-type="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;nv{$smarty.const.NV_LANG_DATA}themever={$ttype}&amp;nv_redirect={$CLIENT_INFO.selfurl|redirect_encrypt}" title="{$LANG->getGlobal('theme_type_chose', $LANG->getGlobal("theme_type_`$ttype`"))}">{$icons[$ttype]} {$LANG->getGlobal("theme_type_`$ttype`")}</a></li>
        {/if}
        {/foreach}
    </ul>
</div>
