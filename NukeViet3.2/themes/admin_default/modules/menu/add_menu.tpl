<!-- BEGIN: main -->
<!-- BEGIN: title -->
<strong><a href="{link_title}">{LANG.back}</strong></a>
<!-- END: title -->
<!-- BEGIN: table -->
<table summary="" class="tab1">
    <thead>
        <tr align="center">
            <td>
                <strong>{LANG.number}</strong>
            </td>
            <td>
                <strong>{LANG.title}</strong>
            </td>
            <td>
                <strong>{LANG.link}</strong>
            </td>
            <td>
                <strong>{LANG.name_block}</strong>
            </td>                   
                        
            <td>
                 <strong>{LANG.action}</strong>
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop1 -->
    <tbody {DATA.class}>
        <tr>
            <td align="center">
            	<select id="change_weight_{ROW.id}" onchange="nv_chang_weight_item('{ROW.id}','{ROW.mid}','{ROW.parentid}','weight');">
                <!-- BEGIN: weight -->
						<option value="{stt}" {select}>{stt}</option>
				<!-- END: weight -->
				</select>
            </td>
            <td>
            	<a href="{ROW.url_title}"><strong>{ROW.title} </strong></a>        
            </td>
           	<td>
                {ROW.link}                
            </td>
            <td>
                {ROW.name_block}                
            </td>        
            <td align="center">
                <span class="edit_icon"><a href="{ROW.edit_url}">{LANG.edit}</a></span>&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_link_del({ROW.id},{ROW.mid},{ROW.parentid},{ROW.nu});">{LANG.delete}</a></span>
            </td>
            <script type="text/javascript">
            	var cat= '{LANG.cat}';
            	var caton= '{LANG.caton}';
            </script>
        </tr>
    </tbody>
    <!-- END: loop1 -->
   
</table>
<!-- END: table -->


<!-- BEGIN: error -->
<div class="quote" style="width:780px;">
    <blockquote class="error">
        <span>{ERROR}</span>
    </blockquote>
</div>
<div class="clear">
</div>
<!-- END: error -->
<form enctype="multipart/form-data" action="{FORM_ACTION}" method="post">
<table summary="" class="tab1">
	<tbody>
		<tr>
			<td><strong>{LANG.name_block}</strong></td>
			<td>
                    <select name="item_menu" id="item_menu_{key}" onchange="nv_link2('{key}');">
                    <!-- BEGIN: loop -->
						<option value="{key}" {select}>{val}</option>
					<!-- END: loop -->
                    </select>
            </td>
		</tr>
	</tbody>
	
	<tbody>
	<tr>
		<td><strong>{LANG.title}</strong></td>
		<td><input type="text" name="title" style="width: 550px" value="{DATA.title}">
		<input type="hidden" name="id" style="width: 550px" value="{DATA.id}">
		<input type="hidden" name="mid" style="width: 550px" value="{DATA.mid}">
		<input type="hidden" name="pa" style="width: 550px" value="{DATA.parentid}">
		</td>
	</tr>
	</tbody>
	
	<tbody>
	<tr>
		<td><strong>{LANG.cats}</strong></td>
		<td>
		<select name="parentid" id="parentid">
                <!-- BEGIN: cat -->
				<option value="{cat.key}"{selected}>{cat.title}</option>
				<!-- END: cat -->				
		</select></td>
	</tr>
	</tbody>
	
	<tbody class="second">
		<tr >
			<td><strong>{LANG.chomodule}</strong></td>
			<td>
                    <select name="module_name" id="module_name_{module.key}" onchange="nv_link('{module.key}');">
                    	<option value="0">{LANG.cho_module}</option>
						<!-- BEGIN: module -->
						<option value="{module.key}"{module.selected}>{module.title}</option>
						<!-- END: module -->
					</select>
	<span id="thu">
	<!-- BEGIN: link -->				
			<select name="op" id="item_name_{item.alias}" onchange="nv_link1('{item.alias}','{item.module}');">
                 	<option value="">{LANG.item_menu}</option>
					 <!-- BEGIN: item -->
						<option value="{item.alias}"{item.selected}>{item.title}</option>
						<!-- END: item -->
					</select>
                </td>
		</tr>
	</tbody>
	<!-- END: link -->
	</span>
	<span id="link_end">
	<tbody class="second">
		<tr>
			<td><strong>{LANG.link}</strong></td>
			<td><input type="text" name="link" style="width: 550px" value="{DATA.link}" id="link">			
			</td>
		</tr>
	</tbody>
	</span>
	<tbody class="second">
		<tr>
			<td><strong>{LANG.note}</strong></td>
			<td><input type="text" name="note" style="width: 550px" value="{DATA.note}"></td>
		</tr>
	</tbody>
	
	<tbody>
		<tr>
                <td style="vertical-align:top">
                   <strong> {LANG.who_view}</strong>
                </td>
                <td>
                    <select name="who_view">
                        <!-- BEGIN: who_view -->
                        <option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
                        <!-- END: who_view -->
                    </select>
                    <!-- BEGIN: group_view_empty -->
                    <br />
                    <strong>{LANG.groups}</strong><br />
                        <!-- BEGIN: groups_view -->
                        <input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}<br />
                        <!-- END: groups_view -->
                    <!-- END: group_view_empty -->
                </td>
        </tr>
	</tbody>	
	<tbody class="second">
		<tr>
			<td><strong>{LANG.target}</strong></td>
			<td>
             <select name="target">
                <!-- BEGIN: target -->
                    <option value="{target.key}"{target.selected}>{target.title}</option>
                <!-- END: target -->
             </select>
            </td>
		</tr>
	</tbody>		
		
</table>

<br/>
<center>
    <input name="submit1" type="submit" value="{LANG.save}" />
</center>

<!-- END: main -->