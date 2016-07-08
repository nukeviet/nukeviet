/*
jquery.flash v1.3.3 -  08/12/10
(c)2009 Stephen Belanger - MIT/GPL.
http://docs.jquery.com/License
*/
;(function($){
	// IE doesn't have navigator.plugins, it uses ActiveXObject instead. >.>
	var isie = function() { var p = navigator.plugins; return (p && p.length) ? false : true; };

	if(isie()) {
		// IE uses an ancient version of Javascript, so let's add the missing indexOf method in manually.
		Array.prototype.indexOf = function(o,i){
			for(var j = this.length, i = i < 0 ? i + j < 0 ? 0 : i + j : i || 0; i < j && this[i] !== o; i++);
			return j <= i ? - 1 : i;
		};
	}

	// Check if browser has flash installed.
	var hasflash = function() {
		return (flashversion())
			? true
			: false;
	};

	// Check what version of flash is installed.
	var flashversion = function() {
		if(isie()) {
			var key = 'ShockwaveFlash.ShockwaveFlash';
			try {
				var axo = new ActiveXObject(key+'.7');
			} catch(e) {
				try {
					var axo = new ActiveXObject(key+'.6');
					return [6, 0, 21];
				} catch(e) {};
				try {
					axo = new ActiveXObject(key);
				} catch(e) {};
			}
			if (axo != null) {
				return axo.GetVariable("$version").split(" ")[1].split(",");
			}
		} else {
			var p = navigator.plugins;
			var f = p['Shockwave Flash'];
			if (f && f.description) {
				return f.description.replace(/([a-zA-Z]|\s)+/, "").replace(/(\s+r|\s+b[0-9]+)/, ".").split(".");
			} else if (p['Shockwave Flash 2.0']) {
				return '2.0.0.11';
			}
		}
	};

	// Ok, enough fixing of IE's inadequacies, let's get on with it!
	$.fn.extend({
		flash: function (opt) {
			// Don't even bother if we don't have Flash installed.
			if (hasflash()) {
				// Let's make some handy functions to minimize code repetition.
				function attr(a,b){return ' '+a+'="'+b+'"';}
				function param(a,b){return '<param name="'+a+'" value="'+b+'" />';}
				
				// Get current version for express install checking.
				var cv = flashversion();
				
				// Finally, we're on to the REAL action.
				$(this).each(function () {
					// Create a reference to the current item as a jquery object.
					var e = $(this);
					
					// Merge settings objects.
					var s = $.extend({
							'id': e.attr('id')
							, 'class': e.attr('class')
							, 'width': e.width()
							, 'height': e.height()
							, 'src': e.attr('href')
							, 'classid': 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
							, 'pluginspace': 'http://get.adobe.com/flashplayer'
							, 'type': 'application/x-shockwave-flash'
							, 'availattrs': [
								'id'
								, 'class'
								, 'width'
								, 'height'
								, 'src'
								, 'type'
							]
							, 'availparams': [
								'src'
								, 'bgcolor'
								, 'quality'
								, 'allowscriptaccess'
								, 'allowfullscreen'
								, 'flashvars'
								, 'wmode'
							]
							, 'version': '9.0.24'
						}, opt);
					
					// Collect list of attributes and parameters to use.
					var a = s.availattrs;
					var p = s.availparams;
					
					// Get required version array.
					var rv = s.version.split('.');
					
					// Open output string.
					var o = '<object';
					
					// Set codebase, if not supplied in the settings.
					if (!s.codebase) {
						s.codebase = (('https:' == document.location.protocol) ? 'https' : 'http')+'://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=' + rv.join(',');
					}
					
					// Use express install swf, if necessary.
					if (s.express) {
						for (var i in cv) {
							if (parseInt(cv[i]) > parseInt(rv[i])) {
								break;
							}
							if (parseInt(cv[i]) < parseInt(rv[i])) {
								s.src = s.express;
							}
						}
					}
					
					// Convert flashvars to query string.
					if (s.flashvars) {
						s.flashvars = unescape($.param(s.flashvars));
					}
					
					// Set browser-specific attributes
					a = isie() ? a.concat(['classid', 'codebase']) : a.concat(['pluginspage']);
					
					// Add attributes to output buffer.
					for (k in a) {
						var n = (k == a.indexOf('src')) ? 'data' : a[k];
						o += s[a[k]] ? attr(n, s[a[k]]) : '';
					};
					o += '>';
					
					// Add parameters to output buffer.
					for (k in p) {
						var n = (k == p.indexOf('src')) ? 'movie' : p[k];
						o += s[p[k]] ? param(n, s[p[k]]) : '';
					};
					
					// Close and swap.
					o += '</object>';
					e.replaceWith(o);
				});
			}
					
			return this;
		}
	});
})(jQuery);