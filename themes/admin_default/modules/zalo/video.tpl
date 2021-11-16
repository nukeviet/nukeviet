<!-- BEGIN: main -->
<div<!-- BEGIN: popup --> style="padding:10px 15px;background-color:#fff"<!-- END: popup -->>
    <!-- BEGIN: isFiles -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-nowrap" style="width:1%">{LANG.video_id}</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.video_thumb}</th>
                    <th>{LANG.file_name} ({LANG.file_desc})</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.file_status}</th>
                    <th style="width:1%"></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: file -->
                <tr class="zalo_file" data-idfield="{IDFIELD}" data-viewfield="{VIEWFIELD}" data-thumbfield="{THUMBFIELD}">
                    <td class="text-nowrap text-center" style="width:1%">
                        <button type="button" class="btn btn-primary btn-block m-bottom">{FILE.video_id}</button>
                        <small>({FILE.addtime_format})</small>
                    </td>
                    <td class="text-nowrap text-center" style="width:1%">
                        <!-- BEGIN: thumb -->
                        <button class="btn btn-default" type="button" data-toggle="viewimg" data-img="{FILE.thumb}"><i class="fa fa-picture-o"></i></button>
                        <!-- END: thumb -->
                    </td>
                    <td>{FILE.video_name}<br/>({FILE.description})<br/>{LANG.video_view}: {FILE.view_format}<br/>{LANG.file_size}: {FILE.size_format}</td>
                    <td class="text-nowrap text-center" style="width:1%">
                        <div class="m-bottom">{FILE.status_message}</div>
                        <!-- BEGIN: status_check --><button type="button" class="btn btn-danger btn-block btn-sm" data-toggle="video_check" data-url="{FORM_ACTION}" data-id="{FILE.id}">{LANG.status_check}</button><!-- END: status_check -->
                    </td>
                    <td class="text-nowrap text-center" style="width:1%">
                        <div class="m-bottom">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="video_edit_btn" data-id="{FILE.id}" data-view="{FILE.view}" data-thumb="{FILE.thumb}" data-description="{FILE.description}">{GLANG.edit}</button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="file_delete" data-confirm="{LANG.delete_confirm}" data-url="{FORM_ACTION}" data-id="{FILE.id}">{LANG.delete}</button>
                        </div>
                        <!-- BEGIN: select --><div class="text-right"><button type="button" class="btn btn-primary btn-sm select-zalo-file" data-toggle="select_zalo_video" data-video-id="{FILE.video_id}" data-view="{FILE.view}" data-thumb="{FILE.thumb}"{FILE.disabled}>{LANG.file_select}</button></div><!-- END: select -->
                    </td>
                </tr>
                <!-- END: file -->
            </tbody>
        </table>
    </div>
<!-- BEGIN: status_check_Modal -->
<div class="modal fade video-list" id="status_check_Modal" tabindex="-1" role="dialog" aria-labelledby="status_check_Label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="status_check_Label">{LANG.status_check_title}</h4>
      </div>
      <div class="modal-body">{STATUS_CHECK.note}</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-toggle="video_check" data-url="{FORM_ACTION}" data-id="{STATUS_CHECK.id}">{LANG.status_check}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
    $('#status_check_Modal').modal({
        backdrop: false
    }).modal('show')
})
</script>
<!-- END: status_check_Modal -->
    <!-- END: isFiles -->
    <div class="row">
        <div class="col-sm-12">
            <form method="POST" action="{FORM_ACTION}" enctype="multipart/form-data" data-toggle="zalo_video" data-url="{ZALO_URL}">
                <div class="panel panel-primary">
                    <div class="panel-heading">{LANG.video_add}</div>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <td>{LANG.choose_file}</td>
                                <td>
                                    <div class="m-bottom"><input type="file" name="file" accept=".avi,.mp4" class="form-control" data-mess="{LANG.file_empty}"></div>
                                    <em>{LANG.file_size_not_exceed} 50MB</em>
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.video_view}</td>
                                <td>
                                    <select name="view" class="form-control">
                                        <option value="horizontal">{LANG.video_view_horizontal}</option>
                                        <option value="vertical">{LANG.video_view_vertical}</option>
                                        <option value="square">{LANG.video_view_square}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.video_thumb}</td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="thumb" id="video_thumb" value="" maxlength="250">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" data-toggle="video_thumb" data-area="video_thumb" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.file_desc}</td>
                                <td><textarea name="description" class="form-control keypress_submit non-resize" maxlength="250" data-mess="{LANG.description_empty}"></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <button type="submit" class="btn btn-primary">{LANG.submit}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="viewimg" tabindex="-1" role="dialog" aria-labelledby="viewimgModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="viewimgModalLabel">{LANG.viewimg}</h4>
            </div>
            <div class="modal-body viewimg"><img border="0" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" alt="" /></div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="video_edit" tabindex="-1" role="dialog" aria-labelledby="video_editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="video_editModalLabel">{LANG.video_edit}</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="{FORM_ACTION}" data-toggle="video_edit">
                    <input type="hidden" name="edit" value="1" />
                    <input type="hidden" name="id" value="" />
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <td>{LANG.video_view}</td>
                                <td>
                                    <select name="view" class="form-control">
                                        <option value="horizontal">{LANG.video_view_horizontal}</option>
                                        <option value="vertical">{LANG.video_view_vertical}</option>
                                        <option value="square">{LANG.video_view_square}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.video_thumb}</td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="thumb" id="modal_video_thumb" value="" maxlength="250">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" data-toggle="video_thumb" data-area="modal_video_thumb" data-upload-dir="{NV_UPLOADS_DIR}/zalo"><i class="fa fa-picture-o"></i></button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.file_desc}</td>
                                <td><input type="text" name="description" class="form-control" maxlength="250" data-mess="{LANG.description_empty}"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <button type="submit" class="btn btn-primary">{LANG.submit}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->