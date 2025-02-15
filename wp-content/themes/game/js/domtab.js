/*
	DOMtab Version 3.1415927
	Updated March the First 2006
	written by Christian Heilmann
	check blog for updates: http://www.wait-till-i.com	
	free to use, not free to resell
*/

domtab={
	tabClass:'game_tabs_menu2', // class to trigger tabbing
	listClass:'game_tabs_menu', // class of the menus
	activeClass:'active', // class of current link
	contentElements:'div', // elements to loop through
	backToLinks:/#top/, // pattern to check "back to top" links
	printID:'domtabprintview', // id of the print all link
	showAllLinkText:'show all content', // text for the print all link
	prevNextIndicator:'doprevnext', // class to trigger prev and next links
	prevNextClass:'prevnext', // class of the prev and next list
	prevLabel:'previous', // HTML content of the prev link
	nextLabel:'next', // HTML content of the next link
	prevClass:'prev', // class for the prev link
	nextClass:'next', // class for the next link
	init:function(){
		var temp;
		if(!document.getElementById || !document.createTextNode){return;}
		var tempelm=document.getElementsByTagName('div');		
		for(var i=0;i<tempelm.length;i++){
			if(!domtab.cssjs('check',tempelm[i],domtab.tabClass)){continue;}
			domtab.initTabMenu(tempelm[i]);
			domtab.removeBackLinks(tempelm[i]);
			if(domtab.cssjs('check',tempelm[i],domtab.prevNextIndicator)){
				domtab.addPrevNext(tempelm[i]);
			}
			domtab.checkURL();
		}
		if(document.getElementById(domtab.printID) 
		   && !document.getElementById(domtab.printID).getElementsByTagName('a')[0]){
			var newlink=document.createElement('a');
			newlink.setAttribute('href','#');
			domtab.addEvent(newlink,'click',domtab.showAll,false);
			newlink.onclick=function(){return false;} // safari hack
			newlink.appendChild(document.createTextNode(domtab.showAllLinkText));
			document.getElementById(domtab.printID).appendChild(newlink);
		}
	},
	initTabMenu:function(menu){
		var id;
		var lists=menu.getElementsByTagName('ul');
		for(var i=0;i<lists.length;i++){
			if(domtab.cssjs('check',lists[i],domtab.listClass)){
				var thismenu=lists[i];
				break;
			}
		}
		if(!thismenu){return;}
		thismenu.currentSection='';
		thismenu.currentLink='';
		var links=thismenu.getElementsByTagName('a');
		for(i=0;i<links.length;i++){
			if(!/#/.test(links[i].getAttribute('href').toString())){continue;}
			id=links[i].href.match(/#(\w.+)/)[1];
			if(document.getElementById(id)){
				domtab.addEvent(links[i],'click',domtab.showTab,false);
				links[i].onclick=function(){return false;} // safari hack
				domtab.changeTab(document.getElementById(id),0);
			}
		}
		id=links[0].href.match(/#(\w.+)/)[1];
		if(document.getElementById(id)){
			domtab.changeTab(document.getElementById(id),1);
			thismenu.currentSection=id;
			thismenu.currentLink=links[0];
			domtab.cssjs('add',links[0].parentNode,domtab.activeClass);
		}
	},

	changeTab:function(elm,state){
		do{
			elm=elm.parentNode;
		} while(elm.nodeName.toLowerCase()!=domtab.contentElements)
		elm.style.display=state==0?'none':'block';
	},
	showTab:function(e){
		var o=domtab.getTarget(e);
		if(o.parentNode.parentNode.currentSection!=''){
			domtab.changeTab(document.getElementById(o.parentNode.parentNode.currentSection),0);
			domtab.cssjs('remove',o.parentNode.parentNode.currentLink.parentNode,domtab.activeClass);
		}
		var id=o.href.match(/#(\w.+)/)[1];
		o.parentNode.parentNode.currentSection=id;
		o.parentNode.parentNode.currentLink=o;
		domtab.cssjs('add',o.parentNode,domtab.activeClass);
		domtab.changeTab(document.getElementById(id),1);
		document.getElementById(id).focus();
		domtab.cancelClick(e);
	},
/* helper methods */
	getTarget:function(e){
		var target = window.event ? window.event.srcElement : e ? e.target : null;
		if (!target){return false;}
		if (target.nodeName.toLowerCase() != 'a'){target = target.parentNode;}
		return target;
	},
	cancelClick:function(e){
		if (window.event){
			window.event.cancelBubble = true;
			window.event.returnValue = false;
			return;
		}
		if (e){
			e.stopPropagation();
			e.preventDefault();
		}
	},
	addEvent: function(elm, evType, fn, useCapture){
		if (elm.addEventListener) 
		{
			elm.addEventListener(evType, fn, useCapture);
			return true;
		} else if (elm.attachEvent) {
			var r = elm.attachEvent('on' + evType, fn);
			return r;
		} else {
			elm['on' + evType] = fn;
		}
	},
	cssjs:function(a,o,c1,c2){
		switch (a){
			case 'swap':
				o.className=!domtab.cssjs('check',o,c1)?o.className.replace(c2,c1):o.className.replace(c1,c2);
			break;
			case 'add':
				if(!domtab.cssjs('check',o,c1)){o.className+=o.className?' '+c1:c1;}
			break;
			case 'remove':
				var rep=o.className.match(' '+c1)?' '+c1:c1;
				o.className=o.className.replace(rep,'');
			break;
			case 'check':
				var found=false;
				var temparray=o.className.split(' ');
				for(var i=0;i<temparray.length;i++){
					if(temparray[i]==c1){found=true;}
				}
				return found;
			break;
		}
	}
}
domtab.addEvent(window, 'load', domtab.init, false);