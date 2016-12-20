<div class="div_busqueda">
<td align="center"><strong> N Cheque:</strong>: </td>                  
	           <input type="text" id="tesoreria_cheques_busqueda_cheques"  
			  	jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"
				
				/>	 
<!--		        <input name="button" type="button" id="tesoreria_banco_cuenta-busqueda_boton_filtro" value="Buscar" />			    </td>
-->
 <label id="" for="tesoreria_busqueda_proveedor_anulados"><strong>Proveedor:</strong></label>
 		 <input type="text" name="tesoreria_busqueda_proveedor_an" id="tesoreria_busqueda_proveedor_an"  maxlength="25" size="25" />
		 <label id="" for=" tesoreria_busqueda_beneficiario_anulados"><strong>Benef    :</strong></label>
 		 <input type="text" name="tesoreria_busqueda_beneficiario_an" id="tesoreria_busqueda_beneficiario_an"  maxlength="25" size="25" />
		<!--		        <input name="button" type="button" id="tesoreria_banco_cuenta-busqueda_boton_filtro" value="Buscar" />			    </td>
-->

</div>			
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>