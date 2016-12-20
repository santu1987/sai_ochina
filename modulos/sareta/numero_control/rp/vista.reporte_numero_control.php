<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_numero_control_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/numero_control/rp/vista.lst.de_numero_control.php¿busq_nombre_numero_control="+getObj('sareta_numero_control_rp_texto_nombre_numero_control').value; 
		openTab("Listado/Numero Control",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_numero_control_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_numero_control_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_numero_control_rp_btn_consultar_numero_control").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/numero_control/db/grid_numero_control.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Numero de Control', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/numero_control/rp/sql_grid_numero_control.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/numero_control/rp/sql_grid_numero_control.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/numero_control/rp/sql_grid_numero_control.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/numero_control/rp/sql_grid_numero_control.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Descripción','','N&deg; Inicial','N&deg; Final','N&deg; Actual','Estatus','Comentario','con'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'descripcion',index:'descripcion', width:220,sortable:false,resizable:false},
									{name:'des_paso',index:'des_paso', width:220,sortable:false,resizable:false,hidden:true},
									{name:'numero_inicial',index:'numero_inicial', width:220,sortable:false,resizable:false},
									{name:'numero_final',index:'numero_final', width:220,sortable:false,resizable:false},
									{name:'numero_actual',index:'numero_actual', width:220,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('vista_id_numero_control').value = ret.id;
									getObj('sareta_numero_control_rp_texto_nombre_numero_control').value = ret.des_paso;
									dialog.hideAndUnload();
									$('#form_rp_numero_control_consulta').jVal();
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
</script>

<div id="botonera">
	<img id="form_rp_sareta_numero_control_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_numero_control_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_numero_control_consulta" id="form_rp_numero_control_consulta">
	<table width="590" class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Numero de Control</th>
	  </tr>
		
		<tr>
          <th width="157">Descripci&oacute;n</th>
		  <td width="421"><ul class="input_con_emergente">
              <li>
                <input name="sareta_numero_control_rp_texto_nombre_numero_control" type="text" id="sareta_numero_control_rp_texto_nombre_numero_control"    size="50" maxlength="30" message="Escribir la Descripci&oacute;n del Numero de Control<br><br> Para Seleccionar Todo un Listado de los Numero de Control Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_numero_control_rp_id_numero_control" name="sareta_numero_control_rp_id_numero_control"/>
              <li id="sareta_numero_control_rp_btn_consultar_numero_control" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>