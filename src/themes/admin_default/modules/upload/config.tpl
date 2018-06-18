<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o"></em> {LANG.autologo}</caption>
            <tbody>
                <!-- BEGIN: loop1 -->
                <tr>
                    <!-- BEGIN: loop2 -->
                    <td><input type="checkbox" name="autologomod[]" value="{MOD_VALUE}" {LEV_CHECKED}/> {CUSTOM_TITLE} </td>
                    <!-- END: loop2 -->
                </tr>
                <!-- END: loop1 -->
            </tbody>
        </table>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption><em class="fa fa-file-text-o"></em> {LANG.logosizecaption}</caption>
                <tbody>
                    <tr>
                        <td>{LANG.upload_logo}</td>
                        <td><input type="text" class="w350 form-control pull-left" style="margin-right: 5px" value="{AUTOLOGOSIZE.upload_logo}" id="upload_logo" name="upload_logo"><input type="button" name="selectimg" value="{LANG.selectimg}" class="btn btn-info"></td>
                    </tr>
                    <tr>
                        <td>{LANG.imagewith} &lt; = 150px</td>
                        <td><span class="text-middle pull-left"> {LANG.logowith} &nbsp;</span><input type="text" class="form-control w50 pull-left" value="{AUTOLOGOSIZE.autologosize1}" maxlength="2" name="autologosize1"/><span class="text-middle">&nbsp; % {LANG.fileimage} </span></td>
                    </tr>
                    <tr>
                        <td>{LANG.imagewith} &gt; 150px, &lt; 350px</td>
                        <td><span class="text-middle pull-left"> {LANG.logowith} &nbsp;</span><input type="text" class="form-control pull-left w50" value="{AUTOLOGOSIZE.autologosize2}" maxlength="2" name="autologosize2"/><span class="text-middle pull-left">&nbsp; % {LANG.fileimage} </span></td>
                    </tr>
                    <tr>
                        <td>{LANG.imagewith} &gt; = 350px</td>
                        <td><span class="text-middle pull-left"> {LANG.logosize3} &nbsp;</span><input type="text" class="form-control pull-left w50" value="{AUTOLOGOSIZE.autologosize3}" maxlength="2" name="autologosize3"/>&nbsp;<span class="text-middle pull-left">&nbsp; % {LANG.fileimage} </span></td>
                    </tr>
                    <tr>
                        <td>{LANG.upload_logo_pos}</td>
                        <td>
                            <select class="form-control w250" name="upload_logo_pos">
                                <!-- BEGIN: upload_logo_pos -->
                                <option value="{UPLOAD_LOGO_POS.key}"{UPLOAD_LOGO_POS.selected}>{UPLOAD_LOGO_POS.title}</option>
                                <!-- END: upload_logo_pos -->
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center">
        <input name="submit" type="submit" value="{LANG.pubdate}" class="btn btn-primary" />
    </div>
</form>
<!--  END: main  -->