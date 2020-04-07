<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/images/{MODULE_FILE}/jquery.ez-plus.js"></script>
<div id="detail" class="product-detail <!-- BEGIN: popupid -->prodetail-popup<!-- END: popupid -->" itemtype="http://schema.org/Product" itemscope>
    <span class="d-none hidden hide" itemprop="mpn" content="{PRODUCT_CODE}"></span>
    <span class="d-none hidden hide" itemprop="sku" content="{PRODUCT_CODE}"></span>
    <div class="d-none hidden hide" itemprop="brand" itemtype="http://schema.org/Thing" itemscope>
        <span itemprop="name">N/A</span>
    </div>
    <!-- BEGIN: allowed_rating_snippets -->
    <div class="d-none hidden hide" itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>
        <span itemprop="reviewCount">{RATE_TOTAL}</span>
        <span itemprop="ratingValue">{RATE_VALUE}</span>
    </div>
    <!-- END: allowed_rating_snippets -->
    <div class="d-none hidden hide" itemprop="offers" itemtype="http://schema.org/Offer" itemscope>
        <!-- BEGIN: price1 -->
        <span itemprop="price">{PRICE.sale}</span>
        <span itemprop="priceCurrency">{PRICE.unit}</span>
        <!-- END: price1 -->
        <a itemprop="url" href="{PRO_FULL_LINK}"></a>
        <span itemprop="priceValidUntil">{PRICEVALIDUNTIL}</span>
        <span itemprop="availability">{AVAILABILITY}</span>
    </div>
    <div class="panel panel-default panel-product-info">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-24 col-sm-10 col-md-10 text-center">
                    <!-- BEGIN: oneimage -->
                    <div class="product-one-image mb-2">
                        <img itemprop="image" src="{IMAGE.file}" alt="{DATA.homeimgalt}" id="product-image-one-view">
                    </div>
                    <script type="text/javascript">
                    $(document).ready(function() {
                        $('#product-image-one-view').ezPlus();
                    });
                    </script>
                    <!-- END: oneimage -->
                    <!-- BEGIN: image -->
                    <div class="product-image-gallery mb-2">
                        <div class="gallery-view">
                            <div class="gallery-view-inner owl-carousel" id="product-image-gallery-view">
                                <!-- BEGIN: loop -->
                                <div class="item" data-item="{IMAGE_STT}">
                                    <div class="item-inner">
                                        <img itemprop="image" src="{IMAGE.file}" alt="{DATA.homeimgalt}" data-zoom-image="{IMAGE.file}">
                                    </div>
                                </div>
                                <!-- END: loop -->
                            </div>
                        </div>
                        <div class="gallery-nav owl-carousel" id="product-image-gallery-nav">
                            <!-- BEGIN: loop1 -->
                            <div class="item<!-- BEGIN: active --> active<!-- END: active -->" data-item="{IMAGE_STT}">
                                <div class="item-inner">
                                    <a href="#" class="item-click-change" data-offset="{IMAGE_STT}"><img src="{IMAGE.thumb}" alt="{DATA.homeimgalt} thumb"></a>
                                </div>
                            </div>
                            <!-- END: loop1 -->
                        </div>
                    </div>
                    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/images/{MODULE_FILE}/OwlCarousel2/assets/owl.carousel.min.css">
                    <script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/images/{MODULE_FILE}/OwlCarousel2/owl.carousel.min.js"></script>
                    <script type="text/javascript">
                    $(document).ready(function() {
                        // Slide ảnh sản phẩm
                        var owlView = $('#product-image-gallery-view');
                        var owlNav = $('#product-image-gallery-nav');
                        owlView.owlCarousel({
                            items: 1,
                            nav: true,
                            navText: ['<span><i class="fa fa-angle-left" aria-hidden="true"></i></span>', '<span><i class="fa fa-angle-right" aria-hidden="true"></i></span>'],
                            dots: false,
                            lazyLoad: true,
                            autoplay: false,
                            margin: 5
                        });
                        owlNav.owlCarousel({
                            nav: false,
                            dots: false,
                            autoplay: false,
                            items: 5,
                            margin: 5
                        });
                        owlView.on('changed.owl.carousel', function(e) {
                            $('.item', owlNav).removeClass('active');
                            $('[data-item="' + e.item.index + '"]', owlNav).addClass('active');
                            $('[data-item="' + e.item.index + '"]', owlView).find('img').ezPlus({
                                zIndex: 9,
                                zoomContainerAppendTo: owlView
                            });
                        });
                        $('.item-click-change', owlNav).on('click', function(e) {
                            e.preventDefault();
                            owlView.trigger('to.owl.carousel', [$(this).data('offset'), 300]);
                        });
                        // Zoom ảnh đầu tiên
                        $('[data-item="0"]', owlView).find('img').ezPlus({
                            zIndex: 9,
                            zoomContainerAppendTo: owlView
                        });
                    });
                    </script>
                    <!-- END: image -->
                    <!-- BEGIN: adminlink -->
                    <div class="admin-links margin-bottom mb-2">{ADMINLINK}</div>
                    <!-- END: adminlink -->
                    <!-- BEGIN: social_icon -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="socialicon">
                                <div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">&nbsp;</div>
                                <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
                            </div>
                        </div>
                    </div>
                    <!-- END: social_icon -->
                </div>
                <div class="col-xs-24 col-sm-14 col-md-14">
                    <ul class="product_info">
                        <li><h1 itemprop="name">{TITLE}</h1></li>
                        <li class="text-muted">{DATE_UP} - {NUM_VIEW} {LANG.detail_num_view}</li>
                        <!-- BEGIN: product_code -->
                        <li>{LANG.product_code}: <strong>{PRODUCT_CODE}</strong></li>
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
                            <p class="text-justify" itemprop="description">{hometext}</p>
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
    <div class="panel panel-default panel-product-keywords">
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
    <div class="panel panel-default panel-product-others">
        <div class="panel-heading">{LANG.detail_others}</div>
        <div class="panel-body">{OTHER}</div>
    </div>
    <!-- END: other -->
    <!-- BEGIN: other_view -->
    <div class="panel panel-default panel-product-viewed">
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
<!-- BEGIN: popup -->
<script type="text/javascript">
$(window).on('load', function() {
    resize_popup();
});
</script>
<!-- END: popup -->
<!-- END: main -->
