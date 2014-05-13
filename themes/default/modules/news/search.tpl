<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form action="{BASE_URL_SITE}" name="fsea" method="get" id="fsea" class="form-horizontal">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />
	<div class="panel panel-default">
		<div class="panel-body">
			<h3 class="text-center"><em class="fa fa-search">&nbsp;</em>{LANG.info_title}</h3>
			<hr />
			<div class="form-group">
				<div class="col-md-4">{LANG.key_title}</div>
	  			<div class="col-md-8"><input type="text" name="q" value="{KEY}" class="form-control" id="key"/></div>
			</div>
			
			<div class="form-group">
				<div class="col-md-4">{LANG.type_title}</div>
	  			<div class="col-md-8">
					<select name="choose" id="choose" class ="form-control">
						<option value="0" {CHECK1}>{LANG.find_all} </option>
						<option value="1" {CHECK1}>{LANG.find_content} </option>
						<option value="2" {CHECK2}>{LANG.find_author} </option>
						<option value="3" {CHECK3}>{LANG.find_resource} </option>
					</select>
	  			</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-4">{LANG.search_cat}</div>
	  			<div class="col-md-8">
					<select name="catid" class ="form-control">
						<!-- BEGIN: search_cat -->
						<option value="{SEARCH_CAT.catid}" {SEARCH_CAT.select}>{SEARCH_CAT.title}</option>
						<!-- END: search_cat -->
					</select>
	  			</div>
			</div>
			
			<div class="form-group form-inline">
				<div class="col-md-4">{LANG.from_date}</div>
	  			<div class="col-md-8"><input class="datepicker form-control" name="to_date" value="{TO_DATE}" style="width:120px; display: inline" maxlength="10" type="text"/></div>
			</div>
			
			<div class="form-group form-inline">
				<div class="col-md-4">{LANG.to_date}</div>
	  			<div class="col-md-8"><input class="datepicker form-control" name="from_date" value="{FROM_DATE}" style="width: 120px; display: inline" maxlength="10" type="text" /></div>
			</div>
			
			<div class="form-group form-inline">
				<div class="col-md-4 text-right">&nbsp;</div>
	  			<div class="col-md-8"><input type="submit" class="btn btn-primary" value="{LANG.search_title}"/></div>
			</div>

		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	$(".datepicker").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
	// Init google custom search
});
</script>
<!-- END: main -->
<!-- BEGIN: results -->
<div class="panel panel-default">
	<div class="panel-body">
		<h3 class="text-center"><em class="fa fa-filter">&nbsp;</em>{LANG.search_on} {TITLE_MOD}</h3><hr />
		<!-- BEGIN: noneresult -->
		<p><em>{LANG.search_none} : <strong class="label label-info">{KEY}</strong> {LANG.search_in_module} <strong>{INMOD}</strong></em></p>
		<!-- END: noneresult -->
		
		<!-- BEGIN: result -->
		<h3><a href="{LINK}">{TITLEROW}</a></h3>
		<div class="text-justify">
			<p>
				<!-- BEGIN: result_img -->
				<img src="{IMG_SRC}" border="0" width="{IMG_WIDTH}px" class="img-thumbnail pull-left" style="margin: 0 5px 5px 0" />
				<!-- END: result_img -->
				{CONTENT}
			</p>
		</div>
		<div class="text-right">
			{AUTHOR}
		</div>
		<div class="text-right">
			<strong>{LANG.source_title}:</strong> <span>{SOURCE}</span>
		</div>
		<hr />
		<!-- END: result -->
		<!-- BEGIN: pages_result -->
		<div class="text-center">
			{VIEW_PAGES}
		</div>
		<!-- END: pages_result -->
		
		<div class="alert alert-info">
			<p><em>{LANG.search_sum_title} <strong>{NUMRECORD}</strong> {LANG.result_title}
			<br />
			{LANG.info_adv} </em></p>
		</div>
		
			<h4><strong>{LANG.search_adv_internet} :</strong></h4>
			<div align="center">
				<form method="get" action="http://www.google.com/search" target="_top">
					<input type="hidden" name="domains" value="{MY_DOMAIN}" />
					
					<div class="form-group">
						<div class="col-md-4"><img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" /></div>
						<div class="col-md-4"><input type="text" name="q" maxlength="255" value="{KEY}" id="sbi" class="form-control" /></div>
						<div class="col-md-4"><input type="submit" name="sa" value="{LANG.search_title}" id="sbb" class="btn btn-default"></div>
					</div>
					
					<div class="form-group">
						<div class="col-md-4"><input type="radio" name="sitesearch" value="" checked id="ss0" /> {LANG.search_on_internet}</div>
						<div class="col-md-4"><input type="radio" name="sitesearch" value="{MY_DOMAIN}" /> {LANG.search_on_nuke} {MY_DOMAIN}</div>
						<div class="col-md-4"></div>
					</div>
				</form>
			</div>
		</div>
</div>
<!-- END: results -->