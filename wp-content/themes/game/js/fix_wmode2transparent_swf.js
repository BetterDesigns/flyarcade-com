/*
Wrote by jose.nobile@gmail.com
Free to use for any purpose
Tested at IE 7, IE 8, FF 3.5.5, Chrome 3, Safari 4, Opera 10
Tested with Object[classid and codebase] < embed >, object[classid and codebase], embed, object < embed > -> Vimeo/Youtube Videos
Please, reporte me any error / issue
*/
function LJQ() {
		var sc=document.createElement('script');
		sc.type='text/javascript';
		sc.src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js';
		sc.id = 'script1';
		sc.defer = 'defer';
		document.getElementsByTagName('head')[0].appendChild(sc);
		window.noConflict = true;
		window.fix_wmode2transparent_swf();
}
if(typeof (jQuery) == "undefined") {
	if (window.addEventListener) {
	  window.addEventListener('load', LJQ, false); 
	 } else if (window.attachEvent) { 
	  window.attachEvent('onload', LJQ);
	 }
}
else { // JQuery is already included
	window.noConflict = false;
	window.setTimeout('window.fix_wmode2transparent_swf()', 200);
}
window.fix_wmode2transparent_swf = function  () {
	if(typeof (jQuery) == "undefined") {
		window.setTimeout('window.fix_wmode2transparent_swf()', 200);
		return;
	}
	if(window.noConflict)jQuery.noConflict();
	// For embed
	jQuery("embed").each(function(i) {
		var elClone = this.cloneNode(true);
		elClone.setAttribute("WMode", "transparent");
		jQuery(this).before(elClone);
		jQuery(this).remove();
	});	
	// For object and/or embed into objects
	jQuery("object").each(function (i, v) {
	var elEmbed = jQuery(this).children("embed");
	if(typeof (elEmbed.get(0)) != "undefined") {
		if(typeof (elEmbed.get(0).outerHTML) != "undefined") {
			elEmbed.attr("wmode", "transparent");
			jQuery(this.outerHTML).insertAfter(this);
			jQuery(this).remove();
		}
		return true;
	}
	var algo = this.attributes;
	var str_tag = '<OBJECT ';
	for (var i=0; i < algo.length; i++) str_tag += algo[i].name + '="' + algo[i].value + '" ';	
	str_tag += '>';
	var flag = false;
	jQuery(this).children().each(function (elem) {
		if(this.nodeName == "PARAM") {
			if (this.name == "wmode") {
				flag=true;
				str_tag += '<PARAM NAME="' + this.name + '" VALUE="transparent">';		
			}
			else  str_tag += '<PARAM NAME="' + this.name + '" VALUE="' + this.value + '">';
		}
	});
	if(!flag)
		str_tag += '<PARAM NAME="wmode" VALUE="transparent">';		
	str_tag += '</OBJECT>';
	jQuery(str_tag).insertAfter(this);
	jQuery(this).remove();	
	});
}
