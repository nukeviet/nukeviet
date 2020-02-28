<!-- BEGIN: main -->

<!-- BEGIN: rate_empty -->
<div class="alert alert-info">
    {EMPTY}
</div>
<!-- END: rate_empty -->

<!-- BEGIN: rate_data -->
<div class="row">
    <!-- BEGIN: loop -->
    <div class="review_row" itemprop="review" itemtype="http://schema.org/Review" itemscope>
        <div class="col-xs-10" itemprop="author" itemtype="http://schema.org/Person" itemscope>
            <strong itemprop="name">{DATA.sender}</strong><span class="help-block">{DATA.add_time}</span>
        </div>
        <div class="col-xs-14">
            <div class="clearfix" itemprop="reviewRating" itemtype="http://schema.org/Rating" itemscope>
                <span class="hidden d-none hide" itemprop="ratingValue">{DATA.rating}</span>
                <span class="hidden d-none hide" itemprop="bestRating">5</span>
                <!-- BEGIN: star -->
                <div class="star-icon">&nbsp;</div>
                <!-- END: star -->
            </div>
            <!-- BEGIN: content -->
            <em class="help-block">"{DATA.content}"</em>
            <!-- END: content -->
        </div>
        <div class="clear"></div>
    </div>
    <!-- END: loop -->

    <!-- BEGIN: generate_page -->
    <div class="text-right pagination-sm">
        {PAGE}
    </div>
    <!-- END: generate_page -->
</div>
<!-- END: rate_data -->

<!-- END: results -->
