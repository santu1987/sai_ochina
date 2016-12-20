<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	$sql="SELECT * FROM chequeras ORDER BY secuencia desc";
	$rs_chequera =& $conn->Execute($sql);
	$ncheque=$rs_chequera->fields("secuencia")+1;
?>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script type="text/javascript" language="JavaScript">   
/* Marcaras de edicion de campos de entrada --> */
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
</script>	
<script type='text/javascript'>
var dialog;
$("#tesoreria_chequeras_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/chequeras/db/grid_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Chequeras', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_cuenta= jQuery("#tesoreria-consultas-busq_cuentas").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				
				$("#tesoreria-consultas-busq_bancos").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_cuentas_dosearch();
												
					});
				$("#tesoreria-consultas-busq_cuentas").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_cuentas_dosearch();
												
					});
				/*$("tesoreria-consultas-busq_anos").keypress(function(key)
				{
						
						tesoreria_usuario_cuentas_dosearch();
				});*/
					function tesoreria_usuario_cuentas_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_cuentas_gridReload,500)
										}
						function tesoreria_usuario_cuentas_gridReload()
						{
							var busq_cuenta= jQuery("#tesoreria-consultas-busq_cuentas").val();
							var busq_banco= jQuery("#tesoreria-consultas-busq_bancos").val();
/*							var busq_anos= jQuery("#tesoreria-consultas-busq_anos").val();
*/							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta+'&busq_banco='+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/chequeras/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta+'&busq_banco='+busq_banco;
							alert(url);
						}
			}
		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/chequeras/db/sql_grid_chequeras.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Id_banco','Banco','Banco_larg','N Cuenta','N de chequera','Primer Cheque','Proximo a emitir','Cantidad de Cheques','Cantidad Emitidos','Estatus','Comentarios'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idbanco',index:'idbanco', width:50,sortable:false,resizable:false,hidden:true},
									{name:'banco_corto',index:'banco_corto', width:130,sortable:false,resizable:false},
									{name:'banco',index:'banco', width:130,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:130,sortable:false,resizable:false},
									{name:'secuencia',index:'secuencia', width:130,sortable:false,resizable:false},
									{name:'primer_cheque' ,index:'primer_cheque', width:130,sortable:false,resizable:false},
									{name:'ultimo_emitido' ,index:'ultimo_emitido', width:110,sortable:false,resizable:false},
									{name:'cantidad_cheques',index:'cantidad_cheques', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cantidad_emitidos',index:'cantidad_emitidos', width:50,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:70,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true }
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_chequeras_db_id').value=ret.id;
									getObj('tesoreria_chequeras_cuenta_id_banco').value=ret.idbanco;
									getObj('tesoreria_chequeras_cuenta_db_nombre').value=ret.banco;
									getObj('tesoreria_chequeras_cuenta_db_n_cuenta').value=ret.ncuenta;
									getObj('tesoreria_chequeras_primer_cheque').value=ret.primer_cheque;
									getObj('tesoreria_chequeras_ultimo_emitido').value=ret.ultimo_emitido;
									getObj('tesoreria_chequeras_cantidad_cheques').value=ret.cantidad_cheques;
									getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value=ret.cantidad_emitidos;	
									getObj('tesoreria_chequeras_db_comentarios').value=ret.comentarios;
									getObj('tesoreria_chequeras_cuenta_db_ncheque').value=ret.secuencia;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=ret.secuencia;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=ret.secuencia;
									getObj('tesoreria_chequeras_db_btn_cancelar').style.display='';
									getObj('tesoreria_chequeras_db_btn_actualizar').style.display='';
									getObj('tesoreria_chequeras_db_btn_guardar').style.display='none';	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled=true;
									getObj('tesoreria_chequeras_primer_cheque').disabled=true;
									ultimo_cheque_emitido_consultado();
							
									if(ret.estatus=='Activo')
										{ 
											getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
											getObj('tesoreria_chequeras_db_estatus').value="1";
											getObj('tesoreria_chequeras_db_estatus_opt_act').disabled="";		
											getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled="";
									
										}else
										if(ret.estatus=='Inactivo')
										{
										getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="checked";
										getObj('tesoreria_chequeras_db_estatus').value="2";
										getObj('tesoreria_chequeras_db_estatus_opt_act').disabled="";		
										getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled="";
										}	
										else
										if(ret.estatus=='Agotada')
										
										{
										getObj('tesoreria_chequeras_db_estatus_opt_agotado').checked="checked";
										getObj('tesoreria_chequeras_db_estatus').value="3";
										getObj('tesoreria_chequeras_db_estatus_opt_act').disabled="true";		
										getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled="true";
										}
										dialog.hideAndUnload();
	
										/*getObj('tesoreria_chequeras_db_estatus_opt_act').disabled=true		
										getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled=true*/		
										getObj('tesoreria_chequeras_db_estatus_opt_agotado').disabled=true;		
									$('#form_tesoreria_db_chequeras').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'estatus',
								viewrecords: true,
								sortorder: "desc"
							});
						}
});
$("#tesoreria_db_btn_consultar_cuentas_chequeras").click(function() {
if(getObj('tesoreria_chequeras_cuenta_id_banco').value!="")
{
	urls='modulos/tesoreria/chequeras/db/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_chequeras_cuenta_id_banco').value;
	///
	var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/chequeras/db/grid_chequeras_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuentas Bancarias', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_ano= jQuery("#tesoreria_chequeras_db_ano").val();
					var busq_cuenta= jQuery("#tesoreria_chequeras_db_cuenta").val(); 
 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/db/sql_grid_cuentas.php?busq_ano="+busq_ano+'&banco='+getObj('tesoreria_chequeras_cuenta_id_banco').value+'&busq_cuenta='+getObj('tesoreria_chequeras_db_cuenta').value,page:1}).trigger("reloadGrid"); 
				}	

				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//-busqueda por banco
				$("#tesoreria_chequeras_db_ano").keypress(function(key)
				{	ano_dosearch();
				});
				$("#tesoreria_chequeras_db_cuenta").keypress(function(key)
				{	ano_dosearch();
				});
					function ano_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(ano_gridReload,500)
						}
					function ano_gridReload()
					{
							var busq_ano= jQuery("#tesoreria_chequeras_db_ano").val(); 
							var busq_cuenta= jQuery("#tesoreria_chequeras_db_cuenta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/db/sql_grid_cuentas.php?busq_ano="+busq_ano+'&banco='+getObj('tesoreria_chequeras_cuenta_id_banco').value+'&busq_cuenta='+getObj('tesoreria_chequeras_db_cuenta').value,page:1}).trigger("reloadGrid");
						url="modulos/tesoreria/chequeras/db/sql_grid_cuentas.php?busq_ano="+busq_ano+'&banco='+getObj('tesoreria_chequeras_cuenta_id_banco').value+'&busq_cuenta='+getObj('tesoreria_chequeras_db_cuenta').value;
					///	alert(url);
						}
							
	
				}
		});
	//

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								datatype: "json",
								colNames:['Id','N Cuenta','Estatus','Chequeras'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'chequera',index:'chequera', width:50,sortable:false,resizable:false,hidden:true}
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_chequeras_cuenta_db_n_cuenta').value=ret.ncuenta;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=ret.chequera;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=ret.chequera;

									dialog.hideAndUnload();
									//$('#form_tesoreria_db_usuario_banco_cuentas').jVal();
					 			///--------------------------------------------------------------------
									setBarraEstado("");
									//getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
									getObj('tesoreria_chequeras_db_btn_actualizar').style.display='none';
									getObj('tesoreria_chequeras_db_btn_guardar').style.display='';
									getObj('tesoreria_chequeras_db_btn_consultar').style.display='';
									getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
									getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
									valr=getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value;
									getObj('tesoreria_chequeras_primer_cheque').value="";
									getObj('tesoreria_chequeras_ultimo_emitido').value="";
									getObj('tesoreria_chequeras_cantidad_cheques').value="";
									getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value="";	
									getObj('tesoreria_chequeras_db_comentarios').value="";
									getObj('tesoreria_chequeras_cantidad_cheques_faltantes').value="";
									//getObj('tesoreria_chequeras_cuenta_db_ncheque').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=valr;
									getObj('tesoreria_chequeras_db_btn_cancelar').style.display='';
									//getObj('tesoreria_chequeras_db_btn_guardar').style.display='';	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled=true;
								//	getObj('tesoreria_chequeras_primer_cheque').disabled=true;
									//clearForm('form_tesoreria_db_chequeras');
									getObj('tesoreria_chequeras_db_estatus').value="1";	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled="";
									getObj('tesoreria_chequeras_primer_cheque').disabled="";
									getObj('tesoreria_chequeras_primer_cheque').value="000000"
									getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
								//-------------------------------------------------------------------
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
$("#tesoreria_db_btn_consultar_banco_chequeras").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/chequeras/db/grid_chequeras.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos activos',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/chequeras/db/grid_banco.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_chequeras_banco").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_chequeras_banco").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_rel_banco_us_dosearch();
					});
				
						function consulta_rel_banco_us_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_rel_banco_us_gridReload,500)
										}
						function consulta_rel_banco_us_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_chequeras_banco").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							//url="modulos/tesoreria/movimientos/db/sql_grid_banco.php";
						}

			}
		});	
			function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/chequeras/db/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo &aacute;rea','Tel&eacute;fono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas','chequeras'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:130,sortable:false,resizable:false},
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
									{name:'chequera',index:'chequera', width:100,sortable:false,resizable:false,hidden:true}
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_chequeras_cuenta_id_banco').value=ret.id;
									getObj('tesoreria_chequeras_cuenta_db_nombre').value=ret.nombre;
									getObj('tesoreria_chequeras_cuenta_db_n_cuenta').value=ret.cuentas;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=ret.chequera;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=ret.chequera;
								dialog.hideAndUnload();
									//$('#form_tesoreria_db_chequeras').jVal();
					 			///--------------------------------------------------------------------
								setBarraEstado("");
									//getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
									getObj('tesoreria_chequeras_db_btn_actualizar').style.display='none';
									getObj('tesoreria_chequeras_db_btn_guardar').style.display='';
									getObj('tesoreria_chequeras_db_btn_consultar').style.display='';
									getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
									getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
									valr=getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value;
									getObj('tesoreria_chequeras_primer_cheque').value="";
									getObj('tesoreria_chequeras_ultimo_emitido').value="";
									getObj('tesoreria_chequeras_cantidad_cheques').value="";
									getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value="";	
									getObj('tesoreria_chequeras_db_comentarios').value="";
									getObj('tesoreria_chequeras_cantidad_cheques_faltantes').value="";
									//getObj('tesoreria_chequeras_cuenta_db_ncheque').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=valr;
									getObj('tesoreria_chequeras_db_btn_cancelar').style.display='';
									//getObj('tesoreria_chequeras_db_btn_guardar').style.display='';	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled=true;
								//	getObj('tesoreria_chequeras_primer_cheque').disabled=true;
									//clearForm('form_tesoreria_db_chequeras');
									getObj('tesoreria_chequeras_db_estatus').value="1";	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled="";
									getObj('tesoreria_chequeras_primer_cheque').disabled="";
									getObj('tesoreria_chequeras_primer_cheque').value="000000"
									getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
							//-------------------------------------------------------------------
								},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#tesoreria_chequeras_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/chequeras/db/sql.actualizar.php",
			data:dataForm('form_tesoreria_db_chequeras'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					//getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
					getObj('tesoreria_chequeras_db_btn_actualizar').style.display='none';
					getObj('tesoreria_chequeras_db_btn_guardar').style.display='';
					getObj('tesoreria_chequeras_db_btn_cancelar').style.display='';
					valor=getObj('tesoreria_chequeras_cuenta_db_ncheque').value;
					clearForm('form_tesoreria_db_chequeras');
					/*getObj('tesoreria_chequeras_cuenta_db_ncheque').value=valor;
					getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=valor;
					getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=valor;
					*/
					getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
					getObj('tesoreria_chequeras_db_estatus').value="1";	
					getObj('tesoreria_chequeras_primer_cheque').value="000000"
					getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(html);
 					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					
								}
				 else if (html=="chequera_activa")
				{
						setBarraEstado("");
					  	setBarraEstado(mensaje[chequera_existe],true,true);
						getObj('tesoreria_chequeras_db_estatus_opt_act').checked="";
						getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="checked";
						getObj('tesoreria_chequeras_db_estatus').value="2";	
				}
				else
					if (html=="cerrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
					}	
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#tesoreria_chequeras_db_btn_guardar").click(function() {
	if($('#form_tesoreria_db_chequeras').jVal)
	{
				if((getObj('tesoreria_chequeras_primer_cheque').value!='000000')&&(getObj('tesoreria_chequeras_ultimo_emitido').value!='000000')&&(getObj('tesoreria_chequeras_primer_cheque').value==getObj('tesoreria_chequeras_ultimo_emitido').value))
				{
					setBarraEstado(mensaje[esperando_respuesta]);
					$.ajax (
					{
						url: "modulos/tesoreria/chequeras/db/sql.registrar.php",
						data:dataForm('form_tesoreria_db_chequeras'),
						type:'POST',
						cache: false,
						success: function(html)
						{
							if (html=="Registrado")
							{
								setBarraEstado(mensaje[registro_exitoso],true,true);
								/*vale=getObj('tesoreria_chequeras_cuenta_db_ncheque').value;
								vale1= parseInt(vale);
								vale1=vale1+1;
								*/clearForm('form_tesoreria_db_chequeras');
								getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
								getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
								getObj('tesoreria_chequeras_db_estatus').value="1";	
								/*getObj('tesoreria_chequeras_cuenta_db_ncheque').value=vale1;
								getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=vale1;
								getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=vale1;
								*/getObj('tesoreria_chequeras_primer_cheque').value="000000"
								getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
							}
							else if (html=="NoRegistro")
							{
									setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
									valor=getObj('tesoreria_chequeras_cuenta_db_ncheque').value;
									clearForm(form_tesoreria_db_chequeras);
									getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
									getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
									getObj('tesoreria_chequeras_db_estatus').value="1";	
									getObj('tesoreria_chequeras_cuenta_db_ncheque').value=valor;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=valor;
									getObj('tesoreria_chequeras_primer_cheque').value="000000"
									getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
								}
							 else if (html=="chequera_activa")
							{
									 setBarraEstado("");
  								  	setBarraEstado(mensaje[chequera_existe],true,true);
									clearForm('form_tesoreria_db_chequeras');
									getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
									getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
									getObj('tesoreria_chequeras_db_estatus').value="1";	
									/*getObj('tesoreria_chequeras_cuenta_db_ncheque').value=vale1;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=vale1;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=vale1;
									*/getObj('tesoreria_chequeras_primer_cheque').value="000000"
									getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
									/*getObj('tesoreria_chequeras_db_estatus_opt_act').checked="";
									getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="checked";
									getObj('tesoreria_chequeras_db_estatus').value="2";	*/
							}
								else
								if (html=="cerrado")
								{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
								}
							else
							{
								
								setBarraEstado(html);
								//getObj('tesoreria_banco_db_direccion').value=html;
								//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
							}
						
						}
					});
				}
	}			
});


$("#tesoreria_chequeras_db_btn_cancelar").click(function() {

	setBarraEstado("");
    //getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
	getObj('tesoreria_chequeras_db_btn_actualizar').style.display='none';
	getObj('tesoreria_chequeras_db_estatus_opt_act').disabled=false		
	getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled=false		
	getObj('tesoreria_chequeras_db_btn_guardar').style.display='';
	getObj('tesoreria_chequeras_db_btn_consultar').style.display='';
	getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
	getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
	getObj('tesoreria_chequeras_db_estatus_opt_act').disabled=false;		
	getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled=false;
	//valr=getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value;
	clearForm('form_tesoreria_db_chequeras');
	getObj('tesoreria_chequeras_db_estatus').value="1";	
	getObj('tesoreria_chequeras_cantidad_cheques').disabled="";
	getObj('tesoreria_chequeras_primer_cheque').disabled="";
	getObj('tesoreria_chequeras_cuenta_db_ncheque').value="";
	getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value="";
	getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value="";
	getObj('tesoreria_chequeras_primer_cheque').value="000000"
	getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
	
});


//consultas automaticas

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_banco_chequeras_codigo()
{
	if ((getObj('tesoreria_chequeras_cuenta_db_n_cuenta').value!="")&&(getObj('tesoreria_chequeras_cuenta_id_banco').value!=""))
    {
			$.ajax({
					url:"modulos/tesoreria/chequeras/db/sql_grid_chequeras_codigo.php",
					data:dataForm('form_tesoreria_db_chequeras'),
					type:'POST',
					cache: false,
					 success:function(html)
					 {//alert(html);
						if((html!="")||(html!=null))
						{		var recordset=html;
								if(recordset)
								{
									recordset = recordset.split("*");
									getObj('tesoreria_chequeras_db_estatus_opt_act').disabled="";		
									getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled="";
									getObj('tesoreria_chequeras_db_id').value=recordset[0];
									getObj('tesoreria_chequeras_cuenta_id_banco').value=recordset[2];
									getObj('tesoreria_chequeras_cuenta_db_nombre').value=recordset[3];
									getObj('tesoreria_chequeras_cuenta_db_n_cuenta').value=recordset[4];
									getObj('tesoreria_chequeras_primer_cheque').value=recordset[6];
									//
									numero_entero=recordset[7];
									getObj('tesoreria_chequeras_ultimo_emitido').value=numero_entero;
									//
									getObj('tesoreria_chequeras_cantidad_cheques').value=recordset[8];
									getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value=recordset[9];	
									getObj('tesoreria_chequeras_db_comentarios').value=recordset[11];
									getObj('tesoreria_chequeras_cuenta_db_ncheque').value=recordset[5];
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=recordset[5];
									getObj('tesoreria_chequeras_db_btn_cancelar').style.display='';
									getObj('tesoreria_chequeras_db_btn_actualizar').style.display='';
									getObj('tesoreria_chequeras_db_btn_guardar').style.display='none';	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled=true;
									getObj('tesoreria_chequeras_primer_cheque').disabled=true;
									ultimo_cheque_emitido_consultado();
										if(recordset[10]=='Activo')
										{ 
											getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
											getObj('tesoreria_chequeras_db_estatus').value="1";
										}else
										if(recordset[10]=='Inactivo')
										{
											getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="checked";
											getObj('tesoreria_chequeras_db_estatus').value="2";
												}	
										else
										if(recordset[10]=='Agotada')
										{
										getObj('tesoreria_chequeras_db_estatus_opt_agotado').checked="checked";
										getObj('tesoreria_chequeras_db_estatus').value="3";
										getObj('tesoreria_chequeras_db_estatus_opt_act').disabled="true";		
										getObj('tesoreria_chequeras_db_estatus_opt_inact').disabled="true";
								
	
										}		
									
									
								}
								 else
								 {
									setBarraEstado("");
									//getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
									getObj('tesoreria_chequeras_db_btn_actualizar').style.display='none';
									getObj('tesoreria_chequeras_db_btn_guardar').style.display='';
									getObj('tesoreria_chequeras_db_btn_consultar').style.display='';
									getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
									getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
									valr=getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value;
									getObj('tesoreria_chequeras_primer_cheque').value="";
									getObj('tesoreria_chequeras_ultimo_emitido').value="";
									getObj('tesoreria_chequeras_cantidad_cheques').value="";
									getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value="";	
									getObj('tesoreria_chequeras_db_comentarios').value="";
									getObj('tesoreria_chequeras_cantidad_cheques_faltantes').value="";
									//getObj('tesoreria_chequeras_cuenta_db_ncheque').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=valr;
									getObj('tesoreria_chequeras_db_btn_cancelar').style.display='';
									//getObj('tesoreria_chequeras_db_btn_guardar').style.display='';	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled=true;
								//	getObj('tesoreria_chequeras_primer_cheque').disabled=true;
									//clearForm('form_tesoreria_db_chequeras');
									getObj('tesoreria_chequeras_db_estatus').value="1";	
									getObj('tesoreria_chequeras_cantidad_cheques').disabled="";
									getObj('tesoreria_chequeras_primer_cheque').disabled="";
									getObj('tesoreria_chequeras_primer_cheque').value="000000"
									getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
									/*getObj('tesoreria_chequeras_cuenta_db_ncheque').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=valr;
									getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=valr;*/
									}
						}	
					 }
				});	 	 
		}	
}//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
function ultimo_cheque_emitido()
	{
					getObj('tesoreria_chequeras_ultimo_emitido').value=getObj('tesoreria_chequeras_primer_cheque').value;
					valor=getObj('tesoreria_chequeras_ultimo_emitido').value;
						valor1= parseInt(valor);//ULTIMO EMITIDO
					valorb=getObj('tesoreria_chequeras_primer_cheque').value;	
						valor2= parseInt(valorb);
					if((getObj('tesoreria_chequeras_primer_cheque').value!="")&&(getObj('tesoreria_chequeras_cantidad_cheques').value!=""))	
					{
					getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value=valor1-valor2;
					cantidad=getObj('tesoreria_chequeras_cantidad_cheques').value;
					cantidad2=	parseInt(cantidad);//CANTIDAD
						valor3=getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value
						valorb=parseInt(valor3);	//EMITIDOS
					cantidad_falta=cantidad2-valorb;
					getObj('tesoreria_chequeras_cantidad_cheques_faltantes').value=cantidad_falta;
					//- validaciones
						/*if(cantidad2<valor2)
							{
								alert("El número de primer cheque no puede ser mayor  a su cantidad")
								getObj('tesoreria_chequeras_primer_cheque').value="";
							}*/
						if (valorb>cantidad2)
							{
								alert("La cantidad de cheques emitidos no puede ser mayor a la cantidad señalada inicialmente")
								getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value="";
							}
					/*	if(valor1>cantidad2)
							{
								//alert("El número del ultimo cheque no puede ser mayor a la cantidad señalada inicialmente")
								getObj('tesoreria_chequeras_ultimo_emitido').value="";
						
							}*/
						 if(cantidad_falta==0)
						 	{
								alert("Chequera en estatus Agotado");
								getObj('tesoreria_chequeras_db_estatus_opt_act').checked="";
								getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
								getObj('tesoreria_chequeras_db_estatus_opt_agotado').checked="checked";
								getObj('tesoreria_chequeras_db_estatus').value="3";
							}
					}
	}
	//
function ultimo_cheque_emitido_consultado()
	{
	//	en el caso que la cheuqera se le hayan acabdo los cheques
		if(getObj('tesoreria_chequeras_ultimo_emitido').value=="000000")
		{
		 	getObj('tesoreria_chequeras_cantidad_cheques_faltantes').value="0";
			getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value=getObj('tesoreria_chequeras_cantidad_cheques').value;
			//alert("Chequera en estatus Agotado");
			getObj('tesoreria_chequeras_db_estatus_opt_act').checked="";
			getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
			getObj('tesoreria_chequeras_db_estatus_opt_agotado').checked="checked";
			getObj('tesoreria_chequeras_db_estatus').value="3";
			//setBarraEstado(mensaje[chequera_agotada],true,true);
}
			else
		{
					valor=getObj('tesoreria_chequeras_ultimo_emitido').value;
						//valor1= parseInt(valor);//ULTIMO EMITIDO
						
					valorb=getObj('tesoreria_chequeras_primer_cheque').value;	
						//valor2= parseInt(valorb);
						
					if((getObj('tesoreria_chequeras_primer_cheque').value!="")||(getObj('tesoreria_chequeras_primer_cheque').value!=""))	
					{
					getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value=valor-valorb;
					cantidad=getObj('tesoreria_chequeras_cantidad_cheques').value;
					cantidad2=	parseInt(cantidad);//CANTIDAD
						valor3=getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value
						valorb=parseInt(valor3);	//EMITIDOS
					cantidad_falta=cantidad2-valorb;
					getObj('tesoreria_chequeras_cantidad_cheques_faltantes').value=cantidad_falta;
					//- validaciones
						if (valorb>cantidad2)
							{
								alert("La cantidad de cheques emitidos no puede ser mayor a la cantidad señalada inicialmente")
								getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value="";
							}
						/*if(valor1>cantidad2)
							{
								//alert("El número del ultimo cheque no puede ser mayor a la cantidad señalada inicialmente")
								getObj('tesoreria_chequeras_ultimo_emitido').value="";
						
							}*/
						 if(cantidad_falta==0)
						 	{
								alert("Chequera en estatus Agotada");
								getObj('tesoreria_chequeras_db_estatus_opt_act').checked="";
								getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
								getObj('tesoreria_chequeras_db_estatus_opt_agotado').checked="checked";
								getObj('tesoreria_chequeras_db_estatus').value="3";
							}
					}
		}
	}
function valida_codigo()
{
	if((isNaN(getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value)==true)||(getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value==""))
	{
		setBarraEstado("");
		//getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value="";
		getObj('tesoreria_chequeras_db_btn_guardar').style.display='';
		getObj('tesoreria_chequeras_db_btn_consultar').style.display='';
		getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
		getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="";
		valr=getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value;
		getObj('tesoreria_chequeras_primer_cheque').value="";
		getObj('tesoreria_chequeras_ultimo_emitido').value="";
		getObj('tesoreria_chequeras_cantidad_cheques').value="";
		getObj('tesoreria_chequeras_cantidad_cheques_emitidos').value="";	
		getObj('tesoreria_chequeras_db_comentarios').value="";
		getObj('tesoreria_chequeras_cantidad_cheques_faltantes').value="";
		getObj('tesoreria_chequeras_cuenta_db_ncheque_codigo').value=valr;
		getObj('tesoreria_chequeras_cuenta_db_ncheque_oculto').value=valr;
		getObj('tesoreria_chequeras_db_btn_cancelar').style.display='';
		getObj('tesoreria_chequeras_cantidad_cheques').disabled=true;
		getObj('tesoreria_chequeras_db_estatus').value="1";	
		getObj('tesoreria_chequeras_cantidad_cheques').disabled="";
		getObj('tesoreria_chequeras_primer_cheque').disabled="";
		getObj('tesoreria_chequeras_primer_cheque').value="000000"
		getObj('tesoreria_chequeras_ultimo_emitido').value="000000"
								
	}
	else
		consulta_automatica_banco_chequeras_codigo();
}	
function valida_cantidad()
{
	getObj('tesoreria_chequeras_cantidad_cheques').value=parseInt(getObj('tesoreria_chequeras_cantidad_cheques').value);
}		
	
$("#tesoreria_chequeras_db_estatus_opt_act").click(function(){
		getObj('tesoreria_chequeras_db_estatus').value="1";
		
	});
$("#tesoreria_chequeras_db_estatus_opt_inact").click(function(){
		getObj('tesoreria_chequeras_db_estatus').value="2";
	});
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#tesoreria_chequeras_cuenta_db_n_cuenta').numeric({});
$('#tesoreria_chequeras_cuenta_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ-. '});
$('tesoreria_chequeras_cuenta_db_ncheque_codigo').numeric({});
$('#tesoreria_chequeras_cantidad_cheques').numeric({allow:''});
$('#tesoreria_chequeras_primer_cheque').numeric({allow:''});
$('#tesoreria_chequeras_ultimo_emitido').numeric({allow:''});
$('#tesoreria_chequeras_cantidad_cheques_emitidos').numeric({allow:''});
$('#tesoreria_chequeras_cantidad_cheques_faltantes').numeric({allow:''});
$('#tesoreria_chequeras_primer_cheque').blur(ultimo_cheque_emitido)
$('#tesoreria_chequeras_cantidad_cheques').blur(ultimo_cheque_emitido)
$('#tesoreria_chequeras_cuenta_db_ncheque_codigo').change(valida_codigo);
$('#tesoreria_chequeras_cantidad_cheques').change(valida_cantidad);

//$('#tesoreria_chequeras_cuenta_db_ncheque_codigo').change(consulta_automatica_banco_chequeras_codigo);

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

<div id="botonera"><img id="tesoreria_chequeras_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="tesoreria_chequeras_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
   	<img id="tesoreria_chequeras_db_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
	 <img id="tesoreria_chequeras_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tesoreria_chequeras_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
	</div>
<form method="post" id="form_tesoreria_db_chequeras" name="form_tesoreria_db_chequeras">
  <input type="hidden"  id="tesoreria_chequeras_db_id" name="tesoreria_chequeras_db_id"/>
  <table   class="cuerpo_formulario">
  
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar Chequera
	  <input type="hidden" name="tesoreria_chequeras_cuenta_db_ncheque" id="tesoreria_chequeras_cuenta_db_ncheque"  value="<?= $ncheque ?>" readonly size="6" /></th>
	</tr>
	  <th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_chequeras_cuenta_db_nombre" type="text" id="tesoreria_chequeras_cuenta_db_nombre"   value="" size="50" maxlength="30" 
						message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ- ,.-.]{1,30}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ- ,.-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
			    	<input type="hidden"  id="tesoreria_chequeras_cuenta_id_banco" name="tesoreria_chequeras_cuenta_id_banco"/>
		</li>
		<li id="tesoreria_db_btn_consultar_banco_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_chequeras_cuenta_db_n_cuenta" type="text" id="tesoreria_chequeras_cuenta_db_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. "  readonly
				jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de Cuenta Invalido', styleType:'cover'}"
				jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		</li>
		<li id="tesoreria_db_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	<tr>    </tr>
	<tr>
	<th>N&uacute;mero de chequera:</th> 
		 
		  <td><input type="text" name="tesoreria_chequeras_cuenta_db_ncheque_codigo" id="tesoreria_chequeras_cuenta_db_ncheque_codigo"  value="" size="6"  maxlength="6"  onchange="valida_codigo" message="Introduzca el Número de chequera "
            jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero de chequera Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
	
		  <input type="hidden"  id="tesoreria_chequeras_cuenta_db_ncheque_oculto" name="tesoreria_chequeras_cuenta_db_ncheque_oculto"/></td></th>
	</tr>
			
	 <tr>
		 <th>Cantidad de Cheques:</th>	
	     <td>	
		 <input name="tesoreria_chequeras_cantidad_cheques" type="text" id="tesoreria_chequeras_cantidad_cheques"   value="" size="6" maxlength="6"  message="Introduzca Cantidad de Cheques. " 
			jVal="{valid:/^[0123456789]{1,6}$/, message:'Cantidad Invalida', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/></td>
	</tr>
	 <tr>
	 <th>Pimer cheque:</th>	
	    <td>	
		<input name="tesoreria_chequeras_primer_cheque" type="text" id="tesoreria_chequeras_primer_cheque"   size="6" value="000000" maxlength="6"  alt="signed-dec"   message="Introduzca el Número del primer cheque. " 
		  style="text-align:right"
		  jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero de Primer Cheque Invalido', styleType:'cover'}"
		  jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		</td>
	 </tr>
	 <tr>
	 <th>Proximo a  emitir:</th>	
	    <td>	
		<input name="tesoreria_chequeras_ultimo_emitido" type="text" id="tesoreria_chequeras_ultimo_emitido"  size="6" value="000000" maxlength="6"  alt="signed-dec"    message="Introduzca el N&uacute;mero deL  &uacute;ltimo cheque. "  readonly
			jVal="{valid:/^[0123456789]{1,7}$/, message:'N&uacute;mero Proximo cheque Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/></td>
	 </tr>
	 <tr>
		 <th>Cantidad de Emitidos:</th>	
	     <td>	
		<input name="tesoreria_chequeras_cantidad_cheques_emitidos" type="text" id="tesoreria_chequeras_cantidad_cheques_emitidos"   value="" size="6" maxlength="6" message="Introduzca cantidad de cheques emitidos."  readonly
			jVal="{valid:/^[0123456789]{1,6}$/, message:'Cantidad de emitidos Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/></td>
	</tr>
	<tr>
		 <th>Cantidad Restante:</th>	
	     <td>	
		
		<input name="tesoreria_chequeras_cantidad_cheques_faltantes" type="text" id="tesoreria_chequeras_cantidad_cheques_faltantes"   value="" size="6" maxlength="6" message="Introduzca cantidad de cheques que posee la chequera. "  readonly
			jVal="{valid:/^[0123456789]{1,6}$/, message:'Cantidad restante Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/></td>
	</tr>
	 <tr>
		<th>Comentarios:</th>
		<td><textarea  name="tesoreria_chequeras_db_comentarios" cols="60" id="tesoreria_chequeras_db_comentarios" message="Introduzca un comentario."
				></textarea>			
		</td>
	</tr>
		<tr> 
		<th>Estatus:</th>
		<td>
		   	<input id="tesoreria_chequeras_db_estatus_opt_act" name="tesoreria_chequeras_db_estatus_opt"  type="radio" value="1" checked="checked" />Activo
	      	<input id="tesoreria_chequeras_db_estatus_opt_inact" name="tesoreria_chequeras_db_estatus_opt"  type="radio" value="2" />Inactivo
   	      	<input id="tesoreria_chequeras_db_estatus_opt_agotado" name="tesoreria_chequeras_db_estatus_opt"  type="radio" value="3"   disabled="disabled"/>Agotado
		  <input type="hidden" id="tesoreria_chequeras_db_estatus" name="tesoreria_chequeras_db_estatus"  value="1" /></td>
	  </tr>
	  <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
   	 </tr>
</table>
<input  name="tesoreria_chequeras_db_id2" type="hidden" id="" />
</form>