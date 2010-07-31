<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_IS_MOD_VOTING' ) ) die( 'Stop!!!' );

function voting_result ( $voting )
{
    global $module_info, $global_config, $module_file;
    $xtpl = new XTemplate( "result.voting.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $script = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.min.js\"></script>\n";
    $script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/poll.js\"></script>\n";
    $xtpl->assign( 'SCRIPT', $script );
    if ( isset( $voting['total'] ) )
    {
        $totalvote = $voting['total'];
    }
    $xtpl->assign( 'PUBLTIME', $voting['pubtime'] );
    $xtpl->assign( 'LANG', $voting['lang'] );
    if ( ! empty( $voting['note'] ) )
    {
        $xtpl->assign( 'VOTINGNOTE', $voting['note'] );
        $xtpl->parse( 'main.note' );
    }
    if ( isset( $voting['row'] ) )
    {
        $a = 1;
        foreach ( $voting['row'] as $voting_i )
        {
            $xtpl->assign( 'VOTING', $voting_i );
            $xtpl->assign( 'BG', ( ( $a == 1 ) ? 'background-color: rgb(0, 102, 204);' : '' ) );
            $xtpl->assign( 'ID', $a );
            $width = ( $voting_i['hitstotal'] / $totalvote ) * 100;
            $width = round( $width, 2 );
            $xtpl->assign( 'WIDTH', $width );
            $xtpl->assign( 'TOTAL', $totalvote );
            if ( $voting_i['title'] )
            {
                $xtpl->parse( 'main.result' );
            }
            $a ++;
        }
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

?>