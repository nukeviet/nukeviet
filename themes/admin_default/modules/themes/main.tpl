<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div id="edit">&nbsp;</div>
<div class="alert alert-danger"><span id="message">ERROR! CONFIG FILE: {ERROR}</span></div>
<!-- END: error -->
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<tbody>
		<tr>
			<!-- BEGIN: loop -->
			<!-- BEGIN: active -->
			<td style="padding-left:50px;width:50%;background-color:#FFDBB7">
			<!-- END: active -->
			<!-- BEGIN: deactive -->
			<td style="padding-left:50px;width:50%">
			<!-- END: deactive -->
			<p>
				<strong>{ROW.name}</strong> {LANG.theme_created_by} <a href="{ROW.website}" title="{LANG.theme_created_website}" style="color:#3B5998" onclick="this.target='_blank'"><strong>{ROW.author}</strong></a>
			</p>
			<p><a href="" data-src="{NV_BASE_SITEURL}themes/{ROW.value}/{ROW.thumbnail}" data-title="{ROW.name}" title="{ROW.name}" class="open_modal"><img alt="{ROW.name}" src="{NV_BASE_SITEURL}themes/{ROW.value}/{ROW.thumbnail}" style="max-width:300px;max-height:200px"/></a>
			</p>
			<p style="font-size:13px;margin-top:10px;font-weight:bold">
				<!-- BEGIN: link_setting -->
				<em class="fa fa-sun-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" class="activate" title="{ROW.value}" style="color:#3B5998">{LANG.theme_created_setting}</a>
				<!-- END: link_setting -->
				<!-- BEGIN: link_active -->
				<em class="fa fa-sun-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" class="activate" title="{ROW.value}" style="color:#3B5998">{LANG.theme_created_activate}</a>
				<!-- END: link_active -->
				<!-- BEGIN: link_delete -->
				<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" class="delete" title="{ROW.value}" style="color:#3B5998">{LANG.theme_delete}</a>
				<!-- END: link_delete -->
			<p style="font-size:13px">
				{ROW.description}
			</p>
			<p style="font-size:13px;margin-top:10px">
				{LANG.theme_created_folder} <span style="background-color:#E5F4FD">/themes/{ROW.value}/</span>
			</p>
			<p style="font-size:13px;margin-top:20px">
				{LANG.theme_created_position} {POSITION}
			</p>
			<!-- BEGIN: endtr -->
			</td>
		</tr>
		<tr>
			<!-- END: endtr -->
			<!-- BEGIN: endtd -->
			</td>
			<!-- END: endtd -->
			<!-- END: loop -->
		</tr>
	</tbody>
	</table>
</div>
<div class="modal fade" id="idmodals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				&nbsp;
			</div>
			<div class="modal-body">
				<p class="text-center"><em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em></p>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
LANG.theme_delete_confirm = '{LANG.theme_delete_confirm}';
//]]>
$('.open_modal').click(function(e){
	e.preventDefault();
	$('#idmodals .modal-header').html( '<strong>' + $(this).data('title') + '</strong><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>');
	$('#idmodals .modal-body').html( '<img src="' + $(this).data('src') + '" alt="" class="center-block img-responsive" />' );
	$('#idmodals').modal('show');
});
</script>
<!-- END: main -->