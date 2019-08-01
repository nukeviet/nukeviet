/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

$(document).ready(function(){
    // Config logo
    $("input[name=selectimg]").click(function() {
        var area = "upload_logo";
        var path = "";
        var currentpath = "images";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
    // Thumbconfig
    $('[data-toggle="thumbCfgViewEx"]').click(function(e) {
        e.preventDefault();
        
        if (typeof $(this).data('busy') == "undefined" || !$(this).data('busy')) {
            var $this = $(this);
            var ctn = $this.parent().parent();
            var did = $this.data('did') != -1 ? $this.data('did') : $('[name="other_dir"]', ctn).val(),
                thumbType = $('[name="other_type"]', ctn).length ? $('[name="other_type"]', ctn).val() : $('[name="thumb_type[' + did + ']"]', ctn).val(),
                thumbW = $('[name="other_thumb_width"]', ctn).length ? $('[name="other_thumb_width"]', ctn).val() : $('[name="thumb_width[' + did + ']"]', ctn).val(),
                thumbH = $('[name="other_thumb_height"]', ctn).length ? $('[name="other_thumb_height"]', ctn).val() : $('[name="thumb_height[' + did + ']"]', ctn).val(),
                thumbQuality = $('[name="other_thumb_quality"]', ctn).length ? $('[name="other_thumb_quality"]', ctn).val() : $('[name="thumb_quality[' + did + ']"]', ctn).val();

            if ((!did && $this.data('did') == -1) || thumbType == 0 || !thumbW || !thumbH || !thumbQuality || thumbW == 0 || thumbH == 0 || thumbQuality == 0) {
                alert($this.data('errmsg'));
                return false;
            }
            
            $this.data('busy', true);
            $this.find('i').removeClass('fa-search');
            $this.find('i').addClass('fa-cog');
            $this.find('i').addClass('fa-spin');
            
        	$.post(
                script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=thumbconfig&nocache=' + new Date().getTime(),
                'getexample=1&did=' + did + '&t=' + thumbType + '&w=' + thumbW + '&h=' + thumbH + '&q=' + thumbQuality, 
                function(res) {
                    $this.data('busy', false);
                    $this.find('i').removeClass('fa-cog');
                    $this.find('i').removeClass('fa-spin');
                    $this.find('i').addClass('fa-search');
                    if (res.status != 'success') {
                        $('#thumbprewiew').html(res.message);
                        return false;
                    }
                    $('#thumbprewiewtmp .imgorg').attr('src', res.src);
                    $('#thumbprewiewtmp .imgthumb').attr('src', res.thumbsrc);
                    $('#thumbprewiew').html($('#thumbprewiewtmp').html());
                    $('html, body').animate({scrollTop: $('#thumbprewiew').offset().top - 10}, 'slow');
                }
            );
        }
    });
});