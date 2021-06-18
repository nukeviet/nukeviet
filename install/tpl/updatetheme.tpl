<!-- BEGIN: main --> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{SITE_TITLE}</title>
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}install/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}install/css/style.css" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}install/css/table.css" />
        <script type="text/javascript">
        var nv_base_siteurl = '{NV_BASE_SITEURL}';
        var update_package_deleted = '{LANG.update_package_deleted}';
        var update_package_not_deleted = '{LANG.update_package_not_deleted}';
        </script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/global.js"></script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/{NV_LANG_UPDATE}.js"></script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}install/js/main.js"></script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}install/js/update.js"></script>
    </head>
    <body>
        <div id="header">
            <h1 id="install_title" class="fl">{SITE_TITLE} <span class="version">({CONFIG.to_version})</span></h1>
            <div id="language" class="fr">
                <div>
                    <strong>Language: </strong>
                    <span class="language_head"><img alt="" src="{NV_BASE_SITEURL}install/images/navigate.png" /> {LANGNAMESL} </span>
                </div>
                <ul class="language_body">
                    <!-- BEGIN: looplang -->
                    <li>
                        <a href="{NV_BASE_SITEURL}install/update.php?{LANG_VARIABLE}={LANGTYPE}&amp;step={MAIN_STEP}">{LANGNAME} </a>
                    </li>
                    <!-- END: looplang -->
                </ul>
            </div>
        </div>
        <div id="install_area" class="clearfix">
            <div id="step_content_wrapper" class="fl">
                <div id="step_content">
                    <div class="step_content_title">{CONTENT_TITLE}</div>
                    <div class="content clearfix">{MODULE_CONTENT}</div>
                </div>
            </div>
            <!-- BEGIN: step_bar -->
            <ul id="step_bar" class="fl">
                <!-- BEGIN: loop -->
                <li {CLASS_STEP}>
                    <span class="number">0{NUM}</span>{STEP_BAR}
                </li>
                <!-- END: loop -->
            </ul>
            <!-- END: step_bar -->
        </div>
        <div id="footer">
            <p>
                &copy; 2010 - 2012 {LANG.developed} <strong><a title="VINADES.,JSC" href="http://vinades.vn">VINADES.,JSC</a></strong>
            </p>
            <p>
                {LANG.publish} <strong>GNU/GPL v2.0</strong>
            </p>
        </div>
    </body>
</html>
<!-- END: main -->