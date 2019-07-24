<script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/clipboard/clipboard.min.js"></script>
{if not empty($ERRORCONFIG)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">
        <strong>ERROR! CONFIG FILE:</strong> <br />
        {foreach from=$ERRORCONFIG item=error}
        {$error}<br />
        {/foreach}
    </div>
</div>
{/if}
<div class="row">
    {foreach from=$ARRAY_THEMES item=theme}
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-border card-border-theme">
            <div class="theme-item{if $SITE_THEME eq $theme.value} active{/if}">
                <div class="theme-item-thumb" style="background-image: url('{$NV_BASE_SITEURL}themes/{$theme.value}/{$theme.thumbnail}');">
                    <img alt="{$theme.name}" src="{$NV_BASE_SITEURL}themes/{$theme.value}/{$theme.thumbnail}">
                    {if $SITE_THEME neq $theme.value}
                    <div class="actions">
                        {if in_array($theme.value, $ARRAY_SITE_THEME)}
                        {if not in_array($theme.value, $THEME_MOBILE_LIST)}
                        <a href="#" class="btn btn-success btn-block activate-theme text-truncate my-1" data-theme="{$theme.value}"><i class="fas fa-check-double"></i> {$LANG->get('theme_created_activate')}</a>
                        {/if}
                        {if $theme.value neq 'default'}
                        <a href="#" class="btn btn-danger btn-block delete-theme-setting text-truncate my-1" data-theme="{$theme.value}"><i class="fas fa-trash-alt"></i> {$LANG->get('theme_delete')}</a>
                        {/if}
                        {else}
                        <a href="#" class="btn btn-primary btn-block activate-theme text-truncate my-1" data-theme="{$theme.value}"><i class="fas fa-check-double"></i> {$LANG->get('theme_created_setting')}</a>
                        {/if}
                    </div>
                    {/if}
                </div>
                <div class="theme-item-title p-2">
                    <div class="row">
                        <div class="col-7">
                            <h3 class="text-truncate"><span>{$theme.name}</span></h3>
                        </div>
                        <div class="col-5">
                            <a href="#" data-toggle="modal" data-target="#theme-detail-{$theme.value}" class="btn btn-{if $SITE_THEME eq $theme.value}secondary{else}primary{/if} float-right themedetail">{$LANG->get('detail')}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="theme-detail-{$theme.value}" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-primary modal-theme-detail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-colored">
                        <h3 class="modal-title">{$LANG->get('detail')}</h3>
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                    </div>
                    <div class="modal-body">
                        {if $SITE_THEME neq $theme.value and in_array($theme.value, $ARRAY_SITE_THEME)}
                        <div class="form-group clearfix">
                            <div class="float-right">
                                <a href="#" class="btn btn-primary" data-toggle="previewtheme" data-value="{$theme.value}"><i class="fas fa-spinner fa-pulse d-none"></i> <span>{if in_array($theme.value, $ARRAY_ALLOW_PREVIEW)}{$LANG->get('preview_theme_off')}{else}{$LANG->get('preview_theme_on')}{/if}</span> </a>
                            </div>
                            <label class="preview-label{if not in_array($theme.value, $ARRAY_ALLOW_PREVIEW)} d-none{/if}">{$LANG->get('preview_theme_link')}:</label>
                        </div>
                        <div class="preview-link form-group{if not in_array($theme.value, $ARRAY_ALLOW_PREVIEW)} d-none{/if}">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control selectedfocus" value="{$theme.link_preview}" id="preview-link-{$theme.value}">
                                <div class="input-group-append">
                                    <a href="javascript:void(0);" class="btn btn-secondary preview-link-btn" data-clipboard-target="#preview-link-{$theme.value}" data-title="{$LANG->get('preview_theme_link_copied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></a>
                                </div>
                            </div>
                        </div>
                        {/if}
                        <div class="text-center">
                            <img alt="{$theme.name}" src="{$NV_BASE_SITEURL}themes/{$theme.value}/{$theme.thumbnail}" class="img-fluid">
                        </div>
                        <h1>{$theme.name}</h1>
                        <p class="author">{$LANG->get('theme_created_by')}: <a href="{$theme.website}" title="{$LANG->get('theme_created_website')}" target="_blank"><strong>{$theme.author}</strong></a></p>
                        <p class="tinfo">{$theme.description}</p>
                        <p class="tdir">{$LANG->get('theme_created_folder')} <code>/themes/{$theme.value}/</code></p>
                        <p class="tpos">{$LANG->get('theme_created_position')} {foreach from=$theme.pos item=pos} <code>[{$pos}]</code> {/foreach}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/foreach}
</div>
<script type="text/javascript">
LANG.theme_delete_confirm = '{$LANG->get('theme_delete_confirm')}';
</script>
