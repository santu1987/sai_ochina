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
$busq_cedula ="";
if(isset($_GET["busq_cedula"]))
$busq_cedula = $_GET['busq_cedula'];
$busq_nombre ="";
if (isset($_GET['busq_nombre']))
$busq_nombre = strtolower($_GET['busq_nombre']);
//echo($busq_nombre);
////////////////////////////////
$where = "WHERE 1 = 1 ";
if($busq_cedula!='')
	$where.= " AND  (persona.cedula LIKE '%$busq_cedula%') ";
if($busq_nombre!='')
	$where.= " AND (lower(persona.nombre) LIKE '%$busq_nombre%')";
////////////////////

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_trabajador) 
			FROM 
				trabajador
			INNER JOIN
				persona
			ON
				persona.id_persona=trabajador.id_persona
			INNER JOIN
				cargos
			ON
				cargos.id_cargos=trabajador.id_cargo
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=trabajador.id_unidad
			INNER JOIN
				paises
			ON
				paises.codigo=trabajador.id_ps_nacimiento
            INNER JOIN
                 municipio
			ON
				municipio.id_mn=trabajador.id_mn_habitacion
			INNER JOIN
				estado
			ON
				estado.id_es=municipio.id_es
			INNER JOIN
				tipo_nomina
			ON
				tipo_nomina.id_tipo_nomina=trabajador.id_tipo_nomina
				".$where."
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
				
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
				trabajador.id_trabajador,
				trabajador.direccion_habitacion as direccion,
				trabajador.email as email,
				trabajador.telefono,
				trabajador.telefono_emergencia as cel,
				trabajador.fecha_nacimiento as fenaci,
				trabajador.lugar_nacimiento as lunaci,
				trabajador.asignaciones,
				persona.id_persona,
				persona.cedula,
				persona.apellido,
				persona.nombre,
				persona.estado_civil,
				persona.sexo,
				persona.observaciones,
				cargos.id_cargos,
				cargos.descripcion,
				unidad_ejecutora.id_unidad_ejecutora,
				unidad_ejecutora.nombre as unidad,
				paises.codigo as cod_pais,
				paises.nombre as pais,
				estado.id_es,
				estado.nom_es as estado,
				municipio.id_mn,
				municipio.nom_mn,
				tipo_nomina.id_tipo_nomina,
				tipo_nomina.nombre as nomina,
				trabajador.fecha_ingreso as fechain,
				trabajador.anos_servicios
			FROM 
				trabajador
			INNER JOIN
				persona
			ON
				persona.id_persona=trabajador.id_persona
			INNER JOIN
				cargos
			ON
				cargos.id_cargos=trabajador.id_cargo
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=trabajador.id_unidad
			INNER JOIN
				paises
			ON
				paises.codigo=trabajador.id_ps_nacimiento
			INNER JOIN
					municipio
			ON
					municipio.id_mn=trabajador.id_mn_habitacion
			INNER JOIN
				estado
			ON
				estado.id_es=municipio.id_es
			INNER JOIN
				tipo_nomina
			ON
				tipo_nomina.id_tipo_nomina=trabajador.id_tipo_nomina
				".$where."
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
			AND
				persona.estatus_eliminar =1
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start;
				";
$row=& $conn->Execute($Sql);
// constructing a JSON
/*$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;*/
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
//$fecha = substr($fecha, 0,10);
//$fecha = substr($fecha,8,2).substr($fecha,4,4).substr($fecha,0,4);
while (!$row->EOF) 
{
	$sql = "
				SELECT
					entrevista.id_entrevista,
					entrevista.cedula,
					entrevista.nombre
				FROM
					persona
				INNER JOIN
					trabajador
				ON
					persona.id_persona = trabajador.id_persona
				INNER JOIN
					entrevista
				ON
					persona.id_entrevista = entrevista.id_entrevista
				WHERE
					trabajador.id_trabajador = ".$row->fields("id_trabajador");
	$bus =& $conn->Execute($sql);			
	$responce->rows[$i]['id']=$row->fields("id_trabajor");
	
	$responce->rows[$i]['cell']=array(	
															$row->fields("id_trabajador"),
															$row->fields("direccion"),
															$row->fields("email"),
															$row->fields("telefono"),
															$row->fields("cel"),
															$row->fields("fenaci"),
															$row->fields("lunaci"),
															$row->fields("asignaciones"),
															$row->fields("id_persona"),
															$row->fields("cedula"),
															$row->fields("apellido"),
															$row->fields("nombre"),
															$row->fields("estado_civil"),
															$row->fields("sexo"),
															$row->fields("observaciones"),
															$row->fields("id_cargos"),
															$row->fields("descripcion"),
															/*$row->fields("id_entrevista"),
															$row->fields("cedu_entre"),
															$row->fields("nom_entre")*/
															$bus->fields("id_entrevista"),
															$bus->fields("cedula"),
															$bus->fields("nombre"),
															$row->fields("id_unidad_ejecutora"),
															$row->fields("unidad"),
															$row->fields("cod_pais"),
															$row->fields("pais"),
															$row->fields("estado"),
															$row->fields("id_mn"),
															$row->fields("nom_mn"),
															$row->fields("id_tipo_nomina"),
															$row->fields("nomina"),
															$row->fields("fechain"),
															$row->fields("anos_servicios")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>