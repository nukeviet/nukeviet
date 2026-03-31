<div class="vstack gap-2 nv-block-banners">
    {foreach from=$DATA item=banner}
    <div class="item text-center">
        {if $banner.file_name neq 'no_image'}
        {if not empty($banner.file_click)}
        <a rel="nofollow" href="{$banner.link}" data-target="{$banner.target}" title="{$banner.file_alt}">
            <img alt="{$banner.file_alt}" src="{$banner.file_image}" width="{$banner.file_width}" class="img-fluid">
        </a>
        {else}
        <img alt="{$banner.file_alt}" src="{$banner.file_image}" width="{$banner.file_width}" class="img-fluid">
        {/if}
        {/if}
        {if not empty($banner.bannerhtml)}
        <div class="bannerhtml">
            {$banner.bannerhtml}
        </div>
        {/if}
    </div>
    {/foreach}
</div>
