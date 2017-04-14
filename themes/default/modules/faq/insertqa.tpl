<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<h3>{LANG.faq_addfaq}</h3>
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
    	<colgroup>
			<col class="w200"/>
		</colgroup>
        <tbody>
            <tr>
                <td style="width:120px">
                    {LANG.faq_title_faq} <sup class="required">(∗)</sup>
                </td>
                <td>
                    <input class="form-control" type="text" value="{DATA.title}" name="title" id="title" style="width:400px"/>
                </td>
            </tr>
            <tr>
                <td style="width:120px">
                    {LANG.faq_catid_faq} <sup class="required">(∗)</sup>
                </td>
                <td>
                    <select class="form-control" name="catid" style="width:400px">
                        <!-- BEGIN: catid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: catid -->
                    </select>
                </td>
            </tr>
            <tr>
                <td style="vertical-align:top;width:120px">
                    {LANG.faq_question_faq} <sup class="required">(∗)</sup>
                </td>
                <td><textarea name="question" id="question" class="form-control" rows="5" style="width:400px">{DATA.question}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="m-bottom">
        <h4>{LANG.faq_answer_faq}</h4>
        {DATA.answer}
    </div>
    <div class="row col-md-24">
    	<!-- BEGIN: captcha -->
		<div class="form-group">
            <div class="middle text-right clearfix">
                <img width="{GFX_WIDTH}" height="{GFX_HEIGHT}" title="{LANG.captcha}" alt="{LANG.captcha}" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" class="captchaImg display-inline-block">
                <em onclick="change_captcha('.fcode');" title="{GLANG.captcharefresh}" class="fa fa-pointer fa-refresh margin-left margin-right"></em>
                <input type="text" placeholder="{LANG.captcha}" maxlength="{NV_GFX_NUM}" value="" name="fcode" class="fcode required form-control display-inline-block" style="width:100px;" data-pattern="/^(.){{NV_GFX_NUM},{NV_GFX_NUM}}$/" onkeypress="nv_validErrorHidden(this);" data-mess="{LANG.error_captcha}"/>
            </div>
		</div>
        <!-- END: captcha -->
        <!-- BEGIN: recaptcha -->
        <div class="form-group">
            <div class="middle text-center clearfix">
                <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}"></div></div>
                <script type="text/javascript">
                nv_recaptcha_elements.push({
                    id: "{RECAPTCHA_ELEMENT}",
                    btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent())
                })
                </script>
            </div>
        </div>
        <!-- END: recaptcha -->
    </div>
    <div class="faq text-center">
        <input class="btn btn-primary" type="submit" name="submit" value="{LANG.faq_save}" />
    </div>
</form>
<!-- END: main -->