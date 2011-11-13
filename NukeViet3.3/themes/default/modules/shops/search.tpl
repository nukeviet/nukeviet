<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/popcalendar/popcalendar.js">
</script>
<div class="search">
    <h3 class="title-search">{LANG.search_title}</h3>
    <form action="{BASE_URL_SITE}" name="fsea" method="get" id="fsea">
        <div class="rows clearfix">
            <span>{LANG.key_title} :</span>
            <input type="text" name="q" value="{KEY}" style="width:400px" id="key"/>
        </div>
        <div class="rows clearfix">
            <label>
                {LANG.search_cat} : 
            </label>
            <select name="catid" class ="sl-choose">
                <!-- BEGIN: search_cat -->
				<option value="{SEARCH_CAT.catid}" {SEARCH_CAT.select}>{SEARCH_CAT.xtitle}{SEARCH_CAT.title}</option>
                <!-- END: search_cat -->
            </select>
        </div>
        <div class="rows clearfix">
            <span>{LANG.finter_title} :</span>
            <input name="to_date" id="to_date" value="{TO_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text"/>
            <img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'to_date', 'dd.mm.yyyy', true);" alt="" height="17" />
			{LANG.to_date} &nbsp;
			<input name="from_date" id="from_date" value="{FROM_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text" />
			<img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'from_date', 'dd.mm.yyyy', true);" alt="" height="17" />
        </div>
        <div class="rows clearfix" align="center">
            <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
			<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
			<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />
			<input class="button" type="submit" value="{LANG.search_title}"/>
			<input class="button" type="button" value="{LANG.search_reset}" id="reset"/>
        </div>
    </form>
</div>
<script type="text/javascript">
  $("#reset").click(function(){
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
        <span class="linktitle">
            <a href="{LINK}">{TITLEROW}</a>
        </span>
        <div class="result-content">
            <!-- BEGIN: result_img --><img src="{IMG_SRC}" border="0" width="100px" style="float:left; margin-right:5px;"/><!-- END: result_img -->
			{CONTENT}<br />
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
            {LANG.info_adv}
        </i>
    </div>
</div>
<div class="result-frame">
    <div>
        <b>{LANG.search_adv_internet} :</b>
    </div>
    <center>
        <form method="get" action="http://www.google.com.vn/search" target="_top">
            <input type="hidden" name="domains" value="{MY_DOMAIN}" />
            <table width="100%">
                <tr>
                    <td align="center" width="100px">
                        <img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" />
                    </td>
                    <td align="left">
                        <input type="text" name="q" size="38" maxlength="255" value="{KEY}" id="sbi" />
                    </td>
                    <td>
                        <input type="submit" name="sa" value="{LANG.search_title}" id="sbb">
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td>
                    <input type="radio" name="sitesearch" value="" checked id="ss0" />
                    {LANG.search_on_internet}
                    <input type="radio" name="sitesearch" value="{MY_DOMAIN}"/>{LANG.search_on_nuke}
                </tr>
            </table>
        </form>
    </center>
</div>
<!-- END: results -->
