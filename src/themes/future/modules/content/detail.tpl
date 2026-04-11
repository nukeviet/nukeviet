<h1 class="mb-3">
    {$CONTENT.title}
    {if $smarty.const.NV_IS_MODADMIN}
    <span class="dropdown fs-6">
        <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-screwdriver-wrench"></i> <span class="visually-hidden">{$LANG->getModule('admtools')}</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{$CONTENT.adminlink}"><i class="fa-solid fa-pencil fa-fw text-center"></i> {$LANG->getGlobal('edit')}</a></li>
            <li><a class="dropdown-item" href="#" data-toggle="nv_del_content" data-id="{$CONTENT.id}" data-checkss="{$CONTENT.checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}" data-detail="true"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
        </ul>
    </span>
    {/if}
</h1>
{if not empty($SOCIAL_CONFIG)}
<div class="mb-3 d-flex flex-wrap gap-3">
    {if $SOCIAL_CONFIG|strpos:'facebook' !== false}
    <div class="fb-like" data-href="{$CONTENT.link}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
    {/if}
    {if $SOCIAL_CONFIG|strpos:'twitter' !== false}
    <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
    {/if}
    {if $SOCIAL_CONFIG|strpos:'zalo' !== false and not empty($GLANG.zaloOfficialAccountID)}
    <div class="zalo-share-button" data-href="{$CONTENT.link}" data-oaid="{$GLANG.zaloOfficialAccountID}" data-layout="1" data-color="blue" data-customize=false></div>
    {/if}
</div>
{/if}
{if not empty($CONTENT.is_inactive)}
{* Thông báo khi admin xem bài đang đình chỉ *}
<div class="alert alert-warning" role="alert"><i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('warning')}</div>
{/if}
{if not empty($CONTENT.description)}
{* Ảnh minh họa dạng bên trái mô tả, chỉ có nếu có mô tả ngắn gọn *}
{if not empty($CONTENT.image) and $CONTENT.imageposition eq 1}
<figure class="float-start me-3 mb-2 align-baseline">
    <div style="width: {$CONTENT.thumb.width}px;">
        <img role="button" data-bs-toggle="modal" data-bs-target="#imgpreview" alt="{$CONTENT.imagealt ?: $CONTENT.title}" src="{$CONTENT.thumb.src}" class="img-fluid">
    </div>
</figure>
<div class="modal fade" id="imgpreview" tabindex="-1" aria-labelledby="imgpreviewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="imgpreviewLabel">{$LANG->getModule('image')}: {$CONTENT.imagealt ?: $CONTENT.title}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body text-center">
                <img alt="{$CONTENT.imagealt ?: $CONTENT.title}" src="{$CONTENT.img.src}" srcset="{$CONTENT.img.srcset}" class="img-fluid">
            </div>
        </div>
    </div>
</div>
{/if}
{* Giới thiệu ngắn gọn của bài đăng *}
<div class="article-intro mb-3 fw-medium">
    {$CONTENT.description}
</div>
{/if}
{* Ảnh minh họa dạng lớn *}
{if not empty($CONTENT.image) and $CONTENT.imageposition eq 2}
<figure class="image">
    <img alt="{$CONTENT.imagealt ?: $CONTENT.title}" src="{$CONTENT.img.src}" srcset="{$CONTENT.img.srcset}" width="{$CONTENT.img.width}">
    {if not empty($CONTENT.imagealt)}
    <figcaption>{$CONTENT.imagealt}</figcaption>
    {/if}
</figure>
{/if}
{* Nội dung chi tiết của bài đăng *}
<div class="clearfix"></div>
<div class="article-body">
    {$CONTENT.bodytext}
</div>
{if not empty($CONTENT_COMMENT)}
{* Phần bình luận *}
{$CONTENT_COMMENT}
{/if}
{if not empty($OTHER_LINKS)}
{* Danh sách các bài khác *}
<hr class="my-4">
<div class="h3 fs-medium border-start border-3 border-primary mb-3 ps-2">{$LANG->getModule('related')}</div>
<ul class="list-unstyled vstack gap-2">
    {foreach from=$OTHER_LINKS item=other}
    <li>
        <i class="fa-solid fa-caret-right me-1"></i> <a href="{$other.link}" title="{$other.title}" class="link-body-emphasis">{$other.title}</a>
    </li>
    {/foreach}
</ul>
{/if}
