<!-- BEGIN: main -->
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
		<div class="row">
			<div class="col-xs-24 col-md-4">
				<div class="form-group">
					<strong>{LANG.keywords}</strong>
				</div>
			</div>
			<div class="col-xs-24 col-md-4">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" />
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}" />
				</div>
			</div>
		</div>
	</form>
</div>

<div id="users">
    <table class="table table-striped table-bordered table-hover">
        <caption>{TABLE_CAPTION}</caption>
        <col class="w100" />
        <col />
        <col />
        <col class="w150" />
        <col class="w150" />
        <thead>
            <tr>
                <th>{LANG.position}</th>
                <th>{LANG.topic_name}</th>
                <th>{LANG.topic_parent}</th>
                <th class="text-center">{LANG.is_active}</th>
                <th class="text-center">{LANG.feature}</th>
            </tr>
        </thead>
        <tbody>
        <!-- BEGIN: row -->
            <tr>
                <td >
                    <select class="form-control" name="weight" id="weight{ROW.id}" onchange="nv_chang_weight({ROW.id});">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td>
                    <strong><a href="{ROW.titlelink}">{ROW.title}</a></strong>{ROW.numsub}
                </td>
                <td>
                    {ROW.parentid}
                </td>
                <td class="text-center">
                    <input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_chang_status({ROW.id});" />
                </td>
                <td>
                    <em class="fa fa-edit">&nbsp;</em><a href="{EDIT_URL}">{GLANG.edit}</a> - 
                    <em class="fa fa-trash-o">&nbsp;</em><a href="javascript:void(0);" onclick="nv_topic_del({ROW.id});">{GLANG.delete}</a>
                </td>
            </tr>
        <!-- END: row -->
        <tbody>
    </table>
</div>
<div style="margin-top:8px;">
    <a class="btn btn-primary" href="{ADD_NEW_TOPIC}">{LANG.addtopic_titlebox}</a>
</div>
<!-- END: main -->