<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/28/2010 19:3
 */

if ( defined( 'NV_CLASS_SQL_DB_PHP' ) ) return;
define( 'NV_CLASS_SQL_DB_PHP', true );

if ( ! defined( 'NV_SITE_TIMEZONE_NAME' ) ) define( 'NV_SITE_TIMEZONE_NAME', '+00:00' );

/**
 * sql_db
 * 
 * @package   
 * @author NUKEVIET 3.0
 * @copyright VINADES
 * @version 2010
 * @access public
 */
class sql_db
{

    const NOT_CONNECT_TO_MYSQL_SERVER = 'Sorry! Could not connect to mysql server';

    const DATABASE_NAME_IS_EMPTY = 'Error! The database name is not the connection name';

    const UNKNOWN_DATABASE = 'Error! Unknown database';

    public $server = 'localhost';

    public $user = 'root';

    public $dbname = '';

    public $sql_version;

    public $db_charset;

    public $db_set_collation;

    public $db_collation;

    public $db_time_zone;

    public $error = array();

    public $time = 0;

    public $query_strs = array();

    private $persistency = false;

    private $new_link = false;

    private $db_connect_id = false;

    private $create_db = false;

    private $query_result = false;

    private $row = array();

    private $rowset = array();

    /**
     * sql_db::__construct()
     * 
     * @param mixed $db_config
     * @return
     */
    public function __construct ( $db_config = array() )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        
        if ( isset( $db_config['dbhost'] ) and ! empty( $db_config['dbhost'] ) ) $this->server = $db_config['dbhost'];
        if ( isset( $db_config['dbport'] ) and ! empty( $db_config['dbport'] ) ) $this->server .= ':' . $db_config['dbport'];
        if ( isset( $db_config['dbname'] ) ) $this->dbname = $db_config['dbname'];
        if ( isset( $db_config['dbuname'] ) ) $this->user = $db_config['dbuname'];
        if ( isset( $db_config['new_link'] ) ) $this->new_link = ( bool )$db_config['new_link'];
        if ( isset( $db_config['create_db'] ) ) $this->create_db = ( bool )$db_config['create_db'];
        if ( isset( $db_config['persistency'] ) ) $this->persistency = ( bool )$db_config['persistency'];
		$this->db_set_collation = isset( $db_config['collation'] ) ? $db_config['collation'] : 'utf8_general_ci';
		
        $this->sql_connect( $db_config['dbpass'] );
        
        if ( $this->db_connect_id ) $this->sql_setdb();
        
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
    }

    /**
     * sql_db::sql_connect()
     * 
     * @return
     */
    private function sql_connect ( $dbpass )
    {
        $function = ( $this->persistency ) ? 'mysql_pconnect' : 'mysql_connect';
        
        $this->db_connect_id = @call_user_func( $function, $this->server, $this->user, $dbpass, $this->new_link );
        unset( $dbpass );
        if ( ! $this->db_connect_id )
        {
            $this->error = $this->sql_error( sql_db::NOT_CONNECT_TO_MYSQL_SERVER );
        }
        else
        {
            if ( empty( $this->dbname ) )
            {
                $this->error = $this->sql_error( sql_db::DATABASE_NAME_IS_EMPTY );
                $this->db_connect_id = false;
            }
            else
            {
                $dbselect = @mysql_select_db( $this->dbname, $this->db_connect_id );
                if ( ! $dbselect )
                {
                    if ( $this->create_db )
                    {
                        @mysql_query( "CREATE DATABASE " . $this->dbname . "", $this->db_connect_id );
                        $dbselect = @mysql_select_db( $this->dbname, $this->db_connect_id );
                    }
                    if ( ! $dbselect )
                    {
                        $this->error = $this->sql_error( sql_db::UNKNOWN_DATABASE );
                        @mysql_close( $this->db_connect_id );
                        $this->db_connect_id = false;
                    }
                }
            }
        }
    }

    /**
     * sql_db::sql_setdb()
     * 
     * @return
     */
    private function sql_setdb ( )
    {
        if ( $this->db_connect_id )
        {
            preg_match( "/^(\d+)\.(\d+)\.(\d+)/", mysql_get_server_info(), $m );
            $this->sql_version = ( $m[1] . '.' . $m[2] . '.' . $m[3] );
            if ( version_compare( $this->sql_version, '4.1.0', '>=' ) )
            {
                @mysql_query( "SET SESSION `time_zone`='" . NV_SITE_TIMEZONE_GMT_NAME . "'", $this->db_connect_id );
                
                $result = @mysql_query( 'SELECT @@session.time_zone AS `time_zone`, @@session.character_set_database AS `character_set_database`, 
                @@session.collation_database AS `collation_database`, @@session.sql_mode AS `sql_mode`', $this->db_connect_id );
                $row = @mysql_fetch_assoc( $result );
                @mysql_free_result( $result );
                
                $this->db_time_zone = $row['time_zone'];
                $this->db_charset = $row['character_set_database'];
                $this->db_collation = $row['collation_database'];
                
                if ( strcasecmp( $this->db_charset, "utf8" ) != 0 or strcasecmp( $this->db_collation, $this->db_set_collation ) != 0 )
                {
                    @mysql_query( "ALTER DATABASE `" . $this->dbname . "` DEFAULT CHARACTER SET `utf8` COLLATE `".$this->db_set_collation."`", $this->db_connect_id );
                    $result = @mysql_query( 'SELECT @@session.character_set_database AS `character_set_database`, 
                    @@session.collation_database AS `collation_database`', $this->db_connect_id );
                    $row = @mysql_fetch_assoc( $result );
                    @mysql_free_result( $result );
                    
                    $this->db_charset = $row['character_set_database'];
                    $this->db_collation = $row['collation_database'];
                }
				
                @mysql_query( "SET NAMES 'utf8'", $this->db_connect_id );
                
                if ( version_compare( $this->sql_version, '5.0.2', '>=' ) )
                {
                    $modes = ( ! empty( $row['sql_mode'] ) ) ? array_map( 'trim', explode( ',', $row['sql_mode'] ) ) : array();
                    if ( ! in_array( 'TRADITIONAL', $modes ) )
                    {
                        if ( ! in_array( 'STRICT_ALL_TABLES', $modes ) ) $modes[] = 'STRICT_ALL_TABLES';
                        if ( ! in_array( 'STRICT_TRANS_TABLES', $modes ) ) $modes[] = 'STRICT_TRANS_TABLES';
                    }
                    $mode = implode( ',', $modes );
                    @mysql_query( "SET SESSION `sql_mode`='" . $mode . "'", $this->db_connect_id );
                }
            }
        }
    }

    /**
     * sql_db::sql_close()
     * 
     * @return
     */
    public function sql_close ( )
    {
        if ( $this->db_connect_id )
        {
            if ( is_resource( $this->query_result ) ) @mysql_free_result( $this->query_result );
            if ( ! $this->persistency )
            {
                $result = @mysql_close( $this->db_connect_id );
                if ( ! $result ) $this->error = $this->sql_error();
                $this->db_connect_id = null;
                $this->row = array();
                $this->rowset = array();
                return $result;
            }
        }
        return false;
    }
	
    /**
     * sql_db::sql_transaction()
     * 
     * @param string $status
     * @return
     */	
	function sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				return @mysql_query('BEGIN', $this->db_connect_id);
			break;

			case 'commit':
				return @mysql_query('COMMIT', $this->db_connect_id);
			break;

			case 'rollback':
				return @mysql_query('ROLLBACK', $this->db_connect_id);
			break;
		}
		return true;
	}	

    /**
     * sql_db::sql_query()
     * 
     * @param string $query
     * @return
     */
    public function sql_query ( $query = "" )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        $this->query_result = false;
        if ( ! empty( $query ) )
        {
            $query = preg_replace( '/union/', 'UNI0N', $query );
            $this->query_result = @mysql_query( $query, $this->db_connect_id );
            $this->query_strs[] = array( 
                htmlspecialchars( $query ), ( $this->query_result ? true : false ) 
            );
        }
        if ( $this->query_result )
        {
            unset( $this->row[$this->query_result] );
            unset( $this->rowset[$this->query_result] );
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $this->query_result;
        }
        else
        {
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return false;
        }
    }

    /**
     * sql_db::sql_query_insert_id()
     * 
     * @param string $query
     * @return
     */
    public function sql_query_insert_id ( $query = "" )
    {
        if ( empty( $query ) or ! preg_match( "/^INSERT\s/is", $query ) )
        {
            return false;
        }
        if ( ! $this->sql_query( $query ) )
        {
            return false;
        }
        $result = @mysql_insert_id( $this->db_connect_id );
        return $result;
    }

    /**
     * sql_db::sql_numrows()
     * 
     * @param integer $query_id
     * @return
     */
    public function sql_numrows ( $query_id = 0 )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            $result = @mysql_num_rows( $query_id );
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $result;
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_affectedrows()
     * 
     * @return
     */
    public function sql_affectedrows ( )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( $this->db_connect_id )
        {
            $result = @mysql_affected_rows( $this->db_connect_id );
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $result;
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_numfields()
     * 
     * @param integer $query_id
     * @return
     */
    public function sql_numfields ( $query_id = 0 )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            $result = @mysql_num_fields( $query_id );
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $result;
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_fieldname()
     * 
     * @param mixed $offset
     * @param integer $query_id
     * @return
     */
    public function sql_fieldname ( $offset, $query_id = 0 )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            $result = @mysql_field_name( $query_id, $offset );
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $result;
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_fieldtype()
     * 
     * @param mixed $offset
     * @param integer $query_id
     * @return
     */
    public function sql_fieldtype ( $offset, $query_id = 0 )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            $result = @mysql_field_type( $query_id, $offset );
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $result;
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_fetchrow()
     * 
     * @param integer $query_id
     * @param integer $type
     * @return
     */
    public function sql_fetchrow ( $query_id = 0, $type = 0 )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            if ( $type != 1 and $type != 2 ) $type = 0;
            switch ( $type )
            {
                case 1:
                    $this->row['' . $query_id . ''] = @mysql_fetch_array( $query_id, MYSQL_NUM );
                    break;
                
                case 2:
                    $this->row['' . $query_id . ''] = @mysql_fetch_array( $query_id, MYSQL_ASSOC );
                    break;
                
                default:
                    $this->row['' . $query_id . ''] = @mysql_fetch_array( $query_id, MYSQL_BOTH );
            }
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $this->row['' . $query_id . ''];
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_fetchrowset()
     * 
     * @param integer $query_id
     * @return
     */
    public function sql_fetchrowset ( $query_id = 0 )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            unset( $this->rowset['' . $query_id . ''] );
            unset( $this->row['' . $query_id . ''] );
            while ( $this->rowset['' . $query_id . ''] = @mysql_fetch_array( $query_id ) )
            {
                $result[] = $this->rowset['' . $query_id . ''];
            }
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $result;
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_fetchfield()
     * 
     * @param mixed $field
     * @param integer $rownum
     * @param integer $query_id
     * @return
     */
    public function sql_fetchfield ( $field, $rownum = -1, $query_id = 0 )
    {
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        $result = false;
        if ( ! empty( $query_id ) )
        {
            if ( $rownum > - 1 )
            {
                $result = @mysql_result( $query_id, $rownum, $field );
            }
            else
            {
                if ( empty( $this->row['' . $query_id . ''] ) && empty( $this->rowset['' . $query_id . ''] ) )
                {
                    if ( $this->sql_fetchrow() ) $result = $this->row['' . $query_id . ''][$field];
                }
                else
                {
                    if ( $this->rowset['' . $query_id . ''] )
                    {
                        $result = $this->rowset['' . $query_id . ''][$field];
                    }
                    elseif ( $this->row['' . $query_id . ''] )
                    {
                        $result = $this->row['' . $query_id . ''][$field];
                    }
                }
            }
        }
        return $result;
    }

    /**
     * sql_db::sql_rowseek()
     * 
     * @param mixed $rownum
     * @param integer $query_id
     * @return
     */
    public function sql_rowseek ( $rownum, $query_id = 0 )
    {
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            $result = @mysql_data_seek( $query_id, $rownum );
            return $result;
        }
        return false;
    }

    /**
     * sql_db::sql_fetch_assoc()
     * 
     * @param integer $query_id
     * @return
     */
    public function sql_fetch_assoc ( $query_id = 0 )
    {
        $stime = array_sum( explode( " ", microtime() ) );
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( ! empty( $query_id ) )
        {
            $this->row['' . $query_id . ''] = @mysql_fetch_assoc( $query_id );
            $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
            return $this->row['' . $query_id . ''];
        }
        $this->time += ( array_sum( explode( " ", microtime() ) ) - $stime );
        return false;
    }

    /**
     * sql_db::sql_freeresult()
     * 
     * @param integer $query_id
     * @return
     */
    public function sql_freeresult ( $query_id = 0 )
    {
        if ( empty( $query_id ) ) $query_id = $this->query_result;
        
        if ( is_resource( $query_id ) )
        {
            unset( $this->row['' . $query_id . ''] );
            unset( $this->rowset['' . $query_id . ''] );
            @mysql_free_result( $query_id );
            return true;
        }
        return false;
    }

    /**
     * sql_db::sql_error()
     * 
     * @param string $message
     * @return
     */
    public function sql_error ( $message = '' )
    {
        if ( ! $this->db_connect_id )
        {
            return array( 
                'message' => @mysql_error(), 'user_message' => $message, 'code' => @mysql_errno() 
            );
        }
        return array( 
            'message' => @mysql_error( $this->db_connect_id ), 'user_message' => $message, 'code' => @mysql_errno( $this->db_connect_id ) 
        );
    }

    /**
     * sql_db::fixdb()
     * 
     * @param mixed $value
     * @return
     */
    public function fixdb ( $value )
    {
        $value = str_replace( '\'', '&#039;', $value );
        $value = preg_replace( array( 
            "/(se)(lect)/i", "/(uni)(on)/i", "/(con)(cat)/i", "/(c)(har)/i", "/(out)(file)/i", "/(al)(ter)/i", "/(in)(sert)/i", "/(d)(rop)/i", "/(f)(rom)/i", "/(whe)(re)/i", "/(up)(date)/i", "/(de)(lete)/i", "/(cre)(ate)/i" 
        ), "$1-$2", $value );
        return $value;
    }

    /**
     * sql_db::unfixdb()
     * 
     * @param mixed $value
     * @return
     */
    function unfixdb ( $value )
    {
        $value = preg_replace( array( 
            "/(se)\-(lect)/i", "/(uni)\-(on)/i", "/(con)\-(cat)/i", "/(c)\-(har)/i", "/(out)\-(file)/i", "/(al)\-(ter)/i", "/(in)\-(sert)/i", "/(d)\-(rop)/i", "/(f)\-(rom)/i", "/(whe)\-(re)/i", "/(up)\-(date)/i", "/(de)\-(lete)/i", "/(cre)\-(ate)/i" 
        ), "$1$2", $value );
        return $value;
    }

    /**
     * sql_db::dbescape()
     * 
     * @param mixed $value
     * @return
     */
    public function dbescape ( $value )
    {
        if ( is_array( $value ) )
        {
            $value = array_map( array( 
                $this, __function__ 
            ), $value );
        }
        else
        {
            if ( ! is_numeric( $value ) || $value{0} == '0' )
            {
                $value = "'" . mysql_real_escape_string( $this->fixdb( $value ) ) . "'";
            }
        }
        
        return $value;
    }

    /**
     * sql_db::dbescape_string()
     * 
     * @param mixed $value
     * @return
     */
    public function dbescape_string ( $value )
    {
        if ( is_array( $value ) )
        {
            $value = array_map( array( 
                $this, __function__ 
            ), $value );
        }
        else
        {
            $value = "'" . mysql_real_escape_string( $this->fixdb( $value ) ) . "'";
        }
        
        return $value;
    }

    /**
     * sql_db::nv_dblikeescape()
     * 
     * @param mixed $value
     * @return
     */
    public function dblikeescape ( $value )
    {
        if ( is_array( $value ) )
        {
            $value = array_map( array( 
                $this, __function__ 
            ), $value );
        }
        else
        {
            $value = mysql_real_escape_string( $this->fixdb( $value ) );
            $value = addcslashes( $value, '_%' );
        }
        
        return $value;
    }

    /**
     * sql_db::constructQuery()
     * 
     * @return
     */
    public function constructQuery ( )
    {
        $numargs = func_num_args();
        if ( empty( $numargs ) ) return false;
        
        $pattern = func_get_arg( 0 );
        if ( empty( $pattern ) ) return false;
        unset( $matches );
        $pattern = preg_replace( "/[\n\r\t]/", " ", $pattern );
        $pattern = preg_replace( "/[ ]+/", " ", $pattern );
        $pattern = preg_replace( array( 
            "/([\S]+)\[/", "/\]([\S]+)/", "/\[[\s]+/", "/[\s]+\]/", "/[\s]*\,[\s]*/" 
        ), array( 
            "\\1 [", "] \\1", "[", "]", ", " 
        ), $pattern );
        
        preg_match_all( "/[\s]*[\"|\']*[\s]*\[([s|d])([a]*)\][\s]*[\"|\']*[\s]*/", $pattern, $matches );
        
        $replacement = func_get_args();
        unset( $replacement[0] );
        
        $count1 = sizeof( $matches[0] );
        $count2 = sizeof( $replacement );
        
        if ( ! empty( $count1 ) )
        {
            if ( $count2 < $count1 ) return false;
            $replacement = array_values( $replacement );
            $pattern = str_replace( "%", "[:25:]", $pattern );
            $pattern = preg_replace( "/[\s]*[\"|\']*[\s]*\[([s|d])([a]*)\][\s]*[\"|\']*[\s]*/", " %s ", $pattern );
            
            $repls = array();
            foreach ( $matches[1] as $key => $datatype )
            {
                $repls[$key] = $replacement[$key];
                if ( $datatype == 's' )
                {
                    if ( isset( $matches[2][$key] ) and $matches[2][$key] == 'a' )
                    {
                        $repls[$key] = ( array )$repls[$key];
                        if ( ! empty( $repls[$key] ) )
                        {
                            $repls[$key] = array_map( array( 
                                $this, 'fixdb' 
                            ), $repls[$key] );
                            $repls[$key] = array_map( 'mysql_real_escape_string', $repls[$key] );
                            $repls[$key] = "'" . implode( "','", $repls[$key] ) . "'";
                        }
                        else
                        {
                            $repls[$key] = "''";
                        }
                    }
                    else
                    {
                        $repls[$key] = "'" . ( ! empty( $repls[$key] ) ? mysql_real_escape_string( $this->fixdb( $repls[$key] ) ) : "" ) . "'";
                    }
                }
                else
                {
                    if ( isset( $matches[2][$key] ) and $matches[2][$key] == 'a' )
                    {
                        $repls[$key] = ( array )$repls[$key];
                        $repls[$key] = ( ! empty( $repls[$key] ) ) ? "'" . implode( "','", array_map( 'intval', $repls[$key] ) ) . "'" : "'0'";
                    }
                    else
                    {
                        $repls[$key] = "'" . intval( $repls[$key] ) . "'";
                    }
                }
            }
            eval( "\$query = sprintf(\$pattern,\"" . implode( "\",\"", $repls ) . "\");" );
            $query = str_replace( "[:25:]", "%", $query );
        }
        else
        {
            $query = $pattern;
        }
        
        return $query;
    }
}

?>