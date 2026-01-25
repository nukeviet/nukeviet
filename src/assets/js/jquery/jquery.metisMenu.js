/**
* metisMenu v1.0.1
* Author : Osman Nuri Okumuş
* Copyright 2014
* Licensed under MIT
*/
!function(i,e,t,n){var l="metisMenu",s={toggle:!0};function a(e,t){this.element=e,this.settings=i.extend({},s,t),this._defaults=s,this._name=l,this.init()}a.prototype={init:function(){var e=i(this.element),t=this.settings.toggle;e.find("li.active").has("ul").children("ul").addClass("collapse in"),e.find("li").not(".active").has("ul").children("ul").addClass("collapse"),e.find("li").has("span").children(".expand").on("click",(function(e){e.preventDefault(),i(this).parent("li").toggleClass("active").children("ul").collapse("toggle"),t&&i(this).parent("li").siblings().removeClass("active").children("ul.in").collapse("hide")}))}},i.fn.metisMenu=function(e){return this.each((function(){i.data(this,"plugin_metisMenu")||i.data(this,"plugin_metisMenu",new a(this,e))}))}}(jQuery,window,document);