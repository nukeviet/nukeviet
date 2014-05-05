<!-- BEGIN: main -->
<table class="tab1">
	<caption>{DATA.title}</caption>
	<col class="w150"/>
	<col/>
	<tbody>
		<tr>
			<td style="vertical-align:top">{LANG.infor_user_send_title}</td>
			<td> {DATA.send_name} &lt;{DATA.sender_email}&gt;
			<br />
			<!-- BEGIN: sender_phone -->
			{GLANG.phonenumber}: {DATA.sender_phone}
			<br />
			<!-- END: sender_phone -->
			IP: {DATA.sender_ip}
			<br />
			{DATA.time} </td>
		</tr>
		<tr>
			<td>{LANG.part_row_title}</td>
			<td>{DATA.part_row_title}</td>
		</tr>
		<tr>
			<td colspan="2">{DATA.content}</td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<tbody>
		<tr>
			<td class="center">
			<!-- BEGIN: reply -->
			<a class="button button-h" href="{URL_REPLY}">{LANG.send_title}</a>
			<!-- END: reply -->
			<a class="button button-h" href="javascript:void(0);" onclick="nv_del_mess({DATA.id});">{GLANG.delete}</a>
			<a class="button button-h" href="{DATA.url_back}">{LANG.back_title}</a></td>
		</tr>
	</tbody>
</table>
<!-- BEGIN: data_reply -->
<table class="tab1">
	<caption>Re: {DATA.title}</caption>
	<col class="w150"/>
	<col />
	<tbody>
		<tr>
			<td style="vertical-align:top">{LANG.infor_user_send_title}</td>
			<td> {REPLY.reply_name} &lt;{REPLY.admin_email}&gt;
			<br />
			{REPLY.time} </td>
		</tr>
		<tr>
			<td>{LANG.reply_user_send_title}</td>
			<td>{REPLY.reply_time}</td>
		</tr>
		<tr>
			<td colspan="2">{REPLY.reply_content}</td>
		</tr>
	</tbody>
</table>
<!-- END: data_reply -->
<!-- END: main -->