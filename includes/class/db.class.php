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
	public $server = '';
	public $dbname = '';
	public $user = '';
	public $dbtype = '';

	private $_select = '';
	private $_from = '';
	private $_join = '';
	private $_where = '';
	private $_group = '';
	private $_having = '';
	private $_order = '';
	private $_limit = 0;
	private $_offset = 0;

	function __construct( $config )
	{
		$aray_type = array( 'mysql', 'pgsql', 'mssql', 'sybase', 'dblib' );

		$AvailableDrivers = PDO::getAvailableDrivers();

		$driver_options = array(
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_PERSISTENT => $config['persistent'],
			PDO::ATTR_CASE => PDO::CASE_LOWER,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);

		if( in_array( $config['dbtype'], $AvailableDrivers ) AND in_array( $config['dbtype'], $aray_type ) )
		{
			$dsn = $config['dbtype'] . ':dbname=' . $config['dbname'] . ';host=' . $config['dbhost'] . ';charset=utf8';
			$driver_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
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
	 * Insert a row into the database return primary key column
	 *
	 * @param string $query
	 * @param string $column The name of the primary key column
	 * @param array $data
	 * @return integer|false
	 */
	public function insert_id( $query, $column, $data = array() )
	{
		try
		{
			if( $this->dbtype == 'oci' )
			{
				$query .= ' RETURNING ' . $column . ' INTO :primary_key';
			}

			$stmt = $this->prepare( $query );
			foreach( $data as $key => $value )
			{
				$stmt->bindParam( ':' . $key, $value, PDO::PARAM_STR );
			}
			if( $this->dbtype == 'oci' )
			{
				$stmt->bindParam( ':primary_key', $primary_key, PDO::PARAM_INT, 11 );
			}
			$stmt->execute();

			if( $this->dbtype == 'oci' )
			{
				return $primary_key;
			}
			else
			{
				return $this->lastInsertId();
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
		}
		return false;
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
					return $this->lastInsertId();
				}
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
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

	/**
	 * reset query.
	 *
	 * @return sql_db $this
	 */
	public function sqlreset()
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
	 * @return sql_db $this
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
	 * @return sql_db $this
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
	 * @return sql_db $this
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
	 * @return sql_db $this
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
	 * @return sql_db $this
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
	 * @return sql_db $this
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
	 * @return sql_db $this
	 */
	public function order( $order = '' )
	{
		$this->_order = $order;

		return $this;
	}

	/**
	 * sets the limit for the query.
	 *
	 * @param int $limit
	 * @return sql_db $this
	 */
	public function limit( $limit )
	{
		$this->_limit = (int)$limit;

		return $this;
	}

	/**
	 * sets the offset for the query.
	 *
	 * @param int $offset
	 * @return sql_db $this
	 */
	public function offset( $offset )
	{
		$this->_offset = (int)$offset;

		return $this;
	}

	public function sql()
	{
		$return = 'SELECT ' . $this->_select;
		if( $this->dbtype == 'oci' AND $this->_offset !== false )
		{
			$return .= ', ROWNUM oci_rownum ';
		}
		$return .= ' FROM ' . $this->_from;

		if( $this->_join )
		{
			$return .= ' ' . $this->_join;
		}

		if( $this->_where )
		{
			$return .= ' WHERE ' . $this->_where;
			if( $this->dbtype == 'oci' AND $this->_limit > 0 )
			{
				$return .= ' AND ROWNUM <= ' . ($this->_limit + $this->_offset);
			}
		}
		elseif( $this->dbtype == 'oci' AND $this->_limit > 0 )
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

		if( $this->dbtype == 'mysql' )
		{
			if( $this->_limit )
			{
				$return .= ' LIMIT ' . $this->_limit;
			}
			if( $this->_offset )
			{
				$return .= ' OFFSET ' . $this->_offset;
			}
		}
		elseif( $this->dbtype == 'oci' AND $this->_offset > 0 )
		{
			$return = 'SELECT ' . $this->_select . ' FROM (' . $return . ') WHERE oci_rownum >= ' . ($this->_offset + 1);
		}

		return $return;
	}
}

?>