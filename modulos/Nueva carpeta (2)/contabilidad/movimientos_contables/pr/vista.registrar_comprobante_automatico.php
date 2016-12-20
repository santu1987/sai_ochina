<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha=date("d/m/Y");
?>
<script type='text/javascript'>
var dialog;

$("#contabilidad_comprobante_pr_btn_consultar").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_comprobante.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta_comprobante").val(); 
					var ano=$("#consulta_ano_comp").val();
				jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables.php?busq_cuenta="+busq_cuenta+"&ano="+ano,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#consulta_ano_comp").change(
					function()
					{
						
						dosearch();													
					}											
				);
				$("#consulta_comprobante").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
					var busq_cuenta= $("#consulta_comprobante").val();
					var ano=$("#consulta_ano_comp").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables.php?busq_cuenta="+busq_cuenta+"&ano="+ano,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables.php?busq_cuenta="+busq_cuenta+"&ano="+ano;
                 // alert(url);				
				}
			}
		}
	);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables.php',
								datatype: "json",
								colNames:['Id','Organismo','A&ntilde;o','Mes','tipo','Comprobante','Secuencia','Comentarios','Cuenta Contable','Desc','REF','Debito','Credito','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','codigo_tipo_comp','id_cc','ejecutora','utf','auxiliar','codigo_proyecto','id_acc','cod_acc','r_aux','r_ejec','r_proy','r_uft','monto_dif','nocomp2'],
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
										{name:'id_proyecto',index:'id_proyecto',width:50,hidden:true,hidden:true},
										{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:50,hidden:true},
										{name:'codigo_tipo_comp',index:'codigo_tipo_comp',width:50,hidden:true},
										{name:'id_cc',index:'id_cc',width:50,hidden:true,hidden:true},
										{name:'ejecutora',index:'ejecutora',width:50,hidden:true},
										{name:'utf',index:'utf',width:50,hidden:true},
										{name:'auxiliar',index:'auxiliar',width:50,hidden:true},
										{name:'codigo_proyecto',index:'codigo_proyecto',width:50,hidden:true},
										{name:'id_acc',index:'id_acc',width:50,hidden:true},
										{name:'cod_acc',index:'cod_acc',width:50,hidden:true},
										{name:'r_aux',index:'r_aux',width:50,hidden:true},
										{name:'r_ejec',index:'r_ejec',width:50,hidden:true},
										{name:'r_proy',index:'r_proy',width:50,hidden:true},
										{name:'r_utf',index:'r_utf',width:50,hidden:true},
										{name:'monto_dif',index:'monto_dif',width:50,hidden:true},
										{name:'ncomp2',index:'ncomp2',width:50,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								
							//alert(ret.numero_comprobante);
			 	getObj('contabilidad_vista_id_comprobante').value =ret.id;
				ncompe=ret.numero_comprobante;
				getObj('contabilidad_comprobante_pr_numero_comprobante').value =ncompe.substr(2,4);
				//getObj('contabilidad_comprobante_pr_numero_comprobante_oculto').value=ret.numero_comprobante;
				getObj('contabilidad_comprobante_pr_cuenta_contable').value=ret.cuenta_contable;
				getObj('contabilidad_comprobante_pr_ref').value=ret.ref;
				getObj('contabilidad_comprobante_pr_auxiliar').value=ret.auxiliar;
			 	getObj('contabilidad_comprobante_pr_ubicacion').value=ret.ejecutora;
				getObj('contabilidad_comprobante_pr_centro_costo').value=ret.codigo_proyecto;
				getObj('contabilidad_pr_centro_costo_id').value=ret.id_proyecto;
				getObj('contabilidad_comprobante_pr_utf').value=ret.utf;
				getObj('contabilidad_comprobante_db_id_cuenta').value=ret.id_cc;
				getObj('contabilidad_comprobante_pr_total_debe').value=ret.monto_debito;
				getObj('contabilidad_comprobante_pr_total_haber').value=ret.monto_credito;
				getObj('contabilidad_comprobante_pr_dif').value=ret.monto_dif;
				getObj('contabilidad_comprobante_pr_desc').value=ret.descripcion;
				getObj('contabilidad_comprobante_pr_tipo').value=ret.codigo_tipo_comp;
				getObj('contabilidad_comprobante_pr_tipo_id').value=ret.id_tipo_comprobante;
				getObj('contabilidad_comprobante_pr_acc_id').value=ret.id_acc;
				getObj('contabilidad_comprobante_pr_acc').value=ret.cod_acc;
				getObj('contabilidad_comprobante_pr_fecha').value=ret.fecha_comprobante;
				getObj('contabilidad_comprobante_pr_tipo_id').value=ret.id_tipo_comprobante;
				
				//
			//
				getObj('contabilidad_comprobante_contabilidad_id').value=ret.codigo_auxiliar;
			 	getObj('contabilidad_comprobante_pr_ejec_id').value=ret.codigo_unidad_ejecutora;
				//getObj('').value=ret.codigo_proyecto;
				getObj('contabilidad_comprobante_pr_utf_id').value=ret.codigo_utilizacion_fondos;
			//	
					/*	getObj('contabilidad_comprobante_pr_estatus').value="Abierto";
						getObj('contabilidad_comprobante_pr_estatus_oc').value='0';*/
						if ((getObj('contabilidad_comprobante_pr_total_debe').value)==(getObj('contabilidad_comprobante_pr_total_haber').value))
						{
							if(((getObj('contabilidad_comprobante_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comprobante_pr_total_haber').value)!="0,00"))
							{	
								getObj('contabilidad_integracion_movimientos_pr_btn_cerrar').style.display='';
							}
						}
				if(ret.monto_debito!="0,00")
				{
					debito_credito=1;
					getObj('contabilidad_comprobante_pr_monto').value=ret.monto_debito;
				}else
				if(ret.monto_credito!="0,00")
				{
					debito_credito=2;
					getObj('contabilidad_comprobante_pr_monto').value=ret.monto_credito;
				}	
				getObj('contabilidad_comprobante_pr_debe_haber').value=debito_credito;

				///// activando condiciones de campos ocultos
				bloquear();
									if(ret.r_aux=='t')
									{
										getObj('contabilidad_comprobante_pr_activo').value=1;
									}
									if(ret.r_proy=='t')
									{
										getObj('contabilidad_comprobante_pr_activo2').value=1;
									}
									if(ret.r_ejec=='t')
									{
										getObj('contabilidad_comprobante_pr_activo3').value=1;
									}
									if(ret.r_utf=='t')
									{
										getObj('contabilidad_comprobante_pr_activo4').value=1;
									}
			/*if(getObj('contabilidad_comprobante_pr_auxiliar').value!=0)
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
			if((getObj('contabilidad_pr_centro_costo_id').value!=0)||(getObj('contabilidad_comprobante_pr_acc_id').value!=0))
			{
				getObj('contabilidad_comprobante_pr_activo4').value=1;
			}*/
			//alert(ret.codigo_tipo_comprobante);
		/*	getObj('contabilidad_comprobante_pr_tipo').value=ret.codigo_tipo_comp;
			getObj('contabilidad_comprobante_pr_tipo_id').value=ret.id_tipo_comprobante;*/
				//////////////////////////////////////////////
				getObj('contabilidad_integracion_movimientos_pr_btn_cancelar').style.display='';
				getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='';
				//getObj('contabilidad_movimientos_pr_btn_guardar').style.display='none';*/
				num_comp=ret.ncomp2;
				getObj('ncomp2').value=ret.ncomp2;
			$tipos=ret.codigo_tipo_comp;
			jQuery("#list_comprobante_auto").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_grid_auto.php?nd='+new Date().getTime()+"&numero_comprobante="+num_comp,page:1}).trigger("reloadGrid");
				url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_grid_auto.php?nd='+new Date().getTime()+"&numero_comprobante="+num_comp,
				//alert(url);		
							dialog.hideAndUnload();
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
$("#contabilidad_vista_btn_consultar_proyecto").click(function() {
if(getObj('contabilidad_comprobante_pr_activo4').value==1)
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
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php',
								datatype: "json",
								colNames:['Id','C&oacute;digo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('contabilidad_comprobante_pr_acc_id').value ="0";
									getObj('contabilidad_comprobante_pr_acc').value ="0" ;
									getObj('contabilidad_comprobante_pr_centro_costo').value = ret.codigo;									
									getObj('contabilidad_pr_centro_costo_id').value = ret.id;
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

$("#contabilidad_integracion_movimientos_pr_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{desbloquear();
		$.ajax (
		{
			url: "modulos/contabilidad/movimientos_contables/pr/sql.actualizar_automatico.php",
			data:dataForm('form_contabilidad_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					bloquear();
				}
				else if (html=="NoActualizo")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
					//limpiar_comp_auto();
					bloquear();
					/*getObj('contabilidad_integracion_movimientos_pr_btn_cancelar').style.display='';
getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="numero_existe")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />N&Uacute;MERO DE COMPROBANTE YA UTILIZADO</p></div>",true,true);
				}
				else if (html=="modulo cerrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M&Oacute;DULO CERRADO</p></div>",true,true);
				}
				else if (html=="no_ano")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />NO COINCIDEN LOS A&Ntilde;OS</p></div>",true,true);
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
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload2,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#consulta-cuenta-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val();
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta;
                 //  alert(url);				
				}
			}
		}
	);
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
								colNames:['C&oacute;digo','Cuenta', 'Denominacion','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora','requiere_utilizacion_fondos'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'requiere_auxiliar',index:'requiere_auxiliar', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_proyecto',index:'requiere_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_unidad_ejecutora',index:'requiere_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_utilizacion_fondos',index:'requiere_utilizacion_fondos', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
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
									if(ret.requiere_utilizacion_fondos=='t')
									{
										getObj('contabilidad_comprobante_pr_activo4').value=1;
									}
									$('#contabilidad_comprobante_pr_cuenta_contable').val(ret.cuenta_contable);
									$('#contabilidad_comprobante_db_id_cuenta').val(ret.id);
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
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?cuenta='+getObj('contabilidad_comprobante_db_id_cuenta').value,							
											datatype: "json",
											colNames:['id','c&oacute;digo','Denominaci&oacute;n'],
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
											colNames:['id','c&oacute;digo','Unidad'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
												{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
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
												colNames:['id','c&oacute;digo','Unidad'],
												colModel:[
													{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
													{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
													{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
														],
												pager: $('#pager_grid_'+nd),
												rowNum:20,
												rowList:[20,50,100],
												imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
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
//////////////////////////////////////////-/-/-/-/-/-/-
$("#contabilidad_integracion_movimientos_pr_btn_cerrar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	debe=getObj('contabilidad_comprobante_pr_total_debe').value;
	haber=getObj('contabilidad_comprobante_pr_total_haber').value;
	if(debe==haber)
	{	
	  $.ajax (
		{
			url: "modulos/contabilidad/movimientos_contables/pr/sql_cerrar_comprobante_integracion.php",
			data:dataForm('form_contabilidad_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
			recordset=html;	recordset = recordset.split("*");
			//alert(recordset[0]);
			if (recordset[0]=="cerrado")
				{
					limpiar_comp_auto();
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/barras.png' />COMPROBANTE CONTABILIZADO ,<BR>CMPROBANTE N&ordm; "+recordset[1]+"</p></div>",true,true);
					//getObj('contabilidad_movimientos_pr_btn_abrir').style.display='';
					getObj('contabilidad_integracion_movimientos_pr_btn_cerrar').style.display='none';
				    /*getObj('contabilidad_integracion_movimientos_pr_btn_cancelar').style.display='';
					getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="NoActualizo")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
					limpiar_comp_auto();
					/*getObj('contabilidad_integracion_movimientos_pr_btn_cancelar').style.display='';
					getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="existe")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZÓ LA OPERACIÓN NÚMERO DE COMPROBANTE EXISTENTE</p></div>",true,true);
				}	
				else if (html=="modulo cerrado")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M&Oacute;DULO CERRADO</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}	
	else
		if(debe!=haber)
		{
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE CERRAR EL ASIENTO SI SE ENCUENTRA DESCUADRADO</p></div>",true,true);
		}
	
});
/////////////////////////////////////////////////////////////////////

function consulta_numero_comprobante_auto() {
		//alert("entro");
		$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_comprobante_cod_integracion.php",
            data:dataForm('form_contabilidad_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
			//	alert(html);
					if(html!="vacio")
					{
					/*	1$row3->fields("id_movimientos_contables"),
						2$row3->fields("id_organismo"),
						3$row3->fields("ano_comprobante"),
						4$row3->fields("mes_comprobante"),
						5$row3->fields("id_tipo_comprobante"),
						6$row_int->fields("numero_comprobante"),
						7$row3->fields("secuencia"),
						8$row3->fields("comentarios"),
						9$row3->fields("cuenta_contable"),
						10$row3->fields("descripcion"),
						11$row3->fields("referencia"),
						12$debe,
						13$haber,
						14$row3->fields("fecha_comprobante"),
						15$id_auxiliar,
						16$id_uejecutora,
						17$id_proyecto,
						18$row3->fields("id_utilizacion_fondos"),
						19$row3->fields("codigo_tipo"),
						20$row3->fields("id_cc"),
						21$cuenta_utf,
						22$codigo_uejecutora,
						23$cuenta_auxiliar,
						24$cod_proyecto,
						25$cod_acc,
						26$row3->fields("estatus"),
						27$id_acc,
						28$row3->fields("requiere_auxiliar"),
						29$row3->fields("requiere_unidad_ejecutora"),
						30$row3->fields("requiere_proyecto"),
						31$row3->fields("requiere_utilizacion_fondos")*/

					//	alert("entro");
									
									if(recordset[28]=='t')
									{
										getObj('contabilidad_comprobante_pr_activo').value=1;
									}
									if(recordset[31]=='t')
									{
										getObj('contabilidad_comprobante_pr_activo2').value=1;
									}
									if(recordset[29]=='t')
									{
										getObj('contabilidad_comprobante_pr_activo3').value=1;
									}
									if(recordset[30]=='t')
									{
										getObj('contabilidad_comprobante_pr_activo4').value=1;
									}
									getObj('contabilidad_vista_id_comprobante').value =recordset[0];
									// getObj('contabilidad_comprobante_pr_numero_comprobanterobante').value =recordset[6];
									getObj('contabilidad_comprobante_pr_cuenta_contable').value=recordset[8];
									getObj('contabilidad_comprobante_pr_ref').value=recordset[10];
									getObj('contabilidad_comprobante_pr_auxiliar').value=recordset[22];
									getObj('contabilidad_comprobante_pr_ubicacion').value=recordset[21];
									getObj('contabilidad_comprobante_pr_centro_costo').value=recordset[23];
									getObj('contabilidad_pr_centro_costo_id').value=recordset[16];
									getObj('contabilidad_comprobante_pr_utf').value=recordset[20];
									getObj('contabilidad_comprobante_db_id_cuenta').value=recordset[19];
									getObj('contabilidad_comprobante_pr_total_debe').value=recordset[11];
									getObj('contabilidad_comprobante_pr_total_haber').value=recordset[12];
									getObj('contabilidad_comprobante_pr_dif').value=recordset[31];
									
									getObj('contabilidad_comprobante_pr_desc').value=recordset[9];
									//
									getObj('contabilidad_comprobante_pr_ejec_id').value=recordset[15];
									getObj('contabilidad_comprobante_pr_utf_id').value=recordset[17];
									getObj('contabilidad_comprobante_contabilidad_id').value=recordset[14];
									///alert(ret.codigo_auxiliar);
									getObj('contabilidad_comprobante_pr_acc_id').value=recordset[26];
									getObj('contabilidad_comprobante_pr_acc').value=recordset[24];
									
									//
									if ((getObj('contabilidad_comprobante_pr_total_debe').value)==(getObj('contabilidad_comprobante_pr_total_haber').value))
									{
										if(((getObj('contabilidad_comprobante_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comprobante_pr_total_haber').value)!="0,00"))
										{	
											
											getObj('contabilidad_integracion_movimientos_pr_btn_cerrar').style.display='';
												getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='';

			
										}
										
									}
									/*alert(ret.estatus);
								if(recordset[25]==1)
									{
										getObj('contabilidad_comprobante_pr_estatus').value="Cerrado";
										getObj('contabilidad_comprobante_pr_estatus_oc').value='1';
										getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
										getObj('contabilidad_movimientos_pr_btn_cerrar').style.display='none';
									}*/
									/*if(recordset[25]==0)
									{
											getObj('contabilidad_comprobante_pr_estatus').value="Abierto";
											getObj('contabilidad_comprobante_pr_estatus_oc').value='0';
											if ((getObj('contabilidad_comprobante_pr_total_debe').value)==(getObj('contabilidad_comprobante_pr_total_haber').value))
											{
													if(((getObj('contabilidad_comprobante_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comprobante_pr_total_haber').value)!="0,00"))
													{	
													getObj('contabilidad_movimientos_pr_btn_cerrar').style.display='';
													getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
													}
											
											}
									}*/
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
									
									///// activando condiciones de campos ocultos
									//bloquear();
									//	alert(ret.codigo_auxiliar);
									if(recordset[27]!=0)
									{
										getObj('contabilidad_comprobante_pr_activo').value=1;
									}
									if(recordset[28]!=0)
									{
										getObj('contabilidad_comprobante_pr_activo2').value=1;
									}
									if(recordset[29]!=0)
									{
										getObj('contabilidad_comprobante_pr_activo3').value=1;
									}
									if(recordset[30]!=0)
									{
										getObj('contabilidad_comprobante_pr_activo4').value=1;
									}
									//alert(ret.codigo_tipo_comprobante);
									getObj('contabilidad_comprobante_pr_tipo').value=recordset[18];
									getObj('contabilidad_comprobante_pr_tipo_id').value=recordset[4];
										
									//////////////////////////////////////////////
								comprobante=getObj('contabilidad_comprobante_pr_tipo').value+getObj('contabilidad_comprobante_pr_numero_comprobante').value;
								jQuery("#list_comprobante_auto").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_grid_auto.php?nd='+new Date().getTime()+"&numero_comprobante="+comprobante,page:1}).trigger("reloadGrid");
										url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_grid_auto.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comprobante_pr_numero_comprobante').value;

					}
			 }
		});	 	 
}		
///////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_db_documentos_btn_abrir").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	debe=getObj('contabilidad_comp_pr_total_debe').value;
	haber=getObj('contabilidad_comp_pr_total_haber').value;

	  $.ajax (
		{
			url: "modulos/contabilidad/movimientos_contables/pr/sql_abrir_comprobante.php",
			data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Abierto")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />SE APERTURO EL COMPROBANTE</p></div>",true,true);
				//	limpiar_comp();
					getObj('contabilidad_comp_pr_estatus').value='Abierto';
					getObj('contabilidad_comp_pr_estatus_oc').value='0';
					getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
					getObj('contabilidad_integracion_movimientos_pr_btn_cerrar').style.display='';
				    /*getObj('contabilidad_integracion_movimientos_pr_btn_cancelar').style.display='';
					getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="NoActualizo")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
					//limpiar_comp();
					/*getObj('contabilidad_integracion_movimientos_pr_btn_cancelar').style.display='';
					getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';

					clearForm('form_contabilidad_pr_movimientos');*/
				}
				
				else
				{
					setBarraEstado(html);
				}
			}
		});
	
	
});
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////-/-/-/-/-/-
function limpiar_comp_auto()
{
	setBarraEstado("");
	////getObj('').style.display='';
	//getObj('tesoreria_moneda_db_btn_eliminar').style.display='none';
	
	getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='none';
	getObj('contabilidad_integracion_movimientos_pr_btn_cerrar').style.display='none';
	//getObj('contabilidad_auxiliares_db_btn_consultar').style.display='';
	clearForm('form_contabilidad_pr_movimientos');
	jQuery("#list_comprobante_auto").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_grid_auto.php',page:1}).trigger("reloadGrid");
	url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_grid_auto.php';
	//alert(url);
	getObj('contabilidad_comprobante_pr_activo').value=0;
	getObj('contabilidad_comprobante_pr_activo2').value=0;
	getObj('contabilidad_comprobante_pr_activo3').value=0;
	getObj('contabilidad_comprobante_pr_activo4').value=0;
	getObj('contabilidad_comprobante_pr_monto').value="0,00";
	getObj('contabilidad_comprobante_pr_total_debe').value="0,00";
	getObj('contabilidad_comprobante_pr_total_haber').value="0,00";
	getObj('contabilidad_comprobante_pr_fecha').value="<?=  date("d/m/Y"); ?>";

	desbloquear();
}
$("#contabilidad_integracion_movimientos_pr_btn_cancelar").click(function() {
limpiar_comp_auto();
	
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
///
$("#contabilidad_vista_btn_consultar_acc").click(function() {
if(getObj('contabilidad_comprobante_pr_activo4').value==1)
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
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php',
								datatype: "json",
								colNames:['Id','C&oacute;digo', 'Acci&oacute;n Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '.utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('contabilidad_comprobante_pr_acc_id').value = ret.id;
									getObj('contabilidad_comprobante_pr_acc').value = ret.codigo;
									getObj('contabilidad_comprobante_pr_centro_costo').value = "0";									
									getObj('contabilidad_pr_centro_costo_id').value = "0";
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
//////////////////////////consultas automaticas inferiores
function consulta_auxiliar_auto_mov()
{
valores=getObj('contabilidad_comprobante_pr_activo').value;	
if((valores!=0)&&(getObj('contabilidad_comprobante_db_id_cuenta').value!=''))
	{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_auxi2.php",
						data:dataForm('form_contabilidad_pr_movimientos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('contabilidad_comprobante_contabilidad_id').value=recordset[0];
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_comprobante_pr_auxiliar').value='';
								getObj('contabilidad_comprobante_contabilidad_id').value='';

							}
							
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comprobante_pr_auxiliar').value='';
		  getObj('contabilidad_comprobante_contabilidad_id').value='';

	}
}

/////////////////////////////////////////////////////
function consulta_automatica_cuentas()
{
	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuentas_contables_int.php",
            data:dataForm('form_contabilidad_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
				getObj('contabilidad_comprobante_pr_cuenta_contable').value = recordset[1];
				getObj('contabilidad_comprobante_db_id_cuenta').value=recordset[0];
				}
				else
				{
				getObj('contabilidad_comp_pr_cuenta_contable').value = "";
				getObj('contabilidad_comprobante_db_id_cuenta').value="";
				//getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value="";
				}
				
			 }
		});	 	 
}
////
function consulta_acc_mov()
{
valores=getObj('contabilidad_comprobante_pr_activo4').value;	
if(valores!=0)
{
	//alert(html);
	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_acc2.php",
            data:dataForm('form_contabilidad_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
				///alert(html);
					if(html!="vacio")
					{
						$('#').val(recordset[1]);
						$('#contabilidad_comprobante_pr_acc_id').val(recordset[0]);
					}else
					if(html!="vacio")
					{
						getObj('#contabilidad_comprobante_pr_acc').value="";
						getObj('#contabilidad_comprobante_pr_acc_id').value="";
					}
			 }
		});	 	 

}else
					{
						getObj('#contabilidad_comprobante_pr_acc').value="";
						getObj('#contabilidad_comprobante_pr_acc_id').value="";
					}
}
///
function consulta_tipo_comprobante_inte()
{
	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo_comprobante_cod_int.php",
            data:dataForm('form_contabilidad_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
				//alert(html);
					if(html!="vacio")
					{
						$('#contabilidad_comprobante_pr_tipo').val(recordset[1]);
						$('#contabilidad_comprobante_pr_tipo_id').val(recordset[0]);
					}
					if(html=="vacio")
					{
						getObj('contabilidad_comprobante_pr_tipo').value="";
						getObj('contabilidad_comprobante_pr_tipo_id').value="";
					}
			 }
		});	 	 
}///
/////////////////////////////////////////////////////////////////////////////////////////////
function consulta_proy_auto()
{
valores=getObj('contabilidad_comprobante_pr_activo4').value;	
if(valores!=0)
	{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_proy2.php",
						data:dataForm('form_contabilidad_pr_movimientos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('contabilidad_pr_centro_costo_id').value=recordset[0];
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_comprobante_pr_centro_costo').value='';
								getObj('contabilidad_pr_centro_costo_id').value='';

							}
							
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comprobante_pr_centro_costo').value='';
		  getObj('contabilidad_pr_centro_costo_id').value='';

	}

}
/////////////////////////////////////////////////////////////////////////////////////////////
function consulta_ubicacion_mov()
{
//alert("entro");
	valores=getObj('contabilidad_comprobante_pr_activo2').value;	
	if(valores!=0)
		{	
			//alert('entro');
				$.ajax({
							url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_ubic2.php",
							data:dataForm('form_contabilidad_pr_movimientos'), 
							type:'POST',
							cache: false,
							 success:function(html)
							 {
								var recordset=html;
								//alert(html);
								if((recordset)&&(recordset!='vacio'))
								{
									recordset = recordset.split("*");
									getObj('contabilidad_comprobante_pr_ejec_id').value=recordset[0];
								}
								else
								if(recordset=='vacio')
								{	
									getObj('contabilidad_comprobante_pr_ejec_id').value='';
									getObj('contabilidad_comprobante_pr_ubicacion').value='';
	
								}
								
							 }
						});	 	
		}
		else
		{	
			  getObj('contabilidad_comprobante_pr_ejec_id').value='';
			  getObj('contabilidad_comprobante_pr_ubicacion').value='';
	
		}


}
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
function consulta_utf_mov()
{
//alert("entro");
	valores=getObj('contabilidad_comprobante_pr_activo3').value;	
	if(valores!=0)
		{	
	//		alert('entro');
				$.ajax({
							url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_utf2.php",
							data:dataForm('form_contabilidad_pr_movimientos'), 
							type:'POST',
							cache: false,
							 success:function(html)
							 {
								var recordset=html;
							//	alert(html);
								if((recordset)&&(recordset!='vacio'))
								{
									recordset = recordset.split("*");
									getObj('contabilidad_comprobante_pr_utf_id').value=recordset[0];
								}
								else
								if(recordset=='vacio')
								{	
									getObj('contabilidad_comprobante_pr_utf_id').value='';
									getObj('contabilidad_comprobante_pr_utf').value='';
	
								}
								
							 }
						});	 	
		}
		else
		{	
			  getObj('').value='';
			  getObj('contabilidad_comprobante_pr_utf').value='';
	
		}


}
/////////////////////////////////////////////////////////////////////////////////////////////
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
	url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_grid_auto.php?nd='+new Date().getTime(),
//	+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value
	datatype: "json",
	colNames:['Id','Organismo','Ano','Mes','tipo','Comprobante','Secuencia','Comentarios','Cuenta Contable','Desc','REF','Debe','Haber','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','id_cc'],
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
			{name:'id_cc',index:'id_cc',width:30,hidden:true}
			

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
			  /*alert(url);*/
			 // alert(html);
			    var recordset=html;				
				recordset = recordset.split("*");
				getObj('contabilidad_vista_id_comprobante').value = recordset[0];
				getObj('contabilidad_comprobante_pr_numero_comprobante').value = recordset[5];
				getObj('contabilidad_comprobante_pr_numero_comprobante_oculto').value=recordset[5];
				getObj('contabilidad_comprobante_pr_cuenta_contable').value=recordset[8];
				getObj('contabilidad_comprobante_pr_desc').value=recordset[9];
				getObj('contabilidad_comprobante_pr_ref').value=recordset[10];
				getObj('contabilidad_comprobante_pr_tipo').value=recordset[18];
				getObj('contabilidad_comprobante_pr_tipo_id').value=recordset[4];
				//
				
				if(recordset[19]==0)
				{
						
						if ((getObj('contabilidad_comprobante_pr_total_debe').value)==(getObj('contabilidad_comprobante_pr_total_haber').value))
						{
							if(((getObj('contabilidad_comprobante_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comprobante_pr_total_haber').value)!="0,00"))
							{	
								
								getObj('contabilidad_integracion_movimientos_pr_btn_cerrar').style.display='';
							//	getObj('contabilidad_movimientos_pr_btn_abrir').style.display='none';
//
							}
							
						}
				}
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

				
				
				getObj('contabilidad_comprobante_pr_ubicacion').value=recordset[19];
				getObj('contabilidad_pr_centro_costo_id').value=recordset[15];
				getObj('contabilidad_comprobante_pr_utf').value=recordset[20];
				getObj('contabilidad_comprobante_pr_auxiliar').value=recordset[21];
				//////////////////////////
				getObj('contabilidad_comprobante_contabilidad_id').value=recordset[17];
				getObj('contabilidad_comprobante_pr_ejec_id').value=recordset[14];
				getObj('contabilidad_comprobante_pr_utf_id').value=recordset[16];
				getObj('contabilidad_comprobante_contabilidad_id').value=recordset[13];
				getObj('contabilidad_comprobante_pr_centro_costo').value=recordset[22];
				getObj('contabilidad_comprobante_pr_acc_id').value=recordset[23];
				getObj('contabilidad_comprobante_pr_acc').value=recordset[24];
				
				//////////////////////////
				/*getObj('contabilidad_comp_pr_ejec_id').value=recordset[22];
				getObj('contabilidad_comp_pr_utf_id').value=recordset[23];
				getObj('contabilidad_comprobante_contabilidad_id').value=recordset[24];*/
				/////////////////////////////
				///// activando condiciones de campos ocultos
				bloquear();
			if(recordset[25]!=0)
			{
				getObj('contabilidad_comprobante_pr_activo').value=1;
			}
			if(recordset[26]!=0)
			{
				getObj('contabilidad_comprobante_pr_activo2').value=1;
			}
			if(recordset[27])
			{
				getObj('contabilidad_comprobante_pr_activo3').value=1;
			}
				if(recordset[28])
			{
				getObj('contabilidad_comprobante_pr_activo4').value=1;
			}
				//////////////////////////////////////////////
		
				getObj('contabilidad_integracion_movimientos_pr_btn_cancelar').style.display='';
				getObj('contabilidad_integracion_movimientos_pr_btn_actualizar').style.display='';
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
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>

<script type='text/javascript'>
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
function esFechaValida(fecha){
    if (fecha != undefined && fecha != "" ){
        
        var dia  =  parseInt(fecha.substring(0,2),10);
        var mes  =  parseInt(fecha.substring(3,5),10);
        var anio =  parseInt(fecha.substring(6),10);
		if((anio>2100)||(anio<1900))
		{
			return false;
		}
    switch(mes){
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            numDias=31;
            break;
        case 4: case 6: case 9: case 11:
            numDias=30;
            break;
        case 2:
            if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
            break;
        default:
           // alert("Fecha introducida errónea");
            return false;
    }
        if (dia>numDias || dia==0){
         //   alert("Fecha introducida errónea");
            return false;
        }
        return anio;
    }
}
 
function comprobarSiBisisesto(anio){
if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
    return true;
    }
else {
    return false;
    }
}
function v_fecha2()
{
	//alert("entro");
	var1=esFechaValida(getObj('contabilidad_comprobante_pr_fecha').value);
	if(var1!=false)
	{
		var2=comprobarSiBisisesto(var1);
	}
	//alert(var1);
	//alert(var2);
	if((var1==false)||(var2==true))
	{
		getObj('contabilidad_comprobante_pr_fecha').value="";
	}

}

$('#contabilidad_auxiliares_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#contabilidad_comprobante_pr_auxiliar').numeric({});
$('#contabilidad_comprobante_pr_centro_costo').numeric({});
$('#contabilidad_comprobante_pr_acc').numeric({});
$('#contabilidad_comprobante_pr_ubicacion').numeric({});
$('#contabilidad_comprobante_pr_utf').numeric({});
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
	<img id="contabilidad_integracion_movimientos_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_comprobante_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<!--<img id="tesoreria_moneda_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/> -->

	<img id="contabilidad_integracion_movimientos_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
<!--	<img id="contabilidad_movimientos_pr_btn_abrir" src="imagenes/iconos/abrir_orden_cxp.png" style="display:none" />
    -->
	<img id="contabilidad_integracion_movimientos_pr_btn_cerrar" src="imagenes/iconos/contabilizar.png"   style="display:none"/>


</div>	
<form method="post" id="form_contabilidad_pr_movimientos" name="form_contabilidad_pr_movimientos">
<input type="hidden"  id="contabilidad_vista_id_comprobante" name="contabilidad_vista_id_comprobante"/>
<input type="hidden" id="contabilidad_comprobante_pr_activo" name="contabilidad_comprobante_pr_activo"  value="1"/>
 <input type="hidden" id="contabilidad_comprobante_pr_activo2" name="contabilidad_comprobante_pr_activo2" value="1"/>
 <input type="hidden" id="contabilidad_comprobante_pr_activo3" name="contabilidad_comprobante_pr_activo3" value="1"/>
  <input type="hidden" id="contabilidad_comprobante_pr_activo4" name="contabilidad_comprobante_pr_activo4" value="1"/>

  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Mantenimiento Comprobante Auto</th>
	</tr>
    	<tr>
		 	<th>Tipo Comprobante :</th>
			<td>
				
					<input type="text" name="contabilidad_comprobante_pr_tipo" id="contabilidad_comprobante_pr_tipo"  size='12' maxlength="12" onblur="consulta_tipo_comprobante_inte()" onchange="consulta_tipo_comprobante_inte()"
					message="Introduzca el tipo de cuenta" 
					jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" readonly="readonly"/>
					<input type="hidden" id="contabilidad_comprobante_pr_tipo_id" name="contabilidad_comprobante_pr_tipo_id" 
					jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
                    <input type="hidden" id="ncomp2" name="ncomp2" />
	<!--				<input type="text" name="cuentas_por_pagar_integracion_tipo_nombre" id="cuentas_por_pagar_integracion_tipo_nombre"  size='30' maxlength="30"
					message="Introduzca el tipo de cuenta" />
	-->					
	       </td>
	</tr>
	<tr >
	<th>N&uacute;mero Comprobante:</th>
		<td>
				<input type="text" id="contabilidad_comprobante_pr_numero_comprobante" name="contabilidad_comprobante_pr_numero_comprobante"   size='12' maxlength="12"  message="Introduzca nº comprobante" onchange="consulta_numero_comprobante_auto()"  onblur="consulta_numero_comprobante_auto()" readonly="readonly" >		
			<input type="hidden" id="contabilidad_comprobante_pr_numero_comprobante_oculto" name="contabilidad_comprobante_pr_numero_comprobante_oculto"  size='12' maxlength="12"  message="Introduzca nº comprobante" >		</td>
			
	</tr>
<tr>
		<th>
			Fecha:
		</th>
		<td width="124">
		            <input alt="date" type="text" name="contabilidad_comprobante_pr_fecha" id="contabilidad_comprobante_pr_fecha" size="7" value="<? echo($fecha);?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" onchange="v_fecha2();"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="contabilidad_comprobante_pr_fecha_oculto" id="contabilidad_comp_pr_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
				  <button type="reset" id="contabilidad_comprobante_pr_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "contabilidad_comprobante_pr_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "contabilidad_comprobante_pr_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("contabilidad_comprobante_pr_fecha").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_inicial_rp_fecha_hasta").value.MMDDAAAA() );
										
									}
							});
					</script>
		</td>
		</tr>	
	<tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="contabilidad_comprobante_pr_cuenta_contable" id="contabilidad_comprobante_pr_cuenta_contable"  size='12' maxlength="12" onblur="consulta_automatica_cuentas()" onchange="consulta_automatica_cuentas()"
				message="Introduzca la cuenta contable" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		        <input type="hidden" id="contabilidad_comprobante_db_id_cuenta" name="contabilidad_comprobante_db_id_cuenta"  
				jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
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
			<select name="contabilidad_comprobante_pr_debe_haber" id="contabilidad_comprobante_pr_debe_haber">
			<option id="1" value="1">Debe</option>
			<option id="2" value="2">Haber</option>
			</select>
			<!--<input type="hidden" name="contabilidad_comprobante_pr_debe_haber" id="contabilidad_comprobante_pr_debe_haber" size="12" maxlength="1" onblur="validar_debe_haber()"
				/>-->
			</td>
							
		</tr>
		<tr>
			<th>
				Monto:			</th>
			<td>
				<input type="text" name="contabilidad_comprobante_pr_monto" id="contabilidad_comprobante_pr_monto" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" message="Introduzca el monto del asiento" size="12" maxlength="12" >			</td>
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
					<input name="contabilidad_comprobante_pr_auxiliar" type="text" id="contabilidad_comprobante_pr_auxiliar"   value="" size="12" maxlength="12" message="Introduzca el c&iacute;digo del auxiliar' "  onblur="consulta_auxiliar_auto_mov()"
										jval="{valid:/^[0-9]{1,12}$/, message:'C&oacute;digo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/> 
					 <input type="hidden" id="contabilidad_comprobante_contabilidad_id" name="contabilidad_comprobante_contabilidad_id"
					 jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					 </li>
					<li id="contabilidad_vista_btn_consultar_auxiliar_cmp" class="btn_consulta_emergente"></li>
					</ul>
					</td>
					<th>Ubicaci&oacute;n:</th>
					<td>
					<ul class="input_con_emergente">
					<li>
							  <input name="contabilidad_comprobante_pr_ubicacion" type="text" id="contabilidad_comprobante_pr_ubicacion"   value="" size="12" maxlength="12" message="Introduzca ubicaci&oacute;n de la cuenta ejm:Div. Telemat' "  onblur="consulta_ubicacion_mov()"
							jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
						  <input type="hidden" name="contabilidad_comprobante_pr_ejec_id" id="contabilidad_comprobante_pr_ejec_id"
						  jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
					  	  jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					 </li>
					<li id="contabilidad_vista_btn_consultar_ubicacion_cmp" class="btn_consulta_emergente"></li>
					</ul>	
					</td>
			</tr>
			<tr>
					<th>Proyecto:</th>
					<td>
					<ul class="input_con_emergente">
					<li>
					<input name="contabilidad_comprobante_pr_centro_costo" type="text" id="contabilidad_comprobante_pr_centro_costo"   value="" size="12" maxlength="12" message="Introduzca el centro de costo' " 
										jval="{valid:/^[0-9]{1,12}$/, message:'C&oacute;digo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"
 onblur="consulta_proy_auto()"
 />	
						<input type="hidden" id="contabilidad_pr_centro_costo_id" name="contabilidad_pr_centro_costo_id" 
						jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					</li>
					<li id="contabilidad_vista_btn_consultar_proyecto" class="btn_consulta_emergente"></li>	
					</ul>
					</td>
					<th>Utilizaci&oacute;n Fondos</th>
					<td>
					<ul class="input_con_emergente">
					<li>
					  <input name="contabilidad_comprobante_pr_utf" type="text" id="contabilidad_comprobante_pr_utf"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo de Utilizaci&oacute;n de fondos' " onblur="consulta_utf_mov()"
										jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					  <input type="hidden" id="contabilidad_comprobante_pr_utf_id" name="contabilidad_comprobante_pr_utf_id" 
					  jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					 </li>
					<li id="contabilidad_vista_btn_consultar_utf" class="btn_consulta_emergente"></li>
					</ul>
					</td>
		</tr>
		<tr>
		<th>Accion Centralizada</th>
					<td>
					<ul class="input_con_emergente">
					<li>
					  <input name="contabilidad_comprobante_pr_acc" type="text" id="contabilidad_comprobante_pr_acc"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo de Utilización de fondos' "  onblur="consulta_acc_mov()"
										jval="{valid:/^[0-9]{1,12}$/, message:'C&oacute;digo Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					  <input type="hidden" id="contabilidad_comprobante_pr_acc_id" name="contabilidad_comprobante_pr_acc_id"
					  jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
					 </li>
					<li id="contabilidad_vista_btn_consultar_acc" class="btn_consulta_emergente"></li>
					</ul>
					</td>
					<th>Diferencia Debe-Haber 					</th>
					<td>
						 <input  type="text" name="contabilidad_comprobante_pr_dif" id="contabilidad_comprobante_pr_dif"  readonly="readonly" size="12" maxlength="12"  value="0,00"/>
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