<?php
session_start();
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script>
var dialog;
$("#presupuesto_ley_db_btn_guardar").click(function() {
	if(($('#form_pr_cierre_presupuesto_ley').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/presupuesto/presupuesto_ley/pr/sql.cierre_proyecto_presupuesto.php",
			data:dataForm('form_pr_cierre_presupuesto_ley'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_cierre_presupuesto_ley');
					getObj("presupuesto_ley_pr_id_unidad_ejecutora").value='';	
					getObj("presupuesto_ley_pr_nombre_unidad_ejecutora").value='';	
				}
				else if(html=="EOF")
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
					getObj("presupuesto_ley_pr_id_unidad_ejecutora").value='';	
					getObj("presupuesto_ley_pr_nombre_unidad_ejecutora").value='';	 	
				}
				else
				{
					setBarraEstado(html,true,true);
				}
			}
		});
	}
});

//------------------ partida ---------------------------
$("#presupuesto_ley_db_btn_consultar_unidad_ejecutora").click(function() {
	if (getObj("presupuesto_ley_pr_opt_todas").checked  == false){
	
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/presupuesto/presupuesto_ley/pr/grid_unidad_ejecutora.php", { },
							function(data)
							{								
									dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
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
									url:'modulos/presupuesto/presupuesto_ley/pr/cmb.sql.unidad.php?nd='+nd,
									datatype: "json",
									colNames:['id','Unidad'],
									colModel:[
										{name:'id',index:'id', width:100,sortable:false,resizable:false,hidden:true},
										{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
									],
									pager: $('#pager_grid_'+nd),
									rowNum:20,
									rowList:[20,50,100],
									imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
									onSelectRow: function(id){
										var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj("presupuesto_ley_pr_id_unidad_ejecutora").value=id;							
										getObj("presupuesto_ley_pr_nombre_unidad_ejecutora").value=ret.nombre;
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
									sortname: 'nombre',
									viewrecords: true,
									sortorder: "asc"
								});
							}
	}
});


$("#presupuesto_ley_pr_opt_todas").click(function() {
	getObj('presupuesto_ley_pr_id_unidad_ejecutora').disabled='true';
	getObj('presupuesto_ley_pr_nombre_unidad_ejecutora').disabled='true';	
		getObj('presupuesto_ley_pr_id_unidad_ejecutora').value='';
	getObj('presupuesto_ley_pr_nombre_unidad_ejecutora').value='';	

	//getObj('presupuesto_ley_pr_tr_unidad_ejecutora').style.display='none';
});

$("#presupuesto_ley_pr_opt_unidad").click(function() {
	getObj('presupuesto_ley_pr_id_unidad_ejecutora').disabled='';
	getObj('presupuesto_ley_pr_nombre_unidad_ejecutora').disabled='';
	getObj('presupuesto_ley_pr_tr_unidad_ejecutora').style.display='';
});

/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
/*-------------------   Fin Validaciones  ---------------------------*/
</script>

<div id="botonera">
	<img id="presupuesto_ley_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_pr_cierre_presupuesto_ley" id="form_pr_cierre_presupuesto_ley">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:6px;" align="absmiddle" /> Ante Proyecto Presupuesto de Ley
			</th>
		</tr>
		<tr>
			<th>Selecci&oacute;n</th>
			<td>
				<input id="presupuesto_ley_pr_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="presupuesto_ley_pr_cmb_ano" id="presupuesto_ley_pr_cmb_ano">
					<?
					$anio_inicio=2010;
					$anio_fin=2011;
					while($anio_inicio <= $anio_fin)
					{
					?>
					<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
				</select>			
			</td>
		</tr>
		<tr id="presupuesto_ley_pr_tr_unidad_ejecutora">
			<th>Unidad Ejecutora </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="hidden" name="presupuesto_ley_pr_id_unidad_ejecutora" id="presupuesto_ley_pr_id_unidad_ejecutora" />
						<input  name="presupuesto_ley_pr_nombre_unidad_ejecutora" type="text" id="presupuesto_ley_pr_nombre_unidad_ejecutora" size="24" 
							message="Introduzca un Nombre para la Unidad Ejecutora." 
							jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
							jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" 
							readonly="true" 
							disabled="disabled"  
						>
					</li>
					<li id="presupuesto_ley_db_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<!--		<tr>
		  <th>Fecha Cierre:</th>
	      <td><label>
		<input readonly="true" type="text" name="presupuesto_ley_pr_fecha_cierre" id="presupuesto_ley_pr_fecha_cierre" size="7" value="<?//php echo date("d/m/Y")?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_cierre_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "presupuesto_ley_pr_fecha_cierre",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_cierre_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
</label></td>
		</tr>-->

		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>