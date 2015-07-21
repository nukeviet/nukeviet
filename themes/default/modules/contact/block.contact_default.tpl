<!-- BEGIN: main -->
<span class="visible-xs-inline-block"><a href="tel:{DEPARTMENT.phone}" title="Hotline" class="hidden-ss-inline-block margin-right"><em class="fa fa-phone"></em>&nbsp;{DEPARTMENT.phone}</a><a title="{LANG.contactUs}" class="pointer button" data-toggle="tip" data-target="#contactList" data-click="y"><em class="fa fa-life-ring fa-lg"></em><span class="hidden">{LANG.contactUs}</span></a></span>
<div id="contactList" class="content">
<h3 class="visible-xs-inline-block">{LANG.contactUs}</h3>
<ul class="contactList">
<!-- BEGIN: phone --><li><a href="tel:{DEPARTMENT.phone}" title="Hotline"><em class="fa fa-phone"></em>&nbsp;{DEPARTMENT.phone}</a></li><!-- END: phone -->
<!-- BEGIN: email --><li><a href="{DEPARTMENT.emailhref}" title="Email"><em class="fa fa-envelope"></em>&nbsp;{DEPARTMENT.email}</a></li><!-- END: email -->
<!-- BEGIN: yahoo --><li><a href="ymsgr:SendIM?{DEPARTMENT.yahoo}" title="Yahoo Chat"><em class="fa fa-weixin"></em>&nbsp;{DEPARTMENT.yahoo}</a></li><!-- END: yahoo -->
<!-- BEGIN: skype --><li><a href="skype:{DEPARTMENT.skype}?call" title="Skype"><em class="fa fa-skype"></em>&nbsp;{DEPARTMENT.skype}</a></li><!-- END: skype -->
</ul>
</div>
<!-- END: main -->