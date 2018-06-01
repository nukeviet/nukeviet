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
<!-- BEGIN: loop1 -->
<div id="{CONTAINERID}">
    <p class="text-center">
        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading"/>
    </p>
</div>
<!-- END: loop1 -->
<!-- BEGIN: loop2 -->
<script type="text/javascript">
    {AJ}
</script>
<!-- END: loop2 -->
<!-- END: main -->