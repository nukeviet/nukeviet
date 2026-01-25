<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o"></em> {LANG.configlogo}</caption>
            <tbody>
                <tr>
                    <td>{LANG.upload_logo}</td>
                    <td>
                        <div class="w350 input-group mb-0">
                            <input class="form-control" type="text" name="upload_logo" id="upload_logo" value="{AUTOLOGOSIZE.upload_logo}" maxlength="250" />
                            <span class="input-group-btn">
                                <button type="button" data-toggle="selectfile" data-target="upload_logo" data-path="" data-currentpath="images" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.imagewith} &lt; = 150px</td>
                    <td><span class="text-middle pull-left"> {LANG.logowith} &nbsp;</span><input type="text" class="form-control w50 pull-left" value="{AUTOLOGOSIZE.autologosize1}" maxlength="2" name="autologosize1" /><span class="text-middle">&nbsp; % {LANG.fileimage} </span></td>
                </tr>
                <tr>
                    <td>{LANG.imagewith} &gt; 150px, &lt; 350px</td>
                    <td><span class="text-middle pull-left"> {LANG.logowith} &nbsp;</span><input type="text" class="form-control pull-left w50" value="{AUTOLOGOSIZE.autologosize2}" maxlength="2" name="autologosize2" /><span class="text-middle pull-left">&nbsp; % {LANG.fileimage} </span></td>
                </tr>
                <tr>
                    <td>{LANG.imagewith} &gt; = 350px</td>
                    <td><span class="text-middle pull-left"> {LANG.logosize3} &nbsp;</span><input type="text" class="form-control pull-left w50" value="{AUTOLOGOSIZE.autologosize3}" maxlength="2" name="autologosize3" />&nbsp;<span class="text-middle pull-left">&nbsp; % {LANG.fileimage} </span></td>
                </tr>
                <tr>
                    <td>{LANG.upload_logo_pos}</td>
                    <td>
                        <select class="form-control w350" name="upload_logo_pos">
                            <!-- BEGIN: upload_logo_pos -->
                            <option value="{UPLOAD_LOGO_POS.key}" {UPLOAD_LOGO_POS.selected}>{UPLOAD_LOGO_POS.title}</option>
                            <!-- END: upload_logo_pos -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">{LANG.autologo}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <!-- BEGIN: loop1 -->
                        <div class="row">
                            <!-- BEGIN: loop2 -->
                            <div class="col-xs-8"><label><input type="checkbox" name="autologomod[]" value="{MOD_VALUE}" {LEV_CHECKED} /> {CUSTOM_TITLE}</label></div>
                            <!-- END: loop2 -->
                        </div>
                        <!-- END: loop1 -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o"></em> {LANG.otherconfig}</caption>
            <tbody>
                <tr>
                    <td>{LANG.tinify_compress}</td>
                    <td><input type="checkbox" name="tinify_active" value="1" {TINIFY_CHECKED} /> {LANG.tinify_compress_note}
                        <!-- BEGIN: tinify_class -->
                        <p>{LANG.tinify_compress_note2}</p><!-- END: tinify_class -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.tinify_api_key}</td>
                    <td><input type="text" class="form-control d-inline-block w300" name="tinify_api" value="{TINIFY_KEY}" maxlength="50" /> {LANG.tinify_api_key_note}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <input type="hidden" name="save" value="1">
        <input type="submit" value="{LANG.pubdate}" class="btn btn-primary" />
    </div>
</form>
<!--  END: main  -->