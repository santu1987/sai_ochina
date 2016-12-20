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
				AND
					cheques.estatus!='1'
				AND
					cheques.estatus!='5'		
				AND
					cheques.id_organismo = ".$_SESSION["id_organismo"]."";
////////////////////////////////////////////////////////////////////
/*$sql_usuarios="SELECT
	     			unidad_ejecutora.id_unidad_ejecutora,unidad_ejecutora.nombre as unidad
				FROM
					usuario
				INNER JOIN
					unidad_ejecutora
				ON
					usuario.id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora		
				where
					id_usuario=".$_SESSION['id_usuario']."
			";	
						
$row_prueba=& $conn->Execute($sql_usuarios);
$id_unidad_ejecutora=$row_prueba->fields("id_unidad_ejecutora");	
$unidad_nombre=$row_prueba->fields("unidad");	
if($id_unidad_ejecutora==11)	
{*/
	$combo=$_GET['combo'];
	if($combo=='1')	
	{
	$sql_where.= " AND  cheques.estado[1]!='0'
					AND  cheques.estado[3]='0'
					 AND	cheques.estado[2]='0'
	";
	
	}else if($combo=='2')		
	{
		$sql_where.=" AND  cheques.estado[3]='0'
					 AND	cheques.estado[2]!='0'
		";
		
	}
	else if($combo=='3')		
	{
		$sql_where.=" AND  cheques.estado[3]!='0' AND cheques.estado[4]='0'";
	}
	else if($combo=='4')		
	{
		$sql_where.=" AND  cheques.estado[4]!='0' AND cheques.estado[5]='0'";
	}
/*}else
if($id_unidad_ejecutora==4)	
{
	if($combo=='1')	
	{
	$sql_where.= " AND  cheques.estado[2]='0'";
	}else if($combo=='2')		
	{
		$sql_where.= " AND  cheques.estado[3]='0'";
	}
	
		
}else
if($id_unidad_ejecutora==15)	
{
	$sql_where.= " AND  cheques.estado[4]='0'
				
			";
}
else
if($id_unidad_ejecutora==2)	
{
	if($combo=='1')	
	{
	$sql_where.= " AND  cheques.estado[9]='0'";
	}else if($combo=='2')		
	{
		$sql_where.= " AND  cheques.estado[8]='0'";
	}
	
				
			
}
else
$sql_where.="AND 2=1";
*///////////////////  AND  cheques.estado[6]!='2'/////////////////////////////////////////////////////////////////////////////////

					

/*if(isset($_GET['tesoreria_busqueda_ncheque']))
{
	$busq_ncheque=strtoupper($_GET['tesoreria_busqueda_ncheque']);
	if($busq_ncheque!='')
	$sql_where.= " AND  cheques.numero_cheque ='$busq_ncheque'";
}*/

if(isset($_GET['tesoreria_busqueda_usuario_estatus']))
{
	$busq_usuario=strtoupper($_GET['tesoreria_busqueda_usuario_estatus']);
	if($busq_usuario!='')

	$sql_where.= " AND  (numero_cheque ='$busq_usuario')";
}
if(isset($_GET['tesoreria_busqueda_banco_estatus']))
{
	$busq_banco=strtoupper($_GET['tesoreria_busqueda_banco_estatus']);
	if($busq_banco!='')
	$sql_where.= " AND  (upper(banco.nombre) like '%$busq_banco%')";
}
if(isset($_GET['tesoreria_busqueda_cuenta_estatus']))
{
	$busq_cuenta=$_GET['tesoreria_busqueda_cuenta_estatus'];
	if($busq_cuenta!='')
	$sql_where.= " AND  cheques.cuenta_banco like '%$busq_cuenta%'";
}
if(isset($_GET['tesoreria_busqueda_proveedor_estatus']))
{
	$busq_proveedor=strtoupper($_GET['tesoreria_busqueda_proveedor_estatus']);
	
	if(($busq_proveedor!=""))
	{		$sql_prove="select id_proveedor from proveedor where (upper(nombre) like '%$busq_proveedor%')
			 ";
			 
			$row_prove=& $conn->Execute($sql_prove);
			if(!$row_prove->EOF)
				{
					$id_proveedor=$row_prove->fields("id_proveedor");	
					$sql_where.= " AND  (cheques.id_proveedor)='$id_proveedor'";
				}
	}
	else
			 $id_proveedor="";	
}

if(isset($_GET['tesoreria_busqueda_beneficiario_estatus']))
{
	$busq_beneficiario=strtoupper($_GET['tesoreria_busqueda_beneficiario_estatus']);
	if($busq_beneficiario!='')
	$sql_where.= " AND  (upper(cheques.benef_nom) like '%$busq_beneficiario%')";
}
if(isset($_GET['fecha']))
{
	$fechas=$_GET['fecha'];
	if($fechas!='')
	{
		$sql_where.= " AND  cheques.fecha_cheque = '$fechas'";
	}
}
$Sql="
			SELECT 
			  distinct count(id_cheques)
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
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
			
		$sql_where
		 
			";//die($Sql);
//			
			//
/*
$Sql="
			SELECT 
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
			INNER JOIN 
				chequeras
			ON 
				cheques.cuenta_banco=chequeras.cuenta	
		$sql_where

*/
$row=& $conn->Execute($Sql);
/*,presupuesto_ley.partida,presupuesto_ley.generica,
				 presupuesto_ley.especifica,presupuesto_ley.sub_especifica*/

if (!$row->EOF)
{
	$count = $row->fields("count");
	
}
$limit = 15;
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
				cheques.id_cheques,
			    cheques.numero_cheque,
				cheques.secuencia,
				cheques.cuenta_banco,
				cheques.id_banco,
				banco.nombre AS banco,
				cheques.monto_cheque,
				cheques.id_proveedor,
				cheques.nombre_beneficiario,
				cheques.cedula_rif_beneficiario,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				cheques.ordenes,
				cheques.tipo_cheque,
				cheques.estatus,
				cheques.benef_nom,
				cheques.estado,
				cheques.benef_nom,
				cheques.fecha_cheque
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
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
				
			 ".$sql_where."
			ORDER BY 
				banco.nombre,cheques.cuenta_banco,cheques.secuencia
	
			 ";	
//die($Sql);
/*$Sql="  SELECT  distinct
			    cheques.numero_cheque,
				cheques.secuencia,
				cheques.cuenta_banco,
				cheques.id_banco,
				banco.nombre AS banco,
				cheques.monto_cheque,
				proveedor.nombre as proveedor,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				cheques.ordenes,
				cheques.tipo_cheque	 
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
			/**/
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$id_prove=$row->fields("id_proveedor");
	if($row->fields("benef_nom")!="")
	{	
		$proveedor=$row->fields("benef_nom");
	}
	else
	{
		$sql_proveedor="select * from proveedor where id_proveedor='$id_prove'";
		$row_proveedor=& $conn->Execute($sql_proveedor);
		$proveedor=$row_proveedor->fields("nombre");
	}
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
	$responce->rows[$i]['id']=$row->fields("id_cheques");
	$ano_cheque=substr($row->fields("fecha_cheque"),0,4);
	$mes_cheque=substr($row->fields("fecha_cheque"),5,2);
	$dia_cheque=substr($row->fields("fecha_cheque"),8,2);
	$fecha_cheque=$dia_cheque."-".$mes_cheque."-".$ano_cheque;
	$responce->rows[$i]['cell']=array(		
											$row->fields("id_cheques"),
											$row->fields("banco"),
											$row->fields("cuenta_banco"),
											$row->fields("id_banco"),
											$n_cheque,
											$proveedor,
											number_format($row->fields("monto_cheque"),2,',','.'),
											$fecha_cheque,
											$row->fields("ordenes"),
        									$row->fields("secuencia"),
											$row->fields("tipo_cheque"),
											$row->fields("estatus")
											);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>