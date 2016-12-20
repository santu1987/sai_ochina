<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM organismo";
$rs_organismo =& $conn->Execute($sql);
$xx = 'javascript:var optionnueva = new Option(perfil_organismo.organismo.options[perfil_organismo.organismo.selectedIndex].text,  perfil_organismo.organismo.options[perfil_organismo.organismo.selectedIndex].value); perfil_organismo.organismoSelect.options[perfil_organismo.organismoSelect.length]=optionnueva;
elem=document.getElementById("organismo"); 
				if (elem.selectedIndex!=-1)  
					elem.options[elem.selectedIndex]=null;
';
while (!$rs_organismo->EOF) {	
	$opt_organismo.="<option value='".$rs_organismo->fields('id_organismo')."' ondblclick='".$xx."'>".$rs_organismo->fields("nombre")."</option>";
$rs_organismo->MoveNext();
 }

$sql="SELECT * FROM tipo_nomina ORDER BY id_tipo_nomina";
$rs_tipo =& $conn->Execute($sql);
while (!$rs_tipo->EOF) {
	$opt_tipo.="<option value='".$rs_tipo->fields("id_tipo_nomina")."' >".$rs_tipo->fields("nombre")."</option>";
$rs_tipo->MoveNext();
}
//
//
$sql="SELECT * FROM  calculo_rrhh ORDER BY id_calculo_rrhh";
$rs_calculo =& $conn->Execute($sql);
while (!$rs_calculo->EOF) {
	$opt_calculo.="<option value='".$rs_calculo->fields("id_calculo_rrhh")."' >".$rs_calculo->fields("nombre")."</option>";
$rs_calculo->MoveNext();
}
//
//
?>
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



<script type='text/javascript'>
//
//
function consulta_automatica(){
	$.ajax({
			url:"modulos/rrhh/concepto_variable/pr/sql_consulta_automatica.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_concepto_variable'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
				 arreglo = html.split('*');
				 var tam = getObj('id_calculo_rrhh').length;
				 for(i=1; i<tam; i++){
					 document.form_pr_concepto_variable.id_calculo_rrhh[i].text='';
					 document.form_pr_concepto_variable.id_calculo_rrhh[i].value='';
				 }
				tam = arreglo.length;
				for(i=0; i<tam; i++){
					pos = arreglo[i].indexOf('-');
					tam = arreglo[i].length;
				document.form_pr_concepto_variable.id_calculo_rrhh[i+1].text=arreglo[i].substr(pos+1,tam);	document.form_pr_concepto_variable.id_calculo_rrhh[i+1].value=arreglo[i].substr(0,pos);
				}
			 }
		});
}
//
//
$("#concepto_variable_pr_btn_consultar").click(function() {
	getObj('id_tipo_nomina').disabled=false;												  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_variable/pr/vista.grid_concepto_variable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Concepto Variable', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_tipo_nomina= jQuery("#tipo_nomina").val();  
					var busq_concepto= jQuery("#concepto_variable_pr_concepto").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_concepto_variable.php?busq_tipo_nomina="+busq_tipo_nomina+"&busq_concepto="+busq_concepto,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
			
				$("#tipo_nomina").change(function()
				{
						//if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#concepto_variable_pr_concepto").keypress(function(key)
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
							var busq_tipo_nomina= jQuery("#tipo_nomina").val();
							var busq_concepto= jQuery("#concepto_variable_pr_concepto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_concepto_variable.php?busq_tipo_nomina="+busq_tipo_nomina+"&busq_concepto="+busq_concepto,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/concepto_variable/pr/sql_concepto_variable.php?nd='+nd,
								datatype: "json",
								colNames:['id_concepto_variable','Concepto','id_trabajador','Cedula','Nombre','Apellido','Porcentaje','Monto','Observacion','id_tipo_nomina','id_trabajador'],
								colModel:[
									{name:'id_concepto_variable',index:'id_concepto_variable', width:50,sortable:false,resizable:false,hidden:true},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false},
									{name:'id_trabajador',index:'id_trabajador', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:100,sortable:false,resizable:false},
									{name:'porcentaje',index:'porcentaje', width:100,sortable:false,resizable:false,hidden:true},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false},
									{name:'id_tipo_nomina',index:'id_tipo_nomina', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_concepto',index:'id_concepto', width:50,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('concepto_variable_pr_id_concepto_variable').value=ret.id_concepto_variable;
									getObj('concepto_variable_pr_concepto').value=ret.concepto;
									getObj('concepto_variable_pr_id_trabajador').value=ret.id_trabajador;
									getObj('concepto_variable_pr_cedula_trabajador').value=ret.cedula;
									getObj('concepto_variable_pr_nombre_trabajador').value=ret.nombre;
									getObj('concepto_variable_pr_apellido_trabajador').value=ret.apellido;
									getObj('concepto_variable_pr_porcentaje').value=ret.porcentaje;
									getObj('concepto_variable_pr_monto').value=ret.monto;
									if(ret.porcentaje!=0){
										getObj('concepto_variable_pr_calculo1').checked=true;
										getObj('concepto_variable_pr_calculo2').checked=false;
									}
									else{
										getObj('concepto_variable_pr_calculo1').checked=false;
										getObj('concepto_variable_pr_calculo2').checked=true;
									}
									
									getObj('concepto_variable_pr_comentario').value=ret.observacion;
									getObj('concepto_variable_pr_id_concepto').value=ret.id_concepto;
									for (i=0; i<getObj('id_tipo_nomina').length; i++){
										if(getObj('id_tipo_nomina')[i].value==ret.id_tipo_nomina){
											getObj('id_tipo_nomina').selectedIndex=i;
											
											}
									}
									getObj('id_tipo_nomina').focus();
									update_concepto_variable_consulta();
									getObj('id_tipo_nomina').disabled=true;
									getObj('concepto_variable_pr_opt').disabled=true;
									getObj('concepto_variable_pr_cod').value=ret.id_concepto;
									getObj('concepto_variable_pr_btn_guardar').style.display='none';
									getObj('concepto_variable_pr_btn_actualizar').style.display='';
									getObj('concepto_variable_pr_btn_eliminar').style.display='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#concepto_variable_pr_nombre").focus();
								$('#concepto_variable_pr_concepto').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_concepto_variable',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
$("#concepto_variable_pr_btn_consulta_emergente_concepto").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_variable/pr/vista.grid_concepto_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Concepto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#concepto_variable_pr_nombre").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_concepto_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#concepto_variable_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#concepto_variable_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_concepto_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/concepto_variable/pr/sql_concepto_nom.php?nd='+nd,
								datatype: "json",
								colNames:['Cod','Concepto','Observacion'],
								colModel:[
									{name:'id_concepto',index:'id_concepto', width:50,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('concepto_variable_pr_id_concepto').value=ret.id_concepto;
									getObj('concepto_variable_pr_concepto').value=ret.descripcion;
									getObj('concepto_variable_pr_cod').value=ret.id_concepto;
									dialog.hideAndUnload();
									consulta_automatica();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#concepto_variable_pr_nombre").focus();
								$('#concepto_variable_pr_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_concepto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
function consulta_automatica_concepto_variable()
{
		$.ajax({
			url:"modulos/rrhh/concepto_variable/pr/sql_grid_concepto_variable.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_concepto_variable'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html
			
			if(recordset)
				{				
					
					//alert(html);
					recordset = recordset.split("*");
					monto = recordset[2];
					tam = monto.length;
					monto = monto.substr(1,tam);
					getObj('concepto_variable_pr_porcentaje').value =	recordset[1];
					getObj('concepto_variable_pr_monto').value = monto;
					getObj('concepto_variable_pr_comentario').value = recordset[3];
					getObj('concepto_variable_pr_id_concepto').value = recordset[4];
					getObj('concepto_variable_pr_concepto').value = recordset[5];
					
				}
			if(!recordset){
					getObj('concepto_variable_pr_id_concepto').value = '';
					getObj('concepto_variable_pr_concepto').value = ''; 
					getObj('concepto_variable_pr_id_frecuencia_concepto').value = ''; 
					getObj('concepto_variable_pr_frecuencia_concepto').value = '';
					getObj('concepto_variable_pr_porcentaje').value = '00,00';
					getObj('concepto_variable_pr_monto').value = '0,00';
					getObj('concepto_variable_pr_comentario').value = '';
				}
			 }
		});	 	 
}
//
//
$("#concepto_variable_pr_btn_consulta_emergente_frecuencia_concepto").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_variable/pr/vista.grid_frecuencia_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Frecuencia de concepto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#concepto_variable_pr_nombre").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_frecuencia_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#concepto_variable_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#concepto_variable_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_frecuencia_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/concepto_variable/pr/sql_frecuencia_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Descripcion','Observacion'],
								colModel:[
									{name:'id_frecuencia_concepto',index:'id_frecuencia_concepto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'descripcion',index:'descripcion', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('concepto_variable_pr_id_frecuencia_concepto').value=ret.id_frecuencia_concepto;
									getObj('concepto_variable_pr_frecuencia_concepto').value=ret.descripcion;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#concepto_variable_pr_nombre").focus();
								$('#concepto_variable_pr_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_frecuencia_concepto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
$("#concepto_variable_pr_btn_consulta_emergente_trabajador").click(function() {
	if(getObj('id_tipo_nomina').value!=0){																	  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_variable/pr/vista.grid_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#concepto_variable_pr_nombre").val();  
					var busq_apellido= jQuery("#concepto_variable_pr_apellido").val();  
					var busq_nomina = getObj('id_tipo_nomina').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido+"&busq_nomina="+busq_nomina,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#concepto_variable_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#concepto_variable_pr_apellido").keypress(function(key)
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
							var busq_nombre= jQuery("#concepto_variable_pr_nombre").val();
							var busq_apellido= jQuery("#concepto_variable_pr_apellido").val();
							var busq_nomina = getObj('id_tipo_nomina').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_variable/pr/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido+"&busq_nomina="+busq_nomina,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/concepto_variable/pr/sql_trabajador_nom.php?busq_nomina='+getObj("id_tipo_nomina").value,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Apellido','Unidad','Cargo'],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:100,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:100,sortable:false,resizable:false},
									{name:'cargo',index:'cargo', width:100,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('concepto_variable_pr_id_trabajador').value=ret.id_trabajador;
									getObj('concepto_variable_pr_cedula_trabajador').value=ret.cedula;
									getObj('concepto_variable_pr_nombre_trabajador').value=ret.nombre;
									getObj('concepto_variable_pr_apellido_trabajador').value=ret.apellido;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#concepto_variable_pr_nombre").focus();
								$('#concepto_variable_pr_nombre').alpha({allow:' '});
								$('#concepto_variable_pr_apellido').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_trabajador',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	}
	});
//
//
$("#concepto_variable_pr_btn_guardar").click(function() {
	getObj('id_tipo_nomina').disabled=false;
	if(getObj('concepto_variable_pr_opt').value==1)
		direc = "modulos/rrhh/concepto_variable/pr/sql.registrar_concepto_variable2.php";
	if(getObj('concepto_variable_pr_opt').value==2)
		direc = "modulos/rrhh/concepto_variable/pr/sql.registrar_concepto_variable.php";	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: direc,
		data:dataForm('form_pr_concepto_variable'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			getObj('id_tipo_nomina').disabled=true;
			if (html=="Registrado")
			{
				limpiar();
				setBarraEstado(mensaje[registro_exitoso],true,true);
				getObj('concepto_variable_pr_id_concepto_variable').value='';
				getObj('concepto_variable_pr_id_concepto').value = '';
				getObj('concepto_variable_pr_id_trabajador').value='';
				getObj('concepto_variable_pr_cedula_trabajador').value='';
				getObj('concepto_variable_pr_nombre_trabajador').value='';
				getObj('concepto_variable_pr_apellido_trabajador').value='';
				getObj('concepto_variable_pr_concepto').value = '';
				getObj('concepto_variable_pr_porcentaje').value = '00,00';
				getObj('concepto_variable_pr_monto').value = '0,00';
				getObj('concepto_variable_pr_comentario').value = '';
				getObj('id_tipo_nomina').selectedIndex = 0;
				getObj('concepto_variable_pr_calculo1').checked=true;
				getObj('concepto_variable_pr_calculo2').checked=false;
				
				update_concepto_variable();
				update_concepto_variable_consulta();
				getObj('id_tipo_nomina').disabled=false;
				
			}
			else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
			else
			{
				setBarraEstado(html);
			}
		}
	});
});
//
//
$("#concepto_variable_pr_btn_actualizar").click(function() {
	getObj('id_tipo_nomina').disabled=false;
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: 'modulos/rrhh/concepto_variable/pr/sql.actualizar_concepto_variable.php',
		data:dataForm('form_pr_concepto_variable'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			getObj('id_tipo_nomina').disabled=true;
			if (html=="Actualizado")
			{
				setBarraEstado(mensaje[registro_exitoso],true,true);
				limpiar();
				getObj('concepto_variable_pr_id_concepto_variable').value='';
				getObj('concepto_variable_pr_id_concepto').value = '';
				getObj('concepto_variable_pr_id_trabajador').value='';
				getObj('concepto_variable_pr_cedula_trabajador').value='';
				getObj('concepto_variable_pr_nombre_trabajador').value='';
				getObj('concepto_variable_pr_apellido_trabajador').value='';
				getObj('concepto_variable_pr_concepto').value = '';
				getObj('concepto_variable_pr_porcentaje').value = '00,00';
				getObj('concepto_variable_pr_monto').value = '0,00';
				getObj('concepto_variable_pr_comentario').value = '';
				getObj('id_tipo_nomina').selectedIndex = 0;
				getObj('concepto_variable_pr_opt').selectedIndex=0;
				getObj('concepto_variable_pr_calculo1').checked=true;
				getObj('concepto_variable_pr_calculo2').checked=false;
				
				update_concepto_variable();
				update_concepto_variable_consulta();
				getObj('id_tipo_nomina').disabled=false;
				getObj('concepto_variable_pr_opt').disabled=false;
				getObj('concepto_variable_pr_btn_actualizar').style.display='none';
				getObj('concepto_variable_pr_btn_guardar').style.display='';
			}
			else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
			else
			{
				setBarraEstado(html);
			}
		}
	});
});
//
//
$("#concepto_variable_pr_cod").change(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: 'modulos/rrhh/concepto_variable/pr/sql_consulta_automatica_cf.php',
		data:dataForm('form_pr_concepto_variable'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			if(html!=''){
				arreglo = html.split('*');
				getObj('concepto_variable_pr_id_concepto').value=arreglo[0];
				getObj('concepto_variable_pr_concepto').value=arreglo[1];
			}
			else if(html==''){
				getObj('concepto_variable_pr_id_concepto').value='';
				getObj('concepto_variable_pr_cod').value='';
				getObj('concepto_variable_pr_concepto').value='';
			}
			setBarraEstado("");
		}
	});
});
//
$("#concepto_variable_pr_btn_eliminar").click(function() {
	 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />&iquest;ESTA SEGURO QUE DESEA ELIMINAR ESTE REGISTRO?</p></div>", ["ACEPTAR", "CANCELAR"], function(val) {
      if(val=='ACEPTAR'){
		  //
		  $.ajax ({
		url: 'modulos/rrhh/concepto_variable/pr/sql.eliminar_concepto_variable.php',
		data:dataForm('form_pr_concepto_variable'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			if(html=='Eliminado'){
				limpiar();
				setBarraEstado("");
			}
		}
	});
		  //
	  }
    }, {title: "OCHINA"});


});

//
function limpiar(){
	getObj('concepto_variable_pr_id_concepto_variable').value = '';
	getObj('concepto_variable_pr_id_concepto').value ='';
	getObj('concepto_variable_pr_id_trabajador').value='';
	getObj('concepto_variable_pr_opt').disabled=false;
	getObj('concepto_variable_pr_opt').selectedIndex=0;
	ocultar_grupos();
	mostrar_trabajador();
	getObj('concepto_variable_pr_concepto').value = '';
	getObj('concepto_variable_pr_cedula_trabajador').value='';
	getObj('concepto_variable_pr_nombre_trabajador').value='';
	getObj('concepto_variable_pr_apellido_trabajador').value='';
	getObj('concepto_variable_pr_porcentaje').value = '00,00';
	getObj('concepto_variable_pr_monto').value = '0,00';
	getObj('concepto_variable_pr_comentario').value = '';
	getObj('id_tipo_nomina').disabled=false;
	getObj('id_tipo_nomina').selectedIndex = 0;
	getObj('concepto_variable_pr_btn_eliminar').style.display='none';
	getObj('concepto_variable_pr_calculo1').checked=true;
	getObj('concepto_variable_pr_calculo2').checked=false;
	getObj('concepto_variable_pr_cod').value='';
	
	getObj('concepto_variable_pr_btn_actualizar').style.display='none';
	getObj('concepto_variable_pr_btn_guardar').style.display='';
	update_concepto_variable();
	update_concepto_variable_consulta();
}
//
//
$('#concepto_variable_pr_cod').numeric({allow:' '});
//
//
$("#concepto_variable_pr_btn_limpiar").click(function() {
	limpiar();
});
//
function update_concepto_variable()
{ 
	//consulta_automatica_concepto_variable();
	$("#trabajador").removeOption(/./);
	$("#trabajador").ajaxAddOption("modulos/rrhh/concepto_variable/pr/cmb.sql.trabajador.php",{id_tipo_nomina:$(this).val()},false);
	//$("#trabajadorSelect").removeOption(/./);
	//$("#trabajadorSelect").ajaxAddOption("modulos/rrhh/concepto_variable/pr/cmb.sql.programaSelect.php",{id_tipo_nomina:$(this).val()},false);
}
//
//
$("#concepto_variable_pr_opt").change(function() {
	if(getObj('concepto_variable_pr_opt').value==1){
		mostrar_trabajador();
		ocultar_grupos();
	}
	if(getObj('concepto_variable_pr_opt').value==2){
		ocultar_trabajador();
		mostrar_grupos();
	}
});
//
//
function ocultar_trabajador(){
  	getObj('concepto_variable_pr_tr_cedula').style.display='none';
	getObj('concepto_variable_pr_tr_nombre').style.display='none';
	getObj('concepto_variable_pr_tr_apellido').style.display='none';
}
//
function mostrar_trabajador(){
	getObj('concepto_variable_pr_tr_cedula').style.display='';
	getObj('concepto_variable_pr_tr_nombre').style.display='';
	getObj('concepto_variable_pr_tr_apellido').style.display='';
} 
//
function ocultar_grupos(){
	getObj('concepto_variable_pr_tr_grupos').style.display='none';
}
//
function mostrar_grupos(){
	getObj('concepto_variable_pr_tr_grupos').style.display='';
}
//
function opt(){
	if(getObj('concepto_variable_pr_calculo1').checked){
		getObj('concepto_variable_pr_monto').value = '0,00';
		getObj('concepto_variable_pr_monto').disabled=true;
		getObj('concepto_variable_pr_porcentaje').disabled=false;
	}
	if(getObj('concepto_variable_pr_calculo2').checked){
		getObj('concepto_variable_pr_porcentaje').value='00,00';
		getObj('concepto_variable_pr_porcentaje').disabled=true;
		getObj('concepto_variable_pr_monto').disabled=false;
	}
}
//
function update_concepto_variable_consulta()
{ 
	//consulta_automatica_concepto_variable();
	$("#trabajador").removeOption(/./);
	$("#trabajador").ajaxAddOption("modulos/rrhh/concepto_variable/pr/cmb.sql.trabajador_consulta.php",{id_tipo_nomina:$(this).val(),concepto_variable_pr_id_concepto_variable:$("#concepto_variable_pr_id_concepto_variable").val()},false);
	$("#trabajadorSelect").removeOption(/./);
	$("#trabajadorSelect").ajaxAddOption("modulos/rrhh/concepto_variable/pr/cmb.sql.programaSelect_consulta.php",{id_tipo_nomina:$(this).val(),concepto_variable_pr_id_concepto_variable:$("#concepto_variable_pr_id_concepto_variable").val()},false);
}

$("#id_tipo_nomina").change(update_concepto_variable);

$("#id_tipo_nomina").focus(update_concepto_variable_consulta);

$("#trabajador").dblclick(function() {
   copyItemList('trabajador','trabajadorSelect');
});

$("#trabajadorSelect").dblclick(function() {
   copyItemList('trabajadorSelect','trabajador');
});
$("#concepto_variable_pr_cedula_trabajador").change(function() {												
	$.ajax({
			url:"modulos/rrhh/concepto_variable/pr/sql_consulta_automatica_trabajador.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_concepto_variable'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
				if(html!=''){
					arreglo = html.split("*");
					getObj('concepto_variable_pr_id_trabajador').value=arreglo[0];
					getObj('concepto_variable_pr_cedula_trabajador').value=arreglo[1];
					getObj('concepto_variable_pr_nombre_trabajador').value=arreglo[2];
					getObj('concepto_variable_pr_apellido_trabajador').value=arreglo[3];
				}
				else if(html==''){
					getObj('concepto_variable_pr_id_trabajador').value='';
					getObj('concepto_variable_pr_cedula_trabajador').value='';
					getObj('concepto_variable_pr_nombre_trabajador').value='';
					getObj('concepto_variable_pr_apellido_trabajador').value='';
				}
			 }
		});
});
</script>
<div id="botonera">
	<img id="concepto_variable_pr_btn_limpiar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="concepto_variable_pr_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
    <img id="concepto_variable_pr_btn_eliminar" style="display:none" class="btn_eliminar"src="imagenes/null.gif" />
    <img id="concepto_variable_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />
    <img id="concepto_variable_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" name="form_pr_concepto_variable" id="form_pr_concepto_variable" >
<input type="hidden" name="concepto_variable_pr_id_concepto_variable" id="concepto_variable_pr_id_concepto_variable" />

<input type="hidden" name="concepto_variable_pr_id_concepto" id="concepto_variable_pr_id_concepto"/>
<input type="hidden" name="concepto_variable_pr_id_trabajador" id="concepto_variable_pr_id_trabajador" />
<input type="hidden" name="concepto_variable_pr_fechact" id="concepto_variable_db_fechact" value="<?php echo date("d-m-Y");?>"/>

	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Concepto Variable</th>
		</tr>
		<tr>
			<th>Tipo de Nomina:</th>
			<td><!--<input type="button" onclick="verProps('id_perfil')" value="ver" />-->
			  <select name="id_tipo_nomina" id="id_tipo_nomina">
			    <option value="0">--SELECCIONE--</option>
			    <?=$opt_tipo?>
	        </select></td>
		</tr>
        <tr>
			<th>Concepto</th>
			<td><ul class="input_con_emergente">
				<li>
           <input name="concepto_variable_pr_cod" type="text" id="concepto_variable_pr_cod" size="5" maxlength="6"/>     
           <input name="concepto_variable_pr_concepto" type="text"  id="concepto_variable_pr_concepto" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'concepto Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Concepto: '+$(this).val()]}"/>
           </li>
				<li id="concepto_variable_pr_btn_consulta_emergente_concepto" class="btn_consulta_emergente"></li>
			</ul>
			</td>
		</tr>
        <tr>
			<th>Carga de Concepto</th>
			<td><select name="concepto_variable_pr_opt" id="concepto_variable_pr_opt"
            message="Seleccione como desea hacer la carga de concepto variable">
			  <option value="1">Individual</option>
			  <option value="2">Grupos</option>
		    </select></td>
		</tr>
        <tr id="concepto_variable_pr_tr_cedula">
			<th>Trabajador</th>
			<td><ul class="input_con_emergente">
				<li>
           <input name="concepto_variable_pr_cedula_trabajador" type="text"  id="concepto_variable_pr_cedula_trabajador" maxlength="60" size="20"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'concepto Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Concepto: '+$(this).val()]}" message="Escriba o Seleccione el Número de Cédula del trabajador"/>
           </li>
				<li id="concepto_variable_pr_btn_consulta_emergente_trabajador" class="btn_consulta_emergente"></li>
			</ul>
			</td>
		</tr>
        <tr id="concepto_variable_pr_tr_nombre"> 
			<th>Nombre</th>
			<td>
            <input readonly="true" name="concepto_variable_pr_nombre_trabajador" type="text"  id="concepto_variable_pr_nombre_trabajador" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
         message="Introduzca el Nombre del Trabajador"        jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
			</td>
		</tr>
        <tr id="concepto_variable_pr_tr_apellido">
			<th>Apellido</th>
			<td><input readonly="true" name="concepto_variable_pr_apellido_trabajador" type="text"  id="concepto_variable_pr_apellido_trabajador" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}" message="Introduzca el Apellido del Trabajador"   jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}"/>
			</td>
		</tr>
		<tr id="concepto_variable_pr_tr_grupos" style="display:none">
			<th>Trabajadores:	</th>	
			<td>
			<table>
			<tr>
			<td rowspan="2">
			<select style="width:200px; height:80px;" name="trabajador" size="15"  multiple="MULTIPLE" id="trabajador">
				
			</select>			</td>
			<td>
			<input style="width:30px;" type="button" value=">" onClick="copyItemList('trabajador','trabajadorSelect')">
			<input style="width:30px;" type="button" value=">>" onClick="copyItemList('trabajador','trabajadorSelect',true)">			</td>	

			<td rowspan="2">	 
			<select style="width:200px; height:80px;" name="trabajadorSelect" size="15"  multiple="MULTIPLE" id="trabajadorSelect">
			</select>
			<!--<input type="button" onclick="verProps('modulo')" value="ver" />-->			</td>
			</tr>	
			<tr>
				<td>
				<input style="width:30px;" type="button" value="<" onClick="copyItemList('trabajadorSelect','trabajador')">
				<input style="width:30px;" type="button" value="<<" onClick="copyItemList('trabajadorSelect','trabajador',true)">				</td>
			</tr>
			</table>			</td>
		</tr>
        
        <tr style="display:none">
			<th>Valor del Concepto</th>
			<td>
			  <input name="concepto_variable_pr_calculo" type="radio" id="concepto_variable_pr_calculo1" value="1" checked="checked" onclick="opt()"/>
		      <strong>Porcentaje </strong>
		      <input type="radio" name="concepto_variable_pr_calculo" id="concepto_variable_pr_calculo2" value="2" onclick="opt()"/>
			  <strong>Monto</strong></td>
		</tr>
        <tr style="display:none">
			<th>Porcentaje</th>
			<td><input name="concepto_variable_pr_porcentaje" type="text" id="concepto_variable_pr_porcentaje" size="10" alt="signed-decimal-im" style="text-align:right"/> 
		    <strong>%</strong></td>
		</tr>
        <tr>
			<th>Monto</th>
			<td><input name="concepto_variable_pr_monto" type="text"  id="concepto_variable_pr_monto" maxlength="60" size="10" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Sueldo Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Sueldo: '+$(this).val()]}" message="Salario Actual del Trabajador"/> 
			</td>
		</tr>
        <tr>
			<th>Observacion</th>
			<td><label>
		    <textarea name="concepto_variable_pr_comentario" id="concepto_variable_pr_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. "></textarea>
		  </label>
			  </td>
		</tr>
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr> 
	</table>
	
</form>