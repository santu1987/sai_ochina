<div class="div_busqueda">
<td align="center"><strong>Nombre</strong>: </td>                  
  <input type="text" id="contabilidad_tipo_comprobante_nombre_consulta"  
   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
   <td align="center"><strong>C&oacute;digo</strong>: </td>                  
  <input type="text" id="contabilidad_tipo_comprobante_cod"  
  />
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>