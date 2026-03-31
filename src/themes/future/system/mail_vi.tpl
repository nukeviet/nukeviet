{*
Tệp thiết lập khung email gửi đi.
Lưu ý: Có tệp này trong giao diện thì cần có tệp theme_email.php xử lý.
Nếu không có tệp theme_email.php thì theme_email.php ở giao diện default được sử dụng và
bạn cần phải đảm bảo ngôn ngữ viết trong tệp này là Smarty để tương thích
*}
<!DOCTYPE html>
<html lang="{$LANG->getGlobal('Content_Language')}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$MESSAGE_TITLE}</title>
<style>
a {
    color:#3F74B8;
}
</style>
</head>
<body style="font:15px/1.35 'Helvetica Neue',Arial,sans-serif;color:#333333">
    <div style="background-color:#F0F0F0;padding:10px">
        <div style="border:1px solid #D8DFE6;background-color:#FFFFFF;padding:20px 10px 30px 10px">
            <div style="margin-bottom:10px">
                <a style="color:#3F74B8" href="{$smarty.const.NV_MY_DOMAIN}" title="{$GCONFIG.site_name}"><img style="width:auto;height:30px;border:0" alt="{$GCONFIG.site_name}" src="cid:sitelogo"/></a>
            </div>
            <div>
                <h1 style="color:#3F74B8;font-size:18px;border-bottom:solid 1px #D8DFE6;margin-top:0;padding-top:0;padding-bottom:5px;margin-bottom:20px">
                    {$MESSAGE_TITLE}
                </h1>
                <div>
                    {$MESSAGE_CONTENT}
                </div>
            </div>
        </div>
        <div style="padding:10px 0;color:#666666;font-size:13px">
            <div style="margin-bottom:5px">
                <a href="{$smarty.const.NV_MY_DOMAIN}" style="color:#666666;text-decoration:none">{$GCONFIG.site_name}</a>
                <br/>
                {$GCONFIG.site_description}
            </div>
            <div style="margin-bottom:20px">
                {$LANG->getGlobal('email')}:
                <a href="mailto:{$GCONFIG.site_email}" style="color:#666666;text-decoration:none">{$GCONFIG.site_email}</a>
                {if not empty($GCONFIG.site_phone)}<br/>
                {$LANG->getGlobal('phonenumber')}:
                <a href="tel:{$GCONFIG.site_phone}" style="color:#666666;text-decoration:none">{$GCONFIG.site_phone}</a>
                {/if}
            </div>
            <div style="font-size:10px">
                ©
                <a href="{$smarty.const.NV_MY_DOMAIN}" style="color:#666666;text-decoration:none">{$GCONFIG.site_name}</a>
                . All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
