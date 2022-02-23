/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var isDebugMode = false;

function htmlspecialchars_decode(string, quote_style) {
    /*
     * Source: http://phpjs.org/functions/htmlspecialchars_decode/
     * Author: Mirek Slugen
     */
    var optTemp = 0,
        i = 0,
        noquotes = false;
    if (typeof quote_style === 'undefined') {
        quote_style = 2;
    }
    string = string.toString()
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>');
    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE': 1,
        'ENT_HTML_QUOTE_DOUBLE': 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE': 4
    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') {
        // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i = 0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;
            } else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }
        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
        // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
    }
    if (!noquotes) {
        string = string.replace(/&quot;/g, '"');
    }
    // Put this in last place to avoid escape being double-decoded
    string = string.replace(/&amp;/g, '&');

    return string;
}

function nv_filename_alt(fileAlt) {
    var lastChar = fileAlt.charAt(fileAlt.length - 1);

    if (lastChar === '/' || lastChar === '\\') {
        fileAlt = fileAlt.slice(0, -1);
    }

    fileAlt = decodeURIComponent(htmlspecialchars_decode(fileAlt.replace(/^.*[\/\\]/g, '')));
    fileAlt = fileAlt.split('.');

    if (fileAlt.length > 1) {
        fileAlt[fileAlt.length - 1] = '';
    }

    fileAlt = fileAlt.join(' ');
    fileAlt = fileAlt.split('_');
    fileAlt = fileAlt.join(' ');
    fileAlt = fileAlt.split('-');
    fileAlt = fileAlt.join(' ');
    return trim(fileAlt);
}

function nv_randomNum(a) {
    for (var b = "", d = 0; d < a; d++) {
        b += "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".charAt(Math.floor(Math.random() * 62));
    }

    return b;
}

// Return height
function resize_byWidth(a, b, d) {
    return parseInt(Math.round(d / a * b));
}

// Return width
function resize_byHeight(a, b, d) {
    return parseInt(Math.round(d / b * a));
}

// Resize image with display width and height (fix to container)
function calSize(a, b, d, e) {
    if (a > d) {
        b = resize_byWidth(a, b, d);
        a = d;
    }

    if (b > e) {
        a = resize_byHeight(a, b, e);
        b = e
    }

    return [parseInt(a), parseInt(b)];
}

function calSizeMax(a, b, d, e) {
    var g = d;
    d = resize_byWidth(a, b, d);

    if (!(d <= e)) {
        d = e;
        g = resize_byHeight(a, b, e);
    }

    return [parseInt(g), parseInt(d)];
}

function calSizeMin(a, b, d, e) {
    var g = d;
    d = resize_byWidth(a, b, d);

    if (!(d >= e)) {
        d = e;
        g = resize_byHeight(a, b, e);
    }

    return [parseInt(g), parseInt(d)];
}

function is_numeric(a) {
    return (typeof a === "number" || typeof a === "string") && a !== "" && !isNaN(a);
}

function checkNewSize() {
    var a = $("input[name=newWidth]").val(),
        b = $("input[name=newHeight]").val(),
        d = [],
        e = $("input[name=origWidth]").val(),
        g = $("input[name=origHeight]").val(),
        h = calSizeMax(e, g, nv_max_width, nv_max_height);
    e = calSizeMin(e, g, nv_min_width, nv_min_height);
    if (a == "" || !is_numeric(a)) {
        d = [LANG.errorEmptyX, "newWidth"];
    } else {
        if (a > h[0]) {
            d = [LANG.errorMaxX, "newWidth"];
        } else {
            if (a < e[0]) {
                d = [LANG.errorMinX, "newWidth"];
            } else {
                if (b == "" || !is_numeric(b)) {
                    d = [LANG.errorEmptyY, "newHeight"];
                } else {
                    if (b > h[1]) {
                        d = [LANG.errorMaxY, "newHeight"];
                    } else {
                        if (b < e[1]) {
                            d = [LANG.errorMinY, "newHeight"];
                        }
                    }
                }
            }
        }
    }
    $("div[title=createInfo]").find("div").remove();
    if (typeof d[0] != "undefined") {
        $("div[title=createInfo]").prepend('<div class="red">' + d[0] + "</div>");
        $("input[name='" + d[1] + "']").select();
        return false;
    }
    a = calSize(a, b, 360, 230);
    $("img[name=myFile2]").width(a[0]).height(a[1]);
    return true;
}

function pathList(a, b) {
    var d = [];
    $("#foldertree").find("span").each(function() {
        if ($(this).attr("title") == b || $(this).attr("title") != "" && $(this).is("." + a)) {
            d[d.length] = $(this).attr("title");
        }
    });
    return d;
}

// Dong cua so va tra ve gia tri
function insertvaluetofield() {
    var area = $("input[name=area]").val();
    var selFile = $("input[name=selFile]").val();
    var imageInfo = $("img[title='" + selFile + "']").attr("name").split("|");
    var path = (imageInfo[7] == "") ? $("span#foldervalue").attr("title") : imageInfo[7];
    var fullPath = nv_base_siteurl + path + "/" + selFile;

    if (area != '') {
        $("#" + area, opener.document).val(fullPath);

        var idalt = $("input[name=alt]").val();
        if (idalt != '') {
            fileAlt = nv_filename_alt(selFile);
            alt = $("img[title='" + selFile + "']").attr("alt");
            if (alt == fileAlt) {
                alt = '';
            }
            $("#" + idalt, opener.document).val(alt);
        }
        window.close();
    } else {
        if (window.opener === null) {
            return !1;
        }
        var CKEditorFuncNum = $("input[name=CKEditorFuncNum]").val();

        window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, fullPath, function() {
            var dialog = this.getDialog();

            if (dialog.getName() == 'image2') {
                var element = dialog.getContentElement('info', 'alt');

                if (element) {
                    element.setValue($("img[title='" + selFile + "']").attr("alt"));
                }
            }
        });

        window.close();
    }
}

// Tai file ve
function download() {
    var selFile = $("input[name=selFile]").val();
    var selFileData = $("img[title='" + selFile + "']").attr("name").split("|");

    fullPath = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];

    $("iframe#Fdownload").attr("src", nv_module_url + "dlimg&path=" + fullPath + "&img=" + selFile);
}

// Xem thong tin chi tiet
function preview() {
    $("div.dynamic").text("");
    $("input.dynamic").val("");
    $("div#fileView").removeClass("zoomin");

    var selFile = $("input[name=selFile]").val();
    var html = LANG.upload_size + ": ";

    var img = $("img[title='" + selFile + "']");
    var selFileData = img.attr("name").split("|");
    fullPath = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];

    if (selFileData[3] == "image" || selFileData[2] == "swf") {
        var size = calSize(selFileData[0], selFileData[1], 188, 120);
        html += selFileData[0] + " x " + selFileData[1] + " pixels (" + selFileData[4] + ")<br />";
        selFileData[3] == "image" ? $("div#fileView").html('<img width="' + size[0] + '" height="' + size[1] + '" src="' + nv_base_siteurl + fullPath + "/" + selFile + '?' + selFileData[8] + '" />') : $("#fileView").flash({
            src: nv_base_siteurl + fullPath + "/" + selFile,
            width: size[0],
            height: size[1]
        }, {
            version: 8
        });
        if (selFileData[3] == "image") {
            $("div#fileView").addClass("zoomin");
            $("div#fileView img").click(function() {
                $("#sitemodal").find(".modal-title").html(selFile);
                $("#sitemodal").find(".modal-body").html('<div class="text-center"><img class="img-responsive" src="' + nv_base_siteurl + fullPath + "/" + selFile + '?' + selFileData[8] + '" /></div>');
                $("#sitemodal").modal();
            });
        }
    } else {
        html += selFileData[4] + "<br />";
        $("div#fileView").html($("div[title='" + selFile + "'] div").html());
    }

    html += LANG.pubdate + ": " + selFileData[6];

    $("#fileInfoAlt").html($("img[title='" + selFile + "']").attr("alt"));
    $("#fileInfoDetail").html(html);
    $("#fileInfoName").html(selFile);
    $("#FileRelativePath").val(nv_base_siteurl + fullPath + "/" + selFile);
    $("#FileAbsolutePath").val(nv_my_domain + nv_base_siteurl + fullPath + "/" + selFile);

    $("div#imgpreview").dialog({
        autoOpen: false,
        width: 388,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        },
        open: function() {
            $("#FileRelativePath").blur();
            $("#FileRelativePath").focus(function() {
                $(this).select();
            });
            $("#FileAbsolutePath").focus(function() {
                $(this).select();
            });
            $("#FileRelativePathBtn").mouseout(function() {
                $(this).tooltip('destroy');
            });
            $("#FileAbsolutePathBtn").mouseout(function() {
                $(this).tooltip('destroy');
            });
            var clipboard1 = new ClipboardJS('#FileRelativePathBtn');
            var clipboard2 = new ClipboardJS('#FileAbsolutePathBtn');
            clipboard1.on('success', function(e) {
                $(e.trigger).tooltip('show');
            });
            clipboard2.on('success', function(e) {
                $(e.trigger).tooltip('show');
            });
        },
        close: function() {
            $('#FileRelativePathBtn').tooltip('destroy');
            $('#FileAbsolutePathBtn').tooltip('destroy');
        }
    }).dialog("open");
}

// Tao anh moi (Menu cong cu anh)
function create() {
    $("div.dynamic").text("");
    $("input.dynamic").val("");

    var selFile = $("input[name=selFile]").val();
    selFileData = $("img[title='" + selFile + "']").attr("name");
    selFileData = selFileData.split("|");

    if (selFileData[3] == "image") {
        path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];

        $("input[name=origWidth]").val(selFileData[0]);
        $("input[name=origHeight]").val(selFileData[1]);

        var SizeMax = calSizeMax(selFileData[0], selFileData[1], nv_max_width, nv_max_height);
        var SizeMin = calSizeMin(selFileData[0], selFileData[1], nv_min_width, nv_min_height);

        $("div[title=createInfo]").html("Max: " + SizeMax[0] + " x " + SizeMax[1] + ", Min: " + SizeMin[0] + " x " + SizeMin[1] + " (pixels)");

        DisSize = calSize(selFileData[0], selFileData[1], 360, 230);

        $("img[name=myFile2]").width(DisSize[0]).height(DisSize[1]).attr("src", nv_base_siteurl + path + "/" + selFile + "?" + selFileData[8]);
        $("#fileInfoDetail2").html(LANG.origSize + ": " + selFileData[0] + " x " + selFileData[1] + " pixels");
        $("#fileInfoName2").html(selFile);

        $("div#imgcreate").dialog({
            autoOpen: false,
            width: 650,
            modal: true,
            position: {
                my: "center",
                at: "center",
                of: window
            }
        }).dialog("open");
    }
}

// Di chuyen file
function move() {
    $("div.dynamic").text("");
    $("input.dynamic").prop("checked", false);
    $("select[name=newPath]").html("");

    var selected, e;
    var currentFolder = $("span#foldervalue").attr("title");
    var listPath = pathList("create_file", currentFolder);

    for (e in listPath) {
        selected = currentFolder == listPath[e] ? ' selected="selected"' : "";
        $("select[name=newPath]").append('<option value="' + listPath[e] + '"' + selected + ">" + listPath[e] + "</option>");
    }

    var selFile = $("input[name=selFile]").val();

    // Kiem tra di chuyen nhieu file hay di chuyen 1 file
    if (selFile.indexOf('|') == -1) {
        var selFileData = $("img[title='" + selFile + "']").attr("name").split("|");
        var path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];
        var moveMessage = path + "/" + selFile;
    } else {
        selFile = selFile.split('|');
        var selFileData = $("img[title='" + selFile[0] + "']").attr("name").split("|");
        var path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];
        var moveMessage = LANG.move_multiple.replace('%s', selFile.length) + ".";
        selFile = selFile.join('|');
    }

    $("div[title=pathFileName]").text(moveMessage);
    $("div#filemove").dialog({
        autoOpen: false,
        width: 300,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        }
    }).dialog("open");
}

// Doi ten file
function filerename() {
    $("div.dynamic, span.dynamic").text("");
    $("input.dynamic").val("");

    var a = $("input[name=selFile]").val();

    $("div#filerenameOrigName").text(a);
    $("input[name=filerenameNewName]").val(a.replace(/^(.+)\.([a-zA-Z0-9]+)$/, "$1"));
    $("span[title=Ext]").text("." + a.replace(/^(.+)\.([a-zA-Z0-9]+)$/, "$2"));
    $("input[name=filerenameAlt]").val($("img[title='" + a + "']").attr("alt"));

    $("div#filerename").dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        }
    }).dialog("open");
}

// Goi chuc nang xoa file
function filedelete() {
    var imgtype = $("select[name=imgtype]").val();
    var author = $("select[name=author]").val() == 1 ? "&author" : "";
    var order = $("select[name=order]").val();

    var selFile = $("input[name=selFile]").val();

    // Kiem tra xoa nhieu file hay xoa 1 file
    if (selFile.indexOf('|') == -1) {
        var selFileData = $("img[title='" + selFile + "']").attr("name").split("|");
        var path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];
        var confirmMessage = LANG.upload_delimg_confirm + " " + selFile + " ?";
    } else {
        selFile = selFile.split('|');
        var selFileData = $("img[title='" + selFile[0] + "']").attr("name").split("|");
        var path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];
        var confirmMessage = LANG.upload_delimgs_confirm.replace('%s', selFile.length) + "?";
        selFile = selFile.join('|');
    }

    if (confirm(confirmMessage)) {
        $.ajax({
            type: "POST",
            url: nv_module_url + "delimg",
            data: "path=" + path + "&file=" + selFile,
            success: function(e) {
                e = e.split('#');

                if (e[0] != 'OK') {
                    alert(e[1]);
                } else {
                    $("#imglist").html(nv_loading_data).load(nv_module_url + "imglist&path=" + path + "&type=" + imgtype + author + "&order=" + order + "&num=" + +nv_randomNum(10), function() {
                        LFILE.setViewMode();
                    });
                }
            }
        });
    }
}

// Ham xu ly khi nhap chuot vao 1 file (Chuot trai, chuot giua lan chuot phai)
function fileMouseup(file, e) {
    // Khong xu ly neu jquery UI selectable dang kich hoat
    if (KEYPR.isFileSelectable == false) {
        // Set shift offset
        if (e.which != 3 && !KEYPR.isShift) {
            // Reset shift offset
            KEYPR.shiftOffset = 0;

            $.each($('.imgcontent'), function(k, v) {
                if (v == file) {
                    KEYPR.shiftOffset = k;
                    return false;
                }
            });
        }

        // e.which: 1: Left Mouse, 2: Center Mouse, 3: Right Mouse
        if (KEYPR.isCtrl) {
            if ($(file).is('.imgsel') && e.which != 3) {
                $(file).removeClass('imgsel');
            } else {
                $(file).addClass('imgsel');
            }
        } else if (KEYPR.isShift && e.which != 3) {
            var clickOffset = -1;
            $('.imgcontent').removeClass('imgsel');

            $.each($('.imgcontent'), function(k, v) {
                if (v == file) {
                    clickOffset = k;
                }

                if ((clickOffset == -1 && k >= KEYPR.shiftOffset) || (clickOffset != -1 && k <= KEYPR.shiftOffset) || v == file) {
                    if (!$(v).is('.imgsel')) {
                        $(v).addClass('imgsel');
                    }
                }
            });
        } else {
            if (e.which != 3 || (e.which == 3 && !$(file).is('.imgsel'))) {
                $('.imgsel').removeClass('imgsel');
                $(file).addClass('imgsel');
            }
        }

        LFILE.setSelFile();

        if (e.which == 3) {
            var isMultiple = $('.imgsel').length === 1 ? false : true;
            var fileExt = $("input[name=selFile]").val().split('.').pop();
            var CKEditorFuncNum = $("input[name=CKEditorFuncNum]").val();
            var area = $("input[name=area]").val();
            var html = "";

            if ((CKEditorFuncNum > 0 || area != "") && !isMultiple) {
                html += '<li id="select"><em class="fa fa-lg ' + ICON.select + '">&nbsp;</em>' + LANG.select + '</li>';
            }

            if (!isMultiple) {
                html += '<li id="download"><em class="fa fa-lg ' + ICON.download + '">&nbsp;</em>' + LANG.download + '</li>';
                html += '<li id="filepreview"><em class="fa fa-lg ' + ICON.preview + '">&nbsp;</em>' + LANG.preview + '</li>';
            }

            if ($.inArray(fileExt, array_images) !== -1) {
                if ($("span#create_file").attr("title") == "1" && !isMultiple) {
                    html += '<li id="fileaddlogo"><em class="fa fa-lg ' + ICON.addlogo + '">&nbsp;</em>' + LANG.addlogo + '</li>';
                    html += '<li id="create"><em class="fa fa-lg ' + ICON.create + '">&nbsp;</em>' + LANG.upload_createimage + '</li>';
                    html += '<li id="cropfile"><em class="fa fa-lg ' + ICON.filecrop + '">&nbsp;</em>' + LANG.crop + '</li>';
                    html += '<li id="rotatefile"><em class="fa fa-lg ' + ICON.filerotate + '">&nbsp;</em>' + LANG.rotate + '</li>';
                }
            }

            if ($("span#move_file").attr("title") == "1") {
                html += '<li id="move"><em class="fa fa-lg ' + ICON.move + '">&nbsp;</em>' + LANG.move + '</li>';
            }

            if ($("span#rename_file").attr("title") == "1" && !isMultiple) {
                html += '<li id="rename"><em class="fa fa-lg ' + ICON.rename + '">&nbsp;</em>' + LANG.rename + '</li>';
            }

            if ($("span#delete_file").attr("title") == "1") {
                html += '<li id="filedelete"><em class="fa fa-lg ' + ICON.filedelete + '">&nbsp;</em>' + LANG.upload_delfile + '</li>';
            }

            if (html != '') {
                html = "<ul>" + html + "</ul>";
            }

            $("div#contextMenu").html(html);
            NVCMENU.show(e);
        }

    }

    KEYPR.isFileSelectable = false;
}

// Ham xu ly khi click chuot vao thu muc (Xem chi tiet, dinh quyen doi ten, tao thu muc moi, xoa thu muc)
function folderMouseup(folder, e) {
    if (e.which != 3) { // Left mouse click
        var folderPath = $(folder).attr("title");
        if (folderPath != $("span#foldervalue").attr("title")) {
            $("span#foldervalue").attr("title", folderPath);
            $("span#view_dir").attr("title", $(folder).is(".view_dir") ? "1" : "0");
            $("span#create_dir").attr("title", $(folder).is(".create_dir") ? "1" : "0");
            $("span#recreatethumb").attr("title", $(folder).is(".recreatethumb") ? "1" : "0");
            $("span#rename_dir").attr("title", $(folder).is(".rename_dir") ? "1" : "0");
            $("span#delete_dir").attr("title", $(folder).is(".delete_dir") ? "1" : "0");
            $("span#upload_file").attr("title", $(folder).is(".upload_file") ? "1" : "0");
            $("span#create_file").attr("title", $(folder).is(".create_file") ? "1" : "0");
            $("span#rename_file").attr("title", $(folder).is(".rename_file") ? "1" : "0");
            $("span#delete_file").attr("title", $(folder).is(".delete_file") ? "1" : "0");
            $("span#move_file").attr("title", $(folder).is(".move_file") ? "1" : "0");
            $("span#crop_file").attr("title", $(folder).is(".crop_file") ? "1" : "0");
            $("span#rotate_file").attr("title", $(folder).is(".rotate_file") ? "1" : "0");
            $("span.folder").css("color", "");

            $(folder).css("color", "red");

            if ($(folder).is(".view_dir")) {
                var imgtype = $("select[name=imgtype]").val();
                var selFile = $("input[name=selFile]").val();
                var author = $("select[name=author]").val() == 1 ? "&author" : "";

                $("div#imglist").html(nv_loading_data).load(nv_module_url + "imglist&path=" + folderPath + "&imgfile=" + selFile + "&type=" + imgtype + author + "&order=" + $("select[name=order]").val() + "&random=" + nv_randomNum(10), function() {
                    LFILE.setViewMode();
                })
            } else {
                $("div#imglist").text("");
            }

            setTimeout(function() {
                NVUPLOAD.init();
            }, 50);
        }
    } else if ($(folder).is('.menu')) { // Right mouse click
        $("span.folder").attr("name", "");
        $(folder).attr("name", "current");

        var html = "";

        if ($(folder).is(".create_dir")) {
            html += '<li id="createfolder"><em class="fa fa-lg ' + ICON.create + '">&nbsp;</em>' + LANG.createfolder + '</li>'
        }
        if ($(folder).is(".recreatethumb")) {
            html += '<li id="recreatethumb"><em class="fa fa-lg ' + ICON.recreatethumb + '">&nbsp;</em>' + LANG.recreatethumb + '</li>'
        }
        if ($(folder).is(".rename_dir")) {
            html += '<li id="renamefolder"><em class="fa fa-lg ' + ICON.rename + '">&nbsp;</em>' + LANG.renamefolder + '</li>'
        }

        if ($(folder).is(".delete_dir")) {
            html += '<li id="deletefolder"><em class="fa fa-lg ' + ICON.filedelete + '">&nbsp;</em>' + LANG.deletefolder + '</li>'
        }

        if (html != "") {
            html = "<ul>" + html + "</ul>"
        }

        $("div#contextMenu").html(html);
        NVCMENU.show(e);
    }
}

// Doi ten thu muc
function renamefolder() {
    var a = $("span[name=current]").attr("title").split("/");
    a = a[a.length - 1];
    $("input[name=foldername]").val(a);
    $("div#renamefolder").dialog("open");
}

// Tao thu muc
function createfolder() {
    $("input[name=createfoldername]").val("");
    $("div#createfolder").dialog("open")
}

// Tạo lại ảnh Thumb
function recreatethumb() {
    $("div#recreatethumb").dialog("open")
    $("#recreatethumb_ok").show();
    $("#recreatethumb_loading").html(LANG.recreatethumb_note);
}

// Xoa thu muc
function deletefolder() {
    if (confirm(LANG.delete_folder)) {
        var a = $("span[name=current]").attr("title");
        $.ajax({
            type: "POST",
            url: nv_module_url + "delfolder&random=" + nv_randomNum(10),
            data: "path=" + a,
            success: function(b) {
                b = b.split("_");
                if (b[0] == "ERROR") {
                    alert(b[1])
                } else {
                    b = a.split("/");
                    a = "";
                    for (i = 0; i < b.length - 1; i++) {
                        if (a != "") {
                            a += "/"
                        }
                        a += b[i]
                    }
                    b = $("select[name=imgtype]").val();
                    var d = $("select[name=author]").val() == 1 ? "&author" : "",
                        e = $("span#path").attr("title"),
                        g = $("input[name=selFile]").val();
                    $("#imgfolder").load(nv_module_url + "folderlist&path=" + e + "&currentpath=" + a + "&random=" + nv_randomNum(10));
                    $("div#imglist").load(nv_module_url + "imglist&path=" + a + "&imgfile=" + g + "&type=" + b + d + "&order=" + $("select[name=order]").val() + "&order=" + $("select[name=order]").val() + "&random=" + nv_randomNum(10), function() {
                        LFILE.setViewMode();
                    })
                }
            }
        })
    }
}

// Tim kiem file
function searchfile() {
    a = $("select[name=searchPath]").val(), q = $("input[name=q]").val();
    b = $("select[name=imgtype]").val(), e = $("select[name=author]").val() == 1 ? "&author" : "";
    $("div#filesearch").dialog("close");
    $("#imglist").html(nv_loading_data).load(nv_module_url + 'imglist&path=' + a + '&type=' + b + e + '&q=' + rawurlencode(q) + '&order=' + $('select[name=order]').val() + '&random=' + nv_randomNum(10), function() {
        LFILE.setViewMode();
    })
    return false;
}

// Cat anh
function cropfile() {
    $("div.dynamic").html("");
    $("input.dynamic").val("");

    var selFile = $("input[name=selFile]").val();
    var selFileData = $("img[title='" + selFile + "']").attr("name").split("|");
    var path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];
    var size = calSize(selFileData[0], selFileData[1], 360, 360);

    selFileData[0] = parseInt(selFileData[0]);
    selFileData[1] = parseInt(selFileData[1]);

    $('#cropContent').css({
        'width': size[0] + 4,
        'height': size[1] + 4
    }).html('<img class="crop-image" src="' + nv_base_siteurl + path + "/" + selFile + '?' + selFileData[8] + '"  width="' + size[0] + '" height="' + size[1] + '"/>');

    // Check size
    if (selFileData[0] < 10 || selFileData[1] < 10 || (selFileData[0] < 16 && selFileData[1] < 16)) {
        $('#cropButtons').html('<span class="text-danger">' + LANG.crop_error_small + '</span>');
    } else {
        $('#cropButtons').html(
            '<div class="margin-left text-left"><label><input type="checkbox" id="crop-keeporg" value="1"/>' + LANG.crop_keep_original + '</label></div>' +
            'X:<input type="text" id="crop-x" value="" class="w50 form-control" readonly="readonly"/> ' +
            'Y:<input type="text" id="crop-y" value="" class="w50 form-control" readonly="readonly"/> ' +
            'W:<input type="text" id="crop-w" value="" class="w50 form-control" readonly="readonly"/> ' +
            'H:<input type="text" id="crop-h" value="" class="w50 form-control" readonly="readonly"/> ' +
            '<input type="button" id="crop-save" value="' + LANG.save + '" class="btn btn-primary"/>'
        );

        // Init cropper
        $('#cropContent img.crop-image').cropper({
            viewMode: 3,
            dragMode: 'crop',
            aspectRatio: NaN,
            responsive: true,
            modal: true,
            guides: false,
            highlight: true,
            autoCrop: true,
            autoCropArea: 0.5,
            movable: false,
            rotatable: false,
            scalable: false,
            zoomable: false,
            zoomOnTouch: false,
            zoomOnWheel: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            minContainerWidth: 10,
            minContainerHeight: 10,
            crop: function(e) {
                $('#crop-x').val(parseInt(Math.floor(e.x)));
                $('#crop-y').val(parseInt(Math.floor(e.y)));
                $('#crop-w').val(parseInt(Math.floor(e.width)));
                $('#crop-h').val(parseInt(Math.floor(e.height)));
            }
        });

        $('#crop-save').click(function() {
            $(this).attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: nv_module_url + "cropimg&random=" + nv_randomNum(10),
                data: "path=" + path + "&file=" + selFile + "&x=" + $('#crop-x').val() + "&y=" + $('#crop-y').val() + "&w=" + $('#crop-w').val() + "&h=" + $('#crop-h').val() + "&k=" + ($("#crop-keeporg").is(":checked") ? 1 : 0),
                success: function(e) {
                    $('#crop-save').removeAttr('disabled');
                    e = e.split('#');

                    if (e[0] == 'ERROR') {
                        $("div#errorInfo").html(e[1]).dialog("open");
                    } else {
                        LFILE.reload(path, e[1]);
                        $("div#cropimage").dialog('close');
                    }
                }
            });
        });
    }

    $("div#cropimage").dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        }
    }).dialog("open");
}

// Them logo
function addlogo() {
    $("div.dynamic").html("");
    $("input.dynamic").val("");

    var selFile = $("input[name=selFile]").val();
    var selFileData = $("img[title='" + selFile + "']").attr("name").split("|");
    var path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];
    var size = calSize(selFileData[0], selFileData[1], 360, 360);
    var logo = $("input[name=upload_logo]").val();
    var logoConfig = $("input[name=upload_logo_config]").val().split('|');

    selFileData[0] = parseInt(selFileData[0]);
    selFileData[1] = parseInt(selFileData[1]);

    $('#addlogoContent').css({
        'width': size[0] + 4,
        'height': size[1] + 4
    }).html('<img class="addlogo-image" src="' + nv_base_siteurl + path + "/" + selFile + '?' + selFileData[8] + '"  width="' + size[0] + '" height="' + size[1] + '"/>');

    // Check size
    if (selFileData[0] < 10 || selFileData[1] < 10 || (selFileData[0] < 16 && selFileData[1] < 16)) {
        $('#addlogoButtons').html('<span class="text-danger">' + LANG.addlogo_error_small + '</span>');
    } else if (logo == '') {
        $('#addlogoButtons').html('<span class="text-danger">' + LANG.notlogo + '</span>');
    } else {
        $('#addlogoButtons').html(
            'X:<input type="text" id="addlogo-x" value="" class="w50 form-control" readonly="readonly"/> ' +
            'Y:<input type="text" id="addlogo-y" value="" class="w50 form-control" readonly="readonly"/> ' +
            'W:<input type="text" id="addlogo-w" value="" class="w50 form-control" readonly="readonly"/> ' +
            'H:<input type="text" id="addlogo-h" value="" class="w50 form-control" readonly="readonly"/> ' +
            '<input type="button" id="addlogo-save" value="' + LANG.save + '" class="btn btn-primary"/>'
        );

        // Set logo size
        var markW, markH;

        if (selFileData[0] <= 150) {
            markW = Math.ceil(selFileData[0] * parseFloat(logoConfig[2]) / 100);
        } else if (selFileData[0] < 350) {
            markW = Math.ceil(selFileData[0] * parseFloat(logoConfig[3]) / 100);
        } else {
            if (Math.ceil(selFileData[0] * parseFloat(logoConfig[4]) / 100) > logoConfig[0]) {
                markW = logoConfig[0];
            } else {
                markW = Math.ceil(selFileData[0] * parseFloat(logoConfig[4]) / 100);
            }
        }

        markH = Math.ceil(markW * logoConfig[1] / logoConfig[0]);

        if (markH > selFileData[1]) {
            markH = selFileData[1];
            markW = Math.ceil(markH * logoConfig[0] / logoConfig[1]);
        }

        // Init cropper
        $('#addlogoContent img.addlogo-image').cropper({
            viewMode: 3,
            dragMode: 'none',
            aspectRatio: markW / markH,
            responsive: true,
            modal: true,
            guides: false,
            highlight: true,
            autoCrop: false,
            autoCropArea: .01,
            movable: false,
            rotatable: false,
            scalable: false,
            zoomable: false,
            zoomOnTouch: false,
            zoomOnWheel: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            minContainerWidth: 10,
            minContainerHeight: 10,
            crop: function(e) {
                $('#addlogo-x').val(parseInt(Math.floor(e.x)));
                $('#addlogo-y').val(parseInt(Math.floor(e.y)));
                $('#addlogo-w').val(parseInt(Math.floor(e.width)));
                $('#addlogo-h').val(parseInt(Math.floor(e.height)));
            },
            built: function(e) {
                var imageData = $(this).cropper('getImageData');
                var cropBoxScale = imageData.naturalWidth / imageData.width;
                var cropBoxSize = {
                    width: markW / cropBoxScale,
                    height: markH / cropBoxScale
                };
                cropBoxSize.left = imageData.width - cropBoxSize.width - 10;
                cropBoxSize.top = imageData.height - cropBoxSize.height - 10;
                $(this).cropper('crop');
                $(this).cropper('setCropBoxData', {
                    left: cropBoxSize.left,
                    top: cropBoxSize.top,
                    width: cropBoxSize.width,
                    height: cropBoxSize.height
                });
                var wrapCropper = $(this).parent();
                $('.cropper-face', wrapCropper).css({
                    'opacity': 1,
                    'background-image': 'url(' + logo + ')',
                    'background-size': '100%',
                    'background-color': 'transparent'
                });
            }
        });

        $('#addlogo-save').click(function() {
            $(this).attr('disabled', 'disabled');

            $.ajax({
                type: "POST",
                url: nv_module_url + "addlogo&random=" + nv_randomNum(10),
                data: "path=" + path + "&file=" + selFile + "&x=" + $('#addlogo-x').val() + "&y=" + $('#addlogo-y').val() + "&w=" + $('#addlogo-w').val() + "&h=" + $('#addlogo-h').val(),
                success: function(e) {
                    $('#addlogo-save').removeAttr('disabled');
                    e = e.split('#');

                    if (e[0] == 'ERROR') {
                        $("div#errorInfo").html(e[1]).dialog("open");
                    } else {
                        LFILE.reload(path, selFile);
                        $("div#addlogo").dialog('close');
                    }
                }
            });
        });
    }

    $("div#addlogo").dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        }
    }).dialog("open");
}

// Xoay anh
function rotatefile() {
    $("div.dynamic").text("");
    $("input.dynamic").val("");
    $('[name="rorateDirection"]').val('0');

    var selFile = $("input[name=selFile]").val();
    var selFileData = $("img[title='" + selFile + "']").attr("name").split("|");
    var path = (selFileData[7] == "") ? $("span#foldervalue").attr("title") : selFileData[7];
    var size = calSize(selFileData[0], selFileData[1], 360, 230);

    $('#rorateimageName').html(selFile);
    $('[name="rorateFile"]').val(selFile);
    $('[name="roratePath"]').val(path);

    var contentMargin = parseInt((Math.sqrt(size[0] * size[0] + size[1] * size[1]) - size[1]) / 2);

    $('#rorateContent').css({
        'width': size[0],
        'height': size[1],
        'margin-top': contentMargin,
        'margin-bottom': contentMargin + 10
    }).html('<img src="' + nv_base_siteurl + path + "/" + selFile + '?' + selFileData[8] + '"  width="' + size[0] + '" height="' + size[1] + '"/>');

    $("div#rorateimage").dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        }
    }).dialog("open");

    // Dat lai gia tri xoay
    RRT.direction = 0;
    RRT.currentDirection = 0;
}

// Xu ly keo tha chuot chon file
function fileSelecting(e, ui) {
    if (e.ctrlKey) {
        if ($(ui.selecting).is('.imgsel')) {
            $(ui.selecting).addClass('imgtempunsel');
        } else {
            $(ui.selecting).addClass('imgtempsel');
        }
    } else if (e.shiftKey) {
        $(ui.selecting).addClass('imgtempsel');
    } else {
        $(ui.selecting).removeClass('imgtempunsel').addClass('imgtempsel');
        $('#imglist .imgcontent:not(.imgtempsel)').addClass('imgtempunsel');
    }
}

// Xu ly khi thoi chon file
function fileUnselect(e, ui) {
    $(ui.unselecting).removeClass('imgtempunsel imgtempsel');
}

// Xu ly khi ket thuc chon file
function fileSelectStop(e, ui) {
    $('#imglist .ui-selected').removeClass('ui-selected');
    $('.imgtempsel').addClass('imgsel').removeClass('imgtempsel');
    $('.imgtempunsel').removeClass('imgsel imgtempunsel');
    LFILE.setSelFile();
}

function enRefreshBtn(btn, state) {
    if (state >= 2) {
        btn.removeClass(ICON.spin);
        btn.data('busy', false);
    }
}

var ICON = [];
ICON.select = 'fa-check-square-o';
ICON.download = 'fa-download';
ICON.preview = 'fa-eye';
ICON.create = 'fa-files-o';
ICON.recreatethumb = 'fa-refresh';
ICON.move = 'fa-arrows';
ICON.rename = 'fa-pencil-square-o';
ICON.filedelete = 'fa-trash-o';
ICON.filecrop = 'fa-crop';
ICON.filerotate = 'fa-repeat';
ICON.addlogo = 'fa-file-image-o';
ICON.spin = 'fa-spin';

$(".vchange").change(function() {
    var a = $("span#foldervalue").attr("title"),
        b = $("input[name=selFile]").val(),
        d = $("select[name=imgtype]").val(),
        e = $("select[name=author]").val() == 1 ? "&author" : "";
    $("#imglist").html(nv_loading_data).load(nv_module_url + "imglist&path=" + a + "&type=" + d + "&imgfile=" + b + e + "&order=" + $("select[name=order]").val() + "&random=" + nv_randomNum(10), function() {
        LFILE.setViewMode();
    })
});

$(".refresh em").click(function() {
    var $this = $(this);
    if ($this.data('busy')) {
        return;
    }
    $this.data('busy', true);
    $this.addClass(ICON.spin);

    var a = $("span#foldervalue").attr("title"),
        b = $("select[name=imgtype]").val(),
        d = $("input[name=selFile]").val(),
        e = $("select[name=author]").val() == 1 ? "&author" : "",
        g = $("span#path").attr("title"),
        loaded = 0;

    $("#imgfolder").html(nv_loading_data).load(nv_module_url + "folderlist&path=" + g + "&currentpath=" + a + "&dirListRefresh&random=" + nv_randomNum(10), function() {
        loaded++;
        (isDebugMode && console.log("Loaded list folder!"));
        enRefreshBtn($this, loaded);
    });
    $("#imglist").html(nv_loading_data).load(nv_module_url + "imglist&path=" + a + "&type=" + b + "&imgfile=" + d + e + "&refresh&order=" + $("select[name=order]").val() + "&random=" + nv_randomNum(10), function() {
        loaded++;
        (isDebugMode && console.log("Loaded list files!"));
        enRefreshBtn($this, loaded);
        LFILE.setViewMode();
    });

    return false;
});

$(".viewmode em").click(function() {
    $(this).data('auto', false);
    $('#imglist').toggleClass('view-detail');
    LFILE.setViewIcon();
});

$(".search em").click(function() {
    var a = $("span#foldervalue").attr("title"),
        b = pathList("create_file", a),
        d, e;
    $("select[name=searchPath]").html("");
    for (e in b) {
        d = a == b[e] ? ' selected="selected"' : "";
        $("select[name=searchPath]").append('<option value="' + b[e] + '"' + d + ">" + b[e] + "</option>")
    }
    $("div#filesearch").dialog({
        autoOpen: false,
        width: 300,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        }
    }).dialog("open");
    $("input[name=q]").val("").focus();
    return false
});

$("div#errorInfo").dialog({
    autoOpen: false,
    width: 300,
    height: 180,
    modal: true,
    position: {
        my: "center",
        at: "center",
        of: window
    },
    show: "slide"
});

$("div#renamefolder").dialog({
    autoOpen: false,
    width: 250,
    height: 160,
    modal: true,
    position: {
        my: "center",
        at: "center",
        of: window
    },
    buttons: {
        Ok: function() {
            var a = $("span[name=current]").attr("title"),
                b = $("input[name=foldername]").val(),
                d = $("span#foldervalue").attr("title"),
                e = a.split("/");
            e = e[e.length - 1];
            if (b == "" || b == e || !nv_namecheck.test(b)) {
                alert(LANG.rename_nonamefolder);
                $("input[name=foldername]").focus();
                return false
            }
            e = $("span[name=current]").attr("class").split(" ");
            e = e[e.length - 1];
            var g = true;
            $("span." + e).each(function() {
                var h = $(this).attr("title").split("/");
                h = h[h.length - 1];
                if (b == h) {
                    g = false
                }
            });
            if (!g) {
                alert(LANG.folder_exists);
                $("input[name=foldername]").focus();
                return false
            }
            $.ajax({
                type: "POST",
                url: nv_module_url + "renamefolder&random=" + nv_randomNum(10),
                data: "path=" + a + "&newname=" + b,
                success: function(h) {
                    var j = h.split("_");
                    if (j[0] == "ERROR") {
                        alert(j[1])
                    } else {
                        j = h.split("/");
                        j = j[j.length - 1];
                        var k;
                        $("span[name=current]").parent().find("span").each(function() {
                            k = $(this).attr("title");
                            k = k.replace(a, h);
                            $(this).attr("title") == d && $("span#foldervalue").attr("title", k);
                            $(this).attr("title", k)
                        });
                        $("span[name=current]").html("&nbsp;" + j).attr("title", h)
                    }
                }
            });
            $(this).dialog("close")
        }
    }
});

$("div#createfolder").dialog({
    autoOpen: false,
    width: 260,
    height: 170,
    modal: true,
    position: {
        my: "center",
        at: "center",
        of: window
    },
    buttons: {
        Ok: function() {
            var a = $("input[name=createfoldername]").val(),
                b = $("span[name=current]").attr("title");
            if (a == "" || !nv_namecheck.test(a)) {
                alert(LANG.name_folder_error);
                $("input[name=createfoldername]").focus();
                return false
            }
            $.ajax({
                type: "POST",
                url: nv_module_url + "createfolder&random=" + nv_randomNum(10),
                data: "path=" + b + "&newname=" + a,
                success: function(d) {
                    var e = d.split("_");
                    if (e[0] == "ERROR") {
                        alert(e[1])
                    } else {
                        e = $("select[name=imgtype]").val();
                        var g = $("select[name=author]").val() == 1 ? "&author" : "",
                            h = $("span#path").attr("title");
                        $("#imgfolder").load(nv_module_url + "folderlist&path=" + h + "&currentpath=" + d + "&random=" + nv_randomNum(10));
                        $("div#imglist").load(nv_module_url + "imglist&path=" + d + "&type=" + e + g + "&order=" + $("select[name=order]").val() + "&random=" + nv_randomNum(10), function() {
                            LFILE.setViewMode();
                        })
                    }
                }
            });
            $(this).dialog("close")
        }
    }
});

var timer_recreatethumb = 0;

function nv_recreatethumb_loop(a, idf) {
    clearTimeout(timer_recreatethumb);
    $.ajax({
        type: "POST",
        url: nv_module_url + "recreatethumb&random=" + nv_randomNum(10),
        data: "path=" + a + "&idf=" + idf,
        success: function(d) {
            var e = d.split("_");
            if (e[0] == "ERROR") {
                alert(e[1])
            } else if (e[0] == "OK") {
                timer_recreatethumb = setTimeout(function() {
                    var loadarea = $("#recreatethumb_loading");
                    var per = (parseInt(e[1]) / parseInt(e[2])) * 100;
                    if (!$('.progress', loadarea).length) {
                        var html = '';
                        html += '<br /><div class="progress">';
                        html += '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>';
                        html += '</div>';
                        html += '<h3 class="text-center">' + LANG.recreatethumb + ': <strong><span class="mcur">' + e[1] + '</span> / ' + e[2] + '</strong> file.</h3>';
                        loadarea.html(html);
                    }
                    $('.progress-bar', loadarea).css({
                        width: per + '%'
                    });
                    $('.mcur', loadarea).html(e[1]);
                    nv_recreatethumb_loop(a, e[1]);
                }, 1000);
            } else if (e[0] == "COMPLETE") {
                $("#recreatethumb_loading").html("<div class=\"alert alert-success\">" + LANG.recreatethumb_result + " <strong>" + e[1] + "</strong> file.</div>");
                setTimeout(function() {
                    $("div#recreatethumb").dialog("close");
                }, 3000);
            }
        }
    });
}

$("div#recreatethumb").dialog({
    autoOpen: false,
    width: 500,
    height: 270,
    modal: true,
    position: {
        my: "center",
        at: "center",
        of: window
    },
    buttons: [{
        text: "OK",
        id: "recreatethumb_ok",
        click: function() {
            $("#recreatethumb_ok").parent().parent().hide();
            $("#recreatethumb_loading").html(nv_loading_data);
            var b = $("span[name=current]").attr("title");
            timer_recreatethumb = setTimeout(function() {
                nv_recreatethumb_loop(b, -1);
            }, 500);
        }
    }],
    close: function() {
        if (timer_recreatethumb) {
            clearTimeout(timer_recreatethumb);
        }
        if ($("#recreatethumb_ok").length) {
            $("#recreatethumb_ok").parent().parent().show();
        }
        $("#recreatethumb_loading").html('');
    }
});

$("input[name=newWidth], input[name=newHeight]").keyup(function() {
    var a = $(this).attr("name"),
        b = $("input[name='" + a + "']").val(),
        d = $("input[name=origWidth]").val(),
        e = $("input[name=origHeight]").val(),
        g = calSizeMax(d, e, nv_max_width, nv_max_height);
    g = a == "newWidth" ? g[0] : g[1];
    if (!is_numeric(b) || b > g || b < 0) {
        $("input[name=newWidth]").val("");
        $("input[name=newHeight]").val("")
    } else {
        a == "newWidth" ? $("input[name=newHeight]").val(resize_byWidth(d, e, b)) : $("input[name=newWidth]").val(resize_byHeight(d, e, b))
    }
});

$("[name=prView]").click(function() {
    checkNewSize();
});

$("[name=newSizeOK]").click(function() {
    var a = $("input[name=newWidth]").val(),
        b = $("input[name=newHeight]").val(),
        d = $("input[name=origWidth]").val(),
        e = $("input[name=origHeight]").val();
    if (a == d && b == e) {
        $("div#imgcreate").dialog("close")
    } else {
        if (checkNewSize() !== false) {
            $(this).attr("disabled", "disabled");
            d = $("input[name=selFile]").val();
            var g = $("span#foldervalue").attr("title");
            $.ajax({
                type: "POST",
                url: nv_module_url + "createimg",
                data: "path=" + g + "&img=" + d + "&width=" + a + "&height=" + b + "&num=" + nv_randomNum(10),
                success: function(h) {
                    var j = h.split("_");
                    if (j[0] == "ERROR") {
                        alert(j[1]);
                        $("[name=newSizeOK]").removeAttr("disabled")
                    } else {
                        j = $("select[name=imgtype]").val();
                        var k = $("select[name=author]").val() == 1 ? "&author" : "";
                        $("input[name=selFile]").val(h);
                        $("[name=newSizeOK]").removeAttr("disabled");
                        $("div#imgcreate").dialog("close");
                        $("#imglist").load(nv_module_url + "imglist&path=" + g + "&type=" + j + "&imgfile=" + h + k + "&order=" + $("select[name=order]").val() + "&num=" + +nv_randomNum(10), function() {
                            LFILE.setViewMode();
                        })
                    }
                }
            })
        }
    }
});

// Di chuyen file (Luu lai)
$("input[name=newPathOK]").click(function() {
    var currentFolder = $("span#foldervalue").attr("title");
    var newPath = $("select[name=newPath]").val();
    var selFile = $("input[name=selFile]").val();
    var mirrorFile = $("input[name=mirrorFile]:checked").length;

    if (currentFolder == newPath) {
        $("div#filemove").dialog("close");
    } else {
        $(this).attr("disabled", "disabled");

        $.ajax({
            type: "POST",
            url: nv_module_url + "moveimg&num=" + nv_randomNum(10),
            data: "path=" + currentFolder + "&newpath=" + newPath + "&file=" + selFile + "&mirror=" + mirrorFile,
            success: function(e) {
                var e = e.split("#");

                if (e[0] == "ERROR") {
                    alert(e[1]);
                    $("input[name=newPathOK]").removeAttr("disabled");
                } else {
                    var imgtype = $("select[name=imgtype]").val();
                    var goNewPath = $("input[name=goNewPath]:checked").length;
                    var author = $("select[name=author]").val() == 1 ? "&author" : "";
                    var order = $("select[name=order]").val();
                    var imgfile = e[1];

                    $("input[name=newPathOK]").removeAttr("disabled");
                    $("input[name=selFile]").val(imgfile);
                    $("div#filemove").dialog("close");

                    if (goNewPath == 1) {
                        goNewPath = $("span#path").attr("title");
                        $("#imgfolder").load(nv_module_url + "folderlist&path=" + goNewPath + "&currentpath=" + newPath + "&random=" + nv_randomNum(10));
                        $("#imglist").load(nv_module_url + "imglist&path=" + newPath + "&type=" + imgtype + "&imgfile=" + imgfile + author + "&order=" + order + "&num=" + +nv_randomNum(10), function() {
                            LFILE.setViewMode();
                        });
                    } else {
                        $("#imglist").load(nv_module_url + "imglist&path=" + currentFolder + "&type=" + imgtype + "&imgfile=" + imgfile + author + "&order=" + order + "&num=" + +nv_randomNum(10), function() {
                            LFILE.setViewMode();
                        });
                    }
                }
            }
        });
    }
});

// Doi ten file (Luu lai)
$("input[name=filerenameOK]").click(function() {
    var b = $("input[name=selFile]").val(),
        d = $("input[name=filerenameNewName]").val(),
        e = b.match(/^(.+)\.([a-zA-Z0-9]+)$/);
    d = $.trim(d);
    $("input[name=filerenameNewName]").val(d);
    if (d == "") {
        alert(LANG.rename_noname);
        $("input[name=filerenameNewName]").focus();
    } else {
        a = $("input[name=filerenameAlt]").val();
        if (e[1] == d && a == $("img[title='" + b + "']").attr("alt")) {
            $("div#filerename").dialog("close");
        } else {
            n = $("img[title='" + b + "']").attr("name").split("|");
            p = (n[7] == "") ? $("span#foldervalue").attr("title") : n[7];

            $(this).attr("disabled", "disabled");
            $.ajax({
                type: "POST",
                url: nv_module_url + "renameimg&num=" + nv_randomNum(10),
                data: "path=" + p + "&file=" + b + "&newname=" + d + "&newalt=" + a,
                success: function(g) {
                    var h = g.split("_");
                    if (h[0] == "ERROR") {
                        alert(h[1]);
                        $("input[name=filerenameOK]").removeAttr("disabled");
                    } else {
                        h = $("select[name=imgtype]").val();
                        var j = $("select[name=author]").val() == 1 ? "&author" : "";
                        $("input[name=filerenameOK]").removeAttr("disabled");
                        $("div#filerename").dialog("close");
                        $("#imglist").load(nv_module_url + "imglist&path=" + p + "&type=" + h + "&imgfile=" + g + j + "&order=" + $("select[name=order]").val() + "&num=" + nv_randomNum(10), function() {
                            LFILE.setViewMode();
                        });
                    }
                }
            });
        }
    }
});

$("img[name=myFile2]").dblclick(function() {
    $("div[title=createInfo]").find("div").remove();
    var a = $("input[name=origWidth]").val(),
        b = $("input[name=origHeight]").val();
    c = calSize(a, b, 360, 230);
    $(this).width(c[0]).height(c[1]);
    $("input[name=newHeight]").val(b);
    $("input[name=newWidth]").val(a).select();
});


// Upload tu internet
function remoteUpload() {
    $("div.dynamic, span.dynamic").html("");
    $("input.dynamic").val("");

    $("div#uploadremote").dialog({
        autoOpen: false,
        width: 400,
        height: 320,
        modal: true,
        position: {
            my: "center",
            at: "center",
            of: window
        }
    }).dialog("open");

    if (nv_auto_alt) {
        $('#uploadremoteFile').keyup(function() {
            var imageUrl = $(this).val();
            fileAlt = nv_filename_alt(imageUrl);
            $('#uploadremoteFileAlt').val(fileAlt);
        });
    }

    var current_folder = $('span.folder[title="' + $("span#foldervalue").attr("title") + '"]');
    var auto_logo = current_folder.is('.auto_logo');
    var logo = $("input[name=upload_logo]").val();
    var panel = $('#uploadremote');
    if (logo == '') {
        $('[data-toggle="autoLogoArea"]', panel).addClass('hidden');
        $('[name="auto_logo"]', panel).prop('checked', false);
    } else {
        $('[data-toggle="autoLogoArea"]', panel).removeClass('hidden');
        if (auto_logo) {
            $('[name="auto_logo"]', panel).prop('checked', true);
        } else {
            $('[name="auto_logo"]', panel).prop('checked', false);
        }
    }

    return false;
}

// Upload tu internet (Submit)
$('[name="uploadremoteFileOK"]').click(function() {
    var fileUrl = $("input[name=uploadremoteFile]").val();
    var currUrl = $("input[name=currentFileUrl]").val();
    var folderPath = $("span#foldervalue").attr("title");
    var check = fileUrl + " " + folderPath;
    var fileAlt = $('#uploadremoteFileAlt').val();
    var panel = $('#uploadremote');
    var auto_logo = ($('[name="auto_logo"]', panel).is(':checked') ? 1 : 0);
    // var regex = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm;
    var regex = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\u00a1-\uffff]+-?)*[a-z\d\u00a1-\uffff]+)(?:\.(?:[a-z\d\u00a1-\uffff]+-?)*[a-z\d\u00a1-\uffff]+)*(?:\.[a-z\u00a1-\uffff]{2,6}))(?::\d+)?(?:\/[^\s]*)?$/gm;
    if (/^(https?|ftp):\/\//i.test(fileUrl) === false) {
        fileUrl = 'http://' + fileUrl;
    }
    $("input[name=uploadremoteFile]").val(fileUrl);

    if (
        regex.test(fileUrl) &&
        //currUrl != check && -- Cho phép upload lại file cũ, không có lý do gì phải cấm
        ((nv_alt_require && fileAlt != '') || !nv_alt_require)
    ) {
        $(this).attr('disabled', 'disabled');
        $('#upload-remote-info').html('<em class="fa fa-2x fa-spinner fa-spin"></em>');

        $.ajax({
            type: "POST",
            url: nv_module_url + "upload&random=" + nv_randomNum(10),
            data: "path=" + folderPath + "&fileurl=" + fileUrl + "&filealt=" + fileAlt + '&autologo=' + auto_logo,
            success: function(k) {
                $('[name="uploadremoteFileOK"]').removeAttr('disabled');

                var l = k.split("_");
                if (l[0] == "ERROR") {
                    $("div#errorInfo").html(l[1]).dialog("open");
                    $('#upload-remote-info').html('');
                } else {
                    $("input[name=currentFileUrl]").val(check);
                    $("input[name=selFile]").val(k);
                    $('#upload-remote-info').html('<em class="fa fa-2x fa-check text-success"></em>');
                    LFILE.reload(folderPath, k);
                    setTimeout("NVUPLOAD.closeRemoteDialog()", 500);
                }
            }
        });
    } else if (nv_alt_require && fileAlt == '' && fileUrl != '') {
        $("div#errorInfo").html(LANG.upload_alt_note).dialog("open");
    } else {
        alert(nv_url)
    }
});

/* List File Handle */
var LFILE = {
    reload: function(path, file) {
        var imgtype = $("select[name=imgtype]").val();
        var author = $("select[name=author]").val() == 1 ? "&author" : "";
        var order = $("select[name=order]").val();

        // Reset shift offset
        KEYPR.shiftOffset = 0;

        $("#imglist").html(nv_loading_data).load(nv_module_url + "imglist&path=" + path + "&type=" + imgtype + "&imgfile=" + file + author + "&order=" + order + "&num=" + nv_randomNum(10), function() {
            LFILE.setViewMode();
        });
    },
    setSelFile: function() {
        $("input[name=selFile]").val('');

        if ($('.imgsel').length) {
            fileName = new Array();
            $.each($('.imgsel'), function() {
                fileName.push($(this).attr("title"));
            });
            fileName = fileName.join('|');

            $("input[name=selFile]").val(fileName);
        }
    },
    setViewMode: function() {
        var numFiles = $('[data-img="false"]').length;
        var numImage = $('[data-img="true"]').length;
        var autoMode = $(".viewmode em").data('auto');

        if (autoMode) {
            if (numImage > numFiles) {
                $('#imglist').removeClass('view-detail');
            } else if (numFiles > 0) {
                $('#imglist').addClass('view-detail');
            }
        }

        LFILE.setViewIcon();
    },
    setViewIcon: function() {
        if ($('#imglist').is('.view-detail')) {
            $('.viewmode em').removeClass('fa-hourglass-o fa-spin fa-list').addClass('fa-file-image-o').attr('title', $('.viewmode em').data('langthumb'));
        } else {
            $('.viewmode em').removeClass('fa-hourglass-o fa-spin fa-file-image-o').addClass('fa-list').attr('title', $('.viewmode em').data('langdetail'));
        }
    }
}

/* Rorate Handle */
var RRT = {
    direction: 0,
    currentDirection: 0,
    arrayDirection: [0, 90, 180, 270],
    timer: null,
    timeOut: 20,
    trigger: function() {
        $('#rorateContent img').rotate(RRT.direction);
    },
    setVal: function() {
        $('[name="rorateDirection"]').val(RRT.direction);
    },
    setDirection: function(direction) {
        if (direction == '') {
            RRT.direction = 0;
        } else {
            direction = parseInt(direction);

            if (direction >= 360) {
                direction = 359;
            } else if (direction < 0) {
                direction = 0;
            }

            RRT.direction = direction;
        }
    },
    increase: function() {
        var direction = RRT.direction;
        direction++;

        if (direction == 360) {
            direction = 0;
        }

        RRT.setDirection(direction);
        RRT.setVal();
        RRT.trigger();
    },
    decrease: function() {
        var direction = RRT.direction;
        direction--;

        if (direction == -1) {
            direction = 359;
        }

        RRT.setDirection(direction);
        RRT.setVal();
        RRT.trigger();
    },
    init: function() {
        $('[name="rorateDirection"]').keyup(function() {
            var direction = $(this).val();

            if (isNaN(direction)) {
                direction = direction.slice(0, direction.length - 1);
            }

            RRT.setDirection(direction);
            RRT.setVal();
            RRT.trigger();
        });

        $('#rorateLeft').mousedown(function() {
            RRT.timer = setInterval("RRT.decrease()", RRT.timeOut);
        });

        $('#rorateLeft').bind("mouseup mouseleave", function() {
            clearInterval(RRT.timer);
        });

        $('#rorateRight').mousedown(function() {
            RRT.timer = setInterval("RRT.increase()", RRT.timeOut);
        });

        $('#rorateRight').bind("mouseup mouseleave", function() {
            clearInterval(RRT.timer);
        });

        $('#rorate90Anticlockwise').click(function() {
            RRT.currentDirection--;

            if (RRT.currentDirection < 0) {
                RRT.currentDirection = 3;
            }

            RRT.setDirection(RRT.arrayDirection[RRT.currentDirection]);
            RRT.setVal();
            RRT.trigger();
        });

        $('#rorate90Clockwise').click(function() {
            RRT.currentDirection++;

            if (RRT.currentDirection > 3) {
                RRT.currentDirection = 0;
            }

            RRT.setDirection(RRT.arrayDirection[RRT.currentDirection]);
            RRT.setVal();
            RRT.trigger();
        });

        $('#rorateimageOK').click(function() {
            var roratePath = $('[name="roratePath"]').val();
            var rorateFile = $('[name="rorateFile"]').val();
            var rorateDirection = $('[name="rorateDirection"]').val();

            $(this).attr("disabled", "disabled");

            $.ajax({
                type: "POST",
                url: nv_module_url + "rotateimg&num=" + nv_randomNum(10),
                data: "path=" + roratePath + "&file=" + rorateFile + "&direction=" + rorateDirection,
                success: function(g) {
                    $('#rorateimageOK').removeAttr("disabled");
                    var h = g.split("#");

                    if (h[0] == "ERROR") {
                        alert(h[1]);
                    } else {
                        $("div#rorateimage").dialog("close");
                        LFILE.reload(roratePath, rorateFile);
                    }
                }
            });
        });
    }
};

/* Keypress, Click Handle */
var KEYPR = {
    isCtrl: false,
    isShift: false,
    shiftOffset: 0,
    allowKey: [112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123],
    isSelectable: false,
    isFileSelectable: false,
    init: function() {
        $('body').keyup(function(e) {
            if (!$(e.target).is('.dynamic') && $.inArray(e.keyCode, KEYPR.allowKey) == -1) {
                e.preventDefault();
            } else {
                return;
            }

            // Ctrl key unpress
            if (e.keyCode == 17) {
                KEYPR.isCtrl = false;
            } else if (e.keyCode == 16) {
                KEYPR.isShift = false;
            }
        });

        $('body').keydown(function(e) {
            if (!$(e.target).is('.dynamic') && $.inArray(e.keyCode, KEYPR.allowKey) == -1) {
                e.preventDefault();
            } else {
                return;
            }

            // Ctrl key press
            if (e.keyCode == 17 /* Ctrl */ ) {
                KEYPR.isCtrl = true;
            } else if (e.keyCode == 27 /* ESC */ ) {
                // Unselect all file
                $(".imgsel").removeClass("imgsel");
                LFILE.setSelFile();

                // Hide contextmenu
                NVCMENU.hide();

                // Reset shift offset
                KEYPR.shiftOffset = 0;
            } else if (e.keyCode == 65 /* A */ && e.ctrlKey === true) {
                // Select all file
                $(".imgcontent").addClass("imgsel");
                LFILE.setSelFile();

                // Hide contextmenu
                NVCMENU.hide();
            } else if (e.keyCode == 16 /* Shift */ ) {
                KEYPR.isShift = true;
            } else if (e.keyCode == 46 /* Del */ ) {
                // Delete file
                if ($('.imgsel').length && $("span#delete_file").attr("title") == '1') {
                    filedelete();
                }
            } else if (e.keyCode == 88 /* X */ ) {
                // Move file
                if ($('.imgsel').length && $("span#move_file").attr("title") == '1') {
                    move();
                }
            }
        });

        // Unselect file when click on wrap area
        $('#imglist').click(function(e) {
            if (KEYPR.isSelectable == false) {
                if ($(e.target).is('#imglist')) {
                    $(".imgsel").removeClass("imgsel");
                }
            }

            KEYPR.isSelectable = false;
        });
    }
};

var NVUPLOAD = {
    uploader: null, // Pupload variable
    rendered: false, // Is rendered upload container
    started: false,
    buttons: '<div class="row">' +
        '<div class="col-sm-14 buttons">' +
        '<div class="btn-group dropup browse-button open perload" id="upload-dropdown-btn">' +
        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">' +
        LANG.upload_mode + ' <span class="caret"></span>' +
        '</button>' +
        '<ul class="dropdown-menu" role="menu">' +
        '<li><a id="upload-remote" onclick="return remoteUpload();" href="#">' + LANG.upload_mode_remote + '</a></li>' +
        '<li><a id="upload-local" href="#">' + LANG.upload_mode_local + '</a></li>' +
        '</ul>' +
        '</div>    ' +
        '</div>' +
        '<div class="col-sm-10">' +
        '<div class="row" id="upload-queue-total">' +
        '<div class="col-sm-8 total-size"></div>' +
        '<div class="col-sm-16 total-status"></div>' +
        '</div>' +
        '</div>' +
        '</div>',
    closeRemoteDialog: function() {
        $("div#uploadremote").dialog('close');
    },
    init: function() {
        // Reset upload if exists
        if (NVUPLOAD.uploader != null) {
            NVUPLOAD.reset();
        }

        // Check folder if allow upload
        var isUploadAllow = $("span#upload_file").attr("title") == "1" ? true : false;

        if (!isUploadAllow) {
            $('#upload-button-area').html('<span class="text-danger"><em class="fa fa-info">&nbsp;</em>' + LANG.notupload + '</span>');
        } else {
            NVUPLOAD.buildBtns();

            var folderPath = $("span#foldervalue").attr("title");

            NVUPLOAD.uploader = new plupload.Uploader({
                runtimes: 'html5,flash,silverlight,html4',
                browse_button: 'upload-local',
                url: nv_module_url + "upload&path=" + folderPath + "&random=" + nv_randomNum(10),
                flash_swf_url: nv_base_siteurl + 'assets/js/plupload/Moxie.swf',
                silverlight_xap_url: nv_base_siteurl + 'assets/js/plupload/Moxie.xap',
                drop_element: 'upload-content',
                file_data_name: 'upload',
                multipart: true,
                multipart_params: {
                    "filealt": "--"
                },
                filters: {
                    max_file_size: nv_max_size_bytes,
                    mime_types: []
                },
                chunk_size: nv_chunk_size,
                resize: false,
                init: {
                    // Event on init uploader
                    PostInit: function() {
                        $('#upload-dropdown-btn').removeClass('open');
                        $('#upload-dropdown-btn').removeClass('perload');
                        (isDebugMode && console.log("Plupload: Event init"));
                    },

                    // Event on add file (Add to queue or first add)
                    FilesAdded: function(up, files) {
                        (isDebugMode && console.log("Plupload: Event fileadded"));
                        // Build upload container
                        if (!NVUPLOAD.rendered) {
                            NVUPLOAD.renderUI();
                        }

                        NVUPLOAD.updateList();

                        // Xác định resize ảnh (bug plupload 2.3.1) => Tạm thời để lại code phòng khi lỗi, vài phiên bản nũa nếu không lỗi sẽ xóa code này
                        /*
                        if (nv_resize != false) {
                            var lastKey = NVUPLOAD.uploader.files.length - 1;
                            $.each(NVUPLOAD.uploader.files, function(k, file) {
                                $('#upload-start').prop('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin"></i> ' + LANG.upload_file);
                                file.clientResize = false;
                                var img = new moxie.image.Image();
                                try {
                                    img.onload = function() {
                                        if (this.width > nv_resize.width || this.height > nv_resize.height) {
                                            file.clientResize = true;
                                        }
                                        if (k == lastKey) {
                                            setTimeout(function() {
                                                $('#upload-start').prop('disabled', false).html(LANG.upload_file);
                                            }, 1500);
                                        }
                                    };
                                    img.onerror = function() {
                                        if (k == lastKey) {
                                            setTimeout(function() {
                                                $('#upload-start').prop('disabled', false).html(LANG.upload_file);
                                            }, 1500);
                                        }
                                    }
                                    img.load(file.getSource());
                                } catch(ex) {
                                    // Nothing
                                }
                                NVUPLOAD.uploader.files[k] = file;
                            });
                        }
                        */

                        if (!$('#upload-start').data('binded')) {
                            $('#upload-start').click(function() {
                                // Check file before start upload
                                var allow_start = true;
                                if (nv_alt_require) {
                                    $.each($('#upload-queue-files .file-alt input'), function() {
                                        if ($(this).val() == '') {
                                            allow_start = false;
                                            return false;
                                        }
                                    });

                                    if (allow_start == false) {
                                        $("div#errorInfo").html(LANG.upload_alt_note).dialog("open");
                                    }
                                }

                                if (allow_start) {
                                    NVUPLOAD.uploader.start();
                                }
                            });
                            $('#upload-start').data('binded', true);
                        }

                        if (!$('#upload-cancel').data('binded')) {
                            $('#upload-cancel').click(function() {
                                NVUPLOAD.uploadCancel();
                            });
                            $('#upload-cancel').data('binded', true);
                        }
                    },

                    // Event on trigger a file upload status
                    UploadProgress: function(up, file) {
                        (isDebugMode && console.log("Plupload: Event Upload Progress"));
                        $('#' + file.id + ' .file-status').html(file.percent + '%');
                        NVUPLOAD.handleStatus(file, false);
                        NVUPLOAD.updateTotalProgress();
                    },

                    // Event on one file finish uploaded (Maybe success or error)
                    FileUploaded: function(up, file, response) {
                        (isDebugMode && console.log("Plupload: Event file uploaded"));
                        (isDebugMode && console.log(response));
                        response = response.response;
                        NVUPLOAD.handleStatus(file, response);
                    },

                    // Event on start upload or finish upload
                    StateChanged: function() {
                        (isDebugMode && console.log("Plupload: Event state changed " + NVUPLOAD.uploader.state));

                        // Start upload
                        if (NVUPLOAD.uploader.state === plupload.STARTED) {
                            if (!NVUPLOAD.started) {
                                NVUPLOAD.started = true;
                                // Hide control button
                                $('#upload-start, #upload-cancel, #upload-button-area .browse-button').hide();

                                // Add some button
                                if (parseFloat(nv_chunk_size) <= 0) {
                                    $('#upload-button-area .buttons').append(
                                        '<input id="upload-stop" type="button" class="btn btn-primary" value="' + LANG.upload_stop + '"/> ' +
                                        '<input style="display:none" id="upload-continue" type="button" class="btn btn-primary" value="' + LANG.upload_continue + '"/>'
                                    );
                                }

                                $('#upload-button-area .buttons').append('<div class="total-info pull-right"></div>');
                                $('#upload-button-area .total-info').html(
                                    plupload.sprintf(LANG.upload_info, NVUPLOAD.uploader.total.uploaded, NVUPLOAD.uploader.files.length, plupload.formatSize(NVUPLOAD.uploader.total.bytesPerSec))
                                );

                                // Init upload progress bar
                                $('#upload-queue-total .total-status').html(
                                    '<div class="progress">' +
                                    '<div class="progress-bar" role="progressbar" aria-valuenow="' + NVUPLOAD.uploader.total.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + NVUPLOAD.uploader.total.percent + '%;">' + NVUPLOAD.uploader.total.percent + '%</div>' +
                                    '</div>'
                                );

                                // Set button handle
                                $('#upload-stop').click(function() {
                                    $(this).hide();
                                    $('#upload-continue').show();
                                    NVUPLOAD.uploader.stop();
                                });

                                $('#upload-continue').click(function() {
                                    $(this).hide();
                                    $('#upload-stop').show();
                                    NVUPLOAD.uploader.start();
                                });
                            }
                        } else if (NVUPLOAD.uploader.state != 8) {
                            // 8 is Queueable.DESTROYED state
                            NVUPLOAD.updateList();
                        }
                    },

                    // Event on a file is uploading
                    UploadFile: function(up, file) {
                        // Not thing to do
                    },

                    // Event on remove a file
                    FilesRemoved: function() {
                        (isDebugMode && console.log("Plupload: Event file removed"));
                        var scrollTop = $('#upload-queue-files').scrollTop();
                        NVUPLOAD.updateList();
                        $('#upload-queue-files').scrollTop(scrollTop);
                    },

                    // Event on all files are uploaded
                    UploadComplete: function(up, files) {
                        (isDebugMode && console.log("Plupload: Event upload completed"));
                        $('#upload-continue').hide();
                        $('#upload-stop').hide();

                        // Show finish button if has failed file
                        if (NVUPLOAD.uploader.total.failed > 0) {
                            $('<input type="button" class="btn btn-primary" value="' + LANG.upload_finish + '" id="upload-finish"/>').insertBefore($('#upload-stop'));

                            $('#upload-finish').click(function() {
                                NVUPLOAD.finish();
                            });
                        } else {
                            $('<i class="fa fa-2x text-success fa-spin fa-spinner"></i>').insertBefore($('#upload-stop'));
                            setTimeout("NVUPLOAD.finish()", 1000);
                        }
                    },

                    // Event on error
                    Error: function(up, err) {
                        (isDebugMode && console.log("Plupload: Event error"));
                        $("div#errorInfo").html("Error #" + err.message + ": <br>" + err.file.name).dialog("open");

                        if (err.code === plupload.INIT_ERROR) {
                            setTimeout("NVUPLOAD.destroyUpload()", 1000);
                        }
                    },

                    // Get image alt before upload
                    BeforeUpload: function(up, file) {
                        (isDebugMode && console.log("Plupload: Event before upload"));
                        var filealt = '';
                        var autologo = ($('[name="auto_logo"]', $('#upload-queue')).is(':checked') ? 1 : 0);

                        if ($('#' + file.id + ' .file-alt').length) {
                            filealt = $('#' + file.id + ' .file-alt input').val();
                        }

                        NVUPLOAD.uploader.settings.multipart_params = {
                            "filealt": filealt,
                            "autologo": autologo
                        };

                        // Xác định resize ảnh (bug plupload 2.3.1) => Tạm thời để lại code phòng khi lỗi, vài phiên bản nũa nếu không lỗi sẽ xóa code này
                        /*
                        if (nv_resize != false) {
                            if (typeof file.clientResize != "undefined" && file.clientResize) {
                                NVUPLOAD.uploader.settings.resize = nv_resize;
                            } else {
                                NVUPLOAD.uploader.settings.resize = {};
                            }
                        }
                        */
                    },

                    // Upload xong một BLOB
                    ChunkUploaded: function(up, file, res) {
                        (isDebugMode && console.log("Plupload: Event chunk uploaded"));
                        /**
                         * Hiện tại Plupload không có chức năng dừng upload chunk và chuyển sang file khác
                         * Do đó tạm thời khi lỗi một BLOG phải chờ upload xong cả file để kiểm tra lỗi
                         */
                        if (res.response != null && res.response != '') {
                            //NVUPLOAD.handleStatus(file, res.response);
                        }
                    }
                }
            });

            NVUPLOAD.uploader.init();
        }
    },
    renderUI: function() {
        var current_folder = $('span.folder[title="' + $("span#foldervalue").attr("title") + '"]');
        var auto_logo = current_folder.is('.auto_logo');
        var logo = $("input[name=upload_logo]").val();

        // Hide files list and show upload container
        $('#imglist').css({
            'display': 'none'
        });
        $('#upload-queue').css({
            'display': 'block'
        });

        if (logo == '') {
            $('#upload-queue').removeClass('auto-logo');
        } else {
            $('#upload-queue').addClass('auto-logo');
        }

        // Add some button
        $('#upload-button-area .buttons').append(
            '<button id="upload-start" type="button" class="btn btn-primary">' + LANG.upload_file + '</button> ' +
            '<input id="upload-cancel" type="button" class="btn btn-default" value="' + LANG.upload_cancel + '"/> '
        );

        // Change browse_button (Change style, Method: setOption is error)
        $('#upload-button-area .browse-button button').remove();
        $('#upload-remote').parent().remove();
        $('#upload-button-area .browse-button ul').removeAttr('role').removeClass('dropdown-menu').addClass('fixul');
        $('#upload-local').addClass('btn btn-primary').text(LANG.upload_add_files);
        $('#upload-button-area .browse-button ul li div:first').width($('#upload-local').outerWidth()).height($('#upload-local').outerHeight());

        // Build upload queue
        $('#upload-queue').html('<div class="queue-header">' +
            '<div class="container-fluid">' +
            '<div class="row">' +
            '<div class="col-sm-' + (nv_alt_require ? '8' : '14') + '">' + LANG.file_name + '</div>' +
            (nv_alt_require ? '<div class="col-sm-6">' + LANG.altimage + '</div>' : '') +
            '<div class="col-sm-4">' + LANG.upload_size + '</div>' +
            '<div class="col-sm-6">' + LANG.upload_status + '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div id="upload-queue-files" class="container-fluid"></div>\
            <div class="queue-opts">\
            <div class="checkbox">\
            <label><input type="checkbox" name="auto_logo" value="1"' + ((logo != '' && auto_logo) ? ' checked="checked"' : '') + '> ' + LANG.autologo_for_upload + '</label>\
            </div>\
            </div>\
        ');

        // Rendered is true
        NVUPLOAD.rendered = true;
    },
    updateList: function() {
        var fileList = $('#upload-queue-files').html('');
        var fileAlt;

        $.each(NVUPLOAD.uploader.files, function(i, file) {
            if (!nv_alt_require) {
                fileList.append(
                    '<div id="' + file.id + '" class="row file-item">' +
                    '<div class="col-sm-14 file-name"><span>' + file.name + '</span></div>' +
                    '<div class="col-sm-4 file-size">' + plupload.formatSize(file.size) + '</div>' +
                    '<div class="col-sm-4 file-status">' + file.percent + '%</div>' +
                    '<div class="col-sm-2 file-action text-right"></div>' +
                    '</div>'
                );
            } else {
                fileAlt = NVLDATA.getValue(file.id);

                if (nv_auto_alt && fileAlt == '') {

                    fileAlt = nv_filename_alt(file.name);

                    NVLDATA.setValue(file.id, fileAlt);
                }

                fileList.append(
                    '<div id="' + file.id + '" class="row file-item">' +
                    '<div class="col-sm-8 file-name"><span>' + file.name + '</span></div>' +
                    '<div class="col-sm-6 file-alt"><input type="text" value="' + fileAlt + '" onkeyup="NVLDATA.setValue( \'' + file.id + '\', this.value);" class="form-control upload-file-alt dynamic"/></div>' +
                    '<div class="col-sm-4 file-size">' + plupload.formatSize(file.size) + '</div>' +
                    '<div class="col-sm-4 file-status">' + file.percent + '%</div>' +
                    '<div class="col-sm-2 file-action text-right"></div>' +
                    '</div>'
                );
            }

            NVUPLOAD.handleStatus(file, false);

            $('#' + file.id + ' .file-delete').click(function(e) {
                $('#' + file.id).remove();
                NVUPLOAD.uploader.removeFile(file);

                e.preventDefault();
            });
        });

        $('#upload-queue-total .total-size').html(plupload.formatSize(NVUPLOAD.uploader.total.size));

        // Scroll to end of file list
        fileList[0].scrollTop = fileList[0].scrollHeight;

        NVUPLOAD.updateTotalProgress();

        // Enable, disable start button
        if (NVUPLOAD.uploader.files.length) {
            $('#upload-start').removeAttr('disabled');
        } else {
            $('#upload-start').attr('disabled', 'disabled');
        }
    },
    reset: function() {
        // Destroy current uploader
        NVUPLOAD.uploader.destroy();
        NVUPLOAD.started = false;

        // Clear uploader variable
        NVUPLOAD.uploader = null;

        // Reset upload button
        NVUPLOAD.buildBtns();

        // Clear upload container
        $('#upload-queue-files').html('');
    },
    uploadCancel: function() {
        // Reset uploader
        NVUPLOAD.reset();

        // Hide upload container and show file list
        $('#upload-queue').html('').css({
            'display': 'none'
        });
        $('#imglist').css({
            'display': 'block'
        });

        // Rendered is false
        NVUPLOAD.rendered = false;

        // Init uploader
        setTimeout(function() {
            NVUPLOAD.init();
        }, 50);
    },
    destroyUpload: function() {
        // Reset uploader
        NVUPLOAD.reset();

        // Hide upload container and show file list
        $('#upload-queue').html('').css({
            'display': 'none'
        });
        $('#imglist').css({
            'display': 'block'
        });

        // Rendered is false
        NVUPLOAD.rendered = false;
    },
    handleStatus: function(file, response) {
        var actionClass;

        if (response != false) {
            check = response.split('_');

            if (check[0] == 'ERROR') {
                file.status = plupload.FAILED;
                file.hint = check[1];
                NVUPLOAD.uploader.total.uploaded--;
                NVUPLOAD.uploader.total.failed++;
            } else {
                file.name = response;
            }

            $.each(NVUPLOAD.uploader.files, function(i, f) {
                if (f.id == file.id) {
                    NVUPLOAD.uploader.files[i].status = file.status;
                    NVUPLOAD.uploader.files[i].hint = file.hint;
                    NVUPLOAD.uploader.files[i].name = file.name;
                }
            });
        }

        if (file.status == plupload.DONE) {
            actionClass = 'text-success fa fa-lg fa-check';
        } else if (file.status == plupload.FAILED) {
            actionClass = 'text-danger fa fa-lg fa-exclamation-triangle';
        } else if (file.status == plupload.QUEUED) {
            actionClass = 'fa fa-lg fa-trash-o file-delete fa-pointer';
        } else if (file.status == plupload.UPLOADING) {
            actionClass = 'text-info fa fa-lg fa-spin fa-circle-o-notch';
        } else {
            // Nothing to do
        }

        var actionHTML = '<i class="' + actionClass + '"></i>';
        if ($('#' + file.id + ' .file-action').html() != actionHTML) {
            $('#' + file.id + ' .file-action').html(actionHTML);
        }

        if (file.hint) {
            $('#' + file.id).attr('title', file.hint);
        }
    },
    updateTotalProgress: function() {
        $('#upload-queue-total .total-status').html(
            '<div class="progress">' +
            '<div class="progress-bar" role="progressbar" aria-valuenow="' + NVUPLOAD.uploader.total.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + NVUPLOAD.uploader.total.percent + '%;">' + NVUPLOAD.uploader.total.percent + '%</div>' +
            '</div>'
        );

        $('#upload-button-area .total-info').html(
            plupload.sprintf(LANG.upload_info, NVUPLOAD.uploader.total.uploaded, NVUPLOAD.uploader.files.length, plupload.formatSize(NVUPLOAD.uploader.total.bytesPerSec))
        );
    },
    finish: function() {
        var folderPath = $("span#foldervalue").attr("title");

        if (NVUPLOAD.uploader.total.uploaded > 0) {
            var selFile = new Array();

            $.each(NVUPLOAD.uploader.files, function(k, v) {
                if (v.status == plupload.DONE) {
                    selFile.push(v.name);
                }
            });

            selFile = selFile.join('|');
        } else {
            var selFile = '';
        }

        $("input[name=selFile]").val(selFile);
        NVUPLOAD.uploadCancel();
        LFILE.reload(folderPath, selFile);
    },
    buildBtns: function() {
        var btnsArea = $('#upload-button-area');
        btnsArea.html(NVUPLOAD.buttons);
        $('#upload-remote').attr('title', btnsArea.data('title') + ' ' + btnsArea.data('remotesize'));
        $('#upload-local').attr('title', btnsArea.data('title') + ' ' + btnsArea.data('localsize'));
        $('#upload-dropdown-btn').addClass('perload');
        setTimeout(function() {
            $('#upload-dropdown-btn').addClass('open');
        }, 10);
    }
};

var NVCMENU = {
    menuStyle: {
        listStyle: 'none',
        padding: '1px',
        margin: '0px',
        backgroundColor: '#fff',
        border: '1px solid #999',
        width: '150px'
    },
    itemStyle: {
        margin: '0px',
        color: '#000',
        display: 'block',
        cursor: 'default',
        padding: '3px',
        border: '1px solid #fff',
        backgroundColor: 'transparent'
    },
    itemHoverStyle: {
        border: '1px solid #0a246a',
        backgroundColor: '#b6bdd2'
    },
    shadow: null,
    menu: null,
    bindings: {
        select: function() {
            insertvaluetofield();
        },
        download: function() {
            download();
        },
        filepreview: function() {
            preview();
        },
        fileaddlogo: function() {
            addlogo();
        },
        create: function() {
            create();
        },
        move: function() {
            move();
        },
        rename: function() {
            filerename();
        },
        filedelete: function() {
            filedelete();
        },
        cropfile: function() {
            cropfile();
        },
        rotatefile: function() {
            rotatefile();
        },
        renamefolder: function() {
            renamefolder()
        },
        createfolder: function() {
            createfolder()
        },
        recreatethumb: function() {
            recreatethumb()
        },
        deletefolder: function() {
            deletefolder()
        }
    },
    init: function() {
        NVCMENU.menu = $('<div id="nvContextMenu"></div>').hide().css({
            position: 'absolute',
            zIndex: '500'
        }).appendTo('body').bind('click', function(e) {
            e.stopPropagation();
        });
        NVCMENU.shadow = $('<div id="nvContextMenuShadow"></div>').hide().css({
            backgroundColor: '#000',
            position: 'absolute',
            opacity: 0.2,
            zIndex: 499
        }).appendTo('body');

        $(document).delegate('*', 'click', function(e) {
            if (e.which != 3) {
                NVCMENU.hide();
            }
        });
    },
    show: function(e) {
        e.preventDefault();

        if ($('#contextMenu').html() != '') {
            var content = $('#contextMenu').find('ul:first').clone(true);
            content.css(NVCMENU.menuStyle).find('li').css(NVCMENU.itemStyle).hover(function() {
                    $(this).css(NVCMENU.itemHoverStyle);
                },
                function() {
                    $(this).css(NVCMENU.itemStyle);
                }
            ).find('img').css({
                verticalAlign: 'middle',
                paddingRight: '2px'
            });

            NVCMENU.menu.html(content);

            $.each(NVCMENU.bindings, function(id, func) {
                $('#' + id, NVCMENU.menu).bind('click', function(e) {
                    NVCMENU.hide();
                    func();
                });
            });

            NVCMENU.menu.css({
                'left': e.pageX + 1,
                'top': e.pageY + 1
            }).show();
            NVCMENU.shadow.css({
                'width': NVCMENU.menu.width(),
                'height': NVCMENU.menu.height(),
                'left': e.pageX + 3,
                'top': e.pageY + 3
            }).show();
        }
        return false;
    },
    hide: function() {
        NVCMENU.menu.hide();
        NVCMENU.shadow.hide();
    }
};

var NVLDATA = {
    support: false,
    init: function() {
        if (typeof(Storage) !== "undefined") {
            NVLDATA.support = true;
        }
    },
    getValue: function(key) {
        if (!NVLDATA.support) {
            return '';
        }

        if (typeof(sessionStorage[key]) !== "undefined" && sessionStorage[key]) {
            return sessionStorage[key];
        }

        return '';
    },
    setValue: function(key, val) {
        sessionStorage[key] = val;
    }
};

// Init functions
KEYPR.init();
RRT.init();
NVCMENU.init();
NVLDATA.init();
