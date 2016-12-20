<script>
var dialog;
$("#trimestres_rp_btn_imprimir").click(function() {
	url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_ley_todo_proyecto.php!anio="+getObj('presupuesto_ley_aprobado_final_rp_anio').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
	openTab("Resumen de presupuesto",url);
});
</script>
<div id="botonera">
	<img id="trimestres_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="trimestres_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form name="form_trimestres" id="form_trimestres">
	<table  class="cuerpo_formulario">
		 <tr>
			<th class="titulo_frame" colspan="4">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> PRESUPUESTO&nbsp;<?=date('Y')?> 
			</th>
		</tr>
		<tr>
			<th colspan="4">A&ntilde;o
				<select name="trimestres_rp_anio" id="trimestres_rp_anio" style="width:60px; min-width:60px;">
					<option value="2010">2010</option>
					<option value="2011" selected="selected">2011</option>
				</select>
			</th>
		</tr>
		<tr>
			<td>
				<table class="clear" width="100%" border="0">
					<tr>
						<td width="10%" style="width:5%"><input name="trimestres_rp_unidad" type="radio" id="trimestres_rp_una_unidad" value="0"></td>
						<td>Una Unidad</td>
					</tr>
					<tr>
						<td><input name="trimestres_rp_unidad" type="radio" id="trimestres_rp_todas_unidad" value="1" checked="checked"></td>
						<td>Todas</td>
					</tr>					
					<tr>
						<td colspan="2" rowspan="3"><br><br><br></td>
					</tr>
				</table>
			</td>
			<td>
				<table class="clear" width="100%" border="0">
					<tr>
						<td width="10%" style="width:5%"><input name="trimestres_rp_proyecto_acc" type="radio" id="trimestres_rp_ambos" value="0" checked="checked"></td>
						<td>Ambos</td>
					</tr>
					<tr>
						<td><input name="trimestres_rp_proyecto_acc" type="radio" id="trimestres_rp_un_proyecto" value="1" ></td>
						<td>Proyecto</td>
					</tr>					
					<tr>
						<td width="10%" style="width:5%"><input name="trimestres_rp_proyecto_acc" type="radio" id="trimestres_rp_todo_proyecto" value="2" ></td>
						<td>Todos</td>
					</tr>
					<tr>
						<td><input name="trimestres_rp_proyecto_acc" type="radio" id="trimestres_rp_una_acc" value="3" ></td>
						<td>Accion Centralizada</td>
					</tr>	
					<tr>
						<td><input name="trimestres_rp_proyecto_acc" type="radio" id="trimestres_rp_todo_acc" value="4" ></td>
						<td>Todos</td>
					</tr>	
				</table>
			</td>
			<td>
				<table class="clear" width="100%" border="0">
					<tr>
						<td width="10%" style="width:5%"><input name="trimestres_rp_acc_es" type="radio" id="trimestres_rp_una_acc_es" value="0"></td>
						<td>Una Accion Especifica</td>
					</tr>
					<tr>
						<td><input name="trimestres_rp_acc_es" type="radio" id="trimestres_rp_todas_acc_es" value="1" checked="checked"></td>
						<td>Todas</td>
					</tr>					
					<tr>
						<td colspan="2" rowspan="3"><br><br><br></td>
					</tr>
				</table>
			</td>
			<td>
				<table class="clear" width="100%" border="0">
					<tr>
						<td width="10%" style="width:5%"><input name="trimestres_rp_partida" type="radio" id="trimestres_rp_una_partida" value="0"></td>
						<td>Una Partida</td>
					</tr>
					<tr>
						<td><input name="trimestres_rp_partida" type="radio" id="trimestres_rp_todaspartida" value="1" checked="checked"></td>
						<td>Todas</td>
					</tr>					
					<tr>
						<td colspan="2" rowspan="3"><br><br><br></td>
					</tr>
				</table>
			</td>

		</tr>
		<tr>
			<td>
				<input type="text" name="trimestres_rp_unidad_codigo" id="trimestres_rp_unidad_codigo" size="8" />
				<img class="btn_consulta_emergente" id="trimestres_rp_btn_consultar_unidad" src="imagenes/null.gif" />
			</td>
			<td>
				<input type="text" name="trimestres_rp_proyecto_acc_codigo" id="trimestres_rp_proyecto_acc_codigo" size="8" />
				<img class="btn_consulta_emergente" id="trimestres_rp_btn_consultar_proyecto_acc" src="imagenes/null.gif" />
			</td>
			<td>	
				<input type="text" name="trimestres_rp_acc_es_codigo" id="trimestres_rp_acc_es_codigo" size="8" />
				<img class="btn_consulta_emergente" id="trimestres_rp_btn_consultar_acc_es" src="imagenes/null.gif" />
			</td>
			<td>
				<input type="text" name="trimestres_rp_partida_codigo" id="trimestres_rp_partida_codigo" size="12" />
				<img class="btn_consulta_emergente" id="trimestres_rp_btn_consultar_partida" src="imagenes/null.gif" />
			</td>
		</tr>
	</table>
</form>