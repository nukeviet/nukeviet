/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/30/2009 1:44
 */

var namecheck = /^([a-zA-Z0-9_-])+$/;

if( typeof( jsi ) == 'undefined' ) var jsi = new Array();
if( ! jsi[0] ) jsi[0] = 'vi';
if( ! jsi[1] ) jsi[1] = 5;
if( ! jsi[2] ) jsi[2] = 15;

var strHref = window.location.href;
if( strHref.indexOf( "?" ) > - 1 )
{
   var strHref_split = strHref.split( "?" );
   var script_name = strHref_split[0];
   var query_string = strHref_split[1];
}

//  ---------------------------------------

else
{
   var script_name = strHref;
   var query_string = '';
}

//  ---------------------------------------

var bn = 0;

//  ---------------------------------------

var lang_error =
{
   en : 'Error: Password has not announced or declared invalid. Only letters, numbers and underscores the Latin alphabet. Minimum ' + jsi[1] + ' characters, maximum ' + jsi[2] + ' characters',
   vi : 'L\u1ED7i: Password c\u1EE7a Admin ch\u01B0a \u0111\u01B0\u1EE3c khai b\u00E1o ho\u1EB7c khai b\u00E1o kh\u00F4ng h\u1EE3p l\u1EC7! (Kh\u00F4ng \u00EDt h\u01A1n ' + jsi[1] + ' k\u00FD t\u1EF1, kh\u00F4ng nhi\u1EC1u h\u01A1n ' + jsi[2] + ' k\u00FD t\u1EF1. Ch\u1EC9 ch\u1EE9a c\u00E1c k\u00FD t\u1EF1 c\u00F3 trong b\u1EA3ng ch\u1EEF c\u00E1i latin, s\u1ED1 v\u00E0 d\u1EA5u g\u1EA1ch d\u01B0\u1EDBi)',
   ru : 'Îøèáêà: Ïàðîëü íå áûë îáúÿâëåí èëè ïðèçíàí íåäåéñòâèòåëüíûì. Ðàçðåøàþòñÿ òîëüêî áóêâû, öèôðû è çíàê ïîä÷åðêèâàíèå ëàòèíñêîãî àëôàâèòà. Êîëè÷åñòâî çíàêîâ íå ìåíüøå ' + jsi[1] + ', íå áîëüøå ' + jsi[2]
}

//  ---------------------------------------

var lang_submit =
{
   en : 'Submit',
   vi : '\u0110\u0103ng nh\u1EADp',
   ru : 'Ëîãèí'
}

//  ---------------------------------------


function nv_checkadminlogin_password( password )
{
   return ( password.value.length >= jsi[1] && password.value.length <= jsi[2] && namecheck.test( password.value ) ) ? true : false;
}

//  ---------------------------------------

function nv_checkadminlogin_submit()
{
   var password = document.getElementById( 'password' );
   if( ! nv_checkadminlogin_password( password ) )
   {
      alert( lang_error[jsi[0]] );
      password.focus();
	  return false;
   }
   return true;
}