<script>
var dialog;
$("#anular_requisicion_pr_btn_guardar").click(function() {
		setBarraEstado(mensaje[esperando_respuesta]);
		
anular_requisicion_numero = getObj("anular_requisicion_numero").value;
anular_requisicion_unidad = getObj("anular_requisicion_unidad").value;
	if(confirm("¿Desea anular la requisicion nº "+anular_requisicion_numero+" de la "+anular_requisicion_unidad+"?, Presione aceptar para anular")) 
	{	
	
			$.ajax (
			{
			url: "modulos/adquisiones/requisiciones/pr/sql.anular_requision.php",
				data:dataForm('form_anular_requisicion'),
				type:'POST',
				cache: false,
				success: function(html)
				{
				resultado = html.split(",");
					if (resultado[0]=="Registrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REQUISICION ANULADA</p></div>",true,true);

						//setBarraEstado(mensaje[registro_exitoso],true,true); anular_orden
						//jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php',page:1}).trigger("reloadGrid");
						clearForm('form_anular_requisicion');
					}
					else if (resultado[0]=="Existe")
					{
						setBarraEstado(mensaje[registro_existe],true,true);
					}else if (resultado[0]=="noRegistrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA REQUISICION YA TIENE UNA COTIZACION, <BR>DEBE ANULAR PRIMERO EL COMPROMISO</p></div>",true,true);
					}
					else
					{
						setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
						setBarraEstado(html);
					}
				}
			});
		
	}

		
});
$("#anular_requisicion_pr_btn_consulta_nro_orden").click(function() {
if(getObj("anular_requisicion_unidad_id").value != ""){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Requisicion', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/requisiciones/pr/cmb.sql.anular_numero_requision.php?nd='+nd+'&unidad='+getObj("anular_requisicion_unidad_id").value,
								datatype: "json",
								colNames:['id_requisicion_encabezado','Nro Requisicion','Asunto'],
								colModel:[
									{name:'id_requisicion_encabezado',index:'id_requisicion_encabezado', width:40,sortable:false,resizable:false,hidden:true},
									{name:'numero_requisicion',index:'numero_requisicion', width:50,sortable:false,resizable:false},
									{name:'asunto',index:'asunto', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:15,
								rowList:[15,30,45],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("anular_requisicion_numero").value=ret.numero_requisicion;
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
								sortname: 'numero_requisicion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//------------------------------------------------------------------------------------------------------------------------
//***********************************************************************************************************************
$("#anular_requisicion_pr_btn_cancelar").click(function() {
		getObj('anular_requisicion_unidad_codigo').value='';
		getObj('anular_requisicion_unidad').value='';
		getObj('anular_requisicion_numero').value='';
	});
//------------------------------------------------------------------------------------------------------------------------
//***********************************************************************************************************************
$("#anular_requisicion_pr_btn_consulta_unidad").click(function() {
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
									getObj('anular_requisicion_unidad_id').value = ret.id;
									getObj('anular_requisicion_unidad_codigo').value = ret.codigo;
									getObj('anular_requisicion_unidad').value = ret.nombre;
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
            data:dataForm('form_anular_requisicion'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('anular_requisicion_unidad_id').value = recordset[0];
				getObj('anular_requisicion_unidad').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('anular_requisicion_unidad_id').value = "";
				getObj('anular_requisicion_unidad').value="";		
				}
			 }
		});	 	 
}
$('#anular_requisicion_unidad_codigo').change(consulta_automatica_unidad_ejecutora);
</script>

<div id="botonera">
	<img id="anular_requisicion_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="anular_requisicion_pr_btn_imprimir" class="btn_imprimir"src="imagenes/null.gif" style="display:none"  />		
	<img id="anular_requisicion_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>-->
	<img id="anular_requisicion_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>
<form name="form_anular_requisicion" id="form_anular_requisicion">
	<table class="cuerpo_formulario" >
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Anular Requisici&oacute;n </th>
		</tr>
		<tr>
			<th>Unidad Solicitante</th>
			<td>
				<table class="clear">
					<tr>
						<td >
							<input type="text" name="anular_requisicion_unidad_codigo" id="anular_requisicion_unidad_codigo" maxlength="5" size="5" 
							onchange="consulta_automatica_unidad_ejecutora" onclick="consulta_automatica_unidad_ejecutora"
							message="Introduzca un Codigo para la unidad ejecutora."  
							jVal="{valid:/^[0-9]{4}$/, message:'Codigo Invalido', styleType:'cover'}">
							<input type="text" name="anular_requisicion_unidad" id="anular_requisicion_unidad" maxlength="100" readonly size="60" >						
							<img id="anular_requisicion_pr_btn_consulta_unidad" class="btn_consulta_emergente" src="imagenes/null.gif"/>
							<input type="hidden" name="anular_requisicion_unidad_id" id="anular_requisicion_unidad_id" readonly>							
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>N&ordm; Requisici&oacute;n </th>
			<td>
				<table class="clear" style="width:50">
					<tr>
						<td><input type="text" name="anular_requisicion_numero" id="anular_requisicion_numero" maxlength="10" ></td>
						<td><img id="anular_requisicion_pr_btn_consulta_nro_orden" class="btn_consulta_emergente" src="imagenes/null.gif"/></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>