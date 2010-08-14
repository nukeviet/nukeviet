<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

/**
 * theme_main_download()
 * 
 * @param mixed $array
 * @param mixed $download_config
 * @param mixed $subs
 * @param mixed $page_title
 * @param mixed $generate_page
 * @return
 */
function theme_main_download( $array, $download_config, $subs, $page_title, $generate_page )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name;

    $xtpl = new XTemplate( "main_page.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_name . "/" );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'PAGE_TITLE', $page_title );
    $xtpl->assign( 'IMG_FOLDER', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/download/" );

    if ( $download_config['is_addfile_allow'] )
    {
        $xtpl->assign( 'UPLOAD', NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=upload" );
        $xtpl->parse( 'main.is_addfile_allow' );
    }

    if ( ! empty( $subs ) )
    {
        foreach ( $subs as $cat )
        {
            $xtpl->assign( 'SUBCAT', $cat );

            if ( ! empty( $cat['description'] ) )
            {
                $xtpl->parse( 'main.subcats.li.description' );
            }

            $xtpl->parse( 'main.subcats.li' );
        }
        $xtpl->parse( 'main.subcats' );
    }

    if ( ! empty( $array ) )
    {
        foreach ( $array as $row )
        {
            $xtpl->assign( 'ROW', $row );

            if ( ! empty( $row['author_name'] ) )
            {
                $xtpl->parse( 'main.row.author_name' );
            }

            if ( ! empty( $row['fileimage']['src'] ) )
            {
                $xtpl->assign( 'FILEIMAGE', $row['fileimage'] );
                $xtpl->parse( 'main.row.is_image' );
            }

            if ( defined( 'NV_IS_MODADMIN' ) )
            {
                $xtpl->parse( 'main.row.is_admin' );
            }

            if ( isset( $row['comment_hits'] ) )
            {
                $xtpl->parse( 'main.row.comment_allow' );
            }

            $xtpl->parse( 'main.row' );
        }
    }

    if ( ! empty( $generate_page ) )
    {
        $xtpl->assign( 'GENERATE_PAGE', $generate_page );
        $xtpl->parse( 'main.generate_page' );
    }

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * view_file()
 * 
 * @param mixed $row
 * @param mixed $download_config
 * @param mixed $page_title
 * @return
 */
function view_file( $row, $download_config, $page_title )
{
    global $global_config, $lang_global, $lang_module, $module_name, $module_info, $my_head;

    if ( ! defined( 'SHADOWBOX' ) and isset( $row['fileimage']['src'] ) and ! empty( $row['fileimage']['src'] ) )
    {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
        $my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
        $my_head .= "<script type=\"text/javascript\">\n";
        $my_head .= "Shadowbox.init();\n";
        $my_head .= "</script>\n";

        define( 'SHADOWBOX', true );
    }

    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/star-rating/jquery.rating.pack.js\"></script>\n";
    $my_head .= "<script src=\"" . NV_BASE_SITEURL . "js/star-rating/jquery.MetaData.js\" type=\"text/javascript\"></script>\n";
    $my_head .= "<link href=\"" . NV_BASE_SITEURL . "js/star-rating/jquery.rating.css\" type=\"text/css\" rel=\"stylesheet\" />\n";
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\">\n";
    $my_head .= "$(document).ready(function(){
            $('#commentForm').validate({
                submitHandler: function() { nv_send_comment(); },
                rules: {
                    comment_uname: {
                    required: true,
                    rangelength: [3, 60]
                    },
                    
                    comment_uemail: {
                    required: true,
                    email: true
                    },
                    
                    comment_subject: {
                    required: true,
                    rangelength: [3, 200]
                    },
                    
                    comment_content: {
                    required: true,
                    rangelength: [3, 1000]
                    },
                    
                    comment_seccode: {
                    required: true,
                    minlength: " . NV_GFX_NUM . "
                    }
                }
			});
          });";
    $my_head .= "  </script>\n";

    $xtpl = new XTemplate( "viewfile.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_name . "/" );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'PAGE_TITLE', $page_title );
    $xtpl->assign( 'ROW', $row );

    if ( $download_config['is_addfile_allow'] )
    {
        $xtpl->assign( 'UPLOAD', NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=upload" );
        $xtpl->parse( 'main.is_addfile_allow' );
    }

    if ( isset( $row['fileimage']['src'] ) and ! empty( $row['fileimage']['src'] ) )
    {
        $xtpl->assign( 'FILEIMAGE', $row['fileimage'] );
        $xtpl->parse( 'main.is_image' );
    }

    if ( ! empty( $row['description'] ) )
    {
        $xtpl->parse( 'main.introtext' );
    }

    if ( ! empty( $row['comment_allow'] ) )
    {
        $xtpl->parse( 'main.comment_allow' );
    }

    if ( $row['is_download_allow'] )
    {
        $xtpl->parse( 'main.report' );

        if ( ! empty( $row['fileupload'] ) )
        {
            $xtpl->assign( 'SITE_NAME', $global_config['site_name'] );

            $a = 0;
            foreach ( $row['fileupload'] as $fileupload )
            {
                $fileupload['key'] = $a;
                $xtpl->assign( 'FILEUPLOAD', $fileupload );
                $xtpl->parse( 'main.download_allow.fileupload.row' );
                $a++;
            }

            $xtpl->parse( 'main.download_allow.fileupload' );
        }

        if ( ! empty( $row['linkdirect'] ) )
        {
            foreach ( $row['linkdirect'] as $host => $linkdirect )
            {
                $xtpl->assign( 'HOST', $host );

                foreach ( $linkdirect as $link )
                {
                    $xtpl->assign( 'LINKDIRECT', $link );
                    $xtpl->parse( 'main.download_allow.linkdirect.row' );
                }

                $xtpl->parse( 'main.download_allow.linkdirect' );
            }
        }

        $xtpl->parse( 'main.download_allow' );
    }
    else
    {
        $xtpl->parse( 'main.download_not_allow' );
    }

    if ( $row['rating_disabled'] )
    {
        $xtpl->parse( 'main.disablerating' );
    }

    if ( defined( 'NV_IS_MODADMIN' ) )
    {
        $xtpl->parse( 'main.is_admin' );
    }

    if ( $row['comment_allow'] )
    {
        if ( $row['is_comment_allow'] )
        {
            $xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getcomment" );
            $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
            $xtpl->assign( 'CAPTCHA_MAXLENGTH', NV_GFX_NUM );

            $xtpl->parse( 'main.comment_allow2.is_comment_allow' );
        }
        $xtpl->parse( 'main.comment_allow2' );
    }

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * show_comment()
 * 
 * @param mixed $array
 * @param mixed $generate_page
 * @return
 */
function show_comment( $array, $generate_page )
{
    global $module_info, $module_name, $lang_module, $lang_global;

    $xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_name . "/" );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
    if ( ! empty( $array ) )
    {
        foreach ( $array as $row )
        {
            $xtpl->assign( 'ROW', $row );

            if ( defined( 'NV_IS_MODADMIN' ) )
            {
                $xtpl->parse( 'main.if_not_empty.detail.is_admin' );
            }
            $xtpl->parse( 'main.if_not_empty.detail' );
        }

        if ( ! empty( $generate_page ) )
        {
            $xtpl->assign( 'GENERATE_PAGE', $generate_page );
            $xtpl->parse( 'main.if_not_empty.generate_page' );
        }

        $xtpl->parse( 'main.if_not_empty' );

    }

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * theme_upload()
 * 
 * @param mixed $array
 * @param mixed $list_cats
 * @param mixed $download_config
 * @param mixed $error
 * @return
 */
function theme_upload( $array, $list_cats, $download_config, $error )
{
    global $module_info, $module_name, $lang_module, $lang_global, $my_head;

    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\">\n";
    $my_head .= "$(document).ready(function(){
            $('#uploadForm').validate({
                rules: {
                    upload_title: {
                    required: true,
                    rangelength: [3, 255]
                    },
                    
                    upload_author_name: {
                    rangelength: [3, 100]
                    },
                    
                    upload_author_email: {
                    email: true
                    },
                    
                    upload_author_url: {
                    url: true
                    },
                    
                    upload_fileupload: {
                    accept: '" . implode( "|", $download_config['upload_filetype'] ) . "'
                    },
                    
                    upload_filesize: {
                    number: true
                    },
                    
                    upload_fileimage: {
                    accept: 'jpg|gif|png'
                    },
                    
                    upload_introtext: {
                    maxlength: 500
                    },
                    
                    upload_description: {
                    maxlength: 5000
                    },
                    
                    upload_user_name: {
                    required: true,
                    rangelength: [3, 60]
                    },
                    
                    upload_seccode: {
                    required: true,
                    minlength: 6
                    }
                }
			});
          });";
    $my_head .= "  </script>\n";

    $xtpl = new XTemplate( "upload.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_name . "/" );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'DOWNLOAD', NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name );
    $xtpl->assign( 'UPLOAD', $array );
    $xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=upload" );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'CAPTCHA_MAXLENGTH', NV_GFX_NUM );
    $xtpl->assign( 'EXT_ALLOWED', implode( ", ", $download_config['upload_filetype'] ) );

    if ( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', $error );
        $xtpl->parse( 'main.is_error' );
    }

    foreach ( $list_cats as $cat )
    {
        $cat['selected'] = $array['catid'] == $cat['id'] ? " selected=\"selected\"" : "";
        $xtpl->assign( 'LISTCATS', $cat );
        $xtpl->parse( 'main.catid' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

?>