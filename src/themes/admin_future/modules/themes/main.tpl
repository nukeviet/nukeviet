<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
{if not empty($ERRORCONFIG)}
<div class="alert alert-danger" role="alert">ERROR! CONFIG FILE:<br />{$ERRORCONFIG|join:'<br />'}</div>
{/if}
<div class="row g-3" id="mainthemes">
    {foreach from=$ARRAY item=theme}
    <div class="col-sm-6 col-lg-4 col-xl-3 col-xxl-2">
        <div class="card{if $GCONFIG.site_theme eq $theme.value} text-bg-primary{/if} item theme-item">
            <div class="themelist-thumb">
                <div class="themelist">
                    <img alt="{$theme.name}" src="{$smarty.const.NV_BASE_SITEURL}themes/{$theme.value}/{$theme.thumbnail}">
                </div>
                {if $theme.allowed_delete or $theme.allowed_active or $theme.allowed_setting}
                <div class="theme-btns flex-wrap">
                    {if $theme.allowed_setting}
                    <button type="button" class="btn btn-dark w-100 text-truncate activate" data-theme="{$theme.value}" data-checkss="{$theme.checkss}"><i class="fa-solid fa-sun fa-fw" data-icon="fa-sun"></i> {$LANG->getModule('theme_created_setting')}</button>
                    {/if}
                    {if $theme.allowed_active}
                    <button type="button" class="btn btn-dark w-100 text-truncate activate" data-theme="{$theme.value}" data-checkss="{$theme.checkss}"><i class="fa-solid fa-sun fa-fw" data-icon="fa-sun"></i> {$LANG->getModule('theme_created_activate')}</button>
                    {/if}
                    {if $theme.allowed_delete}
                    <button type="button" class="btn btn-danger w-100 text-truncate delete" data-theme="{$theme.value}" data-checkss="{$theme.checkss}" data-confirm="{$LANG->getModule('theme_delete_confirm')}"><i class="fa-solid fa-trash fa-fw" data-icon="fa-trash"></i> {$LANG->getModule('theme_delete')}</button>
                    {/if}
                </div>
                {/if}
            </div>
            <div class="card-body p-2">
                <div class="d-flex gap-2 align-items-center justify-content-between">
                    <h2 class="text-truncate fs-5 mw-100 mb-0" title="{$theme.name}">{$theme.name}</h2>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#theme-detail-{$theme.value}" class="flex-shrink-0 btn btn-sm btn-{$GCONFIG.site_theme eq $theme.value ? 'secondary' : 'primary'}">{$LANG->getGlobal('detail')}</button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="theme-detail-{$theme.value}" tabindex="-1" aria-labelledby="theme-detail-{$theme.value}-lbl" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="nv-theme-detail">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title fs-5 fw-medium" id="theme-detail-{$theme.value}-lbl">{$LANG->getGlobal('detail')}: {$theme.value}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                        </div>
                        <div class="modal-body">
                            {if $theme.allowed_preview}
                            <div class="mb-3 d-flex align-items-center justify-content-{in_array($theme.value, $ARRAY_ALLOW_PREVIEW, true) ? 'between' : 'end'} gap-2">
                                <div class="preview-label{in_array($theme.value, $ARRAY_ALLOW_PREVIEW, true) ? '' : ' d-none'}">{$LANG->getModule('preview_theme_link')}:</div>
                                <button type="button" class="btn btn-primary" data-toggle="previewtheme" data-value="{$theme.value}"><i class="fa-solid fa-spinner fa-spin-pulse d-none"></i> <span>{$LANG->getModule(in_array($theme.value, $ARRAY_ALLOW_PREVIEW, true) ? 'preview_theme_off' : 'preview_theme_on')}</span></button>
                            </div>
                            <div class="preview-link mb-3{if not in_array($theme.value, $ARRAY_ALLOW_PREVIEW, true)} d-none{/if}">
                                <div class="input-group">
                                    <input type="text" class="form-control selectedfocus" value="{$theme.link_preview}" name="preview_link" id="preview_link_{$theme.value}">
                                    <button type="button" class="btn btn-secondary preview-link-btn" data-clipboard-target="#preview_link_{$theme.value}" data-success="{$LANG->getModule('preview_theme_link_copied')}" aria-label="{$LANG->getGlobal('copy')}"><i class="fa-solid fa-copy"></i></button>
                                </div>
                            </div>
                            {/if}
                            <div class="text-center mb-3">
                                <img alt="{$theme.name}" class="img-fluid" src="{$smarty.const.NV_BASE_SITEURL}themes/{$theme.value}/{$theme.thumbnail}"/>
                            </div>
                            <div class="fw-medium fs-1">{$theme.name}</div>
                            <p class="author">{$LANG->getModule('theme_created_by')}: <a href="{$theme.website}" title="{$LANG->getModule('theme_created_website')}" target="_blank"><strong>{$theme.author}</strong></a></p>
                            <p class="tinfo">{$theme.description}</p>
                            <p class="tdir">{$LANG->getModule('theme_created_folder')} <code>/themes/{$theme.value}/</code></p>
                            <p class="tpos">{$LANG->getModule('theme_created_position')} <code>{$theme.pos|join:"</code>, <code>"}</code></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/foreach}
</div>
