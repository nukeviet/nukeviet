<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Edit file
if( $nv_Request->isset_request( 'edit', 'get' ) )
{
    $report = $nv_Request->isset_request( 'report', 'get' );

    $id = $nv_Request->get_int( 'id', 'get', 0 );

    if( $id )
    {
        $query = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
        $result = $db->sql_query( $query );
        $numrows = $db->sql_numrows( $result );
        if( $numrows != 1 )
        {
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
            exit();
        }

        define( 'IS_EDIT', true );
        $page_title = $lang_module['download_editfile'];

        $row = $db->sql_fetchrow( $result );
    }
    else
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }

    $groups_list = nv_groups_list();
    $array_who = array(
        $lang_global['who_view0'],
        $lang_global['who_view1'],
        $lang_global['who_view2'] );
    if( ! empty( $groups_list ) )
    {
        $array_who[] = $lang_global['who_view3'];
    }

    $array = array();
    $is_error = false;
    $error = "";

    if( $nv_Request->isset_request( 'submit', 'post' ) )
    {
        $array['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
        $array['title'] = filter_text_input( 'title', 'post', '', 1 );
        $array['description'] = nv_editor_filter_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
        $array['introtext'] = filter_text_textarea( 'introtext', '', NV_ALLOWED_HTML_TAGS );
        $array['author_name'] = filter_text_input( 'author_name', 'post', '', 1 );
        $array['author_email'] = filter_text_input( 'author_email', 'post', '' );
        $array['author_url'] = filter_text_input( 'author_url', 'post', '' );
        $array['fileupload'] = $nv_Request->get_typed_array( 'fileupload', 'post', 'string' );
        $array['linkdirect'] = $nv_Request->get_typed_array( 'linkdirect', 'post', 'string' );
        $array['version'] = filter_text_input( 'version', 'post', '', 1 );
        $array['fileimage'] = filter_text_input( 'fileimage', 'post', '' );
        $array['copyright'] = filter_text_input( 'copyright', 'post', '', 1 );
        $array['comment_allow'] = $nv_Request->get_int( 'comment_allow', 'post', 0 );
        $array['who_comment'] = $nv_Request->get_int( 'who_comment', 'post', 0 );
        $array['groups_comment'] = $nv_Request->get_typed_array( 'groups_comment', 'post', 'int' );
        $array['is_del_report'] = $nv_Request->get_int( 'is_del_report', 'post', 0 );

        $array['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
        $array['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );
        $array['who_download'] = $nv_Request->get_int( 'who_download', 'post', 0 );
        $array['groups_download'] = $nv_Request->get_typed_array( 'groups_download', 'post', 'int' );

        if( ! empty( $array['author_url'] ) )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['author_url'] ) )
            {
                $array['author_url'] = "http://" . $array['author_url'];
            }
        }

        $array['filesize'] = 0;
        if( ! empty( $array['fileupload'] ) )
        {
            $fileupload = $array['fileupload'];
            $array['fileupload'] = array();
            foreach( $fileupload as $file )
            {
                if( ! empty( $file ) )
                {
                    $file2 = substr( $file, strlen( NV_BASE_SITEURL ) );
                    if( file_exists( NV_ROOTDIR . '/' . $file2 ) and ( $filesize = filesize( NV_ROOTDIR . '/' . $file2 ) ) != 0 )
                    {
                        $array['fileupload'][] = substr( $file, strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
                        $array['filesize'] += $filesize;
                    }
                }
            }
        }
        else
        {
            $array['fileupload'] = array();
        }

        // Sort image
        if( ! empty( $array['fileimage'] ) )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['fileimage'] ) )
            {
                $array['fileimage'] = substr( $array['fileimage'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
            }
        }

        if( ! empty( $array['linkdirect'] ) )
        {
            $linkdirect = $array['linkdirect'];
            $array['linkdirect'] = array();
            foreach( $linkdirect as $links )
            {
                $linkdirect2 = array();
                if( ! empty( $links ) )
                {
                    $links = nv_nl2br( $links, "<br />" );
                    $links = explode( "<br />", $links );
                    $links = array_map( "trim", $links );
                    $links = array_unique( $links );

                    foreach( $links as $link )
                    {
                        if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $link ) )
                        {
                            $link = "http://" . $link;
                        }
                        if( nv_is_url( $link ) )
                        {
                            $linkdirect2[] = $link;
                        }
                    }
                }

                if( ! empty( $linkdirect2 ) )
                {
                    $array['linkdirect'][] = implode( "\n", $linkdirect2 );
                }
            }
        }
        else
        {
            $array['linkdirect'] = array();
        }
        if( ! empty( $array['linkdirect'] ) )
        {
            $array['linkdirect'] = array_unique( $array['linkdirect'] );
        }

        if( ! empty( $array['linkdirect'] ) and empty( $array['fileupload'] ) )
        {
            $array['filesize'] = $nv_Request->get_int( 'filesize', 'post', 0 );
        }

        $alias = change_alias( $array['title'] );

        $sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`!=" . $id . " AND `alias`=" . $db->dbescape( $alias );
        $result = $db->sql_query( $sql );
        list( $is_exists ) = $db->sql_fetchrow( $result );

        if( ! $is_exists )
        {
            $sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE `title`=" . $db->dbescape( $array['title'] );
            $result = $db->sql_query( $sql );
            list( $is_exists ) = $db->sql_fetchrow( $result );
        }

        if( empty( $array['title'] ) )
        {
            $is_error = true;
            $error = $lang_module['file_error_title'];
        }
        elseif( $is_exists )
        {
            $is_error = true;
            $error = $lang_module['file_title_exists'];
        }
        elseif( ! empty( $array['author_email'] ) and ( $check_valid_email = nv_check_valid_email( $array['author_email'] ) ) != "" )
        {
            $is_error = true;
            $error = $check_valid_email;
        }
        elseif( ! empty( $array['author_url'] ) and ! nv_is_url( $array['author_url'] ) )
        {
            $is_error = true;
            $error = $lang_module['file_error_author_url'];
        }
        elseif( empty( $array['fileupload'] ) and empty( $array['linkdirect'] ) )
        {
            $is_error = true;
            $error = $lang_module['file_error_fileupload'];
        }
        else
        {
            $array['introtext'] = ! empty( $array['introtext'] ) ? nv_nl2br( $array['introtext'], "<br />" ) : "";
            $array['description'] = ! empty( $array['description'] ) ? nv_editor_nl2br( $array['description'] ) : $array['introtext'];
            $array['fileupload'] = ( ! empty( $array['fileupload'] ) ) ? implode( "[NV]", $array['fileupload'] ) : "";
            if( ( ! empty( $array['linkdirect'] ) ) )
            {
                $array['linkdirect'] = array_map( "nv_nl2br", $array['linkdirect'] );
                $array['linkdirect'] = implode( "[NV]", $array['linkdirect'] );
            }
            else
            {
                $array['linkdirect'] = "";
            }

            if( ! in_array( $array['who_comment'], array_keys( $array_who ) ) )
            {
                $array['who_comment'] = 0;
            }
            if( ! in_array( $array['who_view'], array_keys( $array_who ) ) )
            {
                $array['who_view'] = 0;
            }
            if( ! in_array( $array['who_download'], array_keys( $array_who ) ) )
            {
                $array['who_download'] = 0;
            }

            $array['groups_comment'] = ( ! empty( $array['groups_comment'] ) ) ? implode( ',', $array['groups_comment'] ) : '';
            $array['groups_view'] = ( ! empty( $array['groups_view'] ) ) ? implode( ',', $array['groups_view'] ) : '';
            $array['groups_download'] = ( ! empty( $array['groups_download'] ) ) ? implode( ',', $array['groups_download'] ) : '';

            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET 
                `catid`=" . $array['catid'] . ", 
                `title`=" . $db->dbescape( $array['title'] ) . ", 
                `alias`=" . $db->dbescape( $alias ) . ", 
                `description`=" . $db->dbescape( $array['description'] ) . ", 
                `introtext`=" . $db->dbescape( $array['introtext'] ) . ", 
                `updatetime`=" . NV_CURRENTTIME . ", 
                `author_name`=" . $db->dbescape( $array['author_name'] ) . ", 
                `author_email`=" . $db->dbescape( $array['author_email'] ) . ", 
                `author_url`=" . $db->dbescape( $array['author_url'] ) . ", 
                `fileupload`=" . $db->dbescape( $array['fileupload'] ) . ", 
                `linkdirect`=" . $db->dbescape( $array['linkdirect'] ) . ", 
                `version`=" . $db->dbescape( $array['version'] ) . ", 
                `filesize`=" . $array['filesize'] . ", 
                `fileimage`=" . $db->dbescape( $array['fileimage'] ) . ", 
                `copyright`=" . $db->dbescape( $array['copyright'] ) . ", 
                `comment_allow`=" . $array['comment_allow'] . ", 
                `who_comment`=" . $array['who_comment'] . ", 
                `groups_comment`=" . $db->dbescape( $array['groups_comment'] ) . ",
                `who_view`=" . $array['who_view'] . ", 
                `groups_view`=" . $db->dbescape( $array['groups_view'] ) . ",
                `who_download`=" . $array['who_download'] . ", 
                `groups_download`=" . $db->dbescape( $array['groups_download'] ) . " 
                WHERE `id`=" . $id;
            $result = $db->sql_query( $sql );

            if( ! $result )
            {
                $is_error = true;
                $error = $lang_module['file_error1'];
            }
            else
            {
                if( $report and $array['is_del_report'] )
                {
                    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_report` WHERE `fid`=" . $id;
                    $db->sql_query( $sql );
                }
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['download_editfile'], $array['title'], $admin_info['userid'] );
                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
                exit();
            }
        }

        $array['fileupload'] = ( ! empty( $array['fileupload'] ) ) ? explode( "[NV]", $array['fileupload'] ) : array();
    }
    else
    {
        $array['catid'] = ( int )$row['catid'];
        $array['title'] = $row['title'];
        $array['description'] = nv_editor_br2nl( $row['description'] );
        $array['introtext'] = nv_br2nl( $row['introtext'] );
        $array['author_name'] = $row['author_name'];
        $array['author_email'] = $row['author_email'];
        $array['author_url'] = $row['author_url'];
        $array['fileupload'] = $row['fileupload'];
        $array['linkdirect'] = $row['linkdirect'];
        $array['version'] = $row['version'];
        $array['filesize'] = ( int )$row['filesize'];
        $array['fileimage'] = $row['fileimage'];
        $array['copyright'] = $row['copyright'];
        $array['comment_allow'] = ( int )$row['comment_allow'];
        $array['who_comment'] = ( int )$row['who_comment'];
        $array['groups_comment'] = $row['groups_comment'];

        $array['who_view'] = ( int )$row['who_view'];
        $array['groups_view'] = $row['groups_view'];
        $array['who_download'] = ( int )$row['who_download'];
        $array['groups_download'] = $row['groups_download'];

        $array['fileupload'] = ! empty( $array['fileupload'] ) ? explode( "[NV]", $array['fileupload'] ) : array();
        if( ! empty( $array['linkdirect'] ) )
        {
            $array['linkdirect'] = explode( "[NV]", $array['linkdirect'] );
            $array['linkdirect'] = array_map( "nv_br2nl", $array['linkdirect'] );
        }
        else
        {
            $array['linkdirect'] = array();
        }
        $array['groups_comment'] = ! empty( $array['groups_comment'] ) ? explode( ",", $array['groups_comment'] ) : array();
        $array['groups_view'] = ! empty( $array['groups_view'] ) ? explode( ",", $array['groups_view'] ) : array();
        $array['groups_download'] = ! empty( $array['groups_download'] ) ? explode( ",", $array['groups_download'] ) : array();
        $array['is_del_report'] = 1;
    }

    if( ! empty( $array['description'] ) ) $array['description'] = nv_htmlspecialchars( $array['description'] );
    if( ! empty( $array['introtext'] ) ) $array['introtext'] = nv_htmlspecialchars( $array['introtext'] );

    $array['fileupload_num'] = sizeof( $array['fileupload'] );
    $array['linkdirect_num'] = sizeof( $array['linkdirect'] );

    // Build fileimage
    if( ! empty( $array['fileimage'] ) )
    {
        if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['fileimage'] ) )
        {
            $array['fileimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['fileimage'];
        }
    }

    //Rebuild fileupload
    if( ! empty( $array['fileupload'] ) )
    {
        $fileupload = $array['fileupload'];
        $array['fileupload'] = array();
        foreach( $fileupload as $tmp )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $tmp ) )
            {
                $tmp = NV_BASE_SITEURL . NV_UPLOADS_DIR . $tmp;
            }
            $array['fileupload'][] = $tmp;
        }
    }

    if( ! sizeof( $array['fileupload'] ) ) array_push( $array['fileupload'], "" );
    if( ! sizeof( $array['linkdirect'] ) ) array_push( $array['linkdirect'], "" );

    $listcats = nv_listcats( $array['catid'] );
    if( empty( $listcats ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1" );
        exit();
    }

    $array['comment_allow'] = $array['comment_allow'] ? " checked=\"checked\"" : "";
    $array['is_del_report'] = $array['is_del_report'] ? " checked=\"checked\"" : "";

    $who_comment = $array['who_comment'];
    $array['who_comment'] = array();
    foreach( $array_who as $key => $who )
    {
        $array['who_comment'][] = array( //
            'key' => $key, //
            'title' => $who, //
            'selected' => $key == $who_comment ? " selected=\"selected\"" : "" //
                );
    }

    $groups_comment = $array['groups_comment'];
    $array['groups_comment'] = array();
    if( ! empty( $groups_list ) )
    {
        foreach( $groups_list as $key => $title )
        {
            $array['groups_comment'][] = array( //
                'key' => $key, //
                'title' => $title, //
                'checked' => in_array( $key, $groups_comment ) ? " checked=\"checked\"" : "" //
                    );
        }
    }

    $who_view = $array['who_view'];
    $array['who_view'] = array();
    foreach( $array_who as $key => $who )
    {
        $array['who_view'][] = array( //
            'key' => $key, //
            'title' => $who, //
            'selected' => $key == $who_view ? " selected=\"selected\"" : "" //
                );
    }

    $groups_view = $array['groups_view'];
    $array['groups_view'] = array();
    if( ! empty( $groups_list ) )
    {
        foreach( $groups_list as $key => $title )
        {
            $array['groups_view'][] = array( //
                'key' => $key, //
                'title' => $title, //
                'checked' => in_array( $key, $groups_view ) ? " checked=\"checked\"" : "" //
                    );
        }
    }

    $who_download = $array['who_download'];
    $array['who_download'] = array();
    foreach( $array_who as $key => $who )
    {
        $array['who_download'][] = array( //
            'key' => $key, //
            'title' => $who, //
            'selected' => $key == $who_download ? " selected=\"selected\"" : "" //
                );
    }

    $groups_download = $array['groups_download'];
    $array['groups_download'] = array();
    if( ! empty( $groups_list ) )
    {
        foreach( $groups_list as $key => $title )
        {
            $array['groups_download'][] = array( //
                'key' => $key, //
                'title' => $title, //
                'checked' => in_array( $key, $groups_download ) ? " checked=\"checked\"" : "" //
                    );
        }
    }

    if( defined( 'NV_EDITOR' ) )
    {
        require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
    }

    if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
    {
        $array['description'] = nv_aleditor( 'description', '100%', '300px', $array['description'] );
    }
    else
    {
        $array['description'] = "<textarea style=\"width:100%; height:300px\" name=\"description\" id=\"description\">" . $array['description'] . "</textarea>";
    }

    $sql = "SELECT `config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config` WHERE `config_name`='upload_dir'";
    $result = $db->sql_query( $sql );
    list( $upload_dir ) = $db->sql_fetchrow( $result );

    if( ! $array['filesize'] ) $array['filesize'] = "";

    $xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

    $report = $report ? "&amp;report=1" : "";
    $xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . $id . $report );

    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'DATA', $array );
    $xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
    $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
    $xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/images' );
    $xtpl->assign( 'FILES_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/' . $upload_dir );

    if( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', $error );
        $xtpl->parse( 'main.error' );
    }

    foreach( $listcats as $cat )
    {
        $xtpl->assign( 'LISTCATS', $cat );
        $xtpl->parse( 'main.catid' );
    }

    $a = 0;
    foreach( $array['fileupload'] as $file )
    {
        $xtpl->assign( 'FILEUPLOAD', array( 'value' => $file, 'key' => $a ) );
        $xtpl->parse( 'main.fileupload' );
        ++$a;
    }

    $a = 0;
    foreach( $array['linkdirect'] as $link )
    {
        $xtpl->assign( 'LINKDIRECT', array( 'value' => $link, 'key' => $a ) );
        $xtpl->parse( 'main.linkdirect' );
        ++$a;
    }

    foreach( $array['who_comment'] as $who )
    {
        $xtpl->assign( 'WHO_COMMENT', $who );
        $xtpl->parse( 'main.who_comment' );
    }

    if( ! empty( $array['groups_comment'] ) )
    {
        foreach( $array['groups_comment'] as $group )
        {
            $xtpl->assign( 'GROUPS_COMMENT', $group );
            $xtpl->parse( 'main.group_empty.groups_comment' );
        }
        $xtpl->parse( 'main.group_empty' );
    }

    foreach( $array['who_view'] as $who )
    {
        $xtpl->assign( 'WHO_VIEW', $who );
        $xtpl->parse( 'main.who_view' );
    }

    if( ! empty( $array['groups_view'] ) )
    {
        foreach( $array['groups_view'] as $group )
        {
            $xtpl->assign( 'GROUPS_VIEW', $group );
            $xtpl->parse( 'main.group_empty_view.groups_view' );
        }
        $xtpl->parse( 'main.group_empty_view' );
    }

    foreach( $array['who_download'] as $who )
    {
        $xtpl->assign( 'WHO_DOWNLOAD', $who );
        $xtpl->parse( 'main.who_download' );
    }

    if( ! empty( $array['groups_download'] ) )
    {
        foreach( $array['groups_download'] as $group )
        {
            $xtpl->assign( 'GROUPS_DOWNLOAD', $group );
            $xtpl->parse( 'main.group_empty_download.groups_download' );
        }
        $xtpl->parse( 'main.group_empty_download' );
    }

    $xtpl->parse( 'main.is_del_report' );

    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

// Avtive - Deactive
if( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $id = $nv_Request->get_int( 'id', 'post', 0 );

    if( empty( $id ) ) die( "NO" );

    $query = "SELECT `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if( $numrows != 1 ) die( 'NO' );

    list( $status ) = $db->sql_fetchrow( $result );
    $status = $status ? 0 : 1;

    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `status`=" . $status . " WHERE `id`=" . $id;
    $db->sql_query( $sql );
    die( "OK" );
}

// Delete file
if( $nv_Request->isset_request( 'del', 'post' ) )
{
    if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $id = $nv_Request->get_int( 'id', 'post', 0 );

    if( ! $id ) die( "NO" );

    $query = "SELECT `fileupload`, `fileimage`,`title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if( $numrows != 1 ) die( "NO" );

    $row = $db->sql_fetchrow( $result );

    //Khong xao file vi co the co truong hop file dung chung
    /*
    if(!empty($fileupload))
    {
    $fileupload = explode("[NV]",$fileupload);
    
    foreach($fileupload as $file)
    {
    $file = substr($file,strlen(NV_BASE_SITEURL));
    if ( ! empty( $file ) and file_exists( NV_ROOTDIR . '/' . $file ) )
    {
    @nv_deletefile( NV_ROOTDIR . '/' . $file );
    }
    }
    }

    $fileimage = substr($array['fileimage'],strlen(NV_BASE_SITEURL));
    if ( ! empty( $fileimage ) and file_exists( NV_ROOTDIR . '/' . $fileimage ) )
    {
    @nv_deletefile( NV_ROOTDIR . '/' . $fileimage );
    }*/

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `fid`=" . $id;
    $db->sql_query( $sql );

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_report` WHERE `fid`=" . $id;
    $db->sql_query( $sql );

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $db->sql_query( $sql );
    nv_insert_logs( NV_LANG_DATA, $module_data, $lang_module['download_filequeue_del'], $row['title'], $admin_info['userid'] );
    die( "OK" );
}

// List file
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "`";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;

$listcats = nv_listcats( 0 );
if( empty( $listcats ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1" );
    exit();
}

if( $nv_Request->isset_request( "catid", "get" ) )
{
    $catid = $nv_Request->get_int( 'catid', 'get', 0 );
    if( ! $catid or ! isset( $listcats[$catid] ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }

    $page_title = sprintf( $lang_module['file_list_by_cat'], $listcats[$catid]['title'] );
    $sql .= " WHERE `catid`=" . $catid;
    $base_url .= "&amp;catid=" . $catid;
}
else
{
    $page_title = $lang_module['download_filemanager'];
}

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

if( ! $all_page )
{
    if( $catid )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
    else
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add" );
        exit();
    }
}

$sql .= " ORDER BY `uploadtime` DESC";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;

$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->sql_query( $sql2 );

$array = array();

while( $row = $db->sql_fetchrow( $query2 ) )
{
    $array[$row['id']] = array(
        'id' => ( int )$row['id'], //
        'title' => $row['title'], //
        'cattitle' => $listcats[$row['catid']]['title'], //
        'catlink' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;catid=" . $row['catid'], //
        'uploadtime' => nv_date( "d/m/Y H:i", $row['uploadtime'] ), //
        'status' => $row['status'] ? " checked=\"checked\"" : "", //
        'view_hits' => ( int )$row['view_hits'], //
        'download_hits' => ( int )$row['download_hits'], //
        'comment_hits' => ( int )$row['comment_hits'] //
            );
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'ADD_NEW_FILE', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add" );

if( ! empty( $array ) )
{
    $a = 0;
    foreach( $array as $row )
    {
        $xtpl->assign( 'CLASS', $a % 2 == 1 ? " class=\"second\"" : "" );
        $xtpl->assign( 'ROW', $row );
        $xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . $row['id'] );
        $xtpl->parse( 'main.row' );
        ++$a;
    }
}

if( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>