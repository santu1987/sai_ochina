<?php 
//definiendo que los archivos a crear son txt
$archivo="factura";
header('Content-type: application/xls');
// Creando en archivo con la extencion txt
header('Content-Disposition: attachment; filename="'.$archivo.''.'.xls"');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$hoy = date("Y-m-d H:i:s");
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
///////////////////////////////paso 1 armar el mega query
$mega_qery="
SELECT 
		\"presupuesto_ejecutadoR\".id_unidad_ejecutora,
		\"presupuesto_ejecutadoR\".id_accion_especifica,
		\"presupuesto_ejecutadoR\".partida,
		\"presupuesto_ejecutadoR\".generica,
		\"presupuesto_ejecutadoR\".especifica,
		\"presupuesto_ejecutadoR\".sub_especifica,
		\"presupuesto_ejecutadoR\".monto_comprometido[1] as enero_comp, 
		\"presupuesto_ejecutadoR\".monto_causado[1] as enero_causado,
		\"presupuesto_ejecutadoR\".monto_pagado[1] as enero_pagado,
		\"presupuesto_ejecutadoR\".monto_comprometido[2] as febrero_comp, 
		\"presupuesto_ejecutadoR\".monto_causado[2] as febrero_causado,
		\"presupuesto_ejecutadoR\".monto_pagado[2] as febrero_pagado,
		\"presupuesto_ejecutadoR\".monto_comprometido[3] as marzo_comp, 
		\"presupuesto_ejecutadoR\".monto_causado[3] as marzo_causado,
		\"presupuesto_ejecutadoR\".monto_pagado[3] as marzo_pagado
  FROM \"presupuesto_ejecutadoR\";
	
  ;";
 //x die($mega_qery);
$recordset=& $conn->Execute($mega_qery);

		?>
<html>
<th align="center"> FACTURAS POR CAUSAR Y PAGAR</th>
     <table border="1" >
   		  <tr>
				<th>NUMERO </th>
				<th>U EJECUTORA</th>
                <th>ACCION ESP</th>
                <th>PARTIDAS</th>
				<th>COMPROMETIDO ENERO </th>
				<th>CAUSADO ENERO</th>
				<th>PAGADO ENERO</th>
				<th>COMPROMETIDO FEB </th>
				<th>CAUSADO FEB</th>
				<th>PAGADO FEB</th>
				<th>COMPROMETIDO MARZO </th>
				<th>CAUSADO MARZO</th>
				<th>PAGADO MARZO</th>

		 </tr>
<?php
$conta=1;
while(!$recordset->EOF)
	{
		$partidas=$recordset->fields("partida").$recordset->fields("generica").$recordset->fields("especifica").$recordset->fields("sub_especifica");		///
?>		 
	  <tr>
     			<td><?php echo($conta);?></td>
	 	     	<td><?php echo($recordset->fields("id_unidad_ejecutora"));?></td>
	 	     	<td><?php echo($recordset->fields("id_accion_especifica"));?></td>
	 	     	<td><?php echo($partidas);?></td>
				<td><?php echo(number_format($recordset->fields("enero_comp"),2,',','.'));?></td>
		     	<td><?php echo(number_format($recordset->fields("enero_causado"),2,',','.'));?></td>
                <td><?php echo(number_format($recordset->fields("enero_pagado"),2,',','.'));?></td>
				<td><?php echo(number_format($recordset->fields("febrero_comp"),2,',','.'));?></td>
		     	<td><?php echo(number_format($recordset->fields("febrero_causado"),2,',','.'));?></td>
                <td><?php echo(number_format($recordset->fields("febrero_pagado"),2,',','.'));?></td>
	 	     	<td><?php echo(number_format($recordset->fields("marzo_comp"),2,',','.'));?></td>
		     	<td><?php echo(number_format($recordset->fields("marzo_causado"),2,',','.'));?></td>
                <td><?php echo(number_format($recordset->fields("marzo_pagado"),2,',','.'));?></td>
		<?php     	
		$compromiso1=$compromiso1+$recordset->fields("enero_comp");
		$causado1=$causado1+$recordset->fields("enero_causado");
		$pagado1=$pagado1+$recordset->fields("enero_pagado");
		$compromiso2=$compromiso2+$recordset->fields("febrero_comp");
		$causado2=$causado2+$recordset->fields("febrero_causado");
		$pagado2=$pagado2+$recordset->fields("febrero_pagado");
		$compromiso3=$compromiso3+$recordset->fields("marzo_comp");
		$causado3=$causado3+$recordset->fields("marzo_causado");
		$pagado3=$pagado3+$recordset->fields("marzo_pagado");

		?>		
				
	  </tr>
<?php
         // die($resumen_suma);				
    	  echo("\r\n");
?>
 			
	  <?php 
 	$recordset->MoveNext();
	$conta++;
	}
?>
				<td></td>
	 	     	<td></td>
				<td></td>
				<td></td>
				<td><?php echo(number_format($compromiso1,2,',','.'));?></td>
	 	     	<td><?php echo(number_format($causado1,2,',','.'));?></td> 
				<td><?php echo(number_format($pagado1,2,',','.'));?></td> 
				<td><?php echo(number_format($compromiso2,2,',','.'));?></td>
	 	     	<td><?php echo(number_format($causado2,2,',','.'));?></td> 
	 	     	<td><?php echo(number_format($pagado2,2,',','.'));?></td>  
				<td><?php echo(number_format($compromiso3,2,',','.'));?></td>
	 	     	<td><?php echo(number_format($causado3,2,',','.'));?></td>
				<td><?php echo(number_format($pagado3,2,',','.'));?></td>      
</table>
</html> 