<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>{LANG.metaTagsGroupName}</th>
                    <th>{LANG.metaTagsGroupValue} (*)</th>
                    <th>{LANG.metaTagsContent} (**)</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-center"><input type="hidden" name="checkss" value="{CHECKSS}" /><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                    <select name="metaGroupsName[]" class="form-control"{DATA.disabled}>
                        <option value="name"{DATA.n_selected}>name</option>
                        <option value="property"{DATA.p_selected}>property</option>
                        <option value="http-equiv"{DATA.h_selected}>http-equiv</option>
                    </select></td>
                    <td><input class="w200 form-control" type="text" value="{DATA.value}"{DATA.disabled} name="metaGroupsValue[]" /></td>
                    <td><input class="w400 form-control" type="text" value="{DATA.content}"{DATA.disabled} name="metaContents[]" /></td>
                </tr>
                <!-- END: loop -->
                <tr>
                    <td colspan="2" class="text-right">{LANG.metaTagsOgp}</td>
                    <td><input type="checkbox" value="1" name="metaTagsOgp" {METATAGSOGPCHECKED}/></td>
                </tr>
                <tr>
    				<td colspan="2" class="text-right">{LANG.ogp_image}</td>
    				<td><input type="text" class="w300 form-control pull-left" name="ogp_image" id="ogp_image" value="{OGP_IMAGE}" style="margin-right: 10px" /><button data-name="ogp_image" name="ogp_image_select" class="btn btn-default selectimg"><em class="fa fa-folder-open-o">&nbsp;</em>{GLANG.browse_image}</button></td>
    			</tr>
                <tr>
                    <td colspan="2" class="text-right">{LANG.private_site}</td>
                    <td><input type="checkbox" value="1" name="private_site"{PRIVATE_SITE}></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right">{LANG.description_length}</td>
                    <td class="form-inline">
                        <input class="form-control" type="text" value="{DESCRIPTION_LENGTH}" name="description_length" pattern="^[0-9]*$"  oninvalid="setCustomValidity(nv_digits)" oninput="setCustomValidity('')">
                        ({LANG.description_note})
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="well">
        <div class="m-bottom">*: {NOTE}</div>
        <div class="m-bottom">**: {VARS}</div>
        <div>***: {LANG.metaTagsOgpNote}</div>
    </div>
</form>
<!-- END: main -->
