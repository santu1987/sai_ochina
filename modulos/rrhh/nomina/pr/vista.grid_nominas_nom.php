<div class="div_busqueda">
<td align="center"><strong>Numero</strong>: </td>
<input type="text" id="nomina_pr_numero" maxlength="30" size="8"  
			   jval="{valid:/^[0-9]{1,60}$/}"
				jvalkey="{valid:/[0-9]/}"/>             
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>