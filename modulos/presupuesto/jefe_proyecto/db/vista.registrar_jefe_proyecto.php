<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

?>
<script>
var dialog;
$("#jefe_proyecto_db_btn_guardar").click(function() {
	if($('#form_db_jefe_proyecto').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/jefe_proyecto/db/sql.jefe_proyecto.php",
			data:dataForm('form_db_jefe_proyecto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_jefe_proyecto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}

			}
		});
	}
});

var dialog;
//
//
//

$("#jefe_proyecto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/jefe_proyecto/db/vista.grid_jefe_pro.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Jefe Proyecto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_cedula= jQuery("#jefe_proyecto_db_cedula_jefe").val(); 
					var busq_nombre= jQuery("#jefe_proyecto_db_nombre_jefe").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/jefe_proyecto/db/sql_jefe_pro.php?busq_cedula="+busq_cedula+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#jefe_proyecto_db_cedula_jefe").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#jefe_proyecto_db_nombre_jefe").keypress(function(key)
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
							var busq_cedula= jQuery("#jefe_proyecto_db_cedula_jefe").val();
							var busq_nombre= jQuery("#jefe_proyecto_db_nombre_jefe").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/jefe_proyecto/db/sql_jefe_pro.php?busq_cedula="+busq_cedula+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/jefe_proyecto/db/sql_jefe_pro.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Cedula','Nombre','cargo','estatus', 'grado'],
								colModel:[
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula_jefe_proyecto',index:'cedula_jefe_proyecto', width:40,sortable:false,resizable:false},
									{name:'nombre_jefe_proyecto',index:'nombre_jefe_proyecto', width:100,sortable:false,resizable:false},
									{name:'cargo_jefe_proyecto',index:'cargo_jefe_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'grado_jefe_proyecto',index:'grado_jefe_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('jefe_proyecto_db_id').value = ret.id_jefe_proyecto;
									getObj('jefe_proyecto_db_cedula').value = ret.cedula_jefe_proyecto;
									getObj('jefe_proyecto_db_nombre').value = ret.nombre_jefe_proyecto;
									getObj('jefe_proyecto_db_cargo').value = ret.cargo_jefe_proyecto;
									getObj('jefe_proyecto_db_estatus').value = ret.estatus;	
									getObj('jefe_proyecto_db_grado').value = ret.grado_jefe_proyecto;
									getObj('jefe_proyecto_db_btn_actualizar').style.display='';
									getObj('jefe_proyecto_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#jefe_proyecto_db_cedula_jefe").focus();
								$('#jefe_proyecto_db_cedula_jefe').numeric({allow:''});
								$('#jefe_proyecto_db_nombre_jefe').alpha({allow:' '});
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
/*$("#jefe_proyecto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/jefe_proyecto/db/grid_jefe_proyecto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Jefe Proyecto',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:730,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/jefe_proyecto/db/sql_grid_jefe_proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Cedula','Nombre','cargo','estatus', 'grado'],
								colModel:[
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:20,sortable:false,resizable:false,hidden:true},
									{name:'cedula_jefe_proyecto',index:'cedula_jefe_proyecto', width:71,sortable:false,resizable:false},
									{name:'nombre_jefe_proyecto',index:'nombre_jefe_proyecto', width:250,sortable:false,resizable:false},
									{name:'cargo',index:'cargo', width:20,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:20,sortable:false,resizable:false,hidden:true},
									{name:'grado',index:'grado', width:20,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('jefe_proyecto_db_id').value = ret.id_jefe_proyecto;
									getObj('jefe_proyecto_db_cedula').value = ret.cedula_jefe_proyecto;
									getObj('jefe_proyecto_db_nombre').value = ret.nombre_jefe_proyecto;
									getObj('jefe_proyecto_db_cargo').value = ret.cargo;
									getObj('jefe_proyecto_db_estatus').value = ret.estatus;	
									getObj('jefe_proyecto_db_grado').value = ret.grado;
									getObj('jefe_proyecto_db_btn_actualizar').style.display='';
									getObj('jefe_proyecto_db_btn_guardar').style.display='none';									
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
//***************************************************************************************
$("#jefe_proyecto_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	/*if($('#form_db_programa').jVal())
	{*/
		$.ajax (
		{
			url: "modulos/presupuesto/jefe_proyecto/db/sql.actualizar.php",
			data:dataForm('form_db_jefe_proyecto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('jefe_proyecto_db_btn_actualizar').style.display='none';
					getObj('jefe_proyecto_db_btn_guardar').style.display='';
					clearForm('form_db_jefe_proyecto');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	//}
});
</script>
<script language="javascript" type="text/javascript">
$('#jefe_proyecto_db_nombre').alpha({allow:' áéíóúÁÉÍÓÚ'});
$('#jefe_proyecto_db_cedula').numeric({allow:'.'});
</script>
<div id="botonera">
	<img id="jefe_proyecto_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="jefe_proyecto_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="jefe_proyecto_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="jefe_proyecto_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" />
</div>
<form method="post"  name="form_db_jefe_proyecto" id="form_db_jefe_proyecto">
<input type="hidden" name="jefe_proyecto_db_id" id="jefe_proyecto_db_id" />
	<table class="cuerpo_formulario">
		<tr>
			<th colspan="3" class="titulo_frame"><img src="imagenes/iconos/clasifica24x24.png" style="padding-right:5px;" align="absmiddle" /> Jefe de Proyecto</th>
		</tr>
		<tr>
			<th width="73">Cedula:</th>
			<td width="50"><select name="jefe_proyecto_db_nacionalidad" id="jefe_proyecto_db_nacionalidad" style="width:50px; min-width:50px;">
			  <option>V-</option>
			  <option>E-</option>
			  <option>P-</option>
		    </select></td>
		  <td width="306"><input name="jefe_proyecto_db_cedula" type="text" id="jefe_proyecto_db_cedula"  value="" size="20" maxlength="12"  
					message="Introduzca el Número de Cédula. Ejem: ''V-0000000 ó E-0000000''" 
					jval="{valid:/^[0-9]{1,12}$/, message:'Cédula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>Nombre:</th>
			<td colspan="2">	<input name="jefe_proyecto_db_nombre" type="text" id="jefe_proyecto_db_nombre" size="60" maxlength="60"message='Escriba el Nombre del Jefe de Proyecto. Ejem: "Jefe Proyecto"' 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Valor: '+$(this).val()]}"></td>
		</tr>
		<tr>
			<th>Cargo:</th>
			<td colspan="2">	<select name="jefe_proyecto_db_cargo" id="jefe_proyecto_db_cargo" message='Seleccione el Cargo del Jefe de Proyecto.' style="width:200px">
			  <option value="0" selected="selected">-- Seleccione --</option>
			  <option value="1">Programador</option>
			  <option value="2">Analista de Sistemas</option>
			  <option value="3">Director</option>
			  <option value="4">Responsable</option>
			</select></td>
		</tr>
		<tr>
			<th>Grado:</th>
			<td colspan="2"><select name="jefe_proyecto_db_grado" id="jefe_proyecto_db_grado" message='Seleccione el Grado del Jefe de Proyecto.' style="width:200px">
			  <option value="0">-- Seleccione --</option>
			  <option value="1">TSU</option>
			  <option value="2">Ingeniero</option>
			  <option value="3">Licenciado(a)</option>
			  <option value="4">AN (Alférez de Navío)</option>
			  <option value="5">TF (Teniente de Fragata)</option>
			  <option value="6">TN (Teniente de Navío)</option>
			  <option value="9">CC (Capitán de Corbeta)</option>
              <option value="7">CF (Capitán de Fragata)</option>
			  <option value="8">CN (Capitán de Navío)</option>
			</select></td>
		</tr>
		<tr>
			<th>Estatus:</th>
			<td colspan="2" >
			<select name="jefe_proyecto_db_estatus" id="jefe_proyecto_db_estatus">
				<option value="1">Activo</option>
				<option value="2">Inactivo</option>
			</select>
			</td>
		</tr>
		<tr>
			<td colspan="3" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>