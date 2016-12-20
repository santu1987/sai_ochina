<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_ley_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/ley/rp/vista.lst.de_ley.php¿busq_nombre_ley="+getObj('sareta_ley_rp_texto_nombre_ley').value; 
		openTab("Listado/ley",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_ley_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_ley_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_ley_rp_btn_consultar_ley").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/ley/db/grid_ley.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Leyes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/ley/rp/sql_grid_ley.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/ley/rp/sql_grid_ley.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/ley/rp/sql_grid_ley.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/ley/rp/sql_grid_ley.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Articulo','Parágrafo','Descripción','Tasa','codigo_tasa','Tarifa','Activo','Tonelaje Inicial','Tonelaje Final','Comentario'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'articulo',index:'articulo', width:220,sortable:false,resizable:false},
									{name:'paragrafo',index:'paragrafo', width:220,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:220,sortable:false,resizable:false},
									{name:'tasa',index:'tasa', width:220,sortable:false,resizable:false},
									{name:'codigo_tasa',index:'codigo_tasa', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tarifa',index:'tarifa', width:220,sortable:false,resizable:false},
									{name:'activo',index:'activo', width:220,sortable:false,resizable:false},
									{name:'tonelaje_inicial',index:'tonelaje_inicial', width:220,sortable:false,resizable:false},
									{name:'tonelaje_final',index:'tonelaje_final', width:220,sortable:false,resizable:false},
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('vista_id_ley').value = ret.id;
									getObj('sareta_ley_rp_texto_nombre_ley').value = ret.descripcion;
									dialog.hideAndUnload();
									$('#form_rp_ley_consulta').jVal();
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
	<img id="form_rp_sareta_ley_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_ley_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_ley_consulta" id="form_rp_ley_consulta">
	<table width="590" class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Ley</th>
	  </tr>
		
		<tr>
          <th width="157">Descripci&oacute;n de la Ley</th>
		  <td width="421"><ul class="input_con_emergente">
              <li>
                <input name="sareta_ley_rp_texto_nombre_ley" type="text" id="sareta_ley_rp_texto_nombre_ley"    size="50" maxlength="30" message="Escribir la Descripci&oacute;n de la Ley  <br><br> Para Seleccionar Todo un Listado de Leyes Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_ley_rp_id_ley" name="sareta_ley_rp_id_ley"/>
              <li id="sareta_ley_rp_btn_consultar_ley" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>