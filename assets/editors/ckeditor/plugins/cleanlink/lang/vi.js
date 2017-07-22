/* Hàm CKEDITOR.plugins.setLang chắc chắn sẽ được gọi nhưng có thể không có hiệu lực với CKeditor 3,
 * khi đó hãy chép bổ xung vào tệp editors/ckeditors/lang/vi.js sẽ hoạt động
 */
CKEDITOR.plugins.setLang('cleanlink', 'vi', {
	title : 'Tẩy link ngoài',
	about : 'Giới thiệu',
    guide : 'Hướng dẫn sử dụng',
    guide_info : '<strong>Gỡ link</strong> - Là chức năng chỉ loại bỏ link trong đoạn văn chứa nó mà không loại bỏ chữ<br /><strong>Xóa link</strong> - Là chức năng loại bỏ cả link và chữ trong đoạn chứa link<br />',
    copyright : 'Giấy phép GNU/GPL. Phát triển bởi &copy; <a target="_blank" href="http://volvox.vn">Công ty Volvox</a>.',
	nolink : 'Bài viết <b style="color:green">không có link ngoài</b>, rất tốt !',
	info : 'Danh sách các link ngoài domain hiện tại',
	type_normal : 'Link thường',
	type_no_href : 'Link hỏng',
	type_no_text : 'Link rỗng',
	type_image : 'Link ảnh',
	type_mail : 'Link eMail',
	type_javascript : 'Link javascript',
	no_anchor_text : 'Link chìm (không văn bản)',
	domain : 'Trỏ đến domain',
	anchortext : 'Văn bản liên kết',
	dellink : 'Xóa link',
	unlink : 'Gỡ link',
}); 
