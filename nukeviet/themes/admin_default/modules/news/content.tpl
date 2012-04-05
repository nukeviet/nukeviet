<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="newserror">
	{error}
</div>
<!-- END: error -->
<form action="" enctype="multipart/form-data" method="post">
	<input type="hidden" value="1" name="save" />
	<input type="hidden" value="{rowcontent.id}" name="id" />
	<div class="gray">
		<table width="100%" style="margin-bottom:0">
			<tr>
				<td valign="top">
				<table summary="" class="tab1">
					<tbody>
						<tr>
							<td width="150"><strong>{LANG.name}</strong></td>
							<td>
							<input type="text" maxlength="255" value="{rowcontent.title}" id="idtitle" name="title" style="width:98%" />
							</td>
						</tr>
					</tbody>
					<tbody class="second">
						<tr>
							<td><strong>{LANG.alias}: </strong></td>
							<td>
							<input style="width:80%" name="alias" id="idalias" type="text" value="{rowcontent.alias}" maxlength="255"/>
							<input type="button" value="GET" onclick="get_alias();" style="font-size:11px"  />
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td style="line-height:18px" valign="top"><strong>{LANG.content_cat}</strong>
							<br />
							</td>
							<td valign="top">
							<div style="padding:4px; height:130px;background:#FFFFFF; overflow:auto; border: 1px solid #CCCCCC">
								<table><tbody style="background:#fff;">
									<!-- BEGIN: catid -->
									<tr style="border: 1px solid #CCCCCC">
										<td>
											<input style="margin-left: {CATS.space}px;" type="checkbox" value="{CATS.catid}" name="catids[]" class="news_checkbox" {CATS.checked} {CATS.disabled}>
											{CATS.title}
										</td>
										<td id="catright_{CATS.catid}" style="{CATS.catiddisplay}">
											<input type="radio" name="catid" value="{CATS.catid}" {CATS.catidchecked}/>
											{LANG.content_checkcat}
										</td>
									</tr>
									<!-- END: catid -->
								</tbody></table>
							</div></td>
						</tr>
					</tbody>
				</table>
				<table summary="" class="tab1">
					<tbody>
						<tr>
							<td valign="top"><strong>{LANG.content_topic}</strong></td>
						</tr>
					</tbody>
					<tbody class="second">
						<tr>
							<td>
							<select name="topicid" style="width: 300px;">
								<!-- BEGIN: rowstopic -->
								<option value="{topicid}" {sl}>{topic_title}</option>
								<!-- END: rowstopic -->
							</select>
							<input type="text" maxlength="255" id="AjaxTopicText" value="{rowcontent.topictext}" name="topictext" style="width: 200px;"/>
							</td>
						</tr>
					</tbody>
				</table>
				<table summary="" class="tab1">
					<tbody class="second">
						<tr>
							<td><strong>{LANG.content_homeimg}</strong></td>
							<td>
							<input style="width:380px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/>
							<input type="button" value="Browse server" name="selectimg"/>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td width="180">{LANG.content_homeimgalt}</td>
							<td>
							<input type="text" maxlength="255" value="{rowcontent.homeimgalt}" name="homeimgalt" style="width:98%" />
							</td>
						</tr>
					</tbody>
					<tbody class="second">
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
				<table summary="" class="tab1">
					<tbody>
						<tr>
							<td><strong>{LANG.content_hometext}</strong> {LANG.content_notehome}</td>
						</tr>
					</tbody>
					<tbody class="second">
						<tr>
							<td><textarea name="hometext" rows="5" cols="75" style="font-size:12px; width: 98%; height:100px;">{rowcontent.hometext}</textarea></td>
						</tr>
					</tbody>
				</table></td>
				<td valign="top" style="width: 250px">
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
							<cite>{LANG.content_keywords}:</cite>
						</p>
						<div class="message_body">
							<p>
								{LANG.content_keywords_note} <a onclick="create_keywords();" href="javascript:void(0);">{LANG.content_clickhere}</a>
							</p>
							<textarea rows="3" cols="20" id="keywords" name="keywords" style="width: 240px;">{rowcontent.keywords}</textarea>
						</div>
					</li>
					<li>
						<p class="message_head">
							<cite>{LANG.content_publ_date}</cite><span class="timestamp">{LANG.content_notetime}</span>
						</p>
						<div class="message_body">
							<center>
								<input name="publ_date" id="publ_date" value="{publ_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
								<select name="phour">
									{phour}
								</select>
								:
								<select name="pmin">
									{pmin}
								</select>
							</center>
						</div>
					</li>
					<li>
						<p class="message_head">
							<cite>{LANG.content_exp_date}:</cite><span class="timestamp">{LANG.content_notetime}</span>
						</p>
						<div class="message_body">
							<center>
								<input name="exp_date" id="exp_date" value="{exp_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text"/>
								<select name="ehour">
									{ehour}
								</select>
								:
								<select name="emin">
									{emin}
								</select>
							</center>
							<div style="margin-top: 5px;">
								<input type="checkbox" value="1" name="archive" {archive_checked} />
								<label>{LANG.content_archive}</label>
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
								<label>{LANG.content_inhome}</label>
							</div>
							<div style="margin-bottom: 2px;">
								<label>{LANG.content_allowed_comm}</label>
								<select name="allowed_comm">
									{allowed_comm}
								</select>
							</div>
							<div style="margin-bottom: 2px;">
								<input type="checkbox" value="1" name="allowed_rating" {allowed_rating_checked}/>
								<label>{LANG.content_allowed_rating}</label>
							</div>
							<div style="margin-bottom: 2px;">
								<input type="checkbox" value="1" name="allowed_send" {allowed_send_checked}/>
								<label>{LANG.content_allowed_send}</label>
							</div>
							<div style="margin-bottom: 2px;">
								<input type="checkbox" value="1" name="allowed_print" {allowed_print_checked} />
								<label>{LANG.content_allowed_print}</label>
							</div>
							<div style="margin-bottom: 2px;">
								<input type="checkbox" value="1" name="allowed_save" {allowed_save_checked} />
								<label>{LANG.content_allowed_save}</label>
							</div>
						</div>
					</li>
				</ul></td>
			</tr>
		</table>
	</div>
	<div class="gray">
		<table summary="" class="tab1">
			<tbody>
				<tr>
					<td><strong>{LANG.content_bodytext}</strong>{LANG.content_bodytext_note}</td>
				</tr>
			</tbody>
			<tbody class="second">
				<tr>
					<td>
					<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
						{edit_bodytext}
					</div></td>
				</tr>
			</tbody>
		</table>
		<table summary="" class="tab1">
			<tr>
				<td width="150"><strong>{LANG.content_author}</strong></td>
				<td>
				<input type="text" maxlength="255" value="{rowcontent.author}" name="author" style="width:225px;" />
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.content_sourceid}</strong></td>
				<td>
				<input type="text" maxlength="255" value="{rowcontent.sourcetext}" name="sourcetext" id="AjaxSourceText" style="width: 98%;" />
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.content_copyright}</strong></td>
				<td>
				<input type="checkbox" value="1" name="copyright"{checkcop}/>
				</td>
			</tr>
		</table>
	</div>
	<div class="gray">
		<center>
			<!-- BEGIN:status -->
			<input name="statussave" type="submit" value="{LANG.save}" />
			<!-- END:status -->
			<!-- BEGIN:status0 -->
			<input name="status0" type="submit" value="{LANG.save_temp}" />
			<input name="status1" type="submit" value="{LANG.publtime}" />
			<!-- END:status0 -->
		</center>
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	$("input[name='catids[]']").click(function() {
		var catid = $("input:radio[name=catid]:checked").val();
		var $radios_catid = $("input:radio[name=catid]");
		var catids = [];
		$("input[name='catids[]']").each(function() {
			if($(this).attr('checked')) {
				catids.push($(this).val());
			} else {
				$("#catright_" + $(this).val()).hide();
				if($(this).val() == catid) {
					$radios_catid.filter("[value=" + catid + "]").attr("checked", false);
				}
			}
		});
		if(catids.length > 1) {
			for( i = 0; i < catids.length; i++) {
				$("#catright_" + catids[i]).show();
			};
			catid = parseInt($("input:radio[name=catid]:checked").val() + "");
			if(!catid) {
				alert("{LANG.content_checkcatmsg}");
			}
		}
	});
	$("input[name=selectimg]").click(function() {
		var area = "homeimg";
		var path = "{UPLOADS_DIR_USER}";
		var currentpath = "{UPLOAD_CURRENT}";
		var type = "image";
		nv_open_browse_file(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", "850", "420", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	$(document).ready(function() {
		$("#publ_date,#exp_date").datepicker({
			showOn : "button",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});

		$("#AjaxTopicText").autocomplete(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=topicajax", {
			delay : 10,
			minChars : 2,
			matchSubset : 1,
			matchContains : 1,
			cacheLength : 10,
			onItemSelect : selectItem,
			onFindValue : findValue,
			autoFill : true
		});
		
		$("#AjaxSourceText").autocomplete(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=sourceajax", {
			delay : 10,
			minChars : 2,
			matchSubset : 1,
			matchContains : 1,
			cacheLength : 10,
			onItemSelect : selectItem,
			onFindValue : findValue,
			autoFill : true
		});
	});
	//]]>
</script>
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