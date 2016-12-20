<div class="div_busqueda">
<form id="form_documentos_cxp_consultas" name="form_documentos_cxp_consultas" action="">
<td align="center">&nbsp;</td>
<td align="center">Proveedor</td>
<input type="text" id="cuentas_por_pagra_db_prove" size="20" maxlength="20" jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/> 
<td align="center">N FACTURA</td>
<input type="text" id="cuentas_por_pagra_db_benef" size="20" maxlength="20" jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
<td align="center"><strong><!--Fecha Vencimiento</strong>: </td> 
<input type="text" id="cuentas_por_pagar_db_fecha_vencimiento_consulta" size="7" maxlength="10" readonly="true"/><a id="vista_calendario_fecha_v" href="modulos/cuentas_por_pagar/documentos/db/fecha_documento.php" target="fecha_vencimiento_iframe" onClick="mostrar_fecha_v()"><img src="utilidades/jscalendar-1.0/img.gif" border="0"/>--></a>	 
</form>                                
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
<iframe id="fecha_vencimiento_iframe" name="fecha_vencimiento_iframe" src="" scrolling="no" height="233px" width="250px" style="visibility:hidden; position:absolute; border:0; top:150px; left:470px;"></iframe>
<script language="javascript">
function mostrar_fecha_v(){
	document.getElementById('fecha_vencimiento_iframe').style.visibility='visible';
}
</script>