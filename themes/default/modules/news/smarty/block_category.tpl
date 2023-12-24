{if empty($CONFIGS.title_length) || $CONFIGS.title_length < 50}
{$CONFIGS.title_length = 60}
{/if}

{* define the function *}
{function name=menu data=[]}
{$i = 0}
{if count($data) > 0}
{foreach $data as $entry}
{if $i==0}
<ul{if $entry.parentid==0} id="menu_{$MENUID}"{/if}>
{/if}
{strip}<li{if $entry.active} class="active"{/if}>
        <a href="{$entry.link}" rel="dofollow"{if $entry.parentid!=0} class="sf-with-ul"{/if} title="{$entry.title}">
            {$entry.title|truncate:$CONFIGS.title_length:"..."}
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

<link rel="stylesheet" type="text/css" href="{$NV_STATIC_URL}themes/{$TEMPLATE}/css/jquery.metisMenu.css" />
<script src="{$ASSETS_STATIC_URL}/js/jquery/jquery.metisMenu.js"></script>

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
    });
});
</script>
