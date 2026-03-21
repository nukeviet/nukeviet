<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div id="getuidcontent" class="p-3">
    <form id="formgetuid" method="get"
          action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;area={$AREA}&amp;filtersql={$FILTERSQL}">
        <input type="hidden" name="area" value="{$AREA}">
        <input type="hidden" name="return" value="{$RETURN}">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{$LANG->getModule('pagetitle')}</h5>
            </div>
            <div class="card-body pb-2">
                <div class="alert alert-info py-2 mb-3">{$LANG->getModule('enter_key')}</div>
                <div class="row g-3">
                    <div class="col-6 col-sm-3">
                        <label for="f_username" class="form-label">{$LANG->getGlobal('username')}</label>
                        <input type="text" class="form-control form-control-sm" id="f_username" name="username" value="" maxlength="100" autocomplete="off">
                    </div>
                    <div class="col-6 col-sm-3">
                        <label for="f_full_name" class="form-label">{$LANG->getModule('fullname')}</label>
                        <input type="text" class="form-control form-control-sm" id="f_full_name" name="full_name" value="" maxlength="100" autocomplete="off">
                    </div>
                    <div class="col-6 col-sm-3">
                        <label for="f_email" class="form-label">{$LANG->getModule('email')}</label>
                        <input type="text" class="form-control form-control-sm" id="f_email" name="email" value="" maxlength="100" autocomplete="off">
                    </div>
                    <div class="col-6 col-sm-3">
                        <label for="f_gender" class="form-label">{$LANG->getModule('gender')}</label>
                        <select id="f_gender" name="gender" class="form-select form-select-sm">
                            <option value="">{$LANG->getModule('select_gender')}</option>
                            <option value="M">{$LANG->getModule('select_gender_male')}</option>
                            <option value="F">{$LANG->getModule('select_gender_female')}</option>
                        </select>
                    </div>
                </div>
                <div id="search_other" class="row g-3 mt-0 d-none">
                    <div class="col-6 col-sm-3">
                        <label for="f_sig" class="form-label">{$LANG->getModule('sig')}</label>
                        <input type="text" class="form-control form-control-sm" id="f_sig" name="sig" value="" maxlength="100" autocomplete="off">
                    </div>
                    <div class="col-6 col-sm-3">
                        <label for="f_last_ip" class="form-label">{$LANG->getModule('last_idlogin')}</label>
                        <input type="text" class="form-control form-control-sm" id="f_last_ip" name="last_ip" value="" maxlength="100" autocomplete="off">
                    </div>
                    <div class="col-6 col-sm-6">
                        <div class="form-label">{$LANG->getModule('regdate')}</div>
                        <div class="d-flex gap-1">
                            <input type="text" class="form-control form-control-sm datepicker-get" name="regdatefrom" id="regdatefrom" placeholder="{$LANG->getModule('from')}" maxlength="100" autocomplete="off">
                            <input type="text" class="form-control form-control-sm datepicker-get" name="regdateto" id="regdateto" placeholder="{$LANG->getModule('to')}" maxlength="100" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-6 col-sm-6">
                        <div class="form-label">{$LANG->getModule('last_login')}</div>
                        <div class="d-flex gap-1">
                            <input type="text" class="form-control form-control-sm datepicker-get" name="last_loginfrom" id="last_loginfrom" placeholder="{$LANG->getModule('from')}" maxlength="100" autocomplete="off">
                            <input type="text" class="form-control form-control-sm datepicker-get" name="last_loginto" id="last_loginto" placeholder="{$LANG->getModule('to')}" maxlength="100" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <button type="button" id="btn_toggle_other" class="btn btn-sm btn-secondary">
                    {$LANG->getModule('another_option')}
                </button>
                <div>
                    <button type="reset" class="btn btn-sm btn-secondary">{$LANG->getModule('reset')}</button>
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-sm btn-primary">{$LANG->getModule('search')}</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="resultdata"></div>
