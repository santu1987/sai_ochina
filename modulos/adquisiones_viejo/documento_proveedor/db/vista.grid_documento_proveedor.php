<div class="div_busqueda">
<td align="center"><strong>Codigo</strong>: </td>                  
  <input type="text" id="documento_proveedor_db_codigo_documento" size="6" maxlength="5"  
   jVal="{valid:/^[0-9]{1,60}$/}"
   jValKey="{valid:/[0-9]/}"/>
<td align="center"><strong>Nombre</strong>: </td>
<input type="text" id="documento_proveedor_db_nombre_documento" maxlength="30"  
   jVal="{valid:/^[a-zA-ZáéíóúÁÉÍÓÚ ]{1,60}$/}"
   jValKey="{valid:/[a-zA-ZáéíóúÁÉÍÓÚ ]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>