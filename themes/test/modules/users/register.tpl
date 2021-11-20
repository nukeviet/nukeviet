<!-- BEGIN: main -->
<div class="row centered margin-top-lg margin-bottom-lg">
<div style="max-width:500px">
    <div class="page panel panel-default box-shadow bg-lavender">
        <div class="panel-body">
            <h2 class="text-center margin-bottom-lg">{LANG.register}</h2>
            {FILE "register_form.tpl"}
            <div class="centered margin-top-lg">
                <ul class="users-menu nav navbar-nav">
                    <!-- BEGIN: navbar --><li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<!-- BEGIN: datepicker -->
<link type="text/css" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- END: datepicker -->
<!-- END: main -->