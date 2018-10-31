<div class="card card-table">
    <div class="card-header">
        {$LANG->get('module_sys')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('module_name')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('version')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('settime')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('author')}</th>
                        <th style="width: 1%;" class="text-right text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_MODULES item=row}
                    <tr>
                        <td class="text-nowrap">{$row.title}</td>
                        <td class="text-nowrap">{$row.version}</td>
                        <td class="text-nowrap">{$row.addtime}</td>
                        <td class="text-nowrap">{$row.author}</td>
                        <td class="text-right text-nowrap">
                            <a href="{$row.url_setup}" data-title="{$row.title}" class="btn btn-sm btn-secondary nv-setup-module"><i class="icon icon-left far fa-sun"></i> {$LANG->get('setup')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{if not empty($VIRTUAL_MODULES)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('vmodule')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('module_name')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('vmodule_file')}</th>
                        <th style="width: 15%;" class="text-nowrap">{$LANG->get('settime')}</th>
                        <th style="width: 35%;" class="text-nowrap">{$LANG->get('vmodule_note')}</th>
                        <th style="width: 10%;" class="text-right text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$VIRTUAL_MODULES item=row}
                    <tr>
                        <td class="text-nowrap">{$row.title}</td>
                        <td class="text-nowrap">{$row.module_file}</td>
                        <td class="text-nowrap">{$row.addtime}</td>
                        <td class="text-nowrap">{$row.note}</td>
                        <td class="text-right text-nowrap">
                            {if not empty($row.url_setup)}
                            <a href="{$row.url_setup}" data-title="{$row.title}" class="btn btn-sm btn-secondary nv-setup-module"><i class="icon icon-left far fa-sun"></i> {$LANG->get('setup')}</a>
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
<!-- START FORFOOTER -->
<div id="modal-setup-module" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-primary">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">{$LANG->get('setup')}</h3>
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button>
            </div>
            <div class="modal-body">
                <p class="text-info message"></p>
                <div class="form-group row">
                    <label class="col-12 col-sm-7 col-form-label text-sm-right" for="ftp_user_name">{$LANG->get('setup_option')}</label>
                    <div class="col-12 col-sm-4">
                        <select class="form-control form-control-sm option">
                            <option value="0">{$LANG->get('setup_option_0')}</option>
                            <option value="1">{$LANG->get('setup_option_1')}</option>
                        </select>
                    </div>
                </div>
                <div class="checkmodulehook d-none">
                    <p class="text-danger messagehook d-none"></p>
                    <input type="hidden" name="hook_files" value=""/>
                    <div class="hookmodulechoose" id="hookmodulechoose"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit">{$LANG->get('submit')}</button>
                <button type="button" data-dismiss="modal" class="btn btn-secondary">{$LANG->get('cancel')}</button>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
