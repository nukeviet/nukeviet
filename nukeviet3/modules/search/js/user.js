// JavaScript Document
  /////////////////////////////////////////////////////////////////////
  function change()
  {
	  var slmod = document.getElementById('slmod');
	  //var btadv = document.getElementById('btadv');
	  //if ( slmod.value == 'all') { btadv.disabled = true; } else { btadv.disabled = false; }
  }
  ///////////////////////////////////////////////////////////////////////////
  function GoUrl (url) 
  {
	  var slmod = document.getElementById('slmod');
	  if(slmod.value != 'all')
	  {
		var fsea = document.getElementById('fsea');
		fsea.method = 'post';
		fsea.action = url;
		fsea.submit();
	  }
	  else
	  {
	  	alert("please chose module name!");
	  }
  }
  //////////////////////////////////////////////////////////////////////////////
  function ViewAll(mod)
  {
	  var fsea = document.getElementById('fsea');
	  var slmod = document.getElementById('slmod');
	  fsea.method = 'get';
	  fsea.action = '';
	  slmod.value = mod;
	  fsea.submit();
  }