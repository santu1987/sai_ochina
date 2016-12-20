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
$("#conceptos_trabajador_rp_cedula_trabajador").change(function() {
	$.ajax({
			url:"modulos/rrhh/conceptos/rp/sql_consulta_automatica_trabajador.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_rp_conceptos_trabajador'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
				if(html!=''){
					arreglo = html.split("*");
					getObj('conceptos_trabajador_rp_id_trabajador').value = arreglo[0];
					getObj('conceptos_trabajador_rp_cedula_trabajador').value = arreglo[1];
					getObj('conceptos_trabajador_rp_nombre_trabajador').value = arreglo[2];
					getObj('conceptos_trabajador_rp_apellido_trabajador').value = arreglo[3];
				}
				else if(html==''){
					getObj('conceptos_trabajador_rp_id_trabajador').value = '';
					getObj('conceptos_trabajador_rp_cedula_trabajador').value = '';
					getObj('conceptos_trabajador_rp_nombre_trabajador').value = '';
					getObj('conceptos_trabajador_rp_apellido_trabajador').value = ''; 
				}
			 }
		});
});

//
//
$("#conceptos_trabajador_rp_btn_imprimir").click(function() {
	
		url = "pdfb.php?p=modulos/rrhh/conceptos/rp/vista.lst.conceptos_trabajador.php!id_trabajador="+getObj('conceptos_trabajador_rp_id_trabajador').value;
		openTab("CONCEPTOS POR TRABAJADOR",url);
	
});

//
//
$("#conceptos_trabajador_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/conceptos/rp/vista.grid_conceptos_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_cedula= jQuery("#conceptos_trabajador_rp_cedula").val(); 
					var busq_nombre= jQuery("#conceptos_trabajador_rp_nombre").val(); 
					var busq_apellido= jQuery("#conceptos_trabajador_rp_apellido").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/conceptos/rp/sql_conceptos_trabajador_nom.php?busq_cedula="+busq_cedula+"&busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#conceptos_trabajador_rp_cedula").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#conceptos_trabajador_rp_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#conceptos_trabajador_rp_apellido").keypress(function(key)
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
							var busq_cedula= jQuery("#conceptos_trabajador_rp_cedula").val();
							var busq_nombre= jQuery("#conceptos_trabajador_rp_nombre").val();
							var busq_apellido= jQuery("#conceptos_trabajador_rp_apellido").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/conceptos/rp/sql_conceptos_trabajador_nom.php?busq_cedula="+busq_cedula+"&busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:550,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/conceptos/rp/sql_conceptos_trabajador_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Apellido','Unidad','Cargo'],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:100,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:100,sortable:false,resizable:false},
									{name:'cargo',index:'cargo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('conceptos_trabajador_rp_id_trabajador').value=ret.id_trabajador;
									getObj('conceptos_trabajador_rp_cedula_trabajador').value=ret.cedula;
									getObj('conceptos_trabajador_rp_nombre_trabajador').value = ret.nombre;
									getObj('conceptos_trabajador_rp_apellido_trabajador').value = ret.apellido;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#conceptos_trabajador_rp_cedula").focus();
								$('#conceptos_trabajador_rp_cedula').numeric({allow:'V-E-'});
								$('#conceptos_trabajador_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_trabajador',
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
$("#conceptos_trabajador_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
//
function limpiar(){
	getObj('conceptos_trabajador_rp_id_trabajador').value = '';
	getObj('conceptos_trabajador_rp_cedula_trabajador').value = '';
	getObj('conceptos_trabajador_rp_nombre_trabajador').value = '';
	getObj('conceptos_trabajador_rp_apellido_trabajador').value = '';
	getObj('conceptos_trabajador_rp_fecha_desde').value='';
	getObj('conceptos_trabajador_rp_fecha_hasta').value='';
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
$('#conceptos_trabajador_rp_id_trabajador').alpha({allow:' '});
$('#conceptos_trabajador_rp_cedula_trabajador').numeric({allow:'V-E-'});
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
	<img id="conceptos_trabajador_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    
	<img id="conceptos_trabajador_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_rp_conceptos_trabajador" id="form_rp_conceptos_trabajador">
<input type="hidden" name="conceptos_trabajador_rp_id_trabajador" id="conceptos_trabajador_rp_id_trabajador" />

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Conceptos por Trabajador			</th>
	</tr>
    <tr>
			<th>Trabajador</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="conceptos_trabajador_rp_cedula_trabajador" type="text"  id="conceptos_trabajador_rp_cedula_trabajador" maxlength="60" size="30"
           jval="{valid:/^[0-9 V-E-]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9 V-E-]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="conceptos_trabajador_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
			<th>Nombre</th>
		  <td>         <label>
		    <input type="text" name="conceptos_trabajador_rp_nombre_trabajador" id="conceptos_trabajador_rp_nombre_trabajador" />
	      </label></td>
		</tr>
        <tr>
			<th>Apellido</th>
		  <td><label>
		    <input type="text" name="conceptos_trabajador_rp_apellido_trabajador" id="conceptos_trabajador_rp_apellido_trabajador" />
		    </label>         
		  </td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>