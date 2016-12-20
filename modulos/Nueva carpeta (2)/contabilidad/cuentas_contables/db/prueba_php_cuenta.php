<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$Sql="
select * from saldo_contable order by id_saldo_contable";

$row=& $conn->Execute($Sql);
while (!$row->EOF)
{
	$id=$row->fields('cuenta_contable');

	$Sql2="
	select * from cuenta_contable_contabilidad where id='$id'
	 order by cuenta_contable";
	$row2=& $conn->Execute($Sql2);
	if($row2->EOF)
	{
		echo($id."-");	
	}
$row->MoveNext();
}

?>