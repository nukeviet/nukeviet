<!-- BEGIN: main -->
<form action="" method="POST" onsubmit="check(); return false;">
	<!-- BEGIN: loop -->
	
		<!-- BEGIN: main_group -->
		<p>
			<!-- BEGIN: image -->
			<img src="{DATA.image}" style="max-height: 16px; max-width: 16px" />
			<!-- END: image -->
			<strong>{DATA.title}</strong>
		</p>
		<!-- END: main_group -->
		
		<!-- BEGIN: sub_group -->
		<p>
			{DATA.space}
			<!-- BEGIN: checkbox -->
			<input type="checkbox" name="group_id[]" value="{DATA.id}" id="{DATA.id}">
			<!-- END: checkbox -->
			<label for="{DATA.id}">
				<!-- BEGIN: image -->
				<img src="{DATA.image}" style="max-height: 16px; max-width: 16px" />
				<!-- END: image -->
				{DATA.title} <span class="text-danger">({DATA.numpro})</span>
			</label>
		</p>
		<!-- END: sub_group -->
		
	<!-- END: loop -->
	<input type="submit" name="filter" value="{LANG.filter}" class="btn btn-primary btn-xs btn-sm pull-right" />
</form>
<script type="text/javascript">
	function check()
	{
		var count = 0;
		$('input[name="group_id[]"]').each(function() {
			if( $(this).is(':checked') ) $count++;	
		});
		
		if( count == 0 ) alert('{LANG.filter_no_item}');
	}
</script>
<!-- END: main -->