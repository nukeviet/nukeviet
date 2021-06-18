<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    var items = '{NEW_ITEM_NUM}';
</script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form id="votingcontent" method="post" action="{FORM_ACTION}">
    <div class="row">
        <div class="col-sm-24 col-md-18">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <td>{LANG.voting_time}</td>
                            <td>
                                <table>
                                    <tr>
                                        <td><input name="publ_date" id="publ_date" value="{PUBL_DATE}" class="form-control w100 pull-left" maxlength="10" readonly="readonly" type="text" />&nbsp;</td>
                                        <td>
                                            <select name="phour" class="form-control w100">
                                                <!-- BEGIN: phour -->
                                                <option value="{PHOUR.key}"{PHOUR.selected}>{PHOUR.title}</option>
                                                <!-- END: phour -->
                                            </select>
                                        </td>
                                        <td>&nbsp;:&nbsp;</td>
                                        <td>
                                            <select name="pmin" class="form-control w100">
                                                <!-- BEGIN: pmin -->
                                                <option value="{PMIN.key}"{PMIN.selected}>{PMIN.title}</option>
                                                <!-- END: pmin -->
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>{LANG.voting_timeout}</td>
                            <td>
                                <table>
                                    <tr>
                                        <td><input name="exp_date" id="exp_date" value="{EXP_DATE}" class="form-control w100 pull-left" maxlength="10" readonly="readonly" type="text" />&nbsp;</td>
                                        <td>
                                            <select name="ehour" class="form-control w100">
                                                <!-- BEGIN: ehour -->
                                                <option value="{EHOUR.key}"{EHOUR.selected}>{EHOUR.title}</option>
                                                <!-- END: ehour -->
                                            </select>
                                        </td>
                                        <td>&nbsp;:&nbsp;</td>
                                        <td>
                                            <select name="emin" class="form-control w100">
                                                <!-- BEGIN: emin -->
                                                <option value="{EMIN.key}"{EMIN.selected}>{EMIN.title}</option>
                                                <!-- END: emin -->
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>{LANG.voting_question}<sup class="required">(*)</sup></td>
                            <td><input class="form-control" type="text" name="question" size="60" maxlength="{DATA.question_maxlength}" value="{DATA.question}" class="txt" required placeholder="{LANG.voting_question}"  oninvalid="this.setCustomValidity(nv_required)" oninput="this.setCustomValidity('')"/></td>
                        </tr>
                        <tr>
                            <td>{LANG.voting_link}</td>
                            <td><input class="form-control" type="text" name="link" size="60" value="{DATA.link}" class="txt" /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="items">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>{LANG.voting_answer}</th>
                                <th>{LANG.voting_link}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: item -->
                            <tr>
                                <td class="text-right">{LANG.voting_question_num} {ITEM.stt}</td>
                                <td><input class="form-control" type="text" value="{ITEM.title}" name="answervote[{ITEM.id}]" maxlength="245"/></td>
                                <td><input class="form-control" type="url" value="{ITEM.link}" name="urlvote[{ITEM.id}]"/></td>
                            </tr>
                            <!-- END: item -->
                            <tr>
                                <td class="text-right">{LANG.voting_question_num} {NEW_ITEM}<sup class="required">(*)</sup></td>
                                <td><input class="form-control" type="text" value="" name="answervotenews[]" maxlength="245"/></td>
                                <td><input class="form-control" type="text" value="" name="urlvotenews[]"/></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-24 col-md-6">
            <div class="row">
                <div class="col-sm-12 col-md-24 m-bottom">
                    <label><strong>{LANG.voting_maxoption}:</strong></label>
                    <input class="form-control w100" type="text" name="maxoption" size="5" value="{DATA.acceptcm}" class="txt" required pattern="^([0-9])+$" oninvalid="this.setCustomValidity(nv_digits)" oninput="this.setCustomValidity('')"/>
                </div>
                <div class="col-sm-12 col-md-24 m-bottom">
                    <label><strong>{GLANG.groups_view}:</strong></label>
                    <!-- BEGIN: groups_view -->
                    <div class="clearfix">
                        <label><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.value}" {GROUPS_VIEW.checked} />{GROUPS_VIEW.title}</label>
                    </div>
                    <!-- END: groups_view -->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-24">
                    <label><input type="checkbox" name="active_captcha" value="1"{DATA.active_captcha}/> <strong>{LANG.voting_active_captcha}</strong></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center">
        <input type="button" value="{LANG.add_answervote}" onclick="nv_vote_add_item('{LANG.voting_question_num}');" class="btn btn-info" />
        <input type="submit" name="submit" value="{LANG.voting_confirm}" class="btn btn-primary" />
    </div>
</form>
<script>
$(document).ready(function() {
    $("#publ_date,#exp_date").datepicker({
        showOn : "both",
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly : true
    });
});
</script>
<!-- END: main -->