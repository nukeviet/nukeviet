<!-- BEGIN: main -->
<div id="weblink">
    <h2>{DETAIL.title}</h2>
    <div class="thumb_imgweb">
    <p>
        <!-- BEGIN: img -->
        <img src="{IMG}" alt="" />
        <!-- END: img -->
        <strong>{LANG.name}: </strong>
        <a title="{DETAIL.title}" href="{DETAIL.visit}" target="_blank"><strong>{DETAIL.url}</strong></a><br />
        {LANG.visit}: <span style="color:#F90">{DETAIL.hits_total}</span>  <br />
        {LANG.regiter}: {DETAIL.add_time} <br />
        {LANG.edit_time}: {DETAIL.edit_time}
    </p>
    <div class="clear"></div>
    </div>
	<div class="wl fl"><div class="padding">{LANG.report}: </div></div>
    <div class="wr fr"><div class="padding"><a title="{LANG.report}" href="javascript:void(0);" onclick="NewWindow('{DETAIL.report}','','400','250','no');return false">Click here</a></div></div>
    <div class="clear"></div>
    <!-- BEGIN: des -->
    <div><strong>{LANG.description}: </strong></div>
    <div><div class="padding">{DETAIL.description}</div></div>
    <div class="clear"></div>
    <!-- END: des -->
    <div align="right">{ADMIN_LINK}</div>
</div>
<!-- END: main -->