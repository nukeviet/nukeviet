<!-- BEGIN: main -->
<div class="row">
    <div class="col-24 col-md-20 col-lg-18">
        <form id="strdata" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="checkss" value="{CHECKSS}" />
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="bg-primary">
                        <th colspan="2">
                            {LANG.strdata}
                            <a href="https://developers.google.com/search/docs/appearance/structured-data/search-gallery" target="_blank" title="{LANG.more_information}"><i class="fa fa-question-circle fa-lg text-white" aria-hidden="true"></i></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {LANG.add_sitelinks_search_box_schema}
                            <a class="small" href="https://developers.google.com/search/docs/appearance/structured-data/sitelinks-searchbox" target="_blank" title="{LANG.more_information}"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                        </td>
                        <td class="text-center" style="width: 1%;">
                            <input type="checkbox" class="form-control submit" name="sitelinks_search_box_schema" value="1" {SEARCH_BOX_SCHEMA_CHECKED} />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {LANG.strdata_breadcrumblist}
                            <a class="small" href="https://developers.google.com/search/docs/appearance/structured-data/breadcrumb" target="_blank" title="{LANG.more_information}"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                        </td>
                        <td class="text-center" style="width: 1%;">
                            <input type="checkbox" class="form-control submit" name="breadcrumblist" value="1" {BREADCRUMBLIST_CHECKED} />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {LANG.strdata_localbusiness}
                            <a class="small" href="https://developers.google.com/search/docs/appearance/structured-data/local-business" target="_blank" title="{LANG.more_information}"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                            (<a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;localbusiness_information=1">{LANG.localbusiness_information}</a>)
                        </td>
                        <td class="text-center" style="width: 1%;">
                            <input type="checkbox" class="form-control submit" name="localbusiness" value="1" {LOCALBUSINESS_CHECKED} />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {LANG.strdata_organization}
                            <a class="small" href="https://developers.google.com/search/docs/appearance/structured-data/logo" target="_blank" title="{LANG.more_information}"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                            <div class="help-block">{LANG.strdata_organization_logo_guidelines}</div>
                        </td>
                        <td class="text-center" style="width: 1%;">
                            <img src="{ORGANIZATION_LOGO}" data-default="{ORGANIZATION_LOGO_DEFAULT}" class="pointer" id="organization_logo" alt="" width="112" height="112" />
                            <button type="button" class="btn btn-xs btn-primary" id="organization_logo_del" style="margin-top: 10px;<!-- BEGIN: delbtn -->display:none<!-- END: delbtn -->">{GLANG.delete}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<!-- END: main -->

<!-- BEGIN: logoupload -->
<!-- BEGIN: complete -->
<script>
    $(function() {
        $(window).on("beforeunload", function() {
            $('#organization_logo', opener.document).attr('src', '{FILENAME}');
            $('#organization_logo_del', opener.document).show()
        });
        window.close();
    });
</script>
<!-- END: complete -->
<!-- BEGIN: init -->
<div class="users-av-wraper">
    <form id="upload-form" method="post" enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;logoupload=1">
        <div class="col-xs-16">
            <div class="users-avupload">
                <div title="{LANG.logo_select_img}" id="upload_icon" class="upload-button">
                    <div class="text-center">
                        <span><em class="fa fa-upload"></em></span>
                    </div>
                </div>
                <div class="img-area"><img id="preview" style="display: none;" /></div>
            </div>
        </div>
        <div class="col-xs-8">
            <h2 class="margin-bottom-lg">{LANG.change_logo}</h2>
            <div class="guide" id="guide">
                <div class="margin-bottom"><strong>{LANG.change_logo_guide}:</strong></div>
                <div>- {LANG.change_logo_chosen}</div>
                <div>- {LANG.change_logo_upload}</div>
            </div>
            <div style="display:none" id="uploadInfo">
                <div>- {LANG.filesize}: <span id="image-size"></span></div>
                <div>- {LANG.filetype}: <span id="image-type"></span></div>
                <div>- {LANG.filedimension}: <span id="original-dimension"></span></div>
                <div>- {LANG.displaydimension}: <span id="display-dimension"></span></div>
                <div class="margin-top-lg">
                    <input id="btn-submit" type="submit" class="btn btn-primary btn-sm" value="{LANG.crop}" />
                    <input id="btn-reset" type="button" class="btn btn-primary btn-sm" value="{LANG.chosen_other}" />
                </div>
            </div>
            <div class="exit-bt">
                <button class="btn btn-primary btn-sm" data-toggle="win_close">{GLANG.cancel}</button>
            </div>
        </div>
        <input type="hidden" id="crop_x" name="crop_x" />
        <input type="hidden" id="crop_y" name="crop_y" />
        <input type="hidden" id="crop_width" name="crop_width" />
        <input type="hidden" id="crop_height" name="crop_height" />
        <input type="file" name="image_file" id="image_file" class="hide" accept=".jpg,.jpeg,.png,.webp" />
    </form>
</div>
<script>
    $(function() {
        getFiles(['{ASSETS_STATIC_URL}/js/cropper/cropper.min.css','{ASSETS_STATIC_URL}/js/cropper/cropper.min.js','{NV_STATIC_URL}themes/{TEMPLATE}/js/change_logo.js'], function(){
        UAV.config.maxsize = {NV_UPLOAD_MAX_FILESIZE};
        UAV.config.img_width = {CONFIG.logo_width};
        UAV.config.img_height = {CONFIG.logo_height};
        UAV.lang.bigsize = '{LANG.bigsize}';
        UAV.lang.smallsize = '{LANG.smallsize}';
        UAV.lang.filetype = '{LANG.allowed_type}';
        UAV.lang.bigfile = '{LANG.bigfile}';
        UAV.lang.upload = '{LANG.change_logo_upload}';
        UAV.init()
    });
    <!-- BEGIN: error -->alert('{ERROR}');<!-- END: error -->
    $('[data-toggle=win_close]').on('click', function(e) {
    e.preventDefault();
    window.close()
    })
    });
</script>
<!-- END: init -->
<!-- END: logoupload -->

<!-- BEGIN: lbinf -->
<div class="row">
    <div class="col-24 col-md-20 col-lg-18">
        <form id="localbusiness_info" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;localbusiness_information=1" method="post">
            <div class="m-bottom">
                <textarea class="form-control" name="jsondata" style="height: 500px">{DATA}</textarea>
            </div>
            <div class="text-center">
                <input type="hidden" name="save" value="1"/>
                <button type="submit" class="btn btn-primary">{GLANG.save}</button>
                <button type="button" class="btn btn-default" data-toggle="sample_data" data-url="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">{LANG.localbusiness_reset}</button>
                <button type="button" class="btn btn-default" data-toggle="lbinf_delete" data-confirm="{LANG.localbusiness_del_confirm}" data-url="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">{GLANG.delete}</button>
            </div>
        </form>
    </div>
</div>
<!-- END: lbinf -->
