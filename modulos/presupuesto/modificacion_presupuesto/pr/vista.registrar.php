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
var dialog, monto_mod;
$("#modificacion_presupuesto_db_btn_guardar").click(function() {
	if($('#form_db_modificacion_presupuesto').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/modificacion_presupuesto/pr/sql.modificacion_presupuesto.php",
			data:dataForm('form_db_modificacion_presupuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_db_modificacion_presupuesto'); 
					getObj('modificacion_presupuesto_db_accion_especifica_id').value = "";
					getObj('modificacion_presupuesto_db_codigo_especifica').value = "";
					getObj('modificacion_presupuesto_db_accion_especifica').value = "";
					
					getObj('modificacion_presupuesto_db_partida_numero').value = "";
					getObj('modificacion_presupuesto_db_partida').value = "";
					getObj('modificacion_presupuesto_db_mes_cendente').value = 0;
					getObj('modificacion_presupuesto_db_monto').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_cedente').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_total_disponible').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_aprobado').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value = '0,00';
					
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#modificacion_presupuesto_db_btn_actualizar").click(function() {
	if($('#form_db_modificacion_presupuesto').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/modificacion_presupuesto/pr/sql.actualizar.php",
			data:dataForm('form_db_modificacion_presupuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_modificacion_presupuesto');
					getObj('modificacion_presupuesto_db_unidad_ejecutora').value = 0;
					getObj('modificacion_presupuesto_db_mes_cendente').value = 0;
					getObj('modificacion_presupuesto_db_monto').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_cedente').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_total_disponible').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_aprobado').value = '0,00';
					getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value = '0,00';
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});

$("#modificacion_presupuesto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		    url:"modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Modificación de Presupuesto de Ley', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_partida= jQuery("#modificacion_presupuesto_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/modificacion_presupuesto/pr/sql_grid_presupuesto_ley.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 
				}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//-busqueda por partida
				$("#modificacion_presupuesto_db-consultas-busqueda_partida").keypress(function(key)
				{
						if (key.keycode==13)$("#modificacion_presupuesto_db-consultas-busqueda_boton_filtro")
						//getObj('presupuesto_ley_db-consultas-busqueda_accion').value=""
						//getObj('presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').value=""
						if(key.keyCode==27){this.close();}
				});
					function modificacion_presupuesto_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(modificacion_presupuesto_gridReload,500)
						}
					function modificacion_presupuesto_gridReload()
					{
							var busq_partida= jQuery("#modificacion_presupuesto_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/modificacion_presupuesto/pr/sql_grid_presupuesto_ley.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 
					}
					$("#modificacion_presupuesto_db-consultas-busqueda_boton_filtro").click(function(){
					      modificacion_presupuesto_dosearch();
							if(getObj('modificacion_presupuesto_db-consultas-busqueda_partida').value!="")modificacion_presupuesto_dosearch();
    					 //  if(getObj('presupuesto_ley_db-consultas-busqueda_accion').value!="")presupuesto_ley_accion_dosearch();
						   //if(getObj('presupuesto_ley_db-consultas-busqueda_unidad_ejecutora').value!="")presupuesto_ley_unidad_ejecutora_dosearch();
						    						
						})
			}
		});
				//fin busqeda partida
	/*$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100); 
								.substr(10,5
                        });
				*/		function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:1000,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/modificacion_presupuesto/pr/sql_grid_presupuesto_ley.php?nd='+nd+'&unidad='+getObj('modificacion_presupuesto_db_unidad_ejecutora').value,
								datatype: "json",
								colNames:['id','id_proyecto','id_accion_centralizada', 'id_accion_especifica', 'monto', 'comentario','mes_modificado', 'ano', 'id_unidad_ejecutora','codigo_unidad','Unidad Ejecutora','Codigo Central','Accion Central','Accion Central', 'Codigo Proyecto', 'Proyecto','Proyecto','Accion Especifica', 'Partida','monto_mes','monto_to','clasifica','1','2','3','codigo_especifica'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_proyecto',index:'id_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_centralizada',index:'id_accion_centralizada', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_accion_especifica',index:'id_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'monto',index:'monto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'mes_modificado',index:'mes_modificado', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:30,sortable:false,resizable:false,hidden:true},
									{name:'codigo_unidad',index:'codigo_unidad', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:50,sortable:false,resizable:false},
									{name:'codigo_central',index:'codigo_central', width:70,sortable:false,resizable:false,hidden:true},
									{name:'accion_centrali',index:'accion_centrali', width:70,sortable:false,resizable:false},
									{name:'accion_central',index:'accion_central', width:70,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proyecto',index:'codigo_proyecto', width:70,sortable:false,resizable:false,hidden:true},
									{name:'proyectos',index:'proyectos', width:70,sortable:false,resizable:false},
									{name:'proyecto',index:'proyecto', width:70,sortable:false,resizable:false,hidden:true},
									{name:'denominacion',index:'denominacion', width:70,sortable:false,resizable:false},
									{name:'partida',index:'partida', width:20,sortable:false,resizable:false},
									{name:'monto_mes',index:'monto_mes', width:70,sortable:false,hidden:true},
									{name:'monto_to',index:'monto_to', width:70,sortable:false,hidden:true},
									{name:'clasifica',index:'clasifica', width:70,sortable:false,hidden:true},
									{name:'1',index:'monto_mes', width:70,sortable:false,hidden:true},
									{name:'2',index:'monto_to', width:70,sortable:false,hidden:true},
									{name:'3',index:'clasifica', width:70,sortable:false,hidden:true},
									{name:'codigo_especifica',index:'codigo_especifica', width:70,sortable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('modificacion_presupuesto_db_id').value = ret.id;
									getObj('modificacion_presupuesto_db_proyecto_id').value = ret.id_proyecto;
									getObj('modificacion_presupuesto_db_accion_central_id').value = ret.id_accion_centralizada;
									getObj('modificacion_presupuesto_db_accion_especifica_id').value = ret.id_accion_especifica;
									getObj('modificacion_presupuesto_db_partida_numero').value = ret.partida;
									getObj('modificacion_presupuesto_db_monto_cedente').value = ret.monto;
									getObj('modificacion_presupuesto_db_comentario').value = ret.comentario;
									getObj('modificacion_presupuesto_db_mes_cendente').value = ret.mes_modificado;
									getObj('modificacion_presupuesto_db_unidad_ejecutora').value = ret.id_unidad_ejecutora;
									getObj('modificacion_presupuesto_pr_codigo_unidad').value = ret.codigo_unidad;
									getObj('modificacion_presupuesto_db_nombre_unidad').value = ret.nombre;  
									getObj('modificacion_presupuesto_db_codigo_especifica').value = ret.codigo_especifica;
									//getObj('modificacion_presupuesto_pr_codigo_central').value = ret.codigo_central; length 
									//getObj('modificacion_presupuesto_db_accion_central').value = ret.accion_central;
									if (ret.codigo_central != ""){
										getObj('modificacion_presupuesto_pr_codigo_central').value = ret.codigo_central; 
										getObj('modificacion_presupuesto_db_accion_central').value = ret.accion_central; 
									}else{
										getObj('modificacion_presupuesto_pr_codigo_central').value = "0000";
										getObj('modificacion_presupuesto_db_accion_central').value = " NO APLICA ESTA OPCION"; 
										getObj('modificacion_presupuesto_db_accion_central_id').value = 0; 
									}
									if (ret.codigo_proyecto != ""){
										getObj('modificacion_presupuesto_db_codigo_proyecto').value = ret.codigo_proyecto; 
										getObj('modificacion_presupuesto_db_proyecto').value = ret.proyecto; 
									}else{
										getObj('modificacion_presupuesto_db_codigo_proyecto').value = "0000";
										getObj('modificacion_presupuesto_db_proyecto').value = " NO APLICA ESTA OPCION"; 
										getObj('modificacion_presupuesto_db_proyecto_id').value = 0; 
									}
									//getObj('modificacion_presupuesto_db_proyecto').value = ret.proyecto; 									
									//getObj('modificacion_presupuesto_db_accion_especifica').value = ret.denominacion;
									getObj('modificacion_presupuesto_db_accion_especifica').value = ret.denominacion;
									getObj('modificacion_presupuesto_db_monto').value = ret.monto_mes;
									getObj('modificacion_presupuesto_db_monto_total_disponible').value = ret.monto_to;
									getObj('modificacion_presupuesto_db_partida').value = ret.clasifica;
									
									getObj('modificacion_presupuesto_db_btn_actualizar').style.display='';
									getObj('modificacion_presupuesto_db_btn_guardar').style.display='none';
									
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
									$('#modificacion_presupuesto_db-consultas-busqueda_partida').numeric();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'id_modificacion_ley',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//----------------------------------------------------------------------------------------------------------------------------

$("#modificacion_presupuesto_db_btn_consultar_unidad_ejecutora").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
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
								url:'modulos/presupuesto/modificacion_presupuesto/pr/cmb.sql.unidad_ejecutora.php?nd='+nd+'&ano='+getObj('modificacion_presupuesto_pr_ano').value,
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
									getObj('modificacion_presupuesto_db_unidad_ejecutora').value = ret.id;
									getObj('modificacion_presupuesto_pr_codigo_unidad').value = ret.codigo;
									getObj('modificacion_presupuesto_db_nombre_unidad').value = ret.nombre;
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
function consulta_automatica_unidad()
{ 
	$.ajax({
			url:"modulos/presupuesto/modificacion_presupuesto/pr/sql_grid_unidad.php",
            data:dataForm('form_db_modificacion_presupuesto'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('modificacion_presupuesto_db_unidad_ejecutora').value = recordset[0];
				getObj('modificacion_presupuesto_db_nombre_unidad').value=recordset[1];
				}
				else
			 {  
			   	getObj('modificacion_presupuesto_db_unidad_ejecutora').value ="";
			    getObj('modificacion_presupuesto_db_nombre_unidad').value="";
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------
$("#modificacion_presupuesto_db_btn_consultar_accion_central").click(function() {
if(getObj('modificacion_presupuesto_db_unidad_ejecutora').value  !=0 && getObj('modificacion_presupuesto_db_proyecto_id').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
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
								url:'modulos/presupuesto/modificacion_presupuesto/pr/cmb.sql.accion_central.php?nd='+nd+'&unidad='+getObj('modificacion_presupuesto_db_unidad_ejecutora').value,
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
									getObj('modificacion_presupuesto_db_accion_central_id').value = ret.id;
									getObj('modificacion_presupuesto_pr_codigo_central').value = ret.codigo;
									getObj('modificacion_presupuesto_db_accion_central').value = ret.denominacion;
									getObj('modificacion_presupuesto_db_codigo_proyecto').value = "0000";									
									getObj('modificacion_presupuesto_db_proyecto').value = "  NO APLICA ESTA OPCION  ";
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
function consulta_automatica_accion_central()
{ 
	$.ajax({
			url:"modulos/presupuesto/modificacion_presupuesto/pr/sql_grid_accion_cental_codigo.php",
            data:dataForm('form_db_modificacion_presupuesto'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('modificacion_presupuesto_db_accion_central_id').value = recordset[0];
				getObj('modificacion_presupuesto_db_accion_central').value=recordset[1];
				getObj('modificacion_presupuesto_db_proyecto_id').value="";
				getObj('modificacion_presupuesto_db_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('modificacion_presupuesto_db_codigo_proyecto').value ="0000" ;
				getObj('modificacion_presupuesto_db_codigo_proyecto').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('modificacion_presupuesto_db_accion_central_id').value ="";
			    getObj('modificacion_presupuesto_db_accion_central').value="";
				getObj('modificacion_presupuesto_db_proyecto_id').value="";
				getObj('modificacion_presupuesto_db_proyecto').value="";
				getObj('modificacion_presupuesto_db_codigo_proyecto').value ="" ;
				getObj('modificacion_presupuesto_db_codigo_proyecto').disabled="" ;
				}
			 }
		});	 	 
}

// -----------------------------------------------------------------------------------------------------------------------------------
$("#modificacion_presupuesto_db_btn_consultar_proyecto").click(function() {
if(document.form_db_modificacion_presupuesto.modificacion_presupuesto_db_unidad_ejecutora.selectedIndex  !=0  && document.form_db_modificacion_presupuesto.modificacion_presupuesto_db_accion_central_id.value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
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
								url:'modulos/presupuesto/modificacion_presupuesto/pr/cmb.sql.proyecto.php?nd='+nd+'&unidad='+getObj('modificacion_presupuesto_db_unidad_ejecutora').value,
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
									getObj('modificacion_presupuesto_db_proyecto_id').value = ret.id;
									getObj('modificacion_presupuesto_db_codigo_proyecto').value = ret.codigo;									
									getObj('modificacion_presupuesto_db_proyecto').value = ret.denominacion;
									getObj('modificacion_presupuesto_pr_codigo_central').value = "0000";
									getObj('modificacion_presupuesto_db_accion_central').value = "  NO APLICA ESTA OPCION  ";
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
			url:"modulos/presupuesto/modificacion_presupuesto/pr/sql_grid_proyecto_codigo.php",
            data:dataForm('form_db_modificacion_presupuesto'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('modificacion_presupuesto_db_proyecto_id').value = recordset[0];
				getObj('modificacion_presupuesto_db_proyecto').value=recordset[1];
				getObj('modificacion_presupuesto_db_accion_central_id').value="";
				getObj('modificacion_presupuesto_db_accion_central').value="  NO APLICA ESTA OPCION  ";
				getObj('modificacion_presupuesto_pr_codigo_central').value ="0000" ;
				getObj('modificacion_presupuesto_pr_codigo_central').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('modificacion_presupuesto_db_proyecto_id').value ="";
			    getObj('modificacion_presupuesto_db_proyecto').value="";
				getObj('modificacion_presupuesto_db_accion_central').value="";
				getObj('modificacion_presupuesto_pr_codigo_central').value ="" ;
				getObj('modificacion_presupuesto_db_accion_central_id').value ="";
				getObj('modificacion_presupuesto_pr_codigo_central').disabled="" ;
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------

$("#modificacion_presupuesto_db_btn_consultar_accion_especifica").click(function() {

if(getObj('modificacion_presupuesto_db_proyecto_id').value !="" || getObj('modificacion_presupuesto_db_accion_central_id').value !="")
{
	if (getObj('modificacion_presupuesto_db_accion_central_id').value !=""){
		urls='modulos/presupuesto/modificacion_presupuesto/pr/cmb.sql.accion_especifica_central.php?nd='+nd+'&unidad='+getObj('modificacion_presupuesto_db_unidad_ejecutora').value+'&accion='+getObj('modificacion_presupuesto_db_accion_central_id').value;
	}
	if (getObj('modificacion_presupuesto_db_proyecto_id').value !=""){
		urls='modulos/presupuesto/modificacion_presupuesto/pr/cmb.sql.accion_especifica_proyecto.php?nd='+nd+'&unidad='+getObj('modificacion_presupuesto_db_unidad_ejecutora').value+'&proyecto='+getObj('modificacion_presupuesto_db_proyecto_id').value;
	}
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
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
								colNames:['id','Codigo', 'Unidad Especifica'],
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
									getObj('modificacion_presupuesto_db_accion_especifica_id').value = ret.id;
									getObj('modificacion_presupuesto_db_codigo_especifica').value = ret.codigo;
									getObj('modificacion_presupuesto_db_accion_especifica').value = ret.denominacion;
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
function consulta_automatica_especifica()
{
	$.ajax({
			url:"modulos/presupuesto/modificacion_presupuesto/pr/sql_grid_accion_especifica.php",
            data:dataForm('form_db_modificacion_presupuesto'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('modificacion_presupuesto_db_accion_especifica_id').value = recordset[0];
				getObj('modificacion_presupuesto_db_accion_especifica').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('modificacion_presupuesto_db_accion_especifica_id').value ="";
			    getObj('modificacion_presupuesto_db_accion_especifica').value="";
				}
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------
$("#modificacion_presupuesto_db_btn_consultar_partida").click(function() {
if(getObj('modificacion_presupuesto_db_proyecto_id').value !="" || getObj('modificacion_presupuesto_db_accion_central_id').value !="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
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
								url:'modulos/presupuesto/modificacion_presupuesto/pr/cmb.sql.partida.php?nd='+nd+'&unidad='+getObj('modificacion_presupuesto_db_unidad_ejecutora').value+'&unidad_es='+getObj('modificacion_presupuesto_db_accion_especifica_id').value,
								datatype: "json",
								colNames:['Partida', 'Descripci&oacute;n','Diponible'],
								colModel:[
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('modificacion_presupuesto_db_partida_numero').value = ret.partida;
									getObj('modificacion_presupuesto_db_partida').value = ret.denominacion;
									//getObj('modificacion_presupuesto_db_partida').value = ret.monto;
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
/* ------------------ Código jquery_moneda   ---------------------------*/
documentall = document.all;



function update_monto_mes()
{
	$.ajax ({
			url: "modulos/presupuesto/modificacion_presupuesto/pr/cmb.sql.monto_mensual.php",
			data:dataForm('form_db_modificacion_presupuesto'),
			type:'POST',
			cache: false,
			
			success: function(html)
			{
					var monto = html;
					//setBarraEstado(html);
					//getObj('modificacion_presupuesto_db_monto').value = monto;
					
				recordset = monto.split("*");
				getObj('modificacion_presupuesto_db_monto').value = recordset[0];
				getObj('modificacion_presupuesto_db_monto_aprobado').value=recordset[1];
				getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value= recordset[2];
				monto_mod = getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value.float();
			}
		});
}
function montoreceptor()
{
	var monto_total;
	//alert(getObj('modificacion_presupuesto_db_operacion').checked);
	//if (getObj('modificacion_presupuesto_db_operacion').checked ==true)	{
		//monto_mod = getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value.float();
		//alert(monto_mod);
		if (getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value.float() == '0,00')
			monto_disponible = getObj('modificacion_presupuesto_db_monto_cedente').value.float() + getObj('modificacion_presupuesto_db_monto_aprobado').value.float();
		else
			monto_disponible = getObj('modificacion_presupuesto_db_monto_cedente').value.float() + monto_mod;
		
			monto_total = getObj('modificacion_presupuesto_db_monto_cedente').value.float() + getObj('modificacion_presupuesto_db_monto').value.float();
	//}
	///******************************
	//if (getObj('modificacion_presupuesto_db_operacion').checked ==false)	{
		if (monto_total < 0 )
		{
		
			getObj('modificacion_presupuesto_db_monto_cedente').value="0,00";
			getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value="0,00";
			monto_total = getObj('modificacion_presupuesto_db_monto').value.float();
			
			getObj('modificacion_presupuesto_db_monto_total_disponible').value="0,00";
			getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value="0,00";
			setBarraEstado(mensaje[monto_cedente_superior],true,true);
		}else{
			monto_total = monto_total.currency(2,',','.');	
			monto_disponible = monto_disponible.currency(2,',','.');	
			getObj('modificacion_presupuesto_db_monto_total_disponible').value =  monto_total;
			getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value =monto_disponible;

	}

	//**************************************************************
	
}

$("#modificacion_presupuesto_db_mes_cendente").change(update_monto_mes);

$("#modificacion_presupuesto_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('modificacion_presupuesto_db_btn_cancelar').style.display='';
	getObj('modificacion_presupuesto_db_btn_guardar').style.display='';
	getObj('modificacion_presupuesto_db_btn_actualizar').style.display='none';
	clearForm('form_db_modificacion_presupuesto');
	getObj('modificacion_presupuesto_db_unidad_ejecutora').value = 0;
	getObj('modificacion_presupuesto_db_mes_cendente').value = 0;
	getObj('modificacion_presupuesto_db_monto').value = '0,00';
	getObj('modificacion_presupuesto_db_monto_cedente').value = '0,00';
	getObj('modificacion_presupuesto_db_monto_total_disponible').value = '0,00';
	getObj('modificacion_presupuesto_db_monto_aprobado').value = '0,00';
	getObj('modificacion_presupuesto_db_monto_aprobado_modificado').value = '0,00';
	
});
/*-------------------   Inicio Validaciones  ---------------------------*/
$('#modificacion_presupuesto_db_monto_cedente').numeric({allow:'-'});

$('#modificacion_presupuesto_db_codigo_especifica').numeric({allow:''});
$('#modificacion_presupuesto_db_codigo_especifica').change(consulta_automatica_especifica);
$('#modificacion_presupuesto_pr_codigo_central').numeric({allow:''});
$('#modificacion_presupuesto_pr_codigo_central').change(consulta_automatica_accion_central);
$('#modificacion_presupuesto_pr_codigo_unidad').numeric({allow:''});
$('#modificacion_presupuesto_pr_codigo_unidad').change(consulta_automatica_unidad);
$('#modificacion_presupuesto_db_codigo_proyecto').numeric({allow:''});
$('#modificacion_presupuesto_db_codigo_proyecto').change(consulta_automatica_proyecto);



$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
/*-------------------   Fin Validaciones  ---------------------------*/

</script>

<div id="botonera">
	<img id="modificacion_presupuesto_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="modificacion_presupuesto_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"  />-->
	<img id="modificacion_presupuesto_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="modificacion_presupuesto_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_db_modificacion_presupuesto" id="form_db_modificacion_presupuesto">
<input name="modificacion_presupuesto_db_id" id="modificacion_presupuesto_db_id" type="hidden" />
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/proceso28x28.png" style="padding-right:5px;" align="absmiddle" />Modificaci&oacute;n Presupuesto de Ley</th>
		</tr>
		<tr>
			<th>A&ntilde;o:</th>
			<td>
				<select name="modificacion_presupuesto_pr_ano" id="modificacion_presupuesto_pr_ano" style="min-width:60px; width:60px;">
					<option value="<?=date('Y')-1;?>"><?=date('Y')-1;?></option>
					<option value="<?=date('Y');?>" selected="selected"><?=date('Y');?></option>
					<option value="<?=date('Y')+1;?>"><?=date('Y')+1;?></option>
				</select>
			
			</td>
		</tr>
		<tr>
	  		<tr>
			<th>Unidad Ejecutora:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="modificacion_presupuesto_pr_codigo_unidad" type="text" id="modificacion_presupuesto_pr_codigo_unidad"  maxlength="6"
						onchange="consulta_automatica_unidad" onclick="consulta_automatica_unidad"
						message="Introduzca un Codigo para el Accion Central."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					
						<input name="modificacion_presupuesto_db_nombre_unidad" type="text" id="modificacion_presupuesto_db_nombre_unidad"  style="width:62ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Central." 
						>
					</li>
					<li id="modificacion_presupuesto_db_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="modificacion_presupuesto_db_unidad_ejecutora" type="hidden" id="modificacion_presupuesto_db_unidad_ejecutora" />
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Centralizada:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="modificacion_presupuesto_pr_codigo_central" type="text" id="modificacion_presupuesto_pr_codigo_central"  maxlength="6"
						onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
						message="Introduzca un Codigo para el Accion Central."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}" >
					
						<input name="modificacion_presupuesto_db_accion_central" type="text" id="modificacion_presupuesto_db_accion_central"  style="width:62ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Central." 
						>
					</li>
					<li id="modificacion_presupuesto_db_btn_consultar_accion_central" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="modificacion_presupuesto_db_accion_central_id" type="hidden" id="modificacion_presupuesto_db_accion_central_id" />
		</tr>
		<tr>
			<th>Proyecto:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="modificacion_presupuesto_db_codigo_proyecto" type="text" id="modificacion_presupuesto_db_codigo_proyecto"  maxlength="6"
						onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
						message="Introduzca un Codigo para el Proyecto."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}">
					
						<input name="modificacion_presupuesto_db_proyecto" type="text" id="modificacion_presupuesto_db_proyecto"  style="width:62ex" maxlength="60"
						message="Introduzca un Nombre para el Proyecto." readonly 
						>
					</li>
					<li id="modificacion_presupuesto_db_btn_consultar_proyecto" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="modificacion_presupuesto_db_proyecto_id" type="hidden" id="modificacion_presupuesto_db_proyecto_id" />
		</tr>
		<tr>
			<th>Acci&oacute;n Espec&iacute;fica:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="modificacion_presupuesto_db_codigo_especifica" type="text" id="modificacion_presupuesto_db_codigo_especifica"  maxlength="6"
						onchange="consulta_automatica_especifica" onclick="consulta_automatica_especifica"
						message="Introduzca una Codigo para la Accion Especifica."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}"
						jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" >
					
						<input name="modificacion_presupuesto_db_accion_especifica" type="text" id="modificacion_presupuesto_db_accion_especifica"  style="width:62ex" maxlength="60"
						message="Introduzca una Denominacion para la Accion Especifica." readonly 
						>
					</li>
					<li id="modificacion_presupuesto_db_btn_consultar_accion_especifica" class="btn_consulta_emergente"></li>
				</ul>
		 	  	<input name="modificacion_presupuesto_db_accion_especifica_id" type="hidden" id="modificacion_presupuesto_db_accion_especifica_id" />
		</tr>
		<tr>
			<th>Partida:</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input name="modificacion_presupuesto_db_partida_numero" type="text" id="modificacion_presupuesto_db_partida_numero"  
						
						message="Introduzca una Codigo de la Partida."  size="10">
					
						<input name="modificacion_presupuesto_db_partida" type="text" id="modificacion_presupuesto_db_partida"  style="width:60ex" maxlength="60"
						message="Introduzca una Partida." readonly 
						>
					</li>
					<li id="modificacion_presupuesto_db_btn_consultar_partida" class="btn_consulta_emergente"></li>
				</ul>
		</tr>
		<tr>
			<th>Mes : </th>
			<td>
				<select name="modificacion_presupuesto_db_mes_cendente" id="modificacion_presupuesto_db_mes_cendente"  style="width:17ex;">
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
				</select>&nbsp;&nbsp;&nbsp;
			<!--<input type="radio" id="modificacion_presupuesto_db_operacion" name="modificacion_presupuesto_db_operacion" checked="checked" value="1" />
			Agregar Monto
			<input type="radio" id="modificacion_presupuesto_db_operacion" name="modificacion_presupuesto_db_operacion" value="2" />
			Restar
			Monto--></td>
		</tr>
		<tr>
			<th colspan="2">
				<table   width="100%" border="0">
					<tr>
						<th width="25%">Monto <br />Aprobado</th>
						<td>
							<input name="modificacion_presupuesto_db_monto_aprobado" type="text" 
							id="modificacion_presupuesto_db_monto_aprobado" size="15"  readonly style="text-align:right" value="0,00"
							/>						
						</td>						
						<th width="25%">Monto <br /> Disponible</th>
						<td>
							<input  name="modificacion_presupuesto_db_monto" type="text" id="modificacion_presupuesto_db_monto"  
							size="15" readonly 	message="Monto Actual"  value="0,00"
							jVal="{valid:/^[0123456789,.]{1,15}$/, message:'Monto Invalido', styleType:'cover'}"  
							jValKey="{valid:/[0123456789,]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}" style="text-align:right"/>												
						</td>
						<th width="25%">Monto  <br />Suma o  <br />Resta</th>
						<td>
							<input name="modificacion_presupuesto_db_monto_cedente" type="text" 
							id="modificacion_presupuesto_db_monto_cedente"  size="15" maxlength="15"  
							message="Introduzca el Monto."  value="0,00" alt="signed-decimal"
							 onkeyup="montoreceptor()" style="text-align:right"/>	
													
						</td>
					</tr>
					<tr>
						<th width="25%">Monto  <br />Aprobado  <br />Modificado</th>
						<td>
							<input name="modificacion_presupuesto_db_monto_aprobado_modificado" type="text"  readonly
							id="modificacion_presupuesto_db_monto_aprobado_modificado"  size="15" maxlength="15"  
							message="Introduzca el Monto."  value="0,00" onkeyup="montoreceptor()" style="text-align:right"/>														
						</td>
						<th width="25%">Monto  <br />Disponible  <br />Modificado</th>
						<td>
							<input name="modificacion_presupuesto_db_monto_total_disponible" type="text" 
							id="modificacion_presupuesto_db_monto_total_disponible" size="15"  readonly style="text-align:right" value="0,00"
							/>						
						</td>
						<th colspan="2"></th>
					</tr>
				</table>
			</th>		
		</tr>
		<tr>
			<th>Comentario</th>
			<td>
				<textarea name="modificacion_presupuesto_db_comentario" id="modificacion_presupuesto_db_comentario" cols="65" rows="3" message="Introduzca un Comentario para el Presupuesto."></textarea>			</td>
		</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	<tr>			
  </table>
</form>
