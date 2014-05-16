<!-- BEGIN: main -->
<div id="detail">
	<div>
		<span class="image-demo"> <a href="{SRC_PRO_LAGE}" title="{TITLE}" rel="shadowbox"> <img src="{SRC_PRO}" alt="" width="140px" style="border:1px solid #eeeeee; padding:2px"> </a>
			<br />
			<a href="{SRC_PRO_LAGE}" title="{TITLE}" rel="shadowbox[miss]"><span class="zoom_img">{LANG.detail_view_lage_img}</span></a>
			<!-- BEGIN: adminlink -->
			<div class="fl">
				{ADMINLINK}
			</div>
			<!-- END: adminlink -->
		</span>
		<div class="info_product">
			<h2>{TITLE}</h2>
			<span class="date_up">{DATE_UP} - {NUM_VIEW} {LANG.detail_num_view}</span>
			<!-- BEGIN: product_code -->
			{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
			<br />
			<!-- END: product_code -->
			<!-- BEGIN: price -->
			<p>
				{LANG.detail_pro_price}: <span class="{class_money}">{product_price} {money_unit}</span>
				<!-- BEGIN: discounts -->
				<span class="money">{product_discounts} {money_unit}</span>
				<!-- END: discounts -->
				/ 1 {pro_unit}
			</p>
			<!-- END: price -->
			<!-- BEGIN: contact -->
			{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
			<!-- END: contact -->
			<!-- BEGIN: hometext -->
			<p>
				{hometext}
			</p>
			<!-- END: hometext -->
			<!-- BEGIN: source -->
			<div>
				{LANG.detail_source} : <a href="{link_source}">{source}</a>
			</div>
			<!-- END: source -->
			<!-- BEGIN: promotional -->
			<div>
				{LANG.detail_promotional} : {promotional}
			</div>
			<!-- END: promotional -->
			<!-- BEGIN: warranty -->
			<div>
				{LANG.detail_warranty} : {warranty}
			</div>
			<!-- END: warranty -->
			<!-- BEGIN: address -->
			<div>
				{LANG.detail_product_address} : {address}
			</div>
			<!-- END: address -->
			<!-- BEGIN: note -->
			<div>
				{LANG.cart_note} : {note}
			</div>
			<!-- END: note -->
			<div id="ratedata">
                <!-- BEGIN: allowed_rating -->
            	<div class="clearfix">
                    <span class="rateavg_percent">{LANG.rateavg_percent}: {RATE_AVG_PERCENT}</span>
            		<div class="header-oop icon-rating">
            			{LANG.detail_rank}
            		</div>
            		<form id="form3B" action="">
            			<div class="clearfix">
            				<div id="stringrating" class="small">
            					{STRINGRATING}
            				</div>
        					<div class="rate-star">
                                <div class="width-star-title">{LANG.5star}</div>
                                <div class="width-star-bg" value-data="5">
                                    <input type="hidden" name="valuerate" value="5" />
                                    <div class="width-star-value" title="{PERCENT_RATE.5}%" style="width:{PERCENT_RATE.5}%">&nbsp;</div>
                                </div>
                                <div class="width-star-num">{RATINGDETAIL.5}</div>
                            </div>
                            <div class="rate-star">
                                <div class="width-star-title">{LANG.4star}</div>
                                <div class="width-star-bg" value-data="4">
                                    <input type="hidden" name="valuerate" value="4" />
                                    <div class="width-star-value" title="{PERCENT_RATE.4}%" style="width:{PERCENT_RATE.4}%">&nbsp;</div>
                                </div>
                                <div class="width-star-num">{RATINGDETAIL.4}</div>
                            </div>
                            <div class="rate-star">
                                <div class="width-star-title">{LANG.3star}</div>
                                <div class="width-star-bg" value-data="3">
                                    <input type="hidden" name="valuerate" value="3" />
                                    <div class="width-star-value" title="{PERCENT_RATE.3}%" style="width:{PERCENT_RATE.3}%">&nbsp;</div>
                                </div>
                                <div class="width-star-num">{RATINGDETAIL.3}</div>
                            </div>
                            <div class="rate-star">
                                <div class="width-star-title">{LANG.2star}</div>
                                <div class="width-star-bg" value-data="2">
                                    <input type="hidden" name="valuerate" value="2" />
                                    <div class="width-star-value" title="{PERCENT_RATE.2}%" style="width:{PERCENT_RATE.2}%">&nbsp;</div>
                                </div>
                                <div class="width-star-num">{RATINGDETAIL.2}</div>
                            </div>
                            <div class="rate-star">
                                <div class="width-star-title">{LANG.1star}</div>
                                <div class="width-star-bg" value-data="1">
                                    <input type="hidden" name="valuerate" value="1" />
                                    <div class="width-star-value" title="{PERCENT_RATE.1}%" style="width:{PERCENT_RATE.1}%">&nbsp;</div>
                                </div>
                                <div class="width-star-num">{RATINGDETAIL.1}</div>
                            </div>
            			</div>
            		</form>
                </div>
                <!-- END: allowed_rating -->
            </div>
				<div class="clearfix fl" style="width:170px; padding:6px 0px">
					<strong class="fl">{LANG.detail_share} : </strong>
					<span class="share clearfix">
						<!-- BEGIN: allowed_print -->
						<a rel="nofollow" href="{LINK_PRINT}" title="print" id="click_print"> <img border="0" alt="print" src="{THEME_URL}/images/shops/print.png"> </a>
						<!-- END: allowed_print -->
						<a onclick="share_facebook();" href="javascript:;" title="Share on Facebook"> <img border="0" alt="Share on Facebook" src="{THEME_URL}/images/shops/flickr.png"> </a> <a onclick="share_twitter();" href="javascript:;" title="Share on Twitter"> <img border="0" alt="Share on Twitter" src="{THEME_URL}/images/shops/twitter.png"> </a> <a onclick="share_google();" href="javascript:;" title="Share on Google"> <img border="0" alt="Share on Google" src="{THEME_URL}/images/shops/google.png"> </a> <a onclick="share_buzz();" href="javascript:;" title="Share on Buzz"> <img border="0" alt="Share on Buzz" src="{THEME_URL}/images/shops/buzz.png"> </a> </span>
				</div>
				<!-- BEGIN: order -->
				<div class="clearfix fr" style="width:170px; padding:6px 0px">
					<span class="fl" style="line-height:22px;">{LANG.title_order} : &nbsp;</span>
					<input type="text" name="num" value="1" style="width:30px; height:15px" id="pnum" class="fl">
					<a href="javascript:void(0)" id="{proid}" title="{title_pro}" class="pro_order fl" onclick="cartorder_detail(this)"> {LANG.add_product} </a>
				</div>
				<!-- END: order -->
				<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
		<div style="detail_com">
			<!-- BEGIN: shop -->
			{LANG.company_product} : <a href="{link_shop}" title="{title_shop}">{title_shop}</a>
			<!-- END: shop -->
		</div>
		<div class="TabView" id="TabView">
			<div class="Tabs">
				<a href="#">{LANG.product_detail}</a>
				<a href="#">{LANG.add_otherimage}</a>
				<!-- BEGIN: comment_tab -->
				<a href="#">{LANG.detail_comments}</a>
				<!-- END: comment_tab -->
			</div>
			<div class="Pages">
				<div class="Page">
					{DETAIL}
				</div>
				<div class="Page" align="center">
					<div class="clearfix">
						<!-- BEGIN: othersimg -->
						<div style="width:33%; float:left; text-align:center">
							<a href="{IMG_SRC_OTHER}" rel="shadowbox[miss]"><img src="{IMG_SRC_OTHER}" style="max-width:100%" height="140px"/></a>
						</div>
						<!-- END: othersimg -->
						<!-- BEGIN: no_otherimage -->
						{LANG.detail_no_otherimage}
						<!-- END: no_otherimage -->
					</div>
				</div>
				<!-- BEGIN: comment -->
				<div class="Page">
					<iframe src="{NV_COMM_URL}" onload = "nv_setIframeHeight( this.id )" style="width: 100%; min-height: 300px; max-height: 1000px"></iframe>
				</div>
				<!-- END: comment -->
			</div>
		</div>
	</div>
	<!-- BEGIN: other -->
	<h1 class="divbg">{LANG.detail_others}</h1>
	{OTHER}
	<!-- END: other -->
	<!-- BEGIN: other_view -->
	<h1 class="divbg">{LANG.detail_others_view}</h1>
	{OTHER_VIEW}
	<!-- END: other_view -->
</div>
<div class="msgshow" id="msgshow"></div>
<script language="javascript" type="text/javascript">tabview_initialize('TabView');</script>
<script type="text/javascript">
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
});
</script>
<!-- END: main -->