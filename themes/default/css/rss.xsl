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
				a:link,a:active,a:visited{text-decoration:none;font-size:100%;color:#205FA0}
				a:hover{color:#102D4C;text-decoration:underline}
				a.itemTitle:link,a.itemTitle:visited,a.itemTitle:active,a.itemTitle:hover,.subhead{font-weight:bold}
				.banbox{width:100%;position:fixed;top:0;left:0;background:#A3CEF1;border-bottom:2px solid #ccc;height:60px}
				.mainbox{width:100%;padding-top:70px}
				.itembox{width:100%;float:left}
				.footerbox{clear:both;width:100%;border-top:1px solid #000}
				.item ul{list-style:none;margin:0px;padding:0px;border:none}
				.item li,.fltclear{clear:both}
                .item li img{margin-right:10px}
				.paditembox{padding:0 5px 10px 10px}
				.mvb{margin-bottom:5px}
				.display-table{display:table;height:60px}
                .display-table > div{display:table-row}
                .display-table > div > a{display:table-cell;font-size:18px;font-weight:bold;color:#205FA0;text-decoration:none}
                .logo{padding:8px 10px;}
                .middle{vertical-align:middle}
				</style>
			</head>	
			<xsl:apply-templates select="rss/channel"/>
		</html>
	</xsl:template>
	<xsl:template match="channel">
        <xsl:variable name="imageUrl" select="image/url"/>
        <xsl:variable name="imageTitle" select="image/title"/>
        <xsl:variable name="imageWidth" select="image/width"/>
        <xsl:variable name="imageHeight" select="image/height"/>
		<body>
			<div class="banbox">
				<div class="display-table">			
					<div class="head">
						<a href="{link}" class="logo">
							<img height="{$imageHeight}" width="{$imageWidth}" alt="{$imageTitle}" src="{$imageUrl}" align="left" />
						</a>
                        <a href="{link}" class="middle"> - <xsl:value-of select="$title"/></a>
					</div>
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
		<div class="item">
			<ul>
			   <li>
					<div class="mvb">
						<a href="{link}" class="itemTitle">
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