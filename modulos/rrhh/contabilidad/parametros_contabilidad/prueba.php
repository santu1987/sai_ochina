<?php
require_once('../../../controladores/db.inc.php');
require_once('../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$mes=date('m');
$comprobante_cod=0;
//*	
$sql_comprobante="select * from numeracion_comprobante where ano='2010' and id_organismo='1'";
if (!$conn->Execute($sql_comprobante)) 
	die ('Error en comp: '.$sql_comprobante);
$row_comp=$conn->Execute($sql_comprobante);
$comprobante=$row_comp->fields("numero_comprobante");
$comprobante_mes=substr($comprobante,0,2);
$valor_2=substr($comprobante,1,1);
	if($comprobante_mes=='12')
	{
		$comprobante_cod2='1';
	}
	else
	{
		if($valor_2==0)
		{
			$comprobante_mes=substr($comprobante,0,1);
		}
		else
		{
			$comprobante_mes=substr($comprobante,0,2);
		}
		
		//$comprobante_cod=settype($comprobante_mes,"integer");
		$comprobante_cod2=$comprobante_mes+1;
	
	}
	////////////////////////////////////////////////
	if(strlen($comprobante_mes)=='1')
	{
		$tres="000";
	}else
	{
		$tres="00";
	}
	//////////////////////////////////////////////// 
	$numero_comprobante_nuevo=$comprobante_cod2.$tres;
	//echo($numero_comprobante_nuevo);
//*
?>