<!-- BEGIN: main -->
<form class="form-inline m-bottom" role="form" action="{NV_BASE_ADMINURL}index.php">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
	<input type="hidden" name="mode" value="search"/>
	<div class="form-group">
		<label class="sr-only" for="search_q">{LANG.search_key}</label>
		<input name="q" value="{REQUEST.q}" type="text" class="form-control" id="search_q" placeholder="{LANG.search_key}">
	</div>
	<button type="submit" class="btn btn-primary">{LANG.search_go}</button>
</form>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: empty -->
<div class="alert alert-info">
	{LANG.empty_response}
</div>
<!-- END: empty -->
<!-- BEGIN: login -->
<div class="alert alert-info">
	{LOGIN_NOTE}
</div>
<!-- END: login -->
<!-- BEGIN: data -->
<div class="ext-wrap clearfix">
	<!-- BEGIN: loop -->
	<div class="ext-item clearfix">
		<div class="col-img">
			<span class="img-thumbnail"><span><img src="{ROW.image_small}" width="100" alt="{ROW.title}"/></span></span>
		</div>
		<div class="col-data">
			<div class="col-info">
				<p>
					<span class="rating">
						<!-- BEGIN: star -->
						<span class="star{STAR}"></span>
						<!-- END: star -->
					</span>
				</p>
				<p>
					<a class="ex-detail btn btn-default btn-xs ext-btn" title="{ROW.detail_title}" href="{ROW.detail_link}"><em class="fa fa-share-square-o fa-lg">&nbsp;</em> {LANG.detail}</a>
					<!-- BEGIN: install -->
					<a class="btn btn-default btn-xs ext-btn" href="{ROW.install_link}"><em class="fa fa-download fa-lg">&nbsp;</em> {LANG.install}</a>
					<!-- END: install -->
				</p>
			</div>
			<div class="col-explain">
				<p>{LANG.author}: <span class="text-primary">{ROW.username}</span></p>
				<p>{LANG.ext_type}: <span class="text-primary">{ROW.type}</span></p>
			</div>
			<h3>{ROW.title}</h3>
			<p>{ROW.introtext}</p>
			<p class="{ROW.compatible_class}">{ROW.compatible_title}</p>
		</div>
	</div>
	<!-- END: loop -->
</div>

<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">{LANG.file_name}</h4>
			</div>
			<div class="modal-body">
				<p class="text-center"><em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em></p>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$('.ex-detail').click(function(e){
		e.preventDefault();
		$('#myModalLabel').html( $(this).attr('title') );
		$('#imagemodal .modal-dialog').css({'width': player_width});
		$('#imagemodal .modal-body').load( $(this).attr('href') );
		$('#imagemodal').modal('show');
	});
});
</script>
<!-- END: data -->
<!-- END: main -->