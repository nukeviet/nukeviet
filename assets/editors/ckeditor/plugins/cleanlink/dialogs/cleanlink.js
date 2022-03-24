/* Nguyễn Thái Hà tlthaiha@gmail.com
 * Clean link , need jquery
 */
 
CKEDITOR.dialog.add('cleanlink', function(editor) {
	function cll_log(msg) {
		//hàm log, bật lên để bug
		//console.log(msg);
	}

	var lang = editor.lang.cleanlink;
	// lay phan tu DOM cua tai lieu
	var DOM_doc;
	// lấy phần tử jquery thao tác cho nhanh
	var editor_body;
	var domainName = window.location.hostname;
	cll_log('domainname: ' + domainName);
	var links = [];
	//--------------------- Hàm xác định body
	function cll_determine_body(){
		//khi người dùng nhấn nút HTML thì thẻ frame bị hủy và cần xác định lại
		// lay phan tu DOM cua tai lieu
		DOM_doc = editor.document.getBody();
		// lấy phần tử jquery thao tác cho nhanh
		editor_body = $(DOM_doc.$);	
	}	
	cll_determine_body();
	//--------------------- hàm tách link ra và phân loại
	function cll_extract_link() {
		var l = [];
		var href = '';
		var anchortext = '';
		var type = '';
		var matches = '';
		var link_domain = '';
		var isfound = false;
		editor_body.find('a').each(function(index) {
			type = lang.type_normal;
			link_domain = '';
			var thislink = $(this);
			href = thislink.attr('href');
			isfound = false;
			if (!href) {
				href = '';
				type = lang.type_no_href;
				isfound = true;
			}
			if (!isfound) {
				matches = href.match(/^\w+\:\/\/([^\/?#]+)([\/?#])?.*/i);
				link_domain = matches && matches[1];
				if (link_domain == null) {
					//không phát hiện được domain, có thể là link nội bộ
					if (href.match(/^mailto:.*/i)) {
						isfound = true;
						type = lang.type_mail;
					} else if (href.match(/^javascript:/i)) {
						isfound = true;
						type = lang.type_javascript;
					}
				} else if (link_domain.toLowerCase() != domainName.toLowerCase()) {
					isfound = true;
				}
			}

			if (isfound) {
				//tìm thấy link ngoài domain, tìm tiếp loại link và anchortext
				anchortext = thislink.text();
				if (!anchortext) {
					anchortext = lang.no_anchor_text;
					type = lang.type_no_text;
				}
				if (thislink.find('img').length) {
					type = lang.type_image;
				}
				l.push({
					obj : thislink,
					href : href,
					domain : link_domain,
					type : type,
					anchortext : anchortext
				});
				cll_log('Found Link: ' + href);
			}
		});
		return l;
	}

	var dc = [];
	//nạp tab thứ nhất
	dc.push({
		id : 'cll_main',
		label : lang.title,
		elements : [{
			type : 'html',
			id : 'cll_table',
			html : '',
			setup : function() {
				var htmlToLoad = '';
				var container = this.getElement();
				var i = 0;
				var link = 0;
				if (links.length == 0) {
					htmlToLoad += lang.nolink;
				} else {
					htmlToLoad += '<div style="width:100%;height: 300px; overflow-y: auto; display: inline-block;">';
					htmlToLoad += '<table style="width:100%; background-color:white;">';
					htmlToLoad += '<caption>' + lang.info + ' (' + domainName + ')' + '</caption>';
					htmlToLoad += '<thead><tr><th>' + lang.domain + '</th>';
					htmlToLoad += '<th>' + lang.anchortext + '</th>';
					htmlToLoad += '<th>' + editor.lang.link.type + '</th>';
					htmlToLoad += '<th>' + lang.unlink + '</th>';
					htmlToLoad += '<th>' + lang.dellink + '</th>';
					htmlToLoad += '</tr></thead>';
					htmlToLoad += '<tbody id="exlinks">';
					for ( i = 0; i < links.length; i++) {
						link = links[i];
						htmlToLoad += '<tr style="border-top: 1px solid #ccc;">';
						htmlToLoad += '<td><b>' + link.domain + '</b></td>';
						htmlToLoad += '<td><a href="' + link.href + '" target="_blank" >' + link.anchortext.substring(0, 60) + '</a></td>';
						htmlToLoad += '<td>' + link.type + '</td>';
						htmlToLoad += '<td><input id="unlink_' + i + '" type="checkbox" checked="checked" value="1"/></td>';
						htmlToLoad += '<td><input id="dellink_' + i + '" type="checkbox" value="1" /></td>';
						htmlToLoad += '</tr>';
					}

					htmlToLoad += '</tbody>';
					htmlToLoad += '</table>';
					htmlToLoad += '</div>';
				}

				container.setHtml(htmlToLoad);
			}
		}]
	});
    //-------------------------------------------- thêm tag huong dan su dung
	dc.push({
		id : 'cll_guide',
		label : lang.guide,
		elements : [{
			type : 'html',
			id : 'cll_guide_box',
			html : '<p>' + lang.guide_info + '<p>'
		}]
	});
	//-------------------------------------------- thêm tag bản quyền
	dc.push({
		id : 'cll_about',
		label : lang.about,
		elements : [{
			type : 'html',
			id : 'cll_about_box',
			html : '<p>' + lang.copyright + '<p>'
		}]
	});
	//---------------- tra ve dialogs --------------------------------------
	return {
		title : lang.title,
		minWidth : CKEDITOR.env.ie && CKEDITOR.env.quirks ? 670 : 650,
		minHeight : CKEDITOR.env.quirks ? 390 : 385,
		onShow : function() {
			cll_determine_body();
			//kích thoạt hàm setup
			links = cll_extract_link();
			this.setupContent();
		},
		onOk : function() {
			this.commitContent();
			if (links.length > 0) {
				editor.fire('saveSnapshot');
				var i = 0;
				var link = {};
				var unlink = false;
				for ( i = 0; i < links.length; i++) {
					link = links[i];
					if ($('#dellink_'+ i).is(":checked")) {
						link.obj.remove();
						cll_log('DELETE: ' + i + '   ' + link.href+ ' - ' + link.anchortext.substring(0,40) );
					}
					else if (($('#unlink_'+ i).is(":checked"))) {
						link.obj.replaceWith(link.obj.html());
						cll_log('UNLINK: ' + i + '   ' + link.href+ ' - ' + link.anchortext.substring(0,40) );	
					}
				}
			}
		},
		contents : dc
	};
});
