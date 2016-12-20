<div class="div_busqueda">
<td align="center"><strong>C&eacute;dula</strong>: </td>                      
  <input type="text" id="trabajador_db_cedula_grid" maxlength="30" />
<strong>&nbsp;Nombre </strong>: </td>                      
<label>
  <input type="text" id="trabajador_db_nombre_grid" maxlength="30"  
			   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
</label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>