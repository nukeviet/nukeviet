{* define the function *}
{function name=menu data=[]}
{$i = 0}
{if count($data) > 0}
{foreach $data as $entry}
{if $i==0}
<ul{if $entry.parentid==0} id="navigation{$MENUID}"{/if}>
{/if}
{strip}<li{if $entry.parentid==0} class="{if $entry.is_active}current {/if}{if !empty($entry.css)}{$entry.css}{/if}"{/if}>
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

<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.treeview.css" type="text/css" />
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.cookie.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.treeview.min.js"></script>
<script>
    $(document).ready(function() {
    $("#navigation{$MENUID}").treeview({
    collapsed: true,
    unique: true,
    persist: "location"
    });
    });
</script>
<style>
    #navigation{$MENUID} a {
    background-color: transparent !important
    }

    #navigation .current,
    #navigation .current a {
        font-weight: bold
    }

    #navigation .current ul a {
        font-weight: normal
    }
</style>

{call name=menu data=$MENU}
<div class="clear"></div>
