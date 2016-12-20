<div>
<td align="center"><strong> Comprobante:</strong></td>
<input type="text" id="consulta_comprobante_tes"  name="consulta_comprobante_tes" maxlength="10"   size="10" 
  sage="Introduzca la cuenta contable" 
					jval="{valid:/^[0-9]{1,6}$/}"
					jvalkey="{valid:/[0-9]/}"/>	
<td align="center"><strong>Tipo</strong></td>	
<input type="text" id="consulta_comprobante_cxp_tipo"  name="consulta_comprobante_cxp_tipo" maxlength="10"   size="10" 
  sage="Introduzca la cuenta contable" 
					jval="{valid:/^[0-9]{1,6}$/}"
					jvalkey="{valid:/[0-9]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>