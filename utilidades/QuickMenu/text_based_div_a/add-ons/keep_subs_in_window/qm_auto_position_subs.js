qmad.apsubs=new Object();if(qmad.bvis.indexOf("qm_apsubs(b.cdiv,o);")==-1)qmad.bvis+="qm_apsubs(b.cdiv,o);";;function qm_apsubs(a){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.apsubs)&&!z["on"+qm_index(a)])return;if(!a.settingsid){var v=a;while(!qm_a(v))v=v[qp];a.settingsid=v.id;}var ss=qmad[a.settingsid];if(!ss)return;if(!ss.subs_in_window_active)return;var wh=qm_get_doc_wh();var sxy=qm_get_doc_scrollxy();var xy=qm_get_offset(a);var c1=a.offsetWidth+xy[0];var c2=wh[0]+sxy[0];if(c1>c2){a.style.left=(parseInt(a.style.left)-(c1-c2))+"px";if(a.hasrcorner)a.hasrcorner.style.left=(parseInt(a.hasrcorner.style.left)-(c1-c2))+"px";if(a.hasshadow)a.hasshadow.style.left=(parseInt(a.hasshadow.style.left)-(c1-c2))+"px";if(a.hasselectfix)a.hasselectfix.style.left=(parseInt(a.hasselectfix.style.left)-(c1-c2))+"px";}c1=a.offsetHeight+xy[1];c2=wh[1]+sxy[1];if(c1>c2){a.style.top=(parseInt(a.style.top)-(c1-c2))+"px";if(a.hasrcorner)a.hasrcorner.style.top=(parseInt(a.hasrcorner.style.top)-(c1-c2))+"px";if(a.hasshadow)a.hasshadow.style.top=(parseInt(a.hasshadow.style.top)-(c1-c2))+"px";if(a.hasselectfix)a.hasselectfix.style.top=(parseInt(a.hasselectfix.style.top)-(c1-c2))+"px";}};function qm_get_offset(obj){var x=0;var y=0;do{x+=obj.offsetLeft;y+=obj.offsetTop;}while(obj=obj.offsetParent)return new Array(x,y);};function qm_get_doc_scrollxy(){var sy=0;var sx=0;if((sd=document.documentElement)&&(sd=sd.scrollTop))sy=sd;else  if(sd=document.body.scrollTop)sy=sd;if((sd=document.documentElement)&&(sd=sd.scrollLeft))sx=sd;else  if(sd=document.body.scrollLeft)sx=sd;return new Array(sx,sy);};function qm_get_doc_wh(){db=document.body;var w=0;var h=0;if(tval=window.innerHeight){h=tval;w=window.innerWidth;}else  if((e=document.documentElement)&&(e=e.clientHeight)){h=e;w=document.documentElement.clientWidth;}else  if(e=db.clientHeight){if(!h)h=e;if(!w)w=db.clientWidth;}return new Array(w,h);}