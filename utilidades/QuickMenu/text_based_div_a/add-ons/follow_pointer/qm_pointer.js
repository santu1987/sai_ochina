qmad.br_safari=navigator.userAgent.indexOf("afari")+1;qmad.br_navigator=navigator.userAgent.indexOf("Netscape")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;if(!qmad.pointer&&!qmad.br_oldnav){qmad.pointer=new Object();if(window.attachEvent)window.attachEvent("onload",qm_pointer_init);else  if(window.addEventListener)window.addEventListener("load",qm_pointer_init,1);if(window.attachEvent)document.attachEvent("onmouseover",qm_pointer_hide);else  if(window.addEventListener)document.addEventListener("mouseover",qm_pointer_hide,false);};function qm_pointer_init(e,spec){var q=qmad.pointer;var a;for(i=0;i<10;i++){
    if(!(a=document.getElementById("qm"+i))||(spec&&spec!=i))continue;var ss=qmad[a.id];if(ss&&(ss.pointer_main_image||ss.pointer_sub_image)){q.mimg=ss.pointer_main_image;q.mimgw=ss.pointer_main_image_width;if(!q.mimgw)q.mimgw=0;q.mimgh=ss.pointer_main_image_height;if(!q.mimgh)q.mimgh=0;q.malign=ss.pointer_main_align;if(!q.malign)q.malign="top-or-left";q.mox=ss.pointer_main_off_x;if(!q.mox)q.mox=0;q.moy=ss.pointer_main_off_y;if(!q.moy)q.moy=0;q.simg=ss.pointer_sub_image;q.simgw=ss.pointer_sub_image_width;if(!q.mimgw)q.simgw=0;q.simgh=ss.pointer_sub_image_height;if(!q.mimgh)q.mimgh=0;q.salign=ss.pointer_sub_align;if(!q.salign)q.salign="top-or-left";q.sox=ss.pointer_sub_off_x;if(!q.sox)q.sox=0;q.soy=ss.pointer_sub_off_y;if(!q.soy)q.soy=0;qm_pointer_add(a,"m");var at=a.getElementsByTagName("DIV");for(var i=0;i<at.length;i++)qm_pointer_add(at[i],"s");}i++;}};function qm_pointer_add(a,type){var q=qmad.pointer;var img=q[type+"img"];if(a.attachEvent)a.attachEvent("onmousemove",qm_pointer_move);else  if(a.addEventListener)a.addEventListener("mousemove",qm_pointer_move,1);if(!img)return;var sp=document.createElement("SPAN");sp.style.position="absolute";sp.style.visibility="hidden";if(a.ch)sp.style.top=(-q[type+"imgh"]+q[type+"oy"])+"px";else sp.style.left=(-q[type+"imgw"]+q[type+"ox"])+"px";if(q[type+"align"]=="bottom-or-right")sp.pointerbr=1;sp.pointerox=q[type+"ox"];sp.pointeroy=q[type+"oy"];sp.innerHTML='<img style="position:absolute;" src="../../../../../recursos/QuickMenu/text_based_div_a/add-ons/follow_pointer/'+img+'"  width='+q[type+"imgw"]+' height='+q[type+"imgh"]+'>';sp=a.appendChild(sp);a.haspointer=sp;};function qm_pointer_hide(){var q=qmad.pointer;if(q.lastm&&a!=q.lastm){q.lastm.style.visibility="hidden";q.lastm=null;}};function qm_pointer_move(e){var q=qmad.pointer;e=e||window.event;targ=e.srcElement||e.target;while(targ.tagName!="DIV")targ=targ[qp];if(q.lastm&&a!=q.lastm){q.lastm.style.visibility="hidden";q.lastm=null;}var a;if(a=targ.haspointer){
    if(a.style.visibility!="inherit")a.style.visibility="inherit";var x=e.clientX;var y=e.clientY;var oxy=qm_pointer_get_offsets(targ);if(targ.ch){a.style.left=(x-oxy[0]+a.pointerox)+"px";if(a.pointerbr)a.style.top=(targ.offsetHeight+a.pointeroy)+"px";}else {a.style.top=(y-oxy[1]+a.pointeroy)+"px";if(a.pointerbr)a.style.left=(targ.offsetWidth+a.pointerox)+"px";}q.lastm=a;}};function qm_pointer_get_offsets(a){var x=0;var y=0;while(!qm_a(a)){x+=a.offsetLeft;y+=a.offsetTop;a=a[qp];}if(qmad.br_safari)a=a.offsetParent;x+=a.offsetLeft;y+=a.offsetTop;return new Array(x,y);}