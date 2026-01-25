<div class="fms-files-wraper">
    <ul>
        {foreach from=$FILES item=file}
        <li>
            <div class="file"
                data-toggle="file"
                data-name="{$file.real_name}"
                data-ext="{$file.ext}"
                data-uuid="{$file.uuid}"
                data-path="{$file.path}"
                data-nocache-path="{$file.nocache_path}"
                data-abs-path="{$file.abs_path}"
                data-dir-path="{$file.dir_path}"
                data-dir="{$file.dir}"
                data-alt="{$file.alt}"
                data-mtime="{$file.mtime}"
                data-thumb-src="{$file.src}"
                data-thumb="{$file.thumb}"
                data-preview-size="{$file.size_detail}"
                data-type="{$file.type}"
                data-width="{$file.width}"
                data-height="{$file.height}"
                data-filesize="{$file.filesize_show}"
            >
                <div class="sel">
                    <input class="form-check-input" data-toggle="file-check" type="checkbox" id="{$file.uuid}-file-checkbox" value="" aria-label="{$LANG->getModule('selectimg')}">
                </div>
                <div class="thumb{if $file.height > 0 and ($file.width / $file.height) <= 1.3333333} thumb-v{/if}">
                    <span class="thumb-blur" style="background-image: url({$file.src});"></span>
                    <span class="thumb-bg"></span>
                    <img src="{$file.src}" alt="{$file.alt}">
                </div>
                <div class="name text-truncate" title="{$file.real_name}">
                    <span class="name-real">{$file.real_name}</span>
                    <span class="name-cut">{$file.name}</span>
                </div>
                <div class="info">{$file.size}</div>
                <div class="menu">
                    <button type="button" data-toggle="file-menu" class="btn-menu btn btn-sm btn-secondary" aria-label="{$LANG->getGlobal('option')}"><i class="fa-solid fa-caret-down fa-fw"></i></button>
                </div>
            </div>
        </li>
        {/foreach}
    </ul>
</div>
