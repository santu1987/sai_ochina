<?php session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$user=$_SESSION['id_usuario'];
if($user!='1')
{
	$where.="and movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'";
}

//************************************************************************
//HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$comprobante=$_GET[busq_cuenta];
if(isset($_GET[busq_cuenta]))
{
	if($comprobante!="")
	$where.="and movimientos_contables.numero_comprobante='$comprobante'";
}
if(isset($_GET[ano]))
{
	if($ano!="")
		$where.=" and Extract(year from fecha_comprobante) ='$ano' ";
}else
	{	
		$ano=date("Y");
		$where.=" and Extract(year from fecha_comprobante) ='$ano' ";
		}

/*if($user!='1')
{
	$where.="and movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'";
}*/
	$where.="and movimientos_contables.estatus!='3'";
$cuenta_utf=0;
$codigo_uejecutora=0;
$cuenta_auxiliar=0;
$codigo_proyecto=0;
$codigo_acentral=0;
$resta=0;
/*** '".$_SESSION['id_usuario']."'";**/
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
		$sql2="
				SELECT
						count(distinct(movimientos_contables.numero_comprobante))
				FROM
						movimientos_contables
				inner join
						organismo
				on
					movimientos_contables.id_organismo=organismo.id_organismo
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
			
				";

		/*$Sql2="
					SELECT 
						count(movimientos_contables.id_movimientos_contables) 
					FROM 
						movimientos_contables 
					inner join
						organismo
					on
						movimientos_contables.id_organismo=organismo.id_organismo
								
					where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")
							$where
					";	 */
				
					$row2=& $conn->Execute($sql2);
					
	if (!$row2->EOF)
	{
		$count = $row2->fields("count");
	}
	// calculation of total pages for the query
	if( $count >0 ) 
	{
		$total_pages = ceil($count/$limit);
	} 
	else
	{
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
/*	$Sql_int="
				SELECT distinct
					movimientos_contables.id_movimientos_contables,
					movimientos_contables.id_organismo,
					movimientos_contables.ano_comprobante,
					movimientos_contables.mes_comprobante,
					movimientos_contables.id_tipo_comprobante,
					movimientos_contables.numero_comprobante,
					movimientos_contables.secuencia,
					movimientos_contables.cuenta_contable,
					movimientos_contables.descripcion,
					movimientos_contables.referencia,
					movimientos_contables.debito_credito,
					movimientos_contables.monto_debito,
					movimientos_contables.monto_credito,
					movimientos_contables.fecha_comprobante,
					movimientos_contables.codigo_auxiliar,
					movimientos_contables.codigo_unidad_ejecutora,
					movimientos_contables.codigo_proyecto,
					movimientos_contables.codigo_utilizacion_fondos,
					movimientos_contables.estatus,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id	as id_cc,
					tipo_comprobante.codigo_tipo_comprobante
								
					
				FROM 
					movimientos_contables
				inner join
						organismo
						on
						movimientos_contables.id_organismo=organismo.id_organismo
				inner join 
					cuenta_contable_contabilidad 
				on 
				movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
				inner join
					tipo_comprobante
				on
					tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
									
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
				ORDER BY 
					 movimientos_contables.id_movimientos_contables
				LIMIT 
					$limit 
				OFFSET 
					$start ";*/
		$Sql_int="
				SELECT distinct
			
					movimientos_contables.numero_comprobante, movimientos_contables.id_tipo_comprobante
					
				FROM 
					movimientos_contables
				inner join
					organismo
						on
					movimientos_contables.id_organismo=organismo.id_organismo
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
							ORDER BY 
					 movimientos_contables.numero_comprobante,
					 movimientos_contables.id_tipo_comprobante
				LIMIT 
					$limit 
				OFFSET 
					$start	 
				";
	$row_int=& $conn->Execute($Sql_int);
	
	// constructing a JSON
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while (!$row_int->EOF) 
	{
			$numero_comprobante=$row_int->fields("numero_comprobante");
			$tipo_comprobante=$row_int->fields("id_tipo_comprobante");
			$sql_sumas=" SELECT
							SUM(monto_debito) as debe,
							SUM(monto_credito) as haber
							
						from
							movimientos_contables
						where movimientos_contables.numero_comprobante='$numero_comprobante'
						and movimientos_contables.estatus!='3'
						"
						;
											
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
					
					$debe=number_format($row_sumas->fields("debe"),2,',','.');
					$haber=number_format($row_sumas->fields("haber"),2,',','.');
					$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
					$resta=number_format($resta,2,',','.');
			}
			$sql3=" SELECT
					movimientos_contables.id_movimientos_contables,
					movimientos_contables.id_organismo,
					movimientos_contables.ano_comprobante,
					movimientos_contables.mes_comprobante,
					movimientos_contables.id_tipo_comprobante,
					movimientos_contables.secuencia,
					movimientos_contables.cuenta_contable,
					movimientos_contables.descripcion,
					movimientos_contables.referencia,
					movimientos_contables.debito_credito,
					movimientos_contables.monto_debito,
					movimientos_contables.monto_credito,
					movimientos_contables.fecha_comprobante,
					movimientos_contables.id_auxiliar,
					movimientos_contables.id_unidad_ejecutora,
					movimientos_contables.id_proyecto,
					movimientos_contables.id_accion_central,
					movimientos_contables.id_utilizacion_fondos,
					movimientos_contables.estatus,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id	as id_cc,
					cuenta_contable_contabilidad.requiere_auxiliar,
					cuenta_contable_contabilidad.requiere_unidad_ejecutora,
					cuenta_contable_contabilidad.requiere_proyecto,
					cuenta_contable_contabilidad.requiere_utilizacion_fondos,
					tipo_comprobante.codigo_tipo_comprobante as codigo_tipo,
					movimientos_contables.comentario
				FROM 
					movimientos_contables
				inner join
						organismo
						on
						movimientos_contables.id_organismo=organismo.id_organismo
				inner join 
					cuenta_contable_contabilidad 
				on 
				movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
				inner join
					tipo_comprobante
				on
					tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
									
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				AND movimientos_contables.numero_comprobante='$numero_comprobante'
				$where
				ORDER BY 
					 movimientos_contables.id_movimientos_contables,
					  movimientos_contables.id_tipo_comprobante
				
				";
//die($sql3);
/*LIMIT 
					$limit 
				OFFSET 
					$start	 */
			$row3=& $conn->Execute($sql3);

			////////////////codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_uejecutora=$row3->fields("id_unidad_ejecutora");
							 $estatus=$row3->fields("estatus");
							 if($id_uejecutora!=0)
							 {
								 $sql_ut="select codigo_unidad_ejecutora  from unidad_ejecutora where id_unidad_ejecutora='$id_uejecutora' AND	unidad_ejecutora.id_organismo = $_SESSION[id_organismo]";									
								 $row_ut=& $conn->Execute($sql_ut);
								 if(!$row_ut->EOF)
								 	 $codigo_uejecutora=$row_ut->fields("codigo_unidad_ejecutora");
							 	else
									$codigo_uejecutora='0';	
							 }else
							 if($id_uejecutora==0)
							 {
							  $codigo_uejecutora='0';
							 }
							 
			//////////////// verificando si la partida coincide con el codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_utf=$row3->fields("id_utilizacion_fondos");
							 if($id_utf!=0)
							 {
								 $sql_ut2="select cuenta_utilizacion_fondos from utilizacion_fondos where id_utilizacion_fondos='$id_utf' AND	utilizacion_fondos.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut2=& $conn->Execute($sql_ut2);
								if(!$row_ut2->EOF)	
								 $cuenta_utf=$row_ut2->fields("cuenta_utilizacion_fondos");
								else
								$cuenta_utf='0';
							 }else
							 $cuenta_utf='0';
			//////////////// verificando si la partida coincide con el codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_auxiliar=$row3->fields("id_auxiliar");
							 if($id_auxiliar!=0)
							 {
								 $sql_ut3="select cuenta_auxiliar from auxiliares where id_auxiliares='$id_auxiliar' AND	id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut3=& $conn->Execute($sql_ut3);
								 if(!$row_ut3->EOF)
								 $cuenta_auxiliar=$row_ut3->fields("cuenta_auxiliar");
							 		else
									$cuenta_auxiliar='0';
							 }else
							 $cuenta_auxiliar='0';				 
		 //////////////////////////////////////////////////////////////////
							 $id_proyecto=$row3->fields("id_proyecto");
							 if($id_proyecto!=0)
							 {
								 $sql_ut4="select codigo_proyecto from proyecto where id_proyecto='$id_proyecto' AND	proyecto.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut4=& $conn->Execute($sql_ut4);
								if(!$row_ut4->EOF)	
								 $cod_proyecto=$row_ut4->fields("codigo_proyecto");
								else
								$cod_proyecto='0';
							 }else{
							 $cod_proyecto='0';}
		 //////////////////////////////////////////////////////////////////
							 $id_acc=$row3->fields("id_accion_central");
					
							 if($id_acc!=0)
							 {
								 $sql_ut5="select codigo_accion_central from accion_centralizada where id_accion_central='$id_acc' AND	accion_centralizada.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut5=& $conn->Execute($sql_ut5);
								 //die($sql_ut5);
								if(!$row_ut5->EOF)	
								{ 
									$cod_acc=$row_ut5->fields("codigo_accion_central");
								 //die($cod_acc);
								 }
								else
								$cod_acc='0';
							 }else
							 {
								 $cod_acc='0';
							  	 $id_acc='0';
							}	
		//////////////////////////////////////////////////////////////////	 
			//$id_proyecto=$row3->fields("id_proyecto");
			$fecha_comp = substr($row3->fields("fecha_comprobante"),0,10);
			$fecha_comp = substr($fecha_comp,8,2)."/".substr($fecha_comp,5,2)."/".substr($fecha_comp,0,4);
			$responce->rows[$i]['id']=$row_int->fields("id_movimientos_contables");
			$responce->rows[$i]['cell']=array(	
																$row3->fields("id_movimientos_contables"),
																$row3->fields("id_organismo"),
																$row3->fields("ano_comprobante"),
																$row3->fields("mes_comprobante"),
																$row3->fields("id_tipo_comprobante"),
																$row_int->fields("numero_comprobante"),
																substr($row_int->fields("numero_comprobante"),2,4),
																$row3->fields("secuencia"),
																$row3->fields("comentarios"),
																$row3->fields("cuenta_contable"),
																$row3->fields("descripcion"),
																substr($row3->fields("descripcion"),0,40),
																$row3->fields("referencia"),
																$debe,
																$haber,
																$fecha_comp,
																$id_auxiliar,
																$id_uejecutora,
																$id_proyecto,
																$row3->fields("id_utilizacion_fondos"),
																$row3->fields("codigo_tipo"),
																$row3->fields("id_cc"),
																$cuenta_utf,
																$codigo_uejecutora,
																$cuenta_auxiliar,
																$cod_proyecto,
																$cod_acc,
																$estatus,
																$id_acc,
																$row3->fields("requiere_auxiliar"),
																$row3->fields("requiere_unidad_ejecutora"),
																$row3->fields("requiere_proyecto"),
																$row3->fields("requiere_utilizacion_fondos"),
																$resta,
																number_format($row3->fields("monto_debito"),2,',','.'),
																number_format($row3->fields("monto_credito"),2,',','.')
														);	
					$i++;
		$row_int->MoveNext();
		
	}


	// return the formated data
																//number_format($row_int->fields("monto_debito"),2,',','.'),
																//number_format($row_int->fields("monto_credito"),2,',','.'),
															//	$row_int->fields("descripcion"),
															//	$row_int->fields("fecha_comprobante")
echo $json->encode($responce);

?>