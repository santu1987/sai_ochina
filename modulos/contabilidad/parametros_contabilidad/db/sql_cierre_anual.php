<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$fecha = date("Y-m-d H:i:s");
$ano=$_POST['contabilidad_cierre_ano_anual'];
$numero_comprobante_nuevo_ayo='1000';
$ano2=$ano+1;
$id_para=$_POST['parametro_contabilidad_db_id'];
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$sql_prueba="select * from parametros_contabilidad where   ultimo_mes='12' and id_organismo='$_SESSION[id_organismo]'";
//ano=$ano
if (!$conn->Execute($sql_prueba)) 
		die ('Error al registrar: '.$sql_prueba);
$row=$conn->Execute($sql_prueba);
if(!$row->EOF)
{

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//consultando los saldos contables
				$sql_cuentas = "select 
									saldo_contable.id_saldo_contable, saldo_contable.id_organismo,saldo_contable.ano,
									saldo_contable.cuenta_contable, saldo_contable.cuenta_auxiliar, 
									saldo_contable.saldo_inicio, saldo_contable.debe,saldo_contable.haber, saldo_contable.comentarios,			                        saldo_contable.ultimo_usuario, saldo_contable.ultima_modificacion,
									cuenta_contable_contabilidad.cuenta_contable as cuentas_contab,
									cuenta_contable_contabilidad.id_naturaleza_cuenta,
									naturaleza_cuenta.codigo,
									saldo_contable.id_organismo 
							from
								saldo_contable
							INNER JOIN
									cuenta_contable_contabilidad
								ON
									cuenta_contable_contabilidad.id=saldo_contable.cuenta_contable	
							INNER JOIN
									naturaleza_cuenta
								ON
									cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id	
							where
								ano='$ano'			
							AND
							saldo_contable.id_organismo=$_SESSION[id_organismo]
						order by
							saldo_contable.ano,saldo_contable.cuenta_contable	
						";
					//	die($sql_cuentas);
			$row=& $conn->Execute($sql_cuentas);
			if(!$row->EOF)
			{
				while(!$row->EOF)
				{
					/////////-cuentas contables-//////////////////////////////////////////////
					$id_organismo=$row->fields("id_organismo");
					$cuenta_contable=$row->fields("cuenta_contable");
					$cuenta_auxiliar=$row->fields("cuenta_auxiliar");
					if($cuenta_auxiliar=='')
					{
						$cuenta_auxiliar='0';
					}
					$saldo_inicio=$row->fields("saldo_inicio");
					/*$debe=$row->fields("debe");
					$haber=$row->fields("haber");*/
					$comentarios=$row->fields("comentarios");
					if($comentarios=='')
					{
						$comentarios='0';
					}
					$ultimo_user=$row->fields("ultimo_usuario");
					$ultima_mod=$row->fields("ultima_modificacion");
					///// verificando el saldo inicio segun la naturaleza de la cuenta
					if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT'))
					{
						
						
						$med=strlen($row->fields("debe"));
						$med=$med-2;
						$debe=substr($row->fields("debe"),1,$med);
						$debe_vector=split(",",$debe);
						
						$med2=strlen($row->fields("haber"));
						$med2=$med2-2;
						$haber=substr($row->fields("haber"),1,$med2);
						$haber_vector=split(",",$haber);
						$saldo_inicio=$row->fields("saldo_inicio");
						$saldo_vector=split(",",$saldo_inicio);
						//-
						$ce=0;
						
						
						$debe_total=0;
						$haber_total=0;
						$total_cuenta_debe_haber="";
						
						//calculando el monto del saldo anterior	
							while($ce!=12)
							{
								$debe_total=$debe_total+$debe_vector[$ce];
								$haber_total=$haber_total+$haber_vector[$ce];
								$ce++;
								//echo($debe_total."-".$haber_total);
							}
						
							if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G'))
							{
								$total_cuenta_debe_haber=$debe_total-$haber_total;
							$debe='{'.$total_cuenta_debe_haber.',0,0,0,0,0,0,0,0,0,0,0}';
							$haber='{0,0,0,0,0,0,0,0,0,0,0,0}';
	
							}
							else
							if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT')or($row->fields("codigo")=='I'))
							{
								$total_cuenta_debe_haber=$haber_total-$debe_total;
								$haber='{'.$total_cuenta_debe_haber.',0,0,0,0,0,0,0,0,0,0,0}';
								$debe='{0,0,0,0,0,0,0,0,0,0,0,0}';
							}
							$saldo_inicial='{'.$total_cuenta_debe_haber.',0,0,0,0,0,0,0,0,0,0,0}';
							
					}//fin verificacvion
					else
					{
						$saldo_inicial='{0,0,0,0,0,0,0,0,0,0,0,0}';
						$debe='{0,0,0,0,0,0,0,0,0,0,0,0}';
						$haber='{0,0,0,0,0,0,0,0,0,0,0,0}';
					}					
					$ce=0;
					$debe_total=0;
					$haber_total=0;
					$total_cuenta_debe_haber=0;
					
						/*$saldo_vector=split(",",$saldo_inicio);
						$debe=substr($row->fields("debe"),1,11);
						$debe_vector=split(",",$debe);
						$haber=substr($row->fields("haber"),1,11);
						$haber_vector=split(",",$haber);
						$c_ini=0;
						$mes_topt=12;
							while($c_ini!=$mes_topt)
							{
								//$suma_saldos=$suma_saldos+$saldo_vector[$c_ini];
								$debe_calc=$debe_calc+$debe_vector[$c_ini];
								$haber_calc=$haber_calc+$haber_vector[$c_ini];
								echo("Contador".$c_ini." ".$haber_vector[$c_ini]);
								$c_ini++;				}die();
							if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G'))
							{
								$suma_saldos=$debe_calc-$haber_calc;
							}
							else
							if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT')or($row->fields("codigo")=='I'))
							{
								$suma_saldos=$haber_calc-$debe_calc;
							}	
					$saldo_inicio='{'.$suma_saldos.',0,0,0,0,0,0,0,0,0,0,0}';
					*/
					/////---/// VERIFICANDO QUE LA CUENTA NO EXISTA-------///////////////
						$sql_cuentas2 = "select * 
								from
									saldo_contable
								where
									ano='$ano2'	
								AND
									cuenta_contable='$cuenta_contable'		
								AND
								id_organismo=$_SESSION[id_organismo]
							";
						$row2=& $conn->Execute($sql_cuentas2);
					if($row2->EOF)	
					{
						$sql_agregar="
											INSERT INTO
											saldo_contable(
															id_organismo,
															ano,
															cuenta_contable ,
															cuenta_auxiliar ,
															saldo_inicio ,
															debe,
															haber,
															comentarios,
															ultimo_usuario ,
															ultima_modificacion 
														)
												VALUES
														(
															'$id_organismo',
															$ano2,
															$cuenta_contable,
															$cuenta_auxiliar,
															'$saldo_inicial',
															'$debe',
															'$haber',
															'$comentarios',
															$ultimo_user,
															'$ultima_mod'
														);
											$sql_agregar_aux	
												
						
						";
					
						//die($sql_agregar);
					$conter=$conter+1;
					$cuenta=$row->fields('cuenta_contable');
					//	echo($sql_agregar);
						/*if (!$conn->Execute($sql_agregar)) 
						{
							die('error al registrar en archivo numero'.$conter." ".'cuenta_contable:-'.$cuenta.$sql_agregar);
							}*/
					
				}///fin de if archivos existentes
						
				$row->MoveNext();
				$saldo_inicial="";
				$debe="";
				$haber="";
				
					
				}	
			
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//consulktando los auxiliares
				$sql_auxiliares = "
					select	
							saldo_auxiliares.id_saldo_auxiliar,
							saldo_auxiliares.id_organismo,
							saldo_auxiliares.ano, 
							saldo_auxiliares.cuenta_contable,
							saldo_auxiliares.cuenta_auxiliar, 
							saldo_auxiliares.saldo_inicio,
							saldo_auxiliares.debe as debe_aux,
							saldo_auxiliares.haber as haber_aux,
							saldo_auxiliares.comentarios,
							saldo_auxiliares.ultima_modificacion ,
							saldo_auxiliares.ultimo_usuario,
							cuenta_contable_contabilidad.cuenta_contable as cuentas_contab,
							cuenta_contable_contabilidad.id_naturaleza_cuenta,
							naturaleza_cuenta.codigo
						from
							saldo_auxiliares
								INNER JOIN
										cuenta_contable_contabilidad
									ON
										cuenta_contable_contabilidad.id=saldo_auxiliares.cuenta_contable	
								INNER JOIN
										naturaleza_cuenta
									ON
										cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id	
							where
								ano='$ano'			
							AND
							saldo_auxiliares.id_organismo=$_SESSION[id_organismo]
						order by
							saldo_auxiliares.ano,saldo_auxiliares.cuenta_contable	
						"; 
		//	die($sql_auxiliares);
			$row_auxiliares=& $conn->Execute($sql_auxiliares);
			
			////////////////////////////////////////			
			if(!$row_auxiliares->EOF)
			{
				while(!$row_auxiliares->EOF)
				{
						///////-auxiliares-///////////////////////////////////////
						  $cuenta_contable_aux=$row_auxiliares->fields("cuenta_contable");
						  $cuenta_auxiliar_aux=$row_auxiliares->fields("cuenta_auxiliar");
						  $saldo_inicio=$row_auxiliares->fields("saldo_inicio");
						  $debe_aux=$row_auxiliares->fields("debe_aux");
						  $haber_aux=$row_auxiliares->fields("haber_aux");
						   /* $debe_aux='{0,0,0,0,0,0,0,0,0,0,0,0}';
							$haber_aux='{0,0,0,0,0,0,0,0,0,0,0,0}';*/
						  $comentarios_aux=$row_auxiliares->fields("comentarios");
						if($comentarios_aux=='')
						{
							$comentarios_aux='0';
						}
						  $ultima_modificacion_aux=$row_auxiliares->fields("ultima_modificacion");
						  $ultimo_usuario_aux=$row_auxiliares->fields("ultimo_usuario");	///// verificando el saldo inicio segun la naturaleza de la cuenta
					if(($row_auxiliares->fields("codigo")=='A   ')||($row_auxiliares->fields("codigo")=='P   ') ||($row_auxiliares->fields("codigo")=='PAT'))
					{
						$medax=strlen($row_auxiliares->fields("debe_aux"));
						$medax=$medax-2;
						$debeax=substr($row_auxiliares->fields("debe_aux"),1,$medax);
						$debe_vectorax=split(",",$debeax);
						
						$medax2=strlen($row_auxiliares->fields("haber_aux"));
						$medax2=$medax2-2;
						$haberax=substr($row_auxiliares->fields("haber_aux"),1,$medax2);
						$haber_vectorax=split(",",$haberax);
						$saldo_inicioax=$row_auxiliares->fields("saldo_inicio");
						$saldo_vectorax=split(",",$saldo_inicioax);
						//-
						$ce=0;
						
						
						$debe_totalax=0;
						$haber_totalax=0;
						$total_cuenta_debe_haberax="";
						$ceax;
						//calculando el monto del saldo anterior	
							while($ceax!=12)
							{
								$debe_totalax=$debe_totalax+$debe_vectorax[$ceax];
								$haber_totalax=$haber_totalax+$haber_vectorax[$ceax];
								$ceax++;
								//echo($debe_total."-".$haber_total);
							}
						
							if(($row_auxiliares->fields("codigo")=='A   ')||($row_auxiliares->fields("codigo")=='G'))
							{
								$total_cuenta_debe_haberax=$debe_totalax-$haber_totalax;
								
							}
							else
							if(($row_auxiliares->fields("codigo")=='P   ') ||($row_auxiliares->fields("codigo")=='PAT')or($row_auxiliares->fields("codigo")=='I'))
							{
								$total_cuenta_debe_haberax=$haber_totalax-$debe_totalax;
							}
							$saldo_inicialax='{'.$total_cuenta_debe_haberax.',0,0,0,0,0,0,0,0,0,0,0}';
							
					}//fin verificacvion
					$debeax='{0,0,0,0,0,0,0,0,0,0,0,0}';
					$haberax='{0,0,0,0,0,0,0,0,0,0,0,0}';
					$ceax=0;
					$debe_totalax=0;
					$haber_totalax=0;
					$total_cuenta_debe_haberax=0;
					
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$sql_cuentas3 = "select * 
												from
													saldo_auxiliares
												where
													ano='$ano2'	
												AND
													cuenta_auxiliar='$cuenta_auxiliar_aux'		
												AND
													cuenta_contable='$cuenta_contable_aux'
												AND	
													id_organismo='$_SESSION[id_organismo]'
							";
							//die($sql_cuentas3);
						$row3=& $conn->Execute($sql_cuentas3);
						if($row3->EOF)	
						{	
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$sql_agregar_aux="
											INSERT INTO
											saldo_auxiliares
												(
													  id_organismo,
													  ano,
													  cuenta_contable,
													  cuenta_auxiliar,
													  saldo_inicio,
													  debe,
													  haber,
													  comentarios,
													  ultima_modificacion,
													  ultimo_usuario
												)
											VALUES
												(
													 '$id_organismo',
													  $ano2,
													  $cuenta_contable_aux,
													  $cuenta_auxiliar_aux,
													  '$saldo_inicialax',
													  '$debeax',
													  '$haberax',
													  '$comentarios_aux',
													  '$ultima_modificacion_aux',
													  '$ultimo_usuario_aux'
												);
												UPDATE
														tipo_comprobante
													set
														numero_comprobante='$numero_comprobante_nuevo_ayo'	
												WHERE 
															
														id_organismo=".$_SESSION["id_organismo"]."
												";	
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////													
					$conter=$conter+1;
					$cuenta=$row->fields('cuenta_contable');
					//echo($sql_agregar_aux);
					/*if (!$conn->Execute($sql_agregar_aux)) 
					{
						die('error al registrar en archivo numero'.$conter." ".'cuenta_contable:-'.$cuenta.$sql_agregar_aux);
						}*/
			////////////////////////
						}/*else die("error");*/
			///////////////////	fin de if archivos existentes			
			
				$row_auxiliares->MoveNext();
				  
				}
			}
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			die('Actualizado');
/////////////////////////////////////////////////////
}
//fin de verificacion
else
die("no_ano");
//	'$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]'
//$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]
?>