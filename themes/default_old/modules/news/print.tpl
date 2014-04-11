<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{CONTENT.title} - {CONTENT.sitename}</title>
		{CONTENT.meta_tags}
		<style type="text/css">
			body {
				margin: 0;
				padding: 0;
				text-align: center;
				color: #4D5764;
			}

			a, a:active, a:focus, a:visited {
				color: #4D5764;
				text-decoration: none;
			}

			h1 {
				font-size: 160%
			}

			h2 {
				font-size: 150%
			}

			a:hover {
				text-decoration: underline;
			}

			h1, h2, p {
				margin: 0;
				padding: 0;
			}

			#main {
				margin: 0 auto;
				text-align: left;
				width: 800px;
			}

			#header {
				height: 100%;
				line-height: 40px;
				border-bottom: 2px solid #DC0312;
			}

			#header h2 {
				float: left;
				display: inline;
			}

			#header p {
				float: right;
				display: inline;
			}

			#content #hometext {
				font-weight: bold;
				margin-bottom: 10px;
				text-align: justify;
			}

			#content #bodytext {
				text-align: justify;
			}

			#content ul.control {
				margin: 0;
				padding: 10px 0;
			}

			#content ul.control li {
				display: inline;
				float: left;
				list-style: none;
				font-size: 95%;
			}

			#content ul.control li a {
				padding: 0 2px;
			}

			#content ul.control li a:hover {
				text-decoration: none;
				cursor: pointer;
			}

			#content .time {
				color: #333;
			}

			#content #imghome {
				padding: 4px;
				margin: 0 auto;
			}

			#content #imghome p {
				font-weight: normal
			}

			#content .copyright {
				background: #66CCFF;
				padding: 4px;
				width: 100%;
			}

			#content #author {
				text-align: right;
			}

			#footer {
				margin-top: 10px;
				border-top: 2px solid #DC0312;
			}

			#footer #url {
				line-height: 20px;
				font-size: 100%;
				display: block;
				border-bottom: 2px solid #4D5764;
			}

			#footer .copyright {
				float: left;
				display: inline;
				padding: 5px 0;
			}

			#footer #contact {
				float: right;
				display: inline;
				padding: 5px 0;
			}

			#footer #contact a:hover {
				text-decoration: none;
				cursor: pointer;
			}

			.fl {
				float: left
			}

			.fr {
				float: right
			}

			.clear {
				clear: both;
			}

			.clearfix:after {
				content: ".";
				display: block;
				clear: both;
				visibility: hidden;
				line-height: 0;
				height: 0;
			}

			.clearfix {
				display: inline-block;
			}

			html[xmlns] .clearfix {
				display: block;
			}

			* html .clearfix {
				height: 1%;
			}
		</style>
	</head>
	<body>
		<div id="main">
			<div id="header" class="clearfix">
				<h2>{CONTENT.sitename}</h2>
				<p>
					<a title="{CONTENT.sitename}" href="{CONTENT.url}/">{CONTENT.url}</a>
				</p>
			</div>
			<div class="clear"></div>
			<div id="content">
				<h1>{CONTENT.title}</h1>
				<ul class="control">
					<li class="time">
						{CONTENT.time}
					</li>
					<li>
						|<a title="{LANG.print}" href="javascript:;" onclick="window.print()">{LANG.print}</a>
					</li>
					<li>
						|<a title="{LANG.print_close}" href="javascript:;" onclick="window.close()">{LANG.print_close}</a>
					</li>
				</ul>
				<div class="clear"></div>
				<div id="hometext">
					<!-- BEGIN: image -->
					<div id="imghome" class="fl">
						<img alt="{CONTENT.image.alt}" src="{CONTENT.image.src}" width="{CONTENT.image.width}" />
						<!-- BEGIN: note -->
						<p>
							<em>{CONTENT.image.note}</em>
						</p>
						<!-- END: note -->
					</div>
					<!-- END: image -->
					{CONTENT.hometext}
				</div>
				<!-- BEGIN: imagefull -->
				<div id="imghome">
					<img alt="{CONTENT.image.alt}" src="{CONTENT.image.src}" width="{CONTENT.image.width}" />
					<!-- BEGIN: note -->
					<p>
						<em>{CONTENT.image.note}</em>
					</p>
					<!-- END: note -->
				</div>
				<div class="clear"></div>
				<!-- END: imagefull -->
				<div id="bodytext" class="clearfix">
					{CONTENT.bodytext}
				</div>
				<!-- BEGIN: author -->
				<div id="author">
					<!-- BEGIN: name -->
					<p>
						<strong>{LANG.author}:</strong>
						{CONTENT.author}
					</p>
					<!-- END: name -->
					<!-- BEGIN: source -->
					<p>
						<strong>{LANG.source}:</strong>
						{CONTENT.source}
					</p>
					<!-- END: source -->
				</div>
				<!-- END: author -->
				<!-- BEGIN: copyright -->
				<div class="copyright">
					{CONTENT.copyvalue}
				</div>
				<!-- END: copyright -->
			</div>
			<div id="footer">
				<div id="url">
					<strong>{LANG.print_link}: </strong>{CONTENT.link}
				</div>
				<div class="clear"></div>
				<div class="copyright">
					&copy; {CONTENT.sitename}
				</div>
				<div id="contact">
					<a href="mailto:{CONTENT.contact}">{CONTENT.contact}</a>
				</div>
			</div>
		</div>
	</body>
</html>
<!-- END: main-->