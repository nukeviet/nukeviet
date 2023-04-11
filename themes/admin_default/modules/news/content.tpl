<!-- BEGIN: main -->
<!-- BEGIN: large_sys_note -->
<div class="alert alert-info">{LARGE_SYS_MESSAGE}</div>
<!-- END: large_sys_note -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {error}
</div>
<!-- END: error -->

<!-- BEGIN: restore_note -->
<div class="alert alert-info">
    <i class="fa fa-spin fa-spinner"></i> {LANG.history_recovering}
</div>
<!-- END: restore_note -->

<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" />
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">

<form id="form-news-content" class="form-inline m-bottom confirm-reload" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post" onsubmit="return nv_validForm(this,'{MODULE_DATA}', '{ERROR_BODYTEXT}','{ERROR_CAT}');">
    <div class="row">
        <div class="alert alert-danger" id="show_error" style="display: none"></div>
        <div class="col-sm-24 col-md-18">
            <!-- BEGIN: report -->
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-red">
                    <a class="panel-heading{REPORT.collapsed}"  id="heading-reportlist" role="tab" data-toggle="collapse" data-parent="#accordion" href="#collapse-reportlist" aria-expanded="{REPORT.expanded}" aria-controls="collapse-reportlist">
                    <i class="fa fa-exclamation-triangle"></i> {LANG.report} (<strong>{REPORT.count}</strong>)
                    </a>
                    <div id="collapse-reportlist" class="panel-collapse collapse{REPORT.in}" role="tabpanel" aria-labelledby="heading-reportlist">
                        <div class="list-report" data-url="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=report" data-del-confirm="{LANG.report_del_confirm}">
                            <!-- BEGIN: loop -->
                            <div class="list-report-item item" data-id="{REPORT_DETAILS.id}">
                                <a class="report-title{REPORT_DETAILS.collapsed}" data-toggle="collapse" href="#report-{REPORT_DETAILS.id}" aria-expanded="{REPORT_DETAILS.expanded}" aria-controls="report-{REPORT_DETAILS.id}">{REPORT_DETAILS.orig_content_short}</a>
                                <div class="report-content collapse{REPORT_DETAILS.in}" id="report-{REPORT_DETAILS.id}">
                                    <div class="post_info">
                                        <span>{REPORT_DETAILS.post_info}</span>
                                        </div>
                                    <div class="orig_content_sector">
                                        <label><strong>{LANG.error_text}</strong></label>
                                        <div class="orig_content">{REPORT_DETAILS.orig_content}</div>
                                    </div>
                                    <!-- BEGIN: repl_content -->
                                    <div class="repl_content_sector">
                                        <label><strong>{LANG.proposal_text}</strong></label>
                                        <div class="repl_content">{REPORT_DETAILS.repl_content}</div>
                                    </div>
                                    <!-- END: repl_content -->
                                    <div class="post_action text-right">
                                        <button type="button" class="btn btn-sm btn-danger report_del_action">{GLANG.delete}</button>
                                        <button type="button" class="btn btn-sm btn-danger report_del_mail_action">{LANG.report_delete}</button>
                                    </div>
                                </div>
                            </div>
                            <!-- END: loop -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: report -->

            <table class="table table-striped table-bordered">
                <col class="w200" />
                <col />
                <tbody>
                    <tr>
                        <td><strong>{LANG.name}</strong>: <sup class="required">(∗)</sup></td>
                        <td><input type="text" maxlength="250" value="{rowcontent.title}" id="idtitle" name="title" class="form-control require" data-mess="{LANG.error_title}" onkeypress="nv_validErrorHidden(this);" style="width:350px" /><span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.alias}: </strong></td>
                        <td><input class="form-control" name="alias" id="idalias" type="text" value="{rowcontent.alias}" maxlength="250" style="width:350px" />&nbsp; <em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias();">&nbsp;</em></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.content_topic}: </strong></td>
                        <td>
                            <select class="form-control w300" name="topicid" id="topicid">
                                <!-- BEGIN: rowstopic -->
                                <option value="{topicid}" {sl}>{topic_title}</option>
                                <!-- END: rowstopic -->
                            </select><input class="form-control w200" type="text" maxlength="255" id="AjaxTopicText" value="{rowcontent.topictext}" name="topictext" />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.content_homeimg}</strong></td>
                        <td>
                            <div class="input-group mb-0" style="width:380px">
                                <input class="form-control" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}" />
                                <span class="input-group-btn">
                                    <button type="button" data-toggle="selectfile" data-target="homeimg" data-path="{UPLOADS_DIR_USER}" data-currentpath="{UPLOAD_CURRENT}" data-type="image" data-alt="homeimgalt" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>{LANG.content_homeimgalt}</td>
                        <td><input class="form-control" type="text" maxlength="255" value="{rowcontent.homeimgalt}" id="homeimgalt" name="homeimgalt" style="width:100%" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.imgposition}</td>
                        <td>
                            <select class="form-control" name="imgposition">
                                <!-- BEGIN: looppos -->
                                <option value="{id_imgposition}" {posl}>{title_imgposition}</option>
                                <!-- END: looppos -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>{LANG.content_hometext}</strong><i>{LANG.content_notehome}.</i> <br> {edit_hometext} </td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>{LANG.content_bodytext}</strong><sup class="required {rowcontent.style_content_bodytext_required}" id="content_bodytext_required">(∗)</sup><i>{LANG.content_bodytext_note}</i>
                            <div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
                                {edit_bodytext}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.auto_nav}</strong></td>
                        <td>
                            <input type="checkbox" value="1" name="auto_nav"{AUTO_NAV}/> {LANG.auto_nav_note}
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top"><strong>{LANG.fileattach}</strong></td>
                        <td>
                            <div id="filearea">
                                <!-- BEGIN: files -->
                                <div class="input-group m-bottom item" style="width:380px">
                                    <input class="form-control" type="text" name="files[]" id="file_{FILEUPL.id}" value="{FILEUPL.value}" title="{LANG.fileupload}" />
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="selectfile" data-target="file_{FILEUPL.id}" data-path="{UPLOAD_CURRENT}" data-currentpath="{UPLOAD_CURRENT}" data-type="file" class="btn btn-info" title="{GLANG.browse_file}"><em class="fa fa-folder-open-o"></em></button>
                                        <button type="button" class="btn btn-default" data-toggle="add_file">&plus;</button>
                                        <button type="button" class="btn btn-default" data-toggle="del_file">&times;</button>
                                    </span>
                                </div>
                                <!-- END: files -->
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.content_sourceid}</strong>:</td>
                        <td><input class="form-control" type="text" maxlength="255" value="{rowcontent.sourcetext}" name="sourcetext" id="AjaxSourceText" style="width:100%" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.content_external_link}:</td>
                        <td><input type="checkbox" value="1" name="external_link" {external_link_checked} /></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-24 col-md-6">
            <div class="row">
                <div class="col-sm-12 col-md-24">
                    <ul style="padding-left:4px; margin:0">
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_cat}:</cite><sup class="required">(∗)</sup>
                            </p>
                            <div class="message_body" style="height:260px; overflow: auto">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                        <!-- BEGIN: catid -->
                                        <tr>
                                            <td><input style="margin-left: {CATS.space}px;" type="checkbox" value="{CATS.catid}" name="catids[]" class="news_checkbox" {CATS.checked} {CATS.disabled}> {CATS.title} </td>
                                            <td><input id="catright_{CATS.catid}" style="{CATS.catiddisplay}" type="radio" name="catid" title="{LANG.content_checkcat}" value="{CATS.catid}" {CATS.catidchecked} /></td>
                                        </tr>
                                        <!-- END: catid -->
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <!-- BEGIN:block_cat -->
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_block}:</cite>
                            </p>
                            <div class="message_body" style="overflow: auto">
                                <!-- BEGIN: loop -->
                                <div class="row">
                                    <label><input type="checkbox" value="{BLOCKS.bid}" name="bids[]" {BLOCKS.checked}>{BLOCKS.title}</label>
                                </div>
                                <!-- END: loop -->
                            </div>
                        </li>
                        <!-- END:block_cat -->
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_keyword}:</cite>
                            </p>
                            <div class="message_body" style="overflow: auto">
                                <div class="clearfix uiTokenizer uiInlineTokenizer">
                                    <div id="keywords" class="tokenarea">
                                        <!-- BEGIN: keywords -->
                                        <span class="uiToken removable" title="{KEYWORDS}" ondblclick="$(this).remove();"> {KEYWORDS} <input type="hidden" autocomplete="off" name="keywords[]" value="{KEYWORDS}" /> <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a> </span>
                                        <!-- END: keywords -->
                                    </div>
                                    <div class="uiTypeahead">
                                        <div class="wrap">
                                            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                            <div class="innerWrap">
                                                <div class="input-group" style="width: 100%;">
                                                    <input id="keywords-search" type="text" placeholder="{LANG.input_keyword}" class="form-control textInput" />
                                                    <span class="input-group-btn"><button type="button" title="{LANG.keywords_auto_create}" data-toggle="keywords_auto_create" data-mdata="{MODULE_DATA}"><i class="fa fa-key"></i></button></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_tag}:</cite>
                            </p>
                            <div class="message_body" style="overflow: auto">
                                <div class="clearfix uiTokenizer uiInlineTokenizer">
                                    <div id="tags" class="tokenarea">
                                        <!-- BEGIN: tags -->
                                        <span class="uiToken removable" title="{TAGS}" ondblclick="$(this).remove();"> {TAGS} <input type="hidden" autocomplete="off" name="tags[]" value="{TAGS}" /> <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a> </span>
                                        <!-- END: tags -->
                                    </div>
                                    <div class="uiTypeahead">
                                        <div class="wrap">
                                            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                            <div class="innerWrap">
                                                <div class="input-group" style="width: 100%;">
                                                    <input id="tags-search" type="text" placeholder="{LANG.input_tag}" class="form-control textInput" />
                                                    <span class="input-group-btn"><button type="button" title="{LANG.tags_auto_create}" data-toggle="tags_auto_create" data-mdata="{MODULE_DATA}"><i class="fa fa-tags"></i></button></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-24">
                    <ul style="padding:4px; margin:0">
                        <li>
                            <p class="message_head">
                                <cite>{LANG.group_view}:</cite>
                            </p>
                            <div class="message_body">
                                <!-- BEGIN: group_view -->
                                <div class="row">
                                    <label><input name="group_view[]" type="checkbox" value="{GROUP_VIEW.value}" {GROUP_VIEW.checked} />{GROUP_VIEW.title}</label>
                                </div>
                                <!-- END: group_view -->
                                <div class="alert alert-info" style="padding:5px;">
                                    {LANG.group_view_note}
                                </div>
                            </div>
                        </li>
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_allowed_comm}:</cite>
                            </p>
                            <div class="message_body">
                                <!-- BEGIN: allowed_comm -->
                                <div class="row">
                                    <label><input name="allowed_comm[]" type="checkbox" value="{ALLOWED_COMM.value}" {ALLOWED_COMM.checked} />{ALLOWED_COMM.title}</label>
                                </div>
                                <!-- END: allowed_comm -->
                                <!-- BEGIN: content_note_comm -->
                                <div class="alert alert-info" style="padding:5px;">
                                    {LANG.content_note_comm}
                                </div>
                                <!-- END: content_note_comm -->
                            </div>
                        </li>
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_extra}:</cite>
                            </p>
                            <div class="message_body">
                                <div style="margin-bottom: 2px;">
                                    <input type="checkbox" value="1" name="inhome" {inhome_checked} />
                                    <label> {LANG.content_inhome} </label>
                                </div>
                                <!-- BEGIN: allowed_rating -->
                                <div style="margin-bottom: 2px;">
                                    <input type="checkbox" value="1" name="allowed_rating" {allowed_rating_checked} />
                                    <label> {LANG.content_allowed_rating} </label>
                                </div>
                                <!-- END: allowed_rating -->
                                <!-- BEGIN: not_allowed_rating -->
                                <input type="hidden" name="allowed_rating" value="{rowcontent.allowed_rating}" />
                                <!-- END: not_allowed_rating -->
                                <div style="margin-bottom: 2px;">
                                    <input type="checkbox" value="1" name="allowed_send" {allowed_send_checked} />
                                    <label> {LANG.content_allowed_send} </label>
                                </div>
                                <div style="margin-bottom: 2px;">
                                    <input type="checkbox" value="1" name="allowed_print" {allowed_print_checked} />
                                    <label> {LANG.content_allowed_print} </label>
                                </div>
                                <div style="margin-bottom: 2px;">
                                    <input type="checkbox" value="1" name="allowed_save" {allowed_save_checked} />
                                    <label> {LANG.content_allowed_save} </label>
                                </div>
                                <div style="margin-bottom: 2px;">
                                    <input type="checkbox" value="1" name="copyright" {checkcop} />
                                    <label> {LANG.content_copyright} </label>
                                </div>
                            </div>
                        </li>
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_internal_author}:</cite>
                            </p>
                            <div class="message_body" style="overflow: auto">
                                <div class="clearfix uiTokenizer uiInlineTokenizer">
                                    <div id="internal_authors" class="tokenarea">
                                        <!-- BEGIN: internal_authors -->
                                        <span class="uiToken removable" title="{INTERNAL_AUTHORS.pseudonym}" ondblclick="$(this).remove();"> {INTERNAL_AUTHORS.pseudonym} <input type="hidden" autocomplete="off" name="internal_authors[]" value="{INTERNAL_AUTHORS.id}" /> <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a> </span>
                                        <!-- END: internal_authors -->
                                    </div>
                                    <div class="uiTypeahead">
                                        <div class="wrap">
                                            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                            <div class="innerWrap">
                                                <input id="author-search" type="text" placeholder="{LANG.input_pseudonym}" class="form-control textInput" style="width: 100%;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="message_head">
                                <cite>{LANG.content_author}:</cite>
                            </p>
                            <div class="message_body">
                                <input class="form-control" type="text" maxlength="255" value="{rowcontent.author}" name="author" style="width:100%" />
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <h2><i class="fa fa-angle-double-down" id="adv-form-arrow"></i><a data-toggle="collapse" href="#adv-form" aria-expanded="false">{LANG.content_advfeature}</a></h2>
        <hr class="inline" />
        <div class="collapse" id="adv-form">
            <div class="row">
                <div class="col-sm-24 col-md-18">
                    <table class="table table-striped table-bordered">
                        <col class="w200" />
                        <col />
                        <tbody>
                            <tr>
                                <td><strong>{LANG.titlesite}</strong>:</td>
                                <td><input type="text" maxlength="250" value="{rowcontent.titlesite}" id="idtitlesite" name="titlesite" class="form-control" style="width:350px" /><span class="text-middle"> {GLANG.length_characters}: <span id="titlesitelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
                            </tr>
                            <tr>
                                <td><strong>{LANG.content_description}: </strong></td>
                                <td>
                                    <div class="help-block">
                                        {GLANG.length_characters}:<span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max}
                                    </div>
                                    <textarea id="description" name="description" class="form-control w500" rows="5">{rowcontent.description}</textarea>
                                </td>
                            </tr>
                            <!-- BEGIN: voices -->
                            <tr>
                                <td><strong>{LANG.content_voice}:</strong></td>
                                <td>
                                    <!-- BEGIN: voice -->
                                    <div class="m-bottom">
                                        <div><label for="voice_{VOICE.id}">{VOICE.title}</label></div>
                                        <div class="input-group witdh-100p">
                                            <input class="form-control" type="text" id="voice_{VOICE.id}" name="voice_{VOICE.id}" value="{VOICE.value}">
                                            <span class="input-group-btn">
                                                <button type="button" data-toggle="selectfile" data-target="voice_{VOICE.id}" data-path="{UPLOADS_DIR_USER}" data-type="file" class="btn btn-info" title="{GLANG.browse_file}"><em class="fa fa-folder-open-o"></em></button>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- END: voice -->
                                </td>
                            </tr>
                            <!-- END: voices -->
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-24 col-md-6">
                    <ul style="padding-left:4px; margin:0">
                        <li>
                            <p class="message_head">
                                <cite>{LANG.pick_layout}</cite>
                            </p>
                            <div class="message_body">
                                <select name="layout_func" class="form-control">
                                    <option value="">{LANG.default_layout}</option>
                                    <!-- BEGIN: layout_func -->
                                    <option value="{LAYOUT_FUNC.key}" {LAYOUT_FUNC.selected}>{LAYOUT_FUNC.key}</option>
                                    <!-- END: layout_func -->
                                </select>
                            </div>
                        </li>

                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_publ_date}</cite><span class="timestamp">{LANG.content_notetime}</span>
                            </p>
                            <div class="message_body">
                                <input class="form-control" name="publ_date" id="publ_date" value="{publ_date}" style="width: 90px;" maxlength="10" type="text" />
                                <select class="form-control" name="phour">
                                    {phour}
                                </select>
                                :
                                <select class="form-control" name="pmin">
                                    {pmin}
                                </select>
                            </div>
                        </li>
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_exp_date}:</cite><span class="timestamp">{LANG.content_notetime}</span>
                            </p>
                            <div class="message_body">
                                <input class="form-control" name="exp_date" id="exp_date" value="{exp_date}" style="width: 90px;" maxlength="10" type="text" />
                                <select class="form-control" name="ehour">
                                    {ehour}
                                </select>
                                :
                                <select class="form-control" name="emin">
                                    {emin}
                                </select>
                                <div style="margin-top: 5px;">
                                    <input type="checkbox" value="1" name="archive" {archive_checked} />
                                    <label> {LANG.content_archive} </label>
                                </div>
                            </div>
                        </li>
                        <!-- BEGIN: instant_articles_active -->
                        <li>
                            <p class="message_head">
                                <cite>{LANG.content_insart}:</cite>
                            </p>
                            <div class="message_body">
                                <p>
                                    <label><input type="checkbox" name="instant_active" value="1" {instant_active_checked} />&nbsp;{LANG.content_instant_active}</label>
                                </p>
                                <div class="m-bottom">
                                    {LANG.content_instant_template}:
                                    <input type="text" placeholder="{LANG.content_instant_templatenote}" name="instant_template" value="{rowcontent.instant_template}" class="form-control" style="width:100%" />
                                </div>
                                <p>
                                    <label><input type="checkbox" name="instant_creatauto" value="1" {instant_creatauto_checked} />&nbsp;{LANG.content_instant_creatauto}</label>
                                </p>
                            </div>
                        </li>
                        <!-- END: instant_articles_active -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <br />
        <input type="hidden" value="1" name="save" />
        <input type="hidden" value="{ISCOPY}" name="copy" />
        <input type="hidden" value="{rowcontent.id}" name="id" />
        <input type="hidden" value="{rowcontent.referer}" name="referer">
        <input type="hidden" value="{RESTORE_ID}" name="restore">
        <input type="hidden" value="{RESTORE_HASH}" name="restorehash">
        <!-- BEGIN:status_save -->
        <input class="btn btn-primary submit-post" name="statussave" type="submit" value="{LANG.save}" />
        <!-- END:status_save -->

        <!-- BEGIN:status_4 -->
        <input class="btn btn-warning submit-post" name="status4" type="submit" value="{LANG.save_temp}" />
        <!-- END:status_4 -->
        <!-- BEGIN:status_5 -->
        <input class="btn btn-primary submit-post" name="status5" type="submit" value="{LANG.status_5}" />
        <!-- END:status_5 -->

        <!-- BEGIN:status_8 -->
        <input class="btn btn-primary submit-post" name="status8" type="submit" value="{LANG.status_8}" />
        <!-- END:status_8 -->
        <!-- BEGIN:status_1 -->
        <input class="btn btn-primary submit-post" name="status1" type="submit" value="{LANG.publtime}" />
        <!-- END:status_1 -->
        <br />
    </div>
</form>
<div id="message"></div>
<script>
    var nv_num_files = '{NUMFILE}';
    var LANG = [];
    var CFG = [];
    CFG.id = {rowcontent.id};
    LANG.content_tags_empty = "{LANG.content_tags_empty}.<!-- BEGIN: auto_tags --> {LANG.content_tags_empty_auto}.<!-- END: auto_tags -->";
    LANG.alias_empty_notice = "{LANG.alias_empty_notice}";
    var content_checkcatmsg = "{LANG.content_checkcatmsg}";
    <!-- BEGIN: getalias -->
    $("#idtitle").change(function() {
        get_alias();
    });
    <!-- END: getalias -->
    <!-- BEGIN: holdon_edit -->
    CFG.is_edit_news = true;
    <!-- END: holdon_edit -->
    <!-- BEGIN: restore_auto -->
    $(window).on('load', function() {
        setTimeout(function() {
            var form = $('#form-news-content');
            if ($('[name="status1"]', form).length) {
                $('[name="status1"]', form).trigger('click');
            } else if ($('[name="statussave"]', form).length) {
                $('[name="statussave"]', form).trigger('click');
            } else {
                $('[type="submit"]:first', form).trigger('click');
            }
        }, 2000);
    });
    <!-- END: restore_auto -->
</script>
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script src="{ASSETS_STATIC_URL}/js/jquery/jquery.cookie.js"></script>
<script src="{NV_BASE_SITEURL}themes/admin_default/js/news_content.js"></script>
<!-- END:main -->

<!-- BEGIN: editing -->
<div class="text-center">
    <h2>{MESSAGE}</h2>
    <!-- BEGIN: takeover -->
    <a href="{TAKEOVER_LINK}" class="btn btn-danger">{LANG.dulicate_takeover}</a>
    <!-- END: takeover -->
</div>
<!-- END: editing -->