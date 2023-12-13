{foreach $BANNERS as $data}
<div class="nv-block-banners">
{if $data.file_name != 'no_image'}
{if (!empty($data.file_click))}
    <a rel="nofollow" href="{$data.link}" data-target="{$data.target}" title="{$data.file_alt}"><img alt="{$data.file_alt}" src="{$data.file_image}" width="{$data.file_width}" height="{$data.file_height}"></a>
{else}
    <img alt="{$data.file_alt}" src="{$data.file_image}" width="{$data.file_width}" height="{$data.file_height}">
{/if}
{/if}
{if !empty($data.bannerhtml)}
    <div class="clearfix text-left">
        {$data.bannerhtml}
    </div>
{/if}
</div>
{/foreach}
