<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
//include_once('../../../../controladores/numero_to_letras.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$numero_comprobante=$_GET["numero_comprobante"];
//aca consigo los valores que van por e debe y el haber
$sql_sumas=" SELECT
							SUM(monto_debito) as debe,
							SUM(monto_credito) as haber
							
						from
							movimientos_contables
						where movimientos_contables.numero_comprobante='$numero_comprobante'
						and movimientos_contables.estatus!='3'
						"
						;
											
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
					
					$debe=number_format($row_sumas->fields("debe"),2,',','.');
					$haber=number_format($row_sumas->fields("haber"),2,',','.');
					$resta=round($row_sumas->fields("debe"),2)-round($row_sumas->fields("haber"),2);
					$resta=number_format($resta,2,',','.');
			}
//consulto luego todos los campos del registro que necesito : tipo
$sql_tipo="
			Select 
				id_tipo_comprobante,
				tipo_comprobante.nombre as nombre,
				tipo_comprobante.codigo_tipo_comprobante
			FROM	
				movimientos_contables
			INNER JOIN
				tipo_comprobante
			ON
				tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
			where
				movimientos_contables.numero_comprobante='$numero_comprobante'	
";			 
//die($sql_tipo);
$row_tipo=& $conn->Execute($sql_tipo);
if(!$row_tipo->EOF)
{
$vector=$numero_comprobante."*".substr($numero_comprobante,8)."*".$debe."*".$haber."*".$resta."*".$row_tipo->fields("id_tipo_comprobante")."*".$row_tipo->fields("nombre")."*".$row_tipo->fields("codigo_tipo_comprobante");
}
echo($vector);
?>