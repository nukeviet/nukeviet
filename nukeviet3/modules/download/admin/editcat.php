<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$catid = $nv_Request->get_int( 'cid', 'post,get', 0 );
if ( $nv_Request->get_int( 'save', 'post' ) )
{
    $title = filter_text_input( 'title', 'post', '', 1 );
    $description = filter_text_input( 'description', 'post', '', 1 );
    $catparent = $nv_Request->get_int( 'catparent', 'post' );
    $sql = "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE title LIKE '" . $db->dblikeescape( $title ) . "' AND parentid='" . $catparent . "' AND cid!='" . $catid . "'";
    $alreadytitle = $db->sql_numrows( $db->sql_query( $sql ) );
    if ( ! $alreadytitle )
    {
        $active = $nv_Request->get_int( 'active', 'post', 0 );
        list( $parentid ) = $db->sql_fetchrow( $db->sql_query( "SELECT parentid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid='" . $catid . "'" ) );
        if ( $parentid != $catparent )
        {
            $numcat = $db->sql_numrows( $db->sql_query( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid='" . $catparent . "'" ) );
            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `title`=" . $db->dbescape( $title ) . ", `cdescription`= " . $db->dbescape( $description ) . ", `parentid`='" . $catparent . "', `weight`='" . ( $numcat + 1 ) . "', `active`='" . $active . "' WHERE cid='$catid'";
        }
        else
        {
            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `title`=" . $db->dbescape( $title ) . ", `cdescription`= " . $db->dbescape( $description ) . ", `parentid`='" . $catparent . "', `active`='" . $active . "' WHERE cid='$catid'";
        }
        $db->sql_query( $sql );
        #reupdate
        $result = $db->sql_query( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=" . $catparent . " ORDER BY weight ASC" );
        $i = 1;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET weight='" . $i . "' WHERE cid='" . $row['cid'] . "'";
            $result = $db->sql_query( $sql );
            $i ++;
        }
        if ( $result ) echo $lang_module['editcat_success'];
        else echo $lang_module['editcat_error'];
    }
    else
    {
        echo '
	<script type="text/javascript">
	alert("' . $lang_module['addcat_error_cat'] . '");
	</script>
		';
    }
}
else
{
    $cat = $nv_Request->get_int( 'cat', 'post,get' );
    $row = $db->sql_fetchrow( $db->sql_query( "SELECT cid, title, cdescription, active, parentid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid='$catid'" ) );
    $contents .= "<table class=\"tab1\" style='width:400px'>\n";
    $contents .= "<thead>\n";
    $contents .= "<tr>\n";
    $contents .= "<td colspan=\"2\">" . $lang_module['editcat_cat'] . "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $contents .= "<tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editcat_title'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['title'] . "' name='cattitle' style='width:290px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['editcat_parent'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<select name='catparent'>";
    $contents .= "<option value='0'>" . $lang_module['editcat_maincat'] . "</option>";
    $sql = "SELECT cid, title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid!=" . $catid . " AND parentid=0 ORDER BY weight";
    $result = $db->sql_query( $sql );
    while ( $subrow = $db->sql_fetchrow( $result ) )
    {
        $sel = ( $subrow['cid'] == $row['parentid'] ) ? ' selected' : '';
        $contents .= "<option value='" . $subrow['cid'] . "' " . $sel . ">" . $subrow['title'] . "</option>";
        $contents .= getsubcat( $subrow['cid'], $i = '-' );
    }
    $contents .= "</select>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['editcat_description'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<textarea cols='40' name='catdescription'>" . $row['cdescription'] . "</textarea>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['editcat_active'] . "</td>\n";
    $contents .= "<td>";
    $sel = ( $row['active'] == 1 ) ? ' checked' : '';
    $contents .= "<label><input type='checkbox' name='active' value='1' " . $sel . ">" . $lang_global['yes'] . "</label>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td colspan='2' style='padding-left:100px'>";
    $contents .= "<input type='button' name='confirm' value='" . $lang_module['editcat_save'] . "'>";
    $contents .= "<span name='notice' style='float:right;padding-right:50px;color:red;font-weight:bold'></span>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "</table>\n";
    $contents .= "
<script type='text/javascript'>
$(function(){
$('input[name=\"confirm\"]').click(function(){
	var title = $('input[name=\"cattitle\"]').val();
	if (title==''){
		alert('" . $lang_module['editcat_error_title'] . "');
		$('input[name=\"cattitle\"]').focus();
		return false;
	}
	var catparent = $('select[name=\"catparent\"]').val();
	var description = $('textarea[name=\"catdescription\"]').val();
	var active = $('input[name=\"active\"]').is(':checked')?1:0;
	$('input[name=\"confirm\"]').attr({'disabled':'disabled'});
	$('span[name=\"notice\"]').html('<img src=\"../images/load.gif\"> please wait...');
	$.ajax({	
		type: 'POST',
		url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=editcat',
		data: 'title='+ title + '&catparent='+catparent+'&description='+description+'&active='+active+'&save=1'+'&cid=" . $catid . "',
		success: function(data){				
			$('input[name=\"confirm\"]').attr({'disabled':''});
			$('span[name=\"notice\"]').html(data);
			window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&cid=" . $cat . "';
		}
	});
});
});
</script>
";
}
echo $contents;
?>