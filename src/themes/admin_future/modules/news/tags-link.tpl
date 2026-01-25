<ul class="list-group list-group-flush">
    {foreach from=$DATA item=row}
    <li class="list-group-item" data-item="{$row.id}">
        <div class="fs-6 fw-medium mb-2">
            <div class="d-flex align-items-center">
                <div class="me-2">
                    <input type="checkbox" data-toggle="checkSingle" data-type="link" value="{$row.id}" class="form-check-input m-0 d-block" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                </div>
                <div style="min-width: 0;">
                    <a href="{$row.url}" target="_blank" class="d-block text-truncate" title="{$row.title}">{$row.title}</a>
                </div>
            </div>
        </div>
        <div class="ms-4">
            <div class="show-keywords">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="me-2">
                        <div class="d-flex align-items-center">
                            <div class="me-2">{$LANG->getModule('keyword')}:</div>
                            <span data-toggle="badgeKeyword" class="badge d-block {if in_array($row.keyword, $KEYWORDS, true)}text-bg-success{else}text-bg-warning{/if} text-truncate">{if in_array($row.keyword, $KEYWORDS, true)}<i class="fa-solid fa-check"></i>{else}<i class="fa-solid fa-triangle-exclamation"></i>{/if} {$row.keyword}</span>
                        </div>
                    </div>
                    <div>
                        <a href="#" title="{$LANG->getGlobal('edit')}" aria-label="{$LANG->getGlobal('edit')}" data-toggle="tag_keyword_edit" data-id="{$row.id}"><i class="fa-solid fa-pencil" data-icon="fa-pencil"></i></a>
                    </div>
                </div>
            </div>
            <div class="edit-keywords d-none">
                <div class="d-flex align-items-center">
                    <div class="flex-fill d-flex align-items-center me-2">
                        <label class="me-2 text-nowrap" for="element_change_tag{$row.id}_keyword">{$LANG->getModule('keyword')}:</label>
                        <select class="form-select flex-fill" id="element_change_tag{$row.id}_keyword" name="keyword">
                            {foreach from=$KEYWORDS item=keyword}
                            <option value="{$keyword}"{if $keyword eq $row.keyword} selected{/if}>{$keyword}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="keyword_change" data-id="{$row.id}" data-tid="{$TID}"><i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk"></i> {$LANG->getModule('save')}</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-link" data-toggle="tag_keyword_close" data-id="{$row.id}" aria-label="{$LANG->getGlobal('close')}" title="{$LANG->getGlobal('close')}"><i class="fa-regular fa-circle-xmark"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </li>
    {/foreach}
</ul>
