<form method="post" class="ajax-submit supporter_content" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="row mb-3">
        <label class="col-md-3 col-form-label text-md-end" for="supporter_departmentid">{$LANG->getModule('department_parent')} <span class="text-danger">(*)</span></label>
        <div class="col-md-9">
            <select class="form-select required" id="supporter_departmentid" name="departmentid">
                {foreach from=$DEPARTMENT_OPTIONS item=department}
                <option value="{$department.id}"{if $department.id == $SUPPORTER.departmentid} selected{/if}>{$department.full_name}</option>
                {/foreach}
            </select>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label text-md-end" for="supporter_full_name">{$LANG->getModule('full_name')} <span class="text-danger">(*)</span></label>
        <div class="col-md-9">
            <input type="text" class="form-control required" name="full_name" id="supporter_full_name" value="{$SUPPORTER.full_name}" maxlength="250" autocomplete="name">
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label text-md-end" for="supporter_image">{$LANG->getModule('supporter_avatar')}</label>
        <div class="col-md-9">
            <div class="input-group">
                <input class="form-control" type="text" name="image" value="{$SUPPORTER.image}" id="supporter_image" autocomplete="off">
                <button type="button" class="btn btn-info" data-toggle="selectfile" data-target="supporter_image" data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}" data-currentpath="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}" data-type="image" aria-label="{$LANG->getGlobal('browse_image')}">
                    <i class="fa-solid fa-folder-open"></i>
                </button>
            </div>
            <div class="form-text">{$LANG->getModule('supporter_avatar_note')}</div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label text-md-end" for="supporter_phone">{$LANG->getGlobal('phonenumber')} <span class="text-danger">(*)</span></label>
        <div class="col-md-9">
            <input type="text" class="form-control required" name="phone" id="supporter_phone" value="{$SUPPORTER.phone}" maxlength="250" autocomplete="tel">
            <div class="invalid-feedback"></div>
            <div class="form-text">{$LANG->getGlobal('phone_note_content')}</div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-md-3 col-form-label text-md-end" for="supporter_email">{$LANG->getGlobal('email')}</label>
        <div class="col-md-9">
            <input type="email" class="form-control" name="email" id="supporter_email" value="{$SUPPORTER.email}" maxlength="100" autocomplete="email">
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3 col-form-label text-md-end">{$LANG->getModule('otherContacts')}</div>
        <div class="col-md-9 strs">
            {foreach from=$OTHER_CONTACTS item=other}
            <div class="str d-flex mb-2">
                <div class="row g-2 flex-grow-1">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="other_name[]" value="{$other.name}" placeholder="{$LANG->getModule('otherVar')}" aria-label="{$LANG->getModule('otherVar')}" maxlength="250" autocomplete="off">
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="other_value[]" value="{$other.value}" placeholder="{$LANG->getModule('otherVal')}" aria-label="{$LANG->getModule('otherVal')}" maxlength="250" autocomplete="off">
                    </div>
                </div>
                <div class="text-nowrap ms-2">
                    <button class="btn btn-secondary str_add" type="button" aria-label="{$LANG->getModule('add')}">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class="btn btn-secondary str_del" type="button" aria-label="{$LANG->getModule('del')}">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            {/foreach}
        </div>
    </div>

    <div class="text-end">
        <input type="hidden" name="fc" value="content">
        <input type="hidden" name="id" value="{$SUPPORTER.id}">
        <input type="hidden" name="save" value="1">
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
    </div>
</form>
