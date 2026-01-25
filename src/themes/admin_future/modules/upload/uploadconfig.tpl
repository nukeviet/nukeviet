<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            {if $smarty.const.NV_IS_GODADMIN and $GCONFIG.idsite eq 0}
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('nv_max_width_height')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="hstack gap-2 align-items-center">
                        <input type="number" min="0" max="9999" class="form-control fw-100" id="element_nv_max_width" name="nv_max_width" value="{$smarty.const.NV_MAX_WIDTH}">
                        <span>x</span>
                        <input type="number" min="0" max="9999" class="form-control fw-100" id="element_nv_max_height" name="nv_max_height" value="{$smarty.const.NV_MAX_HEIGHT}">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="nv_auto_resize" value="1"{if $GCONFIG.nv_auto_resize} checked{/if} id="element_nv_auto_resize">
                        <label class="form-check-label" for="element_nv_auto_resize">{$LANG->getModule('nv_auto_resize')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="nv_mobile_mode_img">{$LANG->getModule('nv_mobile_mode_img')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="number" min="0" max="999" class="form-control fw-100" id="nv_mobile_mode_img" name="nv_mobile_mode_img" value="{$smarty.const.NV_MOBILE_MODE_IMG}">
                    <div class="form-text">{$LANG->getModule('nv_mobile_mode_img_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="nv_max_size">{$LANG->getModule('nv_max_size')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select w-auto mw-100" name="nv_max_size" id="nv_max_size">
                        {for $index=100 to 1 step -1}
                        {assign var="size" value=floor($index * $STEP_SIZE)}
                        <option value="{$size}"{if $size eq $GCONFIG.nv_max_size} selected{/if}>{$size|dsize}</option>
                        {/for}
                    </select>
                    <div class="form-text">{$LANG->getModule('sys_max_size')} <strong>{$SYS_MAX_SIZE|dsize}</strong></div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="upload_checking_mode">{$LANG->getModule('upload_checking_mode')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select w-auto mw-100" name="upload_checking_mode" id="upload_checking_mode">
                        {foreach from=$CHECKING_MODE key=key item=value}
                        <option value="{$key}" data-description="{$LANG->getModule("`$key`_mode_note")}"{if $key eq $GCONFIG.upload_checking_mode} selected{/if}>{$value}</option>
                        {/foreach}
                    </select>
                    {if not $SUPPORTED_STRONG}
                    <div class="form-text"><strong class="text-danger">{$LANG->getModule('upload_checking_note')}</strong></div>
                    {/if}
                    <div class="form-text" data-toggle="note">{$LANG->getModule("`$GCONFIG.upload_checking_mode`_mode_note")}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="upload_alt_require" value="1"{if $GCONFIG.upload_alt_require} checked{/if} id="upload_alt_require">
                        <label class="form-check-label" for="upload_alt_require">{$LANG->getModule('upload_alt_require')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="upload_auto_alt" value="1"{if $GCONFIG.upload_auto_alt} checked{/if} id="upload_auto_alt">
                        <label class="form-check-label" for="upload_auto_alt">{$LANG->getModule('upload_auto_alt')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="upload_chunk_size">{$LANG->getModule('upload_chunk')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="hstack gap-2">
                        <input type="number" min="0" max="99999" name="upload_chunk_size" id="upload_chunk_size" value="{$UPLOAD_CHUNK_SIZE}" class="form-control fw-100">
                        <select class="form-select fw-100" name="upload_chunk_size_text">
                            <option value="KB"{if KB eq $UPLOAD_CHUNK_SIZE_TEXT} selected{/if}>KB</option>
                            <option value="MB"{if MB eq $UPLOAD_CHUNK_SIZE_TEXT} selected{/if}>MB</option>
                        </select>
                    </div>
                    <div class="form-text">{$LANG->getModule('upload_chunk_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="nv_overflow_size">{$LANG->getModule('upload_overflow')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="hstack gap-2">
                        <input type="number" min="0" max="999" name="nv_overflow_size" id="nv_overflow_size" value="{$UPLOAD_OVERFLOW_SIZE}" class="form-control fw-100">
                        <select class="form-select fw-100" name="nv_overflow_size_text">
                            <option value="MB"{if 'MB' eq $UPLOAD_OVERFLOW_SIZE_TEXT} selected{/if}>MB</option>
                            <option value="GB"{if 'GB' eq $UPLOAD_OVERFLOW_SIZE_TEXT} selected{/if}>GB</option>
                        </select>
                    </div>
                    <div class="form-text">{$LANG->getModule('upload_overflow_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end pt-0">{$LANG->getModule('uploadconfig_types')}</div>
                <div class="col-sm-8">
                    <div class="row g-2">
                        {foreach from=$MYINI.types key=key item=value}
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="type[]" value="{$key}"{if in_array($value, $GCONFIG.file_allowed_ext, true)} checked{/if} id="uploadconfig_types_{$key}">
                                <label class="form-check-label" for="uploadconfig_types_{$key}">{$value}</label>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end pt-0">{$LANG->getModule('uploadconfig_ban_ext')}</div>
                <div class="col-sm-8">
                    <div data-nv-toggle="scroll" class="scrollmimes">
                        <div class="row g-0">
                            {foreach from=$MYINI.exts key=key item=value}
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ext[]" value="{$key}"{if in_array($value, $GCONFIG.forbid_extensions, true)} checked{/if} id="uploadconfig_ban_{$key}">
                                    <label class="form-check-label" for="uploadconfig_ban_{$key}">{$value}</label>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end pt-0">{$LANG->getModule('uploadconfig_ban_mime')}</div>
                <div class="col-sm-8">
                    <div data-nv-toggle="scroll" class="scrollmimes">
                        <div class="row g-0">
                            {foreach from=$MYINI.mimes key=key item=value}
                            <div class="col-lg-6 py-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mime[]" value="{$key}"{if in_array($value, $GCONFIG.forbid_mimes, true)} checked{/if} id="uploadconfig_forbid_{$key}">
                                    <label class="form-check-label text-break" for="uploadconfig_forbid_{$key}">{$value}</label>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="show_folder_size" value="1"{if $GCONFIG.show_folder_size} checked{/if} id="show_folder_size">
                        <label class="form-check-label" for="show_folder_size">{$LANG->getModule('show_folder_size')}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="save" value="1">
                    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
