<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" name="autocheckupdate" value="1"{if $DATA['autocheckupdate']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('autocheckupdate')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="autoupdatetime">{$LANG->get('updatetime')}</label>
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <select class="form-control form-control-sm" id="autoupdatetime" name="autoupdatetime">
                                {for $key=1 to 100}
                                <option value="{$key}"{if $key eq $DATA['autoupdatetime']} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">{$LANG->get('hour')}</div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit" value="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
