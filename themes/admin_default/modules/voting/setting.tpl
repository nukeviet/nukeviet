<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td colspan="2"><input type="submit" name="submit" value="{LANG.config_save}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
                        <td>{LANG.captcha_type}</td>
                        <td>
                            <select class="form-control w200" name="captcha_type" data-recaptcha-note="{IS_RECAPTCHA_NOTE}">
                                <!-- BEGIN: captcha_type -->
                                <option value="{CAPTCHATYPE.key}"{CAPTCHATYPE.selected}>{CAPTCHATYPE.title}</option>
                                <!-- END: captcha_type -->
                            </select>
                            <span class="recaptcha_note"<!-- BEGIN: recaptcha_note_hide --> style="display:none"<!-- END: recaptcha_note_hide -->>{RECAPTCHA_NOTE}</span>
                        </td>
                    </tr>
				</tbody>
			</table>
		</div>
</form>
<script>
$(function() {
    $("[name=captcha_type]").on('change', function(e) {
        var v = $(this).val(),
            is_recaptcha_note = $(this).data('recaptcha-note');
        if (is_recaptcha_note && v == 'recaptcha') {
            $(".recaptcha_note").show()
        } else {
            $(".recaptcha_note").hide()
        }
    })
});
</script>
<!-- END: main -->