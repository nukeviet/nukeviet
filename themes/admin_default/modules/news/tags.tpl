<!-- BEGIN: main -->
<div class="well">
    <form class="navbar-form" action="{NV_BASE_ADMINURL}index.php" method="get" onsubmit="return nv_search_tag();">
        {LANG.search_key}:
        <input class="form-control" id="q" type="text" value="{Q}" maxlength="64" name="q" style="width: 265px" />
        <input class="btn btn-primary" type="submit" value="{LANG.search}" /><br /><br />
        <label><em>{LANG.search_note}</em></label>
    </form>
</div>
<!-- BEGIN: incomplete_link -->
<div class="alert alert-info">
    <a class="text-info" href="{ALL_LINK}">{LANG.tags_all_link}.</a>
</div>
<!-- END: incomplete_link -->
<div id="module_show_list">
    {TAGS_LIST}
</div>
<br />
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
    <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
    <input type="hidden" name ="tid" value="{tid}" />
    <input name="savecat" type="hidden" value="1" />
    <!-- BEGIN: incomplete --><input name="incomplete" type="hidden" value="1" /><!-- END: incomplete -->
    <div class="table-responsive">
        <table id="edit" class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.add_tags}</caption>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2"><input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td class="text-right w150"><strong>{LANG.name}: </strong> <sup class="required">(∗)</sup></td>
                    <td><input class="form-control w500" name="title" id="idtitle" type="text" value="{title}" maxlength="250" /><span class="text-middle">{GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max}</span></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{LANG.alias}: </strong> <sup class="required">(∗)</sup></td>
                    <td><input class="form-control w500 pull-left" name="alias" id="idalias" type="text" value="{alias}" maxlength="250" />&nbsp; <em class="fa fa-refresh fa-lg fa-pointer icon-pointer" onclick="get_alias_tags();">&nbsp;</em></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{LANG.keywords}: </strong></td>
                    <td><input class="form-control w500" name="keywords" type="text" value="{keywords}" maxlength="255" /></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{LANG.description}</strong></td>
                    <td><textarea class="w500 form-control" id="description" name="description" cols="100" rows="5">{description}</textarea><span class="text-middle">{GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max}</span></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{LANG.content_homeimg}</strong></td>
                    <td><input class="form-control w500 pull-left" style="margin-right: 5px" type="text" name="image" id="image" value="{image}"/> <input id="select-img-tag" type="button" value="Browse server" name="selectimg" class="btn btn-info"/></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
    <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
    <input name="savetag" type="hidden" value="1" />
    <div class="table-responsive">
        <table id="add" class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.add_multiple_tags}</caption>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2"><input class="btn btn-primary" name="submit2" type="submit" value="{LANG.save}" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td class="text-right w150"><strong>{LANG.tag}: </strong> <sup class="required">(∗)</sup></td>
                    <td><textarea class="form-control w500" name="mtitle" id="mtitle" type="text" cols="100" rows="5"></textarea><span class="text-middle">{LANG.note_tags}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript">
var CFG = [];
CFG.upload_current = '{UPLOAD_CURRENT}';
CFG.upload_path = '{UPLOAD_PATH}';
$(document).ready(function(){
    $("#titlelength").html($("#idtitle").val().length);
    $("#idtitle").bind("keyup paste", function() {
        $("#titlelength").html($(this).val().length);
    });

    $("#descriptionlength").html($("#description").val().length);
    $("#description").bind("keyup paste", function() {
        $("#descriptionlength").html($(this).val().length);
    });
});
<!-- BEGIN: getalias -->
$("#idtitle").change(function() {
    get_alias_tags();
});
<!-- END: getalias -->
</script>
<!-- END: main -->