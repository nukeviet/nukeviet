<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('nv_max_width_height')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-inline-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="nv_max_width" name="nv_max_width" value="{$NV_MAX_WIDTH}" maxlength="4">
                        </div>
                        <div class="px-1">x</div>
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="nv_max_height" name="nv_max_height" value="{$NV_MAX_HEIGHT}" maxlength="4">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="nv_auto_resize" name="nv_auto_resize" value="1"{if $CONFIG['nv_auto_resize']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('nv_auto_resize')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="nv_max_size">{$LANG->get('nv_max_size')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="nv_max_size" name="nv_max_size">
                        {for $lkey=0 to 99}
                        {assign var="size" value=((100-$lkey) * $VAL_MAX_SIZE)|floor}
                        <option value="{$size}"{if $size eq $CONFIG.nv_max_size} selected="selected"{/if}>{$size|bytesToText}</option>
                        {/for}
                    </select>
                    <span class="form-text text-muted">({$LANG->get('sys_max_size')}: {$SYS_MAX_SIZE})</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="upload_checking_mode">{$LANG->get('upload_checking_mode')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="upload_checking_mode" name="upload_checking_mode">
                        {foreach from=$CHECKING_MODE key=key item=value}
                        <option value="{$key}"{if $key eq $CONFIG.upload_checking_mode} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                    {if not $SUPPORT_UPLOAD_CHECKING}
                    <span class="form-text text-muted">{$LANG->get('upload_checking_note')}</span>
                    {/if}
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="upload_alt_require" name="upload_alt_require" value="1"{if $CONFIG['upload_alt_require']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('upload_alt_require')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="upload_auto_alt" name="upload_auto_alt" value="1"{if $CONFIG['upload_auto_alt']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('upload_auto_alt')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('upload_chunk')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-inline-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-0 mr-1">
                            <input type="text" class="form-control form-control-sm" id="upload_chunk_size" name="upload_chunk_size" value="{$UPLOAD_CHUNK_SIZE}">
                        </div>
                        <div class="flex-grow-1 flex-shrink-0">
                            <select class="form-control form-control-sm" id="upload_chunk_size_text" name="upload_chunk_size_text">
                                {foreach from=$CHUNK_SIZE key=key item=value}
                                <option value="{$value}"{if $value eq $UPLOAD_CHUNK_SIZE_TEXT} selected="selected"{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <span class="form-text text-muted">{$LANG->get('upload_chunk_help')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right pt-1">{$LANG->get('uploadconfig_types')}</label>
                <div class="col-12 col-sm-9 col-lg-9 pt-0">
                    <div class="row">
                        {foreach from=$INI.types key=key item=value}
                        <div class="col-4 col-sm-4 col-md-3 col-lg-2">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1 w-100 mr-0">
                                <input class="custom-control-input" type="checkbox" name="type[]" value="{$key}"{if in_array($value, $CONFIG.file_allowed_ext)} checked="checked"{/if}><span class="custom-control-label text-truncate" title="{$value}">{$value}</span>
                            </label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right pt-1">{$LANG->get('uploadconfig_ban_ext')}</label>
                <div class="col-12 col-sm-9 col-lg-9 pt-0">
                    <div class="row">
                        {foreach from=$INI.exts key=key item=value}
                        <div class="col-4 col-sm-4 col-md-3 col-lg-2">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1 w-100 mr-0">
                                <input class="custom-control-input" type="checkbox" name="ext[]" value="{$key}"{if in_array($value, $CONFIG.forbid_extensions)} checked="checked"{/if}><span class="custom-control-label text-truncate" title="{$value}">{$value}</span>
                            </label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right pt-1">{$LANG->get('uploadconfig_ban_mime')}</label>
                <div class="col-12 col-sm-9 col-lg-9 pt-0">
                    <div class="row">
                        {foreach from=$INI.mimes key=key item=value}
                        <div class="col-12 col-sm-12 col-md-6">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1 w-100 mr-0">
                                <input class="custom-control-input" type="checkbox" name="mime[]" value="{$key}"{if in_array($value, $CONFIG.forbid_mimes)} checked="checked"{/if}><span class="custom-control-label text-truncate" title="{$value}">{$value}</span>
                            </label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
