<script type="text/javascript" language="JavaScript">   
var lastsel,idd,monto;

$("#requisicion_seguimiento_co_btn_consultar_unidad").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor de mierda...");
		$.ajax ({
		    url:"modulos/adquisiones/requisiciones/co/grid_unidad_ejecutora_para.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
			
				dialog=new Boxy(data,{ title: 'Consulta Emergente de unidad', modal: true,center:false,x:0,y:0,show:false});
				//dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad', modal: true,center:false,x:0,y:0,show:false });								
				dialog_reload=function gridReload(){// alert('aqui');
					var busq_nombre= jQuery("#requisicion_seguimiento_co_unidad").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/requisiciones/co/cmb.sql.unidad.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
				}
				// se llama a la funcion crear_grid
				crear_grid();
		//	
				var timeoutHnd; 
				var flAuto = true;
				// cierre de ventana emergente
				$("#requisicion_seguimiento_co_unidad").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				/// recargar grid
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#requisicion_seguimiento_co_unidad").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/requisiciones/co/cmb.sql.unidad.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
			}
		});
	
	////////////////// crea la tabla 
							function crear_grid()
							{
								jQuery("#list_grid_"+nd).jqGrid
								({
									width:500,
									height:300,
									recordtext:"Registro(s)",
									loadtext: "Recuperando Información del Servidor",		
									url:'modulos/adquisiones/requisiciones/co/cmb.sql.unidad.php?nd='+nd,
									datatype: "json",
									colNames:['id','Codigo','Unidad'],
									colModel:[
										{name:'id',index:'id', width:10,sortable:false,resizable:false,hidden:true},
										{name:'codigo',index:'codigo', width:20,sortable:false,resizable:false},
										{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
									],
									pager: $('#pager_grid_'+nd),
									rowNum:20,
									rowList:[20,50,100],
									imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
									onSelectRow: function(id){
										var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj("requisicion_seguimiento_co_unidad_id").value=id;							
										getObj("requisicion_seguimiento_co_unidad_codigo").value=ret.codigo;
										getObj("requisicion_seguimiento_co_unidad_nombre").value=ret.nombre;
										dialog.hideAndUnload();
									},
									loadComplete:function (id){
										setBarraEstado("");
										dialog.center();
										dialog.show();
										$('#requisicion_seguimiento_co_unidad').alpha({allow:'().,'});
									},
									loadError:function(xhr,st,err){ 
										setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
									},															
									sortname: 'nombre',
									viewrecords: true,
									sortorder: "asc"
								});
							}
});
// -----------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------

$("#requisicion_seguimiento_co_btn_consultar_requisicion").click(function() {
if(getObj('requisicion_seguimiento_co_unidad_id').value!="")	
{	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/co/grid_requisicion.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Requisici&oacute;n', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/requisiciones/co/cmb.sql.requisicion.php?nd='+nd+'&unidad='+getObj('requisicion_seguimiento_co_unidad_id').value,
								datatype: "json",
								colNames:['id','Numero Requisicion','Asunto'],
								colModel:[
									{name:'id',index:'id', width:60,sortable:false,resizable:false,hidden:true},
									{name:'numero_requisicion',index:'numero_requisicion', width:60,sortable:false,resizable:false},
									{name:'asunto',index:'asunto', width:150,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('requisicion_seguimiento_co_id_requisicion').value = ret.id;
									getObj('requisicion_seguimiento_co_nro_requisicion').value = ret.numero_requisicion;
									dialog.hideAndUnload();
//									alert('modulos/adquisiones/requisiciones/co/sql.consulta_seguimiento.php?unidad='+getObj("requisicion_seguimiento_co_unidad_id").value+'&requisicion='+ret.numero_requisicion);
									//alert('modulos/adquisiones/requisiciones/co/cmb.sql.requisicion_detalle.php?nd='+nd+'&unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value+'&requisicion='+ret.numero_requisicion);
									//jQuery("#list_requisicion_seguimiento").setGridParam({url:'modulos/adquisiones/requisiciones/co/cmb.sql.requisicion_detalle.php?nd='+nd+'&unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value+'&requisicion='+ret.numero_requisicion,page:1}).trigger("reloadGrid");
									jQuery("#list_requisicion_seguimiento").setGridParam({url:'modulos/adquisiones/requisiciones/co/sql.consulta_seguimiento.php?unidad='+getObj("requisicion_seguimiento_co_unidad_id").value+'&requisicion_id='+ret.id+'&requisicion='+ret.numero_requisicion,page:1}).trigger("reloadGrid");
									//													alert('modulos/adquisiones/requisiciones/co/sql.consulta_seguimiento.php?unidad='+getObj("requisicion_seguimiento_co_unidad_id").value+'&requisicion_id='+ret.id+'&requisicion='+ret.numero_requisicion);
									//jQuery("#list_requisicion_seguimiento").setGridParam({url:'modulos/adquisiones/requisiciones/co/sql.consulta_seguimiento.php?unidad='+getObj("requisicion_seguimiento_co_unidad_id").value+'&requisicion='+getObj('requisicion_seguimiento_co_nro_requisicion').value,page:1}).trigger("reloadGrid");
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
function consulta_automatica_requiscion()
{
	$.ajax({
			url:"modulos/adquisiones/requisiciones/co/cmb.sql.requisicion_auto.php",
            data:dataForm('form_co_requisicion_seguimiento'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset){
				//alert('aqui');
				//	jQuery("#list_requisicion_seguimiento").setGridParam({url:'modulos/adquisiones/requisiciones/co/sql.consulta_seguimiento.php?unidad='+getObj("requisicion_seguimiento_co_unidad_id").value+'&requisicion='+getObj('requisicion_seguimiento_co_nro_requisicion').value,page:1}).trigger("reloadGrid");
					jQuery("#list_requisicion_seguimiento").setGridParam({url:'modulos/adquisiones/requisiciones/co/sql.consulta_seguimiento.php?unidad='+getObj("requisicion_seguimiento_co_unidad_id").value+'&requisicion='+getObj('requisicion_seguimiento_co_nro_requisicion').value,page:1}).trigger("reloadGrid");
					getObj('requisicion_seguimiento_co_id_requisicion').value = recordset;
				}else{  
				//alert('aquica');
					getObj('requisicion_seguimiento_co_nro_requisicion').value ="";
					//getObj('covertir_req_cot_nro_requi_id').value="";
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA REQUISICION NO EXISTE</p></div>",true,true);
				}
				
			 }
		});	 	 
}
//////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º
$("#list_requisicion_seguimiento").jqGrid({ 
	height: 100,
	width: 750,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",       
   	url:'modulos/adquisiones/requisiciones/co/sql.consulta_seguimiento.php?unidad='+getObj("requisicion_seguimiento_co_unidad_id").value+'requisicion='+getObj('requisicion_seguimiento_co_nro_requisicion').value,
	datatype: "json",
   	colNames:['Creando Requisicion','Enviado a Compras', 'Solicitud Cotizacion', 'Respuesta Cotizacion','Preorden','Orden','Compromiso'],
   	colModel:[
   		{name:'creando',index:'id', width:122,align:"center"},
   		{name:'compra',index:'invdate', width:114,align:"center"},
   		{name:'solicitud',index:'solicitud', width:125,align:"center"},
   		{name:'cotizacion',index:'cotizacion', width:128, align:"center"},
   		{name:'preorden',index:'preorden', width:75, align:"center"},		
   		{name:'orden',index:'orden', width:75,align:"center"},		
   		{name:'compromiso',index:'compromiso', width:85,align:"center"}		
   	],
   	rowNum:5,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_requisicion_seguimiento'),
   	sortname: 'numero_requisicion',
    viewrecords: true,
    sortorder: "asc",
	//y57height: 200
}).navGrid("#pager_requisicion_seguimiento",{search :false,edit:false,add:false,del:false});

</script>
<form  method="post" name="form_co_requisicion_seguimiento" id="form_co_requisicion_seguimiento">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Seguimiento de Requisici&oacute;n</th>  
		</tr>
		<tr>
			<th>Unidad Solicitante</th>
			<td>
				<table width="100%" class="clear" style="width:463px">
					<tr>
						<td>
							<input type="text" size="5" name="requisicion_seguimiento_co_unidad_codigo" id="requisicion_seguimiento_co_unidad_codigo">
							<input type="text" style="width:62ex;" name="requisicion_seguimiento_co_unidad_nombre" id="requisicion_seguimiento_co_unidad_nombre">
						</td>
						<td style="width:5px">
							<img id="requisicion_seguimiento_co_btn_consultar_unidad" class="btn_consulta_emergente"  src="imagenes/null.gif" />
							<input type="hidden" name="requisicion_seguimiento_co_unidad_id" id="requisicion_seguimiento_co_unidad_id">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Requisici&oacute;n</th>
			<td>
				<input type="text" size="10" name="requisicion_seguimiento_co_nro_requisicion" id="requisicion_seguimiento_co_nro_requisicion">
				<input type="hidden"  name="requisicion_seguimiento_co_id_requisicion" id="requisicion_seguimiento_co_id_requisicion">
				<img id="requisicion_seguimiento_co_btn_consultar_requisicion" class="btn_consulta_emergente"  src="imagenes/null.gif" />
			</td>
		</tr>
		<tr>
			<td class="celda_consulta" colspan="2">
				<table id="list_requisicion_seguimiento" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_requisicion_seguimiento" class="scroll" style="text-align:center;"></div> 
				<br />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>