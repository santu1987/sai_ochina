<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
include_once('../../../../controladores/numero_to_letras.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$check = $_POST["tesoreria_cheques_db_itf"]; 
$opcion=$_POST['tesoreria_cheque_manual_db_otro_beneficiario_oc'];
$fecha2 = date("Y-m-d H:i:s");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_tesoreria WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF)
{
	$fecha_cierre_anual = $row_fecha_cierre->fields('fecha_ultimo_cierre_anual');
	$fecha_cierre_mensual = $row_fecha_cierre->fields('fecha_ultimo_cierre_mensual');

}
list($dia1,$mes1,$ano1)=split("-",$fecha_cierre_mensual);
list($dia2,$mes2,$ano2)=split("-",$fecha2);
list($dia3,$mes3,$ano3)=split("-",$fecha_cierre_anual);
if(($dia2 >= $dia1) && ($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
}else
if(($mes2 >= $mes1) && ($ano2 >= $ano1))
{
	$cerrado="mes";
}
if(($dia2 >= $dia3) && ($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}else
if(($mes2 >= $mes3) && ($ano2 >= $ano3))
{
	$cerrado="ano";
}
/*if(($cerrado!="ano")||($cerrado!="mes"))
{*/
//die($opcion);
			//------ verificando si la cuenta y el banco tienen chequera creada
			if($opcion=='0')
			{
					$sql_cheque = "SELECT 
										id_cheques
								   FROM 
										cheques 
								   WHERE 
										cuenta_banco='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
								   AND 
										 id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
									AND 
										id_proveedor='$_POST[tesoreria_cheque_manual_pr_proveedor_id]'
									AND
										numero_cheque='$_POST[tesoreria_cheque_manual_db_n_precheque]'
									AND
										cheques.tipo_cheque='2'			 
									AND		
										cheques.id_organismo=$_SESSION[id_organismo]		 
										 ";
			}
			else
			if($opcion='1')
			{
						$sql_cheque = "SELECT 
										id_cheques
								   FROM 
										cheques 
								   WHERE 
										cuenta_banco='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
								   AND 
										 id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
									
									AND
										numero_cheque='$_POST[tesoreria_cheque_manual_db_n_precheque]'
									AND
										cheques.tipo_cheque='2'			 
									AND		
										cheques.id_organismo=$_SESSION[id_organismo]		 
										 ";
			
			}				 
				//die($sql_cheque);
					if (!$conn->Execute($sql_cheque))die ($sql_cheque);
						$row_verificacion1= $conn->Execute($sql_cheque);
			////////
			//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			$sql_saldo_actual = "SELECT 
										 saldo_actual
								   FROM 
										 banco_cuentas
									WHERE
										cuenta_banco='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
									AND 
										id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
									AND
										estatus='1'		
								  ";
				$row_saldo_actual= $conn->Execute($sql_saldo_actual);
				if(!$row_saldo_actual->EOF)	
				{		
			
							$saldo_actual=$row_saldo_actual->fields("saldo_actual");
							$monto_cheque=$_POST[tesoreria_cheque_manual_db_monto_pagar];
							$monto_cheque= str_replace(".","",$monto_cheque);
							$monto_cheque=str_replace(",",".",$monto_cheque);
			
							$saldo_total=($saldo_actual)-($monto_cheque);
								if($saldo_total<'0')
								{
									//die("no_disponible_saldo");
								}
								else
								{
									///
									$sql = "UPDATE banco_cuentas 
									 SET
										saldo_actual='$saldo_total'
									WHERE cuenta_banco='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
									AND
										id_organismo=$_SESSION[id_organismo]
												";
								
								if (!$conn->Execute($sql)) {
								
								die ($sql);}
								////
								}
									
				}
			
			//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------			
			
			////////
			//---------------busqueda del ultimo CHEQUE----------------
			$sql_ultimo_emitido = "SELECT 
										ultimo_emitido,cantidad_cheques,secuencia
								   FROM 
										chequeras 
									WHERE
										cuenta='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
									AND 
										id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
									AND
										estatus='1'		
								  ";
			if (!$conn->Execute($sql_ultimo_emitido))
					die ('Error_impresion' );
			//die ('Error 	consulta: '.$conn->ErrorMsg());
			//die($sql_ultimo_emitido);	
				$row_emitido= $conn->Execute($sql_ultimo_emitido);
				if(!$row_emitido->EOF)	
				{
					$cantidad=$row_emitido->fields("cantidad_cheques");
					$n_cheque=$row_emitido->fields("ultimo_emitido");
					$secuencia=$row_emitido->fields("secuencia");
					$secuencia2=$secuencia;
					$n_cheque_resultado=intval($n_cheque)+1;
					$n_ultimo=intval($n_cheque_resultado)+1;
							$proximo_emitir=$n_cheque_resultado;
							$estatus=1;
						
						//}
					//////////////////////////////////////////////////
					
					$sql_def=" UPDATE chequeras
									SET
											ultimo_emitido='$proximo_emitir',
											estatus=$estatus,
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
									WHERE
										id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
									AND	
										cuenta='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
									AND
										secuencia='$secuencia2'";
					// die($sql_def);		
					if (!$conn->Execute($sql_def))
						{	echo ('Error_impresion' );}
					//die ('Error al modificar datos chequera: '.$sql_chequeras);
					
				}
				else
					die('chequera_agotada');
				if($n_cheque!="")
				{
					//--- modificando el n_cheque en la tabla cheques
					$sql_cheques=" UPDATE cheques
							SET
									numero_cheque='$n_cheque',
									estatus='2',
									secuencia='$secuencia',
									fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
									ultimo_usuario=".$_SESSION['id_usuario'].",
									fecha_firma='".date("Y-m-d H:i:s")."',
									estado[1]='1',
									estado_fecha[1]='".date("Y-m-d H:i:s")."'
							WHERE
								id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
							AND	
								cuenta_banco='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
							AND
								numero_cheque='$_POST[tesoreria_cheque_manual_db_n_precheque]'
							AND
								cheques.tipo_cheque='2'			 
							AND		
							cheques.id_organismo=$_SESSION[id_organismo]	
							";
					//die($sql_cheques);			
					if (!$conn->Execute($sql_cheques)) 
							die ('Error_impresion' );
					//die ('Error al modificar datos de cheques: '.$sql_cheques);
				
				
				if($opcion=='0')
				{
						$sql_ultimo_emitido2 = $sql_cheque = "SELECT 
								id_cheques,numero_cheque,secuencia
						   FROM 
								cheques 
						   WHERE 
								cuenta_banco='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
						   AND 
								 id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
							AND 
								id_proveedor='$_POST[tesoreria_cheque_manual_pr_proveedor_id]'
							AND
								numero_cheque='$n_cheque'
							AND
								cheques.tipo_cheque='2'			 
							AND		
								cheques.id_organismo=$_SESSION[id_organismo]		 
								 ";
				}
					else
					if($opcion=='1')
					{
						$sql_ultimo_emitido2 = $sql_cheque = "SELECT 
								id_cheques,numero_cheque,secuencia
						   FROM 
								cheques 
						   WHERE 
								cuenta_banco='$_POST[tesoreria_cheque_manual_db_n_cuenta]'
						   AND 
								 id_banco='$_POST[tesoreria_cheque_manual_db_banco_id_banco]'
							
							AND
								numero_cheque='$n_cheque'
							AND
								cheques.tipo_cheque='2'			 
							AND		
								cheques.id_organismo=$_SESSION[id_organismo]		 
								 ";
					}
								 
					if (!$conn->Execute($sql_ultimo_emitido2))
						die ('Error_impresion' );
					//die ('Error consulta numero de cheque: '.$conn->ErrorMsg());
					$row_emitido2= $conn->Execute($sql_ultimo_emitido2);
			
				
				}
			
			$responce=$n_cheque."*".$secuencia;
			die($responce);
				//die($responce);
				//die($row_emitido2->fields("numero_cheque")."*".$row_emitido2("secuencia")."*");
				 //}
/*}else
die("cerrado");		*/		 
?>