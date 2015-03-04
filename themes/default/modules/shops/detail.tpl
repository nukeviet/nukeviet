<!-- BEGIN: main -->
<div id="fb-root"></div>
<script type="text/javascript">
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/{FACEBOOK_LANG}/all.js#xfbml=1&appId={FACEBOOK_APPID}";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<div itemscope itemtype="http://schema.org/Product" style="display: none">
	<span itemprop="name">{TITLE}</span>
	<img itemprop="image" src="{SRC_PRO_FULL}" alt="{TITLE}" />
	<span itemprop="description">{hometext}</span>
	<span itemprop="mpn">{PRODUCT_CODE}</span>
	<!-- BEGIN: rate -->
	<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<span itemprop="ratingValue">{RATE_TOTAL}</span> {LANG.trong}
		<span itemprop="reviewCount">{RATE_VALUE} </span> {LANG.dg}
	</span>
	<!-- END: rate -->
	<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<span itemprop="category">{CAT_TITLE}</span>
		<!-- BEGIN: price1 -->
		<span itemprop="price">{PRICE.sale_format}</span>
		<span itemprop="priceCurrency">{PRICE.unit}</span>
		<!-- END: price1 -->
		<span itemprop="availability">{LANG.detail_pro_number}: {PRODUCT_NUMBER} {pro_unit}</span>
	</span>
</div>

<div id="detail">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-6 text-center">
					<a href="{SRC_PRO_LAGE}" title="{TITLE}" <!-- BEGIN: shadowbox -->rel="shadowbox"<!-- END: shadowbox -->> <img src="{SRC_PRO}" alt="" width="140px" class="img-thumbnail"> </a>
					<br />
					<em class="fa fa-search-plus text-primary zoom_img">&nbsp;</em><a href="{SRC_PRO_LAGE}" title="{TITLE}" rel="shadowbox[miss]">{LANG.detail_view_lage_img}</a>
					<!-- BEGIN: adminlink -->
					<p>
						{ADMINLINK}
					</p>
					<!-- END: adminlink -->
				</div>

				<div class="col-xs-18">
				<ul class="product_info">
					<li>
						<h2>{TITLE}</h2>
					</li>
					<li class="text-muted">
						{DATE_UP} - {NUM_VIEW} {LANG.detail_num_view}
					</li>

					<!-- BEGIN: product_code -->
					<li>
						{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
					</li>
					<!-- END: product_code -->

					<!-- BEGIN: price -->
					<li>
						<p>
							{LANG.detail_pro_price}:
							<!-- BEGIN: discounts -->
							<span class="money">{PRICE.sale_format} {PRICE.unit}</span>
							<span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
							<span class="money">{product_discounts} {money_unit}</span>
							<!-- END: discounts -->

							<!-- BEGIN: no_discounts -->
							<span class="money">{PRICE.price_format} {PRICE.unit}</span>
							<!-- END: no_discounts -->
						</p>
					</li>
					<!-- END: price -->

					<!-- BEGIN: contact -->
					<li>
						{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
					</li>
					<!-- END: contact -->

					<!-- BEGIN: group_detail -->
					<li>
						<!-- BEGIN: loop -->
						<!-- BEGIN: maintitle -->
						<div class="pull-left">
							<strong>{MAINTITLE}:</strong>&nbsp;
						</div>
						<!-- END: maintitle -->

						<!-- BEGIN: subtitle -->
						<ul class="pull-left" style="padding: 0 10px 0">
							<!-- BEGIN: loop -->
							<li>
								<a href="{SUBTITLE.link}" title="{SUBTITLE.title}">{SUBTITLE.title}</a>
							</li>
							<!-- END: loop -->
						</ul>
						<div class="clear"></div>
						<!-- END: subtitle -->
						<!-- END: loop -->
					</li>
					<!-- END: group_detail -->

					<!-- BEGIN: custom_data -->
					<!-- BEGIN: loop -->
					<li>
						<p>
							<strong>{CUSTOM_LANG}:</strong> {CUSTOM_DATA}
						</p>
					</li>
					<!-- END: loop -->
					<!-- END: custom_data -->

					<!-- BEGIN: hometext -->
					<li>
						<p class="text-justify">
							{hometext}
						</p>
					</li>
					<!-- END: hometext -->

					<!-- BEGIN: promotional -->
					<li>
						<strong>{LANG.detail_promotional}:</strong> {promotional}
					</li>
					<!-- END: promotional -->

					<!-- BEGIN: warranty -->
					<li>
						<strong>{LANG.detail_warranty}:</strong> {warranty}
					</li>
					<!-- END: warranty -->
				</ul>
				<hr />

				<!-- BEGIN: group -->
				<div class="row">
					<!-- BEGIN: items -->
					<div class="col-xs-8 col-md-12">
						<div class="form-group">
							<select class="form-control" name="group">
								<!-- BEGIN: header -->
								<option value="">---{HEADER}---</option>
								<!-- END: header -->
								<!-- BEGIN: loop -->
								<option value="{GROUP.groupid}">{GROUP.title}</option>
								<!-- END: loop -->
							</select>
						</div>
					</div>
					<!-- END: items -->
				</div>
				<!-- END: group -->

				<div class="clearfix">
					&nbsp;
				</div>

				<div id="ratedata">
					<!-- BEGIN: allowed_rating -->
					<div class="clearfix">
						<span class="rateavg_percent">{LANG.rateavg_percent}: {RATE_AVG_PERCENT}</span>
						<form id="form3B" action="">
							<div class="clearfix">
								<div id="stringrating" class="small">
									{STRINGRATING}
								</div>

								<div class="rate-star">
									<div class="width-star-title">
										{LANG.5star}
									</div>
									<div class="width-star-bg" value-data="5">
										<input type="hidden" name="valuerate" value="5" />
										<div class="width-star-value" title="{PERCENT_RATE.5}%" style="width:{PERCENT_RATE.5}%">
											&nbsp;
										</div>
									</div>
									<div class="width-star-num">
										{RATINGDETAIL.5}
									</div>
								</div>
								<br />

								<div class="rate-star">
									<div class="width-star-title">
										{LANG.4star}
									</div>
									<div class="width-star-bg" value-data="4">
										<input type="hidden" name="valuerate" value="4" />
										<div class="width-star-value" title="{PERCENT_RATE.4}%" style="width:{PERCENT_RATE.4}%">
											&nbsp;
										</div>
									</div>
									<div class="width-star-num">
										{RATINGDETAIL.4}
									</div>
								</div>
								<br />

								<div class="rate-star">
									<div class="width-star-title">
										{LANG.3star}
									</div>
									<div class="width-star-bg" value-data="3">
										<input type="hidden" name="valuerate" value="3" />
										<div class="width-star-value" title="{PERCENT_RATE.3}%" style="width:{PERCENT_RATE.3}%">
											&nbsp;
										</div>
									</div>
									<div class="width-star-num">
										{RATINGDETAIL.3}
									</div>
								</div>
								<br />

								<div class="rate-star">
									<div class="width-star-title">
										{LANG.2star}
									</div>
									<div class="width-star-bg" value-data="2">
										<input type="hidden" name="valuerate" value="2" />
										<div class="width-star-value" title="{PERCENT_RATE.2}%" style="width:{PERCENT_RATE.2}%">
											&nbsp;
										</div>
									</div>
									<div class="width-star-num">
										{RATINGDETAIL.2}
									</div>
								</div>
								<br />

								<div class="rate-star">
									<div class="width-star-title">
										{LANG.1star}
									</div>
									<div class="width-star-bg" value-data="1">
										<input type="hidden" name="valuerate" value="1" />
										<div class="width-star-value" title="{PERCENT_RATE.1}%" style="width:{PERCENT_RATE.1}%">
											&nbsp;
										</div>
									</div>
									<div class="width-star-num">
										{RATINGDETAIL.1}
									</div>
								</div>
							</div>
						</form>
					</div>
					<!-- END: allowed_rating -->
				</div>
			</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10">
			<!-- BEGIN: social_icon -->
			<ul style="padding: 0; margin-top: 12px;">
				<li class="pull-left"><div class="fb-like" data-href="{SELFURL}" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false" data-share="true">&nbsp;</div></li>
				<li class="pull-left"><div class="g-plusone" data-size="medium"></div></li>
			</ul>
	        <script type="text/javascript">
	          window.___gcfg = {lang: nv_sitelang};
	          (function() {
	            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	            po.src = 'https://apis.google.com/js/plusone.js';
	            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	          })();
	        </script>
	        <!-- END: social_icon -->
		</div>
		<div class="col-md-14">
           <!-- BEGIN: typepeice -->
			<table class="table table-striped table-bordered table-hover" style="width: 264px">
				<thead>
					<tr>
						<th class="text-right">{LANG.detail_pro_number}</th>
						<th class="text-left">{LANG.cart_price} ({money_unit})</th>
					</tr>
				</thead>
				<tbody>
					<!-- BEGIN: items -->
					<tr>
						<td class="text-right">{ITEMS.number_from} -> {ITEMS.number_to}</td>
						<td class="text-left">{ITEMS.price}</td>
					</tr>
					<!-- END: items -->
				</tbody>
			</table>
			<!-- END: typepeice -->

			<!-- BEGIN: order -->
			<div class="pull-right" style="margin-top: 6px">
				<span class="pull-left text-muted" style="margin: 6px 20px 0">{LANG.product_number}: <strong>{PRODUCT_NUMBER}</strong> {pro_unit}</span>
				<input type="number" name="num" value="1" id="pnum" class="pull-left form-control" style="width: 70px">
				<a href="javascript:void(0)" data-id="{proid}" title="{title_pro}" onclick="cartorder_detail(this, '{POPUP}', 0)">
				<button class="btn btn-danger btn-xs" style="margin: 5px 0 0 5px">
					{LANG.add_cart}
				</button></a>
				<a href="javascript:void(0)" data-id="{proid}" title="{title_pro}" onclick="cartorder_detail(this, '{POPUP}', 1)">
				<button class="btn btn-success btn-xs" style="margin: 5px 0 0 5px">
					{LANG.buy_now}
				</button></a>
			</div>
			<!-- END: order -->

			<!-- BEGIN: product_empty -->
			<div class="pull-right" style="margin-top: 6px">
				<button class="btn btn-danger disabled">
					{LANG.product_empty}
				</button>
			</div>
			<!-- END: product_empty -->
		</div>
	</div>

	<div style="detail_com">
		<!-- BEGIN: shop -->
		{LANG.company_product} : <a href="{link_shop}" title="{title_shop}">{title_shop}</a>
		<!-- END: shop -->
	</div>

	<div id="tabs" class="tabs">
		<nav>
			<ul>
				<!-- BEGIN: product_detail -->
				<li>
					<a href="#section-1"><em class="fa fa-bars">&nbsp;</em><span>{LANG.product_detail}</span></a>
				</li>
				<!-- END: product_detail -->

				<!-- BEGIN: othersimg_title -->
				<li>
					<a href="#section-2"><em class="fa fa-picture-o">&nbsp;</em><span>{LANG.add_otherimage}</span></a>
				</li>
				<!-- END: othersimg_title -->

				<!-- BEGIN: comment_tab -->
				<li>
					<a href="#section-3"><em class="fa fa-comments-o">&nbsp;</em><span>{LANG.detail_comments}</span></a>
				</li>
				<!-- END: comment_tab -->
			</ul>
		</nav>
		<div class="content">
			<section id="section-1">
				{DETAIL}
			</section>

			<!-- BEGIN: othersimg -->
			<section id="section-2">
				<!-- BEGIN: loop -->
				<div class="col-xs-12 col-md-6">
					<a href="{IMG_SRC_OTHER}" class="thumbnail" rel="shadowbox[miss]"><img src="{IMG_SRC_OTHER}" style="max-height: 100px" /></a>
				</div>
				<!-- END: loop -->
				<div class="clear">
					&nbsp;
				</div>
			</section>
			<!-- END: othersimg -->

			<!-- BEGIN: comment -->
			<section id="section-3">
				{CONTENT_COMMENT}
			</section>
			<!-- END: comment -->
		</div>
	</div>

	<!-- BEGIN: other -->
	<div class="panel panel-default">
		<div class="panel-heading">
			{LANG.detail_others}
		</div>
		<div class="panel-body">
			{OTHER}
		</div>
	</div>
	<!-- END: other -->

	<!-- BEGIN: other_view -->
	<div class="panel panel-default">
		<div class="panel-heading">
			{LANG.detail_others_view}
		</div>
		<div class="panel-body">
			{OTHER_VIEW}
		</div>
	</div>
	<!-- END: other_view -->
</div>
<div class="msgshow" id="msgshow"></div>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/tabresponsive.js"></script>
<script type="text/javascript">
	var detail_error_group = '{LANG.detail_error_group}';
	new CBPFWTabs( document.getElementById( 'tabs' ) );
	$(function(){
	<!-- BEGIN: allowed_print_js -->
	$('#click_print').click(function(event){
	var href = $(this).attr("href");
	event.preventDefault();
	nv_open_browse(href, '', 640, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
	return false;
	});
	<!-- END: allowed_print_js -->

	<!-- BEGIN: allowed_rating_js -->
	$(".width-star-bg").click(function(event){
	event.preventDefault();
	var val = $(this).attr("value-data");
	if( confirm( '{LANG.rateconfirm}' )){
	$.ajax({
	type: "POST",
	url: '{LINK_RATE}'+'&nocache=' + new Date().getTime(),
	data: 'val=' + val,
	success: function(data){
	var s = data.split('_');
	if( s[0] == 'OK' ){
	$("#ratedata").load('{LINK_RATE}&showdata=1');
	}
	alert(s[1]);
	}
	});
	}
	return false;
	});
	<!-- END: allowed_rating_js -->
	});</script>
<!-- END: main -->