<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table">
	<tfoot>
		<tr>
		<td colspan="6" class="text-center"><input type="button" name="action" value="{LANG.filter_action}" class="btn btn-default" />
		<input type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DISABLE} class="btn btn-default"/>
		<input type="button" name="clear" value="{LANG.filter_clear}" class="btn btn-default"/></td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<form id="filter-form" method="get" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
				<td><input style="width:120px" type="text" class="form-control" name="q" value="{DATA_SEARCH.q}" onfocus="if(this.value == '{LANG.filter_enterkey}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.filter_enterkey}';}"/></td>
				<td>
					<input class="form-control" value="{DATA_SEARCH.from}" type="text" id="from" name="from" readonly="readonly" style="width:80px" placeholder="{LANG.filter_from}" />
				</td>
				<td>
					<input class="form-control" value="{DATA_SEARCH.to}" type="text" id="to" name="to" readonly="readonly" style="width:80px" placeholder="{LANG.filter_to}" />
				</td>
				<td>
					<select class="form-control" name="lang">
						<!-- BEGIN: lang -->
						<option value="{lang.key}"{lang.selected}>{lang.title}</option>
						<!-- END: lang -->
					</select>
				</td>
				<td>
					<select class="form-control" name="user">
						<!-- BEGIN: user -->
						<option value="{user.key}"{user.selected}>{user.title}</option>
						<!-- END: user -->
					</select>
				</td>
				<td>
					<select class="form-control" name="module">
						<!-- BEGIN: module -->
						<option value="{module.key}"{module.selected}>{module.title}</option>
						<!-- END: module -->
					</select>
				</td>
			</form>
		</tr>
	</tbody>
</table>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col style="width: 35px"/>
			<col style="width: 60px"/>
			<col span="6" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" name="all" id="check_all"/></th>
				<th class="text-center"><a href="{DATA_ORDER.lang.data.url}" title="{DATA_ORDER.lang.data.title}" class="{DATA_ORDER.lang.data.class}">{LANG.log_lang}</a></th>
				<th><a href="{DATA_ORDER.module.data.url}" title="{DATA_ORDER.module.data.title}" class="{DATA_ORDER.module.data.class}">{LANG.log_module_name}</a></th>
				<th> {LANG.log_name_key} </th>
				<th> {LANG.log_note} </th>
				<th> {LANG.log_username} </th>
				<th><a href="{DATA_ORDER.time.data.url}" title="{DATA_ORDER.user.data.title}" class="{DATA_ORDER.time.data.class}">{LANG.log_time}</a></th>
				<!-- BEGIN: head_delete -->
				<th class="text-center"> {LANG.log_feature} </th>
				<!-- END: head_delete -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><input type="checkbox" name="all" class="list" value="{DATA.id}"/></td>
				<td class="text-center"> {DATA.lang} </td>
				<td> {DATA.module_name} </td>
				<td> {DATA.name_key} </td>
				<td> {DATA.note_action} </td>
				<td> {DATA.username} </td>
				<td> {DATA.time} </td>
				<!-- BEGIN: delete -->
				<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{DEL_URL}" class="delete">{GLANG.delete}</a></td>
				<!-- END: delete -->
			</tr>
			<!-- END: row -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8">
				<!-- BEGIN: foot_delete -->
				<input type="button" value="{GLANG.delete}" id="delall" class="btn btn-primary" /> <input type="button" value="{LANG.log_empty}" id="logempty" class="btn btn-primary" />
				<!-- END: foot_delete -->
				<!-- BEGIN: generate_page -->
				<div class="text-center">{GENERATE_PAGE}</div>
				<!-- END: generate_page -->
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript">
//<![CDATA[
var LANG = [];
LANG.filter_enterkey = '{LANG.filter_enterkey}';
LANG.filter_err_submit = '{LANG.filter_err_submit}';
LANG.log_del_no_items = '{LANG.log_del_no_items}';
LANG.log_del_confirm = '{LANG.log_del_confirm}';
var CFG = [];
CFG.checksess = '{checksess}';
CFG.url_del = '{URL_DEL}';
CFG.back_url = '{BACK_URL}';
//]]>
</script>
<!-- END: main -->