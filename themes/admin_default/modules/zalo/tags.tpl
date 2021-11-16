<!-- BEGIN: main -->
<!-- BEGIN: ifTags -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered tags" data-delete-confirm="{LANG.delete_tag_confirm}">
            <tbody>
                <!-- BEGIN: tag -->
                <tr>
                    <td><input type="text" class="form-control" value="{TAG.name}" data-toggle="auto_update_tag" data-default="{TAG.name}" data-url="{EDIT_LINK}" data-alias="{TAG.alias}"/></td>
                    <td class="text-nowrap" style="width:1%">
                        <a class="btn btn-primary btn-sm" href="{TAG_LINK}&amp;tag={TAG.alias}">{LANG.followers_list}</a>
                        <a class="btn btn-danger btn-sm" href="{FORM_ACTION}" title="{LANG.click_to_delete}" data-toggle="delete_tag" data-tag-alias="{TAG.alias}">{GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: tag -->
            </tbody>
        </table>
    </div>
</div>
<!-- END: ifTags -->
<form method="POST" action="{FORM_ACTION}" class="form-inline" data-toggle="add_tag">
    <div class="form-group">
        <label class="sr-only" for="new_tag">New Tag</label>
        <input type="text" class="form-control" name="new_tag" id="new_tag" value="" placeholder="{LANG.enter_tag_name}" maxlength="50">
    </div>
    <input type="hidden" name="add_tag" value="1"/>
    <button type="submit" class="btn btn-primary">{LANG.add_tag}</button>
</form>
<!-- END: main -->