<div class="div_busqueda">
<form id="form_mejoras_bien" name="form_mejoras_bien" action="">
<td align="center"><strong>Codigo Activo</strong>: </td>
<input type="text" id="mejoras_bien_pr_codigo_bien" size="7" maxlength="10"  
			   jVal="{valid:/^[a-zA-Z 0-9]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z 0-9]/}"/>	 
<td align="center"><strong>Activo</strong>: </td>                  
	           <input type="text" id="mejoras_bien_pr_nombre_bien" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	 
<td align="center"><strong>Fecha Mejora</strong>: </td> 
<input type="text" id="mejoras_bien_pr_fecha_mejora" size="7" maxlength="10" readonly="true"/><a id="vista_calendario_mejora" href="modulos/bienes/mejoras/pr/fecha_mejora.php" target="fecha_mejoras" onclick="mostrar_mejora()"><img src="utilidades/jscalendar-1.0/img.gif" border="0"/></a>	 
<td align="center"><strong>N&deg; Comprobante</strong>: </td>
<input type="text" id="mejoras_bien_pr_numero_comprobante" size="7" maxlength="10"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	 
<td align="center"><strong>Fecha Comprobante</strong>: </td><input type="text" id="mejoras_bien_pr_fecha_comprobante" size="7" maxlength="10" readonly="true"/><a href="modulos/bienes/mejoras/pr/fecha_comprobante.php" target="fecha_comprobante" onclick="mostrar_comprobante();"><img src="utilidades/jscalendar-1.0/img.gif" border="0" /></a>
</form>                                
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
<iframe id="fecha_mejoras" name="fecha_mejoras" src="" scrolling="no" height="233px" width="250px" style="visibility:hidden; position:absolute; border:0; top:150px; left:470px;"></iframe>
<iframe id="fecha_comprobante" name="fecha_comprobante" src="" scrolling="no" height="233px" width="250px" style="visibility:hidden; position:absolute; border:0; top:150px; left:740px;"></iframe>
<script language="javascript">
function mostrar_mejora(){
	document.getElementById('fecha_mejoras').style.visibility='visible';
}
function mostrar_comprobante(){
	document.getElementById('fecha_comprobante').style.visibility='visible';
}
</script>