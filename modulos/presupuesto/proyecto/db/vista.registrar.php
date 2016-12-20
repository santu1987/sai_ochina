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
//
//
//
$("#proyecto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/proyecto/db/vista.grid_proyecto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proyectos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#proyecto_db_codigo_proyecto").val(); 
					var busq_nombre= jQuery("#proyecto_db_nombre_proyecto").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/proyecto/db/sql_proyecto.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#proyecto_db_codigo_proyecto").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#proyecto_db_nombre_proyecto").keypress(function(key)
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
							var busq_codigo= jQuery("#proyecto_db_codigo_proyecto").val();
							var busq_nombre= jQuery("#proyecto_db_nombre_proyecto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/proyecto/db/sql_proyecto.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/proyecto/db/sql_proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Proyecto','Proyecto2','Nombre Jefe Proyecto','Comentario','id_jefe_proyecto'],
								colModel:[
									{name:'id_proyecto',index:'id_proyecto', width:15,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proyecto',index:'codigo_proyecto', width:15,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:75,sortable:false,resizable:false},
									{name:'nombre2',index:'nombre2', width:75,sortable:false,resizable:false,hidden:true},
									{name:'nombre_jefe_proyecto',index:'nombre_jefe_proyecto', width:75,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('proyecto_db_id').value = ret.id_proyecto;
									getObj('proyecto_db_codigo').value = ret.codigo_proyecto;
									getObj('proyecto_db_nombre').value = ret.nombre2;
									getObj('proyecto_db_comentario').value = ret.comentario;
									getObj('proyecto_db_jefe_proyecto_id').value = ret.id_jefe_proyecto;
									getObj('proyecto_db_jefe_proyecto').value = ret.nombre_jefe_proyecto;
									getObj('proyecto_db_btn_actualizar').style.display='';
									getObj('proyecto_db_btn_cancelar').style.display='';
									getObj('proyecto_db_btn_eliminar').style.display='';
									getObj('proyecto_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#proyecto_db_codigo_proyecto").focus();
								$('#proyecto_db_codigo_proyecto').numeric({allow:''});
								$('#proyecto_db_nombre_proyecto').alpha({allow:''});
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

//
//
//
/*$("#proyecto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/presupuesto/proyecto/db/grid_proyecto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proyectos',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:550,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/proyecto/db/sql_grid_proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Proyecto','Proyecto','Comentario','id_jefe_proyecto','Nombre Jefe Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombreli',index:'nombreli', width:201,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:201,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true},
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre_jefe_proyecto',index:'nombre_jefe_proyecto', width:150,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('proyecto_db_id').value = ret.id;
									getObj('proyecto_db_codigo').value = ret.codigo;
									getObj('proyecto_db_nombre').value = ret.nombre;
									getObj('proyecto_db_comentario').value = ret.comentario;
									getObj('proyecto_db_jefe_proyecto_id').value = ret.id_jefe_proyecto;
									getObj('proyecto_db_jefe_proyecto').value = ret.nombre_jefe_proyecto;
									getObj('proyecto_db_btn_actualizar').style.display='';
									getObj('proyecto_db_btn_cancelar').style.display='';
									getObj('proyecto_db_btn_eliminar').style.display='';
									getObj('proyecto_db_btn_guardar').style.display='none';
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
});*/

$("#proyecto_db_btn_guardar").click(function() {
	if($('#form_db_proyecto').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/proyecto/db/sql.proyecto.php",
			data:dataForm('form_db_proyecto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_proyecto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_db_proyecto.proyecto_db_nombre.value="";
					document.form_db_proyecto.proyecto_db_nombre.focus();
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#proyecto_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	/*if($('#form_db_programa').jVal())
	{*/
		$.ajax (
		{
			url: "modulos/presupuesto/proyecto/db/sql.actualizar.php",
			data:dataForm('form_db_proyecto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('proyecto_db_btn_eliminar').style.display='none';
					getObj('proyecto_db_btn_actualizar').style.display='none';
					getObj('proyecto_db_btn_guardar').style.display='';
					clearForm('form_db_proyecto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_db_proyecto.proyecto_db_nombre.value="";
					document.form_db_proyecto.proyecto_db_nombre.focus();	
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
});
$("#proyecto_db_btn_eliminar").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/presupuesto/proyecto/db/sql.eliminar.php",
			data:dataForm('form_db_proyecto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('proyecto_db_btn_cancelar').style.display='';
					getObj('proyecto_db_btn_eliminar').style.display='none';
					getObj('proyecto_db_btn_actualizar').style.display='none';
					getObj('proyecto_db_btn_guardar').style.display='';
					clearForm('form_db_proyecto');
				}
				else if (html=="bloqueado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />EL PROYECTO YA TIENE ACCIONES ESPECIFICAS ASIGNADAS</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
//-----------------------------------------------------------------------------------------------------
//
//
//
$("#proyecto_db_btn_consultar_proyecto").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/proyecto/db/vista.grid_proyecto2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Jefe de Proyecto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#proyecto_db_nombre_jefe").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/proyecto/db/sql_jefe_proyecto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#proyecto_db_nombre_jefe").keypress(function(key)
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
							var busq_nombre= jQuery("#proyecto_db_nombre_jefe").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/proyecto/db/sql_jefe_proyecto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/proyecto/db/sql_jefe_proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Jefe Proyecto'],
								colModel:[
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:15,sortable:false,resizable:false},
									{name:'jefe',index:'jefe', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('proyecto_db_jefe_proyecto_id').value = ret.id_jefe_proyecto;
									getObj('proyecto_db_jefe_proyecto').value = ret.jefe;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#proyecto_db_nombre_jefe").focus();
								$('#proyecto_db_nombre_jefe').alpha({allow:' '});
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

//
//
//
/*$("#proyecto_db_btn_consultar_proyecto").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/proyecto/db/grid_proyecto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Jefe de Proyecto', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/proyecto/db/cmb.sql.jefe_proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo', 'Jefe de Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false},
									{name:'jefe',index:'jefe', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('proyecto_db_jefe_proyecto_id').value = ret.id;
									getObj('proyecto_db_jefe_proyecto').value = ret.jefe;
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
});*/
// -----------------------------------------------------------------------------------------------------------------------------------
///**************************************************************
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/proyecto/db/sql_grid_proyecto_codigo.php",
            data:dataForm('form_db_proyecto'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				if(recordset)
				{
				recordset = recordset.split(".");
				getObj('proyecto_db_id').value = recordset[0];
				getObj('proyecto_db_jefe_proyecto_id').value=recordset[1];
				getObj('proyecto_db_jefe_proyecto').value=recordset[2];
				getObj('proyecto_db_nombre').value =recordset[3];
				getObj('proyecto_db_comentario').value=recordset[4];
				getObj('proyecto_db_btn_actualizar').style.display='';
				getObj('proyecto_db_btn_guardar').style.display='none';
				}
				else
			 {  
			   	getObj('proyecto_db_id').value ="";
			    getObj('proyecto_db_jefe_proyecto_id').value="";
				getObj('proyecto_db_jefe_proyecto').value="";
				getObj('proyecto_db_nombre').value ="" ;
			 	getObj('proyecto_db_comentario').value="" ;
				}
			 }
		});	 	 
}
//************************************************************


$("#proyecto_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('proyecto_db_btn_cancelar').style.display='';
	getObj('proyecto_db_btn_eliminar').style.display='none';
	getObj('proyecto_db_btn_actualizar').style.display='none';
	getObj('proyecto_db_btn_guardar').style.display='';
	clearForm('form_db_proyecto');
});


$('#proyecto_db_nombre').alpha({allow:'._1234567890- áéíóúÁÉÍÓÚ(),'});
$('#proyecto_db_codigo').numeric({allow:''});
$('#proyecto_db_codigo').change(consulta_automatica_proyecto);

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>
<div id="botonera">
	<img id="proyecto_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="proyecto_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="proyecto_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="proyecto_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="proyecto_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_db_proyecto" id="form_db_proyecto">
	<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Proyecto</th>
	</tr>
	<tr>
		<th>A&ntilde;o:				</th>
		<td ><?=date('Y')+1?></td>
	</tr>
	<tr>
		<th>C&oacute;digo:				</th>
		<td ><input name="proyecto_db_codigo" type="text" id="proyecto_db_codigo"  maxlength="6"
										    onchange="consulta_automatica_proyecto" onclick="consulta_automatica_proyecto"
										   	message="Introduzca un Codigo de 5 digito para el proyecto."  size="6"
										   	jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido, debe tener un minimo de 6 digitos', styleType:'cover'}"
										   	jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" 
										   	></td>
	</tr>
		<tr>
			<th>Nombre:</th>
			<td ><input name="proyecto_db_nombre" type="text" id="proyecto_db_nombre" style="width:82ex;" maxlength="200"
				message="Introduzca un Nombre para el Proyecto." 
				jVal="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚ 1234567890-_]{1,200}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚ 1234567890-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
		</tr>
		<tr>
		  <th>Responsable: </th>
		  <td >
				<table  width="100%" class="clear">
					<tr>
						<td>
							<input name="proyecto_db_jefe_proyecto" type="text" id="proyecto_db_jefe_proyecto" size="89" readonly="readonly" 
				message="Seleccione un jefe de proyecto" 
				 jVal="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚ1234567890-_]{1,100}$/, message:'Nombre Invalido', styleType:'cover'}"/>
		  		
						</td>
						
						<td><img class="btn_consulta_emergente" id="proyecto_db_btn_consultar_proyecto" src="imagenes/null.gif" />
							<input name="proyecto_db_jefe_proyecto_id" type="hidden" id="proyecto_db_jefe_proyecto_id" size="60" />
						</td>
					</tr>
				</table>
			</td>				
	  </tr>
		<tr>
			<th>Comentario:</th>
			<td ><textarea name="proyecto_db_comentario" id="proyecto_db_comentario" cols="90" rows="3" message="Introduzca un Comentario para el Proyecto."/><?=$comentario;?>
			</textarea></td>
		</tr>
		
			
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="proyecto_db_id" id="proyecto_db_id" />
</form>
