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
	id_unidad_ejecutora,
	id_accion_especifica,
	partida,
	generica,
	especifica,
	subespecifica,
	numero_compromiso,
	SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)),
	

	(
	SELECT 
		(monto_precomprometido[2] ) 
		 
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
	AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
	AND
		\"orden_compra_servicioD\".partida = \"presupuesto_ejecutadoR\".partida
	AND
		\"orden_compra_servicioD\".generica = \"presupuesto_ejecutadoR\".generica
	AND
		\"orden_compra_servicioD\".especifica = \"presupuesto_ejecutadoR\".especifica
	AND
		\"orden_compra_servicioD\".subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
	) AS monto_precomprometido,
	(
	SELECT 
		(monto_comprometido[1]) 
		 
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
	AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
	AND
		\"orden_compra_servicioD\".partida = \"presupuesto_ejecutadoR\".partida
	AND
		\"orden_compra_servicioD\".generica = \"presupuesto_ejecutadoR\".generica
	AND
		\"orden_compra_servicioD\".especifica = \"presupuesto_ejecutadoR\".especifica
	AND
		\"orden_compra_servicioD\".subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
	) AS monto_comprometido_enero	,
(
	SELECT 
		(monto_comprometido[2]) 
		 
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
	AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
	AND
		\"orden_compra_servicioD\".partida = \"presupuesto_ejecutadoR\".partida
	AND
		\"orden_compra_servicioD\".generica = \"presupuesto_ejecutadoR\".generica
	AND
		\"orden_compra_servicioD\".especifica = \"presupuesto_ejecutadoR\".especifica
	AND
		\"orden_compra_servicioD\".subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
	) AS monto_comprometido_feb	,

	(
	SELECT 
		(monto_causado[1]) 
		 
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
	AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
	AND
		\"orden_compra_servicioD\".partida = \"presupuesto_ejecutadoR\".partida
	AND
		\"orden_compra_servicioD\".generica = \"presupuesto_ejecutadoR\".generica
	AND
		\"orden_compra_servicioD\".especifica = \"presupuesto_ejecutadoR\".especifica
	AND
		\"orden_compra_servicioD\".subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
	) AS monto_causado,
	(
	SELECT 
		(monto_pagado[2]) 
		 
	FROM
		\"presupuesto_ejecutadoR\"
	WHERE
		\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
	AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
	AND
		\"orden_compra_servicioD\".partida = \"presupuesto_ejecutadoR\".partida
	AND
		\"orden_compra_servicioD\".generica = \"presupuesto_ejecutadoR\".generica
	AND
		\"orden_compra_servicioD\".especifica = \"presupuesto_ejecutadoR\".especifica
	AND
		\"orden_compra_servicioD\".subespecifica = \"presupuesto_ejecutadoR\".sub_especifica
	) AS monto_pagado,
	fecha_elabora
FROM
	\"orden_compra_servicioD\"

INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
WHERE
	fecha_elabora BETWEEN '2011-01-01' AND '2011-01-31'
AND
	estatus <> 3
AND
	numero_orden_compra_servicio <> '0'
AND
	numero_compromiso <> '0'
  ;";
  //die($mega_qery);
$recordset=& $conn->Execute($mega_qery);

		?>
<html>
<th align="center"> FACTURAS POR CAUSAR Y PAGAR</th>
     <table border="1" >
   		  <tr>
				<th>NUMERO </th>
				<th>COMPROMETIDO ENERO </th>
				<th>CAUSADO ENERO</th>
				
		 </tr>
<?php
$conta=1;
while(!$recordset->EOF)
	{
					
		///
?>		 
	  <tr>
     			<td><?php echo($conta);?></td>
	 	     	<td><?php echo($recordset->fields("monto_comprometido_enero"));?></td>
		     	<td><?php echo($recordset->fields("monto_causado"));?></td>
		     	
				
				
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