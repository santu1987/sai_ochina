<div class="div_busqueda">
<td align="center"><strong> BANCO:</strong></td>                  
	           <input type="text" id="tesoreria_banco_cuenta_banco-busqueda_bancos"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" />	 
<!--		        <input name="button" type="button" id="tesoreria_banco_cuenta-busqueda_boton_filtro" value="Buscar" />			    </td>
-->
</div>			
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
		