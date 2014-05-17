<!-- BEGIN: main -->
<div id="users">
	<form action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td class="text-center" colspan="2"><input type="submit" name="submit" value="{LANG.config_confirm}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>{LANG.config_is_addfile}</td>
						<td><input name="is_addfile" value="1" type="checkbox"{DATA.is_addfile} /></td>
					</tr>
					<tr>
						<td>{LANG.config_whoaddfile}</td>
						<td>
							<!-- BEGIN: groups_addfile -->
							<input name="groups_addfile[]" value="{GROUPS_ADDFILE.key}" type="checkbox"{GROUPS_ADDFILE.checked} /> {GROUPS_ADDFILE.title}
							<br />
							<!-- END: groups_addfile -->
						</td>
					</tr>
					<tr>
						<td>{LANG.config_is_uploadfile}</td>
						<td><input name="is_upload" value="1" type="checkbox"{DATA.is_upload} /></td>
					</tr>
					<tr>
						<td>{LANG.config_whouploadfile}</td>
						<td>
							<!-- BEGIN: groups_upload -->
							<input name="groups_upload[]" value="{GROUPS_UPLOAD.key}" type="checkbox"{GROUPS_UPLOAD.checked} /> {GROUPS_UPLOAD.title}
							<br />
							<!-- END: groups_upload -->
						</td>
					</tr>
					<tr>
						<td class="top">{LANG.config_allowfiletype}</td>
						<td>
							<div class="dl-fixheight">
								<!-- BEGIN: upload_filetype -->
								<label><input name="upload_filetype[]" value="{UPLOAD_FILETYPE.ext}" type="checkbox"{UPLOAD_FILETYPE.checked} /> {UPLOAD_FILETYPE.title}</label><br />
								<!-- END: upload_filetype -->
							</div>
						</td>
					</tr>
					<tr>
						<td>{LANG.config_maxfilesize}</td>
						<td><input name="maxfilesize" value="{DATA.maxfilesize}" type="text" maxlength="10" class="pull-left form-control w200"/><span class="text-middle"> {LANG.config_maxfilemb}. {LANG.config_maxfilesizesys} {NV_UPLOAD_MAX_FILESIZE} </span></td>
					</tr>
					<tr>
						<td>{LANG.config_uploadedfolder}</td>
						<td><input name="upload_dir" value="{DATA.upload_dir}" type="text" maxlength="100" class="form-control w200" /></td>
					</tr>
					<tr>
						<td>{LANG.config_queuefolder}</td>
						<td><input name="temp_dir" value="{DATA.temp_dir}" type="text" maxlength="100" class="form-control w200" /></td>
					</tr>
					<tr>
						<td>{LANG.is_resume}</td>
						<td><input name="is_resume" value="1" type="checkbox"{DATA.is_resume} /></td>
					</tr>
					<tr>
						<td>{LANG.max_speed}</td>
						<td><input name="max_speed" value="{DATA.max_speed}" type="text" class="form-control w100 pull-left" maxlength="4" /><span class="text-middle"> {LANG.kb_sec} </span></td>
					</tr>
					<tr>
						<td>{LANG.is_zip}</td>
						<td><input name="is_zip" value="1" type="checkbox"{DATA.is_zip} /></td>
					</tr>
					<tr>
						<td class="top">{LANG.zip_readme}</td>
						<td><textarea name="readme" cols="20" rows="5" class="form-control">{DATA.readme}</textarea></td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- END: main -->