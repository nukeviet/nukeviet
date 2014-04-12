<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/6/2010, 20:9
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_referer_update()
{
	global $nv_Request, $client_info, $global_config, $db, $prefix;

	if( $client_info['is_myreferer'] == 0 )
	{
		$host = $nv_Request->referer_host;
		$host = str_replace( 'www.', '', $host );
		$host = explode( '/', $host );
		$host = reset( $host );
		$host = strtolower( $host );

		$log_path = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ref_logs';
		if( ! is_dir( $log_path ) )
		{
			@nv_mkdir( NV_ROOTDIR . '/' . NV_LOGS_DIR, 'ref_logs', true );
		}

		$log_current = mktime( 0, 0, 0, date( 'n', NV_CURRENTTIME ), date( 'j', NV_CURRENTTIME ), date( 'Y', NV_CURRENTTIME ) );

		$content = '[' . date( 'r', NV_CURRENTTIME ) . ']';
		$content .= ' [' . NV_CLIENT_IP . ']';
		$content .= ' [' . $client_info['referer'] . ']';
		$content .= ' [' . $client_info['selfurl'] . ']';
		$content .= "\r\n";
		$md5 = md5( $client_info['referer'] . $client_info['selfurl'] );

		$is_save = true;

		$referer_blocker = array();
		if( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/referer_blocker.php' ) )
		{
			include NV_ROOTDIR . '/' . NV_DATADIR . '/referer_blocker.php' ;
		}

		if( ! empty( $referer_blocker ) )
		{
			foreach( $referer_blocker as $blocker )
			{
				if( preg_match( '/' . preg_quote( $blocker ) . '/i', $host ) )
				{
					$is_save = false;
					break;
				}
			}
		}

		if( $is_save )
		{
			$tmp = $log_path . '/tmp.' . NV_LOGS_EXT;
			if( file_exists( $tmp ) )
			{
				$ct = file_get_contents( $tmp );
				if( ! empty( $ct ) )
				{
					$ct = trim( $ct );
					$ct = explode( '|', $ct );
					$p = NV_CURRENTTIME - 60;
					if( $ct[0] > $p and $ct[1] == $md5 )
					{
						$is_save = false;
					}
				}
			}
		}

		if( $is_save )
		{
			file_put_contents( $log_path . '/' . $log_current . '.' . NV_LOGS_EXT, $content, FILE_APPEND );
			file_put_contents( $tmp, NV_CURRENTTIME . '|' . $md5 );

			$sth = $db->prepare( 'UPDATE ' . NV_REFSTAT_TABLE . ' SET
				total=total+1,
				month' . date( 'm', NV_CURRENTTIME ) . '=month' . date( 'm', NV_CURRENTTIME ) . '+1,
				last_update=' . NV_CURRENTTIME . '
				WHERE host= :host' );
			$sth->bindParam( ':host', $host, PDO::PARAM_STR );
			$update = $sth->execute();

			if( empty( $update ) )
			{
				$sth = $db->prepare( 'INSERT INTO ' . NV_REFSTAT_TABLE . '
					(host, total, month' . date( 'm', NV_CURRENTTIME ) . ', last_update)
					VALUES ( :host, 1, 1,' . NV_CURRENTTIME . ')' );
				$sth->bindParam( ':host', $host, PDO::PARAM_STR );
				$sth->execute();
			}

			if( ! empty( $nv_Request->search_engine ) )
			{
				if( isset( $global_config['engine_allowed'][$nv_Request->search_engine]['query_param'] ) and ! empty( $global_config['engine_allowed'][$nv_Request->search_engine]['query_param'] ) )
				{
					$key = $global_config['engine_allowed'][$nv_Request->search_engine]['query_param'];
					$key = $nv_Request->referer_queries[$key];
					$key = str_replace( '+', ' ', $key );
					$key = nv_strtolower( $key );
					$key = nv_substr( $key, 0, 100 );
					$key = trim( $key );
					$id = md5( $key );

					if( ! empty( $key ) )
					{
						$sth = $db->prepare( 'UPDATE ' . NV_SEARCHKEYS_TABLE . ' SET total=total+1 WHERE id= :id AND search_engine= :search_engine' );
						$sth->bindParam( ':id', $id, PDO::PARAM_STR );
						$sth->bindParam( ':search_engine', $nv_Request->search_engine, PDO::PARAM_STR );
						$update = $sth->execute();

						if( empty( $update ) )
						{
							$sth = $db->prepare( 'INSERT INTO ' . NV_SEARCHKEYS_TABLE . ' VALUES ( :id, :key, 1, :search_engine)' );
							$sth->bindParam( ':id', $id, PDO::PARAM_STR );
							$sth->bindParam( ':key', $key, PDO::PARAM_STR );
							$sth->bindParam( ':search_engine', $nv_Request->search_engine, PDO::PARAM_STR );
							$sth->execute();
						}
					}
				}
			}
		}
	}
}

nv_referer_update();