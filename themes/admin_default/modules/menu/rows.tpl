<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript">
	var cat = '{LANG.cat}';
	var caton = '{LANG.caton}';
	var nv_lang_data = '{NV_LANG_DATA}';
</script>
<!-- BEGIN: table -->
<form class="navbar-form" method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mid={DATA.mid}&parentid={DATA.parentid}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50">
				<col class="w50">
				<col span="2">
				<col class="w150">
				<col class="w100">
				<col class="w200">
			</colgroup>
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" type="checkbox"></th>
					<th>{LANG.number}</th>
					<th>{LANG.title}</th>
					<th>{LANG.link}</th>
					<th class="text-center">{GLANG.groups_view}</th>
					<th class="text-center">{LANG.display}</th>
					<th class="text-center">{LANG.action}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<select id="action" name="action" class="form-control">
							<option value="delete">{LANG.delete}</option>
						</select>
						<input onclick="return nv_main_action(this.form, '{LANG.msgnocheck}')" name="submit" type="submit" value="{LANG.action_form}" class="btn btn-primary w100" />
					</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop1 -->
				<tr>
					<td class="text-center"><input type="checkbox" name="idcheck[]" value="{ROW.id}" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"></td>
					<td class="text-center">
						<select id="change_weight_{ROW.id}" onchange="nv_chang_weight_item('{ROW.id}','{ROW.mid}','{ROW.parentid}','weight');" class="form-control w100">
							<!-- BEGIN: weight -->
							<option value="{stt}" {select}>{stt}</option>
							<!-- END: weight -->
						</select>
					</td>
					<td>
	                    <!-- BEGIN: icon -->
	                    <img src="{ROW.icon}" height="20px" />
	                    <!-- END: icon -->
	                    <a href="{ROW.url_title}"><strong>{ROW.title} </strong>
	                </a>
					<!-- BEGIN: sub -->
					(<span class="requie">{ROW.sub} {LANG.sub_menu}</span>)
					<!-- END: sub -->
					</td>
					<td>{ROW.link}</td>
					<td class="text-center">{ROW.groups_view}</td>
					<td class="text-center"> <input type="checkbox" id="change_active_{ROW.id}" onclick="nv_change_active({ROW.id})" {ROW.active} /> </td>
					<td class="text-center">
						<!-- BEGIN: reload -->
						<em class="fa fa-refresh fa-lg">&nbsp;</em> <a href="#" onclick="nv_menu_reload( {DATA.mid}, {ROW.id}, {ROW.parentid}, '{LANG.action_menu_reload_confirm}' );" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.action_menu_reload_note}">{LANG.action_menu_reload}</a>&nbsp;
						<!-- END: reload -->
						<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.edit_url}">{LANG.edit}</a>&nbsp;
						<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_menu_item_delete({ROW.id},{ROW.mid},{ROW.parentid},{ROW.nu});">{LANG.delete}</a>
					</td>
				</tr>
				<!-- END: loop1 -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: table -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form id="edit" action="{FORM_ACTION}" method="post">
	<input type="hidden" name="id" value="{DATA.id}">
	<input type="hidden" name="mid" value="{DATA.mid}">
	<input type="hidden" name="pa" value="{DATA.parentid}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{FORM_CAPTION}</caption>
			<colgroup>
				<col class="w150" />
				<col class="w300" />
				<col />
			</colgroup>
			<tfoot>
				<tr>
					<td colspan="3" class="text-center"><input name="submit1" type="submit" value="{LANG.save}" class="btn btn-primary w100" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td><strong>{LANG.name_block}</strong></td>
					<td>
						<select name="item_menu" id="item_menu_{key}" onchange="nv_link_menu('{key}', {DATA.parentid});" class="form-control w200">
							<!-- BEGIN: loop -->
							<option value="{key}" {select}>{val}</option>
							<!-- END: loop -->
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>{LANG.cats}</strong></td>
					<td>
					<select name="parentid" id="parentid" class="form-control w200">
						<!-- BEGIN: cat -->
						<option value="{cat.key}" {cat.selected}>{cat.title}</option>
						<!-- END: cat -->
					</select></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>{LANG.chomodule}</strong></td>
					<td>
						<div class="form-group">
						<select name="module_name" id="module_name_{module.key}" onchange="nv_link_module('{module.key}');" class="form-control w200">
							<option value="0">{LANG.cho_module}</option>
							<!-- BEGIN: module -->
							<option value="{module.key}"{module.selected}>{module.title}</option>
							<!-- END: module -->
						</select>
						</div>
						<span id="thu">
							<!-- BEGIN: link -->
							<select name="op" id="module_sub_menu" onchange="nv_link_settitle('{item.alias}','{item.module}');" class="form-control w200">
								<option value="">{LANG.item_menu}</option>
								<!-- BEGIN: item -->
								<option value="{item.alias}"{item.selected}>{item.title}</option>
								<!-- END: item -->
							</select>
							<!-- END: link -->
						</span>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>{LANG.title}</strong><sup class="required">(*)</sup></td>
					<td><input type="text" name="title" id="title" class="w300 form-control" value="{DATA.title}"/></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>{LANG.link}</strong><sup class="required">(*)</sup></td>
					<td><input type="text" name="link" class="w300 form-control" value="{DATA.link}" id="link"/></td>
					<td>&nbsp;</td>
				</tr>
                <tr>
					<td><strong>{LANG.icon}</strong></td>
					<td>
						<input class="form-control w200 pull-left" type="text" name="icon" id="icon" value="{DATA.icon}"/>
						&nbsp;<input type="button" value="Browse" class="btn btn-info selectimg" data-area="icon" />
					</td>
                    <td>&nbsp;</td>
				</tr>
                <tr>
					<td><strong>{LANG.image}</strong></td>
					<td>
						<input class="form-control w200 pull-left" type="text" name="image" id="image" value="{DATA.image}"/>
						&nbsp;<input type="button" value="Browse" class="btn btn-info selectimg" data-area="image" />
					</td>
                    <td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>{LANG.note}</strong></td>
					<td><input type="text" name="note" class="w300 form-control" value="{DATA.note}"/></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="vertical-align:top"><strong> {GLANG.groups_view}</strong></td>
					<td>
					<!-- BEGIN: groups_view -->
					<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}
					<br />
					<!-- END: groups_view -->
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>{LANG.target}</strong></td>
					<td>
					<select name="target" class="form-control w200">
						<!-- BEGIN: target -->
						<option value="{target.key}"{target.selected}>{target.title}</option>
						<!-- END: target -->
					</select></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><strong>{LANG.add_type_active}</strong></td>
					<td>
					<select name="active_type" class="w300 form-control">
						<!-- BEGIN: active_type -->
						<option value="{ACTIVE_TYPE.key}"{ACTIVE_TYPE.selected}>{ACTIVE_TYPE.title}</option>
						<!-- END: active_type -->
					</select></td>
					<td>{LANG.add_type_active_note}</td>
				</tr>
				<tr>
					<td><strong>{LANG.add_type_css}</strong></td>
					<td><input class="w300 form-control" type="text" name="css" value="{DATA.css}"/></td>
					<td>{LANG.add_type_css_info}</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
var CFG = [];
CFG.upload_current = '{UPLOAD_CURRENT}';
$(document).ready(function() {
	$("#parentid, #module_name_page, select[name='module_name']").select2();
});
</script>
<!-- END: main -->