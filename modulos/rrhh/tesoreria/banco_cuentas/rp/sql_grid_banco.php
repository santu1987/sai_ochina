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
$ayo=date('Y');
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
if(isset($_GET['busq_banco']))
{
	$busq_banco=strtolower($_GET['busq_banco']);
	$where="and lower(banco.nombre) like '%$busq_banco%'";
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
			INNER JOIN 
				banco_cuentas
			ON 
				banco.id_banco = banco_cuentas.id_banco	
			
			WHERE
				banco.estatus='1'
			AND
				banco_cuentas.estatus='1'	
			
			AND
					 banco_cuentas.ayo=$ayo
			AND
				banco.id_organismo=$_SESSION[id_organismo]
			
			$where							 
			GROUP BY
				banco.id_banco	
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
			INNER JOIN 
				banco_cuentas
			ON 
				banco.id_banco = banco_cuentas.id_banco	
					
			WHERE
				banco.estatus='1'
			AND
				banco_cuentas.estatus='1'	
			AND
				banco.id_organismo=$_SESSION[id_organismo]	
			
			$where						 
			GROUP BY
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

	
	
	
					$id_banco=$row->fields("id_banco");
//---------------proceso para identificar si el banco tiene una sola cuenta y traerla de forma automatica-------------------
			$cuenta="";
			$sql_cuentas="
										SELECT	
											banco_cuentas.cuenta_banco
										FROM
											banco_cuentas
												
										WHERE
											banco_cuentas.id_banco='$id_banco'
										AND
											banco_cuentas.estatus='1'
											
										AND
											banco_cuentas.id_organismo=$_SESSION[id_organismo]
										
					";	
			$row_cuenta=& $conn->Execute($sql_cuentas);
			$u=0;
			while (!$row_cuenta->EOF) 
			{
				if($u==0)
				{
					$cuenta=$row_cuenta->fields("cuenta_banco");	
				}
				else if($u>0)
				{
					$cuenta="";		
				}
				$u++;		
				$row_cuenta->MoveNext();
			}		
//--------------------------------------------------------------------------------------------------------------------------------	
	if($cuenta!="")
	{
			//---------------busqueda del ultimo n chequera para  calcular la secuencia----------------
			$id_banco=$row->fields("id_banco");
			
			$sql_ultimo_secuencia = "SELECT 
										secuencia
									 FROM 
									 	chequeras 
									WHERE 
										chequeras.id_banco='$id_banco' 
									AND 
										chequeras.cuenta='$cuenta' 
									AND
										chequeras.id_organismo=$_SESSION[id_organismo]		 
									order by 
										secuencia desc limit 1 ";
			
			$row_secuencia= $conn->Execute($sql_ultimo_secuencia);
				if(!$row_secuencia->EOF)	
				{
					$n_chequera=$row_secuencia->fields("secuencia");
					$n_chequera=$n_chequera+1;
				}
				else
					{
						$n_chequera=1;
						}	
				 
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------	 
	}else
	$n_chequera="";
if ($row->fields("estatus")=="1")
		$estatus="Activo";
else
	if ($row->fields("estatus")=="2")
			$estatus="Inactivo";
	$responce->rows[$i]['id']=$row->fields("id_banco");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_banco"),
															$row->fields("id_organismo"),
															$row->fields("nombre"),
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
															$row->fields("comentarios"),
															$cuenta,
															$n_chequera
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>
