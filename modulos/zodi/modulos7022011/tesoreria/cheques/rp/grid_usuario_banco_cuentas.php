<div class="div_busqueda">
<td align="center"><strong>Usuario</strong>: </td>                  
	           <input type="text" id="tesoreria-reportes-busq_nombre_usuario2"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" />	 
&nbsp;
<td align="center"><strong>Divisi&oacute;n</strong>: </td>
<input type="text" id="tesoreria-reportes-busq_nombre_usuario"  
			   jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/}" />
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>