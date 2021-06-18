/* *
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

function showNvModal(content, css){
	if( css == undefined ){
		css = 'error';
	}
	
    if( $('.nv-modal').length ){
    	$('.nv-modal:first>.nv-modal-wrap>.nv-modal-content').html('<div class="' + css + '">' + content + '</div>');
    	$('.nv-modal:first').show().fadeTo(200, 1);
    }
}

function checkDbDriver(){
	var $this = $('[name="dbtype"]');
	$('#dbtype-check').removeClass('hide');
	
	$.ajax({
		type: 'POST',
		cache: false,
		url: $this.data('url') + '&nocache=' + new Date().getTime(),
		data: 'checkdbtype=' + $this.val(),
		dataType: 'json',
		success: function(e){
			$('#dbtype-check').addClass('hide');
			
			if( e.status != 'success' ){
				var ct = "", len = 0, o;
				for (o in e.files) {
				  len++;
				}
				0 < len ? (ct += '<p><a href="' + e.link + '" target="_blank">' + e.message + "</a></p>", ct += "<ul>", $.each(e.files, function(b, a) {
				  ct += "<li>" + a + "</li>";
				}), ct += "</ul>") : ct += "<p>" + e.message + "</p>";
				showNvModal(ct);
				$this.find("option").prop("selected", !1);
			}
		}
	});
}

$(document).ready(function(){
	// Language control
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
    
    // Check db driver
    if( $('[name="dbtype"]').length ){
	    checkDbDriver();
	    $('[name="dbtype"]').change(function(){
	    	checkDbDriver();
	    });
    }
    
    // Init NV Simple Moal
    if( $('.nv-modal').length ){
    	$('.nv-modal:first>.nv-modal-wrap').click(function(e){
    		if( e.target.className == 'nv-modal-wrap' ){
		    	$('.nv-modal:first').fadeTo(200, 0, function(){
		    		$('.nv-modal:first').hide();
		    		$('.nv-modal:first>.nv-modal-wrap>.nv-modal-content').html('');
		    	});
    		}
    	});
    }
});