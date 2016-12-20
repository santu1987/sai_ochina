<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<? for($i=1;$i<=24;$i++)
			  {
					if($i>=1 && $i<=9)
					{
					  $hora.="<option value='0".$i.":00' >0".$i.":00</option>";
					  $hora.="<option value='0".$i.":30' >0".$i.":30</option>";
					}
					else if ($i==24){
					  $hora.="<option value='00:00' >00:00</option>";
					  $hora.="<option value='00:30' >00:30</option>";
					}
					else
					{
					  $hora.="<option value='".$i.":00' >".$i.":00</option>";
					  $hora.="<option value='".$i.":30' >".$i.":30</option>";
					}
				}
			  
 ?>
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

//---------------------------------------------------------consultar_Buque---------------------------------------

$("#sareta_avisos_recalada_pr_btn_consultar_Buque").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_buque.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Buques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_buque.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_buque.php?nd='+nd,
								datatype: "json",
								colNames:['id','Matricula','Call Sign','Nombre','nombre','id_bandera','Bandera','bandera',
								'R. Bruto','id_actividad','Actividad','actividad','id_clase','Clase','clase','Nac/Ext','Pago Anual',
								'id_ley','Ley','ley','Exonerado','com','ley_tarifa_buque','tarifa_buq','rbruto_buq'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'matricula',index:'matricula', width:220,sortable:false,resizable:false},
									{name:'call_sign',index:'call_sign', width:220,sortable:false,resizable:false},
							
									{name:'nombre1',index:'nombre1', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera1',index:'bandera1', width:220,sortable:false,resizable:false},
									{name:'bandera',index:'bandera', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'r_bruto',index:'r_bruto', width:220,sortable:false,resizable:false},
									
									{name:'id_actividad',index:'id_actividad', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad1',index:'actividad1', width:220,sortable:false,resizable:false},
									{name:'actividad',index:'actividad', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_clase',index:'id_clase', width:220,sortable:false,resizable:false,hidden:true},
									{name:'clase1',index:'clase1', width:220,sortable:false,resizable:false},
									{name:'clase',index:'clase', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'nac',index:'nac', width:220,sortable:false,resizable:false},
									{name:'pago_anual',index:'pago_anual', width:220,sortable:false,resizable:false},
									
									{name:'id_ley',index:'id_ley', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley1',index:'ley1', width:220,sortable:false,resizable:false},
									{name:'ley',index:'ley', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'exonerado',index:'exonerado', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley_tarifa_buque',index:'ley_tarifa_buque', width:220,sortable:false,resizable:false,hidden:true},	
								{name:'tarifa_buque',index:'tarifa_buque', width:220,sortable:false,resizable:false,hidden:true},
								{name:'rbruto_buque',index:'rbruto_buque', width:220,sortable:false,resizable:false,hidden:true}
								
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj('avisos_recalada_id_buque').value = ret.id;
										getObj('avisos_recalada_id_ley_buque').value = ret.id_ley;
										getObj('avisos_recalada_id_bandera_buque').value = ret.id_bandera;
										getObj('avisos_recalada_id_clase_buque').value = ret.id_clase;
										getObj('avisos_recalada_id_actividad_buque').value = ret.id_actividad;
										getObj('avisos_recalada_tarifa_buque').value = ret.ley_tarifa_buque;
										getObj('sareta_avisos_recalada_pr_buque').value = ret.nombre;
										getObj('sareta_avisos_recalada_pr_matricula').value = ret.matricula;
										getObj('sareta_avisos_recalada_pr_call_sign').value = ret.call_sign;
										getObj('sareta_avisos_recalada_pr_rb').value = ret.r_bruto;
										getObj('sareta_avisos_recalada_pr_clase').value = ret.clase;
										getObj('sareta_avisos_recalada_pr_actividad').value = ret.actividad;
										getObj('sareta_avisos_recalada_pr_bandera').value = ret.bandera;
										getObj('sareta_avisos_recalada_pr_tarifa').value = ret.ley_tarifa_buque;
									
								//estos campos se usan para pasar el valor numerico para realisar el carculo de monto recalada del buque
									getObj('tarifa_buq').value=ret.tarifa_buque;
									getObj('arqueo_bruto_buq').value=ret.rbruto_buque;
									
									
									//calculo de buque
									
									var monto =getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value ; 
									getObj('sareta_avisos_recalada_pr_monto').value =monto.currency(2,',','.');
									//calculo de remorcador
									var arq_bruto_rem= getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value;
									getObj('sareta_avisos_recalada_pr_monto_remolque').value =arq_bruto_rem.currency(2,',','.');
									//calculo  total 
									var monto_total_rec=(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)+(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value);
									getObj('sareta_avisos_recalada_pr_montoTotal_rec').value =monto_total_rec.currency(2,',','.');
									//calculo total buq $
										
					var monto_total_buq=
									(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_montoTotal').value =monto_total_buq.currency(2,',','.');
									//calculo total rem $
										
					var monto_total_rem=
									(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value =monto_total_rem.currency(2,',','.');	
									
								dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
									
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
	});


//-----------------------------------------------------consultar_armador-------------------------------------------

$("#sareta_avisos_recalada_pr_btn_consultar_armador").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_armador.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Armadores', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_armador.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_armador.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_armador.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_armador.php?nd='+nd,
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
									getObj('avisos_recalada_id_armador').value = ret.id;
									getObj('sareta_avisos_recalada_pr_armador').value = ret.nombre;
									
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
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
	});
//---------------------------------------------------consultar_bandera_org--------------------------------------------
	
$("#sareta_avisos_recalada_pr_btn_consultar_bandera_org").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_bandera_org.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Banderas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_bandera_org.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_bandera_org.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_bandera_org.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_bandera_org.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								var paso_id_bandera_org=getObj('avisos_recalada_id_bandera_org').value;
									getObj('avisos_recalada_id_bandera_org').value = ret.id;
									getObj('sareta_avisos_recalada_pr_bandera_org').value = ret.nombre;
								if(paso_id_bandera_org != getObj('avisos_recalada_id_bandera_org').value){
									getObj('avisos_recalada_id_puerto_org').value = "";
									getObj('sareta_avisos_recalada_pr_puerto_org').value = "";}
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:' '});
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

//-----------------------------------------------------consultar_puerto_org------------------------------------------
$("#sareta_avisos_recalada_pr_btn_consultar_puerto_org").click(function() {
 if(getObj('avisos_recalada_id_bandera_org').value!==''){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	var id_bandera= getObj('avisos_recalada_id_bandera_org').value;
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_puerto_org.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd+"&id_bandera="+id_bandera,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Puertos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre_puerto").val();
					var bandera= jQuery("#bandera_org").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_puerto_org.php?nombre="+busq_nombre+"&id_bandera="+bandera,page:1}).trigger("reloadGrid"); 
					url="modulos/sareta/avisos_recalada/pr/sql_grid_puerto_org.php?nombre="+busq_nombre+"&id_bandera="+bandera;
					//alert(url);
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre_puerto").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre_puerto").val();
							var bandera= jQuery("#bandera_org").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_puerto_org.php?nombre="+busq_nombre+"&id_bandera="+bandera,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_puerto_org.php?nombre="+busq_nombre+"&id_bandera="+bandera;
							//alert(url);
						}
			}

		});
	
						function crear_grid()
						{
					var bandera_paso= getObj('avisos_recalada_id_bandera_org').value ;
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_puerto_org.php?nd='+nd+'&id_bandera='+bandera_paso,
								datatype: "json",
								colNames:['ID','Nombre','id_bandera','Bandera','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre_bandera',index:'nombre_bandera', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('avisos_recalada_id_puerto_org').value = ret.id;
									getObj('sareta_avisos_recalada_pr_puerto_org').value = ret.nombre;
									
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre_puerto").focus();
								$('#parametro_cxp_pr_nombre_puerto').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
							});
						}
				}//fin de validar si la bandera origen se encuentra vacia
				else
				{
				alert('El Campo Bandera de Origen se Encuentra Vacio');
				}
});


//---------------------------------------------consultar_puerto_rec--------------------------------------------------
$("#sareta_avisos_recalada_pr_btn_consultar_puerto_rec").click(function() {

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_puerto_rec.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Puertos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre_puerto").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_puerto_rec.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
					url="modulos/sareta/avisos_recalada/pr/sql_grid_puerto_rec.php?nombre="+busq_nombre;
					//alert(url);
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre_puerto").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre_puerto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_puerto_rec.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_puerto_rec.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_puerto_rec.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','id_bandera','Bandera','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre_bandera',index:'nombre_bandera', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('avisos_recalada_id_puerto_rec').value = ret.id;
									getObj('sareta_avisos_recalada_pr_puerto_rec').value = ret.nombre;
									
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre_puerto").focus();
								$('#parametro_cxp_pr_nombre_puerto').alpha({allow:' '});
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
//-----------------------------------------------------consultar_puerto_det------------------------------------------

$("#sareta_avisos_recalada_pr_btn_consultar_puerto_det").click(function() {
 if(getObj('avisos_recalada_id_bandera_det').value!==''){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	var id_bandera= getObj('avisos_recalada_id_bandera_det').value;
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_puerto_det.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd+"&id_bandera="+id_bandera,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Puertos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre_puerto").val();
					var bandera= jQuery("#bandera_det").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_puerto_det.php?nombre="+busq_nombre+"&id_bandera="+bandera,page:1}).trigger("reloadGrid"); 
					url="modulos/sareta/avisos_recalada/pr/sql_grid_puerto_det.php?nombre="+busq_nombre+"&id_bandera="+bandera;
					//alert(url);
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_nombre_puerto").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre_puerto").val();
							var bandera= jQuery("#bandera_det").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_puerto_det.php?nombre="+busq_nombre+"&id_bandera="+bandera,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_puerto_det.php?nombre="+busq_nombre+"&id_bandera="+bandera;
							//alert(url);
						}
			}

		});
	
						function crear_grid()
						{
					var bandera_paso= getObj('avisos_recalada_id_bandera_det').value ;
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_puerto_det.php?nd='+nd+'&id_bandera='+bandera_paso,
								datatype: "json",
								colNames:['ID','Nombre','id_bandera','Bandera','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre_bandera',index:'nombre_bandera', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('avisos_recalada_id_puerto_det').value = ret.id;
									getObj('sareta_avisos_recalada_pr_puerto_det').value = ret.nombre;
									
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre_puerto").focus();
								$('#parametro_cxp_pr_nombre_puerto').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
							});
						}
				}//fin de validar si la bandera origen se encuentra vacia
				else
				{
				alert('El Campo Bandera de Destino se Encuentra Vacio');
				}
});

//-------------------------------------------------------consultar_bandera_det----------------------------------------

	
$("#sareta_avisos_recalada_pr_btn_consultar_bandera_det").click(function() {
		var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_bandera_det.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Banderas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_bandera_det.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_bandera_det.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_bandera_det.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_bandera_det.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								var paso_id_bandera_det=getObj('avisos_recalada_id_bandera_det').value;
									getObj('avisos_recalada_id_bandera_det').value = ret.id;
									getObj('sareta_avisos_recalada_pr_bandera_det').value = ret.nombre;
								if(paso_id_bandera_det != getObj('avisos_recalada_id_bandera_det').value){
									getObj('avisos_recalada_id_puerto_det').value = "";
									getObj('sareta_avisos_recalada_pr_puerto_det').value = "";}
									
									
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_pr_nombre").focus();
								$('#parametro_cxp_pr_nombre').alpha({allow:' '});
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
//--------------------------------------------------------consultar_agencia------------------------------------------------------------
$("#sareta_avisos_recalada_pr_btn_consultar_agencia").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_agencia_naviera.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Agencias Navieras', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_agencia_naviera.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_agencia_naviera.php?nd='+nd,
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
									getObj('avisos_recalada_id_agencia_naviera').value = ret.id_agencia_naviera;
									getObj('sareta_avisos_recalada_pr_agencia').value = ret.nom;
							
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
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
	
//------------------------------------------------------consultar_moneda----------------------------------------------------------------------------
$("#sareta_avisos_recalada_pr_btn_consultar_moneda").click(function() {
if(getObj('avisos_recalada_id_buque').value!==''){		
		var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_cambio_moneda.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Moneda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_cambio_moneda.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_cambio_moneda.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_cambio_moneda.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_cambio_moneda.php?nd='+nd,
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
									getObj('avisos_recalada_id_cambio_moneda').value = ret.id;
									getObj('sareta_avisos_recalada_pr_moneda').value = ret.moneda;
									getObj('sareta_avisos_recalada_pr_cambio').value = ret.valor;
									getObj('avisos_recalada_valor_moneda').value = ret.valor_moneda;
									
								
									
									//calculo de buque
									
									var monto =getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value ; 
									getObj('sareta_avisos_recalada_pr_monto').value =monto.currency(2,',','.');
									//calculo de remorcador
									var arq_bruto_rem= getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value;
									getObj('sareta_avisos_recalada_pr_monto_remolque').value =arq_bruto_rem.currency(2,',','.');
									//calculo  total 
									var monto_total_rec=(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)+(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value);
									getObj('sareta_avisos_recalada_pr_montoTotal_rec').value =monto_total_rec.currency(2,',','.');
									//calculo total buq $
										
					var monto_total_buq=
									(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_montoTotal').value =monto_total_buq.currency(2,',','.');
									//calculo total rem $
										
					var monto_total_rem=
									(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value =monto_total_rem.currency(2,',','.');	
									
									dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
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
}//fin de validar si el buque se encuentra vacio
else
{
alert('ES NECESARIO QUE LOS DATOS DEL BUQUE SE ENCUENTRE LLENOS ');
}
});
//---------------------------------------------------------consultar_remolcador---------------------------------------------------------------------


$("#sareta_avisos_recalada_pr_btn_consultar_remolcador").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_remolcador.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Remolcador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_remolcador.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_remolcador.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_remolcador.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_remolcador.php?nd='+nd,
								datatype: "json",
								colNames:['id','Matricula','Call Sign','Nombre','nombre','id_bandera','Bandera','bandera',
								'R. Bruto','id_actividad','Actividad','actividad','id_clase','Clase','clase','Nac/Ext','Pago Anual',
								'id_ley','Ley','ley','Exonerado','com','ley_tarifa_buque','tarifa_rem','rbruto_rem'],
								colModel:[
									{name:'id_remolcador',index:'id_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'matricula_remolcador',index:'matricula_remolcador', width:220,sortable:false,resizable:false},
									{name:'call_sign_remolcador',index:'call_sign_remolcador', width:220,sortable:false,resizable:false},
							
									{name:'nombre1_remolcador',index:'nombre1_remolcador', width:220,sortable:false,resizable:false},
									{name:'nombre_remolcador',index:'nombre_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_bandera_remolcador',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera1_remolcador',index:'bandera1_remolcador', width:220,sortable:false,resizable:false},
									{name:'bandera_remolcador',index:'bandera_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'r_bruto_remolcador',index:'r_bruto_remolcador', width:220,sortable:false,resizable:false},
									
									{name:'id_actividad_remolcador',index:'id_actividad_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad1_remolcador',index:'actividad1_remolcador', width:220,sortable:false,resizable:false},
									{name:'actividad_remolcador',index:'actividad_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_clase_remolcador',index:'id_clase_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'clase1_remolcador',index:'clase1_remolcador', width:220,sortable:false,resizable:false},
									{name:'clase_remolcador',index:'clase_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'nac_remolcador',index:'nac_remolcador', width:220,sortable:false,resizable:false},
									{name:'pago_anual_remolcador',index:'pago_anual_remolcador', width:220,sortable:false,resizable:false},
									

									{name:'id_ley_remolcador',index:'id_ley_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley1_remolcador',index:'ley1_remolcador', width:220,sortable:false,resizable:false},
									{name:'ley_remolcador',index:'ley_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'exonerado_remolcador',index:'exonerado_remolcador', width:220,sortable:false,resizable:false},
									{name:'com_remolcador',index:'com_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley_tarifa_buque_remolcador',index:'ley_tarifa_buque_remolcador', width:220,sortable:false,resizable:false,hidden:true},
								
								{name:'tarifa_remolcador',index:'tarifa_remolcador', width:220,sortable:false,resizable:false,hidden:true},
								{name:'rbruto_remolcador',index:'rbruto_remolcador', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj('avisos_recalada_id_remolcador').value = ret.id_remolcador;
										getObj('sareta_avisos_recalada_pr_remolcador').value = ret.nombre_remolcador;
										getObj('avisos_recalada_id_bandera_remolcador').value = ret.id_bandera_remolcador;
										getObj('avisos_recalada_id_ley_remolcador').value = ret.id_ley_remolcador;
										getObj('avisos_recalada_id_clase_remolcador').value = ret.id_clase_remolcador;
										getObj('avisos_recalada_id_actividad_remolcador').value = ret.id_actividad_remolcador;
										getObj('sareta_avisos_recalada_pr_matricula_remolcador').value = ret.matricula_remolcador;
										getObj('sareta_avisos_recalada_pr_call_sign_remolcador').value = ret.call_sign_remolcador;
										
										getObj('sareta_avisos_recalada_pr_bandera_remolcador').value = ret.bandera_remolcador;
										getObj('sareta_avisos_recalada_pr_Rbruto_remolcador').value = ret.r_bruto_remolcador;
										getObj('sareta_avisos_recalada_pr_tarifa_remolcador').value = ret.ley_tarifa_buque_remolcador;
										
										//paso de valores para carculo de forma numerica
										getObj('tarifa_rem').value=ret.tarifa_remolcador;
										getObj('arqueo_bruto_rem').value=ret.rbruto_remolcador;

									//calculo de buque
									
									var monto =getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value ; 
									getObj('sareta_avisos_recalada_pr_monto').value =monto.currency(2,',','.');
									//calculo de remorcador
									var arq_bruto_rem= getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value;
									getObj('sareta_avisos_recalada_pr_monto_remolque').value =arq_bruto_rem.currency(2,',','.');
									//calculo  total 
									var monto_total_rec=(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)+(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value);
									getObj('sareta_avisos_recalada_pr_montoTotal_rec').value =monto_total_rec.currency(2,',','.');
									//calculo total buq $
										
					var monto_total_buq=
									(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_montoTotal').value =monto_total_buq.currency(2,',','.');
									//calculo total rem $
										
					var monto_total_rem=
									(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value =monto_total_rem.currency(2,',','.');				
									
									
								dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
									
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
	});

//---------------------------------------------------------consultar por  aviso_de_recalada---------------------------------------

$("#sareta_avisos_recalada_pr_btn_consultar").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/avisos_recalada/pr/grid_aviso_recalada.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Avisos De Recalada', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val(); 
					var busq_documento= jQuery("#parametro_cxp_pr_documento").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_aviso_recalada.php?nombre="+busq_nombre+"&documento="+busq_documento,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_pr_documento").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
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
							timeoutHnd = setTimeout(programa_cxp_gridReload,50)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_pr_nombre").val();
							var busq_documento= jQuery("#parametro_cxp_pr_documento").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/avisos_recalada/pr/sql_grid_aviso_recalada.php?nombre="+busq_nombre+"&documento="+busq_documento,page:1}).trigger("reloadGrid");
							url="modulos/sareta/avisos_recalada/pr/sql_grid_aviso_recalada.php?nombre="+busq_nombre+"&documento="+busq_documento;
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
								url:'modulos/sareta/avisos_recalada/pr/sql_grid_aviso_recalada.php?nd='+nd,
								datatype: "json",
								colNames:['N&deg; Documento','id','id_buque','Matricula','Call Sign','Buque','nom_buque','R. Bruto','r_bruto',
								'id_ley_buque','tarifa_buque','tarifa_buque2','id_bandera_buq','Bandera','bandera_buq',
								'id_clase_buq','clase_buq','id_actividad_buq','actividad_buq','obs','id_armador','armador',
								'id_agencia','agencia','id_moneda_cambio','nombre_moneda','moneda_cambio','moneda_cambio2',
								'id_bandera_org','bandera_org','id_puerto_org','puerto_org','id_puerto_rec','puerto_recs',
								'id_bandera_det','bandera_det','id_puerto_det','puerto_det','id_remolcador','Remolcador',
								'nombre_remolcador','id_bandera_remolcador','bandera_remolcador','id_ley_remolcador',
								'id_clase_remolcador','id_actividad_remolcador','matricula_remolcador','call_sign_remolcador',
								'tarifa_rem','tarifa_rem2','arqueo_bruto_rem','arqueo_bruto_rem','F. Recalada','fecha_rec',
								'hora_rec','F. Zarpe','fecha_zap','hora_zap','Monto de Rec'],
								colModel:[
									{name:'numero_documento',index:'numero_documento', width:190,sortable:false,resizable:false},
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true,hidden:true},
									{name:'id_buque',index:'id_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'matricula',index:'matricula', width:220,sortable:false,resizable:false,hidden:true},
									{name:'call_sign',index:'call_sign', width:220,sortable:false,resizable:false},
									{name:'nombre1',index:'nombre1', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false,hidden:true},
									{name:'r_bruto',index:'r_bruto', width:220,sortable:false,resizable:false,hidden:true},
									{name:'rbruto_buque',index:'rbruto_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_ley_buque',index:'id_ley_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tarifa_buque',index:'tarifa_buque', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tarifa_buque2',index:'tarifa_buque2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_bandera_buq',index:'id_bandera_buq', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_buq',index:'bandera_buq', width:220,sortable:false,resizable:false},
									{name:'bandera_buq2',index:'bandera_buq2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_clase_buq',index:'id_clase_buq', width:220,sortable:false,resizable:false,hidden:true},
									{name:'clase_buq',index:'clase_buq', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_actividad_buq',index:'id_actividad_buq', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad_buq',index:'actividad_buq', width:220,sortable:false,resizable:false,hidden:true},
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_armador',index:'id_armador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'armador',index:'armador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_agencia_naviera',index:'id_agencia_naviera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'agencia_naviera',index:'agencia_naviera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_moneda_cambio',index:'id_moneda_cambio', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre_moneda',index:'nombre_moneda', width:220,sortable:false,resizable:false,hidden:true},
									{name:'moneda_cambio',index:'moneda_cambio', width:220,sortable:false,resizable:false,hidden:true},
									{name:'moneda_cambio2',index:'moneda_cambio2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_bandera_org',index:'id_bandera_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_org',index:'bandera_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_puerto_org',index:'id_puerto_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'puerto_org',index:'puerto_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_puerto_rec',index:'id_puerto_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'puerto_rec',index:'puerto_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_bandera_det',index:'id_bandera_det', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_det',index:'bandera_det', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_puerto_det',index:'id_puerto_det', width:220,sortable:false,resizable:false,hidden:true},
									{name:'puerto_det',index:'puerto_det', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_remolcador',index:'id_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'remolcador',index:'remolcador', width:200,sortable:false,resizable:false},
									{name:'nombre_remolcador',index:'nombre_remolcador', width:200,sortable:false,resizable:false,hidden:true},
									{name:'id_bandera_remolcador',index:'id_bandera_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_remolcador',index:'bandera_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_ley_remolcador',index:'id_ley_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_clase_remolcador',index:'id_clase_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_actividad_remolcador',index:'id_actividad_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'matricula_remolcador',index:'matricula_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'call_sign_remolcador',index:'call_sign_remolcador', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tarifa_rem',index:'tarifa_rem', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tarifa_rem2',index:'tarifa_rem2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'arqueo_bruto_rem',index:'arqueo_bruto_rem', width:220,sortable:false,resizable:false,hidden:true},
									{name:'arqueo_bruto_rem2',index:'arqueo_bruto_rem2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'fecha_rec',index:'fecha_rec', width:200,sortable:false,resizable:false},
									{name:'fecha_rec2',index:'fecha_rec2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'hora_rec',index:'hora_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'fecha_zap',index:'fecha_zap', width:200,sortable:false,resizable:false},
									{name:'fecha_zap2',index:'fecha_zap2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'hora_zap',index:'hora_zap', width:220,sortable:false,resizable:false,hidden:true},
									{name:'monto',index:'monto', width:190,sortable:false,resizable:false}	
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//cargando los datos del aviso de recalada ya registrado
								getObj('vista_id_avisos_recalada').value = ret.id;
								getObj('avisos_recalada_id_buque').value = ret.id_buque;
								getObj('sareta_avisos_recalada_pr_matricula').value = ret.matricula;
								getObj('sareta_avisos_recalada_pr_call_sign').value = ret.call_sign;
								getObj('sareta_avisos_recalada_pr_buque').value = ret.nombre;
								getObj('sareta_avisos_recalada_pr_rb').value = ret.r_bruto;
								getObj('arqueo_bruto_buq').value = ret.rbruto_buque;		
								getObj('avisos_recalada_id_ley_buque').value = ret.id_ley_buque;
							
								getObj('sareta_avisos_recalada_pr_tarifa').value = ret.tarifa_buque; 
								getObj('tarifa_buq').value = ret.tarifa_buque2;
								
								getObj('avisos_recalada_id_bandera_buque').value = ret.id_bandera_buq;
								getObj('sareta_avisos_recalada_pr_bandera').value = ret.bandera_buq2;
								
								getObj('avisos_recalada_id_clase_buque').value = ret.id_clase_buq;
								getObj('sareta_avisos_recalada_pr_clase').value = ret.clase_buq;
								
								getObj('avisos_recalada_id_actividad_buque').value = ret.id_actividad_buq;
								getObj('sareta_avisos_recalada_pr_actividad').value = ret.actividad_buq;
								
								getObj('sareta_avisos_recalada_pr_vista_observacion').value = ret.obs;
								
								getObj('avisos_recalada_id_armador').value = ret.id_armador;
								getObj('sareta_avisos_recalada_pr_armador').value = ret.armador;
								
								getObj('avisos_recalada_id_agencia_naviera').value = ret.id_agencia_naviera;
								getObj('sareta_avisos_recalada_pr_agencia').value = ret.agencia_naviera;
								
								getObj('avisos_recalada_id_cambio_moneda').value = ret.id_moneda_cambio;
								getObj('sareta_avisos_recalada_pr_moneda').value = ret.nombre_moneda;
								getObj('sareta_avisos_recalada_pr_cambio').value = ret.moneda_cambio;
								getObj('avisos_recalada_valor_moneda').value = ret.moneda_cambio2;
								
								getObj('avisos_recalada_id_bandera_org').value = ret.id_bandera_org;
								getObj('sareta_avisos_recalada_pr_bandera_org').value = ret.bandera_org;
								
								getObj('avisos_recalada_id_puerto_org').value = ret.id_puerto_org;
								getObj('sareta_avisos_recalada_pr_puerto_org').value = ret.puerto_org;
								
								getObj('avisos_recalada_id_puerto_rec').value = ret.id_puerto_rec;
								getObj('sareta_avisos_recalada_pr_puerto_rec').value = ret.puerto_rec;
								
								
								getObj('avisos_recalada_id_bandera_det').value = ret.id_bandera_det;
								getObj('sareta_avisos_recalada_pr_bandera_det').value = ret.bandera_det;
								
								getObj('avisos_recalada_id_puerto_det').value = ret.id_puerto_det;
								getObj('sareta_avisos_recalada_pr_puerto_det').value = ret.puerto_det;
								//datos del remolcador abrir pestaña y cargar
								if(ret.id_remolcador=='' || ret.id_remolcador==0){
								document.form_pr_avisos_recalada.remolcador.checked=false ;
								getObj('remolcador1').style.display='none'; 
								getObj('remolcador2').style.display='none'; 
								getObj('remolcador3').style.display='none';
								getObj('remolcador4').style.display='none';
								getObj('remolcador5').style.display='none';
								}else{
								document.form_pr_avisos_recalada.remolcador.checked=true ;
								getObj('remolcador1').style.display=''; 
								getObj('remolcador2').style.display=''; 
								getObj('remolcador3').style.display='';
								getObj('remolcador4').style.display='';
								getObj('remolcador5').style.display='';
								
								}
								
								getObj('avisos_recalada_id_remolcador').value = ret.id_remolcador;
								getObj('sareta_avisos_recalada_pr_remolcador').value = ret.nombre_remolcador;
								
								getObj('avisos_recalada_id_bandera_remolcador').value = ret.id_bandera_remolcador;
								getObj('sareta_avisos_recalada_pr_bandera_remolcador').value = ret.bandera_remolcador;
								
								
								getObj('avisos_recalada_id_ley_remolcador').value = ret.id_ley_remolcador;
								getObj('avisos_recalada_id_clase_remolcador').value = ret.id_clase_remolcador;
								getObj('avisos_recalada_id_actividad_remolcador').value = ret.id_actividad_remolcador;
								
								getObj('sareta_avisos_recalada_pr_matricula_remolcador').value = ret.matricula_remolcador;
								getObj('sareta_avisos_recalada_pr_call_sign_remolcador').value = ret.call_sign_remolcador;
								
								getObj('sareta_avisos_recalada_pr_tarifa_remolcador').value = ret.tarifa_rem;
								getObj('tarifa_rem').value = ret.tarifa_rem2;
								
								getObj('sareta_avisos_recalada_pr_Rbruto_remolcador').value = ret.arqueo_bruto_rem;
								getObj('arqueo_bruto_rem').value = ret.arqueo_bruto_rem2;
								
								getObj('avisos_recalada_pr_fecha_recalada').value = ret.fecha_rec2;
								getObj('avisos_recalada_pr_fecha_zarpe').value = ret.fecha_zap2;

								getObj('sareta_ley_pr_vista_hora_rec').value = ret.hora_rec;
								getObj('sareta_ley_pr_vista_hora_zap').value = ret.hora_zap;
									//calculo de buque
									
									var monto =getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value ; 
									getObj('sareta_avisos_recalada_pr_monto').value =monto.currency(2,',','.');
									//calculo de remorcador
									var arq_bruto_rem= getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value;
									getObj('sareta_avisos_recalada_pr_monto_remolque').value =arq_bruto_rem.currency(2,',','.');
									//calculo  total 
									var monto_total_rec=(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)+(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value);
									getObj('sareta_avisos_recalada_pr_montoTotal_rec').value =monto_total_rec.currency(2,',','.');
									//calculo total buq $
										
					var monto_total_buq=
									(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_montoTotal').value =monto_total_buq.currency(2,',','.');
									//calculo total rem $
										
					var monto_total_rem=
									(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value =monto_total_rem.currency(2,',','.');	
									getObj('sareta_avisos_recalada_pr_btn_cancelar').style.display='';
									getObj('sareta_avisos_recalada_pr_btn_actualizar').style.display='';
									getObj('sareta_avisos_recalada_pr_btn_eliminar').style.display='';
									getObj('sareta_avisos_recalada_pr_btn_guardar').style.display='none';
								dialog.hideAndUnload();
									$('#form_pr_avisos_recalada').jVal();
									
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								
								$('#parametro_cxp_pr_nombre').alpha({allow:'0123456789- _'});
								
								$('#parametro_cxp_pr_documento').numeric({allow:'0123456789'});
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


//------------------------------------------ver_remolcador------------------------------------------------------


function ver_remolcador()
{
	
	if(document.form_pr_avisos_recalada.remolcador.checked==true)
		{
			getObj('remolcador1').style.display=''; 
			getObj('remolcador2').style.display=''; 
			getObj('remolcador3').style.display='';
			getObj('remolcador4').style.display='';
			getObj('remolcador5').style.display='';
			
								
					
		}
		else if(document.form_pr_avisos_recalada.remolcador.checked==false)
		{
			getObj('remolcador1').style.display='none'; 
			getObj('remolcador2').style.display='none'; 
			getObj('remolcador3').style.display='none';
			getObj('remolcador4').style.display='none';
			getObj('remolcador5').style.display='none';
			getObj('avisos_recalada_id_remolcador').value = 0;
			getObj('sareta_avisos_recalada_pr_remolcador').value = '';
			getObj('avisos_recalada_id_bandera_remolcador').value = 0;
			getObj('sareta_avisos_recalada_pr_bandera_remolcador').value = '';
			getObj('avisos_recalada_id_ley_remolcador').value = 0;
			getObj('avisos_recalada_id_clase_remolcador').value = 0;
			getObj('avisos_recalada_id_actividad_remolcador').value = 0;
			getObj('sareta_avisos_recalada_pr_matricula_remolcador').value = ' ';
			getObj('sareta_avisos_recalada_pr_call_sign_remolcador').value = ' ';
			getObj('sareta_avisos_recalada_pr_tarifa_remolcador').value ='0,00';
			getObj('tarifa_rem').value = 0;
			getObj('sareta_avisos_recalada_pr_Rbruto_remolcador').value ='0,00';
			getObj('arqueo_bruto_rem').value = 0;
			//calculo de buque
									
									var monto =getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value ; 
									getObj('sareta_avisos_recalada_pr_monto').value =monto.currency(2,',','.');
									//calculo de remorcador
									var arq_bruto_rem= getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value;
									getObj('sareta_avisos_recalada_pr_monto_remolque').value =arq_bruto_rem.currency(2,',','.');
									//calculo  total 
									var monto_total_rec=(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)+(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value);
									getObj('sareta_avisos_recalada_pr_montoTotal_rec').value =monto_total_rec.currency(2,',','.');
									//calculo total buq $
										
					var monto_total_buq=
									(getObj('arqueo_bruto_buq').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_buq').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_montoTotal').value =monto_total_buq.currency(2,',','.');
									//calculo total rem $
										
					var monto_total_rem=
									(getObj('arqueo_bruto_rem').value*getObj('avisos_recalada_valor_moneda').value*getObj('tarifa_rem').value)/(getObj('avisos_recalada_valor_moneda').value);
									getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value =monto_total_rem.currency(2,',','.');	
			

		}
}
//-----------------------------------------------btn_guardar----------------------------------------------

$("#sareta_avisos_recalada_pr_btn_guardar").click(function() {
	if($('#form_pr_avisos_recalada').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/avisos_recalada/pr/sql.registrar.php",
			data:dataForm('form_pr_avisos_recalada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_pr_avisos_recalada');
						setBarraEstado("");
	getObj('sareta_avisos_recalada_pr_btn_cancelar').style.display='';
	getObj('sareta_avisos_recalada_pr_btn_eliminar').style.display='none';
	getObj('sareta_avisos_recalada_pr_btn_actualizar').style.display='none';
	getObj('sareta_avisos_recalada_pr_btn_guardar').style.display='';
	clearForm('form_pr_avisos_recalada');
	document.form_pr_avisos_recalada.remolcador.checked=false ;
	getObj('remolcador1').style.display='none'; 
	getObj('remolcador2').style.display='none'; 
	getObj('remolcador3').style.display='none';
	getObj('remolcador4').style.display='none';
	getObj('remolcador5').style.display='none';
	
	getObj('avisos_recalada_pr_fecha_recalada').value="<?= date ('d/m/Y') ?>";
	getObj('avisos_recalada_pr_fecha_zarpe').value="<?= date ('d/m/Y') ?>";
	
	getObj('sareta_avisos_recalada_pr_montoTotal_rec').value="0,00";
	getObj('sareta_avisos_recalada_pr_montoTotal').value="0,00";
	getObj('sareta_avisos_recalada_pr_monto').value="0,00";
	getObj('sareta_avisos_recalada_pr_cambio').value="0,00";
	getObj('sareta_avisos_recalada_pr_tarifa').value="0,00";
	getObj('sareta_avisos_recalada_pr_Rbruto_remolcador').value="0,00";
	getObj('sareta_avisos_recalada_pr_tarifa_remolcador').value="0,00";
	
	
	getObj('sareta_avisos_recalada_pr_monto_remolque').value="0,00";
	getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value="0,00";
	getObj('sareta_avisos_recalada_pr_rb').value="0,00";
	getObj('sareta_ley_pr_vista_hora_rec').selectedIndex =0;
	getObj('sareta_ley_pr_vista_hora_zap').selectedIndex =0;
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
//-----------------------------Codigo para Atualizar 

$("#sareta_avisos_recalada_pr_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_agencia_naviera').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/avisos_recalada/pr/sql.actualizar.php",
			data:dataForm('form_pr_avisos_recalada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_avisos_recalada_pr_btn_cancelar').style.display='';
	getObj('sareta_avisos_recalada_pr_btn_eliminar').style.display='none';
	getObj('sareta_avisos_recalada_pr_btn_actualizar').style.display='none';
	getObj('sareta_avisos_recalada_pr_btn_guardar').style.display='';
	clearForm('form_pr_avisos_recalada');
	document.form_pr_avisos_recalada.remolcador.checked=false ;
	getObj('remolcador1').style.display='none'; 
	getObj('remolcador2').style.display='none'; 
	getObj('remolcador3').style.display='none';
	getObj('remolcador4').style.display='none';
	getObj('remolcador5').style.display='none';
	
	getObj('avisos_recalada_pr_fecha_recalada').value="<?= date ('d/m/Y') ?>";
	getObj('avisos_recalada_pr_fecha_zarpe').value="<?= date ('d/m/Y') ?>";
	
	getObj('sareta_avisos_recalada_pr_montoTotal_rec').value="0,00";
	getObj('sareta_avisos_recalada_pr_montoTotal').value="0,00";
	getObj('sareta_avisos_recalada_pr_monto').value="0,00";
	getObj('sareta_avisos_recalada_pr_cambio').value="0,00";
	getObj('sareta_avisos_recalada_pr_tarifa').value="0,00";
	getObj('sareta_avisos_recalada_pr_Rbruto_remolcador').value="0,00";
	getObj('sareta_avisos_recalada_pr_tarifa_remolcador').value="0,00";
	
	
	getObj('sareta_avisos_recalada_pr_monto_remolque').value="0,00";
	getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value="0,00";
	getObj('sareta_avisos_recalada_pr_rb').value="0,00";
	getObj('sareta_ley_pr_vista_hora_rec').selectedIndex =0;
	getObj('sareta_ley_pr_vista_hora_zap').selectedIndex =0;
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

$("#sareta_avisos_recalada_pr_btn_eliminar").click(function() {
  if (getObj('vista_id_avisos_recalada').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/avisos_recalada/pr/sql.eliminar.php",
			data:dataForm('form_pr_avisos_recalada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_avisos_recalada_pr_btn_cancelar').style.display='';
					getObj('sareta_avisos_recalada_pr_btn_eliminar').style.display='none';
					getObj('sareta_avisos_recalada_pr_btn_actualizar').style.display='none';
					getObj('sareta_avisos_recalada_pr_btn_guardar').style.display='';
					clearForm('form_pr_avisos_recalada');
					document.form_pr_avisos_recalada.remolcador.checked=false ;
					getObj('remolcador1').style.display='none'; 
					getObj('remolcador2').style.display='none'; 
					getObj('remolcador3').style.display='none';
					getObj('remolcador4').style.display='none';
					getObj('remolcador5').style.display='none';
					
					getObj('avisos_recalada_pr_fecha_recalada').value="<?= date ('d/m/Y') ?>";
					getObj('avisos_recalada_pr_fecha_zarpe').value="<?= date ('d/m/Y') ?>";
					
					getObj('sareta_avisos_recalada_pr_montoTotal_rec').value="0,00";
					getObj('sareta_avisos_recalada_pr_montoTotal').value="0,00";
					getObj('sareta_avisos_recalada_pr_monto').value="0,00";
					getObj('sareta_avisos_recalada_pr_cambio').value="0,00";
					getObj('sareta_avisos_recalada_pr_tarifa').value="0,00";
					getObj('sareta_avisos_recalada_pr_Rbruto_remolcador').value="0,00";
					getObj('sareta_avisos_recalada_pr_tarifa_remolcador').value="0,00";
					
					
					getObj('sareta_avisos_recalada_pr_monto_remolque').value="0,00";
					getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value="0,00";
					getObj('sareta_avisos_recalada_pr_rb').value="0,00";
					getObj('sareta_ley_pr_vista_hora_rec').selectedIndex =0;
					getObj('sareta_ley_pr_vista_hora_zap').selectedIndex =0;
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

//-------------------------------------------------btn_cancelar--------------------------------------------
$("#sareta_avisos_recalada_pr_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_avisos_recalada_pr_btn_cancelar').style.display='';
	getObj('sareta_avisos_recalada_pr_btn_eliminar').style.display='none';
	getObj('sareta_avisos_recalada_pr_btn_actualizar').style.display='none';
	getObj('sareta_avisos_recalada_pr_btn_guardar').style.display='';
	clearForm('form_pr_avisos_recalada');
	document.form_pr_avisos_recalada.remolcador.checked=false ;
	getObj('remolcador1').style.display='none'; 
	getObj('remolcador2').style.display='none'; 
	getObj('remolcador3').style.display='none';
	getObj('remolcador4').style.display='none';
	getObj('remolcador5').style.display='none';
	
	getObj('avisos_recalada_pr_fecha_recalada').value="<?= date ('d/m/Y') ?>";
	getObj('avisos_recalada_pr_fecha_zarpe').value="<?= date ('d/m/Y') ?>";
	
	getObj('sareta_avisos_recalada_pr_montoTotal_rec').value="0,00";
	getObj('sareta_avisos_recalada_pr_montoTotal').value="0,00";
	getObj('sareta_avisos_recalada_pr_monto').value="0,00";
	getObj('sareta_avisos_recalada_pr_cambio').value="0,00";
	getObj('sareta_avisos_recalada_pr_tarifa').value="0,00";
	getObj('sareta_avisos_recalada_pr_Rbruto_remolcador').value="0,00";
	getObj('sareta_avisos_recalada_pr_tarifa_remolcador').value="0,00";
	
	
	getObj('sareta_avisos_recalada_pr_monto_remolque').value="0,00";
	getObj('sareta_avisos_recalada_pr_monto_dolar_remolque').value="0,00";
	getObj('sareta_avisos_recalada_pr_rb').value="0,00";
	getObj('sareta_ley_pr_vista_hora_rec').selectedIndex =0;
	getObj('sareta_ley_pr_vista_hora_zap').selectedIndex =0;
	
});
//---------------------------------------------------------------------------------------------
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});

//----------------------------------------------------------------------------------------------
	$('#sareta_avisos_recalada_pr_matricula').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});
	$('#sareta_avisos_recalada_pr_call_sign').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñ'});
	$('#sareta_avisos_recalada_pr_nombre').alpha({allow:'0123456789- áéíóúÁÉÍÓÚñ'});

</script>
<style type="text/css">
<!--
.style4 {color: #33CCFF}
-->
</style>



<div id="botonera">
	<img id="sareta_avisos_recalada_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_avisos_recalada_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_avisos_recalada_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    <img id="sareta_avisos_recalada_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_avisos_recalada_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
</div>

<form method="post" id="form_pr_avisos_recalada" name="form_pr_avisos_recalada">
<input type="hidden" name="vista_id_avisos_recalada" id="vista_id_avisos_recalada" />

<input type="hidden" name="avisos_recalada_id_buque" 			id="avisos_recalada_id_buque" />
<input type="hidden" name="avisos_recalada_id_ley_buque" 		id="avisos_recalada_id_ley_buque" />
<input type="hidden" name="avisos_recalada_id_bandera_buque" 	id="avisos_recalada_id_bandera_buque" />
<input type="hidden" name="avisos_recalada_id_clase_buque" 		id="avisos_recalada_id_clase_buque" />
<input type="hidden" name="avisos_recalada_id_actividad_buque"  id="avisos_recalada_id_actividad_buque" />
<input type="hidden" name="avisos_recalada_tarifa_buque" 		id="avisos_recalada_tarifa_buque" />
<input type="hidden" name="sareta_avisos_recalada_pr_matricula" id="sareta_avisos_recalada_pr_matricula"  />
<input type="hidden" name="avisos_recalada_id_armador" 			id="avisos_recalada_id_armador" />
<input type="hidden" name="avisos_recalada_id_bandera_org" 			id="avisos_recalada_id_bandera_org" />
<input type="hidden" name="avisos_recalada_id_puerto_org" 			id="avisos_recalada_id_puerto_org" />
<input type="hidden" name="avisos_recalada_id_puerto_rec" 			id="avisos_recalada_id_puerto_rec" />
<input type="hidden" name="avisos_recalada_id_bandera_det" 			id="avisos_recalada_id_bandera_det" />
<input type="hidden" name="avisos_recalada_id_puerto_det" 			id="avisos_recalada_id_puerto_det" />
<input type="hidden" name="avisos_recalada_id_agencia_naviera" 		id="avisos_recalada_id_agencia_naviera" />
<input type="hidden" name="avisos_recalada_id_cambio_moneda" 		id="avisos_recalada_id_cambio_moneda" />

<!-- Comentario los campos siguientes es usan para los datos del remolcador -->
<input type="hidden" name="avisos_recalada_id_remolcador" 		   	id="avisos_recalada_id_remolcador" />
<input type="hidden" name="avisos_recalada_id_bandera_remolcador" 	id="avisos_recalada_id_bandera_remolcador" />
<input type="hidden" name="avisos_recalada_id_ley_remolcador" 		id="avisos_recalada_id_ley_remolcador" />
<input type="hidden" name="avisos_recalada_id_clase_remolcador" 		id="avisos_recalada_id_clase_remolcador" />
<input type="hidden" name="avisos_recalada_id_actividad_remolcador"  id="avisos_recalada_id_actividad_remolcador" />
<input type="hidden" name="sareta_avisos_recalada_pr_matricula_remolcador" id="sareta_avisos_recalada_pr_matricula_remolcador"  />
<input type="hidden" name="sareta_avisos_recalada_pr_call_sign_remolcador" id="sareta_avisos_recalada_pr_call_sign_remolcador"  />



<!-- Comentario los campos siguientes es para el valor de la moneda que se usara -->
<input type="hidden" name="avisos_recalada_valor_moneda" 			id="avisos_recalada_valor_moneda" />


<!-- Comentario los campos siguientes son para el recalada de remolcador -->
<input type="hidden" name="tarifa_rem" 			id="tarifa_rem" />
<input type="hidden" name="arqueo_bruto_rem" 	id="arqueo_bruto_rem" />

<!-- Comentario los campos siguientes son para el recalada de Buque -->
<input type="hidden" name="tarifa_buq" 			id="tarifa_buq" />
<input type="hidden" name="arqueo_bruto_buq" 	id="arqueo_bruto_buq" />


<!-- estatus para verivicar-->
<input type="hidden" name="avisos_recalada_estatus" 	id="avisos_recalada_estatus" value="0" />

<table width="672" class="cuerpo_formulario">
<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Aviso de Recalada</th>
	</tr>
	
<tr>
    	<th width="167">Buque:</th>
  			<td>
  			<ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_buque" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_buque"  size="36" maxlength="60"  readonly
						message="Introduzca una  Buque." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ-]{1,60}$/, message:'Buque Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9  áéíóúÁÉÍÓÚñÑ-]/, cFunc:'alert', cArgs:['Buque : '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_Buque" class="btn_consulta_emergente"></li>
     			</ul>
            </td>
    	</tr>    
    	<tr>                    
            <th>Call Sign:</th>
               <td>
                    <input name="sareta_avisos_recalada_pr_call_sign" type="text"
                     id="sareta_avisos_recalada_pr_call_sign" 
                    size="15" maxlength="10" readonly="readonly"/>       
              </td>
	   </tr>
       <tr>
            <th>Tipo de Buque:</th>
              <td><input name="sareta_avisos_recalada_pr_clase" type="text" class="style4" 
              id="sareta_avisos_recalada_pr_clase"  		value="" size="36" maxlength="60"  readonly/></td>
      </tr>
	
      <tr>
            <th>Actividad:</th>
              <td><input name="sareta_avisos_recalada_pr_actividad" type="text" class="style4" 
                   id="sareta_avisos_recalada_pr_actividad"  value="" size="36" maxlength="60"  readonly/></td>
      </tr>
      <tr>
           <th>Bandera:		</th>	
	       <td>
           	 <table width="476" border="0">
                 <tr>
                     <td width="305">
                     <input name="sareta_avisos_recalada_pr_bandera" type="text" class="style4" 
                     id="sareta_avisos_recalada_pr_bandera"  value="" size="35" maxlength="60"  readonly />
                     </td>
                     <td width="87"><strong>R. Bruto:</strong></td>
                     <td width="70">
                     <input  name="sareta_avisos_recalada_pr_rb" type="text" 
                     id="sareta_avisos_recalada_pr_rb"  size="8" value="0"alt="signed-decimal" readonly="readonly"/>
                     </td>
                 </tr>
             </table>
           </td>
	  </tr>
      <tr>
    	 <th width="167">Armador:</th>
  		 <td width="493">
  			    <ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_armador" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_armador"  size="36" maxlength="1000"  readonly
						message="Introduzca una Armador." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Armador  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Armador: '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_armador" class="btn_consulta_emergente"></li>
     			</ul>
         </td>
    </tr>  
     <tr>
            <th>Selecionar Remolcador:</th>
              <td>
                <input name="remolcador" type="checkbox" id="remolcador" onchange="ver_remolcador();"  />
              </td>
    </tr>
     <tr id="remolcador1" style="display:none;">
    	<th width="167">Remolcador:</th>
  			<td>
  			<ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_remolcador" type="text" 
                    id="sareta_avisos_recalada_pr_remolcador"  size="36" maxlength="60"  readonly/>
        			<li id="sareta_avisos_recalada_pr_btn_consultar_remolcador" class="btn_consulta_emergente"></li>
     			</ul>
            </td>
    	</tr>    
		
      <tr id="remolcador2" style="display:none;">
            <th>&nbsp;</th>
          <td><table width="476">
                <tr>
                    <td width="65"><strong>Bandera:</strong></td>
                    <td width="399"><input name="sareta_avisos_recalada_pr_bandera_remolcador" type="text" class="style4" 
                   id="sareta_avisos_recalada_pr_bandera_remolcador"  value="" size="22" maxlength="22"  readonly/></td>
                </tr>
        	</table></td>
      </tr>
        <tr id="remolcador3" style="display:none;">
            <th>&nbsp;</th>
          <td><table width="476">
                <tr>
                    <td width="65"><strong>R. Bruto:</strong></td><td width="182"><input name="sareta_avisos_recalada_pr_Rbruto_remolcador" type="text" 
                   id="sareta_avisos_recalada_pr_Rbruto_remolcador" alt="signed-decimal"  value="0" size="22" maxlength="22"  readonly/></td>
                    <td width="152"><strong>Tarifa:</strong></td>
                  <td width="57">
                    <input  name="sareta_avisos_recalada_pr_tarifa_remolcador" type="text" id="sareta_avisos_recalada_pr_tarifa_remolcador"  size="8" value="0" alt="signed-decimal"  readonly="readonly"/>
                  </td>
                </tr>
        	</table></td>
      </tr>
      
  <tr id="remolcador4" style="display:none;">
  <th>&nbsp;</th>
   	  
  <td width="0">
   	    	<table width="476">
                <tr>
                    <td width="256">&nbsp;</td>
                    <td width="133"><strong>Monto Remolque :</strong></td>
                  <td width="71">
                    <input  name="sareta_avisos_recalada_pr_monto_remolque" type="text" id="sareta_avisos_recalada_pr_monto_remolque"  size="8" value="0" alt="signed-decimal"   readonly="readonly"/>
                    </td>
                </tr>
        	</table>
    </tr>
    
    <tr id="remolcador5" style="display:none;">
  <th>&nbsp;</th>
   	  
  <td width="0">
   	    	<table width="476">
                <tr>
                    <td width="256">&nbsp;</td>
                    <td width="133"><strong>Monto $ Remolque :</strong></td>
                  <td width="71">
                    <input  name="sareta_avisos_recalada_pr_monto_dolar_remolque" type="text" id="sareta_avisos_recalada_pr_monto_dolar_remolque"  size="8"  value="0" alt="signed-decimal"  readonly="readonly"/>
                    </td>
                </tr>
        	</table>
    </tr>
    <tr>
           <th>Puerto Origen:</th>	
      <td>
           <table width="476" border="0">
             <tr>
             	 <td width="87"><strong>Bandera:</strong></td>
                 <td width="305">
                 <ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_bandera_org" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_bandera_org"  size="22" maxlength="60"  readonly
						message="Introduzca una Bandera." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Bandera Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Bandera : '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_bandera_org" class="btn_consulta_emergente"></li>
     			</ul>
      			</td>
                 <td width="87"><strong>Puerto:</strong></td>
                 <td width="300"> <ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_puerto_org" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_puerto_org"  size="22" maxlength="60"  readonly
						message="Introduzca un Puerto." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Puerto Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Puerto : '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_puerto_org" class="btn_consulta_emergente"></li>
     			</ul>
                 </td>
           	 </tr>
             </table>
          
        </td>
	</tr>
    <tr>
           <th>Puerto Recalada:</th>	
      <td>
           <table width="477" border="0">
             <tr>
             	 <td width="62"><strong>Bandera:</strong></td>
                 <td width="173">
                 VENEZUELA
                 </td>
                 <td width="49"><strong>Puerto:</strong></td>
                 <td width="175"> <ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_puerto_rec" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_puerto_rec"  size="22" maxlength="60"  readonly
						message="Introduzca una Puerto." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Puerto Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Puerto : '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_puerto_rec" class="btn_consulta_emergente"></li>
     			</ul>
                 </td>
           	 </tr>
             </table>
          
        </td>
	</tr>
    <tr>
           <th>Puerto Destino:</th>	
      <td>
           <table width="476" border="0">
             <tr>
             	 <td width="87"><strong>Bandera:</strong></td>
                 <td width="305">
                 <ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_bandera_det" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_bandera_det"  size="22" maxlength="60"  readonly
						message="Introduzca una Bandera." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Bandera Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Bandera : '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_bandera_det" class="btn_consulta_emergente"></li>
     			</ul>
      			</td>
                 <td width="87"><strong>Puerto:</strong></td>
                 <td width="300"> <ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_puerto_det" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_puerto_det"  size="22" maxlength="60"  readonly
						message="Introduzca un Puerto." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Puerto Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Puerto : '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_puerto_det" class="btn_consulta_emergente"></li>
     			</ul>
                 </td>
           	 </tr>
             </table>
          
        </td>
	</tr>
    <tr>
           <th>Fecha Recalada:</th>	
	       <td>
           <table width="392" border="0">
             <tr>
                 <td width="148">
                 <input name="avisos_recalada_pr_fecha_recalada" type="text" id="avisos_recalada_pr_fecha_recalada" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca la Fecha para el Dia de Recalada">
		  <button type="reset" id="avisos_recalada_pr_fecha_recalada_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "avisos_recalada_pr_fecha_recalada",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "avisos_recalada_pr_fecha_recalada_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
      			</td>
                 <td width="116"><strong>Hora Recalada:</strong></td>
                 <td width="114">
                 <select name="sareta_ley_pr_vista_hora_rec" id="sareta_ley_pr_vista_hora_rec">
                <?=$hora ?>
                 </select>
                 </td>
           	 </tr>
             </table>
             
   	  </td>
	</tr>
    <tr>
           <th>Fecha Zarpe:</th>	
	       <td>
           <table width="392" border="0">
             <tr>
                 <td width="148">
                 <input name="avisos_recalada_pr_fecha_zarpe" type="text" id="avisos_recalada_pr_fecha_zarpe" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca la Fecha para el Dia de Zarpe">
		  <button type="reset" id="avisos_recalada_pr_fecha_zarpe_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "avisos_recalada_pr_fecha_zarpe",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "avisos_recalada_pr_fecha_zarpe_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
      			</td>
                 <td width="116"><strong>Hora Zarpe:</strong></td>
                 <td width="114">
                 <select name="sareta_ley_pr_vista_hora_zap" id="sareta_ley_pr_vista_hora_zap">
                <?=$hora ?>
                 </select>
                 </td>
           	 </tr>
             </table>
             
   	  </td>
	</tr>
    
  	<tr>
    	<th width="167">Agencia:</th>
  		<td width="493">
  				<ul class="input_con_emergente">
             		<li>
	
					<input name="sareta_avisos_recalada_pr_agencia" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_agencia" value="" size="36" maxlength="1000"  readonly
						message="Introduzca una Agencia Naviera." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Agencia Naviera  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Agencia Naviera: '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_agencia" class="btn_consulta_emergente"></li>
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
	
					<input name="sareta_avisos_recalada_pr_moneda" type="text" class="style4" 
                    id="sareta_avisos_recalada_pr_moneda"  size="35" maxlength="1000"  readonly
						message="Introduzca una Moneda." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Moneda Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Moneda : '+$(this).val()]}" />
        			<li id="sareta_avisos_recalada_pr_btn_consultar_moneda" class="btn_consulta_emergente"></li>
     			</ul>
      			</td>
                 <td width="87"><strong>Cambio:</strong></td>
                 <td width="70"><input  name="sareta_avisos_recalada_pr_cambio" type="text" id="sareta_avisos_recalada_pr_cambio"  size="8" value="0" alt="signed-decimal" readonly="readonly"/>
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
                      <td width="305"><input name="sareta_avisos_recalada_pr_tarifa" type="text" id="sareta_avisos_recalada_pr_tarifa"  value="0"alt="signed-decimal"  size="40" maxlength="60"  readonly/></td>
                      <td width="87"><strong> Monto:</strong></td>
                      <td width="70"><input  name="sareta_avisos_recalada_pr_monto" type="text" id="sareta_avisos_recalada_pr_monto"  size="8" value="0" alt="signed-decimal"  readonly="readonly"/></td>
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
                    <td width="87"><strong>Monto $ :</strong></td>
                  <td width="70">
                    <input  name="sareta_avisos_recalada_pr_montoTotal" type="text" value="0" id="sareta_avisos_recalada_pr_montoTotal"  size="8" alt="signed-decimal"   readonly="readonly"/>
                    </td>
                </tr>
        	</table>
    </tr>
	
    <tr>
  
   	  <th>&nbsp;</th>
      <td width="0">
   	    	<table width="476">
                <tr>
                    <td width="305">&nbsp;</td>
                    <td width="87"><strong>Total Recalada :</strong></td>
                    <td width="70">
                    <input  name="sareta_avisos_recalada_pr_montoTotal_rec" value="0" type="text" id="sareta_avisos_recalada_pr_montoTotal_rec"  size="8" alt="signed-decimal"    readonly="readonly"/>
                    </td>
                </tr>
        	</table>
    </tr>
	
    <tr>
		<th>Comentario:</th>			
      <td >
      <textarea name="sareta_avisos_recalada_pr_vista_observacion" cols="60" 
        id="sareta_avisos_recalada_pr_vista_observacion"  
        message="Introduzca una Observación. "></textarea>
      </td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>