{* define the function *}
{function name=menu data=[]}
{$i = 0}
{if count($data) > 0}
{foreach $data as $entry}
{if $i==0}
<ul{if $entry.parentid==0} id="menu_{$MENUID}"{/if}>
{/if}
{strip}<li>
        <a href="{$entry.link}" rel="dofollow"{if $entry.parentid!=0} class="sf-with-ul"{/if}{if !empty($entry.note)} title="{$entry.note}"{/if}{if !empty($entry.target)} {$entry.target}{/if}>
            {$entry.title_trim}
        </a>
        {if $entry.parentid==0 && !empty($entry.sub)}<span class="fa arrow expand"></span>{/if}
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

<link rel="stylesheet" type="text/css" href="{$smarty.const.NV_STATIC_URL}themes/{$BLOCK_THEME}/css/jquery.metisMenu.css" />
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.metisMenu.js"></script>

<div class="clearfix panel metismenu">
    <aside class="sidebar">
        <nav class="sidebar-nav">
            {call name=menu data=$MENU}
        </nav>
    </aside>
</div>
<script>
$(function() {
    $('#menu_{$MENUID}').metisMenu({
    toggle: false
    })
});
</script>
