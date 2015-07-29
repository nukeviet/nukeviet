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
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><!-- BEGIN: href --><a href="tel:{PHONE.href}"><!-- END: href -->{PHONE.number}<!-- BEGIN: href2 --></a><!-- END: href2 --><!-- END: item --></td>
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
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="{DEPARTMENT.emailhref}">{EMAIL}</a><!-- END: item --></td>
				</tr>
				<!-- END: email -->
				<!-- BEGIN: yahoo -->
				<tr>
					<td>{LANG.yahoo}</td>
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="ymsgr:SendIM?{YAHOO}" title="Yahoo Chat">{YAHOO}</a><!-- END: item --></td>
				</tr>
				<!-- END: yahoo -->
				<!-- BEGIN: skype -->
				<tr>
					<td>{LANG.skype}</td>
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="skype:{SKYPE}?call" title="Skype">{SKYPE}</a><!-- END: item --></td>
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