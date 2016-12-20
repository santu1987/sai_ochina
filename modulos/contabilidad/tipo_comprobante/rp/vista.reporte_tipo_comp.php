<?php
session_start();
$fecha_comprobante=date("d/m/Y");
?>
<script type='text/javascript'>
var dialog;
$("#contabilidad_tipo_comprobante_rp_btn_imp").click(function()
 {
		tipo=getObj('contabilidad_rel_comp_pr_tipo_id').value;
		fecha=getObj('contabilidad_comp_pr_fecha').value;;
		url="pdf.php?p=modulos/contabilidad/tipo_comprobante/rp/vista.reporte_tipo_comprobante.phpøfecha="+fecha+"@tipo="+tipo;
		openTab("TipoComp",url);
	//	alert(url);
});
$("#contabilidad_tipo_comprobante_rp_btn_cancelar").click(function() {
setBarraEstado("");
clearForm('form_contabilidad_db_tipo_comprobante');
getObj('contabilidad_comp_pr_fecha').value= "<?=$fecha_comprobante;?>";

});
$("#contabilidad_comp_btn_consultar_tipo_rp").click(function() {
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
								url:'modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo.php?nd='+nd,
								datatype: "json",
								colNames:['id','C&oacute;digo','Denominaci&oacute;n','Comentario','numero'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'comen',index:'comen', width:100,sortable:false,resizable:false,hidden:true},
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_rel_comp_pr_tipo').val(ret.codigo);
									$('#contabilidad_rel_comp_pr_tipo_id').val(ret.id);
									//$('#contabilidad_comp_pr_numero_comprobante').val(ret.numero);
								//	consulta_automatica_comprobante();
								//	limpiar_algunos();		
												

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
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_tipo_comprobante_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#contabilidad_tipo_comprobante_db_codigo_comprobante').numeric({});

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

<div id="botonera">
	<img id="contabilidad_tipo_comprobante_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_tipo_comprobante_rp_btn_imp" class="btn_imprimir"src="imagenes/null.gif" /></div>
<form method="post" id="form_contabilidad_db_tipo_comprobante" name="form_contabilidad_db_tipo_comprobante">
	<input   type="hidden" name="contabilidad_tipo_comprobante_db_id"  id="contabilidad_tipo_comprobante_db_id" />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="3"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Relaci&oacute;n de Comprobantes Emitidos seg&uacute;n Tipo </th>
	</tr>
	<tr>
		<th>
			Tipo de Comprobante:		</th>
			
		<td>
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="contabilidad_rel_comp_pr_tipo" id="contabilidad_rel_comp_pr_tipo"  size='12' maxlength="12" onchange="consulta_manual_tipo_comprobante()" 
				message="Introduzca el tipo de cuenta" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		        <input type="text" id="contabilidad_rel_comp_pr_tipo_id" name="contabilidad_rel_comp_pr_tipo_id"  value=""
				 jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
			 </li>
			<li id="contabilidad_comp_btn_consultar_tipo_rp" class="btn_consulta_emergente"></li>
			</ul>		</td>
	</tr>
		<tr>
		<th>
			Fecha:		</th>
		<td>
			  <input alt="date" type="text" name="contabilidad_comp_pr_fecha" id="contabilidad_comp_pr_fecha" size="10" value="<? echo ($fecha_comprobante);?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" 
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="contabilidad_comp_pr_fecha_oculto" id="contabilidad_comp_pr_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
				  <button type="reset" id="contabilidad_comp_pr_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "contabilidad_comp_pr_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "contabilidad_comp_pr_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("contabilidad_comp_pr_fecha").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_inicial_rp_fecha_hasta").value.MMDDAAAA() );
										
									}
							});
					</script>		</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
	</table>	
</form>