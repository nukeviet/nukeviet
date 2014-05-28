<!-- BEGIN: main -->
	<div class="panel panel-default">
		<table class="table">			
			<tbody>
				<tr>
					<td colspan="2" class="active"><strong>{LANG.department}: {DEPARTMENT.full_name}</strong></td>
				</tr>
				<!-- BEGIN: phone -->
				<tr>
					<td>{LANG.phone}</td>
					<td>{DEPARTMENT.phone}</td>
				</tr>
				<!-- END: phone -->
				<!-- BEGIN: fax -->
				<tr>
					<td>{LANG.fax}</td>
					<td>{DEPARTMENT.fax}</td>
				</tr>
				<!-- END: fax -->
				<!-- BEGIN: email -->
				<tr>
					<td>{LANG.email}</td>
					<td><a href="mailto:{DEPARTMENT.email}">{DEPARTMENT.email}</a></td>
				</tr>
				<!-- END: email -->
				<!-- BEGIN: yahoo -->
				<tr>
					<td>{LANG.skype}</td>
					<td><a href="ymsgr:sendIM?{DEPARTMENT.yahoo}">{DEPARTMENT.yahoo}</a></td>
				</tr>
				<!-- END: yahoo -->
				<!-- BEGIN: skype -->
				<tr>
					<td>{LANG.skype}</td>
					<td><a href="skype:{DEPARTMENT.skype}?chat">{DEPARTMENT.skype}</a></td>
				</tr>
				<!-- END: skype -->
				<!-- BEGIN: note -->
				<tr>
					<td>{LANG.note_s}</td>
					<td>{DEPARTMENT.note}</td>
				</tr>
				<!-- END: note -->
			</tbody>
  		</table>
	</div>
<!-- END: main -->