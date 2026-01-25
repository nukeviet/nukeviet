<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card mb-3">
        <div class="card-header fw-medium fs-5 py-2">{$LANG->getModule('configlogo')}</div>
        <div class="card-body">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="upload_logo">{$LANG->getModule('upload_logo')}</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input class="form-control" type="text" name="upload_logo" id="upload_logo" value="{$AUTOLOGOSIZE.upload_logo}" maxlength="250">
                        <button type="button" data-toggle="selectfile" data-target="upload_logo" data-path="" data-currentpath="images" data-type="image" class="btn btn-secondary" title="{$LANG->getGlobal('browse_image')}" aria-label="{$LANG->getGlobal('browse_image')}"><i class="fa-regular fa-folder-open"></i></button>
                    </div>
                    <div class="form-text">{$LANG->getModule('upload_logo_ext', $LOGO_EXTS|join:', ')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="autologosize1">{$LANG->getModule('imagewith')} &lt; = 150px</label>
                <div class="col-sm-8">
                    <div class="hstack gap-1 align-items-center">
                        <div>{$LANG->getModule('logowith')}</div>
                        <input type="number" min="0" max="99" class="form-control fw-75" value="{$AUTOLOGOSIZE.autologosize1}" id="autologosize1" name="autologosize1">
                        <div>% {$LANG->getModule('fileimage')}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="autologosize2">{$LANG->getModule('imagewith')} &gt; 150px, &lt; 350px</label>
                <div class="col-sm-8">
                    <div class="hstack gap-1 align-items-center">
                        <div>{$LANG->getModule('logowith')}</div>
                        <input type="number" min="0" max="99" class="form-control fw-75" value="{$AUTOLOGOSIZE.autologosize2}" id="autologosize2" name="autologosize2">
                        <div>% {$LANG->getModule('fileimage')}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="autologosize3">{$LANG->getModule('imagewith')} &gt; = 350px</label>
                <div class="col-sm-8">
                    <div class="hstack gap-1 align-items-center">
                        <div>{$LANG->getModule('logosize3')}</div>
                        <input type="number" min="0" max="99" class="form-control fw-75" value="{$AUTOLOGOSIZE.autologosize3}" id="autologosize3" name="autologosize3">
                        <div>% {$LANG->getModule('fileimage')}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="upload_logo_pos">{$LANG->getModule('upload_logo_pos')}</label>
                <div class="col-sm-8">
                    <select name="upload_logo_pos" id="upload_logo_pos" class="form-select w-auto mw-100">
                        {foreach from=$LOGO_POSITION key=key item=value}
                        <option value="{$key}"{if $key eq $GCONFIG.upload_logo_pos} selected{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end pt-0">{$LANG->getModule('autologo')}</div>
                <div class="col-sm-8">
                    <div class="row g-2">
                        {foreach from=$SITE_MODS key=mod item=minfo}
                        {if is_dir("`$smarty.const.NV_UPLOADS_REAL_DIR`/`$minfo.module_upload`")}
                        <div class="col-6 col-sm-4 col-lg-3 col-xxl-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="autologomod[]" value="{$mod}" id="autologomod_{$mod}"{if in_array($mod, $AUTOLOGOMOD, true)} checked{/if}>
                                <label class="form-check-label" for="autologomod_{$mod}">
                                    {$minfo.custom_title}
                                </label>
                            </div>
                        </div>
                        {/if}
                        {/foreach}
                        <div class="col-6 col-sm-4 col-lg-3 col-xxl-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="autologomod[]" value="all" id="autologomod__all"{if $GCONFIG.autologomod eq 'all'} checked{/if}>
                                <label class="form-check-label fw-bold" for="autologomod__all">
                                    {$LANG->getModule('autologomodall')}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('pubdate')}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header fw-medium fs-5 py-2">{$LANG->getModule('otherconfig')}</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tinify_active" value="1"{if $GCONFIG.tinify_active} checked{/if} id="tinify_active">
                        <label class="form-check-label" for="tinify_active">{$LANG->getModule('tinify_compress')}</label>
                    </div>
                    <div class="form-text">
                        {$LANG->getModule('tinify_compress_note')}
                        {if $NO_TINIFY}<span class="fw-medium">{$LANG->getModule('tinify_compress_note2')}</span>{/if}
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="tinify_api">{$LANG->getModule('tinify_api_key')}</label>
                <div class="col-sm-8 col-xl-6 col-xxl-5">
                    <input type="text" name="tinify_api" value="{$GCONFIG.tinify_api}" id="tinify_api" class="form-control">
                    <div class="form-text">{$LANG->getModule('tinify_api_key_note')}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('pubdate')}</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
</form>
