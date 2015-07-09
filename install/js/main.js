/* *
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

$(document).ready(function(){
    $('span.language_head').click(function(){
        $('ul.language_body').slideToggle('medium');
    });
    $('ul.language_body li a').mouseover(function(){
        $(this).animate({
            fontSize: "12px",
            paddingLeft: "10px"
        }, 50);
    });
    $('ul.language_body li a').mouseout(function(){
        $(this).animate({
            fontSize: "12px",
            paddingLeft: "10px"
        }, 50);
    });
});