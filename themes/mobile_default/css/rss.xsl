<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.1" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:variable name="title" select="/rss/channel/title"/>
	<xsl:variable name="link" select="rss/channel/link"/>		
	<xsl:template match="/">
		<html>
			<head>
				<title><xsl:value-of select="$title"/></title>	
				<style type="text/css">
				body{background:#fff;border:0px;margin:10px;font:normal 80% Verdana,Arial,Helvetica;color:#000}
				hr{color:#C0C0C0;background:#C0C0C0;border:0px none;height:1px;clear:both;margin:15px 0}
				a:link,a:active{text-decoration:none;font-size:100%;color:#009}
				a:visited{text-decoration:none;font-size:100%;color:#66C}
				a:hover{font-size:100%;color:#009;text-decoration:underline}
				a.item:link,a.item:visited,a.item:active,a.item:hover,.subhead{font-weight:bold}
				.banbox{width:100%;position:fixed;top:0;left:0;background:#fff;border-bottom:2px solid #ccc;height:60px}
				.mainbox{width:100%;padding-top:70px}
				.itembox{width:100%;float:left}
				.footerbox{clear:both;width:100%;border-top:1px solid #000}
				#item ul{list-style:none;margin:0px;padding:0px;border:none}
				#item li,.fltclear{clear:both}
				.paditembox{padding:0 5px 10px 10px}
				.padbanbox{padding:10px}
				.mvb{margin-bottom:5px}
				.fltl{float:left}
				</style>
			</head>	
			<xsl:apply-templates select="rss/channel"/>
		</html>
	</xsl:template>
	<xsl:template match="channel">
		<body>
			<div class="banbox">
				<div class="padbanbox">			
					<div class="mvb">
						<div class="fltl"><span class="subhead">RSS Feed for: </span></div>
						<a href="{link}" class="item">
							<img height="16" hspace="5" vspace="0" border="0" width="16" alt="RSS feed" src="/images/rss.png" align="left" />
							<xsl:value-of select="$title"/>
						</a>
						<br clear="all" />
					</div>
					<div class="fltclear">Below is the latest content available from this feed.</div>
				</div>
			</div>		
			<div class="mainbox">
				<div class="itembox">
					<div class="paditembox">
						<xsl:apply-templates select="item"/>
					</div>
				</div>
			</div>
		</body>
	</xsl:template>
	<xsl:template match="item">
		<div id="item">
			<ul>
			   <li>
					<div class="mvb">
						<a href="{link}" class="item">
							<xsl:value-of disable-output-escaping="yes" select="title"/>
						</a>
					</div>
					<div>
						<xsl:value-of disable-output-escaping="yes" select="description" />
					</div>
					<div class="fltclear"></div>
					<hr />
				</li>
			</ul>
		</div>
	</xsl:template>
</xsl:stylesheet>