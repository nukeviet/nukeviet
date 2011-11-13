//  ------------------------------------------------------------------------ //
//                             PopCalendar 1.5                               //
//                    Copyright (c) 2009 alquanto.de                         //
//                       <http://www.alquanto.net/>                          //
//  ------------------------------------------------------------------------ //

if( typeof( nv_formatString ) == 'undefined' ) var nv_formatString = "mm/dd/yyyy";
if( typeof( nv_gotoString ) == 'undefined' ) var nv_gotoString = "Go To Current Month";
if( typeof( nv_todayString ) == 'undefined' ) var nv_todayString = "Today is";
if( typeof( nv_weekShortString ) == 'undefined' ) var nv_weekShortString = "Wk";
if( typeof( nv_weekString ) == 'undefined' ) var nv_weekString = "calendar week";
if( typeof( nv_scrollLeftMessage ) == 'undefined' ) var nv_scrollLeftMessage = "Click to scroll to previous month. Hold mouse button to scroll automatically.";
if( typeof( nv_scrollRightMessage ) == 'undefined' ) var nv_scrollRightMessage = "Click to scroll to next month. Hold mouse button to scroll automatically.";
if( typeof( nv_selectMonthMessage ) == 'undefined' ) var nv_selectMonthMessage = "Click to select a month.";
if( typeof( nv_selectYearMessage ) == 'undefined' ) var nv_selectYearMessage = "Click to select a year.";
if( typeof( nv_selectDateMessage ) == 'undefined' ) var nv_selectDateMessage = "Select [date] as date.";
if( typeof( nv_aryMonth ) == 'undefined' ) var nv_aryMonth = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
if( typeof( nv_aryMS ) == 'undefined' ) var nv_aryMS = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
if( typeof( nv_aryDayNS ) == 'undefined' ) var nv_aryDayNS = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
if( typeof( nv_aryDayName ) == 'undefined' ) var nv_aryDayName = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
if( typeof( nv_my_ofs ) == 'undefined' ) var nv_my_ofs = 7;

if( typeof( nv_siteroot ) == 'undefined' ) var nv_siteroot = '/';

var popCalendar = {
  enablePast     : true,      // true - enabled ; false - disabled
  fixedX         : -1,        // x position (-1 if to appear below control)
  fixedY         : -1,        // y position (-1 if to appear below control)
  startAt        : 1,         // 0 - sunday ; 1 - monday, ...
  showWeekNumber : false,      // false - don't show; true - show
  showToday      : true,      // false - don't show; true - show
  showDayLetter  : false,     // false - use 2-3 letter day abbreviations; true - show only one letter for a day
  theme          : "default", // directory for images and styles
  hideElements   : false,     // InternetExplorer <6 overlaps selectboxes and applets BEFORE the popCalendar. so hide these temporarily?

  o: null, om: null, oy: null, monthSelected: null, yearSelected: null, dateSelected: null, omonthSelected: null, oyearSelected: null, odateSelected: null, yearConstructed: null, intervalID1: null, intervalID2: null, timeoutID1: null, timeoutID2: null, ctlToPlaceValue: null, ctlNow: null, dateFormat: null, nStartingYear: null, curX: 0, curY: 0,
  visYear: 0, visMonth: 0,
  bPageLoaded  : false,
  ie      : (/msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent)),
  ie7     : (/msie 7/i.test(navigator.userAgent)),
  $       : function() {
    var e = new Array();
    for (var i = 0; i < arguments.length; i++) {
      var element = arguments[i];
      if (typeof element == 'string')
        element = document.getElementById(element);
      if (arguments.length == 1)
        return element;
      e.push(element);
    }
    return e;
  },
  today    : new Date(),
  dateNow  : null,
  monthNow : null,
  yearNow  : null,
  bShow    : false,

  // hides <select> and <applet> objects (for IE only)
  hideElement: function(elmID, overDiv) {
    if(this.ie && !this.ie7) {
      for(var i = 0; i < document.all.tags( elmID ).length; i++) {
        var obj = document.all.tags( elmID )[i];
        if(!obj || !obj.offsetParent) continue;

        // Find the element's offsetTop and offsetLeft relative to the BODY tag.
        objLeft     = obj.offsetLeft;
        objTop      = obj.offsetTop;
        objParent   = obj.offsetParent;

        while(objParent.tagName.toUpperCase() != 'BODY') {
          objLeft  += objParent.offsetLeft;
          objTop   += objParent.offsetTop;
          objParent = objParent.offsetParent;
        }

        objHeight   = obj.offsetHeight;
        objWidth    = obj.offsetWidth;

        if((overDiv.offsetLeft + overDiv.offsetWidth) <= objLeft);
        else if((overDiv.offsetTop + overDiv.offsetHeight) <= objTop);
        else if(overDiv.offsetTop >= (objTop + objHeight + obj.height));
        else if(overDiv.offsetLeft >= (objLeft + objWidth));
        else {
          obj.style.visibility = 'hidden';
        }
      }
    }
  },

  // unhides <select> and <applet> objects (for IE only)
  showElement: function(elmID) {
    if(this.ie && !this.ie7) {
      for(var i = 0; i < document.all.tags( elmID ).length; i++) {
        var obj = document.all.tags(elmID)[i];
        if(!obj || !obj.offsetParent) continue;
        obj.style.visibility = '';
      }
    }
  },

  // helper-functions:
  getLeft: function(l) {
    if (l.offsetParent) return (l.offsetLeft + this.getLeft(l.offsetParent));
    else return (l.offsetLeft);
  },

  getTop: function(l) {
    if (l.offsetParent) return (l.offsetTop + this.getTop(l.offsetParent));
    else return (l.offsetTop);
  },


  /**
   * Initialize the calendar. This Function must be called before show()
   * @param boolean installEventHandlers install event handlers to hide the calendar
   */
  init: function(installEventHandlers) {
    var ofs = this.today.getTimezoneOffset() / 60;
    this.today.setHours(this.today.getHours() + ofs + nv_my_ofs);
    this.dateNow  = this.today.getDate();
    this.monthNow = this.today.getMonth();
    this.yearNow  = this.today.getFullYear();

    document.write('<div onclick="popCalendar.bShow=true" id="pcIDcalendar" style="visibility:none"><table style="width:' + (this.showWeekNumber ? 250 : 230) + 'px;"><tr><td><div id="pcIDcaption" unselectable="on"></div></td></tr><tr><td><div id="pcIDcontent">.</div></td></tr>');
    if (this.showToday) document.write ('<tr><td><div id="pcIDfooter"></div></td></tr>');
    document.write('</table></div><div id="pcIDselectMonth"></div><div id="pcIDselectYear"></div>');

    this.o = this.$('pcIDcalendar');
    popCalendar.hide();
    this.om = this.$('pcIDselectMonth');
    this.oy = this.$('pcIDselectYear');
    this.yearConstructed = false;

    var s = '<div id="pcIDleft"><a href="javascript:void(0)" onclick="popCalendar.decMonth()" onmouseout="clearInterval(popCalendar.intervalID1);" onmousedown="clearTimeout(popCalendar.timeoutID1);popCalendar.timeoutID1=setTimeout(\'popCalendar.StartDecMonth()\',500)" onmouseup="clearTimeout(popCalendar.timeoutID1);clearInterval(popCalendar.intervalID1)" title="'+nv_scrollLeftMessage+'">&nbsp;&nbsp;&nbsp;</a></div>';
    s    += '<div id="pcIDright"><a href="javascript:void(0)" onclick="popCalendar.incMonth()" onmouseout="clearInterval(popCalendar.intervalID1);" onmousedown="clearTimeout(popCalendar.timeoutID1);popCalendar.timeoutID1=setTimeout(\'popCalendar.StartIncMonth()\',500)" onmouseup="clearTimeout(popCalendar.timeoutID1);clearInterval(popCalendar.intervalID1)" title="'+nv_scrollRightMessage+'">&nbsp;&nbsp;&nbsp;</a></div>';
    s    += '<div id="pcIDMonth" onclick="popCalendar.popUpMonth()" title="'+nv_selectMonthMessage+'"></div>';
    s    += '<div id="pcIDYear"  onclick="popCalendar.popUpYear()" title="' +nv_selectYearMessage+'"></div>';

    this.$('pcIDcaption').innerHTML = s;

    if (!installEventHandlers) {                          // hide calendar when enter has been pressed
      document.onkeypress = function (event) {
        if (!event) event = window.event;
        if (event.keyCode == 27) popCalendar.hide();
      };
      document.onclick = function () {                    // hide calendar when ...
        if (!popCalendar.bShow) popCalendar.hide();
        popCalendar.bShow = false;
      };
    }

    var c = document.createElement('link');               // insert CSS in header-section:
    with (c) { type = 'text/css'; rel = 'stylesheet'; href = nv_siteroot + 'js/popcalendar/calendar_themes/' + this.theme+'/style.css'; media = 'screen'; }
    document.getElementsByTagName("head")[0].appendChild(c);
    this.bPageLoaded=true;
  },

  hide: function() {
    this.o.style.visibility = 'hidden';
    if (this.om != null) this.om.style.visibility = 'hidden';
    if (this.oy != null) this.oy.style.visibility = 'hidden';
    if (this.hideElements) {
      this.showElement('SELECT');
      this.showElement('APPLET');
    }
  },

  // holidays...
  HolidaysCounter: 0,
  Holidays: new Array(),
  HolidayRec: function(d, m, y, desc) {
    this.d = d; this.m = m; this.y = y; this.desc = desc;
  },
  addHoliday: function(d, m, y, desc) {
    this.Holidays[this.HolidaysCounter++] = new this.HolidayRec (d, m, y, desc);
  },

  padZero: function(num) {
    return (num < 10) ? '0' + num : num;
  },

  constructDate: function(d,m,y) {
    var s = this.dateFormat;
    s = s.replace('dd','<e>');
    s = s.replace('d','<d>');
    s = s.replace('<e>',this.padZero(d));
    s = s.replace('<d>',d);
    s = s.replace('mmmm','<p>');
    s = s.replace('mmm','<o>');
    s = s.replace('mm','<n>');
    s = s.replace('m','<m>');
    s = s.replace('<m>',m+1);
    s = s.replace('<n>',this.padZero(m+1));
    s = s.replace('<o>',nv_aryMonth[m]);
    s = s.replace('<p>',nv_aryMS[m]);
    s = s.replace('yyyy',y);
    s = s.replace('yy',this.padZero(y%100));
    s = s.replace('hh',this.hour);
    s = s.replace('xx',this.minute);
    s = s.replace('ss',this.second);
    return s.replace ('yy',this.padZero(y%100));
  },

  close: function(day) {
    this.hide();
    if (day) this.dateSelected = day;
    this.ctlToPlaceValue.value = this.constructDate(this.dateSelected, this.monthSelected, this.yearSelected);
  },
  
  setToday: function() {
    this.dateSelected  = this.dateNow;
    this.monthSelected = this.monthNow;
    this.yearSelected  = this.yearNow;
    this.construct();
  },


  StartDecMonth: function() {                             // Month Pulldown
    this.intervalID1 = setInterval('popCalendar.decMonth()',80);
  },
  StartIncMonth: function() {
    this.intervalID1 = setInterval('popCalendar.incMonth()',80);
  },
  incMonth: function() {
    this.monthSelected++;
    if (this.monthSelected > 11) {this.monthSelected = 0; this.yearSelected++;}
    this.construct();
  },
  decMonth: function() {
    this.monthSelected--;
    if (this.monthSelected < 0) {this.monthSelected = 11; this.yearSelected--;}
    this.construct();
  },
  constructMonth: function() {
    this.popDownYear();
    var s = '';
    for (i=0; i<12; i++) {
      var sName = nv_aryMonth[i];
      if (i == this.monthSelected) sName = '<strong>' + sName + '</strong>';
      s += '<li><a href="javascript:void(0);" onclick="popCalendar.monthSelected=' + i + ';popCalendar.construct();popCalendar.popDownMonth();event.cancelBubble=true">' + sName + '</a></li>';
    }
    this.om.innerHTML = '<ul onmouseover="clearTimeout(popCalendar.timeoutID1)" onmouseout="clearTimeout(popCalendar.timeoutID1);popCalendar.timeoutID1=setTimeout(\'popCalendar.popDownMonth()\',100);event.cancelBubble=true">' + s + '</ul>';
  },
  popUpMonth: function() {
    var leftOffset;
    if (this.visMonth == 1) {
      this.popDownMonth();
      this.visMonth--;
    } else {
      this.constructMonth();
      this.om.style.visibility = 'visible';
      leftOffset = parseInt(this.o.style.left) + this.$('pcIDMonth').offsetLeft;
      if (this.ie) leftOffset += 1;
      this.om.style.left = leftOffset + 'px';
      this.om.style.top = parseInt(this.o.style.top) + 19 + 'px';
      if (this.hideElements) {
        this.hideElement('SELECT', this.om);
        this.hideElement('APPLET', this.om);
      }
      this.visMonth++;
    }
  },
  popDownMonth: function() {
    this.om.style.visibility = 'hidden';
    this.visMonth = 0;
  },


  incYear: function() {                                   // Year Pulldown
    for (var i=0; i<7; i++) {
      var newYear = (i + this.nStartingYear) + 1;
      this.$('pcY'+i).innerHTML = (newYear == this.yearSelected) ? '<strong>'+newYear+'</strong>' : newYear;
    }
    this.nStartingYear++; this.bShow=true;
  },
  decYear: function() {
    for (var i=0; i<7; i++) {
      var newYear = (i + this.nStartingYear) - 1;
      this.$('pcY'+i).innerHTML = (newYear == this.yearSelected) ? '<strong>'+newYear+'</strong>' : newYear;
    }
    this.nStartingYear--; this.bShow=true;
  },
  selectYear: function(nYear) {
    this.yearSelected = parseInt(nYear + this.nStartingYear);
    this.yearConstructed = false;
    this.construct();
    this.popDownYear();
  },
  constructYear: function() {
    this.popDownMonth();
    var s = '';
    if (!this.yearConstructed) {
      s = '<li><a href="javascript:void(0);" onmouseout="clearInterval(popCalendar.intervalID1);" onmousedown="clearInterval(popCalendar.intervalID1);popCalendar.intervalID1=setInterval(\'popCalendar.decYear()\',30)" onmouseup="clearInterval(popCalendar.intervalID1)">-</a></li>';
      var j = 0;
      this.nStartingYear = this.yearSelected - 3;
      for ( var i = (this.yearSelected-3); i <= (this.yearSelected+3); i++ ) {
        var sName = i;
        if (i == this.yearSelected) sName = '<strong>' + sName + '</strong>';
        s += '<li><a href="javascript:void(0);" id="pcY' + j + '" onclick="popCalendar.selectYear('+j+');event.cancelBubble=true">' + sName + '</a></li>';
        j++;
      }
      s += '<li><a href="javascript:void(0);" onmouseout="clearInterval(popCalendar.intervalID2);" onmousedown="clearInterval(popCalendar.intervalID2);popCalendar.intervalID2=setInterval(\'popCalendar.incYear()\',30)" onmouseup="clearInterval(popCalendar.intervalID2)">+</a></li>';
      this.oy.innerHTML = '<ul onmouseover="clearTimeout(popCalendar.timeoutID2)" onmouseout="clearTimeout(popCalendar.timeoutID2);popCalendar.timeoutID2=setTimeout(\'popCalendar.popDownYear()\',100)">' + s + '</ul>';

      this.yearConstructed = true;
    }
  },
  popDownYear: function() {
    clearInterval(this.intervalID1);
    clearTimeout(this.timeoutID1);
    clearInterval(this.intervalID2);
    clearTimeout(this.timeoutID2);
    this.oy.style.visibility= 'hidden';
    this.visYear = 0;
  },
  popUpYear: function() {
    var leftOffset;
    if (this.visYear==1) {
      this.popDownYear();
      this.visYear--;
    } else {
      this.constructYear();
      this.oy.style.visibility = 'visible';
      leftOffset = parseInt(this.o.style.left) + this.$('pcIDYear').offsetLeft;
      if (this.ie) leftOffset += 1;
      this.oy.style.left = leftOffset + 'px';
      this.oy.style.top = parseInt(this.o.style.top) + 19 + 'px';
      this.visYear++;
    }
  },

  WeekNbr: function(n) {                                  // construct calendar
    // Algorithm used from Klaus Tondering's Calendar document (The Authority/Guru)
    // http://www.tondering.dk/claus/calendar.html

    if (n == null) n = new Date (this.yearSelected,this.monthSelected,1);
    var year = n.getFullYear();
    var month = n.getMonth() + 1;
    if (this.startAt == 0) {
      var day = n.getDate() + 1;
    } else {
      var day = n.getDate();
    }

    var a = Math.floor((14-month) / 12);
    var y = year + 4800 - a;
    var m = month + 12 * a - 3;
    var b = Math.floor(y/4) - Math.floor(y/100) + Math.floor(y/400);
    var J = day + Math.floor((153 * m + 2) / 5) + 365 * y + b - 32045;
    var d4 = (((J + 31741 - (J % 7)) % 146097) % 36524) % 1461;
    var L = Math.floor(d4 / 1460);
    var d1 = ((d4 - L) % 365) + L;
    var week = Math.floor(d1/7) + 1;
    return week;
  },

  construct : function() {
    var aNumDays = Array (31,0,31,30,31,30,31,31,30,31,30,31);
    var startDate = new Date (this.yearSelected,this.monthSelected,1);
    var endDate;

    if (this.monthSelected==1) {                          // get days of February
      endDate = new Date (this.yearSelected,this.monthSelected+1,1);
      endDate = new Date (endDate - (24*60*60*1000));
      var numDaysInMonth = endDate.getDate();
    } else {
      var numDaysInMonth = aNumDays[this.monthSelected];
    }

    var dayPointer = startDate.getDay() - this.startAt;

    if (dayPointer<0) dayPointer = 6;

    var s = '<table><thead><tr>';                         // dayheader

    if (this.showWeekNumber) {                            // spacer for Weeknumbers
      s += '<th><acronym title="'+nv_weekString+'">' + nv_weekShortString + '</acronym></th>';
    }

    for (var i = 0; i<7; i++) {                           // render daynames
      if (this.showDayLetter)
        s += '<th>' + nv_aryDayNS[(i+this.startAt) % 7].charAt(0) + '</th>';
      else
        s += '<th>' + nv_aryDayNS[(i+this.startAt) % 7] + '</th>';
    }

    s += '</tr></thead><tbody><tr>';

    if (this.showWeekNumber) {
      s += '<td class="pcWeekNumber">' + this.WeekNbr(this.startDate) + '</td>';
    }

    for ( var i=1; i<=dayPointer; i++ ) {
      s += '<td>&nbsp;</td>';
    }

    for (var datePointer=1; datePointer <= numDaysInMonth; datePointer++) {
      dayPointer++;
      var sClass = '';
      var selDayAction = '';
      var sHint = '';

      for (var k=0; k < this.HolidaysCounter; k++) {      // insert holidays
        if ((parseInt(this.Holidays[k].d) == datePointer)&&(parseInt(this.Holidays[k].m) == (this.monthSelected+1))) {
          if ((parseInt(this.Holidays[k].y)==0)||((parseInt(this.Holidays[k].y)==this.yearSelected)&&(parseInt(this.Holidays[k].y)!=0))) {
            sClass = 'pcDayHoliday ';
            sHint += sHint=="" ? this.Holidays[k].desc : "\n"+this.Holidays[k].desc;
          }
        }
      }
      sHint = sHint.replace('/\"/g', '&quot;');

      if ((datePointer == this.odateSelected) && (this.monthSelected == this.omonthSelected) && (this.yearSelected == this.oyearSelected)) {
        sClass+='pcDaySelected';                          // selected day
      } else if ((datePointer == this.dateNow) && (this.monthSelected == this.monthNow) && (this.yearSelected == this.yearNow)) {
        sClass+='pcToday';                                // today
      } else if (
        (dayPointer % 7 == (this.startAt * -1)+1)
        || ((dayPointer % 7 == (this.startAt * -1)+7 && this.startAt==1) || (dayPointer % 7 == this.startAt && this.startAt==0)) )
      {
        sClass+='pcWeekend';                              // sunday
      } else {
        sClass+='pcDay';                                  // every other day
      }

      if (!this.enablePast &&
        ( this.yearSelected < this.yearNow ||
         (this.yearSelected == this.yearNow && this.monthSelected <  this.monthNow) ||
         (this.yearSelected == this.yearNow && this.monthSelected == this.monthNow && datePointer < this.dateNow)
        )) {
        sClass+='Past';                                   // all CSS-classes with past-style are suffixed with "Past":
      } else {
        selDayAction = 'href="javascript:void(0);" onclick="javascript:popCalendar.close('+datePointer+');"';
      }

                                                          // create HTML:
      s += '<td class="' + sClass + '"><a title="'+sHint+'" '+selDayAction+'>'+datePointer+'</a></td>';

      if ((dayPointer+this.startAt) % 7 == this.startAt) {
        s += '</tr>';
        if (datePointer < numDaysInMonth) {
          s += '<tr>';                                    // open next table row, if we are not at the and of actual month
          if (this.showWeekNumber) {
            s += '<td class="pcWeekNumber">' + (this.WeekNbr(new Date(this.yearSelected,this.monthSelected,datePointer+1))) + '</td>';
          }
        }
      }
    }

    if (dayPointer % 7 != 0) s += '</tr>';                // close last opened table row
    s+='</tbody></table>';

    this.$('pcIDcontent').innerHTML = s;
    this.$('pcIDMonth').innerHTML   = '<a href="javascript:void(0)">' + nv_aryMonth[this.monthSelected] + '</a>';
    this.$('pcIDYear').innerHTML    = '<a href="javascript:void(0)">' + this.yearSelected + '</a>';
  },

  /**
   * Main function, shows the calendar.
   *
   * @param btn    The "button" which (de-)activates the calendar
   * @param ctl    The field to receive the selected date; element or ID-string
   * @param format Format of the date-string;  optional; see constructDate()
   * @param past   allow dates in the past;    optional; yes=true, no=false
   * @param x      x-position of the calendar; optional; -1 for directly below btn
   * @param y      y-position of the calendar; optional; -1 for directly below btn
   * @param start  Start of the week;          optional; Monday=1 or Sunday=0
   */
  show: function(btn, ctl, format, past, x, y, start) {
    if (start != null) this.startAt = start;
    if (!format) format = nv_formatString;
    if (!btn) btn = this;
    if (!ctl) ctl = btn;
    
    this.enablePast = (past != null) ? past : this.enablePast;
    this.fixedX = (x != null) ? x : -1;
    this.fixedY = (y != null) ? y : -1;
    if (this.showToday)
      this.$('pcIDfooter').innerHTML = nv_todayString+' <a title="'+nv_gotoString+'" href="javascript:void(0);" onclick="javascript:popCalendar.setToday();">'+ nv_aryDayName[(this.today.getDay()) % 7]+', '+this.dateNow+' '+nv_aryMS[this.monthNow]+' '+this.yearNow+'</a>';
    this.popUp(btn, ctl, format);
  },

  popUp: function(btn, ctl, format) {
    var formatChar = '';
    var aFormat = new Array();
    if (typeof(btn)=='string') btn = this.$(btn);
    if (typeof(ctl)=='string') ctl = this.$(ctl);

    if (this.bPageLoaded) {
      if (this.o.style.visibility == 'hidden') {
        this.ctlToPlaceValue = ctl;
        this.dateFormat = format || this.dateFormat;
        formatChar = ' ';
        aFormat = this.dateFormat.split(formatChar);
        if (aFormat.length < 3) {
          formatChar = '/';
          aFormat = this.dateFormat.split(formatChar);
          if (aFormat.length < 3) {
            formatChar = '.';
            aFormat = this.dateFormat.split(formatChar);
            if (aFormat.length < 3) {
              formatChar = '-';
              aFormat = this.dateFormat.split(formatChar);
              if (aFormat.length < 3) {
                formatChar = '';                          // invalid date format
              }
            }
          }
        }

        var tokensChanged = 0;
        var aData;
        if (formatChar != "") {
          aData = ctl.value.split(formatChar);            // use user's date

          for (var i=0; i<3; i++) {
            if ((aFormat[i] == "d") || (aFormat[i] == "dd")) {
              this.dateSelected = parseInt(aData[i], 10);
              tokensChanged++;
            } else if ((aFormat[i] == "m") || (aFormat[i] == "mm")) {
              this.monthSelected = parseInt(aData[i], 10) - 1;
              tokensChanged++;
            } else if (aFormat[i] == "yyyy") {
              this.yearSelected = parseInt(aData[i], 10);
              tokensChanged++;
            } else if (aFormat[i] == "mmm") {
              for (j=0; j<12; j++) {
                if (aData[i] == nv_aryMonth[j]) {
                  this.monthSelected=j;
                  tokensChanged++;
                }
              }
            } else if (aFormat[i] == "mmmm") {
              for (j=0; j<12; j++) {
                if (aData[i] == nv_aryMS[j]) {
                  this.monthSelected = j;
                  tokensChanged++;
                }
              }
            }
          }
        }
        var TimeFormatChar = ':';
        var timeString = ctl.value.split(" ");
        if (timeString[1] !=null) {
          var timeTokens = timeString[1].split(':');
          if(timeTokens[0].length==2) {
            this.hour = timeTokens[0];
          }
          if (timeTokens[1].length==2) {
            this.minute = timeTokens[1];
          }
          if (timeTokens[2].length==2) {
            this.second= timeTokens[2];
          }
        } else {
          this.hour=00;
          this.minute=00;
          this.second=00;
        }

        if ((tokensChanged != 3) ||
          isNaN(this.dateSelected) ||
          isNaN(this.monthSelected) ||
          this.monthSelected<0 ||
          isNaN(this.yearSelected)) {
          this.dateSelected  = this.dateNow;
          this.monthSelected = this.monthNow;
          this.yearSelected  = this.yearNow;
        }

        this.odateSelected  = this.dateSelected;
        this.omonthSelected = this.monthSelected;
        this.oyearSelected  = this.yearSelected;

        if (typeof jQuery != 'undefined') {               // use jQuery if available
          this.o.style.top = (this.fixedY == -1) ? $(btn).position().top  + btn.offsetHeight + 'px' : this.fixedY + 'px' ;
          this.o.style.left= (this.fixedX == -1) ? $(btn).position().left + 'px' : this.fixedX + 'px';
        } else {
          this.o.style.top = (this.fixedY == -1) ? btn.offsetTop  + this.getTop(btn.offsetParent) + btn.offsetHeight + 2 + 'px' : this.fixedY + 'px' ;
          this.o.style.left= (this.fixedX == -1) ? btn.offsetLeft + this.getLeft(btn.offsetParent) + 'px' : this.fixedX + 'px';
        }
        
        this.construct (1, this.monthSelected, this.yearSelected);
        this.o.style.visibility = "visible";
        if (this.hideElements) {
          this.hideElement('SELECT', this.$('pcIDcalendar'));
          this.hideElement('APPLET', this.$('pcIDcalendar'));
        }
        this.bShow = true;
      } else {
        popCalendar.hide();
        if (this.ctlNow!=btn) this.popUp(btn, ctl, format);
      }
      this.ctlNow = btn;
    }
  }
}

popCalendar.init();
