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
	if(($('#form_pr_cierre_presupuesto_ley_mensual').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/presupuesto/presupuesto_ley/pr/sql.cierre_presupuesto_ley_mensual.php",
			data:dataForm('form_pr_cierre_presupuesto_ley_mensual'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_pr_cierre_presupuesto_ley_mensual');
					getObj("cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora").value='';	
					getObj("cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora").value='';	
				}
				else if(html=="EOF")
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					getObj("cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora").value='';	
					getObj("cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora").value='';	 	
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
	if (getObj("cierre_presupuesto_ley_mensual_pr_opt_todas").checked  == false){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	/*	$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/pr/sql_grid_unidad_ejecutora2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
			
				dialog=new Boxy(data,{ title: 'Consulta Emergente de unidad', modal: true,center:false,x:0,y:0,show:false});
				//dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad', modal: true,center:false,x:0,y:0,show:false });								
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#cierre_presupuesto_ley_mensual_pr_unidad").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/pr/cmb.sql.unidad.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
				}
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#cierre_presupuesto_ley_mensual_pr_unidad").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				//
				//
				//
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
							var busq_nombre= jQuery("#cierre_presupuesto_ley_mensual_pr_unidad").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/pr/cmb.sql.unidad.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
			}
		});
	
	
	*/
//		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/presupuesto/presupuesto_ley/pr/grid_unidad_ejecutora_para2.php", { },
							function(data)
							{								
									dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad', modal: true,center:false,x:0,y:0,show:false });								
									setTimeout(crear_grid,100);
							});
							function crear_grid()
							{//alert('aqui');
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
									imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
									onSelectRow: function(id){
										var ret = jQuery("#list_grid_"+nd).getRowData(id);
										getObj("cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora").value=id;							
										getObj("cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora").value=ret.nombre;
										dialog.hideAndUnload();
									},
									loadComplete:function (id){
										setBarraEstado("");
										dialog.center();
										dialog.show();
										$('#cierre_presupuesto_ley_mensual_pr_unidad').alpha({allow:'().,'});
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


$("#cierre_presupuesto_ley_mensual_pr_opt_todas").click(function() {
	getObj('cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora').disabled='true';
	getObj('cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora').disabled='true';	
		getObj('cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora').value='';
	getObj('cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora').value='';	

	//getObj('cierre_presupuesto_ley_mensual_pr_tr_unidad_ejecutora').style.display='none';
});

$("#cierre_presupuesto_ley_mensual_pr_opt_unidad").click(function() {
	getObj('cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora').disabled='';
	getObj('cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora').disabled='';
	getObj('cierre_presupuesto_ley_mensual_pr_tr_unidad_ejecutora').style.display='';
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

<form method="post" name="form_pr_cierre_presupuesto_ley_mensual" id="form_pr_cierre_presupuesto_ley_mensual">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Cierre Presupuesto Mensual
			</th>
		</tr>
		<tr>
			<th>Selecci&oacute;n</th>
			<td>
				<input id="cierre_presupuesto_ley_mensual_pr_opt_todas" name="cierre_presupuesto_ley_mensual_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="cierre_presupuesto_ley_mensual_pr_opt_unidad" name="cierre_presupuesto_ley_mensual_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="cierre_presupuesto_ley_mensual_pr_cmb_ano" id="cierre_presupuesto_ley_mensual_pr_cmb_ano">
					<?
					$anio_inicio=2011;
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
		<!--<tr id="cierre_presupuesto_ley_mensual_pr_tr_unidad_ejecutora">
			<th>Unidad Ejecutora </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="hidden" name="cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora" id="cierre_presupuesto_ley_mensual_pr_id_unidad_ejecutora" />
						<input  name="cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora" type="text" id="cierre_presupuesto_ley_mensual_pr_nombre_unidad_ejecutora" size="24" 
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
		</tr>-->
		<!--		<tr>
		  <th>Fecha Cierre:</th>
	      <td><label>
		<input readonly="true" type="text" name="cierre_presupuesto_ley_mensual_pr_fecha_cierre" id="cierre_presupuesto_ley_mensual_pr_fecha_cierre" size="7" value="<?//php echo date("d/m/Y")?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_cierre_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cierre_presupuesto_ley_mensual_pr_fecha_cierre",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_cierre_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
</label></td>
		</tr>
-->
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>