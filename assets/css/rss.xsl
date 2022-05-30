<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="2.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns="http://www.w3.org/1999/xhtml">
    <xsl:output method="html" encoding="utf-8"/>
    <xsl:variable name="title" select="/rss/channel/title"/>
    <xsl:variable name="link" select="rss/channel/link"/>
    <xsl:template match="/">
        <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <title>
                    <xsl:value-of select="$title"/>
                </title>
                <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/css/feed.css" />
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
                <div class="container">
                    <a href="{link}" class="tl">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-28.364 -29.444 42.324 42.822" width="24" height="24">
                            <path fill="#F60" d="M-17.392 7.875c0 3.025-2.46 5.485-5.486 5.485s-5.486-2.46-5.486-5.485c0-3.026 2.46-5.486 5.486-5.486s5.486 2.461 5.486 5.486zm31.351 5.486C14.042.744 8.208-11.757-1.567-19.736c-7.447-6.217-17.089-9.741-26.797-9.708v9.792C-16.877-19.785-5.556-13.535.344-3.66a32.782 32.782 0 0 1 4.788 17.004h8.827v.017zm-14.96 0C-.952 5.249-4.808-2.73-11.108-7.817c-4.821-3.956-11.021-6.184-17.255-6.15v8.245c6.782-.083 13.432 3.807 16.673 9.774a19.296 19.296 0 0 1 2.411 9.326h8.278v-.017z"/>
                        </svg>
                        <span><xsl:value-of select="$title"/></span>
                    </a>
                    <a href="{link}" title="{$imageTitle}" class="logo">
                        <img alt="{$imageTitle}" src="{$imageUrl}" />
                    </a>
                </div>
            </div>
            <div class="mainbox">
                <ul class="itembox">
                    <xsl:apply-templates select="item"/>
                </ul>
            </div>
            <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
            <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/moment/moment.min.js"></script>
            <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/feed.js"></script>
        </body>
    </xsl:template>
    <xsl:template match="item">
        <xsl:variable name="item_title" select="title" />
        <xsl:variable name="item_pubdate" select="pubDate" />
        <xsl:variable name="item_desc" select="substring-after(description, '&gt;')" />
        <xsl:variable name="item_image" select="substring-before(substring-after(description, 'src=&quot;'), '&quot;')" />
        <li class="item">
            <div class="item-title">
                <a href="{link}">
                    <xsl:value-of disable-output-escaping="yes" select="$item_title" />
                </a>
            </div>
            <div class="item-pubdate">
                <span class="icon"></span>
                <span class="pubdate" data-toggle="pubdate" data-pubdate="{$item_pubdate}"></span>
            </div>
            <div class="item-contents">
                <xsl:if test="$item_image!=''">
                    <a href="{link}" title="{$item_title}">
                        <img src="{$item_image}" alt="{$item_title}" />
                    </a>
                    <xsl:value-of disable-output-escaping="yes" select="$item_desc" />
                </xsl:if>
                <xsl:if test="$item_image=''">
                    <xsl:value-of disable-output-escaping="yes" select="description" />
                </xsl:if>
            </div>
            <a href="{link}" class="item-more"><span class="icon"></span> {MORE_LANG}</a>
        </li>
    </xsl:template>
</xsl:stylesheet>