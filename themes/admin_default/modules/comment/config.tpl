<!-- BEGIN: main -->
<!-- BEGIN: list -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr class="text-center">
				<th>{LANG.weight}</th>
				<th>{LANG.mod_name}</th>
				<th>{LANG.activecomm}</th>
				<th>{LANG.allowed_comm}</th>
				<th>{LANG.view_comm}</th>
				<th>{LANG.auto_postcomm}</th>
				<th>{LANG.emailcomm}</th>
				<th>{LANG.funcs}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.weight}</td>
				<td>{ROW.admin_title}</td>
				<td class="text-center"><em class="fa fa-{ROW.activecomm} fa-lg">&nbsp;</em></td>
				<td>{ROW.allowed_comm}</td>
				<td>{ROW.view_comm}</td>
				<td>{ROW.auto_postcomm}</td>
				<td class="text-center"><em class="fa fa-{ROW.emailcomm} fa-lg">&nbsp;</em></td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em><a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mod_name={ROW.mod_name}">{LANG.edit}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: list -->

<!-- BEGIN: config -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mod_name={MOD_NAME}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col style="width: 300px;" />
				<col style="width: auto;" />
			</colgroup>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.save}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td><strong>{LANG.activecomm}</strong></td>
					<td><input type="checkbox" value="1" name="activecomm"{ACTIVECOMM}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.allowed_comm}</strong></td>
					<td>
						<!-- BEGIN: allowed_comm -->
						<div class="row">
							<label><input name="allowed_comm[]" type="checkbox" value="{OPTION.value}" {OPTION.checked} />{OPTION.title}</label>
						</div>
						<!-- END: allowed_comm -->
					</td>
				</tr>
				<tr>
					<td><strong>{LANG.view_comm}</strong></td>
					<td>
						<!-- BEGIN: view_comm -->
						<div class="row">
							<label><input name="view_comm[]" type="checkbox" value="{OPTION.value}" {OPTION.checked} />{OPTION.title}</label>
						</div>
						<!-- END: view_comm -->
					</td>
				</tr>
				<tr>
					<td><strong>{LANG.setcomm}</strong></td>
					<td>
						<!-- BEGIN: setcomm -->
						<div class="row">
							<label><input name="setcomm[]" type="checkbox" value="{OPTION.value}" {OPTION.checked} />{OPTION.title}</label>
						</div>
						<!-- END: setcomm -->
					</td>
				</tr>
				<tr>
					<td><strong>{LANG.auto_postcomm}</strong></td>
					<td>
					<select name="auto_postcomm" class="form-control w300">
						<!-- BEGIN: auto_postcomm -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: auto_postcomm -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.emailcomm}</strong></td>
					<td><input type="checkbox" value="1" name="emailcomm"{EMAILCOMM}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.sortcomm}</strong></td>
					<td>
					<select name="sortcomm" class="form-control w300">
						<!-- BEGIN: sortcomm -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: sortcomm -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.adminscomm}</strong></td>
					<td>
					<!-- BEGIN: adminscomm -->
					<label style="display:inline-block;width:200px"> <input name="adminscomm[]" type="checkbox" value="{OPTION.key}" {OPTION.checked}>{OPTION.title} </label>
					<!-- END: adminscomm -->
					</td>
				</tr>
                <tr>
                    <td><strong>{LANG.perpagecomm}</strong></td>
                    <td>
                        <input type="text" name="perpagecomm" value="{DATA.perpagecomm}" class="w300 form-control"/>
                        <span class="help-block m-bottom-none">{LANG.perpagecomm_note}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.timeoutcomm}</strong></td>
                    <td>
                        <input type="text" name="timeoutcomm" value="{DATA.timeoutcomm}" class="w300 form-control"/>
                        <span class="help-block m-bottom-none">{LANG.timeoutcomm_note}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.allowattachcomm}</strong></td>
                    <td><input type="checkbox" value="1" name="allowattachcomm"{ALLOWATTACHCOMM}/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.alloweditorcomm}</strong></td>
                    <td><input type="checkbox" value="1" name="alloweditorcomm"{ALLOWEDITORCOMM}/></td>
                </tr>
			</tbody>
		</table>
	</div>
</form>
<div class="alert alert-info">{LANG.adminscomm_note}</div>
<!-- END: config -->
<!-- END: main -->