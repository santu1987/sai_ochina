<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$monto=$_POST['cuentas_x_pagar_db_orden_monto'];
			if ($_POST['cuentas_x_pagar_db_orden_monto']!="")
			{
			$opcion=$_POST['cuentas_por_pagar_orden_db_op_oculto'];
			if($opcion=='1')
			{
					$sql = "	
									INSERT INTO 
										\"orden_pagoE\"
										(
											monto_pagar,
											fecha_orden_pago,
											numero_cheque,
											id_proveedor,
											id_organismo,
											secuencia
											) 
										VALUES
										(
											'".str_replace(",",".",$monto)."',
											'".date("Y-m-d H:i:s")."',
											0,
											'$_POST[cuentas_x_pagar_db_proveedor_id]',
											".$_SESSION["id_organismo"].",
											0														
										)
								";
				}else
				if($opcion=='2')
			   {
					$sql = "	
									INSERT INTO 
										\"orden_pagoE\"
										(
											monto_pagar,
											fecha_orden_pago,
											numero_cheque,
											cedula_rif_beneficiario,
											beneficiario,
											id_organismo,
											secuencia
											) 
										VALUES
										(
											'".str_replace(",",".",$monto)."',
											'".date("Y-m-d H:i:s")."',
											'$_POST[cuentas_por_pagar_db_empleado_codigo]',
											'$_POST[cuentas_por_pagar_orden_db_empleado_nombre]',
											0,
											'$_POST[cuentas_x_pagar_db_proveedor_id]',
											".$_SESSION["id_organismo"].",
											0														
										)
								";
				   }				
			}
	

if (!$conn->Execute($sql)) 
	//die ('Error al Registrar: '.$sql);
die ('Error al Registrar: '.$conn->ErrorMsg());
else
{

			$Sql="
						SELECT 
							(\"orden_pagoE\".numero_orden_pago)
						FROM 
							\"orden_pagoE\"
						INNER JOIN 
							organismo 
						ON 
							\"orden_pagoE\".id_organismo =organismo.id_organismo
						order by	
						(\"orden_pagoE\".numero_orden_pago) desc			
					";
			//die($Sql);
			$row=& $conn->Execute($Sql);
			if (!$row->EOF)
			{
				$orden_pago = $row->fields("numero_orden_pago");
			}


	$sql = "	
							INSERT INTO 
								\"orden_pagoD\"
								(
									numero_orden_pago,
									fecha_factura,
									numero_factura
									
							    	) 
								VALUES
								(
								   	'$orden_pago',
                 					'".date("Y-m-d H:i:s")."',
									'$orden_pago'																						
								)
						";
	if (!$conn->Execute($sql)) 
	//die ('Error al Registrar: '.$sql);
		die ('Error al Registrar: '.$conn->ErrorMsg());
	else
	die("Registrado");
	//die("$sql");					

}
	
?>