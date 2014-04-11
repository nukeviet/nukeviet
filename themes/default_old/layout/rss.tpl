<!-- BEGIN: main -->
<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/xsl" href="{CSSPATH}" media="screen"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{CHANNEL.title}</title>
		<link>{CHANNEL.link}</link>
		<atom:link href="{CHANNEL.atomlink}" rel="self" type="application/rss+xml" />
		<!-- BEGIN: description -->
		<description>
			<![CDATA[{CHANNEL.description}]]>
		</description>
		<!-- END: description -->
		<language>{CHANNEL.lang}</language>
		<!-- BEGIN: pubDate -->
		<pubDate>{CHANNEL.pubDate}</pubDate>
		<lastBuildDate>{CHANNEL.pubDate}</lastBuildDate>
		<!-- END: pubDate -->
		<copyright>
			<![CDATA[{CHANNEL.copyright}]]>
		</copyright>
		<docs>{CHANNEL.docs}</docs>
		<generator>
			<![CDATA[{CHANNEL.generator}]]>
		</generator>
		<!-- BEGIN: image -->
		<image>
			<url>{IMAGE.src}</url>
			<title>{IMAGE.title}</title>
			<link>{IMAGE.link}</link>
			<width>{IMAGE.width}</width>
			<height>{IMAGE.height}</height>
		</image>
		<!-- END: image -->
		<!-- BEGIN: item -->
		<item>
			<title>{ITEM.title}</title>
			<link>{ITEM.link}</link>
			<!-- BEGIN: guid -->
			<guid isPermaLink="false">
				<![CDATA[{ITEM.guid}]]>
			</guid>
			<!-- END: guid -->
			<description>
				<![CDATA[{ITEM.description}]]>
			</description>
			<!-- BEGIN: pubdate -->
			<pubDate>{ITEM.pubdate}</pubDate>
			<!-- END: pubdate -->
		</item>
		<!-- END: item -->
	</channel>
</rss>
<!-- END: main -->