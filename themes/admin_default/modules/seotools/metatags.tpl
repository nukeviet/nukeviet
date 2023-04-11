<!-- BEGIN: main -->
<form id="metatags-manage" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th class="text-nowrap text-center" style="width: 1%;">{LANG.metaTagsGroupName}</th>
                    <th class="text-nowrap text-center" style="width: 1%;">{LANG.metaTagsGroupValue} (*)</th>
                    <th colspan="2">{LANG.metaTagsContent} (**)</th>
                </tr>
            </thead>
            <tbody class="items">
                <!-- BEGIN: loop -->
                <tr class="item">
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <select name="metaGroupsName[]" class="form-control w100" {DATA.disabled}>
                            <option value="name" {DATA.n_selected}>name</option>
                            <option value="property" {DATA.p_selected}>property</option>
                            <option value="http-equiv" {DATA.h_selected}>http-equiv</option>
                        </select>
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <div class="input-group">
                            <input class="form-control w200" type="text" value="{DATA.value}" {DATA.disabled} name="metaGroupsValue[]" />
                            <div class="input-group-btn dropdown metaGroupsValue-dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right metaGroupsValue-opt"></ul>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input class="form-control" type="text" value="{DATA.content}" {DATA.disabled} name="metaContents[]" />
                            <div class="input-group-btn dropdown metaContents-dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right metaContents-opt">
                                    <!-- BEGIN: metaContents_list -->
                                    <li><a class="metacontent" href="#">{ITEM}</a></li>
                                    <!-- END: metaContents_list -->
                                </ul>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <button type="button" class="btn btn-default del-meta-tag"><em class="fa fa-minus"></em></button>
                        <button type="button" class="btn btn-default add-meta-tag"><em class="fa fa-plus"></em></button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <div class="well mb-0">
                            <div class="m-bottom">*: {NOTE}</div>
                            <div class="m-bottom">**: {VARS}</div>
                            <div>***: {LANG.metaTagsOgpNote}</div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th colspan="2">{LANG.metaTagsSettings}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-right">{LANG.metaTagsOgp}</td>
                    <td><input type="checkbox" value="1" name="metaTagsOgp" {METATAGSOGPCHECKED} /></td>
                </tr>
                <tr>
                    <td class="text-right">{LANG.ogp_image}</td>
                    <td>
                        <div class="w300 input-group mb-0">
                            <input class="form-control" type="text" name="ogp_image" id="ogp_image" value="{OGP_IMAGE}" maxlength="250" />
                            <span class="input-group-btn">
                                <button type="button" data-toggle="selectfile" data-target="ogp_image" data-path="" data-currentpath="images" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">{LANG.private_site}</td>
                    <td><input type="checkbox" value="1" name="private_site" {PRIVATE_SITE}></td>
                </tr>
                <tr>
                    <td class="text-right">{LANG.description_length}</td>
                    <td class="form-inline">
                        <input class="form-control number" type="text" value="{DESCRIPTION_LENGTH}" name="description_length" pattern="^[0-9]*$" oninvalid="setCustomValidity(nv_digits)" oninput="setCustomValidity('')">
                        ({LANG.description_note})
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <input type="hidden" name="checkss" value="{CHECKSS}" /><input type="submit" value="{LANG.submit}" class="btn btn-primary" />
    </div>
</form>
<div id="meta-name-list" class="hide">
    <!-- BEGIN: meta_name_list -->
    <li><a class="groupvalue" href="#">{ITEM}</a></li>
    <!-- END: meta_name_list -->
</div>
<div id="meta-property-list" class="hide">
    <!-- BEGIN: meta_property_list -->
    <li><a class="groupvalue" href="#">{ITEM}</a></li>
    <!-- END: meta_property_list -->
</div>
<div id="meta-http-equiv-list" class="hide">
    <!-- BEGIN: meta_http_equiv_list -->
    <li><a class="groupvalue" href="#">{ITEM}</a></li>
    <!-- END: meta_http_equiv_list -->
</div>
<!-- END: main -->