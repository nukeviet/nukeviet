<!-- BEGIN: upload_blocked -->
<div class="alert alert-info">{CONTENTS.upload_blocked}</div>
<!-- END: upload_blocked -->
<!-- BEGIN: main -->
<div class="alert alert-info">{CONTENTS.info}</div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form id="frm" method="post" enctype="multipart/form-data" action="{CONTENTS.action}">
    <input type="hidden" value="1" name="save" id="save" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col class="w250" />
            <col class="w50" />
            <col />
            <tbody>
                <tr>
                    <td>{CONTENTS.title.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td><input name="{CONTENTS.title.1}" class="w300 required form-control" type="text" value="{CONTENTS.title.2}" maxlength="{CONTENTS.title.3}" /></td>
                </tr>
                <tr>
                    <td>{CONTENTS.plan.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td>
                        <select id="banner_plan" name="{CONTENTS.plan.1}" class="form-control w300" onchange="chancePlan()" onload="chancePlan()">
                            <!-- BEGIN: plan -->
                            <option value="{PLAN.key}" data-exp="{PLAN.exp_time}" data-rimage="{PLAN.require_image}" {PLAN.selected}>{PLAN.title}</option>
                            <!-- END: plan -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.assign_to_user}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="autosearchpersion w300 pull-left" data-checkss="{NV_CHECK_SESSION}">
                            <span class="searchloading hidden"><i class="fa fa-spin fa-spinner"></i></span>
                            <input type="text" class="form-control" name="assign_user" value="{CONTENTS.assign_user}" autocomplete="off"/>
                            <div class="searchresultaj"></div>
                        </div>
                        <a href="javascript:void(0);" title="{LANG.assign_to_user_tip}" data-toggle="tooltip" class="form-info-circle"><i class="fa fa-info-circle"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.file_name}:</td>
                    <td><sup class="required" id="require_image"></sup></td>
                    <td>
                        <!-- BEGIN: img_info -->
                        <div id="current_image">
                            <a href="javascript:void(0)" data-src="{CONTENTS.file_name.1}" {CONTENTS.file_name.2}><img alt="{CONTENTS.file_name.4}" src="{CONTENTS.file_name.3}" width="16" height="16" style="cursor: pointer" /></a>
                            <a href="#" data-toggle="remove_banner">{GLANG.delete}</a>
                        </div>
                        <!-- END: img_info -->
                        <div id="upload_image"{SHOW_BANNER}>
                            <input type="hidden" name="remove_banner" value="{CONTENTS.remove_banner}">
                            <input name="{CONTENTS.upload.1}" type="file">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{CONTENTS.upload.2}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <!-- BEGIN: imageforswf -->
                        <div id="current_imageforswf">
                            <a href="javascript:void(0)" data-src="{CONTENTS.imageforswf.0}" class="open_modal_image" {CONTENTS.file_name.2}><img alt="{CONTENTS.file_name.4}" src="{CONTENTS.imageforswf.1}" width="16" height="16" style="cursor: pointer;" /></a>
                            <a href="#" data-toggle="remove_imageforswf">{GLANG.delete}</a>
                        </div>
                        <!-- END: imageforswf -->
                        <div id="upload_imageforswf"{SHOW_IMAGEFORSWF}>
                            <input type="hidden" name="remove_imageforswf" value="{CONTENTS.remove_imageforswf}">
                            <input name="{CONTENTS.upload.3}" type="file">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{CONTENTS.file_alt.0}:</td>
                    <td>&nbsp;</td>
                    <td><input class="w300 form-control" name="{CONTENTS.file_alt.1}" type="text" value="{CONTENTS.file_alt.2}" maxlength="{CONTENTS.file_alt.3}" /></td>
                </tr>
                <tr>
                    <td>{CONTENTS.click_url.0}:</td>
                    <td>&nbsp;</td>
                    <td><input class="w300 url form-control" name="{CONTENTS.click_url.1}" type="text" value="{CONTENTS.click_url.2}" maxlength="{CONTENTS.click_url.3}" /></td>
                </tr>
                <tr>
                    <td>{CONTENTS.target.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                    <select name="{CONTENTS.target.1}" class="form-control w200">
                        <!-- BEGIN: target -->
                        <option value="{TARGET.key}"{TARGET.selected}>{TARGET.title}</option>
                        <!-- END: target -->
                    </select></td>
                </tr>
                <tr>
                    <td>{CONTENTS.publ_date.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="clearfix">
                            <div class="input-group pull-left">
                                <input name="{CONTENTS.publ_date.1}" id="publ_date" value="{CONTENTS.publ_date.2}" class="form-control" style="width: 100px;" readonly="readonly" type="text" />
                                <span class="input-group-btn pull-left">
                                    <button class="btn btn-default" type="button" id="publ_date-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
                                </span>
                            </div>
                            <select class="form-control pull-left margin-left w70" name="publ_date_h" id="publ_date_h">
                                <!-- BEGIN: h_pub --><option value="{HOUR.key}"{HOUR.pub_selected}>{HOUR.title}</option><!-- END: h_pub -->
                            </select>
                            <select class="form-control pull-left margin-left w70" name="publ_date_m" id="publ_date_m">
                                <!-- BEGIN: m_pub --><option value="{MIN.key}"{MIN.pub_selected}>{MIN.title}</option><!-- END: m_pub -->
                            </select>
                            <a href="javascript:void(0);" class="form-info-circle" data-toggle="delval" data-target="#publ_date" data-select="#publ_date_h,#publ_date_m"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                        </div>
                        <span class="help-block help-block-bottom">{LANG.publ_time_info}</span>
                    </td>
                </tr>
                <tr>
                    <td>{CONTENTS.exp_date.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="clearfix" id="exp_date_manual">
                            <div class="clearfix">
                                <div class="input-group pull-left">
                                    <input name="{CONTENTS.exp_date.1}" id="exp_date" value="{CONTENTS.exp_date.2}" class="form-control" style="width: 100px;" readonly="readonly" type="text" />
                                    <span class="input-group-btn pull-left">
                                        <button class="btn btn-default" type="button" id="exp_date-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
                                    </span>
                                </div>
                                <select class="form-control pull-left margin-left w70" name="exp_date_h" id="exp_date_h">
                                    <!-- BEGIN: h_exp --><option value="{HOUR.key}"{HOUR.exp_selected}>{HOUR.title}</option><!-- END: h_exp -->
                                </select>
                                <select class="form-control pull-left margin-left w70" name="exp_date_m" id="exp_date_m">
                                    <!-- BEGIN: m_exp --><option value="{MIN.key}"{MIN.exp_selected}>{MIN.title}</option><!-- END: m_exp -->
                                </select>
                                <a href="javascript:void(0);" class="form-info-circle" data-toggle="delval" data-target="#exp_date" data-select="#exp_date_h,#exp_date_m"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                            </div>
                            <span class="help-block help-block-bottom">{LANG.exp_date_nochoose}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p class="m-bottom">{LANG.bannerhtml}:</p>
                        {CONTENTS.bannerhtml}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <input type="submit" value="{CONTENTS.submit}" class="btn btn-primary" />
    </div>
</form>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{LANG.file_name}</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    chancePlan();
    $('#frm').validate();
    $("#publ_date,#exp_date").datepicker({
        showOn : "both",
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly : true
    });

    $('#publ_date-btn').click(function(){
        $("#publ_date").datepicker('show');
    });

    $('#exp_date-btn').click(function(){
        $("#exp_date").datepicker('show');
    });

    $(".open_modal_image").on("click", function() {
        $('#imagemodal .modal-body').html('<div class="text-center"><img class="img-banner-preview" src="' + $(this).data('src') + '"></div>');
        $('#imagemodal').modal('show');
    });

    // Xóa banner hiện tại upload lại
    $('[data-toggle="remove_banner"]').on('click', function(e) {
        e.preventDefault();
        $('#current_image').addClass('hidden');
        $('#upload_image').removeClass('hidden');
        $('[name="remove_banner"]').val('1');
    });

    // Xóa ảnh trên mobile upload lại
    $('[data-toggle="remove_imageforswf"]').on('click', function(e) {
        e.preventDefault();
        $('#current_imageforswf').addClass('hidden');
        $('#upload_imageforswf').removeClass('hidden');
        $('[name="remove_imageforswf"]').val('1');
    });
});
function chancePlan() {
    var plsel = $('#banner_plan option:selected');
    if (plsel.data('rimage')) {
        document.getElementById("require_image").innerHTML = "&lowast;";
    } else {
        document.getElementById("require_image").innerHTML = "&nbsp;";
    }
}
</script>
<!-- END: main -->
