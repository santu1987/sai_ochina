<div class="div_busqueda">
<td align="center"><strong>Beneficiario:</strong>: </td>                  
	           <input type="text" id="tesoreria_pr_proveedor_consulta_pagado" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	
<td align="center"><strong>C&oacute;digo:</strong>: </td>                  
	           <input type="text" id="tesoreria_pr_proveedor_codigo_consulta_pagado" maxlength="30"  
               jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de cuenta Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"
			   />	 
                 
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>