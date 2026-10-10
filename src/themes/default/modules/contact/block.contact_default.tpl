<div class="h4 mt-3 d-lg-none">{$LANG->getGlobal('contactUs')}</div>
<ul class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center list-unstyled mb-0 contact-icons">
    {if not empty($BCONFIG.show_clock)}
    <li class="digital-clock" data-format="{$DATETIME_FORMAT}" id="site-digital-clock">
        {$smarty.const.NV_CURRENTTIME|ddatetime}
    </li>
    {/if}
    {foreach from=$ICONS key=stt item=icon}
    <li class="d-flex{if $stt gt 1} d-lg-none{/if} align-items-center gap-1">
        <i class="icon-{$icon.type} flex-shrink-0" title="{$icon.title}" aria-label="{$icon.title}"></i>
        {if not empty($icon.link)}<a class="text-break" href="{$icon.link}" title="{$icon.title}">{$icon.value}</a>{else}<span class="text-break">{$icon.value}</span>{/if}
    </li>
    {/foreach}
    {if count($ICONS) gt 2}
    <li class="d-none d-lg-flex align-items-center gap-1 dropdown">
        <a href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="{$LANG->getModule('otherContacts')}"><i class="fa-solid fa-caret-down"></i></a>
        <ul class="dropdown-menu">
            {for $i=2 to count($ICONS)-1}
            {assign var="icon" value=$ICONS[$i]}
            <li>
                {if not empty($icon.link)}
                <a class="dropdown-item d-flex align-items-center gap-1" href="{$icon.link}">
                    <i class="icon-{$icon.type} flex-shrink-0" title="{$icon.title}" aria-label="{$icon.title}"></i>
                    <span class="text-break">{$icon.value}</span>
                </a>
                {else}
                <div class="dropdown-item d-flex align-items-center gap-1">
                    <i class="icon-{$icon.type} flex-shrink-0" title="{$icon.title}" aria-label="{$icon.title}"></i>
                    <span class="text-break">{$icon.value}</span>
                </div>
                {/if}
            </li>
            {/for}
        </ul>
    </li>
    {/if}
</ul>
