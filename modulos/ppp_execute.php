<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$hoy = date("Y-m-d H:i:s");
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
///////////////////////////////paso 1 armar el mega query
$mega_qery="
SELECT 
		documentos_cxp.ano,	
		substring(documentos_cxp.fecha_documento::varchar,6,2)as mes,
		substring(documentos_cxp.fecha_documento::varchar,9,2)as dia,
		documentos_cxp.id_documentos,
		documentos_cxp.tipo_documentocxp, 
		documentos_cxp.numero_documento,
		documentos_cxp.monto_bruto,
		documentos_cxp.monto_base_imponible,
		(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100) as iva,
		(documentos_cxp.monto_bruto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100)) as total_factura,
		partida,
		monto+(documentos_cxp.porcentaje_iva*documentos_cxp.monto_base_imponible/100) as total_renglon,
		compromiso
  FROM documentos_cxp
  INNER JOIN
  	doc_cxp_detalle
  ON
  doc_cxp_detalle.id_doc=documentos_cxp.id_documentos
  where
	monto_base_imponible2=0
  and
	estatus!='3'
  and 
	orden_pago!='0'				
  order by
	id_documentos
  ;";
$recordset=& $conn->Execute($mega_qery);
while(!$recordset->EOF)
	{
		?>
<html>
     <table>
     <tr>
     	<th>fecha</th>
     	<td><?php echo($recordset->fields("dia")."/".$recordset->fields("mes")."/".$recordset->fields("ano"));?></td>
     </tr>
	<tr>
     	<th>id</th>
     	<td><?php echo($recordset->fields("id_documentos"));?></td>
     </tr>
	<tr>
     	<th>NUMERO</th>
     	<td><?php echo($recordset->fields("numero_documento"));?></td>
     </tr>
	<tr>
     	<th>MONTO B</th>
     	<td><?php echo($recordset->fields("monto_bruto"));?></td>
     </tr>
	<tr>
     	<th>BASE IMP</th>
     	<td><?php echo($recordset->fields("base_imponible"));?></td>
     </tr>
	<tr>
     	<th>iva</th>
     	<td><?php echo($recordset->fields("iva"));?></td>
     </tr>
     <tr>
     	<th>TOTAL FACTURA</th>
     	<td><?php echo($recordset->fields("total_factura"));?></td>
     </tr>
     <tr>
     	<th>PARTIDA</th>
     	<td><?php echo($recordset->fields("partida"));?></td>
     </tr>

     <tr>
     	<th>TOTAL RENGLON</th>
     	<td><?php echo($recordset->fields("total_renglon"));?></td>
     </tr>

     <tr>
     	<th>COMPROMISO</th>
     	<td><?php echo($recordset->fields("compromiso"));?></td>
     </tr>

     </table>  
</html> 
      <?  
	$recordset->MoveNext();
	}
/*
documentos_cxp.orden_pago,
		documentos_cxp.numero_compromiso, 
        documentos_cxp.fecha_documento,
		documentos_cxp.porcentaje_retencion_islr,
		documentos_cxp.estatus,
		documentos_cxp.monto_base_imponible2,
		documentos_cxp.porcentaje_iva2,
		*/
";
?>