<div class="row g-3">
    <form class="col-lg-5" id="clearsystem-form" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" novalidate>
        <ul class="list-group">
            <li class="list-group-item list-group-item-primary">
                <div class="d-flex align-items-center">
                    <div class="fw-medium">{$LANG->getModule('checkContent')}</div>
                    <input type="checkbox" data-toggle="checkAll" class="form-check-input m-0 ps-2 ms-auto" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
            </li>
            {foreach from=$CLEARS item=clear}
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div>{$LANG->getModule($clear)}</div>
                    <input type="checkbox" data-toggle="checkSingle" name="deltype[]" value="{$clear}" class="form-check-input m-0 ps-2 ms-auto" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                </div>
            </li>
            {/foreach}
            <li class="list-group-item text-center">
                <input type="hidden" name="checkss" value="{$CHECKSS}">
                <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
            </li>
        </ul>
    </form>
    <div class="col-lg-7">
        <div id="pload" class="alert alert-info text-center d-none">
            <i class="fa-solid fa-spinner fa-spin-pulse" aria-label="{$LANG->getGlobal('wait_page_load')}"></i>
        </div>
        <ul id="presult" class="list-group d-none">
            <li class="list-group-item list-group-item-success fw-medium">{$LANG->getModule('deletedetail')}:</li>
        </ul>
        <div id="pnoresult" class="alert alert-info text-center d-none" role="alert">
            {$LANG->getModule('no_files_to_delete')}
        </div>
    </div>
</div>
