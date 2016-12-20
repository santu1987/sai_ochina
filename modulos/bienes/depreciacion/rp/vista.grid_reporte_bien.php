<div class="div_busqueda">
<td align="center"><strong>Código</strong>: </td>                      
<label>
  <input type="text" id="reporte_bien_rp_codigo" maxlength="30"  
			   jval="{valid:/^[a-zA-Z-_ 0-9]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z-_ 0-9]/}"/>
</label>
<td align="center"><strong>Bien</strong>: </td>                      
<label>
  <input type="text" id="reporte_bien_rp_nombre" maxlength="30"  
			   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
</label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>