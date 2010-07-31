<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$page_title = $lang_module ['download_config'];
$result = $db->sql_query("SELECT name, value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`");
while ($row=$db->sql_fetchrow($result)){
	$data[$row['name']]=$row['value'];
}
$contents .= "<form method='post' name='' action='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&op=updateconfig'>\n";
$contents .= "<table class=\"tab1\" style='width:700px'>\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan=\"2\">".$lang_module['config_title']."</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_textlimit']."</td>\n";
$contents .= "<td>";
$sel = ($data['deslimit']==1)?' checked':'';
$contents .= "<label><input type='checkbox' value='1' name='deslimit' ".$sel."> ".$lang_module['download_yes']."</label>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_textlimitnum']."</td>\n";
$contents .= "<td><input type='text' value='".$data['textlimit']."' name='textlimit' style='width:50px'></td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_showcomment']."</td>\n";
$contents .= "<td>";
$sel = ($data['showemail']==1)?' checked':'';
$contents .= "<label><input type='checkbox' value='1' name='showemail' ".$sel."> ".$lang_module['download_yes']."</label>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_directdownload']."</td>\n";
$contents .= "<td>";
$sel = ($data['directlink']==1)?' checked':'';
$contents .= "<label><input type='checkbox' value='1' name='directlink' ".$sel."> ".$lang_module['download_yes']."</label>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_showmessage']."</td>\n";
$contents .= "<td>";
$sel = ($data['showmessage']==1)?' checked':'';
$contents .= "<label><input type='checkbox' value='1' name='showmessage' ".$sel."> ".$lang_module['download_yes']."</label>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_message']."</td>\n";
$contents .= "<td>";
$contents .= "<textarea name='messagecontent' cols='50' rows='5'>".htmlspecialchars_decode($data['messagecontent'])."</textarea>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_folder']."</td>\n";
$contents .= "<td>";
$sel = ($data['showsubfolder']==1)?' checked':'';
$contents .= "<label><input type='checkbox' value='1' name='showsubfolder' ".$sel."> ".$lang_module['download_yes']."</label>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_foldernum']."</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value='".$data['numsubfolder']."' name='numsubfolder' style='width:50px'>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_filenum']."</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value='".$data['numfile']."' name='numfile' style='width:50px'>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_whodownload']."</td>\n";
$contents .= "<td>";
$array_who_view = array ($lang_global ['who_view0'], $lang_global ['who_view1'], $lang_global ['who_view2'], $lang_global ['who_view3']);
$groups_list = nv_groups_list ();
$contents .= "<select name=\"who_view1\" id=\"who_view1\" onchange=\"nv_sh('who_view1','groups_list1')\" style=\"width: 250px;\">\n";
foreach ( $array_who_view as $k => $w )
{
	$sel = ($data['who_view1']==$k)?' selected':'';
	$contents .= "<option value=\"" . $k . "\" ".$sel.">" . $w . "</option>\n";
}
$contents .= "</select><br>\n";
$contents .= "<div id=\"groups_list1\" style=\"display:".(($data['who_view1']==3)?'block':'none').";\">\n";
$contents .= "" . $lang_global ['groups_view'] . ":\n";
$contents .= "<table style=\"margin-bottom:8px;width:250px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
foreach ( $groups_list as $grid => $grtl )
{
	$sel = ((int)array_intersect(array($grid),explode(',',$data['groups_view1'])))?'checked=checked':'';
	$contents .= "<p><input ".$sel." name=\"groups_view1[]\" type=\"checkbox\" value=\"" . $grid . "\">" . $grtl . "</p>\n";
}
$contents .= "</td>\n";
$contents .= "</tr></table></div>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_captcha']."</td>\n";
$contents .= "<td>";
$sel = ($data['showcaptcha']==1)?' checked':'';
$contents .= "<label><input type='checkbox' value='1' name='showcaptcha' ".$sel."> ".$lang_module['download_yes']."</label>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_whocomment']."</td>\n";
$contents .= "<td>";

$contents .= "<select name=\"who_view2\" id=\"who_view2\" onchange=\"nv_sh('who_view2','groups_list2')\" style=\"width: 250px;\">\n";
foreach ( $array_who_view as $k => $w )
{
	$sel = ($data['who_view2']==$k)?' selected':'';	
	$contents .= "<option value=\"" . $k . "\" ".$sel.">" . $w . "</option>\n";
}
$contents .= "</select><br>\n";
$contents .= "<div id=\"groups_list2\" style=\"display:".(($data['who_view2']==3)?'block':'none').";\">\n";
$contents .= "" . $lang_global ['groups_view'] . ":\n";
$contents .= "<table style=\"margin-bottom:8px;width:250px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
foreach ( $groups_list as $grid => $grtl )
{
	$sel = ((int)array_intersect(array($grid),explode(',',$data['groups_view2'])))?'checked=checked':'';
	$contents .= "<p><input ".$sel." name=\"groups_view2[]\" type=\"checkbox\" value=\"" . $grid . "\">" . $grtl . "</p>\n";
}
$contents .= "</td>\n";
$contents .= "</tr></table></div>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_whoreport']."</td>\n";
$contents .= "<td>";
$contents .= "<select name=\"who_view3\" id=\"who_view3\" onchange=\"nv_sh('who_view3','groups_list3')\" style=\"width: 250px;\">\n";
foreach ( $array_who_view as $k => $w )
{
	$sel = ($data['who_view3']==$k)?' selected':'';	
	$contents .= "<option value=\"" . $k . "\" ".$sel.">" . $w . "</option>\n";
}
$contents .= "</select><br>\n";
$contents .= "<div id=\"groups_list3\" style=\"display:".(($data['who_view3']==3)?'block':'none').";\">\n";
$contents .= "" . $lang_global ['groups_view'] . ":\n";
$contents .= "<table style=\"margin-bottom:8px;width:250px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
foreach ( $groups_list as $grid => $grtl )
{
	$sel = ((int)array_intersect(array($grid),explode(',',$data['groups_view3'])))?'checked=checked':'';
	$contents .= "<p><input ".$sel." name=\"groups_view3[]\" type=\"checkbox\" value=\"" . $grid . "\">" . $grtl . "</p>\n";
}
$contents .= "</td>\n";
$contents .= "</tr></table></div>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_whorate']."</td>\n";
$contents .= "<td>";
$contents .= "<select name=\"who_view4\" id=\"who_view4\" onchange=\"nv_sh('who_view4','groups_list4')\" style=\"width: 250px;\">\n";
foreach ( $array_who_view as $k => $w )
{
	$sel = ($data['who_view4']==$k)?' selected':'';	
	$contents .= "<option value=\"" . $k . "\" ".$sel.">" . $w . "</option>\n";
}
$contents .= "</select><br>\n";
$contents .= "<div id=\"groups_list4\" style=\"display:".(($data['who_view4']==3)?'block':'none').";\">\n";
$contents .= "" . $lang_global ['groups_view'] . ":\n";
$contents .= "<table style=\"margin-bottom:8px;width:250px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
foreach ( $groups_list as $grid => $grtl )
{
	$sel = ((int)array_intersect(array($grid),explode(',',$data['groups_view4'])))?'checked=checked':'';
	$contents .= "<p><input ".$sel." name=\"groups_view4[]\" type=\"checkbox\" value=\"" . $grid . "\">" . $grtl . "</p>\n";
}
$contents .= "</td>\n";
$contents .= "</tr></table></div>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_whoupload']."</td>\n";
$contents .= "<td>";
$contents .= "<select name=\"who_view5\" id=\"who_view5\" onchange=\"nv_sh('who_view5','groups_list5')\" style=\"width: 250px;\">\n";
foreach ( $array_who_view as $k => $w )
{
	$sel = ($data['who_view5']==$k)?' selected':'';	
	$contents .= "<option value=\"" . $k . "\" ".$sel.">" . $w . "</option>\n";
}
$contents .= "</select><br>\n";
$contents .= "<div id=\"groups_list5\" style=\"display:".(($data['who_view5']==3)?'block':'none').";\">\n";
$contents .= "" . $lang_global ['groups_view'] . ":\n";
$contents .= "<table style=\"margin-bottom:8px;width:250px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
foreach ( $groups_list as $grid => $grtl )
{
	$sel = ((int)array_intersect(array($grid),explode(',',$data['groups_view5'])))?'checked=checked':'';
	$contents .= "<p><input ".$sel." name=\"groups_view5[]\" type=\"checkbox\" value=\"" . $grid . "\">" . $grtl . "</p>\n";
}
$contents .= "</td>\n";
$contents .= "</tr></table></div>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_whouploadfile']."</td>\n";
$contents .= "<td>";
$contents .= "<select name=\"who_view6\" id=\"who_view6\" onchange=\"nv_sh('who_view6','groups_list6')\" style=\"width: 250px;\">\n";
foreach ( $array_who_view as $k => $w )
{
	$sel = ($data['who_view6']==$k)?' selected':'';	
	$contents .= "<option value=\"" . $k . "\" ".$sel.">" . $w . "</option>\n";
}
$contents .= "</select><br>\n";
$contents .= "<div id=\"groups_list6\" style=\"display:".(($data['who_view6']==3)?'block':'none').";\">\n";
$contents .= "" . $lang_global ['groups_view'] . ":\n";
$contents .= "<table style=\"margin-bottom:8px;width:250px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
foreach ( $groups_list as $grid => $grtl )
{
	$sel = ((int)array_intersect(array($grid),explode(',',$data['groups_view6'])))?'checked=checked':'';
	$contents .= "<p><input ".$sel." name=\"groups_view6[]\" type=\"checkbox\" value=\"" . $grid . "\">" . $grtl . "</p>\n";
}
$contents .= "</td>\n";
$contents .= "</tr></table></div>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_maxfilesize']."</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value='".$data['maxfilesize']."' name='maxfilesize' style='width:100px'> ".$lang_module['config_maxfilebyte'].". <br />".$lang_module['config_maxfilesizesys']." ".ini_get('upload_max_filesize');
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_uploadedfolder']."</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value='".$data['filedir']."' name='filedir' style='width:300px'><br /><span style='color:red;font-weight:bold'>";
$contents .=(is_writeable ( '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['filedir'] . '' ))?$lang_module['config_writeable']:$lang_module['config_unwriteable'];
$contents .= "</span></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_queuefolder']."</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value='".$data['filetempdir']."' name='filetempdir' style='width:300px'><br /><span style='color:red;font-weight:bold'>";
$contents .=(is_writeable ( '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['filetempdir'] . '' ))?$lang_module['config_writeable']:$lang_module['config_unwriteable'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>".$lang_module['config_allowfiletype']."</td>\n";
$contents .= "<td>";
$contents .= "<textarea name='filetype' cols='50' rows='5'>".$data['filetype']."</textarea>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td style='text-align:center'><span id='notice' style='float:right;padding-right:50px;color:red'></span></td><td><input name='confirm' type='submit' value='".$lang_module['config_confirm']."'>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";
$contents .="
<script type='text/javascript'>
$(function(){
$('input[name=\"confirm\"]').click(function(){
var deslimit = $('input[name=\"deslimit\"]').is(':checked')?1:0;
var textlimit = $('input[name=\"textlimit\"]').val();
if (isNaN(textlimit)){
	alert('".$lang_module['config_alert_numtextlimit']."');
	$('input[name=\"textlimit\"]').focus();
	return false;
}
var directlink = $('input[name=\"directlink\"]').is(':checked')?1:0;
var showemail = $('input[name=\"showemail\"]').is(':checked')?1:0;
var showmessage = $('input[name=\"showmessage\"]').is(':checked')?1:0;
var messagecontent = $('textarea[name=\"messagecontent\"]').val();
var showsubfolder = $('input[name=\"showsubfolder\"]').is(':checked')?1:0;
var numsubfolder = $('input[name=\"numsubfolder\"]').val();
if (isNaN(numsubfolder)){
	alert('".$lang_module['config_alert_numfolder']."');
	$('input[name=\"numsubfolder\"]').focus();
	return false;
}
var numfile = $('input[name=\"numfile\"]').val();
if (isNaN(numfile)){
	alert('".$lang_module['config_alert_numfile']."');
	$('input[name=\"numfile\"]').focus();
	return false;
}
var who_view1 = $('select[name=\"who_view1\"]').val();
var groups_view1 = [];
$('input[name=\"groups_view1[]\"]:checked').each(function(){groups_view1.push($(this).val());});
var showcaptcha = $('input[name=\"showcaptcha\"]').is(':checked')?1:0;
var who_view2 = $('select[name=\"who_view2\"]').val();
var groups_view2 = [];
$('input[name=\"groups_view2[]\"]:checked').each(function(){groups_view2.push($(this).val());});
var who_view3 = $('select[name=\"who_view3\"]').val();
var groups_view3 = [];
$('input[name=\"groups_view3[]\"]:checked').each(function(){groups_view3.push($(this).val());});
var who_view4 = $('select[name=\"who_view4\"]').val();
var groups_view4 = [];
$('input[name=\"groups_view4[]\"]:checked').each(function(){groups_view4.push($(this).val());});
var who_view5 = $('select[name=\"who_view5\"]').val();
var groups_view5 = [];
$('input[name=\"groups_view5[]\"]:checked').each(function(){groups_view5.push($(this).val());});
var who_view6 = $('select[name=\"who_view6\"]').val();
var groups_view6 = [];
$('input[name=\"groups_view6[]\"]:checked').each(function(){groups_view6.push($(this).val());});
var maxfilesize = $('input[name=\"maxfilesize\"]').val();
if (isNaN(maxfilesize)){
	alert('".$lang_module['config_alert_filesize']."');
	$('input[name=\"numfile\"]').focus();
	return false;
}
var filedir = $('input[name=\"filedir\"]').val();
var filetempdir = $('input[name=\"filetempdir\"]').val();
var filetype = $('textarea[name=\"filetype\"]').val();
$('span[id=notice]').html('<img src=\"../images/load.gif\"> please wait...');
$('input[name=\"confirm\"]').attr({'disabled':'disabled'});	
		$.ajax({	
			type: 'POST',
			url: '" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&op=updateconfig',
			data: 'deslimit='+ deslimit +'&showemail='+ showemail + '&textlimit='+textlimit+'&directlink='+directlink
			+'&showmessage='+showmessage+'&messagecontent='+messagecontent+'&showsubfolder='+showsubfolder
			+'&numsubfolder='+numsubfolder+'&numfile='+numfile+'&who_view1='+who_view1+'&groups_view1='+groups_view1
			+'&showcaptcha='+showcaptcha+'&who_view2='+who_view2+'&groups_view2='+groups_view2+'&who_view3='+who_view3+'&groups_view3='+groups_view3
			+'&who_view4='+who_view4+'&groups_view4='+groups_view4+'&who_view5='+who_view5+'&groups_view5='+groups_view5+'&who_view6='+who_view6+'&groups_view6='+groups_view6+'&maxfilesize='+maxfilesize
			+'&filedir='+filedir+'&filetempdir='+filetempdir+'&filetype='+filetype,
			success: function(data){				
				$('span[id=notice]').html(data);
				$('input[name=\"confirm\"]').attr({'disabled':''});
			}
		});

return false;
});


});
</script>";
include (NV_ROOTDIR . "/includes/header.php");
$contents .= nv_admin_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>