<div class="card">
    <div class="card-body">
        <h2 class="h5 border-bottom pb-3 mb-0"><i class="fa-solid fa-filter"></i> {$LANG->getModule('search_on')} {$LANG->getModule('search_modul_title')}</h2>
        {if $NUMRECORD eq 0}
        <p class="fst-italic mt-3">{$LANG->getModule('search_none')}: <span class="badge text-bg-info text-wrap">{$KEY}</span> {$LANG->getModule('search_in_module')} <strong>{$LANG->getModule('search_modul_title')}</strong></p>
        {/if}
        {foreach from=$ARRAY item=row}
        <article class="row gx-3 py-3 mx-0 border-bottom">
            {if not empty($row.homeimgfile)}
            <div class="col-4 col-md-3 ps-0">
                <a href="{$row.link}"{if $row.external_link} target="_blank"{/if} class="ratio d-block rounded overflow-hidden" style="--bs-aspect-ratio: {$IMGRATIO}%;">
                    <img src="{$row.homeimgfile}" alt="{$row.title_plain}" class="object-fit-cover">
                </a>
            </div>
            {/if}
            <div class="{if not empty($row.homeimgfile)}col-8 col-md-9 pe-0{else}col-12 px-0{/if}">
                <h3 class="fs-5 fw-medium mb-2"><a class="link-body-emphasis" href="{$row.link}"{if $row.external_link} target="_blank"{/if}>{$row.title}</a></h3>
                <div class="text-break mb-2 d-none d-sm-block">{$row.content}</div>
                <ul class="list-inline text-muted small mb-0">
                    <li class="list-inline-item"><i class="fa-regular fa-clock"></i> {$row.publtime|ddatetime}</li>
                    {if not empty($row.authors_internal) or not empty($row.author)}
                    <li class="list-inline-item">
                        <i class="fa-regular fa-user"></i> <span class="visually-hidden">{$LANG->getModule('author')}:</span>
                        {foreach from=$row.authors_internal item=author}<a href="{$author.href}">{$author.name}</a>{if not $author@last or not empty($row.author)}, {/if}{/foreach}{$row.author}
                    </li>
                    {/if}
                    {if not empty($row.source)}
                    <li class="list-inline-item">{$LANG->getModule('source_title')}: {$row.source}</li>
                    {/if}
                </ul>
            </div>
        </article>
        {/foreach}
        {if not empty($GENERATE_PAGE)}
        <div class="d-flex justify-content-center mt-4">
            {$GENERATE_PAGE}
        </div>
        {/if}
        <div class="alert alert-info fst-italic mt-4">
            {$LANG->getModule('search_sum_title')} <strong>{$NUMRECORD|dnumber}</strong> {$LANG->getModule('result_title')}<br>
            {$LANG->getModule('info_adv')}
        </div>
        <h3 class="h6 fw-bold">{$LANG->getModule('search_adv_internet')}:</h3>
        <form method="get" action="https://www.google.com/search">
            <input type="hidden" name="domains" value="{$smarty.const.NV_MY_DOMAIN}">
            <div class="input-group mb-2">
                <span class="input-group-text"><i class="fa-brands fa-google"></i></span>
                <input type="text" class="form-control" name="q" value="{$KEY}" maxlength="255" aria-label="{$LANG->getModule('search_adv_internet')}">
                <button type="submit" name="sa" class="btn btn-secondary">{$LANG->getModule('search_title')}</button>
            </div>
            <div class="d-flex flex-wrap column-gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sitesearch" value="" id="newsSearchGoogleAll" checked>
                    <label class="form-check-label" for="newsSearchGoogleAll">{$LANG->getModule('search_on_internet')}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sitesearch" value="{$smarty.const.NV_MY_DOMAIN}" id="newsSearchGoogleSite">
                    <label class="form-check-label" for="newsSearchGoogleSite">{$LANG->getModule('search_on_nuke')} {$smarty.const.NV_MY_DOMAIN}</label>
                </div>
            </div>
        </form>
    </div>
</div>
