<? if (!$_SESSION) session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

	$sql="SELECT * FROM ramo WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY nombre";
	$rs_ramos =& $conn->Execute($sql);
	while (!$rs_ramos->EOF) {
		$opt_ramos.="<option value='".$rs_ramos->fields("id_ramo")."' >".$rs_ramos->fields("nombre")."</option>";
		$rs_ramos->MoveNext();
	}
?>
<script type="text/javascript">


/*--------------------------------------   GUARDAR ----------------------------------------------------*/
$("#documento_proveedor_db_btn_guardar").click(function() {
	if($('#form_db_documento_proveedor').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/adquisiones/documento_proveedor/db/sql.documento_proveedor.php",
			data:dataForm('form_db_documento_proveedor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_documento_proveedor');
					getObj('documento_proveedor_db_estatus').value="0"
					getObj('documento_proveedor_estatus_opt_act').checked="checked";
					
				}
				else if (html=="Existe")
				{
					//alert(html);
					setBarraEstado(html);
					setBarraEstado(mensaje[registro_existe],true,true);

				}else
					{
					 alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
});

/*--------------------------------------   BUSCAR ----------------------------------------------------*/
//
//
//
$("#documento_proveedor_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/documento_proveedor/db/vista.grid_documento_proveedor.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#documento_proveedor_db_codigo_proveedor").val(); 
					var busq_nombre= jQuery("#documento_proveedor_db_nombre_proveedor").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/documento_proveedor/db/sql_documento_proveedor.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#documento_proveedor_db_codigo_documento").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#documento_proveedor_db_nombre_documento").keypress(function(key)
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
							var busq_codigo= jQuery("#documento_proveedor_db_codigo_documento").val();
							var busq_nombre= jQuery("#documento_proveedor_db_nombre_documento").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/documento_proveedor/db/sql_documento_proveedor.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/adquisiones/documento_proveedor/db/sql_documento_proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre','Observacion','Estatus'],
								colModel:[
									{name:'id_documento_proveedor',index:'id_documento_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_documento',index:'codigo_documento', width:30,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('documento_proveedor_db_id').value = ret.id_documento_proveedor;
									getObj('documento_proveedor_db_codigo').value = ret.codigo_documento;
									getObj('documento_proveedor_db_nombre').value = ret.nombre;
									getObj('documento_proveedor_db_observacion').value = ret.comentario;
									if(ret.estatus=='Activo')
						     	    { 
										getObj('documento_proveedor_estatus_opt_act').checked="checked";
										getObj('documento_proveedor_db_estatus').value="0";
									}else
									{
									getObj('documento_proveedor_estatus_opt_inact').checked="checked";
									getObj('documento_proveedor_db_estatus').value="1";
									}
									getObj('documento_proveedor_db_btn_cancelar').style.display='';
									getObj('documento_proveedor_db_btn_actualizar').style.display='';
									getObj('documento_proveedor_db_btn_eliminar').style.display='';
									getObj('documento_proveedor_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#documento_proveedor_db_codigo_documento").focus();
								$('#documento_proveedor_db_codigo_documento').numeric({allow:''});
								$('#documento_proveedor_db_nombre_documento').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_documento_proveedor',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
//
/*$("#documento_proveedor_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/adquisiones/documento_proveedor/db/grid_documento_proveedor.php", { },
                        function(data)
                        {								
							dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Documentos a consignar del Proveedor',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:750,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/documento_proveedor/db/sql_grid_documento_proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Nombre','Observación','Estatus'],
								colModel:[
									{name:'id',index:'id', width:40,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:40,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:60,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:220,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:100,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('documento_proveedor_db_id').value = ret.id;
									getObj('documento_proveedor_db_codigo').value = ret.codigo;
									getObj('documento_proveedor_db_nombre').value = ret.nombre;
									getObj('documento_proveedor_db_observacion').value = ret.observacion;
									if(ret.estatus=='Activo')
						     	    { 
										getObj('documento_proveedor_estatus_opt_act').checked="checked";
										getObj('documento_proveedor_db_estatus').value="0";
									}else
									{
									getObj('documento_proveedor_estatus_opt_inact').checked="checked";
									getObj('documento_proveedor_db_estatus').value="1";
									}
									getObj('documento_proveedor_db_btn_cancelar').style.display='';
									getObj('documento_proveedor_db_btn_actualizar').style.display='';
									getObj('documento_proveedor_db_btn_eliminar').style.display='';
									getObj('documento_proveedor_db_btn_guardar').style.display='none';									
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
								sortname: 'codigo_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/
//----- modificar
$("#documento_proveedor_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_documento_proveedor').jVal())
	{
		$.ajax (
		{
			url: "modulos/adquisiones/documento_proveedor/db/sql.actualizar.php",
			data:dataForm('form_db_documento_proveedor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('documento_proveedor_db_btn_eliminar').style.display='none';
					getObj('documento_proveedor_db_btn_actualizar').style.display='none';
					getObj('documento_proveedor_db_btn_guardar').style.display='';
					clearForm('form_db_documento_proveedor');
					getObj('documento_proveedor_estatus_opt_act').checked="checked";
					getObj('documento_proveedor_db_estatus').value="0"
					getObj('documento_proveedor_db_estatus').value="0";
					
						}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('documento_proveedor_db_btn_eliminar').style.display='none';
					getObj('documento_proveedor_db_btn_actualizar').style.display='none';
					getObj('documento_proveedor_db_btn_guardar').style.display='';
					clearForm('form_db_documento_proveedor');
					getObj('documento_proveedor_estatus_opt_act').checked="checked";
					getObj('documento_proveedor_db_estatus').value="0"
					getObj('documento_proveedor_db_estatus').value="0";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#documento_proveedor_db_btn_eliminar").click(function() {
  if (getObj('documento_proveedor_db_id').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/adquisiones/documento_proveedor/db/sql.eliminar.php",
			data:dataForm('form_db_documento_proveedor'),
			type:'POST',
			cache: false,
			success: function(html)
			{ 
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('documento_proveedor_db_btn_eliminar').style.display='none';
					getObj('documento_proveedor_db_btn_actualizar').style.display='none';
					getObj('documento_proveedor_db_btn_guardar').style.display='';
					clearForm('form_db_documento_proveedor');
					getObj('documento_proveedor_estatus_opt_act').checked="checked";
					getObj('estatus').value="0";
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true);
					getObj('estatus').value="0";
				}
				else
				{
					setBarraEstado(html);
					getObj('estatus').value="0";
				}
			}
		});
	}
  }
});
$("#documento_proveedor_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('documento_proveedor_db_btn_cancelar').style.display='';
	getObj('documento_proveedor_db_btn_actualizar').style.display='none';
	getObj('documento_proveedor_db_btn_guardar').style.display='';
	clearForm('form_db_documento_proveedor');
	getObj('documento_proveedor_estatus_opt_act').checked="checked";
	getObj('estatus').value="0";
});
function consulta_automatica_documento()
{
	if (getObj('documento_proveedor_db_codigo')!=" ")
	{
	$.ajax({
			url:"modulos/adquisiones/documento_proveedor/db/sql.codigo.php",
            data:dataForm('form_db_documento_proveedor'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				if(recordset)
				{
				
				recordset = recordset.split(".");
				getObj('documento_proveedor_db_id').value = recordset[0];
				getObj('documento_proveedor_db_nombre').value=recordset[1];
				getObj('documento_proveedor_db_observacion').value=recordset[3];
				if(recordset[2]=="0")
				{
					getObj('documento_proveedor_estatus_opt_act').checked="checked";
					getObj('documento_proveedor_db_estatus').value="0";
				}else
				{
						getObj('documento_proveedor_estatus_opt_inact').checked="checked";
						getObj('documento_proveedor_db_estatus').value="1";
				}
		
				
				getObj('documento_proveedor_db_btn_actualizar').style.display='';
				getObj('documento_proveedor_db_btn_guardar').style.display='none';	
				}
				else
			 {  
			   getObj('documento_proveedor_db_id').value ="";
			    getObj('documento_proveedor_db_nombre').value="";
				getObj('documento_proveedor_db_observacion').value="";
				}
			 }
		});	 	 
	}	//alert(html);
}
$('#documento_proveedor_db_codigo').change(consulta_automatica_documento)

$('#documento_proveedor_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
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

$("#documento_proveedor_estatus_opt_act").click(function(){
		getObj('documento_proveedor_db_estatus').value="0"
	});
$("#documento_proveedor_estatus_opt_inact").click(function(){
		getObj('documento_proveedor_db_estatus').value="1"
	});
	
</script>
<div id="botonera">
	<img id="documento_proveedor_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="documento_proveedor_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="documento_proveedor_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="documento_proveedor_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="documento_proveedor_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form name="form_db_documento_proveedor" id="form_db_documento_proveedor">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="2"><input type="hidden" id="documento_proveedor_db_id" name="documento_proveedor_db_id" />
		    <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Documentos</th>
		</tr>
		<tr>
		<th>C&oacute;digo:</th>
		 <td>
		    	<input type="text" name="documento_proveedor_db_codigo" id="documento_proveedor_db_codigo"  style="width:6ex;" 
				message="Introduzca el Codigo del documento." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,4}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		 </td>
		</tr>
		  <th>Nombre: </th>
		    <td><input type="text" name="documento_proveedor_db_nombre" id="documento_proveedor_db_nombre" style="width:62ex;" 
				message="Introduzca el Nombre del Proveedor." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		 </td>
		</tr>
		<tr> 
		 <th>Estatus:</th>
		 <td>
		   	<input id="documento_proveedor_estatus_opt_act" name="documento_proveedor_estatus_opt"  type="radio" value="0" checked="checked" />Activo
	      	<input id="documento_proveedor_estatus_opt_inact" name="documento_proveedor_estatus_opt"  type="radio" value="1" />Inactivo
          <input type="hidden" id="documento_proveedor_db_estatus" name="documento_proveedor_db_estatus"  value="0" /></td>
		</tr>
		  <th>Observación:</th>
		    <td>
				<textarea  name="documento_proveedor_db_observacion" cols="60" id="documento_proveedor_db_observacion" message="Introduzca una Observación. Ejem:'El siguiente documento consiste en ...' "></textarea>
		</td>
  <tr>
    <td colspan="2" class="bottom_frame">&nbsp;</td>
  </tr>
	</table>

</form>