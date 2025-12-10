<form method="post" class="row g-3 ajax-submit" action="{$FORM_ACTION}" autocomplete="off" id="role" novalidate>
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2 fw-medium fs-5">
                {$LANG->getModule('api_role_properties')}
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label for="role_title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_roles_title')} <span class="text-danger">(*)</span></label>
                    <div class="col-sm-8 col-lg-4 col-xxl-3">
                        <input type="text" id="role_title" name="role_title" value="{$DATA.role_title}" class="form-control" maxlength="250" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="role_description" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_roles_description')}</label>
                    <div class="col-sm-8 col-lg-4 col-xxl-3">
                        <textarea class="form-control" id="role_description" name="role_description" rows="2" maxlength="250">{$DATA.role_description}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_role_type')}</div>
                    <div class="col-sm-8 col-lg-6 col-xxl-5 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="private" name="role_type" id="role_type_private" {$DATA.role_type_private_checked}>
                            <label class="form-check-label" for="role_type_private"> {$LANG->getModule('api_role_type_private')}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="public" name="role_type" id="role_type_public" {$DATA.role_type_public_checked}>
                            <label class="form-check-label" for="role_type_public"> {$LANG->getModule('api_role_type_public')}</label>
                        </div>
                        <div class="form-text">
                            <ul class="role_note note">
                                <li>{$LANG->getModule('api_role_type_private_note')}</li>
                                <li>{$LANG->getModule('api_role_type_public_note')}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_role_object')}</div>
                    <div class="col-sm-8 col-lg-6 col-xxl-5 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="admin" name="role_object" id="role_object_admin" {$DATA.role_object_admin_checked}>
                            <label class="form-check-label" for="role_object_admin"> {$LANG->getModule('api_role_object_admin')}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="user" name="role_object" id="role_object_user" {$DATA.role_object_user_checked}>
                            <label class="form-check-label" for="role_object_user"> {$LANG->getModule('api_role_object_user')}</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('log_period')}</div>
                    <div class="col-sm-8 col-lg-6 col-xxl-5">
                        <div class="row">
                            <div class="col-md-7 col-xxl-5">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="log_period" value="{$DATA.log_period}" maxlength="10">
                                    <span class="input-group-text border-start-0">{$LANG->getModule('hours')}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-text">{$LANG->getModule('log_period_note')}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label text-sm-end pt-0">{$LANG->getModule('flood_blocker')}</div>
                    <div class="col-sm-8">
                        <div class="d-none d-sm-block border-1 border-transparent border-solid mb-2 px-3">
                            <div class="row g-3 ">
                                <div class="col-5">{$LANG->getModule('flood_limit')}</div>
                                <div class="col-7">{$LANG->getModule('flood_interval')}</div>
                            </div>
                        </div>
                        <div class="border rounded-3 p-3 items vstack gap-3">
                            {if empty($DATA.flood_rules)}
                                {append var='DATA' value=['' => ''] index='flood_rules'}
                            {/if}
                            {assign var="stt" value=0}
                            {foreach $DATA.flood_rules as $INTERVAL => $LIMIT}
                            {assign var="stt" value=$stt+1}
                            <div class="row g-3 flood_rule item">
                                <div class="col-sm-5">
                                    <label for="flood_rules_limit{$stt}" class="lbl_rule form-label d-block d-sm-none">{$LANG->getModule('flood_limit')}:</label>
                                    <input type="number" class="form-control ipt_rule" id="flood_rules_limit{$stt}" name="flood_rules_limit[]" value="{$LIMIT}" maxlength="15">
                                </div>
                                <div class="col-sm-7 d-flex gap-3">
                                    <div class="flex-fill">
                                        <label for="flood_rules_interval{$stt}" class="lbl_interval form-label d-block d-sm-none">{$LANG->getModule('flood_interval')}:</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control ipt_interval" id="flood_rules_interval{$stt}" name="flood_rules_interval[]" value="{if !empty($INTERVAL)}{math equation="round(x / y)" x=$INTERVAL y=60}{/if}" maxlength="10">
                                            <span class="input-group-text">{$LANG->getModule('minutes')}</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 d-flex gap-1 align-items-end">
                                        <div>
                                            <button class="btn text-nowrap btn-secondary del-rule text-success" type="button" aria-label="{$LANG->getGlobal('add')}"><i class="fa-solid fa-minus"></i></button>
                                        </div>
                                        <div>
                                            <button class="btn text-nowrap btn-secondary add-rule text-danger" type="button" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                        <div class="form-text mt-2">{$LANG->getModule('flood_blocker_note')}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-sm-3 d-flex justify-content-sm-start justify-content-center">
                        <input type="hidden" name="checkss" value="{$CHECKSS}">
                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2 fw-medium fs-5">
                <div class="">{$LANG->getModule('apis_list')}</div>
            </div>
            <div id="apicheck" class="card-body">
                <div id="role">
                    {$APICHECK}
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="row gy-3">
                    <div class="col-sm-6">
                        <select name="save" class="form-select" style="display:inline-block;">
                            {foreach $SAVEOPTS as $KEY => $NAME}
                            <option value="{$KEY}">{$NAME}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-sm-start justify-content-center">
                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
