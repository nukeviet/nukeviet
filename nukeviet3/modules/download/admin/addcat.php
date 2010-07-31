<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if ( $nv_Request->get_int( 'save', 'post' ) )
{
    $title = filter_text_input( 'title', 'post', '', 1 );
    $description = filter_text_input( 'description', 'post', '', 1 );
    $catparent = $nv_Request->get_int( 'catparent', 'post' );
    $sql = "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE title LIKE '" . $db->dblikeescape( $title ) . "' AND parentid='" . $catparent . "'";
    $alreadytitle = $db->sql_numrows( $db->sql_query( $sql ) );
    if ( ! $alreadytitle )
    {
        $active = $nv_Request->get_int( 'active', 'post', 0 );
        $alias = change_alias( $title );
        if ( $catparent != 0 )
        {
            $numcat = $db->sql_numrows( $db->sql_query( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=" . $catparent . "" ) );
        }
        else
        {
            $numcat = $db->sql_numrows( $db->sql_query( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=0" ) );
        }
        $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_categories` (`cid`, `title`, `parentid`, `weight`, `active`, `alias`, `cdescription`) VALUES (NULL, " . $db->dbescape( $title ) . ", " . intval( $catparent ) . ", " . ( $numcat + 1 ) . ", '" . $active . "', " . $db->dbescape( $alias ) . ", " . $db->dbescape( $description ) . ")";
        $cid = $db->sql_query_insert_id( $sql );
        if ( $cid )
        {
            $numcat = $db->sql_numrows( $db->sql_query( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `alias`=" . $db->dbescape( $alias ) . "" ) );
            if ( $numcat > 1 )
            {
                $alias .= "-" . $cid;
                $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `alias`=" . $db->dbescape_string( $alias ) . " WHERE cid=" . $cid;
                $db->sql_query( $sql );
            }
            echo $lang_module['addcat_success'];
        }
        else
            echo $lang_module['addcat_error'];
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
    $catid = $nv_Request->get_int( 'cid', 'get' );
    $row = $db->sql_fetchrow( $db->sql_query( "SELECT cid, title, cdescription, active, parentid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid='$catid'" ) );
    $contents .= "<table class=\"tab1\" style='width:400px'>\n";
    $contents .= "<thead>\n";
    $contents .= "<tr>\n";
    $contents .= "<td colspan=\"2\">" . $lang_module['addcat_titlebox'] . "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $contents .= "<tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['addcat_title'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='' name='cattitle' style='width:290px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['addcat_par'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<select name='catparent'>";
    $contents .= "<option value='0'>" . $lang_module['addcat_maincat'] . "</option>";
    $sql = "SELECT cid, title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=0 ORDER BY weight";
    $result = $db->sql_query( $sql );
    while ( $subrow = $db->sql_fetchrow( $result ) )
    {
        $contents .= "<option value='" . $subrow['cid'] . "'>" . $subrow['title'] . "</option>";
        $contents .= getsubcat( $subrow['cid'], $i = '-' );
    }
    $contents .= "</select>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['addcat_description'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<textarea cols='40' name='catdescription'></textarea>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['addcat_active'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<label><input type='checkbox' name='active' value='1' checked=\"checked\" > " . $lang_module['addcat_active_yes'] . "</label>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td colspan='2' style='padding-left:100px'>";
    $contents .= "<input type='button' name='confirm' value='" . $lang_module['addcat_save'] . "'>";
    $contents .= "<span name='notice' style='float:right;padding-right:50px;color:red;font-weight:bold'></span>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "</table>\n";
    $contents .= "
<script type='text/javascript'>
$(function(){
$('input[name=\"confirm\"]').click(function(){
	$('input[name=\"confirm\"]').attr('disabled','disabled');
	var title = $('input[name=\"cattitle\"]').val();
	if (title==''){
		alert('" . $lang_module['addcat_error_title'] . "');
		$('input[name=\"cattitle\"]').focus();
		return false;
	}
	var catparent = $('select[name=\"catparent\"]').val();
	var description = $('textarea[name=\"catdescription\"]').val();
	var active = $('input[name=\"active\"]').is(':checked')?1:0;
	$('span[name=\"notice\"]').html('<img src=\"../images/load.gif\"> please wait...');
	$.ajax({	
		type: 'POST',
		url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addcat',
		data: 'title='+ title + '&catparent='+catparent+'&description='+description+'&active='+active+'&save=1',
		success: function(data){				
			$('input[name=\"confirm\"]').removeAttr('disabled');
			$('span[name=\"notice\"]').html(data);
			window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&cid=" . $catid . "';
		}
	});
});
});
</script>
";
}
echo $contents;
?>