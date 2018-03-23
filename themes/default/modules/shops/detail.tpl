<!-- BEGIN: main -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/lightSlider/css/lightslider.min.css" rel="stylesheet" type="text/css">
<div itemscope itemtype="http://schema.org/Product" style="display: none">
    <span itemprop="name">{TITLE}</span> <img itemprop="image" src="{SRC_PRO_FULL}" alt="{TITLE}" /> <span itemprop="description">{hometext}</span> <span itemprop="mpn">{PRODUCT_CODE}</span>
    <!-- BEGIN: allowed_rating_snippets -->
    <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"> <span itemprop="ratingValue">{RATE_VALUE}</span> {LANG.trong} <span itemprop="reviewCount">{RATE_TOTAL} </span> {LANG.dg}
    </span>
    <!-- END: allowed_rating_snippets -->
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer"> <span itemprop="category">{CAT_TITLE}</span> <!-- BEGIN: price1 --> <span itemprop="price">{PRICE.sale_format}</span> <span itemprop="priceCurrency">{PRICE.unit}</span> <!-- END: price1 --> <span itemprop="availability">{LANG.detail_pro_number}: {PRODUCT_NUMBER} {pro_unit}</span>
    </span>
</div>
<div id="detail"<!-- BEGIN: popupid --> class="prodetail-popup"<!-- END: popupid -->>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-24 col-sm-10 col-md-10 text-center">
                    <!-- BEGIN: image -->
                    <div class="image">
                        <ul id="imageGallery" class="gallery">
                            <!-- BEGIN: loop -->
                            <li data-thumb="{IMAGE.thumb}" style="height: 200px" data-src="{IMAGE.file}"><img src="{IMAGE.file}" /></li>
                            <!-- END: loop -->
                        </ul>
                    </div>
                    <!-- END: image -->
                    <a href="" data-src="{SRC_PRO_LAGE}" data-width="{SRC_PRO_LAGE_INFO.width}" class="open_modal" title="{TITLE}"><img src="{SRC_PRO}" alt="" class="img-thumbnail" id="imageproduct"></a> <br />
                    <!-- BEGIN: adminlink -->
                    <p class="tab-pane">{ADMINLINK}</p>
                    <!-- END: adminlink -->
                    <!-- BEGIN: social_icon -->
                    <div class="news_column panel panel-default">
                        <div class="panel-body">
                            <div class="socialicon">
                                <div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">&nbsp;</div>
                                <div class="g-plusone" data-size="medium"></div>
                                <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
                            </div>
                        </div>
                    </div>
                    <!-- END: social_icon -->
                </div>
                <div class="col-xs-24 col-sm-14 col-md-14">
                    <ul class="product_info">
                        <li>
                            <h2>{TITLE}</h2>
                        </li>
                        <li class="text-muted">{DATE_UP} - {NUM_VIEW} {LANG.detail_num_view}</li>
                        <!-- BEGIN: product_code -->
                        <li>{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
                        </li>
                        <!-- END: product_code -->
                        <!-- BEGIN: price -->
                        <li>
                            <p>
                                {LANG.detail_pro_price}:
                                <!-- BEGIN: discounts -->
                                <span class="money">{PRICE.sale_format} {PRICE.unit}</span> <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span> <span class="money">{product_discounts} {money_unit}</span>
                                <!-- END: discounts -->
                                <!-- BEGIN: no_discounts -->
                                <span class="money">{PRICE.price_format} {PRICE.unit}</span>
                                <!-- END: no_discounts -->
                            </p>
                        </li>
                        <!-- END: price -->
                        <!-- BEGIN: product_weight -->
                        <li>{LANG.weights}: <strong>{PRODUCT_WEIGHT}</strong>&nbsp<span>{WEIGHT_UNIT}</span>
                        </li>
                        <!-- END: product_weight -->
                        <!-- BEGIN: contact -->
                        <li>{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
                        </li>
                        <!-- END: contact -->
                        <!-- BEGIN: group_detail -->
                        <li>
                            <!-- BEGIN: loop --> <!-- BEGIN: maintitle -->
                            <div class="pull-left">
                                <strong>{MAINTITLE}:</strong>&nbsp;
                            </div> <!-- END: maintitle --> <!-- BEGIN: subtitle -->
                            <ul class="pull-left list-inline" style="padding: 0 10px 0">
                                <!-- BEGIN: loop -->
                                <li>{SUBTITLE.title}</li>
                                <!-- END: loop -->
                            </ul>
                            <div class="clear"></div> <!-- END: subtitle --> <!-- END: loop -->
                        </li>
                        <!-- END: group_detail -->
                        <!-- BEGIN: custom_data -->
                        {CUSTOM_DATA}
                        <!-- END: custom_data -->
                        <!-- BEGIN: hometext -->
                        <li>
                            <p class="text-justify">{hometext}</p>
                        </li>
                        <!-- END: hometext -->
                        <!-- BEGIN: promotional -->
                        <li><strong>{LANG.detail_promotional}:</strong> {promotional}</li>
                        <!-- END: promotional -->
                        <!-- BEGIN: warranty -->
                        <li><strong>{LANG.detail_warranty}:</strong> {warranty}</li>
                        <!-- END: warranty -->
                    </ul>
                    <hr />
                    <!-- BEGIN: gift -->
                    <div class="alert alert-info">
                        <div class="pull-left">
                            <em class="fa fa-gift fa-3x">&nbsp;</em>
                        </div>
                        <div class="pull-left">
                            <h4>{gift_content}</h4>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- END: gift -->
                    <!-- BEGIN: group -->
                    <div class="well">
                        <div class="filter_product">
                            <!-- BEGIN: items -->
                            <div class="row">
                                <!-- BEGIN: header -->
                                <div class="col-xs-8 col-sm-5" style="margin-top: 4px">{HEADER}</div>
                                <!-- END: header -->
                                <div class="col-xs-16 col-sm-19 itemsgroup" data-groupid="{GROUPID}" data-header="{HEADER}">
                                    <!-- BEGIN: loop -->
                                    <label class="label_group <!-- BEGIN: active -->active<!-- END: active -->"> <input type="radio" class="groupid" onclick="check_quantity( $(this) )" name="groupid[{GROUPID}]" value="{GROUP.groupid}"
                                    <!-- BEGIN: checked -->checked="checked" <!-- END: checked -->>{GROUP.title}
                                    </label>
                                    <!-- END: loop -->
                                </div>
                            </div>
                            <!-- END: items -->
                        </div>
                        <span id="group_error">&nbsp;</span>
                    </div>
                    <!-- END: group -->
                    <!-- BEGIN: order_number -->
                    <div class="well">
                        <div class="row">
                            <div class="col-xs-8 col-sm-5">{LANG.detail_pro_number}</div>
                            <div class="col-xs-16 col-sm-19">
                                <input type="number" name="num" value="1" min="1" id="pnum" class="pull-left form-control" style="width: 100px; margin-right: 5px">
                                <!-- BEGIN: product_number -->
                                <span class="help-block pull-left" id="product_number">{LANG.detail_pro_number}: <strong>{PRODUCT_NUMBER}</strong> {pro_unit}
                                </span>
                                <!-- END: product_number -->
                            </div>
                        </div>
                    </div>
                    <!-- END: order_number -->
                    <div class="clearfix"></div>
                    <!-- BEGIN: typepeice -->
                    <table class="table table-striped table-bordered table-hover">
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
                    <button class="btn btn-danger btn-order" data-id="{proid}" onclick="cartorder_detail(this, {POPUP}, 0); return !1;">
                        <em class="fa fa-shopping-cart fa-lg">&nbsp;</em> {LANG.add_cart}
                    </button>
                    <button class="btn btn-success btn-order" data-id="{proid}" onclick="cartorder_detail(this, {POPUP}, 1); return !1;">
                        <em class="fa fa-paper-plane-o fa-lg">&nbsp;</em> {LANG.buy_now}
                    </button>
                    <!-- END: order -->
                    <!-- BEGIN: product_empty -->
                    <button class="btn btn-danger disabled">{LANG.product_empty}</button>
                    <!-- END: product_empty -->
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: product_detail -->
    <!-- BEGIN: tabs -->
    <div role="tabpanel" class="tabs">
        <ul class="nav nav-tabs" role="tablist">
            <!-- BEGIN: tabs_title -->
            <li role="presentation"
                <!-- BEGIN: active -->class="active"<!-- END: active -->> <a href="#{TABS_KEY}-{TABS_ID}" aria-controls="{TABS_KEY}-{TABS_ID}" role="tab" data-toggle="tab"> <!-- BEGIN: icon --> <img src="{TABS_ICON}" /> <!-- END: icon --> <!-- BEGIN: icon_default --> <em class="fa fa-bars">&nbsp;</em> <!-- END: icon_default --> <span>{TABS_TITLE}</span>
            </a>
            </li>
            <!-- END: tabs_title -->
        </ul>
        <div class="tab-content">
            <!-- BEGIN: tabs_content -->
            <div role="tabpanel" class="tab-pane fade <!-- BEGIN: active -->active in<!-- END: active -->" id="{TABS_KEY}-{TABS_ID}">{TABS_CONTENT}</div>
            <!-- END: tabs_content -->
        </div>
    </div>
    <!-- END: tabs -->
    <!-- BEGIN: keywords -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="keywords">
                <em class="fa fa-tags">&nbsp;</em><strong>{LANG.keywords}: </strong>
                <!-- BEGIN: loop -->
                <a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}
                <!-- END: loop -->
            </div>
        </div>
    </div>
    <!-- END: keywords -->
    <!-- BEGIN: other -->
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.detail_others}</div>
        <div class="panel-body">{OTHER}</div>
    </div>
    <!-- END: other -->
    <!-- BEGIN: other_view -->
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.detail_others_view}</div>
        <div class="panel-body">{OTHER_VIEW}</div>
    </div>
    <!-- END: other_view -->
    <!-- END: product_detail -->
</div>
<div class="modal fade" id="idmodals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                &nbsp;
            </div>
            <div class="modal-body">
                <p class="text-center">
                    <em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN: allowed_print_js -->
<script type="text/javascript" data-show="after">
	$(function() {
		$('#click_print').click(function(event) {
			var href = $(this).attr("href");
			event.preventDefault();
			nv_open_browse(href, '', 640, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
			return false;
		});
	});
</script>
<!-- END: allowed_print_js -->
<!-- BEGIN: imagemodal -->
<script type="text/javascript" data-show="after">
	$('.open_modal').click(function(e){
		e.preventDefault();
 		$('#idmodals .modal-body').html( '<img src="' + $(this).data('src') + '" alt="" class="img-responsive" />' );
 		$('#idmodals').modal('show');
	});
</script>
<!-- END: imagemodal -->
<!-- BEGIN: order_number_limit -->
<script type="text/javascript" data-show="after">
	$('#pnum').attr( 'max', '{PRODUCT_NUMBER}' );
	$('#pnum').change(function(){
		if( intval($(this).val()) > intval($(this).attr('max')) ){
			alert('{LANG.detail_error_number} ' + $(this).attr('max') );
			$(this).val( $(this).attr('max') );
		}
	});
</script>
<!-- END: order_number_limit -->
<script type="text/javascript">
	var detail_error_group = '{LANG.detail_error_group}';
	function check_quantity( _this ){
		$('input[name="'+_this.attr('name')+'"]').parent().css('border-color', '#ccc');
		if( _this.is(':checked') ) {
		    _this.parent().css('border-color', 'blue');
		}
		$('#group_error').css( 'display', 'none' );
		<!-- BEGIN: check_price -->
		check_price( '{proid}', '{pro_unit}' );
		<!-- END: check_price -->
        resize_popup();
	}
</script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/lightSlider/js/lightslider.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:5,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'left',
        onSliderLoad: function(el) {
            //el.lightGallery({
            //    selector: '#imageGallery .lslide'
            //});
        }
    });
  });
</script>
<!-- BEGIN: popup -->
<script type="text/javascript">
$(window).on('load', function() {
    resize_popup();
});
</script>
<!-- END: popup -->
<!-- END: main -->