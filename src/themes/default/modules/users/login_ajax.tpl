{include file='login_form.tpl'}
{if not empty($NAVS)}
<div class="d-flex justify-content-center mt-3" data-area="other-form">
    <ul class="list-inline mb-0">
        {foreach from=$NAVS item=nav}
        <li class="list-inline-item">
            <a href="{$nav.href}">
                <i class="fa-solid fa-caret-right"></i>&nbsp;{$nav.title}
            </a>
        </li>
        {/foreach}
    </ul>
</div>
{/if}
