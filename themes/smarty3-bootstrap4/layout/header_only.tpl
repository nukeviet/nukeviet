<!DOCTYPE html>
	<html lang="{$LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
	<head>
		<title>{$theme_page_title}</title>
        {foreach $metatags as $meta_tag}
            <meta {$meta_tag.name}="{$meta_tag.value}" content="{$meta_tag.content}">
        {/foreach}
		<link rel="shortcut icon" href="{$global_config.site_favicon}">


        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="/themes/smarty3-bootstrap4/assets/css/preload.min.css">
        <link rel="stylesheet" href="/themes/smarty3-bootstrap4/assets/css/plugins.min.css">
        <link rel="stylesheet" href="/themes/smarty3-bootstrap4/assets/css/style.light-blue-500.min.css">

        {foreach $html_links as $html_link}
         <link {foreach $html_link as $key => $value} {$key}="{$value}" {/foreach}>
        {/foreach}

        <!--[if lt IE 9]>
            <script src="/themes/smarty3-bootstrap4/assets/js/html5shiv.min.js"></script>
            <script src="/themes/smarty3-bootstrap4/assets/js/respond.min.js"></script>
        <![endif]-->


        {foreach $html_js as $js}
            {if $js.ext eq '1'}
                <script src="{$js.content}"></script>
            {else}
                <script>{$js.content}</script>
            {/if}
        {/foreach}
	</head>
    <body>
        <div class="card-body" style="padding-left:100px;">
            <div class="card">
                <div class="card-header">
                    Ví dụ dùng ngôn ngữ
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">{$NLANG->get('admin_logout_title')}</li>
                    <li class="list-group-item">{$NLANG->get('admin_ipincorrect', '12.1.1.1')}</li>
                    <li class="list-group-item">{$NLANG->get('unick_type_3')}</li>
                    <li class="list-group-item">{$NLANG->get('loginincorrect')}</li>
                    <li class="list-group-item">{$NLANG->get('memory_time_usage', '12MB', '5')}</li>
                    <li class="list-group-item">{$NLANG->get('KEY_NOT_EXISTS')}</li>
                </div>
            </div>
        </div>
