<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_tipo_tasa_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/tipo_tasa/rp/vista.lst.de_tipo_tasa.php¿busq_nombre_tipo_tasa="+getObj('sareta_tipo_tasa_rp_texto_nombre_tipo_tasa').value; 
		openTab("Listado/Tipo de Tasa",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_tipo_tasa_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_tipo_tasa_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_tipo_tasa_rp_btn_consultar_tipo_tasa").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/tipo_tasa/rp/grid_consulta_tipo_tasa.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Tasas',modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/sareta/tipo_tasa/rp/sql_grid_tipo_tasa.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('vista_id_tipo_tasa').value = ret.id;
									getObj('sareta_tipo_tasa_rp_texto_nombre_tipo_tasa').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_rp_tipo_tasa_consulta').jVal();
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
$('#sareta_tipo_tasa_rp_texto_nombre_tipo_tasa').alpha({allow:' áéíóúÁÉÍÓÚñ'});
</script>

<div id="botonera">
	<img id="form_rp_sareta_tipo_tasa_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_tipo_tasa_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_tipo_tasa_consulta" id="form_rp_tipo_tasa_consulta">
	<table width="474" class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Tipo de Tasa</th>
	  </tr>
		
		<tr>
          <th width="98">Tipo de Tasa</th>
		  <td width="374"><ul class="input_con_emergente">
              <li>
                <input name="sareta_tipo_tasa_rp_texto_nombre_tipo_tasa" type="text" id="sareta_tipo_tasa_rp_texto_nombre_tipo_tasa"    size="50" maxlength="30" message="Escribir el Nombre de el Tipo de Tasa. Ejem: ''Recalada' <br><br> Para Seleccionar Todo un Listado de Tipo de Tasa Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
              <input type="hidden" id="sareta_tipo_tasa_rp_id_tipo_tasa" name="sareta_tipo_tasa_rp_id_tipo_tasa"/>
              <li id="sareta_tipo_tasa_rp_btn_consultar_tipo_tasa" class="btn_consulta_emergente"></li>             
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>