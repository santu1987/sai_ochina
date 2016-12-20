<div class="div_busqueda">
<td align="center"><strong>Codigo</strong>: </td>                  
  <input type="text" id="plan_compra_db_codigo_demanda" size="6" maxlength="6"  
   jVal="{valid:/^[a-zA-Z0-1]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z]0-1/}"/>
   <td align="center"><strong>Nombre</strong>: </td>                  
   <input type="text" id="plan_compra_db_nombre_demanda" maxlength="30"  
   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>