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

if(!$sidx) $sidx =1;
$sql_where = "WHERE
					cheques.numero_cheque>0	
				AND cheques.estatus=5
				AND
					cheques.id_organismo = ".$_SESSION["id_organismo"]."";
if(isset($_GET['usu_cheanu']))
{
	$bus_usuario=strtoupper($_GET['usu_cheanu']);
	$sql_where.= " AND  (upper(usuario.nombre) like '%$bus_usuario%')";
}
if(isset($_GET['banco_cheanu']))
{
	$bus_banco=strtoupper($_GET['banco_cheanu']);
	if($bus_banco!='')
	$sql_where.= " AND  (upper(banco.nombre) like '%$bus_banco%')";
}
if(isset($_GET['cuenta_cheanu']))
{
	$bus_cuenta=strtoupper($_GET['cuenta_cheanu']);
	if($bus_cuenta!='')
	$sql_where.= " AND  (upper(cheques.cuenta_banco) like '%$bus_cuenta%')";
}
if(isset($_GET['prov_cheanu']))
{
	$bus_proveedor=strtoupper($_GET['prov_cheanu']);
	if($bus_proveedor!='')
	$sql_where.= " AND  (upper(proveedor.nombre) like '%$bus_proveedor%')";
}

$Sql="SELECT 
			    count(id_cheques) 
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo = organismo.id_organismo
			INNER JOIN 
				banco
			ON 
				cheques.id_banco=banco.id_banco
			INNER JOIN 
				proveedor
			ON 
				cheques.id_proveedor=proveedor.id_proveedor
			INNER JOIN 
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
			$sql_where
			";
	//
$row=& $conn->Execute($Sql);
/*,presupuesto_ley.partida,presupuesto_ley.generica,
				 presupuesto_ley.especifica,presupuesto_ley.sub_especifica*/

if (!$row->EOF)
{
	$count = $row->fields("count");
	
}
$limit = 20;
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
$Sql="  SELECT  distinct
			    cheques.numero_cheque,
				cheques.secuencia,
				cheques.cuenta_banco,
				cheques.id_banco,
				banco.nombre AS banco,
				cheques.monto_cheque,
				proveedor.nombre as proveedor,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				cheques.ordenes	 
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo = organismo.id_organismo
			INNER JOIN 
				banco
			ON 
				cheques.id_banco=banco.id_banco
			INNER JOIN 
				proveedor
			ON 
				cheques.id_proveedor=proveedor.id_proveedor
			INNER JOIN 
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
			INNER JOIN 
				chequeras
			ON 
				cheques.cuenta_banco=chequeras.cuenta	
			 ".$sql_where."
			ORDER BY 
				banco.nombre,cheques.cuenta_banco,cheques.secuencia
			 
			 ";
/*LIMIT 
				$limit 
			OFFSET 
				$start
			/**/
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$nom=$row->fields("nombre");
	$ape=$row->fields("apellido");
	$nombre=$nom."  ". $ape;
		   //
		$primer=strlen($row->fields("numero_cheque"));
		$n_cheque=$row->fields("numero_cheque");
						switch($primer)
									{
										case 1:
										$n_cheque='00000'.$n_cheque;
										break;
										case 2:
										$n_cheque='0000'.$n_cheque;
										break;
										case 3:
										$n_cheque='000'.$n_cheque;
										break;
										case 4:
										$n_cheque='00'.$n_cheque;
										break;
										case 5:
										$n_cheque='0'.$n_cheque;
										break;
										case 6:
										$n_cheque=$n_cheque;
										break;
										
									}
										
	if ($row->fields("estatus")=="1")
		$estatus="Activo";
	else
	if ($row->fields("estatus")=="2")
			$estatus="Inactivo";	
	$responce->rows[$i]['id']=$row->fields("id_banco_cuentas");

	$responce->rows[$i]['cell']=array(		
											$nombre,
											$row->fields("proveedor"),
											$row->fields("id_banco"),
											$row->fields("banco"),
											$row->fields("cuenta_banco"),
        									$row->fields("secuencia"),
											$n_cheque,
											$row->fields("ordenes"),	 
											$row->fields("monto_cheque")									
											);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>