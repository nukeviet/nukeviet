<!-- BEGIN: main -->
<div class="m-bottom">
    <!-- BEGIN: all_link -->
    <a class="btn btn-primary m-bottom" href="{ALL_LINK}">{LANG.tags_all_link}</a>
    <a class="btn btn-default m-bottom" href="{COMPLETE_LINK}">{LANG.tags_complete_link}</a>
    <a class="btn btn-default m-bottom" href="{INCOMPLETE_LINK}">{LANG.tags_incomplete_link}</a>
    <!-- END: all_link -->
    <!-- BEGIN: complete_link -->
    <a class="btn btn-default m-bottom" href="{ALL_LINK}">{LANG.tags_all_link}</a>
    <a class="btn btn-primary m-bottom" href="{COMPLETE_LINK}">{LANG.tags_complete_link}</a>
    <a class="btn btn-default m-bottom" href="{INCOMPLETE_LINK}">{LANG.tags_incomplete_link}</a>
    <!-- END: complete_link -->
    <!-- BEGIN: incomplete_link -->
    <a class="btn btn-default m-bottom" href="{ALL_LINK}">{LANG.tags_all_link}</a>
    <a class="btn btn-default m-bottom" href="{COMPLETE_LINK}">{LANG.tags_complete_link}</a>
    <a class="btn btn-primary m-bottom" href="{INCOMPLETE_LINK}">{LANG.tags_incomplete_link}</a>
    <!-- END: incomplete_link -->

    <button type="button" class="btn btn-default m-bottom" data-toggle="add_tags" data-title="{LANG.add_tags}" data-fc="addTag">{LANG.add_tags}</button>
    <button type="button" class="btn btn-default m-bottom" data-toggle="add_tags" data-title="{LANG.add_multiple_tags}" data-fc="addMultiTags">{LANG.add_multiple_tags}</button>
</div>

<div class="well">
    <form class="form-inline" action="{FORM_ACTION}" method="get" data-toggle="nv_search_tag">
        <div class="form-group">
            <label for="q">{LANG.search_key}:</label>
            <input class="form-control" id="q" name="q" type="text" value="{Q}" placeholder="{LANG.search_note}" maxlength="64" />
        </div>
        <button class="btn btn-primary" type="submit">{LANG.search}</button>
    </form>
</div>

<!-- BEGIN: show_list -->
<div class="m-bottom" id="module_show_list">
    <form data-checkss="{NV_CHECK_SESSION}">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption>
                    <em class="fa fa-file-text-o"></em>&nbsp;{LIST_CAPTION}
                </caption>
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
                        <th>{LANG.name}</th>
                        <th class="text-nowrap">{LANG.description}</th>
                        <th>{LANG.keywords}</th>
                        <th colspan="2">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: loop -->
                    <tr>
                        <td class="text-center" style="width: 1%;"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.tid}" name="idcheck[]" /></td>
                        <td>
                            <a href="{ROW.link}" target="_blank">{ROW.title}</a>
                        </td>
                        <td class="text-center" style="width: 1%;">
                            <!-- BEGIN: complete -->
                            <em class="text-success fa fa-check"></em>
                            <!-- END: complete -->
                            <!-- BEGIN: incomplete -->
                            <em class="text-danger fa fa-warning tags-tip" data-toggle="tooltip" data-placement="top" title="{LANG.tags_no_description}"></em>
                            <!-- END: incomplete -->
                        </td>
                        <td>
                            {ROW.keywords}
                        </td>
                        <td class="text-center" style="width: 1%;">
                            <button type="button" class="btn btn-default btn-block btn-sm" <!-- BEGIN: nolink --> disabled
                                <!-- END: nolink --> data-toggle="add_tags" data-title="{LANG.tag_links}" data-fc="tagLinks" data-tid="{ROW.tid}"><em class="fa fa-tags">&nbsp;</em>{LANG.tag_links}: <strong>{ROW.numnews}</strong>
                            </button>
                        </td>
                        <td class="text-center text-nowrap" style="width: 1%;">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="add_tags" data-title="{LANG.edit_tags}" data-fc="editTag" data-tid="{ROW.tid}"><em class="fa fa-edit fa-lg">&nbsp;</em>{GLANG.edit}</button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="nv_del_tag" data-tid="{ROW.tid}"><em class="fa fa-trash-o fa-lg">&nbsp;</em>{GLANG.delete}</button>
                        </td>
                    </tr>
                    <!-- END: loop -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"><button type="button" class="btn btn-danger" data-toggle="nv_del_check_tags" data-msgnocheck="{LANG.msgnocheck}">{GLANG.delete}</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
</div>
<!-- END: show_list -->

<!-- Modal -->
<div class="modal fade" id="addTag" tabindex="-1" role="dialog" aria-labelledby="addTagLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addTagLabel"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.tags-tip').tooltip();
    });
</script>
<!-- END: main -->
<!-- BEGIN: add_tag -->
<div class="alert">
    <form action="{FORM_ACTION}" method="post" class="form-horizontal" data-toggle="addTagSubmit">
        <input name="savecat" type="hidden" value="1" />
        <input name="tid" type="hidden" value="{TID}" />

        <div class="form-group">
            <label for="idkeywords" class="col-sm-6 control-label">{LANG.keywords} <sup class="required">(∗)</sup></label>
            <div class="col-sm-18">
                <input type="text" class="form-control" id="idkeywords" name="keywords" value="{KEYWORDS}" maxlength="250" placeholder="{LANG.keywords}" data-error="{LANG.error_tag_keywords}">
            </div>
        </div>

        <div class="form-group">
            <label for="idtitle" class="col-sm-6 control-label">{LANG.name} <sup class="required">(∗)</sup></label>
            <div class="col-sm-18">
                <input type="text" class="form-control" id="idtitle" name="title" value="{TITLE}" maxlength="65" placeholder="{LANG.name}" data-error="{LANG.error_tag_title}">
                <span class="text-middle">{GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max}</span>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-6 control-label">{LANG.description}</label>
            <div class="col-sm-18">
                <textarea class="form-control" id="description" name="description" cols="100" rows="5">{DESCRIPTION}</textarea>
                <span class="text-middle">{GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max}</span>
            </div>
        </div>

        <div class="form-group">
            <label for="image" class="col-sm-6 control-label">{LANG.content_homeimg}</label>
            <div class="col-sm-18">
                <div class="input-group">
                    <input type="text" class="form-control" id="image" name="image" value="{IMAGE}" placeholder="{LANG.content_homeimg}">
                    <span class="input-group-btn">
                        <button name="selectimg" class="btn btn-info" type="button" data-toggle="select_img_tag" data-path="{UPLOAD_PATH}" data-currentpath="{UPLOAD_CURRENT}">Browse server</button>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group m-bottom-none">
            <div class="col-sm-offset-6 col-sm-18">
                <button type="submit" class="btn btn-primary">{LANG.save}</button>
            </div>
        </div>
    </form>
</div>
<script>
    $(function() {
        $("#titlelength").html($("#idtitle").val().length);
        $("#idtitle").bind("keyup paste", function() {
            $("#titlelength").html($(this).val().length);
        });

        $("#descriptionlength").html($("#description").val().length);
        $("#description").bind("keyup paste", function() {
            $("#descriptionlength").html($(this).val().length);
        });

        $("[data-toggle=select_img_tag]").on('click', function(e) {
            e.preventDefault();
            var path = $(this).data('path'),
                currentpath = $(this).data('currentpath');
            nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=image&path=" + path + "&type=image&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no")
        });

        $('[data-toggle=addTagSubmit] [type=submit]').on('click', function(e) {
            e.preventDefault();
            var that = $(this).parents('form'),
                title = $('[name=title]', that).val(),
                keywords = $('[name=keywords]', that).val();

            $('.has-error', that).removeClass('has-error');

            title = trim(strip_tags(title));
            keywords = trim(strip_tags(keywords));
            $('[name=title]', that).val(title);
            $('[name=keywords]', that).val(keywords);
            $("#titlelength").text(title.length);
            if (keywords.length < 2) {
                $('[name=keywords]', that).parent().addClass('has-error');
                alert($('[name=keywords]', that).data('error'));
                $('[name=keywords]', that).focus();
                return !1
            }
            if (title.length < 2) {
                $('[name=title]', that).parent().addClass('has-error');
                alert($('[name=title]', that).data('error'));
                $('[name=title]', that).focus();
                return !1
            }
        });

        $('[data-toggle=addTagSubmit]').on('submit', function(e) {
            e.preventDefault();
            var that = $(this),
                url = that.attr('action'),
                tid = parseInt($('[name=tid]', that).val()),
                data = that.serialize();
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: data,
                dataType: "json",
                success: function(b) {
                    if (b.status == 'error') {
                        alert(b.message);
                        if (b.input != '' && $('[name=' + b.input + ']:visible', that).length) {
                            $('[name=' + b.input + ']', that).parent().addClass('has-error');
                            $('[name=' + b.input + ']', that).focus()
                        }
                    } else if (b.status == 'ok') {
                        if (!tid) {
                            window.location.href = url
                        } else {
                            window.location.href = window.location.href
                        }
                    }
                }
            });
        });
    });
</script>
<!-- END: add_tag -->
<!-- BEGIN: add_multi_tags -->
<div class="alert">
    <form action="{FORM_ACTION}" method="post" data-toggle="addTagSubmit">
        <input name="savetag" type="hidden" value="1" />
        <div class="form-group">
            <label for="mtitle">{LANG.note_tags}</label>
            <textarea class="form-control" name="mtitle" id="mtitle" cols="100" rows="5" maxlength="2000"></textarea>
        </div>
        <div class="text-center">
            <input class="btn btn-primary" type="submit" value="{LANG.save}" />
        </div>
    </form>
</div>
<script>
    $(function() {
        $('[data-toggle=addTagSubmit]').on('submit', function(e) {
            e.preventDefault();
            var that = $(this),
                url = that.attr('action'),
                data = that.serialize();
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: data,
                dataType: "html",
                success: function(b) {
                    alert(b);
                    window.location.href = url
                }
            });
        });
    })
</script>
<!-- END: add_multi_tags -->
<!-- BEGIN: taglinks -->
<form action="{FORM_ACTION}" method="post" data-toggle="delTagLinks" class="table-responsive">
    <input type="hidden" name="tid" value="{TID}" />
    <table class="table table-bordered table-striped">
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td style="width: 1%;">
                    <input type="checkbox" value="{ROW.id}" name="idcheck[]" />
                </td>
                <td><a href="{ROW.url}" target="_blank">{ROW.title}</a></td>
            </tr>
            <tr class="item" data-id="{ROW.id}">
                <td colspan="2">
                    <div class="kshow">
                        <div class="pull-right">
                            <button type="button" class="btn btn-link" title="{GLANG.edit}" data-toggle="tag_keyword_edit"><i class="fa fa-pencil-square-o fa-lg"></i></button>
                        </div>
                        {LANG.keyword}: <span class="keyword<!-- BEGIN: invalid --> invalid<!-- END: invalid -->">{ROW.keyword}</span>
                    </div>
                    <div class="kedit form-inline hidden">
                        <div class="form-group">
                            <label>{LANG.select_keyword}</label>
                            <select class="form-control" name="keyword">
                                <!-- BEGIN: keyword -->
                                <option value="{KEYS.val}" {KEYS.sel}>{KEYS.val}</option>
                                <!-- END: keyword -->
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" data-toggle="keyword_change">{LANG.save}</button>
                        <button type="button" class="btn btn-link" data-toggle="tag_keyword_edit"><span class="close" aria-hidden="true">&times;</span></button>
                    </div>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <button type="button" class="btn btn-danger btn-xs" data-toggle="tags_id_check_del">{LANG.del_selected}</button>
                    <button type="button" class="btn btn-danger btn-xs" data-toggle="tags_id_all_del">{LANG.del_all}</button>
                </td>
            </tr>
        </tfoot>
    </table>
</form>
<script>
    $(function() {
        $('[data-toggle=tag_keyword_edit]').on('click', function(e) {
            e.preventDefault();
            var item = $(this).parents('.item');
            $('.kshow, .kedit', item).toggleClass('hidden')
        });
        $('[data-toggle=keyword_change]').on('click', function(e) {
            e.preventDefault();
            var form = $(this).parents('form'),
                item = $(this).parents('.item'),
                id = item.data('id'),
                tid = $('[name=tid]', form).val(),
                keyword = $('[name=keyword]', item).val(),
                url = form.attr('action');
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: 'keywordEdit=1&id=' + id + '&tid=' + tid + '&keyword=' + rawurlencode(keyword),
                dataType: "html",
                success: function(b) {
                    $('.keyword', item).text(keyword).removeClass('invalid');
                    $('.kshow, .kedit', item).toggleClass('hidden')
                }
            })
        });
        $('[data-toggle=tags_id_check_del]').on('click', function(e) {
            e.preventDefault();
            var form = $(this).parents('form'),
                tid = $('[name=tid]', form).val(),
                url = form.attr('action'),
                ids = '';
            if ($('[name^=idcheck]:checked', form).length) {
                $('[name^=idcheck]:checked', form).each(function() {
                    if (ids != '') ids += ',';
                    ids += $(this).val()
                })
            }
            if (ids != '') {
                if (confirm(nv_is_del_confirm[0])) {
                    $.ajax({
                        type: 'POST',
                        cache: !1,
                        url: url,
                        data: 'tagsIdDel=1&ids=' + ids + '&tid=' + tid,
                        dataType: "html",
                        success: function(b) {
                            window.location.href = window.location.href
                        }
                    })
                }
            }
        });
        $('[data-toggle=tags_id_all_del]').on('click', function(e) {
            e.preventDefault();
            var form = $(this).parents('form'),
                tid = $('[name=tid]', form).val(),
                url = form.attr('action');
            if (confirm(nv_is_del_confirm[0])) {
                $.ajax({
                    type: 'POST',
                    cache: !1,
                    url: url,
                    data: 'tagsIdAllDel=1&tid=' + tid,
                    dataType: "html",
                    success: function(b) {
                        window.location.href = window.location.href
                    }
                })
            }
        })
    })
</script>
<!-- END: taglinks -->