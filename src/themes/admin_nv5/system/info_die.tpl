<!doctype html>
<html lang="{$LANG->get('Content_Language')}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <head>
        <title>{$PAGE_TITLE}</title>
        <meta http-equiv="content-type" content="text/html; charset={$SITE_CHARSET}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="{$SITE_DESCRIPTION}">
        <meta name="author" content="{$NV_SITE_COPYRIGHT}">
        <meta name="generator" content="{$NV_SITE_NAME}">
        <meta name="robots" content="noindex, nofollow">
        <meta http-equiv="expires" content="0" />
        <meta name="resource-type" content="document" />
        <meta name="distribution" content="global" />
        <meta name="copyright" content="Copyright (c) {$NV_SITE_NAME}" />
        <link rel="shortcut icon" href="{$SITE_FAVICON}">

        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/fonts/fonts.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/libs/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/libs/perfect-scrollbar/css/perfect-scrollbar.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/libs/gritter/css/jquery.gritter.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/css/nv.core.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/css/nv.style.css">

        <script type="text/javascript">
        var  nv_base_siteurl = '{$NV_BASE_SITEURL}',
             nv_lang_data = '{$NV_LANG_DATA}',
             nv_lang_interface = '{$NV_LANG_INTERFACE}',
             nv_lang_variable = '{$NV_LANG_VARIABLE}',
             nv_my_ofs = {$NV_SITE_TIMEZONE_OFFSET},
             nv_my_abbr = '{$NV_CURRENTTIME}',
             nv_cookie_prefix = '{$NV_COOKIE_PREFIX}',
             nv_check_pass_mstime = '{$NV_CHECK_PASS_MSTIME}';
        </script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/language/{$NV_LANG_INTERFACE}.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/global.js"></script>

        <script src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/js/bootstrap.bundle.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/libs/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/libs/gritter/js/jquery.gritter.min.js"></script>

        <!--[if lt IE 9]>
        <script src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/js/html5shiv.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="nv-splash-page nv-error-page">
        <div class="wrapper">
            <div class="main-content-wrapper">
                <div class="main-content">
                    <div class="page-body container-fluid">
                        <div class="error-container">
                            <div class="error-number">{$ERROR_CODE}</div>
                            <div class="error-title">{$ERROR_TITLE}</div>
                            <div class="error-description">{$ERROR_CONTENT}</div>
                            <div class="error-goback-btn">
                                {if isset($ADMIN_LINK)}
                                <a href="{$ADMIN_LINK}" class="btn btn-xl btn-primary">{$GO_ADMINPAGE}</a>
                                {/if}
                                {if isset($SITE_LINK)}
                                <a href="{$SITE_LINK}" class="btn btn-xl btn-primary">{$GO_SITEPAGE}</a>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/js/nv.core.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/js/nv.main.js"></script>
    </body>
</html>
