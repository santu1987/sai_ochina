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
if(isset($_GET['busq_nombre']))
{
	$busq_nombre=strtolower($_GET['busq_nombre']);
	$where.="and lower(nombre) like '%$busq_nombre%'";
	
}
$Sql="
			SELECT 
				count(afectado.id_afectado) 
			FROM 
				zodi.afectado
			
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
				afectado.id_afectado,
				afectado.ci,
				afectado.nombre,
				afectado.apellido,
				afectado.sexo,
				afectado.fecha_nacimiento,
				afectado.lugarnacimiento, 
       			afectado.nacionalidad,
				afectado.fec_registrado,
				afectado.edad,
				afectado.frk_nivel_estudio,
				afectado.telefono_afect,
				afectado.id_parentesco,
				afectado.frk_edofederal,
				afectado.frk_municipio,
				afectado.frk_parroquia, 
			    afectado.comunidad,
				afectado.direccion,
				afectado.frk_refugio,
				afectado.frk_condicion_afectado,
				afectado.frk_condicion_fisica, 
       			afectado.fecha_ingreso_refugio,
				afectado.fecha_egreso_refugio,
				afectado.grupo_familiar, 
       			afectado.telefono_local,
				afectado.frk_vehiculo,
				afectado.frk_profesion,
				condicion_fisica.alergias,
				condicion_fisica.tratamiento,
				condicion_fisica.descripcion,
				condicion_fisica.grupo_sanguineo,
				profesion.nombre_empresa,
				profesion.direccion,
				profesion.profesion,
				vehiculo.placas,
				vehiculo.modelo,
				vehiculo.marca,
				vehiculo.color
			FROM 
				zodi.afectado
			INNER JOIN 
				zodi.condicion_fisica
			ON 
				zodi.afectado.frk_condicion_fisica = zodi.condicion_fisica.id_condicion_fisica
			INNER JOIN 
				zodi.profesion
			ON 
				zodi.afectado.frk_profesion = zodi.profesion.id_profesion
			INNER JOIN 
				zodi.vehiculo
			ON 
				zodi.afectado.frk_vehiculo = zodi.vehiculo.id_vehiculo
			ORDER BY
				
					$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
				
";
$row_persona=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row_persona->EOF) 
{
	
	
	
	$responce->rows[$i]['id']=$row_persona->fields("id_afectado");

	$responce->rows[$i]['cell']=array(	
											$row_persona->fields("afectado.id_afectado"),
											$row_persona->fields("afectado.nombre"),
											$row_persona->fields("afectado.apellido"),
											$row_persona->fields("afectado.ci"),
											$row_persona->fields("afectado.fecha_nacimiento"),
											$row_persona->fields("condicion_fisica.grupo_sanguineo"),
											$row_persona->fields("condicion_fisica.alergias"),
											$row_persona->fields("condicion_fisica.tratamiento"),
											$row_persona->fields("afectado.direccion"),
											$row_persona->fields("afectado.telefono_afect"),
											$row_persona->fields("afectado.telefono_local"),
											$row_persona->fields("afectado.comunidad"),
											$row_persona->fields("afectado.telefono_local"),
											$row_persona->fields("vehiculo.placas"),
											$row_persona->fields("vehiculo.modelo"),
											$row_persona->fields("vehiculo.marca"),
											$row_persona->fields("vehiculo.color"),
											$row_persona->fields("profesion.nombre_empresa"),
											$row_persona->fields("profesion.direccion"),
											$row_persona->fields("profesion.profesion")
											
														);
	$i++;
	$row_persona->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>