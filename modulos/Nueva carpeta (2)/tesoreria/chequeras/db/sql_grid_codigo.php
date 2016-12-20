<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
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
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$codigo=$_POST['tesoreria_chequeras_banco_db_codigo'];
$codigo=intval($codigo);
if(!$codigo)
{
	$responce="";
	die $responce;
}
$where=" WHERE banco.id_organismo=$_SESSION[id_organismo]";
$where.= "AND  (codigo_banco='$codigo')";
$Sql="		
			SELECT 
				count(banco.id_banco) 
			FROM 
				banco
			INNER JOIN 
				organismo 
			ON 
				banco.id_organismo = organismo.id_organismo
			".$where."
	";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} 
else {
	$total_pages = 0;
}

// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;

// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;

// the actual query for the grid data
$Sql="
			SELECT 
				banco.id_banco,
				banco.id_organismo,
				banco.codigo_banco,
				banco.nombre,
				banco.sucursal,
				banco.direccion,
				banco.codigoarea,
				banco.telefono,
				banco.fax,
				banco.persona_contacto,
				banco.cargo_contacto,
				banco.email_contacto,
				banco.pagina_banco,
				banco.estatus,
				banco.comentarios		
			FROM 
				banco
			INNER JOIN 
				organismo 
			ON 
				banco.id_organismo = organismo.id_organismo
			".$where."
";//die($Sql);						
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id_banco");
	$responce =$row->fields("id_banco")."*". $row->fields("id_organismo")."*". $row->fields("codigo_banco")."*". $row->fields("nombre")."*". $row->fields("sucursal")."*".$row->fields("direccion")."*".$row->fields("codigoarea")."*".$row->fields("telefono")."*".$row->fields("fax")."*".$row->fields("persona_contacto")."*".$row->fields("cargo_contacto")."*".$row->fields("email_contacto")."*".$row->fields("pagina_banco")."*".$row->fields("comentarios")."*".$row->fields("estatus")."*";

}else
{
	$responce="";
}
	echo ($responce);


//echo $json->encode($responce);
	/*else
	{
		$responce="";
		echo($responce);	
		
	}*/
?>