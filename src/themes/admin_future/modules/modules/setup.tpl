<div class="vstack gap-4">
    {if not empty($MODULES)}
    <div class="card">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('module_sys')}</div>
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getModule('weight')}</th>
                            <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('module_name')}</th>
                            <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('version')}</th>
                            <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('settime')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('author')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="stt" value=1}
                        {foreach from=$MODULES item=mod}
                        <tr>
                            <td class="text-center">
                                {$stt++}
                            </td>
                            <td class="text-break">
                                {$mod.title}
                            </td>
                            <td>{$mod.version}</td>
                            <td>{$mod.addtime}</td>
                            <td class="text-break">{$mod.author}</td>
                            <td class="text-norwap">
                                <button type="button" class="btn text-nowrap btn-sm btn-primary" data-toggle="setupModule" data-mod="{$mod.title}" data-link="{$mod.url_setup}"><i class="fa-solid fa-sun" data-icon="fa-sun"></i> {$LANG->getModule('setup')}</button>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}
    {if not empty($VMODULES)}
    <div class="card">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('vmodule')}</div>
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getModule('weight')}</th>
                            <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('module_name')}</th>
                            <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('vmodule_file')}</th>
                            <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('settime')}</th>
                            <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('vmodule_note')}</th>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getGlobal('actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="stt" value=1}
                        {foreach from=$VMODULES item=mod}
                        <tr>
                            <td class="text-center">
                                {$stt++}
                            </td>
                            <td class="text-break">
                                {$mod.title}
                            </td>
                            <td>{$mod.module_file}</td>
                            <td>{$mod.addtime}</td>
                            <td>{$mod.note}</td>
                            <td class="text-norwap">
                                {if not empty($mod.url_setup)}
                                <button type="button" class="btn text-nowrap btn-sm btn-primary" data-toggle="setupModule" data-mod="{$mod.title}" data-link="{$mod.url_setup}"><i class="fa-solid fa-sun" data-icon="fa-sun"></i> {$LANG->getModule('setup')}</button>
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}
    {if empty($MODULES) and empty($VMODULES)}
    <div class="alert alert-info" role="alert">{$LANG->getModule('setup_no_module')}</div>
    {/if}
</div>
{if not empty($AUTOSETUP)}<div data-toggle="autosetupModule" data-mod="{$AUTOSETUP}" class="d-none"></div>{/if}
<!-- START FORFOOTER -->
<div class="modal fade" id="modal-setup-module" tabindex="-1" aria-labelledby="modal-setup-module-lbl" aria-hidden="true">
    <div class="modal-dialog">
        <form data-toggle="setupModuleForm" class="modal-content" method="post" action="" data-mod="" novalidate>
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="modal-setup-module-lbl">{$LANG->getModule('setup')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="vstack gap-3">
                    <div class="sample d-none">
                        <div class="text-primary message mb-2"></div>
                        <label class="form-label" for="modal-setup-module-sel">{$LANG->getModule('setup_option')}:</label>
                        <select class="form-select option" id="modal-setup-module-sel">
                            <option value="0">{$LANG->getModule('setup_option_0')}</option>
                            <option value="1">{$LANG->getModule('setup_option_1')}</option>
                        </select>
                    </div>
                    <div class="checkmodulehook d-none">
                        <div class="text-danger messagehook d-none"></div>
                        <input type="hidden" name="hook_files" value="">
                        <div class="hookmodulechoose vstack gap-3" id="hookmodulechoose"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary submit"><i class="fa-solid fa-sun" data-icon="fa-sun"></i> {$LANG->getGlobal('submit')}</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> {$LANG->getGlobal('cancel')}</button>
            </div>
        </form>
    </div>
</div>
<!-- END FORFOOTER -->
