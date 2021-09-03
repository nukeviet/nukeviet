<!-- BEGIN: main -->
<h2 class="margin-bottom-lg margin-top-lg">{LANG.user_info}</h2>
<div class="m-bottom clearfix">
    <figure class="avatar left">
        <div style="width:80px;">
            <p class="text-center"><img src="{SRC_IMG}" alt="{USER.username}" title="{USER.username}" width="80" class="img-thumbnail bg-gainsboro m-bottom" /></p>
        </div>
    </figure>
    <div>
        <ul class="nv-list-item xsm">
            <li><em class="fa fa-angle-right">&nbsp;</em> {LANG.account2}: <strong>{USER.username}</strong><!-- BEGIN: viewemail --> ({USER.email})<!-- END: viewemail --></li>
            <li><em class="fa fa-angle-right">&nbsp;</em> {LANG.last_login}: {USER.last_login}</li>
        </ul>
        <!-- BEGIN: for_admin -->
        <div class="margin-top-lg">
            <!-- BEGIN: edit --><a href="{USER.link_edit}"><i class="fa fa-edit"></i> {GLANG.edit}</a>&nbsp;&nbsp;<!-- END: edit -->
            <!-- BEGIN: delete --><a href="#" data-toggle="admindeluser" data-userid="{USER.userid}" data-link="{USER.link_delete}" data-back="{USER.link_delete_callback}"><i class="fa fa-trash-o"></i> {GLANG.delete}</a><!-- END: delete -->
        </div>
        <!-- END: for_admin -->
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <colgroup>
            <col style="width:30%" />
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
<!-- END: main -->