<!-- BEGIN: main-->
<!-- BEGIN: error-->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="form_edit_ftp">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr class="text-center">
					<th>{LANG.plugin_area}</th>
					<th>{LANG.plugin_number}</th>
					<th>{LANG.plugin_file}</th>
					<th>{LANG.plugin_func}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>{DATA.plugin_area}</td>
					<td class="text-center">
					<select id="weight_{DATA.pid}" onchange="nv_chang_weight('{DATA.pid}');" class="form-control w100">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT}" {WEIGHT_SELECTED}>{WEIGHT}</option>
						<!-- END: weight -->
					</select></td>
					<td>{DATA.plugin_file}</td>
					<td class="text-center"><em class="fa fa-trash-o fa-lg"> </em><a onclick="return confirm(nv_is_del_confirm[0]);" href="{DATA.plugin_delete}">{LANG.isdel}</a></td>
				</tr>
				<!-- END: loop -->
				<!-- BEGIN: add -->
				<tr>
					<td colspan="4" class="text-center"> {LANG.plugin_add}
					<select name="plugin_file" class="form-control w200">
						<option value=""> -- </option>
						<!-- BEGIN: file -->
						<option value="{PLUGIN_FILE}" {PLUGIN_SELECTED}>{PLUGIN_FILE} </option>
						<!-- END: file -->
					</select> &nbsp;
                    <!-- BEGIN: info -->
                    <br><br><div class="panel panel-info">
                        <div class="panel-heading">{NAME}</div>
                        <div class="panel-body">
                            {DESCRIPTION}
                        </div>
                    </div>
                    <!-- END: info -->                     
					{LANG.plugin_area}: <select name="plugin_area" class="form-control w200">
						<option value=""> -- </option>
						<!-- BEGIN: area -->
						<option value="{AREA_VALUE}">{AREA_TEXT} </option>
						<!-- END: area -->
					</select> &nbsp; 
                    <input class="btn btn-primary" type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/> &nbsp; <input class="btn btn-danger" type="submit" name="delete" onclick="return confirm(nv_is_del_confirm[0]);" value="{LANG.plugin_file_delete}" style="width: 150px;"/></td>
				</tr>
				<!-- END: add -->
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
    $("select[name=plugin_file]").change(function() {
        window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&plugin_file=' + $(this).val();
    });
</script>    
<!-- END: main -->