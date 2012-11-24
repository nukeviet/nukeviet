<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1" style="auto">
		<col width="200"/>
		<tbody>
			<tr>
				<td class="aright"><strong>{LANG.nv_max_size}:</strong></td>
				<td>
					<select name="nv_max_size">
						<!-- BEGIN: size -->
						<option value="{SIZE.key}"{SIZE.selected}>{SIZE.title}</option>
						<!-- END: size -->
					</select>
					({LANG.sys_max_size}: {SYS_MAX_SIZE})
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="aright"><strong>{LANG.upload_checking_mode}:</strong></td>
				<td>
					<select name="upload_checking_mode">
						<!-- BEGIN: upload_checking_mode -->
						<option value="{UPLOAD_CHECKING_MODE.key}"{UPLOAD_CHECKING_MODE.selected}>{UPLOAD_CHECKING_MODE.title}</option>
						<!-- END: upload_checking_mode -->
					</select>
					{UPLOAD_CHECKING_NOTE}
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.uploadconfig_types}</strong></td>
				<td>
					<!-- BEGIN: types -->
					<label style="display:inline-block;width:100px"><input type="checkbox" name="type[]" value="{TYPES.key}"{TYPES.checked}/> {TYPES.title}&nbsp;&nbsp;</label>
					<!-- END: types -->
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td style="vertical-align:top"><strong>{LANG.uploadconfig_ban_ext}</strong></td>
				<td>
					<!-- BEGIN: exts -->
					<label style="display:inline-block;width:100px"><input type="checkbox" name="ext[]" value="{EXTS.key}"{EXTS.checked} /> {EXTS.title}&nbsp;&nbsp;</label>
					<!-- END: exts -->
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td style="vertical-align:top"><strong>{LANG.uploadconfig_ban_mime}</strong></td>
				<td>
					<!-- BEGIN: mimes -->
					<label style="display:inline-block;width:300px"><input type="checkbox" name="ext[]" value="{MIMES.key}"{MIMES.checked} /> {MIMES.title}&nbsp;&nbsp;</label>
					<!-- END: mimes -->
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="center">
					<input type="submit" value="{LANG.banip_confirm}" name="submit"/>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->