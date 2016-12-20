<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script>
var dialog;
$("#parametro_cxp_db_btn_guardar").click(function() {
	
	if(($('#form_db_parametro_cxp').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/cuentas_por_pagar/parametros_cxp/db/sql.parametro_cxp.php",
			data:dataForm('form_db_parametro_cxp'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_parametro_cxp');
					getObj('parametro_cxp_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
					getObj('parametro_cxp_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#parametro_cxp_db_btn_eliminar").click(function() {
  
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/cuentas_por_pagar/parametros_cxp/db/sql.eliminar.php",
			data:dataForm('form_db_parametro_cxp'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('parametro_cxp_db_btn_eliminar').style.display='none';
					getObj('parametro_cxp_db_btn_actualizar').style.display='none';
					getObj('parametro_cxp_db_btn_cancelar').style.display='';
					getObj('parametro_cxp_db_btn_consultar').style.display='';
					getObj('parametro_cxp_db_btn_guardar').style.display='';
					clearForm('form_db_parametro_cxp');
					getObj('parametro_cxp_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
					getObj('parametro_cxp_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
  }
);

//
//
//

$("#parametro_cxp_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/cuentas_por_pagar/parametros_cxp/db/vista.grid_parametro_cxp.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Parametros', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre_organismo").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/parametros_cxp/db/sql_parametro_cxp.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre_organismo").keypress(function(key)
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
							var busq_nombre= jQuery("#parametro_cxp_db_nombre_organismo").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/parametros_cxp/db/sql_parametro_cxp.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/cuentas_por_pagar/parametros_cxp/db/sql_parametro_cxp.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/parametros_cxp/db/sql_parametro_cxp.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Organismo','A&ntilde;o','Fecha Ciere mes','Fecha Ciere Anual','Comentario','ultimo_mes'],
								colModel:[
									{name:'id_parametros_cxp',index:'id_parametros_cxp', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:20,sortable:false,resizable:false},
									{name:'fecha_cierre_mes',index:'fecha_cierre_mes', width:50,sortable:false,resizable:false},
									{name:'fecha_cierre_anual',index:'fecha_cierre_anual', width:50,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ultimo',index:'ultimo', width:50,sortable:false,resizable:false,hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('parametro_cxp_db_id_parametros_cxp').value = ret.id_parametros_cxp;
									getObj('parametro_cxp_db_anio').value = ret.ano;
									getObj('parametro_cxp_db_fecha_cierre_mes').value = ret.fecha_cierre_mes.substr(0, 10);
									getObj('parametro_cxp_db_fecha_cierre_anual').value = ret.fecha_cierre_anual.substr(0, 10);
									getObj('parametro_cxp_db_comentario').value = ret.comentario;
									getObj('parametro_cxp_db_btn_cancelar').style.display='';
									getObj('parametro_cxp_db_btn_eliminar').style.display='';
									getObj('parametro_cxp_db_btn_actualizar').style.display='';
									getObj('parametro_cxp_db_btn_guardar').style.display='none';
									getObj('parametro_cxp_db_ultimo_mes').value=ret.ultimo;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre_organismo").focus();
								$('#parametro_cxp_db_nombre_organismo').alpha({allow:' '});
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
//
//
//
/*$("#parametro_cxp_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/cxp/parametro_cxp/db/grid_parametro_cxp.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Parametro cxp', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:900,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cxp/parametro_cxp/db/sql_grid_parametro_cxp.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Organismo','A&ntilde;o','Precompromiso','Compromiso','Ultimo mes','Fecha Ciere mes','Fecha Ciere Anual','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'organismo',index:'organismo', width:100,sortable:false,resizable:false},
									{name:'anio',index:'anio', width:150,sortable:false,resizable:false},
									{name:'precompromiso',index:'precompromiso', width:150,sortable:false,resizable:false,hidden:true},
									{name:'compromiso',index:'compromiso', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ultimo_mes',index:'ultimo_mes', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha_mes',index:'fecha_mes', width:250,sortable:false,resizable:false},
									{name:'fecha_anual',index:'fecha_anual', width:250,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:250,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('parametro_cxp_db_id_parametros_cxp').value = ret.id_parametros_cxp;
									getObj('parametro_cxp_db_anio').value = ret.anio;
									getObj('parametro_cxp_db_num_precompromiso').value = ret.precompromiso;
									getObj('parametro_cxp_db_num_compromiso').value = ret.compromiso;
									getObj('parametro_cxp_db_ultimo_mes').value = ret.ultimo_mes;
									getObj('parametro_cxp_db_fecha_cierre_mes').value = ret.fecha_mes.substr(0, 10);
									getObj('parametro_cxp_db_fecha_cierre_anual').value = ret.fecha_anual.substr(0, 10);
									getObj('parametro_cxp_db_comentario').value = ret.comentario;
									getObj('parametro_cxp_db_btn_cancelar').style.display='';
									getObj('parametro_cxp_db_btn_eliminar').style.display='';
									getObj('parametro_cxp_db_btn_actualizar').style.display='';
									getObj('parametro_cxp_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									if(xhr.status == 200){
									setBarraEstado("No se han registrado datos en este organismo para buscar");
									}else{
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
									}
								},															
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

$("#parametro_cxp_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_parametro_cxp').jVal())
	{
		$.ajax (
		{
			url: "modulos/cuentas_por_pagar/parametros_cxp/db/sql.actualizar.php",
			data:dataForm('form_db_parametro_cxp'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('parametro_cxp_db_btn_eliminar').style.display='none';
					getObj('parametro_cxp_db_btn_actualizar').style.display='none';
					getObj('parametro_cxp_db_btn_guardar').style.display='';
					clearForm('form_db_parametro_cxp');
					getObj('parametro_cxp_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
					getObj('parametro_cxp_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#parametro_cxp_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('parametro_cxp_db_btn_cancelar').style.display='';
	getObj('parametro_cxp_db_btn_eliminar').style.display='none';
	getObj('parametro_cxp_db_btn_actualizar').style.display='none';
	getObj('parametro_cxp_db_btn_guardar').style.display='';
	clearForm('form_db_parametro_cxp');
	getObj('parametro_cxp_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
	getObj('parametro_cxp_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
});


$('#parametro_cxp_db_anio').numeric({allow:''});
$('#parametro_cxp_db_num_precompromiso').numeric({allow:'_'});
$('#parametro_cxp_db_num_compromiso').numeric({allow:'_'});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});

</script>
<div id="botonera">
	<img id="parametro_cxp_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
  <img id="parametro_cxp_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="parametro_cxp_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" onClick="Application.evalCode('win_popup_armador', true);" />
	<img id="parametro_cxp_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
<img id="parametro_cxp_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onClick="guardar()" /></div>
<form name="form_db_parametro_cxp" id="form_db_parametro_cxp">
  <table class="cuerpo_formulario">
  <tr>
			<th colspan="2" class="titulo_frame"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />
			Par&aacute;metro Cuentas Por Cobrar </th>
	</tr>
		
		<tr>
			<th>A&ntilde;o :						</th>
			<td>	<input name="parametro_cxp_db_anio" type="text" id="parametro_cxp_db_anio" size="6" maxlength="4" message="Introduzca el a&ntilde;o del cxp" jVal="{valid:/^[0-9]{1,60}$/, message:'A&ntilde;o Invalida', styleType:'cover'}"	jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['A&ntilde;o: '+$(this).val()]}"></td>
		</tr>
		
			<tr><th>Ultimo mes Cerrado:</th>
			<td><select name="parametro_cxp_db_ultimo_mes" id="parametro_cxp_db_ultimo_mes" style="width:90px; min-width:90px;">
					<option value="1">Enero</option>
					<option value="2">Febrero</option>
					<option value="3">Marzo</option>
					<option value="4">Abril</option>
					<option value="5">Mayo</option>
					<option value="6">Junio</option>
					<option value="7">Julio</option>
					<option value="8">Agosto</option>
					<option value="9">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12">Diciembre</option>
				</select></td>
		</tr>
		
		<tr>
			<th>Fecha Cierre Mes :	</th>
			<td><input name="parametro_cxp_db_fecha_cierre_mes" type="text" id="parametro_cxp_db_fecha_cierre_mes" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha Cierre Mes">
		  <button type="reset" id="parametro_cxp_db_fecha_mes_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "parametro_cxp_db_fecha_cierre_mes",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "parametro_cxp_db_fecha_mes_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>			</td>
		</tr>
		<tr>
			<th>Fecha Cierre Anual :	</th>
			<td><input name="parametro_cxp_db_fecha_cierre_anual" type="text" id="parametro_cxp_db_fecha_cierre_anual" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha Cierre Mes">
		  <button type="reset" id="parametro_cxp_db_fecha_anual_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "parametro_cxp_db_fecha_cierre_anual",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "parametro_cxp_db_fecha_anual_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>			</td>
		</tr>
		<tr>
			<th>Comentario :			</th>
			<td>	<textarea name="parametro_cxp_db_comentario" cols="60" id="parametro_cxp_db_comentario" message="Introduzca un Comentario"></textarea></td>
		</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;
      <input type="hidden" name="parametro_cxp_db_id_parametros_cxp" id="parametro_cxp_db_id_parametros_cxp" /></td>
	</table>
	<span class="bottom_frame"><span class="titulo_frame">
	</span></span>
</form>