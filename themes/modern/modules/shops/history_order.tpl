<!-- BEGIN: main -->
<table class="rows">
	<tr class="bgtop">
		<td align="center" width="20">{LANG.order_no_products}</td>
		<td>{LANG.order_code}</td>
		<td width="125">{LANG.history_date}</td>
		<!-- BEGIN: price1 -->
		<td align="right">{LANG.history_total}</td>
		<!-- END: price1 -->
		<td>{LANG.history_payment}</td>
		<td align="center" width="40">{LANG.history_remove}</td>
	</tr>
	<!-- BEGIN: rows -->
	<tr {bg}>
		<td align="center">{TT}</td>
		<td><a title="{history_date} {LANG.order_moment} {history_moment}" href="{link}"><strong>{order_code}</strong></a></td>
		<td>{history_date} {LANG.order_moment} {history_moment}</td>
		<!-- BEGIN: price2 -->
		<td class="money" align="right">{history_total} ({unit_total})</td>
		<!-- END: price2 -->
		<td>{history_payment}</td>
		<td align="center">
		<!-- BEGIN: remove -->
		<a class="del" title="{LANG.history_remove}" href="{link_remove}">{LANG.history_remove}</a>
		<!-- END: remove -->
		{text_no_remove} </td>
	</tr>
	<!-- END: rows -->
	<tbody>
		<tfoot>
			<tr>
				<td align="right" colspan="7"><input class="button" id="Check_Order" type="button" value="{LANG.history_update}"></td>
			</tr>
		</tfoot>
</table>
<script type="text/javascript">
	$(function() {
		$('#Check_Order').click(function(event) {
			event.preventDefault();
			$.ajax({
				type : 'GET',
				url : '{LINK_CHECK_ORDER}&nocache=' + new Date().getTime(),
				data : '',
				success : function(data) {
					var s = data.split('_');
					var strText = s[1];
					var intIndexOfMatch = strText.indexOf('#@#');
					while (intIndexOfMatch != -1) {
						strText = strText.replace('#@#', '_');
						intIndexOfMatch = strText.indexOf('#@#');
					}
					alert(strText);
					if (s[0] == 'UPDATE') {
						window.location = '{URL_DEL_BACK}';
					}
				}
			});
			return false;
		});

		$('a.del').click(function(event) {
			event.preventDefault();
			if (confirm("{LANG.history_del_confirm}")) {
				var href = $(this).attr('href');
				$.ajax({
					type : 'GET',
					url : href + "&nocache=" + new Date().getTime(),
					data : '',
					success : function(data) {
						var s = data.split('_');
						var strText = s[1];
						var intIndexOfMatch = strText.indexOf('#@#');
						while (intIndexOfMatch != -1) {
							strText = strText.replace('#@#', '_');
							intIndexOfMatch = strText.indexOf('#@#');
						}
						alert(strText);
						window.location = '{URL_DEL_BACK}';
					}
				});
				return false;
			}
		});
	}); 
</script>
<!-- END: main -->