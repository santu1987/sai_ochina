<?php
session_start();
//modulos/adquisiones/requisiciones/db/vista.resgistrar.php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor >= '".date("d-m-Y")."' AND nombre = 'IVA' ORDER BY fecha_valor asc";
$bus =& $conn->Execute($sql_porcen);
if ($bus->fields==""){
	$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor <= '".date("d-m-Y")."' AND nombre = 'IVA' ORDER BY fecha_valor desc";
	$bus =& $conn->Execute($sql_porcen);
	}
$porcentajexx = $bus->fields("porcentaje_impuesto");
/*if ($porcentaje!="")
echo "<input type='hidden' name='cotizacones_pr_porcentaje' id='cotizacones_pr_porcentaje' value='$porcentaje'/>";*/
//
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
$sql_uni="SELECT * FROM unidad_ejecutora WHERE (id_unidad_ejecutora =". $_SESSION["id_unidad_ejecutora"].")  ORDER BY nombre";
$rs_unida =& $conn->Execute($sql_uni);
?>

<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script type="text/javascript" language="JavaScript">   
/* Marcaras de edicion de campos de entrada --> */
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
</script>
<script>
$("#cotizacones_pr_btn_anadir").click(function(){
	if($('#form_pr_cotizaciones').jVal())
	{
		//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/cotizacion/pr/sql.actualizar.php",
			data:dataForm('form_pr_cotizaciones'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split(",");
			//alert(resultado[0]);   cotizacones_pr_numero
				if (html=="Ok")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_cotizaciones');
					getObj('cotizacones_pr_producto').value="";
					getObj('cotizacones_pr_partida').value="";
					getObj('cotizacones_pr_cantidad').value="";
					getObj('cotizacones_pr_monto').value="0,00";
					
					//alert("modulos/adquisiones/cotizacion/co/sql.consulta_p.php?numero_requision="+getObj('cotizacones_pr_numero').value);
					jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta_p.php?numero_requision="+getObj('cotizacones_pr_numero_cotizacion').value,page:1}).trigger("reloadGrid");						//jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta_p.php?numero_requision="+ret.nro_cotiza,page:1}).trigger("reloadGrid");
					//	getObj('cotizacones_pr_numero_requision').value=resultado[1];
						//getObj('cotizacones_pr_numero').value =resultado[1]; ;
				}
				else
				{
					setBarraEstado(html);
						//getObj('cotizacones_pr_obesrvacion').value=html;
				}
			}
		});
	}
});
//
var porcen = getObj("cotizacones_pr_porcentaje").value;
if (porcen!="")
	getObj('cotizacones_pr_iva').value = porcen.replace('.',',');
else
	getObj('cotizacones_pr_iva').value = "0,00";
//
//
//
//
$("#cotizacones_pr_btn_consultar").click(function() {											  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/cotizacion/pr/vista.grid_cotizacion_requisicion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cotizacion', modal: true,center:false,x:0,y:0,show:false});

				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#cotizacion_pr_requisicion").val(); 
					var busq_coti= jQuery("#cotizacion_pr_coti").val();
					var busq_fecha= jQuery("#cotizacion_pr_fecha").val();
					var busq_prove= jQuery("#cotizacion_pr_proveedor").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/cotizacion/pr/sql_busqueda_cotizacion.php?busq_nombre="+busq_nombre+"&busq_coti="+busq_coti+"&busq_fecha="+busq_fecha+"&busq_prove="+busq_prove,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#cotizacion_pr_requisicion").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						programa_dosearch();
												
					});
				$("#cotizacion_pr_coti").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						programa_dosearch();
												
					});
				/*$("#cotizacion_pr_calendario").click(function()
				{						
setBarraEstado("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<iframe align='middle' height='200px' width='290px'  src='modulos/adquisiones/cotizacion/pr/vista_calendario.php' style='border:none' scrolling='no'></iframe>",true,true);


												
					});*/
				$("#cotizacion_pr_fecha").focus(function()
				{			
						//if(key.keyCode==27){this.close();}
						programa_dosearch();				
						//alert(dialog);
						//dialog.hideAndUnload();
					});
				
				//
				//
				//
				//
				$("#cotizacion_pr_proveedor").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#cotizacion_pr_requisicion").val();
							var busq_coti= jQuery("#cotizacion_pr_coti").val();
							var busq_fecha= jQuery("#cotizacion_pr_fecha").val();
							var busq_prove= jQuery("#cotizacion_pr_proveedor").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/cotizacion/pr/sql_busqueda_cotizacion.php?busq_nombre="+busq_nombre+"&busq_coti="+busq_coti+"&busq_fecha="+busq_fecha+"&busq_prove="+busq_prove,page:1}).trigger("reloadGrid");
							
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/sql_busqueda_cotizacion.php?nd='+nd,
								datatype: "json",
								colNames:['id_cotizacion','Requisicion','Cotizacion', 'AÒo','Asunto','id_proyecto','codigo','nombre','id_accion_centralizada','prioridad','comentario','id_accion_especifica','codigo_accion_especifica','accion_especifica','observacion','id_proveedor','codigo_proveedor','proveedor','fecha'],
								colModel:[
									{name:'id_solicitud_cotizacione',index:'id_solicitud_cotizacione', width:20,sortable:false,resizable:false,hidden:true},
									{name:'numero_requisicion',index:'numero_requisicion', width:20,sortable:false,resizable:false},
									{name:'numero_cotizacion',index:'numero_cotizacion', width:20,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
									{name:'asunto',index:'asunto', width:50,sortable:false,resizable:false},
									{name:'id_proyecto',index:'id_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:10,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:50,sortable:false,resizable:false},
									{name:'id_accion_centralizada',index:'id_accion_centralizada', width:50,sortable:false,resizable:false,hidden:true},
									{name:'prioridad',index:'prioridad', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_especifica',index:'codigo_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'accion_especifica',index:'accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'observacion',index:'observacion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'codigo_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:50,sortable:false,resizable:false},
									{name:'fecha',index:'fecha', width:20,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//alert(ret.numero_requisicion);
								getObj('cotizacones_pr_numero_requision').value = ret.numero_requisicion;
								getObj('cotizacones_pr_numero').value = ret.numero_requisicion;
									getObj('cotizacones_pr_numero_cotizacion').value = ret.numero_cotizacion;
									getObj('cotizacones_pr_codigo_proveedor').value = ret.codigo_proveedor;
									getObj('cotizacones_pr_proveedor').value = ret.proveedor;
									getObj('cotizacones_pr_asunto').value = ret.asunto;
									if (ret.id_proyecto != 0){
										getObj('cotizacones_pr_proyecto_id').value = ret.id_proyecto;
										getObj('cotizacones_pr_codigo_proyecto').value = ret.codigo;
										getObj('cotizacones_pr_proyecto').value = ret.nombre;
										getObj('cotizacones_pr_accion_central_id').value = ""
										getObj('cotizacones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
										getObj('requisiciones_pr_codigo_central').value="0000";
										getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
										getObj('cotizacones_pr_accion_central').disabled="disabled";
									}
									if (ret.id_accion_centralizada != 0){
										getObj('cotizacones_pr_accion_central_id').value = ret.id_accion_centralizada;
										getObj('requisiciones_pr_codigo_central').value = ret.codigo;
										getObj('cotizacones_pr_accion_central').value = ret.nombre;
										getObj('cotizacones_pr_proyecto_id').value="";
										getObj('cotizacones_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
										getObj('cotizacones_pr_codigo_proyecto').value ="0000" ;
										getObj('cotizacones_pr_codigo_proyecto').disabled="disabled" ;
										getObj('cotizacones_pr_proyecto').disabled="disabled" ;
															}
									getObj('cotizacones_pr_accion_especifica_id').value = ret.id_accion_especifica;
									getObj('requisiciones_pr_accion_especifica_codigo').value = ret.codigo_accion_especifica;
									getObj('cotizacones_pr_accion_especifica').value = ret.accion_especifica;
									getObj('cotizacones_pr_obesrvacion').value = ret.observacion;
									getObj('cotizaciones_pr_tiempo_entrega').value = "";
									getObj('cotizaciones_pr_validez_oferta').value = "";
									getObj('cotizaciones_pr_lugar_entrega').value = "";
									getObj('cotizaciones_pr_condiciones_pago').value = "";
									//getObj('cotizacones_pr_numero').value = ret.nro_requision;
									jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta_p.php?numero_requision="+ret.numero_cotizacion,page:1}).trigger("reloadGrid");
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#cotizacion_pr_fecha").focus();
								$('#cotizacion_pr_requisicion').numeric({allow:''});
								$('#cotizacion_pr_coti').numeric({allow:''});
								$('#cotizacion_pr_fecha').numeric({allow:'-'});
								$('#cotizacion_pr_proveedor').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_solicitud_cotizacione',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
/*$("#cotizacones_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	clearForm('form_pr_cotizaciones');
	if (porcen!="")
	getObj('cotizacones_pr_iva').value = porcen.replace('.',',');
	else
	getObj('cotizacones_pr_iva').value = "0,00";
	getObj('cotizacones_pr_monto').value = "0,00";
	//alert('modulos/adquisiones/cotizacion/pr/sql_grid_requisiciones.php');
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cotizaci&oacute;n', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:950,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/sql_grid_requisiciones.php?nd='+nd,
								datatype: "json",
								colNames:['id_cotizacion','Requisicion','Cotizacion', 'AÒo', 'Asunto','id_proyecto','Codigo Pro/Accion','Proyecto/Accion central','Proyecto/Accion central', 'id_accion_centralizada','priorida','comentario','id_accion_especifica','Codigo de accion especifica','Acci&oacute;n Espec&iacute;fica','Observacion','id_proveedor','Codigo proveedor','Proveedor'],
								colModel:[
									{name:'id_cot',index:'id_cot', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nro_requision',index:'nro_requision', width:30,sortable:false,resizable:false},
									{name:'nro_cotiza',index:'nro_cotiza', width:30,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
									{name:'asunto',index:'asunto', width:140,sortable:false,resizable:false},
									{name:'id_proyecto',index:'id_proyecto', width:200,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:40,sortable:false,resizable:false},
									{name:'proyectoli',index:'proyectoli', width:150,sortable:false,resizable:false},
									{name:'proyecto',index:'proyecto', width:170,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_centralizada',index:'id_accion_centralizada', width:50,sortable:false,resizable:false,hidden:true},
									{name:'priorida',index:'priorida', width:200,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_especifica',index:'codigo_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'accion_epe',index:'accion_epe', width:220,sortable:false,resizable:false,hidden:true},
									{name:'observacion',index:'observacion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'codigo_proveedor', width:220,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:50,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cotizacones_pr_numero_requision').value = ret.nro_requision;
									getObj('cotizacones_pr_numero_cotizacion').value = ret.nro_cotiza;
									getObj('cotizacones_pr_codigo_proveedor').value = ret.codigo_proveedor;
									getObj('cotizacones_pr_proveedor').value = ret.proveedor;
									getObj('cotizacones_pr_asunto').value = ret.asunto;
									if (ret.id_proyecto != 0){
										getObj('cotizacones_pr_proyecto_id').value = ret.id_proyecto;
										getObj('cotizacones_pr_codigo_proyecto').value = ret.codigo;
										getObj('cotizacones_pr_proyecto').value = ret.proyecto;
										getObj('cotizacones_pr_accion_central_id').value = ""
										getObj('cotizacones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
										getObj('requisiciones_pr_codigo_central').value="0000";
										getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
										getObj('cotizacones_pr_accion_central').disabled="disabled";
									}
									if (ret.id_accion_centralizada != 0){
										getObj('cotizacones_pr_accion_central_id').value = ret.id_accion_centralizada;
										getObj('requisiciones_pr_codigo_central').value = ret.codigo;
										getObj('cotizacones_pr_accion_central').value = ret.proyecto;
										getObj('cotizacones_pr_proyecto_id').value="";
										getObj('cotizacones_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
										getObj('cotizacones_pr_codigo_proyecto').value ="0000" ;
										getObj('cotizacones_pr_codigo_proyecto').disabled="disabled" ;
										getObj('cotizacones_pr_proyecto').disabled="disabled" ;
															}
									getObj('cotizacones_pr_accion_especifica_id').value = ret.id_accion_especifica;
									getObj('requisiciones_pr_accion_especifica_codigo').value = ret.codigo_accion_especifica;
									getObj('cotizacones_pr_accion_especifica').value = ret.accion_epe;
									
									getObj('cotizacones_pr_obesrvacion').value = ret.observacion;
									getObj('cotizacones_pr_numero').value = ret.nro_requision;
									dialog.hideAndUnload();
									jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta_p.php?numero_requision="+ret.nro_cotiza,page:1}).trigger("reloadGrid");
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'id_solicitud_cotizacione',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/
// -----------------------------------------------------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------------------------------------------------
$("#cotizacones_pr_btn_partida").click(function() {

if(getObj('cotizacones_pr_accion_especifica_id').value !="")
{
	urls='modulos/adquisiones/requisiciones/db/cmb.sql.partida.php?nd='+nd+'&unidad_es='+getObj('cotizacones_pr_accion_especifica_id').value+'&accion='+getObj('cotizacones_pr_accion_central_id').value+'&proyecto='+getObj('cotizacones_pr_proyecto_id').value;
		alert(urls);
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
								loadtext: "Recuperando InformaciÛn del Servidor",		
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
									//getObj('cotizacones_pr_accion_especifica_id').value = ret.id;
									getObj('cotizacones_pr_partida').value = ret.partida;
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

// -----------------------------------------------------------------------------------------------------------------------------------

function consulta_automatica_numero_cotizacion()
{
	$.ajax({
			url:"modulos/adquisiones/cotizacion/pr/cmb.sql.numero_cotiza.php",
            data:dataForm('form_pr_cotizaciones'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				//alert(html);
				recordset = recordset.split("*");
					getObj("cotizacones_pr_numero_cotizacion").value=recordset[0];
					getObj("cotizacones_pr_numero").value=recordset[1];
					getObj("cotizacones_pr_numero_requision").value=recordset[1];
					getObj("cotizacones_pr_accion_especifica_id").value=recordset[4];
					getObj("cotizacones_pr_asunto").value=recordset[5];
					getObj("cotizacones_pr_obesrvacion").value=recordset[7];
					getObj("cotizacones_pr_accion_especifica").value=recordset[8];
					getObj("requisiciones_pr_accion_especifica_codigo").value=recordset[9];
					getObj("cotizacones_pr_codigo_proveedor").value=recordset[11];
					getObj("cotizacones_pr_proveedor").value=recordset[12];
					if (recordset[2] != 0){
						getObj('cotizacones_pr_proyecto_id').value = recordset[2];
						getObj('cotizacones_pr_codigo_proyecto').value = recordset[13];
						getObj('cotizacones_pr_proyecto').value = recordset[14];
						getObj('cotizacones_pr_accion_central_id').value = "";
						getObj('cotizacones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
						getObj('requisiciones_pr_codigo_central').value="0000";
						getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
						getObj('cotizacones_pr_accion_central').disabled="disabled";
					}
					if (recordset[3] != 0){
						getObj('cotizacones_pr_accion_central_id').value = recordset[3];
						getObj('requisiciones_pr_codigo_central').value = recordset[13];
						getObj('cotizacones_pr_accion_central').value = recordset[14];
						getObj('cotizacones_pr_proyecto_id').value="";
						getObj('cotizacones_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
						getObj('cotizacones_pr_codigo_proyecto').value ="0000" ;
						getObj('cotizacones_pr_codigo_proyecto').disabled="disabled" ;
						getObj('cotizacones_pr_proyecto').disabled="disabled" ;
					}
					jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta_p.php?numero_requision="+getObj('cotizacones_pr_numero_cotizacion').value,page:1}).trigger("reloadGrid");
				}else{  
					getObj('cotizacones_pr_numero_cotizacion').value ="";
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA COTIZACI&Oacute;N NO EXISTE</p></div>",true,true);
				}
				
			 }
		});	 	 
}
//************************************************************************************************************
$("#cotizacones_pr_btn_cancelar").click(function() {
	getObj('requisiciones_pr_codigo_central').disabled=""; 
	getObj('cotizacones_pr_codigo_proyecto').disabled="";
	getObj('requisiciones_pr_codigo_central').disabled="";
	getObj('cotizacones_pr_numero_cotizacion').value="";
	getObj('cotizacones_pr_codigo_proveedor').value="";
	getObj('cotizacones_pr_proveedor').value="";

	getObj('cotizacones_pr_ano').value ="";
	getObj('cotizacones_pr_proyecto').value ="";
	getObj('cotizacones_pr_proyecto_id').value ="";
	getObj('cotizacones_pr_codigo_proyecto').value ="";
	getObj('requisiciones_pr_codigo_central').value ="";
	getObj('cotizacones_pr_accion_central').value ="";
	getObj('cotizacones_pr_accion_central_id').value ="";
	getObj('cotizacones_pr_accion_especifica').value ="";
	getObj('requisiciones_pr_accion_especifica_codigo').value ="";
	getObj('cotizacones_pr_accion_especifica_id').value ="";
	getObj('cotizacones_pr_asunto').value ="";
	getObj('cotizacones_pr_obesrvacion').value ="";
	getObj('cotizacones_pr_producto').value ="";
	getObj('cotizacones_pr_partida').value ="";
	getObj('cotizacones_pr_cantidad').value ="";
	getObj('cotizacones_pr_unidad_medida').value ="";
	getObj('cotizacones_pr_numero').value="";
/*gianni xs*/
jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta_p.php"}).trigger("reloadGrid");
getObj('cotizaciones_pr_tiempo_entrega').value="";
getObj('cotizaciones_pr_validez_oferta').value="";
getObj('cotizaciones_pr_lugar_entrega').value="";
getObj('cotizaciones_pr_condiciones_pago').value="";
getObj('cotizacones_pr_monto').value="0,00";
if (porcen!="")
getObj('cotizacones_pr_iva').value = porcen.replace('.',',');
else
getObj('cotizacones_pr_iva').value = "0,00";
getObj('cotizacones_pr_unidad_medida').value=0;
clearform('form_pr_cotizaciones');
/*getObj('cotizacones_pr_accion_especifica').disabled="disabled"
getObj('requisiciones_pr_accion_especifica_codigo').disabled="disabled";
*/
 	} );
//---------------------------------------------------------------------------------------------------------------------------------


$('#cotizacones_pr_cotizacones_pr_asunto').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ 0123456789'});
$('#cotizacones_pr_producto').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄, '});
$('#cotizacones_pr_cantidad').numeric({allow:''});
$('#cotizacones_pr_numero_cotizacion').change(consulta_automatica_numero_cotizacion);

//**************************************************************************************************************************************
var lastsel,idd,monto;

$("#list_cotizacion").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",

	url:'modulos/adquisiones/cotizacion/co/sql.consulta_p.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','N&ordm; Renglon','Cantidad','id_unidad_medida','Unidad Medida','numero_requision','Descripcion','Monto','Impuesto','Partida'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
	   		{name:'n_renglon',index:'n_renglon', width:55},
			{name:'cantidad',index:'cantidad', width:70},
			{name:'id_unidad_medida',index:'id_unidad_medida', width:200,hidden:true},
			{name:'nombre',index:'nombre', width:100},
			{name:'numero_requision',index:'numero_requision', width:50,hidden:true},
			{name:'descripcion',index:'descripcion', width:200},
			{name:'monto',index:'monto', width:50, align:"right"},
			{name:'impuesto',index:'impuesto', width:55, align:"right"},
			{name:'partida',index:'partida', width:50}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_cotizaciones'),
   	sortname: 'id_solicitud_cotizacion',
    viewrecords: true,
    sortorder: "asc",
	onSelectRow: function(id){
		var ret = jQuery("#list_cotizacion").getRowData(id);
		//alert("modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value);
		//setBarraEstado("modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto);
		
		idd = ret.id;
		if(idd && idd!==lastsel){
		$.ajax({
			url:"modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value,
            data:dataForm('form_pr_cotizaciones'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				/*if(recordset)
				{alert("modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd);*/

				recordset = recordset.split("*");
				
				getObj('cotizacones_pr_id_solicitud').value = recordset[0];
				getObj('cotizacones_pr_secuencia').value = recordset[1];
				getObj('cotizacones_pr_cantidad').value=recordset[2];
				getObj('cotizacones_pr_unidad_medida').value=recordset[3];
				getObj('cotizacones_pr_producto').value=recordset[4];
				getObj('cotizacones_pr_partida').value=recordset[6];
				getObj('cotizacones_pr_monto').value=recordset[7];
				if (recordset[8]=="0,00")  getObj('cotizacones_pr_iva').value = porcen;
				else getObj('cotizacones_pr_iva').value=recordset[8];
				if (getObj('cotizaciones_pr_tiempo_entrega').value =="")	getObj('cotizaciones_pr_tiempo_entrega').value=recordset[9];
				if (getObj('cotizaciones_pr_lugar_entrega').value =="")     getObj('cotizaciones_pr_lugar_entrega').value=recordset[10];
				if (getObj('cotizaciones_pr_condiciones_pago').value =="")  getObj('cotizaciones_pr_condiciones_pago').value=recordset[11];
				if (getObj('cotizaciones_pr_validez_oferta').value =="")    getObj('cotizaciones_pr_validez_oferta').value=recordset[12];
				//}
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
			jQuery('#list_cotizacion').restoreRow(lastsel);
			jQuery('#list_cotizacion').editRow(idd,true);
			lastsel=idd;
			
		}
			/*idd = ret.id;
			monto = ret.monto;*/


	},
	//cellEdit: true,
	url:'modulos/adquisiones/requisiciones/co/sql.consulta.php',
}).navGrid("#pager_cotizaciones",{search :false,edit:false,add:false,del:false});



//**************************************************************************************************************************************

var timeoutHnd; 
var flAuto = true;

function proyecto_doSearch(ev)
{ 
	if(!flAuto) return; 
 var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(proyecto_gridReload,150)
} 

function requisioes_gridReload()
{ 
	jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta.php",page:1}).trigger("reloadGrid"); 
} 

$("#cotizacones_pr_btn_pdf").click(function(){

	requisiones_ano = getObj('cotizacones_pr_ano').value;
	numero_requi = getObj('cotizacones_pr_numero_cotizacion').value;
	//url="pdf.php?p=modulos/adquisiones/requisiciones/rp/vista.lst.requisicion.phpønumero_requi="+numero_requi"+ßano="requisiones_ano; 
	url="pdf.php?p=modulos/adquisiones/cotizacion/rp/vista.lst.solicitudcotizacion_cargado.phpøano="+requisiones_ano+"@numero_coti="+numero_requi; 
	//alert(url);
	openTab("Cotizaciones Montos",url);
});

	/*
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#proyecto_co_btn_consultar").attr("enable",state); 
}*/
$('#proyecto_busqueda_proyecto').alpha({nocaps:true,allow:'¥'});
//

</script>

<div id="botonera">
	<img id="cotizacones_pr_btn_cancelar" class="btn_cancelar"  src="imagenes/null.gif" />
	<img id="cotizacones_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="cotizacones_pr_btn_consultar" name="cotizacones_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<!--<img id="cotizacones_pr_btn_pdf" class="btn_imprimir"src="imagenes/null.gif"  />		-->
</div>

<form method="post" name="form_pr_cotizaciones" id="form_pr_cotizaciones">

<input type='hidden' name='cotizacones_pr_porcentaje' id='cotizacones_pr_porcentaje' value='<?=number_format($porcentajexx,2,',','.')?>'/>

<input type="hidden" name="cotizacones_pr_numero_requision" id="cotizacones_pr_numero_requision" value=""  />
<input type="hidden" name="cotizacones_pr_numero_reglon" id="cotizacones_pr_numero_reglon"/>
<table class="cuerpo_formulario">
  <tr>
    <th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Solicitud Cotizaciones 
  
  </tr>
	<tr>
		<th colspan="2">Unidad Solicitante: <?=$rs_unida->fields("nombre")?></th>
	</tr>
   <tr>
    <th width="50%"> N&ordm; Cotizaci&oacute;n
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
    <td width="50%"><input name="cotizacones_pr_numero_cotizacion"  type="text" id="cotizacones_pr_numero_cotizacion"  maxlength="10" 
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
	N&ordm; Requisici&oacute;n
          <input name="cotizacones_pr_numero" type="text" id="cotizacones_pr_numero"  maxlength="10" readonly="readonly"
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
   </td>
  </tr>
  <tr>
    <th width="50%"> A&ntilde;o	
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
    <td width="50%"><select name="cotizacones_pr_ano" id="cotizacones_pr_ano" style="min-width:60px; width:60px;">
      <option value="<?= date('Y')-1 ;?>">
        <?= date('Y')-1 ;?>
        </option>
      <option value="<?= date('Y');?>" selected="selected">
        <?= date('Y');?>
        </option>
    </select> 
   </td>
  </tr>
  <tr>
    <th>Proveedor</th>
    <td>
	<ul class="input_con_emergente">
		<li>
				<input name="cotizacones_pr_codigo_proveedor" type="text" id="cotizacones_pr_codigo_proveedor"  maxlength="5"  readonly="readonly"
				onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
       			message="Introduzca un Codigo para el Proyecto."  size="5"
				jVal="{valid:/^[0-9]{4}$/, message:'Codigo Invalido', styleType:'cover'}">	
			
				<input type="text" name="cotizacones_pr_proveedor" id="cotizacones_pr_proveedor"  readonly="readonly" 
						style="width:62ex;" maxlength="100"  message="Introduzca el Nombre del Proyecto." />
		</li>
	</ul>	
  </tr>
  <tr>
    <th>Proyecto</th>
    <td>
	<ul class="input_con_emergente">
		<li>
				<input name="cotizacones_pr_codigo_proyecto" type="text" id="cotizacones_pr_codigo_proyecto"  maxlength="5" 
				onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
       			message="Introduzca un Codigo para el Proyecto."  size="5" readonly="readonly"
				jVal="{valid:/^[0-9]{1,6}$/, message:'Codigo Invalido', styleType:'cover'}">	
			
				<input type="text" name="cotizacones_pr_proyecto" id="cotizacones_pr_proyecto"   readonly="readonly"
						style="width:62ex;" maxlength="100"  message="Introduzca el Nombre del Proyecto." />
				<input type="hidden" name="cotizacones_pr_proyecto_id" id="cotizacones_pr_proyecto_id" />
		</li>
	</ul>	
  </tr>
  <tr>
    <th>Acci&oacute;n Centralizada</th>
    <td>
	<ul class="input_con_emergente">
		<li>			
				<input name="requisiciones_pr_codigo_central" type="text" id="requisiciones_pr_codigo_central"  maxlength="5" size="5"
				onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
       			message="Introduzca codigo de  la accion central."  readonly="readonly"
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄1234567890]{1,6)$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
       			 <input type="text" name="cotizacones_pr_accion_central" id="cotizacones_pr_accion_central"  readonly="readonly"
				style="width:62ex;" maxlength="100"  message="Introduzca el Nombre del Proyecto." />
       			 <input type="hidden" name="cotizacones_pr_accion_central_id" id="cotizacones_pr_accion_central_id" />
        </li>
	</ul>	
	</td>
  </tr>
  <tr>
 
    <th>Acci&oacute;n Espec&iacute;fica</th>
    <td>
	 <ul class="input_con_emergente">
		<li>	
		<input name="requisiciones_pr_accion_especifica_codigo" type="text" id="requisiciones_pr_accion_especifica_codigo"  maxlength="5" size="5"
				onchange="consulta_automatica_accion_especifica" onclick="consulta_automatica_accion_especifica" 
            	message="Introduzca codigo de la acciÛn especÌfica."  readonly="readonly"
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄1234567890]{1,6}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
			
		<input type="text" name="cotizacones_pr_accion_especifica" id="cotizacones_pr_accion_especifica"  readonly="readonly"
				style="width:62ex;" maxlength="100" message="Introduzca la Acci&oacute;n Espec&iacute;fica." 
				jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄.,]{1,100}$/, message:'Acci&oacute;n Espec&iacute;fica Invalida', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄.,]/, cFunc:'alert', cArgs:['Acci&oacute;n Espec&iacute;fica: '+$(this).val()]}"	/>
        <input type="hidden" name="cotizacones_pr_accion_especifica_id" id="cotizacones_pr_accion_especifica_id" />
        </li>
	</ul>	
	</td>
  </tr>
  <tr>
    <th>Asunto</th>
    <td><textarea name="cotizacones_pr_asunto" cols="65" rows="2" id="cotizacones_pr_asunto"  readonly="readonly"
				message="Introduzca la Asunto del cual trata la requisici&oacute;n."
				></textarea></td>
  </tr>
  <tr>
    <th>Observaci&oacute;n</th>
    <td><textarea name="cotizacones_pr_obesrvacion" id="cotizacones_pr_obesrvacion" cols="65" rows="2"  readonly="readonly"
				message="Introduzca un observacion para la requisici&oacute;n."></textarea>    </td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#4c7595">&nbsp;</th>
  </tr>
  <tr>
    <th colspan="2"> <table class="clear" width="100%" border="0">
	<tr>
			<th >TIEMPO DE ENTREGA</th>
			<th colspan="4"><input type="text" name="cotizaciones_pr_tiempo_entrega" id="cotizaciones_pr_tiempo_entrega" size="30" /></th>
		</tr>
		<tr>
			<th >VALIDEZ DE LA OFERTA</th>
			<th colspan="4"><input type="text" name="cotizaciones_pr_validez_oferta" id="cotizaciones_pr_validez_oferta" size="30" /></th>
		</tr>
		<tr>
			<th >LUGAR DE ENTREGA</th>
			<th colspan="4"><input type="text" name="cotizaciones_pr_lugar_entrega" id="cotizaciones_pr_lugar_entrega" size="68" /></th>
		</tr>
		<tr>
			<th >CONDICIONES DE PAGO</th>
			<th colspan="3"><input type="text" name="cotizaciones_pr_condiciones_pago" id="cotizaciones_pr_condiciones_pago" size="68" /></th>
						<td width="4%" rowspan="2"><img id="cotizacones_pr_btn_anadir" src="imagenes/actuliza40.png"   /> </td>

		</tr>
      <tr>
        <th>Descripci&oacute;n</th>
        <td colspan="3">
		<input type="hidden" name="cotizacones_pr_id_solicitud" id="cotizacones_pr_id_solicitud" />
		<input type="hidden" name="cotizacones_pr_secuencia" id="cotizacones_pr_secuencia" />
		<textarea name="cotizacones_pr_producto"  id="cotizacones_pr_producto" 
							message="Introduzca el nombre del Producto/Servicio" cols="65" rows="1"
							jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄0123456789.-(),]{2,200}$/, message:'Producto/Servicio Invalido', styleType:'cover'}"
							jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄0123456789.-(),]/, cFunc:'alert', cArgs:['Producto/Servicio: '+$(this).val()]}"></textarea>        </td>
      </tr>
      <tr>
        <th width="20%"style="text-align:center;">Partida</th>
        <th width="20%"style="text-align:center;">Cantidad</th>
        <th width="20%"style="text-align:center;">Unidad de Medida</th>
		<th width="20%"style="text-align:center;">Monto</th>
		<th width="20%"style="text-align:center;">Valor Impuesto</th>
      </tr>
      <tr>
        <td width="20%"align="center"><input type="text" name="cotizacones_pr_partida" id="cotizacones_pr_partida"
							message="Introduzca la partida." maxlength="12" size="12" readonly /></td>
        <td width="20%" align="center"><input type="text" name="cotizacones_pr_cantidad" id="cotizacones_pr_cantidad"
							message="Introduzca la cantidad del producto." maxlength="8" size="10"
							jval="{valid:/^[0123456789]{1,10}$/, message:'Producto Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['Producto: '+$(this).val()]}" />        </td>
        <td width="20%" align="center"><select name="cotizacones_pr_unidad_medida" id="cotizacones_pr_unidad_medida" style="min-width:150px; width:150px;">
          <option value="0">--------SELECCIONE--------</option>
          <?=$opt_unida_medida;?>
        </select>        </td>
		<td width="20%" align="center"><input name="cotizacones_pr_monto" type="text" id="cotizacones_pr_monto" style="text-align:right" size="15" alt="signed-decimal"/></td>
		<td width="20%" align="center"><input name="cotizacones_pr_iva" type="text" id="cotizacones_pr_iva" style="text-align:right" size="6" maxlength="5" alt="signed-decimal-im"/></td>
      </tr>
		
    </table></th>
  </tr>
  <tr>
    <td class="celda_consulta" colspan="2">
	
	<table id="list_cotizacion" class="scroll" cellpadding="0" cellspacing="0"></table> 
	<div id="pager_cotizaciones" class="scroll" style="text-align:center;"></div> 
	<br />
		</td>
  </tr>
  <tr>
    <td colspan="2" class="bottom_frame">&nbsp;</td>
  </tr>
</table>
</form>
