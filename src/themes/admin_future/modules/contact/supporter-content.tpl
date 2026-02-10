<form action="{$FORM_ACTION}" method="post" class="ajax-submit" novalidate data-prevalidate="on">
    <div class="row g-3">
        <div class="col-12">
            <label for="departmentid" class="form-label">{$LANG->getModule('department_parent')}</label>
            <select class="form-select" name="departmentid" id="departmentid">
                <option value="0">{$LANG->getModule('department_empty')}</option>
                {foreach from=$DEPARTMENTS item=department}
                <option value="{$department.id}"{if $SUPPORTER.departmentid == $department.id} selected{/if}>{$department.full_name}</option>
                {/foreach}
            </select>
        </div>

        <div class="col-12">
            <label for="full_name" class="form-label">{$LANG->getModule('full_name')} <span class="text-danger">(*)</span></label>
            <input type="text" class="form-control" name="full_name" id="full_name" value="{$SUPPORTER.full_name}" maxlength="250" required autocomplete="name" />
        </div>

        <div class="col-12">
            <label for="image" class="form-label">{$LANG->getModule('supporter_avatar')}</label>
            <div class="input-group">
                <input class="form-control" type="text" name="image" value="{$SUPPORTER.image}" id="image" autocomplete="off" />
                <button class="btn btn-secondary" data-toggle="selectfile" data-target="image" data-path="{$MODULE_UPLOAD}" data-type="image" type="button">
                    <i class="fa-regular fa-folder-open"></i>
                </button>
                <button class="btn btn-secondary help-show" type="button">
                    <i class="fa-regular fa-circle-question"></i>
                </button>
            </div>
            <div class="help-block text-muted small mt-1" style="display: none;">{$LANG->getModule('supporter_avatar_note')}</div>
        </div>

        <div class="col-12">
            <label for="phone" class="form-label">{$LANG->getGlobal('phonenumber')} <span class="text-danger">(*)</span></label>
            <div class="input-group">
                <input type="text" class="form-control" name="phone" id="phone" value="{$SUPPORTER.phone}" maxlength="250" required autocomplete="tel" />
                <button class="btn btn-secondary help-show" type="button">
                    <i class="fa-regular fa-circle-question"></i>
                </button>
            </div>
            <div class="help-block text-muted small mt-1" style="display: none;">{$LANG->getGlobal('phone_note_content')}</div>
        </div>

        <div class="col-12">
            <label for="email" class="form-label">{$LANG->getGlobal('email')}</label>
            <input type="email" class="form-control" name="email" id="email" value="{$SUPPORTER.email}" maxlength="100" autocomplete="email" />
        </div>

        <div class="col-12">
            <label class="form-label">{$LANG->getModule('otherContacts')}</label>
            <div class="strs">
                {foreach from=$SUPPORTER.others key=name item=value}
                <div class="str d-flex mb-2">
                    <div class="row flex-grow-1 g-2">
                        <div class="col-5">
                            <input type="text" class="form-control" name="other_name[]" value="{$name}" placeholder="{$LANG->getModule('otherVar')}" maxlength="250" autocomplete="off" />
                        </div>
                        <div class="col-7">
                            <input type="text" class="form-control" name="other_value[]" value="{$value}" placeholder="{$LANG->getModule('otherVal')}" maxlength="250" autocomplete="off" />
                        </div>
                    </div>
                    <div class="ms-2">
                        <button class="btn btn-secondary str_add" type="button" title="{$LANG->getGlobal('add')}">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <button class="btn btn-secondary str_del" type="button" title="{$LANG->getGlobal('delete')}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <input type="hidden" name="fc" value="content">
        <input type="hidden" name="id" value="{$SUPPORTER.id}">
        <input type="hidden" name="save" value="1">
        <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
    </div>
</form>
