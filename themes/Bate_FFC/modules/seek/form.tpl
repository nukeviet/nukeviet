<!-- BEGIN: main -->
<div class="page panel panel-default">
    <div class="panel-body">
        <h3 class="text-center margin-bottom-lg">{LANG.info_title}</h3>
        <div id="search-form" class="text-center">
            <form action="{DATA.action}" name="form_search" method="get" id="form_search" role="form">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
                <div class="m-bottom">
                    <div class="form-group">
                        <label class="sr-only" for="search_query">{LANG.key_title}</label>
                        <input class="form-control" id="search_query" name="q" value="{DATA.key}" maxlength="{NV_MAX_SEARCH_LENGTH}" placeholder="{LANG.key_title}" />
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="search_query_mod">{LANG.type_search}</label>
                        <select name="m" id="search_query_mod" class="form-control">
                            <option value="all">{LANG.search_on_site}</option>
                            <!-- BEGIN: select_option -->
                            <option data-adv="{MOD.adv_search}" value="{MOD.value}"{MOD.selected}>{MOD.custom_title}</option>
                            <!-- END: select_option -->
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" id="search_submit" value="{LANG.search_title}" class="btn btn-primary" />
                        <a href="#" class="advSearch">{LANG.search_title_adv}</a>
                    </div>
                </div>
                <div class="radio">
                    <label class="radio-inline"> <input name="l" id="search_logic_and" type="radio" {DATA.andChecked} value="1" /> {LANG.logic_and}</label>
                    <label class="radio-inline"> <input name="l" id="search_logic_or" type="radio" {DATA.orChecked} value="0" /> {LANG.logic_or}</label>
                </div>
                <input type="hidden" name="page" value="{PAGE}" />
            </form>
        </div>
        <!-- BEGIN: search_engine_unique_ID -->
        <script async src="//cse.google.com/cse.js?cx={SEARCH_ENGINE_UNIQUE_ID}"></script>
        <div class="text-center margin-bottom-lg search_adv">
            <a href="javascript:void(0);" class="IntSearch"><i class="fa fa-eye" aria-hidden="true"></i> {LANG.search_adv_internet}</a>
        </div>
        <div id="gcse" class="hidden">
            <div class="gcse-search"></div>
        </div>
        <!-- END: search_engine_unique_ID -->
        <div id="search_result">
            <hr />
            {SEARCH_RESULT}
        </div>
    </div>
</div>
<script type="text/javascript">
function show_advSearch() {
    var data = $('#search_query_mod').find('option:selected').data();
    if (data.adv == true) {
        $("a.advSearch").show();
    } else if (data.adv == false) {
        $("a.advSearch").hide();
    } else {
        $("a.advSearch").show();
    }
}
$(function() {
    show_advSearch();
});
$('#search_query_mod').change(function() {
    show_advSearch();
});
$("a.advSearch").click(function(e) {
    e.preventDefault();
    var b = $("#form_search #search_query_mod").val();
    if ("all" == b) {
        return alert("{LANG.chooseModule}"), $("#form_search #search_query_mod").focus(), !1
    }
    var b = nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + b + "&" + nv_fc_variable + "=search", a = $("#form_search #search_query").val(), a = strip_tags(a);
    {NV_MIN_SEARCH_LENGTH} <= a.length && {NV_MAX_SEARCH_LENGTH} >= a.length && (a = rawurlencode(a), b = b + "&q=" + a);

    window.location.href = b;
});
$("a.IntSearch").click(function(e) {
    e.preventDefault();
    $(".fa", this).toggleClass("fa-eye fa-eye-slash");
    $("#search-form, #gcse, #search_result").toggleClass("hidden")
});
$("#form_search").submit(function() {
    var a = $("#form_search [name=q]").val(), a = strip_tags(a), b;
    $("#form_search [name=q]").val(a);
    if ({NV_MIN_SEARCH_LENGTH} > a.length || {NV_MAX_SEARCH_LENGTH} < a.length) {
        return $("#form_search [name=q]").select(), !1
    }
    return true;
});
</script>
<!-- END: main -->
