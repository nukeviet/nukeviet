<!-- BEGIN: uploadPage -->
<iframe src="{IFRAME_SRC}" style="border: 0;width: 100%;height:400px">&nbsp;</iframe>
<!-- END: uploadPage -->
<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.resizable.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.button.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.dialog.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/jquery/jquery.treeview.css" rel="stylesheet" />

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.draggable.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.resizable.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.button.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/contextmenu/jquery.contextmenu.r2.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.flash.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.upload.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.lazyload.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.treeview.min.js"></script>

<div class="content">
	<div class="row upload-wrap">
		<div class="col-lg-2 col-md-2 col-sm-3 imgfolder" id="imgfolder">
			<p class="upload-loading">
				<em class="fa fa-spin fa-spinner fa-2x m-bottom upload-fa-loading">&nbsp;</em>
				<br />
				{LANG.waiting}...
			</p>
		</div>
		<div class="col-lg-10 col-md-10 col-sm-9 filebrowse">
			<div id="imglist">
				<p class="upload-loading">
					<em class="fa fa-spin fa-spinner fa-2x m-bottom upload-fa-loading">&nbsp;</em>
					<br />
					{LANG.waiting}...
				</p>
			</div>
		</div>
	</div>
</div>
<div class="footer">
	<div class="row">
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-1">
					<div class="refresh text-right">
						<em title="{LANG.refresh}" class="fa fa-refresh fa-lg fa-pointer">&nbsp;</em>
					</div>
				</div>
				<div class="col-sm-3">
					<select name="imgtype" class="form-control input-sm vchange">
						<option value="file"{SFILE}>{LANG.type_file}</option>
						<option value="image"{SIMAGE}>{LANG.type_image}</option>
						<option value="flash"{SFLASH}>{LANG.type_flash}</option>
					</select> 
				</div>
				<div class="col-sm-3">
					<select name="author" class="form-control input-sm vchange">
						<option value="0">{LANG.author0}</option>
						<option value="1">{LANG.author1}</option>
					</select> 
				</div>
				<div class="col-sm-3">
					<select name="order" class="form-control input-sm vchange">
						<option value="0">{LANG.order0}</option>
						<option value="1">{LANG.order1}</option>
						<option value="2">{LANG.order2}</option>
					</select>
				</div>
				<div class="col-sm-2">
					<div class="search text-left">
						<em title="{LANG.search}" class="fa fa-search fa-lg fa-pointer">&nbsp;</em>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="uploadForm" class="upload-hide">
				<div style="margin-top:5px;margin-right:5px;float:left;" id="cfile">
					{LANG.upload_file}
				</div>
				<div class="upload"><input type="file" name="upload" id="myfile"/>
				</div>
				<div style="margin-top:10px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/load_bar.gif"/>
				</div>
				<div style="margin-top:5px;margin-left:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/ok.png"/>
				</div>
				<div style="margin-top:7px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/error.png"/>
				</div>
				<div style="float:left;margin:0 5px;">
					{LANG.upload_otherurl}: <input type="text" name="imgurl"/>
				</div>
				<div style="margin-top:10px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/load_bar.gif"/>
				</div>
				<div style="margin-top:5px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/ok.png"/>
				</div>
				<div style="margin-top:7px;margin-left:5px;margin-right:5px;float:left;display:none"><img src="{NV_BASE_SITEURL}images/error.png"/>
				</div>
				<div style="float:left;"><input type="button" value="Upload" id="confirm" />
				</div>
			</div>
			<div class="notupload" class="upload-hide">
				{LANG.notupload}
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>

<input type="hidden" name="currentFileUpload" value=""/>
<input type="hidden" name="currentFileUrl" value=""/>
<input type="hidden" name="selFile" value=""/>
<input type="hidden" name="CKEditorFuncNum" value="{FUNNUM}"/>
<input type="hidden" name="area" value="{AREA}"/>
<input type="hidden" name="alt" value="{ALT}"/>

<div class="upload-hide" id="contextMenu"></div>

<div class="upload-hide">
	<iframe id="Fdownload" src="" width="0" height="0" frameborder="0"></iframe>
</div>

<div id="renamefolder" class="upload-hide" title="{LANG.renamefolder}">
	<div class="form-horizontal" role="form">
		<div class="form-group">
			<label class="control-label col-xs-3">{LANG.rename_newname}:</label>
			<div class="col-xs-9">
				<input type="text" name="foldername" class="form-control"/>
			</div>
		</div>
	</div>
</div>

<div id="createfolder" class="upload-hide" title="{LANG.createfolder}">
	<div class="form-horizontal" role="form">
		<div class="form-group">
			<label class="control-label col-xs-5">{LANG.foldername}:</label>
			<div class="col-xs-7">
				<input type="text" name="createfoldername" class="form-control"/>
			</div>
		</div>
	</div>
</div>

<div id="errorInfo" class="upload-hide" title="{LANG.errorInfo}"></div>

<div id="imgpreview" title="{LANG.preview}">
	<div id="fileInfoAlt" class="dynamic file-title"></div>
	<div id="fileView" class="dynamic file-content"></div>
	<div id="fileInfoName" class="dynamic file-title"></div>
	<div id="fileInfoDetail" class="dynamic file-detail"></div>
</div>

<div id="imgcreate" title="{LANG.upload_createimage}">
	<div class="row">
		<div class="col-xs-5">
			<input type="hidden" name="origWidth" value="" class="dynamic" />
			<input type="hidden" name="origHeight" value="" class="dynamic" />
			<div class="title">{LANG.newSize}</div>
			<div class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-xs-2 control-label">X:</label>
					<div class="col-xs-4"><input type="text" name="newWidth" maxlength="4" class="dynamic form-control" /></div>
					<label class="col-xs-2 control-label">Y:</label>
					<div class="col-xs-4"><input type="text" name="newHeight" maxlength="4" class="dynamic form-control" /></div>
				</div>
			</div>
			<div class="text-center form-group">
				<button class="btn btn-default" type="button" name="prView"><em class="fa fa-search fa-lg">&nbsp;</em>{LANG.prView}</button>
				<button class="btn btn-primary" type="button" name="newSizeOK">{LANG.addlogosave}</button>
			</div>
			<div title="createInfo" class="dynamic text-center text-muted"></div>
		</div>
		<div class="col-xs-7 text-center">
			<div class="image-preview-wrap clearfix">
				<div id="fileInfoName2" class="dynamic title"></div>
				<div id="fileInfoDetail2" class="dynamic"></div>
				<div class="image-preview">
					<img name="myFile2" alt="{LANG.clickSize}" width="0" height="0" src="" />
				</div>
			</div>
		</div>
	</div>
</div>

<div id="filemove" title="{LANG.move}">
	<div title="pathFileName" class="dynamic filename"></div>
	<div class="form-group">
		<label>{LANG.movefolder}:</label>
		<select name="newPath" class="form-control"></select>
	</div>
	<div class="checkbox">
		<label>
			<input name="mirrorFile" type="checkbox" class="dynamic" /> {LANG.mirrorFile}.
		</label>
	</div>
	<div class="checkbox">
		<label>
			<input name="goNewPath" type="checkbox" class="dynamic" /> {LANG.goNewPath}.
		</label>
	</div>
	<div class="text-center">
		<input type="button" value="{LANG.addlogosave}" name="newPathOK" class="btn btn-primary"/>
	</div>
</div>

<div id="filesearch" title="{LANG.search}">
	<form method="get" onsubmit="return searchfile();" role="form">
		<div class="form-group">
			<label>{LANG.searchdir}:</label>
			<select name="searchPath" class="form-control"></select>
		</div>
		<div class="form-group">
			<label>{LANG.searchkey}:</label>
			<input name="q" type="text" class="form-control dynamic" />
		</div>
		<div class="text-center">
			<button type="submit" name="search" class="btn btn-primary">{LANG.search}</button>
		</div>
	</form>
</div>

<div id="filerename" title="{LANG.rename}">
	<div id="filerenameOrigName" class="dynamic origname text-center"></div>
	<div class="form-horizontal" role="form">
		<div class="form-group">
			<label class="col-xs-4 control-label">{LANG.rename_newname}:</label>
			<div class="col-xs-7">
				<input type="text" name="filerenameNewName" maxlength="255" class="dynamic form-control" />
			</div>
			<div class="col-xs-1">
				<span title="Ext">Ext</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-4 control-label">{LANG.altimage}:</label>
			<div class="col-xs-8">
				<input type="text" name="filerenameAlt" maxlength="255" class="dynamic form-control" />
			</div>
		</div>
		<div class="text-center">
			<input class="btn btn-primary" type="button" value="{LANG.addlogosave}" name="filerenameOK" />
		</div>
	</div>
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
LANG.crop = "{LANG.crop}";
LANG.rotate = "{LANG.rotate}";

var nv_max_width = '{NV_MAX_WIDTH}', nv_max_height = '{NV_MAX_HEIGHT}', nv_min_width = '{NV_MIN_WIDTH}', nv_min_height = '{NV_MIN_HEIGHT}';
var nv_module_url = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=", nv_namecheck = /^([a-zA-Z0-9_-])+$/, array_images = ["gif", "jpg", "jpeg", "pjpeg", "png"], array_flash = ["swf", "swc", "flv"], array_archives = ["rar", "zip", "tar"], array_documents = ["doc", "xls", "chm", "pdf", "docx", "xlsx"];
var nv_loading_data = '<p class="upload-loading"><em class="fa fa-spin fa-spinner fa-2x m-bottom upload-fa-loading">&nbsp;</em><br />{LANG.waiting}...</p>';
//]]>
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/upload.js"></script>
<script type="text/javascript">
$(function(){
	$("#imgfolder").load(nv_module_url + "folderlist&path={PATH}&currentpath={CURRENTPATH}&random=" + nv_randomNum(10));
	$("#imglist").load(nv_module_url + "imglist&path={CURRENTPATH}&type={TYPE}&random=" + nv_randomNum(10))
});
</script>
<!--  END: main  -->