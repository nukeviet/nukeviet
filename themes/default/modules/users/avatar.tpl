<!-- BEGIN: main -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="CMS NukeViet" />
        <title>{SITE_NAME}</title>

        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/avatar.css" rel="stylesheet" type="text/css" />
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />

        <script src="{NV_BASE_SITEURL}js/jquery/jquery.min.Jcrop.js" type="text/javascript"></script>
        <script src="{NV_BASE_SITEURL}js/jquery/jquery.Jcrop.min.js" type="text/javascript"></script>
        <script src="{NV_BASE_SITEURL}js/global.js" type="text/javascript"></script>
        <script type="text/javascript">
		/**
		 *
		 * HTML5 Image uploader with Jcrop
		 *
		 * Licensed under the MIT license.
		 * http://www.opensource.org/licenses/mit-license.php
		 * 
		 * Copyright 2012, Script Tutorials
		 * http://www.script-tutorials.com/
		 */
		
		// convert bytes into friendly format
		function bytesToSize(bytes) {
		    var sizes = ['Bytes', 'KB', 'MB'];
		    if (bytes == 0) return 'n/a';
		    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
		};
		
		// check for selected crop region
		function checkForm() {
		    if (parseInt($('#w').val())) return true;
		    $('.error').html('{LANG.avata_upload}').show();
		    return false;
		};
		
		// update info by cropping (onChange and onSelect events handler)
		function updateInfo(e) {
		    $('#x1').val(e.x);
		    $('#y1').val(e.y);
		    $('#x2').val(e.x2);
		    $('#y2').val(e.y2);
		    $('#w').val(e.w);
		    $('#h').val(e.h);
		};
		
		// clear info by cropping (onRelease event handler)
		function clearInfo() {
		    $('.info #w').val('');
		    $('.info #h').val('');
		};
		
		function fileSelectHandler() {
			
		    // get selected file
		    var oFile = $('#image_file')[0].files[0];
		    var avatar_width = '{NV_AVATAR_WIDTH}';
		    var avatar_height = '{NV_AVATAR_HEIGHT}';
		
		    // hide all errors
		    $('.error').hide();
		
		    // check for image type (jpg and png are allowed)
		    var rFilter = /^(image\/jpeg|image\/png)$/i;
		    if (! rFilter.test(oFile.type)) {
		        $('.error').html('{LANG.avata_filetype}').show();
		        return false;
		    }
		
		    // check for file size
		    if (oFile.size > '{NV_UPLOAD_MAX_FILESIZE}') {
		        $('.error').html('{LANG.avata_bigfile}').show();
		        return false;
		    }
		    
		    // preview element
		    var oImage = document.getElementById('preview');
		
		    // prepare HTML5 FileReader
		    var oReader = new FileReader();
		    
		        oReader.onload = function(e) {
				
		        // e.target.result contains the DataURL which we can use as a source of the image
		        oImage.src = e.target.result;
		        
			        oImage.onload = function () { // onload event handler
					
				    if (oImage.naturalWidth > '{NV_MAX_WIDTH}' || oImage.naturalHeight > '{NV_MAX_HEIGHT}') {
				        $('.error').html('{LANG.avata_bigsize}').show();
				        return false;
				    }
				    
				    $('#upload_icon').hide();
		
		            // display step 2
		            $('.step2').fadeIn(500);
		
		            // display some basic image info
		            var sResultFileSize = bytesToSize(oFile.size);
		            $('#filesize').val(sResultFileSize);
		            $('#filetype').val(oFile.type);
		            $('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);
		
		            // Create variables (in this scope) to hold the Jcrop API and image size
		            var jcrop_api, boundx, boundy;
		
		            // destroy Jcrop if it is existed
		            if (typeof jcrop_api != 'undefined') 
		                jcrop_api.destroy();
		
		            // initialize Jcrop
		            $('#preview').Jcrop({
						minSize: [avatar_width, avatar_height],
		                aspectRatio : 1, // keep aspect ratio 1:1
		                bgFade: true, // use fade effect
		                bgOpacity: .3, // fade opacity
		                onChange: updateInfo,
		                onSelect: updateInfo,
		                onRelease: clearInfo
		            }, function(){
		
		                // use the Jcrop API to get the real image size
		                var bounds = this.getBounds();
		                boundx = bounds[0];
		                boundy = bounds[1];
		
		                // Store the Jcrop API in the jcrop_api variable
		                jcrop_api = this;
		            });
		        };
		    };
		
		    // read selected file as DataURL
		    oReader.readAsDataURL(oFile);
		}
        </script>
    </head>
    <body>
        <div class="wraper">
            <div class="bbody">
                <!-- BEGIN: uploadform -->
                <form id="myimages" name="myimages" method="post" enctype="multipart/form-data" action="{NV_AVATAR_UPLOAD}" onsubmit="return checkForm()">
                    <input type="hidden" id="x1" name="x1" />
                    <input type="hidden" id="y1" name="y1" />
                    <input type="hidden" id="x2" name="x2" />
                    <input type="hidden" id="y2" name="y2" />
                    <input type="hidden" id="old_images" name="old_images" />

                    <div><input type="file" name="image_file" id="image_file" onchange="fileSelectHandler()" /></div>

                    <div class="error"></div>
					
					<a href="#" id="upload_icon" title="{LANG.avata_select_img}">
						<img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/users/upload.png" width="200" />
						<h5>{LANG.avata_select_img}</h5>
					</a>
					
                    <div class="step2">
                        <img id="preview" style="max-width: 800px" />
                        
                        <div class="info">
                            <label>{LANG.avata_filesize}</label> <input type="text" id="filesize" name="filesize" readonly />
                            <label>{LANG.avata_ftype}</label> <input type="text" id="filetype" name="filetype" readonly />
                            <label>{LANG.avata_filedimension}</label> <input type="text" id="filedim" name="filedim" readonly />
                            <label>W</label> <input type="text" id="w" name="w" readonly />
                            <label>H</label> <input type="text" id="h" name="h" readonly />
                        </div>
                        <input class="submit" type="submit" value="{LANG.avata_crop}" />
                    </div>
                </form>
                <!-- END: uploadform -->
                
                <!-- BEGIN: uploadsuccess -->
                	<script type="text/javascript">
					$("#avatar", opener.document).val('{FILENAME}');
					window.close();
                	</script>
                <!-- END: uploadsuccess -->
            </div>
        </div>
    </body>
</html>
<script>
//<![CDATA[
var a = opener.document.getElementById("avatar").value;
if( a != '' )
{
	$('#old_images').val(a);
}

$(document).ready(function() {
	$('#upload_icon').click(function() {
    	$('input[type="file"]').click();
	});
});
//]]>
</script>
<!-- END: main -->