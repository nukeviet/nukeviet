/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var UAV = {};
// Default config, replace it with your own
UAV.config = {
    inputFile: 'image_file',
    uploadIcon: 'upload_icon',
    pattern: /^(image\/jpeg|image\/png|image\/webp)$/i,
    maxsize: 2097152,
    img_width: 80,
    img_height: 80,
    target: 'preview',
    uploadInfo: 'uploadInfo',
    uploadGuide: 'guide',
    x: 'crop_x',
    y: 'crop_y',
    w: 'crop_width',
    h: 'crop_height',
    originalDimension: 'original-dimension',
    displayDimension: 'display-dimension',
    imageType: 'image-type',
    imageSize: 'image-size',
    btnSubmit: 'btn-submit',
    btnReset: 'btn-reset',
    uploadForm: 'upload-form'
};
// Default language, replace it with your own
UAV.lang = {
    bigsize: 'File too large',
    smallsize: 'File too small',
    filetype: 'Only accept jmage file tyle',
    bigfile: 'File too big',
    upload: 'Please upload and drag to crop'
};
UAV.data = {
    error: false,
    busy: false,
    cropperApi: null
};
UAV.tool = {
    bytes2Size: function(bytes) {
        var sizes = ['Bytes', 'KB', 'MB'];
        if (bytes == 0) return 'n/a';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
    },
    update: function(e) {
        $('#' + UAV.config.x).val(e.x);
        $('#' + UAV.config.y).val(e.y);
        $('#' + UAV.config.w).val(e.width);
        $('#' + UAV.config.h).val(e.height);
    },
    clear: function() {
        $('#' + UAV.config.x).val(0);
        $('#' + UAV.config.y).val(0);
        $('#' + UAV.config.w).val(0);
        $('#' + UAV.config.h).val(0);
    }
};
// Please use this package with fengyuanchen/cropper https://fengyuanchen.github.io/cropper
UAV.common = {
    read: function(file) {
        $('#' + UAV.config.uploadIcon).hide();
        var fRead = new FileReader();
        fRead.onload = function(e) {
            $('#' + UAV.config.target).show();
            $('#' + UAV.config.target).attr('src', e.target.result);
            $('#' + UAV.config.target).on('load', function() {
                var img = document.getElementById(UAV.config.target);
                var boxWidth = $('#' + UAV.config.target).innerWidth();
                var boxHeight = Math.round(boxWidth * img.naturalHeight / img.naturalWidth);
                var minCropBoxWidth = UAV.config.img_width / (img.naturalWidth / boxWidth);
                var minCropBoxHeight = UAV.config.img_height / (img.naturalHeight / boxHeight);
                if (img.naturalWidth < UAV.config.img_width || img.naturalHeight < UAV.config.img_height) {
                    UAV.common.error(UAV.lang.smallsize);
                    UAV.data.error = true;
                    return false;
                }
                if (!UAV.data.error) {
                    // Hide and show data
                    $('#' + UAV.config.uploadGuide).hide();
                    $('#' + UAV.config.uploadInfo).show();
                    $('#' + UAV.config.imageType).html(file.type);
                    $('#' + UAV.config.imageSize).html(UAV.tool.bytes2Size(file.size));
                    $('#' + UAV.config.originalDimension).html(img.naturalWidth + ' x ' + img.naturalHeight);

                    UAV.data.cropperApi = $('#' + UAV.config.target).cropper({
                        viewMode: 3,
                        dragMode: 'crop',
                        aspectRatio: 1,
                        responsive: true,
                        modal: true,
                        guides: false,
                        highlight: true,
                        autoCrop: false,
                        autoCropArea: 0.1,
                        movable: false,
                        rotatable: false,
                        scalable: false,
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        minCropBoxWidth: minCropBoxWidth,
                        minCropBoxHeight: minCropBoxHeight,
                        minContainerWidth: 10,
                        minContainerHeight: 10,
                        crop: function(e) {
                            UAV.tool.update(e);
                        },
                        built: function() {
                            var imageData = $(this).cropper('getImageData');
                            var cropBoxScale = imageData.naturalWidth / imageData.width;
                            imageData.width = parseInt(Math.floor(imageData.width));
                            imageData.height = parseInt(Math.floor(imageData.height));
                            var cropBoxSize = {
                                width: 80 / cropBoxScale,
                                height: 80 / cropBoxScale
                            };
                            cropBoxSize.left = (imageData.width - cropBoxSize.width) / 2;
                            cropBoxSize.top = (imageData.height - cropBoxSize.height) / 2;
                            $(this).cropper('crop');
                            $(this).cropper('setCropBoxData', {
                                left: cropBoxSize.left,
                                top: cropBoxSize.top,
                                width: cropBoxSize.width,
                                height: cropBoxSize.height
                            });
                            $('#' + UAV.config.w).val(imageData.width);
                            $('#' + UAV.config.h).val(imageData.height);
                            $('#' + UAV.config.displayDimension).html(imageData.width + ' x ' + imageData.height);
                        }
                    });
                } else {
                    $('#' + UAV.config.uploadIcon).show();
                }
            });
        };
        fRead.readAsDataURL(file);
    },
    init: function() {
        UAV.data.error = false;
        if ($('#' + UAV.config.inputFile).val() == '') {
            UAV.data.error = true;
        }
        var image = $('#' + UAV.config.inputFile)[0].files[0];
        // Check ext
        if (!UAV.config.pattern.test(image.type)) {
            UAV.common.error(UAV.lang.filetype);
            UAV.data.error = true;
        }
        // Check size
        if (image.size > UAV.config.maxsize) {
            UAV.common.error(UAV.lang.bigfile);
            UAV.data.error = true;
        }
        if (!UAV.data.error) {
            // Read image
            UAV.common.read(image);
        }
    },
    error: function(e) {
        UAV.common.reset();
        alert(e);
    },
    reset: function() {
        if (UAV.data.cropperApi != null) {
            UAV.data.cropperApi.cropper('destroy');
            UAV.data.cropperApi = null;
        }
        UAV.data.error = false;
        UAV.data.busy = false;
        UAV.tool.clear();
        $('#' + UAV.config.target).removeAttr('src').removeAttr('style').hide();
        $('#' + UAV.config.uploadIcon).show();
        $('#' + UAV.config.uploadInfo).hide();
        $('#' + UAV.config.uploadGuide).show();
        $('#' + UAV.config.imageType).html('');
        $('#' + UAV.config.imageSize).html('');
        $('#' + UAV.config.originalDimension).html('');
        $('#' + UAV.config.w).val('');
        $('#' + UAV.config.h).val('');
        $('#' + UAV.config.displayDimension).html('');
    },
    submit: function() {
        if (!UAV.data.busy) {
            if ($('#' + UAV.config.w).val() == '' || $('#' + UAV.config.w).val() == '0') {
                alert(UAV.lang.upload);
                return false;
            }
            UAV.data.busy = true;
            return true;
        }
        return false;
    }
};
UAV.init = function() {
    $('#' + UAV.config.uploadIcon).click(function() {
        $('#' + UAV.config.inputFile).trigger('click');
    });
    $('#' + UAV.config.inputFile).change(function() {
        UAV.common.init();
    });
    $('#' + UAV.config.btnReset).click(function() {
        if (!UAV.data.busy) {
            UAV.common.reset();
            $('#' + UAV.config.uploadIcon).trigger('click');
        }
    });
    $('#' + UAV.config.uploadForm).submit(function() {
        return UAV.common.submit();
    });
};
