<script>
var dialog;
$("#anular_preorden_pr_btn_guardar").click(function() {
		setBarraEstado(mensaje[esperando_respuesta]);
		
anular_preorden_numero = getObj("anular_preorden_numero").value;
anular_preorden_unidad = getObj("anular_preorden_unidad").value;
	if(confirm("¿Desea anular la orden nº "+anular_preorden_numero+" de la "+anular_preorden_unidad+"?, Presione aceptar para anular")) 
	{	
		if(confirm("¿Desea transformar en la orden anulada en preorden?, Presione aceptar convertirla en preorden")) 
		{
			$.ajax (
			{
			url: "modulos/adquisiones/orden/pr/sql.convertir_pre_orden.php?orden="+anular_preorden_numero,
				data:dataForm('form_anular_preorden'),
				type:'POST',
				cache: false,
				success: function(html)
				{
				resultado = html.split(",");
					if (resultado[0]=="Registrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN FUE CONVERTIDA EN LA PRE ORDEN CON EXITO,<BR>PRE_ORDEN N&ordm; "+resultado[1]+"</p></div>",true,true);

						//setBarraEstado(mensaje[registro_exitoso],true,true); anular_orden
						//jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php',page:1}).trigger("reloadGrid");
						clearForm('form_anular_preorden');
					}
					else if (resultado[0]=="Existe")
					{
						setBarraEstado(mensaje[registro_existe],true,true);
					}else if (resultado[0]=="noRegistrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN YA TIENE UN COMPROMISO, <BR>DEBE ANULAR PRIMERO EL COMPROMISO</p></div>",true,true);
					}
					else
					{
						setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
						setBarraEstado(html);
					}
				}
			});
		}else{
			$.ajax (
			{
			url: "modulos/adquisiones/orden/pr/sql.anular_orden.php?orden="+anular_preorden_numero,
				data:dataForm('form_anular_preorden'),
				type:'POST',
				cache: false,
				success: function(html)
				{
				resultado = html.split(",");
					if (html=="Registrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN FUE ANNULADA CON EXITO</p></div>",true,true);

						//setBarraEstado(mensaje[registro_exitoso],true,true); 
						//jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php',page:1}).trigger("reloadGrid");
						clearForm('form_anular_preorden');
					}
					else if (html=="Existe")
					{
						setBarraEstado(mensaje[registro_existe],true,true);
					}else if (html=="noRegistrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN YA TIENE UN COMPROMISO, <BR>DEBE ANULAR PRIMERO EL COMPROMISO</p></div>",true,true);
					}
					else
					{
						setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
						setBarraEstado(html);
					}
				}
			});
		}
	}

		/*$.ajax (
		{
		url: "modulos/adquisiones/orden/pr/sql.convertir_pre_orden.php",
			data:dataForm('form_ordenes'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split(",");
				if (resultado[0]=="Registrado")
				{
					getObj("ordenes_pr_nro_pre_orden").value=resultado[1];
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php',page:1}).trigger("reloadGrid");
					//clearForm('form_ordenes');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});*/
});
$("#anular_preorden_pr_btn_consulta_nro_orden").click(function() {
if(getObj("anular_preorden_unidad_id").value != ""){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/orden/pr/grid_orden.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Orden de Compra/Servicio', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/orden/pr/cmb.sql.numero_pre_orden.php?nd='+nd+'&unidad='+getObj("anular_preorden_unidad_id").value,
								datatype: "json",
								colNames:['Pre-Orden','Orden','Cotizacion','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora'],
								colModel:[
									{name:'preorden',index:'preorden', width:35,sortable:false,resizable:false},
									{name:'orden',index:'orden', width:35,sortable:false,resizable:false},
									{name:'cotizacion',index:'cotizacion', width:35,sortable:false,resizable:false},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'preorden', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:15,
								rowList:[15,30,45],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("anular_preorden_numero").value=ret.preorden;
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero);
									//jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.preorden,page:1}).trigger("reloadGrid");
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'numero_orden_compra_servicio',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//------------------------------------------------------------------------------------------------------------------------
//***********************************************************************************************************************
$("#anular_preorden_pr_btn_cancelar").click(function() {
		getObj('anular_preorden_unidad_codigo').value='';
		getObj('anular_preorden_unidad').value='';
		getObj('anular_preorden_numero').value='';
	});
//------------------------------------------------------------------------------------------------------------------------
//***********************************************************************************************************************
$("#anular_preorden_pr_btn_consulta_unidad").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/orden/pr/grid_orden.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/orden/pr/cmb.sql.unidad?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Unidad'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:15,
								rowList:[15,30,50],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('anular_preorden_unidad_id').value = ret.id;
									getObj('anular_preorden_unidad_codigo').value = ret.codigo;
									getObj('anular_preorden_unidad').value = ret.nombre;
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
								sortname: 'codigo_unidad',
								viewrecords: true,
								sortorder: "asc"
							});
						}

});
//***********************************************************************************************************************
//***********************************************************************************************************************
function consulta_automatica_unidad_ejecutora()
{
	$.ajax({
			url:"modulos/adquisiones/orden/pr/sql_grid_unidad_ejecutora.php",
            data:dataForm('form_anular_preorden'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('anular_preorden_unidad_id').value = recordset[0];
				getObj('anular_preorden_unidad').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('anular_preorden_unidad_id').value = "";
				getObj('anular_preorden_unidad').value="";		
				}
			 }
		});	 	 
}
$('#anular_preorden_unidad_codigo').change(consulta_automatica_unidad_ejecutora);
</script>

<div id="botonera">
	<img id="anular_preorden_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="anular_preorden_pr_btn_imprimir" class="btn_imprimir"src="imagenes/null.gif" style="display:none"  />		
	<img id="anular_preorden_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>-->
	<img id="anular_preorden_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>
<form name="form_anular_preorden" id="form_anular_preorden">
	<table class="cuerpo_formulario" >
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Anular Pre-Orden de Compra/Servicio</th>
		</tr>
		<tr>
			<th>Unidad Solicitante</th>
			<td>
				<table class="clear">
					<tr>
						<td >
							<input type="text" name="anular_preorden_unidad_codigo" id="anular_preorden_unidad_codigo" maxlength="5" size="5" 
							onchange="consulta_automatica_unidad_ejecutora" onclick="consulta_automatica_unidad_ejecutora"
							message="Introduzca un Codigo para la unidad ejecutora."  
							jVal="{valid:/^[0-9]{4}$/, message:'Codigo Invalido', styleType:'cover'}">
							<input type="text" name="anular_preorden_unidad" id="anular_preorden_unidad" maxlength="100" readonly size="60" >						
							<img id="anular_preorden_pr_btn_consulta_unidad" class="btn_consulta_emergente" src="imagenes/null.gif"/>
							<input type="hidden" name="anular_preorden_unidad_id" id="anular_preorden_unidad_id" readonly>							
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>N&ordm; PreOrden</th>
			<td>
				<table class="clear" style="width:50">
					<tr>
						<td><input type="text" name="anular_preorden_numero" id="anular_preorden_numero" maxlength="10" ></td>
						<td><img id="anular_preorden_pr_btn_consulta_nro_orden" class="btn_consulta_emergente" src="imagenes/null.gif"/></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>