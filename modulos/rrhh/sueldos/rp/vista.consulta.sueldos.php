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

//
$("#aumento_sueldos_rp_btn_imprimir").click(function() {
	desde = getObj('aumento_sueldos_rp_fecha_desde').value; 
	dd = desde.substr(0,2);
	md = desde.substr(3,2);
	ad = desde.substr(6,4);
	hasta = getObj('aumento_sueldos_rp_fecha_hasta').value;
	dh = hasta.substr(0,2);
	mh = hasta.substr(3,2);
	ah = hasta.substr(6,4);
	var err_fech = 0;
	if(getObj('aumento_sueldos_rp_fecha_desde').value!='' || getObj('aumento_sueldos_rp_fecha_hasta').value!=''){
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
		if(getObj('aumento_sueldos_rp_fecha_desde').value=='' && getObj('aumento_sueldos_rp_fecha_hasta').value!=''){
			err_fech = 1;
		}
	}
	if(getObj('aumento_sueldos_rp_id_unidad_ejecutora').value!='' || getObj('aumento_sueldos_rp_id_unidad_ejecutora').value==''){
		if(err_fech!=0)
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha Hasta tiene que ser mayor que la fecha Desde</p></div>",true,true);
		if(err_fech==0){	
		url = "pdfb.php?p=modulos/rrhh/sueldos/rp/vista.lst.sueldos.php!id_unidad="+getObj('aumento_sueldos_rp_id_unidad_ejecutora').value+"@fecha_desde="+getObj('aumento_sueldos_rp_fecha_desde').value+"@fecha_hasta="+getObj('aumento_sueldos_rp_fecha_hasta').value+"@id_trabajador="+getObj('aumento_sueldos_rp_id_trabajador').value;
		//alert(url);
		openTab("SUELDO DE LOS TRABAJADORES",url);
		}
	}
	
});

//
//
$("#aumento_sueldos_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/sueldos/rp/vista.grid_uni_eje_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#aumento_sueldos_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/sueldos/rp/sql_uni_eje_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#aumento_sueldos_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#aumento_sueldos_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/sueldos/rp/sql_uni_eje_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/sueldos/rp/sql_uni_eje_nom.php?nd='+nd,
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
									getObj('aumento_sueldos_rp_id_unidad_ejecutora').value=ret.id_unidad_ejecutora;
									getObj('aumento_sueldos_rp_nombre_unidad').value=ret.nombre;
									getObj('aumento_sueldos_rp_id_trabajador').value = '';
									getObj('aumento_sueldos_rp_cedula_trabajador').value = '';
									getObj('aumento_sueldos_rp_nombre_trabajador').value = '';
									getObj('aumento_sueldos_rp_apellido_trabajador').value = '';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#aumento_sueldos_db_nombre").focus();
								$('#aumento_sueldos_db_nombre').alpha({allow:' '});
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
$("#aumento_sueldos_db_btn_consulta_emergente_trabajador").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/sueldos/rp/vista.grid_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#aumento_sueldos_db_nombre").val(); 
					var busq_apellido= jQuery("#aumento_sueldos_db_apellido").val();
					var busq_unidad = getObj('aumento_sueldos_rp_id_unidad_ejecutora').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/sueldos/rp/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido+"&busq_unidad="+busq_unidad,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#aumento_sueldos_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#aumento_sueldos_db_apellido").keypress(function(key)
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
							var busq_nombre= jQuery("#aumento_sueldos_db_nombre").val();
							var busq_apellido= jQuery("#aumento_sueldos_db_apellido").val();
							var busq_unidad = getObj('aumento_sueldos_rp_id_unidad_ejecutora').value;					
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/sueldos/rp/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido+"&busq_unidad="+busq_unidad,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/sueldos/rp/sql_trabajador_nom.php?busq_unidad='+getObj("aumento_sueldos_rp_id_unidad_ejecutora").value,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Apellido'],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aumento_sueldos_rp_id_trabajador').value=ret.id_trabajador;
									getObj('aumento_sueldos_rp_cedula_trabajador').value=ret.cedula;
									getObj('aumento_sueldos_rp_nombre_trabajador').value=ret.nombre;
									getObj('aumento_sueldos_rp_apellido_trabajador').value=ret.apellido;							
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#aumento_sueldos_db_nombre").focus();
								$('#aumento_sueldos_db_nombre').alpha({allow:' '});
								$('#aumento_sueldos_db_apellido').alpha({allow:' '});
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
// ******************************************************************************
$("#aumento_sueldos_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
//
$("#aumento_sueldos_rp_cedula_trabajador").change(function() {
	$.ajax({
			url:"modulos/rrhh/sueldos/rp/sql_consulta_automatica_trabajador.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_rp_aumento_sueldos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
				if(html!=''){
					arreglo = html.split('*');
					getObj('aumento_sueldos_rp_id_trabajador').value = arreglo[0];
					getObj('aumento_sueldos_rp_cedula_trabajador').value = arreglo[1];
					getObj('aumento_sueldos_rp_nombre_trabajador').value = arreglo[2];
					getObj('aumento_sueldos_rp_apellido_trabajador').value = arreglo[3];
				}
				else if(html==''){
					getObj('aumento_sueldos_rp_id_trabajador').value = '';
					getObj('aumento_sueldos_rp_cedula_trabajador').value = '';
					getObj('aumento_sueldos_rp_nombre_trabajador').value = '';
					getObj('aumento_sueldos_rp_apellido_trabajador').value = '';
				}
			 }
		});

});
//
function limpiar(){
	getObj('aumento_sueldos_rp_id_trabajador').value = '';
	getObj('aumento_sueldos_rp_id_unidad_ejecutora').value = '';
	getObj('aumento_sueldos_rp_nombre_unidad').value = '';
	getObj('aumento_sueldos_rp_cedula_trabajador').value = '';
	getObj('aumento_sueldos_rp_nombre_trabajador').value='';
	getObj('aumento_sueldos_rp_apellido_trabajador').value='';
	getObj('aumento_sueldos_rp_fecha_desde').value='';
	getObj('aumento_sueldos_rp_fecha_hasta').value='';
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
	<img id="aumento_sueldos_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    
	<img id="aumento_sueldos_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_rp_aumento_sueldos" id="form_rp_aumento_sueldos">
<input type="hidden" name="aumento_sueldos_rp_id_trabajador" id="aumento_sueldos_rp_id_trabajador" />
<input type="hidden" name="aumento_sueldos_rp_id_unidad_ejecutora" id="aumento_sueldos_rp_id_unidad_ejecutora"/>

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Sueldos			</th>
	</tr>
    <tr>
			<th>Unidad</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="aumento_sueldos_rp_nombre_unidad" type="text"  id="aumento_sueldos_rp_nombre_unidad" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="aumento_sueldos_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
    	<tr>
			<th>Trabajador</th>
		  <td> <ul class="input_con_emergente">
				<li>
           <input name="aumento_sueldos_rp_cedula_trabajador" type="text" id="aumento_sueldos_rp_cedula_trabajador" maxlength="60" size="30"
           jval="{valid:/^[0-9 V-E-]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9 V-E-]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="aumento_sueldos_db_btn_consulta_emergente_trabajador" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
			<th>Nombre</th>
		  <td><label>
		    <input readonly="true" type="text" name="aumento_sueldos_rp_nombre_trabajador" id="aumento_sueldos_rp_nombre_trabajador" message="Nombre del Trabajador"/>
	      </label></td>
		</tr>
        <tr>
			<th>Apellido</th>
		  <td><label>
		    <input readonly="true" type="text" name="aumento_sueldos_rp_apellido_trabajador" id="aumento_sueldos_rp_apellido_trabajador" message="Apellido del Trabajador"/>
	      </label></td>
		</tr>
        <tr style="display:none">
			<th>Fechas</th>
		  <td>Desde: 
          <input readonly="true" type="text" name="aumento_sueldos_rp_fecha_desde" id="aumento_sueldos_rp_fecha_desde" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_desde_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "aumento_sueldos_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_desde_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
Hasta: 
<input readonly="true" type="text" name="aumento_sueldos_rp_fecha_hasta" id="aumento_sueldos_rp_fecha_hasta" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_hasta_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "aumento_sueldos_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_hasta_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>

          </td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>