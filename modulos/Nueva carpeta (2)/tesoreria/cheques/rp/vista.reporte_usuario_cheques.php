<?php
session_start();
if(date("d")=="31")
{
	$dia=date("d")-1;
	$mes=date("m")-1;
	$ayo=date("Y");
}
	else
	{
		$dia=date("d");	
	}
if(date("m")=="1")
{
	$mes="12";
	$ayo=date("Y")-1;
}
else
	{
	$mes=date("m")-1;
	$ayo=date("Y");
	}
$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>

<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


var dialog;

$("#form_cheques_usuarios_rp_btn_imprimir").click(function() {
if(($('#form_rp_cheques_usuarios').jVal()))
	{
	
	  opciones=getObj('tesoreria_cheque_manual_rp_op_oculto').value;
      if(getObj('tesoreriaa_cheques_estatus').value=='1')
	  {///////////////////////1
	  //generico 1
	  //cheques solos
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.generico_cheques_emitidos.php¿desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
									//alert(url);
									openTab("Cheques",url);
								}
	//usuarios solos												
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
									openTab("Cheques/Usuarios",url);
									//alert(url);
								}
								else
								//proveedor
								if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_cheques.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Proveedores",url);
										//alert(url);
									}
									else
								//banco cuenta	
								if((getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_cheques_s_cuentas.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Bancos",url);
									//	alert(url);
									}
								else
								//banco solo
								if((getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_cheques.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Bancos",url);
										//alert(url);
									}	
				/// ----------------------------consultas de reportes combinados:
							//-----------------usuario
							//  usuario-banco-cuentas
							else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_cheques_usuarios.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Usuarios/Bancos",url);
								//alert(url);
								}
								
							// usuario-banco
							else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_cheques_usuarios_s_cuenta.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Usuarios",url);
								//alert(url);
							
								}	
							//------------------------------ proveedor	
							//porveedor-banco-cuentas
								else
								if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_banco_cuenta_cheques.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Proveedores/Banco/Cuentas",url);
									}
							//porveedor-banco
								else
								if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
									{
										
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_banco_cheques.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
										//alert(url);
										openTab("Cheques/Proveedores/Banco",url);
									}
							//porveedor-banco-USUARIOS
								else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
									{
										
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques.proveedor.banco.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Prove-Us",url);alert(url);
									}			
							//usuario-proveedor
							else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_cheques.usuarios.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Usuarios/Prove",url);
								}
							
							else
							//----todos CON PROVEEDOR
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques.proveedor.banco.cuentas.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
									openTab("Cheques/Prove-Us/Bancos",url);
								}
							else
							//todos CON beneficiario
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!=""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_usuario.cheques.banco.cuentas.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@eva_opcion="+opciones; 
									openTab("Cheques/Usuarios",url);
									
								}	
								
						//---- para beneficiario en el caso de ser seleccionado
						if((getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque').value='2')&&(getObj('tesoreria_cheques_manual_rp_radio2').checked='checked'))
						{
						// beneficiario
							if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_id_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Beneficiarios",url);
									}
						//usuario-beneficiario
							if((getObj('tesoreria_cheques_usuarios_rp_id_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
								{
										url="pdfb.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_usuario.cheques.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@eva_opcion="+opciones; 
										openTab("Cheques/Beneficiarios/Usuarios",url);
								}						
								
								else
					//beneficiario-banco-cuentas
								if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.banco.cuenta.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Benef",url);
									}
					//beneficiario-banco
								
								if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_id_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
									{
										
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.banco.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Beneficiarios/Bancos",url);
									}				
					//beneficiario-banco-USUARIOS
								else
								if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
									{
										
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.usuario.banco.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@eva_opcion="+opciones;
										openTab("Cheques/Bene-Us/Bancos",url);
									}	
						}	
					// limpia_impresion_reportes_cheques();				
	 }
//////////////////////////////////2
	  if(getObj('tesoreriaa_cheques_estatus').value=='2')
	  {
	  if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.generico_cheques_anulados.php¿desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques/Usuarios",url);
				}
	  //usuario
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques_anulados.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques/Usuarios",url);
				}
				else//proveedor
				if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_cheques_anulados.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Proveedores",url);
					}
				//banco cuentas
				if((getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.bancos_cheques_anulados_s_cuentas.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Bancos",url);
						
						
					}
				else//banco
				if((getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="") && (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_cheques_anulados.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Bancos",url);
						
					}
					
			/// consultas de reportes combinados  usuario-banco-cuentas
			else
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques_banco_cuentas_anulados.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value; 
//+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques/Usuarios",url);
				}
				
			// usuario-banco
			else
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques_bancos_anulados.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value; 
				openTab("Cheques/Usuarios",url);
				}	
			//porveedor-banco-cuentas
				else
				if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_banco_cuenta_cheques_anulados.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Proveedores",url);
					}
						//proveedor-banco-usuarios-cuentas
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques.proveedor.banco.cuentas.anulados.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Proveedor/Bancos",url);
					}		
			//porveedor-banco
				else
				if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_banco_cheques.anulados.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value;
						openTab("Cheques/Proveedores",url);
						//alert(url);
					}
			//usuario-proveedor
			else
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.proveedor_cheques.usuarios.anulados.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value; 
				openTab("Cheques/Usuarios",url);
				
				}	
			else//***********/****************************//proveedor-banco-usuarios
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.usuario_cheques.proveedor.banco.anulados.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques/Usuarios",url);
				}
			else
				//todos CON beneficiario
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!=""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.usuario.banco.cuentas.anulados.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@eva_opcion="+opciones;
				openTab("Cheques/Usuarios",url);
				}	
				
		//---- para beneficiario en el caso de ser seleccionado
		  					
			if((getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque').value='2')&&(getObj('tesoreria_cheques_manual_rp_radio2').checked='checked'))
			{
			/*if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
					{
						//url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques_anulados.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						 url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques_an.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Beneficiario",url);

					}*/
					if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_id_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_chequesa.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Beneficiarios",url);
									}
			//usuario-beneficiario
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
				{		
				url="pdfb.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_usuario.cheques.anulados.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques/Beneficiario/Usuarios",url);
				}
			//bancos-beneficiarios-cuentas
			if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.banco.cuenta.anulados.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@cuenta"+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Beneficiarios/Bancos/cuentas",url);
					}	
			else
			//beneficiario-banco
			if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.banco.anulado.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Beneficiarios/Bancos",url);
						
					}			
			else
			//beneficiario-banco-usuarios
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques.usuario.banco.anulados.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
						openTab("Cheques/Beneficiarios/Bancos",url);
			//			alert(url);
						
					}	
		/*else
		// beneficiario
		if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_id_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
									{
										url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.beneficiario_cheques_anulados.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
										openTab("Cheques/Beneficiarios",url);
									}		*/
	  
	  }
	  // limpia_impresion_reportes_cheques();
	  }
	  else
	   if(getObj('tesoreriaa_cheques_estatus').value=='3')
	  {	//usuario emisor
	  //reimpresion generico
	  if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.generico_cheques_reimpresos.php¿desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques Reimpresos",url);
			//	alert(url);
				}else
	 //reimpresion por usuario
	  		if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques Reimpresos",url);
				//alert(url);
				}
							else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_bancos.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Us/Ban/Cuentas",url);
								//alert(url);
							}
							// usuario-banco
							else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_bancos.usuario.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Usuarios/Bancos",url);
								}
							
									
			//////-------------------------------------------			
				
				else//bancoscuentas
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_bancos_s_cuenta.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@cuenta"+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Reimpresos/Bancos/cuentas",url);
					//	alert(url);
					}
			else//bancos 
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_bancos.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Reimpresos/Bancos",url);
				//	alert(url);
					}
			
			
					else//		
			if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{//proveedor
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_proveedor.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Reimpresos/Proveedor",url);
					//alert(url);	
					}
					//porveedor-banco-cuentas
				else
				if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_proveedor.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones+"@cuentas="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value;
						openTab("Cheques/Proveedores",url);
					}
						//proveedor-banco-usuarios
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_usuario_proveedor.banco.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
						openTab("Cheques/Proveedor/Bancos",url);
					}		
			//porveedor-banco
				else
				
				if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_proveedor.banco.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
						openTab("Cheques/Proveedores/Bancos",url);
					}
			//usuario-proveedor
			else
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_usuario_proveedor.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 

				openTab("Cheques/Usuarios",url);
				
				}	
			else
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_usuario_proveedor.banco.cuentas.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques/Usuarios",url);
				}
			else		
		
			if( (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value==""))
					{//beneficiario
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_beneficiario.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Reimpresos/Beneficiario",url);
						
					}else
					//usuario-beneficiario
						if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value==""))
							{		
							url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_beneficiario_us.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value;
							openTab("Cheques/Beneficiario/Usuarios",url);
							}
						//bancos-beneficiarios-cuentas
						if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_beneficiario.banco.cuentas.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@cuenta"+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@eva_opcion="+opciones;
									openTab("Cheques/Beneficiarios/Bancos/cuentas",url);
								}	
						else
						//beneficiario-banco
						if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_beneficiario.banco.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
									openTab("Cheques/Beneficiarios/Bancos",url);
									
								}			
						else
						//beneficiario-banco-usuarios
						if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
						{
							url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_usuario_beneficiario.banco.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
							openTab("Cheques/Beneficiarios/Bancos",url);
						}		
							if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_usuario_beneficiario.banco.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
									openTab("Cheques/Beneficiarios/Bancos",url);
								}
								else
							//todos CON beneficiario
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!=""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_usuario_beneficiario.banco.cuentas.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Usuarios",url);
								}	
											
									
		 	
	  }
/////////////////////////////////4///////////////////////////////////////////
 if(getObj('tesoreriaa_cheques_estatus').value=='4')
	  {		//generico historico
	  		if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico.php¿desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques Historico",url);
				}
			//usuario emisor	
			else
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_usuario.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques Historico/Us",url);
				//alert(url);
				}
							else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_bancos_us_cuentas.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Us/Ban/Cuentas",url);
								
								}
							// usuario-banco
							else
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_usuario_bancos.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Usuarios/Bancos",url);
								
								}
							
									
			//////-------------------------------------------			
				
				else//bancoscuentas
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_banco_cuentas.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@cuenta"+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Reimpresos/Bancos/cuentas",url);
					}
			else//bancos 
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=="") )
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_banco.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Historico/Bancos",url);
						
					}
			
			
					else//		
			if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&& (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{//proveedor
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Reimpresos/Proveedor",url);
						//alert(url);
						
					}
					//porveedor-banco-cuentas
				else
				if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco_c_cuentas.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones+"@cuentas="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value;
						openTab("Cheques/Proveedores",url);
					}
						//proveedor-banco-usuarios
			if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco_cuentas.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
						openTab("Cheques/Proveedor/Bancos",url);
					}		
			//porveedor-banco
				else
				if((getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
					{
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco.php¿id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
						openTab("Cheques/Proveedores/Bancos",url);
						//alert(url);
					}
			//usuario-proveedor
			else
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_usuario.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 

				openTab("Cheques/Usuarios",url);
				
				}	
			else//todos y proveedor
				if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value==""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco_cuentas_us.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@id_proveedor="+getObj('tesoreria_cheques_proveedores_rp_id').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
				openTab("Cheques/Usuarios",url);
				}
			else		
		
			if( (getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
					{//beneficiario
						url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones; 
						openTab("Cheques Reimpresos/Beneficiario",url);alert(url);
						
						
					}else
					//usuario-beneficiario
						if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value=="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
							{		
							url="pdfb.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_usuario.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@eva_opcion="+opciones+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value; 
							openTab("Cheques/Beneficiario/Usuarios",url);
							}
						//bancos-beneficiarios-cuentas
						if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco_c_cuentas.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@cuenta"+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@eva_opcion="+opciones;
									openTab("Cheques/Beneficiarios/Bancos/Cuentas",url);
								}	
						else
						//beneficiario-banco
						if((getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_usuarios_rp_usuario').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
									openTab("Cheques/Beneficiarios/Bancos",url);
								}			
						else
						//beneficiario-banco-usuarios
						if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
						{
							url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco_cuentas.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@eva_opcion="+opciones;
							openTab("Cheques/Beneficiarios/Bancos",url);
						}		
							/*if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value=="")&&(getObj('tesoreria_cheques_proveedores_rp_id').value==""))
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_reimpresos_usuario_beneficiario.banco.php¿rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@id_usuario="+getObj('tesoreria_cheques_usuarios_rp_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@eva_opcion="+opciones;
									openTab("Cheques/Beneficiarios/Bancos",url);
								}*/	
								else
							//todos CON beneficiario
								if((getObj('tesoreria_cheques_usuarios_rp_usuario').value!="")&&(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")&&(getObj('tesoreria_cheques_banco_rp_n_cuenta').value!="")&&(getObj('tesoreria_cheques_manual_rp_empleado_codigo').value!=""))
								{
								url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_historico_proveedor_banco_c_cuentas.php¿id_usuario="+getObj('tesoreria_cheques_usuarios_rp_id_usuario').value+"@desde="+getObj('tesoreria_cheques_usuarios_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_usuarios_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value+"@id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@tipo="+getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value+"@rif="+getObj('tesoreria_cheques_manual_rp_empleado_codigo').value+"@eva_opcion="+opciones; 
								openTab("Cheques/Usuarios",url);
								}	
											
									
		 	
	  }
//////////////////////////////////////////////////////////////////////////////	
	
//	limpia_impresion_reportes_cheques();		
	
	
	
	 }
	 getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque').value='3';
	 getObj('tesoreria_cheque_manual_rp_op_oculto').value="1";
	 getObj('tesoreria_cheques_manual_rp_radio1').checked='checked';
	 //getObj('tr_beneficiario_reporte_cheque').style.display='';
	
	 
});
$("#form_cheques_usuarios_rp_btn_cancelar").click(function() {
	limpia_impresion_reportes_cheques();
	
	
});

function limpia_impresion_reportes_cheques()
{
	setBarraEstado("");
	clearForm('form_rp_cheques_usuarios');
	getObj("tesoreria_cheques_usuarios_rp_fecha_desde").value = "<?=$fecha;   ?>";
	getObj("tesoreria_cheques_usuarios_rp_fecha_hasta").value = "<?=date("d/m/Y");   ?>";
	getObj("tesoreria_cheques_usuarios_rp_fecha_desde_oculto").value="<?=  $fecha; ?>";
	getObj("tesoreria_cheques_usuarios_rp_fecha_hasta_oculto").value="<?= date("d/m/Y"); ?>";
	getObj("tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque").value=1;
	getObj('tr_proveedor_reporte_cheque').style.display='';
	getObj('tesoreria_cheque_manual_rp_op_oculto').value="1";
	getObj('tesoreria_cheques_manual_rp_radio1').checked='checked';
	getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque').value='3';
	getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value='3';
	getObj('tesoreriaa_cheques_estatus').value='0';
	
	
}


//------------------------------------------------------------------------------
$("#tesoreria_cheques_usuarios_rp_btn_consultar_usuario").click(function() {
////
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/rp/grid_usuario_banco_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario").val(); 
					var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-reportes-busq_nombre_usuario").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_reportes_dosearch();
												
					});
				$("#tesoreria-reportes-busq_nombre_usuario2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_reportes_dosearch();
												
					});
					function tesoreria_usuario_reportes_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_reportes_gridReload,500)
										}
						function tesoreria_usuario_reportes_gridReload()
						{
							var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario").val(); 
							var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario2").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			
						}
			}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Usuario'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:300,sortable:false,resizable:false}
								
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_cheques_usuarios_rp_id_usuario').value = ret.id;
									getObj('tesoreria_cheques_usuarios_rp_usuario').value = ret.nombre;
								
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
	
});


//-------------------------------------------------------------------
//------------------------------------------------------------------------------
$("#tesoreria_cheques_proveedor_db_btn_consultar_proveedor").click(function() {
if((getObj('tesoreria_cheque_manual_rp_op_oculto').value=='1')||(getObj('tesoreria_cheque_manual_rp_op_oculto').value=='3'))
{
/*		var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/tesoreria/cheques/rp/grid_usuario_banco_cuentas.php", { },
									function(data)
								{								
										dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
										setTimeout(crear_grid,100);
								});*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/rp/grid_pagar.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Proveedor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor= jQuery("#tesoreria_rp_proveedor_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_rp_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				function consulta_doc_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc_gridReload,500)
					}
						function consulta_doc_gridReload()
						{
							var busq_proveedor= jQuery("#tesoreria_rp_proveedor_consulta").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/rp/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor;
						//	setBarraEstado(url);
						}

			}
		});

//								
								function crear_grid()
								{
									jQuery("#list_grid_"+nd).jqGrid
									({
										width:400,
										height:300,
										recordtext:"Registro(s)",
										loadtext: "Recuperando Información del Servidor",		
										url:'modulos/tesoreria/cheques/rp/cmb.sql.proveedor.php?nd='+nd,
										datatype: "json",
										colNames:['Id','Codigo','Proveedor'],
										colModel:[
											{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
											{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
											{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
										],
										pager: $('#pager_grid_'+nd),
										rowNum:20,
										rowList:[20,50,100],
										imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
										onSelectRow: function(id){
										var ret = jQuery("#list_grid_"+nd).getRowData(id);
											getObj('tesoreria_cheques_proveedores_rp_id').value = ret.id_proveedor;
											getObj('tesoreria_cheques_proveedores_rp_codigo').value = ret.codigo;
											getObj('tesoreria_cheques_proveedores_rp_nombre').value = ret.nombre;
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
										sortname: 'id_proveedor',
										viewrecords: true,
										sortorder: "asc"
									});
								}
}	
});
//-------------------------------------------------------------------
$("#tesoreria_cheques_banco_rp_btn_consultar_banco").click(function() {

/*	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos Activos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/rp/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_banco_cheques.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_banco_cuenta_banco-busqueda_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				
						function consulta_doc_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc_gridReload,500)
										}
						function consulta_doc_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_banco_cheques.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/rp/sql_grid_banco_cheques.php?busq_banco="+busq_banco;
							
						}

			}
		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/pr/sql_grid_banco_cheques.php?nd='+nd,								datatype: "json",
     								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo Área','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas','id_banco_cheques','banco_cheques','cuenta_banco_cheques'],
									colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:160,sortable:false,resizable:false},
									{name:'sucursal' ,index:'sucursal', width:130,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigoarea',index:'codigoarea', width:50,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fax',index:'fax',width:50,sortable:false,resizable:false,hidden:true},
									{name:'persona_contacto',index:'persona_contacto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cargo_contacto',index:'cargo_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'email_contacto',index:'email_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'pagina_banco',index:'pagina_banco', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true },
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_banco_cheques',index:'id_banco_cheques', width:100,sortable:false,resizable:false,hidden:true},
									{name:'banco_cheques',index:'banco_cheques', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_banco_cheques',index:'cuenta_banco_cheques', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheques_banco_rp_id_banco').value = ret.id;
									getObj('tesoreria_cheques_banco_rp_nombre').value = ret.nombre;
									//getObj('tesoreria_cheques_banco_rp_n_cuenta').value = ret.cuentas;									
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
	
});//--------------------------------------------------------------------------------------------------------
$("#tesoreria_cheques_banco_rp_btn_consultar_cuentas").click(function() {
if(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")
{
	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/pr/sql_grid_cuenta_cheque.php?nd='+nd+'&banco='+getObj('tesoreria_cheques_banco_rp_id_banco').value,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','N  Cuenta','Estatus','CuentaNuevo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuentan',index:'cuentan', width:50,sortable:false,resizable:false,hidden:true}

									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheques_banco_rp_n_cuenta').value=ret.ncuenta;
									dialog.hideAndUnload();
									//$('#form_tesoreria_db_usuario_banco_cuentas').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
								
							});
						}
}
});
//--------------------------------------------------------------------------------------------------------------------------------------
//consultas automaticas
function consulta_automatica_proveedor()
{
if((getObj('tesoreria_cheque_manual_rp_op_oculto').value=='1')||(getObj('tesoreria_cheque_manual_rp_op_oculto').value=='3'))
{

	$.ajax({
			url:"modulos/tesoreria/cheques/rp/sql_grid_proveedor_rp_codigo_manual_reporte",
            data:dataForm('form_rp_cheques_usuarios'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('tesoreria_cheques_proveedores_rp_id').value=recordset[0];
				getObj('tesoreria_cheques_proveedores_rp_nombre').value = recordset[1];
				}
				else
			 {  
			   	getObj('tesoreria_cheques_proveedores_rp_nombre').value ="";
				getObj('tesoreria_cheques_proveedores_rp_id').value="";
				}
				
			 }
		});	
	}	 	 
}
////////////////////////
$("#tesoreria_cheques_empleado_db_btn_consultar_empleado").click(function() {

if((getObj('tesoreria_cheque_manual_rp_op_oculto').value=='2')||(getObj('tesoreria_cheque_manual_rp_op_oculto').value=='3'))
{
		/*var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/rp/grid_pagar.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Empleados Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor= jQuery("#tesoreria_rp_proveedor_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_rp_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc2_dosearch();
					});
				
						function consulta_doc2_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc2_gridReload,500)
										}
						function consulta_doc2_gridReload()
						{
							var busq_proveedor= jQuery("#tesoreria_rp_proveedor_consulta").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/rp/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor;
						}

			}
		});
						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.beneficiario.php?nd='+nd,
								datatype: "json",
								colNames:['rif','beneficiario'],
								colModel:[
									{name:'rif',index:'rif', width:50,sortable:false,resizable:false},
									{name:'beneficiario',index:'beneficiario', width:100,sortable:false,resizable:false}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheques_manual_rp_empleado_codigo').value = ret.rif;
									getObj('tesoreria_cheque_manual_rp_empleado_nombre').value = ret.beneficiario;
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
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}	
});
//---------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_empleado()
{//alert('paso');
if((getObj('tesoreria_cheque_manual_rp_op_oculto').value=='2')||(getObj('tesoreria_cheque_manual_rp_op_oculto').value=='3'))
{
	$.ajax({
			url:"modulos/tesoreria/cheques/rp/sql_grid_empleado_codigo_emi.php",
            data:dataForm('form_rp_cheques_usuarios'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				var cod_em=getObj('tesoreria_cheques_manual_rp_empleado_codigo').value;
				getObj('tesoreria_cheques_manual_rp_empleado_codigo').value=cod_em;
				getObj('tesoreria_cheque_manual_rp_empleado_nombre').value = recordset[1];
				}
				else
			 {  
			   	getObj('tesoreria_cheque_manual_rp_empleado_nombre').value ="";
				getObj('tesoreria_cheques_manual_rp_empleado_codigo').value="";
				}
				
			 }
		});	 
		}	 
}
//-
function cambio_combo()
{
	getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb').value=getObj('tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque').value;
}
/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
	$('#tesoreria_cheques_proveedores_rp_codigo').change(consulta_automatica_proveedor);
	$('#tesoreria_cheques_manual_rp_empleado_codigo').blur(consulta_automatica_empleado);
	
/*-------------------   Fin Validaciones  ---------------------------*/
</script>
<script language="javascript" type="text/javascript">
	//getObj('tipo_oculto_usu').value= 1;
	
	$("#tesoreria_cheques_manual_rp_radio1").click(function(){
		getObj('tesoreria_cheque_manual_rp_op_oculto').value="1"
	});
$("#tesoreria_cheques_manual_rp_radio2").click(function(){
		getObj('tesoreria_cheque_manual_rp_op_oculto').value="2"
	});
$("#tesoreria_cheques_manual_rp_radio3").click(function(){
		getObj('tesoreria_cheque_manual_rp_op_oculto').value="3"
	});	

</script>
<div id="botonera">
	<img id="form_cheques_usuarios_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_cheques_usuarios_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_cheques_usuarios" id="form_rp_cheques_usuarios">
  <table  class="cuerpo_formulario"  style="width:700">
		<tr>
		<th  class="titulo_frame" colspan="3"> <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Relaci&oacute;n Cheques Emitidos </th>
	   </tr>
	   <tr>
	   		<th width="260">ESTATUS CHEQUE :<SELECT id="tesoreriaa_cheques_estatus" name="tesoreriaa_cheques_estatus">
			<option value="1">EMITIDO</option>
			<option value="2">ANULADO</option>
			<option value="3">REIMPRESO</option>
			<option value="4">TODOS</option>
			</SELECT>
			</th>
			<th width="257">DESDE:
			<input readonly="true" type="text" name="tesoreria_cheques_usuarios_rp_fecha_desde" id="tesoreria_cheques_usuarios_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_cheques_usuarios_rp_fecha_desde_oculto" id="tesoreria_cheques_usuarios_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="tesoreria_cheques_usuarios_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_cheques_usuarios_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_cheques_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_cheques_usuarios_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_cheques_usuarios_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_cheques_usuarios_rp_fecha_desde").value =getObj("tesoreria_cheques_usuarios_rp_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      
			</th>
			<th width="167">HASTA:
			<input readonly="true" type="text" name="tesoreria_cheques_usuarios_rp_fecha_hasta" id="tesoreria_cheques_usuarios_rp_fecha_hasta" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_cheques_usuarios_rp_fecha_hasta_oculto" id="tesoreria_cheques_usuarios_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="tesoreria_cheques_usuarios_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_cheques_usuarios_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_cheques_usuarios_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_cheques_usuarios_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_cheques_usuarios_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_cheques_usuarios_rp_fecha_hasta").value =getObj("tesoreria_cheques_usuarios_rp_fecha_hasta_oculto").value;
									}
							}
					});
			</script>
	      	
			</th>
	  </tr>
	   <tr>
	   		<th width="260">USUARIO RESPONSABLE:</th>
			<th width="257">BANCO:</th>
			<th width="167">CUENTA:</th>
	  </tr>
	  
<tr>
	 <th>
	 <table class="clear" width="100%" border="0">
			<tr>				
								<td>
									<input name="tesoreria_cheques_usuarios_rp_usuario"id="tesoreria_cheques_usuarios_rp_usuario" type="text"  size="35">
								</td>
								<td>
									<img class="btn_consulta_emergente" id="tesoreria_cheques_usuarios_rp_btn_consultar_usuario" src="imagenes/null.gif" />
									<input name="tesoreria_cheques_usuarios_rp_id_usuario" id="tesoreria_cheques_usuarios_rp_id_usuario" type="hidden" disabled="disabled">
								</td>
			</tr>	
		</table>	
	 </th>
	 <th>
	<table class="clear" width="100%" border="0">
				<tr>				
									<td><input name="tesoreria_cheques_banco_rp_nombre"id="tesoreria_cheques_banco_rp_nombre" type="text"  size="35" /></td>
									<td>
										<img class="btn_consulta_emergente" id="tesoreria_cheques_banco_rp_btn_consultar_banco" src="imagenes/null.gif" />
										<input name="tesoreria_cheques_banco_rp_id_banco" id="tesoreria_cheques_banco_rp_id_banco" type="hidden" disabled="disabled">									</td>
				</tr>				
		</table>		 
	 <th>
						<table class="clear" width="100%" border="0">
						<tr>		
									<td>
										<input name="tesoreria_cheques_banco_rp_n_cuenta"id="tesoreria_cheques_banco_rp_n_cuenta" type="text"  size="35">
									</td>
									<td>
										<img class="btn_consulta_emergente" id="tesoreria_cheques_banco_rp_btn_consultar_cuentas" src="imagenes/null.gif" />
									</td>
						  </tr>
					   </table>
	</th>
	  
</tr>
<tr>
	<th>
			Tipo :
	      <label>
	      <select name="tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque" id="tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque" onchange="cambio_combo()" >
	        <option value="3" selected="selected">Todos</option>
            <option value="1">Autom&aacute;tico</option>
            <option value="2" >Manual</option>

          </select>
	      <input type="hidden" name="tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb" id="tesoreria_cheques_proveedor_db_btn_consultar_tipo_cheque_cmb"  value="3"/>
	      </label>
	</th>
	 <th colspan="2">
	 <table class="clear" width="100%" border="0" >
			<tr style="display:none">				
								<th>Beneficiario:</th>
	                            <td><label>
	     <input name="tesoreria_cheques_manual_rp_radio" type="radio" id="tesoreria_cheques_manual_rp_radio1"  value="1" checked="CHECKED"/>
	    Prooveedor</label>
	    &nbsp;&nbsp;
	    <label>
          <input name="tesoreria_cheques_manual_rp_radio" type="radio" id="tesoreria_cheques_manual_rp_radio2"  value="0" />
      Empleado</label>
	  <label>
	      <input type="radio" name="tesoreria_cheques_manual_rp_radio" value="3" id="tesoreria_cheques_manual_rp_radio3"  />
	      Todos</label>
	  <input type="hidden" name="tesoreria_cheque_manual_rp_op_oculto" id="tesoreria_cheque_manual_rp_op_oculto" value="1" /></td>
			</tr>	
		</table>	
	 </th>
	 
</tr>
<tr>
	 <th colspan="3">
	 <table class="clear" width="100%" border="0" >
				
			<tr id="tr_proveedor_reporte_cheque">
	<th>Proveedor:</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				<input name="tesoreria_cheques_proveedores_rp_codigo" type="text" id="tesoreria_cheques_proveedores_rp_codigo"  maxlength="4"  message="Introduzca el Nº Proveedor. "
				onchange="consulta_automatica_proveedor" onclick="consulta_automatica_proveedor"
				size="5"/>
	
				<input type="text" name="tesoreria_cheques_proveedores_rp_nombre" id="tesoreria_cheques_proveedores_rp_nombre" size="35"
			    readonly />
				<input type="hidden" name="tesoreria_cheques_proveedores_rp_id" id="tesoreria_cheques_proveedores_rp_id" readonly />
				</li> 
					<li id="tesoreria_cheques_proveedor_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
			  
			  </ul>			</td>
			  
	</tr>
	</table>
	<th>
		<!--<input name="comprometer_rp_unidad" id="radio" type="radio" value="0" />
UNO
<input name="comprometer_rp_unidad" id="radio" type="radio" value="0" />
TODOS-->
	  
		 
	</th>
</tr>
<tr>
		 <th colspan="3">
	 <table class="clear" width="100%" border="0" >
				
			<tr id="tr_proveedor_reporte_cheque">
	 <tr id="tr_empleado_reporte_cheque" style="display:none">
      <th>Empleado :</th>
      <td >		<ul class="input_con_emergente">
	  <li>
				<input name="tesoreria_cheques_manual_rp_empleado_codigo" type="text" id="tesoreria_cheques_manual_rp_empleado_codigo"
				onchange="" onclick="" onblur="consulta_automatica_empleado()"  size="5"  maxlength="5" 
				message="Introduzca un Codigo para el Empleado."
				/>
	
				<input name="tesoreria_cheque_manual_rp_empleado_nombre" type="text" id="tesoreria_cheque_manual_rp_empleado_nombre" size="35" maxlength="60" readonly=""
				message="Introduzca el nombre del Empleado." />
	  </li> 
	  					<li id="tesoreria_cheques_empleado_db_btn_consultar_empleado" class="btn_consulta_emergente"></li>

		</ul> 
		
		     </td></tr>
	</table>
		<label>Nota:Las Impresiones de relaciones de cheques deben ser impresas en hojas tama&ntilde;o oficio</label>

	
	<th>
		<!--<input name="comprometer_rp_unidad" id="radio" type="radio" value="0" />
UNO
<input name="comprometer_rp_unidad" id="radio" type="radio" value="0" />
TODOS-->
	  
	</th>	
</tr>
	
	 <tr>
      
	  <td colspan="3" class="bottom_frame">&nbsp;</td>
  	 
    </tr> 	
	  </table>

</form>
