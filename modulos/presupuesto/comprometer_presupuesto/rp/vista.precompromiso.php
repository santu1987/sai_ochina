<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script>
	$("#precompromisos_rp_btn_imprimir").click(function() {
	//alert('aqui1');
		/*if((getObj('precompromisos_rp_unidad').value =="" ) && (getObj('precompromisos_rp_proyecto').value =="" )&& (getObj('precompromisos_rp_accion_central').value =="" ) && (getObj('precompromisos_rp_accion_es').value =="" ) && (getObj('precompromisos_rp_partida').value =="" ) && (getObj('precompromisos_rp_generica').value =="" ) && (getObj('precompromisos_rp_espedifica').value =="" ))
		{*/
			//alert('aqui2');
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.precompromiso.php!precompromisos_rp_desde="+getObj('precompromisos_rp_desde').value+"@precompromisos_rp_hasta="+getObj('precompromisos_rp_hasta').value; 
		/*}else if((getObj('precompromisos_rp_unidad').value !="" ) && (getObj('precompromisos_rp_proyecto').value =="" )&& (getObj('precompromisos_rp_accion_central').value =="" ) && (getObj('precompromisos_rp_accion_es').value =="" ) && (getObj('precompromisos_rp_partida').value =="" ) && (getObj('precompromisos_rp_generica').value =="" ) && (getObj('precompromisos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_unidad.php!compromisos_rp_desde="+getObj('precompromisos_rp_desde').value+"@compromisos_rp_hasta="+getObj('precompromisos_rp_hasta').value+"@unidad="+getObj('precompromisos_rp_unidad').value; 
		
		}else if(((getObj('precompromisos_rp_unidad').value !="" )||(getObj('precompromisos_rp_unidad').value =="" )) && (getObj('precompromisos_rp_proyecto').value !="" )&& (getObj('precompromisos_rp_accion_central').value =="" ) && (getObj('precompromisos_rp_accion_es').value =="" ) && (getObj('precompromisos_rp_partida').value =="" ) && (getObj('precompromisos_rp_generica').value =="" ) && (getObj('precompromisos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_proyectos.php!compromisos_rp_desde="+getObj('precompromisos_rp_desde').value+"@compromisos_rp_hasta="+getObj('precompromisos_rp_hasta').value+"@unidad="+getObj('precompromisos_rp_unidad').value+"@proyecto="+getObj('precompromisos_rp_proyecto').value; 
		
		}else if(((getObj('precompromisos_rp_unidad').value !="" )||(getObj('precompromisos_rp_unidad').value =="" )) && (getObj('precompromisos_rp_proyecto').value =="" )&& (getObj('precompromisos_rp_accion_central').value !="" ) && (getObj('precompromisos_rp_accion_es').value =="" ) && (getObj('precompromisos_rp_partida').value =="" ) && (getObj('precompromisos_rp_generica').value =="" ) && (getObj('precompromisos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_accion_cen.php!compromisos_rp_desde="+getObj('precompromisos_rp_desde').value+"@compromisos_rp_hasta="+getObj('precompromisos_rp_hasta').value+"@unidad="+getObj('precompromisos_rp_unidad').value+"@acc_cen="+getObj('precompromisos_rp_accion_central').value; 
		
		}*/
		//alert(url);
		openTab("Reporte Pre-Compromisos",url);
	
	});
</script> 
<div id="botonera">
	<img id="precompromisos_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="precompromisos_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>
<form name="form_rp_precompromisos" id="form_rp_precompromisos" method="post">
<table class="cuerpo_formulario" >
	<tr>
		<th  class="titulo_frame" colspan="3">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />	PRE-COMPROMISOS
		</th>
	</tr>
	<tr>
		<th >Desde
				<input type="text" name="precompromisos_rp_desde" id="precompromisos_rp_desde" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />&nbsp;&nbsp;
				
			</th>
		<th>
			Hasta
				<input type="text" name="precompromisos_rp_hasta" id="precompromisos_rp_hasta" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />
		</th>
		<th>&nbsp;
		</th>

	</tr>
	<tr>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>UNIDAD</th>
					<td>
						<input name="precompromisos_rp_unidad" id="precompromisos_rp_unidad">
						<!--<img class="btn_consulta_emergente" id="precompromisos_rp_btn_consultar_unidad" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>PROYECTO</th>
					<td>
						<input name="precompromisos_rp_proyecto" id="precompromisos_rp_proyecto">
						<!--<img class="btn_consulta_emergente" id="precompromisos_rp_btn_consultar_proyecto" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
				<tr>
					<th>ACCION CENTRALIZADA</th>
					<td>
						<input name="precompromisos_rp_accion_central" id="precompromisos_rp_accion_central">
						<!--<img class="btn_consulta_emergente" id="precompromisos_rp_btn_consultar_accion_central" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ACCION ESPECIFICA</th>
					<td>
						<input name="precompromisos_rp_accion_es" id="precompromisos_rp_accion_es">
						<!--<img class="btn_consulta_emergente" id="precompromisos_rp_btn_consultar_accion_es" src="imagenes/null.gif" />&nbsp;&nbsp;-->
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
						<input name="precompromisos_rp_partida" id="precompromisos_rp_partida" size="5" maxlength="3">
						<!--<img class="btn_consulta_emergente" id="precompromisos_rp_btn_consultar_partida" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>GENERICA</th>
					<td>
						<input name="precompromisos_rp_generica" id="precompromisos_rp_generica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="precompromisos_rp_btn_consultar_generica" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ESPECIFICA</th>
					<td>
						<input name="precompromisos_rp_espedifica" id="precompromisos_rp_espedifica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="precompromisos_rp_btn_consultar_espedifica" src="imagenes/null.gif" />-->
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