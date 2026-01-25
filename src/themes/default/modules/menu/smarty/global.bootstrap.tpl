{* define the function *}
{function name=menu data=[]}
{$i = 0}
{if count($data) > 0}
{foreach $data as $entry}
{if $i==0}
<ul class="{if $entry.parentid==0}nav navbar-nav{else}dropdown-menu{/if}">
{if $entry.parentid==0}
<li><a class="home" title="{$LANG->getGlobal('Home')}" href="{$THEME_SITE_HREF}" rel="dofollow"><em class="fa fa-lg fa-home">&nbsp;</em><span class="visible-xs-inline-block"> {$LANG->getGlobal('Home')}</span></a></li>
{/if}
{/if}
{strip}<li {if $entry.parentid==0} role="presentation" class="{if !empty($entry.css)}{$entry.css}{/if}{if !empty($entry.sub)} dropdown{/if}{if $entry.is_active} active{/if}"{else}{if !empty($entry.sub)} class="dropdown-submenu"{/if}{/if}>
        <a href="{$entry.link}" rel="dofollow"{if $entry.parentid==0} role="button"{if !empty($entry.sub)} class="dropdown-toggle" aria-expanded="false"{/if}{/if}{if !empty($entry.note)} title="{$entry.note}"{/if}{if !empty($entry.target)} {$entry.target}{/if}>
            {if !empty($entry.icon)}<img src="{$entry.icon}" alt="" />{/if}
            {$entry.title_trim}
            {if $entry.parentid==0 && !empty($entry.sub)} <strong class="caret"></strong>{/if}
        </a>
{if !empty($entry.sub)}
{call name=menu data=$entry.sub}
{/if}
</li>{/strip}
{$i=$i+1}
{/foreach}
{if $i > 0}
</ul>
{/if}
{/if}
{/function}

<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-site-default">
            <span class="sr-only">&nbsp;</span> <span class="icon-bar">&nbsp;</span> <span class="icon-bar">&nbsp;</span> <span class="icon-bar">&nbsp;</span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="menu-site-default">
        {call name=menu data=$MENU}
    </div>
</div>
<script type="text/javascript" data-show="after">
    {literal}$(function() {
        checkWidthMenu();
        $(window).resize(checkWidthMenu);
    });{/literal}
</script>
