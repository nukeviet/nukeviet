<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post" class="confirm-reload">
	<div class="row">
		<div class="col-sm-12 col-md-9">
			<table class="table table-striped table-bordered">
				<col class="w200" />
				<col />
				<tbody>
					<tr>
						<td><strong>{LANG.name}</strong></td>
						<td><input type="text" maxlength="255" value="{rowcontent.title}" id="idtitle" name="title" class="form-control"  style="width:350px"/><span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
					</tr>
					<tr>
						<td><strong>{LANG.alias}: </strong></td>
						<td><input class="form-control" name="alias" id="idalias" type="text" value="{rowcontent.alias}" maxlength="255"  style="width:350px"/>&nbsp; <em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias();">&nbsp;</em></td>
					</tr>
				</tbody>
			</table>
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td class="top"><strong>{LANG.content_topic}</strong></td>
					</tr>
					<tr>
						<td>
						<select class="form-control w300" name="topicid">
							<!-- BEGIN: rowstopic -->
							<option value="{topicid}" {sl}>{topic_title}</option>
							<!-- END: rowstopic -->
						</select>
						<input class="form-control w200" type="text" maxlength="255" id="AjaxTopicText" value="{rowcontent.topictext}" name="topictext"/></td>
					</tr>
				</tbody>
			</table>
			<table class="table table-striped table-bordered table-hover">
				<col class="w200" />
				<col />
				<tbody>
					<tr>
						<td><strong>{LANG.content_homeimg}</strong></td>
						<td><input class="form-control" style="width:380px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/> <input type="button" value="Browse server" name="selectimg" class="btn btn-info" /></td>
					</tr>
					<tr>
						<td>{LANG.content_homeimgalt}</td>
						<td><input class="form-control" type="text" maxlength="255" value="{rowcontent.homeimgalt}" id="homeimgalt" name="homeimgalt" style="width:98%" /></td>
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
				</tbody>
			</table>
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td><strong>{LANG.content_hometext}</strong> {LANG.content_notehome}. {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
					</tr>
					<tr>
						<td><textarea id="description" name="hometext" rows="5" cols="75" style="font-size:14px; width: 100%; height:100px;" class="form-control">{rowcontent.hometext}</textarea></td>
					</tr>
					<tr>
						<td><strong>{LANG.content_bodytext}</strong>{LANG.content_bodytext_note}</td>
					</tr>
					<tr>
						<td>
						<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
							{edit_bodytext}
						</div></td>
					</tr>
					<tr>
						<td><strong>{LANG.content_sourceid}</strong></td>
					</tr>
					<tr>
						<td>
							<input class="form-control" type="text" maxlength="255" value="{rowcontent.sourcetext}" name="sourcetext" id="AjaxSourceText" style="width:100%"/>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-sm-12 col-md-3">
			<div class="row">
				<div class="col-sm-6 col-md-12">
					<ul style="padding:4px; margin:0">
						<li>
							<p class="message_head">
								<cite>{LANG.content_cat}:</cite>
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
								<div class="clearfix uiTokenizer uiInlineTokenizer">
		                            <div id="bids" class="tokenarea">
		                                <!-- BEGIN: default -->
		                                <span class="uiToken removable" title="{BLOCKS.title}">
		                                    {BLOCKS.title}
		                                    <input type="hidden" autocomplete="off" name="bids[]" value="{BLOCKS.bid}" />
		                                    <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a>
		                                </span>
		                                <!-- END: default -->
		                            </div>
		                            <div class="uiTypeahead">
		                                <div class="wrap">
		                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
		                                    <div class="innerWrap">
		                                        <input id="bids-search" type="text" placeholder="{LANG.input_title_blocks}" class="form-control" style="width: 100%;" />
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
							</div>
						</li>
						<!-- END:block_cat -->
						<li>
							<p class="message_head">
								<cite>{LANG.content_tag}:</cite>
							</p>
							<div class="message_body" style="overflow: auto">
								<div class="clearfix uiTokenizer uiInlineTokenizer">
		                            <div id="keywords" class="tokenarea">
		                                <!-- BEGIN: keywords -->
		                                <span class="uiToken removable" title="{KEYWORDS}">
		                                    {KEYWORDS}
		                                    <input type="hidden" autocomplete="off" name="keywords[]" value="{KEYWORDS}" />
		                                    <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a>
		                                </span>
		                                <!-- END: keywords -->
		                            </div>
		                            <div class="uiTypeahead">
		                                <div class="wrap">
		                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
		                                    <div class="innerWrap">
		                                        <input id="keywords-search" type="text" placeholder="{LANG.input_keyword_tags}" class="form-control" style="width: 100%;" />
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                	</div>
						</li>
<li>
							<p class="message_head">
								<cite>{LANG.content_publ_date}</cite><span class="timestamp">{LANG.content_notetime}</span>
							</p>
							<div class="message_body">
								<input class="form-control" name="publ_date" id="publ_date" value="{publ_date}" style="width: 80px;" maxlength="10" readonly="readonly" type="text"/>
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
								<input class="form-control" name="exp_date" id="exp_date" value="{exp_date}" style="width: 80px;" maxlength="10" readonly="readonly" type="text"/>
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
					</ul>
				</div>
				<div class="col-sm-6 col-md-12">
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
								<div class="alert alert-info">{LANG.content_note_comm}</div>
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
								<input type="checkbox" value="1" name="copyright"{checkcop}/>
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
	<div class="text-center">
		<br/>
		<input type="hidden" value="1" name="save" />
		<input type="hidden" value="{rowcontent.id}" name="id" />
		<!-- BEGIN:status -->
		<input class="btn btn-primary" name="statussave" type="submit" value="{LANG.save}" />
		<!-- END:status -->
		<!-- BEGIN:status0 -->
		<input class="btn btn-primary" name="status4" type="submit" value="{LANG.save_temp}" />
		<input class="btn btn-primary" name="status1" type="submit" value="{LANG.publtime}" />
		<!-- END:status0 -->
        <!-- BEGIN:status1 -->
		<input class="btn btn-primary" name="status4" type="submit" value="{LANG.save_temp}" />
		<input class="btn btn-primary" name="status6" type="submit" value="{LANG.save_send_admin}" />
            <!-- BEGIN:status0 -->
            <input class="btn btn-primary" name="status0" type="submit" value="{LANG.save_send_spadmin}" />
            <!-- END:status0 -->
		<!-- END:status1 -->
		<br />
	</div>
</form>
<script type="text/javascript">
//<![CDATA[
var content_checkcatmsg = "{LANG.content_checkcatmsg}";
$("input[name=selectimg]").click(function() {
	var area = "homeimg";
	var alt = "homeimgalt";
	var path = "{UPLOADS_DIR_USER}";
	var currentpath = "{UPLOAD_CURRENT}";
	var type = "image";
	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
//]]>
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/news/js/content.js"></script>
<!-- BEGIN: getalias -->
<script type="text/javascript">
//<![CDATA[
$("#idtitle").change(function() {
	get_alias();
});
//]]>
</script>
<!-- END: getalias -->
<!-- END:main -->