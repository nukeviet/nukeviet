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
                        <xsl:value-of select="$title"/>
                    </a>
                    <a href="{link}" title="{$imageTitle}" class="logo">
                        <img alt="{$imageTitle}" src="{$imageUrl}" />
                    </a>
                </div>
            </div>
            <div class="mainbox">
                <div class="itembox">
                    <div class="paditembox">
                        <xsl:apply-templates select="item"/>
                    </div>
                </div>
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
        <div class="item">
            <div class="item-title">
                <a href="{link}">
                    <xsl:value-of disable-output-escaping="yes" select="$item_title" />
                </a>
            </div>
            <div class="item-pubdate">
                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="shape-rendering:geometricPrecision;text-rendering:geometricPrecision;image-rendering:optimizeQuality;fill-rule:evenodd;clip-rule:evenodd" viewBox="0 0 12181.73 11983.31" width="14" height="14">
                    <path d="m28.62 3961.42 12144.88.98c16.69-527.88 2.81-1077.29 2.83-1607.59.01-630.86 40.69-867.08-285.67-1302.51-228.14-304.39-680.05-392.8-1194.79-391.88-538.77.95-1079.03 3.68-1617.68-.24l-4.92 1189.84c702.43 328.24 419.58 1602.52-487.86 1611.66-1031.18 10.39-1284.62-1174.12-600.35-1624.14l2.96-1178.59-3826.24 1.53-.36 961.46 4.72 219.51c634.03 187.72 581.19 1618.81-591.95 1614.75C2484.68 3413.13 2462.09 2195 3071 1836.93l1.39-1189.17-1306.16-14.54c-652.95-10.76-1343.73 150.71-1579.76 587.4-121.99 208.91-160.73 480.91-170.12 768.23-20.88 639.7 9.22 1327.52 12.27 1972.57z" style="fill:#fd7e14"/>
                    <path d="M8764.54 8493.31h1152.24c178.2 0 324 145.8 324 324v780.75c0 178.2-145.8 324-324 324H8764.54c-178.2 0-324-145.8-324-324v-780.75c0-178.2 145.8-324 324-324zm-52.91-2672.29h1152.24c178.2 0 324 145.8 324 324v780.75c0 178.2-145.8 324-324 324H8711.63c-178.2 0-324-145.8-324-324v-780.75c0-178.2 145.8-324 324-324zm-3326.34 0h1152.24c178.2 0 324 145.8 324 324v780.75c0 178.2-145.8 324-324 324H5385.29c-178.2 0-324-145.8-324-324v-780.75c0-178.2 145.8-324 324-324zm52.91 2672.29h1152.24c178.2 0 324 145.8 324 324v780.75c0 178.2-145.8 324-324 324H5438.2c-178.2 0-324-145.8-324-324v-780.75c0-178.2 145.8-324 324-324zm-3304.12 0h1152.24c178.2 0 324 145.8 324 324v780.75c0 178.2-145.8 324-324 324H2134.08c-178.2 0-324-145.8-324-324v-780.75c0-178.2 145.8-324 324-324zm-52.91-2672.29h1152.24c178.2 0 324 145.8 324 324v780.75c0 178.2-145.8 324-324 324H2081.17c-178.2 0-324-145.8-324-324v-780.75c0-178.2 145.8-324 324-324zM7988.02 658.95l-2.96 1178.59c14 455.59-93.96 871.44 220.36 1069.12 359.35 226.01 787.81 44.15 864.68-288.38l9.08-2197.35c-55.17-291.16-288.6-472.82-655.95-407.71-347.87 61.66-434.89 275.56-435.21 645.73zm-4915.63-11.19L3071 1836.93c-10.55 556.24-101.88 1159.81 539.14 1167.95 253.25 3.21 477.43-152.44 531.45-328.45 69.3-225.79 21.43-1680.78 20.19-2015.95-.32-408.39-110.21-663.22-551.54-657.61-403.9 5.14-553.24 227.43-537.85 644.89zM119.38 11286.78c160.74 374.19 524.36 676.03 1057.54 685.94 571.81 10.62 1154.87-.05 1727.95-.05h6909.66c422.96 0 1327.37 54.16 1661.01-82.02 358.49-146.32 680.39-513.29 699.01-1026.92l-1.05-6901.33-346.49-.98-33.26 6816.89c-4.05 363.87-248.4 634.26-504.46 748.58-294.23 131.37-801.57 86.35-1157.47 86.35H2693.35c-822.38 0-1725.31 162.09-2131.43-382.95-240.32-353.87-201.57-1146.07-185.61-1714.87l13.57-5548.8-361.26-5.2L.3 9489.06c-4.72 923.04 48.01 1632.28 119.08 1797.72z" style="fill:#6c757d"/>
                </svg>
                <div class="pubdate" data-toggle="pubdate" data-pubdate="{$item_pubdate}"></div>
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
        </div>
    </xsl:template>
</xsl:stylesheet>