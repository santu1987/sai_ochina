<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

	$sql = "SELECT
			cheques.id_cheques,
			cheques.cuenta_banco,
			cheques.nombre_beneficiario,
			cheques.monto_cheque as monto,
			cheques.fecha_cheque as emitido,
			cheques.estado_fecha[3] as entregado,
			cheques.nombre_conformador as confor
		FROM
			cheques
		WHERE
			cheques.estado[3]='1'
		AND
			cheques.numero_cheque='$_POST[cheques_conformado_numero]'
			";	
	$row=& $conn->Execute($sql);
	if($row->fields("nombre_beneficiario")!=""){
		$beneficiario=$row->fields("nombre_beneficiario");
	}
	else{
			$sql2 = "SELECT
				cheques.id_cheques,
				cheques.cuenta_banco,
				proveedor.nombre as provee,
				cheques.monto_cheque as monto,
				cheques.fecha_cheque as emitido,
				cheques.estado_fecha[3] as entregado,
				cheques.nombre_conformador as confor
			FROM
				cheques
			INNER JOIN
				proveedor
			ON
				proveedor.id_proveedor=cheques.id_proveedor
			WHERE
				cheques.estado[3]='1'
			AND
				cheques.numero_cheque='$_POST[cheques_conformado_numero]'
				";	
		$row2=& $conn->Execute($sql2);
		$beneficiario=$row2->fields("provee");
	}
	if($row->fields("id_cheques")!=""){
		$arreglo = $row->fields("id_cheques")."*".$row->fields("cuenta_banco")."*".$beneficiario."*".$row->fields("monto")."*".$row->fields("emitido")."*".$row->fields("entregado")."*".$row->fields("confor");
	}
	else{$arreglo = "";}
	echo $arreglo;
?>