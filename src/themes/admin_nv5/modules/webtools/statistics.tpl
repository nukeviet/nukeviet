<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="statistics_timezone">{$LANG->get('statistics_timezone')}</label>
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <select class="select2" id="statistics_timezone" name="statistics_timezone">
                        {foreach from=$TIMEZONES item=value}
                        <option value="{$value}"{if $value eq $CONFIG['statistics_timezone']} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" name="online_upd" value="1"{if $CONFIG['online_upd']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('online_upd')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" name="statistic" value="1"{if $CONFIG['statistic']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('statistic')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="googleAnalyticsID">{$LANG->get('googleAnalyticsID')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="googleAnalyticsID" name="googleAnalyticsID" value="{$CONFIG['googleAnalyticsID']}">
                    <span class="form-text text-muted">{$LANG->get('googleAnalyticsID_help')}</span>
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

<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">

<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script>
$(document).ready(function() {
    $(".select2").select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
});
</script>
