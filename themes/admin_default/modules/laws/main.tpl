<!-- BEGIN: list -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.title}</th>
				<th>{LANG.code}</th>
				<th>{LANG.publtime}</th>
				<th>{LANG.exptime}</th>
				<th class="text-center" style="width:150px">{LANG.status}</th>
				<th class="text-center" style="width:120px">{LANG.feature}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{DATA.title}</td>
				<td><strong>{DATA.code}</strong></td>
				<td>{DATA.publtime}</td>
				<td>{DATA.exptime}</td>
				<td class="text-center">
				<select class="form-control" id="status_{DATA.id}" name="status[]" onchange="nv_change_status({DATA.id});">
					<option value="0">{LANG.status0}</option>
					<option value="1"{DATA.selected}>{LANG.status1}</option>
				</select></td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em><a href="{DATA.url_edit}">{GLANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_delete_law({DATA.id});">{GLANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<div class="text-center">
	{NV_GENERATE_PAGE}
</div>
<!-- END: list -->

<!-- BEGIN: main -->
<div style="text-align: left; margin-bottom:10px;">
	<input name="submit" onclick="window.location='{ADD_LINK}';" type="button" value="{LANG.add}" class="btn btn-primary" />
</div>

<div id="lawlist"></div>

<script type="text/javascript">
	function nv_load_laws(url, area) {
		$('#lawlist').load(rawurldecode(url));
	}

	$(window).load(function() {
		$('#lawlist').load('{BASE_LOAD}');
	}); 
</script>
<!-- END: main -->

<!-- BEGIN: add -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div id="pageContent">
	<form class="form-inline" id="addRow" action="{DATA.action_url}" method="post">
		<h3 class="myh3">{DATA.ptitle}</h3>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<col style="width:200px" />
				<tbody>
					<tr>
						<td>{LANG.title} <span style="color:red">*</span></td>
						<td>
						<input title="{LANG.title}" class="form-control" style="width: 400px" type="text" name="title" value="{DATA.title}" maxlength="255" />
						</td>
					</tr>
					<tr>
						<td>{LANG.code}</td>
						<td>
						<input title="{LANG.code}" class="form-control" style="width: 400px" type="text" name="code" value="{DATA.code}" maxlength="255" />
						</td>
					</tr>
					<tr>
						<td>{LANG.catSel}</td>
						<td>
						<select class="form-control" title="{LANG.catSel}" name="cid" style="width: 200px">
							<!-- BEGIN: catopt -->
							<option value="{CATOPT.id}"{CATOPT.selected}>{CATOPT.name}</option>
							<!-- END: catopt -->
						</select></td>
					</tr>
					<tr>
						<td>{LANG.areaSel}</td>
						<td>
						<select class="form-control" title="{LANG.areaSel}" name="aid" style="width: 200px">
							<!-- BEGIN: areaopt -->
							<option value="{AREAOPT.id}"{AREAOPT.selected}>{AREAOPT.name}</option>
							<!-- END: areaopt -->
						</select></td>
					</tr>
					<tr>
						<td>{LANG.subjectSel}</td>
						<td>
						<select class="form-control" title="{LANG.subjectSel}" name="sid" style="width: 200px">
							<!-- BEGIN: subopt -->
							<option value="{SUBOPT.id}"{SUBOPT.selected}>{SUBOPT.title}</option>
							<!-- END: subopt -->
						</select></td>
					</tr>
					<tr>
						<td>{LANG.signer}</td>
						<td>
						<select class="form-control" title="{LANG.signer}" name="sgid" style="width: 200px">
							<!-- BEGIN: singers -->
							<option value="{SINGER.id}"{SINGER.selected}>{SINGER.title}</option>
							<!-- END: singers -->
						</select></td>
					</tr>
					<tr>
						<td>{LANG.who_view}</td>
						<td><!-- BEGIN: group_view -->
						<div class="row">
							<label>
								<input name="groups_view[]" type="checkbox" value="{GROUPS_VIEWS.id}" {GROUPS_VIEWS.checked} />
								{GROUPS_VIEWS.title}</label>
						</div><!-- END: group_view --></td>
					</tr>
					<tr>
						<td>{LANG.who_download}</td>
						<td><!-- BEGIN: groups_download -->
						<div class="row">
							<label>
								<input name="groups_download[]" type="checkbox" value="{GROUPS_DOWNLOAD.id}" {GROUPS_DOWNLOAD.checked} />
								{GROUPS_DOWNLOAD.title}</label>
						</div><!-- END: groups_download --></td>
					</tr>
					<tr>
						<td style="vertical-align:top"> {LANG.fileupload} <strong>[<a onclick="nv_add_files('{NV_BASE_ADMINURL}','{UPLOADS_DIR_USER}','{GLANG.delete}','Browse server');" href="javascript:void(0);" title="{LANG.add}">{LANG.add}]</a></strong></td>
						<td>
						<div id="filearea">
							<!-- BEGIN: files -->
							<div id="fileitem_{FILEUPL.id}">
								<input title="{LANG.fileupload}" class="form-control" type="text" name="files[]" id="fileupload_{FILEUPL.id}" value="{FILEUPL.value}" style="width: 400px" />
								<input onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=fileupload_{FILEUPL.id}&path={UPLOADS_DIR_USER}&type=file', 'NVImg', '850', '500', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' );return false;" type="button" value="Browse server" class="selectfile btn btn-primary" />
								<input onclick="nv_delete_datacontent('fileitem_{FILEUPL.id}');return false;" type="button" value="{GLANG.delete}" class="selectfile btn btn-danger" />
							</div>
							<!-- END: files -->
						</div></td>
					</tr>
					<tr>
						<td> {LANG.publtime} </td>
						<td><label>
							<input class="form-control" name="publtime" id="publtime" value="{DATA.publtime}" style="width: 110px;" maxlength="10" type="text" />
							&nbsp;({LANG.prm})</label></td>
					</tr>
					<tr>
						<td> {LANG.startvalid} </td>
						<td><label>
							<input class="form-control" name="startvalid" id="startvalid" value="{DATA.startvalid}" style="width: 110px;" maxlength="10" type="text" />
							&nbsp;({LANG.prm})</label></td>
					</tr>
					<tr>
						<td> {LANG.exptime} </td>
						<td>
						<select class="form-control" id="chooseexptime" name="chooseexptime" style="width: 200px">
							<option value="0"{DATA.select0}>{LANG.hl0}</option>
							<option value="1"{DATA.select1}>{LANG.hl1}</option>
						</select>
						<div id="exptimearea" style="display:{DATA.display}">
							<input class="form-control" name="exptime" id="exptime" value="{DATA.exptime}" style="width: 110px;" maxlength="10" type="text" />
							<img src="{NV_BASE_SITEURL}images/calendar.jpg" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'exptime', 'dd.mm.yyyy', true);" alt="" height="17" /> ({LANG.prm})
						</div>
						<script type="text/javascript">
							$(document).ready(function() {
								$('#chooseexptime').change(function() {
									if ($(this).val() == 0) {
										$('#exptime').val('');
										$('#exptimearea').hide();
									} else {
										$('#exptimearea').show();
									}
								});
							});
						</script></td>
					</tr>
					<tr>
						<td> {LANG.replacement} ({LANG.ID}) </td>
						<td>
						<input class="form-control" title="{LANG.replacement}" type="text" name="replacement" id="replacement" style="width: 200px;" maxlength="255" value="{DATA.replacement}" />
						</td>
					</tr>
					<tr>
						<td> {LANG.relatement} ({LANG.ID}) </td>
						<td>
						<input class="form-control" title="{LANG.relatement}" type="text" name="relatement" id="relatement" style="width: 200px;" maxlength="255" value="{DATA.relatement}" />
						</td>
					</tr>
					<tr>
						<td>{LANG.keywords}</td>
						<td><label>
							<input title="{LANG.keywords}" class="form-control" style="width: 400px" type="text" name="keywords" value="{DATA.keywords}" maxlength="255" />
							({LANG.keywordsNote})</label></td>
					</tr>
					<tr>
						<td style="vertical-align:top">{LANG.note}</td>
						<td><textarea style="width:400px;" class="form-control" name="note" id="note">{DATA.note}</textarea></td>
					</tr>
					<tr>
						<td style="vertical-align:top">{LANG.introtext}</td>
						<td><textarea style="width:400px" class="form-control" name="introtext" id="introtext">{DATA.introtext}</textarea></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			{LANG.bodytext}
		</div>
		<div>
			{CONTENT}
		</div>
		<br />
		<input type="hidden" name="save" value="1" />
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</form>
</div>
<script type="text/javascript">
	//<![CDATA[
	$("#publtime,#startvalid").datepicker({
		showOn : "both",
		dateFormat : "dd.mm.yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});

	var nv_num_files = {NUMFILE};
	
	$(document).ready(function() {
		$("#replacementSearch").click(function() {
			nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getlid&area=replacement", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
			return false;
		});
		$("#amendmentSearch").click(function() {
			nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getlid&area=amendment", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
			return false;
		});
		$("#supplementSearch").click(function() {
			nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getlid&area=supplement", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
			return false;
		});
		$('select[name=who_view]').change(function() {
			if ($(this).val() == 3) {
				$('#group_view').removeClass('groupcss0');
				$('#group_view').addClass('groupcss1');
			} else {
				$('#group_view').removeClass('groupcss1');
				$('#group_view').addClass('groupcss0');
			}
		});
		$('select[name=who_download]').change(function() {
			if ($(this).val() == 3) {
				$('#groups_download').removeClass('groupcss0');
				$('#groups_download').addClass('groupcss1');
			} else {
				$('#groups_download').removeClass('groupcss1');
				$('#groups_download').addClass('groupcss0');
			}
		});
	});
	$("form#addRow").submit(function() {
		var a = $("[name=title]").val();
		a = trim(a);
		$("[name=title]").val(a);
		if (a.length < 2) {
			alert("{LANG.errorIsEmpty}: {LANG.title}");
			$("[name=title]").select();
			return !1
		}

		if (trim($("[name=code]").val()) == "") {
			alert("{LANG.errorAreaYesCode}");
			$("[name=code]").select();
			return !1
		}

		a = $("[name=introtext]").val();
		a = trim(a);
		$("[name=introtext]").val(a);
		if (a.length < 2) {
			alert("{LANG.errorIsEmpty}: {LANG.introtext}");
			$("[name=introtext]").select();
			return !1
		}

		a = $(this).serialize();
		var b = $(this).attr("action");
		$("[type=submit]").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : b,
			data : a,
			success : function(c) {
				if (c == "OK") {
					alert("{LANG.addTopicOK}");
					window.location = "{MODULE_URL}";
				} else {
					alert(c);
				}
				$("[type=submit]").removeAttr("disabled")
			}
		});
		return !1
	});
	//]]>
</script>
<!-- END: add -->