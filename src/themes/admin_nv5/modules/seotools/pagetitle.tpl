<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <input type="hidden" name="save" value="1" />
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="pageTitleMode">{$LANG->get('pagetitle2')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="pageTitleMode" name="pageTitleMode" value="{$DATA['pageTitleMode']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit" value="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
            <div class="form-group row py-0">
                <div class="col-12">
                    {$LANG->get('pagetitleNote')}
                </div>
            </div>
        </div>
    </div>
</form>
