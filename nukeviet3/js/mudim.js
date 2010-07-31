/*----------------------------------------------------------------------------
  CHIM - CHuoi's Input Method
----------------------------------------------------------------------------
  copyright         : (C) 2005, 2006, 2007 by Dao Hai Lam/  website           : 
  http://xvnkb.sf.net/chim
  email             : daohailam<at>yahoo<dot>com
  last modify       : Thu, 05 Jul 2007 23:07:22 +0700
  version           : 0.9.3
----------------------------------------------------------------------------
 Mudim - Mudzot's Input Method
 (c)2008 by Mudzot
 http://code.google.com/p/mudim
 email: mudzot<at>gmail.com
 version: 0.8
 date: 29.05.08
----------------------------------------------------------------------------
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
-------------------------------------------------------------------------------*/

// Development environment: Firefox with Firebug extension with console debug
if (typeof(console)=='undefined') {	//if console doesnt exist, use the annoying alert instead
	console=function () {return this;};	// console.debug   <--- instruct the build script remove this line
	console.debug = function (a) {alert(a)};		
}

//----------------------------------------------------------------------------
// Class: CHIM
//----------------------------------------------------------------------------
CHIM = function() {
	return this;
};
//----------------------------------------------------------------------------
// Class Mudim
//----------------------------------------------------------------------------
Mudim = function() {
	return this;
};
//----------------------------------------------------------------------------
// Constants:
//----------------------------------------------------------------------------
Mudim.DISPLAY_ID=['mudim-off','mudim-vni','mudim-telex','mudim-viqr','mudim-mix','mudim-auto'];
Mudim.SPELLCHECK_ID='mudim-checkspell';
Mudim.ACCENTRULE_ID='mudim-accentrule';
CHIM.CHAR_A = 'A';
CHIM.CHAR_a = 'a';
CHIM.CHAR_E = 'E';
CHIM.CHAR_e = 'e';
CHIM.CHAR_U = 'U';
CHIM.CHAR_u = 'u';
CHIM.CHAR_G = 'G';
CHIM.CHAR_g = 'g';
CHIM.CHAR_Q = 'Q';
CHIM.CHAR_q = 'q';
CHIM.CHAR_y = 'y';
CHIM.CHAR_Y = 'Y';
CHIM.CHAR_i = 'i';
CHIM.CHAR_I = 'I';
CHIM.CHAR_0x80 = String.fromCharCode(0x80);
//----------------------------------------------------------------------------
CHIM.vowels = "AIUEOYaiueoy";
CHIM.separators = " !@#$%^&*()_+=-{}[]|\\:\";'<>?,./~`\r\n\t";
//----------------------------------------------------------------------------
CHIM.off = 0;
CHIM.buffer = [];
CHIM.dirty = false;
//----------------------------------------------------------------------------
// Function: CHIM.CharIsUI
//	Checking if given character is in U & I set or not
//
// Parameters:
//	u - given char
//
// Returns:
//	True - if u is in [U,I]
//----------------------------------------------------------------------------
CHIM.CharIsUI = function(u) {
	var n, UI = CHIM.UI;
	u = u.charCodeAt(0);
	for ( n = 0; UI[n] != 0 && UI[n] != u; n++ ) {}
	return UI[n] != 0 ? n : -1;
};
//----------------------------------------------------------------------------
// Function: CHIM.CharIsO
//	Checking if given character is O or not
// Parameters:
//	u: the char
//----------------------------------------------------------------------------
CHIM.CharIsO = function(u) {
	var n, O = CHIM.O;
	u = u.charCodeAt(0);
	for ( n = 0; O[n] != 0 && O[n] != u; n++ ){}
	return O[n] != 0 ? n : -1;
};
//----------------------------------------------------------------------------
// Function: CHIM.CharPriorityCompare
//	Compare 2 chars using VNese priority table
//----------------------------------------------------------------------------
CHIM.CharPriorityCompare = function(u1, u2) {
	var VN = CHIM.VN;
	var n, i = -1, j = -1, u;
	for ( n = 0, u = u1.charCodeAt(0); VN[n] != 0 && VN[n] != u; n++ ){}
	if ( VN[n] != 0 ) {i = n;}
	for ( n = 0, u = u2.charCodeAt(0); VN[n] != 0 && VN[n] != u; n++ ){}
	if ( VN[n] ) {j = n;}
	return i - j;
};
//----------------------------------------------------------------------------
// Function: CHIM.SetCharAt
//----------------------------------------------------------------------------
CHIM.SetCharAt = function( n, c ) {
	CHIM.buffer[n] = String.fromCharCode( c );
};
//----------------------------------------------------------------------------
// Class: CHIM.Speller
//----------------------------------------------------------------------------
CHIM.Speller = function() {
	return this;
};
//----------------------------------------------------------------------------
CHIM.Speller.enabled = true;
CHIM.Speller.position = 0;
CHIM.Speller.count = 0;
CHIM.Speller.vowels = [];
CHIM.Speller.lasts = [];
//----------------------------------------------------------------------------
CHIM.Speller.Toggle = function() {
	CHIM.Speller.enabled = !CHIM.Speller.enabled;
	Mudim.SetPreference();
};
//----------------------------------------------------------------------------
CHIM.Speller.Set = function(position, key) {
	CHIM.Speller.vowels[CHIM.Speller.count] = CHIM.Speller.position;
	CHIM.Speller.lasts[CHIM.Speller.count++] = key;
	CHIM.Speller.position = position;
};
//----------------------------------------------------------------------------
CHIM.Speller.Clear = function() {
	CHIM.Speller.position = -1;
	CHIM.Speller.count = 0;
};
//----------------------------------------------------------------------------
CHIM.Speller.Last = function() {
	return CHIM.Speller.lasts[CHIM.Speller.count - 1];
};
Mudim.consonants = "BCDFGHJKLMNPQRSTVWXZbcdfghjklmnpqrstvwxz";
Mudim.spchk = "AIUEOYaiueoy|BDFJKLQSVWXZbdfjklqsvwxz|'`~?.^*+=";
Mudim.vwchk = "|oa|uy|ue|oe|ou|ye|ua|uo|ai|ui|oi|au|iu|ia|eu|ie|ao|eo|ay|uu|io|yu|";
Mudim.nvchk = "FfJjWwZz";
Mudim.separators = "!@#$%^&*()_+=-{}[]|\\:\";'<>?,./~`";
Mudim.tailConsonantsPattern = '|c|ch|p|t|m|n|ng|nh|';
/**
* Spell checking (if necessary)
* If spelling error detected, assign the error index in buffer to CHIM.off
* @param (char) key Current key
* @param (int) Accent group, 0 means non-accent key
* @return true if spelling error detected
*/
Mudim.CheckSpell = function(key, grp) {
	var b = CHIM.buffer;
	var len = b.length;
	var n = key.toLowerCase();
	if (CHIM.Speller.enabled && !Mudim.tempDisableSpellCheck) {
		//console.debug('Begin CheckSpell');
		// Rule based on the ending consonants
		if (grp > 0 && CHIM.off == 0) {
			if (Mudim.tailConsonants.length>0) {
				var ecIdx=Mudim.tailConsonantsPattern.indexOf('|'+Mudim.tailConsonants+'|');
				if (ecIdx<0) {		// spelling rule as described in Issue #16 comment #1
					CHIM.off = len;
					Mudim.tailConsonants='';
					//console.debug('Tail consonants not in pattern, just append');
					return true;
				} else if (ecIdx<9 && grp==2) {
					var typeid = Mudim.GetMarkTypeID(n,2);
					if (typeid !=0 && typeid!=1 && typeid!=5) {
						CHIM.off = len;
						Mudim.tailConsonants='';
						//console.debug('Tail consonants not in pattern, just append');
						return true;
					}
				}
			}
			
			if (len == 2 && (b[1]==CHIM.CHAR_u || b[1]==CHIM.CHAR_U) && (b[0]==CHIM.CHAR_q || b[0]==CHIM.CHAR_Q) && (grp==2 || (grp==1 && Mudim.GetMarkTypeID(n,1)==1))) {	//spelling rule as described in Issue #16 comment #0
				CHIM.off = len;
				return CHIM.Append(len, c, key);
			}
		} else if ( !CHIM.off ) {
			var kp = Mudim.spchk.indexOf(key);
			if (len>0) {
				var lkey = b[len-1].toLowerCase();
			}
			if ( len==0 ) {
				//console.debug('Buffer empty');
				if ( Mudim.nvchk.indexOf(key) >= 0 ) {
					CHIM.off = -1;
				} else if ( kp >= 0 && kp < 12 ) {
					CHIM.Speller.Set(0, key);
				} else if( kp == 12 || kp > 37 ) {
					return;
				} else {
					CHIM.Speller.Clear();
				}
			} else if( kp == 12 || kp > 37 ) {	// | or `~^.?
				CHIM.ClearBuffer();
				return;
			} else if( kp > 12 ) {		// b, d, f,...
				CHIM.off = len;
			} else if( kp >= 0 ) { // vowels				
				var i = 0;
				while (Mudim.consonants.indexOf(b[i])>=0) {
					i++;
				}
				if (i>0) {
					Mudim.headConsonants = b.slice(0,i).toString().replace(/,/g,'').toLowerCase();
				}
				if ( CHIM.Speller.position < 0 ) {
					if (Mudim.headConsonants == 'q') {	// q must be followed by u
						if (len==1 && n!='u') {
							//console.debug('Q not followed by u: spelling error');
							CHIM.off = len;
						} else if (b[1] == 'u' && n == 'u') {
							//console.debug('QUU: spelling error');
							CHIM.off = len;
						}
					} else if (lkey == 'p' && n!= 'h') {	// p must be followed by h
						//console.debug('P not followed by h: spelling error');
						CHIM.off = len;
					} else if (lkey == 'k' && n != 'i' && n !='e' && n!='y') {
						//console.debug('K not followed by [iey]: spelling error');
						CHIM.off = len;
					} else if (Mudim.headConsonants == 'ngh' && n!='i' && n!='e') {
						//console.debug('NGH not followed by [ie]: spelling error');
						CHIM.off = len;
					} else {
						CHIM.Speller.Set(len, key);
						if (n == 'y') {
							if ('hklmst'.indexOf(lkey) < 0) {
								//console.debug('Y must follow [hklms]: spelling error');
								CHIM.off = len;
							}
						} else if (n=='e' || n=='i') {
							if (len>1 && (lkey=='g')) {
								//console.debug('xg[ie]: spelling error');
								CHIM.off=len;
							}
							if (lkey=='c') {
								//console.debug('c[ie] : spelling error');
								CHIM.off=1;
							}
						}
					}
				} else if( len - CHIM.Speller.position > 1 ) {
					//console.debug('Spelling error');
					CHIM.off = len;
				} else {
					var w = "|"+CHIM.Speller.Last().toLowerCase()+key.toLowerCase()+"|";
					var idx = Mudim.vwchk.indexOf(w);
					if ( idx < 0 ) {
						//console.debug('Vowel composition not found: spelling error');
						CHIM.off = len;
					} else if (idx < 18 && (Mudim.headConsonants == 'c' || Mudim.headConsonants == 'C')){
						//console.debug('C doesnt come with this vowel composition');
						CHIM.off = len;
					} else if (lkey == 'y' && Mudim.headConsonants == '' && n != 'e') {
						//console.debug('y[^e] : spelling error');
						CHIM.off = len;
					} else {
						CHIM.Speller.Set(len,key);
					}
				}
			} else {		// special cases
				switch( key ) {
					case 'h':
					case 'H': // [cgknpt]h
						if( lkey >= CHIM.CHAR_0x80 || "CGKNPTcgknpt".indexOf(lkey) < 0 ) {
							CHIM.off = len;
						}
						break;
					case 'g':
					case 'G': // [n]g
						if( lkey != 'n' && lkey != 'N' ) {
							CHIM.off = len;
						}
						break;
					case 'r':
					case 'R': // [t]r
						if( lkey != 't' && lkey != 'T' ) {
							CHIM.off = len;
						}
						break;
					default:
						if( Mudim.consonants.indexOf(lkey) >= 0 ) {
							CHIM.off = len;
						}
						break;
				}
			}
		}
		if (CHIM.off != 0) {
			return true;
		}
	}
	return false;
};
//----------------------------------------------------------------------------
// Function: CHIM.Append
//	Add key to buffer and check spelling
// Parameters:
//	count: current buffer length
//	lastkey: last key in buffer
//	key: current key
//----------------------------------------------------------------------------
CHIM.Append = function(count, lastkey, key) {
	//console.debug('|%s| (Begin Append)',CHIM.buffer);
	if ( Mudim.separators.indexOf(key) >= 0 ) {
		CHIM.ClearBuffer();
		return;
	}
	Mudim.my='mu';
	CHIM.buffer.push(key);
	return Mudim.AdjustAccent(CHIM.modes[Mudim.method-1][2].charAt(0));
};
//----------------------------------------------------------------------------
// Function: CHIM.AddKey
//	Add key to internal buffer
//
// Parameters:
//	key - the key to add
//
// Returns:
//	-1 - if nothing change in the internal buffer
//	N >= 0 - indicate the position of internal buffer has been changed
// Remarks:
//	Some spell checking with the consonats at the end occur here
//----------------------------------------------------------------------------
CHIM.AddKey = function( key ) {
	var p = -1;
	var i, j = -1;
	var b, c = 0, cc, l;
	var count = CHIM.buffer.length;
	var m = CHIM.modes[ Mudim.method-1 ], n;
	var v = null;
	var autoModeProbe = false;
	//console.debug('|%s| (Begin AddKey)',CHIM.buffer);
	if( !count || CHIM.off != 0 || Mudim.tempOff) {
		if (Mudim.CheckSpell(key,l)) {
			return CHIM.Append(count, c, key);
		}
		return CHIM.Append(0, 0, key);
	}
	b = CHIM.buffer;
	c = b[p = count - 1];
	n = key.toLowerCase();
	for( l = 1; l < m.length; l++ )
		if( m[l].indexOf(n) >= 0 ) {break;}
	if( l >= m.length ) {
		//console.debug('Not mark key, just append to buffer');
		Mudim.CheckSpell(key,0);
		return CHIM.Append(count, c, key);
	}
	if (Mudim.method == 5) {	//Auto detect
		Mudim.method = Mudim.AutoDetectMode(n);
		//console.debug("Method detected: "+Mudim.method);
		autoModeProbe = true;
	}
	if ((p=Mudim.FindAccentPos(n))<0) {
		//console.debug('Appropriate accent position not found, just append');
		if (autoModeProbe) {
			Mudim.method = 5;
			autoModeProbe = false;
		}
		Mudim.CheckSpell(key,0);
		return CHIM.Append(count, c, key);
	}
	//console.debug('Found mark position: %d',p);
	Mudim.lord='dz';
	if (Mudim.CheckSpell(key,l)) {
		if (autoModeProbe) {
			Mudim.method = 5;
			autoModeProbe = false;
		}
		return CHIM.Append(count, c, key);
	}
	c=b[p];
	var x = c.charCodeAt(0);
	var found = false;
	//Actually put the mark on defined position
	if( l == 1 ) {	//group 1
		//console.debug('Mark key group 1 detected');
		m = m[0];
		for( i = 0; !found && i < m.length; i++ ) {
			var k = m[i];
			if( k[0] == n ) {
				for( i = 1; i < k.length; i++ ) {
					v = CHIM.vncode_1[k[i]];
					Mudim.AdjustAccent(n);
					x=b[p].charCodeAt(0);
					if (Mudim.GetMarkTypeID(n,1)==3) {			//Exception of dd, replace the first char
						//console.debug('Receive 2nd d, consider 1st char to put mark');
						p=0;c=b[p];x=c.charCodeAt(0);
					}					
					if (Mudim.PutMark(p,x,1,v,n,true)) {
						//console.debug('Successfully put mark of group 1');
						if (p>0 && Mudim.GetMarkTypeID(n,1)==1 && p<count-1 && CHIM.CharIsO(b[p])>=0 && CHIM.CharIsUI(b[p-1])>=0 && b[0]!=CHIM.CHAR_q && b[0]!=CHIM.CHAR_Q) {		// uox+ when x!=i   ---> u+o+
							//console.debug('uoxw when x!=i : put additional + on u');
							Mudim.PutMark(p-1,b[p-1].charCodeAt(0),1,CHIM.vn_UW,n,false);
						}
						found=true; 
						break;
					}
				}
				break;
			}
		}
	}
	else {	//and group 2
		//console.debug('Mark key group 2 detected');
		for( i = 0; i < CHIM.vncode_2.length; i++ ) {
			v = CHIM.vncode_2[i];
			if (Mudim.PutMark(p,x,2,v,n,true)) {
				//console.debug('Successfully put mark of group 2');
				found=true; 
				break;
			}
		}
	}
	if( !found ) {
		//console.debug('Mark isnt compatible with this position');
		Mudim.CheckSpell(key,0);
		if (autoModeProbe) {
			//console.debug("Change back to method 5");
			Mudim.method = 5;
		}
		autoModeProbe = false;
		return CHIM.Append(count, c, key);
	} else {
		if (autoModeProbe) {
			CHIM.SetDisplay();
		}
		autoModeProbe = false;
	}
	if (CHIM.off!=0) {
		//console.debug('Push the key into buffer due to double mark');
		CHIM.buffer.push(key);
	}
	return p>=0;
};
//----------------------------------------------------------------------------
// Function: CHIM.BackSpace
//	Delete the last char in internal buffer and update Speller status
//----------------------------------------------------------------------------
CHIM.BackSpace = function() {
	var count = CHIM.buffer.length;
	if( count <= 0 ) {
		CHIM.dirty = true;
	} else {
		if (Mudim.accent[0]==count-1) Mudim.ResetAccentInfo();
		var i=CHIM.vn_OW.length-1;
		var code = CHIM.buffer[count-1].charCodeAt(0);
		while (i>=0 && CHIM.vn_OW[i] != code) {		// test if this is o+
			i--;
		}
		if (i<0) {
			i=CHIM.vn_UW.length-1;		
			while (i>=0 && CHIM.vn_UW[i] != code) {		// is this u+ ?
				i--;
			}
		}
		if (i>=0 && (i%2) == 1) {
			//console.debug('Decrease w');
			Mudim.w--;
		}
		--count;
		CHIM.buffer.pop();
		if( count == CHIM.Speller.position ) {
			CHIM.Speller.position = CHIM.Speller.vowels[--CHIM.Speller.count];
		}
		if( (CHIM.off < 0 && !count) || (count <= CHIM.off) ) {
			CHIM.off = 0;
		}
	}
};
//----------------------------------------------------------------------------
// Function: CHIM.ClearBuffer
//	Clear internal buffer & Speller status
//----------------------------------------------------------------------------
CHIM.ClearBuffer = function() {
	CHIM.off = 0;
	Mudim.w=0;
	CHIM.Speller.Clear();
	Mudim.ResetAccentInfo();
	Mudim.tailConsonants='';
	Mudim.headConsonants='';
	Mudim.ctrlSerie = 0;
	Mudim.shiftSerie = 0;
	if (CHIM.buffer.length>0) {
		Mudim.tempOff = false;
		Mudim.tempDisableSpellCheck = false;
	}
	CHIM.buffer = [];
};
//----------------------------------------------------------------------------
// Function: CHIM.SetDisplay
//	Show current status on browser
//----------------------------------------------------------------------------
CHIM.SetDisplay = function() {
	if ( typeof(Mudim.DISPLAY_ID) != "undefined" && Mudim.method < Mudim.DISPLAY_ID.length ) {
		var r;
		//Clear all button. This is needed by only the lazy rendering engine of my Konqueror
		for (var i=0;i<5;i++) {
			r=document.getElementById(Mudim.DISPLAY_ID[i]);
			if (r) {r.checked = false;}
		}
		//Set the selected method on
		 r = document.getElementById( Mudim.DISPLAY_ID[Mudim.method] );
		if ( r ) {r.checked = true;}
	}
	if ( typeof(Mudim.SPELLCHECK_ID) != "undefined" ) {
		var r = document.getElementById( Mudim.SPELLCHECK_ID );
		if ( r ) {r.checked = CHIM.Speller.enabled;}
	}
	if ( typeof(Mudim.ACCENTRULE_ID) != "undefined" ) {
		var r = document.getElementById( Mudim.ACCENTRULE_ID );
		if ( r ) {r.checked = Mudim.newAccentRule;}
	}
};
//----------------------------------------------------------------------------
// Function: CHIM.SwitchMethod
//	Switching to next pecking method
//----------------------------------------------------------------------------
CHIM.SwitchMethod = function() {
	CHIM.ClearBuffer();
	Mudim.method = (++Mudim.method % 6);
	CHIM.SetDisplay();
	Mudim.SetPreference();
};
//----------------------------------------------------------------------------
// Function: CHIM.SetMethod
//	Set pecking method :-)
//
// Parameters:
//	m - value of pecking method
//----------------------------------------------------------------------------
CHIM.SetMethod = function(m) {
	CHIM.ClearBuffer();
	Mudim.method = m;
	CHIM.SetDisplay();
	Mudim.SetPreference();
};
//----------------------------------------------------------------------------
// Function: CHIM.Toggle
//----------------------------------------------------------------------------
CHIM.Toggle = function() {
	var p;
	if (!(p=Mudim.Panel)) {Mudim.InitPanel();}
	if (Mudim.method == 0) {
		CHIM.SetMethod(Mudim.oldMethod);
	}
	else {
		Mudim.oldMethod=Mudim.method;
		CHIM.SetMethod(0);
	}
	Mudim.SetPreference();
};
//----------------------------------------------------------------------------
// Function: CHIM.GetTarget
//	Get the current target which CHIM's pointing to
//
// Parameters:
//	e - the current active event
//
// Returns:
//	The current target
//----------------------------------------------------------------------------
CHIM.GetTarget = function(e) {
	var r;
	if ( e == null ) {
		e = window.event;
	}
	if ( e == null ) {
		return null;
	}
	if ( e.srcElement != null ) {	 // IE
		r = e.srcElement;
	} else {
		r = e.target;
		while ( r && r.nodeType != 1 ) // climb up from text nodes on Moz
			r = r.parentNode;
	}
	if (r.tagName == 'BODY') {
		r = r.parentNode;
	}
	CHIM.peckable = r.tagName=='HTML' || r.type=='textarea' || r.type=='text';

	return r;
};
//----------------------------------------------------------------------------
// Function: CHIM.GetCursorPosition
//----------------------------------------------------------------------------
CHIM.GetCursorPosition = function( target ) {
	if (target == null || target.value == null || target.value.length == 0) {
		return -1;
	}
	// Moz/Opera
	if (typeof(target.selectionStart) != 'undefined') {
		if (target.selectionStart < 0 || target.selectionStart > target.length ||
			target.selectionEnd < 0 || target.selectionEnd > target.length ||
			target.selectionEnd < target.selectionStart) {
			return -1;
		}
		return target.selectionStart;
	}
	// IE
	if (document.selection) {
		var selection = document.selection.createRange();
		var textRange = target.createTextRange();
		// if the current selection is within the edit control
		if (textRange == null || selection == null ||
			((selection.text != "") && textRange.inRange(selection) == false)) {
			return -1;
		}
		if (selection.text == "") {
			var index = 1;
			if ( target.tagName == "INPUT" ) {
				var contents = textRange.text;
				while (index < contents.length) {
					textRange.findText(contents.substring(index));
					if (textRange.boundingLeft == selection.boundingLeft) {break;}
					index++;
				}
			}
			// Handle text areas.
			else if ( target.tagName == "TEXTAREA" ) {
				var caret = document.selection.createRange().duplicate();
				index = target.value.length + 1;
				while (caret.parentElement() == target && caret.move("character", 1) == 1) {
					--index;
					if (target.value.charCodeAt(index) == 10) {index -= 1;}
				}
				if (index == target.value.length + 1) {index = -1;}
			}
			return index;
		}
		return textRange.text.indexOf(selection.text);
	}
};
//----------------------------------------------------------------------------
// Function: CHIM.SetCursorPosition
//----------------------------------------------------------------------------
CHIM.SetCursorPosition = function(target, p) {
	if (p < 0) {return;}
	if (target.setSelectionRange) {
		target.setSelectionRange(p, p);
	} else if (target.createTextRange) {
		var range = target.createTextRange();
		range.collapse(true);
        // odd behaviour each 4th times when a mark is put at the end of line
        var i;
        var dec = 0;
        for (i = 0; i<p; i++) {
            if ((target.value.charCodeAt(i) == 10) || (target.value.charCodeAt(i) == 13)) {
                if (dec == 0) {
                    --p;
                    dec = 1;
                }
            } else {
                dec = 0;
            }
        }
		range.moveStart('character', p);
		range.moveEnd('character', 0);
		range.select();
	}
};
/**
* Update buffer content with the word which appears right before the cursor
* @param  (object) target The target text element
*/
CHIM.UpdateBuffer = function(target) {
	CHIM.ClearBuffer();

	if (target.tagName != 'HTML') {
		var separators = CHIM.separators;
		var c = CHIM.GetCursorPosition( target ) - 1;
		if ( c > 0 ) {
			while ( c >= 0 && separators.indexOf(target.value.charAt(c)) < 0 ) {
				CHIM.buffer.unshift(target.value.charAt(c));
				c = c - 1;
			}
		}
		Mudim.startWordOffset = c + 1;
	}
	else {
		CHIM.buffer = CHIM.HTMLEditor.GetCurrentWord(target).split('');
	}
	CHIM.dirty = false;
};
//----------------------------------------------------------------------------
CHIM.VK_TAB = 9;
CHIM.VK_BACKSPACE = 8;
CHIM.VK_ENTER = 13;
CHIM.VK_DELETE = 46;
CHIM.VK_SPACE = 32;
CHIM.VK_LIMIT = 128;
CHIM.VK_LEFT_ARROW = 37;
CHIM.VK_RIGHT_ARROW = 39;
CHIM.VK_HOME = 36;
CHIM.VK_END = 35;
CHIM.VK_PAGE_UP = 33;
CHIM.VK_PAGE_DOWN = 34;
CHIM.VK_UP_ARROW = 38;
CHIM.VK_DOWN_ARROW = 40;
CHIM.VK_ONOFF = 120;	//F9
CHIM.VK_ONOFF2 = 121;	//F10
CHIM.VK_PANELTOGGLE = 119;	//F8
CHIM.VK_CTRL = 17;
CHIM.VK_SHIFT = 16;
CHIM.VK_ALT = 18;
//----------------------------------------------------------------------------
// Function: ProcessControlKey
//----------------------------------------------------------------------------
CHIM.ProcessControlKey = function(keyCode, release) {
	switch ( keyCode ) {
		case CHIM.VK_TAB:
		case CHIM.VK_ENTER:
			CHIM.ClearBuffer();
			break;
		case CHIM.VK_BACKSPACE:
			if (!release) {CHIM.BackSpace();}
			break;
		case CHIM.VK_DELETE:
		case CHIM.VK_LEFT_ARROW:
		case CHIM.VK_RIGHT_ARROW:
		case CHIM.VK_HOME:
		case CHIM.VK_END:
		case CHIM.VK_PAGE_UP:
		case CHIM.VK_PAGE_DOWN:
		case CHIM.VK_UP_ARROW:
		case CHIM.VK_DOWN_ARROW:
			CHIM.dirty = true;
			break;
	}
};
//----------------------------------------------------------------------------
// Function: IsHotkey
//	Check if key pressed is a CHIM hotkey or not
//
// Parameters:
//	e - the event object
//	k - the value of key
//
// Returns:
//	True - if k is a hotkey
//
// See also:
//	<CHIM.SwitchMethod>
//----------------------------------------------------------------------------
CHIM.IsHotkey = function(e, k) {
	if ( k == CHIM.VK_PANELTOGGLE ) {
		Mudim.TogglePanel();return true;
	} else if (k == CHIM.VK_ONOFF || k == CHIM.VK_ONOFF2) {
		CHIM.Toggle();
		return true;
	}
	return false;
};
//----------------------------------------------------------------------------
// Class: CHIM.HTMLEditor
//----------------------------------------------------------------------------
CHIM.HTMLEditor = function() {
	return this;
};
//----------------------------------------------------------------------------
// Function: CHIM.HTMLEditor.GetRange
//----------------------------------------------------------------------------
CHIM.HTMLEditor.GetRange = function(target) {
	if (!target.parentNode.iframe) {return;}
	var win = target.parentNode.iframe.contentWindow;
	return (!window.opera && document.all) ?
			win.document.selection.createRange() :
			win.getSelection().getRangeAt(0);
};
//----------------------------------------------------------------------------
// Function: CHIM.HTMLEditor.GetCurrentWord
//----------------------------------------------------------------------------
CHIM.HTMLEditor.GetCurrentWord = function(target) {
	var range = CHIM.HTMLEditor.GetRange(target);
	if (!range) {return '';}
	if (!window.opera && document.all) {
		while (range.moveStart('character', -1) == -1) {
			if (CHIM.separators.indexOf(range.text.charAt(0)) >= 0) {
				range.moveStart('character', 1);
				break;
			}
		}
		return range.text;
	}

	var word = '';
	var s;
	if (!(s = range.startContainer.nodeValue)) {return '';}
	var c = range.startOffset - 1;
	if (c > 0) {
		while ( c >= 0 && CHIM.separators.indexOf(s.charAt(c)) < 0 && s.charCodeAt(c)!=160) {	//It's so strange that space character appears to have code 160 in iframe. But issue 7 fixed
			word = s.charAt(c) + word;
			c = c - 1;
		}
	}
	return word;
};
//----------------------------------------------------------------------------
// Function: CHIM.HTMLEditor.Process
//----------------------------------------------------------------------------
CHIM.HTMLEditor.Process = function(target, l) {
	var range = CHIM.HTMLEditor.GetRange(target);
	if (typeof(range)=='undefined') {
		return;
	}
	var b = CHIM.buffer;
	if (!window.opera && document.all) {
		var x = -l;
		range.moveStart('character', x);
		range.moveEnd('character', x+b.length);
		range.pasteHTML(b.toString().replace(/,/g,''));
		return;
	}
	var container = range.startContainer;
	var offset = range.startOffset;
	var start = offset - l;
	container.nodeValue = container.nodeValue.substring(0, start) +
		b.toString().replace(/,/g,'') + container.nodeValue.substring(start + l);
	if (l<b.length) {offset++;}
	range.setEnd(container, offset);
	range.setStart(container, offset);
};
//----------------------------------------------------------------------------
// Function: CHIM.Freeze
//----------------------------------------------------------------------------
CHIM.Freeze = function(target) {
	/*var ign = Mudim.IGNORE_ID;
	if (ign.length > 0) {
		for ( var i = 0; i < ign.length; i++ ) {
			if( target.id == ign[i] ) {return true;}
		}
	}
	return false;*/
	var ign = Mudim.IGNORE_ID;
	return ( ign.test( target.id ) ) ? true : false;
};
//----------------------------------------------------------------------------
// Function: CHIM.KeyHandler
//	Handle key press event
//----------------------------------------------------------------------------
CHIM.KeyHandler = function(e) {
	if ( e == null ) {e = window.event;}
	if (e.isHandled==true) {return;}
	e.isHandled=true;
	var keyCode = e.keyCode;
	if ( keyCode == 0 ) {	// as it might on Moz
		keyCode = e.charCode;
	}
	if ( keyCode == 0 ) {	// unlikely to get here
		keyCode = e.which;
	}
	if ( Mudim.method == 0 ) {return;}
	var target = null;
	if ( !(target = CHIM.GetTarget(e)) || !CHIM.peckable || CHIM.Freeze(target) ) {return;}
	if ( e.ctrlKey || e.ctrlLeft || e.metaKey) {
		if (keyCode == CHIM.VK_BACKSPACE || keyCode == CHIM.VK_LEFT_ARROW || keyCode == CHIM.VK_RIGHT_ARROW) {
			CHIM.dirty = true;
		}
		return;
	}
	if ( e.charCode == null || e.charCode != 0 ) { // process ASCII only
		var key = String.fromCharCode(keyCode);
		if ( keyCode == CHIM.VK_SPACE || keyCode == CHIM.VK_ENTER ) {
			CHIM.ClearBuffer();
		} else if ( keyCode > CHIM.VK_SPACE && keyCode < CHIM.VK_LIMIT ) {
			if ( CHIM.dirty ) {
				CHIM.UpdateBuffer( target );
			}
			var l = CHIM.buffer.length;
			if (l==0) {
				Mudim.startWordOffset=CHIM.GetCursorPosition(target);
			}
			if (Mudim.newTempDisableSpellCheckRequest) {
				CHIM.ClearBuffer();
				Mudim.startWordOffset=CHIM.GetCursorPosition(target);
				Mudim.newTempDisableSpellCheckRequest = false;
			}
			if (CHIM.AddKey(key) ) {
				if (e.stopPropagation) {e.stopPropagation();}
				if (e.preventDefault) {e.preventDefault();}
				e.cancelBubble = true;
				e.returnValue = false;
				Mudim.UpdateUI(target,l);
			}
		}
		else {
			CHIM.dirty = true;
		}
	}
	else { // process control key
		CHIM.ProcessControlKey( keyCode, true );
	}
};
/**
*Catch keyup event to implement escaping Mudim for current word
*@param (object) e Event passed by browser
*/
CHIM.KeyUp = function(e) {
	if ( e == null ) {e = window.event;}
	if ( e.keyCode == CHIM.VK_SHIFT ) {
		if (Mudim.shiftSerie == 1) {
			//console.debug('Temporarily turn off');
			Mudim.tempOff = true;
			Mudim.shiftSerie = 0;
		}
	}
	if ( e.keyCode == CHIM.VK_CTRL ) {
		if (Mudim.ctrlSerie == 1) {
			//console.debug('Temporarily disable spell checking');
			Mudim.tempDisableSpellCheck = true;
			Mudim.ctrlSerie = 0;
			Mudim.newTempDisableSpellCheckRequest = true;
		}
	}
	//console.debug('ctrl: '+Mudim.ctrlSerie+' || tempDis: '+Mudim.tempDisableSpellCheck);
};
//----------------------------------------------------------------------------
// Function: KeyDown
//	Handle the key down event
//
// Parameters:
//	e - event which is passed by some browsers
//----------------------------------------------------------------------------
CHIM.KeyDown = function(e) {
	var target = null;
	if ( e == null ) {e = window.event;}
	if (CHIM.IsHotkey(e,e.keyCode)) {return;}
	if (e.altKey || e.altLeft) {
		return;
	}
	if ( e.shiftKey || e.shiftLeft || e.metaKey ) {
		Mudim.shiftSerie |= 1;		//Shift pressed
		if (e.keyCode != CHIM.VK_SHIFT) {	// Shift-x
			Mudim.shiftSerie |= 2;	
		}
		return;
	}
	if ( e.ctrlKey || e.ctrlLeft || e.metaKey ) {
		Mudim.ctrlSerie |= 1;		//Ctrl pressed
		if (e.keyCode != CHIM.VK_CTRL) {	// Ctrl-x
			Mudim.ctrlSerie |= 2;	
		}
		return;
	}
	if ( !(target = CHIM.GetTarget(e)) || !CHIM.peckable || CHIM.Freeze(target) ) {return;}
	var keyCode = e.keyCode;
	if ( keyCode == 0 ) {	// as it might on Moz
		keyCode = e.charCode;
	}
	if ( keyCode == 0 ) {	// unlikely to get here
		keyCode = e.which;
	}
	CHIM.ProcessControlKey( keyCode, false );
};
//----------------------------------------------------------------------------
// Function: MouseDown
//	Handle the mouse down event
//----------------------------------------------------------------------------
CHIM.MouseDown = function(e) {
	CHIM.Activate();
	CHIM.dirty = true;
};
//----------------------------------------------------------------------------
// Function: Attach
//	Attach CHIM to an element
//
// Parameters:
//	e - element to attach
//	r - boolean value indicates that CHIM functions will replace the
//		default handlers
//----------------------------------------------------------------------------
CHIM.Attach = function(e, r) {
	if (!e) {return;}

	if (!e.chim) {
			if (!r) {
				if (!window.opera && document.all) { // IE
					e.attachEvent('onkeydown', CHIM.KeyDown);
					e.attachEvent('onkeyup', CHIM.KeyUp);
					e.attachEvent('onkeypress', CHIM.KeyHandler);
					e.attachEvent('onmousedown', CHIM.MouseDown);
				}
				else { // Moz & others
					e.addEventListener('keydown', CHIM.KeyDown, false);
					e.addEventListener('keyup', CHIM.KeyUp, false);
					e.addEventListener('keypress', CHIM.KeyHandler, false);
					e.addEventListener('mousedown', CHIM.MouseDown, false);
				}
			}
			else {
				e.onkeydown = CHIM.KeyDown;
				e.onkeyup = CHIM.KeyUp;
				e.onkeypress = CHIM.KeyHandler;
				e.onmousedown = CHIM.MouseDown;
			}
		e.chim = true;
	}

	var f = e.getElementsByTagName('iframe');
	for (var i = 0; i < f.length; i++) {
		var doc = (!window.opera && document.all) ?
				f[i].contentWindow.document : f[i].contentDocument;
		try {
			doc.iframe = f[i];
			CHIM.Attach(doc, false);
		} catch(e) {}
	}
	var f = e.getElementsByTagName('frame');
	for (var i = 0; i < f.length; i++) {
		var doc = (!window.opera && document.all) ?
				f[i].contentWindow.document : f[i].contentDocument;
		try {
			doc.iframe = f[i];
			CHIM.Attach(doc, false);
		} catch(e) {}
	}
};
//----------------------------------------------------------------------------
// Function: CHIM.Activate
//----------------------------------------------------------------------------
CHIM.Activate = function() {
	try {
		CHIM.Attach(document, true);
		CHIM.SetDisplay();
	} catch (exc) {
	}
};
//----------------------------------------------------------------------------
//  Code tables
//----------------------------------------------------------------------------
CHIM.vn_A0=[65,193,192,7842,195,7840];
CHIM.vn_a0=[97,225,224,7843,227,7841];
CHIM.vn_A6=[194,7844,7846,7848,7850,7852];
CHIM.vn_a6=[226,7845,7847,7849,7851,7853];
CHIM.vn_A8=[258,7854,7856,7858,7860,7862];
CHIM.vn_a8=[259,7855,7857,7859,7861,7863];
CHIM.vn_O0=[79,211,210,7886,213,7884];
CHIM.vn_o0=[111,243,242,7887,245,7885];
CHIM.vn_O6=[212,7888,7890,7892,7894,7896];
CHIM.vn_o6=[244,7889,7891,7893,7895,7897];
CHIM.vn_O7=[416,7898,7900,7902,7904,7906];
CHIM.vn_o7=[417,7899,7901,7903,7905,7907];
CHIM.vn_U0=[85,218,217,7910,360,7908];
CHIM.vn_u0=[117,250,249,7911,361,7909];
CHIM.vn_U7=[431,7912,7914,7916,7918,7920];
CHIM.vn_u7=[432,7913,7915,7917,7919,7921];
CHIM.vn_E0=[69,201,200,7866,7868,7864];
CHIM.vn_e0=[101,233,232,7867,7869,7865];
CHIM.vn_E6=[202,7870,7872,7874,7876,7878];
CHIM.vn_e6=[234,7871,7873,7875,7877,7879];
CHIM.vn_I0=[73,205,204,7880,296,7882];
CHIM.vn_i0=[105,237,236,7881,297,7883];
CHIM.vn_Y0=[89,221,7922,7926,7928,7924];
CHIM.vn_y0=[121,253,7923,7927,7929,7925];
//----------------------------------------------------------------------------
CHIM.vncode_2=[
	CHIM.vn_A0,CHIM.vn_a0,CHIM.vn_A6,CHIM.vn_a6,CHIM.vn_A8,CHIM.vn_a8,
	CHIM.vn_O0,CHIM.vn_o0,CHIM.vn_O6,CHIM.vn_o6,CHIM.vn_O7,CHIM.vn_o7,
	CHIM.vn_U0,CHIM.vn_u0,CHIM.vn_U7,CHIM.vn_u7,
	CHIM.vn_E0,CHIM.vn_e0,CHIM.vn_E6,CHIM.vn_e6,
	CHIM.vn_I0,CHIM.vn_i0,CHIM.vn_Y0,CHIM.vn_y0
];
//----------------------------------------------------------------------------
CHIM.vn_AA=[
	65,194,193,7844,192,7846,7842,7848,195,7850,7840,7852,258,194,7854,7844,
	7856,7846,7858,7848,7860,7850,7862,7852,97,226,225,7845,224,7847,7843,
	7849,227,7851,7841,7853,259,226,7855,7845,7857,7847,7859,7849,7861,7851,
	7863,7853
];
CHIM.vn_AW=[
	65,258,193,7854,192,7856,7842,7858,195,7860,7840,7862,194,258,7844,7854,
	7846,7856,7848,7858,7850,7860,7852,7862,97,259,225,7855,224,7857,7843,
	7859,227,7861,7841,7863,226,259,7845,7855,7847,7857,7849,7859,7851,7861,
	7853,7863
];
//----------------------------------------------------------------------------
CHIM.vn_OO=[
	79,212,211,7888,210,7890,7886,7892,213,7894,7884,7896,416,212,7898,7888,
	7900,7900,7902,7892,7904,7894,7906,7896,111,244,243,7889,242,7891,7887,
	7893,245,7895,7885,7897,417,244,7899,7889,7901,7891,7903,7893,7905,7895,
	7907,7897
];
CHIM.vn_OW=[
	79,416,211,7898,210,7900,7886,7902,213,7904,7884,7906,212,416,7888,7898,
	7890,7900,7892,7902,7894,7904,7896,7906,111,417,243,7899,242,7901,7887,
	7903,245,7905,7885,7907,244,417,7889,7899,7891,7901,7893,7903,7895,7905,
	7897,7907
];
CHIM.vn_UW=[
	85,431,218,7912,217,7914,7910,7916,360,7918,7908,7920,117,432,250,
	7913,249,7915,7911,7917,361,7919,7909,7921];
CHIM.vn_EE=[
	69,202,201,7870,200,7872,7866,7874,7868,7876,7864,7878,101,234,233,7871,
	232,7873,7867,7875,7869,7877,7865,7879
];
CHIM.vn_DD=[68,272,100,273];
//----------------------------------------------------------------------------
CHIM.vncode_1=[
	CHIM.vn_AA,CHIM.vn_EE,CHIM.vn_OO,
	CHIM.vn_AW,CHIM.vn_OW,CHIM.vn_UW,
	CHIM.vn_DD
];
//----------------------------------------------------------------------------
CHIM.modes=[
	[[['6',0,1,2],['7',4,5],['8',3],['9',6]],'6789','012345'],
	[[['a',0],['e',1],['o',2],['w',3,4,5],['d',6]],'ewoda','zsfrxj'],
	[[['^',0,1,2],['+',4,5],['(',3],['d',6]],'^+(d',"='`?~."],
	[[['6',0,1,2],['7',4,5],['8',3],['9',6],['a',0],['e',1],['o',2],['w',3,4,5],['d',6]],'6789ewoda',"012345zsfrxj"],
	[[['6',0,1,2],['7',4,5],['8',3],['9',6],['a',0],['e',1],['o',2],['w',3,4,5],['d',6],['^',0,1,2],['+',4,5],['(',3],['d',6]],'6789ewoda^+(d',"012345zsfrxj='`?~."]
];
//----------------------------------------------------------------------------
CHIM.UI=[
	85,218,217,7910,360,7908,117,250,249,7911,361,7909,431,7912,7914,7916,
	7918,7920,432,7913,7915,7917,7919,7921,73,205,204,7880,296,7882,105,237,
	236,7881,297,7883,0
];
//----------------------------------------------------------------------------
CHIM.O=[
	79,211,210,7886,213,7884,111,243,242,7887,245,7885,212,7888,7890,7892,
	7894,7896, 244,7889,7891,7893,7895,7897,416,7898,7900,7902,7904,7906,
	417,7899,7901,7903,7905,7907,0];
//----------------------------------------------------------------------------
CHIM.VN=[
	97,65,225,193,224,192,7843,7842,227,195,7841,7840,226,194,7845,7844,7847,
	7846,7849,7848,7851,7850,7853,7852,259,258,7855,7854,7857,7856,7859,7858,
	7861,7860,7863,7862,101,69,233,201,232,200,7867,7866,7869,7868,7865,7864,
	234,202,7871,7870,7873,7872,7875,7874,7877,7876,7879,7878,111,79,243,211,
	242,210,7887,7886,245,213,7885,7884,244,212,7889,7888,7891,7890,7893,
	7892,7895,7894,7897,7896,417,416,7899,7898,7901,7900,7903,7902,7905,7904,
	7907,7906,121,89,253,221,7923,7922,7927,7926,7929,7928,7925,7924,117,85,
	250,218,249,217,7911,7910,361,360,7909,7908,432,431,7913,7912,7915,7914,
	7917,7916,7919,7918,7921,7920,105,73,237,205,236,204,7881,7880,297,296,
	7883,7882,273,272,0
];
//---------------------------------------------------------------------------
// Function: UpdateUI
//	Synchronize text content with internal buffer after adding key
// Parameters:
//	target: the text container (textbox, textarea, ...)
//	l : buffer length BEFORE adding key
//---------------------------------------------------------------------------
Mudim.UpdateUI = function(target,l) {
	var b=CHIM.buffer;
	if (target.tagName == 'HTML') {
		CHIM.HTMLEditor.Process(target, l);
		if (l < CHIM.buffer.length) {return;}
		return false;
	}
	var start = Mudim.startWordOffset < 0 ? 0 : Mudim.startWordOffset;
	var end = CHIM.GetCursorPosition(target);
	var t = target.scrollTop;
	target.value = target.value.substring( 0, start ) +
		b.toString().replace(/,/g,'') + target.value.substring( end );
	CHIM.SetCursorPosition( target, start + b.length);
	target.scrollTop = t;
};
//---------------------------------------------------------------------------
// Function FindAccentPos
//	Find position to put accent based on current internal buffer content, provided with the most possible next key
// Return:
//	the position (index in buffer)
//---------------------------------------------------------------------------
Mudim.FindAccentPos = function(nKey) {
	var k=nKey.toLowerCase();
	var m=CHIM.modes[Mudim.method-1];
	var b=CHIM.buffer;
	var len=b.length;
	var i,j,l,p,c;
	if (!len || CHIM.off!=0) {return -1;}
	for( i = 1; i < m.length; i++ )
		if( m[i].indexOf(k) >= 0 ) {break;}
	p=len-1;	
	Mudim.is='ot';
	switch (l=i) {
		case 1:
			if (Mudim.GetMarkTypeID(k,1)==3)	{break;}		// d in telex
		case 2:
		default:
			i=p;
			while (i>=0 && b[i] < CHIM.CHAR_0x80 && CHIM.vowels.indexOf(b[i])<0) i--;		//Find the last vowel
				if (i<0) {return -1;}
			if (i<len-1) {	//consonants at the end, copy them to Mudim.tailConsonants for spell checking
				Mudim.tailConsonants=b.slice(i+1,len).toString().replace(/,/g,'').toLowerCase();
			}
			while( i-1 >= 0 &&
				(CHIM.vowels.indexOf(b[i-1]) >=0 || b[i-1] > CHIM.CHAR_0x80) &&
				CHIM.CharPriorityCompare( b[i-1], b[i] ) < 0 ) i--;
			if( i == len-1 && i-1 >= 0 &&	(j = CHIM.CharIsUI(b[i-1])) > 0 ) {
				switch( b[i] ) {
					case CHIM.CHAR_a:
					case CHIM.CHAR_A:
						//something like lu'a, bu`a, .. BUT only change when deal with mark group 2 or ( of group 1
						if ( (i-2 < 0 ||
							(j < 24 && b[i-2] != CHIM.CHAR_q && b[i-2] != CHIM.CHAR_Q) ||
							(j >= 24 && b[i-2] != CHIM.CHAR_g && b[i-2] != CHIM.CHAR_G) ) && (l==2 || (l==1 && Mudim.GetMarkTypeID(k,1)==1))) i--;
						break;
					case CHIM.CHAR_u:
					case CHIM.CHAR_U:
						if( i-2 < 0 || (b[i-2] != CHIM.CHAR_g && b[i-2] != CHIM.CHAR_G) ) i--;
						break;
					case CHIM.CHAR_Y:
					case CHIM.CHAR_y:
						//old accent rule : tu`y, hu?y, .... but quy` , quy?
						if ( (!Mudim.newAccentRule) && i-2>=0 && b[i-2]!=CHIM.CHAR_q && b[i-2]!=CHIM.CHAR_Q ) {i--;}
						break;
				}
			}
			if ( i == len-1 && i-1 >=0 && CHIM.CharIsO(b[i-1]) > 0) {
				switch(b[i]) {
					case CHIM.CHAR_a:
					case CHIM.CHAR_A:
						if (!Mudim.newAccentRule && (l==2 || (l==1 && Mudim.GetMarkTypeID(k,1)!=1))) i--;
						break;
					case CHIM.CHAR_e:
					case CHIM.CHAR_E:
						if (!Mudim.newAccentRule) i--;
						break;
				};
			}
			if (i == len-2 && i-1 >= 0) {	// qux ---> put mark on x
				var uipos=CHIM.CharIsUI(b[i]);
				if (uipos>=0 && uipos<24 & (b[i-1]==CHIM.CHAR_q || b[i-1]==CHIM.CHAR_Q)) {
					i++;
				}
			}
			p=i;
			break;
	};
	if (Mudim.GetMarkTypeID(k,1)==3 && b[0]=='d') {return 0;}
	return p;
};
//---------------------------------------------------------------------------
//Function Mudim.PutMark
//	put diacritical mark
//Parameters:
//	pos: position in buffer to put
//	charCodeAtPos: code of the character at pos
//	group: mark group (1 or 2 ), define which table (vncode_1 or vncode_2)
//	idx: index in the table
//	key: the key added
//	checkDouble: raise CHIM.off when delete mark
//---------------------------------------------------------------------------
Mudim.PutMark = function(pos,charCodeAtPos,group,subsTab,key,checkDouble) {
	var v = subsTab;
	var i;
	//console.debug('|'+CHIM.buffer+'| (Begin PutMark)');
	for (i=0;i<v.length;i++) {
		if (v[i]==charCodeAtPos) {
			switch (group) {
				case 1:
					if (Mudim.GetMarkTypeID(key,1)==1) {Mudim.w++;}
					if( i % 2 == 0 ) {
						CHIM.SetCharAt( pos, v[i+1] );
					} else {
						CHIM.SetCharAt( pos, v[i-1] );
						if (checkDouble) {
							CHIM.off = CHIM.buffer.length + 1;
						}
					}
					break;
				case 2:
					var j = Mudim.GetMarkTypeID(key,2);
					if( j >= 0 ) {
						if( j != i ) {
							CHIM.SetCharAt( pos, v[j] );
							//console.debug('Mark group 2 added');
							Mudim.accent=[pos,(CHIM.buffer[pos]).charCodeAt(0),v,key];
						} else {
							CHIM.SetCharAt(pos, v[0]);
							Mudim.ResetAccentInfo();
							if (checkDouble) {
								//console.debug('Double mark, clear now');
								CHIM.off = CHIM.buffer.length + 1;
							}
						}
					}
					break;
			}
			return true;
		}
	}
	return false;
};
//---------------------------------------------------------------------------
// Function ResetAccentInfo
//	Reset information about current accent
//---------------------------------------------------------------------------
Mudim.ResetAccentInfo = function() {
	Mudim.accent = [-1,0,null,'z'];
};
//---------------------------------------------------------------------------
// Function AdjustAccent
//	Update accent position when it is changed
// Return:
//	true if accent has been updated successfully
//---------------------------------------------------------------------------
Mudim.AdjustAccent = function(vk) {
	//console.debug('|'+CHIM.buffer+'| (Begin AdjustAccent)');
	if (CHIM.off!=0) {
		return false;
	}
	var p=Mudim.FindAccentPos(vk);
	var a = Mudim.accent;	
	var b=CHIM.buffer;
	var v,i,j,c;
	if (p<0) { return false;}
	i = CHIM.vn_OW.length-1;
	c = b[p].charCodeAt(0);
	while (i>=0 && CHIM.vn_OW[i]!=c) {i--;}
	j = CHIM.vn_UW.length-1;
	if (p>0) {
		c=b[p-1].charCodeAt(0);
		while (j>=0 && CHIM.vn_UW[j]!=c) {j--;}
	} else {
		j=-1;
	}
	//uo
	if (p<b.length-1 && p>0 && i>=0 && j>=0) {
		//console.debug('uo detected with w = '+Mudim.w);
		if (Mudim.w==1) {
			if (i%2==0) {	//u+o
				Mudim.PutMark(p,b[p].charCodeAt(0),1,CHIM.vn_OW,CHIM.modes[Mudim.method-1][1].charAt(1),false);	//u+ o+
				if (b[0]==CHIM.CHAR_q || b[0]==CHIM.CHAR_Q) {	//if word starts with 'q' then change to uo+
					//console.debug('Change to quo+');
					Mudim.PutMark(p-1,b[p-1].charCodeAt(0),1,CHIM.vn_UW,CHIM.modes[Mudim.method-1][1].charAt(1),false);
				}
			} else {	//uo+
				if (b[0]!=CHIM.CHAR_q && b[0]!=CHIM.CHAR_Q) {	//if word doesnt start with 'q' then change to u+o+
					Mudim.PutMark(p-1,b[p-1].charCodeAt(0),1,CHIM.vn_UW,CHIM.modes[Mudim.method-1][1].charAt(1),false);
				}
			}
			return true;
		}
	}
	if (a[0]>=0 && p>0 && a[0]!=p) {		// Automatically adjust the accent, when position changes
		//console.debug('Accent position changed, adjust now');
		Mudim.PutMark(a[0],a[1],2,a[2],a[3],false);
		for( i = 0; i < CHIM.vncode_2.length; i++ ) {
			v = CHIM.vncode_2[i];
			if (Mudim.PutMark(p,b[p].charCodeAt(0),2,v,a[3],true)) {
				break;
			}
		}
		return true;
	}
	return false;
};
//----------------------------------------------------------------------------
// Function: GetMarkTypeID
// 	get the index in mark string of each method
// Parameters:
//	key: the key
//	group: mark group (1 or 2)
// Return:
//	-1 if this key is not a mark key
//	or the index in mark key string.
// Remarks:
//	Use global variable Mudim.method
//	For group 1, the return value is stable only for the ones used in implementation (1 and 3)
//----------------------------------------------------------------------------
Mudim.GetMarkTypeID = function (key,group) {
	var m = CHIM.modes[Mudim.method-1];
	if (Mudim.method != 4) {
		return m[group].indexOf(key);
	} else {
		var j = -1;
		for (var i = 0; i < 2; i++) {
			j = CHIM.modes[i][group].indexOf(key);
			if (j>=0) {
				return j;
			}
		}
		return j;
	}
};
/**
* Auto detect typing mode based on current character
* @param(char) c Current character
*@return  typing mode
*/
Mudim.AutoDetectMode = function(c) {
	var gi;
	if ((gi = CHIM.modes[4][1].indexOf(c))>=0) {
		if (gi<4) {
			return 1;
		} else if (gi<9) {
			return 2;
		} else {
			return 3;
		}
	} else if ((gi = CHIM.modes[4][2].indexOf(c))>=0) {
		if (gi<6) {
			return 1;
		} else if (gi<12) {
			return 2;
		} else {
			return 3;
		}
	} else {
		return 0;
	}
};
//----------------------------------------------------------------------------
// Function: Mudim.SetPreference()
//----------------------------------------------------------------------------
Mudim.SetPreference = function() {
	var d=new Date();
	d.setTime(d.getTime()+604800000);
	var tail=';expires='+d.toGMTString()+';path=/';
	var value = Mudim.method;
	var value= CHIM.Speller.enabled ? value + 8 : value;
	value = Mudim.newAccentRule ? value + 16 : value;
	value = Mudim.showPanel ? value + 32 : value;
	value += Mudim.displayMode * 64;
	document.cookie='|mudim-settings='+value+tail;
	//console.debug('Cookie value written : |mudim-settings='+value+tail);
};
//----------------------------------------------------------------------------
// Function: Mudim.GetPreference()
//----------------------------------------------------------------------------
Mudim.GetPreference = function() {
	var c=document.cookie.split(';');
	for (var i=0;i<c.length && c[i].indexOf('|mudim-settings')<0;i++);
	if (i==c.length) {
		CHIM.SetDisplay();
	} else {
		var value=parseInt(c[i].split('=')[1],10);
		//console.debug('Cookie value read : %d',value);
		Mudim.method = value & 7;
		CHIM.Speller.enabled = (value & 8) ? true : false;
		CHIM.newAccentRule = (value & 16) ? true : false;
		Mudim.showPanel = (value & 32) ? true : false;
		Mudim.displayMode = (value & 64)>>6;
	}
};
//----------------------------------------------------------------------------
// Function: MudimToggleAccentRule()
//----------------------------------------------------------------------------
Mudim.ToggleAccentRule = function() {
	Mudim.newAccentRule = !Mudim.newAccentRule;
};
//----------------------------------------------------------------------------
// Function: Mudim.TogglePanel()
//----------------------------------------------------------------------------
Mudim.TogglePanel = function() {
	Mudim.showPanel = !Mudim.showPanel;
	Mudim.Panel.style.display = Mudim.showPanel ? '' : 'None';
	Mudim.SetPreference();
};
Mudim.ShowPanel = function() {
	Mudim.showPanel = true;
	Mudim.Panel.style.display = '';
};
Mudim.HidePanel = function() {
	Mudim.showPanel = false;
	Mudim.Panel.style.display = 'None';
};
Mudim.InitPanel = function() {
	if (!Mudim.Panel) {
		Mudim.GetPreference();
		Mudim.panels =['<div id="mudimPanel" style="position: fixed; bottom: 0; right:0; left:0; width: 100%; border: 1px solid black; padding: 1px; background: '+Mudim.PANEL_BACKGROUND+'; color:'+Mudim.COLOR+'; z-index:4000; text-align: center; font-size: 10pt;"><a href="http://mudim.googlecode.com" title="Mudzot\'s Input Method" onclick="Mudim.ToggleDisplayMode();return false;">Mudim</a> v0.8 <input name="mudim" id="mudim-off" onclick="Mudim.SetMethod(0);" type="radio">'+Mudim.LANG[0]+'<input name="mudim" id="mudim-vni" onclick="Mudim.SetMethod(1);" type="radio"> '+Mudim.LANG[1]+' <input name="mudim" id="mudim-telex" onclick="Mudim.SetMethod(2);" type="radio"> '+Mudim.LANG[2]+' <input name="mudim" id="mudim-viqr" onclick="Mudim.SetMethod(3);" type="radio"> '+Mudim.LANG[3]+' <input name="mudim" id="mudim-mix" onclick="Mudim.SetMethod(4);" type="radio"> '+Mudim.LANG[4]+' <input name="mudim" id="mudim-auto" onclick="Mudim.SetMethod(5);" type="radio"> '+Mudim.LANG[5]+' <input id="mudim-checkspell" onclick="javascript:Mudim.ToggleSpeller();" type="checkbox">'+Mudim.LANG[6]+'<input id="mudim-accentrule" onclick="javascript:Mudim.ToggleAccentRule();" type="checkbox">'+Mudim.LANG[7]+' [&nbsp;<a href="#" onclick="Mudim.Toggle();return false;">'+Mudim.LANG[8]+'</a> (F9) <a href="#" onclick="Mudim.TogglePanel();return false;">'+Mudim.LANG[9]+'</a> (F8) ]</div>',
						'<div id="mudimPanel" style="position: fixed; bottom: 0; right: 0; width: 120px; border: 1px solid black; padding: 1px; background: '+Mudim.PANEL_BACKGROUND+'; color:'+Mudim.COLOR+'; z-index:100; text-align: center; font-size: 10pt;"><a href="http://mudim.googlecode.com" title="Mudzot\'s Input Method" onclick="Mudim.ToggleDisplayMode();return false;">Mudim</a>:#METHOD#</div>'];
		var f=document.createElement('div');
		f.innerHTML=Mudim.panels[Mudim.displayMode].replace('#METHOD#',Mudim.LANG[Mudim.method]);
		f.style.display = 'None';
		document.body.appendChild(f);
		Mudim.Panel=f;
		if (Mudim.showPanel) {
			Mudim.ShowPanel();
		} else {
			Mudim.HidePanel();
		}
	}
};
Mudim.ToggleSpeller = function() {
	CHIM.Speller.Toggle();
};
Mudim.Toggle = function() {
	CHIM.Toggle();
};
Mudim.ToggleDisplayMode = function() {
	if (Mudim.displayMode) {
		Mudim.displayMode = 0;
	} else {
		Mudim.displayMode = 1;
	}
	Mudim.BeforeInit();
	Mudim.Panel.innerHTML=Mudim.panels[Mudim.displayMode].replace('#METHOD#',Mudim.LANG[Mudim.method]);
	Mudim.AfterInit();
	Mudim.SetPreference();
};
Mudim.SetMethod = function(m) {
	CHIM.SetMethod(m);
};
Mudim.SwitchMethod = function() {
	CHIM.SwitchMethod();
};
/**
* User-defined hook
*/
Mudim.BeforeInit = function() {
};
/**
* User-defined hook
*/
Mudim.AfterInit = function() {
};
/**
* Initialize Mudim
*/
Mudim.Init = function() {
	Mudim.BeforeInit();
	Mudim.InitPanel();
	CHIM.Activate();
	Mudim.AfterInit();
};
/**
* Get the style object of the Panel. Dont't use in BeforeInit hook
*/
Mudim.GetPanelStyle = function() {
	return Mudim.Panel.firstChild.style;
};
//----------------------------------------------------------------------------
/**
* Define method
* Values: 0 - off, 1 - vni, 2 - telex, 3 - viqr, 4 - combined, 5 - auto
*/
Mudim.method = 4;
/**
* Use the new accent rule
*/
Mudim.newAccentRule = true;
/**
*Last used method
*/
Mudim.oldMethod = 4;
/**
*Visibility of panel. Assign to this variable to set default value
*/
Mudim.showPanel = false;
/**
*
*/
Mudim.accent=[-1,0,null,-1];	//[position, code, substitution table, index]
/**
*
*/
Mudim.w = 0;
/**
*Temporarily turn Mudim off for current word
*/
Mudim.tempOff = false;
/**
*Temporarily disable spell checking for current word
*/
Mudim.tempDisableSpellCheck = false;
/**
*Last state of tempDisableSpellCheck
*/
Mudim.newTempDisableSpellCheckRequest = false;
/**
*Indicate CTRL is being pressed
*/
Mudim.ctrlSerie = 0;
/**
*Indicate SHIFT is being pressed
*/
Mudim.shiftSerie = 0;
/**
* Head consonants
*/
Mudim.headConsonants = '';
/**
* Tail consonants
*/
Mudim.tailConsonants = '';
/**
*The word, which is stored in buffer, starts at this offset
*/
Mudim.startWordOffset=0;
/**
* Control panel text color
*/
Mudim.COLOR='Black';
/**
* Control panel background color
*/
Mudim.PANEL_BACKGROUND='lightYellow';
/**
*Phrases used in panel
*/
Mudim.LANG=['Tắt','VNI','Telex','Viqr','Tổng hợp','Tự động','Chính tả','Bỏ dấu kiểu mới','Bật/Tắt','Ẩn/Hiện'];
/**
*Array containing ID of elements which doesn't need Vietnamese typing
*/
//Mudim.IGNORE_ID = [];
Mudim.IGNORE_ID = /^.+(_iavim)$/;
/**
*Panel display mode. 0 = full; 1 = minimized
*/
Mudim.displayMode = 0;
/**
*Panel's HTML content for each display mode
*/
Mudim.panels = ['',''];
Mudim.REV = 153;
//----------------------------------------------------------------------------

for (var i=1;i<100;i++) {
	setTimeout("Mudim.Init()",2000*i);
}

// Change log

// Add display modes (full and minimized)
// Move panel from top to bottom of the page
// Add 2 hooks BeforeInit and AfterInit
// Use polling method to activate Mudim


// Changes in 0.8
// Add escape method with Ctrl and Shift
// Fix issue #10
// Improve compatibility with other js input methods (specifically, with avim)
// Fix issue #30

// Changes in 0.7
// Fix issue #21 (return false in toggle links)
// Fix issue #23 (typo error in creating cookie)
// Fix issue #19 : some word cannot put accent on due to  new spell checking rule
// Fix issue #20 about error in console when there's no text target
// Remove Viqr from method Auto
// Save accent rule setting, and show/hide panel setting
// Add spelling rule as described in Issue #16 comment #0 and comment #1
// Take frequently used array from Append, make them global
// Forbid ng[ie], c[ie] and q[^u]
// Fix the qui't issue
// Fix the appear-again khuyur issue as magically as when it disappeared

//Changes in 0.6
// Add AUTO method, which allows user to type in any of 3 methods
// Fix issue 9
// Fix issue 8
//SetPreference when Show/Hide Panel

//Changes in 0.5
// Issue 6,7 fixed

//Changes in 0.4
// - Standardize coding style for compatibility with javascript compressor (issue 3)
// - Fix spelling feature (issue 4)
// - Fix problem with readding accent

//Changes in version 0.3
// - First public release
// - Shorthand ruoiw for ruwowi and similar situations
// - Refactoring work:
//	+ FindAccentPos and PutMark from AddKey
//	+ UpdateUI from KeyHandler
// - Added attribute Mudim.accent which store information about the last accent function ResetAccentInfo
// - Auto adjust accent position
// - The problem with khuyur in 0.2 disappeared magically (Why?)
// - Fix serious problem with textbox and textarea due to caret position (issue 2)
// - Fix su73a in VNI and some issue related to 'uo' composiotion (issue 1)


//Changes in version 0.2
// - Happy to see it run on Opera and Konqueror, some modifications in hotkey implementation, though, 
//	which use new attribute Mudin.oldMethod to store the old method when toggle.
// - Fix some bugs with 'ua' composition
// - Limited support for old accent rule with new attribute Mudin.newAccentRule.
//	I peronally recommend the new accent rule, that's why I don't store this attribute in cookie.
//	It means that if you prefer the old rule, you have to explicitly select it every time you open a new page.
// - Add 1 more toggle hotkey F10 as an alternative to F9 in case of conflict with browser hotkeys.
// - Fix permission error in javascript console when try to attach to cross-site iframe
// - Known issue : khuyur

//Changes in Mudim 0.1 from CHIM:
// - Easy-to-use control panel (credit for this smart idea goes to Wasabi with his BIM)
// - Save settings in cookie
// - Ability to put ALL diacritical marks at the end of word
// - Fix hotkey problem in IE
