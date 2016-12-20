<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM organismo";
$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_organismo.="<option value='".$rs_modulo->fields("id_organismo")."' >".$rs_modulo->fields("nombre")."</option>";
$rs_modulo->MoveNext();
}

?>

<script type='text/javascript'>
var dialog;
$("#accion_especifica_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/accion_especifica/db/grid_accion_especifica.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Acci&oacute;n Especifica',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
							width:1000,
							height:300,
							recordtext:"Registro(s)",
							loadtext: "Recuperando Información del Servidor",		
							url:'modulos/presupuesto/accion_especifica/db/sql_grid_accion_especifica.php?nd='+nd,
							datatype: "json",
							colNames:['ID','Codigo','Accion Especifica','Accion','Comentario','id_jefe','Responsable','id_central','Codigo Accion Central','Accion Central','Accion Central','id_proyecto','Codigo Proyecto','Proyecto','Proyectos'],
							colModel:[
								{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
								{name:'codigo',index:'codigo', width:30,sortable:false,resizable:false},
								{name:'accion',index:'accion', width:150,sortable:false,resizable:false},
								{name:'accionss',index:'accionss', width:150,sortable:false,resizable:false,hidden:true},
								{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
								{name:'id_jefe',index:'id_jefe', width:20,sortable:false,resizable:false,hidden:true},
								{name:'jefe',index:'jefe', width:50,sortable:false,resizable:false},
								{name:'id_central',index:'id_central', width:20,sortable:false,resizable:false,hidden:true},
								{name:'codi_central',index:'codi_central', width:20,sortable:false,resizable:false,hidden:true},
								{name:'centralre',index:'centralre', width:160,sortable:false,resizable:false,hidden:true},
								{name:'central',index:'central', width:160,sortable:false,resizable:false},
								{name:'id_proyecto',index:'id_proyecto', width:20,sortable:false,resizable:false,hidden:true},
								{name:'codi_proyecto',index:'codi_proyecto', width:20,sortable:false,resizable:false,hidden:true},
								{name:'proyectore',index:'proyectore', width:160,sortable:false,resizable:false},
								{name:'proyecto',index:'proyecto', width:160,sortable:false,resizable:false,hidden:true}
							],
							pager: $('#pager_grid_'+nd),
							rowNum:20,
							rowList:[20,50,100],
							imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
							onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('accion_especifica_db_id').value = ret.id;
								getObj('accion_especifica_db_codigo').value = ret.codigo;									
								getObj('accion_especifica_db_denominacion').value = ret.accionss;
								getObj('accion_especifica_db_comentario').value = ret.comentario;
								getObj('accion_especifica_db_jefe_accion_id').value = ret.id_jefe;
								getObj('accion_especifica_db_jefe_accion').value = ret.jefe;
								if((ret.id_central != "")&&( ret.id_central != "0")){
								getObj('accion_especifica_db_accion_central').value = ret.id_central;
								getObj('accion_especifica_db_codigo_central').value = ret.codi_central;
								getObj('accion_especifica_db_nombre_central').value = ret.central;								
								getObj('accion_especifica_db_proyecto').value = "";
								getObj('accion_especifica_db_nombre_proyecto').value = "  NO APLICA ESTA OPCION  ";
								getObj('accion_especifica_db_codigo_proyecto').value = "0000";
								}else{
								getObj('accion_especifica_db_accion_central').value = "";
								getObj('accion_especifica_db_codigo_central').value = "0000";
								getObj('accion_especifica_db_nombre_central').value = "  NO APLICA ESTA OPCION  ";
								getObj('accion_especifica_db_proyecto').value = ret.id_proyecto;
								getObj('accion_especifica_db_nombre_proyecto').value = ret.proyecto;
								getObj('accion_especifica_db_codigo_proyecto').value = ret.codi_proyecto;
								}									
								
								
								
								getObj('accion_especifica_db_btn_eliminar').style.display='';
								getObj('accion_especifica_db_btn_actualizar').style.display='';
								getObj('accion_especifica_db_btn_guardar').style.display='none';									
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
								sortname: 'id_accion_especifica',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#accion_especifica_db_btn_guardar").click(function() {
	if($('#form_db_accion_especifica').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/accion_especifica/db/sql.accion_especifica.php",
			data:dataForm('form_db_accion_especifica'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('accion_especifica_db_codigo_proyecto').disabled="" ;
					getObj('accion_especifica_db_codigo_central').disabled="" ;
					clearForm('form_db_accion_especifica');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('accion_especifica_db_codigo_proyecto').disabled="" ;
					getObj('accion_especifica_db_codigo_central').disabled="" ;
					document.form_db_accion_especifica.accion_especifica_db_nombre.value="";
					document.form_db_accion_especifica.accion_especifica_db_nombre.focus();
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				//	setBarraEstado(html,true,true);
				}
			}
		});
	}
});

$("#accion_especifica_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_db_accion_especifica').jVal())
	{
		$.ajax (
		{
			url: "modulos/presupuesto/accion_especifica/db/sql.actualizar.php",
			data:dataForm('form_db_accion_especifica'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('accion_especifica_db_btn_actualizar').style.display='none';
					getObj('accion_especifica_db_btn_guardar').style.display='';
					getObj('accion_especifica_db_codigo_proyecto').disabled="" ;
					getObj('accion_especifica_db_codigo_central').disabled="" ;
					
					clearForm('form_db_accion_especifica');
				}
				else if (html=="NoActualizo")
				{
					
					setBarraEstado(mensaje[registro_existe],true,true);
					
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});

$("#accion_especifica_db_btn_eliminar").click(function() {
  if (getObj('accion_especifica_db_id').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/presupuesto/accion_especifica/db/sql.eliminar.php",
			data:dataForm('form_db_accion_especifica'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('accion_especifica_db_btn_cancelar').style.display='';
					getObj('accion_especifica_db_btn_eliminar').style.display='none';
					getObj('accion_especifica_db_btn_actualizar').style.display='none';
					getObj('accion_especifica_db_btn_guardar').style.display='';
					clearForm('form_db_accion_especifica');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true);
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
  }
});

//-----
$("#accion_especifica_db_btn_consultar_accion").click(function() { 
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/accion_especifica/db/grid_accion_especifica.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Jefe de Accion Especifica', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/accion_especifica/db/cmb.sql.jefe_proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo', 'Jefe de Accion Especifica'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false},
									{name:'jefe',index:'jefe', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('accion_especifica_db_jefe_accion_id').value = ret.id;
									getObj('accion_especifica_db_jefe_accion').value = ret.jefe;
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
								sortname: 'id_jefe_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
// -----

$("#accion_especifica_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('accion_especifica_db_btn_cancelar').style.display='';
	getObj('accion_especifica_db_btn_actualizar').style.display='none';
	getObj('accion_especifica_db_btn_eliminar').style.display='none';
	getObj('accion_especifica_db_btn_guardar').style.display='';
	getObj('accion_especifica_db_codigo_proyecto').disabled="" ;
	getObj('accion_especifica_db_codigo_central').disabled="" ;
	clearForm('form_db_accion_especifica');
});
///*************************************************************************************************************************
function consulta_automatica_accion_especifica()
{
	$.ajax({
			url:"modulos/presupuesto/accion_especifica/db/sql_grid_accion_codigo.php",
            data:dataForm('form_db_accion_especifica'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html.replace('"','');
			if(recordset)
			{
				recordset = recordset.split(".");
				getObj('accion_especifica_db_id').value = recordset[0];
				getObj('accion_especifica_db_jefe_accion_id').value=recordset[1];
				getObj('accion_especifica_db_jefe_accion').value =recordset[2];
				getObj('accion_especifica_db_denominacion').value=recordset[3];
				getObj('accion_especifica_db_comentario').value=recordset[4];
				if (recordset[5] != 0){
					getObj('accion_especifica_db_accion_central').value=recordset[5];
					getObj('accion_especifica_db_codigo_central').value=recordset[6];
					getObj('accion_especifica_db_nombre_central').value=recordset[7];
				}else{
					getObj('accion_especifica_db_accion_central').value = "";
					getObj('accion_especifica_db_codigo_central').value = "0000";
					getObj('accion_especifica_db_nombre_central').value = "  NO APLICA ESTA OPCION  ";
				}
				if (recordset[8] != 0){
					getObj('accion_especifica_db_proyecto').value=recordset[8];
					getObj('accion_especifica_db_codigo_proyecto').value=recordset[9];
					getObj('accion_especifica_db_nombre_proyecto').value=recordset[10];	
				}else{
					getObj('accion_especifica_db_proyecto').value = "";
					getObj('accion_especifica_db_codigo_proyecto').value = "0000";
					getObj('accion_especifica_db_nombre_proyecto').value = "  NO APLICA ESTA OPCION  ";
				}
				getObj('accion_especifica_db_btn_eliminar').style.display='';
				getObj('accion_especifica_db_btn_actualizar').style.display='';
				getObj('accion_especifica_db_btn_guardar').style.display='none';									
				}
			else{  
			   	getObj('accion_especifica_db_id').value ="";
			    getObj('accion_especifica_db_denominacion').value="";
				getObj('accion_especifica_db_comentario').value="";
				getObj('accion_especifica_db_jefe_accion').value ="" ;
			 	getObj('accion_especifica_db_jefe_accion_id').value="" ;
				getObj('accion_especifica_db_proyecto').value = "";
				getObj('accion_especifica_db_codigo_proyecto').value = "";
				getObj('accion_especifica_db_nombre_proyecto').value = "";
				getObj('accion_especifica_db_accion_central').value = "";
				getObj('accion_especifica_db_codigo_central').value = "";
				getObj('accion_especifica_db_nombre_central').value = "";


				}
			 }
		});	 	 
}
//***********************----*******************************
///**********************----************************************
function consulta_automatica_accion_central()
{
	$.ajax({
			url:"modulos/presupuesto/accion_especifica/db/sql_grid_accion_cental_codigo.php",
            data:dataForm('form_db_accion_especifica'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('accion_especifica_db_accion_central').value = recordset[0];
				getObj('accion_especifica_db_nombre_central').value=recordset[1];
				getObj('accion_especifica_db_proyecto').value="";
				getObj('accion_especifica_db_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('accion_especifica_db_codigo_proyecto').value ="0000" ;
				getObj('accion_especifica_db_codigo_proyecto').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('accion_especifica_db_accion_central').value ="";
			    getObj('accion_especifica_db_nombre_central').value="";
				getObj('accion_especifica_db_proyecto').value="";
				getObj('accion_especifica_db_nombre_proyecto').value="";
				getObj('accion_especifica_db_codigo_proyecto').value ="" ;
				getObj('accion_especifica_db_codigo_proyecto').disabled="" ;
				}
			 }
		});	 	 
}
// -----
$("#accion_especifica_db_btn_consultar_central").click(function() {
if(getObj('accion_especifica_db_proyecto').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/accion_especifica/db/grid_accion_especifica.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/accion_especifica/db/cmb.sql.accion_central.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('accion_especifica_db_accion_central').value = ret.id;
									getObj('accion_especifica_db_codigo_central').value = ret.codigo;
									getObj('accion_especifica_db_nombre_central').value = ret.denominacion;
									getObj('accion_especifica_db_proyecto').value="";
									getObj('accion_especifica_db_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
									getObj('accion_especifica_db_codigo_proyecto').value ="0000" ;
									getObj('accion_especifica_db_codigo_proyecto').disabled="disabled" ;
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

///**********************----************************************
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/accion_especifica/db/sql_grid_proyecto_codigo.php",
            data:dataForm('form_db_accion_especifica'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('accion_especifica_db_proyecto').value = recordset[0];
				getObj('accion_especifica_db_nombre_proyecto').value=recordset[1];
				getObj('accion_especifica_db_accion_central').value="";
				getObj('accion_especifica_db_nombre_central').value="  NO APLICA ESTA OPCION  ";
				getObj('accion_especifica_db_codigo_central').value ="0000" ;
				getObj('accion_especifica_db_codigo_central').disabled="disabled" ;
				}
				else
			 {  
			   	getObj('accion_especifica_db_proyecto').value ="";
			   //	getObj('accion_especifica_db_codigo_proyecto').value ="";
			    getObj('accion_especifica_db_nombre_proyecto').value="";
				getObj('accion_especifica_db_nombre_central').value="";
				getObj('accion_especifica_db_codigo_central').value ="" ;
				getObj('accion_especifica_db_accion_central').value ="";
				getObj('accion_especifica_db_codigo_central').disabled="" ;
				}
			 }
		});	 	 
}
// -----

$("#accion_especifica_db_btn_consultar_proyecto").click(function() {
if(getObj('accion_especifica_db_accion_central').value =="")
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/accion_especifica/db/grid_accion_especifica.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/accion_especifica/db/cmb.sql.proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('accion_especifica_db_proyecto').value = ret.id;
									getObj('accion_especifica_db_codigo_proyecto').value = ret.codigo;
									getObj('accion_especifica_db_nombre_proyecto').value = ret.denominacion;
									getObj('accion_especifica_db_accion_central').value="";
									getObj('accion_especifica_db_nombre_central').value="  NO APLICA ESTA OPCION  ";
									getObj('accion_especifica_db_codigo_central').value ="0000" ;
									getObj('accion_especifica_db_codigo_central').disabled="disabled" ;

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
//***********************************************************************************************************************
// -----------------------------------------------------------------------------------------------------------------------------------


$('#accion_especifica_db_codigo').numeric({allow:''});
$('#accion_especifica_db_codigo').change(consulta_automatica_accion_especifica);
$('#accion_especifica_db_codigo_central').numeric({allow:''});
$('#accion_especifica_db_codigo_central').change(consulta_automatica_accion_central);
$('#accion_especifica_db_codigo_proyecto').numeric({allow:''});
$('#accion_especifica_db_codigo_proyecto').change(consulta_automatica_proyecto);

$('#accion_especifica_db_denominacion').alpha({allow:'._1234567890-ÁÉÍÓÚáéíóú '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>
<div id="botonera">
	<img id="accion_especifica_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="accion_especifica_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="accion_especifica_db_btn_consultar" name="accion_especifica_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="accion_especifica_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="accion_especifica_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />

</div>

<form method="post" name="form_db_accion_especifica" id="form_db_accion_especifica">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Acci&oacute;n Especifica </th>
		</tr>
       <tr>
		<th>A&ntilde;o:				</th>
		<td ><?=date('Y')+1?></td>
	</tr>
		<tr>
			<th>C&oacute;digo:				</th>
			<td >
				<input name="accion_especifica_db_codigo" type="text" id="accion_especifica_db_codigo"  maxlength="6"
				onchange="consulta_automatica_accion_especifica" onclick="consulta_automatica_accion_especifica"
				message="Introduzca un Codigo de 6 digito para la Accion Especifica."  size="6"
				jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" >
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Centralizada:</th>
		    <td >
				<table  width="100%" class="clear">
					<tr>
						<td>
							<input name="accion_especifica_db_codigo_central" type="text" id="accion_especifica_db_codigo_central"  maxlength="6"
							onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
							message="Introduzca un Codigo para el Accion Central."  size="6"
							jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}"
							jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" >
						</td>
						<td>
							<input name="accion_especifica_db_nombre_central" type="text" id="accion_especifica_db_nombre_central"  size="80" maxlength="200"
						message="Introduzca un Denominacion para la Accion Central." readonly 
						jVal="{valid:/^[a-zA-Z0-9.,()-_ áéíóúÁÉÍÓÚ 1234567890-().,_]{1,100}$/, message:'Denominacion Invalido', styleType:'cover'}"
						jValKey="{valid:/^[a-zA-Z0-9.,()-_ áéíóúÁÉÍÓÚ 1234567890-().,_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}">
						</td>
						<td><img class="btn_consulta_emergente" id="accion_especifica_db_btn_consultar_central" src="imagenes/null.gif" />
							<input name="accion_especifica_db_accion_central" type="hidden" id="accion_especifica_db_accion_central" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Proyecto:</th>
			<td >
				<table  width="100%" class="clear">
					<tr>
						<td>
							<input name="accion_especifica_db_codigo_proyecto" type="text" id="accion_especifica_db_codigo_proyecto"  maxlength="6"
						onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
						message="Introduzca un Codigo para el Proyecto."  size="6"
						jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}"
						jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" >
						</td>
						<td>
							<input name="accion_especifica_db_nombre_proyecto" type="text" id="accion_especifica_db_nombre_proyecto"  size="80" maxlength="200"
						message="Introduzca un Nombre para el Proyecto." readonly 
						jVal="{valid:/^[a-zA-Z0-9.,()-_ áéíóúÁÉÍÓÚ 1234567890-.,_]{1,100}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/^[a-zA-Z0-9.,()-_ áéíóúÁÉÍÓÚ 1234567890.,-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}">
						</td>
						<td><img class="btn_consulta_emergente" id="accion_especifica_db_btn_consultar_proyecto" src="imagenes/null.gif" />
							<input name="accion_especifica_db_proyecto" type="hidden" id="accion_especifica_db_proyecto" />
						</td>
					</tr>
				</table>				
			</td>
		</tr>
		<tr>
			<th>Denominaci&oacute;n:</th>
			<td >&nbsp;<input name="accion_especifica_db_denominacion" type="text" id="accion_especifica_db_denominacion"  size="94" maxlength="300"
				message="Introduzca una Denominacion para la Accion Espesifica." 
			
				></td>
		</tr>
		<tr>
		  <th>Responsable:</th>
		   <td >
		  	 <table  width="100%" class="clear">
				<tr>
					<td>
						<input name="accion_especifica_db_jefe_accion" type="text" id="accion_especifica_db_jefe_accion" size="91" readonly="readonly"  
				message="Elija un jefe de acción especifica" 
				 jVal="{valid:/^[a-zA-Z0-9 .,()-_áéíóúÁÉÍÓÚ1234567890,.-_]{1,100}$/, message:'Nombre de Jefe Invalido', styleType:'cover'}" />
					</td>
					
					<td><img class="btn_consulta_emergente" id="accion_especifica_db_btn_consultar_accion" src="imagenes/null.gif" />
						 <input name="accion_especifica_db_jefe_accion_id" type="hidden" id="accion_especifica_db_jefe_accion_id" />
					</td>
				</tr>
			</table>
       </tr>
		<tr>
			<th>Comentario:</th>
			<td ><textarea name="accion_especifica_db_comentario" id="accion_especifica_db_comentario" cols="87" rows="3" message="Introduzca un Comentario para el Accion Espesifica."></textarea></td>
		</tr>
		
			
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="accion_especifica_db_id" id="accion_especifica_db_id" />
</form>

