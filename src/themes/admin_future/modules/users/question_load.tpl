<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{$LANG->getModule('addquestion')}</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <label for="new_title" class="col-sm-2 col-form-label">{$LANG->getModule('question')}:</label>
            <div class="col-sm-7 col-lg-6">
                <input type="text" class="form-control" name="new_title" id="new_title" maxlength="255" autocomplete="off">
            </div>
            <div class="col-sm-3 col-lg-4">
                <button type="button" class="btn btn-primary" id="btn_add_question" data-icon="fa-solid fa-plus">
                    <i class="fa-solid fa-plus" data-icon="fa-solid fa-plus"></i> {$LANG->getModule('addquestion')}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{$LANG->getModule('list_question')}</h5>
    </div>
    <div class="card-body">
        <div id="module_show_list">
            <div class="text-center">
                <img src="{$smarty.const.NV_BASE_SITEURL}{$smarty.const.NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading...">
            </div>
        </div>
    </div>
</div>
