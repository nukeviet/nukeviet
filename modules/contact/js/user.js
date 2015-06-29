/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function sendcontact(num) {
	var sendname = document.getElementById('fname');
	var sendemail = document.getElementById('femail_iavim');
	var sendtitle = document.getElementById('ftitle');
	var sendcontent = document.getElementById('fcon');
	var bt = document.getElementById('btsend');
	var fcon = document.getElementById('fcontact');

	bt.disabled = true;

	if (sendname.value.length < 2) {
		alert(nv_fullname);
		bt.disabled = false;
		sendname.focus();
		return false;
	}

	if (! nv_mailfilter.test(sendemail.value)) {
		alert(nv_email);
		bt.disabled = false;
		sendemail.focus();
		return false;
	}

	if (sendtitle.value.length < 2) {
		alert(nv_title);
		bt.disabled = false;
		sendtitle.focus();
		return false;
	}

	if (sendcontent.value.length < 2) {
		alert(nv_content);
		bt.disabled = false;
		sendcontent.focus();
		return false;
	}

	var seccode = document.getElementById('fcode_iavim');

	if ((seccode.value.length != num ) || ! nv_namecheck.test(seccode.value)) {
		alert(nv_code);
		nv_change_captcha('vimg', 'fcode_iavim');
		bt.disabled = false;
		seccode.focus();
		return false;
	}

	return true;

}

function nv_ismaxlength(fid, length) {
	if (fid.value.length < length) {
		fid.value = fid.value.substring(0, length);
	}
}