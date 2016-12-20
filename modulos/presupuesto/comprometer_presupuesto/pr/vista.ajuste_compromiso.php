<script>
var dialog;
//************************************************************************************************************************
$("#ajuste_pr_btn_consulta_compromiso").click(function() {
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
			url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.numero_compromiso.php?nd='+nd,
			datatype: "json",
			colNames:['Compromiso','N&ordm; Orden','Concepto'],
			colModel:[
				{name:'compromiso',index:'compromiso', width:20,sortable:false,resizable:false},
				{name:'numero',index:'numero', width:20,sortable:false,resizable:false},
				{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);
				getObj("ajuste_pr_nro_compromiso").value=ret.compromiso;
				getObj("ajuste_pr_nro_orden").value=ret.numero;
				dialog.hideAndUnload();
				//alert('modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.disponibilida_presupuesto_orden.php?numero_compromiso='+getObj('compromiso_pr_pre_compromiso').value);
				//jQuery("#list_ajuste_compromiso").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/pr/cmb.sql.orden_detalle.php?cotizacion='+ret.preorden,page:1}).trigger("reloadGrid");
				
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
});

</script>
<form  name="form_ajuste_compromiso" id="form_ajuste_compromiso">
<table  class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Ajuste de Compromiso</th>
	</tr>

	<tr>
		<th>N&deg; Compromiso </th>
		<td><input type="text" size="9" maxlength="8" name="ajuste_pr_nro_compromiso" id="ajuste_pr_nro_compromiso" readonly>
			<img id="ajuste_pr_btn_consulta_compromiso" class="btn_consulta_emergente" src="imagenes/null.gif"/>
		</td>
	</tr>
	<tr>
		<th>Nro Orden</th>
		<td><input type="text" size="9" maxlength="8" name="ajuste_pr_nro_orden" id="ajuste_pr_nro_orden" readonly></td>
	</tr>
	<tr>
		<td class="celda_consulta" colspan="2">
			<table id="list_ajuste_compromiso" class="scroll" cellpadding="0" cellspacing="0"></table> 
			<div id="pager_ajuste_compromiso" class="scroll" style="text-align:center;"></div> 
			<br />
		</td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table>
</form>