var idStatusBar;
var maintab;
var maxTabs=100;
var TimemsgStatusBar=3000;

//------------------------------------------------------------------------------------------------------------------------------
//						MENSAJES DE FINALIZACION DE PROCESOS
var mensaje = new Array();

var registro_exitoso=0;
var actualizacion_exitosa=1;
var eliminacion_exitosa=2;
var esperando_respuesta=3;
var  registro_existe=4;
var  relacion_existe=5;
var  esperando_respuesta=6;
var  la_transacion_no_se_efectuo=7;
var  monto_cedente_superior=8;
var  fecha_impuesto=9;
var  convertirda=10;
var  impresion_cheque=11;
var  inactiva=12;
var  inactiva_total=13;
var  no_impresion=14;
var  chequera_agotada=15;
var  chequera_existe=16;
var  operacion_exitosa=17;
var  firma_existe=18;
var  error=19;
var existe_nomina=20;
var cerrar=21;
var no_cheque=22;
mensaje[0]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png' />LA OPERACION SE REGISTRO CON EXITO</p></div>";
mensaje[1]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png' />EL REGISTRO SE ACTUALIZO CON EXITO</p></div>";
mensaje[2]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png' />EL REGISTRO SE ELIMINO CON EXITO</p></div>";
mensaje[3]="<img align='absmiddle' src='imagenes/loading.gif' /> ESPERANDO RESPUESTA DEL SERVIDOR...";
mensaje[4]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL REGISTRO YA EXISTE...</p></div>"
mensaje[5]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png' />EL REGISTRO MANTIENE RELACIONES ACTIVAS OTRAS TABLAS Y NO PUEDE SER ELIMINADO...</p></div>"
mensaje[6]="<img align='absmiddle' src='imagenes/loading.gif' />Esperando Respuesta del Servidor...";
mensaje[7]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE EFECTUO LA OPERACIÓN</p></div>"
mensaje[8]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL MONTO CEDENTE SUPERA AL MONTO DE LA PARTIDA</p></div>"
mensaje[9]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />LA FECHA DEL IMPUESTO NO PUEDE SER MAYOR A LA FECHA ACTUAL</p></div>"
mensaje[10]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />LA REQUISICION  YA FUE COVERTIDA EN UNA SOLICITUD DE COTIZACION</p></div>"
mensaje[11]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />PREPARANDO VISTA DE IMPRSION</p></div>"
mensaje[12]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />SE ACTIVO LA SIGUIENTE CHEQUERA</p></div>"
mensaje[13]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />SE INACTIVO LA CHEQUERA ACTUAL, NO HAY MAS CHEQUERAS CARGADAS A ESTE BANCO</p></div>"
mensaje[14]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE ENCUENTRAN CHEQUERAS ACTIVAS PARA ESTA CUENTA,PARA EMITIR PARA EMITIR UN CHEQUE POR MEDIO DE LA MISMA EBE CREAR UNA CHEQUERA NUEVA</p></div>"
mensaje[15]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />CHEQUERA AGOTADA</p></div>"
mensaje[16]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />YA EXISTE UNA CHEQUERA ACTIVA ASIGNADA A ESTA CUENTA, EL REGISTRO SE GUARDÓ COMO INACTIVO</p></div>"
mensaje[17]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png' />OPERACIÓN REALIZADA CON EXITO</p></div>"
mensaje[18]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />YA EXISTE UNA FIRMA COMO ACTIVA . EL REGISTRO SE GUARDO COMO INACTIVO</p></div>"
mensaje[19]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/error.png' />ERROR AL GUARDAR! VERIFIQUE QUE TODOS LOS CAMPOS REQUERIDOS ESTEN LLENOS</p></div>";
mensaje[20]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />YA EXISTE UN REGISTRO DE ESTE TIPO DE NOMINA PARA ESTE AÑO...</p></div>"
mensaje[21]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_locked_good.png' />SE HA CERRADO EL PROCESO CON EXITO</p></div>"
mensaje[22]="<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ESTE CHEQUE NO ESTA PAGADO...</p></div>"
//------------------------------------------------------------------------------------------------------------------------------


// Firefox doesn't fire resize on page elements
function init()
{
	qm_create(0,false,0,500,true,false,false,false);	
	qm_tree_init();
	qm_ibcss_init();
	$(".lnk").bind('click',
										function(e) {

												if (!maintab.tabExists(this.title) ) {
													if( maintab.getTabIndex() >= maxTabs) // maximum 5 tabs open 
														maintab.TabCloseEl(0);
														maintab.CreateTab(this.title,true,this.id)
												}
										}                    
	);		
}

jQuery(document).ready(

function()
{
	jQuery("#Splitter").splitter({
		type: 'v',
    	initA: 250, 
		maxA: 350,
		minA:30, 
		accessKey: '|'
	});

	$('#menu_principal').load('menu.php', '');

	jQuery(window).bind("resize", function(){
		var $ms = $("#Splitter");
		var top = $ms.offset().top;		// from dimensions.js
		var wh = $(window).height()-25;
		// Account for margin or border on the splitter container
		var mrg = parseInt($ms.css("marginBottom")) || 0;
		var brd = parseInt($ms.css("borderBottomWidth")) || 0;
		$ms.css("height", (wh-top-mrg-brd-3)+"px");
		// IE fires resize for splitter; others don't so do it here
		if ( !jQuery.browser.msie )
			$ms.trigger("resize");
	}).trigger("resize");	

	
	maintab = $("#RightPane").jqDynTabs({tabcontrol:$("#mainTabArea"), tabcontent :$("#mainPanelArea"), position:"top"});				
});

function closeAllTabs()
{
	for (i=0; i<maxTabs;i++)
		maintab.TabCloseEl(0);
}
function closeTab(title)
{
	if (!maintab.tabExists(title) ) {
			maintab.TabCloseEl(0);
			
	}	
}

function openTab(title,pagina)
{
	if (!maintab.tabExists(title) ) {
		if( maintab.getTabIndex() >= maxTabs) // maximum 5 tabs open 
			maintab.TabCloseEl(0);
			maintab.CreateTab(title,true,pagina)
	}	
}
//------------------------------------------------------------------------------------------------------------------------------
//						FUNCION PARA VER LAS PROPEDADES DE UN OBJETO
function verProps(id_objeto)
{
	var indice;
	var objeto		=	document.getElementById(id_objeto);
	var ventana		=	window.open("","nvent");
	for ( indice in objeto)	ventana.document.write(indice+" es "+ document.getElementById(id_objeto)[indice]+"<br>");
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//					FUNCION PARA OBTENER LOS CAMPOS&DATOS DE UN FORMULARIO
function dataForm(nombre_form)
{
	var NumCampos = getObj(nombre_form).length;
	var i = 0;
	var post_to_get=''; 
	while (i <= (NumCampos-1))
	{
		if (i != 0)
		{
			post_to_get += "&";
		}
		if (getObj(nombre_form).elements[i].tagName=='SELECT' && getObj(nombre_form).elements[i].multiple)
		{
			post_to_get += getObj(nombre_form).elements[i].name + "=";
			post_to_get += getList(getObj(nombre_form).elements[i].name);
		}
		else if (getObj(nombre_form).elements[i].type=='checkbox')
		{
			post_to_get += getObj(nombre_form).elements[i].name + "=";
			post_to_get += getObj(nombre_form).elements[i].checked;
		}		
		else
		{
			if(!getObj(nombre_form).elements[i].disabled){
				post_to_get += getObj(nombre_form).elements[i].name + "=";
				post_to_get += getObj(nombre_form).elements[i].value.replace("&",'¶'); 				
			}
		}
		i++; 
	}
	return post_to_get;
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//					FUNCION PARA LIMPIAR LOS CAMPOS&DATOS DE UN FORMULARIO
function clearForm(nombre_form)
{
	var NumCampos = getObj(nombre_form).length;
	var i = 0;
	while (i <= (NumCampos-1))
	{
		if (getObj(nombre_form).elements[i].tagName=='SELECT' && getObj(nombre_form).elements[i].multiple)
		{
			getObj(nombre_form).elements[i].options[0].selected;
		}
		else
		{
			getObj(nombre_form).elements[i].value=''; 
		}
		i++; 
	}
	getObj(nombre_form).elements[0].focus();
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//		Funcion para tomar la referencia a un objeto, tomando en cuenta el explorador utilizado
function getObj(objeto)
{
	//si puedo tomar el objeto por getElementById, entonces es IE5.0 o superior
	if (document.getElementById)
	{
		var referencia_objeto=document.getElementById(objeto)
	}
	//si puedo tomar el objeto por ALL, entonces es IE4.0 o inferior
	else if (document.all)
	{
		var referencia_objeto=document.all[objeto]	
	}
	//si puedo tomar el objeto por LAYERS, entonces es NETSCAPE o MOZILLA
	else if (document.layers)
	{
		var referencia_objeto=document.layers(objeto)
	}
	//si no puedo tomar el objeto, muestro un mensaje de error
	else
	{
		alert("Tu explorador no soporta algunas de las funciones en JavaScript")
		return false
	}
	//devuelve la referencia al objeto
	//if (!referencia_objeto) alert("JAVASCRIPT.FUNCIONES_BASICAS.JS.TOMAR_OBJ: No se puede obtener referencia al objeto de nombre \""+objeto+"\"");
	return referencia_objeto
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//					Obtener el valor de un combo
function getSelect(id_objeto)
{
	return getObj(id_objeto).options[getObj(id_objeto).selectedIndex].value;	
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//		Funcion para copiar los valores selecionados de un select en otro
function copyItemList(listOrigen,listDestino, All)
{
	for(var i=0;i<getObj(listOrigen).options.length;i++) 
	{		
		if (getObj(listOrigen).options[i].selected || All) 
		{
			getObj(listDestino).options[getObj(listDestino).length]=new Option(getObj(listOrigen).options[i].text,  getObj(listOrigen).options[i].value);
			getObj(listOrigen).options[i]=null;	
			i=-1;			
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//		Funcion para obtener los valores de los combos y las listas
function getList(objSelect, onlySelect)
{
	var cadArray='';
	var spl='';
	var j=0;
	for(var i=0;i<getObj(objSelect).options.length;i++) 
	{		
		if (onlySelect)
		{
			if (j!=0) spl=',';			
			if (getObj(objSelect).options[i].selected) 
			{
				cadArray=cadArray+spl+getObj(objSelect).options[i].value;
				j++;
			}
		}
		else
		{
			if (i!=0) spl=',';
			cadArray=cadArray+spl+getObj(objSelect).options[i].value;
		}
	}
	return cadArray;
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//		Funcion para tomar la referencia a un objeto, tomando en cuenta el explorador utilizado
function setBarraEstado(html,autohide,modal,callback)
{
	//alert(html+"##"+autohide+"##"+modal+"##"+callback);
	if (modal)
	{
		var html; var callback;
		dialog=new Boxy.alert(html, callback, { title: "SAI-OCHINA",modal: true,center:true });
		clearBarraEstado();
	}
	else
	{
		getObj('td_bottom').innerHTML=html;
		clearTimeout(idStatusBar);
		if (autohide) idStatusBar=setTimeout(clearBarraEstado,TimemsgStatusBar);
	}
}
//------------------------------------------------------------------------------------------------------------------------------
//		Funcion para tomar la referencia a un objeto, tomando en cuenta el explorador utilizado
function clearBarraEstado()
{
	getObj('td_bottom').innerHTML='';
}
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
//		Funcion para tomar la referencia a un objeto, tomando en cuenta el explorador utilizado
function setArea(html)
{
	getObj('mainPanelArea').innerHTML=html;
}
//------------------------------------------------------------------------------------------------------------------------------
String.prototype.float =function() {
    var n=this;
	n = n.toString().replace(/\$|\./g,'');
	n = n.toString().replace(/\$|\,/g,'.');
	return parseFloat(n);
}
/*-------------------   Fin SUMA  ---------------------------*/
Number.prototype.currency = function(c, d, t){
	var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
String.prototype.DDMMAAAA = function() {
	return this.replace(/^(\d{2})\/(\d{2})\/(\d{4})$/, "$2/$1/$3");
}
String.prototype.MMDDAAAA = function() {
	return this.replace(/^(\d{2})\/(\d{2})\/(\d{4})$/, "$2/$1/$3");
}
