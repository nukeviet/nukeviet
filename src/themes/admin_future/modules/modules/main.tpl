{assign var="tblNames" value=[
    'act' => "{$LANG->getModule('caption_actmod')}",
    'deact' => "{$LANG->getModule('caption_deactmod')}",
    'bad' => "{$LANG->getModule('caption_badmod')}"
]}
<div class="vstack gap-4">
    {foreach from=$ARRAY key=tblname item=mods}
    {if not empty($mods)}
    <div class="card">
        <div class="card-header fs-5 fw-medium">{$tblNames[$tblname]}</div>
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('weight')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('module_name')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('custom_title')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('version')}</th>
                            <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getGlobal('activate')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$mods key=mod_title item=mod}
                        <tr>
                            <td>
                                <select data-toggle="changeWeiModule" data-mod="{$mod_title}" name="change_weight_{$mod_title}" id="change_weight_{$mod_title}" class="form-select fw-75">
                                    {foreach from=$WEIGHT_LIST item=weight}
                                    <option value="{$weight}"{if $weight eq $mod.weight} selected{/if}>{$weight}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td class="text-break">
                                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=show&amp;mod={$mod_title}"><i class="fa-solid fa-magnifying-glass"></i> {$mod.title}</a>
                            </td>
                            <td>{$mod.custom_title}</td>
                            <td>{$mod.version}</td>
                            <td class="text-center">
                                <div class="d-inline-flex">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" aria-label="{$LANG->getGlobal('activate')}" data-toggle="changeActModule" data-mod="{$mod_title}" data-checkss="{$mod.act_checkss}" name="change_act_{$mod_title}" id="change_act_{$mod_title}"{if $mod.act and $tblname eq 'act'} checked{/if}{if not $mod.act_allowed and $mod.act} disabled{/if}>
                                    </div>
                                </div>
                            </td>
                            <td class="text-norwap">
                                <div class="d-flex gap-1">
                                    <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;mod={$mod_title}" class="btn text-nowrap btn-sm btn-secondary"><i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}</a>
                                    <button type="button" class="btn text-nowrap btn-sm btn-secondary" data-toggle="recreateModule" data-mod="{$mod_title}"><i class="fa-solid fa-sun" data-icon="fa-sun"></i> {$LANG->getGlobal('recreate')}</button>
                                    {if $mod.del}
                                    <button data-toggle="deleteModule" data-mod="{$mod_title}" data-checkss="{$mod.del_checkss}" type="button" class="btn text-nowrap btn-sm btn-secondary"><i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                                    {/if}
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}
    {/foreach}
</div>
<!-- START FORFOOTER -->
<div class="modal fade" id="modal-reinstall-module" tabindex="-1" aria-labelledby="modal-reinstall-module-lbl" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-submit" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=recreate_mod" novalidate>
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="modal-reinstall-module-lbl">{$LANG->getModule('reinstall_module')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="text-primary message"></div>
                <div class="showoption mt-3">
                    <label class="form-label fw-medium" for="modal-reinstall-module-sel">{$LANG->getModule('reinstall_option')}:</label>
                    <select class="form-select option" name="sample" id="modal-reinstall-module-sel">
                        <option value="0">{$LANG->getModule('reinstall_option_0')}</option>
                        <option value="1">{$LANG->getModule('reinstall_option_1')}</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="mod" value="">
                <input type="hidden" name="checkss" value="">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-sun" data-icon="fa-sun"></i> {$LANG->getGlobal('submit')}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> {$LANG->getGlobal('cancel')}</button>
            </div>
        </form>
    </div>
</div>
<!-- END FORFOOTER -->
