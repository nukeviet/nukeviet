<!-- BEGIN: main -->
<div class="page-header">
		<h3>{LANG.in_group}</h3>
	</div>
<ul class="list-tab top-option clearfix">
				<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
				<li><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
				<li><a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a></li>
				<li><!-- BEGIN: allowopenid --><a href="{URL_HREF}openid">{LANG.openid_administrator}</a><!-- END: allowopenid --></li>
				<li class="ui-tabs-selected"><a href="{URL_HREF}regroups">{LANG.in_group}</a></li>
				<li><!-- BEGIN: logout --><a href="{URL_HREF}logout">{LANG.logout_title}</a><!-- END: logout --></li>
</ul>
<!-- BEGIN: error -->
<hr /><br />
<div style="width: 600px;" class="quote">
    <blockquote class="error">
        <p> <span style="color: #F00; font-weight: bold; font-size: 15px">{ERROR}</span></p>
    </blockquote>
</div>
<br />
<div class="clear"></div>
<!-- END: error -->
<form id="registerForm" action="{USER_REGISTER}" method="post" class="box-border-shadow content-box clearfix reg">

<dl class="clearfix gray">
	<dt class="fl"><label> {LANG.in_group} </label></dt>
	<dd class="fr"><!-- BEGIN: list --> <input type="checkbox"
		value="{GROUP.id}" name="group[]" {GROUP.checked} /> <span>{GROUP.title}</span><br />
	<!-- END: list --></dd>
</dl>
<input id="submit" type="submit" class="button" value="{LANG.register}" name="save" />
</form>
<!-- END: main -->