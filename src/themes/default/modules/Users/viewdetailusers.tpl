<!-- BEGIN: main -->
<div class="page">
    <h2 class="margin-bottom-lg margin-top-lg">{LANG.user_info}</h2>
    <div class="row m-bottom">
    	<div class="col-sm-6 text-center">
    		<p><img src="{SRC_IMG}" alt="{USER.username}" class="img-thumbnail bg-gainsboro"/></p>
    		{LANG.img_size_title}
    	</div>
    	<div class="col-sm-18">
    		<ul class="nv-list-item">
    			<li><em class="fa fa-angle-right">&nbsp;</em> {LANG.account2}: <strong>{USER.username}</strong><!-- BEGIN: viewemail --> ({USER.email})<!-- END: viewemail --></li>
    			<li><em class="fa fa-angle-right">&nbsp;</em> {LANG.last_login}: {USER.last_login}</li>
    		</ul>
    	</div>
    </div>
    
    <!-- BEGIN: for_admin -->
    <div class="m-bottom clearfix">
        <div class="pull-right">
            {LANG.for_admin}: 
            <!-- BEGIN: edit --><a href="{USER.link_edit}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> {GLANG.edit}</a><!-- END: edit -->
            <!-- BEGIN: delete --><a href="#" class="btn btn-danger btn-xs" data-toggle="admindeluser" data-userid="{USER.userid}" data-link="{USER.link_delete}" data-back="{USER.link_delete_callback}"><i class="fa fa-trash-o"></i> {GLANG.delete}</a><!-- END: delete -->
        </div>
    </div>
    <!-- END: for_admin -->
    
    <div class="table-responsive">
    	<table class="table table-bordered table-striped">
            <colgroup>
    			<col style="width:30%"/>
    		</colgroup>
    		<tbody>
    			<tr>
    				<th>{LANG.name}</th>
    				<td>{USER.full_name}</td>
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
    				<th>{LANG.regdate}</th>
    				<td>{USER.regdate}</td>
    			</tr>
    			<!-- BEGIN: field -->
    			<!-- BEGIN: loop -->
    			<tr>
    				<th>{FIELD.title}</th>
    				<td>{FIELD.value}</td>
    			</tr>
    			<!-- END: loop -->
    			<!-- END: field -->
    		</tbody>
    	</table>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{URL_HREF}memberlist"><em class="fa fa-caret-right margin-right-sm"></em>{LANG.listusers}</a></li>
        <!-- BEGIN: navbar --><li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
    </ul>
</div>
<!-- END: main -->