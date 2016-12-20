<div class="div_busqueda">
<td align="center"><strong>Partida:
  <label></label>
  <input type="text" id="adquisiciones_impuesto_db_partida" size="4" maxlength="3" 
  jVal="{valid:/^[0-9]{1,60}$/}"
  jValKey="{valid:/[0-9]/}"/>
  &nbsp;&nbsp;Denominacion</strong>: </td>                  
  <input type="text" id="adquisiciones_impuesto_db_nombre"  
   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
