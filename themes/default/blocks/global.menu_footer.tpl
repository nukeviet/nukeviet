<ul class="menu">
{foreach $MENU as $item}
    <li>
        <a href="{$item.link}">{$item.title}</a>
    </li>
{/foreach}
</ul>
