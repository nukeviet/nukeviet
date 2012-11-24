<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

//Edit - accept file
if( $nv_Request->isset_request( 'edit', 'get' ) )
{
    $page_title = $lang_module['download_filequeue'];

    $id = $nv_Request->get_int( 'id', 'get', 0 );

    if( ! $id )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=filequeue" );
        exit();
    }

    $query = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if( $numrows != 1 )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=filequeue" );
        exit();
    }

    $row = $db->sql_fetchrow( $result );

    $groups_list = nv_groups_list();
    $array_who = array(
        $lang_global['who_view0'],
        $lang_global['who_view1'],
        $lang_global['who_view2'] );
    if( ! empty( $groups_list ) )
    {
        $array_who[] = $lang_global['who_view3'];
    }

    $sql = "SELECT `config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config` WHERE `config_name`='upload_dir'";
    $result = $db->sql_query( $sql );
    list( $upload_dir ) = $db->sql_fetchrow( $result );

    $array = array();
    $is_error = false;
    $error = "";

    if( $nv_Request->isset_request( 'submit', 'post' ) )
    {
        $array['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
        $array['title'] = filter_text_input( 'title', 'post', '', 1 );
        $array['description'] = nv_editor_filter_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
        $array['introtext'] = filter_text_textarea( 'introtext', '', NV_ALLOWED_HTML_TAGS );
        $array['user_name'] = filter_text_input( 'user_name', 'post', '', 1 );
        $array['author_name'] = filter_text_input( 'author_name', 'post', '', 1 );
        $array['author_email'] = filter_text_input( 'author_email', 'post', '' );
        $array['author_url'] = filter_text_input( 'author_url', 'post', '' );
        $array['fileupload'] = ! empty( $row['fileupload'] ) ? explode( "[NV]", $row['fileupload'] ) : array();

        // Lay duong dan day du file tai len cu
        if( ! empty( $array['fileupload'] ) )
        {
            $fileupload = $array['fileupload'];
            $array['fileupload'] = array();
            foreach( $fileupload as $file )
            {
                if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $file ) )
                {
                    $file = NV_BASE_SITEURL . NV_UPLOADS_DIR . $file;
                }
                $array['fileupload'][] = $file;
            }
        }

        $array['fileupload2'] = $nv_Request->get_typed_array( 'fileupload2', 'post', 'string' );
        $array['linkdirect'] = $nv_Request->get_typed_array( 'linkdirect', 'post', 'string' );
        $array['version'] = filter_text_input( 'version', 'post', '', 1 );
        $array['filesize'] = $nv_Request->get_int( 'filesize', 'post', 0 );
        $array['fileimage'] = $row['fileimage'];

        // Lay duong dan day du hinh cu
        if( ! empty( $array['fileimage'] ) )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['fileimage'] ) )
            {
                $array['fileimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['fileimage'];
            }
        }

        $array['fileimage2'] = filter_text_input( 'fileimage2', 'post', '' );
        $array['copyright'] = filter_text_input( 'copyright', 'post', '', 1 );
        $array['comment_allow'] = $nv_Request->get_int( 'comment_allow', 'post', 0 );
        $array['who_comment'] = $nv_Request->get_int( 'who_comment', 'post', 0 );
        $array['groups_comment'] = $nv_Request->get_typed_array( 'groups_comment', 'post', 'int' );

        $array['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
        $array['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );
        $array['who_download'] = $nv_Request->get_int( 'who_download', 'post', 0 );
        $array['groups_download'] = $nv_Request->get_typed_array( 'groups_download', 'post', 'int' );
        // Sort image
        if( ! empty( $array['fileimage'] ) )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['fileimage'] ) )
            {
                $array['fileimage'] = substr( $array['fileimage'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
            }
        }
        if( ! empty( $array['fileimage2'] ) )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['fileimage2'] ) )
            {
                $array['fileimage2'] = substr( $array['fileimage2'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
            }
        }

        if( ! empty( $array['author_url'] ) )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['author_url'] ) )
            {
                $array['author_url'] = "http://" . $array['author_url'];
            }
        }

        // Cat ngan duong dan file tai len moi
        if( ! empty( $array['fileupload2'] ) )
        {
            $fileupload2 = $array['fileupload2'];
            $array['fileupload2'] = array();
            $array['filesize'] = 0;
            foreach( $fileupload2 as $file )
            {
                if( ! empty( $file ) )
                {
                    $file2 = substr( $file, strlen( NV_BASE_SITEURL ) );
                    if( file_exists( NV_ROOTDIR . '/' . $file2 ) and ( $filesize = filesize( NV_ROOTDIR . '/' . $file2 ) ) != 0 )
                    {
                        $array['fileupload2'][] = substr( $file, strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
                        $array['filesize'] += $filesize;
                    }
                }
            }
        }
        else
        {
            $array['fileupload2'] = array();
        }

        // Cat ngan duong dan file tai len cu
        if( ! empty( $array['fileupload'] ) )
        {
            $fileupload = $array['fileupload'];
            $array['fileupload'] = array();
            $array['filesize'] = 0;
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

        if( ! empty( $array['linkdirect'] ) )
        {
            $linkdirect = $array['linkdirect'];
            $array['linkdirect'] = array();
            foreach( $linkdirect as $links )
            {
                $linkdirect = array();
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
                            $linkdirect[] = $link;
                        }
                    }
                }

                if( ! empty( $linkdirect ) )
                {
                    $array['linkdirect'][] = implode( "\n", $linkdirect );
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

        $sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `title`=" . $db->dbescape( $array['title'] );
        $result = $db->sql_query( $sql );
        list( $is_exists ) = $db->sql_fetchrow( $result );

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
        elseif( empty( $array['fileupload'] ) and empty( $array['linkdirect'] ) and empty( $array['fileupload2'] ) )
        {
            $is_error = true;
            $error = $lang_module['file_error_fileupload'];
        }
        else
        {
            $alias = change_alias( $array['title'] );
            $array['description'] = nv_editor_nl2br( $array['description'] );
            $array['introtext'] = nv_nl2br( $array['introtext'], "<br />" );

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

            if( $row['user_id'] )
            {
                $array['user_name'] = $row['user_name'];
            }

            if( ! empty( $array['fileupload2'] ) )
            {
                $array['fileupload'] = $array['fileupload2'];
            }
            elseif( ! empty( $array['fileupload'] ) )
            {
                $fileupload = $array['fileupload'];
                $array['fileupload'] = array();
                foreach( $fileupload as $file )
                {
                    $file = NV_UPLOADS_DIR . $file;
                    $newfile = basename( $file );

                    if( preg_match( "/(.*)(\.[a-zA-Z0-9]{32})(\.[a-zA-Z]+)$/", $newfile, $m ) )
                    {
                        $newfile = $m[1] . $m[3];
                    }

                    $newfile2 = $newfile;
                    $i = 1;
                    while( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $upload_dir . '/' . $newfile2 ) )
                    {
                        $newfile2 = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $newfile );
                        ++$i;
                    }

                    if( @nv_copyfile( NV_ROOTDIR . '/' . $file, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $upload_dir . '/' . $newfile2 ) )
                    {
                        $array['fileupload'][] = '/' . $module_name . '/' . $upload_dir . '/' . $newfile2;
                    }
                }
            }

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

            if( ! empty( $array['fileimage2'] ) )
            {
                $array['fileimage'] = $array['fileimage2'];
            }
            elseif( ! empty( $array['fileimage'] ) )
            {
                $fileimage = NV_UPLOADS_DIR . $array['fileimage'];
                $array['fileimage'] = "";
                if( file_exists( NV_ROOTDIR . '/' . $fileimage ) )
                {
                    $newfile = basename( $fileimage );

                    if( preg_match( "/(.*)(\.[a-zA-Z0-9]{32})(\.[a-zA-Z]+)$/", $newfile, $m ) )
                    {
                        $newfile = $m[1] . $m[3];
                    }

                    $newfile2 = $newfile;
                    $i = 1;
                    while( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/images/' . $newfile2 ) )
                    {
                        $newfile2 = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $newfile );
                        ++$i;
                    }

                    if( @nv_copyfile( NV_ROOTDIR . '/' . $fileimage, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/images/' . $newfile2 ) )
                    {
                        $array['fileimage'] = '/' . $module_name . '/images/' . $newfile2;
                    }
                }
            }

            $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` VALUES (
                NULL, 
                " . $array['catid'] . ", 
                " . $db->dbescape( $array['title'] ) . ", 
                " . $db->dbescape( $alias ) . ", 
                " . $db->dbescape( $array['description'] ) . ", 
                " . $db->dbescape( $array['introtext'] ) . ", 
                " . $row['uploadtime'] . ", 
                " . NV_CURRENTTIME . ", 
                " . $row['user_id'] . ", 
                " . $db->dbescape( $array['user_name'] ) . ", 
                " . $db->dbescape( $array['author_name'] ) . ", 
                " . $db->dbescape( $array['author_email'] ) . ", 
                " . $db->dbescape( $array['author_url'] ) . ", 
                " . $db->dbescape( $array['fileupload'] ) . ", 
                " . $db->dbescape( $array['linkdirect'] ) . ", 
                " . $db->dbescape( $array['version'] ) . ", 
                " . $array['filesize'] . ", 
                " . $db->dbescape( $array['fileimage'] ) . ", 
                1, 
                " . $db->dbescape( $array['copyright'] ) . ", 
                0, 0, 
                " . $array['comment_allow'] . ", 
                " . $array['who_comment'] . ", 
                " . $db->dbescape( $array['groups_comment'] ) . ", 
                " . $array['who_view'] . ", 
                " . $db->dbescape( $array['groups_view'] ) . ", 
                " . $array['who_download'] . ", 
                " . $db->dbescape( $array['groups_download'] ) . ", 
                0, '')";

            if( ! $db->sql_query_insert_id( $sql ) )
            {
                $is_error = true;
                $error = $lang_module['file_error2'];
            }
            else
            {
                // Neu khong co file tai len moi moi xoa
                if( ! empty( $row['fileupload'] ) )
                {
                    $row['fileupload'] = explode( "[NV]", $row['fileupload'] );

                    foreach( $row['fileupload'] as $fileupload )
                    {
                        $fileupload = NV_UPLOADS_DIR . $fileupload;
                        if( file_exists( NV_ROOTDIR . '/' . $fileupload ) )
                        {
                            @nv_deletefile( NV_ROOTDIR . '/' . $fileupload );
                        }
                    }
                }

                if( ! empty( $row['fileimage'] ) )
                {
                    $fileimage = NV_UPLOADS_DIR . $row['fileimage'];
                    if( file_exists( NV_ROOTDIR . '/' . $fileimage ) )
                    {
                        @nv_deletefile( NV_ROOTDIR . '/' . $fileimage );
                    }
                }

                $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE `id`=" . $id;
                $db->sql_query( $sql );

                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=filequeue" );
                exit();
            }
        }

    }
    else
    {
        $array['catid'] = ( int )$row['catid'];
        $array['title'] = $row['title'];
        $array['description'] = nv_editor_br2nl( $row['description'] );
        $array['introtext'] = nv_br2nl( $row['introtext'] );
        $array['user_name'] = $row['user_name'];
        $array['author_name'] = $row['author_name'];
        $array['author_email'] = $row['author_email'];
        $array['author_url'] = $row['author_url'];
        $array['fileupload'] = $row['fileupload'];
        $array['fileupload2'] = array();
        $array['linkdirect'] = $row['linkdirect'];
        $array['version'] = $row['version'];
        $array['filesize'] = ( int )$row['filesize'];
        $array['fileimage'] = $row['fileimage'];
        $array['fileimage2'] = "";
        $array['copyright'] = $row['copyright'];
        $array['comment_allow'] = 1;
        $array['who_comment'] = $array['who_view'] = $array['who_download'] = 0;
        $array['groups_comment'] = $array['groups_view'] = $array['groups_download'] = array();

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
    }

    $array['id'] = ( int )$row['id'];

    if( ! empty( $array['description'] ) ) $array['description'] = nv_htmlspecialchars( $array['description'] );
    if( ! empty( $array['introtext'] ) ) $array['introtext'] = nv_htmlspecialchars( $array['introtext'] );

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

    if( ! empty( $array['fileupload2'] ) )
    {
        $fileupload2 = $array['fileupload2'];
        $array['fileupload2'] = array();
        foreach( $fileupload2 as $tmp )
        {
            if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $tmp ) )
            {
                $tmp = NV_BASE_SITEURL . NV_UPLOADS_DIR . $tmp;
            }
            $array['fileupload2'][] = $tmp;
        }
    }

    if( ! sizeof( $array['fileupload2'] ) ) array_push( $array['fileupload2'], "" );
    if( ! sizeof( $array['linkdirect'] ) ) array_push( $array['linkdirect'], "" );

    $array['fileupload2_num'] = sizeof( $array['fileupload2'] );
    $array['linkdirect_num'] = sizeof( $array['linkdirect'] );

    // Build fileimage
    if( ! empty( $array['fileimage'] ) )
    {
        if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['fileimage'] ) )
        {
            $array['fileimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['fileimage'];
        }
    }
    if( ! empty( $array['fileimage2'] ) )
    {
        if( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['fileimage2'] ) )
        {
            $array['fileimage2'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['fileimage2'];
        }
    }

    $listcats = nv_listcats( $array['catid'] );
    if( empty( $listcats ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1" );
        exit();
    }

    $array['comment_allow'] = $array['comment_allow'] ? " checked=\"checked\"" : "";

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

    if( ! $array['filesize'] ) $array['filesize'] = "";

    $xtpl = new XTemplate( "filequeue_edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
    $xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;id=" . $id );
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

    if( ! empty( $array['fileupload'] ) )
    {
        $a = 0;
        foreach( $array['fileupload'] as $file )
        {
            $xtpl->assign( 'FILEUPLOAD', array( 'value' => $file, 'key' => $a ) );
            $xtpl->parse( 'main.fileupload' );
            ++$a;
        }

        $xtpl->parse( 'main.if_fileupload' );
    }

    $a = 0;
    foreach( $array['fileupload2'] as $file )
    {
        $xtpl->assign( 'FILEUPLOAD2', array( 'value' => $file, 'key' => $a ) );
        $xtpl->parse( 'main.fileupload2' );
        ++$a;
    }

    $a = 0;
    foreach( $array['linkdirect'] as $link )
    {
        $xtpl->assign( 'LINKDIRECT', array( 'value' => $link, 'key' => $a ) );
        $xtpl->parse( 'main.linkdirect' );
        ++$a;
    }

    if( ! empty( $array['fileimage'] ) )
    {
        $xtpl->parse( 'main.fileimage.if_fileimage' );
        $xtpl->parse( 'main.fileimage' );
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

    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit;
}

//Delete file
if( $nv_Request->isset_request( 'del', 'post' ) )
{
    if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $id = $nv_Request->get_int( 'id', 'post', 0 );

    if( ! $id )
    {
        die( "NO" );
    }

    $query = "SELECT `fileupload`, `fileimage` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if( $numrows != 1 )
    {
        die( "NO" );
    }

    list( $fileupload, $fileimage ) = $db->sql_fetchrow( $result );

    if( ! empty( $fileupload ) )
    {
        $fileupload = explode( "[NV]", $fileupload );
        foreach( $fileupload as $file )
        {
            $file = NV_UPLOADS_DIR . $file;
            if( file_exists( NV_ROOTDIR . '/' . $file ) )
            {
                @nv_deletefile( NV_ROOTDIR . '/' . $file );
            }
        }
    }

    if( ! empty( $fileimage ) )
    {
        $fileimage = NV_UPLOADS_DIR . $fileimage;
        if( file_exists( NV_ROOTDIR . '/' . $fileimage ) )
        {
            @nv_deletefile( NV_ROOTDIR . '/' . $fileimage );
        }
    }

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE `id`=" . $id;
    $db->sql_query( $sql );

    die( "OK" );
}

//All del
if( $nv_Request->isset_request( 'alldel', 'post' ) )
{
    if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $query = "SELECT `fileupload`, `fileimage` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp`";
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if( ! $numrows )
    {
        die( "NO" );
    }

    while( list( $fileupload, $fileimage ) = $db->sql_fetchrow( $result ) )
    {
        if( ! empty( $fileupload ) )
        {
            $fileupload = explode( "[NV]", $fileupload );
            foreach( $fileupload as $file )
            {
                $file = NV_UPLOADS_DIR . $file;
                if( file_exists( NV_ROOTDIR . '/' . $file ) )
                {
                    @nv_deletefile( NV_ROOTDIR . '/' . $file );
                }
            }
        }

        if( ! empty( $fileimage ) )
        {
            $fileimage = NV_UPLOADS_DIR . $fileimage;
            if( file_exists( NV_ROOTDIR . '/' . $fileimage ) )
            {
                @nv_deletefile( NV_ROOTDIR . '/' . $fileimage );
            }
        }
    }

    $sql = "TRUNCATE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_tmp`";
    $db->sql_query( $sql );

    die( "OK" );
}

//List files
$page_title = $lang_module['download_filequeue'];

$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp`";

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_file ) = $db->sql_fetchrow( $result1 );

if( ! $all_file )
{
    $contents = "<div style=\"padding-top:15px;text-align:center\">\n";
    $contents .= "<strong>" . $lang_module['filequeue_empty'] . "</strong>";
    $contents .= "</div>\n";
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "\" />";

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit;
}

$listcats = nv_listcats( 0 );
if( empty( $listcats ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1" );
    exit();
}

$sql2 = "SELECT * " . $sql . " ORDER BY `uploadtime` DESC";
$result2 = $db->sql_query( $sql2 );

$array = array();

while( $row = $db->sql_fetchrow( $result2 ) )
{
    $array[$row['id']] = array( //
        'id' => ( int )$row['id'], //
        'title' => $row['title'], //
        'cattitle' => $listcats[$row['catid']]['title'], //
        'catlink' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;catid=" . $row['catid'], //
        'uploadtime' => nv_date( "d/m/Y H:i", $row['uploadtime'] ), //
        );
}

$xtpl = new XTemplate( "filequeue.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $page_title );

if( ! empty( $array ) )
{
    $a = 0;
    foreach( $array as $row )
    {
        $xtpl->assign( 'CLASS', $a % 2 == 1 ? " class=\"second\"" : "" );
        $xtpl->assign( 'ROW', $row );
        $xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=filequeue&amp;edit=1&amp;id=" . $row['id'] );
        $xtpl->parse( 'main.row' );
        ++$a;
    }
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>