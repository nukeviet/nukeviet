<div class="fmd fade" data-dialog="search" id="[prefix]-search" tabindex="-1" aria-labelledby="[prefix]-search-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-search-label">{$LANG->getModule('search')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form>
                    <div class="mb-3">
                        <label for="[prefix]-search-dir" class="form-label">{$LANG->getModule('searchdir')}:</label>
                        <select class="form-select" name="dir" id="[prefix]-search-dir">
                            <option>s</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="[prefix]-search-key" class="form-label">{$LANG->getModule('searchkey')}:</label>
                        <input class="form-control" name="q" type="text" maxlength="200" value="" id="[prefix]-search-key"/>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('search')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="fmd fade" id="[prefix]-add" tabindex="-1" aria-labelledby="[prefix]-add-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-add-label">{$LANG->getModule('upload_manager')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                Ahehe
            </div>
        </div>
    </div>
</div>
