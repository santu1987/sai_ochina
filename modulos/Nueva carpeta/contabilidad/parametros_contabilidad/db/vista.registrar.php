<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>


<script>
var dialog;
$("#parametro_contabilidad_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('parametro_contabilidad_db_btn_cancelar').style.display='';
	getObj('parametro_contabilidad_db_btn_eliminar').style.display='none';
	getObj('parametro_contabilidad_db_btn_actualizar').style.display='none';
	getObj('parametro_contabilidad_db_btn_guardar').style.display='';
	clearForm('form_db_parametro_contabilidad');
	getObj('parametro_contabilidad_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
	getObj('parametro_contabilidad_db_fecha_cierre_ano').value="<?= date ('d/m/Y') ?>";	
	
});
$("#parametro_contabilidad_db_btn_guardar").click(function() {
	
	if(($('#form_db_parametro_contabilidad').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/contabilidad/parametros_contabilidad/db/sql.parametro_contabilidad.php",
			data:dataForm('form_db_parametro_contabilidad'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_parametro_contabilidad');
					document.form_db_parametro_contabilidad.parametro_contabilidad_db_numeracion.selectedIndex=1;
					getObj('parametro_contabilidad_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
					getObj('parametro_contabilidad_db_fecha_cierre_ano').value="<?= date ('d/m/Y') ?>";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
//************************************************************************
$("#parametro_contabilidad_db_btn_consultar").click(function() {
															// alert('aqui');
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/parametros_contabilidad/db/grid_contabilidad.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Parametros Contabilidad', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/parametros_contabilidad/co/cmb.sql.parametro_contabilidad.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Fecha Cierre Mes', 'Fecha Cierre Anual', 'Cuenta Superavit', 'Numeraci&oacute;n Autom&aacute;tica','Comentario','a&ntilde;o','ultimo_mes'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cierre_mensual',index:'cierre_mensual', width:250,sortable:false,resizable:false},
									{name:'cierre_anual',index:'cierre_anual', width:250,sortable:false,resizable:false},
									{name:'cuenta_superavit',index:'cuenta_superavit', width:70,sortable:false,resizable:false,hidden:true},
									{name:'nro_automatico',index:'nro_automatico', width:70,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ultimo_m',index:'ultimo_m', width:50,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('parametro_contabilidad_db_id').value = ret.id;
									anio = ret.cierre_mensual.substr(0, 4);
									mes = ret.cierre_mensual.substr(5, 2);
									dia = ret.cierre_mensual.substr(8, 2);
									anio2 = ret.cierre_anual.substr(0, 4);
									mes2 = ret.cierre_anual.substr(5, 2);
									dia2 = ret.cierre_anual.substr(8, 2);
									getObj('parametro_contabilidad_db_anio').value =ret.ano;
									getObj('parametro_contabilidad_db_ultimo_mes').value =ret.ultimo_m;
									getObj('parametro_contabilidad_db_fecha_cierre_mes').value = dia+"/"+mes+"/"+anio;
									getObj('parametro_contabilidad_db_fecha_cierre_ano').value = dia2+"/"+mes2+"/"+anio2;
									getObj('parametro_contabilidad_db_cuenta_superavit').value = ret.cuenta_superavit;
									getObj('parametro_contabilidad_db_comentario').value = ret.comentario;
									if (ret.nro_automatico == 't'){
										document.form_db_parametro_contabilidad.parametro_contabilidad_db_numeracion.selectedIndex=0;
									}else{
										document.form_db_parametro_contabilidad.parametro_contabilidad_db_numeracion.selectedIndex=1;
									}
									getObj('parametro_contabilidad_db_btn_guardar').style.display='none';
									getObj('parametro_contabilidad_db_btn_actualizar').style.display='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'id_parametro_analisis_cotizacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#parametro_contabilidad_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_db_parametro_contabilidad').jVal())
	{
		
		$.ajax (
		{
			url: "modulos/contabilidad/parametros_contabilidad/db/sql.actualizar.php",
			data:dataForm('form_db_parametro_contabilidad'),
			type:'POST',
			cache: false,
			success: function(html)
			{	//alert(html);		
				if (html=="No Actualizo")
				{
					getObj('parametro_contabilidad_db_cuenta_superavit').value = "";
					getObj('parametro_contabilidad_db_comentario').value = "";
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('parametro_contabilidad_db_btn_actualizar').style.display='none';
					getObj('parametro_contabilidad_db_btn_guardar').style.display='';
					clearForm('form_db_parametro_contabilidad');
					getObj('parametro_contabilidad_db_fecha_cierre_mes').value = "<?= date("d/m/Y");?>";
					getObj('parametro_contabilidad_db_fecha_cierre_ano').value = "<?= date("d/m/Y");?>";
					
					
				
				}
				
					
			}
				
		});
	}
});

</script>

<div id="botonera">
	<img id="parametro_contabilidad_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
  <img id="parametro_contabilidad_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="parametro_contabilidad_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="parametro_contabilidad_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
<img id="parametro_contabilidad_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" /></div>

<form name="form_db_parametro_contabilidad" id="form_db_parametro_contabilidad">
<input type="hidden" name="parametro_contabilidad_db_id" id="parametro_contabilidad_db_id">
	<table class="cuerpo_formulario">
        <tr>
            <th colspan="2" class="titulo_frame"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />
                Par&aacute;metro  de Contabilidad
            </th>
        </tr>
        <tr>
			<th>A&ntilde;o :						</th>
			<td>	<input name="parametro_contabilidad_db_anio" type="text" id="parametro_contabilidad_db_anio" size="6" maxlength="4" message="Introduzca el a&ntilde;o del cxp" jVal="{valid:/^[0-9]{1,60}$/, message:'A&ntilde;o Invalida', styleType:'cover'}"	jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['A&ntilde;o: '+$(this).val()]}"></td>
		</tr>
        			<tr><th>Ultimo mes Cerrado:</th>
			<td><select name="parametro_contabilidad_db_ultimo_mes" id="parametro_contabilidad_db_ultimo_mes" style="width:90px; min-width:90px;">
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
			<td><input name="parametro_contabilidad_db_fecha_cierre_mes" type="text" id="parametro_contabilidad_db_fecha_cierre_mes" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha Cierre Mes">
		  <button type="reset" id="parametro_contabilidad_db_fecha_mes_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "parametro_contabilidad_db_fecha_cierre_mes",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "parametro_contabilidad_db_fecha_mes_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
			</td>
		</tr>
        
        <tr>
			<th>Fecha Cierre Anual :	</th>
			<td><input name="parametro_contabilidad_db_fecha_cierre_ano" type="text" id="parametro_contabilidad_db_fecha_cierre_ano" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha Cierre Anual">
		  <button type="reset" id="parametro_contabilidad_db_fecha_ano_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "parametro_contabilidad_db_fecha_cierre_ano",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "parametro_contabilidad_db_fecha_ano_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
			</td>
		</tr>
        <tr>
			<th>Numeraci&oacute;n Automatica :			</th>
			<td>	
				<select name="parametro_contabilidad_db_numeracion" id="parametro_contabilidad_db_numeracion" style="width:50px; min-width:50px;">
					<option value="1">Si</option>
					<option value="2">No</option>
				</select>
			</td>
		</tr>
        <tr>
        	<th>Nro Cuenta Superavit</th>
            <td><input name="parametro_contabilidad_db_cuenta_superavit" type="text" id="parametro_contabilidad_db_cuenta_superavit" maxlength="12" message="Introduzca el Nro de Cuenta que utilizara para guardar el superavit"></td>
        </tr>
        <tr>
			<th>Comentario :			</th>
			<td>	<textarea name="parametro_contabilidad_db_comentario" cols="60" id="parametro_contabilidad_db_comentario" message="Introduzca un Comentario"></textarea></td>
		</tr>
        <tr>
            <td colspan="2" class="bottom_frame">
        <tr>			
       </table>
        <span class="bottom_frame"><span class="titulo_frame">
        </span></span>
</form>  