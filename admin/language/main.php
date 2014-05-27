<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nv_lang_data'];

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );


$array_lang_setup = array();
$result = $db->query( 'SELECT lang, setup FROM ' . $db_config['prefix'] . '_setup_language' );
while( $row = $result->fetch() )
{
	$array_lang_setup[$row['lang']] = intval( $row['setup'] );
}

if( defined( 'NV_IS_GODADMIN' ) or ( $global_config['idsite'] > 0 and defined( 'NV_IS_SPADMIN' ) ) )
{
	$checksess = $nv_Request->get_title( 'checksess', 'get', '' );
	$keylang = $nv_Request->get_title( 'keylang', 'get', '', 1 );
	$deletekeylang = $nv_Request->get_title( 'deletekeylang', 'get', '', 1 );

	if( $nv_Request->isset_request( 'activelang', 'get' ) and $checksess == md5( 'activelang_' . $keylang . session_id() ) and preg_match( '/^[a-z]{2}$/', $keylang ) )
	{
        if( empty( $global_config['idsite'] ) )
		{
		    $activelang = $nv_Request->get_int( 'activelang', 'get', 0 );
            $allow_sitelangs = $global_config['allow_sitelangs'];

            $temp = ( $activelang == 1 ) ? $lang_global['yes'] : $lang_global['no'];
            nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nv_lang_slsite'], ' langkey : ' . $keylang . ' [ ' . $temp . ' ]', $admin_info['userid'] );

            if( $activelang )
            {
                $allow_sitelangs[] = $keylang;
            }
            elseif( $keylang != $global_config['site_lang'] )
            {
                $allow_sitelangs = array_diff( $allow_sitelangs, array( $keylang ) );
            }

            $allow_sitelangs = array_unique( $allow_sitelangs );

            $sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang='sys' AND module = 'global' AND config_name = 'allow_sitelangs'" );
            $sth->bindValue( ':config_value', implode( ',', $allow_sitelangs ), PDO::PARAM_STR );
            $sth->execute();

			nv_save_file_config_global();

            $xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
            $xtpl->parse( 'activelang' );
            $contents = $xtpl->text( 'activelang' );

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme( $contents );
            include NV_ROOTDIR . '/includes/footer.php';
            exit();
		}
        else
        {
            Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=site&' . NV_OP_VARIABLE . '=edit&idsite=' . $global_config['idsite'] );
            die();
        }
	}
	elseif( $checksess == md5( $keylang . session_id() ) and in_array( $keylang, $global_config['allow_adminlangs'] ) )
	{
		if( isset( $array_lang_setup[$keylang] ) and $array_lang_setup[$keylang] == 1 )
		{
			include NV_ROOTDIR . '/includes/header.php';
			echo nv_admin_theme( $lang_module['nv_data_setup'] );
			include NV_ROOTDIR . '/includes/footer.php';
			exit();
		}
		elseif( $global_config['lang_multi'] )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nv_setup_new'] . ' ' . $lang_module['nv_lang_data'], ' langkey : ' . $keylang, $admin_info['userid'] );

			$site_theme = $db->query( "SELECT config_value FROM " . NV_CONFIG_GLOBALTABLE . " where lang='" . $global_config['site_lang'] . "' AND module='global' AND config_name='site_theme'" )->fetchColumn();

			$global_config['site_theme'] = $site_theme;

			try
			{
			  $db->exec( 'ALTER DATABASE ' . $db_config['dbname'] . ' DEFAULT CHARACTER SET utf8 COLLATE ' . $db_config['collation'] );
			}
			catch( PDOException $e )
			{
			  trigger_error( $e->getMessage() );
			}
			require_once NV_ROOTDIR . '/includes/action_' . $db->dbtype . '.php';

			$sql_create_table = nv_create_table_sys( $keylang );

			foreach( $sql_create_table as $query )
			{
				try
				{
					$db->query( $query );
				}
				catch (PDOException $e)
				{
					include NV_ROOTDIR . '/includes/header.php';
					echo nv_admin_theme( 'ERROR SETUP SQL: <br />' . $query );
					include NV_ROOTDIR . '/includes/footer.php';
					exit();
				}
			}
			$db->columns_add( NV_COUNTER_GLOBALTABLE, $keylang . '_count', 'integer', 2147483647, true, 0);

			if( defined( 'NV_MODULE_SETUP_DEFAULT' ) )
			{
				$lang_module['modules'] = '';
				$lang_module['vmodule_add'] = '';
				$lang_module['blocks'] = '';
				$lang_module['autoinstall'] = '';
				$lang_global['mod_modules'] = '';

				$module_name = 'modules';
				require_once NV_ROOTDIR . '/' . NV_ADMINDIR . '/modules/functions.php';
				$module_name = '';

				$array_module_setup = explode( ',', NV_MODULE_SETUP_DEFAULT );
				$modules_exit = nv_scandir( NV_ROOTDIR . '/modules', $global_config['check_module'] );

				$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $keylang . '_modules ORDER BY weight ASC' );
				while( $row = $result->fetch() )
				{
					$setmodule = $row['title'];
					$row['module_file'] = $row['module_file'];

					if( in_array( $row['module_file'], $modules_exit ) and in_array( $setmodule, $array_module_setup ))
					{
						nv_setup_data_module( $keylang, $setmodule );
					}
					else
					{
						$sth = $db->prepare( 'DELETE FROM ' . $db_config['prefix'] . '_' . $keylang . '_modules WHERE title= :module' );
						$sth->bindParam( ':module', $setmodule, PDO::PARAM_STR );
						$sth->execute();
					}
				}

				//cai dat du lieu mau
				$filesavedata = '';
				$lang_data = $keylang;
				if( file_exists( NV_ROOTDIR . '/install/data_' . $keylang . '.php' ) )
				{
					$filesavedata = $keylang;
				}
				elseif( file_exists( NV_ROOTDIR . '/install/data_en.php' ) )
				{
					$filesavedata = 'en';
				}
				if( ! empty( $filesavedata ) )
				{
					try
					{
						include_once NV_ROOTDIR . '/install/data_' . $filesavedata . '.php' ;

						//xoa du lieu tai bang nvx_vi_modules
						$db->query( "DELETE FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules WHERE module_file NOT IN ('" . implode( "', '", $modules_exit ) . "')" );

						//xoa du lieu tai bang nvx_setup_modules
						$db->query( "DELETE FROM " . $db_config['prefix'] . "_setup_modules WHERE module_file NOT IN ('" . implode( "', '", $modules_exit ) . "')" );

						//xoa du lieu tai bang nvx_vi_blocks
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight WHERE bid in (SELECT bid FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups WHERE module NOT IN (SELECT title FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules))' );

						//xoa du lieu tai bang nvx_vi_blocks_groups
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups WHERE module NOT IN (SELECT title FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules)' );

						//xoa du lieu tai bang nvx_vi_modthemes
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes WHERE func_id in (SELECT func_id FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs WHERE in_module NOT IN (SELECT title FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules))' );

						//xoa du lieu tai bang nvx_vi_modfuncs
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs WHERE in_module NOT IN (SELECT title FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules)' );

						//xoa du lieu tai bang nvx_config
						$db->query( "DELETE FROM " . $db_config['prefix'] . "_config WHERE lang= '" . $lang_data . "' AND module!='global' AND module NOT IN (SELECT title FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules)" );

						$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules WHERE title='news'" );
						if( $result->fetchColumn() )
						{
							$result = $db->query( 'SELECT catid FROM ' . $db_config['prefix'] . '_' . $lang_data . '_news_cat ORDER BY sort ASC' );
							while( list( $catid_i ) = $result->fetch( 3 ) )
							{
								nv_copy_structure_table( $db_config['prefix'] . '_' . $lang_data . '_news_' . $catid_i, $db_config['prefix'] . '_' . $lang_data . '_news_rows' );
							}
							$result->closeCursor();

							$result = $db->query( 'SELECT id, listcatid FROM ' . $db_config['prefix'] . '_' . $lang_data . '_news_rows ORDER BY id ASC' );
							while( list( $id, $listcatid ) = $result->fetch( 3 ) )
							{
								$arr_catid = explode( ',', $listcatid );
								foreach( $arr_catid as $catid )
								{
									$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_news_' . $catid . ' SELECT * FROM ' . $db_config['prefix'] . '_' . $lang_data . '_news_rows WHERE id=' . $id );
								}
							}
							$result->closeCursor();
						}
					}
					catch (PDOException $e)
					{
						include NV_ROOTDIR . '/includes/header.php';
						echo nv_admin_theme( 'ERROR SETUP: <br />' . $e->getMessage() );
						include NV_ROOTDIR . '/includes/footer.php';
						exit();
					}
				}
			}

			$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $keylang . '&' . NV_NAME_VARIABLE . '=settings&' . NV_OP_VARIABLE . '=main' );

			$xtpl->parse( 'contents_setup' );
			$contents = $xtpl->text( 'contents_setup' );

			include NV_ROOTDIR . '/includes/header.php';
			echo nv_admin_theme( $contents );
			include NV_ROOTDIR . '/includes/footer.php';
			exit();
		}
		else
		{
			include NV_ROOTDIR . '/includes/header.php';
			echo nv_admin_theme( $lang_module['nv_data_note'] );
			include NV_ROOTDIR . '/includes/footer.php';
			exit();
		}
	}
	elseif( $checksess == md5( $deletekeylang . session_id() . 'deletekeylang' ) and ! in_array( $deletekeylang, $global_config['allow_sitelangs'] ) )
	{
		define( 'NV_IS_FILE_MODULES', true );

		$lang = $deletekeylang;

		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nv_setup_delete'], ' langkey : ' . $deletekeylang, $admin_info['userid'] );

		$sql = 'SELECT title, module_file, module_data FROM ' . $db_config['prefix'] . '_' . $lang . '_modules ORDER BY weight ASC';
		$result_del_module = $db->query( $sql );

		while( list( $title, $module_file, $module_data ) = $result_del_module->fetch( 3 ) )
		{
			if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php' ) )
			{
				$sql_drop_module = array();

				include NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php' ;
				if( ! empty( $sql_drop_module ) )
				{
					foreach( $sql_drop_module as $sql )
					{
						try
						{
							$db->query( $sql );
						}
						catch (PDOException $e)
						{
							trigger_error( $e->getMessage() );
						}
					}
				}
			}
		}

		$db->query( 'ALTER TABLE ' . NV_COUNTER_GLOBALTABLE . ' DROP ' . $deletekeylang . '_count' );

		require_once NV_ROOTDIR . '/includes/action_' . $db->dbtype . '.php';

		$sql_drop_table = nv_delete_table_sys( $deletekeylang );

		foreach( $sql_drop_table as $sql )
		{
			try
			{
				$db->query( $sql );
			}
			catch (PDOException $e)
			{
				trigger_error( $e->getMessage() );
			}
		}

		$db->query( "DELETE FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang = '" . $deletekeylang . "'" );
		$db->query( "DELETE FROM " . $db_config['prefix'] . "_setup_language WHERE lang = '" . $deletekeylang . "'" );

		nv_delete_all_cache();

		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&rand=' . nv_genpass() );
		exit();
	}
}
$a = 0;
foreach( $global_config['allow_adminlangs'] as $keylang )
{
	$delete = '';
	$allow_sitelangs = '';

	$xtpl->assign( 'ROW', array(
		'keylang' => $keylang,
		'name' => $language_array[$keylang]['name']
	) );

	if( defined( 'NV_IS_GODADMIN' ) or ( $global_config['idsite'] > 0 and defined( 'NV_IS_SPADMIN' ) ) )
	{
		if( isset( $array_lang_setup[$keylang] ) and $array_lang_setup[$keylang] == 1 )
		{
			if( ! in_array( $keylang, $global_config['allow_sitelangs'] ) )
			{
				$xtpl->assign( 'DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;deletekeylang=' . $keylang . '&amp;checksess=' . md5( $keylang . session_id() . 'deletekeylang' ) );

				$xtpl->parse( 'main.loop.setup_delete' );
			}
			else
			{
				$xtpl->parse( 'main.loop.setup_note' );
			}

			if( $keylang != $global_config['site_lang'] )
			{
				$selected_yes = $selected_no = ' ';

				if( in_array( $keylang, $global_config['allow_sitelangs'] ) )
				{
					$selected_yes = ' selected="selected"';
				}
				else
				{
					$selected_no = ' selected="selected"';
				}

				$xtpl->assign( 'ALLOW_SITELANGS', array(
					'selected_yes' => $selected_yes,
					'selected_no' => $selected_no,
					'url_yes' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;keylang=' . $keylang . '&amp;activelang=1&amp;checksess=' . md5( 'activelang_' . $keylang . session_id() ),
					'url_no' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;keylang=' . $keylang . '&amp;activelang=0&amp;checksess=' . md5( 'activelang_' . $keylang . session_id() )
				) );

				$xtpl->parse( 'main.loop.allow_sitelangs' );
			}
			else
			{
				$xtpl->parse( 'main.loop.allow_sitelangs_note' );
			}
		}
		else
		{
			$xtpl->assign( 'INSTALL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;keylang=' . $keylang . '&amp;checksess=' . md5( $keylang . session_id() ) );
			$xtpl->parse( 'main.loop.setup_new' );
		}
	}
	$xtpl->parse( 'main.loop' );
}
$contents .= "</table>\n";

$contents .= "<div class=\"quote\" style=\"width:97.5%;\">\n";
$contents .= "<blockquote><span>" . $lang_module['nv_data_note'] . "</span></blockquote>\n";
$contents .= "</div>\n";
$contents .= "<div class=\"clear\"></div>\n";

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';