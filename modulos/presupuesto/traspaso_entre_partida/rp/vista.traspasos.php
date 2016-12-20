<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script>
	$("#traspasos_rp_btn_imprimir").click(function() {
	//alert('aqui1');
		/*if((getObj('traspasos_rp_unidad').value =="" ) && (getObj('traspasos_rp_proyecto').value =="" )&& (getObj('traspasos_rp_accion_central').value =="" ) && (getObj('traspasos_rp_accion_es').value =="" ) && (getObj('traspasos_rp_partida').value =="" ) && (getObj('traspasos_rp_generica').value =="" ) && (getObj('traspasos_rp_espedifica').value =="" ))
		{*/
			//alert('aqui2');
			url="pdfb.php?p=modulos/presupuesto/traspaso_entre_partida/rp/vista.lst.traspaso_reprogramacion.php!traspasos_rp_desde="+getObj('traspasos_rp_desde').value+"@traspasos_rp_hasta="+getObj('traspasos_rp_hasta').value; 
		/*}else if((getObj('traspasos_rp_unidad').value !="" ) && (getObj('traspasos_rp_proyecto').value =="" )&& (getObj('traspasos_rp_accion_central').value =="" ) && (getObj('traspasos_rp_accion_es').value =="" ) && (getObj('traspasos_rp_partida').value =="" ) && (getObj('traspasos_rp_generica').value =="" ) && (getObj('traspasos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_unidad.php!compromisos_rp_desde="+getObj('traspasos_rp_desde').value+"@compromisos_rp_hasta="+getObj('traspasos_rp_hasta').value+"@unidad="+getObj('traspasos_rp_unidad').value; 
		
		}else if(((getObj('traspasos_rp_unidad').value !="" )||(getObj('traspasos_rp_unidad').value =="" )) && (getObj('traspasos_rp_proyecto').value !="" )&& (getObj('traspasos_rp_accion_central').value =="" ) && (getObj('traspasos_rp_accion_es').value =="" ) && (getObj('traspasos_rp_partida').value =="" ) && (getObj('traspasos_rp_generica').value =="" ) && (getObj('traspasos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_proyectos.php!compromisos_rp_desde="+getObj('traspasos_rp_desde').value+"@compromisos_rp_hasta="+getObj('traspasos_rp_hasta').value+"@unidad="+getObj('traspasos_rp_unidad').value+"@proyecto="+getObj('traspasos_rp_proyecto').value; 
		
		}else if(((getObj('traspasos_rp_unidad').value !="" )||(getObj('traspasos_rp_unidad').value =="" )) && (getObj('traspasos_rp_proyecto').value =="" )&& (getObj('traspasos_rp_accion_central').value !="" ) && (getObj('traspasos_rp_accion_es').value =="" ) && (getObj('traspasos_rp_partida').value =="" ) && (getObj('traspasos_rp_generica').value =="" ) && (getObj('traspasos_rp_espedifica').value =="" ))
		{
			url="pdfb.php?p=modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.compromiso_adquiridos_accion_cen.php!compromisos_rp_desde="+getObj('traspasos_rp_desde').value+"@compromisos_rp_hasta="+getObj('traspasos_rp_hasta').value+"@unidad="+getObj('traspasos_rp_unidad').value+"@acc_cen="+getObj('traspasos_rp_accion_central').value; 
		
		}*/
		//alert(url);
		openTab("Reporte Traspasos",url);
	
	});
</script> 
<div id="botonera">
	<img id="traspasos_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="traspasos_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>
<form name="form_rp_traspasos" id="form_rp_traspasos" method="post">
<table class="cuerpo_formulario" >
	<tr>
		<th  class="titulo_frame" colspan="3">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />	TRASPASOS Y/O REPROGRAMACIONES
		</th>
	</tr>
	<tr>
		<th >Desde
				<input type="text" name="traspasos_rp_desde" id="traspasos_rp_desde" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />&nbsp;&nbsp;
				
			</th>
		<th>
			Hasta
				<input type="text" name="traspasos_rp_hasta" id="traspasos_rp_hasta" size="10" value="<?=date('d/m/Y')?>" alt="date-ven" />
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
						<input name="traspasos_rp_unidad" id="traspasos_rp_unidad">
						<!--<img class="btn_consulta_emergente" id="traspasos_rp_btn_consultar_unidad" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>PROYECTO</th>
					<td>
						<input name="traspasos_rp_proyecto" id="traspasos_rp_proyecto">
						<!--<img class="btn_consulta_emergente" id="traspasos_rp_btn_consultar_proyecto" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
				<tr>
					<th>ACCION CENTRALIZADA</th>
					<td>
						<input name="traspasos_rp_accion_central" id="traspasos_rp_accion_central">
						<!--<img class="btn_consulta_emergente" id="traspasos_rp_btn_consultar_accion_central" src="imagenes/null.gif" />&nbsp;&nbsp;-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ACCION ESPECIFICA</th>
					<td>
						<input name="traspasos_rp_accion_es" id="traspasos_rp_accion_es">
						<!--<img class="btn_consulta_emergente" id="traspasos_rp_btn_consultar_accion_es" src="imagenes/null.gif" />&nbsp;&nbsp;-->
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
						<input name="traspasos_rp_partida" id="traspasos_rp_partida" size="5" maxlength="3">
						<!--<img class="btn_consulta_emergente" id="traspasos_rp_btn_consultar_partida" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>GENERICA</th>
					<td>
						<input name="traspasos_rp_generica" id="traspasos_rp_generica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="traspasos_rp_btn_consultar_generica" src="imagenes/null.gif" />-->
					</td>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th>ESPECIFICA</th>
					<td>
						<input name="traspasos_rp_espedifica" id="traspasos_rp_espedifica" size="5" maxlength="2">
						<!--<img class="btn_consulta_emergente" id="traspasos_rp_btn_consultar_espedifica" src="imagenes/null.gif" />-->
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