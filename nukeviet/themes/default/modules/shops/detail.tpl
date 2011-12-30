<!-- BEGIN: main -->
<div id="detail">
    <div>
        <span style="float:left; text-align:center">
            <a href="{SRC_PRO_LAGE}" title="{TITLE}" rel="shadowbox">
                <img src="{SRC_PRO}" alt="" width="140px" style="border:1px solid #eeeeee; padding:2px">
            </a>
            <br />
            <a href="{SRC_PRO_LAGE}" title="{TITLE}" rel="shadowbox[miss]"><span class="zoom_img">{LANG.detail_view_lage_img}</span></a>
        </span>
        <div class="info_product fl">
            <h2>{TITLE}</h2>
            <!-- BEGIN: product_code -->
             <b>{LANG.product_code}: {PRODUCT_CODE}</b>
            <!-- END: product_code -->
            <span class="date_up">{DATE_UP} - {NUM_VIEW} {LANG.detail_num_view}</span>
            <!-- BEGIN: price -->
            <p>
            	{LANG.detail_pro_price} : <span class="{class_money}">{product_price} {money_unit}</span>
                <!-- BEGIN: discounts -->
				<span class="money">{product_discounts} {money_unit}</span>
				<!-- END: discounts --> / 1 {pro_unit}
            </p>
            <!-- END: price -->
            <!-- BEGIN: contact -->
            {LANG.detail_pro_price} : <span class="money">{LANG.price_contact}</span>
            <!-- END: contact -->
            <!-- BEGIN: hometext -->
            <p>
            	{hometext}
            </p>	
            <!-- END: hometext -->
            <p>
            	<span>{LANG.detail_rank} : <span class="math_rate">{RATE}</span> {LANG.detail_rate_math}</span>
                <span class="div_rate">
                    <a href="#" class="rate">1</a>
                    <a href="#" class="rate">2</a>
                    <a href="#" class="rate">3</a>
                    <a href="#" class="rate">4</a>
                    <a href="#" class="rate">5</a>
                </span>
            <p>
            <div class="clearfix fl" style="width:170px">
            	<strong class="fl">{LANG.detail_share} : </strong>
                <span class="share clearfix">
					<a href="{LINK_PRINT}" title="print" id="click_print">
						<img border="0" alt="print" src="{THEME_URL}/images/shops/print.png">
					</a>
					<a onclick="share_facebook();" href="javascript:;" title="Share on Facebook">
						<img border="0" alt="Share on Facebook" src="{THEME_URL}/images/shops/flickr.png">
					</a>
					<a onclick="share_twitter();" href="javascript:;" title="Share on Twitter">
						<img border="0" alt="Share on Twitter" src="{THEME_URL}/images/shops/twitter.png">
					</a>
					<a onclick="share_google();" href="javascript:;" title="Share on Google">
						<img border="0" alt="Share on Google" src="{THEME_URL}/images/shops/google.png">
					</a>
					<a onclick="share_buzz();" href="javascript:;" title="Share on Buzz">
						<img border="0" alt="Share on Buzz" src="{THEME_URL}/images/shops/buzz.png">
					</a>
				</span>
            </div>
            <!-- BEGIN: order -->
            <div class="clearfix fr" style="width:170px">
                <!-- BEGIN: num -->
                <span class="fl" style="line-height:22px;">{LANG.title_order} : &nbsp;</span> 
                <input type="text" name="num" value="1" style="width:30px; height:15px" id="pnum" class="fl">
                <!-- END: num -->
                <a href="javascript:void(0)" id="{proid}" title="{title_pro}" class="pro_order fl" onclick="cartorder_detail(this)">
                	{LANG.add_product}
                </a>
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
	            <a href="#">{LANG.detail_comments}</a>
	        </div>
	        <div class="Pages">
	            <div class="Page">
	                {DETAIL}
	            </div>
	            <div class="Page">
	                <!-- BEGIN: comment -->
				    <div class="prd_rate">
				        <form class="comment" action="">
				            <input type="hidden" value="{proid}" name="proid" id="proid" />
				            <fieldset>
				                <span id="charlimitinfo">{LANG.comment_limit_characters}</span>
				                <textarea id="comment" rows="5" name="comment" style="width:90%"></textarea>
				                <div class="fl clearfix">
				                    <label for="captcha">
				                        {LANG.comment_capcha}
				                    </label>
									<img height="20" name="vimg" src="{NV_BASE_SITEURL}?scaptcha=captcha" title="{LANG.captcha}" alt="{LANG.captcha}" id="vimg" />
									<input type="text" maxlength="6" value="" id="fcode_iavim" name="fcode" style="width:60px"/>
									<input type="button" value="Reset" class="button" onclick="nv_change_captcha('vimg','fcode_iavim');" style="margin-right:5px" />
				                </div>
				                <div>
				                    <input type="button" value="{LANG.comment_send}" id="submit" class="button"/>
				                </div>
				            </fieldset>
				        </form>
						<!-- BEGIN: list -->
				        <div class="cm_rows clearfix">
				            <div class="cm_u fl">
				                <a title="{LANG.comment_user_view_info} {username}" href="#"><strong>{username}</strong></a>
				                <span class="date">{date_up}</span>
				            </div>
				            <div class="fr">
				                <a title="Like" href="#" class="like_off">&nbsp;</a>
				            </div>
				            <div class="clear"></div>
				            <p>
				                {content}
				            </p>
				        </div>
				        <!-- END: list -->
				    </div>
                    <!-- END: comment -->
	            </div>
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
<script language="javascript" type="text/javascript">
	tabview_initialize('TabView');
</script>
<script type="text/javascript">
    $(function(){
        $("#submit").click(function(event){
            event.preventDefault();
            var comment = $('#comment').val();
            var fcode_iavim = $('#fcode_iavim').val();
            var id = $('#proid').val();
            $.ajax({
                type: "POST",
                url: '{link_addcomment}',
                data: 'id=' + id + '&content=' + comment + '&code=' + fcode_iavim,
                success: function(data){
                    var s = data.split('_');
                    if (s[0] == 'ERR') {
                        alert(s[1]);
                        nv_change_captcha('vimg', 'fcode_iavim');
                    }
                    if (s[0] == 'OK') {
                        $("#comment").val('');
                        alert(s[1]);
                    }
                }
            });
            return false;
        });
        $('#click_print').click(function(event){
            var href = $(this).attr("href");
            event.preventDefault();
            NewWindow(href, '', '640', '300', 'yes');
            return false;
        });
        $("a.rate").click(function(event){
            event.preventDefault();
            var val = $(this).html();
            $.ajax({
                type: "POST",
                url: '{LINK_RATE}'+'&nocache=' + new Date().getTime(),
                data: 'val=' + val,
                success: function(data){
                   	var s = data.split('_');
                    alert(s[1]);
                }
            });
            return false;
        });
    });
</script>
<!-- END: main -->
