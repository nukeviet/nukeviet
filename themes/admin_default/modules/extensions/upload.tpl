<!-- BEGIN: info -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: fileinfo -->
<h3><strong>{LANG.autoinstall_uploadedfile}:</strong></h3>
<div class="nv-listing m-bottom">
	<div class="listing-body">
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.autoinstall_uploadedfilesize}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.filesize}</strong></div>
			</div>
		</div>
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.autoinstall_uploaded_filenum}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.filenum}</strong></div>
			</div>
		</div>
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.extname}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.extname}</strong></div>
			</div>
		</div>
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.ext_type}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.exttype}</strong></div>
			</div>
		</div>
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.file_version}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.extversion}</strong></div>
			</div>
		</div>
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.author}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.extauthor}</strong></div>
			</div>
		</div>
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.autoinstall_uploaded_num_exists}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.existsnum}</strong></div>
			</div>
		</div>
		<div class="listing-item">
			<div class="row">
				<div class="col-lg-3 col-sm-4 text-right"><span class="listing-item-title">{LANG.autoinstall_uploaded_num_invalid}</span></div>
				<div class="col-lg-9 col-sm-8"><strong class="text-danger">{INFO.invaildnum}</strong></div>
			</div>
		</div>
	</div>
</div>
<div id="upload-ext-status">
	<!-- BEGIN: fail -->
	<div class="alert alert-danger">{LANG.autoinstall_error_check_fail}</div>
	<!-- END: fail -->
	<!-- BEGIN: warning -->
	<div class="alert alert-warning">{LANG.autoinstall_error_check_warning}</div>
	<!-- END: warning -->
	<!-- BEGIN: success -->
	<div class="alert alert-success">{LANG.autoinstall_error_check_success}</div>
	<!-- END: success -->
</div>
<script type="text/javascript">
$(function(){
	$('#upload-ext-status a').click(function(e){
		e.preventDefault();
		$("#filelist").html('<div class="text-center"><strong>{LANG.autoinstall_package_processing}</strong><br /><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>');
		$("#filelist").load("{EXTRACTLINK}");
	});
});
</script>
<div id="filelist">
	<h3><strong>{LANG.autoinstall_uploaded_filelist}:</strong></h3>
	<div class="clearfix"></div>
	<!-- BEGIN: file -->
	<div class="pull-right m-bottom">
		<em class="fa fa-lg {INFO.classcfg.invaild} text-warning">&nbsp;</em> {LANG.autoinstall_note_invaild} &nbsp; &nbsp;
		<em class="fa fa-lg {INFO.classcfg.exists} text-warning">&nbsp;</em> {LANG.autoinstall_note_exists}
	</div>
	<div class="clearfix"></div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
						{FILE}
						<!-- BEGIN: icons -->
						<div class="pull-right">
							<!-- BEGIN: icon --><em class="fa {ICON} text-warning">&nbsp;</em> &nbsp;<!-- END: icon -->
						</div>
						<!-- END: icons -->
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<!-- END: file -->
</div>
<!-- END: fileinfo -->
<!-- END: info -->

<!-- BEGIN: extract -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: errorfile -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-danger">{LANG.autoinstall_error_warning_fileexist}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-danger">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: errorfile -->
<!-- BEGIN: errorfolder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-danger">{LANG.autoinstall_error_warning_invalidfolder}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-danger">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: errorfolder -->
<!-- BEGIN: infoerror -->
<div id="checkmessage">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td class="text-center"><strong class="text-danger">{LANG.autoinstall_error_warning_overwrite}</strong></td>
				</tr>
				<tr>
					<td class="text-center"><input type="button" name="install_content_overwrite" value="{LANG.autoinstall_overwrite}" class="btn btn-primary"/></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("input[name=install_content_overwrite]").click(function() {
			if (confirm("{LANG.autoinstall_error_warning_overwrite}")) {
				$("#checkmessage").html('<div style="text-align:center"><strong>{LANG.autoinstall_package_processing}</strong><br /><br /><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="" /></div>');
				$("#checkmessage").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=install_check&overwrite={CHECKSESS}");
			}
		});
	});
	//]]>
</script>
<!-- END: infoerror -->
<!-- BEGIN: complete -->
<!-- BEGIN: no_extract -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-danger">{LANG.autoinstall_cantunzip}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-danger">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: no_extract -->
<!-- BEGIN: error_create_folder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-danger">{LANG.autoinstall_error_warning_permission_folder}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-danger">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: error_create_folder -->
<!-- BEGIN: error_move_folder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-danger">{LANG.autoinstall_error_movefile}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-danger">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: error_move_folder -->
<!-- BEGIN: error_mine -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center" colspan="2"><p><strong class="text-warning">{LANG.autoinstall_error_mimetype}</strong></p><a href="#" class="btn btn-warning upload-dismiss-mime">{LANG.autoinstall_error_mimetype_pass}</a></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-warning">{FILENAME}</td>
				<td class="text-warning">{MIME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(function(){
	$('.upload-dismiss-mime').click(function(e){
		e.preventDefault();
		$("#filelist").html('<div class="text-center"><strong>{LANG.autoinstall_package_processing}</strong><br /><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>');
		$("#filelist").load("{DISMISS_LINK}");
	});
});
</script>
<!-- END: error_mine -->
<!-- BEGIN: ok -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-success">{LANG.autoinstall_unzip_success}</strong></td>
			</tr>
			<tr>
				<td class="text-center"><a href="{URL_GO}" title="{LANG.autoinstall_unzip_setuppage}">{LANG.autoinstall_unzip_setuppage}</a></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	setTimeout("redirect_page()", 5000);
	function redirect_page() {
		parent.location = "{URL_GO}";
	}

	//]]>
</script>
<!-- END: ok -->
<!-- END: complete -->
