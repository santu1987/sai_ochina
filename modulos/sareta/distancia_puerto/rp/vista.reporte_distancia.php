<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_distancia_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/distancia_puerto/rp/vista.lst.de_distancia.php¿busq_nombre_distancia="+getObj('sareta_distancia_rp_texto_nombre').value; 
		openTab("Listado/Distancias",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_distancia_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_distancia_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_distancia_rp_btn_consultar_distancia").click(function() {
		var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/distancia_puerto/rp/grid_consulta_distancia.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Distancia entre Puertos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/rp/sql_grid_distancia.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/distancia_puerto/db/sql_grid_distancia.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/distancia_puerto/rp/sql_grid_distancia.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/distancia_puerto/rp/sql_grid_distancia.php?nd='+nd,
								datatype: "json",
								colNames:['ID','id_org','Bandera Desde','id_Pto_Org','Puerto_Origen','Puerto Origen','id_rec','Bandera Hasta','id_Pto_Rec','Puerto_Recalada','Puerto Recalada','Millas','obs'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_org',index:'id_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_org',index:'bandera_org', width:220,sortable:false,resizable:false},
									{name:'id_pto_org',index:'id_pto_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'pto_org1',index:'pto_org1', width:220,sortable:false,resizable:false},
									{name:'pto_org',index:'pto_org', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_rec',index:'id_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera_rec',index:'bandera_rec', width:220,sortable:false,resizable:false},
									{name:'id_pto_rec',index:'id_pto_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'pto_rec1',index:'pto_rec1', width:220,sortable:false,resizable:false},
									{name:'pto_rec',index:'pto_rec', width:220,sortable:false,resizable:false,hidden:true},
									{name:'millas',index:'millas', width:220,sortable:false,resizable:false},
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sareta_distancia_rp_texto_nombre').value = ret.pto_org;
									dialog.hideAndUnload();
									$('#form_db_distancia').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
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
$('#sareta_bandera_rp_texto_nombre_bandera').alpha({allow:' áéíóúÁÉÍÓÚñ'});
</script>

<div id="botonera">
	<img id="form_rp_sareta_distancia_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_distancia_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_distancia_consulta" id="form_rp_distancia_consulta">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Distancias entre Puertos</th>
	  </tr>
		
		<tr>
          <th>Puerto</th>
		  <td><ul class="input_con_emergente">
              <li>
                <input name="sareta_distancia_rp_texto_nombre" type="text" id="sareta_distancia_rp_texto_nombre"    size="50" maxlength="30" message="Escribir el Nombre de la distancia. <br><br> Para Seleccionar Todo un Listado de distancias Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_distancia_rp_id_distancia" name="sareta_distancia_rp_id_distancia"/>
              <li id="sareta_distancia_rp_btn_consultar_distancia" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>