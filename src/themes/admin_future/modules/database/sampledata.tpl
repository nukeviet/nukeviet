<div class="alert alert-info" role="alert">{$LANG->getModule('sampledata_note')}</div>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fs-5 fw-medium">
                {$LANG->getModule('sampledata_creat')}
            </div>
            <div class="card-body" id="sampledataarea" data-errsys="{$LANG->getModule('sampledata_error_sys')}" data-init="{$LANG->getModule('sampledata_dat_init')}">
                <form method="post">
                    <input type="hidden" name="delifexists" value="0">
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="element_sample_name">{$LANG->getModule('sampledata_name')} <span class="text-danger">(*)</span></label>
                        <div class="col-12 col-sm-8">
                            <input type="text" name="sample_name" id="element_sample_name" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-8 offset-sm-4">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-play" data-icon="fa-play"></i> {$LANG->getModule('sampledata_start')}</button>
                        </div>
                    </div>
                </form>
                <div id="spdresult" class="d-none pt-3">
                    <div id="spdresulttop" class="d-none"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fs-5 fw-medium">
                {$LANG->getModule('sampledata_list')}
            </div>
            {if not empty($DATA)}
            <div class="card-body p-0 pb-1">
                <ul class="list-group list-group-flush">
                    {foreach from=$DATA item=row}
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-6 fw-medium text-break">{$row.title}</div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-2">{$row.creattime}</div>
                                    <div>
                                        <a href="#" data-toggle="sampDel" data-sname="{$row.title}" data-checkss="{$row.checkss}" class="text-danger" data-bs-toggle="tooltip" data-bs-title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}" data-bs-trigger="hover"><i class="fa-solid fa-trash" data-icon="fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    {/foreach}
                </ul>
            </div>
            {else}
            <div class="card-body">
                <div class="alert alert-info mb-0" role="alert">{$LANG->getModule('sampledata_empty')}</div>
            </div>
            {/if}
        </div>
    </div>
</div>
