<?php
session_start();
ini_set("memory_limit","20M");

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
////
$sql_det_doc="
SELECT id, id_doc, partida, monto, id_organismo, compromiso
  FROM doc_cxp_detalle
  order by
  id_doc
  ;

";
die($sql_det_doc);
$row2=& $conn->Execute($sql_det_doc);
while (!$row2->EOF) 
{	
	$partida=$row2->fields("partida");
	$compromiso=$row2->fields("compromiso");
	$id_doc=$row2->fields("id_doc");
	$ids=$row2->fields("id");
	$sql_det_doc3="SELECT id
				  FROM doc_cxp_detalle
				  where
				  partida='$partida'
				  and
				  compromiso='$compromiso'
				  and
				  id_doc='$id_doc'
				  and
				  id!=$ids

  
";
//die($sql_det_doc3);
$tes++;
		$row3=& $conn->Execute($sql_det_doc3);
		if(!$row3->EOF)
		{
			echo($row3->fields("id"));
		} 
		/*while (!$row3->EOF) 
		{
			$id2=$row3->fields("id");
			//echo($tes."-".$row2->fields("partida")."-".$row2->fields("compromiso")."-".$row2->fields("id_doc"));	
			  $sql_delete="DELETE FROM doc_cxp_detalle
 			  WHERE  id!=$id2";
			  echo($sql_delete);
			  if (!$conn->Execute($sql_delete))
			  {
			  	die("error");
			  }else
			  echo($id2);
			  
			 $row3->MoveNext();
		}*/
		
 $row2->MoveNext();
		
}
?>