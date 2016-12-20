<div class="div_busqueda">
<td align="center"><strong>Cedula</strong>: </td>                  
  <input type="text" id="jefe_proyecto_db_cedula_jefe" size="10" maxlength="9"  
   jVal="{valid:/^[0-9]{1,60}$/}"
   jValKey="{valid:/[0-9]/}"/>
<td align="center"><strong>Nombre</strong>: </td>                     
	<input type="text" id="jefe_proyecto_db_nombre_jefe" maxlength="30"  
   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ.]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z áéíóúÁÉÍOÚ.]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>