/**
 * jqDynTabs 1.0 beta - jQuery dynamic tabs plugin with ajax  
 *
 * tony@trirand.com
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */
(function ($) {
$.fn.jqDynTabs = function( p ) {
p = $.extend({ 
	tabcontrol:"",
	tabcontent:"",
	side: "left",
	orientation: "top",
	onClickTab : null
}, p || {});
this.CreateTab = function(tabName, closable, purl, content) {
	var thisg = this.get(0);
	var tabID = thisg.Name + 'Tab' + thisg.tabNumber;
	var panelID = thisg.Name + 'Panel' + thisg.tabNumber;
	if (closable == null || typeof closable != "boolean" ) { closable = true };
	var panel = document.createElement('div');
	panel.style.left = '0px';
	panel.style.top = '0px';
	panel.style.width = '100%';
	panel.style.height = '100%';
	panel.style.display = 'none';
	panel.tabNum = thisg.tabNumber;
	panel.id = panelID;
	if(thisg.panelContainer.insertAdjacentElement == null)
		thisg.panelContainer.appendChild(panel)
	else
		thisg.panelContainer.insertAdjacentElement("beforeEnd",panel); //Internet Explorer
	var sidenum=1;
	if (p.side == "right") sidenum =0
		else p.side = "left";
	if (purl) {
		if (purl.indexOf('?') == -1) purl +='?'; 
		$.ajax({
			url:purl+"&nd="+new Date().getTime(),
			method:"GET", 
			dataType:"html",
			complete: function(req) { 
				$(panel).html(req.responseText);
			},
			error: function( req, errortype) { alert(errortype) }
		});
	} else {
		if (content) $(panel).html(content);
	}
	var cell = thisg.tabContainer.insertCell(thisg.tabContainer.cells.length - parseInt(sidenum) ); 
	cell.id = tabID;
	cell.className = 'lowTab';
	cell.tabNum = thisg.tabNumber;
	cell.closable = closable;
	cell.tabName = tabName;
	var clicktab = this;
	$(cell).click( function(e) { 
		var el = (e.target || e.srcElement);
		clicktab.TabClickEl(el);
		if (p.onClickTab != null && typeof p.onClickTab == "function") { p.onClickTab( tabName, panel )}
	});
	if (closable ) {
		cell.innerHTML = ' ' + tabName+ '  ' + "<img src='imagenes/tab_close-on.gif'/><span> </span>";
	} else {
		cell.innerHTML = ' ' + tabName+ ' '+' ';
	}
	this.TabClickEl(cell);
	$("img",cell).click(function(){ clicktab.TabCloseEl(cell)} )
	thisg.tabNumber++;
	return panel;
}

this.TabClickEl = function (element) {
	if(this.get(0).currentHighTab == element) return;
	if(this.get(0).currentHighTab != null) {
		this.get(0).currentHighTab.className = this.get(0).lowTabStyle;
		if (this.get(0).currentHighTab.closable) { $("img",this.get(0).currentHighTab).attr({src:"imagenes/tab_close-on.gif"})}
	}

	if(this.get(0).currentHighPanel != null)
		this.get(0).currentHighPanel.style.display = 'none';
	this.get(0).currentHighPanel = null;
	this.get(0).currentHighTab = null;

	if(element == null) return;
	this.get(0).currentHighTab = element;
	this.get(0).currentHighPanel = document.getElementById(this.get(0).Name + 'Panel' + this.get(0).currentHighTab.tabNum);
	if(this.get(0).currentHighPanel == null)
	{
		this.get(0).currentHighTab = null
		return;
	}
	this.get(0).currentHighTab.className = this.get(0).highTabStyle;
	this.get(0).currentHighPanel.style.display = '';
	if (element.closable) { $("img",element).attr({src:"imagenes/tab_close-on.gif"})}
}

this.TabCloseEl = function(element) {
	if(element == null) return;
	var thisg = this.get(0);
	var tabLength = thisg.tabContainer.cells.length;
	if (  tabLength == 1) return; 
	var isNumber = false, isHighTab=false, elemIndex, i, panel;
	if ( typeof element === 'number' && element >= 0 ) {
		isNumber = true;
		for(i = 0; i<= tabLength-1; i++) {
			if(thisg.tabContainer.cells[i].cellIndex==element) {
				elemIndex = thisg.tabContainer.cells[i].cellIndex;
				if (p.side=="right") { 
					elemIndex++;
					element = thisg.tabContainer.cells[i+1].tabNum;
				} else { element = thisg.tabContainer.cells[i].tabNum; }
				break;
			}
		}
		panel = document.getElementById(thisg.Name + 'Panel' + element);
		if (panel.tabNum == thisg.currentHighTab.tabNum) isHighTab = true;
	}
	if(element == thisg.currentHighTab || isHighTab) {
		i = -1;
		if(tabLength > 2)
		{
			i = isHighTab ? elemIndex: element.cellIndex;
			if(i == tabLength- 2) i--;
			else i++;
			if(p.side=="right") { 
				if(i===0) i=2; 
				else
					if( i === tabLength) i=i-2;  
			}
			if(i >= 0)
				this.TabClickEl(thisg.tabContainer.cells[i]);
			else
				this.TabClickEl(null);
		}
	}

	if ( isNumber ) {
		thisg.tabContainer.deleteCell(elemIndex);
	}
	else {
		panel = document.getElementById(thisg.Name + 'Panel' + element.tabNum);
		thisg.tabContainer.deleteCell(element.cellIndex);
	}
	if(panel != null) {
		thisg.panelContainer.removeChild(panel);
		//panel = null;
	}
}

this.getTabIndex = function () {
	return this.get(0).tabContainer.cells.length - 1; 
}

this.tabExists = function (tabName) {
	for( var i=0;i<= this.get(0).tabContainer.cells.length-1;i++){
		if( this.get(0).tabContainer.cells[i].tabName == tabName) {
			this.TabClickEl(this.get(0).tabContainer.cells[i]);			
			return true;
		}
	}
	return false;
}

return this.each( function() {
	if (p.tabcontrol == null && p.tabcontrol.length ==0 && p.tabcontent == null && p.tabcontent.length == 0) {
	  return;
	} else {
		p.tabcontrol = p.tabcontrol.get(0);
		p.tabcontent = p.tabcontent.get(0);
	}
	this.Name = 'jqDynTabs'+ Math.round(100*Math.random());
	this.tabNumber = 0;
	this.currentHighPanel = null;
	this.currentHighTab = null;
	this.panelContainer = p.tabcontent; 
	this.tabContainer = p.tabcontrol; 
	this.lowTabStyle = 'lowTab';
	if(p.position=="bottom")
	  this.highTabStyle = 'highTabBottom';
	else
		this.highTabStyle = 'highTab';

});
}
})(jQuery);
