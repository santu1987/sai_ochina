<?php
session_start();

$fecha=date("d/m/Y");
?>


<script type='text/javascript'>
var dialog;
//
$("#ficha_persona_imprimir").click(function() {
		url="pdf.php?p=modulos/zodi/ficha_personal/rp/vista.lst.ficha_personal.php";
		setBarraEstado(url);
		openTab("FichaPersonal",url);
});
///////////////////guardar
$("#ficha_persona_guardar").click(function() {
if ($('#form_ficha_personal').jVal())
{			//si no es 
	setBarraEstado(mensaje[esperando_respuesta]);
													$.ajax (
													{
														url: "modulos/zodi/ficha_personal/db/sql.guardar.php",
														data:dataForm('form_ficha_personal'),
														type:'POST',
														cache: false,
														success: function(html)
														{
															recordset=html;
															//alert(recordset[1]);
															//recordset = recordset.split("*");
															if (recordset[0]=="Registrado")
															{
																setBarraEstado(mensaje[registro_exitoso],true,true);
															}
															else if (recordset[0]=="NoRegistro")
															{
																setBarraEstado(mensaje[registro_existe],true,true);
															}
													}
												});
												

}
});
$("#ficha_persona_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/zodi/ficha_personal/db/sql_grid_ficha.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Personas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#ficha_grid_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/zodi/ficha_personal/db/sql_ficha_consulta.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#ficha_grid_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#ficha_grid_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/zodi/ficha_personal/db/sql_ficha_consulta.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/zodi/ficha_personal/db/sql_ficha_consulta.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Apellido','C.I','Fecha Nacimiento','Grupo sanguineo','Alergico','Tratamiento','Dir','cel','tlf_emer','dir_familiar','tlf','placas','modelo','color','profesion','empr','dir_trab'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:100,sortable:false,resizable:false},
									{name:'ci',index:'ci', width:100,sortable:false,resizable:false},
									{name:'fec_nac',index:'fec_nac', width:100,sortable:false,resizable:false,hidden:true},
									{name:'g_sanguineo',index:'g_sanguineo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'alergico',index:'alergico', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tratamiento',index:'tratamiento', width:100,sortable:false,resizable:false,hidden:true},
									{name:'dir',index:'dir', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cel',index:'cel', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tlf_eme',index:'tlf_eme', width:100,sortable:false,resizable:false,hidden:true},
									{name:'dir_familiar',index:'dir_familiar', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tlf',index:'tlf', width:100,sortable:false,resizable:false,hidden:true},
									{name:'placas',index:'placas', width:100,sortable:false,resizable:false,hidden:true},
									{name:'modelo',index:'modelo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'marca',index:'color', width:100,sortable:false,resizable:false,hidden:true},
									{name:'profesion',index:'profesion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'empresa',index:'empresa', width:100,sortable:false,resizable:false,hidden:true},
									{name:'dir_empresa',index:'dir_empresa', width:100,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_persona').value = ret.id;
									getObj('ficha_personal_nombre').value=ret.nombre;
									getObj('ficha_personal_apellido').value = ret.apellido;
									cedula=ret.ci;
									nac=ret.nacionalidad;
									getObj('ficha_personal_cedula').value=cedula;
									
									getObj('ficha_personal_fecha').value =ret.fec_nac;
									getObj('ficha_personal_dirdomici').value = ret.dir;
									getObj('ficha_personal_tlf').value = ret.cel;
									getObj('').value = ret.comentario;
									getObj('').style.display='';
									getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='';
									//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='';
									getObj('adquisiciones_impuesto_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#adquisiciones_impuesto_db_nombre2").focus();
								$('#adquisiciones_impuesto_db_nombre2').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_impuesto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
////////////////////////////////////////////
</script>

<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script type='text/javascript'>
//----------------- funcion para crear las pestañas---------------------////
$(function() {
    $('#pestana_doc').tabs();
 });
////------------------------ fin de funcion crear pestañas bienes ----------------/////

/*$('#adquisiciones_impuesto_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#adquisiciones_impuesto_db_organismo').alpha({allow:' áéíóúÄÉÍÓÚ'});
$('#adquisiciones_impuesto_db_fecha').numeric({allow:'/-'});
$("input, select, textarea").bind("focus", function(){
-->	/////////////////////////////
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
*/	
</script>

<div id="botonera">
	<img id="ficha_persona_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <!--<img id="adquisiciones_impuesto_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/> -->
	<img id="ficha_persona_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="ficha_persona_imprimir" class="btn_imprimir" src="imagenes/null.gif" />
	<img id="ficha_persona_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="ficha_persona_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<div id="pestana_doc">
<form method="post" id="form_ficha_personal" name="form_ficha_personal">

	<div>
			 <ul class="tabs-nav">
				<li><a href="#pestana1_doc"><span>Datos Persona</span></a></li>
				<li><a href="#pestana2_doc"><span>Informaci&oacute;n M&eacute;dica</span></a></li>
				<li><a href="#pestana3_doc"><span>Veh&iacute;culo</span></a></li>
				<li><a href="#pestana4_doc"><span>Ocupaci&oacute;n</span></a></li>
				<li><a href="#pestana5_doc"><span>Agregar Imagen</span></a></li>

			  </ul> 
	</div>
<div>
<div id="pestana1_doc" class="tabs-container">
<table class="cuerpo_formulario">
		 <th style="border-top: 1px #BADBFC solid">Nombres:</th>
		   <td  style="border-top: 1px #BADBFC solid">
		<input name="ficha_personal_nombre" type="text" id="ficha_personal_nombre"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	  
		 </td>
		</tr>
		<tr>
		<th>Apellidos:		</th>	
		<td>	
		<input name="ficha_personal_apellido" type="text" id="ficha_personal_apellido"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
		</tr>
		<tr>
		<th>C&eacute;dula de Identidad:		</th>	
		<td>
		<select name="ficha_personal_nacionalidad" id="ficha_personal_nacionalidad" style="width:50px; min-width:50px;">
				  <option>V-</option>
				  <option>E-</option>
				  <option>P-</option>
		  </select>	    
		  <input name="ficha_personal_cedula" type="text" id="ficha_personal_cedula"  size="8" maxlength="9" width="150px" 
					message="Introduzca el N&uacute;mero de C&eacute;dula. Ejem: ''V-0000000 &oacute; E-0000000''" 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
		</tr> 
		<tr>
		<th>Fecha de Nacimiento:</th>
			<td width="124">
					<input alt="date" type="text" name="ficha_personal_fecha" id="ficha_personal_fecha" size="7" value="<? echo($fecha);?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" onchange="v_fecha2();"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
					<input type="hidden"  name="ficha_personal_fecha_oculto" id="ficha_personal_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
				  <button type="reset" id="ficha_personal_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "ficha_personal_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "ficha_personal_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("ficha_personal_fecha").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_inicial_rp_fecha_hasta").value.MMDDAAAA() );
										
									}
							});
					</script>
		</td>
		</tr>
		<tr> 
		<th>Dir.Domiciliaria</th>
		<td><textarea name="ficha_personal_dirdomici" cols="60" id="ficha_personal_dirdomici" message="Introduzca un dirección"></textarea></td>
		</tr>
		<tr>
		<th>N TLF Personal</th>
		<td>
				<input name="ficha_personal_tlf" type="text" id="ficha_personal_tlf" size="27" maxlength="7" message="Introduzca el Número de Teléfono ." 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
				
		</td>
		</tr>
		<tr>
		<th>N TLF Emergencia</th>
		<td>
				<input name="ficha_personal_tlf_emergencia" type="text" id="ficha_personal_tlf_emergencia" size="27" maxlength="7" message="Introduzca el Número de Teléfono ." 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
		</tr>
		<tr>
		<th>Dir.Familiar</th>
		<td><textarea name="ficha_personal_dirfamiliar" cols="60" id="ficha_personal_dirfamiliar" message="Introduzca un dirección"></textarea></td>
		</tr>
		<tr>
		<th>Tel&eacute;fono</th>
		<td><input name="ficha_personal_tlf_fam" type="text" id="ficha_personal_tlf_fam" size="27" maxlength="7" message="Introduzca el Número de Teléfono ." 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" /></td>
		</tr>
		
		
		<tr>
		<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>
  </table>
</div>
  <div id="pestana2_doc" class="tabs-container">
  <table   class="cuerpo_formulario">
 
   <tr>
   		<th style="border-top: 1px #BADBFC solid">Grupo Sangu&iacute;neo:</th>
		   <td  style="border-top: 1px #BADBFC solid">
				<input name="ficha_personal_grupo_sanguineo" type="text" id="ficha_personal_grupo_sanguineo"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>Alergico a :</th>
		<td>
				<input name="ficha_personal_alergico" type="text" id="ficha_personal_alergico"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>Tratamiento M&eacute;dico</th>
  		<td><textarea name="ficha_personal_tratamiento" cols="60" id="ficha_personal_tratamiento" message="Introduzca un tratamiento"></textarea></td>
   </tr>
    <tr>
        <td colspan="4" class="bottom_frame">&nbsp;</td>
        </tr>
  </table>
  </div>
  <div id="pestana3_doc" class="tabs-container">
  <table   class="cuerpo_formulario">
   <tr>	
   <th style="border-top: 1px #BADBFC solid">Veh&iacute;culo Personal:</th>
		   <td  style="border-top: 1px #BADBFC solid">
   	
				<input name="ficha_personal_vehiculos" type="text" id="ficha_personal_vehiculos"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>Placas:</th>
		<td>
				<input name="ficha_personal_placas" type="text" id="ficha_personal_placas"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>M&oacute;delo:</th>
		<td>
				<input name="ficha_personal_modelo_vehiculo" type="text" id="ficha_personal_modelo_vehiculo"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>Marca:</th>
		<td>
						<input name="ficha_personal_marca_vehiculo" type="text" id="ficha_personal_marca_vehiculo"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>Color:</th>	
   		<td>
					<input name="ficha_personal_color_vehiculo" type="text" id="ficha_personal_color_vehiculo"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
    <tr>
        <td colspan="4" class="bottom_frame">&nbsp;</td>
        </tr>
  </table> 
  </div>
 <div id="pestana4_doc" class="tabs-container">
  <table   class="cuerpo_formulario">

   <tr>
   		<th style="border-top: 1px #BADBFC solid">Profesi&oacute;n u Oficio:</th>
		   <td  style="border-top: 1px #BADBFC solid">
					<input name="ficha_personal_oficio" type="text" id="ficha_personal_oficio"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>Empresa donde trabaja</th>
		<td>
				<input name="ficha_personal_empresa" type="text" id="ficha_personal_empresa"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		</td>
   </tr>
   <tr>
   		<th>Direcci&oacute;n donde trabaja</th>
		<td><textarea name="ficha_personal_dir_trabajo" cols="60" id="ficha_personal_dir_trabajo" message="Introduzca un tratamiento"></textarea></td>
		 <tr>
        <td colspan="4" class="bottom_frame">&nbsp;</td>
        </tr>
   </tr>
</table>
</div>
</div>
<input type="hidden" id="id_persona" name="id_persona" />
</form>
 <div id="pestana5_doc" class="tabs-container">
	<table width="310" class="cuerpo_formulario">
				  <tr>
						<th style="border-top: 1px #BADBFC solid">Foto:</th>
 				 </tr>
	</table>			 	
 <form action="modulos/administracion_sistema/usuario/db/foto.php" method="post" enctype="multipart/form-data" name="form_foto" target="resultado" id="form_foto">
  
			 <iframe name="resultado" style="display:none"></iframe>
			<iframe id="limpiar_cache" name="limpiar_cache" style="display:none"></iframe>
				<table width="310" class="cuerpo_formulario">
				  <tr>
					<th><img src="imagenes/foto/sombra.png" name="foto_usuario" width="77" height="92" id="foto_usuario" style=" padding-right:35; padding-left:55" /></th>
					<td width="99%">
					<div align="center">
					  <input name="foto" style="padding-left:0; margin-left:0" type="file" id="foto" onchange="tiempo()" size="50"/>
					</div>
					</td>
				  </tr>
			
				  <tr>
					<td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
				  </tr>
			  </table>
			  <input name="nomfoto" type="hidden" id="nomfoto" value=""/>
				<input id="form_foto_opt" name="form_foto_opt" type="hidden"/>
				<input id="form_foto_err_f" name="form_foto_err_f" type="hidden" onclick="error_formato()"/>
				<input id="form_foto_err_t" name="form_foto_err_t" type="hidden" onclick=" error_tamano()" />
				<input id="form_foto_fecha" name="form_foto_fecha" type="hidden"/>
			

			 
</form>
</div>	
<script type="text/javascript">
<!--
num=0;

</script>