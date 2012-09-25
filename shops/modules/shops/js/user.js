/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function sendrating(id, point, newscheckss) {
	if(point==1 || point==2 || point==3 || point==4 || point==5){
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&id=' + id + '&checkss=' + newscheckss + '&point=' + point, 'stringrating', '');
	}
}

function sendcommment(id, newscheckss, gfx_count) {
	var commentname = document.getElementById('commentname');
	var commentemail = document.getElementById('commentemail_iavim');
	var commentseccode = document.getElementById('commentseccode_iavim');
	var commentcontent = strip_tags(document.getElementById('commentcontent').value);
	if (commentname.value == "") {
		alert(nv_fullname);
		commentname.focus();
	} else if (!nv_email_check(commentemail)) {
		alert(nv_error_email);
		commentemail.focus();
	} else if (!nv_name_check(commentseccode)) {
		error = nv_error_seccode.replace( /\[num\]/g, gfx_count );
		alert(error);
		commentseccode.focus();
	} else if (commentcontent == "") {
		alert(nv_content);
		document.getElementById('commentcontent').focus();
	} else {
		var sd = document.getElementById('buttoncontent');
		sd.disabled = true;
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=postcomment&id=' + id + '&checkss=' + newscheckss + '&name=' + commentname.value + '&email=' + commentemail.value + '&code=' + commentseccode.value + '&content=' + encodeURIComponent(commentcontent), '', 'nv_commment_result');
	}
	return;
}

function nv_commment_result(res) {
	nv_change_captcha('vimg', 'commentseccode_iavim');
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		document.getElementById('commentcontent').value = "";
		nv_show_hidden('showcomment', 1);
		nv_show_comment(r_split[1], r_split[2], r_split[3]);
		alert(r_split[4]);
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_content_failed);
	}
	nv_set_disable_false('buttoncontent');
	return false;
}

function nv_show_comment(id, checkss, page) {
	nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&id=' + id + '&checkss=' + checkss + '&page=' + page, 'showcomment', '');
}

function remove_text() {
	document.getElementById('to_date').value = "";
	document.getElementById('from_date').value = "";
}

function nv_del_content(id, checkss, base_adminurl) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', base_adminurl + 'index.php', nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&id=' + id + '&checkss=' + checkss, '', 'nv_del_content_result');
	}
	return false;
}
function nv_del_content_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		window.location.href = strHref;
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

function share_twitter(){u=location.href;t=document.title;window.open("http://twitter.com/home?status="+encodeURIComponent(u))}
function share_facebook(){u=location.href;t=document.title;window.open("http://www.facebook.com/share.php?u="+encodeURIComponent(u)+"&t="+encodeURIComponent(t))}function share_google(){u=location.href;t=document.title;window.open("http://www.google.com/bookmarks/mark?op=edit&bkmk="+encodeURIComponent(u)+"&title="+t+"&annotation="+t)}
function share_buzz(){u=location.href;t=document.title;window.open("http://buzz.yahoo.com/buzz?publisherurn=DanTri&targetUrl="+encodeURIComponent(u))}


//JavaScript Document
//Copyright (C) 2005 Ilya S. Lyubinskiy. All rights reserved.
//Technical support: http://www.php-development.ru/

//----- Auxiliary -------------------------------------------------------------
function tabview_aux(TabViewId, id)
{
var TabView = document.getElementById(TabViewId);
// ----- Tabs -----
var Tabs = TabView.firstChild;
while (Tabs.className != "Tabs" ) Tabs = Tabs.nextSibling;
var Tab = Tabs.firstChild;
var i   = 0;
do
{
 if (Tab.tagName == "A")
 {
   i++;
   Tab.href      = "javascript:tabview_switch('"+TabViewId+"', "+i+");";
   Tab.className = (i == id) ? "Active" : "";
   Tab.blur();
 }
}
while (Tab = Tab.nextSibling);
// ----- Pages -----
var Pages = TabView.firstChild;
while (Pages.className != 'Pages') Pages = Pages.nextSibling;
var Page = Pages.firstChild;
var i    = 0;
do
{
 if (Page.className == 'Page')
 {
   i++;
   //if (Pages.offsetHeight) Page.style.height = (Pages.offsetHeight-2)+"px";
   //Page.style.overflow = "auto";
   Page.style.display  = (i == id) ? 'block' : 'none';
 }
}
while (Page = Page.nextSibling);
}
//----- Functions ------

function SetCookieForTabView(cookieName,cookieValue,nDays) {
var today = new Date();
var expire = new Date();
if (nDays==null || nDays==0) nDays=1;
expire.setTime(today.getTime() + 3600000*24*nDays);
document.cookie = cookieName+"="+escape(cookieValue)+ ";expires="+expire.toGMTString();
}
function ReadCookie(cookieName) {
var theCookie=""+document.cookie;
var ind=theCookie.indexOf(cookieName);
if (ind==-1 || cookieName=="") return ""; 
var ind1=theCookie.indexOf(';',ind);
if (ind1==-1) ind1=theCookie.length; 
return unescape(theCookie.substring(ind+cookieName.length+1,ind1));
}
function tabview_switch(TabViewId, id) { tabview_aux(TabViewId, id); SetCookieForTabView('tvID',id,36) }
function tabview_initialize(TabViewId) 
{ 
	tvID2 = ReadCookie('tvID')
	if (tvID2==-1 || tvID2=="") { SetCookieForTabView('tvID',1,36); tabview_aux(TabViewId,  1);  }
	else {tabview_aux(TabViewId,  tvID2); }
}

/*javascript user*/
function cartorder(a_ob){
	var id = $(a_ob).attr("id");
    $.ajax({        
      type: "GET",
      url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setcart' + '&id=' + id + "&nocache=" + new Date().getTime(),
      data:'',
      success: function(data){  
          var s = data.split('_');
          var strText = s[1];
          if ( strText != null )
          {
              var intIndexOfMatch = strText.indexOf('#@#');
              while (intIndexOfMatch != -1) {
                  strText = strText.replace('#@#', '_');
                  intIndexOfMatch = strText.indexOf('#@#');
              }
              //alert(strText);	
              alert_msg(strText);
              linkloadcart = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
              $("#cart_"+ nv_module_name).load(linkloadcart);
          }
      }
    });
}
/**/
function cartorder_detail(a_ob){
    var num = $('#pnum').val();
    var id = $(a_ob).attr("id");
    $.ajax({
        type: "POST",
        url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setcart' + '&id=' + id + "&nocache=" + new Date().getTime(),
        data: 'num=' + num,
        success: function(data){
            var s = data.split('_');
			var strText = s[1];
			if ( strText != null )
	        {
	              var intIndexOfMatch = strText.indexOf('#@#');
	              while (intIndexOfMatch != -1) {
	                  strText = strText.replace('#@#', '_');
	                  intIndexOfMatch = strText.indexOf('#@#');
	              }
	              alert_msg(strText);
	              linkloadcart = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
	              $("#cart_"+ nv_module_name).load(linkloadcart);
	        }
        }
    });
}
function alert_msg(msg) {
	$('#msgshow').html(msg); 
	$('#msgshow').show('slide').delay(3000).hide('slow'); 
}

function tooltip_shop (){
	$(".tip_trigger").hover(function(){
		tip = $(this).find('.tip');
		tip.show(); //Show tooltip
	}, function() {
		tip.hide(); //Hide tooltip		  
	}).mousemove(function(e) {
		var mousex = e.pageX + 20; //Get X coodrinates
		var mousey = e.pageY + 20; //Get Y coordinates
		var tipWidth = tip.width(); //Find width of tooltip
		var tipHeight = tip.height(); //Find height of tooltip
		
		//Distance of element from the right edge of viewport
		var tipVisX = $(window).width() - (mousex + tipWidth);
		//Distance of element from the bottom of viewport
		var tipVisY = $(window).height() - (mousey + tipHeight);
		  
		if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
			mousex = e.pageX - tipWidth - 20;
		} if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
			mousey = e.pageY - tipHeight - 20;
		} 
		tip.css({  top: mousey, left: mousex });
	});
}

function checknum(){
	var price1 = $('#price1').val();
	var price2 = $('#price2').val();
	if(price2 == '') { price2 = 0 }
	if(price2 < price1){
		document.getElementById('price2').value = '';
	}
	if (isNaN(price1)) {
		alert(isnumber);
		document.getElementById('submit').disabled = true;
	}else if (price2 != 0 && isNaN(price2)) {
		alert(isnumber);
		document.getElementById('submit').disabled = true;
	}
}
function cleartxtinput(id,txt_default){
	$("#"+id).focusin(function(){
		var txt = $(this).val();
		if (txt_default == txt ){
			$(this).val('');
		}
	});
	$("#"+id).focusout(function(){
		var txt = $(this).val();
		if ( txt == '') {
			$(this).val(txt_default);
		}
	}); 
}

function onsubmitsearch()
{
	var keyword = $('#keyword').val();
    var price1 = $('#price1').val(); if ( price1 == null ) price1 ='';
    var price2 = $('#price2').val(); if ( price2 == null ) price2 ='';
    var sid = $('#sourceid').val();
    var typemoney = $('#typemoney').val(); if ( typemoney == null ) typemoney ='';
    var cataid = $('#cata').val();
    if ( keyword == '' && price1 == '' && price2 == '' && cataid == 0 && sid == 0 )
    {
    	return false;
    }
    else {
    	window.location.href = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=shops&' + nv_fc_variable + '=search_result&keyword='+rawurlencode(keyword)+'&price1='+price1+'&price2='+price2+'&typemoney='+typemoney+'&cata='+cataid+'&sid='+sid;
    }
    return false;
}
function onsubmitsearch1()
{
    var keyword = $('#keyword1').val();
    var price1 = $('#price11').val(); if ( price1 == null ) price1 ='';
    var price2 = $('#price21').val(); if ( price2 == null ) price2 ='';
    var sid = $('#sourceid1').val();
    var typemoney = $('#typemoney1').val(); if ( typemoney == null ) typemoney ='';
    var cataid = $('#cata1').val();
    if ( keyword == '' && price1 == '' && price2 == '' && cataid == 0 && sid == 0 )
    {
    	return false;
    }
    else {
    	window.location.href = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=search_result&keyword='+rawurlencode(keyword)+'&price1='+price1+'&price2='+price2+'&typemoney='+typemoney+'&cata='+cataid+'&sid='+sid;
    }
    return false;
}