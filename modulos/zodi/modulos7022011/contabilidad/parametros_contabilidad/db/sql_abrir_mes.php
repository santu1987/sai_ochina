<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
////////////////////////////////////////calculando la fecha del cierre mensual/////////////////////////////////////////////////////////
	$dia_f=20;
	$mes_f=date("m")-1;
	$ayo_f=date("Y");
	$fecha_f=date("d/m/Y",mktime(0,0,0,$mes_f,$dia_f,$ayo_f));
//die($fecha_f);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$ayo=date("Y");
$mes=date("m");
$ayo_ant=$ayo-1;	

if($mes==1)
{
	$mes_ant=12;
	$ayo=$ayo_ant;
}
else
if($mes!=1)
	$mes_ant=$mes-1;
$sql_prueba="select * from parametros_contabilidad where (ano='$ayo')  and id_organismo='$_SESSION[id_organismo]'";
//die($sql_prueba);
if (!$conn->Execute($sql_prueba)) 
		die ('Error al registrar: '.$sql_prueba);
$row=$conn->Execute($sql_prueba);
if(!$row->EOF)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/*$comprobante_cod=0;
			//*	
			$sql_comprobante="select * from numeracion_comprobante where ano='$ayo' and id_organismo='1'";
			if (!$conn->Execute($sql_comprobante)) 
				die ('Error en comp: '.$sql_comprobante);
			$row_comp=$conn->Execute($sql_comprobante);
			$comprobante=$row_comp->fields("numero_comprobante");
			$comprobante_mes=substr($comprobante,0,2);
			$len_valor=strlen($comprobante);
			$valor_2=substr($comprobante,1,1);
				if($comprobante_mes=='1')
				{
					$comprobante_cod2='12';
					$mes=12;
				}
				else
				{
					if(($valor_2==0)&&($len_valor==4))
					{
						$comprobante_mes=substr($comprobante,0,1);
					}
					else
					{
						$comprobante_mes=substr($comprobante,0,2);
					}
					
					//$comprobante_cod=settype($comprobante_mes,"integer");
					$comprobante_cod2=$comprobante_mes-1;
					//$mes_ant=$comprobante_mes-1;
					//$mes2=$mes2-1;
				}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				if(strlen($comprobante_mes)=='1')
				{
					$tres="000";
				}else
				{
					$tres="00";
				}*/
$mes2=$row->fields("ultimo_mes");

if($mes_ant==1)
	$mes2=12;
else
	$mes2=$mes2-1;

$dia_desde=1;
	if($mes_ant==12)
		$ayo_desde=$ayo_f-1;
	else
		$ayo_desde=$ayo_f;
$fecha_desde=date("d/m/Y",mktime(0,0,0,$mes_ant,$dia_desde,$ayo_desde));
$fecha_hasta=date("d/m/Y",mktime(0,0,0,$mes,$dia_desde,$ayo_f));			
/*$sql_comprobante="select  * from movimientos_contables
					WHERE id_organismo=$_SESSION[id_organismo]
					AND
						fecha_comprobante>='$fecha_desde' and fecha_comprobante<='$fecha_hasta'
					order by id_movimientos_contables desc
 ";
			if (!$conn->Execute($sql_comprobante)) 
				die ('Error en comp: '.$sql_comprobante);
			$row_comp=$conn->Execute($sql_comprobante);
			if(!$row_comp->EOF)
			{
				$numero_comprobante_nuevo=$row_comp->fields("numero_comprobante");				
				$numero_comprobante_nuevo=$numero_comprobante_nuevo+1;
			}
			else
			{
						$sql_comprobante="select * from numeracion_comprobante where ano='$ayo' and id_organismo='1'";
						if (!$conn->Execute($sql_comprobante)) 
							die ('Error en comp: '.$sql_comprobante);
						$row_comp=$conn->Execute($sql_comprobante);
						$comprobante=$row_comp->fields("numero_comprobante");
						//$comprobante_mes=substr($comprobante,0,2);
						//-------------------------------------------------
							if($comprobante<=10000)
								{
									$comprobante_mes=substr($comprobante,0,1);
								}
								else
								if($comprobante>=9000)
								{
									$comprobante_mes=substr($comprobante,0,2);
								}
						//-------------------------------------------------
						$len_valor=strlen($comprobante);
						$valor_2=substr($comprobante,1,1);
						//die($comprobante_mes);
							if($comprobante_mes=='1')
							{
								$comprobante_cod2='12';
								$mes=12;
							}
							else
							{
								
								
								//$comprobante_cod=settype($comprobante_mes,"integer");
								$comprobante_cod2=$comprobante_mes-1;
								//$mes_ant=$comprobante_mes-1;
								//$mes2=$mes2-1;
							}
							
								$tres="000";
							
					$numero_comprobante_nuevo=$comprobante_cod2.$tres;
									//die($comprobante_cod2);

				}	
*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
/*die($row->fields("ultimo_mes")."==".$mes_ant."-".$row->fields("ultimo_mes")."==".$mes);				
*/
/*if(($row->fields("ultimo_mes")==$mes_ant)||($row->fields("ultimo_mes")==$mes))
{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/														$sql_pago="UPDATE parametros_contabilidad
																	SET
																		
																		ultimo_usuario=".$_SESSION['id_usuario']."	,
																		fecha_cierre_mensual='".$fecha_f."',
																		ultimo_mes='$mes2',
																		ultima_modificacion='".date("Y-m-d H:i:s")."',
																		comentarios='$_POST[contabilidad_apertura_contable_comentarios]'
																	WHERE 
																			ano='$ayo'
																	AND
																		id_organismo=".$_SESSION["id_organismo"]."
																;";	
															/*	UPDATE
																		numeracion_comprobante
																	set
																		numero_comprobante='$numero_comprobante_nuevo'	
																WHERE 
																			ano='$ayo'
																	AND
																		id_organismo=".$_SESSION["id_organismo"]."
																	
															//die($sql_pago);	*/	
																	if (!$conn->Execute($sql_pago)) 
																		die ('Error al registrar: '.$sql_pago);
											
																	
				die("Actualizado");
	/*}
	else
	die("no_ultimo_mes");*/
}else
	die("NoActualizo");
?>