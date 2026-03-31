<!DOCTYPE html>
<html lang="{$LANG->getGlobal('Content_Language')}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#" data-theme="{$COLOR_MODE}" data-bs-theme="{$COLOR_MODE}" dir="{$TCONFIG::isRtl() ? 'rtl' : 'ltr'}">

<head>
    <title>{$THEME_PAGE_TITLE}</title>
    {foreach from=$METATAGS item=meta}
    <meta {$meta.name}="{$meta.value}" content="{$meta.content}">
    {/foreach}
    <link rel="shortcut icon" href="{$SITE_FAVICON}">
    {foreach from=$HTML_LINKS item=links}
    <link{foreach from=$links key=key item=value} {$key}{if not empty($value)}="{$value}"{/if}{/foreach}>
    {/foreach}
    <script data-show="inline" type="text/javascript" src="{$smarty.const.NV_BASE_SITEURL}themes/{$GCONFIG.module_theme}/js/nv.head.js"></script>
    {foreach from=$HTML_JS item=js}
    <script{if not empty($js.type)} type="{$js.type}"{/if}{if $js.ext} src="{$js.content}"{/if}>{if not $js.ext}{$smarty.const.PHP_EOL}{$js.content}{$smarty.const.PHP_EOL}{/if}</script>
    {/foreach}
</head>

<body class="mname-{$MODULE_NAME} op-{$OP}{if $HOME} site-home{/if}">
