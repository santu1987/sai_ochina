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
$("#conceptos_fijos_pr_cedula_trabajador").change(function() {												
	if(getObj('id_tipo_nomina').value!=0){													   
	$.ajax({
			url:"modulos/rrhh/concepto_fijo/pr/sql_consulta_automatica_trabajador.php?id_tipo_nomina="+getObj('id_tipo_nomina').value,
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_conceptos_fijos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
				if(html!=''){
					arreglo = html.split("*");
					getObj('conceptos_fijos_pr_id_trabajador').value=arreglo[0];
					getObj('conceptos_fijos_pr_cedula_trabajador').value=arreglo[1];
					getObj('conceptos_fijos_pr_nombre_trabajador').value=arreglo[2];
					getObj('conceptos_fijos_pr_apellido_trabajador').value=arreglo[3];
				}
				else if(html==''){
					getObj('conceptos_fijos_pr_id_trabajador').value='';
					getObj('conceptos_fijos_pr_cedula_trabajador').value='';
					getObj('conceptos_fijos_pr_nombre_trabajador').value='';
					getObj('conceptos_fijos_pr_apellido_trabajador').value='';
				}
			 }
		});
	}
});
//
//
function consulta_automatica(){
	$.ajax({
			url:"modulos/rrhh/concepto_fijo/pr/sql_consulta_automatica.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_conceptos_fijos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
				 arreglo = html.split('*');
				 var tam = getObj('id_calculo_rrhh').length;
				 for(i=1; i<tam; i++){
					 document.form_pr_conceptos_fijos.id_calculo_rrhh[i].text='';
					 document.form_pr_conceptos_fijos.id_calculo_rrhh[i].value='';
				 }
				tam = arreglo.length;
				for(i=0; i<tam; i++){
					pos = arreglo[i].indexOf('-');
					tam = arreglo[i].length;
				document.form_pr_conceptos_fijos.id_calculo_rrhh[i+1].text=arreglo[i].substr(pos+1,tam);	document.form_pr_conceptos_fijos.id_calculo_rrhh[i+1].value=arreglo[i].substr(0,pos);
				}
			 }
		});
}
//
//
$("#conceptos_fijos_pr_btn_consultar").click(function() {
	getObj('id_tipo_nomina').disabled=false;												  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_fijo/pr/vista.grid_concepto_fijo.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Conceptos Fijos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_tipo_nomina= jQuery("#tipo_nomina").val();  
					var busq_conceptos= jQuery("#conceptos_fijos_pr_conceptos").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_concepto_fijo.php?busq_tipo_nomina="+busq_tipo_nomina+"&busq_conceptos="+busq_conceptos,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
			
				$("#tipo_nomina").change(function()
				{
						//if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#conceptos_fijos_pr_conceptos").keypress(function(key)
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
							var busq_conceptos= jQuery("#conceptos_fijos_pr_conceptos").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_concepto_fijo.php?busq_tipo_nomina="+busq_tipo_nomina+"&busq_conceptos="+busq_conceptos,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/concepto_fijo/pr/sql_concepto_fijo.php?nd='+nd,
								datatype: "json",
								colNames:['id_concepto_fijos','Concepto','id_trabajador','Cedula','Nombre','Apellido','Porcentaje','Monto','Observacion','id_tipo_nomina','id_trabajador'],
								colModel:[
									{name:'id_concepto_fijos',index:'id_concepto_fijos', width:50,sortable:false,resizable:false,hidden:true},
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
									getObj('conceptos_fijos_pr_id_concepto_fijo').value=ret.id_concepto_fijos;
									getObj('conceptos_fijos_pr_concepto').value=ret.concepto;
									getObj('conceptos_fijos_pr_id_trabajador').value=ret.id_trabajador;
									getObj('conceptos_fijos_pr_cod').value=ret.id_concepto;
									getObj('conceptos_fijos_pr_cedula_trabajador').value=ret.cedula;
									getObj('conceptos_fijos_pr_nombre_trabajador').value=ret.nombre;
									getObj('conceptos_fijos_pr_apellido_trabajador').value=ret.apellido;
									getObj('conceptos_fijos_pr_porcentaje').value=ret.porcentaje;
									
									getObj('conceptos_fijos_pr_monto').value=ret.monto;
									if(ret.porcentaje!=0){
										getObj('conceptos_fijos_pr_calculo1').checked=true;
										getObj('conceptos_fijos_pr_calculo2').checked=false;
									}
									else{
										getObj('conceptos_fijos_pr_calculo1').checked=false;
										getObj('conceptos_fijos_pr_calculo2').checked=true;
									}
									
									getObj('conceptos_fijos_pr_comentario').value=ret.observacion;
									getObj('conceptos_fijos_pr_id_concepto').value=ret.id_concepto;
									for (i=0; i<getObj('id_tipo_nomina').length; i++){
										if(getObj('id_tipo_nomina')[i].value==ret.id_tipo_nomina){
											getObj('id_tipo_nomina').selectedIndex=i;
											
											}
									}
									getObj('id_tipo_nomina').focus();
									update_conceptos_fijos_consulta();
									getObj('id_tipo_nomina').disabled=true;
									getObj('conceptos_fijos_pr_opt').disabled=true;
									getObj('conceptos_fijos_pr_btn_guardar').style.display='none';
									getObj('conceptos_fijos_pr_btn_actualizar').style.display='';
									getObj('conceptos_fijos_pr_btn_eliminar').style.display='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#conceptos_fijos_pr_nombre").focus();
								$('#conceptos_fijos_pr_conceptos').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_concepto_fijos',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
$("#conceptos_fijos_pr_btn_consulta_emergente_concepto").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_fijo/pr/vista.grid_concepto_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Conceptos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#conceptos_fijos_pr_nombre").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_concepto_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#conceptos_fijos_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#conceptos_fijos_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_concepto_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/concepto_fijo/pr/sql_concepto_nom.php?nd='+nd,
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
									getObj('conceptos_fijos_pr_id_concepto').value=ret.id_concepto;
									getObj('conceptos_fijos_pr_cod').value=ret.id_concepto;
									getObj('conceptos_fijos_pr_concepto').value=ret.descripcion;
									dialog.hideAndUnload();
									consulta_automatica();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#conceptos_fijos_pr_nombre").focus();
								$('#conceptos_fijos_pr_nombre').alpha({allow:' '});
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
function consulta_automatica_concepto_fijo()
{
		$.ajax({
			url:"modulos/rrhh/concepto_fijo/pr/sql_grid_conceptos_fijos.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_conceptos_fijos'), 
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
					getObj('conceptos_fijos_pr_porcentaje').value =	recordset[1];
					getObj('conceptos_fijos_pr_monto').value = monto;
					getObj('conceptos_fijos_pr_comentario').value = recordset[3];
					getObj('conceptos_fijos_pr_id_concepto').value = recordset[4];
					getObj('conceptos_fijos_pr_concepto').value = recordset[5];
					
				}
			if(!recordset){
					getObj('conceptos_fijos_pr_id_concepto').value = '';
					getObj('conceptos_fijos_pr_concepto').value = ''; 
					getObj('conceptos_fijos_pr_id_frecuencia_concepto').value = ''; 
					getObj('conceptos_fijos_pr_frecuencia_concepto').value = '';
					getObj('conceptos_fijos_pr_porcentaje').value = '00,00';
					getObj('conceptos_fijos_pr_monto').value = '0,00';
					getObj('conceptos_fijos_pr_comentario').value = '';
				}
			 }
		});	 	 
}
//
//
$("#conceptos_fijos_pr_btn_consulta_emergente_frecuencia_concepto").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_fijo/pr/vista.grid_frecuencia_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Frecuencia de Conceptos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#conceptos_fijos_pr_nombre").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_frecuencia_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#conceptos_fijos_pr_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#conceptos_fijos_pr_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_frecuencia_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/concepto_fijo/pr/sql_frecuencia_nom.php?nd='+nd,
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
									getObj('conceptos_fijos_pr_id_frecuencia_concepto').value=ret.id_frecuencia_concepto;
									getObj('conceptos_fijos_pr_frecuencia_concepto').value=ret.descripcion;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#conceptos_fijos_pr_nombre").focus();
								$('#conceptos_fijos_pr_nombre').alpha({allow:' '});
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
$("#conceptos_fijos_pr_btn_consulta_emergente_trabajador").click(function() {
	if(getObj('id_tipo_nomina').value!=0){																	  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/concepto_fijo/pr/vista.grid_trabajador_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajador', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#conceptos_fijos_pr_nombre").val();  
					var busq_apellido= jQuery("#conceptos_fijos_pr_apellido").val();  
					var busq_nomina = getObj('id_tipo_nomina').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido+"&busq_nomina="+busq_nomina,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#conceptos_fijos_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#conceptos_fijos_pr_apellido").keypress(function(key)
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
							var busq_nombre= jQuery("#conceptos_fijos_pr_nombre").val();
							var busq_apellido= jQuery("#conceptos_fijos_pr_apellido").val();
							var busq_nomina = getObj('id_tipo_nomina').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/concepto_fijo/pr/sql_trabajador_nom.php?busq_nombre="+busq_nombre+"&busq_apellido="+busq_apellido+"&busq_nomina="+busq_nomina,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/concepto_fijo/pr/sql_trabajador_nom.php?busq_nomina='+getObj("id_tipo_nomina").value,
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
									getObj('conceptos_fijos_pr_id_trabajador').value=ret.id_trabajador;
									getObj('conceptos_fijos_pr_cedula_trabajador').value=ret.cedula;
									getObj('conceptos_fijos_pr_nombre_trabajador').value=ret.nombre;
									getObj('conceptos_fijos_pr_apellido_trabajador').value=ret.apellido;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#conceptos_fijos_pr_nombre").focus();
								$('#conceptos_fijos_pr_nombre').alpha({allow:' '});
								$('#conceptos_fijos_pr_apellido').alpha({allow:' '});
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
$("#conceptos_fijos_pr_btn_guardar").click(function() {
	getObj('id_tipo_nomina').disabled=false;
	if(getObj('conceptos_fijos_pr_opt').value==1)
		direc = "modulos/rrhh/concepto_fijo/pr/sql.registrar_concepto_fijo2.php";
	if(getObj('conceptos_fijos_pr_opt').value==2)
		direc = "modulos/rrhh/concepto_fijo/pr/sql.registrar_concepto_fijo.php";	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: direc,
		data:dataForm('form_pr_conceptos_fijos'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			getObj('id_tipo_nomina').disabled=true;
			if (html=="Registrado")
			{
				limpiar();
				setBarraEstado(mensaje[registro_exitoso],true,true);
				getObj('conceptos_fijos_pr_id_concepto_fijo').value='';
				getObj('conceptos_fijos_pr_id_concepto').value = '';
				getObj('conceptos_fijos_pr_id_trabajador').value='';
				getObj('conceptos_fijos_pr_cedula_trabajador').value='';
				getObj('conceptos_fijos_pr_nombre_trabajador').value='';
				getObj('conceptos_fijos_pr_apellido_trabajador').value='';
				getObj('conceptos_fijos_pr_concepto').value = '';
				getObj('conceptos_fijos_pr_porcentaje').value = '00,00';
				getObj('conceptos_fijos_pr_monto').value = '0,00';
				getObj('conceptos_fijos_pr_comentario').value = '';
				getObj('id_tipo_nomina').selectedIndex = 0;
				getObj('conceptos_fijos_pr_calculo1').checked=true;
				getObj('conceptos_fijos_pr_calculo2').checked=false;
				
				update_conceptos_fijos();
				update_conceptos_fijos_consulta();
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
$("#conceptos_fijos_pr_btn_actualizar").click(function() {
	getObj('id_tipo_nomina').disabled=false;
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: 'modulos/rrhh/concepto_fijo/pr/sql.actualizar_concepto_fijo.php',
		data:dataForm('form_pr_conceptos_fijos'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			getObj('id_tipo_nomina').disabled=true;
			if (html=="Actualizado")
			{
				setBarraEstado(mensaje[registro_exitoso],true,true);
				limpiar();
				getObj('conceptos_fijos_pr_id_concepto_fijo').value='';
				getObj('conceptos_fijos_pr_id_concepto').value = '';
				getObj('conceptos_fijos_pr_id_trabajador').value='';
				getObj('conceptos_fijos_pr_cedula_trabajador').value='';
				getObj('conceptos_fijos_pr_nombre_trabajador').value='';
				getObj('conceptos_fijos_pr_apellido_trabajador').value='';
				getObj('conceptos_fijos_pr_concepto').value = '';
				getObj('conceptos_fijos_pr_porcentaje').value = '00,00';
				getObj('conceptos_fijos_pr_monto').value = '0,00';
				getObj('conceptos_fijos_pr_comentario').value = '';
				getObj('id_tipo_nomina').selectedIndex = 0;
				getObj('conceptos_fijos_pr_opt').selectedIndex=0;
				getObj('conceptos_fijos_pr_calculo1').checked=true;
				getObj('conceptos_fijos_pr_calculo2').checked=false;
				
				update_conceptos_fijos();
				update_conceptos_fijos_consulta();
				getObj('id_tipo_nomina').disabled=false;
				getObj('conceptos_fijos_pr_opt').disabled=false;
				getObj('conceptos_fijos_pr_btn_actualizar').style.display='none';
				getObj('conceptos_fijos_pr_btn_guardar').style.display='';
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
$("#conceptos_fijos_pr_btn_eliminar").click(function() {
	 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />&iquest;ESTA SEGURO QUE DESEA ELIMINAR ESTE REGISTRO?</p></div>", ["ACEPTAR", "CANCELAR"], function(val) {
      if(val=='ACEPTAR'){
		  //
		  $.ajax ({
		url: 'modulos/rrhh/concepto_fijo/pr/sql.eliminar_concepto_fijo.php',
		data:dataForm('form_pr_conceptos_fijos'),
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
//
$("#conceptos_fijos_pr_cod").change(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: 'modulos/rrhh/concepto_fijo/pr/sql_consulta_automatica_cf.php',
		data:dataForm('form_pr_conceptos_fijos'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			if(html!=''){
				arreglo = html.split('*');
				getObj('conceptos_fijos_pr_id_concepto').value=arreglo[0];
				getObj('conceptos_fijos_pr_concepto').value=arreglo[1];
			}
			else if(html==''){
				getObj('conceptos_fijos_pr_id_concepto').value='';
				getObj('conceptos_fijos_pr_cod').value='';
				getObj('conceptos_fijos_pr_concepto').value='';
			}
			setBarraEstado("");
		}
	});
});
//
//
function limpiar(){
	getObj('conceptos_fijos_pr_id_concepto_fijo').value = '';
	getObj('conceptos_fijos_pr_id_concepto').value ='';
	getObj('conceptos_fijos_pr_id_trabajador').value='';
	getObj('conceptos_fijos_pr_cod').value='';
	getObj('conceptos_fijos_pr_opt').disabled=false;
	getObj('conceptos_fijos_pr_opt').selectedIndex=0;
	ocultar_grupos();
	mostrar_trabajador();
	getObj('conceptos_fijos_pr_concepto').value = '';
	getObj('conceptos_fijos_pr_cedula_trabajador').readOnly = true;
	getObj('conceptos_fijos_pr_cedula_trabajador').value='';
	getObj('conceptos_fijos_pr_nombre_trabajador').value='';
	getObj('conceptos_fijos_pr_apellido_trabajador').value='';
	getObj('conceptos_fijos_pr_porcentaje').value = '00,00';
	getObj('conceptos_fijos_pr_monto').value = '0,00';
	getObj('conceptos_fijos_pr_comentario').value = '';
	getObj('id_tipo_nomina').disabled=false;
	getObj('id_tipo_nomina').selectedIndex = 0;
	getObj('conceptos_fijos_pr_calculo1').checked=true;
	getObj('conceptos_fijos_pr_calculo2').checked=false;
	
	getObj('conceptos_fijos_pr_btn_actualizar').style.display='none';
	getObj('conceptos_fijos_pr_btn_guardar').style.display='';
	getObj('conceptos_fijos_pr_btn_eliminar').style.display='none';
	update_conceptos_fijos();
	update_conceptos_fijos_consulta();
}
//
//
$('#conceptos_fijos_pr_cod').numeric({allow:' '});
$('#conceptos_fijos_pr_cedula_trabajador').numeric({allow:'V-E-'});
//
//
$("#conceptos_fijos_pr_btn_limpiar").click(function() {
	limpiar();
});
//
function update_conceptos_fijos()
{ 
	//consulta_automatica_concepto_fijo();
	$("#trabajador").removeOption(/./);
	$("#trabajador").ajaxAddOption("modulos/rrhh/concepto_fijo/pr/cmb.sql.trabajador.php",{id_tipo_nomina:$(this).val()},false);
	//$("#trabajadorSelect").removeOption(/./);
	//$("#trabajadorSelect").ajaxAddOption("modulos/rrhh/concepto_fijo/pr/cmb.sql.programaSelect.php",{id_tipo_nomina:$(this).val()},false);
}
//
//
$("#conceptos_fijos_pr_opt").change(function() {
	if(getObj('conceptos_fijos_pr_opt').value==1){
		mostrar_trabajador();
		ocultar_grupos();
	}
	if(getObj('conceptos_fijos_pr_opt').value==2){
		ocultar_trabajador();
		mostrar_grupos();
	}
});
//
//
function ocultar_trabajador(){
  	getObj('conceptos_fijos_pr_tr_cedula').style.display='none';
	getObj('conceptos_fijos_pr_tr_nombre').style.display='none';
	getObj('conceptos_fijos_pr_tr_apellido').style.display='none';
}
//
function mostrar_trabajador(){
	getObj('conceptos_fijos_pr_tr_cedula').style.display='';
	getObj('conceptos_fijos_pr_tr_nombre').style.display='';
	getObj('conceptos_fijos_pr_tr_apellido').style.display='';
} 
//
function ocultar_grupos(){
	getObj('conceptos_fijos_pr_tr_grupos').style.display='none';
}
//
function mostrar_grupos(){
	getObj('conceptos_fijos_pr_tr_grupos').style.display='';
}
//
function opt(){
	if(getObj('conceptos_fijos_pr_calculo1').checked){
		getObj('conceptos_fijos_pr_monto').value = '0,00';
		getObj('conceptos_fijos_pr_monto').disabled=true;
		getObj('conceptos_fijos_pr_porcentaje').disabled=false;
	}
	if(getObj('conceptos_fijos_pr_calculo2').checked){
		getObj('conceptos_fijos_pr_porcentaje').value='00,00';
		getObj('conceptos_fijos_pr_porcentaje').disabled=true;
		getObj('conceptos_fijos_pr_monto').disabled=false;
	}
}
//
function update_conceptos_fijos_consulta()
{ 
	//consulta_automatica_concepto_fijo();
	$("#trabajador").removeOption(/./);
	$("#trabajador").ajaxAddOption("modulos/rrhh/concepto_fijo/pr/cmb.sql.trabajador_consulta.php",{id_tipo_nomina:$(this).val(),conceptos_fijos_pr_id_concepto_fijo:$("#conceptos_fijos_pr_id_concepto_fijo").val()},false);
	$("#trabajadorSelect").removeOption(/./);
	$("#trabajadorSelect").ajaxAddOption("modulos/rrhh/concepto_fijo/pr/cmb.sql.programaSelect_consulta.php",{id_tipo_nomina:$(this).val(),conceptos_fijos_pr_id_concepto_fijo:$("#conceptos_fijos_pr_id_concepto_fijo").val()},false);
}

$("#id_tipo_nomina").change(update_conceptos_fijos);

$("#id_tipo_nomina").focus(update_conceptos_fijos_consulta);

$("#trabajador").dblclick(function() {
   copyItemList('trabajador','trabajadorSelect');
});

$("#trabajadorSelect").dblclick(function() {
   copyItemList('trabajadorSelect','trabajador');
});
function habilitar_cedula(){
	if(getObj('conceptos_fijos_pr_cedula_trabajador').readOnly==true && getObj('id_tipo_nomina').value!=0){
		getObj('conceptos_fijos_pr_cedula_trabajador').readOnly = false;
	}
	else if(getObj('conceptos_fijos_pr_cedula_trabajador').readOnly==false && getObj('id_tipo_nomina').value==0){
		getObj('conceptos_fijos_pr_cedula_trabajador').readOnly = true;
		getObj('conceptos_fijos_pr_id_trabajador').value = '';
		getObj('conceptos_fijos_pr_cedula_trabajador').value = '';
		getObj('conceptos_fijos_pr_nombre_trabajador').value = '';
		getObj('conceptos_fijos_pr_apellido_trabajador').value = '';
	}
}
</script>
<div id="botonera">
	<img id="conceptos_fijos_pr_btn_limpiar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="conceptos_fijos_pr_btn_eliminar" style="display:none" class="btn_eliminar"src="imagenes/null.gif" />
    <img id="conceptos_fijos_pr_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
    <img id="conceptos_fijos_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />
    <img id="conceptos_fijos_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" name="form_pr_conceptos_fijos" id="form_pr_conceptos_fijos" >
<input type="hidden" name="conceptos_fijos_pr_id_concepto_fijo" id="conceptos_fijos_pr_id_concepto_fijo" />

<input type="hidden" name="conceptos_fijos_pr_id_concepto" id="conceptos_fijos_pr_id_concepto"/>
<input type="hidden" name="conceptos_fijos_pr_id_trabajador" id="conceptos_fijos_pr_id_trabajador" />
<input type="hidden" name="conceptos_fijos_pr_fechact" id="conceptos_fijos_db_fechact" value="<?php echo date("d-m-Y");?>"/>

	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Conceptos Fijos</th>
		</tr>
		<tr>
			<th>Tipo de Nomina:			</th>
			<td>
				<select name="id_tipo_nomina" id="id_tipo_nomina" onchange="habilitar_cedula()">
				<option value="0">--SELECCIONE--</option>
					<?=$opt_tipo?>
				</select>
				<!--<input type="button" onclick="verProps('id_perfil')" value="ver" />-->			</td>
		</tr>
        <tr>
			<th>Concepto</th>
			<td><ul class="input_con_emergente">
				<li>
           <input name="conceptos_fijos_pr_cod" type="text" id="conceptos_fijos_pr_cod" size="5" maxlength="6"/>     
           <input name="conceptos_fijos_pr_concepto" type="text"  id="conceptos_fijos_pr_concepto" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'concepto Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Concepto: '+$(this).val()]}"/>
           </li>
				<li id="conceptos_fijos_pr_btn_consulta_emergente_concepto" class="btn_consulta_emergente"></li>
			</ul>
			</td>
		</tr>
        <tr>
			<th>Carga de Concepto</th>
			<td><select name="conceptos_fijos_pr_opt" id="conceptos_fijos_pr_opt" 
            message="Seleccione como desea hacer la carga de concepto fijo">
			  <option value="1">Individual</option>
			  <option value="2">Grupos</option>
				
				</select></td>
		</tr>
        <tr id="conceptos_fijos_pr_tr_cedula">
			<th>Trabajador</th>
			<td><ul class="input_con_emergente">
				<li>
           <input name="conceptos_fijos_pr_cedula_trabajador" readonly="true" type="text"  id="conceptos_fijos_pr_cedula_trabajador" maxlength="60" size="20"
           jval="{valid:/^[0-9 V-E-]{1,60}$/, message:'concepto Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9 V-E-]/, cFunc:'alert', cArgs:['Concepto: '+$(this).val()]}" 
            message="Escriba o Seleccione el Número de Cédula del Trabajador"/>
           </li>
				<li id="conceptos_fijos_pr_btn_consulta_emergente_trabajador" class="btn_consulta_emergente"></li>
			</ul>
			</td>
		</tr>
        <tr id="conceptos_fijos_pr_tr_nombre"> 
			<th>Nombre</th>
			<td>
            <input readonly="true" name="conceptos_fijos_pr_nombre_trabajador" type="text"  id="conceptos_fijos_pr_nombre_trabajador" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
         message="Introduzca el Nombre del Trabajador"        jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
			</td>
		</tr>
        <tr id="conceptos_fijos_pr_tr_apellido">
			<th>Apellido</th>
			<td><input readonly="true" name="conceptos_fijos_pr_apellido_trabajador" type="text"  id="conceptos_fijos_pr_apellido_trabajador" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}" message="Introduzca el Apellido del Trabajador"   jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}"/>
			</td>
		</tr>
		<tr id="conceptos_fijos_pr_tr_grupos" style="display:none">
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
			  <input name="conceptos_fijos_pr_calculo" type="radio" id="conceptos_fijos_pr_calculo1" value="1" checked="checked" onclick="opt()"/>
		      <strong>Porcentaje </strong>
		      <input type="radio" name="conceptos_fijos_pr_calculo" id="conceptos_fijos_pr_calculo2" value="2" onclick="opt()"/>
			  <strong>Monto</strong></td>
		</tr>
        <tr style="display:none">
			<th>Porcentaje</th>
			<td><input name="conceptos_fijos_pr_porcentaje" type="text" id="conceptos_fijos_pr_porcentaje" size="10" alt="signed-decimal-im" style="text-align:right"/> 
		    <strong>%</strong></td>
		</tr>
        <tr>
			<th>Monto</th>
			<td><input name="conceptos_fijos_pr_monto" type="text"  id="conceptos_fijos_pr_monto" maxlength="60" size="10" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Sueldo Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Sueldo: '+$(this).val()]}" message="Salario Actual del Trabajador"/> 
			</td>
		</tr>
        <tr>
			<th>Observacion</th>
			<td><label>
		    <textarea name="conceptos_fijos_pr_comentario" id="conceptos_fijos_pr_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. "></textarea>
		  </label>
			  </td>
		</tr>
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr> 
	</table>
	
</form>