<html>
<head>

<title>Sistema Administrativo Integrado - SAI-OCHINA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- --------------------------script para el historial de actualizaciones------------------- -->
<script language="javascript" type="text/javascript">
function reducir()
   {
      alt = document.getElementById('popup').style.height;
		alt = alt.replace('px','');
		if(alt==0){
			clearInterval(t_red);
			document.getElementById('popup').style.display='none';
		}
		if(alt>0){
			top = document.getElementById('popup').style.marginTop;
			top = top.replace('px',''); 
			top = parseInt(top)+10;
			top = top+"px";
			alt = parseInt(alt) - 10;
			alt = alt+"px";
			document.getElementById('popup').style.height=alt;
			document.getElementById('popup').style.marginTop=top;
		}
		document.getElementById('msj_popup').src="imagenes/iconos/chat32.png";	
	}
function cerrarPopup(){
		t_red = setInterval('reducir()',40);
	}
function aumentar(){
		alt = document.getElementById('popup').style.height;
		alt = alt.replace('px','');
		if(alt==100){
			clearInterval(t_aum);
		}
		if(alt<100){
			top = document.getElementById('popup').style.marginTop;
			top = top.replace('px','');
			top = parseInt(top)-10;
			top = top+"px";
			document.getElementById('popup').style.display='';
			alt = parseInt(alt) + 10;
			alt = alt+"px";
			document.getElementById('popup').style.height=alt;
			document.getElementById('popup').style.marginTop=top;
		}
		document.getElementById('msj_popup').src="imagenes/iconos/chat321.png";
	}
function abrir_popup(){
	t_aum = setInterval('aumentar()',40);
	//document.getElementById('popup').style.visibility = 'visible';
}
</script>  
<!-- ------------------------------------------------------------------------------------ -->
<!-- LIBRERIA jQajax : Libreria General JQuery -->
<!-- COMENTARIO: Implementada a la fecha 11.12.2008						 -->
<script type="text/javascript" src="utilidades/jqGrid/jquery.js"></script>
<!-- ------------------------------------------------------------------------------------ -->
<script type="text/javascript" src="utilidades/pestanasjs/jquery-1.1.3.1.pack.js"></script>
<script type="text/javascript" src="utilidades/pestanasjs/jquery.tabs.pack.js"></script>
<script type="text/javascript" src="utilidades/pestanasjs/jquery.history_remote.pack.js"></script>

<link rel="stylesheet" href="css/jquery.tabs1.css" type="text/css" media="print, projection, screen">
<!-- ------------------------------------------------------------------------------------------------>

<!-- ------------------------------------------------------------------------------------ -->
<!-- LIBRERIA jQajax : Esta libreria se utilizara para los envios por Ajax -->
<!-- COMENTARIO: Implementada a la fecha 11.12.2008						 -->
<script type="text/javascript" src="utilidades/jQajax/jquery.ajaxq-0.0.1.js"></script>
<!-- ------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------ -->
<!-- LIBRERIA jVal ,alphanumeric: Estas librerias se utilizara para la validacion de los campos de formularios-->
<!-- COMENTARIO: Implementada a la fecha 14.01.2009 actualmente en popup						 -->
<script type="text/javascript" src="utilidades/jVal.0.1.3/jVal.js"></script>
<script type="text/javascript" src="utilidades/jVal.0.1.3/jquery.corner.js"></script>
<link rel="stylesheet" type="text/css" href="utilidades/jVal.0.1.3/jVal.css">

<script type="text/javascript" src="utilidades/alphanumeric/jquery.alphanumeric.js"></script>
<!-- ------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------ -->
<!-- LIBRERIA messages : Esta libreria se utilizara para la visualizacion de ayudas en los campos -->
<!-- COMENTARIO: Implementada a la fecha 11.12.2008						 -->
<script src="utilidades/messages/messages.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="utilidades/messages/messages.css">
<!-- ------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------ -->
<!-- LIBRERIA jQajax : Esta libreria se utilizaran para la generacion de ventanas emergentes-->
<!-- COMENTARIO: Implementada a la fecha 11.12.2008						 -->
<script src="utilidades/boxy-0.1.4/src/javascripts/jquery.boxy.js" type="text/javascript"></script>
<link rel="stylesheet" href="utilidades/boxy-0.1.4/src/stylesheets/boxy.css" type="text/css" />
<!-- ------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------ -->
<!-- LIBRERIA jqGrid : Esta libreria se utilizaran para la generacion de los grids-->
<!-- COMENTARIO: Implementada a la fecha 11.12.2008						 -->
<script src="utilidades/jqgrid_demo/js/jquery.jqGrid.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="utilidades/jqgrid_demo/themes/basic/grid.css" />
<!-- ------------------------------------------------------------------------------------ -->


<link rel="stylesheet" type="text/css" media="all" href="css/menu_arbol.css" />
<script type="text/javascript" src="js/constructor_menu_arbol_principal/qm.js"></script>
<script type="text/javascript" src="js/constructor_menu_arbol_principal/qm_pure_css.js"></script>

<script type="text/javascript" src="utilidades/selectboxes/jquery.selectboxes.js"></script>


<!-- Add-On Core Code (Remove when not using any add-on's) -->
<style type="text/css">.qmfv{visibility:visible !important;}.qmfh{visibility:hidden !important;}</style>
<script type="text/JavaScript">
var qmad = new Object();
qmad.bvis="";
qmad.bhide="";
qmad.bhover="";
</script>


	<!-- Add-On Settings -->
	<script type="text/JavaScript">

		/*******  Menu 0 Add-On Settings *******/
		var a = qmad.qm0 = new Object();

		// Item Bullets (CSS - Imageless) Add On
		a.ibcss_apply_to = "parent";
		a.ibcss_main_type = "arrow";
		a.ibcss_main_direction = "right";
		a.ibcss_main_size = 5;
		a.ibcss_main_bg_color = "#bbbbbb";
		a.ibcss_main_bg_color_hover = "#bbbbbb";
		a.ibcss_main_bg_color_active = "#bbbbbb";
		a.ibcss_main_border_color_active = "#dd3300";
		a.ibcss_main_position_x = -9;
		a.ibcss_main_position_y = -3;
		a.ibcss_main_align_x = "left";
		a.ibcss_main_align_y = "middle";
		a.ibcss_sub_type = "arrow-v";
		a.ibcss_sub_direction = "right";
		a.ibcss_sub_size = 3;
		a.ibcss_sub_bg_color = "";
		a.ibcss_sub_bg_color_active = "";
		a.ibcss_sub_border_color = "#797979";
		a.ibcss_sub_border_color_hover = "#222222";
		a.ibcss_sub_border_color_active = "#000000";
		a.ibcss_sub_position_x = -8;
		a.ibcss_sub_position_y = -1;
		a.ibcss_sub_align_x = "left";
		a.ibcss_sub_align_y = "middle";

		// Tree Menu Add On
		a.tree_width = 220;
		a.tree_sub_sub_indent = 12;
		a.tree_hide_focus_box = true;
		a.tree_auto_collapse = true;
		a.tree_expand_animation = 2;
		a.tree_expand_step_size = 8;
		a.tree_collapse_animation = 3;
		a.tree_collapse_step_size = 15;

	</script>

<script type="text/javascript" src="js/constructor_menu_arbol_principal/qm_item_bullets_css.js"></script>
<script type="text/javascript" src="js/constructor_menu_arbol_principal/qm_tree_menu.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="css/tabs.css" />
<link rel="stylesheet" type="text/css" href="css/formularios.css">

<script src="js/tabs/jquery.dimensions.js" type="text/javascript"></script>
<script src="js/tabs/jquery.splitter.js" type="text/javascript"></script>
<script src="js/tabs/jquery.jqDynTabs.js" type="text/javascript"></script>

<script src="js/index.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/index.css">


</head>
<body>

<div id="td_top"></div>
<div id="Splitter">
		<div id="menu_principal" class="scroll-pane-menu"></div>
		<div id="RightPane">
			<div id="tab" style="width:75%">
			<table class="tabHolder" cellspacing="0" cellpadding="0"  onselectstart="return false;">
				<tr id="mainTabArea">
					<td style="font-size:1px;border-bottom:3px solid #83B4D8; width:100% "  align="right">&nbsp; </td>
				</tr>
			</table> 
			</div>
			<div id="base_scroll"></div>
			<!-- Tabs pane -->
			<div id="mainPanelArea" class="tabPanel" ></div>
			</div>

	<div id="bottom" class="scroll-pane"></div>
</div>
<div id="td_bottom">
</div>
<!-- div para el icono de historial de actualizaciones -->
<div id="icono_popup">
		<img id="msj_popup" src="imagenes/iconos/chat321.png" width="24" height="20" style="margin-left:95%; cursor:pointer" border="0" onClick="abrir_popup();" />
</div>
<!-- div para el historial de actualizacion -->
<div id="popup" style="height:100px; margin-top: -130px; display:none">
	<!-- div de titulo para el historial de actualizaciones -->
   	<div id="popup_titulo" align="center"> Historial de Actualizaciones<img src="imagenes/tab_close-on.gif" width="16" height="16" border="0" style="padding-left:25px; cursor:pointer;" onClick="cerrarPopup();" />
    </div>
    <!-- div contenido del historial de actualizaciones -->
   <div id="popup_contenido">
   		<p align="center" style=" color:#36F; font-weight:bold">&nbsp; Se ha efectuado una actualización en el Modulo...<br>
        <a href="#" style="color:#03F;" onClick="openTab('Historial Actualización','historial.php');">Ir al sitio</a></p>
   </div>
</div>

</body>
</html>