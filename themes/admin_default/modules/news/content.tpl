<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="newserror">
	{error}
</div>
<!-- END: error -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post" class="confirm-reload">
	<div class="gray">
		<table style="margin-bottom:0; width: 100%">
			<tr>
				<td class="top">
				<table class="tab1">
					<col class="w200" />
					<col />
					<tbody>
						<tr>
							<td><strong>{LANG.name}</strong></td>
							<td><input type="text" maxlength="255" value="{rowcontent.title}" id="idtitle" name="title" class="w350" /> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </td>
						</tr>
						<tr>
							<td><strong>{LANG.alias}: </strong></td>
							<td><input class="w350" name="alias" id="idalias" type="text" value="{rowcontent.alias}" maxlength="255"/>&nbsp; <em class="icon-refresh icon-large icon-pointer" onclick="get_alias();">&nbsp;</em></td>
						</tr>
						<tr>
							<td class="top" style="line-height:18px"><strong>{LANG.content_cat}</strong>
							<br />
							</td>
							<td class="top">
							<div style="padding:4px; height:130px;background:#FFFFFF; overflow:auto; border: 1px solid #CCCCCC">
								<table class="tab1">
									<col span="2" style="width:50%">
									<tbody>
										<!-- BEGIN: catid -->
										<tr>
											<td><input style="margin-left: {CATS.space}px;" type="checkbox" value="{CATS.catid}" name="catids[]" class="news_checkbox" {CATS.checked} {CATS.disabled}> {CATS.title} </td>
											<td id="catright_{CATS.catid}" style="{CATS.catiddisplay}"><input type="radio" name="catid" value="{CATS.catid}" {CATS.catidchecked}/> {LANG.content_checkcat} </td>
										</tr>
										<!-- END: catid -->
									</tbody>
								</table>
							</div></td>
						</tr>
					</tbody>
				</table>
				<table class="tab1">
					<tbody>
						<tr>
							<td class="top"><strong>{LANG.content_topic}</strong></td>
						</tr>
						<tr>
							<td>
							<select class="w300" name="topicid">
								<!-- BEGIN: rowstopic -->
								<option value="{topicid}" {sl}>{topic_title}</option>
								<!-- END: rowstopic -->
							</select> 
							<input class="w200" type="text" maxlength="255" id="AjaxTopicText" value="{rowcontent.topictext}" name="topictext"/></td>
						</tr>
					</tbody>
				</table>
				<table class="tab1">
					<col class="w200" />
					<col />
					<tbody>
						<tr>
							<td><strong>{LANG.content_homeimg}</strong></td>
							<td><input style="width:380px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/> <input type="button" value="Browse server" name="selectimg"/></td>
						</tr>
						<tr>
							<td>{LANG.content_homeimgalt}</td>
							<td><input type="text" maxlength="255" value="{rowcontent.homeimgalt}" id="homeimgalt" name="homeimgalt" style="width:98%" /></td>
						</tr>
						<tr>
							<td>{LANG.imgposition}</td>
							<td>
							<select name="imgposition">
								<!-- BEGIN: looppos -->
								<option value="{id_imgposition}" {posl}>{title_imgposition}</option>
								<!-- END: looppos -->
							</select></td>
						</tr>
					</tbody>
				</table>
				<table class="tab1">
					<tbody>
						<tr>
							<td><strong>{LANG.content_hometext}</strong> {LANG.content_notehome}. {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
						</tr>
						<tr>
							<td><textarea id="description" name="hometext" rows="5" cols="75" style="font-size:12px; width: 98%; height:100px;">{rowcontent.hometext}</textarea></td>
						</tr>
					</tbody>
				</table></td>
				<td style="vertical-align: top; width: 250px">
				<ul style="padding:4px; margin:0">
					<!-- BEGIN:block_cat -->
					<li>
						<p class="message_head">
							<cite>{LANG.content_block}:</cite>
						</p>
						<div style="width: 260px; overflow: auto; text-align:left; margin:auto">
							<table>
								{row_block}
							</table>
						</div>
					</li>
					<!-- END:block_cat -->
					<li>
						<p class="message_head">
							<cite>{LANG.content_tag}:</cite>
						</p>
						<div class="message_body">
							<p>
								{LANG.content_tag_note} <a onclick="create_keywords();" href="javascript:void(0);">{LANG.content_clickhere}</a>
							</p><textarea rows="3" cols="20" id="keywords" name="keywords" style="width: 240px;">{rowcontent.keywords}</textarea>
						</div>
					</li>
					<li>
						<p class="message_head">
							<cite>{LANG.content_publ_date}</cite><span class="timestamp">{LANG.content_notetime}</span>
						</p>
						<div class="message_body center">
							<input name="publ_date" id="publ_date" value="{publ_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
							<select name="phour">
								{phour}
							</select>
							:
							<select name="pmin">
								{pmin}
							</select>
						</div>
					</li>
					<li>
						<p class="message_head">
							<cite>{LANG.content_exp_date}:</cite><span class="timestamp">{LANG.content_notetime}</span>
						</p>
						<div class="message_body center">
							<input name="exp_date" id="exp_date" value="{exp_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
							<select name="ehour">
								{ehour}
							</select>
							:
							<select name="emin">
								{emin}
							</select>
							<div style="margin-top: 5px;">
								<input type="checkbox" value="1" name="archive" {archive_checked} />
								<label> {LANG.content_archive} </label>
							</div>
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
								<label> {LANG.content_allowed_comm} </label>
								<select name="allowed_comm">
									{allowed_comm}
								</select>
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
						</div>
					</li>
				</ul></td>
			</tr>
		</table>
	</div>
	<div class="gray">
		<table class="tab1">
			<tbody>
				<tr>
					<td><strong>{LANG.content_bodytext}</strong>{LANG.content_bodytext_note}</td>
				</tr>
				<tr>
					<td>
					<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
						{edit_bodytext}
					</div></td>
				</tr>
			</tbody>
		</table>
		<table class="tab1">
			<col class="w200" />
			<col />
			<tbody>
				<tr>
					<td><strong>{LANG.content_author}</strong></td>
					<td><input type="text" maxlength="255" value="{rowcontent.author}" name="author" style="width:225px; margin-right: 50px;" />
					<!-- BEGIN: googleplus -->
					{LANG.googleplus}
					<select name="gid">
						<!-- BEGIN: gid -->
						<option value="{GOOGLEPLUS.gid}"{GOOGLEPLUS.selected}>{GOOGLEPLUS.title}</option>
						<!-- END: gid -->
					</select>
					<!-- END: googleplus -->
					</td>
				</tr>
				<tr>
					<td><strong>{LANG.content_sourceid}</strong></td>
					<td><input type="text" maxlength="255" value="{rowcontent.sourcetext}" name="sourcetext" id="AjaxSourceText" style="width: 98%;" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.content_copyright}</strong></td>
					<td><input type="checkbox" value="1" name="copyright"{checkcop}/></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="gray center">
		<br />
		<input type="hidden" value="1" name="save" />
		<input type="hidden" value="{rowcontent.id}" name="id" />
		<!-- BEGIN:status -->
		<input name="statussave" type="submit" value="{LANG.save}" />
		<!-- END:status -->
		<!-- BEGIN:status0 -->
		<input name="status0" type="submit" value="{LANG.save_temp}" />
		<input name="status1" type="submit" value="{LANG.publtime}" />
		<!-- END:status0 -->
		<br />
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
	nv_open_browse_file(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
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