<!-- BEGIN: main -->
<div class="form-group">
    <form class="form-inline" method="get" action="{FORM_ACTION}">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
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
<div class="all-list" data-pid="{CONTENTS.pid}" data-keyword="{CONTENTS.keyword}">
    <!-- BEGIN: loop -->
    <div class="list m-bottom" id="{LIST.key}">
        <a href="#" data-toggle="nv_show_banners_list" data-act="{LIST.act}"><em class="fa fa-file-text-o"></em>&nbsp;{LIST.title} <span class="label label-danger">{LIST.num}</span></a>
    </div>
    <!-- END: loop -->
</div>
<script>
$(function(){
    $('[data-toggle=nv_show_banners_list]').on('click', function(e) {
        e.preventDefault();
        nv_show_banners_list($(this).parents('.list').attr('id'), 0, $(this).parents('.all-list').data('pid'), $(this).data('act'), $(this).parents('.all-list').data('keyword'));
    })
})
</script>
<!-- END: main -->