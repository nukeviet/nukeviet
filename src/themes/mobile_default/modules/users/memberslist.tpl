<!-- BEGIN: main -->
<div class="page">
    <h2>{LANG.listusers}</h2>
    <div class="table-responsive">
    	<table class="table table-bordered table-striped">
    		<colgroup>
                <col/>
    			<col style="width:15%"/>
    			<col style="width:15%"/>
    		</colgroup>
    		<thead>
    			<tr>
    				<td><a href="{username}">{LANG.account}</a></td>
    				<td class="text-center"><a href="{gender}">{LANG.gender}</a></td>
    				<td class="text-center"><a href="{regdate}">{LANG.regdate}</a></td>
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
</div>
<!-- END: main -->