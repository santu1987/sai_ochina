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


$("#nomina_rp_btn_imprimir").click(function() {										
	if(getObj('nomina_rp_id_tipo_nomina').value!='' && getObj('nomina_rp_id_nominas').value!=''){										
	desde = getObj('nomina_rp_fecha_desde').value; 
	dd = desde.substr(0,2);
	md = desde.substr(3,2);
	ad = desde.substr(6,4);
	hasta = getObj('nomina_rp_fecha_hasta').value;
	dh = hasta.substr(0,2);
	mh = hasta.substr(3,2);
	ah = hasta.substr(6,4);
	
	var err_fech = 0;
	if(getObj('nomina_rp_fecha_desde').value!='' || getObj('nomina_rp_fecha_hasta').value!=''){
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
		if(getObj('nomina_rp_fecha_desde').value=='' && getObj('nomina_rp_fecha_hasta').value!=''){
			err_fech = 1;
		}
	}
	if(getObj('nomina_rp_id_tipo_nomina').value!='' || getObj('nomina_rp_id_tipo_nomina').value==''){
		if(err_fech!=0)
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha Hasta tiene que ser mayor que la fecha Desde</p></div>",true,true);
		if(err_fech==0){	
		url = "pdfb.php?p=modulos/rrhh/nomina/rp/vista.lst.nomina2.php!id_tipo_nomina="+getObj('nomina_rp_id_tipo_nomina').value+"@id_nominas="+getObj('nomina_rp_id_nominas').value+"@fecha_desde="+getObj('nomina_rp_fecha_desde').value+"@fecha_hasta="+getObj('nomina_rp_fecha_hasta').value+"@frecuencia="+getObj('nomina_rp_frecuencia').value+"@numero="+getObj('nomina_rp_nominas').value;
		if(getObj('nomina_rp_id_trabajador').value!='')
		url = "pdfb.php?p=modulos/rrhh/nomina/rp/vista.lst.nomina.php!id_tipo_nomina="+getObj('nomina_rp_id_tipo_nomina').value+"@id_nominas="+getObj('nomina_rp_id_nominas').value+"@fecha_desde="+getObj('nomina_rp_fecha_desde').value+"@fecha_hasta="+getObj('nomina_rp_fecha_hasta').value+"@id_trabajador="+getObj('nomina_rp_id_trabajador').value+"@frecuencia="+getObj('nomina_rp_frecuencia').value+"@numero="+getObj('nomina_rp_nominas').value;
		//alert(url);
		openTab("DETALLE CALCULO DE NOMINA",url);
		}
	}
	}
});

//
//
$("#nomina_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/nomina/rp/vista.grid_tipo_nomina_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#nomina_pr_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/rp/sql_tipo_nomina_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#nomina_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#nomina_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/rp/sql_tipo_nomina_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/nomina/rp/sql_tipo_nomina_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo Nomina','Observacion'],
								colModel:[
									{name:'id_tipo_nomina',index:'id_tipo_nomina', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('nomina_rp_id_tipo_nomina').value=ret.id_tipo_nomina;
									getObj('nomina_rp_tipo_nomina').value=ret.nombre;
									//
									getObj('nomina_rp_id_trabajador').value='';
									getObj('nomina_rp_cedula_trabajador').value='';
									getObj('nomina_rp_nombre_trabajador').value='';
									getObj('nomina_rp_apellido_trabajador').value='';
									getObj('nomina_rp_id_nominas').value='';
									getObj('nomina_rp_nominas').value='';
									getObj('nomina_rp_fecha_desde').value='';
									getObj('nomina_rp_fecha_hasta').value='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#nomina_pr_nombre").focus();
								$('#nomina_pr_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_nomina',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
$("#nomina_rp_btn_consulta_emergente_nominas").click(function() {
	if(getObj('nomina_rp_id_tipo_nomina').value!='' /*&& getObj('nomina_rp_id_trabajador').value!=''*/){														  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/nomina/rp/vista.grid_nominas_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Nominas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_numero= jQuery("#nomina_rp_numero").val(); 
					var id_tipo_nomina = getObj('nomina_rp_id_tipo_nomina').value;
					var id_trabajador = getObj('nomina_rp_id_trabajador').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/rp/sql_nominas_nom.php?busq_numero="+busq_numero+"&id_tipo_nomina="+getObj('nomina_rp_id_tipo_nomina').value+"&id_trabajador="+id_trabajador,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#nomina_rp_numero").keypress(function(key)
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
							var busq_numero= jQuery("#nomina_rp_numero").val();
							var id_tipo_nomina = getObj('nomina_rp_id_tipo_nomina').value;
							var id_trabajador = getObj('nomina_rp_id_trabajador').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/rp/sql_nominas_nom.php?busq_numero="+busq_numero+"&id_tipo_nomina="+getObj('nomina_rp_id_tipo_nomina').value+"&id_trabajador="+id_trabajador,page:1}).trigger("reloadGrid");
							
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
								url:"modulos/rrhh/nomina/rp/sql_nominas_nom.php?id_tipo_nomina="+getObj('nomina_rp_id_tipo_nomina').value+"&id_trabajador="+getObj('nomina_rp_id_trabajador').value,
								datatype: "json",
								colNames:['ID','Nº Nomina','Desde','Hasta','Frecuencia'],
								colModel:[
									{name:'id_nominas',index:'id_nominas', width:50,sortable:false,resizable:false,hidden:true},
									{name:'numero_nomina',index:'numero_nomina', width:100,sortable:false,resizable:false},
									{name:'desde',index:'desde', width:100,sortable:false,resizable:false},
									{name:'hasta',index:'hasta', width:100,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:100,sortable:false,resizable:false, hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('nomina_rp_id_nominas').value=ret.id_nominas;
									getObj('nomina_rp_nominas').value=ret.numero_nomina;
									getObj('nomina_rp_fecha_desde').value=ret.desde;
									getObj('nomina_rp_fecha_hasta').value=ret.hasta;
									getObj('nomina_rp_frecuencia').value=ret.descripcion;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#nomina_rp_numero").focus();
								$('#nomina_rp_numero').numeric({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_nominas',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	}
	});
//
//
$("#nomina_rp_btn_consulta_emergente_trabajador").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/nomina/rp/vista.grid_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#nomina_rp_nombre").val(); 
					var busq_apellido= jQuery("#nomina_rp_apellido").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/rp/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#nomina_rp_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#nomina_rp_apellido").keypress(function(key)
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
							var busq_nombre= jQuery("#nomina_rp_nombre").val();
							var busq_apellido= jQuery("#nomina_rp_apellido").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/rp/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/nomina/rp/sql_trabajador_nom.php?nd='+nd,
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
									getObj('nomina_rp_id_trabajador').value=ret.id_trabajador;
									getObj('nomina_rp_cedula_trabajador').value=ret.cedula;
									getObj('nomina_rp_nombre_trabajador').value=ret.nombre;
									getObj('nomina_rp_apellido_trabajador').value=ret.apellido;
									//
									getObj('nomina_rp_id_nominas').value='';
									getObj('nomina_rp_nominas').value='';
									getObj('nomina_rp_fecha_desde').value='';
									getObj('nomina_rp_fecha_hasta').value='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#nomina_rp_nombre").focus();
								$('#nomina_rp_nombre').alpha({allow:' '});
								$('#nomina_rp_apellido').alpha({allow:' '});
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
$("#nomina_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
//
function limpiar(){
	getObj('nomina_rp_id_trabajador').value = '';
	getObj('nomina_rp_id_tipo_nomina').value = '';
	getObj('nomina_rp_id_nominas').value = '';
	getObj('nomina_rp_tipo_nomina').value = '';
	getObj('nomina_rp_nominas').value = '';
	getObj('nomina_rp_cedula_trabajador').value='';
	getObj('nomina_rp_nombre_trabajador').value='';
	getObj('nomina_rp_apellido_trabajador').value='';
	getObj('nomina_rp_frecuencia').value='';
	getObj('nomina_rp_fecha_desde').value='';
	getObj('nomina_rp_fecha_hasta').value='';
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
	<img id="nomina_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    
	<img id="nomina_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_rp_nomina" id="form_rp_nomina">
<input type="hidden" name="nomina_rp_id_trabajador" id="nomina_rp_id_trabajador" />
<input type="hidden" name="nomina_rp_id_tipo_nomina" id="nomina_rp_id_tipo_nomina"/>
<input type="hidden" name="nomina_rp_id_nominas" id="nomina_rp_id_nominas"/>
<input type="hidden" name="nomina_rp_frecuencia" id="nomina_rp_frecuencia" />

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Nomina			</th>
	</tr>
    <tr>
			<th>Tipo Nomina</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="nomina_rp_nombre_tipo_nomina" type="text"  id="nomina_rp_tipo_nomina" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="nomina_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
			<th>Trabajador</th>
		  <td> <ul class="input_con_emergente">
				<li>
           <input name="nomina_rp_cedula_trabajador" type="text"  id="nomina_rp_cedula_trabajador" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="nomina_rp_btn_consulta_emergente_trabajador" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
			<th>Nombre</th>
		  <td><label>
		    <input readonly="true" type="text" name="nomina_rp_nombre_trabajador" id="nomina_rp_nombre_trabajador" message="Nombre del Trabajador"/>
	      </label></td>
		</tr>
        <tr>
			<th>Apellido</th>
		  <td><label>
		    <input readonly="true" type="text" name="nomina_rp_apellido_trabajador" id="nomina_rp_apellido_trabajador" message="Apellido del Trabajador"/>
	      </label></td>
		</tr>
        <tr>
			<th>Nominas</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="nomina_rp_nominas" type="text"  id="nomina_rp_nominas" maxlength="60" size="7" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="nomina_rp_btn_consulta_emergente_nominas" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
			<th>Desde</th>
		  <td><input readonly="true" type="text" name="nomina_rp_fecha_desde" id="nomina_rp_fecha_desde" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/></td>
		</tr>
        <tr>
			<th>Desde</th>
		  <td><input readonly="true" type="text" name="nomina_rp_fecha_hasta" id="nomina_rp_fecha_hasta" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>