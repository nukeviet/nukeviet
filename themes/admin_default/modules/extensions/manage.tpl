<!-- BEGIN: main -->
<div class="row m-bottom">
	<div class="col-xs-12">
		<div class="pull-left">
			<div class="dropdown">
				<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{LANG.install_package}</button>
				<div class="dropdown-menu" role="menu">
					<div class="nv-panel-body w350">
						<!-- BEGIN: upload -->
						<form onsubmit="return checkform();" class="form-inline" method="post" enctype="multipart/form-data" action="{SUBMIT_URL}">
							<input type="file" class="form-control w200 input-sm" name="extfile"/>
							<input type="submit" name="submit" class="btn btn-primary btn-sm" value="{LANG.install_submit}"/>
						</form>
						<script type="text/javascript">
						function checkext(myArray, myValue) {
							var type = eval(myArray).join().indexOf(myValue) >= 0;
							return type;
						}
						function checkform(){
							var zipfile = $("input[name=extfile]").val();
							if( zipfile == "" ){
								alert("{LANG.install_error_nofile}");
								return false;
							}
							var filezip = zipfile.slice(-3);
							var filegzip = zipfile.slice(-2);
							var allowext = new Array("zip", "gz");
							if (! checkext(allowext, filezip) || ! checkext(allowext, filegzip)) {
								alert("{LANG.install_error_filetype}");
								return false;
							}
							return true;
						}
						</script>
						<!-- END: upload -->
						<!-- BEGIN: nozlib -->
						<span class="text-danger"><strong>{GLANG.error_zlib_support}</strong></span>
						<!-- END: nozlib -->
					</div>
				</div>
			</div>
		</div>
		<div class="pull-right">
			<em class="fa {THEME_CONFIG.sys_icon}"></em> {LANG.extType_sys}
			<em class="fa {THEME_CONFIG.admin_icon}"></em> {LANG.extType_admin}
		</div>
	</div>
</div>
<div class="nv-listing">
	<div class="listing-title">
		<div class="row">
			<div class="col-sm-1 col-sm-offset-2">{LANG.ext_type}</div>
			<div class="col-sm-3">{LANG.extname}</div>
			<div class="col-sm-3">{LANG.file_version}</div>
			<div class="col-sm-3">{LANG.author}</div>
		</div>
	</div>
	<div class="listing-body">
		<!-- BEGIN: loop -->
		<div class="listing-item">
			<div class="row">
				<div class="col-sm-2 text-center">
					<em class="fa fa-cloud-download fa-lg package-ext" data-toggle="tooltip" data-placement="top" title="{LANG.package}" data-href="{ROW.url_package}">&nbsp;</em>
					<!-- BEGIN: delete --><em class="fa fa-trash-o fa-lg delete-ext" data-toggle="tooltip" data-placement="top" title="{GLANG.delete}" data-href="{ROW.url_delete}">&nbsp;</em><!-- END: delete -->
				</div>
				<div class="col-sm-1">{ROW.type}</div>
				<div class="col-sm-3">
					{ROW.basename}
					<!-- BEGIN: icons -->
					<div class="pull-right text-right">
						<!-- BEGIN: loop --><em class="fa {ICON}">&nbsp;</em><!-- END: loop -->
					</div>
					<!-- END: icons -->
				</div>
				<div class="col-sm-3">{ROW.version}</div>
				<div class="col-sm-3">{ROW.author}</div>
			</div>
		</div>
		<!-- END: loop -->
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	$('.package-ext').click(function(e){
		window.location = $(this).data('href');
	});
	$('.delete-ext').click(function(){
		if( confirm('{LANG.delele_ext_confirm}') ){
			$.post($(this).data('href') + '&nocache=' + new Date().getTime(), '', function(res) {
				res = res.split('_');
				alert(res[1]);
				
				if( res[0] == 'OK' ){
					window.location.href = window.location.href;
				}
			});	
		}
	});
});
</script>
<!-- END: main -->