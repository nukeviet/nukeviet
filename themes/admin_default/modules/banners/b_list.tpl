<!-- BEGIN: main -->
<!-- BEGIN: searchform -->
<div class="form-group">
    <form class="form-inline" method="get" action="{FORM_ACTION}">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
        <input type="hidden" name="{NV_OP_VARIABLE}" value="banners_list"/>
        <input type="text" class="w250 form-control" name="q" value="{CONTENTS.keyword}" placeholder="{LANG.enter_keyword}"/>
        <select name="pid" class="w200 form-control">
            <option value="0">{LANG.all_plan}</option>
            <!-- BEGIN: plan -->
            <option value="{PLAN.id}"{PLAN.selected}>{PLAN.title}</option>
            <!-- END: plan -->
        </select>
        <input type="submit" value="{GLANG.search}" class="btn btn-primary"/>
    </form>
</div>
<!-- END: searchform -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption><em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.caption}</caption>
        <!-- BEGIN: nv_banner_weight -->
        <col style="white-space:nowrap" />
        <!-- END: nv_banner_weight -->
        <colgroup>
            <col span="5">
            <col class="w100">
            <col class="w150">
        </colgroup>
        <thead>
            <tr>
                <!-- BEGIN: thead -->
                <th>{THEAD}</th>
                <!-- END: thead -->
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <!-- BEGIN: nv_banner_weight -->
                <td>{ROW.weight}</td>
                <!-- END: nv_banner_weight -->
                <td><a href="{ROW.view}">{ROW.title}</a></td>
                <td><a href="{ROW.pid.0}">{ROW.pid.1}</a></td>
                <td>
                    <!-- BEGIN: user -->
                    <a href="{USER.link}">{USER.username}</a>
                    <!-- END: user -->
                </td>
                <td>{ROW.publ_date}</td>
                <td>{ROW.exp_date}</td>
                <td class="text-center"><input name="{ROW.act.0}" id="{ROW.act.0}" type="checkbox" value="1" onclick="{ROW.act.2}"{ROW.checked}/></td>
                <td>
                    <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.edit}">{CONTENTS.edit}</a> &nbsp;
                    <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="#" id="delete_banners" data-toggle="nv_delete_banner" data-url="{ROW.delfile}">{CONTENTS.del}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<script>
$(function() {
    $('[data-toggle=nv_delete_banner]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url'),
            conf = confirm('{LANG.file_del_confirm}');
        if (conf == true) {
            $.ajax({
                type: 'POST',
                url: url + '&nocache=' + new Date().getTime(),
                data: '',
                success: function(data) {
                    alert(data);
                    location.reload();
                }
            })
        }
    })
})
</script>
<!-- END: main -->