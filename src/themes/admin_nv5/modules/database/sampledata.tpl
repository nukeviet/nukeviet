{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{else}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('sampledata_note')}</div>
</div>
{/if}
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header card-header-divider">{$LANG->get('sampledata_creat')}</div>
            <div class="card-body" id="sampledataarea" data-errsys="{$LANG->get('sampledata_error_sys')}" data-init="{$LANG->get('sampledata_dat_init')}">
                <form method="post" autocomplete="off">
                    <input type="hidden" name="delifexists" value="0"/>
                    <div class="form-group row">
                        <label class="col-12 col-sm-4 col-form-label text-sm-right" for="sampledata_name">{$LANG->get('sampledata_name')}</label>
                        <div class="col-12 col-sm-7">
                            <input type="text" class="form-control form-control-sm" id="sampledata_name" name="sample_name" value="" placeholder="{$LANG->get('sampledata_name_rule')}" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group row mb-0 pb-0">
                        <label class="col-12 col-sm-4 col-form-label text-sm-right"></label>
                        <div class="col-12 col-sm-7 col-lg-5">
                            <button class="btn btn-space btn-primary" type="submit"><span class="lo d-none"><i class="fas fa-spin fa-spinner"></i>&nbsp;</span>{$LANG->get('sampledata_start')}</button>
                        </div>
                    </div>
                </form>
                <div id="spdresult">
                    <div id="spdresulttop"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        {if empty($FILES)}
        <div role="alert" class="alert alert-primary alert-dismissible">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="fas fa-info-circle"></i></div>
            <div class="message">{$LANG->get('sampledata_empty')}</div>
        </div>
        {else}
        <div class="card">
            <div class="card-header card-header-divider mb-0 mx-0 px-3">{$LANG->get('sampledata_list')}</div>
            <div class="list-group list-group-flush">
                {foreach from=$FILES item=row}
                <div class="list-group-item">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="flex-grow-1">
                            {$row.title}
                        </div>
                        <div class="flex-grow-0 mx-2">
                            {$row.creattime}
                        </div>
                        <div class="flex-grow-0">
                            <a href="javascript:void(0);" class="text-danger" onclick="nv_delete_sampledata('{$row.title}');"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
        {/if}
    </div>
</div>
