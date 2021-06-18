<!-- BEGIN: main -->
<div class="login-box">
    <div class="row centered margin-top-lg">
        <div class="col-md-12">
            <div class="page panel panel-default bg-lavender box-shadow">
                <div class="panel-body">
                    <!-- BEGIN: redirect2 -->
                    <div class="text-center margin-bottom-lg">
                        <a title="{SITE_NAME}" href="{THEME_SITE_HREF}"><img class="logo" src="{LOGO_SRC}" alt="{SITE_NAME}"></a>
                    </div>
                    <!-- END: redirect2 -->
                    <h2 class="text-center margin-bottom-lg">{LANG.login}</h2>
                    {FILE "login_form.tpl"}
                </div>
            </div>
            <ul class="nav navbar-nav">
                <!-- BEGIN: navbar --><li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
            </ul>
        </div>
    </div>
</div>
<!-- END: main -->
