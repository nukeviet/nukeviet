<style>
    html, body {
        height: auto;
        min-height: 0;
        margin: 0;
        background-color: transparent;
        overflow: hidden;
    }
</style>
<script src="{$smarty.const.NV_STATIC_URL}themes/{$JS_DIR}/js/avatar.js"></script>
<div class="usravt-upload-wraper" data-area="avatar"
    data-upload-url="{$DATA.form_action}"
    data-checkss="{$DATA.checkss}"
    data-max-size="{$smarty.const.NV_UPLOAD_MAX_FILESIZE}"
    data-max-width="{$smarty.const.NV_MAX_WIDTH}"
    data-max-height="{$smarty.const.NV_MAX_HEIGHT}"
    data-max-scale="4"
    data-zoom-step="0.2"
    data-rotate-step="2"
    data-hold-interval="350"
    data-hold-delay="45"
    data-min-width="{$GCONFIG.avatar_width}"
    data-min-height="{$GCONFIG.avatar_height}"
    data-msg-no-file="{$LANG->getModule('avatar_err_nofile')}"
    data-msg-too-many="{$LANG->getModule('avatar_err_too_many')}"
    data-msg-empty="{$LANG->getModule('avatar_err_empty')}"
    data-msg-too-big="{$LANG->getModule('avatar_err_too_big')}"
    data-msg-heic="{$LANG->getModule('avatar_err_heic')}"
    data-msg-bad-type="{$LANG->getModule('avatar_err_bad_type')}"
    data-msg-max-pixels="{$LANG->getModule('avatar_bigsize', $smarty.const.NV_MAX_WIDTH, $smarty.const.NV_MAX_HEIGHT)}"
    data-msg-min-pixels="{$LANG->getModule('avatar_smallsize', $GCONFIG.avatar_width, $GCONFIG.avatar_height)}"
    data-msg-save="{$LANG->getModule('avatar_error_save')}"
>
    <div class="picker-zone" data-area="picker">
        <input type="file" tabindex="-1" aria-hidden="true" accept="image/png,image/jpeg">
        <div class="ic">
            <i class="fa-solid fa-upload" aria-hidden="true"></i>
        </div>
        <div class="hint">{$LANG->getModule('avatar_chosen')}</div>
        <div class="meta text-muted small">{$LANG->getModule('avatar_filetype')}</div>
        <div class="meta text-muted small">({$LANG->getModule('avatar_bigfile', $UPLOAD_MAX_FILESIZE_TEXT)})</div>
        <button type="button" class="btn btn-primary">{$LANG->getGlobal('browse_image')}</button>
        <div class="overlay">
            <span>{$LANG->getModule('avatar_chosen_release')}</span>
        </div>
    </div>
    <div data-area="cropper" class="d-none cropper-zone">
        <div class="text-center drop-hint mb-3"><i class="fa-solid fa-up-down-left-right" aria-hidden="true"></i> {$LANG->getModule('avatar_drag_adjust')}</div>
        <div class="text-center mb-3">
            <button type="button" class="btn btn-secondary" data-toggle="change-file"><i class="fa-solid fa-image" aria-hidden="true"></i> {$LANG->getModule('avatar_change_other')}</button>
        </div>
        <div class="stage">
            <div class="stage-frame" data-area="frame" tabindex="0" role="application"></div>
        </div>
        <div class="controls" data-toggle="controls">
            <button class="control-btn" type="button" data-act="out" aria-label="{$LANG->getModule('avatar_ctl_zoomout')}" title="{$LANG->getModule('avatar_ctl_zoomout')}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"/>
                </svg>
            </button>
            <input class="control-slider" type="range" aria-label="{$LANG->getModule('avatar_ctl_slidezoom')}" title="{$LANG->getModule('avatar_ctl_slidezoom')}" min="1" max="4" step="0.01" value="1" data-toggle="slider">
            <button class="control-btn" type="button" data-act="in" aria-label="{$LANG->getModule('avatar_ctl_zoomin')}" title="{$LANG->getModule('avatar_ctl_zoomin')}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
            </button>
        </div>
        <div class="actions" data-toggle="actions">
            <button class="btn btn-secondary" type="button" data-act="ccw" data-hold="1" aria-label="{$LANG->getModule('avatar_ctl_ccw')}" title="{$LANG->getModule('avatar_ctl_ccw')}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 12a9 9 0 1 0 3-6.7"/>
                    <path d="M3 4v5h5"/>
                </svg>
            </button>
            <button class="btn btn-secondary" type="button" data-act="reset">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v4"/>
                    <path d="M12 21v-4"/>
                    <path d="M3 12h4"/>
                    <path d="M21 12h-4"/>
                    <circle cx="12" cy="12" r="3.2"/>
                </svg>
                {$LANG->getModule('avatar_reset')}
            </button>
            <button class="btn btn-secondary" type="button" data-act="cw" data-hold="1" aria-label="{$LANG->getModule('avatar_ctl_cw')}" title="{$LANG->getModule('avatar_ctl_cw')}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 12a9 9 0 1 1-3-6.7"/>
                    <path d="M21 4v5h-5"/>
                </svg>
            </button>
        </div>
        <button class="btn btn-primary w-100 mt-4" type="button" data-toggle="save">{$LANG->getGlobal('save')}</button>
    </div>
</div>
