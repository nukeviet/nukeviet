<!-- BEGIN: main -->
<div class="page">
    <div class="panel-group" id="accordion-settings" role="tablist" aria-multiselectable="true">
        <div class="panel panel-primary">
            <a class="panel-heading" role="tab" id="settings-sector-heading" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#settings-sector" aria-expanded="true" aria-controls="settings-sector">
                {LANG.general_settings}
            </a>
            <div id="settings-sector" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="settings-sector-heading">
                <form action="{FORM_ACTION}" method="post" id="cdn-settings" class="form-horizontal ajax-submit" data-callback="country_cdn_list_load">
                    <div class="alert alert-info mb-0" style="border-radius:0">
                        {LANG.not_apply_to_localhost}
                    </div>
                    <ul class="list-group type2n1 mb-0">
                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.static_url}</strong></label>
                                <div class="col-sm-14">
                                    <input type="text" name="nv_static_url" value="{DATA.nv_static_url}" class="form-control" />
                                    <div class="help-block mb-0">{LANG.static_url_note}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.cdn_url}</strong></label>
                                <div class="col-sm-14">
                                    <div class="cdn-list">
                                        <!-- BEGIN: cdn_item -->
                                        <div class="item form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <label style="margin-bottom: 0"><input type="checkbox" style="background-color:#fff" data-toggle="cdn_default" {CDN_URL.is_default_checked}> {LANG.default}</label>
                                                </span>
                                                <input type="text" name="cdn_url[]" value="{CDN_URL.val}" class="form-control" placeholder="{LANG.url}" />
                                                <input type="hidden" name="cdn_countries[]" value="{CDN_URL.countries}" />
                                                <input type="hidden" name="cdn_is_default[]" value="{CDN_URL.is_default}" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" data-toggle="add_cdn" title="{LANG.add_cdn}"><span class="fa fa-plus"></span></button>
                                                    <button class="btn btn-default" type="button" data-toggle="remove_cdn" title="{LANG.remove_cdn}"><span class="fa fa-remove"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- END: cdn_item -->
                                    </div>
                                    <div class="help-block">{LANG.cdn_notes}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.assets_cdn}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" name="assets_cdn" value="1" {DATA.assets_cdn_checked} />
                                        <strong class="visible-xs-inline-block">{LANG.assets_cdn}</strong>
                                    </label>
                                    <div class="help-block mb-0">{DATA.assets_cdn_note}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item text-center">
                            <input type="hidden" name="checkss" value="{CHECKSS}" />
                            <input type="submit" value="{LANG.submit}" class="btn btn-primary" />
                        </li>
                    </ul>
                </form>
            </div>
        </div>

        <div class="panel panel-primary">
            <a id="country-cdn-sector-heading" class="panel-heading collapsed" style="display: block;text-decoration:none" role="tab" data-toggle="collapse" data-parent="#accordion-settings" href="#country-cdn-sector" aria-expanded="false" aria-controls="country-cdn-sector">
                {LANG.bycountry}
            </a>
            <div id="country-cdn-sector" class="panel-collapse collapse" role="tabpanel" aria-labelledby="country-cdn-sector-heading" data-loaded="false" data-url="{CDN_BY_COUNTRY_URL}"></div>
        </div>
    </div>
</div>
<!-- END: main -->

<!-- BEGIN: by_country -->
<form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="cdn-country">
    <input type="hidden" name="checkss" value="{CHECKSS}" />
    <table class="table table-striped table-bordered mb-0">
        <tbody>
            <!-- BEGIN: country -->
            <tr class="country <!-- BEGIN: selected -->c-selected<!-- END: selected -->">
                <td style="width: 50%;">{COUNTRY.name}</td>
                <td>
                    <select name="ccdn[{COUNTRY.code}]" class="form-control">
                        <option value="">{LANG.by_default}</option>
                        <!-- BEGIN: cdn -->
                        <option value="{CDN.key}" {CDN.sel}>{CDN.url}</option>
                        <!-- END: cdn -->
                    </select>
                </td>
            </tr>
            <!-- END: country -->
        </tbody>
    </table>
</form>
<!-- END: by_country -->