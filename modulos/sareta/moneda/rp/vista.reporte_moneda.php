<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_moneda_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/moneda/rp/vista.lst.de_moneda.php¿busq_nombre_moneda="+getObj('sareta_moneda_rp_texto_nombre_moneda').value; 
		openTab("Listado/moneda",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_moneda_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_moneda_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_moneda_rp_btn_consultar_moneda").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/moneda/rp/grid_consulta_moneda.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Monedas',modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/sareta/moneda/rp/sql_grid_moneda.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Operaci&oacute;n','Comentario'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'operacion',index:'operacion', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('vista_id_moneda').value = ret.id;
									getObj('sareta_moneda_rp_texto_nombre_moneda').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_rp_moneda_consulta').jVal();
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
$('#sareta_moneda_rp_texto_nombre_moneda').alpha({allow:' áéíóúÁÉÍÓÚñ'});
</script>

<div id="botonera">
	<img id="form_rp_sareta_moneda_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_moneda_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_moneda_consulta" id="form_rp_moneda_consulta">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Moneda</th>
	  </tr>
		
		<tr>
          <th>Moneda</th>
		  <td><ul class="input_con_emergente">
              <li>
                <input name="sareta_moneda_rp_texto_nombre_moneda" type="text" id="sareta_moneda_rp_texto_nombre_moneda"    size="50" maxlength="30" message="Escribir el Nombre de la Moneda. <br><br> Para Seleccionar Todo un Listado de Moneda Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_moneda_rp_id_moneda" name="sareta_moneda_rp_id_moneda"/>
              <li id="sareta_moneda_rp_btn_consultar_moneda" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>