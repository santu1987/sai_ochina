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
if (isset($_GET['busq_nombre']))
$busq_nombre = strtolower($_GET['busq_nombre']);
$where = " WHERE 1 = 1 ";
if ($busq_nombre!='')
$where.= " AND lower(proveedor.nombre) like '%$busq_nombre%' ";
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
				proveedor.id_organismo = organismo.id_organismo ".$where."
			AND
				(proveedor.id_organismo = ".$_SESSION['id_organismo'].")";
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
				proveedor.fecha_vencimiento_rcn,
				proveedor.id_ramo, 
				proveedor.comentario,
				proveedor.solvencia_laboral,
				proveedor.fecha_vencimiento_sol,
				proveedor.objeto_compania,
				covertura_distribucion,
				proveedor.fecha_vencimiento_rif
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
			".$where."
	    	AND
				(documento_proveedor.id_organismo = ".$_SESSION['id_organismo'].")
			ORDER BY 
				proveedor.nombre
				
			LIMIT 
				$limit 
			OFFSET 
				$start ";
				
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$rif = split("-",$row->fields('rif'));
$fecha_rcn = split("-",$row->fields('fecha_vencimiento_rcn'));
$fech_rcn = $fecha_rcn[2]."-".$fecha_rcn[1]."-".$fecha_rcn[0];

$fecha_sol = split("-",$row->fields('fecha_vencimiento_sol'));
$fech_sol = $fecha_sol[2]."-".$fecha_sol[1]."-".$fecha_sol[0];


$fecha_rif = split("-",$row->fields('fecha_vencimiento_rif'));
$fech_rif = $fecha_rif[2]."-".$fecha_rif[1]."-".$fecha_rif[0];

$riftipo = $rif[0];
$rifnumero = $rif[1];
$rifcontrol = $rif[2];
if ($rifcontrol != "")
	$rifnumero = $rifnumero."-".$rifcontrol;
	
	$responce->rows[$i]['id']=$row->fields("id_proveedor");

	$responce->rows[$i]['cell']=array(	
															
															$row->fields("id_proveedor"),
															$row->fields("codigo_proveedor"),
															$row->fields("nombre"),
															$row->fields("direccion"),
															$row->fields("telefono"),
															$row->fields("fax"),
															$row->fields("rif"),
															$row->fields("nit"),
															$row->fields("nombre_persona_contacto"),
															$row->fields("cargo_persona_contacto"),
															$row->fields("email_contacto"),
															$row->fields("paginaweb"),
															$row->fields("rnc"),
															$fech_rcn,
															$row->fields("id_ramo"),
															$row->fields("comentario"),
															$riftipo,
															$rifnumero,
															$fech_sol,
															$row->fields("solvencia_laboral"),
															$row->fields("objeto_compania"),
															$row->fields("covertura_distribucion"),
															$fech_rif
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>