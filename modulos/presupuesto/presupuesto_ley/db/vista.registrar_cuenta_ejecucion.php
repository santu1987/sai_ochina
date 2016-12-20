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
$("#aperturar_cuenta_pr_btn_guardar").click(function() {
	if(($('#form_pr_aperturar').jVal()))
	{
	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/presupuesto/presupuesto_ley/db/sql.aperturar_cuenta.php",
			data:dataForm('form_pr_aperturar'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
			//	alert('aqui');
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_pr_aperturar');
					getObj('aperturar_cuenta_pr_codigo_central').disabled='';
					getObj('aperturar_cuenta_pr_codigo_proyecto').disabled='';
					//getObj('aperturar_cuenta_pr_partida_numero').value =   	'';
					//getObj('aperturar_cuenta_pr_partida').value =   			'';
					//getObj('aperturar_cuenta_pr_codigo_especifica').value = 	'';
					//getObj('aperturar_cuenta_pr_nombre_especifica').value =  '';
					//getObj('aperturar_cuenta_pr_accion_especifica').value = 	'';
				}
				else if (html=="Existe")
				{
				//	alert('aqui 2');
					setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado(html);
				}
				
				else
				{
				//alert('aqui 3');
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("#aperturar_cuenta_pr_btn_consultar").click(function() {
unidades=getObj('aperturar_cuenta_pr_unidad_ejecutora').value;
cod_unidad=getObj('aperturar_cuenta_pr_codigo_unidad_ejecutora').value;
ano=getObj('aperturar_cuenta_pr_anio').value; 
if(unidades!="")
{
var nd=new Date().getTime();

	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				urls="modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?unidades="+unidades+"&ano="+ ano+"&cod_unidad="+ cod_unidad ;
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Presupuesto de ley  '+unidades+" "+'a&ntilde;o '+ano, modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:urls,page:1}).trigger("reloadGrid"); 
				
				}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//--------------------------------------------busqueda por accion centralizada proyecto----------------------------------------------------------------------------------------------------------------------
					$("#presupuesto_ley_db-consultas-busqueda_accion_proyecto").keypress(function(key)
						{
							if (key.keycode==13)$("#presupuesto_ley_db-consultas-busqueda_boton_filtro")
							if(key.keyCode==27){this.close();}
							
						});
				function presupuesto_ley_accion_proyecto_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(presupuesto_ley_accion_proyecto_gridReload,500)
						}
					function presupuesto_ley_accion_proyecto_gridReload()
					{
							var busq_accion_proyecto= jQuery("#presupuesto_ley_db-consultas-busqueda_accion_proyecto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?busq_accion_proyecto="+busq_accion_proyecto+"&unidades="+unidades+"&ano="+ano,page:1}).trigger("reloadGrid");			
						}
				
			
				//-------------------------------------------busqueda por partida--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
				$("#presupuesto_ley_db-consultas-busqueda_partida").keypress(function(key)
				{
						if (key.keycode==13)$("#presupuesto_ley_db-consultas-busqueda_boton_filtro")
						if(key.keyCode==27){this.close();}
				});
					function presupuesto_ley_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(presupuesto_ley_gridReload,500)
						}
					function presupuesto_ley_gridReload()
					{
							var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?busq_partida="+busq_partida+"&unidades="+unidades+"&ano="+ano,page:1}).trigger("reloadGrid");			
					}
					
					//------------------------------------------------busqueda accion	--------------------------------------------------------------------------------------------------------------------------
					$("#presupuesto_ley_db-consultas-busqueda_accion").keypress(function(key)
						{
							if (key.keycode==13)$("#presupuesto_ley_db-consultas-busqueda_boton_filtro")
							//getObj('presupuesto_ley_db-consultas-busqueda_accion').value;
							getObj('presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').value=""
							if(key.keyCode==27){this.close();}
							
						});
					function presupuesto_ley_accion_dosearch()
					{
							if(!flAuto) return; 
							if(timeoutHnd) 
								clearTimeout(timeoutHnd) 
								timeoutHnd = setTimeout(presupuesto_ley_accion_gridReload,500)
					}
					function presupuesto_ley_accion_gridReload()
					{
								var busq_accion= jQuery("#presupuesto_ley_db-consultas-busqueda_accion").val();
								jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?busq_accion="+busq_accion+"&unidades="+unidades+"&ano="+ano,page:1}).trigger("reloadGrid"); 			
					}
					//------------------------------fin busqueda de accion------------------------------------------------------------------------------------------------------------------
						//---------------------busqueda partida/accion---------------------------------------------------------------------------------------------------------------------------------------------------------
					function presupuesto_ley_partida_accion_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(presupuesto_ley_partida_accion_gridReload,500)
						}
					function presupuesto_ley_partida_accion_gridReload()
					{
							var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val();
							var busq_accion= jQuery("#presupuesto_ley_db-consultas-busqueda_accion").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?busq_accion="+busq_accion+"&busq_partida="+busq_partida+"&unidades="+unidades+"&ano="+ano,page:1}).trigger("reloadGrid");			
						
						}
					//---------------------------------fin busqueda partida/accion----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
					
					//---------------------------------Busqueda partida/proyecto-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
					function presupuesto_ley_partida_accion_proyecto_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(presupuesto_ley_partida_accion_proyecto_gridReload,500)
						}
					function presupuesto_ley_partida_accion_proyecto_gridReload()
					{		
				
							var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val();
							var busq_accion_proyecto= jQuery("#presupuesto_ley_db-consultas-busqueda_accion_proyecto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?busq_accion_proyecto="+busq_accion_proyecto+"&busq_partida="+busq_partida+"&unidades="+unidades+"&ano="+ano,page:1}).trigger("reloadGrid");			
						}
					//-----------------------------------------fin de buesqueda partida proyecto
					$("#presupuesto_ley_db-consultas-busqueda_boton_filtro").click(function(){
					        presupuesto_ley_dosearch();
							if(getObj('presupuesto_ley_db-consultas-busqueda_partida').value!="")presupuesto_ley_dosearch();
    					    if(getObj('presupuesto_ley_db-consultas-busqueda_accion').value!="")presupuesto_ley_accion_dosearch();
   					    	if((getObj('presupuesto_ley_db-consultas-busqueda_accion_proyecto').value!=""))presupuesto_ley_accion_proyecto_dosearch();
						 //   if(getObj('presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').value!="")presupuesto_ley_unidad_ejecutora_dosearch();
						  // 	if((getObj('presupuesto_ley_db-consultas-busqueda_partida').value!="")&&(getObj('presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').value!=""))presupuesto_ley_partida_unidad_dosearch();
						   	if((getObj('presupuesto_ley_db-consultas-busqueda_partida').value!="")&&(getObj('presupuesto_ley_db-consultas-busqueda_accion').value!=""))presupuesto_ley_partida_accion_dosearch();
                        	if((getObj('presupuesto_ley_db-consultas-busqueda_partida').value!="")&&(getObj('presupuesto_ley_db-consultas-busqueda_accion_proyecto').value!=""))presupuesto_ley_partida_accion_proyecto_dosearch();
						})
		/*		$("#presupuesto_ley_db-consultas-busqueda_partida").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						presupuesto_ley_dosearch();
												
						});
					function presupuesto_ley_dosearch()
					{
						if(!flAuto) return; 
							if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(presupuesto_ley_gridReload,500)
						}
					function presupuesto_ley_gridReload()
					{
							var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 			
						}*/
	
				}
		});

/*	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
					function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:1000,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?nd='+nd+"&unidades="+unidades+"&ano="+ano+"&cod_unidad="+ cod_unidad,
								datatype: "json",
								colNames:['id_presupuesto_ley', 'id_organismo','id_accion_central','Codigo_unidad_ejecutora','Unidad Ejecutora','Denominaci&oacute;n','Acci&oacute;n/Proyecto','Acci&oacute;n/Proyecto', 'codigo_especifica','Acci&oacute;n Espec&iacute;fica','Acci&oacute;n Espec&iacute;fica','Partida', 'id_proyecto','Año', 'comentario', 'id_accion_especifica','id_unidad_ejecutora','1','2','3','4','5','6','7','8','9','10','11','12','codigo_pro','codigo_central','suma'],
								colModel:[
									{name:'id_presupuesto_ley',index:'id_presupuesto_ley', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_organismo',index:'id_organismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_central',index:'id_accion_central', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:40,sortable:false,resizable:false,hidden:true},
									{name:'titulo',index:'titulo', width:40,sortable:false,resizable:false},
									{name:'accion_proyectoi',index:'accion_proyectoi', width:110,sortable:false,resizable:false},									
									{name:'accion_proyecto',index:'accion_proyecto', width:110,sortable:false,resizable:false,hidden:true},									
									{name:'codigo_especifica',index:'codigo_especifica', width:140,sortable:false,resizable:false,hidden:true},
									{name:'denominacioni',index:'denominacioni', width:140,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:140,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:40,sortable:false,resizable:false},									
									{name:'id_proyecto',index:'id_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'anio',index:'anio', width:20,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'enero',index:'enero', width:50,sortable:false,resizable:false,hidden:true},
									{name:'febrero',index:'febrero', width:50,sortable:false,resizable:false,hidden:true},
									{name:'marzo',index:'marzo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'abril',index:'abril', width:50,sortable:false,resizable:false,hidden:true},
									{name:'mayo',index:'mayo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'junio',index:'junio', width:50,sortable:false,resizable:false,hidden:true},
									{name:'julio',index:'julio', width:50,sortable:false,resizable:false,hidden:true},
									{name:'agosto',index:'agosto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'septiembre',index:'septiembre', width:50,sortable:false,resizable:false,hidden:true},
									{name:'octubre',index:'octubre', width:50,sortable:false,resizable:false,hidden:true},
									{name:'noviembre',index:'noviembre', width:50,sortable:false,resizable:false,hidden:true},
									{name:'diciembre',index:'diciembre', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proyecto',index:'codigo_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_central',index:'codigo_accion_central', width:30,sortable:false,resizable:false,hidden:true},
									{name:'suma',index:'suma', width:30,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aperturar_cuenta_pr_id').value = ret.id_presupuesto_ley;
									
									if (ret.codigo_accion_central == "0000"){
										getObj('aperturar_cuenta_pr_accion_central_id').value = 0;
										getObj('aperturar_cuenta_pr_accion_central_id_old').value = 0;
										getObj('aperturar_cuenta_pr_nombre_central').value = "   NO APLICA";
										getObj('aperturar_cuenta_pr_codigo_central').value = "0000";
									}else{
										getObj('aperturar_cuenta_pr_accion_central_id').value = ret.id_accion_central;
										getObj('aperturar_cuenta_pr_accion_central_id_old').value = ret.id_accion_central;
										getObj('aperturar_cuenta_pr_nombre_central').value = ret.accion_proyecto;
										getObj('aperturar_cuenta_pr_codigo_central').value = ret.codigo_accion_central;
									}
									//***********
									if (ret.codigo_proyecto == "0000"){
										getObj('aperturar_cuenta_pr_proyecto_id').value = 0
										getObj('aperturar_cuenta_pr_proyecto_id_old').value = 0
										getObj('aperturar_cuenta_pr_nombre_proyecto').value = "   NO APLICA";
										getObj('aperturar_cuenta_pr_codigo_proyecto').value = "0000";
									}else{
										getObj('aperturar_cuenta_pr_proyecto_id').value = ret.id_proyecto;
										getObj('aperturar_cuenta_pr_proyecto_id_old').value = ret.id_proyecto;
										getObj('aperturar_cuenta_pr_nombre_proyecto').value = ret.accion_proyecto;
										getObj('aperturar_cuenta_pr_codigo_proyecto').value = ret.codigo_proyecto;
									}
									getObj('aperturar_cuenta_pr_accion_especifica').value = ret.id_accion_especifica;
									getObj('aperturar_cuenta_pr_accion_especifica_old').value = ret.id_accion_especifica;
									getObj('aperturar_cuenta_pr_codigo_especifica').value = ret.codigo_especifica;
									getObj('aperturar_cuenta_pr_nombre_especifica').value = ret.denominacion;
									getObj('aperturar_cuenta_pr_unidad_ejecutora_id').value=ret.id_unidad_ejecutora;
									getObj('aperturar_cuenta_pr_unidad_ejecutora_id_old').value=ret.id_unidad_ejecutora;
									getObj('aperturar_cuenta_pr_codigo_unidad_ejecutora').value=ret.codigo_unidad_ejecutora;
									getObj('aperturar_cuenta_pr_unidad_ejecutora').value = ret.nombre;
									getObj('aperturar_cuenta_pr_anio').value = ret.anio;
									getObj('aperturar_cuenta_pr_partida_numero').value = ret.partida;
									getObj('aperturar_cuenta_pr_partida_numero_old').value = ret.partida;
									getObj('aperturar_cuenta_pr_partida').value = ret.denominacion;

									getObj('aperturar_cuenta_pr_monto_enero').value = ret.enero;

									getObj('aperturar_cuenta_pr_monto_febrero').value = ret.febrero;

									getObj('aperturar_cuenta_pr_monto_marzo').value = ret.marzo;

									getObj('aperturar_cuenta_pr_monto_abril').value = ret.abril;

									getObj('aperturar_cuenta_pr_monto_mayo').value = ret.mayo;

									getObj('aperturar_cuenta_pr_monto_junio').value = ret.junio;

									getObj('aperturar_cuenta_pr_monto_julio').value = ret.julio;

									getObj('aperturar_cuenta_pr_monto_agosto').value = ret.agosto;

									getObj('aperturar_cuenta_pr_monto_septiembre').value = ret.septiembre;

									getObj('aperturar_cuenta_pr_monto_octubre').value = ret.octubre;

									getObj('aperturar_cuenta_pr_monto_noviembre').value = ret.noviembre;

									getObj('aperturar_cuenta_pr_monto_diciembre').value = ret.diciembre;
									
									getObj('aperturar_cuenta_pr_monto_presupuesto').value = ret.suma;
									
									getObj('aperturar_cuenta_pr_comentario').value = ret.comentario;
									getObj('aperturar_cuenta_pr_btn_cancelar').style.display='';
									getObj('aperturar_cuenta_pr_btn_actualizar').style.display='';
									getObj('aperturar_cuenta_pr_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').alpha({allow:' '});
								$("#presupuesto_ley_db-consultas-busqueda_partida").focus();
								$('#presupuesto_ley_db-consultas-busqueda_partida').numeric({allow:'.'});
								
								$('#presupuesto_ley_db-consultas-busqueda_accion').alpha({allow:' '});
				
									},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'partida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}//fin del if de la consulta
else
{
alert("Debe elegir una unidad solicitante");
}	

});

////////////////////////////////
//------------------ partida ---------------------------
$("#aperturar_cuenta_pr_btn_consultar_partida").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
unidades=getObj('aperturar_cuenta_pr_unidad_ejecutora').value;
ano=getObj('aperturar_cuenta_pr_anio').value;
if(unidades!="")
{
var nd=new Date().getTime();

	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley_n.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				urls="modulos/presupuesto/presupuesto_ley/db/cmb.sql.partida.php?unidades="+unidades+"&ano="+ ano ;
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Presupuesto de ley  '+unidades+" "+'año '+ano, modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:urls,page:1}).trigger("reloadGrid"); 
				
				}	
				
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				
			
				//-------------------------------------------busqueda por partida--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
				$("#presupuesto_ley_db-consultas-busqueda_partida").keypress(function(key)
				{
						if (key.keycode==13)$("#presupuesto_ley_db-consultas-busqueda_boton_filtro")
						if(key.keyCode==27){this.close();}
				});
					function presupuesto_ley_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(presupuesto_ley_gridReload,500)
						}
					function presupuesto_ley_gridReload()
					{
							var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val();
							//alert("modulos/presupuesto/presupuesto_ley/db/cmb.sql.partida.php?busq_partida="+busq_partida+"&unidades="+unidades+"&ano="+ano);
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.partida.php?busq_partida="+busq_partida+"&unidades="+unidades+"&ano="+ano,page:1}).trigger("reloadGrid");			
					}
					
					$("#presupuesto_ley_db-consultas-busqueda_boton_filtro").click(function(){
					        presupuesto_ley_dosearch();
							if(getObj('presupuesto_ley_db-consultas-busqueda_partida').value!="")presupuesto_ley_dosearch();
						 //   if(getObj('presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').value!="")presupuesto_ley_unidad_ejecutora_dosearch();
						  // 	if((getObj('presupuesto_ley_db-consultas-busqueda_partida').value!="")&&(getObj('presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').value!=""))presupuesto_ley_partida_unidad_dosearch();
						})					
		/*		$("#presupuesto_ley_db-consultas-busqueda_partida").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						presupuesto_ley_dosearch();
												
						});
					function presupuesto_ley_dosearch()
					{
						if(!flAuto) return; 
							if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(presupuesto_ley_gridReload,500)
						}
					function presupuesto_ley_gridReload()
					{
							var busq_partida= jQuery("#presupuesto_ley_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_presupuesto_ley.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 			
						}*/
	
				}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:900,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.partida.php?nd='+nd,
								datatype: "json",
								colNames:['Partida', 'Descripci&oacute;n'],
								colModel:[
									{name:'partida',index:'partida', width:100,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:400,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aperturar_cuenta_pr_partida_numero').value = ret.partida;
									getObj('aperturar_cuenta_pr_partida').value = ret.denominacion;
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
								sortname: 'partida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
				}
});

///**********************----************************************
function consulta_automatica_accion_central()
{ 
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_accion_cental_codigo_apertura.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_accion_central_id').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_central').value=recordset[1];
				getObj('aperturar_cuenta_pr_proyecto_id').value="";
				getObj('aperturar_cuenta_pr_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('aperturar_cuenta_pr_codigo_proyecto').value ="0000" ;
				getObj('aperturar_cuenta_pr_codigo_proyecto').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('aperturar_cuenta_pr_accion_central_id').value ="";
			    getObj('aperturar_cuenta_pr_nombre_central').value="";
				getObj('aperturar_cuenta_pr_proyecto_id').value="";
				getObj('aperturar_cuenta_pr_nombre_proyecto').value="";
				getObj('aperturar_cuenta_pr_codigo_proyecto').value ="" ;
				getObj('aperturar_cuenta_pr_codigo_proyecto').disabled="" ;
				}
			 }
		});	 	 
}
// -----
$("#aperturar_cuenta_pr_btn_consultar_central").click(function() {
if(getObj('aperturar_cuenta_pr_proyecto_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	//**************
	$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/db/grid_accion_central_para.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
			
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Accion Centralizada', modal: true,center:false,x:0,y:0,show:false});
				//dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad', modal: true,center:false,x:0,y:0,show:false });								
				dialog_reload=function gridReload(){ //alert('aqui');
					var busq_nombre= jQuery("#ante_aperturar_cuenta_pr_accion_nom").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_central.php?busq_nombre="+busq_nombre+'&ano='+getObj('aperturar_cuenta_pr_anio').value,page:1}).trigger("reloadGrid"); 
				}
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#ante_aperturar_cuenta_pr_accion_nom").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				//
				//
				//
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
							var busq_nombre= jQuery("#ante_aperturar_cuenta_pr_accion_nom").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_central.php?busq_nombre="+busq_nombre+'&ano='+getObj('aperturar_cuenta_pr_anio').value,page:1}).trigger("reloadGrid");
							
						}
			}
		});
	/*$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_central.php?nd='+nd+'&ano='+getObj('aperturar_cuenta_pr_anio').value,
								datatype: "json",
								colNames:['id','Codigo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aperturar_cuenta_pr_accion_central_id').value = ret.id;
									getObj('aperturar_cuenta_pr_codigo_central').value = ret.codigo;
									getObj('aperturar_cuenta_pr_nombre_central').value = ret.denominacion;
									getObj('aperturar_cuenta_pr_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
									getObj('aperturar_cuenta_pr_codigo_proyecto').value ="0000" ;
									getObj('aperturar_cuenta_pr_codigo_proyecto').disabled="disabled" ;
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

///**********************----************************************
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_proyecto_codigo_apertura.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_proyecto_id').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_proyecto').value=recordset[1];
				getObj('aperturar_cuenta_pr_accion_central_id').value="";
				getObj('aperturar_cuenta_pr_nombre_central').value="  NO APLICA ESTA OPCION  ";
				getObj('aperturar_cuenta_pr_codigo_central').value ="0000" ;
				getObj('aperturar_cuenta_pr_codigo_central').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('aperturar_cuenta_pr_proyecto_id').value ="";
			    getObj('aperturar_cuenta_pr_nombre_proyecto').value="";
				getObj('aperturar_cuenta_pr_nombre_central').value="";
				getObj('aperturar_cuenta_pr_codigo_central').value ="" ;
				getObj('aperturar_cuenta_pr_accion_central_id').value ="";
				getObj('aperturar_cuenta_pr_codigo_central').disabled="" ;
				}
			 }
		});	 	 
}
// -----

$("#aperturar_cuenta_pr_btn_consultar_proyecto").click(function() {
if(getObj('aperturar_cuenta_pr_accion_central_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/db/grid_proyecto_para.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
			
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Accion Centralizada', modal: true,center:false,x:0,y:0,show:false});
				//dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad', modal: true,center:false,x:0,y:0,show:false });								
				dialog_reload=function gridReload(){ //alert('aqui');
					var busq_nombre= jQuery("#ante_aperturar_cuenta_pr_proyect_nom").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.proyecto.php?busq_nombre="+busq_nombre+'&ano='+getObj('aperturar_cuenta_pr_anio').value,page:1}).trigger("reloadGrid"); 
				}
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#ante_aperturar_cuenta_pr_proyect_nom").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				//
				//
				//
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
							var busq_nombre= jQuery("#ante_aperturar_cuenta_pr_proyect_nom").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.proyecto.php?busq_nombre="+busq_nombre+'&ano='+getObj('aperturar_cuenta_pr_anio').value,page:1}).trigger("reloadGrid");
							
						}
			}
		});
	/*$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.proyecto.php?nd='+nd+'&ano='+getObj('aperturar_cuenta_pr_anio').value,
								datatype: "json",
								colNames:['id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aperturar_cuenta_pr_proyecto_id').value = ret.id;
									getObj('aperturar_cuenta_pr_codigo_proyecto').value = ret.codigo;
									getObj('aperturar_cuenta_pr_nombre_proyecto').value = ret.denominacion;
									getObj('aperturar_cuenta_pr_nombre_central').value="  NO APLICA ESTA OPCION  ";
									getObj('aperturar_cuenta_pr_codigo_central').value ="0000" ;
									getObj('aperturar_cuenta_pr_codigo_central').disabled="disabled" ;

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
//***********************************************************************************************************************
function consulta_automatica_especifica()
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_accion_especifica_apertura.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_accion_especifica').value = recordset[0];
				getObj('aperturar_cuenta_pr_nombre_especifica').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('aperturar_cuenta_pr_accion_especifica').value ="";
			    getObj('aperturar_cuenta_pr_nombre_especifica').value="";
				}
			 }
		});	 	 
}
// -----
$("#aperturar_cuenta_pr_btn_consultar_especifica").click(function() {
if(getObj('aperturar_cuenta_pr_accion_central_id').value !="" || getObj('aperturar_cuenta_pr_proyecto_id').value !="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
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
								url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.accion_especifica.php?nd='+nd+"&proyecto="+getObj('aperturar_cuenta_pr_proyecto_id').value+"&accion_central="+getObj('aperturar_cuenta_pr_accion_central_id').value,
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
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aperturar_cuenta_pr_accion_especifica').value = ret.id;
									getObj('aperturar_cuenta_pr_codigo_especifica').value = ret.codigo;
									getObj('aperturar_cuenta_pr_nombre_especifica').value = ret.denominacion;
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
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$("#aperturar_cuenta_pr_btn_consultar_unidad_ejecutora").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	//**************
	$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/db/grid_unidad_ejecutora_para.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
			
				dialog=new Boxy(data,{ title: 'Consulta Emergente de unidad', modal: true,center:false,x:0,y:0,show:false});
				//dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad', modal: true,center:false,x:0,y:0,show:false });								
				dialog_reload=function gridReload(){ //alert('aqui');
					var busq_nombre= jQuery("#ante_aperturar_cuenta_pr_unidad").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.unidad.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
				}
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#ante_aperturar_cuenta_pr_unidad").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				//
				//
				//
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
							var busq_nombre= jQuery("#ante_aperturar_cuenta_pr_unidad").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/db/cmb.sql.unidad.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
			}
		});
	////****************************************************************************************************************
	//******************************************************************************************************************
/*	$.post("modulos/presupuesto/presupuesto_ley/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
				function crear_grid()
				{
					jQuery("#list_grid_"+nd).jqGrid
					({
						width:400,
						height:350,
						recordtext:"Registro(s)",
						loadtext: "Recuperando Información del Servidor",		
						url:'modulos/presupuesto/presupuesto_ley/db/cmb.sql.unidad.php?nd='+nd,
						datatype: "json",
						colNames:['id','Codigo', 'Unidad'],
						colModel:[
							{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
							{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
							{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false}
						],
						pager: $('#pager_grid_'+nd),
						rowNum:20,
						rowList:[20,50,100],
						imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
						onSelectRow: function(id){
						var ret = jQuery("#list_grid_"+nd).getRowData(id);
							getObj('aperturar_cuenta_pr_unidad_ejecutora_id').value = ret.id;
							getObj('aperturar_cuenta_pr_codigo_unidad_ejecutora').value = ret.codigo;
							getObj('aperturar_cuenta_pr_unidad_ejecutora').value = ret.nombre;
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
						sortname: 'codigo_unidad',
						viewrecords: true,
						sortorder: "asc"
					});
				}

});
//***********************************************************************************************************************
//***********************************************************************************************************************
function consulta_automatica_unidad_ejecutora()
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/db/sql_grid_unidad_ejecutora_apertura.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_unidad_ejecutora_id').value = recordset[0];
				getObj('aperturar_cuenta_pr_unidad_ejecutora').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('aperturar_cuenta_pr_unidad_ejecutora_id').value = "";
				getObj('aperturar_cuenta_pr_unidad_ejecutora').value="";		
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
//***********************************************************************************************************************
function consulta_automatica_partida_numero() 
{
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/db/sql.partida_apertura.php",
            data:dataForm('form_pr_aperturar'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('aperturar_cuenta_pr_partida_numero').value = recordset[0];
				getObj('aperturar_cuenta_pr_partida').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('aperturar_cuenta_pr_partida_numero').value = "";
				getObj('aperturar_cuenta_pr_partida').value="";		
				}
			 }
		});	 	 
}
// -----
//***********************************************************************************************************************
// -----------------------------------------------------------------------------------------------------------------------------------


$('#aperturar_cuenta_pr_codigo_especifica').numeric({allow:''});
$('#aperturar_cuenta_pr_codigo_especifica').change(consulta_automatica_especifica);
$('#aperturar_cuenta_pr_codigo_central').numeric({allow:''});
$('#aperturar_cuenta_pr_codigo_central').change(consulta_automatica_accion_central);
$('#aperturar_cuenta_pr_codigo_proyecto').numeric({allow:''});
$('#aperturar_cuenta_pr_codigo_proyecto').change(consulta_automatica_proyecto);
$('#aperturar_cuenta_pr_codigo_unidad_ejecutora').change(consulta_automatica_unidad_ejecutora);
$('#aperturar_cuenta_pr_partida_numero').change(consulta_automatica_partida_numero);


/*$("#aperturar_cuenta_pr_proyecto").change(update_unidad_especifica_proyecto);
$("#aperturar_cuenta_pr_accion_central").change(update_unidad_especifica_central);*/

function deshabilita_proyecto()
{
				

	if (getObj('aperturar_cuenta_pr_accion_central').value != 0)
	{
		document.form_pr_aperturar.aperturar_cuenta_pr_proyecto.disabled=true;
	}
	if (getObj('aperturar_cuenta_pr_accion_central').value == 0)
	{
		document.form_pr_aperturar.aperturar_cuenta_pr_proyecto.disabled=false;
	}
}
function deshabilita_accion_centralizada()
{
	if (document.form_pr_aperturar.aperturar_cuenta_pr_proyecto.value != 0)
	{
		document.form_pr_aperturar.aperturar_cuenta_pr_accion_central.disabled=true;
	}
	if (document.form_pr_aperturar.aperturar_cuenta_pr_proyecto.value == 0)
	{
		document.form_pr_aperturar.aperturar_cuenta_pr_accion_central.disabled=false;
	}
}
$("#aperturar_cuenta_pr_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('aperturar_cuenta_pr_btn_cancelar').style.display='';
	//getObj('aperturar_cuenta_pr_btn_actualizar').style.display='none';
	getObj('aperturar_cuenta_pr_btn_guardar').style.display='';
	clearForm('form_pr_aperturar');
					getObj('aperturar_cuenta_pr_accion_central').value =		0;
					getObj('aperturar_cuenta_pr_proyecto').value =			0;
					getObj('aperturar_cuenta_pr_codigo_central').disabled='';
					getObj('aperturar_cuenta_pr_codigo_proyecto').disabled='';
					getObj('aperturar_cuenta_pr_accion_especifica').value =	0;
					getObj('aperturar_cuenta_pr_unidad_ejecutora').value =	0;
					

});
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
/*-------------------   Inicio Validaciones  ---------------------------*/
$('#aperturar_cuenta_pr_anio').numeric({allow:''});
$('#aperturar_cuenta_pr_monto_enero').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_febrero').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_marzo').numeric({allow:', . '});
$('#aperturar_cuenta_pr_monto_abril').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_mayo').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_junio').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_julio').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_agosto').numeric({allow:',.'});
$('#aperturar_cuenta_pr_monto_septiembre').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_octubre').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_noviembre').numeric({allow:', .'});
$('#aperturar_cuenta_pr_monto_diciembre').numeric({allow:', .'});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
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
function suma()
{ 
	
	
	valor=getObj('aperturar_cuenta_pr_monto_enero').value.float() + getObj('aperturar_cuenta_pr_monto_febrero').value.float() + getObj('aperturar_cuenta_pr_monto_marzo').value.float() + getObj('aperturar_cuenta_pr_monto_abril').value.float() + getObj('aperturar_cuenta_pr_monto_mayo').value.float() + getObj('aperturar_cuenta_pr_monto_junio').value.float() + getObj('aperturar_cuenta_pr_monto_julio').value.float() + getObj('aperturar_cuenta_pr_monto_agosto').value.float() + getObj('aperturar_cuenta_pr_monto_septiembre').value.float() + getObj('aperturar_cuenta_pr_monto_octubre').value.float() + getObj('aperturar_cuenta_pr_monto_noviembre').value.float() + getObj('aperturar_cuenta_pr_monto_diciembre').value.float();
	
	//valor='999999.58';
	valor = valor.currency(2,',','.');	
		
	getObj('aperturar_cuenta_pr_monto_presupuesto').value = valor;

}
/*-------------------   Fin SUMA  ---------------------------*/

</script>

<div id="botonera">
	<img id="aperturar_cuenta_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="aperturar_cuenta_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"  />
    <img id="aperturar_cuenta_pr_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif" style="display:none" />-->
	<img id="aperturar_cuenta_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_pr_aperturar" id="form_pr_aperturar">
  <input name="aperturar_cuenta_pr_id" id="aperturar_cuenta_pr_id" type="hidden" />
  <input name="aperturar_cuenta_pr_accion_central_id_old" id="aperturar_cuenta_pr_accion_central_id_old" type="hidden" />
  <input name="aperturar_cuenta_pr_proyecto_id_old" id="aperturar_cuenta_pr_proyecto_id_old" type="hidden" />
  <input name="aperturar_cuenta_pr_accion_especifica_old" id="aperturar_cuenta_pr_accion_especifica_old" type="hidden" />
  <input name="aperturar_cuenta_pr_unidad_ejecutora_id_old" id="aperturar_cuenta_pr_unidad_ejecutora_id_old" type="hidden" />
  <input name="aperturar_cuenta_pr_partida_numero_old" id="aperturar_cuenta_pr_partida_numero_old" type="hidden" />
  
  <input type="hidden" name="campo" id="campo" />
	<table class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />  Aperturar	Cuenta		</th>
	  </tr>
		<tr>
			<th>A&ntilde;o:</th>
			<td>
				<select  name="aperturar_cuenta_pr_anio" id="aperturar_cuenta_pr_anio">
					<?
					$anio_inicio=2011;
					$anio_fin=2011;
					while($anio_inicio <= $anio_fin)
					{
						if($anio_inicio == $anio_fin)
							$selected = " selected";
						else
							$selected = " ";
					?>
					<option value="<?=$anio_inicio;?>" <?= $selected;?>><?=$anio_inicio;?></option>
                   	
					<?
						$anio_inicio++;
					}
					?>
				</select>
		</tr>
		<tr>
			<th>Unidad Solicitante : </th>
			<td>
				<ul class="input_con_emergente">
					<li>
					<input name="aperturar_cuenta_pr_codigo_unidad_ejecutora" type="text" id="aperturar_cuenta_pr_codigo_unidad_ejecutora"  maxlength="6"
								onchange="consulta_automatica_unidad_ejecutora" onclick="consulta_automatica_unidad_ejecutora"
								message="Introduzca un Codigo para la unidad ejecutora."  size="6"
								jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					<input name="aperturar_cuenta_pr_unidad_ejecutora" type="text" id="aperturar_cuenta_pr_unidad_ejecutora"  style="width:60ex" maxlength="100"
								message="Introduzca la unidad ejecutora." readonly
					>
					
					</li>
					<li id="aperturar_cuenta_pr_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
				<input name="aperturar_cuenta_pr_unidad_ejecutora_id" type="hidden" id="aperturar_cuenta_pr_unidad_ejecutora_id" />
			</td>
		</tr>
		<tr>
		<tr>
			<th>Acci&oacute;n Central:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="aperturar_cuenta_pr_codigo_central" type="text" id="aperturar_cuenta_pr_codigo_central"  maxlength="6"
						onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
						message="Introduzca un Codigo para el Accion Central."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					
						<input name="aperturar_cuenta_pr_nombre_central" type="text" id="aperturar_cuenta_pr_nombre_central"  style="width:60ex" maxlength="100"
						message="Introduzca una Denominacion para la Accion Central." 
						>
					</li>
					<li id="aperturar_cuenta_pr_btn_consultar_central" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="aperturar_cuenta_pr_accion_central_id" type="hidden" id="aperturar_cuenta_pr_accion_central_id" />
				
			</td>
		</tr>
		<tr>
			<th>Proyecto:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="aperturar_cuenta_pr_codigo_proyecto" type="text" id="aperturar_cuenta_pr_codigo_proyecto"  maxlength="6"
						onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
						message="Introduzca un Codigo para el Proyecto."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					
						<input name="aperturar_cuenta_pr_nombre_proyecto" type="text" id="aperturar_cuenta_pr_nombre_proyecto"  style="width:60ex" maxlength="100"
						message="Introduzca un Nombre para el Proyecto." readonly 
						>
					</li>
					<li id="aperturar_cuenta_pr_btn_consultar_proyecto" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="aperturar_cuenta_pr_proyecto_id" type="hidden" id="aperturar_cuenta_pr_proyecto_id" />
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Espec&iacute;fica</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="aperturar_cuenta_pr_codigo_especifica" type="text" id="aperturar_cuenta_pr_codigo_especifica"  maxlength="9"
						onchange="consulta_automatica_especifica" onclick="consulta_automatica_especifica"
						message="Introduzca una Codigo para la Accion Especifica."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}">
					
						<input name="aperturar_cuenta_pr_nombre_especifica" type="text" id="aperturar_cuenta_pr_nombre_especifica"  style="width:60ex" maxlength="100"
						message="Introduzca una Denominacion para la Accion Especifica." readonly 
						>
					</li>
					<li id="aperturar_cuenta_pr_btn_consultar_especifica" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="aperturar_cuenta_pr_accion_especifica" type="hidden" id="aperturar_cuenta_pr_accion_especifica" />
<!--				<select name="aperturar_cuenta_pr_accion_especifica" id="aperturar_cuenta_pr_accion_especifica" style="width:400px; min-width:400px;">
					<option value="0"></option>
					<?//=$opt_accione;?>
				</select>-->
				
			</td>
		</tr>
		<tr>
			<th>Partida :	 </th>
			<td>
			<ul class="input_con_emergente">
				<li>
					<input name="aperturar_cuenta_pr_partida_numero" type="text" id="aperturar_cuenta_pr_partida_numero" size="9" maxlength="12"/
                    onchange="consulta_automatica_partida_numero" onclick="consulta_automatica_partida_numero">
					<input name="aperturar_cuenta_pr_partida" type="text" id="aperturar_cuenta_pr_partida" style="width:57ex" maxlength="100" readonly 
					message="Introduzca la Partidad Presupuestaria" jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0,.-_123456789/-]{1,100}$/, message:'Partidad no Invalida', styleType:'cover'}"
					/>
				</li>
				<li id="aperturar_cuenta_pr_btn_consultar_partida" class="btn_consulta_emergente"></li>
			</ul>
			
			</td>
		</tr>

		<tr>
			<th>Comentario:</th>
			<td>
				<textarea name="aperturar_cuenta_pr_comentario" id="aperturar_cuenta_pr_comentario" cols="62" rows="3" message="Introduzca un Comentario para el Presupuesto."></textarea>
			</td>
		</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>			
	</table>
</form>