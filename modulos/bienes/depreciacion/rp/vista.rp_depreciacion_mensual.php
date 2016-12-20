<script>
var dialog;
//----------------------------------------------------------------------------------------------------
$("#form_reporte_consult_bien").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/depreciacion/rp/vista.grid_reporte_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Bienes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#reporte_bien_rp_codigo").val(); 
					var busq_nombre= jQuery("#reporte_bien_rp_nombre").val(); 
					var id_tipo_bien= getObj('form_reporte_depreciacion_bien_id_tipo_bien').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo+"&id_tipo_bien="+id_tipo_bien,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#reporte_bien_rp_codigo").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#reporte_bien_rp_nombre").keypress(function(key)
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
							var busq_codigo= jQuery("#reporte_bien_rp_codigo").val();
							var busq_nombre= jQuery("#reporte_bien_rp_nombre").val();
							var id_tipo_bien= getObj('form_reporte_depreciacion_bien_id_tipo_bien').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo+"&id_tipo_bien="+id_tipo_bien,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?id_tipo_bien='+getObj('form_reporte_depreciacion_bien_id_tipo_bien').value,
								datatype: "json",
								colNames:['ID','Codigo','Bien'],
								colModel:[
									{name:'id_bienes',index:'id_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_reporte_depreciacion_bien_id_bien').value = ret.id_bienes;
									getObj('form_reporte_depreciacion_bien_nombre_bien').value = ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();

								//$("#valor_impuesto_db_nombre_uni").focus();
								$('#reporte_bien_rp_codigo').alpha({allow:'0123456789 '});
								$('#reporte_bien_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});



//
//

//
//----------------------------------------------------------------

$("#form_reporte_consult_tipo_bien").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/depreciacion/rp/vista.grid_reporte_tipo_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Bien', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#reporte_bien_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/depreciacion/rp/sql_reporte_tipo_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#reporte_bien_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#reporte_bien_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/depreciacion/rp/sql_reporte_tipo_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/depreciacion/rp/sql_reporte_tipo_bien_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo','Comentario'],
								colModel:[
									{name:'id_tipo_bienes',index:'id_tipo_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_reporte_depreciacion_bien_id_tipo_bien').value=ret.id_tipo_bienes;
									getObj('form_reporte_depreciacion_bien_nombre_tipo_bien').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#reporte_bien_rp_nombre").focus();
								$('#reporte_bien_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
// ******************************************************************************
$("#form_reporte_depreciacion_bien_rp_btn_imprimir").click(function() {
url="pdfb.php?p=modulos/bienes/depreciacion/rp/vista.lst.depreciacion_mensual.php!id_tipo_bien="+getObj('form_reporte_depreciacion_bien_id_tipo_bien').value+"@id_bienes="+getObj('form_reporte_depreciacion_bien_id_bien').value;
//url = "pdfb.php?p=modulos/bienes/depreciacion/rp/vista.lst.depreciacion_mensual.php";
openTab('RP Depreciaciones Mensuales',url);
});
// ******************************************************************************
$("#form_reporte_depreciacion_bien_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
function limpiar(){
	getObj('form_reporte_depreciacion_bien_id_tipo_bien').value='';
	getObj('form_reporte_depreciacion_bien_nombre_tipo_bien').value='';
	getObj('form_reporte_depreciacion_bien_id_bien').value='';
	getObj('form_reporte_depreciacion_bien_nombre_bien').value='';
}
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#sitio_fisico_db_nombre_unidad').alpha({allow:' '});
$('#sitio_fisico_db_nombre_sitio').alpha({allow:' '});
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
	<img id="form_reporte_depreciacion_bien_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_reporte_depreciacion_bien_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 

<html> 
<head>
<title>Crear input file</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<form id="form_reporte_depreciacion_bien" name="form_reporte_depreciacion_bien" action="">
<input id="form_reporte_depreciacion_bien_id_tipo_bien" name="form_reporte_depreciacion_bien_id_tipo_bien" type="hidden">
<input id="form_reporte_depreciacion_bien_id_bien" name="form_reporte_depreciacion_bien_id_bien" type="hidden">
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="3">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:1px; align="absmiddle" /> Depreciacion Mensual de Bien(es)			</th>
	</tr>
    	<tr>
			<th>Tipo de Bien</th>
		  <td><label>
		    <input readonly="true" type="text" name="form_reporte_depreciacion_bien_nombre_tipo_bien" id="form_reporte_depreciacion_bien_nombre_tipo_bien" />
	      </label><img id="form_reporte_consult_tipo_bien" src="imagenes/iconos/view16x16.png" style="cursor:pointer; overflow:hidden"/></td>

			
  </tr>
        <tr>
			<th>Bien</th>
		  <td><label>
		    <input readonly="true" type="text" name="form_reporte_depreciacion_bien_nombre_bien" id="form_reporte_depreciacion_bien_nombre_bien" />
		    </label><img id="form_reporte_consult_bien" src="imagenes/iconos/view16x16.png" style="cursor:pointer; overflow:hidden"/>
         	
		  </td>

			
		</tr>
		<tr>
			<td colspan="3" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>
</body>
</html>