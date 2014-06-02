<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme' . md5( $global_config['sitekey'] . session_id() ) . '.zip';

$xtpl = new XTemplate( 'install_check.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

if( file_exists( $filename ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
	$zip = new PclZip( $filename );
	$ziplistContent = $zip->listContent();
	$overwrite = $nv_Request->get_string( 'overwrite', 'get', '' );

	$errorfile = array();

	if( $overwrite != md5( $filename . $global_config['sitekey'] . session_id() ) )
	{
		foreach( $ziplistContent as $array_file )
		{
			//check exist file on system
			if( empty( $array_file['folder'] ) and file_exists( NV_ROOTDIR . '/themes/' . trim( $array_file['filename'] ) ) )
			{
				$errorfile[] = $array_file['filename'];
			}
		}
	}

	if( ! empty( $errorfile ) )
	{
		// File da ton tai tren he thong
		$xtpl->assign( 'OVERWRITE', md5( $filename . $global_config['sitekey'] . session_id() ) );

		foreach( $errorfile as $i => $file )
		{
			$xtpl->assign( 'FILE', $file );
			$xtpl->parse( 'exists.loop' );
		}

		$xtpl->parse( 'exists' );
		$contents = $xtpl->text( 'exists' );

		include NV_ROOTDIR . '/includes/header.php';
		echo $contents;
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}
	else
	{
		$temp_extract_dir = NV_TEMP_DIR . '/' . md5( $filename . $global_config['sitekey'] . session_id() );

		if( NV_ROOTDIR . '/' . $temp_extract_dir )
		{
			nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
		}

		$ftp_check_login = 0;
		if( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) == 1 )
		{
			$ftp_server = nv_unhtmlspecialchars( $global_config['ftp_server'] );
			$ftp_port = intval( $global_config['ftp_port'] );
			$ftp_user_name = nv_unhtmlspecialchars( $global_config['ftp_user_name'] );
			$ftp_user_pass = nv_unhtmlspecialchars( $global_config['ftp_user_pass'] );
			$ftp_path = nv_unhtmlspecialchars( $global_config['ftp_path'] );
			// set up basic connection
			$conn_id = ftp_connect( $ftp_server, $ftp_port, 10 );
			// login with username and password
			$login_result = ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass );
			if( ( ! $conn_id ) || ( ! $login_result ) )
			{
				$ftp_check_login = 3;
			}
			elseif( ftp_chdir( $conn_id, $ftp_path ) )
			{
				$ftp_check_login = 1;
			}
			else
			{
				$ftp_check_login = 2;
			}
		}

		if( $ftp_check_login == 1 )
		{
			ftp_mkdir( $conn_id, $temp_extract_dir );

			if( substr( $sys_info['os'], 0, 3 ) != 'WIN' ) ftp_chmod( $conn_id, 0777, $temp_extract_dir );

			foreach( $ziplistContent as $array_file )
			{
				if( ! empty( $array_file['folder'] ) and ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'] ) )
				{
					$cp = '';
					$e = explode( '/', $array_file['filename'] );
					foreach( $e as $p )
					{
						if( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $cp . $p ) )
						{
							ftp_mkdir( $conn_id, $temp_extract_dir . '/' . $cp . $p );
							if( substr( $sys_info['os'], 0, 3 ) != 'WIN' ) ftp_chmod( $conn_id, 0777, $temp_extract_dir . '/' . $cp . $p );
						}
						$cp .= $p . '/';
					}
				}
			}
		}

		$no_extract = array();
		$extract = $zip->extract( PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir );

		foreach( $extract as $extract_i )
		{
			$filename_i = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $extract_i['filename'] ) );

			if( $extract_i['status'] != 'ok' and $extract_i['status'] != 'already_a_directory' )
			{
				$no_extract[] = $filename_i;
			}
		}

		if( empty( $no_extract ) )
		{
			$error_create_folder = array();

			foreach( $ziplistContent as $array_file )
			{
				$dir_name = '';

				if( ! empty( $array_file['folder'] ) and ! file_exists( NV_ROOTDIR . '/' . $array_file['filename'] ) )
				{
					$dir_name = $array_file['filename'];
				}
				elseif( ! file_exists( NV_ROOTDIR . '/' . dirname( $array_file['filename'] ) ) )
				{
					$dir_name = dirname( $array_file['filename'] );
				}

				if( ! empty( $dir_name ) )
				{
					$cp = '';
					$e = explode( '/', $dir_name );

					foreach( $e as $p )
					{
						if( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/themes/' . $cp . $p ) )
						{
							if( ! ( $ftp_check_login == 1 and ftp_mkdir( $conn_id, 'themes/' . $cp . $p ) ) )
							{
								@mkdir( NV_ROOTDIR . '/themes/' . $cp . $p );
							}

							if( ! is_dir( NV_ROOTDIR . '/themes/' . $cp . $p ) )
							{
								$error_create_folder[] = 'themes/' . $cp . $p;
							}
						}

						$cp .= $p . '/';
					}
				}
			}

			$error_create_folder = array_unique( $error_create_folder );

			if( ! empty( $error_create_folder ) )
			{
				// Xuat cac thu muc khong the tao

				asort( $error_create_folder );

				foreach( $error_create_folder as $i => $folder )
				{
					$xtpl->assign( 'FOLDER', $folder );
					$xtpl->parse( 'error_create_folder.loop' );
				}

				$xtpl->parse( 'error_create_folder' );
				$contents = $xtpl->text( 'error_create_folder' );

				include NV_ROOTDIR . '/includes/header.php';
				echo $contents;
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}
			else
			{
				$error_move_folder = array();

				foreach( $ziplistContent as $array_file )
				{
					if( empty( $array_file['folder'] ) )
					{
						if( file_exists( NV_ROOTDIR . '/themes/' . $array_file['filename'] ) )
						{
							if( ! ( $ftp_check_login == 1 and ftp_delete( $conn_id, 'themes/' . $array_file['filename'] ) ) )
							{
								nv_deletefile( NV_ROOTDIR . '/themes/' . $array_file['filename'] );
							}
						}

						if( ! ( $ftp_check_login == 1 and ftp_rename( $conn_id, $temp_extract_dir . '/' . $array_file['filename'], 'themes/' . $array_file['filename'] ) ) )
						{
							@rename( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'], NV_ROOTDIR . '/themes/' . $array_file['filename'] );
						}

						if( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'] ) )
						{
							$error_move_folder[] = $array_file['filename'];
						}
					}
				}

				if( empty( $error_move_folder ) )
				{
					// Giai nen hoan tat

					nv_deletefile( $filename );
					nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );

					$selectthemes = '';
					foreach( $ziplistContent as $file_i )
					{
						if( preg_match( '/^(?!admin\_)([a-zA-Z0-9\-\_]+)\/config.ini$/', $file_i['filename'], $m ) )
						{
							$selectthemes = $m[1];
							break;
						}
					}

					if( ! empty($selectthemes) and file_exists( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' ) )
					{
						$sth = $db->prepare('SELECT count(*) FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id = 0 AND theme= :theme');
						$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
						$sth->execute();
						$count = $sth->fetchColumn();
						if( empty( $count ) )
						{
							// Thiết lập Layout
							$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' );
							$layoutdefault = ( string )$xml->layoutdefault;
							$layout = $xml->xpath( 'setlayout/layout' );

							for( $i = 0, $count = sizeof( $layout ); $i < $count; ++$i )
							{
								$layout_name = ( string )$layout[$i]->name;

								if( in_array( $layout_name, $layout_array ) )
								{
									$layout_funcs = $layout[$i]->xpath( 'funcs' );

									for( $j = 0, $sizeof = sizeof( $layout_funcs ); $j < $sizeof; ++$j )
									{
										$mo_funcs = ( string )$layout_funcs[$j];
										$mo_funcs = explode( ':', $mo_funcs );
										$m = $mo_funcs[0];
										$arr_f = explode( ',', $mo_funcs[1] );

										foreach( $arr_f as $f )
										{
											$array_layout_func_default[$m][$f] = $layout_name;
										}
									}
								}
							}

							$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_modthemes (func_id, layout, theme) VALUES (:func_id, :layout, :theme)' );
							$sth->bindValue( ':func_id', 0, PDO::PARAM_INT );
							$sth->bindParam( ':layout', $layoutdefault, PDO::PARAM_STR );
							$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
							$sth->execute();

							$fnresult = $db->query( 'SELECT func_id, func_name, func_custom_name, in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func=1 ORDER BY subweight ASC' );
							while( list( $func_id, $func_name, $func_custom_name, $in_module ) = $fnresult->fetch( 3 ) )
							{
								$layout_name = ( isset( $array_layout_func_default[$in_module][$func_name] ) ) ? $array_layout_func_default[$in_module][$func_name] : $layoutdefault;
								$sth->bindParam( ':func_id', $func_id, PDO::PARAM_INT );
								$sth->bindParam( ':layout', $layout_name, PDO::PARAM_STR );
								$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
								$sth->execute();
							}

							// Thiết lập Block
							$array_all_funcid = array();
							$func_result = $db->query( 'SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC' );
							while( list( $func_id_i ) = $func_result->fetch( 3 ) )
							{
								$array_all_funcid[] = $func_id_i;
							}

							$blocks = $xml->xpath( 'setblocks/block' );
							for( $i = 0, $count = sizeof( $blocks ); $i < $count; ++$i )
							{
								$row = (array)$blocks[$i];
								$file_name = $row['file_name'];

								if( $row['module'] == 'theme' and preg_match( $global_config['check_block_theme'], $file_name, $matches ) )
								{
									if( ! file_exists( NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name ) )
									{
										continue;
									}
								}
								elseif( isset( $site_mods[$row['module']] ) and preg_match( $global_config['check_block_module'], $file_name, $matches ) )
								{
									$mod_file = $site_mods[$module]['module_file'];
									if( file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name ) )
									{
										continue;
									}
								}
								else
								{
									continue;
								}

								$sth = $db->prepare( 'SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position' );
								$sth->bindParam( ':theme', $selectthemes, PDO::PARAM_STR );
								$sth->bindParam( ':position', $row['position'], PDO::PARAM_STR );
								$sth->execute();
								$row['weight'] = intval( $sth->fetchColumn() ) + 1;

								$row['exp_time'] = 0;
								$row['active'] = 1;
								$row['groups_view'] = '6';

								$all_func = ($row['all_func'] == 1 and preg_match( '/^global\.([a-zA-Z0-9\-\_\.]+)\.php$/', $file_name )) ? 1 : 0;

								$_sql = "INSERT INTO " . NV_BLOCKS_TABLE . "_groups (theme, module, file_name, title, link, template, position, exp_time, active, groups_view, all_func, weight, config) VALUES
								( :selectthemes, :module, :file_name, :title, :link, :template, :position, '" . $row['exp_time'] . "', '" . $row['active'] . "', :groups_view, '" . $all_func . "', '" . $row['weight'] . "', :config )";
								$data = array();
								$data['selectthemes'] = $selectthemes;
								$data['module'] = $row['module'];
								$data['file_name'] = $file_name;
								$data['title'] = $row['title'];
								$data['link'] = $row['link'];
								$data['template'] = (string)$row['template'];
								$data['position'] = $row['position'];
								$data['groups_view'] = $row['groups_view'];
								$data['config'] = (string)$row['config'];
								$row['bid'] = $db->insert_id( $_sql, 'bid', $data );
								if( $all_func )
								{
									$array_funcid = $array_all_funcid;
								}
								else
								{
									$array_funcid = array();
									if( ! is_array( $row['funcs'] ) )
									{
										$row['funcs'] = array( $row['funcs'] );
									}
									foreach( $row['funcs'] as $_funcs_list )
									{
										list( $mod, $func_list ) = explode( ':', $_funcs_list );
										if( isset( $site_mods[$mod] ) )
										{
											$func_array = explode( ',', $func_list );
											foreach( $site_mods[$mod]['funcs'] as $_tmp )
											{
												if( in_array( $_tmp['func_name'], $func_array ) )
												{
													$array_funcid[] = $_tmp['func_id'];
												}
											}
										}
									}
								}

								$sth = $db->prepare( 'SELECT MAX(t1.weight) FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t1.func_id= :func_id AND t2.theme= ' . $db->quote( $selectthemes ) . ' AND t2.position= :position' );
								foreach( $array_funcid as $func_id )
								{
									$sth->bindParam( ':func_id', $func_id, PDO::PARAM_INT );
									$sth->bindParam( ':position', $row['position'], PDO::PARAM_STR );
									$sth->execute();
									$weight = $sth->fetchColumn();
									$weight = intval( $weight ) + 1;

									$db->query( 'INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')' );
								}

							}
						}

						$nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks&selectthemes=' . $selectthemes;
						$xtpl->assign( 'NV_REDIRECT_LANG', $lang_module['autoinstall_theme_success_setupblocks'] );
					}
					else
					{
						$nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setuplayout&selectthemes=' . $selectthemes;
						$xtpl->assign( 'NV_REDIRECT_LANG', $lang_module['autoinstall_theme_success_setuplayout'] );
					}

					$xtpl->assign( 'NV_REDIRECT', $nv_redirect );

					$xtpl->parse( 'complete' );
					$contents = $xtpl->text( 'complete' );

					include NV_ROOTDIR . '/includes/header.php';
					echo $contents;
					include NV_ROOTDIR . '/includes/footer.php';
					exit();
				}
				else
				{
					// Xuat cac thu muc khong the di chuyen file qua
					asort( $error_move_folder );

					foreach( $error_move_folder as $i => $folder )
					{
						$xtpl->assign( 'FOLDER', $folder );
						$xtpl->parse( 'error_move_folder.loop' );
					}

					$xtpl->parse( 'error_move_folder' );
					$contents = $xtpl->text( 'error_move_folder' );

					include NV_ROOTDIR . '/includes/header.php';
					echo $contents;
					include NV_ROOTDIR . '/includes/footer.php';
					exit();
				}
			}
		}
		else
		{
			// Khong the giai nen
			$xtpl->parse( 'nounzip' );
			$contents = $xtpl->text( 'nounzip' );

			include NV_ROOTDIR . '/includes/header.php';
			echo $contents;
			include NV_ROOTDIR . '/includes/footer.php';
			exit();
		}
	}
}