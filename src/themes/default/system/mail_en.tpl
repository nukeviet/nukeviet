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
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<title>{$MESSAGE_TITLE}</title>
<style>
a {
    color: #3F74B8;
}
img {
    max-width: 100%;
    height: auto;
}
@media only screen and (min-width: 1366px) {
    .mail-wrap {
        max-width: 800px !important;
    }
}
@media only screen and (max-width: 620px) {
    .mail-outer {
        padding: 16px 12px !important;
    }
    .mail-card {
        padding: 24px 20px !important;
    }
}
</style>
</head>
<body style="margin:0;padding:0;background-color:#F3F5F8;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;color:#374151">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F3F5F8">
        <tr>
            <td align="center" class="mail-outer" style="padding:32px 16px">
                <!--[if mso]><table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="mail-wrap" style="width:100%;max-width:600px">
                    <tr>
                        <td align="center" style="padding:0 0 24px 0">
                            <a href="{$smarty.const.NV_MY_DOMAIN}" title="{$GCONFIG.site_name}" style="color:#1F2937;font-size:20px;font-weight:600;text-decoration:none">{if not empty($MAIL_LOGO)}<img style="display:block;width:auto;height:40px;border:0" height="40" alt="{$GCONFIG.site_name}" src="cid:sitelogo"/>{else}{$GCONFIG.site_name}{/if}</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="mail-card" style="background-color:#FFFFFF;border:1px solid #E3E7ED;border-radius:12px;padding:36px 40px">
                            <div style="font-size:15px;line-height:1.6;color:#374151;overflow-wrap:anywhere">
                                {$MESSAGE_CONTENT}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:24px 16px 0 16px;font-size:13px;line-height:1.6;color:#5F6B7A">
                            <div style="font-weight:600">
                                <a href="{$smarty.const.NV_MY_DOMAIN}" style="color:#5F6B7A;text-decoration:none">{$GCONFIG.site_name}</a>
                            </div>
                            <div>{$GCONFIG.site_description}</div>
                            <div style="margin-top:8px">
                                {$LANG->getGlobal('email')}:
                                <a href="mailto:{$GCONFIG.site_email}" style="color:#5F6B7A">{$GCONFIG.site_email}</a>
                                {if not empty($GCONFIG.site_phone)}<br/>
                                {$LANG->getGlobal('phonenumber')}:
                                <a href="tel:{$GCONFIG.site_phone}" style="color:#5F6B7A">{$GCONFIG.site_phone}</a>
                                {/if}
                            </div>
                            <div style="margin-top:12px;font-size:12px;color:#5F6B7A">
                                © {$GCONFIG.site_name}. All rights reserved.
                            </div>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
