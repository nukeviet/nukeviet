<!-- BEGIN: main -->
<div id="users">
    <form class="form-inline" action="{FORM_ACTION}" method="post">
    	<div class="table-responsive">
	        <table class="table table-striped table-bordered table-hover">
	            <tbody>
	                <tr>
	                    <td style="width:200px">{LANG.config_nummain}</td>
	                    <td>
							<input class="form-control" type="text" style="width:350px" name="nummain" value="{DATA.nummain}"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_numsub}</td>
	                    <td>
							<input class="form-control" type="text" style="width:350px" name="numsub" value="{DATA.numsub}"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_typeview}</td>
	                    <td>
	                        <select class="form-control" name="typeview">
	                            <!-- BEGIN: typeview -->
	                            <option value="{typeview.id}"{typeview.selected}> {typeview.title}</option>
	                            <!-- END: typeview -->
	                        </select>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_down_in_home}</td>
	                    <td>
							<input type="checkbox" name="down_in_home" value="1" {DATA.down_in_home} />
	                    </td>
	                </tr>
	            </tbody>
	        </table>
	    </div>
        <div class="text-center">
            <input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}" />
        </div>
    </form>
</div><!-- END: main -->