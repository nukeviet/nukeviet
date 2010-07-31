<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
function _substr($str, $length, $minword = 3)
{
    $sub = '';
    $len = 0;
   
    foreach (explode(' ', $str) as $word)
    {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
       
        if (strlen($word) > $minword && strlen($sub) >= $length)
        {
            break;
        }
    }
   
    return $sub . (($len < strlen($str)) ? '...' : '');
}

function nv_news_page( $base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true )
{
	global $lang_global;
	$total_pages = ceil( $num_items / $per_page );
	if ( $total_pages == 1 ) return '';
	@$on_page = floor( $start_item / $per_page ) + 1;
	$page_string = "";
	if ( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		for ( $i = 1; $i <= $init_page_max; $i++ )
		{
			$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
			if ( $i < $init_page_max ) $page_string .= ", ";
		}
		if ( $total_pages > 3 )
		{
			if ( $on_page > 1 && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? " ... " : ", ";
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
				for ( $i = $init_page_min - 1; $i < $init_page_max + 2; $i++ )
				{
					$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
					$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
					if ( $i < $init_page_max + 1 )
					{
						$page_string .= ", ";
					}
				}
				$page_string .= ( $on_page < $total_pages - 4 ) ? " ... " : ", ";
			}
			else
			{
				$page_string .= " ... ";
			}

			for ( $i = $total_pages - 2; $i < $total_pages + 1; $i++ )
			{
				$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
				$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
				if ( $i < $total_pages )
				{
					$page_string .= ", ";
				}
			}
		}
	}
	else
	{
		for ( $i = 1; $i < $total_pages + 1; $i++ )
		{
			$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
			if ( $i < $total_pages )
			{
				$page_string .= ", ";
			}
		}
	}
	if ( $add_prevnext_text )
	{
		if ( $on_page > 1 )
		{
			$href = "href=\"" . $base_url . "/page-" . ( ( $on_page - 2 ) * $per_page ) . "\"";
			$page_string = "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
		}
		if ( $on_page < $total_pages )
		{
			$href = "href=\"" . $base_url . "/page-" . ( $on_page * $per_page ) . "\"";
			$page_string .= "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pagenext'] . "</a></span>";
		}
	}
	return $page_string;
}
?>