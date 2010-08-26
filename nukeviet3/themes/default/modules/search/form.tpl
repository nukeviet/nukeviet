<!-- BEGIN: main -->
<input type="hidden" id="mydomain" value="{MY_DOMAIN}" />
<input type="hidden" id="confirm_search_on_internet" value="{LANG.search_on_internet}" />
<div class="sea-frame">
    <form action="{BASE_URL_SITE}" name="form_search" method="get" id="form_search">
        <div class="title">
            {LANG.info_title}
        </div>
        <div class="form">
            <dl class="clearfix" style="margin-bottom:5px">
                <dd class="fl" style="width:100px;text-align:left;">
                    <label>
                        {LANG.key_title}:
                    </label>
                </dd>
                <dt class="fl" style="text-align:left;">
                    <input class="intxt" id="search_query" name="q" value="{SEARCH_QUERY}" maxlength="{NV_MAX_SEARCH_LENGTH}" />
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl" style="width:100px;text-align:left;">
                    <label>
                        {LANG.type_search}:
                    </label>
                </dd>
                <dt class="fl" style="text-align:left;">
                    <select name="mod" id="search_query_mod">
                        <option value="all">{LANG.search_on_site}</option>
                        <!-- BEGIN: select_option -->
                        <option value="{MOD.value}"{MOD.selected}>{MOD.custom_title}</option>
                        <!-- END: select_option -->
                    </select>
                    <input type="hidden" id="search_checkss" value="{CHECKSS}" />
                    <input type="submit" id="search_submit" value="{LANG.search_title}" />&nbsp;&nbsp;
                    <a href="javascript:void(0);" onclick="GoUrl({NV_MIN_SEARCH_LENGTH}, {NV_MAX_SEARCH_LENGTH});">{LANG.search_title_adv}</a><br />
                    <strong>&raquo;</strong> <a href="javascript:void(0);" onclick="GoGoogle({NV_MIN_SEARCH_LENGTH}, {NV_MAX_SEARCH_LENGTH});">{LANG.search_adv_internet}</a>
                </dt>
            </dl>
        </div>
    </form>
</div>
<div id="search_result"></div>
<!-- BEGIN: is_key -->
<script type="text/javascript">
    nv_send_search( {NV_MIN_SEARCH_LENGTH}, {NV_MAX_SEARCH_LENGTH} );
</script>
<!-- AND: is_key -->
<!-- END: main -->
