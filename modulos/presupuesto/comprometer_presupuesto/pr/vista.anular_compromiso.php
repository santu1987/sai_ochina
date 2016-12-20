<script>
var dialog;
$("#anular_compromiso_pr_btn_guardar").click(function() {
		setBarraEstado(mensaje[esperando_respuesta]);
		
anular_compromiso_numero = getObj("anular_compromiso_numero").value;
anular_compromiso_unidad = getObj("anular_compromiso_unidad").value;
	
//	Boxy.ask("<iframe style='width:0px; height:0px; border:0px' src="+url+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />¿Desea anular el Compromiso nro "+anular_compromiso_numero+" de la "+anular_compromiso_unidad+"?</p></div>", ["SI"],["NO"], 
	if (getObj("anular_compromiso_hasta_orden").checked  == true){
		todo = 0;
		parte = "";
	}else{
		todo = 1;
		parte = ", La requision asociada con este compromiso prodria tener otras ordenes y compromisos hechos";
	}
	if(confirm("¿Desea anular el Compromiso nro "+anular_compromiso_numero+" de la "+anular_compromiso_unidad+"?"+parte)) 
	{	
	// "modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.anular_compromiso.php?cod="+getObj("anular_compromiso_unidad_ejecutora_codigo").value
			$.ajax (
			{
			url: "modulos/presupuesto/comprometer_presupuesto/pr/sql.anular.php?anulacion="+todo,
				data:dataForm('form_anular_compromiso'),
				type:'POST',
				cache: false,
				success: function(html)
				{
				resultado = html.split(",");
					if (resultado[0]=="Registrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL COMPROMISO FUE ANULADO</p></div>",true,true);
						//setBarraEstado(html);
					url="pdf.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.anular_compromiso.php¿cod="+getObj('anular_compromiso_unidad_ejecutora_codigo').value+"@unidad="+getObj('anular_compromiso_unidad_ejecutora_codigo').value; 
					Boxy.ask("<iframe style='width:0px; height:0px; border:0px' src="+url+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REALIZANDO IMPRESIÓN</p></div>", ["CERRAR"], 
					function(val)
					 {
                		if(val=="CERRAR")
						{
							setTimeout("limpiar()",200);
						}   
					 }, {title:"SAI-OCHINA"});
						
						clearForm('form_anular_compromiso');
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
$("#anular_compromiso_pr_btn_consulta_nro_orden").click(function() {
if(getObj("anular_compromiso_unidad_id").value != ""){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/pr/grid_compromiso.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Compromiso', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:300,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.anular_numero_compromiso.php?nd='+nd,
								datatype: "json",
								colNames:['Compromiso','Orden','idproveedor','Proveedor','codigo','unidad'],
								colModel:[
									{name:'compromiso',index:'compromiso', width:50,sortable:false,resizable:false},
									{name:'orden',index:'preorden', width:50,sortable:false,resizable:false},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad',index:'unidad', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:15,
								rowList:[15,30,45],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("anular_compromiso_numero").value=ret.compromiso;
									getObj("anular_compromiso_unidad_ejecutora_codigo").value=ret.codigo;
									getObj("anular_compromiso_ejecutora_unidad").value=ret.unidad;
									dialog.hideAndUnload();
									//alert('modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero);
									//jQuery("#list_preorden").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.orden_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.preorden,page:1}).trigger("reloadGrid");
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'numero_compromiso',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//------------------------------------------------------------------------------------------------------------------------
//***********************************************************************************************************************
$("#anular_compromiso_pr_btn_cancelar").click(function() {
		getObj('anular_compromiso_unidad_codigo').value='';
		getObj('anular_compromiso_unidad').value='';
		getObj('anular_compromiso_numero').value='';
	});
//------------------------------------------------------------------------------------------------------------------------
//***********************************************************************************************************************
$("#anular_compromiso_pr_btn_consulta_unidad").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/pr/grid_compromiso.php", { },
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
								url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.unidad.php?nd='+nd,
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
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('anular_compromiso_unidad_id').value = ret.id;
									getObj('anular_compromiso_unidad_codigo').value = ret.codigo;
									getObj('anular_compromiso_unidad').value = ret.nombre;
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
			url:"modulos/presupuesto/comprometer_presupuesto/pr/sql_grid_unidad_ejecutora.php",
            data:dataForm('form_anular_compromiso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('anular_compromiso_unidad_id').value = recordset[0];
				getObj('anular_compromiso_unidad').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('anular_compromiso_unidad_id').value = "";
				getObj('anular_compromiso_unidad').value="";		
				}
			 }
		});	 	 
}
$('#anular_compromiso_unidad_codigo').change(consulta_automatica_unidad_ejecutora);
</script>

<div id="botonera">
	<img id="anular_compromiso_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<!--<img id="anular_compromiso_pr_btn_imprimir" class="btn_imprimir"src="imagenes/null.gif" style="display:none"  />		
	<img id="anular_compromiso_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>-->
	<img id="anular_compromiso_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>
<form name="form_anular_compromiso" id="form_anular_compromiso">
	<table class="cuerpo_formulario" >
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Anular Compromiso </th>
		</tr>
		<tr>
			<th>Tipo de anulaci&oacute;n</th>
			<td>
				Compromiso y Orden &nbsp;<input type="radio" name="anular_compromiso_option" id="anular_compromiso_hasta_orden" value="0" checked="checked"/>&nbsp;&nbsp;&nbsp;&nbsp;
                Todo el proceso &nbsp;<input type="radio" name="anular_compromiso_option" id="anular_compromiso_total" value="1" />
			</td>
		</tr>
        <tr>
			<th>Unidad Solicitante</th>
			<td>
				<table class="clear">
					<tr>
						<td >
							<input type="text" name="anular_compromiso_unidad_codigo" id="anular_compromiso_unidad_codigo" maxlength="5" size="5" 
							onchange="consulta_automatica_unidad_ejecutora" onclick="consulta_automatica_unidad_ejecutora"
							message="Introduzca un Codigo para la unidad ejecutora."  
							jVal="{valid:/^[0-9]{4}$/, message:'Codigo Invalido', styleType:'cover'}">
							<input type="text" name="anular_compromiso_unidad" id="anular_compromiso_unidad" maxlength="100" readonly size="60" >						
							<img id="anular_compromiso_pr_btn_consulta_unidad" class="btn_consulta_emergente" src="imagenes/null.gif"/>
							<input type="hidden" name="anular_compromiso_unidad_id" id="anular_compromiso_unidad_id" readonly>							
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>N&ordm; Compromiso </th>
			<td>
				<table class="clear" style="width:50">
					<tr>
						<td><input type="text" name="anular_compromiso_numero" id="anular_compromiso_numero" maxlength="10" ></td>
						<td><img id="anular_compromiso_pr_btn_consulta_nro_orden" class="btn_consulta_emergente" src="imagenes/null.gif"/></td>
					</tr>
				</table>
			</td>
		</tr>
        		<tr>
			<th>Unidad Ejecutora</th>
			<td>
				<table class="clear">
					<tr>
						<td >
							<input type="text" name="anular_compromiso_unidad_ejecutora_codigo" id="anular_compromiso_unidad_ejecutora_codigo" maxlength="5" readonly size="5" >
							<input type="text" name="anular_compromiso_ejecutora_unidad" id="anular_compromiso_ejecutora_unidad" maxlength="100" readonly size="60" >						
							<!--onchange="consulta_automatica_unidad_ejecutora_ejecutora" onclick="consulta_automatica_unidad_ejecutora_ejecutora" 
                            <img id="anular_compromiso_pr_btn_consulta_ejecutora_unidad" class="btn_consulta_emergente" src="imagenes/null.gif"/>
							<input type="hidden" name="anular_compromiso_unidad_ejecutora_id" id="anular_compromiso_unidad_ejecutora_id" readonly>	-->						
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>