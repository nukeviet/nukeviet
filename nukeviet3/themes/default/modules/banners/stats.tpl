<!-- BEGIN: main -->
<!-- BEGIN: management -->
<div style="border:1px #ccc solid;padding:5px">
<h3 style="margin-bottom:15px"><strong>{LANG.tool_management}</strong></h3>
<div class="plan_title" style="float:left"><a href="{clientinfo_link}">{LANG.client_info}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_addads}">{LANG.client_addads}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_stats}">{LANG.client_stats}</a></div>
<div style="clear:both"></div>
</div>
<!-- END: management -->

<div id="clinfo" style="width:700px">
	<h3>{LANG.stats_views_ads}
	<select name="ads">
		<option value="">{LANG.stats_views_select}</option>
		<!-- BEGIN: ads -->
		<option value="{ads.id}">{ads.title}</option>
		<!-- END: ads -->
	</select>	
	{LANG.stats_views}
	<select name="type">
		<option value="">{LANG.stats_views_select}</option>
		<option value="country">Country</option>
		<option value="browser">Browser</option>
		<option value="date">Date</option>
		<option value="os">Operating System</option>
	</select>
	{LANG.stats_views_month}
	<select name="month">
		<option value="">{LANG.stats_views_select}</option>
		<!-- BEGIN: month -->
		<option value="{month}">{month}</option>
		<!-- END: month -->
	</select>	
	</h3>
<script type="text/javascript">
$(function(){
	$('select[name=ads]').change(function(){
		var ads = $(this).val();
		var type = $('select[name=type]').val();
		var month = $('select[name=month]').val();		
		if (type != "" && month !="" & ads!=""){
			$('#chartdata').slideDown();
			$('#chartdata').html('<img src="{charturl}&type='+type+'&month='+month+'&ads='+ads+'" style="width:700px"/>');
		}
	});
	$('select[name=type]').change(function(){
		var type = $(this).val();
		var month = $('select[name=month]').val();
		var ads = $('select[name=ads]').val();
		if (type != "" && month !="" & ads!=""){
			$('#chartdata').slideDown();
			$('#chartdata').html('<img src="{charturl}&type='+type+'&month='+month+'&ads='+ads+'" style="width:700px"/>');
		}
	});
	$('select[name=month]').change(function(){
		var month = $(this).val();
		var type = $('select[name=type]').val();
		var ads = $('select[name=ads]').val();		
		if (type != "" && month !="" & ads!=""){
			$('#chartdata').slideDown();
			$('#chartdata').html('<img src="{charturl}&type='+type+'&month='+month+'&ads='+ads+'" style="width:700px"/>');
		}
	});
});
</script>
</div>

<div id="chartdata" style="border:1px solid #DADADA;margin-bottom:10px;margin-top:5px;padding:5px;display:none;width:700px"></div>
<!-- END: main -->