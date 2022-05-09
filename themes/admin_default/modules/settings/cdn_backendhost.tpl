<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
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
                            <div class="item form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <label style="margin-bottom: 0"><input type="checkbox" style="background-color:#fff" data-toggle="cdn_default" {CDN_URL.is_default_checked}> {LANG.default}</label>
                                    </span>
                                    <input type="text" name="cdn_url[]" value="{CDN_URL.val}" class="form-control" placeholder="{LANG.url}" />
                                    <input type="hidden" name="cdn_countries[]" value="{CDN_URL.countries}" />
                                    <input type="hidden" name="cdn_is_default[]" value="{CDN_URL.is_default}" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" data-toggle="remove_cdn" title="{LANG.remove_cdn}"><span class="fa fa-remove"></span></button>
                                        <button class="btn btn-default" type="button" data-toggle="add_cdn" title="{LANG.add_cdn}"><span class="fa fa-plus"></span></button>
                                    </span>
                                </div>
                            </div>
                            <!-- END: cdn_item -->
                        </div>
                        {LANG.cdn_notes}
                        <a class="btn btn-default active" href="{CDN_BY_COUNTRY_URL}">{LANG.bycountry}</a>
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
        $('body').on('click', '[data-toggle=add_cdn]', function(e) {
            e.preventDefault();
            var cdnlist = $(this).parents('.cdn-list'),
                item = $(this).parents('.item'),
                newitem = item.clone();
            $('[name^=cdn_url], [name^=cdn_countries]', newitem).val('');
            $('[name^=cdn_is_default]', newitem).val('0');
            $('[data-toggle=cdn_default]', newitem).prop('checked', false);
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
                $('[name^=cdn_is_default]', item).val('0');
                $('[data-toggle=cdn_default]', item).prop('checked', false)
            }
        });
        $('body').on('change', '[data-toggle=cdn_default]', function(e) {
            var item = $(this).parents('.item');
            if ($(this).is(':checked')) {
                $('[name^=cdn_is_default]', item).val('1');
                $('[data-toggle=cdn_default]', item.siblings()).prop('checked', false);
                $('[name^=cdn_is_default]', item.siblings()).val('0');
            } else {
                $('[name^=cdn_is_default]', item).val('0');
            }
        });

    })
</script>
<!-- END: main -->

<!-- BEGIN: by_country -->
<style>
    .c-selected{background-color: #6c757d !important;color: #fff !important;font-weight: 700;}
</style>
<form action="{FORM_ACTION}" method="post" class="row" id="cdn-country">
    <input type="hidden" name="checkss" value="{CHECKSS}" />
    <div class="col-lg-16 table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <th colspan="2">{LANG.bycountry}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: country -->
                <tr class="country <!-- BEGIN: selected -->c-selected<!-- END: selected -->">
                    <td>{COUNTRY.name}</td>
                    <td>
                        <select name="cdn[{COUNTRY.code}]" class="form-control">
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
    </div>
</form>
<script>
    $(function() {
        $('#cdn-country').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action'),
                data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data
            })
        });
        $('[name^=cdn]').on('change', function(e) {
            e.preventDefault();
            if ($(this).val() != '') {
                $(this).parents('.country').addClass('c-selected')
            } else {
                $(this).parents('.country').removeClass('c-selected')
            }
            $(this).parents('form').submit()
        })
    })
</script>
<!-- END: by_country -->