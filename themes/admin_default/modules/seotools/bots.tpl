<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"> {ERROR} </blockquote>
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<thead>
			<tr class="center">
				<td>{LANG.bot_name}</td>
				<td>{LANG.bot_agent}(*)</td>
				<td>{LANG.bot_ips} (**)</td>
				<td>{LANG.bot_allowed}</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td class="center" colspan="4"><input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;" /></td>
			</tr>
		</tfoot>
		<tbody class="center">
			<!-- BEGIN: loop -->
			<tr>
				<td><input class="w200" type="text" value="{DATA.name}" name="bot_name[{DATA.id}]" /></td>
				<td><input class="w200" type="text" value="{DATA.agent}" name="bot_agent[{DATA.id}]" /></td>
				<td><input class="w200" type="text" value="{DATA.ips}" name="bot_ips[{DATA.id}]" /></td>
				<td><input type="checkbox" value="1" name="bot_allowed[{DATA.id}]" {DATA.checked} /></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</form>
<!-- END: main -->