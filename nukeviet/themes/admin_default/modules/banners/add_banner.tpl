<!-- BEGIN: upload_blocked -->
<div class="quote" style="width:98%">
	<blockquote><span>{CONTENTS.upload_blocked}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: upload_blocked -->
<!-- BEGIN: main -->
<div class="quote" style="width:98%">
<blockquote{CLASS}><span>{CONTENTS.info}</span></blockquote>
</div>
<div class="clear"></div>
<form method="post" enctype="multipart/form-data" action="{CONTENTS.action}">
	<input type="hidden" value="1" name="save" id="save" />
	<table summary="{CONTENTS.info}" class="tab1">
		<col style="width:250px;white-space:nowrap" />
		<col valign="top" width="10px" />
		<tbody class="second">
			<tr>
				<td>{CONTENTS.title.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="{CONTENTS.title.1}" id="{CONTENTS.title.1}" type="text" value="{CONTENTS.title.2}" style="width:300px" maxlength="{CONTENTS.title.3}" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.plan.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td>
					<select name="{CONTENTS.plan.1}" id="{CONTENTS.plan.1}">
						<!-- BEGIN: plan -->
						<option value="{PLAN.key}"{PLAN.selected}>{PLAN.title}</option>
						<!-- END: plan -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.client.0}:</td>
				<td></td>
				<td>
					<select name="{CONTENTS.client.1}" id="{CONTENTS.client.1}">
						<option value="">&nbsp;</option>
						<!-- BEGIN: client -->
						<option value="{CLIENT.key}"{CLIENT.selected}>{CLIENT.title}</option>
						<!-- END: client -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.upload.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="{CONTENTS.upload.1}" type="file" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.file_alt.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.file_alt.1}" id="{CONTENTS.file_alt.1}" type="text" value="{CONTENTS.file_alt.2}" style="width:300px" maxlength="{CONTENTS.file_alt.3}" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.click_url.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.click_url.1}" id="{CONTENTS.click_url.1}" type="text" value="{CONTENTS.click_url.2}" style="width:300px" maxlength="{CONTENTS.click_url.3}" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.publ_date.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.publ_date.1}" id="{CONTENTS.publ_date.1}" type="text" value="{CONTENTS.publ_date.2}" style="width:278px" maxlength="{CONTENTS.publ_date.3}" readonly="readonly" />
				<img src="{CONTENTS.publ_date.4}" width="{CONTENTS.publ_date.5}" height="{CONTENTS.publ_date.6}" style="cursor:pointer;vertical-align: middle;" onclick="{CONTENTS.publ_date.7}" alt="" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.exp_date.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.exp_date.1}" id="{CONTENTS.exp_date.1}" type="text" value="{CONTENTS.exp_date.2}" style="width:278px" maxlength="{CONTENTS.exp_date.3}" readonly="readonly" />
				<img src="{CONTENTS.exp_date.4}" width="{CONTENTS.exp_date.5}" height="{CONTENTS.exp_date.6}" style="cursor:pointer;vertical-align: middle;" onclick="{CONTENTS.exp_date.7}" alt="" /></td>
			</tr>
		</tbody>
	</table>
	<div class="center">
		<input type="submit" value="{CONTENTS.submit}" />
	</div>
</form>
<!-- END: main -->