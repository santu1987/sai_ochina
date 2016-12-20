<? if (!$_SESSION) session_start();
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------

//
//
//----------------------------------------------------------------


$("#sitio_fisico_rp_btn_imprimir").click(function() {
	desde = getObj('sitio_fisico_rp_fecha_desde').value; 
	dd = desde.substr(0,2);
	md = desde.substr(3,2);
	ad = desde.substr(6,4);
	hasta = getObj('sitio_fisico_rp_fecha_hasta').value;
	dh = hasta.substr(0,2);
	mh = hasta.substr(3,2);
	ah = hasta.substr(6,4);
	var err_fech = 0;
	if(getObj('sitio_fisico_rp_fecha_desde').value!='' || getObj('sitio_fisico_rp_fecha_hasta').value!=''){
		if(ah==ad){
			if(md==mh && dd>=dh){
				//alert("La fecha 'Hasta' tiene que ser mayor que la fecha 'Desde'");
				err_fech = 1
			}
			if(md>mh){
				err_fech = 1;
			}
		}
		if(ad>ah){
			err_fech = 1;
		}
		if(getObj('sitio_fisico_rp_fecha_desde').value=='' && getObj('sitio_fisico_rp_fecha_hasta').value!=''){
			err_fech = 1;
		}
	}
	if(getObj('sitio_fisico_rp_id_sitio_fisico').value!=''){
		if(err_fech!=0)
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha Hasta tiene que ser mayor que la fecha Desde</p></div>",true,true);
		if(err_fech==0){	
		url = "pdfb.php?p=modulos/bienes/sitiofisico/rp/vista.lst.sitio_fisico.php!id_sitio="+getObj('sitio_fisico_rp_id_sitio_fisico').value+"@fecha_desde="+getObj('sitio_fisico_rp_fecha_desde').value+"@fecha_hasta="+getObj('sitio_fisico_rp_fecha_hasta').value;
		openTab("ACTIVOS FIJOS POR SITIO FISICO",url);
		}
	}
	else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Tiene que Seleccionar un Sitio Fisico</p></div>",true,true);
});

//
//
$("#sitio_fisico_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/sitiofisico/rp/vista.grid_uni_eje_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#sitio_fisico_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/rp/sql_uni_eje_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#sitio_fisico_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#sitio_fisico_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/rp/sql_uni_eje_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/sitiofisico/rp/sql_uni_eje_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Unidad','Comentario'],
								colModel:[
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sitio_fisico_rp_id_unidad_ejecutora').value=ret.id_unidad_ejecutora;
									getObj('sitio_fisico_rp_nombre_unidad').value=ret.nombre;
									getObj('sitio_fisico_rp_id_sitio_fisico').value = '';
									getObj('sitio_fisico_rp_nombre_sitio_fisico').value = '';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#sitio_fisico_db_nombre").focus();
								$('#sitio_fisico_db_nombre').alpha({allow:' '});
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
//
//
$("#sitio_fisico_db_btn_consulta_emergente_sitio").click(function() {
	if(getObj('sitio_fisico_rp_id_unidad_ejecutora').value!=''){									 
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/sitiofisico/rp/vista.grid_sitio_fisico_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Sitio Fisico', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#sitio_fisico_db_nombre").val(); 
					var id_unidad = getObj('sitio_fisico_rp_id_unidad_ejecutora').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/rp/sql_sitio_fisico_nom.php?busq_nombre="+busq_nombre+"&id_unidad="+id_unidad,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#sitio_fisico_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#sitio_fisico_db_nombre").val();
							var id_unidad = getObj('sitio_fisico_rp_id_unidad_ejecutora').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/rp/sql_sitio_fisico_nom.php?busq_nombre="+busq_nombre+"&id_unidad="+id_unidad,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/sitiofisico/rp/sql_sitio_fisico_nom.php?id_unidad='+getObj('sitio_fisico_rp_id_unidad_ejecutora').value,
								datatype: "json",
								colNames:['ID','Sitio','Comentario'],
								colModel:[
									{name:'id_sitio_fisico',index:'id_sitio_fisico', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sitio_fisico_rp_id_sitio_fisico').value=ret.id_sitio_fisico;
									getObj('sitio_fisico_rp_nombre_sitio_fisico').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#sitio_fisico_db_nombre").focus();
								$('#sitio_fisico_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_sitio_fisico',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	}
	});

//
//
// ******************************************************************************
$("#sitio_fisico_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
//
function limpiar(){
	getObj('sitio_fisico_rp_id_sitio_fisico').value = '';
	getObj('sitio_fisico_rp_id_unidad_ejecutora').value = '';
	getObj('sitio_fisico_rp_nombre_unidad').value = '';
	getObj('sitio_fisico_rp_nombre_sitio_fisico').value = '';
	getObj('sitio_fisico_rp_fecha_desde').value='';
	getObj('sitio_fisico_rp_fecha_hasta').value='';
}
//
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#sitio_fisico_db_nombre_unidad').alpha({allow:' '});
$('#sitio_fisico_db_nombre_sitio').alpha({allow:' '});
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
//
</script>
<div id="botonera">
	<img id="sitio_fisico_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    
	<img id="sitio_fisico_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_rp_sitio_fisico" id="form_rp_sitio_fisico">
<input type="hidden" name="sitio_fisico_rp_id_sitio_fisico" id="sitio_fisico_rp_id_sitio_fisico" />
<input type="hidden" name="sitio_fisico_rp_id_unidad_ejecutora" id="sitio_fisico_rp_id_unidad_ejecutora"/>

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Sitio Fisico			</th>
	</tr>
    <tr>
			<th>Unidad</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="sitio_fisico_rp_nombre_unidad" type="text"  id="sitio_fisico_rp_nombre_unidad" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="sitio_fisico_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
    	<tr>
			<th>Sitio Fisico</th>
		  <td> <ul class="input_con_emergente">
				<li>
           <input name="sitio_fisico_rp_nombre_sitio_fisico" type="text"  id="sitio_fisico_rp_nombre_sitio_fisico" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="sitio_fisico_db_btn_consulta_emergente_sitio" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>