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
				proveedor.codigo_proveedor,
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
			ORDER BY 
				$sidx 
				$sord
			LIMIT 
				$limit 
			OFFSET 
				$start 
";
$row=& $conn->Execute($Sql);
/*  
				documento_proveedor.id_documento
			LIMIT 
				$limit 
			OFFSET 
				$start 
				constructing a JSON*/
/*INNER JOIN 
				organismo 
			ON 
				proveedor.id_organismo = organismo.id_organismo
				
					FROM 
				proveedor 
			INNER JOIN 
				documento_proveedor
			ON 
			proveedor.id_proveedor = documento_proveedor.id_proveedor
				*/
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while(!$row->EOF )
{
/*guardando documento en vector*/
//$vector[$i]=$row->fields('documento_proveedor.estatus');
$rif = split("-",$row->fields('rif'));
$riftipo = $rif[0];
$rifnumero = $rif[1];
$rifcontrol = $rif[2];
if ($rifcontrol != "")
	$rifnumero = $rifnumero."-".$rifcontrol;
	
	$responce->rows[$i]['id']=$row->fields("id_proveedor");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_proveedor"),
															$row->fields("codigo_proveedor"),
															trim($row->fields("nombre")),
															trim($row->fields("direccion")),
															trim($row->fields("telefono")),
															trim($row->fields("fax")),
															$row->fields("rif"),
															trim($row->fields("nit")),
															trim($row->fields("nombre_persona_contacto")),
															trim($row->fields("cargo_persona_contacto")),
															trim($row->fields("email_contacto")),
															trim($row->fields("paginaweb")),
															trim($row->fields("rnc")),
															$row->fields("id_ramo"),
															trim($row->fields("comentario")),
															$riftipo,
															$rifnumero
																														
											);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>