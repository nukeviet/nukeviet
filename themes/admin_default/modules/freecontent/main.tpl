<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="m-bottom">
    <div class="m-bottom text-center">
        <h2>{LANG.usage_note}</h2>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="text-center">
                <span class="icon-step">1</span>
                <p class="text-step">{LANG.usage_s1}</p>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="text-center">
                <span class="icon-step">2</span>
                <p class="text-step">{LANG.usage_s2}</p>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="text-center">
                <span class="icon-step">3</span>
                <p class="text-step">{LANG.usage_s3}</p>
            </div>
        </div>
    </div>
    <hr>
    <p class="text-center">
        <button class="btn btn-primary block-add-trigger">{LANG.block_add}</button>
    </p>
</div>
<div class="alert alert-danger">{LANG.performance_note}</div>
<!-- END: empty -->
<!-- BEGIN: rows -->
<div class="clearfix">
    <button class="btn btn-primary block-add-trigger pull-right">{LANG.block_add}</button>
    <blockquote><em>{LANG.block_list_guide}</em></blockquote>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <col span="2" style="white-space: nowrap;" />
        <col class="w250" />
        <thead>
            <tr>
                <th class="text-center">{LANG.block_title}</th>
                <th class="text-center">{LANG.block_description}</th>
                <th class="text-center">{LANG.tools}</th>
            </tr>
        </thead>
        <tbody id="block-list-container">
            <!-- BEGIN: loop -->
            <tr id="block-row-{ROW.bid}">
                <td><strong><a href="{ROW.link}">{ROW.title}</a></strong></td>
                <td>{ROW.description}</td>
                <td class="text-center">
                    <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="#" class="block-edit" data-bid="{ROW.bid}">{GLANG.edit}</a>
                     &nbsp;
                    <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="#" class="block-delete" data-bid="{ROW.bid}" data-title="{ROW.title}">{GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: rows -->
<div class="modal fade" id="block-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">{LANG.block_add_edit}</h3>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="block-title" class="col-sm-5 control-label">{LANG.block_title}<sup class="required">(*)</sup></label>
                        <div class="col-sm-19">
                            <input type="text" class="form-control txt" id="block-title" name="title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="block-description" class="col-sm-5 control-label">{LANG.block_description}</label>
                        <div class="col-sm-19">
                            <textarea name="description" id="block-description" class="form-control txt"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="bid" id="block-bid" value="" class="txt">
                </form>
            </div>
            <div class="modal-footer">
                <span class="per-loading"> <i class="fa fa-circle-o-notch fa-spin"></i> </span>
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
                <button type="button" class="btn btn-primary block-submit-trigger">{GLANG.save}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="block-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">{LANG.block_delete}</h3>
            </div>
            <div class="modal-body">
                <p class="text-danger confirm">{LANG.block_delete_confirm}</p>
                <p class="text-center loading">
                    <em class="fa fa-circle-o-notch fa-spin fa-2x"></em>
                </p>
                <p class="text-center message"></p>
                <p class="text-center success text-success">
                    <em class="fa fa-check-circle fa-2x"></em>
                </p>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="bid" value="">
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
                <button type="button" class="btn btn-primary block-delete-trigger">{GLANG.delete}</button>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->