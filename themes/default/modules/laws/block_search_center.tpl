<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">

<form id="ltablesearch{BLOCKID}" class="form-horizontal formsearch" action="{FORM_ACTION}" method="get" onsubmit="return nv_check_search_laws(this);">
    <!-- BEGIN: no_rewrite -->
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="search"/>
    <!-- END: no_rewrite -->
    <input type="hidden" name="is_advance" value="{IS_ADVANCE}"/>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.s_key}</label>
        <div class="col-sm-18 col-md-18">
            <div class="row">
                <div class="col-xs-19 col-md-19"><input class="form-control" id="ls_key" type="text" name="q" value="{Q}"/></div>
                <!-- BEGIN: is_advance_btn -->
                <div class="col-xs-5 col-md-5"><a href="#" class="<!-- BEGIN: is_advance_class -->advance<!-- END: is_advance_class -->" id="btn-search"><em class="fa fa-search">&nbsp;</em>{LANG_ADVANCE}</a></div>
                <!-- END: is_advance_btn -->
            </div>
        </div>
    </div>

    <div id="advance" <!-- BEGIN: is_advance -->style="display: none"<!-- END: is_advance --> >
        <!-- BEGIN: pubtime -->
        <div class="form-group">
            <label class="col-sm-6 col-md-6 control-label">{LANG.s_pubtime}</label>
            <div class="col-sm-18 col-md-18 form-inline">
                <input class="form-control" id="ls_from" style="width:110px" type="text" name="sfrom" value="{FROM}" readonly="readonly"/>
                {LANG.to}
                <input class="form-control" id="ls_to" style="width:110px" type="text" name="sto" value="{TO}" readonly="readonly"/>
            </div>
        </div>
        <!-- END: pubtime -->
        <div class="form-group">
            <label class="col-sm-6 col-md-6 control-label">{LANG.area}</label>
            <div class="col-sm-18 col-md-18">
                <select class="form-control" id="ls_area" name="area">
                    <!-- BEGIN: area -->
                    <option value="{KEY}"{SELECTED}>{TITLE}</option>
                    <!-- END: area -->
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-6 col-md-6 control-label">{LANG.cat}</label>
            <div class="col-sm-18 col-md-18">
                <select class="form-control" id="ls_cat" name="cat">
                    <!-- BEGIN: cat -->
                    <option value="{KEY}"{SELECTED}>{TITLE}</option>
                    <!-- END: cat -->
                </select>
            </div>
        </div>
        <!-- BEGIN: approval -->
        <div class="form-group">
            <label class="control-label col-sm-6 col-md-6">{LANG.approval}</label>
            <div class="col-sm-18 col-md-18">
                <select class="form-control" name="approval">
                    <!-- BEGIN: loop -->
                    <option value="{APP.key}"{APP.selected}>{APP.title}</option>
                    <!-- END: loop -->
                </select>
            </div>
        </div>
        <!-- END: approval -->
        <!-- BEGIN: exptime -->
        <div class="form-group">
            <label class="col-sm-6 col-md-6 control-label">{LANG.s_status}</label>
            <div class="col-sm-18 col-md-18">
                <select class="form-control" id="ls_status" name="status">
                    <!-- BEGIN: status -->
                    <option value="{status.id}"{status.selected}>{status.title}</option>
                    <!-- END: status -->
                </select>
            </div>
        </div>
        <!-- END: exptime -->
        <div class="form-group">
            <label class="col-sm-6 col-md-6 control-label">{LANG.s_signer}</label>
            <div class="col-sm-18 col-md-18">
                <select class="form-control" id="ls_signer" name="signer">
                    <!-- BEGIN: signer -->
                    <option value="{KEY}"{SELECTED}>{TITLE}</option>
                    <!-- END: signer -->
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-6 col-md-6 control-label">{LANG.subject}</label>
            <div class="col-sm-18 col-md-18">
                <select class="form-control" id="ls_subject" name="subject">
                    <!-- BEGIN: subject -->
                    <option value="{KEY}"{SELECTED}>{TITLE}</option>
                    <!-- END: subject -->
                </select>
            </div>
        </div>
        <!-- BEGIN: examine -->
        <div class="form-group">
            <label class="col-sm-6 col-md-6 control-label">{LANG.examine}</label>
            <div class="col-sm-18 col-md-18">
                <select class="form-control" name="examine">
                    <option value="0">--{LANG.examine}--</option>
                    <!-- BEGIN: loop -->
                    <option value="{EXAMINE.key}"{EXAMINE.selected}>{EXAMINE.title}</option>
                    <!-- END: loop -->
                </select>
            </div>
        </div>
        <!-- END: examine -->
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">&nbsp;</label>
        <div class="col-sm-18 col-md-18">
            <input class="btn btn-primary" type="submit" value="{LANG.search}"/>
            <input class="btn btn-danger" id="lclearform{BLOCKID}" type="button" value="{LANG.clear}"/>
        </div>
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#lclearform{BLOCKID}').click(function() {
        $('#ltablesearch{BLOCKID} input[type=text]').val('');
        $('#ltablesearch{BLOCKID} select').val('0');
    });
    $("#ls_from,#ls_to").datepicker({
        showOn : "both",
        dateFormat : "dd/mm/yy",
        yearRange: "2000:2025",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly : true
    });
    $('#btn-search').click(function(e) {
        e.preventDefault();
        var a = $(this);
        if( a.hasClass('advance')){
            $('#advance').slideDown();
            a.html('<em class="fa fa-search">&nbsp;</em>{LANG.search_simple}');
            a.removeClass('advance');
            $('input[name="is_advance"]').val(1);
        } else {
            $('#advance').slideUp();
            a.html('<em class="fa fa-search">&nbsp;</em>{LANG.search_advance}');
            a.addClass('advance');
            $('input[name="is_advance"]').val(0);
        }
    });
});
function nv_check_search_laws(data) {
    if (($('#ls_key').val() == '' ) && ($('#ls_cat').val() == 0 ) && ($('#ls_area').val() == 0 ) && ($('#ls_subject').val() == 0 ) && ($('#ls_signer').val() == 0 ) && ($('#ls_status').val() == 0 ) && ($('#ls_from').val() == '' ) && ($('#ls_to').val() == '' )) {
        alert('{LANG.search_alert}');
        return false;
    }
    return true;
}
</script>
<!-- END: main -->