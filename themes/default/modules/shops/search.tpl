<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<div class="search">
	<h3 class="text-center">{LANG.search_title}</h3>
	<hr />
	<form action="{BASE_URL_SITE}index.php" name="fsea" method="get" id="fsea" class="form-horizontal">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />

        <div class="form-group">
            <label class="col-sm-4 control-label">{LANG.key_title}</span></label>
            <div class="col-sm-40">
                <input type="text" name="q" value="{KEY}" class="form-control" id="key"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">{LANG.search_cat}</span></label>
            <div class="col-sm-20">
                <select name="catid" class ="form-control">
                    <!-- BEGIN: search_cat -->
                    <option value="{SEARCH_CAT.catid}" {SEARCH_CAT.select}>{SEARCH_CAT.xtitle}{SEARCH_CAT.title}</option>
                    <!-- END: search_cat -->
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">{LANG.from_date}</span></label>
            <div class="col-sm-20">
                <input name="to_date" id="to_date" value="{TO_DATE}" class="form-control" style="width: 90px; display: inline" maxlength="10" readonly="readonly" type="text"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">{LANG.to_date}</span></label>
            <div class="col-sm-20">
                <input name="from_date" id="from_date" value="{FROM_DATE}" class="form-control" style="width:90px; display: inline" maxlength="10" readonly="readonly" type="text" />
            </div>
        </div>

		<div class="text-center">
			<input class="btn btn-primary" type="submit" value="{LANG.search_title}"/>
			<input class="btn btn-warning" type="button" value="{LANG.search_reset}" id="reset"/>
		</div>
	</form>
</div>

<script type="text/javascript" data-show="after" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" data-show="after" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" data-show="after">
	$(document).ready(function() {
		$("#to_date,#from_date").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
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
                <img src="{IMG_SRC}" width="100px" class="img-thumbnail pull-left image" />
                <!-- END: result_img -->
                {CONTENT}
            </p>
        </div>

		<!-- BEGIN: adminlink -->
		<div class="pull-right">
			{ADMINLINK}
		</div>
		<!-- END: adminlink -->
		<div class="clear"></div>
		<hr />
		<!-- END: result -->

		<!-- BEGIN: pages_result -->
		<div class="text-center">
			{VIEW_PAGES}
		</div>
		<!-- END: pages_viewpages -->

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
                    <div class="col-md-8"><img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" /></div>
                    <div class="col-md-8"><input type="text" name="q" maxlength="255" value="{KEY}" id="sbi" class="form-control" /></div>
                    <div class="col-md-8"><input type="submit" name="sa" value="{LANG.search_title}" id="sbb" class="btn btn-default"></div>
                </div>

                <div class="form-group">
                    <div class="col-md-8"><input type="radio" name="sitesearch" value="" checked id="ss0" /> {LANG.search_on_internet}</div>
                    <div class="col-md-8"><input type="radio" name="sitesearch" value="{MY_DOMAIN}" /> {LANG.search_on_nuke} {MY_DOMAIN}</div>
                    <div class="col-md-8"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: results -->