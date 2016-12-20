<?php
require_once('../../controladores/db.inc.php');
require_once('../../utilidades/adodb/adodb.inc.php');

$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$Sql="SELECT schemaname AS nspname, tablename AS relname, tableowner AS relowner FROM pg_catalog.pg_tables WHERE schemaname NOT IN ('pg_catalog', 'information_schema', 'pg_toast') ORDER BY schemaname, tablename";
$row=& $conn->Execute($Sql);

while (!$row->EOF) 
{
	//foreach ($row->fields as $key => $val) echo "$key : $val<br />";
	$opts.="<a target='tables' href='tables.php?table=".$row->fields("relname")."&schema=".$row->fields("nspname")."'>". $row->fields("relname")."</a><br />";
	$row->MoveNext();
}

?>
<?="<b>Base de Datos: </b>".$db["dbname"]."<br />"?>

<?=$opts?>
<iframe style="float:right; left:300px; top:0px; position:absolute" id="tables" name="tables" width="900px" height="700px"></iframe>