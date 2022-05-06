<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <col class="w400" />
            <tbody>
                <tr>
                    <td><strong>{LANG.static_url}</strong><br /><small>({LANG.not_apply_to_localhost})</small></td>
                    <td>
                        <div class="m-bottom">
                            <input type="text" name="nv_static_url" value="{DATA.nv_static_url}" class="form-control" />
                        </div>
                        {LANG.static_url_note}
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.cdn_url}</strong><br /><small>({LANG.not_apply_to_localhost})</small></td>
                    <td>
                        <div class="cdn-list">
                            <!-- BEGIN: cdn_item -->
                            <div class="item panel <!-- BEGIN: is_default -->panel-primary<!-- END: is_default --><!-- BEGIN: by_country -->panel-info<!-- END: by_country --><!-- BEGIN: is_secondary -->panel-default<!-- END: is_secondary -->">
                                <div class="panel-heading">
                                    <div class="input-group">
                                        <span class="input-group-addon">{LANG.url}</span>
                                        <input type="text" name="cdn_url[]" value="{CDN_URL.val}" class="form-control" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" data-toggle="remove_cdn" title="{LANG.remove_cdn}"><span class="fa fa-remove"></span></button>
                                            <button class="btn btn-default" type="button" data-toggle="add_cdn" title="{LANG.add_cdn}"><span class="fa fa-plus"></span></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <select name="cdn_action[]" class="form-control">
                                                <!-- BEGIN: action -->
                                                <option value="{ACTION.val}" {ACTION.sel}>{ACTION.name}</option>
                                                <!-- END: action -->
                                            </select>
                                        </div>
                                        <div class="col-xs-19">
                                            <div class="input-group">
                                                <span class="input-group-addon">{LANG.countries}</span>
                                                <input type="text" value="{CDN_URL.countries_list}" name="cdn_countries[]" class="form-control cdn_countries" style="background-color: #fff;" readonly />
                                                <div class="input-group-btn dropdown-toggle">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{LANG.select_countries}"><span class="fa fa-globe"></span></button>
                                                    <ul class="countrylist dropdown-menu dropdown-menu-right dropdown-menu-checkbox">
                                                        <li>
                                                            <div class="checkall">
                                                                <button type="button" class="btn btn-xs btn-default" data-toggle="removeall">{LANG.removeall}</button>
                                                            </div>
                                                        </li>
                                                        <!-- BEGIN: country_list -->
                                                        <li>
                                                            <label>
                                                                <input type="checkbox" class="cdn_country" value="{COUNTRY.code}" {COUNTRY.checked}> {COUNTRY.name}
                                                            </label>
                                                        </li>
                                                        <!-- END: country_list -->
                                                        <li>
                                                            <div class="checkall">
                                                                <button type="button" class="btn btn-xs btn-default" data-toggle="removeall">{LANG.removeall}</button>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: cdn_item -->
                        </div>
                        {LANG.cdn_notes}
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.assets_cdn}</strong><br /><small>({LANG.not_apply_to_localhost})</small></td>
                    <td>
                        <input type="checkbox" name="assets_cdn" value="1" {DATA.assets_cdn_checked} /> {DATA.assets_cdn_note}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <input type="hidden" name="checkss" value="{CHECKSS}" />
        <input type="submit" value="{LANG.submit}" class="btn btn-primary" />
    </div>
</form>
<script>
    $(function() {
        $('body').on('click', '.countrylist', function(e) {
            e.stopPropagation()
        });
        $('body').on('click', '[data-toggle=add_cdn]', function(e) {
            e.preventDefault();
            var cdnlist = $(this).parents('.cdn-list'),
                item = $(this).parents('.item'),
                newitem = item.clone();
            $('[name^=cdn_url], [name^=cdn_countries]', newitem).val('');
            $('[name^=cdn_action] option:selected', newitem).prop('selected', false);
            $('.cdn_country', newitem).prop('checked', false);
            newitem.removeClass('panel-primary panel-info').addClass('panel-default');
            newitem.appendTo(cdnlist)
        });
        $('body').on('click', '[data-toggle=remove_cdn]', function(e) {
            e.preventDefault();
            var cdnlist = $(this).parents('.cdn-list'),
                item = $(this).parents('.item');
            if ($('.item', cdnlist).length > 1) {
                item.remove()
            } else {
                $('[name^=cdn_url], [name^=cdn_countries]', item).val('');
                $('[name^=cdn_action] option:selected', item).prop('selected', false);
                $('.cdn_country', item).prop('checked', false)
            }
        });
        $('body').on('change', '.cdn_country', function(e) {
            var item = $(this).parents('.item'),
                clist = $(this).parents('.countrylist'),
                cv = '';
            $('.cdn_country:checked', clist).each(function(e) {
                if (cv != '') {
                    cv += ', ';
                }
                cv += $(this).val()
            });
            $('[name^=cdn_countries]', item).val(cv)
        });
        $('body').on('click', '[data-toggle=removeall]', function(e) {
            e.preventDefault();
            var item = $(this).parents('.item');
            $('.cdn_country:checked', item).prop('checked', false);
            $('[name^=cdn_countries]', item).val('')
        });
        $('body').on('change',  '[name^=cdn_action]', function(e) {
            var cdnlist = $(this).parents('.cdn-list'),
                item = $(this).parents('.item');
            if ($(this).val() == '1') {
                $('.panel-primary', cdnlist).removeClass('panel-primary panel-info').addClass('panel-default');
                item.removeClass('panel-default panel-info').addClass('panel-primary');
                $('[name^=cdn_action] option[value=1]:selected', item.siblings()).prop('selected', false)
            } else if ($(this).val() == '2') {
                item.removeClass('panel-default panel-primary').addClass('panel-info');
            } else {
                item.removeClass('panel-primary panel-info').addClass('panel-default');
            }
        })
    })
</script>
<!-- END: main -->