<div class="h4 mt-3 d-lg-none socal-icons-title">{$LANG->getGlobal('joinnow')}</div>
<ul class="socal-icons list-unstyled d-flex align-items-center gap-2 mb-0">
    {foreach from=$SOCIALS item=icon}
    <li>
        <a href="{$icon.url}" title="{$icon.name}" aria-label="{$icon.name}" style="--hover-color:#{$icon.color}"><i class="{$icon.icon}"></i></a>
    </li>
    {/foreach}
</ul>
