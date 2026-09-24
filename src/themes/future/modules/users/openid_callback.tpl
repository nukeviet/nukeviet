{* Kết quả xác thực OAuth, users.js đọc dữ liệu để gửi về cửa sổ mẹ hoặc chuyển trang *}
<div class="d-none" data-toggle="usersOpenidCallback"
    data-result="{$OPIDRESULT.status|escape}"
    data-redirect="{$OPIDRESULT.redirect|escape}"
    data-message="{$OPIDRESULT.mess|escape}"
    data-origin="{$smarty.const.NV_MY_DOMAIN}"
></div>
