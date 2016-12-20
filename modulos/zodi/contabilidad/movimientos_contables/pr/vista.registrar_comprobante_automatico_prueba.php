<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

?>
<script type='text/javascript'>
var dialog;

$("#contabilidad_comprobante_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Comprobantes', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes.php',
								datatype: "json",
								colNames:['Id','Organismo','Ano','Mes','tipo','Comprobante','Secuencia','Comentarios','Cuenta Contable','Desc','REF','Debito','Credito','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','codigo_tipo_comp','id_cc','estatus'],
								colModel:[
										{name:'id',index:'id', width:20,hidden:true},
										{name:'codigo_organismo',index:'codigo_organismo', width:20,hidden:true,hidden:true},
										{name:'ano_comprobante',index:'ano_comprobante', width:20,hidden:true,hidden:true},
										{name:'mes_comprobante',index:'mes_comprobante', width:20,hidden:true,hidden:true},
										{name:'id_tipo_comprobante',index:'id_tipo_comprobante', width:20,hidden:true},
										{name:'numero_comprobante',index:'numero_comprobante', width:20},
										{name:'secuencia',index:'secuencia', width:20,hidden:true,hidden:true},
										{name:'comentarios',index:'comentarios', width:20,hidden:true,hidden:true},
										{name:'cuenta_contable',index:'cuenta_contable',width:70,hidden:true},
										{name:'descripcion',index:'descripcion',width:70},
										{name:'ref',index:'ref',width:20,hidden:true},
										{name:'monto_debito',index:'monto_debito',width:50},
										{name:'monto_credito',index:'monto_credito',width:50},
										{name:'fecha_comprobante',index:'fecha_comprobante',width:50,hidden:true},
										{name:'codigo_auxiliar',index:'codigo_auxiliar',width:50,hidden:true},
										{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora',width:50,hidden:true},
										{name:'codigo_proyecto',index:'codigo_proyecto',width:50,hidden:true,hidden:true},
										{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:50,hidden:true},
										{name:'codigo_tipo_cp',index:'codigo_tipo_cp',width:30,hidden:true},		
										{name:'id_cc',index:'id_cc',width:50,hidden:true,hidden:true},
										{name:'estatus',index:'estatus',width:30,hidden:true}										
							
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								if(ret.requiere_auxiliar=='t')
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(ret.requiere_proyecto=='t')
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(ret.requiere_unidad_ejecutora=='t')
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
			 	getObj('contabilidad_comp_id_comprobante').value =ret.id;
				getObj('contabilidad_comp_pr_numero_comprobante').value =ret.numero_comprobante;
				getObj('contabilidad_comp_pr_cuenta_contable').value=ret.cuenta_contable;
				getObj('contabilidad_comp_pr_ref').value=ret.ref;
				getObj('contabilidad_comp_pr_auxiliar').value=ret.codigo_auxiliar;
			 	getObj('contabilidad_comp_pr_ubicacion').value=ret.codigo_unidad_ejecutora;
				getObj('contabilidad_comp_pr_centro_costo').value=ret.codigo_proyecto;
				getObj('contabilidad_comp_pr_utf').value=ret.codigo_utilizacion_fondos;
				getObj('contabilidad_auxiliares_db_id_cuenta').value=ret.id_cc;
				getObj('contabilidad_comp_pr_total_debe').value=ret.monto_debito;
				getObj('contabilidad_comp_pr_total_haber').value=ret.monto_credito;
				getObj('contabilidad_comp_pr_desc').value=ret.descripcion;
				//
			//	alert(ret.estatus);
				if(ret.estatus==1)
				{
					getObj('contabilidad_comp_pr_estatus').value="Cerrado";
					getObj('contabilidad_comp_pr_estatus_oc').value='1';
					getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
					getObj('contabilidad_movimientos_pr_btn_cerrar').style.display='none';
				}
				if(ret.estatus==0)
				{
						getObj('contabilidad_comp_pr_estatus').value="Abierto";
						getObj('contabilidad_comp_pr_estatus_oc').value='0';
						if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
						{
							if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
							{	
								
								getObj('contabilidad_movimientos_pr_btn_cerrar').style.display='';
								getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';

							}
							else
							{
								alert("entro");
								alert(getObj('contabilidad_comp_pr_total_debe').value);
								alert(getObj('contabilidad_comp_pr_total_haber').value);
							}
						}
				}
				if(ret.monto_debito!="0,00")
				{
					debito_credito=1;
					getObj('contabilidad_comp_pr_monto').value=ret.monto_debito;
				}else
				if(ret.monto_credito!="0,00")
				{
					debito_credito=2;
					getObj('contabilidad_comp_pr_monto').value=ret.monto_credito;
				}	
				getObj('contabilidad_comp_pr_debe_haber').value=debito_credito;

				///// activando condiciones de campos ocultos
				bloquear();
			if(getObj('contabilidad_comp_pr_auxiliar').value!=0)
			{
				getObj('contabilidad_comp_pr_activo').value=1;
			}
			if(getObj('contabilidad_comp_pr_ubicacion').value!=0)
			{
				getObj('contabilidad_comp_pr_activo2').value=1;
			}
			if(getObj('contabilidad_comp_pr_utf').value!=0)
			{
				getObj('contabilidad_comp_pr_activo3').value=1;
			}
			//alert(ret.codigo_tipo_comp);
			getObj('contabilidad_comp_pr_tipo').value=ret.codigo_tipo_comp;
			getObj('contabilidad_comp_pr_tipo_id').value=ret.id_tipo_comprobante;
				//////////////////////////////////////////////
				getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
				getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='';
				getObj('contabilidad_movimientos_pr_btn_guardar').style.display='none';
				jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,page:1}).trigger("reloadGrid");
				url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,
								dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#contabilidad_movimientos_pr_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_contabilidad_pr_movimientos').jVal())
	{desbloquear();
		$.ajax (
		{
			url: "modulos/contabilidad/movimientos_contables/pr/sql.actualizar.php",
			data:dataForm('form_contabilidad_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar_comp();
					
				    /*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="NoActualizo")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
					limpiar_comp();
					/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="numero_existe")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NÚMERO DE COMPROBANTE YA UTILIZADO</p></div>",true,true);
					/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#contabilidad_vista_btn_consultar_cuenta").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Cuenta', 'Denominacion','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'requiere_auxiliar',index:'requiere_auxiliar', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_proyecto',index:'requiere_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_unidad_ejecutora',index:'requiere_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],


								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//alert(ret.requiere_auxiliar);
									if(ret.requiere_auxiliar=='t')
									{
										getObj('contabilidad_comprobante_pr_activo').value=1;
									}
									if(ret.requiere_proyecto=='t')
									{
										getObj('contabilidad_comprobante_pr_activo2').value=1;
									}
									if(ret.requiere_unidad_ejecutora=='t')
									{
										getObj('contabilidad_comprobante_pr_activo3').value=1;
									}
									$('#contabilidad_comprobante_pr_cuenta_contable').val(ret.cuenta_contable);
									$('#contabilidad_auxiliares_db_id_cuenta').val(ret.id);
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#contabilidad_vista_btn_consultar_auxiliar_cmp").click(function() {
if(getObj('contabilidad_comprobante_pr_activo').value==1)
{	
				var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
									function(data)
									{								
											dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
											setTimeout(crear_grid,100);
									});
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?cuenta='+getObj('contabilidad_auxiliares_db_id_cuenta').value,							
											datatype: "json",
											colNames:['id','codigo','Denominacion'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
												{name:'denominacion',index:'denominacion', width:50,sortable:false,resizable:false},

													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#contabilidad_comprobante_pr_auxiliar').val(ret.cuenta_contable);
												$('#contabilidad_comprobante_contabilidad_id').val(ret.id);
												dialog.hideAndUnload();
											},
											loadComplete:function (id){
												setBarraEstado("");
												dialog.center();
												dialog.show();
											},
											loadError:function(xhr,st,err){ 
												setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
											},															
											sortname: 'cuenta_contable',
											viewrecords: true,
											sortorder: "asc"
										});
									}
}
});
$("#contabilidad_vista_btn_consultar_ubicacion_cmp").click(function() {
if(getObj('contabilidad_comprobante_pr_activo2').value==1)
{	
				var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
									function(data)
									{								
											dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
											setTimeout(crear_grid,100);
									});
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php',							
											datatype: "json",
											colNames:['id','codigo','Unidad'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
												{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#contabilidad_comprobante_pr_ubicacion').val(ret.codigo);
												$('#contabilidad_comprobante_pr_ejec_id').val(ret.id);
												dialog.hideAndUnload();
											},
											loadComplete:function (id){
												setBarraEstado("");
												dialog.center();
												dialog.show();
											},
											loadError:function(xhr,st,err){ 
												setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
											},															
											sortname: 'cuenta_contable',
											viewrecords: true,
											sortorder: "asc"
										});
									}
}
});
$("#contabilidad_vista_btn_consultar_utf").click(function() {
if(getObj('contabilidad_comprobante_pr_activo3').value==1)
{	
	
					var nd=new Date().getTime();
					setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
					$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
										function(data)
										{								
												dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
												setTimeout(crear_grid,100);
										});
										function crear_grid()
										{
											jQuery("#list_grid_"+nd).jqGrid
											({
												width:800,
												height:300,
												recordtext:"Registro(s)",
												loadtext: "Recuperando Información del Servidor",		
												url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php',							
												datatype: "json",
												colNames:['id','codigo','Unidad'],
												colModel:[
													{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
													{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
													{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
														],
												pager: $('#pager_grid_'+nd),
												rowNum:20,
												rowList:[20,50,100],
												imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
												onSelectRow: function(id){
												var ret = jQuery("#list_grid_"+nd).getRowData(id);
													$('#contabilidad_comprobante_pr_utf').val(ret.codigo);
													$('#contabilidad_comprobante_pr_utf_id').val(ret.id);
													dialog.hideAndUnload();
												},
												loadComplete:function (id){
													setBarraEstado("");
													dialog.center();
													dialog.show();
												},
												loadError:function(xhr,st,err){ 
													setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
												},															
												sortname: 'cuenta_contable',
												viewrecords: true,
												sortorder: "asc"
											});
										}
}
});
function limpiar_comp()
{
	setBarraEstado("");
	////getObj('').style.display='';
	//getObj('tesoreria_moneda_db_btn_eliminar').style.display='none';
	getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
	//getObj('contabilidad_auxiliares_db_btn_consultar').style.display='';
	clearForm('form_contabilidad_pr_movimientos');
	getObj('contabilidad_comprobante_pr_activo').value=0;
	getObj('contabilidad_comprobante_pr_activo2').value=0;
	getObj('contabilidad_comprobante_pr_activo3').value=0;
	getObj('contabilidad_comprobante_pr_monto').value="0,00";
	desbloquear();

	jQuery("#list_comprobante_auto").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables.php',page:1}).trigger("reloadGrid");
	url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables.php';

}
$("#contabilidad_auxiliares_db_btn_cancelar").click(function() {
limpiar_comp();
	
});
//
function validar_debe_haber()
{
	if((getObj('contabilidad_comprobante_pr_debe_haber').value!='1')&&(getObj('contabilidad_comprobante_pr_debe_haber').value!='2'))
	{
			getObj('contabilidad_comprobante_pr_debe_haber').value="";
	}
}
/* ------------------ Sub-Mascara jquery_moneda   ---------------------------*/
documentall = document.all;


function formatamoney(c) {
    var t = this; if(c == undefined) c = 2;		
    var p, d = (t=t.split("."))[1].substr(0, c);
    for(p = (t=t[0]).length; (p-=3) >= 1;) {
	        t = t.substr(0,p) + "." + t.substr(p);
    }
    return t+","+d+Array(c+1-d.length).join(0);
}

String.prototype.formatCurrency=formatamoney

function demaskvalue(valor, currency){

var val2 = '';
var strCheck = '0123456789';
var len = valor.length;
	if (len== 0){
		return 0.00;
	}

	if (currency ==true){	

		
		for(var i = 0; i < len; i++)
			if ((valor.charAt(i) != '0') && (valor.charAt(i) != ',')) break;
		
		for(; i < len; i++){
			if (strCheck.indexOf(valor.charAt(i))!=-1) val2+= valor.charAt(i);
		}

		if(val2.length==0) return "0.00";
		if (val2.length==1)return "0.0" + val2;
		if (val2.length==2)return "0." + val2;
		
		var parte1 = val2.substring(0,val2.length-2);
		var parte2 = val2.substring(val2.length-2);
		var returnvalue = parte1 + "." + parte2;
		return returnvalue;
		
	}
	else{
			val3 ="";
			for(var k=0; k < len; k++){
				if (strCheck.indexOf(valor.charAt(k))!=-1) val3+= valor.charAt(k);
			}			
	return val3;
	}
}

function reais(obj,event){

var whichCode = (window.Event) ? event.which : event.keyCode;

if (whichCode == 8 && !documentall) {	

	if (event.preventDefault){ //standart browsers
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	obj.value= demaskvalue(x,true).formatCurrency();
	return false;
}

FormataReais(obj,'.',',',event);
} // end reais


function backspace(obj,event){


var whichCode = (window.Event) ? event.which : event.keyCode;
if (whichCode == 8 && documentall) {	
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	var y = demaskvalue(x,true).formatCurrency();

	obj.value =""; 
	obj.value += y;
	
	if (event.preventDefault){ 
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	return false;

	}		
}

function FormataReais(fld, milSep, decSep, e) {
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;

if (whichCode == 0 ) return true;
if (whichCode == 9 ) return true; //tecla tab
if (whichCode == 13) return true; //tecla enter
if (whichCode == 16) return true; //shift internet explorer
if (whichCode == 17) return true; //control no internet explorer
if (whichCode == 27 ) return true; //tecla esc
if (whichCode == 34 ) return true; //tecla end
if (whichCode == 35 ) return true;//tecla end
if (whichCode == 36 ) return true; //tecla home


if (e.preventDefault){ 
		e.preventDefault()
	}else{ 
		e.returnValue = false
}

var key = String.fromCharCode(whichCode);  
if (strCheck.indexOf(key) == -1) return false;  

fld.value += key;

var len = fld.value.length;
var bodeaux = demaskvalue(fld.value,true).formatCurrency();
fld.value=bodeaux;

  if (fld.createTextRange) {
    var range = fld.createTextRange();
    range.collapse(false);
    range.select();
  }
  else if (fld.setSelectionRange) {
    fld.focus();
    var length = fld.value.length;
    fld.setSelectionRange(length, length);
  }
  return false;

}
/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//----------------------------------------------------------------------------------------------------
/* ------------------ Sub-Mascara jquery_moneda   ---------------------------*/
documentall = document.all;


function formatamoney(c) {
    var t = this; if(c == undefined) c = 2;		
    var p, d = (t=t.split("."))[1].substr(0, c);
    for(p = (t=t[0]).length; (p-=3) >= 1;) {
	        t = t.substr(0,p) + "." + t.substr(p);
    }
    return t+","+d+Array(c+1-d.length).join(0);
}

String.prototype.formatCurrency=formatamoney

function demaskvalue(valor, currency){

var val2 = '';
var strCheck = '0123456789';
var len = valor.length;
	if (len== 0){
		return 0.00;
	}

	if (currency ==true){	

		
		for(var i = 0; i < len; i++)
			if ((valor.charAt(i) != '0') && (valor.charAt(i) != ',')) break;
		
		for(; i < len; i++){
			if (strCheck.indexOf(valor.charAt(i))!=-1) val2+= valor.charAt(i);
		}

		if(val2.length==0) return "0.00";
		if (val2.length==1)return "0.0" + val2;
		if (val2.length==2)return "0." + val2;
		
		var parte1 = val2.substring(0,val2.length-2);
		var parte2 = val2.substring(val2.length-2);
		var returnvalue = parte1 + "." + parte2;
		return returnvalue;
		
	}
	else{
			val3 ="";
			for(var k=0; k < len; k++){
				if (strCheck.indexOf(valor.charAt(k))!=-1) val3+= valor.charAt(k);
			}			
	return val3;
	}
}

function reais(obj,event){

var whichCode = (window.Event) ? event.which : event.keyCode;

if (whichCode == 8 && !documentall) {	

	if (event.preventDefault){ //standart browsers
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	obj.value= demaskvalue(x,true).formatCurrency();
	return false;
}

FormataReais(obj,'.',',',event);
} // end reais


function backspace(obj,event){


var whichCode = (window.Event) ? event.which : event.keyCode;
if (whichCode == 8 && documentall) {	
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	var y = demaskvalue(x,true).formatCurrency();

	obj.value =""; 
	obj.value += y;
	
	if (event.preventDefault){ 
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	return false;

	}		
}

function FormataReais(fld, milSep, decSep, e) {
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;

if (whichCode == 0 ) return true;
if (whichCode == 9 ) return true; //tecla tab
if (whichCode == 13) return true; //tecla enter
if (whichCode == 16) return true; //shift internet explorer
if (whichCode == 17) return true; //control no internet explorer
if (whichCode == 27 ) return true; //tecla esc
if (whichCode == 34 ) return true; //tecla end
if (whichCode == 35 ) return true;//tecla end
if (whichCode == 36 ) return true; //tecla home


if (e.preventDefault){ 
		e.preventDefault()
	}else{ 
		e.returnValue = false
}

var key = String.fromCharCode(whichCode);  
if (strCheck.indexOf(key) == -1) return false;  

fld.value += key;

var len = fld.value.length;
var bodeaux = demaskvalue(fld.value,true).formatCurrency();
fld.value=bodeaux;

  if (fld.createTextRange) {
    var range = fld.createTextRange();
    range.collapse(false);
    range.select();
  }
  else if (fld.setSelectionRange) {
    fld.focus();
    var length = fld.value.length;
    fld.setSelectionRange(length, length);
  }
  return false;

}
/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
function bloquear(){
//getObj('contabilidad_comprobante_pr_numero_comprobante').disabled="disabled";
getObj('contabilidad_comprobante_pr_cuenta_contable').disabled="disabled";
//getObj('contabilidad_comprobante_pr_ref').disabled="disabled";
getObj('contabilidad_comprobante_pr_debe_haber').disabled="disabled";
getObj('contabilidad_comprobante_pr_monto').disabled="disabled";
getObj('contabilidad_comprobante_pr_auxiliar').disabled="disabled";
getObj('contabilidad_comprobante_pr_ubicacion').disabled="disabled";
getObj('contabilidad_comprobante_pr_centro_costo').disabled="disabled";
getObj('contabilidad_comprobante_pr_utf').disabled="disabled";
getObj('contabilidad_comprobante_pr_activo').value='0';
getObj('contabilidad_comprobante_pr_activo2').value='0';
getObj('contabilidad_comprobante_pr_activo3').value='0';

}
function desbloquear(){
//getObj('contabilidad_comprobante_pr_numero_comprobante').disabled="";
getObj('contabilidad_comprobante_pr_cuenta_contable').disabled=""//;
//getObj('contabilidad_comprobante_pr_ref').disabled="";
getObj('contabilidad_comprobante_pr_debe_haber').disabled="";
getObj('contabilidad_comprobante_pr_monto').disabled="";
getObj('contabilidad_comprobante_pr_auxiliar').disabled="";
getObj('contabilidad_comprobante_pr_ubicacion').disabled="";
getObj('contabilidad_comprobante_pr_centro_costo').disabled="";
getObj('contabilidad_comprobante_pr_utf').disabled="";
}

//-------------------------------------------------------------------------------------------------------------------------------------
var lastsel,idd,monto;
$("#list_comprobante_auto").jqGrid({
	height: 115,
	width: 570,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables.php?nd='+new Date().getTime(),
//	+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value
	datatype: "json",
	colNames:['Id','Organismo','Ano','Mes','tipo','Comprobante','Secuencia','Comentarios','Cuenta Contable','Desc','REF','Debito','Credito','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','id_cc','tipo_comen'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'codigo_organismo',index:'codigo_organismo', width:20,hidden:true},
			{name:'ano_comprobante',index:'ano_comprobante', width:20,hidden:true},
			{name:'mes_comprobante',index:'mes_comprobante', width:20,hidden:true},
			{name:'codigo_tipo_comprobante',index:'codigo_tipo_comprobante', width:20,hidden:true},
			{name:'numero_comprobante',index:'numero_comprobante', width:20,hidden:true},
			{name:'secuencia',index:'secuencia', width:20,hidden:true},
			{name:'comentarios',index:'comentarios', width:20,hidden:true},
			{name:'cuenta_contable',index:'cuenta_contable',width:45},
			{name:'descripcion',index:'descripcion',width:60},
			{name:'ref',index:'ref',width:20},
			{name:'monto_debito',index:'monto_debito',width:40},
			{name:'monto_credito',index:'monto_credito',width:40},
			{name:'fecha_comprobante',index:'fecha_comprobante',width:30,hidden:true,hidden:true},
			{name:'codigo_auxiliar',index:'codigo_auxiliar',width:30,hidden:true},
			{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora',width:30,hidden:true},
			{name:'codigo_proyecto',index:'codigo_proyecto',width:30,hidden:true,hidden:true},
			{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:30,hidden:true},
			{name:'id_cc',index:'id_cc',width:30,hidden:true},
			{name:'tipo_comp',index:'tipo_comp',width:30,hidden:true}
			
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_cotizaciones'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	//multiselect: true,
		onSelectRow: function(id){
		var ret = jQuery("#list_comprobante_auto").getRowData(id);
		//alert("modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value);
		//setBarraEstado("modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto);
		idd = ret.id;
		if(idd && idd!==lastsel){//	alert(idd);
		$.ajax({
			url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables2.php?nd='+new Date().getTime()+"&id="+idd,
			//url:"modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value,
            data:dataForm('form_contabilidad_pr_movimientos'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		   url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables2.php?nd='+new Date().getTime()+"&id="+idd;
			   /*alert(url);
			  alert(html);*/
			    var recordset=html;				
				recordset = recordset.split("*");
				getObj('contabilidad_vista_id_comprobante').value = recordset[0];
				getObj('contabilidad_comprobante_pr_numero_comprobante').value = recordset[5];
				getObj('contabilidad_comprobante_pr_cuenta_contable').value=recordset[8];
				getObj('contabilidad_comprobante_pr_desc').value=recordset[9];
				getObj('contabilidad_comprobante_pr_ref').value=recordset[10];
			
				if(recordset[11]!="0,00")
				{
					debito_credito=1;
					getObj('contabilidad_comprobante_pr_monto').value=recordset[11];
					
				}else
				if(recordset[12]!="0,00")
				{
					debito_credito=2;
					getObj('contabilidad_comprobante_pr_monto').value=recordset[12];
				}	
				getObj('contabilidad_comprobante_pr_debe_haber').value=debito_credito;

				
				getObj('contabilidad_comprobante_pr_auxiliar').value=recordset[13];
				getObj('contabilidad_comprobante_pr_ubicacion').value=recordset[14];
				getObj('contabilidad_comprobante_pr_centro_costo').value=recordset[15];
				getObj('contabilidad_comprobante_pr_utf').value=recordset[16];
				getObj('contabilidad_auxiliares_db_id_cuenta').value=recordset[17];
				///// activando condiciones de campos ocultos
				bloquear();
			if(getObj('contabilidad_comprobante_pr_auxiliar').value!=0)
			{
				getObj('contabilidad_comprobante_pr_activo').value=1;
			}
			if(getObj('contabilidad_comprobante_pr_ubicacion').value!=0)
			{
				getObj('contabilidad_comprobante_pr_activo2').value=1;
			}
			if(getObj('contabilidad_comprobante_pr_utf').value!=0)
			{
				getObj('contabilidad_comprobante_pr_activo3').value=1;
			}
				
				//////////////////////////////////////////////
		
				getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
				getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='';
				//getObj('tesoreria_moneda_db_btn_eliminar').style.display='';
				//getObj('contabilidad_auxiliares_db_btn_guardar').style.display='none';
		
			 }
		});	 
			/*$.ajax (
				{
				url: "modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto,
					data:dataForm('form_pr_cotizaciones'),
					type:'GET',
					cache: false,
					success: function(html)
					{
					setBarraEstado(html);
						if (resultado=="Ok")
						{
							setBarraEstado(mensaje[registro_exitoso],true,true);
							jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta.php?numero_requision="+getObj('cotizacones_pr_numero_cotizacion').value,page:1}).trigger("reloadGrid");
							//clearForm('form_pr_cotizaciones');
						}
						else
						{
							setBarraEstado(html);
						}

					}
				});	*/
			jQuery('#list_comprobante_auto').restoreRow(lastsel);
			jQuery('#list_comprobante_auto').editRow(idd,true);
			lastsel=idd;
			
		}
			
	},
	
}).navGrid("#pager_comprobante_auto",{search :false,edit:false,add:false,del:false});
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_auxiliares_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#contabilidad_auxiliares_db_cuenta_auxiliar').numeric({});
$('#contabilidad_auxiliares_db_cuenta_contable').numeric({});
$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	
</script>

<div id="botonera">
	<img id="contabilidad_auxiliares_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_comprobante_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<!--<img id="tesoreria_moneda_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/> -->

	<img id="contabilidad_movimientos_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
</div>	
<form method="post" id="form_contabilidad_pr_movimientos" name="form_contabilidad_pr_movimientos">
<input type="hidden"  id="contabilidad_vista_id_comprobante" name="contabilidad_vista_id_comprobante"/>
<input type="hidden" id="contabilidad_comprobante_pr_activo" name="contabilidad_comprobante_pr_activo"  value="0"/>
 <input type="hidden" id="contabilidad_comprobante_pr_activo2" name="contabilidad_comprobante_pr_activo2" value="0"/>
 <input type="hidden" id="contabilidad_comprobante_pr_activo3" name="contabilidad_comprobante_pr_activo3" value="0"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Mantenimiento Comprobante Auto</th>
	</tr>
	<tr >
	<th>Número Comprobante:</th>
		<td>
			<input type="text" id="contabilidad_comprobante_pr_numero_comprobante" name="contabilidad_comprobante_pr_numero_comprobante"  size='12' maxlength="12"  message="Introduzca nº comprobante" >		</td>
	</tr>
	<tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="contabilidad_comprobante_pr_cuenta_contable" id="contabilidad_comprobante_pr_cuenta_contable"  size='12' maxlength="12"
				message="Introduzca la cuenta contable" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		        <input type="hidden" id="contabilidad_auxiliares_db_id_cuenta" name="contabilidad_auxiliares_db_id_cuenta" />
		 </li>
		<li id="contabilidad_vista_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
	    </ul>	  </td>	
		</tr>
		<tr>
			<th>Ref:</th>
		  <td><input type="text" id="contabilidad_comprobante_pr_ref" name="contabilidad_comprobante_pr_ref" size="12" maxlength="12" message="Introduzca la refrencia" /></td>
		</tr>
		<tr>
		
			<th>Descripci&oacute;n:</th>
			 <td>
			 <textarea  name="contabilidad_comprobante_pr_desc" cols="60" id="contabilidad_comprobante_pr_desc" message="Introduzca una Descripción del asiento. Ejem:'Esta cuenta es ...' " style="width:422px"></textarea>
			</td>
		</tr>
		<tr>
			<th>Debe-Haber:</th>
			<td>
				<input type="text" name="contabilidad_comprobante_pr_debe_haber" id="contabilidad_comprobante_pr_debe_haber" size="12" maxlength="1" onblur="validar_debe_haber()"
				message="Introduzca el valor 1 si el registro va por el debe , 2 si el registro va por el haber" 
				jval="{valid:/^[1-2]{1,2}$/, message:'Codigo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[1-2]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>			</td>
		</tr>
		<tr>
			<th>
				Monto:			</th>
			<td>
				<input type="text" name="contabilidad_comprobante_pr_monto" id="contabilidad_comprobante_pr_monto" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" message="Introduzca el monto dle asiento" size="12" maxlength="12" >			</td>
		</tr>
	</table>
	<table  cols="4" class="cuerpo_formulario" width="100%" border="0">
					<tr>
					<th>Total Debe:</th>
					<td>
						<input type="text" id="contabilidad_comprobante_pr_total_debe" name="contabilidad_comprobante_pr_total_debe"  value="0,00" readonly="readonly" />
					</td>
					<th>Total Haber:</th>
					<td>
						<input type="text" id="contabilidad_comprobante_pr_total_haber" name="contabilidad_comprobante_pr_total_haber" readonly="readonly" value="0,00" />
					</td>
				</tr>
				<tr>
					<th>Auxiliar:		</th>	
					<td>
					<ul class="input_con_emergente">
					 <li>	
					<input name="contabilidad_comprobante_pr_auxiliar" type="text" id="contabilidad_comprobante_pr_auxiliar"   value="" size="12" maxlength="12" message="Introduzca el c&iacute;digo del auxiliar' " 
										jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/> 
					 <input type="hidden" id="contabilidad_comprobante_contabilidad_id" name="contabilidad_comprobante_contabilidad_id">
					 </li>
					<li id="contabilidad_vista_btn_consultar_auxiliar_cmp" class="btn_consulta_emergente"></li>
					</ul>
					</td>
					<th>Ubicaci&oacute;n:</th>
					<td>
					<ul class="input_con_emergente">
					<li>
							  <input name="contabilidad_comprobante_pr_ubicacion" type="text" id="contabilidad_comprobante_pr_ubicacion"   value="" size="12" maxlength="12" message="Introduzca ubicación d ela cuenta ejm:Div. Telemat' " 
							jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>		  <input type="hidden" name="contabilidad_comprobante_pr_ejec_id" id="contabilidad_comprobante_pr_ejec_id"/>
				   	</li>
					<li id="contabilidad_vista_btn_consultar_ubicacion_cmp" class="btn_consulta_emergente"></li>
					</ul>	
					</td>
			</tr>
			<tr>
					<th>Centro de Costo:</th>
					<td><input name="contabilidad_comprobante_pr_centro_costo" type="text" id="contabilidad_comprobante_pr_centro_costo"   value="" size="12" maxlength="12" message="Introduzca el centro de costo' " 
										jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>		</td>
					<th>Utilización Fondos</th>
					<td>
					<ul class="input_con_emergente">
					<li>
					  <input name="contabilidad_comprobante_pr_utf" type="text" id="contabilidad_comprobante_pr_utf"   value="" size="12" maxlength="12" message="Introduzca el código de Utilización de fondos' " 
										jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					  <input type="hidden" id="contabilidad_comprobante_pr_utf_id" name="contabilidad_comprobante_pr_utf_id" />
					 </li>
					<li id="contabilidad_vista_btn_consultar_utf" class="btn_consulta_emergente"></li>
					</ul>
					</td>
		</tr>
				<tr>
					<td class="celda_consulta" colspan="4">
							<table id="list_comprobante_auto" class="scroll" cellpadding="0" cellspacing="0"></table> 
							<div id="pager_comprobante_auto" class="scroll" style="text-align:center;"></div> 
							<br />		</td>
				</tr>
				 <tr>
					<td height="22" colspan="4" class="bottom_frame">&nbsp;</td>
				  </tr>
			</table>
<input   type="hidden" name="contabilidad_auxiliares_db_id_aux"  id="contabilidad_auxiliares_db_id_aux" />
</form>