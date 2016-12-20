<div class="div_busqueda">
<td align="center"><strong>Codigo</strong>: </td>                  
  <input type="text" id="proyecto_db_codigo_proyecto" size="6" maxlength="5"  
   jVal="{valid:/^[0-9]{1,60}$/}"
   jValKey="{valid:/[0-9]/}"/>
<td align="center"><strong>Proyecto</strong>: </td>
<input type="text" id="proyecto_db_nombre_proyecto" maxlength="30"  
   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>