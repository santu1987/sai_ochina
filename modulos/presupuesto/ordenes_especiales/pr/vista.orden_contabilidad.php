<?php
session_start();
//modulos/presupuesto/ordenes_especiales/pr/vista.resgistrar.php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor >= '".date("Y-m-d")."' AND nombre = 'IVA' ORDER BY fecha_valor asc";
$bus =& $conn->Execute($sql_porcen);
if ($bus->fields==""){
	$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor <= '".date("Y-m-d")."' AND nombre = 'IVA' ORDER BY fecha_valor desc";
	$bus =& $conn->Execute($sql_porcen);
	}
/*die ($sql_porcen);*/
$porcentajexx = $bus->fields("porcentaje_impuesto");


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

$("#orden_especial_pr_btn_anadir").click(function(){
	//if($('#form_pr_ordenes_contabilidad').jVal())
	//{
	 if((getObj('orden_especial_pr_numero').value!="") && (getObj('orden_especial_pr_numero_compromiso').value!="")){ 
	 if(getObj('orden_especial_pr_numero_reglon').value==""){ 
		//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/ordenes_especiales/pr/sql.orden_contabilidad.php",
			data:dataForm('form_pr_ordenes_contabilidad'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split(",");
			//alert("modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+resultado[1]);
				if (resultado[0]=="Registrado")
				{
				//alert("modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+resultado[1]);
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_ordenes_contabilidad');
					jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+resultado[2],page:1}).trigger("reloadGrid");
						getObj('orden_especial_pr_numero_requision').value=resultado[1];
						getObj('orden_especial_pr_numero_precompromiso').value=resultado[2];
						getObj('orden_especial_pr_numero').value =resultado[1]; 
						getObj('orden_especial_pr_producto').value="";
						getObj('orden_especial_pr_cantidad').value ="";
						getObj('orden_especial_pr_monto').value ="0,00"; 
						
						//setBarraEstado(html);
				}else if (html=="Okk")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_ordenes_contabilidad');
					jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+resultado[2],page:1}).trigger("reloadGrid");
						getObj('orden_especial_pr_numero_requision').value=resultado[1];
						getObj('orden_especial_pr_numero').value =resultado[1]; 
						getObj('orden_especial_pr_numero_precompromiso').value=resultado[2];
						getObj('orden_especial_pr_producto').value="";
						getObj('orden_especial_pr_cantidad').value =""; 
				}else if (html=="Existe")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL PRODUCTO/SERVICIO YA FUE AGREGADO </p></div>",true,true);
				}else if (html=="cotizacion_existe")
				{
					setBarraEstado(html);
				}
				//si la requisicion ya fue convertida en cotizacion
				/*//else if (html=="cotizacion_existe") 
			{
					alert("No se puede modificar la requisición debido a que ya fue convertida en cotización");
			}
				*////////////////////////////////////////////////////////////////////////////
				else
				{
				//alert('aqui');
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
						//getObj('orden_especial_pr_obesrvacion').value=html;
				}
			}
			});
		}else if(getObj('orden_especial_pr_numero_reglon').value!=""){ 
		//setBarraEstado(mensaje[esperando_respuesta]);
		urlXX= "modulos/presupuesto/ordenes_especiales/pr/sql.actualizar_con.php";
		$.ajax (
		{
		url: "modulos/presupuesto/ordenes_especiales/pr/sql.actualizar_con.php",
			data:dataForm('form_pr_ordenes_contabilidad'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split("*");
			//alert(resultado[0]);
				if (resultado[0]=="Ok")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_ordenes_contabilidad');
		//	alert("modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+getObj('orden_especial_pr_numero').value);

					jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+getObj('orden_especial_pr_numero_precompromiso').value,page:1}).trigger("reloadGrid");
						//getObj('orden_especial_pr_numero_requision').value=resultado[1];
						//getObj('orden_especial_pr_numero').value =resultado[1]; ;
				}else if (resultado[0]=="Existe")
				{
		//		alert('aqui1');
					setBarraEstado(resultado[1]);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL PRODUCTO/SERVICIO YA FUE ACTUALIZADO </p></div>",true,true);
				}else if (html=="cotizacion_existe")
				{
			//	alert('aqui3');
					//setBarraEstado(mensaje[convertirda],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />YA TIENE COMPROMISO </p></div>",true,true);
				}
				//si la requisicion ya fue convertida en cotizacion
				/*else if (html=="cotizacion_existe") 
			{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA REQUISICION YA TIENE UNA COTIZACION,<BR>NO SE PUEDE MODIFICAR </p></div>",true,true);
			}*/
				///////////////////////////////////////////////////////////////////////////
				else
				{
					//alert('aqui2 '+getObj('orden_especial_pr_accion_especifica_id').value);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
						//getObj('orden_especial_pr_obesrvacion').value=html;
				}
			}
		});
		}
	}else{
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />DEBE COLOCAR EL NUMERO DE ORDEN Y COMPROMISO </p></div>",true,true);
	}
	//}
});
$("#orden_especial_pr_btn_actualizar").click(function(){

	if($('#form_pr_ordenes_contabilidad').jVal())
	{
		 if(getObj('orden_especial_pr_numero_reglon').value!=""){ 
		//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/ordenes_especiales/pr/sql.actualizar_con.php",
			data:dataForm('form_pr_ordenes_contabilidad'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split("*");
			//alert(resultado[0]);
				if (resultado[0]=="Ok")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_ordenes_contabilidad');
					jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+resultado[1],page:1}).trigger("reloadGrid");
						//getObj('orden_especial_pr_numero_requision').value=resultado[1];
						//getObj('orden_especial_pr_numero').value =resultado[1]; ;
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
						//getObj('orden_especial_pr_obesrvacion').value=html;
				}
			}
		});
		}
	}
});
//
//
//
$("#orden_especial_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/ordenes_especiales/pr/vista.grid_requisicion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Impuesto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_requi= jQuery("#requisicion_db_requisicion").val(); 
					var busq_asunt= jQuery("#requisicion_db_asunto").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/ordenes_especiales/pr/sql_busqueda_requisicion_cont.php?busq_requi="+busq_requi+"&busq_asunt="+busq_asunt+"&ano="+getObj('orden_especial_pr_ano').value,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/ordenes_especiales/pr/sql_busqueda_requisicion_cont.php?busq_requi="+busq_requi+"&busq_asunt="+busq_asunt+"&ano="+getObj('orden_especial_pr_ano').value,page:1}).trigger("reloadGrid");
							
						}
			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/ordenes_especiales/pr/sql_busqueda_requisicion_cont.php?nd='+nd,
								datatype: "json",
								colNames:['Nº Orden Extraordinaria','Año','id_proyecto','id_accion','id_accion_especifica','Asunto','Proyecto/Accion Central','Proyecto/Accion Central1','Prioridad','Comentario','id_tipo_documento','Observacion','Accion Especifica1','Accion Especifica','id_unidad','Unidad','Codigo_unidad','tipo','id_proveedor','code_proveedor','proveedor','id_cus','code_cus','custodio','numero_precompromiso','numero_compromiso'],
								colModel:[
									{name:'numero_requisicion',index:'numero_requisicion', width:10,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:25,sortable:false,resizable:false,hidden:true},
									{name:'id_proyecto',index:'id_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_accion',index:'id_accion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:10,sortable:false,resizable:false,hidden:true},
									{name:'asunto',index:'asunto', width:20,sortable:false,resizable:false},
									{name:'codigo',index:'codigo', width:10,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:30,sortable:false,resizable:false,hidden:true},
									{name:'prioridad',index:'prioridad', width:10,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:10,sortable:false,resizable:false,hidden:true},
									{name:'ano_csc',index:'ano_csc', width:10,sortable:false,resizable:false,hidden:true},
									{name:'id_tipo_documento',index:'id_tipo_documento', width:10,sortable:false,resizable:false,hidden:true},
									{name:'accion_especifica',index:'accion_especifica', width:30,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_especifica',index:'codigo_accion_especifica', width:10,sortable:false,resizable:false},
									{name:'id_unidadd',index:'id_unidadd', width:10,sortable:false,resizable:false,hidden:true},
									{name:'unidadd',index:'unidadd', width:30,sortable:false,resizable:false,hidden:true},
									{name:'codigo_unidadd',index:'codigo_unidadd', width:10,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:10,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:10,sortable:false,resizable:false,hidden:true},
									{name:'code_proveedor',index:'code_proveedor', width:10,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:10,sortable:false,resizable:false,hidden:true},
									{name:'id_cus',index:'id_cus', width:10,sortable:false,resizable:false,hidden:true},
									{name:'code_cus',index:'code_cus', width:10,sortable:false,resizable:false,hidden:true},
									{name:'custodio',index:'custodio', width:10,sortable:false,resizable:false,hidden:true},
									{name:'numero_precompromiso',index:'numero_precompromiso', width:10,sortable:false,resizable:false,hidden:true},
									{name:'numero_compromiso',index:'numero_compromiso', width:10,sortable:false,resizable:false,hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('orden_especial_pr_numero_requision').value = ret.numero_requisicion;
									getObj('orden_especial_pr_asunto').value = ret.asunto;
									if (ret.tipo == 1){
										getObj('orden_especial_pr_proyecto_id').value = ret.id_proyecto;
										//alert(getObj('orden_especial_pr_proyecto_id').value);
										getObj('orden_especial_pr_codigo_proyecto').value = ret.codigo;										
										
										getObj('orden_especial_pr_proyecto').value = ret.nombre;
										getObj('orden_especial_pr_accion_central_id').value =0;
										getObj('orden_especial_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
										getObj('orden_especial_pr_codigo_central').value="0000";
										getObj('orden_especial_pr_codigo_central').disabled="disabled"; 
										getObj('orden_especial_pr_accion_central').disabled="disabled";
									}
									if (ret.tipo == 2){
										getObj('orden_especial_pr_accion_central_id').value = ret.id_proyecto;
										getObj(getObj('orden_especial_pr_accion_central_id').value);
										getObj('orden_especial_pr_codigo_central').value = ret.codigo;
										getObj('orden_especial_pr_accion_central').value = ret.nombre;
										getObj('orden_especial_pr_proyecto_id').value=0;
										getObj('orden_especial_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
										getObj('orden_especial_pr_codigo_proyecto').value ="0000" ;
										getObj('orden_especial_pr_codigo_proyecto').disabled="disabled" ;
										getObj('orden_especial_pr_proyecto').disabled="disabled" ;
															}
									getObj('orden_especial_pr_accion_especifica_id').value = ret.id_accion_especifica;
									getObj('orden_especial_pr_accion_especifica_codigo').value = ret.codigo_accion_especifica;
									getObj('orden_especial_pr_accion_especifica').value = ret.accion_especifica;
									 
									getObj('orden_especial_pr_unidad_id').value = ret.id_unidadd;
									getObj('orden_especial_pr_unidad').value = ret.unidadd;
									getObj('orden_especial_pr_codigo_unidad').value = ret.codigo_unidadd;
									 
									getObj('orden_especial_pr_obesrvacion').value = ret.observacion;
									getObj('orden_especial_pr_numero').value = ret.numero_requisicion;
										   												 
									getObj('orden_especial_pr_proveedor_id').value = ret.id_proveedor;
									getObj('orden_especial_pr_codigo').value = ret.code_proveedor;
									getObj('orden_especial_pr_proveedor').value = ret.proveedor;
									
									/*getObj('orden_especial_pr_unidad_custodio_id').value = ret.id_cus;
									getObj('orden_especial_pr_codigo_unidad_custodio').value = ret.code_cus;
									getObj('orden_especial_pr_unidad_custodio').value = ret.custodio;*/
									getObj('orden_especial_pr_numero_precompromiso').value = ret.numero_precompromiso;
									getObj('orden_especial_pr_numero_compromiso').value = ret.numero_compromiso;
									//numero_precompromiso
									dialog.hideAndUnload();
									//alert("modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+ret.numero_requisicion+"&id_unidadd="+ret.id_unidadd);
									jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+ret.numero_precompromiso+"&id_unidadd="+ret.id_unidadd,page:1}).trigger("reloadGrid");
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
// -----------------------------------------------------------------------------------------------------------------------------------

$("#orden_especial_pr_btn_consultar_unidad").click(function() {

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/ordenes_especiales/pr/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad  Solicitante', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/ordenes_especiales/pr/cmb.sql.unidad_contabilidad.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Unidad'],
								colModel:[
									{name:'id',index:'id', width:30,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:25,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('orden_especial_pr_unidad_id').value = ret.id;
									getObj('orden_especial_pr_codigo_unidad').value = ret.codigo;
									getObj('orden_especial_pr_unidad').value = ret.denominacion;
									getObj('orden_especial_pr_accion_central_id').value = ""
									getObj('orden_especial_pr_accion_central').value="";
									getObj('orden_especial_pr_codigo_central').value="";
									getObj('orden_especial_pr_proyecto').value ="";
									getObj('orden_especial_pr_proyecto_id').value ="";
									getObj('orden_especial_pr_codigo_proyecto').value ="";
									getObj('orden_especial_pr_codigo_proyecto').disabled="" ;
									getObj('orden_especial_pr_codigo_central').disabled=""; 
									getObj('orden_especial_pr_accion_central').disabled="";
									getObj('orden_especial_pr_accion_especifica').disabled=""
									getObj('orden_especial_pr_accion_especifica_codigo').disabled="";
									getObj('orden_especial_pr_accion_especifica').value="";
									getObj('orden_especial_pr_accion_especifica_codigo').value="";
									getObj('orden_especial_pr_accion_especifica_id').value="";
									
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
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});
/// ----------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_unidad()
{
		$.ajax({
			url:"modulos/presupuesto/ordenes_especiales/pr/sql_grid_unidad_co.php",
			//"modulos/presupuesto/ordenes_especiales/pr/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_ordenes_contabilidad'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html;
			  				//alert(html);
				//getObj('orden_especial_pr_asunto').value=html;
				//getObj('orden_especial_pr_accion_especifica').value=html;
			if(recordset)
				{
				recordset = recordset.split("*");
				getObj('orden_especial_pr_unidad_id').value = recordset[0];
				getObj('orden_especial_pr_unidad').value = recordset[1];
				
				}
				else
			   {  
			   	getObj('orden_especial_pr_unidad_id').value = "";
				//getObj('orden_especial_pr_accion_especifica_codigo').value = "";
				getObj('orden_especial_pr_unidad').value = "";
			    }
			 }
		});	 	 
	
}
//-------------------------------------------------------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------------------------------------------------
$("#orden_especial_pr_btn_consultar_proyecto").click(function() {
if(getObj('orden_especial_pr_accion_central_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/ordenes_especiales/pr/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proyecto', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proyecto_re.php?nd='+nd+'&unidad='+getObj('orden_especial_pr_unidad_id').value,
								datatype: "json",
								colNames:['id','Codigo', 'Proyecto'/*,'Saldo'*/],
								colModel:[
									{name:'id',index:'id', width:30,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:25,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}/*,
									{name:'saldo',index:'saldo', width:50,sortable:false,resizable:false}*/
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('orden_especial_pr_proyecto_id').value = ret.id;
									getObj('orden_especial_pr_codigo_proyecto').value = ret.codigo
									getObj('orden_especial_pr_proyecto').value = ret.denominacion;
									getObj('orden_especial_pr_accion_central_id').value = ""
									getObj('orden_especial_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
									getObj('orden_especial_pr_codigo_central').value="0000";
									getObj('orden_especial_pr_codigo_central').disabled="disabled"; 
									getObj('orden_especial_pr_accion_central').disabled="disabled";
									getObj('orden_especial_pr_accion_especifica').disabled=""
									getObj('orden_especial_pr_accion_especifica_codigo').disabled="";
									getObj('orden_especial_pr_accion_especifica').value="";
									getObj('orden_especial_pr_accion_especifica_codigo').value="";
									getObj('orden_especial_pr_accion_especifica_id').value="";
									
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
$("#orden_especial_pr_btn_consultar_accion_central").click(function() {
if(getObj('orden_especial_pr_proyecto_id').value =="")
{
//alert('modulos/presupuesto/ordenes_especiales/pr/cmb.sql.accion_central.php?nd='+nd+'&anio='+getObj('orden_especial_pr_ano').value+'&unidad='+getObj('orden_especial_pr_unidad_id').value);
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/ordenes_especiales/pr/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/ordenes_especiales/pr/cmb.sql.accion_central_contabilidad.php?nd='+nd+'&unidad='+getObj('orden_especial_pr_unidad_id').value,
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
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('orden_especial_pr_accion_central_id').value = ret.id;
									
									getObj('orden_especial_pr_codigo_central').value = ret.codigo;
									getObj('orden_especial_pr_accion_central').value = ret.denominacion;
									getObj('orden_especial_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
									getObj('orden_especial_pr_codigo_proyecto').value ="0000" ;
									getObj('orden_especial_pr_codigo_proyecto').disabled="disabled" ;
									getObj('orden_especial_pr_proyecto').disabled="disabled" ;
									getObj('orden_especial_pr_accion_especifica').disabled=""
									getObj('orden_especial_pr_accion_especifica_codigo').disabled="";
									getObj('orden_especial_pr_accion_especifica').value="";
									getObj('orden_especial_pr_accion_especifica_codigo').value="";
									getObj('orden_especial_pr_accion_especifica_id').value="";
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
$("#orden_especial_pr_btn_consultar_accion_especifica").click(function() {

if(getObj('orden_especial_pr_proyecto_id').value !="" || getObj('orden_especial_pr_accion_central_id').value !="")
{
	var nd=new Date().getTime();
		//urls='modulos/presupuesto/ordenes_especiales/pr/cmb.sql.accion_especifica.php?nd='+nd+'&accion_central_id ='+getObj('orden_especial_pr_accion_central_id').value+'&proyecto_id ='+getObj('orden_especial_pr_proyecto_id').value;
		//urls='modulos/presupuesto/ordenes_especiales/pr/cmb.sql.accion_especifica.php?proyecto_id ="+getObj('orden_especial_pr_proyecto_id').value;
//alert('modulos/presupuesto/ordenes_especiales/pr/cmb.sql.accion_especifica.php?nd='+nd+'&accion_central ='+getObj('orden_especial_pr_accion_central_id').value+'&proyecto ='+getObj('orden_especial_pr_proyecto_id').value);
	//alert(urls);

	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/ordenes_especiales/pr/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Especifica', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								//url:urls,
 								url:'modulos/presupuesto/ordenes_especiales/pr/cmb.sql.accion_especifica_contabilidad.php?nd='+nd+'&unidad='+getObj('orden_especial_pr_unidad_id').value+'&accion_central='+getObj('orden_especial_pr_accion_central_id').value+'&proyecto='+getObj('orden_especial_pr_proyecto_id').value,	
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
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('orden_especial_pr_accion_especifica_id').value = ret.id;
									getObj('orden_especial_pr_accion_especifica_codigo').value = ret.codigo;
									getObj('orden_especial_pr_accion_especifica').value = ret.denominacion;
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
$("#orden_especial_pr_btn_partida").click(function() {

if(getObj('orden_especial_pr_accion_especifica_id').value !="")
{
	urls='modulos/presupuesto/ordenes_especiales/pr/cmb.sql.partida_contabilidad.php?nd='+nd+'&unidad='+getObj('orden_especial_pr_unidad_id').value+'&unidad_es='+getObj('orden_especial_pr_accion_especifica_id').value+'&accion='+getObj('orden_especial_pr_accion_central_id').value+'&proyecto='+getObj('orden_especial_pr_proyecto_id').value+'&gasto='+getObj('orden_especial_pr_tipo_doc').value;
		//alert(urls);  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/ordenes_especiales/pr/grid_requisiciones.php", { },
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
								colNames:['Id','Partida', 'Denominacion'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('orden_especial_pr_accion_especifica_id').value = ret.id;
									
										getObj('orden_especial_pr_partida').value = ret.partida;
										getObj('orden_especial_pr_producto').value = ret.denominacion;
									
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
			url:"modulos/presupuesto/ordenes_especiales/pr/sql_grid_accion_cental_codigo.php",
            data:dataForm('form_pr_ordenes_contabilidad'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{

				recordset = recordset.split("*");
				
				getObj('orden_especial_pr_accion_central_id').value = recordset[0];
				getObj('orden_especial_pr_accion_central').value=recordset[1];
				getObj('orden_especial_pr_proyecto_id').value="";
				
				getObj('orden_especial_pr_accion_especifica').value="";
				getObj('orden_especial_pr_accion_especifica_codigo').value="";
				getObj('orden_especial_pr_accion_especifica_id').value="";
				
				getObj('orden_especial_pr_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('orden_especial_pr_codigo_proyecto').value ="0000" ;
				getObj('orden_especial_pr_codigo_proyecto').disabled="disabled" ;
				getObj('orden_especial_pr_proyecto').disabled="disabled" ;
				getObj('orden_especial_pr_accion_especifica').disabled=""
				getObj('orden_especial_pr_accion_especifica_codigo').disabled="";
				
				}
				else
			 {  
				getObj('orden_especial_pr_accion_central_id').value ="";
				getObj('orden_especial_pr_accion_central').value="";
				getObj('orden_especial_pr_proyecto_id').value="";
				getObj('orden_especial_pr_proyecto').value="";
				getObj('orden_especial_pr_codigo_proyecto').value ="" ;
				getObj('orden_especial_pr_codigo_proyecto').disabled="" ;
				getObj('orden_especial_pr_accion_especifica').value="";
				getObj('orden_especial_pr_accion_especifica_codigo').value="";
				getObj('orden_especial_pr_accion_especifica_id').value="";
				}
			 }
		});	 	 
}
// ----------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/ordenes_especiales/pr/sql_grid_proyecto_codigo_co.php",
            data:dataForm('form_pr_ordenes_contabilidad'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('orden_especial_pr_proyecto_id').value = recordset[0];
				getObj('orden_especial_pr_proyecto').value=recordset[1];
				getObj('orden_especial_pr_accion_central_id').value = ""
				getObj('orden_especial_pr_accion_especifica').value="";
				getObj('orden_especial_pr_accion_especifica_codigo').value="";
				getObj('orden_especial_pr_accion_especifica_id').value="";
				getObj('orden_especial_pr_accion_central').value="  NO APLICA ESTA OPCION  ";
				getObj('orden_especial_pr_codigo_central').value="0000";
				getObj('orden_especial_pr_codigo_central').disabled="disabled"; 
				getObj('orden_especial_pr_accion_central').disabled="disabled";
				getObj('orden_especial_pr_accion_especifica').disabled=""
				getObj('orden_especial_pr_accion_especifica_codigo').disabled="";
				
				}
				else
			 {  
			   	getObj('orden_especial_pr_proyecto_id').value ="";
				getObj('orden_especial_pr_proyecto').value="";
				getObj('orden_especial_pr_accion_central_id').value = ""
				getObj('orden_especial_pr_accion_central').value="";
				getObj('orden_especial_pr_codigo_central').value="";
				getObj('orden_especial_pr_codigo_central').disabled=""; 
				getObj('orden_especial_pr_accion_especifica').value="";
				getObj('orden_especial_pr_accion_especifica_codigo').value="";
				getObj('orden_especial_pr_accion_especifica_id').value="";
				}
			 }
		});	 	 
}
/// ----------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_accion_especifica()
{
		$.ajax({
			url:"modulos/presupuesto/ordenes_especiales/pr/sql_grid_accion_especifica_co.php",
			//"modulos/presupuesto/ordenes_especiales/pr/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_ordenes_contabilidad'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html;
			  				//alert(html);
				//getObj('orden_especial_pr_asunto').value=html;
				//getObj('orden_especial_pr_accion_especifica').value=html;
			if(recordset)
				{
				recordset = recordset.split("*");
				getObj('orden_especial_pr_accion_especifica_id').value = recordset[0];
				getObj('orden_especial_pr_accion_especifica').value = recordset[1];
				
				}
				else
			   {  
			   	getObj('orden_especial_pr_accion_especifica_id').value = "";
				//getObj('orden_especial_pr_accion_especifica_codigo').value = "";
				getObj('orden_especial_pr_accion_especifica').value = "";
			    }
			 }
		});	 	 
	
}
//-------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
$("#orden_especial_pr_btn_consultar_proveedor").click(function() {
/*
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/ordenes_especiales/pr/grid_proveedor.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedor', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
						//alert('aqui');
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:280,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('orden_especial_pr_proveedor_id').value = ret.id_proveedor;
									getObj('orden_especial_pr_codigo').value = ret.codigo;
									getObj('orden_especial_pr_proveedor').value = ret.nombre;
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
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#proveedor_db_btn_consultar").click(function() {*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/ordenes_especiales/pr/vista.grid_proveedor.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proveedor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#proveedor_db_nombre_proveedor").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proveedor.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#proveedor_db_nombre_proveedor").keypress(function(key)
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
							var busq_nombre= jQuery("#proveedor_db_nombre_proveedor").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proveedor.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Proveedor'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'codigo_proveedor', width:25,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:150,sortable:false,resizable:false}
//covertura_distribucion
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('orden_especial_pr_proveedor_id').value = ret.id_proveedor;
									getObj('orden_especial_pr_codigo').value = ret.codigo_proveedor;
									getObj('orden_especial_pr_proveedor').value = ret.nombre;
									dialog.hideAndUnload();
					 			},
								/*onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									limpiar_proveedor();
									getObj('orden_especial_pr_proveedor_id').value = ret.id_proveedor;
									getObj('orden_especial_pr_codigo').value = ret.codigo_proveedor;
									getObj('orden_especial_pr_proveedor').value = ret.nombre;
									
								
							
							dialog.hideAndUnload();
					 			},*/

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#proveedor_db_nombre_proveedor").focus();
								$('#proveedor_db_nombre_proveedor').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//------------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------

/*$("#orden_especial_pr_btn_consultar_proveedor").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/ordenes_especiales/pr/grid_proveedor.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_requi= jQuery("#orden_especial_pr_proeevedor_conta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proveedor.php?busq_requi="+busq_requi,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#orden_especial_pr_proeevedor_conta").keypress(function(key)
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
							var busq_requi= jQuery("#orden_especial_pr_proeevedor_conta").val();
							
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proveedor.php?busq_requi="+busq_requi,page:1}).trigger("reloadGrid");
							
						}
			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/ordenes_especiales/pr/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:280,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('orden_especial_pr_proveedor_id').value = ret.id_proveedor;
									getObj('orden_especial_pr_codigo').value = ret.codigo;
									getObj('orden_especial_pr_proveedor').value = ret.nombre;
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
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});					
		}
	});*/
//
//
// -----------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------
//
$("#orden_especial_pr_btn_cancelar").click(function() {
/*getObj('orden_especial_pr_codigo_central').disabled=""; 
getObj('orden_especial_pr_codigo_proyecto').disabled="" ;
getObj('orden_especial_pr_codigo_central').disabled=""; 
getObj('orden_especial_pr_ano').value ="";
 getObj('orden_especial_pr_proyecto').value ="";
 getObj('orden_especial_pr_proyecto_id').value ="";
 getObj('orden_especial_pr_codigo_proyecto').value ="";
 getObj('orden_especial_pr_codigo_central').value ="";
 getObj('orden_especial_pr_accion_central').value ="";
 getObj('orden_especial_pr_accion_central_id').value ="";
  getObj('orden_especial_pr_accion_especifica').value ="";
 getObj('orden_especial_pr_accion_especifica_codigo').value ="";
 getObj('orden_especial_pr_accion_especifica_id').value ="";
 getObj('orden_especial_pr_asunto').value ="";
 getObj('orden_especial_pr_obesrvacion').value ="";
 getObj('orden_especial_pr_producto').value ="";
 getObj('orden_especial_pr_partida').value ="";
getObj('orden_especial_pr_cantidad').value ="";
getObj('orden_especial_pr_tipo_doc').value="";         
getObj('orden_especial_pr_unidad_medida').value ="";
getObj('orden_especial_pr_numero').value="";*/
clearForm('form_pr_ordenes_contabilidad');
jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php"}).trigger("reloadGrid");
/*getObj('orden_especial_pr_accion_especifica').disabled="disabled"
getObj('orden_especial_pr_accion_especifica_codigo').disabled="disabled";
*/
 	} );
//---------------------------------------------------------------------------------------------------------------------------------
$('#orden_especial_pr_codigo_unidad').change(consulta_automatica_unidad);
$('#orden_especial_pr_codigo_central').change(consulta_automatica_accion_central);
$('#orden_especial_pr_codigo_proyecto').change(consulta_automatica_proyecto);
$('#orden_especial_pr_accion_especifica_codigo').change(consulta_automatica_accion_especifica);


$('#orden_especial_pr_orden_especial_pr_asunto').alpha({allow:'áéíóúÁÉÍÓÚ //0123456789'});
$('#orden_especial_pr_producto').alpha({allow:'áéíóúÁÉÍÓÚ1234567890.//()-,"$& '});
$('#orden_especial_pr_cantidad').numeric({allow:'.'});


</script>
<script type="text/javascript">
var lastsel,idd;

$("#list_ordenes").jqGrid({
	height: 85,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','N&ordm; Renglon','Cantidad','id_unidad_medida','Unidad Medida','Descripcion','numero_requision','Partida','Par','Monto','Iva','Total'],
   	colModel:[
	   		{name:'orden_especial_pr_numero_reglon',index:'orden_especial_pr_numero_reglon', width:50,hidden:true},
	   		{name:'reglon',index:'reglon', width:30},
			{name:'orden_especial_pr_cantidad',index:'orden_especial_pr_cantidad', width:30,editable:true, editable:true},
			{name:'orden_especial_pr_unidad_medida',index:'orden_especial_pr_unidad_medida', width:100,hidden:true},
			{name:'nombre',index:'nombre', width:60},
			{name:'orden_especial_pr_producto',index:'orden_especial_pr_producto', width:200, editable:true},
			{name:'numero_requision',index:'numero_requision', width:50,hidden:true},
			{name:'orden_especial_pr_partida',index:'orden_especial_pr_partida', width:50,hidden:true},
			{name:'orden_especial_pr_tipo_doc',index:'orden_especial_pr_tipo_doc', width:50,hidden:true},
			{name:'orden_especial_pr_monto',index:'orden_especial_pr_monto', width:50},
			{name:'orden_especial_pr_iva',index:'orden_especial_pr_iva', width:50},
			{name:'total',index:'total', width:50}
   	],
   	rowNum:5,
   	rowList:[5,10,15],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_ordenes_extra'),
   	sortname: 'id_orden_compra_serviciod',
    viewrecords: true,
    sortorder: "asc",
}).navGrid("#pager_ordenes_extra",{search :false,edit:false,add:false,del:false})
.navButtonAdd('#pager_ordenes_extra',{caption:"Editar",
	onClickButton:function(){
		var gsr = jQuery("#list_ordenes").getGridParam('selrow');
		if(gsr){
			//alert(gsr);
			getObj('orden_especial_pr_numero_reglon').value=gsr;
			jQuery("#list_ordenes").GridToForm(gsr,"#form_pr_ordenes_contabilidad");
		} else {
			alert("Por Seleccione una Linea")
		}							
	} 
}).navButtonAdd('#pager_ordenes_extra',{caption:"Eliminar",
	onClickButton:function(){
	//alert("AQUI");
	
		var gsr = jQuery("#list_ordenes").getGridParam('selrow');
	if(gsr){
			if(confirm("¿Desea eliminar este item?")) 
		{
//		alert("modulos/presupuesto/ordenes_especiales/pr/sql.eliminar.php?id="+gsr+"&requi="+getObj('orden_especial_pr_numero').value+"&accionc="+getObj('orden_especial_pr_accion_central_id').value+"&proyecto="+getObj('orden_especial_pr_proyecto_id').value+"&accione="+getObj('orden_especial_pr_accion_especifica_id').value+"&unidad="+getObj('orden_especial_pr_unidad_id').value+"&partida="+getObj('orden_especial_pr_partida').value);
			$.ajax ({
			url: "modulos/presupuesto/ordenes_especiales/pr/sql.eliminar_con.php?id="+gsr+"&requi="+getObj('orden_especial_pr_numero_precompromiso').value+"&accionc="+getObj('orden_especial_pr_accion_central_id').value+"&proyecto="+getObj('orden_especial_pr_proyecto_id').value+"&accione="+getObj('orden_especial_pr_accion_especifica_id').value+"&unidad="+getObj('orden_especial_pr_unidad_id').value,
				data:dataForm('form_pr_ordenes_contabilidad'), 
				type:'GET',
				cache: false,
				success: function(html)
				{//alert("AQUI 2");
				if (html=="Ok")
					{
							//alert(gsr);

						setBarraEstado(mensaje[eliminacion_exitosa],true,true);
						jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php?numero_requision="+getObj('orden_especial_pr_numero_precompromiso').value,page:1}).trigger("reloadGrid");
						//clearForm('form_pr_ordenes_contabilidad');
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
	jQuery("#list_ordenes").setGridParam({url:"modulos/presupuesto/ordenes_especiales/co/sql.consulta_contabilidad.php",page:1}).trigger("reloadGrid"); 
} 

$("#orden_especial_pr_btn_pdf").click(function(){

	//requisiones_ano = getObj('orden_especial_pr_ano').value;
	numero_requi = getObj('orden_especial_pr_numero_requision').value;
	pre = getObj('orden_especial_pr_numero_precompromiso').value;
	//url="pdf.php?p=modulos/adquisiones/requisiciones/rp/vista.lst.requisicion.php¿numero_requi="+numero_requi"+§ano="requisiones_ano; 
	url="pdf.php?p=modulos/presupuesto/ordenes_especiales/rp/vista.lst.orden_extraordinaria.php¿numero_requi="+numero_requi+"@pre="+pre; 
	//alert(url);
	openTab("Orden Extraordinaria",url);
});

var porcen = getObj("orden_especial_pr_porcentaje").value;
if (porcen!="")
	getObj('orden_especial_pr_iva').value = porcen.replace('.',',');
else
	getObj('orden_especial_pr_iva').value = "0,00";

	/*
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#proyecto_co_btn_consultar").attr("enable",state); 
}*/
$('#proyecto_busqueda_proyecto').alpha({nocaps:true,allow:'´'});

</script>

<div id="botonera">
	<img id="orden_especial_pr_btn_cancelar" class="btn_cancelar"  src="imagenes/null.gif" />
	<!--<img id="orden_especial_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />-->
	<img id="orden_especial_pr_btn_consultar" name="orden_especial_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="orden_especial_pr_btn_pdf" class="btn_imprimir"src="imagenes/null.gif"  />		
</div>

<form method="post" name="form_pr_ordenes_contabilidad" id="form_pr_ordenes_contabilidad">
<input type="hidden" name="orden_especial_pr_numero_precompromiso" id="orden_especial_pr_numero_precompromiso" />
<input type="hidden" name="orden_especial_pr_numero_requision" id="orden_especial_pr_numero_requision" value=""  />
<input type="hidden" name="orden_especial_pr_numero_reglon" id="orden_especial_pr_numero_reglon"/>
<input type='hidden' name='orden_especial_pr_porcentaje' id='orden_especial_pr_porcentaje' value='<?=number_format($porcentajexx,2,',','.')?>'/>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Carga de Orden y Compromiso </th>  
	</tr>
	<!--<tr>
		<th colspan="2">Unidad Solicitante: <?//=utf8_decode($rs_unida->fields("nombre"))?></th>
	</tr>-->
   <tr>
    <th width="50%"> A&ntilde;o	
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
    <td width="50%"><?= date('Y');?><!--<select name="orden_especial_pr_ano" id="orden_especial_pr_ano" style="min-width:60px; width:60px;">
      <option value="<?//= date('Y')-1 ;?>">
        <?//= date('Y')-1 ;?>
        </option>
      <option value="<?//= date('Y');?>" selected="selected">
        <?//= date('Y');?>
        </option>
    </select> -->
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   
	N&ordm; Orden
          <input name="orden_especial_pr_numero"  type="text" id="orden_especial_pr_numero"  maxlength="10" 
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
    &nbsp;&nbsp;&nbsp;&nbsp;
			
	N&ordm; Compromiso
          <input name="orden_especial_pr_numero_compromiso"  type="text" id="orden_especial_pr_numero_compromiso"  maxlength="10" 
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
      </td>
  </tr>

	
 <tr>
    <th>Unidad Solicitante</th>
    <td>
	<ul class="input_con_emergente">
		<li>			
				<input name="orden_especial_pr_codigo_unidad" type="text" id="orden_especial_pr_codigo_unidad"  maxlength="5" size="5"
				onchange="consulta_automatica_unidad" onclick="consulta_automatica_unidad"
       			message="Introduzca codigo de la Unidad." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{4,5}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
       			 <input type="text" name="orden_especial_pr_unidad" id="orden_especial_pr_unidad" 
				style="width:62ex;" maxlength="60"  message="Introduzca el Nombre de la Unidad." />
       			 <input type="hidden" name="orden_especial_pr_unidad_id" id="orden_especial_pr_unidad_id" />
        </li>
		<li id="orden_especial_pr_btn_consultar_unidad" class="btn_consulta_emergente"></li>
	</ul>	</td>
  </tr>
  <!-- <tr>
    <th>Unidad Custodio</th>
    <td>
	<ul class="input_con_emergente">
		<li>			
				<input name="orden_especial_pr_codigo_unidad_custodio" type="text" id="orden_especial_pr_codigo_unidad_custodio"  maxlength="5" size="5"
				onchange="consulta_automatica_unidad_custodio" onclick="consulta_automatica_unidad"
       			message="Introduzca codigo de la Unidad." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{4,5}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
       			 <input type="text" name="orden_especial_pr_unidad_custodio" id="orden_especial_pr_unidad_custodio" 
				style="width:62ex;" maxlength="60"  message="Introduzca el Nombre de la Unidad." />
       			 <input type="hidden" name="orden_especial_pr_unidad_custodio_id" id="orden_especial_pr_unidad_custodio_id" />
        </li>
		<li id="orden_especial_pr_btn_consultar_unidad_custodio" class="btn_consulta_emergente"></li>
	</ul>	</td>
  </tr>-->
  <tr>
    <th>Acci&oacute;n Centralizada</th>
    <td>
	<ul class="input_con_emergente">
		<li>			
				<input name="orden_especial_pr_codigo_central" type="text" id="orden_especial_pr_codigo_central"  maxlength="6" size="6"
				onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
       			message="Introduzca codigo de  la accion central." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{5,6}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
       			 <input type="text" name="orden_especial_pr_accion_central" id="orden_especial_pr_accion_central" 
				style="width:62ex;" maxlength="60"  message="Introduzca el Nombre del Proyecto." />
       			 <input type="hidden" name="orden_especial_pr_accion_central_id" id="orden_especial_pr_accion_central_id" />
        </li>
		<li id="orden_especial_pr_btn_consultar_accion_central" class="btn_consulta_emergente"></li>
	</ul>	</td>
  </tr>
  <tr>
    <th>Proyecto</th>
    <td>
	<ul class="input_con_emergente">
		<li>
				<input name="orden_especial_pr_codigo_proyecto" type="text" id="orden_especial_pr_codigo_proyecto"  maxlength="6" 
				onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
       			message="Introduzca un Codigo para el Proyecto."  size="6"
				jVal="{valid:/^[0-9]{5,6}$/, message:'Codigo Invalido', styleType:'cover'}">	
			
				<input type="text" name="orden_especial_pr_proyecto" id="orden_especial_pr_proyecto"  
						style="width:62ex;" maxlength="60"  message="Introduzca el Nombre del Proyecto." />
				<input type="hidden" name="orden_especial_pr_proyecto_id" id="orden_especial_pr_proyecto_id" />
		</li>
		<li id="orden_especial_pr_btn_consultar_proyecto" class="btn_consulta_emergente"></li>
	</ul>  </tr>
  
  <tr>
 
    <th>Acci&oacute;n Espec&iacute;fica</th>
    <td>
	 <ul class="input_con_emergente">
		<li>	
		<input name="orden_especial_pr_accion_especifica_codigo" type="text" id="orden_especial_pr_accion_especifica_codigo"  maxlength="6" size="6"
				onchange="consulta_automatica_accion_especifica" onclick="consulta_automatica_accion_especifica" 
            	message="Introduzca codigo de la acción específica." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{4,8}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
			
		<input type="text" name="orden_especial_pr_accion_especifica" id="orden_especial_pr_accion_especifica" 
				style="width:62ex;" maxlength="200" message="Introduzca la Acci&oacute;n Espec&iacute;fica." 
				jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,]{1,200}$/, message:'Acci&oacute;n Espec&iacute;fica Invalida', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789.,]/, cFunc:'alert', cArgs:['Acci&oacute;n Espec&iacute;fica: '+$(this).val()]}"	/>
        <input type="hidden" name="orden_especial_pr_accion_especifica_id" id="orden_especial_pr_accion_especifica_id" />
        </li>
		<li id="orden_especial_pr_btn_consultar_accion_especifica" class="btn_consulta_emergente"></li>
	</ul>	</td>
  </tr>
  <tr>
			<th>Proveedor</th>
			<td>
			<table  width="100%" class="clear">
					<tr>
						<td style="width:390px">
							<input name="orden_especial_pr_codigo" type="text" id="orden_especial_pr_codigo"  maxlength="4"
							onchange="consulta_automatica_proveedor" onclick="consulta_automatica_proveedor"
							message="Introduzca un Codigo para el proveedor."  size="5"
							jVal="{valid:/^[0-9]{4}$/, message:'Codigo Invalido', styleType:'cover'}"
							jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" >
				
							<input type="text" name="orden_especial_pr_proveedor" id="orden_especial_pr_proveedor" size="60"
							message="Introduzca el nombre del Proveedor." readonly />						</td>
						<td>
							<input type="hidden" name="orden_especial_pr_proveedor_id" id="orden_especial_pr_proveedor_id" readonly />
							<img class="btn_consulta_emergente" id="orden_especial_pr_btn_consultar_proveedor" src="imagenes/null.gif" />						</td>
					</tr>
				</table>				</td>
		</tr>
  <tr>
    <th>Concepto de la Orden </th>
    <td><textarea name="orden_especial_pr_asunto" cols="75" rows="2" id="orden_especial_pr_asunto" 
				message="Introduzca la Asunto del cual trata la requisici&oacute;n."
				jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789//.,-()]{2,200}$/, message:'Asunto Invalido', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789//.,-()]/, cFunc:'alert', cArgs:['Asunto: '+$(this).val()]}"></textarea></td>
  </tr>
  <tr>
    <th colspan="2"> <table class="clear" width="100%" border="0">
      <tr>
        <th>Tipo de gasto </th>
        <td><select name="orden_especial_pr_tipo_doc" id="orden_especial_pr_tipo_doc" style="min-width:150px; width:150px;" >
          <? //=$opt_tipo_doc;?>
          <option value="0" >----SELECCIONE---</option>
          <option value="401" >401-Gasto de Personal</option>
		  <option value="402" >402-Insumos</option>
		  <option value="403" >403-Servicios</option>
          <option value="404" >404-Activos Reales</option>
          <option value="407" >407-Transferencia y Donaciones</option>
		  <option value="408" >408-Otros Gastos</option>
        </select>        </td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th>Observaci&oacute;n</th>
    <td><textarea name="orden_especial_pr_obesrvacion" id="orden_especial_pr_obesrvacion" cols="75" rows="2" 
				message="Introduzca un observacion para la requisici&oacute;n."></textarea></td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#4c7595">&nbsp;</th>
  </tr>
  <tr>
    <th colspan="2"> <table class="clear" width="100%" border="0">
      <tr>
        <th>Descripci&oacute;n del Renglon </th>
        <td colspan="3"><textarea name="orden_especial_pr_producto"  id="orden_especial_pr_producto" 
							message="Introduzca el nombre del Producto/Servicio" cols="75" rows="1" readonly="readonly"
						></textarea>		</td>
		<td width="2%"><img id="orden_especial_pr_btn_anadir" src="imagenes/anadir.png"   /></td>
      </tr>
      <tr>
        <th >Partida</th>
        <th width="20%" style="text-align:center;">Cantidad</th>
        <th width="20%" style="text-align:center;">Unidad de Medida</th>
		<th width="20%" style="text-align:center;">Base Imponible </th>
		<th width="20%" style="text-align:center;">Valor Impuesto</th>
      </tr>
      <tr>
        <td width="20%" ><input type="text" name="orden_especial_pr_partida" id="orden_especial_pr_partida"
							message="Introduzca la partida." maxlength="12" size="12" />
              <img id="orden_especial_pr_btn_partida" class="btn_consulta_emergente" src="imagenes/null.gif"  /> </td>
        <td width="20%" align="center"><input type="text" name="orden_especial_pr_cantidad" id="orden_especial_pr_cantidad"
							message="Introduzca la cantidad del producto." maxlength="8" size="10"
							jval="{valid:/^[0123456789.]{1,10}$/, message:'Producto Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0123456789.]/, cFunc:'alert', cArgs:['Producto: '+$(this).val()]}" alt="integer"/>        </td>
        <td width="20%" align="center"><select name="orden_especial_pr_unidad_medida" id="orden_especial_pr_unidad_medida" style="min-width:150px; width:150px;">
          <option value="0">--------SELECCIONE--------</option>
          <?=$opt_unida_medida;?>
        </select>        </td>
        <td width="20%" align="center"><input type="text" name="orden_especial_pr_monto" id="orden_especial_pr_monto" maxlength="12"  style="text-align:right" size="15" alt="signed-decimal"/> </td>
		<td width="20%" align="center"><input name="orden_especial_pr_iva" type="text" id="orden_especial_pr_iva" style="text-align:right" size="6" maxlength="5" alt="signed-decimal-im"/></td>
		
		<!-- <td width="2%"><img id="orden_especial_pr_btn_actualizar" src="imagenes/actuliza15.png"   /> </td>-->
      </tr>
    </table></th>
  </tr>
  <tr>
    <td class="celda_consulta" colspan="2">
	
	<table id="list_ordenes" class="scroll" cellpadding="0" cellspacing="0"></table> 
	<div id="pager_ordenes_extra" class="scroll" style="text-align:center;"></div> 
	<br />		</td>
  </tr>
  <tr>
    <td colspan="2" class="bottom_frame">&nbsp;</td>
  </tr>
</table>
</form>
