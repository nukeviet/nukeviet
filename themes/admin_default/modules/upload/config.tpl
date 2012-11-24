<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<caption>
			{LANG.autologo}
		</caption>
		<!-- BEGIN: loop1 -->
		<tbody {CLASS}>
			<tr>
				<!-- BEGIN: loop2 -->
				<td>
				<input type="checkbox" name="autologomod[]" value="{MOD_VALUE}" {LEV_CHECKED}/>
				{CUSTOM_TITLE} </td>
				<!-- END: loop2 -->
			</tr>
		</tbody>
		<!-- END: loop1 -->
	</table>
	<table class="tab1">
		<caption>
			{LANG.logosizecaption}
		</caption>
		<tbody class="second">
			<tr>
				<td>{LANG.upload_logo}</td>
				<td>
					<input type="text" style="width: 350px" value="{AUTOLOGOSIZE.upload_logo}" id="upload_logo" name="upload_logo">
					<input type="button" name="selectimg" value="{LANG.selectimg}" style="width:100px">
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.imagewith} <= 150px</td>
				<td>
					{LANG.logowith}
					<input type="text" style="width: 30px" value="{AUTOLOGOSIZE.autologosize1}" maxlength="2" name="autologosize1"/>
					% {LANG.fileimage}
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.imagewith} > 150px, < 350px</td>
				<td>
					{LANG.logowith}
					<input type="text" style="width: 30px" value="{AUTOLOGOSIZE.autologosize2}" maxlength="2" name="autologosize2"/>
					% {LANG.fileimage}	
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.imagewith} >= 350px</td>
				<td>
					{LANG.logosize3}
					<input type="text" style="width: 30px" value="{AUTOLOGOSIZE.autologosize3}" maxlength="2" name="autologosize3"/>
					% {LANG.fileimage}
				</td>
			</tr>
		</tbody>
	</table>
	<div class="center">
		<input name="submit" type="submit" value="{LANG.pubdate}" />
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("input[name=selectimg]").click(function(){
		var area = "upload_logo";
		var path= "";                       
		var currentpath= "images";                      
		var type= "image";
		nv_open_browse_file("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area+"&path="+path+"&type="+type+"&currentpath="+currentpath, "NVImg", "850", "420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
});
</script>
<!--  END: main  -->