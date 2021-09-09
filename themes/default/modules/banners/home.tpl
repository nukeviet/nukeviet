<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
    <li class="active"><a href="{MANAGEMENT.main}">{LANG.plan_info}</a></li>
    <li><a href="{MANAGEMENT.addads}">{LANG.client_addads}</a></li>
    <li><a href="{MANAGEMENT.stats}">{LANG.client_stats}</a></li>
</ul>
<!-- END: management -->
<!-- BEGIN: if_banner_plan -->
<!-- BEGIN: info -->
<div class="m-bottom">{MAIN_PAGE_INFO}:</div>
<!-- END: info -->
<!-- BEGIN: banner_plan -->
<div class="panel panel-primary">
    <div class="panel-heading">{LANG.plan_title}: <strong>{PLAN_TITLE}</strong></div>
    <ul class="list-group">
        <li class="list-group-item">{PLAN_LANG_TITLE}: {PLAN_LANG_NAME}</li>
        <li class="list-group-item">{PLAN_SIZE_TITLE}: {PLAN_SIZE_NAME}</li>
        <li class="list-group-item">{PLAN_FORM_TITLE}: {PLAN_FORM_NAME}</li>
        <li class="list-group-item">{LANG.plan_allowed}: <!-- BEGIN: allowed -->{LANG.plan_allowed_yes}<!-- END: allowed --><!-- BEGIN: notallowed -->{LANG.plan_allowed_no}<!-- END: notallowed --></li>
        <!-- BEGIN: desc --><li class="list-group-item">{PLAN_DESCRIPTION_NAME}</li><!-- END: desc -->
    </ul>
</div>
<!-- END: banner_plan -->
<!-- END: if_banner_plan -->
<!-- BEGIN: login_check -->
<div class="alert alert-info">
    <a href="javascript:void(0);" onclick="loginForm('');">{LANG.login_to_check}.</a>
</div>
<!-- END: login_check -->
<!-- BEGIN: no_permission -->
<div class="alert alert-warning">{LANG.no_permission}.</div>
<!-- END: no_permission -->
<!-- END: main -->