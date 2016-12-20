<div class="div_busqueda">
<td align="center"><strong>Unidad </strong>: </td>
<input type="text" id="aumento_sueldos_pr_nombre" maxlength="30"  
			   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
<td align="center"><strong>Cedula </strong>: </td>
<input type="text" id="aumento_sueldos_pr_cedula" maxlength="30"  
			   jval="{valid:/^[V-E-0-9]{1,60}$/}"
				jvalkey="{valid:/[V-E-0-9]/}"/>                
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
