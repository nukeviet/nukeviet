//** Stay on Top content script- (c) Dynamic Drive DHTML code library: http://www.dynamicdrive.com.
//** Script available at usage terms at http://www.dynamicdrive.com
//** v1.0 (May 12th, 09')

var alwaysOnTop={

	dsettings: {
		targetid: '',
		orientation: 2,
		position: [10, 30],
		externalsource: '',
		frequency: 1,
		hideafter: 0,
		fadeduration: [500, 500],
		display: 0 //internal setting on whether to display target element at all (based on frequency setting)
  },

	settingscache: {},

	positiontarget:function($target, settings){
		var fixedsupport=!document.all || document.all && document.compatMode=="CSS1Compat" && window.XMLHttpRequest //not IE or IE7+ browsers in standards mode
		var posoptions={position:fixedsupport? 'fixed':'absolute', visibility:'visible'}
		if (settings.fadeduration[0]>0) //if fade in
			posoptions.opacity=0
		posoptions[(/^[13]$/.test(settings.orientation))? 'left' : 'right']=settings.position[0]
		posoptions[(/^[12]$/.test(settings.orientation))? 'top' : 'bottom']=settings.position[1]
		if (document.all && !window.XMLHttpRequest) //loose check for IE6 and below
			posoptions.width=$target.width() //IE6- seems to require an explicit width on a DIV
		$target.css(posoptions)
		if (!fixedsupport){
			this.keepfixed($target, settings)
			var evtstr='scroll.' + settings.targetid + ' resize.'+settings.targetid
			jQuery(window).bind(evtstr, function(){alwaysOnTop.keepfixed($target, settings)})
		}
		this.revealdiv($target, settings, fixedsupport)
		if (settings.hideafter>0){ //if hide timer enabled
			setTimeout(function(){
				alwaysOnTop.hidediv(settings.targetid)
			}, settings.hideafter+settings.fadeduration[0])
		}
	},

	keepfixed:function($target, settings){
		var $window=jQuery(window)
		var is1or3=/^[13]$/.test(settings.orientation)
		var is1or2=/^[12]$/.test(settings.orientation)
		var x=$window.scrollLeft() + (is1or3? settings.position[0] : $window.width()-$target.outerWidth()-settings.position[0])
		var y=$window.scrollTop() + (is1or2? settings.position[1] : $window.height()-$target.outerHeight()-settings.position[1])
		$target.css({left:x+'px', top:y+'px'})
	},

	revealdiv:function($target, settings){
		if (settings.fadeduration[0]>0) //if fade in
			$target.show().animate({opacity:1}, settings.fadeduration[0])
		else
			$target.show()
		if (settings.frequency=="session") //set session only cookie (name: 'sots' + targetid)? 
			this.setCookie('sots'+settings.targetid, 'shown')
		else if (/^\d+ day/i.test(settings.frequency)){ //set persistent cookie (name: 'sotp' + targetid)? 
			var persistdays=parseInt(settings.frequency)
			this.setCookie('sotp'+settings.targetid, persistdays, persistdays)
		}
	},

	hidediv:function(targetid){ //public function
		var $target=jQuery('#'+targetid)
		if ($target.css('display')=='none') //if target hidden already
			return
		var settings=this.settingscache[targetid]
		if (settings.fadeduration[1]>0) //if fade out
			$target.animate({opacity:0}, settings.fadeduration[1], function(){$target.hide()})
		else
			$target.hide()
		var evtstr='scroll.' + settings.targetid + ' resize.'+settings.targetid
		jQuery(window).unbind(evtstr)
	},

	loadajaxcontent:function($, settings){
		$.ajax({
			url: settings.externalsource,
			error:function(ajaxrequest){
				alert('Error fetching Ajax content.\nServer Response: '+ajaxrequest.responseText)
			},
			success:function(content){
				var $target=$(content)
				if ($target.get(0).id==settings.targetid)
					alwaysOnTop.positiontarget($target.appendTo('body'), settings)
				else
					alert('Error: The value you have entered for "targetid" ('+settings.targetid+') '
						+ 'doesn\'t match the ID of your remote content\'s DIV container ('+$target.get(0).id+'). This must be corrected'
					)
			}
		})
	},

	init:function(options){
		var settings={}
  	settings=jQuery.extend(settings, this.dsettings, options)
		this.settingscache[settings.targetid]=settings
		if (typeof settings.frequency=="number") //value of 1=show, 0=hide
			settings.display=(settings.frequency>Math.random())? 1 : 0
		else if (settings.frequency=="session")
			settings.display=(this.getCookie('sots'+settings.targetid)=='shown')? 0 : 1 //session cookie name: 'sots' + targetid
		else if (/^\d+ day/i.test(settings.frequency)){ //match 'xx days'
			//If admin has changed number of days to persist from current cookie records, reset persistence by deleting cookie
			if (parseInt(this.getCookie('sotp'+settings.targetid))!= parseInt(settings.frequency))
				this.setCookie('sotp'+settings.targetid, '', -1)
			settings.display=(this.getCookie('sotp'+settings.targetid)!=null)? 0 : 1 //persistent cookie name: 'sotp' + targetid
		}
		jQuery(document).ready(function($){
			if (settings.externalsource!='' && settings.display){ //if ajax content
					alwaysOnTop.loadajaxcontent($, settings)
			}
			else if (settings.externalsource==''){ //inline content
				var $target=$('#'+settings.targetid)
				if (!settings.display){ //if hide content (based on frequency setting)
					$target.hide()
					return false
				}
				else{
					alwaysOnTop.positiontarget($target, settings)
				}
			}
		}) //end ready
	},

	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]*", "i"); //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	},

	setCookie:function(name, value, days){
		if (typeof days!="undefined"){ //if set persistent cookie
			var expireDate = new Date()
			var expstring=expireDate.setDate(expireDate.getDate()+days)
			document.cookie = name+"="+value+"; expires="+expireDate.toGMTString()
		}
		else
			document.cookie = name+"="+value+"; path=/"
	}
}