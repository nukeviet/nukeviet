<!-- BEGIN: main -->
<!-- BEGIN: data -->
<form class="form-inline" role="form">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr class="text-center">
					<th class="w100">{LANG.weight}</th>
					<th>{LANG.question}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">
					<select class="form-control" id="id_weight_{ROW.qid}" onchange="nv_chang_question({ROW.qid});">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight -->
					</select></td>
					<td><input name="hidden_{ROW.qid}" id="hidden_{ROW.qid}" type="hidden" value="{ROW.title}" /><input class="form-control" type="text" name="title_{ROW.qid}" id="title_{ROW.qid}" value="{ROW.title}" style="width:550px" />&nbsp; <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_save_title({ROW.qid});">{LANG.save}</a> &nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_question({ROW.qid})">{GLANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: data -->
<div class="form-inline">
	<strong>{LANG.question}: </strong><input class="form-control" style="width: 450px" name="new_title" id="new_title" type="text" maxlength="255" /> <input name="Button1" type="button" class="btn btn-primary" value="{LANG.addquestion}" onclick="nv_add_question();return;" />
</div>
<!-- END: main -->
<!-- BEGIN: load -->
<div id="module_show_list">
	<div style="text-align:center;">
		<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..."/>
	</div>
</div>
<script type="text/javascript">nv_show_list_question();</script>
<!-- END: load -->