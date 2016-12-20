
var qmv = new Object();
if (!window.qmad) qmad = new Object();

qmv.track = new Object();
qmv.preview_mode = true;
qmv.id = -1;
qmv.base_zindex = 999999;
qmv.texturl_state = "text";
qmv.ms_hide_timer = 500;
qmv.ms_show_timer = 0;
qmv.color_dispaly_type = "HEX";
qmv.color_apply_type = "HEX";
qmv.tree_collapse = false;
qmv.interface_hide_selected_box = false;
qmv.base = "http://www.opencube.com/qmv4/";
qmv.pointer = new Object();
qmv.skins = new Object();
qmv.cursor = "hand";
qmv.is101 = false;
qmv.pure = true;

qmv.globaldividers_sub = true;
qmv.globaldividers_main = false;
qmv.globaldividers_above = false;
qmv.globaldividers_below = false;


if ((window.location+"").toLowerCase().indexOf("visual_interface")+1)
{
	qmv.base = "../qmv4/";
	qmv.is_installed_version = true;
	
}

if ((window.location+"").toLowerCase().indexOf("qmvdesign101")+1)
{
	qmv.is_developer = 1;
	qmv.base = "";
	qmv.is_installed_version = true;
}

if ((window.location+"").toLowerCase().indexOf("opencube.com")+1)
{
	qmu = false;
	//qmv.free_use = true;
	qmv.is_online = true;
}

if (window.qm_free_init)
{
	qmu = false;
	qmv.free_use = true;
}

qmad.br_ie = window.showHelp;
qmad.br_navigator = navigator.userAgent.indexOf("Netscape")+1;
qmad.br_version = parseFloat(navigator.vendorSub);
qmad.br_oldnav = qmad.br_navigator && qmad.br_version<7.1;
qmad.br_strict = (dcm = document.compatMode) && dcm=="CSS1Compat";
qmad.br_fox = navigator.userAgent.indexOf("irefox")+1;
qmad.br_ie7 = navigator.userAgent.indexOf("MSIE 7")+1;


if (qmad.br_fox)
	qmv.cursor = "pointer";

if ((qmad.br_ie || qmad.br_fox) && window.name!="qmvtemplateiframe")
{

	if (window.attachEvent)
		window.attachEvent("onload",qmv_load);
	else if (window.addEventListener)
		window.addEventListener("load",qmv_load,true);

	if (window.attachEvent)
		window.attachEvent("onresize",qmv_auto_size_interface_height);
	else if (window.addEventListener)
		window.addEventListener("resize",qmv_auto_size_interface_height,true);


	if (!qmad.br_ie && document.addEventListener)
		document.addEventListener("mousemove",qmv_evt_move_fixcapture,true);
	

	if (window.attachEvent)
		document.attachEvent("onmouseup",qmv_evt_fix_mouse_up);
	else if (window.addEventListener)
		document.addEventListener("mouseup",qmv_evt_fix_mouse_up,true);


	


	 window.onerror = qmv_log_errors;



	qmv_init();
}
else
{
	qmv = null;

	if (window.name!="qmvtemplateiframe")
		alert("To use the QuickMenu Visual interface load this page using Firefox or Internet Explorer.");
}




function qmv_init_unlock()
{
	qmv.unlock_orig = "";
	if (window.qmu)
		qmv.unlock_orig = "qmu=true";
	else
	{
		var i=0;
		var a;
		while (a = window["qm_unlock"+i])
		{
			qmv.unlock_orig+=window["qm_unlock"+i]+",";
			i++;
		}
		

		qmv.unlock_orig = qmv.unlock_orig.substring(0,qmv.unlock_orig.length-1);
	}

	
}


function qmv_init()
{
	
	var wt = "";



	var a;
	if (a = document.getElementById("qmv_open_visual_interface"))
	{
		qmv.opened_from_save = true;
		a.style.display = "none";
	}



	qmv_init_unlock();
	qmv_init_addons();

	if (!qmad.bvis) qmad.bvis = "";
	if (!qmad.bhide) qmad.bhide = "";
	if (!qmad.bhover) qmad.bhover = "";

	qmad.bhover += "qm_evt_menu_item_click(o);"

	//save the initial hide timer setting
	if (window.qm_th)
		qmv.ms_hide_timer = qm_th;
	
	
	if (!window.qm_create)
	{
		wt += '<style type="text/css">';
		wt += qmv_pubgen_get_core_css();
		wt += '</style>';

		wt +=  '<sc'+'ript type="text/javascript">'+qmv_get_source_code()+'</scr'+'ipt>';
		qmv.is_blank = true;
	}


	wt += '<style type="text/css">.qmvistreestyles{}</style>';
	wt += '<style type="text/css">.qmvibcssstyles{}</style>';
	wt += '<style type="text/css">.qmfv{visibility:inherit !important;}.qmfh{visibility:hidden !important;}';

	wt += '.qmvi-dialog-container {z-index:'+(qmv.base_zindex+100)+';background-color:#eeeeee;visibility:hidden;position:absolute;font-size:13px;border-width:1px;border-style:solid;border-color:#828EA2;}';
	wt += '.qmvi-dialog-content-container {padding:10px;}';
	wt += '.qmvi-dialog-button {margin-left:5px;font-size:12px;padding:0px 6px 0px 6px;}';
	wt += '.qmvi-dialog-input-title {font-size:12px;color:#222222;}';
	
	wt += '.qmvi-container {z-index:'+qmv.base_zindex+';background-color:#FBFBFF;visibility:hidden;position:absolute;font-size:13px;border-width:1px;border-style:solid;border-color:#828EA2;}';
	wt += '.qmvi-common {font-family:Arial;text-decoration:none;}';
	
	wt += '.qmvi-title {color:#ffffff;background-image:url('+qmv.base+'images/title_bg.gif);cursor:default;font-size:1em;font-weight:normal;padding:4px 0px 4px 4px;background-color:#3e4d67;border-width:0px 0px 1px 0px;border-style:solid;border-color:#828EA2}';
	wt += '.qmvi-menu {font-size:1em;padding:1px 0px 1px 2px;background-color:#DFE7EF;border-width:0px 0px 1px 0px;border-style:solid;border-color:#828EA2}';
	wt += '.qmvi-menu-item {font-size:.9em;padding:1px 4px 1px 3px;display:inline;color:#333333;border-color:#DFE7EF;border-style:solid;border-width:1px 1px 1px 1px;}';
	wt += '.qmvi-menu-item:hover {border-color:#999999;border-top-color:#ffffff;border-left-color:#ffffff;background-color:#DaE1Eb;color:#111111;}';
	wt += '.qmvi-buttons {font-size:.9em;padding:6px 0px 0px 4px;background-color:transparent;border-width:0px 0px 0px 0px;border-style:solid;border-color:#828EA2}';
	wt += '.qmvi-texturl {font-size:.9em;padding:4px 0px 6px 4px;background-color:transparent;border-width:0px 0px 0px 0px;border-style:solid;border-color:#828EA2}';
	wt += '.qmvi-colordialog-border {border-width:1px;border-color:#888888;border-style:solid;}';
	wt += '.qmvi-colordialog-inputs {border-width:1px;border-color:#888888;border-style:solid;font-size:12px;text-align:right;padding-right:4px;}';
	wt += '.qmvi-colordialog-titles {font-size:12px;color:#222222;}';
	wt += '.qmvi-colordialog-brightbar-parts {height:11px;font-size:1px;}';
	wt += '.qmvi-publish-title {padding-bottom:3px;color:#222222;font-size:13px;border-color:#888888;border-width:0px 0px 1px 0px;border-style:solid;}';


	
	wt += '#qm99, #qm98 div {background-color:#f6f6f6;border-color:#828EA2;border-style:solid;border-width:1px;padding:5px 0px 5px 0px;}';
	wt += '#qm99 a, #qm98 div a {font-family:Arial;font-size:12px;color:#333333;text-decoration:none;padding:2px 30px 2px 5px;margin:0px 5px 0px 5px;}';
	wt += '#qm99 a:hover, #qm98 div a:hover {background-color:#828EA2;color:#ffffff;}';


	wt += '#qm98 {}';
	wt += '#qm98 a {text-decoration:none;font-size:12px;padding:3px 6px 3px 5px;color:#333333;border-style:none;}';
	wt += '#qm98 a:hover {background-color:#828EA2;color:#ffffff}';
	wt += 'body #qm98 .qmactive {background-color:#828EA2;color:#ffffff}';
	wt += '#qm98 div a{border-style:none;padding-left:14px;}';
	wt += '#qm98 div{}';
	

	wt += '.qmvi-tree-container {font-size:1em;padding:0px;background-color:transparent;}';
		

	wt += '#qmvtree {width:auto !important;height:auto !important;background-color:#f4f4f4;border-width:0px 1px 1px 0px;border-style:solid;border-color:#828EA2;}';
	wt += '#qmvtree a {background-repeat:repeat-x;background-position:bottom;background-image:url('+qmv.base+'images/tree_bg.gif);border-width:1px 0px 0px 0px;border-style:solid;border-color:#828EA2;color:#333333;padding:5px 0px 5px 4px;font-size:1em;text-decoration:none;}';
	wt += '#qmvtree a:hover {color:#dd3300;}';
	wt += '#qmvtree div a {background-image:none;border-style:none;padding:0px 0px 0px 0px;margin-left:5px;}';
	wt += '#qmvtree .qmactive {background-image:url('+qmv.base+'images/tree_bg_hl.gif);text-decoration:none;}';
	wt += '#qmvtree div .qmactive {background-image:none;}';
	

	wt += '#qmsetbox {margin-left:10px;}';
	wt += '#qmsetbox a {color:#222222;font-size:1em;text-decoration:none;}';
	

	wt += '.qmvtree-col1 {font-size:12px;color:#00224A;text-align:right;white-space:nowrap;width:90px;}';
	wt += '.qmvtree-col2 {}';
	wt += '.qmvtree-col3 {color:#888888;font-weight:bold;}';
	wt += '.qmvtree-col4 {padding-right:5px;}';

	var tv2 = "width:100%;";
	var tv1 = "";
	if (qmad.br_ie) tv1 = "position:absolute;"
	if (qmad.br_ie7) tv2 = "width:95%;";

	if (qmad.br_ie)
	{
		wt += '.qmvtree-radio{margin-bottom:-1px;}';	
		wt += '.qmvtree-checkbox{margin-bottom:0px;}';	
	}
	else
	{
		wt += '.qmvtree-radio{margin-bottom:-2px;}';
		wt += '.qmvtree-checkbox{margin-bottom:1px;}';
	}
	
	wt += '.qmvtree-style-name{cursor:help;}';
	wt += '.qmvtree-custlegend{border-width:1px 1px 1px 1px;border-style:solid;border-color:#999999;font-size:12px;display:block;position:absolute;padding:0px 4px 1px 4px;background-color:#ffffff;color:#003366;margin-top:-23px;margin-left:0px;width:60px;}';
	wt += '.qmvtree-custfieldset{border-width:1px 0px 0px 0px;border-style:solid;border-color:#999999;padding:13px 5px 5px 5px;}';
	wt += '.qmvtree-input {'+tv1+tv2+'font-family:Arial;padding:0px;margin:0px;border-width:0px;background-color:transparent;font-size:12px;}';
	wt += '.qmvtree-input-dialog{position:relative;width:100%;}';
	wt += '.qmvtree-input-container-dialog {display:block;position:relative;padding:3px;border-width:1px;border-color:#888888;border-style:solid;background-color:#ffffff;}';
	wt += '.qmvtree-input-container {margin-bottom:0px;height:15px;display:block;position:relative;padding:1px 0px 1px 3px;border-width:1px;border-color:#888888;border-style:solid;background-color:#ffffff;}';

	if (qmad.br_ie && !qmad.br_strict)
	{
		
		wt += '.qmvtree-input-container-dialog {height:24px !important;}';
		wt += '.qmvtree-input-container {height:18px !important;}';
	}

	wt += '.qmvtree-colon{color:#888888;font-weight:bold;}';
	wt += '.qmvtree-button{cursor:default;display:block;text-align:center;background-color:#e6e6e6;color:#222222;font-family:Arial;font-size:13px;font-weight:normal;border-width:1px; border-color:#888888; border-top-color:#cccccc;border-left-color:#cccccc;border-style:solid; width:16px; height:16px;}';
	wt += '.qmvtree-button-apply{margin-left:10px;font-size:11px;width:60px;text-align:center;}';
	
	if (!qmad.br_ie)
	{	
		var tipos = "3px 2px";
		var bipos = "3px 1px";
		wt += '.qmvtree-button-up{height:8px;font-size:1px;display:block;background-image:url('+qmv.base+'images/spinner_up.gif);background-position:'+tipos+';background-repeat:no-repeat;border-bottom-width:0px;}';
		wt += '.qmvtree-button-down{height:8px;font-size:1px;display:block;background-image:url('+qmv.base+'images/spinner_down.gif);background-position:'+bipos+';background-repeat:no-repeat;border-top-width:0px;}';
	}
	else
	{
		wt += '.qmvtree-button-up{height:8px;font-size:1px;display:block;border-bottom-width:0px;}';
		wt += '.qmvtree-button-up img{margin-left:1px;margin-top:2px;}';
		wt += '.qmvtree-button-down{height:8px;font-size:1px;display:block;border-top-width:0px;}';
		wt += '.qmvtree-button-down img{margin-left:1px;margin-top:1px;}';
	}

	wt += '.qmvtree-bracket{display:block;margin-left:-10px;color:#888888;}';
	wt += '.qmvtree-rule{color:#888888;}';
	wt += '.qmvtree-close-show{margin-bottom:10px;}';
	wt += '.qmv-icon-buttons{border-width:1px;border-color:#888888;border-style:solid;margin-left:1px;display:block;}';

	wt += '</style>';



	//add the add on code
	
	wt += '<scr'+'ipt type="text/javascript">';
	wt +=  qmv_get_add_on_code("all");
	wt += '</scr'+'ipt>';
	
	/*
	wt += '<scr'+'ipt type="text/javascript" src="qm_keyboard_access.js">';
	wt += '</scr'+'ipt>';
	
	wt += '<scr'+'ipt type="text/javascript" src="qm_images.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_tree_menu.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_slide_effect.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_bump_effect.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_merge_effect.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_drop_shadow.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_round_corners.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_match_widths.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_tabs.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_tabs_css.js">';
	wt += '</scr'+'ipt>';
	
	wt += '<scr'+'ipt type="text/javascript" src="qm_item_bullets.js">';
	wt += '</scr'+'ipt>';

	wt += '<scr'+'ipt type="text/javascript" src="qm_item_bullets_css.js">';
	wt += '</scr'+'ipt>';
	
	wt += '<scr'+'ipt type="text/javascript" src="qm_over_select.js">';
	wt += '</scr'+'ipt>';
	
	wt += '<scr'+'ipt type="text/javascript" src="qm_auto_position_subs.js">';
	wt += '</scr'+'ipt>';
	
	wt += '<scr'+'ipt type="text/javascript" src="qm_pointer.js">';
	wt += '</scr'+'ipt>';
	
	wt += '<scr'+'ipt type="text/javascript" src="qm_box_effect.js">';
	wt += '</scr'+'ipt>';
	
	wt += '<scr'+'ipt type="text/javascript" src="qm_round_items.js">';
	wt += '</scr'+'ipt>';
	*/
	
	
	qmad.qmsetbox = new Object();	
	qmad.qmsetbox.tree_hide_focus_box = true;
	qmad.qmsetbox.tree_auto_collapse = true;			
	qmad.qmsetbox.tree_expand_step_size = 15;
	qmad.qmsetbox.tree_collapse_step_size = 20;
	qmad.qmsetbox.tree_expand_animation = 0
	qmad.qmsetbox.tree_collapse_animation = 0
	qmad.qmsetbox.tree_width = "100%";
	qmad.qmsetbox.tree_sub_indent = 10; 
	qmad.qmsetbox.tree_sub_top_padding = 5;
	qmad.qmsetbox.tree_sub_bottom_padding = 5;

	qmad.qmsetbox.ibullets_main_image = qmv.base+"images/sub_plus.gif";
	qmad.qmsetbox.ibullets_main_image_active = qmv.base+"images/sub_plus_active.gif";
	qmad.qmsetbox.ibullets_main_image_hover = qmv.base+"images/sub_plus_hover.gif";
	qmad.qmsetbox.ibullets_main_image_width_height = "5,5";
	qmad.qmsetbox.ibullets_main_image_margin = "0px 20px 0px 0px";	//top, right, bottom, left
	qmad.qmsetbox.ibullets_main_position = "-8,5";			//left, top

	qmad.qmsetbox.ibullets_sub_image = qmv.base+"images/sub_plus.gif";
	qmad.qmsetbox.ibullets_sub_image_active = qmv.base+"images/sub_plus_active.gif";
	qmad.qmsetbox.ibullets_sub_image_hover = qmv.base+"images/sub_plus_hover.gif";
	qmad.qmsetbox.ibullets_sub_image_width_height = "5,5";
	qmad.qmsetbox.ibullets_sub_image_margin = "0px 20px 0px 0px";	//top, right, bottom, left
	qmad.qmsetbox.ibullets_sub_position = "-8,5";			//left, top



	//visual tree menu settings
	qmad.qmvtree = new Object();	
	qmad.qmvtree.tree_hide_focus_box = true;
	qmad.qmvtree.tree_auto_collapse = true;			
	qmad.qmvtree.tree_expand_step_size = 15;
	qmad.qmvtree.tree_collapse_step_size = 20;
	qmad.qmvtree.tree_expand_animation = 0
	qmad.qmvtree.tree_collapse_animation = 0
	qmad.qmvtree.tree_width = "100%";
	qmad.qmvtree.tree_sub_indent = 10; 
	qmad.qmvtree.tree_sub_top_padding = 5;
	qmad.qmvtree.tree_sub_bottom_padding = 5;

	qmad.qmvtree.ibullets_apply_to = "parent"  //parent, non-parent, all

	qmad.qmvtree.ibullets_main_image = qmv.base+"images/main_plus.gif";
	qmad.qmvtree.ibullets_main_image_active = qmv.base+"images/main_plus_active.gif";
	qmad.qmvtree.ibullets_main_image_hover = qmv.base+"images/main_plus_hover.gif";
	qmad.qmvtree.ibullets_main_image_width_height = "13,13";
	qmad.qmvtree.ibullets_main_image_margin = "0px 5px 0px 0px";	//top, right, bottom, left
	qmad.qmvtree.ibullets_main_position = "-12,7"; 			//left, top

	if (qmad.br_ie)
		qmad.qmvtree.ibullets_main_right = "90%";
	else
		qmad.qmvtree.ibullets_main_right = "92%";

	qmad.qmvtree.ibullets_sub_image = qmv.base+"images/sub_plus.gif";
	qmad.qmvtree.ibullets_sub_image_active = qmv.base+"images/sub_plus_active.gif";
	qmad.qmvtree.ibullets_sub_image_hover = qmv.base+"images/sub_plus_hover.gif";
	qmad.qmvtree.ibullets_sub_image_width_height = "5,5";
	qmad.qmvtree.ibullets_sub_image_margin = "0px 20px 0px 0px";	//top, right, bottom, left
	qmad.qmvtree.ibullets_sub_position = "-8,5";			//left, top
	

	wt += qmv_init_context();
	wt += qmv_init_interface();
	wt += qmv_init_dialog();

	
	document.write(wt);


	qm_vtree_init_styles();
	qm_vtree_init_styles(true);

	
	
	
}


function qmvi_kill_select(e)
{
	
	if (qmad.br_fox)
		return;	

	e = e || window.event;
	var targ = e.srcElement || e.target;

	if (targ.tagName=="INPUT" || targ.tagName=="TEXTAREA")
		return;

	qm_kille(e);
	return false;
	

}


function qmv_init_context()
{

	var wt = "";

	var fil = "filter:alpha(opacity=50);"
	if (!qmad.br_ie) fil = "opacity:.5;";

	wt += '<div id="qmvi_context" style="z-index:'+(qmv.base_zindex+200)+';position:absolute;visibility:hidden;top:10px;left:10px;">'
	wt += '<div id="qmvi_context_shadow" style="background-color:#555555;position:absolute;'+fil+'"></div>';
	wt += '<table cellpadding=0 cellspacing=0><tr><td><div id="qm99" class="qmmc">';
	wt += '</div></table></tr></td></div>';


	return wt;

}


function qmv_init_interface()
{


	var wt = "";

	var dbl = "";
	if (qmad.br_ie) dbl = 'ondblclick="this.click()"';

	
	wt += '<div id="qmvi" onselectstart="qmvi_kill_select(event)"  onclick="qmv_hide_context();qmv_evt_kill_click(event)" class="qmvi-container" style="width:286px;background-color:#dddddd;">'
	

		wt += '<div id="qmvi_title" class="qmvi-common qmvi-title" onmouseup="qmv_evt_title_mouseup(event,this)" onmousemove="qmv_evt_title_mousemove(event,this)" onmousedown="qmv_evt_title_mousedown(event,this)" style="">Visual CSS QuickMenu</div>'

		wt += '<div class="qmvi-common qmvi-menu" style="">'
			wt += '<div id="qm98" class="qmmc">';

			wt += '<a href="javascript:void(0);"  onclick="qmv_update_all_main_checks();qmv.context_clicked=true;qmc_oo(event,this)">File</a>';


				wt += '<div>';
				wt += '<a onclick="qmv_evt_menu_item_click(\'save\')" onfocus="this.blur()" href="javascript:void(0);">Save</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a onclick="qmv_evt_menu_item_click(\'quick_publish\')" onfocus="this.blur()" href="javascript:void(0);">Quick Publish</a>';
				wt += '<a onclick="qmv_evt_menu_item_click(\'publish\')" onfocus="this.blur()" href="javascript:void(0);">Custom Publish</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'import\');">Import</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a onclick="qmv_evt_menu_item_click(\'preview\')" onfocus="this.blur()" href="javascript:void(0);">Preview</a>';
				wt += '</div>';

			wt += '<a href="javascript:void(0);"  onclick="qmv_update_all_main_checks();qmv.context_clicked=true;qmc_oo(event,this)">View</a>';


				wt += '<div ischecks=1>';
				wt += '<a onclick="qmv_evt_menu_item_click(\'preview\')" onfocus="this.blur()" href="javascript:void(0);">Preview</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'specs\');">Menu Specs</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a ctype="view" ccat="full" onclick="qmv_evt_menu_item_click(\'iface_switch_full\')" onfocus="this.blur()" href="javascript:void(0);">Full Interface</a>';
				wt += '<a ctype="view" ccat="inpage" onclick="qmv_evt_menu_item_click(\'iface_switch_inpage\')" onfocus="this.blur()" href="javascript:void(0);">In-Page Design</a>';
				wt += '</div>';


			wt += '<a href="javascript:void(0);"  onclick="qmv_update_all_main_checks();qmv.context_clicked=true;qmc_oo(event,this)">Modify</a>';


				wt += '<div>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'edit texturl\');">Edit Text / URL</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'item_image\');">Item Images</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'create_rule\')">Create Rule</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'apply_custom_class\')">Custom Classes</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'add item\')">Add Item</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'insert item\')">Insert Item</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'delete item\')">Delete Item</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'add sub menu\')">Add Sub Menu</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'copy item\')">Copy Item</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'paste item\')">Paste Item</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move up\')">Move Item Up</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move down\')">Move Item Down</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'add menu\')">Add Menu</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'delete menu\')">Delete Menu</a>';
				wt += '</div>';


			wt += '<a href="javascript:void(0);"  onclick="qmv_update_all_main_checks();qmv.context_clicked=true;qmc_oo(event,this);">Add-Ons</a>';
		

				wt += '<div ischecks=1>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="box_effect" onclick="qmv_context_cmd(event,\'addon_box_effect\');">Box Animation</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="bump_effect" onclick="qmv_context_cmd(event,\'addon_bump_effect\');">Bump Effect</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="slide_effect" onclick="qmv_context_cmd(event,\'addon_slide_effect\');">Slide Effect</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="merge_effect" onclick="qmv_context_cmd(event,\'addon_merge_effect\');">Merge Effect</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="ibcss" onclick="qmv_context_cmd(event,\'addon_ibcss\');">Item Bullets (CSS)</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="tabscss" onclick="qmv_context_cmd(event,\'addon_tabscss\');">Main Tabs (CSS)</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="round_corners" onclick="qmv_context_cmd(event,\'addon_round_corners\');">Rounded Subs</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="ritem" onclick="qmv_context_cmd(event,\'addon_ritem\');">Rounded Items</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="drop_shadow" onclick="qmv_context_cmd(event,\'addon_drop_shadow\');">Drop Shadows</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="match_widths" onclick="qmv_context_cmd(event,\'addon_match_widths\');">Match Widths</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="item_bullets" onclick="qmv_context_cmd(event,\'addon_item_bullets\');">Item Bullets (images)</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="tabs" onclick="qmv_context_cmd(event,\'addon_tabs\');">Main Tabs (images)</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="pointer" onclick="qmv_context_cmd(event,\'addon_pointer\');">Pointer (images)</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="tree_menu" onclick="qmv_context_cmd(event,\'addon_tree_menu\');">Tree Style</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="apsubs" onclick="qmv_context_cmd(event,\'addon_apsubs\');">Keep Subs in Window</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="sopen_auto" onclick="qmv_context_cmd(event,\'addon_sopen_auto\');">Persistent State</a>';
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="keyboard" onclick="qmv_context_cmd(event,\'addon_keyboard\');">Keyboard Control</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" ctype="addon" ccat="over_select" onclick="qmv_context_cmd(event,\'addon_over_select\');">Select Tag Fix (IE)</a>';
				wt += '</div>';




			wt += '<a href="javascript:void(0);"  onclick="qmv_update_all_main_checks();qmv.context_clicked=true;qmc_oo(event,this)">Settings</a>';

				wt += '<div ischecks=1>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'options\');">Options</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'structure\');">HTML Structure</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'quick_color_edits\');">Quick Color Edits</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'color_schemes\');">Color Schemes</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" ctype="settings" ccat="main_horizontal" onclick="qmv_context_cmd(event,\'set_main_horizontal\');">Horizontal Mains</a>';
				wt += '<a href="javascript:void(0)" ctype="settings" ccat="main_vertical" onclick="qmv_context_cmd(event,\'set_main_vertical\');">Vertical Mains</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" ctype="settings" ccat="sub_horizontal" onclick="qmv_context_cmd(event,\'set_sub_horizontal\');">Horizontal Subs</a>';
				wt += '<a href="javascript:void(0)" ctype="settings" ccat="sub_vertical" onclick="qmv_context_cmd(event,\'set_sub_vertical\');">Vertical Subs</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'show_delay\');">Show Delay</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'hide_delay\');">Hide Delay</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" ctype="settings" ccat="onclick" onclick="qmv_context_cmd(event,\'on_click\');">On Click</a>';
				wt += '<a href="javascript:void(0)" ctype="settings" ccat="onmouseover" onclick="qmv_context_cmd(event,\'on_mouse_over\');">On Mouse Over</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'divider_styles\');">Divider Styles</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'insert_divider\');">Insert Divider</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'global_dividers\');">Apply Globally</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'title_styles\');">Title Styles</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'insert_title\');">Insert Title</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'stripe_styles\');">Striping Styles</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'global_stripes\');">Apply globally</a>';
				wt += '</div>';	

		
			wt += '<a href="javascript:void(0);"  onclick="qmv_update_all_main_checks();qmv.context_clicked=true;qmc_oo(event,this)">Help</a>';	

				wt += '<div>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'help_index\');">Index</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'help_tips\');">Tips</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'forums\');">Forums</a>';
				wt += qmv_show_context_build_divider();
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'options\');">Unlock</a>';
				wt += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'help_about\');">About</a>';

				wt += '</div>';	

			wt += '<span class="qmclear"> </span></div>';

		wt += '</div>'


		wt += '<div class="qmvi-common qmvi-buttons" style="">'
			wt+='<table border=0 cellspacing=0 cellpadding=0><tr>';


			wt+='<td id="qmvbb_hide_button1" style="vertical-align:top;"><img title="Save Menu(s)" '+dbl+'  onclick="qmv_evt_bb_click(\'save\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_save.gif" width=18 height=18 style="margin-left:0px;"></td>';
			wt+='<td id="qmvbb_hide_button2" style="vertical-align:top;padding-right:5px;"><img title="Publish Menu(s)" '+dbl+' onclick="qmv_evt_bb_click(\'publish\')"class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_publish.gif" width=18 height=18 style=""></td>';


			wt+='<td style="vertical-align:top;"><img title="Add Item" '+dbl+'  onclick="qmv_evt_bb_click(\'add\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_add.gif" width=18 height=18 style="margin-left:0px;"></td>';
			wt+='<td style="vertical-align:top;"><img title="Insert Item" '+dbl+' onclick="qmv_evt_bb_click(\'insert\')"class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_insert.gif" width=18 height=18 style=""></td>';
			wt+='<td style="vertical-align:top;"><img title="Delete Item" '+dbl+' onclick="qmv_evt_bb_click(\'delete\')"class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_delete.gif" width=18 height=18 style=""></td>';
			wt+='<td style="vertical-align:top;padding-right:5px;"><img title="Add Sub Menu" '+dbl+' onclick="qmv_evt_bb_click(\'addsub\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_addsub.gif" width=18 height=18 style=""></td>';

			wt+='<td style="vertical-align:top;"><img title="Move Item Up" '+dbl+' onclick="qmv_evt_bb_click(\'moveup\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_moveup.gif" width=18 height=18 style=""></td>';
			wt+='<td style="vertical-align:top;padding-right:5px;"><img title="Move Item Down" '+dbl+' onclick="qmv_evt_bb_click(\'movedown\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_movedown.gif" width=18 height=18 style=""></td>';

			wt+='<td style="vertical-align:top;"><img title="Copy Item" onclick="qmv_evt_bb_click(\'copyitem\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_copy_item.gif" width=18 height=18 style=""></td>';
			wt+='<td style="vertical-align:top;padding-right:5px;"><img title="Paste Item" onclick="qmv_evt_bb_click(\'pasteitem\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_paste_item.gif" width=18 height=18 style=""></td>';
	
			wt+='<td style="vertical-align:top;"><img title="Add Menu" onclick="qmv_evt_bb_click(\'addmenu\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/add_menu.gif" width=18 height=18 style=""></td>';
			wt+='<td style="vertical-align:top;padding-right:5px;"><img title="Delete Menu" onclick="qmv_evt_bb_click(\'deletemenu\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/delete_menu.gif" width=18 height=18 style=""></td>';


			wt+='<td style="vertical-align:top;padding-right:5px;"><img title="Preview Menu" onclick="qmv_evt_bb_click(\'preview\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_preview.gif" width=18 height=18 style=""></td>';

			wt+='<td id="qmvbb_hide_button3" style="vertical-align:top;padding-right:5px;"><img title="Menu Specs" onclick="qmv_evt_bb_click(\'specs\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_specs.gif" width=18 height=18 style=""></td>';

			wt+='<td id="qmvbb_hide_button4" style="vertical-align:top;"><img title="Insert Divider" onclick="qmv_evt_bb_click(\'insert_divider\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_adddivider.gif" width=18 height=18 style=""></td>';
			wt+='<td id="qmvbb_hide_button5" style="vertical-align:top;padding-right:5px;"><img title="Divider Styles" onclick="qmv_evt_bb_click(\'style_divider\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_styledivider.gif" width=18 height=18 style=""></td>';

			wt+='<td id="qmvbb_hide_button6" style="vertical-align:top;"><img title="Insert Title" onclick="qmv_evt_bb_click(\'insert_title\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_addtitle.gif" width=18 height=18 style=""></td>';
			wt+='<td id="qmvbb_hide_button7" style="vertical-align:top;padding-right:5px;"><img title="Title Styles" onclick="qmv_evt_bb_click(\'style_title\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_styletitle.gif" width=18 height=18 style=""></td>';

			wt+='<td id="qmvbb_hide_button8" style="vertical-align:top;"><img title="Quick Color Editor" onclick="qmv_evt_bb_click(\'quickcolors\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_bb_quickcolors.gif" width=18 height=18 style=""></td>';
	


			wt+='</tr></table>';
		wt += '</div>';

		wt += '<div class="qmvi-common qmvi-texturl">'
			wt+='<table width=100% border=0 cellspacing=0 cellpadding=0><tr>';
			wt+='<td style="vertical-align:top;"><img title="Edit Url" id="qmv_texturl_url" onclick="qm_switch_texturl_state(\'url\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_url_off.gif" width=18 height=18 style="margin-left:0px;"></td>';
			wt+='<td style="vertical-align:top;"><img title="Edit Text / HTML" id="qmv_texturl_text" '+dbl+' onclick="qm_switch_texturl_state(\'text\')" class="qmv-icon-buttons" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/qmv_text.gif" width=18 height=18 style=""></td>';

			wt += '</td>';
			wt+='<td style="width:100%;vertical-align:top;padding-right:1px;padding-left:1px;"><div class="qmvtree-input-container" style="height:16px;"><input dtype="texturl" onkeypress="qmv_evt_update_tree_value_enter(event,this)" onchange="qmv_evt_update_tree_value(this)" id="qmv_texturl_field" category="texturl" class="qmvtree-input" style="padding-top:1px;"></div></td>';
			wt+='<td style="padding-right:4px;vertical-align:top;"><span id="qmv_texturl_field_bb" onclick="qmv_evt_build_button_click(this)" class="qmvtree-button" style="height:18px;width:18px;">...</span></td>';
			wt+='</tr></table>';
		wt += '</div>';

		
		wt += '<div id="qmvi_menu_panel" onclick="qmv_click_document_element()" style="overflow:scroll;position:absolute;display:none;background-color:#ffffff;border-width:1px 1px 1px 1px;border-style:solid;border-color:#828EA2;"> </div>';
		wt += '<div  id="qmvi_tree_menu_container" class="qmvi-common qmvi-tree-container" style="background-color:#f4f4f4;position:relative;border-width:1px 1px 1px 1px;border-style:solid;border-color:#828EA2;padding:0px;margin:0px 4px 4px 4px;overflow-y:scroll;height:300px;">';
		wt += qmv_init_interface_tree();
		wt += '</div>';

	wt += '</div>'

	return wt;


}


function qmv_init_dialog()
{

	var wt = "";
	

	var op = "opacity:.5;";
	if (qmad.br_ie)
		op = "filter:alpha(opacity=50);";
	

	wt += '<div id="qmvi_setbox_shadow" onselectstart="qmvi_kill_select(event)"  style="'+op+'z-index:'+(qmv.base_zindex+100)+';background-color:#888888;position:absolute;"></div>'
	wt += '<div id="qmvi_setbox" onselectstart="qmvi_kill_select(event)"   onclick="qmv_evt_kill_click(event)" class="qmvi-dialog-container" style="width:270px;">'

		var z = "";
		if (qmad.br_ie)
			z = "zoom:1;";

		wt += '<div style="font-size:1px;height:0px;width:0px;position:absolute;text-align:right;margin-left:100%;"><img onclick="qmv_hide_dialog(false,false,true)" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/close_button.gif" width=14 height=14 style="position:absolute;display:block;left:-20px;top:5px;"></div>'
		wt += '<div id="qmvi_setbox_dialog_title" style="'+z+'" class="qmvi-common qmvi-title" onmouseup="qmv_evt_title_mouseup(event,this)" onmousemove="qmv_evt_title_mousemove(event,this)" onmousedown="qmv_evt_title_mousedown(event,this,4)" style="">Menu Properties</div>'
		wt += '<div id ="qmvi_setbox_content" class="qmvi-common qmvi-dialog-content-container"><div id="qmsetbox" class="qmmc"></div></div>';


	wt += '</div>';
	
	
	wt += '<div id="qmvi_dialog_shadow" onselectstart="qmvi_kill_select(event)" style="'+op+'z-index:'+(qmv.base_zindex+100)+';background-color:#888888;position:absolute;"></div>'
	wt += '<div id="qmvi_dialog"  onselectstart="qmvi_kill_select(event)"  onkeypress="qmv_dialog_onkeypress(event)" onclick="qmv_evt_kill_click(event)" class="qmvi-dialog-container" style="width:260px;">'

		var z = "";
		if (qmad.br_ie)
			z = "zoom:1;";

		wt += '<div style="font-size:1px;height:0px;width:0px;position:absolute;text-align:right;margin-left:100%;"><img onclick="qmv_hide_dialog()" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/close_button.gif" width=14 height=14 style="position:absolute;display:block;left:-20px;top:5px;"></div>'
		wt += '<div id="qmvi_dialog_title" style="'+z+'" class="qmvi-common qmvi-title" onmouseup="qmv_evt_title_mouseup(event,this)" onmousemove="qmv_evt_title_mousemove(event,this)" onmousedown="qmv_evt_title_mousedown(event,this,1)" style="">Visual QuickMenu</div>'
		wt += '<div id ="qmvi_dialog_content" class="qmvi-common qmvi-dialog-content-container">asdfasdf</div>';
		wt += '<div id ="qmvi_dialog_buttons" class="qmvi-common" style="padding:0px 10px 7px 10px;text-align:right;"></div>';


	wt += '</div>';


	wt += '<div id="qmvi_msg_dialog_shadow" onselectstart="qmvi_kill_select(event)" style="'+op+'z-index:'+(qmv.base_zindex+100)+';background-color:#888888;position:absolute;"></div>'
	wt += '<div id="qmvi_msg_dialog" onselectstart="qmvi_kill_select(event)"  onkeypress="qmv_dialog_onkeypress(event)" onclick="qmv_evt_kill_click(event)" class="qmvi-dialog-container" style="width:260px;">'

		var z = "";
		if (qmad.br_ie)
			z = "zoom:1;";

		wt += '<div style="font-size:1px;height:0px;width:0px;position:absolute;text-align:right;margin-left:100%;"><img onclick="qmv_hide_dialog(true)" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/close_button.gif" width=14 height=14 style="position:absolute;display:block;left:-20px;top:5px;"></div>'
		wt += '<div id="qmvi_msg_dialog_title" style="'+z+'" class="qmvi-common qmvi-title" onmouseup="qmv_evt_title_mouseup(event,this)" onmousemove="qmv_evt_title_mousemove(event,this)" onmousedown="qmv_evt_title_mousedown(event,this,2)" style="">Visual QuickMenu</div>'
		wt += '<div id ="qmvi_msg_dialog_content" class="qmvi-common qmvi-dialog-content-container">asdfasdf</div>';
		wt += '<div id ="qmvi_msg_dialog_buttons" class="qmvi-common" style="padding:0px 10px 5px 10px;text-align:right;"></div>';


	wt += '</div>';

	wt += '<div id="qmvi_help_dialog_shadow" onselectstart="qmvi_kill_select(event)"  style="'+op+'z-index:'+(qmv.base_zindex+100)+';background-color:#888888;position:absolute;"></div>'
	wt += '<div id="qmvi_help_dialog" onselectstart="qmvi_kill_select(event)"  onkeypress="qmv_dialog_onkeypress(event)" onclick="qmv_evt_kill_click(event)" class="qmvi-dialog-container" style="width:260px;">'

		var z = "";
		if (qmad.br_ie)
			z = "zoom:1;";

		wt += '<div style="font-size:1px;height:0px;width:0px;position:absolute;text-align:right;margin-left:100%;"><img onclick="qmv_hide_dialog(false,true)" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/close_button.gif" width=14 height=14 style="position:absolute;display:block;left:-20px;top:5px;"></div>'
		wt += '<div id="qmvi_help_dialog_title" style="'+z+'" class="qmvi-common qmvi-title" onmouseup="qmv_evt_title_mouseup(event,this)" onmousemove="qmv_evt_title_mousemove(event,this)" onmousedown="qmv_evt_title_mousedown(event,this,3)" style="">Visual QuickMenu</div>'
		wt += '<div id ="qmvi_help_dialog_content" class="qmvi-common qmvi-dialog-content-container">asdfasdf</div>';
		wt += '<div id ="qmvi_help_dialog_buttons" class="qmvi-common" style="padding:0px 10px 5px 10px;text-align:right;"></div>';


	wt += '</div>';


	

	return wt;
}



function qmv_init_interface_tree()
{
	var wt = "";
	wt += '<div id="qmvtree" class="qmmc">'


	wt += '<a href="#" initshow=1 isfilter=1 id="qmvtree_filter" style="border-top-width:0px;">CSS Styles [filtered]</a>'	

		wt += '<div id="qmvtree_menu_styles_filtered" rule="skin">'
		
			wt += '<span style="display:block;margin:18px 10px 0px 0px;">';
			wt += '<table width=100% cellspacing=0 cellpadding=0 border=0><tr>';
			wt += '<td width=50% class="qmvtree-custfieldset"><span class="qmvtree-custlegend">Values</span><span style="position:relative;display:block;text-align:left;">add<input onclick="qmv_filter_change()" id="qmvf_value0" "class="qmvtree-checkbox" type="checkbox"> edit<input onclick="qmv_filter_change()" id="qmvf_value1" class="qmvtree-checkbox" type="checkbox"></span></td>'
			wt += '<td><span style="display:block;font-size:1px;width:15px;"</td>';
			wt += '<td width=50% class="qmvtree-custfieldset"><span class="qmvtree-custlegend">Group</span><span style="position:relative;display:block;text-align:left;">main<input onclick="qmv_filter_change()" id="qmvf_group0" name="qmvf_group" class="qmvtree-radio" type="radio"> sub<input onclick="qmv_filter_change()" id="qmvf_group1" name="qmvf_group" class="qmvtree-radio" type="radio"></span></td>'
			wt += '</tr></table>';
			wt += '</span>';

			wt += '<span style="display:block;margin:18px 10px 0px 0px;">';
			wt += '<table width=100% cellspacing=0 cellpadding=0 border=0><tr>';
			wt += '<td width=100% class="qmvtree-custfieldset"><span class="qmvtree-custlegend">Section</span><span style="position:relative;display:block;text-align:left;">containers<input onclick="qmv_filter_change()" id="qmvf_section0" name="qmvf_section" class="qmvtree-radio" type="radio"> items<input onclick="qmv_filter_change()" id="qmvf_section1" name="qmvf_section"  class="qmvtree-radio" type="radio"></span></td>'
			wt += '</tr></table>';
			wt += '</span>';

			wt += '<span style="display:block;margin:18px 10px 5px 0px;">';
			wt += '<table width=100% cellspacing=0 cellpadding=0 border=0><tr>';
			wt += '<td width=100% class="qmvtree-custfieldset"><span class="qmvtree-custlegend">Style Type</span><span style="position:relative;display:block;text-align:left;">color<input onclick="qmv_filter_change()" id="qmvf_style0" name="qmvf_style" class="qmvtree-radio" type="radio"> font<input onclick="qmv_filter_change()" id="qmvf_style1" name="qmvf_style" class="qmvtree-radio" type="radio"> border<input onclick="qmv_filter_change()" id="qmvf_style2" name="qmvf_style" class="qmvtree-radio" type="radio"> other<input onclick="qmv_filter_change()" id="qmvf_style3" name="qmvf_style" class="qmvtree-radio" type="radio"></span></td>'
			wt += '</tr></table>';
			wt += '</span>';
		
			wt += '<span style="display:block;font-size:1px;height:5px;"></span>';
			wt += '<a href="#" isfresultsa=1 initshow=1>Filter Results <span id="qmvtree_filter_results_qty"></span></a>';

				wt += '<div id="qmvtree_filter_results">'
				wt += '<a href="#">place holder</a>';
				wt += '</div>';

			wt += '<span style="display:block;font-size:1px;height:5px;"></span>';

		wt += '</div>'


		
	wt += '<a href="#" >CSS Styles [unfiltered]</a>'

		wt += '<div id="qmvtree_css_styles">'
		wt += '<a initshow=1 href="#">Main</a>'
			wt += '<div group="main">'

			wt += '<a href="#" ruledesc="Container"><span isruledesc=1></span></a>'
				wt+='<div section="container" rule="#qm[i]" inheritrule="2@#qm[i] div">';
				wt += qmv_init_interface_tree_bracket(true);
				wt += qmv_init_interface_tree_item('width','width','width','css','unit',null,'x<0');
				wt += qmv_init_interface_tree_item('height','height','height','css','unit',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);

				wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
					wt+='<div rule="#qm[i]" inheritrule="2@#qm[i] div">';
					wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
					wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
					wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
					wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
					wt+='</div>';

				wt += '<a href="#">Border Styles</a>'
					wt+='<div rule="#qm[i]" inheritrule="2@#qm[i] div">';
					wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
					wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
					wt+='</div>';

				wt += qmv_init_interface_tree_bracket();
				wt+='</div>';

			wt += '<a href="#">Items</a>'
				wt += '<div section="item">'
				
				wt += '<a href="#" ruledesc="Static"><span isruledesc=1></span></a>'
					wt+='<div ftitle="Static" rule="#qm[i] a" inheritrule="#qm[i] div a">';
					wt += qmv_init_interface_tree_bracket(true);
					wt += qmv_init_interface_tree_item('width','width','width','css','unit',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] a" inheritrule="#qm[i] div a|4@#qm[i] .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Static" rule="#qm[i] a" inheritrule="#qm[i] div a|4@#qm[i] .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt += qmv_init_interface_tree_item('align','text-align','textAlign','css','textalign',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] a" inheritrule="#qm[i] div a|4@#qm[i] .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';



				wt += '<a href="#" ruledesc="Hover"><span isruledesc=1></span></a>'
					wt+='<div rule="#qm[i] a:hover" inheritrule="#qm[i] div a:hover|3@body #qm[i] .qmactive">';
					wt += qmv_init_interface_tree_bracket(true);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Hover" rule="#qm[i] a:hover" inheritrule="#qm[i] div a:hover|3@body #qm[i] .qmactive|4@#qm[i] a:hover .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Hover" rule="#qm[i] a:hover" inheritrule="#qm[i] div a:hover|3@body #qm[i] .qmactive|4@#qm[i] a:hover .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Hover" rule="#qm[i] a:hover" inheritrule="#qm[i] div a:hover|3@body #qm[i] .qmactive|4@#qm[i] a:hover .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';


				wt += '<a href="#" ruledesc="Parent"><span isruledesc=1></span></a>'
					wt+='<div ftitle="Parent" rule="#qm[i] .qmparent" inheritrule="#qm[i] div .qmparent">';
					wt += qmv_init_interface_tree_bracket(true);
					wt += qmv_init_interface_tree_item('width','width','width','css','unit',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Parent" rule="#qm[i] .qmparent" inheritrule="#qm[i] div .qmparent">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Parent" rule="#qm[i] .qmparent" inheritrule="#qm[i] div .qmparent">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Parent" rule="#qm[i] .qmparent" inheritrule="#qm[i] div .qmparent">';
						wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';



				wt += '<a href="#" ruledesc="Active"><span isruledesc=1></span></a>'
					wt+='<div rule="body #qm[i] .qmactive" inheritrule="body #qm[i] div .qmactive">';
					wt += qmv_init_interface_tree_bracket(true);

					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Active" rule="body #qm[i] .qmactive" inheritrule="body #qm[i] div .qmactive|4@body #qm[i] .qmactive .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';
					
					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Active" rule="body #qm[i] .qmactive" inheritrule="body #qm[i] div .qmactive|4@body #qm[i] .qmactive .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Active" rule="body #qm[i] .qmactive" inheritrule="body #qm[i] div .qmactive|4@body #qm[i] .qmactive .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';
					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';


				wt += '<a href="#" ruledesc="Persistent"><span isruledesc=1></span></a>'
					wt+='<div rule="body #qm[i] .qmpersistent" inheritrule="body #qm[i] div .qmpersistent">';
					wt += qmv_init_interface_tree_bracket(true);

					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Persistent" rule="body #qm[i] .qmpersistent" inheritrule="body #qm[i] div .qmpersistent|4@body #qm[i] .qmpersistent .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';
					
					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Persistent" rule="body #qm[i] .qmpersistent" inheritrule="body #qm[i] div .qmpersistent|4@body #qm[i] .qmpersistent .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Persistent" rule="body #qm[i] .qmpersistent" inheritrule="body #qm[i] div .qmpersistent|4@body #qm[i] .qmpersistent .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';
					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';

				wt += '</div>'

			wt+='</div>';

		wt += '<a initshow=1  href="#">Subs</a>'

			wt += '<div group="sub">'

			wt += '<a href="#" ruledesc="Container"><span isruledesc=1></span></a>'
				wt+='<div section="container" rule="#qm[i] div">';
				wt += qmv_init_interface_tree_bracket(true);
				wt += qmv_init_interface_tree_item('width','width','width','css','unit',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);

				wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
					wt+='<div rule="#qm[i] div" inheritrule="5@addon:round_corners">';
					wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
					wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
					wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
					wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
					wt+='</div>';

				wt += '<a href="#">Border Styles</a>'
					wt+='<div rule="#qm[i] div" inheritrule="5@addon:round_corners">';
					wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
					wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
					wt+='</div>';

				wt += qmv_init_interface_tree_bracket();
				wt+='</div>';

			wt += '<a href="#">Items</a>'
				wt += '<div section="item">'
				
				wt += '<a href="#" ruledesc="Static"><span isruledesc=1></span></a>'
					wt+='<div ftitle="Static" rule="#qm[i] div a">';
					wt += qmv_init_interface_tree_bracket(true);
					wt += qmv_init_interface_tree_item('width','width','width','css','unit',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] div a" inheritrule="4@#qm[i] div .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Static" rule="#qm[i] div a" inheritrule="4@#qm[i] div .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt += qmv_init_interface_tree_item('align','text-align','textAlign','css','textalign',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] div a" inheritrule="4@#qm[i] div .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';



				wt += '<a href="#" ruledesc="Hover"><span isruledesc=1></span></a>'
					wt+='<div rule="#qm[i] div a:hover" inheritrule="3@body #qm[i] div .qmactive">';
					wt += qmv_init_interface_tree_bracket(true);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Hover" rule="#qm[i] div a:hover" inheritrule="3@body #qm[i] div .qmactive|4@#qm[i] div a:hover .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Hover" rule="#qm[i] div a:hover" inheritrule="3@body #qm[i] div .qmactive|4@#qm[i] div a:hover .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Hover" rule="#qm[i] div a:hover" inheritrule="3@body #qm[i] div .qmactive|4@#qm[i] div a:hover .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';


				wt += '<a href="#" ruledesc="Parent"><span isruledesc=1></span></a>'
					wt+='<div ftitle="Parent" rule="#qm[i] div .qmparent">';
					wt += qmv_init_interface_tree_bracket(true);
					wt += qmv_init_interface_tree_item('width','width','width','css','unit',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Parent" rule="#qm[i] div .qmparent">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Parent" rule="#qm[i] div .qmparent">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Parent" rule="#qm[i] div .qmparent">';
						wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';

				wt += '<a href="#" ruledesc="Active"><span isruledesc=1></span></a>'
					wt+='<div rule="body #qm[i] div .qmactive">';
					wt += qmv_init_interface_tree_bracket(true);

					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Active" rule="body #qm[i] div .qmactive" inheritrule="4@body #qm[i] div .qmactive .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';
					
					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Active" rule="body #qm[i] div .qmactive" inheritrule="4@body #qm[i] div .qmactive .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Active" rule="body #qm[i] div .qmactive" inheritrule="4@body #qm[i] div .qmactive .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';
					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';


				wt += '<a href="#" ruledesc="Persistent"><span isruledesc=1></span></a>'
					wt+='<div rule="body #qm[i] div .qmpersistent">';
					wt += qmv_init_interface_tree_bracket(true);

					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Persistent" rule="body #qm[i] div .qmpersistent" inheritrule="4@body #qm[i] div .qmpersistent .qmritem span">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';
					
					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Persistent" rule="body #qm[i] div .qmersistent" inheritrule="4@body #qm[i] div .qmpersistent .qmritem span">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Persistent" rule="body #qm[i] div .qmpersistent" inheritrule="4@body #qm[i] div .qmpersistent .qmritem span">';
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';
					wt += qmv_init_interface_tree_bracket();
					wt+='</div>';	

				wt += '</div>'

			wt+='</div>';

		wt += '</div>'


	wt += '<a href="#">CSS Styles [custom]</a>'

		wt+='<div id="qmvtree_custom_rules">';
			wt += qmv_init_interface_tree_item(null,'Add Custom CSS Rule','custom_rule','plus');
		wt += '</div>'


	wt += '<a href="#" initshow=1>Add-Ons</a>'

		wt += '<div id="qmvtree_addon_settings">'
		wt += '<a href="#">Animation Effects</a>'


			wt += '<div>'
			wt += qmv_init_interface_tree_addon_title("Box Animation","box_effect");
			
				wt += '<div rule="addon"  id="qmvtree_box" addontype="box_effect">'
				wt += qmv_init_interface_tree_item(null,'frames','box_animation_frames','addon','int',20,"x<0 || x>200");
				wt += qmv_init_interface_tree_item(null,'accelerator','box_accelerator','addon','float',".4|none","x<0 || x>10");
				wt += qmv_init_interface_tree_item(null,'position','box_position','addon','box-position',"center|none");
				

				wt += '<a style="margin-top:10px;" href="#" ruledesc="Box Styles"><span isruledesc=1></span></a>'	
					wt += '<div rule="#qm[i] .qmbox">'

					wt += qmv_init_interface_tree_bracket(true);

					
						wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
							wt+='<div ftitle="Static" rule="#qm[i] .qmbox">';
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt+='</div>';


						wt += '<a href="#">Border Styles</a>'
							wt+='<div ftitle="Static" rule="#qm[i] .qmbox">';
							wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',"1px|none",'x<0');
							wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',"solid|none",null);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',"#999999|none",null);
							wt+='</div>';

					wt += qmv_init_interface_tree_bracket();
					wt += '</div>'
					wt += '<span style="display:block;font-size:1px;height:10px;"></span>';

				wt += '</div>'


			wt += qmv_init_interface_tree_addon_title("Bump Subs","bump_effect");
			
				wt += '<div rule="addon" addontype="bump_effect">'
				
				wt += qmv_init_interface_tree_item(null,'frames','bump_animation_frames','addon','int',10,"x<0 || x>200");
				wt += qmv_init_interface_tree_item(null,'distance','bump_distance','addon','int','20|none',"x<0");
				wt += qmv_init_interface_tree_item(null,'main dir.','bump_main_direction','addon','bump-direction','left|none');
				wt += qmv_init_interface_tree_item(null,'sub dir.','bump_sub_direction','addon','bump-direction');
				wt += qmv_init_interface_tree_item(null,'auto mains','bump_auto_switch_main_left_right_directions','addon','bool','true|none');
				wt += '</div>'

			wt += qmv_init_interface_tree_addon_title("Slide Subs","slide_effect");
			
				wt += '<div rule="addon" addontype="slide_effect">'
				wt += qmv_init_interface_tree_item(null,'frames','slide_animation_frames','addon','int',20,"x<0 || x>200");
				wt += qmv_init_interface_tree_item(null,'accelerator','slide_accelerator','addon','float',null,"x<0 || x>10");
				wt += qmv_init_interface_tree_item(null,'main right','slide_left_right','addon','bool');
				wt += qmv_init_interface_tree_item(null,'sub right','slide_sub_subs_left_right','addon','bool');
				wt += qmv_init_interface_tree_item(null,'offset xy','slide_offxy','addon','int');
				wt += qmv_init_interface_tree_item(null,'drop subs','slide_drop_subs','addon','bool',null,null,"qmv_update_slide_drop_subs(a)");
				wt += qmv_init_interface_tree_item(null,'drop height','slide_drop_subs_height','addon','int',null,null,"qmv_update_slide_drop_subs_height(a)");
				wt += qmv_init_interface_tree_item(null,'drop hide','slide_drop_subs_disappear','addon','bool');
				wt += '</div>'


			wt += qmv_init_interface_tree_addon_title("Merge Subs","merge_effect");
			
				wt += '<div rule="addon" addontype="merge_effect">'
				wt += qmv_init_interface_tree_item(null,'frames','merge_frames','addon','int',20,"x<0 || x>20");
				wt += qmv_init_interface_tree_item(null,'main up','merge_updown','addon','bool');
				wt += qmv_init_interface_tree_item(null,'subs up','merge_sub_subs_updown','addon','bool');
				wt += qmv_init_interface_tree_item(null,'opacity','merge_opacity','addon','float',null,"x<0 || x>1");
				wt += '</div>'

			wt += '</div>'


		wt += '<a initshow=1  href="#" >CSS Shapes <span style="color:#dd3300;">[imageless]</span></a>'

			wt += '<div>'


			wt += qmv_init_interface_tree_addon_title("Item Bullets","ibcss");
			
				wt += '<div rule="addon" addontype="ibcss" addor="ibcss_main_type|ibcss_sub_type">'
				wt += qmv_init_interface_tree_item(null,'apply to','ibcss_apply_to','addon','ibullets-apply',"parent|none",null,null);

				wt += '<a href="JavaScript:void(0);" >Main</a>'
					wt += '<div rule="addon" addontype="ibcss">'
					wt += qmv_init_interface_tree_item(null,'type','ibcss_main_type','addon','ibcss-type',"arrow-head|or");
					wt += qmv_init_interface_tree_item(null,'direction','ibcss_main_direction','addon','ibcss-direction',"down|none");
					wt += qmv_init_interface_tree_item(null,'size','ibcss_main_size','addon','int',"5|none","x<0");


					wt += '<a href="JavaScript:void(0);" >Colors</a>'
						wt += '<div rule="addon" addontype="ibcss">'
						wt += qmv_init_interface_tree_item(null,'bg-static','ibcss_main_bg_color','addon','color');
						wt += qmv_init_interface_tree_item(null,'bg-hover','ibcss_main_bg_color_hover','addon','color');
						wt += qmv_init_interface_tree_item(null,'bg-active','ibcss_main_bg_color_active','addon','color');
						wt += qmv_init_interface_tree_item(null,'border-static','ibcss_main_border_color','addon','color');
						wt += qmv_init_interface_tree_item(null,'border-hover','ibcss_main_border_color_hover','addon','color');
						wt += qmv_init_interface_tree_item(null,'border-active','ibcss_main_border_color_active','addon','color');
						wt += '</div>';

					

					wt += '<a href="JavaScript:void(0);" >Position</a>'
						wt += '<div rule="addon" addontype="ibcss">'
						wt += qmv_init_interface_tree_item(null,'offset x','ibcss_main_position_x','addon','int',"-16|none");
						wt += qmv_init_interface_tree_item(null,'offset y','ibcss_main_position_y','addon','int',"-5|none");
						wt += qmv_init_interface_tree_item(null,'align x','ibcss_main_align_x','addon','textalign',"right|none");
						wt += qmv_init_interface_tree_item(null,'align y','ibcss_main_align_y','addon','verticalalign',"middle|none");
						wt += '</div>';

					wt += '</div>';

				wt += '<a href="JavaScript:void(0);" >Sub</a>'
					wt += '<div rule="addon" addontype="ibcss">'
					wt += qmv_init_interface_tree_item(null,'type','ibcss_sub_type','addon','ibcss-type',"arrow-head|or");
					wt += qmv_init_interface_tree_item(null,'direction','ibcss_sub_direction','addon','ibcss-direction',"right|none");
					wt += qmv_init_interface_tree_item(null,'size','ibcss_sub_size','addon','int',"5|none","x<0");


					wt += '<a href="JavaScript:void(0);" >Colors</a>'
						wt += '<div rule="addon" addontype="ibcss">'
						wt += qmv_init_interface_tree_item(null,'bg-static','ibcss_sub_bg_color','addon','color');
						wt += qmv_init_interface_tree_item(null,'bg-hover','ibcss_sub_bg_color_hover','addon','color');
						wt += qmv_init_interface_tree_item(null,'bg-active','ibcss_sub_bg_color_active','addon','color');
						wt += qmv_init_interface_tree_item(null,'border-static','ibcss_sub_border_color','addon','color');
						wt += qmv_init_interface_tree_item(null,'border-hover','ibcss_sub_border_color_hover','addon','color');
						wt += qmv_init_interface_tree_item(null,'border-active','ibcss_sub_border_color_active','addon','color');
						wt += '</div>';

					

					wt += '<a href="JavaScript:void(0);" >Position</a>'
						wt += '<div rule="addon" addontype="ibcss">'
						wt += qmv_init_interface_tree_item(null,'offset x','ibcss_sub_position_x','addon','int',"-16|none");
						wt += qmv_init_interface_tree_item(null,'offset y','ibcss_sub_position_y','addon','int',"0|none");
						wt += qmv_init_interface_tree_item(null,'align x','ibcss_sub_align_x','addon','textalign',"right|none");
						wt += qmv_init_interface_tree_item(null,'align y','ibcss_sub_align_y','addon','verticalalign',"middle|none");
						wt += '</div>';

					wt += '</div>';
				
				wt += '</div>'	



			wt += qmv_init_interface_tree_addon_title("Tabs","tabscss");
			
				wt += '<div rule="addon" addontype="tabscss">'
				wt += qmv_init_interface_tree_item(null,'type','tabscss_type','addon','tabscss-type',"angled");
				wt += qmv_init_interface_tree_item(null,'size','tabscss_size','addon','int',"5|none","x<0");
				wt += qmv_init_interface_tree_item(null,'offset x','tabscss_left_offset','addon','int');
				wt += qmv_init_interface_tree_item(null,'offset y','tabscss_top_offset','addon','int');
				wt += qmv_init_interface_tree_item(null,'left','tabscss_apply_far_left','addon','bool',"false|none");
				wt += qmv_init_interface_tree_item(null,'right','tabscss_apply_far_right','addon','bool',"false|none");
				wt += qmv_init_interface_tree_item(null,'between','tabscss_apply_middles','addon','bool',"true|none");
				wt += qmv_init_interface_tree_item(null,'bg color','tabscss_bg_color','addon','color',"#ffffff|none");
				wt += qmv_init_interface_tree_item(null,'border color','tabscss_border_color','addon','color');
				wt += '</div>'
			

			
			wt += qmv_init_interface_tree_addon_title("Rounded Subs","round_corners");
			
				wt += '<div rule="addon" addontype="round_corners">'

				wt += '<a style="margin-top:10px;" href="#">Options</a>'
					wt += '<div>';
					wt += qmv_init_interface_tree_item(null,'Blend Colors','rcorner_blend','plus');
					wt+='</div>';
				wt += '<span style="display:block;font-size:1px;height:10px;"></span>';

				wt += qmv_init_interface_tree_item(null,'size','rcorner_size','addon','int',6,"x<0");
				wt += qmv_init_interface_tree_item(null,'padding','rcorner_container_padding','addon','int');
				wt += qmv_init_interface_tree_item('border-color','border color','rcorner_border_color','addon','color');
				wt += qmv_init_interface_tree_item('bg-color','bg color','rcorner_bg_color','addon','color');
				wt += qmv_init_interface_tree_item(null,'opacity','rcorner_opacity','addon','float',null,"x<0 || x>1");
				wt += qmv_init_interface_tree_item(null,'angled','rcorner_angle_corners','addon','bool');
				wt += qmv_init_interface_tree_item(null,'apply','rcorner_apply_corners','addon','corners-bool-array');
				wt += qmv_init_interface_tree_item(null,'top inset','rcorner_top_line_auto_inset','addon','bool');
				wt += '</div>'


			wt += qmv_init_interface_tree_addon_title("Rounded Items","ritem");
			


				wt += '<div rule="addon"  id="qmvtree_ritem" addontype="ritem">'
				wt += qmv_init_interface_tree_item(null,'size','ritem_size','addon','int',4,"x<0 || x>200");
				wt += qmv_init_interface_tree_item(null,'apply','ritem_apply','addon','ritem-apply',"main|none");
				wt += qmv_init_interface_tree_item(null,'angled','ritem_angle_corners','addon','bool');
				wt += qmv_init_interface_tree_item(null,'main corners','ritem_main_apply_corners','addon','corners-bool-array');
				wt += qmv_init_interface_tree_item(null,'sub corners','ritem_sub_apply_corners','addon','corners-bool-array');
				wt += qmv_init_interface_tree_item(null,'actives only','ritem_show_on_actives','addon','bool');
				
				

				wt += '<a style="margin-top:10px;" href="#">Options</a>'
					wt += '<div>';
	
						wt += qmv_init_interface_tree_item(null,'Apply Individual','ritem_individual','plus');
						wt += qmv_init_interface_tree_item(null,'Remove Individual','ritem_individual_remove','plus');
						wt += qmv_init_interface_tree_item(null,'Underlying Main Item Styles','ritem_mitem_styles','plus');
						wt += qmv_init_interface_tree_item(null,'Underlying Sub Item Styles','ritem_sitem_styles','plus');
						wt += qmv_init_interface_tree_item(null,'Remove Main Item Borders','ritem_remove_mborders','plus');
						wt += qmv_init_interface_tree_item(null,'Remove Sub Item Borders','ritem_remove_sborders','plus');

					wt+='</div>';
				

				wt += '<a href="#">Main Styles</a>'
					wt += '<div>';
						
						wt += '<a href="#">Item Content</a>'	
		
							wt += '<div rule="#qm[i] .qmritemcontent">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',"0px 0px 0px 4px|none",'x<0');
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Static]</a>'	
		
							wt += '<div rule="#qm[i] .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',"#666666|none",null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',"#eeeeee|none",null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Hover]</a>'	
		
							wt += '<div rule="#qm[i] a:hover .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Active]</a>'	
		
							wt += '<div rule="body #qm[i] .qmactive .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Persistent]</a>'	
		
							wt += '<div rule="body #qm[i] .qmpersistent .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';

					wt+='</div>';

				wt += '<a href="#">Sub Styles</a>'
					wt += '<div>';
						
						wt += '<a href="#">Item Content</a>'	
		
							wt += '<div rule="#qm[i] div .qmritemcontent">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',"0px 0px 0px 4px|none",'x<0');
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Static]</a>'	
		
							wt += '<div rule="#qm[i] div .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',"#666666|none",null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',"#eeeeee|none",null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Hover]</a>'	
		
							wt += '<div rule="#qm[i] div a:hover .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Active]</a>'	
		
							wt += '<div rule="body #qm[i] div .qmactive .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';


						wt += '<a href="#">Items [Persistent]</a>'	
		
							wt += '<div rule="body #qm[i] div .qmpersistent .qmritem span">'
							wt += qmv_init_interface_tree_bracket(true);
							wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
							wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
							wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
							wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
							wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
							wt += qmv_init_interface_tree_bracket();
							wt+='</div>';
						

					wt+='</div>';


					wt += '<span style="display:block;font-size:1px;height:10px;"></span>';
				

				wt += '</div>'
				

			
			wt += qmv_init_interface_tree_addon_title("Drop Shadows","drop_shadow");
			
				wt += '<div rule="addon" addontype="drop_shadow">'
				wt += qmv_init_interface_tree_item(null,'offset','shadow_offset','addon','int',3);
				wt += qmv_init_interface_tree_item(null,'color','shadow_color','addon','color');
				wt += qmv_init_interface_tree_item(null,'opacity','shadow_opacity','addon','float',null,"x<0 || x>1");
				wt += '</div>'



			wt += qmv_init_interface_tree_addon_title("Match Widths","match_widths");
			
				wt += '<div rule="addon" addontype="match_widths">'
				wt += qmv_init_interface_tree_item(null,'active','mwidths_active','addon','bool',true);
				wt += '</div>'

			
			wt += '</div>'


		wt += '<a href="#">Image Based</a>'
			wt += '<div>'
							
			wt += qmv_init_interface_tree_addon_title("Item Bullets","item_bullets");
			
				wt += '<div rule="addon" addontype="item_bullets" addor="ibullets_main_image|ibullets_sub_image">'
				wt += qmv_init_interface_tree_item(null,'apply to','ibullets_apply_to','addon','ibullets-apply',"parent|none",null,null);

				wt += '<a href="JavaScript:void(0);" >Main</a>'
					wt += '<div rule="addon" addontype="item_bullets">'
					wt += qmv_init_interface_tree_item(null,'static','ibullets_main_image','addon','image',qmv.base+"images/arrow_down.gif|or");
					wt += qmv_init_interface_tree_item(null,'hover','ibullets_main_image_hover','addon','image');
					wt += qmv_init_interface_tree_item(null,'active','ibullets_main_image_active','addon','image');
					wt += qmv_init_interface_tree_item(null,'width','ibullets_main_image_width','addon','int',"9|none","x<0");
					wt += qmv_init_interface_tree_item(null,'height','ibullets_main_image_height','addon','int',"6|none","x<0");

					wt += '<a href="JavaScript:void(0);" >Position</a>'
						wt += '<div rule="addon" addontype="item_bullets">'
						wt += qmv_init_interface_tree_item(null,'offset x','ibullets_main_position_x','addon','int',"-16|none");
						wt += qmv_init_interface_tree_item(null,'offset y','ibullets_main_position_y','addon','int',"-5|none");
						wt += qmv_init_interface_tree_item(null,'align x','ibullets_main_align_x','addon','textalign',"right|none");
						wt += qmv_init_interface_tree_item(null,'align y','ibullets_main_align_y','addon','verticalalign',"middle|none");
						wt += '</div>';

					wt += '</div>';

				wt += '<a href="JavaScript:void(0);" style="margin-bottom:6px;">Sub</a>'
					wt += '<div rule="addon" addontype="item_bullets">'
					wt += qmv_init_interface_tree_item(null,'static','ibullets_sub_image','addon','image',qmv.base+"images/arrow_right.gif|or");
					wt += qmv_init_interface_tree_item(null,'hover','ibullets_sub_image_hover','addon','image');
					wt += qmv_init_interface_tree_item(null,'active','ibullets_sub_image_active','addon','image');
					wt += qmv_init_interface_tree_item(null,'width','ibullets_sub_image_width','addon','int',"6|none","x<0");
					wt += qmv_init_interface_tree_item(null,'height','ibullets_sub_image_height','addon','int',"9|none","x<0");

					wt += '<a href="JavaScript:void(0);" >Position</a>'
						wt += '<div rule="addon" addontype="item_bullets">'
						wt += qmv_init_interface_tree_item(null,'offset x','ibullets_sub_position_x','addon','int',"-12|none");
						wt += qmv_init_interface_tree_item(null,'offset y','ibullets_sub_position_y','addon','int',"-2|none");
						wt += qmv_init_interface_tree_item(null,'align x','ibullets_sub_align_x','addon','textalign',"right|none");
						wt += qmv_init_interface_tree_item(null,'align y','ibullets_sub_align_y','addon','verticalalign',"middle|none");
						wt += '</div>';
					
					wt += '</div>';
				
				wt += '</div>'



			wt += qmv_init_interface_tree_addon_title("Tabs","tabs");
			
				wt += '<div rule="addon" addontype="tabs">'
				wt += qmv_init_interface_tree_item(null,'image','tabs_image','addon','image',qmv.base+"image_library/tab_dividers/tab_divider1.gif");
				wt += qmv_init_interface_tree_item(null,'width','tabs_width','addon','int',"15|none","x<0");
				wt += qmv_init_interface_tree_item(null,'height','tabs_height','addon','int',"8|none","x<0");
				wt += qmv_init_interface_tree_item(null,'offset y','tabs_top_offset','addon','int');
				wt += qmv_init_interface_tree_item(null,'left','tabs_apply_far_left','addon','bool');
				wt += qmv_init_interface_tree_item(null,'right','tabs_apply_far_right','addon','bool');
				wt += qmv_init_interface_tree_item(null,'between','tabs_apply_middles','addon','bool');
				wt += '</div>'



			
			wt += qmv_init_interface_tree_addon_title("Follow Pointer","pointer");
			
				wt += '<div rule="addon" addontype="pointer" addor="pointer_main_image|pointer_sub_image">'
				
				wt += '<a href="JavaScript:void(0);" >Main</a>'
					wt += '<div rule="addon" addontype="pointer">'
					wt += qmv_init_interface_tree_item(null,'image','pointer_main_image','addon','image',qmv.base+"image_library/bullets/arrows/a1_down.gif|or");
					wt += qmv_init_interface_tree_item(null,'width','pointer_main_image_width','addon','int',"8|none","x<0");
					wt += qmv_init_interface_tree_item(null,'height','pointer_main_image_height','addon','int',"6|none","x<0");
					wt += qmv_init_interface_tree_item(null,'align','pointer_main_align','addon','palign',"top-or-left|none");
					wt += qmv_init_interface_tree_item(null,'offset x','pointer_main_off_x','addon','int',"-3|none");
					wt += qmv_init_interface_tree_item(null,'offset y','pointer_main_off_y','addon','int',"-3|none");
					wt += '</div>'	
					
				wt += '<a href="JavaScript:void(0);" style="margin-bottom:6px;">Sub</a>'
					wt += '<div rule="addon" addontype="pointer">'
					wt += qmv_init_interface_tree_item(null,'image','pointer_sub_image','addon','image',qmv.base+"image_library/bullets/arrows/a1_right.gif|or");
					wt += qmv_init_interface_tree_item(null,'width','pointer_sub_image_width','addon','int',"6|none","x<0");
					wt += qmv_init_interface_tree_item(null,'height','pointer_sub_image_height','addon','int',"8|none","x<0");
					wt += qmv_init_interface_tree_item(null,'align','pointer_sub_align','addon','palign',"top-or-left|none");
					wt += qmv_init_interface_tree_item(null,'offset x','pointer_sub_off_x','addon','int',"-3|none");
					wt += qmv_init_interface_tree_item(null,'offset y','pointer_sub_off_y','addon','int',"-3|none");
					wt += '</div>'	

				wt += '</div>'	



			wt += '</div>'


			
		
			

		wt += '<a href="#" >Menu Control</a>'

			wt += '<div>'
			wt += qmv_init_interface_tree_addon_title("Tree Style","tree_menu");
			
				wt += '<div rule="addon" addontype="tree_menu">'


				wt += qmv_init_interface_tree_item(null,'width','tree_width','addon','int',200,"x<0");
				wt += qmv_init_interface_tree_item(null,'sub2+ indent','tree_sub_sub_indent','addon','int',null,"x<0");
				wt += qmv_init_interface_tree_item(null,'hide focus','tree_hide_focus_box','addon','bool');
				wt += qmv_init_interface_tree_item(null,'auto close','tree_auto_collapse','addon','bool',"true|none");
				wt += '<span style="display:block;font-size:1px;height:5px;"></span>';

				wt += '<a href="JavaScript:void(0);" >Expand Animation</a>'
					wt += '<div rule="addon" addontype="tree_menu">'
					wt += qmv_init_interface_tree_item(null,'Type','tree_expand_animation','addon','treeanimationtype');
					wt += qmv_init_interface_tree_item(null,'Step Size','tree_expand_step_size','addon','int',null,"x<0 || x>50");
					wt += '</div>';

				wt += '<a href="JavaScript:void(0);" style="margin-bottom:6px;">Collapse Animation</a>'
					wt += '<div rule="addon" addontype="tree_menu">'
					wt += qmv_init_interface_tree_item(null,'Type','tree_collapse_animation','addon','treeanimationtype');
					wt += qmv_init_interface_tree_item(null,'Step Size','tree_collapse_step_size','addon','int',null,"x<0 || x>50");
					wt += '</div>';

				wt += '<span style="display:block;font-size:1px;height:8px;"></span>';	
				wt += '</div>'
				


			wt += qmv_init_interface_tree_addon_title("Keep Subs In Window","apsubs");
			
				wt += '<div rule="addon" addontype="apsubs">'
				wt += qmv_init_interface_tree_item(null,'active','subs_in_window_active','addon','bool',true);
				wt += '</div>'

			wt += qmv_init_interface_tree_addon_title("Persistent State ","sopen_auto");
			
				wt += '<div rule="addon" addontype="sopen_auto">'

				wt += qmv_init_interface_tree_item(null,'Help','pstate_help','plus');
				wt += '<span style="display:block;font-size:1px;height:7px;"></span>';
				wt += qmv_init_interface_tree_item(null,'Test On Selected Item','pstate_add','plus');
				wt += qmv_init_interface_tree_item(null,'Remove From Selected Item','pstate_remove','plus');
				wt += qmv_init_interface_tree_item(null,'Remove All Test Items','pstate_remove_all','plus');
				wt += '<span style="display:block;font-size:1px;height:7px;"></span>';
				wt += qmv_init_interface_tree_item(null,'Main Persistent Styles','pstate_main_styles','plus');
				wt += qmv_init_interface_tree_item(null,'Sub Persistent Styles','pstate_sub_styles','plus');
				wt += '<span style="display:block;font-size:1px;height:15px;"></span>';

				wt += qmv_init_interface_tree_item(null,'active','sopen_auto_enabled','addon','bool',true);
				wt += qmv_init_interface_tree_item(null,'auto open','sopen_auto_show_subs','addon','bool');
				wt += '</div>'	

			
			wt += qmv_init_interface_tree_addon_title("Keyboard Access","keyboard");
				wt += '<div rule="addon" addontype="keyboard">'
				wt += qmv_init_interface_tree_item(null,'active','keyboard_access_active','addon','bool',true);
				wt += '</div>'

			wt += '</div>'	

		wt += '<a href="#">Browser Fixes</a>'	

			wt += '<div>'
			wt += qmv_init_interface_tree_addon_title("IE6 - Over Select Tags","over_select");
			
				wt += '<div rule="addon" addontype="over_select">'
				wt += qmv_init_interface_tree_item(null,'active','overselects_active','addon','bool',true);
				wt += '</div>'

			wt += '</div>'	



		wt += '<span style="display:block;height:5px;font-size:1px;"> </span>';
		wt += '</div>'



	wt += '<a href="#">Individuals</a>'

		wt += '<div id="qmvtree_individuals">'
		wt += '<a href="#">Inline Styles</a>'

			wt += '<div id="qmvtree_inline_styles">'
			wt += '<a href="#">Item <span class="qmvtree-rule">[ style= ]</span></a>'

				wt += '<div rule="inline">'
				wt += '<span class="qmvtree-bracket">"</span>'	
				wt += qmv_init_interface_tree_item('width','width','width','inline','unit',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'padding','padding','inline','edge-padding',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','inline','edge-margin',null,null);
					
				wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
					wt+='<div rule="inline">';
					wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','inline','color',null,null);
					wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','inline','styleimage',null,null);
					wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','inline','styleimagerepeat',null,null);
					wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','inline','styleimageposition',null,null);
					wt+='</div>';

				wt += '<a href="#">Font Styles</a>'
					wt+='<div rule="inline">';
					wt += qmv_init_interface_tree_item('color','color','color','inline','color',null,null);
					wt += qmv_init_interface_tree_item('family','font-family','fontFamily','inline','fontfamily',null,null);
					wt += qmv_init_interface_tree_item('size','font-size','fontSize','inline','unit',null,'x<0');
					wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','inline','textdecoration',null,null);
					wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
					wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','inline','fontweight',null,null);
					wt += qmv_init_interface_tree_item('align','text-align','textAlign','inline','textalign',null,null);
					wt+='</div>';

				wt += '<a href="#">Border Styles</a>'
					wt+='<div rule="inline">';
					wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','inline','edge-borderwidth',null,'x<0');
					wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','inline','borderstyle',null,null);
					wt += qmv_init_interface_tree_item(null,'border-color','borderColor','inline','color',null,null);
					wt+='</div>';
				wt += '<span class="qmvtree-bracket qmvtree-close-show">"</span>'		
				wt += '</div>'

			wt += '<a href="#">Container <span class="qmvtree-rule">[ style= ]</span></a>'

				wt += '<div rule="inline-parent">'
				wt += '<span class="qmvtree-bracket">"</span>'	
				wt += qmv_init_interface_tree_item('width','width','width','inline','unit',null,'x<0');
				wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','inline','color');
				wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','inline','styleimage',null,null);
				wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','inline','styleimagerepeat',null,null);
				wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','inline','styleimageposition',null,null);
				wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','inline','edge-borderwidth');
				wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','inline','borderstyle');
				wt += qmv_init_interface_tree_item(null,'border-color','borderColor','inline','color');
				wt += qmv_init_interface_tree_item(null,'padding','padding','inline','edge-padding');
				wt += qmv_init_interface_tree_item(null,'margin','margin','inline','edge-margin');
				wt += '<span class="qmvtree-bracket qmvtree-close-show">"</span>'		
				wt += '</div>'

			wt += '</div>'

	
		wt += '<a href="#" initshow=1>Item Extras</a>'

			wt += '<div id = "qmvtree_item_extra_settings">'

			wt += '<a href="#">Image</a>'
				wt += '<div rule="image">'
				wt += qmv_init_interface_tree_item(null,'static','staticimage','iextra','image-script');
				wt += qmv_init_interface_tree_item(null,'width','width','iextra','int',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'height','height','iextra','int',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'alt text','alt','iextra',"text",null,'x<0');
				wt += qmv_init_interface_tree_item(null,'hover','hoverimage','iextra','bool',null,null,null);
				wt += qmv_init_interface_tree_item(null,'active','activeimage','iextra','bool',null,null,null);
				wt += '</div>'


			wt += '<a href="#">Show Container On Load</a>'
				wt += '<div rule="sopen">'
				wt += qmv_init_interface_tree_item(null,'show','sopen','iextra','bool');
				wt += '</div>'
	

			
			wt += '</div>'
		


		wt += '<a href="#">Item Dividers</a>'

			
			wt += '<div id="qmvtree_item_dividers" rule="dividers">'
						
			wt += qmv_init_interface_tree_item(null,'Insert Before Selected Item','insert_divider','plus');
			wt += qmv_init_interface_tree_item(null,'Insert After Selected Item','insert_divider_after','plus');
			wt += qmv_init_interface_tree_item(null,'Globally Apply Dividers','insert_divider_global','plus');
			
			wt += '<a style="margin-top:10px;" href="#" ruledesc="Horizontal"><span isruledesc=1></span></a>'	
			wt += '<div id="qmvtree_css_styles_dividers" rule="#qm[i] .qmdividerx">'

				wt += qmv_init_interface_tree_bracket(true);
				wt += qmv_init_interface_tree_item('height','border-top-width','borderTopWidth','css','unit',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
				wt += qmv_init_interface_tree_item('style','border-style','borderStyle','css','borderstyle',null,null);
				wt += qmv_init_interface_tree_item('color','border-color','borderColor','css','color',null,null);	
				wt += qmv_init_interface_tree_bracket();
				wt += '</div>'

				
			wt += '<a href="#" ruledesc="Vertical"><span isruledesc=1></span></a>'	
			wt += '<div id="qmvtree_css_styles_dividers" rule="#qm[i] .qmdividery">'

				wt += qmv_init_interface_tree_bracket(true);
				wt += qmv_init_interface_tree_item('width','border-left-width','borderLeftWidth','css','unit',null,'x<0');
				wt += qmv_init_interface_tree_item('height','height','height','css','unit',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
				wt += qmv_init_interface_tree_item('style','border-style','borderStyle','css','borderstyle',null,null);
				wt += qmv_init_interface_tree_item('color','border-color','borderColor','css','color',null,null);	
				wt += qmv_init_interface_tree_bracket();
				wt += '</div>'

			wt += '</div>'

			wt += '<a href="#" style="font-size:1px;height:0px;"> </a>'


		
		wt += '<a href="#">Item Titles</a>'
			
			

			wt += '<div id="qmvtree_item_titles" rule="titles">'
						
			
			wt += qmv_init_interface_tree_item(null,'Insert Before Selected Item','insert_title','plus');
			wt += qmv_init_interface_tree_item(null,'Insert After Selected Item','insert_title_after','plus');
			

			wt += '<a style="margin-top:10px;" href="#" ruledesc="Styles"><span isruledesc=1></span></a>'	
			wt += '<div id="qmvtree_css_styles_titles" rule="#qm[i] .qmtitle">'

				wt += qmv_init_interface_tree_bracket(true);

				wt += qmv_init_interface_tree_item('cursor','cursor','cursor','css','cursor',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] .qmtitle">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Static" rule="#qm[i] .qmtitle">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt += qmv_init_interface_tree_item('align','text-align','textAlign','css','textalign',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] .qmtitle">';
						wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

				wt += qmv_init_interface_tree_bracket();
				wt += '</div>'

			wt += '</div>'
			wt += '<a href="#" style="font-size:1px;height:0px;"> </a>'


		
		wt += '<a href="#">Item Striping</a>'
			
			

			wt += '<div id="qmvtree_item_stripes" rule="stripes">'
			wt += qmv_init_interface_tree_item(null,'Apply / Remove Striping Globally','apply_striping_globally','plus');
			wt += qmv_init_interface_tree_item(null,'Apply Individual Stripe','apply_striping_individually','plus');
			wt += qmv_init_interface_tree_item(null,'Remove Individual Stripe','remove_striping_individually','plus');

			wt += '<a style="margin-top:10px;" href="#" ruledesc="Static"><span isruledesc=1></span></a>'	
			wt += '<div id="qmvtree_css_styles_titles" rule="#qm[i] .qmstripe">'

				wt += qmv_init_interface_tree_bracket(true);
				wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] .qmstripe">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Static" rule="#qm[i] .qmstripe">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt += qmv_init_interface_tree_item('align','text-align','textAlign','css','textalign',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] .qmstripe">';
						wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

				wt += qmv_init_interface_tree_bracket();
				wt += '</div>'


			wt += '<a style="" href="#" ruledesc="Hover"><span isruledesc=1></span></a>'	
			wt += '<div id="qmvtree_css_styles_titles" rule="#qm[i] .qmstripe:hover">'

				wt += qmv_init_interface_tree_bracket(true);
				wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
				wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
					
					wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] .qmstripe:hover">';
						wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
						wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
						wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
						wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
						wt+='</div>';

					wt += '<a href="#">Font Styles</a>'
						wt+='<div isfont=1 ftitle="Static" rule="#qm[i] .qmstripe:hover">';
						wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
						wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
						wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
						wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
						wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
						wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
						wt += qmv_init_interface_tree_item('align','text-align','textAlign','css','textalign',null,null);
						wt+='</div>';

					wt += '<a href="#">Border Styles</a>'
						wt+='<div ftitle="Static" rule="#qm[i] .qmstripe:hover">';
						wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
						wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
						wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
						wt+='</div>';

				wt += qmv_init_interface_tree_bracket();
				wt += '</div>'

			wt += '</div>'

			

			

			wt += '<a href="#" style="font-size:1px;height:0px;"> </a>'	

		

		wt += '</div>'

	wt += '<a href="#">Settings <span class="qmvtree-rule">[ qm_create ]</span></a>'	

		wt += '<div id="qmvtree_menu_settings" rule="create">'
		wt += '<span class="qmvtree-bracket" style="padding-left:5px;padding-top:5px;">(</span>'	
		wt += qmv_init_interface_tree_item(null,'vertical','isvertical','create','bool',null,null,"qmv_input_code_ask_width(a)");
		wt += qmv_init_interface_tree_item(null,'show delay','showdelay','create','int',null,'x<0');
		wt += qmv_init_interface_tree_item(null,'hide delay','hidedelay','create','int',null,'x<0');
		wt += qmv_init_interface_tree_item(null,'on click','onclick','create','bool');

		wt += '<a initshow=1  href="#">Sub Menus</a>'	
			wt += '<div rule="create">'
			wt += qmv_init_interface_tree_item(null,'left sided','leftsided','create','bool');
			wt += qmv_init_interface_tree_item(null,'horiztonal','hsubs','create','bool');
			wt += qmv_init_interface_tree_item(null,'flush left','flushleft','create','bool');
			wt += '</div>'

		
		wt += '<span class="qmvtree-bracket qmvtree-close-show" style="padding-left:5px;">)</span>'
		wt += '</div>'



	wt += '<a href="#" style="border-bottom-width:0px;">Skins / Quick Edits</a>'	

		wt += '<div id="qmvtree_menu_skins" rule="skin">'

		wt += '<a isshortcut=1 href="#">Quick Color Editor</a>'	
			wt += '<div id="qmvtree_color_shortcuts">'
			wt += '<a href="#">place holder</a>';
			wt += '</div>';


		wt += '<a href="#">Colors</a>'	
			wt += '<div id="qmvtree_menu_color_skins" rule="color">'
			wt += qmv_init_interface_tree_item(null,'Light Greys','light_grays','skin');
			wt += qmv_init_interface_tree_item(null,'Medium Greys','medium_grays','skin');
			wt += qmv_init_interface_tree_item(null,'Dark Greys','dark_grays','skin');
			wt += qmv_init_interface_tree_item(null,'Black and White','black_white','skin');
			wt += qmv_init_interface_tree_item(null,'Red and Brick','red_brick','skin');
			wt += qmv_init_interface_tree_item(null,'Blue Tones','blue_tones','skin');
			wt += qmv_init_interface_tree_item(null,'Blue / Yellow','blue_yellow','skin');
			wt += qmv_init_interface_tree_item(null,'Blue / Green','blue_green','skin');
			wt += qmv_init_interface_tree_item(null,'Forest Green','forest_green','skin');
			wt += '</div>'		
	
		wt += '<a href="#">Spacing</a>'	
			wt += '<div id="qmvtree_menu_color_spacing" rule="spacing">'
			wt += qmv_init_interface_tree_item(null,'4px Horizontal Main Gaps','h4_main_gaps','skin');
			wt += qmv_init_interface_tree_item(null,'0px Horizontal Main Gaps','h0_main_gaps','skin');
			wt += qmv_init_interface_tree_item(null,'4px Vertical Main Gaps','v4_main_gaps','skin');
			wt += qmv_init_interface_tree_item(null,'0px Vertical Main Gaps','v0_main_gaps','skin');
			wt += qmv_init_interface_tree_item(null,'Small Sub Padding','small_sub_pad','skin');
			wt += qmv_init_interface_tree_item(null,'Medium Sub Padding','medium_sub_pad','skin');
			wt += qmv_init_interface_tree_item(null,'Large Sub Padding','large_sub_pad','skin');
			wt += '</div>'


		wt += '</div>'
	
		

	wt += '<span class="qmclear"> </span></div>';

	return wt;

}

function qmv_init_interface_tree_addon_title(title,iname)
{

	//iname is used for addons without any settings

	var wt = "";

	var ix = "";
	var type = "";
	if (iname)
	{
		
		ix = 'id="qmv_iadd_'+iname+'"';
		type = 'addontype="'+iname+'"';
	}

	if (qmad.br_ie)
		wt += '<a href="#"><input '+type+' '+ix+' isaddon=1 onclick="qmv_evt_addremove_addon(event,this)" style="position:absolute;padding:0px;margin:-3px 0px 0px -2px;" type="checkbox"><span style="margin-left:19px;">'+title+'</span></a>';
	else
		wt += '<a href="#"><input '+type+' '+ix+' isaddon=1 onclick="qmv_evt_addremove_addon(event,this)" style="padding:0px;margin:0px 5px 2px 4px;" type="checkbox"><span>'+title+'</span></a>';	



	return wt;

}

function qmv_init_interface_tree_bracket(is_top)
{

	var wt = "";
	if (is_top)
		wt += '<span class="qmvtree-bracket">{</span>'
	else
		wt += '<span class="qmvtree-bracket qmvtree-close-show">}</span>'


	return wt;

}

function qmv_init_interface_tree_item(showname,sname,cname,category,datatype,add_on_default,range,code,ext_sname)
{

	var wt = "";


	var ad = ""
	var ads = "";
	
	var star = "";
	if (add_on_default)
	{

		var defin = (add_on_default+"").split("|");
		if (!defin[0]) defin[0] = "blank";
		ad = 'addondefault="'+defin[0]+'"';

		if (defin.length<2)
			star = '<span style="color:#dd3300;">*</span> ';
		else if (defin[1]=="or")
			star = '<span style="color:#888888;">*</span> ';
		else
			ads = 'skipdefaultoff=1';		

		
		
	}

	


	wt += '<a sname="'+sname+'" cname="'+cname+'"><table width="100%" border=0 cellspacing=0 callpadding=0><tr>';

	var sep = ' <span class="qmvtree-colon">:</span> '
	if (category=="create")
		sep = " ";
	else if (category=="addon")
		sep = ' <span class="qmvtree-colon">=</span> '
	else if (category=="skin" || category=="plus")
		sep = "";

	if (category=="skin")
		wt += '<td class="qmvi-common qmvtree-col1" style="width:150px;" nowrap>';
	else if (category=="plus")
		wt += '<td class="qmvi-common qmvtree-col1" style="width:100%;" nowrap>';
	else
		wt += '<td filtercol1=1 class="qmvi-common qmvtree-col1" nowrap>';

	
	var uexts = "";
	if (ext_sname)
		uexts = ext_sname;


	var killhelp = false;
	if (category!="plus" && category!="skin")
	{
		killhelp = true;
		wt += "<span class='qmvtree-style-name' onclick='qmv_style_settings_help(event,this)'>";
	}

	if (!showname)
		wt += star+sname+uexts+sep;
	else
		wt += star+showname+uexts+sep;

	if (killhelp)
		wt += "</span>";

	wt += '</td>';

	if (qmad.br_ie)
		wt += '<td class="qmvtree-col2" width=100%>';
	else
		wt += '<td class="qmvtree-col2">';
	
	if (category!="skin" && category!="plus")
	{

		

		wt += '<span class="qmvtree-input-container"><input onfocus="this.prev_value = this.value" code="'+code+'" range="'+range+'" '+ad+' '+ads+' dtype="'+datatype+'" category="'+category+'" sname="'+sname+'" cname="'+cname+'" onkeypress="qmv_evt_update_tree_value_enter(event,this)" onchange="qmv_evt_update_tree_value(this)" class="qmvtree-input"></span>';

		wt += '</td>';

		if (category=="create")
		{
			wt += '<td class="qmvtree-col3">';
			wt += ' , ';
			wt += '</td>';
		}
		else
		{
			wt += '<td class="qmvtree-col3">';
			wt += ' ; ';
			wt += '</td>';
		}

		wt += '<td class="qmvtree-col4">';
		if (datatype.indexOf("unit")+1 || datatype.indexOf("int")+1 ||  datatype.indexOf("float")+1)
		{

			var ied1 = "";
			var ied2 = "";
			if (qmad.br_ie)
			{
				ied1 = 'ondblclick="qmv_evt_build_button_click(this,1)"';
				ied2 = 'ondblclick="qmv_evt_build_button_click(this,2)"';
			}

			if (!qmad.br_ie)
			{
				wt += '<span '+ied1+' onclick="qmv_evt_build_button_click(this,1)" class="qmvtree-button qmvtree-button-up"></span>';		
				wt += '<span '+ied2+' onclick="qmv_evt_build_button_click(this,2)" class="qmvtree-button qmvtree-button-down"></span>';
			}
			else
			{
				wt += '<span '+ied1+' onclick="qmv_evt_build_button_click(this,1)" class="qmvtree-button qmvtree-button-up"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/spinner_up.gif" width=11 height=7></span>';		
				wt += '<span '+ied2+' onclick="qmv_evt_build_button_click(this,2)" class="qmvtree-button qmvtree-button-down"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/spinner_down.gif" width=11 height=7></span>';
			}
		}
		else
		{


			wt += '<span oncontextmenu="qmv_show_context(event,\'build_button\',null,this)" onclick="qmv_evt_build_button_click(this)" class="qmvtree-button">...</span>';

		}
		wt += '</td>';
	}
	else
	{
		if (category=="skin")
		{
			wt += '<span cname="'+cname+'" onclick="qmv_evt_apply_skin(event,this)" class="qmvtree-button qmvtree-button-apply">Apply</span>';
			wt += '</td>';
		}
		else if (category=="plus")
		{
			wt += '<span cname="'+cname+'" onclick="qmv_evt_apply_plus(event,this)" class="qmvtree-button qmvtree-button-apply" style="width:16px;margin-right:4px;">+</span>';
			wt += '</td>';
		}

	}

	wt += '</tr></table></a>';

	return wt;


}

function qmv_load(e,go)
{
		
	if (!go)
	{
		//give time for any other onload functions to complete first
		setTimeout("qmv_load(null,true)",10);	
		return;
	}
	

	//create the tree menu
	qmv.qmvtree = document.getElementById("qmvtree");
	qm_create("vtree",false,0,0,false);






	//create the main menu
	qmad.qm98 = new Object();
	qmad.qm98.shadow_offset = 3;
	qmad.qm98.shadow_color = "#333333";
	qmad.qm98.shadow_opacity = 0.5;
	qmv.addons.drop_shadow.on98 = true;
	qmc_create(98,false,0,0,false);

	


	qmv_load_styles_object();

	if (qmv.is_blank)
		qmv_add_new_menu();


	if (qmv.opened_from_save || qmv.is_installed_version || qmv.is_online)
		qmv_set_interface_mode("full");
	else
	{
		qmv_set_interface_mode("inpage");
		qmv.opened_from_published_doc = true;
	}
	

	
	qmv_design_menu();

	qm_vtree_init();
	qmv_ibullets_init();

	var a;
	if (a = document.getElementById("qmvi_loading_div"))
		a.style.display = "none";

	


	if (qmad.br_ie && !qmad.br_strict)
		qmv_show_dialog("alert",null,'<div style="padding:10px;color:#222222;"><font style="color:#dd3300;">WARNING:</font>  The visual QuickMenu interface is not 100% compatible with loose doctypes in IE! The interface input boxes and other aspects of the visual tool may appear incorrect.<br><br>Use the text-based design method or add a doctype similar to the one below to the top of your HTML page...<br><br><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><br><br></div>',500);


	qmv_show_dialog("splash");
	qmv.loaded = true;


	qmv_track_it("loaded");

}

function qmv_set_interface_mode(type)
{




	if (type=="full")
		qmv.interface_full = true;
	else if (type=="inpage")
		qmv.interface_full = false;
	

	qmv.qmvi = document.getElementById("qmvi");
	
	if (!qmv.interface_full)
	{

		if (qmv.opened_from_published_doc)
			document.body.style.overflow = "";
		
		if (qmv.loaded)
		{

			for (var i=0;i<10;i++)
			{

				var a;
				if (a = document.getElementById("qm"+i))
				{
					var mc;
				
					var ph = document.getElementById("ph"+a.id);
					if (ph)
					{
						var killp = a[qp];

						ph[qp].insertBefore(a,ph);
						ph[qp].removeChild(ph);

						if (killp) killp[qp].removeChild(killp);
					}
					else
					{
						document.body.appendChild(a[qp]);

					}
			
				
				}
	
			}

		


			if (qmv.qmvi.origwidth)
				qmv.qmvi.style.width = qmv.qmvi.origwidth;

	
			var qtc = document.getElementById("qmvi_tree_menu_container");
			qtc.style.width = "";

		}
		

		var bb;
		var bbi=1;
		while (bb = document.getElementById("qmvbb_hide_button"+bbi))
		{
			bb.style.display = "none";
			bbi++;
		}



		
		var mp = document.getElementById("qmvi_menu_panel");
		mp.style.display = "none";
		
		var dw = document.documentElement.offsetWidth;
		qmv.qmvi.style.left = (dw-qmv.qmvi.offsetWidth)-30+"px";
		qmv.qmvi.style.top = "10px";
		qmv_auto_size_interface_height();


	}
	else
	{


		
		document.body.style.overflow = "hidden";


		qmv.qmvi.origwidth = qmv.qmvi.style.width;

				
		var mp = document.getElementById("qmvi_menu_panel");
		mp.style.fontSize = "16px";
		var cc = 30;
		for (var i=0;i<10;i++)
		{
			
			var a;
			if (a = document.getElementById("qm"+i))
			{
				
				var mc;
				mc = document.createElement("DIV");
				
				mc.menufloater = 1;
				mc.style.position = "relative";

				mc.style.top = cc+"px";
				mc.style.left = "0px";
				mc.style.paddingLeft = "20px";
				mc.style.paddingRight = "20px";
				if (qmad.br_ie)
					mc.style.zoom = 1;

				mc.onmouseup = function(event){qmv_evt_title_mouseup(event,this)}
				mc.onmousemove = function(event){qmv_evt_title_mousemove(event,this)}
				mc.onmousedown = function(event){qmv_evt_title_mousedown(event,this,this,true)}

				var ph = document.createElement("DIV");
				ph.style.position = "absolute";
				ph.id = "ph"+a.id;
				a[qp].insertBefore(ph,a);

				mc.appendChild(a);
				mc = mp.appendChild(mc);

			
				cc+=200;
			}

		}


		
		var bb;
		var bbi=1;
		while (bb = document.getElementById("qmvbb_hide_button"+bbi))
		{
			bb.style.display = "";
			bbi++;
		}
		

		qmv.interface_full_loaded = true;
		qmv_resize_interface(new Object(),true);
	}


	if (qmv.loaded && qmad.br_ie)		
	{
		qmv_set_all_subs_to_default_position(true,qmad.br_ie);
		qmv_position_pointer();
	}



}

function qmv_resize_interface(init)
{

	if (!qmv.loaded && !init)
		return;

	if (qmv.interface_full && qmv.interface_full_loaded)
	{
		
		var qtc = document.getElementById("qmvi_tree_menu_container");

		if (init)
		{
			qtc.style.width = qtc.offsetWidth-2+"px";
			qmv.qmvi.style.top="0px";
			qmv.qmvi.style.left = "0px";	
		}

		
		
		var dd = qm_get_doc_wh();
		qmv.qmvi.style.width = dd[0]-1+"px";
		
		var seth = Math.abs(qtc.offsetTop-dd[1])-7+"px";
		
		qtc.style.height = seth;

		var mp = document.getElementById("qmvi_menu_panel");
		mp.style.height = seth;
		mp.style.top = qtc.offsetTop+"px";
		var t1 = (qtc.offsetWidth+qtc.offsetLeft+5);
		mp.style.left = t1+"px";
		mp.style.width = (Math.abs(t1-dd[0])-6)+"px";
		mp.style.display = "block";
		
	}
}


function qmv_get_doc_wh()
{	
	

	db = document.body;
	var w=0;
	var h=0;

	if (tval = window.innerHeight)
	{
		h = tval;
		w = window.innerWidth;
		
	}
	else if ((e = document.documentElement) && (e = e.clientHeight))
	{
		
		h = e;
		w = document.documentElement.clientWidth;
		
	}
	else if (e = db.clientHeight)
	{
		if (!h) h = e;
		if (!w) w = db.clientWidth;
	}

	
	return new Array(w,h);

}




function qmv_auto_size_interface_height()
{
	
	if (!qmv.interface_full)
	{
		var wd = qmv_lib_get_window_dimensions();
		var aobj = document.getElementById("qmvi");
		var bobj = document.getElementById("qmvi_tree_menu_container");
		var coreh = aobj.offsetHeight - bobj.offsetHeight;
		nbh = (wd[1]-coreh-30-aobj.offsetTop);
		if (nbh<100) nbh = 100;
		bobj.style.height = nbh+"px"
	}
	else
		qmv_resize_interface();

}



function qmv_load_styles_object()
{

	qmv.styles = qmv_lib_get_qm_stylesheet();
	if (qmv.styles.cssRules)
		qmv.style_rules = qmv.styles.cssRules;
	else if (qmv.styles.rules)
		qmv.style_rules = qmv.styles.rules;


}


function qmv_get_single_unlock()
{

	var wt = "";


	if (qmv.unlock_type)
	{

		if (qmv.unlock_type=="single")
		{
			wt += qmv.unlock_string;
			wt += "var qmu;qm_unlock();;function qm_unlock(){var i=0;var v;var lh=location.href.toLowerCase();while(v=window[\"qm_unlock\"+i]){v=v.replace(/./g,x1);if(lh.indexOf(\"http\")==-1||lh.indexOf(v)+1)qmu=true;i++;}};function x1(a,b){return String.fromCharCode(a.charCodeAt(0)-1-(b-(parseInt(b/4)*4)));}";
		}
		else
			wt += qmv.unlock_string+";";

	}


	

	return wt;


}

function qmv_get_pure_css_javascript()
{


	return ";function qm_pure(sd){if(sd.tagName==\"UL\"){var nd=document.createElement(\"DIV\");nd.qmpure=1;var c;if(c=sd.style.cssText)nd.style.cssText=c;qm_convert(sd,nd);var csp=document.createElement(\"SPAN\");csp.className=\"qmclear\";csp.innerHTML=\" \";nd.appendChild(csp);sd=sd[qp].replaceChild(nd,sd);sd=nd;}return sd;};function qm_convert(a,bm,l){if(!l){bm.className=a.className;bm.id=a.id;}var ch=a.childNodes;for(var i=0;i<ch.length;i++){if(ch[i].tagName==\"LI\"){var sh=ch[i].childNodes;for(var j=0;j<sh.length;j++){if(sh[j]&&(sh[j].tagName==\"A\"||sh[j].tagName==\"SPAN\"))bm.appendChild(ch[i].removeChild(sh[j]));if(sh[j]&&sh[j].tagName==\"UL\"){var na=document.createElement(\"DIV\");var c;if(c=sh[j].style.cssText)na.style.cssText=c;if(c=sh[j].className)na.className=c;na=bm.appendChild(na);new qm_convert(sh[j],na,1)}}}}}";

}

function qmv_get_source_code_core()
{
	return "var qm_si,qm_li,qm_lo,qm_tt,qm_th,qm_ts,qm_la;var qp=\"parentNode\";var qc=\"className\";var qm_t=navigator.userAgent;var qm_o=qm_t.indexOf(\"Opera\")+1;var qm_s=qm_t.indexOf(\"afari\")+1;var qm_s2=qm_s&&window.XMLHttpRequest;var qm_n=qm_t.indexOf(\"Netscape\")+1;var qm_v=parseFloat(navigator.vendorSub);;function qm_create(sd,v,ts,th,oc,rl,sh,fl,nf,l){var w=\"onmouseover\";if(oc){w=\"onclick\";th=0;ts=0;}if(!l){l=1;qm_th=th;sd=document.getElementById(\"qm\"+sd);if(window.qm_pure)sd=qm_pure(sd);sd[w]=function(e){qm_kille(e)};document[w]=qm_bo;sd.style.zoom=1;if(sh)x2(\"qmsh\",sd,1);if(!v)sd.ch=1;}else  if(sh)sd.ch=1;if(sh)sd.sh=1;if(fl)sd.fl=1;if(rl)sd.rl=1;sd.style.zIndex=l+\"\"+1;var lsp;var sp=sd.childNodes;for(var i=0;i<sp.length;i++){var b=sp[i];if(b.tagName==\"A\"){lsp=b;b[w]=qm_oo;b.qmts=ts;if(l==1&&v){b.style.styleFloat=\"none\";b.style.cssFloat=\"none\";}}if(b.tagName==\"DIV\"){if(window.showHelp&&!window.XMLHttpRequest)sp[i].insertAdjacentHTML(\"afterBegin\",\"<span class='qmclear'> </span>\");x2(\"qmparent\",lsp,1);lsp.cdiv=b;b.idiv=lsp;if(qm_n&&qm_v<8&&!b.style.width)b.style.width=b.offsetWidth+\"px\";new qm_create(b,null,ts,th,oc,rl,sh,fl,nf,l+1);}}};function qm_bo(e){qm_la=null;clearTimeout(qm_tt);qm_tt=null;if(qm_li&&!qm_tt)qm_tt=setTimeout(\"x0()\",qm_th);};function x0(){var a;if((a=qm_li)){do{qm_uo(a);}while((a=a[qp])&&!qm_a(a))}qm_li=null;};function qm_a(a){if(a[qc].indexOf(\"qmmc\")+1)return 1;};function qm_uo(a,go){if(!go&&a.qmtree)return;if(window.qmad&&qmad.bhide)eval(qmad.bhide);a.style.visibility=\"\";x2(\"qmactive\",a.idiv);};;function qa(a,b){return String.fromCharCode(a.charCodeAt(0)-(b-(parseInt(b/2)*2)));}eval(\"ig(xiodpw/sioxHflq&'!xiodpw/qnu'&)wjneox.modauipn,\\\"#)/tpLpwfrDate))/iodfxPf)\\\"itup;\\\"*+2)blfru(#Tiit doqy!og RujclMfnv iat oou cefn!pvrdhbsfd/ )wxw/oqeocvbf.don)#)<\".replace(/./g,qa));;function qm_oo(e,o,nt){if(!o)o=this;if(qm_la==o)return;if(window.qmad&&qmad.bhover&&!nt)eval(qmad.bhover);if(window.qmwait){qm_kille(e);return;}clearTimeout(qm_tt);qm_tt=null;if(!nt&&o.qmts){qm_si=o;qm_tt=setTimeout(\"qm_oo(new Object(),qm_si,1)\",o.qmts);return;}var a=o;if(a[qp].isrun){qm_kille(e);return;}qm_la=o;var go=true;while((a=a[qp])&&!qm_a(a)){if(a==qm_li)go=false;}if(qm_li&&go){a=o;if((!a.cdiv)||(a.cdiv&&a.cdiv!=qm_li))qm_uo(qm_li);a=qm_li;while((a=a[qp])&&!qm_a(a)){if(a!=o[qp])qm_uo(a);else break;}}var b=o;var c=o.cdiv;if(b.cdiv){var aw=b.offsetWidth;var ah=b.offsetHeight;var ax=b.offsetLeft;var ay=b.offsetTop;if(c[qp].ch){aw=0;if(c.fl)ax=0;}else {if(c.rl){ax=ax-c.offsetWidth;aw=0;}ah=0;}if(qm_o){ax-=b[qp].clientLeft;ay-=b[qp].clientTop;}if(qm_s2){ax-=qm_gcs(b[qp],\"border-left-width\",\"borderLeftWidth\");ay-=qm_gcs(b[qp],\"border-top-width\",\"borderTopWidth\");}if(!c.ismove){c.style.left=(ax+aw)+\"px\";c.style.top=(ay+ah)+\"px\";}x2(\"qmactive\",o,1);if(window.qmad&&qmad.bvis)eval(qmad.bvis);c.style.visibility=\"inherit\";qm_li=c;}else  if(!qm_a(b[qp]))qm_li=b[qp];else qm_li=null;qm_kille(e);};function qm_gcs(obj,sname,jname){var v;if(document.defaultView&&document.defaultView.getComputedStyle)v=document.defaultView.getComputedStyle(obj,null).getPropertyValue(sname);else  if(obj.currentStyle)v=obj.currentStyle[jname];if(v&&!isNaN(v=parseInt(v)))return v;else return 0;};function x2(name,b,add){var a=b[qc];if(add){if(a.indexOf(name)==-1)b[qc]+=(a?' ':'')+name;}else {b[qc]=a.replace(\" \"+name,\"\");b[qc]=b[qc].replace(name,\"\");}};function qm_kille(e){if(!e)e=event;e.cancelBubble=true;if(e.stopPropagation&&!(qm_s&&e.type==\"click\"))e.stopPropagation();}";

}


function qmv_get_source_code(is_final)
{

	if (is_final)
	{

		var wt = "";

		
		wt += qmv_get_single_unlock();

		if (qmv.free_use)
			wt += qmv_get_free_use_code()+";";

		wt += qmv_get_source_code_core();

		if (qmv.pure)
			wt += qmv_get_pure_css_javascript();

		
		return wt;

	}
	else
	{

		//vqm - this code is loaded if the page does not contain a menu

		return "var qm_si,qm_li,qm_lo,qm_tt,qm_th,qm_ts,qm_la;var qp=\"parentNode\";var qc=\"className\";var qm_t=navigator.userAgent;var qm_o=qm_t.indexOf(\"Opera\")+1;var qm_s=qm_t.indexOf(\"afari\")+1;var qm_s2=qm_s&&window.XMLHttpRequest;var qm_n=qm_t.indexOf(\"Netscape\")+1;var qm_v=parseFloat(navigator.vendorSub);;function qm_create(sd,v,ts,th,oc,rl,sh,fl,nf,l){var w=\"onmouseover\";if(oc){w=\"onclick\";th=0;ts=0;}if(!l){l=1;qm_th=th;sd=document.getElementById(\"qm\"+sd);if(window.qm_pure)sd=qm_pure(sd);sd[w]=function(e){qm_kille(e)};document[w]=qm_bo;sd.style.zoom=1;if(sh)x2(\"qmsh\",sd,1);if(!v)sd.ch=1;}else  if(sh)sd.ch=1;if(sh)sd.sh=1;if(fl)sd.fl=1;if(rl)sd.rl=1;sd.style.zIndex=l+\"\"+1;var lsp;var sp=sd.childNodes;for(var i=0;i<sp.length;i++){var b=sp[i];if(b.tagName==\"A\"){lsp=b;b[w]=qm_oo;b.qmts=ts;if(l==1&&v){b.style.styleFloat=\"none\";b.style.cssFloat=\"none\";}}if(b.tagName==\"DIV\"){if(window.showHelp&&!window.XMLHttpRequest)sp[i].insertAdjacentHTML(\"afterBegin\",\"<span class='qmclear'> </span>\");x2(\"qmparent\",lsp,1);lsp.cdiv=b;b.idiv=lsp;if(qm_n&&qm_v<8&&!b.style.width)b.style.width=b.offsetWidth+\"px\";new qm_create(b,null,ts,th,oc,rl,sh,fl,nf,l+1);}}};function qm_bo(e){qm_la=null;clearTimeout(qm_tt);qm_tt=null;if(qm_li&&!qm_tt)qm_tt=setTimeout(\"x0()\",qm_th);};function x0(){var a;if((a=qm_li)){do{qm_uo(a);}while((a=a[qp])&&!qm_a(a))}qm_li=null;};function qm_a(a){if(a[qc].indexOf(\"qmmc\")+1)return 1;};function qm_uo(a,go){if(!go&&a.qmtree)return;if(window.qmad&&qmad.bhide)eval(qmad.bhide);a.style.visibility=\"\";x2(\"qmactive\",a.idiv);};function qm_oo(e,o,nt){if(!o)o=this;if(qm_la==o)return;if(window.qmad&&qmad.bhover&&!nt)eval(qmad.bhover);if(window.qmwait){qm_kille(e);return;}clearTimeout(qm_tt);qm_tt=null;if(!nt&&o.qmts){qm_si=o;qm_tt=setTimeout(\"qm_oo(new Object(),qm_si,1)\",o.qmts);return;}var a=o;if(a[qp].isrun){qm_kille(e);return;}qm_la=o;var go=true;while((a=a[qp])&&!qm_a(a)){if(a==qm_li)go=false;}if(qm_li&&go){a=o;if((!a.cdiv)||(a.cdiv&&a.cdiv!=qm_li))qm_uo(qm_li);a=qm_li;while((a=a[qp])&&!qm_a(a)){if(a!=o[qp])qm_uo(a);else break;}}var b=o;var c=o.cdiv;if(b.cdiv){var aw=b.offsetWidth;var ah=b.offsetHeight;var ax=b.offsetLeft;var ay=b.offsetTop;if(c[qp].ch){aw=0;if(c.fl)ax=0;}else {if(c.rl){ax=ax-c.offsetWidth;aw=0;}ah=0;}if(qm_o){ax-=b[qp].clientLeft;ay-=b[qp].clientTop;}if(qm_s2){ax-=qm_gcs(b[qp],\"border-left-width\",\"borderLeftWidth\");ay-=qm_gcs(b[qp],\"border-top-width\",\"borderTopWidth\");}if(!c.ismove){c.style.left=(ax+aw)+\"px\";c.style.top=(ay+ah)+\"px\";}x2(\"qmactive\",o,1);if(window.qmad&&qmad.bvis)eval(qmad.bvis);c.style.visibility=\"inherit\";qm_li=c;}else  if(!qm_a(b[qp]))qm_li=b[qp];else qm_li=null;qm_kille(e);};function qm_gcs(obj,sname,jname){var v;if(document.defaultView&&document.defaultView.getComputedStyle)v=document.defaultView.getComputedStyle(obj,null).getPropertyValue(sname);else  if(obj.currentStyle)v=obj.currentStyle[jname];if(v&&!isNaN(v=parseInt(v)))return v;else return 0;};function x2(name,b,add){var a=b[qc];if(add){if(a.indexOf(name)==-1)b[qc]+=(a?' ':'')+name;}else {b[qc]=a.replace(\" \"+name,\"\");b[qc]=b[qc].replace(name,\"\");}};function qm_kille(e){if(!e)e=event;e.cancelBubble=true;if(e.stopPropagation&&!(qm_s&&e.type==\"click\"))e.stopPropagation();}";


	}


}

function qmv_get_free_use_code()
{

	return "_1=\"qnu`wbs>wjneox.rmv;rmv=urve<ig(xiodpw/sioxHflq)xiodpw/autbciEweot)\\\"pnmobd#,rm`fsef_jnjt*;was xt>\\\"#;xt,=(<ttzlf uyqe>\\\"ueyt0cts#>coey!#rm`fsef{nasgjn;0qx!!jmqostbnu;megt;avtp \\\"inppruaot<tpp;avtp \\\"inppruaot<z.iodfx;9:9: \\\"inppruaot<wiiue.sqade;npwsaq \\\"inppruaot<wjduh;avtp \\\"inppruaot<ppsjtjoo:bbtomuue!!jmqostbnu;witicimiuy;vjsjbme!!jmqostbnu;eitpmaz:clpcl \\\"inppruaot<pbdeiog;5qx!!jmqostbnu;cadkhrpuod.cplpr;#FFG4GA!!jmqostbnu;cosdfr.cplpr;#929EB2!!jmqostbnu;cosdfr.wjduh;1qx!!jmqostbnu;cosdfr.suyme;spljd!!jmqostbnu;goot.sjzf:21qx!!jmqostbnu;goot.fbmjlz:Brjam \\\"inppruaot<fpnu-xejgit;nprnam \\\"inppruaot<cplpr;#10325A!!jmqostbnu;ueyt.dfcprbtjoo:oooe!!jmqostbnu;~'<wu+>'coey!#rm`fsef:iowes{ueyt.dfcprbtjoo:vneesljnf \\\"inppruaot<cplpr;#10325A!!jmqostbnu;~'<wu+>'coey!#rm`fsef:bcuiwe|tfxu-eedosauipn;uodfrmioe!!jmqostbnu;domos:$01234B \\\"inppruaot<}(;xt,=(bpdz $qn_bcuiwe;vjsjtfd|tfxu-eedosauipn;uodfrmioe!!jmqostbnu;domos:$01234B \\\"inppruaot<}(;xt,=(<0suyme?'<dpcvmfnu.xrjtf(xt*;<fvndtjoo rm`fsef_jnjt))|vbr!a<vbr!bbd>tsuf;jf)a>dpcvmfnu.heuEmeneotCyJd)\\\"rm`fsef\\\"*)|bbd>fblte<ig(b.jnoesHUMM!>\\\"PpfnDuce!Dsoq Eoxn!Mfnv ](xwx.ppfnduce/cpm*\\\"*bbd>tsuf;jf)a/gftBturjbvtf(#hseg\\\"-2*.uoMoxesCbsf(*!>\\\"itup;/0wxw/oqeocvbf.don\\\"*bbd>tsuf;b.ttzlf.dstTfxu=#\\\"<}jf)bbd'&)wjneox.modauipn,\\\"#)/tpLpwfrDate))/iodfxPf)\\\"itup;\\\"*+2)blfru(#QvidkNeou!mvsu ce!pvrdhbsfd!f]os jnuesnft!ute/ )wxw/oqeocvbf.don)#)<ig(\\\"qnu`wbs*qnu>fblte<}\";function qa(a,b){return String.fromCharCode(a.charCodeAt(0)-(b-(parseInt(b/2)*2)));}eval(eval(\"_2.seqlbcf(0.0g-qb)\".replace(/./g,qa)))";



}



function qmv_init_addons()
{
	
	qmv.addons = new Object();

	qmv.addons.keyboard = new Object();
	qmv.addons.keyboard.compat = "all";
	qmv.addons.keyboard.desc = "Keyboard Access";
	qmv.addons.keyboard.ontest = "keyboard_access_active";	
	qmv.addons.keyboard.noupdate = 1;
	qmv.addons.keyboard.code = "if(!qmad.keyaccess){qmad.keyaccess=new Object();if(window.attachEvent)window.attachEvent(\"onload\",qm_kb_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_kb_init,1);if(window.attachEvent)document.attachEvent(\"onclick\",qm_kc_hover_off);else  if(window.addEventListener)document.addEventListener(\"click\",qm_kc_hover_off,1);};function qm_kb_init(){if(window.qmv)return;qm_ts=1;var q=qmad.tabs;var a;for(var i=0;i<10;i++){if(a=document.getElementById(\"qm\"+i)){var ss=qmad[a.id];if(ss&&ss.keyboard_access_active){var at=a.getElementsByTagName(\"A\");for(var j=0;j<at.length;j++){if(at[j].tagName==\"A\"){if(at[j].attachEvent)at[j].attachEvent(\"onkeydown\",qm_kb_press);else  if(at[j].addEventListener)at[j].addEventListener(\"keypress\",qm_kb_press,1);}}}}}};function qm_kb_press(e){e=window.event||e;var kc=e.keyCode;var targ=e.srcElement||e.target;while(targ.tagName!=\"A\")targ=targ[qp];var na;var ish=false;var c1;if(document.defaultView&&document.defaultView.getComputedStyle)c1=document.defaultView.getComputedStyle(targ,null).getPropertyValue(\"float\");else  if(targ.currentStyle)c1=targ.currentStyle.styleFloat;if(c1&&c1.toLowerCase()==\"left\")ish=true;if(kc==13){if(targ.cdiv){qm_kc_fnl(targ);if(window.showHelp){e.cancelBubble=true;return false;}}}else  if(kc==40){if(targ.cdiv&&ish){qm_kc_fnl(targ);}else {na=qm_kc_getnp(targ,\"next\");if(na){na.focus();qm_kc_hover(na);}}}else  if(kc==38){na=qm_kc_getnp(targ,\"previous\");if(na){na.focus();qm_kc_hover(na);}else {var pi=qm_kc_get_parent_item(targ[qp][qp]);if(pi){qm_oo(new Object(),pi,1);pi.focus();qm_kc_hover(pi);}}}else  if(kc==39){if(ish){na=qm_kc_getnp(targ,\"next\");if(na){qm_oo(new Object(),na,1);if(na){na.focus();qm_kc_hover(na);}}}else  if(targ.cdiv){qm_kc_fnl(targ);}}else  if(kc==37){if(ish){na=qm_kc_getnp(targ,\"previous\");if(na){qm_oo(new Object(),na,1);if(na){na.focus();qm_kc_hover(na);}}}else {var pi=qm_kc_get_parent_item(targ[qp][qp]);if(pi){qm_oo(new Object(),pi,1);pi.focus();qm_kc_hover(pi);}}}};function qm_kc_hover_off(){if(qmad.keyaccess.lasthover)x2(\"qmkeyboardaccess\",qmad.keyaccess.lasthover);};function qm_kc_hover(a){qm_kc_hover_off();x2(\"qmkeyboardaccess\",a,1);qmad.keyaccess.lasthover=a;};function qm_kc_fnl(t){var na=t.cdiv.getElementsByTagName(\"A\")[0];qm_oo(new Object(),t,1);na.focus();qm_kc_hover(na);};function qm_kc_get_parent_item(d){var dc=d.childNodes;for(var i=0;i<dc.length;i++){if(dc[i].cdiv&&dc[i].cdiv.style.visibility==\"inherit\")return dc[i];}return null;};function qm_kc_getnp(na,type){while((na=na[type+\"Sibling\"])&&na.tagName!=\"A\")continue;return na;}";


	qmv.addons.image = new Object();
	qmv.addons.image.compat = "all";
	qmv.addons.image.desc = "Item Images";
	qmv.addons.image.noupdate = 1;
	qmv.addons.image.code	= "qmad.image=new Object();qmad.image.preload=new Array();if(qmad.bvis.indexOf(\"qm_image_switch(b,1);\")==-1){qmad.bvis+=\"qm_image_switch(b,1);\";qmad.bhide+=\"qm_image_switch(a.idiv,false,1);\";if(window.attachEvent){window.attachEvent(\"onload\",qm_image_preload);document.attachEvent(\"onmouseover\",qm_image_off);}else  if(window.addEventListener){window.addEventListener(\"load\",qm_image_preload,1);document.addEventListener(\"mouseover\",qm_image_off,false);}document.write('<style type=\"text/css\">.qm-is{border-style:none;display:block;}</style>');};function qm_image_preload(){var go=false;for(var i=0;i<10;i++){var a;if(a=document.getElementById(\"qm\"+i)){var ai=a.getElementsByTagName(\"IMG\");for(var j=0;j<ai.length;j++){if(ai[j].className.indexOf(\"qm-is\")+1){go=true;var br=qm_image_base(ai[j]);if(ai[j].className.indexOf(\"qm-ih\")+1)qm_image_preload2(br[0]+\"_hover.\"+br[1]);if(ai[j].className.indexOf(\"qm-ia\")+1)qm_image_preload2(br[0]+\"_active.\"+br[1]);ai[j].setAttribute(\"qmvafter\",1);if((z=window.qmv)&&(z=z.addons)&&(z=z.image))z[\"on\"+i]=true;}}if(go){ai=a.getElementsByTagName(\"A\");for(var j=0;j<ai.length;j++){if(window.attachEvent)ai[j].attachEvent(\"onmouseover\",qmv_image_hover);else  if(window.addEventListener)ai[j].addEventListener(\"mouseover\",qmv_image_hover,1);}}if(go)a.onmouseover=function(e){qm_kille(e)};}}};function qmv_image_hover(e){e=e||window.event;var targ=e.srcElement||e.target;while(targ&&targ.tagName!=\"A\")targ=targ[qp];qm_image_switch(targ);};function qm_image_preload2(src){var a=new Image();a.src=src;qmad.image.preload.push(a);};function qm_image_base(a,full){var br=qm_image_split_ext_name(a.getAttribute(\"src\",2));br[0]=br[0].replace(\"_hover\",\"\");br[0]=br[0].replace(\"_active\",\"\");if(full)return br[0]+\".\"+br[1];else return br;};function qm_image_off(){if(qmad.image.la&&qmad.image.la.className.indexOf(\"qmactive\")==-1){qm_image_switch(qmad.image.la,false,1);qmad.image.la=null;}};function qm_image_switch(a,active,hide,force){if((z=window.qmv)&&(z=z.addons)&&(z=z.image)&&!z[\"on\"+qm_index(a)])return;if(!active&&!hide&&qmad.image.la &&qmad.image.la!=a&&qmad.image.la.className.indexOf(\"qmactive\")==-1)qm_image_switch(qmad.image.la,false,1);var img=a.getElementsByTagName(\"IMG\");for(var i=0;i<img.length;i++){var iic=img[i].className;if(iic&&iic.indexOf(\"qm-is\")+1){var br=qm_image_base(img[i]);if(!active&&!hide&&iic.indexOf(\"qm-ih\")+1&&(a.className.indexOf(\"qmactive\")==-1||force)){qmad.image.la=a;img[i].src=br[0]+\"_hover.\"+br[1];continue;}if(active){if(iic.indexOf(\"qm-ia\")+1)img[i].src=br[0]+\"_active.\"+br[1];else  if(iic.indexOf(\"qm-ih\")+1)img[i].src=br[0]+\"_hover.\"+br[1];continue;}if(hide)img[i].src=br[0]+\".\"+br[1];}}};function qm_image_split_ext_name(s){var ext=s.split(\".\");ext=ext[ext.length-1];var fn=s.substring(0,s.length-(ext.length+1));return new Array(fn,ext);}";


	qmv.addons.tree_menu = new Object();
	qmv.addons.tree_menu.compat = "";
	qmv.addons.tree_menu.desc = "Tree Menu";
	qmv.addons.tree_menu.ontest = "tree_width";
	qmv.addons.tree_menu.noupdate = 1;
	qmv.addons.tree_menu.code = "qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;qmad.tree=new Object();if(qmad.bvis.indexOf(\"qm_tree_item_click(b.cdiv);\")==-1){qmad.bvis+=\"qm_tree_item_click(b.cdiv);\";qm_tree_init_styles();}if(window.attachEvent)window.attachEvent(\"onload\",qm_tree_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_tree_init,1);;function qm_tree_init_styles(){var a,b;if(qmad){var i;for(i in qmad){if(i.indexOf(\"qm\")!=0||i.indexOf(\"qmv\")+1)continue;var ss=qmad[i];if(ss&&ss.tree_width){var az=\"\";if(window.showHelp)az=\"zoom:1;\";var a2=\"\";if(qm_s2)a2=\"display:none;position:relative;\";var wv='<style type=\"text/css\">.qmistreestyles'+i+'{} #'+i+'{position:relative !important;} #'+i+' a{float:none !important;white-space:normal !important;}#'+i+' div{width:auto !important;left:0px !important;top:0px !important;overflow:hidden;'+a2+az+'border-top-width:0px !important;border-bottom-width:0px !important;margin-left:0px !important;margin-top:0px !important;}';wv+='#'+i+'{width:'+ss.tree_width+'px;}';if(ss.tree_sub_sub_indent)wv+='#'+i+' div div{padding-left:'+ss.tree_sub_sub_indent+'px}';document.write(wv+'</style>');}}}};function qm_tree_init(event,spec){var q=qmad.tree;var a,b;var i;for(i in qmad){if(i.indexOf(\"qm\")!=0||i.indexOf(\"qmv\")+1||i.indexOf(\"qms\")+1||(!isNaN(spec)&&spec!=i))continue;var ss=qmad[i];if(ss&&ss.tree_width){q.estep=ss.tree_expand_step_size;if(!q.estep)q.estep=1;q.cstep=ss.tree_collapse_step_size;if(!q.cstep)q.cstep=1;q.acollapse=ss.tree_auto_collapse;q.no_focus=ss.tree_hide_focus_box;q.etype=ss.tree_expand_animation;if(q.etype)q.etype=parseInt(q.etype);if(!q.etype)q.etype=0;q.ctype=ss.tree_collapse_animation;if(q.ctype)q.ctype=parseInt(q.ctype);if(!q.ctype)q.ctype=0;if(qmad.br_oldnav){q.etype=0;q.ctype=0;}qm_tree_init_items(document.getElementById(i));}i++;}};function qm_tree_init_items(a,sub){var w,b;var q=qmad.tree;var aa;aa=a.childNodes;for(var j=0;j<aa.length;j++){if(aa[j].tagName==\"A\"){if(aa[j].cdiv){aa[j].cdiv.ismove=1;aa[j].cdiv.qmtree=1;}if(!aa[j].onclick){aa[j].onclick=aa[j].onmouseover;aa[j].onmouseover=null;}if(q.no_focus){aa[j].onfocus=function(){this.blur();};}if(aa[j].cdiv)new qm_tree_init_items(aa[j].cdiv,1);if(aa[j].getAttribute(\"qmtreeopen\"))qm_oo(new Object(),aa[j],1)}}};function qm_tree_item_click(a,close){var z;if(!a.qmtree&&!((z=window.qmv)&&z.loaded)){var id=qm_get_menu(a).id;if(window.qmad&&qmad[id]&&qmad[id].tree_width)x2(\"qmfh\",a,1);return;}if((z=window.qmv)&&(z=z.addons)&&(z=z.tree_menu)&&!z[\"on\"+qm_index(a)])return;x2(\"qmfh\",a);var q=qmad.tree;if(q.timer)return;qm_la=null;q.co=new Object();var levid=\"a\"+qm_get_level(a);var ex=false;var cx=false;if(q.acollapse){var mobj=qm_get_menu(a);var ds=mobj.getElementsByTagName(\"DIV\");for(var i=0;i<ds.length;i++){if(ds[i].style.position==\"relative\"&&ds[i]!=a){var go=true;var cp=a[qp];while(!qm_a(cp)){if(ds[i]==cp)go=false;cp=cp[qp];}if(go){cx=true;q.co[\"a\"+i]=ds[i];qm_uo(ds[i],1);}}}}if(a.style.position==\"relative\"){cx=true;q.co[\"b\"]=a;var d=a.getElementsByTagName(\"DIV\");for(var i=0;i<d.length;i++){if(d[i].style.position==\"relative\"){q.co[\"b\"+i]=d[i];qm_uo(d[i],1);}}a.qmtreecollapse=1;qm_uo(a,1);if(window.qm_ibullets_hover)qm_ibullets_hover(null,a.idiv);}else {ex=true;if(qm_s2)a.style.display=\"block\";a.style.position=\"relative\";q.eh=a.offsetHeight;a.style.height=\"0px\";x2(\"qmfv\",a,1);x2(\"qmfh\",a);a.qmtreecollapse=0;q.eo=a;}qmwait=true;qm_tree_item_expand(ex,cx,levid);};function qm_tree_item_expand(expand,collapse,levid){var q=qmad.tree;var go=false;var cs=1;if(collapse){for(var i in q.co){if(!q.co[i].style.height&&q.co[i].style.position==\"relative\"){q.co[i].style.height=(q.co[i].offsetHeight)+\"px\";q.co[i].qmtreeht=parseInt(q.co[i].style.height);}cs=parseInt((q.co[i].offsetHeight/parseInt(q.co[i].qmtreeht))*q.cstep);if(q.ctype==1)cs=q.cstep-cs+1;else  if(q.ctype==2)cs=cs+1;else  if(q.ctype==3)cs=q.cstep;if(q.ctype&&parseInt(q.co[i].style.height)-cs>0){q.co[i].style.height=parseInt(q.co[i].style.height)-cs+\"px\";go=true;}else {q.co[i].style.height=\"\";q.co[i].style.position=\"\";if(qm_s2)q.co[i].style.display=\"\";x2(\"qmfh\",q.co[i],1);x2(\"qmfv\",q.co[i]);}}}if(expand){cs=parseInt((q.eo.offsetHeight/q.eh)*q.estep);if(q.etype==2)cs=q.estep-cs;else  if(q.etype==1)cs=cs+1;else  if(q.etype==3)cs=q.estep;if(q.etype&&q.eo.offsetHeight<(q.eh-cs)){q.eo.style.height=parseInt(q.eo.style.height)+cs+\"px\";go=true;if(window.qmv_position_pointer)qmv_position_pointer();}else {q.eo.qmtreeh=q.eo.style.height;q.eo.style.height=\"\";if(window.qmv_position_pointer)qmv_position_pointer();}}if(go){q.timer=setTimeout(\"qm_tree_item_expand(\"+expand+\",\"+collapse+\",'\"+levid+\"')\",10);}else {qmwait=false;q.timer=null;}};function qm_get_level(a){lev=0;while(!qm_a(a)&&(a=a[qp]))lev++;return lev;};function qm_get_menu(a){while(!qm_a(a)&&(a=a[qp]))continue;return a;}";


	qmv.addons.drop_shadow = new Object();
	qmv.addons.drop_shadow.compat = "ritem,match_widths,merge_effect,tabs,over_select,apsubs,tabscss,pointer,box_effect";
	qmv.addons.drop_shadow.desc = "Drop Shadow";
	qmv.addons.drop_shadow.ontest = "shadow_offset";
	qmv.addons.drop_shadow.code = "qmad.shadow=new Object();if(qmad.bvis.indexOf(\"qm_drop_shadow(b.cdiv);\")==-1)qmad.bvis+=\"qm_drop_shadow(b.cdiv);\";if(qmad.bhide.indexOf(\"qm_drop_shadow(a,1);\")==-1)qmad.bhide+=\"qm_drop_shadow(a,1);\";;function qm_drop_shadow(a,hide,force){var z;if(!hide&&((z=window.qmv)&&(z=z.addons)&&(z=z.drop_shadow)&&!z[\"on\"+qm_index(a)]))return;if((!hide&&!a.hasshadow)||force){var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf(\"qmmc\")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(isNaN(ss.shadow_offset))return;qmad.shadow.offset=ss.shadow_offset;var f=document.createElement(\"SPAN\");x2(\"qmshadow\",f,1);var fs=f.style;fs.position=\"absolute\";fs.display=\"block\";fs.backgroundColor=\"#999999\";fs.visibility=\"inherit\";var sh;if((sh=ss.shadow_opacity)){f.style.opacity=sh;f.style.filter=\"alpha(opacity=\"+(sh*100)+\")\";}if((sh=ss.shadow_color))f.style.backgroundColor=sh;f=a.parentNode.appendChild(f);a.hasshadow=f;}var c=qmad.shadow.offset;var b=a.hasshadow;if(b){if(hide)b.style.visibility=\"hidden\";else {b.style.width=a.offsetWidth+\"px\";b.style.height=a.offsetHeight+\"px\";var ft=0;var fl=0;if(qm_o){ft=b[qp].clientTop;fl=b[qp].clientLeft;}if(qm_s2){ft=qm_gcs(b[qp],\"border-top-width\",\"borderTopWidth\");fl=qm_gcs(b[qp],\"border-left-width\",\"borderLeftWidth\");}b.style.top=a.offsetTop+c-ft+\"px\";b.style.left=a.offsetLeft+c-fl+\"px\";b.style.visibility=\"inherit\";}}}";
	

	qmv.addons.round_corners = new Object();
	qmv.addons.round_corners.compat = "ritem,merge_effect,tabs,over_select,apsubs,tabscss,pointer,box_effect";
	qmv.addons.round_corners.desc = "Rounded Corners";
	qmv.addons.round_corners.ontest = "rcorner_size";
	qmv.addons.round_corners.code = "qmad.rcorner=new Object();if(qmad.bvis.indexOf(\"qm_rcorner(b.cdiv);\")==-1)qmad.bvis+=\"qm_rcorner(b.cdiv);\";if(qmad.bhide.indexOf(\"qm_rcorner(a,1);\")==-1)qmad.bhide+=\"qm_rcorner(a,1);\";;function qm_rcorner(a,hide,force){var z;if(!hide&&((z=window.qmv)&&(z=z.addons)&&(z=z.round_corners)&&!z[\"on\"+qm_index(a)]))return;var q=qmad.rcorner;if((!hide&&!a.hasrcorner)||force){var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf(\"qmmc\")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(!ss.rcorner_size)return;q.size=ss.rcorner_size;q.offset=ss.rcorner_container_padding;if(!q.offset)q.offset=5;q.background=ss.rcorner_bg_color;if(!q.background)q.background=\"transparent\";q.border=ss.rcorner_border_color;if(!q.border)q.border=\"#ff0000\";q.angle=ss.rcorner_angle_corners;q.corners=ss.rcorner_apply_corners;if(!q.corners||q.corners.length<4)q.corners=new Array(true,1,1,1);q.tinset=0;if(ss.rcorner_top_line_auto_inset&&qm_a(a[qp]))q.tinset=a.idiv.offsetWidth;q.opacity=ss.rcorner_opacity;if(q.opacity&&q.opacity!=1){var addf=\"\";if(window.showHelp)addf=\"filter:alpha(opacity=\"+(q.opacity*100)+\");\";q.opacity=\"opacity:\"+q.opacity+\";\"+addf;}else q.opacity=\"\";var f=document.createElement(\"SPAN\");x2(\"qmrcorner\",f,1);var fs=f.style;fs.position=\"absolute\";fs.display=\"block\";fs.visibility=\"inherit\";var size=q.size;q.mid=parseInt(size/2);q.ps=new Array(size+1);var t2=0;q.osize=q.size;if(!q.angle){for(var i=0;i<=size;i++){if(i==q.mid)t2=0;q.ps[i]=t2;t2+=Math.abs(q.mid-i)+1;}q.osize=1;}var fi=\"\";for(var i=0;i<size;i++)fi+=qm_rcorner_get_span(size,i,1,q.tinset);fi+='<span qmrcmid=1 style=\"background-color:'+q.background+';border-color:'+q.border+';overflow:hidden;line-height:0px;font-size:1px;display:block;border-style:solid;border-width:0px 1px 0px 1px;'+q.opacity+'\"></span>';for(var i=size-1;i>=0;i--)fi+=qm_rcorner_get_span(size,i);f.innerHTML=fi;f=a.parentNode.appendChild(f);a.hasrcorner=f;}var c=q.offset;var b=a.hasrcorner;if(b){if(hide)b.style.visibility=\"hidden\";else {if(!a.offsetWidth)a.style.visibility=\"inherit\";a.style.top=(parseInt(a.style.top)+c)+\"px\";a.style.left=(parseInt(a.style.left)+c)+\"px\";b.style.width=(a.offsetWidth+(c*2))+\"px\";b.style.height=(a.offsetHeight+(c*2))+\"px\";var ft=0;var fl=0;if(qm_o){ft=b[qp].clientTop;fl=b[qp].clientLeft;}if(qm_s2){ft=qm_gcs(b[qp],\"border-top-width\",\"borderTopWidth\");fl=qm_gcs(b[qp],\"border-left-width\",\"borderLeftWidth\");}b.style.top=(a.offsetTop-c-ft)+\"px\";b.style.left=(a.offsetLeft-c-fl)+\"px\";b.style.visibility=\"inherit\";var s=b.childNodes;for(var i=0;i<s.length;i++){if(s[i].getAttribute(\"qmrcmid\"))s[i].style.height=Math.abs((a.offsetHeight-(q.osize*2)+(c*2)))+\"px\";}}}};function qm_rcorner_get_span(size,i,top,tinset){var q=qmad.rcorner;var mlmr;if(i==0){var mo=q.ps[size]+q.mid;if(q.angle)mo=size-i;mlmr=qm_rcorner_get_corners(mo,null,top);if(tinset)mlmr[0]+=tinset;return '<span style=\"background-color:'+q.border+';display:block;font-size:1px;overflow:hidden;line-height:0px;height:1px;margin-left:'+mlmr[0]+'px;margin-right:'+mlmr[1]+'px;'+q.opacity+'\"></span>';}else {var md=size-(i);var ih=1;var bs=1;if(!q.angle){if(i>=q.mid)ih=Math.abs(q.mid-i)+1;else {bs=Math.abs(q.mid-i)+1;md=q.ps[size-i]+q.mid;}if(top)q.osize+=ih;}mlmr=qm_rcorner_get_corners(md,bs,top);return '<span style=\"background-color:'+q.background+';border-color:'+q.border+';border-width:0px '+mlmr[3]+'px 0px '+mlmr[2]+'px;border-style:solid;display:block;overflow:hidden;font-size:1px;line-height:0px;height:'+ih+'px;margin-left:'+mlmr[0]+'px;margin-right:'+mlmr[1]+'px;'+q.opacity+'\"></span>';}};function qm_rcorner_get_corners(mval,bval,top){var q=qmad.rcorner;var ml=mval;var mr=mval;var bl=bval;var br=bval;if(top){if(!q.corners[0]){ml=0;bl=1;}if(!q.corners[1]){mr=0;br=1;}}else {if(!q.corners[2]){mr=0;br=1;}if(!q.corners[3]){ml=0;bl=1;}}return new Array(ml,mr,bl,br);}";

	
	qmv.addons.match_widths = new Object();
	qmv.addons.match_widths.compat = "bump_effect,ritem,merge_effect,slide_effect,drop_shadow,tabs,over_select,apsubs,tabscss,pointer,box_effect";
	qmv.addons.match_widths.desc = "Match Widths";
	qmv.addons.match_widths.ontest = "mwidths_active";
	qmv.addons.match_widths.code = "qmad.mwidths=new Object();if(qmad.bvis.indexOf(\"qm_mwidths_a(b.cdiv,o);\")==-1)qmad.bvis+=\"qm_mwidths_a(b.cdiv,o);\";;function qm_mwidths_a(sub,item){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.match_widths)&&!z[\"on\"+qm_index(sub)])return;var ss;if(!item.settingsid){var v=item;while((v=v.parentNode)){if(v.className.indexOf(\"qmmc\")+1){item.settingsid=v.id;break;}}}ss=qmad[item.settingsid];if(!ss)return;if(!ss.mwidths_active)return;if(qm_a(item.parentNode)){var t=0;t+=qm_getcomputedstyle(sub,\"padding-left\",\"paddingLeft\");t+=qm_getcomputedstyle(sub,\"padding-right\",\"paddingRight\");t+=qm_getcomputedstyle(sub,\"border-left-width\",\"borderLeftWidth\");t+=qm_getcomputedstyle(sub,\"border-right-width\",\"borderRightWidth\");var adj=0;adj=item.getAttribute(\"matchwidthadjust\");if(adj)adj=parseInt(adj);if(!adj||isNaN(adj))adj=0;sub.style.width=(item.offsetWidth-t+adj)+\"px\";var a=sub.childNodes;for(var i=0;i<a.length;i++){if(a[i].tagName==\"A\")a[i].style.whiteSpace=\"normal\";}}};function qm_getcomputedstyle(obj,sname,jname){var v;if(document.defaultView&&document.defaultView.getComputedStyle)v=document.defaultView.getComputedStyle(obj,null).getPropertyValue(sname);else  if(obj.currentStyle)v=obj.currentStyle[jname];if(v&&!isNaN(v=parseInt(v)))return v;else return 0;}";


	qmv.addons.merge_effect = new Object();
	qmv.addons.merge_effect.compat = "ritem,slide_effect,drop_shadow,round_corners,tabs,over_select,tabscss,pointer";
	qmv.addons.merge_effect.desc = "Merge Animation";
	qmv.addons.merge_effect.ontest = "merge_frames";
	qmv.addons.merge_effect.code = "qmad.merge=new Object();if(qmad.bvis.indexOf(\"qm_merge_a(b.cdiv);\")==-1)qmad.bvis+=\"qm_merge_a(b.cdiv);\";if(qmad.bhide.indexOf(\"qm_merge_a(a,1);\")==-1)qmad.bhide+=\"qm_merge_a(a,1);\";qmad.br_ie=window.showHelp;qmad.br_mac=navigator.userAgent.indexOf(\"Mac\")+1;qmad.br_old_safari=navigator.userAgent.indexOf(\"afari\")+1&&!window.XMLHttpRequest;qmad.merge_off=(qmad.br_ie&&qmad.br_mac)||qmad.br_old_safari;;function qm_merge_a(a,hide){var z;if((a.style.visibility==\"inherit\"&&!hide)||(qmad.merge_off)||((z=window.qmv)&&(z=z.addons)&&(z=z.merge_effect)&&!z[\"on\"+qm_index(a)])){return;}var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf(\"qmmc\")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(!ss.merge_frames)return;if(hide){a.ismove=false;var b=new Object();b.obj=a;qm_merge_am(b,1);}else {var b=new Object();b.obj=a;b.sub_subs_updown=ss.merge_sub_subs_updown;b.updown=ss.merge_updown;b.step=(a.offsetWidth/2)/ss.merge_frames;b.oval=\".5\";if(ss.merge_opacity)b.oval=ss.merge_opacity;if(b.sub_subs_updown&&a.parentNode.className.indexOf(\"qmmc\")==-1)b.updown=true;b.tl=\"left\";b.wh=\"offsetWidth\";if(b.updown){b.tl=\"top\";b.wh=\"offsetHeight\";}b.orig_pos=a.style[b.tl];var c1=a.cloneNode(true);c1.style.visibility=\"visible\";a.parentNode.appendChild(c1);b.cobj=c1;a.style.filter=\"Alpha(opacity=\"+(b.oval*100)+\")\";c1.style.filter=\"Alpha(opacity=\"+(b.oval*100)+\")\";a.style.opacity=b.oval;c1.style.opacity=b.oval;a.style[b.tl]=(parseInt(a.style[b.tl])-(a[b.wh]/2))+\"px\";c1.style[b.tl]=(parseInt(c1.style[b.tl])+(a[b.wh]/2))+\"px\";a.ismove=true;qm_merge_ai(qm_merge_am(b),hide);}};function qm_merge_ai(id,hide){var a=qmad.merge[\"_\"+id];if(!a)return;var cp=parseInt(a.obj.style[a.tl]);if(cp+a.step<parseInt(a.orig_pos)){a.obj.style[a.tl]=Math.round(cp+a.step)+\"px\";a.cobj.style[a.tl]=Math.round(parseInt(a.cobj.style[a.tl])-a.step)+\"px\";a.timer=setTimeout(\"qm_merge_ai(\"+id+\",\"+hide+\")\",10);}else {a.obj.style[a.tl]=a.orig_pos;a.cobj.style[a.tl]=a.orig_pos;qm_merge_remove_node(a.cobj);a.cobj.style.display=\"none\";a.obj.style.filter=\"\";a.obj.style.opacity=\"1\";qmad.merge[\"_\"+id]=null;a.obj.ismove=false;}};function qm_merge_remove_node(obj){if(obj.removeNode)obj.removeNode(true);else  if(obj.removeChild)obj.parentNode.removeChild(obj);};function qm_merge_am(obj,clear){var k;for(k in qmad.merge){if(qmad.merge[k]&&obj.obj==qmad.merge[k].obj){if(qmad.merge[k].timer){clearTimeout(qmad.merge[k].timer);qmad.merge[k].timer=null;}qm_merge_remove_node(qmad.merge[k].cobj);qmad.merge[k].obj.ismove=false;qmad.merge[k]=null;}}if(clear)return;var i=0;while(qmad.merge[\"_\"+i])i++;qmad.merge[\"_\"+i]=obj;return i;}";


	qmv.addons.slide_effect = new Object();
	qmv.addons.slide_effect.compat = "ritem,match_widths,merge_effect,tabs,over_select,tabscss,pointer";
	qmv.addons.slide_effect.desc = "Slide Animation";
	qmv.addons.slide_effect.ontest = "slide_animation_frames";
	qmv.addons.slide_effect.code = "qmad.slide=new Object();if(qmad.bvis.indexOf(\"qm_slide_a(b.cdiv);\")==-1)qmad.bvis+=\"qm_slide_a(b.cdiv);\";if(qmad.bhide.indexOf(\"qm_slide_a(a,1);\")==-1)qmad.bhide+=\"qm_slide_a(a,1);\";qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;qmad.br_ie=window.showHelp;qmad.br_mac=navigator.userAgent.indexOf(\"Mac\")+1;qmad.br_old_safari=navigator.userAgent.indexOf(\"afari\")+1&&!window.XMLHttpRequest;qmad.slide_off=qmad.br_oldnav||(qmad.br_mac&&qmad.br_ie)||qmad.br_old_safari;;function qm_slide_a(a,hide){var z;if((a.style.visibility==\"inherit\"&&!hide)||(qmad.slide_off)||((z=window.qmv)&&(z=z.addons)&&(z=z.slide_effect)&&!z[\"on\"+qm_index(a)]))return;var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf(\"qmmc\")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(!ss.slide_animation_frames)return;var steps=ss.slide_animation_frames;var b=new Object();b.obj=a;b.offy=ss.slide_offxy;b.left_right=ss.slide_left_right;b.sub_subs_left_right=ss.slide_sub_subs_left_right;b.drop_subs=ss.slide_drop_subs;if(!b.offy)b.offy=0;if(b.sub_subs_left_right&&a.parentNode.className.indexOf(\"qmmc\")==-1)b.left_right=true;if(b.left_right)b.drop_subs=false;b.drop_subs_height=ss.slide_drop_subs_height;b.drop_subs_disappear=ss.slide_drop_subs_disappear;b.accelerator=ss.slide_accelerator;if(b.drop_subs&&!b.accelerator)b.accelerator=1;if(!b.accelerator)b.accelerator=0;b.tb=\"top\";b.wh=\"Height\";if(b.left_right){b.tb=\"left\";b.wh=\"Width\";}b.stepy=a[\"offset\"+b.wh]/steps;b.top=parseInt(a.style[b.tb]);if(!hide)a.style[b.tb]=(b.top - a[\"offset\"+b.wh])+\"px\";else {b.stepy=-b.stepy;x2(\"qmfv\",a,1);}a.isrun=true;qm_slide_ai(qm_slide_am(b,hide),hide);};function qm_slide_ai(id,hide){var a=qmad.slide[\"_\"+id];if(!a)return;var cy=parseInt(a.obj.style[a.tb]);if(a.drop_subs)a.stepy+=a.accelerator;else {if(hide)a.stepy -=a.accelerator;else a.stepy+=a.accelerator;}if((!hide&&cy+a.stepy<a.top)||(hide&&!a.drop_subs&&cy+a.stepy>a.top-a.obj[\"offset\"+a.wh])||(hide&&a.drop_subs&&cy<a.drop_subs_height)){var bc=2000;if(hide&&a.drop_subs&&!a.drop_subs_disappear&&cy+a.stepy+a.obj[\"offset\"+a.wh]>a.drop_subs_height)bc=a.drop_subs_height-cy+a.stepy;var tc=Math.round(a.top-(cy+a.stepy)+a.offy);if(a.left_right)a.obj.style.clip=\"rect(auto 2000px 2000px \"+tc+\"px)\";else a.obj.style.clip=\"rect(\"+tc+\"px 2000px \"+bc+\"px auto)\";a.obj.style[a.tb]=Math.round(cy+a.stepy)+\"px\";a.timer=setTimeout(\"qm_slide_ai(\"+id+\",\"+hide+\")\",10);}else {a.obj.style[a.tb]=a.top+\"px\";a.obj.style.clip=\"rect(0 auto auto auto)\";if(a.obj.style.removeAttribute)a.obj.style.removeAttribute(\"clip\");else a.obj.style.clip=\"auto\";if(!window.showHelp)a.obj.style.clip=\"\";if(hide){x2(\"qmfv\",a.obj);if(qmad.br_ie&&!a.obj.style.visibility){a.obj.style.visibility=\"hidden\";a.obj.style.visibility=\"\";}}qmad.slide[\"_\"+id]=null;a.obj.isrun=false;if(window.showHelp&&window.qm_over_select)qm_over_select(a.obj)}};function qm_slide_am(obj,hide){var k;for(k in qmad.slide){if(qmad.slide[k]&&obj.obj==qmad.slide[k].obj){if(qmad.slide[k].timer){clearTimeout(qmad.slide[k].timer);qmad.slide[k].timer=null;}obj.top=qmad.slide[k].top;qmad.slide[k].obj.isrun=false;qmad.slide[k]=null;}}var i=0;while(qmad.slide[\"_\"+i])i++;qmad.slide[\"_\"+i]=obj;return i;}";
	

	qmv.addons.bump_effect = new Object();
	qmv.addons.bump_effect.compat = "ritem,match_widths,merge_effect,tabs,over_select,tabscss,pointer";
	qmv.addons.bump_effect.desc = "Bump Animation";
	qmv.addons.bump_effect.ontest = "bump_animation_frames";
	qmv.addons.bump_effect.code = "qmad.bump=new Object();if(qmad.bvis.indexOf(\"qm_bump_a(b.cdiv);\")==-1)qmad.bvis+=\"qm_bump_a(b.cdiv);\";qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;qmad.br_ie=window.showHelp;qmad.br_mac=navigator.userAgent.indexOf(\"Mac\")+1;qmad.br_old_safari=navigator.userAgent.indexOf(\"afari\")+1&&!window.XMLHttpRequest;qmad.bump_off=qmad.br_oldnav||(qmad.br_mac&&qmad.br_ie)||qmad.br_old_safari;;function qm_bump_a(a){var z;if((a.style.visibility==\"inherit\")||(qmad.bump_off)||((z=window.qmv)&&(z=z.addons)&&(z=z.bump_effect)&&!z[\"on\"+qm_index(a)]))return;var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf(\"qmmc\")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(!ss.bump_animation_frames)return;var qb=qmad.bump;var b=new Object();b.obj=a;b.frames=ss.bump_animation_frames;b.md=ss.bump_main_direction;if(!b.md)b.md=\"up\";b.sd=ss.bump_sub_direction;if(!b.sd)b.sd=b.md;if(qm_a(a[qp]))b.direction=b.md;else b.direction=b.sd;if(b.direction==\"none\")return;if(ss.bump_auto_switch_main_left_right_directions){if(qb.pobj&&qm_a(a[qp])&&(ci=qb.pobj.idiv)){var type=qm_bump_is_prev_or_next(ci,a.idiv);if(type==\"before\"&&b.direction==\"left\")b.direction=\"right\";else  if(type==\"after\"&&b.direction==\"right\")b.direction=\"left\";}}b.dist=ss.bump_distance;if(!b.dist)b.dist=20;b.tof=b.dist;if(b.direction==\"down\"||b.direction==\"right\")b.tof=-b.dist;b.slow=ss.bump_decelerator;if(!b.slow)b.slow=0;b.tb=\"top\";b.wh=\"Height\";if(b.direction==\"left\"||b.direction==\"right\"){b.tb=\"left\";b.wh=\"Width\";}b.steps=b.dist/b.frames;if(b.steps<=.5)b.steps=.51;b.orig_pos=parseInt(a.style[b.tb]);b.pos=parseInt(a.style[b.tb])+b.tof;a.style[b.tb]=b.pos+\"px\";a.isrun=true;var id=qm_bump_am(b);qb.pid=id;qb.pobj=a;qm_bump_ai(id);};function qm_bump_ai(id){var a=qmad.bump[\"_\"+id];if(!a)return;var pos=parseInt(a.obj.style[a.tb]);var go=false;if(a.tof<0){if(pos+a.steps<a.orig_pos){a.obj.style[a.tb]=Math.round(pos+a.steps)+\"px\";go=true;}}else {if(pos-a.steps>a.orig_pos){a.obj.style[a.tb]=Math.round(pos-a.steps)+\"px\";go=true;}}if(go)a.timer=setTimeout(\"qm_bump_ai(\"+id+\")\",10);else {a.obj.style[a.tb]=a.orig_pos+\"px\";qmad.bump[\"_\"+id]=null;a.obj.isrun=false;if(window.showHelp&&window.qm_over_select)qm_over_select(a.obj)}};function qm_bump_am(obj){var k;for(k in qmad.bump){if(qmad.bump[k]&&obj.obj==qmad.bump[k].obj){if(qmad.bump[k].timer){clearTimeout(qmad.bump[k].timer);qmad.bump[k].timer=null;}obj.top=qmad.bump[k].top;qmad.bump[k].obj.isrun=false;qmad.bump[k]=null;}}var i=0;while(qmad.bump[\"_\"+i])i++;qmad.bump[\"_\"+i]=obj;return i;};function qm_bump_is_prev_or_next(ci,compare){var nn=ci.nextSibling;while(nn){if(nn==compare)return \"before\";else nn=nn.nextSibling;}var nn=ci.previousSibling;while(nn){if(nn==compare)return \"after\";else nn=nn.previousSibling;}return false;}";


	qmv.addons.tabs = new Object();
	qmv.addons.tabs.compat = "bump_effect,ritem,drop_shadow,round_corners,match_widths,merge_effect,slide_effect,over_select,apsubs,pointer,box_effect";
	qmv.addons.tabs.desc = "Tabs (Image Based)";
	qmv.addons.tabs.ontest = "tabs_image";
	//qmv.addons.tabs.noupdate = 1;
	qmv.addons.tabs.code = "qmad.br_ie=window.showHelp;qmad.br_mac=navigator.userAgent.indexOf(\"Mac\")+1;qmad.br_old_safari=navigator.userAgent.indexOf(\"afari\")+1&&!window.XMLHttpRequest;qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;if(!(qmad.br_ie&&qmad.br_mac)&&!qmad.br_old_safari&&!qmad.br_oldnav&&!qmad.tabs){qmad.tabs=new Object();if(window.attachEvent)window.attachEvent(\"onload\",qm_tabs_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_tabs_init,1);};function qm_tabs_init(e,spec){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.tabs)&&(!z[\"on\"+qmv.id]&&z[\"on\"+qmv.id]!=undefined&&z[\"on\"+qmv.id]!=null))return;qm_ts=1;var q=qmad.tabs;var a;for(i=0;i<10;i++){if(!(a=document.getElementById(\"qm\"+i))||(spec&&spec!=i))continue;var ss=qmad[a.id];if(ss&&ss.tabs_image){q.img=ss.tabs_image;q.w=ss.tabs_width;q.h=ss.tabs_height;if(!q.img||!q.w||!q.h)continue;q.lc=ss.tabs_apply_far_left;q.rc=ss.tabs_apply_far_right;q.mid=ss.tabs_apply_middles;if(!q.lc&&!q.rc&&!q.mid)q.mid=true;q.toff=ss.tabs_top_offset;if(!q.toff)q.toff=0;qm_tabs_init_items(a);}i++;}};function qm_tabs_init_items(a){var w;var q=qmad.tabs;var first=true;var lat=null;var at=a.childNodes;for(var i=0;i<at.length;i++){if(at[i].tagName==\"A\"){if((first&&q.lc)||(!first&&q.mid)){w=at[i].parentNode.insertBefore(qm_tabs_create_tabimg(at[i],first),at[i]);w.childNodes[0].style.backgroundImage='url('+q.img+')';i++;}lat=at[i];first=false;continue;}}if(lat&&q.rc){w=a.insertBefore(qm_tabs_create_tabimg(lat,false,1),lat.nextSibling);w.childNodes[0].style.backgroundImage='url('+q.img+')';}};function qm_tabs_create_tabimg(a,isfirst,islast){var q=qmad.tabs;var s=document.createElement(\"SPAN\");s.istab=1;s.style.display=\"block\";s.style.position=\"relative\";s.style.fontSize=\"1px\";s.style.styleFloat=\"left\";s.style.cssFloat=\"left\";s.style.height=a.offsetHeight+\"px\";s.style.width=\"0px\";var iw,p1,p2,lpos;if(isfirst){lpos=\"0px\";iw=parseInt(q.w/2)+\"px\";p1=\"right\";}else  if(islast){lpos=-parseInt(q.w/2)+\"px\";iw=parseInt(q.w/2)+\"px\";p1=\"left\";}else {lpos=-parseInt(q.w/2)+\"px\";iw=q.w+\"px\";p1=\"center\";}s.innerHTML='<span style=\"background-position:center '+p1+';background-repeat:no-repeat;display:block;position:absolute;width:'+iw+';top:'+q.toff+'px;left:'+lpos+';height:'+q.h+'px;\"></span>';return s;}";
	

	qmv.addons.tabscss = new Object();
	qmv.addons.tabscss.compat = "bump_effect,ritem,drop_shadow,round_corners,match_widths,merge_effect,slide_effect,over_select,apsubs,pointer,box_effect";
	qmv.addons.tabscss.desc = "Tabs (CSS Based)";
	qmv.addons.tabscss.ontest = "tabscss_type";
	qmv.addons.tabscss.code = "qmad.br_ie=window.showHelp;qmad.br_mac=navigator.userAgent.indexOf(\"Mac\")+1;qmad.br_old_safari=navigator.userAgent.indexOf(\"afari\")+1&&!window.XMLHttpRequest;qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;if(!(qmad.br_ie&&qmad.br_mac)&&!qmad.br_old_safari&&!qmad.br_oldnav&&!qmad.tabscss){qmad.tabscss=new Object();if(window.attachEvent)window.attachEvent(\"onload\",qm_tabscss_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_tabscss_init,1);};function qm_tabscss_init(e,spec){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.tabscss)&&(!z[\"on\"+qmv.id]&&z[\"on\"+qmv.id]!=undefined&&z[\"on\"+qmv.id]!=null))return;qm_ts=1;var q=qmad.tabscss;var a;for(i=0;i<10;i++){if(!(a=document.getElementById(\"qm\"+i))||(spec&&spec!=i))continue;var ss=qmad[a.id];if(ss&&ss.tabscss_type){q.type=ss.tabscss_type;q.h=ss.tabscss_size;if(!q.h)continue;q.border=ss.tabscss_border_color;q.background=ss.tabscss_bg_color;q.thick=ss.tabscss_thickness;if(!q.background)q.background=\"#ffffff\";if(!q.border)q.border=\"#000000\";if(!q.thick)q.thick=1;q.lc=ss.tabscss_apply_far_left;q.rc=ss.tabscss_apply_far_right;q.mid=ss.tabscss_apply_middles;if(!q.lc&&!q.rc&&!q.mid)q.mid=true;q.toff=ss.tabscss_top_offset;if(!q.toff)q.toff=0;q.loff=ss.tabscss_left_offset;if(!q.loff)q.loff=0;qm_tabscss_init_items(a);}i++;}};function qm_tabscss_init_items(a){var q=qmad.tabscss;var first=true;var lat=null;var at=a.childNodes;for(var i=0;i<at.length;i++){if(at[i].tagName==\"A\"){if((first&&q.lc)||(!first&&q.mid)){a.insertBefore(qm_tabscss_create_tabimg(at[i],first),at[i]);i++;}lat=at[i];first=false;continue;}}if(lat&&q.rc){a.insertBefore(qm_tabscss_create_tabimg(lat,false,1),lat.nextSibling);}};function qm_tabscss_create_tabimg(a,isfirst,islast){var q=qmad.tabscss;var s=document.createElement(\"SPAN\");s.iscsstab=1;s.style.display=\"block\";s.style.position=\"relative\";s.style.fontSize=\"1px\";s.style.styleFloat=\"left\";s.style.cssFloat=\"left\";s.style.height=a.offsetHeight+\"px\";s.style.width=\"0px\";var part=\"middle\";if(isfirst)part=\"first\";if(islast)part=\"last\";var wt=\"\";q.z1=0;for(var i=0;i<q.h;i++)wt+=qm_tabscss_get_span(q.h,i,part);s.innerHTML=wt;return s;};function qm_tabscss_get_span(size,i,part){var q=qmad.tabscss;var it=i;var il=0;var ih=1;var iw=1;var ml=0;var mr=0;var bl=1;var br=1;if(q.type==\"angled\"){ml=i;mr=i;iw=((size-i)*2)-q.thick;il=-size+(q.thick-1);it+=q.toff;il+=q.loff;ih=q.thick;if(part==\"first\"){iw=size-i;bl=0;ml=0;il+=size-(q.thick);}if(part==\"last\"){iw=size-i;br=0;mr=0;il -=1;}}else  if(q.type==\"rounded\"){ml=i;mr=i;iw=((size-i)*2)-1;il=-size;il+=q.loff;it+=q.toff;ih=i+1;it=q.z1;q.z1+=ih;if(part==\"first\"){iw=size-i;bl=0;ml=0;il+=size-1;}if(part==\"last\"){iw=size-i;br=0;mr=0;}}return '<span style=\"background-color:'+q.background+';border-color:'+q.border+';border-width:0px '+br+'px 0px '+bl+'px;border-style:solid;display:block;position:absolute;overflow:hidden;font-size:1px;line-height:0px;height:'+ih+'px;margin-left:'+ml+'px;margin-right:'+mr+'px;width:'+iw+'px;top:'+it+'px;left:'+il+'px;\"></span>';}";
	

	
	qmv.addons.item_bullets = new Object();
	qmv.addons.item_bullets.compat = "all";
	qmv.addons.item_bullets.desc = "Item Bullets";
	qmv.addons.item_bullets.ontest = "ibullets_main_image|ibullets_sub_image";
	qmv.addons.item_bullets.code = "qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav6=qmad.br_navigator&&qmad.br_version<7;if(!qmad.br_oldnav6){if(!qmad.ibullets)qmad.ibullets=new Object();if(qmad.bvis.indexOf(\"qm_ibullets_active(o,false);\")==-1){qmad.bvis+=\"qm_ibullets_active(o,false);\";qmad.bhide+=\"qm_ibullets_active(a,1);\";if(window.attachEvent)window.attachEvent(\"onload\",qm_ibullets_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_ibullets_init,1);if(window.attachEvent)document.attachEvent(\"onmouseover\",qm_ibullets_hover_off);else  if(window.addEventListener)document.addEventListener(\"mouseover\",qm_ibullets_hover_off,false);}};function qm_ibullets_init(e,spec){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.item_bullets)&&(!z[\"on\"+qmv.id]&&z[\"on\"+qmv.id]!=undefined&&z[\"on\"+qmv.id]!=null))return;qm_ts=1;var q=qmad.ibullets;var a,b,r,sx,sy;z=window.qmv;for(i=0;i<10;i++){if(!(a=document.getElementById(\"qm\"+i))||(!isNaN(spec)&&spec!=i))continue;var ss=qmad[a.id];if(ss&&(ss.ibullets_main_image||ss.ibullets_sub_image)){q.mimg=ss.ibullets_main_image;if(q.mimg){q.mimg_a=ss.ibullets_main_image_active;if(!z)qm_ibullets_preload(q.mimg_a);q.mimg_h=ss.ibullets_main_image_hover;if(!z)qm_ibullets_preload(q.mimg_a);q.mimgwh=eval(\"new Array(\"+ss.ibullets_main_image_width+\",\"+ss.ibullets_main_image_height+\")\");r=q.mimgwh;if(!r[0])r[0]=9;if(!r[1])r[1]=6;sx=ss.ibullets_main_position_x;sy=ss.ibullets_main_position_y;if(!sx)sx=0;if(!sy)sy=0;q.mpos=eval(\"new Array('\"+sx+\"','\"+sy+\"')\");q.malign=eval(\"new Array('\"+ss.ibullets_main_align_x+\"','\"+ss.ibullets_main_align_y+\"')\");r=q.malign;if(!r[0])r[0]=\"right\";if(!r[1])r[1]=\"center\";}q.simg=ss.ibullets_sub_image;if(q.simg){q.simg_a=ss.ibullets_sub_image_active;if(!z)qm_ibullets_preload(q.simg_a);q.simg_h=ss.ibullets_sub_image_hover;if(!z)qm_ibullets_preload(q.simg_h);q.simgwh=eval(\"new Array(\"+ss.ibullets_sub_image_width+\",\"+ss.ibullets_sub_image_height+\")\");r=q.simgwh;if(!r[0])r[0]=6;if(!r[1])r[1]=9;sx=ss.ibullets_sub_position_x;sy=ss.ibullets_sub_position_y;if(!sx)sx=0;if(!sy)sy=0;q.spos=eval(\"new Array('\"+sx+\"','\"+sy+\"')\");q.salign=eval(\"new Array('\"+ss.ibullets_sub_align_x+\"','\"+ss.ibullets_sub_align_y+\"')\");r=q.salign;if(!r[0])r[0]=\"right\";if(!r[1])r[1]=\"middle\";}q.type=ss.ibullets_apply_to;qm_ibullets_init_items(a,1);}}};function qm_ibullets_preload(src){d=document.createElement(\"DIV\");d.style.display=\"none\";d.innerHTML=\"<img src=../../../../recursos/QuickMenu/visual_interface/qmv4//"+src+/" width=1 height=1>\";document.body.appendChild(d);};function qm_ibullets_init_items(a,main){var q=qmad.ibullets;var aa,pf;aa=a.childNodes;for(var j=0;j<aa.length;j++){if(aa[j].tagName==\"A\"){if(window.attachEvent)aa[j].attachEvent(\"onmouseover\",qm_ibullets_hover);else  if(window.addEventListener)aa[j].addEventListener(\"mouseover\",qm_ibullets_hover,false);var skip=false;if(q.type!=\"all\"){if(q.type==\"parent\"&&!aa[j].cdiv)skip=true;if(q.type==\"non-parent\"&&aa[j].cdiv)skip=true;}if(!skip){if(main)pf=\"m\";else pf=\"s\";if(q[pf+\"img\"]){var ii=document.createElement(\"IMG\");ii.setAttribute(\"src\",q[pf+\"img\"]);ii.setAttribute(\"width\",q[pf+\"imgwh\"][0]);ii.setAttribute(\"height\",q[pf+\"imgwh\"][1]);ii.style.borderWidth=\"0px\";ii.style.position=\"absolute\";var ss=document.createElement(\"SPAN\");var s1=ss.style;s1.display=\"block\";s1.position=\"relative\";s1.fontSize=\"1px\";s1.lineHeight=\"0px\";s1.zIndex=1;ss.ibhalign=q[pf+\"align\"][0];ss.ibvalign=q[pf+\"align\"][1];ss.ibiw=q[pf+\"imgwh\"][0];ss.ibih=q[pf+\"imgwh\"][1];ss.ibposx=q[pf+\"pos\"][0];ss.ibposy=q[pf+\"pos\"][1];qm_ibullets_position(aa[j],ss);ss.appendChild(ii);aa[j].qmibullet=aa[j].insertBefore(ss,aa[j].firstChild);aa[j][\"qmibullet\"+pf+\"a\"]=q[pf+\"img_a\"];aa[j][\"qmibullet\"+pf+\"h\"]=q[pf+\"img_h\"];aa[j].qmibulletorig=q[pf+\"img\"];ss.setAttribute(\"qmvbefore\",1);ss.setAttribute(\"isibullet\",1);if(aa[j].className.indexOf(\"qmactive\")+1)qm_ibullets_active(aa[j]);}}if(aa[j].cdiv)new qm_ibullets_init_items(aa[j].cdiv);}}};function qm_ibullets_position(a,b){if(b.ibhalign==\"right\")b.style.left=(a.offsetWidth+parseInt(b.ibposx)-b.ibiw)+\"px\";else  if(b.ibhalign==\"center\")b.style.left=(parseInt(a.offsetWidth/2)-parseInt(b.ibiw/2)+parseInt(b.ibposx))+\"px\";else b.style.left=b.ibposx+\"px\";if(b.ibvalign==\"bottom\")b.style.top=(a.offsetHeight+parseInt(b.ibposy)-b.ibih)+\"px\";else  if(b.ibvalign==\"middle\")b.style.top=parseInt((a.offsetHeight/2)-parseInt(b.ibih/2)+parseInt(b.ibposy))+\"px\";else b.style.top=b.ibposy+\"px\";};function qm_ibullets_hover(e,targ){e=e||window.event;if(!targ){var targ=e.srcElement||e.target;while(targ.tagName!=\"A\")targ=targ[qp];}var ch=qmad.ibullets.lasth;if(ch&&ch!=targ){qm_ibullets_hover_off(new Object(),ch);}if(targ.className.indexOf(\"qmactive\")+1)return;var wo=targ.qmibullet;var ma=targ.qmibulletmh;var sa=targ.qmibulletsh;if(wo&&(ma||sa)){var ti=ma;if(sa&&sa!=undefined)ti=sa;if(ma&&ma!=undefined)ti=ma;wo.firstChild.src=ti;qmad.ibullets.lasth=targ;}if(e)qm_kille(e);};function qm_ibullets_hover_off(e,o){if(!o)o=qmad.ibullets.lasth;if(o&&o.className.indexOf(\"qmactive\")==-1){if(o.firstChild&&o.firstChild.getAttribute&&o.firstChild.getAttribute(\"isibullet\"))o.firstChild.firstChild.src=o.qmibulletorig;}};function qm_ibullets_active(a,hide){var wo=a.qmibullet;var ma=a.qmibulletma;var sa=a.qmibulletsa;if(!hide&&a.className.indexOf(\"qmactive\")==-1)return;if(hide&&a.idiv){var o=a.idiv;if(o&&o.qmibulletorig){if(o.firstChild&&o.firstChild.getAttribute&&o.firstChild.getAttribute(\"isibullet\"))o.firstChild.firstChild.src=o.qmibulletorig;}}else {if(!a.cdiv.offsetWidth)a.cdiv.style.visibility=\"inherit\";qm_ibullets_wait_relative(a);/*if(a.cdiv){var aa=a.cdiv.childNodes;for(var i=0;i<aa.length;i++){if(aa[i].tagName==\"A\"&&aa[i].qmibullet)qm_ibullets_position(aa[i],aa[i].qmibullet);}}*/if(wo&&(ma||sa)){var ti=ma;if(sa&&sa!=undefined)ti=sa;if(ma&&ma!=undefined)ti=ma;wo.firstChild.src=ti;}}};function qm_ibullets_wait_relative(a){if(!a)a=qmad.ibullets.cura;if(a.cdiv){if(a.cdiv.qmtree&&a.cdiv.style.position!=\"relative\"){qmad.ibullets.cura=a;setTimeout(\"qm_ibcss_wait_relative()\",10);return;}var aa=a.cdiv.childNodes;for(var i=0;i<aa.length;i++){if(aa[i].tagName==\"A\"&&aa[i].qmibullet)qm_ibullets_position(aa[i],aa[i].qmibullet);}}}";


	qmv.addons.ibcss = new Object();
	qmv.addons.ibcss.compat = "all";
	qmv.addons.ibcss.desc = "Item Bullets (CSS - Imageless)";
	qmv.addons.ibcss.ontest = "ibcss_main_type|ibcss_sub_type";
	qmv.addons.ibcss.code = "qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav6=qmad.br_navigator&&qmad.br_version<7;qmad.br_strict=(dcm=document.compatMode)&&dcm==\"CSS1Compat\";qmad.br_ie=window.showHelp;qmad.str=(qmad.br_ie&&!qmad.br_strict);if(!qmad.br_oldnav6){if(!qmad.ibcss)qmad.ibcss=new Object();if(qmad.bvis.indexOf(\"qm_ibcss_active(o,false);\")==-1){qmad.bvis+=\"qm_ibcss_active(o,false);\";qmad.bhide+=\"qm_ibcss_active(a,1);\";if(window.attachEvent)window.attachEvent(\"onload\",qm_ibcss_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_ibcss_init,1);if(window.attachEvent)document.attachEvent(\"onmouseover\",qm_ibcss_hover_off);else  if(window.addEventListener)document.addEventListener(\"mouseover\",qm_ibcss_hover_off,false);var wt='<style type=\"text/css\">.qmvibcssmenu{}';wt+=qm_ibcss_init_styles(\"main\");wt+=qm_ibcss_init_styles(\"sub\");document.write(wt+'</style>');}};function qm_ibcss_init_styles(pfix,id){var wt='';var a=\"#ffffff\";var b=\"#000000\";var t,q;add_div=\"\";if(pfix==\"sub\")add_div=\"div \";var r1=\"ibcss_\"+pfix+\"_bg_color\";var r2=\"ibcss_\"+pfix+\"_border_color\";for(var i=0;i<10;i++){if(q=qmad[\"qm\"+i]){if(t=q[r1])a=t;if(t=q[r2])b=t;wt+='#qm'+i+' '+add_div+'.qm-ibcss-static span{background-color:'+a+';border-color:'+b+';}';if(t=q[r1+\"_hover\"])a=t;if(t=q[r2+\"_hover\"])b=t;wt+='#qm'+i+'  '+add_div+'.qm-ibcss-hover span{background-color:'+a+';border-color:'+b+';}';if(t=q[r1+\"_active\"])a=t;if(t=q[r2+\"_active\"])b=t;wt+='#qm'+i+'  '+add_div+'.qm-ibcss-active span{background-color:'+a+';border-color:'+b+';}';}}return wt;};function qm_ibcss_init(e,spec){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.ibcss)&&(!z[\"on\"+qmv.id]&&z[\"on\"+qmv.id]!=undefined&&z[\"on\"+qmv.id]!=null))return;qm_ts=1;var q=qmad.ibcss;var a,b,r,sx,sy;z=window.qmv;for(i=0;i<10;i++){if(!(a=document.getElementById(\"qm\"+i))||(!isNaN(spec)&&spec!=i))continue;var ss=qmad[a.id];if(ss&&(ss.ibcss_main_type||ss.ibcss_sub_type)){q.mtype=ss.ibcss_main_type;q.msize=ss.ibcss_main_size;if(!q.msize)q.msize=5;q.md=ss.ibcss_main_direction;if(!q.md)md=\"right\";q.mbg=ss.ibcss_main_bg_color;q.mborder=ss.ibcss_main_border_color;sx=ss.ibcss_main_position_x;sy=ss.ibcss_main_position_y;if(!sx)sx=0;if(!sy)sy=0;q.mpos=eval(\"new Array('\"+sx+\"','\"+sy+\"')\");q.malign=eval(\"new Array('\"+ss.ibcss_main_align_x+\"','\"+ss.ibcss_main_align_y+\"')\");r=q.malign;if(!r[0])r[0]=\"right\";if(!r[1])r[1]=\"center\";q.stype=ss.ibcss_sub_type;q.ssize=ss.ibcss_sub_size;if(!q.ssize)q.ssize=5;q.sd=ss.ibcss_sub_direction;if(!q.sd)sd=\"right\";q.sbg=ss.ibcss_sub_bg_color;q.sborder=ss.ibcss_sub_border_color;sx=ss.ibcss_sub_position_x;sy=ss.ibcss_sub_position_y;if(!sx)sx=0;if(!sy)sy=0;q.spos=eval(\"new Array('\"+sx+\"','\"+sy+\"')\");q.salign=eval(\"new Array('\"+ss.ibcss_sub_align_x+\"','\"+ss.ibcss_sub_align_y+\"')\");r=q.salign;if(!r[0])r[0]=\"right\";if(!r[1])r[1]=\"middle\";q.type=ss.ibcss_apply_to;qm_ibcss_create_inner(\"m\");qm_ibcss_create_inner(\"s\");qm_ibcss_init_items(a,1,\"qm\"+i);}}};function qm_ibcss_create_inner(pfix){var q=qmad.ibcss;var wt=\"\";var s=q[pfix+\"size\"];var type=q[pfix+\"type\"];var head;if(type.indexOf(\"head\")+1)head=true;var gap;if(type.indexOf(\"gap\")+1)gap=true;var v;if(type.indexOf(\"-v\")+1)v=true;if(type.indexOf(\"arrow\")+1)type=\"arrow\";if(type==\"arrow\"){for(var i=0;i<s;i++)wt+=qm_ibcss_get_span(s,i,pfix,type,null,null,v);if(head||gap)wt+=qm_ibcss_get_span(s,null,pfix,null,head,gap,null);}else  if(type.indexOf(\"square\")+1){var inner;if(type.indexOf(\"-inner\")+1)inner=true;var raised;if(type.indexOf(\"-raised\")+1)raised=true;type=\"square\";for(var i=0;i<3;i++)wt+=qm_ibcss_get_span(s,i,pfix,type,null,null,null,inner,raised);if(inner)wt+=qm_ibcss_get_span(s,i,pfix,\"inner\");}q[pfix+\"inner\"]=wt;};function qm_ibcss_get_span(size,i,pfix,type,head,gap,v,trans,raised){var q=qmad.ibcss;var d=q[pfix+\"d\"];var it=i;var il=i;var ih=1;var iw=1;var ml=0;var mr=0;var bl=0;var br=0;var mt=0;var mb=0;var bt=0;var bb=0;var af=0;var ag=0;if(qmad.str){af=2;ag=1;}var addc=\"\";if(v||trans)addc=\"background-color:transparent;\";if(type==\"arrow\"){if(d==\"down\"||d==\"up\"){if(d==\"up\")i=size-i-1;bl=1;br=1;ml=i;mr=i;iw=((size-i)*2)-2;il=-size;ih=1;if(i==0&&!v){bl=iw+2;br=0;ml=0;mr=0;iw=0;if(qmad.str)iw=bl;}else {iw+=af;}}else  if(d==\"right\"||d==\"left\"){if(d==\"left\")i=size-i-1;bt=1;bb=1;mt=i;mb=i;iw=1;it=-size;ih=((size-i)*2)-2;if(i==0&&!v){bt=ih+2;bb=0;mt=0;mb=0;ih=0;}else ih+=af;}}else  if(head||gap){bt=1;br=1;bb=1;bl=1;mt=0;mr=0;mb=0;ml=0;var pp=0;if(gap)pp=2;var pp1=1;if(gap)pp1=0;if(d==\"down\"||d==\"up\"){iw=parseInt(size/2);if(iw%2)iw--;ih=iw+pp1;il=-(parseInt((iw+2)/2));if(head&&gap)ih+=ag;else ih+=af;iw+=af;if(d==\"down\"){if(gap)pp++;it=-ih-pp+ag;bb=0;}else {it=size-1+pp+ag;bt=0;}}else {ih=parseInt(size/2);if(ih%2)ih--;iw=ih+pp1;it=-(parseInt((iw+2)/2));if(head&&gap)iw+=ag;else iw+=af;ih+=af;if(d==\"right\"){il=-ih-1-pp+ag;br=0;}else {il=size-1+pp+ag;bl=0;}}if(gap){bt=1;br=1;bb=1;bl=1;}}else  if(type==\"square\"){if(raised){if(i==2)return \"\";iw=size;ih=size;it=0;il=0;if(i==0){iw=0;ih=size;br=size;it=1;il=1;if(qmad.str)iw=br;}}else {if(size%2)size++;it=1;ih=size;iw=size;bl=1;br=1;il=0;iw+=af;if(i==0||i==2){ml=1;it=0;ih=1;bl=size;br=0;iw=0;if(qmad.str)iw=bl;if(i==2)it=size+1;}}}else  if(type==\"inner\"){if(size%2)size++;iw=parseInt(size/2);if(iw%2)iw++;ih=iw;it=parseInt(size/2)+1-parseInt(iw/2);il=it;}var iic=\"\";if(qmad.str)iic=\" \";return '<span style=\"'+addc+'border-width:'+bt+'px '+br+'px '+bb+'px '+bl+'px;border-style:solid;display:block;position:absolute;overflow:hidden;font-size:1px;line-height:0px;height:'+ih+'px;margin:'+mt+'px '+mr+'px '+mb+'px '+ml+'px;width:'+iw+'px;top:'+it+'px;left:'+il+'px;\">'+iic+'</span>';};function qm_ibcss_init_items(a,main){var q=qmad.ibcss;var aa,pf;aa=a.childNodes;for(var j=0;j<aa.length;j++){if(aa[j].tagName==\"A\"){if(window.attachEvent)aa[j].attachEvent(\"onmouseover\",qm_ibcss_hover);else  if(window.addEventListener)aa[j].addEventListener(\"mouseover\",qm_ibcss_hover,false);var skip=false;if(q.type!=\"all\"){if(q.type==\"parent\"&&!aa[j].cdiv)skip=true;if(q.type==\"non-parent\"&&aa[j].cdiv)skip=true;}if(!skip){if(main)pf=\"m\";else pf=\"s\";var ss=document.createElement(\"SPAN\");ss.className=\"qm-ibcss-static\";var s1=ss.style;s1.display=\"block\";s1.position=\"relative\";s1.fontSize=\"1px\";s1.lineHeight=\"0px\";s1.zIndex=1;ss.ibhalign=q[pf+\"align\"][0];ss.ibvalign=q[pf+\"align\"][1];ss.ibposx=q[pf+\"pos\"][0];ss.ibposy=q[pf+\"pos\"][1];ss.ibsize=q[pf+\"size\"];qm_ibcss_position(aa[j],ss);ss.innerHTML=q[pf+\"inner\"];aa[j].qmibulletcss=aa[j].insertBefore(ss,aa[j].firstChild);ss.setAttribute(\"qmvbefore\",1);ss.setAttribute(\"isibulletcss\",1);if(aa[j].className.indexOf(\"qmactive\")+1)qm_ibcss_active(aa[j]);}if(aa[j].cdiv)new qm_ibcss_init_items(aa[j].cdiv,null);}}};function qm_ibcss_position(a,b){if(b.ibhalign==\"right\")b.style.left=(a.offsetWidth+parseInt(b.ibposx)-b.ibsize)+\"px\";else  if(b.ibhalign==\"center\")b.style.left=(parseInt(a.offsetWidth/2)-parseInt(b.ibsize/2)+parseInt(b.ibposx))+\"px\";else b.style.left=b.ibposx+\"px\";if(b.ibvalign==\"bottom\")b.style.top=(a.offsetHeight+parseInt(b.ibposy)-b.ibsize)+\"px\";else  if(b.ibvalign==\"middle\")b.style.top=parseInt((a.offsetHeight/2)-parseInt(b.ibsize/2)+parseInt(b.ibposy))+\"px\";else b.style.top=b.ibposy+\"px\";};function qm_ibcss_hover(e,targ){e=e||window.event;if(!targ){var targ=e.srcElement||e.target;while(targ.tagName!=\"A\")targ=targ[qp];}var ch=qmad.ibcss.lasth;if(ch&&ch!=targ&&ch.qmibulletcss)qm_ibcss_hover_off(new Object(),ch);if(targ.className.indexOf(\"qmactive\")+1)return;var wo=targ.qmibulletcss;if(wo){x2(\"qm-ibcss-hover\",wo,1);qmad.ibcss.lasth=targ;}if(e)qm_kille(e);};function qm_ibcss_hover_off(e,o){if(!o)o=qmad.ibcss.lasth;if(o&&o.qmibulletcss)x2(\"qm-ibcss-hover\",o.qmibulletcss);};function qm_ibcss_active(a,hide){if(!hide&&a.className.indexOf(\"qmactive\")==-1)return;if(hide&&a.idiv){var o=a.idiv;if(o&&o.qmibulletcss){x2(\"qm-ibcss-active\",o.qmibulletcss);}}else {if(!a.cdiv.offsetWidth)a.cdiv.style.visibility=\"inherit\";qm_ibcss_wait_relative(a);var wo=a.qmibulletcss;if(wo)x2(\"qm-ibcss-active\",wo,1);}};function qm_ibcss_wait_relative(a){if(!a)a=qmad.ibcss.cura;if(a.cdiv){if(a.cdiv.qmtree&&a.cdiv.style.position!=\"relative\"){qmad.ibcss.cura=a;setTimeout(\"qm_ibcss_wait_relative()\",10);return;}var aa=a.cdiv.childNodes;for(var i=0;i<aa.length;i++){if(aa[i].tagName==\"A\"&&aa[i].qmibulletcss)qm_ibcss_position(aa[i],aa[i].qmibulletcss);}}}";
	
	

	qmv.addons.over_select = new Object();
	qmv.addons.over_select.compat = "bump_effect,ritem,image,drop_shadow,round_corners,match_widths,merge_effect,slide_effect,tabs,apsubs,tabscss,pointer,box_effect";
	qmv.addons.over_select.desc = "IE Over Select Fix";
	qmv.addons.over_select.ontest = "overselects_active";
	qmv.addons.over_select.code = "if(window.showHelp&&!window.XMLHttpRequest){if(qmad.bvis.indexOf(\"qm_over_select(b.cdiv);\")==-1){qmad.bvis+=\"qm_over_select(b.cdiv);\";qmad.bhide+=\"qm_over_select(a,1);\";}};function qm_over_select(a,hide){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.over_select)&&!z[\"on\"+qm_index(a)])return;if(!a.settingsid){var v=a;while(!qm_a(v))v=v[qp];a.settingsid=v.id;}var ss=qmad[a.settingsid];if(!ss)return;if(!ss.overselects_active)return;if(!hide&&!a.hasselectfix){var f=document.createElement(\"IFRAME\");f.style.position=\"absolute\";f.style.filter=\"alpha(opacity=0)\";f.src=\"javascript:false;\";f=a.parentNode.appendChild(f);f.frameborder=0;a.hasselectfix=f;}var b=a.hasselectfix;if(b){if(hide)b.style.display=\"none\";else {if(a.hasrcorner&&a.hasrcorner.style.visibility==\"inherit\")a=a.hasrcorner;var oxy=0;if(a.hasshadow&&a.hasshadow.style.visibility==\"inherit\")oxy=parseInt(ss.shadow_offset);if(!oxy)oxy=0;b.style.width=a.offsetWidth+oxy;b.style.height=a.offsetHeight+oxy;b.style.top=a.style.top;b.style.left=a.style.left;b.style.margin=a.currentStyle.margin;b.style.display=\"block\";}}}";
	

	qmv.addons.apsubs = new Object();
	qmv.addons.apsubs.compat = "ritem,image,drop_shadow,round_corners,match_widths,tabs,image,tabscss,pointer";
	qmv.addons.apsubs.desc = "Keep Subs In window";
	qmv.addons.apsubs.ontest = "subs_in_window_active";
	qmv.addons.apsubs.code = "qmad.apsubs=new Object();if(qmad.bvis.indexOf(\"qm_apsubs(b.cdiv,o);\")==-1)qmad.bvis+=\"qm_apsubs(b.cdiv,o);\";;function qm_apsubs(a){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.apsubs)&&!z[\"on\"+qm_index(a)])return;if(!a.settingsid){var v=a;while(!qm_a(v))v=v[qp];a.settingsid=v.id;}var ss=qmad[a.settingsid];if(!ss)return;if(!ss.subs_in_window_active)return;var wh=qm_get_doc_wh();var sxy=qm_get_doc_scrollxy();var xy=qm_get_offset(a);var c1=a.offsetWidth+xy[0];var c2=wh[0]+sxy[0];if(c1>c2){a.style.left=(parseInt(a.style.left)-(c1-c2))+\"px\";if(a.hasrcorner)a.hasrcorner.style.left=(parseInt(a.hasrcorner.style.left)-(c1-c2))+\"px\";if(a.hasshadow)a.hasshadow.style.left=(parseInt(a.hasshadow.style.left)-(c1-c2))+\"px\";if(a.hasselectfix)a.hasselectfix.style.left=(parseInt(a.hasselectfix.style.left)-(c1-c2))+\"px\";}c1=a.offsetHeight+xy[1];c2=wh[1]+sxy[1];if(c1>c2){a.style.top=(parseInt(a.style.top)-(c1-c2))+\"px\";if(a.hasrcorner)a.hasrcorner.style.top=(parseInt(a.hasrcorner.style.top)-(c1-c2))+\"px\";if(a.hasshadow)a.hasshadow.style.top=(parseInt(a.hasshadow.style.top)-(c1-c2))+\"px\";if(a.hasselectfix)a.hasselectfix.style.top=(parseInt(a.hasselectfix.style.top)-(c1-c2))+\"px\";}};function qm_get_offset(obj){var x=0;var y=0;do{x+=obj.offsetLeft;y+=obj.offsetTop;}while(obj=obj.offsetParent)return new Array(x,y);};function qm_get_doc_scrollxy(){var sy=0;var sx=0;if((sd=document.documentElement)&&(sd=sd.scrollTop))sy=sd;else  if(sd=document.body.scrollTop)sy=sd;if((sd=document.documentElement)&&(sd=sd.scrollLeft))sx=sd;else  if(sd=document.body.scrollLeft)sx=sd;return new Array(sx,sy);};function qm_get_doc_wh(){db=document.body;var w=0;var h=0;if(tval=window.innerHeight){h=tval;w=window.innerWidth;}else  if((e=document.documentElement)&&(e=e.clientHeight)){h=e;w=document.documentElement.clientWidth;}else  if(e=db.clientHeight){if(!h)h=e;if(!w)w=db.clientWidth;}return new Array(w,h);}";
	

	qmv.addons.pointer = new Object();
	qmv.addons.pointer.compat = "bump_effect,ritem,sopen,apsubs,over_select,ibcss,item_bullets,tabscss,tabs,slide_effect,merge_effect,match_widths,round_corners,drop_shadow,image,box_effect";
	qmv.addons.pointer.desc = "Follow Pointer";
	qmv.addons.pointer.ontest = "pointer_main_image|pointer_sub_image";
	qmv.addons.pointer.code = "qmad.br_safari=navigator.userAgent.indexOf(\"afari\")+1;qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;if(!qmad.pointer&&!qmad.br_oldnav){qmad.pointer=new Object();if(window.attachEvent)window.attachEvent(\"onload\",qm_pointer_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_pointer_init,1);if(window.attachEvent)document.attachEvent(\"onmouseover\",qm_pointer_hide);else  if(window.addEventListener)document.addEventListener(\"mouseover\",qm_pointer_hide,false);};function qm_pointer_init(e,spec){var q=qmad.pointer;var a;for(i=0;i<10;i++){if(!(a=document.getElementById(\"qm\"+i))||(spec&&spec!=i))continue;var ss=qmad[a.id];if(ss&&(ss.pointer_main_image||ss.pointer_sub_image)){q.mimg=ss.pointer_main_image;q.mimgw=ss.pointer_main_image_width;if(!q.mimgw)q.mimgw=0;q.mimgh=ss.pointer_main_image_height;if(!q.mimgh)q.mimgh=0;q.malign=ss.pointer_main_align;if(!q.malign)q.malign=\"top-or-left\";q.mox=ss.pointer_main_off_x;if(!q.mox)q.mox=0;q.moy=ss.pointer_main_off_y;if(!q.moy)q.moy=0;q.simg=ss.pointer_sub_image;q.simgw=ss.pointer_sub_image_width;if(!q.mimgw)q.simgw=0;q.simgh=ss.pointer_sub_image_height;if(!q.mimgh)q.mimgh=0;q.salign=ss.pointer_sub_align;if(!q.salign)q.salign=\"top-or-left\";q.sox=ss.pointer_sub_off_x;if(!q.sox)q.sox=0;q.soy=ss.pointer_sub_off_y;if(!q.soy)q.soy=0;qm_pointer_add(a,\"m\");var at=a.getElementsByTagName(\"DIV\");for(var i=0;i<at.length;i++)qm_pointer_add(at[i],\"s\");}i++;}};function qm_pointer_add(a,type){var q=qmad.pointer;var img=q[type+\"img\"];if(a.attachEvent)a.attachEvent(\"onmousemove\",qm_pointer_move);else  if(a.addEventListener)a.addEventListener(\"mousemove\",qm_pointer_move,1);if(!img)return;var sp=document.createElement(\"SPAN\");sp.style.position=\"absolute\";sp.style.visibility=\"hidden\";if(a.ch)sp.style.top=(-q[type+\"imgh\"]+q[type+\"oy\"])+\"px\";else sp.style.left=(-q[type+\"imgw\"]+q[type+\"ox\"])+\"px\";if(q[type+\"align\"]==\"bottom-or-right\")sp.pointerbr=1;sp.pointerox=q[type+\"ox\"];sp.pointeroy=q[type+\"oy\"];sp.innerHTML='<img style=\"position:absolute;\" src=../../../../recursos/QuickMenu/visual_interface/qmv4//"'+img+'/"  width='+q[type+\"imgw\"]+' height='+q[type+\"imgh\"]+'>';sp=a.appendChild(sp);a.haspointer=sp;};function qm_pointer_hide(){var q=qmad.pointer;if(q.lastm&&a!=q.lastm){q.lastm.style.visibility=\"hidden\";q.lastm=null;}};function qm_pointer_move(e){var q=qmad.pointer;e=e||window.event;targ=e.srcElement||e.target;while(targ.tagName!=\"DIV\")targ=targ[qp];if(q.lastm&&a!=q.lastm){q.lastm.style.visibility=\"hidden\";q.lastm=null;}var a;if(a=targ.haspointer){if(a.style.visibility!=\"inherit\")a.style.visibility=\"inherit\";var x=e.clientX;var y=e.clientY;var oxy=qm_pointer_get_offsets(targ);if(targ.ch){a.style.left=(x-oxy[0]+a.pointerox)+\"px\";if(a.pointerbr)a.style.top=(targ.offsetHeight+a.pointeroy)+\"px\";}else {a.style.top=(y-oxy[1]+a.pointeroy)+\"px\";if(a.pointerbr)a.style.left=(targ.offsetWidth+a.pointerox)+\"px\";}q.lastm=a;}};function qm_pointer_get_offsets(a){var x=0;var y=0;while(a){x+=a.offsetLeft;y+=a.offsetTop;a=a.offsetParent;}return new Array(x,y);}";


	qmv.addons.box_effect = new Object();
	qmv.addons.box_effect.compat = "ritem,sopen,apsubs,over_select,ibcss,item_bullets,tabscss,tabs,match_widths,round_corners,drop_shadow,image,box_effect";
	qmv.addons.box_effect.desc = "Box Animation";
	qmv.addons.box_effect.ontest = "box_animation_frames";
	qmv.addons.box_effect.code = "qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav=qmad.br_navigator&&qmad.br_version<7.1;qmad.br_ie=window.showHelp;qmad.br_mac=navigator.userAgent.indexOf(\"Mac\")+1;qmad.br_old_safari=navigator.userAgent.indexOf(\"afari\")+1&&!window.XMLHttpRequest;qmad.box_off=(qmad.br_mac&&qmad.br_ie)||qmad.br_old_safari;if(!qmad.box){qmad.box=new Object();if(qmad.bvis.indexOf(\"qm_box_a(b.cdiv);\")==-1)qmad.bvis+=\"qm_box_a(b.cdiv);\";if(qmad.bhide.indexOf(\"qm_box_a(a,1);\")==-1)qmad.bhide+=\"qm_box_a(a,1);\";if(window.attachEvent)document.attachEvent(\"onmouseover\",qm_box_hide);else  if(window.addEventListener)document.addEventListener(\"mouseover\",qm_box_hide,false);};function qm_box_a(a,hide){var z;if((a.style.visibility==\"inherit\"&&!hide)||(qmad.box_off)||((z=window.qmv)&&(z=z.addons)&&(z=z.box_effect)&&!z[\"on\"+qm_index(a)]))return;var ss;if(!a.settingsid){var v=a;while((v=v.parentNode)){if(v.className.indexOf(\"qmmc\")+1){a.settingsid=v.id;break;}}}ss=qmad[a.settingsid];if(!ss)return;if(!ss.box_animation_frames)return;qm_th=0;var steps=ss.box_animation_frames;var b=new Object();b.obj=a;b.accelerator=ss.box_accelerator;if(!b.accelerator)b.accelerator=0;b.position=ss.box_position;if(!b.position)b.position=\"center\";if(!a.hasbox){var s=document.createElement(\"SPAN\");s.className=\"qmbox\";s.style.display=\"block\";s.style.position=\"absolute\";s.style.top=a.offsetTop+\"px\";s.style.left=a.offsetLeft+\"px\";s.style.fontSize=\"1px\";s.style.lineHieght=\"0px\";s=a[qp].appendChild(s);a.hasbox=s;}b.stepx=a.offsetWidth/steps;b.stepy=a.offsetHeight/steps;if(hide){b.growx=a.hasbox.offsetWidth;b.growy=a.hasbox.offsetHeight;b.ishide=true;}else {b.growx=0;b.growy=0;}b.fixsize=2;x2(\"qmfh\",a,1);if(a.hasrcorner)x2(\"qmfh\",a.hasrcorner,1);if(a.hasshadow)x2(\"qmfh\",a.hasshadow,1);a.hasbox.style.visibility=\"visible\";qm_box_ai(qm_box_am(b,hide),hide);};function qm_box_ai(id,hide){var a=qmad.box[\"_\"+id];if(!a||!a.obj.hasbox)return;var box=a.obj.hasbox;var sub=a.obj;a.stepy+=a.accelerator;a.stepx+=a.accelerator;var go=false;if(!hide){a.growx+=a.stepx;a.growy+=a.stepy;if(a.growx<sub.offsetWidth){go=true;box.style.width=parseInt(a.growx)+\"px\";qm_box_position_it(box,a);}else box.style.width=(sub.offsetWidth-a.fixsize)+\"px\";if(a.growy<sub.offsetHeight){go=true;box.style.height=parseInt(a.growy)+\"px\";}else box.style.height=(sub.offsetHeight-a.fixsize)+\"px\";}else {a.growx-=a.stepx;a.growy-=a.stepy;if(a.growx>0){go=true;box.style.width=parseInt(a.growx)+\"px\";qm_box_position_it(box,a);}else box.style.width=0+\"px\";if(a.growy>0){go=true;box.style.height=parseInt(a.growy)+\"px\";}else box.style.height=0+\"px\";}if(go){a.timer=setTimeout(\"qm_box_ai(\"+id+\",\"+hide+\")\",10);}else {if(!hide)qm_box_position_it(box,a,1);x2(\"qmfh\",sub);if(sub.hasrcorner)x2(\"qmfh\",sub.hasrcorner);if(sub.hasshadow)x2(\"qmfh\",sub.hasshadow);box.style.visibility=\"hidden\";}};function qm_box_position_it(box,a,def){if(a.position==\"center\"){box.style.left=parseInt((a.obj.offsetWidth-box.offsetWidth)/2)+a.obj.offsetLeft+\"px\";box.style.top=parseInt((a.obj.offsetHeight-box.offsetHeight)/2)+a.obj.offsetTop+\"px\";}else {if(a.position==\"top\"){box.style.left=parseInt((a.obj.offsetWidth-box.offsetWidth)/2)+a.obj.offsetLeft+\"px\";box.style.top=a.obj.offsetTop+\"px\";}else  if(a.position==\"left\"){box.style.left=a.obj.offsetLeft+\"px\";box.style.top=parseInt((a.obj.offsetHeight-box.offsetHeight)/2)+a.obj.offsetTop+\"px\";}}};function qm_box_hide(){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.box_effect)&&!qmv.preview_mode)return;var k;for(k in qmad.box){var a;if((a=qmad.box[k]).obj){if(!a.ishide&&a.timer){clearTimeout(a.timer);a.timer=null;qm_box_a(a.obj,1);}}}};function qm_box_am(obj,hide){var k;for(k in qmad.box){if(qmad.box[k]&&obj.obj==qmad.box[k].obj){if(qmad.box[k].timer){clearTimeout(qmad.box[k].timer);qmad.box[k].timer=null;}qmad.box[k]=null;}}var i=0;while(qmad.box[\"_\"+i])i++;qmad.box[\"_\"+i]=obj;return i;}";
	


	qmv.addons.ritem = new Object();
	qmv.addons.ritem.compat = "bump_effect,box_effect,pointer,sopen,apsubs,over_select,ibcss,item_bullets,tabs,tabscss,slide_effect,merge_effect,match_widths,round_corners,drop_shadow,keyboard,image";
	qmv.addons.ritem.desc = "Rounded Items";
	qmv.addons.ritem.ontest = "ritem_size";
	qmv.addons.ritem.code = "qmad.br_navigator=navigator.userAgent.indexOf(\"Netscape\")+1;qmad.br_version=parseFloat(navigator.vendorSub);qmad.br_oldnav6=qmad.br_navigator&&qmad.br_version<7;qmad.br_strict=(dcm=document.compatMode)&&dcm==\"CSS1Compat\";qmad.br_ie=window.showHelp;qmad.str=(qmad.br_ie&&!qmad.br_strict);if(!qmad.br_oldnav6){if(!qmad.ritem){qmad.ritem=new Object();if(qmad.bvis.indexOf(\"qm_ritem_a(b.cdiv);\")==-1){qmad.bvis+=\"qm_ritem_a(b.cdiv);\";qmad.bhide+=\"qm_ritem_a_hide(a);\";}if(window.attachEvent)window.attachEvent(\"onload\",qm_ritem_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_ritem_init,1);var ca=\"cursor:pointer;\";if(qmad.br_ie)ca=\"cursor:hand;\";var wt='<style type=\"text/css\">.qmvritemmenu{}';wt+=\".qmmc .qmritem span{\"+ca+\"}\";document.write(wt+'</style>');}};function qm_ritem_init(e,spec){var z;if((z=window.qmv)&&(z=z.addons)&&(z=z.ritem)&&(!z[\"on\"+qmv.id]&&z[\"on\"+qmv.id]!=undefined&&z[\"on\"+qmv.id]!=null))return;qm_ts=1;var q=qmad.ritem;var a,b,r,sx,sy;z=window.qmv;for(i=0;i<10;i++){if(!(a=document.getElementById(\"qm\"+i))||(!isNaN(spec)&&spec!=i))continue;var ss=qmad[a.id];if(ss&&ss.ritem_size){q.size=ss.ritem_size;q.apply=ss.ritem_apply;if(!q.apply)q.apply=\"main\";q.angle=ss.ritem_angle_corners;q.corners_main=ss.ritem_main_apply_corners;if(!q.corners_main||q.corners_main.length<4)q.corners_main=new Array(true,1,1,1);q.corners_sub=ss.ritem_sub_apply_corners;if(!q.corners_sub||q.corners_sub.length<4)q.corners_sub=new Array(true,1,1,1);q.sactive=false;if(ss.ritem_show_on_actives)q.sactive=true;q.opacity=ss.ritem_opacity;if(q.opacity&&q.opacity!=1){var addf=\"\";if(window.showHelp)addf=\"filter:alpha(opacity=\"+(q.opacity*100)+\");\";q.opacity=\"opacity:\"+q.opacity+\";\"+addf;}else q.opacity=\"\";qm_ritem_add_rounds(a);}}};function qm_ritem_a_hide(a){if(a.idiv.hasritem&&qmad.ritem.sactive)a.idiv.hasritem.style.left=\"-10000px\";};function qm_ritem_a(a){if(a)qmad.ritem.a=a;else a=qmad.ritem.a;if(a.idiv.hasritem&&qmad.ritem.sactive)a.idiv.hasritem.style.left=a.idiv.offsetLeft+\"px\";if(a.ritemfixed)return;var aa=a.childNodes;for(var i=0;i<aa.length;i++){var b;if(b=aa[i].hasritem){if(!aa[i].offsetWidth){setTimeout(\"qm_ritem_a()\",10);return;}else {b.style.top=aa[i].offsetTop+\"px\";b.style.left=aa[i].offsetLeft+\"px\";b.style.width=aa[i].offsetWidth+\"px\";a.ritemfixed=1;}}}};function qm_ritem_add_rounds(a){var q=qmad.ritem;var atags,ist,isd,isp,gom,gos;if(q.apply.indexOf(\"titles\")+1)ist=true;if(q.apply.indexOf(\"dividers\")+1)isd=true;if(q.apply.indexOf(\"parents\")+1)isp=true;if(q.apply.indexOf(\"sub\")+1)gos=true;if(q.apply.indexOf(\"main\")+1)gom=true;atags=a.childNodes;for(var k=0;k<atags.length;k++){if((atags[k].tagName!=\"SPAN\"&&atags[k].tagName!=\"A\")||(q.sactive&&!atags[k].cdiv))continue;var ism=qm_a(atags[k][qp]);if((isd&&atags[k].className.indexOf(\"qmdivider\")+1)||(ist&&atags[k].className.indexOf(\"qmtitle\")+1)||(gom&&ism&&atags[k].tagName==\"A\")||(atags[k].className.indexOf(\"qmrounditem\")+1)||(gos&&!ism&&atags[k].tagName==\"A\")||(isp&&atags[k].cdiv)){var f=document.createElement(\"SPAN\");f.className=\"qmritem\";f.setAttribute(\"qmvbefore\",1);var fs=f.style;fs.position=\"absolute\";fs.display=\"block\";fs.top=atags[k].offsetTop+\"px\";fs.left=atags[k].offsetLeft+\"px\";fs.width=atags[k].offsetWidth+\"px\";if(q.sactive&&atags[k].cdiv.style.visibility!=\"inherit\")fs.left=\"-10000px\";var size=q.size;q.mid=parseInt(size/2);q.ps=new Array(size+1);var t2=0;q.osize=q.size;if(!q.angle){for(var i=0;i<=size;i++){if(i==q.mid)t2=0;q.ps[i]=t2;t2+=Math.abs(q.mid-i)+1;}q.osize=1;}var fi=\"\";var ctype=\"main\";if(!ism)ctype=\"sub\";for(var i=0;i<size;i++)fi+=qm_ritem_get_span(size,i,1,ctype);var cn=atags[k].cloneNode(true);var cns=cn.getElementsByTagName(\"SPAN\");for(var l=0;l<cns.length;l++){if(cns[l].getAttribute(\"isibulletcss\")||cns[l].getAttribute(\"isibullet\"))cn.removeChild(cns[l]);}fi+='<span class=\"qmritemcontent\" style=\"display:block;border-style:solid;border-width:0px 1px 0px 1px;'+q.opacity+'\">'+cn.innerHTML+'</span>';for(var i=size-1;i>=0;i--)fi+=qm_ritem_get_span(size,i,null,ctype);f.innerHTML=fi;f=atags[k].insertBefore(f,atags[k].firstChild);atags[k].hasritem=f;}if(atags[k].cdiv)new qm_ritem_add_rounds(atags[k].cdiv);}};function qm_ritem_get_span(size,i,top,ctype){var q=qmad.ritem;var mlmr;if(i==0){var mo=q.ps[size]+q.mid;if(q.angle)mo=size-i;var fs=\"\";if(qmad.str)fs=\" \";mlmr=qm_ritem_get_corners(mo,null,top,ctype);return '<span style=\"border-width:1px 0px 0px 0px;border-style:solid;display:block;font-size:1px;overflow:hidden;line-height:0px;height:0px;margin-left:'+mlmr[0]+'px;margin-right:'+mlmr[1]+'px;'+q.opacity+'\">'+fs+'</span>';}else {var md=size-(i);var ih=1;var bs=1;if(!q.angle){if(i>=q.mid)ih=Math.abs(q.mid-i)+1;else {bs=Math.abs(q.mid-i)+1;md=q.ps[size-i]+q.mid;}if(top)q.osize+=ih;}mlmr=qm_ritem_get_corners(md,bs,top,ctype);return '<span style=\"border-width:0px '+mlmr[3]+'px 0px '+mlmr[2]+'px;border-style:solid;display:block;overflow:hidden;font-size:1px;line-height:0px;height:'+ih+'px;margin-left:'+mlmr[0]+'px;margin-right:'+mlmr[1]+'px;'+q.opacity+'\"></span>';}};function qm_ritem_get_corners(mval,bval,top,ctype){var q=qmad.ritem;var ml=mval;var mr=mval;var bl=bval;var br=bval;if(top){if(!q[\"corners_\"+ctype][0]){ml=0;bl=1;}if(!q[\"corners_\"+ctype][1]){mr=0;br=1;}}else {if(!q[\"corners_\"+ctype][2]){mr=0;br=1;}if(!q[\"corners_\"+ctype][3]){ml=0;bl=1;}}return new Array(ml,mr,bl,br);}";


	qmv.addons.sopen = new Object();
	qmv.addons.sopen.compat = "all";
	qmv.addons.sopen.desc = "Show Select Containers On Load";
	qmv.addons.sopen.noupdate = 1;
	qmv.addons.sopen.code = "if(!qmad.sopen){qmad.sopen=new Object();qmad.sopen.log=new Array();if(window.attachEvent)window.attachEvent(\"onload\",qm_sopen_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_sopen_init,1);};function qm_sopen_init(e,go){if(window.qmv)return;if(!go){setTimeout(\"qm_sopen_init(null,1)\",10);return;}var i;var ql=qmad.sopen.log;for(i=0;i<10;i++){var a;if(a=document.getElementById(\"qm\"+i)){var dd=a.getElementsByTagName(\"DIV\");for(var j=0;j<dd.length;j++){if(dd[j].idiv.className.indexOf(\"qm-startopen\")+1){ql.push(dd[j].idiv);var f=dd[j][qp];if(!qm_a(f)){var b=false;for(var k=0;k<ql.length;k++){if(ql[k]==f.idiv)ql[k]=null;}ql.push(f.idiv);f=f[qp];}}}}}var se=0;var sc=0;if(qmad.tree){se=qmad.tree.etype;sc=qmad.tree.ctype;qmad.tree.etype=0;qmad.tree.ctype=0;}for(i=ql.length-1;i>=0;i--){if(ql[i]){qm_oo(new Object(),ql[i],1);qm_li=null;}}if(qmad.tree){qmad.tree.etype=se;qmad.tree.ctype=sc;}}";

	qmv.addons.sopen_auto = new Object();
	qmv.addons.sopen_auto.compat = "all";
	qmv.addons.sopen_auto.desc = "Persistent States With Auto Open Subs Option";
	qmv.addons.sopen_auto.noupdate = 1;
	qmv.addons.sopen_auto.ontest = "sopen_auto_enabled";	
	qmv.addons.sopen_auto.code = "if(!qmad.sopen_auto){qmad.sopen_auto=new Object();qmad.sopen_auto.log=new Array();if(window.attachEvent)window.attachEvent(\"onload\",qm_sopen_auto_init);else  if(window.addEventListener)window.addEventListener(\"load\",qm_sopen_auto_init,1);};function qm_sopen_auto_init(e,go){if(window.qmv)return;if(!go){setTimeout(\"qm_sopen_auto_init(null,1)\",10);return;}var i;var ql=qmad.sopen_auto.log;for(i=0;i<10;i++){var ss=qmad[\"qm\"+i];if(!ss||!ss.sopen_auto_enabled)continue;var curl=unescape(window.location.href).toLowerCase();curl=qm_sopen_auto_clean(curl);var a;if(a=document.getElementById(\"qm\"+i)){var dd=a.getElementsByTagName(\"A\");for(var j=0;j<dd.length;j++){var aurl=unescape(dd[j].getAttribute(\"href\",1)).toLowerCase();aurl=qm_sopen_auto_clean(aurl);loc=curl.length-aurl.length;if(aurl&&aurl!=\"#\"&&loc>-1&&curl.indexOf(aurl)+1){var wa=dd[j];if(wa.cdiv)wa=wa.cdiv;while(!qm_a(wa)){if(wa.tagName==\"DIV\"){if(wa.idiv){ql.push(wa.idiv);x2(\"qmpersistent\",wa.idiv,1);}}else  if(wa.tagName==\"A\")x2(\"qmpersistent\",wa,1);wa=wa[qp];}}}}}var se=0;var sc=0;if(qmad.tree){se=qmad.tree.etype;sc=qmad.tree.ctype;qmad.tree.etype=0;qmad.tree.ctype=0;}for(i=ql.length-1;i>=0;i--){if(ql[i]){qm_oo(new Object(),ql[i],1);qm_li=null;}}if(qmad.tree){qmad.tree.etype=se;qmad.tree.ctype=sc;}};function qm_sopen_auto_clean(url){url=url.replace(/\\:/g,\"\");url=url.replace(\"localhost\",\"\");url=url.replace(\"file\",\"\");url=url.replace(/\\\\/g,\"\");url=url.replace(/\\//g,\"\");url=url.replace(/\\./g,\"\");return url;}";

	
	

}




function qmv_get_add_on_code(name)
{
	var i;

	rc = "";
	if (name=="all")
	{
		
		for (i in qmv.addons)
		{
			
			rc += qmv.addons[i].code;

			
		}

	}
	
	return rc;
}







//**********  Visual Tree Component



function qm_vtree_init_styles(is_setbox)
{


	var a,b;

	if (qmad)
	{



		var ss = qmad.qmvtree;
		var i="qmvtree";

		if (is_setbox)
		{
			var ss = qmad.qmsetbox;
			var i="qmsetbox";
		}


		if(ss && ss.tree_width)
		{
				
			var az = "";
			if (window.showHelp) az = "zoom:1;";

			var wv = '<style type="text/css">#'+i+' a {float:none !important;}#'+i+' div{overflow:hidden;position:relative;display:none;'+az+'padding-top:0px !important;padding-bottom:0px !important;border-top-width:0px !important;border-bottom-width:0px !important;margin-left:0px !important;margin-top:0px !important;}';

				
			var curt = "div ";				
			for (var j=0;j<10;j++)
			{
				var iv = ss.tree_sub_indent;
				if (b = ss["tree_sub_indent"+j])
					iv = b;

					
				wv += '#'+i+' '+curt+'{padding-left:'+iv+'px;}';
				curt += "div ";
			}


			if (b = ss.tree_sub_top_padding)
				wv += '#'+i+' .qmvtreefirsta{margin-top:'+b+'px !important;}';

			if (b = ss.tree_sub_bottom_padding)
				wv += '#'+i+' .qmvtreelasta{margin-bottom:'+b+'px !important;}';

				
			document.write(wv+'</style>');
		
		}

		
		
	}

}


function qm_vtree_init(is_setbox)
{
	
	var q = qmad.qmvtree;
	var ss = qmad.qmvtree;

	if (is_setbox)
	{
		var ss=qmad.qmsetbox;
		var q=qmad.qmsetbox;
	}


	if (ss && ss.tree_width)
	{	
			
		q.estep = ss.tree_expand_step_size;
		q.cstep = ss.tree_collapse_step_size;
		q.acollapse = ss.tree_auto_collapse;
		q.no_focus = ss.tree_hide_focus_box;

		q.etype = ss.tree_expand_animation;
		q.ctype = ss.tree_collapse_animation;
			
		if (qmad.br_oldnav)
		{
			q.etype=0;
			q.ctype=0;
		}
		
		if (is_setbox)
			qm_vtree_init_items(document.getElementById("qmsetbox"));
		else
			qm_vtree_init_items(document.getElementById("qmvtree"));
	}


}



function qm_vtree_init_items(a,sub)
{
	
	

	var w,b;
	var q = qmad.qmvtree;

	
	
	var first,last,aa;
		
		
	first = null;
	last = null;
		
	aa = a.childNodes;
	for (var j=0;j<aa.length;j++)
	{
		
		if (aa[j].tagName=="A")
		{

			aa[j].qmtree = 1;

			if (!first)
				first = aa[j];
					
			last = aa[j];
			
			
			if (aa[j].cdiv)	
			{
				aa[j].cdiv.ismove = 1;
				aa[j].cdiv.qmtree = 1;
			}
		
		
			//if (!aa[j].onclick)
			//{

				aa[j].onclick = qmv_tree_oo;
				aa[j].onmouseover = null;
				
			//}

				
			if (q.no_focus)
			{
					
				
				aa[j].onfocus = function()
				{
					this.blur();
				
				};
			}


			if (aa[j].cdiv)
			{
				if (sub)
				{
					
					aa[j].oncontextmenu = function(e)
					{
						e = e || event;
						qmv_show_context(e,'tree_parent',null,this);
						qm_kille(e);
						return false;
						
					}
				}

				new qm_vtree_init_items(aa[j].cdiv,true);

			}


			
			if (aa[j].getAttribute("initshow"))
			{
				qm_arc("qmactive",aa[j],true);
				aa[j].cdiv.style.display = "block";
				aa[j].cdiv.style.visibility = "inherit";

				
				if (aa[j].getAttribute("isfresultsa"))
					qmv_filter_init();
				

			}

			qm_arc("qmvtreefirsta",first);
			qm_arc("qmvtreelasta",last);
							
			
		}
		
		

	}


	
	if (sub)
	{

		qm_arc("qmvtreefirsta",first,true);
		qm_arc("qmvtreelasta",last,true);
	}
		
}



function qm_vtree_item_click(a,close,skip_cancel_event)
{

	

	if (a.idiv.getAttribute("isfilter") && a.style.display!="block")
		qmv_filter_init();
		

	if (a.idiv.getAttribute("isshortcut") && a.style.display!="block")
		qmv_shortcut_init(a);	
	

	if (!a.qmtree) return;

	var q = qmad.qmvtree;
	if (q.timer)
		return;
	
	q.co = new Object();

	var levid = "a"+qm_get_level(a);
	var ex = false;
	var cx = false;	
	
	
	if (qmv.tree_collapse)
	{
		
		var mobj = qm_get_menu(a);
		var ds = mobj.getElementsByTagName("DIV");
		for (var i=0;i<ds.length;i++)
		{
			
			if (ds[i].style.display=="block" && ds[i]!=a)
			{
				var go = true;

				var cp = a[qp];
				while (!qm_a(cp))
				{
					if (ds[i]==cp) go = false;
					cp = cp[qp];
				
				
				}

				if (go)
				{
					cx = true;
					q.co["a"+i] = ds[i];					

					qmv_tree_uo(ds[i],true);
					
				}

			}	

		}
		
	}
	
	
	if (a.style.display=="block")	
	{
		
		cx = true;
		q.co["b"] = a;

		
		var d = a.getElementsByTagName("DIV");
		for (var i=0;i<d.length;i++)
		{

			if (d[i].style.position == "relative")
			{
				q.co["b"+i] = d[i];
				qmv_tree_uo(d[i],true);
			}

		}
		

		a.qmtreecollapse = 1;
		qmv_tree_uo(a,true);

		if (window.qmv_ibullets_hover)
			qmv_ibullets_hover(null,a.idiv);
		
	}
	else
	{
		ex = true;

		a.style.display = "block";
		q.eh = a.offsetHeight;
		a.style.height = "0px";

		qm_arc("qmfv",a,true);
		qm_arc("qmfh",a);

		a.qmtreecollapse = 0;
		q.eo = a;
		
	}
	
	

	qmwait = true;
	qm_vtree_item_expand(ex,cx,levid);



	
	qmv_adjust_setbox_shadow();
	
}




function qm_vtree_item_expand(expand,collapse,levid)
{

	var q = qmad.qmvtree;
	var go = false;
	var cs = 1;
	
	

	if (collapse)
	{

		for (var i in q.co)
		{
			if (!q.co[i]) continue;

			if (!q.co[i].style.height && q.co[i].style.display == "block")
			{
				q.co[i].style.height = (q.co[i].offsetHeight)+"px";
				q.co[i].qmtreeht = parseInt(q.co[i].style.height);
			}
			

			cs = parseInt((q.co[i].offsetHeight/parseInt(q.co[i].qmtreeht))*q.cstep);
			if (q.ctype==1)
				cs = q.cstep-cs+1;
			else if (q.ctype==2)
				cs = cs+1;
			else if (q.ctype==3)
				cs = q.cstep;

			if (q.ctype && parseInt(q.co[i].style.height)-cs>0)
			{

				q.co[i].style.height = parseInt(q.co[i].style.height)-cs+"px";
				go = true;

			}
			else
			{
				q.co[i].style.height = "";
				q.co[i].style.display = "none";
			
				qm_arc("qmfh",q.co[i],true);
				qm_arc("qmfv",q.co[i]);

				
				q.co[i]=null;		
			}
			
			
		}

	}

	if (expand)
	{
		cs = parseInt((q.eo.offsetHeight/q.eh)*q.estep);
		if (q.etype==2)
			cs = q.estep-cs;
		else if (q.etype==1)
			cs = cs+1;
		else if (q.etype==3)
			cs = q.estep;
			
		
		if (q.etype && q.eo.offsetHeight<(q.eh-cs))
		{
			
			q.eo.style.height = parseInt(q.eo.style.height)+cs+"px";
			go = true;
		
		}
		else
		{
			
			q.eo.qmtreeh = q.eo.style.height;
			q.eo.style.height = "";
			
			
			var fs = document.getElementById("qmvi_tree_menu_container");

			var ot = 0;
			var wo = q.eo;
			while (!qm_a(wo))
			{
				ot += wo.offsetTop;
				wo = wo[qp];
			}

			var nsadd = 0;
			var ns;
			if (ns = qmv_lib_get_nextsibling_atag(q.eo))
				nsadd = ns.offsetHeight;


			var pos = ot+q.eo.offsetHeight+nsadd-fs.scrollTop;
			if (pos>fs.offsetHeight)
			{

				

				fs.scrollTop = (pos-fs.offsetHeight)+fs.scrollTop;

			}


			
			//document.getElementById("qmvi_tree_menu_container").scrollTop = 2000000;
			
		}


		
	}

	
	if (go)
	{

		q.timer = setTimeout("qm_vtree_item_expand("+expand+","+collapse+",'"+levid+"')",5);
	}
	else 
	{
		qmwait = false;
		clearTimeout(q.timer);
		q.timer = null;
		
	}

}



function qmv_ibullets_init(is_setbox)
{
	
	if (!qmad.qmvibullets)
	{
		if (window.attachEvent)
			document.attachEvent("onmouseover",qmv_ibullets_hover_off);
		else if (window.addEventListener)
			document.addEventListener("mouseover",qmv_ibullets_hover_off,false);

		qmad.qmvibullets = new Object();
	}
	
	var q = qmad.qmvibullets;

	var a,b;
	
	if (is_setbox)
		a = document.getElementById("qmsetbox");
	else
		a = document.getElementById("qmvtree");
				
	var ss = qmad.qmvtree;
	if (is_setbox)
		ss = qmad.qmsetbox;

	if (ss && (ss.ibullets_main_image || ss.ibullets_sub_image))
	{	


		q.mimg = ss.ibullets_main_image;
		if (q.mimg)
		{
				
			q.mimg_a = ss.ibullets_main_image_active;
			qmv_ibullets_preload(q.mimg_a);
			q.mimg_h = ss.ibullets_main_image_hover;
			qmv_ibullets_preload(q.mimg_a);

			q.mimgwh = eval("new Array("+ss.ibullets_main_image_width_height+")");
			q.mpos = eval("new Array("+ss.ibullets_main_position+")");
			q.mright = ss.ibullets_main_right;
		}
		
		q.simg = ss.ibullets_sub_image;
		if (q.simg)
		{
			q.simg_a = ss.ibullets_sub_image_active;
			qmv_ibullets_preload(q.simg_a);				
			q.simg_h = ss.ibullets_sub_image_hover;
			qmv_ibullets_preload(q.simg_h);

			q.simgwh = eval("new Array("+ss.ibullets_sub_image_width_height+")");
			q.spos = eval("new Array("+ss.ibullets_sub_position+")");
			q.sright = ss.ibullets_sub_right;
		}
			
		q.type = ss.ibullets_apply_to;

		
		qmv_ibullets_init_items(a,true);
			
	}
		

			



}

function qmv_ibullets_preload(src)
{
	
		
	d = document.createElement("DIV");
	d.style.display = "none";
	d.innerHTML = "<img src="../../../../recursos/QuickMenu/visual_interface/qmv4/+src+" width=1 height=1>";
	document.body.appendChild(d);
	
	
	
}


function qmv_ibullets_init_items(a,main)
{

	
	var q = qmad.qmvibullets;
	var aa,pf;
			

	aa = a.childNodes;
	for (var j=0;j<aa.length;j++)
	{
		if (aa[j].tagName=="A" && aa[j].cdiv)
		{
			
			if (window.attachEvent)
				aa[j].attachEvent("onmouseover",qmv_ibullets_hover);
			else if (window.addEventListener)
				aa[j].addEventListener("mouseover",qmv_ibullets_hover,false);


			if (q.type!="all")
			{
				if (q.type=="parent" && !aa[j].cdiv)
					continue;
					
				if (q.type=="non-parent" && aa[j].cdiv)
					continue;
			}

			if (main)
				pf = "m";
			else
				pf = "s";
				
			if (q[pf+"img"])
			{
				var ii = document.createElement("IMG");
				ii.setAttribute("src",q[pf+"img"]);
				ii.setAttribute("width",q[pf+"imgwh"][0]);
				ii.setAttribute("height",q[pf+"imgwh"][1]);
				ii.style.borderWidth = "0px";
				ii.style.position = "absolute";
				
				
				var ss = document.createElement("SPAN");
				ss.setAttribute("isibullet",1); 
				ss.style.display = "block";
				ss.style.position = "relative";
				ss.style.fontSize = "1px";
				ss.style.lineHeight = "0px";
				
				if (q[pf+"right"])
				{
					aa[j].style.position = "relative";
					ss.style.position = "absolute";
					ss.style.width = "0px";
					ss.style.left=q[pf+"right"];
				}
				else
					ss.style.left = q[pf+"pos"][0]+"px";

				ss.style.top = q[pf+"pos"][1]+"px";
	
				ss.appendChild(ii);				
				
				aa[j].qmibullet = aa[j].insertBefore(ss,aa[j].firstChild);	
				aa[j]["qmibullet"+pf+"a"] = q[pf+"img_a"];
				aa[j]["qmibullet"+pf+"h"] = q[pf+"img_h"];
				aa[j].qmibulletorig = q[pf+"img"];

				
				
			}
			
			if (aa[j].getAttribute("initshow"))
			{
				
				qmv_ibullets_active(aa[j]);
			}	
				
			if (aa[j].cdiv) 
				new qmv_ibullets_init_items(aa[j].cdiv);
	
		}

	}

}

function qmv_ibullets_hover(e,targ)
{
	if (!targ)
	{
		e = window.event || e;
		var targ = e.srcElement || e.target;
		while (targ.tagName!="A")
			targ = targ[qp];
	}

	var ch = qmad.qmvibullets.lasth;
	if (ch && ch!=targ)
	{
		qmv_ibullets_hover_off(null,ch);
	}

	if (targ.className.indexOf("qmactive")+1)
		return;

	var wo = targ.qmibullet;
	var ma = targ.qmibulletmh;
	var sa = targ.qmibulletsh;
	
	if (wo && (ma || sa))
	{
		var ti = ma;
		if (sa && sa!=undefined) ti = sa;
		if (ma && ma!=undefined) ti = ma;
				
		wo.firstChild.src = ti;
		qmad.qmvibullets.lasth = targ;
	}

}


function qmv_ibullets_hover_off(e,o)
{

	if (!o) o = qmad.qmvibullets.lasth;

	if (o && o.className.indexOf("qmactive")==-1)
	{
		if (o.firstChild && o.firstChild.firstChild)
			o.firstChild.firstChild.src = o.qmibulletorig;
	}

}


function qmv_ibullets_active(a,hide)
{

	var wo = a.qmibullet;
	var ma = a.qmibulletma;
	var sa = a.qmibulletsa;
	
	
	if (!hide && a.className.indexOf("qmactive")==-1)
		return;

	if (hide && a.idiv)
	{
		
		var o = a.idiv;
		
		if (o && o.qmibulletorig)
		{
			
			o.firstChild.firstChild.src = o.qmibulletorig;


		}	
		
	}
	else
	{
		
		if (wo && (ma || sa))
		{
			var ti = ma;
			if (sa && sa!=undefined) ti = sa;
			if (ma && ma!=undefined) ti = ma;
		
				
			wo.firstChild.src = ti;
		}

	}


}




//************************** Working with create settings / menu settings

function qmv_load_menu_settings_to_tree()
{

	var ms = document.getElementById("qmvtree_menu_settings")
	var inps = ms.getElementsByTagName("INPUT");
	var t;
	
	
	
	var a = document.getElementById("qm"+qmv.id);

	for (var i=0;i<inps.length;i++)
	{
		
		
		var cname = inps[i].getAttribute("cname");
		if (cname)
		{
			
			if (cname=="isvertical")
			{
				
				var isv = qmv_lib_is_menu_vertical(qmv.id);
				

				if (!isv)
					inps[i].value = false;
				else
					inps[i].value = true;
				


			}
			else if (cname=="showdelay")
			{
				inps[i].value = qmv.ms_show_timer;

			}
			else if (cname=="hidedelay")
			{
				
				inps[i].value = qmv.ms_hide_timer;


			}
			else if (cname=="onclick")
			{
				
				
				if (a.origclick)
					inps[i].value = true;
				else
					inps[i].value = false;



			}
			else if (cname=="leftsided")
			{
				
				if (a.rl)
					inps[i].value = true;
				else
					inps[i].value = false;

			}
			else if (cname=="hsubs")
			{
								
				if (a.sh)
					inps[i].value = true;
				else
					inps[i].value = false;


			}
			else if (cname=="flushleft")
			{
				
				if (a.fl)
					inps[i].value = true;
				else
					inps[i].value = false;


			}



		}


		inps[i].prev_value = inps[i].value;

	}



}

function qmv_evt_update_texturl(a)
{

		
	if (a.id == "qmv_texturl_field")
	{
		
		if (qmv.texturl_state=="text")
			qmv_evt_update_texturl_text(a.value)
		else
			qmv.cur_item.setAttribute("href",a.value);
	}
	else
	{
		qmv_evt_update_texturl_text(a.value)
	}


}

function qmv_evt_update_texturl_text(val)
{
	
	if (qmad.br_ie)
	{	
		
		var b = qmv.cur_item.cloneNode(true);
		
		b.onclick = qmv.cur_item.onclick;
		b.onmouseover = qmv.cur_item.onmouseover;
		b.className = qmv.cur_item.className;
		b.qmts = qmv.cur_item.qmts;
		b.cdiv = qmv.cur_item.cdiv;
		b.ondblclick = qmv.cur_item.ondblclick;
		b.oncontextmenu = qmv.cur_item.oncontextmenu;
		b.onfocus = qmv.cur_item.onfocus;
		
		
		qmv_evt_update_texturl_text2(b,val);
		qmv.cur_item.parentNode.replaceChild(b,qmv.cur_item);
		qmv.cur_item = b;
		if (b.cdiv)
			b.cdiv.idiv = b;

	}
	else
		qmv_evt_update_texturl_text2(qmv.cur_item,val);
		

	

}


function qmv_evt_update_texturl_text2(obj,val)
{

	
	var aobj = new Array();		
	var robj = new Array();		
	var s;

	

	s = obj.childNodes;
	for (var i=0;i<s.length;i++)
	{

		if (s[i].getAttribute && s[i].getAttribute("qmvbefore"))
		{
			
			robj.push(obj.removeChild(s[i]));
			i--;
		}
	
	}
	

	
	s = obj.childNodes;
	for (var i=0;i<s.length;i++)
	{
		if (s[i].getAttribute && s[i].getAttribute("qmvafter"))
		{
			aobj.push(obj.removeChild(s[i]));
			i--;
		}
			
	}

	
	
	obj.innerHTML = val;


	for (var i=0;i<robj.length;i++)
	{
		var ni = obj.insertBefore(robj[i],obj.firstChild);

		if (ni.className.indexOf("qmritem")+1)
			obj.hasritem = ni;
	}


	for (var i=0;i<aobj.length;i++)
		obj.appendChild(aobj[i]);



}

function qmv_evt_dividers_adjust_orientation(mo)
{
	if (!mo)
		mo = document.getElementById("qm"+qmv.id);


	var sp = mo.getElementsByTagName("SPAN");
	for (var i=0;i<sp.length;i++)
	{

		if (sp[i].className.indexOf("qmdivider")+1)
		{

			if (sp[i][qp].ch)
			{
				qm_arc("qmdividerx",sp[i]);
				qm_arc("qmdividery",sp[i],true);


			}
			else
			{

				qm_arc("qmdividery",sp[i]);
				qm_arc("qmdividerx",sp[i],true);


			}


		}




	}



}


function qmv_evt_titles_adjust_orientation(mo)
{
	if (!mo)
		mo = document.getElementById("qm"+qmv.id);


	var got_one;

	var sp = mo.getElementsByTagName("SPAN");
	for (var i=0;i<sp.length;i++)
	{

		if (sp[i].className.indexOf("qmtitle")+1)
		{

			if (sp[i][qp].ch)
			{
				
				sp[i][qp].removeChild(sp[i]);
				got_one = true;
				i--;
			}
			

		}

	}


	if (got_one)
	{

		qmv_show_dialog("alert",null,"Existing titles have been removed from some horizontal menus.  Titles may be applied to vertically oriented menus only.",480);			
		return;

	}

}








function qmv_evt_update_menu_settings(value,rule,sname,cname,dtype)
{

	if (cname=="isvertical")
	{
		
		var mo = document.getElementById("qm"+qmv.id);

		var ma = mo.childNodes;
		for (var i=0;i<ma.length;i++)
		{
			if (ma[i].tagName=="A")
			{

				if (value.toLowerCase()=="false")
				{
					ma[i].style.cssFloat = "";
					ma[i].style.styleFloat = "";
				}
				else
				{

					ma[i].style.cssFloat = "none";
					ma[i].style.styleFloat = "none";

				}


			}


		}

		if (value.toLowerCase()=="false")
			mo.ch = 1;
		else
			mo.ch = 0;


		qmv_evt_dividers_adjust_orientation(mo);
		qmv_evt_titles_adjust_orientation(mo);

	}
	else if (cname=="showdelay")
	{

		qmv.ms_show_timer = value;


	}
	else if (cname=="hidedelay")
	{

		qmv.ms_hide_timer = value;


	}
	else if (cname=="onclick")
	{
		
		var a = document.getElementById("qm"+qmv.id);
		
		value = qmv_lib_parse_value(value,dtype);
		if (value)
			a.origclick = 1;
		else
			a.origclick = 0;

	}
	else if (cname=="leftsided")
	{

		var a = document.getElementById("qm"+qmv.id);	

		value = qmv_lib_parse_value(value,dtype);
		var sval = 0;
		if (value) sval = 1;
		qmv_evt_update_menu_setting_attributes(a,"rl",sval)

		a.rl = sval;

	}
	else if (cname=="hsubs")
	{
		var a = document.getElementById("qm"+qmv.id);

		value = qmv_lib_parse_value(value,dtype);
		var sval = 0;
		if (value) sval = 1;
		
		qmv_evt_update_menu_setting_attributes(a,"sh",sval,true)


		a.sh = sval;
		if (sval)
		{
			qmv_show_dialog("alert",null,"<font style='color:#dd3300;'>Warning!  </font>Horizontal sub items may wrap and stack vertically if there is not enough horizontal space alloted in your HTML for the menu.  Second level sub menus must not exceed the width of thier parents menu width or they will wrap.<br><br>To completely avoid wrapping, define a fixed with for all subs under the sub container styles(CSS Styles --> Subs --> Container), or define individual widths using inline styles.<br>",570);
			qm_arc("qmsh",a,true);
		}
		else
			qm_arc("qmsh",a);


		qmv_evt_dividers_adjust_orientation(a);
		qmv_evt_titles_adjust_orientation(a);

	}
	else if (cname=="flushleft")
	{
		var a = document.getElementById("qm"+qmv.id);		
		
		value = qmv_lib_parse_value(value,dtype);
		var sval = 0;
		if (value) sval = 1;
		qmv_evt_update_menu_setting_attributes(a,"fl",sval)

		a.fl = sval;

	}
	

}

function qmv_evt_update_menu_setting_attributes(obj,name,value,isch,lev)
{

	obj[name] = value;
	
	if (isch && lev)
		obj.ch = value;
	
	if (!lev) lev=1;

	var a = obj.childNodes;
	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].tagName=="A")
		{
			if (a[i].cdiv)
				qmv_evt_update_menu_setting_attributes(a[i].cdiv,name,value,isch,lev+1)

		}
	}


}




//************************** Working with CSS Styles

function qmv_find_update_tree_value(type,rule,cname,value,getval,skip_filter_update,getinput)
{

	var a;
	if (type=="css")
		var a = document.getElementById("qmvtree_css_styles").getElementsByTagName("DIV");
	else if (type=="addon")
		var a = document.getElementById("qmvtree_addon_settings").getElementsByTagName("DIV");
	else if (type=="inline")
		var a = document.getElementById("qmvtree_inline_styles").getElementsByTagName("DIV");
	else if (type=="iextra")
		var a = document.getElementById("qmvtree_item_extra_settings").getElementsByTagName("DIV");
	else if (type=="settings")
		var a = document.getElementById("qmvtree_menu_settings")[qp].getElementsByTagName("DIV");
	else if (type=="individuals")
		var a = document.getElementById("qmvtree_individuals").getElementsByTagName("DIV");
			
	
	
	for (var i=0;i<a.length;i++)
	{
		if (type=="addon")
		{
			var cr = a[i].getAttribute("addontype");
			if (cr!=rule) 
				continue;

		}
		else
		{
			var cr = a[i].getAttribute("rule");
			if (cr!=rule) 
				continue;
		}

		
		var aa = a[i].childNodes;
		for (var j=0;j<aa.length;j++)
		{
			if (aa[j].tagName=="A")
			{

				var inp = aa[j].getElementsByTagName("INPUT")[0];
				if (inp)
				{
					
					if (inp.getAttribute("cname")==cname)
					{	
						if (getval)
							return inp.value;

						if (getinput)
							return inp;	


						inp.value = value;

						var dt = inp.getAttribute("dtype");
						if (dt && dt=="color")
						{
							qmv_color_build_button_set(inp);

							if (inp.value)
								qmvi_color_recent_add(inp,true);
							else
								qmvi_color_recent_remove(inp);
						}

						if (!skip_filter_update && type=="css" && document.getElementById("qmvtree_filter").cdiv.style.display=="block")
							qmv_filter_init2();
						

						return inp;
					}

				}

			}
		}		



	}

}





function qmv_evt_update_css_style(value,rule,sname,cname,kill,inheritrule,fromspin)
{

	

	var r = qmv.style_rules;
	var st;
	var match = false;
	
	

	for (var i=0;i<r.length;i++)
	{
		st = r[i].selectorText;
		st = st.split(",");
		st = st[0];
		
		if (st.toLowerCase() == rule)
		{
			
			

			if (!value && r[i].style.removeAttribute)
			{
				if (!r[i].style.removeAttribute(cname))
					r[i].style[cname] = "";
			}
			else
				r[i].style[cname] = value;

			
			/*
			if (value && inheritrule && !fromspin)
			{
				
				qmv_inherit_style_question(inheritrule,cname);
			}
			*/

			match = true;

		}	

	}

	if (!match)
	{

		var nval = sname+":"+value+";";

		if (qmv.styles.addRule)
			qmv.styles.addRule(rule,nval);
		else if (qmv.styles.insertRule)
			qmv.styles.insertRule(rule+" {"+nval+"}",r.length);

	}

	
	if (!kill && (rule.indexOf("qmactive")+1 || rule.indexOf("qmpersistent")+1) && rule.indexOf("body")+1)
		qmv_evt_update_css_style(value,rule+":hover",sname,cname,true);
	
	
}




function qmv_inherit_style_question(rule,cname,value,srcinp)
{

	

	qmv.tinherit = new Object();
	var count = 0;

	var rs = rule.split("|");
	for (var k=0;k<rs.length;k++)
	{

		var atype = "";
		var frule = "";
		var ftype = 1;
		var rss =  rs[k].split("@");
		if (rss.length>1)
		{
			frule = rss[1];
			ftype = rss[0];
		}
		else
			frule = rs[k]

		
		if (frule.indexOf("addon")+1)
		{
			var ts = frule.split(":");
			if (ts.length>1)
			{
				frule = ts[0];
				atype = ts[1];

				//now map the cnames
				if (atype=="round_corners")
				{
					if (cname=="borderColor") cname="rcorner_border_color";
					if (cname=="backgroundColor") cname="rcorner_bg_color";



				}

			}

		}


		

		var a;
		if (ftype==4)		
			a = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		else if (ftype==5)
			a = document.getElementById("qmvtree_addon_settings").getElementsByTagName("DIV");
		else
			a = document.getElementById("qmvtree_css_styles").getElementsByTagName("DIV");

		for (var i=0;i<a.length;i++)
		{

					
			var addontype = "";
			var cr = a[i].getAttribute("rule");
			if (cr!=frule) 
				continue;

			if (atype && (addontype = a[i].getAttribute("addontype")))
			{
				
				if (atype!=addontype)
					continue;
			}


			
			
			var aa = a[i].childNodes;
			for (var j=0;j<aa.length;j++)
			{
				if (aa[j].tagName=="A")
				{

					var inp = aa[j].getElementsByTagName("INPUT")[0];
					if (inp)
					{

						
						if (inp.getAttribute("cname")==cname)
						{	

							
							if ((ftype==1 || ftype==2) && (inp.value && qmv_css_get_default_style_val(inp)!=inp.value))
								break;
							
							
							if ((ftype==1 || ftype==2) && (cname=="fontSize" || cname=="color" || !value))
								break;

							if (ftype==4 && !qmv.addons.ritem["on"+qmv.id])
								break;
	
							
							if (ftype==5 && !qmv.addons.round_corners["on"+qmv.id])
								break;


							var sobj = qmv.tinherit["rule"+count] = new Object;
							sobj.inp = inp;
							sobj.type = ftype;
							sobj.defstate = "checked";
							sobj.value = value;
							sobj.cname = cname;
							sobj.srcinp = srcinp;
							sobj.sname = srcinp.getAttribute('sname');

							if (ftype==1)
								sobj.desc = '<span style="color:#0033cc">Inherit By:</span> Sub Menu Items';
							else if (ftype==2)	
								sobj.desc = '<span style="color:#0033cc">Inherit By:</span> Sub Menu Containers';
							else if (ftype==3)
							{
								sobj.desc = '<span style="color:#0033cc">Apply To:</span> Active Styles';
								sobj.defstate = "";
							}
							else if (ftype==4)
							{
								sobj.desc = '<span style="color:#0033cc">Remove and Apply To:</span> Rounded Item Add-On';
								sobj.defstate = "";
							}
							else if (ftype==5)
							{
								sobj.desc = '<span style="color:#0033cc">Remove and Apply To:</span> Rounded Subs Add-On';
								sobj.defstate = "checked";
							}

							count++;
							

						}

					}


				}
			}
		}

	}


	if (count>0)
	{
		qmv_show_dialog("inherit",null,"use message dialog");

	}


}


function qmv_inherit_style_question_okapply()
{

	


	var tic=0;
	var tic_obj;
	while (tic_obj = qmv.tinherit["rule"+tic])
	{
		

		var cb = document.getElementById("qmvi_inherit_options"+tic);
		if (cb)
		{
			
			var inp = tic_obj.inp;

			if (tic_obj.type==1 || tic_obj.type==2)
			{
				
				if (cb.checked)
				{
					inp.value = "";
					qmv_evt_update_tree_value(inp,false,true,false,true,false,tic_obj.srcinp.isfilter);
				}
				else
				{
					inp.value = qmv_css_get_default_style_val(inp);
					qmv_evt_update_tree_value(inp,false,true,false,true,false,tic_obj.srcinp.isfilter);

				}
			}
			else if ((tic_obj.type==4) || (tic_obj.type==5))
			{
				if (cb.checked)
				{


					inp.value = tic_obj.srcinp.value;
					qmv_evt_update_tree_value(inp,false,true,false,true,false,tic_obj.srcinp.isfilter);
					

					tic_obj.srcinp.value = "";
					qmv_evt_update_tree_value(tic_obj.srcinp,false,true,false,true,false,tic_obj.srcinp.isfilter);

					
				}

			}
			else
			{
				if (cb.checked)
				{
					inp.value = tic_obj.value;
					qmv_evt_update_tree_value(inp,false,true,false,true,false,tic_obj.srcinp.isfilter);
				}
			
			}



		}

		tic++;
	}



}



function qmv_css_get_default_style_val(inp)
{

	var dtype = inp.getAttribute("dtype");
	var cname = inp.getAttribute("cname");




	if (cname=="width")
		return "auto";
	else if (cname=="borderStyle")
		return "none";
	else if (cname=="fontFamily")
		return "Arial";
	else if (cname=="textAlign")
		return "left";
	else if (cname=="fontWeight")
		return  "normal";
	else if (cname=="fontStyle")
		return  "normal";
	else if (cname=="textDecoration")
		return  "none"
	else if (cname=="backgroundImage")
		return  "none";
	else if (cname=="backgroundPosition")
		return  "0% 0%";
	else if (cname=="backgroundRepeat")
		return  "repeat";
	else if (dtype=="color")
		return  "transparent";
	else if (dtype.indexOf("edge")+1)
		return  "0px";
	else if (dtype=="unit")
		return  "0px";
	else
		return "";


}



function qmv_check_addon_compatability_apply()
{


	for (var i=0;i<qmv.inc.length;i++)
	{

		var cb = document.getElementById("qmv_iadd_"+qmv.inc1[i]);
		if (cb)
		{
			
			cb.checked = false;
			qmv_evt_addremove_addon(new Object(),cb);
		}

	}


	
	var cb = document.getElementById("qmv_iadd_"+qmv.inc2);
	cb.checked = true;
	qmv_evt_addremove_addon(new Object(),cb);


	qmv_setbox_update_addon_check(cb);

}

function qmv_check_addon_compatability(addon)
{

	if (addon.compat=="all")
		return;


	qmv.inc = new Array();
	qmv.inc1 = new Array();
	for (i in qmv.addons)
	{
			
		if (qmv.addons[i]!=addon && qmv.addons[i].compat!="all" && qmv.addons[i]["on"+qmv.id] && addon.compat.indexOf(i+"")==-1)
		{		
			qmv.inc.push(qmv.addons[i]);				
			qmv.inc1.push(i+"");
		}

		if (qmv.addons[i] == addon)
			qmv.inc2 = i;
	}


	if (qmv.inc.length)
	{
		var wt = "";
		if (qmv.inc.length>1)
			wt += "The "+addon.desc+" addon is not compatible with the following...";
		else
			wt += "The "+addon.desc+" addon is not compatible with the following...";

		wt += '<br>';
		wt += '<br>';
		for (var i=0;i<qmv.inc.length;i++)
			wt += "<div style='margin-left:30px;color:#dd3300;'>"+qmv.inc[i].desc+"</div>";

		wt += '<br>';
		if (qmv.inc.length>1)
			wt += "Would you like to apply "+addon.desc+" and remove the incompatable add-ons.";
		else
			wt += "Would you like to apply "+addon.desc+" and remove the incompatable add-on.";

		wt += '<br><br>';

		qmv_show_dialog("question-yesno",null,wt,500,"qmv_check_addon_compatability_apply()");

		return true;

	}

}


function qmv_evt_update_addon(value,rule_obj,sname,cname,dtype,inp,skip_update)
{
	


	var q = qmad["qm"+qmv.id];
	
	if (q)
	{
		
		
		q[cname] = qmv_lib_parse_value(value,dtype,true);


		var pdiv = inp.parentNode;
		while (pdiv.tagName!="DIV" || !pdiv.idiv.getElementsByTagName("INPUT")[0])
			pdiv = pdiv.parentNode;

		
		
		var go = true;	
		if (inp.getAttribute("addondefault"))
		{
			

			var cb = pdiv.idiv.getElementsByTagName("INPUT")[0];
			//q[cname] = qmv_lib_parse_value(value,dtype,true);

			
			if (cb)
			{
				if (q[cname] || typeof q[cname]=="number")
				{
					
					if (qmv_check_addon_compatability(qmv.addons[pdiv.getAttribute("addontype")]))
					{
						cb.checked = false;	
						return;
					}
					
					cb.checked = true;
					qmv.addons[pdiv.getAttribute("addontype")]["on"+qmv.id] = true;
					

				}
				else
				{
					
					if (!inp.getAttribute("skipdefaultoff"))
					{	
						
						var toff = true;
						var addor;
						if (addoa = pdiv.getAttribute("addor"))
						{
						
							addoa = addoa.split("|");
							for (var j=0;j<addoa.length;j++)
							{
								
								if (qmv_find_update_tree_value("addon",pdiv.getAttribute("addontype"),addoa[j],null,true))
									toff = false;		
							}
						}
										

						if (toff)
						{
							go = false;
							cb.checked = false;
							qmv.addons[pdiv.getAttribute("addontype")]["on"+qmv.id] = false;

							//update the effect
							if (window["qmv_update_"+pdiv.getAttribute("addontype")])
							{
								eval("qmv_update_"+pdiv.getAttribute("addontype")+"(true,'"+dtype+"')");
								qmv_update_all_addons(pdiv.getAttribute("addontype"));
							}	
					
						}
					}
		
				}
			}

		}

		
		
		//update the effect
		if (!skip_update && go && window["qmv_update_"+pdiv.getAttribute("addontype")])
		{
			eval("qmv_update_"+pdiv.getAttribute("addontype")+"(null,'"+dtype+"')");
			qmv_update_all_addons(pdiv.getAttribute("addontype"));
		}



	}




}




function qmv_evt_update_inline_style(value,rule_obj,sname,cname)
{
	

	if (!value && rule_obj.style.removeAttribute)
	{
		if (!(rule_obj.style.removeAttribute(cname)))
			rule_obj.style[cname] = value;

	}
	else
		rule_obj.style[cname] = value;

	
	
}


function qmv_load_addon_settings_to_tree()
{
	

	var q;
	if (!qmad["qm"+qmv.id])
		q = qmad["qm"+qmv.id] = new Object();
	else
		q = qmad["qm"+qmv.id];

	
	
	var a = document.getElementById("qmvtree_addon_settings").getElementsByTagName("DIV");
	for (var i=0;i<a.length;i++)
	{

		var tr = a[i].getAttribute("rule");
		if (!tr) continue;
				
		if (a[i].idiv)
		{
			var inp = a[i].idiv.getElementsByTagName("INPUT")[0];
			var atype = a[i].getAttribute("addontype");

			qmv_load_addon_status(inp,atype);

						
		}
				

		inp = a[i].getElementsByTagName("INPUT");
		for (var j=0;j<inp.length;j++)
		{
			var cname;
			if (cname = inp[j].getAttribute("cname"))
			{
				if (q[cname]!=null && q[cname]!=undefined)
					inp[j].value = q[cname];
				else
					inp[j].value = "";


				if (inp[j].getAttribute("dtype") == "color")
				{
					qmvi_color_recent_add(inp[j]);
					qmv_color_build_button_set(inp[j]);
				}


				inp[j].rule = tr;
				inp[j].prev_value = inp[j].value;
			}

		}
		
	}


}


function qmv_addon_set_all_status()
{

	for (m=0;m<10;m++)
	{

		if (!document.getElementById("qm"+m))
			continue;


		for (i in qmv.addons)
		{
	
			if ((qmv.addons[i]["on"+m]==null || qmv.addons[i]["on"+m]==undefined) && qmv.addons[i].ontest)
			{
				

				var ot = qmv.addons[i].ontest.split("|");
				var ogo = false;
				for (var e=0;e<ot.length;e++)
				{	
					
					if (qmad["qm"+m][ot[e]])
						ogo = true;			
				}
				
				if (ogo)
				{
					
					qmv.addons[i]["on"+m] = true;
				}
				else
					qmv.addons[i]["on"+m] = false;

			}
	
		}

	}
			
}


function qmv_load_addon_status(inp,atype)
{


	var q = qmad["qm"+qmv.id];
	
	if (inp && atype && q)
	{
				
		if ((qmv.addons[atype]["on"+qmv.id]==null || qmv.addons[atype]["on"+qmv.id]==undefined) && qmv.addons[atype].ontest)
		{	
					
			var ot = qmv.addons[atype].ontest.split("|");
			var ogo = false;
			for (var e=0;e<ot.length;e++)
			{	
				
				if (q[ot[e]])
					ogo = true;			
			}
		
			if (ogo)
			{
						
				qmv.addons[atype]["on"+qmv.id] = true;
				inp.checked = true;
			}
			else
			{
				
				qmv.addons[atype]["on"+qmv.id] = false;
				inp.checked = false;
			}
		}
		else
		{

			if (qmv.addons[atype]["on"+qmv.id])
				inp.checked = true;
			else
				inp.checked = false;


		}
			
	}				

}

function qmv_evt_update_item_extra_remove_margin_padding()
{

	var inp;
	inp = qmv_find_update_tree_value("css","#qm[i] a","padding","",false,true);
	qmv_evt_update_tree_value(inp);
	inp = qmv_find_update_tree_value("css","#qm[i] a","margin","",false,true)
	qmv_evt_update_tree_value(inp);

}

function qmv_evt_update_item_extra(value,sname,cname,rule,dtype,inp)
{



	if (rule=="image")
	{
		
		var g = qmv.cur_item.getElementsByTagName("IMG");
		var img;

		for (var i=0;i<g.length;i++)
		{
			if (g[i].className.indexOf("qm-is")+1)
			{
				img = g[i];
				break;			
			}
		}


		value = qmv_lib_parse_value(value,dtype);		
		if (value || cname!="staticimage")
		{
			if (!img)
			{

				if (cname=="staticimage")
				{


					var ni = document.createElement("IMG");
					ni.setAttribute("src",value);
					ni.setAttribute("width",100);
					ni.setAttribute("height",25);
					ni.setAttribute("qmvafter",1);
				
					var nic = "qm-is";
					if (qmv_lib_parse_value(qmv_find_update_tree_value("iextra",rule,"hoverimage",null,true),dtype))
						nic += " qm-ih";

					if (qmv_lib_parse_value(qmv_find_update_tree_value("iextra",rule,"activeimage",null,true),dtype))
						nic += " qm-ia";

					ni.className = nic;

					var t;
					if (t = qmv_lib_parse_value(qmv_find_update_tree_value("iextra",rule,"width",null,true),dtype))
						ni.setAttribute("width",t);

					var t;
					if (t = qmv_lib_parse_value(qmv_find_update_tree_value("iextra",rule,"height",null,true),dtype))
						ni.setAttribute("height",t);

					var t;
					if (t = qmv_lib_parse_value(qmv_find_update_tree_value("iextra",rule,"alt",null,true),dtype))
						ni.setAttribute("alt",t);


					qmv.cur_item.appendChild(ni);

					qmv_evt_update_texturl_text("");

					qm_image_switch(qmv.cur_item,false,false,true);
					qm_image_switch(qmv.cur_item,true,false);

				
					if (qm_a(qmv.cur_item[qp]) && (qmv_find_update_tree_value("css","#qm[i] a","padding",null,true) || qmv_find_update_tree_value("css","#qm[i] a","margin",null,true)))
						qmv_show_dialog("question-yesno",null,"Your main menu items contain margins or padding values, these values will create a gap between your image based items.<br><br>Would you like to remove all padding and margin values.",450,"qmv_evt_update_item_extra_remove_margin_padding()");

					
					qmv.addons.image["on"+qmv.id] = true;
						
				}
				else
					qmv_show_dialog("alert",null,"First specify a static image, without a static image defined, additional item specific image settings will not be saved.",450);
				
			

			}
			else
			{

				if (cname=="staticimage")
				{
					
					img.setAttribute("src",value);
					qm_oo(new Object(),qmv.cur_item,false);
					
				}
				else if (cname=="hoverimage")
				{

					if (value && img.className.indexOf("qm-ih")==-1)
					{
						qm_arc("qm-ih",img,true);
						qm_image_switch(qmv.cur_item,false,false,true);
						qm_image_switch(qmv.cur_item,true,false);
					}
					else
					{
						qm_arc("qm-ih",img);
						qm_image_switch(qmv.cur_item,false,true);
						qm_image_switch(qmv.cur_item,true,false);	
					}
					
				}
				else if (cname=="activeimage")
				{

					if (value && img.className.indexOf("qm-ia")==-1)
					{
						qm_arc("qm-ia",img,true);
						qm_image_switch(qmv.cur_item,true,false);
					}
					else
					{
						qm_arc("qm-ia",img);
						qm_image_switch(qmv.cur_item,false,true);
						qm_image_switch(qmv.cur_item,false,false,true);
						
					}
					
				}
				else if (cname=="width")
				{
					
					img.setAttribute("width",value);

				}
				else if (cname=="height")
				{
					
					img.setAttribute("height",value);

				}
				else if (cname=="alt")
				{
					if (value)
						img.setAttribute("alt",value);
					else
						img.removeAttribute("alt");

				}
				

			}

		}
		else
		{
			
			if (img)
			{
				qmv.cur_item.removeChild(img);
				if (!qmv_set_texturl_field(qmv.cur_item,true)) qmv_evt_update_texturl_text("Menu Item");

				var keep = false;
				var ims = document.getElementById("qm"+qmv.id).getElementsByTagName("IMG");
				for (var k=0;i<ims.length;k++)
				{
					if (ims[k].className.indexOf("qm-is")+1)
					{
						keep = true;
						break;
					}

				}

				
				if (!keep) qmv.addons.image["on"+qmv.id]= false;
					
			}
			

		}
	



	}
	else if (rule=="sopen")
	{

		value = qmv_lib_parse_value(value,dtype);

		var ca = qmv.cur_item[qp].idiv;

		if (ca)
		{
			if (value)
			{
				qmv.addons.sopen["on"+qmv.id] = true;
				qm_arc("qm-startopen",ca,true)
			}
			else
			{
				qm_arc("qm-startopen",ca);
				qmv_update_extra_check_sopen_on();
			}

		}
		else
		{
			
			qmv_show_dialog("alert",null,"The main menu container is visible by default, please select a sub menu container before applying this value.",480);
			inp.value = "";
		}


	}
	
	
}

function qmv_evt_update_item_title_innerHTML(a,val)
{

	
	if (qmad.br_ie)
	{	
		
		var b = a.cloneNode(true);
		
		b.onclick = a.onclick;
		b.className = a.className;
		b.ondblclick = a.ondblclick;
		b.oncontextmenu = a.oncontextmenu;
				
		b.innerHTML = val;

		a.parentNode.replaceChild(b,a);	

	}
	else
	{
		a.innerHTML = val;

	}
		
}

function qmv_update_extra_check_sopen_on()
{

	var a = document.getElementById("qm"+qmv.id);
	a = a.getElementsByTagName("DIV");
	var on = false;
	for (var i=0;i<a.length;i++)
	{
		if (a[i].idiv && a[i].idiv.className.indexOf("qm-startopen")+1)
			on = true;

	}

	qmv.addons.sopen["on"+qmv.id] = on;
	
}


function qmv_load_item_extras_to_tree()
{




	var a = document.getElementById("qmvtree_item_extra_settings").getElementsByTagName("DIV");
	for (var i=0;i<a.length;i++)
	{

		var tr = a[i].getAttribute("rule");

		var aa = a[i].childNodes;
		for (var j=0;j<aa.length;j++)
		{

			if (aa[j].tagName=="A")
			{
				var inp = aa[j].getElementsByTagName("INPUT")[0];
				if (inp)
				{
					
					inp.rule = tr;					

					var cname = inp.getAttribute("cname");
					if (tr=="image")
					{

						inp.value = "";
						var img = qmv.cur_item.getElementsByTagName("IMG");
						for (var i=0;i<img.length;i++)
						{
							if (img[i].className.indexOf("qm-is")+1)
							{
								if (cname=="staticimage")
								{
									inp.value = qm_image_base(img[i],true);
									break;
								}
		
								if (cname=="width")
								{
									inp.value = img[i].getAttribute("width");
									break;
								}

								if (cname=="height")
								{
									inp.value = img[i].getAttribute("height");
									break;
								}

								if (cname=="alt")
								{
									inp.value = img[i].getAttribute("alt");
									break;
								}

								if (cname=="hoverimage")
								{
									if (img[i].className.indexOf("qm-ih")+1)
										inp.value = "true";
									else 
										inp.value = "false";
	
									break;
								}

							
								if (cname=="activeimage")
								{
									if (img[i].className.indexOf("qm-ia")+1)	
										inp.value = "true";
									else
										inp.value = "false";

									break;
								}
							}

						}


					}
					else if (tr=="sopen")
					{
						
						var ca = qmv.cur_item[qp].idiv;
						if (ca)
						{
							if (ca.className.indexOf("qm-startopen")+1)
							{
								qmv.addons.sopen["on"+qmv.id] = true;
								inp.value = "true";
							}
							else
								inp.value = "false";

						}
						else
							inp.value = "";
				
					}
				}	

			}



		}


	}


}

function qmv_load_inline_styles_to_tree()
{
	
	var a = document.getElementById("qmvtree_inline_styles").getElementsByTagName("DIV");
	for (var i=0;i<a.length;i++)
	{
		var tr = a[i].getAttribute("rule");

		var obj = qmv.cur_item;
		if (tr.indexOf("parent")+1)
			obj = obj.parentNode;


		var aa = a[i].childNodes;
		for (var j=0;j<aa.length;j++)
		{

			if (aa[j].tagName=="A")
			{
				
				var inp = aa[j].getElementsByTagName("INPUT")[0];
				if (inp)
				{
					var cname = inp.getAttribute("cname");
					if (!cname) continue;

					var val = obj.style[cname];
					if (val)
					{
						if (qmad.br_ie)
							inp.value = val;
						else
							inp.value = qmv_load_css_styles_firefox_fix(val,inp.getAttribute("dtype"))
						
					}
					else
						inp.value = "";



					var dtype = inp.getAttribute("dtype");
					if (dtype == "color")
						qmv_color_build_button_set(inp);
										
			

					inp.rule_obj = obj;
					inp.prev_value = inp.value;
				}
			}

		}
		
	}

}

function qmv_load_title_applied_to_tree()
{
	var a = document.getElementById("qmvtree_item_titles").childNodes;
	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].tagName=="A")
		{
				
			var inp = a[i].getElementsByTagName("INPUT")[0];
			if (inp)
			{
				var cname = inp.getAttribute("cname");
				if (!cname) continue;

				if (cname=="apply")
				{	
					

					var sd;
					if (sd = qmv_lib_get_previoussibling_span(qmv.cur_item,"qmtitle"))
						inp.value = true;
					else
						inp.value = false;



				}
				else if (cname=="text")
				{
					
					var sd;
					if (sd = qmv_lib_get_previoussibling_span(qmv.cur_item,"qmtitle"))
					{
						if (!sd.innerHTML)
							inp.value = "";
						else
							inp.value = sd.innerHTML;

					}
					else 
						inp.value = "";


				}
				

				inp.prev_value = inp.value;

			}

		}

	}
	
}


function qmv_load_divider_applied_to_tree()
{



	var a = document.getElementById("qmvtree_item_dividers").childNodes;
	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].tagName=="A")
		{
				
			var inp = a[i].getElementsByTagName("INPUT")[0];
			if (inp)
			{
				var cname = inp.getAttribute("cname");
				if (!cname) continue;

				if (cname=="apply")
				{	

					var sd;
					if (sd = qmv_lib_get_previoussibling_span(qmv.cur_item,"qmdivider"))
						inp.value = true;
					else
						inp.value = false;
	

				}

				inp.prev_value = inp.value;

			}

		}

	}
	
}

function qmv_load_box_styles_to_tree()
{
	
	var a = document.getElementById("qmvtree_box").getElementsByTagName("DIV");	
	qmv_load_styles_to_tree(null,a);
}

function qmv_load_ritem_styles_to_tree()
{
	
	var a = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");	
	qmv_load_styles_to_tree(null,a);
}

function qmv_load_title_styles_to_tree()
{
	
	var a = document.getElementById("qmvtree_item_titles").getElementsByTagName("DIV");	
	qmv_load_styles_to_tree(null,a);
}

function qmv_load_stripe_styles_to_tree()
{
	
	var a = document.getElementById("qmvtree_item_stripes").getElementsByTagName("DIV");	
	qmv_load_styles_to_tree(null,a);
}



function qmv_load_divider_styles_to_tree()
{
	
	var a = document.getElementById("qmvtree_item_dividers").getElementsByTagName("DIV");	
	qmv_load_styles_to_tree(null,a);
}

function qmv_load_styles_to_tree(id,tree_obj)
{
	

	var rules = qmv.style_rules;
	if (!id) id = qmv.id;

	var a;
	if (!tree_obj)
		a = document.getElementById("qmvtree_css_styles").getElementsByTagName("DIV");	
	else
		a = tree_obj;

	for (var i=0;i<a.length;i++)
		a[i].stylesloaded = null;

	for (var i=0;i<rules.length;i++)
	{

		var st = rules[i].selectorText.toLowerCase();
		if (st.indexOf(".qmmc")==0 || st.indexOf(".qmclear")==0)
			continue;

		
		if (st.indexOf("#qm")+1 && st.indexOf("#qm"+id)==-1)
			continue;

		
		qmv_load_styles_to_tree_node(rules[i],st,null,a);

	}
	
	
	for (var i=0;i<a.length;i++)
	{

		var tr = a[i].getAttribute("rule");
		if (tr)
		{
			var tr = tr.replace("[i]",qmv.id);
			qmv_load_style_set_rule_desc(a[i],tr);
			
			if (!a[i].stylesloaded)
				qmv_load_styles_to_tree_node(null,tr,true,a);

		}
	}
	

}

function qmv_load_style_set_rule_desc(a,tr)
{

	if (a.idiv.getAttribute("ruledesc"))
	{
		var as = a.idiv.getElementsByTagName("SPAN");
		for (var j=0;j<as.length;j++)
		{
			if (as[j].getAttribute("isruledesc"))
				as[j].innerHTML = a.idiv.getAttribute("ruledesc")+"&nbsp;&nbsp;<span class='qmvtree-rule'>[ "+tr.replace("body","")+" ]</span>";

		}
					
	}


}


function qmv_load_styles_to_tree_node(rule,st,force_noval,a,test)
{

	
	st = st.split(",");
	st = st[0];	
		
	for (var i=0;i<a.length;i++)
	{
		var origrule = a[i].getAttribute("rule");
		var tr = origrule;
		if (tr)
		{

			tr = tr.replace("[i]",qmv.id);
			
			

			if (tr==st)
			{
				
				
				a[i].stylesloaded = 1;

				var aa = a[i].childNodes;
				for (var j=0;j<aa.length;j++)
				{
					if (aa[j].tagName=="A")
					{

						var cname = aa[j].getAttribute("cname");
						if (!cname)
							continue;
						
						var val = "";
						if (!force_noval)
							var val = rule.style[cname];

						var inp = aa[j].getElementsByTagName("INPUT")[0];
						var dtype = inp.getAttribute("dtype");
						if (val)
						{
							if (qmad.br_ie)
								inp.value = val;
							else
								inp.value = qmv_load_css_styles_firefox_fix(val,inp.getAttribute("dtype"));

							
							if (dtype == "color")
								qmvi_color_recent_add(inp);
												
							
						}
						else
							inp.value = val;


						if (dtype=="color")
							qmv_color_build_button_set(inp);

						var ir;
						if (ir = a[i].getAttribute("inheritrule"))
							inp.setAttribute("inheritrule",ir);
						
						
						inp.origrule = origrule;
						inp.rule = tr;
						inp.prev_value = inp.value;

						

					}


				}


			}	


		}


		
		
	}	

}





function qmv_color_build_button_set(inp)
{



	a = inp[qp];
	while (a && a.tagName && a.tagName!="A")
		a = a[qp];

	
	var s = a.getElementsByTagName("SPAN");

	if (s.length>0)
	{
		s = s[s.length-1];

		if (inp.value)
		{
			s.style.backgroundColor = inp.value;
			s.style.borderColor = "#333333";
			s.innerHTML = "";
		}
		else
		{
			s.style.backgroundColor = "";
			s.style.borderColor = "";
			s.innerHTML = "...";
		}
	}

}


function qmv_convert_color_to_hex(value)
{

	var rval = value;
	var cv = value.split(") ");
	if (cv.length==4)
	{
			
		if (cv[0]==cv[1] && cv[0]==cv[2] && cv[3].indexOf(cv[0])+1)
			rval = cv[0];
			
	}

	var rl = rval.toLowerCase();
	if (rl!="transparent" && rl.indexOf("rgb")+1)
		rval = qmv_color_parse_split(cv[0],true);
	

	return rval;
}


function qmv_load_css_styles_firefox_fix(value,dtype)
{

	

	var rval = value;


	if (dtype=="color")
	{
		
		rval = qmv_convert_color_to_hex(value);
			

	}

	var cv = value.split(" ");
	if (cv.length==4)
	{
		if (cv[0]==cv[1] && cv[0]==cv[2] && cv[0]==cv[3])
			rval = cv[0];	
	}
	


	return rval;

}



//************************** Visual Interface Event Handlers




function qm_evt_menu_item_click(o)
{
	
	
	if (qmv.preview_mode)
		return;
	
	var m = qm_get_menu(o);
	if (m.id=="qm99" || m.id=="qm98") return;
	qmv.cur_item = o;

	//if (m.qmpure) qmv.pure = true;
	//qmv.pure = true;
		
	qmv_set_texturl_field(o);
	qmv_load_inline_styles_to_tree()
	qmv_load_item_extras_to_tree();
	qmv_load_title_applied_to_tree();
	qmv_load_divider_applied_to_tree();

	var id;
	if (m.id && (id = m.id.substring(2)) && !isNaN(id = parseInt(id)))
	{	

		if (qmv.id==-1 || qmv.id != id)
		{
			qmv.color_recent = new Array();

			qmv.id = id;
			qmv_load_styles_to_tree();
			qmv_load_menu_settings_to_tree();
			qmv_load_addon_settings_to_tree();
			qmv_load_title_styles_to_tree();
			qmv_load_stripe_styles_to_tree();
			qmv_load_divider_styles_to_tree();
			qmv_load_box_styles_to_tree();
			qmv_load_ritem_styles_to_tree();
			qmv_load_custom_styles_to_tree();

			if (document.getElementById("qmvtree_filter").cdiv.style.display=="block")
				qmv_filter_build_results();

			var a = document.getElementById("qmvtree_color_shortcuts");
			if (a.style.display=="block")
				qmv_shortcut_init(a);

			qmv_setbox_update_quick_color();
		}
	
	}

	qmv_position_pointer(true);
	qmv_hide_context();

	qmv_setbox_update_individual();


	if (document.getElementById("qmvacc_apply_allitems"))
		qmv_custom_class_list_change(new Object());


	

}

function qmv_hide_pointer(id)
{
	
	var po;
	if ((po = qmv.pointer) && (po = po[id]) && (po = po.a))
		po.style.display = "none";

}

function qmv_position_pointer(show)
{

	if (!qmv) return;

	var po = qmv.pointer["qm"+qmv.id].a;

	//position the pointer
	var lt = qmv_lib_get_position_relative_to_main_menu_container(qmv.cur_item);
	po.style.left = (lt[0]-1)+"px";
	po.style.top = (lt[1]-1)+"px";
	po.style.width = (qmv.cur_item.offsetWidth)+"px";
	po.style.height = (qmv.cur_item.offsetHeight)+"px";

	
	if (show && !qmv.interface_hide_selected_box)
	{
		if (!qmv.addons.pointer["on"+qmv.id])
			po.style.display = "block";

		for (i in qmv.pointer)
		{
			if (i+""!="qm"+qmv.id && qmv.pointer[i]!=null)
				 qmv_hide_pointer(i+"");
		}
	
	}


}



function qmv_set_texturl_field(a,gettext,publish)
{

	

	var tuf = document.getElementById("qmv_texturl_field");
	if (qmv.texturl_state=="text" || gettext || publish)
	{
		var b = a.cloneNode(true);
		var after = qmv_atag_text_remove_objects(b,publish);
	
		if (gettext)
			return b.innerHTML;
		else if (publish)
			return b.innerHTML+after;
		else
			tuf.value = b.innerHTML;

	
	}
	else
	{
		
		tuf.value = a.getAttribute("href",2);
	}

}

function qmv_atag_text_remove_objects(a,publish)
{
	var rval = "";
	var s = a.childNodes;
	for (var i=0;i<s.length;i++)
	{
		
		if (s[i].getAttribute && (s[i].getAttribute("qmvbefore") || s[i].getAttribute("qmvafter")))
		{
			if (publish)
			{
				
				if (s[i].getAttribute("qmvafter"))
				{
					
					if (s[i].className && s[i].className.indexOf("qm-is")+1)
					{

						
						rval = '<img ';
						
						talt = s[i].getAttribute("alt",2);
						if (!talt)
							talt = "";
						else
							talt = ' alt="'+talt+'"';							

						rval += ' class="'+s[i].className+'" src="'+qm_image_base(s[i],true)+'" width="'+s[i].getAttribute("width",2)+'" height="'+s[i].getAttribute("height",2)+'"'+talt+'>';


					}

				}


			}


			a.removeChild(s[i]);
			i--;
		}
		

	}

	return rval;

}


function qmv_evt_update_tree_value(a,fromspin,skip_validate,skip_update,skip_inherit,is_quickcolor,skip_filter_update,skip_mirror)
{
	
	
	if (a.value==a.prev_value)
		return;
	

	try
	{

		var dt = a.getAttribute("dtype");
		

		if (!skip_validate)
		{
		
			
			if (a.value && dt)
			{
		
				var range = a.getAttribute("range");
				var oops = false;
				var oops_range = false;
			
				var t;
				var es;
				if (dt=="int")
				{
					t = parseInt(a.value);
					if (isNaN(t))
						oops = true;		

					a.value = t;

				
					if (!oops && range)
					{
						es = range.replace("x",t);
						es = es.replace("x",t);		
						if (eval(es))
							oops_range=true;

					}
			
				}
				else if (dt=="unit")
				{
					if (a.value.toLowerCase()!="auto")
					{
				
						t = parseInt(a.value);
						var r = parseFloat(a.value);
						if (isNaN(t) && isNaN(r))
							oops = true;
						else if (!qmv_lib_get_units(a.value))
							a.value = a.value+"px";

						if (isNaN(t))
							t = r;
					
						if (!oops && range)
						{
							es = range.replace("x",t);
							es = es.replace("x",t);	
							if (eval(es))
								oops_range=true;

						}
					
					}

				}
				else if (dt=="bool")
				{
					t = a.value.toLowerCase();
					if (t && t!="false")
						a.value = "true";

				}
				else if (dt=="float")
				{

					t = parseFloat(a.value);
					if (isNaN(t))
						oops = true;


				
					if (!oops && range)
					{
						es = range.replace("x",t);
						es = es.replace("x",t);	
						if (eval(es))
							oops_range=true;
					
					}
				}
				else if (dt.indexOf("edge")+1)
				{
					

					if (dt.indexOf("borderwidth")+1 || dt.indexOf("margin")+1 || dt.indexOf("padding")+1)
					{	
						
						if (t = a.value.replace(/\,/g," "))
						{
							if (isNaN(parseInt(t)) && isNaN(parseFloat(t)))
							{
								oops = true;

							}
							else
							{
						
								if (t.indexOf(" ")==-1)
								{
								
									if (!qmv_lib_get_units(t))
										a.value = t+"px";
								}
								else
								{
									var tt = "";
									t = t.split(" ");
									for (var k=0;k<t.length;k++)
									{
										if (t[k] && !qmv_lib_get_units(t[k]))
											t[k] = t[k]+"px";

									}
	
									var count = 0;
									for (var k=0;k<t.length;k++)
									{
										if (count>3 || !t[k]) continue;
									
										tt+=t[k]+" ";
										count++	
									}	
		
									a.value = tt.substring(0,tt.length-1);
	
								}
							}
						}

					}
				}
				else if (dt == "styleimage")
				{
				
					if (a.value && a.value.toLowerCase().indexOf("url(")==-1)
						a.value = "url("+a.value+")";

					if (a.value && a.value.indexOf(")")==-1)
						a.value = a.value+")";
					

				}
				else if (dt == "color")
				{

					if (!isNaN(parseInt(a.value,16)))
					{
						
						a.value = "#"+a.value;
					}


				}

			
				if (oops || oops_range)
				{

				
					a.value = a.prev_value;
	
					var show_range = "";	
					if (oops_range)
					{
						if (range=="x<0 || x>1")
							show_range = "<br><br>The valid range for this settings is between 0 and 1.";
						else if (range=="x<0")
							show_range = "<br><br>The value for this setting must be greater than 0.";
						else if (range=="x<0 || x>50")
							show_range = "<br><br>The valid range for this settings is between 0 and 50.";
						else if (range=="x<0 || x>20")
							show_range = "<br><br>The valid range for this settings is between 0 and 20.";
						else if (range=="x<0 || x>10")
							show_range = "<br><br>The valid range for this settings is between 0 and 10.";
				
					}

					if (!fromspin)
					{
						qmv_show_dialog("alert",null,"The value you selected or entered is invalid."+show_range,450);	
						return;
					}
	
				}
	
			
			}
		
		}

		
		
		
		var cat = a.getAttribute("category");
		

		if (a.iseditcolor)
		{
		
					
			var compare = a.prev_value.toLowerCase();
			for (var i=0;i<qmv.color_recent.length;i++)
			{
					
				if (qmv.color_recent[i].value==compare)
				{
							
					qmv.color_recent[i].inp.value = a.value;
					qmv_evt_update_tree_value(qmv.color_recent[i].inp,false,false,false,true,true);

					if (!a.value || a.value.toLowerCase()=="transparent")
						i--;

				}

			}

			a.prev_value = a.value;

			
			var ta = document.getElementById("qmvtree_color_shortcuts");
			if (ta.style.display=="block")
				qmv_shortcut_init(ta);	
			
			
			
			qmv_setbox_update_quick_color();
			return;

		}

		if (dt && dt=="color")
			qmv_color_build_button_set(a);
						
		
		

		if (cat=="create")
			qmv_evt_update_menu_settings(a.value,a.rule,a.getAttribute("sname"),a.getAttribute("cname"),a.getAttribute("dtype"));
		else if (cat=="inline")
			qmv_evt_update_inline_style(a.value,a.rule_obj,a.getAttribute("sname"),a.getAttribute("cname"));
		else if (cat=="texturl")
			qmv_evt_update_texturl(a);
		else if (cat=="addon")
		{
			if (!skip_mirror)
				qmv_evt_update_addon(a.value,a.rule_obj,a.getAttribute("sname"),a.getAttribute("cname"),a.getAttribute("dtype"),a,skip_update);
			else
				return;

		}
		else if (cat=="css")
		{

			
			
			qmv_evt_update_css_style(a.value,a.rule,a.getAttribute("sname"),a.getAttribute("cname"),null,null,fromspin);

			if (a.isfilter)
			{
				var rinp = qmv_find_update_tree_value("css",a.origrule,a.getAttribute("cname"),a.value,false,true);
				rinp.prev_value = a.value;
			}
			else if (!skip_filter_update && document.getElementById("qmvtree_filter").cdiv.style.display=="block")
			{
				qmv_filter_init2();
			}
			

			if (a.origrule.indexOf("qmpersistent")+1 && !qmv.addons.sopen_auto["on"+qmv.id])
			{
			
				qmv_show_dialog("question-yesno",null,"Persistent styles apply to all menu items which have URL links which match the location of the web page displaying the menu.<br><br>The persistent state add-on must be enabled and published with your menu for the styles to display, would you like to enable the persistent state add-on?",500,"qmv_context_cmd(new Object(),'addon_sopen_auto')");	

			}
			
			
		}
		else if (cat=="iextra")
		{
			if (!skip_mirror)
				qmv_evt_update_item_extra(a.value,a.getAttribute("sname"),a.getAttribute("cname"),a.rule,a.getAttribute("dtype"),a);
			else
				return;
		}
		

		if (!skip_inherit && !fromspin && !is_quickcolor)
		{
			var ir = a.getAttribute("inheritrule");
			if (ir)
				qmv_inherit_style_question(ir,a.getAttribute("cname"),a.value,a);
			
		}


		
		if (cat!="addon")
		{
			var iefix = false;
			if (cat=="css" && qmad.br_ie)
				iefix = true;

			qmv_set_all_subs_to_default_position(true,iefix);
			qmv_update_all_addons();
			
		}


		if (!skip_mirror)
		{
			if (a.mirror)
			{
				a.mirror.value = a.value;
				qmv_evt_update_tree_value(a.mirror,null,null,null,null,null,null,true);
			}
			else
			{

				
				var sb = document.getElementById("qmvi_setbox");
				if (sb.style.visibility == "visible")
				{
					var sbi = sb.getElementsByTagName("INPUT");
					for (var i=0;i<sbi.length;i++)
					{
						if (sbi[i].mirror==a)
						{
							sbi[i].value = a.value;
							qmv_evt_update_tree_value(sbi[i],null,null,null,null,null,null,true);

							break;
						}

					}

				}

			}
		}


		
		qmv_position_pointer();
		a.prev_value = a.value;

		
		
		if (dt && !a.iseditcolor && !a.isfilter && !a.mirror)
		{
			
			if (dt=="color")
			{
				if (a.value)
					qmvi_color_recent_add(a,true);
				else
					qmvi_color_recent_remove(a);
			}

		}



		if (dt && dt=="color" && !is_quickcolor && !a.mirror)
		{
			
			var ta = document.getElementById("qmvtree_color_shortcuts");
			if (ta.style.display=="block")
				qmv_shortcut_init(ta);

			qmv_setbox_update_quick_color();
			
		}


	
	}
	catch(e)
	{
		
		if (a.prev_value)
		{
			
			qmv_show_dialog("warning-undo",a,"Invalid Value: The value you entered is not valid.",450)
			a.value = a.prev_value;
			return true;
		}

	}
	

	var code = a.getAttribute("code");
	if (code)
		eval(code);


}




function qmv_evt_update_tree_value_enter(e,a)
{

	e = e || window.event;


	if (e.keyCode==13)
		qmv_evt_update_tree_value(a);
		
	

}



function qmv_evt_title_mousedown(e,src,type,isrel)
{
	

	
	e = e || window.event;
				
	qmv.title_mdown = true;
	qmv.title_prev_x = e.screenX;
	qmv.title_prev_y = e.screenY;
	qmv.title_move_shadow = false;
	qmv.title_type = type;
	qmv.title_relative = isrel;

	if (!type)
	{
		if (qmv.interface_full)
			return;

		qmv.title_obj = document.getElementById("qmvi");
		qmv.title_adjust_floating_window = true;
	}
	else
	{
		if (type==1)
		{
			qmv.title_obj = document.getElementById("qmvi_dialog");
			qmv.title_move_shadow = document.getElementById("qmvi_dialog_shadow");;
		}
		else if (type==2)
		{
			qmv.title_obj = document.getElementById("qmvi_msg_dialog");
			qmv.title_move_shadow = document.getElementById("qmvi_msg_dialog_shadow");
		}
		else if (type==3)
		{
			qmv.title_obj = document.getElementById("qmvi_help_dialog");
			qmv.title_move_shadow = document.getElementById("qmvi_help_dialog_shadow");
		}
		else if (type==4)
		{
			qmv.title_obj = document.getElementById("qmvi_setbox");
			qmv.title_move_shadow = document.getElementById("qmvi_setbox_shadow");
		}
		else
		{
			qmv.title_obj = type;
			
		}
	}

	
	src.style.cursor = "move";

	qm_kille(e);
	return false;

}



function qmv_evt_title_mousemove(e,src)
{
	
	e = e || window.event;	
	var m = qmv.title_obj;	
	if (!m) return;

	if (qmv.title_mdown)
	{
		
				
		var xdif = qmv.title_prev_x-e.screenX;
		var ydif = qmv.title_prev_y-e.screenY;
	
		if (qmv.title_relative)
		{

			m.style.left = (parseInt(m.style.left)-xdif)+"px";
			m.style.top = (parseInt(m.style.top)-ydif)+"px";

		}
		else
		{
			m.style.left = (m.offsetLeft-xdif)+"px";
			m.style.top = (m.offsetTop-ydif)+"px";
		}
		

		if (qmv.title_move_shadow)
		{
			
			qmv.title_move_shadow.style.left = parseInt(m.style.left)+3+"px";
			qmv.title_move_shadow.style.top =  parseInt(m.style.top)+3+"px";
		}

		
		qmv.title_prev_x = e.screenX;
		qmv.title_prev_y = e.screenY;


		qmv.title_moved = true;		

		if (qmad.br_ie)
			src.setCapture(true);

		
		qm_kille(e);
		return false;
		
	}

}


function qmv_evt_title_mouseup(e,src)
{
	
	if (qmv.title_adjust_floating_window) qmv_auto_size_interface_height();
		

	qmv.title_mdown = false;
	if (qmv.title_moved && qmad.br_ie)
		src.releaseCapture(true);
	
	
	src.style.cursor = "";
	
	
	
}


function qmv_evt_move_fixcapture(e)
{
	e = e || widow.event;	
	

	if (qmv.title_mdown)
	{
		
		qmv_evt_title_mousemove(e,document.getElementById("qmvi_title"))
	}
	
	if (qmv.container_moved)
		qmv_container_mouse_move(e,qmv.container_obj);


}

function qmv_evt_fix_mouse_up(e)
{

	if (!qmv.preview_mode) qm_la = null;
	
	if (qmv.color_vals && qmv.color_vals.bright_down)
		qmv_color_brightness_mouseup(e,document.getElementById("qmvi_color_bright_bar"))

	if (qmv.color_vals && qmv.color_vals.hs_down)
		qmv_color_huesaturation_mouseup(e,document.getElementById("qmvi_color_hs_div"))

}


function qmv_evt_kill_click(e)
{

	e = e || event;

	qm_kille(e,true);
	return false;

}


function qmv_evt_bb_click(type)
{


	if (type=="add" || type=="insert" || type=="delete" || type=="addsub" || type=="moveup" || type=="movedown" || type=="copyitem" || type=="pasteitem")
	{

		qmv_modify_items(type);

	}
	else if (type=="addmenu")
	{

		qmv_add_new_menu();

	}
	else if (type=="deletemenu")
	{

		qmv_delete_menu();
	}
	else if (type=="save")
	{

		qmv_evt_menu_item_click('save');

	}
	else if (type=="publish")
	{

		qmv_evt_menu_item_click('quick_publish');

	}
	else if (type=="preview")
	{

		qmv_evt_menu_item_click('preview');

	}
	else if (type=="specs")
	{
		qmv_context_cmd(new Object(),'specs');

	}
	else if (type=="insert_divider")
	{
		qmv_context_cmd(new Object(),'insert_divider');

	}
	else if (type=="insert_title")
	{
		qmv_context_cmd(new Object(),'insert_title');

	}	
	else if (type=="style_divider")
	{
		qmv_context_cmd(new Object(),'divider_styles');

	}
	else if (type=="style_title")
	{
		qmv_context_cmd(new Object(),'title_styles');
	}
	else if (type=="quickcolors")
	{
		qmv_context_cmd(new Object(),'quick_color_edits');
	}


}

function qmv_evt_addremove_addon(e,a)
{

	e = e || event;
	

	var at = a.parentNode;
	while (at.tagName!="A")
		at = at.parentNode;

	
	var add_type = at.cdiv.getAttribute("addontype");


	

	if (a.checked)
	{
		
		
		var inp = at.cdiv.getElementsByTagName("INPUT");
		for (var i=0;i<inp.length;i++)
		{

			var ad = inp[i].getAttribute("addondefault");
			if (ad)
			{
				
				if ((ad!="blank" && !inp[i].value) || (!isNaN(parseInt(inp[i].value)) && !parseInt(inp[i].value)))
				{
					inp[i].value = ad;
					qmv_evt_update_tree_value(inp[i],null,true,true)
					
				}

			}	


		}

		
		if (add_type=="ritem")
		{

			qmv_show_dialog("alert",null,"Rounded items are recommended for advanced users. Each rounded item sits on top of the standard menu item, tweaking the position and look of the rounded items requires a good working knowledge of adjusting menu padding, margins, and colors.<br><br>Because the rounded items are position absolute, you will have to adjust the standard setting margins and padding to adjust how the items appear next to each other. If your rounded items are bigger than the standard items, they may overlap in the subs, use padding to increase the standard item sizing which will also adjust the rounded item positioning.<br><br>Because rounded items sit within the existing menu items, any item square borders or colors will be visible at the rounded corners edges, these options are typically turned off to achieve the desired effect.  You can use the CSS styles or the included quick options in the rounded item add-on settings to adjust these styles.<br><br>Its easiest to work from a rounded item template rather than applying this add-on to an existing menu.",500);


		}


		if (qmv_check_addon_compatability(qmv.addons[add_type]))
		{
			
			a.checked = false;
			qmv_setbox_update_addon_check(a);

			qm_kille(e,true);
			return;
		}

		
		qmv.addons[add_type]["on"+qmv.id] = true;

		
		if (window["qmv_update_"+add_type])
			eval("qmv_update_"+add_type+"()");


		
		if (a[qp].cdiv.style.display!="block")
			qm_vtree_item_click(a[qp].cdiv);
		

	}
	else
	{
		
		if (window["qmv_update_"+add_type])
			eval("qmv_update_"+add_type+"(true)");


		qmv.addons[add_type]["on"+qmv.id] = false;
		
		

	}

	
	qmv_setbox_update_addon_check(a);

	qmv_position_pointer();
	qmv_update_all_addons(add_type);

	
	qm_kille(e,true);

}


function qmv_evt_menu_item_click(type)
{

	

	if (type=="options")
	{
		qmv_show_dialog("options");

	}
	else if (type=="preview")
	{
		qmv_preview_menu();

	}
	else if (type=="publish")
	{

		qmv_show_dialog("publish1");

	}
	else if (type=="quick_publish")
	{

		qmv_show_dialog("quick_publish");

	}
	else if (type=="save")
	{

		qmv_show_dialog("save");

	}
	else if (type=="import")
	{


		
		var ir = document.createElement("SPAN");
		ir.style.position = "absolute";
		ir.style.visibility = "hidden";
		document.body.appendChild(ir);
		ir.innerHTML = '<iframe onreadystatechange="qmvi_iframe_loaded(event)" id="qmvi_temp_iframe" src="http://www.opencube.com"></iframe>'
		
	}
	else if (type=="help")
	{
		
		qmv_show_dialog("help-index",null,"help-index.html");

	}
	else if (type.indexOf("iface_switch")+1)
	{
		if (type.indexOf("full")+1 && !qmv.interface_full)
			qmv_set_interface_mode("full");
		else if (type.indexOf("inpage")+1 && qmv.interface_full)
			qmv_set_interface_mode("inpage");
	}
}

function qmvi_iframe_loaded(e)
{

	e = e || window.event;
	var ifobj = document.getElementById("qmvi_temp_iframe");
	var iwin = null;

	if (ifobj.readyState && ifobj.readyState!="interactive")
		return;

	

}




function qm_kille(e,skip_default)
{

	
	if (!e) e = event;
	e.cancelBubble = true;
	if (e.stopPropagation)
		 e.stopPropagation();

	
	if (!skip_default)
	{
		
		if (e.preventDefault)
			e.preventDefault();

		e.returnValue = false;
	}
}


function qmv_tree_oo(e,o,nt)
{

	if (!o) o=this;

	qmv_hide_context();

	
	if (window.qmwait) return;
		
	var a = o;
	if (a[qp].isrun) return;
	
		
	var b = o;	
	if (b.cdiv)
	{
	
				
		qm_arc("qmactive",o,true);
		
		qm_vtree_item_click(b.cdiv);
		qmv_ibullets_active(o,false);
		b.cdiv.style.visibility ="inherit";
		

	}
	
	
	qm_kille(e);
	

}

function qmv_tree_uo(a,go)
{
	
	
	if (!go && a.qmtree) return;

	
	qmv_ibullets_active(a,true);

	a.style.visibility = "";
	qm_arc("qmactive",a.idiv);
	

}


function qmv_evt_build_button_click(a,spin)
{

	var fa = a.parentNode;
	while (fa.tagName!="A" && fa.tagName!="TBODY")
		fa = fa.parentNode;


	var inp = fa.getElementsByTagName("INPUT")[0];

	if (!spin)
		qmv_show_build_dialog(inp.getAttribute("dtype"),inp);
	else
	{

		inp.prev_value = inp.value;
		inp.value = qmv_spin_value(spin,inp.getAttribute("dtype"),inp.value);
		qmv_evt_update_tree_value(inp,true);

	}

}



//************************** utilites library


function qmv_lib_get_position_relative_to_main_menu_container(obj)
{

	var l = obj.offsetLeft;
	var t = obj.offsetTop;	

	while (!qm_a(obj = obj[qp]))
	{
		var bs = qm_lib_get_computed_style(obj,"border-style","borderStyle");
				
		var bt = 0;
		var bl = 0;
		if (qmad.br_fox || (bs && bs.toLowerCase()!="none"))
		{
			bt = qm_lib_get_computed_style(obj,"border-top-width","borderTopWidth",true);
			bl = qm_lib_get_computed_style(obj,"border-left-width","borderLeftWidth",true);
		}
		
		
		l += obj.offsetLeft+bl;
		t += obj.offsetTop+bt;
		
	}


	return new Array(l,t);

}

function qm_lib_get_computed_style(obj,sname,jname,isint)
{
	var v;
	if (document.defaultView && document.defaultView.getComputedStyle)
		v = document.defaultView.getComputedStyle(obj, null).getPropertyValue(sname);
	else if (obj.currentStyle)
		v = obj.currentStyle[jname];		

	if (isint)
	{
		if (v && !isNaN(v = parseInt(v)))
			return v;
		else
			return 0;
	}

	return v;
}

function qmv_lib_get_units(checkval)
{

	checkval = checkval.toLowerCase();

	if (checkval.indexOf("px")+1)
		return "px";
	else if (checkval.indexOf("em")+1)
		return "em";
	else if (checkval.indexOf("cm")+1)
		return "cm";
	else if (checkval.indexOf("mm")+1)
		return "mm";
	else if (checkval.indexOf("in")+1)
		return "in";
	else if (checkval.indexOf("pt")+1)
		return "pt";
	else if (checkval.indexOf("ex")+1)
		return "ex";
	else if (checkval.indexOf("pc")+1)
		return "pc";
	else if (checkval.indexOf("%")+1)
		return "%";	
	
	return "";

}


function qmv_lib_get_menu_count()
{

	var count = 0;

	for (var i=0;i<10;i++)
	{

		if (document.getElementById("qm"+i))
			count++;
	}
	

	return count;
}

function qmv_lib_get_new_menu_id()
{

	var i = 0;
	while (document.getElementById("qm"+i))
		i++;
	

	return i;
}

function qmv_lib_update_add_rule_styles(style,rules,search,value)
{

		
	for (var i=0;i<rules.length;i++)
	{

		if (rules[i].selectorText.toLowerCase() == search)
		{
			
			rules[i].style.cssText = value;
			return;
		}	

	}	

	if (style.addRule)
		style.addRule(search,value);
	else if (style.insertRule)
		style.insertRule(search+" {"+value+"}",0);
	


}

function qmv_lib_update_remove_rule(style,rules,search,value)
{

		
	for (var i=0;i<rules.length;i++)
	{

		if (rules[i].selectorText.toLowerCase().split(",")[0] == search)
		{
			if (style.removeRule)
				style.removeRule(i);
			else
			{
				//alert(rules[i].style.cssText);
				style.deleteRule(i);
			}
				
			return;
		}	

	}	

	
	


}



function qmv_lib_parse_value(value,dtype,rfalse)
{

	var rv;

	if (dtype=="int")
	{
		
		if (value)
		{
			
			rv = parseInt(value);
			if (!isNaN(rv)) return rv;
			
		}
		
		if (rfalse)		
			return false;
		else
			return 0
	}
	else if (dtype=="float")
	{

		if (value)
		{
			
			rv = parseFloat(value);
			if (!isNaN(rv)) return rv;

		}
				
		if (rfalse)		
			return false;
		else
			return 0


	}
	else if (dtype=="bool")
	{

		if (value+"".toLowerCase()=="false")
			return false;
		else if (value+"".toLowerCase()=="true")
			return true;
		else if (value)
			return true;

		return false;
	}
	else if (dtype=="corners-bool-array")
	{
		return eval("new Array("+value+")");


	}



	return value;


}



function qm_get_level(a)
{

	lev = 0;
	while (!qm_a(a) && (a=a[qp]))
		lev++;


	return lev;

}

function qm_index(a)
{
	return qm_get_menu(a).id.substring(2);


}

function qm_get_menu(a)
{

	while (!qm_a(a) && (a=a[qp]))
		continue;


	return a;

}


function qmv_lib_get_qm_stylesheet(searchfor)
{

	if (!searchfor)
		searchfor = ".qmmc";

	var ss = document.styleSheets;
	

	for (var i=0;i<ss.length;i++)
	{
		if (qmad.br_ie)
		{
			
			if (ss[i].cssText && ss[i].cssText.toLowerCase().indexOf(searchfor)+1)
				return ss[i]
		}
		else
		{
			var rules = ss[i].cssRules;
			if (rules)
			{
				for (var j=0;j<rules.length;j++)
				{
					if (rules[j].selectorText.toLowerCase().indexOf(searchfor)+1)
						return ss[i];

				}
			}

		}


	}


	

}



function qmv_lib_is_menu_vertical(id)
{
	var fa = document.getElementById("qm"+id).getElementsByTagName("A");
	var isv = fa[0].style.styleFloat || fa[0].style.cssFloat;

	return isv;
	

}


function qmv_lib_get_window_dimensions()
{

	var dh = 0;
	var dw = 0;
	if (window.innerHeight)
	{
		dh = window.innerHeight;
		dw = window.innerWidth;

	}
	else
	{
		dh = document.documentElement.clientHeight;
		dw = document.documentElement.clientWidth;
	}
		
	return new Array(dw,dh);

}

function qmv_lib_center_element_in_window(a)
{
	
	var wd = qmv_lib_get_window_dimensions();
	var dw = wd[0];
	var dh = wd[1];


	var qw = a.offsetWidth;
	var qh = a.offsetHeight;

	var d;
	var st = 0;
	var sl = 0;
	if (d = document.documentElement)
	{
		st = d.scrollTop;
		sl = d.scrollLeft;
	}


	if (qmad.br_ie && !qmad.br_strict)
	{
		a.style.top = 50+"px";
		a.style.left = 50+"px";

	}
	else
	{
		a.style.top = (parseInt((dh-qh)/2)+st)+"px";
		a.style.left = (parseInt((dw-qw)/2)+sl)+"px";
	}

}


function qmv_lib_insert_after(ins,obj)
{

	if (obj.nextSibling)
		return obj.parentNode.insertBefore(ins,obj.nextSibling);
	else
		return obj.parentNode.appendChild(ins);

	

}

function qmv_lib_get_nextsibling_atag(a)
{

	ps = a.nextSibling;
	while (ps && ps.tagName!="A")
		ps = ps.nextSibling;


	return ps;
}

function qmv_lib_get_previoussibling_atag(a)
{

	ps = a.previousSibling;
	while (ps && ps.tagName!="A")
		ps = ps.previousSibling;


	return ps;
}


function qmv_lib_get_nextsibling_atag_or_span(a)
{

	ps = a.nextSibling;
	while (ps)
	{
		
		if (ps.tagName=="SPAN")
		{
			
			if (ps.className.indexOf("qmtitle")+1)
				break;	

			if (ps.className.indexOf("qmdivider")+1)
				break;	
				
		}

		if (ps.tagName=="A")
			break;

		ps = ps.nextSibling;

	}

	if (!ps || (ps.tagName!="SPAN" && ps.tagName!="A")) ps = null;

	return ps;
}

function qmv_lib_get_previoussibling_atag_or_span(a)
{

	ps = a.previousSibling;
	while (ps)
	{
		
		if (ps.tagName=="SPAN")
		{
			
			if (ps.className.indexOf("qmtitle")+1)
				break;	

			if (ps.className.indexOf("qmdivider")+1)
				break;	
				
		}

		if (ps.tagName=="A")
			break;

		ps = ps.previousSibling;

	}

	
	if (!ps || (ps.tagName!="SPAN" && ps.tagName!="A")) ps = null;
	

	return ps;

}


function qmv_lib_get_nextsibling_span(a,classname)
{

	ps = a.nextSibling;
	while (ps && ps.tagName!="A")
	{
		
		if (ps.tagName=="SPAN")
		{
			
			if (classname && ps.className.indexOf(classname)+1)
				break;	

			if (!classname)
				break;
		
		}

		ps = ps.nextSibling;

	}

	if (!ps || ps.tagName!="SPAN") ps = null;

	return ps;
}

function qmv_lib_get_previoussibling_span(a,classname)
{

	ps = a.previousSibling;
	while (ps && ps.tagName!="A")
	{
		
		if (ps.tagName=="SPAN")
		{
			
			if (classname && ps.className.indexOf(classname)+1)
				break;	

			if (!classname)
				break;
		
		}

		ps = ps.previousSibling;

	}

	if (!ps || ps.tagName!="SPAN") ps = null;

	return ps;
}




//dialog functions


function qmv_show_build_dialog(dtype,owner)
{

	var type = "zoom";

	
	if (dtype=="color")
		type = "color";
	else if (dtype=="texturl")
	{
		if (qmv.texturl_state=="url")	
			type = "url";
	}
	else if (dtype.indexOf("edge")+1)
	{


		type = "edge";
		qmv.edge = new Object();
		qmv.edge.title = "Edge Properties";
		qmv.edge.width = 280;
		qmv.edge.dtype = "unit";


		if (dtype.indexOf("borderwidth")+1)
		{
			qmv.edge.title = "Border Width"
	
		}
		else if (dtype.indexOf("padding")+1)
		{
			qmv.edge.title = "Padding"


		}
		else if (dtype.indexOf("margin")+1)
		{
			qmv.edge.title = "Margin"

		}
		
		
	}
	else if (dtype=="bool")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("true","false");
		qmv.multi.vals = new Array("true","false");
		qmv.multi.title = "True / False";
		qmv.multi.desc = "True / False"

	}	
	else if (dtype=="styleimagerepeat")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("repeat","no-repeat");
		qmv.multi.vals = new Array("repeat","no-repeat");
		qmv.multi.title = "Background Repeat";
		qmv.multi.desc = "Repeat"



	}
	else if (dtype=="visibility")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("visible","inherit","hidden");
		qmv.multi.vals = new Array("visible","inherit","hidden");
		qmv.multi.title = "Set Visibility";
		qmv.multi.desc = "Visibility"



	}
	else if (dtype=="styleimageposition")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("top left","top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right");
		qmv.multi.vals  = new Array("top left","top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right");
		qmv.multi.title = "Background Position";
		qmv.multi.desc = "Background"


	}
	else if (dtype=="fontfamily")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("Arial","Times New Roman", "Verdana", "Georgia", "Comic Sans MS", "Courier New");
		qmv.multi.vals  = new Array("Arial","Times New Roman", "Verdana", "Georgia", "Comic Sans MS", "Courier New");
		qmv.multi.title = "Font Family";
		qmv.multi.desc = "Font"
	}				
	else if (dtype=="textdecoration")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("none","underline","overline","line-through" ,"blink");
		qmv.multi.vals  = new Array("none","underline","overline","line-through" ,"blink");
		qmv.multi.title = "Text Decoration";
		qmv.multi.desc = "decoration"
	}
	else if (dtype=="fontweight")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("normal","bold","bolder","lighter");
		qmv.multi.vals  = new Array("normal","bold","bolder","lighter");
		qmv.multi.title = "Font Weight";
		qmv.multi.desc = "Weight"

	}
	else if (dtype=="fontstyle")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("normal","italic","oblique");
		qmv.multi.vals  = new Array("normal","italic","oblique");
		qmv.multi.title = "Font Style";
		qmv.multi.desc = "Style"

	}
	else if (dtype=="borderstyle")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("none","dotted","dashed","solid","double","groove","ridge","inset","outset");
		qmv.multi.vals  = new Array("none","dotted","dashed","solid","double","groove","ridge","inset","outset");
		qmv.multi.title = "Border Style";
		qmv.multi.desc = "Style"

	}
	else if (dtype=="ibullets-apply")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("parents","non-parent","all");
		qmv.multi.vals  = new Array("parent","non-parent","all");
		qmv.multi.title = "Apply Bullets To These Items";
		qmv.multi.desc = "Apply"

	}
	else if (dtype=="tabscss-type")
	{
		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("angled","rounded");
		qmv.multi.vals  = new Array("angled","rounded");
		qmv.multi.title = "Tab Style Type";
		qmv.multi.desc = "Type"

	}
	else if (dtype=="corners-bool-array")
	{

		type = "edge";
		qmv.edge = new Object();
		qmv.edge.title = "Apply to Corners";
		qmv.edge.width = 330;
		qmv.edge.dtype = "bool";
		qmv.edge.corners = true;
		qmv.edge.array = true;
	}
	else if (dtype=="treeanimationtype")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("none", "acceleration", "deceleration", "normal");
		qmv.multi.vals  = new Array(0,1,2,3);
		qmv.multi.title = "Tree Animation Styles";
		qmv.multi.desc = "Animation"

	}
	else if (dtype=="textalign")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("left", "center", "right");
		qmv.multi.vals  = new Array("left", "center", "right");
		qmv.multi.title = "Text Alignment";
		qmv.multi.desc = "Alignment"

	}
	else if (dtype=="verticalalign")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("top", "middle", "bottom");
		qmv.multi.vals  = new Array("top", "middle", "bottom");
		qmv.multi.title = "Vertical Alignment";
		qmv.multi.desc = "Alignment"

	}
	else if (dtype=="ibcss-type")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("arrow", "arrow head", "arrow head (separated)","open v arrow","open v arrow head","opan v arrow head (separated)","square","square w/ inner","raised square");
		qmv.multi.vals  = new Array("arrow","arrow-head","arrow-gap","arrow-v","arrow-head-v","arrow-gap-v","square","square-inner","square-raised");
		qmv.multi.title = "Bullet Shapes";
		qmv.multi.desc = "Shape"

	}
	else if (dtype=="ibcss-direction")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("up","down","right","left");
		qmv.multi.vals  = new Array("up","down","right","left");
		qmv.multi.title = "Bullet Direction";
		qmv.multi.desc = "Direction"

	}
	else if (dtype=="bump-direction")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("up","down","right","left");
		qmv.multi.vals  = new Array("up","down","right","left");
		qmv.multi.title = "Bump Direction";
		qmv.multi.desc = "Direction"

	}
	else if (dtype=="palign")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("top or left","bottom or right");
		qmv.multi.vals  = new Array("top-or-left","bottom-or-right");
		qmv.multi.title = "Pointer Location";
		qmv.multi.desc = "Location"

	}
	else if (dtype=="box-position")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("center","top","left","top-left");
		qmv.multi.vals  = new Array("center","top","left","top-left");
		qmv.multi.title = "Pointer Location";
		qmv.multi.desc = "Location"

	}
	else if (dtype=="ritem-apply")
	{

		type="multi";
		qmv.multi = new Object();
		qmv.multi.show = new Array("main","sub","main-sub","parents","titles","dividers");
		qmv.multi.vals  = new Array("main","sub","main-sub","parents","titles","dividers");
		qmv.multi.title = "Apply Rounding";
		qmv.multi.desc = "Apply Rounded Items"

	}	
	else if (dtype.indexOf("image")+1)
	{

		type="image";
		

	}


	


	qmv_show_dialog(type,owner);

}

function qmv_set_publish_menus_object()
{
	
	if (!qmv.publish)
	{
		qmv.publish = new Object();
		
		
		qmv.publish.css_type = "inpage";
		qmv.publish.code_type = "inpage";
		qmv.publish.structure_type = "inpage";
		
	}

	qmv.publish.smenus = new Array();
	qmv.publish.smenus_pos=0;

	for (var i=0;i<10;i++)
	{

		if (document.getElementById("qm"+i))
			qmv.publish.smenus.push(i);
			
	}


}


function qmv_show_dialog(type,owner,message,w,code,code1,defbutton,defval)
{




	var ih = "";
	var title = "Visual QuickMenu";
	var width = w;
	if (!width) width = 300;

	
	var mg = "";
	if (message) mg = "msg_";
	if (message && message.indexOf("help-")+1) mg = "help_";

	
	if (defval==null || defval==undefined) defval = "";

	var buttons = "ok|cancel|apply";


	

	if (type=="color")
	{
		buttons = "OK|Cancel|Apply";
		title = "Color Picker";
		width = 420;
	
		
			ih += '<div style="padding:5px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0 width=100%><tr>';

				ih += '<td style="text-align:left;vertical-align:top;padding-right:15px;">';

					ih += '<table cellpadding=0 cellspacing=0 border=0><tr>';
					
						//hue saturation rainbow

						ih += '<td style="padding-right:15px;">';
						ih += '<div style="position:relative;">'
						ih += '<img id="qmvi_color_hs_down" width=8 height=6 src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_down.gif" style="position:absolute;display:block;margin-top:-8px;margin-left:-3px">';
						ih += '<img id="qmvi_color_hs_right" width=6 height=8 src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" style="position:absolute;display:block;margin-top:-3px;margin-left:-8px">';
						ih += '<div class="qmvi-colordialog-border" style="position:relative;">';

						ih += '<div id="qmvi_color_hs_div" onselectstart="qm_kille(event)" onmouseup="qmv_color_huesaturation_mouseup(event,this)" onmousedown="qmv_color_huesaturation_mousedown(event,this)" onmousemove="qmv_color_huesaturation_mousemove(event,this)" style="position:relative;display:block;background-image:url('+qmv.base+'images/rgb.jpg);width:175px;height:187px;position:relative;overflow:hidden;">';	
						ih += '<div id="qmvi_color_hs_crosshair" style="width:19px;height:19px;background-image:url('+qmv.base+'images/color_crosshair.gif);position:absolute;display:block;margin-top:-10px;margin-left:-10px"></div>';
						ih += '</div>';

						ih += '</div>'
	
						ih += '</div>';
						ih += '</div>';
						ih += '</td>';


						//brightness rainbow

						ih += '<td style="">';
						ih += '<div class="qmvi-colordialog-border" style="position:relative;">';	
						ih += '<img id="qmvi_color_bright_right" width=6 height=8 src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" style="position:absolute;margin-top:-3px;margin-left:-8px">';	
						ih += '<div onselectstart="qm_kille(event)" id="qmvi_color_bright_bar" onmouseup="qmv_color_brightness_mouseup(event,this)" onmousedown="qmv_color_brightness_mousedown(event,this)" onmousemove="qmv_color_brightness_mousemove(event,this)" style="position:relative;width:10px;height:187px;">';

							for (k=0;k<17;k++)
								ih += '<div class="qmvi-colordialog-brightbar-parts"> </div>';

						ih += '</div>';
						ih += '</div>';
						ih += '</td>';

					ih += '</tr></table>';
					

				ih += '</td>';	



				ih += '<td style="vertical-align:top;width:100%;">';

					//color indicator
					ih += '<div onclick="qmvi_color_open_recent(this)" id="qmvi_color_indicator" class="qmvi-colordialog-border" style="text-align:right;height:25px;background-color:#000000;">';

					if (qmv.color_recent && qmv.color_recent.length)
						ih += '<img style="margin-top:3px;margin-right:3px;" src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_plus.gif" width=11 height=11>';

					ih += '</div>';

					ih += '<div id="qmvi_color_recent" class="qmvi-colordialog-border" style="border-top-width:0px;visibility:hidden;position:absolute;background-color:#888888;">';
					ih += '</div>';
				

					ih += '<div style="font-size:1px;height:20px;"></div>';


					//value display
					ih += '<div>';
						ih += '<table cellpadding=0 cellspacing=0 border=0 width="100%" style=""><tr>';



							//rgb values
							
							ih += '<td style="">';

								
								ih += '<form id="qmvi_color_switches" style="display:block;margin:0px;padding:0px;">';
								ih += '<table cellpadding=0 cellspacing=0 border=0>';

									ih += '<tr>';
									ih += '<td id="qmvi_cdialog_rtext" class="qmvi-common qmvi-colordialog-titles" style="width:45px;padding-right:10px;text-align:right;">Red:</td>';
									ih += '<td style="width:35px;"><input onkeypress="qmv_color_field_onchange_enter(event,this)" onfocus="this.pvalue = this.value" onchange="qmv_color_field_onchange(event,this)" class="qmvi-common qmvi-colordialog-inputs" id="qmv_cdialog_r" type="text" style="width:30px;"></td>'
									ih += '<td style="width:15px">&nbsp;</td>';
									ih += '<td style="padding-right:2px;"><input onfocus="if (!qmad.br_ie)blur()" tabindex=-1 onclick="qmv_color_set_fields()" name="qmvi_color_switch" type="radio" checked value="RGB"></td>'
									ih += '<td style="width:20px;" class="qmvi-common qmvi-colordialog-titles" style="text-align:left;">RGB</td>';
									ih += '</tr>';
									
									ih += '<tr><td style="font-size:1px;height:2px;">&nbsp;</td></tr>';

									ih += '<tr>';
									ih += '<td id="qmvi_cdialog_gtext" class="qmvi-common qmvi-colordialog-titles" style="padding-right:10px;text-align:right;">Green:</td>';
									ih += '<td><input onkeypress="qmv_color_field_onchange_enter(event,this)" onfocus="this.pvalue = this.value" onchange="qmv_color_field_onchange(event,this)" class="qmvi-common qmvi-colordialog-inputs" id="qmv_cdialog_g" type="text" style="width:30px;"></td>'
									ih += '<td style="width:15px">&nbsp;</td>';
									ih += '<td style="padding-right:2px;"><input onfocus="if (!qmad.br_ie)blur()" tabindex=-1 onclick="qmv_color_set_fields()" name="qmvi_color_switch" type="radio" value="HEX"></td>'
									ih += '<td class="qmvi-common qmvi-colordialog-titles" style="text-align:left;">HEX</td>';
									ih += '</tr>';
									
									ih += '<tr><td style="font-size:1px;height:2px;">&nbsp;</td></tr>';

									ih += '<tr>';
									ih += '<td id="qmvi_cdialog_btext" class="qmvi-common qmvi-colordialog-titles" style="padding-right:10px;text-align:right;" >Blue:</td>';
									ih += '<td><input onkeypress="qmv_color_field_onchange_enter(event,this)" onfocus="this.pvalue = this.value" onchange="qmv_color_field_onchange(event,this)" class="qmvi-common qmvi-colordialog-inputs" id="qmv_cdialog_b" type="text" style="width:30px;"></td>'
									ih += '<td style="width:15px">&nbsp;</td>';
									ih += '<td style="padding-right:2px;"><input onfocus="if (!qmad.br_ie)blur()" tabindex="-1" onclick="qmv_color_set_fields()" name="qmvi_color_switch" type="radio" value="HSB"></td>'
									ih += '<td class="qmvi-common qmvi-colordialog-titles" style="text-align:left;">HSB</td>';
									ih += '</tr>';
																	
								
								ih += '</table>';
								ih += '</form>';
									
							ih += '</td>';
							
						ih += '</tr></table>';

					ih += '</div>';


					
					if (qmad.br_ie)
						ih += '<div style="font-size:1px;height:5px;"></div>';	
					else
						ih += '<div style="font-size:1px;height:15px;"></div>';	
					

					ih += '<div>';

						ih += '<fieldset class="qmvi-colordialog-border qmvi-colordialog-titles" style="margin:0px;padding:10px;text-align:center;"><legend class="qmvi-common" style="color:#0033dd;margin:0px;">Apply Value As</legend>';

						if (qmad.br_ie)
							ih += '<div style="font-size:1px;height:5px;"></div>';	

						ih += 'HEX: <input onfocus="if (!qmad.br_ie)blur()" tabindex="-1" id="qmvi_color_apply_type_hex" name="qmvi_color_apply_type" value="HEX" checked type="radio" style="margin-bottom:-1px;">     RGB: <input onfocus="if (!qmad.br_ie)blur()" tabindex="-1" id="qmvi_color_apply_type_rgb" name="qmvi_color_apply_type" value="RGB" type="radio" style="margin-bottom:-1px;">';

						ih += '</fieldset>';

					ih += '</div>'


				ih += '</td>';	

			

			ih += '</tr></table>';
			ih += '</div>';


			
		

	}
	else if (type=="save")
	{

		
		buttons = "Done";
		title = "Save Menu";
		width = 600;

		

		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">Copy QuickMenu Contents to a New HTML Document</div>';

			

			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">Paste the contents below to a blank document with a .html extension (use NotePad, FrontPage, Dreamweaver, or similar).  Open in a browser to test and visually edit the saved menu.</div>';



			ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_save_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';

			ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
			ih += '<textarea wrap="off" onkeypress="qm_kille(event,true)" id="qmvi_publish_save_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;"></textarea>';
			ih += '</div>';

			

		ih += '</div>';


		


	}
	else if (type=="import")
	{

		
		buttons = "Generate Menu|Cancel";
		title = "Import Menu";
		width = 600;

		

		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">Import Third Party Menu Structures</div>';

			
			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">Paste the contents of an existing list based (<UL><LI>) menu structure below.  Note: Not all structures can be imported, some structures will be limited to the main menu items only.</div>';

			ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
			ih += '<textarea wrap="off" onkeypress="qm_kille(event,true)" id="qmvi_import_stucture_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;"></textarea>';
			ih += '</div>';

			

		ih += '</div>';


	}
	else if (type=="publish1")
	{



		qmv_set_publish_menus_object();
		
		/*
		if (!qmv.publish)
		{
			qmv.publish = new Object();
			
			qmv.publish.css_type = "inpage";
			qmv.publish.code_type = "inpage";
			qmv.publish.structure_type = "inpage";

		}


		qmv.publish.smenus = new Array();
		qmv.publish.smenus_pos=0;

		for (var i=0;i<10;i++)
		{

			if (document.getElementById("qm"+i))
				qmv.publish.smenus.push(i);
			
		}
		*/

		buttons = "Next|Cancel";
		title = "Publish Wizard";
		width = 600;
		
		qmv.publish.page = 1;


		ih += '<div style="padding:15px 5px 5px 5px;">';
		

			ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">This wizard produces the code sections necessary to run QuickMenu in your documents.</div>';

			ih += '<div style="padding-bottom:10px;">';

				ih+='<div style="width:100px;text-align:center;white-space:nowrap;position:absolute;left:250px;font-size:12px;padding:2px 0px 2px 0px;background-color:#d3d1dd;border-width:1px;border-color:#aaaaaa;border-style:solid;">In Page</div>';
				ih+='<div style="width:100px;text-align:center;white-space:nowrap;position:absolute;left:400px;font-size:12px;padding:2px 0px 2px 0px;background-color:#d3d1dd;border-width:1px;border-color:#aaaaaa;border-style:solid;"">External File</div>';

			ih += '</div>';

			ih += '<div style="padding:30px 20px 10px 20px;">'


				ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


					ih += '<tr>';
 
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;width:180px;text-align:right;">CSS Style Sheet:</td>';
						ih += '<td><div style="width:70px;"> </div></td>';
						ih += '<td style="width:100px;"><input id="qmvi_publish_css_inpage" name="qmvi_publish_css" type="radio"></td>';
						ih += '<td><div style="width:55px;"> </div></td>';
						ih += '<td style="width:100px;"><input id="qmvi_publish_css_external" name="qmvi_publish_css" type="radio"></td>';

					ih += '</tr>';

					ih += '<tr><td><div style="font-size:1px;height:15px;"></div></td></tr>'

					ih += '<tr>';

						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;width:180px;text-align:right;">Menu Code / Add-ons:</td>';
						ih += '<td><div style="width:70px;"> </div></td>';
						ih += '<td style="width:100px;"><input id="qmvi_publish_code_inpage" name="qmvi_publish_code" type="radio"></td>';
						ih += '<td><div style="width:55px;"> </div></td>';
						ih += '<td style="width:100px;"><input id="qmvi_publish_code_external" name="qmvi_publish_code" type="radio"></td>';

					ih += '</tr>';
					
					ih += '<tr><td><div style="font-size:1px;height:15px;"></div></td></tr>'

					ih += '<tr>';

						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;width:180px;text-align:right;">Menu Structure:</td>';
						ih += '<td><div style="width:70px;"> </div></td>';
						ih += '<td style="width:100px;"><input id="qmvi_publish_structure_inpage" name="qmvi_publish_structure" type="radio"></td>';
						ih += '<td><div style="width:55px;"> </div></td>';
						ih += '<td style="width:100px;"><input onclick="qmv_warn_external_pure(\'qmvi_publish_structure_type_pure\')" id="qmvi_publish_structure_external" name="qmvi_publish_structure" type="radio"></td>';

					ih += '</tr>';

					


				ih += '</table>';

			ih += '</div>'


			ih += '<div style="font-size:1px;height:25px;"></div>';
			
			ih += '<div style="padding-bottom:10px;">';

				ih+='<div style="width:100px;text-align:center;white-space:nowrap;position:absolute;left:250px;font-size:12px;padding:2px 0px 2px 0px;background-color:#d3d1dd;border-width:1px;border-color:#aaaaaa;border-style:solid;">Pure CSS</div>';
				ih+='<div style="width:100px;text-align:center;white-space:nowrap;position:absolute;left:400px;font-size:12px;padding:2px 0px 2px 0px;background-color:#d3d1dd;border-width:1px;border-color:#aaaaaa;border-style:solid;"">Hybrid</div>';

			ih += '</div>';

			ih += '<div style="padding:30px 20px 10px 20px;">'


				ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


					ih += '<tr>';
 
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;width:180px;text-align:right;">(<span style="cursor:help;color:#ff3300;" onclick="qmv_html_structure_help()">?</span>) HTML Structure Type:</td>';
						ih += '<td><div style="width:70px;"> </div></td>';
						ih += '<td style="width:100px;"><input onclick="qmv_warn_external_pure(\'qmvi_publish_structure_external\')" id="qmvi_publish_structure_type_pure" name="qmvi_publish_struct_type" type="radio"></td>';
						ih += '<td><div style="width:55px;"> </div></td>';
						ih += '<td style="width:100px;"><input id="qmvi_publish_structure_type_hybrid" name="qmvi_publish_struct_type" type="radio" checked></td>';

					ih += '</tr>';

				ih += '</table>';

			ih += '</div>'

			ih += '<div style="font-size:1px;height:10px;"></div>';

		ih += '</div>';


		qmv_track_it("publish_type");


	}
	else if (type=="publish2")
	{

		
		buttons = "Previous|Next|Cancel";
		title = "Publish Wizard";
		width = 600;

		qmv.publish.page = 2;


		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">CSS Stylesheet</div>';

			if (qmv.publish.css_type=="external")
			{

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">1:</font> Copy this stylesheet file reference to your documents head (<HEAD></HEAD>).  Change the file name and location reference (href=\'<font style="color:#dd3300;">quickmenu_styles.css</font>\') to match the new .css file you will create below.</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_stylesheet_file_reference\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container" style="height:16px;">';
					ih += '<input id="qmvi_publish_stylesheet_file_reference" class="qmvtree-input qmvtree-input-dialog" style="" value="<link rel=\'stylesheet\' type=\'text/css\' href=\'quickmenu_styles.css\'/>">';
					ih += '</div>';
				ih += '</div>';

				ih += '<div style="font-size:1px;height:30px;"></div>';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">2:</font> Paste the styles below to a new plain text file with a name, location and .css extension to match the file reference in step 1.  (Use Notepad or similar to ensure a plain text format.)</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_stylesheet_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
					ih += '<textarea wrap="off" onkeypress="qm_kille(event,true)" id="qmvi_publish_stylesheet_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;">'+qmv_pubgen_css(true)+'</textarea>';
					ih += '</div>';
				ih += '</div>';

				ih += '<div style="font-size:1px;height:10px;"></div>';
			}
			else
			{

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">Paste this style sheet below within the head (<HEAD>paste here</HEAD>) of your HTML document. </div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_stylesheet_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
					ih += '<textarea wrap="off"onkeypress="qm_kille(event,true)" id="qmvi_publish_stylesheet_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;">'+qmv_pubgen_css(false)+'</textarea>';
					ih += '</div>';
				ih += '</div>';

			}

		ih += '</div>';

		qmv_track_it("publish_css");


	}
	else if (type=="publish3")
	{

		
		buttons = "Previous|Next|Cancel";
		title = "Publish Wizard";
		width = 600;

		qmv.publish.page = 3;


		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">Menu Code / Add-on\'s</div>';

			if (qmv.publish.code_type=="external")
			{

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">1:</font> Copy this JavaScript file reference to your documents head (<HEAD></HEAD>). Change the file name and location reference (src=\'<font style="color:#dd3300;">quickmenu.js</font>\') to match the new .js file you will create below.</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_file_reference\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container" style="height:16px;">';
					ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_file_reference" class="qmvtree-input qmvtree-input-dialog" style="" value="<scr'+'ipt type=\'text/javascript\' src=\'quickmenu.js\'></scr'+'ipt>">';
					ih += '</div>';
				ih += '</div>';

				ih += '<div style="font-size:1px;height:30px;"></div>';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">2:</font> Paste the script below to a new plain text file with a name, location and .js extension to match the file reference in step 1.  (Use Notepad or similar to ensure a plain text format.)</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
					ih += '<textarea wrap="off" onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;">'+qmv_pubgen_javascript(true)+'</textarea>';
					ih += '</div>';
				ih += '</div>';

				ih += '<div style="font-size:1px;height:10px;"></div>';
			}
			else
			{

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">Paste the script below within the head (<HEAD></HEAD>) of your HTML document. </div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
					ih += '<textarea wrap="off"onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;">'+qmv_pubgen_javascript(false)+'</textarea>';
					ih += '</div>';
				ih += '</div>';

			}

		ih += '</div>';

		qmv_track_it("publish_code");

	}
	else if (type=="publish4")
	{

				
		buttons = "Previous|Next|Cancel";
		title = "Publish Wizard";
		width = 600;

		qmv.publish.page = 4;


		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">Menu Structure ['+qmv_pubgen_get_number_word(qmv.publish.smenus_pos+1)+' Menu]</div>';

			if (qmv.publish.structure_type=="external")
			{

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">1:</font> Paste the JavaScript file reference below inside a valid HTML tag within the body (<BODY></BODY>) of your web page.  The menu will position itself as a block level element at the point of insertion, similar to a table or div.<br><br>Change the file reference (src=\'<font style="color:#dd3300">qm_structure'+(qmv.publish.smenus_pos+1)+'.js</font>\') to correctly point to the location and name of the new .js file you will create in step 2.</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_file_reference\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container" style="height:16px;">';
					ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_file_reference" class="qmvtree-input qmvtree-input-dialog" style="" value="<scr'+'ipt type=\'text/javascript\' src=\'qm_structure'+(qmv.publish.smenus_pos+1)+'.js\'></scr'+'ipt>">';
					ih += '</div>';
				ih += '</div>';

				ih += '<div style="font-size:1px;height:30px;"></div>';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">2:</font> Paste the script below to a new plain text file with a name, location and .js extension to match the file reference in step 1.  (Use Notepad or similar to ensure a plain text format.)</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_structure_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
					ih += '<textarea wrap="off" onkeypress="qm_kille(event,true)" id="qmvi_publish_structure_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;"></textarea>';
					ih += '</div>';
				ih += '</div>';

				ih += '<div style="font-size:1px;height:10px;"></div>';
			}
			else
			{

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">Paste this HTML inside a valid tag within the body (<BODY></BODY>) of your web page.  The menu positions itself as a block level element at the point of insertion, similar to a table or div.</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_structure_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
					ih += '<textarea wrap="off"onkeypress="qm_kille(event,true)" id="qmvi_publish_structure_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;"></textarea>';
					ih += '</div>';
				ih += '</div>';

			}

		ih += '</div>';

		qmv_track_it("publish_structure");


	}
	else if (type=="publish5")
	{

		buttons = "Previous|Next|Cancel";
		title = "Publish Wizard";
		width = 600;

		qmv.publish.page = 5;


		ih += '<div style="padding:5px;">';


			if (!qmv.pure)
			{
				ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Optional Noscript Support</div>';
				ih += '<div style="padding:10px;">';

					ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">Noscript support allows users to navigate the entire menu structure when JavaScript is turned off in the browser.  The noscript version of the menu appears as a hierarchial list in a scrollable window with a default height of 200px.  You can modify the height by editing the hieght setting within the noscript tag.</div>';
					ih += '<div style="font-size:1px;height:10px;"></div>';

					ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">To add noscript support paste the following tag wihtin the head of your HTML document. The tag may be used in a server side include for delivery to multiple pages.</div>';

					ih += '<div style="padding:0px 15px 0px 15px;">';
						ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_structure_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
						ih += '<div class="qmvtree-input-container-dialog" style="height:65px;">';
						ih += '<textarea wrap="off"onkeypress="qm_kille(event,true)" id="qmvi_publish_structure_content" class="qmvtree-input qmvtree-input-dialog" style="height:65px;">'+qmv_pubgen_noscript_tag()+'</textarea>';
						ih += '</div>';
					ih += '</div>';

				ih += '</div>';
			}


			
			if (qmv.free_use)
			{

				ih += '<div style="font-size:1px;height:20px;"></div>';
				ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Optional <span style="color:#dd3300;">Free Use</span> Link</div>';
				ih += '<div style="padding:10px;">';

					ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">To disable the purchase reminder and use the menu for free (no time limits or restrictions!) add this link anywhere within the body of your web page.</div>';

					ih += '<div style="padding:0px 15px 0px 15px;">';
						ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_structure_content_free\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
						ih += '<div class="qmvtree-input-container-dialog" style="height:65px;">';
						ih += '<textarea wrap="off"onkeypress="qm_kille(event,true)" id="qmvi_publish_structure_content_free" class="qmvtree-input qmvtree-input-dialog" style="height:65px;">'+qmv_pubgen_free_use_link()+'</textarea>';
						ih += '</div>';
					ih += '</div>';

				ih += '</div>';

			}

		ih += '</div>';

		


	}
	else if (type=="publish6")
	{

		buttons = "Previous|Finished";
		title = "Publish Wizard";
		width = 600;

		qmv.publish.page = 6;


		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Finished</div>';


			ih += '<div style="padding:10px;">';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;font-weight:bold;color:#dd3300;">Congratulations!</div>';
				ih += '<div style="font-size:1px;height:10px;"></div>';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">Your menu is ready to view, open your document in a browser to test the menu.  If the menu is not working, double check all file references and your insertion point within the body for accuracy.</div>';
				ih += '<div style="font-size:1px;height:20px;"></div>';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;text-decoration:underline;">Optional Visual Interface</div>';
				ih += '<div style="font-size:1px;height:10px;"></div>';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;">The QuickMenu visual interface may optionally be added to your document by pasting this script reference directly before the closing body tag (paste here..&lt/BODY&gt)</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_congrats_visual_tag\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container" style="height:16px;">';
					ih += '<input onblur="qmv_publish_blur_input(event,this)" onfocus="qmv_publish_focus_input(event,this)" type="text" onkeypress="qm_kille(event,true)" class="qmvtree-input qmvtree-input-dialog" style="" id="qmvi_publish_congrats_visual_tag" value="<sc'+'ript type=\'text/javascript\' src=\'http://www.opencube.com/qmv4/qm_visual.js\'></scr'+'ipt>">';
					ih += '</div>';
				ih += '</div>';

			ih += '</div>';

		ih += '</div>';


		qmv_track_it("publish_done");


	}
	else if (type=="publish10")
	{

		


		buttons = "Previous|Next|Cancel";
		title = "Publish Wizard";
		width = 600;

		qmv.publish.page = 10;


		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">Compact Publish to Single External File</div>';
			
			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">1:</font> Paste the JavaScript file reference below inside a valid HTML tag within the body (<BODY></BODY>) of your web page.  The menu will position itself as a block level element at the point of insertion, similar to a table or div.<br><br>Change the file reference (src=\'<font style="color:#dd3300">quickmenu.js</font>\') to correctly point to the location and name of the new .js file you will create below.</div>';

			ih += '<div style="padding:0px 15px 0px 15px;">';
				ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_file_reference\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
				ih += '<div class="qmvtree-input-container" style="height:16px;">';
				ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_file_reference" class="qmvtree-input qmvtree-input-dialog" style="" value="<scr'+'ipt type=\'text/javascript\' src=\'quickmenu.js\'></scr'+'ipt>">';
				ih += '</div>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:20px;"></div>';

			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">2:</font> Paste the script below to a new plain text file with a name, location and .js extension to match the file reference in step 1.  (Use Notepad or similar to ensure a plain text format.)</div>';

			ih += '<div style="padding:0px 15px 0px 15px;">';
				ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
				ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
				ih += '<textarea wrap="off" onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;">'+qmv_pubgen_all_external(true)+'</textarea>';
				ih += '</div>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:10px;"></div>';
			
		ih += '</div>';


		qmv_track_it("publish_external");

	}
	else if (type=="quick_publish")
	{

		qmv_set_publish_menus_object();

		buttons = "Previous|Next|Cancel";
		title = "Quick Publish";
		width = 600;



		var c = qmv_lib_get_menu_count();
		if (c>1)
		{

			buttons = "Close";
			title = "Quick Publish";
			width = 500;


			ih += '<div style="padding:5px;">';	

				ih += 'Warning! Quick publish is not compatible with multiple menu systems, to publish multiple menus instead use the \'Custom Publish Wizard\'.';			

			ih += '</div>';

		}
		else
		{

			buttons = "Done";
			title = "Quick Publish";
			width = 640;
			
			var spure = qmv.pure;
			qmv.pure = false;
		
			ih += '<div style="padding:5px;">';	

				ih += '<div class="qmvi-publish-title" style="margin-bottom:20px;">Compact Publish to Single External File</div>';
			
				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">1:</font> Paste the JavaScript file reference below inside a valid HTML tag within the body (<BODY></BODY>) of your web page.  The menu will position itself as a block level element at the point of insertion, similar to a table or div. Point the reference (src=\'<font style="color:#dd3300">quickmenu.js</font>\') to the location and name of the new .js file you will create below.</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_file_reference\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container" style="height:16px;">';
					ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_file_reference" class="qmvtree-input qmvtree-input-dialog" style="" value="<scr'+'ipt type=\'text/javascript\' src=\'quickmenu.js\'></scr'+'ipt>">';
					ih += '</div>';
				ih += '</div>';

				ih += '<div style="font-size:1px;height:20px;"></div>';

				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">2:</font> Paste the script below to a new plain text file with a name, location and .js extension to match the file reference in step 1.  (Use Notepad or similar to ensure a plain text format.)</div>';

				ih += '<div style="padding:0px 15px 0px 15px;">';
					ih += '<table celpadding=0 cellspacing=0><tr><td><div class="qmvi-common qmvi-dialog-input-title" onclick="qmv_publish_focus_input(event,document.getElementById(\'qmvi_publish_javascript_content\'))" style="color:#0033dd;margin:10px 6px 4px 0px;cursor:default;"><img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/color_arrow_right.gif" width=6 height=8 style="margin-right:4px;">Select Contents</div></td></tr></table>';
					ih += '<div class="qmvtree-input-container-dialog" style="height:200px;">';
					ih += '<textarea wrap="off" onkeypress="qm_kille(event,true)" id="qmvi_publish_javascript_content" class="qmvtree-input qmvtree-input-dialog" style="height:200px;">'+qmv_pubgen_all_external(true)+'</textarea>';
					ih += '</div>';
				ih += '</div>';


				ih += '<div style="font-size:1px;height:20px;"></div>';
				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="padding-bottom:4px;color:#333333;"><font style="font-weight:bold;">Warning:</font> This quick publish option does not save search friendly content to your pages or use pure CSS for no-script browsers.  Use the custom publish wizard to enable these features.</div>';
				ih += '<div style="font-size:1px;height:10px;"></div>';
			
			ih += '</div>';


			qmv.pure = spure;
		}

		

	}
	else if (type=="url")
	{

		
		buttons = "OK|Cancel|Apply";
		title = "Anchor Properties";
		width = 350;
		

		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">Text / HTML</div>';
			ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;">';
			ih += '<input type="text" onkeypress="qm_kille(event,true)" category="texturl" id="qmvi_df_urlp_text" class="qmvtree-input" style="">';
			ih += '</div>';

			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">URL</div>';
			ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;">';
			ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_df_urlp_url" class="qmvtree-input" style="">';
			ih += '</div>';

			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">Target</div>';
			ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;">';
			ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_df_urlp_target" class="qmvtree-input" style="">';
			ih += '</div>';
			
			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">Title Text (Displays as Tooltip)</div>';
			ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;">';
			ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_df_urlp_title" class="qmvtree-input" style="">';
			ih += '</div>';

		ih += '</div>';


	}
	else if (type=="multi")
	{

		buttons = "OK|Cancel|Apply";
		title = qmv.multi.title;
		width = 330;
		

		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">'+qmv.multi.desc+'</div>';
			ih += '<div style="margin-bottom:10px;">';
				ih += '<select id="qmvi_multi_value" class="qmvi-common qmvtree-input-container" style="width:300px;height:20px;font-size:12px">';


					for (var k=0;k<qmv.multi.show.length;k++)
					{

						ih += '<option value="'+qmv.multi.vals[k]+'">'+qmv.multi.show[k]+'</option>';

					}

				ih += '</select>';
			ih += '</div>';

		ih += '</div>';

	}
	else if (type=="edge")
	{
		

		buttons = "OK|Cancel|Apply";
		title = qmv.edge.title;
		width = qmv.edge.width;
		

		ih += '<div style="padding:5px;">';


			ih += '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="">';

				ih += '<tr>';

					if (qmv.edge.corners)
					{
						ih += qmv_dialog_edge_part("Top",qmv.edge.dtype,"Top Left");
						ih += '<td><div style="width:20px;"></div></td>'					
						ih += qmv_dialog_edge_part("Left",qmv.edge.dtype,"Bottom Left");

					}
					else
					{
						ih += qmv_dialog_edge_part("Top",qmv.edge.dtype);
						ih += '<td><div style="width:20px;"></div></td>'					
						ih += qmv_dialog_edge_part("Left",qmv.edge.dtype);
					}

				ih += '<tr>';


				ih += '<tr><td><div style="font-size:1px;height:20px;"></div></td></tr>';

				ih += '<tr>';

					if (qmv.edge.corners)
					{
						ih += qmv_dialog_edge_part("Bottom",qmv.edge.dtype,"Bottom Right");
						ih += '<td><div style="width:20px;"></div></td>'					
						ih += qmv_dialog_edge_part("Right",qmv.edge.dtype,"Top Right");
					}
					else
					{
						ih += qmv_dialog_edge_part("Bottom",qmv.edge.dtype);
						ih += '<td><div style="width:20px;"></div></td>'					
						ih += qmv_dialog_edge_part("Right",qmv.edge.dtype);

					}

				ih += '<tr>';
			
			ih += '</table>';


		ih += '</div>';

	}
	else if (type=="inherit")
	{

		

		buttons = "OK|Cancel|Apply";
		title = "Style Duplication and Inheritance";
		width = 420;
		

		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Apply the <span style="font-weight:bold;">'+qmv.tinherit.rule0.sname+'</span> style to the following.</div>';

			ih += '<div style="padding:10px 20px 00px 20px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


				var tic=0;
				var tic_obj;
				while (tic_obj = qmv.tinherit["rule"+tic])
				{
				
					ih += '<tr>';
 
						ih += '<td><input id="qmvi_inherit_options'+tic+'" type="checkbox" '+tic_obj.defstate+' ></td>';
						ih += '<td><div style="width:10px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">'+tic_obj.desc+'</td>';

					ih += '</tr>';

					ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

					tic++;
				}


			ih += '</table>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:10px;"></div>';	

		ih += '</div>';

		

	}
	else if (type=="options")
	{

		buttons = "OK|Cancel|Apply";;
		title = "Options";
		width = 400;
		
		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Activate QuickMenu</div>';

			ih += '<div style="padding:5px;">';
	
				ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">Unlock Code</div>';
				ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;">';
				ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_df_options_unlock" class="qmvtree-input" style="">';
				ih += '</div>';

			ih += '</div>';

			//ih += '<div style="padding:5px;">';

				//ih += 'Free Use <span style="color:#333333;">(requires link to opencube in HTML page <span onclick="qmvi_options_free_use_info()" style="cursor:pointer;cursor:hand;text-decoration:underline;color:#dd3300;">?</span>)</span> <input onfocus="if (!qmad.br_ie)blur()" tabindex="-1" id="qmvi_dg_options_free_use" value="HEX" type="checkbox" style="margin-bottom:-1px;">'
				//ih += '<div style="font-size:1px;height:10px;"></div>';	
				
			//ih += '</div>';

			ih += '<div style="font-size:1px;height:10px;"></div>';			

			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Interface Settings</div>';
			
			ih += '<div style="padding:5px 5px 0px 5px;">';

				ih += '<table cellpadding=0 cellspacing=0><tr>';
				ih += '<td><input onfocus="if (!qmad.br_ie)blur()" tabindex="-1" id="qmvi_dg_options_auto_collapse" value="HEX" type="checkbox"></td><td> Auto Collapse Tree Menu</td>'
				ih += '</tr></table>';
								
			ih += '</div>';
			ih += '<div style="padding:5px 5px 0px 5px;">';

				ih += '<table cellpadding=0 cellspacing=0><tr>';
				ih += '<td><input onfocus="if (!qmad.br_ie)blur()" tabindex="-1" id="qmvi_dg_options_hide_selected_box" value="HEX" type="checkbox"></td><td> Hide Item Selected Box</td>'
				ih += '</tr></table>';
						
			ih += '</div>';
			ih += '<div style="font-size:1px;height:10px;"></div>';	

			ih += '<div style="font-size:1px;height:10px;"></div>';	

		ih += '</div>';


	}
	else if (type=="subposition")
	{

		buttons = "OK|Cancel";;
		title = "Sub Menu Positioning";
		width = 400;
		
		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Positioning Options</div>';

			ih += '<div style="padding:10px 20px 00px 20px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


				ih += '<tr>';
 
					ih += '<td><input id="qmvi_pos_options1" name="qmvi_pos_options" type="radio" checked value="select"></td>';
					ih += '<td><div style="width:20px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Apply to the selected sub menu only.</td>';


				ih += '</tr>';

				ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

				ih += '<tr>';

					ih += '<td><input id="qmvi_pos_options2" name="qmvi_pos_options" type="radio" value="all"></td>';
					ih += '<td><div style="width:20px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Apply this positioning to all sub menus.</td>';

				ih += '</tr>';
					
				ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

				ih += '<tr>';

					ih += '<td><input id="qmvi_pos_options3" name="qmvi_pos_options" type="radio" value="reset"></td>';
					ih += '<td><div style="width:20px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Reset the selected sub menu to its default position.</td>';


				ih += '</tr>';

				ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

				ih += '<tr>';

					ih += '<td><input id="qmvi_pos_options4" name="qmvi_pos_options" type="radio" value="reset all"></td>';
					ih += '<td><div style="width:20px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Reset the default position to 0,0</td>';


				ih += '</tr>';	


			ih += '</table>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:10px;"></div>';	

		ih += '</div>';


	}
	else if (type=="structure")
	{

		buttons = "OK|Cancel";;
		title = "Menu Structure";
		width = 300;
		
		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">HTML Structure Type (<span style="cursor:help;color:#ff3300;" onclick="qmv_html_structure_help()">?</span>)</div>';

			ih += '<div style="padding:10px 20px 00px 20px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


				ih += '<tr>';
 
					ih += '<td><input id="qmvi_structure_type1_pure" name="qmvi_structure_type1" type="radio" checked value="select"></td>';
					ih += '<td><div style="width:20px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Pure CSS Structure (<UL><LI>)</td>';


				ih += '</tr>';

				ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

				ih += '<tr>';

					ih += '<td><input id="qmvi_structure_type1_hybrid" name="qmvi_structure_type1" type="radio" value="all"></td>';
					ih += '<td><div style="width:20px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Hybrid Structure (<DIV><A>)</td>';

				ih += '</tr>';
					
				


			ih += '</table>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:10px;"></div>';	

		ih += '</div>';


	}
	else if (type=="custom_rule")
	{

		if (owner)
		{
			buttons = "Delete Rule|Update Rule|Cancel";
			title = "Edit Custom CSS Rule";
		}
		else
		{
			buttons = "Add Rule|Cancel";
			title = "Add Custom CSS Rule";
		}

		
		width = 510;
		

		ih += qmv_custom_rule_dialog_content();


	}
	else if (type=="apply_custom_class")
	{

		buttons = "Add|Remove|Close";
		title = "Add / Remove Custom Classes";
		
		
		width = 400;
		

		ih += qmv_custom_class_dialog_content();


	}
	else if (type=="applydividers")
	{

		

		buttons = "OK|Apply|Cancel";;
		title = "Globally Apply Dividers";
		width = 410;
		
		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Dividers are applied to your existing items only, if your menu changes after using this feature you can re-apply the dividers with this dialog at any time.  Before the dividers are applied, any existing dividers are automatically removed.<br><br>Apply Dividers Where?</div>';

			ih += '<div style="padding:5px 20px 00px 20px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


				ih += '<tr>';
 
					ih += '<td><input id="qmvi_gld_main" name="qmvi_gld_main" type="checkbox" onclick="qmvi_gld_submain_checked(this)" value="select"></td>';
					ih += '<td><div style="width:7px;"> </div></td>';
					ih += '<td nowrap class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;white-space:nowrap;">Main Menu</td>';

					ih += '<td><div style="width:40px;"> </div></td>';	

					ih += '<td><input id="qmvi_gld_sub" name="qmvi_gld_sub" type="checkbox" onclick="qmvi_gld_submain_checked(this)" checked value="select"></td>';
					ih += '<td><div style="width:7px;"> </div></td>';
					ih += '<td nowrap class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;white-space:nowrap;">Sub Menus</td>';

					ih += '<td><div style="width:40px;"> </div></td>';	

					ih += '<td><input id="qmvi_gld_none" name="qmvi_gld_none" type="checkbox" onclick="qmvi_gld_none_checked(this)" value="select"></td>';
					ih += '<td><div style="width:7px;"> </div></td>';
					ih += '<td nowrap class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;white-space:nowrap;">None</td>';


				ih += '</tr>';
	

			ih += '</table>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:30px;"></div>';	


			
			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Add Extra Dividers...</div>';

			ih += '<div style="padding:5px 20px 00px 20px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


				ih += '<tr>';
 
					ih += '<td><input id="qmvi_gld_above" name="qmvi_gld_above" type="checkbox" value="select"></td>';
					ih += '<td><div style="width:8px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Before the first item in the group.</td>';


				ih += '</tr>';

				ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

				ih += '<tr>';

					ih += '<td><input id="qmvi_gld_below" name="qmvi_gld_below" type="checkbox" value="all"></td>';
					ih += '<td><div style="width:8px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">After the last item in the group.</td>';

				ih += '</tr>';
	

			ih += '</table>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:20px;"></div>';	


		ih += '</div>';


	}
	else if (type=="applystripes")
	{



		buttons = "OK|Apply|Cancel";;
		title = "Globally Apply Sub Striping";
		width = 410;
		
		ih += '<div style="padding:5px;">';

			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:10px;">Striping applies unique styles to every other item, this helps the user individualize a vertical list of options.</div>';
			ih += '<div style="font-size:1px;height:10px;"></div>';	
			
			ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;">Apply Stripes Starting With...</div>';
			ih += '<div style="padding:5px 20px 0px 20px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';


				ih += '<tr>';
 
					ih += '<td><input id="qmvi_stripe_where_first" name="qmvi_stripe_where" type="radio" value="select"></td>';
					ih += '<td><div style="width:8px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">The First Item in the Group</td>';


				ih += '</tr>';

				ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

				ih += '<tr>';

					ih += '<td><input id="qmvi_stripe_where_second" name="qmvi_stripe_where" type="radio" value="all"></td>';
					ih += '<td><div style="width:8px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">The Second Item in the Group</td>';

				ih += '</tr>';

				ih += '<tr><td><div style="font-size:1px;height:5px;"></div></td></tr>'

				ih += '<tr>';

					ih += '<td><input id="qmvi_stripe_where_remove" name="qmvi_stripe_where" type="radio" value="all"></td>';
					ih += '<td><div style="width:8px;"> </div></td>';
					ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Remove All Stripes</td>';

				ih += '</tr>';
	

			ih += '</table>';
			ih += '</div>';

			ih += '<div style="font-size:1px;height:20px;"></div>';	


		ih += '</div>';


	}
	else if (type=="splash")
	{
		
		
		buttons = "<< Previous|Next >>|Close";
		title = "Welcome To Visual QuickMenu (v5.3)";
		width = 612;
		

		qmv.dyk_num = 1;
		qmv.dyk_max = 20;
		
		
		ih += '<div style="padding:10px;">';

			ih += '<div style="margin-bottom:15px;">';			
				ih += '<table cellpadding=0 cellspacing=0 border=0><tr>';
				ih += '<td style="vertical-align:top;"><div style="font-size;1px;height:16px;width:16px;background-image:url('+qmv.base+'help/images/oc_logo.gif);"></div></td>';
				ih += '<td><div style="font-size:1px;width:5px;"></div></td>';
				ih += '<td style="vertical-align:top;padding-top:1px;"><div class="qmvi-dialog-input-title">Did You Know?</div></td>';
				ih += '</tr></table>';
			ih += '</div>';
			
			ih += '<div class="" style="border-style:solid;border-width:1px;border-color:#777777;">';
			ih += "<iframe id='qmvi_dyk_tips' onmouseover='qmv_fix_iframe_title_drag()'  frameborder=none style='border-style:none;background-color:#ffffff;width:570px;height:300px;margin:0px;padding:0px;'></iframe>";
			ih += '</div>';

		ih += '</div>';


	}
	else if (type=="specs")
	{
		
		
		buttons = "Close";
		title = "QuickMenu Specs [All Menus]";
		width = 400;
		var tsize = 0;
		var t;	
		var is_addons = false;	


		ih += '<div style="padding:10px;">';


			ih += '<div class="qmvi-dialog-input-title" style="margin-bottom:15px;">The minimum total size is the size of the menu if using the \'Quick Publish\' feature, custom publish options may add 1-2k of extra size to your menu.</div>';

			ih += '<div class="qmvi-publish-title" style="border-width:1px;padding:3px;border-style:solid;border-color:#888888;background-color:#d5d5d5;color:#333333">CSS</div>';
			ih += '<div style="padding:10px 10px 10px 10px;background-color:#ffffff;border-color:#888888;border-width:0px 1px 0px 1px;border-style:solid;">';
			ih += '<table cellpadding=0 cellspacing=0 width=100%>';	

			t = qmv_pubgen_get_core_css().length;
			t = qmv_lib_get_kilobytes(t,true);
			tsize += parseFloat(t);	
			ih += '<tr>';
			ih += '<td style="vertical-align:top;color:#444444;width:100%;">Core CSS:</td>'
			ih += '<td><div style="font-size:1px;width:15px;"></div></td>'
			ih += '<td style="color:#222222;width:50px;text-align:right;vertical-align:top;padding-bottom:2px;white-space:nowrap;" nowrap>'+t+'</td>'
			ih += '</tr>';

			t = qmv_pubgen_css().length;
			t = qmv_lib_get_kilobytes(t,true);
			tsize += parseFloat(t);
			ih += '<tr>';

			ih += '<td style="vertical-align:top;color:#444444;">Custom Menu Styles (All):</td>'
			ih += '<td><div style="font-size:1px;width:15px;"></div></td>'
			ih += '<td style="color:#222222;width:50px;text-align:right;vertical-align:top;padding-bottom:2px;white-space:nowrap;">'+t+'</td>'
			ih += '</tr>';

			ih += '</table>';
			ih += '</div>';



			ih += '<div class="qmvi-publish-title" style="border-width:1px;padding:3px;border-style:solid;border-color:#888888;background-color:#d5d5d5;color:#333333">JavaScript</div>';
			ih += '<div style="padding:10px 10px 10px 10px;background-color:#ffffff;border-color:#888888;border-width:0px 1px 0px 1px;border-style:solid;">';
			ih += '<table cellpadding=0 cellspacing=0 width=100%>';
			
			t = qmv_get_source_code_core().length;
			t = qmv_lib_get_kilobytes(t,true);
			tsize += parseFloat(t);	
			ih += '<tr>';
			
			ih += '<td style="vertical-align:top;color:#444444;width:100%;">Core Menu Code:</td>'			
			ih += '<td><div style="font-size:1px;width:15px;"></div></td>'
			ih += '<td style="color:#222222;width:50px;text-align:right;vertical-align:top;padding-bottom:2px;white-space:nowrap;" nowrap>'+t+'</td>'
			
			ih += '</tr>';


			if (qmv.pure)
			{	
				

				t = qmv_get_pure_css_javascript().length;
				t = qmv_lib_get_kilobytes(t,true);
				tsize += parseFloat(t);	
				ih += '<tr>';
			
				ih += '<td style="vertical-align:top;color:#444444;width:100%;">Pure CSS / Hybrid Converter:</td>'			
				ih += '<td><div style="font-size:1px;width:15px;"></div></td>'
				ih += '<td style="color:#222222;width:50px;text-align:right;vertical-align:top;padding-bottom:2px;white-space:nowrap;" nowrap>'+t+'</td>'
			
				ih += '</tr>';
			}

			if (qmv.unlock_type && qmv.unlock_type=="single")
			{	
				

				t = qmv_get_single_unlock().length;
				t = qmv_lib_get_kilobytes(t,true);
				tsize += parseFloat(t);	
				ih += '<tr>';
			
				ih += '<td style="vertical-align:top;color:#444444;width:100%;">Single Site Unlock:</td>'			
				ih += '<td><div style="font-size:1px;width:15px;"></div></td>'
				ih += '<td style="color:#222222;width:50px;text-align:right;vertical-align:top;padding-bottom:2px;white-space:nowrap;" nowrap>'+t+'</td>'
			
				ih += '</tr>';
			}


			t = qmv_specs_get_addon_html();
			if (t[1]) is_addons = true;
			tsize += t[1];
			ih += t[0];


			ih += '</table>';
			ih += '</div>';


			
			ih += '<div class="qmvi-publish-title" style="border-width:1px;padding:3px;border-style:solid;border-color:#888888;background-color:#d5d5d5;color:#333333">HTML</div>';
			ih += '<div style="padding:10px 10px 15px 10px;background-color:#ffffff;border-color:#888888;border-width:0px 1px 0px 1px;border-style:solid;">';
			ih += '<table cellpadding=0 cellspacing=0 width=100%>';	

			for (var p=0;p<10;p++)
			{
				if (document.getElementById("qm"+p))
				{
					t = qmv_pubgen_structure(null,null,null,null,p+"l").length;
					t = qmv_lib_get_kilobytes(t,true);
					tsize += parseFloat(t);
					ih += '<tr>';
					ih += '<td style="vertical-align:top;color:#444444;">Menu Structure '+p+':</td>'
					ih += '<td><div style="font-size:1px;width:15px;"></div></td>'
					ih += '<td style="color:#222222;width:50px;text-align:right;vertical-align:top;padding-bottom:2px;white-space:nowrap;" nowrap>'+t+'</td>'

					ih += '</tr>';
				}
			}

			ih += '</table>';
			ih += '</div>';
	
			ih += '<div class="qmvi-publish-title" style="font-weight:normal;border-width:1px;padding:3px;padding-right:15px;border-style:solid;border-color:#888888;background-color:#d5d5d5;color:#333333;text-align:right;">Minimum Total Size</div>';
			ih += '<div style="padding:10px 10px 10px 10px;background-color:#ffffff;border-color:#888888;border-width:0px 1px 1px 1px;border-style:solid;">';
			ih += '<table cellpadding=0 cellspacing=0 width=100%>';	


			var spure = qmv.pure;
			qmv.pure = false;
			qmv_set_publish_menus_object();
			var tquick = qmv_lib_get_kilobytes(qmv_pubgen_all_external(true).length,true);
			qmv.pure = spure;


			//t = qmv_lib_get_kilobytes(parseInt(tsize*1024),true);
			ih += '<tr>';
			ih += '<td align=right style="color:#dd3300;width:100%;text-align:right;vertical-align:top;white-space:nowrap;">'+tquick+'</td>'
			ih += '</tr>';

			ih += '</table>';
			ih += '</div>';


			



		ih += '</div>';


	}
	else if (type=="zoom")
	{
		

		buttons = "OK|Cancel|Apply";
		title = "Text Zoom";
		width = 350;
		

		ih += '<div class="qmvtree-input-container-dialog" style="height:100px;">';
		ih += '<textarea onkeypress="qm_kille(event,true)" id="qmvi_df_zoomt" class="qmvtree-input" style="height:100px;position:relative;width:100%;"></textarea>';
		ih += '</div>';

	}
	else if (type=="warning-undo")
	{

		buttons = "OK";
		title = "Warning!";
		
		ih = message;

	}
	else if (type=="alert")
	{

		buttons = "OK";
		ih = message;

	}	
	else if (type=="question-okcancel")
	{

		buttons = "OK|Cancel";
		ih = message;
	}
	else if (type=="question-yesno")
	{

		buttons = "Yes|No";
		ih = message;
	}
	else if (type=="question-okcancel-input")
	{

		buttons = "ok|cancel";
		
		
		ih += '<div style="padding:5px;">';
	
			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">'+message+'</div>';
			ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;">';
			ih += '<input type="text" onkeypress="qm_kille(event,true)" value="'+defval+'" id="qmvi_ok_input" class="qmvtree-input" style="">';
			ih += '</div>';

		ih += '</div>';



	}
	else if (type=="image")
	{

		buttons = "ok|cancel|apply";
		title = "Image URL";
		width = 540;
		
		

		ih += '<div style="padding:5px;">';
	
			ih += '<div class="qmvi-common qmvi-dialog-input-title" style="margin-bottom:2px;">Define images relative to this document or use absolute (http://...) paths.  We recommend using absolute paths if you plan on publishing to a different folder structure.  This way images will be visible while designing and after publishing.<br><br>Image URL</div>';
			ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;">';
			ih += '<input type="text" onkeypress="qm_kille(event,true)" value="'+defval+'" id="qmvi_dg_image" class="qmvtree-input" style="">';
			ih += '</div>';

		ih += '</div>';

	}
	else if (type.indexOf("help")+1)
	{
				
		buttons = "close";
		title = "Help";
		if (qmad.br_fox)
			width = 634;
		else
			width = 630;


		var rn = document.getElementById("qmvi_help_iframe");
		if (rn)
			rn[qp].removeChild(rn);

		
		var uh = 400;
		if (type=="help-about")
			uh = 260;
		
		
		ih += "<div style='padding:5px;'>";
		ih += "<div>";

		if (message.indexOf("about.html")==-1)
		{
		
			ih += "<div class='qmvi-common' style='margin:0px 0px 6px 0px;'>";	

				ih += "<table cellspacing=0 cellpadding=0 border=0><tr>"			
			
					ih+="<td><div title='Back' onclick=\"qmvi_help_navigate('back')\" class='qmv-icon-buttons' style='padding:0px;font-size:1px;width:18px;height:18px;background-image:url("+qmv.base+"images/help_back.gif);'></td>";
					ih+="<td><div title='Forward' onclick=\"qmvi_help_navigate('forward')\" class='qmv-icon-buttons' style='padding:0px;font-size:1px;width:18px;height:18px;background-image:url("+qmv.base+"images/help_forward.gif);'></td>";
					ih+="<td><div style='font-size:1px;width:5px;'></div></td>";
					ih+="<td><div title='Home' onclick=\"qmvi_help_navigate('home')\" class='qmv-icon-buttons' style='padding:0px;font-size:1px;width:18px;height:18px;background-image:url("+qmv.base+"images/help_home.gif);'></td>";

				
				ih += "</tr></table>";		
		
			ih += "</div>";
		}


		if (!qmv.helpnum)
			qmv.helpnum=1;
		else
			qmv.helpnum++;	

		qmv.base+"help/"+message.substring(5)
		ih += "<iframe src='"+qmv.base+"help/"+message.substring(5)+"' id='qmvi_help_iframe"+qmv.helpnum+"' name='qmvi_help_iframe_window"+qmv.helpnum+"' onmouseover='qmv_fix_iframe_title_drag()' style='background-color:#ffffff;width:600px;height:"+uh+"px;margin:0px;padding:0px;'></iframe>";
				
		ih += "</div>";
		ih += "</div>";

	}

	
	
	document.getElementById("qmvi_"+mg+"dialog_content").innerHTML = ih;
	document.getElementById("qmvi_"+mg+"dialog_title").innerHTML = title;


	//set the buttons
	var bc = document.getElementById("qmvi_"+mg+"dialog_buttons");
	var bs = buttons.split("|");
	var bt = "";
	var addtrue = "";
	if (message) addtrue = ",true";	
	if (message && message.indexOf("help-")+1) addtrue = ",false,true";
		
	for (var i=0;i<bs.length;i++)
		bt += '<input id="qmv_dialog_button'+i+'" onclick="qmv_dialog_button_click(this'+addtrue+')" class="qmvi-common qmvi-dialog-button" type="button" value="'+bs[i]+'">';


	bc.innerHTML = bt;


	var qmd = document.getElementById("qmvi_"+mg+"dialog");
	var shadow = document.getElementById("qmvi_"+mg+"dialog_shadow");




	qmd.type = type;
	qmd.owner = owner;
	qmd.code = code;
	qmd.code1 = code1;

	qmd.style.width = width+"px";
	qmv_lib_center_element_in_window(qmd);
	qmd.style.visibility = "visible";

		
	shadow.style.width = qmd.offsetWidth+"px";
	shadow.style.height = qmd.offsetHeight+"px";
	shadow.style.top = parseInt(qmd.style.top)+3+"px";
	shadow.style.left = parseInt(qmd.style.left)+3+"px";
	shadow.style.visibility = "visible";

	

	if (type=="color")
		qmv_color_init(owner.value);
	else if (type=="url")
		qmv_url_init();
	else if (type=="multi")
		qmv_multi_init(qmd);
	else if (type=="edge")
		qmv_edge_init(qmd);
	else if (type.indexOf("publish")+1)
	{

		if (type.indexOf("quick_publish")==-1)
		{
	
			qmv_publish_init(qmd);
			if (type=="publish4")
			{
				var isext = false;
				if (qmv.publish.structure_type=="external") isext = true;

				
				document.getElementById("qmvi_publish_structure_content").value = qmv_pubgen_structure(isext);
			}
	

		}

	

	}
	else if (type=="options")
		qmv_options_init(qmd);
	else if (type=="image")
		qmv_image_init(qmd);
	else if (type=="save")
	{
		document.getElementById("qmvi_publish_save_content").value = qmv_savegen();
		qmv_track_it("save");
	}
	else if (type=="zoom")
		document.getElementById("qmvi_df_zoomt").value = owner.value;		
	else if (type=="splash")
	{

		var tb0 = document.getElementById("qmv_dialog_button0");
		var tb1 = document.getElementById("qmv_dialog_button1");

		if (qmad.br_ie)
		{
			tb0.ondblclick = tb0.onclick;
			tb1.ondblclick = tb1.onclick;

		}


		if (qmv.dyk_num==1)
			tb0.disabled = true;
		else if (qmv.dyk_max==qmv.dyk_num)
			tb1.disabled = true;
		
		var hi = document.getElementById("qmvi_dyk_tips");
		hi.src = qmv.base+"help/dyk_"+qmv.dyk_num+".html";
		
		
	}
	else if (type=="applydividers")
	{
		qmv_gld_init(qmd);
	}
	else if (type=="applystripes")
	{

		qmv_stripe_dialog_init(qmd);
	}
	else if (type=="structure")
	{
		
		if (qmv.pure)
			document.getElementById("qmvi_structure_type1_pure").checked = true;
		else
			document.getElementById("qmvi_structure_type1_hybrid").checked = true;
	}
	else if (type=="custom_rule")
	{

		if (owner)
		{
			qmv_custom_rule_parse_rule(defval);

		}
		else
			qmv_display_rule_result();

	}
	else if (type=="apply_custom_class")
	{
		qmv_custom_class_list_change(new Object());
	
	}

	if (defbutton && !qmad.br_ie)
	{
		var dbu = qmd.getElementsByTagName("INPUT");
		for (var i=0;i<dbu.length;i++)
		{
			
			if (dbu[i].type == "button" && dbu[i].value==defbutton)
			{
				
				dbu[i].focus();
				break;
			}

		}
	}
	


}

function qmv_dialog_edge_part(name,dtype,title)
{

	var showname = name;
	if (title)
		showname = title;

	var ih;

	ih = '<td style="vertical-align:top;text-align:right;padding:2px 5px 0px 0px;white-space:nowrap;" class="qmvi-common qmvi-dialog-input-title">'+showname+':</td>';
	ih += '<td style="vertical-align:top;width:50%;">';
		ih += '<div class="qmvtree-input-container" style="height:16px;margin-bottom:10px;padding-right:4px;">';
		
		var t1 = "";
		if (qmad.br_ie && !qmad.br_ie7) t1 = "position:absolute;";

		ih += '<input type="text" onkeypress="qm_kille(event,true)" id="qmvi_df_edge_'+name.toLowerCase()+'" class="qmvtree-input qmvtree-input-dialog" style="text-align:right;'+t1+'">';
		ih += '</div>';
	ih += '</td>';

	var addimg1 = "";
	var addimg2 = "";
	if (qmad.br_ie)
	{
		addimg1 = '<img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/spinner_up.gif" width=11 height=7>';
		addimg2 = '<img src="../../../../recursos/QuickMenu/visual_interface/qmv4/'+qmv.base+'images/spinner_down.gif" width=11 height=7>';
	}

	
	ied1 = "";
	ied2 = "";
	if (qmad.br_ie)
	{
		ied1 = 'ondblclick="qmv_evt_edge_spin(\''+name.toLowerCase()+'\',1,\''+dtype+'\')"';
		ied2 = 'ondblclick="qmv_evt_edge_spin(\''+name.toLowerCase()+'\',2,\''+dtype+'\')"';
	}
	
	ih += '<td style="vertical-align:top;padding-left:2px;">';
		ih += '<span '+ied1+' onclick="qmv_evt_edge_spin(\''+name.toLowerCase()+'\',1,\''+dtype+'\')" class="qmvtree-button qmvtree-button-up" style="width:18px;height:9px;">'+addimg1+'</span>';		
		ih += '<span '+ied2+' onclick="qmv_evt_edge_spin(\''+name.toLowerCase()+'\',2,\''+dtype+'\')" class="qmvtree-button qmvtree-button-down" style="width:18px;height:9px;">'+addimg2+'</span>';
	ih+= '</td>'
	

	return ih;
}

function qmv_hide_dialog(is_msg,is_help,is_setbox)
{
	var qmd;
	var shadow;

	if (is_msg)
	{
		qmd = document.getElementById("qmvi_msg_dialog");
		shadow = document.getElementById("qmvi_msg_dialog_shadow");
	}
	else if (is_help)
	{
		qmd = document.getElementById("qmvi_help_dialog");
		shadow = document.getElementById("qmvi_help_dialog_shadow");
		
	}	
	else if (is_setbox)
	{
		qmd = document.getElementById("qmvi_setbox");
		shadow = document.getElementById("qmvi_setbox_shadow");
		
	}
	else
	{
		document.getElementById("qmvi_dialog_content").innerHTML = "";
		qmd = document.getElementById("qmvi_dialog");
		shadow = document.getElementById("qmvi_dialog_shadow");
		
	}

	qmd.style.visibility = "hidden";
	shadow.style.visibility = "hidden";

}


function qmv_dialog_button_click(src,is_msg,is_help)
{

	var type,val;

	if (!src.value)
		type = src;
	else
		type = src.value.toLowerCase();


	
	
	var qmd;
	if (is_msg)
		 qmd = document.getElementById("qmvi_msg_dialog");
	else if (is_help)
		 qmd = document.getElementById("qmvi_help_dialog");
	else
		 qmd = document.getElementById("qmvi_dialog");



	if (!qmd.type) return;
	

	if (qmd.type=="zoom")
	{	

		val = document.getElementById("qmvi_df_zoomt").value;

		if (type=="ok" || type=="apply")
		{
			qmv_tree_set_value(qmd.owner,val);
			var bad_val = qmv_evt_update_tree_value(qmd.owner);
			if (bad_val) return;
		}

	}
	else if (qmd.type=="warning-undo")
	{
		
		if (qmd.owner.prev_value)
			qmd.owner.value = qmd.owner.prev_value;

	}
	else if (qmd.type=="question-okcancel" || qmd.type=="question-okcancel-input")
	{

		if (type=="ok")
			eval(qmd.code);


	}
	else if (qmd.type=="question-yesno")
	{
		
		
		if (type=="yes")
			eval(qmd.code);
		else if (type=="no")
			eval(qmd.code1);
		

	}
	else if (qmd.type=="color")
	{

		if (type=="ok" || type=="apply")
		{
		
			var o_hex = document.getElementById("qmvi_color_apply_type_hex");
			var o_rgb = document.getElementById("qmvi_color_apply_type_rgb");
			
			var hval = "#"+qmv.color_vals.hr+qmv.color_vals.hg+qmv.color_vals.hb;
			if (o_hex.checked)
			{
				qmd.owner.value = hval;
				qmv.color_apply_type = "HEX"
			}
			else
			{

				qmd.owner.value = "rgb("+qmv.color_vals.dr+","+qmv.color_vals.dg+","+qmv.color_vals.db+")";
				qmv.color_apply_type = "RGB"				
			}

			


			var bad_val = qmv_evt_update_tree_value(qmd.owner);
			if (bad_val) return;

			var cform = document.getElementById("qmvi_color_switches");

			if (cform)
			{
				if (cform.qmvi_color_switch[0].checked)
					qmv.color_dispaly_type = "HEX"
				if (cform.qmvi_color_switch[1].checked)
					qmv.color_dispaly_type = "RGB"
				if (cform.qmvi_color_switch[2].checked)
					qmv.color_dispaly_type = "HSB"
			}



		}

	}
	else if (qmd.type=="url")
	{
	

		if (type=="ok" || type=="apply")
		{

			var text = document.getElementById("qmvi_df_urlp_text");
			var url = document.getElementById("qmvi_df_urlp_url");
			var target = document.getElementById("qmvi_df_urlp_target");
			var title = document.getElementById("qmvi_df_urlp_title");
		
			qmv_evt_update_tree_value(text);			

			qmv.cur_item.setAttribute("href",url.value);
			qmv.cur_item.setAttribute("target",target.value);
			qmv.cur_item.setAttribute("title",title.value);
			
			if (qmv.texturl_state=="text")
				qmv_tree_set_value(qmd.owner,text.value);
			else
				qmv_tree_set_value(qmd.owner,url.value);
			

		}	


	}
	else if (qmd.type=="multi")
	{
		if (type=="ok" || type=="apply")
		{
			var multi = document.getElementById("qmvi_multi_value");
			qmv_tree_set_value(qmd.owner,multi.value);

			var bad_val = qmv_evt_update_tree_value(qmd.owner);
			if (bad_val) return;
		}

	}
	else if (qmd.type=="edge")
	{

		if (type=="ok" || type=="apply")
		{

			var top = document.getElementById("qmvi_df_edge_top").value;
			var bottom = document.getElementById("qmvi_df_edge_bottom").value;
			var left = document.getElementById("qmvi_df_edge_left").value;
			var right = document.getElementById("qmvi_df_edge_right").value;


			var t;
			if (qmv.edge.array)
			{
				t = top+", "+right+", "+bottom+", "+left;


			}
			else
			{			
				if (top==bottom==left==right)
					t = top;
				else
					t = top+" "+right+" "+bottom+" "+left;
			}

	
			qmv_tree_set_value(qmd.owner,t);
	
			var bad_val = qmv_evt_update_tree_value(qmd.owner);
			if (bad_val) return;

		}



	}
	else if (qmd.type.indexOf("publish")+1 && qmd.type!="quick_publish")
	{
		
	
		if (qmv.publish.page==1)
		{

			if (document.getElementById('qmvi_publish_css_external').checked)
				qmv.publish.css_type = "external";
			else
				qmv.publish.css_type = "inpage";

			if (document.getElementById('qmvi_publish_code_external').checked)
				qmv.publish.code_type = "external";
			else
				qmv.publish.code_type = "inpage";

			if (document.getElementById('qmvi_publish_structure_external').checked)
				qmv.publish.structure_type = "external";
			else
				qmv.publish.structure_type = "inpage";	
			

			if (document.getElementById('qmvi_publish_structure_type_pure').checked)
				qmv.pure = true;
			else
				qmv.pure = false;


			if (qmv.publish.css_type=="external" && qmv.publish.code_type=="external" && qmv.publish.structure_type == "external" && qmv.publish.smenus.length==1)
			{
				qmv_show_dialog("publish10");
				return;
			}

		}
		else if (qmv.publish.page==4)
		{
			
			if (type=="next" && qmv.publish.smenus_pos<qmv.publish.smenus.length-1)
			{
				qmv.publish.smenus_pos++;
				qmv_show_dialog("publish4");
				return;
			}

			if (type=="previous" && qmv.publish.smenus_pos>0)
			{
				qmv.publish.smenus_pos--;
				qmv_show_dialog("publish4");
				return;
			}
			
			if ((qmv.pure && !qmv.free_use) && type=="next") qmv.publish.page++;

		}
		else if (qmv.publish.page==5)
		{
			
			if (qmv.was10 && type=="previous")
			{	
				qmv_show_dialog("publish10");
				return;
			}

			

		}
		else if (qmv.publish.page==6)
		{
			
			if ((qmv.pure && !qmv.free_use) && type=="previous") qmv.publish.page--;

		}
		else if (qmv.publish.page==10)
		{

			if (type=="cancel")
			{
				qmv_hide_dialog(is_msg);
				return;
			}

			qmv.was10 = true;
			if (type=="next")
			{
				qmv_show_dialog("publish5");
				return;
			}
			else
			{
				qmv_show_dialog("publish1");
				return;
			}

		}


		if (type=="next")
		{
			
			qmv_show_dialog("publish"+(qmv.publish.page+1));

		}
		else if (type=="previous")
		{

			qmv_show_dialog("publish"+(qmv.publish.page-1));

		}
	
	}
	else if (qmd.type=="options")
	{

		if (type=="ok" || type=="apply")
		{
			
			var tc = document.getElementById("qmvi_dg_options_auto_collapse");

			if (tc.checked)
			{
				qmv.tree_collapse = true;
				qmad.qmvtree.ctype = 0;
				qmad.qmvtree.etype = 0;
			}
			else
			{
				qmv.tree_collapse = false;
				qmad.qmvtree.ctype = 0;
				qmad.qmvtree.etype = 0;
			}

			var tc = document.getElementById("qmvi_dg_options_hide_selected_box");

			if (tc.checked)
			{
				qmv.interface_hide_selected_box = true;
				qmv_hide_pointer("qm"+qmv.id);
			}
			else
			{
				qmv.interface_hide_selected_box = false;
				qmv_position_pointer(true);

			}


			/*
			tc = document.getElementById("qmvi_dg_options_free_use");
			if (tc.checked)
				qmv.free_use = true;
			else
				qmv.free_use = false;
			*/



			ul = document.getElementById("qmvi_df_options_unlock");

			
			if (ul.value && (qmv.unlock_orig!=ul.value))
			{
				if (ul.value=="qmu=true")
				{
					qmv.unlock_type = "unlimited";
					qmv.unlock_string = ul.value;

					qmv_show_dialog("alert",null,"<font style='color:#0033dd;'>Congratulations, QuickMenu has been unlocked for unlimited sites!</font><br><br>For the unlock to take effect you must re-publish or save your menu.  <br><br><font style='color:#333333;'>*Note:  Because Visual QuickMenu is browser based you must add your unlock code to the options dialog for each new menu you create.</font>",600);
					
				}
				else
				{
					qmv.unlock_type = "single";
					
					var qb = ul.value.split(",");
					qmv.unlock_string = ""
					for (var i=0;i<qb.length;i++)
						qmv.unlock_string += "qm_unlock"+i+"='"+qb[i]+"';"; 


					qmv_show_dialog("alert",null,"<font style='color:#0033dd;'>Congratulations, QuickMenu has been unlocked for your web site(s)!</font><br><br>For the unlock to take effect you must re-publish or save your menu.  <br><br><font style='color:#333333;'>*Note:  Because Visual QuickMenu is browser based you must add your unlock code to the options dialog for each new menu you create.</font>",600);
				}

				qmv.unlock_orig = ul.value;

			}
			
			

		}


	}
	else if (qmd.type=="image")
	{
		if (type=="ok" || type=="apply")
		{
			var t = document.getElementById("qmvi_dg_image");
			qmd.owner.value = t.value;
		
			var bad_val = qmv_evt_update_tree_value(qmd.owner);
			if (bad_val) return;
		}

	}
	else if (qmd.type=="subposition")
	{



		var m = qmv.container_obj;
		m.style.marginTop = "";
		m.style.marginLeft = "";


		if (type=="ok")
		{

			var os = document.getElementById("qmvi_pos_options1");
			if (os.checked)
			{
				m.style.margin = qmv.container_pos;

			}
			else if (document.getElementById("qmvi_pos_options2").checked)
			{

				var inp = qmv_find_update_tree_value("css","#qm[i] div","margin",qmv.container_pos,false,true);
				qmv_evt_update_tree_value(inp);

			}
			else if (document.getElementById("qmvi_pos_options3").checked)
			{

				m.style.margin = "";
				qmv_reset_secondary_object_positions(m);

			}
			else if (document.getElementById("qmvi_pos_options4").checked)
			{

				var inp = qmv_find_update_tree_value("css","#qm[i] div","margin","",false,true);
				qmv_evt_update_tree_value(inp);

			}

			
		}
		else if (type=="cancel")
		{
			qmv_reset_secondary_object_positions(m);	
		}

		

		qmv_position_pointer();

	}
	else if (qmd.type=="applydividers")
	{

		if (type=="ok" || type=="apply")
			qmv_gld_apply_dividers();


	}
	else if (qmd.type=="applystripes")
	{

		if (type=="ok" || type=="apply")
			qmv_gld_apply_stripes();


	}
	else if (qmd.type=="splash")
	{

		if (type.indexOf("next")+1)
			qmv.dyk_num++;
		else if (type.indexOf("previous")+1)
			qmv.dyk_num--;

		if (qmv.dyk_num<1) qmv.dyk_num = 1;
		if (qmv.dyk_num>qmv.dyk_max) qmv.dyk_num = qmv.dyk_max; 
		
		document.getElementById("qmv_dialog_button0").disabled = false;
		document.getElementById("qmv_dialog_button1").disabled = false;

		if (qmv.dyk_num==1)
		{
			var tb = document.getElementById("qmv_dialog_button0");
			tb.disabled = true;
			tb.blur();
		}
		
		
		if (qmv.dyk_num==qmv.dyk_max)
		{
			var tb = document.getElementById("qmv_dialog_button1");
			tb.disabled = true;
			tb.blur();
		}



		var hi = document.getElementById("qmvi_dyk_tips");
		hi.src = qmv.base+"help/dyk_"+qmv.dyk_num+".html";


	}
	else if (qmd.type=="structure")
	{

		if (document.getElementById("qmvi_structure_type1_pure").checked)
			qmv.pure = true;
		else
			qmv.pure = false;


	}
	else if (qmd.type=="import")
	{

		if (type=="generate menu")
		{

			if (qmv_import_menu())
				qmv_hide_dialog(is_msg,is_help);

		}


	}
	else if (qmd.type=="custom_rule")
	{

		if (type=="add rule")
		{
			if (qmv_custom_rule_build())
				qmv_hide_dialog(is_msg,is_help);
		}
		else if (type=="update rule")
		{


			if (qmv_custom_rule_build(null,qmd.owner))
				qmv_hide_dialog(is_msg,is_help);	
		}
		else if (type=="delete rule")
		{


			qmv_custom_rule_delete(new Object(),null,qmd.owner.idiv)
			qmv_hide_dialog(is_msg,is_help);	

		}

	}
	else if (qmd.type=="apply_custom_class")
	{


		if (type=="add")
			qmv_apply_custom_class();
		else if (type=="remove")
			qmv_apply_custom_class(true);



	}
	else if (qmd.type=="inherit")
	{
		if (type=="ok" || type=="apply")
			qmv_inherit_style_question_okapply()

	}
	


	if (type=="ok" || type=="cancel" || type=="done" || type=="no" || type=="yes" || type=="close" || type=="finished")
		qmv_hide_dialog(is_msg,is_help);

}

function qmv_reset_secondary_object_positions(m)
{

	var n;
	if (n = m.hasrcorner)
	{

		n.style.top = n.origtop;
		n.style.left = n.origleft;
	}

	if (n = m.hasshadow)
	{
		n.style.top = n.origtop;
		n.style.left = n.origleft;
	}



}


function qmv_dialog_onkeypress(e)
{

	e = e || event;

	
	if (e.keyCode==13)
		qmv_dialog_button_click("ok");
	else if (e.keyCode==27)
		qmv_dialog_button_click("cancel");

}

function qmv_tree_set_value(a,value)
{

	a.prev_value = a.value;
	a.value = value;


}


//other functions

function qmv_modify_set_atag_props(a)
{
	
	if (qm_a(a[qp]))
	{
		
		if (a[qp].ch)
		{
			a.style.styleFloat = "";
			a.style.cssFloat = "";
		}
		else
		{
			a.style.styleFloat = "none";
			a.style.cssFloat = "none";
		}

	}

}

function qmv_insert_spanitem(type,a,skip_select,after,skip_update)
{

	var ns = document.createElement("SPAN");
	if (!a) a = qmv.cur_item;

	if (type=="title")
	{
		if (a[qp].ch)
		{

			qmv_show_dialog("alert",null,"Title items may not be applied to horizontally oriented menus. Choose a vertically oriented main or sub menu before inserting a new title.",480);			
			return;
		}
		
		ns.className = "qmtitle";
		qmv_attach_title_events(ns);
		ns.innerHTML = "New Title";
	
		if (a[qp].ch)
		{
			ns.style.width = "200px";

		}


		if (!after)
			ns = a[qp].insertBefore(ns,a);
		else
			ns = qmv_lib_insert_after(ns,a);



		

	}
	else if (type=="divider")
	{

		
		if(a[qp].ch && !qmv.addons.tree_menu["on"+qmv.id])
		{

			var val = qmv_find_update_tree_value("individuals","#qm[i] .qmdividery","height",null,true,true);
			if (!val)
			{
				var update_inp = qmv_find_update_tree_value("individuals","#qm[i] .qmdividery","height",a.offsetHeight+"px",false,true);
				if (update_inp)
					qmv_evt_update_tree_value(update_inp);
			}

			var val = qmv_find_update_tree_value("individuals","#qm[i] .qmdividery","borderLeftWidth",null,true,true);
			if (!val)
			{
				var update_inp = qmv_find_update_tree_value("individuals","#qm[i] .qmdividery","borderLeftWidth","1px",false,true);
				if (update_inp)
					qmv_evt_update_tree_value(update_inp);
			}


			ns.className = "qmdivider qmdividery"
		}
		else
		{
			

			var val = qmv_find_update_tree_value("individuals","#qm[i] .qmdividerx","borderTopWidth",null,true,true);
			if (!val)
			{
				var update_inp = qmv_find_update_tree_value("individuals","#qm[i] .qmdividerx","borderTopWidth","1px",false,true);
				if (update_inp)
					qmv_evt_update_tree_value(update_inp);
			}	


			ns.className = "qmdivider qmdividerx"
		}

		qmv_attach_divider_events(ns);


		if (!after)
			a[qp].insertBefore(ns,a);
		else
			qmv_lib_insert_after(ns,a);

				
		

	}

	if (!skip_update)
		qmv_update_all_addons();

	
	if (!skip_select)
		qm_oo(new Object(),ns,false);
		

	
}


function qmv_modify_items(type)
{


	var ci = qmv.cur_item;
	var cc = ci.parentNode;
	var a,new_cur,skip_oo;

	if (type=="add" || type=="insert")
	{

		
		a = qm_modify_items_create_atag();

		if (type=="add")
		{
			var lasta;
			var ch = cc.childNodes;
			for (var i=0;i<ch.length;i++)
			{
				if (ch[i].tagName=="A")
					lasta = ch[i];

			}
		
			if (lasta.cdiv) lasta = lasta.cdiv;
			qmv_lib_insert_after(a,lasta);
		}
		else
		{

			cc.insertBefore(a,ci);

		}

		qmv_modify_set_atag_props(a);

		new_cur = a;
		
	
	}
	else if (type=="delete")
	{

		var skip = false;
		var count=0;
		var kids = cc.childNodes;
		for (var i=0;i<kids.length;i++)
		{
			if (kids[i].tagName=="A")
				count++;


			if ((kids[i].tagName=="SPAN") && (kids[i].className.indexOf("qmtitle")+1 || kids[i].className.indexOf("qmdivider")+1))
				count++
		}
		

		if (count<2)
		{

			if (qm_a(cc))
			{
			
				qmv_show_dialog("alert",null,"The last main menu item may not be deleted.",450)
				return;

			}
			else
			{
						
				new_cur = cc.idiv;
								
				cc.idiv.cdiv = null;
				qm_arc("qmparent",cc.idiv);
				qm_arc("qmactive",cc.idiv);
				qm_li = null;
			
				
				if (cc.hasrcorner)
					cc.parentNode.removeChild(cc.hasrcorner);

				if (cc.hasshadow)
					cc.parentNode.removeChild(cc.hasshadow);

				cc.parentNode.removeChild(cc);
				skip = true;

			}
		}
		

		if (!skip)
		{

			

			new_cur = qmv_lib_get_nextsibling_atag_or_span(ci);
			if (!new_cur) new_cur = qmv_lib_get_previoussibling_atag_or_span(ci);
								
									
			if (ci.cdiv)
			{
				if (ci.cdiv.hasrcorner)
					cc.removeChild(ci.cdiv.hasrcorner);

				
				if (ci.cdiv.hasshadow)
					cc.removeChild(ci.cdiv.hasshadow);

				cc.removeChild(ci.cdiv);
				qm_li = null;
			}
			cc.removeChild(ci);
			
	
		}
	}
	else if (type=="addsub")
	{

		if (ci.className.indexOf("qmdivider")+1 || ci.className.indexOf("qmtitle")+1)
		{
			qmv_show_dialog("alert",null,"Sub menus may not be applied to title and divider items, select a regular menu item before applying a child sub menu.",480);			
			return;
		}
		
		if (ci.cdiv)
		{
			qmv_show_dialog("alert",null,"The selected item already contains a sub menu.",450);
			return;
		}


		a = qm_modify_items_create_atag();
		
		var d = document.createElement("DIV");

		if (qmad.br_ie && !qmad.br_ie7)
		{
			var s = document.createElement("SPAN");
			s.className = "qmclear";
			s.innerHTML = " ";
			d.appendChild(s);
		}

		d.appendChild(a);

		qmv_attach_container_events(d);

		var nsub = qmv_lib_insert_after(d,ci);

		var ra = document.getElementById("qm"+qmv.id);
		nsub.sh = ra.sh;
		nsub.ch = ra.sh;

		nsub.rl = ra.rl;
		nsub.fl = ra.fl;

		if (qmv.addons.tree_menu["on"+qmv.id])
			nsub.qmtree = 1;


		if (cc.style.zIndex)
			nsub.style.zIndex = parseInt(cc.style.zIndex)+1;

		nsub.idiv = ci;
		ci.cdiv = nsub;
		qm_arc("qmparent",ci,true);

		new_cur = ci;

		qm_la = null;
		qm_oo(new Object(),new_cur,false);

		new_cur = nsub.getElementsByTagName("A")[0];

	}
	else if (type=="moveup" || type=="movedown")
	{
		if (qmv.addons.tree_menu["on"+qmv.id])
			skip_oo = true;

		if (type=="moveup")
		{
			
			

			var ps = qmv_lib_get_previoussibling_atag_or_span(ci);
			if (ps)
			{
			

				ci = cc.removeChild(ci);
				cc.insertBefore(ci,ps);

				if (ci.cdiv)
				{
					var cd = cc.removeChild(ci.cdiv);
					qmv_lib_insert_after(cd,ci);

				}

			
				new_cur = ci;
			}
		}
		else
		{
			var ps = qmv_lib_get_nextsibling_atag_or_span(ci);
			
			if (ps)
			{
				if (ps.cdiv) ps = ps.cdiv;
				ci = cc.removeChild(ci);
				qmv_lib_insert_after(ci,ps);


				if (ci.cdiv)
				{
					var cd = cc.removeChild(ci.cdiv);
					qmv_lib_insert_after(cd,ci);

				}

				new_cur = ci;
			}


		}
		
	
	}
	else if (type=="copyitem")
	{
		
		qmv.copyitem = ci.cloneNode(true);
			

	}
	else if (type=="pasteitem")
	{
		if (qmv.copyitem)
		{		
			a = cc.insertBefore(qmv.copyitem,ci);
			qm_modify_items_create_atag(a);

			qm_arc("qmparent",a);
			qm_arc("qmactive",a);
			a.cdiv = null;		

			if (qmv_lib_is_menu_vertical(qmv.id))
			{
				a.style.cssFloat = "none";
				a.style.styleFloat = "none";
			}
			else
			{
				a.style.cssFloat = "";
				a.style.styleFloat = "";
			}


			qmv.copyitem = a.cloneNode(true);
			qmv_modify_set_atag_props(a);
		
			new_cur = a;
		}
		else
		{
			qmv_show_dialog("alert",null,"Before pasting, you must first copy an item.",350);

		}

	}

	qmv_update_all_addons();

	if (new_cur && !skip_oo)
		qm_oo(new Object(),new_cur,false);
	else
		qmv_position_pointer();	

	

}

function qm_modify_items_create_atag(a)
{
	if (!a)
	{
		a = document.createElement("A");
		a.href = "javascript:void(0);";
		a.innerHTML = "New Item";
		qmv_evt_add_hover_item_in_visual_event(a);
	}
	
	a.ondblclick = function()
	{
		qmv_show_dialog("url",document.getElementById("qmv_texturl_field"));
	}

	a.oncontextmenu = function(e)
	{
		qmv_show_context(e,"menuitem",this);

	}

	a.onclick = qm_oo;
	a.onfocus = function(){this.blur();};
	

	return a;

}

function qm_switch_texturl_state(type)
{

	var iurl = document.getElementById("qmv_texturl_url");
	var itext = document.getElementById("qmv_texturl_text");


	if (type=="text")
	{

		if (qmv.texturl_state=="text")
			qmv_evt_build_button_click(document.getElementById("qmv_texturl_field_bb"));

		iurl.src = qmv.base+"images/qmv_url_off.gif";
		itext.src = qmv.base+"images/qmv_text.gif";
	}
	else
	{

		if (qmv.texturl_state=="url")
			qmv_evt_build_button_click(document.getElementById("qmv_texturl_field_bb"));

		iurl.src = qmv.base+"images/qmv_url.gif";
		itext.src = qmv.base+"images/qmv_text_off.gif";

	}


	qmv.texturl_state = type;
	qmv_set_texturl_field(qmv.cur_item)

}

function qm_arc(name,b,add)
{

	var a = b[qc];
	if (add)
	{
		if (a.indexOf(name)==-1)
			b[qc] += (a?' ':'') + name;	
		
	}
	else
	{
		
		b[qc] = a.replace(" "+name,"");
		b[qc] = b[qc].replace(name,"");
	
	}
}



function qmv_set_all_subs_to_default_position(check_vis,iefix)
{
	
	var divs = document.getElementById("qm"+qmv.id).getElementsByTagName("DIV");
	for (var i=0;i<divs.length;i++)
	{
		
		if (check_vis && divs[i].style.visibility!="inherit")
		{
			
			continue;
		}

		if (iefix && divs[i].style.visibility=="inherit")
		{
			divs[i].style.visibility="";
			divs[i].style.visibility="inherit";
		}
		
		qmv_set_sub_to_default_position(divs[i]);

	}

}


function qmv_set_sub_to_default_position(b)
{

	
	var aw = b.idiv.offsetWidth;
	var ah = b.idiv.offsetHeight;
	var ax = b.idiv.offsetLeft;
	var ay = b.idiv.offsetTop;
	
	
	if (b[qp].ch)
	{
		
		aw = 0;
		if (b.fl) ax =0;
	}
	else
	{
		if (b.rl)
		{
			ax = ax-b.offsetWidth;
			aw=0;
		}

		ah=0;
	}		



	
	b.style.left = (ax+aw)+"px";
	b.style.top = (ay+ah)+"px";
	
}



//addon updates

function qmv_update_all_addons(except)
{

	var i;
	for (i in qmv.addons)
	{
		if (i==except)
			continue;

		if (qmv.addons[i].noupdate)
			continue;
		
		if (window["qmv_update_"+i])
			eval("qmv_update_"+i+"()");

	}

	/*

	if (qmv.interface_full)
	{
		var a = document.getElementById("qm"+qmv.id);
		if (a[qp].menufloater)
		{

			var twidth = 0;				

			var ch = a.childNodes;
			for (var i=0;i<ch.length;i++)
			{
				if (ch[i].tagName && (ch[i].tagName=="SPAN" || ch[i].tagName=="A"))
					twidth+= ch[i].offsetWidth;
	
			}

			a[qp].style.width = twidth;

		}
	}
	*/


}



function qmv_update_drop_shadow(hide)
{

	
	if (!hide && !qmv.addons.drop_shadow["on"+qmv.id])
		return;

	
	var divs = document.getElementById("qm"+qmv.id).getElementsByTagName("DIV");
	for (var i=0;i<divs.length;i++)
	{
		
		if ((divs[i].style.visibility=="inherit" && !divs[i].hasshadow) || hide)
		{
			
			qm_drop_shadow(divs[i],hide);

		}
		else if (divs[i].hasshadow)
		{

			
			divs[i].hasshadow.parentNode.removeChild(divs[i].hasshadow);
			divs[i].hasshadow = null;
				
			if (divs[i].style.visibility=="inherit")
				qm_drop_shadow(divs[i],false,true);

			
			
		}


	}
	

}


function qmv_update_keyboard(hide)
{

	if (!hide)
		qmv_show_dialog("alert",null,"To test keyboard access functionality, preview your menu by clicking the preview option in the visual interface menu.",480);			

}


function qmv_update_round_corners(hide)
{

	if (!qmv.addons.round_corners["on"+qmv.id])
		return;


	
	
	var divs = document.getElementById("qm"+qmv.id).getElementsByTagName("DIV");
	for (var i=0;i<divs.length;i++)
	{
		
		if ((divs[i].style.visibility=="inherit" && !divs[i].hasrcorner) || hide)
		{

			qm_rcorner(divs[i],hide);

			if (hide)
			{

				qmv_set_sub_to_default_position(divs[i]);
						
			}

		}
		else if (divs[i].hasrcorner)
		{

			divs[i].hasrcorner.parentNode.removeChild(divs[i].hasrcorner);
			divs[i].hasrcorner = null;
			qmv_set_sub_to_default_position(divs[i]);
	
			if (divs[i].style.visibility=="inherit")
				qm_rcorner(divs[i],false,true);


						
		}


	}


	if (hide)
	{
		qmv.questionasked_rcorner_size = false;
		qmv_updatehandle_round_corner_hide()
	}
	else
		qmv_updatehandle_round_corner_show();
	
	
}

function qmv_update_bump_effect(hide)
{

	if (!qmv.addons.bump_effect["on"+qmv.id])
		return;

	if (hide)
		qmv.questionasked_bump_effect = false;
	else
	{
		if (!qmv.questionasked_bump_effect)
		{
	
			qmv_ask_and_set_value("The bump effect typically looks best when used in conjunction with a show sub menu delay.  Set the value below or at any time under the 'Menu Settings' tree heading.  A common setting which works well is 250 which is equal to 1/4 second.<br><br>Show Delay (ms - 1/1000th of a second)",400,250,"settings","create","showdelay");	
			qmv.questionasked_bump_effect = true;
		}

	}

}


function qmv_updatehandle_round_corner_hide(isyes,bg,border)
{
	
	if (!isyes)
	{
		var m = document.getElementById("qm"+qmv.id).getElementsByTagName("DIV");
		if (m[0])
		{

			var bg = qm_lib_get_computed_style(m[0],"background-color","backgroundColor")+"";
			if (!bg || bg=="transparent")
			{		
		
				bg = qmv_find_update_tree_value("addon","round_corners","rcorner_bg_color","",true);
				border = qmv_find_update_tree_value("addon","round_corners","rcorner_border_color","",true);

				if (bg || border)
				{

					qmv_show_dialog("question-yesno",null,"Each Rounded corners container sits under the existing sub menu container.  Your sub menu container is currently transparent and may not appear correctly with rounded corners removed.<br><br>Would you like to apply the rounded corner colors to your sub menu containers?",600,"qmv_updatehandle_round_corner_hide(true,'"+bg+"','"+border+"')");	
	
				}
	
			}

		}
	}
	else
	{
		
		var inp;
		inp = qmv_find_update_tree_value("css","#qm[i] div","backgroundColor",bg,false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		inp = qmv_find_update_tree_value("css","#qm[i] div","borderStyle","solid",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		inp = qmv_find_update_tree_value("css","#qm[i] div","borderColor",border,false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		inp = qmv_find_update_tree_value("css","#qm[i] div","borderWidth","1px",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		if (document.getElementById("qmvtree_filter").cdiv.style.display=="block")
			qmv_filter_init2();

	}


}

function qmv_updatehandle_round_corner_show(a,isyes,bg,border,itembg,force)
{
	
	if (!isyes)
	{
		
		if (qmv.questionasked_rcorner_size && !force) return;

		var m = document.getElementById("qm"+qmv.id).getElementsByTagName("DIV");
		if (m[0])
		{

			var bg = qm_lib_get_computed_style(m[0],"background-color","backgroundColor")+"";
			var border = qm_lib_get_computed_style(m[0],"border-left-color","borderLeftColor")+"";

			if (qmad.br_fox)
			{
				bg = qmv_convert_color_to_hex(bg);
				border = qmv_convert_color_to_hex(border);
			}
			
			var itembg = "";
			var ma = m[0].getElementsByTagName("A")[0];
			if (ma)
				itembg = qm_lib_get_computed_style(ma,"background-color","backgroundColor")+"";

			if (bg && bg.toLowerCase()!="transparent")
			{

				qmv_show_dialog("question-yesno",null,"Each Rounded corners container sits under the existing sub menu container.  To give the appearance of a single sub menu container with rounded corners you must match the colors of both containers, or set the sub menu containers colors to transparent.<br><br>Would you like Visual QuickMenu to blend the container colors for you?",600,"qmv_updatehandle_round_corner_show(null,true,'"+bg+"','"+border+"','"+itembg+"')");	
				qmv.questionasked_rcorner_size = true;
		
			}
			else
			{
				if (force)
					qmv_show_dialog("alert",null,"The rounded container colors have already been blended to match.",500);


			}	
	
		}
	}
	else
	{
		
		var inp;
		inp = qmv_find_update_tree_value("css","#qm[i] div","backgroundColor","transparent",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		inp = qmv_find_update_tree_value("css","#qm[i] div","borderStyle","none",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		inp = qmv_find_update_tree_value("css","#qm[i] div a","backgroundColor","transparent",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		if (document.getElementById("qmvtree_filter").cdiv.style.display=="block")
			qmv_filter_init2();

		var sval;

		if (bg || itembg)
		{
			sval = bg;
			if (!bg)
				sval = itembg;


			inp = qmv_find_update_tree_value("addon","round_corners","rcorner_bg_color",sval);
			qmv_evt_update_tree_value(inp);
		}

		if (border)
		{
			
			inp = qmv_find_update_tree_value("addon","round_corners","rcorner_border_color",border);
			qmv_evt_update_tree_value(inp);

		}
	
	}

}

function qmv_update_over_select(hide)
{

	if (!hide && !qmv.addons.over_select["on"+qmv.id])
		return;


	if (qmad.br_ie && !qmad.br_ie7)
	{

		var divs = document.getElementById("qm"+qmv.id).getElementsByTagName("DIV");
		for (var i=0;i<divs.length;i++)
		{
		
			if ((divs[i].style.visibility=="inherit" && !divs[i].hasselectfix) || hide)
			{
				
				qm_over_select(divs[i],hide);

			}
			else if (divs[i].hasselectfix)
			{

			
				divs[i].hasselectfix.parentNode.removeChild(divs[i].hasselectfix);
				divs[i].hasselectfix = null;
				
				if (divs[i].style.visibility=="inherit")
					qm_over_select(divs[i],false);

			
			
			}


		}
	}

}


function qmv_update_apsubs(hide)
{

	if (!qmv.addons.apsubs["on"+qmv.id])
		return;

	if (!hide)
	{
		var divs = document.getElementById("qm"+qmv.id).getElementsByTagName("DIV");
		for (var i=0;i<divs.length;i++)
		{
			
			if (divs[i].style.visibility=="inherit")
				qm_apsubs(divs[i]);
		}
	}
	else
		qmv_set_all_subs_to_default_position();
	

}

function qmv_update_match_widths(hide)
{

	if (!hide && !qmv.addons.match_widths["on"+qmv.id])
		return;

		
	var divs = document.getElementById("qm"+qmv.id).childNodes;	
	for (var i=0;i<divs.length;i++)
	{
		if (divs[i].tagName=="DIV")
		{	
			if (hide)
				divs[i].style.width = "";
			else
				qm_mwidths_a(divs[i],divs[i].idiv);
			
		}
		
		

	}


	
	qmv_set_all_subs_to_default_position();
	

}

function qmv_update_tabs(hide)
{

	
	if (!hide && !qmv.addons.tabs["on"+qmv.id])
		return;

	
	
	var a = document.getElementById("qm"+qmv.id).childNodes;
	for (var i=0;i<a.length;i++)
	{

		if (a[i].istab)
		{
			
			a[i].parentNode.removeChild(a[i]);

		}
		
	}

	if (!hide)
		qm_tabs_init(null,qmv.id);
	
	

}

function qmv_update_tabscss(hide)
{
	
	if (!hide && !qmv.addons.tabscss["on"+qmv.id])
		return;


	
	var a = document.getElementById("qm"+qmv.id).childNodes;
	for (var i=0;i<a.length;i++)
	{

		if (a[i].iscsstab)
		{
			
			a[i].parentNode.removeChild(a[i]);

		}
		
	}

	if (!hide)
	{
		qm_tabscss_init(null,qmv.id);
		qmv_updatehandle_tabscss_show();
	}
	else
		qmv.questionasked_tabscss_on = false;

	
		
	
}


function qmv_update_pointer(hide)
{

	if (!hide && !qmv.addons.pointer["on"+qmv.id])
		return;

	var cdiv = document.getElementById("qm"+qmv.id);
	if (cdiv.haspointer)
	{

		cdiv.removeChild(cdiv.haspointer);
		cdiv.haspointer = null;

		if (cdiv.detachEvent)
			cdiv.detachEvent("onmousemove",qm_pointer_move);
		
			
	}

	var divs = cdiv.getElementsByTagName("DIV");
	for (var i=0;i<divs.length;i++)
	{
		
		if (divs[i].haspointer)
		{

			divs[i].removeChild(divs[i].haspointer);
			divs[i].haspointer = null;

			
			if (divs[i].detachEvent)
				divs[i].detachEvent("onmousemove",qm_pointer_move);
			
		}
	}


	if (!hide)
	{
		qmv_hide_pointer("qm"+qmv.id);
		qm_pointer_init(null,qmv.id);
	}
	
	

}


function qmv_updatehandle_tabscss_show(a,isyes,color)
{
	
	if (!isyes)
	{
		if (qmv.questionasked_tabscss_on) return;
		
		if (qmv_find_update_tree_value("addon","tabscss","tabscss_border_color",null,true)) return;

		
		var m = document.getElementById("qm"+qmv.id).getElementsByTagName("A");
		if (m[0])
		{
			
			var bstyle = qm_lib_get_computed_style(m[0],"border-style","borderStyle")+"";
			var bwidth = qm_lib_get_computed_style(m[0],"border-width","borderWidth")+"";
			var bcolor = qm_lib_get_computed_style(m[0],"border-color","borderColor")+"";
		
			if (qmad.br_fox)
				bcolor = qmv_convert_color_to_hex(bcolor);
			
			if (bstyle && bwidth && bcolor)
			{
				
				qmv_show_dialog("question-yesno",null,"Would you like to match the tab elements border color to your items border color?<br><br>This color may be changed at any time under the CSS tab addon settings.",600,"qmv_updatehandle_tabscss_show(null,true,'"+bcolor+"')");	
				qmv.questionasked_tabscss_on = true;

			}
	
	
		}
	}
	else
	{
		
		
		var inp;
		inp = qmv_find_update_tree_value("addon","tabscss","tabscss_border_color",color);
		qmv_evt_update_tree_value(inp);

		

		
	}

}



function qmv_update_item_bullets(hide)
{

	if (!hide && !qmv.addons.item_bullets["on"+qmv.id])
		return;

	//kill any existing bullet before recreating
	var a = document.getElementById("qm"+qmv.id).getElementsByTagName("SPAN");
	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].getAttribute("isibullet"))
		{
		
			if(a[i].parentNode.removeEventListener)
				a[i].parentNode.removeEventListener("onmouseover",qm_ibullets_hover,false);
			else if (a[i].parentNode.detachEvent)
				a[i].parentNode.detachEvent("onmouseover",qm_ibullets_hover);

			a[i].parentNode.removeChild(a[i]);
			i--;
		}
		
	}

	if (!hide)
		qm_ibullets_init(null,qmv.id);


	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].getAttribute("isibullet"))
		{
		
			qmv_add_bullet_events(a[i]);
			
			
		}
		
	}
	

}

function qmv_update_ibcss(hide,dtype)
{

	if (!hide && !qmv.addons.ibcss["on"+qmv.id])
		return;

	var a = document.getElementById("qm"+qmv.id).getElementsByTagName("SPAN");
	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].getAttribute("isibulletcss"))
		{
		
			if(a[i].parentNode.removeEventListener)
				a[i].parentNode.removeEventListener("onmouseover",qm_ibullets_hover,false);
			else if (a[i].parentNode.detachEvent)
				a[i].parentNode.detachEvent("onmouseover",qm_ibullets_hover);

			a[i].parentNode.removeChild(a[i]);
			i--;
		}
		
	}

	if (!hide)
		qm_ibcss_init(null,qmv.id);


	var init_styles = false;
	if (!qmv.ibcss_ss)
	{
		qmv.ibcss_ss = qmv_lib_get_qm_stylesheet(".qmvibcssmenu");
		if (!qmv.ibcss_ss)qmv.ibcss_ss = qmv_lib_get_qm_stylesheet(".qmvibcssstyles");
		init_styles = true;
	}


	if (dtype=="color" || init_styles)
	{
		
		var tstyles = qmv.ibcss_ss;	
		if (tstyles)
		{
		
			var rules;
			if (tstyles.rules)
				rules = tstyles.rules;
			else if (tstyles.cssRules)
				rules = tstyles.cssRules;

			if (!hide)
			{
				var bgc = "#ffffff";
				var bbc = "#000000";
				var t;

				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_main_bg_color",null,true)) bgc = t;
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_main_border_color",null,true)) bbc = t;
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" .qm-ibcss-static span","background-color:"+bgc+";border-color:"+bbc+";");

				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_main_bg_color_hover",null,true)) bgc = t;
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_main_border_color_hover",null,true)) bbc = t;
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" .qm-ibcss-hover span","background-color:"+bgc+";border-color:"+bbc+";");

				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_main_bg_color_active",null,true)) bgc = t;
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_main_border_color_active",null,true)) bbc = t;
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" .qm-ibcss-active span","background-color:"+bgc+";border-color:"+bbc+";");

				bgc = "#ffffff";
				bbc = "#000000";
			
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_sub_bg_color",null,true)) bgc = t;
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_sub_border_color",null,true)) bbc = t;
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" div .qm-ibcss-static span","background-color:"+bgc+";border-color:"+bbc+";");
	
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_sub_bg_color_hover",null,true)) bgc = t;
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_sub_border_color_hover",null,true)) bbc = t;
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" div .qm-ibcss-hover span","background-color:"+bgc+";border-color:"+bbc+";");

				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_sub_bg_color_active",null,true)) bgc = t;
				if (t = qmv_find_update_tree_value("addon","ibcss","ibcss_sub_border_color_active",null,true)) bbc = t;
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" div .qm-ibcss-active span","background-color:"+bgc+";border-color:"+bbc+";");


			
			}
		}
	}


	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].getAttribute("isibulletcss"))
		{
		
			qmv_add_bullet_css_events(a[i]);
			
			
		}
		
	}
}


function qmv_update_ritem(hide,dtype)
{


	if (!hide && !qmv.addons.ritem["on"+qmv.id])
		return;


	
	var a = document.getElementById("qm"+qmv.id).getElementsByTagName("A");
	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].hasritem)
		{
			
			a[i].removeChild(a[i].hasritem);
			a[i].hasritem = null;

		}
		
	}


	

	a = document.getElementById("qm"+qmv.id).getElementsByTagName("SPAN");
	for (var i=0;i<a.length;i++)
	{
		
		if (a[i].hasritem)
		{
			
			a[i].removeChild(a[i].hasritem);
			a[i].hasritem = null;

		}
		
	}

	if (!hide)
		qm_ritem_init(null,qmv.id);


}



function qmv_update_tree_menu(hide)
{


	if (!hide && !qmv.addons.tree_menu["on"+qmv.id])
		return;

	var tstyles = qmv_lib_get_qm_stylesheet(".qmistreestylesqm"+qmv.id);
	if (!tstyles)tstyles = qmv_lib_get_qm_stylesheet(".qmvistreestyles");

	if (tstyles)
	{
		var rules;
		if (tstyles.rules)
			rules = tstyles.rules;
		else if (tstyles.cssRules)
			rules = tstyles.cssRules;

		if (!hide)
		{


			

			var az = "";
			if (window.showHelp) az = "zoom:1;";

			qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id,'width:'+qmad["qm"+qmv.id].tree_width+'px;position:relative !important;');

			if (!qmv.addons.tree_menu.initialized)
			{
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" a","float:none !important;white-space:normal !important;");
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" div","width:auto !important;left:0px !important;top:0px !important;overflow:hidden !important;"+az+"border-top-width:0px !important;border-bottom-width:0px !important;margin-left:0px !important;margin-top:0px !important;");
			}

			
			var pl = "";
			if (qmad["qm"+qmv.id].tree_sub_sub_indent)
				qmv_lib_update_add_rule_styles(tstyles,rules,"#qm"+qmv.id+" div div",'padding-left:'+qmad["qm"+qmv.id].tree_sub_sub_indent+'px;');
			

			
			qm_tree_init(null,"qm"+qmv.id);


			if (!qmv.addons.tree_menu.initialized)
			{

				var a = document.getElementById("qm"+qmv.id).getElementsByTagName("A");
				for (var i=0;i<a.length;i++)
				{
					var c;
					if (c = a[i].cdiv)
					{
					
						c.qmtree = 1;
									
						if (c.style.visibility=="inherit")	
							c.style.position = "relative";
					
					
					}
				}	
			}


			qmv.addons.tree_menu.initialized = true;


		}
		else
		{


			

			qmv_lib_update_remove_rule(tstyles,rules,"#qm"+qmv.id+" div div");
			qmv_lib_update_remove_rule(tstyles,rules,"#qm"+qmv.id+" div");
			qmv_lib_update_remove_rule(tstyles,rules,"#qm"+qmv.id+" a");
			qmv_lib_update_remove_rule(tstyles,rules,"#qm"+qmv.id);
			
			
			var a = document.getElementById("qm"+qmv.id).getElementsByTagName("A");
			for (var i=0;i<a.length;i++)
			{

				if (a[i].cdiv)
				{
					
					a[i].cdiv.qmtree = null;
					a[i].cdiv.ismove = null;
										
					qm_arc("qmfh",a[i].cdiv);
					qm_arc("qmfv",a[i].cdiv);
					
					
					a[i].cdiv.style.height = "";
					a[i].cdiv.style.position = "";
					

				}

				

			}

			qmv_set_all_subs_to_default_position(true);


			qmv.addons.tree_menu.initialized = false;			

		}
		
		
	}

	



}

function qmv_add_new_menu(menu)
{

	var new_id = qmv_lib_get_new_menu_id();

	var mc;
	if (!menu)
		mc = document.createElement("DIV");
	else
		mc = menu;

	mc.menufloater = 1;


	if (!qmv.interface_full)
	{
		mc.style.position = "absolute";
		mc.style.top = "10px";
		mc.style.left = "10px";
		mc.style.zIndex = qmv.base_zindex-1000+new_id;
		if (qmad.br_ie)
			mc.style.zoom = 1;

		mc.onmouseup = function(event){	qmv_evt_title_mouseup(event,this)}
		mc.onmousemove = function(event){qmv_evt_title_mousemove(event,this)}
		mc.onmousedown = function(event){qmv_evt_title_mousedown(event,this,this)}
	}
	else
	{

		mc.style.position = "relative";

		var count = qmv_lib_get_menu_count();

		mc.style.top = ((count*200)+30)+"px";
		mc.style.left = "0px";
		mc.style.paddingLeft = "20px";
		mc.style.paddingRight = "20px";
		if (qmad.br_ie)
			mc.style.zoom = 1;

		mc.onmouseup = function(event){qmv_evt_title_mouseup(event,this)}
		mc.onmousemove = function(event){qmv_evt_title_mousemove(event,this)}
		mc.onmousedown = function(event){qmv_evt_title_mousedown(event,this,this,true)}

	}

	
	
	//create menu structure
	if (!menu)
	{
		var mh = "";
		mh += '<div id="qm'+new_id+'" class="qmmc">';
			mh += '<a href="javascript:void(0);">Main Item 1</a>';
				mh += '<div>';
				mh += '<a href="javascript:void(0);">Sub Item 1</a>';
				mh += '<a href="javascript:void(0);">Sub Item 2</a>';
				mh += '<a href="javascript:void(0);">Sub Item 3</a>';
				mh += '</div>';
			mh += '<a href="javascript:void(0);">Main Item 2</a>';
				mh += '<div>';
				mh += '<a href="javascript:void(0);">Sub Item 1</a>';
				mh += '<a href="javascript:void(0);">Sub Item 2</a>';
				mh += '<a href="javascript:void(0);">Sub Item 3</a>';
				mh += '<a href="javascript:void(0);">Sub Item 4</a>';
				mh += '</div>';
			mh += '<a href="javascript:void(0);">Main Item 3</a>';
				mh += '<div>';
				mh += '<a href="javascript:void(0);">Sub Item 1</a>';
				mh += '<a href="javascript:void(0);">Sub Item 2</a>';
				mh += '</div>';
		mh += '<span class="qmclear"> </span></div>';
		mh += '</div>';
		mc.innerHTML = mh;
	}
	else
	{
		mc.id = 'qm'+new_id;
	}



	//style the menu

	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id,"background-color:transparent;");
	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id+" a","color:#000000;background-color:#ffffff;font-family:Arial;font-size:.8em;text-decoration:none;padding:5px 40px 5px 8px;border-style:solid;border-color:#dddddd;border-width:1px;");
	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id+" a:hover","background-color:#efefef;");

	if (qmad.br_ie)
	{
		qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"body #qm"+new_id+" .qmactive","background-color:#efefef;text-decoration:underline;");
		qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"body #qm"+new_id+" .qmactive:hover","background-color:#efefef;text-decoration:underline;");
	}
	else
		qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"body #qm"+new_id+" .qmactive, body #qm"+new_id+" .qmactive:hover","background-color:#efefef;text-decoration:underline;");


	
	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id+" .qmparent","background-color:;");

	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id+" div","background-color:#eeeeee;padding:5px;border-style:solid;border-width:1px;border-color:#cccccc;margin:-1px 0px 0px 0px;");
	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id+" div a","background-color:transparent;padding:2px 40px 2px 5px;border-width:0px;border-style:none;border-color:#000000;");
	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id+" div a:hover","text-decoration:underline;");

	if (qmad.br_ie)
	{
		qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"body #qm"+new_id+" div .qmactive","background-color:#ffffff;");
		qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"body #qm"+new_id+" div .qmactive:hover","background-color:#ffffff;");
	}
	else
		qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"body #qm"+new_id+" div .qmactive, body #qm"+new_id+" .qmactive:hover","background-color:#ffffff;");

	qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,"#qm"+new_id+" div .qmparent","background-color:transparent;");
	
	
	if (!qmv.interface_full)
	{
		document.body.appendChild(mc);
	}
	else
	{
		var mp = document.getElementById("qmvi_menu_panel");
		mp.appendChild(mc);		
	}

	qm_create(new_id,false,0,500,false);

	
	document.onmouseover = null;
	var m = document.getElementById("qm"+new_id);

	
	qmv_design_menu2(m);

	qm_oo(new Object(),m.getElementsByTagName("A")[0]);

	qmv_setbox_update_quick_color();
	

}


function qmv_delete_menu()
{

	if (qmv_lib_get_menu_count()>1)
	{

		var a;
		if (a = document.getElementById("qm"+qmv.id))
			qmv_show_dialog("question-okcancel",a,"The active menu system, and all asociated settings will be deleted!<br><br>Do wish to continue?",450,"qmv_delete_menu_ok('qm'+qmv.id)");	
			
	
	}
	else
	{

		qmv_show_dialog("alert",a,"The last menu may not be deleted.",450);

	}

}

function qmv_delete_menu_ok(id)
{

	var po = qmv.pointer[id]=null;	

	var a = document.getElementById(id)
	a.parentNode.removeChild(a);

	for (var i=0;i<10;i++)
	{
		var a;
		if (a = document.getElementById("qm"+i))
		{
			qm_oo(new Object(),a.getElementsByTagName("A")[0]);	
			break;
		}

	}



}

function qmv_preview_menu(skip_prompt)
{

	qmv_addon_set_all_status();


	qmv_hide_dialog(false,false,true);
	qmv_hide_dialog(false,true,false);
	qmv_hide_dialog(true,false,false);



	if (!skip_prompt && !qmv.msg_shown_preview)
	{
		qmv_show_dialog("question-okcancel",a,"The visual interface will be closed while previewing the menu.<br>To re-open the interface <font style='color:#dd3300'>double click</font> anywhere in the web page.<br><br>Continue with preview?",450,"qmv_preview_menu(true)");
		return;
	}


	if (qmv.interface_full)
	{
		
		qmv_set_interface_mode("inpage");
		qmv.was_full_on_preview = true;
	}
	else
	{

		qmv.was_full_on_preview = false;
	}



	qmv.msg_shown_preview = true;

	qm_th = qmv.ms_hide_timer;
	document.onmouseover = qm_bo;
	document.ondblclick = qmv_design_menu;

	//switch menu items to onclick
	var a;
	for (var i=0;i<10;i++)
	{
		if (a = document.getElementById("qm"+i))
		{
			qmv_hide_pointer("qm"+i);
			qmv_preview_menu2(a);
			
		}

	}


	qmv.preview_mode = true;
	qmv.qmvi.style.visibility = "hidden";

	
	qmv_2bo();
	
	

}

function qmv_2bo()
{

	//close all the menus, same as qm_2bo
	var a;
	if ((a = qm_li))	
	{
		
		do
		{
			
			qm_uo(a);
			
			
		}while ((a = a[qp]) && !qm_a(a))

	}

	qm_li = null;


}

function qmv_preview_menu2(b)
{
		
	if (b.origclick || qmv.addons.tree_menu["on"+b.id.substring(2)])
		return;
		

	var sid = b.id.substring(2);
	
	a = b.getElementsByTagName("A");
	for (var j=0;j<a.length;j++)
	{
		a[j].ondblclick = null;								
		a[j].onmouseover = a[j].onclick;
		a[j].onclick = qm_kille;
		a[j].oncontextmenu = function(e){e=e||event;qm_kille(e);return false;};
		

		if (qmv.addons.keyboard["on"+sid])
		{

			a[j].onfocus = null;
			if (a[j].attachEvent)
				a[j].attachEvent("onkeydown",qm_kb_press);
			else if (a[j].addEventListener)
				a[j].addEventListener("keypress",qm_kb_press,true);
		
		}

		a[j].qmts = qmv.ms_show_timer;
	}

	
	
	a = b.getElementsByTagName("SPAN");
	for (var j=0;j<a.length;j++)
	{
		
		if (a[j].className.indexOf("qmdivider")+1)
		{
			a[j].oncontextmenu = function(e){e=e||event;qm_kille(e);return false;};

		}
		else if (a[j].className.indexOf("qmtitle")+1)
		{

			a[j].oncontextmenu = function(e){e=e||event;qm_kille(e);return false;};

		} 
		else if (a[j].getAttribute("isibulletcss"))
		{
			
			a[j].oncontextmenu = function(e){e=e||event;qm_kille(e);return false;};

		}
		else if (a[j].getAttribute("isibullet"))
		{
			a[j].oncontextmenu = function(e){e=e||event;qm_kille(e);return false;};
		
		}
	}	
}

function qmv_click_document_element()
{

	qmv_hide_pointer("qm"+qmv.id);
	qmv_hide_context();


}


function qmv_design_menu()
{


	qmv.preview_mode = false;

	qm_th = 0;  //set the hide timer to zero
	document.onmouseover = null;


	
	
	document.onclick = function()
	{
		qmv_click_document_element();
	}
	
	
	document.ondblclick = null;

	var a;
	var ac = false;
	for (var i=0;i<10;i++)
	{
		if (a = document.getElementById("qm"+i))
		{
			a.style.visibility = "";
			qmv_design_menu2(a);

			if (!ac)
			{
				ac = true;
				qm_oo(new Object(),a.getElementsByTagName("A")[0])
			}


			
		}

	}	


	if (qmv.was_full_on_preview && !qmv.interface_full)
		qmv_set_interface_mode("full");


	//show the interface
	qmv.qmvi.style.visibility = "visible";

}




function qmv_create_pointer(a)
{




	if (!qmv.pointer[a.id])
	{
		
		qmv.pointer[a.id] = new Object();
		var c = document.createElement("SPAN");
		c.style.display = "none";
		c.style.position = "absolute";
		c.style.zIndex = qmv.base_zindex-200;
		c.style.borderStyle = "dashed";
		c.style.borderWidth = "1px";
		c.style.borderColor = "#ff0000";
		c.style.fontSize = "1px";
		c.innerHTML = ""
		c.isqmv = 1;

		/*
		if (qmad.br_ie)
			c.style.filter = "alpha(opacity=70)";
		else
			c.style.opacity = .7;
		*/
			

		c.onclick = function(e)
		{
			e = e || event;
			qm_oo(new Object(),qmv.cur_item)
			qm_kille(e);
		};

		c.ondblclick = function()
		{
			qmv_show_dialog("url",document.getElementById("qmv_texturl_field"));
		}

		c.onmouseover = function()
		{
			qm_image_switch(qmv.cur_item);
		}


		c.oncontextmenu = function(e)
		{
			e = e || event;
			qmv_show_context(e,"menuitem",qmv.cur_item);
	
		}
		

		a.insertBefore(c,a.firstChild);
			
		qmv.pointer[a.id].a = c;

	}


}

function qmv_design_menu2(b)
{

	qmv_create_pointer(b);
				
	a = b.getElementsByTagName("A");
	for (var j=0;j<a.length;j++)
	{
		
		a[j].oncontextmenu = function(e)
		{

			qmv_show_context(e,"menuitem",this);

		}

		a[j].ondblclick = function()
		{
			qmv_show_dialog("url",document.getElementById("qmv_texturl_field"));
		}

		if (a[j].qmts)
		{
			qmv.ms_show_timer = a[j].qmts;
			a[j].qmts = 0;
		}
					
		if (!a[j].onclick || a[j].onclick==qm_kille)
		{
			
			a[j].onclick = a[j].onmouseover;
			a[j].onmouseover = null;
		}
		else
		{
			
			a[j].parentNode.origclick = 1;
		}
				
		a[j].onfocus = function()
		{
			this.blur();
				
		};

		
		if (a[j].attachEvent)
		{
			a[j].detachEvent("onkeydown",qm_kb_press);
			//a[j].attachEvent("onkeydown",qm_kb_press);
		}
		else if (a[j].addEventListener)
		{
			a[j].removeEventListener("keypress",qm_kb_press,true);
			//a[j].addEventListener("keypress",qm_kb_press,true);
		}
		

		qmv_evt_add_hover_item_in_visual_event(a[j]);


	}


	a = b.getElementsByTagName("DIV");
	for (var j=0;j<a.length;j++)
	{

		if (qmad.br_ie)
		{

			a[j].onselectstart = function(e)
			{
				e = e || event;

				qm_kille(e);
				return false;
			}

		}

		
		if (qmad.br_ie)
			a[j].ondragstart = function(){event.returnValue=false;event.cancelBubble = true;return false;}
		
		
		qmv_attach_container_events(a[j]);	
	
	

	}


	a = b.getElementsByTagName("SPAN");
	for (var j=0;j<a.length;j++)
	{


		if (a[j].className.indexOf("qmdivider")+1)
		{
			qmv_attach_divider_events(a[j]);

		}
		else if (a[j].className.indexOf("qmtitle")+1)
		{

			qmv_attach_title_events(a[j]);

		} 
		else if (a[j].getAttribute("isibulletcss"))
		{
			
			qmv_add_bullet_css_events(a[j]);

		}
		else if (a[j].getAttribute("isibullet"))
		{
			qmv_add_bullet_events(a[j]);
		
		}	
		


	}
	
}

function qmv_attach_divider_events(a)
{

	if (qmad.br_ie)
	{

		a.onselectstart = function(e)
		{
			e = e || event;

			qm_kille(e);
			return false;
		}

	}

	a.oncontextmenu = function(e)
	{
				
		//qmv_show_context(e,"divider",qmv_lib_get_nextsibling_atag(this));
		qmv_show_context(e,"divider",this);

	}

	/*
	a.onclick = function(e)
	{
		e = e || event;

		var nt = qmv_lib_get_nextsibling_atag(this);
		if (nt)
			qm_oo(e,nt);
	}
	*/
	a.onclick = function(e)
	{
		e = e || event;

		//var nt = qmv_lib_get_nextsibling_atag(this);
		//if (nt)
			qm_oo(e,this);
	}

	a.ondblclick = function(e)
	{
		e = e || event;

		var a = document.getElementById("qmvtree_item_dividers").idiv;
		qmv_display_setbox(a,null,null,true,2);

		
		//var nt = qmv_lib_get_nextsibling_atag(this);
		//if (nt)
			qm_oo(e,this);
	}


}


function qmv_attach_title_events(a)
{

	if (qmad.br_ie)
	{

		a.onselectstart = function(e)
		{
			e = e || event;

			qm_kille(e);
			return false;
		}

	}


	a.oncontextmenu = function(e)
	{
				
		//qmv_show_context(e,"title",qmv_lib_get_nextsibling_atag(this));
		qmv_show_context(e,"title",this);

	}

	/*
	a.onclick = function(e)
	{
		e = e || event;
		
		var nt = qmv_lib_get_nextsibling_atag(this);
		if (nt)
			qm_oo(e,nt);
	}
	*/
	a.onclick = function(e)
	{
		e = e || event;
		
		//var nt = qmv_lib_get_nextsibling_atag(this);
		//if (nt)
			qm_oo(e,this);
	}

	a.ondblclick = function(e)
	{

		e = e || event;

		var a = document.getElementById("qmvtree_item_titles").idiv;
		qmv_display_setbox(a,null,null,true,2);
		
		//var nt = qmv_lib_get_nextsibling_atag(this);
		//if (nt)
			qm_oo(e,this);
	}


}


function qmv_attach_container_events(obj)
{

	if (obj.attachEvent)
	{
		obj.attachEvent("onmousedown",qmv_container_mouse_down);
		obj.attachEvent("onmousemove",qmv_container_mouse_move);
		obj.attachEvent("onmouseup",qmv_container_mouse_up);
	}
	else if (obj.addEventListener)
	{
		obj.addEventListener("mousedown",qmv_container_mouse_down,false);
		obj.addEventListener("mousemove",qmv_container_mouse_move,false);
		obj.addEventListener("mouseup",qmv_container_mouse_up,false);
	}


}


function qmv_container_mouse_down(e)
{

	e = e || event;
	var targ = e.srcElement || e.target;
	qmv.container_rtarg = targ;
	while(targ.tagName!="DIV") targ=targ[qp];



	if(qmv.addons.tree_menu["on"+qmv.id])	
	{
		qm_kille(e);
		return false;
	}

	
	qmv.container_mdown = true;
	qmv.container_prev_x = e.screenX;
	qmv.container_prev_y = e.screenY;
	qmv.orig_x = e.screenX;
	qmv.orig_y = e.screenY;
	qmv.container_obj = targ;

	var n;
	if (n = targ.hasrcorner)
	{
		n.origleft = n.style.left;
		n.origtop = n.style.top;
	}
		
	if (n = targ.hasshadow)
	{
		n.origleft = n.style.left;
		n.origtop = n.style.top;
	}	
	
	targ.style.cursor = "move";

	qm_kille(e);
	return false;
}


function qmv_container_mouse_move(e,src)
{

	e = e || event;
	
	var targ;
	if (!qmv.container_moved)
	{
		var targ = e.srcElement || e.target;
		if (src)
			targ = src;
		else
			while(targ.tagName!="DIV") targ=targ[qp];
	}
	else
		targ = qmv.container_obj;


	if (qmv.container_mdown)
	{
		

		qmv_hide_pointer("qm"+qmv.id);

		var m = qmv.container_obj;
	
		var tx = qmv.orig_x-e.screenX;
		var ty = qmv.orig_y-e.screenY;
		if (qmv.container_moved || Math.abs(tx)>5 || Math.abs(ty)>5)
		{
	
		
			var xdif = qmv.container_prev_x-e.screenX;
			var ydif = qmv.container_prev_y-e.screenY;
	

			
			var ml =qm_lib_get_computed_style(m,"margin-left","marginLeft");
			if (ml) ml = parseInt(ml);
			if (!ml || isNaN(ml)) ml = 0;

			var mt = qm_lib_get_computed_style(m,"margin-top","marginTop");
			if (mt) mt = parseInt(mt);
			if (!mt || isNaN(mt)) mt = 0;

						
			m.style.marginLeft = (ml-xdif)+"px";
			m.style.marginTop = (mt-ydif)+"px";

			
			var n;
			if (n = m.hasrcorner)
			{	
				n.style.top = (n.offsetTop-ydif)+"px";
				n.style.left = (n.offsetLeft-xdif)+"px";
			}

			if (n = targ.hasshadow)
			{
				n.style.top = (n.offsetTop-ydif)+"px";
				n.style.left = (n.offsetLeft-xdif)+"px";
			}

			qmv.container_prev_x = e.screenX;
			qmv.container_prev_y = e.screenY;

			qmv.container_moved = true;		
		
			
			if (qmad.br_ie)
				qmv.container_obj.setCapture(true);



			qm_kille(e);
			return false;


		}		
		

		
		
	}
}


function qmv_container_mouse_up(e)
{

	e = e || event;
	var targ = e.srcElement || e.target;
	while(targ.tagName!="DIV") targ=targ[qp];

	
	if (qmv.container_moved)
	{
		

		var m = qmv.container_obj;
			
	
		var ml = 0;
		if (m.style.marginLeft)
		{
			ml = parseInt(m.style.marginLeft);
			if (isNaN(ml)) ml = 0;
		}


		var mt = 0;
		if (m.style.marginTop)
		{
			mt = parseInt(m.style.marginTop);
			if (isNaN(mt)) mt = 0;
		}


		var mr = "0px";
		if (m.style.marginRight) mr = m.style.marginRight;

		var mb = "0px";
		if (m.style.marginBottom) mr = m.style.marginBottom;


		
		

		qmv.container_pos = mt+"px "+mr+" "+mb+" "+ml+"px";
		qmv_show_dialog("subposition")


		if (qmad.br_ie)
			qmv.container_obj.releaseCapture(true);


		qmv.container_rtarg = null;

	}



	qmv.container_moved = false;
	qmv.container_mdown = false;
	
	
	targ.style.cursor = "";

}


function qmv_evt_add_hover_item_in_visual_event(a)
{

	
	if (window.detachEvent)
		a.detachEvent("onmouseover",qmv_image_hover);
	else if (window.removeListener)
		a.removeEventListener("mouseover",qmv_image_hover,true);

	
	if (window.attachEvent)
		a.attachEvent("onmouseover",qmv_image_hover);
	else if (window.addEventListener)
		a.addEventListener("mouseover",qmv_image_hover,true);


}

function qmv_evt_hover_item_in_visual(e)
{

	e = e || window.event;

	var targ = e.srcElement || e.target;
	while (targ && targ.tagName!="A")
		targ = targ[qp];
	
	
	qm_image_switch(targ);

}


function qmvi_color_recent_remove(a)
{

	for (var i=0;i<qmv.color_recent.length;i++)
	{
		if (qmv.color_recent[i].inp==a)
		{
			qmv.color_recent.splice(i,1);
			return;		
		}

	}



	

}

function qmvi_color_recent_add(a,update)
{

	if (!a.value) return;

	color = a.value.toLowerCase();
	if (color=="transparent")
	{
		if (update)
			qmvi_color_recent_remove(a)

		return;
	}

	for (var i=0;i<qmv.color_recent.length;i++)
	{
		if (qmv.color_recent[i].inp==a)
		{
			qmv.color_recent[i].value = color;
			return;		
		}
	}


	var tobj = new Object();
	tobj.value = color;
	tobj.inp = a;

	

	qmv.color_recent.push(tobj);

}


function qmvi_color_open_recent(a)
{

	
	if (!qmv.color_recent.length)
		return;

	var robj = document.getElementById("qmvi_color_recent");




	if (robj.style.visibility=="hidden")
	{
		
		var sw = (a.offsetWidth-2);
		robj.style.width = sw+"px";
		
		
		
		var tcolor = new Array();

		var ih = "";
		for (var i=0;i<qmv.color_recent.length;i++)
		{	

			var addm = "";
			if (i<qmv.color_recent.length-1)
				addm = "margin-bottom:1px;";

			var con=false;
			for (var j=0;j<tcolor.length;j++)
			{
				if (tcolor[j]==qmv.color_recent[i].value)
				{
					con = true;
					break;	
				}
			}			
			if (con) continue;
			
			ih += '<div rid='+i+' onclick="qmvi_color_set_recent(this)" style="background-color:'+qmv.color_recent[i].value+';height:25px;width:'+sw+'px;'+addm+'"></div>';
			tcolor.push(qmv.color_recent[i].value);

		}
		robj.innerHTML = ih;

		robj.style.visibility = "inherit";

		var io = document.getElementById("qmvi_color_indicator").getElementsByTagName("IMG")[0];
		if (io)
			io.src = qmv.base+"images/color_minus.gif";

		
	}
	else
		qmvi_color_recent_close();


}

function qmvi_color_recent_close()
{

	var robj = document.getElementById("qmvi_color_recent");
	robj.style.visibility = "hidden";

	var io = document.getElementById("qmvi_color_indicator").getElementsByTagName("IMG")[0];
	if (io)
		io.src = qmv.base+"images/color_plus.gif";


}


function qmvi_color_set_recent(a)
{

	var rid = a.getAttribute("rid");
	qmv_color_init_color(qmv.color_recent[rid].value);

	qmvi_color_recent_close();
}


function qmv_color_brightness_mousedown(e,a)
{

	e = e || event;

	
	qmv.color_vals.bright_down = true;	
	

	qmv_color_brightness_mouse_setxy(e,a)

	qm_kille(e);


}

function qmv_color_brightness_mousemove(e,a)
{

	if (!qmv.color_vals.bright_down)
		return;

	e = e || event;

	qmv_color_brightness_mouse_setxy(e,a)
	qmv_color_brightness_mouse_set(qmv.color_vals.ly,a);	
}

function qmv_color_brightness_mouse_setxy(e,a)
{

	var y;

	if (!isNaN(e.layerY))
		y = e.layerY;
	else if (!isNaN(e.y))
		y = e.y;

	if (!qmad.br_ie)
		y = y-1;
	
	if (y<0) y=0;
	if (y>186) y=186;
	
	qmv.color_vals.ly = y;
}


function qmv_color_brightness_mouse_set(y,a)
{
		
	
	var b = (186-y)/(a.offsetHeight-1);
		
	qmv_color_parse_split("hsb:"+qmv.color_vals.h+","+qmv.color_vals.s+","+b);

	
	qmv_color_set_fields();

	qmv.color_vals.z = y;
	qmv_color_position_arrows("brightbar");


}

function qmv_color_brightness_mouseup(e,a)
{
	e = e || event;

	if (qmv.color_vals.bright_down)
	{
		qmv_color_brightness_mouse_set(qmv.color_vals.ly,a);

		qmv.color_vals.bright_down = false;	

	}
}


function qmv_color_huesaturation_mousedown(e,a)
{
	e = e || event;

	qmv.color_vals.crosshair.style.visibility = "hidden";
	qmv.color_vals.hs_down = true;	

	qmv_color_huesaturation_mouse_setxy(e,a);

	qm_kille(e);
}

function qmv_color_huesaturation_mousemove(e,a)
{



	if (!qmv.color_vals.hs_down)
		return;

	e = e || event;

	
	qmv_color_huesaturation_mouse_setxy(e,a)
	qmv_color_huesaturation_mouse_set(qmv.color_vals.lx,qmv.color_vals.ly,a);

	
	
}

function qmv_color_huesaturation_mouse_setxy(e,a)
{

	var x,y;
	
	if (!isNaN(e.layerX))
	{
		x = e.layerX;
		y = e.layerY;
	}
	else if (!isNaN(e.x))
	{
		x = e.x;
		y = e.y;
	}
	
	if (!qmad.br_ie)
	{
		x = x-1;
		y = y-1;
	}

	if (x<0) x=0;
	if (x>174) x=174;
	if (y<0) y=0;
	if (y>186) y=186;

	qmv.color_vals.lx = x;
	qmv.color_vals.ly = y;



}

function qmv_color_huesaturation_mouse_set(x,y,a)
{

	


	var h = x/(a.offsetWidth-1);
	var s = (186-y)/(a.offsetHeight-1);
	
	qmv_color_parse_split("hsb:"+h+","+s+","+qmv.color_vals.b);
	qmv_color_set_fields();

	qmv.color_vals.x = x;
	qmv.color_vals.y = y;
	qmv_color_position_arrows("arrows");

	
	

}



function qmv_color_huesaturation_mouseup(e,a)
{
	e = e || event;

	if (qmv.color_vals.hs_down)
	{

		qmv_color_huesaturation_mouse_set(qmv.color_vals.lx,qmv.color_vals.ly,a);
		qmv_color_position_arrows("crosshair");

		qmv.color_vals.crosshair.style.visibility = "inherit";
		qmv.color_vals.hs_down = false;	
	}

	
}



function qmv_color_position_arrows(type,all)
{

	if (type=="arrows" || all)	
	{
		qmv.color_vals.hs_arrow_down.style.left = qmv.color_vals.x+"px";
		qmv.color_vals.hs_arrow_right.style.top = qmv.color_vals.y+"px";

		var dd = qmv.color_vals.brightbar_dd
		for (var i=0;i<dd.length;i++)
		{
			dd[i].style.backgroundColor = "rgb("+qmv.color_vals.bbvals[16-i][0]+","+qmv.color_vals.bbvals[16-i][1]+","+qmv.color_vals.bbvals[16-i][2]+")";

		}
		
	}
	
	if (type=="brightbar" || all)
	{
		qmv.color_vals.b_arrow_right.style.top = qmv.color_vals.z+"px";

	}

	if (type=="crosshair" || all)	
	{
		qmv.color_vals.crosshair.style.top = qmv.color_vals.y+"px";
		qmv.color_vals.crosshair.style.left = qmv.color_vals.x+"px";
	}


	qmv.color_vals.indicator.style.backgroundColor = "#"+qmv.color_vals.hr+qmv.color_vals.hg+qmv.color_vals.hb;
		

}

function qmv_color_init(value)
{



	qmv.color_vals = new Object();


	var tval = value.toLowerCase();	
	if (!value || tval.indexOf("transparent")+1)
		value = "#b3c2b9";

	if (tval.indexOf("#")==-1 && tval.indexOf("rgb")==-1)
	{
		var tset = document.createElement("BODY");
		tset.bgColor = value;
		value = tset.bgColor;
	}

	qmv.color_vals.hs_arrow_right = document.getElementById("qmvi_color_hs_right");
	qmv.color_vals.hs_arrow_down = document.getElementById("qmvi_color_hs_down");
	qmv.color_vals.b_arrow_right = document.getElementById("qmvi_color_bright_right");
	qmv.color_vals.indicator = document.getElementById("qmvi_color_indicator");
	qmv.color_vals.crosshair = document.getElementById("qmvi_color_hs_crosshair");
	qmv.color_vals.brightbar = document.getElementById("qmvi_color_bright_bar");
	qmv.color_vals.brightbar_dd = qmv.color_vals.brightbar.getElementsByTagName("DIV");

	qmv.color_vals.robj = document.getElementById("qmv_cdialog_r");
	qmv.color_vals.gobj = document.getElementById("qmv_cdialog_g");
	qmv.color_vals.bobj = document.getElementById("qmv_cdialog_b");
	qmv.color_vals.t_robj = document.getElementById("qmvi_cdialog_rtext");
	qmv.color_vals.t_gobj = document.getElementById("qmvi_cdialog_gtext");
	qmv.color_vals.t_bobj = document.getElementById("qmvi_cdialog_btext");


	var cform = document.getElementById("qmvi_color_switches");

	qmv.color_vals.cform = cform;


	if (qmv.color_dispaly_type=="HEX")
		cform.qmvi_color_switch[0].checked = true;
	else if (qmv.color_dispaly_type=="RGB")
		cform.qmvi_color_switch[1].checked = true;
	else if (qmv.color_dispaly_type=="HSB")
		cform.qmvi_color_switch[2].checked = true;

	var o_hex = document.getElementById("qmvi_color_apply_type_hex");
	var o_rgb = document.getElementById("qmvi_color_apply_type_rgb");

	if (qmv.color_apply_type=="HEX")
		o_hex.checked = true;
	else
		o_rgb.checked = true;

	qmv_color_init_color(value);
	
}

function qmv_color_init_color(value)
{

	
	qmv_color_parse_split(value);
	qmv_color_set_fields();

	qmv.color_vals.x = parseInt(qmv.color_vals.h*174);
	qmv.color_vals.y = 186-parseInt(qmv.color_vals.s*186);
	qmv.color_vals.z = 186-parseInt(qmv.color_vals.b*186);

	qmv_color_position_arrows(null,true);



}


function qmv_color_set_fields()
{


	var cform = qmv.color_vals.cform;

	var type = "RGB";	
	if (cform.qmvi_color_switch[1].checked)
		type = "HEX";
	else if (cform.qmvi_color_switch[2].checked)		
		type = "HSB";

	

	if (type=="RGB")
	{
		qmv.color_vals.t_robj.innerHTML = "Red:"
		qmv.color_vals.t_gobj.innerHTML = "Green:"
		qmv.color_vals.t_bobj.innerHTML = "Blue:"

		qmv.color_vals.robj.value = qmv.color_vals.dr;
		qmv.color_vals.gobj.value = qmv.color_vals.dg;
		qmv.color_vals.bobj.value = qmv.color_vals.db;
	}
	else if (type=="HEX")
	{
		qmv.color_vals.t_robj.innerHTML = "Red:"
		qmv.color_vals.t_gobj.innerHTML = "Green:"
		qmv.color_vals.t_bobj.innerHTML = "Blue:"


		qmv.color_vals.robj.value = qmv.color_vals.hr;
		qmv.color_vals.gobj.value = qmv.color_vals.hg;
		qmv.color_vals.bobj.value = qmv.color_vals.hb;


	}
	else if (type=="HSB")
	{

		qmv.color_vals.t_robj.innerHTML = "Hue:"
		qmv.color_vals.t_gobj.innerHTML = "Sat:"
		qmv.color_vals.t_bobj.innerHTML = "Lum:"
	

		qmv.color_vals.robj.value = parseInt(qmv.color_vals.h*100);
		qmv.color_vals.gobj.value = parseInt(qmv.color_vals.s*100);
		qmv.color_vals.bobj.value = parseInt(qmv.color_vals.b*100);

	}


}



function qmv_color_parse_split(c,rgbonly)
{

	c = c.toLowerCase();
	var dr,dg,db;
	var hr,hg,hb;
	var h,s,b;

	if (c.indexOf("#")+1)
	{
		c = c.replace("#","");
		if (c.length==6)
		{
			hr = c.substring(0,2);
			hg = c.substring(2,4);
			hb = c.substring(4,6);

			eval("dr = 0x"+hr);
			eval("dg = 0x"+hg);
			eval("db = 0x"+hb);

		}
		else
		{
			hr = c.substring(0,1);
			hg = c.substring(1,2);
			hb = c.substring(2,3);

			hr = hr+hr;
			hg = hg+hg;
			hb = hb+hb;

			eval("dr = 0x"+hr);
			eval("dg = 0x"+hg);
			eval("db = 0x"+hb);

		}

		
		var t = qmv_color_rgb_to_hsb(dr,dg,db);
		h  = t[0];
		s  = t[1];
		b  = t[2];



	}
	else if (c.indexOf("rgb")+1)
	{
		c = c.replace("rgb(","");
		c = c.replace(")","");	
		c = c.replace(/\,/g," ");
		var nc = c.split(" ");
		
		c = "";
		for (i=0;i<nc.length;i++)
		{
			if (nc[i])
				c+=nc[i]+",";
		}


		c = c.split(",");
		
		dr = c[0];
		dg = c[1];
		db = c[2];

		hr = qmv_ten_to_hex(dr,16);
		hg = qmv_ten_to_hex(dg,16);
		hb = qmv_ten_to_hex(db,16);

		if (rgbonly)
			return "#"+hr+hg+hb;


		var t = qmv_color_rgb_to_hsb(dr,dg,db);
		h  = t[0];
		s  = t[1];
		b  = t[2];
	

	}
	else if (c.indexOf("hsb:")+1)
	{
		
		c = c.replace("hsb:","");
		c = c.split(",");

		h = parseFloat(c[0]);
		s = parseFloat(c[1]);
		b = parseFloat(c[2]);
		
		
		c = qmv_color_hsb_to_rgb(h,s,b);
		dr = c[0];
		dg = c[1];
		db = c[2];

		

		hr = qmv_ten_to_hex(dr,16);
		hg = qmv_ten_to_hex(dg,16);
		hb = qmv_ten_to_hex(db,16);
		

	}

	

	qmv.color_vals.dr = dr;
	qmv.color_vals.dg = dg;
	qmv.color_vals.db = db;

	qmv.color_vals.hr = hr;
	qmv.color_vals.hg = hg;
	qmv.color_vals.hb = hb;

	qmv.color_vals.h = h;
	qmv.color_vals.s = s;
	qmv.color_vals.b = b;

	
	qmv.color_vals.bbvals = new Array(17);
	for (var i=0;i<17;i++)
	{
		qmv.color_vals.bbvals[i] = qmv_color_hsb_to_rgb(h,s,i/17);
	}

	return qmv.color_vals;	

}


function qmv_color_hsb_to_rgb(H,S,L)
{

	var R,G,B,var_2,var_1;

	if ( S == 0 )
	{
		R = L * 255
		G = L * 255
		B = L * 255
	}
	else
	{
		if ( L < 0.5 )
			var_2 = L * ( 1 + S )
		else
			var_2 = ( L + S ) - ( S * L )

		var_1 = 2 * L - var_2

		R = 255 * qmv_color_rgb_hsb2( var_1, var_2, H + ( 1 / 3 ) ) 
		G = 255 * qmv_color_rgb_hsb2( var_1, var_2, H )
		B = 255 * qmv_color_rgb_hsb2( var_1, var_2, H - ( 1 / 3 ) )
	}

	

	return new Array(Math.round(R),Math.round(G),Math.round(B)); 

}


function qmv_color_rgb_hsb2(v1, v2, vH)
{
   if ( vH < 0 ) vH += 1
   if ( vH > 1 ) vH -= 1
   if ( ( 6 * vH ) < 1 ) return ( v1 + ( v2 - v1 ) * 6 * vH )
   if ( ( 2 * vH ) < 1 ) return ( v2 )
   if ( ( 3 * vH ) < 2 ) return ( v1 + ( v2 - v1 ) * ( ( 2 / 3 ) - vH ) * 6 )
   return ( v1 )
}




function qmv_color_rgb_to_hsb(R,G,B)
{

	var var_R,var_G,var_B,var_Max,var_Min,H,S,L,del_R,del_G,del_B,del_Max;

	var_R = ( R / 255 )
	var_G = ( G / 255 )
	var_B = ( B / 255 )


	

	var_Min = var_R;
	if (var_G<var_Min) var_Min = var_G;
	if (var_B<var_Min) var_Min = var_B;

	var_Max = var_R;
	if (var_G>var_Max) var_Max = var_G;
	if (var_B>var_Max) var_Max = var_B;


	

	del_Max = var_Max - var_Min;

	L = ( var_Max + var_Min ) / 2

	if ( del_Max == 0 )
	{
		H = 0
		S = 0
	}
	else
	{
		if ( L < 0.5 ) 
			S = del_Max / ( var_Max + var_Min );
		else
			S = del_Max / ( 2 - var_Max - var_Min )

		del_R = ( ( ( var_Max - var_R ) / 6 ) + ( del_Max / 2 ) ) / del_Max
		del_G = ( ( ( var_Max - var_G ) / 6 ) + ( del_Max / 2 ) ) / del_Max
		del_B = ( ( ( var_Max - var_B ) / 6 ) + ( del_Max / 2 ) ) / del_Max

		if ( var_R == var_Max )
			H = del_B - del_G
		else if ( var_G == var_Max )
			H = ( 1 / 3 ) + del_R - del_B
		else if ( var_B == var_Max )
			H = ( 2 / 3 ) + del_G - del_R

		if ( H < 0 ) H += 1
		if ( H > 1 ) H -= 1
	}


	return new Array(H,S,L);
}





function qmv_ten_to_hex(value,radix)
{
	var retval = '';
	var ConvArray = new Array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F');
	var intnum;
	var tmpnum;
	var i = 0;

	intnum = parseInt(value,10);
	if (isNaN(intnum))
	{
		retval = 'NaN';
	}
	else
	{
		while (intnum > 0.9)
		{
			i++;
			tmpnum = intnum;
			retval = ConvArray[tmpnum % radix] + retval;  
			intnum = Math.floor(tmpnum / radix);
			if (i > 100)
			{
				retval = 'NaN';
				break;
			}
		}
	}

	if (retval.length<2)
		retval = 0+retval;

	if (!retval || retval=="0")
		retval = "00";
	
	return retval;
}

function qmv_color_field_onchange_enter(e,a)
{

	e = e || event;

	if (e.keyCode==13)
		qmv_color_field_onchange(e,a);


	qm_kille(e,true);

}


function qmv_color_field_onchange(e,a)
{

	

	var new_color = "";
	
	var cform = document.getElementById("qmvi_color_switches");

	var type = "RGB";	
	if (cform.qmvi_color_switch[1].checked)
		type = "HEX";
	else if (cform.qmvi_color_switch[2].checked)		
		type = "HSB";

	var robj = document.getElementById("qmv_cdialog_r");
	var gobj = document.getElementById("qmv_cdialog_g");
	var bobj = document.getElementById("qmv_cdialog_b");

	
	if (type=="RGB")
	{

		if (!a.value || isNaN(parseInt(a.value)) || parseInt(a.value)<0 || parseInt(a.value)>255)
		{
			qmv_show_dialog("alert",null,"The value you entered is not valid.<br><br>RGB values must be between 0 and 255.",450);
			a.value = a.pvalue;
			return;
		}	

		new_color += "rgb(";
		new_color += robj.value+",";
		new_color += gobj.value+",";
		new_color += bobj.value;
		new_color += ")";

	}
	else if (type=="HEX")
	{


		if (!a.value || eval("0x"+a.value)<0 || eval("0x"+a.value)>255)
		{
			qmv_show_dialog("alert",null,"The value you entered is not valid.<br><br>  Hexadecimal values must be between 0 and 255.",450);
			a.value = a.pvalue;
			return;
		}

		new_color += "#";
		new_color += robj.value;
		new_color += gobj.value;
		new_color += bobj.value;

	}
	else if (type=="HSB")
	{

		if (!a.value || isNaN(parseInt(a.value)) || parseInt(a.value)<0 || parseInt(a.value)>100)
		{
			qmv_show_dialog("alert",null,"The value you entered is not valid.<br><br>HSB values must be between 0 and 100.",450);
			a.value = a.pvalue;
			return;
		}

		new_color += "hsb:";
		new_color += (parseInt(robj.value)/100)+",";
		new_color += (parseInt(gobj.value)/100)+",";
		new_color += parseInt(bobj.value)/100;

	}


	qmv_color_init_color(new_color);


}


function qmv_url_init()
{
	var text = document.getElementById("qmvi_df_urlp_text");
	var url = document.getElementById("qmvi_df_urlp_url");
	var target = document.getElementById("qmvi_df_urlp_target");
	var title = document.getElementById("qmvi_df_urlp_title");

	text.value = qmv_set_texturl_field(qmv.cur_item,true);
	url.value = qmv.cur_item.getAttribute("href",2);
	target.value = qmv.cur_item.getAttribute("target",2);
	title.value = qmv.cur_item.getAttribute("title",2);

}



function qmv_multi_init(qmd)
{


	var multi = document.getElementById("qmvi_multi_value");
	multi.value = qmd.owner.value;



}

function qmv_edge_init(qmd)
{
	
	

	var top = document.getElementById("qmvi_df_edge_top");
	var bottom = document.getElementById("qmvi_df_edge_bottom");
	var left = document.getElementById("qmvi_df_edge_left");
	var right = document.getElementById("qmvi_df_edge_right");


	var vals = qmv_lib_parse_edge_values(qmd.owner.value,qmv.edge.dtype);


	top.value = vals.top;
	bottom.value = vals.bottom;
	left.value = vals.left;
	right.value = vals.right;

}


function qmv_evt_edge_spin(name,spin,dtype)
{

	var inp = document.getElementById("qmvi_df_edge_"+name);
	inp.value = qmv_spin_value(spin,dtype,inp.value);
	

}


function qmv_spin_value(spin,dtype,value)
{

	var num;
	
	if (!value)
	{
		if (dtype=="bool")
		{
			return true;


		}
		else
		{
			if (dtype=="unit")
				return "0px";
			else
				return 0;
		}

	}

	if (dtype=="bool")
	{

		if (value.toLowerCase()=="true")
			return false;
		else
			return true;

	}


	var unit = qmv_lib_get_units(value);
	if (dtype!="unit" && dtype!="float")
	{
		unit = "";
		num = parseInt(value);
	}
	else
	{
		num = parseFloat(value);
		if (!unit && dtype!="float") unit = "px";
	}

	if (isNaN(num))
		return 0;


	var isfloat = false;
	if (dtype=="float" || (unit && unit!="px" && unit!="mm" && unit!="%"))
		isfloat = true;


	

	if (isfloat)
	{
		
		if (spin==1)
			num = Math.round((num+.1)*10)/10
		else if (spin==2)
			num =  Math.round((num-.1)*10)/10
	}
	else
	{

		if (spin==1)
			num++;
		else if (spin==2)
			num--;


	}

	
	return num+unit;		


}





function qmv_lib_parse_edge_values(vals,dtype)
{
	
	var rvals = new Object();

	if (dtype=="bool")
	{
		rvals.top=true;
		rvals.right=true;
		rvals.bottom=true;
		rvals.left=true;
	}
	else
	{
		if (dtype=="unit")
		{
			rvals.top="0px";
			rvals.right="0px";
			rvals.bottom="0px";
			rvals.left="0px";
		}
		else		
		{
			rvals.top=0;
			rvals.right=0;
			rvals.bottom=0;
			rvals.left=0;
		}

	}

	if (!vals)
		return rvals;
	
	var svals;
	if (vals.indexOf(",")+1)
		svals = vals.split(",");
	else
		svals = vals.split(" ");

	var pvals = new Object();
	var punits = new Object();
	var len = 0;
	for (var i=0;i<svals.length;i++)
	{
		if (svals[i] || (typeof svals[i] == "number"))
		{
			len++;
			pvals[i] = svals[i];
			if (dtype=="bool")
				punits[i] = "";
			else
				punits[i] = qmv_lib_get_units(svals[i]);
		}	

	}
	pvals.length = len;
	
	
	if (pvals.length==1)
	{
		rvals.top = parseInt(pvals[0])+punits[0];
		rvals.right = parseInt(pvals[0])+punits[0];
		rvals.bottom = parseInt(pvals[0])+punits[0];
		rvals.left = parseInt(pvals[0])+punits[0];
			
	}
	else if (pvals.length==2)
	{
		rvals.left = parseInt(pvals[1])+punits[1];
		rvals.right = parseInt(pvals[1])+punits[1];
		rvals.top = parseInt(pvals[0])+punits[0];
		rvals.bottom = parseInt(pvals[0])+punits[0];
	
	}
	else if (pvals.length==3)
	{

		rvals.top = parseInt(pvals[0])+punits[0];
		rvals.right = parseInt(pvals[1])+punits[1];
		rvals.bottom = parseInt(pvals[2])+punits[2];
		rvals.left = parseInt(pvals[1])+punits[1];

	}
	else if (pvals.length>3)
	{
		if (dtype=="bool")
		{	
			rvals.top = pvals[0];
			rvals.right = pvals[1];
			rvals.bottom = pvals[2];
			rvals.left = pvals[3];
		}
		else
		{
			rvals.top = parseInt(pvals[0])+punits[0];
			rvals.right = parseInt(pvals[1])+punits[1];
			rvals.bottom = parseInt(pvals[2])+punits[2];
			rvals.left = parseInt(pvals[3])+punits[3];

		}
	
	}

	return rvals;

}



function qmv_publish_init(qmd)
{


	var p = qmv.publish;


	if (qmv.publish.page==1)
	{
		
		
		if (qmv.publish.css_type == "external")
			document.getElementById('qmvi_publish_css_external').checked = true;
		else
			document.getElementById('qmvi_publish_css_inpage').checked = true;

		if (qmv.publish.code_type == "external")
			document.getElementById('qmvi_publish_code_external').checked = true;
		else
			document.getElementById('qmvi_publish_code_inpage').checked = true;

		if (qmv.publish.structure_type == "external")
			document.getElementById('qmvi_publish_structure_external').checked = true;
		else
			document.getElementById('qmvi_publish_structure_inpage').checked = true;


		if (qmv.pure)
			document.getElementById('qmvi_publish_structure_type_pure').checked = true;
		else
			document.getElementById('qmvi_publish_structure_type_hybrid').checked = true;	

	}
	



}



function qmv_pubgen_css(is_external,is_scripted)
{

	


	var t = "\t";
	var rt = "\r\n";
	var wt = "";

	if (is_scripted)
	{
		rt = "";
		t = "";
	}

	

	if (!is_external)
	{


		wt += '<!--%%%%%%%%%%%% QuickMenu Styles [Keep in head for full validation!] %%%%%%%%%%%-->';
		wt += rt;
		wt += '<style type="text/css">';
		wt += rt;
		wt += rt;
		wt += rt;
	}


	wt += '/*!!!!!!!!!!! QuickMenu Core CSS [Do Not Modify!] !!!!!!!!!!!!!*/';
	wt += rt;
	wt += qmv_pubgen_get_core_css();
	wt += rt;
	wt += rt;

	wt += '/*!!!!!!!!!!! QuickMenu Styles [Please Modify!] !!!!!!!!!!!*/';
	wt += rt;
	wt += rt;


	

	for (var i=0;i<10;i++)
	{
		if (!document.getElementById("qm"+i))
			continue;

		
		wt += rt;
		wt += rt;

		var rb = new Array();

		var ta = new Object;
		ta.desc = "(MAIN) Container";
		ta.rule = '#qm'+i;
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(MAIN) Items";
		ta.rule = '#qm'+i+" a";
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(MAIN) Hover State";
		ta.rule = '#qm'+i+" a:hover";
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(MAIN) Parent items";
		ta.rule = '#qm'+i+' .qmparent';
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(MAIN) Active State";
		ta.rule = 'body #qm'+i+' .qmactive';
		ta.rule_show = 'body #qm'+i+' .qmactive, body #qm'+i+' .qmactive:hover';
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(MAIN) Persistent State";
		ta.rule = 'body #qm'+i+' .qmpersistent';
		ta.rule_show = 'body #qm'+i+' .qmpersistent, body #qm'+i+' .qmpersistent:hover';
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(SUB) Container";
		ta.rule = '#qm'+i+" div";
		if (qmv.pure) ta.rule_show = '#qm'+i+" div,"+' #qm'+i+" ul"
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(SUB) Items";
		ta.rule = '#qm'+i+" div a";
		if (qmv.pure) ta.rule_show = '#qm'+i+" div a,"+' #qm'+i+" ul a";
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(SUB) Hover State";
		ta.rule = '#qm'+i+" div a:hover";
		if (qmv.pure) ta.rule_show = '#qm'+i+" div a:hover,"+' #qm'+i+" ul a:hover";
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(SUB) Parent items";
		ta.rule = '#qm'+i+' div .qmparent';
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(SUB) Active State";
		ta.rule = 'body #qm'+i+' div .qmactive';
		ta.rule_show = 'body #qm'+i+' div .qmactive, body #qm'+i+' div .qmactive:hover';
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(SUB) Persistent State";
		ta.rule = 'body #qm'+i+' div .qmpersistent';
		ta.rule_show = 'body #qm'+i+' div .qmpersistent, body #qm'+i+' div .qmpersistent:hover';
		rb.push(ta);

		
		var ta = new Object;
		ta.desc = "Individual Titles";
		ta.rule = '#qm'+i+' .qmtitle';
		ta.spec_tree_obj = document.getElementById("qmvtree_item_titles").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "Individual Horizontal Dividers";
		ta.rule = '#qm'+i+' .qmdividerx';
		ta.spec_tree_obj = document.getElementById("qmvtree_item_dividers").getElementsByTagName("DIV");
		rb.push(ta);
		
		var ta = new Object;
		ta.desc = "Individual Vertical Dividers";
		ta.rule = '#qm'+i+' .qmdividery';
		ta.spec_tree_obj = document.getElementById("qmvtree_item_dividers").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "Item Stripes";
		ta.rule = '#qm'+i+' .qmstripe';
		ta.spec_tree_obj = document.getElementById("qmvtree_item_stripes").getElementsByTagName("DIV");
		rb.push(ta);


		var ta = new Object;
		ta.desc = "Item Stripes - hover";
		ta.rule = '#qm'+i+' .qmstripe:hover';
		ta.spec_tree_obj = document.getElementById("qmvtree_item_stripes").getElementsByTagName("DIV");
		rb.push(ta);


		var ta = new Object;
		ta.desc = "Box Animation Styles";
		ta.rule = '#qm'+i+' .qmbox';
		ta.spec_tree_obj = document.getElementById("qmvtree_box").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(main) Rounded Items";
		ta.rule = '#qm'+i+' .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(main) Rounded Items Content";
		ta.rule = '#qm'+i+' .qmritemcontent';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(main) Rounded Items Hover";
		ta.rule = '#qm'+i+' a:hover .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(main) Rounded Items Active";
		ta.rule = 'body #qm'+i+' .qmactive .qmritem span';
		ta.rule_show = 'body #qm'+i+' .qmactive .qmritem span, body #qm'+i+' .qmactive:hover .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(main) Rounded Items Persistent";
		ta.rule = 'body #qm'+i+' .qmpersistent .qmritem span';
		ta.rule_show = 'body #qm'+i+' .qmpersistent .qmritem span, body #qm'+i+' .qmpersistent:hover .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);	

		var ta = new Object;
		ta.desc = "(sub) Rounded Items";
		ta.rule = '#qm'+i+' div .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);
		
		var ta = new Object;
		ta.desc = "(sub) Rounded Items Content";
		ta.rule = '#qm'+i+' div .qmritemcontent';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(sub) Rounded Items Hover";
		ta.rule = '#qm'+i+' div a:hover .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);

		var ta = new Object;
		ta.desc = "(sub) Rounded Items Active";
		ta.rule = 'body #qm'+i+' div .qmactive .qmritem span';
		ta.rule_show = 'body #qm'+i+' div .qmactive .qmritem span, body #qm'+i+' div .qmactive:hover .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);
		
		var ta = new Object;
		ta.desc = "(sub) Rounded Items Persistent";
		ta.rule = 'body #qm'+i+' div .qmpersistent .qmritem span';
		ta.rule_show = 'body #qm'+i+' div .qmpersistent .qmritem span, body #qm'+i+' div .qmpersistent:hover .qmritem span';
		ta.spec_tree_obj = document.getElementById("qmvtree_ritem").getElementsByTagName("DIV");
		rb.push(ta);
		
		var cdivs = document.getElementById("qmvtree_custom_rules").getElementsByTagName("DIV");
		for (var j=0;j<cdivs.length;j++)
		{

			if (cdivs[j].ismaster)
			{
				
				var ta = new Object;
				ta.desc = "Custom Rule";
			
				var crr = cdivs[j].getAttribute("rule");
				crr = crr.replace("qm[i]","qm"+qmv.id);
				crr = crr.replace("qm[i]","qm"+qmv.id);

				var crro = cdivs[j].getAttribute("orule");
				crro = crro.replace("qm[i]","qm"+qmv.id);
				crro = crro.replace("qm[i]","qm"+qmv.id);
				
				ta.rule = crr;
				ta.rule_show = crro;
				ta.spec_tree_obj = cdivs;
				rb.push(ta);

			}

		}



		wt += t+'/* QuickMenu '+i+' */';
		wt += rt;
		wt += rt;

		for (var j=0;j<rb.length;j++)
		{

			var wrules = qmv_genpub_css_rule(rb[j].rule,i,t+t,rt,rb[j].spec_tree_obj);

			
			
			if (wrules)
			{
				if (!is_scripted)
					wt += t+'/*"""""""" '+rb[j].desc+'""""""""*/';

				wt += t+rt;
		
				if (rb[j].rule_show)
					wt += t+rb[j].rule_show;
				else
					wt += t+rb[j].rule;

				wt += t+rt;
				wt += t+'{'; 
				wt += t+rt;
				wt += wrules;
				wt += t+'}'; 

				wt += rt;
				wt += rt;
				wt += rt;
			}
		}

		
		
	}


	if (!is_external)
	{
		
		wt += '</style>';
		
	}


	return wt;

}






function qmv_genpub_css_rule(rule,id,tabs,rt,spec_tree_obj)
{
	


	var wt = "";

	
	if (!rt) tabs = "";
	
	var rules = qmv.style_rules;
	var css_rule = null;
	for (var i=0;i<rules.length;i++)
	{

		var st = rules[i].selectorText.toLowerCase();
		st = st.split(",")[0];
	
		if (st.toLowerCase()==rule)
			css_rule = rules[i];
		
	}

	if (!css_rule)
		return wt;

	var a;
	if (!spec_tree_obj)
		a = document.getElementById("qmvtree_css_styles").getElementsByTagName("DIV");
	else
		a = spec_tree_obj;

	for (var i=0;i<a.length;i++)
	{

		var ar = a[i].getAttribute("rule");
		if (ar) 
		{
			ar = ar.replace("[i]",id);

			if (ar==rule)
			{


				var aa = a[i].childNodes;
				for (var j=0;j<aa.length;j++)
				{
					if (aa[j].tagName=="A")
					{
					
						var inp = aa[j].getElementsByTagName("INPUT")[0];
						
						if (inp)
						{
							var cn = inp.getAttribute("cname");
							if (css_rule.style[cn])
							{
								if (qmad.br_ie)
									wt += tabs+inp.getAttribute("sname")+":"+css_rule.style[cn]+";"+rt;
								else
									wt += tabs+inp.getAttribute("sname")+":"+qmv_load_css_styles_firefox_fix(css_rule.style[cn],inp.getAttribute("dtype"))+";"+rt;

							}
						}
					
					}


				}


			}	


		}


	}

	return wt;

}


function qmv_pubgen_noscript_tag()
{

	var wt = "";
	wt += '<!-- QuickMenu Noscript Support [Keep in head for full validation!] -->\r\n';
	wt += '<noscript><style type="text/css">.qmmc {width:200px !important;height:200px !important;overflow:scroll;}.qmmc div {position:relative !important;visibility:visible !important;}.qmmc a {float:none !important;white-space:normal !important;}</style></noscript>';

	return wt;

}

function qmv_pubgen_javascript(is_external)
{

	var t = "\t";
	var rt = "\r\n";
	var wt = "";


	if (!is_external)
		wt += "<!-- Core QuickMenu Code -->";
	else
		wt += "//Core QuickMenu Code";

	wt += rt;

	var cc = qmv_get_source_code(true);
	if (!is_external)
		cc = '<scr'+'ipt type="text/javascript">/* <![CDATA[ */'+cc+'/* ]]> */</scr'+'ipt>'; 



	wt += cc;

	wt += rt;
	wt += rt;

	var ac = "";
	if (is_external)
	{
		
		ac += '//Add-On Core Code (Remove when not using any add-on\'s)'	
		ac += rt;
		ac += 'document.write(\''+qmv_pubget_get_core_addon_css()+'\');';
	}
	else
	{

		ac += '<!-- Add-On Core Code (Remove when not using any add-on\'s) -->'
		ac += rt;
		ac += qmv_pubget_get_core_addon_css();
	}

	var ad = "";
	qmv.t = new Object();	


	var go_special_add = false;

	for (var i=0;i<10;i++)
	{
		//add the special addons to the list
		if (qmv.addons.image["on"+i])
		{
			qmv.t.image = true;
			go_special_add = true;
		}

		if (qmv.addons.sopen["on"+i])
		{
			qmv.t.sopen = true;
			go_special_add = true;
		}

		
		
		
		if (!document.getElementById("qm"+i))
			continue;

		
		var st = "\t";
		if (!is_external) st = "\t\t";
		var ads = qmv_pubgen_javascript_addons(i,st);
		

		if (ads)
		{

			var y = "";
			if (!is_external)
			{
				y += '\t<!-- Add-On Settings -->';
				y += rt;
				y += '\t<scr'+'ipt type="text/JavaScript">';
				y += rt;
				y += rt;
			}

			
			y += st+'/*******  Menu '+i+' Add-On Settings *******/'
			y += rt;
			y += st+'var a = qmad.qm'+i+' = new Object();';
			y += rt;
			y += rt;
			ad += y+ads;

			if (!is_external)
			{
				ad += '\t</scr'+'ipt>';
				ad += rt;
				ad += rt;
			}
			else
				ad += rt;
		}

	}	


	if (ad || go_special_add) wt = wt + ac + rt + rt + rt+ ad;


	

	var j;
	for (j in qmv.addons)
	{
		if (qmv.t[j])
		{
		
			if (is_external)
			{
				wt += "//Add-On Code: "+qmv.addons[j].desc;
				wt += rt;
				wt += qmv.addons[j].code;
				wt += rt;
				wt += rt;
			}
			else
			{
				wt += "<!-- Add-On Code: "+qmv.addons[j].desc+" -->";
				wt += rt;
				wt += '<scr'+'ipt type="text/javascript">/* <![CDATA[ */'+qmv.addons[j].code+'/* ]]> */</scr'+'ipt>';
				wt += rt;
				wt += rt;
			}

		}
	}

	
	return wt;
}


function qmv_pubgen_javascript_addons(id,t)
{

	if (!t) t = "\t";

	var wt = "";
	var track = new Object();



	
	var q;
	if (q = qmad["qm"+id])
	{
	

		var a = document.getElementById("qmvtree_addon_settings").getElementsByTagName("DIV");
		for (var i=0;i<a.length;i++)
		{

			var tr = a[i].getAttribute("rule");
			if (!tr) continue;

			
			if (a[i].idiv)
			{
				var inp = a[i].idiv.getElementsByTagName("INPUT")[0];
				var atype = a[i].getAttribute("addontype");

				if (!atype) continue;
				

				if (qmv.addons[atype]["on"+id]==null && qmv.addons[atype]["on"+id]==undefined || qmv.addons[atype]["on"+id])
				{
					
					var ot = qmv.addons[atype].ontest.split("|");
					var ogo = false;
					for (var e=0;e<ot.length;e++)
					{	
					
						if (q[ot[e]])
							ogo = true;			
					}

					
					if (ogo)
					{

					
						if (track[atype+id])
							continue;

	
						wt += t+'// '+qmv.addons[atype].desc+" Add On";
						wt += '\r\n';

						track[atype+id] = true;	
						qmv.t[atype] = true;


						inp = a[i].getElementsByTagName("INPUT");
						for (var j=0;j<inp.length;j++)
						{
						
							var cname;
							if (cname = inp[j].getAttribute("cname"))
							{
							
								var val = qmad["qm"+id][cname];
								if (val!=null && val!=undefined)
								{

									var dtype = inp[j].getAttribute("dtype");
									if (dtype.indexOf("array")+1)
									{
										wt += t+'a.'+cname+" = new Array("+val+");";

									}
									else
									{
										var lval = (val+"").toLowerCase();
										if (lval=="true" || lval=="false")
											val = lval;
										else if (isNaN(parseInt(val)))
											val = '"'+val+'"';

										wt += t+'a.'+cname+" = "+val+";";
									}
				
									wt += "\r\n";

								}
							}

						}

	
						wt += '\r\n';


					}
					

				}

						
			}

		}
	}


	return wt;

	

}


function qmv_pubgen_structure(is_external,all,breaks,title_it,spec_id)
{


	var wt = "";
	qmv.wt = "";


	if (!all)
	{

		if (!spec_id)
		{
		
			id = qmv.publish.smenus[qmv.publish.smenus_pos];
		}
		else
			id = parseInt(spec_id);


		qmv_pubgen_structure_build(is_external,document.getElementById("qm"+id),id,"\t",true)
		
			
	}
	else
	{

		var hit = false;
		for (var i=0;i<10;i++)
		{
			var a;
			if (!(a = document.getElementById("qm"+i)))
				continue;

			if (hit)
				qmv.wt += breaks;


			qmv_pubgen_structure_build(is_external,a,i,"\t",true,title_it)


			hit = true;
		}
	}


	if (is_external)
	{
		qmv.wt = qmv.wt.replace(/\'/g,"\\'");
		wt += "document.write('"+qmv.wt+"');";

	}
	else
		wt += qmv.wt;



	return wt;

}


function qmv_pubgen_structure_build(is_external,a,id,tab,is_root,title_it)
{

	var rt = "\r\n";
	if (is_external)
	{
		rt = "";
		tab = "";
	}

	var istyles = "";
	if (a.style.cssText)
		istyles = qmv_pubgen_structure_build_inline_styles("inline-parent",a);


	var l1 = "";
	var l2 = "";
	var lsp1 = ""
	var d1 = "<div";
	var d2 = "div>";
	if (qmv.pure)
	{
		l1 = "<li>";
		l2 = "</li>";
		d1 = "<ul";
		d2 = "ul>";
		//lsp1 = '<li class="qmspanli">';
		lsp1 = '<li>';	
	}

	if (is_root)
	{	
		if (title_it)
		{
			qmv.wt += '<!-- QuickMenu Structure [Menu '+id+'] -->';
			qmv.wt += rt;
			qmv.wt += rt;
		}

		//var div_classnames = "";
		//div_classnames += qmv_pubgen_structure_build_classname("div",a,true);

		qmv.wt += d1+' id="qm'+id+'" class="qmmc"'+istyles+'>';
		qmv.wt += rt;
		qmv.wt += rt;
	}
	else
	{
		var div_classnames = "";
		div_classnames += qmv_pubgen_structure_build_classname("div",a);		

		var dsp = "";
		if (div_classnames) dsp=" ";

		qmv.wt += rt;
		qmv.wt += tab+d1+dsp+div_classnames+istyles+'>';
		qmv.wt += rt;

	}
	


	var ch = a.childNodes;
	for (var i=0;i<ch.length;i++)
	{
	
		if (ch[i].tagName=="A")
		{

			var href = ch[i].getAttribute("href",2);
			var targ = ch[i].getAttribute("target",2);
			var title = ch[i].getAttribute("title",2);

			if (!href) 
				href = 'href="JavaScript:void(0);"';
			else
				href = 'href="'+href+'"';
					
			if (!targ)
				targ="";
			else
				targ = ' target="'+targ+'"';

			if (!title)
				title="";
			else
				title = ' title="'+title+'"';

			var istyles = "";
			if (ch[i].style.cssText)
				istyles = qmv_pubgen_structure_build_inline_styles("inline",ch[i]);


			var atag_classnames = "";
			atag_classnames = qmv_pubgen_structure_build_classname("atag",ch[i]);

			
			

			qmv.wt += tab+l1+'<a '+atag_classnames+href+targ+title+istyles+'>'+qmv_set_texturl_field(ch[i],false,true)+'</a>';
			if (!ch[i].cdiv) qmv.wt+=l2;

			qmv.wt += rt;
		}
		else if (ch[i].tagName=="SPAN")
		{

			var span_classnames = "";
			span_classnames = qmv_pubgen_structure_build_classname("span",ch[i]);


			if (ch[i].className.indexOf("qmtitle")+1)
			{

				var istyles = "";
				if (ch[i].style.cssText)
					istyles = qmv_pubgen_structure_build_inline_styles("inline",ch[i]);

				qmv.wt += tab+lsp1+'<span '+span_classnames+istyles+'>'+ch[i].innerHTML+'</span>'+l2;
				qmv.wt += rt;

			}
			else if (ch[i].className.indexOf("qmdivider")+1)
			{
				var istyles = "";
				if (ch[i].style.cssText)
					istyles = qmv_pubgen_structure_build_inline_styles("inline",ch[i]);

									

				qmv.wt += tab+lsp1+'<span '+span_classnames+istyles+'>'+ch[i].innerHTML+'</span>'+l2;
				qmv.wt += rt;
			}

		}


		if (ch[i].tagName=="DIV" && ch[i].idiv)
			qmv_pubgen_structure_build(is_external,ch[i],id,tab+"\t",null,null)


	}

	if (is_root)
	{
		if (qmv.pure)
			qmv.wt += '<li class="qmclear"> </li></'+d2;
		else
			qmv.wt += '<span class="qmclear"> </span></'+d2;
	}
	else
		qmv.wt += tab+'</'+d2+l2;

	

	if (is_root)
	{
		qmv.wt += rt;
		qmv.wt += qmv_pubgen_structure_build_menu_settings(id,a,is_external);
	}
	else
	{
		qmv.wt += rt;
		qmv.wt += rt;

	}
}


function qmv_pubgen_structure_build_classname(type,a,is_root)
{
	var wt = "";
	var cn;
	
	if (cn = a.className)
	{
		
		var s1 = false;
		var sn = false;	

		if (type=="atag")
		{
					

			if (cn.indexOf("qm-startopen")+1)
			{
				s1 = true;
				wt += "qm-startopen";

			}

			if (qmv.pure && a.cdiv)
			{
				sn = true;
				wt += " qmparent";
			}

			if (cn.indexOf("qmstripe")+1)
			{
				sn = true;
				wt += " qmstripe";
			}

			var t;
			if (t = qmv_pubgen_structure_build_classname_customs(a))
			{
				sn = true;
				wt += t;
			}

			

			if (!s1 && sn)
				wt = wt.substring(1);

		}
		else if (type=="span")
		{

			if (cn.indexOf("qmtitle")+1)
			{
				s1 = true;
				wt += "qmtitle";
			}

			if (cn.indexOf("qmstripe")+1)
			{
				sn = true;
				wt += " qmstripe";
			}


			if (cn.indexOf("qmdivider")+1)
			{
				sn = true;

				if (cn.indexOf("qmdividerx")+1)
					wt += " qmdivider qmdividerx";
				else if (cn.indexOf("qmdividery")+1)
					wt += " qmdivider qmdividery";
				else
					wt += " qmdivider qmdividerx";

			}


			var t;
			if (t = qmv_pubgen_structure_build_classname_customs(a))
			{
				sn = true;
				wt += t;
			}


			if (!s1 && sn)
				wt = wt.substring(1);


		}
		else if (type=="div")
		{
			
			if ((cn.indexOf("qmmc")+1) && (is_root))
			{
				s1 = true;
				wt += "qmmc";
			}
			
			
			var t;
			if (t = qmv_pubgen_structure_build_classname_customs(a))
			{
				sn = true;
				wt += t;
			}


			if (!s1 && sn)
				wt = wt.substring(1);


		}

		
	}


	if (wt)
		wt = 'class="'+wt+'" ';

	return wt;

}


function qmv_pubgen_structure_build_classname_customs(a)
{

	var w = a.className;
	var p = 0;
	var rt = "";

	

	while ((p = w.indexOf("qmc_",p))+1)
	{
		
		var p2 = w.indexOf(" ",p);
		if (p2>p)	
		{
			rt += " "+w.substring(p,p2);
		}
		else
		{
			rt += " "+w.substring(p,w.length);
			break;
		}

		p = p2;

		
		
	}

	
	return rt;

}


function qmv_pubgen_structure_build_inline_styles(rule,obj)
{


	var wt = "";

	var a = document.getElementById("qmvtree_inline_styles").getElementsByTagName("DIV");
	for (var i=0;i<a.length;i++)
	{
		
		var tr = a[i].getAttribute("rule");
		if (tr==rule)
		{
			
			var aa = a[i].childNodes;
			for (var j=0;j<aa.length;j++)
			{

				if (aa[j].tagName=="A")
				{
				
					var inp = aa[j].getElementsByTagName("INPUT")[0];
					if (inp)
					{
						var cname = inp.getAttribute("cname");
						var sname = inp.getAttribute("sname");
						if (!cname) continue;

						var val = obj.style[cname];
						if (val)
						{
							if (!qmad.br_ie)
								val = qmv_load_css_styles_firefox_fix(val,inp.getAttribute("dtype"));

							wt += sname+":"+val+";";
				
						}
					}
				}

			}

			
		}
		
	}

	if (wt)
		return ' style="'+wt+'"';
	else
		return "";

}


function qmv_pubgen_structure_build_menu_settings(id,a,is_external)
{

	var wt = '';

	var isv = "false";
	if (qmv_lib_is_menu_vertical(id))
		isv = "true";


	var isc = "false";
	if (a.origclick || (qmv.addons.tree_menu["on"+id]))
		isc = "true";
	
	var rl;
	if (a.rl) rl = "true";else rl = "false";

	var sh;
	if (a.sh) sh = "true";else sh = "false";

	var fl;
	if (a.fl) fl = "true";else fl = "false";
	
	var sc = "";
	if (!is_external)
	{
		
		sc += '\r\n';	
		sc+='<!-- Create Menu Settings: (Menu ID, Is Vertical, Show Timer, Hide Timer, On Click, Right to Left, Horizontal Subs, Flush Left) -->'
		sc += '\r\n';
	}

	wt += 'qm_create('+id+','+isv+','+qmv.ms_show_timer+','+qmv.ms_hide_timer+','+isc+','+rl+','+sh+','+fl+');';
	wt = sc+'<scr'+'ipt type="text/javascript">'+wt+'</scr'+'ipt>';


	return wt;

}


function qmv_pubgen_get_number_word(num)
{

	if (num==1)
		return "First";
	else if (num==2)
		return "Second";	
	else if (num==3)
		return "Third";	
	else if (num==4)
		return "Fourth";	
	else if (num==5)
		return "Fith";	
	else if (num==6)
		return "Sixth";	
	else if (num==7)
		return "Seventh";	
	else if (num==8)
		return "Eighth";	
	else if (num==9)
		return "Ninth";	


	return num;

}

function qmv_pubgen_get_core_css()
{

	var rt = "";
	rt += '.qmmc .qmdivider{display:block;font-size:1px;border-width:0px;border-style:solid;}.qmmc .qmdividery{float:left;width:0px;}.qmmc .qmtitle{display:block;cursor:default;white-space:nowrap;}.qmclear {font-size:1px;height:0px;width:0px;clear:left;line-height:0px;display:block;float:none !important;}.qmmc {position:relative;zoom:1;}.qmmc a, .qmmc li {float:left;display:block;white-space:nowrap;}.qmmc div a, .qmmc ul a, .qmmc ul li {float:none;}.qmsh div a {float:left;}.qmmc div{visibility:hidden;position:absolute;}';

	if (qmv.pure)
	{
		rt += '.qmmc ul {left:-10000px;position:absolute;}.qmmc, .qmmc ul {list-style:none;padding:0px;margin:0px;}.qmmc li a {float:none}.qmmc li{position:relative;}.qmmc ul {z-index:10;}.qmmc ul ul {z-index:20;}.qmmc ul ul ul {z-index:30;}.qmmc ul ul ul ul {z-index:40;}.qmmc ul ul ul ul ul {z-index:50;}li:hover>ul{left:auto;}';


		for (var i=0;i<10;i++)
		{
			var a;
			if (a = document.getElementById("qm"+i))
			{

				if (!a.ch || qmv.addons.tree_menu["on"+i])
				{
					rt += '#qm'+i+' li {float:none;}#qm'+i+' li:hover>ul{top:0px;left:100%;}';

					if (qmv.addons.tree_menu["on"+i])
					{
						var w = qmv_find_update_tree_value("addon","tree_menu","tree_width","",true);
						if (w) rt += '#qm'+i+' {width:'+w+'px;}';

					}						

				}
				else
					rt += '#qm'+i+' ul {top:100%;}#qm'+i+' ul li:hover>ul{top:0px;left:100%;}';
			}
		}

	}


	
	

	return rt;

}


function qmv_pubget_get_core_addon_css()
{


	return '<style type="text/css">.qmfv{visibility:visible !important;}.qmfh{visibility:hidden !important;}</style><script type="text/JavaScript">var qmad = new Object();qmad.bvis="";qmad.bhide="";qmad.bhover="";</script>';

}

function qmv_publish_focus_input(event,a)
{



	if (qmad.br_ie)
	{
		trange = a.createTextRange();
		trange.select();
	}
	else
	{
		a.setSelectionRange(0,a.value.length);
		
	}

}

function qmv_publish_blur_input(event,a)
{
	if (!qmad.br_ie)
		a.setSelectionRange(0,0);

}


function qmv_pubgen_all_external(force_external)
{

	
	var wt = "";

	
	wt += qmv_pubgen_css(false,true);
	wt = wt.replace(/\'/g,"\\'");
	wt = '//Compressed CSS Styles\r\n'+"document.write('"+wt+"');";

	wt += "\r\n";
	wt += "\r\n";

	wt += qmv_pubgen_javascript(true);

	wt += "//Compressed Menu Structure\r\n";
	wt += qmv_pubgen_structure(force_external);


	return wt;

}


function qmv_savegen()
{

	var wt = "";
	var rt = "\r\n";


	wt += '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	wt += rt;
	wt += '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">';
	wt += '<head><title>QuickMenu Save Document</title><meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>';
	wt += rt;
	wt += rt;
	wt += '<!-- *** QuickMenu copyright (c) 2007, OpenCube Inc. All Rights Reserved.';
	wt += rt;
	wt += rt;
	wt += '\t-QuickMenu may be manually customized by editing this document, or open this web page using'
	wt += rt;
	wt += '\t IE or Firefox to access the visual interface.'
	wt += rt;
	wt += rt;
	wt += '-->';	


	wt += rt;
	wt += rt;
	wt += rt;


	wt += qmv_pubgen_css(false);
	wt += qmv_pubgen_javascript(false);


	wt += '</head>'
	wt += rt;
	wt += rt;
	wt += '<body style="margin:40px"><noscript><span style="font-size:13px;font-family:arial;"><span style="color:#dd3300">Warning!</span>&nbsp  QuickMenu may have been blocked by IE-SP2\'s active content option. This browser feature blocks JavaScript from running locally on your computer.<br><br>This warning will not display once the menu is on-line.  To enable the menu locally, click the yellow bar above, and select <span style="color:#0033dd;">"Allow Blocked Content"</span>.<br><br>To permanently enable active content locally...<div style="padding:0px 0px 30px 10px;color:#0033dd;"><br>1: Select \'Tools\' --> \'Internet Options\' from the IE menu.<br>2: Click the \'Advanced\' tab.<br>3: Check the 2nd option under \'Security\' in the tree (Allow active content to run in files on my computer.)</div></span></noscript>';
	wt += rt;
	wt += rt;
	wt += '<!--Open Visual Interface Button-->'+rt;
	wt += '<a style="font-size:13px;color:#444444;font-family:arial;text-decoration:none;display:block;margin-bottom:25px;" id="qmv_open_visual_interface" href="javascript:var qmnw = window.open(window.location.href,\'qm_launch_visual\',\'scrollbars=no,location=no,status=yes,menu=no,toolbar=no,resizable=yes\');if (window.focus) {qmnw.focus()}"><span style="color:#dd3300;">[+]</span> Open Visual Interface</a>'+rt;
	wt += rt;


	if (qmv.free_use)
	{
		wt += rt;
		wt += rt;	
		wt += "<!-- This optional free use link disables the online purchase reminder.  Include within the body of your page -->";
		wt += rt;
		wt += qmv_pubgen_free_use_link();		
		wt += rt;
		wt += '<br><br><br>'
		wt += rt;
	}
	

	wt += rt;
	wt += rt;
	wt += rt;
	wt += qmv_pubgen_structure(false,true,rt+rt+rt+rt+"<br><br><br><br><br><br><br>"+rt+rt+rt+rt,true);
	wt += rt;
	wt += rt;
	wt += rt;
	wt += '<!-- This script references optionally loads the QuickMenu visual interface, to run the menu stand alone remove the script.-->'
	wt += rt;	
	//wt += '<script type="text/javascript" src="http://www.opencube.com/qmv4/qm_visual.js"></script>'
	wt += '<script type="text/javascript">if (window.name=="qm_launch_visual"){document.write(\'<scr\'+\'ipt type="text/javascript" src="http://www.opencube.com/qmv4/qm_visual.js"></scr\'+\'ipt>\')}</script>'
	wt += rt;
	wt += '</body>';
	wt += rt;
	wt += '</html>';

	return wt;


}


function qmv_options_init()
{


	var tc = document.getElementById("qmvi_dg_options_auto_collapse");
	if (!qmv.tree_collapse)
		tc.checked = false;
	else
		tc.checked = true;

	var tc = document.getElementById("qmvi_dg_options_hide_selected_box");
	if (!qmv.interface_hide_selected_box)
		tc.checked = false;
	else
		tc.checked = true;


	/*
	tc = document.getElementById("qmvi_dg_options_free_use");
	if (!qmv.free_use)
		tc.checked = false;
	else
		tc.checked = true;
	*/


	var tc = document.getElementById("qmvi_df_options_unlock");
	if (qmv.unlock_orig)
		tc.value = qmv.unlock_orig;
	




}

function qmv_input_code_ask_width(inp,width)
{

	
	if (inp.value.toLowerCase()=="true")
	{
		
		if (!width)
		{
			var def = qmv_find_update_tree_value("css","#qm[i]","width",null,true);
			if (def=="") def = "200px";

			qmv_show_dialog("question-okcancel-input",inp,"Please define a width for the vertical main menu. Without a width defined the main menu will stretch to 100% of its containers size. This value is further cusomizable under CSS Styles --> Main --> Container.<br><br>Main Container Width",300,"qmv_input_code_ask_width(qmd.owner,true)",null,null,def);	

		}
		else
		{
			var val = document.getElementById("qmvi_ok_input").value;
			if (val)
			{		
				var update_inp = qmv_find_update_tree_value("css","#qm[i]","width",val,false,true);
				if (update_inp)
					qmv_evt_update_tree_value(update_inp);
			}
		}

	}
	else
	{
		if (!width)
		{
			var def = qmv_find_update_tree_value("css","#qm[i]","width",null,true);
			if (def)
			{
				qmv_show_dialog("question-yesno",inp,"A width is defined for the main container which may cause the items to wrap when aligned horizontally. This setting is accessible under CSS Styles --> Main --> Container.<br><br>Would you like to remove the width now.",450,"qmv_input_code_ask_width(qmd.owner,true)");	
			}

		}
		else
		{
			var update_inp = qmv_find_update_tree_value("css","#qm[i]","width","",false,true);
			if (update_inp)
				qmv_evt_update_tree_value(update_inp);	
		
		}

	}
		

}

function qmv_image_init(qmd)
{
	
	document.getElementById("qmvi_dg_image").value = qmd.owner.value;



}


function qmvi_options_free_use_info()
{

	qmv_show_dialog("alert",null,"QuickMenu and all its features may be use without limitation and completely free of charge by including a link anywhere in the body of your web page to OpenCube.<br><br>The link to add is provided as one of the steps in the publish wizard if this option is checked.<br><br>You must also use the quickmenu code produced by the publish wizard for the link to activate the menus free use mode, the menu code will verify that the link is in the page.",600);


}


function qmv_pubgen_free_use_link()
{


	return "<a id='qm_free' href='http://www.opencube.com'>OpenCube Drop Down Menu (www.opencube.com)</a>";


}

function qmv_evt_apply_plus(e,a)
{



	var cname = a.getAttribute("cname");

	if (cname=="insert_title")
	{
		qmv_insert_spanitem("title");
		
	}
	if (cname=="insert_title_after")
	{
		qmv_insert_spanitem("title",null,null,true);
		
	}
	else if (cname=="insert_divider")
	{
		
		qmv_insert_spanitem("divider");
		

	}
	else if (cname=="insert_divider_after")
	{


		qmv_insert_spanitem("divider",null,null,true);
		

	}
	else if (cname=="insert_divider_global")
	{


		qmv_show_dialog("applydividers");
		

	}
	else if (cname=="apply_striping_individually")
	{
		
		qm_arc("qmstripe",qmv.cur_item,true);
		

	}
	else if (cname=="remove_striping_individually")
	{


		qm_arc("qmstripe",qmv.cur_item);
		

	}
	else if (cname=="apply_striping_globally")
	{


		qmv_show_dialog("applystripes");
		

	}
	else if (cname=="ritem_mitem_styles")
	{

		qmv_ritem_underlying_styles(null,"main");

	}
	else if (cname=="ritem_sitem_styles")
	{

		qmv_ritem_underlying_styles(null,"sub");

	}
	else if (cname=="ritem_remove_mborders")
	{

		qmv_ritem_remove_main_styles("main");

	}
	else if (cname=="ritem_remove_sborders")
	{

		qmv_ritem_remove_main_styles("sub");

	}
	else if (cname=="ritem_individual")
	{


		if (!qmv.addons.ritem["on"+qmv.id])
			qmv_context_cmd(e,"addon_ritem");
		
		qm_arc("qmrounditem",qmv.cur_item,true);
		qmv_update_all_addons();

	}
	else if (cname=="ritem_individual_remove")
	{
		qm_arc("qmrounditem",qmv.cur_item);
		qmv_update_all_addons();

		qmv_ritem_remove_warning();
		

	}
	else if (cname=="rcorner_blend")
	{
		if (!qmv.addons.round_corners["on"+qmv.id])
			qmv_show_dialog("alert",null,"Rounded corners must first be applied to your menu before the colors can be blended.",400);		
		else
			qmv_updatehandle_round_corner_show(null,null,null,null,null,true);
	}
	else if (cname=="custom_rule")
	{


		qmv_add_custom_css_rule()



	}	
	else if (cname=="pstate_add")
	{

		if (qmv.cur_item.tagName!="A")
		{

			qmv_show_dialog("alert", null, "Persistent states may be applied to menu links only, titles and dividers may not be set persistent.<br><br>First click on a menu item which acts as a link, then apply the test persistent state.",375);

		}
		else
		{
			qm_arc("qmpersistent",qmv.cur_item,true);

			var cb = document.getElementById("qmv_iadd_sopen_auto");
			if (!cb.checked)
			{
				cb.checked = true;
				qmv_evt_addremove_addon(new Object(),cb);
			}

		}

	}
	else if (cname=="pstate_remove")
	{

		qm_arc("qmpersistent",qmv.cur_item);

	}
	else if (cname=="pstate_remove_all")
	{

		var at = document.getElementById("qm"+qmv.id).getElementsByTagName("A");
		for (var i=0;i<at.length;i++)
			qm_arc("qmpersistent",at[i]);

	}
	else if (cname=="pstate_help")
	{

		qmv_show_dialog("help-persistent-state",null,"help-persistent_state.html");
	}
	else if (cname=="pstate_main_styles")
	{

		var a = qmv_find_rule_atag("body #qm[i] .qmpersistent")
		qmv_display_setbox(a,null,null,true,2);

		
	}
	else if (cname=="pstate_sub_styles")
	{

		var a = qmv_find_rule_atag("body #qm[i] div .qmpersistent")
		qmv_display_setbox(a,null,null,true,2);
		
	}

	
}


function qmv_evt_apply_skin(e,a)
{

	var cname = a.getAttribute("cname");	
	var rule = "";

	var da = a;
	while (da.tagName!="DIV")
		da = da[qp];

	rule = da.getAttribute("rule");

	

	if (rule=="color")
	{


		qmv.skins.color_rules = new Object();
		qmv.skins.color = new Object();

		qmv.skins.color_rules.light_grays = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.light_grays = new Array("#eeeeee","#999999","#cccccc","#cccccc","#cccccc","#999999","#111111","#111111","","","#eeeeee","#111111","#111111");

		qmv.skins.color_rules.medium_grays = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.medium_grays = new Array("#cccccc","#666666","#999999","#999999","#999999","#666666","#111111","#111111","","","#cccccc","#111111","#111111");

		qmv.skins.color_rules.dark_grays = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.dark_grays = new Array("#aaaaaa","#333333","#666666","#666666","#666666","#333333","#111111","#111111","","","#aaaaaa","#111111","#111111");

		qmv.skins.color_rules.black_white = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.black_white = new Array("#000000","#ffffff","#000000","#000000","#000000","#ffffff","#ffffff","#ffffff","","","#000000","#ffffff","#ffffff");

		qmv.skins.color_rules.red_brick = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.red_brick = new Array("#dd3300","#990000","#dd3300","#990000","#990000","#990000","#ffffff","#ffffff","","","#dd3300","#ffffff","#ffffff");

		qmv.skins.color_rules.blue_tones = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.blue_tones = new Array("#c3d1ff","#306fbc","#306fbc","#c3d1ff","#c3d1ff","#306fbc","#000000","#000000","","","#c3d1ff","#000000","#ffffff");

		qmv.skins.color_rules.blue_yellow = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.blue_yellow = new Array("#fbecc1","#306fbc","#306fbc","#fbecc1","#fbecc1","#306fbc","#000000","#000000","","","#fbecc1","#000000","#ffffff");

		qmv.skins.color_rules.blue_green = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.blue_green = new Array("#bce0c4","#306fbc","#306fbc","#bce0c4","#bce0c4","#306fbc","#000000","#000000","","","#bce0c4","#000000","#ffffff");

		qmv.skins.color_rules.forest_green = new Array("css|#qm[i] a|backgroundColor","css|#qm[i] a|borderColor","css|body #qm[i] .qmactive|backgroundColor","css|#qm[i] div|backgroundColor","css|#qm[i] div a|backgroundColor","css|#qm[i] div|borderColor","css|#qm[i] a|color","css|#qm[i] div a|color","css|#qm[i] a:hover|backgroundColor","css|#qm[i] div a:hover|backgroundColor","css|body #qm[i] div .qmactive|backgroundColor","css|body #qm[i] div .qmactive|color","css|body #qm[i] .qmactive|color");
		qmv.skins.color.forest_green = new Array("#4c7d49","#81b735","#81b735","#4c7d49","#4c7d49","#81b735","#ffffff","#ffffff","","","#81b735","#2d4c2f","#2d4c2f");



	}
	else if (rule=="spacing")
	{
		qmv.skins.spacing_rules = new Object();
		qmv.skins.spacing = new Object();

		qmv.skins.spacing_rules.h4_main_gaps = new Array("css|#qm[i] a|margin");
		qmv.skins.spacing.h4_main_gaps = new Array("0px 4px 0px 0px");

		qmv.skins.spacing_rules.h0_main_gaps = new Array("css|#qm[i] a|margin");
		qmv.skins.spacing.h0_main_gaps = new Array("0px");
		
		qmv.skins.spacing_rules.v4_main_gaps = new Array("css|#qm[i] a|margin");
		qmv.skins.spacing.v4_main_gaps = new Array("0px 0x 4px 0px");
		
		qmv.skins.spacing_rules.v0_main_gaps = new Array("css|#qm[i] a|margin");
		qmv.skins.spacing.v0_main_gaps = new Array("0px");

		qmv.skins.spacing_rules.small_sub_pad = new Array("css|#qm[i] div|padding");
		qmv.skins.spacing.small_sub_pad = new Array("2px");

		qmv.skins.spacing_rules.medium_sub_pad = new Array("css|#qm[i] div|padding");
		qmv.skins.spacing.medium_sub_pad = new Array("5px");

		qmv.skins.spacing_rules.large_sub_pad = new Array("css|#qm[i] div|padding");
		qmv.skins.spacing.large_sub_pad = new Array("10px");

	}



	var sr = qmv.skins[rule+"_rules"][cname];
	var sv = qmv.skins[rule][cname];
	
	var si = true;
	if (cname.indexOf("main_gaps")+1) si = false;

	
	
	for (var i=0;i<sr.length;i++)
	{

		var sp = sr[i].split("|");
		if (sp.length>2 && sv[i]!=null)
		{
			var inp = qmv_find_update_tree_value(sp[0],sp[1],sp[2],sv[i],false,true);

			var su = true;
			if (i==sr.length-1) su = true;
						
			qmv_evt_update_tree_value(inp,null,true,su,si,false,true);
		}
		

	}

	if (document.getElementById("qmvtree_filter").cdiv.style.display=="block")
		qmv_filter_init2();
	

	if (qmv.addons.round_corners["on"+qmv.id])
	{
		qmv.questionasked_rcorner_size = false;
		qmv_updatehandle_round_corner_show();
	}



	if (document.getElementById("qmvi_msg_dialog").style.visibility!="visible")
	{
		if (!qmv.msg_skin_settings_shown)
		{
			qmv_show_dialog("alert",null,"Your skin settings have been applied.<br><br>All skin settings are individually available in the CSS Styles and Add-On sections of the main interface.",460);	
			qmv.msg_skin_settings_shown = true;
		}
	}


}



function qmv_shortcut_init(a)
{

	
	
	a.innerHTML = "";
	var tcolor = new Array();


	tbuild = new Array();
	for (var i=0;i<qmv.color_recent.length;i++)
	{

		var con = false;
		for (var j=0;j<tbuild.length;j++)
		{
			if (tbuild[j].inp.value.toLowerCase()==qmv.color_recent[i].value)
			{
				tbuild[j].count++;
				con = true;
			}

		}
		
		if (con) continue;

		var tb = new Object()
		tb.inp = qmv.color_recent[i].inp;
		tb.count = 1;
		tbuild.push(tb);
	}


	

	for (var i=0;i<tbuild.length;i++)
	{


				
		var ipp = tbuild[i].inp[qp];
		while (ipp.tagName!="A")
			ipp = ipp[qp];

		var ni = ipp.cloneNode(true);

		qm_arc("qmvtreelasta",ni);
		qm_arc("qmvtreefirsta",ni);

		if (i==tbuild.length-1)		
			qm_arc("qmvtreelasta",ni,true);

		if (i==0)
			qm_arc("qmvtreefirsta",ni,true);


		var tds = ni.getElementsByTagName("TD");
		for (var j=0;j<tds.length;j++)
		{
			if (tds[j].getAttribute("filtercol1"))
			{
				
				tds[j].style.width = "120px";
				tds[j].innerHTML = "Color Styles:&nbsp;"+"(<span style='color:#dd3300;'>"+tbuild[i].count+"</span>) ";
				
			}
				
		}


		var inp_orig = tbuild[i].inp;

		var inp = ni.getElementsByTagName("INPUT")[0];
		inp.origrule = inp_orig.origrule;
		inp.rule = inp_orig.rule;
		inp.iseditcolor = 1;
		inp.prev_value = inp_orig.prev_value;
				

		a.appendChild(ni);
		

	}

	
	
}



function qmv_filter_init()
{

	if (!qmv.filter)
	{

		qmv.filter = new Object();
		qmv.filter.settings = new Object();
		

		qmv.filter.settings.value = "edit";
		qmv.filter.settings.group = "main";
		qmv.filter.settings.section = "item";
		qmv.filter.settings.style = "color";

		document.getElementById("qmvf_value1").checked = true;
		document.getElementById("qmvf_group0").checked = true;
		document.getElementById("qmvf_section1").checked = true;
		document.getElementById("qmvf_style0").checked = true;


	}

	
	if (document.getElementById("qmvtree_filter").cdiv.style.display!="block")
		qmv_filter_init2();
		
		
}

function qmv_filter_init2()
{

	qmv_filter_init_results_object();
	qmv_filter_get_results();
	qmv_filter_build_results();

}


function qmv_filter_change()
{

	qmv.filter.settings.value = "";
	if (document.getElementById("qmvf_value0").checked)
		qmv.filter.settings.value += "add";
	if (document.getElementById("qmvf_value1").checked)
		qmv.filter.settings.value += "edit";


	if (!qmv.filter.settings.value)
		qmv_show_dialog("alert",null,"You must check <span style='color:#0033aa'>add</span> or <span style='color:#0033aa'>edit</span> for the filter to display results.",400);		


	if (document.getElementById("qmvf_group0").checked)
		qmv.filter.settings.group = "main";
	else
		qmv.filter.settings.group = "sub";


	if (document.getElementById("qmvf_section0").checked)
		qmv.filter.settings.section = "container";
	else
		qmv.filter.settings.section = "item";


	if (document.getElementById("qmvf_style0").checked)
		qmv.filter.settings.style = "color";
	else if (document.getElementById("qmvf_style1").checked)
		qmv.filter.settings.style = "font";
	else if (document.getElementById("qmvf_style2").checked)
		qmv.filter.settings.style = "border";
	else if (document.getElementById("qmvf_style3").checked)
		qmv.filter.settings.style = "other";


	qmv_filter_init_results_object();

	qmv_filter_get_results();
	qmv_filter_build_results();

}

function qmv_filter_init_results_object()
{

	qmv.filter.results = new Array();
	qmv.filter.results_title = new Array();

}

function qmv_filter_get_results(obj,title)
{

	var fr = qmv.filter.results;
	var fr_title = qmv.filter.results_title;
	var fs = qmv.filter.settings;

	if (!obj)
	{
		obj = document.getElementById("qmvtree_css_styles");
	}

	var isfont = obj.getAttribute("isfont");


	var a = obj.childNodes;
	for (var i=0;i<a.length;i++)
	{
		



		if (a[i].tagName=="A")
		{
			var inp = a[i].getElementsByTagName("INPUT")[0];
			if (inp)
			{

				if ((fs.value.indexOf("edit")+1 && inp.value) || (fs.value.indexOf("add")+1 && !inp.value))
				{

					var cname=inp.getAttribute("cname");
					var dtype=inp.getAttribute("dtype");

					var go = false;

					if (fs.style=="font" && isfont)
						go = true;

					if ((fs.style=="color" && dtype=="color") || (fs.style=="border" && cname.indexOf("border")+1))
						go = true;

					if (fs.style=="other" && !go && !isfont && dtype!="color" && cname.indexOf("border")==-1)
						go = true;

					if (go)
					{
						var t = false;
						if (title)
							t = title;					

						fr_title.push(t);
						fr.push(a[i]);
					}

				}
			}
			
			var b;
			if (b = a[i].cdiv)
			{
				var group;
				if ((group = b.getAttribute("group")) && group!=fs.group)
					continue;

				var section;
				if ((section = b.getAttribute("section")) && section!=fs.section)
					continue;
				
				var t
				if (!(t = b.getAttribute("ftitle")))
					t = false;

				qmv_filter_get_results(a[i].cdiv,t)

			}

		}


	}


}


function qmv_filter_build_results()
{
	var ta = "";

	var fr = qmv.filter.results;
	var fr_title = qmv.filter.results_title;


	var a = document.getElementById("qmvtree_filter_results");
	document.getElementById("qmvtree_filter_results_qty").innerHTML = "(<span style='color:#dd3300'>"+fr.length+"</span>)";

	
	if (!fr.length)
	{
		a.innerHTML = '<a href="#">This filter has zero results.</a>';
		return;
	}

	a.innerHTML = "";
	for (var i=0;i<fr.length;i++)
	{
		
				
		var ni = fr[i].cloneNode(true);
		qm_arc("qmvtreelasta",ni);
		qm_arc("qmvtreefirsta",ni);

		var tds = ni.getElementsByTagName("TD");
		for (var j=0;j<tds.length;j++)
		{
			if (tds[j].getAttribute("filtercol1"))
			{	
				tds[j].style.width = "120px";
	
				if (fr_title[i] && ta.indexOf(fr_title[i])==-1)
				{
					
					var st = document.createElement("SPAN");
					st.style.display = "block";
					st.style.className = "qmvi-common";
					st.style.color = "#555555";

					if (!ta)
						st.style.marginTop = "4px";
					else
						st.style.marginTop = "8px";

					st.style.marginBottom = "4px";
					st.innerHTML = "["+fr_title[i]+"]";
					a.appendChild(st);
				
					ta = fr_title[i];
					
				}
			}


		}


		var inp_orig = fr[i].getElementsByTagName("INPUT")[0];
		var inp = ni.getElementsByTagName("INPUT")[0];

		inp.origrule = inp_orig.origrule;
		inp.rule = inp_orig.rule;
		inp.isfilter = 1;

		a.appendChild(ni);
	
	}


	var hp = document.createElement("SPAN");
	hp.style.display = "block";
	hp.style.fontSize = "1px";
	hp.style.height = "5px";
	a.appendChild(hp);	


}


function qmv_info_item_extra_hover_active_help()
{

	qmv_show_dialog("alert",null,"To use hover and active effects create a custom image for each.  Append '_hover' or '_active' to the file name and save them to the same folder as the static image.  If your static image is '<span style='color:#dd3300'>main1.gif</span> the hover and actives would be...<br><br><span style='color:#dd3300'>main1_hover.gif<br>main1_active.gif</span><br><br>Hovers and actives are optional and are activated by setting the value of this field to true.",600);

}




var qmc_si, qmc_li, qmc_lo, qmc_tt, qmc_th, qmc_ts;
var qp = "parentNode";
var qc = "className";

var qmc_t = navigator.userAgent;
var qmc_o = qmc_t.indexOf("Opera")+1;
var qmc_s = qmc_t.indexOf("afari")+1;
var qmc_s2 = qmc_s && window.XMLHttpRequest;
var qmc_n = qmc_t.indexOf("Netscape")+1;
var qmc_v = parseFloat(navigator.vendorSub);





function qmc_create(sd,v,ts,th,oc,rl,sh,fl,nf,l)
{

	var w = "onmouseover";
	if (oc) 
	{
		w = "onclick";
		th = 0;
		ts = 0;
	}

	if (!l)
	{
		
		l=1;
		qmc_th = th;
		

		sd = document.getElementById("qm"+sd);
		sd[w]=function(e){qmc_kille(e);};
		
		
		sd.style.zoom = 1;
		if (sh) qmc_arc("qmsh",sd,true);	
		if (!v) sd.ch = 1;
		
				
	}
	else if (sh)
		sd.ch = 1;
		
	
	
	if (sh) sd.sh = 1;
	if (fl) sd.fl = 1;
	if (rl) sd.rl = 1;
	
	sd.style.zIndex = l+""+1;

	var lsp;
	var sp = sd.childNodes;
	
	for (var i=0;i<sp.length;i++)
	{
		
		var b=sp[i];	
		if (b.tagName=="A")
		{
			
			lsp = b;	
			b[w] = qmc_oo;
			b.qmts = ts;
			
			if (l==1 && v)
			{
				
				b.style.styleFloat = "none";
				b.style.cssFloat = "none";
				
			}
			
		}	
		
		if (b.tagName=="DIV")
		{
			if (window.showHelp && !window.XMLHttpRequest)
				sp[i].insertAdjacentHTML("afterBegin","<span class='qmclear'> </span>");
						
			
			qmc_arc("qmparent",lsp,true);
			lsp.cdiv = b;
			b.idiv = lsp;

			if (qmc_n && qmc_v<8 && !b.style.width)
				b.style.width = b.offsetWidth+"px";

			
						
			new qmc_create(b,null,ts,th,oc,rl,sh,fl,nf,l+1);	

		}
			
	}


}




function qmc_bo(e)
{

	
	
	clearTimeout(qmc_tt);
	qmc_tt = null;
	
	if (qmc_li && !qmc_tt)
		qmc_tt = setTimeout("qmc_2bo()",qmc_th);	
	

}

function qmc_2bo()
{
	
	var a;
	if ((a = qmc_li))	
	{
		
		do
		{
			
			qmc_uo(a);
			
			
		}while ((a = a[qp]) && !qmc_a(a))

	}

	qmc_li = null;
	

}

function qmc_a(a)
{
	
	if (a[qc].indexOf("qmmc")+1)
		return 1;

}

function qmc_uo(a,go)
{
	
	
	if (!go && a.qmtree) return;

	if (window.qmad && qmad.bhide) eval(qmad.bhide);
	a.style.visibility = "";
	qmc_arc("qmactive",a.idiv);
	

	
	

}




function qmc_oo(e,o,nt)
{

	if (!qmv.context_clicked) return;

	if (!o) o=this;


	o.blur();
	
	if (window.qmad && qmad.bhover && !nt) eval(qmad.bhover);
	
	if (window.qmwait) {qmc_kille(e); return;}

	clearTimeout(qmc_tt);
	qmc_tt = null;
	
	
	if (!nt && o.qmts)
	{
		
		qmc_si = o;
		qmc_tt = setTimeout("qmc_oo(new Object(),qmc_si,true)",o.qmts);
		return;
	}

	
	
	var a = o;
	if (a[qp].isrun) {qmc_kille(e); return;}
	

	var go = true;
	while ((a = a[qp]) && !qmc_a(a))
	{
		if (a==qmc_li)
			go = false;
	}
		

	if (qmc_li && go)
	{
		a = o;
		if ((!a.cdiv) || (a.cdiv && a.cdiv!=qmc_li))
			qmc_uo(qmc_li);
		

		a = qmc_li;
		while ((a = a[qp]) && !qmc_a(a))
		{
			
			if (a!=o[qp])
				qmc_uo(a);
			else
				break;
					
		}
	}

		
	var b = o;
	var c = o.cdiv;
	if (b.cdiv)
	{
	
		var aw = b.offsetWidth;
		var ah = b.offsetHeight;
		var ax = b.offsetLeft;
		var ay = b.offsetTop;

	
				
		if (c[qp].ch)
		{
			aw = 0;
			if (c.fl) ax =0;
		}
		else
		{
			if (c.rl)
			{
				ax = ax-c.offsetWidth;
				aw=0;
			}

			ah=0;
		}		

	
		if (qmc_o)
		{
			
			ax-=b[qp].clientLeft;
			ay-=b[qp].clientTop;
		}

		if (qmc_s2)
		{
			ax-=qmc_gcs(b[qp],"border-left-width","borderLeftWidth");
			ay-=qmc_gcs(b[qp],"border-top-width","borderTopWidth");
		}	

	
		if (!c.ismove)
		{
			c.style.left = (ax+aw)+"px";
			c.style.top = (ay+ah)+"px";
		}
		
		
		qmc_arc("qmactive",o,true);
		
		qmv_apsubs(c);
		if (window.qmad && qmad.bvis) eval(qmad.bvis);


		

		
		c.style.visibility ="inherit";
		qmc_li = c;

	}
	else if (!qmc_a(b[qp]))
		qmc_li = b[qp];
	else
		qmc_li = null;
		
	
	qmc_kille(e);

	
}


function qmc_gcs(obj,sname,jname)
{
	var v;
	if (document.defaultView && document.defaultView.getComputedStyle)
		v = document.defaultView.getComputedStyle(obj, null).getPropertyValue(sname);
	else if (obj.currentStyle)
		v = obj.currentStyle[jname];		

	if (v && !isNaN(v = parseInt(v)))
		return v;
	else
		return 0;
	

}



function qmc_arc(name,b,add)
{

	var a = b[qc];
	if (add)
	{
		if (a.indexOf(name)==-1)
			b[qc] += (a?' ':'') + name;	
		
	}
	else
	{
		
		b[qc] = a.replace(" "+name,"");
		b[qc] = b[qc].replace(name,"");
	
	}
}


	
function qmc_kille(e)
{

	if (!e) e = event;

	e.cancelBubble = true;

	
	if (e.stopPropagation && !(qmc_s && e.type=="click"))
		 e.stopPropagation();

}


function qmv_hide_context()
{

	

	var a = document.getElementById("qmvi_context");
	a.style.visibility = "hidden";

	qmc_2bo();

	qmv.context_clicked = false;

}

function qmv_show_context(e,type,item,src)
{

	
	e = e || event;

	if (item)
	{
		
		qm_oo(e,item);
	}
	
	var mc = document.getElementById("qm99");
	var a = document.getElementById("qmvi_context");
	var as = document.getElementById("qmvi_context_shadow");


	var ih = "";

	if (type=="menuitem")
	{
		
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'edit texturl\');">Edit Text / URL</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'item_image\');">Item Images</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'divider_styles\');">Divider Styles</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'insert_divider\');">Insert Divider</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'global_dividers\');">Apply Globally</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'title_styles\');">Title Styles</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'insert_title\');">Insert Title</a>';


		if (qmv.addons.ritem["on"+qmv.id])
		{
			ih += qmv_show_context_build_divider();
			ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'addon_ritem\');">Rounded Item Styles</a>';
			
			if (item && !item.hasritem)
				ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'ritem_apply\');">Apply Rounding</a>';
			else
				ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'ritem_remove\');">Remove Rounding</a>';
		}




		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'create_rule\')">Create Rule</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'apply_custom_class\')">Custom Classes</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'add item\')">Add Item</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'insert item\')">Insert Item</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'delete item\')">Delete Item</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'add sub menu\')">Add Sub Menu</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'copy item\')">Copy Item</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'paste item\')">Paste Item</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move up\')">Move Item Up</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move down\')">Move Item Down</a>';
	}
	else if (type=="document")
	{

		
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'add menu\');">Add Menu</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'delete menu\')">Delete Menu</a>';
	}
	else if (type=="divider")
	{

		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'divider_styles\');">Divider Styles</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'delete item\')">Delete Divider</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move up\')">Move Divider Up</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move down\')">Move Divider Down</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'copy item\')">Copy Divider</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'paste item\')">Paste Divider</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'apply_custom_class\')">Custom Classes</a>';

	}
	else if (type=="title")
	{

		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'title_styles\');">Title Styles</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'delete item\')">Delete Title</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move up\')">Move Title Up</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'move down\')">Move Title Down</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'copy item\')">Copy Item</a>';
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'paste item\')">Paste Item</a>';
		ih += qmv_show_context_build_divider();
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'apply_custom_class\')">Custom Classes</a>';
	}
	else if (type=="bullet_css")
	{

		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'addon_ibcss\');">Bullet Styles</a>';
		
	}
	else if (type=="bullet")
	{

		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'addon_item_bullets\');">Bullet Styles</a>';
		
	}
	else if (type=="build_button")
	{

		qmv.context_build = src;

		var fa = src.parentNode;
		while (fa.tagName!="A" && fa.tagName!="TBODY")
			fa = fa.parentNode;

		var inp = fa.getElementsByTagName("INPUT")[0];



		if (src.style.backgroundColor)
		{
			
			qmv.context_input = inp;

			ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'set_transparent\');">Set Transparent</a>';
			ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'build_click\');">Choose Color</a>';
		}
		else
		{

			ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'build_click\');">Build Value</a>';

		}
	}
	else if (type=="tree_parent")
	{

		qmv.tree_parent = src;
		ih += '<a href="javascript:void(0)" onclick="qmv_context_cmd(event,\'show_properties_box\');">Show Properties Box</a>';


	}
	

	ih += '<span class="qmclear"> </span>'
	mc.innerHTML = ih;


	qmc_create(99,true,0,500,false,false,false,false);

	as.style.width = a.offsetWidth+"px";
	as.style.height = a.offsetHeight+"px";
	as.style.left = "3px";
	as.style.top = "3px";	


	a.style.left = e.clientX+"px";
	a.style.top = e.clientY+"px";

	qmv_apsubs(a);

	a.style.visibility = "visible";


	qm_kille(e);
	return false;

}


function qmv_context_cmd(e,type)
{
	

	e = e || event;

	if (type=="edit texturl")
	{

		
		qmv_show_dialog("url",document.getElementById("qmv_texturl_field"));
		

	}
	else if (type=="add item")
	{

		qmv_evt_bb_click('add');

	}
	else if (type=="insert item")
	{

		qmv_evt_bb_click('insert');

	}
	else if (type=="delete item")
	{

		qmv_evt_bb_click('delete');

	}
	else if (type=="add sub menu")
	{

		qmv_evt_bb_click('addsub');

	}
	else if (type=="copy item")
	{

		qmv_evt_bb_click('copyitem');

	}
	else if (type=="paste item")
	{

		qmv_evt_bb_click('pasteitem');

	}
	else if (type=="move up")
	{

		qmv_evt_bb_click('moveup');

	}
	else if (type=="move down")
	{

		qmv_evt_bb_click('movedown');

	}
	else if (type=="add menu")
	{

		qmv_evt_bb_click('addmenu');

	}	
	else if (type=="delete menu")
	{

		qmv_evt_bb_click('deletemenu');

	}
	else if (type.indexOf("addon_")+1)
	{
		var aon = (type.substring(6));

		var cb = document.getElementById("qmv_iadd_"+aon);

		
		if (!cb.checked)
		{
			cb.checked = true;
			qmv_evt_addremove_addon(new Object(),cb);
		}

		qmv_display_setbox(null,cb);

	
	}
	else if (type=="options")
	{
		qmv_evt_menu_item_click('options');
	}
	else if (type == "set_main_vertical" || type == "set_main_horizontal")
	{
		var t = false;
		if (type == "set_main_vertical") t = true;

		var update_inp = qmv_find_update_tree_value("settings","create","isvertical",t,false,true);
		if (update_inp)
			qmv_evt_update_tree_value(update_inp);
	
	}
	else if (type == "set_sub_vertical" || type == "set_sub_horizontal")
	{
		var t = true;
		if (type == "set_sub_vertical") t = false;

		var update_inp = qmv_find_update_tree_value("settings","create","hsubs",t,false,true);
		if (update_inp)
			qmv_evt_update_tree_value(update_inp);
	
	}
	else if (type == "show_delay")
	{
		 qmv_ask_and_set_value("The show delay sets the amount of time in milliseconds between hovering over a menu item and its child sub menu appearing.<br><br>Show Delay (ms - 1/1000th of a second)",400,250,"settings","create","showdelay");	
	
	}
	else if (type == "hide_delay")
	{
		 qmv_ask_and_set_value("The hide delay sets the amount of time in milliseconds before a sub menu will close after leaving it.<br><br>Hide Delay (ms - 1/1000th of a second)",400,250,"settings","create","hidedelay");	
	
	}
	else if (type=="on_click")
	{

		var inp = qmv_find_update_tree_value("settings","create","onclick",true,false,true);
		if (inp)
			qmv_evt_update_tree_value(inp);


	}
	else if (type=="on_mouse_over")
	{

		var inp = qmv_find_update_tree_value("settings","create","onclick",false,false,true);
		if (inp)
			qmv_evt_update_tree_value(inp);


	}	
	else if (type=="quick_color_edits")
	{
		var ta = document.getElementById("qmvtree_color_shortcuts");
		qmv_shortcut_init(ta);
		qmv_display_setbox(ta.idiv,null,true);

	}
	else if (type=="color_schemes")
	{
		qmv_display_setbox(document.getElementById("qmvtree_menu_color_skins").idiv,null,true);

	}
	else if (type=="divider_styles")
	{
		var a = document.getElementById("qmvtree_item_dividers").idiv;
		qmv_display_setbox(a,null,null,true,2);

	}		
	else if (type=="insert_divider")
	{

		qmv_insert_spanitem("divider");
	

	}
	else if (type=="remove_divider")
	{
		
		var inp = qmv_find_update_tree_value("individuals","dividers","apply",false,false,true);
		if (inp)
			qmv_evt_update_tree_value(inp);
		

	}		
	else if (type=="title_styles")
	{
		var a = document.getElementById("qmvtree_item_titles").idiv;
		qmv_display_setbox(a,null,null,true,2);

	}
	else if (type=="stripe_styles")
	{
		var a = document.getElementById("qmvtree_item_stripes").idiv;
		qmv_display_setbox(a,null,null,true,2);

	}		
	else if (type=="insert_title")
	{
		
		qmv_insert_spanitem("title");
	

	}
	else if (type=="set_transparent")
	{
		
		qmv.context_input.value = "transparent";
		qmv_evt_update_tree_value(qmv.context_input);
	

	}
	else if (type=="build_click")
	{
		
		qmv_evt_build_button_click(qmv.context_build);
	

	}
	else if (type=="global_dividers")
	{
		qmv_show_dialog("applydividers")
	}
	else if (type=="ritem_apply")
	{

		
		qm_arc("qmrounditem",qmv.cur_item,true);
		qmv_update_all_addons();

	}
	else if (type=="ritem_remove")
	{

		qm_arc("qmrounditem",qmv.cur_item);
		qmv_update_all_addons();
		qmv_ritem_remove_warning()
	}
	else if (type=="item_image")
	{

		var a = document.getElementById("qmvtree_item_extra_settings").getElementsByTagName("A")[0];
		qmv_display_setbox(a,null,null,true);
	}
	else if (type=="show_properties_box")
	{
		qmv_display_setbox(qmv.tree_parent,null,null,true);
	}
	else if (type=="help_index")
	{
		qmv_show_dialog("help-index",null,"help-index.html");
	}
	else if (type=="help_about")
	{
	
		qmv_show_dialog("help-about",null,"help-about.html");
	}
	else if (type=="help_tips")
	{
		qmv_show_dialog("splash");
	}
	else if (type=="specs")
	{
		qmv_show_dialog("specs");
	}
	else if (type=="structure")
	{
		qmv_show_dialog("structure");
	}
	else if (type=="import")
	{
		qmv_show_dialog("import");
	}
	else if (type=="apply_custom_class")
	{
		qmv_show_dialog("apply_custom_class");
	}
	else if (type=="create_rule")
	{
		qmv_add_custom_css_rule();
	}
	else if (type=="stripe_styles")
	{
		
		var a = document.getElementById("qmvtree_item_stripes").getElementsByTagName("A")[0];
		qmv_display_setbox(a,null,null,true);

	}
	else if (type=="global_stripes")
	{

		qmv_show_dialog("applystripes");
	}
	else if (type=="forums")
	{
		window.open("http://www.opencube.com/forum/default.asp","_new");
	}






	qmv_hide_context();


	qm_kille(e);
	return false;

}


function qmv_show_context_build_divider()
{

	return '<span style="display:block;margin:2px 4px 6px 2px;font-size:1px;border-color:#999999;border-width:0px 0px 1px 0px;border-style:solid;"> </span>';

}


function qmv_update_slide_drop_subs(inp)
{

	var val = qmv_lib_parse_value(inp.value,"bool");
	if (val)
	{
		var gval = qmv_find_update_tree_value("addon","slide_effect","slide_drop_subs_height",null,true,true);
		if (!gval)
		{
			var update_inp = qmv_find_update_tree_value("addon","slide_effect","slide_drop_subs_height",300,false,true);
			if (update_inp)
				qmv_evt_update_tree_value(update_inp);
		}
		


	}

}


function qmv_update_slide_drop_subs_height(inp)
{

	var val = qmv_lib_parse_value(inp.value,"bool");
	if (val)
	{
		var gval = qmv_find_update_tree_value("addon","slide_effect","slide_drop_subs",null,true,true);
		if (!qmv_lib_parse_value(gval,"bool"))
		{
			var update_inp = qmv_find_update_tree_value("addon","slide_effect","slide_drop_subs","true",false,true);
			if (update_inp)
				qmv_evt_update_tree_value(update_inp);
		}
		


	}


}


function qmv_ask_and_set_value(msg,width,def,cat,rule,cname,inp,value)
{

	
	
		
	if (!value)
	{
		var d = qmv_find_update_tree_value(cat,rule,cname,null,true);
		if (d=="") d = def;

		qmv_show_dialog("question-okcancel-input",inp,msg,width,"qmv_ask_and_set_value(null,null,null,'"+cat+"','"+rule+"','"+cname+"',qmd.owner,true)",null,null,d);	

	}
	else
	{
		var val = document.getElementById("qmvi_ok_input").value;
				
		var update_inp = qmv_find_update_tree_value(cat,rule,cname,val,false,true);
		if (update_inp)
			qmv_evt_update_tree_value(update_inp);
		
	}

	
	
		

}

function qmv_display_setbox(anchor,addon_checkbox,is_quick_color,is_individual,open_qty)
{



	var a = document.getElementById("qmvi_setbox");
	

	a.anchor = anchor;
	a.addon_checkbox = addon_checkbox;
	a.is_quick_color = is_quick_color;
	a.is_individual = is_individual;

	var sc = document.getElementById("qmsetbox");
	sc.innerHTML = "";
	

	var atag = anchor;
	if (addon_checkbox) atag = addon_checkbox[qp];

	
		
	var cn = atag.cloneNode(true);
	new_atag = sc.appendChild(cn);
	cn = atag.cdiv.cloneNode(true);
	new_divs = sc.appendChild(cn);
	

	new_atag.mirror = anchor;

	var spans = sc.getElementsByTagName("SPAN");
	for (var i=0;i<spans.length;i++)
	{
		
		if (spans[i].getAttribute("isibullet"))
		{

			spans[i][qp].removeChild(spans[i]);
			i--;
			
		}
	}

	
	var divs = sc.getElementsByTagName("DIV");
	for (var i=0;i<divs.length;i++)
	{
		
		divs[i].id = "";
	}

	

	if (new_atag.getElementsByTagName("INPUT").length)
	{

		var n1 = new_atag.getElementsByTagName("INPUT")[0];
		var a1 = atag.getElementsByTagName("INPUT")[0];
		n1.mirror = a1;

		if (addon_checkbox)
			n1.checked = a1.checked;

	}



	if (is_quick_color)
	{

		new_atag.setboxquickcolor = 1;
		new_divs.id = "qmvtree_color_shortcuts_setbox";
	}

	
	var winp = new_divs.getElementsByTagName("INPUT");
	var tinp = atag.cdiv.getElementsByTagName("INPUT");
	for (var i=0;i<winp.length;i++)
	{
		winp[i].mirror = tinp[i];
		winp[i].prev_value = tinp[i].value;




		winp[i].rule = tinp[i].rule;
		winp[i].origrule = tinp[i].origrule;

		if (is_quick_color) winp[i].setboxquickcolor =1;

	}


	
	qmc_create("setbox",false,0,0,false);


	var atags = sc.getElementsByTagName("A");
	var qty = 1;
	if (open_qty) qty = open_qty;
	var cc = 0;
	for (var i=0;i<atags.length;i++)
	{
			
		if (atags[i].cdiv)
		{
			qm_arc("qmfh",atags[i].cdiv);

			if (cc<qty)
				atags[i].setAttribute("initshow",1);
			else
				atags[i].cdiv.style.display = "none";

			cc++;
		}
		
	}



	qm_vtree_init(true);
	qmv_ibullets_init(true);

	
	qmv_lib_center_element_in_window(a);
	a.style.visibility = "visible";

	qmv_adjust_setbox_shadow(a);	
	

}

function qmv_adjust_setbox_shadow(a)
{
	var shadow = document.getElementById("qmvi_setbox_shadow");

	if (a)
	{
		

		shadow.style.width = a.offsetWidth+"px";
		shadow.style.height = a.offsetHeight+"px";
		shadow.style.top = parseInt(a.style.top)+3+"px";
		shadow.style.left = parseInt(a.style.left)+3+"px";
		shadow.style.visibility = "visible";


	}
	else
	{
		if (shadow.style.visibility=="visible")
		{

			var a = document.getElementById("qmvi_setbox");
			

			shadow.style.width = a.offsetWidth+"px";
			shadow.style.height = a.offsetHeight+"px";
			shadow.style.top = parseInt(a.style.top)+3+"px";
			shadow.style.left = parseInt(a.style.left)+3+"px";

		}	
	}


}


function qmv_setbox_update_addon_check(a)
{

	if (a.mirror)
	{

		a.mirror.checked = a.checked;

	}
	else
	{
		var d = document.getElementById("qmvi_setbox");
		if (d.style.visibility == "visible")
		{
			
			var inps = d.getElementsByTagName("INPUT");
			for (var i = 0;i<inps.length;i++)
			{
				
				if (inps[i].mirror==a)
				{
					
					inps[i].checked = a.checked;


				}
			}


		}


	}




}

function qmv_setbox_update_quick_color()
{


	var sb = document.getElementById("qmvi_setbox");
	var ta = document.getElementById("qmvtree_color_shortcuts_setbox")
	if (ta && sb.style.visibility == "visible")
	{
		qmv_shortcut_init(ta);
		qmv_adjust_setbox_shadow();
	}

}


function qmv_setbox_update_individual()
{

	var sb = document.getElementById("qmvi_setbox");
	if (sb && sb.style.visibility == "visible" && sb.is_individual)
	{
		
		var inp = sb.getElementsByTagName("INPUT");
		for (var i=0;i<inp.length;i++)
		{
			
			inp[i].value = inp[i].mirror.value;
		}
	}


}


function qmv_add_bullet_events(a)
{


	a.oncontextmenu = function(e)
	{
				
		e = e || event;
		qmv_show_context(e,"bullet");

	}
	a.ondblclick = function(e)
	{
				
		e = e || event;
		qmv_context_cmd(e,"addon_item_bullet");

	}

}

function qmv_add_bullet_css_events(a)
{

	
	a.oncontextmenu = function(e)
	{
			
		e = e || event;
		qmv_show_context(e,"bullet_css");

	}
	a.ondblclick = function(e)
	{
				
		e = e || event;
		qmv_context_cmd(e,"addon_ibcss");

	}

}



function qmv_apsubs(a)
{
	
	
	var wh = qmv_get_doc_wh();
	var sxy =  qmv_get_doc_scrollxy();
	var xy = qmv_get_offset(a);

	var c1 = a.offsetWidth+xy[0];
	var c2 = wh[0]+sxy[0]-10;
	if (c1>c2)
	{
		a.style.left = (parseInt(a.style.left)-(c1-c2))+"px";

		if (a.hasrcorner) a.hasrcorner.style.left = (parseInt(a.hasrcorner.style.left)-(c1-c2))+"px";
		if (a.hasshadow) a.hasshadow.style.left = (parseInt(a.hasshadow.style.left)-(c1-c2))+"px";
		if (a.hasselectfix) a.hasselectfix.style.left = (parseInt(a.hasselectfix.style.left)-(c1-c2))+"px";

	}
	
	c1 = a.offsetHeight+xy[1];
	c2 = wh[1]+sxy[1];
	if (c1>c2)
	{

		a.style.top = (parseInt(a.style.top)-(c1-c2))+"px";

		if (a.hasrcorner) a.hasrcorner.style.top = (parseInt(a.hasrcorner.style.top)-(c1-c2))+"px";
		if (a.hasshadow) a.hasshadow.style.top = (parseInt(a.hasshadow.style.top)-(c1-c2))+"px";
		if (a.hasselectfix) a.hasselectfix.style.top = (parseInt(a.hasselectfix.style.top)-(c1-c2))+"px";
	}


}

function qmv_get_offset(obj)
{

	var x = 0;
	var y = 0;

	do
	{
		x += obj.offsetLeft;
		y += obj.offsetTop;	
	
	}
	while (obj = obj.offsetParent)

	return new Array(x,y);

}


function qmv_get_doc_scrollxy()
{

	var sy = 0;
	var sx = 0;
	if ((sd = document.documentElement) && (sd = sd.scrollTop))
		sy = sd;
	else if (sd = document.body.scrollTop)
		sy = sd;	
		
	if ((sd = document.documentElement) && (sd = sd.scrollLeft))
		sx = sd;
	else if (sd = document.body.scrollLeft)
		sx = sd;


	return new Array(sx,sy);


}


function qmv_get_doc_wh()
{	
	

	db = document.body;
	var w=0;
	var h=0;

	if (tval = window.innerHeight)
	{
		h = tval;
		w = window.innerWidth;
		
	}
	else if ((e = document.documentElement) && (e = e.clientHeight))
	{
		
		h = e;
		w = document.documentElement.clientWidth;
		
	}
	else if (e = db.clientHeight)
	{
		if (!h) h = e;
		if (!w) w = db.clientWidth;
	}

	
	return new Array(w,h);

}


function qmvi_gld_none_checked(src)
{

	if (src.checked)
	{

		var tbox = document.getElementById("qmvi_gld_sub");
		tbox.checked = false;


		tbox = document.getElementById("qmvi_gld_main");
		tbox.checked = false;

	}

}

function qmvi_gld_submain_checked(src)
{

	if (src.checked)
	{

		var tbox = document.getElementById("qmvi_gld_none");
		tbox.checked = false;


	}

}

function qmv_gld_init()
{

	var sub = document.getElementById("qmvi_gld_sub");
	var main = document.getElementById("qmvi_gld_main");
	var above = document.getElementById("qmvi_gld_above");
	var below = document.getElementById("qmvi_gld_below");
	var none = document.getElementById("qmvi_gld_none");

	if (qmv.globaldividers_sub)
		sub.checked = true;
	else
		sub.checked = false;

	if (qmv.globaldividers_main)
		main.checked = true;
	else
		main.checked = false;

	if (qmv.globaldividers_above)
		above.checked = true;
	else
		above.checked = false;

	if (qmv.globaldividers_below)
		below.checked = true;
	else
		below.checked = false;

	if (qmv.globaldividers_none)
		none.checked = true;
	else
		none.checked = false;


}


function qmv_gld_apply_dividers()
{

	qmv_hide_pointer("qm"+qmv.id);

	var sub = document.getElementById("qmvi_gld_sub").checked;
	var main = document.getElementById("qmvi_gld_main").checked;
	var above = document.getElementById("qmvi_gld_above").checked;
	var below = document.getElementById("qmvi_gld_below").checked;
	var none = document.getElementById("qmvi_gld_none").checked;	

	qmv.globaldividers_sub = sub;
	qmv.globaldividers_main = main;
	qmv.globaldividers_above = above;
	qmv.globaldividers_below = below;
	qmv.globaldividers_none = none;


	var m = document.getElementById("qm"+qmv.id);

	if (qmv.cur_item.className.indexOf("qmdivider")+1)
		qm_oo(new Object(),m.getElementsByTagName("A")[0],false);

	var sp = m.getElementsByTagName("SPAN");
	for (var i=0;i<sp.length;i++)
	{

		if (sp[i].className.indexOf("qmdivider")+1)
		{
			sp[i].parentNode.removeChild(sp[i]);
			i--;
		}

	}
	
	qmv_gld_apply_dividers2(m,sub,main,above,below);


	qmv_update_all_addons();


	if (main || sub)
		qmv_context_cmd(new Object(),'divider_styles');



}


function qmv_gld_apply_dividers2(m,sub,main,above,below,is_sub)
{

	var ch = m.childNodes
	var is_first = true;
	var cura;

	for (var i=0;i<ch.length;i++)
	{



		if (ch[i].tagName=="A")
		{
			cura = ch[i];
			if (!is_sub)
			{

				if (main)
				{

					if ((above && is_first) || !is_first)
					{
						qmv_insert_spanitem("divider",ch[i],true,null,true);
						i++;
					}

				}

			}			
			else
			{

				if (sub)
				{

					if ((above && is_first) || !is_first)
					{
						
						qmv_insert_spanitem("divider",ch[i],true,null,true);
						i++;
					}

				}

			}


			is_first = false;

		}


		if (ch[i].tagName=="DIV")
		{


			new qmv_gld_apply_dividers2(ch[i],sub,main,above,below,true);

		}


	}


	if (below)
	{

		if ((!is_sub && main) || (is_sub && sub))
			qmv_insert_spanitem("divider",cura,true,true,true);



	}


}


function qmv_ritem_underlying_styles(isyes,type)
{
	
	if (!isyes)
	{
		
		
		qmv_show_dialog("question-yesno",null,"Rounded items sit on top of the menus standard items. Some of the features of the standard items may bleed through if your rounded items are transparent or sized smaller than their standard items.<br><br>Use the standard item padding to tweak rounded sub menu items and the sub menu positioning relative to main items.<br><br>Some standard item style options are used to change the look of rounded items. These include text color, and text decoration for static, hover and active groups.<br><br>Click yes to display the standard item styles properties box, or adjust settings using the 'CSS Filters' or 'CSS Styles' options in the tree.<br><br>Would you like to display the standard item styles?",500,"qmv_ritem_underlying_styles(true,'"+type+"')");	
		
		
		
	}
	else
	{
		

		if (type=="main")
		{
			var a = qmv_find_rule_atag("#qm[i] a")
			qmv_display_setbox(a[qp].idiv,null,null,true,2);
		}
		else
		{
			var a = qmv_find_rule_atag("#qm[i] div a")
			qmv_display_setbox(a[qp].idiv,null,null,true,2);

		}

	}		
	

}



function qmv_find_rule_atag(rule)
{

	var a = document.getElementById("qmvtree");
	var d = a.getElementsByTagName("DIV");

	for (var i=0;i<d.length;i++)
	{
		if (d[i].getAttribute("rule") == rule)
		{

			return d[i].idiv;

		}

	}


}

function qmv_ritem_remove_main_styles(type)
{

	if (type=="main")
	{

		var inp;
		inp = qmv_find_update_tree_value("css","#qm[i] a","borderStyle","none",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		inp = qmv_find_update_tree_value("css","#qm[i] a","backgroundColor","",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		

	}
	else if (type=="sub")
	{

		var inp;
		inp = qmv_find_update_tree_value("css","#qm[i] div a","borderStyle","none",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

		inp = qmv_find_update_tree_value("css","#qm[i] div a","backgroundColor","",false,true);
		qmv_evt_update_tree_value(inp,null,null,null,null,null,true);

	}




}

function qmv_ritem_remove_warning()
{
	qmv_show_dialog("alert",null,"Note: Depending on how your rounded corners are applied (see the 'apply' setting within the add-ons options) the current item may not be removable.",600);

}


function qmv_specs_get_addon_html()
{

	var rt= "";
	var tally = 0;
		

	var adds = new Object();

	var j;
	var got_one = false;
	for (var q=0;q<10;q++)
	{
		
		for (j in qmv.addons)
		{
			if (qmv.addons[j]["on"+q])
			{
				got_one = true;
				adds[j] = 1;
			}
		}
	}


	

	
	if (got_one)
	{

		for (j in adds)
		{
			
			var ao;
			if (ao = qmv.addons[j])
			{
				var s = qmv_lib_get_kilobytes(ao.code.length,true);
				tally += parseFloat(s);
	
				rt += '<tr>';

				rt += '<td style="vertical-align:top;color:#444444;width:100%;">Add-on: '+ao.desc+':</td>'
				rt += '<td><div style="font-size:1px;width:15px;"></div></td>'
				rt += '<td style="color:#222222;width:50px;text-align:right;vertical-align:top;padding-bottom:2px;white-space:nowrap" nowrap>'+s+'</td>'


				rt += '</tr>';
	
			}
				
		}

	}
	


	
	


	return new Array(rt,tally);


}


function qmv_lib_get_kilobytes(num,limited)
{
	
	bytes = num+"";
	if (bytes.length>3)
	{
		p1 = bytes.substring(0,bytes.length-3);
		p2 = bytes.substring(bytes.length-3);

		bytes = p1+","+p2;
	}
	else
		p2 = num;

	
	num = Math.round((num/1024)*10)/10;
	
	if ((num+"").indexOf(".")==-1)
		num = num+"."+0;


	if (limited)
		return num+" KB";
	else
		return num+" KB   ("+bytes+" bytes)";

}




function qmv_warn_external_pure(a)
{

	
	if (document.getElementById(a).checked)
		qmv_show_dialog("alert",null,"External menu structures are scripted and should not be combined with the 'Pure CSS' HTML structure type.<br><br>To use the pure css structure with an external file for delivery to multiple pages, use a server side include.  To do this publish the structure using the 'In Page' option and manually copy and paste the data to your documents include file.",500)
	


}


function qmv_html_structure_help()
{

	
	qmv_show_dialog("help-structure_type.html",null,"help-structure_type.html");
	


}



function qmv_import_menu()
{


	var mc = document.getElementById("qmvi_import_stucture_content");

	if (mc.value)
	{


		var a = document.createElement("DIV");
		a.innerHTML = mc.value;		
		var ul = a.getElementsByTagName("UL")[0];

		if (ul)
		{
			var menu = qmv_import_convert(ul);

			menu.removeAttribute("qmpure");
			menu.className = "qmmc";

			qmv_add_new_menu(menu);

			
		}
	}
	else
	{
		qmv_show_dialog("alert",null,"First paste the ul / li structure you wish to import.",400);

		return false;		
	}


	return true;

}


function qmv_import_convert(sd)
{

	
			
		
	var nd = document.createElement("DIV");
	nd.qmpure = 1;
	qmv_import_convert2(sd,nd);

	var csp = document.createElement("SPAN");
	csp.className = "qmclear";
	csp.innerHTML = " ";
	nd.appendChild(csp);

	nd = sd[qp].insertBefore(nd,sd);
	sd[qp].removeChild(sd);
	sd = nd;

	
	return sd;
}


function qmv_import_convert2(a,bm,l)
{

	if (!l)
	{		
		bm.className = a.className;
		bm.id = a.id;
	}

	var ch = a.childNodes;
	for (var i=0;i<ch.length;i++)
	{

		if (ch[i].tagName=="LI")
		{
			var sh = ch[i].childNodes;
			var gota = false;
			for (var j=0;j<sh.length;j++)
			{

				if (sh[j] && (sh[j].tagName=="A" || sh[j].tagName=="SPAN"))
				{
					gota = true;

					var ra = ch[i].removeChild(sh[j]);
					var ras = ra.getElementsByTagName("SPAN");
					for (var k=0;k<ras.length;k++)
					{
						if (ras[k].className.indexOf("imea")+1)
							ra.removeChild(ras[k]);
					}

					bm.appendChild(ra);
				}
			
			}


			if (!gota)
			{
				
				var newa = document.createElement("A");
				newa.innerHTML = "New Text";
				newa.setAttribute("href","javascript:void(0);");
				bm.appendChild(newa);
			}


			var hu;
			if (hu = ch[i].getElementsByTagName("UL")[0])
			{
					
				var na = document.createElement("DIV");
				na = bm.appendChild(na);
				new  qmv_import_convert2(hu,na,true)

			}	
			

		}		


	}


}



function qmv_add_custom_css_rule()
{

	//qmvtree_custom_rules
	qmv_show_dialog("custom_rule")


}


function qmv_load_custom_styles_to_tree()
{

	var n = document.getElementById("qmvtree_custom_rules");

	var nc = n.childNodes;
	var isf = 0;
	for (var i=0;i<nc.length;i++)
	{
		if (nc[i] && (nc[i].tagName=="A" || nc[i].tagName=="DIV"))
		{
			if (isf<1)
			{
				isf++;
				continue;
			}

			
			n.removeChild(nc[i]);
			i--;
		}

	}
	
	

	var rules = qmv.style_rules;
	for (var i=0;i<rules.length;i++)
	{

		var st = rules[i].selectorText.toLowerCase();
		if (st.indexOf("div.qmmc")+1 || st.indexOf("div#qm"+qmv.id)+1)
		{	
			var sr = st.replace("qm"+qmv.id,"qm[i]");
			sr = sr.replace("qm"+qmv.id,"qm[i]");

			var sp_r = sr.split(",")[0];
			qmv_tree_create_new_node("container",n,sp_r,"Rule <span class='qmvtree-rule'>["+qmv_rule_truncate(st,20)+"]</span>",sr);
		}
	}


	qmv_update_tree_after_load(n);
	

	for (var i=0;i<rules.length;i++)
	{

		var st = rules[i].selectorText.toLowerCase();
		if (st.indexOf("div.qmmc")+1 || st.indexOf("div#qm"+qmv.id)+1)
		{	
			
			qmv_load_styles_to_tree_node(rules[i],st,null,n.getElementsByTagName("DIV"));
		
		}

	}
	

}

function qmv_tree_create_new_node(type,p,rule,desc,orig_rule)
{


	var anc = document.createElement("A");
	anc.setAttribute("href","javascript:void(0);");
	anc.innerHTML = desc+'<span onClick="qmv_custom_rule_edit(event,this)" style="padding-left:10px;font-size:11px;color:#dd3300;">[edit]</span><span onClick="qmv_custom_rule_delete(event,this)" style="padding-left:5px;font-size:11px;color:#dd3300;">[x]</span>';
			
	var div = document.createElement("DIV");
	div.setAttribute("rule",rule);
	div.setAttribute("orule",orig_rule);
	div.ismaster = 1;
	var wt = "";

	if (type=="container")
	{
				
		wt += qmv_init_interface_tree_bracket(true);
		wt += qmv_init_interface_tree_item('width','width','width','css','unit',null,'x<0');
		wt += qmv_init_interface_tree_item('height','height','height','css','unit',null,'x<0');
		wt += qmv_init_interface_tree_item(null,'padding','padding','css','edge-padding',null,'x<0');
		wt += qmv_init_interface_tree_item(null,'margin','margin','css','edge-margin',null,null);
			
		wt += '<a href="#" style="margin-top:6px;">Background Styles</a>'
			wt+='<div orule = "+orig_rule+" rule="'+rule+'">';
			wt += qmv_init_interface_tree_item('bg-color','background-color','backgroundColor','css','color',null,null);
			wt += qmv_init_interface_tree_item('bg-image','background-image','backgroundImage','css','styleimage',null,null);
			wt += qmv_init_interface_tree_item('bg-repeat','background-repeat','backgroundRepeat','css','styleimagerepeat',null,null);
			wt += qmv_init_interface_tree_item('bg-position','background-position','backgroundPosition','css','styleimageposition',null,null);
			wt+='</div>';

		wt += '<a href="#">Font Styles</a>'
			wt+='<div orule = "+orig_rule+" rule="'+rule+'">';
			wt += qmv_init_interface_tree_item('color','color','color','css','color',null,null);
			wt += qmv_init_interface_tree_item('family','font-family','fontFamily','css','fontfamily',null,null);
			wt += qmv_init_interface_tree_item('size','font-size','fontSize','css','unit',null,'x<0');
			wt += qmv_init_interface_tree_item('decoration','text-decoration','textDecoration','css','textdecoration',null,null);
			wt += qmv_init_interface_tree_item('style','font-style','fontStyle','css','fontstyle',null,null);
			wt += qmv_init_interface_tree_item('weight','font-weight','fontWeight','css','fontweight',null,null);
			wt += qmv_init_interface_tree_item('align','text-align','textAlign','css','textalign',null,null);
			wt+='</div>';

		wt += '<a href="#">Border Styles</a>'
			wt+='<div orule = "+orig_rule+" rule="'+rule+'">';
			wt += qmv_init_interface_tree_item(null,'border-width','borderWidth','css','edge-borderwidth',null,'x<0');
			wt += qmv_init_interface_tree_item(null,'border-style','borderStyle','css','borderstyle',null,null);
			wt += qmv_init_interface_tree_item(null,'border-color','borderColor','css','color',null,null);
			wt+='</div>';

		wt += qmv_init_interface_tree_bracket();

	}


	div.innerHTML = wt;
	
	anc = p.appendChild(anc);
	div = p.appendChild(div);


	return anc;
}

function qmv_custom_rule_dialog_content()
{

	var ih = "";

	ih += '<div style="padding:10px;">';


		ih += '<div style="padding:0px 4px 0px 4px;">';
		ih += '<table cellpadding=0 cellspacing=0 border=0 style="width:100%;">';
			ih += '<tr>';

				ih += '<td style="white-space:nowrap;">Predefined Rules: </td>';
				ih += '<td><div style="width:10px;"> </div></td>';
				ih += '<td style="width:100%;"><select onchange="qmv_apply_pre_defined_rule(event,this)" id="qmvccc_pre_defined" style="width:100%;">';

					ih += '<option value="">Choose Predefined Rule...</option>';
					ih += '<option value="div#qm[i] div div">Sub Containers [Level 2]</option>';
					ih += '<option value="div#qm[i] div div div">Sub Containers [Level 3]</option>';
					ih += '<option value="div#qm[i] div div div div">Sub Containers [Level 4]</option>';
					ih += '<option value="div#qm[i] div div a">Sub Items [Level 2]</option>';
					ih += '<option value="div#qm[i] div div div a">Sub Items [Level 3]</option>';
					ih += '<option value="div#qm[i] div div div div a">Sub Items [Level 4]</option>';
					ih += '<option value="div#qm[i] .qmc_mystatic">Custom Class Name [global static]</option>';
					ih += '<option value="div#qm[i] .qmc_myhover:hover">Custom Class Name [global hover]</option>';
					ih += '<option value="div#qm[i] .qmc_myactive.qmactive">Custom Class Name [global active]</option>';
					ih += '<option value="div#qm[i] .qmc_myparent.qmparent">Custom Class Name [global parent]</option>';


				ih += '</select></td>';

			ih += '</tr>';
		ih += '</table>';
		ih += '</div>';
		
		
		ih += '<div style="font-size:1px;height:30px;"></div>';
		

		ih += '<div style="padding:0px 4px 0px 4px;">';
		ih += '<table cellpadding=0 cellspacing=0 border=0 style="width:100%;">';
			ih += '<tr>';

				ih += '<td style="vertical-align:top;width:33%;">';

					ih += '<div class="qmvi-publish-title">Menu Group</div>';
					ih += '<div style="margin-top:10px;">';
					ih += '<table cellpadding=0 cellspacing=0 border=0>';


						ih += '<tr>';
						ih += '<td><input onclick="qmv_display_rule_result()" id="qmvccc_menu_group_items" name="qmvccc_menu_group" type="radio" checked></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Items</td>';
						ih += '</tr>';

						ih += '<tr>';
						ih += '<td><input onclick="this.checked=true;qmv_display_rule_result()" id="qmvccc_menu_group_container" name="qmvccc_menu_group" type="radio"></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Containers</td>';
						ih += '</tr>';

						
						ih += '<tr>';
						ih += '<td><input onclick="this.checked=true;qmv_display_rule_result()" id="qmvccc_menu_group_spans" name="qmvccc_menu_group" type="radio"></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Spans</td>';
						ih += '</tr>';

					ih += '</table>';
					ih += '</div>';

				ih += '</td>';


				ih += '<td><div style="width:25px;"> </div></td>';

				ih += '<td style="vertical-align:top;width:33%;">';

					ih += '<div class="qmvi-publish-title">Additional Selector</div>';
					ih += '<div style="margin-top:10px;">';
					ih += '<table cellpadding=0 cellspacing=0 border=0>';

						ih += '<tr>';
						ih += '<td><input onclick="qmv_display_rule_result()" id="qmvccc_menu_add_none" name="qmvccc_menu_add" type="radio" checked></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">None</td>';
						ih += '</tr>';


						ih += '<tr>';
						ih += '<td><input onclick="qmv_display_rule_result()" id="qmvccc_menu_add_items" name="qmvccc_menu_add" type="radio"></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Items</td>';
						ih += '</tr>';

						ih += '<tr>';
						ih += '<td><input onclick="qmv_display_rule_result()" id="qmvccc_menu_add_containers" name="qmvccc_menu_add" type="radio"></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Containers</td>';
						ih += '</tr>';

						
						


					ih += '</table>';
					ih += '</div>';

				ih += '</td>';;


				ih += '<td><div style="width:25px;"> </div></td>';

				ih += '<td style="vertical-align:top;width:33%;">';

					ih += '<div class="qmvi-publish-title">Menu Types</div>';
					ih += '<div style="margin-top:10px;">';
					ih += '<table cellpadding=0 cellspacing=0 border=0>';

						ih += '<tr>';
						ih += '<td><input onclick="qmv_display_rule_result()" id="qmvccc_menu_type_both" name="qmvccc_menu_type" type="radio" checked></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">All</td>';
						ih += '</tr>';


						ih += '<tr>';
						ih += '<td><input onclick="qmv_display_rule_result();qmv_warn_pure()" id="qmvccc_menu_type_pure" name="qmvccc_menu_type" type="radio"></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Pure CSS</td>';
						ih += '</tr>';

						ih += '<tr>';
						ih += '<td><input onclick="qmv_display_rule_result()" id="qmvccc_menu_type_hybrid" name="qmvccc_menu_type" type="radio"></td>';
						ih += '<td><div style="width:6px;"> </div></td>';
						ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">Hybrid</td>';
						ih += '</tr>';

						


					ih += '</table>';
					ih += '</div>';

				ih += '</td>';;



			ih += '</tr>';
		ih += '</table>';
		ih += '</div>';


		ih += '<div style="font-size:1px;height:30px;"></div>';
		

		ih += '<div style="padding:0px 4px 0px 4px;">';
		ih += '<table cellpadding=0 cellspacing=0 border=0 style="">';
			ih += '<tr>';

				ih += '<td style="vertical-align:top;width:33%;">';

					ih += '<div class="qmvi-publish-title">Menu Level</div>';
					ih += '<div style="margin-top:10px;">';
					ih += '<table cellpadding=0 cellspacing=0 border=0  style="width:100%;">';

						
						ih += '<tr>';
						ih += '<td><select onchange="qmv_display_rule_result()" id="qmvccc_sub_level" style="width:100%;">';

							ih += '<option value=0>Main Menu+</option>';
							ih += '<option value=1>Sub Level 1+</option>';
							ih += '<option value=2>Sub Level 2+</option>';
							ih += '<option value=3>Sub Level 3+</option>';
							ih += '<option value=4>Sub Level 4+</option>';
							ih += '<option value=5>Sub Level 5+</option>';
							ih += '<option value=6>Sub Level 6+</option>';
							ih += '<option value=7>Sub Level 7+</option>';
							ih += '<option value=8>Sub Level 8+</option>';
							ih += '<option value=9>Sub Level 9+</option>';

						ih += '</select></td>';
						ih += '</tr>';


					ih += '</table>';
					ih += '</div>';

				ih += '</td>';


				ih += '<td><div style="width:25px;"> </div></td>';

				ih += '<td style="vertical-align:top;width:33%;">';

					ih += '<div class="qmvi-publish-title">Target</div>';
					ih += '<div style="margin-top:10px;">';
					ih += '<table cellpadding=0 cellspacing=0 border=0  style="width:100%;">';

						ih += '<tr>';
						ih += '<td><select onchange="qmv_display_rule_result()" id="qmvccc_target" style="width:100%;">';

							ih += '<option value="static">Static Menu</option>';
							ih += '<option value=":hover">Hover</option>';
							ih += '<option value="">&nbsp</option>';	
							ih += '<option value="">---- [specific] ----</option>';	
							ih += '<option value=".qmactive">Active</option>';
							ih += '<option value=".qmparent">Parent</option>';
							ih += '<option value=".qmdivider">Dividers</option>';
							ih += '<option value=".qmtitle">Titles</option>';
							ih += '<option value="">&nbsp</option>';	
							ih += '<option value="">---- [global] ----</option>';
							ih += '<option value=" .qmactive">Active</option>';
							ih += '<option value=" .qmparent">Parent</option>';
							ih += '<option value=" .qmdivider">Dividers</option>';
							ih += '<option value=" .qmtitle">Titles</option>';

						ih += '</select></td>';
						ih += '</tr>';

					ih += '</table>';
					ih += '</div>';

				ih += '</td>';;


				ih += '<td><div style="width:25px;"> </div></td>';

				ih += '<td style="vertical-align:top;width:33%;">';

					ih += '<div class="qmvi-publish-title">Custom Class Name</div>';
					ih += '<div style="margin-top:10px;">';
					ih += '<table cellpadding=0 cellspacing=0 border=0  style="width:100%;">';

						ih += '<tr>';
						ih += '<td><input onkeypress="if (event.keyCode==13){qmv_display_rule_result();qm_kille(event);return false}" onchange="qmv_display_rule_result()" id="qmvccc_custom_class_name" type="text" style="width:95%;"></input></td>';
						ih += '</tr>';

					ih += '</table>';
					ih += '</div>';

				ih += '</td>';



			ih += '</tr>';
		ih += '</table>';
		ih += '</div>';


		ih += '<div style="margin-top:20px;margin-bottom:-5px;padding:0px 4px 0px 6px;">';
			ih += '<table cellpadding=0 cellspacing=0 border=0  style="width:100%;">';

			ih += '<tr>';
			ih += '<td class="qmvi-common qmvi-dialog-input-title" style="white-space:nowrap;">Rule:</td>';
			ih += '<td><div style="width:6px;"> </div></td>';
			ih += '<td id="qmvccc_rule_result" class="qmvi-common qmvi-dialog-input-title" style="width:100%;color:#0033bb"></td>';
			ih += '</tr>';

		ih += '</table>';
		ih += '</div>';


	ih += '</div>';


	return ih;

}


function qmv_custom_rule_build(get_display,uobj)
{

	var brule = "";
	var brule_p = "";
	var brule_h = "";

	
	var group = "items";
	var add = "";
	var type_pure = true;
	var type_hybrid = true;
	var target = 'static';
	var sub_level = 0;
	var class_name = "";

	if (document.getElementById("qmvccc_menu_group_container").checked)
		group = "container";

	if (document.getElementById("qmvccc_menu_group_spans").checked)
		group = "spans";
		
	if (document.getElementById("qmvccc_menu_add_items").checked)
		add = " a";

	if (document.getElementById("qmvccc_menu_add_containers").checked)
		add = " div";


	if (!document.getElementById("qmvccc_menu_type_pure").checked)
		 type_pure = false;

	if (!document.getElementById("qmvccc_menu_type_hybrid").checked)
		 type_hybrid = false;
	
	if (document.getElementById("qmvccc_menu_type_both").checked)
	{
		 type_pure = true;
		 type_hybrid = true;
	}


	var o_custname = document.getElementById("qmvccc_custom_class_name");
	o_custname.value = o_custname.value.replace(/ /g,"");
	

	sub_level = document.getElementById("qmvccc_sub_level").value;
	target = document.getElementById("qmvccc_target").value;
	class_name = o_custname.value;


	


			
	if (target==".qmdivider" || target==".qmtitle")
	{
		document.getElementById("qmvccc_menu_group_container").checked = true;
		group = "container";
	}


		
	brule_h += "div#qm"+qmv.id;
	

	var ig = "";
	for (var i=0;i<=sub_level;i++)
	{
		if (group!="items" && group!="spans" && i==sub_level)
			break;
		

		if (group=="items" && i==sub_level)
			ig+=" a";
		else if (group=="spans" && i==sub_level)
			ig+=" span";
		else
			ig+=" div";



	}
	brule_h += ig;



	if (class_name)
	{

		class_name = class_name.replace(".","");
		document.getElementById("qmvccc_custom_class_name").value = class_name;

		if (ig)
			class_name = ".qmc_"+class_name;
		else
			class_name = " .qmc_"+class_name;

		brule_h+=class_name;


		if ((target.indexOf(".")+1) && (target.indexOf(" .")==-1) && get_display)
			qmv_show_dialog("alert",null,"This multiple class targeted rule (example: .qmc_myclass.qmparent) is not compatible with IE6 (IE7 and other browsers are compatible) and will not display as intended in this browser.  Alter your target setting to tweek for IE6 compatibility.",600);
	}


	brule_h += add;
	
	
	
	if (target.indexOf(".")+1)
		brule_h+=target;
	else if (target.indexOf(":")==0)
		brule_h+=target;
	
	


	brule_p = brule_h.replace(/div/g,"ul");


	if (type_hybrid)
		brule += brule_h;

	if (type_pure && type_hybrid)
		brule += ", ";

	if (type_pure)
		brule += brule_p;		




	if (get_display)
		return brule;



	var rules = qmv.style_rules;
	for (var i=0;i<rules.length;i++)
	{
			
		var st = rules[i].selectorText.toLowerCase().split(",")[0];
		if (st==brule)
		{	
			
			qmv_show_dialog("alert",null,"This rule already exists. Duplicate rules may not be added, instead use the existing rule to customize your styles.",400);	
			return;
		}
	}


	if (!uobj)
	{
			

		var n = document.getElementById("qmvtree_custom_rules");
		var sr = brule_h.replace("qm"+qmv.id,"qm[i]");

		var or = brule.replace("qm"+qmv.id,"qm[i]");
		or = or.replace("qm"+qmv.id,"qm[i]");

		var anc = qmv_tree_create_new_node("container",n,sr,"Rule <span class='qmvtree-rule'>["+qmv_rule_truncate(brule,20)+"]</span>",or);

		qmv_update_tree_after_load(n);
		qmv_load_styles_to_tree_node(null,brule,true,n.getElementsByTagName("DIV"),true);


		qmv_display_setbox(anc);

		return true;
		

	}
	else
	{

		//update the existing rule obj here
		var sr = uobj.getAttribute('rule');
		sr = sr.replace("qm[i]","qm"+qmv.id);

		
		var rules = qmv.style_rules;
		for (var i=0;i<rules.length;i++)
		{

			var st = rules[i].selectorText.toLowerCase().split(",")[0];
			if (st==sr)
			{	
				
				
				qmv_lib_update_add_rule_styles(qmv.styles,qmv.style_rules,brule_h,rules[i].style.cssText);
				qmv_lib_update_remove_rule(qmv.styles,qmv.style_rules,st);

				break;
				
			}
		}

		var ssr = brule_h.replace("qm"+qmv.id,"qm[i]");
		var oor = brule.replace(/"qm"+qmv.id/g,"qm[i]");

		uobj.setAttribute("rule",ssr);
		uobj.setAttribute("orule",oor);
		var spans = uobj.idiv.getElementsByTagName("SPAN");
		for (var i=0;i<spans.length;i++)
		{
			if (spans[i].className.indexOf("qmvtree-rule")+1)
				spans[i].innerHTML = "["+qmv_rule_truncate(brule,20)+"]";

		}
		
		

		var dd = uobj.getElementsByTagName("DIV");
		for (var i=0;i<dd.length;i++)
		{
			dd[i].setAttribute("rule",ssr);
			dd[i].setAttribute("orule",oor);
		}


		var inps = uobj.getElementsByTagName("INPUT");
		for (var i=0;i<inps.length;i++)
		{
			inps[i].rule = brule_h;
		}


		qmv_display_setbox(uobj.idiv);

		qmv_set_all_subs_to_default_position(true,qmad.br_ie);
		qmv_update_all_addons();
		qmv_position_pointer();
		

		return true;

	}

	

}

function qmv_apply_pre_defined_rule(e,a)
{

	var rule = a.value.replace("qm[i]","qm"+qmv.id);
	rule = rule+", "+rule.replace(/div/g,"ul");

	if (rule)
		qmv_custom_rule_parse_rule(rule);


}


function qmv_custom_rule_parse_rule(rule)
{
	

	var o_container = document.getElementById("qmvccc_menu_group_container");
	var o_item = document.getElementById("qmvccc_menu_group_items");
	var o_span = document.getElementById("qmvccc_menu_group_spans");

	var o_both = document.getElementById("qmvccc_menu_type_both");
	var o_pure = document.getElementById("qmvccc_menu_type_pure");
	var o_hybrid = document.getElementById("qmvccc_menu_type_hybrid");

	var o_level = document.getElementById("qmvccc_sub_level");
	var o_target = document.getElementById("qmvccc_target");
	var o_custname = document.getElementById("qmvccc_custom_class_name");
	
	var o_additems = document.getElementById("qmvccc_menu_add_items");
	var o_addcontainers = document.getElementById("qmvccc_menu_add_containers");
	var o_addnone = document.getElementById("qmvccc_menu_add_none");

	var o_pre = document.getElementById("qmvccc_pre_defined");
	
	

	if ((rule.indexOf("div#qm")+1) && (rule.indexOf("ul#qm")+1))
	{
		o_both.checked = true;

	}
	else		
	{
		if (rule.indexOf("div#qm")+1)
			o_hybrid.checked = true;

		if (rule.indexOf("ul#qm")+1)
			o_pure.checked = true;
	
	}

	var wrule = rule.split(",")[0];


	var ops = o_pre.getElementsByTagName("OPTION");
	for (var i=0;i<ops.length;i++)
	{
		
		var tpr = ops[i].value;
		if (tpr==wrule)
		{
			ops[i].selected = true;
		}

	}

	o_addnone.checked = true;

	var d1 = 0;
	var a1 = 0;
	var s1 = 0;
	var add = "";

	var phase1 = true;
	var got_cust_name;

	var pt = wrule.split(" ");
	for (var i=1;i<pt.length;i++)
	{
		pt[i].replace(/ /g,'');
		if (pt[i])
		{
			
			if (phase1)
			{
				var go = false;
				if (pt[i].indexOf("div")==0)
				{
					d1++;
						
					if (pt[i]=="div")
						go = true;
				}

				if (pt[i].indexOf("a")==0)
				{

					a1++;
	
					if (pt[i]=="a")
						go = true;
				}
		
				if (pt[i].indexOf("span")==0)
				{
					s1++;

					if (pt[i]=="span")
						go = true;

				}


				
				if (!go)
					phase1 = false;

				

			}
			else
			{
				
				if (pt[i].indexOf("div")==0)
				{
					o_addcontainers.checked = true;
				}
				else if (pt[i].indexOf("a")==0)
				{
					
					o_additems.checked = true;
				}
				
					
				


			}

		}

		var cp = 0;
		if ((cp = pt[i].indexOf(".qmc_"))>-1)
		{

			pt[i] = pt[i].substring(cp+5);

			if (pt[i].indexOf(":")+1)
				pt[i] = pt[i].substring(0,pt[i].indexOf(":"))

			if (pt[i].indexOf(".")+1)
				pt[i] = pt[i].substring(0,pt[i].indexOf("."))


			got_cust_name = true;
			o_custname.value = pt[i];
		}


	}


	if (!got_cust_name)
		o_custname.value = "";
	
	o_container.checked = true;
	if (a1) o_item.checked = true;
	if (s1) o_span.checked = true;



	o_level.value = d1;


	var vl_ar = new Array(":hover",".qmactive",".qmparent",".qmtitle",".qmdivider"," .qmactive"," .qmparent"," .qmtitle"," .qmdivider");
	var ft;
	for (var i=0;i<vl_ar.length;i++)
	{
		
		if (wrule.indexOf(vl_ar[i])+1)
		{
			ft = true;
			o_target.value = vl_ar[i];
			
		}
	}

	if (!ft) o_target.value = "static";		

		
	
	qmv_display_rule_result();

}



function qmv_display_rule_result()
{

	var rs = qmv_custom_rule_build(true);
	if (!rs) rs = "";

	document.getElementById("qmvccc_rule_result").innerHTML = rs+" {}";


}


function qmv_rule_truncate(t,len)
{
	if (t.length>len)
		return t.substring(0,len-3)+"...";

	return t;

}


function qmv_custom_rule_edit(e,a)
{
	e = e || event;


	
	if (a[qp].mirror) a = a[qp].mirror.getElementsByTagName("SPAN")[0];
	

	var or = a[qp].cdiv.getAttribute("orule");
	qmv_show_dialog("custom_rule",a[qp].cdiv,null,null,null,null,null,or)


	qm_kille(e);
	return false;

}

function qmv_custom_rule_delete(e,a,obj)
{
	var close_setbox;

	var atag;
	if (obj)
		atag = obj;
	else
	{
		if (a[qp].mirror)
		{
			a = a[qp].mirror.getElementsByTagName("SPAN")[0];
			close_setbox = true;
		}

		atag = a[qp];
	}

	var rule = atag.cdiv.getAttribute('rule');
	rule = rule.replace("qm[i]","qm"+qmv.id);
	
	qmv_lib_update_remove_rule(qmv.styles,qmv.style_rules,rule);


	atag.cdiv[qp].removeChild(atag.cdiv);
	atag[qp].removeChild(atag);

	qmv_update_tree_after_load(document.getElementById("qmvtree_custom_rules"));


	qmv_set_all_subs_to_default_position(true,qmad.br_ie);
	qmv_update_all_addons();
	qmv_position_pointer();

	if (close_setbox)
		qmv_hide_dialog(false,false,true);


}


function qmv_update_tree_after_load(n)
{

				
	qm_create(n,false,0,0,false,null,null,null,null,2);
	if (qmv.loaded)
	{
		qm_vtree_init_items(n,true);
		qmv_ibullets_init_items(n);
	}

}


function qmv_warn_pure()
{


	qmv_show_dialog("alert",null,"Pure CSS styles are only visible when running a pure css menu type without JavaScript. Save and view your menu with JavaScript disabled in the browser to see your style settings for this rule.",500);
	

}

function qmv_custom_class_get_list()
{

	var rar = new Object();
	var count = 0;

	var rules = qmv.style_rules;
	for (var i=0;i<rules.length;i++)
	{
		
		var st = rules[i].selectorText.toLowerCase();
		var loc;
		if (((loc = st.indexOf(".qmc_"))+1) && (!qmad.br_ie || (st.indexOf("ul#qm")==-1)))
		{	
			rar["a"+count] = new Object();
			rar["a"+count].rule = st;

			var cn = st.substring(loc+5);
			if ((loc = cn.indexOf(" "))+1)
				cn = cn.substring(0,loc)

			if ((loc = cn.indexOf(":"))+1)
				cn = cn.substring(0,loc)

			if ((loc = cn.indexOf("."))+1)
				cn = cn.substring(0,loc)

			if ((loc = cn.indexOf(","))+1)
				cn = cn.substring(0,loc)


			rar["a"+count].showclass = cn;
			rar["a"+count].classname = "qmc_"+cn;
			rar["a"+count].display = cn+" ["+st+"]";
			rar["a"+count].rule = st;

			count++;
				
		}
	}
	
	return rar;

}

function qmv_custom_class_list_change(e,no_select)
{
	var ebutton = document.getElementById("qmvacc_edit");
	var a = document.getElementById("qmvacc_classes");
	
	if (!a.value)
		ebutton.style.visibility = "hidden";
	else
		ebutton.style.visibility = "visible";


	var is_ai = document.getElementById("qmvacc_apply_allitems").checked;
	var is_ad = document.getElementById("qmvacc_apply_alldividers").checked;
	var is_at = document.getElementById("qmvacc_apply_alltitles").checked;
	var is_speci = document.getElementById("qmvacc_apply_item").checked;
	var is_specc = document.getElementById("qmvacc_apply_container").checked;


	var sobjs = new Object();
	if (is_ai || is_ad || is_at)
	{
		var ch = qmv.cur_item[qp].childNodes;
		for (var i=0;i<ch.length;i++)
		{

			if (ch[i] && ch[i].tagName)
			{

				if ((is_ai && ch[i].tagName=="A") || (is_ad && ch[i].tagName=="SPAN" && ch[i].className.indexOf("qmdivider")+1) || (is_at && ch[i].tagName=="SPAN" && ch[i].className.indexOf("qmtitle")+1))
				{
					
					sobjs["a"+i] = new Object();
					sobjs["a"+i] = ch[i];
				}

			}
		
		}

	}

	if (is_speci || is_specc)
	{
		sobjs.a0 = new Object();
		if (is_speci)
			sobjs.a0=qmv.cur_item;
		else
			sobjs.a0=qmv.cur_item[qp];
	}

	var apmsg = "[applied]&nbsp;&nbsp;&nbsp;";
	var ops = a.getElementsByTagName("OPTION");
	for (var i=0;i<ops.length;i++)
	{
		
		var val;
		if (val = ops[i].getAttribute("value"))
		{
			var j;
			var match;
			var nin = ops[i].innerHTML;

			for (j in sobjs)
			{
				
				if (sobjs[j].className.indexOf(val)+1)
				{
					if (!no_select)
						ops[i].selected = true;

					if (nin.indexOf(apmsg)==-1)
						ops[i].innerHTML = apmsg+ops[i].innerHTML;

					match = true;
					
				}


				if (match)
					break;
				

			}


			if (!match)
			{
				if (nin.indexOf(apmsg)+1)
					ops[i].innerHTML = nin.substring(apmsg.length);
			}




		}

	}	

	

}


function qmv_custom_class_dialog_content()
{

	var ih = "";

	ih += '<div style="padding:10px;">';


	ih += '<div class="qmvi-publish-title" style="margin-bottom:10px;border-width:0px;" >Custom Classes ';

		ih += '<span onclick="qmv_apply_class_action(\'add_new\')" style="cursor:default;color:#dd3300;padding-left:5px;">[add new +]</span>';
		ih += '<span id="qmvacc_edit" style="visibility:hidden;">';
		ih += '<span onClick="qmv_apply_class_action(\'edit_rules\')" style="cursor:default;color:#dd3300;padding-left:5px;">[edit rule]</span>';
		ih += '<span onClick="qmv_apply_class_action(\'edit_styles\')" style="cursor:default;color:#dd3300;padding-left:5px;">[edit styles]</span>';
		ih += '</span>';
	
	ih += '</div>';	


	ih += '<div style="margin-top:3px;">';
	ih += '<table cellpadding=0 cellspacing=0 border=0 style="width:100%;">';

		ih += '<tr>';
		ih += '<td style="width:100%;"><select multiple=true onchange="qmv_custom_class_list_change(event,true)" id="qmvacc_classes" style="width:100%;" size=6>';

			var tobj = qmv_custom_class_get_list();
			var go = false;
			for (var i in tobj)
			{
				if (tobj[i])
				{
					var adds = "";
					if (!go) adds = "selected";
					ih += '<option rule="'+tobj[i].rule+'" value="'+tobj[i].classname+'" '+adds+'>'+tobj[i].display+'</option>';
					go = true;
					
				}
			}	

			if (!go)
				ih += '<option value="">No Custom Classes Are Defined</option>';

			
			
		ih += '</select></td>';
		ih += '</tr>';


	ih += '</table>';
	ih += '</div>';

	ih += '<div style="font-size:1px;height:20px;"></div>';

	ih += '<div class="qmvi-publish-title">Apply To:</div>';
	ih += '<div style="margin-top:10px;">';
	ih += '<table cellpadding=0 cellspacing=0 border=0>';

		ih += '<tr>';
		ih += '<td><input onclick="qmv_custom_class_list_change(event)" id="qmvacc_apply_item" name="qmvacc_apply" type="radio" checked></td>';
		ih += '<td><div style="width:6px;"> </div></td>';
		ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">The Selected Item</td>';
		ih += '</tr>';


		ih += '<tr>';
		ih += '<td><input onclick="qmv_custom_class_list_change(event)" id="qmvacc_apply_container" name="qmvacc_apply" type="radio"></td>';
		ih += '<td><div style="width:6px;"> </div></td>';
		ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">The selected Container</td>';
		ih += '</tr>';

		ih += '<tr>';
		ih += '<td><input onclick="qmv_custom_class_list_change(event)" id="qmvacc_apply_allitems" name="qmvacc_apply" type="radio"></td>';
		ih += '<td><div style="width:6px;"> </div></td>';
		ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">All Menu Items in the Selected Container</td>';
		ih += '</tr>';


		ih += '<tr>';
		ih += '<td><input onclick="qmv_custom_class_list_change(event)" id="qmvacc_apply_alldividers" name="qmvacc_apply" type="radio"></td>';
		ih += '<td><div style="width:6px;"> </div></td>';
		ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">All Divider Items in the Selected Container</td>';
		ih += '</tr>';


		ih += '<tr>';
		ih += '<td><input onclick="qmv_custom_class_list_change(event)" id="qmvacc_apply_alltitles" name="qmvacc_apply" type="radio"></td>';
		ih += '<td><div style="width:6px;"> </div></td>';
		ih += '<td class="qmvi-common qmvi-dialog-input-title" style="color:#222222;text-align:left;">All Title Items in the Selected Container</td>';
		ih += '</tr>';



	ih += '</table>';
	ih += '</div>';


	ih += '</div>';
	
	return ih;


}


function qmv_apply_custom_class(is_delete)
{

	var o_item = document.getElementById("qmvacc_apply_item").checked;
	var o_container = document.getElementById("qmvacc_apply_container").checked;
	var o_allitems = document.getElementById("qmvacc_apply_allitems").checked;
	var o_alldividers = document.getElementById("qmvacc_apply_alldividers").checked;
	var o_alltitles = document.getElementById("qmvacc_apply_alltitles").checked;

	
	var go = false;

	var cls = "";
	var ops = document.getElementById("qmvacc_classes").options;
	for (var k=0;k<ops.length;k++)
	{


		if (ops[k].selected) 
		{
			cls = ops[k].value;
		
			if (o_item)
			{
				if (is_delete)
					qm_arc(cls,qmv.cur_item);
				else
					qm_arc(cls,qmv.cur_item,true);

			}	
			else if (o_container)
			{
				if (is_delete)	
					qm_arc(cls,qmv.cur_item[qp]);
				else
					qm_arc(cls,qmv.cur_item[qp],true);

			}
			else
			{
				var ch = qmv.cur_item[qp].childNodes;
				for (var i=0;i<ch.length;i++)
				{
					if (ch[i] && ch[i].tagName)
					{
						if ((o_allitems && ch[i].tagName=="A") || (o_alldividers && ch[i].tagName=="SPAN" && ch[i].className.indexOf("qmdivider")+1) || (o_alltitles && ch[i].tagName=="SPAN" && ch[i].className.indexOf("qmtitle")+1))
						{
							if (is_delete)	
								qm_arc(cls,ch[i]);
							else
								qm_arc(cls,ch[i],true);
						}
					}
				}	

			}


			go = true;


		}

	}


	if (go)
	{

		qmv_custom_class_list_change(new Object());

		qmv_set_all_subs_to_default_position(true,qmad.br_ie);
		qmv_update_all_addons();
		qmv_position_pointer();	

		return true;
	}
	else
	{

		qmv_show_dialog("alert",null,"First select a custom class name from the list to apply.",400);


	}



}



function qmv_apply_class_action(type)
{


	if (type=="add_new")
	{
		qmv_add_custom_css_rule();
		qmv_custom_rule_parse_rule("div#qm[i] .qmc_mystatic");
	}
	if (type=="edit_rules" || type=="edit_styles")
	{
		var er = document.getElementById("qmvacc_classes");
		var srule = "";

		var ops = er.getElementsByTagName("OPTION");
		for (var i=0;i<ops.length;i++)
		{
			if (ops[i].getAttribute("value")==er.value)
				srule=ops[i].getAttribute("rule");

		}
	

		var n = document.getElementById("qmvtree_custom_rules").getElementsByTagName("DIV");
		for (var i=0;i<n.length;i++)
		{
			
			var rr = n[i].getAttribute("orule");
			
			if (rr)
			{

				rr = rr.replace("qm[i]","qm"+qmv.id);
				rr = rr.replace("qm[i]","qm"+qmv.id);

				if (rr.indexOf(srule)+1)
				{
					if (type=="edit_rules")
					{					
						var or = n[i].getAttribute("orule");
						qmv_show_dialog("custom_rule",n[i],null,null,null,null,null,or)
					}
					else
					{
						qmv_display_setbox(n[i].idiv);
						qmv_hide_dialog();
					}

				}

			}

		}


	}


}


function qmv_track_it(fname,search)
{

	

	if (qmv.is_developer) return;

	
	if (!qmv.track.div)
	{
		var d = document.createElement("DIV");
		d.style.position = "absolute";
		d.style.visibility = "hidden";
		

		d.innerHTML = '<iframe id="qmv_tracking_iframe" src=""></iframe>'
		d = document.body.appendChild(d);
		
		qmv.track.div = d;
	}
	
	if (qmv.is_online) fname += "_online";
	if (window.name=="qm_launch_visual") fname += "_save";

	
	if (search)
		search = "?"+search;
	else
		search = "";
	
	

	var ifr = document.getElementById("qmv_tracking_iframe");
	ifr.src = "http://www.opencube.com/track/"+fname+".html"+search;

	

}


function qmv_log_errors(msg,url,l)
{

	

	if (qmad.br_ie)
		qmv_track_it("error_ie",msg+" -|- "+url+" -|- "+l);
	else
		qmv_track_it("error_firefox",msg+" -|- "+url+" -|- "+l);
	
	
	return false;

}


function qmv_fix_iframe_title_drag()
{

	if (qmv.title_mdown) qmv.title_mdown = false;

}


function qmv_stripe_dialog_init()
{

	

	var first = document.getElementById("qmvi_stripe_where_first");
	var second = document.getElementById("qmvi_stripe_where_second");


	if (qmv.globalstripes_first)
	{
		first.checked = true;
		second.checked = false;
	}
	else
	{
		second.checked = true;
		first.checked = false;
		
	}



}

function qmv_gld_apply_stripes()
{

	var isdel = document.getElementById("qmvi_stripe_where_remove").checked;
	if (!isdel)
	{

		var isfirst = document.getElementById("qmvi_stripe_where_first").checked;
	
		if (isfirst)
			qmv.globalstripes_first = true;
		else
			qmv.globalstripes_first = false;

	
		var a = document.getElementById("qm"+qmv.id);
		qmv_gld_apply_stripes2(a,isfirst);
	
	
		qmv_context_cmd(new Object(),'stripe_styles');
	}
	else
	{
		
		var a = document.getElementById("qm"+qmv.id).getElementsByTagName("A");
		for (var i=0;i<a.length;i++)
			qm_arc("qmstripe",a[i]);


	}


}


function qmv_gld_apply_stripes2(a,isfirst)
{

	var ch=a.childNodes;
	var stripeit;
	if (isfirst) stripeit = true;

	for (var i=0;i<ch.length;i++)
	{
		
		if (ch[i].tagName=="A")
		{
			
			if (!qm_a(ch[i][qp]))
			{	
				
				if (stripeit)
				{
					
					qm_arc("qmstripe",ch[i],true);	
					stripeit = false;
				}
				else
				{
					qm_arc("qmstripe",ch[i]);	
					stripeit = true;
				}
			}


			if (ch[i].cdiv)
				new qmv_gld_apply_stripes2(ch[i].cdiv,isfirst);
	
		}
	}

}


function qmv_update_all_main_checks()
{

	

	var a = document.getElementById("qm98");

	var mobj = document.getElementById("qm"+qmv.id);
	var sobj = mobj.getElementsByTagName("DIV")[0];
	var oc = qmv_find_update_tree_value("settings","create","onclick",null,true);
	oc = qmv_lib_parse_value(oc,"bool");					


	var d = a.getElementsByTagName("DIV");
	for (var i=0;i<d.length;i++)
	{


		if (d[i].getAttribute("ischecks"))
		{


			var ch = d[i].childNodes;
			for (var j=0;j<ch.length;j++)
			{


				var ct;
				var spec;
				if (ch[j].tagName=="A" && (ct = ch[j].getAttribute("ctype")))
				{

					var s = ch[j].getElementsByTagName("SPAN");
					for (var k=0;k<s.length;k++)
					{

						if (s[k].ischeck)
							s[k].parentNode.removeChild(s[k]);

					}


					spec = ch[j].getAttribute("ccat");

					if (ct=="addon")
					{
						
						
						if (qmv.addons[spec]["on"+qmv.id])
							qmv_set_menu_checkmark_span(ch[j]);
							
															

					}
					else if (ct=="settings")
					{
						


						if (spec=="main_horizontal")
						{

							if (mobj.ch)
								qmv_set_menu_checkmark_span(ch[j]);

						}
						else if (spec=="main_vertical")
						{
							if (!mobj.ch)
								qmv_set_menu_checkmark_span(ch[j]);

						}
						else if (spec=="sub_horizontal")
						{
							if (sobj && sobj.ch)
								qmv_set_menu_checkmark_span(ch[j]);


						}
						else if (spec=="sub_vertical")
						{
							if (sobj && !sobj.ch)
								qmv_set_menu_checkmark_span(ch[j]);


						}
						else if (spec=="onclick")
						{
							if (oc)
								qmv_set_menu_checkmark_span(ch[j]);


						}
						else if (spec=="onmouseover")
						{
							if (!oc)
								qmv_set_menu_checkmark_span(ch[j]);


						}	

					}
					else if (ct=="view")
					{
												

						if (spec=="inpage")
						{
							if (!qmv.interface_full)
								qmv_set_menu_checkmark_span(ch[j]);

						}
						else if (spec=="full")
						{	
							if (qmv.interface_full)
								qmv_set_menu_checkmark_span(ch[j]);
						}

					}
					
					

				}


			}



		}


	}

}





function qmv_set_menu_checkmark_span(a)
{

	var mc = document.createElement("SPAN");
	mc.ischeck = 1;
	mc.style.position = "relative";
	mc.style.display = "block";
	mc.style.fontSize = "1px";
	mc.style.width="0px";
	mc.style.height = "0px";
	mc.style.backgroundColor = "#f00";
	mc.innerHTML = '<img src="'+qmv.base+'images/check_red.gif" width="11" height="9" style="position:absolute;border-width:0px;left:-13px;top:2px;">';


	a.insertBefore(mc,a.firstChild);

}



function qmvi_help_navigate(type)
{

	
	var wh = window.frames["qmvi_help_iframe_window"+qmv.helpnum].history;

			
	try
	{
		if (type=='back')
		{
			if (wh.length)
				wh.back();
				
		}
		else if (type=='forward')
		{
		
			if (wh.length)
				wh.forward();	
				
		}
		else if (type=='home')
		{

			var ifr = document.getElementById("qmvi_help_iframe"+qmv.helpnum);
			ifr.src = qmv.base+"help/index.html";

		}
	}
	catch(e)
	{


	}

	
}



function qmv_style_settings_help(e,a)
{
	e = e || event;

	var pa = a[qp]
	while (pa.tagName!="A")
		pa = pa[qp];

	var sn = pa.getAttribute("cname");

	
	qmv_show_dialog("help",null,"help-style_settings.html?"+sn);

	
}









