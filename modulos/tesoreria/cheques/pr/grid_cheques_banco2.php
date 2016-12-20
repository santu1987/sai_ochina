<div class="div_busqueda">
<td align="center"><strong> N Pre-Cheque:</strong>: </td>                  
	           <input type="text" id="tesoreria_cheques_busqueda_cheques"  
			  	jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"
				
				/>	 


</div>			
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>