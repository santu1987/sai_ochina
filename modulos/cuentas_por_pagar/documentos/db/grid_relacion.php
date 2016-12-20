<div class="div_busqueda">

<td align="center"><strong> Cuenta Contable:</strong></td>
<input type="text" id="relacion_cuenta_contable" maxlength="30"  
  sage="Introduzca la cuenta contable" 
					jval="{valid:/^[0-9]{1,6}$/}"
					jvalkey="{valid:/[0-9]/}"/>	
<td align="center"><strong> Denominaci&oacute;n:</strong></td>
<input type="text" id="relacion_descripcion_cuenta" maxlength="30"  
  sage="Introduzca un nombre de cuenta" 
				/>	
`<td align="center"><strong>Tipo Documento:</strong></td>
<input type="text" id="relacion_tipo_documento" maxlength="9"  
  sage="Introduzca el tipo de documento" 
					jval="{valid:/^[0-9]{1,6}$/}"
					jvalkey="{valid:/[0-9]/}"/>	
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>