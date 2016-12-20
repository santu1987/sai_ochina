<div class="div_busqueda">
<td align="center"><strong> Cuenta Contable:</strong></td>
<input type="text" id="consulta-cuenta-contable-busqueda-nombre_ad" maxlength="30"  
  sage="Introduzca la cuenta contable" 
					jval="{valid:/^[0-9]{1,6}$/}"
					jvalkey="{valid:/[0-9]/}"/>	
<td align="center"><strong> Denominaci&oacute;n:</strong></td>
<input type="text" id="consulta-cuenta-contable-busqueda2_ad" maxlength="30"  
  sage="Introduzca un nombre de cuenta" 
				/>	
`
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>