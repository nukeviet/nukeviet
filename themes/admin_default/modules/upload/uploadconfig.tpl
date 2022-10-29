<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="frm">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col style="width: 230px"/>
            <col />
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.pubdate}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td><strong>{LANG.nv_max_width_height}</strong></td>
                    <td><input class="form-control w100 pull-left" type="text" value="{NV_MAX_WIDTH}" name="nv_max_width" maxlength="4"/><span class="pull-left text-middle">&nbsp; x &nbsp;</span><input class="form-control pull-left w100" type="text" value="{NV_MAX_HEIGHT}" name="nv_max_height" maxlength="4"/> <span class="pull-left text-middle"><input type="checkbox" style="margin-left:40px;" name="nv_auto_resize" value="1" {NV_AUTO_RESIZE}/>{LANG.nv_auto_resize}</span> </td>
                </tr>
                <tr>
                    <td><strong>{LANG.nv_mobile_mode_img}</strong></td>
                    <td><input class="form-control w100" type="text" value="{NV_MOBILE_MODE_IMG}" name="nv_mobile_mode_img" maxlength="3"/><span class="help-block m-bottom-none">{LANG.nv_mobile_mode_img_note}</span></td>
                </tr>
                <tr>
                    <td><strong>{LANG.nv_max_size}:</strong></td>
                    <td>
                    <select name="nv_max_size" id="nv_max_size" class="form-control w200">
                        <!-- BEGIN: size -->
                        <option value="{SIZE.key}"{SIZE.selected}>{SIZE.title}</option>
                        <!-- END: size -->
                    </select><span class="text-middle">&nbsp; ({LANG.sys_max_size}: {SYS_MAX_SIZE}) </span></td>
                </tr>
                <tr>
                    <td><strong>{LANG.upload_checking_mode}:</strong></td>
                    <td>
                    <select name="upload_checking_mode" class="form-control w100">
                        <!-- BEGIN: upload_checking_mode -->
                        <option value="{UPLOAD_CHECKING_MODE.key}"{UPLOAD_CHECKING_MODE.selected}>{UPLOAD_CHECKING_MODE.title}</option>
                        <!-- END: upload_checking_mode -->
                    </select> {UPLOAD_CHECKING_NOTE} </td>
                </tr>
                <tr>
                    <td><strong>{LANG.upload_alt_require}</strong></td>
                    <td>
                        <input type="checkbox" name="upload_alt_require" value="1"{UPLOAD_ALT_REQUIRE}/>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.upload_auto_alt}</strong></td>
                    <td>
                        <input type="checkbox" name="upload_auto_alt" value="1"{UPLOAD_AUTO_ALT}/>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.upload_chunk}</strong></td>
                    <td>
                        <div class="clearfix">
                            <input type="text" name="upload_chunk_size" value="{UPLOAD_CHUNK_SIZE}" class="form-control pull-left w100"/>
                            <select class="form-control pull-left m-left w100" name="upload_chunk_size_text">
                                <!-- BEGIN: chunk_size_lev -->
                                <option value="{CHUNK_SIZE_LEV.key}"{CHUNK_SIZE_LEV.selected}>{CHUNK_SIZE_LEV.title}</option>
                                <!-- END: chunk_size_lev -->
                            </select>
                        </div>
                        <span class="help-block m-bottom-none">{LANG.upload_chunk_help}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.upload_overflow}</strong></td>
                    <td>
                        <div class="clearfix">
                            <input type="text" name="nv_overflow_size" value="{UPLOAD_OVERFLOW_SIZE}" class="form-control pull-left w100">
                            <select class="form-control pull-left m-left w100" name="nv_overflow_size_text">
                                <!-- BEGIN: overflow_size_lev -->
                                <option value="{OVERFLOW_SIZE_LEV.key}"{OVERFLOW_SIZE_LEV.selected}>{OVERFLOW_SIZE_LEV.title}</option>
                                <!-- END: overflow_size_lev -->
                            </select>
                        </div>
                        <span class="help-block m-bottom-none">{LANG.upload_overflow_help}</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.uploadconfig_types}</strong></td>
                    <td>
                    <!-- BEGIN: types -->
                    <label style="display:inline-block;width:100px"><input type="checkbox" name="type[]" value="{TYPES.key}"{TYPES.checked}/> {TYPES.title}&nbsp;&nbsp;</label>
                    <!-- END: types -->
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top"><strong>{LANG.uploadconfig_ban_ext}</strong></td>
                    <td>
                    <!-- BEGIN: exts -->
                    <label style="display:inline-block;width:100px"><input type="checkbox" name="ext[]" value="{EXTS.key}"{EXTS.checked} /> {EXTS.title}&nbsp;&nbsp;</label>
                    <!-- END: exts -->
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top"><strong>{LANG.uploadconfig_ban_mime}</strong></td>
                    <td>
                    <!-- BEGIN: mimes -->
                    <label style="display:inline-block;width:48%"><input type="checkbox" name="mime[]" value="{MIMES.key}"{MIMES.checked} /> {MIMES.title}&nbsp;&nbsp;</label>
                    <!-- END: mimes -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#frm').validate();
    });
</script>
<!-- END: main -->
