<div class="div_busqueda">
<td align="center"><strong>Cédula</strong>: </td>
<label>
  <input type="text" id="trabajador_db_cedula_grid_t" />
</label>
<td align="center"><strong> &nbsp;&nbsp;Nombre </strong>: </td>
<input type="text" id="trabajador_db_nombre_grid_t" maxlength="30"  
			   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
