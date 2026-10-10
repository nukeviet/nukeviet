<div class="modal fade" id="menuBlockModal" tabindex="-1" aria-labelledby="menuBlockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post"
                  class="ajax-submit"
                  novalidate
                  action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;action=block{if $DATAFORM.id}&amp;id={$DATAFORM.id}{/if}">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuBlockModalLabel">{$FORM_CAPTION}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="block_title" class="form-label">{$LANG->getModule('name_block')} <span class="text-danger">(*)</span></label>
                        <input type="text" class="form-control required" id="block_title" name="title" value="{$DATAFORM.title}" maxlength="255" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="action_menu" class="form-label">{$LANG->getModule('action_menu')}</label>
                        <select name="action_menu" id="action_menu" class="form-select">
                            <option value=""> </option>
                            <option value="sys_mod">{$LANG->getModule('action_menu_sys_1')}</option>
                            <option value="sys_mod_sub">{$LANG->getModule('action_menu_sys_2')}</option>
                            {if $ACTION_MENU_OPTIONS}
                            <optgroup label="{$LANG->getModule('action_menu_sys_3')}">
                                {foreach from=$ACTION_MENU_OPTIONS item=opt}
                                <option value="{$opt.value}">{$opt.title}</option>
                                {/foreach}
                            </optgroup>
                            {/if}
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="save" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
