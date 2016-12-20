<? if (!$_SESSION) session_start();
?>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$c=0;
$f=1;
$sql="	SELECT 
			monto,
			id_escala_sueldo
		FROM 
			escala_sueldos 
		WHERE 
			id_organismo = $_SESSION[id_organismo]
		ORDER BY id_escala_sueldo ";
$row =& $conn->Execute($sql);
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>

<script language="javascript">
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
//----------------- fin mascara edicion de campo -------------------------///
</script>

<script>
var dialog;
//----------------------------------------------------------------------------------------------------


$("#sitio_fisico_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/sitiofisico/db/vista.grid_sitio_fisico_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Sitio Físico', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_unidad= jQuery("#sitio_fisico_db_nombre_uni").val(); 
					var busq_nombre= jQuery("#sitio_fisico_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/db/sql_sitio_fisico_nombre.php?busq_unidad="+busq_unidad+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#sitio_fisico_db_nombre_uni").change(function()
				{
						//if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#sitio_fisico_db_nombre").keypress(function(key)
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
							var busq_unidad= jQuery("#sitio_fisico_db_nombre_uni").val();
							var busq_nombre= jQuery("#sitio_fisico_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/db/sql_sitio_fisico_nombre.php?busq_unidad="+busq_unidad+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/sitiofisico/db/sql_sitio_fisico_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Sitio Fisico','Comentario','id_unidad','Unidad'],
								colModel:[
									{name:'id_sitio_fisico',index:'id_sitio_fisico', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad',index:'unidad', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sitio_fisico_db_id_sitio_fisico').value = ret.id_sitio_fisico;
									getObj('sitio_fisico_db_id_unidad_ejecutora').value = ret.id_unidad_ejecutora;
									getObj('sitio_fisico_db_nombre_unidad').value = ret.unidad;					
									getObj('sitio_fisico_db_nombre_sitio').value = ret.nombre;
									getObj('sitio_fisico_db_comentario').value = ret.comentarios;
									getObj('sitio_fisico_db_btn_guardar').style.display = 'none';
									getObj('sitio_fisico_db_btn_actualizar').style.display = '';
									getObj('sitio_fisico_db_btn_eliminar').style.display = '';
		
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#valor_impuesto_db_nombre_uni").focus();
								$('#sitio_fisico_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_sitio_fisico',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});



//
//

//
//
//----------------------------------------------------------------


$("#aumento_sueldos_pr_btn_guardar").click(function() {
	//													
	nue_suel = getObj('aumento_sueldos_pr_nuevo_sueldo').value;
	act_suel = getObj('aumento_sueldos_pr_sueldo').value;
	act_suel = act_suel.replace('.','');
	act_suel = act_suel.replace(',','.');
	act_suel = parseFloat(act_suel);
	nue_suel = nue_suel.replace('.','');
	nue_suel = nue_suel.replace(',','.');
	nue_suel = parseFloat(nue_suel);
	//
	if(nue_suel <= act_suel){
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El nuevo Sueldo Tiene que ser Superior al Sueldo Actual </p></div>",true,true);
		}
		//
	if(nue_suel > act_suel){	
	if ($('#form_pr_aumento_sueldos').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/sueldos/pr/sql.registrar_aum_sueldos.php",
			data:dataForm('form_pr_aumento_sueldos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//
					getObj('aumento_sueldos_pr_id_aumento_sueldo').value = '';
					getObj('aumento_sueldos_pr_id_trabajador').value = '';
					getObj('aumento_sueldos_pr_cedula_trabajador').value = '';
					getObj('aumento_sueldos_pr_nombre_trabajador').value = '';
					getObj('aumento_sueldos_pr_apellido_trabajador').value = '';
					getObj('aumento_sueldos_pr_unidad').value = '';
					getObj('aumento_sueldos_pr_cargo').value = '';
					getObj('aumento_sueldos_pr_fecha_aumento').value = getObj('aumento_sueldos_pr_fechact').value;
					getObj('aumento_sueldos_pr_sueldo').value = '0,00';
					getObj('aumento_sueldos_pr_porcentaje').value = '00,00';
					getObj('aumento_sueldos_pr_nuevo_sueldo').value = '0,00';
					getObj('aumento_sueldos_pr_comentario').value = '';
					getObj('form_aumento_sueldos_vista_escala_sueldos').style.display='none';
					getObj('aumento_sueldos_pr_btn_actualizar').style.display = 'none';
					getObj('aumento_sueldos_pr_btn_eliminar').style.display = 'none';
					getObj('aumento_sueldos_pr_btn_guardar').style.display = '';
					habilitar_radios();
					//
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Maximo")
					max_registro();
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
	//
	}
});

//----------------------------------------------------------------
//-----------------------Actualizar-------------------------------
$("#sitio_fisico_db_btn_actualizar").click(function() {
	if ($('#form_db_sitio_fisico').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/sitiofisico/db/sql.actualizar_sitio_fisico.php",
			data:dataForm('form_db_sitio_fisico'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('sitio_fisico_db_id_sitio_fisico').value = '';
					getObj('sitio_fisico_db_id_unidad_ejecutora').value='';
					getObj('sitio_fisico_db_nombre_unidad').value='';
					getObj('sitio_fisico_db_nombre_sitio').value = '';
					getObj('sitio_fisico_db_comentario').value = '';
					getObj('sitio_fisico_db_btn_actualizar').style.display = 'none';
					getObj('sitio_fisico_db_btn_eliminar').style.display = 'none';
					getObj('sitio_fisico_db_btn_guardar').style.display = '';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha del valor del impuesto \n tiene que ser mayor que la fecha actual </p></div>",true,true);
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
$("#sitio_fisico_db_btn_eliminar").click(function() {
	if ($('#form_db_sitio_fisico').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/sitiofisico/db/sql.eliminar_sitio_fisico.php",
			data:dataForm('form_db_sitio_fisico'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sitio_fisico_db_id_sitio_fisico').value = '';
					getObj('sitio_fisico_db_id_unidad_ejecutora').value='';
					getObj('sitio_fisico_db_nombre_unidad').value='';
					getObj('sitio_fisico_db_nombre_sitio').value = '';
					getObj('sitio_fisico_db_comentario').value = '';
					getObj('sitio_fisico_db_btn_actualizar').style.display = 'none';
					getObj('sitio_fisico_db_btn_eliminar').style.display = 'none';
					getObj('sitio_fisico_db_btn_guardar').style.display = '';
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

//
//
$("#aumento_sueldos_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/sueldos/pr/vista.grid_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#aumento_suelods_pr_nombre").val(); 
					var busq_cedula= jQuery("#aumento_sueldos_pr_cedula").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/sueldos/pr/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#aumento_sueldos_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#aumento_sueldos_pr_cedula").keypress(function(key)
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
							var busq_nombre= jQuery("#aumento_sueldos_pr_nombre").val();
							var busq_cedula= jQuery("#aumento_sueldos_pr_cedula").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/sueldos/pr/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:780,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/sueldos/pr/sql_trabajador_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Apellido','Unidad','Cargo'],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:100,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:100,sortable:false,resizable:false},
									{name:'cargo',index:'cargo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('aumento_sueldos_pr_cedula_trabajador').value = ret.cedula;
									getObj('aumento_sueldos_pr_id_trabajador').value=ret.id_trabajador;
									getObj('aumento_sueldos_pr_nombre_trabajador').value = ret.nombre;
									getObj('aumento_sueldos_pr_apellido_trabajador').value = ret.apellido;
									getObj('aumento_sueldos_pr_unidad').value = ret.unidad;
									getObj('aumento_sueldos_pr_cargo').value =	ret.cargo;
									dialog.hideAndUnload();
									consulta_automatica_sueldo();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#sitio_fisico_db_nombre").focus();
								$('#aumento_sueldos_pr_nombre').alpha({allow:' '});
								$('#aumento_sueldos_pr_cedula').numeric({allow:'V-E-'});
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
//
//

//
//
// ******************************************************************************
$("#aumento_sueldos_pr_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
getObj('aumento_sueldos_pr_id_aumento_sueldo').value = '';
getObj('aumento_sueldos_pr_id_trabajador').value = '';
getObj('aumento_sueldos_pr_cedula_trabajador').value = '';
getObj('aumento_sueldos_pr_nombre_trabajador').value = '';
getObj('aumento_sueldos_pr_apellido_trabajador').value = '';
getObj('aumento_sueldos_pr_unidad').value = '';
getObj('aumento_sueldos_pr_cargo').value = '';
getObj('aumento_sueldos_pr_fecha_aumento').value = getObj('aumento_sueldos_pr_fechact').value;
getObj('aumento_sueldos_pr_sueldo').value = '0,00';
getObj('aumento_sueldos_pr_porcentaje').value = '00,00';
getObj('aumento_sueldos_pr_nuevo_sueldo').value = '0,00';
getObj('aumento_sueldos_pr_comentario').value = '';
getObj('aumento_sueldos_pr_btn_actualizar').style.display = 'none';
getObj('aumento_sueldos_pr_btn_eliminar').style.display = 'none';
getObj('aumento_sueldos_pr_btn_guardar').style.display = '';
getObj('form_aumento_sueldos_vista_escala_sueldos').style.display='none';
habilitar_radios();
setBarraEstado("");
});
//
//
$("#aumento_sueldos_pr_cedula_trabajador").change(function() {
	$.ajax({
			url:"modulos/rrhh/sueldos/pr/sql_consulta_automatica_trabajador.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_aumento_sueldos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
				if(html!=''){
					arreglo = html.split('*');
					getObj('aumento_sueldos_pr_id_trabajador').value = arreglo[0];
					getObj('aumento_sueldos_pr_cedula_trabajador').value = arreglo[1];
					getObj('aumento_sueldos_pr_nombre_trabajador').value = arreglo[2];
					getObj('aumento_sueldos_pr_apellido_trabajador').value = arreglo[3];
					getObj('aumento_sueldos_pr_unidad').value=arreglo[4];
					getObj('aumento_sueldos_pr_cargo').value=arreglo[5];
					consulta_automatica_sueldo();
				}
				else if(html==''){
					getObj('aumento_sueldos_pr_id_trabajador').value = '';
					getObj('aumento_sueldos_pr_cedula_trabajador').value = '';
					getObj('aumento_sueldos_pr_nombre_trabajador').value = '';
					getObj('aumento_sueldos_pr_apellido_trabajador').value = '';
				}
				
			 }
		});

});
//Redondeo de dos decimales
//
function redondeo(numero)
{
	var original=parseFloat(numero);
	var result=Math.round(original*100)/100 ;
	return result;
}
//
function consulta_automatica_sueldo()
{	

		$.ajax({
			url:"modulos/rrhh/sueldos/pr/sql_grid_sueldos.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_aumento_sueldos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html
			if(recordset)
				{
				recordset = recordset.split("*");
				recordset[1] = recordset[1].replace('{',''); 
				recordset[1] = recordset[1].replace('}','');
				recordset[2] = recordset[2].replace('{',''); 
				recordset[2] = recordset[2].replace('}','');
				recordset[2] = recordset[2].replace('"','');
				recordset[2] = recordset[2].replace('"','');
				arreglo2 = recordset[2].split(',');
				arreglo = recordset[1].split(',');
				pos=-1;
					for(i=0; i<=39; i++){
							
							if(arreglo[i]=='0'){
								pos = i;
								break;
							}
						
						
					}
					if(pos==-1)
						pos=20;
						sueldo = arreglo[pos-1].replace('.',',');
						exi = sueldo.indexOf('.');
						if(exi==-1)
						sueldo = sueldo + ",00";
					getObj('aumento_sueldos_pr_sueldo').value = sueldo;
					//getObj('aumento_sueldos_pr_comentario').value = arreglo2[pos-1];
				}
				else
			   {  
					getObj('aumento_sueldos_pr_sueldo').value='0,00';
			    }
			 }
		});	 	 
}
//
function calcular_sueldo(){	
	var sact = getObj('aumento_sueldos_pr_sueldo').value;
	sact = sact.replace('.','');
	sact = sact.replace(',','.');
	var por = getObj('aumento_sueldos_pr_porcentaje').value;
	por = por.replace('.','');
	por = por.replace(',','.');
	if (sact!='0,00'){
		snue = (sact * por)/100;
		snue = parseFloat(sact) + parseFloat(snue);
		snue = String(snue); 
		pos = snue.lastIndexOf('.');
		if(pos==-1)
			snue = snue + ',00'; 
		else	
			snue = redondeo(snue);
			snue = String(snue);
			snue = snue.replace('.',',');
		getObj('aumento_sueldos_pr_nuevo_sueldo').value = snue;
	}
	
}
//
//
function opt_porcentaje(){
	getObj('aumento_sueldos_pr_nuevo_sueldo').readOnly = true;
	getObj('aumento_sueldos_pr_nuevo_sueldo').value = '0,00';
	getObj('aumento_sueldos_pr_porcentaje').readOnly = false;
	getObj('form_aumento_sueldos_vista_escala_sueldos').style.display='none';
	
}
function opt_tipeo(){
	getObj('aumento_sueldos_pr_nuevo_sueldo').readOnly = false;
	getObj('aumento_sueldos_pr_porcentaje').value = '00,00';
	getObj('aumento_sueldos_pr_porcentaje').readOnly = true;
	getObj('form_aumento_sueldos_vista_escala_sueldos').style.display='none';
}
//
function max_registro(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Este Usuario llego al límite de registro de Aumento de Sueldos </p></div>",true,true);
}
//
//
function habilitar_radios(){
	getObj('aumento_sueldos_pr_calcular1').checked=true;
	getObj('aumento_sueldos_pr_calcular2').checked=false;
	getObj('aumento_sueldos_pr_porcentaje').readOnly=false;
	getObj('aumento_sueldos_pr_nuevo_sueldo').readOnly=true;
}

//
$("#aumento_sueldos_pr_calcular3").click(function() {
	getObj('form_aumento_sueldos_vista_escala_sueldos').style.display='';
	getObj('aumento_sueldos_pr_porcentaje').readOnly=true;
	getObj('aumento_sueldos_pr_nuevo_sueldo').readOnly=true;
});
//
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos

$('#aumento_sueldos_db_id_aumento_sueldo').alpha({allow:' '});
$('#aumento_sueldos_pr_cedula_trabajador').numeric({allow:'V-E-'});
$('#aumento_sueldos_db_nombre_trabajador').alpha({allow:' '});
$('#aumento_sueldos_db_apellido_trabajador').alpha({allow:' '});
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
</script>
<div id="botonera">
	<img id="aumento_sueldos_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="aumento_sueldos_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img style="display:none" id="aumento_sueldos_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="aumento_sueldos_pr_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="aumento_sueldos_pr_btn_guardar"  src="imagenes/iconos/aumentar.png"  /></div>
    

<form name="form_pr_aumento_sueldos" id="form_pr_aumento_sueldos">
<input type="hidden" name="aumento_sueldos_pr_id_aumento_sueldo" id="aumento_sueldos_pr_id_aumento_sueldo" />
<input type="hidden" name="aumento_sueldos_pr_fechact" id="aumento_sueldos_pr_fechact" value="<?php echo date("d-m-Y");?>"/>
<input type="hidden" name="aumento_sueldos_pr_id_trabajador" id="aumento_sueldos_pr_id_trabajador"/>
<table class="cuerpo_formulario">
  <tr>
    <th class="titulo_frame" colspan="3"> <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Sueldo </th>
  </tr>
  <tr>
    <th width="124">Trabajador</th>
    <td colspan="2"><ul class="input_con_emergente">
      <li>
        <input name="aumento_sueldos_pr_cedula_trabajador" type="text"  id="aumento_sueldos_pr_cedula_trabajador" size="10"
           jval="{valid:/^[0-9 V-E-]{1,60s}$/, message:'Cedula Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9 V-E-]/, cFunc:'alert', cArgs:['Cedula: '+$(this).val()]}"/>
      </li>
      <li id="aumento_sueldos_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
    </ul></td>
  </tr>
  <tr>
    <th>Nombre</th>
    <td colspan="2"><input readonly="true" name="aumento_sueldos_pr_nombre_trabajador" type="text"  id="aumento_sueldos_pr_nombre_trabajador" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" message="Nombre del Trabajador"/></td>
  </tr>
  <tr>
    <th>Apellido</th>
    <td colspan="2"><input readonly="true" name="aumento_sueldos_pr_apellido_trabajador" type="text"  id="aumento_sueldos_pr_apellido_trabajador" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}" message="Apelldio del Trabajador"/></td>
  </tr>
  <tr>
    <th>Unidad</th>
    <td width="180"><input readonly="true" name="aumento_sueldos_pr_unidad" type="text"  id="aumento_sueldos_pr_unidad" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}" message="Unidad donde pertenece el Trabajador"/></td>
    <td width="243">&nbsp;&nbsp;<strong>Cargo:
      <input readonly="true" name="aumento_sueldos_pr_cargo" type="text"  id="aumento_sueldos_pr_cargo" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}" message="Cargo que desempeña el Trabajador"/>
    </strong></td>
  </tr>
  <tr>
    <th>Fecha de Aumento</th>
    <td colspan="2"><input readonly="true" type="text" name="aumento_sueldos_pr_fecha_aumento" id="aumento_sueldos_pr_fecha_aumento" size="7" value="<?php echo date("d-m-Y")?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
      <button type="reset" id="fecha_boton"> ...</button>
      <script type="text/javascript">
					Calendar.setup({
						inputField     :    "aumento_sueldos_pr_fecha_aumento",      // id of the input field
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script></td>
  </tr>
  <tr>
    <th>Sueldo Actual</th>
    <td colspan="2"><input readonly="true" name="aumento_sueldos_pr_sueldo" type="text"  id="aumento_sueldos_pr_sueldo" maxlength="60" size="10" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Sueldo Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Sueldo: '+$(this).val()]}" message="Salario Actual del Trabajador"/></td>
  </tr>
  <tr>
    <th>Calcular Sueldo</th>
    <td colspan="2"> Porcentaje:
      <input name="aumento_sueldos_pr_calcular" type="radio" id="aumento_sueldos_pr_calcular1" value="1" checked="checked" onclick="opt_porcentaje();"/>
      &nbsp;Tipeo:
      <input type="radio" name="aumento_sueldos_pr_calcular" id="aumento_sueldos_pr_calcular2" value="2" onclick="opt_tipeo();" /> 
      &nbsp;Escala Sueldos: <input type="radio" name="aumento_sueldos_pr_calcular" id="aumento_sueldos_pr_calcular3" value="3"/></td>
  </tr>
  <tr id="form_aumento_sueldos_vista_escala_sueldos" style="display:none">
    <th>Fecha de Aumento</th>
    <td colspan="2"><table width="200" border="1">
      <tr>
        <td><div align="center"><strong>Sueldos/Niveles</strong></div></td>
        <td><div align="center"><strong>MIN	I </strong></div></td>
        <td><div align="center"><strong>II</strong></div></td>
        <td><div align="center"><strong>III</strong></div></td>
        <td><div align="center"><strong>PROM IV</strong></div></td>
        <td><div align="center"><strong>V</strong></div></td>
        <td><div align="center"><strong>VI</strong></div></td>
        <td><div align="center"><strong>VII</strong></div></td>
      </tr>
      <?php
	  	$c = 0;
		$f = 1;
	  	while(!$row->EOF){
			$c++;
			if($c==1){
				echo "<tr>
				<td><div align='center'><strong>$f</strong></div></td>";
			}
			$monto = $row->fields("monto");
			$pos = strpos($monto,'.');
			$tam = strlen($monto);
			
			if($pos=='')
				$monto.='00';
			if($pos!=''){
				$res = $tam - $pos;
				if($res<=2)
					$monto.='0';
			}	
		?>
        <td><?php echo $pos;?><input readonly="true" id="escala_sueldo<?php echo $f."".$c;?>" name="escala_sueldo" type="text" maxlength="10" size="6" value="<?php echo $monto;?>" alt="signed-decimal" onclick="press(this.value)" onmousemove="upclick(this.id)" onmouseout="downclick(this.id)"/></td>
        <?php
			if($c==7){
				$f++;
				$c=0;	
		?>
        	</tr>
      <?php 
			}
			
			$row->MoveNext();
		}
	  ?>
    </table></td>
  </tr>
  <tr>
    <th>Porcentaje</th>
    <td><input name="aumento_sueldos_pr_porcentaje" type="text" id="aumento_sueldos_pr_porcentaje" size="10" alt="signed-decimal-im" style="text-align:right" onblur="calcular_sueldo()"/>
      <strong>%</strong></td>
    <td><strong>Nuevo Sueldo:</strong>
      <input readonly="true" type="text" name="aumento_sueldos_pr_nuevo_sueldo" id="aumento_sueldos_pr_nuevo_sueldo" size="10" alt="signed-decimal"/></td>
  </tr>
  <tr>
    <th>Observaci&oacute;n</th>
    <td colspan="2"><label>
      <textarea name="aumento_sueldos_pr_comentario" id="aumento_sueldos_pr_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este porcentaje es...'"></textarea>
    </label></td>
  </tr>
  <tr>
    <td colspan="3" class="bottom_frame">&nbsp;</td>
  </tr>
</table>
</form>
<script>
function press(val){
	 getObj('aumento_sueldos_pr_nuevo_sueldo').value=val;
}
function upclick(val){
	getObj(val).style.opacity='0.5';
}
function downclick(val){
	getObj(val).style.opacity='1';
}
</script>