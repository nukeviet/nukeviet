/**
 * hoverIntent is similar to jQuery's built-in "hover" method except that
 * instead of firing the handlerIn function immediately, hoverIntent checks
 * to see if the user's mouse has slowed down (beneath the sensitivity
 * threshold) before firing the event. The handlerOut function is only
 * called after a matching handlerIn.
 *
 * hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+
 * http://cherne.net/brian/resources/jquery.hoverIntent.html
 *
 * You may use hoverIntent under the terms of the MIT license. Basically that
 * means you are free to use hoverIntent as long as this header is left intact.
 * Copyright 2007, 2013 Brian Cherne
 *
 * // basic usage ... just like .hover()
 * .hoverIntent( handlerIn, handlerOut )
 * .hoverIntent( handlerInOut )
 *
 * // basic usage ... with event delegation!
 * .hoverIntent( handlerIn, handlerOut, selector )
 * .hoverIntent( handlerInOut, selector )
 *
 * // using a basic configuration object
 * .hoverIntent( config )
 *
 * @param  handlerIn   function OR configuration object
 * @param  handlerOut  function OR selector for delegation OR undefined
 * @param  selector    selector OR undefined
 * @author Brian Cherne <brian(at)cherne(dot)net>
 **/
!function(e){e.fn.hoverIntent=function(t,n,o){var r,v,i,u,s={interval:100,sensitivity:7,timeout:0};s="object"==typeof t?e.extend(s,t):e.isFunction(n)?e.extend(s,{over:t,out:n,selector:o}):e.extend(s,{over:t,out:t,selector:n});var h=function(e){r=e.pageX,v=e.pageY},a=function(t,n){if(n.hoverIntent_t=clearTimeout(n.hoverIntent_t),Math.abs(i-r)+Math.abs(u-v)<s.sensitivity)return e(n).off("mousemove.hoverIntent",h),n.hoverIntent_s=1,s.over.apply(n,[t]);i=r,u=v,n.hoverIntent_t=setTimeout((function(){a(t,n)}),s.interval)},I=function(t){var n=jQuery.extend({},t),o=this;o.hoverIntent_t&&(o.hoverIntent_t=clearTimeout(o.hoverIntent_t)),"mouseenter"==t.type?(i=n.pageX,u=n.pageY,e(o).on("mousemove.hoverIntent",h),1!=o.hoverIntent_s&&(o.hoverIntent_t=setTimeout((function(){a(n,o)}),s.interval))):(e(o).off("mousemove.hoverIntent",h),1==o.hoverIntent_s&&(o.hoverIntent_t=setTimeout((function(){!function(e,t){t.hoverIntent_t=clearTimeout(t.hoverIntent_t),t.hoverIntent_s=0,s.out.apply(t,[e])}(n,o)}),s.timeout)))};return this.on({"mouseenter.hoverIntent":I,"mouseleave.hoverIntent":I},s.selector)}}(jQuery);