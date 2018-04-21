<!-- BEGIN: main -->
<!-- BEGIN: large_sys_note -->
<div class="alert alert-info">{LARGE_SYS_MESSAGE}</div>
<!-- END: large_sys_note -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{error}
</div>
<!-- END: error -->

<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<form class="form-inline m-bottom confirm-reload" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post" onsubmit="return nv_validForm(this,'{MODULE_DATA}', '{ERROR_BODYTEXT}','{ERROR_CAT}');">
	<div class="row">    
        <div class="alert alert-danger" id="show_error" style="display: none">
            
        </div>        
		<div class="col-sm-24 col-md-18">
			<table class="table table-striped table-bordered">
				<col class="w200" />
				<col />
				<tbody>
					<tr>
						<td><strong>{LANG.name}</strong>: <sup class="required">(∗)</sup></td>
						<td><input type="text" maxlength="250" value="{rowcontent.title}" id="idtitle" name="title" class="form-control require" data-mess="{LANG.error_title}" onkeypress="nv_validErrorHidden(this);" style="width:350px"/><span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
					</tr>
					<tr>
						<td><strong>{LANG.alias}: </strong></td>
						<td><input class="form-control" name="alias" id="idalias" type="text" value="{rowcontent.alias}" maxlength="250"  style="width:350px"/>&nbsp; <em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias();">&nbsp;</em></td>
					</tr>
					<tr>
                        <td><strong>{LANG.content_topic}: </strong></td>
						<td>
						<select class="form-control w300" name="topicid" id="topicid">
							<!-- BEGIN: rowstopic -->
							<option value="{topicid}" {sl}>{topic_title}</option>
							<!-- END: rowstopic -->
						</select><input class="form-control w200" type="text" maxlength="255" id="AjaxTopicText" value="{rowcontent.topictext}" name="topictext"/></td>
					</tr>
					<tr>
						<td><strong>{LANG.content_homeimg}</strong></td>
						<td><input class="form-control" style="width:380px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/><input id="select-img-post" type="button" value="{GLANG.browse_image}" name="selectimg" class="btn btn-info" /></td>
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
						</select></td>
					</tr>
					<tr>
						<td colspan="2"><strong>{LANG.content_hometext}</strong><i>{LANG.content_notehome}.</i> <br> {edit_hometext} </td>
					</tr>
					<tr>
						<td colspan="2"><strong>{LANG.content_bodytext}</strong><sup class="required {rowcontent.style_content_bodytext_required}" id="content_bodytext_required">(∗)</sup><i>{LANG.content_bodytext_note}</i>
                        <br>
						<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
							{edit_bodytext}
						</div>
                        </td>
					</tr>
                    <tr>
                        <td style="vertical-align:top"> {LANG.fileattach} <strong>[<a onclick="nv_add_files('{NV_BASE_ADMINURL}', '{UPLOAD_CURRENT}', '{GLANG.delete}', '{GLANG.browse_file}');" href="javascript:void(0);" title="{LANG.add}">{LANG.add}]</a></strong></td>
                        <td>
                            <div id="filearea">
                                <!-- BEGIN: files -->
                                <div id="fileitem_{FILEUPL.id}" style="margin-bottom: 5px">
                                    <input title="{LANG.fileupload}" class="form-control w400 pull-left" type="text" name="files[]" id="fileupload_{FILEUPL.id}" value="{FILEUPL.value}" style="margin-right: 5px" />
                                    <input onclick="nv_open_browse('{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=fileupload_{FILEUPL.id}&path={UPLOAD_CURRENT}&type=file', 'NVImg', '850', '500', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');return false;" type="button" value="{GLANG.browse_file}" class="selectfile btn btn-primary" />
                                    <input onclick="nv_delete_datacontent('fileitem_{FILEUPL.id}');return false;" type="button" value="{GLANG.delete}" class="selectfile btn btn-danger" />
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
                        <td><input type="checkbox" value="1" name="external_link" {external_link_checked}/></td>
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
											<td><input id="catright_{CATS.catid}" style="{CATS.catiddisplay}" type="radio" name="catid" title="{LANG.content_checkcat}" value="{CATS.catid}" {CATS.catidchecked}/></td>
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
                                                <input id="keywords-search" type="text" placeholder="{LANG.input_keyword}" class="form-control textInput" style="width: 100%;" />
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
												<input id="tags-search" type="text" placeholder="{LANG.input_tag}" class="form-control textInput" style="width: 100%;" />
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
								<cite>{LANG.content_allowed_comm}:</cite>
							</p>
							<div class="message_body">
								<!-- BEGIN: allowed_comm -->
								<div class="row">
									<label><input name="allowed_comm[]" type="checkbox" value="{ALLOWED_COMM.value}" {ALLOWED_COMM.checked} />{ALLOWED_COMM.title}</label>
								</div>
								<!-- END: allowed_comm -->
								<!-- BEGIN: content_note_comm -->
								<div class="alert alert-info">
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
									<input type="checkbox" value="1" name="inhome" {inhome_checked}/>
									<label> {LANG.content_inhome} </label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_rating" {allowed_rating_checked}/>
									<label> {LANG.content_allowed_rating} </label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_send" {allowed_send_checked}/>
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
									<input type="checkbox" value="1" name="copyright" {checkcop}/>
									<label> {LANG.content_copyright} </label>
								</div>
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_author}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" type="text" maxlength="255" value="{rowcontent.author}" name="author" style="width:100%" />
							</div>
						</li>
						<!-- BEGIN: googleplus -->
						<li>
							<p class="message_head">
								<cite>{LANG.googleplus}:</cite>
							</p>
							<div class="message_body">
								<select class="form-control" name="gid">
									<!-- BEGIN: gid -->
									<option value="{GOOGLEPLUS.gid}"{GOOGLEPLUS.selected}>{GOOGLEPLUS.title}</option>
									<!-- END: gid -->
								</select>
							</div>
						</li>
						<!-- END: googleplus -->
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<h2><i class="fa fa-angle-double-down" id="adv-form-arrow"></i><a data-toggle="collapse" href="#adv-form" aria-expanded="false">{LANG.content_advfeature}</a></h2>
		<hr class="inline"/>
		<div class="collapse" id="adv-form">
			<div class="row">
				<div class="col-sm-24 col-md-18">
					<table class="table table-striped table-bordered">
						<col class="w200" />
						<col />
						<tbody>
							<tr>
								<td><strong>{LANG.titlesite}</strong>:</td>
								<td><input type="text" maxlength="250" value="{rowcontent.titlesite}" id="idtitlesite" name="titlesite" class="form-control"  style="width:350px"/><span class="text-middle"> {GLANG.length_characters}: <span id="titlesitelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
							</tr>
							<tr>
								<td><strong>{LANG.content_description}: </strong></td>
								<td>
								<div class="help-block">
									{GLANG.length_characters}:<span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max}
								</div>								<textarea id="description" name="description" class="form-control w500" rows="5">{rowcontent.description}</textarea></td>
							</tr>
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
                                <option value="{LAYOUT_FUNC.key}"{LAYOUT_FUNC.selected}>{LAYOUT_FUNC.key}</option>
                                <!-- END: layout_func -->
                            </select>
                            </div>
                        </li>

						<li>
							<p class="message_head">
								<cite>{LANG.content_publ_date}</cite><span class="timestamp">{LANG.content_notetime}</span>
							</p>
							<div class="message_body">
								<input class="form-control" name="publ_date" id="publ_date" value="{publ_date}" style="width: 90px;" maxlength="10" type="text"/>
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
								<input class="form-control" name="exp_date" id="exp_date" value="{exp_date}" style="width: 90px;" maxlength="10" type="text"/>
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
									<label><input type="checkbox" name="instant_active" value="1" {instant_active_checked}/>&nbsp;{LANG.content_instant_active}</label>
								</p>
								<div class="m-bottom">
									{LANG.content_instant_template}:
									<input type="text" placeholder="{LANG.content_instant_templatenote}" name="instant_template" value="{rowcontent.instant_template}" class="form-control" style="width:100%"/>
								</div>
								<p>
									<label><input type="checkbox" name="instant_creatauto" value="1" {instant_creatauto_checked}/>&nbsp;{LANG.content_instant_creatauto}</label>
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
		<br/>
		<input type="hidden" value="1" name="save" />
		<input type="hidden" value="{ISCOPY}" name="copy" />
		<input type="hidden" value="{rowcontent.id}" name="id" />
		<input type="hidden" value="{rowcontent.referer}" name="referer">
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
<script type="text/javascript">
//<![CDATA[
var nv_num_files = '{NUMFILE}';
var LANG = [];
var CFG = [];
CFG.uploads_dir_user = "{UPLOADS_DIR_USER}";
CFG.upload_current = "{UPLOAD_CURRENT}";
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
//]]>
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.cookie.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/news_content.js"></script>
<!-- END:main -->

<!-- BEGIN: editing -->
<div class="text-center">
    <h2>{MESSAGE}</h2>
    <!-- BEGIN: takeover -->
    <a href="{TAKEOVER_LINK}" class="btn btn-danger">{LANG.dulicate_takeover}</a>
    <!-- END: takeover -->
</div>
<!-- END: editing -->