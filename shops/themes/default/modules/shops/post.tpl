<!-- BEGIN: main -->
<form action="" name="fac" method="post" id="fac" enctype="multipart/form-data">
	<input type="hidden" value="1" name="save" />
	<div class="post-product clearfix">
		<h2>{LANG.product_post_title}</h2>
		<!-- BEGIN: error -->
		<div class="post-error"><span>{LANG.post_err} : </span>{info}</div>
		<!-- END: error -->
		<div class="group_f">
			<div class="title_group"><span class="info-require fr">{LANG.product_post_info}</span>{LANG.product_info}</div>	
			<div class="b_content form_x">
				<p>
					<strong>{LANG.product_title}</strong> <span class="span-require">(*)</span><br />
					<input type="text" value="{DATA.title}" class="input txt-full" name="title" />
				</p>
				<p>
					<strong>{LANG.product_alias}</strong><br />
					<input type="text" value="{DATA.alias}" class="input txt-full" name="alias" />
				</p>
				<div class="clearfix">
					<p class="fl">
						<strong>{LANG.detail_address}</strong>
						<br />
						<input type="text" value="{DATA.address}" class="input txt-full" name="address" />
					</p>
					<p class="fl">
						<strong>{LANG.product_catalogs}</strong>
						<br />
						<select class="input" name="catalogs">
							<!-- BEGIN: loop_cata -->
							<option value="{catid}" {select} {disabled}>{xtitle}{title}</option>
							<!-- END: loop_cata -->
						</select> <span class="span-require">(*)</span>
					</p>
				</div>
				<p>
					<strong>{LANG.product_image}</strong><br />
					<!-- BEGIN: imgpro -->
					<img src="{img_pro}" width="90px" height="70px" style="margin-right:10px; border:1px solid #F3F3F3; padding:2px;" alt="image" class="fl" />
					<!-- END: imgpro -->
					<input type="file" style="width:400px" name="homeimg" id="homeimg" class="fl" />
					<div class="clear"></div>
				</p>
				<p>
					<strong>{LANG.product_intro}</strong> <span class="span-require">(*)</span>
					<textarea rows="3" class="input txt-full" name="hometext">{DATA.hometext}</textarea>
				</p>
				<p>
					<strong>{LANG.product_detail}</strong> <span class="span-require">(*)</span><br />
					{NV_EDITOR}
				</p>
			</div>	
		</div>
		<div class="group_f">
			<div class="title_group"><span class="info-require fr">{LANG.product_post_info}</span>{LANG.product_sale_info}</div>	
			<div class="clearfix">
				<p class="fl">
					<strong>{LANG.detail_pro_price}</strong>
					<br />
					<input type="text" class="input"  value="{DATA.product_price}" name="product_price" />
				</p>
				<p class="fl">
					<strong>{LANG.product_unit_price}</strong>
					<br />
					<select class="input" name="money_unit">
						<!-- BEGIN: money_unit -->
						<option value="{MON.code}" {MON.select}>{MON.currency}</option>
						<!-- END: money_unit -->
					</select>
				</p>
				<p class="fl">
					<strong>{LANG.product_unit}</strong>
					<br />
					<select class="input" name="product_unit">
						<!-- BEGIN: loop_product_unit -->
						<option value="{unitid}" {select}>{utitle}</option>
						<!-- END: loop_product_unit -->
					</select>
				</p>
			</div>
			<div class="clearfix">
				<p class="fl">
					<strong>{LANG.product_number}</strong>
					<br />
					<input type="text" class="input" value="{DATA.product_number}" name="product_number" />
				</p>
				<p class="fl">
					<strong>{LANG.product_status}</strong>
					<br />
					<input type="text" class="input" value="{DATA.pstatus}" name="pstatus"  />
				</p>
			</div>
			<p>
				<strong>{LANG.product_payment_form}</strong><br />
				<input type="text" value="{DATA.payment}" class="input txt-full" name="payment" />
			</p>
			<p>
				<strong>{LANG.product_move_form}</strong><br />
				<input type="text" value="{DATA.move}" class="input txt-full" name="move" />
			</p>
			<p>
				<strong>{LANG.detail_exptime}</strong><br />
				<input type="text" value="{DATA.exp_date}" class="input" name="exp_date" id="exp_date" maxlength="10" readonly="readonly"/> 
				<img src="{NV_BASE_SITEURL}images/calendar.jpg" alt="" class="refresh show-date" onclick="popCalendar.show(this, 'exp_date', 'dd.mm.yyyy', false);"/>
			</p>
			<p>
				<strong>{LANG.product_keywords}</strong><br />
				<input type="text" name="keywords" class="input txt-full" value="{DATA.keywords}"/>
			</p>
		</div>
		<div class="fr">
			<input type="submit" class="button" value="{LANG.lang_post}"/>
		</div>
	</div>
</form>
<!-- END: main -->