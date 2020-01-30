<div class="card card-border-color card-border-color-primary">
    <div class="card-header card-header-divider">
        {$LANG->get('settings_utheme')}
        <span class="card-subtitle">{$LANG->get('settings_utheme_help')}</span>
    </div>
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div role="alert" class="alert alert-primary alert-dismissible">
                <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                <div class="icon"><i class="fas fa-info-circle"></i></div>
                <div class="message">
                    <a href="{$LINK_SET_CONFIG}">{$LANG->get('settings_utheme_note')}</a>. {$LANG_MESSAGE}.
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('settings_utheme_choose')}</label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    {foreach from=$ARRAY_THEMES item=themename}
                    <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="user_allowed_theme[]" value="{$themename}"{if in_array($themename, $CONFIG.user_allowed_theme) or $GCONFIG.site_theme eq $themename} checked="checked"{/if}{if $GCONFIG.site_theme eq $themename} disabled="disabled"{/if}><span class="custom-control-label">{$themename}</span>
                    </label>
                    {/foreach}
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
