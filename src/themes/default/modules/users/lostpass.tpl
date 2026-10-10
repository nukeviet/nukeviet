<div class="d-flex justify-content-center">
    <div class="rounded-4 border shadow-lg p-4">
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-unlock-keyhole"></i>
            </div>
        </div>
        <h1 class="h2 text-center mb-3">{$LANG->getModule('lostpass_page_title')}</h1>
        {include file='lostpass_form.tpl'}
        {if not empty($NAVS)}
        <div class="fw-300 mx-auto mt-4 text-center" data-area="other-form">
            <ul class="list-inline mb-0">
                {foreach from=$NAVS item=nav}
                <li class="list-inline-item text-nowrap">
                    <a href="{$nav.href}">
                        <i class="fa-solid fa-caret-right"></i>&nbsp;{$nav.title}
                    </a>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
    </div>
</div>
