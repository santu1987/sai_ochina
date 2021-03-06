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
$codigo=$_POST['proveedor_db_codigo'];
$where.= "AND  (codigo_proveedor='$codigo') ";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT  DISTINCT
				count(proveedor.id_proveedor) 
			FROM 
				proveedor 
			INNER JOIN 
				organismo 
			ON 
				proveedor.id_organismo = organismo.id_organismo
			WHERE
				(proveedor.id_organismo = ".$_SESSION['id_organismo'].")
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
			SELECT  DISTINCT
				(proveedor.nombre), 
				proveedor.id_proveedor, 
				proveedor.direccion, 
				proveedor.telefono, 
				proveedor.fax, 
				proveedor.rif as rif,   
				proveedor.nit as nit, 
				proveedor.nombre_persona_contacto, 
				proveedor.cargo_persona_contacto, 
				proveedor.email_contacto, 
				proveedor.paginaweb, 
				proveedor.rnc as rnc,  
				proveedor.id_ramo, 
				proveedor.comentario
			FROM 
				documento_proveedor 
			INNER JOIN 
				proveedor
			ON 
			documento_proveedor.id_proveedor = proveedor.id_proveedor
     		INNER JOIN 
				documento
			ON 
			documento_proveedor.id_documento = documento.id_documento_proveedor
     	   INNER JOIN 
				organismo 
			ON 
			documento_proveedor.id_organismo = organismo.id_organismo
	    	WHERE
				(documento_proveedor.id_organismo = ".$_SESSION['id_organismo'].")
			".$where."
			ORDER BY 
				$sidx 
				$sord
			LIMIT 
				$limit 
			OFFSET 
				$start 
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

if(!$row->EOF )
{
/*guardando documento en vector*/
//$vector[$i]=$row->fields('documento_proveedor.estatus');
$rif = split("-",$row->fields('rif'));
$riftipo = $rif[0];
$rifnumero = $rif[1];
$rifcontrol = $rif[2];
if ($rifcontrol != "")$rifnumero = $rifnumero."-".$rifcontrol;
	$responce->rows[$i]['id']=$row->fields("id_proveedor");
	$responce =$row->fields("id_proveedor")."*". $row->fields("nombre")."*". $row->fields("direccion")."*". $row->fields("telefono")."*". $row->fields("fax")."*".$row->fields("rif")."*".$row->fields("nit")."*". $row->fields("nombre_persona_contacto")."*".$row->fields("email_contacto")."*".$row->fields("paginaweb")."*".$row->fields("rnc")."*".$row->fields("id_ramo")."*".$row->fields("comentario")."*".$row->fields("cargo_persona_contacto")."*".$riftipo."*".$rifnumero."*";
//echo $json->encode($responce);
echo($responce);
}
else
{$responce=" ";
// return the formated data
echo ($responce);}
//die($Sql);
?>