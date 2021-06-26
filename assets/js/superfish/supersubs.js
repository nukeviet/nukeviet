
/*
 * Supersubs v0.3b - jQuery plugin
 * Copyright (c) 2013 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 *
 * This plugin automatically adjusts submenu widths of suckerfish-style menus to that of
 * their longest list item children. If you use this, please expect bugs and report them
 * to the jQuery Google Group with the word 'Superfish' in the subject line.
 *
 */
!function(t){t.fn.supersubs=function(i){var e=t.extend({},t.fn.supersubs.defaults,i);return this.each((function(){var i=t(this),s=t.meta?t.extend({},e,i.data()):e;$ULs=i.find("ul").show();var n=t('<li id="menu-fontsize">&#8212;</li>').css({padding:0,position:"absolute",top:"-999em",width:"auto"}).appendTo(i)[0].clientWidth;t("#menu-fontsize").remove(),$ULs.each((function(i){var e=t(this),a=e.children(),d=a.children("a"),h=a.css("white-space","nowrap").css("float");e.add(a).add(d).css({float:"none",width:"auto"});var c=e[0].clientWidth/n;(c+=s.extraWidth)>s.maxWidth?c=s.maxWidth:c<s.minWidth&&(c=s.minWidth),c+="em",e.css("width",c),a.css({float:h,width:"100%","white-space":"normal"}).each((function(){var i=t(this).children("ul"),e=void 0!==i.css("left")?"left":"right";i.css(e,"100%")}))})).hide()}))},t.fn.supersubs.defaults={minWidth:9,maxWidth:25,extraWidth:0}}(jQuery);