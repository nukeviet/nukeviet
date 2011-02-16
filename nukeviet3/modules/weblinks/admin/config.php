<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['weblink_config'];
$submit = $nv_Request->get_string( 'submit', 'post' );
if ( ! empty( $submit ) )
{
    $error = 0;
    $intro = nv_htmlspecialchars( trim( $nv_Request->get_string( 'intro', 'post', '' ) ) );
    $numcat = $nv_Request->get_int( 'numcat', 'post', 2 );
    
    $showsub = $nv_Request->get_int( 'showsub', 'post', 0 );
    
    $numsub = $nv_Request->get_int( 'numsub', 'post', 2 );
    $numinsub = $nv_Request->get_int( 'numinsub', 'post', 0 );
    
    $showcatimage = $nv_Request->get_int( 'showcatimage', 'post', 0 );
    
    $numsubcat = $nv_Request->get_int( 'numsubcat', 'post', 2 );
    $shownumsubcat = $nv_Request->get_int( 'shownumsubcat', 'post', 0 );
    
    $sort = ( $nv_Request->get_string( 'sort', 'post' ) == 'asc' ) ? 'asc' : 'des';
    $sortoption = nv_htmlspecialchars( $nv_Request->get_string( 'sortoption', 'post', 'byid' ) );
    
    $showlinkimage = $nv_Request->get_int( 'showlinkimage', 'post', 0 );
    $showdes = $nv_Request->get_int( 'showdes', 'post', 0 );
    
    $imgwidth = ( $nv_Request->get_int( 'imgwidth', 'post' ) >= 0 ) ? $nv_Request->get_int( 'imgwidth', 'post' ) : 100;
    $imgheight = ( $nv_Request->get_int( 'imgheight', 'post' ) >= 0 ) ? $nv_Request->get_int( 'imgheight', 'post' ) : 75;
    $timeout = ( $nv_Request->get_int( 'timeout', 'post' ) >= 0 ) ? $nv_Request->get_int( 'timeout', 'post' ) : 1;
    $per_page = ( $nv_Request->get_int( 'per_page', 'post' ) >= 0 ) ? $nv_Request->get_int( 'per_page', 'post' ) : 10;
    
    $sql = array();
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $numcat . "' WHERE name='numcat'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $showsub . "' WHERE name='showsub'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $numsub . "' WHERE name='numsub'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $numinsub . "' WHERE name='numinsub'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $showcatimage . "' WHERE name='showcatimage'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $numsubcat . "' WHERE name='numsubcat'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $shownumsubcat . "' WHERE name='shownumsubcat'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $sort . "' WHERE name='sort'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $sortoption . "' WHERE name='sortoption'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $showlinkimage . "' WHERE name='showlinkimage'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $showdes . "' WHERE name='showdes'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $imgwidth . "' WHERE name='imgwidth'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $imgheight . "' WHERE name='imgheight'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $timeout . "' WHERE name='timeout'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $per_page . "' WHERE name='per_page'";
    foreach ( $sql as $value )
    {
        if ( ! $db->sql_query( $value ) )
        {
            $error = 1;
            break;
        }
    }
    $redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
    $contents .= "<div style=\"padding-top:30px; text-align:center;font-weight:bold;\">";
    $contents .= ( $error == 1 ) ? $lang_module['weblink_config_unsuccess'] : $lang_module['weblink_config_success'];
    $contents .= "</div><meta http-equiv=\"Refresh\" content=\"1;URL=" . $redirect . "\">";
}
else
{
    $sql = "SELECT name, value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $$row['name'] = $row['value'];
    }
    $contents .= "<div id=\"list_mods\">";
    $contents .= "<form action='' method='post'>";
    $contents .= "<table class=\"tab1\" style=\"margin-bottom: 8px;\">\n";
    $contents .= "<tbody>";
    $contents .= "<tr>";
    $contents .= "<td width=\"230px\" align=\"right\">" . $lang_module['weblink_config_col'] . "</td>\n";
    $contents .= "<td>\n";
    $contents .= "<select name=\"numcat\">\n";
    for ( $i = 1; $i <= 4; $i ++ )
    {
        $self = ( $numcat == $i ) ? ' selected="selected"' : "";
        $contents .= "<option value=\"" . $i . "\"" . $self . ">" . $i . "</option>\n";
    }
    unset( $i, $self );
    $contents .= "</select>\n";
    $contents .= "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>\n";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $checked = ( $showsub == 1 ) ? ' checked' : '';
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_showsub'] . "</td>\n";
    $contents .= "<td><input type=\"checkbox\" value=\"1\" name='showsub' $checked> " . $lang_module['weblink_yes'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>\n";
    $contents .= "<tbody>";
    $contents .= "<tr>";
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_showsublimit'] . "</td>\n";
    $contents .= "<td>\n";
    $contents .= "<select name=\"numsub\">\n";
    for ( $i = 1; $i <= 4; $i ++ )
    {
        $self = ( $numsub == $i ) ? ' selected="selected"' : "";
        $contents .= "<option value=\"" . $i . "\"" . $self . ">" . $i . "</option>\n";
    }
    unset( $i, $self );
    $contents .= "</select>\n";
    $contents .= "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>\n";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $checked = ( $numinsub == 1 ) ? ' checked' : '';
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_shownumsub'] . "</td>\n";
    $contents .= "<td><input type=\"checkbox\" value=\"1\" name=\"numinsub\" $checked> " . $lang_module['weblink_yes'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>\n";
    $contents .= "<tbody>\n";
    $contents .= "<tr>";
    $checked = ( $showcatimage == 1 ) ? ' checked' : '';
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_showimage'] . "</td>\n";
    $contents .= "<td><input type=\"checkbox\" value=\"1\" name=\"showcatimage\" $checked> " . $lang_module['weblink_yes'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>\n";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $checked = ( $showdes == 1 ) ? ' checked' : '';
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_showdes'] . "</td>\n";
    $contents .= "<td><input type=\"checkbox\"  value=\"1\" name=\"showdes\" $checked> " . $lang_module['weblink_yes'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "</table>";
    //viewcat
    $contents .= "<legend><strong>" . $lang_module['weblink_config_sub'] . "</strong></legend>";
    $contents .= "<table class=\"tab1\" style=\"margin-bottom: 8px;\">\n";
    $contents .= "<tbody>";
    $contents .= "<tr>";
    $contents .= "<td  align=\"right\"  width=\"230px\">" . $lang_module['weblink_config_col'] . "</td>\n";
    $contents .= "<td>\n";
    $contents .= "<select name=\"numsubcat\">\n";
    for ( $i = 1; $i <= 8; $i ++ )
    {
        $self = ( $numsubcat == $i ) ? ' selected="selected"' : "";
        $contents .= "<option value=\"" . $i . "\"" . $self . ">" . $i . "</option>\n";
    }
    unset( $i, $self );
    $contents .= "</select>\n";
    $contents .= "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td  align=\"right\"  width=\"230px\">" . $lang_module['weblink_config_timeout'] . "</td>\n";
    $contents .= "<td><input type=\"text\" name=\"timeout\" value=\"" . $timeout . "\" style=\"width:50px\"></td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody>";
    $contents .= "<tr>";
    $checked = ( $shownumsubcat == 1 ) ? ' checked' : '';
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_shownumsub'] . "</td>\n";
    $contents .= "<td><input type=\"checkbox\"  value=\"1\" valuename=\"shownumsubcat\" $checked> " . $lang_module['weblink_yes'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td  align=\"right\"  width=\"230px\">" . $lang_module['weblink_config_imgwidth'] . "</td>\n";
    $contents .= "<td><input type=\"text\" name=\"imgwidth\" value=\"" . $imgwidth . "\" style=\"width:50px\"></td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody>";
    $contents .= "<tr>";
    $contents .= "<td  align=\"right\"  width=\"230px\">" . $lang_module['weblink_config_imgheight'] . "</td>\n";
    $contents .= "<td><input type=\"text\" name=\"imgheight\" value=\"" . $imgheight . "\" style=\"width:50px\"></td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>\n";
    $contents .= "<td  align=\"right\">" . $lang_module['config_per_page'] . "</td>\n";
    $contents .= "<td>\n";
    $contents .= "<select name=\"per_page\">\n";
    for ( $i = 5; $i <= 20; $i ++ )
    {
        $self = ( $per_page == $i ) ? ' selected="selected"' : "";
        $contents .= "<option value=\"" . $i . "\"" . $self . ">" . $i . "</option>\n";
    }
    $contents .= "</select>\n";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>";
    $contents .= "<tbody>";
    $contents .= "<tr>";
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_sort'] . "</td>\n";
    $asc = $des = '';
    ( $sort == 'asc' ) ? $asc = ' checked' : $des = ' checked';
    $contents .= "<td><input type=\"radio\" name='sort' id='sapxep_0' $asc value='asc'> " . $lang_module['weblink_asc'] . "  <input type=\"radio\" name='sort' id='sapxep_1' $des value='des'> " . $lang_module['weblink_des'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_sortoption'] . "</td>\n";
    $byid = ( $sortoption == 'byid' ) ? ' checked' : '';
    $byrand = ( $sortoption == 'byrand' ) ? ' checked' : '';
    $bytime = ( $sortoption == 'bytime' ) ? ' checked' : '';
    $byhit = ( $sortoption == 'byhit' ) ? ' checked' : '';
    $contents .= "<td>
	<input type=\"radio\" name=\"sortoption\" id=\"sapxepoption_0\" $byid value=\"byid\">" . $lang_module['weblink_config_sortbyid'] . "  
	<input type=\"radio\" name=\"sortoption\" id=\"sapxepoption_1\" $byrand value=\"byrand\">" . $lang_module['weblink_config_sortbyrand'] . "
	<input type=\"radio\" name=\"sortoption\" id=\"sapxepoption_2\" $bytime value=\"bytime\">" . $lang_module['weblink_config_sortbytime'] . "
	<input type=\"radio\" name=\"sortoption\" id=\"sapxepoption_3\" $byhit value=\"byhit\">" . $lang_module['weblink_config_sortbyhit'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody>";
    $contents .= "<tr>";
    $checked = ( $showlinkimage == 1 ) ? ' checked' : '';
    $contents .= "<td  align=\"right\">" . $lang_module['weblink_config_showimagelink'] . "</td>\n";
    $contents .= "<td><input type=\"checkbox\"  value=\"1\" name='showlinkimage' $checked> " . $lang_module['weblink_yes'] . "</td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td colspan=\"2\" align=\"center\"><input type=\"submit\" name='submit' value='" . $lang_module['weblink_submit'] . "'></td>\n";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "</table>";
    $contents .= "</form>";
    $contents .= "</div>\n";
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>