{* define the function *}
{function name=menu data=[]}
{$i = 0}
{if count($data) > 0}
{foreach $data as $id => $entry}
{if $i==0}
<ul id="menu-{$MENUID}-{$entry.parentid}"{if $entry.parentid==0} class="vert-menu mb-3"{else} class="collapse{if $entry.is_show} in{/if}"{/if}>
{/if}
{strip}<li{if $entry.is_active} class="active"{/if}>
        <a href="{$entry.link}" rel="dofollow"{if !empty($entry.note)} title="{$entry.note}"{/if}{if !empty($entry.target)} {$entry.target}{/if}>
            {$entry.title_trim}
        </a>
{if !empty($entry.sub)}
        <span class="arrow{if !$entry.is_active} collapsed{/if}" data-toggle="collapse" data-target="#menu-{$MENUID}-{$entry.id}" aria-controls="menu-{$MENUID}-{$entry.id}" aria-expanded="{if $entry.is_active}true{else}false{/if}"></span>
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

<link rel="stylesheet" type="text/css" href="{$smarty.const.NV_STATIC_URL}themes/{$BLOCK_THEME}/css/global.vertmenu.css" />

{call name=menu data=$MENU}