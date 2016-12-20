<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

//---------------busqueda del ultimo CHEQUE----------------
$sql_saldo_actual = "SELECT 
							 saldo_actual
					   FROM 
					   		 banco_cuentas
					   	WHERE
							cuenta_banco='$_POST[tesoreria_cheques_db_n_cuenta]'
			   		    AND 
			   		 		id_banco='$_POST[tesoreria_cheques_db_banco_id_banco]'
						AND
							estatus='1'		
					  ";
	$row_saldo_actual= $conn->Execute($sql_saldo_actual);
	if(!$row_saldo_actual->EOF)	
	{		

				$saldo_actual=$row_saldo_actual->fields("saldo_actual");
			    $monto_cheque=$_POST[tesoreria_cheques_db_monto_pagar];
				$saldo_total=($saldo_actual)-($monto_cheque);
					if($saldo_total<'0')
					{
						die("no_disponible_saldo");
					}else
					if($saldo_total=='0')
					{
						die("disponible_saldo_cero");
					}
					else
					if($saldo_total>'0')
					{
						die("sin_novedad");
					}
						
	}

?>