<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/css/users.css" rel="stylesheet" />
<script src="{NV_STATIC_URL}themes/default/js/users.js"></script>
<div class="centered">
<div class="login-box">
    <div class="page panel panel-default box-shadow bg-lavender" style="margin-bottom:0">
        <div class="panel-body">
            <h2 class="text-center margin-bottom-lg">{LANG.login}</h2>
            {FILE "login_form.tpl"}
            <div class="text-center margin-top-lg">
                <!-- BEGIN: navbar --><a href="{NAVBAR.href}" class="margin-right-lg"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a><!-- END: navbar -->
            </div>
        </div>
    </div>
</div>
</div>
<!-- END: main -->