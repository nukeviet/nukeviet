/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 16-03-2015 12:55
 */


CKEDITOR.dialog.add('tbvdownloadDialog', function(editor) {
	function rhi_log(msg) {
		//hàm log, bật lên để bug
		// console.log(msg);
	}
    var path_image= (editor.config.filebrowserImageBrowseUrl)? editor.config.filebrowserImageBrowseUrl : '';
	var sURLVariables = path_image.split('&');
	var ipath = icurrentpath = dirpath_view = '';
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == 'path')
        {
            dirpath_view = ipath = sParameterName[1];
        }
        else if (sParameterName[0] == 'currentpath')
        {
            icurrentpath = sParameterName[1];
        }
    }
    icurrentpath = (icurrentpath.indexOf(ipath) != -1 ) ? icurrentpath : ipath;
    
	var lang = editor.lang.tbvdownload;
	var domainName = window.location.hostname;
	rhi_log('domainname: ' + domainName);
	var editor_id = editor.id;
	rhi_log('editor_id: ' + editor_id);
	var DOM_doc;
	var editor_body;
	var rhi_list = [];
	var need_alt = 0;
	var UploaderUrl = script_name + '?' + nv_name_variable + '=upload&' + nv_fc_variable + '=upload';
    var RenameUrl = script_name + '?' + nv_name_variable + '=upload&' + nv_fc_variable + '=renameimg';

	rhi_log('Uploader : ' + UploaderUrl);
	var rhi_progress = 0;
	//--------------------- Hàm xác định body
	function rhi_determine_body(){
		//khi người dùng nhấn nút HTML thì thẻ frame bị hủy và cần xác định lại
		// lay phan tu DOM cua tai lieu
		DOM_doc = editor.document.getBody();
		// lấy phần tử jquery thao tác cho nhanh
		editor_body = $(DOM_doc.$);	
	}
	rhi_determine_body();
    //---------------ham doi ten file
    function rhi_renameimage( pathsave, new_filename, filename ){
        var result = false;
        $.ajax({
			type : 'POST',
			url : RenameUrl,
            dataType: "html",
            async: false,
			data : {
				path : pathsave,
				newname : new_filename,
				file : filename
			},
            success : function(rn) {
                if (rn.substring(0, 6) == 'ERROR_') {
					result = filename; //ten file cu
				} else {
			        result = rn;//ten file moi
                }
            } 
        });
        return result;
    }
	//--------------------- Hàm lấy tất cả các phần tử img
	function rhi_extract_image() {
		var l = [];
		var img_src = '';
		var img_alt = '';
		var matches = '';
		var img_domain = '';
		var isnew = false;
		//tìm tất cả ảnh trong nội dung
		editor_body.find('img').each(function(index) {
			var thisimg = $(this);
			img_src = thisimg.attr('src');
			if ( typeof (img_src) == 'undefined')
				img_src = '';
			matches = img_src.match(/^https?\:\/\/([^\/?#]+)([\/?#])?.*/i);
			img_domain = matches && matches[1];
			if (img_domain && (img_domain.toLowerCase() != domainName.toLowerCase())) {
				//tìm thấy ảnh đúng là ở ngoài domain hiện tại
				img_alt = thisimg.attr('alt');
				if ( typeof (img_alt) == 'undefined')
					img_alt = '';
				if (!img_alt)
					need_alt = 1;
				var imgobj = {
					obj : thisimg,
					alt : img_alt
				};
				isnew = l.every(function(rhi) {
					if (rhi.src != img_src)
						return true;
					else {
						rhi.objs.push(imgobj);
						rhi_log('image repeat: ' + img_src);
						return false;
					}
				});
				if (isnew) {
					l.push({
						src : img_src,
						objs : [imgobj]
					});
					rhi_log('image found: ' + img_src);
				}
			}
		});
		return l;
	};
	//---------------- hàm hiển thị thanh tiến trình
	function rhi_set_progress(p) {
		rhi_progress += p;
		var l = (rhi_list.length == 0) ? 1 : rhi_list.length;
		var w = Math.round(rhi_progress * 100 / l);
		if (w < 1)
			w = 1;
		$('#rhi_pv').css('width', '' + w + '%');
		rhi_log('progress: ' + rhi_progress + '/' + l);
	}
	//rhi_list = rhi_extract_image();
	//biến nội của cửa sổ dialogs contents
	var dc = [];
	//nạp tab thứ nhất
	dc.push({
		id : 'rhi_main',
		label : lang.title,
		elements : [{
			type : 'hbox',
			widths : ['100%', '120px'],
			align : 'right',
			children : [{
				id : 'rhi_pathsave',
				type : 'text',
				label : lang.url_path_save,
				required : true,
				'default' : icurrentpath
			}, {
				type : 'button',
				id : 'rhi_browse',
				hidden : true,
				style : 'display:inline-block;margin-top:12px;',
				align : 'center',
				label : editor.lang.common.browseServer,
				filebrowser : {
					action : 'Browse',
					params : {},
					onSelect : function(fileUrl) {
						var dialog = this.getDialog();
						var p = fileUrl.substring(0, fileUrl.lastIndexOf("/"));
						dialog.getContentElement('rhi_main', 'rhi_pathsave').setValue(p);
					}
				}
			}]
		}, {
			id : 'rhi_Alt',
			type : 'text',
			label : lang.altimage,
			'default' : '',
		}, {
			id : 'rhi_noaltonly',
			type : 'checkbox',
			label : lang.noaltonly,
			'default' : 'checked',
		}, {
			id : 'rhi_FileName',
			type : 'text',
			label : lang.filename,
			'default' : '',
		}, {
			type : 'html',
			id : 'rhi_table',
			html : '',
			setup : function() {
				var htmlToLoad = '';
				var container = this.getElement();
				if (rhi_list.length == 0) {
					htmlToLoad += lang.noimgae;
				} else {
					//có ảnh ngoài, xử lý hiện danh sách
					htmlToLoad += '<div style="width:100%;height: 200px; overflow-y: auto; display: inline-block;">';
					htmlToLoad += '<table style="width:100%; background-color:white;">';
					htmlToLoad += '<thead><tr><th colspan="2">' + lang.imageurl + '</th><th>' + lang.imagestatus + '</th></tr></thead>';
					htmlToLoad += '<tbody id="eximgs">';
					rhi_list.every(function(rhi, i) {
						htmlToLoad += '<tr style="border-top: 1px solid #ccc;">';
						htmlToLoad += '<td style="vertical-align: middle;"><img src="' + rhi.src + '" style="width:64px;height:64px;padding:2px"></td>';
						htmlToLoad += '<td><a href="' + rhi.src + '" target="_blank" >' + rhi.src + '</a>';
						htmlToLoad += '<ol style="padding:3px 0 3px 30px" colspan="2">';
						rhi.objs.every(function(rgi_e) {
							var alt = rgi_e.alt;
							if (!alt)
								alt = '<span style="color:red">' + lang.noalt + '</span>';
							htmlToLoad += '<li>' + alt + '</li>';
							return true;
						});
						htmlToLoad += '</ol>';
						htmlToLoad += '<span style="color:red" id="rhie_' + i + '"></span>';
						htmlToLoad += '</td><td id="rhis_' + i + '" style="color:red">✘</td></tr>';
						return true;
					});
					htmlToLoad += '</tbody>';
					htmlToLoad += '</table>';
					htmlToLoad += '</div>';
				}
				container.setHtml(htmlToLoad);
			},
		}, {
			type : 'hbox',
			widths : ['120px', '100%'],
			children : [{
				type : 'button',
				id : 'rhi_cmd_upload',
				label : editor.lang.common.uploadSubmit,
				onClick : function() {
					if (rhi_list.length == 0) {
						alert(lang.noimgae2);
						return false;
					}
					var dialog = this.getDialog();
					var new_alt = dialog.getValueOf('rhi_main', 'rhi_Alt');
					// kiểm tra xem có cần thông tin về alt không để tiếp tục
					if (!new_alt)
						new_alt = '';
					rhi_log('new_alt: ' + new_alt);
					var noaltonly = dialog.getValueOf('rhi_main', 'rhi_noaltonly') ? 1 : 0;
					rhi_log('noaltonly: ' + noaltonly);
					if (((!noaltonly) || need_alt) && (new_alt == '' )) {
						alert(lang.require_alt);
						return false;
					}
					// kiểm tra xem có thư mục chưa để tiếp tục
					var pathsave = dialog.getValueOf('rhi_main', 'rhi_pathsave');
					if (!pathsave) {
						alert(lang.require_path_save);
						return false;
					}
					var new_filename = dialog.getValueOf('rhi_main', 'rhi_FileName');
					if (!new_filename)
						new_filename = '';
					else
						rhi_log('New Filename: ' + new_filename);
					//thực hiện upload
					editor.fire('saveSnapshot');
					k = 0;
					rhi_list.every(function(rhi, i) {
						rhi_log('Upload file: ' + rhi.src);
						$.ajax({
							type : 'POST',
							url : UploaderUrl,
							data : {
								path : pathsave,
								fileurl : rhi.src,
								newfilename : new_filename
							},
							success : function(r) {
								//upload thành công
								rhi_log('Upload result: ' + r);
								if (r.substring(0, 6) == 'ERROR_') {
									$('#rhie_' + i).html(r);
								} else {
				                    var new_src = nv_base_siteurl + pathsave + '/' + r;
									rhi.objs.every(function(rgi_e, j) {
										var obj = rgi_e.obj;
										obj.attr('src', new_src).attr('data-cke-saved-src', new_src);
										if ((!noaltonly) || (rgi_e.alt == ''))
											obj.attr('alt', new_alt);
										rhi_log(j + '. ' + rgi_e.alt);
										return true;
									});
									$('#rhis_' + i).html('✔').css('color', 'green');
								}
								rhi_set_progress(1);
							}
						}).fail(function() {
							$('#rhie_' + i).html(lang.server_error);
							rhi_log(lang.server_error);
							rhi_set_progress(1);
						});
						return true;
					});
				}
			}, {
				type : 'html',
				id : 'rhi_progress',
				html : '<div style="width:100%; height:17px; border: 1px solid #ccc;"><div id="rhi_pv" style="height:100%;width:1px; background-color:green;" ><div><div>',
				setup : function() {
					rhi_progress = 0;
					rhi_set_progress(0);
				}
			}]
		}]
	});
    //thêm tag huong dan su dung
	dc.push({
		id : 'rhi_guide',
		label : lang.guide,
		elements : [{
			type : 'html',
			id : 'rhi_guide_box',
			html : '<p>' + lang.guidecontent + '<p>'
		}]
	});
	//thêm tag bản quyền
	dc.push({
		id : 'rhi_about',
		label : lang.about,
		elements : [{
			type : 'html',
			id : 'rhi_about_box',
			html : '<p>' + lang.copyright + '<p>'
		}]
	});
	//---------------- tra ve dialogs --------------------------------------
	return {
		title : lang.title,
		minWidth : CKEDITOR.env.ie && CKEDITOR.env.quirks ? 670 : 650,
		minHeight : CKEDITOR.env.quirks ? 390 : 385,
		onShow : function() {
			//kích thoạt hàm setup
			rhi_determine_body();
			rhi_list = rhi_extract_image();
			this.setupContent();
		},
		onOk : function() {
			this.commitContent();
		},
		contents : dc
	};
});
