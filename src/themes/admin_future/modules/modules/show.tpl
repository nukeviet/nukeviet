<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap">{$LANG->getModule('funcs_subweight')}</th>
                        <th class="text-nowrap text-center">{$LANG->getModule('funcs_in_submenu')}</th>
                        <th class="text-nowrap">{$LANG->getModule('funcs_title')}</th>
                        {if $MODULE_VERSION.virtual}
                        <th class="text-nowrap">{$LANG->getModule('funcs_alias')}</th>
                        {/if}
                        <th class="text-nowrap">{$LANG->getModule('custom_title')}</th>
                        <th class="text-nowrap">{$LANG->getModule('site_title')}</th>
                        <th class="text-nowrap">{$LANG->getModule('description')}</th>
                        <th class="text-nowrap">{$LANG->getModule('funcs_layout')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ACT_FUNCS key=func_name item=funcs}
                    {if $funcs.show_func}
                    <tr>
                        <td>
                            <select data-toggle="changeWeiFunc" data-func-id="{$funcs.func_id}" name="change_weight_{$funcs.func_id}" id="change_weight_{$funcs.func_id}" class="form-select fw-75">
                                {foreach from=$WEIGHT_LIST item=weight}
                                <option value="{$weight}"{if $weight eq $funcs.subweight} selected{/if}>{$weight}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" aria-label="{$LANG->getModule('funcs_in_submenu')}" data-toggle="changeMenuFunc" data-func-id="{$funcs.func_id}" name="chang_func_in_submenu_{$funcs.func_id}" id="chang_func_in_submenu_{$funcs.func_id}"{if not empty($funcs.in_submenu)} checked{/if}{if not in_array($func_name, $IN_SUBMENU)} disabled{/if}>
                                </div>
                            </div>
                        </td>
                        <td class="text-break">{$func_name}</td>
                        {if $MODULE_VERSION.virtual}
                        <td class="text-break">
                            {if $func_name neq 'main' and in_array($func_name, $CHANGE_ALIAS)}
                            <a href="#" data-toggle="changeValFunc" data-func-id="{$funcs.func_id}" data-mode="change_alias"><i class="fa-solid fa-pencil" data-icon="fa-pencil"></i> {$funcs.alias}</a>
                            {else}
                            {$funcs.alias}
                            {/if}
                        </td>
                        {/if}
                        <td><a href="#" data-toggle="changeValFunc" data-func-id="{$funcs.func_id}" data-mode="change_custom_name"><i class="fa-solid fa-pencil" data-icon="fa-pencil"></i> {$funcs.func_custom_name}</a></td>
                        <td><a href="#" data-toggle="changeValFunc" data-func-id="{$funcs.func_id}" data-mode="change_site_title"><i class="fa-solid fa-pencil" data-icon="fa-pencil"></i> {$funcs.func_site_title}</a></td>
                        <td><a href="#" data-toggle="changeValFunc" data-func-id="{$funcs.func_id}" data-mode="change_description"><i class="fa-solid fa-pencil" data-icon="fa-pencil"></i> {$funcs.description ?: $LANG->getModule('empty')}</a></td>
                        <td>{$funcs.layout}</td>
                    </tr>
                    {/if}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- START FORFOOTER -->
<div class="modal fade" id="funChange" tabindex="-1" role="dialog" aria-labelledby="funChange-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="funChange-label"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <form class="modal-body" data-toggle="changeValFuncForm">
                <label for="funChange-name" class="form-label fw-medium" id="funChange-title"></label>
                <input type="text" class="form-control" name="newvalue" value="" maxlength="" id="funChange-name">
                <input type="hidden" name="type" value="" id="funChange-type">
                <input type="hidden" name="id" value="" id="funChange-id">
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
