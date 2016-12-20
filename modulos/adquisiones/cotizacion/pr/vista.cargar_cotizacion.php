<? if (!$_SESSION) session_start();

?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------
$("#cargar_cotizacion_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cotizaciones', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:750,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/sql_grid_cotizacionDE.php?nd='+nd,
								datatype: "json",
								colNames:['Cotizacion','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora','id_solicitud_cotizacione','titulo','Renglon','Cantidad','Unidad Medida','Descripcion','Monto','id_solicitud_cotizacione'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'id_solicitud_cotizacione',index:'id_solicitud_cotizacione', width:100,sortable:false,resizable:false,hidden:true},
									{name:'titulo',index:'titulo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'secuencia',index:'secuencia', width:50,sortable:false,resizable:false},
									{name:'cantidad',index:'cantidad', width:50,sortable:false,resizable:false},
									{name:'unidad_medida',index:'unidad_medida', width:50,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:200,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:200,sortable:false,resizable:false,hidden:true},														
									{name:'id_solicitud_d',index:'id_solicitud_d', width:200,sortable:false,resizable:false,hidden:true}														
								
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("cargar_cotizacion_pr_id_detalle").value=ret.id_solicitud_d;
									getObj("cargar_cotizacion_pr_numero_cotizacion").value=ret.numero;
									getObj("cargar_cotizacion_pr_id_proveedor").value=ret.idproveedor;
									getObj("cargar_cotizacion_pr_proveedor").value=ret.proveedor;
									getObj("cargar_cotizacion_pr_id_unidad_ejecutora").value=ret.id_unidad_ejecutora;
									getObj("cargar_cotizacion_pr_unidad_ejecutora").value=ret.unidad_ejecutora;
									getObj("cargar_cotizacion_pr_id").value=ret.id_solicitud_cotizacione;
									getObj("cargar_cotizacion_pr_id_renglon").value=ret.secuencia;
									getObj("cargar_cotizacion_pr_cantida").value=ret.cantidad;
									getObj("cargar_cotizacion_pr_unidad_medida").value=ret.unidad_medida;
									getObj("cargar_cotizacion_pr_renglon").value=ret.descripcion;
									getObj("cargar_cotizacion_pr_costo").value=ret.monto;
									getObj("cargar_cotizacion_pr_renglon_codigo").value=ret.secuencia;	
/*									alert(getObj("cargar_cotizacion_pr_id").value); */
								getObj("cargar_cotizacion_pr_titulo").value=ret.titulo;
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
								sortname: 'numero_cotizacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});


//----------------------------------------------------------------


$("#cargar_cotizacion_pr_btn_guardar").click(function() {
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/cotizacion/pr/sql.cotizacion_detalle.php",
			data:dataForm('form_pr_cargar_cotizacion'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('cargar_cotizacion_pr_renglon_codigo').value="";
					getObj('cargar_cotizacion_pr_renglon').value="";
					getObj('cargar_cotizacion_pr_cantida').value="";
					getObj('cargar_cotizacion_pr_unidad_medida').value="";
					getObj('cargar_cotizacion_pr_costo').value = '0,00';
//					clearForm('form_pr_cargar_cotizacion');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			}
		});
});


//----------------------------------------------------------------

$("#cargar_cotizacion_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cotizaciones', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/cmb.sql.numero_cotizacion.php?nd='+nd,
								datatype: "json",
								colNames:['Cotizacion','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora','id_solicitud_cotizacione','titulo'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'id_solicitud_cotizacione',index:'id_solicitud_cotizacione', width:100,sortable:false,resizable:false,hidden:true},
									{name:'titulo',index:'titulo', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("cargar_cotizacion_pr_numero_cotizacion").value=ret.numero;
									getObj("cargar_cotizacion_pr_id_proveedor").value=ret.idproveedor;
									getObj("cargar_cotizacion_pr_proveedor").value=ret.proveedor;
									getObj("cargar_cotizacion_pr_id_unidad_ejecutora").value=ret.id_unidad_ejecutora;
									getObj("cargar_cotizacion_pr_unidad_ejecutora").value=ret.unidad_ejecutora;
									getObj("cargar_cotizacion_pr_id").value=ret.id_solicitud_cotizacione;
/*									alert(getObj("cargar_cotizacion_pr_id").value);
*/									getObj("cargar_cotizacion_pr_titulo").value=ret.titulo;
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
								sortname: 'numero_cotizacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
// ******************************************************************************
$("#cargar_cotizacion_pr_btn_consulta_emergente_detalle").click(function() {
	var nd=new Date().getTime();
//	alert('modulos/adquisiones/cotizacion/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&nro='+getObj("cargar_cotizacion_pr_numero_cotizacion").value);
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	
	
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cotizaciones', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:900,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&nro='+getObj("cargar_cotizacion_pr_numero_cotizacion").value,
								datatype: "json",
								colNames:['Id','numero_cotizacion','Renglon','Cantida','Unidad Medida','Descripcion','Monto'],
								colModel:[
									{name:'id',index:'id', width:8,sortable:false,resizable:false,hidden:true},
									{name:'numero',index:'numero', width:15,sortable:false,resizable:false,hidden:true},
									{name:'secuencia',index:'secuencia', width:15,sortable:false,resizable:false},
									{name:'cantidad',index:'cantidad', width:15,sortable:false,resizable:false},
									{name:'unidad_medida',index:'unidad_medida', width:25,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:200,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:200,sortable:false,resizable:false,hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("cargar_cotizacion_pr_id_detalle").value=ret.id;
									//getObj("cargar_cotizacion_pr_id_proveedor").value=ret.numero;
									getObj("cargar_cotizacion_pr_id_renglon").value=ret.secuencia;
									getObj("cargar_cotizacion_pr_cantida").value=ret.cantidad;
									getObj("cargar_cotizacion_pr_unidad_medida").value=ret.unidad_medida;
									getObj("cargar_cotizacion_pr_renglon").value=ret.descripcion;
									getObj("cargar_cotizacion_pr_costo").value=ret.monto;
									getObj("cargar_cotizacion_pr_renglon_codigo").value=ret.secuencia;							
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
								sortname: 'id_solicitud_cotizacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#cargar_cotizacion_pr_btn_cancelar").click(function() {
clearForm('form_pr_cargar_cotizacion');
getObj('cargar_cotizacion_pr_costo').value = '0,00';
setBarraEstado("");
});
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_renglon()
{
		$.ajax({
			url:"modulos/adquisiones/cotizacion/pr/sql_grid_renglon_cotizacion.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_cargar_cotizacion'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html
			
			if(recordset)
				{
				recordset = recordset.split("*");
				getObj("cargar_cotizacion_pr_id_detalle").value=recordset[0];
				getObj('cargar_cotizacion_pr_id_renglon').value = recordset[2];
				getObj('cargar_cotizacion_pr_renglon').value = recordset[5];
				getObj('cargar_cotizacion_pr_cantida').value=recordset[3];
				getObj('cargar_cotizacion_pr_unidad_medida').value=recordset[4];
				}
				else
			   {  
				getObj('cargar_cotizacion_pr_id_renglon').value = "";
				getObj('cargar_cotizacion_pr_renglon').value = "";
				getObj('cargar_cotizacion_pr_renglon_codigo"').value = "";
			    }
			 }
		});	 	 
	
}
// -----------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_ncotizacion()
{
	if (getObj('cargar_cotizacion_pr_numero_cotizacion')!=" ")
	{
	$.ajax({
			url:"modulos/adquisiones/cotizacion/pr/sql_grid_cotizacion.php",
            data:dataForm('form_pr_cargar_cotizacion'),
			type:'POST',
			cache: false,
			 success:function(html)
			 { 
			 	 var recordset=html;
				if(recordset)
				{
				recordset = recordset.split(".");
				getObj("cargar_cotizacion_pr_id").value=recordset[5];
				getObj('cargar_cotizacion_pr_unidad_ejecutora').value = recordset[4];
				getObj('cargar_cotizacion_pr_proveedor').value=recordset[2];
				getObj('cargar_cotizacion_pr_titulo').value=recordset[6];
				getObj('documento_proveedor_db_btn_actualizar').style.display='';
				getObj('documento_proveedor_db_btn_guardar').style.display='none';	
				}
				else
			 {  
			    getObj("cargar_cotizacion_pr").value="";
				getObj('cargar_cotizacion_pr_unidad_ejecutora').value = "";
				getObj('cargar_cotizacion_pr_proveedor').value="";
				getObj('cargar_cotizacion_pr_titulo').value="";
				
				}
			 }
		});	 	 
	}	
}
//-------------------------------------------------------------------------------------------------------------------------------------------------
/* ******************************************************************************
$("#cargar_cotizacion_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
//	alert('modulos/adquisiones/cotizacion/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&nro='+getObj("cargar_cotizacion_pr_numero_cotizacion").value);
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	
	
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cotizaciones', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:900,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&nro='+getObj("cargar_cotizacion_pr_numero_cotizacion").value,
								datatype: "json",
								colNames:['Id','numero_cotizacion','Renglon','Cantida','Unidad Medida','Descripcion'],
								colModel:[
									{name:'id',index:'id', width:8,sortable:false,resizable:false,hidden:true},
									{name:'numero',index:'numero', width:15,sortable:false,resizable:false,hidden:true},
									{name:'secuencia',index:'secuencia', width:15,sortable:false,resizable:false},
									{name:'cantidad',index:'cantidad', width:15,sortable:false,resizable:false},
									{name:'unidad_medida',index:'unidad_medida', width:25,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj("cargar_cotizacion_pr_numero_cotizacion").value=ret.id;
									//getObj("cargar_cotizacion_pr_id_proveedor").value=ret.numero;
									getObj("cargar_cotizacion_pr_id_renglon").value=ret.secuencia;
									getObj("cargar_cotizacion_pr_cantida").value=ret.cantidad;
									getObj("cargar_cotizacion_pr_unidad_medida").value=ret.unidad_medida;
									getObj("cargar_cotizacion_pr_renglon").value=ret.descripcion;
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
								sortname: 'id_solicitud_cotizacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
*/
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
$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
</script>
<div id="botonera">
	<img id="cargar_cotizacion_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
<img id="cargar_cotizacion_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
	<img id="cargar_cotizacion_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>

<form name="form_pr_cargar_cotizacion" id="form_pr_cargar_cotizacion">
<input type="hidden" name="cargar_cotizacion_pr_id" id="cargar_cotizacion_pr_id" />
<input type="hidden" name="cargar_cotizacion_pr_id_detalle" id="cargar_cotizacion_pr_id_detalle" />
<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Cargar Cotizaci&oacute;n			</th>
	</tr>
		<tr>
			<th>N&ordm; Cotizaci&oacute;n</th>
			<td><input type="text" name="cargar_cotizacion_pr_numero_cotizacion" id="cargar_cotizacion_pr_numero_cotizacion" size="7" />
				<img id="cargar_cotizacion_pr_btn_consulta_emergente" class="btn_consulta_emergente" src="imagenes/null.gif"  />			</td>
		</tr>
		<tr>
			<th>Unidad Solicitante </th>
			<td><input type="hidden" name="cargar_cotizacion_pr_id_unidad_ejecutora" id="cargar_cotizacion_pr_id_unidad_ejecutora"  />
				<input type="text" name="cargar_cotizacion_pr_unidad_ejecutora" id="cargar_cotizacion_pr_unidad_ejecutora" readonly size="40" />			</td>
		</tr>
		<tr>
			<th>Proveedor</th>
			<td><input type="hidden" name="cargar_cotizacion_pr_id_proveedor" id="cargar_cotizacion_pr_id_proveedor"  />
				<input type="text" name="cargar_cotizacion_pr_proveedor" id="cargar_cotizacion_pr_proveedor" readonly size="63" />			</td>
		</tr>
		<tr>
			<th>T&iacute;tulo</th>
			<td><textarea name="cargar_cotizacion_pr_titulo" id="cargar_cotizacion_pr_titulo"  cols="60" rows="2"  ></textarea></td>
		</tr>
		<tr>
			<th>Renglon</th>
			<td>
	 <ul class="input_con_emergente">
			<li>
			<input name="cargar_cotizacion_pr_renglon_codigo" type="text" id="cargar_cotizacion_pr_renglon_codigo"  maxlength="4" size="5"
				onchange="consulta_automatica_renglon" onclick="consulta_automatica_renglon"
				message="Introduzca codigo del renglon." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,4}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
				
			   <input type="hidden" name="cargar_cotizacion_pr_id_renglon" id="cargar_cotizacion_pr_id_renglon"  />
				<input name="cargar_cotizacion_pr_renglon" type="text" id="cargar_cotizacion_pr_renglon"   size="50" >
			</li>
			<li id="cargar_cotizacion_pr_btn_consulta_emergente_detalle" class="btn_consulta_emergente"></li>
	</ul>
			</td>
		</tr>
		<tr>
			<th>Cantidad</th>
			<td><input type="text" name="cargar_cotizacion_pr_cantida" id="cargar_cotizacion_pr_cantida" readonly size="8" />
			<input type="text" name="cargar_cotizacion_pr_unidad_medida" id="cargar_cotizacion_pr_unidad_medida" readonly size="6" /></td>
		</tr>
		<tr>
			<th>Costo</th>
			<td>	<input  name="cargar_cotizacion_pr_costo" type="text" id="cargar_cotizacion_pr_costo" 	 value="0,00" size="16" maxlength="16" 
			message="Introduzca el Monto Asignado para Octubre" jVal="{valid:/^[0123456789,. ]{1,60}$/, message:'Monto Invalido', styleType:'cover'}"  
			jValKey="{valid:/[0123456789,]/, cFunc:'alert', cArgs:['Año: '+$(this).val()]}" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" style="text-align:right">
		</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>