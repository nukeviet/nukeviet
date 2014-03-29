<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{VOTINGQUESTION}</title>
		<meta http-equiv="Content-Language" content="vi" />
		{SCRIPT}
		<script type="text/javascript">
			$(document).ready(function() {
				if ($("#poll-results").length > 0) {
					animateResults();
				}
			});

			function animateResults() {
				$("#poll-results div").each(function() {
					var percentage = $(this).next().text();
					$(this).css({
						width : "0%"
					}).animate({
						width : percentage
					}, 'slow');
				});
			}
		</script>
		<style type="text/css">
			body {
				font-family: arial, verdana, helvetica, sans-serif;
				font-size: 12px;
				cursor: default;
				background-color: #ffffff;
			}

			* {
				margin: 0;
				padding: 0;
				text-decoration: none;
			}

			html {
				height: 100%;
				margin-bottom: 1px;
			}

			#wrapper {
				width: 650px;
				margin: auto;
			}

			hr {
				border: 0;
				color: #cccccc;
				background-color: #cdcdcd;
				height: 1px;
				width: 100%;
				text-align: left;
			}

			h1 {
				font-size: 28px;
				color: #cc0000;
				background-color: #ffffff;
				font-family: Arial, Verdana, Helvetica, sans-serif;
				font-weight: 300;
			}

			h2 {
				font-size: 15px;
				color: gray;
				font-family: Arial, Verdana, Helvetica, sans-serif;
				font-weight: 300;
				background-color: #ffffff;
			}

			h3 {
				color: #cc0000;
				font-size: 150%;
				text-align: left;
				font-weight: 600;
				padding: 10px 5px 15px 5px;
				margin-top: 5px;
				text-align: center;
			}

			p {
				color: #000;
				background-color: #ffffff;
				line-height: 20px;
				padding: 5px;
			}

			.graph {
				width: 100%;
				position: relative;
				right: 1%;
			}

			.bar-title {
				position: relative;
				float: left;
				width: 50%;
				line-height: 20px;
				margin-right: 17px;
				font-weight: bold;
				text-align: right;
			}

			.bar-container {
				position: relative;
				float: left;
				width: 40%;
				height: 10px;
				margin: 0 0 15px;
			}

			.bar-container div {
				background-color: #cc4400;
				height: 20px;
			}

			.bar-container strong {
				position: absolute;
				right: -32px;
				top: 0;
				overflow: hidden;
			}

			#poll-results p {
				text-align: center;
				clear: both;
			}
		</style>
	</head>
	<body>
		<div id="wrapper">
			<div id="poll-container">
				<div id="poll-results">
					<!-- BEGIN: note -->
					<p style="color: #0000ff;padding-top:10px">
						<strong>{VOTINGNOTE}</strong>
					</p>
					<!-- END: note -->
					<h3>{VOTINGQUESTION}</h3>
					<dl class="graph">
						<!-- BEGIN: result -->
						<dt class="bar-title">
							{VOTING.title}
						</dt>
						<dd class="bar-container">
							<div style="width: {WIDTH}%;display: block;{BG}" id="bar{ID}">
								&nbsp;
							</div>
							<strong>{WIDTH}%</strong>
						</dd>
						<!-- END: result -->
					</dl>
					<p style="padding-top:10px">
						<strong>{LANG.total}</strong>: {TOTAL} {LANG.counter} - <strong>{LANG.publtime}:</strong>
						{PUBLTIME}
					</p>
				</div>
			</div>
		</div>
	</body>
</html>
<!-- END: main -->