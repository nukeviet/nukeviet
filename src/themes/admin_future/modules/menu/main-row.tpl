<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="modal fade" id="menuRowModal" tabindex="-1" aria-labelledby="menuRowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post"
                  class="ajax-submit"
                  novalidate
                  action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuRowModalLabel">{$FORM_CAPTION}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                </div>
                <div class="modal-body pt-4">
                    <input type="hidden" name="id" value="{$DATA.id}">
                    <input type="hidden" name="mid" value="{$DATA.mid}">
                    <input type="hidden" name="pa" value="{$DATA.parentid}">
                    <input type="hidden" name="action" value="row">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">

                    <div class="row mb-3">
                        <label for="item_menu_sel" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('name_block')}</label>
                        <div class="col-md-9">
                            <select name="item_menu" id="item_menu_sel" class="form-select"
                                    data-parentid="{$DATA.parentid}">
                                {foreach from=$MENUBLOCKS_OPTIONS item=b}
                                <option value="{$b.key}"{if $b.selected} selected{/if}>{$b.val|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="parentid_sel" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('cats')}</label>
                        <div class="col-md-9">
                            <select name="parentid" id="parentid_sel" class="form-select">
                                {foreach from=$CATS_OPTIONS item=c}
                                <option value="{$c.key}"{if $c.selected} selected{/if}>{$c.title|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 field">
                        <label for="module_name_sel" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('chomodule')}</label>
                        <div class="col-md-9">
                            <select name="module_name" id="module_name_sel" class="form-select">
                                <option value="">{$LANG->getModule('no')}</option>
                                {foreach from=$MODULES_OPTIONS item=m}
                                <option value="{$m.key}"{if $m.selected} selected{/if}>{$m.title|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 field-func{if !$FUNCS_OPTIONS} d-none{/if}">
                        <label for="func_sel" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('cho_module_item')}</label>
                        <div class="col-md-9">
                            <select name="func" id="func_sel" class="form-select">
                                <option value="">{$LANG->getModule('no')}</option>
                                {foreach from=$FUNCS_OPTIONS item=f}
                                <option value="{$f.alias}" data-title="{$f.title|escape}"{if $f.selected} selected{/if}>{$f.name|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="row_title" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('title')} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control required" id="row_title" name="title"
                                       value="{$DATA.title|escape}" maxlength="250" autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" data-toggle="get-title"
                                        data-bs-toggle="tooltip" title="{$LANG->getModule('action_menu_reload')}">
                                    <i class="fa-solid fa-rotate"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="row_link" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('link')}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="row_link" name="link"
                                       value="{$DATA.link}" maxlength="250" autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" data-toggle="get-link"
                                        data-bs-toggle="tooltip" title="{$LANG->getModule('action_menu_reload')}">
                                    <i class="fa-solid fa-rotate"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="icon_input" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('icon')}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="icon_input" name="icon"
                                       value="{$DATA.icon|escape}" maxlength="250" autocomplete="off">
                                <button type="button" class="btn btn-info"
                                        data-toggle="selectfile" data-target="icon_input"
                                        data-path="{$UPLOAD_CURRENT}" data-type="image"
                                        title="{$LANG->getGlobal('browse_image')}">
                                    <i class="fa-solid fa-folder-open"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="image_input" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('image')}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="image_input" name="image"
                                       value="{$DATA.image|escape}" maxlength="250" autocomplete="off">
                                <button type="button" class="btn btn-info"
                                        data-toggle="selectfile" data-target="image_input"
                                        data-path="{$UPLOAD_CURRENT}" data-type="image"
                                        title="{$LANG->getGlobal('browse_image')}">
                                    <i class="fa-solid fa-folder-open"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="note_input" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('note')}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="note_input" name="note"
                                   value="{$DATA.note|escape}" maxlength="250" autocomplete="off">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 col-form-label text-md-end">{$LANG->getModule('groups_view')}</div>
                        <div class="col-md-9">
                            <select name="groups_view[]" class="form-select" multiple>
                                {foreach from=$GROUPS_OPTIONS item=g}
                                <option value="{$g.key}"{if $g.selected} selected{/if}>{$g.title|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="target_sel" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('target')}</label>
                        <div class="col-md-9">
                            <select name="target" id="target_sel" class="form-select">
                                {foreach from=$TARGET_OPTIONS item=t}
                                <option value="{$t.key}"{if $t.selected} selected{/if}>{$t.title|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="active_type_sel" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('add_type_active')}</label>
                        <div class="col-md-9">
                            <select name="active_type" id="active_type_sel" class="form-select">
                                {foreach from=$ACTIVE_TYPE_OPTIONS item=a}
                                <option value="{$a.key}"{if $a.selected} selected{/if}>{$a.title|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="css_input" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('add_type_css')}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="css_input" name="css"
                                   value="{$DATA.css|escape}" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
