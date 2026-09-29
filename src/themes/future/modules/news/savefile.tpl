<!DOCTYPE html>
<html lang="{$LANG->getGlobal('Content_Language')}" dir="{if $IS_RTL}rtl{else}ltr{/if}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$CONTENT.title} - {$CONTENT.sitename}</title>
    <link rel="canonical" href="{$CONTENT.link}">
    {if not empty($INLINE_CSS)}
    <style>
{$INLINE_CSS}
    </style>
    {/if}
</head>
<body>
    <article class="news-print">
        <header class="news-print-header">
            <div class="news-print-sitename">{$CONTENT.sitename}</div>
            <a href="{$CONTENT.url}/" title="{$CONTENT.sitename}">{$CONTENT.url}</a>
        </header>
        <h1 class="news-print-title">{$CONTENT.title}</h1>
        <div class="news-print-time">{$CONTENT.time}</div>
        {if $CONTENT.status != 1}
        <div class="news-print-alert">{$LANG->getModule('no_public')}</div>
        {/if}
        <div class="news-print-hometext">
            {if not empty($CONTENT.image.src) and $CONTENT.image.position == 1}
            <figure class="news-print-image news-print-image-start">
                <img src="{$CONTENT.image.src}" alt="{$CONTENT.image.alt}" width="{$CONTENT.image.width}">
                {if not empty($CONTENT.image.note)}
                <figcaption>{$CONTENT.image.note}</figcaption>
                {/if}
            </figure>
            {/if}
            {$CONTENT.hometext}
        </div>
        {if not empty($CONTENT.image.src) and $CONTENT.image.position == 2}
        <figure class="news-print-image">
            <img src="{$CONTENT.image.src}" alt="{$CONTENT.image.alt}" width="{$CONTENT.image.width}">
            {if not empty($CONTENT.image.note)}
            <figcaption>{$CONTENT.image.note}</figcaption>
            {/if}
        </figure>
        {/if}
        <div class="news-print-bodytext">
            {$CONTENT.bodytext}
        </div>
        {if not empty($CONTENT.author) or not empty($CONTENT.source)}
        <div class="news-print-author">
            {if not empty($CONTENT.author)}
            <p><strong>{$LANG->getModule('author')}:</strong> {$CONTENT.author}</p>
            {/if}
            {if not empty($CONTENT.source)}
            <p><strong>{$LANG->getModule('source')}:</strong> {$CONTENT.source}</p>
            {/if}
        </div>
        {/if}
        {if $CONTENT.copyright == 1}
        <div class="news-print-copyright">{$CONTENT.copyvalue}</div>
        {/if}
        <footer class="news-print-footer">
            <p><strong>{$LANG->getModule('print_link')}:</strong> <a href="{$CONTENT.link}">{$CONTENT.link}</a></p>
            <p>&copy; {$CONTENT.sitename}</p>
            <a href="mailto:{$CONTENT.contact}">{$CONTENT.contact}</a>
        </footer>
    </article>
</body>
</html>
