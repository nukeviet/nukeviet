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
				<!-- BEGIN: address -->
				<tr>
					<td>{LANG.address}</td>
					<td>{DEPARTMENT.address}</td>
				</tr>
				<!-- END: address -->
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
					<td>{YAHOO.name}</td>
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="ymsgr:SendIM?{YAHOO.value}" title="{YAHOO.name}">{YAHOO.value}</a><!-- END: item --></td>
				</tr>
				<!-- END: yahoo -->
				<!-- BEGIN: skype -->
				<tr>
					<td>{SKYPE.name}</td>
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="skype:{SKYPE.value}?call" title="{SKYPE.name}">{SKYPE.value}</a><!-- END: item --></td>
				</tr>
				<!-- END: skype -->
                <!-- BEGIN: viber -->
				<tr>
					<td>{VIBER.name}</td>
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma -->{VIBER.value}<!-- END: item --></td>
				</tr>
				<!-- END: viber -->
                <!-- BEGIN: icq -->
				<tr>
					<td>{ICQ.name}</td>
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="icq:message?uin={ICQ.value}" title="{ICQ.name}">{ICQ.value}</a><!-- END: item --></td>
				</tr>
				<!-- END: icq -->
                <!-- BEGIN: whatsapp -->
				<tr>
					<td>{WHATSAPP.name}</td>
					<td><!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a data-android="intent://send/{WHATSAPP.value}#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" title="{WHATSAPP.name}">{WHATSAPP.value}</a><!-- END: item --></td>
				</tr>
				<!-- END: whatsapp -->
                <!-- BEGIN: other -->
				<tr>
					<td>{OTHER.name}</td>
					<td>{OTHER.value}</td>
				</tr>
				<!-- END: other -->
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