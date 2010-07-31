<!-- BEGIN: main -->
<!-- BEGIN: permission -->
<span style='color:red;display:inline-block;padding:10px'>{permission}</span>
<!-- END: permission -->
<!-- BEGIN: script -->
{script}
<!-- END: script -->
<!-- BEGIN: message -->
<div class="description1">
<h3>Upload file</h3>
<P>{MSG}</P>
<P>{META}</P>
</div>
<!-- END: message -->
<!-- BEGIN: content -->
<div class="description1"  style='padding:5px'>
<h3>{LANG.upload_title}</h3>
<form action='' enctype="multipart/form-data" name='formfileupload'	method='post'>
<table width="100%">
	<tbody class="upload_tb">
		<tr>
			<td style=" color:#F00; font-weight:bold" align="center">{ERROR}</td>
		</tr>
    </tbody>
</table>
<table>
    <tbody class="upload_tb">
        <tr>
			<td style="width: 150px;">{LANG.upload_filename}</td>
			<td><input name="title" style="width: 290px;" type="text" value="{DATA.title}"> *</td>
		</tr>
		<tr>
			<td>{LANG.upload_cat}</td>
			<td><select name="catparent">
				<!-- BEGIN: selectcat -->
				<option value="{selectcat.cid}">{selectcat.title}</option>
				<!-- END: selectcat -->
			</select></td>
		</tr>
		<tr>
			<td>{LANG.upload_des}</td>
			<td><textarea id="description" name="description" cols='45' rows='5'>{DATA.description}</textarea>  *</td>
		</tr>
		<tr>
			<td style="width: 150px;">{LANG.upload_image}</td>

			<td><input name="fileimage" id="fileimage" style="width: 290px;" type="file"><br />
			<span id="imgfiletype">{LANG.upload_allowtype}</span></td>
		</tr>		
		<tr>
			<td style="width: 150px;">{LANG.upload_tag}</td>
			<td><input value="{DATA.taglist}" name="taglist" style="width: 300px;" type="text"></td>
		</tr>
		<tr>
			<td style="width: 150px;">{LANG.upload_author}</td>
			<td><input value="{DATA.author}" name="author" style="width: 290px;" type="text"></td>
		</tr>
		<tr>
			<td style="width: 150px;">{LANG.upload_email}</td>
			<td><input value="{DATA.authoremail}" name="authoremail" style="width: 290px;" type="text"> *</td>
		</tr>
		<tr>
			<td style="width: 150px;">{LANG.upload_homepage}</td>
			<td><input value="{DATA.homepage}" name="homepage" style="width: 290px;" type="text"></td>
		</tr>
		<!-- BEGIN: fileupload -->
		<tr>
			<td style="width: 150px;">{LANG.upload_filename}</td>
			<td><input name="fileupload" id="fileupload" style="width: 290px;" type="file"><br />
			{LANG.upload_filetype} <span id="filetype">{FILE_TYPE}</span>
			</td>
		</tr>
		<!-- END: fileupload -->
		<tr>
			<td style="width: 150px;">{LANG.upload_filelink}</td>
			<td><textarea cols="45" name="linkdirect">{DATA.linkdirect}</textarea></td>
		</tr>
		<tr>
			<td style="width: 150px;">{LANG.upload_version}</td>

			<td><input value="{DATA.version}" name="version" style="width: 150px;" type="text"></td>
		</tr>
		<tr>
			<td style="width: 150px;">{LANG.upload_filesize}</td>
			<td><input value="{DATA.filesize}" name="filesize" style="width: 150px;" type="text">
			{LANG.upload_fileblank}</td>
		</tr>
        <tr>
			<td style="width: 150px;">{LANG.upload_copyright}</td>

			<td><input value="{DATA.copyright}" name="copyright" style="width: 150px;" type="text"></td>
		</tr>
		<tr>
			<td>{LANG.upload_captcha}</td>
            <td>
            <input name="captcha" style="width: 70px;float:left" type='text'/> 
            <img style="vertical-align: top; height: 22px;" src="{BASE_SITE_URL}?scaptcha=captcha" id="vimg" style="float:left">
            <input type="button" onclick="nv_change_captcha('vimg','commentseccode');" value="" class="bt_reset1"/>
			</td>
		</tr>
        <tr>
			<td></td>
            <td><input name="confirm" value="Upload" type='button'></td>
		</tr>
	</tbody>
</table>
</form>
<script type="text/javascript">
function verify(myArray,myValue) {
	var yesno = eval(myArray).join().indexOf(myValue)>=0;
	return yesno;
}
$(function(){
	$('input[name=confirm]').click(function(event){
		event.preventDefault();
		var title = $('input[name=title]').val();
		if (title.length<5){
			alert("{LANG.upload_error_title}");
			$('input[name=title]').focus();
			return false;
		}
		var description = $("textarea[name=description]").val();
		if (description.length<15){
			alert("{LANG.upload_error_des}");
			$('textarea[name=description]').focus();
			return false;
		}
		
		var authoremail = $("input[name=authoremail]").val();
		if (description.length>0){
			if (!nv_mailfilter.test(authoremail)){
				alert("{LANG.upload_error_email}");
				return false;
			}
		}
		
		var fileimage = $("input[name=fileimage]").val();
		if (fileimage!=''){
		    var imgextension = fileimage.slice(-3);
			var imgfiletype = $('#imgfiletype').text();
			var imgtypearray = imgfiletype.split(',');
			if (!verify(imgtypearray,imgextension)){
				alert("{LANG.upload_error_fileimagetype}");
		    	return false;
			}
		}
		var fileupload = $("input[name=fileupload]").val();
		<!-- BEGIN: javaup -->
	    var extension = fileupload.slice(-3);
		var filetype = $('#filetype').text();
		var typearray = filetype.split(',');
		if (!verify(typearray,extension)){
			alert("{LANG.upload_error_fileupoadtype}");
	    	return false;
		}
		<!-- END: javaup -->
		var linkdirect = $("textarea[name=linkdirect]").val();

		if (fileupload=='' && linkdirect==''){
			alert("{LANG.upload_error_fileupoad}");
			$('input[name=fileupload]').focus();
			return false;
		}
		var captcha = $('input[name=captcha]').val();
		if (captcha=='' || !nv_name_check(captcha)){
			alert("{LANG.upload_error_captcha}");
			return false;
		} 
		$("form[name=formfileupload]").submit();
	});
});
</script></div>
<!-- END: content -->
<!-- BEGIN: scriptfoot -->
{scriptfoot}
<!-- END: scriptfoot -->
<!-- END: main -->