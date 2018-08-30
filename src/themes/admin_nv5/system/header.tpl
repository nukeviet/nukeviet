<!doctype html>
<html lang="{$LANG->get('Content_Language')}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <head>
        <title>{$NV_SITE_TITLE}</title>
        <meta http-equiv="content-type" content="text/html; charset={$SITE_CHARSET}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="{$SITE_DESCRIPTION}">
        <meta name="author" content="{$NV_SITE_COPYRIGHT}">
        <meta name="generator" content="{$NV_SITE_NAME}">
        <meta name="robots" content="noindex, nofollow">
        <link rel="shortcut icon" href="{$SITE_FAVICON}">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/fonts/fonts.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/perfect-scrollbar/css/perfect-scrollbar.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/gritter/css/jquery.gritter.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/css/nv.core.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/css/nv.style.css">
        {if isset($NV_CSS_MODULE_THEME)}
        <link rel="stylesheet" href="{$NV_CSS_MODULE_THEME}" type="text/css">
        {/if}

        <script type="text/javascript">
        var  nv_base_siteurl = '{$NV_BASE_SITEURL}',
             nv_lang_data = '{$NV_LANG_DATA}',
             nv_lang_interface = '{$NV_LANG_INTERFACE}',
             nv_name_variable = '{$NV_NAME_VARIABLE}',
             nv_fc_variable = '{$NV_OP_VARIABLE}',
             nv_lang_variable = '{$NV_LANG_VARIABLE}',
             nv_module_name = '{$MODULE_NAME}',
             nv_my_ofs = {$NV_SITE_TIMEZONE_OFFSET},
             nv_my_abbr = '{$NV_CURRENTTIME}',
             nv_cookie_prefix = '{$NV_COOKIE_PREFIX}',
             nv_check_pass_mstime = '{$NV_CHECK_PASS_MSTIME}',
             nv_safemode = {$NV_SAFEMODE},
             nv_area_admin = 1;
        </script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/language/{$NV_LANG_INTERFACE}.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/global.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/admin.js"></script>

        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/bootstrap.bundle.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/gritter/js/jquery.gritter.min.js"></script>

        {if isset($NV_JS_MODULE)}
        <script src="{$NV_JS_MODULE}"></script>
        {/if}

        <!--[if lt IE 9]>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/html5shiv.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/respond.min.js"></script>
        <![endif]-->
    </head>
