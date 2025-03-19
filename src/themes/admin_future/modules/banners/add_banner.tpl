{if $data.upload_blocked}
<div class="alert alert-danger">{$data.upload_blocked}</div>
{/if}
<div class="alert alert-danger">{$data.info}</div>

<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form id="frm" method="post" enctype="multipart/form-data" action="{$data.action}" class="form-inline">
    <input type="hidden" value="1" name="save" id="save" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col class="w300" />
            <col class="w20">
            <col>
            <tbody>
                <tr>
                    <td>{$data.title.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td><input class="w300 required form-control" name="{$data.title.1}" type="text" value="{$data.title.2}" maxlength="{$data.title.3}" /></td>
                </tr>
                <tr>
                    <td>{$data.plan.0}:</td>
                    <td><sup class="required">&lowast;</sup></td>
                    <td>
                        <select id="banner_plan" name="{$data.plan.1}" class="form-control w300 pull-left" onchange="chancePlan()" onload="chancePlan()">
                            {foreach $plans as $key=>$val}
                            <option value="{$key}" data-exp="{if $data.plan.6.$key > 0} true {else} false {/if}" data-rimage="{if $data.plan.5.$key > 0} true {else} false {/if}" {if $key==$data.plan.3} selected="selected" {/if}>{$val}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{$LANG->getModule("assign_to_user")}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="col-auto">
                            <div class="input-group">
                                <div class="autosearchpersion w300 pull-left" data-checkss="{$smarty.const.NV_CHECK_SESSION}">
                                    <span class="searchloading visually-hidden"><i class="fa fa-spin fa-spinner"></i></span>
                                    <input type="text" class="form-control" name="assign_user" value="{$data.assign_user}" autocomplete="off" />
                                    <div class="searchresultaj"></div>
                                </div>
                                <div class="pull-left margin-left">
                                    <a href="javascript:void(0);" title="{$LANG->getModule("assign_to_user_tip")}" data-bs-toggle="tooltip" class="form-info-circle"><i class="fa fa-info-circle"></i></a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{$data.upload.0}:</td>
                    <td><sup class="required" id="require_image"></sup></td>
                    <td><input name="{$data.upload.1}" type="file" /></td>
                </tr>
                <tr>
                    <td>{$data.upload.2}:</td>
                    <td>&nbsp;</td>
                    <td><input name="{$data.upload.3}" type="file" /></td>
                </tr>
                <tr>
                    <td>{$data.file_alt.0}:</td>
                    <td>&nbsp;</td>
                    <td><input class="form-control w300" name="{$data.file_alt.1}" type="text" value="{$data.file_alt.2}" maxlength="{$data.file_alt.3}" /></td>
                </tr>
                <tr>
                    <td>{$data.click_url.0}:</td>
                    <td>&nbsp;</td>
                    <td><input class="form-control w300" name="{$data.click_url.1}" type="text" value="{$data.click_url.2}" maxlength="{$data.click_url.3}" /></td>
                </tr>
                <tr>
                    <td>{$data.target.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <select name="{$data.target.1}" class="form-control w300">
                            {foreach $data.target.2 as $key=>$val}
                            <option value="{$key}" {if $key==$data.target.3} selected="selected" {/if}>{$val}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{$data.publ_date.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input name="{$data.publ_date.1}" id="publ_date" value="{$data.publ_date.2}" class="form-control" style="width: 100px;" readonly="readonly" type="text" />
                                    <span class="input-group-btn pull-left">
                                        <button class="btn btn-default" type="button" id="publ_date-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <select class="form-control w70" name="publ_date_h" id="publ_date_h">
                                    {for $var=0 to 23}
                                    <option value="{$var}" {if $var==$data.publ_date.3} selected="selected" {/if}>{$var|string_format:"%02d"}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select class="form-control w70" name="publ_date_m" id="publ_date_m">
                                    {for $var=0 to 59}
                                    <option value="{$var}" {if $var==$data.publ_date.4} selected="selected" {/if}>{$var|string_format:"%02d"}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0);" class="form-info-circle" data-toggle="delval" data-target="#publ_date" data-select="#publ_date_h,#publ_date_m"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                            </div>
                            <span class="help-block help-block-bottom">{$LANG->getModule("publ_time_info")}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{$data.exp_date.0}:</td>
                    <td>&nbsp;</td>
                    <td>
                        <div class="row clearfix" id="exp_date_manual">
                            <div class="col-md-3">
                                <div class="input-group pull-left">
                                    <input name="{$data.exp_date.1}" id="exp_date" value="{$data.exp_date.2}" class="form-control" style="width: 100px;" readonly="readonly" type="text" />
                                    <span class="input-group-btn pull-left">
                                        <button class="btn btn-default" type="button" id="exp_date-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <select class="form-control w70" name="exp_date_h" id="exp_date_h">
                                    {for $var=0 to 23}
                                    <option value="{$var}" {if $var==$data.exp_date.3} selected="selected" {/if}>{$var|string_format:"%02d"}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select class="form-control w70" name="exp_date_m" id="exp_date_m">
                                    {for $var=0 to 59}
                                    <option value="{$var}" {if $var==$data.exp_date.4} selected="selected" {/if}>{$var|string_format:"%02d"}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0);" class="form-info-circle" data-toggle="delval" data-target="#exp_date" data-select="#exp_date_h,#exp_date_m"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                            </div>
                            <span class="help-block help-block-bottom">{$LANG->getModule("exp_date_nochoose")}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p class="m-bottom">{$data.bannerhtml.0}:</p>
                        {$data.bannerhtml.1}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <input type="submit" value="{$data.submit}" class="btn btn-primary" />
    </div>
    <div id="demo"></div>
</form>
<script type="text/javascript">
$(document).ready(function() {
    chancePlan();
    $('#frm').validate();
    $("#publ_date,#exp_date").datepicker({
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        showOn: 'focus'
    });

    $('#publ_date-btn').click(function(){
        $("#publ_date").datepicker('show');
    });

    $('#exp_date-btn').click(function(){
        $("#exp_date").datepicker('show');
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
