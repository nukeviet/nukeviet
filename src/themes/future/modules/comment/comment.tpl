{* Giao diện danh sách bình luận cấp 1 *}
{if not empty($COMMENT_RESULT)}
<div class="alert alert-info" id="comment-result-info" role="alert">{$COMMENT_RESULT}</div>
<script type="text/javascript">$('#comment-result-info').delay(5000).fadeOut('slow');</script>
{/if}
<ul class="comment-list list-unstyled vstack gap-3">
    {foreach from=$DATA.comment item=comment}
    <li class="d-flex" id="cid_{$comment.cid}">
        <div class="flex-shrink-0">
            <div class="align-baseline d-flex align-items-center rounded-circle justify-content-center fw-50 fh-50 bg-primary-subtle fs-1 fw-medium text-primary-emphasis overflow-hidden">
                {if empty($comment.photo)}
                {$comment.post_name_letter}
                {else}
                <img class="object-fit-cover w-100 h-100" src="{NV_BASE_SITEURL}{$comment.photo}" alt="{$comment.post_name}">
                {/if}
            </div>
        </div>
        <div class="flex-grow-1 ms-2">
            <div class="p-2 rounded-3 bg-body-tertiary">
                <div class="comment-info mb-0 small fw-medium">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <i class="fa-solid fa-circle-user"></i> {$comment.post_name}
                        </li>
                        {if not empty($MCONFIG.emailcomm) and not empty($comment.post_email)}
                        <li class="list-inline-item">
                            <i class="fa-solid fa-at"></i> <a title="mailto {$comment.post_email}" href="mailto:{$comment.post_email}">{$comment.post_email}</a>
                        </li>
                        {/if}
                        <li class="list-inline-item">
                            <i class="fa-regular fa-clock"></i> {$comment.post_time|ddatetime}
                        </li>
                    </ul>
                </div>
                <div class="richtext-container">{$comment.content}</div>
            </div>
            <div class="comment-tool mt-2 small">
                <ul class="list-inline">
                    {if not empty($ALLOWED_DELETE)}
                    <li class="list-inline-item">
                        <i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i>
                        <a href="#" data-toggle="commDelete" data-cid="{$comment.cid}" data-checkss="{$comment.check_like}">{$LANG->getModule('delete')}</a>
                    </li>
                    {/if}
                    {if not empty($ALLOWED_COMM)}
                    <li class="list-inline-item">
                        <i class="fa-solid fa-reply"></i>
                        <a href="#" data-toggle="commFeedback" data-cid="{$comment.cid}" data-postname="{$comment.post_name}">{$LANG->getModule('feedback')}</a>
                    </li>
                    {/if}
                    <li class="list-inline-item">
                        <i class="fa-solid fa-thumbs-up" data-icon="fa-thumbs-up"></i>
                        <a href="#" data-toggle="commLike" data-cid="{$comment.cid}" data-checkss="{$comment.check_like}" data-like="1">{$LANG->getModule('like')}</a>
                        <span id="count-comment{$comment.cid}-like">{$comment.likes}</span>
                    </li>
                    <li class="list-inline-item">
                        <i class="fa-solid fa-thumbs-down" data-icon="fa-thumbs-down"></i>
                        <a href="#" data-toggle="commLike" data-cid="{$comment.cid}" data-checkss="{$comment.check_like}" data-like="-1">{$LANG->getModule('dislike')}</a>
                        <span id="count-comment{$comment.cid}-dislike">{$comment.dislikes}</span>
                    </li>
                    {if not empty($comment.attach)}
                    <li class="list-inline-item">
                        <i class="fa-solid fa-paperclip"></i>
                        <a href="{$comment.attach}" rel="nofollow">{$LANG->getModule('attachdownload')}</a>
                    </li>
                    {/if}
                </ul>
            </div>
            {if not empty($comment.children)}
            <div class="mt-3">
                {$comment.children}
            </div>
            {/if}
        </div>
    </li>
    {/foreach}
</ul>
{if not empty($DATA.page)}
<div class="d-flex justify-content-center">{$DATA.page}</div>
{/if}
