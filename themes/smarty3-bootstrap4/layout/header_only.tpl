<!DOCTYPE html>
	<html lang="{$LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
	<head>
		<title>{$theme_page_title}</title>
        {foreach $metatags as $meta_tag}
            <meta {$meta_tag.name}="{$meta_tag.value}" content="{$meta_tag.content}">
        {/foreach}
		<link rel="shortcut icon" href="{$global_config.site_favicon}">
        
        {foreach $html_links as $html_link}
         <link {foreach $html_link as $key => $value} {$key}="{$value}" {/foreach}>
        {/foreach}
        
        {foreach $html_js as $js}
            {if $js.ext eq '1'}
                <script src="{$js.content}"></script>
            {else}
                <script>{$js.content}</script>
            {/if}        
        {/foreach}        
	</head>
	<body>