<ul class="list-unstyled vstack gap-2 mb-0">
    {foreach from=$ITEMS item=item}
    <li>
        <div class="small text-body-secondary d-flex flex-wrap column-gap-2 mb-1">
            <span class="text-break"><i class="fa-regular fa-user fa-fw"></i> <span class="fw-semibold">{$item.post_name}</span></span>
            <span><i class="fa-regular fa-clock fa-fw"></i> {$LANG->getModule('pubtime')} {$item.post_time}</span>
        </div>
        <a class="link-body-emphasis text-break" href="{$item.link}#idcomment">{$item.content}</a>
    </li>
    {/foreach}
</ul>
