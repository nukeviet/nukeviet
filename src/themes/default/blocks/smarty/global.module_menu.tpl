<div class="clearfix panel">
    <aside class="sidebar module-menu">
        <nav class="sidebar-nav">
            <ul>
                <li><a class="home {$MOD.active}" href="{$MOD.href}">{$MOD.title}</a></li>
{foreach $MENU as $item}
                <li><a class="{$item.active}" href="{$item.href}">{$item.title}</a></li>
{/foreach}
            </ul>
        </nav>
    </aside>
</div>
