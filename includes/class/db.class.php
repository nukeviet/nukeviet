<?php


/**
 * @Project NUKEVIET 3.x
 * @Author VuThao (vuthao27@gmail.com)
 * @Copyright (C) 2013 VuThao. All rights reserved
 * @Createdate Thu, 12 Sep 2013 04:07:53 GMT
 */

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

		if( in_array( $config['dbtype'], $AvailableDrivers ) AND in_array( $config['dbtype'], $aray_type ) )
		{
			$dsn = $config['dbtype'] . ':dbname=' . $config['dbname'] . ';host=' . $config['dbhost'];
		}
		elseif( $config['dbtype'] == 'oci' )
		{
			$dsn = 'oci:dbname=//' . $config['dbhost'] . ':' . $config['dbport'] . '/' . $config['dbname'];
		}
		elseif( $config['dbtype'] == 'sqlite' )
		{
			$dsn = 'sqlite:' . $config['dbname'];
		}
		else
		{
			trigger_error( $config['dbtype'] . ' is not supported', 256 );
		}

		$driver_options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_CASE => PDO::CASE_LOWER,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);

		$this->server = $config['dbhost'];
		$this->dbtype = $config['dbtype'];
		$this->dbname = $config['dbname'];
		$this->user = $config['dbuname'];
		try
		{
			parent::__construct( $dsn . ';charset=utf8', $config['dbuname'], $config['dbpass'], $driver_options );
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
			$result = $this->query( $query );
			$this->query_strs[] = array( htmlspecialchars( $query ), ( $result ? true : false ) );
			return $result;
		}
		catch( PDOException $e )
		{
			return false;
		}
	}

	/**
	 * sql_db::sql_version()
	 *
	 * @return
	 */
	public function sql_version()
	{
		try
		{
			if ( $this->dbtype = 'mysql' )
			{
				$rs = $this->query( 'SELECT VERSION()' );
				return $rs->fetchColumn( );
			}
			elseif ( $this->dbtype = 'oci' )
			{
				$rs = $this->query( 'select version from product_component_version' );
				return $rs->fetchColumn( );
			}
		}
		catch( PDOException $e )
		{
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
				if( $this->exec( $query ) )
				{
					return $this->lastInsertId();
				}
			}
		}
		catch( PDOException $e )
		{
			return false;
		}
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

?>