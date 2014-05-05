<!-- BEGIN: main -->
<div id="users">
	<form action="{FORM_ACTION}" method="post">
		<table class="tab1">
			<colgroup>
				<col style="width: 260px" />
				<col/>
			</colgroup>
			<tfoot>
				<tr>
					<td class="center" colspan="2"><input type="submit" name="submit" value="{LANG.config_confirm}" /></td>
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
					<select name="who_addfile">
						<!-- BEGIN: who_addfile -->
						<option value="{WHO_ADDFILE.key}"{WHO_ADDFILE.selected}> {WHO_ADDFILE.title}</option>
						<!-- END: who_addfile -->
					</select>
					<!-- BEGIN: group3 -->
					<br />
					{LANG.groups_upload}
					<br />
					<!-- BEGIN: groups_addfile -->
					<input name="groups_addfile[]" value="{GROUPS_ADDFILE.key}" type="checkbox"{GROUPS_ADDFILE.checked} /> {GROUPS_ADDFILE.title}
					<br />
					<!-- END: groups_addfile -->
					<!-- END: group3 -->
					</td>
				</tr>
				<tr>
					<td>{LANG.config_is_uploadfile}</td>
					<td><input name="is_upload" value="1" type="checkbox"{DATA.is_upload} /></td>
				</tr>
				<tr>
					<td>{LANG.config_whouploadfile}</td>
					<td>
					<select name="who_upload">
						<!-- BEGIN: who_upload -->
						<option value="{WHO_UPLOAD.key}"{WHO_UPLOAD.selected}> {WHO_UPLOAD.title}</option>
						<!-- END: who_upload -->
					</select>
					<!-- BEGIN: group_empty -->
					<br />
					{LANG.groups_upload}
					<br />
					<!-- BEGIN: groups_upload -->
					<input name="groups_upload[]" value="{GROUPS_UPLOAD.key}" type="checkbox"{GROUPS_UPLOAD.checked} /> {GROUPS_UPLOAD.title}
					<br />
					<!-- END: groups_upload -->
					<!-- END: group_empty -->
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
					<td><input name="maxfilesize" value="{DATA.maxfilesize}" type="text" maxlength="10" class="right"/> {LANG.config_maxfilemb}
					<br />
					{LANG.config_maxfilesizesys} {NV_UPLOAD_MAX_FILESIZE}</td>
				</tr>
				<tr>
					<td>{LANG.config_uploadedfolder}</td>
					<td><input name="upload_dir" value="{DATA.upload_dir}" type="text" maxlength="100" /></td>
				</tr>
				<tr>
					<td>{LANG.config_queuefolder}</td>
					<td><input name="temp_dir" value="{DATA.temp_dir}" type="text" maxlength="100" /></td>
				</tr>
				<tr>
					<td>{LANG.is_resume}</td>
					<td><input name="is_resume" value="1" type="checkbox"{DATA.is_resume} /></td>
				</tr>
				<tr>
					<td>{LANG.max_speed}</td>
					<td><input name="max_speed" value="{DATA.max_speed}" type="text" style="width:50px" maxlength="4" /> {LANG.kb_sec} </td>
				</tr>
				<tr>
					<td>{LANG.is_zip}</td>
					<td><input name="is_zip" value="1" type="checkbox"{DATA.is_zip} /></td>
				</tr>
				<tr>
					<td class="top">{LANG.zip_readme}</td>
					<td><textarea name="readme" cols="20" rows="5" class="txt-full">{DATA.readme}</textarea></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<!-- END: main -->