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
		\"presupuesto_ejecutadoR\".monto_presupuesto[1] as enero_presupuesto, 
		\"presupuesto_ejecutadoR\".monto_comprometido[1] as enero_comp, 
		\"presupuesto_ejecutadoR\".monto_causado[1] as enero_causado,
		\"presupuesto_ejecutadoR\".monto_pagado
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
				<th>PARTIDAS</th>
				<th>PRESUPUESTADO</th>
				<th>COMPROMETIDO ENERO </th>
				<th>CAUSADO ENERO</th>
				
		 </tr>
<?php
$conta=1;
while(!$recordset->EOF)
	{
		$partidas=$recordset->fields("partida").$recordset->fields("generica").$recordset->fields("especifica").$recordset->fields("sub_especifica");		///
?>		 
	  <tr>
     			<td><?php echo($conta);?></td>
	 	     	<td><?php echo($partidas);?></td>
				<td><?php echo($recordset->fields("enero_presupuesto"));?></td>
	 	     	<td><?php echo($recordset->fields("enero_comp"));?></td>
		     	<td><?php echo($recordset->fields("enero_causado"));?></td>
		     	
				
				
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
</table>
</html> 