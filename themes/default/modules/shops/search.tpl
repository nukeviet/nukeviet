<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<div class="search">
	<h3 class="title-search">{LANG.search_title}</h3>
	<form action="{BASE_URL_SITE}index.php" name="fsea" method="get" id="fsea">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />
		<div class="rows clearfix">
			<span>{LANG.key_title} :</span>
			<input type="text" name="q" value="{KEY}" style="width:400px" id="key"/>
		</div>
		<div class="rows clearfix">
			<label> {LANG.search_cat} : </label>
			<select name="catid" class ="sl-choose">
				<!-- BEGIN: search_cat -->
				<option value="{SEARCH_CAT.catid}" {SEARCH_CAT.select}>{SEARCH_CAT.xtitle}{SEARCH_CAT.title}</option>
				<!-- END: search_cat -->
			</select>
		</div>
		<div class="rows clearfix">
			<span>{LANG.finter_title} :</span>
			<input name="to_date" id="to_date" value="{TO_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text"/>
			{LANG.to_date} &nbsp;
			<input name="from_date" id="from_date" value="{FROM_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text" />
		</div>
		<div class="rows clearfix" align="center">
			<input class="button" type="submit" value="{LANG.search_title}"/>
			<input class="button" type="button" value="{LANG.search_reset}" id="reset"/>
		</div>
	</form>
</div>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#to_date,#from_date").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});
	});
	$("#reset").click(function() {
		$('#from_date').val("");
		$('#to_date').val("");
		$('#key').val("");
	}); 
</script>
<!-- END: main -->
<!-- BEGIN: results -->
<div class="result-frame">
	<div class="result-title">
		<strong>{LANG.search_on} {TITLE_MOD}</strong>
	</div>
	<br />
	<!-- BEGIN: noneresult -->
	<div class="result-content">
		{LANG.search_none} : "<b>{KEY}</b>" {LANG.search_in_module} <b>{INMOD}</b>
	</div>
	<!-- END: noneresult -->
	<div class="cl-result">
		<!-- BEGIN: result -->
		<span class="linktitle"> <a href="{LINK}">{TITLEROW}</a> </span>
		<div class="result-content">
			<!-- BEGIN: result_img -->
			<img src="{IMG_SRC}" border="0" width="100px" style="float:left; margin-right:5px;"/>
			<!-- END: result_img -->
			{CONTENT}
			<br />
			<!-- BEGIN: adminlink -->
			<div class="fr">
				{ADMINLINK}
			</div>
			<!-- END: adminlink -->
			<div style="clear:both;"></div>
		</div>
		<!-- END: result -->
		<!-- BEGIN: pages_result -->
		<div class="cl-viewpages">
			{VIEW_PAGES}
		</div>
		<!-- END: pages_viewpages -->
	</div>
	<div class="cl-info">
		<i>{LANG.search_sum_title} {NUMRECORD} {LANG.result_title}
		<br>
		{LANG.info_adv} </i>
	</div>
</div>
<div class="result-frame">
	<div>
		<b>{LANG.search_adv_internet} :</b>
	</div>
	<center>
		<form method="get" action="http://www.google.com/search" target="_top">
			<input type="hidden" name="domains" value="{MY_DOMAIN}" />
			<table width="100%">
				<tr>
					<td align="center" width="100px"><img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" /></td>
					<td align="left"><input type="text" name="q" size="38" maxlength="255" value="{KEY}" id="sbi" /></td>
					<td><input type="submit" name="sa" value="{LANG.search_title}" id="sbb"></td>
				</tr>
			</table>
			<table width="100%">
				<tr>
					<td><input type="radio" name="sitesearch" value="" checked id="ss0" /> {LANG.search_on_internet} <input type="radio" name="sitesearch" value="{MY_DOMAIN}"/>{LANG.search_on_nuke}
				</tr>
			</table>
		</form>
	</center>
</div>
<!-- END: results -->