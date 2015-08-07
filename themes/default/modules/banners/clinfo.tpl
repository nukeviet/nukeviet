<!-- BEGIN: clinfo -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{MANAGEMENT.main}">{LANG.plan_info}</a></li>
	<li class="active"><a href="{MANAGEMENT.link}">{LANG.client_info}</a></li>
	<li><a href="{MANAGEMENT.addads}">{LANG.client_addads}</a></li>
	<li><a href="{MANAGEMENT.stats}">{LANG.client_stats}</a></li>
	<li><a href="{MANAGEMENT.logout}">{GLANG.logout}</a></li>
</ul>
<!-- END: management -->
<div id="clinfo">
	<div class="table-responsive">
		<table class="table table-striped">
			<tbody>
				<!-- BEGIN: name_value -->
				<tr>
					<td>{INFO_NAME}</td>
					<td>{INFO_VALUE}</td>
				</tr>
				<!-- END: name_value -->			
			</tbody>
		</table>
	</div>
</div>
<p class="text-center">
	<a class="btn btn-info" href="javascript:void(0);" onclick="{EDIT_ONCLICK}">{EDIT_NAME}</a>
</p>
<!-- END: clinfo -->