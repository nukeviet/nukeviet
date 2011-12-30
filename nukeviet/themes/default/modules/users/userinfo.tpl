<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.user_info}</h2>
    <div style="padding-bottom:10px">
        <div class="utop">
            <span class="topright">
                <a href="{URL_HREF}editinfo">{LANG.editinfo}</a> 
                <strong>&middot;</strong> <a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
                <!-- BEGIN: allowopenid --><strong>&middot;</strong> <a href="{URL_HREF}openid">{LANG.openid_administrator}</a><!-- END: allowopenid -->
                <!-- BEGIN: regroups --><strong>&middot;</strong><a href="{URL_HREF}regroups">{LANG.in_group}</a><!-- END: regroups -->
                <!-- BEGIN: logout --><strong>&middot;</strong> <a href="{URL_HREF}logout">{LANG.logout_title}</a><!-- END: logout -->
            </span>
        </div>
    <div class="clear"></div>
    </div>
    <div class="uinfo"> 
       	
        <div class="uimg">
            <img src="{SRC_IMG}" alt="" /><br />
            <span style="font-size:11px; color:#999">{LANG.img_size_title}</span>
        </div>
       {LANG.account2}: <strong>{USER.username}</strong> ({USER.email})<br />
       {USER.current_mode}<br />
       {LANG.current_login}: {USER.current_login}<br />
       {LANG.ip}: {USER.current_ip} 
             
        <div class="clear"></div>
        <!-- BEGIN: change_login_note -->
        <div id="info" style="padding-top:10px;padding-bottom:5px">
            <strong>&raquo; {USER.change_name_info}</strong>
        </div>
        <!-- END: change_login_note -->
        <!-- BEGIN: pass_empty_note -->
        <div id="info" style="padding-top:10px;padding-bottom:5px">
            <strong>&raquo; {USER.pass_empty_note}</strong>
        </div>
        <!-- END: pass_empty_note -->
        <!-- BEGIN: question_empty_note -->
        <div id="info" style="padding-top:10px;padding-bottom:5px">
            <strong>&raquo; {USER.question_empty_note}</strong>
        </div>
        <!-- END: question_empty_note -->        
        <div class="content">
            <dl class="clearfix gray">
        	   <dt class="fl" style="width:48%;">{LANG.name}:</dt>
               <dd class="fl">{USER.full_name}</dd>
            </dl>
            <dl class="clearfix">
        	   <dt class="fl" style="width:48%;">{LANG.birthday}:</dt>
               <dd class="fl">{USER.birthday}</dd>
            </dl>
            <dl class="clearfix gray">
        	   <dt class="fl" style="width:48%;">{LANG.gender}:</dt>
               <dd class="fl">{USER.gender}</dd>
            </dl>
            <dl class="clearfix">
        	   <dt class="fl" style="width:48%;">{LANG.address}:</dt>
               <dd class="fl">{USER.location}</dd>
            </dl>
            <dl class="clearfix gray">
        	   <dt class="fl" style="width:48%;">{LANG.website}:</dt>
               <dd class="fl">{USER.website}</dd>
            </dl>
            <dl class="clearfix">
        	   <dt class="fl" style="width:48%;">{LANG.yahoo}:</dt>
               <dd class="fl">{USER.yim}</dd>
            </dl>
            <dl class="clearfix gray">
        	   <dt class="fl" style="width:48%;">{LANG.phone}:</dt>
               <dd class="fl">{USER.telephone}</dd>
            </dl>
            <dl class="clearfix">
        	   <dt class="fl" style="width:48%;">{LANG.fax}:</dt>
               <dd class="fl">{USER.fax}</dd>
            </dl>
            <dl class="clearfix gray">
        	   <dt class="fl" style="width:48%;">{LANG.mobile}:</dt>
               <dd class="fl">{USER.mobile}</dd>
            </dl>
            <dl class="clearfix">
        	   <dt class="fl" style="width:48%;">{LANG.showmail}:</dt>
               <dd class="fl">{USER.view_mail}</dd>
            </dl>
            <dl class="clearfix gray">
        	   <dt class="fl" style="width:48%;">{LANG.regdate}:</dt>
               <dd class="fl">{USER.regdate}</dd>
            </dl>
            <dl class="clearfix">
        	   <dt class="fl" style="width:48%;">{LANG.st_login2}:</dt>
               <dd class="fl">{USER.st_login}</dd>
            </dl>
            <dl class="clearfix gray">
        	   <dt class="fl" style="width:48%;">{LANG.last_login}:</dt>
               <dd class="fl">{USER.last_login}</dd>
            </dl>
        </div>      
    </div>
</div>
<!-- END: main -->