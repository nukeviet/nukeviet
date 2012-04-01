<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 29-03-2012 03:29
 */

define( 'NV_SYSTEM', true );
define( 'NV_IS_UPDATE', true );

//Ket noi den mainfile.php nam o thu muc goc.
$realpath_mainfile = '';

$temp_dir = str_replace( DIRECTORY_SEPARATOR, '/', dirname( __file__ ) );
$temp_path = "/../";
for( $i = 0; $i < 10; ++$i )
{
	$realpath_mainfile = @realpath( $temp_dir . $temp_path . 'mainfile.php' );
	if( ! empty( $realpath_mainfile ) ) break;
	$temp_path .= "../";
}
unset( $temp_dir, $temp_path );

if( empty( $realpath_mainfile ) ) die();

require ( $realpath_mainfile );
unset( $realpath_mainfile );

// Kiem tra tu cach admin
if( ! defined( 'NV_IS_GODADMIN' ) )
{
	Header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA ) );
	die();
}

// Kiem tra ton tai goi update
if( ! file_exists( NV_ROOTDIR . '/install/update_data.php' ) )
{
	Header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA ) );
	die();
}
require ( NV_ROOTDIR . '/install/update_data.php' );
if( empty( $nv_update_config ) )
{
	Header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA ) );
	die();
}

// Ham cua admin
define( 'NV_ADMIN', true );
include_once ( NV_ROOTDIR . "/includes/core/admin_functions.php" );

// Xac dinh ngon ngu cap nhat
$dirs = nv_scandir( NV_ROOTDIR . "/language", "/^([a-z]{2})/" );
$languageslist = array();

foreach( $dirs as $file )
{
	if( is_file( NV_ROOTDIR . '/language/' . $file . '/install.php' ) )
	{
		$languageslist[] = $file;
	}
}
$data_update_lang = array_keys( $nv_update_config['lang'] );
$array_lang_update = array_intersect( $data_update_lang, $languageslist );
$nv_update_config['allow_lang'] = $array_lang_update;

$cookie_lang = $nv_Request->get_string( 'update_lang', 'cookie', '' );
$update_lang = $nv_Request->get_string( NV_LANG_VARIABLE, 'get,post', '' );

if( ! empty( $update_lang ) and ( in_array( $update_lang, $array_lang_update ) ) and file_exists( NV_ROOTDIR . "/language/" . $update_lang . "/global.php" ) )
{
	if( $update_lang != $cookie_lang ) $nv_Request->set_Cookie( 'update_lang', $update_lang, NV_LIVE_COOKIE_TIME );
}
elseif( preg_match( "/^[a-z]{2}$/", $cookie_lang ) and ( in_array( $cookie_lang, $array_lang_update ) ) and file_exists( NV_ROOTDIR . "/language/" . $cookie_lang . "/global.php" ) )
{
	$update_lang = $cookie_lang;
}
elseif( in_array( NV_LANG_DATA, $array_lang_update ) )
{
	$update_lang = NV_LANG_DATA;
	$nv_Request->set_Cookie( 'update_lang', $update_lang, NV_LIVE_COOKIE_TIME );
}
else
{
	$update_lang = $array_lang_update[0];
	$nv_Request->set_Cookie( 'update_lang', $update_lang, NV_LIVE_COOKIE_TIME );
}

define( 'NV_LANG_UPDATE', $update_lang );

unset( $dirs, $languageslist, $file, $data_update_lang, $array_lang_update, $cookie_lang, $update_lang );
if( NV_LANG_UPDATE != NV_LANG_DATA ) unset( $lang_module, $lang_global );

require( NV_ROOTDIR . "/language/" . NV_LANG_UPDATE . "/global.php" );
require( NV_ROOTDIR . "/language/" . NV_LANG_UPDATE . "/admin_global.php" );
require( NV_ROOTDIR . "/language/" . NV_LANG_UPDATE . "/install.php" );

$lang_module = array_merge( $lang_module, $nv_update_config['lang'][NV_LANG_UPDATE] );
unset( $nv_update_config['lang'] );

class NvUpdate
{
	private $db;
	private $lang;
	private $glang;
	private $config;
	
	public function __construct( $nv_update_config )
	{
		global $db, $lang_module, $lang_global;
		
		$this->db = $db;
		$this->lang = $lang_module;
		$this->glang = $lang_global;
		$this->config = $nv_update_config;
	}
	
	public function check_package()
	{
		if( ! isset( $this->config['release_date'] ) ) return false;
		elseif( ! isset( $this->config['author'] ) ) return false;
		elseif( ! isset( $this->config['support_website'] ) ) return false;
		elseif( ! isset( $this->config['to_version'] ) ) return false;
		elseif( ! isset( $this->config['allow_old_version'] ) ) return false;
		elseif( ! isset( $this->config['type'] ) ) return false;
		return true;
	}
	
	public function build_full_ver( $version, $revision )
	{
		return $version . '.r' . $revision;
	}
	
	public function list_data_update()
	{
		if( empty( $this->config['tasklist'] ) ) return array();
		
		global $global_config;
		
		$tasklist = array();
		
		foreach( $this->config['tasklist'] as $task )
		{
			if( $task['r'] > $global_config['revision'] )
			{
				$tasklist[$task['f']] = array( 'title' => isset( $this->lang[$task['l']] ) ? $this->lang[$task['l']] : "N/A", 'require' => $task['rq'] );
			}
		}
		
		return $tasklist;
	}
	
	public function list_all_file( $dir = '', $base_dir = '' )
	{
		if( empty( $dir ) ) $dir = NV_ROOTDIR . '/install/update';
		
		$file_list = array();
		
		if( is_dir( $dir ) )
		{
			$array_filedir = scandir( $dir );
			
			foreach( $array_filedir as $v )
			{
				if( $v == '.' or $v == '..' ) continue;
				
				if( is_dir( $dir . '/' . $v ) )
				{
					foreach( $this->list_all_file( $dir . '/' . $v, $base_dir . '/' . $v ) as $file )
					{
						$file_list[] = $file;
					}
				}
				else
				{
					$file_list[] = preg_replace( '/^\//', '', $base_dir . '/' . $v );
				}
			}
		}
		
		return $file_list;
	}
	
	public function template( $contents )
	{
		global $language_array;
		
		$xtpl = new XTemplate( "updatetheme.tpl", NV_ROOTDIR . "/install/tpl" );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
		$xtpl->assign( 'NV_LANG_UPDATE', NV_LANG_UPDATE );
		$xtpl->assign( 'LANG', $this->lang );
		$xtpl->assign( 'CONFIG', $this->config );
		
		$xtpl->assign( 'SITE_TITLE', $this->config['type'] == 1 ? $this->lang['update_site_title_update'] : $this->lang['update_site_title_upgrade'] );
		
		$xtpl->assign( 'CONTENT_TITLE', $this->lang['update_step_title_' . $this->config['step'] ] );
		
		$xtpl->assign( 'MODULE_CONTENT', $contents );
		
		$xtpl->assign( 'LANGTYPESL', NV_LANG_UPDATE );
		$langname = $language_array[NV_LANG_UPDATE]['name'];
		$xtpl->assign( 'LANGNAMESL', $langname );
		
		foreach( $this->config['allow_lang'] as $languageslist_i )
		{
			if( ! empty( $languageslist_i ) and ( NV_LANG_UPDATE != $languageslist_i ) )
			{
				$xtpl->assign( 'LANGTYPE', $languageslist_i );
				$langname = $language_array[$languageslist_i]['name'];
				$xtpl->assign( 'LANGNAME', $langname );
				$xtpl->parse( 'main.looplang' );
			}
		}
		
		$step_bar = array( $this->lang['update_step_1'], $this->lang['update_step_2'], $this->lang['update_step_3'] );
		
		foreach( $step_bar as $i => $step_bar_i )
		{
			$n = $i + 1;
			$class = "";
			
			if( $this->config['step'] >= $n )
			{
				$class = " class=\"";
				$class .= ( $this->config['step'] > $n ) ? 'passed_step' : '';
				$class .= ( $this->config['step'] == $n ) ? 'current_step' : '';
				$class .= "\"";
			}
			
			$xtpl->assign( 'CLASS_STEP', $class );
			$xtpl->assign( 'STEP_BAR', $step_bar_i );
			$xtpl->assign( 'NUM', $n );
			$xtpl->parse( 'main.step_bar.loop' );
		}
		
		$xtpl->parse( 'main.step_bar' );
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
	
	public function step1( $array )
	{
		global $global_config;
		
		$xtpl = new XTemplate( "updatestep1.tpl", NV_ROOTDIR . "/install/tpl" );
		$xtpl->assign( 'LANG', $this->lang );
		$xtpl->assign( 'CONFIG', $this->config );
		$xtpl->assign( 'DATA', $array );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
		$xtpl->assign( 'RELEASE_DATE', ! empty( $this->config['release_date'] ) ? nv_date( 'd/m/Y H:i:s', $this->config['release_date'] ) : "N/A" );
		$xtpl->assign( 'ALLOW_OLD_VERSION', ! empty( $this->config['allow_old_version'] ) ? implode( ', ', $this->config['allow_old_version'] ) : "N/A" );
		$xtpl->assign( 'UPDATE_AUTO_TYPE', isset( $this->config['update_auto_type'] ) ? $this->lang['update_auto_type_' . $this->config['update_auto_type']] : "N/A" );
		
		if( $array['isupdate_allow'] )
		{
			$xtpl->parse( 'main.canupdate' );
		}
		else
		{
			$xtpl->assign( 'URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=deleteupdate&amp;checksess=' . md5( $global_config['sitekey'] . session_id() ) );
			$xtpl->assign( 'URL_RETURN', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=siteinfo' );
			$xtpl->parse( 'main.cannotupdate' );
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
	
	public function step2( $array, $substep )
	{
		global $global_config;
		
		$xtpl = new XTemplate( "updatestep2.tpl", NV_ROOTDIR . "/install/tpl" );
		$xtpl->assign( 'LANG', $this->lang );
		$xtpl->assign( 'CONFIG', $this->config );
		$xtpl->assign( 'DATA', $array );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
		if( $substep == 1 )
		{
			$xtpl->assign( 'URL_DUMP_BACKUP', NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=1&amp;dump&amp;checksess=' . md5( $global_config['sitekey'] . session_id() ) );
			$xtpl->parse( 'main.step1' );
		}
		elseif( $substep == 2 )
		{
			if( $array['taskempty'] )
			{
				$xtpl->parse( 'main.step2.taskempty' );
			}
			else
			{
				if( $this->config['update_auto_type'] == 0 )
				{
					$xtpl->parse( 'main.step2.manual' );
				}
				elseif( $this->config['update_auto_type'] == 2 )
				{
					$xtpl->parse( 'main.step2.semiautomatic' );
				}
				else
				{
					// Cong viec lien quan CSDL
					if( ! empty( $array['data_list'] ) )
					{
						foreach( $array['data_list'] as $w )
						{
							$xtpl->assign( 'ROW', $w );
							$xtpl->parse( 'main.step2.automatic.data.loop' );
						}
						
						$xtpl->parse( 'main.step2.automatic.data' );
					}
					else
					{
						$xtpl->parse( 'main.step2.automatic.nodata' );
					}
					
					// Cong viec lien quan cac file nguon
					if( ! empty( $array['file_list'] ) )
					{
						foreach( $array['file_list'] as $w )
						{
							$xtpl->assign( 'ROW', $w );
							$xtpl->parse( 'main.step2.automatic.file.loop' );
						}
						
						$xtpl->parse( 'main.step2.automatic.file' );
					}
					else
					{
						$xtpl->parse( 'main.step2.automatic.nofile' );
					}
				
					$xtpl->parse( 'main.step2.automatic' );
				}
			}
		
			$xtpl->parse( 'main.step2' );
		}
		elseif( $substep == 3 )
		{
			if( ! empty( $array['errorStepMoveFile'] ) )
			{
				$xtpl->parse( 'main.step3.error' );
			}
			else
			{				
				// Viet cac tien trinh
				foreach( $array['task'] as $task )
				{
					$xtpl->assign( 'ROW', $task );
					$xtpl->parse( 'main.step3.data.loop' );
				}
				
				if( ! empty( $array['stopprocess'] ) ) // Dung cong viec do loi
				{
					$xtpl->assign( 'ERROR_MESSAGE', sprintf( $this->lang['update_task_error_message'], $array['stopprocess']['title'] ) );
					$xtpl->parse( 'main.step3.data.errorProcess' );
				}
				elseif( $array['AllPassed'] == true ) // Hoan tat cong viec va chuyen sang buoc tiep theo
				{
					$xtpl->parse( 'main.step3.data.AllPassed' );
				}
				else // Tiep tuc khoi chay tien trinh
				{
					$xtpl->parse( 'main.step3.data.ConStart' );
				}
				
				if( $array['AllPassed'] == true and empty( $array['stopprocess'] ) )
				{
					$xtpl->parse( 'main.step3.data.next_step' );
				}
				
				$xtpl->parse( 'main.step3.data' );
			}
			
			$xtpl->parse( 'main.step3' );
		}
		elseif( $substep == 4 )
		{
			global $sys_info, $nv_update_config;
			
			if( substr( $sys_info['os'], 0, 3 ) == 'WIN' )
			{
				$xtpl->parse( 'main.step4.win' );
			}
			
			if( $array['FTP_nosupport'] )
			{
				$xtpl->parse( 'main.step4.FTP_nosupport' );
			}
			elseif( $array['check_FTP'] )
			{
				$xtpl->assign( 'ACTIONFORM', NV_BASE_SITEURL . "install/update.php?step=" . $this->config['step'] . "&amp;substep=" . $substep );
				
				if( ! empty( $array['ftpdata']['error'] ) and $array['ftpdata']['show_ftp_error'] )
				{
					$xtpl->parse( 'main.step4.check_FTP.errorftp' );
				}
				
				$xtpl->parse( 'main.step4.check_FTP' );
			}
			
			// Danh sach cac file se bi tac dong
			foreach( $nv_update_config['updatelog']['file_list'] as $fileID => $fileName )
			{
				$xtpl->assign( 'ROW', array( 'id' => $fileID, 'name' => $fileName, 'status' => in_array( $fileName, $array['file_list'] ) ? '' : ' iok' ) );
				$xtpl->parse( 'main.step4.loop' );
			}
			
			if( $array['file_backup'] )
			{
				$xtpl->assign( 'URL_DUMPBACKUP', NV_BASE_SITEURL . "install/update.php?step=" . $this->config['step'] . "&substep=" . $substep . '&dumpfile' );
				$xtpl->parse( 'main.step4.file_backup' );
			}
			
			$xtpl->parse( 'main.step4' );
		}
		elseif( $substep == 5 )
		{
			if( $array['error'] )
			{
				$xtpl->parse( 'main.step5.error' );
			}
			else
			{
				$xtpl->parse( 'main.step5.guide' );
			}
			
			$xtpl->parse( 'main.step5' );
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
	
	public function step3( $array )
	{
		global $global_config;
		
		$xtpl = new XTemplate( "updatestep3.tpl", NV_ROOTDIR . "/install/tpl" );
		$xtpl->assign( 'LANG', $this->lang );
		$xtpl->assign( 'CONFIG', $this->config );
		$xtpl->assign( 'DATA', $array );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
		$xtpl->assign( 'URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=deleteupdate&amp;checksess=' . md5( $global_config['sitekey'] . session_id() ) );
		$xtpl->assign( 'URL_GOHOME', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );
		$xtpl->assign( 'URL_GOADMIN', NV_BASE_ADMINURL );

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
	
	public function PackageErrorTheme()
	{
		global $global_config;
		
		$xtpl = new XTemplate( "packageerror.tpl", NV_ROOTDIR . "/install/tpl" );
		$xtpl->assign( 'LANG', $this->lang );
		$xtpl->assign( 'CONFIG', $this->config );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
		$xtpl->assign( 'URL_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=deleteupdate&amp;checksess=' . md5( $global_config['sitekey'] . session_id() ) );
		$xtpl->assign( 'URL_RETURN', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=siteinfo' );
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
	
	public function version_info( $array )
	{		
		$xtpl = new XTemplate( "updatestep3.tpl", NV_ROOTDIR . "/install/tpl" );
		$xtpl->assign( 'LANG', $this->lang );
		$xtpl->assign( 'CONFIG', $this->config );
		$xtpl->assign( 'DATA', $array );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
		if( $array['checkversion'] )
		{
			$xtpl->parse( 'version_info.checkversion' );
		}
		
		$xtpl->parse( 'version_info' );
		echo( $xtpl->text( 'version_info' ) );
		exit();
	}
	
	public function module_info( $onlineModules, $userModules )
	{		
		global $global_config;
		
		$xtpl = new XTemplate( "updatestep3.tpl", NV_ROOTDIR . "/install/tpl" );
		$xtpl->assign( 'LANG', $this->lang );
		$xtpl->assign( 'CONFIG', $this->config );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
		$i = 0;
		foreach( $userModules as $mod )
		{
			if( $mod['mode'] != 'sys' )
			{
				$mod['note'] = $this->lang['update_mod_othermod'];
			}
			else
			{
				$mod['note'] = $this->lang['update_mod_uptodate'];
			}
		
			$mod['class'] = $i ++ % 2 ? 'specalt' : 'spec';
			$mod['time'] = nv_date( 'd/m/y H:i', $mod['time'] );
			
			$xtpl->assign( 'ROW', $mod );
			$xtpl->parse( 'module_info.loop' );
		}
		
		$xtpl->parse( 'module_info' );
		echo( $xtpl->text( 'module_info' ) );
		exit();
	}
	
	public function log( $step, $substep, $timeID, $content, $status )
	{
		global $client_info;
		
		$file_log = 'log-update-' . nv_date( 'H-i-s-d-m-Y', $timeID ) . '-' . $client_info['session_id'] . '.log';
		
		$time = nv_date( 'H:i:s_d-m-Y' );
		$status = empty( $status ) ? 'FAILURE' : 'SUCCESS';
		$contents = $time . '  |  ' . $client_info['ip'] . '  |  ' . $content . '  |  ' . $status . "\n";
		
		if( ! file_exists( NV_ROOTDIR . "/" . NV_LOGS_DIR . "/data_logs/" . $file_log ) )
		{
			$contents = $this->lang['update_log_start'] . ':   ' . $time . "\n" . $contents;
		}
		
		file_put_contents( NV_ROOTDIR . "/" . NV_LOGS_DIR . "/data_logs/" . $file_log, $contents, FILE_APPEND );
	} 
}

// Su dung session de luu phien lam viec
$nv_update_config['updatelog'] = $nv_Request->get_string( 'updatelog', 'session', '' );

if( empty( $nv_update_config['updatelog'] ) )
{
	$nv_update_config['updatelog'] = array();
}
else
{
	$nv_update_config['updatelog'] = unserialize( $nv_update_config['updatelog'] );
}

// Buoc nang cap
$nv_update_config['step'] = $nv_Request->get_int( 'step', 'get', 1 );
if( $nv_update_config['step'] < 1 or ! isset( $nv_update_config['updatelog']['step'] ) or $nv_update_config['step'] > 3 or $nv_update_config['updatelog']['step'] < ( $nv_update_config['step'] - 1 ) )
{
	$nv_update_config['step'] = 1;
}

$NvUpdate = new NvUpdate( $nv_update_config );

// Trang chinh
$contents = "";

if( $nv_update_config['step'] == 1 )
{
	if( $NvUpdate->check_package() === false )
	{
		$contents = $NvUpdate->PackageErrorTheme();
	}
	else
	{
		$array = array();
		$array['current_version'] = $NvUpdate->build_full_ver( $global_config['version'], $global_config['revision'] );
		
		if( in_array( $array['current_version'], $nv_update_config['allow_old_version'] ) )
		{
			$array['ability'] = $lang_module['update_ability_1'];
			$array['isupdate_allow'] = true;
		}
		else
		{
			$array['ability'] = $lang_module['update_ability_0'];
			$array['isupdate_allow'] = false;
		}
		
		$nv_update_config['updatelog']['step'] = ( $array['isupdate_allow'] ) ? 1 : 0;
		$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
		
		$contents = $NvUpdate->step1( $array );
	}
}
elseif( $nv_update_config['step'] == 2 )
{
	$array = array();
	$set_log = false;
	
	$nv_update_config['substep'] = $nv_Request->get_int( 'substep', 'get', 1 );
	if( $nv_update_config['substep'] < 1 or ! isset( $nv_update_config['updatelog']['substep'] ) or $nv_update_config['substep'] > 5 or $nv_update_config['updatelog']['substep'] < ( $nv_update_config['substep'] - 1 ) )
	{
		$nv_update_config['substep'] = 1;
	}
	
	if( $nv_update_config['substep'] == 1 ) // Backup CSDL
	{
		// Backup CSDL
		if( $nv_Request->isset_request( 'dump', 'get' ) )
		{
			$checksess = filter_text_input( 'checksess', 'get', '' );
			if( $checksess != md5( $global_config['sitekey'] . session_id() ) ) die( 'Error!!!' );
			
			// Danh dau phien bat dau khoi tao
			if( ! isset( $nv_update_config['updatelog']['starttime'] ) )
			{
				$nv_update_config['updatelog']['starttime'] = NV_CURRENTTIME;
				$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
			}
			
			$type = filter_text_input( 'type', 'get', '' );
			
			$current_day = mktime( 0, 0, 0, date( "n", NV_CURRENTTIME ), date( "j", NV_CURRENTTIME ), date( "Y", NV_CURRENTTIME ) );

			$contents = array();
			$contents['savetype'] = ( $type == "sql" ) ? "sql" : "gz";
			$file_ext = ( $contents['savetype'] == "sql" ) ? "sql" : "sql.gz";
			$log_dir = NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup";

			$contents['filename'] = $log_dir . "/" . md5( nv_genpass( 10 ) . $client_info['session_id'] ) . "_" . $current_day . "." . $file_ext;

			if( ! file_exists( $contents['filename'] ) )
			{
				$contents['tables'] = array();
				$res = $db->sql_query( "SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'" );
				
				while( $item = $db->sql_fetchrow( $res ) )
				{
					$contents['tables'][] = $item[0];
				}
				$db->sql_freeresult( $res );

				$contents['type'] = "all";

				include ( NV_ROOTDIR . "/includes/core/dump.php" );

				$dump = nv_dump_save( $contents );
				
				// Ghi log
				$NvUpdate->log( $nv_update_config['step'], $nv_update_config['substep'], $nv_update_config['updatelog']['starttime'], $lang_module['update_dump'] . ' ' . $contents['savetype'], $dump );
			
				if( $dump == false )
				{
					die( $lang_module['update_dump_error'] );
				}
				else
				{
					$file = str_replace( NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup/", "", $dump[0] );
					
					die( $lang_module['update_dump_ok'] . ' ' . nv_convertfromBytes( $dump[1] ) . '<br /><a href="' . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=database&amp;" . NV_OP_VARIABLE . "=getfile&amp;filename=" . $file. "&amp;checkss=" . md5( $file . $client_info['session_id'] . $global_config['sitekey'] ) . '" title="' . $lang_module['update_dump_download'] . '">' . $lang_module['update_dump_download'] . '</a>'  );
				}
			}
			else
			{
				die( $lang_module['update_dump_exist'] );
			}
		}
		
		// Co the bo qua buoc nay nen luu lai buoc 2
		$nv_update_config['updatelog']['substep'] = 1;
		$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
	}
	elseif( $nv_update_config['substep'] == 2 ) // Kiem tra va thong ke cac cong viec se thuc hien
	{
		// Cong viec di chuyen file
		if( ! isset( $nv_update_config['updatelog']['file_list'] ) )
		{
			$file_list = $NvUpdate->list_all_file();
			$nv_update_config['updatelog']['file_list'] = $file_list;
			$set_log = true;
		}
		
		// Cong viec nang cap CSDL
		if( ! isset( $nv_update_config['updatelog']['data_list'] ) )
		{
			$data_list = $NvUpdate->list_data_update();
			$nv_update_config['updatelog']['data_list'] = $data_list;
			$set_log = true;
		}
		
		$array['taskempty'] = false;
		if( empty( $nv_update_config['updatelog']['data_list'] ) and empty( $nv_update_config['updatelog']['file_list'] ) )
		{
			$array['taskempty'] = true;
			$nv_update_config['updatelog']['step'] = 2;
			$set_log = true;
		}
		
		if( $array['taskempty'] == false )
		{
			// Nang cap bang tay
			if( $nv_update_config['update_auto_type'] == 0 )
			{
				$array['guide'] = "N/A";
				
				if( file_exists( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' ) )
				{
					$array['guide'] = file_get_contents( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' );
				}
				
				$nv_update_config['updatelog']['step'] = 2;
				$set_log = true;
			}
			// Nang cap nua tu dong
			elseif( $nv_update_config['update_auto_type'] == 2 )
			{
				$nv_update_config['updatelog']['substep'] = 2;
				$set_log = true;
			}
			// Nang cap tu dong
			else
			{
				$nv_update_config['updatelog']['substep'] = 2;
				$set_log = true;
				$array['file_list'] = $nv_update_config['updatelog']['file_list'];
				$array['data_list'] = $nv_update_config['updatelog']['data_list'];
			}
		}
		
		if( $set_log === true )
		{
			$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
		}
		
		// Neu nang cap nua tu dong ma khong co thay doi file nao thi chuyen trang sang buoc tiep theo luon
		if( $nv_update_config['update_auto_type'] == 2 and empty( $nv_update_config['updatelog']['file_list'] ) )
		{
			Header( 'Location:' . NV_BASE_SITEURL . 'install/update.php?step=2&substep=3' );
			exit();
		}
	}
	elseif( $nv_update_config['substep'] == 3 ) // Buoc cap nhat CSDL
	{
		// Kiem tra loi neu buoc cap nhat nua tu dong
		$array['errorStepMoveFile'] = false;
		if( $nv_update_config['update_auto_type'] == 2 )
		{
			$check_list_file = $NvUpdate->list_all_file();
			if( ! empty( $check_list_file ) ) $array['errorStepMoveFile'] = true;
			
			// Neu khong co cong viec nang cap CSDL nao va kieu nang cap nua tu dong thi chuyen den buoc 3
			if( empty( $nv_update_config['updatelog']['data_list'] ) )
			{
				$nv_update_config['updatelog']['step'] = 2;
				$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
				
				Header( 'Location:' . NV_BASE_SITEURL . 'install/update.php?step=3' );
				exit();
			}
		}
		
		// Neu khong co cong viec nang cap CSDL nao
		if( empty( $nv_update_config['updatelog']['data_list'] ) )
		{
			$nv_update_config['updatelog']['substep'] = 3;
			$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
			
			Header( 'Location:' . NV_BASE_SITEURL . 'install/update.php?step=2&substep=4' );
			exit();
		}
		
		if( ! $array['errorStepMoveFile'] )
		{
			// Tien trinh bat dau chay
			if( $nv_Request->isset_request( 'load', 'get' ) )
			{
				// Danh dau phien bat dau khoi tao
				if( ! isset( $nv_update_config['updatelog']['starttime'] ) )
				{
					$nv_update_config['updatelog']['starttime'] = NV_CURRENTTIME;
					$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
				}

				$func = filter_text_input( 'load', 'get', '' );
				
				$nv_update_baseurl = NV_BASE_SITEURL . 'install/update.php?step=2&substep=3&load=' . $func;
				
				/*
				Chuan hoa tra ve cho Ajax
				status|funcname|functitle|url|lang|message|stop|allcomplete
				
				status:
				- 0: That bai
				- 1: Thanh cong
				
				funcname: Ten ham tiep theo thuc hien
				functitle: Ten cong viec tiep theo se thuc hien
				url: Duong dan tiep theo duoc load
				lang: Cac ngon ngu bi loi
				message: Thong tin (duoc add vao functitle sau dau -)
				stop: Dung tien trinh
				allcomplete: Hoan tat tat ca tien trinh
				*/
				
				$return = array(
					'status' => '0',
					'funcname' => 'NO',
					'functitle' => 'NO',
					'url' => 'NO',
					'lang' => 'NO',
					'message' => 'NO',
					'stop' => '1',
					'allcomplete' => '0',
				);
				
				if( ! isset( $nv_update_config['updatelog']['data_list'][$func] ) ) $return['stop'] = '1';
				if( ! nv_function_exists( $func ) ) $return['stop'] = '1';
				
				$check_return = call_user_func( $func ); // Goi ham thuc hien nang cap
				
				// Trang thai thuc hien
				$return['status'] = $check_return['status'] ? '1' : '0';
				$return['stop'] = ( $check_return['status'] == 0 and $nv_update_config['updatelog']['data_list'][$func]['require'] == 2 ) ? '1' : '0';
				$return['message'] = $check_return['message'];
				
				$last_task = end( $nv_update_config['updatelog']['data_list'] );
				$last_task_key = key( $nv_update_config['updatelog']['data_list'] );
				// Kiem tra ket thuc tien trinh
				if( $last_task_key == $func and $return['stop'] == '0' )
				{
					$return['allcomplete'] = '1';
					
					// Ghi lai de chuyen sang buoc tiep theo
					if( $nv_update_config['update_auto_type'] == 2 )
					{
						if( file_exists( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' ) )
						{
							$nv_update_config['updatelog']['substep'] = 4;
							$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
						}
						else
						{
							$nv_update_config['updatelog']['step'] = 2;
							$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
						}
					}
					else
					{
						$nv_update_config['updatelog']['substep'] = 3;
						$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
					}
				}
				
				if( $return['allcomplete'] != '1' and $return['stop'] != '1' )
				{
					// Kiem tra tiep tuc, neu tiep tuc thi khong can URL
					if( $check_return['next'] )
					{
						$is_get_next = false;
						foreach( $nv_update_config['updatelog']['data_list'] as $k => $v )
						{
							if( $is_get_next == true )
							{
								$return['funcname'] = $k;
								$return['functitle'] = $v['title'];
								break;
							}
							if( $k == $func ) $is_get_next = true;
						}
						unset( $is_get_next, $k, $v );
					}
					else
					{
						$return['url'] = $check_return['link'];
						$return['funcname'] = $func;
						$return['functitle'] = $nv_update_config['updatelog']['data_list'][$func]['title'];
					}
				}
				
				// Ghi log passed
				$nv_update_config['updatelog']['data_passed'][$func] = $check_return['status'] ? 1 : 2;
				$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
				
				// Ghi logs
				$log_message = $nv_update_config['updatelog']['data_list'][$func]['title'] . ( $check_return['message'] ? ( ' - ' . $check_return['message'] ) : '' );
				$NvUpdate->log( $nv_update_config['step'], $nv_update_config['substep'], $nv_update_config['updatelog']['starttime'], $log_message, $check_return['status'] );
				
				die( implode( '|', $return ) );
			}
			
			$array['task'] = array();
			$array['started'] = false; // Da bat dau chua
			$array['nextfunction'] = ''; // Ham tiep theo se thuc hien
			$array['nextftitle'] = ''; // Ten cong viec tiep theo se thuc hien
			$array['stopprocess'] = array(); // Dung tien trinh
			$array['AllPassed'] = false; // Da hoan tat toan bo cac cong viec
			
			$get_next_func = false;
			$num_passed = 0;
			
			foreach( $nv_update_config['updatelog']['data_list'] as $funcsname => $task )
			{
				// Khoi tao ham tiep theo thuc hien
				if( empty( $array['nextfunction'] ) )
				{
					$array['nextfunction'] = $funcsname;
					$array['nextftitle'] = $task['title'];
				}
				
				// Danh dau ham tiep theo se thuc hien
				if( $get_next_func == true )
				{
					$array['nextfunction'] = $funcsname;
					$array['nextftitle'] = $task['title'];
					$get_next_func = false;
				}
				
				// $passed:
				//	- 0: Chua thuc hien
				//	- 1: Da hoan thanh
				//	- 2: That bai				
				$passed = isset( $nv_update_config['updatelog']['data_passed'][$funcsname] ) ? $nv_update_config['updatelog']['data_passed'][$funcsname] : 0;
				switch( $passed )
				{
					case 0: $class = ''; break;
					case 1: $class = ' iok'; break;
					default: $class = ( ( $task['require'] == 0 ) ? ' iok' : ( ( $task['require'] == 1 ) ? ' iwarn' : ' ierror' ) );
				}
				$class_trim = trim( $class );
				
				// Da thuc hien thi danh dau da thuc hien
				if( $array['started'] == false and $passed > 0 ) $array['started'] = true;
				
				// Tinh toan ham tiep theo se thuc hien, them vao danh dach cac cong viec da thuc hien (du thanh cong hay that bai)
				if( $passed > 0 )
				{
					$get_next_func = true;
					$num_passed = $num_passed + 1;
				}
				
				// Dung tien trinh
				if( $class_trim == 'ierror' and empty( $array['stopprocess'] ) )
				{
					$array['stopprocess'] = array(
						'id' => $funcsname,
						'title' => $task['title']
					);
				}
				
				$status_title = $lang_module['update_task' . $class_trim ];
				
				$array['task'][$funcsname] = array(
					'id' => $funcsname,
					'title' => $task['title'],
					'require' => $task['require'],
					'class' => $class,
					'status' => $status_title,
				);
			}
			
			// Kiem tra hoan tat
			if( $num_passed == sizeof( $array['task'] ) )
			{
				$array['AllPassed'] = true;
				
				// Danh dau hoan tat de tiep tuc buoc di chuyen file (neu KHONG xay ra loi)
				if( empty( $array['stopprocess'] ) )
				{
					if( $nv_update_config['update_auto_type'] == 2 )
					{
						if( file_exists( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' ) )
						{
							$nv_update_config['updatelog']['substep'] = 4;
							$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
						}
						else
						{
							$nv_update_config['updatelog']['step'] = 2;
							$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
						}
					}
					else
					{
						$nv_update_config['updatelog']['substep'] = 3;
						$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
					}
				}
			}
			
			// Kiem tra buoc tiep theo
			if( $nv_update_config['update_auto_type'] == 2 )
			{
				if( file_exists( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' ) )
				{
					$array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=5';
				}
				else
				{
					$array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=3';
				}
			}
			else
			{
				$array['NextStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=4';
			}
			
			// Xac dinh buoc truoc
			if( $nv_update_config['update_auto_type'] == 2 and empty( $nv_update_config['updatelog']['file_list'] ) )
			{
				$array['PrevStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=1';
			}
			else
			{
				$array['PrevStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=2';
			}
		}
	}
	elseif( $nv_update_config['substep'] == 4 ) // Di chuyen cac file
	{
		// Danh dau phien bat dau khoi tao
		if( ! isset( $nv_update_config['updatelog']['starttime'] ) )
		{
			$nv_update_config['updatelog']['starttime'] = NV_CURRENTTIME;
			$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
		}
		
		// Danh sach cac file con lai
		$array['file_list'] = $NvUpdate->list_all_file();
		
		// Download file thay doi
		if( $nv_Request->isset_request( 'downfile', 'get' ) )
		{
			$checksess = filter_text_input( 'checksess', 'get', '' );
			if( $checksess != md5( $global_config['sitekey'] . session_id() ) ) die( 'Error!!!' );
			
			$file = filter_text_input( 'downfile', 'get', '' );
			
			if( ! file_exists( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $file ) )
			{
				$NvUpdate->log( $nv_update_config['step'], $nv_update_config['substep'], $nv_update_config['updatelog']['starttime'], $lang_module['update_log_dump_file_down'], false );
				die('Error Access!!!');
			}
			else
			{
				// Tai ve roi moi xem la da hoan thanh backup
				$nv_update_config['updatelog']['file_backuped'] = 1;
				$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );

				$NvUpdate->log( $nv_update_config['step'], $nv_update_config['substep'], $nv_update_config['updatelog']['starttime'], $lang_module['update_log_dump_file_down'], true );
				
				//Download file
				require_once ( NV_ROOTDIR . '/includes/class/download.class.php' );

				$download = new download( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $file, NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs', 'backup_update_' . date( 'Y_m_d' ) . '.zip' );
				$download->download_file();
				exit();
			}
		}
		
		// Di chuyen thu cong
		$array['manual'] = false;
		$array['manualcomplete'] = false;
		
		if( $nv_Request->isset_request( 'manual', 'get' ) )
		{
			$array['manual'] = true;
			if( empty( $array['file_list'] ) )
			{
				$array['manualcomplete'] = true;
			}
		}
		
		// Sao luu file thay doi
		if( $nv_Request->isset_request( 'dumpfile', 'get' ) )
		{
			$zip_file_backup = array();
			foreach ( $nv_update_config['updatelog']['file_list'] as $file_i )
			{
				if ( is_file( NV_ROOTDIR . '/' . $file_i ) )
				{
					$zip_file_backup[] = NV_ROOTDIR . '/' . $file_i;
				}
			}
			if ( ! empty( $zip_file_backup ) )
			{
				$file_src = 'backup_update_' . date( 'Y_m_d' ) . '_' . md5( $global_config['sitekey'] . session_id() ) . '.zip';

				// Kiem tra file ton tai
				$filename2 = $file_src;
				$i = 1;
				while( file_exists( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $filename2 ) )
				{
					$filename2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $file_src );
					$i++;
				}
				
				require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
				$zip = new PclZip( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/' . $filename2 );
				$return = $zip->add( $zip_file_backup, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );
				
				if ( empty( $return ) )
				{
					// Ghi Log
					$NvUpdate->log( $nv_update_config['step'], $nv_update_config['substep'], $nv_update_config['updatelog']['starttime'], $lang_module['update_log_dump_file'], false );
					
					die( $lang_module['update_file_backup_error'] );
				}
				else
				{				
					// Ghi log
					$NvUpdate->log( $nv_update_config['step'], $nv_update_config['substep'], $nv_update_config['updatelog']['starttime'], $lang_module['update_log_dump_file'], true );
					
					die( '<a href="' . NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=4&downfile=' . $filename2 . '&checksess=' . md5( $global_config['sitekey'] . session_id() ) . '" title="' . $lang_module['update_log_dump_file_down'] . '">' . $lang_module['update_file_backup_ok'] . '</a>' );
				}
			}
		}
		
		$ftp_check_login = intval( $global_config['ftp_check_login'] );
		$show_ftp_error = false;
		// Luu thong tin cau hinh FTP
		if( $nv_Request->isset_request( 'modftp', 'post' ) )
		{
			// Cau hinh FTP
			$ftp_check_login = 1;
			$global_config['ftp_server'] = $nv_Request->get_string( 'ftp_server', 'post', 'localhost' );
			$global_config['ftp_port'] = $nv_Request->get_int( 'ftp_port', 'post', 21 );
			$global_config['ftp_user_name'] = $nv_Request->get_string( 'ftp_user_name', 'post', '' );
			$global_config['ftp_user_pass'] = $nv_Request->get_string( 'ftp_user_pass', 'post', '' );
			$global_config['ftp_path'] = $nv_Request->get_string( 'ftp_path', 'post', '/' );
			
			$show_ftp_error = true;
		}
		
		$array['ftpdata'] = array(
			'ftp_server' => $global_config['ftp_server'],
			'ftp_port' => $global_config['ftp_port'],
			'ftp_user_name' => $global_config['ftp_user_name'],
			'ftp_user_pass' => $global_config['ftp_user_pass'],
			'ftp_path' => $global_config['ftp_path'],
			'error' => '',
			'show_ftp_error' => $show_ftp_error
		);
		
		// Tu dong nhan dien remove_path
		if( $nv_Request->isset_request( 'tetectftp', 'post' ) )
		{
			$ftp_server = nv_unhtmlspecialchars( filter_text_input( 'ftp_server', 'post', '', 1, 255 ) );
			$ftp_port = intval( filter_text_input( 'ftp_port', 'post', '21', 1, 255 ) );
			$ftp_user_name = nv_unhtmlspecialchars( filter_text_input( 'ftp_user_name', 'post', '', 1, 255 ) );
			$ftp_user_pass = nv_unhtmlspecialchars( filter_text_input( 'ftp_user_pass', 'post', '', 1, 255 ) );
			
			if( ! $ftp_server or ! $ftp_user_name or ! $ftp_user_pass )
			{
				die( 'ERROR|' . $lang_module['ftp_error_empty'] );
			}
			
			if( ! defined( 'NV_FTP_CLASS' ) ) require( NV_ROOTDIR . '/includes/class/ftp.class.php' );
			if( ! defined( 'NV_BUFFER_CLASS' ) ) require( NV_ROOTDIR . '/includes/class/buffer.class.php' );
			
			$ftp = new NVftp( $ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 10 ), $ftp_port );
			
			if( ! empty( $ftp->error ) )
			{
				$ftp->close();
				die( 'ERROR|' . (string)$ftp->error );
			}
			else
			{
				$list_valid = array( NV_CACHEDIR, NV_DATADIR, "images", "includes", "js", "language", NV_LOGS_DIR, "modules", NV_SESSION_SAVE_PATH, "themes", NV_TEMP_DIR, NV_UPLOADS_DIR );
			
				$ftp_root = $ftp->detectFtpRoot( $list_valid, NV_ROOTDIR );
				
				if( $ftp_root === false )
				{
					$ftp->close();
					die( 'ERROR|' . ( empty( $ftp->error ) ? $lang_module['ftp_error_detect_root'] : (string)$ftp->error ) );
				}
				
				$ftp->close();
				die( 'OK|'. $ftp_root );
			}
			
			$ftp->close();
			die( 'ERROR|' . $lang_module['ftp_error_detect_root'] );
		}

		// Neu khong co file can di chuyen thi chuyen sang buoc 2/5 hoac buoc 3
		if( empty( $nv_update_config['updatelog']['file_list'] ) )
		{
			// Chuyen den buoc 3 neu khong co huong dan nang cap khac bang tay
			if( ! file_exists( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' ) )
			{
				$nv_update_config['updatelog']['step'] = 2;
				$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
				
				Header( 'Location:' . NV_BASE_SITEURL . 'install/update.php?step=3' );
				exit();
			}
			else // Chuyen den buoc 2/5
			{
				$nv_update_config['updatelog']['substep'] = 4;
				$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
				
				Header( 'Location:' . NV_BASE_SITEURL . 'install/update.php?step=2&substep=5' );
				exit();
			}
		}
		
		// Kiem tra FTP
		$array['check_FTP'] = false;
		$array['FTP_nosupport'] = false;
		if( $sys_info['ftp_support'] )
		{
			if( $ftp_check_login == 1 ) // Dang nhap FTP
			{
				$ftp_server = nv_unhtmlspecialchars( $global_config['ftp_server'] );
				$ftp_port = intval( $global_config['ftp_port'] );
				$ftp_user_name = nv_unhtmlspecialchars( $global_config['ftp_user_name'] );
				$ftp_user_pass = nv_unhtmlspecialchars( $global_config['ftp_user_pass'] );
				$ftp_path = nv_unhtmlspecialchars( $global_config['ftp_path'] );
				
				include_once ( NV_ROOTDIR . '/includes/class/ftp.class.php' );
				$ftp = new NVftp( $ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 20 ), $ftp_port );
				
				if( ! empty( $ftp->error ) )
				{
					$array['check_FTP'] = true;
					$array['ftpdata']['error'] = $ftp->error;
				}
				elseif( $ftp->chdir( $ftp_path ) === false )
				{
					$array['check_FTP'] = true;
					$array['ftpdata']['error'] = $lang_module['ftp_error_path'];
				}
				else
				{
					// Ghi nhat ki
					$NvUpdate->log( $nv_update_config['step'], $nv_update_config['substep'], $nv_update_config['updatelog']['starttime'], $lang_module['update_log_ftp'], true );
					
					$array_config = array(
						'ftp_server' => $global_config['ftp_server'],
						'ftp_port' => $global_config['ftp_port'],
						'ftp_user_name' => $global_config['ftp_user_name'],
						'ftp_user_pass' => $global_config['ftp_user_pass'],
						'ftp_path' => $global_config['ftp_path'],
						'ftp_check_login' => 1,
					);
					
					// Luu lai cau hinh FTP
					foreach( $array_config as $config_name => $config_value )
					{
						$db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` 
						SET `config_value`=" . $db->dbescape_string( $config_value ) . " 
						WHERE `config_name` = " . $db->dbescape_string( $config_name ) . " 
						AND `lang` = 'sys' AND `module`='global' 
						LIMIT 1" );
					}
					
					nv_save_file_config_global();
					
					$nv_update_config['updatelog']['ftp_check_login'] = 1;
					$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
				}
				
				$ftp->close();
			}
			else
			{
				$array['check_FTP'] = true;
			}
		}
		else
		{
			$array['FTP_nosupport'] = true;
		}
		
		$array['file_backup'] = true;
		if( ! empty( $nv_update_config['updatelog']['file_backuped'] ) ) $array['file_backup'] = false;
	}
	elseif( $nv_update_config['substep'] == 5 ) // Huong dan nang cap giao dien bang tay
	{
		$array['guide'] = "";
		$array['error'] = false;
		if( file_exists( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' ) )
		{
			$array['guide'] = file_get_contents( NV_ROOTDIR . '/install/update_docs_' . NV_LANG_UPDATE . '.html' );
		}
		else
		{
			$array['error'] = true;
		}
		
		// Buoc truoc
		if( $nv_update_config['update_auto_type'] == 2 )
		{
			$array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=3';
		}
		else
		{
			$array['BackStepUrl'] = NV_BASE_SITEURL . 'install/update.php?step=2&amp;substep=4';
		}
		
		$nv_update_config['updatelog']['step'] = 2;
		$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
	}
	
	$contents = $NvUpdate->step2( $array, $nv_update_config['substep'] );
}
elseif( $nv_update_config['step'] == 3 ) // Hoan tat nang cap
{
	// Danh dau phien bat dau khoi tao
	if( ! isset( $nv_update_config['updatelog']['starttime'] ) )
	{
		$nv_update_config['updatelog']['starttime'] = NV_CURRENTTIME;
		$nv_Request->set_session( 'updatelog', serialize( $nv_update_config['updatelog'] ) );
	}
	
	$array = array();

	// Lay thong tin phien ban va module
	if( $nv_Request->isset_request( 'load', 'get' ) )
	{
		$type = filter_text_input( 'load', 'get', '' );
		
		if( $type == 'ver' )
		{
			$version = nv_geVersion( 0 );
			$array['current_version'] = $NvUpdate->build_full_ver( $global_config['version'], $global_config['revision'] );
			$array['newVersion'] = ( string ) $version->version . ' - ' . ( string ) $version->name;
			
			$array['checkversion'] = false;
			if ( nv_version_compare( $global_config['version'], $version->version ) < 0 )
			{
				$array['checkversion'] = true;
			}
			
			$NvUpdate->version_info( $array );
		}
		elseif( $type = 'mod' )
		{
			$_modules = nv_getModVersion( 0 );
			$_modules = nv_object2array( $_modules );
			$_modules = $_modules['module'];
			$onlineModules = array();
			foreach ( $_modules as $m )
			{
				$name = array_shift( $m );
				$onlineModules[$name] = $m;
				unset( $onlineModules[$name]['date'] );
				$onlineModules[$name]['pubtime'] = strtotime( $m['date'] );
			}

			$userModules = array();
			
			$lang_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
			while( list( $lang ) = $db->sql_fetchrow( $lang_query ) )
			{
				$sql = "SELECT b.module_file, b.mod_version, b.author FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` AS a INNER JOIN `" . $db_config['prefix'] . "_setup_modules` AS b ON a.title=b.title  GROUP BY b.module_file ORDER BY b.module_file ASC";
				$result = $db->sql_query( $sql );
				
				while( $row = $db->sql_fetchrow( $result ) )
				{
					if( isset( $userModules[$row['module_file']] ) ) continue;
					
					$v = "";
					$p = 0;
					if ( preg_match( "/^([^\s]+)\s+([\d]+)$/", $row['mod_version'], $matches ) )
					{
						$v = ( string )$matches[1];
						$p = ( int )$matches[2];
					}
					
					$userModules[$row['module_file']] = array(
						'module_file' => $row['module_file'],
						'mod_version' => $v,
						'mode' => isset( $onlineModules[$row['module_file']]['mode'] ) ? $onlineModules[$row['module_file']]['mode'] : false,
						'time' => $p,
						'author' => $row['author']
					);
				}
			}

			$NvUpdate->module_info( $onlineModules, $userModules );
		}
		else
		{
			die('&nbsp;');
		}
	}
	
	$contents = $NvUpdate->step3( $array );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $NvUpdate->template( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>