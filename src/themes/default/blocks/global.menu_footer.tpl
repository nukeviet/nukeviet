<ul class="list-unstyled row g-2">
    {foreach from=$SITE_MODS key=modname item=modvalues}
    {if in_array($modname, $DATA.module_in_menu) and !empty($modvalues.funcs)}
    <li class="col-sm-6">
        <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$modname}"><i class="fa-solid fa-angle-right"></i> {$modvalues.custom_title}</a>
    </li>
    {/if}
    {/foreach}
</ul>
