<?php
session_start();
?>

<script>
var dialog;

$("#form_rp_sareta_tipo_documento_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/sareta/tipo_documento/rp/vista.lst.tipo_documento.php¿busq_nombre_tipo_documento="+getObj('sareta_tipo_documento_rp_texto_nombre_tipo_documento').value; 
		openTab("Listado/Tipo de Documento",url);
//		+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value
});
$("#form_rp_sareta_tipo_documento_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_tipo_documento_consulta');
});

//-------------------------------------------------------------------
var dialog;
$("#sareta_tipo_documento_rp_btn_consultar_tipo_documento").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/tipo_documento/rp/grid_consulta_tipo_documento.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Documentos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/tipo_documento/rp/sql_grid_tipo_documento.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/tipo_documento/rp/sql_grid_tipo_documento.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/tipo_documento/rp/sql_grid_tipo_documento.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/tipo_documento/rp/sql_grid_tipo_documento.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Nombre','nombre','Factor','Vida Propia','Pago Inmediato','Pago Posterior','Calculo de Mora','Secuencia Activa','Secuencia_paso','id_numero_control','Ultimo Numero','obs'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'nombre_paso',index:'nombre_paso', width:220,sortable:false,resizable:false,hidden:true},

									{name:'factor',index:'factor', width:220,sortable:false,resizable:false},
									{name:'vida_propia',index:'vida_propia', width:220,sortable:false,resizable:false},
									{name:'Pg_inmediato',index:'Pg_inmediato', width:220,sortable:false,resizable:false},
									{name:'Pg_posterior',index:'Pg_posterior', width:220,sortable:false,resizable:false},
									{name:'mora',index:'mora', width:220,sortable:false,resizable:false},
									{name:'secuencia_activa',index:'secuencia_activa', width:220,sortable:false,resizable:false},
									{name:'Secuencia_paso',index:'Secuencia_paso', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_numero_control',index:'id_numero_control', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ultimo_numero',index:'ultimo_numero', width:220,sortable:false,resizable:false},
									
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj('sareta_tipo_documento_rp_texto_nombre_tipo_documento').value = ret.nombre_paso;
								
									dialog.hideAndUnload();
									$('#form_rp_tipo_documento_consulta').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_rp_nombre").focus();
								$('#parametro_cxp_rp_nombre').alpha({allow:'0123456789 '});
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
	<img id="form_rp_sareta_tipo_documento_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_rp_sareta_tipo_documento_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_tipo_documento_consulta" id="form_rp_tipo_documento_consulta">
	<table width="590" class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Lista de Tipo de Documentos</th>
	  </tr>
		
		<tr>
          <th width="157">Tipo de Documento</th>
		  <td width="421"><ul class="input_con_emergente">
              <li>
                <input name="sareta_tipo_documento_rp_texto_nombre_tipo_documento" type="text" id="sareta_tipo_documento_rp_texto_nombre_tipo_documento"    size="50" maxlength="30" message="Escribir la Descripci&oacute;n del Tipo de Documento  <br><br> Para Seleccionar Todo un Listado de Tipo de Documentos Solo Oprimir  el Botón (Imprimir)<strong></strong>" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
                
                <input type="hidden" id="sareta_tipo_documento_rp_id_tipo_documento" name="sareta_tipo_documento_rp_id_tipo_documento"/>
              <li id="sareta_tipo_documento_rp_btn_consultar_tipo_documento" class="btn_consulta_emergente"></li>
		    </ul></td>
	  </tr><tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>