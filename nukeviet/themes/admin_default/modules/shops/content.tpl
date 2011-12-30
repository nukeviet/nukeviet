<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="error">
	{error}
</div>
<!-- END: error -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}js/jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/popcalendar/popcalendar.js"></script>
<form action="" enctype="multipart/form-data" method="post">
	<input type="hidden" value="1" name="save">
	<input type="hidden" value="{rowcontent.id}" name="id">
	<div class="gray">
		<table width="100%" style="margin-bottom:0">
			<tr>
				<td valign="top">
				<table summary="" class="tab1">
					<tbody>
						<tr>
							<td width="120px"><strong>{LANG.content_cat} (*)</strong></td>
							<td>
							<select name="catid" style="width:300px">
								<!-- BEGIN: rowscat -->
								<option value="{catid_i}" {select} >{xtitle_i} {title_i}</option>
								<!-- END: rowscat -->
							</select></td>
						</tr>
					</tbody>
					<tbody class="second">
						<tr>
							<td width="120px"><strong>{LANG.name} (*)</strong></td>
							<td>
							<input type="text" maxlength="255" value="{rowcontent.title}" name="title" id="idtitle" style="width:90%" />
							</td>
						</tr>
					</tbody>
					<tbody >
						<tr>
							<td><strong>{LANG.alias}: </strong></td>
							<td>
							<input style="width:90%" name="alias" type="text" id="idalias" value="{rowcontent.alias}" maxlength="255"/>
							<input type="button" value="GET" onclick="get_alias();" style="font-size:11px"  />
							</td>
						</tr>
					</tbody>
				</table>
				<table summary="" class="tab1">
					<tbody>
						<tr>
							<td align="right"><strong>{LANG.content_product_code}</strong></td>
							<td>
							<input type="text" maxlength="32" value="{rowcontent.product_code}" name="product_code" style="width: 200px;" />
							</td>
							<td align="right"><strong>{LANG.content_product_product_price}</strong></td>
							<td>
							<input type="text" maxlength="20" value="{rowcontent.product_price}" name="product_price" style="width: 100px;" />
							<select name="money_unit">
								<!-- BEGIN: money_unit -->
								<option value="{MON.code}" {MON.select}>{MON.currency}</option>
								<!-- END: money_unit -->
							</select></td>
						</tr>
					</tbody>
					<tbody class="second">
						<td width="110px"><strong>{LANG.content_product_number}</strong></td>
						<td><!-- BEGIN: edit --><strong>{rowcontent.product_number}</strong> +
						<input type="text" maxlength="10" value="0" name="product_number" style="width: 50px;" />
						<!-- END: edit --><!-- BEGIN: add -->
						<input type="text" maxlength="10" value="{rowcontent.product_number}" name="product_number" style="width: 50px;" />
						<!-- END: add -->
						<select name="product_unit">
							<!-- BEGIN: rowunit -->
							<option value="{uid}" {uch}>{utitle}</option>
							<!-- END: rowunit -->
						</select></td>
						<td align="right"><strong>{LANG.content_product_discounts}</strong></td>
						<td>
						<input type="text" maxlength="3" value="{rowcontent.product_discounts}" name="product_discounts" style="width: 20px;" />
						<strong>%</strong></td>
					</tbody>
				</table>
				<table summary="" class="tab1" style="margin-bottom:0">
					<tbody>
						<tr>
							<td><strong>{LANG.content_homeimg}</strong></td>
						</tr>
					</tbody>
					<tbody class="second">
						<tr>
							<td>
							<input style="width:400px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/>
							<input type="button" value="Browse server" name="selectimg"/>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td> {LANG.content_homeimgalt} </td>
						</tr>
					</tbody>
					<tbody class="second">
						<tr>
							<td>
							<input type="text" maxlength="255" value="{rowcontent.homeimgalt}" name="homeimgalt" style="width:98%" />
							</td>
						</tr>
					</tbody>
				</table>
				<table summary="" class="tab1" style="margin-bottom:0">
					<tbody>
						<tr>
							<td><strong>{LANG.content_hometext} (*)</strong> {LANG.content_notehome}</td>
						</tr>
					</tbody>
					<tbody class="second">
						<tr>
							<td>							<textarea class="textareas" name="hometext" style="width: 98%; height:100px">{rowcontent.hometext}</textarea></td>
						</tr>
					</tbody>
				</table>
				<table summary="" class="tab1">
					<tr>
						<td width="110px"><strong>{LANG.content_product_address}</strong></td>
						<td>
						<input type="text" maxlength="255" value="{rowcontent.address}" name="address" style="width:98%;"/>
						</td>
					</tr>
				</table>
				<table summary="" class="tab1">
					<tr>
						<td width="110px"><strong>{LANG.content_sourceid}</strong></td>
						<td>
						<select name="sourceid" style="width: 300px;">
							{sourceid}
						</select>
						<input type="text" maxlength="255" id="AjaxSourceText" value="{rowcontent.sourcetext}" name="sourcetext" style="width:225px;">
						</td>
					</tr>
				</table>
				<table class="tab1">
					<tr>
						<td><strong>{LANG.content_note}</strong></td>
					</tr>
					<tbody class="second">
						<tr>
							<td>							<textarea class="textareas" rows="6" name="note" style="width:98%">{rowcontent.note}</textarea></td>
						</tr>
					</tbody>
				</table></td>
				<td valign="top" style="width: 280px">
				<div style="margin-left:4px;">
					<!-- BEGIN:listgroup -->
					<table summary="" class="tab1">
						<tbody class="second">
							<tr>
								<td><strong>{LANG.content_group}</strong></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>
								<div style="padding:4px; height:100px;background:#FFFFFF; overflow:auto; text-align:left; border: 1px solid #CCCCCC">
									<ul style="margin:0">
										{listgroupid}
									</ul>
								</div></td>
							</tr>
						</tbody>
					</table>
					<!-- END:listgroup -->
					<!-- BEGIN:block_cat -->
					<table summary="" class="tab1">
						<tbody class="second">
							<tr>
								<td><strong>{LANG.content_block}</strong></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>
								<div style="padding:4px; background:#FFFFFF; text-align:left; border: 1px solid #CCCCCC">
									{row_block}
								</div></td>
							</tr>
						</tbody>
					</table>
					<!-- END:block_cat -->
					<table summary="" class="tab1">
						<tbody class="second">
							<tr>
								<td style="line-height:16px"><strong>{LANG.content_keywords}</strong>
								<br />
								{LANG.content_keywords_note} <a onclick="create_keywords();" href="javascript:void(0);" style="color:#0099CC">{LANG.content_clickhere}</a></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>								<textarea rows="3" cols="20" id="keywords" name="keywords" style="width: 98%;">{rowcontent.keywords}</textarea></td>
							</tr>
						</tbody>
					</table>
					<table summary="" class="tab1">
						<tbody class="second">
							<tr>
								<td><strong>{LANG.content_publ_date}</strong><span class="timestamp">{LANG.content_notetime}</span></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>
								<input name="publ_date" id="publ_date" value="{publ_date}" style="width:90px;" maxlength="10" readonly="readonly" type="text"/>
								<img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;"
								onclick="popCalendar.show(this, 'publ_date', 'dd/mm/yyyy', false);" alt="" height="17">
								<select name="phour">
									{phour}
								</select> :
								<select name="pmin">
									{pmin}
								</select>
								<input type="button" value="{LANG.comment_delete}" style="font-size:11px" onclick="clearobval('publ_date')" />
								</td>
							</tr>
						</tbody>
					</table>
					<table summary="" class="tab1">
						<tbody class="second">
							<tr>
								<td><strong>{LANG.content_exp_date}</strong><span class="timestamp">{LANG.content_notetime}</span></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>
								<div>
									<input name="exp_date" id="exp_date" value="{exp_date}" style="width:90px;" maxlength="10" readonly="readonly" type="text"/>
									<img src="{NV_BASE_SITEURL}images/calendar.jpg" widht="18" style="cursor: pointer; vertical-align: middle;"
									onclick="popCalendar.show(this, 'exp_date', 'dd/mm/yyyy', false);" alt="" height="17">
									<select name="ehour">
										{ehour}
									</select>
									:
									<select name="emin">
										{emin}
									</select>
									<input type="button" value="{LANG.comment_delete}" style="font-size:11px;" onclick="clearobval('exp_date')" />
								</div>
								<div style="margin-top: 5px;">
									<input type="checkbox" value="1" name="archive" {archive_checked} />
									<label>{LANG.content_archive}</label>
								</div></td>
							</tr>
						</tbody>
					</table>
					<table summary="" class="tab1">
						<tbody class="second">
							<tr>
								<td><strong>{LANG.content_extra}</strong></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="inhome" {inhome_checked}/>
									<label>{LANG.content_inhome}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<label>{LANG.content_allowed_comm}</label>
									<select name="allowed_comm">
										{allowed_comm}
									</select>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_rating" {allowed_rating_checked}/>
									<label>{LANG.content_allowed_rating}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_send" {allowed_send_checked}/>
									<label>{LANG.content_allowed_send}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_print" {allowed_print_checked} />
									<label>{LANG.content_allowed_print}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_save" {allowed_save_checked} />
									<label>{LANG.content_allowed_save}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" name="showprice" value="1" {ck_showprice}/>
									{LANG.content_showprice}
								</div></td>
							</tr>
						</tbody>
					</table>
				</div></td>
			</tr>
		</table>
	</div>
	<div class="gray">
		<table summary="" class="tab1">
			<tbody>
				<tr>
					<td><strong>{LANG.content_bodytext}</strong>{LANG.content_bodytext_note}</td>
				</tr>
			</tbody>
			<tbody class="second">
				<tr>
					<td>
					<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
						{edit_bodytext}
					</div></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="gray">
		<center>
			<!-- BEGIN:status -->
			<input name="statussave" type="submit" value="{LANG.save}" />
			<!-- END:status -->
			<!-- BEGIN:status0 -->
			<input name="status0" type="submit" value="{LANG.save_temp}" />
			<input name="status1" type="submit" value="{LANG.publtime}" />
			<!-- END:status0 -->
		</center>
	</div>
</form>
<script type="text/javascript">
    $("input[name=selectimg]").click(function()
    {
        var area = "homeimg";
        var path = "{NV_UPLOADS_DIR}/{module_name}";
        var currentpath = "{CURRENT}";
        var type = "image";
        nv_open_browse_file("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", "850", "400", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
    $(document).ready(function()
    {
        $("#AjaxSourceText").autocomplete("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={module_name}&{NV_OP_VARIABLE}=sourceajax",
        {
            delay : 10,
            minChars : 2,
            matchSubset : 1,
            matchContains : 1,
            cacheLength : 10,
            onItemSelect : selectItem,
            onFindValue : findValue,
            formatItem : formatItem,
            autoFill : true
        });
    });
    function clearobval(ob)
    {
        $("#" + ob + "").val('');
    }
</script>
<!-- BEGIN: getalias -->
<script type="text/javascript">
    $("#idtitle").change(function()
    {
        get_alias();
    });

</script>
<!-- END: getalias -->
<!-- END:main -->