<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="frm">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
                <col style="width: 40%" />
                <col style="width: 60%" />
            </colgroup>
            <tfoot>
				<tr>
					<td class="text-center" colspan="2"><input type="hidden" name="checkss" value="{DATA.checkss}" /><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary w100"/></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td><strong>{LANG.cookie_prefix}</strong></td>
					<td><input class="w200 required validalphanumeric form-control" type="text" name="cookie_prefix" value="{DATA.cookie_prefix}" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.session_prefix}</strong></td>
					<td><input class="w200 required validalphanumeric form-control" type="text" name="session_prefix" value="{DATA.session_prefix}" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.live_cookie_time}</strong></td>
					<td><input class="required form-control pull-left w50" type="text" name="nv_live_cookie_time" value="{NV_LIVE_COOKIE_TIME}" /> <span class="text-middle">&nbsp;({GLANG.day})</span> </td>
				</tr>
				<tr>
					<td><strong>{LANG.live_session_time}</strong></td>
					<td><input class="required form-control pull-left w50" type="text" name="nv_live_session_time" value="{NV_LIVE_SESSION_TIME}" /> <span class="text-middle">&nbsp;({GLANG.min}) <em>{LANG.live_session_time0}</em></span></td>
				</tr>
				<tr>
					<td><strong>{LANG.cookie_secure}</strong><br />({LANG.cookie_secure_note})</td>
					<td><input type="checkbox"  value="1" name="cookie_secure" {CHECKBOX_COOKIE_SECURE}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.cookie_httponly}</strong><br />({LANG.cookie_httponly_note})</td>
					<td><input type="checkbox" value="1" name="cookie_httponly" {CHECKBOX_COOKIE_HTTPONLY}/></td>
				</tr>
                <tr>
					<td><strong>{LANG.cookie_SameSite}</strong><br />({LANG.cookie_SameSite_note})<br />{LANG.cookie_SameSite_note2}</td>
					<td>
                        <!-- BEGIN: SameSite -->
                        <div>
                            <label class="pointer"><input type="radio" name="cookie_SameSite" value="{SAMESITE.val}"{SAMESITE.checked}/> <code>{SAMESITE.val}</code>: {SAMESITE.note}</label>
                        </div>
                        <!-- END: SameSite -->
                    </td>
				</tr>
			</tbody>
		</table>
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