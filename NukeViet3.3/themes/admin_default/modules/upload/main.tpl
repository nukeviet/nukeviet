<!-- BEGIN: uploadPage -->
<iframe src="{IFRAME_SRC}" width="100%" height="400px" frameborder="0"></iframe>
<!-- END: uploadPage -->
<!-- BEGIN: main -->
<!-- BEGIN: header -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<title>Management Upload File</title>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.upload.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.lazyload.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
</head>
<style type="text/css">
body{background:#EAEAEA;font:12px Arial;height:100%;margin:0;padding:0;position:fixed;width:100%;}
</style>
<body>
<!-- END: header -->
<style type="text/css">
input[type="file"]{cursor:pointer;filter:alpha(opacity=0);font-size:30px;height:33px;left:-320px;opacity:0;position:relative;z-index:1;}
td{border:1px solid #CCC;}
.authorFile{float:left;margin-left:5px;position:relative;}
.content{background:#FFF;font:12px Arial;}
.filebrowse{background:#F5F5F5;height:360px;overflow:auto;width:100%;}
.filetype{float:left;position:relative;}
.footer{font:12px Arial;padding:5px;}
.imgcontent{background:#fff;border:2px solid #EAEAEA;color:#666;cursor:pointer;float:left;height:124px;line-height:16px;margin-bottom:2px;margin-right:2px;padding:5px;text-align:center;width:100px;}
.imgfolder{background:#FFF;cursor:pointer;height:360px;margin:1px;overflow:auto;width:200px;}
.imgInfo{font:400 10px/12px tahoma,arial,sans-serif;height:30px;}
.imgsel{background:#fd8;border:2px solid #fb0;color:#333}
.notupload{color:#F00;float:right;font-size:10px;font-weight:bold;padding:5px;}
.refresh{float:left;margin-left:15px;margin-top:3px;position:relative;}
.refresh img{border:none;}
.upload{background:url('{NV_BASE_SITEURL}images/upload.gif') no-repeat 0px 0px;cursor:pointer;float:left;height:26px;overflow:hidden;position:relative;width:77px;}
.uploadForm{float:right;position:relative;}
img.previewimg{background:#FFF;border:2px solid #F0F0F0;max-height:80px;max-width:80px;padding:2px;}
#errorInfo{color:#F00;font-size:12px;padding:15px;}
#imglist{margin:5px;}
.imgcontent:hover{background:#F2F9FC;border:2px solid #DDEFF8;color:#333;}
.upload:hover{background:url('{NV_BASE_SITEURL}images/upload.gif') no-repeat 0px -26px;}
.red{color:red}
</style>

<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.dialog.css" rel="stylesheet" />

<link type="text/css" href="{NV_BASE_SITEURL}js/jquery/jquery.treeview.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.treeview.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/contextmenu/jquery.contextmenu.r2.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.flash.js"></script>
<div class="content">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
                <tr>
                    <td valign="top" width="200">
                    	<div id="imgfolder" class="imgfolder"></div>
                    </td>
                    <td valign="top">
                        <div class="filebrowse">
                            <div id="imglist">
                            	<p style="padding:20px; text-align:center"><img src="{NV_BASE_SITEURL}images/load_bar.gif"/> please wait...</p>
                            </div>
                        </div>
                    </td>
                </tr>
        </tbody>
    </table>
</div>
<div class="footer">
    <div class="filetype">
        {LANG.selectfiletype}: 
        <select name="imgtype" id="imgtype">
            <option value="file"{SFILE}>{LANG.type_file}</option>
            <option value="image"{SIMAGE}>{LANG.type_image}</option>
            <option value="flash"{SFLASH}>{LANG.type_flash}</option>
        </select>
    </div>
    <div class="authorFile">
        <select name="author" id="author">
            <option value="0">{LANG.author0}</option>
            <option value="1">{LANG.author1}</option>
        </select>
    </div>
    <div class="refresh">
        <a href="#"><img alt="{LANG.refresh}" title="{LANG.refresh}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16"/></a>
    </div>
    <div class="uploadForm" style="display:none">
        <div style="margin-top:5px;margin-right:5px;float:left;" id="cfile">&nbsp;&nbsp;</div>
        <div class="upload"><input type="file" name="fileupload" id="myfile"/></div>
        <div style="margin-top:10px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/load_bar.gif"/></div>
        <div style="margin-top:5px;margin-left:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/ok.png"/></div>
        <div style="margin-top:7px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/error.png"/></div>
        <div style="float:left;margin-left:5px;">{LANG.upload_otherurl}: <input type="text" name="imgurl"/></div>
        <div style="margin-top:10px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/load_bar.gif"/></div>
        <div style="margin-top:5px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/ok.png"/></div>
        <div style="margin-top:7px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/error.png"/></div>
        <div style="float:left;"><input type="button" value="Upload" id="confirm" /></div>
    </div>
    <div class="notupload" style="display:none">{LANG.notupload}</div>
    <div style="clear:both"></div>
</div>

<input type="hidden" name="currentFileUpload" value=""/>
<input type="hidden" name="currentFileUrl" value=""/>
<input type="hidden" name="selFile" value=""/>
<input type="hidden" name="CKEditorFuncNum" value="{FUNNUM}"/>
<input type="hidden" name="area" value="{AREA}"/>
<div style="display:none" id="contextMenu"></div>
<div style="display:none"><iframe id="Fdownload" src="" width="0" height="0" frameborder="0"></iframe></div>

<div id="renamefolder" style="display:none" title="{LANG.renamefolder}">
    {LANG.rename_newname}<input type="text" name="foldername"/>
</div>

<div id="createfolder" style="display:none" title="{LANG.createfolder}">
    {LANG.foldername}<input type="text" name="createfoldername"/>
</div>

<div id="errorInfo" style="display:none" title="{LANG.errorInfo}"></div>
<div id="imgpreview" style="overflow:auto;display:none" title="{LANG.preview}">
    <div style="text-align:center;margin-top:10px" id="fileView" class="dynamic"></div>
    <div style="text-align:center;font-size:12px;font-weight:800;margin-top:10px" id="fileInfoName" class="dynamic"></div>
    <div style="text-align:center;font-size:11px;margin-top:10px;margin-bottom:10px" id="fileInfoDetail" class="dynamic"></div>
</div>
<div id="imgcreate" style="overflow:auto;display:none;padding:10px;font-size:11px;" title="{LANG.upload_createimage}">
    <div style="float:left;width:260px;">
        <div style="padding:5px;background:#EAEAEA;font-weight:800;">{LANG.newSize}</div>
        <div style="padding:5px;">
            <input type="hidden" name="origWidth" value="" class="dynamic" />
            <input type="hidden" name="origHeight" value="" class="dynamic" />
            X: <input style="width:35px;margin-right:5px" type="text" name="newWidth" maxlength="4" class="dynamic" />
            Y: <input style="width:35px;margin-right:5px" type="text" name="newHeight" maxlength="4" class="dynamic" />
            <input type="button" value="{LANG.prView}" name="prView" />
            <input type="button" value="OK" name="newSizeOK" />
        </div>
        <div style="font-size:10px;" title="createInfo" class="dynamic"></div>
    </div>
    <div style="float:right;width:360px;">
        <div style="text-align:center;font-size:12px;font-weight:800;" id="fileInfoName2" class="dynamic"></div>
        <div style="text-align:center;" id="fileInfoDetail2" class="dynamic"></div>
        <div style="width:360px;height:230px;text-align:center;margin-top:10px">
            <img name="myFile2" title="{LANG.clickSize}" alt="{LANG.clickSize}" style="border:2px solid #F0F0F0;" width="0" height="0" src="" />
        </div>
    </div>
    <div style="clear:both"></div>
</div>
<div id="filemove" style="display:none;padding:10px;font-size:11px;" title="{LANG.move}">
    <div title="pathFileName" style="font-weight:800;margin-bottom:10px" class="dynamic"></div>
    {LANG.movefolder}:
    <div style="margin-top:10px;margin-bottom:10px"><select name="newPath"></select></div>
    <div><input style="vertical-align:middle" name="mirrorFile" type="checkbox" class="dynamic" /> {LANG.mirrorFile}</div>
    <div style="margin-bottom:10px"><input style="vertical-align:middle" name="goNewPath" type="checkbox" class="dynamic" /> {LANG.goNewPath}</div>
    <input style="width:60px;" type="button" value="OK" name="newPathOK" />
</div>
<div id="filerename" style="display:none;padding:10px;font-size:11px;text-align:center;" title="{LANG.rename}">
    <div id="filerenameOrigName" style="font-weight:800;margin-bottom:10px" class="dynamic"></div>
    <div style="margin-top:10px;margin-bottom:10px">
        {LANG.rename_newname}:
        <input style="width:150px;margin-left:5px" type="text" name="filerenameNewName" maxlength="255" class="dynamic" />
        <span title="Ext">Ext</span>
    </div>
    <input style="width:60px;" type="button" value="OK" name="filerenameOK" />
</div>
<script type="text/javascript">
//<![CDATA[
var LANG = [];
LANG.upload_size = "{LANG.upload_size}";
LANG.pubdate = "{LANG.pubdate}";
LANG.download = "{LANG.download}";
LANG.preview = "{LANG.preview}";
LANG.addlogo = "{LANG.addlogo}";
LANG.select = "{LANG.select}";
LANG.upload_createimage = "{LANG.upload_createimage}";
LANG.move = "{LANG.move}";
LANG.rename = "{LANG.rename}";
LANG.upload_delfile = "{LANG.upload_delfile}";
LANG.createfolder = "{LANG.createfolder}";
LANG.renamefolder = "{LANG.renamefolder}";
LANG.deletefolder = "{LANG.deletefolder}";
LANG.delete_folder = "{LANG.delete_folder}";
LANG.rename_nonamefolder = "{LANG.rename_nonamefolder}";
LANG.folder_exists = "{LANG.folder_exists}";
LANG.name_folder_error = "{LANG.name_folder_error}";
LANG.rename_noname = "{LANG.rename_noname}";
LANG.upload_delimg_confirm = "{LANG.upload_delimg_confirm}";
LANG.origSize = "{LANG.origSize}";
LANG.errorMinX = "{LANG.errorMinX}";
LANG.errorMaxX = "{LANG.errorMaxX}";
LANG.errorMinY = "{LANG.errorMinY}";
LANG.errorMaxY = "{LANG.errorMaxY}";
LANG.errorEmptyX = "{LANG.errorEmptyX}";
LANG.errorEmptyY = "{LANG.errorEmptyY}";

var nv_max_width = {NV_MAX_WIDTH}, nv_max_height = {NV_MAX_HEIGHT}, nv_min_width = {NV_MIN_WIDTH}, nv_min_height = {NV_MIN_HEIGHT};
var nv_base_siteurl = "{NV_BASE_SITEURL}", nv_module_url = "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=", nv_namecheck = /^([a-zA-Z0-9_-])+$/, array_images = ["gif", "jpg", "jpeg", "pjpeg", "png"], array_flash = ["swf", "swc", "flv"], array_archives = ["rar", "zip", "tar"], array_documents = ["doc", "xls", "chm", "pdf", "docx", "xlsx"];

var ICON = [];
ICON.select = nv_base_siteurl + 'js/contextmenu/icons/select.png';
ICON.download = nv_base_siteurl + 'js/contextmenu/icons/download.png';
ICON.preview = nv_base_siteurl + 'js/contextmenu/icons/view.png';
ICON.create = nv_base_siteurl + 'js/contextmenu/icons/copy.png';
ICON.move = nv_base_siteurl + 'js/contextmenu/icons/move.png';
ICON.rename = nv_base_siteurl + 'js/contextmenu/icons/rename.png';
ICON.filedelete = nv_base_siteurl + 'js/contextmenu/icons/delete.png';

$(function() {
  $.getScript(nv_base_siteurl + 'js/upload.js', function() {
   $("#imgfolder").load(nv_module_url + "folderlist&path={PATH}&currentpath={CURRENTPATH}&random=" + nv_randomNum(10));
   $("#imglist").load(nv_module_url + "imglist&path={CURRENTPATH}&type={TYPE}&random=" + nv_randomNum(10))
  });
});
//]]>
</script>
<!-- BEGIN: footer -->
</body>
</html>
<!-- END: footer -->
<!--  END: main  -->
