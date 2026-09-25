{* Điều hướng dạng tab trên màn hình lớn, dạng dropdown trên di động *}
<ul class="nav nav-tabs mb-3 d-none d-md-flex">
    <li class="nav-item"><a class="nav-link" href="{$BASE_URL}">{$LANG->getModule('your_content')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$BASE_URL}&amp;contentid=0">{$LANG->getModule('add_content')}</a></li>
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{$BASE_URL}&amp;author_info=1">{$LANG->getModule('author_info')}</a></li>
</ul>
<div class="dropdown mb-3 d-md-none">
    <button type="button" class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between" data-bs-toggle="dropdown" aria-expanded="false">{$LANG->getModule('author_info')}</button>
    <ul class="dropdown-menu w-100">
        <li><a class="dropdown-item" href="{$BASE_URL}">{$LANG->getModule('your_content')}</a></li>
        <li><a class="dropdown-item" href="{$BASE_URL}&amp;contentid=0">{$LANG->getModule('add_content')}</a></li>
        <li><a class="dropdown-item active" aria-current="page" href="{$BASE_URL}&amp;author_info=1">{$LANG->getModule('author_info')}</a></li>
    </ul>
</div>
<div class="card">
    <div class="card-body">
        <h1 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('author_info')}</h1>
        <form action="{$FORM_ACTION}" method="post" data-toggle="ajax-form" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="newsAuthorPseudonym">{$LANG->getModule('author_pseudonym')} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="newsAuthorPseudonym" name="pseudonym" value="{$DATA.pseudonym}" maxlength="100" placeholder="{$LANG->getModule('author_pseudonym')}" data-valid data-error-type="feedback">
                    <div class="invalid-feedback">{$LANG->getModule('author_pseudonym_empty')}</div>
                </div>
                <div class="col-12">
                    <label class="form-label" for="newsAuthorDescription">{$LANG->getModule('author_description')}</label>
                    <textarea class="form-control" id="newsAuthorDescription" name="description" rows="8" placeholder="{$LANG->getModule('author_description')}">{$DATA.description_br2nl}</textarea>
                </div>
            </div>
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('author_info_save')}</button>
                <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
            </div>
        </form>
    </div>
</div>
