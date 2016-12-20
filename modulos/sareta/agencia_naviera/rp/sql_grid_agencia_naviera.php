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

$sql = "SELECT id_unidad_ejecutora FROM usuario WHERE id_usuario=".$_SESSION['id_usuario'];
					$row= $conn->Execute($sql);
					$delegacion=0;
					if(!$row->EOF){
					$delegacion=$row->fields("id_unidad_ejecutora");
					}
					
if (isset($_GET['nombre']))
$busq_nombre = strtolower($_GET['nombre']);
$add = " WHERE sareta.agencia_naviera.id_agencia_naviera=sareta.agencia_naviera.id_agencia_naviera";
if ($busq_nombre!='')
$add.= " AND lower(nombre) like '$busq_nombre%' ";
$limit = 15;
if(!$sidx) $sidx =1;
$Sql="
		SELECT 
				count(id_agencia_naviera) 
			FROM 
				sareta.agencia_naviera
			".$add;

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
				agencia_naviera.id_estado,						
				id_agencia_naviera,
				id_delegacion,
				nombre,
				rif,
				nit,
				direccion,
				estado.nom_es,
				codigo_area,
				zona,
				apartado,
				telefono1,
				telefono2,
				fax1,
				fax2,
				pag_web,
				correo_electronico,
				contacto,
				cedula,
				cargo,
				codigo_auxiliar,
				comentario,
				ultimo_usuario
			FROM 
				sareta.agencia_naviera,estado 
			".$add." and sareta.agencia_naviera.id_estado=estado.id_es 
			 and 
			 id_delegacion=".$delegacion."
			ORDER BY 
				sareta.agencia_naviera.id_agencia_naviera
			;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
			
	$responce->rows[$i]['id_agencia_naviera']=$row->fields("id_agencia_naviera");
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_agencia_naviera"),
															$row->fields("id_delegacion"),
														    substr($row->fields("nombre"),0,15),
															$row->fields("rif"),
															$row->fields("nit"),
															substr($row->fields("direccion"),0,15),															$row->fields("id_estado"),
															utf8_encode($row->fields("nom_es")),
															$row->fields("codigo_area"),
															utf8_encode($row->fields("zona")),
															utf8_encode($row->fields("apartado")),
															utf8_encode($row->fields("telefono1")),
															utf8_encode($row->fields("telefono2")),
															utf8_encode($row->fields("fax1")),
															utf8_encode($row->fields("fax2")),
															substr($row->fields("pag_web"),0,16),
															utf8_encode($row->fields("correo_electronico")),
															substr($row->fields("contacto"),0,10),
															$row->fields("cedula"),
															utf8_encode($row->fields("cargo")),
															utf8_encode($row->fields("codigo_auxiliar")),
															$row->fields("comentario")

														);
	$i++;
	$row->MoveNext();
}
// return the formated data

echo $json->encode($responce);
?>
