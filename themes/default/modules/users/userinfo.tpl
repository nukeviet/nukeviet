<!-- BEGIN: main -->
<div class="page panel panel-default">
    <div class="panel-body">
        <h2 class="margin-bottom-lg">{LANG.user_info}</h2>
        <div class="row">
            <figure onclick="changeAvatar(1);" class="avatar left pointer">
                <div style="width:80px;">
                    <p class="text-center"><img src="{IMG.src}" alt="{USER.username}" title="{USER.username}" width="80" class="img-thumbnail bg-gainsboro m-bottom"/></p>
                    <figcaption>{IMG.title}</figcaption>
                </div>
            </figure>
        	<div>
        		<ul class="nv-list-item xsm">
        			<li><em class="fa fa-chevron-right ">&nbsp;</em> {LANG.account2}: <strong>{USER.username}</strong> ({USER.email})</li>
        			<li><em class="fa fa-chevron-right ">&nbsp;</em> {USER.current_mode}</li>
        			<li><em class="fa fa-chevron-right ">&nbsp;</em> {LANG.current_login}: {USER.current_login}</li>
        			<li><em class="fa fa-chevron-right ">&nbsp;</em> {LANG.ip}: {USER.current_ip}</li>
        		</ul>
        	</div>
        </div>
    </div>
</div>
<div class="page panel panel-default">
    <div class="panel-body">
        <!-- BEGIN: change_login_note -->
        <div class="alert alert-danger">
        	<em class="fa fa-exclamation-triangle ">&nbsp;</em> {USER.change_name_info}
        </div>
        <!-- END: change_login_note -->
        <!-- BEGIN: pass_empty_note -->
        <div class="alert alert-danger">
        	<em class="fa fa-exclamation-triangle ">&nbsp;</em> {USER.pass_empty_note}
        </div>
        <!-- END: pass_empty_note -->
        <!-- BEGIN: question_empty_note -->
        <div class="alert alert-danger">
        	<em class="fa fa-exclamation-triangle ">&nbsp;</em> {USER.question_empty_note}
        </div>
        <!-- END: question_empty_note -->
        <div class="table-responsive">
        	<table class="table table-bordered table-striped">
        		<tbody>
        			<tr>
        				<th>{LANG.name}</th>
        				<td>
        				    {USER.full_name}
        				</td>
        			</tr>
        			<tr>
        				<th>{LANG.birthday}</th>
        				<td>{USER.birthday}</td>
        			</tr>
        			<tr>
        				<th>{LANG.gender}</th>
        				<td>{USER.gender}</td>
        			</tr>
        			<tr>
        				<th>{LANG.showmail}</th>
        				<td>{USER.view_mail}</td>
        			</tr>
        			<tr>
        				<th>{LANG.regdate}</th>
        				<td>{USER.regdate}</td>
        			</tr>
        			<tr>
        				<th>{LANG.st_login2}</th>
        				<td>{USER.st_login}</td>
        			</tr>
        			<tr>
        				<th>{LANG.last_login}</th>
        				<td>{USER.last_login}</td>
        			</tr>
        		</tbody>
        	</table>
        </div>
    </div>
</div>
<!-- END: main -->