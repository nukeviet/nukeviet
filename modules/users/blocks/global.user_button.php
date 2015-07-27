<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

global $client_info, $global_config, $module_name, $user_info, $lang_global, $my_head;

$content = "";

if ( $global_config['allowuserlogin'] )
{
    $groups_list = nv_groups_list_pub();

    if ( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/users/block.user_button.tpl' ) )
    {
        $block_theme = $global_config['module_theme'];
    }
    elseif ( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/users/block.user_button.tpl' ) )
    {
        $block_theme = $global_config['site_theme'];
    }
    else
    {
        $block_theme = 'default';
    }

    $xtpl = new XTemplate( 'block.user_button.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/users' );

    if ( file_exists( NV_ROOTDIR . '/modules/users/language/' . NV_LANG_DATA . '.php' ) )
    {
        include NV_ROOTDIR . '/modules/users/language/' . NV_LANG_DATA . '.php';
    }
    else
    {
        include NV_ROOTDIR . '/modules/users/language/vi.php';
    }

    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );

    if ( defined( 'NV_IS_USER' ) )
    {
        if ( file_exists( NV_ROOTDIR . '/' . $user_info['photo'] ) and ! empty( $user_info['photo'] ) )
        {
            $avata = NV_BASE_SITEURL . $user_info['photo'];
        }
        else
        {
            $avata = NV_BASE_SITEURL . 'themes/' . $block_theme . '/images/users/no_avatar.jpg';
        }

        $xtpl->assign( 'AVATA', $avata );
        $xtpl->assign( 'USER', $user_info );

        $xtpl->assign( 'URL_MODULE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users' );
        $xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=' );

        if ( defined( 'NV_OPENID_ALLOWED' ) )
        {
            $xtpl->parse( 'signed.allowopenid' );
        }

        if ( ! empty( $groups_list ) and $global_config['allowuserpublic'] == 1 )
        {
            $xtpl->parse( 'signed.regroups' );
        }

        $xtpl->parse( 'signed' );
        $content = $xtpl->text( 'signed' );
    }
    else
    {
        $xtpl->assign( 'USER_LOSTPASS', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass', true ) );
        $xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
        $xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
        $xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
        $xtpl->assign( 'CHECKSESS', md5( $client_info['session_id'] . $global_config['sitekey'] ) );
        $xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
        $xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
        $xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME );

        if ( in_array( $global_config['gfx_chk'], array(
            2,
            4,
            5,
            7 ) ) )
        {
            $xtpl->parse( 'main.captcha' );
        }

        if ( in_array( $global_config['gfx_chk'], array(
            3,
            4,
            6,
            7 ) ) )
        {
            $xtpl->parse( 'main.allowuserreg.captcha_reg' );
        }

        if ( defined( 'NV_OPENID_ALLOWED' ) )
        {
            foreach ( $global_config['openid_servers'] as $server )
            {
                $assigns = array();
                $assigns['href'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=' . $server . '&amp;nv_redirect=' . nv_base64_encode( $client_info['selfurl'] );
                $assigns['title'] = $lang_global['openid_login'] . " " . ucfirst( $server );
                $assigns['img_src'] = NV_BASE_SITEURL . 'themes/' . $block_theme . '/images/users/' . $server . '.png';
                $assigns['img_width'] = $assigns['img_height'] = 24;

                $xtpl->assign( 'OPENID', $assigns );
                $xtpl->parse( 'main.openid.server' );
            }
            $xtpl->parse( 'main.openid' );
        }

        if ( $global_config['allowuserreg'] )
        {
            $xtpl->parse( 'main.allowuserreg' );
            $xtpl->parse( 'main.allowuserreg2' );
            $xtpl->parse( 'main.allowuserreg3' );
        }

        $xtpl->parse( 'main' );
        $content = $xtpl->text( 'main' );
    }
}
