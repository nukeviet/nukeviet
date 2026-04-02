<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link rel="stylesheet" href="{$smarty.const.NV_BASE_SITEURL}themes/admin_future/css/colpick.css">

<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.NV_BASE_SITEURL}themes/admin_future/js/colpick.js"></script>

<form id="groupForm" class="ajax-submit" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}{if !empty($DATA.id)}&amp;edit&amp;id={$DATA.id}{else}&amp;add{/if}" autocomplete="off" novalidate>
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
        <div class="card-header fs-5 fw-medium">{$PAGE_TITLE}</div>
        <div class="card-body">
            {if $SHOW_BASIC_INFO}
            <div class="row mb-3">
                <label for="title" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('title')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input title="{$LANG->getModule('title')}" class="form-control" type="text" name="title" id="title" value="{$DATA.title}" maxlength="240" required autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="alias" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('alias')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input title="{$LANG->getModule('alias')}" class="form-control" type="text" name="alias" id="alias" value="{$DATA.alias}" maxlength="240" autocomplete="off">
                        <button class="btn btn-secondary" type="button" id="get_alias_btn" aria-label="{$LANG->getModule('get_alias')}"><i class="fa-solid fa-refresh" data-icon="fa-refresh"></i></button>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="description" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('group_description')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input title="{$LANG->getModule('group_description')}" class="form-control" type="text" name="description" id="description" value="{$DATA.description}" maxlength="240" autocomplete="off">
                </div>
            </div>

            <div class="row mb-3">
                <label for="exp_time" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('exp_time')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" name="exp_time" id="exp_time" class="form-control fw-150" value="{$DATA.exp_time}" maxlength="10" autocomplete="off">
                                <button type="button" class="btn btn-secondary" data-toggle="focusDate" aria-label="{$LANG->getModule('select_date')}"><i class="fa-solid fa-calendar"></i></button>
                            </div>
                        </div>
                        <div class="col-auto align-self-center">
                            <span class="text-muted">{$LANG->getModule('emptyIsUnlimited')}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="group_type" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('group_type')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" name="group_type" id="group_type" style="width:250px">
                        {foreach from=$GROUP_TYPE_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.selected} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 offset-lg-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_default" value="1" class="form-check-input" role="switch" id="is_default"{if $DATA.is_default} checked{/if}>
                        <label class="form-check-label" for="is_default">{$LANG->getModule('group_is_default')}</label>
                    </div>
                </div>
            </div>

            {if $SHOW_SITEUS}
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 offset-lg-3">
                    <div class="form-check form-switch">
                        <input title="{$LANG->getModule('siteus')}" type="checkbox" name="siteus" value="1" class="form-check-input" role="switch" id="siteus"{if $DATA.siteus} checked{/if}>
                        <label class="form-check-label" for="siteus">{$LANG->getModule('siteus')}</label>
                    </div>
                </div>
            </div>
            {/if}

            <div class="row mb-3">
                <div class="col-sm-4 col-lg-3 text-sm-end fw-medium">{$LANG->getModule('content')}</div>
                <div class="col-sm-8 col-lg-8 col-xxl-8">
                    {$EDITOR_CONTENT}
                </div>
            </div>
            {/if}

            {if $SHOW_EMAIL}
            <div class="row mb-3">
                <label for="email" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('email')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input title="{$LANG->getModule('email')}" class="form-control" id="email" type="email" name="email" value="{$DATA.email}" maxlength="240" autocomplete="email">
                </div>
            </div>
            {/if}

            <div class="row mb-3">
                <label for="group_color" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('group_color')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="row g-2">
                        <div class="col-auto">
                            <input class="form-control" type="text" name="group_color" id="group_color" value="{$DATA.group_color}" maxlength="10" style="width:200px" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <input name="group_color_demo" class="form-control" style="width:50px;{if !empty($DATA.group_color)}background-color:{$DATA.group_color};{/if}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="group_avatar" class="col-sm-4 col-lg-3 col-form-label text-sm-end">{$LANG->getModule('group_avatar')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="group_avatar" name="group_avatar" value="{$DATA.group_avatar}" maxlength="255" autocomplete="off">
                        <button type="button" data-toggle="selectfile" data-target="group_avatar" data-path="{$AVATAR_PATH}" data-currentpath="{$AVATAR_CURENT_PATH}" data-type="image" class="btn btn-info" title="{$LANG->getGlobal('browse_image')}" aria-label="{$LANG->getGlobal('browse_image')}"><i class="fa-solid fa-folder-open"></i></button>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 offset-lg-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="require_2step_admin" value="1" class="form-check-input{if $SHOW_2STEP_ADMIN} checkdefault{/if}" role="switch" id="require_2step_admin"{if $DATA.require_2step_admin} checked{/if}>
                        <label class="form-check-label" for="require_2step_admin">{$LANG->getModule('two_step_verification_require_admin')}</label>
                    </div>
                    {if $SHOW_2STEP_ADMIN}<div class="form-text">{$LANG->getModule('two_step_verification_require_admindefault')}</div>{/if}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-4 offset-lg-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="require_2step_site" value="1" class="form-check-input{if $SHOW_2STEP_SITE} checkdefault{/if}" role="switch" id="require_2step_site"{if $DATA.require_2step_site} checked{/if}>
                        <label class="form-check-label" for="require_2step_site">{$LANG->getModule('two_step_verification_require_site')}</label>
                    </div>
                    {if $SHOW_2STEP_SITE}<div class="form-text">{$LANG->getModule('two_step_verification_require_sitedefault')}</div>{/if}
                </div>
            </div>
        </div>
    </div>

    {if $SHOW_CONFIG}
    <div class="card mb-4">
        <div class="card-header fw-bold"><i class="fa-solid fa-file-text"></i> {$LANG->getModule('access_caption_leader')}</div>
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center text-nowrap">{$LANG->getModule('access_groups_add')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('access_groups_del')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('access_addus')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('access_waiting')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('access_editus')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('access_delus')}</th>
                            <th class="text-center text-nowrap">{$LANG->getModule('access_passus')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><input type="checkbox" value="1" name="access_groups_add"{if $DATA.config.access_groups_add} checked{/if}></td>
                            <td class="text-center"><input type="checkbox" value="1" name="access_groups_del"{if $DATA.config.access_groups_del} checked{/if}></td>
                            <td class="text-center"><input type="checkbox" value="1" name="access_addus"{if $DATA.config.access_addus} checked{/if}></td>
                            <td class="text-center"><input type="checkbox" value="1" name="access_waiting"{if $DATA.config.access_waiting} checked{/if}></td>
                            <td class="text-center"><input type="checkbox" value="1" name="access_editus"{if $DATA.config.access_editus} checked{/if}></td>
                            <td class="text-center"><input type="checkbox" value="1" name="access_delus"{if $DATA.config.access_delus} checked{/if}></td>
                            <td class="text-center"><input type="checkbox" value="1" name="access_passus"{if $DATA.config.access_passus} checked{/if}></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}

    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <input type="hidden" name="save" value="1">
    <div class="text-center"><button name="submit" type="submit" class="btn btn-primary" style="min-width: 150px">{$LANG->getModule('save')}</button></div>
</form>
