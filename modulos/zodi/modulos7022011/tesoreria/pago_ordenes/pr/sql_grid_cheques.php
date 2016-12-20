<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
//include_once('../../../../controladores/numero_to_letras.php');
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
			SELECT 
				count(cheques.id_cheques) 
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo =cheques.id_organismo
			INNER JOIN 
				banco 
			ON 
				cheques.id_banco =banco.id_banco
			INNER JOIN 
				proveedor
			ON 
				cheques.id_proveedor =proveedor.id_proveedor	
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
				cheques.id_cheques,
				banco.nombre,
				banco.id_banco,
				cheques.cuenta_banco,
				cheques.numero_cheque,
				cheques.tipo_cheque,
				cheques.id_proveedor,
				cheques.nombre_beneficiario as proveedor,
				cheques.cedula_rif_beneficiario,
				cheques.monto_cheque,
				cheques.monto_escrito,				
				cheques.concepto,
				cheques.estatus,
				cheques.comentarios				
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo =cheques.id_organismo
			INNER JOIN 
				banco 
			ON 
				cheques.id_banco =banco.id_banco
				
			ORDER BY
				cheques.numero_cheque
				
";			
 		

// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
	
$row=& $conn->Execute($Sql);

while (!$row->EOF) 
{
		//------------------ verificando datos de proveedor
		$id_proveedor=$row->fields("id_proveedor");
		if($id_proveedor!="")
		{
			$sql_proveedor="select id_proveedor,nombre,codigo_proveedor from proveedor where id_proveedor='$id_proveedor'";
			$row_proveedor=& $conn->Execute($sql_proveedor);
			$id_proveedor=$row_proveedor->fields("id_proveedor");
			$codigo_proveedor=$row_proveedor->fields("codigo_proveedor");
		}else
			{
				$id_proveedor="";
				$codigo_proveedor="";
		
				}
	//------------------
	if ($row->fields("tipo_cheque")=="1")
		$tipo_cheque="Automatico";
	else
	if ($row->fields("estatus")=="2")
			$tipo_cheque="Manuales";
	if ($row->fields("estatus")=="1")
		$estatus="Cheque Cargado";
	else
	if ($row->fields("estatus")=="2")
			$estatus="Cheque Impreso";
	else
	if ($row->fields("estatus")=="3")
			$estatus="En Caja";
	else
	if ($row->fields("estatus")=="4")
			$estatus="Pagado";
	else
	if ($row->fields("estatus")=="5")
			$estatus="Anulado";
//----------------------------------------------------------------------------------	
		$rif = split("-",$row->fields('cedula_rif_beneficiario'));
		$riftipo = $rif[0];
		$rifnumero = $rif[1];
		$rifcontrol = $rif[2];
				
	$responce->rows[$i]['id']=$row->fields("id_cheques");

	$responce->rows[$i]['cell']=array(	
																												
															$row->fields("id_cheques"),
															$row->fields("id_banco"),
															$row->fields("nombre"),
															$row->fields("cuenta_banco"),
															$row->fields("numero_cheque"),
															$tipo_cheque,
															$id_proveedor,
															$codigo_proveedor,
															$row->fields("proveedor"),
															$row->fields("rif"),
															$riftipo,
															$rifnumero,
															$row->fields("monto_cheque"),
															$row->fields("monto_cheque"),
															$row->fields("concepto"),
															$estatus,
															$row->fields("comentarios")
															
																														
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>