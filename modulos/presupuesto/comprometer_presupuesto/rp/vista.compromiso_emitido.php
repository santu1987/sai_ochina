<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script>
	$("#compromisos_emitidos_rp_btn_imprimir").click(function() {
	//alert('aqui1');
		if((getObj('compromisos_emitidos_rp_unidad').value =="" ) && (getObj('compromisos_emitidos_rp_proyecto').value =="" )&& (getObj('compromisos_emitidos_rp_accion_central').value =="" ) && (getObj('compromisos_emitidos_rp_accion_es').value =="" ) && (getObj('compromisos_emitidos_rp_partida').value =="" ) && (getObj('compromisos_emitidos_rp_generica').value =="" ) && (getObj('compromisos_emitidos_rp_espedifica').value =="" ))
		{
			//alert('aqui2');
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_emitidos.php!compromisos_rp_desde="+getObj('compromisos_emitidos_rp_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_emitidos_rp_hasta').value; 
		}else if((getObj('compromisos_emitidos_rp_unidad').value !="" ) && (getObj('compromisos_emitidos_rp_proyecto').value =="" )&& (getObj('compromisos_emitidos_rp_accion_central').value =="" ) && (getObj('compromisos_emitidos_rp_accion_es').value =="" ) && (getObj('compromisos_emitidos_rp_partida').value =="" ) && (getObj('compromisos_emitidos_rp_generica').value =="" ) && (getObj('compromisos_emitidos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_emitidos_unidad.php!compromisos_rp_desde="+getObj('compromisos_emitidos_rp_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_emitidos_rp_hasta').value+"@unidad="+getObj('compromisos_emitidos_rp_unidad').value; 
		
		}else if(((getObj('compromisos_emitidos_rp_unidad').value !="" )||(getObj('compromisos_emitidos_rp_unidad').value =="" )) && (getObj('compromisos_emitidos_rp_proyecto').value !="" )&& (getObj('compromisos_emitidos_rp_accion_central').value =="" ) && (getObj('compromisos_emitidos_rp_accion_es').value =="" ) && (getObj('compromisos_emitidos_rp_partida').value =="" ) && (getObj('compromisos_emitidos_rp_generica').value =="" ) && (getObj('compromisos_emitidos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_emitidos_proyecto.php!compromisos_rp_desde="+getObj('compromisos_emitidos_rp_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_emitidos_rp_hasta').value+"@unidad="+getObj('compromisos_emitidos_rp_unidad').value+"@proyecto="+getObj('compromisos_emitidos_rp_proyecto').value; 
		
		}else if(((getObj('compromisos_emitidos_rp_unidad').value !="" )||(getObj('compromisos_emitidos_rp_unidad').value =="" )) && (getObj('compromisos_emitidos_rp_proyecto').value =="" )&& (getObj('compromisos_emitidos_rp_accion_central').value !="" ) && (getObj('compromisos_emitidos_rp_accion_es').value =="" ) && (getObj('compromisos_emitidos_rp_partida').value =="" ) && (getObj('compromisos_emitidos_rp_generica').value =="" ) && (getObj('compromisos_emitidos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_emitidos_acc_cen.php!compromisos_rp_desde="+getObj('compromisos_emitidos_rp_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_emitidos_rp_hasta').value+"@unidad="+getObj('compromisos_emitidos_rp_unidad').value+"@acc_cen="+getObj('compromisos_emitidos_rp_accion_central').value; 
		
		}
		//alert(url);
		openTab("Reporte Compromisos Emitidos",url);
	
	});
</script>
<div id="botonera">
	<img id="compromisos_emitidos_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="compromisos_emitidos_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>
<form name="form_rp_compromisos_emitidos" id="form_rp_compromisos_emitidos" method="post">
<table class="cuerpo_formulario" >
	<tr>
		<th  class="titulo_frame" colspan="3">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />	COMPROMISOS EMITIDOS
		</th>
	</tr>
	<tr>
		<th>A&Ntilde;O
			<select name="compromisos_emitidos_rp_ano" id="compromisos_emitidos_rp_ano">
				<?
					$anio_inicio=2011;
					$anio_fin=date('Y') ;
					while($anio_inicio <= 2011)
					{
					if($anio_inicio==date('Y'))
						$selected = "selected";
					else
						$selected = "";
					?>
					<option <?=$selected?>  value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
				?>
			</select>
		</th>
		<th >Desde
				<input type="text" name="compromisos_emitidos_rp_desde" id="compromisos_emitidos_rp_desde" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />&nbsp;&nbsp;
				
			</th>
		<th>
			Hasta
				<input type="text" name="compromisos_emitidos_rp_hasta" id="compromisos_emitidos_rp_hasta" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />
		</th>
	</tr>
	<tr>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>UNIDAD</th>
					<td>
						<input name="compromisos_emitidos_rp_unidad" id="compromisos_emitidos_rp_unidad">
						<!--<img class="btn_consulta_emergente" id="compromisos_emitidos_rp_btn_consultar_unidad" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>PROYECTO</th>
					<td>
						<input name="compromisos_emitidos_rp_proyecto" id="compromisos_emitidos_rp_proyecto">
						<!--<img class="btn_consulta_emergente" id="compromisos_emitidos_rp_btn_consultar_proyecto" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
				<tr>
					<th>ACCION CENTRALIZADA</th>
					<td>
						<input name="compromisos_emitidos_rp_accion_central" id="compromisos_emitidos_rp_accion_central">
						<!--<img class="btn_consulta_emergente" id="compromisos_emitidos_rp_btn_consultar_accion_central" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ACCION ESPECIFICA</th>
					<td>
						<input name="compromisos_emitidos_rp_accion_es" id="compromisos_emitidos_rp_accion_es">
						<!--<img class="btn_consulta_emergente" id="compromisos_emitidos_rp_btn_consultar_accion_es" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
	</tr>
	<tr>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>PARTIDA</th>
					<td>
						<input name="compromisos_emitidos_rp_partida" id="compromisos_emitidos_rp_partida" size="5" maxlength="3">
						<!--<img class="btn_consulta_emergente" id="compromisos_emitidos_rp_btn_consultar_partida" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>GENERICA</th>
					<td>
						<input name="compromisos_emitidos_rp_generica" id="compromisos_emitidos_rp_generica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="compromisos_emitidos_rp_btn_consultar_generica" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ESPECIFICA</th>
					<td>
						<input name="compromisos_emitidos_rp_espedifica" id="compromisos_emitidos_rp_espedifica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="compromisos_emitidos_rp_btn_consultar_espedifica" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
	</tr>
	<tr>
		<td colspan="3" class="bottom_frame">&nbsp;</td>
	</tr>
</table>
</form>