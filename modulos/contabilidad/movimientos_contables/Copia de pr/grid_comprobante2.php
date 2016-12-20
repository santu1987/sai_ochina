<div>
<td align="center"><strong> Comprobante:</strong></td>
<input type="text" id="consulta_comprobante2"  name="consulta_comprobante2" maxlength="10"   size="10" 
  sage="Introduzca n comp" 
					jval="{valid:/^[0-9]{1,6}$/}"
					jvalkey="{valid:/[0-9]/}"/>	
<td align="center"><strong> Tipo Comprobante:</strong></td>
<input type="text" id="consulta_comprobante_tipo2"  name="consulta_comprobante_tipo2" maxlength="10"   size="10" 
  sage="Introduzca el tipo"/>	                    
<td align="center"><strong>A&ntilde;o</strong></td>	
<select name="consulta_ano_comp2" id="consulta_ano_comp2" >
<option value="2010">2010</option>
<option value="2011" selected="selected">2011</option>
<option value="2012">2012</option>
</select>				
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>