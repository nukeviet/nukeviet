<!-- BEGIN: main -->
<span class="visible-xs-inline-block"><a title="{LANG.contactUs}" class="pointer button" data-toggle="tip" data-target="#contactList" data-click="y"><em class="icon-old-phone icon-lg"></em><span class="hidden">{LANG.contactUs}</span></a></span>
<div id="contactList" class="content">
<h3 class="visible-xs-inline-block">{LANG.contactUs}</h3>
<ul class="contactList">
<!-- BEGIN: phone --><li><em class="fa fa-phone"></em>&nbsp;<!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><!-- BEGIN: href --><a href="tel:{PHONE.href}"><!-- END: href -->{PHONE.number}<!-- BEGIN: href2 --></a><!-- END: href2 --><!-- END: item --></li><!-- END: phone -->
<!-- BEGIN: email --><li><em class="fa fa-envelope"></em>&nbsp;<!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="{DEPARTMENT.emailhref}">{EMAIL}</a><!-- END: item --></li><!-- END: email -->
<!-- BEGIN: yahoo --><li><em class="icon-yahoo"></em>&nbsp;<!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="ymsgr:SendIM?{YAHOO}" title="Yahoo Chat">{YAHOO}</a><!-- END: item --></li><!-- END: yahoo -->
<!-- BEGIN: skype --><li><em class="fa fa-skype"></em>&nbsp;<!-- BEGIN: item --><!-- BEGIN: comma -->&nbsp; <!-- END: comma --><a href="skype:{SKYPE}?call" title="Skype">{SKYPE}</a><!-- END: item --></li><!-- END: skype -->
</ul>
</div>
<!-- END: main -->