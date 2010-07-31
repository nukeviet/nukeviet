<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$ok = $nv_Request->get_int ( 'ok', 'post' );
$catid = $nv_Request->get_int ( 'cid', 'post,get' );
if ($ok)
{
	$movecat = $nv_Request->get_int('movecat','post');
	list($catparent) = $db->sql_fetchrow($db->sql_query("SELECT parentid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid=" . $catid . ""));	
	$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid='$catid'");
	$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET catid='".$movecat."' WHERE catid='".$catid."'");
	#reupdate
	$result = $db->sql_query ( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=" . $catparent . " ORDER BY weight ASC" );
	$i = 1;
	while ( $row = $db->sql_fetchrow ( $result ) )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET weight='" . $i . "' WHERE cid='".$row['cid']."'";
		$db->sql_query ( $sql );
		$i ++;
	}
	echo $lang_module['delcat_success'];
} else
{
	$numcid = $db->sql_numrows($db->sql_query("SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=" . $catid . ""));
	list($catparent) = $db->sql_fetchrow($db->sql_query("SELECT parentid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid=" . $catid . ""));
	if ($numcid){
		echo '
		<script type="text/javascript">
			alert("'.$lang_module['delcat_nodel'].'");
		</script>';
		exit();
	}
	echo "<script type='text/javascript' src='" . NV_BASE_SITEURL . "js/jquery/jquery.min.1.4.2.js'></script>";
	echo "<table class=\"tab1\" style='width:400px'>\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">".$lang_module['delcat_confirm']."</td>\n";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";
	echo "<tr>\n";
	echo "<td>".$lang_module['delcat_movecat']."</td>\n";
	echo "<td>";
	echo "<select name='catparent'>";
	echo "<option value='0'>".$lang_module['delcat_catmain']."</option>";
	$sql = "SELECT cid, title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid!=" . $catid . " AND parentid=0";
	$result = $db->sql_query ( $sql );
	while ( $subrow = $db->sql_fetchrow ( $result ) )
	{
		$sel = ($subrow ['cid'] == $row ['parentid']) ? ' selected' : '';
		echo "<option value='" . $subrow ['cid'] . "' " . $sel . ">" . $subrow ['title'] . "</option>";
		echo getsubcat ( $subrow ['cid'], $i = '-' );
	}
	echo "</select>";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan='2' style='padding-left:150px'>";
	echo "<input type='button' name='confirm' value='".$lang_module['delcat_nextstep']."'>";
	echo "<span name='notice' style='float:right;padding-right:50px;color:red;font-weight:bold'></span>";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</tbody>\n";
	echo "</table>\n";
	echo "
<script type='text/javascript'>
$('input[name=confirm]').click(function(){
	if (confirm('".$lang_module['delcat_confirmdel']."')){
		var movecat = $('select[name=catparent]').val();
		$.ajax({	
			type: 'POST',
			url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delcat',
			data:'movecat='+movecat+'&ok=1'+'&cid=" . $catid . "',
			success: function(data){				
				alert(data);
				window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&cid=".$catparent."';
			}
		});
	} else {
		window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&cid=".$catparent."';
	}
});
</script>
";
}
?>