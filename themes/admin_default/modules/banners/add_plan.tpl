<!-- BEGIN: main -->
<div class="alert alert-info">{CONTENTS.info}</div>
<form method="post" action="{CONTENTS.action}">
	<input type="hidden" value="1" name="save" id="save" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col class="w200"/>
			<col class="w20">
			<col>
			<tbody>
				<tr>
					<td>{CONTENTS.title.0}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input class="w300 form-control" name="{CONTENTS.title.1}" id="{CONTENTS.title.1}" type="text" value="{CONTENTS.title.2}" maxlength="{CONTENTS.title.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.size}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input name="{CONTENTS.width.1}" id="{CONTENTS.width.1}" type="text" value="{CONTENTS.width.2}" class="form-control w100 pull-left" maxlength="{CONTENTS.width.3}" placeholder="{CONTENTS.width.0}" /><span class="pull-left text-middle">&nbsp;x&nbsp;</span><input name="{CONTENTS.height.1}" id="{CONTENTS.height.1}" type="text" value="{CONTENTS.height.2}" class="form-control w100 pull-left" maxlength="{CONTENTS.height.3}" placeholder="{CONTENTS.height.0}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.blang.0}:</td>
					<td>&nbsp;</td>
					<td>
					<select name="{CONTENTS.blang.1}" id="{CONTENTS.blang.1}" class="form-control w200">
						<option value="">{CONTENTS.blang.2}</option>
						<!-- BEGIN: blang -->
						<option value="{BLANG.key}"{BLANG.selected}>{BLANG.title}</option>
						<!-- END: blang -->
					</select></td>
				</tr>
				<tr>
					<td>{CONTENTS.form.0}:</td>
					<td>&nbsp;</td>
					<td>
					<select name="{CONTENTS.form.1}" id="{CONTENTS.form.1}" class="form-control w200">
						<!-- BEGIN: form -->
						<option value="{FORM.key}"{FORM.selected}>{FORM.title}</option>
						<!-- END: form -->
					</select></td>
				</tr>
				<tr>
					<td colspan="3">{CONTENTS.description.0}:</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div>
		{DESCRIPTION}
	</div>
	<div style="padding-top:10px" class="text-center">
		<input type="submit" value="{CONTENTS.submit}" class="btn btn-primary" />
	</div>
</form>
<!-- END: main -->