<!-- BEGIN: main -->
<table class="tab1">
	<caption>{DATA.title}</caption>
	<col width="150px" />
	<tbody>
		<tr>
			<td style="vertical-align:top">{LANG.infor_user_send_title}</td>
			<td>
				{DATA.send_name} &lt;{DATA.sender_email}&gt;<br />
				<!-- BEGIN: sender_phone -->{GLANG.phonenumber}: {DATA.sender_phone}<br /><!-- END: sender_phone -->
				IP: {DATA.sender_ip}<br />
				{DATA.time}
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>{LANG.part_row_title}</td>
			<td>{DATA.part_row_title}</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2">{DATA.content}</td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<tbody>
		<tr>
			<td class="center">
				<!-- BEGIN: reply --><a class="button1" href="{URL_REPLY}"><span><span>{LANG.send_title}</span></span></a><!-- END: reply -->
				<a class="button1" href="javascript:void(0);" onclick="nv_del_mess({DATA.id});"><span><span>{GLANG.delete}</span></span></a>
				<a class="button1" href="{DATA.url_back}"><span><span>{LANG.back_title}</span></span></a>
			</td>
		</tr>
	</tbody>
</table>
<!-- BEGIN: data_reply -->
<table class="tab1">
	<caption>Re: {DATA.title}</caption>
	<col width="150px" />
	<tbody>
		<tr>
			<td style="vertical-align:top">{LANG.infor_user_send_title}</td>
			<td>
				{REPLY.reply_name} &lt;{REPLY.admin_email}&gt;<br />
				{REPLY.time}
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>{LANG.reply_user_send_title}</td>
			<td>{REPLY.sender_name} &lt;{DATA.sender_email}&gt;</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2">{DATA.reply_content}</td>
		</tr>
	</tbody>
</table>
<!-- END: data_reply -->
<!-- END: main -->
