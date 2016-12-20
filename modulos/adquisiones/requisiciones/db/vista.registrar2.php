<?php
session_start();
//modulos/adquisiones/requisiciones/db/vista.resgistrar.php
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

$("#requisiones_pr_btn_anadir").click(function(){
	if($('#form_pr_requisiones').jVal())
	{
		//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/requisiciones/db/sql.requisiciones.php",
			data:dataForm('form_pr_requisiones'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split(",");
			//alert(getObj('requisiones_pr_numero_reglon').value);
				if (resultado[0]=="Ok")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_requisiones');
					jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php?numero_requision="+resultado[1],page:1}).trigger("reloadGrid");
						getObj('requisiones_pr_numero_requision').value=resultado[1];
						getObj('requisiones_pr_numero').value =resultado[1]; 
						getObj('requisiones_pr_producto').value="";
						getObj('requisiones_pr_cantidad').value =""; 
				}else if (resultado[0]=="Okk")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_requisiones');
					jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php?numero_requision="+resultado[1],page:1}).trigger("reloadGrid");
						getObj('requisiones_pr_numero_requision').value=resultado[1];
						getObj('requisiones_pr_numero').value =resultado[1]; 
						getObj('requisiones_pr_producto').value="";
						getObj('requisiones_pr_cantidad').value =""; 
				}else if (html=="Existe")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL PRODUCTO/SERVICIO YA FUE AGREGADO </p></div>",true,true);
				}else if (html=="cotizacion_existe")
				{
					setBarraEstado(mensaje[convertirda],true,true);
				}
				//si la requisicion ya fue convertida en cotizacion
				/*//else if (html=="cotizacion_existe") 
			{
					alert("No se puede modificar la requisición debido a que ya fue convertida en cotización");
			}
				*////////////////////////////////////////////////////////////////////////////
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
						//getObj('requisiones_pr_obesrvacion').value=html;
				}
			}
		});
	}
});
$("#requisiones_pr_btn_actualizar").click(function(){
	if($('#form_pr_requisiones').jVal())
	{
		//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/requisiciones/db/sql.actualizar.php",
			data:dataForm('form_pr_requisiones'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split("*");
			//alert(resultado[0]);
				if (resultado[0]=="Ok")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_requisiones');
					jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php?numero_requision="+resultado[1],page:1}).trigger("reloadGrid");
						//getObj('requisiones_pr_numero_requision').value=resultado[1];
						//getObj('requisiones_pr_numero').value =resultado[1]; ;
				}else if (resultado[0]=="Existe")
				{
					setBarraEstado(resultado[1]);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL PRODUCTO/SERVICIO YA FUE AGREGADOO </p></div>",true,true);
				}else if (html=="cotizacion_existe")
				{
					setBarraEstado(mensaje[convertirda],true,true);
				}
				//si la requisicion ya fue convertida en cotizacion
				/*else if (html=="cotizacion_existe") 
			{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA REQUISICION YA TIENE UNA COTIZACION,<BR>NO SE PUEDE MODIFICAR </p></div>",true,true);
			}*/
				///////////////////////////////////////////////////////////////////////////
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
						//getObj('requisiones_pr_obesrvacion').value=html;
				}
			}
		});
	}
});
//
//
//
$("#requisiones_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/requisiciones/db/vista.grid_requisicion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Impuesto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_requi= jQuery("#requisicion_db_requisicion").val(); 
					var busq_asunt= jQuery("#requisicion_db_asunto").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/requisiciones/db/sql_busqueda_requisicion.php?busq_requi="+busq_requi+"&busq_asunt="+busq_asunt,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#requisicion_db_requisicion").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#requisicion_db_asunto").keypress(function(key)
				{ 
					 
						if(key.keyCode==27){this.close();}
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
							var busq_requi= jQuery("#requisicion_db_requisicion").val();
							var busq_asunt= jQuery("#requisicion_db_asunto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/requisiciones/db/sql_busqueda_requisicion.php?busq_requi="+busq_requi+"&busq_asunt="+busq_asunt,page:1}).trigger("reloadGrid");
							
						}
			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/requisiciones/db/sql_busqueda_requisicion.php?nd='+nd,
								datatype: "json",
								colNames:['Nº Requisicion','Año','id_proyecto','id_accion_centralizada','id_accion_especifica','Asunto','Codigo','Proyecto/Accion Central','Prioridad','Comentario','ano_csc','id_tipo_documento','Observacion','Accion Especifica','Codigo'],
								colModel:[
									{name:'numero_requisicion',index:'numero_requisicion', width:10,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:25,sortable:false,resizable:false,hidden:true},
									{name:'id_proyecto',index:'id_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_centralizada',index:'id_accion_centralizada', width:10,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:10,sortable:false,resizable:false,hidden:true},
									{name:'asunto',index:'asunto', width:20,sortable:false,resizable:false},
									{name:'codigo',index:'codigo', width:10,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:30,sortable:false,resizable:false},
									{name:'prioridad',index:'prioridad', width:10,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:10,sortable:false,resizable:false,hidden:true},
									{name:'ano_csc',index:'ano_csc', width:10,sortable:false,resizable:false,hidden:true},
									{name:'id_tipo_documento',index:'id_tipo_documento', width:10,sortable:false,resizable:false,hidden:true},
									{name:'observacion',index:'observacion', width:10,sortable:false,resizable:false,hidden:true},
									{name:'accion_especifica',index:'accion_especifica', width:30,sortable:false,resizable:false},
									{name:'codigo_accion_especifica',index:'codigo_accion_especifica', width:10,sortable:false,resizable:false,hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('requisiones_pr_numero_requision').value = ret.numero_requisicion;
									getObj('requisiones_pr_asunto').value = ret.asunto;
									if (ret.id_proyecto != 0){
										getObj('requisiones_pr_proyecto_id').value = ret.id_proyecto;
									//	alert(getObj('requisiones_pr_proyecto_id').value);
										getObj('requisiones_pr_codigo_proyecto').value = ret.codigo;										
										
										getObj('requisiones_pr_proyecto').value = ret.nombre;
										getObj('requisiones_pr_accion_central_id').value = ""
										getObj('requisiones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
										getObj('requisiciones_pr_codigo_central').value="0000";
										getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
										getObj('requisiones_pr_accion_central').disabled="disabled";
									}
									if (ret.id_accion_centralizada != 0){
										getObj('requisiones_pr_accion_central_id').value = ret.id_accion_centralizada;
										getObj(getObj('requisiones_pr_accion_central_id').value);
										getObj('requisiciones_pr_codigo_central').value = ret.codigo;
										getObj('requisiones_pr_accion_central').value = ret.nombre;
										getObj('requisiones_pr_proyecto_id').value="";
										getObj('requisiones_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
										getObj('requisiones_pr_codigo_proyecto').value ="0000" ;
										getObj('requisiones_pr_codigo_proyecto').disabled="disabled" ;
										getObj('requisiones_pr_proyecto').disabled="disabled" ;
															}
									getObj('requisiones_pr_accion_especifica_id').value = ret.id_accion_especifica;
									getObj('requisiciones_pr_accion_especifica_codigo').value = ret.codigo_accion_especifica;
									getObj('requisiones_pr_accion_especifica').value = ret.accion_especifica;
									 
									getObj('requisiones_pr_obesrvacion').value = ret.observacion;
									getObj('requisiones_pr_numero').value = ret.numero_requisicion;
									dialog.hideAndUnload();
									
									jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php?numero_requision="+ret.numero_requisicion,page:1}).trigger("reloadGrid");
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#requisicion_db_requisicion").focus();
								$('#requisicion_db_requisicion').numeric({allow:' '});
								$('#requisicion_db_asunto').alph({allow:'0-9'});
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
//
//
//
/*$("#requisiones_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Requici&oacute;n', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:950,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/requisiciones/db/sql_grid_requisiciones.php?nd='+nd,
								datatype: "json",
								colNames:['Requisicion', 'Año', 'Asunto','id_proyecto','Codigo','Proyecto/Accion central','Proyecto/Accion central', 'id_accion_centralizada','priorida','comentario','id_accion_especifica','Codigo de accion especifica','Acci&oacute;n Espec&iacute;fica','Observacion'],
								colModel:[
									{name:'nro_requision',index:'nro_requision', width:50,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:60,sortable:false,resizable:false,hidden:true},
									{name:'asunto',index:'asunto', width:100,sortable:false,resizable:false},
									{name:'id_proyecto',index:'id_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
									{name:'proyectoli',index:'proyectoli', width:150,sortable:false,resizable:false},
									{name:'proyecto',index:'proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_centralizada',index:'id_accion_centralizada', width:50,sortable:false,resizable:false,hidden:true},
									{name:'priorida',index:'priorida', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_especifica',index:'codigo_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'accion_epe',index:'accion_epe', width:150,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:50,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisiones_pr_numero_requision').value = ret.nro_requision;
									getObj('requisiones_pr_asunto').value = ret.asunto;
									if (ret.id_proyecto != 0){
										getObj('requisiones_pr_proyecto_id').value = ret.id_proyecto;
									//	alert(getObj('requisiones_pr_proyecto_id').value);
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
										getObj(getObj('requisiones_pr_accion_central_id').value);
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
									jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php?numero_requision="+ret.nro_requision,page:1}).trigger("reloadGrid");

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
});*/
// -----------------------------------------------------------------------------------------------------------------------------------

$("#requisiones_pr_btn_consultar_proyecto").click(function() {
if(getObj('requisiones_pr_accion_central_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/requisiciones/db/cmb.sql.proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:30,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:25,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisiones_pr_proyecto_id').value = ret.id;
									getObj('requisiones_pr_codigo_proyecto').value = ret.codigo
									getObj('requisiones_pr_proyecto').value = ret.denominacion;
									getObj('requisiones_pr_accion_central_id').value = ""
									getObj('requisiones_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
									getObj('requisiciones_pr_codigo_central').value="0000";
									getObj('requisiciones_pr_codigo_central').disabled="disabled"; 
									getObj('requisiones_pr_accion_central').disabled="disabled";
									getObj('requisiones_pr_accion_especifica').disabled=""
									getObj('requisiciones_pr_accion_especifica_codigo').disabled="";
									getObj('requisiones_pr_accion_especifica').value="";
									getObj('requisiciones_pr_accion_especifica_codigo').value="";
									getObj('requisiones_pr_accion_especifica_id').value="";
									
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
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/requisiciones/db/cmb.sql.accion_central.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Acci&oacute;n Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:25,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
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
									getObj('requisiones_pr_accion_especifica').value="";
									getObj('requisiciones_pr_accion_especifica_codigo').value="";
									getObj('requisiones_pr_accion_especifica_id').value="";
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
		//urls='modulos/adquisiones/requisiciones/db/cmb.sql.accion_especifica.php?nd='+nd+'&accion_central_id ='+getObj('requisiones_pr_accion_central_id').value+'&proyecto_id ='+getObj('requisiones_pr_proyecto_id').value;
		//urls='modulos/adquisiones/requisiciones/db/cmb.sql.accion_especifica.php?proyecto_id ="+getObj('requisiones_pr_proyecto_id').value;
//alert('modulos/adquisiones/requisiciones/db/cmb.sql.accion_especifica.php?nd='+nd+'&accion_central ='+getObj('requisiones_pr_accion_central_id').value+'&proyecto ='+getObj('requisiones_pr_proyecto_id').value);
	//alert(urls);

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
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								//url:urls,
 								url:'modulos/adquisiones/requisiciones/db/cmb.sql.accion_especifica.php?nd='+nd+'&accion_central='+getObj('requisiones_pr_accion_central_id').value+'&proyecto='+getObj('requisiones_pr_proyecto_id').value,	
								datatype: "json",
								colNames:['Id','Codigo', 'Accion Especifica'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:25,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisiones_pr_accion_especifica_id').value = ret.id;
									getObj('requisiciones_pr_accion_especifica_codigo').value = ret.codigo;
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
	urls='modulos/adquisiones/requisiciones/db/cmb.sql.partida.php?nd='+nd+'&unidad_es='+getObj('requisiones_pr_accion_especifica_id').value+'&accion='+getObj('requisiones_pr_accion_central_id').value+'&proyecto='+getObj('requisiones_pr_proyecto_id').value+'&gasto='+getObj('requisiones_pr_tipo_doc').value;
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
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								datatype: "json",
								colNames:['Id','Partida', 'Denominacion','Disponible'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false},
									{name:'monto_pre',index:'monto_pre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
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
				
				getObj('requisiones_pr_accion_especifica').value="";
				getObj('requisiciones_pr_accion_especifica_codigo').value="";
				getObj('requisiones_pr_accion_especifica_id').value="";
				
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
				getObj('requisiones_pr_accion_especifica').value="";
				getObj('requisiciones_pr_accion_especifica_codigo').value="";
				getObj('requisiones_pr_accion_especifica_id').value="";
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
				getObj('requisiones_pr_accion_especifica').value="";
				getObj('requisiciones_pr_accion_especifica_codigo').value="";
				getObj('requisiones_pr_accion_especifica_id').value="";
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
				getObj('requisiones_pr_accion_especifica').value="";
				getObj('requisiciones_pr_accion_especifica_codigo').value="";
				getObj('requisiones_pr_accion_especifica_id').value="";
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
       		  var recordset=html;
			  				//alert(html);
				//getObj('requisiones_pr_asunto').value=html;
				//getObj('requisiones_pr_accion_especifica').value=html;
			if(recordset)
				{
				recordset = recordset.split("*");
				getObj('requisiones_pr_accion_especifica_id').value = recordset[0];
				getObj('requisiones_pr_accion_especifica').value = recordset[1];
				
				}
				else
			   {  
			   	getObj('requisiones_pr_accion_especifica_id').value = "";
				//getObj('requisiciones_pr_accion_especifica_codigo').value = "";
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
getObj('requisiones_pr_tipo_doc').value="";         
getObj('requisiones_pr_unidad_medida').value ="";
getObj('requisiones_pr_numero').value="";
clearForm('form_pr_requisiones');
jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php"}).trigger("reloadGrid");
/*getObj('requisiones_pr_accion_especifica').disabled="disabled"
getObj('requisiciones_pr_accion_especifica_codigo').disabled="disabled";
*/
 	} );
//---------------------------------------------------------------------------------------------------------------------------------
$('#requisiciones_pr_codigo_central').change(consulta_automatica_accion_central);
$('#requisiones_pr_codigo_proyecto').change(consulta_automatica_proyecto);
$('#requisiciones_pr_accion_especifica_codigo').change(consulta_automatica_accion_especifica);


$('#requisiones_pr_requisiones_pr_asunto').alpha({allow:'áéíóúÁÉÍÓÚ 0123456789'});
$('#requisiones_pr_producto').alpha({allow:'áéíóúÁÉÍÓÚ1234567890.()-, '});
$('#requisiones_pr_cantidad').numeric({allow:''});


</script>
<script type="text/javascript">
/*
$("#list_requisiones").jqGrid
({ 
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/adquisiones/requisiciones/co/sql.consulta2.php?nd='+new Date().getTime(),
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
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id_requisicion_detalle',
    viewrecords: true,
    sortorder: "asc",
	forceFit : true,
	cellEdit: true
})/*.navGrid('#pager_requisiones',
{edit:false,add:false,refresh:false,search:false}, //options
{} // search options
)*;*/

//**************************************************************************************************************************************
var lastsel,idd;

$("#list_requisiones").jqGrid({
	height: 85,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/adquisiones/requisiciones/co/sql.consulta2.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','N&ordm; Renglon','Cantidad','id_unidad_medida','Unidad Medida','Descripcion','numero_requision','Partida','Par'],
   	colModel:[
	   		{name:'requisiones_pr_numero_reglon',index:'requisiones_pr_numero_reglon', width:50,hidden:true},
	   		{name:'reglon',index:'reglon', width:50},
			{name:'requisiones_pr_cantidad',index:'requisiones_pr_cantidad', width:80,editable:true, editable:true},
			{name:'requisiones_pr_unidad_medida',index:'requisiones_pr_unidad_medida', width:200,hidden:true},
			{name:'nombre',index:'nombre', width:100},
			{name:'requisiones_pr_producto',index:'requisiones_pr_producto', width:200, editable:true},
			{name:'numero_requision',index:'numero_requision', width:50,hidden:true},
			{name:'requisiones_pr_partida',index:'requisiones_pr_partida', width:50,hidden:true},
			{name:'requisiones_pr_tipo_doc',index:'requisiones_pr_tipo_doc', width:50,hidden:true}
   	],
   	rowNum:5,
   	rowList:[5,10,15],
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_requisiones'),
   	sortname: 'id_requisicion_detalle',
    viewrecords: true,
    sortorder: "asc",
	/*onSelectRow: function(id){
		var ret = jQuery("#list_requisiones").getRowData(id);
		//alert("modulos/adquisiones/requisiciones/db/sql.actualizar.php?id="+id_deta+"&descripcion="+descrip);
		/*if(id && id!==lastsel){
							$.ajax (
								{
								url: "modulos/adquisiones/requisiciones/db/sql.actualizar.php?id="+id_deta+"&descripcion="+descrip,
									data:dataForm('form_pr_requisiones'),
									type:'GET',
									cache: false,
									success: function(html)
									{
										if (resultado=="Ok")
										{
											setBarraEstado(mensaje[registro_exitoso],true,true);
											jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php?numero_requision="+getObj('requisiones_pr_numero').value,page:1}).trigger("reloadGrid");
											//clearForm('form_pr_requisiones');
										}
										else
										{
											setBarraEstado(html);
										}
	
									}
								});	
			jQuery('#list_requisiones').restoreRow(lastsel);
			jQuery('#list_requisiones').editRow(id,true);
			lastsel=id;
			
		}
				id_deta = ret.id;
				descrip = ret.descripcion;
	},*/
	//cellEdit: true,
	//url:'modulos/adquisiones/requisiciones/co/sql.consulta2.php',
}).navGrid("#pager_requisiones",{search :false,edit:false,add:false,del:false})
.navButtonAdd('#pager_requisiones',{caption:"Editar",
	onClickButton:function(){
		var gsr = jQuery("#list_requisiones").getGridParam('selrow');
		if(gsr){
			//alert(gsr);
			jQuery("#list_requisiones").GridToForm(gsr,"#form_pr_requisiones");
		} else {
			alert("Por Seleccione una Linea")
		}							
} 
}).navButtonAdd('#pager_requisiones',{caption:"Eliminar",
	onClickButton:function(){
	//alert("AQUI");
	
		var gsr = jQuery("#list_requisiones").getGridParam('selrow');
		if(gsr){
		if(confirm("¿Desea eliminar este item?")) 
		{
			$.ajax ({
			url: "modulos/adquisiones/requisiciones/db/sql.eliminar.php?id="+gsr+"&requi="+getObj('requisiones_pr_numero').value,
				data:dataForm('form_pr_requisiones'),
				type:'GET',
				cache: false,
				success: function(html)
				{
					if (html=="Ok")
					{
							//alert(gsr);

						setBarraEstado(mensaje[eliminacion_exitosa],true,true);
						jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php?numero_requision="+getObj('requisiones_pr_numero').value,page:1}).trigger("reloadGrid");
						//clearForm('form_pr_requisiones');
					}else if (html=="cotizacion_existe")
					{
						setBarraEstado(mensaje[convertirda],true,true);
					}else
					{
						setBarraEstado(html);
					}
				}
			});	
		}
		} else {
			alert("Por Seleccione una Linea")
		}							
	} 
});



//**************************************************************************************************************************************

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
	jQuery("#list_requisiones").setGridParam({url:"modulos/adquisiones/requisiciones/co/sql.consulta2.php",page:1}).trigger("reloadGrid"); 
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

</script>

<div id="botonera">
	<img id="requisiones_pr_btn_cancelar" class="btn_cancelar"  src="imagenes/null.gif" />
	<img id="requisiones_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="requisiones_pr_btn_consultar" name="requisiones_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="requisiones_pr_btn_pdf" class="btn_imprimir"src="imagenes/null.gif"  />		
</div>

<form method="post" name="form_pr_requisiones" id="form_pr_requisiones">
<input type="hidden" name="requisiones_pr_numero_requision" id="requisiones_pr_numero_requision" value=""  />
<input type="hidden" name="requisiones_pr_numero_reglon" id="requisiones_pr_numero_reglon"/>
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Crear Requisici&oacute;n</th>  
	</tr>
	<tr>
		<th colspan="2">Unidad Solicitante: <?=$rs_unida->fields("nombre")?></th>
	</tr>
   <tr>
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
    </select> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;
	N&ordm; Requisici&oacute;n
          <input name="requisiones_pr_numero"  type="text" id="requisiones_pr_numero"  maxlength="10" readonly="readonly"
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
   </td>
  </tr>
  <tr>
    <th>Proyecto</th>
    <td>
	<ul class="input_con_emergente">
		<li>
				<input name="requisiones_pr_codigo_proyecto" type="text" id="requisiones_pr_codigo_proyecto"  maxlength="5" 
				onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
       			message="Introduzca un Codigo para el Proyecto."  size="5"
				jVal="{valid:/^[0-9]{4,5}$/, message:'Codigo Invalido', styleType:'cover'}">	
			
				<input type="text" name="requisiones_pr_proyecto" id="requisiones_pr_proyecto"  
						style="width:62ex;" maxlength="60"  message="Introduzca el Nombre del Proyecto." />
				<input type="hidden" name="requisiones_pr_proyecto_id" id="requisiones_pr_proyecto_id" />
		</li>
		<li id="requisiones_pr_btn_consultar_proyecto" class="btn_consulta_emergente"></li>
	</ul>	
  </tr>
  <tr>
    <th>Acci&oacute;n Centralizada</th>
    <td>
	<ul class="input_con_emergente">
		<li>			
				<input name="requisiciones_pr_codigo_central" type="text" id="requisiciones_pr_codigo_central"  maxlength="5" size="5"
				onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
       			message="Introduzca codigo de  la accion central." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,5}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
       			 <input type="text" name="requisiones_pr_accion_central" id="requisiones_pr_accion_central" 
				style="width:62ex;" maxlength="60"  message="Introduzca el Nombre del Proyecto." />
       			 <input type="hidden" name="requisiones_pr_accion_central_id" id="requisiones_pr_accion_central_id" />
        </li>
		<li id="requisiones_pr_btn_consultar_accion_central" class="btn_consulta_emergente"></li>
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
            	message="Introduzca codigo de la acción específica." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,5}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
			
		<input type="text" name="requisiones_pr_accion_especifica" id="requisiones_pr_accion_especifica" 
				style="width:62ex;" maxlength="60" message="Introduzca la Acci&oacute;n Espec&iacute;fica." 
				jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,]{1,100}$/, message:'Acci&oacute;n Espec&iacute;fica Invalida', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,]/, cFunc:'alert', cArgs:['Acci&oacute;n Espec&iacute;fica: '+$(this).val()]}"	/>
        <input type="hidden" name="requisiones_pr_accion_especifica_id" id="requisiones_pr_accion_especifica_id" />
        </li>
		<li id="requisiones_pr_btn_consultar_accion_especifica" class="btn_consulta_emergente"></li>
	</ul>	
	</td>
  </tr>
  <tr>
    <th>Asunto</th>
    <td><textarea name="requisiones_pr_asunto" cols="75" rows="2" id="requisiones_pr_asunto" 
				message="Introduzca la Asunto del cual trata la requisici&oacute;n."
				jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,-()]{2,200}$/, message:'Asunto Invalido', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,-()]/, cFunc:'alert', cArgs:['Asunto: '+$(this).val()]}"></textarea></td>
  </tr>
  <tr>
    <th colspan="2"> <table class="clear" width="100%" border="0">
      <tr>
        <th>Tipo de gasto </th>
        <td><select name="requisiones_pr_tipo_doc" id="requisiones_pr_tipo_doc" style="min-width:150px; width:150px;" >
          <? //=$opt_tipo_doc;?>
          <option value="0" >----SELECCIONE---</option>
          <option value="402" >Insumos</option>
          <option value="404" >Activos Reales</option>
          <option value="403" >Servicios</option>
        </select>        </td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th>Observaci&oacute;n</th>
    <td><textarea name="requisiones_pr_obesrvacion" id="requisiones_pr_obesrvacion" cols="75" rows="2" 
				message="Introduzca un observacion para la requisici&oacute;n."></textarea>    </td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#4c7595">&nbsp;</th>
  </tr>
  <tr>
    <th colspan="2"> <table class="clear" width="100%" border="0">
      <tr>
        <th>Descripci&oacute;n</th>
        <td colspan="3"><textarea name="requisiones_pr_producto"  id="requisiones_pr_producto" 
							message="Introduzca el nombre del Producto/Servicio" cols="75" rows="1"
							jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,-*+()$_]{2,200}$/, message:'Producto/Servicio Invalido', styleType:'cover'}"
							jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,-*+()$_]/, cFunc:'alert', cArgs:['Producto/Servicio: '+$(this).val()]}"></textarea>        </td>
      </tr>
      <tr>
        <th >Partida</th>
        <th style="text-align:center;">Cantidad</th>
        <th style="text-align:center;">Unidad de Medida</th>
      </tr>
      <tr>
        <td width="15%" ><input type="text" name="requisiones_pr_partida" id="requisiones_pr_partida"
							message="Introduzca la partida." maxlength="12" size="12" />
              <img id="requisiones_pr_btn_partida" class="btn_consulta_emergente" src="imagenes/null.gif"  /> </td>
        <td width="23%" align="center"><input type="text" name="requisiones_pr_cantidad" id="requisiones_pr_cantidad"
							message="Introduzca la cantidad del producto." maxlength="8" size="10"
							jval="{valid:/^[0123456789.]{1,10}$/, message:'Producto Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0123456789.]/, cFunc:'alert', cArgs:['Producto: '+$(this).val()]}" alt="integer"/>        </td>
        <td width="23%" align="center"><select name="requisiones_pr_unidad_medida" id="requisiones_pr_unidad_medida" style="min-width:150px; width:150px;">
          <option value="0">--------SELECCIONE--------</option>
          <?=$opt_unida_medida;?>
        </select>        </td>
        <td width="2%"><img id="requisiones_pr_btn_anadir" src="imagenes/anadir.png"   /></td>
		<!-- <td width="2%"><img id="requisiones_pr_btn_actualizar" src="imagenes/actuliza15.png"   /> </td>-->
      </tr>
    </table></th>
  </tr>
  <tr>
    <td class="celda_consulta" colspan="2">
	
	<table id="list_requisiones" class="scroll" cellpadding="0" cellspacing="0"></table> 
	<div id="pager_requisiones" class="scroll" style="text-align:center;"></div> 
	<br />
		</td>
  </tr>
  <tr>
    <td colspan="2" class="bottom_frame">&nbsp;</td>
  </tr>
</table>
</form>
