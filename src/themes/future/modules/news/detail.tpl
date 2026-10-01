{* Nhãn "Mới" dùng lại cho các danh sách tin *}
{capture name="badgeNew"}
<span class="badge text-bg-danger badge-new">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
    </svg> {$LANG->getModule('newpost')}
</span>
{/capture}
{* Liên kết tới một bài viết khác, có tooltip ảnh + mô tả nếu bật cấu hình *}
{function name="newsArticleLink" row=[]}
<a class="link-body-emphasis" href="{$row.link}"{if not empty($row.external_link)} target="_blank"{/if}{if not empty($MCONFIG.showtooltip)} data-toggle="tooltipArticle" data-hometext="{$row.hometext_clean}" data-alt="{$row.title}" data-img="{$row.imghome}" data-bs-toggle="tooltip" data-bs-placement="{$MCONFIG.tooltip_position}"{/if}>{$row.title}</a>
{/function}
{* Danh sách tin khác: dòng sự kiện, tin mới hơn, tin cũ hơn *}
{function name="newsOtherList" items=[]}
<ul class="list-unstyled vstack gap-2 mb-0">
    {foreach from=$items item=row}
    <li>
        <i class="fa-solid fa-angle-right text-muted"></i>
        {newsArticleLink row=$row}
        <span class="small text-muted">({1|ddate:$row.time})</span>
        {if (($row.newday * 86400) + $row.time) gte $smarty.now}
        {$smarty.capture.badgeNew}
        {/if}
    </li>
    {/foreach}
</ul>
{/function}
{* Bài viết liên quan cố định, hiển thị trên hoặc dưới nội dung *}
{capture name="relatedArticles"}
{if not empty($RELATED_ARTICLES)}
<div>
    <div class="fw-bold fs-5 mb-2">{$LANG->getModule('related_sarticles')}:</div>
    <ul class="mb-0">
        {foreach from=$RELATED_ARTICLES item=row}
        <li>
            {newsArticleLink row=$row}
            {if (($row.newday * 86400) + $row.time) gte $smarty.now}
            {$smarty.capture.badgeNew}
            {/if}
        </li>
        {/foreach}
    </ul>
</div>
{/if}
{/capture}
{* Quyền sửa/xóa bài viết của người quản trị *}
{assign var="linkEdit" value=""}
{assign var="linkDelete" value=[]}
{if $smarty.const.NV_IS_MODADMIN}
{assign var="linkEdit" value=$DETAIL|editAllowed:true}
{assign var="linkDelete" value=$DETAIL|deleteAllowed:1:true}
{/if}
<link href="{$smarty.const.ASSETS_STATIC_URL}/js/highlight/github.min.css" rel="stylesheet">
<div class="vstack gap-3 mb-4">
    <h1 class="mb-0">{$DETAIL.title}</h1>
    <div class="row align-items-center g-2">
        <div class="col-md-6 text-body-secondary">
            <i class="fa-regular fa-clock"></i> {$DETAIL.publtime}
        </div>
        {if $DETAIL.allowed_send == 1 or $DETAIL.allowed_print == 1 or $DETAIL.allowed_save == 1 or not empty($linkEdit) or not empty($linkDelete)}
        <div class="col-md-6">
            <ul class="list-inline mb-0 text-md-end">
                {if $DETAIL.allowed_send == 1}
                <li class="list-inline-item">
                    <a class="link-secondary" href="#" data-toggle="newsSendMailModal" data-obj="#newsSendMailModal" data-url="{$DETAIL.url_sendmail}" data-ss="{$DETAIL.newscheckss}" aria-label="{$LANG->getModule('sendmail')}" data-bs-toggle="tooltip" title="{$LANG->getModule('sendmail')}"><i class="fa-solid fa-envelope"></i></a>
                </li>
                {/if}
                {if $DETAIL.allowed_print == 1}
                <li class="list-inline-item">
                    <a class="link-secondary" rel="nofollow" href="#" data-toggle="newsPrint" data-url="{$DETAIL.url_print}" aria-label="{$LANG->getModule('print')}" data-bs-toggle="tooltip" title="{$LANG->getModule('print')}"><i class="fa-solid fa-print"></i></a>
                </li>
                {/if}
                {if $DETAIL.allowed_save == 1}
                <li class="list-inline-item">
                    <a class="link-secondary" rel="nofollow" href="{$DETAIL.url_savefile}" aria-label="{$LANG->getModule('savefile')}" data-bs-toggle="tooltip" title="{$LANG->getModule('savefile')}"><i class="fa-solid fa-floppy-disk"></i></a>
                </li>
                {/if}
                {if not empty($linkEdit) or not empty($linkDelete)}
                <li class="list-inline-item">
                    <span class="dropdown">
                        <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-screwdriver-wrench"></i> <span class="visually-hidden">{$LANG->getModule('admtools')}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {if not empty($linkEdit)}
                            <li><a class="dropdown-item" href="{$linkEdit}"><i class="fa-solid fa-pencil fa-fw text-center"></i> {$LANG->getGlobal('edit')}</a></li>
                            {/if}
                            {if not empty($linkDelete)}
                            <li><a class="dropdown-item" href="#" data-toggle="nv_del_content" data-id="{$linkDelete.id}" data-checkss="{$linkDelete.checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}" data-detail="{$linkDelete.detail}"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
                            {/if}
                        </ul>
                    </span>
                </li>
                {/if}
            </ul>
        </div>
        {/if}
    </div>
    {if $DETAIL.allowed_send == 1}
    <!-- START FORFOOTER -->
    <div class="modal fade" id="newsSendMailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"></div>
    </div>
    <!-- END FORFOOTER -->
    {/if}
    {if $DETAIL.status != 1}
    <div class="alert alert-warning mb-0">{$LANG->getModule('no_public')}</div>
    {/if}
    {* Báo nói *}
    {if not empty($DETAIL.current_voice)}
    <link rel="stylesheet" href="{$smarty.const.NV_STATIC_URL}{$smarty.const.NV_ASSETS_DIR}/js/plyr/plyr.css">
    <script src="{$smarty.const.NV_STATIC_URL}{$smarty.const.NV_ASSETS_DIR}/js/plyr/plyr.polyfilled.js"></script>
    <div class="news-detail-player">
        <div class="player">
            <audio id="newsVoicePlayer" data-voice-id="{$DETAIL.current_voice.id}" data-voice-path="{$DETAIL.current_voice.path}" data-voice-title="{$DETAIL.current_voice.title}" data-autoplay="{$DETAIL.autoplay}"></audio>
        </div>
        <div class="dropdown">
            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-microphone"></i> <span data-area="newsVoiceVal">{$DETAIL.current_voice.title}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                {foreach from=$DETAIL.voicedata item=voice}
                <li><a class="dropdown-item" href="#" data-toggle="newsVoiceSel" data-id="{$voice.id}" data-path="{$voice.path}" data-tokend="{$smarty.const.NV_CHECK_SESSION}">{$voice.title}</a></li>
                {/foreach}
            </ul>
        </div>
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" role="switch" id="newsVoiceAutoplay" data-toggle="newsVoiceAutoplay" data-tokend="{$smarty.const.NV_CHECK_SESSION}"{if $DETAIL.autoplay} checked{/if}>
            <label class="form-check-label" for="newsVoiceAutoplay">{$LANG->getModule('autoplay')}</label>
        </div>
    </div>
    {/if}
    {* Phần mở đầu và ảnh minh họa *}
    {if $DETAIL.showhometext}
    <div class="clearfix">
        {if not empty($DETAIL.image.src) and $DETAIL.image.position == 1}
        <figure class="figure align-baseline float-start me-3 mb-2" role="button" data-toggle="modalShowByObj" data-obj="#newsImgPreview">
            <img src="{$DETAIL.image.src}" alt="{$DETAIL.image.alt}" width="{$DETAIL.image.width}" class="figure-img img-fluid rounded mb-1">
            {if not empty($DETAIL.image.note)}
            <figcaption class="figure-caption fst-italic">{$DETAIL.image.note}</figcaption>
            {/if}
        </figure>
        <div id="newsImgPreview" class="d-none" title="">
            <div class="text-center">
                <img src="{$DETAIL.homeimgfile}" srcset="{$DETAIL.srcset}" alt="{$DETAIL.image.alt}" class="img-fluid rounded">
                {if not empty($DETAIL.image.note)}
                <div class="small fst-italic mt-1">{$DETAIL.image.note}</div>
                {/if}
            </div>
        </div>
        {/if}
        {if not empty($DETAIL.hometext)}
        <div class="fw-bold" data-toggle="error-report">{$DETAIL.hometext}</div>
        {/if}
    </div>
    {if not empty($DETAIL.image.src) and $DETAIL.image.position == 2}
    <figure class="figure d-block text-center mb-0">
        <img src="{$DETAIL.image.src}" srcset="{$DETAIL.srcset}" alt="{$DETAIL.image.alt}" width="{$DETAIL.image.width}" class="figure-img img-fluid rounded mb-1">
        {if not empty($DETAIL.image.note)}
        <figcaption class="figure-caption fst-italic">{$DETAIL.image.note}</figcaption>
        {/if}
    </figure>
    {/if}
    {/if}
    {if $DETAIL.related_pos == 1}
    {$smarty.capture.relatedArticles}
    {/if}
    {* Mục lục bài viết *}
    {if not empty($DETAIL.navigation)}
    <script src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
    <nav class="news-toc news-toc-{$DETAIL.auto_nav}" data-area="newsToc" data-copied="{$LANG->getModule('link_copied')}" aria-label="{$LANG->getModule('table_of_contents')}">
        <div class="news-toc-title">
            <i class="fa-solid fa-list-ul"></i> {$LANG->getModule('table_of_contents')}
        </div>
        <ol class="news-toc-list">
            {foreach from=$DETAIL.navigation item=nav}
            <li>
                <a href="#" data-toggle="newsTocScroll" data-scroll-to="{$nav.item.1}" data-location="{$nav.item.2}">{$nav.item.0}</a>
                {if not empty($nav.subitems)}
                <ol class="news-toc-sub">
                    {foreach from=$nav.subitems item=subnav}
                    <li>
                        <a href="#" data-toggle="newsTocScroll" data-scroll-to="{$subnav.1}" data-location="{$subnav.2}">{$subnav.0}</a>
                    </li>
                    {/foreach}
                </ol>
                {/if}
            </li>
            {/foreach}
        </ol>
    </nav>
    {/if}
    {* Nội dung chi tiết *}
    {if not empty($DETAIL.bodyhtml)}
    <div class="richtext-container news-bodyhtml clearfix" data-toggle="error-report">
        {$DETAIL.bodyhtml}
    </div>
    {/if}
    {if $DETAIL.related_pos == 2}
    {$smarty.capture.relatedArticles}
    {/if}
    {* File đính kèm *}
    {if not empty($DETAIL.files)}
    <div class="card">
        <div class="card-header fw-medium">
            <i class="fa-solid fa-download"></i> {$LANG->getModule('files')}
        </div>
        <ul class="list-group list-group-flush">
            {foreach from=$DETAIL.files item=file}
            <li class="list-group-item">
                <div class="d-flex align-items-center gap-2">
                    <a class="me-auto text-break" href="{$file.url}" title="{$file.titledown} {$file.title}" download>{$file.titledown}: <strong>{$file.title}</strong></a>
                    {if not empty($file.urlfile)}
                    <a class="btn btn-sm btn-success" role="button" data-bs-toggle="collapse" href="#file-{$file.key}" aria-expanded="false" aria-controls="file-{$file.key}" aria-label="{$LANG->getModule('preview')}" title="{$LANG->getModule('preview')}"><i class="fa-solid fa-eye"></i></a>
                    {elseif in_array($file.ext, ['png', 'jpe', 'jpeg', 'jpg', 'gif', 'bmp', 'ico', 'tiff', 'tif', 'svg', 'svgz'], true)}
                    <a class="btn btn-sm btn-success" href="#" data-toggle="newsAttachImage" data-src="{$file.src}" aria-label="{$LANG->getModule('preview')}" title="{$LANG->getModule('preview')}"><i class="fa-solid fa-eye"></i></a>
                    {/if}
                </div>
                {if not empty($file.urlfile)}
                <div class="collapse" id="file-{$file.key}" data-toggle="newsCollapseFile" data-src="{$file.urlfile}">
                    <div class="pt-3">
                        <iframe class="w-100 border rounded" height="600"></iframe>
                    </div>
                </div>
                {/if}
            </li>
            {/foreach}
        </ul>
    </div>
    {/if}
    {if not empty($DETAIL.author) or not empty($DETAIL.source)}
    <div class="text-end">
        {if not empty($DETAIL.author)}
        <p class="mb-1"><strong>{$LANG->getModule('author')}:</strong> {$DETAIL.author}</p>
        {/if}
        {if not empty($DETAIL.source)}
        <p class="mb-1"><strong>{$LANG->getModule('source')}:</strong> {$DETAIL.source}</p>
        {/if}
    </div>
    {/if}
    {if $DETAIL.copyright == 1 and not empty($MCONFIG.copyright)}
    <div class="alert alert-info mb-0">{$MCONFIG.copyright}</div>
    {/if}
    {* Chân bài: từ khóa, đánh giá, chia sẻ *}
    {assign var="hasSocial" value=($SOCIALS.facebook or $SOCIALS.twitter)}
    {if not empty($KEYWORDS) or $DETAIL.allowed_rating or $hasSocial}
    <div class="border-top pt-3 vstack gap-3">
        {if not empty($KEYWORDS)}
        <div class="d-flex flex-wrap align-items-center gap-1">
            <span class="visually-hidden">{$LANG->getModule('tags')}:</span>
            {foreach from=$KEYWORDS item=keyword}
            <a class="badge text-bg-secondary text-truncate mw-100" href="{$keyword.link}" title="{$keyword.keyword}"><i class="fa-solid fa-tag"></i> {$keyword.keyword}</a>
            {/foreach}
        </div>
        {/if}
        {if $DETAIL.allowed_rating or $hasSocial}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            {if $DETAIL.allowed_rating}
            <form data-toggle="newsRating" data-id="{$DETAIL.id}" data-checkss="{$DETAIL.newscheckss}" data-checked="{$DETAIL.numberrating_star}">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="news-rating{if $DETAIL.disablerating} disabled{/if}">
                        {foreach ['star_verygood', 'star_good', 'star_ok', 'star_poor', 'star_verypoor'] as $starkey}
                        {assign var="starval" value=(5 - $starkey@index)}
                        <input type="radio" id="newsRating{$starval}" name="rate" value="{$starval}">
                        <label for="newsRating{$starval}" data-title="{$LANG->getModule($starkey)}"><i class="fa-solid fa-star"></i><span class="visually-hidden">{$LANG->getModule($starkey)}</span></label>
                        {/foreach}
                    </div>
                    <span class="small text-body-secondary" data-area="newsRatingFeedback" data-default="{if not $DETAIL.disablerating}{$LANG->getModule('star_note')}{/if}" data-success="{$LANG->getModule('rating_success')}">{if not $DETAIL.disablerating}{$LANG->getModule('star_note')}{/if}</span>
                </div>
                <div class="small text-body-secondary mt-1 d-none" data-area="newsRatingInfo">
                    <div data-area="newsRatingString">{$DETAIL.stringrating}</div>
                    {if $DETAIL.numberrating gte $MCONFIG.allowed_rating_point}
                    <div>
                        {$LANG->getModule('rating_average')}: <span data-area="newsRatingNumber">{$DETAIL.numberrating}</span> / <span data-area="newsRatingClick">{$DETAIL.click_rating}</span> {$LANG->getModule('rating_count')}
                    </div>
                    {/if}
                </div>
            </form>
            {/if}
            {if $hasSocial}
            <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                <span class="small text-body-secondary"><i class="fa-solid fa-share-nodes"></i> {$LANG->getGlobal('share')}:</span>
                {if $SOCIALS.facebook}
                <button type="button" class="btn btn-share-facebook rounded-circle" data-toggle="nv-social-share" data-platform="facebook" data-url="{$DETAIL.link}" data-title="{$DETAIL.title}" title="{$LANG->getGlobal('share_on', 'Facebook')}" aria-label="{$LANG->getGlobal('share_on', 'Facebook')}"><i class="fa-brands fa-facebook-f"></i></button>
                {/if}
                {if $SOCIALS.twitter}
                <button type="button" class="btn btn-share-x rounded-circle" data-toggle="nv-social-share" data-platform="x" data-url="{$DETAIL.link}" data-title="{$DETAIL.title}" title="{$LANG->getGlobal('share_on', 'X')}" aria-label="{$LANG->getGlobal('share_on', 'X')}"><i class="fa-brands fa-x-twitter"></i></button>
                {/if}
            </div>
            {/if}
        </div>
        {/if}
    </div>
    {/if}
    {* Bình luận, template bình luận đã có sẵn đường kẻ ngăn cách ở trên *}
    {if not empty($CONTENT_COMMENT)}
    <div>{$CONTENT_COMMENT}</div>
    {/if}
</div>
{* Tin khác *}
{if not empty($TOPICS) or not empty($RELATED_NEW) or not empty($RELATED)}
<div class="vstack gap-4 mb-4">
    {if not empty($TOPICS)}
    <section>
        <div class="fw-medium fs-5 border-bottom pb-2 mb-3">{$LANG->getModule('topic')}</div>
        {newsOtherList items=$TOPICS}
        <div class="text-end mt-2">
            <a title="{$TOPICS[0].topictitle}" href="{$TOPICS[0].topiclink}">{$LANG->getModule('more')}</a>
        </div>
    </section>
    {/if}
    {if not empty($RELATED_NEW) or not empty($RELATED)}
    <div class="row g-4">
        {if not empty($RELATED_NEW)}
        <section class="col-12 col-md">
            <div class="fw-medium fs-5 border-bottom pb-2 mb-3">{$LANG->getModule('related_new')}</div>
            {newsOtherList items=$RELATED_NEW}
        </section>
        {/if}
        {if not empty($RELATED)}
        <section class="col-12 col-md">
            <div class="fw-medium fs-5 border-bottom pb-2 mb-3">{$LANG->getModule('related')}</div>
            {newsOtherList items=$RELATED}
        </section>
        {/if}
    </div>
    {/if}
</div>
{/if}
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/highlight/highlight.min.js"></script>
