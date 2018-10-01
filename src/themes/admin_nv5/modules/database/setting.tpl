<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="dump_autobackup">{$LANG->get('dump_autobackup')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="dump_autobackup" name="dump_autobackup" value="1"{if $DUMP_AUTOBACKUP} checked="checked"{/if} data-toggle="controlrw"><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="dump_backup_ext">{$LANG->get('dump_backup_ext')}</label>
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <select class="form-control form-control-sm" id="dump_backup_ext" name="dump_backup_ext">
                        {foreach from=$SQL_EXT item=value}
                        <option value="{$value}"{if $value eq $DATA['dump_backup_ext']} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="dump_interval">{$LANG->get('dump_interval')}</label>
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <select class="form-control form-control-sm" id="dump_interval" name="dump_interval">
                                {for $key=1 to 10}
                                <option value="{$key}"{if $key eq $DATA['dump_interval']} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">{$LANG->get('day')}</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="dump_backup_day">{$LANG->get('dump_backup_day')}</label>
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <select class="form-control form-control-sm" id="dump_backup_day" name="dump_backup_day">
                                {for $key=2 to 99}
                                <option value="{$key}"{if $key eq $DATA['dump_backup_day']} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">{$LANG->get('day')}</div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
