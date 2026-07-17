<!-- BEGIN: main -->
<form id="trusted-proxies-form" class="form-horizontal" method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="alert alert-info">{LANG.trusted_proxy_note}</div>
            <div class="alert alert-warning">{LANG.trusted_proxy_note_strip}</div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="trusted_proxy_enable">{LANG.trusted_proxy_enable}</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="trusted_proxy_enable" name="trusted_proxy_enable" value="1"{DATA.enable_checked}> {LANG.trusted_proxy_enable_des}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="trusted_proxies" class="col-sm-6 control-label">{LANG.trusted_proxy_list}</label>
                <div class="col-sm-18 col-md-14 col-lg-10">
                    <textarea class="form-control" style="font-family: monospace;" id="trusted_proxies" name="trusted_proxies" rows="12" spellcheck="false" placeholder="127.0.0.1&#10;10.0.0.0/8&#10;173.245.48.0/20">{DATA.trusted_proxies}</textarea>
                    <div class="help-block">{LANG.trusted_proxy_list_des}</div>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="fetch_cf" data-loaded-mess="{LANG.trusted_proxy_cf_loaded}">
                        <i class="fa fa-cloud-download" data-icon="fa-cloud-download"></i> {LANG.trusted_proxy_fetch_cf}
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-footer text-center">
            <input type="hidden" name="checkss" value="{CHECKSS}">
            <button type="submit" name="save" value="1" class="btn btn-primary">{GLANG.save}</button>
        </div>
    </div>
</form>
<!-- END: main -->
