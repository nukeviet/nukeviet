<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="2.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:atom="http://www.w3.org/2005/Atom">
    <xsl:output method="html" encoding="utf-8"/>
    <xsl:template match="/">
        <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <title>
                    <xsl:value-of select="atom:feed/atom:title"/>
                </title>
                <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/css/feed.css" />
            </head>
            <body>
                <xsl:apply-templates select="/atom:feed"/>
                <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
                <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/moment/moment.min.js"></script>
                <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/feed.js"></script>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="/atom:feed">
        <div class="banbox">
            <div class="container">
                <a href="{atom:link[@rel='alternate']/@href}" class="tl">
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="shape-rendering:geometricPrecision;text-rendering:geometricPrecision;image-rendering:optimizeQuality;fill-rule:evenodd;clip-rule:evenodd" viewBox="0 0 5000.01 4530.17" width="30" height="30">
                        <path d="M2392.01 738.85c-74.72-58.1-148.1-109.3-219.32-152.2-356.1 322.74-645.48 721.16-873.93 1142.4 30.42 116.92 67.82 235.93 112.76 355.66 136.98-295.06 307.4-574.78 505.81-832.45 141.33-185.5 299.6-359.17 474.68-513.41zm1480.8 2192.05c86.48-34.58 167.47-72.67 242.84-114.16-25.63-116.27-58.8-235.93-98.95-357.77-49.8 119.73-106.56 238.74-169.52 356.36 10.35 39.43 18.66 77.46 25.63 115.57zM1127.07 1594.05c-86.48 34.58-167.47 72.67-242.84 114.16C987.2 2175.86 1183 2632.19 1438.5 3036.74c115.57 31.84 236.63 58.8 362.56 78.88-148.87-213.3-279.47-439.52-389.53-675.33-109.69-232.74-203.14-479.08-258.82-730.69-10.36-39.42-18.67-77.45-25.64-115.55zm2720.11 115.57c-111.1 502.68-355.34 985.92-648.35 1406 126.63-21.47 248.39-48.45 364.67-80.93 248.59-409.34 446.94-853.47 551.51-1322.27-73.32-40.85-154.31-79.58-242.9-115.57-6.91 36.64-14.52 74.74-24.93 112.77zM3619.56 322.98c184.73 106.56 292.69 363.96 304.46 714.77 91.34 31.12 179.24 65.71 262.91 103.11 7.09-353.74-65.27-770.95-366.71-997.23-357.26-268.22-852.32-117.36-1195 99.08 74.02 51.9 147.41 110.01 220.02 173.67 200.69-107.91 528.66-235.24 774.32-93.4zm-1509.83 908.58c127.97-11.82 258.11-17.33 390.24-17.33 134.93 0 265.02 5.56 390.94 16.62-83.03-101-167.47-192.34-251.85-275.37-91.99-2.81-184.1-2.81-276.07 0-86.49 84.44-170.92 176.41-253.26 276.08zm-1033.79-191.7c7.8-247.51 63.16-566.15 291.99-709.34 259.9-162.61 613.07-14.96 848.88 122.21 616.59 358.79 1074.77 995.18 1371.62 1631.26 44.99-119.73 82.34-238.04 112.76-354.95-198.28-365.56-441.89-710.42-733.49-1007.72-281.32-286.75-620.36-552.02-1009.95-669.7-311.49-94.1-660.43-63.28-886.33 192.09-213.37 241.18-265.02 589.1-258.37 899.88 83.72-37.33 171.55-72.61 262.89-103.73zm1751.31 2898.49c-35.28 33.24 88.59-80.22 0 0zm-435.24-152.2c-29.09-25.63-58.8-52.61-88.59-80.29-134.23-4.86-265.02-15.22-391.64-31.12 85.08 95.49 172.26 184.02 260.87 263.61 73.37-43.6 146.75-94.79 219.36-152.2zm1532-301c-7.6 240.54-58.94 540.71-267.77 692.97-238.74 174-578.81 52.86-810.32-70.89-72.67 62.96-146.69 121.77-220.71 175.08 347.61 210.75 832.63 369.91 1194.11 99.84 303.18-226.47 375.15-645.93 367.61-1000.75-83.76 37.34-171.57 71.91-262.92 103.75zm-835.9 188.89c-128.68 15.92-259.45 26.98-392.35 31.12-160.38 156.61-343.58 297.05-541.09 403.4-177.96 95.76-388.06 177.06-593.63 152.14-428.46-51.84-500.88-573.5-486.5-919.5v-3.45c6.97-175.46 32.8-353.93 77.47-523.77-62.26-117.62-119.02-235.93-168.82-354.95C870.94 2799.6 798.84 3162.1 814.5 3522.04c12.02 276.78 80.09 575.54 277.54 781.56 503.64 525.36 1321.38 24.29 1735.21-365.25 88.59-80.29 175.78-168.82 260.86-264.31zm1226.88-2312.26c47.67 26.28 375.99 206.86-2.78-1.56-3.38-1.85-3.58-1.95-1.3-.69-274.26-150.14-576.71-249.28-882.29-312.08 78.18 102.4 152.9 210.37 223.47 323.83 355.72 101.64 763.53 280.04 982.54 593.06 85.08 121.64 128.68 266.3 90.19 413.05-41.1 156.99-156.48 284.84-279.21 385.37-287.52 235.61-658.32 369.59-1017.37 448.86-414.91 91.6-844.33 118.25-1267.87 86.68-311.94-23.27-630.13-75.81-925.96-179.88-12.46 88.08-22.12 177.38-22.12 266.42 506.76 159.74 1049.2 210.18 1578.28 177.77 519.23-31.84 1064.41-141.01 1521.65-399.18 336.67-168.3 687.79-497.69 687.79-900.9-.02-412.23-352.75-717.25-685.02-900.75zm-4014.86 713.6c134.05-312.51 502.23-504.53 802.15-619.71 79.71-32.86 162.49-61.05 245.65-83.73 69.86-112.76 145.3-221.43 224.17-324.52-305.16 62.71-612.11 160.5-884.28 314.11-13.87 6.9-26.98 13.87-40.14 22.12l-.7-.01C324.12 1569.85-9.87 1875.83.23 2279.88c10.1 405.65 359.11 704.09 687.6 884.86 8.31-91.34 22.11-184.73 40.14-278.82-270.02-166.54-576.2-464.8-427.84-810.54z" style="fill:#0694ba;fill-rule:nonzero"/>
                        <path d="M2277.34 2860.13c70.25 26.72 145.36 40.34 220.59 40.34 316.53 0 589.73-248.27 618.76-563.72 29.01-315.57-192.85-610.9-504.98-668.61-312-57.72-626.36 136.98-712.08 442.78-85.78 305.86 80.73 636.39 377.71 749.21z" style="fill:#fd7e14;fill-rule:nonzero"/>
                    </svg>
                    <span><xsl:value-of select="atom:title"/></span>
                </a>
                <a href="{atom:link[@rel='alternate']/@href}" title="{atom:title}" class="logo">
                    <img alt="{atom:title}" src="{atom:logo}" />
                </a>
            </div>
        </div>
        <div class="mainbox">
            <ul class="itembox">
                <xsl:apply-templates select="atom:entry"/>
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="atom:entry">
        <xsl:variable name="item_title" select="atom:title" />
        <xsl:variable name="item_pubdate" select="atom:updated" />
        <xsl:variable name="item_desc" select="substring-after(atom:summary, '&gt;')" />
        <xsl:variable name="item_image" select="substring-before(substring-after(atom:summary, 'src=&quot;'), '&quot;')" />
        <li class="item">
            <div class="item-title">
                <a href="{atom:link/@href}">
                    <xsl:value-of disable-output-escaping="yes" select="$item_title" />
                </a>
            </div>
            <div class="item-pubdate">
                <span class="icon"></span>
                <span class="pubdate" data-toggle="pubdate" data-pubdate="{$item_pubdate}"></span>
            </div>
            <div class="item-contents">
                <xsl:if test="$item_image!=''">
                    <a href="{atom:link/@href}" title="{$item_title}">
                        <img src="{$item_image}" alt="{$item_title}" />
                    </a>
                    <xsl:value-of disable-output-escaping="yes" select="$item_desc" />
                </xsl:if>
                <xsl:if test="$item_image=''">
                    <xsl:value-of disable-output-escaping="yes" select="atom:summary" />
                </xsl:if>
            </div>
            <a href="{atom:link/@href}" class="item-more"><span class="icon"></span> {MORE_LANG}</a>
        </li>
    </xsl:template>
</xsl:stylesheet>