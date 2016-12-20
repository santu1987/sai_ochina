<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sql="
	SELECT 
	\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador,	
	(
		(
			(monto_presupuesto [3] )+
			(monto_traspasado [3]  )+
			(monto_modificado [3] )
		)-
			(monto_comprometido [3]) 
	)AS monto_total,
	monto_presupuesto [3] AS monto_act
FROM 
	\"presupuesto_ejecutadoR\"
ORDER BY
	\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador
";
$rs_cierre =& $conn->Execute($sql);
if (!$rs_cierre->EOF)
{
	$conn->StartTrans();
	while (!$rs_cierre->EOF) {
	$monto_total = $rs_cierre->fields("monto_total");
	$monto_act = $rs_cierre->fields("monto_act") - $rs_cierre->fields("monto_total");
	//$traspasado_actual = $rs_cierre->fields("monto_traspasado_actual");
	$sql2="	
		UPDATE 
			\"presupuesto_ejecutadoR\" 
		SET 
			monto_presupuesto[3]  = '".$monto_act."' ,
			monto_presupuesto[4]  = '".$monto_total."' 
		WHERE 
			id_presupuesto_ejecutador=".$rs_cierre->fields("id_presupuesto_ejecutador")."
	";
		if (!$conn->Execute($sql2)) 	{
			$conn->CompleteTrans();
			die ('<div id="mensaje"><p>Error al Procesar, no se efectuo el cierre de prespuesto de ley: '.$conn->ErrorMsg().'</p></div>');
		}
		
		$rs_cierre->MoveNext();
	}
	$conn->CompleteTrans();
	die("Ok");
}
else
{
	die("EOF");
	//die ($sql);

}
?>