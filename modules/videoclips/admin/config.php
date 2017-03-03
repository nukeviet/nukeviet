<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['config'];

$skins = nv_scandir( NV_ROOTDIR . "/images/jwplayer/skin/", "/^[a-zA-Z0-9\_\-\.]+\.zip$/", 1 );

$array_config = array();
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config['otherClipsNum'] = $nv_Request->get_int( 'otherClipsNum', 'post', 0 );
	$array_config['playerAutostart'] = $nv_Request->get_int( 'playerAutostart', 'post', 0 );
	$array_config['playerSkin'] = $nv_Request->get_title( 'playerSkin', 'post', '', 1 );
	$array_config['playerMaxWidth'] = $nv_Request->get_int( 'playerMaxWidth', 'post', 0 );
	$array_config['idhomeclips'] = $nv_Request->get_int( 'idhomeclips', 'post', 0 );
	if ( ! in_array( $array_config['playerSkin'] . ".zip", $skins ) ) $array_config['playerSkin'] = "";
	if ( $array_config['playerMaxWidth'] < 50 or $array_config['playerMaxWidth'] > 1000 ) $array_config['playerMaxWidth'] = 640;

	$content_config = "<?php\n\n";
	$content_config .= NV_FILEHEAD . "\n\n";
	$content_config .= "if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";
	$content_config .= "\$configMods['otherClipsNum'] = " . $array_config['otherClipsNum'] . ";\n";
	$content_config .= "\$configMods['playerAutostart'] = " . $array_config['playerAutostart'] . ";\n";
	$content_config .= "\$configMods['playerSkin'] = \"" . nv_htmlspecialchars( $array_config['playerSkin'] ) . "\";\n";
	$content_config .= "\$configMods['playerMaxWidth'] = " . $array_config['playerMaxWidth'] . ";\n";
	$content_config .= "\$configMods['idhomeclips'] = " . $array_config['idhomeclips'] . ";\n";
	$content_config .= "\n";
	$content_config .= "?>";

	file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php", $content_config, LOCK_EX );
	die( 'OK' );
}

$configMods = array();
$configMods['otherClipsNum'] = 16; //So video-clip hien thi tren trang chu hoac trang The loai
$configMods['playerAutostart'] = 0; //Co tu dong phat video hay khong
$configMods['playerSkin'] = ""; //Skin cua player
$configMods['playerMaxWidth'] = 640; //Chieu rong toi da cua player
$configMods['idhomeclips'] = 0;
if ( file_exists( NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php" ) )
{
	require ( NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php" );
}

$configMods['playerAutostart'] = $configMods['playerAutostart'] ? " checked=\"checked\"" : "";

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'CONFIGMODULE', $configMods );

$sql = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip ORDER BY addtime DESC LIMIT 100";
$result = $db->query( $sql );
while ( $row = $result->fetch() )
{
	$row['select'] = $configMods['idhomeclips'] ? " selected=\"selected\"" : "";
	$xtpl->assign( 'VHOME', $row );
	$xtpl->parse( 'main.idhomeclips' );
}

for ( $i = 10; $i <= 50; ++$i )
{
	$sel = $i == $configMods['otherClipsNum'] ? " selected=\"selected\"" : "";
	$xtpl->assign( 'NUMS', array( 'value' => $i, 'select' => $sel ) );
	$xtpl->parse( 'main.otherClipsNum' );
}

foreach ( $skins as $skin )
{
	$skin = substr( $skin, 0, -4 );
	$sel = $skin == $configMods['playerSkin'] ? " selected=\"selected\"" : "";
	$xtpl->assign( 'SKIN', array( 'value' => $skin, 'select' => $sel ) );
	$xtpl->parse( 'main.playerSkin' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';