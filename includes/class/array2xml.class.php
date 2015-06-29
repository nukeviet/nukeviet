<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 6/5/2010 2:18
 */

class Array2XML
{
	private $rootname_default = 'root';
	private $itemname_default = 'item';
	private $xml;

	/**
	 * Array2XML::__construct()
	 *
	 * @return
	 */
	function __construct()
	{
	}

	/**
	 * Array2XML::checkArray()
	 *
	 * @param mixed $array
	 * @return
	 */
	private function checkArray( $array )
	{
		$return = ( is_array( $array ) and ! empty( $array ) ) ? true : false;
		return $return;
	}

	/**
	 * Array2XML::setRootName()
	 *
	 * @param mixed $array
	 * @param mixed $rootname
	 * @return
	 */
	private function setRootName( $array, $rootname )
	{
		if( empty( $rootname ) )
		{
			$rootname = $this->rootname_default;
		}

		if( sizeof( $array ) > 1 )
		{
			return $rootname;
		}
		else
		{
			$key = key( $array );
			if( preg_match( "/^[0-9](.*)$/", $key ) )
			{
				return $rootname;
			}
			else
			{
				return $key;
			}
		}
	}

	/**
	 * Array2XML::addArray()
	 *
	 * @param mixed $array
	 * @param mixed $root
	 * @param mixed $lastname
	 * @return
	 */
	private function addArray( $array, &$root, $lastname )
	{
		foreach( $array as $key => $val )
		{
			if( preg_match( "/^[0-9](.*)$/", $key ) )
			{
				$newKey = $lastname . '_' . $this->itemname_default;
			}
			else
			{
				$newKey = $key;
			}

			$node = $this->xml->createElement( $newKey );

			if( is_array( $val ) )
			{
				$this->addArray( $array[$key], $node, $newKey );
			}
			else
			{
				$nodeText = $this->xml->createTextNode( $val );
				$node->appendChild( $nodeText );
			}
			$root->appendChild( $node );
		}
	}

	/**
	 * Array2XML::createXML()
	 *
	 * @param mixed $array
	 * @param mixed $rootname
	 * @param string $encoding
	 * @param bool $is_save
	 * @param string $file
	 * @return
	 */
	private function createXML( $array, $rootname, $encoding = 'utf-8', $is_save = false, $file = '' )
	{
		if( ! $this->checkArray( $array ) )
		{
			return false;
		}

		$rootname = $this->setRootName( $array, $rootname );
		$this->xml = new DOMDocument( "1.0", $encoding );
		$this->xml->formatOutput = true;
		$root = $this->xml->createElement( $rootname );
		$root = $this->xml->appendchild( $root );

		if( sizeof( $array ) > 1 )
		{
			$this->addArray( $array, $root, $rootname );
		}
		else
		{
			$key = key( $array );
			$this->addArray( $array[$key], $root, $rootname );
		}

		if( $is_save )
		{
			if( $this->xml->save( $file ) == 0 )
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return $this->xml->saveXML();
		}
	}

	/**
	 * Array2XML::saveXML()
	 *
	 * @param mixed $array
	 * @param mixed $rootname
	 * @param mixed $file
	 * @param string $encoding
	 * @return
	 */
	public function saveXML( $array, $rootname, $file, $encoding = '' )
	{
		return $this->createXML( $array, $rootname, $encoding, true, $file );
	}

	/**
	 * Array2XML::showXML()
	 *
	 * @param mixed $array
	 * @param mixed $rootname
	 * @param string $encoding
	 * @return
	 */
	public function showXML( $array, $rootname, $encoding = '' )
	{
		$content = $this->createXML( $array, $rootname, $encoding );

		if( $content == false )
		{
			return $content;
		}

		@Header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( '-1 day' ) ) . " GMT" );
		@Header( 'Content-Type: text/xml; charset=' . $encoding );
		if( ! empty( $_SERVER['SERVER_SOFTWARE'] ) and strstr( $_SERVER['SERVER_SOFTWARE'], 'Apache/2' ) )
		{
			@Header( 'Cache-Control: no-cache, pre-check=0, post-check=0' );
		}
		else
		{
			@Header( 'Cache-Control: private, pre-check=0, post-check=0, max-age=0' );
		}
		@Header( 'Expires: 0' );
		@Header( 'Pragma: no-cache' );
		Header( 'Content-Encoding: none' );
		echo ( $content );
		die();
	}
}