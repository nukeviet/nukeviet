<!-- BEGIN: msg -->
<div class="alert alert-info text-center">
    <p><strong>{LANG.msg1} {TYPE} {LANG.msg2}. <a href="{HREF}">{LANG.msg3}</a> {LANG.msg4}</strong></p>
	{LANG.msg5}
</div>
<!-- END: msg -->

<!-- BEGIN: list -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col />
            <col class="w100" />
            <col span="5" class="w150" />

        </colgroup>
        <thead>
            <tr>
                <th>{LANG.title}</th>
                <th>{LANG.code}</th>
                <!-- BEGIN: view_time_title -->
                <th>{LANG.publtime}</th>
                <th>{LANG.exptime}</th>
                <!-- END: view_time_title -->
                <!-- BEGIN: view_comm_time_title -->
                <th>{LANG.start_comm_time}</th>
                <th>{LANG.end_comm_time}</th>
                <!-- END: view_comm_time_title -->
                <th class="text-center">{LANG.admin_add}</th>
                <th class="text-center">{LANG.status}</th>
                <th class="text-center">{LANG.feature}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td><a href="{DATA.url_view}" target="_blank" title="{DATA.title}">{DATA.title}</a></td>
                <td><strong>{DATA.code}</strong></td>
                <!-- BEGIN: view_time -->
                <td>{DATA.publtime}</td>
                <td>{DATA.exptime}</td>
                <!-- END: view_time -->
                <!-- BEGIN: view_comm_time -->
                <td>{DATA.start_comm_time}</td>
                <td>{DATA.end_comm_time}</td>
                <!-- END: view_comm_time -->
                <td>{DATA.admin_add}</td>
                <td class="text-center">
                <select class="form-control" id="status_{DATA.id}" name="status[]" onchange="nv_change_status({DATA.id});">
                    <option value="0">{LANG.status0}</option>
                    <option value="1"{DATA.selected}>{LANG.status1}</option>
                </select></td>
                <td class="text-center">
                	<em class="fa fa-edit fa-lg">&nbsp;</em><a href="{DATA.url_edit}">{GLANG.edit}</a>
                	- <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_delete_law({DATA.id});">{GLANG.delete}</a>
                	<!-- BEGIN: view_comm -->
                	- <em class="fa fa-eye fa-lg">&nbsp;</em><a href="{DATA.url_view_comm}">{LANG.view_comm}</a>
                	<!-- END: view_comm -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<div class="text-center">
    {NV_GENERATE_PAGE}
</div>
<!-- END: list -->

<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>

<div class="well">
    <form class="form-inline">
        <div class="form-group">
            <label class="sr-only">{LANG.keywords}</label>
            <input type="text" name="keywords" class="form-control" placeholder="{LANG.keywords}" />
        </div>
        <div class="form-group">
            <label class="sr-only">{LANG.catParent}</label>
            <select class="form-control select2" name="cid">
                <option value="">---{LANG.catParent}---</option>
                <!-- BEGIN: catParent -->
                <option value="{CATOPT.id}">{CATOPT.name}</option>
                <!-- END: catParent -->
            </select>
        </div>
        <div class="form-group">
            <label class="sr-only">{LANG.areaSel}</label>
            <select class="form-control select2" name="aid">
                <option value="">---{LANG.areaSel}---</option>
                <!-- BEGIN: alist -->
                <option value="{ALIST.id}">{ALIST.name}</option>
                <!-- END: alist -->
            </select>
        </div>
        <div class="form-group">
            <label class="sr-only">{LANG.subjectSel}</label>
            <select class="form-control select2" name="sid">
                <option value="">---{LANG.subjectSel}---</option>
                <!-- BEGIN: slist -->
                <option value="{SLIST.id}">{SLIST.title}</option>
                <!-- END: slist -->
            </select>
        </div>
        <!-- BEGIN: elist_loop -->
        <div class="form-group">
            <label class="sr-only">{LANG.ExamineSel}</label>
            <select class="form-control select2" name="eid">
                <option value="">---{LANG.ExamineSel}---</option>
                <!-- BEGIN: elist -->
                <option value="{ELIST.id}">{ELIST.title}</option>
                <!-- END: elist -->
            </select>
        </div>
        <!-- END: elist_loop -->
        <div class="form-group">
            <label class="sr-only">{LANG.signer}</label>
            <select class="form-control select2" name="sgid">
                <option value="">---{LANG.signer}---</option>
                <!-- BEGIN: sglist -->
                <option value="{SGLIST.id}">{SGLIST.title}</option>
                <!-- END: sglist -->
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" onclick="nv_search_laws(); return false;">{LANG.search}</button>
        </div>
    </form>
</div>

<div style="text-align: left; margin-bottom:10px;">
    <input name="submit" onclick="window.location='{ADD_LINK}';" type="button" value="{LANG.add_laws}" class="btn btn-primary" />
</div>

<div id="lawlist">
    <div style="text-align: center"><em class="fa fa-spinner fa-spin fa-4x">&nbsp;</em><br />{LANG.wait}</div>
</div>

<script type="text/javascript">
    $('.select2').select2();

    function nv_load_laws(url, area) {
        $('#lawlist').load(rawurldecode(url));
    }

    function nv_search_laws() {
        var keywords = $('input[name="keywords"]').val();
        var cid = $('select[name="cid"]').val();
        var aid = $('select[name="aid"]').val();
        var sid = $('select[name="sid"]').val();
        var eid = $('select[name="eid"]').val();
        var sgid = $('select[name="sgid"]').val();

        if (keywords == '' && cid == '' && aid == '' && sid == '' && eid == '' && sgid == '') {
            alert('{LANG.search_error}');
        } else {
            $('#lawlist').html('<div style="text-align: center"><em class="fa fa-spinner fa-spin fa-4x">&nbsp;</em><br />{LANG.wait}</div>');
            $('#lawlist').load('{BASE_LOAD}&keywords=' + encodeURIComponent(keywords) + '&cat=' + cid + '&aid=' + aid + '&sid=' + sid + '&eid=' + eid + '&sgid=' + sgid);
        }
    }

    $(window).on('load', function() {
        $('#lawlist').load('{BASE_LOAD}');
    });
</script>
<!-- END: main -->

<!-- BEGIN: add -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div id="pageContent">
    <form id="addRow" action="{DATA.action_url}" method="post">
        <h3 class="myh3">{DATA.ptitle}</h3>
        <div class="row">
            <div class="col-sm-24 col-md-18">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <col style="width:200px" />
                        <tbody>
                            <tr>
                                <td>{LANG.title} <span class="red">*</span></td>
                                <td>
                                <input title="{LANG.title}" class="form-control" style="width: 400px" type="text" name="title" value="{DATA.title}" maxlength="255" />
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.code} <span class="red">*</span></td>
                                <td>
                                <input title="{LANG.code}" class="form-control" style="width: 400px" type="text" name="code" value="{DATA.code}" maxlength="255" />
                                </td>
                            </tr>

                            <tr>
                                <td style="vertical-align:top"> {LANG.fileupload} <strong>[<a onclick="nv_add_files('{NV_BASE_ADMINURL}','{UPLOADS_DIR_USER}','{GLANG.delete}','Browse server');" href="javascript:void(0);" title="{LANG.add}">{LANG.add}]</a></strong></td>
                                <td>
                                <div id="filearea">
                                    <!-- BEGIN: files -->
                                    <div id="fileitem_{FILEUPL.id}" style="margin-bottom: 5px">
                                        <input title="{LANG.fileupload}" class="form-control w400 pull-left" type="text" name="files[]" id="fileupload_{FILEUPL.id}" value="{FILEUPL.value}" style="margin-right: 5px" />
                                        <input onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=fileupload_{FILEUPL.id}&path={UPLOADS_DIR_USER}&type=file', 'NVImg', '850', '500', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' );return false;" type="button" value="Browse server" class="selectfile btn btn-primary" />
                                        <input onclick="nv_delete_datacontent('fileitem_{FILEUPL.id}');return false;" type="button" value="{GLANG.delete}" class="selectfile btn btn-danger" />
                                    </div>
                                    <!-- END: files -->
                                </div></td>
                            </tr>
                            <!-- BEGIN: comment -->
                            <tr class="form-inline">
                                <td> {LANG.start_comm_time} </td>
                                <td><label>
                                    <input class="form-control" name="start_comm_time" id="start_comm_time" value="{DATA.start_comm_time}" style="width: 110px;" maxlength="10" type="text" />
                                    &nbsp;({LANG.prm})</label></td>
                            </tr>
                            <tr class="form-inline">
                                <td> {LANG.end_comm_time} </td>
                                <td><label>
                                    <input class="form-control" name="end_comm_time" id="end_comm_time" value="{DATA.end_comm_time}" style="width: 110px;" maxlength="10" type="text" />
                                    &nbsp;({LANG.prm})</label></td>
                            </tr>
                            <tr>
                                <td> {LANG.approval} </td>
                                <td>
                                <select class="form-control" name="approval" style="width: 200px">
                                    <option value="0"{DATA.e0}>{LANG.e0}</option>
                                    <option value="1"{DATA.e1}>{LANG.e1}</option>
                                </select>
                                </td>
                            </tr>
                            <!-- END: comment-->
                            <!-- BEGIN: normal_laws -->
                            <tr class="form-inline">
                                <td> {LANG.publtime}  <span class="red">*</span></td>
                                <td><label>
                                    <input class="form-control" name="publtime" id="publtime" value="{DATA.publtime}" style="width: 110px;" maxlength="10" type="text" />
                                    &nbsp;({LANG.prm})</label></td>
                            </tr>
                            <tr class="form-inline">
                                <td> {LANG.startvalid}</td>
                                <td><label>
                                    <input class="form-control" name="startvalid" id="startvalid" value="{DATA.startvalid}" style="width: 110px;" maxlength="10" type="text" />
                                    &nbsp;({LANG.prm})</label></td>
                            </tr>
                            <tr>
                                <td> {LANG.exptime} </td>
                                <td>
                                <select class="form-control" id="chooseexptime" name="chooseexptime" style="width: 200px">
                                    <option value="0"{DATA.select0}>{LANG.hl0}</option>
                                    <option value="1"{DATA.select1}>{LANG.hl1}</option>
                                </select>
                                <div id="exptimearea" style="display:{DATA.display}">
                                    <input class="form-control" name="exptime" id="exptime" value="{DATA.exptime}" style="width: 110px;" maxlength="10" type="text" />
                                    ({LANG.prm})
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#chooseexptime').change(function() {
                                            if ($(this).val() == 0) {
                                                $('#exptime').val('');
                                                $('#exptimearea').hide();
                                            } else {
                                                $('#exptimearea').show();
                                            }
                                        });
                                    });
                                </script></td>
                            </tr>
                            <!-- END: normal_laws -->
                            <tr>
                                <td> {LANG.replacement} ({LANG.ID}) </td>
                                <td>
                                <input class="form-control" title="{LANG.replacement}" type="text" name="replacement" id="replacement" style="width: 200px;" maxlength="255" value="{DATA.replacement}" />
                                </td>
                            </tr>
                            <tr>
                                <td> {LANG.relatement} ({LANG.ID}) </td>
                                <td>
                                <input class="form-control" title="{LANG.relatement}" type="text" name="relatement" id="relatement" style="width: 200px;" maxlength="255" value="{DATA.relatement}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{LANG.keywords}</td>
                                <td><label>
                                    <input title="{LANG.keywords}" class="form-control" style="width: 400px" type="text" name="keywords" value="{DATA.keywords}" maxlength="255" />
                                    ({LANG.keywordsNote})</label></td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top">{LANG.note}</td>
                                <td><textarea class="form-control" name="note" id="note">{DATA.note}</textarea></td>
                            </tr>
                            <tr>
                                <td>{LANG.introtext} <span class="red">*</span></td>
                                <td><textarea class="form-control" rows="5" name="introtext" id="introtext">{DATA.introtext}</textarea></td>
                            </tr>
                            <tr>
                                <td colspan="2">{CONTENT}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-24 col-md-6">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <td>{LANG.catSel} <span class="red">*</span></td>
                        </tr>
                        <tr>
                            <td>
                            <select class="form-control select2" title="{LANG.catSel}" name="cid" id="cid">
                                <!-- BEGIN: catopt -->
                                <option value="{CATOPT.id}"{CATOPT.selected}>{CATOPT.name}</option>
                                <!-- END: catopt -->
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td>{LANG.areaSel} <span class="red">*</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="height: 200px; overflow: scroll">
                                    <!-- BEGIN: areaopt -->
                                    <label class="show"><input type="checkbox" name="aid[]" value="{AREAOPT.id}" {AREAOPT.checked} />{AREAOPT.name}</label>
                                    <!-- END: areaopt -->
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{LANG.subjectSel} <span class="red">*</span></td>
                        </tr>
                        <tr>
                            <td>
                            <select class="form-control select2" title="{LANG.subjectSel}" name="sid">
                                <!-- BEGIN: subopt -->
                                <option value="{SUBOPT.id}"{SUBOPT.selected}>{SUBOPT.title}</option>
                                <!-- END: subopt -->
                            </select></td>
                        </tr>
                        <!-- BEGIN: loop -->
                        <tr>
                            <td>{LANG.ExamineSel} <span class="red">*</span></td>
                        </tr>
                        <tr>
                            <td>
                            <select class="form-control select2" title="{LANG.ExamineSel}" name="eid">
                                <!-- BEGIN: exbopt -->
                                <option value="{EXBOPT.id}"{EXBOPT.selected}>{EXBOPT.title}</option>
                                <!-- END: exbopt -->
                            </select></td>
                        </tr>
                         <!-- END: loop -->
                        <tr>
                            <td>{LANG.signer} <span class="red">*</span></td>
                        </tr>
                        <tr>
                            <td>
                            <select class="form-control" title="{LANG.signer}" name="sgid" id="signer">
                                <!-- BEGIN: singers -->
                                <option value="{SINGER.id}"{SINGER.selected}>{SINGER.title}</option>
                                <!-- END: singers -->
                            </select></td>
                        </tr>
                        <tr>
                            <td>{LANG.who_view}</td>
                        </tr>
                        <tr>
                            <td><!-- BEGIN: group_view -->
                            <div class="row">
                                <label>
                                    <input name="groups_view[]" type="checkbox" value="{GROUPS_VIEWS.id}" {GROUPS_VIEWS.checked} />
                                    {GROUPS_VIEWS.title}</label>
                            </div><!-- END: group_view --></td>
                        </tr>
                        <tr>
                            <td>{LANG.who_download}</td>
                        </tr>
                        <tr>
                            <td><!-- BEGIN: groups_download -->
                            <div class="row">
                                <label>
                                    <input name="groups_download[]" type="checkbox" value="{GROUPS_DOWNLOAD.id}" {GROUPS_DOWNLOAD.checked} />
                                    {GROUPS_DOWNLOAD.title}</label>
                            </div><!-- END: groups_download --></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <input type="hidden" name="save" value="1" />
        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </form>
</div>
<script type="text/javascript">
    //<![CDATA[
    $("#publtime,#startvalid,#end_comm_time,#start_comm_time,#exptime").datepicker({
        showOn : "both",
        yearRange: "2000:2025",
        dateFormat : "dd.mm.yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly : true
    });

    var nv_num_files = '{NUMFILE}';
    var nv_is_editor = '{IS_EDITOR}';

    $(document).ready(function() {
        $('.select2').select2();
        $('#signer').select2({
            tags: true,
            multiple: false,
            tokenSeparators: [',']
        });

        $("#replacementSearch").click(function() {
            nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getlid&area=replacement", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
            return false;
        });
        $("#amendmentSearch").click(function() {
            nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getlid&area=amendment", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
            return false;
        });
        $("#supplementSearch").click(function() {
            nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getlid&area=supplement", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
            return false;
        });
    });
    $("form#addRow").submit(function() {
        var a = $("[name=title]").val();
        a = trim(a);
        $("[name=title]").val(a);
        if (a.length < 2) {
            alert("{LANG.errorIsEmpty}: {LANG.title}");
            $("[name=title]").select();
            return !1;
        }

        if (trim($("[name=code]").val()) == "") {
            alert("{LANG.errorAreaYesCode}");
            $("[name=code]").select();
            return !1;
        }

        a = $("[name=introtext]").val();
        a = trim(a);
        $("[name=introtext]").val(a);
        if (a.length < 2) {
            alert("{LANG.errorIsEmpty}: {LANG.introtext}");
            $("[name=introtext]").select();
            return !1;
        }

        if( nv_is_editor == '1' )
        {
            $("textarea[name=bodytext]").val(CKEDITOR.instances.{MODULE_DATA}_bodytext.getData());
        }

        a = $(this).serialize();
        var b = $(this).attr("action");
        $("[type=submit]").attr("disabled", "disabled");
        $.ajax({
            type : "POST",
            url : b,
            data : a,
            success : function(c) {
                if (c == "OK") {
                    window.location = "{MODULE_URL}";
                } else {
                    alert(c);
                }
                $("[type=submit]").removeAttr("disabled");
            }
        });
        return !1;
    });
    //]]>
</script>
<!-- END: add -->