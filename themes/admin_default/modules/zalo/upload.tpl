<!-- BEGIN: main -->
<div<!-- BEGIN: popup --> style="padding:5px 15px;background-color:#fff"<!-- END: popup -->>
    <!-- BEGIN: isType -->
    <div class="form-inline m-bottom">
        <div class="input-group">
            <span class="input-group-addon">{LANG.filter_by_type}:</span>
            <select class="form-control" data-toggle="filter_by_type" data-url="{FORM_ACTION}">
                <option value="">{LANG.no_filter}</option>
                <!-- BEGIN: type -->
                <option value="{TYPE.key}" {TYPE.sel}>{TYPE.name}</option>
                <!-- END: type -->
            </select>
        </div>
    </div>
    <!-- END: isType -->
    <!-- BEGIN: isFiles -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-nowrap"  style="width: 280px;">{LANG.file_name}</th>
                    <th>{LANG.file_desc}</th>
                    <!-- BEGIN: non_popup -->
                    <th class="text-nowrap text-center" style="width:1%">{LANG.file_addtime}</th>
                    <!-- END: non_popup -->
                    <th class="text-nowrap text-center" style="width:1%">{LANG.file_exptime}</th>
                    <th class="text-nowrap text-center" style="width:1%">{LANG.operation}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: file -->
                <tr class="zalo_file" data-idfield="{IDFIELD}" data-textfield="{TEXTFIELD}" data-clfield="{CLFIELD}">
                    <td style="width: 280px;min-width:250px">
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group" style="width: 35px">
                                <!-- BEGIN: non_popup2 -->
                                <button type="button" class="btn btn-primary disabled">
                                    <!-- BEGIN: image --><i class="fa fa-file-image-o" title="{FILE.type} ({FILE.type_name})"></i><!-- END: image -->
                                    <!-- BEGIN: doc --><i class="fa fa-file-word-o" title="{FILE.type} ({FILE.type_name})"></i><!-- END: doc -->
                                    <!-- BEGIN: pdf --><i class="fa fa-file-pdf-o" title="{FILE.type} ({FILE.type_name})"></i><!-- END: pdf -->
                                </button>
                                <!-- END: non_popup2 -->
                                <!-- BEGIN: select2 -->
                                <button type="button" class="btn btn-danger" data-toggle="select_file" data-id="{FILE.id}" title="{LANG.file_select}"><i class="fa fa-location-arrow"></i></button>
                                <!-- END: select2 -->
                            </div>
                            <div class="btn-group" role="group" style="width: auto">
                                <button type="button" class="btn btn-primary" style="text-align: left; overflow:hidden" data-toggle="filepreview" data-type="{FILE.type}" data-file="{FILE.fullname}" data-width="{FILE.width}" data-height="{FILE.height}" data-url="{FORM_ACTION}&amp;file_download=1&amp;id={FILE.id}">{FILE.file}</button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form method="POST" action="{FORM_ACTION}&amp;file_desc_change=1" data-toggle="file_desc">
                            <input type="hidden" name="id" value="{FILE.id}" />
                            <input type="text" name="description" value="{FILE.description}" class="form-control" style="min-width: 150px;" maxlength="250" data-toggle="file_desc_change" data-default="{FILE.description}">
                        </form>
                    </td>
                    <!-- BEGIN: non_popup -->
                    <td class="text-center" style="width:1%">{FILE.addtime}</td>
                    <!-- END: non_popup -->
                    <td class="text-center" style="width:1%">
                        {FILE.exptime}
                    </td>
                    <td class="text-nowrap text-center" style="1%">
                        <select class="form-control" style="width:100px;" data-toggle="file_action_change" data-url="{FORM_ACTION}" data-id="{FILE.id}" data-confirm="{LANG.delete_confirm}">
                            <option value=""></option>
                            <!-- BEGIN: select -->
                            <option value="selfile">{LANG.file_select}</option>
                            <!-- BEGIN: with_desc -->
                            <option value="selfiledesc">{LANG.file_desc_select}</option>
                            <!-- END: with_desc -->
                            <!-- END: select -->
                            <!-- BEGIN: if_expired -->
                            <option value="renewal">{LANG.renewal}</option>
                            <!-- END: if_expired -->
                            <option value="delete">{GLANG.delete}</option>
                        </select>
                    </td>
                </tr>
                <!-- END: file -->
            </tbody>
        </table>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="viewfile" tabindex="-1" role="dialog" aria-labelledby="viewfileModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="viewfileModalLabel">{LANG.viewfile}</h4>
                </div>
                <div class="modal-body">
                    <div class="imgpreview hidden">
                        <img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" width="1" height="1" alt="" />
                    </div>
                    <div class="filepreview hidden"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: isFiles -->

    <div class="row">
        <div class="col-sm-12">
            <form method="POST" action="{FORM_UPLOAD_ACTION}" enctype="multipart/form-data" data-toggle="zalo_upload" data-store-on-server="{STORE_ON_SERVER}">
                <!-- BEGIN: type_hide -->
                <input type="hidden" name="type" value="{TYPE}" data-url="{ZALO_URL}"/>
                <!-- END: type_hide -->
                <div class="panel panel-primary">
                    <div class="panel-heading">{LANG.upload_form}</div>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <!-- BEGIN: type_select -->
                            <tr>
                                <td>{LANG.file_type}</td>
                                <td>
                                    <select name="type" class="form-control" data-toggle="upload_type_change">
                                        <!-- BEGIN: type -->
                                        <option value="{FILE_TYPE.key}" data-accept="{FILE_TYPE.accept}" data-url="{FILE_TYPE.url}" data-maxsize="{FILE_TYPE.maxsize}" {FILE_TYPE.sel}>{FILE_TYPE.name}</option>
                                        <!-- END: type -->
                                    </select>
                                </td>
                            </tr>
                            <!-- END: type_select -->
                            <tr>
                                <td>{LANG.choose_file}</td>
                                <td>
                                    <div class="m-bottom"><input type="file" name="file" accept="{FILE_ACCEPT}" class="form-control" data-mess="{LANG.file_empty}"></div>
                                    <em>{LANG.file_size_not_exceed} <span id="upload_maxsize">{MAX_SIZE}</span></em>
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.file_desc}</td>
                                <td><input type="text" name="description" class="form-control" maxlength="250"></td>
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
<!-- END: main -->
<!-- BEGIN: preview -->
<div class="panel panel-primary">
    <!-- BEGIN: if_img -->
    <div class="panel-image image-9-16">
        <img class="panel-image" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.svg" alt="" style="background-image: url({PREVIEW.localfile});" />
    </div>
    <!-- END: if_img -->
    <table class="table table-striped table-bordered">
        <colgroup>
            <col style="width: 40%"/>
        </colgroup>
        <tbody>
            <!-- BEGIN: file_on_local -->
            <tr>
                <td>{LANG.file_on_site_server}</td>
                <td><a href="{PREVIEW.download}">{LANG.download}</a></td>
            </tr>
            <!-- END: file_on_local -->
            <tr>
                <td>{LANG.file_on_zalo}</td>
                <td>{PREVIEW.file}</td>
            </tr>
            <!-- BEGIN: description -->
            <tr>
                <td>{LANG.text_message_attached}</td>
                <td>{PREVIEW.description}</td>
            </tr>
            <!-- END: description -->
        </tbody>
    </table>
</div>
<!-- END: preview -->