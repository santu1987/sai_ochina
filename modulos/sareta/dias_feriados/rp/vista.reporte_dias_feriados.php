<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_dias_feriados_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/dias_feriados/rp/vista.lst.de_dias_feriados.php¿busq_nombre_dias_feriados="+getObj('sareta_dias_feriados_rp_texto_nombre_dias_feriados').value; 
		openTab("Listado/Dias Feriados",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_dias_feriados_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_dias_feriados_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_dias_feriados_rp_btn_consultar_dias_feriados").click(function() {
		var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/dias_feriados/rp/grid_consulta_dias_feriados.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Dias Feriados', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/dias_feriados/rp/sql_grid_dias_feriados.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/dias_feriados/rp/sql_grid_dias_feriados.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/dias_feriados/rp/sql_grid_dias_feriados.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/dias_feriados/rp/sql_grid_dias_feriados.php?nd='+nd,
								datatype: "json",
								colNames:['id','Descripci&oacute;n','des','Fecha','Tipo','Ctipo','Comentario','Com','Delegai&oacute;n','delegaion1'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'des1',index:'des1', width:220,sortable:false,resizable:false},
									{name:'des2',index:'des2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'fecha',index:'fecha', width:100,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false},
									{name:'ctipo',index:'ctipo', width:220,sortable:false,resizable:false,hidden:true},
									{name:'obs1',index:'obs1', width:150,sortable:false,resizable:false},
									{name:'obs2',index:'obs2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'delegaion',index:'delegaion', width:300,sortable:false,resizable:false},
									{name:'deleg',index:'deleg', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('vista_id_dias_feriados').value = ret.id;
									getObj('sareta_dias_feriados_rp_texto_nombre_dias_feriados').value = ret.des2;
									dialog.hideAndUnload();
									$('#form_rp_dias_feriados_consulta').jVal();
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
</script>

<div id="botonera">
	<img id="form_rp_sareta_dias_feriados_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_dias_feriados_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_dias_feriados_consulta" id="form_rp_dias_feriados_consulta">
	<table width="590" class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Dias Feriados</th>
	  </tr>
		
		<tr>
          <th width="157">Descripci&oacute;n:</th>
		  <td width="421"><ul class="input_con_emergente">
              <li>
                <input name="sareta_dias_feriados_rp_texto_nombre_dias_feriados" type="text" id="sareta_dias_feriados_rp_texto_nombre_dias_feriados"    size="50" maxlength="30" message="Escribir el Nombre del Dia Feriado<br><br> Para Seleccionar Todo un Listado de los Dias Feriados Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z 0-9 &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z 0-9 &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_dias_feriados_rp_id_dias_feriados" name="sareta_dias_feriados_rp_id_dias_feriados"/>
              <li id="sareta_dias_feriados_rp_btn_consultar_dias_feriados" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>