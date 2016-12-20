<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<?php 
$fechaAct =date("Y");
$fechas;
for($i=1994;$i<=$fechaAct;$i++){
	$fecha.="<option value=".$i.">".$i."</option>";
	}
?>
<script type='text/javascript'>
//------------------ Marcaras de edicion de campos de entrada -----------------////
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
//----------------- fin mascara edicion de campo -------------------------///

var dialog;
//------------------------------------------------------------------------------------------------


$("#sareta_avisos_anuales_pr_btn_consultar").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_anuales/pr/grid_avisos_anuales.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Aviso Anual', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_avisos_anuales.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_avisos_anuales.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_anuales/pr/sql_grid_avisos_anuales.php?nombre="+busq_nombre;
							
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/avisos_anuales/pr/sql_grid_avisos_anuales.php?nd='+nd,
								datatype: "json",
								colNames:['id','id_buque','A&ntilde;o','Matricula','Call Sign','Buque','','id_clase_buque','Clase','','id_actividad_buque','Actividad','','id_bandera_buque','Bandera','','R. Bruto','registro_bruto_valor_buque','id_ley','Tarifa','tarifa_valor','obs','F. Recalada','id_cambio_moneda','Moneda','moneda_cambio','id_armador','Armador','id_agencia','Agencia Nav'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_buque',index:'id_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ano',index:'ano', width:220,sortable:false,resizable:false},
									{name:'matricula',index:'matricula', width:220,sortable:false,resizable:false},
									{name:'call_sign',index:'call_sign', width:220,sortable:false,resizable:false},
									{name:'nombre1',index:'nombre1', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_Clase_buque',index:'id_Clase_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'Clase1',index:'Clase1', width:220,sortable:false,resizable:false},
									{name:'Clase',index:'Clase', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_actividad_buque',index:'id_actividad_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad1',index:'actividad1', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad',index:'actividad', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_bandera_buque',index:'id_bandera_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera1',index:'bandera1', width:220,sortable:false,resizable:false},
									{name:'bandera',index:'bandera', width:220,sortable:false,resizable:false,hidden:true},
								
									{name:'registro_bruto_buque',index:'registro_bruto_buque', width:220,sortable:false,resizable:false},
									{name:'registro_bruto_valor_buque',index:'registro_bruto_valor_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_ley',index:'id_ley', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'tarifa_buque',index:'tarifa_buque', width:220,sortable:false,resizable:false},
									{name:'tarifa_valor',index:'tarifa_valor', width:220,sortable:false,resizable:false,hidden:true},
		
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true},
									{name:'fecha_recalada',index:'fecha_recalada', width:220,sortable:false,resizable:false},
									{name:'id_cambio_moneda',index:'id_cambio_moneda', width:220,sortable:false,resizable:false,hidden:true},
									{name:'moneda',index:'moneda', width:220,sortable:false,resizable:false,hidden:true},
									{name:'moneda_cambio',index:'moneda_cambio', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_armador',index:'id_armador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'armador',index:'armador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_agencia',index:'id_agencia', width:220,sortable:false,resizable:false,hidden:true},
									{name:'agencia',index:'agencia', width:220,sortable:false,resizable:false,hidden:true}
					
								],
								
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
										
									
								
										getObj('vista_id_avisos_anuales').value = ret.id;
										getObj('sareta_avisos_anuales_pr_tarifa').value = ret.tarifa_buque;
										getObj('sareta_avisos_anuales_pr_tarifa_valor').value = ret.tarifa_valor;														
										getObj('sareta_avisos_anuales_pr_buque').value = ret.nombre;
										getObj('sareta_avisos_anuales_pr_matricula').value = ret.matricula;
										getObj('sareta_avisos_anuales_pr_call_sign').value = ret.call_sign;
										getObj('sareta_avisos_anuales_pr_clase').value = ret.Clase;
										getObj('sareta_avisos_anuales_pr_actividad').value = ret.actividad;
										getObj('sareta_avisos_anuales_pr_bandera').value = ret.bandera;
										getObj('sareta_avisos_anuales_pr_rb').value = ret.registro_bruto_buque;
										getObj('sareta_avisos_anuales_pr_registro_bruto_valor_buque').value = ret.registro_bruto_valor_buque;
										getObj('sareta_avisos_anuales_pr_vista_observacion').value = ret.obs;	
										getObj('sareta_avisos_anuales_pr_ano').value = ret.ano;	
										getObj('sareta_avisos_anuales_pr_fecha_recalada').value = ret.fecha_recalada;
										getObj('sareta_avisos_anuales_pr_femision').value = ret.fecha_recalada;
										getObj('sareta_avisos_anuales_pr_id_moneda').value = ret.id_cambio_moneda;
										getObj('sareta_avisos_anuales_pr_moneda').value = ret.moneda;
										
										getObj('sareta_avisos_anuales_valor_moneda').value = ret.moneda_cambio;
										//se multiplica por 1 para hacer un cambio de esprecion
										var valor_cam=(1*ret.moneda_cambio);
										getObj('sareta_avisos_anuales_pr_cambio').value = valor_cam.currency(2,',','.');
										
										
									getObj('sareta_avisos_anuales_pr_id_armador').value = ret.id_armador;
									getObj('sareta_avisos_anuales_pr_armador').value = ret.armador;
									getObj('sareta_avisos_anuales_pr_id_agencia').value = ret.id_agencia;
									getObj('sareta_avisos_anuales_pr_agencia').value = ret.agencia;
							
										
										
										//calculo de monto$
									
									var montoD =(getObj('sareta_avisos_anuales_pr_registro_bruto_valor_buque').value*getObj('sareta_avisos_anuales_valor_moneda').value*getObj('sareta_avisos_anuales_pr_tarifa_valor').value )/(getObj('sareta_avisos_anuales_valor_moneda').value); 
									getObj('sareta_avisos_anuales_pr_monto').value =montoD.currency(2,',','.');
								/*	//calculo de monto*/
									var monto =getObj('sareta_avisos_anuales_pr_registro_bruto_valor_buque').value*getObj('sareta_avisos_anuales_valor_moneda').value*getObj('sareta_avisos_anuales_pr_tarifa_valor').value ; 
									getObj('sareta_avisos_anuales_pr_montoTotal').value =monto.currency(2,',','.');
										
										
									//getObj('sareta_avisos_anuales_pr_btn_guardar').style.display='none';
									getObj('sareta_avisos_anuales_pr_btn_actualizar').style.display='';
									getObj('sareta_avisos_anuales_pr_btn_eliminar').style.display='';
										
								dialog.hideAndUnload();
									$('#form_pr_avisos_anuales').jVal();
									
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:'0123456789- '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
	//-----------------------------------------------------consultar_armador-------------------------------------------

$("#sareta_avisos_anuales_pr_btn_consultar_armador").click(function() {
if(getObj('vista_id_avisos_anuales').value!==''){
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_anuales/pr/grid_armador.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Armadores', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_armador.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_armador.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_anuales/pr/sql_grid_armador.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/avisos_anuales/pr/sql_grid_armador.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sareta_avisos_anuales_pr_id_armador').value = ret.id;
									getObj('sareta_avisos_anuales_pr_armador').value = ret.nombre;
									
									dialog.hideAndUnload();
									$('#form_pr_avisos_anuales').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}//fin de validar si el aviso anual se encuentra vacio
else
{
alert('ES NECESARIO QUE LOS DATOS DEL AVISO ANUAL SE ENCUENTRE LLENOS');
}	
});

//--------------------------------------------------------consultar_agencia------------------------------------------------------------
$("#sareta_avisos_anuales_pr_btn_consultar_agencia").click(function() {
if(getObj('vista_id_avisos_anuales').value!==''){
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_anuales/pr/grid_agencia_naviera.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Agencias Navieras', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_anuales/pr/sql_grid_agencia_naviera.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});

		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÃ³n del Servidor",		
								url:'modulos/sareta/avisos_anuales/pr/sql_grid_agencia_naviera.php?nd='+nd,
								datatype: "json",
								colNames:['id_agencia_naviera','id_delegacion','Nombre','nom','RIF','NIT','Direcci&oacute;n','id_estado','Estado','&Aacute;rea','Zona','Apartado','Telefono ','Telefono 2','Fax ','Fax 2','Pag Web','pag_web','Correo','Contacto','cont','Cedula','Cargo','Codigo Auxiliar','Comentario'],
								colModel:[
										{name:'id_agencia_naviera',index:'id_agencia_naviera', width:220,sortable:false,resizable:false,hidden:true},
										{name:'id_delegacion',index:'id_delegacion', width:220,sortable:false,resizable:false,hidden:true},
										{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
										{name:'nom',index:'nom', width:220,sortable:false,resizable:false,hidden:true},
										{name:'rif',index:'rif', width:220,sortable:false,resizable:false},
										{name:'nit',index:'nit', width:220,sortable:false,resizable:false,hidden:true},
										{name:'direccion',index:'di2  €ion', width:220,sortable:false,resizable:false,hidden:true},
										{name:'id_estado',index:'id_estado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'estado',index:'estado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'area',index:'area', width:220,sortable:false,resizable:false},
										{name:'zona',index:'zona', width:220,sortable:false,resizable:false,hidden:true},
										{name:'apartado',index:'apartado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'telefono1',index:'telefono1', width:220,sortable:false,resizable:false},
										{name:'telefono2',index:'telefono2', width:220,sortable:false,resizable:false,hidden:true},
										{name:'fax1',index:'fax1', width:220,sortable:false,resizable:false,hidden:true},
										{name:'fax2',index:'fax2', width:220,sortable:false,resizable:false,hidden:true},

										{name:'pag',index:'pag', width:220,sortable:false,resizable:false},
										{name:'pag_web',index:'pag_web', width:220,sortable:false,resizable:false,hidden:true},
										{name:'correo',index:'correo', width:220,sortable:false,resizable:false,hidden:true},
										{name:'contacto',index:'contacto', width:220,sortable:false,resizable:false},
										{name:'cont',index:'cont', width:220,sortable:false,resizable:false,hidden:true},
										{name:'cedula',index:'cedula', width:220,sortable:false,resizable:false,hidden:true},
										{name:'cargo',index:'cargo', width:220,sortable:false,resizable:false,hidden:true},
										{name:'auxiliar',index:'auxiliar', width:220,sortable:false,resizable:false,hidden:true},
										{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//trasferi los datos a los campos del formulario
									getObj('sareta_avisos_anuales_pr_id_agencia').value = ret.id_agencia_naviera;
									getObj('sareta_avisos_anuales_pr_agencia').value = ret.nom;
							
									dialog.hideAndUnload();
									$('#form_pr_avisos_anuales').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:'0123456789- '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}//fin de validar si el aviso anual se encuentra vacio
else
{
alert('ES NECESARIO QUE LOS DATOS DEL AVISO ANUAL SE ENCUENTRE LLENOS');
}
});
	
//------------------------------------------------------consultar_moneda----------------------------------------------------------------------------
$("#sareta_avisos_anuales_pr_btn_consultar_moneda").click(function() {
if(getObj('vista_id_avisos_anuales').value!==''){		
		var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_anuales/pr/grid_cambio_moneda.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Moneda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_cambio_moneda.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_anuales/pr/sql_grid_cambio_moneda.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_anuales/pr/sql_grid_cambio_moneda.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/avisos_anuales/pr/sql_grid_cambio_moneda.php?nd='+nd,
								datatype: "json",
								colNames:['id','id_moneda','Moneda','moneda','Fecha de Cambio','Valor','Comentario','Com','valor_moneda'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
								{name:'id_moneda',index:'id_moneda', width:220,sortable:false,resizable:false,hidden:true},
								{name:'moneda1',index:'moneda1', width:220,sortable:false,resizable:false},
								{name:'moneda',index:'moneda', width:220,sortable:false,resizable:false,hidden:true},
								{name:'fecha',index:'fecha', width:220,sortable:false,resizable:false},
								{name:'valor',index:'valor', width:220,sortable:false,resizable:false},
								{name:'obs1',index:'obs1', width:220,sortable:false,resizable:false},
								{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true},
								{name:'valor_moneda',index:'valor_moneda', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sareta_avisos_anuales_pr_id_moneda').value = ret.id;
									getObj('sareta_avisos_anuales_pr_moneda').value = ret.moneda;
									getObj('sareta_avisos_anuales_pr_cambio').value = ret.valor;
									getObj('sareta_avisos_anuales_valor_moneda').value = ret.valor_moneda;
									
								
									
									//calculo de monto$
									
									var montoD =(getObj('sareta_avisos_anuales_pr_registro_bruto_valor_buque').value*getObj('sareta_avisos_anuales_valor_moneda').value*getObj('sareta_avisos_anuales_pr_tarifa_valor').value )/(getObj('sareta_avisos_anuales_valor_moneda').value); 
									getObj('sareta_avisos_anuales_pr_monto').value =montoD.currency(2,',','.');
								/*	//calculo de monto*/
									var monto =getObj('sareta_avisos_anuales_pr_registro_bruto_valor_buque').value*getObj('sareta_avisos_anuales_valor_moneda').value*getObj('sareta_avisos_anuales_pr_tarifa_valor').value ; 
									getObj('sareta_avisos_anuales_pr_montoTotal').value =monto.currency(2,',','.');
									dialog.hideAndUnload();
									$('#form_pr_avisos_anuales').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}//fin de validar si el aviso anual se encuentra vacio
else
{
alert('ES NECESARIO QUE LOS DATOS DEL AVISO ANUAL SE ENCUENTRE LLENOS');
}
});
//-----------------------------Codigo para Atualizar 

$("#sareta_avisos_anuales_pr_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_pr_avisos_anuales').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/avisos_anuales/pr/sql.actualizar.php",
			data:dataForm('form_pr_avisos_anuales'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
				
	getObj('sareta_avisos_anuales_pr_btn_cancelar').style.display='';
	getObj('sareta_avisos_anuales_pr_btn_eliminar').style.display='none';
	getObj('sareta_avisos_anuales_pr_btn_actualizar').style.display='none';
	//getObj('sareta_avisos_anuales_pr_btn_guardar').style.display='';
	clearForm('form_pr_avisos_anuales');
	
	getObj('sareta_avisos_anuales_pr_cambio').value = "0,00";
	getObj('sareta_avisos_anuales_pr_monto').value = "0,00";
	getObj('sareta_avisos_anuales_pr_montoTotal').value = "0,00";
	getObj('sareta_avisos_anuales_pr_tarifa').value = "0,00";

					});															
				}
				else
				{
				setBarraEstado(html,true,true);
				}
			}
		});
	}
});
//------------------------------------------------Codigo para eliminar  

$("#sareta_avisos_anuales_pr_btn_eliminar").click(function() {
  if (getObj('form_pr_avisos_anuales').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/avisos_anuales/pr/sql.eliminar.php",
			data:dataForm('form_pr_avisos_anuales'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);					
					getObj('sareta_avisos_anuales_pr_btn_cancelar').style.display='';
					getObj('sareta_avisos_anuales_pr_btn_eliminar').style.display='none';
					getObj('sareta_avisos_anuales_pr_btn_actualizar').style.display='none';
					//getObj('sareta_avisos_anuales_pr_btn_guardar').style.display='';
					clearForm('form_pr_avisos_anuales');
					getObj('sareta_avisos_anuales_pr_cambio').value = "0,00";
					getObj('sareta_avisos_anuales_pr_monto').value = "0,00";
					getObj('sareta_avisos_anuales_pr_montoTotal').value = "0,00";
					getObj('sareta_avisos_anuales_pr_tarifa').value = "0,00";

				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con esta Agencia Naviera</p></div>",true,true); 
				}
				else 
				{
					
					setBarraEstado(html,true,true);
				}
			}
		});
	}
  }
});

//------------------------------------------------------------------------------------------------
	
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_avisos_anuales_pr_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_avisos_anuales_pr_btn_cancelar').style.display='';
	getObj('sareta_avisos_anuales_pr_btn_eliminar').style.display='none';
	getObj('sareta_avisos_anuales_pr_btn_actualizar').style.display='none';
	//getObj('sareta_avisos_anuales_pr_btn_guardar').style.display='';
	clearForm('form_pr_avisos_anuales');
	getObj('sareta_avisos_anuales_pr_cambio').value = "0,00";
	getObj('sareta_avisos_anuales_pr_monto').value = "0,00";
	getObj('sareta_avisos_anuales_pr_montoTotal').value = "0,00";
	getObj('sareta_avisos_anuales_pr_tarifa').value = "0,00";

});


	$('#sareta_avisos_anuales_pr_matricula').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});
	$('#sareta_avisos_anuales_pr_call_sign').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});
	$('#sareta_avisos_anuales_pr_nombre').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});

</script>
<style type="text/css">
<!--
.style4 {color: #33CCFF}
-->
</style>



<div id="botonera">
	<img id="sareta_avisos_anuales_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_avisos_anuales_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
  <!--  <img id="sareta_avisos_anuales_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />-->
    <img id="sareta_avisos_anuales_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_avisos_anuales_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
</div>

<form method="post" id="form_pr_avisos_anuales" name="form_pr_avisos_anuales">
<input type="hidden" name="vista_id_avisos_anuales" id="vista_id_avisos_anuales" />
<input type="hidden" name="sareta_avisos_anuales_pr_matricula" id="sareta_avisos_anuales_pr_matricula" />
<input type="hidden" name="sareta_avisos_anuales_pr_id_agencia" id="sareta_avisos_anuales_pr_id_agencia" />
<input type="hidden" name="sareta_avisos_anuales_pr_id_armador" id="sareta_avisos_anuales_pr_id_armador" />
<input type="hidden" name="sareta_avisos_anuales_pr_id_moneda" id="sareta_avisos_anuales_pr_id_moneda" />
<input type="hidden" name="sareta_avisos_anuales_valor_moneda" id="sareta_avisos_anuales_valor_moneda" />
<input type="hidden" name="sareta_avisos_anuales_pr_registro_bruto_valor_buque" id="sareta_avisos_anuales_pr_registro_bruto_valor_buque" />
<input type="hidden" name="sareta_avisos_anuales_pr_tarifa_valor" id="sareta_avisos_anuales_pr_tarifa_valor" />
<table width="672" class="cuerpo_formulario">
<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Aviso Anual</th>
	</tr>
<tr>
    	<th width="167">Buque:</th>
  			<td>
  			
						<input name="sareta_avisos_anuales_pr_buque" type="text" class="style4"
                        id="sareta_avisos_anuales_pr_buque" value="" size="60" maxlength="1000"  readonly
						message="Buscar una Buque." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Buque  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['buque: '+$(this).val()]}" />
        		
            </td>
    	</tr>    
    	<tr>                    
            <th>Call Sign:</th>
               <td>
                    <input name="sareta_avisos_anuales_pr_call_sign" type="text" id="sareta_avisos_anuales_pr_call_sign" 
                    size="15" maxlength="10" readonly="readonly"/>       
              </td>
	   </tr>
       <tr>
            <th>Tipo de Buque:</th>
              <td>
                  <table width="476" border="0">
                    <tr>
                      <td width="306"><input name="sareta_avisos_anuales_pr_clase" type="text" class="style4" id="sareta_avisos_anuales_pr_clase"  		value="" size="40" maxlength="60"  readonly/></td>
                      <td width="87"><strong>Año:</strong></td>
                      <td width="70">
                      <input  name="sareta_avisos_anuales_pr_ano" type="text" id="sareta_avisos_anuales_pr_ano"  size="8"  readonly="readonly"/>
                      </td>
                    </tr>
                  </table>
              </td>
      </tr>
	
      <tr>
            <th>Actividad:</th>
              <td>
                  <table width="476" border="0">
                    <tr>
                      <td width="305">
                      <input name="sareta_avisos_anuales_pr_actividad" type="text" class="style4" 
                      id="sareta_avisos_anuales_pr_actividad"  value="" size="40" maxlength="60"  readonly/>
                      </td>
                      <td width="87"><strong> F. Emisi&oacute;n:</strong></td>
                      <td width="70">
                      <input  name="sareta_avisos_anuales_pr_femision" type="text" 
                      id="sareta_avisos_anuales_pr_femision"  size="8"   readonly="readonly"/>
                      </td>
                    </tr>
                  </table>
              </td>
      </tr>
      <tr>
           <th>Bandera:		</th>	
	       <td>
           	 <table width="476" border="0">
                 <tr>
                     <td width="305">
                     <input name="sareta_avisos_anuales_pr_bandera" type="text" class="style4" 
                     id="sareta_avisos_anuales_pr_bandera"  value="" size="40" maxlength="60"  readonly />
                     </td>
                     <td width="87"><strong>R. Bruto:</strong></td>
                     <td width="70">
                     <input  name="sareta_avisos_anuales_pr_rb" type="text" id="sareta_avisos_anuales_pr_rb"  size="8"  readonly="readonly"/>
                     </td>
                 </tr>
             </table>
           </td>
	  </tr>
      <tr>
		<th>Fecha Recalada:</th>	
        <td>
            <input name="sareta_avisos_anuales_pr_fecha_recalada" type="text" id="sareta_avisos_anuales_pr_fecha_recalada" 
          size="15" maxlength="10" readonly="readonly"/>
        </td>
      </tr>
	  <tr>
    	 <th width="167">Armador:</th>
  		 <td width="493">
  			    <ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_anuales_pr_armador" type="text" class="style4" 
                    id="sareta_avisos_anuales_pr_armador"  size="41" maxlength="1000"  readonly
						message="Introduzca una Armador." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Armador  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Armador: '+$(this).val()]}" />
        			<li id="sareta_avisos_anuales_pr_btn_consultar_armador" class="btn_consulta_emergente"></li>
     			</ul>
         </td>
    </tr>  
  	<tr>
    	<th width="167">Agencia:</th>
  		<td width="493">
  				<ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_anuales_pr_agencia" type="text" class="style4" 
                    id="sareta_avisos_anuales_pr_agencia" value="" size="41" maxlength="1000"  readonly
						message="Introduzca una Agencia Naviera." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Agencia Naviera  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Agencia Naviera: '+$(this).val()]}" />
        			<li id="sareta_avisos_anuales_pr_btn_consultar_agencia" class="btn_consulta_emergente"></li>
     			</ul>
         </td>
    </tr>   
    <tr>
           <th>Moneda:</th>	
	       <td>
           <table width="476" border="0">
             <tr>
                 <td width="305">
                 <ul class="input_con_emergente">
             		<li>
           		      <input name="sareta_avisos_anuales_pr_moneda" type="text" class="style4" 
                    id="sareta_avisos_anuales_pr_moneda"  size="40" maxlength="1000"  readonly
						message="Introduzca una Moneda." 
						jval="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ]{1,60}$/, message:'Moneda Invalida', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ]/, cFunc:'alert', cArgs:['Moneda : '+$(this).val()]}" />
           		   <li id="sareta_avisos_anuales_pr_btn_consultar_moneda" class="btn_consulta_emergente"></li>
     			</ul>
   			  </td>
                 <td width="87"><strong>Cambio:</strong></td>
                 <td width="70"><input  name="sareta_avisos_anuales_pr_cambio" type="text" id="sareta_avisos_anuales_pr_cambio" value="0,00" size="8"  readonly="readonly"/>
                 </td>
             	</tr>
             </table>
             
    	</td>
	</tr>
     <tr>
    	<th>Tarifa:</th>
          <td>
                <table width="476" border="0">
                    <tr>
                      <td width="305"><input name="sareta_avisos_anuales_pr_tarifa" type="text" class="style4" id="sareta_avisos_anuales_pr_tarifa"  value="0,00" size="40" maxlength="60"  readonly/></td>
                      <td width="87"><strong> Monto $:</strong></td>
                      <td width="70"><input  name="sareta_avisos_anuales_pr_monto" type="text" id="sareta_avisos_anuales_pr_monto" value="0,00" size="8"   readonly="readonly"/></td>
                    </tr>
                </table>
          </td>
    </tr>
    <tr>
  
   	  
      <th>&nbsp;</th>
      <td width="0">
   	    	<table width="476">
                <tr>
                    <td width="305">&nbsp;</td>
                    <td width="87"><strong>Monto :</strong></td>
                    <td width="70">
                    <input  name="sareta_avisos_anuales_pr_montoTotal" type="text" id="sareta_avisos_anuales_pr_montoTotal" value="0,00" size="8"   readonly="readonly"/>
                    </td>
                </tr>
        	</table>
   	    </td>
    </tr>
	<tr>
		<th>Comentario:</th>			
      <td >
      <textarea name="sareta_avisos_anuales_pr_vista_observacion" cols="60" 
        id="sareta_avisos_anuales_pr_vista_observacion"  
        message="Introduzca una Observación. "></textarea>
      </td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>