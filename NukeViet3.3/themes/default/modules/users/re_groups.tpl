<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.in_group}</h2>
    <div style="padding-bottom:10px">
        <div class="utop">
            <span class="topright">
            <a href="{URL_HREF}main">{LANG.user_info}</a> 
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
                <!-- BEGIN: allowopenid --><strong>&middot;</strong> <a href="{URL_HREF}openid">{LANG.openid_administrator}</a><!-- END: allowopenid -->
                <strong>&middot;</strong><a href="{URL_HREF}regroups">{LANG.in_group}
                <!-- BEGIN: logout --><strong>&middot;</strong> <a href="{URL_HREF}logout">{LANG.logout_title}</a><!-- END: logout -->			
 			</span>
   		 </div>
    <div class="clear"></div>
    </div>
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
</div>
<!-- END: main -->