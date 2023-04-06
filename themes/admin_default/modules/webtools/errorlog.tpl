<!-- BEGIN: main -->
<div class="m-bottom">
    <select class="form-control" id="errorfile" data-url="{PAGE_URL}" style="width:fit-content">
        <!-- BEGIN: error_file -->
        <option value="{ERRORFILE.val}" {ERRORFILE.sel}>{ERRORFILE.name}</option>
        <!-- END: error_file -->
    </select>
</div>
<div class="panel-group" id="errorlist" role="tablist" aria-multiselectable="true">
    {ERRORLIST}
</div>
<!-- END: main -->

<!-- BEGIN: errorlist -->
<!-- BEGIN: error -->
<div class="panel panel-primary">
<div class="panel-heading" role="tab" id="heading-{ERROR.id}">
    <h4 class="panel-title">
        <a class="{ERROR.collapsed}" role="button" data-toggle="collapse" data-parent="#errorlist" href="#collapse-{ERROR.id}" aria-expanded="{ERROR.expanded}" aria-controls="collapse-{ERROR.id}">
            {LANG.errorlog_time}: {ERROR.time}
        </a>
    </h4>
</div>
<div id="collapse-{ERROR.id}" class="panel-collapse collapse {ERROR.in}" role="tabpanel" aria-labelledby="heading-{ERROR.id}">
    <table class="table table-striped table-bordered">
        <colgroup>
            <col style="width:1%" />
        </colgroup>
        <tbody>
            <!-- BEGIN: option -->
            <tr>
                <td class="text-nowrap">{OPTION.title}:</td>
                <td>
                    {OPTION.value}
                    <!-- BEGIN: note --><div class="small text-muted">{NOTE}</div><!-- END: note -->
                </td>
            </tr>
            <!-- END: option -->
        </tbody>
    </table>
</div>
</div>
<!-- END: error -->
<!-- END: errorlist -->

<!-- BEGIN: filelist_empty -->
<div class="alert alert-info">{LANG.error_filelist_empty}</div>
<!-- END: filelist_empty -->