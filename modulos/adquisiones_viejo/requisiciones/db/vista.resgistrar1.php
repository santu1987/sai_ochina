<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM tipo_documento Where nombre = 'Requisiciones' ORDER BY nombre";
$rs_tipo_doc =& $conn->Execute($sql);
while (!$rs_tipo_doc->EOF) {
	$opt_tipo_doc.="<option value='".$rs_tipo_doc->fields("id_tipo_documento")."' >".$rs_tipo_doc->fields("nombre")."</option>";
	$rs_tipo_doc->MoveNext();
}

$sql="SELECT * FROM unidad_medida  ORDER BY nombre";
$rs_unida_medida =& $conn->Execute($sql);
while (!$rs_unida_medida->EOF) {
	$opt_unida_medida.="<option value='".$rs_unida_medida->fields("id_unidad_medida")."' >".$rs_unida_medida->fields("nombre")."</option>";
	$rs_unida_medida->MoveNext();
}
 
?>
<script>

$("#requisiones_pr_btn_anadir").click(function(){
	if($('#form_pr_requisiones').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/requisiciones/db/sql.requisiciones.php",
			data:dataForm('form_pr_requisiones'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split(",");
			//alert(resultado[0]);
				if (resultado[0]=="Ok")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_requisiones');
					jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta.php?numero_requision="+resultado[1],page:1}).trigger("reloadGrid");
						getObj('requisiones_pr_numero_requision').value=resultado[1];
						getObj('requisiones_pr_numero').value =resultado[1]; ;
				}
				else
				{
					setBarraEstado(html);
						getObj('requisiones_pr_obesrvacion').value=html;
				}
			}
		});
	}
});
$("#requisiones_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Requici&oacute;n', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/requisiciones/db/sql_grid_requisiciones.php?nd='+nd,
								datatype: "json",
								colNames:['Nro de Requisicion', 'Año', 'Asunto','id_proyecto','Codigo Pr/accion cent','Proyecto/Accion central', 'id_accion_centralizada','priorida','comentario','id_accion_especifica','Codigo de accion especifica','Acci&oacute;n Espec&iacute;fica','Observacion'],
								colModel:[
									{name:'nro_requision',index:'nro_requision', width:100,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
									{name:'asunto',index:'asunto', width:160,sortable:false,resizable:false},
									{name:'id_proyecto',index:'id_proyecto', width:200,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'proyecto',index:'proyecto', width:180,sortable:false,resizable:false},
									{name:'id_accion_centralizada',index:'id_accion_centralizada', width:50,sortable:false,resizable:false,hidden:true},
									{name:'priorida',index:'priorida', width:200,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_especifica',index:'codigo_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'accion_epe',index:'accion_epe', width:220,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:50,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisiones_pr_numero_requision').value = ret.nro_requision;
									getObj('requisiones_pr_asunto').value = ret.asunto;
									if (ret.id_proyecto != 0){
										getObj('requisiones_pr_proyecto_id').value = ret.id_proyecto;
										getObj('requisiones_pr_codigo_proyecto').value = ret.codigo;
										getObj('requisiones_pr_proyecto').value = ret.proyecto;
										getObj('requisiones_pr_accion_central_id').value = ""
										getObj('requisiones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
										getObj('requisiciones_pr_codigo_central').value="0000";
										getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
										getObj('requisiones_pr_accion_central').disabled="disabled";
									}
									if (ret.id_accion_centralizada != 0){
										getObj('requisiones_pr_accion_central_id').value = ret.id_accion_centralizada;
										getObj('requisiciones_pr_codigo_central').value = ret.codigo;
										getObj('requisiones_pr_accion_central').value = ret.proyecto;
										getObj('requisiones_pr_proyecto_id').value="";
										getObj('requisiones_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
										getObj('requisiones_pr_codigo_proyecto').value ="0000" ;
										getObj('requisiones_pr_codigo_proyecto').disabled="disabled" ;
										getObj('requisiones_pr_proyecto').disabled="disabled" ;
															}
									getObj('requisiones_pr_accion_especifica_id').value = ret.id_accion_especifica;
									getObj('requisiciones_pr_accion_especifica_codigo').value = ret.codigo_accion_especifica;
									getObj('requisiones_pr_accion_especifica').value = ret.accion_epe;
									
									getObj('requisiones_pr_obesrvacion').value = ret.observacion;
									getObj('requisiones_pr_numero').value = ret.nro_requision;
									dialog.hideAndUnload();
									jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta.php?numero_requision="+ret.nro_requision,page:1}).trigger("reloadGrid");

					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'numero_requisicion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
// -----------------------------------------------------------------------------------------------------------------------------------

$("#requisiones_pr_btn_consultar_proyecto").click(function() {
if(getObj('requisiones_pr_accion_central_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/requisiciones/db/cmb.sql.proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisiones_pr_proyecto_id').value = ret.id;
									alert(ret.id);
									getObj('requisiones_pr_codigo_proyecto').value = ret.codigo
									getObj('requisiones_pr_proyecto').value = ret.denominacion;
									getObj('requisiones_pr_accion_central_id').value = ""
									getObj('requisiones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
									getObj('requisiciones_pr_codigo_central').value="0000";
									getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
									getObj('requisiones_pr_accion_central').disabled="disabled";
									getObj('requisiones_pr_accion_especifica').disabled=""
									getObj('requisiciones_pr_accion_especifica_codigo').disabled="";
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
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
// -----------------------------------------------------------------------------------------------------------------------------------
$("#requisiones_pr_btn_consultar_accion_central").click(function() {
if(getObj('requisiones_pr_proyecto_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/requisiciones/db/cmb.sql.accion_central.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Acci&oacute;n Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:200,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisiones_pr_accion_central_id').value = ret.id;
									getObj('requisiciones_pr_codigo_central').value = ret.codigo;
									getObj('requisiones_pr_accion_central').value = ret.denominacion;
									getObj('requisiones_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
									getObj('requisiones_pr_codigo_proyecto').value ="0000" ;
									getObj('requisiones_pr_codigo_proyecto').disabled="disabled" ;
									getObj('requisiones_pr_proyecto').disabled="disabled" ;
									getObj('requisiones_pr_accion_especifica').disabled=""
									getObj('requisiciones_pr_accion_especifica_codigo').disabled="";
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
								sortname: 'id_accion_central',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
// -----------------------------------------------------------------------------------------------------------------------------------
$("#requisiones_pr_btn_consultar_accion_especifica").click(function() {

if(getObj('requisiones_pr_proyecto_id').value !="" || getObj('requisiones_pr_accion_central_id').value !="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Especifica', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/requisiciones/db/cmb.sql.accion_especifica.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo', 'Accion Especifica'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisiones_pr_accion_especifica_id').value = ret.id;
									alert(ret.id);
									//getObj('requisiciones_pr_accion_especifica_codigo').value = ret.codigo;
									getObj('requisiones_pr_accion_especifica').value = ret.denominacion;
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
								sortname: 'id_accion_especifica',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
// -----------------------------------------------------------------------------------------------------------------------------------
$("#requisiones_pr_btn_partida").click(function() {

if(getObj('requisiones_pr_accion_especifica_id').value !="")
{
	urls='modulos/adquisiones/requisiciones/db/cmb.sql.partida.php?nd='+nd+'&unidad_es='+getObj('requisiones_pr_accion_especifica_id').value+'&accion='+getObj('requisiones_pr_accion_central_id').value+'&proyecto='+getObj('requisiones_pr_proyecto_id').value;
		//alert(urls);
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Especifica', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								datatype: "json",
								colNames:['Id','Partida', 'Denominacion'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('requisiones_pr_accion_especifica_id').value = ret.id;
									getObj('requisiones_pr_partida').value = ret.partida;
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
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//---------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_accion_central()
{
	$.ajax({
			url:"modulos/adquisiones/requisiciones/db/sql_grid_accion_cental_codigo.php",
            data:dataForm('form_pr_requisiones'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				
				getObj('requisiones_pr_accion_central_id').value = recordset[0];
				getObj('requisiones_pr_accion_central').value=recordset[1];
				getObj('requisiones_pr_proyecto_id').value="";
				
				getObj('requisiones_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('requisiones_pr_codigo_proyecto').value ="0000" ;
				getObj('requisiones_pr_codigo_proyecto').disabled="disabled" ;
				getObj('requisiones_pr_proyecto').disabled="disabled" ;
				getObj('requisiones_pr_accion_especifica').disabled=""
				getObj('requisiciones_pr_accion_especifica_codigo').disabled="";
				
				}
				else
			 {  
				getObj('requisiones_pr_accion_central_id').value ="";
				getObj('requisiones_pr_accion_central').value="";
				getObj('requisiones_pr_proyecto_id').value="";
				getObj('requisiones_pr_proyecto').value="";
				getObj('requisiones_pr_codigo_proyecto').value ="" ;
				getObj('requisiones_pr_codigo_proyecto').disabled="" ;
				}
			 }
		});	 	 
}
// ----------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/adquisiones/requisiciones/db/sql_grid_proyecto_codigo.php",
            data:dataForm('form_pr_requisiones'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('requisiones_pr_proyecto_id').value = recordset[0];
				getObj('requisiones_pr_proyecto').value=recordset[1];
				getObj('requisiones_pr_accion_central_id').value = ""
				getObj('requisiones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
				getObj('requisiciones_pr_codigo_central').value="0000";
				getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
				getObj('requisiones_pr_accion_central').disabled="disabled";
				getObj('requisiones_pr_accion_especifica').disabled=""
				getObj('requisiciones_pr_accion_especifica_codigo').disabled="";
				
				}
				else
			 {  
			   	getObj('requisiones_pr_proyecto_id').value ="";
				getObj('requisiones_pr_proyecto').value="";
				getObj('requisiones_pr_accion_central_id').value = ""
				getObj('requisiones_pr_accion_central').value="";
				getObj('requisiciones_pr_codigo_central').value="";
				getObj('requisiciones_pr_codigo_central').disabled=""; 
				}
			 }
		});	 	 
}
/// ----------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_accion_especifica()
{
		$.ajax({
			url:"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_requisiones'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html
			  alert(html);
			  getObj('requisiones_pr_obesrvacion').value=html;
			if(recordset)
				{
				recordset = recordset.split("*");
				getObj('requisiones_pr_accion_especifica_id').value = recordset[0];
				getObj('requisiones_pr_accion_especifica').value = recordset[1];
				
				}
				else
			   {  
			   	getObj('requisiones_pr_accion_especifica_id').value = "";
				getObj('requisiciones_pr_accion_especifica_codigo').value = "";
				getObj('requisiones_pr_accion_especifica').value = "";
			    }
			 }
		});	 	 
	
}
// -----------------------------------------------------------------------------------------------------------------------------------
//
$("#requisiones_pr_btn_cancelar").click(function() {
getObj('requisiciones_pr_codigo_central').disabled=""; 
getObj('requisiones_pr_codigo_proyecto').disabled="" ;
getObj('requisiciones_pr_codigo_central').disabled=""; 
getObj('requisiones_pr_ano').value ="";
 getObj('requisiones_pr_proyecto').value ="";
 getObj('requisiones_pr_proyecto_id').value ="";
 getObj('requisiones_pr_codigo_proyecto').value ="";
 getObj('requisiciones_pr_codigo_central').value ="";
 getObj('requisiones_pr_accion_central').value ="";
 getObj('requisiones_pr_accion_central_id').value ="";
  getObj('requisiones_pr_accion_especifica').value ="";
 getObj('requisiciones_pr_accion_especifica_codigo').value ="";
 getObj('requisiones_pr_accion_especifica_id').value ="";
 getObj('requisiones_pr_asunto').value ="";
 getObj('requisiones_pr_obesrvacion').value ="";
 getObj('requisiones_pr_producto').value ="";
 getObj('requisiones_pr_partida').value ="";
getObj('requisiones_pr_cantidad').value ="";
getObj('requisiones_pr_unidad_medida').value ="";
getObj('requisiones_pr_numero').value="";
/*getObj('requisiones_pr_accion_especifica').disabled="disabled"
getObj('requisiciones_pr_accion_especifica_codigo').disabled="disabled";
*/
 	} );
//---------------------------------------------------------------------------------------------------------------------------------
$('#requisiciones_pr_codigo_central').change(consulta_automatica_accion_central);
$('#requisiones_pr_codigo_proyecto').change(consulta_automatica_proyecto);
$('#requisiciones_pr_accion_especifica_codigo').change(consulta_automatica_accion_especifica);


$('#requisiones_pr_requisiones_pr_asunto').alpha({allow:'áéíóúÁÉÍÓÚ 0123456789'});
$('#requisiones_pr_producto').alpha({allow:'áéíóúÁÉÍÓÚ, '});
$('#requisiones_pr_cantidad').numeric({allow:''});


</script>
<script type="text/javascript">

$("#list_requisiones").jqGrid
({ 
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/adquisiones/requisiciones/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Nº Renglon','Descripcion','Cantidad','id_unidad_medida','Unidad Medida','numero_requision','Eliminar'],
   	colModel:[
	   		{name:'id_requisicion_detalle',index:'id_requisicion_detalle', width:50,hidden:true},
	   		{name:'n_renglon',index:'n_renglon', width:50},
			{name:'descripcion',index:'descripcion', width:200,editable:true},
			{name:'cantidad',index:'cantidad', width:80,editable:true,editrules:{number:true}},
			{name:'id_unidad_medida',index:'id_unidad_medida', width:200,hidden:true},
			{name:'nombre',index:'nombre', width:100},
			{name:'numero_requision',index:'numero_requision', width:50,hidden:true},
			{name:'id_detalle',index:'id_detalle', width:50,hidden:true}
   	],
	pager: jQuery('#pager_requisiones'),
   	rowNum:6,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id_requisicion_detalle',
    viewrecords: true,
    sortorder: "asc",
	forceFit : true,
	cellEdit: true
})/*.navGrid('#pager_requisiones',
{edit:false,add:false,refresh:false,search:false}, //options
{} // search options
)*/;
var timeoutHnd; 
var flAuto = true;

function proyecto_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(proyecto_gridReload,150)
} 

function requisioes_gridReload()
{ 
	jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta.php",page:1}).trigger("reloadGrid"); 
} 

$("#requisiones_pr_btn_pdf").click(function(){

	requisiones_ano = getObj('requisiones_pr_ano').value;
	numero_requi = getObj('requisiones_pr_numero_requision').value;
	//url="pdf.php?p=modulos/adquisiones/requisiciones/rp/vista.lst.requisicion.php¿numero_requi="+numero_requi"+§ano="requisiones_ano; 
	url="pdf.php?p=modulos/adquisiones/requisiciones/rp/vista.lst.requisicion.php¿ano="+requisiones_ano+"@numero_requi="+numero_requi; 
	//alert(url);
	openTab("Requisicion",url);
});
	/*
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#proyecto_co_btn_consultar").attr("enable",state); 
}*/
	
	
$('#proyecto_busqueda_proyecto').alpha({nocaps:true,allow:'´'});
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
	<img id="requisiones_pr_btn_cancelar" class="btn_cancelar"  src="imagenes/null.gif" />
	<img id="requisiones_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="requisiones_pr_btn_consultar" name="requisiones_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="requisiones_pr_btn_pdf" class="btn_imprimir"src="imagenes/null.gif"  />		
</div>

<form method="post" name="form_pr_requisiones" id="form_pr_requisiones">
<input type="hidden" name="requisiones_pr_numero_requision" id="requisiones_pr_numero_requision" value=""  />
<table class="cuerpo_formulario">
  <tr>
    <th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Crear Requisici&oacute;n  </tr>
        <th width="50%"> A&ntilde;o	
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
        <td width="50%"><select name="requisiones_pr_ano" id="requisiones_pr_ano" style="min-width:60px; width:60px;">
      <option value="<?= date('Y')-1 ;?>">
        <?= date('Y')-1 ;?>
        </option>
      <option value="<?= date('Y');?>" selected="selected">
        <?= date('Y');?>
        </option>
    </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Nº Requisición
          <input name="requisiones_pr_numero"  type="text" id="requisiones_pr_numero"  maxlength="10" readonly="readonly"
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>   </td>
  </tr>
  <tr>
    <th>Proyecto</th>
    <td>
	<ul class="input_con_emergente">
		<li>
				<input name="requisiones_pr_codigo_proyecto" type="text" id="requisiones_pr_codigo_proyecto"  value="" size="5" maxlength="4" 
				onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto" message="Introduzca un Codigo para el Proyecto."
				jVal="{valid:/^[0-9]{4}$/, message:'Codigo Invalido', styleType:'cover'}">	
			
				<input type="text" name="requisiones_pr_proyecto" id="requisiones_pr_proyecto"  
						style="width:62ex;" maxlength="60"  message="Introduzca el Nombre del Proyecto." />
				<input type="hidden" name="requisiones_pr_proyecto_id" id="requisiones_pr_proyecto_id" />
		</li>
		<li id="requisiones_pr_btn_consultar_proyecto" class="btn_consulta_emergente"></li>
	</ul>  </tr><tr>
    <th>Acci&oacute;n Central</th>
    <td>
	<ul class="input_con_emergente">
		<li>			
				<input name="requisiciones_pr_codigo_central" type="text" id="requisiciones_pr_codigo_central"  maxlength="4" size="5"
				onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
       			message="Introduzca codigo de  la accion central." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,4}$/, message:'Nombre Invalido', styleType:'cover'}"
				/>
       			 <input type="text" name="requisiones_pr_accion_central" id="requisiones_pr_accion_central" 
				style="width:62ex;" maxlength="60"  message="Introduzca el Nombre del Proyecto." />
       			 <input type="hidden" name="requisiones_pr_accion_central_id" id="requisiones_pr_accion_central_id" />
        </li>
		<li id="requisiones_pr_btn_consultar_accion_central" class="btn_consulta_emergente"></li>
	</ul>	</td>
  </tr>
  <tr>
 
    <th>Acci&oacute;n Espec&iacute;fica</th>
    <td>
	 <ul class="input_con_emergente">
		<li>	
		<input name="requisiciones_pr_accion_especifica_codigo" type="text" id="requisiciones_pr_accion_especifica_codigo"  maxlength="4" size="5"
				onchange="consulta_automatica_accion_especifica" onclick="consulta_automatica_accion_especifica" 
            	message="Introduzca codigo de la acción específica." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,4}$/, message:'Nombre Invalido', styleType:'cover'}"
				/>
			
		<input type="text" name="requisiones_pr_accion_especifica" id="requisiones_pr_accion_especifica" 
				style="width:62ex;" maxlength="60" message="Introduzca la Acci&oacute;n Espec&iacute;fica." 
				jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Acci&oacute;n Espec&iacute;fica Invalida', styleType:'cover'}"
					/>
        <input type="hidden" name="requisiones_pr_accion_especifica_id" id="requisiones_pr_accion_especifica_id" />
        </li>
		<li id="requisiones_pr_btn_consultar_accion_especifica" class="btn_consulta_emergente"></li>
	</ul>	</td>
  </tr>
  <tr>
    <th>Asunto</th>
    <td><textarea name="requisiones_pr_asunto" cols="65" rows="2" id="requisiones_pr_asunto" 
				message="Introduzca la Asunto del cual trata la requisici&oacute;n."
				jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789]{2,120}$/, message:'Asunto Invalido', styleType:'cover'}"
				></textarea></td>
  </tr>
  <tr>
    <th colspan="2"> <table class="clear" width="100%" border="0">
      <tr>
        <th>Tipo de gasto </th>
        <td><select name="requisiones_pr_tipo_doc" id="requisiones_pr_tipo_doc" style="min-width:150px; width:150px;" >
          <? //=$opt_tipo_doc;?>
          <option value="" >----SELECCIONE---</option>
          <option value="" >Insumos</option>
          <option value="" >Activos Reales</option>
          <option value="" >Servicios</option>
        </select>        </td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th>Observaci&oacute;n</th>
    <td><textarea name="requisiones_pr_obesrvacion" id="requisiones_pr_obesrvacion" cols="65" rows="2" 
				message="Introduzca un observacion para la requisici&oacute;n."></textarea>    </td>
  </tr>
  <tr>
    <th colspan="2">&nbsp;</th>
  </tr>
  <tr>
    <th colspan="2"> <table class="clear" width="100%" border="0">
      <tr>
        <th>Descripci&oacute;n</th>
        <td colspan="3"><textarea name="requisiones_pr_producto"  id="requisiones_pr_producto" 
							message="Introduzca el nombre del Producto/Servicio" cols="65" rows="1"
							jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789]{2,120}$/, message:'Producto/Servicio Invalido', styleType:'cover'}"
							jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789]/, cFunc:'alert', cArgs:['Producto/Servicio: '+$(this).val()]}"></textarea>        </td>
      </tr>
      <tr>
        <th colspan="2">Partida</th>
        <th style="text-align:center;">Cantidad</th>
        <th style="text-align:center;">Unidad de Medida</th>
      </tr>
      <tr>
        <td colspan="2"><input type="text" name="requisiones_pr_partida" id="requisiones_pr_partida"
							message="Introduzca la partida." maxlength="11" size="12" />
              <img id="requisiones_pr_btn_partida" class="btn_consulta_emergente" src="imagenes/null.gif"  /> </td>
        <td width="23%" align="center"><input type="text" name="requisiones_pr_cantidad" id="requisiones_pr_cantidad"
							message="Introduzca la cantidad del producto." maxlength="8" size="10"
							jval="{valid:/^[0123456789]{1,10}$/, message:'Producto Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['Producto: '+$(this).val()]}" />        </td>
        <td width="23%" align="center"><select name="requisiones_pr_unidad_medida" id="requisiones_pr_unidad_medida" style="min-width:150px; width:150px;">
          <option value="0">--------SELECCIONE--------</option>
          <?=$opt_unida_medida;?>
        </select>        </td>
        <td width="4%"><img id="requisiones_pr_btn_anadir" src="imagenes/anadir.png"   /> </td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <td class="celda_consulta" colspan="2"><table id="list_requisiones" class="scroll" cellpadding="0" cellspacing="0" >
    </table>
        <div id="pager_requisiones" class="scroll" style="text-align:center;"></div></td>
  </tr>
  <tr>
    <td colspan="2" class="bottom_frame">&nbsp;</td>
  </tr>
</table>
</form>
