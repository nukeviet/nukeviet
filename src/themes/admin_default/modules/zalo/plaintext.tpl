<!-- BEGIN: main -->
<div<!-- BEGIN: popup --> style="padding:10px 15px;background-color:#fff"<!-- END: popup -->>
    <!-- BEGIN: change_template_type -->
    <div class="form-inline m-bottom">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">{LANG.template}:</span>
                <select class="form-control" data-toggle="change_template_type">
                    <option value="" data-url="{MAIN_PAGE}"></option>
                    <!-- BEGIN: loop -->
                    <option value="{LOOP.key}" data-url="{LOOP.url}"{LOOP.sel}>{LOOP.name}</option>
                    <!-- END: loop -->
                </select>
            </div>
        </div>
    </div>
    <!-- END: change_template_type -->
    <div class="m-bottom form-inline">
        <a href="{ADD_LINK}" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> {LANG.template_add}</a>
    </div>
    <div class="row">
        <!-- BEGIN: template -->
        <!-- BEGIN: element3 -->
        </div>
        <div class="row">
        <!-- END: element3 -->
        <div class="col-sm-8">
            <div class="panel panel-primary list" data-idfield="{IDFIELD}" data-parameter="{PARAMETER}" data-clfield="{CLFIELD}">
                <div class="panel-heading">{TEMPLATE.title}</div>
                <div class="panel-body">
                    {TEMPLATE.content}
                </div>
                <div class="panel-footer d-flex">
                    <div>
                        <a href="{EDIT_LINK}{TEMPLATE.id}" class="btn btn-primary btn-sm">{LANG.template_edit}</a>
                        <a href="{DEL_LINK}" class="btn btn-default btn-sm" data-toggle="list_del" data-confirm="{LANG.delete_confirm}" data-id="{TEMPLATE.id}">{GLANG.delete}</a>
                    </div>
                    <!-- BEGIN: select -->
                    <div class="ml-auto">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="list_select" data-id="{TEMPLATE.content}" data-tid="{TEMPLATE.id}">{LANG.list_select}</button>
                    </div>
                    <!-- END: select -->
                </div>
            </div>
        </div>
        <!-- END: template -->
    </div>
</div>
<!-- END: main -->
<!-- BEGIN: add -->
<div<!-- BEGIN: popup --> style="padding:10px 15px;background-color:#fff"<!-- END: popup -->>
    <div class="m-bottom form-inline">
        <a href="{LIST_LINK}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {LANG.template_list}</a>
        <!-- BEGIN: add_bt --><a href="{ADD_LINK}" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> {LANG.template_add}</a><!-- END: add_bt -->
    </div>
    <form method="POST" action="{FORM_ACTION}" data-toggle="list_form_submit">
        <input type="hidden" name="save" value="1" />
        <div class="row">
            <div class="col-sm-8">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="form-group">
                            <label>{LANG.title}</label>
                            <input type="text" class="form-control" name="title" value="{TEMPLATE.title}" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label>{LANG.content}</label>
                            <textarea type="text" class="form-control non-resize auto-resize" name="content" maxlength="2000">{TEMPLATE.content}</textarea>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary btn-sm">{LANG.submit}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$(function() {
    resizeTextArea($('textarea.auto-resize'))
})
</script>
<!-- END: add -->