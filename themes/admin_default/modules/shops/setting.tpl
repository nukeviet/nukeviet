<!-- BEGIN: main -->
<form class="form-inline" action="" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.content_extra}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_active_order_active}</strong></td>
					<td><input type="checkbox" value="1" name="active_order" {ck_active_order} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_payment}</strong></td>
					<td><input type="checkbox" value="1" name="active_payment" {ck_active_payment} id="active_payment" /> {LANG.setting_active_payment_note} </td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_shipping}</strong></td>
					<td><input type="checkbox" value="1" name="use_shipping" {ck_shipping}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_coupons}</strong></td>
					<td><input type="checkbox" value="1" name="use_coupons" {ck_coupons}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_point_active}</strong></td>
					<td><input type="checkbox" name="point_active" value="1" {ck_active_point} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_wishlist}</strong></td>
					<td><input type="checkbox" value="1" name="active_wishlist" {ck_active_wishlist} id="active_wishlist" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_gift}</strong></td>
					<td><input type="checkbox" value="1" name="active_gift" {ck_active_gift} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_warehouse}</strong></td>
					<td><input type="checkbox" value="1" name="active_warehouse" {ck_active_warehouse} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.review_setting_active}</strong></td>
					<td><input type="checkbox" value="1" name="review_active" {ck_review_active}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.template_setting_active}</strong></td>
					<td><input type="checkbox" value="1" name="template_active" {ck_template_active}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.download_setting_active}</strong></td>
					<td><input type="checkbox" value="1" name="download_active" id="download_active" {ck_download_active}/></td>
				</tr>
				<tr id="download_groups" <!-- BEGIN: download_groups_none -->style="display: none"<!-- END: download_groups_none -->>
					<td><strong>{LANG.download_setting_groups}</strong></td>
					<td>
						<!-- BEGIN: download_groups -->
						<label class="show"><input name="download_groups[]" type="checkbox" value="{DOWNLOAD_GROUPS.value}" {DOWNLOAD_GROUPS.checked} />{DOWNLOAD_GROUPS.title}</label>
						<!-- END: download_groups -->
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.search_per_page}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_home_view}</strong></td>
					<td>
					<select class="form-control" name="home_view">
						<!-- BEGIN: home_view_loop -->
						<option value="{type_view}"{view_selected}>{name_view}</option>
						<!-- END: home_view_loop -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_homesite}</strong></td>
					<td><input class="form-control" type="text" value="{DATA.homewidth}" style="width: 60px;" name="homewidth" /><span class="text-middle"> x </span><input class="form-control" type="text" value="{DATA.homeheight}" style="width: 60px;" name="homeheight" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_per_page}</strong></td>
					<td><input class="form-control" type="text" value="{DATA.per_page}" style="width: 60px;" name="per_page" /><span class="text-middle"> {LANG.setting_per_note_home} </span></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_per_row}</strong></td>
					<td>
					<select class="form-control" name="per_row">
						<!-- BEGIN: per_row -->
						<option value="{PER_ROW.value}" {PER_ROW.selected}>{PER_ROW.value}</option>
						<!-- END: per_row -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_displays}</strong></td>
					<td><input type="checkbox" value="1" name="show_displays" {ck_displays}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_tooltip}</strong></td>
					<td><input type="checkbox" value="1" name="active_tooltip" {ck_active_tooltip} id="active_tooltip" /></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.setting}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_hometext}</strong></td>
					<td><input type="checkbox" value="1" name="active_showhomtext" {ck_active_showhomtext} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_price}</strong></td>
					<td><input type="checkbox" value="1" name="active_price" {ck_active_price} id="active_price" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_compare}</strong></td>
					<td><input type="checkbox" value="1" name="show_compare" {ck_compare} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_money_all}</strong></td>
					<td>
					<select class="form-control" name="money_unit">
						<!-- BEGIN: money_loop -->
						<option value="{DATAMONEY.value}"{DATAMONEY.selected}>{DATAMONEY.title}</option>
						<!-- END: money_loop -->
					</select>&nbsp;<em class="fa fa-info-circle fa-lg text-info" data-toggle="tooltip" title="" data-original-title="{LANG.setting_money_all_note}">&nbsp;</em></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_weight_all}</strong></td>
					<td>
					<select class="form-control" name="weight_unit">
						<!-- BEGIN: weight_loop -->
						<option value="{DATAWEIGHT.value}"{DATAWEIGHT.selected}>{DATAWEIGHT.title}</option>
						<!-- END: weight_loop -->
					</select>&nbsp;<em class="fa fa-info-circle fa-lg text-info" data-toggle="tooltip" title="" data-original-title="{LANG.setting_weight_all_note}">&nbsp;</em></td>
				</tr>
				<tr>
					<td><strong>{LANG.format_order_id}</strong></td>
					<td><input class="form-control" type="text" value="{DATA.format_order_id}" style="width: 100px;" name="format_order_id" /><span class="text-middle"> {LANG.format_order_id_note} </span></td>
				</tr>
				<tr>
					<td><strong>{LANG.format_code_id}</strong></td>
					<td><input class="form-control" type="text" value="{DATA.format_code_id}" style="width: 100px;" name="format_code_id" /><span class="text-middle"> {LANG.format_order_id_note} </span></td>
				</tr>
				<tr>
					<th>{LANG.setting_facebookAppID}</th>
					<td><input class="form-control w150" name="facebookappid" value="{DATA.facebookappid}" type="text"/><span class="text-middle">{LANG.setting_facebookAppIDNote}</span></td>
				</tr>
				<tr>
					<th>{LANG.setting_alias_lower}</th>
					<td><input type="checkbox" value="1" name="alias_lower"{ck_alias_lower} id="alias_lower"/></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_show_product_code}</strong></td>
					<td><input type="checkbox" value="1" name="show_product_code" {ck_show_product_code} id="show_product_code" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_sortdefault}</strong></td>
					<td>
						<select name="sortdefault" class="form-control w200">
							<!-- BEGIN: sortdefault -->
							<option value="{SORTDEFAULT.index}" {SORTDEFAULT.selected}>{SORTDEFAULT.value}</option>
							<!-- END: sortdefault -->
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive" id="setting_group_price">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.setting_active_order}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_guest_order}</strong></td>
					<td><input type="checkbox" value="1" name="active_guest_order" {ck_active_guest_order} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_order_non_detail}</strong></td>
					<td><input type="checkbox" value="1" name="active_order_non_detail" {ck_active_order_non_detail} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_order_popup}</strong></td>
					<td><input type="checkbox" value="1" name="active_order_popup" {ck_active_order_popup} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_order_number}</strong></td>
					<td><input type="checkbox" value="1" name="active_order_number" {ck_active_order_number} id="active_order_number" /> {LANG.setting_active_order_number_note} </td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_active_auto_check_order}</strong></td>
					<td><input type="checkbox" value="1" name="auto_check_order" {ck_auto_check_order} id="auto_check_order" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_group_notify}</strong><em class="help-block">{LANG.setting_group_notify_note}</em></td>
					<td>
						<!-- BEGIN: groups_notify -->
						<div class="row">
							<label><input name="groups_notify[]" type="checkbox" value="{GROUPS_NOTIFY.value}" {GROUPS_NOTIFY.checked} />{GROUPS_NOTIFY.title}</label>
						</div>
						<!-- END: groups_notify -->
					</td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_order_day}</strong><span class="help-block">{LANG.setting_order_day_note}</span></td>
					<td><input type="text" name="order_day" class="form-control" value="{DATA.order_day}" />&nbsp;<span class="text-middle">{LANG.setting_order_num_day}</span></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.review}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.review_setting_check}</strong></td>
					<td><input type="checkbox" value="1" name="review_check" {ck_review_check}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.review_setting_captcha}</strong></td>
					<td><input type="checkbox" value="1" name="review_captcha" {ck_review_captcha}/></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.keywords}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.tags_alias}</strong></td>
					<td><input type="checkbox" value="1" name="tags_alias"{TAGS_ALIAS}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_auto_tags}</strong></td>
					<td><input type="checkbox" value="1" name="auto_tags"{AUTO_TAGS}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_tags_remind}</strong></td>
					<td><input type="checkbox" value="1" name="tags_remind"{TAGS_REMIND}/></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.setting_point}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_point_conversion}</strong></td>
					<td><input type="text" name="point_conversion" class="form-control" value="{DATA.point_conversion}" onkeyup="this.value=FormatNumber(this.value);"/> <span class="text-middle">{DATA.money_unit} / {LANG.setting_point_1}</span></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_point_new_order}</strong></td>
					<td><input type="text" name="point_new_order" class="form-control" value="{DATA.point_new_order}" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_money_to_point}</strong></td>
					<td><input type="text" name="money_to_point" class="form-control" value="{DATA.money_to_point}" onkeyup="this.value=FormatNumber(this.value);"/> <span class="text-middle">{DATA.money_unit}</span></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive" id="setting_group_price">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.setting_group_price}
			</caption>
			<colgroup>
				<col class="w500" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_group_price_space}</strong><em class="help-block">{LANG.setting_group_price_space_note}</em></td>
					<td>
						<textarea class="form-control" name="group_price" rows="9" style="width: 100%">{DATA.group_price}</textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="text-center"><input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" /><input type="hidden" value="1" name="savesetting">
	</div>
</form>

<!-- BEGIN: payment -->
<script type="text/javascript">
	var url_back = '{url_back}';
	var url_change_weight = '{url_change}';
	var url_active = '{url_active}';
</script>

<table id="edit" class="table table-striped table-bordered table-hover">
	<caption>
		{LANG.paymentcaption}
	</caption>
	<thead>
		<tr>
			<td class="w100 text-center"><strong>{LANG.weight}</strong></td>
			<td><strong>{LANG.paymentname}</strong></td>
			<td><strong>{LANG.domain}</strong></td>
			<td class="text-center"><strong>{LANG.active}</strong></td>
			<td class="text-center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: paymentloop -->
		<tr>
			<td class="text-center">{DATA_PM.slect_weight}</td>
			<td>{DATA_PM.paymentname}</td>
			<td>{DATA_PM.domain}</td>
			<td class="text-center"><input type="checkbox" name="{DATA_PM.payment}" id="{DATA_PM.payment}" {DATA_PM.active} onclick="ChangeActive(this,url_active)"/></td>
			<td class="text-center"><span class="edit_icon"><a href="{DATA_PM.link_edit}#edit">{LANG.edit}</a></span></td>
		</tr>
		<!-- END: paymentloop -->
	</tbody>
</table>
<!-- END: payment -->

<script type="text/javascript">
	$('#download_active').change(function(){
		$('#download_groups').toggle();
	});
</script>

<!-- BEGIN: main -->