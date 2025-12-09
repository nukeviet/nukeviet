<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="element_rss_logo" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('rss_logo')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input class="form-control" type="text" name="rss_logo" id="element_rss_logo" value="{$DATA.rss_logo}">
                        <button type="button" data-toggle="selectfile" data-target="element_rss_logo" data-path="{$UPLOADS_DIR_USER}" data-type="image" class="btn btn-secondary" title="{$LANG->getGlobal('browse_image')}"><i class="fa-regular fa-folder-open"></i></button>
                    </div>
                    <div class="form-text">{$LANG->getModule('rss_logo_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_atom_logo" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('atom_logo')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input class="form-control" type="text" name="atom_logo" id="element_atom_logo" value="{$DATA.atom_logo}">
                        <button type="button" data-toggle="selectfile" data-target="element_atom_logo" data-path="{$UPLOADS_DIR_USER}" data-type="image" class="btn btn-secondary" title="{$LANG->getGlobal('browse_image')}"><i class="fa-regular fa-folder-open"></i></button>
                    </div>
                    <div class="form-text">{$LANG->getModule('atom_logo_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="feeds_contents" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('content')}</label>
                <div class="col-sm-9">
                    {$DATA.contents}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9 offset-sm-3">
                    <input type="hidden" name="save" value="1">
                    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('save')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
