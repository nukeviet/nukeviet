<!-- BEGIN: main -->
<div class="infoerror" id="infodetectedupg">
	{LANG.update_package_error}<br />
	<strong><a class="delete_update_backage" href="{URL_DELETE}" title="{LANG.update_package_delete}">{LANG.update_package_delete}</a></strong>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.delete_update_backage').click(function(){
			if( confirm( nv_is_del_confirm[0] ) ){
				$('#infodetectedupg').append('<div id="dpackagew"><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Waiting..."/></div>');
				$.get( $(this).attr('href') , function(e){
					$('#dpackagew').remove()
					if( e == 'OK' ){
						window.location = '{URL_RETURN}';
					}else{
						alert(e);
					}
				});
			}
			return !1;
		});
	});
	</script>
</div>
<!-- END: main -->