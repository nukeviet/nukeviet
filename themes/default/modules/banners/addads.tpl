<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{MANAGEMENT.main}">{LANG.plan_info}</a></li>
	<li><a href="{MANAGEMENT.link}">{LANG.client_info}</a></li>
	<li class="active"><a href="{MANAGEMENT.addads}">{LANG.client_addads}</a></li>
	<li><a href="{MANAGEMENT.stats}">{LANG.client_stats}</a></li>
	<li><a href="{MANAGEMENT.logout}">{GLANG.logout}</a></li>
</ul>
<!-- END: management -->
<div id="clinfo" class="alert alert-danger">
	{pagetitle}{errorinfo}
</div>
<div id="clinfo">
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js" data-show="after"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js" data-show="after"></script>
	<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
	<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
	<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js" data-show="after"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js" data-show="after"></script>
	<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js" data-show="after"></script>
	<form id="frm" action="" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
		<div class="form-group">
			<label for="title" class="col-sm-6 control-label">{LANG.addads_title}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-18">
				<input class="required form-control" type="text" name="title" id="title" value=""/>
			</div>
		</div>
		<div class="form-group">
			<label for="block" class="col-sm-6 control-label">{LANG.addads_block}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-18">
				<select name="block" id="block" class="form-control">
					<!-- BEGIN: blockitem -->
					<option value="{blockitem.id}">{blockitem.title}</option>
					<!-- END: blockitem -->
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="image" class="col-sm-6 control-label">{LANG.addads_adsdata}<span class="text-danger"> (*)</span>:</label>
			<div class="col-sm-18">
				<input type="file" name="image" id="image" value="" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-6 control-label">{LANG.addads_description}:</label>
			<div class="col-sm-18">
				<input type="text" name="description" id="description" value="" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-sm-6 control-label">{LANG.addads_url}:</label>
			<div class="col-sm-18">
				<input class="url form-control" type="text" name="url" id="url" value=""/>
			</div>
		</div>
		<div class="form-group">
			<label for="begintime" class="col-sm-6 control-label">{LANG.addads_timebegin}:</label>
			<div class="col-sm-18">
				<div class="input-group">
					<input type="text" class="form-control datepicker" id="begintime" name="begintime" value="" readonly="readonly"/>
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="begintime-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="endtime" class="col-sm-6 control-label">{LANG.addads_timeend}:</label>
			<div class="col-sm-18">
				<div class="input-group">
					<input type="text" class="form-control datepicker" id="endtime" name="endtime" value="" readonly="readonly"/>
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="endtime-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-6 col-sm-18">
				<input type="submit" name="confirm" value="{LANG.addads_confirm}" class="btn btn-primary"/>
			</div>
		</div>
	</form>
	<script type="text/javascript" data-show="after">
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
