<!-- BEGIN: main -->
<div id="users">
    <form action="{FORM_ACTION}" method="post">
    	<div class="table-responsive">
	        <table class="table table-striped table-bordered table-hover">
	            <tbody>
	                <tr>
	                    <td class="w350">{LANG.config_nummain}</td>
	                    <td>
							<input class="form-control w200 pull-left" type="text" name="nummain" value="{DATA.nummain}"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_numsub}</td>
	                    <td>
							<input class="form-control w200 pull-left" type="text" name="numsub" value="{DATA.numsub}"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_typeview}</td>
	                    <td>
	                        <select class="form-control w250 pull-left" name="typeview">
	                            <!-- BEGIN: typeview -->
	                            <option value="{typeview.id}"{typeview.selected}> {typeview.title}</option>
	                            <!-- END: typeview -->
	                        </select>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_down_in_home}</td>
	                    <td>
							<label><input type="checkbox" name="down_in_home" value="1" {DATA.down_in_home} />{LANG.config_down_in_home_note}</label>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_detail_other}</td>
	                    <td>
	                    	<!-- BEGIN: detail_other -->
							<label><input type="checkbox" name="detail_other[]" value="{OTHER.key}" {OTHER.checked} />{OTHER.value}</label>&nbsp;&nbsp;&nbsp;
							<!-- END: detail_other -->
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_detail_other_numlinks}</td>
	                    <td>
							<input class="form-control w200 pull-left" type="text" name="other_numlinks" value="{DATA.other_numlinks}"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_detail_hide_empty_field}</td>
	                    <td>
							<input type="checkbox" name="detail_hide_empty_field" value="1"{DATA.detail_hide_empty_field}/>
	                    </td>
	                </tr>
                    <tr>
                        <td>{LANG.config_show_link_detailpage}</td>
                        <td>
                            <div><label><input type="checkbox" name="detail_show_link_cat" value="1"{DATA.detail_show_link_cat}/>&nbsp;{LANG.config_show_link_detailpage1}</label></div>
                            <div><label><input type="checkbox" name="detail_show_link_area" value="1"{DATA.detail_show_link_area}/>&nbsp;{LANG.config_show_link_detailpage2}</label></div>
                            <div><label><input type="checkbox" name="detail_show_link_subject" value="1"{DATA.detail_show_link_subject}/>&nbsp;{LANG.config_show_link_detailpage3}</label></div>
                            <div><label><input type="checkbox" name="detail_show_link_signer" value="1"{DATA.detail_show_link_signer}/>&nbsp;{LANG.config_show_link_detailpage4}</label></div>
                        </td>
                    </tr>
	                <tr>
	                    <td>{LANG.config_detail_pdf_quick_view}</td>
	                    <td>
							<input type="checkbox" name="detail_pdf_quick_view" value="1"{DATA.detail_pdf_quick_view}/>
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