<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_clases_buques_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/clases_buques/rp/vista.lst.de_clases_buques.php¿busq_nombre_clases_buques="+getObj('sareta_clases_buques_rp_texto_nombre_clases_buques').value; 
		openTab("Listado/Clases de Buque",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_clases_buques_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_clases_buques_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_clases_buques_rp_btn_consultar_clases_buques").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/clases_buques/rp/grid_consulta_clases_buques.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Clases de Buques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/clases_buques/rp/sql_grid_clases_buques.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/clases_buques/rp/sql_grid_clases_buques.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/clases_buques/rp/sql_grid_clases_buques.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/clases_buques/rp/sql_grid_clases_buques.php?nd='+nd,
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
									//getObj('vista_id_clases_buques').value = ret.id;
									getObj('sareta_clases_buques_rp_texto_nombre_clases_buques').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_rp_clases_buques_consulta').jVal();
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
$('#sareta_clases_buques_rp_texto_nombre_clases_buques').alpha({allow:' áéíóúÁÉÍÓÚñ'});
</script>

<div id="botonera">
	<img id="form_rp_sareta_clases_buques_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_clases_buques_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_clases_buques_consulta" id="form_rp_clases_buques_consulta">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Clase de Buques</th>
	  </tr>
		
		<tr>
          <th>Clases de Buques</th>
		  <td><ul class="input_con_emergente">
              <li>
                <input name="sareta_clases_buques_rp_texto_nombre_clases_buques" type="text" id="sareta_clases_buques_rp_texto_nombre_clases_buques"    size="50" maxlength="30" message="Escribir el Nombre de la Clases de Buques. Ejem: ''Cementero'' <br><br> Para Seleccionar Todo un Listado de Clases de Buques Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_clases_buques_rp_id_clases_buques" name="sareta_clases_buques_rp_id_clases_buques"/>
              <li id="sareta_clases_buques_rp_btn_consultar_clases_buques" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>