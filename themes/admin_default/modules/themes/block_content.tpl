<!-- BEGIN: head -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<style type="text/css">
	.exp_time {
		line-height: 20px
	}

	.exp_time input {
		float: left
	}

	.exp_time img {
		float: left;
		margin: 2px
	}
	.content{
		padding: 5px;
	}
</style>
<div class="content">
<script type="text/javascript">
	//<![CDATA[
	var htmlload = '<tr><td class="text-center" colspan="2"><img src="{NV_BASE_SITEURL}images/load_bar.gif"/></td></tr>';
	//]]>
</script>
<!-- END: head -->
<!-- BEGIN: main -->
<!-- BEGIN: block_group_notice -->
<div class="alert alert-danger"><span id="message">{LANG.block_group_notice}</span></div>
<!-- END: block_group_notice -->
<!-- BEGIN: error -->
<div id="edit">&nbsp;</div>
<div class="alert alert-danger"><span id="message">{ERROR}</span></div>
<!-- END: error -->
<form method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;selectthemes={SELECTTHEMES}&amp;blockredirect={BLOCKREDIRECT}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col style="width:160px;white-space:nowrap" />
			<col style="width:600px;white-space:nowrap" />
			<tbody>
				<tr>
					<td>{LANG.block_type}:</td>
					<td>
					<select name="module" class="form-control w200 pull-left" style="margin-right: 5px">
						<option value="">{LANG.block_select_type}</option>
						<option value="theme"{THEME_SELECTED}>{LANG.block_type_theme}</option>
						<!-- BEGIN: module -->
						<option value="{MODULE.key}"{MODULE.selected}>{MODULE.title}</option>
						<!-- END: module -->
					</select>
					<select name="file_name" class="form-control w200">
						<option value="">{LANG.block_select}</option>
					</select></td>
				</tr>
			</tbody>
			<tbody id="block_config">
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<td>{LANG.block_title}:</td>
					<td><input class="w300 form-control" name="title" type="text" value="{ROW.title}"/></td>
				</tr>
				<tr>
					<td>{LANG.block_link}:</td>
					<td><input class="w500 form-control" name="link" type="text" value="{ROW.link}"/></td>
				</tr>
				<tr>
					<td>{LANG.block_tpl}:</td>
					<td>
					<select id="template" name="template" class="form-control w200">
						<option value="">{LANG.block_default}</option>
						<!-- BEGIN: template -->
						<option value="{TEMPLATE.key}"{TEMPLATE.selected}>{TEMPLATE.title}</option>
						<!-- END: template -->
					</select></td>
				</tr>
				<tr>
					<td>{LANG.block_pos}:</td>
					<td>
					<select name="position" class="form-control w200">
						<!-- BEGIN: position -->
						<option value="{POSITION.key}"{POSITION.selected}>{POSITION.title}</option>
						<!-- END: position -->
					</select></td>
				</tr>
				<tr>
					<td>{LANG.block_exp_time}:</td>
					<td class="exp_time"><input name="exp_time" id="exp_time" value="{ROW.exp_time}" style="width: 90px" maxlength="10" type="text" class="form-control" /><span class="text-middle"> (dd/mm/yyyy) </span></td>
				</tr>
				<tr>
					<td>{LANG.block_active}:</td>
					<td><input type="checkbox" name="active" value="1"{ROW.block_active}/> {LANG.block_yes}</td>
				</tr>
				<tr>
					<td>{GLANG.groups_view}:</td>
					<td>
					<!-- BEGIN: groups_list -->
					<p><input name="groups_view[]" type="checkbox" value="{GROUPS_LIST.key}"{GROUPS_LIST.selected}/>&nbsp;{GROUPS_LIST.title}
					</p>
					<!-- END: groups_list -->
					</td>
				</tr>
				<!-- BEGIN: edit -->
				<tr>
					<td>{LANG.block_groupbl}:</td>
					<td><span style="color:red;font-weight:bold">{ROW.bid}</span> &nbsp;&nbsp;&nbsp; <label><input type="checkbox" value="1" name="leavegroup"/>{LANG.block_leavegroup} ({BLOCKS_NUM} {LANG.block_count})</label></td>
				</tr>
				<!-- END: edit -->
				<tr>
					<td>{LANG.add_block_module}:</td>
					<td>
					<!-- BEGIN: add_block_module -->
					<label id="labelmoduletype{I}"{SHOWSDISPLAY}> <input type="radio" name="all_func" class="moduletype{I}" value="{B_KEY}"{CK}/> {B_VALUE} </label>
					<!-- END: add_block_module -->
					</td>
				</tr>
				<tr {SHOWS_ALL_FUNC} id="shows_all_func">
					<td style="vertical-align:top"> {LANG.block_function}:
					<br />
					<br />
					<label><input type="button" name="checkallmod" value="{LANG.block_check}" style="margin-bottom:5px"/></label></td>
					<td>
						<div class="list-funcs">
							<table class="table table-striped table-bordered table-hover">
								<tbody>
									<!-- BEGIN: loopfuncs -->
									<tr class="funclist" id="idmodule_{M_TITLE}">
										<td style="font-weight:bold; white-space:nowrap"><input {M_CHECKED} type="checkbox" value="{M_TITLE}" class="checkmodule"/> {M_CUSTOM_TITLE} </td>
										<!-- BEGIN: fuc -->
										<td style="white-space:nowrap"><label><input type="checkbox"{SELECTED} name="func_id[]" value="{FUNCID}" /> {FUNCNAME}</label></td>
										<!-- END: fuc -->
									</tr>
									<!-- END: loopfuncs -->
								</tbody>
							</table>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="padding:10px;text-align:center">
		<input type="hidden" name="bid" value="{ROW.bid}" />
		<input type="button" onclick="window.close()" value="{LANG.back}" class="btn btn-primary" />
		<input type="submit" name="confirm" value="{LANG.block_confirm}" class="btn btn-primary" />
	</div>
</form>

<script type="text/javascript">
	var bid = parseInt('{ROW.bid}');
	var bid_module = '{ROW.module}';
	var selectthemes = '{SELECTTHEMES}';
	var lang_block_no_func = '{LANG.block_no_func}';
	var lang_block_error_nogroup = '{LANG.block_error_nogroup}';
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/block_content.js"></script>
</div>
<!-- END: main -->
<!-- BEGIN: blockredirect -->
<script type="text/javascript">
	window.opener.location.href = '{BLOCKREDIRECT}';
	window.opener.focus();
	window.close();
</script>
<!-- END: blockredirect -->