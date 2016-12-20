
<div class="div_busqueda">
<td align="center"><strong>C&oacute;d </strong>: </td>                      
<label>
  <input type="text" id="conceptos_db_numero" size="8" maxlength="4"  
			   jval="{valid:/^[0-9]{1,60}$/}"
				jvalkey="{valid:/[0-9]/}"/>
</label>
<td align="center"><strong>Concepto </strong>: </td>
<label>
  <input type="text" id="conceptos_db_descripcion" maxlength="30"  
			   jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/}"/>
</label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>