qmad.merge=new Object();if(qmad.bvis.indexOf("qm_merge_a(b.cdiv);")==-1)qmad.bvis+="qm_merge_a(b.cdiv);";if(qmad.bhide.indexOf("qm_merge_a(a,1);")==-1)qmad.bhide+="qm_merge_a(a,1);";qmad.br_ie=window.showHelp;qmad.br_mac=navigator.userAgent.indexOf("Mac")+1;qmad.br_old_safari=navigator.userAgent.indexOf("afari")+1&&!window.XMLHttpRequest;qmad.merge_off=(qmad.br_ie&&qmad.br_mac)||qmad.br_old_safari;;function qm_merge_a(a,hide){var z;if((a.style.visibility=="inherit"&&!hide)||(qmad.merge_off)||((z=window.qmv)&&(z=z.addons)&&(z=z.merge_effect)&&!z["on"+qm_index(a)])){return;}var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf("qmmc")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(!ss.merge_frames)return;if(hide){a.ismove=false;var b=new Object();b.obj=a;qm_merge_am(b,1);}else {var b=new Object();b.obj=a;b.sub_subs_updown=ss.merge_sub_subs_updown;b.updown=ss.merge_updown;b.step=(a.offsetWidth/2)/ss.merge_frames;b.oval=".5";if(ss.merge_opacity)b.oval=ss.merge_opacity;if(b.sub_subs_updown&&a.parentNode.className.indexOf("qmmc")==-1)b.updown=true;b.tl="left";b.wh="offsetWidth";if(b.updown){b.tl="top";b.wh="offsetHeight";}b.orig_pos=a.style[b.tl];var c1=a.cloneNode(true);c1.style.visibility="visible";a.parentNode.appendChild(c1);b.cobj=c1;a.style.filter="Alpha(opacity="+(b.oval*100)+")";c1.style.filter="Alpha(opacity="+(b.oval*100)+")";a.style.opacity=b.oval;c1.style.opacity=b.oval;a.style[b.tl]=(parseInt(a.style[b.tl])-(a[b.wh]/2))+"px";c1.style[b.tl]=(parseInt(c1.style[b.tl])+(a[b.wh]/2))+"px";a.ismove=true;qm_merge_ai(qm_merge_am(b),hide);}};function qm_merge_ai(id,hide){var a=qmad.merge["_"+id];if(!a)return;var cp=parseInt(a.obj.style[a.tl]);if(cp+a.step<parseInt(a.orig_pos)){a.obj.style[a.tl]=Math.round(cp+a.step)+"px";a.cobj.style[a.tl]=Math.round(parseInt(a.cobj.style[a.tl])-a.step)+"px";a.timer=setTimeout("qm_merge_ai("+id+","+hide+")",10);}else {a.obj.style[a.tl]=a.orig_pos;a.cobj.style[a.tl]=a.orig_pos;qm_merge_remove_node(a.cobj);a.cobj.style.display="none";a.obj.style.filter="";a.obj.style.opacity="1";qmad.merge["_"+id]=null;a.obj.ismove=false;}};function qm_merge_remove_node(obj){if(obj.removeNode)obj.removeNode(true);else  if(obj.removeChild)obj.parentNode.removeChild(obj);};function qm_merge_am(obj,clear){var k;for(k in qmad.merge){if(qmad.merge[k]&&obj.obj==qmad.merge[k].obj){if(qmad.merge[k].timer){clearTimeout(qmad.merge[k].timer);qmad.merge[k].timer=null;}qm_merge_remove_node(qmad.merge[k].cobj);qmad.merge[k].obj.ismove=false;qmad.merge[k]=null;}}if(clear)return;var i=0;while(qmad.merge["_"+i])i++;qmad.merge["_"+i]=obj;return i;}