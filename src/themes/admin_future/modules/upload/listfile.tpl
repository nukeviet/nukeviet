<div class="fms-files-wraper">
    <ul>
        {foreach from=$FILES item=file}
        <li>
            <div class="file">
                <div class="thumb{if $file.height > $file.width} thumb-v{/if}">
                    <span class="thumb-blur" style="background-image: url({$file.src});"></span>
                    <span class="thumb-bg"></span>
                    <img src="{$file.src}" alt="">
                </div>
                <div class="name">2</div>
                <div class="info">3</div>
            </div>
        </li>
        {/foreach}
    </ul>
</div>
