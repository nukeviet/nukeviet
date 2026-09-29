<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom py-3 mb-3">
            <div class="h4 mb-0">{$CONTENT.sitename}</div>
            <a href="{$CONTENT.url}/" title="{$CONTENT.sitename}">{$CONTENT.url}</a>
        </div>
        <h1 class="h3 mb-2">{$CONTENT.title}</h1>
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="text-body-secondary"><i class="fa-regular fa-clock"></i> {$CONTENT.time}</span>
            <div class="d-flex gap-2 ms-auto d-print-none">
                <button type="button" class="btn btn-sm btn-primary" data-toggle="winCMD" data-cmd="print"><i class="fa-solid fa-print"></i> {$LANG->getModule('print')}</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="winCMD" data-cmd="close"><i class="fa-solid fa-power-off"></i> {$LANG->getModule('print_close')}</button>
            </div>
        </div>
        {if $CONTENT.status != 1}
        <div class="alert alert-warning">{$LANG->getModule('no_public')}</div>
        {/if}
        <div class="fw-bold mb-3 clearfix">
            {if not empty($CONTENT.image.width) and $CONTENT.image.position == 1}
            <figure class="figure float-start me-3 mb-2">
                <img src="{$CONTENT.image.src}" alt="{$CONTENT.image.alt}" width="{$CONTENT.image.width}" class="figure-img img-thumbnail img-fluid mb-1">
                {if not empty($CONTENT.image.note)}
                <figcaption class="figure-caption fw-normal fst-italic">{$CONTENT.image.note}</figcaption>
                {/if}
            </figure>
            {/if}
            {$CONTENT.hometext}
        </div>
        {if not empty($CONTENT.image.width) and $CONTENT.image.position == 2}
        <figure class="figure d-block text-center mb-3">
            <img src="{$CONTENT.image.src}" alt="{$CONTENT.image.alt}" width="{$CONTENT.image.width}" class="figure-img img-thumbnail img-fluid mb-1">
            {if not empty($CONTENT.image.note)}
            <figcaption class="figure-caption fst-italic">{$CONTENT.image.note}</figcaption>
            {/if}
        </figure>
        {/if}
        <div class="richtext-container clearfix mb-3">
            {$CONTENT.bodytext}
        </div>
        {if not empty($CONTENT.author) or not empty($CONTENT.source)}
        <div class="text-end mb-3">
            {if not empty($CONTENT.author)}
            <p class="mb-1"><strong>{$LANG->getModule('author')}:</strong> {$CONTENT.author}</p>
            {/if}
            {if not empty($CONTENT.source)}
            <p class="mb-1"><strong>{$LANG->getModule('source')}:</strong> {$CONTENT.source}</p>
            {/if}
        </div>
        {/if}
        {if $CONTENT.copyright == 1}
        <div class="alert alert-info">{$CONTENT.copyvalue}</div>
        {/if}
        <div class="border-top pt-3 pb-3 small">
            <p class="mb-1 text-break"><strong>{$LANG->getModule('print_link')}:</strong> {$CONTENT.link}</p>
            <p class="mb-1">&copy; {$CONTENT.sitename}</p>
            <a href="mailto:{$CONTENT.contact}">{$CONTENT.contact}</a>
        </div>
    </div>
</div>
