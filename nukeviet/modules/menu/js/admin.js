/* *
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 3 / 25 / 2010 18 : 6
 */

function nv_link1(alias, module)
{
    var nv_timer = nv_settimeout_disable('item_name_' + alias, 2000);
    var new_status = document.getElementById('item_name_' + alias).options[document.getElementById('item_name_' + alias).selectedIndex].value;
    if(new_status != 0)
    {
        $('input#module').val(module);
        $('input#op').val(new_status);
        $('input#link').val(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + module + "&" + nv_fc_variable + "=" + new_status);
        var new_text = document.getElementById('item_name_' + alias).options[document.getElementById('item_name_' + alias).selectedIndex].text;
        $('input#title').val(trim(new_text));
    }
    return;
}

// ---------------------------------------

function nv_link(module)
{
    var nv_timer = nv_settimeout_disable('module_name_' + module, 2000);
    var new_status = document.getElementById('module_name_' + module).options[document.getElementById('module_name_' + module).selectedIndex].value;
    var new_text = document.getElementById('module_name_' + module).options[document.getElementById('module_name_' + module).selectedIndex].text;
	
    $('input#title').val(trim(new_text));
    if(new_status != 0)
    {
        $('input#link').val(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + new_status);
        $('input#module').val(new_status);
        nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add_menu&action=1&module=' + new_status, 'thu', '');
    }
    else
    {
        $('input#link').val('');
        $('#thu').hide();
    }
}

function nv_link2(blog_menu)
{
    var nv_timer = nv_settimeout_disable('item_menu_' + blog_menu, 2000);
    var new_status = document.getElementById('item_menu_' + blog_menu).options[document.getElementById('item_menu_' + blog_menu).selectedIndex].value;
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add_menu&item=1&mid=' + new_status, 'parentid', '');
}

function nv_menu_delete(id, num)
{
    if(num == 0)
    {
        if(confirm(nv_is_del_confirm[0]))
        {
            nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&del=1&id=' + id, '', 'nv_menu_delete_result');
        }
    }
    else
    {
        alert(block + " " + num + block2);
    }
    return false;
}

function nv_menu_delete_result(res)
{
    var r_split = res.split("_");

    if(r_split[0] == 'OK')
    {
        window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main';
    }
    else
    {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}

function nv_chang_weight_item(id, mid, parentid)
{
    var nv_timer = nv_settimeout_disable('change_weight_' + id, 3000);
    var new_weight = document.getElementById('change_weight_' + id).options[document.getElementById('change_weight_' + id).selectedIndex].value;
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight_row&id=' + id + '&mid=' + mid + '&parentid=' + parentid + '&new_weight=' + new_weight + '&num=' + nv_randomPassword(8), '', 'nv_menu_item_delete_result');

    return;
}

function nv_menu_item_delete(id, mid, parentid, num)
{
    if(num)
    {
        alert(cat + num + caton);
    }
    else if(confirm(nv_is_del_confirm[0]))
    {
        nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_row&id=' + id + '&parentid=' + parentid + '&mid=' + mid, '', 'nv_menu_item_delete_result');
    }

    return false;
}

function nv_menu_item_delete_result(res)
{
    var r_split = res.split("_");
    if(r_split[0] == 'OK')
    {
        window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add_menu&mid=' + r_split[2] + '&parentid=' + r_split[3];
    }
    else
    {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}