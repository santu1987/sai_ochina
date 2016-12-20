<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

//---------------busqueda del ultimo CHEQUE----------------
$sql_ultimo_emitido_secuencia = "SELECT 
									max(secuencia)
							   FROM 
									chequeras 
								WHERE
									cuenta='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]'
								AND 
									id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo]'
								AND
									estatus='2'		
							  ";

		$row_emitido_secuencia= $conn->Execute($sql_ultimo_emitido_secuencia);
		
	if(!$row_emitido_secuencia->EOF)	
	{	
			$sec=$row_emitido_secuencia->fields("max");
			
			//
			$sql_ultimo_emitido = "SELECT 
									ultimo_emitido,cantidad_cheques,secuencia,ultimo_emitido,primer_cheque
							   FROM 
									chequeras 
								WHERE
									cuenta='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]'
								AND 
									id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo]'
								AND
									estatus='2'	
								AND
									secuencia='$sec'";		
						 
			$row_emitido= $conn->Execute($sql_ultimo_emitido); 
			if(!$row_emitido->EOF)	
			{
			
			
			//
						$cantidad=$row_emitido->fields("cantidad_cheques");
						$n_cheque=$row_emitido->fields("ultimo_emitido");
						$secuencia=$row_emitido->fields("secuencia");
						$secuencia2=$secuencia;
						$n_cheque_resultado=intval($n_cheque)+1;
						$n_ultimo=intval($n_cheque_resultado)+1;
						
						$primer_cheque=$row_emitido->fields("primer_cheque");
						$primer=intval($primer_cheque);
						$total=	$n_cheque_resultado-$primer;
						//verificando en el cas que se termine la chequera n_cheque>cantidad
							//if($n_cheque>$cantidad)
							
									
									//$secuencia=$secuencia+1;
									
									
										$sql_activar_chequeras=" UPDATE chequeras
																	SET
																			estatus='1',
																			fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
																			ultimo_usuario=".$_SESSION['id_usuario']."
																	WHERE
																		id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo]'
																	AND	
																		cuenta='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo]'
																	AND
																		secuencia='$secuencia'
																	";
											
												if (!$conn->Execute($sql_activar_chequeras))
													{die($sql_activar_chequeras);	
													//die ('Error activando chequera' );
													}										
														
											$activa="activa";												//////////////////////////////////////////////////
												
									
										
							
						if($activa=="activa")
						{
						$responce="activa"."*".$secuencia;
						die($responce);
						}/*else
						if(!$activa)
						{
							
							die("Error");
						}*/
			}
	}
		//die($sql_ultimo_emitido);
		die("Error");

?>