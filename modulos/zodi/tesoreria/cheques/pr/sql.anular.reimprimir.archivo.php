<?php
session_start();
$msgBloqueado= "<img align='absmiddle' src='imagenes/caution.gif' /> Error al Elminar: Este registro tiene campos relacionado con otras tablas";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$id_banco=$_POST[tesoreria_cheque_reimpresion2_pr_banco_id_banco];
	$cuenta_banco=$_POST[tesoreria_cheque_reimpresion2_pr_n_cuenta];
	$n_cheque_form=$_POST[tesoreria_cheque_reimpresion2_pr_n_cheque];
							
$sql = "
			SELECT 
				id_cheques,
				id_banco,
				cuenta_banco,
				numero_cheque,
				tipo_cheque,
				id_proveedor,	
				monto_cheque,
				concepto,
				estatus,
				comentarios,
				porcentaje_itf,
				cheques.id_organismo,
				fecha_cheque,
				cheques.ordenes,
				cedula_rif_beneficiario,
				nombre_beneficiario,
				porcentaje_islr,
				base_imponible,
				secuencia
			FROM 
				cheques
			INNER JOIN
				organismo
			ON
			cheques.id_organismo=organismo.id_organismo	
			WHERE
				cheques.id_cheques='$_POST[tesoreria_cheque_reimpresion2_pr_id_cheque]' 
			AND
				cheques.numero_cheque='$_POST[tesoreria_cheque_reimpresion2_pr_n_cheque]'
			AND
				cheques.secuencia='$_POST[tesoreria_cheque_reimpresion2_pr_secuencia]'
			AND
				cheques.id_banco='$_POST[tesoreria_cheque_reimpresion2_pr_banco_id_banco]'	
			AND	
				cheques.cuenta_banco='$_POST[tesoreria_cheque_reimpresion2_pr_n_cuenta]'
			AND	
				cheques.id_organismo=".$_SESSION["id_organismo"]."	
			AND
				cheques.secuencia='$_POST[tesoreria_cheque_reimpresion2_pr_secuencia]'	
			";
$row= $conn->Execute($sql);

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
if(!$row->EOF)
	{
		
		//--- modificando el n_cheque en la tabla cheques
							if($_POST[tesoreria_cheques_reimpresion2_pr_tipo]=='1')
							{
								 $id_proveedor=$row->fields("id_proveedor");
								 $beneficiario="0";
								 $rif="0";
								 $opcion='1';
							}else
							if($_POST[tesoreria_cheques_reimpresion2_pr_tipo]=='2')
							{
								 $opcion='2';
								 $beneficiario=$row->fields("nombre_beneficiario");
								 $rif=$row->fields("cedula_rif_beneficiario");
							}
								 
								 $tipo_cheque=$row->fields("tipo_cheque");
								 $n_cheque=$row->fields("numero_cheque");
							     $secuencia=$row->fields("secuencia");

							
	//---------------busqueda del ultimo CHEQUE----------------
			$sql_ultimo_emitido = "SELECT 
										ultimo_emitido,cantidad_cheques,secuencia
								   FROM 
										chequeras 
									WHERE
										chequeras.cuenta='$cuenta_banco'
									AND 
										chequeras.id_banco='$id_banco'
									AND
										chequeras.estatus='1'		
								  ";
			if (!$conn->Execute($sql_ultimo_emitido))
					//die ('Error_impresion' );
			//die ('Error 	consulta: '.$conn->ErrorMsg());
			die($sql_ultimo_emitido);	
				$row_emitido= $conn->Execute($sql_ultimo_emitido);
				if(!$row_emitido->EOF)	
				{
						
						/*$n_cheque=$row_emitido->fields("ultimo_emitido");
						$n_cheque=$n_cheque-1;
						$secuencia=$row_emitido->fields("secuencia");
*/
						if($n_cheque==$n_cheque_form)
						{
							$n_cheque_ant=$n_cheque_form;
						}else
						die('Error');
				}				
							
							
														
														
				
	 }
	 else{	$bloqueado=true;
			die("Error-REIMPRIMIR");
			}
		/*	//die($sql);
		if (!$conn->Execute($sql_reg))
		{   //die ('Error'.$sql);
			die ('Error-REIMPRIMIR');
			//die ('Error DUPLICAR REGISTRO: '.$sql_reg);
			}*/

if ($bloqueado){
//die($sql);
	echo (($bloqueado)?$msgBloqueado:'Error-REIMPRIMIR: '.$conn->ErrorMsg().'<br />');
	}
	else
	{
		/*	//---modificando estatus de cheque a reimpreso
			$sql_contab="UPDATE cheques
							SET
								
								reimpreso='1',
								codigo_banco_reimpreso=$_POST[tesoreria_cheque_reimpresion_pr_banco_id_nuevo],
								cuenta_banco_reimpreso=$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo],
								numero_cheque_reimpreso=$n_cheque_form,
								fecha_reimpresion='".date("Y-m-d H:i:s")."',
								usuario_reimpresion=".$_SESSION['id_usuario'].",
								estatus='2'														
							WHERE
								cheques.numero_cheque='$_POST[tesoreria_cheque_reimpresion_pr_n_cheque]'
							AND
								cheques.secuencia='$_POST[tesoreria_cheque_reimpresion_pr_secuencia]'
							AND
								cheques.id_banco='$_POST[tesoreria_cheque_reimpresion_pr_banco_id_banco]'	
							AND	
								cheques.cuenta_banco='$_POST[tesoreria_cheque_reimpresion_pr_n_cuenta]'
							AND	
								cheques.id_organismo=".$_SESSION["id_organismo"]."	
									";		
					if (!$conn->Execute($sql_contab)) 
						//die('Error-act cheque');
						die($sql_contab);	*/
			////////////////////////////--------------------------------------------------------------------------------
			if($tipo_cheque=='1')
			{
				$opcion='0';
				
			}
			
			$responce=$n_cheque."*".$secuencia."*".$tipo_cheque."*".$opcion;
			
	die($responce);		
	}

		
?>