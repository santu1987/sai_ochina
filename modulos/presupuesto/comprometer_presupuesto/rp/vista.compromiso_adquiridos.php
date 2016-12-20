<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script>
	$("#compromisos_adquiridos_btn_imprimir").click(function() {
	//alert('aqui1');
		if((getObj('compromisos_adquiridos_unidad').value =="" ) && (getObj('compromisos_adquiridos_proyecto').value =="" )&& (getObj('compromisos_adquiridos_accion_central').value =="" ) && (getObj('compromisos_adquiridos_accion_es').value =="" ) && (getObj('compromisos_adquiridos_partida').value =="" ) && (getObj('compromisos_adquiridos_generica').value =="" ) && (getObj('compromisos_adquiridos_espedifica').value =="" ))
		{
			//alert('aqui2');
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos.php!compromisos_rp_desde="+getObj('compromisos_adquiridos_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_adquiridos_hasta').value; 
		}else if((getObj('compromisos_adquiridos_unidad').value !="" ) && (getObj('compromisos_adquiridos_proyecto').value =="" )&& (getObj('compromisos_adquiridos_accion_central').value =="" ) && (getObj('compromisos_adquiridos_accion_es').value =="" ) && (getObj('compromisos_adquiridos_partida').value =="" ) && (getObj('compromisos_adquiridos_generica').value =="" ) && (getObj('compromisos_adquiridos_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_unidad.php!compromisos_rp_desde="+getObj('compromisos_adquiridos_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_adquiridos_hasta').value+"@unidad="+getObj('compromisos_adquiridos_unidad').value; 
		
		}else if(((getObj('compromisos_adquiridos_unidad').value !="" )||(getObj('compromisos_adquiridos_unidad').value =="" )) && (getObj('compromisos_adquiridos_proyecto').value !="" )&& (getObj('compromisos_adquiridos_accion_central').value =="" ) && (getObj('compromisos_adquiridos_accion_es').value =="" ) && (getObj('compromisos_adquiridos_partida').value =="" ) && (getObj('compromisos_adquiridos_generica').value =="" ) && (getObj('compromisos_adquiridos_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_proyectos.php!compromisos_rp_desde="+getObj('compromisos_adquiridos_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_adquiridos_hasta').value+"@unidad="+getObj('compromisos_adquiridos_unidad').value+"@proyecto="+getObj('compromisos_adquiridos_proyecto').value; 
		
		}else if(((getObj('compromisos_adquiridos_unidad').value !="" )||(getObj('compromisos_adquiridos_unidad').value =="" )) && (getObj('compromisos_adquiridos_proyecto').value =="" )&& (getObj('compromisos_adquiridos_accion_central').value !="" ) && (getObj('compromisos_adquiridos_accion_es').value =="" ) && (getObj('compromisos_adquiridos_partida').value =="" ) && (getObj('compromisos_adquiridos_generica').value =="" ) && (getObj('compromisos_adquiridos_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_accion_cen.php!compromisos_rp_desde="+getObj('compromisos_adquiridos_desde').value+"@compromisos_rp_hasta="+getObj('compromisos_adquiridos_hasta').value+"@unidad="+getObj('compromisos_adquiridos_unidad').value+"@acc_cen="+getObj('compromisos_adquiridos_accion_central').value; 
		
		}
		//alert(url);
		openTab("Reporte Compromisos Adquiridos",url);
	
	});
</script> 
<div id="botonera">
	<img id="compromisos_adquiridos_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="compromisos_adquiridos_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>
<form name="form_rp_compromisos_adquiriidos" id="form_rp_compromisos_adquiriidos" method="post">
<table class="cuerpo_formulario" >
	<tr>
		<th  class="titulo_frame" colspan="3">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />	COMPROMISOS ADQUIRIDOS
		</th>
	</tr>
	<tr>
		<th >Desde
				<input type="text" name="compromisos_adquiridos_desde" id="compromisos_adquiridos_desde" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />&nbsp;&nbsp;
				
			</th>
		<th>
			Hasta
				<input type="text" name="compromisos_adquiridos_hasta" id="compromisos_adquiridos_hasta" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />
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
						<input name="compromisos_adquiridos_unidad" id="compromisos_adquiridos_unidad">
						<!--<img class="btn_consulta_emergente" id="compromisos_adquiridos_btn_consultar_unidad" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>PROYECTO</th>
					<td>
						<input name="compromisos_adquiridos_proyecto" id="compromisos_adquiridos_proyecto">
						<!--<img class="btn_consulta_emergente" id="compromisos_adquiridos_btn_consultar_proyecto" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
				<tr>
					<th>ACCION CENTRALIZADA</th>
					<td>
						<input name="compromisos_adquiridos_accion_central" id="compromisos_adquiridos_accion_central">
						<!--<img class="btn_consulta_emergente" id="compromisos_adquiridos_btn_consultar_accion_central" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ACCION ESPECIFICA</th>
					<td>
						<input name="compromisos_adquiridos_accion_es" id="compromisos_adquiridos_accion_es">
						<!--<img class="btn_consulta_emergente" id="compromisos_adquiridos_btn_consultar_accion_es" src="imagenes/null.gif" />&nbsp;&nbsp;-->
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
						<input name="compromisos_adquiridos_partida" id="compromisos_adquiridos_partida" size="5" maxlength="3">
						<!--<img class="btn_consulta_emergente" id="compromisos_adquiridos_btn_consultar_partida" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>GENERICA</th>
					<td>
						<input name="compromisos_adquiridos_generica" id="compromisos_adquiridos_generica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="compromisos_adquiridos_btn_consultar_generica" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ESPECIFICA</th>
					<td>
						<input name="compromisos_adquiridos_espedifica" id="compromisos_adquiridos_espedifica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="compromisos_adquiridos_btn_consultar_espedifica" src="imagenes/null.gif" />-->
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