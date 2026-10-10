<h1 class="h3 mb-3"><i class="fa-solid fa-users-gear text-primary"></i> {$LANG->getModule('group_edit')}</h1>


<form action="{$FORM_ACTION}" method="post" data-toggle="ajax-form" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="group_title">{$LANG->getModule('group_title')} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="group_title" name="group_title"
                        placeholder="{$LANG->getModule('group_title')}" value="{$DATA.title}" maxlength="240"
                        data-valid data-error-type="feedback"
                        data-error-mess="{$LANG->getModule('group_title_empty')}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="group_desc">{$LANG->getModule('group_desc')}</label>
                    <input type="text" class="form-control" id="group_desc" name="group_desc"
                        placeholder="{$LANG->getModule('group_desc')}" value="{$DATA.description}" maxlength="240">
                </div>
                <div class="col-12">
                    <div class="form-label">{$LANG->getModule('group_content')}</div>
                    {$DATA.htmlbodyhtml}
                </div>
            </div>
        </div>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="checkss" value="{$DATA.checkss}">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
            </button>
        </div>
    </div>
</form>
