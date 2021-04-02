<!-- BEGIN: main -->
<!DOCTYPE html>
<html lang="{LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <head>
        <meta http-equiv="content-type" content="text/html; charset={SITE_CHARSET}" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{PAGE_TITLE}</title>
        <link rel="shortcut icon" href="{SITE_FAVICON}" />
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{NV_STATIC_URL}{NV_ASSETS_DIR}/css/font-awesome.min.css" rel="stylesheet" />
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/main.js"></script>
    </head>
    <body>
        <div class="nv-infodie text-center">
            <div class="panel-body">
                <p><a href="{HOME_LINK}"><img class="logo" src="{LOGO}" alt="{SITE_NAME}"></a></p>
                <div class="m-bottom">
                    <h2>{INFO_TITLE}</h2>
                    {INFO_CONTENT}
                </div>
                <div>
                    <hr />
                    <!-- BEGIN: adminlink -->
                    <em class="fa fa-gears fa-lg">&nbsp;</em> <a href="{ADMIN_LINK}">{GO_ADMINPAGE}</a>
                    <span class="defis">&nbsp;</span>
                    <!-- END: adminlink -->
                    <!-- BEGIN: sitelink -->
                    <em class="fa fa-home fa-lg">&nbsp;</em> <a href="{SITE_LINK}">{GO_SITEPAGE}</a>
                    <!-- END: sitelink -->
                </div>
            </div>
        </div>
    </body>
</html>
<!-- END: main -->
