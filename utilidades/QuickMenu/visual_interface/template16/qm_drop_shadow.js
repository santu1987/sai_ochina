qmad.shadow=new Object();if(qmad.bvis.indexOf("qm_drop_shadow(b.cdiv);")==-1)qmad.bvis+="qm_drop_shadow(b.cdiv);";if(qmad.bhide.indexOf("qm_drop_shadow(a,1);")==-1)qmad.bhide+="qm_drop_shadow(a,1);";;function qm_drop_shadow(a,hide,force){var z;if(!hide&&((z=window.qmv)&&(z=z.addons)&&(z=z.drop_shadow)&&!z["on"+qm_index(a)]))return;if((!hide&&!a.hasshadow)||force){var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf("qmmc")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(!ss.shadow_offset)return;qmad.shadow.offset=ss.shadow_offset;var f=document.createElement("SPAN");x2("qmshadow",f,1);var fs=f.style;fs.position="absolute";fs.display="block";fs.backgroundColor="#999999";fs.visibility="inherit";var sh;if((sh=ss.shadow_opacity)){f.style.opacity=sh;f.style.filter="alpha(opacity="+(sh*100)+")";}if((sh=ss.shadow_color))f.style.backgroundColor=sh;f=a.parentNode.appendChild(f);a.hasshadow=f;}var c=qmad.shadow.offset;var b=a.hasshadow;if(b){if(hide)b.style.visibility="hidden";else {b.style.width=a.offsetWidth+"px";b.style.height=a.offsetHeight+"px";var ft=0;var fl=0;if(qm_o){ft=b[qp].clientTop;fl=b[qp].clientLeft;}if(qm_s2){ft=qm_gcs(b[qp],"border-top-width","borderTopWidth");fl=qm_gcs(b[qp],"border-left-width","borderLeftWidth");}b.style.top=a.offsetTop+c-ft+"px";b.style.left=a.offsetLeft+c-fl+"px";b.style.visibility="inherit";}}}