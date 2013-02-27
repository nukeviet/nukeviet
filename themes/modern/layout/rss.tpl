<!-- BEGIN: main -->
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{CHANNEL.title}</title>
		<link>{CHANNEL.link}</link>
		<atom:link href="{CHANNEL.atomlink}" rel="self" type="application/rss+xml" />
		<description>{CHANNEL.description}</description>
		<language>{CHANNEL.lang}</language>
		<copyright>{CHANNEL.copyright}</copyright>
		<docs>{CHANNEL.docs}</docs>
		<generator>{CHANNEL.generator}</generator>
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
			<guid isPermaLink="false">{ITEM.guid}</guid>
			<description>{ITEM.description}</description>
			<pubDate>{ITEM.pubdate}</pubDate>
		</item>
		<!-- END: item -->
	</channel>
</rss>
<!-- END: main -->