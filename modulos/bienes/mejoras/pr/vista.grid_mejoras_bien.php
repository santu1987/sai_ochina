<div class="div_busqueda">
<td align="center"><strong>Codigo</strong>: </td>
<input type="text" id="mejoras_bien_pr_codigo" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z 0-9]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z 0-9]/}"/>	 
<td align="center"><strong>Activo</strong>: </td>                  
	           <input type="text" id="mejoras_bien_pr_nombre" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	 
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>