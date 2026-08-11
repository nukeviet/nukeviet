<!-- BEGIN: main -->
<style>
    html, body {
    height: auto;
    min-height: 0;
    margin: 0;
    background-color: transparent;
    overflow: hidden;
}
</style>
<script src="{NV_STATIC_URL}themes/{TEMPLATE}/js/avatar.js"></script>
<div class="usravt-upload-wraper" data-area="avatar"
    data-upload-url="{DATA.form_action}"
    data-checkss="{DATA.checkss}"
    data-max-size="{NV_UPLOAD_MAX_FILESIZE}"
    data-max-width="{NV_MAX_WIDTH}"
    data-max-height="{NV_MAX_HEIGHT}"
    data-max-scale="4"
    data-zoom-step="0.2"
    data-rotate-step="2"
    data-hold-interval="350"
    data-hold-delay="45"
    data-min-width="{NV_AVATAR_WIDTH}"
    data-min-height="{NV_AVATAR_HEIGHT}"
    data-msg-no-file="{LANG.avatar_err_nofile}"
    data-msg-too-many="{LANG.avatar_err_too_many}"
    data-msg-empty="{LANG.avatar_err_empty}"
    data-msg-too-big="{LANG.avatar_err_too_big}"
    data-msg-heic="{LANG.avatar_err_heic}"
    data-msg-bad-type="{LANG.avatar_err_bad_type}"
    data-msg-max-pixels="{LANG.avatar_bigsize}"
    data-msg-min-pixels="{LANG.avatar_smallsize}"
    data-msg-save="{LANG.avatar_error_save}"
>
    <div class="picker-zone" data-area="picker">
        <input type="file" tabindex="-1" aria-hidden="true" accept="image/png,image/jpeg">
        <div class="ic">
            <i class="fa fa-upload" aria-hidden="true"></i>
        </div>
        <div class="hint">{LANG.avatar_chosen}</div>
        <div class="meta text-muted small">{LANG.avatar_filetype}</div>
        <div class="meta text-muted small">({LANG.avatar_bigfile})</div>
        <button class="btn btn-primary">{GLANG.browse_image}</button>
        <div class="overlay">
            <span>{LANG.avatar_chosen_release}</span>
        </div>
    </div>
    <div data-area="cropper" class="hidden cropper-zone">
        <div class="text-center drop-hint margin-bottom"><i class="fa fa-arrows" aria-hidden="true"></i> {LANG.avatar_drag_adjust}</div>
        <div class="text-center margin-bottom">
            <button class="btn btn-default" data-toggle="change-file"><i class="fa fa-picture-o" aria-hidden="true"></i> {LANG.avatar_change_other}</button>
        </div>
        <div class="stage">
            <div class="stage-frame" data-area="frame" tabindex="0" role="application"></div>
        </div>
        <div class="controls" data-toggle="controls">
            <button class="control-btn" type="button" data-act="out" aria-label="{LANG.avatar_ctl_zoomout}" title="{LANG.avatar_ctl_zoomout}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"/>
                </svg>
            </button>
            <input class="control-slider" type="range" aria-label="{LANG.avatar_ctl_slidezoom}" title="{LANG.avatar_ctl_slidezoom}" min="1" max="4" step="0.01" value="1" data-toggle="slider">
            <button class="control-btn" type="button" data-act="in" aria-label="{LANG.avatar_ctl_zoomin}" title="{LANG.avatar_ctl_zoomin}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
            </button>
        </div>
        <div class="actions" data-toggle="actions">
            <button class="btn btn-default" type="button" data-act="ccw" data-hold="1" aria-label="{LANG.avatar_ctl_ccw}" title="{LANG.avatar_ctl_ccw}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 12a9 9 0 1 0 3-6.7"/>
                    <path d="M3 4v5h5"/>
                </svg>
            </button>
            <button class="btn btn-default" type="button" data-act="reset">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v4"/>
                    <path d="M12 21v-4"/>
                    <path d="M3 12h4"/>
                    <path d="M21 12h-4"/>
                    <circle cx="12" cy="12" r="3.2"/>
                </svg>
                {LANG.avatar_reset}
            </button>
            <button class="btn btn-default" type="button" data-act="cw" data-hold="1" aria-label="{LANG.avatar_ctl_cw}" title="{LANG.avatar_ctl_cw}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 12a9 9 0 1 1-3-6.7"/>
                    <path d="M21 4v5h-5"/>
                </svg>
            </button>
        </div>
        <button class="btn btn-primary btn-block margin-top-lg" type="button" data-toggle="save">{GLANG.save}</button>
    </div>
</div>
<!-- END: main -->
