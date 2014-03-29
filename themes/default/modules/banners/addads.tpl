<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{clientinfo_link}">{LANG.client_info}</a></li>
	<li class="active"><a href="{clientinfo_addads}">{LANG.client_addads}</a></li>
	<li><a href="{clientinfo_stats}">{LANG.client_stats}</a></li>
</ul>
<!-- END: management -->
<div id="clinfo" class="alert alert-danger">
	{pagetitle}{errorinfo}
</div>
<div id="clinfo">
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
	<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
	<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
	<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
	<form id="frm" action="" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
		<div class="form-group">
			<label for="title" class="col-sm-3 control-label">{LANG.addads_title}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-9">
				<input class="required form-control" type="text" name="title" id="title" value=""/>
			</div>
		</div>
		<div class="form-group">
			<label for="block" class="col-sm-3 control-label">{LANG.addads_block}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-9">
				<select name="block" id="block" class="form-control">
					<!-- BEGIN: blockitem -->
					<option value="{blockitem.id}">{blockitem.title}</option>
					<!-- END: blockitem -->
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="image" class="col-sm-3 control-label">{LANG.addads_adsdata}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-9">
				<input type="file" name="image" id="image" value="" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-3 control-label">{LANG.addads_description}:</label>
			<div class="col-sm-9">
				<input type="text" name="description" id="description" value="" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-sm-3 control-label">{LANG.addads_url}:</label>
			<div class="col-sm-9">
				<input class="url form-control" type="text" name="url" id="url" value=""/>
			</div>
		</div>
		<div class="form-group">
			<label for="begintime" class="col-sm-3 control-label">{LANG.addads_timebegin}:</label>
			<div class="col-sm-9">
				<div class="input-group">
					<input type="text" class="form-control datepicker" id="begintime" name="begintime" value="" readonly="readonly"/>
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="begintime-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="endtime" class="col-sm-3 control-label">{LANG.addads_timeend}:</label>
			<div class="col-sm-9">
				<div class="input-group">
					<input type="text" class="form-control datepicker" id="endtime" name="endtime" value="" readonly="readonly"/>
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="endtime-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<input type="submit" name="confirm" value="{LANG.addads_confirm}" class="btn btn-primary"/>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#frm').validate();
			$(".datepicker").datepicker({
				showOn : "focus",
				dateFormat : "dd/mm/yy",
				changeMonth : true,
				changeYear : true,
				showOtherMonths : true,
				buttonImageOnly : true
			});
			$('#begintime-btn').click(function(){
				$("#begintime").datepicker('show');
			});
			$('#endtime-btn').click(function(){
				$("#endtime").datepicker('show');
			});
		});
	</script>
</div>
<!-- END: main -->