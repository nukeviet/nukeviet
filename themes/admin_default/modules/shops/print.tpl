<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>print</title>
		<meta http-equiv="Content-Language" content="vi" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>

	<body>
		<style type="text/css">
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}

			.divall {
				border: 1px solid #CCC;
			}

			.cltab {
				margin: auto;
				width: 100%;
			}

			.cltab tr {
				height: 30px;
			}

			.cltab tr td {
				border-bottom: #E9E9E9 1px dotted;
				padding: 5px;
			}

			.tabright {
				width: 100%;
			}

			.div_top {
				background: #DFF;
				color: #036;
				font-weight: bold;
				line-height: 20px;
				padding: 5px
			}
		</style>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
				<div class="div_top">
					{LANG.order_info} <span style="font-weight:normal"> ( {LANG.order_date} {dateup} {LANG.order_moment} {moment}) </span>
				</div>
				<div class="divall">
					<table class="cltab">
						<tr>
							<td width="100px"><strong>{LANG.order_name}</strong></td>
							<td>: {DATA.order_name}</td>
						</tr>
						<tr>
							<td><strong>{LANG.order_email}</strong></td>
							<td>: {DATA.order_email}</td>
						</tr>
						<tr>
							<td><strong>{LANG.order_address}</strong></td>
							<td>: {DATA.order_address}</td>
						</tr>
						<tr>
							<td><strong>{LANG.order_phone}</strong></td>
							<td>: {DATA.order_phone}</td>
						</tr>
					</table>
					<div style="padding:5px;">
						{DATA.order_note}
					</div>
					<table class="cltab" cellpadding="0" cellspacing="1">
						<tr bgcolor="#B5D6FD">
							<td>&nbsp;</td>
							<td>{LANG.name}</td>
							<td width="50" class="text-center">{LANG.order_product_number}</td>
							<td width="80" align="right">{LANG.order_product_price}</td>
							<td width="40" class="text-center">{LANG.order_product_unit}</td>
						</tr>
						<!-- BEGIN: loop -->
						<tr>
							<td>{tt}</td>
							<td>{product_name}</td>
							<td width="50" class="text-center">{product_number}</td>
							<td width="80" align="right">{product_price}</td>
							<td width="40" class="text-center">{product_unit}</td>
						</tr>
						<!-- END: loop -->
					</table>
					<div class="div_top">
						{LANG.order_total} : {order_total} {unit} -/- <span style="color:#F00">{payment}</span>
					</div>
				</div></td>
			</tr>
		</table>
	</body>
</html>
<!-- END: main -->