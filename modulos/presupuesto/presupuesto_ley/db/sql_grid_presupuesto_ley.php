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

if(isset($_GET["unidades"]))
	$busq_unidad=strtoupper($_GET["unidades"]);
if(isset($_GET["ano"]))
	$ano=$_GET["ano"];
if(isset($_GET["busq_partida"]))
	$busq_partida =$_GET["busq_partida"];
if(isset($_GET["busq_accion"]))
	$busq_accion=strtoupper($_GET["busq_accion"]);
if(isset($_GET["cod_unidad"]))
	$busq_cod_unidad=strtoupper($_GET["cod_unidad"]);
	

	if($ano!="")
	{
	$where1.=" AND anteproyecto_presupuesto.anio='$ano'";
	
	}

	if($busq_cod_unidad!="")
	{
	$where2.=" AND (unidad_ejecutora.codigo_unidad_ejecutora) =  '$busq_cod_unidad'";
	
	
	}
	   if($busq_partida!='')
	{	
			$partida =substr($busq_partida,0,3);
		if ($partida!=FALSE) $where2.= " AND  anteproyecto_presupuesto.partida like '%$partida%'";
		
		$generica =substr($busq_partida,3,2);
		if ($generica!=FALSE) $where2.= " AND  anteproyecto_presupuesto.generica like '%$generica%'";
		
		$especifica=substr($busq_partida,5,2);
		if ($especifica!=FALSE)$where2.= " AND  anteproyecto_presupuesto.especifica like '%$especifica%'";
		
		$sub_especifica =substr($busq_partida,7,2);
		if ($sub_especifica!=FALSE)	$where2.= " AND  anteproyecto_presupuesto.sub_especifica like '%$sub_especifica%'";
	}	
if($busq_accion!="")
	{
	$where3.= " AND upper(accion_especifica.denominacion) like  '%$busq_accion%'";
	
	}
	$busq_accion_proyecto=	strtoupper($busq_accion_proyecto);
	if($busq_accion_proyecto!="")
	{
	$where3.=" AND ((upper(accion_centralizada.denominacion) like  '%$busq_accion_proyecto%') OR  (upper(proyecto.nombre) like  '%$busq_accion_proyecto%'))";
	}
		
	
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_anteproyecto_presupuesto)
 	 		FROM 
				anteproyecto_presupuesto
			INNER JOIN 
				unidad_ejecutora 
			ON 
				anteproyecto_presupuesto.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
			LEFT JOIN 
				accion_centralizada 
			ON 
				accion_centralizada.id_accion_central = anteproyecto_presupuesto.id_accion_central
			LEFT JOIN 
				proyecto 
			ON 
				proyecto.id_proyecto = anteproyecto_presupuesto.id_proyecto
			LEFT JOIN 
				accion_especifica 
			ON 
				anteproyecto_presupuesto.id_accion_especifica = accion_especifica.id_accion_especifica
			WHERE (anteproyecto_presupuesto.id_organismo = ".$_SESSION['id_organismo'].")
			$where1
			$where2
			$where3
			
";//$where2

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
		anteproyecto_presupuesto.id_anteproyecto_presupuesto, 
		anteproyecto_presupuesto.id_organismo, 
		anteproyecto_presupuesto.id_unidad_ejecutora,
		unidad_ejecutora.id_unidad_ejecutora,
		unidad_ejecutora.codigo_unidad_ejecutora AS codigo_unidad,
		unidad_ejecutora.nombre AS unidad_ejecutora,
		anteproyecto_presupuesto.id_accion_central, 
		accion_centralizada.codigo_accion_central AS codigo_accion,
		accion_centralizada.denominacion AS accion_centralizada, 
		anteproyecto_presupuesto.id_proyecto,
		proyecto.codigo_proyecto AS codigo_proyecto,  
		proyecto.nombre AS proyecto,
		anteproyecto_presupuesto.id_accion_especifica, 
		accion_especifica.codigo_accion_especifica AS codigo_especifica, 
		accion_especifica.denominacion AS accion_especifica, 
		anteproyecto_presupuesto.anio, 
		partida, generica, especifica, sub_especifica, 	
		enero, febrero, marzo, 
		abril, mayo, junio,  
		julio,agosto, septiembre, 
		octubre, noviembre, diciembre, 
		estatus, 
		anteproyecto_presupuesto.comentario
	FROM 
		anteproyecto_presupuesto
	INNER JOIN 
		unidad_ejecutora 
	ON 
		anteproyecto_presupuesto.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
	LEFT JOIN 
		accion_centralizada 
	ON 
		accion_centralizada.id_accion_central = anteproyecto_presupuesto.id_accion_central
	LEFT JOIN 
		proyecto 
	ON 
		proyecto.id_proyecto = anteproyecto_presupuesto.id_proyecto
	LEFT JOIN 
		accion_especifica 
	ON 
		anteproyecto_presupuesto.id_accion_especifica = accion_especifica.id_accion_especifica
   WHERE (anteproyecto_presupuesto.id_organismo = ".$_SESSION['id_organismo'].") 
  ".$where1."
   ".$where2."
	".$where3."
       		ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";//	

$row=& $conn->Execute($Sql);
// constructing a JSON		number_format($total1,2,',','.')
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$sqlsum="
	SELECT 
		sum(enero+ febrero+ marzo+ 
		abril+ mayo+ junio+  
		julio+agosto+ septiembre+ 
		octubre+ noviembre+ diciembre) AS suma
	
	FROM 
		anteproyecto_presupuesto
	WHERE
		id_anteproyecto_presupuesto= ".$row->fields("id_anteproyecto_presupuesto")."
";
$row_sum=& $conn->Execute($sqlsum);

if ($row->fields("proyecto") != ""){
	$titulo="Proyecto";
	$accion_proyecto = $row->fields("proyecto");
	$codigo_proyecto = $row->fields("codigo_proyecto");
}else{
	$codigo_proyecto = "0000";
}
if ($row->fields("accion_centralizada") != ""){
	$titulo="Accion centralizada";
	$accion_proyecto = $row->fields("accion_centralizada");
	$codigo_accion_central = $row->fields("codigo_accion");
}else{
	$codigo_accion_central = "0000";
}
$accion_especifica=$row->fields("accion_especifica");
$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
$monto = $row->fields("enero")+$row->fields("febrero")+$row->fields("marzo")+$row->fields("abril")+$row->fields("mayo")+$row->fields("junio")+$row->fields("julio")+$row->fields("agosto")+$row->fields("septiembre")+$row->fields("octubre")+$row->fields("noviembre")+$row->fields("diciembre");
	$responce->rows[$i]['id']=$row->fields("id_anteproyecto_presupuesto");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_anteproyecto_presupuesto"),
															$row->fields("id_organismo"),
															$row->fields("id_accion_central"),
															$row->fields("codigo_unidad"),
															$row->fields("unidad_ejecutora"),
															$titulo,
															substr($accion_proyecto,0,40),
															$accion_proyecto,															
															$row->fields("codigo_especifica"),
															substr($accion_especifica,0,40),
															$accion_especifica,
															$partida,
															$row->fields("id_proyecto"),
															$row->fields("anio"),
															$row->fields("comentario"),
															$row->fields("id_accion_especifica"),
															$row->fields("id_unidad_ejecutora"),
															number_format($row->fields("enero"),2,',','.'),
															number_format($row->fields("febrero"),2,',','.'),
															number_format($row->fields("marzo"),2,',','.'),
															number_format($row->fields("abril"),2,',','.'),
															number_format($row->fields("mayo"),2,',','.'),
															number_format($row->fields("junio"),2,',','.'),
															number_format($row->fields("julio"),2,',','.'),
															number_format($row->fields("agosto"),2,',','.'),
															number_format($row->fields("septiembre"),2,',','.'),
															number_format($row->fields("octubre"),2,',','.'),
															number_format($row->fields("noviembre"),2,',','.'),
															number_format($row->fields("diciembre"),2,',','.'),
															$codigo_proyecto,
															$codigo_accion_central,
															number_format($row_sum->fields("suma"),2,',','.'),
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
//echo($Sql);
?>