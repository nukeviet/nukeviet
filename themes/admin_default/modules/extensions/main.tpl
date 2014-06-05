<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: empty -->
<div class="alert alert-info">
	{LANG.empty_response}
</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<div class="ext-wrap clearfix">
	<!-- BEGIN: loop -->
	<div class="ext-item clearfix">
		<div class="col-img">
			<span class="img-thumbnail"><span><img src="{ROW.image_small}" width="100"/></span></span>
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
					<button type="button" class="btn btn-default btn-xs ext-btn">
						<em class="fa fa-share-square-o fa-lg">&nbsp;</em> <a href="">{LANG.detail}</a>
					</button>
					<button type="button" class="btn btn-default btn-xs ext-btn">
						<em class="fa fa-download fa-lg">&nbsp;</em> <a href="">{LANG.install}</a>
					</button>
				</p>
			</div>
			<h3>{ROW.title}</h3>
			<p>{ROW.introtext}</p>
			<p>{LANG.author}: <a>{ROW.username}</a></p>
		</div>
	</div>
	<!-- END: loop -->
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: data -->
<!-- END: main -->