<!-- BEGIN: main -->
<!-- BEGIN: paymentedit -->
<div style="background:#F0F0F0;padding:10px; font-weight:bold">{EDITPAYMENT}</div>
<form action="" method="post">
<table class="tab1">
    <tbody class="second">
    <tr>
        <td>{LANG.paymentname}</td>
        <td><input name="paymentname" value="{DATA.paymentname}" style="width: 400px;"></td>
    </tr>
    </tbody>
    <tr>
        <td>{LANG.domain}</td>
        <td><input name="domain" value="{DATA.domain}" style="width: 400px;"></td>
    </tr>
    <tbody class="second">
    <tr>
        <td>{LANG.active}</td>
        <td><input type="checkbox" value="1" name="active" {DATA.active}/></td>
    </tr>
    </tbody>
    <!-- BEGIN: config -->
    <tbody{cl}>
        <tr>
            <td>{CONFIG_LANG}</td>
            <td><input name="config[{CONFIG_NAME}]" value="{CONFIG_VALUE}" style="width: 400px;"></td>
        </tr>
    </tbody>
    <!-- END: config -->
    <tbody class="second">
    <tr>
        <td>{LANG.images_button}</td>
        <td>
            <input style="width:400px" type="text" name="images_button" id="homeimg" value="{DATA.images_button}"/>
            <input type="button" value="{LANG.browse_image}" name="selectimg"/>
        </td>
    </tr>
    </tbody>				
    <tr>
        <td><input name="payment" value="{DATA.payment}" type="hidden"></td>
        <td><input type="submit" value="{LANG.save}" name="saveconfigpaymentedit"></td>
    <tr>
</table>
</form>   
<!-- END: paymentedit -->

<!-- BEGIN: listpay -->
<script type="text/javascript">
	var url_back = '{url_back}';
	var url_change_weight = '{url_change}';
	var url_active = '{url_active}';
</script>
<div style="background:#F0F0F0;padding:10px; font-weight:bold">{LANG.paymentcaption}</div>
<table id="edit" class="tab1">
	<thead>
		<tr>
			<td width="50" align="center"><strong>{LANG.weight}</strong></td>
            <td><strong>{LANG.paymentname}</strong></td>
			<td><strong>{LANG.domain}</strong></td>
			<td align="center"><strong>{LANG.active}</strong></td>
			<td align="center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<!-- BEGIN: paymentloop -->
	<tbody{DATA_PM.class}>
		<tr>
			<td align="center">{DATA_PM.slect_weight}</td>
            <td>{DATA_PM.paymentname}</td>
			<td>{DATA_PM.domain}</td>
			<td align="center"><input type="checkbox" name="{DATA_PM.payment}" id="{DATA_PM.payment}" {DATA_PM.active} onclick="ChangeActive(this,url_active)"/></td>
			<td align="center"><span class="edit_icon"><a href="{DATA_PM.link_edit}#edit">{LANG.edit}</a></span></td>
		</tr>
	</tbody>
	<!-- END: paymentloop -->
</table>
<!-- END: listpay -->

<!-- BEGIN: olistpay -->
<div style="background:#F0F0F0;padding:10px; font-weight:bold">{LANG.paymentcaption_other}</div>
<table id="edit" class="tab1">
	<thead>
		<tr>
			<td align="center" width="50"><strong>{LANG.setting_stt}</strong></td>
            <td><strong>{LANG.paymentname}</strong></td>
			<td><strong>{LANG.domain}</strong></td>
			<td align="center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<!-- BEGIN: opaymentloop -->
	<tbody{DATA_PM.class}>
		<tr>
			<td align="center">{ODATA_PM.STT}</td>
            <td>{ODATA_PM.paymentname}</td>
			<td>{ODATA_PM.domain}</td>
			<td align="center"><span class="edit_icon"><a href="{ODATA_PM.link_edit}#edit">{LANG.payment_integrat}</a></span></td>
		</tr>
	</tbody>
	<!-- END: opaymentloop -->
</table>
<!-- END: olistpay -->

<script type="text/javascript">
	$("input[name=selectimg]").click(function(){
		var area = "homeimg";
		var path= "{NV_UPLOADS_DIR}/{MODULE_NAME}";						
		var type= "image";
		nv_open_browse_file("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "400","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	}); 
</script>
<!-- END: main -->