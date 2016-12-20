<script>
var dialog;
//************************************************************************************************************************
$("#compromiso_pr_btn_guardar").click(function() {
	if(($('#form_compromiso').jVal()))
	{
	
	if(getObj("compromiso_pr_nro_orden").value==0){
		alert('Debe tener un nuemro de Orden');
	}else{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/presupuesto/comprometer_presupuesto/pr/sql.comprometer.php",
			data:dataForm('form_compromiso'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split(".");
				if (resultado[0]=="Registrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La Orden fue Comprometida. Numero de Compromiso "+resultado[1]+" </p></div>",true,true);
					//setBarraEstado(mensaje["<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />Orden Comprometida. Numero de Compromiso "+resultado[1]+" </p></div>"],true,true);
					clearForm('form_compromiso');
					//setBarraEstado(resultado[1]);
				jQuery("#list_compromiso").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.orden_detalle.php?cotizacion=0',page:1}).trigger("reloadGrid");
				}
				else if (resultado[0]=="error")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />Renglon(es) "+resultado[1]+" no tiene(n) disponibilidad presupuestaria</p></div>",true,true);
				}
				/*else 
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />PRESUPUESTO CERRADO</p></div>",true,true);
				}*/
				else
				{
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
		}
	}
});
//************************************************************************************************************************
$("#compromiso_pr_btn_consulta_pre_compromiso").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/pr/grid_compromiso.php", { },
	function(data)
	{								
			dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Pre Compromiso', modal: true,center:false,x:0,y:0,show:false });								
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
			url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.numero_preorden.php?nd='+nd,
			datatype: "json",
			colNames:['PreCompromiso','N&ordm; Orden','Concepto'],
			colModel:[
				{name:'preorden',index:'preorden', width:20,sortable:false,resizable:false},
				{name:'numero',index:'numero', width:20,sortable:false,resizable:false},
				{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);
				getObj("compromiso_pr_pre_compromiso").value=ret.preorden;
				getObj("compromiso_pr_nro_orden").value=ret.numero;
				dialog.hideAndUnload();
				alert('modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.disponibilida_presupuesto_orden.php?numero_precompromiso='+getObj('compromiso_pr_pre_compromiso').value);
				jQuery("#list_compromiso").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.orden_detalle.php?cotizacion='+ret.preorden,page:1}).trigger("reloadGrid");
				
			},
			loadComplete:function (id){
				setBarraEstado("");
				dialog.center();
				dialog.show();
			},
			loadError:function(xhr,st,err){ 
				setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
			},															
			sortname: 'numero_precompromiso',
			viewrecords: true,
			sortorder: "asc"
		});
	}
});
//************************************************************************************************************************
//************************************************************************************************************************ 
var lastsel,idd,monto;

$("#list_compromiso").jqGrid({
	height: 115,
	width: 800,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.orden_detalle.php?numero_precompromiso='+getObj('compromiso_pr_pre_compromiso').value,
	datatype: "json",
   	colNames:['Id','Renglon','Descripcion','Cantidad','Unidad Medida','Monto T.','Iva','Total','Partida','Disponibilidad','Disponible'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'compromiso_pr_idd',index:'compromiso_pr_idd', width:35},
			{name:'compromiso_pr_producto',index:'compromiso_pr_producto', width:168},
			{name:'compromiso_pr_cantidad',index:'compromiso_pr_cantidad', width:40},
			{name:'compromiso_pr_unidad_medida',index:'compromiso_pr_unidad_medida', width:52},
			{name:'compromiso_pr_monto',index:'compromiso_pr_monto', width:55},
			{name:'compromiso_pr_precio',index:'compromiso_pr_precio', width:55},
			{name:'compromiso_pr_iva',index:'compromiso_pr_iva', width:35},
			{name:'compromiso_pr_partida',index:'compromiso_pr_partida', width:53},
			{name:'disponibilida',index:'disponibilida', width:57, align:"center"},
			{name:'disponible',index:'disponible', width:57, align:"center"}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_compromiso'),
   	sortname: 'secuencia',
    viewrecords: true,
    sortorder: "asc",
	afterInsertRow: function(rowid, aData){
    	switch (aData.disponibilida) {
    		case 'No tiene disponibilidad para este Reglon':
    			jQuery("#list_compromiso").setCell(rowid,'disponibilida','',{color:'red'});
    		break;
    		case 'Tiene disponibilidad':
    			jQuery("#list_compromiso").setCell(rowid,'disponibilida','',{color:'blue'});
    		break;
    		
    	}
    }
}).navGrid("#pager_compromiso",{search :false,edit:false,add:false,del:false});
//************************************************************************************************************************
//***********************************************************************************************************************
function consulta_automatica_pre_compromiso()
{
	$.ajax({
			url:"modulos/presupuesto/comprometer_presupuesto/pr/sql_grid_pre_compromiso.php",
            data:dataForm('form_compromiso'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('compromiso_pr_pre_compromiso').value = recordset[0];
				getObj('compromiso_pr_nro_orden').value=recordset[1];
				//alert('modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?unidad='+recordset[0]);
				//jQuery("#list_presupuesto_aprobado").setGridParam({url:'modulos/presupuesto/presupuesto_ley/pr/cmd.sql.presupuesto.php?unidad='+recordset[0],page:1}).trigger("reloadGrid");
				jQuery("#list_compromiso").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.orden_detalle.php?cotizacion='+recordset[0],page:1}).trigger("reloadGrid");
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('compromiso_pr_pre_compromiso').value = "";
				getObj('compromiso_pr_nro_orden').value="";		
				}
			 }
		});	 	 
}
//***********************************************************************************************************************
$('#compromiso_pr_pre_compromiso').change(consulta_automatica_pre_compromiso);
</script>
<div id="botonera">
	<img id="compromiso_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"/>
<!--	<img id="compromiso_pr_btn_imprimir" class="btn_imprimir"src="imagenes/null.gif"/>		-->
	<img id="compromiso_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif" />
</div>

<form name="form_compromiso" id="form_compromiso">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Comprometer Presupuesto</th>
		</tr>
		<tr>
			<th>Nro PreCompromiso</th>
			<td><input type="text" size="9" maxlength="6" name="compromiso_pr_pre_compromiso" id="compromiso_pr_pre_compromiso"
				onchange="consulta_automatica_pre_compromiso" onclick="consulta_automatica_pre_compromiso" message="Introduzca la Número del PreCompromiso."
				jval="{valid:/^[0123456789]{3,8}$/, message:'Producto/Servicio Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['Producto/Servicio: '+$(this).val()]}">
				<img id="compromiso_pr_btn_consulta_pre_compromiso" class="btn_consulta_emergente" src="imagenes/null.gif"/>
			</td>
		</tr>
		<tr>
			<th>Nro Orden</th>
			<td><input type="text" size="9" maxlength="8" name="compromiso_pr_nro_orden" id="compromiso_pr_nro_orden" readonly></td>
		</tr>
		<tr>
			<td class="celda_consulta" colspan="2">
				<table id="list_compromiso" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_compromiso" class="scroll" style="text-align:center;"></div> 
				<br />
			</td>
		</tr>		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>