<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{clientinfo_link}">{LANG.client_info}</a></li>
	<li><a href="{clientinfo_addads}">{LANG.client_addads}</a></li>
	<li><a href="{clientinfo_stats}">{LANG.client_stats}</a></li>
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
				<td>{LANG.plan_title}</td>
				<td>{LANG.plan_info}</td>
				<td>{LANG.description}</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: banner_plan -->
			<tr>
				<td>{PLAN_TITLE}</td>
				<td><strong>{PLAN_LANG_TITLE}</strong>: {PLAN_LANG_NAME}, <strong>{PLAN_SIZE_TITLE}</strong>: {PLAN_SIZE_NAME}, <strong>{PLAN_FORM_TITLE}</strong>: {PLAN_FORM_NAME}</td>
				<td>{PLAN_DESCRIPTION_NAME}</td>
			</tr>
			<!-- END: banner_plan -->			
		</tbody>
	</table>
</div>
<!-- END: if_banner_plan -->
<div id="{CONTAINERID}"></div>
<script type="text/javascript">
{AJ}
</script>
<!-- END: main -->