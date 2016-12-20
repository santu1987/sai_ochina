<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_tipo_actividad_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/tipo_actividad/rp/vista.lst.de_tipo_actividad.php¿busq_nombre_tipo_actividad="+getObj('sareta_tipo_actividad_rp_texto_nombre_tipo_actividad').value; 
		openTab("Listado/tipo de Actividad",url);
});
$("#form_rp_sareta_tipo_actividad_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_tipo_actividad_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_tipo_actividad_rp_btn_consultar_tipo_actividad").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/tipo_actividad/rp/grid_consulta_tipo_actividad.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Actividades',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/tipo_actividad/rp/sql_grid_tipo_actividad.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:180,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath:'../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('vista_id_tipo_actividad').value = ret.id;
									getObj('sareta_tipo_actividad_rp_texto_nombre_tipo_actividad').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_rp_tipo_actividad_consulta').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
/*-------------------   Fin Validaciones  ---------------------------*/
$('#sareta_tipo_actividad_rp_texto_nombre_tipo_actividad').alpha({allow:' áéíóúÁÉÍÓÚñ'});
</script>

<div id="botonera">
	<img id="form_rp_sareta_tipo_actividad_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_tipo_actividad_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_tipo_actividad_consulta" id="form_rp_tipo_actividad_consulta">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Tipo de Actividad</th>
	  </tr>
		
		<tr>
          <th>Tipo de Actividad</th>
		  <td><ul class="input_con_emergente">
              <li>
                <input name="sareta_tipo_actividad_rp_texto_nombre_tipo_actividad" type="text" id="sareta_tipo_actividad_rp_texto_nombre_tipo_actividad"    size="50" maxlength="60" message="Escribir el Nombre de el Tipo de Actividad. Ejem: ''TRASPORTE DE PERSONAL'' <br><br> Para Seleccionar Todo un Listado de Actividad Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z  &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z  &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_tipo_actividad_rp_id_tipo_actividad" name="sareta_tipo_actividad_rp_id_tipo_actividad"/>
              <li id="sareta_tipo_actividad_rp_btn_consultar_tipo_actividad" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>