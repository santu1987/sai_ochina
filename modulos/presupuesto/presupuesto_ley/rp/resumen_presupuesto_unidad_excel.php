<?php
header("Pragma: ");
header('Cache-control: ');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename='Tu_Nombre_De_Archivo.xls'");

?>


<table border=1>
<tr>
<td rowspan="5"><img src="../../../../imagenes/logos/logo_ochina_295x260.jpg" width="100" ></td> 
<td align="center">República Bolivariana de Venezuela</td>
</tr>
<tr>
	<td align="center">Ministerio del Poder Popular para la Defensa</td>
   <td></td>
</tr>
<tr>
	<td align="center">Viceministerio de Servicios</td>
   <td></td>
</tr>
<tr>
	<td align="center">Direcci&oacute;n General de Empresas y Servicios</td>
   <td></td>
</tr>
<tr>
	<td align="center">Oficina Coordinadora de Hidrografía y Navegación</td>
    <td></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td colspan="3" align="center">PRESUPUESTO POR UNIDAD EJECUTORA</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td>Año:</td>
   <td>Unidad Ejecutora:</td>
   <td>date('d/m/Y')</td>
</tr>

</table>


