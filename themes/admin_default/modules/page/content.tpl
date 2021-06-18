<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
    <input name="save" type="hidden" value="1" />
    <input type="hidden" value="{ISCOPY}" name="copy" />
    <div class="row">
        <div class="col-sm-24 col-md-18">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <colgroup>
                        <col class="w200" />
                        <col />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="text-right"> {LANG.title} <sup class="required">(*)</sup></td>
                            <td><input class="w300 form-control pull-left" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="250" />&nbsp;<span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
                        </tr>
                        <tr>
                            <td class="text-right">{LANG.alias}</td>
                            <td><input class="w300 form-control pull-left" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="250" />&nbsp;<em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('{ID}');">&nbsp;</em></td>
                        </tr>
                        <tr>
                            <td class="text-right">{LANG.image}</td>
                            <td><input class="w300 form-control pull-left" type="text" name="image" id="image" value="{DATA.image}" style="margin-right: 5px"/><input type="button" value="Browse server" name="selectimg" class="btn btn-info"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">{LANG.imagealt}</td>
                            <td><input class="w300 form-control" type="text" name="imagealt" id="imagealt" value="{DATA.imagealt}"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">{LANG.imgposition}</td>
                            <td>
                            <select class="form-control w300" name="imageposition">
                                <!-- BEGIN: looppos -->
                                <option value="{id_imgposition}" {posl}>{title_imgposition}</option>
                                <!-- END: looppos -->
                            </select></td>
                        </tr>
                        <tr>
                            <td class="text-right">{LANG.description} </td>
                            <td >							<textarea class="form-control" id="description" name="description" cols="100" rows="5">{DATA.description}</textarea> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="strong" > {LANG.bodytext} <sup class="required">(*)</sup>
                            <div>
                                {BODYTEXT}
                            </div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-24 col-md-6">
            <table class="table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <td>{LANG.group_post}</td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" value="1" name="hot_post"{HOST_POST}/> {LANG.hot_post}</label></td>
                    </tr>
                    <tr>
                        <td>{LANG.keywords}</td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" value="{DATA.keywords}" name="keywords" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.socialbutton}</td>
                    </tr>
                    <tr>
                        <td><label><input type="checkbox" value="1" name="socialbutton"{SOCIALBUTTON}/> {LANG.socialbuttonnote}</label></td>
                    </tr>
                    <tr>
                        <td>{LANG.layout_func}</td>
                    </tr>
                    <tr>
                        <td>
                        <select name="layout_func" class="form-control">
                            <option value="">{LANG.layout_default}</option>
                            <!-- BEGIN: layout_func -->
                            <option value="{LAYOUT_FUNC.key}"{LAYOUT_FUNC.selected}>{LAYOUT_FUNC.key}</option>
                            <!-- END: layout_func -->
                        </select></td>
                    </tr>
                    <tr>
                        <td>{LANG.activecomm}</td>
                    </tr>
                    <tr>
                        <td><!-- BEGIN: activecomm -->
                        <div class="row">
                            <label><input name="activecomm[]" type="checkbox" value="{ACTIVECOMM.value}" {ACTIVECOMM.checked} />{ACTIVECOMM.title}</label>
                        </div><!-- END: activecomm --></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row text-center"><input type="submit" value="{LANG.save}" class="btn btn-primary"/>
    </div>
</form>
<script type="text/javascript">
    var uploads_dir_user = '{UPLOADS_DIR_USER}';
    $("#titlelength").html($("#idtitle").val().length);
    $("#idtitle").bind('keyup paste', function() {
        $("#titlelength").html($(this).val().length);
    });

    $("#descriptionlength").html($("#description").val().length);
    $("#description").bind('keyup paste', function() {
        $("#descriptionlength").html($(this).val().length);
    });
</script>
<!-- BEGIN: get_alias -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#idtitle').change(function() {
            get_alias('{ID}');
        });
    });
</script>
<!-- END: get_alias -->
<!-- END: main -->
