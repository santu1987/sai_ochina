<?php session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$user=$_SESSION['id_usuario'];
$fecha = $_POST[contabilidad_comp_pr_fecha];
$ano=substr($fecha,6,4);
$mes=substr($fecha,3,2);
if($ano!="")
{
	$where.="and ano_comprobante='$ano'";	

}
$contabilidad_comp_pr_numero_comprobante=$_POST[contabilidad_comp_pr_numero_comprobante];
$tipo_comprobante=$_POST[contabilidad_comp_pr_tipo];
if($user!='1')
{
	$where.="and movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'";
}
	//$where.="and movimientos_contables.estatus!='3'";

if(($contabilidad_comp_pr_numero_comprobante!="")&&($tipo_comprobante!=""))
{
	$compro=$tipo_comprobante.$contabilidad_comp_pr_numero_comprobante;
/////////////////////////////////////////////////////////////
				$debe=0;
				$haber=0;
				$resta=0;
				//************************************************************************
				$limit = 15;
				if(!$sidx) $sidx =1;
				
					$sql_estatus="SELECT 
									movimientos_contables.estatus,numero_comprobante,id_tipo_comprobante				
								FROM 
									movimientos_contables
								inner join
										organismo
										on
										movimientos_contables.id_organismo=organismo.id_organismo
								where		
										(organismo.id_organismo =".$_SESSION['id_organismo'].")
								$where		 
								ORDER BY 
									 movimientos_contables.id_movimientos_contables desc
								";
			//die($sql_estatus);				
				if (!$conn->Execute($sql_estatus)) 	{
					$responce="error"."*".$debe."*".$haber;		
											}
					$row=& $conn->Execute($sql_estatus);
					// constructing a JSONand							numero_comprobante=$contabilidad_comp_pr_numero_comprobante		
				
					if (!$row->EOF) 
					{//die($row->fields("estatus"));
						$tipo_comprobante=$row->fields("id_tipo_comprobante");

						if($row->fields("estatus")=='0')
						{
							$sql="SELECT numero_comprobante,codigo_tipo_comprobante
										 FROM tipo_comprobante
										inner join
											organismo
										on
											tipo_comprobante.id_organismo=organismo.id_organismo
										where		
												(organismo.id_organismo =".$_SESSION['id_organismo'].") 
										and
											tipo_comprobante.id='$tipo_comprobante'	
											
										
							";
						//	die($sql);
							if (!$conn->Execute($sql)) 	{
							$responce="error"."*".$debe."*".$haber;		
											}
				
							$rs_comprobante =& $conn->Execute($sql);
							//$comprobante=$rs_comprobante->fields("numero_comprobante");	
										$comprobante=$rs_comprobante->fields("numero_comprobante")-1;
										$comprobante2=$comprobante;
										$codigo_tipo_comprobante=$rs_comprobante->fields("codigo_tipo_comprobante");
										$comprobante=$codigo_tipo_comprobante.$comprobante;
										$id_tipo_comprobante=$row->fields("id_tipo_comprobante");
							//die($comprobante);
				
						}else
						if($row->fields("estatus")=='1')
						{
							/*$sql="SELECT numero_comprobante FROM numeracion_comprobante 
											inner join
												organismo
											on
												numeracion_comprobante.id_organismo=organismo.id_organismo
											where		
													(organismo.id_organismo =".$_SESSION['id_organismo'].")
											order by 
													 numeracion_comprobante.id			
									 
							
							";
							if (!$conn->Execute($sql)) 	{
							$responce="error"."*".$debe."*".$haber;		
											}
				
							$rs_comprobante =& $conn->Execute($sql);
							$comprobante=$rs_comprobante->fields("numero_comprobante");*/
							//$comprobante=$comprobante+1.00;	
							$comprobante="0";	
							//die($comprobante);
						
						}
							//////-----------------------------------------
				
							$sql_sumas=" SELECT
										SUM(monto_debito) as debe,
										SUM(monto_credito) as haber
									from
										movimientos_contables
									where movimientos_contables.numero_comprobante='$comprobante'
										and
											estatus!='3'
										and
											ano_comprobante='$ano'		
									
									";
									//die($sql_sumas);
									if (!$conn->Execute($sql_sumas)) 	{
									$responce="error"."*".$debe."*".$haber."*".$resta;		
											}
				
									$row_sumas=& $conn->Execute($sql_sumas);
									if(!$row_sumas->EOF)
									{
											$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
											$resta=number_format($resta,2,',','.');
											$debe=number_format($row_sumas->fields("debe"),2,',','.');
											$haber=number_format($row_sumas->fields("haber"),2,',','.');
									}else
									{
										$debe="0,00";
										$haber="0,00";		
									}
				////////////////////////////////--------------------------------------------------------------------------
					if($compro!='0')
						{	
								$sql_tipo=" SELECT
									id_movimientos_contables,
									movimientos_contables.id_tipo_comprobante,
									tipo_comprobante.codigo_tipo_comprobante as codigo_tipo,
									movimientos_contables.descripcion,
									fecha_comprobante as fecha
								FROM 
									movimientos_contables
								inner join
										organismo
										on
										movimientos_contables.id_organismo=organismo.id_organismo
								inner join 
									cuenta_contable_contabilidad 
								on 
								movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
								inner join
									tipo_comprobante
								on
									tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
													
								where		
											(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
								AND movimientos_contables.numero_comprobante='$comprobante'
								$where
								ORDER BY 
									 movimientos_contables.id_movimientos_contables
									 
								";
							//die($sql_tipo);
									$rs_tipo =& $conn->Execute($sql_tipo);
									if(!$rs_tipo->EOF)
									{
									$tipo=$rs_tipo->fields("id_tipo_comprobante");
									$codigo_tipo=$rs_tipo->fields("codigo_tipo");
									$descripcion=$rs_tipo->fields("descripcion");
									$fecha = substr($rs_tipo->fields("fecha"),0,10);
									$fecha = substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
	
									
									}else
									{
									$tipo="";
									$codigo_tipo="";
									}
							}	
									$resta=number_format($resta,2,',','.');
				
							////////--------------------------------------
							$responce=$comprobante2."*".$debe."*".$haber."*".$tipo."*".$codigo_tipo."*".$resta."*".$descripcion."*".$fecha;
							
					}else
					{
						$resta=number_format($resta,2,',','.');
						$responce="vacio"."*".$debe."*".$haber."*".$tipo."*".$codigo_tipo."*".$resta."*".$descripcion."*".$fecha;
					}
				//	die($responce);
				
					// return the formated data
				//echo $json->encode($responce);
/////////////////////////////////////////////////////////////
}else
$responce="vacio"."*".$debe."*".$haber."*".$tipo."*".$codigo_tipo."*".$resta."*".$descripcion."*".$fecha;
die($responce)
?>