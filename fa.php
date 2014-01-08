<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 28/10/2012, 14:51
 */

// Xác định root site
define( 'NV_ROOTDIR', pathinfo( str_replace( DIRECTORY_SEPARATOR, '/', __file__ ), PATHINFO_DIRNAME ) );

class Token
{
	public $type;
	public $contents;

	public function __construct( $rawToken )
	{
		if( is_array( $rawToken ) )
		{
			$this->type = $rawToken[0];
			$this->contents = $rawToken[1];
		}
		else
		{
			$this->type = - 1;
			$this->contents = $rawToken;
		}
	}

}

function nv_fomat_dir( $dirname, $all = false )
{
	$dh = opendir( NV_ROOTDIR . '/' . $dirname );
	if( $dh )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( '/^([a-zA-Z0-9\-\_\/\.]+)\.php$/', $file ) )
			{
				if( ! nv_fomat_file_php( NV_ROOTDIR . '/' . $dirname . '/' . $file ) )
				{
					echo $dirname . '/' . $file . ' ---------------------- no change ----------------------<br>';
				}
				else
				{
					echo $dirname . '/' . $file . '<br>';
				}
			}
			elseif( preg_match( "/^([a-zA-Z0-9\-\_\/\.]+)\.js$/", $file ) )
			{
				if( ! nv_fomat_file_js( NV_ROOTDIR . '/' . $dirname . '/' . $file ) )
				{
					echo $dirname . '/' . $file . ' ---------------------- no change ----------------------<br>';
				}
				else
				{
					echo $file . '<br>';
				}
			}
			elseif( preg_match( "/^([a-zA-Z0-9\-\_\/\.]+)\.tpl$/", $file ) )
			{
				if( ! nv_fomat_file_tpl( NV_ROOTDIR . '/' . $dirname . '/' . $file ) )
				{
					echo $dirname . '/' . $file . ' ---------------------- no change ----------------------<br>';
				}
				else
				{
					echo $dirname . '/' . $file . '<br>';
				}
			}
			elseif( $all and preg_match( "/^([a-zA-Z0-9\-\_\/]+)$/", $file ) and is_dir( NV_ROOTDIR . '/' . $dirname . '/' . $file ) )
			{
				nv_fomat_dir( $dirname . '/' . $file, $all );
			}
		}
	}
}

/**
 * @param strimg $filename
 * @return number
 */
function nv_fomat_file_php( $filename )
{
	// ap dung cho Aptana Studio 3
	$array_file_not_fomat = array();
	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/class/idna_convert.class.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/class/openid.class.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/class/pclzip.class.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/class/SimpleCaptcha.class.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/class/xtemplate.class.php';

	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/phpmailer/class.phpmailer.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/phpmailer/class.pop3.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/includes/phpmailer/class.smtp.php';

	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/api.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/cas.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/ConfigurationUpdater.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/createcasimage.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/createimage.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/getconfig.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/getmathml.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/libwiris.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/service.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/showcasimage.php';
	$array_file_not_fomat[] = NV_ROOTDIR . '/editors/ckeditor/plugins/ckeditor_wiris/integration/showimage.php';

	if( ! in_array( $filename, $array_file_not_fomat ) )
	{
		$contents = file_get_contents( $filename );

		// Thêm dòng trắng đầu file
		$output_data = preg_replace( '/^\<\?php/', "<?php\n", trim( $contents ) );

		// Thêm dòng trắng ở cuối file
		$output_data = preg_replace( '/\?\>$/', "\n?>", $output_data );

		//Xóa các dòng trống có tab, hoặc có nhiều hơn 1 dòng trống
		$output_data = trim( preg_replace( '/\n([\t\n]+)\n/', "\n\n", $output_data ) );
		$output_data = preg_replace( '/\,\s\-\s/', ', -', $output_data );

		//Không xuống dòng nếu if có 1 lệnh
		$output_data = preg_replace( '/if\((.*)\)\n([\t\s]+)([^\{\s]{1}+)/', "if(\\1) \\3", $output_data );

		//Thêm khoảng cách vào sau và trước dấu mở ngoặc đơn
		$raw_tokens = token_get_all( $output_data );
		$array_tokend = array();
		foreach( $raw_tokens as $rawToken )
		{
			$array_tokend[] = new Token( $rawToken );
		}
		foreach( $array_tokend as $key => $tokend )
		{
			if( $tokend->contents == '(' )
			{
				if( $array_tokend[$key + 1]->type != T_WHITESPACE )
				{
					$array_tokend[$key]->contents = '( ';
				}
			}
			elseif( $tokend->contents == ')' )
			{
				if( $array_tokend[$key - 1]->type != T_WHITESPACE )
				{
					$array_tokend[$key]->contents = ' )';
				}
			}
		}
		$output_data = '';
		foreach( $array_tokend as $key => $tokend )
		{
			$output_data .= $tokend->contents;
		}

		//Xử lý mảng
		$raw_tokens = token_get_all( $output_data );

		$array_tokend = array();
		foreach( $raw_tokens as $rawToken )
		{
			$array_tokend[] = new Token( $rawToken );
		}

		$output_data = '';

		$this_line_tab = '';
		// Thut dau dong dong hien tai
		$is_in_array = 0;
		// Trong array - array cap thu bao nhieu
		$num_open_parentheses = array();
		// Dem so dau (
		$num_close_parentheses = array();
		// Dem so dau )
		$is_double_arrow = array();
		// Array co xuong hang hay khong

		$total_tokend = sizeof( $array_tokend );

		foreach( $array_tokend as $key => $tokend )
		{
			// Xac dinh so tab
			if( $tokend->type == T_WHITESPACE and preg_match( "/\n/", $tokend->contents ) and $is_in_array <= 0 )
			{
				$tab = array_filter( explode( "\n", $tokend->contents ) );
				$tab = end( $tab );

				$this_line_tab = $tab;
			}
			elseif( $tokend->type == T_CATCH and $array_tokend[$key + 1]->type == T_WHITESPACE )
			{
				$array_tokend[$key + 1]->contents = '';
			}

			// Danh dau array bat dau
			if( $tokend->type == T_ARRAY )
			{
				$is_in_array++;
				$is_double_arrow[$is_in_array] = 0;
				// Mac dinh khong co mui ten hoac array con
				$key_close_array = $key;

				// Tim trong array nay co mui ten => hay la array con hay khong
				$j = $key;
				$_num_open_parentheses = 0;
				$_num_close_parentheses = 0;

				while( $j < $total_tokend )
				{
					$j++;
					if( $array_tokend[$j]->contents == "(" ) $_num_open_parentheses++;
					if( $array_tokend[$j]->contents == ")" ) $_num_close_parentheses++;

					if( $array_tokend[$j]->type == T_DOUBLE_ARROW or $array_tokend[$j]->type == T_ARRAY or ( $array_tokend[$j]->type == T_COMMENT and $array_tokend[$j - 2]->contents == "," ) )
					{
						$is_double_arrow[$is_in_array]++;
					}

					if( $_num_open_parentheses > 0 and $_num_open_parentheses == $_num_close_parentheses )
					{
						$key_close_array = $j;
						break;
					}
				}

				$is_double_arrow[$is_in_array] = $is_double_arrow[$is_in_array] > 2 ? true : false;

				$num_open_parentheses[$is_in_array] = 0;
				$num_close_parentheses[$is_in_array] = 0;
			}

			if( $is_in_array > 0 )
			{
				if( empty( $is_double_arrow[$is_in_array] ) and $tokend->type == T_WHITESPACE )
				{
					$tokend->contents = str_replace( array( "\n", "\t" ), array( " ", "" ), $tokend->contents );
				}

				// Xoa dau , cuoi cung cua array
				if( $key == ( $key_close_array - 2 ) and $tokend->contents == "," and $tokend->type == - 1 )
				{
					$tokend->contents = '';
				}
				elseif( $tokend->type == T_WHITESPACE and preg_match( "/\n/", $tokend->contents ) and ! empty( $is_double_arrow[$is_in_array] ) )
				{
					$tokend->contents = "\n" . $this_line_tab;
					for( $i = 0; $i < $is_in_array; ++$i )
					{
						$tokend->contents .= "\t";
					}
				}

				// Dong mo array
				if( $tokend->contents == "(" ) $num_open_parentheses[$is_in_array]++;
				if( $tokend->contents == ")" ) $num_close_parentheses[$is_in_array]++;

				if( $num_open_parentheses[$is_in_array] > 0 and $num_open_parentheses[$is_in_array] == $num_close_parentheses[$is_in_array] )
				{
					if( ! empty( $is_double_arrow[$is_in_array] ) )
					{
						$output_data = trim( $output_data ) . "\n" . $this_line_tab;
						for( $i = 1; $i < $is_in_array; ++$i )
						{
							$output_data .= "\t";
						}
					}
					$output_data .= ")";

					$is_in_array--;
				}
				else
				{
					$output_data .= $tokend->contents;
				}
			}
			else
			{
				$output_data .= $tokend->contents;
			}
		}

		// Loại bỏ khoảng trắng ()
		$output_data = preg_replace( '/\([\s]+\)/', '()', $output_data );
		$output_data = preg_replace( "/[ ]+/", " ", $output_data );

		if( $output_data != $contents )
		{
			return file_put_contents( $filename, trim( $output_data ), LOCK_EX );
		}
	}
	return 0;
}

function nv_fomat_file_js( $filename )
{
	// ap dung cho Zend Studio 9.0.4
	$contents = file_get_contents( $filename );
	$output_data = preg_replace( '/\n([\t\n]+)\n/', "\n\n", $contents );
	//Xóa các dòng trống có tab, hoặc có nhiều hơn 1 dòng trống
	$output_data = str_replace( '( var ', '(var ', $output_data );
	if( $output_data != $contents )
	{
		return file_put_contents( $filename, trim( $output_data ), LOCK_EX );
	}
	return 0;
}

function nv_fomat_file_tpl( $filename )
{
	$contents = file_get_contents( $filename );
	// Xóa dòng trống ở cuối và sửa khoảng trống 				<textarea
	$contentssave = trim( preg_replace( '/\>\s*\<textarea\s*/', '><textarea ', $contents ) );
	$contentssave = str_replace( '<td></td>', '<td>&nbsp;</td>', $contentssave );
	$contentssave = str_replace( '<tbody{', '<tbody {', $contentssave );
	$contentssave = str_replace( '<li{', '<li {', $contentssave );
	$contentssave = str_replace( '<blockquote{CLASS}>', '<blockquote {CLASS}>', $contentssave );

	$dom = new DOMDocument();
	$dom->loadHTML( $contentssave );
	$dom->preserveWhiteSpace = false;
	$Tagname = $dom->getElementsByTagname( 'script' );
	foreach( $Tagname as $child )
	{
		$tmp_dom = new DOMDocument();
		$tmp_dom->appendChild( $tmp_dom->importNode( $child, true ) );
		$html = trim( $tmp_dom->saveHTML() );
		if( preg_match( '/\<\!\-\-\s*BEGIN:\s*([a-zA-Z0-9\-\_]+)\s*\-\-\>/', $html, $m ) )
		{
			print_r( "Xtemplate: " . $m[1] . " trong JS file: " . $filename );
			return 0;
		}
		elseif( preg_match_all( '/\{\s*\n*\t*([a-zA-Z0-9\-\_\.]+)\s*\n*\t*\}/i', $html, $m ) )
		{
			$s = sizeof( $m[0] );
			for( $i = 0; $i <= $s; $i++ )
			{
				$contentssave = ( string )str_replace( $m[0][$i], '{' . trim( $m[1][$i] ) . '}', $contentssave );
			}
		}
	}
	//Xóa các dòng trống có tab, hoặc có nhiều hơn 1 dòng trống
	$contentssave = trim( preg_replace( '/\n([\t\n]+)\n/', "\n\n", $contentssave ) );

	if( $contentssave != $contents )
	{
		return file_put_contents( $filename, $contentssave, LOCK_EX );
	}
	return 0;
}

$filename = isset( $_GET['f'] ) ? trim( $_GET['f'] ) : '';
$filename = str_replace( '..', '', $filename );
if( preg_match( "/^([a-zA-Z0-9\-\_\/\.]+)\.php$/", $filename ) )
{
	if( ! nv_fomat_file_php( NV_ROOTDIR . '/' . $filename ) )
	{
		echo $filename . ' ---------------------- no change ----------------------<br>';
	}
	else
	{
		echo $filename . '<br>';
	}
}
elseif( preg_match( "/^([a-zA-Z0-9\-\_\/\.]+)\.tpl$/", $filename ) )
{
	if( ! nv_fomat_file_tpl( NV_ROOTDIR . '/' . $filename ) )
	{
		echo $filename . ' ---------------------- no change ----------------------<br>';
	}
	else
	{
		echo $filename . '<br>';
	}
}
elseif( preg_match( "/^([a-zA-Z0-9\-\_\/\.]+)\.js$/", $filename ) )
{
	if( ! nv_fomat_file_js( NV_ROOTDIR . '/' . $filename ) )
	{
		echo $filename . ' ---------------------- no change ----------------------<br>';
	}
	else
	{
		echo $filename . '<br>';
	}
}
elseif( ( preg_match( "/^([a-zA-Z0-9\-\_\/]+)$/", $filename ) or $filename == '' ) and is_dir( NV_ROOTDIR . '/' . $filename ) )
{
	$all = isset( $_GET['all'] ) ? intval( $_GET['all'] ) : 0;
	nv_fomat_dir( $filename, $all );
}
else
{
	die( $filename );
}

?>