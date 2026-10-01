<h1 class="mb-3">
    {$DATA.title}
    {if $smarty.const.NV_IS_MODADMIN}
    <span class="dropdown fs-6">
        <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-screwdriver-wrench"></i> <span class="visually-hidden">{$LANG->getModule('admtools')}</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{$DATA.admin_edit}"><i class="fa-solid fa-pencil fa-fw text-center"></i> {$LANG->getGlobal('edit')}</a></li>
            <li><a class="dropdown-item" href="#" data-toggle="nv_del_content" data-id="{$DATA.id}" data-checkss="{$DATA.admin_checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}" data-detail="true"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
        </ul>
    </span>
    {/if}
</h1>
{if $SOCIALS.facebook or $SOCIALS.twitter}
<div class="mb-3 d-flex flex-wrap align-items-center gap-2">
    <span class="small text-body-secondary"><i class="fa-solid fa-share-nodes"></i> {$LANG->getGlobal('share')}:</span>
    {if $SOCIALS.facebook}
    <button type="button" class="btn btn-share-facebook rounded-circle" data-toggle="nv-social-share" data-platform="facebook" data-url="{$DATA.link}" data-title="{$DATA.title}" title="{$LANG->getGlobal('share_on', 'Facebook')}" aria-label="{$LANG->getGlobal('share_on', 'Facebook')}"><i class="fa-brands fa-facebook-f"></i></button>
    {/if}
    {if $SOCIALS.twitter}
    <button type="button" class="btn btn-share-x rounded-circle" data-toggle="nv-social-share" data-platform="x" data-url="{$DATA.link}" data-title="{$DATA.title}" title="{$LANG->getGlobal('share_on', 'X')}" aria-label="{$LANG->getGlobal('share_on', 'X')}"><i class="fa-brands fa-x-twitter"></i></button>
    {/if}
</div>
{/if}
{if $smarty.const.NV_IS_MODADMIN and empty($DATA.status)}
{* Thông báo khi admin xem bài đang đình chỉ *}
<div class="alert alert-warning" role="alert"><i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('warning')}</div>
{/if}
{if not empty($DATA.description)}
{* Ảnh minh họa dạng bên trái mô tả, chỉ có nếu có mô tả ngắn gọn *}
{if not empty($DATA.image) and $DATA.imageposition eq 1}
<figure class="float-start me-3 mb-2 align-baseline">
    <div style="width: {$DATA.thumb.width}px;">
        <img role="button" data-bs-toggle="modal" data-bs-target="#imgpreview" alt="{$DATA.imagealt ?: $DATA.title}" src="{$DATA.thumb.src}" class="img-fluid">
    </div>
</figure>
<div class="modal fade" id="imgpreview" tabindex="-1" aria-labelledby="imgpreviewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="imgpreviewLabel">{$LANG->getModule('image')}: {$DATA.imagealt ?: $DATA.title}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body text-center">
                <img alt="{$DATA.imagealt ?: $DATA.title}" src="{$DATA.img.src}" srcset="{$DATA.img.srcset}" class="img-fluid">
            </div>
        </div>
    </div>
</div>
{/if}
{* Giới thiệu ngắn gọn của bài đăng *}
<div class="article-intro mb-3 fw-medium">
    {$DATA.description}
</div>
{/if}
{* Ảnh minh họa dạng lớn *}
{if not empty($DATA.image) and $DATA.imageposition eq 2}
<figure class="image">
    <img alt="{$DATA.imagealt ?: $DATA.title}" src="{$DATA.img.src}" srcset="{$DATA.img.srcset}" width="{$DATA.img.width}">
    {if not empty($DATA.imagealt)}
    <figcaption>{$DATA.imagealt}</figcaption>
    {/if}
</figure>
{/if}
{* Nội dung chi tiết của bài đăng *}
<div class="clearfix"></div>
<div class="article-body">
    {$DATA.bodytext}
</div>
{if not empty($COMMENT)}
{* Phần bình luận *}
{$COMMENT}
{/if}
{if not empty($OTHERS)}
{* Danh sách các bài khác *}
<hr class="my-4">
<div class="h3 fs-medium border-start border-3 border-primary mb-3 ps-2">{$LANG->getModule('other_articles')}</div>
<ul class="list-unstyled vstack gap-2">
    {foreach from=$OTHERS item=other}
    <li>
        <i class="fa-solid fa-caret-right me-1"></i> <a href="{$other.link}" title="{$other.title}" class="link-body-emphasis">{$other.title}</a>
    </li>
    {/foreach}
</ul>
{/if}
