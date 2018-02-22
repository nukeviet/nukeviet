<!-- BEGIN: main -->
<div id="ablist" class="form-inline well">
    <div class="form-group">
        <strong>{LANG.keywords}</strong>
    </div>
    <div class="form-group">
        <input class="form-control" type="text" value="{Q}" name="q" maxlength="255" />
    </div>
    <select class="form-control" name="tList">
        <option value="0"> {LANG.topicselect} </option>
        <!-- BEGIN: psopt4 -->
        <option value="{OPTION4.id}"> {OPTION4.name} </option>
        <!-- END: psopt4 -->
    </select>
    <input class="btn btn-primary" name="ok2" type="button" value="{LANG.search}" />
</div>
<div class="form-inline">
    <input class="btn btn-primary" name="addNew" type="button" value="{LANG.addClip}" />
</div>
<div class="myh3">
    {PTITLE}
</div>
<div id="pageContent">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="w150"> {LANG.adddate} </th>
                <th> {LANG.title} </th>
                <th> {LANG.topic_parent} </th>
                <th class="text-center w20"><i class="fa fa-eye" title="{LANG.viewhits}"></i></th>
                <th class="text-center w20"><i class="fa fa-thumbs-up" title="{LANG.like}"></i></th>
                <th class="text-center w20"><i class="fa fa-thumbs-down" title="{LANG.dislike}"></i></th>
                <th class="w150"> {LANG.status} </th>
                <th class="text-center"> {LANG.feature} </th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td> {DATA.adddate} </td>
                <td> <a href="{DATA.link_view}">{DATA.title}</a> </td>
                <td><a href="{MODULE_URL}&amp;tid={DATA.tid}">{DATA.topicname}</a></td>
                <td class="text-center">{DATA.view}</td>
                <td class="text-center">{DATA.liked}</td>
                <td class="text-center">{DATA.unlike}</td>
                <td><a  href="{DATA.id}" title="{DATA.alt}" class="changeStatus">{DATA.icon} {DATA.status}</a></td>
                <td class="text-center">
                    <em class="fa fa-edit">&nbsp;</em><a href="{MODULE_URL}&edit&id={DATA.id}">{GLANG.edit}</a> - <em class="fa fa-trash-o">&nbsp;</em><a class="del" href="{DATA.id}">{GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
    <div id="nv_generate_page" class="text-center">
        {NV_GENERATE_PAGE}
    </div>
</div>
<script type="text/javascript">
    //<![CDATA[
    $("a.del").click(function() {
        confirm("{LANG.delConfirm} ?") && $.ajax({
            type : "POST",
            url : "{MODULE_URL}",
            data : "del=" + $(this).attr("href"),
            success : function(a) {
                "OK" == a ? window.location.href = window.location.href : alert(a)
            }
        });
        return !1
    });
    $("input[name=addNew]").click(function() {
        window.location.href = "{MODULE_URL}&add";
        return !1
    });
    $("a.changeStatus").click(function() {
        var a = this;
        $.ajax({
            type : "POST",
            url : "{MODULE_URL}",
            data : "changeStatus=" + $(this).attr("href"),
            success : function(b) {
                $(a).html(b)
            }
        });
        return !1
    });
    $("input[name=ok2]").click(function() {
        var a = $("select[name=tList]").val();
        var q = $("input[name=q]").val();
        window.location.href = ( a !='' || q !='') ? "{MODULE_URL}&tid=" + a[0] + "&q=" + q : "{MODULE_URL}";
        return !1
    });
    //]]>
</script>
<!-- END: main -->
<!-- BEGIN: add -->
<h3 class="myh3"> {INFO_TITLE} </h3>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR_INFO}</div>
<!-- END: error -->

<form class="form-inline" id="addInformation" method="post" action="{POST.action}">
    <table class="table table-striped table-bordered table-hover">
        <col style="width:220px" />
        <tbody>
            <tr>
                <td> {LANG.title} <span style="color:red"> * </span></td>
                <td>
                <input class="form-control" title="{LANG.title}" type="text" name="title" value="{POST.title}" style="width:400px" maxlength="250" />
                </td>
            </tr>
            <tr>
                <td> {LANG.alias} <span style="color:red"> * </span></td>
                <td>
                <input class="form-control" title="{LANG.alias}" type="text" name="alias" value="{POST.alias}" style="width:400px" maxlength="250" id="id_alias" required="required" oninvalid="setCustomValidity(nv_required)" oninput="setCustomValidity('')"/>
                &nbsp;<i class="fa fa-refresh fa-lg icon-pointer" onclick="nv_get_alias('id_alias');">&nbsp;</i>
                </td>
            </tr>
            <tr>
                <td> {LANG.topic_parent}<span style="color:red"> * </span> </td>
                <td>
                <select class="form-control" name="tid">
                    <!-- BEGIN: option3 -->
                    <option value="{OPTION3.value}"{OPTION3.selected}> {OPTION3.name} </option>
                    <!-- END: option3 -->
                </select></td>
            </tr>
            <tr>
                <td> {LANG.internalpath} </td>
                <td>
                <input class="form-control" title="{LANG.internalpath}" type="text" name="internalpath" id="internalpath" value="{POST.internalpath}" style="width:280px" maxlength="255" />
                <input type="button" value="Browse server" name="selectfile" class="btn btn-info" />
                </td>
            </tr>
            <tr>
                <td> {LANG.externalpath} </td>
                <td>
                <input class="form-control" title="{LANG.externalpath}" type="text" name="externalpath" value="{POST.externalpath}" style="width:400px" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td> {LANG.commAllow} </td>
                <td>
                <input name="comm" type="checkbox"{POST.comm} value="1" />
                </td>
            </tr>
            <tr>
                <td> {LANG.homeImg} </td>
                <td>
                <input class="form-control" title="{LANG.homeImg}" type="text" name="img" id="img" value="{POST.img}" style="width:280px" maxlength="255" />
                <input type="button" value="Browse server" name="selectimg" class="btn btn-info" />
                </td>
            </tr>
            <tr>
                <td> {LANG.hometext} <span style="color:red"> * </span></td>
                <td><textarea title="{LANG.hometext}" name="hometext" class="form-control" style="width:400px;height:100px">{POST.hometext}</textarea></td>
            </tr>
            <tr>
                <td> {LANG.keywords} </td>
                <td>
                <input class="form-control" title="{LANG.keywords}" type="text" name="keywords" value="{POST.keywords}" style="width:400px" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td colspan="2">{LANG.bodytext}</td>
            </tr>
            <tr>
                <td colspan="2">{CONTENT}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <input name="redirect" type="hidden" value="{POST.redirect}" />
                    <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
                </td>
            </tr>
        </tbody>
    </table>

</form>
<script type="text/javascript">
    //<![CDATA[
    $("input[name=selectfile]").click(function() {
        var area = "internalpath";
        var alt = "homeimgalt";
        var paths = "{UPLOAD_CURRENT}/video";
        var currentpaths = "{UPLOAD_CURRENT}/video";
        var type = "file";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + paths + "&type=" + type + "&currentpath=" + currentpaths, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
    $("input[name=selectimg]").click(function() {
        var area = "img";
        var alt = "homeimgalt";
        var path = "{UPLOAD_CURRENT}/images";
        var currentpath = "{UPLOAD_CURRENT}/images";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

    $("form#addInformation").submit(function() {
        var a = trim($("input[name=title]").val());
        $("input[name=title]").val(a);
        if ("" == a)
            return alert("{LANG.error1}"), $("input[name=title]").val("").select(), !1;
        a = trim($("input[name=internalpath]").val());
        $("input[name=internalpath]").val(a);
        b = trim($("input[name=externalpath]").val());
        $("input[name=externalpath]").val(b);
        if ("" == a && "" == b)
            return alert("{LANG.error5}"), $("input[name=internalpath]").select(), !1;
        a = trim($("textarea[name=hometext]").val());
        $("textarea[name=hometext]").val(a);
        if ("" == a)
            return alert("{LANG.error7}"), $("textarea[name=hometext]").val("").select(), !1;
        $("form#addInformation").submit();
        return !1
    });

    function nv_get_alias(id) {
        var title = strip_tags($("[name='title']").val());
        if (title != '') {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
                $("#" + id).val(strip_tags(res));
            });
        }
        return false;
    }
    //]]>
</script>
<!-- BEGIN: auto_get_alias -->
<script type="text/javascript">
    $("[name='title']").change(function() {
        nv_get_alias('id_alias');
    });
</script>
<!-- END: auto_get_alias -->
<!-- END: add -->