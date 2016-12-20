<?php

session_start();

require_once('../../controladores/db.inc.php');
require_once('../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha =date("Y-m-d H:i:s") ;
//////SELECCIONANDO TODA LA INFORMACION QUE NECESITO;
$sql_saldos=
			"SELECT saldo_contable.id_saldo_contable,
					saldo_contable.id_organismo,
					saldo_contable.ano,
					saldo_contable.cuenta_contable,
					saldo_contable.cuenta_auxiliar, 
       				saldo_contable.saldo_inicio,
					saldo_contable.debe,
					saldo_contable.haber,
					saldo_contable.comentarios,
					saldo_contable.ultimo_usuario,
					saldo_contable.ultima_modificacion
  			FROM saldo_contable";

$row=& $conn->Execute($sql_saldos);
while (!$row->EOF)
{	
	$id_saldo=$row->fields("id_saldo_contable");	
	$sql_update_cuentas="UPDATE saldo_contable
							SET  saldo_inicio[9]=0,
								 debe[9]=0,
								 haber[9]=0
							WHERE id_saldo_contable=$id_saldo";
//die($sql_update_cuentas);
								if (!$conn->Execute($sql_update_cuentas)) 
											{
												
												die("error"."".$sql_update_cuentas.$cont.$cont2);
											}
											else
											{
											
											$cont++;
											}
	$row->MoveNext();
$cont2++;
	
}
die("Cantidad de registros registrados : ".$cont." de ".$cont2);							
						
?>