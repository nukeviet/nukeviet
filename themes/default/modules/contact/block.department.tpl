<!-- BEGIN: main  -->
		<div class="panel panel-default">
			<table class="table">
				<!-- BEGIN: department -->				
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
					<!-- BEGIN: note -->
					<tr>
						<td>{LANG.note_s}</td>
						<td>{DEPARTMENT.note}</td>
					</tr>
					<!-- END: note -->
				</tbody>
				<!-- END: department -->
	  		</table>
		</div>
<!-- END: main -->