<div class="div_busqueda">
<td align="center"><strong>CI</strong>: </td>                      
<label>
  <input type="text" id="prestamo_pr_ci_grid" maxlength="30"/>
</label>
<td align="center"><strong>Trabajador </strong>: </td>
<label>
  <input type="text" id="prestamo_pr_trabajador_grid" maxlength="30"  
			   jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/}"/>
</label>
&nbsp;
<td align="center"><strong>Fecha</strong>: </td>
<label>
  <input type="text" id="prestamo_pr_fecha_grid" maxlength="30" alt="date"/>
</label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>