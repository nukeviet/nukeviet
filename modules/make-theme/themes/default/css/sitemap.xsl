<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" xmlns:html="http://www.w3.org/TR/REC-html40" xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>XML Sitemap for</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<style type="text/css">
				a{color:#000;display:block}
				body,td,th{font:13px Tahoma,Arial,sans-serif}
				h1{color:#FF4500;font:bold 16px Tahoma,Arial,sans-serif}
				h2{font:bold 13px Tahoma,Arial,sans-serif}
				h3{font:bold 10px Tahoma,Arial,sans-serif}
				th{background:#D3D3D3;font-weight:800;padding-right:30px;text-align:left}
				tr.high{background:whitesmoke}
				tr:hover{background:#FAF0E6}
				</style>
			</head>
			<body>
				<h1>XML Sitemap</h1>
				<h2 id="pageLoc"></h2>
				<h3>Number of sitemaps in this XML sitemap: <xsl:value-of select="count(sitemap:urlset/sitemap:url)"></xsl:value-of></h3>
				<table cellpadding="5">
					<tr style="border-bottom:1px black solid;">
						<th>URL</th>
						<th>Priority</th>
						<th>Change Frequency</th>
						<th>Last Change</th>
					</tr>
					<xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
					<xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>
					<xsl:for-each select="sitemap:urlset/sitemap:url">
						<tr>
							<xsl:if test="position() mod 2 != 1">
								<xsl:attribute  name="class">high</xsl:attribute>
							</xsl:if>
							<td>
								<xsl:variable name="itemURL">
									<xsl:value-of select="sitemap:loc"/>
								</xsl:variable>
								<a href="{$itemURL}">
									<xsl:value-of select="sitemap:loc"/>
								</a>
							</td>
							<td>
								<xsl:value-of select="concat(sitemap:priority*100,'%')"/>
							</td>
							<td>
								<xsl:value-of select="concat(translate(substring(sitemap:changefreq, 1, 1),concat($lower, $upper),concat($upper, $lower)),substring(sitemap:changefreq, 2))"/>
							</td>
							<td>
								<xsl:value-of select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
							</td>
						</tr>
					</xsl:for-each>
				</table>
				<script language="JavaScript">
				<![CDATA[
					var theURL = document.getElementById("pageLoc");
					theURL.innerHTML += ' ' + location;
					document.title += ': ' + location;
				]]>
				</script>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>