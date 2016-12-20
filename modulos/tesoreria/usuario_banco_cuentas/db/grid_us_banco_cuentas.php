<div class="div_busqueda">
<td align="center"><strong>&nbsp;Banco</strong>: </td>                  
	           <input type="text" id="tesoreria-consultas-busq_banco2"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" />	 
&nbsp;&nbsp;
<td align="center"><strong>Cuenta:</strong>: </td>
<input type="text" id="tesoreria-consultas-busq_cuentas2"/>
&nbsp;&nbsp;
<td align="center"><strong>Usuario:</strong>: </td>
<input type="text" id="tesoreria-consultas-busq_us2"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" />

</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>