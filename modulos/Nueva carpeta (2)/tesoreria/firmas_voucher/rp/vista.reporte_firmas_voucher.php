<?php
session_start();
?>

<script>
var dialog;

$("#tesoreria_frimas_voucher_rp_btn_imprimir").click(function() {
if(($('#form_rp_banco_cuentas').jVal()))
	{
		url="pdf.php?p=modulos/tesoreria/firmas_voucher/rp/vista.lst.firmas_voucher.php¿mes="+getObj('').value+"@ayo="+getObj('').value; 
		openTab("Banco/Cuentas",url);
	 }
});
$("#tesoreria_frimas_voucher_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_firmas_voucher');
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
	<img id="tesoreria_frimas_voucher_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="tesoreria_frimas_voucher_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_firmas_voucher" id="form_rp_firmas_voucher">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Firmas Voucher </th>
	  </tr>
		<!--<tr>
			<th>Selección</th>
			<td>
				<input id="tesoreria_banco_cuentas_rp_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>-->
		<tr>
		<th>
			Año
		</th>
		<td>
			<select  name="tesoreria_frimas_voucher_rp_ayo" id="tesoreria_frimas_voucher_rp_ayo">
					<?
					$anio_inicio=date("Y");
					$anio_fin=date("Y")+1;
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
		<tr id="tesoreria_frimas_voucher_rp_tr_banco" >
		<tr>
			<th>Mes:</th>
				<td>
				<select  name="tesoreria_frimas_voucher_rp_mes" id="tesoreria_frimas_voucher_rp_mes">
				  <option value="ENERO">ENERO</option>
				  <option value="FEBRERO">FEBRERO</option>
				  <option value="MARZO">MARZO</option>
				  <option value="ABRIL">ABRIL</option>
				  <option value="MAYO">MAYO</option>
				  <option value="JUNIO">JUNIO</option>
				  <option value="JULIO">JULIO</option>
				  <option value="AGOSTO">AGOSTO</option>
				  <option value="SEPTIEMBRE">SEPTIEMBRE</option>
				  <option value="OCTUBRE">OCTUBRE</option>
				  <option value="NOVIEMBRE">NOVIEMBRE</option>
				  <option value="DICIEMBRE">DICIEMBRE</option>
			  </select>
					
				</td>	
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>