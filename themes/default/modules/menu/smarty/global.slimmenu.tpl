{* define the function *}
{function name=menu data=[]}
{$i = 0}
{if count($data) > 0}
{foreach $data as $entry}
{if $i==0}
<ul{if $entry.parentid==0} class="slimmenu"{/if}>
{if $entry.parentid==0}
<li>
    <a title="{$LANG->getGlobal('Home')}" href="{$THEME_SITE_HREF}" rel="dofollow"><em class="fa fa-lg fa-home">&nbsp;</em> <span class="hidden-sm"> {$LANG->getGlobal('Home')} </span></a>
</li>
{/if}
{/if}
{strip}<li{if $entry.parentid==0} class="{if $entry.is_active}current {/if}{if !empty($entry.css)}{$entry.css}{/if}"{/if}>
        {if !empty($entry.icon)}<img src="{$entry.icon}" alt="" />&nbsp;{/if}
        <a href="{$entry.link}" rel="dofollow"{if !empty($entry.note)} title="{$entry.note}"{/if}{if !empty($entry.target)} {$entry.target}{/if}>
            {$entry.title_trim}
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

<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.NV_STATIC_URL}themes/{$BLOCK_THEME}/css/slimmenu.css" />
<script src="{$smarty.const.NV_STATIC_URL}themes/{$BLOCK_THEME}/js/jquery.slimmenu.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>

{call name=menu data=$MENU}

<script>
    {literal}$('ul.slimmenu').slimmenu({
        resizeWidth: (theme_responsive == '1') ? 768 : 0,
        collapserTitle: '',
        easingEffect: 'easeInOutQuint',
        animSpeed: 'medium',
        indentChildren: true,
        childrenIndenter: '&nbsp;&nbsp; '
    });{/literal}
</script>
