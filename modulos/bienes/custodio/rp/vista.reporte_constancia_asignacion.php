<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//-----------------------------------------------------------------------------------------------
$("#constancia_rp_btn_imprimir").click(function() {
	if(getObj('constancia_rp_id_custodio').value!=''){
		url = "pdf.php?p=modulos/bienes/custodio/rp/vista.lst.constancia_asignacion.php?constancia_rp_id_custodio="+getObj('constancia_rp_id_custodio').value+"@departamento="+getObj('constancia_sitio_fisico').value+"@id_departamento="+getObj('constancia_id_sitio_fisico').value;
		
		openTab("Constancia Asignación",url);
		
		
	}
	else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Tiene que Seleccionar un Custodio</p></div>",true,true);
});

//
$("#constancia_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/custodio/rp/vista.grid_custodio_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Custodio', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#acti_custodio_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/rp/sql.bienes_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#acti_custodio_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#acti_custodio_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/rp/sql.bienes_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/custodio/rp/sql.bienes_custodio_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Custodio'],
								colModel:[
									{name:'id_custodio',index:'id_custodio', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'custodio',index:'custodio', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('constancia_rp_id_custodio').value=ret.id_custodio;
									getObj('constancia_rp_custodio').value=ret.custodio;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#acti_custodio_db_nombre").focus();
								$('#acti_custodio_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_custodio',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//-------------------------------------------------
$("#constancia_btn_emergente_sitio").click(function() {									 
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/custodio/rp/vista.grid_sitio_fisico_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Departamento', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#sitio_fisico_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/rp/sql_sitio_fisico_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#unidad_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#unidad_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/rp/sql_sitio_fisico_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/custodio/rp/sql_sitio_fisico_nom.php',
								datatype: "json",
								colNames:['ID','Departamento'],
								colModel:[
									{name:'id_uni',index:'id_uni', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('constancia_sitio_fisico').value=ret.nombre;
									getObj('constancia_id_sitio_fisico').value=ret.id_uni;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#unidad_db_nombre").focus();
								$('#unidad_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	}
);
/// ******************************************************************************
$("#constancia_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
//
function limpiar(){
	getObj('constancia_rp_id_custodio').value = '';
	getObj('constancia_rp_custodio').value = '';
	getObj('constancia_sitio_fisico').value = '';
	getObj('constancia_id_sitio_fisico').value = '';
}
//
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
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
	<img id="constancia_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    
	<img id="constancia_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_rp_acti_custo" id="form_rp_acti_custo">
  <table class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Activos	por	Custodio	</th>
	</tr>
    <tr>
			<th>Custodio:</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="constancia_rp_custodio" type="text"  id="constancia_rp_custodio" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
       </li>
				<li id="constancia_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
    <tr>
      <th>Departamento: </th>
      <td>
      <ul class="input_con_emergente">
				<li>
           <input name="constancia_sitio_fisico" type="text"  id="constancia_sitio_fisico" maxlength="60" size="30" readonly="true"/>
           </li>
		  <li id="constancia_btn_emergente_sitio" class="btn_consulta_emergente"></li>
		</ul>
      </td>
    </tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
  <input type="hidden" name="constancia_rp_id_custodio" id="constancia_rp_id_custodio" />
  <label>
    <input type="hidden" name="constancia_id_sitio_fisico" id="constancia_id_sitio_fisico" />
  </label>
</form>