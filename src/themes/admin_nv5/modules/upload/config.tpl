<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <h4 class="mb-0">{$LANG->get('autologo')}</h4>
                </div>
            </div>
            <div class="card-divider"></div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-9 col-lg-9 form-check mt-1">
                    <div class="row">
                        {foreach from=$AUTOLOG_MODS key=key item=value}
                        <div class="col-6 col-sm-4 col-md-3">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1 w-100 mr-0">
                                <input class="custom-control-input" type="checkbox" name="autologomod[]" value="{$value.key}"{if in_array($value.key, $AUTOLOG_MOD)} checked="checked"{/if}><span class="custom-control-label text-truncate" title="{$value.title}">{$value.title}</span>
                            </label>
                        </div>
                        {/foreach}
                        <div class="col-6 col-sm-4 col-md-3">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1 w-100 mr-0">
                                <input class="custom-control-input" type="checkbox" name="autologomod[]" value="all"{if $AUTOLOG_MOD_TEXT eq 'all'} checked="checked"{/if}><span class="custom-control-label text-truncate" title="{$LANG->get('autologomodall')}"><strong>{$LANG->get('autologomodall')}</strong></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <h4 class="mb-0">{$LANG->get('logosizecaption')}</h4>
                </div>
            </div>
            <div class="card-divider"></div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="upload_logo">{$LANG->get('upload_logo')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="upload_logo" name="upload_logo" value="{$DATA.upload_logo}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-secondary btn-input-sm" type="button" id="upload_logo_select"><i class="icon fas fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="autologosize1">{$LANG->get('imagewith')} &lt;= 150px</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    {$LANG->get('logowith')}
                    <input type="text" class="form-control form-control-sm d-inline-block width-50" id="autologosize1" name="autologosize1" value="{$DATA.autologosize1}">
                    % {$LANG->get('fileimage')}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="autologosize2">{$LANG->get('imagewith')} &gt; 150px, &lt; 350px</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    {$LANG->get('logowith')}
                    <input type="text" class="form-control form-control-sm d-inline-block width-50" id="autologosize2" name="autologosize2" value="{$DATA.autologosize2}">
                    % {$LANG->get('fileimage')}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="autologosize3">{$LANG->get('imagewith')} &gt; = 350px</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    {$LANG->get('logosize3')}
                    <input type="text" class="form-control form-control-sm d-inline-block width-50" id="autologosize3" name="autologosize3" value="{$DATA.autologosize3}">
                    % {$LANG->get('fileimage')}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="upload_logo_pos">{$LANG->get('upload_logo_pos')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="upload_logo_pos" name="upload_logo_pos">
                        {foreach from=$ARRAY_LOGO_POSITION key=key item=value}
                        <option value="{$key}"{if $key eq $UPLOAD_LOGO_POS} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}=upload&amp;js"></script>
<script>
$(document).on("nv.upload.ready", function() {
    $("#upload_logo_select").nvBrowseFile({
        adminBaseUrl: '{$NV_BASE_ADMINURL}',
        path: '/uploads',
        currentpath: '/uploads',
        type: 'file',
        area: '#upload_logo', // Đối tượng trả về đường dẫn => Build ra currentfile
        alt: '#autologosize1', // Đối tượng trả về ALT image
    });
});
</script>
