<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------

//
//
//----------------------------------------------------------------


$("#nomina_pr_btn_guardar").click(function() {
	if ($('#form_pr_nomina').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/nomina/pr/sql.registrar_nomina.php",
			data:dataForm('form_pr_nomina'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar()
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha del valor del impuesto \n tiene que ser mayor que la fecha actual </p></div>",true,true);
					}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});

//
//----------------------------------------------------------------
//
//
$("#nomina_pr_btn_consulta_emergente_tipo_nomina").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/nomina/pr/vista.grid_tipo_nomina_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Nomina', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#nomina_pr_descripcion").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/pr/sql_tipo_nomina_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#nomina_pr_descripcion").keypress(function(key)
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
							var busq_nombre= jQuery("#nomina_pr_descripcion").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/pr/sql_tipo_nomina_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/nomina/pr/sql_tipo_nomina_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo Nomina','Observacion'],
								colModel:[
									{name:'id_tipo_nomina',index:'id_tipo_nomina', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false}				],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('nomina_pr_id_tipo_nomina').value=ret.id_tipo_nomina;
									getObj('nomina_pr_tipo_nomina').value=ret.nombre;
									dialog.hideAndUnload();
									consulta_automatica_numero_nominas();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#nomina_pr_descripcion").focus();
								$('#nomina_pr_descripcion').alpha({allow:' '});
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
$("#nomina_pr_btn_consulta_emergente_trabajador").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/nomina/pr/vista.grid_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_cedula= jQuery("#nomina_pr_cedula").val(); 
					var busq_nombre= jQuery("#nomina_pr_nombre").val(); 
					var busq_apellido= jQuery("#nomina_pr_apellido").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/pr/sql_trabajador_nom.php?busq_cedula="+busq_cedula+"&busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#nomina_pr_cedula").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#nomina_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#nomina_pr_apellido").keypress(function(key)
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
							var busq_cedula= jQuery("#nomina_pr_cedula").val();
							var busq_nombre= jQuery("#nomina_pr_nombre").val();
							var busq_apellido= jQuery("#nomina_pr_apellido").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/nomina/pr/sql_trabajador_nom.php?busq_cedula="+busq_cedula+"&busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/nomina/pr/sql_trabajador_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Apellido','ID','Cargo'],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:100,sortable:false,resizable:false},
									{name:'id_cargos',index:'id_cargos', width:100,sortable:false,resizable:false, hidden:true},
									{name:'descripcion',index:'descripcion', width:100,sortable:false,resizable:false}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('nomina_pr_id_trabajador').value=ret.id_trabajador;
									getObj('nomina_pr_cedula_trabajador').value=ret.cedula;
									getObj('nomina_pr_nombre_trabajador').value=ret.nombre;
									getObj('nomina_pr_apellido_trabajador').value=ret.apellido;
									dialog.hideAndUnload();
									generar_pre_nomina2();
									//consulta_automatica_numero_nominas();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#nomina_pr_descripcion").focus();
								$('#nomina_pr_descripcion').alpha({allow:' '});
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
function consulta_automatica_numero_nominas(){
	$.ajax ({
		    url:"modulos/rrhh/nomina/pr/sql_numero_nomina.php",
			data:dataForm("form_pr_nomina"),
			type:'POST', 
			cache: false,
			success: function(html)
			{
				if(html!=''){
					arreglo = html.split('*');
					getObj('nomina_pr_id_nominas').value = arreglo[0];
					getObj('nomina_pr_desde').value = arreglo[1];
					getObj('nomina_pr_hasta').value = arreglo[2];
					getObj('nomina_pr_procesada').value = arreglo[3];
					alert('paso');
					generar_pre_nomina();
					
				}
				else if(html==''){
					getObj('nomina_pr_id_nominas').value = '';
					getObj('nomina_pr_desde').value = '';
					getObj('nomina_pr_hasta').value = '';
					getObj('nomina_pr_procesada').value = '';
				}
			}
		});
}
//
//
function generar_pre_nomina(){
	$.ajax ({
		    url:"modulos/rrhh/nomina/pr/sql_registrar_pre_nomina.php",
			data:dataForm("form_pr_nomina"),
			type:'POST', 
			cache: false,
			success: function(html)
			{
				alert(html);
			}
		});
}
//
//
function limpiar(){
	getObj('nomina_pr_id_tipo_nomina').value = '';
	getObj('nomina_pr_tipo_nomina').value='';
	getObj('nomina_pr_id_trabajador').value='';
	getObj('nomina_pr_cedula_trabajador').value='';
	getObj('nomina_pr_nombre_trabajador').value='';
	getObj('nomina_pr_apellido_trabajador').value='';
	getObj('nomina_pr_opt_calculo').selectedIndex=0;
	ocultar_grupal();
	getObj('nomina_pr_btn_actualizar').style.display = 'none';
	getObj('nomina_pr_btn_eliminar').style.display = 'none';
	getObj('nomina_pr_btn_guardar').style.display = '';
	setBarraEstado("");
}
$("#nomina_pr_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
});
//
//
function ocultar_individual(){
	getObj('nomina_pr_id_trabajador').value='';
	getObj('nomina_pr_cedula_trabajador').value='';
	getObj('nomina_pr_nombre_trabajador').value='';
	getObj('nomina_pr_apellido_trabajador').value='';
	getObj('nomina_pr_vista_cedula').style.display='none';
	getObj('nomina_pr_vista_nombre').style.display='none';
	getObj('nomina_pr_vista_apellido').style.display='none';
	getObj('nomina_pr_id_tipo_nomina').value='';
	getObj('nomina_pr_vista_tipo_nomina').style.display='';	
}
//
//
function ocultar_grupal(){
	getObj('nomina_pr_id_tipo_nomina').value='';
	getObj('nomina_pr_tipo_nomina').value='';
	getObj('nomina_pr_vista_tipo_nomina').style.display='none';
	getObj('nomina_pr_vista_cedula').style.display='';
	getObj('nomina_pr_vista_nombre').style.display='';
	getObj('nomina_pr_vista_apellido').style.display='';
}
//
//
$("#nomina_pr_opt_calculo").change(function() {
	if(getObj('nomina_pr_opt_calculo').value==2)
		ocultar_individual();
	if(getObj('nomina_pr_opt_calculo').value==1)
		ocultar_grupal();
});
//
//Consulta automatica del trabajador
$("#nomina_pr_cedula_trabajador").change(function() {
	$.ajax ({
		    url:"modulos/rrhh/nomina/pr/sql_consulta_automatica_trabajador.php",
			data:dataForm("form_pr_nomina"),
			type:'POST', 
			cache: false,
			success: function(html)
			{
				if(html!=''){
					arreglo = html.split('*');
					getObj('nomina_pr_id_trabajador').value=arreglo[0];
					getObj('nomina_pr_cedula_trabajador').value=arreglo[1];
					getObj('nomina_pr_nombre_trabajador').value=arreglo[2];
					getObj('nomina_pr_apellido_trabajador').value=arreglo[3];
				}
				else{
					getObj('nomina_pr_id_trabajador').value='';
					getObj('nomina_pr_cedula_trabajador').value='';
					getObj('nomina_pr_nombre_trabajador').value='';
					getObj('nomina_pr_apellido_trabajador').value='';
				}
			}
		});
});
//
//Calcular la pre-nomina de forma individual
function generar_pre_nomina2(){
	alert('paso');
	$.ajax ({
		    url:"modulos/rrhh/nomina/pr/sql_registrar_pre_nomina2.php",
			data:dataForm("form_pr_nomina"),
			type:'POST', 
			cache: false,
			success: function(html)
			{
				alert(html);
			}
		});
}
//
//
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
//$('#sitio_fisico_db_nombre_unidad').alpha({allow:' '});
$('#nomina_pr_cedula_trabajador').numeric({allow:'V-E'});
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
	<img id="nomina_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="nomina_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="nomina_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="nomina_pr_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="nomina_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_pr_nomina" id="form_pr_nomina">
<input type="hidden" name="nomina_pr_id_nomina" id="nomina_pr_id_nomina" />
<input type="hidden" name="nomina_pr_fechact" id="nomina_pr_fechact" value="<?php echo date("d-m-Y");?>"/>
<input type="hidden" name="nomina_pr_id_tipo_nomina" id="nomina_pr_id_tipo_nomina"/>
<input type="hidden" name="nomina_pr_id_nominas" id="nomina_pr_id_nominas" />
<input type="hidden" name="nomina_pr_desde" id="nomina_pr_desde" />
<input type="hidden" name="nomina_pr_hasta" id="nomina_pr_hasta" />
<input type="hidden" name="nomina_pr_procesada" id="nomina_pr_procesada"/>
<input type="hidden" name="nomina_pr_id_trabajador" id="nomina_pr_id_trabajador"/>
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Calcular Nomina</th>
	</tr>
         <tr>
			<th>Tipo de Calculo</th>
		  <td><label>
		    <select name="nomina_pr_opt_calculo" id="nomina_pr_opt_calculo" >
            	<option value="1">Individual</option>
                <option value="2">Tipo Nomina</option>
	        </select>
	       </label></td>
		</tr>
        <tr id="nomina_pr_vista_tipo_nomina" style="display:none">
			<th>Tipo de Nomina</th>
		  <td><ul class="input_con_emergente">
				<li>
           <input name="nomina_pr_tipo_nomina" type="text"  id="nomina_pr_tipo_nomina" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Tipo Nomina Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Tipo Nomina: '+$(this).val()]}"/>
           </li>
				<li id="nomina_pr_btn_consulta_emergente_tipo_nomina" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr id="nomina_pr_vista_cedula" >
			<th>Cedula</th>
		  <td><ul class="input_con_emergente">
				<li>
           <input name="nomina_pr_cedula_trabajador" type="text"  id="nomina_pr_cedula_trabajador" maxlength="60" size="30"
           jval="{valid:/^[0-9 V-E]{1,60}$/, message:'Cedula Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9 V-E]/, cFunc:'alert', cArgs:['Tipo Nomina: '+$(this).val()]}"/>
           </li>
				<li id="nomina_pr_btn_consulta_emergente_trabajador" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr id="nomina_pr_vista_nombre" >
			<th>Nombre</th>
		  <td><label>
		    <input readonly="true" type="text" name="nomina_pr_nombre_trabajador" id="nomina_pr_nombre_trabajador" />
	      </label></td>
		</tr>
         <tr id="nomina_pr_vista_apellido" >
			<th>Apellido</th>
		  <td><label>
		    <input readonly="true" type="text" name="nomina_pr_apellido_trabajador" id="nomina_pr_apellido_trabajador" />
	       </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>