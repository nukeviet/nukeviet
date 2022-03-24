<!-- BEGIN: main -->
<div class="page">
    <h2 class="margin-bottom-lg margin-top-lg">{LANG.listusers}</h2>
    <div class="table-responsive">
    	<table class="table table-bordered table-striped">
    		<colgroup>
    			<col/>
    			<col style="width:15%"/>
                <col style="width:15%"/>
    		</colgroup>
    		<thead>
    			<tr class="bg-lavender">
    				<td><a href="{username}" class="black text-uppercase">{LANG.account}</a></td>
    				<td class="text-center"><a href="{gender}" class="black text-uppercase">{LANG.gender}</a></td>
    				<td class="text-center"><a href="{regdate}" class="black text-uppercase">{LANG.regdate}</a></td>
    			</tr>
    		</thead>
    		<tbody>
    			<!-- BEGIN: list -->
    			<tr>
    				<td><a href="{USER.link}">{USER.username} <!-- BEGIN: fullname -->&nbsp;({USER.full_name})<!-- END: fullname --></a></td>
    				<td class="text-center">{USER.gender}</td>
    				<td class="text-center">{USER.regdate}</td>
    			</tr>
    			<!-- END: list -->
    		</tbody>
    		<!-- BEGIN: generate_page -->
    		<tfoot>
    			<tr>
    				<td colspan="3">{GENERATE_PAGE}</td>
    			</tr>
    		</tfoot>
    		<!-- END: generate_page -->
    	</table>
    </div>

    <ul class="nav navbar-nav">
        <!-- BEGIN: navbar --><li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
    </ul>
</div>
<!-- END: main -->