<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$user=$_SESSION['id_usuario'];
if($user!='1')
{
	$where.="and movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'";
}
///// consulta1
$sql_estatus="SELECT 
distinct(movimientos_contables.numero_comprobante),estatus,movimientos_contables.id_movimientos_contables,id_tipo_comprobante,fecha_comprobante,codigo_tipo_comprobante
								
				FROM 
					movimientos_contables
				inner join
						organismo
					on
						movimientos_contables.id_organismo=organismo.id_organismo
				
			  INNER JOIN
			  	tipo_comprobante
			 ON
			 tipo_comprobante.id=movimientos_contables.id_tipo_comprobante		
				where		
						(organismo.id_organismo =".$_SESSION['id_organismo'].")	
					and
						movimientos_contables.estatus!='3'	
				$where		 
				ORDER BY 
					 movimientos_contables.id_movimientos_contables desc";
//die($sql_estatus);
$row=& $conn->Execute($sql_estatus);
if (!$row->EOF) 
{
	$fecha_comprobante = substr($row->fields("fecha_comprobante"),0,10);
	$fecha_comprobante = substr($fecha_comprobante,8,2)."/".substr($fecha_comprobante,5,2)."/".substr($fecha_comprobante,0,4);
	$tipo_comprobante=$row->fields("codigo_tipo_comprobante");
	
	$mes=substr($row->fields("fecha_comprobante"),5,2);
	$ano=substr($row->fields("fecha_comprobante"),0,4);
			if($row->fields("estatus")=='0')
					{	
	/*					$sql="SELECT numero_comprobante,codigo_tipo_comprobante
										 FROM tipo_comprobante
										inner join
											organismo
										on
											tipo_comprobante.id_organismo=organismo.id_organismo
										where		
												(organismo.id_organismo =".$_SESSION['id_organismo'].") 
										and
											tipo_comprobante.id='$tipo_comprobante'";
	*/										//die($sql);
	$sql="SELECT  
				  max(movimientos_contables.numero_comprobante) as maximo
			  FROM movimientos_contables
			  INNER JOIN
			  	tipo_comprobante
			 ON
			 tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
							where		
									(movimientos_contables.id_organismo =".$_SESSION['id_organismo'].") 
							and
								ano_comprobante='$ano'
							and
								codigo_tipo_comprobante='$tipo_comprobante'
							and	
								mes_comprobante='$mes'";
							//	die($sql);
						$rs_comprobante =& $conn->Execute($sql);
						if(!$rs_comprobante->EOF)
						{
										$comprobante=$rs_comprobante->fields("maximo");
																				//die($comprobante2);

										$comprobante2=substr($comprobante,10);
										$codigo_tipo_comprobante=$rs_comprobante->fields("codigo_tipo_comprobante");
										//die($codigo_tipo_comprobante);
										$med=strlen($codigo_tipo_comprobante);
										if($med==1)
										{
											$codigo_tipo_comprobante="0".$codigo_tipo_comprobante;
											}
											else
											$codigo_tipo_comprobante=$codigo_tipo_comprobante;
										$comprobante=$comprobante;
										$id_tipo_comprobante=$row->fields("id_tipo_comprobante");
									//	die($comprobante);
										//$comprobante=$comprobante+1.00;	
										//// verificando montos del ebe y haber
							//////////////////////////////////////////////////////////////////////////////////////////			
										$sql_sumas=" SELECT
															SUM(monto_debito) as debe,
															SUM(monto_credito) as haber
														from
															movimientos_contables
														where numero_comprobante='$comprobante'
														and
															id_tipo_comprobante='$id_tipo_comprobante'
														and
															movimientos_contables.estatus!='3'
														and
															 movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'		 	
																						";
																						//die($sql_sumas);
										$row_sumas=& $conn->Execute($sql_sumas);
										
											if(!$row_sumas->EOF)
											{
													$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
													$resta=number_format($resta,2,',','.');
													$debe=number_format($row_sumas->fields("debe"),2,',','.');
													$haber=number_format($row_sumas->fields("haber"),2,',','.');
											}else
											{
													$debe="0,00";
													$haber="0,00";
													$resta="0,00";		
											}
											if($comprobante!='0')
										   {	
												$sql_tipo=" SELECT
													id_movimientos_contables,
													movimientos_contables.id_tipo_comprobante,
													tipo_comprobante.codigo_tipo_comprobante as codigo_tipo,movimientos_contables.descripcion 
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
												AND movimientos_contables.numero_comprobante='$comprobante'
												and
															id_tipo_comprobante='$id_tipo_comprobante'
												and
															movimientos_contables.estatus!='3'	
												and
															 movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'
												ORDER BY 
													 movimientos_contables.id_movimientos_contables";
												//die($sql_tipo);	
													$rs_tipo =& $conn->Execute($sql_tipo);
													if(!$rs_tipo->EOF)
													{
														$tipo=$rs_tipo->fields("id_tipo_comprobante");
														$codigo_tipo=$rs_tipo->fields("codigo_tipo");
														$med=strlen($codigo_tipo);
														if($med==1)
														{
															$codigo_tipo="0".$codigo_tipo;
															}
															else
															$codigo_tipo=$codigo_tipo	;
																		$descripcion_valor=$rs_tipo->fields("descripcion");
													}else
													{
														$tipo="";
														$codigo_tipo="";
														$descripcion_valor="";
														$comprobante2="";
														//die($tipo);
													}
											
											}	
			//////////////////////////////////////////////////////////////////////////////////////////
						}
			//////////////////
					}else
						if($row->fields("estatus")=='1')
						{
									/*
										$sql="SELECT numero_comprobante
										 FROM tipo_comprobante
										inner join
											organismo
										on
											tipo_comprobante.id_organismo=organismo.id_organismo
										where		
												(organismo.id_organismo =".$_SESSION['id_organismo'].") 
										and
											tipo_comprobante.id='$tipo_comprobante'";
								//die($sql);	
									$rs_comprobante =& $conn->Execute($sql);
									if(!$rs_comprobante->EOF)
									{
										$comprobante=$rs_comprobante->fields("numero_comprobante");
										$comprobante="";
										
									}else*/
										$comprobante="";
										//
										$debe="0,00";
										$haber="0,00";
										$resta="0,00";
										$fecha_comprobante=date("d/m/Y");
										//
						}
						$valor_estatus="Abierto";
						$valor_estatus2="0";
				}
				else
				{
				
						/*$sql="SELECT numero_comprobante FROM numeracion_comprobante 
												inner join
													organismo
												on
													numeracion_comprobante.id_organismo=organismo.id_organismo
												where		
														(organismo.id_organismo =".$_SESSION['id_organismo'].") 
								
								";
								
								$rs_comprobante =& $conn->Execute($sql);
								if(!$rs_comprobante->EOF)
								{
									$comprobante=$rs_comprobante->fields("numero_comprobante");
								}else*/
								$comprobante="";
						//
							$debe="0,00";
							$haber="0,00";
							//
						$valor_estatus="Abierto";
						$valor_estatus2="0";
						$fecha_comprobante=date("d/m/Y");
				}
/*//// verificando montos del ebe y haber
$sql_sumas=" SELECT
					SUM(monto_debito) as debe,
					SUM(monto_credito) as haber
				from
					movimientos_contables
				where numero_comprobante='$comprobante'
												";
$row_sumas=& $conn->Execute($sql_sumas);

	if(!$row_sumas->EOF)
	{
			$debe=number_format($row_sumas->fields("debe"),2,',','.');
			$haber=number_format($row_sumas->fields("haber"),2,',','.');
	}else
	{
		$debe="0,00";
		$haber="0,00";		
	}
*//////
/*	$sql="SELECT numero_comprobante FROM numeracion_comprobante ";
	$rs_comprobante =& $conn->Execute($sql);
	$comprobante=$rs_comprobante->fields("numero_comprobante")+1;*/

$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>

<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
</head>
<!--/
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
--><script type="text/javascript" language="JavaScript">   
/* Marcaras de edicion de campos de entrada --> */
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);

function esFechaValida(fecha){
    if (fecha != undefined && fecha != "" ){
        
        var dia  =  parseInt(fecha.substring(0,2),10);
        var mes  =  parseInt(fecha.substring(3,5),10);
        var anio =  parseInt(fecha.substring(6),10);
		if((anio>2100)||(anio<1900))
		{
			return false;
		}
    switch(mes){
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            numDias=31;
            break;
        case 4: case 6: case 9: case 11:
            numDias=30;
            break;
        case 2:
            if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
            break;
        default:
           // alert("Fecha introducida errónea");
            return false;
    }
        if (dia>numDias || dia==0){
         //   alert("Fecha introducida errónea");
            return false;
        }
        return anio;
    }
}
 
function comprobarSiBisisesto(anio){
if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
    return true;
    }
else {
    return false;
    }
}
/*function v_fecha()
{
	//alert("entro");
	var1=esFechaValida(getObj('contabilidad_comp_pr_fecha').value);
	if(var1!=false)
	{
		var2=comprobarSiBisisesto(var1);
	}
	alert(var1);
	alert(var2);
	if((var1==false)||(var2==true))
	{
		//alert("entro");
		getObj('contabilidad_comp_pr_fecha').value="";
	}

}*/
  function esDigito(sChr){
  var sCod = sChr.charCodeAt(0);
  return ((sCod > 47) && (sCod < 58));
  }
 
  function valSep(oTxt){
  var bOk = false;
  var sep1 = oTxt.value.charAt(2);
  var sep2 = oTxt.value.charAt(5);
  bOk = bOk || ((sep1 == "-") && (sep2 == "-"));
  bOk = bOk || ((sep1 == "/") && (sep2 == "/"));
  return bOk;
  }
 
  function finMes(oTxt){
  var nMes = parseInt(oTxt.value.substr(3, 2), 10);
  var nAno = parseInt(oTxt.value.substr(6), 10);
  var nRes = 0;
  switch (nMes){
   case 1: nRes = 31; break;
   case 2: nRes = 28; break;
   case 3: nRes = 31; break;
   case 4: nRes = 30; break;
   case 5: nRes = 31; break;
   case 6: nRes = 30; break;
   case 7: nRes = 31; break;
   case 8: nRes = 31; break;
   case 9: nRes = 30; break;
   case 10: nRes = 31; break;
   case 11: nRes = 30; break;
   case 12: nRes = 31; break;
  }
  return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0);
  }
function valDia(oTxt){
  var bOk = false;
  var nDia = parseInt(oTxt.value.substr(0, 2), 10);
  bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt)));
  return bOk;
  }
 
  function valMes(oTxt){
  var bOk = false;
  var nMes = parseInt(oTxt.value.substr(3, 2), 10);
  bOk = bOk || ((nMes >= 1) && (nMes <= 12));
  return bOk;
  }
 
  function valAno(oTxt){
  var bOk = true;
  var nAno = oTxt.value.substr(6);
  bOk = bOk && ((nAno.length == 2) || (nAno.length == 4));
  if (bOk){
   for (var i = 0; i < nAno.length; i++){
   bOk = bOk && esDigito(nAno.charAt(i));
   }
  }
  return bOk;
  }
 function v_fecha(oTxt){
  fech=new Date(); 
  oTxt=getObj('contabilidad_comp_pr_fecha');
  var bOk = true;
  if (oTxt.value != ""){
   bOk = bOk && (valAno(oTxt));
   bOk = bOk && (valMes(oTxt));
   bOk = bOk && (valDia(oTxt));
   bOk = bOk && (valSep(oTxt));
   if (!bOk){
   alert("Fecha inválida");
   oTxt.value ="<?= date("d/m/Y")?>";
  // getObj('cuentas_por_pagar_db_fecha_v').value = date();
  // oTxt.focus();
   } //else alert("Fecha correcta");
  }
  }
</script>	<script type='text/javascript'>


var dialog;

/*$("#contabilidad_db_comprobante_manual_btn_cerrar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	debe=getObj('contabilidad_comp_pr_total_debe').value;
	haber=getObj('contabilidad_comp_pr_total_haber').value;
	if(debe==haber)
	{
		//limpio
		limpiar_comp();
		
		//mando mensaje
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />DOCUMENTO CERRADO</p></div>",true,true);
	}
	else
	if(debe!=haber)
	{
		
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE CERRAR EL ASIENTO SI SE ENCUENTRA DESCUADRADO</p></div>",true,true);

	}
	
	
});
*/
///////////////////////////////////////////////////////////////////////////////////////////////
function limpiar_comp_completo()
{
setBarraEstado("");
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
					clearForm('form_contabilidad_comprobantes_pr_movimientos');
					getObj('contabilidad_comp_pr_activo').value=0;
					getObj('contabilidad_comp_pr_activo2').value=0;
					getObj('contabilidad_comp_pr_activo3').value=0;
					getObj('contabilidad_comp_pr_activo4').value=0;
					getObj('contabilidad_comp_pr_estatus').value="Abierto";
					getObj('contabilidad_comp_pr_estatus_oc').value=0;
					getObj('contabilidad_comp_pr_estatus').value="Abierto";
					getObj('contabilidad_comp_pr_estatus_oc').value='0';
					getObj('contabilidad_comp_pr_monto').value="0,00";
					//getObj('contabilidad_comp_pr_numero_comprobante').value=recordset[0];
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
				    getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
					getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='none';

					//alert(url);
					getObj('contabilidad_comp_pr_total_debe').value="0,00";
					getObj('contabilidad_comp_pr_total_haber').value="0,00";
					//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
				//	getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
							desbloquear();
					jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0,page:1}).trigger("reloadGrid");
					url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value
}
function limpiar_comp() 
{
	
	//si son dif es decir no deberia permitir cerrarce en este caso
	if ((getObj('contabilidad_comp_pr_total_debe').value)!=(getObj('contabilidad_comp_pr_total_haber').value))
	//los montos del debe son != al del haber
	{//alert("entra");
	//des_block_b1();//desbloqueo campos claves		
								//creando el valor del numero de comprobante
								numero_comprobante=getObj('contabilidad_comp_pr_numero_comprobante2').value;
								//
								jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
												//alert(url);
												getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
												/*getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
												getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
												getObj('contabilidad_comp_pr_dif').value=recordset[3];*/
												//bloqueado 12/02/2012
												/*if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
													{
														if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
														{	
															getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
															getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
														}else
															{
															getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
															}
													}else*/
														getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
												limpiar_algunos();	
												getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
												getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
												getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
												//getObj('movimientos_contables_db_btn_eliminar').style.display='';
												getObj('movimientos_contables_db_btn_eliminar2').style.display='none';

	
	
	//block_b1();//bloqueo campos claves
	}else{ //alert("entro");
		//	des_block_b1();//desbloqueo campos claves		
					
					/*setBarraEstado("");
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
					
					getObj('contabilidad_comp_pr_activo').value=0;
					getObj('contabilidad_comp_pr_activo2').value=0;
					getObj('contabilidad_comp_pr_activo3').value=0;
					getObj('contabilidad_comp_pr_activo4').value=0;
					getObj('contabilidad_comp_pr_estatus').value="Abierto";
					getObj('contabilidad_comp_pr_estatus_oc').value=0;
					getObj('contabilidad_comp_pr_estatus').value="Abierto";
					getObj('contabilidad_comp_pr_estatus_oc').value='0';
					getObj('contabilidad_comp_pr_monto').value="0,00";
					//getObj('contabilidad_comp_pr_numero_comprobante').value=recordset[0];
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
					//alert(url);
					getObj('contabilidad_comp_pr_total_debe').value="0,00";
					getObj('contabilidad_comp_pr_total_haber').value="0,00";
					//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
					if(((getObj('contabilidad_comp_pr_total_debe').value)=="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)=="0,00"))
					    getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
							desbloquear();
					jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0,page:1}).trigger("reloadGrid");
					url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value
*/
/*if((getObj('contabilidad_comp_pr_numero_comprobante').value!="") and (getObj('contabilidad_comp_pr_tipo_id').value!=""))
{*/
/*	jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value+"&tipo_comprobante="+getObj('contabilidad_comp_pr_tipo_id').value,page:1}).trigger("reloadGrid");
						url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value+"&tipo_comprobante="+getObj('contabilidad_comp_pr_tipo_id').value,
					alert(url);*/
	
			$.ajax ({
						url: "modulos/contabilidad/movimientos_contables/pr/sql_limpiar_comprobante.php",
						data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
						type:'POST',
						cache: false,
						success: function(html)
						{
							recordset=html;
							//
						//	alert(html);
							//comprobante=;
							recordset = recordset.split("*");
							if((recordset[0]!="error")&&(recordset[0]!="vacio"))
							{
								setBarraEstado("");
								fec=getObj('contabilidad_comp_pr_fecha').value;
								clearForm('form_contabilidad_comprobantes_pr_movimientos');
								getObj('contabilidad_comp_pr_fecha').value=fec;
								if(recordset[0]!='0')
								getObj('contabilidad_comp_pr_numero_comprobante').value=recordset[0];
								getObj('contabilidad_comp_pr_numero_comprobante2').value=recordset[8];
								getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
								//getObj('movimientos_contables_db_btn_eliminar').style.display='none';
								getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
								getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
								if((getObj('contabilidad_comp_pr_numero_comprobante').value!="0")&&(getObj('contabilidad_comp_pr_numero_comprobante').value!=""))
								{
								//getObj('contabilidad_comp_pr_numero_comprobante').value=comprobante;
								getObj('contabilidad_comp_pr_tipo_id').value=recordset[3];
								getObj('contabilidad_comp_pr_tipo').value=recordset[4];
								getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
								getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
								getObj('contabilidad_comp_pr_dif').value=recordset[5];
								getObj('contabilidad_comp_pr_desc').value=recordset[6];
								comprobante=getObj('contabilidad_comp_pr_numero_comprobante2').value;
								getObj('contabilidad_comp_pr_numero_comprobante').value;
								
								}else
								{
									
									getObj('contabilidad_comp_pr_fecha').value="<?php  echo(date("d/m/Y")); ?>";
									comprobante="0";
								}
								getObj('contabilidad_comp_pr_activo').value=0;
								getObj('contabilidad_comp_pr_activo2').value=0;
								getObj('contabilidad_comp_pr_activo3').value=0;
								getObj('contabilidad_comp_pr_activo4').value=0;
							//	getObj('contabilidad_comp_pr_fecha').value=3212231;
								//	getObj('contabilidad_comp_pr_fecha_boton_d').disabled="";
								getObj('contabilidad_comp_pr_estatus').value="Abierto";
								getObj('contabilidad_comp_pr_estatus_oc').value=0;
								getObj('contabilidad_comp_pr_estatus').value="Abierto";
								getObj('contabilidad_comp_pr_estatus_oc').value='0';
								getObj('contabilidad_comp_pr_monto').value="0,00";
								getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
								getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
								getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='none';
							//	getObj('movimientos_contables_db_btn_eliminar').style.display='none';
								getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
	
				
			jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+comprobante,page:1}).trigger("reloadGrid");
								url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+comprobante,
								//alert(url);
								getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
								getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
								getObj('contabilidad_comp_pr_dif').value=recordset[5];
								getObj('contabilidad_comp_pr_desc').value=recordset[6];
								if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
									{
										if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
										{	
											
											getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
											getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
										//	getObj('movimientos_contables_db_btn_eliminar').style.display='none';

			
										}
										
									}else
									{
											getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
								
									
									}
								if(((getObj('contabilidad_comp_pr_total_debe').value)=="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)=="0,00"))
						
										getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
								desbloquear();
						 //   block_b1();//bloqueo campos claves
							//este bloqueo se hace siempre en el caso que exita un ultimo comprobante abierto...
							//en caso de que no haya comprobante abierto
								/*if((recordset[0]=="")&&(recordset[1]=="0,00")&&(recordset[2]=="0,00"))
								{
									des_block_b1();
								}
*/							}	
							else
								if(recordset[0]=="vacio")
								{
									limpiar_algunos();	
									getObj('contabilidad_comp_pr_cuenta_contable').value="";
									jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0+"&tipo_comprobante="+0,page:1}).trigger("reloadGrid");
									url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0+"&tipo_comprobante="+0,
									//alert(url);
									/////aqui es donde entra cuando el comprobante debe limpiarse como tal 
									
									getObj('contabilidad_comp_pr_tipo_id').value="";
									getObj('contabilidad_comp_pr_tipo').value="";
									//getObj('contabilidad_comp_pr_total_debe').value="0,00";
									//getObj('contabilidad_comp_pr_total_haber').value="0,00";
									
										getObj('contabilidad_comp_pr_total_debe').value=recorset[1];
										getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
											
									getObj('cuenta_nombre').value="";
								}
								else
								{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
								}
						}
					});
	
	
	}//fin de else
	
	
	
}

function limpiar_guardar()
{
$.ajax ({
			url: "modulos/contabilidad/movimientos_contables/pr/sql_limpiar_comprobante.php",
			data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				recordset=html;
				//alert(html);
				recordset = recordset.split("*");
				if((recordset[0]!="error")&&(recordset[0]!="vacio"))
				{
					setBarraEstado("");
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
					clearForm('form_contabilidad_comprobantes_pr_movimientos');
					getObj('contabilidad_comp_pr_activo').value=0;
					getObj('contabilidad_comp_pr_activo2').value=0;
					getObj('contabilidad_comp_pr_activo3').value=0;
					getObj('contabilidad_comp_pr_activo4').value=0;
					getObj('contabilidad_comp_pr_estatus').value="Abierto";
					getObj('contabilidad_comp_pr_estatus_oc').value=0;
					getObj('contabilidad_comp_pr_estatus').value="Abierto";
					getObj('contabilidad_comp_pr_estatus_oc').value='0';
					getObj('contabilidad_comp_pr_monto').value="0,00";
					getObj('contabilidad_comp_pr_numero_comprobante').value=recordset[0];
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
					jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,page:1}).trigger("reloadGrid");
					url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,
					//alert(url);
					uno=recordset[1];
					dos=recordset[2];
					
					getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
				
					getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
						
					if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
						{
							if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
							{	
								
								getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
								getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';

							}
							
						}else
						{
								getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
						}
					if(((getObj('contabilidad_comp_pr_total_debe').value)=="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)=="0,00"))
			
							getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
						
					desbloquear();
				}	
				else
					if(recordset[0]=="vacio")
					{

					jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,page:1}).trigger("reloadGrid");
					url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,
					//alert(url);
					getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
					getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
					getObj('contabilidad_comp_pr_dif').value=recordset[5];
					}
				else
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
				}
			}
		});

}
	//FUNCON PARA CERRAR BOXY.
function cerrar_boxy(self)
{
			Boxy.get(self).hide();
}
///////////////////////////////////////////////////////////////////////////////////////////////
$("#contabilidad_db_comprobante_manual_btn_cerrar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	debe=getObj('contabilidad_comp_pr_total_debe').value;
	haber=getObj('contabilidad_comp_pr_total_haber').value;
	if(debe==haber)
	{	
	  
	  Boxy.ask("<div id='encabezado'><p>SAI OCHINA</p></div><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif'>REALIZANDO PROCESO DE CIERRE:POR FAVOR ESPERE UNOS SEGUNDOS</p></div><div id='encabezado'><p>.</p></div>",[],{title:"SAI-OCHINA",fixed:false,closeable:false});

	  $.ajax ( 
		{
			url: "modulos/contabilidad/movimientos_contables/pr/sql_cerrar_comprobante.php",
			data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
			cerrar_boxy(document.getElementById('mensaje'));
			//cerrar_boxy(document.getElementById('mensaje2'));
				if (html=="cerrado")
				{
					//limpiar_comp();
//					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />DOCUMENTO CERRADO</p></div>",true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/barras.png' />COMPROBANTE CONTABILIZADO </p></div>",true,true);
					getObj('contabilidad_comp_pr_estatus').value='Cerrado';
					getObj('contabilidad_comp_pr_estatus_oc').value='1';
					getObj('contabilidad_comp_pr_total_debe').value="0,00";
					getObj('contabilidad_comp_pr_total_haber').value="0,00";
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
					getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
					getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='';
					//getObj('movimientos_contables_db_btn_eliminar').style.display='none';
					getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
					/*clearForm('form_contabilidad_comprobantes_pr_movimientos');
						comprobante="0";
						getObj('contabilidad_comp_pr_activo').value=0;
						getObj('contabilidad_comp_pr_activo2').value=0;
						getObj('contabilidad_comp_pr_activo3').value=0;
						getObj('contabilidad_comp_pr_activo4').value=0;
						getObj('contabilidad_comp_pr_estatus').value="Abierto";
						getObj('contabilidad_comp_pr_estatus_oc').value=0;
						getObj('contabilidad_comp_pr_estatus').value="Abierto";
						getObj('contabilidad_comp_pr_estatus_oc').value='0';
						getObj('contabilidad_comp_pr_monto').value="0,00";
						getObj('contabilidad_comp_pr_total_haber').value="0,00";
						getObj('contabilidad_comp_pr_total_debe').value="0,00";
						getObj('contabilidad_comp_pr_fecha').value="<?=  date("d/m/Y"); ?>";
						getObj('contabilidad_comp_pr_fecha_boton_d').disabled="";
					    getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
						getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
	                    getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='none';	
		
	jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+comprobante,page:1}).trigger("reloadGrid");
						url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+comprobante;*/
						//alert(url);
				    /*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="NoActualizo")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
					limpiar_comp();
					/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}	
	else
		if(debe!=haber)
		{
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUEDE CERRAR EL ASIENTO SI SE ENCUENTRA DESCUADRADO</p></div>",true,true);
			getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
		}
	
});
///////////////////////////////////////////////////////////////////
$("#contabilidad_db_comprobante_manual_btn_abrir").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	debe=getObj('contabilidad_comp_pr_total_debe').value;
	haber=getObj('contabilidad_comp_pr_total_haber').value;

 Boxy.ask("<div id='encabezado'><p>SAI OCHINA</p></div><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif'>REALIZANDO PROCESO DE APERTURA DE COMPROBANTE:POR FAVOR ESPERE UNOS SEGUNDOS</p></div><div id='pie'><p>.</p></div>",[],{title:"SAI-OCHINA",fixed:false,closeable:false});
	  $.ajax (
		{
			url: "modulos/contabilidad/movimientos_contables/pr/sql_abrir_comprobante.php",
			data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			
				cerrar_boxy(document.getElementById('mensaje'));
				if (html=="Abierto")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />SE APERTURO EL COMPROBANTE</p></div>",true,true);
				//	limpiar_comp();
					getObj('contabilidad_comp_pr_estatus').value='Abierto';
					getObj('contabilidad_comp_pr_estatus_oc').value='0';
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
					getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
					getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='none';
				//	getObj('movimientos_contables_db_btn_eliminar').style.display='';
					getObj('movimientos_contables_db_btn_eliminar2').style.display='';

					//	getObj('contabilidad_comp_pr_fecha').value="<?=  date("d/m/Y"); ?>";
						getObj('contabilidad_comp_pr_fecha_boton_d').disabled="";
				    /*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				else if (html=="NoActualizo")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
					//limpiar_comp();
					/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}else if (html=="tiene_factura")
				{
					//setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REALIZ&Oacute; LA OPERACI&Oacute;N, EL COMPROBANTE CUENTA CON UNA FACTURA</p></div>",true,true);
					//limpiar_comp();
					/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
					getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
					getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_pr_movimientos');*/
				}
				
				else
				{
					setBarraEstado(html);
				}
			}
		});
	
	
});
///////////////////////////////////////////////////////////////////

$("#contabilidad_movimientos_pr_btn_actualizar").click(function() {
//alert("");
/*if(getObj('contabilidad_comp_pr_monto').value!='0,00')
{*/
	
								if($('#form_contabilidad_comprobantes_pr_movimientos').jVal())
								{
								setBarraEstado(mensaje[esperando_respuesta]);
								desbloquear();
									$.ajax (
									{
										url: "modulos/contabilidad/movimientos_contables/pr/sql.actualizar.php",
										data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
										type:'POST',
										cache: false,
										success: function(html)
										{
											recordset=html;
											recordset = recordset.split("*");
											//alert(html);
											if (recordset[0]=="Registrado")
											{
												setBarraEstado(mensaje[actualizacion_exitosa],true,true);
								//creando el valor del numero de comprobante
	getObj('contabilidad_comp_pr_numero_comprobante').value=recordset[5];
	getObj('contabilidad_comp_pr_numero_comprobante2').value=recordset[4];
	numero_comprobante=getObj('contabilidad_comp_pr_numero_comprobante2').value;	
								//
								
												jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
												url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
												//alert(url);
												getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
												getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
												getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
												getObj('contabilidad_comp_pr_dif').value=recordset[3];
												if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
													{
														if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
														{	
															
														//getObj('movimientos_contables_db_btn_eliminar').style.display='none';
														getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
															getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
															getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
							
														}else
															{
															getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
															
															}
													}else
														getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
							
												limpiar_algunos();	
												//valor=getObj('contabilidad_comp_pr_numero_comprobante').value+1;
												//limpiar_comp();
												//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
												////************************************************************//////
												getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
												getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
												getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
												//clearForm('form_contabilidad_pr_movimientos');
											}
											else if (recordset[0]=="NoActualizo")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
												valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
												//limpiar_comp();
												//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
												//******************************************************************************
												/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
												getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
												getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
												clearForm('form_contabilidad_pr_movimientos');*/
											}
											else if (recordset[0]=="numero_existe")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NÚMERO DE COMPROBANTE YA UTILIZADO</p></div>",true,true);
												valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
												/*limpiar_comp();
												getObj('contabilidad_comp_pr_numero_comprobante').value=valor;*/
												/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
												getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
												getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
												clearForm('form_contabilidad_pr_movimientos');*/
											}
											else if (recordset[0]=="documento_cerrado")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />DOCUMENTO CERRADO</p></div>",true,true);
												valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
												//limpiar_comp();
												//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;*/
												/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
												getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
												getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
												clearForm('form_contabilidad_pr_movimientos');*/
											}
											else if (recordset[0]=="modulo cerrado")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />M&Oacute;DULO CERRADO</p></div>",true,true);
												valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
												//limpiar_comp();
												//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;*/
												/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
												getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
												getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
												clearForm('form_contabilidad_pr_movimientos');*/
											}else
											 if (recordset[0]=="no_ayo")
											{
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />FECHA ERRADA </p></div>",true,true);
																		}		
											else
											{
												setBarraEstado(recordset[0]);
												valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
												//limpiar_comp();
												getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
											}
										}
									});
								}
//}
});
$("#contabilidad_movimientos_pr_btn_guardar").click(function() {

	if((getObj('contabilidad_comp_pr_activo').value==1)&&(getObj('contabilidad_comp_contabilidad_id').value==""))
	{	
			alert("seleccione un auxiliar");
	}		
	else
	{
	//des_block_b1();//bloqueando campos claves
			
				if(getObj('contabilidad_comp_pr_monto').value!='')
				{
								if($('#form_contabilidad_comprobantes_pr_movimientos').jVal())
									{ 
									//alert("entro");
									setBarraEstado(mensaje[esperando_respuesta]);
									desbloquear();
										$.ajax (
										{
											url: "modulos/contabilidad/movimientos_contables/pr/sql.guardar.php",
											data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
											type:'POST',
											cache: false,
											success: function(html)
											{
												recordset=html;
												recordset = recordset.split("*");
												//alert(html);
												if (recordset[0]=="Registrado")
												{
													setBarraEstado(mensaje[registro_exitoso],true,true);
													getObj('contabilidad_comp_pr_numero_comprobante').value=recordset[3];
	
	getObj('contabilidad_comp_pr_numero_comprobante2').value=recordset[5];
	numero_comprobante=getObj('contabilidad_comp_pr_numero_comprobante2').value;												
													jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
													url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
													//alert(url);
getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';													
													getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
													getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
													getObj('contabilidad_comp_pr_dif').value=recordset[4];
													if(getObj('contabilidad_comp_pr_dif').value=='0,00')
													getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
													limpiar_algunos();
													/////////////////////////////////////////////////////////	
													//valor=getObj('contabilidad_comp_pr_numero_comprobante').value+1;
													//limpiar_comp();
													//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
													////************************************************************//////
													/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
													getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
													getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
													clearForm('form_contabilidad_pr_movimientos');*/
												}
												else if (recordset[0]=="NoActualizo")
												{
													//setBarraEstado(mensaje[registro_existe],true,true);
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
													valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
													//limpiar_comp();
													//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
													//******************************************************************************
													/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
													getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
													getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
													clearForm('form_contabilidad_pr_movimientos');*/
												}
												else if (recordset[0]=="numero_existe")
												{
													//setBarraEstado(mensaje[registro_existe],true,true);
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />N&Uacute;MERO DE COMPROBANTE YA UTILIZADO</p></div>",true,true);
													valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
													/*limpiar_comp();
													getObj('contabilidad_comp_pr_numero_comprobante').value=valor;*/
													/*getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
													getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
													getObj('contabilidad_auxiliares_db_btn_guardar').style.display='';
													clearForm('form_contabilidad_pr_movimientos');*/
												}else
												 if (recordset[0]=="numero_comprobante")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png'/>N&Uacute;MERO DE COMPROBANTE NO EXISTE, CREE UNO EN EL M&Uacute;DULO N&Uacute;MERO COMRPOBANTE</p></div>",true,true);
													valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
																			}
												else
												 if (recordset[0]=="no_ayo")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />FECHA ERRADA </p></div>",true,true);
													valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
																			}							
												else
												if(recordset[0]=="error_num_comprobante")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ERROR EN NUMERO DE COMPROBANTE</p></div>",true,true);
												}
												else
												{
													setBarraEstado(recordset[0]);
													valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
													//limpiar_comp();
													getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
												}
											}
										});
									}
				}
		
	//block_b1();//bloqueando campos claves
	
		}	//FIN DEL ELSE


});
/////////////// consultas
$("#contabilidad_movimientos_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_comprobante_mov.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta_comprobante").val(); 
					var ano=$("#consulta_ano_comp").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes.php?busq_cuenta="+busq_cuenta+"&ano="+ano,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes.php?busq_cuenta="+busq_cuenta+"&ano="+ano;
					//alert(url);
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#consulta_ano_comp").change(
					function()
					{
						
						dosearch();													
					}											
				);
				$("#consulta_comprobante").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);	
				$("#consulta_comprobante_tipo").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
					var busq_cuenta= $("#consulta_comprobante").val();
					var ano=$("#consulta_ano_comp").val();
					var tipo= $("#consulta_comprobante_tipo").val();
					
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
                 // alert(url);				
				}
			}
		}
	);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes.php',
								datatype: "json",
								colNames:['Id','Organismo','A&ntilde;o','Mes','tipo','Comprobante','Comprobante2','Secuencia','Comentarios','Cuenta Contable','Desc','Descripci&oacute;n','REF','Debito','Credito','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','codigo_tipo_comp','id_cc','utf','ejecutora','auxiliar','proyecto','acentral','estatus','id_acc','rauxiliar','reje','rproy','rutf','resta','debe_reg','haber_reg'],
								colModel:[
										{name:'id',index:'id', width:20,hidden:true},
										{name:'codigo_organismo',index:'codigo_organismo', width:20,hidden:true,hidden:true},
										{name:'ano_comprobante',index:'ano_comprobante', width:20,hidden:true,hidden:true},
										{name:'mes_comprobante',index:'mes_comprobante', width:20,hidden:true,hidden:true},
										{name:'id_tipo_comprobante',index:'id_tipo_comprobante', width:20,hidden:true},
										{name:'numero_comprobante',index:'numero_comprobante', width:20,hidden:true},
										{name:'numero_comprobante2',index:'numero_comprobante2', width:20},
										{name:'secuencia',index:'secuencia', width:20,hidden:true,hidden:true},
										{name:'comentarios',index:'comentarios', width:20,hidden:true,hidden:true},
										{name:'cuenta_contable',index:'cuenta_contable',width:70,hidden:true},
										{name:'descripcion',index:'descripcion',width:70,hidden:true},
										{name:'descripcion2',index:'descripcion2',width:70},
										{name:'ref',index:'ref',width:20,hidden:true},
										{name:'monto_debito',index:'monto_debito',width:50},
										{name:'monto_credito',index:'monto_credito',width:50},
										{name:'fecha_comprobante',index:'fecha_comprobante',width:50,hidden:true},
										{name:'codigo_auxiliar',index:'codigo_auxiliar',width:50,hidden:true},
										{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora',width:50,hidden:true},
										{name:'codigo_proyecto',index:'codigo_proyecto',width:5,hidden:true},
										{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:50,hidden:true},
										{name:'codigo_tipo_comp',index:'codigo_tipo_comp',width:50,hidden:true},
										{name:'id_cc',index:'id_cc',width:50,hidden:true,hidden:true},
										{name:'cuenta_utf',index:'cuenta_utf',width:50,hidden:true},
										{name:'uejecutora',index:'uejecutora',width:50,hidden:true},
										{name:'auxiliar',index:'auxiliar',width:50,hidden:true},
										{name:'proyecto',index:'proyecto',width:50,hidden:true},
										{name:'acentral',index:'acentral',width:50,hidden:true},
										{name:'estatus',index:'estatus',width:50,hidden:true},
										{name:'id_acc',index:'id_acc',width:50,hidden:true},
										{name:'rauxiliar',index:'rauxiliar',width:5,hidden:true},
										{name:'reje',index:'reje',width:50,hidden:true},
										{name:'rproy',index:'rproy',width:50,hidden:true},
										{name:'rutf',index:'rutf',width:50,hidden:true},
										{name:'resta',index:'resta',width:50,hidden:true},
										{name:'debe_reg',index:'debe_reg',width:50,hidden:true},
										{name:'haber_reg',index:'haber_reg',width:50,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								bloquear();
								/*if(ret.rauxiliar=='t')
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(ret.rutf=='t')
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(ret.reje=='t')
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
									if(ret.rproy=='t')
									{
										getObj('contabilidad_comp_pr_activo4').value=1;
									}*/
				numero_comprobante=ret.numero_comprobante;
			 	getObj('contabilidad_comp_id_comprobante').value =ret.id;
				getObj('contabilidad_comp_pr_numero_comprobante').value =ret.numero_comprobante2.substr(2);
				getObj('contabilidad_comp_pr_numero_comprobante2').value =ret.numero_comprobante;
				numero_Comprobante=ret.numero_comprobante;
				//getObj('contabilidad_comp_pr_cuenta_contable').value=ret.cuenta_contable;
			/*	getObj('contabilidad_comp_pr_ref').value=ret.ref;
				getObj('contabilidad_comp_pr_auxiliar').value=ret.auxiliar;
			 	getObj('contabilidad_comp_pr_ubicacion').value=ret.uejecutora;
				getObj('contabilidad_comp_pr_centro_costo').value=ret.proyecto;
				getObj('contabilidad_pr_centro_costo_id_cmp').value=ret.codigo_proyecto;
				getObj('contabilidad_comp_pr_utf').value=ret.cuenta_utf;
				getObj('contabilidad_auxiliares_db_id_cuenta').value=ret.id_cc;*/
				getObj('contabilidad_comp_pr_total_debe').value=ret.monto_debito;
				getObj('contabilidad_comp_pr_total_haber').value=ret.monto_credito;
				getObj('contabilidad_comp_pr_desc').value=ret.descripcion;
				//
				/*getObj('contabilidad_comp_pr_ejec_id').value=ret.codigo_unidad_ejecutora;
				getObj('contabilidad_comp_pr_utf_id').value=ret.codigo_utilizacion_fondos;
				getObj('contabilidad_comp_contabilidad_id').value=ret.codigo_auxiliar;*/
				///alert(ret.codigo_auxiliar);
				/*getObj('contabilidad_comp_pr_acc_id').value=ret.id_acc;
				getObj('contabilidad_comp_pr_acc').value=ret.acentral;*/
				getObj('contabilidad_comp_pr_dif').value=ret.resta;
				getObj('contabilidad_comp_pr_fecha').value=ret.fecha_comprobante;
				getObj('contabilidad_comp_pr_fecha_boton_d').disabled="";
				//
				//alert(ret.estatus);
				if(ret.estatus==1)
				{
					getObj('contabilidad_comp_pr_estatus').value="Cerrado";
					getObj('contabilidad_comp_pr_estatus_oc').value='1';
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='';
					getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
					getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='';
					
				}
				if(ret.estatus==0)
				{
						getObj('contabilidad_comp_pr_estatus').value="Abierto";
						//getObj('movimientos_contables_db_btn_eliminar').style.display='';
						getObj('movimientos_contables_db_btn_eliminar2').style.display='';
							getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='none';
						getObj('contabilidad_comp_pr_estatus_oc').value='0';
						if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
						{
							if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
							{	
								
								getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
								getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';

							}
							
						}
				}
				/*if(ret.debe_reg!="0,00")
				{
					debito_credito=1;
					getObj('contabilidad_comp_pr_monto').value=ret.debe_reg;
				}else
				if(ret.haber_reg!="0,00")
				{
					debito_credito=2;
					getObj('contabilidad_comp_pr_monto').value=ret.haber_reg;
				}*/	
					//getObj('contabilidad_comp_pr_debe_haber').value=debito_credito;

				///// activando condiciones de campos ocultos
			//	alert(ret.codigo_auxiliar);
			/*if(ret.rauxiliar!=0)
			{
				getObj('contabilidad_comp_pr_activo').value=1;
			}
			if(ret.reje!=0)
			{
				getObj('contabilidad_comp_pr_activo2').value=1;
			}
			if(ret.rutf!=0)
			{
				getObj('contabilidad_comp_pr_activo3').value=1;
			}
			if(ret.rproy!=0)
			{
				getObj('contabilidad_comp_pr_activo4').value=1;
			}*/
			//alert(ret.codigo_tipo_comp);
			getObj('contabilidad_comp_pr_tipo').value=ret.codigo_tipo_comp;
			getObj('contabilidad_comp_pr_tipo_id').value=ret.id_tipo_comprobante;
				//////////////////////////////////////////////
			getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
				/*getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='';
				getObj('contabilidad_movimientos_pr_btn_guardar').style.display='none';*/
								jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
				url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
	//alert(url);
	//bloqueo campos q generan la llave del numero de comprobante
	//block_b1();
								dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#contabilidad_comprobante_btn_consultar_cuenta").click(function() {
/*
if(getObj('activo').value=="")
{
*/

	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload2,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#consulta-cuenta-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta;
                 //  alert(url);				
				}
			}
		}
	);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?nd='+nd,
								datatype: "json",
								colNames:['C&oacute;digo','Cuenta', 'Denominacion','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora','requiere_utf'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'requiere_auxiliar',index:'requiere_auxiliar', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_proyecto',index:'requiere_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_unidad_ejecutora',index:'requiere_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_utilizacion_fondos',index:'requiere_utilizacion_fondos', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//alert(ret.requiere_auxiliar);
									if(ret.requiere_auxiliar=='t')
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(ret.requiere_utilizacion_fondos=='t')
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(ret.requiere_proyecto=='t')
									{
										getObj('contabilidad_comp_pr_activo4').value=1;
									}
									if(ret.requiere_unidad_ejecutora=='t')
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
									$('#contabilidad_comp_pr_cuenta_contable').val(ret.cuenta_contable);
									$('#contabilidad_auxiliares_db_id_cuenta').val(ret.id);
									getObj('cuenta_nombre').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
 
//}//fin de if getObj('activo').value==""						
});

$("#contabilidad_comprobante_btn_consultar_auxiliar_cmp").click(function() {
if((getObj('contabilidad_comp_pr_activo').value==1))
{	//&&(getObj('activo').value=="")
			/*	var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
									function(data)
									{								
											dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
											setTimeout(crear_grid,100);
									});*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_auxiliar_mov.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-auxiliar-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-auxiliar-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-auxiliar-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-auxiliar-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
	    			var busq_nom= $("#consulta-auxiliar-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-auxiliar-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom+"&cuenta="+getObj('contabilidad_auxiliares_db_id_cuenta').value,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom+"&cuenta="+getObj('contabilidad_auxiliares_db_id_cuenta').value;
                // alert(url);				
				}
			}
		}
	);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////									
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?cuenta='+getObj('contabilidad_auxiliares_db_id_cuenta').value,							
											datatype: "json",
											colNames:['id','c&oacute;digo','Denominaci&oacute;n'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
												{name:'denominacion',index:'denominacion', width:50,sortable:false,resizable:false},

													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#contabilidad_comp_pr_auxiliar').val(ret.cuenta_contable);
												$('#contabilidad_comp_contabilidad_id').val(ret.id);
												$('#contabilidad_comp_pr_auxiliar_desc').val(ret.denominacion);
												dialog.hideAndUnload();
											},
											loadComplete:function (id){
												setBarraEstado("");
												dialog.center();
												dialog.show();
											},
											loadError:function(xhr,st,err){ 
												setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
											},															
											sortname: 'cuenta_contable',
											viewrecords: true,
											sortorder: "asc"
										});
									}
}
});
$("#contabilidad_comprobante_btn_consultar_ubicacion_cmp").click(function() {
if(getObj('contabilidad_comp_pr_activo3').value==1)
{	
				/*var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
									function(data)
									{								
											dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
											setTimeout(crear_grid,100);
									});*/
									var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_ubicacion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-ubic-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-ubic-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-ubic-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-ubic-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
	    			var busq_nom= $("#consulta-ubic-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-ubic-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
                //alert(url);				
				}
			}
		}
	);
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php',							
											datatype: "json",
											colNames:['id','c&oacute;digo','Unidad'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
												{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#contabilidad_comp_pr_ubicacion').val(ret.codigo);
												$('#contabilidad_comp_pr_ejec_id').val(ret.id);
												$('#contabilidad_comp_pr_ubicacion_desc').val(ret.unidad);
												dialog.hideAndUnload();
											},
											loadComplete:function (id){
												setBarraEstado("");
												dialog.center();
												dialog.show();
											},
											loadError:function(xhr,st,err){ 
												setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
											},															
											sortname: 'cuenta_contable',
											viewrecords: true,
											sortorder: "asc"
										});
									}
}
});
$("#contabilidad_comprobante_btn_consultar_utf").click(function() {
if(getObj('contabilidad_comp_pr_activo2').value==1)
{	
	/*
					var nd=new Date().getTime();
					setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
					$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
										function(data)
										{								
												dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
												setTimeout(crear_grid,100);
										});*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_utf.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de UTF', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-utf-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-utf-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-utf-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-utf-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
	    			var busq_nom= $("#consulta-utf-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-utf-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
             ///  alert(url);				
				}
			}
		}
	);
		
										function crear_grid()
										{
											jQuery("#list_grid_"+nd).jqGrid
											({
												width:800,
												height:300,
												recordtext:"Registro(s)",
												loadtext: "Recuperando Información del Servidor",		
												url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php',							
												datatype: "json",
												colNames:['id','C&oacute;digo','Unidad'],
												colModel:[
													{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
													{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
													{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
														],
												pager: $('#pager_grid_'+nd),
												rowNum:20,
												rowList:[20,50,100],
												imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
												onSelectRow: function(id){
												var ret = jQuery("#list_grid_"+nd).getRowData(id);
													$('#contabilidad_comp_pr_utf').val(ret.codigo);
													$('#contabilidad_comp_pr_utf_id').val(ret.id);
													$('#contabilidad_comp_pr_utf_desc').val(ret.unidad);
													dialog.hideAndUnload();
												},
												loadComplete:function (id){
													setBarraEstado("");
													dialog.center();
													dialog.show();
												},
												loadError:function(xhr,st,err){ 
													setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
												},															
												sortname: 'cuenta_contable',
												viewrecords: true,
												sortorder: "asc"
											});
										}
}
});
$("#contabilidad_comp_btn_consultar_tipo").click(function() {

	
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
					$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_tipo.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cod= $("#consulta_tipo_cod").val();
					var busq_denom= $("#consulta_tipo_dem").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-auxiliar-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_cod= $("#consulta_tipo_cod").val();
					var busq_denom= $("#consulta_tipo_dem").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom;	
				//	alert(url);
				}
				//
				$("#consulta_tipo_cod").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);
				$("#consulta_tipo_dem").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);						
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
	    			var busq_cod= $("#consulta_tipo_cod").val();
					var busq_denom= $("#consulta_tipo_dem").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom;
					//alert(url);				
				}
			}
		}
	);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////									

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo.php?nd='+nd,
								datatype: "json",
								colNames:['id','C&oacute;digo','Denominaci&oacute;n','Comentario','numero'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'comen',index:'comen', width:100,sortable:false,resizable:false,hidden:true},
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_comp_pr_tipo').val(ret.codigo);
									$('#contabilidad_comp_pr_tipo_id').val(ret.id);
									//$('#contabilidad_comp_pr_numero_comprobante').val(ret.numero);
								//	consulta_automatica_comprobante();
								//	limpiar_algunos();		
												

									//$('#cuentas_por_pagar_integracion_tipo_nombre').val(ret.nombre);
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});



/*function limpiar_comp()
{
	setBarraEstado("");
	valores=getObj('contabilidad_comp_pr_numero_comprobante').value;
	getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
	clearForm('form_contabilidad_comprobantes_pr_movimientos');
	getObj('contabilidad_comp_pr_activo').value=0;
	getObj('contabilidad_comp_pr_activo2').value=0;
	getObj('contabilidad_comp_pr_activo3').value=0;
	getObj('contabilidad_comp_pr_monto').value="0,00";
	valores2=parseInt(valores)+1;
	getObj('contabilidad_comp_pr_numero_comprobante').value=valores2;
	jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,page:1}).trigger("reloadGrid");
	url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_comp_pr_numero_comprobante').value,
	alert(url);		
	desbloquear();
}*/
$("#contabilidad_auxiliares_db_btn_cancelar").click(function() {
limpiar_comp();
	
});
//
function validar_debe_haber()
{
	if((getObj('contabilidad_comp_pr_debe_haber').value!='1')&&(getObj('contabilidad_comp_pr_debe_haber').value!='2'))
	{
			getObj('contabilidad_comp_pr_debe_haber').value="";
	}
}
/* ------------------ Sub-Mascara jquery_moneda   ---------------------------*/
documentall = document.all;


function formatamoney(c) {
    var t = this; if(c == undefined) c = 2;		
    var p, d = (t=t.split("."))[1].substr(0, c);
    for(p = (t=t[0]).length; (p-=3) >= 1;) {
	        t = t.substr(0,p) + "." + t.substr(p);
    }
    return t+","+d+Array(c+1-d.length).join(0);
}

String.prototype.formatCurrency=formatamoney

function demaskvalue(valor, currency){

var val2 = '';
var strCheck = '0123456789';
var len = valor.length;
	if (len== 0){
		return 0.00;
	}

	if (currency ==true){	

		
		for(var i = 0; i < len; i++)
			if ((valor.charAt(i) != '0') && (valor.charAt(i) != ',')) break;
		
		for(; i < len; i++){
			if (strCheck.indexOf(valor.charAt(i))!=-1) val2+= valor.charAt(i);
		}

		if(val2.length==0) return "0.00";
		if (val2.length==1)return "0.0" + val2;
		if (val2.length==2)return "0." + val2;
		
		var parte1 = val2.substring(0,val2.length-2);
		var parte2 = val2.substring(val2.length-2);
		var returnvalue = parte1 + "." + parte2;
		return returnvalue;
		
	}
	else{
			val3 ="";
			for(var k=0; k < len; k++){
				if (strCheck.indexOf(valor.charAt(k))!=-1) val3+= valor.charAt(k);
			}			
	return val3;
	}
}

function reais(obj,event){

var whichCode = (window.Event) ? event.which : event.keyCode;

if (whichCode == 8 && !documentall) {	

	if (event.preventDefault){ //standart browsers
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	obj.value= demaskvalue(x,true).formatCurrency();
	return false;
}

FormataReais(obj,'.',',',event);
} // end reais


function backspace(obj,event){


var whichCode = (window.Event) ? event.which : event.keyCode;
if (whichCode == 8 && documentall) {	
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	var y = demaskvalue(x,true).formatCurrency();

	obj.value =""; 
	obj.value += y;
	
	if (event.preventDefault){ 
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	return false;

	}		
}

function FormataReais(fld, milSep, decSep, e) {
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;

if (whichCode == 0 ) return true;
if (whichCode == 9 ) return true; //tecla tab
if (whichCode == 13) return true; //tecla enter
if (whichCode == 16) return true; //shift internet explorer
if (whichCode == 17) return true; //control no internet explorer
if (whichCode == 27 ) return true; //tecla esc
if (whichCode == 34 ) return true; //tecla end
if (whichCode == 35 ) return true;//tecla end
if (whichCode == 36 ) return true; //tecla home


if (e.preventDefault){ 
		e.preventDefault()
	}else{ 
		e.returnValue = false
}

var key = String.fromCharCode(whichCode);  
if (strCheck.indexOf(key) == -1) return false;  

fld.value += key;

var len = fld.value.length;
var bodeaux = demaskvalue(fld.value,true).formatCurrency();
fld.value=bodeaux;

  if (fld.createTextRange) {
    var range = fld.createTextRange();
    range.collapse(false);
    range.select();
  }
  else if (fld.setSelectionRange) {
    fld.focus();
    var length = fld.value.length;
    fld.setSelectionRange(length, length);
  }
  return false;

}
/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//----------------------------------------------------------------------------------------------------
/* ------------------ Sub-Mascara jquery_moneda   ---------------------------*/
documentall = document.all;


function formatamoney(c) {
    var t = this; if(c == undefined) c = 2;		
    var p, d = (t=t.split("."))[1].substr(0, c);
    for(p = (t=t[0]).length; (p-=3) >= 1;) {
	        t = t.substr(0,p) + "." + t.substr(p);
    }
    return t+","+d+Array(c+1-d.length).join(0);
}

String.prototype.formatCurrency=formatamoney

function demaskvalue(valor, currency){

var val2 = '';
var strCheck = '0123456789';
var len = valor.length;
	if (len== 0){
		return 0.00;
	}

	if (currency ==true){	

		
		for(var i = 0; i < len; i++)
			if ((valor.charAt(i) != '0') && (valor.charAt(i) != ',')) break;
		
		for(; i < len; i++){
			if (strCheck.indexOf(valor.charAt(i))!=-1) val2+= valor.charAt(i);
		}

		if(val2.length==0) return "0.00";
		if (val2.length==1)return "0.0" + val2;
		if (val2.length==2)return "0." + val2;
		
		var parte1 = val2.substring(0,val2.length-2);
		var parte2 = val2.substring(val2.length-2);
		var returnvalue = parte1 + "." + parte2;
		return returnvalue;
		
	}
	else{
			val3 ="";
			for(var k=0; k < len; k++){
				if (strCheck.indexOf(valor.charAt(k))!=-1) val3+= valor.charAt(k);
			}			
	return val3;
	}
}

function reais(obj,event){

var whichCode = (window.Event) ? event.which : event.keyCode;

if (whichCode == 8 && !documentall) {	

	if (event.preventDefault){ //standart browsers
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	obj.value= demaskvalue(x,true).formatCurrency();
	return false;
}

FormataReais(obj,'.',',',event);
} // end reais


function backspace(obj,event){


var whichCode = (window.Event) ? event.which : event.keyCode;
if (whichCode == 8 && documentall) {	
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	var y = demaskvalue(x,true).formatCurrency();

	obj.value =""; 
	obj.value += y;
	
	if (event.preventDefault){ 
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	return false;

	}		
}

function FormataReais(fld, milSep, decSep, e) {
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;

if (whichCode == 0 ) return true;
if (whichCode == 9 ) return true; //tecla tab
if (whichCode == 13) return true; //tecla enter
if (whichCode == 16) return true; //shift internet explorer
if (whichCode == 17) return true; //control no internet explorer
if (whichCode == 27 ) return true; //tecla esc
if (whichCode == 34 ) return true; //tecla end
if (whichCode == 35 ) return true;//tecla end
if (whichCode == 36 ) return true; //tecla home


if (e.preventDefault){ 
		e.preventDefault()
	}else{ 
		e.returnValue = false
}

var key = String.fromCharCode(whichCode);  
if (strCheck.indexOf(key) == -1) return false;  

fld.value += key;

var len = fld.value.length;
var bodeaux = demaskvalue(fld.value,true).formatCurrency();
fld.value=bodeaux;

  if (fld.createTextRange) {
    var range = fld.createTextRange();
    range.collapse(false);
    range.select();
  }
  else if (fld.setSelectionRange) {
    fld.focus();
    var length = fld.value.length;
    fld.setSelectionRange(length, length);
  }
  return false;

}
/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
$("#contabilidad_vista_btn_consultar_proyecto_cmp").click(function() {
if(getObj('contabilidad_comp_pr_activo4').value==1)
{
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_presupuesto_ley.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proyectos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-proyecto-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-proyecto-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-proyecto-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-proyecto-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
	    			var busq_nom= $("#consulta-proyecto-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-proyecto-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
                //alert(url);				
				}
			}
		}
	);
///////////////////////////////////////////////////////////////////////////////////						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php',
								datatype: "json",
								colNames:['Id','C&oacute;digo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('contabilidad_comp_pr_acc_id').value ="0";
									getObj('contabilidad_comp_pr_acc').value ="0" ;
									getObj('contabilidad_comp_pr_centro_costo').value = ret.codigo;
									getObj('contabilidad_pr_centro_costo_id_cmp').value = ret.id;
									getObj('contabilidad_comp_pr_centro_costo_desc').value = ret.denominacion;
									getObj('contabilidad_comp_pr_acc').value="0";
									getObj('contabilidad_comp_pr_acc_desc').value="NO APLICA";
									getObj('contabilidad_comp_pr_acc_id').value="";									

								dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});

//////////////////////////////////////////////////

$("#movimientos_contables_db_btn_eliminar").click(function() {
	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/movimientos_contables/pr/sql.eliminar.php",
			data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
			recordset=html;
			recordset = recordset.split("*");
			//alert(html);
			if (recordset[0]=="Eliminado")
			{
				
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					//creando el valor del numero de comprobante
								numero_comprobante=getObj('contabilidad_comp_pr_tipo').value+getObj('contabilidad_comp_pr_numero_comprobante').value;
								//
								
												jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
												url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
												//alert(url);
												getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
												getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
												getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
												getObj('contabilidad_comp_pr_dif').value=recordset[3];
												if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
													{
														if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
														{	
															
													//	getObj('movimientos_contables_db_btn_eliminar').style.display='none';
														getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
															getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
															getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
							
														}else
															{
															getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
															
															}
													}else
														getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
							
												limpiar_algunos();	
												//valor=getObj('contabilidad_comp_pr_numero_comprobante').value+1;
												//limpiar_comp();
												//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
												////************************************************************//////
												getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
												getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
												getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
												//clearForm('form_contabilidad_pr_movimientos');
				}
				else if (html=="ExisteRelacion")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />IMPOSIBLE ELIMINAR</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html,true,true);					
				}			
			}
		});
});
///////////////////////////////////////////
$("#movimientos_contables_db_btn_eliminar2").click(function() {
//alert(getObj('contabilidad_comp_pr_estatus_oc').value);

if(getObj('contabilidad_comp_pr_estatus_oc').value!=0)
{
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />IMPOSIBLE ELIMINAR COMPROBANTE CERRADO</p></div>",true,true);
}
else
if(getObj('contabilidad_comp_pr_estatus_oc').value==0)
{	
	
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ESTA ABSOLUTAMENTE SEGURO QUE DESEA ELIMINAR EL REGISTRO SELECCIONADO?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
{
if(val=='ACEPTAR')
{	
	
	
		setBarraEstado(mensaje[esperando_respuesta]);
					$.ajax (
					{
						url: "modulos/contabilidad/movimientos_contables/pr/sql.eliminar2.php",
						data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
						type:'POST',
						cache: false,
						success: function(html)
						{//alert(html);
						recordset=html;
						recordset = recordset.split("*");
					//	alert(html);
						if (recordset[0]=="Eliminado")
						{
							
							
								/*setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ELIMINACI&Oacute;N EXITOSA</p></div>",true,true);*/
							setBarraEstado(mensaje[eliminacion_exitosa],true,true);
							//creando el valor del numero de comprobante
							numero_comprobante=getObj('contabilidad_comp_pr_numero_comprobante2').value;
							//
							
							jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
							url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
							//alert(url);
							getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
							getObj('contabilidad_comp_pr_total_debe').value=recordset[1];
							getObj('contabilidad_comp_pr_total_haber').value=recordset[2];
							getObj('contabilidad_comp_pr_dif').value=recordset[3];
							if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
								{
									if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
									{	
										
									//getObj('movimientos_contables_db_btn_eliminar').style.display='none';
									getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
										getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
										getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
							
									}else
										{
										getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
										
										}
								}else
									getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
							
							limpiar_algunos();	
							//valor=getObj('contabilidad_comp_pr_numero_comprobante').value+1;
							//limpiar_comp();
							//getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
							////************************************************************//////
							getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
							getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='none';
							getObj('contabilidad_movimientos_pr_btn_guardar').style.display='';
							//clearForm('form_contabilidad_pr_movimientos');
							
							if((getObj('contabilidad_comp_pr_total_debe').value=='0,00')&&(getObj('contabilidad_comp_pr_total_haber').value=="0,00"))//limpiar_comp();
							{
								//alert("entro");
									getObj('contabilidad_comp_pr_tipo').value='';
									getObj('contabilidad_comp_pr_tipo_id').value='';
									getObj('contabilidad_comp_pr_numero_comprobante2').value='';
									getObj('contabilidad_comp_pr_numero_comprobante').value='';
									getObj('numero_comprobante_cont').value='';
									getObj('sumador_comprobante').value='';
									getObj('cuenta_nombre').value='';	
							}
						}
							else if (html=="ExisteRelacion")
							{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />IMPOSIBLE ELIMINAR</p></div>",true,true);
							}
							else
							{
								setBarraEstado(html,true,true);					
							}			
						}
					});
}});}		
});

///////////////////////////////////////////////////////////
$("#contabilidad_vista_btn_cmp_consultar_acc").click(function() {
if(getObj('contabilidad_comp_pr_activo4').value==1)
{	
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_acc.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-acc-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-acc-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-acc-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-acc-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
	    			var busq_nom= $("#consulta-acc-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-acc-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
                //alert(url);				
				}
			}
		}
	);
///////////////////////////////////////////////////////////////////////////////////						
						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php',
								datatype: "json",
								colNames:['Id','C&oacute;digo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('contabilidad_comp_pr_acc_id').value = ret.id;
									getObj('contabilidad_comp_pr_acc').value = ret.codigo;
									getObj('contabilidad_comp_pr_centro_costo').value = "0";									
									getObj('contabilidad_pr_centro_costo_id_cmp').value = "0";
									getObj('contabilidad_comp_pr_acc_desc').value=ret.denominacion;
									getObj('contabilidad_comp_pr_centro_costo').value="0";
									getObj('contabilidad_comp_pr_centro_costo_desc').value="NO APLICA";
									getObj('contabilidad_pr_centro_costo_id_cmp').value="";
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'denominacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
///

function bloquear(){
//getObj('contabilidad_comp_pr_numero_comprobante').disabled="disabled";
//getObj('contabilidad_comp_pr_cuenta_contable').disabled="disabled";
//getObj('contabilidad_comp_pr_ref').disabled="disabled";
//getObj('contabilidad_comp_pr_debe_haber').disabled="disabled";
//getObj('contabilidad_comp_pr_monto').disabled="disabled";
///////////////////////////////////////////////////////////////
//getObj('contabilidad_comp_pr_auxiliar').disabled="disabled";
//getObj('contabilidad_comp_pr_ubicacion').disabled="disabled";
//getObj('contabilidad_comp_pr_centro_costo').disabled="disabled";
//getObj('contabilidad_comp_pr_utf').disabled="disabled";
///////////////////////////////////////////////////////////////
/*getObj('contabilidad_comp_pr_activo').value='0';
getObj('contabilidad_comp_pr_activo2').value='0';
getObj('contabilidad_comp_pr_activo3').value='0';
getObj('contabilidad_comp_pr_activo4').value='0';*/

}
function desbloquear(){
//getObj('contabilidad_comp_pr_numero_comprobante').disabled="";
getObj('contabilidad_comp_pr_cuenta_contable').disabled=""//;
//getObj('contabilidad_comp_pr_ref').disabled="";
getObj('contabilidad_comp_pr_debe_haber').disabled="";
getObj('contabilidad_comp_pr_monto').disabled="";
getObj('contabilidad_comp_pr_auxiliar').disabled="";
getObj('contabilidad_comp_pr_ubicacion').disabled="";
getObj('contabilidad_comp_pr_centro_costo').disabled="";
getObj('contabilidad_comp_pr_utf').disabled="";
}
function limpiar_algunos()
{
	//alert("entro");
	getObj('activo').value='';
	getObj('contabilidad_comp_pr_debe_haber').disabled='';
	getObj('contabilidad_comp_pr_cuenta_contable').disabled='';
	getObj('cuenta_nombre').disabled='';
	getObj('contabilidad_comp_pr_auxiliar').disabled='';
	//
	getObj('contabilidad_comp_pr_ref').value="";
	getObj('cuenta_nombre').value="";
	//getObj('contabilidad_comp_pr_desc').value="";
	getObj('contabilidad_comp_pr_debe_haber').value="";
	getObj('contabilidad_comp_pr_monto').value="0,00";
	/*getObj('contabilidad_comp_pr_total_debe').value="0,00";
	getObj('contabilidad_comp_pr_total_haber').value="0,00";*/
	getObj('contabilidad_comp_pr_comentarios').value="";
	getObj('contabilidad_comp_pr_auxiliar').value="";
	getObj('contabilidad_comp_pr_cuenta_contable').value="";
	getObj('contabilidad_comp_contabilidad_id').value="";
	getObj('contabilidad_comp_pr_ubicacion').value="";
	getObj('contabilidad_comp_pr_ejec_id').value="";
	getObj('contabilidad_comp_pr_centro_costo').value="";
	getObj('contabilidad_comp_pr_utf').value="";
	getObj('contabilidad_comp_pr_utf_id').value="";
	getObj('contabilidad_comp_pr_auxiliar_desc').value="";
	getObj('contabilidad_comp_pr_centro_costo_desc').value="";
	getObj('contabilidad_comp_pr_acc_desc').value="";
	getObj('contabilidad_comp_pr_ubicacion_desc').value="";
	getObj('contabilidad_comp_pr_utf_desc').value="";

	
	
	getObj('contabilidad_comp_filtrar').value="";
	getObj('contabilidad_comp_filtrar').disabled="disabled";
	
	getObj('contabilidad_pr_centro_costo_id_cmp').value="";
	getObj('contabilidad_comp_pr_acc').value="";
	getObj('contabilidad_comp_pr_acc_id').value="";
	getObj('contabilidad_comprobante_btn_consultar_ubicacion_cmp').value="";
	getObj('contabilidad_comp_pr_acc').value="";
	getObj('contabilidad_vista_btn_cmp_consultar_acc').value="";
	//getObj('movimientos_contables_db_btn_eliminar').style.display='none';
	getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
	getObj('contabilidad_comp_pr_activo').value=0;
	getObj('contabilidad_comp_pr_activo2').value=0;
	getObj('contabilidad_comp_pr_activo3').value=0;
	getObj('contabilidad_comp_pr_activo4').value=0;
	
	//getObj('contabilidad_comp_pr_fecha').value="<?=  date("d/m/Y"); ?>";	
}
/// consultas automaticas

///////////////////////////////////////////////-consulta automatica comprobante-//////////////////////////////////////////////////////////////////
function consulta_manual_tipo_comprobante()
{
	if(getObj('contabilidad_comp_pr_tipo').value!="")
	{
	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_tipo_comprobante_cod.php",
            data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
				//alert(html);
					if(html!="vacio")
					{
						//limpiar_algunos();
						$('#contabilidad_comp_pr_tipo').val(recordset[1]);
						$('#contabilidad_comp_pr_tipo_id').val(recordset[0]);
						//getObj('contabilidad_comp_pr_numero_comprobante').value=recordset[4];	
					//	consulta_automatica_comprobante();
					/*jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0+"&tipo_comprobante="+0,page:1}).trigger("reloadGrid");
								url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0+"&tipo_comprobante="+0;*/
					}
					if(html=="vacio")
					{
						getObj('contabilidad_comp_pr_tipo').value="";
						getObj('contabilidad_comp_pr_tipo_id').value="";
						//getObj('contabilidad_comp_pr_numero_comprobante').value="";
						limpiar_comp();
					}
						/*getObj('contabilidad_comp_pr_activo').value="";
						getObj('contabilidad_comp_pr_activo2').value="";
						getObj('contabilidad_comp_pr_activo3').value="";
						getObj('contabilidad_comp_pr_activo4').value="";
						
						getObj('contabilidad_comp_pr_cuenta_contable').value="";
						getObj('contabilidad_auxiliares_db_id_cuenta').value="";
						getObj('contabilidad_comp_pr_ref').value="";
						getObj('contabilidad_comp_pr_desc').value="";
						getObj('contabilidad_comp_pr_estatus').value="abierto";
						getObj('contabilidad_comp_pr_estatus_oc').value=1;
						getObj('contabilidad_comp_pr_debe_haber').value=1;
						getObj('contabilidad_comp_pr_monto').value="0,00";
						getObj('contabilidad_comp_pr_total_debe').value="0,00";
						getObj('contabilidad_comp_pr_total_haber').value="0,00";
						//////////////////////////
						getOBj('contabilidad_comp_pr_auxiliar').value="";
						getObj('contabilidad_comp_contabilidad_id').value="";
						getObj('contabilidad_comp_pr_centro_costo').value="";
						getObj('contabilidad_pr_centro_costo_id_cmp').value="";
						getObj('contabilidad_comp_pr_acc').value="";
						getObj('contabilidad_comp_pr_acc_id').value="";
						getObj('contabilidad_comp_pr_ubicacion').value="";
						getObj('contabilidad_comp_pr_ejec_id').value="";
						getObj('contabilidad_comp_pr_utf').value="";
						getObj('contabilidad_comp_pr_utf_id').value="";
						getObj('contabilidad_comp_pr_dif').value="";*/
			 }
		});	
	}	 	 
}
/////////////////////////////////////////////////////
function consulta_automatica_cuentas_contables()
{
/*
if(getObj('activo').value=="")
{*/


	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuentas_cont.php",
            data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
									if(recordset[3]=='t')
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(recordset[6]=='t')
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(recordset[4]=='t')
									{
										getObj('contabilidad_comp_pr_activo4').value=1;
									}
									if(recordset[5]=='t')
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
				getObj('contabilidad_comp_pr_cuenta_contable').value = recordset[1];
				getObj('contabilidad_auxiliares_db_id_cuenta').value=recordset[0];
				getObj('cuenta_nombre').value=recordset[2];
				}
				else
				{
				getObj('contabilidad_comp_pr_cuenta_contable').value = "";
				getObj('contabilidad_auxiliares_db_id_cuenta').value="";
				getObj('cuenta_nombre').value="";
				//getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value="";
				}
				
			 }
		});	 	 
//}/////fin de if activo
}
///
function auxiliares_consulta_mov()
{
valores=getObj('contabilidad_comp_pr_activo').value;	
//alert(valores);	
if((valores!=0)&&(getObj('contabilidad_auxiliares_db_id_cuenta').value!=''))
	{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_auxi.php",
						data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								//getObj('').value = recordset[0];
								getObj('contabilidad_comp_contabilidad_id').value=recordset[0];
								getObj('contabilidad_comp_pr_auxiliar_desc').value=recordset[4];
								//getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[2];
								/*getObj('contabilidad_auxiliares_db_nombre').value=recordset[4];
								getObj('contabilidad_auxiliares_db_comentario').value=recordset[5];
								getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='';
								getObj('contabilidad_auxiliares_db_desc').value=recordset[6];*/
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_comp_pr_auxiliar').value='';
								getObj('contabilidad_comp_contabilidad_id').value='';
								getObj('contabilidad_comp_pr_auxiliar_desc').value='';

							}
							
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comp_pr_auxiliar').value='';
		  getObj('contabilidad_comp_contabilidad_id').value='';

	}
}
//////////////////////////////////
function consulta_utf_mod()
{
	valores2=getObj('contabilidad_comp_pr_activo2').value;	
//alert(valores);	
if(valores2!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_utf.php",
						data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
						//	alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('contabilidad_comp_pr_utf_id').value=recordset[0];
								getObj('contabilidad_comp_pr_utf_desc').value=recordset[2];
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_comp_pr_utf_id').value='';
								getObj('contabilidad_comp_pr_utf').value='';
								getObj('contabilidad_comp_pr_utf_desc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comp_pr_utf_id').value='';
		  getObj('contabilidad_comp_pr_utf').value='';
	}
}
/////////////////////////////////
function consulta_acc_mov()
{
	valores4=getObj('contabilidad_comp_pr_activo4').value;	
//alert(valores);	
if(valores4!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_acc.php",
						data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('contabilidad_comp_pr_acc_id').value=recordset[0];
								getObj('contabilidad_comp_pr_acc_desc').value=recordset[2];
								getObj('contabilidad_comp_pr_centro_costo').value="0";
								getObj('contabilidad_comp_pr_centro_costo_desc').value="NO APLICA";
								getObj('contabilidad_comp_pr_centro_costo_desc').value="";
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_comp_pr_acc_id').value='';
								getObj('contabilidad_comp_pr_acc').value='';
								getObj('contabilidad_comp_pr_acc_desc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comp_pr_acc_id').value='';
		  getObj('contabilidad_comp_pr_acc').value='';

	}
	
}
////////////////////////////////
function consulta_ubicacion_mov()
{
valores3=getObj('contabilidad_comp_pr_activo3').value;	
//alert(valores);	
if(valores3!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_ubic.php",
						data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('contabilidad_comp_pr_ejec_id').value=recordset[0];
								getObj('contabilidad_comp_pr_ubicacion_desc').value=recordset[2];
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_comp_pr_ubicacion').value='';
								getObj('contabilidad_comp_pr_ejec_id').value='';
								getObj('contabilidad_comp_pr_ubicacion_desc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comp_pr_ubicacion').value='';
		  getObj('contabilidad_comp_pr_ejec_id').value='';

	}

}
///
function consulta_proyecto_mov()
{
valores4=getObj('contabilidad_comp_pr_activo4').value;	
//alert(valores);	
if(valores4!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_proy.php",
						data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('contabilidad_pr_centro_costo_id_cmp').value=recordset[0];
								getObj('contabilidad_comp_pr_centro_costo_desc').value=recordset[2];
								getObj('contabilidad_comp_pr_acc').value="0";
								getObj('contabilidad_comp_pr_acc_desc').value="NO APLICA";
								getObj('contabilidad_comp_pr_acc_id').value="";
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_comp_pr_centro_costo').value='';
								getObj('contabilidad_pr_centro_costo_id_cmp').value='';
								getObj('contabilidad_comp_pr_centro_costo_desc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comp_pr_centro_costo').value='';
		  getObj('contabilidad_pr_centro_costo_id_cmp').value='';

	}
	
}
///
function consulta_automatica_comprobante()
{
 $.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_comprobante_cod.php",
            data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
			//  alert(html);
					if(html!="vacio")
					{
					/*	1$row3->fields("id_movimientos_contables"),
						2$row3->fields("id_organismo"),
						3$row3->fields("ano_comprobante"),
						4$row3->fields("mes_comprobante"),
						5$row3->fields("id_tipo_comprobante"),
						6$row_int->fields("numero_comprobante"),
						7$row3->fields("secuencia"),
						8$row3->fields("comentarios"),
						9$row3->fields("cuenta_contable"),
						10$row3->fields("descripcion"),
						11$row3->fields("referencia"),
						12$debe,
						13$haber,
						14$row3->fields("fecha_comprobante"),
						15$id_auxiliar,
						16$id_uejecutora,
						17$id_proyecto,
						18$row3->fields("id_utilizacion_fondos"),
						19$row3->fields("codigo_tipo"),
						20$row3->fields("id_cc"),
						21$cuenta_utf,
						22$codigo_uejecutora,
						23$cuenta_auxiliar,
						24$cod_proyecto,
						25$cod_acc,
						26$row3->fields("estatus"),
						27$id_acc,
						28$row3->fields("requiere_auxiliar"),
						29$row3->fields("requiere_unidad_ejecutora"),
						30$row3->fields("requiere_proyecto"),
						31$row3->fields("requiere_utilizacion_fondos")*/

					//	alert("entro");
									
									if(recordset[28]=='t')
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(recordset[31]=='t')
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(recordset[29]=='t')
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
									if(recordset[30]=='t')
									{
										getObj('contabilidad_comp_pr_activo4').value=1;
									}
									getObj('contabilidad_comp_id_comprobante').value =recordset[0];
									// getObj('contabilidad_comp_pr_numero_comprobante').value =recordset[6];
									getObj('contabilidad_comp_pr_cuenta_contable').value=recordset[8];
									getObj('contabilidad_comp_pr_ref').value=recordset[10];
									getObj('contabilidad_comp_pr_auxiliar').value=recordset[22];
									getObj('contabilidad_comp_pr_ubicacion').value=recordset[21];
									getObj('contabilidad_comp_pr_centro_costo').value=recordset[23];
									getObj('contabilidad_pr_centro_costo_id_cmp').value=recordset[16];
									getObj('contabilidad_comp_pr_utf').value=recordset[20];
									getObj('contabilidad_auxiliares_db_id_cuenta').value=recordset[19];
									getObj('contabilidad_comp_pr_total_debe').value=recordset[11];
									getObj('contabilidad_comp_pr_total_haber').value=recordset[12];
									getObj('contabilidad_comp_pr_dif').value=recordset[31];
									getObj('contabilidad_comp_pr_desc').value=recordset[9];
									//
									getObj('contabilidad_comp_pr_ejec_id').value=recordset[15];
									getObj('contabilidad_comp_pr_utf_id').value=recordset[17];
									getObj('contabilidad_comp_contabilidad_id').value=recordset[14];
									///alert(ret.codigo_auxiliar);
									getObj('contabilidad_comp_pr_acc_id').value=recordset[26];
									getObj('contabilidad_comp_pr_acc').value=recordset[24];
									
									//
									//alert(ret.estatus);
									if(recordset[25]==1)
									{
										getObj('contabilidad_comp_pr_estatus').value="Cerrado";
										getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='';
										getObj('contabilidad_comp_pr_estatus_oc').value='1';
										getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='';
										getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
									}
									if(recordset[25]==0)
									{
											getObj('contabilidad_comp_pr_estatus').value="Abierto";
												getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='none';
											getObj('contabilidad_comp_pr_estatus_oc').value='0';
											if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
											{
													if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
													{	
													getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
													getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
													}
											}
									}
									if(recordset[32]!="0,00")
									{
										debito_credito=1;
										getObj('contabilidad_comp_pr_monto').value=recordset[32];
									}else
									if(recordset[33]!="0,00")
									{
										debito_credito=2;
										getObj('contabilidad_comp_pr_monto').value=recordset[33];
									}	
									getObj('contabilidad_comp_pr_debe_haber').value=debito_credito;
									///// activando condiciones de campos ocultos
									//bloquear();
									//	alert(ret.codigo_auxiliar);
									/*if(recordset[27]!=0)
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(recordset[28]!=0)
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(recordset[29]!=0)
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
									if(recordset[30]!=0)
									{
										getObj('contabilidad_comp_pr_activo4').value=1;
									}*/
									//alert(ret.codigo_tipo_comp);
									getObj('contabilidad_comp_pr_tipo').value=recordset[18];
									getObj('contabilidad_comp_pr_tipo_id').value=recordset[4];
									//////////////////////////////////////////////
									getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
									getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='';
									getObj('contabilidad_movimientos_pr_btn_guardar').style.display='none';
									comprobantex=recordset[5];
									jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+comprobantex,page:1}).trigger("reloadGrid");
							url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+comprobantex;
					}
					else
					{
						getObj('contabilidad_comp_pr_activo').value="";
						getObj('contabilidad_comp_pr_activo2').value="";
						getObj('contabilidad_comp_pr_activo3').value="";
						getObj('contabilidad_comp_pr_activo4').value="";
						getObj('contabilidad_comp_pr_cuenta_contable').value="";
						getObj('contabilidad_auxiliares_db_id_cuenta').value="";
						getObj('contabilidad_comp_pr_ref').value="";
						getObj('contabilidad_comp_pr_desc').value="";
						getObj('contabilidad_comp_pr_estatus').value="Abierto";
						getObj('contabilidad_comp_pr_estatus_oc').value=0;
						getObj('contabilidad_comp_pr_debe_haber').value=1;
						getObj('contabilidad_comp_pr_monto').value="0,00";
						getObj('contabilidad_comp_pr_total_debe').value="0,00";
						getObj('contabilidad_comp_pr_total_haber').value="0,00";
						//////////////////////////
						getObj('contabilidad_comp_pr_auxiliar').value="";
						getObj('contabilidad_comp_contabilidad_id').value="";
						getObj('contabilidad_comp_pr_centro_costo').value="";
						getObj('contabilidad_pr_centro_costo_id_cmp').value="";
						getObj('contabilidad_comp_pr_acc').value="";
						getObj('contabilidad_comp_pr_acc_id').value="";
						getObj('contabilidad_comp_pr_ubicacion').value="";
						getObj('contabilidad_comp_pr_ejec_id').value="";
						getObj('contabilidad_comp_pr_utf').value="";
						getObj('contabilidad_comp_pr_utf_id').value="";
						getObj('contabilidad_comp_pr_dif').value="";
						
						jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?numero_comprobante='+0+"&tipo_comprobante="+0}).trigger("reloadGrid");
					}
			 }
		});
}
				
function limpiar_vacio()
{
	clearForm(form_contabilidad_comprobantes_pr_movimientos);
	jQuery("#list_comprobante").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0+"&tipo_comprobante="+0,page:1}).trigger("reloadGrid");
							url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+0+"&tipo_comprobante="+0,
								//alert(url);
								getObj('contabilidad_comp_pr_total_debe').value="0,00";
								getObj('contabilidad_comp_pr_total_haber').value="0,00";

}
$("#contabilidad_movimientos_contables_rp_imprimir").click(function()
 {
	
		desde_numero=getObj('contabilidad_comp_pr_numero_comprobante2').value;
		hasta_numero=getObj('contabilidad_comp_pr_numero_comprobante2').value;
				url="pdf.php?p=modulos/contabilidad/movimientos_contables/rp/vista.lst.comprobante_contabilidad.php¿desde_numero="+desde_numero+"@hasta_numero="+hasta_numero;
		openTab("Movimientos Contables",url);
		//alert(url);
	
});

$("#").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/contabilidad/movimientos_contables/pr/sql.eliminar.php",
			data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
			type:'POST',
			cache: false,
			success: function(html)
			{ 
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					/*getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_guardar').style.display='';
					clearForm('form_adquisiciones_db_impuesto');
					//getObj("adquisiciones_impuesto_db_fecha").value = "<?=  date("d/m/Y"); ?>";
					getObj("adquisiciones_impuesto_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";*/
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
///
//funcion de bloqueo y desbloqueo de campos q pueden alterar la clave principal del numero de comprobante
/*function block_b1()
{
	alert("ent");
	getObj('contabilidad_comp_pr_tipo').disabled='disabled';
	
	
}
function des_block_b1()
{
	getObj('contabilidad_comp_pr_tipo').disabled='';
}*/
//-------------------------------------------------------------------------------------------------------------------------------------
var lastsel,idd,monto;
if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
		{
		if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))

				getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
					
		}
		numero_comprobante=getObj('contabilidad_comp_pr_numero_comprobante2').value;
		//bloqueando en caso de que halla un comprobante en estatus abierto...
		/*if(numero_comprobante!="")
		{
			block_b1();
		}*/
url3='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante;
//alert(url3);	
$("#list_comprobante").jqGrid({
	height: 100,
	width: 570,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
//	+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value
	datatype: "json",
	colNames:['Id','Organismo','Ano','Mes','tipo','Comprobante','Secuencia','Cuenta Contable','Cuenta Contable','Desc','REF','Debe','Haber','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','id_cc','codigo_tipo_comp','estatus','req_aux','req_ueject','req_proyecto','req_utf','desc2'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'codigo_organismo',index:'codigo_organismo', width:20,hidden:true},
			{name:'ano_comprobante',index:'ano_comprobante', width:20,hidden:true},
			{name:'mes_comprobante',index:'mes_comprobante', width:20,hidden:true},
			{name:'codigo_tipo_comprobante',index:'codigo_tipo_comprobante', width:20,hidden:true},
			{name:'numero_comprobante',index:'numero_comprobante', width:20,hidden:true},
			{name:'secuencia',index:'secuencia', width:20,hidden:true},
			{name:'cuenta_contable2',index:'cuenta_contable',width:70},
			{name:'cuenta_contable',index:'cuenta_contable',width:70,hidden:true},
			{name:'descripcion',index:'descripcion',width:70},
			{name:'ref',index:'ref',width:20},
			{name:'monto_debito',index:'monto_debito',width:50},
			{name:'monto_credito',index:'monto_credito',width:50},
			{name:'fecha_comprobante',index:'fecha_comprobante',width:50,hidden:true,hidden:true},
			{name:'codigo_auxiliar',index:'codigo_auxiliar',width:50,hidden:true},
			{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora',width:50,hidden:true},
			{name:'codigo_proyecto',index:'codigo_proyecto',width:50,hidden:true,hidden:true},
			{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:50,hidden:true},
			{name:'id_cc',index:'id_cc',width:50,hidden:true},
			{name:'codigo_tipo_comp',index:'codigo_tipo_comp',width:50,hidden:true},
			{name:'estatus',index:'estatus',width:50,hidden:true},
			{name:'req_aux',index:'req_aux',width:50,hidden:true},
			{name:'req_ueject',index:'req_ueject',width:50,hidden:true},
			{name:'req_proyecto',index:'req_proyecto',width:50,hidden:true},
			{name:'req_utf',index:'req_utf',width:50,hidden:true},
			{name:'descripcion2',index:'descripcion2',width:60,hidden:true}
   	],
	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_comprobante'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	
	
   
	//multiselect: true,
	
		onSelectRow: function(id){
		var ret = jQuery("#list_comprobante").getRowData(id);
	//alert("modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value);
		//setBarraEstado("modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto);
		/*if(getObj('contabilidad_comp_pr_numero_comprobante').value!="")
		{*/
		/*getObj('contabilidad_comp_pr_tipo_id').value=ret.codigo_tipo_comprobante
		getObj('contabilidad_comp_pr_tipo').value=ret.codigo_tipo_comp
		getObj('contabilidad_comp_id_comprobante').value=ret.id*/
//}
		idd="";lastsel="";
		idd = ret.id;
		if(idd && idd!==lastsel){//	alert(idd);
		$.ajax({
			url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes_auto.php?nd='+new Date().getTime()+"&id="+idd,
			//url:"modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value,
            data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		   url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes_auto.php?nd='+new Date().getTime()+"&id="+idd;
			    var recordset=html;			

			//	 alert(url);
		
	  			//alert(recordset);				
				recordset = recordset.split("*");
				//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
				//getObj('contabilidad_comp_pr_fecha_boton_d').value=ret.fecha_comprobante;
			/*	getObj('activo').value='ACTIVO';
				getObj('contabilidad_comp_pr_debe_haber').disabled='disabled';
				getObj('contabilidad_comp_pr_cuenta_contable').disabled='disabled';
				getObj('cuenta_nombre').disabled='disabled';
				getObj('contabilidad_comp_pr_auxiliar').disabled='disabled';*/
				getObj('contabilidad_comp_id_comprobante').value = recordset[0];
				//getObj('contabilidad_comp_pr_numero_comprobante').value = recordset[5];
				getObj('contabilidad_comp_pr_cuenta_contable').value=recordset[8];
				getObj('contabilidad_comp_pr_desc').value=recordset[9];
				getObj('contabilidad_comp_pr_ref').value=recordset[10];
				getObj('contabilidad_comp_pr_comentarios').value=recordset[7];
				debito_credito=1;
				if(recordset[11]!="0,00")
				{
					debito_credito=1;
					getObj('contabilidad_comp_pr_monto').value=recordset[11];
					
				}else
				if(recordset[12]!="0,00")
				{
					debito_credito=2;
					getObj('contabilidad_comp_pr_monto').value=recordset[12];
				}	
				getObj('contabilidad_comp_pr_debe_haber').value=debito_credito;

				
				getObj('contabilidad_comp_pr_auxiliar').value=recordset[24];
				getObj('contabilidad_comp_pr_auxiliar_desc').value=recordset[35];
				getObj('contabilidad_comp_pr_ubicacion').value=recordset[22];
				getObj('contabilidad_comp_pr_ubicacion_desc').value=recordset[33];
				getObj('contabilidad_comp_pr_utf_desc').value=recordset[34];
				getObj('contabilidad_comp_pr_centro_costo').value=recordset[26];
				getObj('contabilidad_comp_pr_centro_costo_desc').value=recordset[36];
				getObj('contabilidad_comp_pr_acc_desc').value=recordset[37];
				getObj('contabilidad_comp_pr_utf').value=recordset[23];
				getObj('contabilidad_auxiliares_db_id_cuenta').value=recordset[17];
				
				//////////////////////////
				getObj('contabilidad_comp_pr_ejec_id').value=recordset[14];
				getObj('contabilidad_comp_pr_utf_id').value=recordset[16];
				getObj('contabilidad_comp_contabilidad_id').value=recordset[13];
				getObj('contabilidad_comp_contabilidad_id2').value=recordset[13];
				getObj('contabilidad_pr_centro_costo_id_cmp').value=recordset[15];
				getObj('contabilidad_comp_pr_acc').value=recordset[25];
				getObj('contabilidad_comp_pr_acc_id').value=recordset[27];
				
				// getObj('movimientos_contables_db_btn_eliminar').style.display='';
				 getObj('movimientos_contables_db_btn_eliminar2').style.display='';

				///////////////////////////
				/////////////////////////////
				if(recordset[21]==0)
				{
					getObj('contabilidad_comp_pr_estatus').value="Abierto";
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
					if ((getObj('contabilidad_comp_pr_total_debe').value)==(getObj('contabilidad_comp_pr_total_haber').value))
					{
					if(((getObj('contabilidad_comp_pr_total_debe').value)!="0,00")&&((getObj('contabilidad_comp_pr_total_haber').value)!="0,00"))
					getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
					getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='none';
                  

					}
				}
				else
				{
					getObj('contabilidad_comp_pr_estatus').value="Cerrado";
					getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='';
					getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
					getObj('contabilidad_movimientos_contables_rp_imprimir').style.display='';


				}
				bloquear();

				///// activando condiciones de campos ocultos
				getObj('cuenta_nombre').value=recordset[32];
				
			if(recordset[28]=='t')
			{
				getObj('contabilidad_comp_pr_activo').value=1;
			}
			if(recordset[31]=='t')
			{
				getObj('contabilidad_comp_pr_activo2').value=1;
			}
			
			if(recordset[29]=='t')
			{
				getObj('contabilidad_comp_pr_activo3').value=1;
			}
			
			if(recordset[30]=='t')
			{
				getObj('contabilidad_comp_pr_activo4').value=1;
			}
			if(recordset[28]=='f')
			{
				getObj('contabilidad_comp_pr_activo').value=0;
			}
			if(recordset[31]=='f')
			{
				getObj('contabilidad_comp_pr_activo2').value=0;
			}
			
			if(recordset[29]=='f')
			{
				getObj('contabilidad_comp_pr_activo3').value=0;
			}
			
			if(recordset[30]=='f')
			{
				getObj('contabilidad_comp_pr_activo4').value=0;
			}
				getObj('contabilidad_comp_pr_tipo').value=recordset[20];
				getObj('contabilidad_comp_pr_tipo_id').value=recordset[4];
		
				getObj('contabilidad_movimientos_pr_btn_guardar').style.display='none';	
				getObj('contabilidad_auxiliares_db_btn_cancelar').style.display='';
				getObj('contabilidad_movimientos_pr_btn_actualizar').style.display='';
				//getObj('tesoreria_moneda_db_btn_eliminar').style.display='';
				//getObj('contabilidad_auxiliares_db_btn_guardar').style.display='none';
		
		//	alert(recordset[37]);
			 }
		});	 
			/*$.ajax (
				{
				url: "modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto,
					data:dataForm('form_pr_cotizaciones'),
					type:'GET',
					cache: false,
					success: function(html)
					{
					setBarraEstado(html);
						if (resultado=="Ok")
						{
							setBarraEstado(mensaje[registro_exitoso],true,true);
							jQuery("#list_cotizacion").setGridParam({url:"modulos/adquisiones/cotizacion/co/sql.consulta.php?numero_requision="+getObj('cotizacones_pr_numero_cotizacion').value,page:1}).trigger("reloadGrid");
							//clearForm('form_pr_cotizaciones');
						}
						else
						{
							(html);
						}

					}
				});	*/
		
			jQuery('#list_comprobante').restoreRow(lastsel);
			jQuery('#list_comprobante').editRow(idd,true);
			lastsel=idd;
			
		}
			
	},
	
}).navGrid("#pager_comprobante",{search :false,edit:false,add:false,del:false});
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

// $('#contabilidad_comp_pr_comentarios').alpha({allow:' áéíóúÄÉÍÓÚ. '});
/*$('#contabilidad_comp_pr_desc').alpha({allow:' áéíóúÄÉÍÓÚ. '});
*/
$('#contabilidad_comp_pr_tipo').numeric({});
$('#contabilidad_comp_pr_cuenta_contable').numeric({});
//$('#contabilidad_comp_pr_ref').alphanumeric({});
$('#contabilidad_comp_pr_cuenta_contable').numeric({});
$('#contabilidad_comp_pr_centro_costo').numeric({});
$('#contabilidad_comp_pr_acc').numeric({});
$('#contabilidad_comp_pr_ubicacion').numeric({});
$('#contabilidad_comp_pr_utf').numeric({});
$('#contabilidad_comp_pr_auxiliar').numeric({});
$('#contabilidad_comp_pr_numero_comprobante').numeric({});




$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	

	
</script>
<div id="botonera">
	<img src="imagenes/null.gif" width="31" height="26" class="btn_cancelar" id="contabilidad_auxiliares_db_btn_cancelar"/>
   <!-- <img id="movimientos_contables_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>-->
	    <img id="movimientos_contables_db_btn_eliminar2" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>

	<img id="contabilidad_movimientos_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img id="contabilidad_movimientos_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_movimientos_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="contabilidad_movimientos_contables_rp_imprimir" class="btn_imprimir" src="imagenes/null.gif" style="display:none" />
	<img id="contabilidad_db_comprobante_manual_btn_abrir" src="imagenes/iconos/abrir_orden_cxp.png"  style="display:none"/>
	<img id="contabilidad_db_comprobante_manual_btn_cerrar" src="imagenes/iconos/cerrar_orden_cxp.png"   style="display:none"/>
</div>	
<form method="post" id="form_contabilidad_comprobantes_pr_movimientos" name="form_contabilidad_comprobantes_pr_movimientos">
<input type="hidden"  id="contabilidad_comp_id_comprobante" name="contabilidad_comp_id_comprobante" value="0"/>
<input type="hidden" id="contabilidad_comp_pr_activo" name="contabilidad_comp_pr_activo"  value="0"/>
 <input type="hidden" id="contabilidad_comp_pr_activo2" name="contabilidad_comp_pr_activo2" value="0"/>
 <input type="hidden" id="contabilidad_comp_pr_activo3" name="contabilidad_comp_pr_activo3" value="0"/>
  <input type="hidden" id="contabilidad_comp_pr_activo4" name="contabilidad_comp_pr_activo4" value="0"/>

  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="6"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Mantenimiento Comprobante

	</th>
	<input   type="hidden" name="contabilidad_comp_pr_id"  id="contabilidad_comp_pr_id" />

	</tr>
	
	<tr>
		 	<th>Tipo Comprobante :</th>
			<td>
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="contabilidad_comp_pr_tipo" id="contabilidad_comp_pr_tipo"  size='12' maxlength="12" onchange="consulta_manual_tipo_comprobante()" value="<? echo($codigo_tipo);?>"
				message="Introduzca el tipo de cuenta" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		        <input type="hidden" id="contabilidad_comp_pr_tipo_id" name="contabilidad_comp_pr_tipo_id"  value="<? echo($tipo);?>"
				 jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
<input type="hidden" id="contabilidad_comp_pr_numero_comprobante2" name="contabilidad_comp_pr_numero_comprobante2"  value="<?php echo($comprobante)?>"/>
<input type="hidden" id="activo" name="activo" value="" />
<!--				<input type="text" name="cuentas_por_pagar_integracion_tipo_nombre" id="cuentas_por_pagar_integracion_tipo_nombre"  size='30' maxlength="30"
				message="Introduzca el tipo de cuenta" />
-->			 </li>
			<li id="contabilidad_comp_btn_consultar_tipo" class="btn_consulta_emergente"></li>
			</ul>			</td>
    </tr>
    <tr >
	<th>N&uacute;mero Comprobante:</th>
		<td>
			<input type="text" id="contabilidad_comp_pr_numero_comprobante" name="contabilidad_comp_pr_numero_comprobante"  onchange="consulta_automatica_comprobante()" onblur="consulta_automatica_comprobante()"  message="Introduzca n comprobante" size="12" maxlength="4"    value="<? echo($comprobante2);?>" readonly="readonly" 
				/>
				
				
	
    <!--jval="{valid:/^[0-9]{1,4}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" -->
    		<input type="hidden" id="numero_comprobante_cont" name="numero_comprobante_cont" />
			<input type="hidden" id="sumador_comprobante" name="sumador_comprobante" />
		</td>
	</tr>
	<tr>
			<th style="display:none">Año:</th>
			<td style="display:none">
				<select id="contabilidad_comp_pr_contable_ano" name="contabilidad_comp_pr_contable_ano">
					<?
					$anio_inicio=date("Y");
					$anio_fin=$anio_inicio+1;
					while($anio_inicio <= $anio_fin)
					{
					?>
					<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
				</select>			</td>
	</tr>	
		<tr>
			<th style="display:none">Mes:</th>
			<td style="display:none">
				<select id="contabilidad_comp_pr_contable_mes" name="contabilidad_comp_pr_contable_mes">
				<option value="0">--Seleccione--</option>
				<option value="1">Enero</option>
				<option value="2">Febrero</option>
				<option value="3">Marzo</option>
				<option value="4">Abril</option>
				<option value="5">Mayo</option>
				<option value="6">Junio</option>
				<option value="7">Julio</option>
				<option value="8">Agosto</option>
				<option value="9">Septiembre</option>
				<option value="10">Octubre</option>
				<option value="11">Noviembre</option>
				<option value="12">Diciembre</option>
				</select>			</td>
		</tr>
		<tr>		
		<th>Fecha:</th>
				  <td><label>
				  <input   alt="date" type="text" name="contabilidad_comp_pr_fecha" id="contabilidad_comp_pr_fecha" size="7"  onchange="v_fecha();" onblur="v_fecha();"  value="<? echo ($fecha_comprobante);?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" readonly="readonly"
					/>
				  
				  <button type="reset" id="contabilidad_comp_pr_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "contabilidad_comp_pr_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "contabilidad_comp_pr_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("contabilidad_comp_pr_fecha").value.MMDDAAAA() );
										//consulta_automatica_impuesto_cxp();
								}
							});
					</script>
					<input type="hidden"  name="contabilidad_comp_pr_fecha_oculto" id="contabilidad_comp_pr_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
                    <input type="hidden" name="compi" id="compi">
				  </label>		        </td>
	</tr>	
	<tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="contabilidad_comp_pr_cuenta_contable" id="contabilidad_comp_pr_cuenta_contable"  size='12' maxlength="12"  onchange="consulta_automatica_cuentas_contables()" onblur="consulta_automatica_cuentas_contables()" 
				message="Introduzca el c&oacute;digo de la cuenta contable" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		       <input type="text" name="cuenta_nombre" id="cuenta_nombre"  size="30"/>
			    <input type="hidden" id="contabilidad_auxiliares_db_id_cuenta" name="contabilidad_auxiliares_db_id_cuenta" />
		 </li>
		<li id="contabilidad_comprobante_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
	    </ul>
		
	  </td>	
	</tr>
		<tr>
			<th>Ref:</th>
		    <td>
			<input type="text" id="contabilidad_comp_pr_ref" name="contabilidad_comp_pr_ref" size="12" maxlength="8" message="Introduzca la refrencia" 
			 />

			</td>
		</tr>
		<tr>
		
			<th>Descripci&oacute;n:</th>
			 <td>
			 <textarea  name="contabilidad_comp_pr_desc" cols="60" id="contabilidad_comp_pr_desc"  message="Introduzca una Descripci&oacute;n del asiento. Ejem:'Esta cuenta es ...' "   ><?php echo($descripcion_valor);?></textarea>			</td>
		</tr>
		<tr>
			<th>Estatus:</th>
			<td>
				<input type="text" id="contabilidad_comp_pr_estatus" name="contabilidad_comp_pr_estatus" value=<? echo ($valor_estatus) ?> readonly="readonly">
					
				<input  type="hidden" name="contabilidad_comp_pr_estatus_oc" id="contabilidad_comp_pr_estatus_oc" value=<? echo ($valor_estatus2) ?> />
			</td>
		</tr>
		<tr>
			<th>Debe-Haber:</th>
			<td>
			<select name="contabilidad_comp_pr_debe_haber" id="contabilidad_comp_pr_debe_haber">
			<option id="1" value="1">Debe</option>
			<option id="2" value="2">Haber</option>
			</select>
			<!--<input type="hidden" name="contabilidad_comprobante_pr_debe_haber" id="contabilidad_comprobante_pr_debe_haber" size="12" maxlength="1" onblur="validar_debe_haber()"
				/>-->
			</td>
							
		</tr>
		<tr>
			<th>
				Monto:
			</th>
			<td>
				<input type="text" name="contabilidad_comp_pr_monto" id="contabilidad_comp_pr_monto" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event);" value="0,00" message="Introduzca el monto dle asiento"  size="12" maxlength="12">			</td>
		</tr>
		<tr>
		</tr>
		<tr>
			<th>Comentarios:</th>
			<td>
				<textarea id="contabilidad_comp_pr_comentarios" name="contabilidad_comp_pr_comentarios" cols="60"/>			</td>
		</tr>	
  </table>
	<table  cols="4" class="cuerpo_formulario" width="100%" border="0">
				<tr>
					<th>Total Debe:</th>
					<td>
						<input type="text" id="contabilidad_comp_pr_total_debe" name="contabilidad_comp_pr_total_debe"  value="<? echo($debe);?>" readonly="readonly" />					</td>
					<th>Total Haber:</th>
					<td>
						<input type="text" id="contabilidad_comp_pr_total_haber" name="contabilidad_comp_pr_total_haber" readonly="readonly" value="<? echo($haber);?>" />					</td>
				</tr>
				<tr>
				
					<th>Auxiliar:		</th>	
					<td colspan="3">
					<ul class="input_con_emergente">
					 <li>	
					<input name="contabilidad_comp_pr_auxiliar" type="text" id="contabilidad_comp_pr_auxiliar"   value="" size="12" maxlength="30" message="Introduzca el c&oacute;digo del auxiliar' "   onblur="auxiliares_consulta_mov()" 
					/> 
					<input type="text"  name="contabilidad_comp_pr_auxiliar_desc" id="contabilidad_comp_pr_auxiliar_desc" size="35"/>
					
					
					 <input type="hidden" id="contabilidad_comp_contabilidad_id" name="contabilidad_comp_contabilidad_id">
					<input type="hidden" id="contabilidad_comp_contabilidad_id2" name="contabilidad_comp_contabilidad_id2">
					 </li>
					<li id="contabilidad_comprobante_btn_consultar_auxiliar_cmp" class="btn_consulta_emergente"></li>
					</ul>					</td>
				
					
				</tr>
				<tr>
					<th>Proyecto:</th>
					<td colspan="3">
					<ul class="input_con_emergente">
					<li>
					<input name="contabilidad_comp_pr_centro_costo" type="text" id="contabilidad_comp_pr_centro_costo"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo del centro de costo' "   onblur="consulta_proyecto_mov();"
										/>
					<input type="text"  name="contabilidad_comp_pr_centro_costo_desc" id="contabilidad_comp_pr_centro_costo_desc" size="35"/>						
						<input type="hidden" id="contabilidad_pr_centro_costo_id_cmp" name="contabilidad_pr_centro_costo_id_cmp" >
					</li>
					<li id="contabilidad_vista_btn_consultar_proyecto_cmp" class="btn_consulta_emergente"></li>	
					</ul>
					</td>
					
					
				</tr>
					<tr>
						<th>Accion Centralizada:</th>
					<td colspan="3">
					<ul class="input_con_emergente">
					<li>
					  <input name="contabilidad_comp_pr_acc" type="text" id="contabilidad_comp_pr_acc"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo de la acci&oacute;n centralizada'" onblur="consulta_acc_mov()" 
										/>
					  <input type="text"  name="contabilidad_comp_pr_acc_desc" id="contabilidad_comp_pr_acc_desc" size="35"/>
					  <input type="hidden" id="contabilidad_comp_pr_acc_id" name="contabilidad_comp_pr_acc_id" />
					 </li>
					<li id="contabilidad_vista_btn_cmp_consultar_acc" class="btn_consulta_emergente"></li>
					</ul>					</td>
					
		</tr>
		<tr>
		<th>Ubicaci&oacute;n:</th>
					<td colspan="3">
					<ul class="input_con_emergente">
					<li>
					  <input name="contabilidad_comp_pr_ubicacion" type="text" id="contabilidad_comp_pr_ubicacion"   value="" size="12" maxlength="12" message="Introduzca ubicaci&oacute;n d ela cuenta ejm:Div. Telemat' " onblur="consulta_ubicacion_mov()"
							/>
					<input type="text"  name="contabilidad_comp_pr_ubicacion_desc" id="contabilidad_comp_pr_ubicacion_desc" size="35" />		
					
					 <input type="hidden" name="contabilidad_comp_pr_ejec_id" id="contabilidad_comp_pr_ejec_id"/>
				      </li>
					<li id="contabilidad_comprobante_btn_consultar_ubicacion_cmp" class="btn_consulta_emergente"></li>
					</ul>					</td>
	
	</tr>
	<tr>
	<th>Utilizaci&oacute;n Fondos</th>
					<td colspan="3">
					<ul class="input_con_emergente">
					<li>
					  <input name="contabilidad_comp_pr_utf" type="text" id="contabilidad_comp_pr_utf"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo de Utilizaci&oacute;n de fondos' " onblur='consulta_utf_mod()'/>
					  <input type="text"  name="contabilidad_comp_pr_utf_desc" id="contabilidad_comp_pr_utf_desc" size="35"/>
					  <input type="hidden" id="contabilidad_comp_pr_utf_id" name="contabilidad_comp_pr_utf_id"  />
				 </li>
				<li id="contabilidad_comprobante_btn_consultar_utf" class="btn_consulta_emergente"></li>
				</ul></td>
				
	</tr>
	<tr>
			<th>Diferencia Debe-Haber 					</th>
					<td colspan="3">
						 <input  type="text" name="contabilidad_comp_pr_dif" id="contabilidad_comp_pr_dif"  readonly="readonly" size="12" maxlength="12"  value="<? echo($resta);?>"/>					</td>		
	</tr>
		<tr style="display:none">
				<th>Filtrar Cuenta</th>
				<td colspan="3">
					<input type="text" name="contabilidad_comp_filtrar" id="contabilidad_comp_filtrar" disabled="disabled" />				</td>
					
		</tr>			
	</table>
  <table   class="cuerpo_formulario" align="center">

	<tr>
		<td class="celda_consulta" colspan="2">
				<table id="list_comprobante" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_comprobante" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>
<input   type="hidden" name="contabilidad_auxiliares_db_id_aux"  id="contabilidad_auxiliares_db_id_aux" />
</form>