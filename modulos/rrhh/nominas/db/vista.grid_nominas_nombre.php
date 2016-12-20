<div class="div_busqueda">
<td align="center"><strong>Tipo de Nomina</strong>: </td>                  
	           <input type="text" id="nominas_db_nombre" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	 
&nbsp;<strong>Fecha Desde</strong>:
<label>
  <input name="nominas_db_fechad" type="text" id="nominas_db_fechad" size="10" />
</label>
&nbsp;<strong>Fecha Hasta</strong>: 
<label>
  <input name="nominas_db_fechah" type="text" id="nominas_db_fechah" size="10" />
</label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>