<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
/* este programa esta hecho para q me chupoen la poronga silvia bruja-*/
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$poronga=6;
$poronga2=9;

$Sql="
select * from saldo_contable order by id_saldo_contable";

$row=& $conn->Execute($Sql);
while (!$row->EOF)
{
	$id_cc=$row->fields("cuenta_contable");
	$med=strlen($row->fields("debe"));
	$med=$med-2;
	$debe=substr($row->fields("debe"),1,$med);
	$debe_vector=split(",",$debe);
	$med=strlen($row->fields("haber"));
	$med=$med-2;
	$haber=substr($row->fields("haber"),1,$med);
	$haber_vector=split(",",$haber);
	$saldo_inicio=$row->fields("saldo_inicio");
	$saldo_vector=split(",",$saldo_inicio);
	
	$debe_sep=$debe_vector[8];
	$haber_sep=$haber_vector[8];
	$saldo_sep=$saldo_vector[8];
	
	
	
	/*$sql_chupa_poronga1="
		update
				saldo_contable
			SET 
					saldo_inicio[".$poronga."]= '$saldo_sep',
					debe[".$poronga."]= '$debe_sep',
					haber[".$poronga."]= '$haber_sep'
			WHERE
						cuenta_contable='$id_cc'
	
	";*/
	$sql_soy_un_webo2="
		update
				saldo_contable
			SET 
					saldo_inicio[".$poronga."]= '$saldo_sep',
					debe[".$poronga."]= '$debe_sep',
					haber[".$poronga."]= '$haber_sep',
					saldo_inicio[9]= '0',
					debe[9]= '0',
					haber[9]= '0'
			WHERE
						cuenta_contable='$id_cc';
	";
	//echo($sql_soy_un_webo2);
if (!$conn->Execute($sql_soy_un_webo2)) 
			{
				$responde="error ".$sql_soy_un_webo2;
				//$responce='Error al Actualizar: '.$conn->ErrorMsg();
				//$responce=$responce."*".$debe."*".$haber;
				die($responde);
			}	else
			echo("registro".$id_cc);

$row->MoveNext();
}

?>