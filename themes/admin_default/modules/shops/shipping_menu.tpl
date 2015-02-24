<div class="row">
	<div class="col-xs-8">

	</div>
	<div class="col-xs-16">
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-primary" id="shipping">
				{LANG.shipping}
			</button>
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="caret"></span>
				<span class="sr-only"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{LOCALTION_URL}">{LANG.location}</a>
				</li>
				<li>
					<a href="{CARRIER_URL}">{LANG.carrier}</a>
				</li>
				<li>
					<a href="{CONFIG_URL}">{LANG.carrier_config_config}</a>
				</li>
				<li>
					<a href="{SHOPS_URL}">{LANG.shops}</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<hr class="clearfix" />
<script type="text/javascript">
	$('#shipping').click(function(){
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=shipping';
	});
</script>