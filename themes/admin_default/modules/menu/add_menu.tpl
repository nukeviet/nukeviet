<!-- BEGIN: main -->
<script type="text/javascript">
var cat = '{LANG.cat}';
var caton = '{LANG.caton}';
</script>
<strong><a href="{link_menu}">{LANG.menu}</a></strong>
<!-- BEGIN: title -->
>> <strong><a href="{link_title}">{LANG.back}</strong></a>
<!-- END: title -->
<!-- BEGIN: table -->
<table class="tab1">
    <thead>
        <tr align="center">
            <td style="width:40px"><strong>{LANG.number}</strong></td>
            <td><strong>{LANG.title}</strong></td>
            <td><strong>{LANG.link}</strong></td>
            <td><strong>{LANG.name_block}</strong></td>
            <td style="width:100px"><strong>{LANG.action}</strong></td>
        </tr>
    </thead>
    <!-- BEGIN: loop1 -->
    <tbody {ROW.class}>
        <tr>
            <td align="center">
            <select id="change_weight_{ROW.id}" onchange="nv_chang_weight_item('{ROW.id}','{ROW.mid}','{ROW.parentid}','weight');">
                <!-- BEGIN: weight -->
                <option value="{stt}" {select}>{stt}</option>
                <!-- END: weight -->
            </select></td>
            <td><a href="{ROW.url_title}"><strong>{ROW.title} </strong></a><!-- BEGIN: sub --> (<span class="requie">{ROW.sub} {LANG.sub_menu}</span>)<!-- END: sub --></td>
            <td>{ROW.link}</td>
            <td>{ROW.name_block}</td>
            <td align="center"><span class="edit_icon"><a href="{ROW.edit_url}">{LANG.edit}</a></span>&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_menu_item_delete({ROW.id},{ROW.mid},{ROW.parentid},{ROW.nu});">{LANG.delete}</a></span></td>
        </tr>
    </tbody>
    <!-- END: loop1 -->
</table>
<!-- END: table -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
    <blockquote class="error">
        <span>{ERROR}</span>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form id="edit" action="{FORM_ACTION}" method="post">
    <input type="hidden" name="id" value="{DATA.id}">
    <input type="hidden" name="mid" value="{DATA.mid}">
    <input type="hidden" name="pa" value="{DATA.parentid}">
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:150px"><strong>{LANG.name_block}</strong></td>
                <td style="width:300px">
					<select name="item_menu" id="item_menu_{key}" onchange="nv_link2('{key}');">
						<!-- BEGIN: loop -->
						<option value="{key}" {select}>{val}</option>
						<!-- END: loop -->
					</select>
				</td>
				<td></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>{LANG.cats}</strong></td>
                <td>
					<select name="parentid" id="parentid">
						<!-- BEGIN: cat -->
						<option value="{cat.key}"{selected}>{cat.title}</option>
						<!-- END: cat -->
					</select>
				</td>
				<td></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>{LANG.chomodule}</strong></td>
                <td>
					<select name="module_name" id="module_name_{module.key}" onchange="nv_link('{module.key}');">
						<option value="0">{LANG.cho_module}</option>
						<!-- BEGIN: module --><option value="{module.key}"{module.selected}>{module.title}</option>
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
						<!-- END: link -->
					</span>
				</td>
				<td></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>{LANG.title}</strong></td>
                <td><input type="text" name="title" id="title" class="txt-full" value="{DATA.title}"/></td>
				<td></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>{LANG.link}</strong></td>
                <td><input type="text" name="link" class="txt-full" value="{DATA.link}" id="link"/></td>
				<td></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>{LANG.note}</strong></td>
                <td><input type="text" name="note" class="txt-full" value="{DATA.note}"/></td>
				<td></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="vertical-align:top"><strong> {LANG.who_view}</strong></td>
                <td>
					<select name="who_view">
						<!-- BEGIN: who_view -->
						<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
						<!-- END: who_view -->
					</select><!-- BEGIN: group_view_empty -->
					<br />
					<strong>{LANG.groups}</strong>
					<div class="hr"></div>
					<!-- BEGIN: groups_view -->
					<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} />
					{GROUPS_VIEW.title}
					<br />
					<!-- END: groups_view --><!-- END: group_view_empty -->
				</td>
				<td></td>
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
				<td></td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td><strong>{LANG.add_type_active}</strong></td>
                <td>
					<select name="active_type" class="txt-full">
						<!-- BEGIN: active_type --><option value="{ACTIVE_TYPE.key}"{ACTIVE_TYPE.selected}>{ACTIVE_TYPE.title}</option>
						<!-- END: active_type -->
					</select>
				</td>
				<td>{LANG.add_type_active_note}</td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td><strong>{LANG.add_type_css}</strong></td>
                <td><input class="txt-full" type="text" name="css" value="{DATA.css}"/></td>
				<td>{LANG.add_type_css_info}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <center>
        <input name="submit1" type="submit" value="{LANG.save}" />
    </center>
</form>
<!-- END: main -->