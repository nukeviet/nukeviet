<!-- BEGIN: main -->
<link rel="stylesheet" href="{THEME_TEM}/images/{MODULE_FILE}/tiny-slider/tiny-slider.css">
<script type="text/javascript" src="{THEME_TEM}/images/{MODULE_FILE}/tiny-slider/min/tiny-slider.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var slider = tns({
        container: '#product_center_{BLOCKID}',
        items: {NUMVIEW},
        slideBy: 'page',
        autoplay: true,
        gutter: 10,
        center: false,
        nav: false,
        autoplayHoverPause: true,
        autoplayButtonOutput: false,
        responsive: {
            0: {
                items: 2
            },
            767: {
                items: 3
            },
            992: {
                items: {NUMVIEW}
            }
        },
        mouseDrag: true,
        prevButton: '#product_center_{BLOCKID}_prev',
        nextButton: '#product_center_{BLOCKID}_next',
        controlsContainer: '#product_center_{BLOCKID}_ctrs'
    });
});
</script>
<div class="product_center_wrap">
    <div id="product_center_{BLOCKID}" class="product_center_slide">
        <!-- BEGIN: items -->
        <div class="items">
            <a href="{LINK}" title="{TITLE}" class="img"><img src="{SRC_IMG}" width="{WIDTH}" alt="{TITLE}" class="thumbnail" /></a>
            <!-- BEGIN: price -->
            <span class="price">
                <!-- BEGIN: discounts -->
                <span class="money show">{PRICE.sale_format} {PRICE.unit}</span>
                <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
                <!-- END: discounts -->

                <!-- BEGIN: no_discounts -->
                <span class="money">{PRICE.price_format} {PRICE.unit}</span>
                <!-- END: no_discounts -->
            </span>
            <!-- END: price -->
            <!-- BEGIN: contact -->
            <span class="money">{LANG.price_contact}</span>
            <!-- END: contact -->
            <p><a href="{LINK}" title="{TITLE}">{TITLE0}</a></p>
        </div>
        <!-- END: items -->
    </div>
    <ul class="controls" id="product_center_{BLOCKID}_ctrs">
        <li class="prev-button"><span id="product_center_{BLOCKID}_prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></span></li>
        <li class="next-button"><span id="product_center_{BLOCKID}_next"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></li>
    </ul>
</div>
<!-- END: main -->
