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
<div class="well">{MAIN_PAGE_INFO}:</div>
<!-- END: info -->
<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<td class="min-w100">{LANG.plan_title}</td>
				<td class="min-w100">{LANG.plan_info}</td>
				<td class="min-w100">{LANG.description}</td>
				<td class="min-w100 text-center">{LANG.plan_allowed}</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: banner_plan -->
			<tr>
				<td>{PLAN_TITLE}</td>
				<td><strong>{PLAN_LANG_TITLE}</strong>: {PLAN_LANG_NAME}, <strong>{PLAN_SIZE_TITLE}</strong>: {PLAN_SIZE_NAME}, <strong>{PLAN_FORM_TITLE}</strong>: {PLAN_FORM_NAME}</td>
				<td>{PLAN_DESCRIPTION_NAME}</td>
                <td class="text-center">
                    <!-- BEGIN: allowed -->
                    <a class="btn btn-xs btn-success">{LANG.plan_allowed_yes}</a>
                    <!-- END: allowed -->
                    <!-- BEGIN: notallowed -->
                    <a class="btn btn-xs btn-danger">{LANG.plan_allowed_no}</a>
                    <!-- END: notallowed -->
                </td>
			</tr>
			<!-- END: banner_plan -->
		</tbody>
	</table>
</div>
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