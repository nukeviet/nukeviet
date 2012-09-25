<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}js/jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/popcalendar/popcalendar.js"></script>
<form action="" name="fac" method="post" id="fac" enctype="multipart/form-data">
<input type="hidden" value="1" name="save" />
<input type="hidden" value="{shopid}" name="shopid" />
<div class="block clearfix">
    <h1 class="title_page_2">{LANG.product_post_title}</h1>
    <div class="b_content">
    	<!-- BEGIN: error -->
    	<div style="border:1px solid #FFAAD5;width:848px;margin:0 auto 10px; background:#FFD2E9; padding:10px">
        <span style="color:#F00; font-weight:bold;">{LANG.post_err} : </span>{info}</div>
        <!-- END: error -->
        <div class="group_f">
            <div class="title_group"><span class="desc_txt fr">{LANG.product_post_info}</span>{LANG.product_info}</div>	
            <div class="b_content form_x">
                <p><strong>{LANG.product_title} (*)</strong></p>
                <p>
                    <input type="text" value="{DATA.title}" style="width:600px" name="title" />
                </p>
				<p><strong>{LANG.product_alias} (*)</strong></p>
                <p>
                    <input type="text" value="{DATA.alias}" style="width:600px" name="alias" />
                </p>
                <div class="div3 clearfix">
                    <p class="fl">
                        <strong>{LANG.detail_address}</strong>
                        <br />
                        <input type="text" value="{DATA.address}" class="txt2" name="address" />
                    </p>
                    <p class="fl">
                        <strong>{LANG.product_catalogs}</strong>
                        <br />
                        <select class="txt2" name="catalogs">
                            <!-- BEGIN: loop_cata -->
                            <option value="{catid}" {select} {disabled}>{xtitle}{title}</option>
                            <!-- END: loop_cata -->
                        </select> (*)
                    </p>
                </div>
                
                <p><strong>{LANG.product_image}</strong></p>
                <p>
                    <!-- BEGIN: imgpro -->
                    <img src="{img_pro}" width="90px" height="70px" style="margin-right:10px; border:1px solid #F3F3F3; padding:2px;" alt="image" class="fl" />
                    <!-- END: imgpro -->
					<input type="file" style="width:400px" name="homeimg" id="homeimg" class="fl" />
					<div style="clear:both;"></div>
                </p>
                <p><strong>{LANG.product_intro} (*)</strong></p>
                <p>
                    <textarea rows="3" style="width:810px" name="hometext">{DATA.hometext}</textarea>
                </p>
                <p><strong>{LANG.product_detail} (*)</strong></p>
                <p>
                    {NV_EDITOR}
                </p>
            </div>	
        </div>
        <div class="group_f">
            <div class="title_group"><span class="desc_txt fr">{LANG.product_post_info}</span>{LANG.product_sale_info}</div>	
            <div class="b_content form_x">
                <div class="div5 clearfix">
                    <p class="fl">
                        <strong>{LANG.detail_pro_price}</strong>
                        <br />
                        <input type="text" class="txt5"  value="{DATA.product_price}" name="product_price" />
                    </p>
                    <p class="fl">
                        <strong>{LANG.product_unit_price}</strong>
                        <br />
                        <select name="money_unit">
                        	<!-- BEGIN: money_unit -->
                            <option value="{MON.code}" {MON.select}>{MON.currency}</option>
                            <!-- END: money_unit -->
                        </select>
                    </p>
                    <p class="fl">
                        <strong>{LANG.product_unit}</strong>
                        <br />
                        <select class="txt5" name="product_unit">
                            <!-- BEGIN: loop_product_unit -->
                            <option value="{unitid}" {select}>{utitle}</option>
                            <!-- END: loop_product_unit -->
                        </select>
                    </p>
                    <p class="fl">
                        <strong>{LANG.product_number}</strong>
                        <br />
                        <input type="text" class="txt5" value="{DATA.product_number}" name="product_number" />
                    </p>
                    <p class="fl mm">
                        <strong>{LANG.product_status}</strong>
                        <br />
                        <input type="text" class="txt6" value="{DATA.pstatus}" name="pstatus"  />
                    </p>
                </div>
                <p><strong>{LANG.product_payment_form}</strong></p>
                <p>
                    <input type="text" value="{DATA.payment}" class="txt1" name="payment" />
                </p>
                <p><strong>{LANG.product_move_form}</strong></p>
                <p>
                    <input type="text" value="{DATA.move}" class="txt1" name="move" />
                </p>
                <p><strong>{LANG.detail_exptime}</strong></p>
                <p>
                    <input type="text" value="{DATA.exp_date}" class="txt4" name="exp_date" id="exp_date" maxlength="10" readonly="readonly"/> 
                    <img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/calendar.png" alt="" class="img_c"  style="cursor:pointer" 
                    onclick="popCalendar.show(this, 'exp_date', 'dd.mm.yyyy', false);"/>
                </p>
                <p><strong>{LANG.product_keywords}</strong></p>
                <p>
                    <input type="text" name="keywords" class="txt1" value="{DATA.keywords}"/>
                </p>
            </div>	
        </div>
        <div class="group_f">
            <div class="d_post clearfix">
                <a href="#" class="bnt_post fr" id="post"><span>{LANG.lang_post}</span></a>						
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
	  $(function(){	  
		  $("#post").click(function(event){
			  event.preventDefault();	
			  $("#fac").removeAttr("target");
			  $("#fac").removeAttr("action");
			  $("#fac").submit();
			  return false;
		  });
		  $("#view").click(function(event){
			  event.preventDefault();	
			  $("#fac").attr("target","_blank");
			  $("#fac").attr("action","#");
			  $("#fac").submit();
			  return false;
		  });
	  });
  </script>
<!-- END: main -->