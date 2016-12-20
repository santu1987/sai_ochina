<? if (!$_SESSION) session_start();
?>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM paises ORDER BY nombre";
$rs_nivel =& $conn->Execute($sql);
while (!$rs_nivel->EOF){
	$opt_pais.="<option value='".$rs_nivel->fields("codigo")."' >".$rs_nivel->fields("nombre")."</option>";
	$rs_nivel->MoveNext();
} 
$sql="SELECT *FROM estado ORDER BY nom_es";
$rs_estado =& $conn->Execute($sql);
while (!$rs_estado->EOF){
	$opt_estado.="<option value='".$rs_estado->fields("id_es")."' >".$rs_estado->fields("nom_es")."</option>";
	$rs_estado->MoveNext();
} 
?>
<script>
$(document).ready(function(){
	// Parametros para e combo1
   $("#trabajador_db_estado_ubica").change(function () {
   		$("#trabajador_db_estado_ubica option:selected").each(function () {
			//alert($(this).val());
				elegido=$(this).val();
				$.post("modulos/rrhh/trabajador/db/sql.combo.php", { elegido: elegido }, function(data){
				$("#trabajador_db_municipio_ubica").html(data);
			});			
        });
   })
});
//----------------- funcion para crear las pestañas de trabajador ---------------------////
$(function() {
    $('#pestana_tra').tabs();
 });
////------------------------ fin de funcion crear pestañas trabajador ----------------/////
var dialog;
//----------------------------------------------------------------------------------------------------
$("#trabajador_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/trabajador/db/vista.grid_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajadores', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#trabajador_db_cedula_grid_t").val(); 
					var busq_cedula= jQuery("#trabajador_db_cedula_grid_t").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql_trabajador_nombre.php?busq_nombre="+busq_nombre+"&busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#trabajador_db_cedula_grid_t").keypress(function(ey)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#trabajador_db_nombre_grid_t").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_cedula= jQuery("#trabajador_db_cedula_grid_t").val();
							var busq_nombre= jQuery("#trabajador_db_nombre_grid_t").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql_trabajador_nombre.php?busq_nombre="+busq_nombre+"&busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/trabajador/db/sql_trabajador_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','direccion','email','telefono','cel','fenaci','lunaci','asignaciones','id_persona','Cedula','Apellidos','Nombres','estado_civil','sexo','observaciones','id_cargos','descripcion','id_entrevista','cedu_entre','nom_entre','id_unidad','Unidad','cod_pais','pais','estado','id_mn','nom_mn','','Nomina','Fecha Ingreso','anos servicios'],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador', width:50,hidden:true},
									{name:'direccion',index:'direccion',hidden:true},
									{name:'email',index:'email',hidden:true},
									{name:'telefono',index:'telefono',hidden:true},
									{name:'cel',index:'cel',hidden:true},
									{name:'fenaci',index:'fenaci',hidden:true},
									{name:'lunaci',index:'lunaci',hidden:true},
									{name:'asignaciones',index:'asignaciones',hidden:true},
									{name:'id_persona',index:'id_persona',hidden:true},
									{name:'cedula',index:'cedula',width:50},
									{name:'apellido',index:'apellido',width:80},
									{name:'nombre',index:'nombre',width:80},
									{name:'estado_civil',index:'estado_civil',hidden:true},
									{name:'sexo',index:'sexo',hidden:true},
									{name:'observaciones',index:'observaciones',hidden:true},
									{name:'id_cargos',index:'id_cargos',hidden:true},
									{name:'descripcion',index:'descripcion',hidden:true},
									{name:'id_entrevista',index:'id_entrevista',hidden:true},
									{name:'cedu_entre',index:'cedu_entre',hidden:true},
									{name:'nom_entre',index:'nom_entre',hidden:true},
									{name:'id_unidad_eje',index:'id_unidad_eje',hidden:true},
									{name:'unidad',index:'unidad',width:150},
									{name:'cod_pais',index:'cod_pais',hidden:true},
									{name:'pais',index:'pais',hidden:true},
									{name:'estado',index:'estado',hidden:true},
									{name:'id_mn',index:'id_mn',hidden:true},
									{name:'nom_mn',index:'nom_mn', hidden:true},
									{name:'id_tipo_nomina',index:'id_tipo_nomina',hidden:true},
									{name:'nomina',index:'nomina', width:80},
									{name:'fechain',index:'fechain', hidden:true},
									{name:'anos_servicios',index:'anos_servicios', hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('trabajador_db_pais_nac').style.display='none';
									getObj('trabajador_db_pais_nac2').style.display='';
									getObj('trabajador_db_estado_ubica2').style.display='';
									getObj('trabajador_db_estado_ubica').style.display='none';
									getObj('trabajador_db_municipio_ubica2').style.display='';
									getObj('trabajador_db_municipio_ubica').style.display='none';
									// aqui todos los valores para los campos
									var ci=ret.cedula;
									var sex=ret.sexo;
									var nlidad=ci.substr(0,2);
									var cedula=ci.substr(2,12);
									if(nlidad=="V-"){
										getObj('trabajador_db_nacionalidad').selectedIndex=0;
									}
									if(nlidad=="E-"){
										getObj('trabajador_db_nacionalidad').selectedIndex=1;
									}
									if(nlidad=="P-"){
										getObj('trabajador_db_nacionalidad').selectedIndex=2;
									}
									getObj('trabajador_db_cedula').value=cedula;
									getObj('trabajador_db_nombre').value = ret.nombre;
									getObj('trabajador_db_apellido').value = ret.apellido;
									if(sex=="M"){
										getObj('trabajador_db_sexo').selectedIndex=0;
									}
									else{
										getObj('trabajador_db_sexo').selectedIndex=1;
									}
									if(ret.estado_civil==1){
										getObj('trabajador_db_estado_civil').selectedIndex=1;
									}
									if(ret.estado_civil==2){
										getObj('trabajador_db_estado_civil').selectedIndex=2;
									}
									if(ret.estado_civil==3){
										getObj('trabajador_db_estado_civil').selectedIndex=3;
									}
									if(ret.estado_civil==4){
										getObj('trabajador_db_estado_civil').selectedIndex=4;
									}
									if(ret.estado_civil==5){
										getObj('trabajador_db_estado_civil').selectedIndex=5;
									}
									getObj('trabajador_db_pais_nac2').value=ret.pais;
									getObj('trabajador_db_id_pais').value=ret.cod_pais;
									getObj('trabajador_db_fecha_nac').value=ret.fenaci;
									getObj('trabajador_db_lugar_nac').value=ret.lunaci;
									getObj('trabajador_db_estado_ubica2').value=ret.estado;
									getObj('trabajador_db_municipio_ubica2').value=ret.nom_mn;
									getObj('trabajador_db_id_municipio').value=ret.id_mn;
									getObj('trabajador_db_direccion').value=ret.direccion;
									getObj('trabajador_db_fecha_ingreso').value=ret.fechain;
									var tel= ret.telefono;
									var area_t= tel.substr(0,3);
									tel= tel.substr(4,14);
									var cel= ret.cel;
									var area_c= cel.substr(0,3);
									cel= cel.substr(4,14);
									getObj('trabajador_db_area_telef').value=area_t;
									getObj('trabajador_db_numero_telef').value=tel;
									getObj('trabajador_db_area_cel').value=area_c;
									getObj('trabajador_db_numero_cel').value=cel;
									getObj('trabajador_db_email').value=ret.email;
									getObj('trabajador_db_observacion').value=ret.observaciones;
									var entrevista=ret.cedu_entre+" "+ret.nom_entre;
									getObj('trabajador_db_entrevista').value=entrevista;
									getObj('trabajador_bd_id_entrevista').value=ret.id_entrevista;
									getObj('trabajador_db_cargo').value=ret.descripcion;
									getObj('trabajador_db_id_cargo').value=ret.id_cargos;
									getObj('trabajador_db_area').value=ret.unidad;
									getObj('trabajador_db_id_area').value=ret.id_unidad_eje;
									getObj('trabajador_db_asignacion').value=ret.asignaciones;
									getObj('trabajador_db_id_persona').value=ret.id_persona;
									getObj('trabajador_bd_id_tipo_nomina').value=ret.id_tipo_nomina;
									getObj('trabajador_db_tipo_nomina').value=ret.nomina;
									getObj('trabajador_db_anos_servicios').value=ret.anos_servicios;
									getObj('trabajador_db_btn_actualizar').style.display = '';
									getObj('trabajador_db_btn_eliminar').style.display = '';
									getObj('trabajador_db_btn_guardar').style.display = 'none';
									///-----------------------------------
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#trabajador_db_nombre_grid_t').alpha({allow:' '});
								$('#trabajador_db_cedula_grid_t').numeric({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_trabajador',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//----------------------------------------------------------------
$("#trabajador_db_btn_guardar").click(function() {
	var val = 0;										   
	if(getObj('trabajador_db_email').value!=''){
		val = validar_correo();
	}
	if(val==0){
	if ($('#form_db_trabajador').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/trabajador/db/sql.registrar_trabajador.php",
			data:dataForm('form_db_trabajador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_campos();
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
	}
	if(val==1){
		setBarraEstado('<div id="mensaje"><p><img align="absmiddle" src="imagenes/iconos/folder_important.png" />El Correo Electronico "'+getObj('trabajador_db_email').value+'" Es Invalido</p></div>',true,true);
	}
});

//----------------------------------------------------------------
//-----------------------Actualizar-------------------------------
$("#trabajador_db_btn_actualizar").click(function() {
	if ($('#form_db_trabajador').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/trabajador/db/sql.actualizar_trabajador.php",
			data:dataForm('form_db_trabajador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar_campos();
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});

//
//----------------------------------------------------------------
$("#trabajador_db_btn_eliminar").click(function() {
	if ($('#form_db_trabajador').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/trabajador/db/sql.eliminar_trabajador.php",
			data:dataForm('form_db_trabajador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					limpiar_campos();
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Relacion_Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});
//------------- funcion de consulta emergente entrevista---------------
$("#entrevista_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/trabajador/db/vista.grid_entrevista.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Entrevistas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#trabajador_db_nombre_grid").val(); 
					var busq_cedula= jQuery("#trabajador_db_cedula_grid").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_entrevista.php?busq_nombre="+busq_nombre+"&busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#trabajador_db_nombre_grid").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#trabajador_db_cedula_grid").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#trabajador_db_nombre_grid").val();
							var busq_cedula= jQuery("#trabajador_db_cedula_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_entrevista.php?busq_nombre="+busq_nombre+"&busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/trabajador/db/sql.consulta_entrevista.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Observaciones'],
								colModel:[
									{name:'id_entrevista',index:'id_entrevista', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'trabajador',index:'trabajador', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:150}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('trabajador_bd_id_entrevista').value=ret.id_entrevista;
									var entre= ret.cedula+" "+ret.trabajador;
									getObj('trabajador_db_entrevista').value=entre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#trabajador_db_nombre_grid').alpha({allow:' '});
								$('#trabajador_db_cedula_grid').numeric({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_entrevista',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

// ******************************************************************************
//------------- funcion de consulta emergente area---------------
$("#cargo_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/trabajador/db/vista.grid_cargo.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Cargos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_cargo= jQuery("#trabajador_db_cargo_grid").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_cargo.php?busq_cargo="+busq_cargo,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#trabajador_db_cargo_grid").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_cargo= jQuery("#trabajador_db_cargo_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_cargo.php?busq_cargo="+busq_cargo,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/trabajador/db/sql.consulta_cargo.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cargo','Descripcion'],
								colModel:[
									{name:'id_cargo',index:'id_cargo',hidden:true},
									{name:'cargo',index:'cargo', width:150},
									{name:'obser',index:'obser', width:200}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('trabajador_db_id_cargo').value=ret.id_cargo;
									getObj('trabajador_db_cargo').value=ret.cargo;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#trabajador_db_cargo_grid").focus();
								$('#trabajador_db_cargo_grid').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_cargos',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

// ******************************************************************************
//------------- funcion de consulta emergente area---------------
$("#area_trabajo_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/trabajador/db/vista.grid_area.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Area de Trabajo', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#trabajador_db_area_grid").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_area.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#trabajador_db_area_grid").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#trabajador_db_area_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_area.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/trabajador/db/sql.consulta_area.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Area de Trabajo'],
								colModel:[
									{name:'id_unidad',index:'id_unidad',hidden:true},
									{name:'area',index:'area', width:200}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('trabajador_db_id_area').value=ret.id_unidad;
									getObj('trabajador_db_area').value=ret.area;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#trabajador_db_area_grid").focus();
								$('#trabajador_db_area_grid').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

// ******************************************************************************
//------------- funcion de consulta emergente entrevista---------------
$("#tipo_nomina_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/trabajador/db/vista.grid_tipo_nomina.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Tipo de Nominas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#trabajador_db_tipo_nomina_grid").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_tipo_nomina.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#trabajador_db_tipo_nomina_grid").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#trabajador_db_tipo_nomina_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/trabajador/db/sql.consulta_tipo_nomina.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/trabajador/db/sql.consulta_tipo_nomina.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo Nomina','Observacion'],
								colModel:[
									{name:'id_tipo_nomina',index:'id_tipo_nomina', width:50,sortable:false,resizable:false,hidden:true},
									{name:'tipo_nomina',index:'tipo_nomina', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:150}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('trabajador_bd_id_tipo_nomina').value=ret.id_tipo_nomina;
									getObj('trabajador_db_tipo_nomina').value=ret.tipo_nomina;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#trabajador_db_tipo_nomina_grid').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_nomina',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

// ******************************************************************************
function mover_tele(){
	var valor= document.getElementById('trabajador_db_area_telef').value;
	valor= valor.length;
	if(valor==2){
		//alert('jjj');
		getObj('trabajador_db_numero_telef').focus();
	} 
}
function mover_cel(){
	var valor= document.getElementById('trabajador_db_area_cel').value;
	valor= valor.length;
	if(valor==2){
		//alert('jjj');
		getObj('trabajador_db_numero_cel').focus();
	} 
}
function limpiar_campos(){
	getObj('trabajador_db_nacionalidad').selectedIndex=0;
	getObj('trabajador_db_cedula').value='';
	getObj('trabajador_db_nombre').value='';
	getObj('trabajador_db_id_trabajador').value='';
	getObj('trabajador_db_apellido').value='';
	getObj('trabajador_db_sexo').selectedIndex=0;
	getObj('trabajador_db_estado_civil').selectedIndex=0;
	getObj('trabajador_db_pais_nac').selectedIndex=0;
	getObj('trabajador_db_id_pais').value='';
	getObj('trabajador_db_fecha_nac').value='';
	getObj('trabajador_db_fecha_ingreso').value='';
	getObj('trabajador_db_lugar_nac').value='';
	getObj('trabajador_db_estado_ubica').selectedIndex=0;
	getObj('trabajador_db_id_estado').value='';
	getObj('trabajador_db_municipio_ubica').selectedIndex=0;
	getObj('trabajador_db_id_municipio').value='';
	getObj('trabajador_db_direccion').value='';
	getObj('trabajador_db_area_telef').value='';
	getObj('trabajador_db_numero_telef').value='';
	getObj('trabajador_db_area_cel').value='';
	getObj('trabajador_db_numero_cel').value='';
	getObj('trabajador_db_email').value='';
	getObj('trabajador_db_entrevista').value='';
	getObj('trabajador_bd_id_entrevista').value='';
	getObj('trabajador_db_observacion').value='';
	getObj('trabajador_db_cargo').value='';
	getObj('trabajador_db_id_cargo').value='';
	getObj('trabajador_db_area').value='';
	getObj('trabajador_db_id_area').value='';
	getObj('trabajador_db_asignacion').value=''; 
	getObj('trabajador_db_pais_nac').style.display = '';
	getObj('trabajador_db_pais_nac2').style.display = 'none';
	getObj('trabajador_db_estado_ubica').style.display = '';
	getObj('trabajador_db_estado_ubica2').style.display = 'none';
	getObj('trabajador_db_municipio_ubica').style.display = '';
	getObj('trabajador_db_municipio_ubica2').style.display = 'none';
	getObj('trabajador_db_btn_actualizar').style.display = 'none';
	getObj('trabajador_db_btn_eliminar').style.display = 'none';
	getObj('trabajador_db_btn_guardar').style.display = '';	
	getObj('trabajador_bd_id_tipo_nomina').value='';
	getObj('trabajador_db_tipo_nomina').value='';
	getObj('trabajador_db_anos_servicios').value='';
}
//
//
function validar_correo(){
	var correo = getObj('trabajador_db_email').value;
	var tam = correo.length;
	var pos_arroba1 = correo.indexOf('@');
	var pos_arroba2 = correo.lastIndexOf('@');
	var err = 0;
	if(pos_arroba1!=pos_arroba2)
		err = 1;
	if(pos_arroba1==-1)
		err = 1;
	if(pos_arroba1==pos_arroba2){
		var cad = correo.substr(pos_arroba1+1,tam);
		if(cad.indexOf('-')!=-1 || cad.indexOf('_')!=-1)
			err = 1;
		for(i=0; i<=9; i++){
			if(cad.indexOf(i)!=-1)
				err = 1;
		}
		tam = cad.length;
		var pos_punto1 = cad.indexOf('.');
		var pos_punto2 = cad.lastIndexOf('.');
		if(pos_punto1!=pos_punto2)
			err = 1;
		if(pos_punto1==-1)
			err = 1;
		if(pos_punto1==pos_punto2){
			cad = cad.substr(pos_punto1+1,tam);
			tam = cad.length;
			
			if(tam<=2 || tam>=4)
				err = 1;
		}

	}	
	return err;
}
//
//

$("#trabajador_db_btn_cancelar").click(function() {
limpiar_campos();
setBarraEstado("");
});
//-------------------------------------------------
$('#trabajador_db_cedula').numeric({allow:' '});
$('#trabajador_db_cedula_grid').numeric({allow:' '});
$('#trabajador_db_area_telef').numeric({allow:' '});
$('#trabajador_db_numero_telef').numeric({allow:' '});
$('#trabajador_db_area_cel').numeric({allow:' '});
$('#trabajador_db_numero_cel').numeric({allow:' '});
$('#trabajador_db_nombre').alpha({allow:' '});
$('#trabajador_db_email').alphanumeric({allow:'-@.'});
$('#trabajador_db_apellido').alpha({allow:' '});
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
//
$("#trabajador_db_pais_nac2").focus(function() {
	getObj('trabajador_db_pais_nac2').style.display='none';
	getObj('trabajador_db_pais_nac').style.display='';
});
$("#trabajador_db_estado_ubica2").focus(function() {
	getObj('trabajador_db_estado_ubica2').style.display='none';
	getObj('trabajador_db_estado_ubica').style.display='';
	getObj('trabajador_db_municipio_ubica2').style.display='none';
	getObj('trabajador_db_municipio_ubica').style.display='';
});
$("#trabajador_db_estado_ubica").change(function() {
	var estado=getObj('trabajador_db_estado_ubica').value;
	getObj('trabajador_db_id_estado').value=estado;
});
$("#trabajador_db_municipio_ubica").change(function() {
	var muni=getObj('trabajador_db_municipio_ubica').value;
	getObj('trabajador_db_id_municipio').value=muni;
});
$("#trabajador_db_pais_nac").change(function() {
	var pais=getObj('trabajador_db_pais_nac').value;
	getObj('trabajador_db_id_pais').value=pais;
});
</script>
<div id="botonera">
	<img id="trabajador_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="trabajador_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="trabajador_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="trabajador_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="trabajador_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
<form name="form_db_trabajador" id="form_db_trabajador">
  <div id="pestana_tra">
    <div>
      <ul class="tabs-nav">
        <li><a href="#pes_tra1"><span>Datos Personales</span></a></li>
        <li><a href="#pes_tra2"><span>Datos Administrativos</span></a></li>
        <li><a href="#pes_tra3"><span>Nomina</span></a></li>
      </ul>
    </div>
  <div id="pes_tra1" class="tabs-container">
    <table class="cuerpo_formulario">
         <tr>
                <th width="128" style="border-top: 1px #BADBFC solid;">Cédula</th>
              <td colspan="3" style="border-top: 1px #BADBFC solid;">  
               <select name="trabajador_db_nacionalidad" id="trabajador_db_nacionalidad" style="width:50px; min-width:50px;">
                      <option>V-</option>
                      <option>E-</option>
                      <option>P-</option>
              </select>	    
              <input name="trabajador_db_cedula" type="text" id="trabajador_db_cedula"  size="8" maxlength="9" width="150px" 
                        message="Introduzca el N&uacute;mero de C&eacute;dula. Ejem: ''V-0000000 &oacute; E-0000000''" 
                        jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
                        jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
              <input type="hidden" name="trabajador_db_id_persona" id="trabajador_db_id_persona" /></td>
        </tr>
        <tr>
                <th>Nombres</th>
              <td colspan="2">
              <input name="trabajador_db_nombre" type="text"  id="trabajador_db_nombre" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
         message="Introduzca el Nombre del Trabajador"        jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
              <span style="border-top: 1px #BADBFC solid;">
              <input type="hidden" name="trabajador_db_id_trabajador" id="trabajador_db_id_trabajador" />
            </span></td>
        </tr>
            <tr>
                <th>Apellidos</th>
              <td colspan="2"><input name="trabajador_db_apellido" type="text"  id="trabajador_db_apellido" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}" message="Introduzca el Apellido del Trabajador"
                jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
              </td>
            </tr>
            <tr>
              <th>Sexo</th>
              <td colspan="2"><select name="trabajador_db_sexo" id="trabajador_db_sexo" style="width:50px; min-width:50px;" message="Indique el Sexo del trabajador.">
                <option value="M">M</option>
                <option value="F">F</option>
              </select>
                <strong>&nbsp;&nbsp;Estado Civil: </strong>
                <select name="trabajador_db_estado_civil" id="trabajador_db_estado_civil" message="Seleccione el Estado Civil del Trabajador.">
                  <option value="0" selected="selected">-- SELECCIONE --</option>
                  <option value="1">Soltero(a)</option>
                  <option value="2">Casado(a)</option>
                  <option value="3">Divorciado(a)</option>
                  <option value="4">Viudo(a)</option>
                  <option value="5">Concubino</option>
              </select></td>
            </tr>
            <tr>
                <th>Pais de Nacimiento</th>
              <td width="188"><label>
                  <select name="trabajador_db_pais_nac" id="trabajador_db_pais_nac" message="Seleccione el País de Nacimiento del Trabajador.">
                    <option value="0">-- SELECCION --</option>
                    <?= $opt_pais?>
                  </select>
                <input type="text" name="trabajador_db_pais_nac2" id="trabajador_db_pais_nac2" style="display:none"/>
                <input type="hidden" name="trabajador_db_id_pais" id="trabajador_db_id_pais" />
              </label></td>
              <td width="346"><strong>Fecha Nacimiento:</strong>
                <input readonly="true" type="text" name="trabajador_db_fecha_nac" id="trabajador_db_fecha_nac" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
                jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
                <button type="reset" id="fecha_boton"> ...</button>
              <script type="text/javascript">
                        Calendar.setup({
                            inputField     :    "trabajador_db_fecha_nac",      // id of the input field
                            ifFormat       :    "%d/%m/%Y",       // format of the input field
                            showsTime      :    false,            // will display a time selector
                            button         :    "fecha_boton",   // trigger for the calendar (button ID)
                            singleClick    :    true          // double-click mode
                        });
                    </script></td>
            </tr>
            <tr>
                <th>Lugar Nacimiento</th>
              <td colspan="2"><label>
                <textarea name="trabajador_db_lugar_nac" id="trabajador_db_lugar_nac" cols="60" style="width:422px" message="Introduzca el Lugar de Nacimiento del Trabajador. Ejem: 'Caracas,parroquia sucre...'"></textarea>
              </label></td>
            </tr>
             <tr>
                <th>Ubicacion Hab</th>
              <td colspan="2"> <strong>Estado</strong>
<label>
              <select name="trabajador_db_estado_ubica" id="trabajador_db_estado_ubica" message="Seleccione el Estado en el Cual Habita el Trabajador.">
                <option value="0">-- SELECCIONE --</option>
                <?= $opt_estado?>
              </select>
              <input type="text" name="trabajador_db_estado_ubica2" id="trabajador_db_estado_ubica2" style="display:none" />
              </label>
<label>
  <input type="hidden" name="trabajador_db_id_estado" id="trabajador_db_id_estado" />
</label>
&nbsp;&nbsp;&nbsp;<strong>Municipio</strong><label>
  <select name="trabajador_db_municipio_ubica" id="trabajador_db_municipio_ubica" message="Seleccione el Municipio en el Cual Habita el Trabajador.">
    <option value="0">-- SELECCIONE --</option>
    </select>
  <input type="text" name="trabajador_db_municipio_ubica2" id="trabajador_db_municipio_ubica2" style="display:none"/>
  <input type="hidden" name="trabajador_db_id_municipio" id="trabajador_db_id_municipio" />
</label></td>
            </tr>
             <tr>
                <th>Dirección Hab</th>
              <td colspan="2"><label>
                <textarea name="trabajador_db_direccion" id="trabajador_db_direccion" cols="60" style="width:422px" message="Introduzca la Dirección Exacta de Ubicación del Trabajador."></textarea>
              </label></td>
            </tr>
             <tr>
                <th>Teléfono</th>
              <td colspan="2"><label>
                <input name="trabajador_db_area_telef" type="text" id="trabajador_db_area_telef" size="1" maxlength="3" onkeypress="mover_tele();" message="Indique el Código de Area Teléfonica."/>
              </label>                <label>
                <input name="trabajador_db_numero_telef" type="text" id="trabajador_db_numero_telef" size="8" maxlength="10" message="Indique el Número Teléfonico del Trabajador."/>
                &nbsp;&nbsp;<strong>Tel Emergencia</strong>
<input name="trabajador_db_area_cel" type="text" id="trabajador_db_area_cel" size="1" maxlength="3" message="Indique el Código de Compañía Celular." onkeypress="mover_cel();"/>
              </label>
              <label>
                <input name="trabajador_db_numero_cel" type="text" id="trabajador_db_numero_cel" size="8" maxlength="10" message="Indique el Número Celular del Trabajador."/>
              </label></td>
            </tr>
             <tr>
                <th>Email</th>
                <td colspan="2"><label>
                  <input name="trabajador_db_email" type="text" id="trabajador_db_email" size="30" maxlength="100" jval="{valid:/^[a-zA-Z 0-9 -_.]{1,60}$/, message:'Correo Invalido', styleType:'cover'}"
         message="Introduzca el Correo Electronico del Trabajador"        jvalkey="{valid:/[a-zA-Z 0-9 -_.]/, cFunc:'alert', cArgs:['Email: '+$(this).val()]}"/></label></td>
            </tr>
            <tr>
                <th>Observaci&oacute;n</th>
              <td colspan="2"><label>
                <textarea name="trabajador_db_observacion" id="trabajador_db_observacion" cols="60" style="width:422px" message="Introduzca una Observaciòn."></textarea>
              </label></td>
            </tr>
            <tr>
              <td colspan="3" class="bottom_frame">&nbsp;</td>
            </tr>			
      </table>
  </div>
  <div id="pes_tra2" class="tabs-container">
  	<table class="cuerpo_formulario">
         <tr>
           <th>Entrevista</th>
              <td>  <ul class="input_con_emergente">
                    <li>
               <input name="trabajador_db_entrevista" type="text"  id="trabajador_db_entrevista" size="30" maxlength="60" readonly="true" message="Seleccione la entrevista Hecha al Trabajador."/>
               <input type="hidden" name="trabajador_bd_id_entrevista" id="trabajador_bd_id_entrevista" />
                </li>
                    <li id="entrevista_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
                </ul></td>
         </tr>
         <tr>
                <th width="128" style="border-top: 1px #BADBFC solid;">Cargo</th>
           <td width="463" colspan="2" style="border-top: 1px #BADBFC solid;">
           	<ul class="input_con_emergente">
                    <li>
               <input name="trabajador_db_cargo" type="text"  id="trabajador_db_cargo" maxlength="60" size="30" readonly="true" message="Seleccione el Cargo que Ocupará el Trabajador."/>
               <input type="hidden" name="trabajador_db_id_cargo" id="trabajador_db_id_cargo" />
               </li>
                    <li id="cargo_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
             </ul>
           </td>
        </tr>
        <tr>
                <th>Area de Trabajo</th>
              <td>
              	<ul class="input_con_emergente">
                    <li>
               <input name="trabajador_db_area" type="text"  id="trabajador_db_area" maxlength="60" size="30" readonly="true" message="Seleccione el Area o Departamento dond el Trabajador Realizará sus Labores."/>
               <input type="hidden" name="trabajador_db_id_area" id="trabajador_db_id_area" />
                  </li>
                    <li id="area_trabajo_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
             </ul>
              </td>
        </tr>
        <tr>
          <th>Asignaciones</th>
          <td><textarea name="trabajador_db_asignacion" id="trabajador_db_asignacion" cols="60" style="width:422px" message="Indique las Asignaciones o Tareas que Desempeñará el Trabajador"></textarea></td>
        </tr>
            <tr>
              <td colspan="2" class="bottom_frame">&nbsp;</td>
            </tr>			
      </table>
  </div>
  <div id="pes_tra3" class="tabs-container">
  	<table class="cuerpo_formulario">
         <tr>
                <th width="128" style="border-top: 1px #BADBFC solid;">Tipo de Nomina</th>
           <td width="463" colspan="2" style="border-top: 1px #BADBFC solid;">
           <ul class="input_con_emergente">
                    <li>
               <input name="trabajador_db_tipo_nomina" type="text"  id="trabajador_db_tipo_nomina" size="30" maxlength="60" readonly="true" message="Seleccione el Tipo de Nomina al que pertenece el Trabajador."/>
               <input type="hidden" name="trabajador_bd_id_tipo_nomina" id="trabajador_bd_id_tipo_nomina" />
                </li>
                    <li id="tipo_nomina_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
                </ul>
           </td>
        </tr>
            <tr>
                <th>Fecha de Ingreso:</th>
              <td>
              <input readonly="true" type="text" name="trabajador_db_fecha_ingreso" id="trabajador_db_fecha_ingreso" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
                jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
                <button type="reset" id="fecha_ingreso_boton"> ...</button>
              <script type="text/javascript">
                        Calendar.setup({
                            inputField     :    "trabajador_db_fecha_ingreso",      // id of the input field
                            ifFormat       :    "%d/%m/%Y",       // format of the input field
                            showsTime      :    false,            // will display a time selector
                            button         :    "fecha_ingreso_boton",   // trigger for the calendar (button ID)
                            singleClick    :    true          // double-click mode
                        });
                    </script>
              </td>
            </tr>
            <tr>
              <th>Años Servicios Público</th>
              <td><input type="text" name="trabajador_db_anos_servicios" id="trabajador_db_anos_servicios" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
                jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/></td>
            </tr>
            <tr>
              <td colspan="2" class="bottom_frame">&nbsp;</td>
            </tr>			
      </table>
  </div>
 </div>
</form>