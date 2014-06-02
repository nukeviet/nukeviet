<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/28/2009 14:30
 */

if( defined( 'NV_CLASS_IMAGE_PHP' ) ) return;
define( 'NV_CLASS_IMAGE_PHP', true );

if( ! defined( 'ERROR_IMAGE1' ) ) define( 'ERROR_IMAGE1', 'The file is not a known image format' );
if( ! defined( 'ERROR_IMAGE2' ) ) define( 'ERROR_IMAGE2', 'The file is not readable' );
if( ! defined( 'ERROR_IMAGE3' ) ) define( 'ERROR_IMAGE3', 'File is not supplied or is not a file' );
if( ! defined( 'ERROR_IMAGE4' ) ) define( 'ERROR_IMAGE4', 'Image type not supported' );
if( ! defined( 'ERROR_IMAGE5' ) ) define( 'ERROR_IMAGE5', 'Image mime type not supported' );
if( ! defined( 'ERROR_IMAGE6' ) ) define( 'ERROR_IMAGE6', 'Error loading Image' );

if( ! defined( 'NV_ROOTDIR' ) ) define( 'NV_ROOTDIR', preg_replace( "/[\/]+$/", '', str_replace( '\\', '/', realpath( dirname( __file__ ) . '/../../' ) ) ) );

class image
{
	var $filename;
	var $is_url = false;
	var $fileinfo = array();
	var $gmaxX = 0;
	var $gmaxY = 0;
	var $error = '';
	var $createImage = false;
	var $create_Image_info = array();
	var $logoimg;
	var $is_destroy = false;
	var $is_createWorkingImage = false;

	/**
	 * image::image()
	 *
	 * @param mixed $filename
	 * @param integer $gmaxX
	 * @param integer $gmaxY
	 * @return
	 */
	function image( $filename, $gmaxX = 0, $gmaxY = 0 )
	{
		if( preg_match( "/(http|https|ftp):\/\//i", $filename ) )
		{
			$this->is_url = true;
			$this->filename = $this->set_tempnam( $filename );
		}
		else
		{
			$this->filename = $filename;
		}
		$this->gmaxX = intval( $gmaxX );
		$this->gmaxY = intval( $gmaxY );
		$this->error = '';
		$this->createImage = false;
		$this->create_Image_info = array();
		$this->fileinfo = $this->is_image( $this->filename );
		$this->error = $this->check_file();
		if( empty( $this->error ) ) $this->get_createImage();
	}

	/**
	 * image::is_image()
	 *
	 * @param mixed $img
	 * @return
	 */
	function is_image( $img )
	{
		$typeflag = array();
		$typeflag[1] = array( 'type' => IMAGETYPE_GIF, 'ext' => 'gif' );
		$typeflag[2] = array( 'type' => IMAGETYPE_JPEG, 'ext' => 'jpg' );
		$typeflag[3] = array( 'type' => IMAGETYPE_PNG, 'ext' => 'png' );
		$typeflag[4] = array( 'type' => IMAGETYPE_SWF, 'ext' => 'swf' );
		$typeflag[5] = array( 'type' => IMAGETYPE_PSD, 'ext' => 'psd' );
		$typeflag[6] = array( 'type' => IMAGETYPE_BMP, 'ext' => 'bmp' );
		$typeflag[7] = array( 'type' => IMAGETYPE_TIFF_II, 'ext' => 'tiff' );
		$typeflag[8] = array( 'type' => IMAGETYPE_TIFF_MM, 'ext' => 'tiff' );
		$typeflag[9] = array( 'type' => IMAGETYPE_JPC, 'ext' => 'jpc' );
		$typeflag[10] = array( 'type' => IMAGETYPE_JP2, 'ext' => 'jp2' );
		$typeflag[11] = array( 'type' => IMAGETYPE_JPX, 'ext' => 'jpf' );
		$typeflag[12] = array( 'type' => IMAGETYPE_JB2, 'ext' => 'jb2' );
		$typeflag[13] = array( 'type' => IMAGETYPE_SWC, 'ext' => 'swc' );
		$typeflag[14] = array( 'type' => IMAGETYPE_IFF, 'ext' => 'aiff' );
		$typeflag[15] = array( 'type' => IMAGETYPE_WBMP, 'ext' => 'wbmp' );
		$typeflag[16] = array( 'type' => IMAGETYPE_XBM, 'ext' => 'xbm' );

		$imageinfo = array();
		$file = @getimagesize( $img );
		if( $file )
		{
			$imageinfo['src'] = $img;
			$imageinfo['width'] = $file[0];
			$imageinfo['height'] = $file[1];
			$imageinfo['mime'] = $file['mime'];
			$imageinfo['type'] = $typeflag[$file[2]]['type'];
			$imageinfo['ext'] = $typeflag[$file[2]]['ext'];
			$imageinfo['bits'] = $file['bits'];
			$imageinfo['channels'] = isset( $file['channels'] ) ? intval( $file['channels'] ) : 0;
		}

		return $imageinfo;
	}

	/**
	 * image::set_memory_limit()
	 *
	 * @return
	 */
	function set_memory_limit()
	{
		$mb = Pow( 1024, 2 );
		$k64 = Pow( 2, 16 );
		$tweakfactor = 1.8;
		$memoryNeeded = round( ( $this->fileinfo['width'] * $this->fileinfo['height'] * $this->fileinfo['bits'] * $this->fileinfo['channels'] / 8 + $k64 ) * $tweakfactor );

		$disable_functions = ( ini_get( "disable_functions" ) != '' and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
		if( extension_loaded( 'suhosin' ) )
		{
			$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
		}

		$memoryHave = ( ( function_exists( 'memory_get_usage' ) and ! in_array( 'memory_get_usage', $disable_functions ) ) ) ? @memory_get_usage() : 0;

		$memoryLimitMB = ( integer )ini_get( 'memory_limit' );
		$memoryLimit = $memoryLimitMB * $mb;
		if( $memoryHave + $memoryNeeded > $memoryLimit )
		{
			$newLimit = $memoryLimitMB + ceil( ( $memoryHave + $memoryNeeded - $memoryLimit ) / $mb );
			if( ( function_exists( 'memory_limit' ) and ! in_array( 'memory_limit', $disable_functions ) ) )
			{
				ini_set( 'memory_limit', $newLimit . 'M' );
			}
		}
	}

	/**
	 * image::get_createImage()
	 *
	 * @return
	 */
	function get_createImage()
	{
		switch( $this->fileinfo['type'] )
		{
			case IMAGETYPE_GIF:
				$this->createImage = ImageCreateFromGif( $this->filename );
				break;
			case IMAGETYPE_JPEG:
				$this->createImage = ImageCreateFromJpeg( $this->filename );
				break;
			case IMAGETYPE_PNG:
				$this->createImage = ImageCreateFromPng( $this->filename );
				break;
		}

		if( ! $this->createImage )
		{
			$this->error = ERROR_IMAGE6;
		}
		else
		{
			$this->create_Image_info = $this->fileinfo;
			$this->is_destroy = false;
		}
	}

	/**
	 * image::set_tempnam()
	 *
	 * @param mixed $filename
	 * @return
	 */
	function set_tempnam( $filename )
	{
		$tmpfname = tempnam( NV_ROOTDIR . "/tmp", "tmp" );
		$input = fopen( $filename, "rb" );
		$output = fopen( $tmpfname, "wb" );
		while( $data = fread( $input, 1024 ) )
		{
			fwrite( $output, $data );
		}
		fclose( $output );
		fclose( $input );
		return $tmpfname;
	}

	/**
	 * image::check_file()
	 *
	 * @return
	 */
	function check_file()
	{
		if( $this->fileinfo == array() ) return ERROR_IMAGE1;
		if( ! is_readable( $this->filename ) ) return ERROR_IMAGE2;
		if( $this->fileinfo['src'] == '' || $this->fileinfo['width'] == 0 || $this->fileinfo['height'] == 0 || $this->fileinfo['mime'] == '' ) return ERROR_IMAGE3;
		if( ! in_array( $this->fileinfo['type'], array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ) ) ) return ERROR_IMAGE4;
		if( ! preg_match( "#image\/[x\-]*(jpg|jpeg|pjpeg|gif|png)#is", $this->fileinfo['mime'] ) ) return ERROR_IMAGE5;
		return '';
	}

	/**
	 * image::resizeXY()
	 *
	 * @param integer $maxX
	 * @param integer $maxY
	 * @return
	 */
	function resizeXY( $maxX = 0, $maxY = 0 )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}
			$maxX = intval( $maxX );
			$maxY = intval( $maxY );
			if( $maxX > $this->gmaxX and $this->gmaxX != 0 ) $maxX = $this->gmaxX;
			if( $maxY > $this->gmaxY and $this->gmaxY != 0 ) $maxY = $this->gmaxY;
			if( $maxX < 0 ) $maxX = 0;
			if( $maxY < 0 ) $maxY = 0;
			if( ( $maxX != 0 || $maxY != 0 ) and ( $maxX != $this->create_Image_info['width'] || $maxY != $this->create_Image_info['height'] ) )
			{
				if( $maxX >= $maxY )
				{
					$newwidth = $maxX;
					$newheight = ceil( $maxX * $this->create_Image_info['height'] / $this->create_Image_info['width'] );

					if( $maxY != 0 and $newheight > $maxY )
					{
						$newwidth = ceil( $newwidth / $newheight * $maxY );
						$newheight = $maxY;
					}
				}
				else
				{
					$newwidth = ceil( $this->create_Image_info['width'] / $this->create_Image_info['height'] * $maxY );
					$newheight = $maxY;

					if( $maxX != 0 and $newwidth > $maxX )
					{
						$newheight = ceil( $maxX * $newheight / $newwidth );
						$newwidth = $maxX;
					}
				}
				$workingImage = function_exists( "ImageCreateTrueColor" ) ? ImageCreateTrueColor( $newwidth, $newheight ) : ImageCreate( $newwidth, $newheight );
				if( $workingImage != false )
				{
					$this->is_createWorkingImage = true;
					$this->set_memory_limit();

					$transparent_index = imagecolortransparent( $this->createImage );
					if( $transparent_index >= 0 )
					{
						$t_c = imagecolorsforindex( $this->createImage, $transparent_index );
						$transparent_index = imagecolorallocate( $workingImage, $t_c['red'], $t_c['green'], $t_c['blue'] );
						if( false !== $transparent_index and imagefill( $workingImage, 0, 0, $transparent_index ) )
						{
							imagecolortransparent( $workingImage, $transparent_index );
						}
					}

					if( $this->fileinfo['type'] == IMAGETYPE_PNG )
					{
						if( imagealphablending( $workingImage, false ) )
						{
							$transparency = imagecolorallocatealpha( $workingImage, 0, 0, 0, 127 );
							if( false !== $transparency and imagefill( $workingImage, 0, 0, $transparency ) )
							{
								imagesavealpha( $workingImage, true );
							}
						}
					}

					if( ImageCopyResampled( $workingImage, $this->createImage, 0, 0, 0, 0, $newwidth, $newheight, $this->create_Image_info['width'], $this->create_Image_info['height'] ) )
					{
						$this->createImage = $workingImage;
						$this->create_Image_info['width'] = $newwidth;
						$this->create_Image_info['height'] = $newheight;
					}
				}
			}
		}
	}

	/**
	 * image::resizePercent()
	 *
	 * @param integer $percent
	 * @return
	 */
	function resizePercent( $percent = 0 )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}
			$percent = intval( $percent );
			if( $percent <= 0 ) $percent = 100;
			$X = ceil( ( $this->create_Image_info['width'] * $percent ) / 100 );
			$Y = ceil( ( $this->create_Image_info['height'] * $percent ) / 100 );
			if( $X > $this->gmaxX and $this->gmaxX != 0 ) $X = $this->gmaxX;
			if( $Y > $this->gmaxY and $this->gmaxY != 0 ) $Y = $this->gmaxY;
			if( $X != $this->create_Image_info['width'] || $Y != $this->create_Image_info['height'] )
			{
				if( $X >= $Y )
				{
					$newwidth = $X;
					$newheight = ceil( $X * $this->create_Image_info['height'] / $this->create_Image_info['width'] );

					if( $Y != 0 and $newheight > $Y )
					{
						$newwidth = ceil( $newwidth / $newheight * $Y );
						$newheight = $Y;
					}
				}
				else
				{
					$newwidth = ceil( $this->create_Image_info['width'] / $this->create_Image_info['height'] * $Y );
					$newheight = $Y;

					if( $X != 0 and $newwidth > $X )
					{
						$newheight = ceil( $X * $newheight / $newwidth );
						$newwidth = $X;
					}
				}
				$workingImage = function_exists( "ImageCreateTrueColor" ) ? ImageCreateTrueColor( $newwidth, $newheight ) : ImageCreate( $newwidth, $newheight );
				if( $workingImage != false )
				{
					$this->is_createWorkingImage = true;
					$this->set_memory_limit();

					$transparent_index = imagecolortransparent( $this->createImage );
					if( $transparent_index >= 0 )
					{
						$t_c = imagecolorsforindex( $this->createImage, $transparent_index );
						$transparent_index = imagecolorallocate( $workingImage, $t_c['red'], $t_c['green'], $t_c['blue'] );
						if( false !== $transparent_index and imagefill( $workingImage, 0, 0, $transparent_index ) )
						{
							imagecolortransparent( $workingImage, $transparent_index );
						}
					}

					if( $this->fileinfo['type'] == IMAGETYPE_PNG )
					{
						if( imagealphablending( $workingImage, false ) )
						{
							$transparency = imagecolorallocatealpha( $workingImage, 0, 0, 0, 127 );
							if( false !== $transparency and imagefill( $workingImage, 0, 0, $transparency ) )
							{
								imagesavealpha( $workingImage, true );
							}
						}
					}

					if( ImageCopyResampled( $workingImage, $this->createImage, 0, 0, 0, 0, $newwidth, $newheight, $this->create_Image_info['width'], $this->create_Image_info['height'] ) )
					{
						$this->createImage = $workingImage;
						$this->create_Image_info['width'] = $newwidth;
						$this->create_Image_info['height'] = $newheight;
					}
				}
			}
		}
	}

	/**
	 * image::cropFromLeft()
	 *
	 * @param mixed $leftX
	 * @param mixed $leftY
	 * @param mixed $newwidth
	 * @param mixed $newheight
	 * @return
	 */
	function cropFromLeft( $leftX, $leftY, $newwidth, $newheight )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}

			$leftX = intval( $leftX );
			$leftY = intval( $leftY );
			$newwidth = intval( $newwidth );
			$newheight = intval( $newheight );
			if( $leftX < 0 || $leftX >= $this->create_Image_info['width'] ) $leftX = 0;
			if( $leftY < 0 || $leftY >= $this->create_Image_info['height'] ) $leftY = 0;
			if( $newwidth <= 0 || ( $newwidth + $leftX > $this->create_Image_info['width'] ) ) $newwidth = $this->create_Image_info['width'] - $leftX;
			if( $newheight <= 0 || ( $newheight + $leftY > $this->create_Image_info['height'] ) ) $newheight = $this->create_Image_info['height'] - $leftY;
			if( $newwidth != $this->create_Image_info['width'] || $newheight != $this->create_Image_info['height'] )
			{
				$workingImage = function_exists( "ImageCreateTrueColor" ) ? ImageCreateTrueColor( $newwidth, $newheight ) : ImageCreate( $newwidth, $newheight );
				if( $workingImage != false )
				{
					$this->is_createWorkingImage = true;
					$this->set_memory_limit();

					$transparent_index = imagecolortransparent( $this->createImage );
					if( $transparent_index >= 0 )
					{
						$t_c = imagecolorsforindex( $this->createImage, $transparent_index );
						$transparent_index = imagecolorallocate( $workingImage, $t_c['red'], $t_c['green'], $t_c['blue'] );
						if( false !== $transparent_index and imagefill( $workingImage, 0, 0, $transparent_index ) )
						{
							imagecolortransparent( $workingImage, $transparent_index );
						}
					}

					if( $this->fileinfo['type'] == IMAGETYPE_PNG )
					{
						if( imagealphablending( $workingImage, false ) )
						{
							$transparency = imagecolorallocatealpha( $workingImage, 0, 0, 0, 127 );
							if( false !== $transparency and imagefill( $workingImage, 0, 0, $transparency ) )
							{
								imagesavealpha( $workingImage, true );
							}
						}
					}

					if( ImageCopyResampled( $workingImage, $this->createImage, 0, 0, $leftX, $leftY, $newwidth, $newheight, $newwidth, $newheight ) )
					{
						$this->createImage = $workingImage;
						$this->create_Image_info['width'] = $newwidth;
						$this->create_Image_info['height'] = $newheight;
					}
				}
			}
		}
	}

	/**
	 * image::cropFromCenter()
	 *
	 * @param mixed $newwidth
	 * @param mixed $newheight
	 * @return
	 */
	function cropFromCenter( $newwidth, $newheight )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}

			$newwidth = intval( $newwidth );
			$newheight = intval( $newheight );
			if( $newwidth <= 0 || $newwidth > $this->create_Image_info['width'] ) $newwidth = $this->create_Image_info['width'];
			if( $newheight <= 0 || $newheight > $this->create_Image_info['height'] ) $newheight = $this->create_Image_info['height'];
			if( $newwidth < $this->create_Image_info['width'] || $newheight < $this->create_Image_info['height'] )
			{
				$leftX = ( $this->create_Image_info['width'] - $newwidth ) / 2;
				$leftY = ( $this->create_Image_info['height'] - $newheight ) / 2;
				$workingImage = function_exists( "ImageCreateTrueColor" ) ? ImageCreateTrueColor( $newwidth, $newheight ) : ImageCreate( $newwidth, $newheight );
				if( $workingImage != false )
				{
					$this->is_createWorkingImage = true;
					$this->set_memory_limit();

					$transparent_index = imagecolortransparent( $this->createImage );
					if( $transparent_index >= 0 )
					{
						$t_c = imagecolorsforindex( $this->createImage, $transparent_index );
						$transparent_index = imagecolorallocate( $workingImage, $t_c['red'], $t_c['green'], $t_c['blue'] );
						if( false !== $transparent_index and imagefill( $workingImage, 0, 0, $transparent_index ) )
						{
							imagecolortransparent( $workingImage, $transparent_index );
						}
					}

					if( $this->fileinfo['type'] == IMAGETYPE_PNG )
					{
						if( imagealphablending( $workingImage, false ) )
						{
							$transparency = imagecolorallocatealpha( $workingImage, 0, 0, 0, 127 );
							if( false !== $transparency and imagefill( $workingImage, 0, 0, $transparency ) )
							{
								imagesavealpha( $workingImage, true );
							}
						}
					}

					if( ImageCopyResampled( $workingImage, $this->createImage, 0, 0, $leftX, $leftY, $newwidth, $newheight, $newwidth, $newheight ) )
					{
						$this->createImage = $workingImage;
						$this->create_Image_info['width'] = $newwidth;
						$this->create_Image_info['height'] = $newheight;
					}
				}
			}
		}
	}

	/**
	 * image::addstring()
	 *
	 * @param mixed $string
	 * @param string $align
	 * @param string $valign
	 * @param string $font
	 * @param integer $fsize
	 * @return
	 */
	function addstring( $string, $align = 'right', $valign = 'bottom', $font = '', $fsize = 2 )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}

			if( $string != '' )
			{
				$this->set_memory_limit();

				if( $font == '' ) $font = NV_ROOTDIR . '/includes/fonts/Pixelation.ttf';
				$bbox = imagettfbbox( $fsize, 0, $font, $string );
				$string_width = $bbox[2] - $bbox[0];
				$string_height = $bbox[1] - $bbox[7];
				if( $string_width != 0 and $string_height != 0 and $string_width + 20 <= $this->create_Image_info['width'] and $string_height + 20 < $this->create_Image_info['height'] )
				{
					switch( $align )
					{
						case 'left':
							$X = 10;
							break;
						case 'center':
							$X = ( $this->create_Image_info['width'] - $string_width ) / 2;
							break;
						default:
							$X = $this->create_Image_info['width'] - ( $string_width + 10 );
					}
					switch( $valign )
					{
						case 'top':
							$Y = 10;
							break;
						case 'middle':
							$Y = ( $this->create_Image_info['height'] - $string_height ) / 2;
							break;
						default:
							$Y = $this->create_Image_info['height'] - ( $string_height + 10 );
					}

					$grey = imagecolorallocate( $this->createImage, 128, 128, 128 );
					imagealphablending( $this->createImage, true );
					imagettftext( $this->createImage, $fsize, 0, $X, $Y, $grey, $font, $string );
				}
			}
		}
	}

	/**
	 * image::addlogo()
	 *
	 * @param mixed $logo
	 * @param string $align
	 * @param string $valign
	 * @return
	 */
	function addlogo( $logo, $align = 'right', $valign = 'bottom', $config_logo = array() )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}

			$logo_info = $this->is_image( $logo );
			if( $logo_info != array() and $logo_info['width'] != 0 and $logo_info['height'] != 0 and in_array( $logo_info['type'], array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ) ) and preg_match( "#image\/[x\-]*(jpg|jpeg|pjpeg|gif|png)#is", $logo_info['mime'] ) )
			{
				$this->set_memory_limit();

				if( isset( $config_logo['w'] ) and isset( $config_logo['h'] ) )
				{
					$dst_w = $config_logo['w'];
					$dst_h = $config_logo['h'];
				}
				else
				{
					$dst_w = $logo_info['width'];
					$dst_h = $logo_info['height'];
				}

				if( isset( $config_logo['x'] ) and isset( $config_logo['y'] ) )
				{
					$X = $config_logo['x'];
					$Y = $config_logo['y'];
				}
				else
				{
					switch( $align )
					{
						case 'left':
							$X = 10;
							break;
						case 'center':
							$X = ceil( ( $this->create_Image_info['width'] - $logo_info['width'] ) / 2 );
							break;
						default:
							$X = $this->create_Image_info['width'] - ( $logo_info['width'] + 10 );
					}
					switch( $valign )
					{
						case 'top':
							$Y = 10;
							break;
						case 'middle':
							$Y = ceil( ( $this->create_Image_info['height'] - $logo_info['height'] ) / 2 );
							break;
						default:
							$Y = $this->create_Image_info['height'] - ( $logo_info['height'] + 10 );
					}
				}

				if( $X + $dst_w <= $this->create_Image_info['width']  and $Y + $dst_h <= $this->create_Image_info['height'] )
				{
					if( $this->fileinfo['type'] == IMAGETYPE_PNG and ! $this->is_createWorkingImage )
					{
						if( imagealphablending( $this->createImage, false ) )
						{
							$transparency = imagecolorallocatealpha( $this->createImage, 0, 0, 0, 127 );
							if( false !== $transparency and imagefill( $this->createImage, 0, 0, $transparency ) )
							{
								imagesavealpha( $this->createImage, true );
							}
						}
					}

					imagealphablending( $this->createImage, true );

					switch( $logo_info['type'] )
					{
						case IMAGETYPE_GIF:
							$this->logoimg = ImageCreateFromGif( $logo );
							break;
						case IMAGETYPE_JPEG:
							$this->logoimg = ImageCreateFromJpeg( $logo );
							break;
						case IMAGETYPE_PNG:
							$this->logoimg = ImageCreateFromPng( $logo );
							break;
					}

					ImageCopyResampled( $this->createImage, $this->logoimg, $X, $Y, 0, 0, $dst_w, $dst_h, $logo_info['width'], $logo_info['height'] );
				}
			}
		}
	}

	/**
	 * image::rotate()
	 *
	 * @param mixed $direction
	 * @return
	 */
	function rotate( $direction )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}

			$direction = intval( $direction );
			$direction = 360 - $direction % 360;
			if( $direction != 0 and $direction != 360 )
			{
				$this->set_memory_limit();
				$workingImage = imagerotate( $this->createImage, $direction, -1 );
				imagealphablending( $workingImage, true );
				imagesavealpha( $workingImage, true );
				$this->createImage = $workingImage;
				$this->create_Image_info['width'] = imagesX( $this->createImage );
				$this->create_Image_info['height'] = imagesY( $this->createImage );
			}
		}
	}

	/**
	 * image::reflection()
	 *
	 * @return
	 */
	function reflection()
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}
			$this->set_memory_limit();

			$newheight = $this->create_Image_info['height'] + ( $this->create_Image_info['height'] / 2 );
			$newwidth = $this->create_Image_info['width'];
			$workingImage = function_exists( "ImageCreateTrueColor" ) ? ImageCreateTrueColor( $newwidth, $newheight ) : ImageCreate( $newwidth, $newheight );
			imagealphablending( $workingImage, false );
			imagesavealpha( $workingImage, true );
			imagecopy( $workingImage, $this->createImage, 0, 0, 0, 0, $this->create_Image_info['width'], $this->create_Image_info['height'] );
			$reflection_height = $this->create_Image_info['height'] / 2;
			$alpha_step = 80 / $reflection_height;
			for( $y = 1; $y <= $reflection_height; ++$y )
			{
				for( $x = 0; $x < $newwidth; ++$x )
				{
					$rgba = imagecolorat( $this->createImage, $x, $this->create_Image_info['height'] - $y );
					$alpha = ( $rgba & 0x7F000000 ) >> 24;
					$alpha = max( $alpha, 47 + ( $y * $alpha_step ) );
					$rgba = imagecolorsforindex( $this->createImage, $rgba );
					$rgba = imagecolorallocatealpha( $workingImage, $rgba['red'], $rgba['green'], $rgba['blue'], $alpha );
					imagesetpixel( $workingImage, $x, $this->create_Image_info['height'] + $y - 1, $rgba );
				}
			}
			$this->createImage = $workingImage;
			$this->create_Image_info['height'] = $newheight;
		}
	}

	/**
	 * image::show()
	 *
	 * @param integer $quality
	 * @return
	 */
	function show( $quality = 100 )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}

			header( "Content-type: " . $this->create_Image_info['mime'] );
			switch( $this->create_Image_info['type'] )
			{
				case IMAGETYPE_GIF:
					ImageGif( $this->createImage );
					break;

				case IMAGETYPE_JPEG:
					ImageJpeg( $this->createImage, null, $quality );
					break;

				case IMAGETYPE_PNG:
					$quality = round( ( $quality / 100 ) * 10 );
					if( $quality < 1 ) $quality = 1;
					elseif( $quality > 10 ) $quality = 10;
					$quality = 10 - $quality;

					ImagePng( $this->createImage, $quality );
					break;
			}
			$this->close();
		}
	}

	/**
	 * image::save()
	 *
	 * @param mixed $path
	 * @param string $newname
	 * @param integer $quality
	 * @return
	 */
	function save( $path, $newname = '', $quality = 100 )
	{
		if( empty( $this->error ) )
		{
			if( $this->is_destroy )
			{
				$this->get_createImage();
			}

			if( is_dir( $path ) and is_writeable( $path ) )
			{
				if( empty( $newname ) )
				{
					$newname = $this->create_Image_info['width'] . '_' . $this->create_Image_info['height'];
					if( defined( 'PATHINFO_FILENAME' ) )
					{
						$basename = pathinfo( $this->create_Image_info['src'], PATHINFO_FILENAME );
					}
					else
					{
						$basename = strstr( $this->create_Image_info['src'], '.' ) ? substr( $this->create_Image_info['src'], 0, strrpos( $this->create_Image_info['src'], '.' ) ) : "";
					}

					if( ! empty( $basename ) ) $newname .= '_' . $basename;
				}
				$newname = preg_replace( '/^\W+|\W+$/', '', $newname );
				$newname = preg_replace( '/[ ]+/', '_', $newname );
				$newname = strtolower( preg_replace( '/\W-/', '', $newname ) );

				$_array_name = explode( '.', $newname );
				$newname = preg_replace( "/." . array_pop( $_array_name ) . "$/", '', $newname );

				if( ! preg_match( "/\/$/", $path ) ) $path = $path . "/";
				$newname = $path . $newname . '.' . $this->create_Image_info['ext'];

				switch( $this->create_Image_info['type'] )
				{
					case IMAGETYPE_GIF:
						ImageGif( $this->createImage, $newname );
						break;

					case IMAGETYPE_JPEG:
						ImageJpeg( $this->createImage, $newname, $quality );
						break;

					case IMAGETYPE_PNG:
						ImagePng( $this->createImage, $newname );
				}

				$this->create_Image_info['src'] = $newname;
			}

			$this->Destroy();
		}
	}

	/**
	 * image::Destroy()
	 *
	 * @return
	 */
	function Destroy()
	{
		if( is_resource( $this->logoimg ) ) @ImageDestroy( $this->logoimg );
		if( is_resource( $this->createImage ) ) @ImageDestroy( $this->createImage );
		$this->is_destroy = true;
	}

	/**
	 * image::close()
	 *
	 * @return
	 */
	function close()
	{
		if( is_resource( $this->logoimg ) ) @ImageDestroy( $this->logoimg );
		if( is_resource( $this->createImage ) ) @ImageDestroy( $this->createImage );
		if( $this->is_url )
		{
			@unlink( $this->filename );
			$this->is_url = false;
		}
		$this->is_destroy = true;
	}
}