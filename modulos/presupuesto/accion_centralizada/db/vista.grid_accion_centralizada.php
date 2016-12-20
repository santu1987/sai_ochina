<div class="div_busqueda">
<td align="center"><strong>Codigo</strong>: </td>                  
  <input type="text" id="accion_centralizada_db_codigo_acc_cen" size="6" maxlength="5"  
   jVal="{valid:/^[0-9]{1,60}$/}"
   jValKey="{valid:/[0-9]/}"/>
<td align="center"><strong>Accion Centralizada</strong>: </td>                  
<input type="text" id="accion_centralizada_db_nombre_acc_cen" maxlength="30"  
   jVal="{valid:/^[a-zA-ZáéíóúÁÉÍÓÚ ]{1,60}$/}"
   jValKey="{valid:/[a-zA-ZáéíóúÁÉÍÓÚ ]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>