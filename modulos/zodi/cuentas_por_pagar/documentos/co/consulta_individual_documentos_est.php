<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
if($_GET['id_proveedor']!='')
{
	$id_proveedor=$_GET['id_proveedor'];
}
if($_GET['id_factura']!='')
{
	$id_factura=$_GET['id_factura'];
}
$Sql_factura="
				SELECT 
						documentos_cxp.id_documentos,
						documentos_cxp.id_organismo,
						documentos_cxp.ano,
						documentos_cxp.id_proveedor,
						documentos_cxp.tipo_documentocxp,
						documentos_cxp.numero_documento,
						documentos_cxp.numero_control,
						documentos_cxp.fecha_vencimiento,
						documentos_cxp.porcentaje_iva,
      					documentos_cxp.porcentaje_retencion_iva,
						documentos_cxp.monto_bruto,
						documentos_cxp.monto_base_imponible, 
       					documentos_cxp.orden_pago,
						documentos_cxp.numero_compromiso,
						documentos_cxp.descripcion_documento,
						documentos_cxp.comentarios, 
       					documentos_cxp.ultimo_usuario,
						documentos_cxp.fecha_ultima_modificacion,
						documentos_cxp.fecha_documento, 
						documentos_cxp.porcentaje_retencion_islr, 
      					documentos_cxp.estatus,
						tipo_documento_cxp.nombre AS doc,
						proveedor.nombre as proveedor,
						proveedor.rif AS rif,
						proveedor.telefono AS telefono,
						proveedor.codigo_proveedor
						 
						
				FROM
						documentos_cxp
				INNER JOIN
						organismo
				ON
						documentos_cxp.id_organismo=organismo.id_organismo	
				INNER JOIN
						proveedor
				ON
						documentos_cxp.id_proveedor=proveedor.id_proveedor					
				INNER JOIN
						tipo_documento_cxp
				ON
						documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento							
				WHERE
						documentos_cxp.id_documentos='$id_factura'
				AND
						documentos_cxp.id_proveedor='$id_proveedor'								

";
$row=& $conn->Execute($Sql_factura);
$fecha_venc = substr($row->fields("fecha_vencimiento"),0,10);
$fecha_venc = substr($fecha_venc,8,2)."".substr($fecha_venc,4,4)."".substr($fecha_venc,0,4);
	if($row->fields("estatus")=='1')
	{
		$estat="ABIERTA";
	}else
	if($row->fields("estatus")=='2')
	{
		$estat="CERRADA";
	}
$orden="Sin orden";
$estatus="Estatus:No determinado";
$cheque="Sin cheque";
$banco_cheque="Banco:No determinado";
if($row->fields("orden_pago")!='0')
{
	$orden="";
	$orden_pago=$row->fields("orden_pago");
	$sql_orden="SELECT 	
			id_orden_pago,
			orden_pago, 
      		cheque, 
			estatus
				  FROM 
							orden_pago
					INNER JOIN
							organismo
					ON
							orden_pago.id_organismo=organismo.id_organismo	
					where
						id_orden_pago='$orden_pago'
	";
	$row_orden=& $conn->Execute($sql_orden);
//die($sql_orden);
		?>
				<script type='text/javascript'>
						getObj('cheques_estatus_pagado2').style.display='';
						getObj('cheques_estatus_cancel2').style.display='none';
				</script>
		<?
	$orden="N Orden de Pago:  "." ".$row->fields("orden_pago");
	if($row_orden->fields("estatus")=='1')
	$estatus="Estatus: ABIERTA";
	else if($row_orden->fields("estatus")=='2')
	$estatus="Estatus: CERRADA";
	
		if(($row_orden->fields("cheque")!='0')&&($row_orden->fields("cheque")>'0'))
		{
			$cheque="";
			$cheque_banco=$row_orden->fields("cheque");
			
			$sql_cheque="SELECT 	
								cheques.id_banco,
								cheques.cuenta_banco,
								banco.nombre AS banco 
								
						FROM 
								cheques
						INNER JOIN
								organismo
						ON
								cheques.id_organismo=organismo.id_organismo	
						INNER JOIN
								banco
						ON
								cheques.id_banco=banco.id_banco				
						where
							numero_cheque='$cheque_banco'
			";
			$row_cheque=& $conn->Execute($sql_cheque);
			?>
				<script type='text/javascript'>
						getObj('cheques_estatus_pagado').style.display='';
						getObj('cheques_estatus_cancel').style.display='none';
				</script>
							

		<?
		$cheque="N Cheque:  "." ".$row_orden->fields("cheque");
		$banco_cheque="Banco: "." ".$row_cheque->fields("banco");
			}

		
			
}
		?>
		<table class="cuerpo_formulario">
          <tr >
            <th colspan="6" class="titulo_frame"></th>
          </tr>
          <tr>
            <th colspan="6" class="titulo_td"><div align="center"> DOCUMENTO
              <?PHP echo($row->fields('doc')." ".$row->fields('numero_control'));  ?>
            </div>
                <form id="form1" name="form1" method="post" action="">
              </form></th>
          </tr>
          <tr>
            <th width="52" >N Documento</th>
            <td><?php echo $row->fields('numero_documento');?></td>
            <th width="151"  >N control:</th>
            <td ><?php echo $row->fields('numero_control');?></td>
            <th>Fecha vencimiento:</th>
            <td ><?php echo $fecha_venc;?></td>
          </tr>
          <tr>
            <th colspan="6"><div align="center">Estatus: <?php echo $estat;?></div></th>
          </tr>
          <tr>
            <th>Proveedor:</th>
            <td><?php echo $row->fields('proveedor');?></td>
            <th>C&oacute;digo:</th>
            <td><?php echo $row->fields('codigo_proveedor') ;?></td>
            <th>RIF:</th>
            <td><?php echo $row->fields('rif');?></td>
          </tr>
          <tr>
            <th colspan="3"  class="titulo_td"><div align="center">ORDEN</div></th>
            <th colspan="3"  class="titulo_td"><div align="center" >CHEQUE</div></th>
          </tr>
          <tr>
            <th rowspan="2"><img id="cheques_estatus_pagado2" src="imagenes/iconos/check_mark.png"  style="display:none"/><img id="cheques_estatus_cancel2" src="imagenes/iconos/cancel.png"/></th>
            <th colspan="2"><?php echo($orden);?></th>
		    <th rowspan="2"><img id="cheques_estatus_pagado" src="imagenes/iconos/check_mark.png" style="display:none" /><img id="cheques_estatus_cancel" src="imagenes/iconos/cancel.png"/></th>
            <th colspan="2"><? echo($cheque);?></th>
              </tr>
          <tr>
            <th colspan="2"><?php echo($estatus);?></th>
            <th colspan="2"><?php echo($banco_cheque);?></th>
          </tr>
        </table>
		