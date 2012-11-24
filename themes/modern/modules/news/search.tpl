<!-- BEGIN: main -->
<div class="box-border-shadow news-search content-box clearfix m-bottom">
	<h3 class="title-search">{LANG.info_title}</h3>
	<form action="{BASE_URL_SITE}" name="fsea" method="get" id="fsea">
				<div class="rows clearfix">
					<label>{LANG.key_title} :</label>
					<input type="text" name="q" value="{KEY}" class="input" id="key"/>
				</div>
				<div class="rows clearfix">
					<label>{LANG.type_title} :</label>
					<select name="choose" id="choose" class ="sl-choose">
						<option value="0" {CHECK1}>{LANG.find_all}  </option>
						<option value="1" {CHECK1}>{LANG.find_content}  </option>
						<option value="2" {CHECK2}>{LANG.find_author}  </option>
						<option value="3" {CHECK3}>{LANG.find_resource}  </option>
					</select>
				</div>
				<div class="rows clearfix">
					<label>{LANG.search_cat} : </label>
					<select name="catid" class ="sl-choose">
					<!-- BEGIN: search_cat -->
						 <option value="{SEARCH_CAT.catid}" {SEARCH_CAT.select}>{SEARCH_CAT.title}</option>
					<!-- END: search_cat -->
					</select>
				</div>
				<div class="rows clearfix">
					<label>{LANG.finter_title} :</label>
					<input class="input" name="to_date" id="to_date" value="{TO_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text"/>
					<img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'to_date', 'dd.mm.yyyy', true);" alt="Calendar" height="17" />{LANG.to_date}
					<input name="from_date" id="from_date" value="{FROM_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text" /><img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'from_date', 'dd.mm.yyyy', true);" alt="Calendar icon" height="17" />
					<img src="{NV_BASE_SITEURL}images/refresh.png" onclick="remove_text()" style="cursor:pointer; vertical-align:middle;" alt="Empty Calendar form"/>
				</div>
				<div class="rows clearfix">
					<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
					<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
					<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />
					<input class="button" type="submit" value="{LANG.search_title}"/>
				</div>
	</form>
</div>
<!-- END: main --><!-- BEGIN: results -->
<div class="box-border-shadow news-search content-box clearfix">
    <h3 class="title-search">{LANG.search_on} {TITLE_MOD}</h3>
    <!-- BEGIN: noneresult -->
    <div class="result-content">
        {LANG.search_none} : "<b>{KEY}</b>" {LANG.search_in_module} <b>{INMOD}</b>
    </div>
    <!-- END: noneresult -->
        <!-- BEGIN: result -->
		<div class="box-border-shadow m-bottom listz-newsbox-border-shadow m-bottom listz-news">
			<div class="content-box clearfix">
				<!-- BEGIN: result_img -->
               <a title="{TITLEROW}" href="{LINK}"><img src="{IMG_SRC}" class="s-border fl left" width="{IMG_WIDTH}" alt="{TITLEROW}" /></a>
               <!-- END: result_img -->
            <h4><a title="{TITLEROW}" href="{LINK}">{TITLEROW}</a></h4>
			<p>
				   {CONTENT}
			</p>
		</div>
		<div class="info small">
			{LANG.pubtime}: {TIME} | {LANG.author}: {AUTHOR} | {LANG.source_title} : {SOURCE}
		</div>
    </div>
        <!-- END: result -->
		<!-- BEGIN: pages_result -->
        <div class="cl-viewpages">
            {VIEW_PAGES}
        </div>
        <!-- END: pages_result -->
    <div class="cl-info">
        <i>{LANG.search_sum_title} {NUMRECORD} {LANG.result_title} 
            <br />
            {LANG.info_adv}
        </i>
    </div>
</div>
<div class="result-frame">
    <div>
        <b>{LANG.search_adv_internet} :</b>
    </div>
    <div>
        <center>
            <form method="get" action="http://www.google.com/search" target="_top">
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

                        <input type="radio" name="sitesearch" value="{MY_DOMAIN}" />{LANG.search_on_nuke} {MY_DOMAIN}
                    </tr>
                </table>
                </center>
            </div>
            </div><!-- END: results -->
