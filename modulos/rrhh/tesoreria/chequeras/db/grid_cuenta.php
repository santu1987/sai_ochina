<div class="div_busqueda">
	<td align="center"><strong>&nbsp;Banco</strong>: </td>                  
	           <input type="text" id="tesoreria-consultas-busq_bancos" size="40" maxlength="20"  
			   jVal="{valid:/^[0-9]{1,20}$/}"
				jValKey="{valid:/[0-9]/}" />	 
<td align="center"><strong>&nbsp;N Cuenta</strong>: </td>                  
	           <input type="text" id="tesoreria-consultas-busq_cuentas" size="40" maxlength="20"  
			   jVal="{valid:/^[0-9]{1,20}$/}"
				jValKey="{valid:/[0-9]/}" />	 
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>