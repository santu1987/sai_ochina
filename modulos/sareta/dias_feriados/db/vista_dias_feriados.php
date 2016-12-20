<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT id_unidad_ejecutora,nombre FROM unidad_ejecutora where unidad_regional=1";
$rs_Delegaciones = $conn->Execute($sql);

while (!$rs_Delegaciones->EOF) {
	
	$opt_Delegaciones.="<option value='".$rs_Delegaciones->fields("id_unidad_ejecutora")."' >".$rs_Delegaciones->fields("nombre")."</option>";
$rs_Delegaciones->MoveNext();
}

?>

<script>
var dialog;
$("#sareta_dias_feriados_db_btn_guardar").click(function() {
	
	if(($('#form_db_dias_feriados').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/sareta/dias_feriados/db/sql.dias_feriados.php",
			data:dataForm('form_db_dias_feriados'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_dias_feriados');
					
					getObj('dias_feriados_db_fecha_ano').value="<?= date ('d/m/Y') ?>";
					getObj('dias_feriados_db_tipo').selectedIndex =0;
					getObj('dias_feriados_db_delegacion').selectedIndex =0;
					getObj('delegacion').style.display='none'; 
				}
				else if(html=="falta_delegacion")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Seleccione una Delegaci&oacute;n</p></div>",true,true);
				}
				else if(html=="ExisteN")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El d&iacute;a Feriado que Intenta Registrar Existe de Tipo Nacional</p></div>",true,true);
				}
				else if(html=="ExisteR")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El D&iacute;a Feriado que Intenta Registrar ya Existe para la Delegac&oacute;n Seleccionada</p></div>",true,true);
				}
				else if(html=="ExisteV")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El D&iacute;a Feriado que Intenta Registrar ya Existe de Tipo Variable</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
//************************************************************************
$("#sareta_dias_feriados_db_btn_consultar").click(function() {
		var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/dias_feriados/db/grid_dias_feriados.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Dias Feriados', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/dias_feriados/db/sql_grid_dias_feriados.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/dias_feriados/db/sql_grid_dias_feriados.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/dias_feriados/db/sql_grid_dias_feriados.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/dias_feriados/db/sql_grid_dias_feriados.php?nd='+nd,
								datatype: "json",
								colNames:['id','Descripci&oacute;n','des','Fecha','Tipo','Ctipo','Comentario','Com','Delegai&oacute;n','delegaion1'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'des1',index:'des1', width:220,sortable:false,resizable:false},
									{name:'des2',index:'des2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'fecha',index:'fecha', width:100,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false},
									{name:'ctipo',index:'ctipo', width:220,sortable:false,resizable:false,hidden:true},
									{name:'obs1',index:'obs1', width:150,sortable:false,resizable:false},
									{name:'obs2',index:'obs2', width:220,sortable:false,resizable:false,hidden:true},
									{name:'delegaion',index:'delegaion', width:300,sortable:false,resizable:false},
									{name:'deleg',index:'deleg', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('dias_feriados_db_id').value = ret.id;
									getObj('dias_feriados_db_nombre').value = ret.des2;
									getObj('dias_feriados_db_fecha_ano').value = ret.fecha;
									getObj('dias_feriados_db_comentario').value = ret.obs2;
									
									if(ret.ctipo=="1"){
									getObj('dias_feriados_db_tipo').selectedIndex =0;
									getObj('dias_feriados_db_delegacion').selectedIndex =0;
									getObj('delegacion').style.display='none'; 
									}else if(ret.ctipo=="2"){
										getObj('dias_feriados_db_tipo').selectedIndex =1;
										getObj('dias_feriados_db_delegacion').value =ret.deleg;
									getObj('delegacion').style.display='';
									}else if(ret.ctipo=="3"){
									getObj('dias_feriados_db_tipo').selectedIndex =2;
									getObj('dias_feriados_db_delegacion').selectedIndex =0;
									getObj('delegacion').style.display='none';
									}
									
									getObj('sareta_dias_feriados_db_btn_cancelar').style.display='';
									getObj('sareta_dias_feriados_db_btn_actualizar').style.display='';
									getObj('sareta_dias_feriados_db_btn_eliminar').style.display='';
									getObj('sareta_dias_feriados_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
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
	//--------------------------------------------------------------------------------------------------------------------------
$("#sareta_dias_feriados_db_btn_eliminar").click(function() {
  if (getObj('dias_feriados_db_id').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/dias_feriados/db/sql.eliminar.php",
			data:dataForm('form_db_dias_feriados'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_dias_feriados_db_btn_cancelar').style.display='';
					getObj('sareta_dias_feriados_db_btn_eliminar').style.display='none';
					getObj('sareta_dias_feriados_db_btn_actualizar').style.display='none';
					getObj('sareta_dias_feriados_db_btn_guardar').style.display='';
					clearForm('form_db_dias_feriados');
					getObj('dias_feriados_db_fecha_ano').value="<?= date ('d/m/Y') ?>";
					getObj('dias_feriados_db_delegacion').selectedIndex =0;
					getObj('dias_feriados_db_tipo').selectedIndex =0;
					getObj('delegacion').style.display='none'; 
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con este Dia Feriado</p></div>",true,true); 
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


	//--------------------------------------------------------------------------------------------------------------------------
$("#sareta_dias_feriados_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_db_dias_feriados').jVal())
	{
		
		$.ajax (
		{
			url: "modulos/sareta/dias_feriados/db/sql.actualizar.php",
			data:dataForm('form_db_dias_feriados'),
			type:'POST',
			cache: false,
			success: function(html)
			{	//alert(html);		
				if (html=="No Actualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}else if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					getObj('sareta_dias_feriados_db_btn_cancelar').style.display='';
					getObj('sareta_dias_feriados_db_btn_eliminar').style.display='none';
					getObj('sareta_dias_feriados_db_btn_actualizar').style.display='none';
					getObj('sareta_dias_feriados_db_btn_guardar').style.display='';
					clearForm('form_db_dias_feriados');
					getObj('dias_feriados_db_fecha_ano').value="<?= date ('d/m/Y') ?>";
					getObj('dias_feriados_db_delegacion').selectedIndex =0;
					getObj('dias_feriados_db_tipo').selectedIndex =0;
					getObj('delegacion').style.display='none'; 
				
				}
				else if(html=="NoExisteCambio")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El D&iacute;a Feriado No Sufrio Cambios</p></div>",true,true);
				}
				else if(html=="ExisteN")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El D&iacute;a Feriado que Intenta Atualizar Existe de Tipo Nacional</p></div>",true,true);
				}
				else if(html=="ExisteR")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El D&iacute;a Feriado que Intenta Atualizar ya Existe para la Delegac&oacute;n Seleccionada</p></div>",true,true);
				}
				else if(html=="ExisteV")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El D&iacute;a Feriado que Intenta Registrar ya Existe de Tipo Variable</p></div>",true,true);
				}
				else if(html=="falta_delegacion")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Seleccione una Delegaci&oacute;n</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
				
					
			}
				
		});
	}
});

	//--------------------------------------------------------------------------------------------------------------------------
$("#sareta_dias_feriados_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_dias_feriados_db_btn_cancelar').style.display='';
	getObj('sareta_dias_feriados_db_btn_eliminar').style.display='none';
	getObj('sareta_dias_feriados_db_btn_actualizar').style.display='none';
	getObj('sareta_dias_feriados_db_btn_guardar').style.display='';
	clearForm('form_db_dias_feriados');
	getObj('dias_feriados_db_fecha_ano').value="<?= date ('d/m/Y') ?>";
	getObj('dias_feriados_db_delegacion').selectedIndex =0;
	getObj('dias_feriados_db_tipo').selectedIndex =0;
	getObj('delegacion').style.display='none'; 
	
});
//---------------------------------------------------------------------------------------------------------------------------------
function ver_delegacion()
{
	
		if(getObj('dias_feriados_db_tipo').value=="1" || getObj('dias_feriados_db_tipo').value=="3")
		{
			
			getObj('dias_feriados_db_delegacion').selectedIndex =0;
			getObj('delegacion').style.display='none'; 
					
		}else if(getObj('dias_feriados_db_tipo').value=="2" )
		{
		getObj('dias_feriados_db_delegacion').value=0;
			getObj('delegacion').style.display=''; 

		}
}

	//--------------------------------------------------------------------------------------------------------------------------
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
	
	$('#dias_feriados_db_nombre').alpha({allow:'- 0123456789áéíóúÁÉÍÓÚñÑ'});
	
</script>


<div id="botonera">
	<img id="sareta_dias_feriados_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
  <img id="sareta_dias_feriados_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_dias_feriados_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="sareta_dias_feriados_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
<img id="sareta_dias_feriados_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" /></div>


<form name="form_db_dias_feriados" id="form_db_dias_feriados">
<input type="hidden" name="dias_feriados_db_id" id="dias_feriados_db_id">
	<table class="cuerpo_formulario">
        <tr>
            <th colspan="2" class="titulo_frame"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Dia Feriado</th>
        </tr>
        <tr>
			<th>Nombre:</th>
			<td>
			
            
            <input name="dias_feriados_db_nombre" type="text" class="style4" id="dias_feriados_db_nombre"  size="64" maxlength="60"  
						message="Introduzca la Nombre para el Dia Feriado" 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Nombre de Dia Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Nombre de Dia: '+$(this).val()]}" />
         
			</td>
		</tr>
        <tr>
			<th>Fecha :	</th>
			<td><input name="dias_feriados_db_fecha_ano" type="text" id="dias_feriados_db_fecha_ano" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca la Fecha para el Dia Feriado">
		  <button type="reset" id="dias_feriados_db_fecha_ano_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "dias_feriados_db_fecha_ano",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "dias_feriados_db_fecha_ano_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
			</td>
		</tr>
        <tr>
			<th>Tipo :			</th>
			<td>	
				<select name="dias_feriados_db_tipo" id="dias_feriados_db_tipo" style="width:100px; min-width:100px;" onchange="ver_delegacion();">
					<option value="1">NACIONAL</option>
					<option value="2">REGIONAL</option>
                    <option value="3">VARIABLE</option>
				</select>
			</td>
		</tr>
        <tr id="delegacion" style="display:none;">
        	<th>Delegacion:</th>
            <td>	
				<select name="dias_feriados_db_delegacion" id="dias_feriados_db_delegacion"  style="width:349px; min-width:349px;" >
					<option value="0">---- SELECCIONE -----</option>
					<?=strtoupper($opt_Delegaciones);?>
				</select>
			</td>
     	</tr>
        <tr>
			<th>Comentario :			</th>
			<td>	<textarea name="dias_feriados_db_comentario" cols="60" id="dias_feriados_db_comentario" message="Introduzca un Comentario"></textarea></td>
		</tr>
        <tr>
            <td colspan="2" class="bottom_frame">&nbsp;
        <tr>			
       </table>
        <span class="bottom_frame"><span class="titulo_frame">
        </span></span>
</form>  