<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


//verificando datos
$mes=$_POST[posicion];
$mes=$_POST[posicion];
$debe=$_POST[debe];
$haber=$_POST[haber];
$debe2 = str_replace(".","",$debe);
$debe_def = str_replace(",",".",$debe2);
$haber2 = str_replace(".","",$haber);
$haber_def = str_replace(",",".",$haber2);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

												$sql ="
												UPDATE 
																		saldo_contable
															SET
																		debe[".$mes."]= '$debe_def',
																		haber[".$mes."]= '$haber_def'
												WHERE
															cuenta_contable='$_POST[contabilidad_auxiliares_db_id_cuenta]'
												and
													ano='2011'									
																	
																
																";
														
													
								//die($sql);
											if (!$conn->Execute($sql)) 
											{
												$responce='Error al Actualizar: '.$conn->ErrorMsg();
											}
											else
											{
													$responce="Registrado";
													die($responce);
											}
											
												
												
		
			
?>