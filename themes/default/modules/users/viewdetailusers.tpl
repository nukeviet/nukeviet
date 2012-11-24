<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.user_info}</h2>
    <div class="uinfo"> 
       	
        <div class="uimg">
            <img src="{SRC_IMG}" alt="" /><br />
            <span style="font-size:11px; color:#999">{LANG.img_size_title}</span>
        </div>
       {LANG.account2}: <strong>{USER.username}</strong> <!-- BEGIN: viewemail -->({USER.email})<!-- END: viewemail --><br />
       {LANG.last_login}: {USER.last_login}<br /> 
             
        <div class="clear"></div>      
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
        	   <dt class="fl" style="width:48%;">{LANG.regdate}:</dt>
               <dd class="fl">{USER.regdate}</dd>
            </dl>
        </div>      
    </div>
</div>
<!-- END: main -->