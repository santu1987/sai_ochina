<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM unidad_ejecutora WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY nombre";
$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_organismo.="<option value='".$rs_modulo->fields("id_unidad_ejecutora")."' >".$rs_modulo->fields("nombre")."</option>";
	$rs_modulo->MoveNext();
}

?>
<script>
var dialog;
$("#traspaso_entre_partida_db_btn_guardar").click(function() {
	if($('#form_db_traspaso_entre_partida').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/traspaso_entre_partida/db/sql.reprogramar.php",
			data:dataForm('form_db_traspaso_entre_partida'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_db_traspaso_entre_partida');
					getObj('traspaso_entre_partida_db_monto').value =					'0,00';
					getObj('traspaso_entre_partida_db_monto_cedente').value =   		'0,00';
					getObj('traspaso_entre_partida_db_monto_total').value =				'0,00';
					getObj('traspaso_entre_partida_db_monto_actual_receptor').value =   '0,00';
					getObj('traspaso_entre_partida_db_monto_total_receptor').value =	'0,00';
					getObj('traspaso_entre_partida_db_mes_cendente').value =	0;
					getObj('traspaso_entre_partida_db_mes_receptor').value =	0;
					getObj('traspaso_entre_partida_db_partida_numero').value ="";
					getObj('traspaso_entre_partida_db_partida').value ="";
					getObj('traspaso_entre_partida_db_partida_numero_receptor').value ="";
					getObj('traspaso_entre_partida_db_partida_receptor').value ="";
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}//setBarraEstado(html);
			}
		});
	}
});
$("#traspaso_entre_partida_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:1100,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/traspaso_entre_partida/db/sql_grid_traspaso_entre_partida.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora').value,
								datatype: "json",
								colNames:['id','id_unidad_cedente','id_proyecto_cedente', 'id_accion_centralizada_cedente', 'id_accion_especifica_cedente','id_accion_centralizada_receptora', 'Mes Cedente','Partida Cedente', 'Unidad Cedente', 'Acci&oacute;n Espec&iacute;fica','unidad_receptora', 'proyecto_receptora','id_accion_centralizada_receptora', 'id_accion_especifica_receptora', 'Monto Transferido', 'Mes Receptor','Partida Recive', 'Unidad Recive', 'Accion Espec&iacute;fica Recive','partida', 'partidas','accion_central_cede','proyecto','accion_central_cede2','proyecto2','monto_actual','monto_actual_re'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_unidad_cedente',index:'id_unidad_cedente', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_proyecto_cedente',index:'id_proyecto_cedente', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_centralizada_cedente',index:'id_accion_centralizada_cedente', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica_cedente',index:'id_accion_especifica_cedente', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'mes_cedente',index:'mes_cedente', width:20,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:60,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:40,sortable:false,resizable:false},
									{name:'accion_especifica',index:'accion_especifica', width:50,sortable:false,resizable:false},
									{name:'id_unidad_receptora',index:'id_unidad_receptora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_proyecto_receptora',index:'id_proyecto_receptora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_centralizada_receptora',index:'id_accion_centralizada_receptora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica_receptora',index:'id_accion_especifica_receptora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'monto_receptora',index:'monto_receptora', width:25,sortable:false,resizable:false},
									{name:'mes_receptora',index:'mes_receptora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'denominacion_re',index:'denominacion_re', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:50,sortable:false,resizable:false,hidden:true},
									{name:'accion_especifica_re',index:'accion_especifica_re', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partidas',index:'partidas', width:50,sortable:false,resizable:false,hidden:true},
									{name:'accion_central_cede',index:'accion_central_cede', width:50,sortable:false,resizable:false,hidden:true},
									{name:'proyecto_cede',index:'proyecto_cede', width:50,sortable:false,resizable:false,hidden:true},
									{name:'accion_central_rece',index:'accion_central_rece', width:50,sortable:false,resizable:false,hidden:true},
									{name:'proyecto_rece',index:'proyecto_rece', width:50,sortable:false,resizable:false,hidden:true},
									{name:'monto_actual',index:'monto_actual', width:40,sortable:false,resizable:false,hidden:true},
									{name:'monto_actual_re',index:'monto_actual_re', width:40,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),///   monto_actual
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('traspaso_entre_partida_db_unidad_ejecutora').value = ret.id_unidad_cedente;
									getObj('traspaso_entre_partida_db_accion_central_id').value = ret.id;
									getObj('traspaso_entre_partida_db_partida').value = ret.denominacion;
									getObj('traspaso_entre_partida_db_proyecto_id').value = ret.id_proyecto_cedente;
									//getObj('traspaso_entre_partida_db_proyecto').value = ret.id_proyecto_cedente;
									getObj('traspaso_entre_partida_db_accion_especifica_id').value = ret.id_accion_especifica_cedente;
									getObj('traspaso_entre_partida_db_accion_especifica').value = ret.accion_especifica;
									getObj('traspaso_entre_partida_db_comentario').value = ret.comentario;
									getObj('traspaso_entre_partida_db_mes_cendente').value = ret.mes_cedente;
									getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value = ret.id_unidad_receptora;
									getObj('traspaso_entre_partida_db_proyecto_id_receptor').value = ret.id_proyecto_receptora;
									//getObj('traspaso_entre_partida_db_accion_central_receptor').value = ret.id_accion_centralizada_receptora; 
									getObj('traspaso_entre_partida_db_accion_central_id_receptor').value = ret.id_accion_centralizada_receptora;
									getObj('traspaso_entre_partida_db_accion_especifica_id_receptor').value = ret.id_accion_especifica_receptora;
									getObj('traspaso_entre_partida_db_accion_especifica_receptor').value = ret.accion_especifica_re;
									getObj('traspaso_entre_partida_db_monto_cedente').value = ret.monto_receptora;
									//getObj('traspaso_entre_partida_db_monto_receptor').value = ret.monto_receptora;
									getObj('traspaso_entre_partida_db_partida_receptor').value = ret.denominacion_re;
									getObj('traspaso_entre_partida_db_partida_numero').value = ret.partida;
									getObj('traspaso_entre_partida_db_partida_numero_receptor').value = ret.partidas;
									getObj('traspaso_entre_partida_db_mes_receptor').value = ret.mes_receptora;
									getObj('traspaso_entre_partida_db_accion_central').value = ret.accion_central_cede;
									getObj('traspaso_entre_partida_db_proyecto').value = ret.proyecto_cede;
									getObj('traspaso_entre_partida_db_accion_central_receptor').value = ret.accion_central_rece;
									getObj('traspaso_entre_partida_db_proyecto_receptor').value = ret.proyecto_rece; 
									getObj('traspaso_entre_partida_db_monto').value = ret.monto_actual; 
									getObj('traspaso_entre_partida_db_monto_actual_receptor').value = ret.monto_actual_re; 
									
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
								sortname: 'id_traspaso_entre_partida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
						//getObj("traspaso_entre_partida_db_monto").value = update_monto_mes;
						//$("#traspaso_entre_partida_db_monto_actual_receptor").value = update_monto_mes;

});
// ------------------------------------------------------ Accion Central -----------------------------------------------------------------------------
$("#traspaso_entre_partida_db_btn_consultar_accion_central").click(function() {
if(getObj('traspaso_entre_partida_db_unidad_ejecutora').value  !=0 && getObj('traspaso_entre_partida_db_proyecto_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_central.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Accion Central'],
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
									getObj('traspaso_entre_partida_db_accion_central_id').value = ret.id;
									getObj('traspaso_entre_partida_db_codigo_central').value = ret.codigo;
									getObj('traspaso_entre_partida_db_accion_central').value = ret.denominacion;
									getObj('traspaso_entre_partida_db_proyecto_id').value = "";
									getObj('traspaso_entre_partida_db_codigo_proyecto').value = "0000";
									getObj('traspaso_entre_partida_db_codigo_proyecto').disabled="disabled" ;
									getObj('traspaso_entre_partida_db_proyecto').value = "  NO APLICA ESTA OPCION ";
									
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
								sortname: 'codigo_accion_central',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
function consulta_automatica_accion_central()
{ 
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/db/sql_grid_accion_cental_codigo.php",
            data:dataForm('form_db_traspaso_entre_partida'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('traspaso_entre_partida_db_accion_central_id').value = recordset[0];
				getObj('traspaso_entre_partida_db_accion_central').value=recordset[1];
				getObj('traspaso_entre_partida_db_proyecto_id').value="";
				getObj('traspaso_entre_partida_db_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('traspaso_entre_partida_db_codigo_proyecto').value ="0000" ;
				getObj('traspaso_entre_partida_db_codigo_proyecto').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('traspaso_entre_partida_db_accion_central_id').value ="";
			    getObj('traspaso_entre_partida_db_accion_central').value="";
				getObj('traspaso_entre_partida_db_proyecto_id').value="";
				getObj('modificacion_presupuesto_db_proyecto').value="";
				getObj('traspaso_entre_partida_db_codigo_proyecto').value ="" ;
				getObj('traspaso_entre_partida_db_codigo_proyecto').disabled="" ;
				}
			 }
		});	 	 
}
// ------------------------------------------------------ PROYECTO -----------------------------------------------------------------------------
$("#traspaso_entre_partida_db_btn_consultar_proyecto").click(function() {
if(document.form_db_traspaso_entre_partida.traspaso_entre_partida_db_unidad_ejecutora.selectedIndex  !=0  && document.form_db_traspaso_entre_partida.traspaso_entre_partida_db_accion_central_id.value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.proyecto.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Proyecto'],
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
									getObj('traspaso_entre_partida_db_proyecto_id').value = ret.id;
									getObj('traspaso_entre_partida_db_codigo_proyecto').value = ret.codigo;
									getObj('traspaso_entre_partida_db_proyecto').value = ret.denominacion;
									
									getObj('traspaso_entre_partida_db_accion_central_id').value = "";
									getObj('traspaso_entre_partida_db_codigo_central').value = "0000";
									getObj('traspaso_entre_partida_db_codigo_central').disabled="disabled" ;
									getObj('traspaso_entre_partida_db_accion_central').value = "  NO APLICA ESTA OPCION ";									
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
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/db/sql_grid_proyecto_codigo.php",
            data:dataForm('form_db_traspaso_entre_partida'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('traspaso_entre_partida_db_proyecto_id').value = recordset[0];
				getObj('traspaso_entre_partida_db_proyecto').value=recordset[1];
				getObj('traspaso_entre_partida_db_accion_central_id').value="";
				getObj('traspaso_entre_partida_db_accion_central').value="  NO APLICA ESTA OPCION  ";
				getObj('traspaso_entre_partida_db_codigo_central').value ="0000" ;
				getObj('traspaso_entre_partida_db_codigo_central').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('traspaso_entre_partida_db_proyecto_id').value ="";
			    getObj('traspaso_entre_partida_db_proyecto').value="";
				getObj('traspaso_entre_partida_db_accion_central').value="";
				getObj('traspaso_entre_partida_db_accion_central').value ="" ;
				getObj('traspaso_entre_partida_db_accion_central_id').value ="";
				getObj('traspaso_entre_partida_db_accion_central').disabled="" ;
				}
			 }
		});	 	 
}
// ------------------------------------------------------ Accion Especifica -----------------------------------------------------------------------------

$("#traspaso_entre_partida_db_btn_consultar_accion_especifica").click(function() {

if(getObj('traspaso_entre_partida_db_proyecto_id').value !="" || getObj('traspaso_entre_partida_db_accion_central_id').value !="")
{
	if (getObj('traspaso_entre_partida_db_accion_central_id').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_central.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora').value+'&accion='+getObj('traspaso_entre_partida_db_accion_central_id').value;
	}
	if (getObj('traspaso_entre_partida_db_proyecto_id').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_proyecto.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora').value+'&proyecto='+getObj('traspaso_entre_partida_db_proyecto_id').value;
	}
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								colNames:['id','Codigo', 'Accion Especifica'],
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
									getObj('traspaso_entre_partida_db_accion_especifica_id').value = ret.id;
									getObj('traspaso_entre_partida_db_codigo_especifica').value = ret.codigo;
									getObj('traspaso_entre_partida_db_accion_especifica').value = ret.denominacion;
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
//****************************************************
function consulta_automatica_especifica()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/db/sql_grid_accion_especifica.php",
            data:dataForm('form_db_traspaso_entre_partida'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('traspaso_entre_partida_db_accion_especifica_id').value = recordset[0];
				getObj('traspaso_entre_partida_db_accion_especifica').value=recordset[1];
				getObj('traspaso_entre_partida_db_codigo_especifica').value=recordset[2];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('traspaso_entre_partida_db_accion_especifica_id').value ="";
			    getObj('traspaso_entre_partida_db_accion_especifica').value="";
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------
$("#traspaso_entre_partida_db_btn_consultar_partida").click(function() {
if(getObj('traspaso_entre_partida_db_proyecto_id').value !="" || getObj('traspaso_entre_partida_db_accion_central_id').value !="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.partida.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora').value+'&unidad_es='+getObj('traspaso_entre_partida_db_accion_especifica_id').value,
								datatype: "json",
								colNames:['Id','Partida', 'Descripci&oacute;n','Monto'],
								colModel:[
									{name:'id_clasi_presu',index:'id_clasi_presu', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('traspaso_entre_partida_db_partida_numero').value = ret.partida;
									getObj('traspaso_entre_partida_db_partida').value = ret.denominacion;
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
//-------------------------------- RECEPTOR ---------------------------------------------------------
$("#traspaso_entre_partida_db_btn_consultar_accion_central_receptor").click(function() {
if(getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value  !=0 && getObj('traspaso_entre_partida_db_proyecto_id_receptor').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_central.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Accion Central'],
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
									getObj('traspaso_entre_partida_db_accion_central_id_receptor').value = ret.id;
									getObj('traspaso_entre_partida_db_codigo_central_receptor').value = ret.codigo;
									getObj('traspaso_entre_partida_db_accion_central_receptor').value = ret.denominacion;
									getObj('traspaso_entre_partida_db_proyecto_id_receptor').value="";
									getObj('traspaso_entre_partida_db_proyecto_receptor').value="  NO APLICA ESTA OPCION  ";
									getObj('traspaso_entre_partida_db_codigo_proyecto_receptor').value ="0000" ;
									getObj('traspaso_entre_partida_db_codigo_proyecto_receptor').disabled="disabled" ;									
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
								sortname: 'denominacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
function consulta_automatica_accion_central_receptor()
{ 
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/db/sql_grid_accion_cental_codigo_receptor.php",
            data:dataForm('form_db_traspaso_entre_partida'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('traspaso_entre_partida_db_accion_central_id_receptor').value = recordset[0];
				getObj('traspaso_entre_partida_db_accion_central_receptor').value=recordset[1];
				getObj('traspaso_entre_partida_db_proyecto_id_receptor').value="";
				getObj('traspaso_entre_partida_db_proyecto_receptor').value="  NO APLICA ESTA OPCION  ";
				getObj('traspaso_entre_partida_db_codigo_proyecto_receptor').value ="0000" ;
				getObj('traspaso_entre_partida_db_codigo_proyecto_receptor').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('traspaso_entre_partida_db_accion_central_id_receptor').value ="";
			    getObj('traspaso_entre_partida_db_accion_central_receptor').value="";
				getObj('traspaso_entre_partida_db_proyecto_id_receptor').value="";
				getObj('traspaso_entre_partida_db_proyecto_receptor').value="";
				getObj('traspaso_entre_partida_db_codigo_proyecto_receptor').value ="" ;
				getObj('traspaso_entre_partida_db_codigo_proyecto_receptor').disabled="" ;
				}
			 }
		});	 	 
} 
// -----------------------------------------------------------------------------------------------------------------------------------
$("#traspaso_entre_partida_db_btn_consultar_proyecto_receptor").click(function() {
if(document.form_db_traspaso_entre_partida.traspaso_entre_partida_db_unidad_ejecutora_receptor.selectedIndex  !=0  && document.form_db_traspaso_entre_partida.traspaso_entre_partida_db_accion_central_id_receptor.value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.proyecto.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Proyecto'],
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
									getObj('traspaso_entre_partida_db_proyecto_id_receptor').value = ret.id;
									getObj('traspaso_entre_partida_db_codigo_proyecto_receptor').value = ret.codigo;
									getObj('traspaso_entre_partida_db_proyecto_receptor').value = ret.denominacion;
									getObj('traspaso_entre_partida_db_accion_central_id_receptor').value="";
									getObj('traspaso_entre_partida_db_accion_central_receptor').value="  NO APLICA ESTA OPCION  ";
									getObj('traspaso_entre_partida_db_codigo_central_receptor').value ="0000" ;
									getObj('traspaso_entre_partida_db_codigo_central_receptor').disabled="disabled" ;
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
function consulta_automatica_proyecto_receptor()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/db/sql_grid_proyecto_codigo_receptor.php",
            data:dataForm('form_db_traspaso_entre_partida'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('traspaso_entre_partida_db_proyecto_id_receptor').value = recordset[0];
				getObj('traspaso_entre_partida_db_proyecto_receptor').value=recordset[1];
				getObj('traspaso_entre_partida_db_accion_central_id_receptor').value="";
				getObj('traspaso_entre_partida_db_accion_central_receptor').value="  NO APLICA ESTA OPCION  ";
				getObj('traspaso_entre_partida_db_codigo_central_receptor').value ="0000" ;
				getObj('traspaso_entre_partida_db_codigo_central_receptor').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('traspaso_entre_partida_db_proyecto_id_receptor').value ="";
			    getObj('traspaso_entre_partida_db_proyecto_receptor').value="";
				getObj('traspaso_entre_partida_db_accion_central_id_receptor').value="";
				getObj('traspaso_entre_partida_db_accion_central_receptor').value ="" ;
				getObj('traspaso_entre_partida_db_codigo_central_receptor').value ="";
				getObj('traspaso_entre_partida_db_codigo_central_receptor').disabled="" ;
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------

$("#traspaso_entre_partida_db_btn_consultar_accion_especifica_receptor").click(function() {

if(getObj('traspaso_entre_partida_db_proyecto_id_receptor').value !="" || getObj('traspaso_entre_partida_db_accion_central_id_receptor').value !="")
{
	if (getObj('traspaso_entre_partida_db_accion_central_id_receptor').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_central.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value+'&accion='+getObj('traspaso_entre_partida_db_accion_central_id_receptor').value;
	//	alert(urls);
	}
	if (getObj('traspaso_entre_partida_db_proyecto_id_receptor').value !=""){
		urls='modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.accion_especifica_proyecto.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value+'&proyecto='+getObj('traspaso_entre_partida_db_proyecto_id_receptor').value;
	//	alert(urls);

	}
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
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
								colNames:['Id','Codigo', 'Accion Especifica'],
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
									getObj('traspaso_entre_partida_db_accion_especifica_id_receptor').value = ret.id;
									getObj('traspaso_entre_partida_db_codigo_especifica_receptor').value = ret.codigo;
									getObj('traspaso_entre_partida_db_accion_especifica_receptor').value = ret.denominacion;
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
function consulta_automatica_especifica_receptor()
{
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/db/sql_grid_accion_especifica_receptor.php",
            data:dataForm('form_db_traspaso_entre_partida'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('traspaso_entre_partida_db_accion_especifica_id_receptor').value = recordset[0];
				getObj('traspaso_entre_partida_db_accion_especifica_receptor').value=recordset[1];
				getObj('traspaso_entre_partida_db_codigo_especifica_receptor').value=recordset[2];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('traspaso_entre_partida_db_accion_especifica_id_receptor').value ="";
			    getObj('traspaso_entre_partida_db_accion_especifica_receptor').value="";
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------
$("#traspaso_entre_partida_db_btn_consultar_partida_receptor").click(function() {
if(getObj('traspaso_entre_partida_db_proyecto_id').value !="" || getObj('traspaso_entre_partida_db_accion_central_id').value !="")
{
	var nd=new Date().getTime();
	alert('modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.partida.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora').value+'&unidad_es='+getObj('traspaso_entre_partida_db_accion_especifica_id').value)
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.partida.php?nd='+nd+'&unidad='+getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value+'&unidad_es='+getObj('traspaso_entre_partida_db_accion_especifica_id_receptor').value,
								datatype: "json",
								colNames:['Id','Partida', 'Descripci&oacute;n','Monto'],
								colModel:[
									{name:'id_clasi_presu',index:'id_clasi_presu', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('traspaso_entre_partida_db_partida_numero_receptor').value = ret.partida;
									getObj('traspaso_entre_partida_db_partida_receptor').value = ret.denominacion;
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
// --------------------------------------- FIN RECEPTOR ------------------------------------------------
/* ------------------ Código jquery_moneda   ---------------------------*/
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

	obj.value =""; //necessário para o opera
	obj.value += y;
	
	if (event.preventDefault){ //standart browsers
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	return false;

	}// end if		
}// end backspace

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


if (e.preventDefault){ //standart browsers
		e.preventDefault()
	}else{ // internet explorer
		e.returnValue = false
}

var key = String.fromCharCode(whichCode);  // Valor para o código da Chave
if (strCheck.indexOf(key) == -1) return false;  // Chave inválida

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
/*------------------- Fin del Código jquery_moneda ---------------------*/
/*-------------------   Fin Validaciones  ---------------------------*/
function convertir(moneda)
{
	tam = moneda.length;
	for (i=1; i<=3; i++){
	pos = moneda.indexOf(".");
	moneda = moneda.substr(0,pos) + moneda.substr(pos+1,tam);
	}
	moneda = moneda.replace(",",".");
	return moneda;
}
function totalconv(valor)
{
	tam= valor.length;
	decimal= valor.substr(tam-3,3);
	valor= valor.substr(0,tam-3);
	tam2= valor.length;
	valor1= parseInt(valor);
	// calcula el reciduo
	valor1= valor1%3;
	//calcular posiciones
	valor2= tam2/3;
	var cad="";
	cont= 3;
	res=3;
	if (valor1==0){valor2=valor2+1;}
	for(i=0; i<=valor2; i++){
		if (tam2<4){
			res=0;
			cont=tam2;
			tam2=0;
			}
		cad= valor.substr(tam2-res,cont) + cad;
		if(tam2>=4){ 
		cad= "."+ cad;
		}
		tam2= tam2-3;

	}
	cad = cad + decimal;
	return cad;
}


/*-------------------   Fin SUMA  ---------------------------*/
/*-------------------   MES  ---------------------------*/
$("#traspaso_entre_partida_db_btn_consultar_mes").click(function() {
//alert('aqui');
if(getObj('traspaso_entre_partida_db_nombre_unidad_ejecutora').value  !=""  && getObj('traspaso_entre_partida_db_proyecto').value !=""  && getObj('traspaso_entre_partida_db_accion_central').value !=""  && getObj('traspaso_entre_partida_db_partida_numero').value !="")
{
	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
		alert("modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual.php?unidad="+getObj('traspaso_entre_partida_db_unidad_ejecutora').value+"&unidad_es="+getObj('traspaso_entre_partida_db_accion_especifica_id').value+"&partida_toda="+getObj('traspaso_entre_partida_db_partida_numero').value+"&proyecto="+getObj('traspaso_entre_partida_db_proyecto_id').value+"&accion_central="+getObj('traspaso_entre_partida_db_accion_central_id').value+"&ano="+getObj('traspaso_entre_partida_db_ano').value);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url: "modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual.php?unidad="+getObj('traspaso_entre_partida_db_unidad_ejecutora').value+"&unidad_es="+getObj('traspaso_entre_partida_db_accion_especifica_id').value+"&partida_toda="+getObj('traspaso_entre_partida_db_partida_numero').value+"&proyecto="+getObj('traspaso_entre_partida_db_proyecto_id').value+"&accion_central="+getObj('traspaso_entre_partida_db_accion_central_id').value+"&ano="+getObj('traspaso_entre_partida_db_ano').value,
								datatype: "json",
								colNames:['id','Mes', 'Presupuesto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false},
									{name:'mes',index:'mes', width:50,sortable:false,resizable:false},
									{name:'presupuesto',index:'presupuesto', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//alert(ret.id);
									getObj('traspaso_entre_partida_db_mes_cendente').value = ret.mes;
									getObj('traspaso_entre_partida_db_monto').value = ret.presupuesto;

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
								sortname: 'mes',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
/*
function update_monto_mes()
{
	$.ajax ({
			url: "modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual.php",
			data:dataForm('form_db_traspaso_entre_partida'),
			type:'POST',
			cache: false,
			success: function(html)
			{
					var monto = html;
					getObj('traspaso_entre_partida_db_monto').value = monto;
					//setBarraEstado(html);
			}
		});
}*/
function update_monto_mes_r()
{
	$.ajax ({
			url: "modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.monto_mensual_receptor.php",
			data:dataForm('form_db_traspaso_entre_partida'),
			type:'POST',
			cache: false,
			success: function(html)
			{
					var monto = html;
					//alert(html);
					getObj('traspaso_entre_partida_db_monto_actual_receptor').value = monto;
					monto_total = getObj('traspaso_entre_partida_db_monto_cedente').value.float() + getObj('traspaso_entre_partida_db_monto_actual_receptor').value.float();
					monto_total = monto_total.currency(2,',','.');	
					getObj('traspaso_entre_partida_db_monto_total_receptor').value = monto_total;
			}
		});
}
function montoreceptor()
{
	var monto_total;
	if (getObj('traspaso_entre_partida_db_monto').value.float() >= getObj('traspaso_entre_partida_db_monto_cedente').value.float())
	{
		monto_total = getObj('traspaso_entre_partida_db_monto').value.float() - getObj('traspaso_entre_partida_db_monto_cedente').value.float();
		monto_total = monto_total.currency(2,',','.');	
		getObj('traspaso_entre_partida_db_monto_total').value = monto_total;
	}
	else{
		getObj('traspaso_entre_partida_db_monto_cedente').value="0,00";
		monto_total = getObj('traspaso_entre_partida_db_monto').value.float();
		monto_total = monto_total.currency(2,',','.');	
		getObj('traspaso_entre_partida_db_monto_total').value = monto_total;
		setBarraEstado(mensaje[monto_cedente_superior],true,true);
	}
	
}//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$("#traspaso_entre_partida_db_btn_consultar_unidad_ejecutora").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/traspaso_entre_partida/db/grid_traspaso_entre_partida.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/traspaso_entre_partida/db/cmb.sql.reprogramacion_unidad_ejecutora.php?nd='+nd+'&ano='+getObj('traspaso_entre_partida_db_ano').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Unidad Ejecutora'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:350,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('traspaso_entre_partida_db_unidad_ejecutora').value = ret.id;
									getObj('traspaso_entre_partida_db_codigo_unidad').value = ret.codigo;
									getObj('traspaso_entre_partida_db_nombre_unidad_ejecutora').value = ret.nombre;
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
								sortname: 'codigo_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_unidad()
{ 
	$.ajax({
			url:"modulos/presupuesto/traspaso_entre_partida/db/sql_grid_reprogramacion_unidad.php",
            data:dataForm('form_db_traspaso_entre_partida'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('traspaso_entre_partida_db_unidad_ejecutora').value = recordset[0];
				getObj('traspaso_entre_partida_db_nombre_unidad_ejecutora').value=recordset[1];
				}
				else
			 {  
			   	getObj('traspaso_entre_partida_db_unidad_ejecutora').value ="";
			    getObj('traspaso_entre_partida_db_nombre_unidad_ejecutora').value="";
				}
			 }
		});	 	 
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$("#traspaso_entre_partida_db_codigo_unidad").change(consulta_automatica_unidad);
$("#traspaso_entre_partida_db_mes_cendente").change(update_monto_mes);
$("#traspaso_entre_partida_db_mes_receptor").change(update_monto_mes_r);

$("#traspaso_entre_partida_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('traspaso_entre_partida_db_btn_cancelar').style.display='';
	getObj('traspaso_entre_partida_db_btn_guardar').style.display='';
	clearForm('form_db_traspaso_entre_partida');
	getObj('traspaso_entre_partida_db_monto').value =					'0,00';
	getObj('traspaso_entre_partida_db_monto_cedente').value =   		'0,00';
	getObj('traspaso_entre_partida_db_monto_total').value =				'0,00';
	getObj('traspaso_entre_partida_db_monto_actual_receptor').value =   '0,00';
	getObj('traspaso_entre_partida_db_monto_total_receptor').value =	'0,00';
	getObj('traspaso_entre_partida_db_mes_cendente').value =	0;
	getObj('traspaso_entre_partida_db_mes_receptor').value =	0;
	getObj('traspaso_entre_partida_db_unidad_ejecutora').value ="";
	getObj('traspaso_entre_partida_db_unidad_ejecutora_receptor').value =0;

	

});
/*-------------------   Inicio Validaciones  ---------------------------*//*
$('#traspaso_entre_partida_db_codigo_central').numeric({allow:''});
$('#traspaso_entre_partida_db_codigo_central').change(consulta_automatica_accion_central);
$('#traspaso_entre_partida_db_codigo_proyecto').numeric({allow:''});
$('#traspaso_entre_partida_db_codigo_proyecto').change(consulta_automatica_proyecto);
$('#traspaso_entre_partida_db_codigo_especifica').numeric({allow:''});
$('#traspaso_entre_partida_db_codigo_especifica').change(consulta_automatica_especifica);
$('#traspaso_entre_partida_db_monto_cedente').numeric({allow:','});

$('#traspaso_entre_partida_db_codigo_central_receptor').numeric({allow:''});
$('#traspaso_entre_partida_db_codigo_central_receptor').change(consulta_automatica_accion_central_receptor);
$('#traspaso_entre_partida_db_codigo_proyecto_receptor').numeric({allow:''});
$('#traspaso_entre_partida_db_codigo_proyecto_receptor').change(consulta_automatica_proyecto_receptor);
$('#traspaso_entre_partida_db_codigo_especifica_receptor').numeric({allow:''});
$('#traspaso_entre_partida_db_codigo_especifica_receptor').change(consulta_automatica_especifica_receptor);
*/

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
////
////
/*-------------------   Fin Validaciones  ---------------------------*/

</script>

<div id="botonera">
	<img id="traspaso_entre_partida_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="traspaso_entre_partida_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"  />-->
	<img id="traspaso_entre_partida_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_db_traspaso_entre_partida" id="form_db_traspaso_entre_partida">
<input name="traspaso_entre_partida_db_id" id="traspaso_entre_partida_db_id" type="hidden" />
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/proceso28x28.png" style="padding-right:5px;" align="absmiddle" /> Reprogramacion de Presupuesto	</th>
		</tr>
		<tr>
			<th>A&ntilde;o:</th>
			<td>
				<select name="traspaso_entre_partida_db_ano" id="traspaso_entre_partida_db_ano" style="min-width:60px; width:60px;">
					<option value="<?=date('Y')-1;?>"><?=date('Y')-1;?></option>
					<option value="<?=date('Y');?>" selected="selected"><?=date('Y');?></option>
					<option value="<?=date('Y')+1;?>"><?=date('Y')+1;?></option>
				</select>
			</td>
		</tr>
		<tr>
	  <th colspan="2" align="center" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF; font-size:14px">Cedente	</td>	  </tr>
	  		<tr>
			<th>Unidad Ejecutora:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_codigo_unidad" type="text" id="traspaso_entre_partida_db_codigo_unidad"  maxlength="6"
						onchange="consulta_automatica_unidad" onclick="consulta_automatica_unidad"
						message="Introduzca un Codigo de la Unidad Solicitante."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					
						<input name="traspaso_entre_partida_db_nombre_unidad_ejecutora" type="text" id="traspaso_entre_partida_db_nombre_unidad_ejecutora"  style="width:60ex" maxlength="60"
						message="Introduzca una Denominacion para la Unidad Solicitante." 
						jVal="{valid:/^[a-zA-Z0-9 ,.áéíóúÁÉÍÓÚ 1234567890-_]{1,100}$/, message:'Denominacion Invalido', styleType:'cover'}"
						jValKey="{valid:/^[a-zA-Z0-9., áéíóúÁÉÍÓÚ 1234567890-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}">
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="traspaso_entre_partida_db_unidad_ejecutora" type="hidden" id="traspaso_entre_partida_db_unidad_ejecutora" />		
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Central:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_codigo_central" type="text" id="traspaso_entre_partida_db_codigo_central"  maxlength="6"
						onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
						message="Introduzca un Codigo para el Accion Central."  size="6"
						 >
					
						<input name="traspaso_entre_partida_db_accion_central" type="text" id="traspaso_entre_partida_db_accion_central"  style="width:60ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Central." 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_accion_central" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="traspaso_entre_partida_db_accion_central_id" type="hidden" id="traspaso_entre_partida_db_accion_central_id" />
			
			</td>
		</tr>
		<tr>
			<th>Proyecto:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_codigo_proyecto" type="text" id="traspaso_entre_partida_db_codigo_proyecto"  maxlength="9"
						onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
						message="Introduzca un Codigo para el Proyecto."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					
						<input name="traspaso_entre_partida_db_proyecto" type="text" id="traspaso_entre_partida_db_proyecto"  style="width:60ex" maxlength="60"
						message="Introduzca un Nombre para el Proyecto." readonly 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_proyecto" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="traspaso_entre_partida_db_proyecto_id" type="hidden" id="traspaso_entre_partida_db_proyecto_id" />
			
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Espec&iacute;fica:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_codigo_especifica" type="text" id="traspaso_entre_partida_db_codigo_especifica"  maxlength="9"
						onchange="consulta_automatica_especifica" onclick="consulta_automatica_especifica"
						message="Introduzca una Codigo para la Accion Especifica."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					
						<input name="traspaso_entre_partida_db_accion_especifica" type="text" id="traspaso_entre_partida_db_accion_especifica"  style="width:60ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Especifica." readonly 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_accion_especifica" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="traspaso_entre_partida_db_accion_especifica_id" type="hidden" id="traspaso_entre_partida_db_accion_especifica_id" />

			</td>
		</tr>
		<tr>
			<th>Partida:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_partida_numero" type="text" id="traspaso_entre_partida_db_partida_numero"  maxlength="12"
						
						message="Introduzca un Codigo para el Proyecto."  size="10" readonly >
					
						<input name="traspaso_entre_partida_db_partida" type="text" id="traspaso_entre_partida_db_partida"  style="width:58ex" maxlength="60"
						message="Introduzca un Nombre para el Proyecto." readonly 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_partida" class="btn_consulta_emergente"></li>
				</ul>
			</td>
		</tr>
		<tr>
			<th>Mes:</th>
			<td>
				 <ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_mes_cendente" type="text" id="traspaso_entre_partida_db_mes_cendente"  maxlength="12"
						message="Introduzca el mes cedente."  size="20" readonly >
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_mes" class="btn_consulta_emergente"></li>
				</ul>
				<!--<select name="traspaso_entre_partida_db_mes_cendente" id="traspaso_entre_partida_db_mes_cendente"  style="width:17ex;">
					<option value="0">--------SELECIONE--------</option>
					<option value="enero">Enero</option>
					<option value="febrero">Febrero</option>
					<option value="marzo">Marzo</option>
					<option value="abril">Abril</option>
					<option value="mayo">Mayo</option>
					<option value="junio">Junio</option>
					<option value="julio">Julio</option>
					<option value="agosto">Agosto</option>
					<option value="septiembre">Septiembre</option>
					<option value="octubre">Octubre</option>
					<option value="noviembre">Noviembre</option>
					<option value="diciembre">Diciembre</option>
				</select>	-->
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<table  width="100%" border="0">
					<tr>
						<th>Monto Actual</th>
						<td>
							<input  name="traspaso_entre_partida_db_monto" type="text" id="traspaso_entre_partida_db_monto"  
							size="15" readonly 	message="Monto Actual del Mes Cedente" 
							onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" value="0,00"  
							jVal="{valid:/^[0123456789,.]{1,15}$/, message:'Monto Invalida', styleType:'cover'}"
							jValKey="{valid:/[0123456789,.]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}"  style="text-align:right"/>												
						</td>
						<th>Monto</th>
						<td>
							<input name="traspaso_entre_partida_db_monto_cedente" type="text" 
							id="traspaso_entre_partida_db_monto_cedente" size="15" maxlength="15"  
							message="Introduzca el Monto Cedente." 
							jVal="{valid:/^[0123456789,.]{1,15}$/, message:'Monto Invalida', styleType:'cover'}"
							jValKey="{valid:/[0123456789,.]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}"
							onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" value="0,00" onkeyup="montoreceptor()" style="text-align:right" 
							 />						
						</td>
						<th>Monto Total</th>
						<td>
							<input name="traspaso_entre_partida_db_monto_total" type="text" 
							id="traspaso_entre_partida_db_monto_total" size="15"  readonly  style="text-align:right"
							 value="0,00"  
							/>						
						</td>
					</tr>
				</table>
		</td>		</tr>
		<tr>
		<th colspan="2" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF;font-size:14px">Receptora	</td>		
		</tr>
<tr>
			<th>Unidad Ejecutora:</th>
			<td>
				<select name="traspaso_entre_partida_db_unidad_ejecutora_receptor" id="traspaso_entre_partida_db_unidad_ejecutora_receptor"   style="width:62ex;">
					<option value="0">----------------------------------SELECCIONE----------------------------------</option>
					<?=$opt_organismo;?>
				</select>			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Central : </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_codigo_central_receptor" type="text" id="traspaso_entre_partida_db_codigo_central_receptor" 
						onchange="consulta_automatica_accion_central_receptor" onclick="consulta_automatica_accion_central_receptor"
						message="Introduzca un Codigo para el Accion Central."  size="6" maxlength="6"
						 >
					
						<input name="traspaso_entre_partida_db_accion_central_receptor" type="text" id="traspaso_entre_partida_db_accion_central_receptor"  style="width:60ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Central." 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_accion_central_receptor" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="traspaso_entre_partida_db_accion_central_id_receptor" type="hidden" id="traspaso_entre_partida_db_accion_central_id_receptor" />
			</td>
		</tr>
		<tr>
			<th>Proyecto: </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_codigo_proyecto_receptor" type="text" id="traspaso_entre_partida_db_codigo_proyecto_receptor"  
						onchange="consulta_automatica_proyecto_receptor" onclick="consulta_automatica_proyecto_receptor"
						message="Introduzca un Codigo para el Proyecto."  size="6" maxlength="6"
						>
					
						<input name="traspaso_entre_partida_db_proyecto_receptor" type="text" id="traspaso_entre_partida_db_proyecto_receptor"  style="width:60ex" maxlength="60"
						message="Introduzca un Nombre para el Proyecto." readonly 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_proyecto_receptor" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="traspaso_entre_partida_db_proyecto_id_receptor" type="hidden" id="traspaso_entre_partida_db_proyecto_id_receptor" />
				</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Espec&iacute;fica:</th>
			<td>
			
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_codigo_especifica_receptor" type="text" id="traspaso_entre_partida_db_codigo_especifica_receptor"  
						onchange="consulta_automatica_especifica_receptor" onclick="consulta_automatica_especifica_receptor"
						message="Introduzca una Codigo para la Accion Especifica."  size="6" maxlength="6"
						 >
					
						<input name="traspaso_entre_partida_db_accion_especifica_receptor" type="text" id="traspaso_entre_partida_db_accion_especifica_receptor"  style="width:60ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Especifica." readonly 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_accion_especifica_receptor" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="traspaso_entre_partida_db_accion_especifica_id_receptor" type="hidden" id="traspaso_entre_partida_db_accion_especifica_id_receptor" />			
			
				
			</td>
		</tr>
		<tr>
			<th>Partida:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="traspaso_entre_partida_db_partida_numero_receptor" type="text" id="traspaso_entre_partida_db_partida_numero_receptor"  
						readonly
						message="Introduzca una Codigo para la Accion Especifica."  size="8" maxlength="12" >
					
						<input name="traspaso_entre_partida_db_partida_receptor" type="text" id="traspaso_entre_partida_db_partida_receptor"  style="width:60ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Especifica." readonly 
						>
					</li>
					<li id="traspaso_entre_partida_db_btn_consultar_partida_receptor" class="btn_consulta_emergente"></li>
				</ul>
			
		</tr>
		<tr>
			<th>Mes:</th>
			<td>
				<select name="traspaso_entre_partida_db_mes_receptor" id="traspaso_entre_partida_db_mes_receptor"  style="width:17ex;">
					<option value="0">--------SELECIONE--------</option>
					<option value="enero">Enero</option>
					<option value="febrero">Febrero</option>
					<option value="marzo">Marzo</option>
					<option value="abril">Abril</option>
					<option value="mayo">Mayo</option>
					<option value="junio">Junio</option>
					<option value="julio">Julio</option>
					<option value="agosto">Agosto</option>
					<option value="septiembre">Septiembre</option>
					<option value="octubre">Octubre</option>
					<option value="noviembre">Noviembre</option>
					<option value="diciembre">Diciembre</option>
				</select>			
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<table class="clear" width="100%" border="0">
					<tr>
						<th>Monto Actual</th>
						<td>
							<input  name="traspaso_entre_partida_db_monto_actual_receptor" type="text" id="traspaso_entre_partida_db_monto_actual_receptor"  size="13" readonly 
							message="Monto Actual del Mes Receptor"  style="text-align:right"
							jVal="{valid:/^[0123456789.,]{1,15}$/, message:'Monto Invalido', styleType:'cover'}"  
							jValKey="{valid:/[0123456789.,]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}" 
							 value="0,00"  />						
						</td>
						
						<th>Monto Total</th>
						<td>
							<input name="traspaso_entre_partida_db_monto_total_receptor" type="text" id="traspaso_entre_partida_db_monto_total_receptor" 
							size="15"   style="text-align:right"  value="0,00"  
							/>						
						</td>
					</tr>
				</table>
		</td>		</tr>

		<tr>
			<th>Comentario:</th>
			<td>
				<textarea name="traspaso_entre_partida_db_comentario" id="traspaso_entre_partida_db_comentario" cols="65" rows="2" message="Introduzca un Comentario para el Presupuesto."></textarea>			</td>
		</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	<tr>			
  </table>
</form>
