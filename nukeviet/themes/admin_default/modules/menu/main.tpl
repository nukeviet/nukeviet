<!-- BEGIN: first -->
<table class="tab1 fixtab">
	<tbody>
		</tr>
			<td>{LANG.main_note_0} <a href="{ADD_NEW}" title="{LANG.add_menu}"><strong>{LANG.here}</strong></a> {LANG.main_note_1}</td>
		</tr>
	</tbody>
</table>
<!-- BEGIN: table -->
<script type="text/javascript">
	var block = '{LANG.block}';
	var block2 = '{LANG.block2}';
</script>
<table summary="" class="tab1">
    <thead>
        <tr align="center">
            <td style="width:50px">
                <strong>{LANG.number}</strong>
            </td>
            <td>
                <strong>{LANG.name_block}</strong>
            </td>
            <td>
                <strong>{LANG.menu}</strong>
            </td>
            <td>
                <strong>{LANG.menu_description}</strong>
            </td>                   
                        
            <td style="width:100px">
                 <strong>{LANG.action}</strong>
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop1 -->
    <tbody {ROW.class}>
        <tr>
            <td align="center">
                {ROW.nb}
            </td>
            <td>
            	<a href="{ROW.link_view}" title="{ROW.title}"><strong>{ROW.title}</strong></a>               
            </td>
           	<td>
                {ROW.menu_item}                
            </td>
           	<td>
                {ROW.description}                
            </td>        
            
             <td align="center">
                <span class="edit_icon"><a href="{ROW.edit_url}">{LANG.edit}</a></span>&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_menu_delete({ROW.id},{ROW.num});">{LANG.delete}</a></span>
            </td>
           </tr>
    </tbody>
    <!-- END: loop1 -->
    <!-- BEGIN: generate_page -->
	<tfoot>
		<tr>
			<td colspan="5">
				{GENERATE_PAGE}
			</td>
		</tr>
	</tfoot>
    <!-- END: generate_page -->
</table>
<!-- END: table -->
<!-- END: first -->

<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
    <blockquote class="error">
        <span>{ERROR}</span>
    </blockquote>
</div>
<div class="clear">
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <input type="hidden" name ="id" value="{DATAFORM.id}" />
	<input name="save" type="hidden" value="1" />
    <table summary="" class="tab1">
        <tbody>
            <tr>
                <td align="right">
                    <strong>{LANG.name_block}: </strong>
                </td>
                <td>
                    <input style="width: 650px" name="title" type="text" value="{DATAFORM.title}" maxlength="255" />
                </td>
            </tr>
        </tbody>        
        <tbody class="second">
            <tr>
                <td align="right">
                    <strong>{LANG.menu_description}: </strong>
                </td>
                <td>
                    <input style="width: 650px" name="description" type="text" value="{DATAFORM.description}" maxlength="255" />
                </td>
            </tr>
        </tbody>
		<tfoot>
			<tr>
				<td align="center" colspan="2">
					<input name="submit1" type="submit" value="{LANG.save}" />
				</td>
			</tr>
		</tfoot>
    </table>
</form>
<!-- END: main -->
