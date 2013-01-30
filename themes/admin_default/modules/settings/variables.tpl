<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error"> {ERROR} </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<form action="" method="post" id="frm">
	<table class="tab1">
		<tbody>
			<tr>
				<td><strong>{LANG.cookie_prefix}</strong></td>
				<td><input class="required validalphanumeric" type="text" name="cookie_prefix" value="{DATA.cookie_prefix}" style="width: 200px"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.session_prefix}</strong></td>
				<td><input class="required validalphanumeric" type="text" name="session_prefix" value="{DATA.session_prefix}" style="width: 200px"/></td>
			</tr>
		</tbody>

		<tbody>
			<tr>
				<td><strong>{LANG.live_cookie_time}</strong></td>
				<td><input class="required digits" type="text" name="nv_live_cookie_time" value="{NV_LIVE_COOKIE_TIME}" style="width: 50px; text-align: right"/> ({GLANG.day}) </td>
			</tr>
		</tbody>

		<tbody class="second">
			<tr>
				<td><strong>{LANG.live_session_time}</strong></td>
				<td><input class="required digits" type="text" name="nv_live_session_time" value="{NV_LIVE_SESSION_TIME}" style="width: 50px; text-align: right"/> ({GLANG.min}) <i>{LANG.live_session_time0}</i></td>
			</tr>
		</tbody>

		<tbody>
			<tr>
				<td><strong>{LANG.cookie_secure}</strong></td>
				<td><input type="checkbox"  value="1" name="cookie_secure" {CHECKBOX_COOKIE_SECURE}/></td>
			</tr>
		</tbody>

		<tbody class="second">
			<tr>
				<td><strong>{LANG.cookie_httponly}</strong></td>
				<td><input type="checkbox" value="1" name="cookie_httponly" {CHECKBOX_COOKIE_HTTPONLY}/></td>
			</tr>
		</tbody>

	</table>
	<div style="width: 200px; margin: 10px auto; text-align: center;">
		<input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/>
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$.validator.addMethod('validalphanumeric', function(str) {
			return (/^([a-zA-Z0-9_])+$/.test(str) ) ? true : false;
		}, ' required a-z, 0-9, and _ only');
		$('#frm').validate();
	}); 
</script>
<!-- END: main -->