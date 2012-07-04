<!-- BEGIN: main -->
<!-- BEGIN: data -->
<table class="tab1">
	<thead>
		<tr align="center">
			<td style="width:60px;">{LANG.weight}</td>
			<td>{LANG.question}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center">
				<select id="id_weight_{ROW.qid}" onchange="nv_chang_question({ROW.qid});">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select>
			</td>
			<td>
				<input name="hidden_{ROW.qid}" id="hidden_{ROW.qid}" type="hidden" value="{ROW.title}" />
				<input type="text" name="title_{ROW.qid}" id="title_{ROW.qid}" value="{ROW.title}" style="width:550px" />
				<span class="edit_icon"><a href="javascript:void(0);" onclick="nv_save_title({ROW.qid});">{LANG.save}</a></span>
				&nbsp;-&nbsp;
				<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_del_question({ROW.qid})">{GLANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: data -->
<div style="text-align:center; padding-top:15px;"><strong>{LANG.question}:
<input style="width: 450px" name="new_title" id="new_title" type="text" maxlength="255" />
<input name="Button1" type="button" value="{LANG.addquestion}" onclick="nv_add_question();return;" /></div>
<!-- END: main -->
<!-- BEGIN: load -->
<div id="module_show_list"><center><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..."/></center></div>
<script type="text/javascript">nv_show_list_question();</script>
<!-- END: load -->