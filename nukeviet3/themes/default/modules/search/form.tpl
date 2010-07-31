<!-- BEGIN: main -->
<form action="{BASE_URL_SITE}" name="fsea" method="get" id="fsea">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /><input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <div class="sea-frame">
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
                        <input type="text" name="q" value="{KEY}" class="intxt" id="q" />
                    </td>
                </tr>
                <tr>
                    <td class="cltxt">
                        {LANG.type_search} :
                    </td>
                    <td>
                        <select class="sl2" name="mod" onchange="change()" id="slmod">
                            <option value="all">{LANG.search_on_site}</option>
                            <!-- BEGIN: select_option --><option value="{SELECT_VALUE}" selected="selected">{SELECT_NAME}</option>
                            <!-- END: select_option --><!-- BEGIN: option_loop --><option value="{SELECT_VALUE}">{SELECT_NAME}</option>
                            <!-- END: option_loop -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="cltxt">
                    </td>
                    <td>
                        <input type="submit" value="{LANG.search_title}" /><input type="button" value="{LANG.search_title_adv}" id="btadv" onclick="GoUrl('{URL_SEARCH_ADV}')"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form><!-- END: main --><!-- BEGIN: results -->
<div class="result-frame">
    <!-- BEGIN: loop_result -->
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
            {CONTENT}
        </div>
        <!-- END: result --><!-- BEGIN: limit_result -->
        <div class="cl-viewall">
            <a href="{URL_VIEW_ALL}"><i>{LANG.view_all_title} &gt;&gt;</i></a>
        </div>
        <!-- END: limit_result --><!-- BEGIN: pages_result -->
        <div class="cl-viewpages">
            {VIEW_PAGES}
        </div>
        <!-- END: pages_viewpages -->
    </div>
    <!-- END: loop_result -->
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
    <div>
        <center>
            <form method="get" action="http://www.google.com.vn/custom" target="_top">
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
                            <input type="radio" name="sitesearch" value="{MY_DOMAIN}" />{MY_DOMAIN}
                        </td>
                    </tr>
                </table>
            </form>
        </center>
    </div>
</div><!-- END: results -->
