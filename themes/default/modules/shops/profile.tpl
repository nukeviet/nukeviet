<!-- BEGIN: main -->
<div class="profile">
    <div style="margin-bottom:10px">
		<a class="profile_tab" href="{PROFILE_URL}" >{LANG.profile_manage_info}</a>
		<a class="profile_tab" href="{URL_MYPRO}">{LANG.profile_manage_myproducts}</a>
		<a class="profile_tab" href="{USER_LOGOUT}">{LANG.profile_user_logout}</a>
	</div>
	<div class="profile_info">
	<!-- BEGIN: user -->
	<div class="allinfo" style="margin-top:2px">
		<strong>{LANG.profile_user_name} :</strong> <span> {USER.full_name}</span> <br />
		<strong>{LANG.profile_company_address} : </strong><span> {USER.location}</span> <br />
		<strong>{LANG.profile_username} : </strong> <span> {USER.username}</span> &nbsp; <strong>{LANG.profile_user_email} : </strong> <span> {USER.email}</span> <br /> 
		<strong>{LANG.profile_user_last_online} : </strong> <span> {USER.last_login}</span><br />
		<div style="float:right;">
			<a class="profile_tab" href="{USER_EDIT}">{LANG.profile_user_edit_info}</a>
			<a class="profile_tab" href="{USER_CHANGE_PASS}">{LANG.profile_user_change_pass}</a>
		</div>
		<div style="clear:both"></div>
	</div>
	<!-- END: user -->
	</div>
</div>
<!-- END: main -->