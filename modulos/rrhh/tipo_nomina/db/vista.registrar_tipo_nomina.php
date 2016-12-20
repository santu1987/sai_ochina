<? if (!$_SESSION) session_start();
?>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM frecuencia ORDER BY id_frecuencia";
$rs_concepto =& $conn->Execute($sql);
while (!$rs_concepto->EOF){
	$opt_concepto.="<option value='".$rs_concepto->fields("id_frecuencia")."' >".$rs_concepto->fields("descripcion")."</option>";
	$rs_concepto->MoveNext();
} 
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------

$("#tipo_nomina_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/tipo_nomina/db/vista.grid_tipo_nomina_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Tipo de Nomina', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_nomina_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/tipo_nomina/db/sql_tipo_nomina_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_nomina_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#tipo_nomina_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/tipo_nomina/db/sql_tipo_nomina_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/tipo_nomina/db/sql_tipo_nomina_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre Nomina','','','Frecuencia'],
								colModel:[
									{name:'id_tipo_nomina',index:'id_tipo_nomina', width:50,sortable:false,resizable:false,hidden:true},
									{name:'tipo_nomina',index:'tipo_nomina', width:50,sortable:false,resizable:false},
									{name:'observaciones',index:'observaciones', width:50,hidden:true},
									{name:'id_frecuencia',index:'id_frecuencia',hidden:true},
									{name:'frecuencia',index:'frecuencia', width:50}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tipo_nomina_db_id_tipo_nomina').value=ret.id_tipo_nomina;
									getObj('tipo_nomina_db_nombre_tipo_nomina').value=ret.tipo_nomina;
									var frecu=ret.id_frecuencia;
									if(frecu==2){
										getObj('tipo_nomina_db_frecuencia').selectedIndex=1;
									}
									if(frecu==3){
										getObj('tipo_nomina_db_frecuencia').selectedIndex=2;
									}
									if(frecu==4){
										getObj('tipo_nomina_db_frecuencia').selectedIndex=3;
									}
									if(frecu==5){
										getObj('tipo_nomina_db_frecuencia').selectedIndex=4;
									}
									getObj('tipo_nomina_db_comentario').value=ret.observaciones;
									getObj('tipo_nomina_db_btn_guardar').style.display = 'none';
									getObj('tipo_nomina_db_btn_actualizar').style.display = '';
									getObj('tipo_nomina_db_btn_eliminar').style.display='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tipo_nomina_db_nombre").focus();
								$('#tipo_nomina_db_nombre').alpha({allow:' '});
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




//
//

//
//
//----------------------------------------------------------------


$("#tipo_nomina_db_btn_guardar").click(function() {
	if ($('#form_db_tipo_nomina').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/tipo_nomina/db/sql.registrar_tipo_nomina.php",
			data:dataForm('form_db_tipo_nomina'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_campo();
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


//----------------------------------------------------------------
//----------------------Actualizar--------------------------------
$("#tipo_nomina_db_btn_actualizar").click(function() {
	if ($('#form_db_tipo_nomina').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/tipo_nomina/db/sql.actualizar_tipo_nomina.php",
			data:dataForm('form_db_tipo_nomina'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar_campo();
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
$("#tipo_nomina_db_btn_eliminar").click(function() {
	if ($('#form_db_tipo_nomina').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/tipo_nomina/db/sql.eliminar_tipo_nomina.php",
			data:dataForm('form_db_tipo_nomina'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('tipo_nomina_db_id_tipo_nomina').value='';
					getObj('tipo_nomina_db_nombre_tipo_nomina').value='';
					getObj('tipo_nomina_db_comentario').value = '';
					getObj('tipo_nomina_db_btn_actualizar').style.display='none';
					getObj('tipo_nomina_db_btn_eliminar').style.display='none';
					getObj('tipo_nomina_db_btn_guardar').style.display='';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Relacion_Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
//					clearForm('form_db_valor_impuesto');
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
// ******************************************************************************
function limpiar_campo(){
	getObj('tipo_nomina_db_nombre_tipo_nomina').focus();
	getObj('tipo_nomina_db_id_tipo_nomina').value='';
	getObj('tipo_nomina_db_nombre_tipo_nomina').value='';
	getObj('tipo_nomina_db_comentario').value='';
	getObj('tipo_nomina_db_btn_actualizar').style.display='none';
	getObj('tipo_nomina_db_btn_eliminar').style.display='none';
	getObj('tipo_nomina_db_btn_guardar').style.display='';
	getObj('tipo_nomina_db_frecuencia').selectedIndex=0;
}
$("#tipo_nomina_db_btn_cancelar").click(function() {
//clearForm('form_db_tipo_nomina');
limpiar_campo();
setBarraEstado("");
});
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------
/* ******************************************************************************


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#tipo_nomina_db_cedula_tipo_nomina').numeric({allow:' '});
$('#tipo_nomina_db_nombre_tipo_nomina').alpha({allow:'() '});
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
	<img id="tipo_nomina_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img style="display:none" id="tipo_nomina_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif"/>
	<img id="tipo_nomina_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="tipo_nomina_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="tipo_nomina_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_tipo_nomina" id="form_db_tipo_nomina">
<input type="hidden" name="tipo_nomina_db_id_tipo_nomina" id="tipo_nomina_db_id_tipo_nomina"/>
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Tipo de Nomina</th>
	</tr>
        <tr>
			<th>Nomina:</th>
		  <td><input name="tipo_nomina_db_nombre_tipo_nomina" type="text" id="tipo_nomina_db_nombre_tipo_nomina" maxlength="60" size="30" message="Introduzca el Nombre la Nomina. Ejem: 'Nomina Mayor'" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;()]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;()]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
          <th>Frecuencia</th>
          <td><label>
            <select name="tipo_nomina_db_frecuencia" id="tipo_nomina_db_frecuencia">
            <option value="0">-- SELECCION --</option>
              <?= $opt_concepto?>
            </select>
          </label></td>
        </tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td><label>
		    <textarea name="tipo_nomina_db_comentario" id="tipo_nomina_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este Nivel Academico es...'"></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>