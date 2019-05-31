<ul>
    {foreach from=$ARRAY_FILES item=file}
    <li>
        <div class="file{if in_array($file.title, $SELECTFILE)} file-selected{/if}" data-file="{$file.title}" data-fdata="{$file.data}" data-alt="{$file.alt}" data-img="{$file.is_img}">
            <div class="img">
                <div class="bg" style="background-image: url('{$file.src}');"><img src="{$file.src}" alt="{$file.alt}"></div>
            </div>
            <div class="name" title="{$file.title}"><i class="icon fas fa-file-image"></i>{$file.title}</div>
            <div class="info">{$file.size}</div>
        </div>
    </li>
    {/foreach}
</ul>
