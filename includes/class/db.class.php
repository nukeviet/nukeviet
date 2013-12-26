<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VuThao (vuthao27@gmail.com)
 * @Copyright (C) 2013 VuThao. All rights reserved
 * @Createdate Thu, 12 Sep 2013 04:07:53 GMT
 */

if ( defined( 'NV_CLASS_SQL_DB_PHP' ) ) return;
define( 'NV_CLASS_SQL_DB_PHP', true );

/**
 * extends for PDO
 */
class sql_db extends pdo
{
	public $connect = 0;
	public $query_strs = array();
	public $server = '';
	public $dbname = '';
	public $user = '';
	public $dbtype = '';

	function __construct( $config )
	{
		$aray_type = array( 'mysql', 'pgsql', 'mssql', 'sybase', 'dblib' );

		$AvailableDrivers = PDO::getAvailableDrivers();
		
		$driver_options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_PERSISTENT => $config['persistent'],
			PDO::ATTR_CASE => PDO::CASE_LOWER,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);

		if( in_array( $config['dbtype'], $AvailableDrivers ) AND in_array( $config['dbtype'], $aray_type ) )
		{
			$dsn = $config['dbtype'] . ':dbname=' . $config['dbname'] . ';host=' . $config['dbhost'] . ';charset=utf8';
		}
		elseif( $config['dbtype'] == 'oci' )
		{
			$dsn = 'oci:dbname=//' . $config['dbhost'] . ':' . $config['dbport'] . '/' . $config['dbname'] . ';charset=AL32UTF8';
			$driver_options[PDO::ATTR_STRINGIFY_FETCHES] = true;
		}
		elseif( $config['dbtype'] == 'sqlite' )
		{
			$dsn = 'sqlite:' . $config['dbname'];
		}
		else
		{
			trigger_error( $config['dbtype'] . ' is not supported', 256 );
		}

		$this->server = $config['dbhost'];
		$this->dbtype = $config['dbtype'];
		$this->dbname = $config['dbname'];
		$this->user = $config['dbuname'];
		try
		{
			parent::__construct( $dsn , $config['dbuname'], $config['dbpass'], $driver_options );
			parent::exec( "SET SESSION time_zone='" . NV_SITE_TIMEZONE_GMT_NAME . "'" );
			$this->connect = 1;
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
		}
	}

	/**
	 * sql_db::sql_query()
	 *
	 * @param string $query
	 * @return
	 */
	public function sql_query( $query = '' )
	{
		$query = preg_replace( '/union/', 'UNI0N', $query );
		try
		{
			$result = parent::query( $query );
			$this->query_strs[] = array( htmlspecialchars( $query ), ( $result ? true : false ) );
			return $result;
		}
		catch( PDOException $e )
		{
			trigger_error( $query . ' --- ' .$e->getMessage() );
			return false;
		}
	}

	public function query( $query )
	{
		try
		{
			$result = parent::query( $query );
			$this->query_strs[] = array( htmlspecialchars( $query ), ( $result ? true : false ) );
			return $result;
		}
		catch( PDOException $e )
		{
			trigger_error( $query . ' --- ' .$e->getMessage() );
			return false;
		}
	}
	
	public function exec( $query )
	{
		try
		{
			$result = parent::exec( $query );
			$this->query_strs[] = array( htmlspecialchars( $query ), ( $result ? true : false ) );
			return $result;
		}
		catch( PDOException $e )
		{
			trigger_error( $query . ' --- ' .$e->getMessage() );
			return false;
		}
	}	

	/**
	 * sql_db::sql_fetchrow()
	 *
	 * @param object $query_id
	 * @param integer $type
	 * @return
	 */
	public function sql_fetchrow( $query_id, $type = 0 )
	{
		try
		{
			switch( $type )
			{
				case 1:
					return $query_id->fetch( PDO::FETCH_NUM );
				case 2:
					return $query_id->fetch( PDO::FETCH_ASSOC );
				default:
					return $query_id->fetch( PDO::FETCH_BOTH );
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			return false;
		}
	}

	/**
	 * sql_db::sql_fetch_assoc()
	 *
	 * @param object $query_id
	 * @return
	 */
	public function sql_fetch_assoc( $query_id )
	{
		try
		{
			return $query_id->fetch( PDO::FETCH_ASSOC );
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			return false;
		}
	}

	/**
	 * sql_db::sql_numrows()
	 *
	 * @param object $query_id
	 * @return
	 */
	public function sql_numrows( $query_id )
	{
		try
		{
			return $query_id->rowCount();
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );			
			return false;
		}
	}

	/**
	 * sql_db::sql_affectedrows()
	 *
	 * @param object $query_id
	 * @return
	 */
	public function sql_affectedrows( $query_id )
	{
		try
		{
			return $query_id->rowCount();
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );			
			return false;
		}
	}

	/**
	 * sql_db::sql_query_insert_id()
	 *
	 * @param string $query
	 * @return
	 */
	public function sql_query_insert_id( $query )
	{
		try
		{
			if( preg_match( "/^INSERT\s/is", $query ) )
			{
				if( parent::exec( $query ) )
				{
					$this->query_strs[] = array( htmlspecialchars( $query ), true );
					return $this->lastInsertId();
				}
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
		}
		$this->query_strs[] = array( htmlspecialchars( $query ), false );
		return false;
	}

	/**
	 * sql_db::sql_freeresult()
	 *
	 * @param object $query_id
	 * @return
	 */
	public function sql_freeresult( $query_id )
	{
		try
		{
			return $query_id->closeCursor();
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			return false;
		}
		return false;
	}

	/**
	 * sql_db::fixdb()
	 *
	 * @param mixed $value
	 * @return
	 */
	public function fixdb( $value )
	{
		$value = str_replace( '\'', '&#039;', $value );
		$value = preg_replace( array( "/(se)(lect)/i", "/(uni)(on)/i", "/(con)(cat)/i", "/(c)(har)/i", "/(out)(file)/i", "/(al)(ter)/i", "/(in)(sert)/i", "/(d)(rop)/i", "/(f)(rom)/i", "/(whe)(re)/i", "/(up)(date)/i", "/(de)(lete)/i", "/(cre)(ate)/i" ), "$1-$2", $value );
		return $value;
	}

	/**
	 * sql_db::unfixdb()
	 *
	 * @param mixed $value
	 * @return
	 */
	function unfixdb( $value )
	{
		$value = preg_replace( array( "/(se)\-(lect)/i", "/(uni)\-(on)/i", "/(con)\-(cat)/i", "/(c)\-(har)/i", "/(out)\-(file)/i", "/(al)\-(ter)/i", "/(in)\-(sert)/i", "/(d)\-(rop)/i", "/(f)\-(rom)/i", "/(whe)\-(re)/i", "/(up)\-(date)/i", "/(de)\-(lete)/i", "/(cre)\-(ate)/i" ), "$1$2", $value );
		return $value;
	}

	/**
	 * sql_db::dbescape()
	 *
	 * @param mixed $value
	 * @return
	 */
	public function dbescape( $value )
	{
		if( is_array( $value ) )
		{
			$value = array_map( array( $this, __function__ ), $value );
		}
		else
		{
			if( ! is_numeric( $value ) || $value{0} == '0' )
			{
				$value = $this->quote( $this->fixdb( $value ) );
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
	public function dbescape_string( $value )
	{
		if( is_array( $value ) )
		{
			$value = array_map( array( $this, __function__ ), $value );
		}
		else
		{
			$value = $this->quote( $this->fixdb( $value ) );
		}

		return $value;
	}

	/**
	 * sql_db::nv_dblikeescape()
	 *
	 * @param mixed $value
	 * @return
	 */
	public function dblikeescape( $value )
	{
		if( is_array( $value ) )
		{
			$value = array_map( array( $this, __function__ ), $value );
		}
		else
		{
			$value = trim( $this->quote( $this->fixdb( $value ) ), "'" );
			$value = addcslashes( $value, '_%' );
		}
		return $value;
	}

}

class sqldriver
{
	private $_select = '';
	private $_from = '';
	private $_join = '';
	private $_where = '';
	private $_group = '';
	private $_having = '';
	private $_order = '';
	private $_limit = 0;
	private $_offset = 0;
	private $_dbtype = '';

	public function __construct( $config )
	{
		//$this->classname = get_class( $this );
		$this->reset( );
		$this->_dbtype = $config['dbtype'];
	}

	/**
	 * reset query.
	 *
	 * @return sqldriver $this
	 */
	public function reset( )
	{
		$this->_select = '';
		$this->_from = '';
		$this->_join = '';
		$this->_where = '';
		$this->_group = '';
		$this->_having = '';
		$this->_order = '';
		$this->_limit = 0;
		$this->_offset = 0;

		return $this;
	}

	/**
	 * select for the query.
	 *
	 * @param string $select
	 * @return sqldriver $this
	 */
	public function select( $select = '' )
	{
		$this->_select = $select;

		return $this;
	}

	/**
	 * from for the query.
	 *
	 * @param string $from
	 * @return sqldriver $this
	 */
	public function from( $from = '' )
	{
		$this->_from = $from;

		return $this;
	}
	
	/**
	 * join for the query.
	 *
	 * @param string join_table_on
	 * @return sqldriver $this
	 */
	public function join( $join_table_on )
	{
		$this->_join = $join_table_on;

		return $this;
	}	

	/**
	 * where for the query.
	 *
	 * @param string $where
	 * @return sqldriver $this
	 */
	public function where( $where = '' )
	{
		$this->_where = $where;

		return $this;
	}

	/**
	 * group for the query.
	 *
	 * @param string $group
	 * @return sqldriver $this
	 */
	public function group( $group = '' )
	{
		$this->_group = $group;

		return $this;
	}

	/**
	 * having for the query.
	 *
	 * @param string $having
	 * @return sqldriver $this
	 */
	public function having( $having = '' )
	{
		$this->_having = $having;

		return $this;
	}

	/**
	 * order for the query.
	 *
	 * @param string $order
	 * @return sqldriver $this
	 */
	public function order( $order = '' )
	{
		$this->_order = $order;

		return $this;
	}

	/**
	 * sets the limit (and offset optionally) for the query.
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return sqldriver $this
	 */
	public function limit( $limit, $offset = false )
	{
		$this->_limit = (int)$limit;
		$this->_offset = (int)$offset;

		return $this;
	}

	public function get( )
	{
		$return = 'SELECT ' . $this->_select;
		if( $this->_dbtype == 'oci' AND $this->_offset !== false )
		{
			$return .= ', ROWNUM oci_rownum, count(*) over () found_rows ';
		}
		$return .= ' FROM ' . $this->_from;
		
		if( $this->_join )
		{
			$return .= ' ' . $this->_join;
		}

		if( $this->_where )
		{
			$return .= ' WHERE ' . $this->_where;
			if( $this->_dbtype == 'oci' AND $this->_limit > 0 )
			{
				$return .= ' AND ROWNUM <= ' . ($this->_limit + $this->_offset);
			}
		}
		elseif( $this->_dbtype == 'oci' AND $this->_limit > 0 )
		{
			$return .= ' WHERE ROWNUM <= ' . ($this->_limit + $this->_offset);
		}
		if( $this->_group )
		{
			$return .= ' GROUP BY ' . $this->_group;
		}
		if( $this->_having )
		{
			$return .= ' HAVING BY ' . $this->_having;
		}
		if( $this->_order )
		{
			$return .= ' ORDER BY ' . $this->_order;
		}

		if( $this->_dbtype == 'mysql' )
		{
			if( $this->_limit )
			{
				$return .= ' LIMIT ' . $this->_offset . ', ' . $this->_limit;
			}
		}
		elseif( $this->_dbtype == 'oci' AND $this->_offset > 0 )
		{
			$return = 'SELECT ' . $this->_select . ' FROM (' . $return . ') WHERE oci_rownum >= ' . ($this->_offset + 1);
		}

		return $return;
	}

}

?>