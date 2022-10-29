<!-- BEGIN: main -->
<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/xsl" href="{CSSPATH}" media="screen"?>
<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    <!-- BEGIN: atom -->xmlns:atom="http://www.w3.org/2005/Atom"<!-- END: atom -->
>
    <channel>
        <title>{CHANNEL.title}</title>
        <link>{CHANNEL.link}</link>
        <!-- BEGIN: atom_link --><atom:link href="{CHANNEL.atomlink}" rel="self" /><!-- END: atom_link -->
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
            <!-- BEGIN: pubdate -->
            <pubDate>{ITEM.pubdate}</pubDate>
            <!-- END: pubdate -->
            <!-- BEGIN: author -->
            <author>
                <![CDATA[{ITEM.author}]]>
            </author>
            <!-- END: author -->
            <description>
                <![CDATA[{ITEM.description}]]>
            </description>
            <!-- BEGIN: content -->
            <content:encoded>
                <![CDATA[
                <!doctype html>
                <html lang="{SITELANG}" prefix="op: http://media.facebook.com/op#">
                    <head>
                        <meta charset="{CHARSET}">
                        <link rel="canonical" href="{ITEM.link}">
                        <meta property="op:markup_version" content="v1.0">
                        <meta property="fb:article_style" content="{ITEM.content.template}">
                    </head>
                    <body>
                        <article>
                            <header>
                                <!-- BEGIN: image -->
                                <figure>
                                    <img src="{ITEM.content.image}" />
                                    <figcaption>{ITEM.content.image_caption}</figcaption>
                                </figure>
                                <!-- END: image -->
                                <h1>{ITEM.title}</h1>
                                <h2>{ITEM.description}</h2>
                                <!-- BEGIN: opkicker -->
                                <h3 class="op-kicker">
                                    {ITEM.content.opkicker}
                                </h3>
                                <!-- END: opkicker -->
                                <!-- BEGIN: pubdate -->
                                <time class="op-published" datetime="{PUBLISHED}">{PUBLISHED_DISPLAY}</time>
                                <!-- END: pubdate -->
                                <!-- BEGIN: modifydate -->
                                <time class="op-modified" dateTime="{MODIFIED}">{MODIFIED_DISPLAY}</time>
                                <!-- END: modifydate -->
                            </header>
                            {ITEM.content.html}
                        </article>
                    </body>
                </html>
                ]]>
            </content:encoded>
            <!-- END: content -->
        </item>
        <!-- END: item -->
    </channel>
</rss>
<!-- END: main -->