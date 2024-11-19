<div class="fms-files-wraper">
    <ul>
        {foreach from=$FILES item=file}
        <li>
            <div class="file" data-toggle="file">
                <div class="thumb{if $file.height > $file.width} thumb-v{/if}">
                    <span class="thumb-blur" style="background-image: url({$file.src});"></span>
                    <span class="thumb-bg"></span>
                    <img src="{$file.src}" alt="{$file.alt}">
                </div>
                <div class="name text-truncate" title="{$file.real_name}">
                    <span class="name-real">{$file.real_name}</span>
                    <span class="name-cut">{$file.name}</span>
                </div>
                <div class="info">{$file.size}</div>
            </div>
        </li>
        {/foreach}
    </ul>
</div>
