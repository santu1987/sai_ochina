<script>
var dialog;
$("#presupuesto_ley_aprobado_final_rp_btn_imprimir").click(function() {

alert('aqui');
	//mes_hasta = eval(getObj("presupuesto_apro_rp_cmb_mes_hasta").value);
	//mes_desde = eval(getObj("presupuesto_apro_rp_cmb_mes_desde").value);
	//if (mes_hasta >= mes_desde){
	//////////////////////////////////////////////// TODAS LAS UNIDADES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if((getObj('presupuesto_ley_aprobado_final_rp_todas_unidad').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_ambos').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_accion_especifica_todas_especifica').checked  == true))
		{
		alert('aqui 1');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_ley_todo.php!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}else if((getObj('presupuesto_ley_aprobado_final_rp_todas_unidad').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_todo_proyecto').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_accion_especifica_todas_especifica').checked  == true))
		{
		alert('aqui 2');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_ley_todo_proyecto.php!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}else if((getObj('presupuesto_ley_aprobado_final_rp_todas_unidad').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_todo_accion').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_accion_especifica_todas_especifica').checked  == true))
		{
		alert('aqui 3');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_ley_todo_central.php!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}else if((getObj('presupuesto_ley_aprobado_final_rp_una_unidad').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_ambos').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_accion_especifica_todas_especifica').checked  == true))
		{
		alert('aqui 4');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_ley_todo_unidades.php!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"@unidad="+getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value; 
		}else if((getObj('presupuesto_ley_aprobado_final_rp_todas_unidad').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_un_proyecto').checked  == true) && (getObj('presupuesto_ley_aprobado_final_rp_accion_especifica_todas_especifica').checked  == true))
		{
		alert('aqui 5');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_ley_todo_proyecto.php!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}
		
		openTab("Resumen de presupuesto",url);
});
		
		
$("#presupuesto_ley_aprobado_final_rp_btn_consultar_unidad").click(function() {
	if(getObj('presupuesto_ley_aprobado_final_rp_una_unidad').checked==true){														
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/rp/grid_unidad.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#presupuesto_movimiento_pr_nombre_unidad").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmb.sql.unidad_ejecutora2.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#presupuesto_movimiento_pr_nombre_unidad").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
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
							var busq_nombre= jQuery("#presupuesto_movimiento_pr_nombre_unidad").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmb.sql.unidad_ejecutora2.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/rp/cmb.sql.unidad_ejecutora2.php?nd='+nd+'&ano='+getObj('presupuesto_ley_aprobado_final_rp_anio').value,
								datatype: "json",
								colNames:['ID','Codigo','Nombre'],
								colModel:[
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora', width:25,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:300,sortable:false,resizable:false}								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj("presupuesto_ley_aprobado_final_rp_unidad_id").value=ret.id_unidad_ejecutora;							
								getObj("presupuesto_ley_aprobado_final_rp_unidad_codigo").value=ret.codigo_unidad_ejecutora;
								getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value= ret.id_unidad_ejecutora;
								getObj('presupuesto_ley_aprobado_final_rp_unidad_direccion').value="vista.lst.presupuesto_comprometido_unidad_ejecutora!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value/*+"@desde="+getObj('presupuesto_apro_rp_cmb_mes_desde').value+"@hasta="+getObj('presupuesto_apro_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora_ejecutora="+ret.id;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#presupuesto_movimiento_pr_nombre_unidad").focus();
								$('#presupuesto_movimiento_pr_nombre_unidad').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	}
	});
//***********************************************************************************************************************
function consulta_automatica_unidad_ejecutora()
{
	if(getObj('presupuesto_ley_aprobado_final_rp_una_unidad').checked==true){	
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/rp/sql_grid_unidad_ejecutora2.php?unidad="+getObj('presupuesto_ley_aprobado_final_rp_unidad_codigo').value,
            data:dataForm('form_presupuesto_ley_aprobado_final'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value = recordset[0];
				alert('encontrado');
				//getObj('presupuesto_ley_pr_unidad_ejecutora').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{ 
				alert('No encontrado'); 
			   	getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value = "";
				//getObj('presupuesto_ley_db_unidad_ejecutora').value="";		
				}
			 }
		});
	}	 	 
}
$("#presupuesto_ley_aprobado_final_rp_una_unidad").click(function() {
	getObj('presupuesto_ley_aprobado_final_rp_unidad_id').disabled='';
	getObj('presupuesto_ley_aprobado_final_rp_unidad_codigo').disabled='';
	clearForm('presupuesto_ley_aprobado_final_rp_unidad_codigo');
	getObj('acciones').value='0';
	getObj('proyectos').value='0';
	getObj('ambos').value='1';
	
	getObj('presupuesto_ley_aprobado_final_rp_ambos').checked=true;
});
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
$("#presupuesto_ley_aprobado_final_rp_btn_consultar_pro").click(function() {
if((getObj('presupuesto_ley_aprobado_final_rp_un_proyecto').checked  == true) /*&& (getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value  != "") */)
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/rp/grid_proyecto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proyecto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_proyecto").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sql.proyecto.php?busq_nombre="+busq_nombre+"&ano="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"&unidad="+getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#ante_pro_presupuesto_pr_nombre_proyecto").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
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
							var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_proyecto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sql.proyecto.php?busq_nombre="+busq_nombre+"&ano="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"&unidad="+getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value,page:1}).trigger("reloadGrid");
							
						}
			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/rp/cmd.sql.proyecto.php?ano='+getObj('presupuesto_ley_aprobado_final_rp_anio').value+'&unidad='+getObj('presupuesto_ley_aprobado_final_rp_unidad_id').value,
								datatype: "json",
								colNames:['ID','Codigo','Proyecto'],
								colModel:[
									{name:'id_proyecto',index:'id_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proyecto',index:'codigo_proyecto', width:25,sortable:false,resizable:false},
									{name:'proyecto',index:'proyecto', width:300,sortable:false,resizable:false}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								    getObj('presupuesto_ley_aprobado_final_rp_accion_id').value = '';
									getObj('presupuesto_ley_aprobado_final_rp_proyecto_id').value = ret.id_proyecto;
									getObj('presupuesto_ley_aprobado_final_rp_pro_codigo').value = ret.codigo_proyecto;
									//getObj('presupuesto_apro_rp_nombre_accion_proyecto').value = ret.proyecto;
									//getObj('presupuesto_apro_rp_direccion').value="vista.lst.presupuesto_comprometido_proyecto.php!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value/*+"@desde="+getObj('presupuesto_apro_rp_cmb_mes_desde').value+"@hasta="+getObj('presupuesto_apro_rp_cmb_mes_hasta').value*/+"@proyecto="+ret.id;
									//alert(getObj('presupuesto_apro_rp_direccion').value);
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#ante_pro_presupuesto_pr_nombre_proyecto").focus();
								$('#ante_pro_presupuesto_pr_nombre_proyecto').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}
	});
//***********************************************************************************************************************
function consulta_automatica_proyecto()
{
	if(getObj('presupuesto_ley_aprobado_final_rp_un_proyecto').checked==true){	
	$.ajax({
			url:"modulos/presupuesto/presupuesto_ley/rp/sql_grid_proyecto_codigo2.php?codigo="+getObj('presupuesto_ley_aprobado_final_rp_unidad_codigo').value,
            data:dataForm('form_presupuesto_ley_aprobado_final'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('presupuesto_ley_aprobado_final_rp_proyecto_id').value = recordset[0];
				alert('encontrado');
				//getObj('presupuesto_ley_pr_unidad_ejecutora').value=recordset[1];
				setBarraEstado(html);
				}
				else
			 	{ 
				alert('No encontrado'); 
			   	getObj('presupuesto_ley_aprobado_final_rp_proyecto_id').value = "";
				//getObj('presupuesto_ley_db_unidad_ejecutora').value="";
				setBarraEstado(html);		
				}
			 }
		});
	}	 	 
}

$("#presupuesto_ley_aprobado_final_rp_una_unidad").click(function() {
	getObj('presupuesto_ley_aprobado_final_rp_unidad_id').disabled='';
	getObj('presupuesto_ley_aprobado_final_rp_unidad_codigo').disabled='';
	clearForm('presupuesto_ley_aprobado_final_rp_unidad_codigo');
	getObj('acciones').value='0';
	getObj('proyectos').value='0';
	getObj('ambos').value='1';
	
	getObj('presupuesto_ley_aprobado_final_rp_ambos').checked=true;
});
$("#presupuesto_ley_aprobado_final_rp_todo_proyecto").click(function() {
	getObj('presupuesto_ley_aprobado_final_rp_unidad_id').disabled='';
	getObj('presupuesto_ley_aprobado_final_rp_unidad_codigo').disabled='';
	getObj('presupuesto_ley_aprobado_final_rp_pro_codigo').style.display='none';
	getObj('presupuesto_ley_aprobado_final_rp_acc_codigo').style.display='none'; 
	getObj('presupuesto_ley_aprobado_final_rp_btn_consultar_acc').style.display='none';
	getObj('presupuesto_ley_aprobado_final_rp_btn_consultar_pro').style.display='none'; 
	clearForm('presupuesto_ley_aprobado_final_rp_unidad_codigo');
	getObj('acciones').value='0';
	getObj('proyectos').value='1';
	getObj('ambos').value='0';
});
$("#presupuesto_ley_aprobado_final_rp_un_proyecto").click(function() {
	getObj('presupuesto_ley_aprobado_final_rp_unidad_id').disabled='';
	getObj('presupuesto_ley_aprobado_final_rp_unidad_codigo').disabled='';
	getObj('presupuesto_ley_aprobado_final_rp_pro_codigo').style.display='';
	getObj('presupuesto_ley_aprobado_final_rp_acc_codigo').style.display='none'; 
	getObj('presupuesto_ley_aprobado_final_rp_btn_consultar_acc').style.display='none';
	getObj('presupuesto_ley_aprobado_final_rp_btn_consultar_pro').style.display=''; 
	clearForm('presupuesto_ley_aprobado_final_rp_unidad_codigo');
	getObj('acciones').value='0';
	getObj('proyectos').value='1';
	getObj('ambos').value='0';
	
});
$('#presupuesto_ley_aprobado_final_rp_unidad_codigo').change(consulta_automatica_unidad_ejecutora);
$('#presupuesto_ley_aprobado_final_rp_pro_codigo').change(consulta_automatica_proyecto);

</script>
<div id="botonera">
	<img id="presupuesto_ley_aprobado_final_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="presupuesto_ley_aprobado_final_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>
<form name="form_presupuesto_ley_aprobado_final" id="form_presupuesto_ley_aprobado_final">
<input type="hidden" name="presupuesto_ley_aprobado_final_rp_unidad_id" id="presupuesto_ley_aprobado_final_rp_unidad_id">
<input type="hidden" name="presupuesto_ley_aprobado_final_rp_proyecto_id" id="presupuesto_ley_aprobado_final_rp_proyecto_id">
<input type="hidden" name="presupuesto_ley_aprobado_final_rp_accion_id" id="presupuesto_ley_aprobado_final_rp_accion_id">
<input type="hidden" name="presupuesto_ley_aprobado_final_rp_unidad_direccion" id="presupuesto_ley_aprobado_final_rp_unidad_direccion">
<input type="hidden" name="acciones" id="acciones">
<input type="hidden" name="proyectos" id="proyectos">
<input type="hidden" name="ambos" id="ambos">
	<table class="cuerpo_formulario" style="width:500">
		<tr>
			<th colspan="3" class="titulo_frame">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Reporte Presupuesto de Ley 2011
			</th>
		</tr>
		<tr>
			<th colspan="3">A&ntilde;o
				<select name="presupuesto_ley_aprobado_final_rp_anio" id="presupuesto_ley_aprobado_final_rp_anio" style="width:60px; min-width:60px;">
					<option value="2010">2010</option>
					<option value="2011" selected="selected">2011</option>
				</select>
			</th>
		</tr>
		<tr>
			<th>
				<table class="clear" width="100%" border="0">
					<tr>
						<td width="10%" style="width:5%"><input name="presupuesto_ley_aprobado_final_rp_unidad" type="radio" id="presupuesto_ley_aprobado_final_rp_una_unidad" value="0"></td>
						<td>Una Unidad</td>
					</tr>
					<tr>
						<td><input name="presupuesto_ley_aprobado_final_rp_unidad" type="radio" id="presupuesto_ley_aprobado_final_rp_todas_unidad" value="1" checked="checked"></td>
						<td>Todas</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" name="presupuesto_ley_aprobado_final_rp_unidad_codigo" id="presupuesto_ley_aprobado_final_rp_unidad_codigo" size="8" />
					    <img class="btn_consulta_emergente" id="presupuesto_ley_aprobado_final_rp_btn_consultar_unidad" src="imagenes/null.gif" /></td>
					</tr>
				</table>
			</th>
			<th>
				<table class="clear" width="100%" border="0">
					<tr>
						<td width="10%" style="width:5%"><input name="presupuesto_ley_aprobado_final_rp_proyecto_accion" type="radio" id="presupuesto_ley_aprobado_final_rp_ambos" value="0" checked="checked"></td>
						<td>Ambos</td>
					</tr>
					<tr>
						<td><input name="presupuesto_ley_aprobado_final_rp_proyecto_accion" type="radio" id="presupuesto_ley_aprobado_final_rp_un_proyecto" value="1"></td>
						<td>Un Proyecto</td>
					</tr>
					<tr>
						<td><input name="presupuesto_ley_aprobado_final_rp_proyecto_accion" type="radio" id="presupuesto_ley_aprobado_final_rp_todo_proyecto" value="2"></td>
						<td>Todos</td>
					</tr>
					<tr>
						<td><input name="presupuesto_ley_aprobado_final_rp_proyecto_accion" type="radio" id="presupuesto_ley_aprobado_final_rp_un_accion" value="3"></td>
						<td>Una Acci&oacute;n Central</td>
					</tr>
					<tr>
						<td><input name="presupuesto_ley_aprobado_final_rp_proyecto_accion" type="radio" id="presupuesto_ley_aprobado_final_rp_todo_accion" value="4"></td>
						<td>Todas</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="text" name="presupuesto_ley_aprobado_final_rp_acc_codigo" id="presupuesto_ley_aprobado_final_rp_acc_codigo" size="8" style="display:none">
							<input type="text" name="presupuesto_ley_aprobado_final_rp_pro_codigo" id="presupuesto_ley_aprobado_final_rp_pro_codigo" size="8" style="display:none">
							<img class="btn_consulta_emergente" id="presupuesto_ley_aprobado_final_rp_btn_consultar_acc" src="imagenes/null.gif"style="display:none" />
							<img class="btn_consulta_emergente" id="presupuesto_ley_aprobado_final_rp_btn_consultar_pro" src="imagenes/null.gif"style="display:none" />
						</td>
					</tr>
				</table>
			</th>
			<th>
				<table class="clear" width="100%" border="0">
					<tr>
						<td width="10%" style="width:5%"><input name="presupuesto_ley_aprobado_final_rp_accion_especifica" type="radio" id="presupuesto_ley_aprobado_final_rp_accion_especifica_una_especifica" value="0"></td>
						<td>Una Acci&oacute;n Especifica</td>
					</tr>
					<tr>
						<td><input name="presupuesto_ley_aprobado_final_rp_accion_especifica" type="radio" id="presupuesto_ley_aprobado_final_rp_accion_especifica_todas_especifica" value="1" checked="checked"></td>
						<td>Todas</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" name="presupuesto_ley_aprobado_final_rp_especifica_codigo" id="presupuesto_ley_aprobado_final_rp_especifica_codigo" size="8"><img class="btn_consulta_emergente" id="presupuesto_ley_aprobado_final_rp_btn_consultar_especifica" src="imagenes/null.gif" /></td>
					</tr>
				</table>
			</th>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
		<td colspan="3" class="bottom_frame">&nbsp;</td>
	</tr>
	</table>
</form>