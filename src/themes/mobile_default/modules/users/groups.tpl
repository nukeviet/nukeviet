<!-- BEGIN: main -->
<h2 class="margin-bottom-lg margin-top-lg">{LANG.group_manage}</h2>
<div id="pageContent">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col span="4" />
            <thead>
                <tr>
                    <th> {LANG.title} </th>
                    <th class="text-center"> {LANG.add_time} </th>
                    <th class="text-center"> {LANG.exp_time} </th>
                    <th class="text-center"> {LANG.users} </th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td><a title="{LANG.users}" href="{LOOP.link_userlist}">{LOOP.title}</a></td>
                    <td class="text-center">{LOOP.add_time}</td>
                    <td class="text-center">{LOOP.exp_time}</td>
                    <td class="text-center">{LOOP.number}</td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</div>
<ul class="nav navbar-nav">
    <!-- BEGIN: navbar --><li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
</ul>
<!-- END: main -->
