<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_agencia_naviera_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/agencia_naviera/rp/vista.lst.de_agencia_naviera.php¿busq_nombre_agencia_naviera="+getObj('sareta_agencia_naviera_rp_texto_nombre_agencia_naviera').value; 
		openTab("Listado/Agencia Naviera",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_agencia_naviera_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_agencia_naviera_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_agencia_naviera_rp_btn_consultar_agencia_naviera").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/agencia_naviera/rp/grid_consulta_agencia_naviera.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Agencias Navieras', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/agencia_naviera/rp/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/agencia_naviera/rp/sql_grid_agencia_naviera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/agencia_naviera/rp/sql_grid_agencia_naviera.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/agencia_naviera/rp/sql_grid_agencia_naviera.php?nd='+nd,
								datatype: "json",
								colNames:['id_agencia_naviera','id_delegacion','Nombre','RIF','NIT','Direcci&oacute;n','id_estado','Estado','&Aacute;rea','Zona','Apartado','Telefono ','Telefono 2','Fax ','Fax 2','Pag Web','Correo','Contacto','Cedula','Cargo','Codigo Auxiliar','Comentario'],
								colModel:[
										{name:'id_agencia_naviera',index:'id_agencia_naviera', width:220,sortable:false,resizable:false,hidden:true},
										{name:'id_delegacion',index:'id_delegacion', width:220,sortable:false,resizable:false,hidden:true},
										{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
										{name:'rif',index:'rif', width:220,sortable:false,resizable:false},
										{name:'nit',index:'nit', width:220,sortable:false,resizable:false,hidden:true},
										{name:'direccion',index:'direccion', width:220,sortable:false,resizable:false,hidden:true},
										{name:'id_estado',index:'id_estado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'estado',index:'estado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'area',index:'area', width:220,sortable:false,resizable:false},
										{name:'zona',index:'zona', width:220,sortable:false,resizable:false,hidden:true},
										{name:'apartado',index:'apartado', width:220,sortable:false,resizable:false,hidden:true},
										{name:'telefono1',index:'telefono1', width:220,sortable:false,resizable:false},
										{name:'telefono2',index:'telefono2', width:220,sortable:false,resizable:false,hidden:true},
										{name:'fax1',index:'fax1', width:220,sortable:false,resizable:false,hidden:true},
										{name:'fax2',index:'fax2', width:220,sortable:false,resizable:false,hidden:true},
										{name:'pag_web',index:'pag_web', width:220,sortable:false,resizable:false},
										{name:'correo',index:'correo', width:220,sortable:false,resizable:false,hidden:true},
										{name:'contacto',index:'contacto', width:220,sortable:false,resizable:false},
										{name:'cedula',index:'cedula', width:220,sortable:false,resizable:false,hidden:true},
										{name:'cargo',index:'cargo', width:220,sortable:false,resizable:false,hidden:true},
										{name:'auxiliar',index:'auxiliar', width:220,sortable:false,resizable:false,hidden:true},
										{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('vista_id_agencia_naviera').value = ret.id;
									getObj('sareta_agencia_naviera_rp_texto_nombre_agencia_naviera').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_rp_agencia_naviera_consulta').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre_organismo").focus();
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
	<img id="form_rp_sareta_agencia_naviera_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_agencia_naviera_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_agencia_naviera_consulta" id="form_rp_agencia_naviera_consulta">
	<table width="590" class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Agencia Naviera</th>
	  </tr>
		
		<tr>
          <th width="157">Nombre:</th>
		  <td width="421"><ul class="input_con_emergente">
              <li>
                <input name="sareta_agencia_naviera_rp_texto_nombre_agencia_naviera" type="text" id="sareta_agencia_naviera_rp_texto_nombre_agencia_naviera"    size="50" maxlength="30" message="Escribir el Nombre de la Agencia Naviera<br><br> Para Seleccionar Todo un Listado de Agencias Navieras Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z 0-9 &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z 0-9 &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_agencia_naviera_rp_id_agencia_naviera" name="sareta_agencia_naviera_rp_id_agencia_naviera"/>
              <li id="sareta_agencia_naviera_rp_btn_consultar_agencia_naviera" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>