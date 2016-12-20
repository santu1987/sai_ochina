<div class="div_busqueda">
<form id="form_documentos_cxp_consultas_orden" name="form_documentos_cxp_consultas_orden" action="">
<td align="center">&nbsp;</td>
<td align="center" style="display:none"><label style="display:none">Proveedor:</label> </td>                  
	           <input style="display:none" type="text" id="cuentas_por_pagar_db_orden_proveedor_consulta" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	 
<td align="center"><strong>Fecha </strong>: </td> 
<input type="text" id="cuentas_por_pagar_db_orden_fecha_consulta" size="7" maxlength="10" readonly="true"/><a id="vista_calendario_fecha_orden" href="modulos/cuentas_por_pagar/orden_pago/db/fecha_orden.php" target="fecha_orden_iframe" onClick="mostrar_fecha_orden()"><img src="utilidades/jscalendar-1.0/img.gif"  border="0"/></a>	 
<td align="center">&nbsp;</td>
</form>                                
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
<iframe id="fecha_orden_iframe" name="fecha_orden_iframe" src="" scrolling="no" height="233px" width="250px" style="visibility:hidden; position:absolute; border:0; top:150px; left:470px;"></iframe>
<script language="javascript">
function mostrar_fecha_orden(){
	document.getElementById('fecha_orden_iframe').style.visibility='visible';
}
</script>