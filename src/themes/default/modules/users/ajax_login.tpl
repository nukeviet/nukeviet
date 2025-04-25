<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/{TEMPLATE}/css/users.css" rel="stylesheet" />
<!-- BEGIN: google_identity_js -->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<!-- END: google_identity_js -->
<script src="{NV_STATIC_URL}themes/{TEMPLATE}/js/users.js"></script>
<div class="centered">
    <div class="login-box">
        <div class="page panel panel-default box-shadow bg-lavender" style="margin-bottom:0">
            <div class="panel-body">
                <div class="h2 text-center margin-bottom"><strong>{LANG.login}</strong></div>
                {FILE "login_form.tpl"}
                <div class="text-center margin-top-lg">
                    <!-- BEGIN: navbar --><a href="{NAVBAR.href}" class="margin-right-lg"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a><!-- END: navbar -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    // Ẩn/hiển thị mật khẩu
    "function" === typeof addPassBtn && addPassBtn()
})
</script>
<!-- END: main -->
