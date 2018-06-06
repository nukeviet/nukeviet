<!-- BEGIN: main -->
<!-- BEGIN: module -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.googleplus_module}</caption>
		<thead>
			<tr class="text-center">
				<th>{LANG.weight}</th>
				<th>{LANG.module}</th>
				<th>{LANG.custom_title}</th>
				<th>{LANG.googleplus_title}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.number}</td>
				<td>{ROW.title}</td>
				<td>{ROW.custom_title}</td>
				<td>
				<select id="id_mod_{ROW.title}" onchange="nv_mod_googleplus('{ROW.title}');" class="form-control w200">
					<option value="">&nbsp;</option>
					<!-- BEGIN: gid -->
					<option value="{GOOGLEPLUS.gid}"{GOOGLEPLUS.selected}>{GOOGLEPLUS.title}</option>
					<!-- END: gid -->
				</select></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: module -->
<div class="alert alert-info">{LANG.googleplusNote1}</div>
<div class="table-responsive navbar-form">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.googleplus_list}</caption>
		<thead>
			<tr class="text-center">
				<th>{LANG.weight}</th>
				<th>{LANG.googleplus_idprofile}</th>
				<th>{LANG.googleplus_title}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td class="text-right"><strong>{LANG.googleplus_add}: </strong></td>
				<td class="text-center"><input class="form-control" name="new_profile" id="new_profile" type="text" maxlength="255" /></td>
				<td colspan="2">
                    <input class="w250 form-control pull-left" name="new_title" id="new_title" type="text" maxlength="255" style="margin-right: 5px;" />
                    <input name="Button1" class="btn btn-info" type="button" value="{LANG.submit}" onclick="nv_add_googleplus();return;" />
                </td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: googleplus -->
			<tr>
				<td class="text-center">
				<select class="form-control" id="id_weight_{ROW.gid}" onchange="nv_chang_googleplus({ROW.gid});">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select></td>
				<td class="text-center">{ROW.idprofile}</td>
				<td><input name="hidden_{ROW.gid}" id="hidden_{ROW.gid}" type="hidden" value="{ROW.title}" /><input type="text" name="title_{ROW.gid}" id="title_{ROW.gid}" value="{ROW.title}" class="w250 form-control" /> <input type="button" onclick="nv_save_title({ROW.gid});" value="{GLANG.save}" class="btn btn-primary" /></td>
				<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_googleplus({ROW.gid})">{GLANG.delete}</a></td>
			</tr>
			<!-- END: googleplus -->
		</tbody>
	</table>
</div>
<div class="well">{LANG.googleplusNote2}</div>
<!-- END: main -->
<!-- BEGIN: load -->
<div id="module_show_list">
	<div class=text-center>
		<img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading..."/>
	</div>
</div>
<script type="text/javascript">nv_show_list_googleplus();</script>
<!-- END: load -->