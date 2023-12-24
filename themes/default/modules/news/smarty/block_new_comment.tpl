{if empty($CONFIGS.titlelength) || $CONFIGS.titlelength < 60}
{$CONFIGS.titlelength = 60}
{/if}

<ul class="list-none list-items">
{foreach $COMMENTS as $comment}
    <li>
        <div style="display:flex;justify-content: space-between">
            {strip}<strong class="mr-1"><em class="fa fa-user"></em>&nbsp;{$comment.post_name}</strong>
            <small>{$comment.post_time}</small>{/strip}
        </div>
        <p class="margin-top-sm"><a href="{$comment.url_comment}#idcomment">{$comment.content|truncate:$CONFIGS.titlelength:"..."}</a></p>
    </li>
{/foreach}
</ul>
