<!-- BEGIN: main -->
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="header">
            <!-- BEGIN: head_td -->
            <th><a href="{HEAD_TD.href}">{HEAD_TD.title}</a></th>
            <!-- END: head_td -->
            <td style="text-align: center"><strong>{LANG.admin_permissions}</strong></td>
            <td>&nbsp;</td>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: xusers -->
        <tr>
            <td>{CONTENT_TD.userid}</td>
            <td>
                <!-- BEGIN: is_admin -->
                <img style="vertical-align: middle;" alt="{CONTENT_TD.level}" title="{CONTENT_TD.level}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{CONTENT_TD.img}.png" width="38" height="18" />
            <!-- END: is_admin -->{CONTENT_TD.username}
            </td>
            <td>{CONTENT_TD.full_name}</td>
            <td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
            <td>{CONTENT_TD.admin_module_cat}</td>
            <td class="text-center">
                <!-- BEGIN: is_edit --> <em class="fa fa-edit fa-lg">&nbsp;</em><a href="{EDIT_URL}">{LANG.admin_edit}</a> <!-- END: is_edit -->
            </td>
        </tr>
        <!-- END: xusers -->
    </tbody>
</table>
<!-- BEGIN: edit -->
<form method="post" enctype="multipart/form-data" action="">
    <table class="table table-striped table-bordered table-hover">
        <caption>{CAPTION_EDIT}</caption>
        <tr>
            <td>{LANG.admin_permissions}</td>
            <td>
                <!-- BEGIN: admin_module --> <input name="admin_module" value="{ADMIN_MODULE.value}" type="radio"{ADMIN_MODULE.checked}> {ADMIN_MODULE.text} <!-- END: admin_module -->
            </td>
        </tr>
        <tbody style="" id="id_admin_module">
            <tr>
                <td colspan="2">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">{LANG.content_cat}</th>
                                <th class="text-center" id="check_add_content">{LANG.permissions_add_content}</th>
                                <th class="text-center" id="check_edit_content">{LANG.permissions_edit_content}</th>
                                <th class="text-center" id="check_del_content">{LANG.permissions_del_content}</th>
                                <th class="text-center" id="check_admin_content">{LANG.permissions_admin}</th>
                            </tr>
                        </thead>
                        <!-- BEGIN: catid -->
                        <tbody>
                            <tr>
                                <td>{CONTENT.title}</td>
                                <td class="text-center"><input type="checkbox" name="add_content[]" value="{CONTENT.catid}"{CONTENT.checked_add_content}></td>
                                <td class="text-center"><input type="checkbox" name="edit_content[]" value="{CONTENT.catid}"{CONTENT.checked_edit_content}></td>
                                <td class="text-center"><input type="checkbox" name="del_content[]" value="{CONTENT.catid}"{CONTENT.checked_del_content}></td>
                                <td class="text-center"><input type="checkbox" name="admin_content[]" value="{CONTENT.catid}"{CONTENT.checked_admin}></td>
                            </tr>
                        </tbody>
                        <!-- END: catid -->
                    </table>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center"><input type="submit" value="{LANG.save}" name="submit"></td>
            </tr>
        </tfoot>
    </table>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#check_add_content").one("dblclick", check_add_first);
        $("#check_app_content").one("dblclick", check_app_first);
        $("#check_pub_content").one("dblclick", check_pub_first);
        $("#check_edit_content").one("dblclick", check_edit_first);
        $("#check_del_content").one("dblclick", check_del_first);
        $("#check_admin_content").one("dblclick", check_admin_first);
        $("input[name=admin_module]").click(function() {
            var type = $(this).val();
            if (type == 0) {
                $("#id_admin_module").show();
            } else {
                $("#id_admin_module").hide();
            }
        });
    });
</script>
<!-- END: edit -->
<!-- END: main -->
<!-- BEGIN: view_user -->
<table class="table table-striped table-bordered table-hover">
    <caption>{CAPTION_EDIT}</caption>
    <thead>
        <tr class="header" class="text-center">
            <td>{LANG.content_cat}</td>
            <td>{LANG.permissions_add_content}</td>
            <td>{LANG.permissions_edit_content}</td>
            <td>{LANG.permissions_del_content}</td>
            <td>{LANG.permissions_admin}</td>
        </tr>
    </thead>
    <!-- BEGIN: catid -->
    <tbody>
        <tr>
            <td>{CONTENT.title}</td>
            <td class="text-center">{CONTENT.checked_add_content}</td>
            <td class="text-center">{CONTENT.checked_edit_content}</td>
            <td class="text-center">{CONTENT.checked_del_content}</td>
            <td class="text-center">{CONTENT.checked_admin}</td>
        </tr>
    </tbody>
    <!-- END: catid -->
</table>
<!-- END: view_user -->