<!-- BEGIN: main -->
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={SITE_CHARSET}" />
		<style type="text/css">
			.content {
				COLOR: #333;
				FONT: 13px Arial, Helvetica, sans-serif;
				MARGIN: 15px 0px 20px 0px;
			}

			.footer {
				COLOR: #333;
				FONT: 12px Arial, Helvetica, sans-serif;
			}

			.sitename {
				COLOR: #333;
				FONT: bold 17px Arial, Helvetica, sans-serif;
				MARGIN: 5px 0px 5px 0px;
			}

			.subtitle {
				COLOR: #333;
				FONT: bold 12px Arial, Helvetica, sans-serif;
			}

			.subtitle A, .footer A {
				COLOR: #333;
				TEXT-DECORATION: underline;
			}

			.title {
				COLOR: #F00;
				FONT: bold 14px Arial, Helvetica, sans-serif;
				MARGIN: 15px 0px 5px 0px;
			}

			.footer A:hover, .content A:hover {
				COLOR: #F00;
			}

			.subtitle A:hover {
				COLOR: #F00;
				TEXT-DECORATION: none;
			}
		</style>
	</head>
	<body>
		<div class="sitename">
			{SITE_NAME} - {SITE_SLOGAN}
		</div>
		<div class="subtitle">
			Email: <a href="mailto:{SITE_EMAIL}">{SITE_EMAIL}</a>&nbsp;&nbsp; Tel: {SITE_FONE}&nbsp;&nbsp; Website: <a href="{SITE_URL}">{SITE_URL}</a>
		</div>
		<hr/>
		<div class="title">
			{TITLE}
		</div>
		<div class="content">
			{CONTENT}
		</div>
		<hr/>
		<div class="footer">
			{AUTHOR_SIG},
			<br/>
			<br/>
			{AUTHOR_NAME}
			<br/>
			{AUTHOR_POS}
			<br/>
			Contact: <a href="mailto:{AUTHOR_EMAIL}">{AUTHOR_EMAIL}</a>
		</div>
	</body>
</html>
<!-- END: main -->