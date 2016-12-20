<?php session_start();
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
if(isset($_GET['busq_banco']))
{
	$busq_banco=strtolower($_GET['busq_banco']);
	$where.="and lower(banco.nombre) like '%$busq_banco%'";
	
}
$Sql="
			SELECT 
				count(banco.id_banco) 
			FROM 
				banco
			INNER JOIN 
				organismo 
			ON 
				banco.id_organismo = organismo.id_organismo
			WHERE
				banco.id_organismo=$_SESSION[id_organismo]
			$where	
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
			WHERE
				banco.id_organismo=$_SESSION[id_organismo]
			$where	
			ORDER BY
				
					$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
				
";
				
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	if ($row->fields("estatus")=="1")
		$estatus="Activo";
else
	if ($row->fields("estatus")=="2")
			$estatus="Inactivo";
$nombre=$row->fields("nombre");
$banco = split( "-", $nombre);
$nombre_banco=$banco[0];
$sucursal=$banco[1];				
$responce->rows[$i]['id']=$row->fields("id_banco");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_banco"),
															$row->fields("id_organismo"),
															$nombre,
															$row->fields("sucursal"),
															$row->fields("direccion"),
															$row->fields("codigoarea"),
															$row->fields("telefono"),
															$row->fields("fax"),
															$row->fields("persona_contacto"),
															$row->fields("cargo_contacto"),
															$row->fields("email_contacto"),
															$row->fields("pagina_banco"),
															$estatus,
															$row->fields("comentarios")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>