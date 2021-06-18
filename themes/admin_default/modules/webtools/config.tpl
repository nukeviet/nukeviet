<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
    	<table class="table table-striped table-bordered table-hover">
    		<col style="width:50%" />
    		<col style="width:50%" />
    		<tfoot>
    			<tr>
    				<td class="text-center" colspan="2"><input type="hidden" name="checkss" value="{CHECKSS}" /><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary"/></td>
    			</tr>
    		</tfoot>
    		<tbody>
    			<tr>
    				<td><strong>{LANG.autocheckupdate}</strong></td>
    				<td><input type="checkbox" value="1" name="autocheckupdate" {AUTOCHECKUPDATE} /></td>
    			</tr>
    			<tr>
    				<td><strong>{LANG.updatetime}</strong></td>
    				<td>
    				<select name="autoupdatetime" class="form-control w100 pull-left">
    					<!-- BEGIN: updatetime -->
    					<option value="{VALUE}" {SELECTED}>{TEXT} </option>
    					<!-- END: updatetime -->
    				</select>
    				<span class="text-middle">&nbsp;({LANG.hour})</span></td>
    			</tr>
    		</tbody>
    	</table>
    </div>
</form>
<!-- END: main -->