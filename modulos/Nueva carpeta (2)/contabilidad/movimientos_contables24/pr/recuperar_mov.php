<script language="javascript">
$("#contabilidad_recuve_pr_btn_consultar_tipo").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
					$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?nd='+nd,
								datatype: "json",
								colNames:['id','C&oacute;digo','Denominaci&oacute;n','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'comen',index:'comen', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_recuve_pr_tipo').val(ret.codigo);
									$('#contabilidad_recuve_pr_tipo_id').val(ret.id);
									//$('#cuentas_por_pagar_integracion_tipo_nombre').val(ret.nombre);
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
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
function limpiar_recuve()
{
	clearForm('form_recuve_mov');
	jQuery("#list_comprobante_recuve").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_recuve.php'}).trigger("reloadGrid");
											//alert(url);


}
$("#contabilidad_recuve_pr_btn_consultar_n_comprobante").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/cuentas_por_pagar/documentos/rp/grid_pagar.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Comprobantes Contables', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
		({
			width:350,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando InformaciÛn del Servidor",		
			url:'modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?nd='+nd,
			datatype: "json",
			colNames:['Id','N Comprobante Int'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#numero_comprobante_cont_recuve').val(ret.id);
				$('#contabilidad_recuve_pr_numero_comprobante').val(ret.numero_comprobante);
					jQuery("#list_comprobante_recuve").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_recuve.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_recuve_pr_numero_comprobante').value,page:1}).trigger("reloadGrid");
				url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_recuve.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_recuve_pr_numero_comprobante').value,
	//alert(url);
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
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});	
$("#contabilidad_recuve_pr_btn_guardar").click(function() {
if(getObj('ids_comprobante').value=="")
{	
		alert("seleccione un auxiliar");

}		
else
{		
							if($('#form_recuve_mov').jVal())
								{ //alert("entro");
								setBarraEstado(mensaje[esperando_respuesta]);
								$.ajax (
									{
										url: "modulos/contabilidad/movimientos_contables/pr/sql.guardar_recuve.php",
										data:dataForm('form_recuve_mov'),
										type:'POST',
										cache: false,
										success: function(html)
										{
											recordset=html;
											recordset = recordset.split("*");
											alert(html);
											if (recordset=="Registrado")
											{
												setBarraEstado(mensaje[actualizacion_exitosa],true,true);
												limpiar_recuve();	
											}
											else if (recordset=="NoActualizo")
											{
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZ” LA OPERACI”N</p></div>",true,true);

											}
											
										}
									});
								}	
}
});
$("#contabilidad_recuve_pr_btn_cancelar").click(function() {
 limpiar_recuve();
 });
function consulta_manual_mov_contables()
{
	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_mov_cod.php",
            data:dataForm('form_recuve_mov'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
				//alert(html);
					if(html!="vacio")
					{
						$('#contabilidad_recuve_pr_numero_comprobante').val(recordset[1]);
						jQuery("#list_comprobante_recuve").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_recuve.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_recuve_pr_numero_comprobante').value,page:1}).trigger("reloadGrid");
						//$('#numero_comprobante_cont_recuve').val(recordset[0]);
					}else
					if(html=="vacio")
					{
						getObj('contabilidad_recuve_pr_numero_comprobante').value="";
						getObj('numero_comprobante_cont_recuve').value="";
					}
			 }
		});	 	 
}
/////////////////////////////////////////////////////
//-------------------------------------------------------------------------------------------------------------------------------------
var lastsel,idd,monto;
numero_comprobante=getObj('contabilidad_recuve_pr_numero_comprobante').value;
url3='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_recuve.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante;
//alert(url3);	
$("#list_comprobante_recuve").jqGrid({
	height: 100,
	width: 570,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_recuve.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
//	+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value
	datatype: "json",
	colNames:['Id','Organismo','Ano','Mes','tipo','Comprobante','Secuencia','Cuenta Contable','Desc','REF','Debe','Haber','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','id_cc','codigo_tipo_comp','estatus','req_aux','req_ueject','req_proyecto','req_utf','desc2'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'codigo_organismo',index:'codigo_organismo', width:20,hidden:true},
			{name:'ano_comprobante',index:'ano_comprobante', width:20,hidden:true},
			{name:'mes_comprobante',index:'mes_comprobante', width:20,hidden:true},
			{name:'codigo_tipo_comprobante',index:'codigo_tipo_comprobante', width:20,hidden:true},
			{name:'numero_comprobante',index:'numero_comprobante', width:20,hidden:true},
			{name:'secuencia',index:'secuencia', width:20,hidden:true},
			{name:'cuenta_contable',index:'cuenta_contable',width:70},
			{name:'descripcion',index:'descripcion',width:70},
			{name:'ref',index:'ref',width:20},
			{name:'monto_debito',index:'monto_debito',width:50},
			{name:'monto_credito',index:'monto_credito',width:50},
			{name:'fecha_comprobante',index:'fecha_comprobante',width:50,hidden:true,hidden:true},
			{name:'codigo_auxiliar',index:'codigo_auxiliar',width:50,hidden:true},
			{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora',width:50,hidden:true},
			{name:'codigo_proyecto',index:'codigo_proyecto',width:50,hidden:true,hidden:true},
			{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:50,hidden:true},
			{name:'id_cc',index:'id_cc',width:50,hidden:true},
			{name:'codigo_tipo_comp',index:'codigo_tipo_comp',width:50,hidden:true},
			{name:'estatus',index:'estatus',width:50,hidden:true},
			{name:'req_aux',index:'req_aux',width:50,hidden:true},
			{name:'req_ueject',index:'req_ueject',width:50,hidden:true},
			{name:'req_proyecto',index:'req_proyecto',width:50,hidden:true},
			{name:'req_utf',index:'req_utf',width:50,hidden:true},
			{name:'descripcion2',index:'descripcion2',width:60,hidden:true}
   	],
	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_recuve'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	
		onSelectRow: function(id){
		var ret = jQuery("#list_comprobante_recuve").getRowData(id);
		idd="";lastsel="";
		s = jQuery("#list_comprobante_recuve").getGridParam('selarrrow');
		idd = ret.id;
		if(idd && idd!==lastsel){
			
		//	alert(s);
			getObj('ids_comprobante').value=s;
			
		}
			
	},
	onSelectAll:function(id){
		var ret = jQuery("#list_comprobante_recuve").getRowData(id);
		s = jQuery("#list_comprobante_recuve").getGridParam('selarrrow');
		/*if(getObj('tesoreria_cheques_db_n_precheque').value=="")
		{
			getObj('tesoreria_cheques_pr_ret_islr').value='0,00';
		}*/
		//alert(s);
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('ids_comprobante').value=s;
		
			}
}

}).navGrid("#pager_recuve",{search :false,edit:false,add:false,del:false});
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


$('#contabilidad_recuve_pr_numero_comprobante').numeric({});

//$('#tesoreria_cheques_db_concepto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄·ÈÌÛ˙¡…Õ”⁄0123456789,.-'});
$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	
</script>

   <div id="botonera"><img id="contabilidad_recuve_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="contabilidad_recuve_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_recuve_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="contabilidad_recuve_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img id="contabilidad_recuve_pr_btn_imprimir_automatico"  class="btn_imprimir" src="imagenes/null.gif"  style="display:none" />
	<img id="contabilidad_recuve_pr_btn_imprimir"  class="btn_imprimir_vista_previa" src="imagenes/null.gif"  style="display:none" /></div>
<form method="post" id="form_recuve_mov" name="form_recuve_mov">
<input type="hidden"  id="tesoreria_vista_cheque" name="tesoreria_vista_cheque"/>
<input type="hidden" name="orden_pago_pr_cot_select" id="orden_pago_pr_cot_select"  />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Recuperar Registros de Movimientos Contables </th>
	</tr>
	
    <tr >
	<th>N&uacute;mero Comprobante:</th>
		<td>
		<ul class="input_con_emergente">
		  <li>
			<input type="text" id="contabilidad_recuve_pr_numero_comprobante" name="contabilidad_recuve_pr_numero_comprobante"    message="Introduzca n comprobante" size="12" maxlength="12" onblur="consulta_manual_mov_contables();"  
				jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
				
	
    <!-- onchange="consulta_automatica_comprobante()" onblur="consulta_automatica_comprobante()" -->
    		<input type="hidden" id="numero_comprobante_cont_recuve" name="numero_comprobante_cont_recuve" />
		    <input type="hidden" id="ids_comprobante" name="ids_comprobante" />
		  </li>
			<li id="contabilidad_recuve_pr_btn_consultar_n_comprobante" class="btn_consulta_emergente"></li>
			</ul>	  </td>
	</tr>
	
	<tr>
		<td class="celda_consulta" colspan="2">
				<table id="list_comprobante_recuve" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_recuve" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
	</tr>
	<tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>
  <input  name="tesoreria_banco_db_id" type="hidden" id="" />
</form>
