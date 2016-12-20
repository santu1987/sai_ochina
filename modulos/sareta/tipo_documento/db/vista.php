<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sql3 = "SELECT id_unidad_ejecutora FROM usuario WHERE 
				id_usuario=".$_SESSION['id_usuario'];
									$row1= $conn->Execute($sql3);
									$delegacion=0;
									if(!$row1->EOF){
									$delegacion=$row1->fields("id_unidad_ejecutora");
									}

$sql="SELECT * FROM sareta.numero_control WHERE id_numero_control=id_numero_control and id_delegacion= ".$delegacion."";
$rs_activa = $conn->Execute($sql);

while (!$rs_activa->EOF) {
	
	$opt_activa.="<option value='".$rs_activa->fields("id_numero_control")."' >".$rs_activa->fields("descripcion")."</option>";
$rs_activa->MoveNext();
}

$sql2="SELECT * FROM sareta.nombre_documento WHERE id=id ORDER BY 
				sareta.nombre_documento.codigo";
$rs_tipo_documento = $conn->Execute($sql2);

while (!$rs_tipo_documento->EOF) {
	
	$opt_tipo_documento.="<option value='".$rs_tipo_documento->fields("id")."' >".$rs_tipo_documento->fields("descripcion")."</option>";
$rs_tipo_documento->MoveNext();
}

?>
<script type='text/javascript'>
var dialog;
$("#sareta_tipo_documento_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/tipo_documento/db/grid_tipo_documento.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Documentos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/tipo_documento/db/sql_grid_tipo_documento.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/tipo_documento/db/sql_grid_tipo_documento.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/tipo_documento/db/sql_grid_tipo_documento.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/tipo_documento/db/sql_grid_tipo_documento.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Nombre','nombre','Factor','Vida Propia','Pago Inmediato','Pago Posterior','Calculo de Mora','Secuencia Activa','Secuencia_paso','Documento','id_nombre_documento','id_numero_control','Ultimo Numero','obs'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'nombre_paso',index:'nombre_paso', width:220,sortable:false,resizable:false,hidden:true},

									{name:'factor',index:'factor', width:105,sortable:false,resizable:false},
									{name:'vida_propia',index:'vida_propia', width:170,sortable:false,resizable:false},
									{name:'Pg_inmediato',index:'Pg_inmediato', width:220,sortable:false,resizable:false},
									{name:'Pg_posterior',index:'Pg_posterior', width:220,sortable:false,resizable:false},
									{name:'mora',index:'mora', width:250,sortable:false,resizable:false},
									{name:'secuencia_activa',index:'secuencia_activa', width:250,sortable:false,resizable:false},
									{name:'Secuencia_paso',index:'Secuencia_paso', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_numero_control',index:'id_numero_control', width:220,sortable:false,resizable:false},
									{name:'nombre_documento',index:'nombre_documento', width:220,sortable:false,resizable:false,hidden:true},
									{name:'id_nombre_documento',index:'id_nombre_documento', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ultimo_numero',index:'ultimo_numero', width:220,sortable:false,resizable:false},
									
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_tipo_documento').value = ret.id;
									getObj('sareta_tipo_documento_db_vista_nombre').value = ret.nombre_paso;
									if(ret.factor=="SUMA"){
									getObj('sareta_tipo_documento_db_vista_factor').selectedIndex =0;
									}else{getObj('sareta_tipo_documento_db_vista_factor').selectedIndex =1;
									}
									
									if(ret.vida_propia=="SI"){
									getObj('sareta_tipo_documento_db_vista_vida_propia').selectedIndex =0;
									}else{getObj('sareta_tipo_documento_db_vista_vida_propia').selectedIndex =1;
									}
									
									if(ret.Pg_inmediato=="SI"){
									getObj('sareta_tipo_documento_db_vista_paso_inmediato').selectedIndex =0;
									}else{getObj('sareta_tipo_documento_db_vista_paso_inmediato').selectedIndex =1;
									}
									
									if(ret.Pg_posterior=="SI"){
									getObj('sareta_tipo_documento_db_vista_pago_posterior').selectedIndex =0;
									}else{getObj('sareta_tipo_documento_db_vista_pago_posterior').selectedIndex =1;
									}
									
									if(ret.mora=="SI"){
									getObj('sareta_tipo_documento_db_vista_mora').selectedIndex =0;
									}else{getObj('sareta_tipo_documento_db_vista_mora').selectedIndex =1;
									}
									
									getObj('sareta_tipo_documento_db_vista_codigo_numero_control').value = ret.id_numero_control;
									getObj('sareta_tipo_documento_db_vista_codigo_nombre_docmento').value = ret.id_nombre_documento;
									getObj('sareta_tipo_documento_db_vista_numero').value = ret.ultimo_numero;
									getObj('sareta_tipo_documento_db_vista_obs').value = ret.obs;
				
									getObj('sareta_tipo_documento_db_btn_cancelar').style.display='';
									getObj('sareta_tipo_documento_db_btn_actualizar').style.display='';
									getObj('sareta_tipo_documento_db_btn_eliminar').style.display='';
									getObj('sareta_tipo_documento_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_tipo_documento').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

$("#sareta_tipo_documento_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_tipo_documento').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/tipo_documento/db/sql.actualizar.php",
			data:dataForm('form_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_tipo_documento_db_btn_eliminar').style.display='none';
						getObj('sareta_tipo_documento_db_btn_actualizar').style.display='none';
						getObj('sareta_tipo_documento_db_btn_guardar').style.display='';
						clearForm('form_db_tipo_documento');
						getObj('sareta_tipo_documento_db_vista_factor').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_vida_propia').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_paso_inmediato').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_pago_posterior').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_mora').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_codigo_numero_control').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_codigo_nombre_docmento').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_numero').value ='0';
					});															
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#sareta_tipo_documento_db_btn_guardar").click(function() {
	if($('#form_db_tipo_documento').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/tipo_documento/db/sql.registrar.php",
			data:dataForm('form_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
				
						clearForm('form_db_tipo_documento');
						getObj('sareta_tipo_documento_db_vista_factor').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_vida_propia').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_paso_inmediato').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_pago_posterior').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_mora').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_codigo_numero_control').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_codigo_nombre_docmento').selectedIndex =0;
						getObj('sareta_tipo_documento_db_vista_numero').value ='0';
						setBarraEstado(mensaje[registro_exitoso],true,true,function(){
					});					
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if (html=="numero_control_vacio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br> No Exite una Secuencia Activa <br> Es Necesario Registrar Un N&uacute;mero de Control</p></div>",true,true);
				}
				else if (html=="nombre_documento_vacio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br> No Exite un Nombre de Documento Registrado</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#sareta_tipo_documento_db_btn_eliminar").click(function() {
  if (getObj('vista_id_tipo_documento').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/tipo_documento/db/sql.eliminar.php",
			data:dataForm('form_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_tipo_documento_db_btn_eliminar').style.display='none';
					getObj('sareta_tipo_documento_db_btn_actualizar').style.display='none';
					getObj('sareta_tipo_documento_db_btn_guardar').style.display='';
					clearForm('form_db_tipo_documento');
					getObj('sareta_tipo_documento_db_vista_factor').selectedIndex =0;
					getObj('sareta_tipo_documento_db_vista_vida_propia').selectedIndex =0;
					getObj('sareta_tipo_documento_db_vista_paso_inmediato').selectedIndex =0;
					getObj('sareta_tipo_documento_db_vista_pago_posterior').selectedIndex =0;
					getObj('sareta_tipo_documento_db_vista_mora').selectedIndex =0;
					getObj('sareta_tipo_documento_db_vista_codigo_numero_control').selectedIndex =0;
					getObj('sareta_tipo_documento_db_vista_codigo_nombre_docmento').selectedIndex =0;
					getObj('sareta_tipo_documento_db_vista_numero').value ='0';
					}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con esta tipo_documento</p></div>",true,true); 
				}
				else 
				{
					
					setBarraEstado(html,true,true);
				}
			}
		});
	}
  }
});


$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_tipo_documento_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_tipo_documento_db_btn_cancelar').style.display='';
	getObj('sareta_tipo_documento_db_btn_eliminar').style.display='none';
	getObj('sareta_tipo_documento_db_btn_actualizar').style.display='none';
	getObj('sareta_tipo_documento_db_btn_guardar').style.display='';
	clearForm('form_db_tipo_documento');
	getObj('sareta_tipo_documento_db_vista_factor').selectedIndex =0;
	getObj('sareta_tipo_documento_db_vista_vida_propia').selectedIndex =0;
	getObj('sareta_tipo_documento_db_vista_paso_inmediato').selectedIndex =0;
	getObj('sareta_tipo_documento_db_vista_pago_posterior').selectedIndex =0;
	getObj('sareta_tipo_documento_db_vista_mora').selectedIndex =0;
	getObj('sareta_tipo_documento_db_vista_codigo_numero_control').selectedIndex =0;
	getObj('sareta_tipo_documento_db_vista_codigo_nombre_docmento').selectedIndex =0;
	getObj('sareta_tipo_documento_db_vista_numero').value ='0';
	
});
	
	
	$('#sareta_tipo_documento_db_vista_numero').numeric({allow:' 0123456789'});



</script>


<div id="botonera">
	<img id="sareta_tipo_documento_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_tipo_documento_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_tipo_documento_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_tipo_documento_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_tipo_documento_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_tipo_documento" name="form_db_tipo_documento">
<input type="hidden" name="vista_id_tipo_documento" id="vista_id_tipo_documento" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Tipo de Documento </th>
	</tr>
	<tr>
	<th>Nombre:		</th>	
	<td>
		<input name="sareta_tipo_documento_db_vista_nombre" type="text" id="sareta_tipo_documento_db_vista_nombre"   value="" size="35" maxlength="30"  
						message="Introduzca un Nombre para el Tipo de Documento" 
						jVal="{valid:/^[a-z A-Z_áéíóúÁÉÍÓÚñ 0-9]{1,30}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-z A-Z_áéíóúÁÉÍÓÚñ 0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
	</tr>
    <tr>
	<th>Factor:		</th>	
	<td><select name="sareta_tipo_documento_db_vista_factor" id="sareta_tipo_documento_db_vista_factor">
	  <option value="true" selected="selected">SUMA</option>
	  <option value="false">RESTA</option>
	  </select></td>
	</tr>	
	
	<tr>
		<th>Vida Propia:</th>			
        <td ><select name="sareta_tipo_documento_db_vista_vida_propia" id="sareta_tipo_documento_db_vista_vida_propia">
		  <option value="true" selected="selected">SI</option>
		  <option value="false">NO</option>
		  </select>
        </td>
	</tr>
    
	<tr>
		<th>¿Aplica Pago <br >Complementario <br >Inmediato?:</th>			
        <td ><select name="sareta_tipo_documento_db_vista_paso_inmediato" 
        id="sareta_tipo_documento_db_vista_paso_inmediato">
		  <option value="true" selected="selected">SI</option>
		  <option value="false">NO</option>
		  </select>
        </td>
	</tr>
    <tr>
		<th>¿Aplica Pago <br >Complementario <br >Posterior?:</th>			
        <td ><select name="sareta_tipo_documento_db_vista_pago_posterior" 
        id="sareta_tipo_documento_db_vista_pago_posterior">
		  <option value="true" selected="selected">SI</option>
		  <option value="false">NO</option>
		  </select>
        </td>
	</tr>
    <tr>
		<th>¿Aplica Calculo de <br >Mora?:</th>			
        <td ><select name="sareta_tipo_documento_db_vista_mora" id="sareta_tipo_documento_db_vista_mora">
		  <option value="true" selected="selected">SI</option>
		  <option value="false">NO</option>
		  </select>
        </td>
	</tr>
    
    
    <tr>
	<th>Secuencia Activa: </th>	
    <td>
   		<select name="sareta_tipo_documento_db_vista_codigo_numero_control" id="sareta_tipo_documento_db_vista_codigo_numero_control" >
        <?=$opt_activa ?>
        </select>
	  </td>
	</tr>
    
    <tr>
	<th>Nombre de Documento: </th>	
    <td>
   		<select name="sareta_tipo_documento_db_vista_codigo_nombre_docmento" id="sareta_tipo_documento_db_vista_codigo_nombre_docmento" >
        <?=$opt_tipo_documento ?>
        </select>
	  </td>
	</tr>
    
    <tr>
	<th>Ultimo Numero: </th>	
	<td>
        <input  name="sareta_tipo_documento_db_vista_numero" type="text" id="sareta_tipo_documento_db_vista_numero"  size="8"  maxlength="5"   value="0" message="Introduzca un valor" 
        jval="{valid:/^[0-9]{1,5}$/, message:'Numero Invalido', styleType:'cover'}"
		valkey="{valid:/[0-9]/, cFunc:'alert',cArgs:['Numero: '+$(this).val()]}"/>
        </td>
	</tr>	
    <tr>
			<th>Comentario:			</th>
			<td>	<textarea name="sareta_tipo_documento_db_vista_obs" cols="60" id="sareta_tipo_documento_db_vista_obs" message="Introduzca un Observación"></textarea></td>
	</tr>	
 
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>