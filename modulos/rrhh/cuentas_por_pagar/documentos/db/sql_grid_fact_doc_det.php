<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$fecha2 = date("Y-m-d H:i:s");
$partidas=$_POST[partida_comp];
$id_doc=$_POST[cuentas_por_pagar_db_id];

$sql1="select count(id) from doc_cxp_detalle where id_doc='$id_doc' and partida='$partidas'";
$row_det1=& $conn->Execute($sql1);
				//	die($sql_doc_det);
					if(!$row_det1->EOF)
					{
						$sql2="select id,monto from doc_cxp_detalle where id_doc='$id_doc' and partida='$partidas'";
						$row_det2=& $conn->Execute($sql2);
						if(!$row_det2->EOF)
						{
							$responce=number_format($row_det2->fields("monto"),2,',','.');
							
						}else{$responce=0;}
					}else{$responce=0;}
echo($responce);					
?>					