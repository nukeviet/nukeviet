<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/popcalendar/popcalendar.js">
</script>
<form action="{BASE_URL_SITE}" name="fsea" method="get" id="fsea">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /><input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /><input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />
    <div class = "sea-frame">
        <div class="sea-title">
            {LANG.info_title}
        </div>
        <div class="sea-form">
            <table cellspacing="0" cellpadding="3">
                <tr>
                    <td class="cltxt">
                        {LANG.key_title} : 
                    </td>
                    <td>
                        <input type="text" name="q" value="{KEY}" class="intxt" id="key"/>
                    </td>
                    <td align="right">
                    </td>
                </tr>
                <tr>
                    <td class="cltxt">
                        {LANG.type_title} : 
                    </td>
                    <td>
                        <select name="choose" id="choose" class ="sl-choose">
                            <option value="0" {CHECK1}>{LANG.find_all}  </option>
                            <option value="1" {CHECK1}>{LANG.find_content}  </option>
                            <option value="2" {CHECK2}>{LANG.find_author}  </option>
                            <option value="3" {CHECK3}>{LANG.find_resource}  </option>
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td class="cltxt">
                        {LANG.search_cat} : 
                    </td>
                    <td>
                        <select name="catid" class ="sl-choose">
						<!-- BEGIN: search_cat -->
						     <option value="{SEARCH_CAT.catid}" {SEARCH_CAT.select}>{SEARCH_CAT.title}</option>
						<!-- END: search_cat -->
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>                
                <tr>
                    <td class="cltxt">
                        {LANG.finter_title} : 
                    </td>
                    <td>
                        <input name="to_date" id="to_date" value="{TO_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text"/>
						<img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'to_date', 'dd.mm.yyyy', true);" alt="" height="17" />{LANG.to_date}<input name="from_date" id="from_date" value="{FROM_DATE}" style="width:90px;" maxlength="10" readonly="readonly" type="text" /><img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'from_date', 'dd.mm.yyyy', true);" alt="" height="17" />
                    </td>
                    <td class="cltxt">
                        <img src="{NV_BASE_SITEURL}images/refresh.png" onclick="remove_text()" style="cursor:pointer"/>
                    </td>
                </tr>
                <tr>
                    <td class="cltxt">
                    </td>
                    <td>
                        <input type="submit" value="{LANG.search_title}"/>
                    </td>
                    <td align="right">
                    </td>
                </tr>
                
            </table>
        </div>
    </div>
</form>
<!-- END: main -->
<!-- BEGIN: results -->
<div class="result-frame">
    <div class="result-title">
        {LANG.search_on} {TITLE_MOD}
    </div>
    <!-- BEGIN: noneresult -->
    <div class="result-content">
        {LANG.search_none} : "<b>{KEY}</b>" {LANG.search_in_module} <b>{INMOD}</b>
    </div>
    <!-- END: noneresult -->
    <div class="cl-result">
        <!-- BEGIN: result -->
        <div class="linktitle">
            <a href="{LINK}">{TITLEROW}</a>
        </div>
        <div class="result-content">
        <p>
               <!-- BEGIN: result_img -->
               <img src="{IMG_SRC}" border="0" width="{IMG_WIDTH}px" style="float:left; margin-right:5px;" />
               <!-- END: result_img -->
               {CONTENT}
        </p>
        </div>
        <div class="result-author">
            {AUTHOR}
        </div>
        <div class="result-source">
            {LANG.source_title} : <span>{SOURCE}</span>
        </div>
        <!-- END: result --><!-- BEGIN: pages_result -->
        <div class="cl-viewpages">
            {VIEW_PAGES}
        </div>
        <!-- END: pages_result -->
    </div>
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
