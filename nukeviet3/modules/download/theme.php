<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

function theme_main_download ( $data_content_par, $data_content_chid )
{
    global $global_config, $lang_module, $module_info, $module_name, $configdownload;
    
    $xtpl = new XTemplate( "main_page.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/download/" );
    $xtpl->assign( 'LANG', $lang_module );
    if ( ! empty( $data_content_par ) )
    {
        foreach ( $data_content_par as $title_par => $content )
        {
            $xtpl->assign( 'LINK_URL_CATE', $content['link'] );
            $xtpl->assign( 'CATE_TITLE', $title_par );
            foreach ( $data_content_chid[$title_par] as $title_sub )
            {
                $xtpl->assign( 'LINK_URL_CATE_SUB', $title_sub['link'] );
                $xtpl->assign( 'CATE_TITLE_SUB', $title_sub['title'] );
                $xtpl->parse( 'main.loop_tab_cate.loop_sub_title' );
            }
            if ( empty( $content['content'] ) )
            {
                $xtpl->parse( 'main.loop_tab_cate.none_data' );
            }
            else
            {
                foreach ( $content['content'] as $values )
                {
                    $xtpl->assign( 'FILE_TITLE', $values['title'] );
                    
                    if ( $values['fileimage'] != '' )
                    {
                        if ( ! nv_is_url( $values['fileimage'] ) )
                        {
                            $values['fileimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images/" . $values['fileimage'];
                        }
                        
                        $xtpl->assign( 'SRC_IMG', $values['fileimage'] );
                        $xtpl->parse( 'main.loop_tab_cate.have_data.img' );
                    }
                    $xtpl->assign( 'FILE_HOME_TEXT', nv_clean60( $values['introtext'], $configdownload['textlimit'] ) );
                    $xtpl->assign( 'FILE_SIZE', $values['filesize'] );
                    $xtpl->assign( 'AUTHOR', $values['author'] );
                    $xtpl->assign( 'DATE_UP', date( 'd/m/Y', $values['uploadtime'] ) );
                    $xtpl->assign( 'NUM_VIEW', $values['view'] );
                    $xtpl->assign( 'NUM_DOW', $values['download'] );
                    $xtpl->assign( 'LINK_FILE_VIEW', $values['link_view'] );
                    $xtpl->assign( 'LINK_FILE_DOW', $values['link_dow'] );
                    if ( $configdownload['showcaptcha'] == 0 ) $xtpl->parse( 'main.loop_tab_cate.have_data.down' );
                    $xtpl->assign( 'COPY_RIGHT', $values['copyright'] );
                    if ( defined( 'NV_IS_ADMIN' ) )
                    {
                        $xtpl->assign( 'ADMIN_LINK', adminlink( $values['id'] ) );
                        $xtpl->assign( 'URL_RE', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
                    }
                    $xtpl->parse( 'main.loop_tab_cate.have_data' );
                }
            }
            $xtpl->parse( 'main.loop_tab_cate' );
        }
    }
    
    $xtpl->assign( 'BASE_SITE_THEMES_URL', NV_BASE_SITEURL . "themes/" . $module_info['template'] );
    $xtpl->assign( 'LINK_UP_FILE', NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=upload" );
    $permission = false;
    if ( $configdownload['who_view6'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view2'] ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view6'] == 0 )
    {
        $permission = true;
    }
    if ( $configdownload['who_view6'] == 1 && defined( 'NV_IS_USER' ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view6'] == 2 && defined( 'NV_IS_ADMIN' ) )
    {
        $permission = true;
    }
    if ( $permission )
    {
        $xtpl->parse( 'main.upload' );
    }
    
    if ( defined( 'NV_IS_ADMIN' ) ) $xtpl->parse( 'main.script' );
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function view_file ( $data_content, $data_comment, $dow_err, $cap_err, $fileid, $page, $otherfile, $islink )
{
    global $global_config, $lang_global, $lang_module, $module_name, $module_info, $configdownload, $my_head;
    $xtpl = new XTemplate( "viewfile.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/download/" );
    $path_src = NV_BASE_SITEURL;
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'TITLE_FILE', $data_content['title'] );
    $xtpl->assign( 'FILE_SIZE', $data_content['filesize'] );
    $xtpl->assign( 'AUTHOR', $data_content['author'] );
    $xtpl->assign( 'DATE_UP', date( 'd/m/Y', $data_content['uploadtime'] ) );
    $xtpl->assign( 'NUM_VIEW', $data_content['view'] );
    $xtpl->assign( 'NUM_DOW', $data_content['download'] );
    $xtpl->assign( 'NUM_COM', $data_content['comment'] );
    $xtpl->assign( 'URL_BASE_THEMES', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/" );
    $xtpl->assign( 'URL_BASE_SITE', NV_BASE_SITEURL );
    $xtpl->assign( 'LINK_FILE_DOW', $data_content['link_dow'] );
    $xtpl->assign( 'NV_LENGTH_CAPCHA', NV_GFX_NUM );
    $xtpl->assign( 'URL_BACK', NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
    $xtpl->assign( 'URL_REPORT', NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=report&id=' . $fileid );
    
    if ( $data_content['fileimage'] != '' )
    {
        if ( ! nv_is_url( $data_content['fileimage'] ) )
        {
            $data_content['fileimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images/" . $data_content['fileimage'];
        }
        $xtpl->assign( 'SRC_IMG', $data_content['fileimage'] );
        $xtpl->parse( 'main.img' );
    }
    $copy_r = $lang_module['main_copyright_trial'];
    if ( $cap_err != '0' ) $xtpl->parse( 'main.capcha.err' );
    if ( $dow_err != '0' ) $xtpl->parse( 'main.capcha.dow_err' );
    if ( $configdownload['showcaptcha'] == '1' )
    {
        $xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
        $xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . "?scaptcha=downloadfile" );
        $xtpl->parse( 'main.capcha' );
    }
    $xtpl->assign( 'COPY_RIGHT', $data_content['copyright'] );
    $links = explode( "\n", $data_content['linkdirect'] );
    $str_html_link = "";
    foreach ( $links as $link )
    {
        $str_html_link .= "<a href=\"" . $link . "\" target=\"_blank\">$link</a><br/>";
    }
    if ( ( $islink == 2 && $configdownload['showcaptcha'] == '0' ) || $islink == 2 )
    {
        $xtpl->assign( 'LINK_FILE', $str_html_link );
        $xtpl->parse( 'main.linkdir' );
    }
    $xtpl->assign( 'BODY_TEXT', $data_content['description'] );
    $my_head .= '
        <script type="text/javascript">
        $(function(){
        	$("span.ratenumber a").click(function(event){
        		event.preventDefault();
        		var rate = $(this).attr("href");
        		$.ajax({	
        			type: "POST",
        			url: "' . NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rate",
        			data: "id=' . $fileid . '&rate="+rate,
        			success: function(data){				
        				$("span[class=\'ratenumber\']").html(data);
        			}
        		});
        	});
        function validcaptcha(string) {
        	var pattern = new RegExp(/^([a-zA-Z0-9]{' . NV_GFX_NUM . '})+$/);
        	return pattern.test(string);
        }
        $("input[name=download]").click(function(){
        	var captcha = $("input[name=captcha]").val();
        	if (!validcaptcha(captcha)){
        		alert("' . $lang_module['down_error_captcha'] . '");
        		return false;
        	}
        });
        });
        </script>
        ';
    $permission = false;
    if ( $configdownload['who_view3'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view2'] ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view3'] == 0 )
    {
        $permission = true;
    }
    if ( $configdownload['who_view3'] == 1 && defined( 'NV_IS_USER' ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view3'] == 2 && defined( 'NV_IS_ADMIN' ) )
    {
        $permission = true;
    }
    if ( $permission )
    {
        $xtpl->parse( 'main.errlink' );
    }
    
    $permission = false;
    if ( $configdownload['who_view2'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view2'] ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view2'] == 0 )
    {
        $permission = true;
    }
    if ( $configdownload['who_view2'] == 1 && defined( 'NV_IS_USER' ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view2'] == 2 && defined( 'NV_IS_ADMIN' ) )
    {
        $permission = true;
    }
    if ( $permission )
    {
        $xtpl->assign( 'GET_COMMENT', NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=getcomment&id=' . $fileid . '&page=' . $page . '' );
        $xtpl->parse( 'main.comment' );
    }
    if ( ! empty( $otherfile ) )
    {
        foreach ( $otherfile as $otherfile_i )
        {
            $xtpl->assign( 'otherfile', $otherfile_i );
            $xtpl->parse( 'main.others.otherfile' );
        }
        $xtpl->parse( 'main.others' );
    }
    if ( defined( 'NV_IS_ADMIN' ) )
    {
        $xtpl->assign( 'ADMIN_LINK', adminlink( $data_content['id'] ) );
        $xtpl->assign( 'URL_RE', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        $xtpl->parse( 'main.script' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function viewcat ( $data_content, $title_par, $list_pages, $title_sub )
{
    global $global_config, $lang_module, $module_name, $configdownload, $module_info;
    $path_src = NV_BASE_SITEURL;
    $xtpl = new XTemplate( "viewcat.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/download/" );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'CATE_TITLE', $title_par );
    if ( ! empty( $title_sub ) )
    {
        foreach ( $title_sub as $values )
        {
            $xtpl->assign( 'LINK_URL_CATE_SUB', $values['link'] );
            $xtpl->assign( 'CATE_TITLE_SUB', $values['title'] );
            $xtpl->parse( 'main.loop_sub_title' );
        }
    }
    if ( ! empty( $data_content ) )
    {
        foreach ( $data_content as $values )
        {
            $xtpl->assign( 'FILE_TITLE', $values['title'] );
            if ( $values['fileimage'] != '' )
            {
                if ( ! nv_is_url( $values['fileimage'] ) )
                {
                    $values['fileimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images/" . $values['fileimage'];
                }
                $xtpl->assign( 'SRC_IMG', $values['fileimage'] );
                $xtpl->parse( 'main.have_data.img' );
            }
            
            $xtpl->assign( 'FILE_HOME_TEXT', nv_clean60( $values['introtext'], 200 ) );
            $xtpl->assign( 'FILE_SIZE', $values['filesize'] );
            $xtpl->assign( 'AUTHOR', $values['author'] );
            $xtpl->assign( 'DATE_UP', date( 'd/m/Y', $values['uploadtime'] ) );
            $xtpl->assign( 'NUM_VIEW', $values['view'] );
            $xtpl->assign( 'NUM_DOW', $values['download'] );
            $xtpl->assign( 'LINK_FILE_VIEW', $values['link_view'] );
            $xtpl->assign( 'LINK_FILE_DOW', $values['link_dow'] );
            if ( $configdownload['showcaptcha'] == 0 ) $xtpl->parse( 'main.have_data.down' );
            $xtpl->assign( 'COPY_RIGHT', $values['copyright'] );
            if ( defined( 'NV_IS_ADMIN' ) )
            {
                $xtpl->assign( 'ADMIN_LINK', adminlink( $values['id'] ) );
                $xtpl->assign( 'URL_RE', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
            }
            $xtpl->parse( 'main.have_data' );
        }
    }
    else
    {
        $xtpl->parse( 'main.none_data' );
    }
    $xtpl->assign( 'PAGES', $list_pages );
    $xtpl->parse( 'main.pages' );
    if ( defined( 'NV_IS_ADMIN' ) ) $xtpl->parse( 'main.script' );
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function report ( $fileid, $permission, $er, $url, $content_r )
{
    global $global_config, $my_head, $lang_module, $module_name, $configdownload, $user_info, $module_info;
    $xtpl = new XTemplate( "report.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/download/" );
    #group grant
    $xtpl->assign( 'content', array( 
        'title' => $lang_module['report_title'], 'content' => $lang_module['report_content'], 'report_title_send' => $lang_module['report_title_send'] 
    ) );
    if ( $er == "1" )
    {
        $xtpl->assign( 'MSG', $lang_module['report_sucsser'] );
        $xtpl->parse( 'main.content.msg' );
        $xtpl->assign( 'META', "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=" . $url . "\" >" );
    }
    if ( $er == "2" )
    {
        $xtpl->assign( 'MSG', $lang_module['report_error'] );
        $xtpl->parse( 'main.content.msg' );
        $xtpl->assign( 'CONENT_R', $content_r );
        $xtpl->parse( 'main.content.content_n' );
    }
    if ( $er != "2" && $er != "1" )
    {
        $xtpl->parse( 'main.content.content_n' );
    }
    $xtpl->parse( 'main.content' );
    if ( ! $permission )
    {
        $xtpl->assign( 'permission', $lang_module['report_permission'] );
        $xtpl->parse( 'main.permission' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_uploads_form ( $content, $con_data, $error, $ispost )
{
    global $global_config, $my_head, $lang_module, $module_name, $configdownload, $user_info, $module_data, $module_info;
    $url = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
    $xtpl = new XTemplate( "upload.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/download/" );
    $xtpl->assign( 'LANG', $lang_module );
    if ( ! empty( $content['cat'] ) )
    {
        foreach ( $content['cat'] as $rows_i )
        {
            $xtpl->assign( 'selectcat', $rows_i );
            $xtpl->parse( 'main.content.selectcat' );
        }
    }
    $xtpl->assign( 'FILE_TYPE', $content['filetype'] );
    $permission = false;
    if ( $configdownload['who_view6'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view6'] ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view6'] == 0 )
    {
        $permission = true;
    }
    if ( $configdownload['who_view6'] == 1 && defined( 'NV_IS_USER' ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view6'] == 2 && defined( 'NV_IS_ADMIN' ) )
    {
        $permission = true;
    }
    $xtpl->assign( 'content', $content );
    $xtpl->assign( 'DATA', $con_data );
    $xtpl->assign( 'ERROR', $error );
    if ( $permission )
    {
        $xtpl->parse( 'main.content.fileupload' );
        $xtpl->parse( 'main.content.javaup' );
    }
    $xtpl->assign( 'BASE_SITE_URL', NV_BASE_SITEURL );
    $xtpl->assign( 'BASE_SITE_URL_CHECK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=checkcaptcha' );
    if ( $ispost != 1 ) $xtpl->parse( 'main.content' );
    else
    {
        
        $xtpl->assign( 'MSG', $lang_module['upload_seccer'] );
        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_data;
        $xtpl->assign( 'META', "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=" . $url . "\" >" );
        $xtpl->parse( 'main.message' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function detail ( $fileinfo, $menu, $otherfile, $check_rate, $check_form, $fileid )
{
    global $global_config, $my_head, $module_info, $lang_module, $module_name, $configdownload, $module_data, $user_info, $nv_Request, $module_info;
    $xtpl = new XTemplate( "viewfile.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/download/" );
    $xtpl->assign( 'fileinfo', $fileinfo );
    $xtpl->assign( 'content', $lang_module );
    if ( ! empty( $menu ) )
    {
        foreach ( $menu as $menu_i )
        {
            $xtpl->assign( 'menu', $menu_i );
            $xtpl->parse( 'main.content.menu' );
        }
    }
    $permission = false;
    if ( $configdownload['who_view1'] != '' )
    {
        #group grant
        if ( $configdownload['who_view1'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view1'] ) )
        {
            $permission = true;
        }
        if ( $configdownload['who_view1'] == 0 )
        {
            $permission = true;
        }
        if ( $configdownload['who_view1'] == 1 && defined( 'NV_IS_USER' ) )
        {
            $permission = true;
        }
        if ( $configdownload['who_view1'] == 2 && defined( 'NV_IS_ADMIN' ) )
        {
            $permission = true;
        }
    }
    if ( $permission )
    {
        if ( $configdownload['directlink'] )
        {
            if ( count( $fileinfo['linkdirect'] ) > 0 )
            {
                foreach ( $fileinfo['linkdirect'] as $value )
                {
                    $xtpl->assign( 'listlink', $value );
                    $xtpl->parse( 'main.content.fileinfo.listlink' );
                }
            
            }
            else
            {
                $xtpl->assign( 'downloadlink', array( 
                    'link' => $fileinfo['fileupload'], 'download' => $lang_module['main_downloadtime'] 
                ) );
                $xtpl->parse( 'main.content.fileinfo.downloadlink' );
            }
        }
        else
        {
            $xtpl->assign( 'confirm', NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=down&amp;id=" . $fileinfo['id'] );
            if ( $configdownload['showcaptcha'] )
            {
                $xtpl->assign( 'captcha', NV_BASE_SITEURL );
                $xtpl->parse( 'main.content.fileinfo.confirm.captcha' );
            }
            $xtpl->parse( 'main.content.fileinfo.confirm' );
        }
    }
    else
    {
        $xtpl->assign( 'permission', $lang_module['viewfile_permission'] );
        $xtpl->parse( 'main.content.fileinfo.permission' );
    }
    if ( ! empty( $otherfile ) )
    {
        foreach ( $otherfile as $otherfile_i )
        {
            $xtpl->assign( 'otherfile', $otherfile_i );
            $xtpl->parse( 'main.content.fileinfo.otherfile' );
        }
    }
    if ( $check_rate )
    {
        $xtpl->assign( 'rateform', $check_form );
    }
    else
    {
        $my_head .= '
        <script type="text/javascript">
        $(function(){
        	$("span.ratenumber a").click(function(event){
        		event.preventDefault();
        		var rate = $(this).attr("href");
        		$.ajax({	
        			type: "POST",
        			url: "' . NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rate",
        			data: "id=' . $fileid . '&rate="+rate,
        			success: function(data){				
        				$("span[class=\'ratenumber\']").html(data);
        			}
        		});
        	});
        function validcaptcha(string) {
        	var pattern = new RegExp(/^([a-zA-Z0-9]{' . NV_GFX_NUM . '})+$/);
        	return pattern.test(string);
        }
        $("input[name=download]").click(function(){
        	var captcha = $("input[name=captcha]").val();
        	if (!validcaptcha(captcha)){
        		alert("' . $lang_module['down_error_captcha'] . '");
        		return false;
        	}
        });
        });
        </script>
        ';
        #group grant
        $permission = false;
        if ( $configdownload['who_view4'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view4'] ) )
        {
            $permission = true;
        }
        if ( $configdownload['who_view4'] == 0 )
        {
            $permission = true;
        }
        if ( $configdownload['who_view4'] == 1 && defined( 'NV_IS_USER' ) )
        {
            $permission = true;
        }
        if ( $configdownload['who_view4'] == 2 && defined( 'NV_IS_ADMIN' ) )
        {
            $permission = true;
        }
        if ( $permission )
        {
            $xtpl->parse( 'main.content.fileinfo.rateform' );
        }
        $xtpl->parse( 'main.scriptfoot' );
    }
    #group grant comment
    $page = $nv_Request->get_int( 'page', 'get', 0 );
    $permission = false;
    if ( $configdownload['who_view2'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view2'] ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view2'] == 0 )
    {
        $permission = true;
    }
    if ( $configdownload['who_view2'] == 1 && defined( 'NV_IS_USER' ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view2'] == 2 && defined( 'NV_IS_ADMIN' ) )
    {
        $permission = true;
    }
    if ( $permission )
    {
        $commentdata = array( 
            'comment_view' => $lang_module['comment_view'], 'comment_send' => $lang_module['comment_send'], 'comment_title' => $lang_module['comment_title'], 'comment_submit' => $lang_module['comment_submit'], 'comment_name' => $lang_module['comment_name'], 'comment_email' => $lang_module['comment_email'], 'comment_content' => $lang_module['comment_content'], 'comment_seccode' => $lang_module['comment_seccode'], 'comment_seccode_refresh' => $lang_module['comment_seccode_refresh'], 'sitetheme' => $module_info['template'], 'content' => $global_config['site_theme'], 'baselink' => NV_BASE_SITEURL, 'getcomment' => NV_BASE_SITEURL . '?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=getcomment&id=' . $fileid . '&page=' . $page . '', 'noname' => $lang_module['comment_noname'], 'nocontent' => $lang_module['comment_nocontent'], 'nocaptcha' => $lang_module['comment__error_captcha'] 
        );
        $xtpl->assign( 'comment', $commentdata );
        $xtpl->parse( 'main.content.fileinfo.comment' );
    }
    $xtpl->parse( 'main.content.fileinfo' );
    $xtpl->parse( 'main.content' );
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

?>