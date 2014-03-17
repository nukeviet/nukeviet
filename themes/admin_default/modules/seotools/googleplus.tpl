<!-- BEGIN: main -->
<!-- BEGIN: module -->
<table class="tab1" style="width: 700px;">
	<caption>{LANG.googleplus_module}</caption>
	<thead>
		<tr class="center">
			<td>{LANG.weight}</td>
			<td>{LANG.module}</td>
			<td>{LANG.custom_title}</td>
			<td>{LANG.googleplus_title}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">{ROW.number}</td>
			<td>{ROW.title}</td>
			<td>{ROW.custom_title}</td>
			<td>
			<select id="id_mod_{ROW.title}" onchange="nv_mod_googleplus('{ROW.title}');">
				<option value="">&nbsp;</option>
				<!-- BEGIN: gid -->
				<option value="{GOOGLEPLUS.gid}"{GOOGLEPLUS.selected}>{GOOGLEPLUS.title}</option>
				<!-- END: gid -->
			</select></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: module -->
<div class="quote">
	<blockquote><span>{LANG.googleplusNote1}</span></blockquote>
</div>
<table class="tab1" style="width: 700px;">
	<caption>{LANG.googleplus_list}</caption>
	<thead>
		<tr class="center">
			<td>{LANG.weight}</td>
			<td>{LANG.googleplus_idprofile}</td>
			<td>{LANG.googleplus_title}</td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><strong>{LANG.googleplus_add}: </strong></td>
			<td><input class="w200" name="new_profile" id="new_profile" type="text" maxlength="255" /></td>
			<td colspan="2"><input class="w250" name="new_title" id="new_title" type="text" maxlength="255" /> <input name="Button1" type="button" value="{LANG.submit}" onclick="nv_add_googleplus();return;" /></td>
		</tr>
	</tfoot>
	<tbody>
		<!-- BEGIN: googleplus -->
		<tr>
			<td class="center">
			<select id="id_weight_{ROW.gid}" onchange="nv_chang_googleplus({ROW.gid});">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td class="center">{ROW.idprofile}</td>
			<td><input name="hidden_{ROW.gid}" id="hidden_{ROW.gid}" type="hidden" value="{ROW.title}" /><input type="text" name="title_{ROW.gid}" id="title_{ROW.gid}" value="{ROW.title}" class="w250" /> <input type="button" onclick="nv_save_title({ROW.gid});" value="{GLANG.save}" /></td>
			<td class="center"><em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_googleplus({ROW.gid})">{GLANG.delete}</a></td>
		</tr>
		<!-- END: googleplus -->
	</tbody>
</table>
<div class="quote">
	<blockquote><span>{LANG.googleplusNote2}</span></blockquote>
</div>
<!-- END: main -->
<!-- BEGIN: load -->
<div id="module_show_list">
	<div style="text-align:center;">
		<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..."/>
	</div>
</div>
<script type="text/javascript">nv_show_list_googleplus();</script>
<!-- END: load -->
